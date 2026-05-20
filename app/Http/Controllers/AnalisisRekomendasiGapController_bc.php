<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class AnalisisRekomendasiGapController extends Controller
{
    /**
     * Menampilkan halaman detail PTK per sub indikator dan level
     */
    public function index(Request $request)
    {
        $title = 'Detail PTK dengan Kebutuhan Belajar';

        // Definisi warna level
        $levelColors = [
            1 => '#17a212',
            2 => '#17a2b8',
            3 => '#007bff',
            4 => '#ffc107',
            5 => '#28a745',
        ];

        // Ambil data analisis - otomatis pakai filter dari URL
        $analisisData = $this->getDetailPtkPerSubIndikator($request);

        // Paginate untuk ringkasan semua PTK
        if (isset($analisisData['all_ptks']) && !empty($analisisData['all_ptks'])) {
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 20;
            $currentItems = array_slice($analisisData['all_ptks'], ($currentPage - 1) * $perPage, $perPage);
            $allPtksPaginated = new LengthAwarePaginator(
                $currentItems,
                count($analisisData['all_ptks']),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );
            $analisisData['all_ptks_paginated'] = $allPtksPaginated;
        }

        // Ambil data untuk dropdown filter (untuk tampilan saja)
        $kegiatans = DB::table('kegiatan')->get();
        $pangkatJabatans = DB::table('pangkat_jabatan')->get();
        $jenisPtkList = DB::table('jenis_ptk')->get();
        $kotas = DB::table('kota')->get();
        $jenjangPendidikanList = DB::table('jenjang_pendidikan')->get();
        $bentukPendidikanList = DB::table('sekolah')
            ->select('bentuk_pendidikan')
            ->whereNotNull('bentuk_pendidikan')
            ->distinct()
            ->orderBy('bentuk_pendidikan')
            ->get();

        return view('analisis.rekomendasi-gap', compact(
            'title',
            'analisisData',
            'request',
            'kegiatans',
            'pangkatJabatans',
            'jenisPtkList',
            'kotas',
            'jenjangPendidikanList',
            'bentukPendidikanList',
            'levelColors'
        ));
    }

    /**
     * Method untuk mengambil data DETAIL PTK per sub indikator dan level
     * Menggunakan ptk_jawaban_rekomendasi (konsisten dengan AnalisisController)
     */
    private function getDetailPtkPerSubIndikator(Request $request)
    {
        $targetLevels = [
            'Pertama' => 2,
            'Muda'    => 3,
            'Madya'   => 4,
            'Utama'   => 5
        ];

        // ============================================================
        // QUERY dari ptk_jawaban_rekomendasi (SAMA dengan AnalisisController)
        // ============================================================
        $gapQuery = DB::table('ptk_jawaban_rekomendasi as pjr')
            ->select(
                'pjr.ptk_id',
                'pjr.kegiatan_id',
                'pjr.sub_indikator_id',
                'pjr.sub_indikator_code',
                'pjr.level_gap',
                'pangkat_jabatan.jenjang_jabatan',
                'sub_indikator.sub_indikator_name',
                'kegiatan.entity',
                'kegiatan.tahap',
                'ptk.nip',
                'ptk.nama',
                'ptk.instansi',
                'ptk.no_hp',
                'ptk.jenis_kelamin',
                'sekolah.nama_sekolah',
                'kota.nama_kota',
                'jenjang_pendidikan.jenjang_pendidikan'
            )
            ->join('ptk', 'pjr.ptk_id', '=', 'ptk.ptk_id')
            ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->join('kegiatan', 'pjr.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('sub_indikator', 'pjr.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->whereIn('pangkat_jabatan.jenjang_jabatan', ['Pertama', 'Muda', 'Madya', 'Utama']);

        // Apply filters
        if ($request->filled('kegiatan_id'))
            $gapQuery->where('pjr.kegiatan_id', $request->kegiatan_id);
        if ($request->filled('pangkat_jabatan_id'))
            $gapQuery->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
        if ($request->filled('jenis_ptk_id'))
            $gapQuery->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        if ($request->filled('kota_id'))
            $gapQuery->where('ptk.kota_id', $request->kota_id);
        if ($request->filled('jenjang_pendidikan_id'))
            $gapQuery->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
        if ($request->filled('bentuk_pendidikan'))
            $gapQuery->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
        if ($request->filled('jenis_kelamin'))
            $gapQuery->where('ptk.jenis_kelamin', $request->jenis_kelamin);
        if ($request->filled('sub_indikator_id'))
            $gapQuery->where('pjr.sub_indikator_id', $request->sub_indikator_id);
        if ($request->filled('jenjang_jabatan'))
            $gapQuery->where('pangkat_jabatan.jenjang_jabatan', $request->jenjang_jabatan);

        $allGapData = $gapQuery->get();

        if ($allGapData->isEmpty()) {
            return [
                'total_data_ptk' => 0,
                'total_unique_ptk' => 0,
                'total_unique_sub_indikator' => 0,
                'detail_per_jenjang' => [],
                'all_ptks' => [],
                'message' => 'Tidak ada data ditemukan'
            ];
        }

        // ============================================================
        // Kelompokkan data per PTK dan hitung level_dicapai
        // level_dicapai = MIN(level_gap) - 1 (SAMA dengan AnalisisController)
        // ============================================================

        $order = ['Pertama', 'Muda', 'Madya', 'Utama'];
        $result = [];
        $byJenjang = $allGapData->groupBy('jenjang_jabatan');
        $allPtks = [];
        $ptkGapCount = [];
        $ptkDetails = []; // Untuk menyimpan info PTK

        foreach ($order as $jenjang) {
            if (!isset($targetLevels[$jenjang]) || !isset($byJenjang[$jenjang])) continue;

            $levelTarget = $targetLevels[$jenjang];
            $jenjangData = $byJenjang[$jenjang];

            // Hitung total PTK unik di jenjang ini
            $totalPtkJenjang = $jenjangData->pluck('ptk_id')->unique()->count();

            $rekomendasiData = [];

            foreach ($jenjangData->groupBy('sub_indikator_id') as $subIndikatorId => $subData) {
                $firstData = $subData->first();
                $totalPtkSubIndikator = $subData->pluck('ptk_id')->unique()->count();

                // Group per PTK untuk mencari MIN level_gap
                $ptkGroups = $subData->groupBy('ptk_id');

                // Hitung level_dicapai per PTK = MIN(level_gap) - 1
                $ptkLevelDicapai = [];
                foreach ($ptkGroups as $ptkId => $ptkRows) {
                    $minGap = $ptkRows->min('level_gap');
                    $levelDicapai = $minGap - 1;
                    $ptkLevelDicapai[$ptkId] = $levelDicapai;

                    // Simpan info PTK untuk ringkasan
                    if (!isset($ptkDetails[$ptkId])) {
                        $row = $ptkRows->first();
                        $ptkDetails[$ptkId] = [
                            'ptk_id' => $ptkId,
                            'nip' => $row->nip,
                            'nama' => $row->nama,
                            'no_hp' => $row->no_hp,
                            'jenjang_jabatan' => $jenjang,
                            'jenjang_pendidikan' => $row->jenjang_pendidikan ?? '-',
                            'nama_sekolah' => $row->nama_sekolah,
                            'instansi' => $row->instansi,
                            'nama_kota' => $row->nama_kota,
                        ];
                    }

                    // Hitung jumlah gap per PTK
                    if (!isset($ptkGapCount[$ptkId])) {
                        $ptkGapCount[$ptkId] = 0;
                    }
                    $ptkGapCount[$ptkId]++;
                }

                // Group PTK berdasarkan level_dicapai
                $groupedByLevel = [];
                foreach ($ptkLevelDicapai as $ptkId => $levelDicapai) {
                    // Hanya tampilkan yang belum mencapai target
                    if ($levelDicapai >= $levelTarget) continue;

                    if (!isset($groupedByLevel[$levelDicapai])) {
                        $groupedByLevel[$levelDicapai] = [];
                    }
                    $groupedByLevel[$levelDicapai][] = $ptkId;
                }

                if (empty($groupedByLevel)) continue;

                $ptkPerLevel = [];

                foreach ($groupedByLevel as $levelDicapai => $ptkIds) {
                    $ptcCount = count($ptkIds);

                    // Ambil rekomendasi untuk level ini
                    $rekomendasiPelatihan = [];
                    $subRekoms = DB::table('ptk_rekomendasi')
                        ->where('sub_indikator_id', $subIndikatorId)
                        ->get()
                        ->keyBy('level');

                    for ($lvlTarget = $levelDicapai + 1; $lvlTarget <= $levelTarget; $lvlTarget++) {
                        $rek = $subRekoms->get($lvlTarget);
                        $rekomendasiPelatihan[] = [
                            'level_target' => $lvlTarget,
                            'rekomendasi' => $rek ? $rek->rekomendasi
                                : "Perlu peningkatan dari Level {$levelDicapai} ke Level {$lvlTarget}"
                        ];
                    }

                    // Pagination per level
                    $pageName = 'lvl_' . substr($jenjang, 0, 2) . '_' . $subIndikatorId . '_' . $levelDicapai;
                    $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
                    $perPage = 20;

                    $paginatedIds = array_slice($ptkIds, ($currentPage - 1) * $perPage, $perPage);

                    $ptkListPage = [];
                    foreach ($paginatedIds as $ptkId) {
                        $ptk = $ptkDetails[$ptkId] ?? null;
                        if (!$ptk) continue;

                        $ptkListPage[] = [
                            'ptk_id' => $ptkId,
                            'nip' => $ptk['nip'],
                            'nama' => $ptk['nama'],
                            'no_hp' => $ptk['no_hp'],
                            'sekolah' => $ptk['nama_sekolah'],
                            'instansi' => $ptk['instansi'],
                            'kota' => $ptk['nama_kota'],
                            'jenjang_pendidikan' => $ptk['jenjang_pendidikan'],
                            'level_dicapai' => $levelDicapai,
                            'sub_indikator_id' => $subIndikatorId,
                        ];
                    }

                    $paginator = new LengthAwarePaginator(
                        $ptkListPage,
                        $ptcCount,
                        $perPage,
                        $currentPage,
                        [
                            'path' => Paginator::resolveCurrentPath(),
                            'pageName' => $pageName
                        ]
                    );

                    $ptkPerLevel[] = [
                        'level' => (int) $levelDicapai,
                        'ptk_count' => $ptcCount,
                        'ptk_list' => $paginator,
                        'status' => 'BELUM MENCAPAI',
                        'gap' => $levelTarget - $levelDicapai,
                        'rekomendasi_pelatihan' => $rekomendasiPelatihan,
                    ];
                }

                if (empty($ptkPerLevel)) continue;

                usort($ptkPerLevel, fn($a, $b) => $a['level'] - $b['level']);

                $rekomendasiData[] = [
                    'sub_indikator_id' => $subIndikatorId,
                    'sub_indikator_code' => $firstData->sub_indikator_code,
                    'sub_indikator_name' => $firstData->sub_indikator_name,
                    'target_level' => $levelTarget,
                    'ptk_per_level' => $ptkPerLevel,
                    'total_ptk' => $totalPtkSubIndikator,
                    'total_levels' => count($ptkPerLevel),
                ];
            }

            if (empty($rekomendasiData)) continue;

            $result[] = [
                'jenjang_jabatan' => $jenjang,
                'target_level' => $levelTarget,
                'total_ptk' => $totalPtkJenjang,
                'total_sub_indikator' => count($rekomendasiData),
                'detail_per_sub_indikator' => $rekomendasiData,
            ];
        }

        // ============================================================
        // Buat ringkasan semua PTK
        // ============================================================
        $allPtks = [];
        $counter = 1;

        foreach ($ptkDetails as $ptkId => $ptk) {
            $allPtks[] = [
                'no' => $counter++,
                'ptk_id' => $ptkId,
                'nip' => $ptk['nip'],
                'nama' => $ptk['nama'],
                'no_hp' => $ptk['no_hp'] ?? '-',
                'jenjang' => $ptk['jenjang_jabatan'],
                'jenjang_pendidikan' => $ptk['jenjang_pendidikan'] ?? '-',
                'sekolah' => $ptk['nama_sekolah'] ?? ($ptk['instansi'] ?? '-'),
                'kota' => $ptk['nama_kota'] ?? '-',
                'gap_count' => $ptkGapCount[$ptkId] ?? 0,
                'rekomendasi' => '-',
            ];
        }

        usort($allPtks, fn($a, $b) => strcmp($a['nama'], $b['nama']));
        foreach ($allPtks as $i => &$p) $p['no'] = $i + 1;
        unset($p);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $paginatedPtks = new LengthAwarePaginator(
            array_slice($allPtks, ($currentPage - 1) * $perPage, $perPage),
            count($allPtks),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return [
            'detail_per_jenjang' => $result,
            'total_data_ptk' => $allGapData->count(),
            'total_unique_ptk' => count($ptkDetails),
            'total_unique_sub_indikator' => $allGapData->unique('sub_indikator_id')->count(),
            'all_ptks' => $allPtks,
            'all_ptks_paginated' => $paginatedPtks,
        ];
    }

    /**
     * Method untuk mengambil SEMUA rekomendasi pelatihan dari level dicapai sampai target
     */
    private function getAllRekomendasiForPtk($ptkId, $subIndikatorId, $tahap, $entity, $subIndikatorCode, $levelDicapai, $targetLevel)
    {
        $allRekomendasi = [];

        // Cari SEMUA rekomendasi yang tersimpan di ptk_jawaban_rekomendasi untuk PTK ini
        $existingRekomendasi = DB::table('ptk_jawaban_rekomendasi')
            ->select('ptk_rekomendasi.level', 'ptk_rekomendasi.rekomendasi')
            ->leftJoin('ptk_rekomendasi', function ($join) use ($subIndikatorId) {
                $join->on('ptk_jawaban_rekomendasi.sub_indikator_id', '=', 'ptk_rekomendasi.sub_indikator_id')
                    ->whereRaw('ptk_jawaban_rekomendasi.level_gap = ptk_rekomendasi.level');
            })
            ->where('ptk_jawaban_rekomendasi.ptk_id', $ptkId)
            ->where('ptk_jawaban_rekomendasi.sub_indikator_id', $subIndikatorId)
            ->where('ptk_jawaban_rekomendasi.level_gap', '>', $levelDicapai)
            ->where('ptk_jawaban_rekomendasi.level_gap', '<=', $targetLevel)
            ->orderBy('ptk_jawaban_rekomendasi.level_gap', 'asc')
            ->get();

        // Jika ada rekomendasi yang tersimpan, tambahkan ke array
        foreach ($existingRekomendasi as $rek) {
            if (!empty($rek->rekomendasi)) {
                $allRekomendasi[] = [
                    'level_target' => $rek->level,
                    'rekomendasi' => $rek->rekomendasi
                ];
            }
        }

        // Jika masih ada level yang belum dapat rekomendasi, cari dari ptk_rekomendasi
        for ($levelTarget = $levelDicapai + 1; $levelTarget <= $targetLevel; $levelTarget++) {
            $found = false;
            foreach ($allRekomendasi as $rek) {
                if ($rek['level_target'] == $levelTarget) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $rekomendasi = $this->getRekomendasiPelatihan(
                    $subIndikatorId,
                    $tahap,
                    $entity,
                    $subIndikatorCode,
                    $levelDicapai,
                    $levelTarget
                );

                $allRekomendasi[] = [
                    'level_target' => $levelTarget,
                    'rekomendasi' => $rekomendasi
                ];
            }
        }

        // Urutkan berdasarkan level target
        usort($allRekomendasi, function ($a, $b) {
            return $a['level_target'] - $b['level_target'];
        });

        return $allRekomendasi;
    }

    /**
     * Method untuk mengambil rekomendasi pelatihan untuk level tertentu
     */
    private function getRekomendasiPelatihan($subIndikatorId, $tahap, $entity, $subIndikatorCode, $levelDicapai, $levelTarget)
    {
        // Cari rekomendasi spesifik dari database terlebih dahulu
        $rekomendasi = DB::table('ptk_rekomendasi')
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('tahap', $tahap)
            ->where('entity', $entity)
            ->where('sub_indikator_code', $subIndikatorCode)
            ->where('level', $levelTarget)
            ->first();

        if ($rekomendasi) {
            return $rekomendasi->rekomendasi;
        }

        // Jika tidak ditemukan, cari yang lebih umum
        $rekomendasi = DB::table('ptk_rekomendasi')
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('sub_indikator_code', $subIndikatorCode)
            ->where('level', $levelTarget)
            ->first();

        if ($rekomendasi) {
            return $rekomendasi->rekomendasi;
        }

        // Jika masih tidak ditemukan, buat rekomendasi generik
        $levelNames = [
            1 => 'Dasar',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan'
        ];

        $levelDicapaiName = $levelNames[$levelDicapai] ?? "Level $levelDicapai";
        $levelTargetName = $levelNames[$levelTarget] ?? "Level $levelTarget";
        $gap = $levelTarget - $levelDicapai;

        if ($gap == 1) {
            return "Perlu peningkatan dari $levelDicapaiName ke $levelTargetName untuk meningkatkan kompetensi";
        } else {
            return "Perlu peningkatan signifikan dari $levelDicapaiName ke $levelTargetName (naik $gap level) melalui pelatihan intensif";
        }
    }

    /**
     * Method untuk mengambil rekomendasi spesifik untuk PTK
     */
    private function getRekomendasiForPtk($ptkId, $subIndikatorId, $levelDicapai, $targetLevel)
    {
        // Cari rekomendasi dari ptk_jawaban_rekomendasi
        $rekomendasi = DB::table('ptk_jawaban_rekomendasi')
            ->select('rekomendasi')
            ->where('ptk_id', $ptkId)
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('level_gap', '>', $levelDicapai)
            ->where('level_gap', '<=', $targetLevel)
            ->first();

        if ($rekomendasi && !empty($rekomendasi->rekomendasi)) {
            return $rekomendasi->rekomendasi;
        }

        // Jika tidak ada, cari dari ptk_rekomendasi untuk level berikutnya
        $nextLevel = $levelDicapai + 1;
        $rekomendasi = DB::table('ptk_rekomendasi')
            ->select('rekomendasi')
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('level', $nextLevel)
            ->first();

        if ($rekomendasi) {
            return $rekomendasi->rekomendasi;
        }

        return "Perlu peningkatan dari Level $levelDicapai menuju Level $targetLevel";
    }

    /**
     * Helper functions untuk mendapatkan nama filter
     */
    private function getKegiatanName($id)
    {
        $kegiatan = DB::table('kegiatan')->where('kegiatan_id', $id)->first();
        return $kegiatan ? $kegiatan->kegiatan_name : '-';
    }

    private function getJenjangName($id)
    {
        $jenjang = DB::table('pangkat_jabatan')->where('pangkat_jabatan_id', $id)->first();
        return $jenjang ? $jenjang->jenjang_jabatan : '-';
    }

    private function getJenisPtkName($id)
    {
        $jenis = DB::table('jenis_ptk')->where('jenis_ptk_id', $id)->first();
        return $jenis ? $jenis->jenis_ptk : '-';
    }

    private function getKotaName($id)
    {
        $kota = DB::table('kota')->where('kota_id', $id)->first();
        return $kota ? $kota->nama_kota : '-';
    }

    private function getJenjangPendidikanName($id)
    {
        $jenjang = DB::table('jenjang_pendidikan')->where('jenjang_pendidikan_id', $id)->first();
        return $jenjang ? $jenjang->jenjang_pendidikan : '-';
    }

    /**
     * Get color for level badge
     */
    private function getLevelColor($level)
    {
        $colors = [
            1 => 'rgba(220, 53, 69, 0.8)',
            2 => '#17a2b8',
            3 => '#007bff',
            4 => '#ffc107',
            5 => '#28a745'
        ];
        return $colors[$level] ?? '#17a2b8';
    }

    /**
     * Export ke Excel (panggil dari ExportGapController)
     */
    public function exportExcel(Request $request)
    {
        // Panggil method dari ExportGapController
        return app(ExportGapController::class)->exportRekomendasiGap($request);
    }
}
