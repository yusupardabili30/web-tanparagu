<?php

namespace App\Http\Controllers;

use App\Models\Ptk;
use App\Models\Kota;
use App\Models\Soal;
use App\Models\Sekolah;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Models\PangkatJabatan;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

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

        // Ambil data PTK berdasarkan NIP dengan relasi
        $ptk = Ptk::with(['pangkatJabatan', 'kota', 'sekolah'])
            ->where('nip', $nip)
            ->first();

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
        $soal = Soal::where('entity', $kegiatan->entity)
            ->where('tahap', $kegiatan->tahap)
            ->first();
        $encoded_sub_indikator_id = Hashids::encode($soal->sub_indikator_id);


        // Periksa apakah kegiatan masih aktif
        if ($kegiatan->status !== 'Active') {
            return redirect()->route('lockscreen', ['encode_kegiatan_id' => $encode_kegiatan_id])
                ->with('error', 'Kegiatan sudah tidak aktif');
        }

        // Ambil data untuk dropdown (jika diperlukan untuk edit)
        $pangkatJabatans = PangkatJabatan::orderBy('jenjang_jabatan')->get();
        $kotas = Kota::orderBy('nama_kota')->get();
        $sekolahs = Sekolah::orderBy('nama_sekolah')->get();

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
            'pangkatJabatans' => $pangkatJabatans,
            'kotas' => $kotas,
            'sekolahs' => $sekolahs,
            'instansi' => $ptk->instansi,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'duration' => $duration,
            'remaining' => $remaining,
            'current_nip' => $nip,
            'current_encode_kegiatan_id' => $encode_kegiatan_id,
            'current_kegiatan_id' => $kegiatan_id,
            'data' => $soal,
            'encoded_sub_indikator_id' => $encoded_sub_indikator_id
        ]);
    }

    /**
     * Memulai quiz baru
     */
    public function startQuiz($encode_kegiatan_id, $nip)
    {
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

        // Encode sub_indikator_id dan no_urut
        $encoded_sub_indikator_id = Hashids::encode(1);
        $encoded_no_urut = Hashids::encode(1);

        // Redirect ke halaman quiz pertama
        return redirect()->route('quiz.show', [
            'encoded_kegiatan_id' => $encode_kegiatan_id,
            'nip' => $nip,
            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
            'encoded_no_urut' => $encoded_no_urut
        ]);
    }

    /**
     * Mencari PTK berdasarkan NIK/NIP
     */
    public function search($nik_nip)
    {
        try {
            // Cari PTK berdasarkan NIP atau NIK dengan relasi
            $ptk = Ptk::with(['pangkatJabatan', 'kota', 'sekolah'])
                ->where('nip', $nik_nip)
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
                    'jabatan' => $ptk->pangkatJabatan ? $ptk->pangkatJabatan->jenjang_jabatan : $ptk->jabatan,
                    'email' => $ptk->email,
                    'no_hp' => $ptk->no_hp,
                    'instansi' => $ptk->sekolah ? $ptk->sekolah->nama_sekolah : $ptk->instansi
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
            'instansi' => 'sometimes|string|max:100',
            'sekolah_id' => 'sometimes|exists:sekolah,sekolah_id',
            'kota_id' => 'sometimes|exists:kota,kota_id',
            'pangkat_jabatan_id' => 'sometimes|exists:pangkat_jabatan,pangkat_jabatan_id'
        ]);

        try {
            $updateData = $request->only([
                'nama',
                'email',
                'no_hp',
                'alamat_rumah',
                'instansi',
                'sekolah_id',
                'kota_id',
                'pangkat_jabatan_id'
            ]);

            // Jika kota_id diupdate, update juga alamat_rumah_kota
            if ($request->has('kota_id') && $request->kota_id) {
                $kota = Kota::find($request->kota_id);
                if ($kota) {
                    $updateData['alamat_rumah_kota'] = $kota->nama_kota;
                }
            }

            $ptk->update($updateData);
            $ptk->last_update = now();
            $ptk->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $ptk->load(['pangkatJabatan', 'kota', 'sekolah'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Riwayat Kegiatan
     */
    public function riwayatKegiatan($encode_kegiatan_id, $nip)
    {
        // Decode kegiatan_id
        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0] ?? 0;

        // Cari PTK
        $ptk = Ptk::where('nip', $nip)->first();
        if (!$ptk) {
            abort(404, 'Data PTK tidak ditemukan');
        }

        // Cari Kegiatan saat ini
        $kegiatan = Kegiatan::find($kegiatan_id);
        if (!$kegiatan) {
            abort(404, 'Kegiatan tidak ditemukan');
        }

        // ============================================
        // AMBIL RIWAYAT KEGIATAN DARI ptk_jawaban
        // ============================================
        // Pertama, ambil semua kegiatan_id yang pernah dijawab oleh PTK ini
        $kegiatanIds = DB::table('ptk_jawaban')
            ->where('ptk_id', $ptk->ptk_id)
            ->distinct()
            ->pluck('kegiatan_id')
            ->toArray();

        // Jika ada kegiatan yang pernah dijawab
        if (!empty($kegiatanIds)) {
            // Ambil data kegiatan berdasarkan ID yang ditemukan
            $riwayat = Kegiatan::whereIn('kegiatan_id', $kegiatanIds)
                ->orderBy('start_date', 'desc')
                ->get();

            // Tambahkan kegiatan saat ini jika belum ada di riwayat
            if (!$riwayat->contains('kegiatan_id', $kegiatan_id)) {
                $riwayat->push($kegiatan);
            }
        } else {
            // Jika belum ada riwayat, tampilkan hanya kegiatan saat ini
            $riwayat = collect([$kegiatan]);
        }

        return view('ptk.riwayat', [
            'ptk' => $ptk,
            'kegiatan' => $kegiatan,
            'riwayat' => $riwayat,
            'encode_kegiatan_id' => $encode_kegiatan_id,
            'nip' => $nip
        ]);
    }

    /**
     * Menampilkan detail riwayat per kegiatan
     */
    /**
     * Menampilkan detail riwayat per kegiatan
     */
    public function detailRiwayat($encode_kegiatan_id, $nip)
    {
        try {
            // Decode kegiatan_id
            $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0] ?? 0;

            if (!$kegiatan_id) {
                return redirect()->back()->with('error', 'Kegiatan tidak ditemukan.');
            }

            // Cari PTK
            $ptk = Ptk::where('nip', $nip)->first();
            if (!$ptk) {
                abort(404, 'Data PTK tidak ditemukan');
            }

            // Cari Kegiatan
            $kegiatan = Kegiatan::find($kegiatan_id);
            if (!$kegiatan) {
                abort(404, 'Kegiatan tidak ditemukan');
            }

            // ============================================
            // AMBIL DETAIL JAWABAN PER SUB INDIKATOR
            // ============================================
            $detailJawaban = DB::table('ptk_jawaban as pj')
                ->select(
                    'pj.sub_indikator_id',
                    'pj.sub_indikator_code',
                    'pj.level',
                    'pj.date_create',
                    'pj.date_update',
                    'si.sub_indikator_name',
                    'si.sub_indikator_dec',
                    'si.no_urut as urut_subindikator'
                )
                ->leftJoin('sub_indikator as si', 'pj.sub_indikator_id', '=', 'si.sub_indikator_id')
                ->where('pj.kegiatan_id', $kegiatan_id)
                ->where('pj.ptk_id', $ptk->ptk_id)
                ->orderBy('si.no_urut', 'asc')
                ->get();

            // ============================================
            // HITUNG STATISTIK
            // ============================================
            $totalSubIndikator = $detailJawaban->count();
            $levelTertinggi = $detailJawaban->max('level') ?? 0;
            $levelTerendah = $detailJawaban->min('level') ?? 0;

            // Hitung persentase
            $totalLevel = $detailJawaban->sum('level');
            $rataRataLevel = $totalSubIndikator > 0 ? round($totalLevel / $totalSubIndikator, 2) : 0;

            // Format tanggal kegiatan
            $start_date = date('d F Y', strtotime($kegiatan->start_date));
            $end_date = date('d F Y', strtotime($kegiatan->end_date));

            // Tanggal terakhir aktif
            $tanggalTerakhir = $detailJawaban->max('date_update')
                ? date('d F Y H:i:s', strtotime($detailJawaban->max('date_update')))
                : '-';

            return view('ptk.detail-riwayat', [
                'ptk' => $ptk,
                'kegiatan' => $kegiatan,
                'detailJawaban' => $detailJawaban,
                'totalSubIndikator' => $totalSubIndikator,
                'levelTertinggi' => $levelTertinggi,
                'levelTerendah' => $levelTerendah,
                'rataRataLevel' => $rataRataLevel,
                'tanggalTerakhir' => $tanggalTerakhir,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'encode_kegiatan_id' => $encode_kegiatan_id,
                'nip' => $nip
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

    /**
     * Logout dari PTK
     */
    public function logout()
    {
        return redirect()->route('lockscreen.logout');
    }
}
