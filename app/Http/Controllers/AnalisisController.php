<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AnalisisController extends Controller
{
    public function index(Request $request)
    {
        $tittle = 'Analisis Hasil Instrumen';

        // Ambil data untuk dropdown
        $kegiatans = DB::table('kegiatan')->get();
        $pangkatJabatans = DB::table('pangkat_jabatan')->get();
        $jenisPtkList = DB::table('jenis_ptk')->get();
        $kotas = DB::table('kota')->orderBy('nama_kota')->get();

        // Jika ada filter, ambil data analisis
        if ($request->hasAny(['kegiatan_id', 'pangkat_jabatan_id', 'jenis_ptk_id', 'kota_id'])) {
            try {
                $analisisData = $this->getAnalisisData($request);

                // Jika request AJAX, kembalikan JSON
                if ($request->ajax()) {
                    return response()->json($analisisData);
                }

                // Jika bukan AJAX, parse data untuk view
                return view('analisis.index', compact(
                    'tittle',
                    'kegiatans',
                    'pangkatJabatans',
                    'jenisPtkList',
                    'kotas',
                    'analisisData'
                ));
            } catch (\Exception $e) {
                // Jika request AJAX, kembalikan error
                if ($request->ajax()) {
                    return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
                }

                // Jika bukan AJAX, redirect dengan error
                return redirect()->route('analisis.index')
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // Jika request AJAX tapi tanpa filter
        if ($request->ajax()) {
            return response()->json(['error' => 'Silakan pilih filter terlebih dahulu']);
        }

        // Tampilkan view tanpa data
        return view('analisis.index', compact(
            'tittle',
            'kegiatans',
            'pangkatJabatans',
            'jenisPtkList',
            'kotas'
        ));
    }

    private function getAnalisisData(Request $request)
    {
        // Query dasar untuk analisis
        $query = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.level',
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.sub_indikator_code',
                'ptk.nama',
                'ptk.nip',
                'ptk.kota_id',
                'ptk.pangkat_jabatan_id',
                'ptk.jenis_ptk_id',
                'pangkat_jabatan.jenjang_jabatan',
                'kota.nama_kota',

                'sub_indikator.sub_indikator_name'
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id');

        // Filter berdasarkan request
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

        // Ambil data jawaban
        $jawabanData = $query->get();

        // DEBUG: Cek apakah ada data
        if ($jawabanData->isEmpty()) {
            // Hitung statistik dasar saja
            $statistik = $this->getStatistik($request);

            return [
                'statistik' => $statistik,
                'level_distribution' => [],
                'jenjang_distribution' => [],
                'sub_indikator_stats' => [],
                'distribusi_kota' => [],
                'progress_kota' => $this->getProgressKota($request),
                'modus_per_kota' => [],
                'all_sub_indikators_chart' => []
            ];
        }

        // Hitung statistik
        $statistik = $this->getStatistik($request, $jawabanData);

        // Distribusi level
        $levelDistribution = $jawabanData->where('level', '>=', 2)
            ->groupBy('level')
            ->map(function ($items, $level) {
                return [
                    'level' => (int)$level,
                    'count' => $items->count()
                ];
            })
            ->values()
            ->sortBy('level');

        // Distribusi jenjang jabatan
        $jenjangDistribution = $jawabanData->groupBy('jenjang_jabatan')
            ->map(function ($items, $jenjang) {
                return [
                    'jenjang_jabatan' => $jenjang ?: 'Tidak Diketahui',
                    'count' => $items->count()
                ];
            })
            ->values();

        // Statistik per sub indikator dengan detail level
        $subIndikatorStats = $this->getSubIndikatorStats($jawabanData);

        // Data untuk chart semua sub indikator
        $allSubIndikatorsChart = $this->getAllSubIndikatorsChartData($jawabanData);

        // Distribusi per kota
        $distribusiKota = $this->getDistribusiKota($jawabanData);

        // Progress pengisian per kota
        $progressKota = $this->getProgressKota($request);

        // Modus per kota untuk semua sub indikator
        $modusPerKota = $this->getModusPerKota($jawabanData);

        return [
            'statistik' => $statistik,
            'level_distribution' => $levelDistribution,
            'jenjang_distribution' => $jenjangDistribution,
            'sub_indikator_stats' => $subIndikatorStats,
            'all_sub_indikators_chart' => $allSubIndikatorsChart,
            'distribusi_kota' => $distribusiKota,
            'progress_kota' => $progressKota,
            'modus_per_kota' => $modusPerKota
        ];
    }

    private function getStatistik(Request $request, $jawabanData = null)
    {
        // Query untuk total PTK yang terdaftar berdasarkan filter
        $ptkQuery = DB::table('ptk')
            ->when($request->filled('pangkat_jabatan_id'), function ($q) use ($request) {
                $q->where('pangkat_jabatan_id', $request->pangkat_jabatan_id);
            })
            ->when($request->filled('jenis_ptk_id'), function ($q) use ($request) {
                $q->where('jenis_ptk_id', $request->jenis_ptk_id);
            })
            ->when($request->filled('kota_id'), function ($q) use ($request) {
                $q->where('kota_id', $request->kota_id);
            });

        $totalPtk = $ptkQuery->count();

        // PTK yang sudah menjawab
        $ptkMenjawabQuery = DB::table('ptk_jawaban')
            ->select(DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) as jumlah'))
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->when($request->filled('kegiatan_id'), function ($q) use ($request) {
                $q->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            })
            ->when($request->filled('pangkat_jabatan_id'), function ($q) use ($request) {
                $q->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
            })
            ->when($request->filled('jenis_ptk_id'), function ($q) use ($request) {
                $q->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            })
            ->when($request->filled('kota_id'), function ($q) use ($request) {
                $q->where('ptk.kota_id', $request->kota_id);
            });

        $ptkMenjawab = $ptkMenjawabQuery->first()->jumlah ?? 0;

        // Rata-rata level
        $rataLevel = 0;
        if ($jawabanData && $jawabanData->count() > 0) {
            $rataLevel = $jawabanData->avg('level');
        }

        // Persentase pengisian
        $persentaseIsi = $totalPtk > 0
            ? round(($ptkMenjawab / $totalPtk) * 100, 1)
            : 0;

        return [
            'total_ptk' => $totalPtk,
            'ptk_menjawab' => $ptkMenjawab,
            'rata_level' => round($rataLevel, 2),
            'persentase_isi' => $persentaseIsi
        ];
    }

    private function getSubIndikatorStats($jawabanData)
    {
        return $jawabanData->groupBy('sub_indikator_id')
            ->map(function ($items, $subIndikatorId) {
                $firstItem = $items->first();
                $levels = $items->pluck('level')->map(function ($level) {
                    return (int)$level;
                });

                // Hitung jumlah per level
                $levelCounts = $levels->countBy();

                return (object)[
                    'sub_indikator_id' => $subIndikatorId,
                    'sub_indikator_code' => $firstItem->sub_indikator_code ?? '-',
                    'sub_indikator_name' => $firstItem->sub_indikator_name ?? '-',
                    'level_2' => $levelCounts[2] ?? 0,
                    'level_3' => $levelCounts[3] ?? 0,
                    'level_4' => $levelCounts[4] ?? 0,
                    'level_5' => $levelCounts[5] ?? 0,
                    'total' => $items->count(),
                    'rata_level' => round($levels->avg(), 2),
                    'modus_level' => $levelCounts->sortDesc()->keys()->first() ?? null
                ];
            })
            ->values()
            ->sortBy('sub_indikator_code');
    }

    private function getAllSubIndikatorsChartData($jawabanData)
    {
        // Ambil semua sub indikator yang ada
        $subIndikators = $jawabanData->groupBy('sub_indikator_id')
            ->map(function ($items, $subIndikatorId) {
                $firstItem = $items->first();
                return [
                    'sub_indikator_id' => $subIndikatorId,
                    'sub_indikator_code' => $firstItem->sub_indikator_code ?? 'SI-' . $subIndikatorId,
                    'sub_indikator_name' => $firstItem->sub_indikator_name ?? 'Sub Indikator ' . $subIndikatorId
                ];
            })
            ->values()
            ->sortBy('sub_indikator_code')
            ->take(15); // Batasi 15 sub indikator pertama untuk chart

        // Siapkan data untuk chart
        $chartData = [
            'labels' => $subIndikators->pluck('sub_indikator_code')->toArray(),
            'datasets' => []
        ];

        // Level yang akan ditampilkan
        $levels = [2, 3, 4, 5];
        $levelColors = [
            2 => '#17a2b8',
            3 => '#007bff',
            4 => '#ffc107',
            5 => '#28a745'
        ];
        $levelNames = [
            2 => 'Level 2',
            3 => 'Level 3',
            4 => 'Level 4',
            5 => 'Level 5'
        ];

        foreach ($levels as $level) {
            $dataPerLevel = [];

            foreach ($subIndikators as $subIndikator) {
                $count = $jawabanData
                    ->where('sub_indikator_id', $subIndikator['sub_indikator_id'])
                    ->where('level', $level)
                    ->count();

                $dataPerLevel[] = $count;
            }

            $chartData['datasets'][] = [
                'label' => $levelNames[$level],
                'data' => $dataPerLevel,
                'backgroundColor' => $levelColors[$level],
                'borderColor' => $levelColors[$level],
                'borderWidth' => 1
            ];
        }

        return $chartData;
    }

    private function getDistribusiKota($jawabanData)
    {
        if ($jawabanData->isEmpty()) {
            return collect();
        }

        // Kelompokkan per kota dan hitung statistik
        return $jawabanData->groupBy('nama_kota')
            ->map(function ($items, $namaKota) {
                return (object)[
                    'nama_kota' => $namaKota ?: 'Tidak Diketahui',
                    'jumlah_ptk' => $items->unique('nip')->count(),
                    'rata_level' => round($items->avg('level'), 2)
                ];
            })
            ->values()
            ->sortByDesc('jumlah_ptk')
            ->take(10);
    }

    private function getProgressKota(Request $request)
    {
        return DB::table('kota')
            ->select(
                'kota.nama_kota',
                DB::raw('COUNT(DISTINCT ptk.ptk_id) as total_ptk'),
                DB::raw('COUNT(DISTINCT CASE WHEN ptk_jawaban.ptk_jawaban_id IS NOT NULL THEN ptk.ptk_id END) as sudah_isi'),
                DB::raw('ROUND(COUNT(DISTINCT CASE WHEN ptk_jawaban.ptk_jawaban_id IS NOT NULL THEN ptk.ptk_id END) * 100.0 / COUNT(DISTINCT ptk.ptk_id), 1) as persentase')
            )
            ->leftJoin('ptk', 'kota.kota_id', '=', 'ptk.kota_id')
            ->leftJoin('ptk_jawaban', function ($join) use ($request) {
                $join->on('ptk.ptk_id', '=', 'ptk_jawaban.ptk_id')
                    ->when($request->filled('kegiatan_id'), function ($q) use ($request) {
                        $q->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
                    });
            })
            ->when($request->filled('pangkat_jabatan_id'), function ($q) use ($request) {
                $q->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
            })
            ->when($request->filled('jenis_ptk_id'), function ($q) use ($request) {
                $q->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            })
            ->when($request->filled('kota_id'), function ($q) use ($request) {
                $q->where('kota.kota_id', $request->kota_id);
            })
            ->groupBy('kota.kota_id', 'kota.nama_kota')
            ->orderBy('persentase', 'desc')
            ->limit(10)
            ->get();
    }

    private function getModusPerKota($jawabanData)
    {
        // Kelompokkan per kota
        return $jawabanData->groupBy('nama_kota')
            ->map(function ($items, $kota) {
                // Kelompokkan per sub indikator
                $subIndikatorModus = $items->groupBy('sub_indikator_id')
                    ->map(function ($subItems, $subIndikatorId) {
                        $firstItem = $subItems->first();

                        // Hitung modus level untuk sub indikator ini
                        $levelCounts = $subItems->pluck('level')->countBy();
                        $modus = $levelCounts->sortDesc()->keys()->first();

                        return [
                            'sub_indikator_code' => $firstItem->sub_indikator_code ?? 'SI-' . $subIndikatorId,
                            'sub_indikator_name' => $firstItem->sub_indikator_name ?? 'Sub Indikator ' . $subIndikatorId,
                            'modus_level' => $modus,
                            'jumlah_jawaban' => $subItems->count()
                        ];
                    })
                    ->values()
                    ->sortBy('sub_indikator_code')
                    ->take(5); // Ambil 5 sub indikator pertama

                return [
                    'nama_kota' => $kota ?: 'Tidak Diketahui',
                    'total_jawaban' => $items->count(),
                    'sub_indikator_modus' => $subIndikatorModus
                ];
            })
            ->values()
            ->sortBy('nama_kota');
    }
}
