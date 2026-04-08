<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Ptk;
use App\Models\PangkatJabatan;
use App\Models\Kota;
use App\Models\JenisPtk; // TAMBAHKAN INI
use App\Models\PangkatGolongan; // TAMBAHKAN INI
use App\Models\Agama;
use App\Models\JenjangPendidikan;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class LockScreenController extends Controller
{
    public function index($encode_kegiatan_id)
    {
        // $encode_kegiatan_id adalah kegiatan_id yang sudah di-encode
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            // Tampilkan halaman kegiatan tidak aktif untuk semua kasus error
            return view('errors.inactive-kegiatan', [
                'title' => 'Akses Ditolak',
                'message' => 'Link tidak valid atau kegiatan tidak ditemukan.'
            ]);
        }

        // Decode untuk mendapatkan kegiatan_id asli
        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'Active')->first();

        if (!$kegiatan) {
            // Tampilkan halaman kegiatan tidak aktif
            return view('errors.inactive-kegiatan', [
                'title' => 'Kegiatan Tidak Aktif',
                'message' => 'Kegiatan sudah tidak aktif atau tidak ditemukan.'
            ]);
        }


        // TAMBAHKAN DATA JENIS PTK DAN PANGKAT GOLONGAN
        $jenisPtk = JenisPtk::orderBy('jenis_ptk')->get();
        $pangkatGolongans = PangkatGolongan::orderBy('pangkat_golongan_id')->get();

        // Ambil data untuk dropdown
        $pangkatJabatans = PangkatJabatan::orderBy('pangkat_jabatan_id')->get();
        $kotas = Kota::orderBy('nama_kota')->get();

        // Ambil 10 sekolah pertama untuk inisialisasi
        $sekolahs = Sekolah::orderBy('nama_sekolah')->limit(100)->get();
        $jenjangs = JenjangPendidikan::get();
        // // AMBIL DATA AGAMA DARI DATABASE
        // $agamas = Agama::orderBy('nama_agama')->get();
        // GANTI DENGAN INI: Urutkan Islam pertama, sisanya alfabet
        $agamas = Agama::orderByRaw("CASE WHEN nama_agama = 'Islam' THEN 0 ELSE 1 END, nama_agama ASC")->get();

        return view('lockscreen.index', [
            'title' => 'Lock Screen',
            'encode_kegiatan_id' => $encode_kegiatan_id,
            'kegiatan_id' => $kegiatan_id,
            'kegiatan' => $kegiatan,
            'pangkatJabatans' => $pangkatJabatans,
            'kotas' => $kotas,
            'sekolahs' => $sekolahs,
            'jenisPtk' => $jenisPtk, // TAMBAHKAN INI
            'pangkatGolongans' => $pangkatGolongans,
            'jenjangs' => $jenjangs,  // TAMBAHKAN INI
            'agamas' => $agamas
        ]);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'token' => 'required',
            'kegiatan_id' => 'required',
            'is_nik' => 'nullable|boolean'
        ]);

        $kegiatan_id = $request->kegiatan_id;
        $isNIK = $request->boolean('is_nik');
        $identifier = $request->identifier;

        \Log::info('Login attempt', [
            'identifier' => $identifier,
            'length' => strlen($identifier),
            'is_nik' => $isNIK
        ]);

        // Cari kegiatan yang aktif
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'Active')
            ->first();

        if (!$kegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Kegiatan tidak aktif atau tidak ditemukan'
            ]);
        }

        // Verifikasi token
        if ($request->token !== $kegiatan->instrumen_token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid'
            ]);
        }

        // Jika login dengan NIK
        if ($isNIK) {
            // Cari PTK berdasarkan NIK
            $ptk = Ptk::where('nik', $identifier)->first();

            if ($ptk) {
                // NIK ditemukan di database lokal
                if ($ptk->nip) {
                    // PTK punya NIP, suruh login dengan NIP
                    return response()->json([
                        'success' => false,
                        'use_nip_instead' => true,
                        'nik' => $ptk->nik,
                        'nip' => $ptk->nip,
                        'message' => 'NIK sudah terdaftar. Silakan login menggunakan NIP.'
                    ]);
                } else {
                    // PTK tidak punya NIP - TIDAK DIIZINKAN LOGIN DENGAN NIK
                    // Tetap suruh login dengan NIP (tapi NIP tidak ada)
                    return response()->json([
                        'success' => false,
                        'message' => 'Data dengan NIK ini tidak memiliki NIP. Silakan hubungi administrator untuk mendapatkan NIP.'
                    ]);
                }
            } else {
                // NIK tidak ditemukan, tampilkan modal registrasi
                return response()->json([
                    'success' => false,
                    'show_register_modal' => true,
                    'identifier' => $identifier,
                    'kegiatan_id' => $kegiatan_id,
                    'token' => $request->token
                ]);
            }
        }
        // Login dengan NIP
        else {
            // Cari PTK berdasarkan NIP
            $ptk = Ptk::where('nip', $identifier)->first();

            if (!$ptk) {
                return response()->json([
                    'success' => false,
                    'show_register_modal' => true,
                    'identifier' => $identifier,
                    'kegiatan_id' => $kegiatan_id,
                    'token' => $request->token
                ]);
            }

            // VALIDASI jenis PTK
            if ($ptk->jenis_ptk_id && $kegiatan->entity) {
                $jenisPtk = JenisPtk::find($ptk->jenis_ptk_id);
                if ($jenisPtk && $jenisPtk->jenis_ptk !== $kegiatan->entity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maaf, Anda tidak dapat mengikuti kegiatan ini. Jenis PTK Anda (' . $jenisPtk->jenis_ptk . ') tidak sesuai dengan kategori kegiatan (' . $kegiatan->entity . ').'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data PTK tidak lengkap. Silakan hubungi administrator.'
                ]);
            }

            $encoded_kegiatan_id = Hashids::encode($kegiatan_id);

            return response()->json([
                'success' => true,
                'redirect_url' => route('ptk.show', [
                    'encode_kegiatan_id' => $encoded_kegiatan_id,
                    'nip' => $ptk->nip
                ])
            ]);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:ptk,nip',
            'nik' => 'nullable|max:16',
            'nuptk' => 'nullable|max:19',
            'npwp' => 'nullable|max:20',
            'nama' => 'required|max:200',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|max:45',
            'tgl_lahir' => 'required|date',
            'pangkat_jabatan_id' => 'required|exists:pangkat_jabatan,pangkat_jabatan_id',
            'jenis_ptk_id' => 'nullable|exists:jenis_ptk,jenis_ptk_id', // TAMBAHKAN VALIDASI
            'pangkat_golongan_id' => 'nullable|exists:pangkat_golongan,pangkat_golongan_id', // TAMBAHKAN VALIDASI
            'email' => 'required|email|max:100',
            'no_hp' => 'required|max:16',
            'agama' => 'nullable|max:45',
            'pendidikan' => 'nullable|max:100',
            'alamat_rumah' => 'nullable|max:200',
            'kota_id' => 'nullable|exists:kota,kota_id',
            // 'sekolah_id' => 'nullable|exists:sekolah,sekolah_id',
            'sekolah_id' => 'nullable',
            'instansi' => 'nullable|max:100',
            'alamat_kantor' => 'nullable|max:200',
            'no_rekening' => 'nullable|max:45',
            'kegiatan_id' => 'required|integer',
            'jenjang_pendidikan_id' => 'nullable|exists:jenjang_pendidikan,jenjang_pendidikan_id', // kegiatan_id asli
            'token' => 'required'
        ]);

        $kegiatan_id = $request->kegiatan_id;

        // Verifikasi token kegiatan
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
            ->where('instrumen_token', $request->token)
            ->where('status', 'Active')
            ->first();

        if (!$kegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau kegiatan tidak aktif'
            ]);
        }

        // Validasi: minimal salah satu sekolah_id atau instansi harus diisi
        if (!$request->sekolah_id && !$request->instansi) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih sekolah atau isi instansi manual'
            ]);
        }

        try {
            // Buat data PTK baru
            $ptk = Ptk::create([
                'nip' => $request->nip,
                'nik' => $request->nik,
                'nuptk' => $request->nuptk,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'pangkat_jabatan_id' => $request->pangkat_jabatan_id,
                'jenis_ptk_id' => $request->jenis_ptk_id, // TAMBAHKAN INI
                'pangkat_golongan_id' => $request->pangkat_golongan_id, // TAMBAHKAN INI
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'npwp' => $request->npwp,
                'agama' => $request->agama,
                'pendidikan' => $request->pendidikan,
                'alamat_rumah' => $request->alamat_rumah,
                'kota_id' => $request->kota_id,
                'sekolah_id' => $request->sekolah_id,
                'jenjang_pendidikan_id' => $request->jenjang_pendidikan_id,
                'instansi' => $request->instansi,
                'alamat_kantor' => $request->alamat_kantor,
                'no_rekening' => $request->no_rekening,
                'last_update' => now()
            ]);

            // Sinkronisasi PTK ke API Dapodik
            try {
                $apiLockscreen = new \App\Http\Controllers\ApiLockScreenController();
                $syncResult    = $apiLockscreen->syncPtkToDapodik($request->all());

                \Log::info('LockScreen: Hasil sync PTK ke Dapodik', [
                    'nip'     => $request->nip,
                    'success' => $syncResult['success'],
                    'action'  => $syncResult['action']  ?? null,
                    'message' => $syncResult['message'] ?? null,
                ]);
            } catch (\Exception $e) {
                \Log::error('LockScreen: Gagal sync PTK ke Dapodik', [
                    'nip'   => $request->nip,
                    'error' => $e->getMessage(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan login dengan NIP Anda.',
                'nip'     => $request->nip
            ]);
        } catch (\Exception $e) {
            \Log::error('Error registering PTK: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
    // API untuk search sekolah
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
}
