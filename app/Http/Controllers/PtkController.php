<?php

namespace App\Http\Controllers;

use App\Models\Ptk;
use App\Models\Kota;
use App\Models\Soal;
use App\Models\SoalCase;
use App\Models\Sekolah;
use App\Models\SubIndikator;
use App\Models\PtkJawaban;
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
        $ptk = Ptk::with(['pangkatJabatan', 'kota', 'sekolah', 'jenisPtk', 'pangkatGolongan'])
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




        // ============================================
        // CEK APAKAH ADA JAWABAN YANG BELUM SELESAI (SIMPLIFIED)
        // ============================================
        $hasUnfinishedQuiz = false;

        if ($kegiatan->tahap == 2) {
            // Cek apakah ada jawaban detail untuk quiz 2
            $hasJawabanDetail = DB::table('ptk_jawaban_detail')
                ->where('kegiatan_id', $kegiatan_id)
                ->where('ptk_id', $ptk->ptk_id)
                ->where('tahap', 2)
                ->exists();

            // Cek apakah sudah selesai semua sub indikator
            $totalSubIndikator = DB::table('soal')
                ->where('entity', $kegiatan->entity)
                ->where('tahap', 2)
                ->distinct('sub_indikator_id')
                ->count('sub_indikator_id');

            $completedSubIndikator = DB::table('ptk_jawaban')
                ->where('kegiatan_id', $kegiatan_id)
                ->where('ptk_id', $ptk->ptk_id)
                ->where('tahap', 2)
                ->whereNotNull('level')
                ->distinct('sub_indikator_id')
                ->count('sub_indikator_id');

            // Ada jawaban detail DAN belum selesai semua sub indikator
            $hasUnfinishedQuiz = $hasJawabanDetail && ($completedSubIndikator < $totalSubIndikator);
        }

        // ============================================
        // CEK APAKAH SUDAH SELESAI INSTRUMEN
        // ============================================
        $isFinished = false;

        if ($kegiatan->tahap == 2) {
            // Untuk Quiz 2: Hitung total soal case berdasarkan entity
            $totalCases = SoalCase::where('entity', $kegiatan->entity)->count();

            // Hitung jawaban yang sudah selesai
            $completedCases = DB::table('ptk_jawaban as pj')
                ->join('soal as s', 's.sub_indikator_id', '=', 'pj.sub_indikator_id')
                ->join('soal_case as sc', 'sc.soal_case_id', '=', 's.soal_case_id')
                ->where('pj.kegiatan_id', $kegiatan_id)
                ->where('pj.ptk_id', $ptk->ptk_id)
                ->where('pj.tahap', 2)
                ->where('sc.entity', $kegiatan->entity)
                ->whereNotNull('pj.level')
                ->distinct('sc.soal_case_id')
                ->count('sc.soal_case_id');

            $isFinished = ($completedCases >= $totalCases);
        } else {
            // Untuk Quiz 1
            $totalIndicators = DB::table('soal')
                ->where('entity', $kegiatan->entity)
                ->where('tahap', 1)
                ->distinct('indikator_id')
                ->count('indikator_id');

            $completedIndicators = DB::table('ptk_jawaban')
                ->where('kegiatan_id', $kegiatan_id)
                ->where('ptk_id', $ptk->ptk_id)
                ->where('tahap', 1)
                ->whereNotNull('bobot')
                ->distinct('indikator_id')
                ->count('indikator_id');

            $isFinished = ($completedIndicators >= $totalIndicators);
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
            'encoded_sub_indikator_id' => $encoded_sub_indikator_id,
            'isFinished' => $isFinished, // TAMBAHKAN INI
            'hasUnfinishedQuiz' => $hasUnfinishedQuiz, // TAMBAHKAN INI

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



    /**
     * Melanjutkan quiz yang belum selesai (REVISED - SESUAI LOGIKA LEVEL)
     */
    public function continueQuiz($encode_kegiatan_id, $nip)
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

        // ============================================
        // LOGIKA CONTINUE QUIZ 2 (REVISED)
        // ============================================
        if ($kegiatan->tahap == 2) {
            // 1. Cari semua sub_indikator yang sudah dikerjakan dan sudah dapat level final
            $completedSubs = DB::table('ptk_jawaban')
                ->where('kegiatan_id', $kegiatan_id)
                ->where('ptk_id', $ptk->ptk_id)
                ->where('tahap', 2)
                ->whereNotNull('level')
                ->pluck('sub_indikator_id')
                ->toArray();

            // 2. Cari sub_indikator yang sedang dalam proses (ada di jawaban_detail tapi belum ada level final)
            $inProgressSub = DB::table('ptk_jawaban_detail as pjd')
                ->select('pjd.sub_indikator_id')
                ->leftJoin('ptk_jawaban as pj', function ($join) use ($kegiatan_id, $ptk) {
                    $join->on('pj.sub_indikator_id', '=', 'pjd.sub_indikator_id')
                        ->where('pj.kegiatan_id', $kegiatan_id)
                        ->where('pj.ptk_id', $ptk->ptk_id)
                        ->where('pj.tahap', 2);
                })
                ->where('pjd.kegiatan_id', $kegiatan_id)
                ->where('pjd.ptk_id', $ptk->ptk_id)
                ->where('pjd.tahap', 2)
                ->whereNull('pj.level')
                ->orderBy('pjd.created_at', 'desc')
                ->first();

            // 3. Jika ada sub_indikator yang sedang dalam proses, lanjutkan dari sana
            if ($inProgressSub) {
                // Ambil jawaban terakhir untuk sub_indikator ini
                $lastAnswer = DB::table('ptk_jawaban_detail as pjd')
                    ->join('soal as s', 's.soal_id', '=', 'pjd.soal_id')
                    ->where('pjd.kegiatan_id', $kegiatan_id)
                    ->where('pjd.ptk_id', $ptk->ptk_id)
                    ->where('pjd.tahap', 2)
                    ->where('pjd.sub_indikator_id', $inProgressSub->sub_indikator_id)
                    ->orderBy('pjd.created_at', 'desc')
                    ->first();

                if ($lastAnswer) {
                    // Cek level soal terakhir yang dijawab
                    $lastSoal = Soal::find($lastAnswer->soal_id);

                    // Cek bobot jawaban terakhir
                    $lastBobot = $lastAnswer->bobot ?? 0;

                    // ANALISIS BERDASARKAN LEVEL TERAKHIR
                    switch ($lastSoal->level) {
                        case 2:
                        case 3:
                            if ($lastBobot >= 3) {
                                // Berhasil di level ini, lanjut ke soal berikutnya
                                $nextSoal = Soal::where('sub_indikator_id', $lastSoal->sub_indikator_id)
                                    ->where('entity', $kegiatan->entity)
                                    ->where('no_urut', '>', $lastSoal->no_urut)
                                    ->orderBy('no_urut')
                                    ->first();

                                if ($nextSoal) {
                                    // Masih ada soal di sub_indikator yang sama
                                    return redirect()->route('quiz2.show', [
                                        'tahap' => 2,
                                        'encoded_kegiatan_id' => $encode_kegiatan_id,
                                        'nip' => $nip,
                                        'encoded_sub_indikator_id' => Hashids::encode($nextSoal->sub_indikator_id),
                                        'encoded_no_urut' => Hashids::encode($nextSoal->no_urut)
                                    ]);
                                } else {
                                    // Tidak ada soal lagi di sub_indikator ini
                                    // Cek apakah sudah dapat level final
                                    $finalLevel = DB::table('ptk_jawaban')
                                        ->where('kegiatan_id', $kegiatan_id)
                                        ->where('ptk_id', $ptk->ptk_id)
                                        ->where('tahap', 2)
                                        ->where('sub_indikator_id', $lastSoal->sub_indikator_id)
                                        ->whereNotNull('level')
                                        ->first();

                                    if (!$finalLevel) {
                                        // Belum dapat level final, kemungkinan perlu level_final
                                        $level_final = $lastSoal->level == 2 ? 2 : $lastSoal->level - 1;

                                        // Insert level final
                                        PtkJawaban::updateOrCreate([
                                            'kegiatan_id' => $kegiatan_id,
                                            'sub_indikator_id' => $lastSoal->sub_indikator_id,
                                            'sub_indikator_code' => $lastSoal->sub_indikator_code ?? '',
                                            'tahap' => 2,
                                            'ptk_id' => $ptk->ptk_id
                                        ], [
                                            'level' => $level_final
                                        ]);
                                    }
                                }
                            } else {
                                // Gagal di level ini, sudah dapat level final
                                // Langsung cari sub_indikator berikutnya
                            }
                            break;

                        case 4:
                        case 5:
                            if ($lastBobot == 4) {
                                if ($lastSoal->level == 5) {
                                    // Sudah dapat level 5
                                    PtkJawaban::updateOrCreate([
                                        'kegiatan_id' => $kegiatan_id,
                                        'sub_indikator_id' => $lastSoal->sub_indikator_id,
                                        'sub_indikator_code' => $lastSoal->sub_indikator_code ?? '',
                                        'tahap' => 2,
                                        'ptk_id' => $ptk->ptk_id
                                    ], [
                                        'level' => 5
                                    ]);
                                }

                                // Cari soal berikutnya
                                $nextSoal = Soal::where('sub_indikator_id', $lastSoal->sub_indikator_id)
                                    ->where('entity', $kegiatan->entity)
                                    ->where('no_urut', '>', $lastSoal->no_urut)
                                    ->orderBy('no_urut')
                                    ->first();

                                if ($nextSoal) {
                                    return redirect()->route('quiz2.show', [
                                        'tahap' => 2,
                                        'encoded_kegiatan_id' => $encode_kegiatan_id,
                                        'nip' => $nip,
                                        'encoded_sub_indikator_id' => Hashids::encode($nextSoal->sub_indikator_id),
                                        'encoded_no_urut' => Hashids::encode($nextSoal->no_urut)
                                    ]);
                                }
                            } else {
                                // Gagal di level ini, sudah dapat level_final (level - 1)
                                $level_final = $lastSoal->level - 1;

                                PtkJawaban::updateOrCreate([
                                    'kegiatan_id' => $kegiatan_id,
                                    'sub_indikator_id' => $lastSoal->sub_indikator_id,
                                    'sub_indikator_code' => $lastSoal->sub_indikator_code ?? '',
                                    'tahap' => 2,
                                    'ptk_id' => $ptk->ptk_id
                                ], [
                                    'level' => $level_final
                                ]);
                            }
                            break;
                    }
                }

                // Setelah diproses, cari sub_indikator berikutnya
                $nextSub = SubIndikator::where('sub_indikator_id', '>', $inProgressSub->sub_indikator_id)
                    ->orderBy('sub_indikator_id')
                    ->first();
            } else {
                // 4. Jika tidak ada yang in progress, cari sub_indikator terkecil yang belum dikerjakan
                $allSubs = DB::table('soal')
                    ->where('entity', $kegiatan->entity)
                    ->where('tahap', 2)
                    ->distinct('sub_indikator_id')
                    ->pluck('sub_indikator_id')
                    ->toArray();

                // Cari sub_indikator pertama yang belum ada di completedSubs
                foreach ($allSubs as $subId) {
                    if (!in_array($subId, $completedSubs)) {
                        $nextSub = SubIndikator::find($subId);
                        break;
                    }
                }
            }

            // 5. Redirect ke sub_indikator berikutnya
            if (isset($nextSub)) {
                $firstSoal = Soal::where('sub_indikator_id', $nextSub->sub_indikator_id)
                    ->where('entity', $kegiatan->entity)
                    ->orderBy('no_urut')
                    ->first();

                if ($firstSoal) {
                    return redirect()->route('quiz2.show', [
                        'tahap' => 2,
                        'encoded_kegiatan_id' => $encode_kegiatan_id,
                        'nip' => $nip,
                        'encoded_sub_indikator_id' => Hashids::encode($firstSoal->sub_indikator_id),
                        'encoded_no_urut' => Hashids::encode($firstSoal->no_urut)
                    ]);
                }
            }

            // 6. Jika semua sub_indikator sudah selesai
            $totalSubs = count($allSubs ?? []);
            if ($totalSubs > 0 && count($completedSubs) >= $totalSubs) {
                return redirect()->route('quiz.finish', [
                    'encoded_kegiatan_id' => $encode_kegiatan_id,
                    'nip' => $nip
                ]);
            }
        }

        // Jika tidak bisa continue, mulai dari awal
        $firstSoal = Soal::where('entity', $kegiatan->entity)
            ->where('tahap', $kegiatan->tahap)
            ->orderBy('sub_indikator_id')
            ->orderBy('no_urut')
            ->first();

        if (!$firstSoal) {
            return redirect()->route('ptk.show', [
                'encode_kegiatan_id' => $encode_kegiatan_id,
                'nip' => $nip
            ])->with('error', 'Tidak ada soal tersedia');
        }

        if ($kegiatan->tahap == 2) {
            return redirect()->route('quiz2.show', [
                'tahap' => 2,
                'encoded_kegiatan_id' => $encode_kegiatan_id,
                'nip' => $nip,
                'encoded_sub_indikator_id' => Hashids::encode($firstSoal->sub_indikator_id),
                'encoded_no_urut' => Hashids::encode($firstSoal->no_urut)
            ]);
        }

        return redirect()->route('ptk.show', [
            'encode_kegiatan_id' => $encode_kegiatan_id,
            'nip' => $nip
        ])->with('error', 'Tidak dapat melanjutkan quiz');
    }
}
