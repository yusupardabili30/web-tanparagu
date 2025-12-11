<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Ptk;
use App\Models\Peserta;
use App\Models\PangkatJabatan;
use App\Models\Kota;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function index($encode_kegiatan_id)
    {
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            return view('errors.inactive-kegiatan', [
                'title' => 'Akses Ditolak',
                'message' => 'Link tidak valid atau kegiatan tidak ditemukan.'
            ]);
        }

        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'Active')->first();

        if (!$kegiatan) {
            return view('errors.inactive-kegiatan', [
                'title' => 'Kegiatan Tidak Aktif',
                'message' => 'Kegiatan sudah tidak aktif atau tidak ditemukan.'
            ]);
        }

        $pangkatJabatans = PangkatJabatan::orderBy('pangkat_jabatan_id')->get();
        $kotas = Kota::orderBy('nama_kota')->get();

        $sekolahs = Sekolah::orderBy('nama_sekolah')->limit(100)->get();

        return view('register.index', [
            'title' => 'Register',
            'encode_kegiatan_id' => $encode_kegiatan_id,
            'kegiatan_id' => $kegiatan_id,
            'kegiatan' => $kegiatan,
            'pangkatJabatans' => $pangkatJabatans,
            'kotas' => $kotas,
            'sekolahs' => $sekolahs
        ]);
    }

    public function store(Request $request, $encode_kegiatan_id)
    {
        // Debug data
        // dd($request->all());

        // Validasi input dasar
        $request->validate([
            'nama' => 'required|string|max:200',
            'nik' => 'required|string|max:16',
            'nip' => 'required|string|max:19',
            'pangkat_jabatan_id' => 'required|integer',
            'agama' => 'required|string|max:45',
            'jenis_kelamin' => 'required|string|max:2',
            'tempat_lahir' => 'required|string|max:45',
            'tgl_lahir' => 'required|date',
            'pendidikan' => 'required|string|max:100',
            'unit_kerja_option' => 'required|in:sekolah,unit_kerja',
            'alamat_kantor' => 'required|string',
            'kota_id' => 'required|integer',
            'provinsi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:16',
            'email' => 'required|email|max:255', // Max 25 sesuai database
            'npwp' => 'required|string|max:20',
            'nama_bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:45',
            'atas_nama_rekening' => 'required|string|max:255',
            'website' => 'nullable|string|max:200',
        ]);

        // Inisialisasi variabel dengan nilai default
        $instansi = null;
        $sekolah_id = null;

        // Handle berdasarkan pilihan unit kerja
        if ($request->unit_kerja_option === 'sekolah') {
            // Jika memilih sekolah
            if ($request->has('sekolah_id') && $request->sekolah_id) {
                $request->validate([
                    'sekolah_id' => 'integer|exists:sekolah,sekolah_id',
                ]);
                $sekolah_id = $request->sekolah_id;
                $instansi = null; // Instansi null karena sudah ada sekolah
            }
            // Jika tidak memilih sekolah, keduanya null
        } else {
            // Unit kerja lainnya
            if ($request->has('instansi') && $request->instansi) {
                $request->validate([
                    'instansi' => 'required|string|max:100',
                ]);
                $instansi = $request->instansi;
                $sekolah_id = null; // sekolah_id null karena pilih unit kerja
            } else {
                // Validasi: instansi wajib jika pilih unit kerja
                return back()->withErrors(['instansi' => 'Nama unit kerja wajib diisi'])
                    ->withInput();
            }
        }

        // Pastikan email tidak lebih dari 25 karakter
        $email = substr($request->email, 0, 25);

        // Decode kegiatan_id
        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        try {
            DB::beginTransaction();

            // ============================================
            // 1. HANDLE PTK
            // ============================================

            // Untuk ptk, jika instansi null, gunakan string kosong
            $ptk_instansi = $instansi ?? '';

            // Cek apakah PTK sudah ada berdasarkan NIK atau NIP
            $ptk = Ptk::where('nik', $request->nik)
                ->orWhere('nip', $request->nip)
                ->first();

            if (!$ptk) {
                // Buat PTK baru
                $ptkData = [
                    'nik' => $request->nik,
                    'nip' => $request->nip,
                    'nuptk' => $request->nuptk ?? null,
                    'nama' => $request->nama,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'agama' => $request->agama,
                    'pendidikan' => $request->pendidikan,
                    'no_hp' => $request->no_hp,
                    'npwp' => $request->npwp,
                    'email' => $email,
                    // 'alamat_rumah' => $request->alamat_kantor,
                    'alamat_rumah_kota' => '',
                    'instansi' => $ptk_instansi, // String kosong jika null
                    'alamat_kantor' => $request->alamat_kantor,
                    'alamat_kantor_kota' => '',
                    'no_rekening' => $request->no_rekening,
                    'last_update' => Carbon::now(),
                    'kota_id' => $request->kota_id,
                    'sekolah_id' => $sekolah_id,
                    'pangkat_jabatan_id' => $request->pangkat_jabatan_id,
                ];

                $ptk = Ptk::create($ptkData);
            } else {
                // Update PTK yang sudah ada
                $ptk->update([
                    'nama' => $request->nama,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'agama' => $request->agama,
                    'pendidikan' => $request->pendidikan,
                    'no_hp' => $request->no_hp,
                    'npwp' => $request->npwp,
                    'email' => $email,
                    'instansi' => $ptk_instansi,
                    'alamat_kantor' => $request->alamat_kantor,
                    'no_rekening' => $request->no_rekening,
                    'last_update' => Carbon::now(),
                    'kota_id' => $request->kota_id,
                    'sekolah_id' => $sekolah_id,
                    'pangkat_jabatan_id' => $request->pangkat_jabatan_id,
                ]);
            }

            // ============================================
            // 2. HANDLE PESERTA
            // ============================================

            // Untuk peserta, handle instansi null berdasarkan constraint database
            $peserta_instansi = $instansi;

            // Jika instansi null dan kolom tidak bisa null, gunakan default
            if ($peserta_instansi === null) {
                // Cek constraint database
                // Jika kolom tidak bisa null, gunakan string kosong
                $peserta_instansi = '';
            }

            // Cek apakah Peserta sudah ada berdasarkan NIK atau NIP
            $pesertaExist = Peserta::where('nik', $request->nik)
                ->orWhere('nip', $request->nip)
                ->first();

            if ($pesertaExist) {
                // Update peserta yang sudah ada
                $pesertaExist->update([
                    'nama' => $request->nama,
                    'email' => $email,
                    'no_hp' => $request->no_hp,
                    'npwp' => $request->npwp,
                    'nama_bank' => $request->nama_bank,
                    'no_rekening' => $request->no_rekening,
                    'atas_nama_rekening' => $request->atas_nama_rekening,
                    'pangkat_jabatan_id' => $request->pangkat_jabatan_id,
                    'agama' => $request->agama,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'pendidikan' => $request->pendidikan,
                    'sekolah_id' => $sekolah_id,
                    'instansi' => $peserta_instansi,
                    'alamat_kantor' => $request->alamat_kantor,
                    'kota_id' => $request->kota_id,
                    'provinsi' => $request->provinsi,
                    'last_update' => Carbon::now(),
                ]);
                $peserta = $pesertaExist;
            } else {
                // Buat Peserta baru
                $pesertaData = [
                    'nama' => $request->nama,
                    'nik' => $request->nik,
                    'nip' => $request->nip,
                    'email' => $email,
                    'no_hp' => $request->no_hp,
                    'npwp' => $request->npwp,
                    'nama_bank' => $request->nama_bank,
                    'no_rekening' => $request->no_rekening,
                    'atas_nama_rekening' => $request->atas_nama_rekening,
                    'pangkat_jabatan_id' => $request->pangkat_jabatan_id,
                    'agama' => $request->agama,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'pendidikan' => $request->pendidikan,
                    'sekolah_id' => $sekolah_id,
                    'instansi' => $peserta_instansi,
                    'alamat_kantor' => $request->alamat_kantor,
                    'kota_id' => $request->kota_id,
                    'provinsi' => $request->provinsi,
                    'last_update' => Carbon::now(),
                ];

                // Debug sebelum create
                // dd($pesertaData);

                $peserta = Peserta::create($pesertaData);
            }

            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('register.success', $encode_kegiatan_id)
                ->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Debug error detail
            \Log::error('Register Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());

            // Tampilkan error lebih detail untuk debugging
            $errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();

            return back()
                ->withErrors(['error' => $errorMessage])
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function success($encode_kegiatan_id)
    {
        return view('register.success', [
            'title' => 'Pendaftaran Berhasil',
            'encode_kegiatan_id' => $encode_kegiatan_id
        ]);
    }

    // Method untuk search sekolah dengan AJAX
    public function searchSekolah(Request $request)
    {
        $search = $request->q;

        $sekolahs = Sekolah::where('nama_sekolah', 'like', '%' . $search . '%')
            ->orWhere('npsn', 'like', '%' . $search . '%')
            ->orWhere('alamat', 'like', '%' . $search . '%')
            ->orderBy('nama_sekolah')
            ->paginate(10);

        $formattedResults = $sekolahs->map(function ($sekolah) {
            return [
                'id' => $sekolah->sekolah_id,
                'text' => $sekolah->nama_sekolah . ($sekolah->npsn ? ' (NPSN: ' . $sekolah->npsn . ')' : ''),
                'nama_sekolah' => $sekolah->nama_sekolah,
                'npsn' => $sekolah->npsn,
                'alamat' => $sekolah->alamat,
                'kab_kota' => $sekolah->kab_kota
            ];
        });

        return response()->json([
            'data' => $formattedResults,
            'next_page_url' => $sekolahs->nextPageUrl()
        ]);
    }

    // Method untuk mendapatkan alamat sekolah berdasarkan ID
    public function getSekolahAlamat($id)
    {
        $sekolah = Sekolah::find($id);

        if (!$sekolah) {
            return response()->json([
                'success' => false,
                'message' => 'Sekolah tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'alamat' => $sekolah->alamat,
            'kab_kota' => $sekolah->kab_kota
        ]);
    }
}
