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

        $levelColors = [
            1 => '#17a212',
            2 => '#17a2b8',
            3 => '#007bff',
            4 => '#ffc107',
            5 => '#28a745',
        ];

        // Ambil data analisis
        $analisisData = $this->getDetailPtkPerSubIndikator($request);

        // Ambil data untuk dropdown filter
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
     * ============================================================
     * PERBAIKAN UTAMA: Query yang benar dan pagination terisolasi
     * ============================================================
     */
    private function getDetailPtkPerSubIndikator(Request $request)
    {
        $targetLevels = [
            'Pertama' => ['min' => 2, 'max' => 2, 'target' => 2],
            'Muda'    => ['min' => 2, 'max' => 3, 'target' => 3],
            'Madya'   => ['min' => 2, 'max' => 4, 'target' => 4],
            'Utama'   => ['min' => 2, 'max' => 5, 'target' => 5],
        ];

        // ============================================================
        // 1. Ambil data gap dari ptk_jawaban_rekomendasi
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
                DB::raw('(
                    SELECT pj.level
                    FROM ptk_jawaban as pj
                    WHERE pj.ptk_id = pjr.ptk_id
                    AND pj.sub_indikator_id = pjr.sub_indikator_id
                    AND pj.kegiatan_id = pjr.kegiatan_id
                    ORDER BY pj.ptk_jawaban_id DESC
                    LIMIT 1
                ) as level_dicapai')
            )
            ->join('ptk', 'pjr.ptk_id', '=', 'ptk.ptk_id')
            ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->join('kegiatan', 'pjr.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('sub_indikator', 'pjr.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->whereIn('pangkat_jabatan.jenjang_jabatan', ['Pertama', 'Muda', 'Madya', 'Utama']);

        // Apply filters
        if ($request->filled('kegiatan_id'))
            $gapQuery->where('pjr.kegiatan_id', $request->kegiatan_id);

        if ($request->filled('pangkat_jabatan_id')) {
            $pangkat = DB::table('pangkat_jabatan')
                ->where('pangkat_jabatan_id', $request->pangkat_jabatan_id)
                ->value('jenjang_jabatan');
            if ($pangkat) $gapQuery->where('pangkat_jabatan.jenjang_jabatan', $pangkat);
        }

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

        $gapData = $gapQuery->get();

        if ($gapData->isEmpty()) {
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
        // 2. Hitung gap untuk setiap PTK per sub indikator
        // ============================================================
        $ptkGapMap = [];

        foreach ($gapData as $row) {
            $jenjang = $row->jenjang_jabatan;

            // AMBIL TARGET LEVEL SESUAI JENJANG - SAMA KAYA ANALISIS CONTROLLER
            $targetLevelConfig = $targetLevels[$jenjang] ?? ['min' => 2, 'max' => 2, 'target' => 2];
            $levelMin = $targetLevelConfig['min'];
            $levelMax = $targetLevelConfig['max'];
            $targetLevel = $targetLevelConfig['target']; // ← INI YANG PENTING

            $levelDicapai = (int) ($row->level_dicapai ?? 1);

            // JANGAN PAKAI $row->level_gap LANGSUNG!
            // Hitung level_harus yang sebenarnya: dari levelDicapai+1 sampai targetLevel
            // Tapi hanya jika levelDicapai < targetLevel

            if ($levelDicapai < $targetLevel) {
                // Loop untuk setiap level yang harus dicapai (sama kayak AnalisisController)
                for ($levelHarus = $levelDicapai + 1; $levelHarus <= $targetLevel; $levelHarus++) {
                    // Validasi: levelHarus harus dalam range yang valid
                    if ($levelHarus >= $levelMin && $levelHarus <= $targetLevel) {
                        $key = $row->ptk_id . '_' . $row->sub_indikator_id . '_' . $levelDicapai . '_' . $levelHarus;

                        if (!isset($ptkGapMap[$key])) {
                            $ptkGapMap[$key] = [
                                'ptk_id' => $row->ptk_id,
                                'sub_indikator_id' => $row->sub_indikator_id,
                                'sub_indikator_code' => $row->sub_indikator_code,
                                'sub_indikator_name' => $row->sub_indikator_name,
                                'jenjang_jabatan' => $jenjang,
                                'entity' => $row->entity,
                                'tahap' => $row->tahap,
                                'level_dicapai' => $levelDicapai,
                                'target_level' => $targetLevel,      // ← DARI CONFIG
                                'level_harus' => $levelHarus,        // ← DIHITUNG, BUKAN DARI DB
                                'level_min' => $levelMin,            // ← TAMBAHAN
                                'level_max' => $levelMax,            // ← TAMBAHAN
                                'gap' => $levelHarus - $levelDicapai // ← DIHITUNG ULANG
                            ];
                        }
                    }
                }
            }
        }

        // ============================================================
        // 3. Ambil data PTK detail
        // ============================================================
        $uniquePtkIds = array_unique(array_column($ptkGapMap, 'ptk_id'));
        $totalUniquePtk = count($uniquePtkIds);
        $totalUniqueSubIndikator = count(array_unique(array_column($ptkGapMap, 'sub_indikator_id')));

        $ptkDetails = DB::table('ptk')
            ->select(
                'ptk.ptk_id',
                'ptk.nip',
                'ptk.nama',
                'ptk.no_hp',
                'ptk.instansi',
                'ptk.jenis_kelamin',
                'sekolah.nama_sekolah',
                'kota.nama_kota',
                'jenjang_pendidikan.jenjang_pendidikan'
            )
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->whereIn('ptk.ptk_id', $uniquePtkIds)
            ->get()
            ->keyBy('ptk_id');

        // ============================================================
        // 4. Ambil rekomendasi
        // ============================================================
        $allSubIds = array_unique(array_column($ptkGapMap, 'sub_indikator_id'));
        $rekomendasiMap = DB::table('ptk_rekomendasi')
            ->whereIn('sub_indikator_id', $allSubIds)
            ->get()
            ->groupBy('sub_indikator_id');

        // ============================================================
        // 5. Bangun struktur per jenjang
        // ============================================================
        $byJenjang = [];
        foreach ($ptkGapMap as $item) {
            $jenjang = $item['jenjang_jabatan'];
            $subId = $item['sub_indikator_id'];
            $levelDicapai = $item['level_dicapai'];

            if (!isset($byJenjang[$jenjang])) {
                $byJenjang[$jenjang] = [];
            }

            if (!isset($byJenjang[$jenjang][$subId])) {
                $byJenjang[$jenjang][$subId] = [
                    'sub_indikator_id' => $subId,
                    'sub_indikator_code' => $item['sub_indikator_code'],
                    'sub_indikator_name' => $item['sub_indikator_name'],
                    'entity' => $item['entity'],
                    'tahap' => $item['tahap'],
                    'target_level' => $item['target_level'],
                    'level_min' => $item['level_min'],    // ← TAMBAH INI
                    'level_max' => $item['level_max'],    // ← TAMBAH INI
                    'levels' => []
                ];
            }

            if (!isset($byJenjang[$jenjang][$subId]['levels'][$levelDicapai])) {
                $byJenjang[$jenjang][$subId]['levels'][$levelDicapai] = [];
            }
            $byJenjang[$jenjang][$subId]['levels'][$levelDicapai][] = $item['ptk_id'];
        }

        // ============================================================
        // 6. Bangun hasil akhir dengan pagination terisolasi
        // ============================================================
        $order = ['Pertama', 'Muda', 'Madya', 'Utama'];
        $result = [];
        $allPtks = [];
        $ptkGapCount = [];

        // Ambil semua parameter query dari request untuk dipertahankan
        $queryParams = $request->except(['page', 'lvl_*']);
        $baseUrl = url('/analisis/rekomendasi-gap');

        foreach ($order as $jenjang) {
            if (!isset($byJenjang[$jenjang])) continue;

            $targetLevel = $targetLevels[$jenjang];
            $subIndikatorList = $byJenjang[$jenjang];
            $detailPerSubIndikator = [];
            $totalPtkJenjang = 0;

            foreach ($subIndikatorList as $subId => $subData) {
                $ptkPerLevel = [];

                foreach ($subData['levels'] as $levelDicapai => $ptkIds) {
                    $ptcCount = count($ptkIds);
                    $totalPtkJenjang += $ptcCount;

                    // Ambil rekomendasi - SESUAIKAN DENGAN LEVEL YANG BENAR
                    $rekomendasiPelatihan = [];
                    $subRekoms = $rekomendasiMap->get($subId, collect());

                    // Loop dari levelDicapai+1 sampai targetLevel (sama kayak AnalisisController)
                    for ($lvlTarget = $levelDicapai + 1; $lvlTarget <= $targetLevel; $lvlTarget++) {
                        // Validasi sama dengan AnalisisController
                        $levelMin = $subData['level_min'] ?? 2;  // Ambil dari data yang sudah disimpan
                        $levelMax = $subData['level_max'] ?? $targetLevel;

                        if ($lvlTarget >= $levelMin && $lvlTarget <= $targetLevel) {
                            $rek = $subRekoms->firstWhere('level', $lvlTarget);
                            $rekomendasiText = $rek ? $rek->rekomendasi : $this->getRekomendasiText(
                                $subId,
                                $subData['sub_indikator_code'],
                                $subData['tahap'] ?? '',
                                $subData['entity'] ?? '',
                                $levelDicapai,
                                $lvlTarget
                            );

                            $rekomendasiPelatihan[] = [
                                'level_target' => $lvlTarget,
                                'rekomendasi' => $rekomendasiText
                            ];
                        }
                    }

                    // ============================================================
                    // PERBAIKAN UTAMA: Pagination dengan pageName unik
                    // ============================================================
                    $pageName = 'lvl_' . substr($jenjang, 0, 2) . '_' . $subId . '_' . $levelDicapai;

                    // Ambil current page dari request dengan pageName yang spesifik
                    $currentPage = $request->input($pageName, 1);
                    $perPage = 20;

                    $paginatedIds = array_slice($ptkIds, ($currentPage - 1) * $perPage, $perPage);

                    $ptkListPage = [];
                    foreach ($paginatedIds as $ptkId) {
                        $ptk = $ptkDetails->get($ptkId);
                        if (!$ptk) continue;

                        $ptkListPage[] = [
                            'ptk_id' => $ptkId,
                            'nip' => $ptk->nip,
                            'nama' => $ptk->nama,
                            'no_hp' => $ptk->no_hp,
                            'sekolah' => $ptk->nama_sekolah,
                            'instansi' => $ptk->instansi,
                            'kota' => $ptk->nama_kota,
                            'jenjang_pendidikan' => $ptk->jenjang_pendidikan ?? '-',
                            'level_dicapai' => $levelDicapai,
                            'sub_indikator_id' => $subId,
                        ];

                        if (!isset($ptkGapCount[$ptkId])) $ptkGapCount[$ptkId] = 0;
                        $ptkGapCount[$ptkId]++;
                    }

                    // ============================================================
                    // PERBAIKAN: Buat URL custom untuk pagination dengan parameter lengkap
                    // ============================================================
                    $urlGenerator = function ($page) use ($baseUrl, $queryParams, $pageName) {
                        $params = $queryParams;
                        $params[$pageName] = $page;
                        return $baseUrl . '?' . http_build_query($params);
                    };

                    $paginator = new LengthAwarePaginator(
                        $ptkListPage,
                        $ptcCount,
                        $perPage,
                        $currentPage,
                        [
                            'path' => $baseUrl,
                            'pageName' => $pageName,
                            'query' => $queryParams  // Tambahkan query params
                        ]
                    );

                    // Override URL generator untuk mempertahankan semua parameter
                    $paginator->withPath($baseUrl);
                    $paginator->appends($queryParams);
                    $paginator->setPageName($pageName);

                    $ptkPerLevel[] = [
                        'level' => (int) $levelDicapai,
                        'ptk_count' => $ptcCount,
                        'ptk_list' => $paginator,
                        'status' => 'BELUM MENCAPAI',
                        'gap' => $targetLevel - $levelDicapai,
                        'rekomendasi_pelatihan' => $rekomendasiPelatihan,
                    ];
                }

                if (empty($ptkPerLevel)) continue;

                usort($ptkPerLevel, fn($a, $b) => $a['level'] - $b['level']);

                $detailPerSubIndikator[] = [
                    'sub_indikator_id' => $subId,
                    'sub_indikator_code' => $subData['sub_indikator_code'],
                    'sub_indikator_name' => $subData['sub_indikator_name'],
                    'target_level' => $targetLevel,
                    'ptk_per_level' => $ptkPerLevel,
                    'total_ptk' => array_sum(array_map(fn($l) => $l['ptk_count'], $ptkPerLevel)),
                    'total_levels' => count($ptkPerLevel),
                ];
            }

            if (empty($detailPerSubIndikator)) continue;

            $result[] = [
                'jenjang_jabatan' => $jenjang,
                'target_level' => $targetLevel,
                'total_ptk' => $totalPtkJenjang,
                'total_sub_indikator' => count($detailPerSubIndikator),
                'detail_per_sub_indikator' => $detailPerSubIndikator,
            ];
        }

        // ============================================================
        // 7. Ringkasan PTK dengan pagination terisolasi
        // ============================================================
        $counter = 1;
        foreach ($uniquePtkIds as $ptkId) {
            $ptk = $ptkDetails->get($ptkId);
            if (!$ptk) continue;

            $ptkJenjang = '';
            foreach ($ptkGapMap as $item) {
                if ($item['ptk_id'] == $ptkId) {
                    $ptkJenjang = $item['jenjang_jabatan'];
                    break;
                }
            }

            $allPtks[] = [
                'no' => $counter++,
                'ptk_id' => $ptkId,
                'nip' => $ptk->nip,
                'nama' => $ptk->nama,
                'no_hp' => $ptk->no_hp ?? '-',
                'jenjang' => $ptkJenjang,
                'jenjang_pendidikan' => $ptk->jenjang_pendidikan ?? '-',
                'sekolah' => $ptk->nama_sekolah ?? ($ptk->instansi ?? '-'),
                'kota' => $ptk->nama_kota ?? '-',
                'gap_count' => $ptkGapCount[$ptkId] ?? 0,
                'rekomendasi' => '-',
            ];
        }

        usort($allPtks, fn($a, $b) => strcmp($a['nama'], $b['nama']));
        foreach ($allPtks as $i => &$p) $p['no'] = $i + 1;
        unset($p);

        // Pagination untuk ringkasan
        $currentPageRingkasan = $request->input('page_ringkasan', 1);
        $perPageRingkasan = 20;
        $paginatedPtks = new LengthAwarePaginator(
            array_slice($allPtks, ($currentPageRingkasan - 1) * $perPageRingkasan, $perPageRingkasan),
            count($allPtks),
            $perPageRingkasan,
            $currentPageRingkasan,
            [
                'path' => $baseUrl,
                'pageName' => 'page_ringkasan',
                'query' => $queryParams
            ]
        );
        $paginatedPtks->appends($queryParams);

        return [
            'detail_per_jenjang' => $result,
            'total_data_ptk' => count($ptkGapMap),
            'total_unique_ptk' => $totalUniquePtk,
            'total_unique_sub_indikator' => $totalUniqueSubIndikator,
            'all_ptks' => $allPtks,
            'all_ptks_paginated' => $paginatedPtks,
        ];
    }

    private function getRekomendasiText($subIndikatorId, $subIndikatorCode, $tahap, $entity, $levelDicapai, $levelTarget)
    {
        $rekomendasi = DB::table('ptk_rekomendasi')
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('sub_indikator_code', $subIndikatorCode)
            ->where('tahap', $tahap)
            ->where('entity', $entity)
            ->where('level', $levelTarget)
            ->first();

        if ($rekomendasi) {
            return $rekomendasi->rekomendasi;
        }

        $rekomendasi = DB::table('ptk_rekomendasi')
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('sub_indikator_code', $subIndikatorCode)
            ->where('level', $levelTarget)
            ->first();

        if ($rekomendasi) {
            return $rekomendasi->rekomendasi;
        }

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
            return "Perlu meningkatkan dari $levelDicapaiName ke $levelTargetName (naik 1 level)";
        } else {
            return "Perlu meningkatkan dari $levelDicapaiName ke $levelTargetName (naik $gap level)";
        }
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
