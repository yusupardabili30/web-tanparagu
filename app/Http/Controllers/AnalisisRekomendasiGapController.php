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
     * DIPERBAIKI: Menghilangkan duplikat dan menampilkan semua rekomendasi
     */
    private function getDetailPtkPerSubIndikator(Request $request)
    {
        // Tentukan target level per jenjang
        $targetLevels = [
            'Pertama' => 2,
            'Muda'    => 3,
            'Madya'   => 4,
            'Utama'   => 5
        ];

        // QUERY UTAMA: Gabungkan dengan ptk_jawaban_rekomendasi
        $query = DB::table('ptk_jawaban')
            ->select([
                'ptk.ptk_id',
                'ptk.nip',
                'ptk.nama',
                'ptk.instansi',
                'ptk.no_hp',
                'ptk.jenis_kelamin',
                'pangkat_jabatan.jenjang_jabatan',
                'sekolah.nama_sekolah',
                'sekolah.bentuk_pendidikan',
                'kota.nama_kota',
                'kegiatan.kegiatan_name',
                'kegiatan.entity',
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.sub_indikator_code',
                'sub_indikator.sub_indikator_name',
                'ptk_jawaban.level as level_dicapai',
                'ptk_jawaban.tahap',
                'ptk_jawaban.created_at',
                'jenjang_pendidikan.jenjang_pendidikan',
                'jenis_ptk.jenis_ptk',
                'kegiatan.tahap as kegiatan_tahap',
                'kegiatan.entity as kegiatan_entity',
                // Ambil dari ptk_jawaban_rekomendasi
                'ptk_jawaban_rekomendasi.level_gap',
                // Ambil rekomendasi dari ptk_rekomendasi
                'ptk_rekomendasi.rekomendasi as rekomendasi_ptk'
            ])
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id')
            // LEFT JOIN dengan ptk_jawaban_rekomendasi
            ->leftJoin('ptk_jawaban_rekomendasi', function ($join) {
                $join->on('ptk_jawaban.ptk_id', '=', 'ptk_jawaban_rekomendasi.ptk_id')
                    ->on('ptk_jawaban.sub_indikator_id', '=', 'ptk_jawaban_rekomendasi.sub_indikator_id');
            })
            // LEFT JOIN dengan ptk_rekomendasi untuk mendapatkan teks rekomendasi
            ->leftJoin('ptk_rekomendasi', function ($join) {
                $join->on('ptk_jawaban.sub_indikator_id', '=', 'ptk_rekomendasi.sub_indikator_id')
                    ->whereRaw('ptk_jawaban_rekomendasi.level_gap = ptk_rekomendasi.level');
            })
            ->where('ptk_jawaban.level', '>=', 1)
            ->whereNotNull('pangkat_jabatan.jenjang_jabatan')
            ->whereIn('pangkat_jabatan.jenjang_jabatan', ['Pertama', 'Muda', 'Madya', 'Utama']);

        // ================================================
        // APPLY FILTERS FROM REQUEST
        // ================================================
        if ($request->filled('kegiatan_id')) {
            $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        }
        if ($request->filled('pangkat_jabatan_id')) {
            $query->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
        }
        if ($request->filled('jenis_ptk_id')) {
            $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        }
        if ($request->filled('kota_id')) {
            $query->where('ptk.kota_id', $request->kota_id);
        }
        if ($request->filled('jenjang_pendidikan_id')) {
            $query->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
        }
        if ($request->filled('bentuk_pendidikan')) {
            $query->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
        }
        if ($request->filled('jenis_kelamin')) {
            $query->where('ptk.jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter tambahan
        if ($request->filled('sub_indikator_id')) {
            $query->where('ptk_jawaban.sub_indikator_id', $request->sub_indikator_id);
        }
        if ($request->filled('jenjang_jabatan')) {
            $query->where('pangkat_jabatan.jenjang_jabatan', $request->jenjang_jabatan);
        }

        $data = $query->orderBy('pangkat_jabatan.jenjang_jabatan')
            ->orderBy('ptk_jawaban.sub_indikator_code')
            ->orderBy('ptk_jawaban.level')
            ->orderBy('ptk.nama')
            ->get();

        if ($data->isEmpty()) {
            return [
                'total_data_ptk' => 0,
                'total_unique_ptk' => 0,
                'total_unique_sub_indikator' => 0,
                'detail_per_jenjang' => [],
                'message' => 'Tidak ada data ditemukan berdasarkan filter yang diterapkan'
            ];
        }

        // ================================================
        // FILTER HANYA YANG ADA GAP (tidak mencapai target)
        // DAN HILANGKAN DUPLIKAT
        // ================================================
        $dataWithGap = collect();
        $uniqueKeys = []; // Untuk melacak data unik

        foreach ($data as $row) {
            $jenjang = $row->jenjang_jabatan;
            $levelDicapai = $row->level_dicapai;
            $targetLevel = $targetLevels[$jenjang] ?? 2;

            // Buat key unik: PTK + Sub Indikator
            $uniqueKey = $row->ptk_id . '_' . $row->sub_indikator_id;

            // HANYA SIMPAN JIKA:
            // 1. Ada gap (level_dicapai < targetLevel)
            // 2. Belum ada dalam uniqueKeys
            if ($levelDicapai < $targetLevel && !isset($uniqueKeys[$uniqueKey])) {
                $dataWithGap->push($row);
                $uniqueKeys[$uniqueKey] = true;
            }
        }

        if ($dataWithGap->isEmpty()) {
            return [
                'total_data_ptk' => 0,
                'total_unique_ptk' => 0,
                'total_unique_sub_indikator' => 0,
                'detail_per_jenjang' => [],
                'message' => 'Tidak ada PTK yang memiliki gap (semua sudah mencapai target)'
            ];
        }

        // Kelompokkan data per jenjang
        $result = [];
        $groupedByJenjang = $dataWithGap->groupBy('jenjang_jabatan');

        // Kumpulkan semua PTK unik untuk ringkasan
        $summaryPtks = [];
        $counter = 1;
        $ptkGapDetails = []; // Untuk menyimpan detail gap per PTK

        foreach ($groupedByJenjang as $jenjang => $jenjangData) {
            if (!isset($targetLevels[$jenjang])) continue;

            $targetLevel = $targetLevels[$jenjang];

            // Kelompokkan per sub indikator
            $groupedBySubIndikator = $jenjangData->groupBy('sub_indikator_id');
            $detailPerSubIndikator = [];

            foreach ($groupedBySubIndikator as $subIndikatorId => $subData) {
                $firstSubData = $subData->first();

                // Kelompokkan PTK per level dicapai
                $groupedByLevel = $subData->groupBy('level_dicapai');
                $ptkPerLevel = [];

                foreach ($groupedByLevel as $levelDicapai => $levelData) {
                    $ptkList = [];

                    // Ambil rekomendasi untuk level ini
                    $rekomendasiText = [];
                    $firstPtk = $levelData->first();

                    if ($firstPtk) {
                        // Ambil SEMUA rekomendasi dari level dicapai sampai target
                        $allRekomendasi = $this->getAllRekomendasiForPtk(
                            $firstPtk->ptk_id,
                            $firstPtk->sub_indikator_id,
                            $firstPtk->tahap ?? $firstPtk->kegiatan_tahap,
                            $firstPtk->entity ?? $firstPtk->kegiatan_entity,
                            $firstPtk->sub_indikator_code,
                            $levelDicapai,
                            $targetLevel
                        );

                        $rekomendasiText = $allRekomendasi;
                    }

                    // Kumpulkan PTK untuk level ini
                    foreach ($levelData as $ptk) {
                        // Ambil SEMUA rekomendasi untuk PTK ini
                        $allRekomendasi = $this->getAllRekomendasiForPtk(
                            $ptk->ptk_id,
                            $ptk->sub_indikator_id,
                            $ptk->tahap ?? $ptk->kegiatan_tahap,
                            $ptk->entity ?? $ptk->kegiatan_entity,
                            $ptk->sub_indikator_code,
                            $levelDicapai,
                            $targetLevel
                        );

                        // Format rekomendasi untuk tampilan
                        $rekomendasiFormatted = [];
                        foreach ($allRekomendasi as $rek) {
                            $rekomendasiFormatted[] = "Level {$rek['level_target']}: {$rek['rekomendasi']}";
                        }
                        $rekomendasiTextPtk = implode('<br>', $rekomendasiFormatted);

                        $ptkList[] = [
                            'ptk_id' => $ptk->ptk_id,
                            'nip' => $ptk->nip,
                            'nama' => $ptk->nama,
                            'sekolah' => $ptk->nama_sekolah,
                            'jenjang_pendidikan' => $ptk->jenjang_pendidikan ?? '-',
                            'instansi' => $ptk->instansi,
                            'kota' => $ptk->nama_kota,
                            'no_hp' => $ptk->no_hp,
                            'entity' => $ptk->entity,
                            'created_at' => $ptk->created_at,
                            'level_dicapai' => $levelDicapai,
                            'sub_indikator_id' => $subIndikatorId,
                            'rekomendasi' => $rekomendasiTextPtk,
                            'all_rekomendasi' => $allRekomendasi // Simpan semua rekomendasi untuk summary
                        ];

                        // Simpan detail untuk summary
                        $ptkId = $ptk->ptk_id;
                        if (!isset($ptkGapDetails[$ptkId])) {
                            $ptkGapDetails[$ptkId] = [
                                'ptk_info' => [
                                    'ptk_id' => $ptk->ptk_id,
                                    'nip' => $ptk->nip,
                                    'nama' => $ptk->nama,
                                    'no_hp' => $ptk->no_hp,
                                    'jenjang_jabatan' => $ptk->jenjang_jabatan,
                                    'jenjang_pendidikan' => $ptk->jenjang_pendidikan ?? '-',
                                    'nama_sekolah' => $ptk->nama_sekolah,
                                    'instansi' => $ptk->instansi,
                                    'nama_kota' => $ptk->nama_kota
                                ],
                                'gaps' => []
                            ];
                        }

                        // Simpan gap detail dengan semua rekomendasi
                        $gapKey = $subIndikatorId . '_' . $levelDicapai;
                        if (!isset($ptkGapDetails[$ptkId]['gaps'][$gapKey])) {
                            $ptkGapDetails[$ptkId]['gaps'][$gapKey] = [
                                'sub_indikator_id' => $subIndikatorId,
                                'sub_indikator_name' => $firstSubData->sub_indikator_name,
                                'level_dicapai' => $levelDicapai,
                                'target_level' => $targetLevel,
                                'gap' => $targetLevel - $levelDicapai,
                                'all_rekomendasi' => $allRekomendasi
                            ];
                        }
                    }

                    // Paginate PTK per level
                    $currentPage = LengthAwarePaginator::resolveCurrentPage('level_' . $jenjang . '_' . $subIndikatorId . '_' . $levelDicapai);
                    $perPage = 20;
                    $currentItems = array_slice($ptkList, ($currentPage - 1) * $perPage, $perPage);
                    $ptkListPaginated = new LengthAwarePaginator(
                        $currentItems,
                        count($ptkList),
                        $perPage,
                        $currentPage,
                        [
                            'path' => Paginator::resolveCurrentPath(),
                            'pageName' => 'level_' . $jenjang . '_' . $subIndikatorId . '_' . $levelDicapai
                        ]
                    );

                    $ptkPerLevel[] = [
                        'level' => $levelDicapai,
                        'ptk_count' => count($ptkList),
                        'ptk_list' => $ptkListPaginated,
                        'status' => 'BELUM TARGET',
                        'gap' => $targetLevel - $levelDicapai,
                        'rekomendasi_pelatihan' => $rekomendasiText
                    ];
                }

                // Urutkan berdasarkan level
                usort($ptkPerLevel, function ($a, $b) {
                    return $a['level'] - $b['level'];
                });

                $detailPerSubIndikator[] = [
                    'sub_indikator_id' => $subIndikatorId,
                    'sub_indikator_code' => $firstSubData->sub_indikator_code,
                    'sub_indikator_name' => $firstSubData->sub_indikator_name,
                    'target_level' => $targetLevel,
                    'ptk_per_level' => $ptkPerLevel,
                    'total_ptk' => $subData->unique('ptk_id')->count(),
                    'total_levels' => count($ptkPerLevel)
                ];
            }

            $result[] = [
                'jenjang_jabatan' => $jenjang,
                'target_level' => $targetLevel,
                'total_ptk' => $jenjangData->unique('ptk_id')->count(),
                'total_sub_indikator' => count($groupedBySubIndikator),
                'detail_per_sub_indikator' => $detailPerSubIndikator
            ];
        }

        // ================================================
        // BUAT SUMMARY PTK UNIK TANPA DUPLIKAT
        // ================================================
        $allPtks = [];

        foreach ($ptkGapDetails as $ptkId => $detail) {
            $ptkInfo = $detail['ptk_info'];
            $gapCount = count($detail['gaps']);

            // Kumpulkan SEMUA rekomendasi dari semua gaps
            $allRekomendasi = [];
            foreach ($detail['gaps'] as $gap) {
                if (!empty($gap['all_rekomendasi'])) {
                    foreach ($gap['all_rekomendasi'] as $rek) {
                        // Format: Sub Indikator (Level Target): Rekomendasi
                        $rekText = "{$gap['sub_indikator_name']} (Level {$rek['level_target']}): {$rek['rekomendasi']}";
                        if (!in_array($rekText, $allRekomendasi)) {
                            $allRekomendasi[] = $rekText;
                        }
                    }
                }
            }

            $allPtks[] = [
                'no' => $counter++,
                'ptk_id' => $ptkId,
                'nip' => $ptkInfo['nip'],
                'nama' => $ptkInfo['nama'],
                'no_hp' => $ptkInfo['no_hp'] ?? '-',
                'jenjang' => $ptkInfo['jenjang_jabatan'],
                'jenjang_pendidikan' => $ptkInfo['jenjang_pendidikan'] ?? '-',
                'sekolah' => $ptkInfo['nama_sekolah'] ?? ($ptkInfo['instansi'] ?? '-'),
                'kota' => $ptkInfo['nama_kota'] ?? '-',
                'gap_count' => $gapCount,
                'rekomendasi' => !empty($allRekomendasi) ? implode('<br><br>', $allRekomendasi) : '-'
            ];
        }

        // Urutkan berdasarkan nama
        usort($allPtks, function ($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        // Reset nomor urut setelah sorting
        foreach ($allPtks as $index => &$ptk) {
            $ptk['no'] = $index + 1;
        }

        return [
            'detail_per_jenjang' => $result,
            'total_data_ptk' => $dataWithGap->count(),
            'total_unique_ptk' => $dataWithGap->unique('ptk_id')->count(),
            'total_unique_sub_indikator' => $dataWithGap->unique('sub_indikator_id')->count(),
            'all_ptks' => $allPtks
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
