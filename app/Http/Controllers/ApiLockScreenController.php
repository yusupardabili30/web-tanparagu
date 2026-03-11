<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApiLockscreenController extends Controller
{
    // =========================================================================
    // LOGIN API
    // =========================================================================
    private function loginToApi(): array
    {
        $baseUrl  = config('api.base_url');
        $email    = config('api.email');
        $password = config('api.password');

        try {
            $response = Http::timeout(30)->post($baseUrl . '/login', [
                'email'    => $email,
                'password' => $password,
            ]);

            if ($response->status() !== 200) {
                Log::error('ApiLockscreen: Gagal login', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return ['success' => false, 'message' => 'Gagal login ke API Dapodik'];
            }

            $token = $response->json('token');
            if (!$token) {
                return ['success' => false, 'message' => 'Token tidak ditemukan di response'];
            }

            return ['success' => true, 'token' => $token];
        } catch (\Exception $e) {
            Log::error('ApiLockscreen: Exception saat login', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Gagal terhubung ke API: ' . $e->getMessage()];
        }
    }

    // =========================================================================
    // FETCH PTK DARI API
    // =========================================================================
    private function fetchPtkFromApi(string $token, string $searchKey): ?array
    {
        $baseUrl = config('api.base_url');

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get($baseUrl . '/ptk/' . urlencode($searchKey));

            Log::info('ApiLockscreen: fetchPtk', [
                'searchKey' => $searchKey,
                'status'    => $response->status(),
            ]);

            if ($response->status() !== 200) return null;

            $data = $response->json();

            if (empty($data) || (!isset($data['nama']) && !isset($data['nip']) && !isset($data['nik']))) {
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            Log::warning('ApiLockscreen: fetchPtk error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    // =========================================================================
    // FETCH SEKOLAH DARI API
    // =========================================================================
    private function fetchSekolahFromApi(string $token, string $searchKey): ?array
    {
        $baseUrl = config('api.base_url');

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get($baseUrl . '/sekolah/' . urlencode($searchKey));

            Log::info('ApiLockscreen: fetchSekolah', [
                'searchKey' => $searchKey,
                'status'    => $response->status(),
            ]);

            if ($response->status() !== 200) return null;

            $data = $response->json();

            return (is_array($data) && !empty($data)) ? $data : null;
        } catch (\Exception $e) {
            Log::warning('ApiLockscreen: fetchSekolah error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    // =========================================================================
    // FORMAT DATA SEKOLAH DARI API
    // =========================================================================
    private function formatSekolahData($sekolah): array
    {
        $get = function ($key) use ($sekolah) {
            if (is_array($sekolah)) return $sekolah[$key] ?? null;
            return $sekolah->$key   ?? null;
        };

        return [
            'sekolah_id'        => $get('sekolah_id'),
            'npsn'              => $get('npsn'),
            'nama_sekolah'      => $get('nama_sekolah') ?? $get('nama'),
            'jenjang'           => $get('jenjang'),
            'bentuk_pendidikan' => $get('bentuk_pendidikan'),
            'status_sekolah'    => $get('status_sekolah'),
            'akreditasi'        => $get('akreditasi'),
            'alamat'            => $get('alamat'),
            'kab_kota'          => $get('kab_kota') ?? $get('kota'),
            'kecamatan'         => $get('kecamatan'),
            'desa'              => $get('desa'),
            'akses_internet'    => $get('akses_internet'),
            'source'            => 'dapodik',
        ];
    }

    // =========================================================================
    // RESOLVE SEKOLAH DARI API — ambil entry pertama yang valid
    // =========================================================================
    private function resolveSekolahDariApi(string $token, string $keyword): ?array
    {
        if (empty(trim($keyword))) return null;

        $data = $this->fetchSekolahFromApi($token, $keyword);
        if (!$data) return null;

        // Response bisa single object atau array of objects
        $list = (is_array($data) && isset($data[0])) ? $data : [$data];

        foreach ($list as $item) {
            $formatted = $this->formatSekolahData($item);
            if (!empty($formatted['nama_sekolah'])) {
                Log::info('ApiLockscreen: Sekolah ditemukan dari API', [
                    'keyword'      => $keyword,
                    'nama_sekolah' => $formatted['nama_sekolah'],
                ]);
                return $formatted;
            }
        }

        return null;
    }

    // =========================================================================
    // RESOLVE SEKOLAH UNTUK PTK LOKAL
    // Cari ke API pakai sekolah_id atau instansi dari data PTK
    // =========================================================================
    private function resolveSekolahForLocalPtk($ptk, string $token): ?array
    {
        Log::info('ApiLockscreen: resolveSekolahForLocalPtk', [
            'ptk_nip'    => $ptk->nip,
            'sekolah_id' => $ptk->sekolah_id ?? null,
            'instansi'   => $ptk->instansi   ?? null,
        ]);

        // Cari pakai sekolah_id
        if (!empty($ptk->sekolah_id)) {
            $result = $this->resolveSekolahDariApi($token, (string) $ptk->sekolah_id);
            if ($result) return $result;
        }

        // Fallback: cari pakai nama instansi
        if (!empty($ptk->instansi)) {
            $result = $this->resolveSekolahDariApi($token, $ptk->instansi);
            if ($result) return $result;
        }

        Log::info('ApiLockscreen: Sekolah tidak ditemukan untuk PTK lokal', [
            'ptk_nip' => $ptk->nip,
        ]);

        return null;
    }

    // =========================================================================
    // RESOLVE SEKOLAH DARI DATA PTK API
    // Coba field sekolah_id/npsn (exact) lalu nama (string)
    // ID/NPSN dipisah dari nama agar angka kecil tidak cocok ke LIKE yang salah
    // =========================================================================
    private function resolveSekolahFromPtkData(string $token, array $ptkData): ?array
    {
        // Kandidat ID/NPSN — exact match di API
        $idCandidates = array_filter([
            isset($ptkData['sekolah_id']) && $ptkData['sekolah_id'] !== '' ? (string)$ptkData['sekolah_id'] : null,
            isset($ptkData['npsn'])       && $ptkData['npsn']       !== '' ? (string)$ptkData['npsn']       : null,
        ]);

        // Kandidat nama — string search di API
        $nameCandidates = array_filter([
            $ptkData['nama_sekolah']      ?? null,
            $ptkData['instansi']          ?? null,
            $ptkData['nama_instansi']     ?? null,
            $ptkData['satuan_pendidikan'] ?? null,
            $ptkData['unit_kerja']        ?? null,
            $ptkData['sekolah']           ?? null,
            $ptkData['asal_sekolah']      ?? null,
        ]);

        Log::info('ApiLockscreen: resolveSekolahFromPtkData', [
            'id_candidates'   => array_values($idCandidates),
            'name_candidates' => array_values($nameCandidates),
        ]);

        // Cari pakai ID/NPSN dulu (lebih akurat)
        foreach ($idCandidates as $id) {
            $result = $this->resolveSekolahDariApi($token, $id);
            if ($result) return $result;
        }

        // Cari pakai nama
        foreach ($nameCandidates as $nama) {
            $nama = trim((string) $nama);
            if (empty($nama)) continue;
            $result = $this->resolveSekolahDariApi($token, $nama);
            if ($result) return $result;
        }

        Log::info('ApiLockscreen: Sekolah tidak ditemukan dari data PTK API');
        return null;
    }

    // =========================================================================
    // SEARCH SEKOLAH — hanya dari API, tidak ada lokal
    // GET /lockscreen/api/search-sekolah-dapodik?keyword=xxx
    // =========================================================================
    public function searchSekolahDapodik(Request $request)
    {
        $keyword = $request->query('keyword');

        if (!$keyword || strlen($keyword) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Keyword minimal 2 karakter',
                'data'    => [],
            ]);
        }

        Log::info('ApiLockscreen: searchSekolahDapodik', ['keyword' => $keyword]);

        $loginResult = $this->loginToApi();

        if (!$loginResult['success']) {
            return response()->json([
                'success' => false,
                'message' => $loginResult['message'],
                'data'    => [],
            ]);
        }

        $sekolahApi = $this->fetchSekolahFromApi($loginResult['token'], $keyword);

        if (!$sekolahApi) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => '0 sekolah ditemukan',
            ]);
        }

        $list = is_array($sekolahApi) && isset($sekolahApi[0]) ? $sekolahApi : [$sekolahApi];

        $results = [];
        foreach ($list as $item) {
            $formatted = $this->formatSekolahData($item);
            if (!empty($formatted['nama_sekolah'])) {
                $results[] = $formatted;
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $results,
            'message' => count($results) . ' sekolah ditemukan',
        ]);
    }

    // =========================================================================
    // CEK NIP
    // =========================================================================
    public function cekNip(Request $request)
    {
        $nip = $request->query('nip');
        if (!$nip) return response()->json(['success' => false, 'message' => 'NIP tidak boleh kosong']);

        Log::info('ApiLockscreen: cekNip', ['nip' => $nip]);

        // Login API sekali, dipakai untuk PTK dan sekolah
        $loginResult = $this->loginToApi();
        if (!$loginResult['success']) {
            return response()->json(['success' => false, 'message' => $loginResult['message']]);
        }
        $token = $loginResult['token'];

        // Cek PTK di DB lokal dulu
        $ptkLokal = \App\Models\Ptk::where('nip', $nip)->first();

        if ($ptkLokal) {
            Log::info('ApiLockscreen: NIP ditemukan di DB lokal');
            $sekolahData = $this->resolveSekolahForLocalPtk($ptkLokal, $token);

            Log::info('ApiLockscreen: cekNip lokal - sekolah', [
                'found' => !is_null($sekolahData),
                'nama'  => $sekolahData['nama_sekolah'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'source'  => 'lokal',
                'data'    => $this->formatAutofillDataFromLokal($ptkLokal),
                'sekolah' => $sekolahData,
                'message' => 'Data ditemukan dari database lokal',
            ]);
        }

        // Cek PTK di API
        $apiData = $this->fetchPtkFromApi($token, $nip);
        if (!$apiData) {
            return response()->json(['success' => false, 'message' => 'Data NIP tidak ditemukan']);
        }

        $sekolahData = $this->resolveSekolahFromPtkData($token, $apiData);

        Log::info('ApiLockscreen: cekNip dapodik - sekolah', [
            'found' => !is_null($sekolahData),
            'nama'  => $sekolahData['nama_sekolah'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'source'  => 'dapodik',
            'data'    => $this->formatAutofillData($apiData),
            'sekolah' => $sekolahData,
            'message' => 'Data ditemukan dari API Dapodik',
        ]);
    }

    // =========================================================================
    // CEK NIK
    // =========================================================================
    public function cekNik(Request $request)
    {
        $nik = $request->query('nik');
        if (!$nik) return response()->json(['success' => false, 'message' => 'NIK tidak boleh kosong']);

        if (!preg_match('/^\d{16}$/', $nik)) {
            return response()->json(['success' => false, 'message' => 'NIK harus 16 digit angka']);
        }

        Log::info('ApiLockscreen: cekNik', ['nik' => $nik]);

        $loginResult = $this->loginToApi();
        if (!$loginResult['success']) {
            return response()->json(['success' => false, 'message' => $loginResult['message']]);
        }
        $token = $loginResult['token'];

        $ptkLokal = \App\Models\Ptk::where('nik', $nik)->first();

        if ($ptkLokal) {
            Log::info('ApiLockscreen: NIK ditemukan di DB lokal');
            $sekolahData = $this->resolveSekolahForLocalPtk($ptkLokal, $token);

            return response()->json([
                'success' => true,
                'source'  => 'lokal',
                'data'    => $this->formatAutofillDataFromLokal($ptkLokal),
                'sekolah' => $sekolahData,
                'message' => 'Data ditemukan dari database lokal',
            ]);
        }

        $apiData = $this->fetchPtkFromApi($token, $nik);
        if (!$apiData) {
            return response()->json(['success' => false, 'message' => 'Data NIK tidak ditemukan']);
        }

        $sekolahData = $this->resolveSekolahFromPtkData($token, $apiData);

        return response()->json([
            'success' => true,
            'source'  => 'dapodik',
            'data'    => $this->formatAutofillData($apiData),
            'sekolah' => $sekolahData,
            'message' => 'Data ditemukan dari API Dapodik',
        ]);
    }

    // =========================================================================
    // FORMAT AUTOFILL DATA DARI API
    // =========================================================================
    private function formatAutofillData(array $data): array
    {
        $tanggalLahir = null;
        if (!empty($data['tanggal_lahir'])) {
            try {
                $tanggalLahir = Carbon::parse($data['tanggal_lahir'])->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalLahir = $data['tanggal_lahir'];
            }
        }

        return [
            'nuptk'         => $data['nuptk']        ?? null,
            'nik'           => $data['nik']           ?? null,
            'nip'           => $data['nip']           ?? null,
            'jenis_kelamin' => $this->formatJenisKelamin($data['jenis_kelamin'] ?? null),
            'tgl_lahir'     => $tanggalLahir,
            'agama'         => $data['agama']         ?? null,
            'email'         => $data['email']         ?? null,
            'no_hp'         => $data['no_telepon']    ?? $data['no_hp'] ?? null,
            'nama'          => $data['nama']          ?? $data['nama_ptk'] ?? $data['nama_lengkap'] ?? null,
            'tempat_lahir'  => $data['tempat_lahir']  ?? $data['tmp_lahir'] ?? null,
        ];
    }

    private function formatJenisKelamin($jk): ?string
    {
        if (!$jk) return null;
        $jk = strtoupper(trim($jk));
        if (in_array($jk, ['L', 'P']))                          return $jk;
        if (in_array($jk, ['LAKI-LAKI', 'LAKI', 'LAKI LAKI'])) return 'L';
        if (in_array($jk, ['PEREMPUAN', 'WANITA']))             return 'P';
        return null;
    }

    // =========================================================================
    // FORMAT AUTOFILL DATA DARI DB LOKAL
    // =========================================================================
    private function formatAutofillDataFromLokal($ptk): array
    {
        $tanggalLahir = null;
        if ($ptk->tgl_lahir) {
            try {
                $tanggalLahir = Carbon::parse($ptk->tgl_lahir)->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalLahir = $ptk->tgl_lahir;
            }
        }

        return [
            'nuptk'         => $ptk->nuptk        ?? null,
            'nik'           => $ptk->nik           ?? null,
            'nip'           => $ptk->nip           ?? null,
            'jenis_kelamin' => $ptk->jenis_kelamin ?? null,
            'tgl_lahir'     => $tanggalLahir,
            'agama'         => $ptk->agama         ?? null,
            'email'         => $ptk->email         ?? null,
            'no_hp'         => $ptk->no_hp         ?? null,
            'nama'          => $ptk->nama          ?? null,
            'tempat_lahir'  => $ptk->tempat_lahir  ?? null,
        ];
    }
}
