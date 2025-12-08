<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Ptk;
use App\Models\PangkatJabatan;
use App\Models\Kota;
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

        // Ambil data untuk dropdown
        $pangkatJabatans = PangkatJabatan::orderBy('pangkat_jabatan_id')->get(); 
        $kotas = Kota::orderBy('nama_kota')->get();

        // Ambil 10 sekolah pertama untuk inisialisasi
        $sekolahs = Sekolah::orderBy('nama_sekolah')->limit(100)->get();

        return view('lockscreen.index', [
            'title' => 'Lock Screen',
            'encode_kegiatan_id' => $encode_kegiatan_id,
            'kegiatan_id' => $kegiatan_id,
            'kegiatan' => $kegiatan,
            'pangkatJabatans' => $pangkatJabatans,
            'kotas' => $kotas,
            'sekolahs' => $sekolahs
        ]);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'token' => 'required',
            'kegiatan_id' => 'required' // Ini adalah kegiatan_id asli (belum encode)
        ]);

        $kegiatan_id = $request->kegiatan_id;

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

        // Verifikasi token dari kegiatan
        if ($request->token !== $kegiatan->instrumen_token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid'
            ]);
        }

        // Cek apakah NIP ada di database
        $ptk = Ptk::where('nip', $request->nip)->first();

        if (!$ptk) {
            return response()->json([
                'success' => false,
                'show_register_modal' => true,
                'nip' => $request->nip,
                'kegiatan_id' => $kegiatan_id, // Kirim kegiatan_id asli
                'token' => $request->token
            ]);
        }

        // ENCODE kegiatan_id untuk URL PTK
        $encoded_kegiatan_id = Hashids::encode($kegiatan_id);

        // Redirect ke halaman PTK dengan encode_kegiatan_id
        return response()->json([
            'success' => true,
            'redirect_url' => route('ptk.show', [
                'encode_kegiatan_id' => $encoded_kegiatan_id, // Gunakan encoded ID
                'nip' => $request->nip
            ])
        ]);
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
            'email' => 'required|email|max:100',
            'no_hp' => 'required|max:16',
            'agama' => 'nullable|max:45',
            'pendidikan' => 'nullable|max:100',
            'alamat_rumah' => 'nullable|max:200',
            'kota_id' => 'nullable|exists:kota,kota_id',
            'sekolah_id' => 'nullable|exists:sekolah,sekolah_id',
            'instansi' => 'nullable|max:100',
            'alamat_kantor' => 'nullable|max:200',
            'no_rekening' => 'nullable|max:45',
            'kegiatan_id' => 'required|integer', // kegiatan_id asli
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
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'npwp' => $request->npwp,
                'agama' => $request->agama,
                'pendidikan' => $request->pendidikan,
                'alamat_rumah' => $request->alamat_rumah,
                'kota_id' => $request->kota_id,
                'sekolah_id' => $request->sekolah_id,
                'instansi' => $request->instansi,
                'alamat_kantor' => $request->alamat_kantor,
                'no_rekening' => $request->no_rekening,
                'last_update' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan login dengan NIP Anda.',
                'nip' => $request->nip
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
