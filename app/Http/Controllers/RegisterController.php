<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Ptk;
use App\Models\Peserta;
use App\Models\PangkatJabatan;
use App\Models\Kota;
use App\Models\Bank;
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

        // Ambil semua bank - HAPUS active() karena tidak ada kolom status
        $banks = Bank::orderBy('nama_bank')->get();

        $sekolahs = Sekolah::orderBy('nama_sekolah')->limit(100)->get();

        return view('register.index', [
            'title' => 'Register',
            'encode_kegiatan_id' => $encode_kegiatan_id,
            'kegiatan_id' => $kegiatan_id,
            'kegiatan' => $kegiatan,
            'pangkatJabatans' => $pangkatJabatans,
            'kotas' => $kotas,
            'banks' => $banks,
            'sekolahs' => $sekolahs
        ]);
    }

    public function store(Request $request, $encode_kegiatan_id)
    {
        // ============================================
        // 1. VALIDASI DOUBLE REGISTRASI - KEGIATAN YANG SAMA
        // ============================================

        // Decode kegiatan_id
        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        // Cek apakah NIP sudah terdaftar di KEGIATAN YANG SAMA
        $sudahTerdaftarDiKegiatanIni = Peserta::where('nip', $request->nip)
            ->where('kegiatan_id', $kegiatan_id)
            ->exists();

        if ($sudahTerdaftarDiKegiatanIni) {
            return back()->withErrors([
                'nip' => 'NIP ' . $request->nip . ' sudah terdaftar di kegiatan ini. Tidak dapat mendaftar ulang.'
            ])->withInput();
        }

        // ============================================
        // 2. VALIDASI INPUT FORM
        // ============================================

        $request->validate([
            'nama' => 'required|string|max:200',
            'nik' => 'required|string|max:16',
            'nip' => 'required|string|max:19',
            'pangkat_jabatan_id' => 'required|integer|exists:pangkat_jabatan,pangkat_jabatan_id',
            'agama' => 'required|string|max:45',
            'jenis_kelamin' => 'required|string|in:L,P',
            'tempat_lahir' => 'required|string|max:45',
            'tgl_lahir' => 'required|date',
            'pendidikan' => 'required|string|max:100',
            'unit_kerja_option' => 'required|in:sekolah,unit_kerja',
            'alamat_kantor' => 'required|string|max:500',
            'kota_id' => 'required|integer|exists:kota,kota_id',
            'provinsi' => 'required|string|max:255',
            'ttd_base64' => 'required|string',
            'no_hp' => 'required|string|max:16',
            'email' => 'required|email|max:255',
            'npwp' => 'required|string|max:20',
            'ms_bank_id' => 'required|integer|exists:ms_bank,ms_bank_id',
            'no_rekening' => 'required|string|max:45',
            'atas_nama_rekening' => 'required|string|max:255',
        ]);

        // ============================================
        // 3. VALIDASI TAMBAHAN BERDASARKAN UNIT KERJA
        // ============================================

        $instansi = null;
        $sekolah_id = null;

        if ($request->unit_kerja_option === 'sekolah') {
            if ($request->has('sekolah_id') && $request->sekolah_id) {
                $request->validate([
                    'sekolah_id' => 'integer|exists:sekolah,sekolah_id',
                ]);
                $sekolah_id = $request->sekolah_id;
                $instansi = null;
            }
            // sekolah_id boleh null jika tidak memilih sekolah
        } else {
            $request->validate([
                'instansi' => 'required|string|max:100',
            ]);
            $instansi = $request->instansi;
            $sekolah_id = null;
        }

        // ============================================
        // 4. VALIDASI EMAIL DAN UMUR
        // ============================================

        $email = $request->email;
        if (strlen($email) > 25) {
            $email = substr($email, 0, 25);
        }

        $tgl_lahir = Carbon::parse($request->tgl_lahir);
        $usia = $tgl_lahir->diffInYears(Carbon::now());

        if ($usia < 17) {
            return back()->withErrors([
                'tgl_lahir' => 'Usia minimal 17 tahun. Usia Anda: ' . $usia . ' tahun'
            ])->withInput();
        }

        // ============================================
        // 5. PROSES TRANSACTION DATABASE
        // ============================================

        DB::beginTransaction();

        try {
            // ============================================
            // 6. HANDLE PTK (TABEL MASTER - OPTIONAL UPDATE)
            // ============================================

            // CARA YANG BENAR: Cari PTK berdasarkan NIP SAJA, bukan OR dengan NIK
            // Karena NIP harus unik per orang, sedangkan NIK bisa sama dengan NIP orang lain
            $ptk = Ptk::where('nip', $request->nip)->first();

            // Untuk ptk, jika instansi null, gunakan string kosong
            $ptk_instansi = $instansi ?? '';

            if ($ptk) {
                // PTK SUDAH ADA: Update data PTK dengan informasi terbaru
                // TAPI pastikan NIP yang dicari benar-benar match
                \Log::info('PTK ditemukan berdasarkan NIP', [
                    'nip_dicari' => $request->nip,
                    'nip_ditemukan' => $ptk->nip,
                    'nama_sebelum' => $ptk->nama,
                    'nama_setelah' => $request->nama
                ]);

                $ptk->update([
                    'nama' => $request->nama,
                    'nik' => $request->nik, // Update NIK juga
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $tgl_lahir,
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

                \Log::info('PTK updated successfully', [
                    'nip' => $request->nip,
                    'ptk_id' => $ptk->ptk_id,
                    'nama' => $request->nama
                ]);
            } else {
                // Coba cari berdasarkan NIK juga (untuk kasus NIP berubah)
                // TAPI dengan logika yang lebih hati-hati
                $ptkByNik = Ptk::where('nik', $request->nik)->first();

                if ($ptkByNik) {
                    // Jika ditemukan berdasarkan NIK, update NIP-nya
                    \Log::info('PTK ditemukan berdasarkan NIK, update NIP', [
                        'nik' => $request->nik,
                        'nip_lama' => $ptkByNik->nip,
                        'nip_baru' => $request->nip
                    ]);

                    $ptkByNik->update([
                        'nip' => $request->nip, // Update NIP
                        'nama' => $request->nama,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'tempat_lahir' => $request->tempat_lahir,
                        'tgl_lahir' => $tgl_lahir,
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

                    $ptk = $ptkByNik;
                    \Log::info('PTK updated by NIK', ['nik' => $request->nik, 'ptk_id' => $ptk->ptk_id]);
                } else {
                    // PTK BELUM ADA: Buat PTK baru
                    $ptkData = [
                        'nik' => $request->nik,
                        'nip' => $request->nip,
                        'nuptk' => null,
                        'nama' => $request->nama,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'tempat_lahir' => $request->tempat_lahir,
                        'tgl_lahir' => $tgl_lahir,
                        'agama' => $request->agama,
                        'pendidikan' => $request->pendidikan,
                        'no_hp' => $request->no_hp,
                        'npwp' => $request->npwp,
                        'email' => $email,
                        'alamat_rumah_kota' => '',
                        'instansi' => $ptk_instansi,
                        'alamat_kantor' => $request->alamat_kantor,
                        'alamat_kantor_kota' => '',
                        'no_rekening' => $request->no_rekening,
                        'last_update' => Carbon::now(),
                        'kota_id' => $request->kota_id,
                        'sekolah_id' => $sekolah_id,
                        'pangkat_jabatan_id' => $request->pangkat_jabatan_id,
                    ];

                    $ptk = Ptk::create($ptkData);
                    \Log::info('PTK created new', ['nip' => $request->nip, 'ptk_id' => $ptk->ptk_id]);
                }
            }

            // ============================================
            // 7. HANDLE PESERTA (TABEL KEGIATAN - HARUS BARU)
            // ============================================

            // Untuk peserta, handle instansi null
            $peserta_instansi = $instansi ?? '';

            // Cek apakah Peserta dengan NIP ini sudah ada DI KEGIATAN INI
            // Sebenarnya sudah divalidasi di awal, tapi double check
            $pesertaExistDiKegiatanIni = Peserta::where('nip', $request->nip)
                ->where('kegiatan_id', $kegiatan_id)
                ->exists();

            if ($pesertaExistDiKegiatanIni) {
                throw new \Exception('NIP sudah terdaftar di kegiatan ini');
            }

            // SELALU BUAT PESERTA BARU (karena kegiatan berbeda)
            $pesertaData = [
                'kegiatan_id' => $kegiatan_id,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'nip' => $request->nip,
                'email' => $email,
                'no_hp' => $request->no_hp,
                'npwp' => $request->npwp,
                'ms_bank_id' => $request->ms_bank_id,
                'ttd_base64' => $request->ttd_base64,
                'no_rekening' => $request->no_rekening,
                'atas_nama_rekening' => $request->atas_nama_rekening,
                'pangkat_jabatan_id' => $request->pangkat_jabatan_id,
                'agama' => $request->agama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $tgl_lahir,
                'pendidikan' => $request->pendidikan,
                'sekolah_id' => $sekolah_id,
                'instansi' => $peserta_instansi,
                'alamat_kantor' => $request->alamat_kantor,
                'kota_id' => $request->kota_id,
                'provinsi' => $request->provinsi,
                'last_update' => Carbon::now(),
            ];

            $peserta = Peserta::create($pesertaData);

            \Log::info('Peserta created', [
                'nip' => $request->nip,
                'kegiatan_id' => $kegiatan_id,
                'peserta_id' => $peserta->peserta_id
            ]);

            // ============================================
            // 8. COMMIT TRANSACTION
            // ============================================

            DB::commit();

            // ============================================
            // 9. REDIRECT KE HALAMAN SUKSES
            // ============================================

            return redirect()->route('register.success', $encode_kegiatan_id)
                ->with('success', 'Pendaftaran berhasil!')
                ->with('peserta_nama', $request->nama)
                ->with('peserta_nip', $request->nip);
        } catch (\Exception $e) {
            // ============================================
            // 10. ROLLBACK JIKA ADA ERROR
            // ============================================

            DB::rollBack();

            \Log::error('Error saat pendaftaran:', [
                'nip' => $request->nip,
                'nama' => $request->nama,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // ============================================
            // 11. HANDLE ERROR SPECIFIC
            // ============================================

            $errorMessage = 'Terjadi kesalahan saat menyimpan data.';

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                if (strpos($e->getMessage(), 'ptk.nip') !== false) {
                    $errorMessage = 'NIP sudah terdaftar di database PTK dengan nama yang berbeda.';
                } elseif (strpos($e->getMessage(), 'ptk.nik') !== false) {
                    $errorMessage = 'NIK sudah terdaftar di database PTK.';
                } else {
                    $errorMessage = 'Data sudah ada dalam sistem.';
                }
            } elseif (strpos($e->getMessage(), 'Integrity constraint') !== false) {
                $errorMessage = 'Data referensi tidak valid.';
            } elseif (strpos($e->getMessage(), 'sudah terdaftar') !== false) {
                $errorMessage = $e->getMessage();
            }

            return back()
                ->withErrors(['error' => $errorMessage])
                ->withInput()
                ->with('error', $errorMessage);
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
            ->paginate(50);

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

    public function cekNipKegiatan(Request $request, $encode_kegiatan_id)
    {
        $nip = $request->query('nip');
        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0] ?? 0;

        // 1. Cek apakah NIP sudah terdaftar di KEGIATAN INI
        $sudahTerdaftar = Peserta::where('nip', $nip)
            ->where('kegiatan_id', $kegiatan_id)
            ->exists();

        if ($sudahTerdaftar) {
            return response()->json([
                'sudah_terdaftar' => true,
                'message' => 'NIP sudah terdaftar di kegiatan ini'
            ]);
        }

        // 2. Cari data bank dari peserta lain (prioritas utama untuk bank)
        $bankData = Peserta::where('nip', $nip)
            ->whereNotNull('ms_bank_id')
            ->orderBy('last_update', 'desc')
            ->first(['ms_bank_id', 'no_rekening', 'atas_nama_rekening']);

        // 3. Cari data biodata dari PTK atau peserta
        $data = null;
        $source = null;

        // Prioritaskan dari PTK berdasarkan NIP
        $ptkData = Ptk::where('nip', $nip)->first();
        if ($ptkData) {
            $data = $ptkData;
            $source = 'ptk_by_nip';
        } else {
            // Coba dari Peserta (kegiatan lain) berdasarkan NIP
            $pesertaData = Peserta::where('nip', $nip)
                ->orderBy('last_update', 'desc')
                ->first();
            if ($pesertaData) {
                $data = $pesertaData;
                $source = 'peserta_by_nip';
            }
        }

        if ($data) {
            // Format tanggal
            if ($data->tgl_lahir) {
                $data->tgl_lahir = Carbon::parse($data->tgl_lahir)->format('d-m-Y');
            }

            // Gabungkan data dari PTK/Peserta dengan data bank
            $autofillData = [
                'nama' => $data->nama,
                'nik' => $data->nik,
                'pangkat_jabatan_id' => $data->pangkat_jabatan_id,
                'agama' => $data->agama,
                'jenis_kelamin' => $data->jenis_kelamin,
                'tempat_lahir' => $data->tempat_lahir,
                'tgl_lahir' => $data->tgl_lahir ?? null,
                'pendidikan' => $data->pendidikan,
                'sekolah_id' => $data->sekolah_id,
                'instansi' => $data->instansi,
                'ttd_base64' => $data->ttd_base64 ?? null,
                'alamat_kantor' => $data->alamat_kantor,
                'kota_id' => $data->kota_id,
                'no_hp' => $data->no_hp,
                'email' => $data->email,
                'npwp' => $data->npwp,
                'ms_bank_id' => $bankData ? $bankData->ms_bank_id : null, // Ambil dari bankData
                'no_rekening' => $bankData ? $bankData->no_rekening : ($data->no_rekening ?? ''),
                'atas_nama_rekening' => $bankData ? $bankData->atas_nama_rekening : $data->nama,
            ];

            \Log::info('Data autofill gabungan', [
                'nip' => $nip,
                'source' => $source,
                'bank_data' => $bankData ? 'found' : 'not found',
                'ms_bank_id' => $autofillData['ms_bank_id']
            ]);

            return response()->json([
                'sudah_terdaftar' => false,
                'data' => $autofillData,
                'source' => $source,
                'bank_source' => $bankData ? 'peserta' : 'none',
                'message' => 'Data ditemukan untuk autofill'
            ]);
        }

        return response()->json([
            'sudah_terdaftar' => false,
            'data' => null,
            'message' => 'Data NIP tidak ditemukan'
        ]);
    }
}
