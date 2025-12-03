<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Ptk;
use App\Models\PtkJabatan;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\DB;

class PtkController extends Controller
{
    /**
     * Menampilkan halaman PTK dengan encode_kegiatan_id di URL
     */
    public function index($encode_kegiatan_id, $nip)
    {
        // Validasi decode
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            abort(404, 'ID kegiatan tidak valid');
        }

        // Decode untuk mendapatkan kegiatan_id asli
        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        // Ambil data PTK berdasarkan NIP
        $ptk = Ptk::where('nip', $nip)->first();

        if (!$ptk) {
            // Redirect kembali ke lockscreen dengan encoded ID
            return redirect()->route('lockscreen', ['encode_kegiatan_id' => $encode_kegiatan_id])
                ->with('error', 'Data PTK tidak ditemukan. Silakan registrasi terlebih dahulu.');
        }

        // Ambil data kegiatan
        $kegiatan = Kegiatan::find($kegiatan_id);

        if (!$kegiatan) {
            return redirect()->route('lockscreen', ['encode_kegiatan_id' => $encode_kegiatan_id])
                ->with('error', 'Kegiatan tidak ditemukan');
        }

        // Periksa apakah kegiatan masih aktif
        if ($kegiatan->status !== 'Active') {
            return redirect()->route('lockscreen', ['encode_kegiatan_id' => $encode_kegiatan_id])
                ->with('error', 'Kegiatan sudah tidak aktif');
        }

        // Ambil data jabatan untuk dropdown (jika diperlukan)
        $jabatans = PtkJabatan::orderBy('nama_jabatan')->get();

        // Format tanggal
        $start_date = \Carbon\Carbon::parse($kegiatan->start_date)->format('d/m/Y');
        $end_date = \Carbon\Carbon::parse($kegiatan->end_date)->format('d/m/Y');

        // Hitung durasi dan sisa waktu
        $start = \Carbon\Carbon::parse($kegiatan->start_date);
        $end = \Carbon\Carbon::parse($kegiatan->end_date);
        $now = \Carbon\Carbon::now();

        $duration = $start->diffInDays($end) + 1;
        $remaining = $now->diffInDays($end, false);

        return view('ptk.index', [
            'title' => 'Kegiatan - ' . $kegiatan->kegiatan_name,
            'kegiatan' => $kegiatan,
            'ptk' => $ptk,
            'jabatans' => $jabatans,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'duration' => $duration,
            'remaining' => $remaining,
            'current_nip' => $nip,
            'current_encode_kegiatan_id' => $encode_kegiatan_id,
            'current_kegiatan_id' => $kegiatan_id
        ]);
    }

    /**
     * Memulai quiz baru
     */
    public function startQuiz($encode_kegiatan_id, $nip)
    {
        // Method ini tidak dipakai karena langsung redirect ke route quiz.show
        // Validasi decode
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            abort(404, 'ID kegiatan tidak valid');
        }

        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        // Verifikasi PTK
        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) {
            return redirect()->route('ptk.show', [
                'encode_kegiatan_id' => $encode_kegiatan_id,
                'nip' => $nip
            ])->with('error', 'Data PTK tidak ditemukan');
        }

        // Verifikasi kegiatan
        $kegiatan = Kegiatan::find($kegiatan_id);
        if (!$kegiatan || $kegiatan->status !== 'Active') {
            return redirect()->route('ptk.show', [
                'encode_kegiatan_id' => $encode_kegiatan_id,
                'nip' => $nip
            ])->with('error', 'Kegiatan tidak valid atau tidak aktif');
        }

        // Redirect ke halaman quiz pertama
        return redirect()->route('quiz.show', [
            'encoded_kegiatan_id' => $encode_kegiatan_id,
            'nip' => $nip,
            'sub_indikator_id' => 1,
            'no_urut' => 1
        ]);
    }

    /**
     * Melanjutkan quiz yang sudah dimulai - TIDAK DIPAKAI LAGI
     */
    public function continueQuiz($encode_kegiatan_id, $nip)
    {
        // Method ini tidak dipakai karena langsung redirect ke route quiz.show
        // Validasi decode
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            abort(404, 'ID kegiatan tidak valid');
        }

        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        // Verifikasi PTK
        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) {
            return redirect()->route('ptk.show', [
                'encode_kegiatan_id' => $encode_kegiatan_id,
                'nip' => $nip
            ])->with('error', 'Data PTK tidak ditemukan');
        }

        // Verifikasi kegiatan
        $kegiatan = Kegiatan::find($kegiatan_id);
        if (!$kegiatan || $kegiatan->status !== 'Active') {
            return redirect()->route('ptk.show', [
                'encode_kegiatan_id' => $encode_kegiatan_id,
                'nip' => $nip
            ])->with('error', 'Kegiatan tidak valid atau tidak aktif');
        }

        // Ambil progress terakhir dari database
        $last_progress = $this->getLastProgress($kegiatan_id, $nip);

        // Redirect ke halaman quiz terakhir
        return redirect()->route('quiz.show', [
            'encoded_kegiatan_id' => $encode_kegiatan_id,
            'nip' => $nip,
            'sub_indikator_id' => $last_progress['sub_indikator_id'],
            'no_urut' => $last_progress['no_urut']
        ]);
    }

    /**
     * Mencari PTK berdasarkan NIK/NIP
     */
    public function search($nik_nip)
    {
        try {
            // Cari PTK berdasarkan NIP atau NIK
            $ptk = Ptk::where('nip', $nik_nip)
                ->orWhere('nik', $nik_nip)
                ->first();

            if (!$ptk) {
                return response()->json([
                    'success' => false,
                    'message' => 'PTK tidak ditemukan'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'ptk_id' => $ptk->ptk_id,
                    'nip' => $ptk->nip,
                    'nama' => $ptk->nama,
                    'jabatan' => $ptk->jabatan,
                    'email' => $ptk->email,
                    'no_hp' => $ptk->no_hp,
                    'instansi' => $ptk->instansi
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan progress terakhir quiz
     */
    private function getLastProgress($kegiatan_id, $nip)
    {
        // Query untuk mendapatkan progress terakhir dari database
        // Contoh: dari tabel jawaban atau progress_quiz

        /*
        $last_answer = DB::table('jawaban')
            ->where('kegiatan_id', $kegiatan_id)
            ->where('nip', $nip)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($last_answer) {
            return [
                'sub_indikator_id' => $last_answer->sub_indikator_id,
                'no_urut' => $last_answer->no_urut + 1
            ];
        }
        */

        // Default: mulai dari awal
        return [
            'sub_indikator_id' => 1,
            'no_urut' => 1
        ];
    }

    /**
     * Cek apakah sudah ada progress quiz
     */
    private function hasQuizProgress($kegiatan_id, $nip)
    {
        // Query untuk mengecek apakah sudah ada progress quiz
        /*
        return DB::table('jawaban')
            ->where('kegiatan_id', $kegiatan_id)
            ->where('nip', $nip)
            ->exists();
        */

        return false; // Default: belum ada progress
    }

    /**
     * Update data PTK (jika diperlukan)
     */
    public function update(Request $request, $encode_kegiatan_id, $nip)
    {
        // Validasi decode
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'ID kegiatan tidak valid'
            ]);
        }

        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        // Cari PTK
        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) {
            return response()->json([
                'success' => false,
                'message' => 'Data PTK tidak ditemukan'
            ]);
        }

        $request->validate([
            'nama' => 'sometimes|string|max:200',
            'email' => 'sometimes|email|max:100',
            'no_hp' => 'sometimes|string|max:16',
            'alamat_rumah' => 'sometimes|string|max:200',
            'instansi' => 'sometimes|string|max:100'
        ]);

        try {
            $ptk->update($request->only([
                'nama',
                'email',
                'no_hp',
                'alamat_rumah',
                'instansi'
            ]));

            $ptk->last_update = now();
            $ptk->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $ptk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug: Encode/decode kegiatan_id
     */
    public function debugEncode($kegiatan_id)
    {
        try {
            $encoded = Hashids::encode($kegiatan_id);
            $decoded = Hashids::decode($encoded);

            return response()->json([
                'original_id' => $kegiatan_id,
                'encoded' => $encoded,
                'decoded' => $decoded,
                'is_valid' => count($decoded) > 0 && $decoded[0] == $kegiatan_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function riwayatKegiatan($encode_kegiatan_id, $nip)
    {
        try {
            // Decode kegiatan ID
            $decoded_kegiatan_id = Hashids::decode($encode_kegiatan_id);

            if (empty($decoded_kegiatan_id)) {
                return redirect()->back()->with('error', 'Kegiatan tidak ditemukan.');
            }

            $kegiatan_id = $decoded_kegiatan_id[0];

            // Cari kegiatan
            $kegiatan = Kegiatan::find($kegiatan_id);

            if (!$kegiatan) {
                return redirect()->back()->with('error', 'Kegiatan tidak ditemukan.');
            }

            // Cari data PTK
            $ptk = Ptk::where('nip', $nip)->first();

            if (!$ptk) {
                return redirect()->back()->with('error', 'Data PTK tidak ditemukan.');
            }

            // Ambil semua kegiatan dari database (atau dummy data)
            $kegiatans = Kegiatan::orderBy('start_date', 'desc')->limit(10)->get();

            // TAMBAHKAN INI - Format tanggal
            $start_date = date('d/m/Y', strtotime($kegiatan->start_date));
            $end_date = date('d/m/Y', strtotime($kegiatan->end_date));

            return view('ptk.riwayat', compact(
                'ptk',
                'kegiatan',
                'start_date',
                'end_date',
                'kegiatans',
                'encode_kegiatan_id',
                'nip'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Menampilkan detail riwayat per kegiatan
     */
    public function detailRiwayat($encode_kegiatan_id, $nip)
    {
        try {
            $decoded_kegiatan_id = Hashids::decode($encode_kegiatan_id);

            if (empty($decoded_kegiatan_id)) {
                return redirect()->back()->with('error', 'Kegiatan tidak ditemukan.');
            }

            $kegiatan_id = $decoded_kegiatan_id[0];
            $kegiatan = Kegiatan::find($kegiatan_id);
            $ptk = Ptk::where('nip', $nip)->first();

            if (!$kegiatan || !$ptk) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }

            // TAMBAHKAN INI - Format tanggal
            $start_date = date('d/m/Y', strtotime($kegiatan->start_date));
            $end_date = date('d/m/Y', strtotime($kegiatan->end_date));

            return view('ptk.detail-riwayat', compact(
                'ptk',
                'kegiatan',
                'start_date',
                'end_date',
                'encode_kegiatan_id',
                'nip'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Logout dari PTK
     */
    public function logout()
    {
        return redirect()->route('lockscreen.logout');
    }
}
