<?php

namespace App\Http\Controllers;

use App\Models\Ptk;
use App\Models\Kota;
use App\Models\Sekolah;
use App\Models\JenisPtk;
use App\Models\PangkatGolongan;
use App\Models\PangkatJabatan;
use App\Models\Agama;
use App\Models\JenjangPendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

class PtkEditController extends Controller
{
    /**
     * Menampilkan halaman edit PTK
     */
    public function edit($encode_kegiatan_id, $nip)
    {
        // Validasi decode kegiatan_id
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            abort(404, 'ID kegiatan tidak valid');
        }

        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        // Cari PTK berdasarkan NIP
        $ptk = Ptk::with(['pangkatJabatan', 'kota', 'sekolah', 'jenisPtk', 'pangkatGolongan'])
            ->where('nip', $nip)
            ->first();

        if (!$ptk) {
            return redirect()->route('lockscreen', ['encode_kegiatan_id' => $encode_kegiatan_id])
                ->with('error', 'Data PTK tidak ditemukan');
        }

        // Ambil data untuk dropdown
        $jenisPtk = JenisPtk::orderBy('jenis_ptk')->get();
        $pangkatGolongans = PangkatGolongan::orderBy('pangkat_golongan_id')->get();
        $pangkatJabatans = PangkatJabatan::orderBy('pangkat_jabatan_id')->get();
        $kotas = Kota::orderBy('nama_kota')->get();
        // Ambil sekolah yang dipilih PTK + 100 sekolah lainnya
        $selectedSekolahId = $ptk->sekolah_id;
        $sekolahs = Sekolah::where('sekolah_id', $selectedSekolahId)
            ->orWhere(function ($query) {
                $query->orderBy('nama_sekolah')->limit(100);
            })
            ->orderBy('nama_sekolah')
            ->get();

        $jenjangs = JenjangPendidikan::get();
        $agamas = Agama::orderByRaw("CASE WHEN nama_agama = 'Islam' THEN 0 ELSE 1 END, nama_agama ASC")->get();

        return view('ptk.edit', [
            'title' => 'Edit Biodata PTK',
            'encode_kegiatan_id' => $encode_kegiatan_id,
            'kegiatan_id' => $kegiatan_id,
            'ptk' => $ptk,
            'jenisPtk' => $jenisPtk,
            'pangkatGolongans' => $pangkatGolongans,
            'pangkatJabatans' => $pangkatJabatans,
            'kotas' => $kotas,
            'sekolahs' => $sekolahs,
            'jenjangs' => $jenjangs,
            'agamas' => $agamas
        ]);
    }

    /**
     * Update data PTK di database lokal dan API Dapodik
     */
    public function update(Request $request, $encode_kegiatan_id, $nip)
    {
        // Validasi decode kegiatan_id
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'ID kegiatan tidak valid'
            ]);
        }

        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        // Cari PTK berdasarkan NIK (bukan NIP lagi)
        $ptk = Ptk::where('nik', $request->nik)->first();
        if (!$ptk) {
            return response()->json([
                'success' => false,
                'message' => 'Data PTK tidak ditemukan'
            ]);
        }

        // Validasi input
        $request->validate([
            'nip' => 'required|max:18',  // NIP sekarang bisa diedit
            'nik' => 'required|max:16',  // NIK tetap ada, tapi tidak berubah
            'nama' => 'required|max:200',
            'nuptk' => 'nullable|max:19',
            'npwp' => 'nullable|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|max:45',
            'tgl_lahir' => 'required|date',
            'jenis_ptk_id' => 'nullable|exists:jenis_ptk,jenis_ptk_id',
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongan,pangkat_golongan_id',
            'pangkat_jabatan_id' => 'required|exists:pangkat_jabatan,pangkat_jabatan_id',
            'email' => 'required|email|max:100',
            'no_hp' => 'required|max:16',
            'agama' => 'required|max:45',
            'pendidikan' => 'nullable|max:100',
            'alamat_rumah' => 'nullable|max:200',
            'kota_id' => 'nullable|exists:kota,kota_id',
            'sekolah_id' => 'nullable',
            'instansi' => 'nullable|max:100',
            'alamat_kantor' => 'nullable|max:200',
            'jenjang_pendidikan_id' => 'nullable|exists:jenjang_pendidikan,jenjang_pendidikan_id',
        ]);

        // Validasi: minimal salah satu sekolah_id atau instansi harus diisi
        if (!$request->sekolah_id && !$request->instansi) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih sekolah atau isi instansi manual'
            ]);
        }

        try {
            // Data yang akan diupdate (NIK TIDAK DIUPDATE - readonly)
            $updateData = [
                'nip' => $request->nip,  // NIP sekarang bisa diupdate
                'nama' => $request->nama,
                // 'nik' => $request->nik,  // NIK TIDAK DIUPDATE - HAPUS BARIS INI
                'nuptk' => $request->nuptk,
                'npwp' => $request->npwp,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'jenis_ptk_id' => $request->jenis_ptk_id,
                'pangkat_golongan_id' => $request->pangkat_golongan_id,
                'pangkat_jabatan_id' => $request->pangkat_jabatan_id,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'agama' => $request->agama,
                'pendidikan' => $request->pendidikan,
                'alamat_rumah' => $request->alamat_rumah,
                'kota_id' => $request->kota_id,
                'sekolah_id' => $request->sekolah_id,
                'jenjang_pendidikan_id' => $request->jenjang_pendidikan_id,
                'instansi' => $request->instansi,
                'alamat_kantor' => $request->alamat_kantor,
                'last_update' => now()
            ];

            // Update di database lokal
            $ptk->update($updateData);

            // Siapkan data untuk sync ke API Dapodik
            $apiData = array_merge($updateData, [
                'nik' => $ptk->nik,  // NIK tetap dari database (tidak berubah)
                'tgl_lahir' => $request->tgl_lahir,
                'no_hp' => $request->no_hp,
            ]);

            // Sinkronisasi ke API Dapodik
            $apiSyncResult = ['success' => true, 'message' => 'Tidak perlu sync ke API'];

            try {
                // $apiLockscreen = new ApiLockscreenController();
                // $apiSyncResult = $apiLockscreen->syncPtkToDapodik($apiData);

                // Log::info('PtkEditController: Hasil sync PTK ke Dapodik', [
                //     'nik' => $ptk->nik,
                //     'success' => $apiSyncResult['success'],
                //     'action' => $apiSyncResult['action'] ?? null,
                //     'message' => $apiSyncResult['message'] ?? null,
                // ]);
            } catch (\Exception $e) {
                Log::error('PtkEditController: Gagal sync PTK ke Dapodik', [
                    'nik' => $ptk->nik,
                    'error' => $e->getMessage(),
                ]);
                $apiSyncResult = ['success' => false, 'message' => 'Gagal sync ke API: ' . $e->getMessage()];
            }

            // Ambil data PTK yang sudah diupdate dengan relasi
            $updatedPtk = Ptk::with(['pangkatJabatan', 'kota', 'sekolah', 'jenisPtk', 'pangkatGolongan'])
                ->where('nik', $ptk->nik)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Data PTK berhasil diperbarui',
                'api_sync' => $apiSyncResult,
                'data' => $updatedPtk
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating PTK: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk search sekolah (untuk autocomplete di form edit)
     */
    public function searchSekolah(Request $request)
    {
        $keyword = $request->keyword;

        if (!$keyword || strlen($keyword) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Masukkan minimal 2 karakter untuk pencarian'
            ]);
        }

        try {
            $sekolahs = Sekolah::where('nama_sekolah', 'like', '%' . $keyword . '%')
                ->orWhere('npsn', 'like', '%' . $keyword . '%')
                ->orWhere('alamat', 'like', '%' . $keyword . '%')
                ->orderBy('nama_sekolah')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $sekolahs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk search sekolah dari Dapodik (sama persis dengan lockscreen)
     */
    public function searchSekolahDapodikApi(Request $request)
    {
        $keyword = $request->query('keyword');

        // Debug log
        \Illuminate\Support\Facades\Log::info('PtkEditController: Search Dapodik called', ['keyword' => $keyword]);

        if (!$keyword || strlen($keyword) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Keyword minimal 2 karakter',
                'data' => [],
            ]);
        }

        try {
            // Panggil ApiLockscreenController yang sama seperti di lockscreen
            $apiLockscreen = new ApiLockscreenController();
            $result = $apiLockscreen->searchSekolahDapodik($request);

            // Log hasil untuk debugging
            \Illuminate\Support\Facades\Log::info('PtkEditController: Search Dapodik result', ['result' => $result]);

            return $result;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PtkEditController: Search Dapodik error', ['error' => $e->getMessage()]);

            // Fallback ke database lokal jika API gagal
            return $this->searchSekolahLokalFallback($request);
        }
    }

    /**
     * Fallback search dari database lokal (sama seperti di lockscreen)
     */
    private function searchSekolahLokalFallback(Request $request)
    {
        $keyword = $request->query('keyword');

        if (!$keyword || strlen($keyword) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Keyword minimal 2 karakter',
                'data' => [],
            ]);
        }

        try {
            $sekolahs = Sekolah::where('nama_sekolah', 'like', '%' . $keyword . '%')
                ->orWhere('npsn', 'like', '%' . $keyword . '%')
                ->orWhere('alamat', 'like', '%' . $keyword . '%')
                ->orderBy('nama_sekolah')
                ->limit(50)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $sekolahs,
                'source' => 'local'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
