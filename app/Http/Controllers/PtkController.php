<?php

namespace App\Http\Controllers;

use App\Models\Ptk;
use App\Models\Kota;
use App\Models\Soal;
use App\Models\SoalCase;
use App\Models\Sekolah;
use App\Models\SubIndikator;
use App\Models\PtkJawaban;
use App\Models\PtkJawabanDetail;
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
     * Menampilkan detail riwayat dengan format seperti hasil instrumen
     * Hanya untuk PTK yang sedang login
     */
    public function detailRiwayat($encode_kegiatan_id, $nip)
    {
        try {
            // Decode kegiatan_id
            $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0] ?? 0;

            if (!$kegiatan_id) {
                return redirect()->back()->with('error', 'Kegiatan tidak ditemukan.');
            }

            // Cari PTK berdasarkan NIP
            $ptk = Ptk::with(['pangkatJabatan', 'kota', 'sekolah', 'jenisPtk', 'pangkatGolongan'])
                ->where('nip', $nip)
                ->first();

            if (!$ptk) {
                abort(404, 'Data PTK tidak ditemukan');
            }

            // Cari Kegiatan
            $kegiatan = Kegiatan::find($kegiatan_id);
            if (!$kegiatan) {
                abort(404, 'Kegiatan tidak ditemukan');
            }

            // ============================================
            // AMBIL DATA JAWABAN PTK INI SAJA
            // ============================================
            $query = DB::table('ptk_jawaban')
                ->select(
                    'ptk_jawaban.ptk_jawaban_id',
                    'ptk_jawaban.tahap',
                    'ptk_jawaban.level as level_jawaban',
                    'ptk_jawaban.sub_indikator_code',
                    'ptk_jawaban.sub_indikator_id',
                    'ptk_jawaban.bobot',
                    'ptk_jawaban.created_at',
                    'ptk.nama',
                    'ptk.nip',
                    'ptk.pangkat_jabatan_id',
                    'ptk.instansi',
                    'pangkat_jabatan.golongan_ruang',
                    'pangkat_jabatan.pangkat',
                    'pangkat_jabatan.jenjang_jabatan',
                    'pangkat_jabatan.level_kompetensi',
                    'sub_indikator.sub_indikator_name',
                    'kegiatan.kegiatan_name',
                    'kegiatan.entity'
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
                ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
                ->where('ptk_jawaban.kegiatan_id', $kegiatan_id)
                ->where('ptk.nip', $nip) // Hanya data PTK ini
                ->orderBy('ptk_jawaban.created_at', 'desc');

            $data = $query->get();

            // ============================================
            // TAMBAHKAN REKOMENDASI DENGAN GAP
            // ============================================
            foreach ($data as $item) {
                $rekomendasiInfo = $this->getRekomendasiWithGap(
                    $item->jenjang_jabatan,
                    $item->level_jawaban,
                    $item->sub_indikator_id,
                    $item->tahap,
                    $item->entity,
                    $item->sub_indikator_code
                );

                $item->rekomendasi_info = $rekomendasiInfo;
            }

            // ============================================
            // HITUNG STATISTIK
            // ============================================
            $totalSubIndikator = $data->count();
            $levelTertinggi = $data->max('level_jawaban') ?? 0;
            $levelTerendah = $data->min('level_jawaban') ?? 0;
            $totalLevel = $data->sum('level_jawaban');
            $rataRataLevel = $totalSubIndikator > 0 ? round($totalLevel / $totalSubIndikator, 2) : 0;

            // Format tanggal
            $start_date = date('d F Y', strtotime($kegiatan->start_date));
            $end_date = date('d F Y', strtotime($kegiatan->end_date));
            $tanggalTerakhir = $data->max('created_at')
                ? date('d F Y H:i:s', strtotime($data->max('created_at')))
                : '-';

            return view('ptk.detail-riwayat', [
                'ptk' => $ptk,
                'kegiatan' => $kegiatan,
                'data' => $data, // Data jawaban dengan rekomendasi
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
     * Fungsi untuk mendapatkan rekomendasi dengan GAP level
     * (Sama seperti di HasilInstrumenController)
     */
    private function getRekomendasiWithGap($jenjangJabatan, $levelJawaban, $subIndikatorId, $tahap, $entity, $subIndikatorCode)
    {
        // 1. Tentukan rentang level berdasarkan jenjang jabatan
        $levelRanges = [
            'Pertama' => ['min' => 2, 'max' => 2],  // Hanya level 2
            'Muda'    => ['min' => 2, 'max' => 3],  // Level 2-3
            'Madya'   => ['min' => 2, 'max' => 4],  // Level 2-4
            'Utama'   => ['min' => 2, 'max' => 5]   // Level 2-5
        ];

        $range = $levelRanges[$jenjangJabatan] ?? $levelRanges['Pertama'];
        $levelMin = $range['min'];
        $levelMax = $range['max'];

        // 2. Ambil semua rekomendasi untuk jenjang ini
        $rekomendasiSemua = DB::table('ptk_rekomendasi')
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('tahap', $tahap)
            ->where('entity', $entity)
            ->where('sub_indikator_code', $subIndikatorCode)
            ->whereBetween('level', [$levelMin, $levelMax])
            ->orderBy('level', 'asc')
            ->get();

        // 3. Pisahkan: sudah dicapai vs belum dicapai (GAP)
        $rekomendasiDicapai = [];
        $rekomendasiGap = []; // Level yang belum dicapai

        foreach ($rekomendasiSemua as $rek) {
            $gap = $rek->level - $levelJawaban;

            if ($gap <= 0) {
                // Sudah dicapai atau melampaui
                $rekomendasiDicapai[] = [
                    'level' => $rek->level,
                    'rekomendasi' => $rek->rekomendasi,
                    'gap' => $gap,
                    'status' => $gap < 0 ? 'melampaui' : 'tepat'
                ];
            } else {
                // Belum dicapai (GAP)
                $rekomendasiGap[] = [
                    'level' => $rek->level,
                    'rekomendasi' => $rek->rekomendasi,
                    'gap' => $gap,
                    'status' => 'belum'
                ];
            }
        }

        // 4. Hitung statistik
        $totalLevelHarus = ($levelMax - $levelMin) + 1;
        $levelDicapaiCount = count($rekomendasiDicapai);
        $levelGapCount = count($rekomendasiGap);

        // 5. Tentukan status keseluruhan
        if ($levelGapCount == 0) {
            $status = 'Mencapai Semua Level';
            $statusClass = 'success';
        } elseif ($levelGapCount == 1 && $levelMax - $levelJawaban == 1) {
            $status = 'Mendekati Target';
            $statusClass = 'warning';
        } else {
            $status = 'Perlu Peningkatan';
            $statusClass = 'danger';
        }

        return [
            'jenjang' => $jenjangJabatan,
            'level_jawaban' => $levelJawaban,
            'level_min' => $levelMin,
            'level_max' => $levelMax,
            'rekomendasi_dicapai' => $rekomendasiDicapai,
            'rekomendasi_gap' => $rekomendasiGap,
            'total_level' => $totalLevelHarus,
            'level_dicapai_count' => $levelDicapaiCount,
            'level_gap_count' => $levelGapCount,
            'persentase' => $totalLevelHarus > 0 ? round(($levelDicapaiCount / $totalLevelHarus) * 100, 1) : 0,
            'status' => $status,
            'status_class' => $statusClass,
            'gap_terbesar' => $levelGapCount > 0 ? max(array_column($rekomendasiGap, 'gap')) : 0
        ];
    }

    /**
     * Export PDF hasil instrumen untuk PTK yang sedang login
     */
    public function exportHasilPdf($encode_kegiatan_id, $nip)
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

            // Query data jawaban
            $query = DB::table('ptk_jawaban')
                ->select(
                    'ptk_jawaban.ptk_jawaban_id',
                    'ptk_jawaban.tahap',
                    'ptk_jawaban.level as level_jawaban',
                    'ptk_jawaban.sub_indikator_code',
                    'ptk_jawaban.sub_indikator_id',
                    'ptk_jawaban.bobot',
                    'ptk_jawaban.created_at',
                    'ptk.nama',
                    'ptk.nip',
                    'ptk.pangkat_jabatan_id',
                    'ptk.instansi',
                    'pangkat_jabatan.golongan_ruang',
                    'pangkat_jabatan.pangkat',
                    'pangkat_jabatan.jenjang_jabatan',
                    'pangkat_jabatan.level_kompetensi',
                    'sub_indikator.sub_indikator_name',
                    'kegiatan.kegiatan_name',
                    'kegiatan.entity'
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
                ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
                ->where('ptk_jawaban.kegiatan_id', $kegiatan_id)
                ->where('ptk.nip', $nip)
                ->orderBy('ptk_jawaban.created_at', 'desc');

            $data = $query->get();

            if ($data->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data hasil instrumen');
            }

            // Tambahkan rekomendasi
            foreach ($data as $item) {
                $rekomendasiInfo = $this->getRekomendasiWithGap(
                    $item->jenjang_jabatan,
                    $item->level_jawaban,
                    $item->sub_indikator_id,
                    $item->tahap,
                    $item->entity,
                    $item->sub_indikator_code
                );

                $item->rekomendasi_info = $rekomendasiInfo;
            }

            // Hitung total
            $totalSkor = $data->sum('bobot');
            $totalIndikator = $data->count();

            // Generate PDF
            if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ptk.export-hasil-pdf', [
                    'data' => $data,
                    'ptk' => $ptk,
                    'kegiatan' => $kegiatan,
                    'totalSkor' => $totalSkor,
                    'totalIndikator' => $totalIndikator,
                    'tanggal' => now()->format('d F Y H:i:s')
                ]);

                return $pdf->download('hasil-instrumen-' . $ptk->nip . '-' . date('Ymd-His') . '.pdf');
            } else {
                return view('ptk.export-hasil-pdf', [
                    'data' => $data,
                    'ptk' => $ptk,
                    'kegiatan' => $kegiatan,
                    'totalSkor' => $totalSkor,
                    'totalIndikator' => $totalIndikator,
                    'tanggal' => now()->format('d F Y H:i:s')
                ]);
            }
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





    // App\Http\Controllers\PtkController.php - UPDATE method continueQuiz

    /**
     * Melanjutkan quiz yang belum selesai dengan RESET localStorage dan ambil waktu dari database
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
        // RESET LOCALSTORAGE DAN AMBIL WAKTU DARI DATABASE
        // ============================================
        if ($kegiatan->tahap == 2) {
            // Reset timer di database (ambil waktu sisa terbaru)
            $remainingSeconds = PtkJawabanDetail::resetUserTimer($kegiatan_id, $ptk->ptk_id, 2);

            // Cek apakah waktu sudah habis
            if ($remainingSeconds <= 0) {
                return redirect()->route('quiz.finish', [
                    'encoded_kegiatan_id' => $encode_kegiatan_id,
                    'nip' => $nip
                ])->with('error', 'Waktu pengerjaan telah habis');
            }

            // Simpan flag untuk reset localStorage di halaman quiz nanti
            session([
                'reset_localstorage' => true,
                'quiz2_remaining_seconds' => $remainingSeconds,
                'quiz2_database_time' => PtkJawabanDetail::formatRemainingTime($remainingSeconds)
            ]);

            // Debug log
            \Log::info("Continue Quiz - Reset timer", [
                'kegiatan_id' => $kegiatan_id,
                'ptk_id' => $ptk->ptk_id,
                'remaining_seconds' => $remainingSeconds,
                'formatted_time' => PtkJawabanDetail::formatRemainingTime($remainingSeconds)
            ]);
        }

        // ============================================
        // LOGIKA POSISI LANJUTAN (SAMA SEPERTI SEBELUMNYA)
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
                                }
                            }
                            break;

                        case 4:
                        case 5:
                            if ($lastBobot == 4) {
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
