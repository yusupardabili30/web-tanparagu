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

        // Ambil data untuk dropdown baru
        $bentukPendidikanList = DB::table('sekolah')
            ->select('bentuk_pendidikan')
            ->whereNotNull('bentuk_pendidikan')
            ->distinct()
            ->orderBy('bentuk_pendidikan')
            ->get();

        $jenisKelaminList = DB::table('ptk')
            ->select('jenis_kelamin')
            ->whereNotNull('jenis_kelamin')
            ->distinct()
            ->orderBy('jenis_kelamin')
            ->get();

        // Jika ada filter, ambil data analisis
        if ($request->hasAny(['kegiatan_id', 'pangkat_jabatan_id', 'jenis_ptk_id', 'kota_id', 'bentuk_pendidikan', 'jenis_kelamin'])) {
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
                    'bentukPendidikanList',
                    'jenisKelaminList',
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
            'kotas',
            'bentukPendidikanList',
            'jenisKelaminList'
        ));
    }

    private function getAnalisisData(Request $request)
    {
        // ========================================================
        // QUERY UNTUK DISTRIBUSI PTK (BUKAN BERDASARKAN JAWABAN)
        // ========================================================

        // Query untuk mendapatkan PTK yang sudah menjawab dalam kegiatan tertentu
        $ptkYangSudahMenjawabQuery = DB::table('ptk_jawaban')
            ->select('ptk_id')
            ->when($request->filled('kegiatan_id'), function ($q) use ($request) {
                $q->where('kegiatan_id', $request->kegiatan_id);
            })
            ->groupBy('ptk_id');

        // Ambil PTK yang memenuhi filter dan sudah menjawab
        $ptkQuery = DB::table('ptk')
            ->select(
                'ptk.ptk_id',
                'ptk.nip',
                'ptk.nama',
                'ptk.jenis_kelamin',
                'ptk.kota_id',
                'pangkat_jabatan.jenjang_jabatan',
                'kota.nama_kota',
                'sekolah.bentuk_pendidikan'
            )
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->whereIn('ptk.ptk_id', $ptkYangSudahMenjawabQuery)
            ->when($request->filled('pangkat_jabatan_id'), function ($q) use ($request) {
                $q->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
            })
            ->when($request->filled('jenis_ptk_id'), function ($q) use ($request) {
                $q->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            })
            ->when($request->filled('kota_id'), function ($q) use ($request) {
                $q->where('ptk.kota_id', $request->kota_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            });

        $ptkData = $ptkQuery->get();

        // ========================================================
        // QUERY UNTUK LEVEL DISTRIBUTION (BERDASARKAN JAWABAN)
        // ========================================================

        $jawabanQuery = DB::table('ptk_jawaban')
            ->select('ptk_jawaban.level')
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
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
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            });

        $jawabanData = $jawabanQuery->get();

        // ========================================================
        // HITUNG STATISTIK
        // ========================================================
        $statistik = $this->getStatistik($request, $ptkData, $jawabanData);

        // ========================================================
        // DISTRIBUSI LEVEL (BERDASARKAN JAWABAN)
        // ========================================================
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

        // ========================================================
        // DISTRIBUSI BERDASARKAN PTK (BUKAN JAWABAN)
        // ========================================================

        // Distribusi jenjang jabatan (berdasarkan PTK, bukan jawaban)
        $jenjangDistribution = $ptkData->groupBy('jenjang_jabatan')
            ->map(function ($items, $jenjang) {
                return [
                    'jenjang_jabatan' => $jenjang ?: 'Tidak Diketahui',
                    'count' => $items->count()
                ];
            })
            ->values();

        // Distribusi bentuk pendidikan (berdasarkan PTK, bukan jawaban)
        $bentukPendidikanDistribution = $ptkData->groupBy('bentuk_pendidikan')
            ->map(function ($items, $bentuk) {
                return [
                    'bentuk_pendidikan' => $bentuk ?: 'Tidak Diketahui',
                    'count' => $items->count()
                ];
            })
            ->values();

        // Distribusi jenis kelamin (berdasarkan PTK, bukan jawaban)
        $jenisKelaminDistribution = $ptkData->groupBy('jenis_kelamin')
            ->map(function ($items, $jenis) {
                $label = $jenis == 'L' ? 'Laki-laki' : ($jenis == 'P' ? 'Perempuan' : ($jenis ?: 'Tidak Diketahui'));
                return [
                    'jenis_kelamin' => $label,
                    'count' => $items->count()
                ];
            })
            ->values();

        // ========================================================
        // DATA UNTUK CHART SUB INDIKATOR
        // ========================================================

        // 1. Ambil semua sub indikator yang ada dalam kegiatan
        $semuaSubIndikatorQuery = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.sub_indikator_code',
                'sub_indikator.sub_indikator_name'
            )
            ->join('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
            ->when($request->filled('kegiatan_id'), function ($q) use ($request) {
                $q->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            })
            ->groupBy('ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'sub_indikator.sub_indikator_name')
            ->orderBy('ptk_jawaban.sub_indikator_code');

        $semuaSubIndikator = $semuaSubIndikatorQuery->get();

        // 2. Query untuk data chart sub indikator (menggunakan DISTINCT pada ptk_id)
        $subIndikatorQuery = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.level',
                DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) as ptk_count')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
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
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->where('ptk_jawaban.level', '>=', 2)
            ->groupBy('ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level');

        $subIndikatorData = $subIndikatorQuery->get();

        // Data untuk chart semua sub indikator
        $allSubIndikatorsChart = $this->getAllSubIndikatorsChartData($semuaSubIndikator, $subIndikatorData);

        // ========================================================
        // MODUS PER KOTA - PERBAIKAN: Hitung TOTAL semua sub indikator
        // ========================================================

        // Query untuk mendapatkan TOTAL semua jawaban per kota
        $totalJawabanPerKotaQuery = DB::table('ptk_jawaban')
            ->select(
                'kota.nama_kota',
                DB::raw('COUNT(*) as total_jawaban')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
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
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->where('ptk_jawaban.level', '>=', 2)
            ->groupBy('kota.nama_kota');

        $totalJawabanPerKota = $totalJawabanPerKotaQuery->get()
            ->pluck('total_jawaban', 'nama_kota')
            ->toArray();

        // Query untuk modus per kota dengan COUNT (bukan DISTINCT) karena kita hitung semua jawaban
        $modusKotaQuery = DB::table('ptk_jawaban')
            ->select(
                'kota.nama_kota',
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.level',
                DB::raw('COUNT(*) as jumlah_jawaban') // COUNT biasa, bukan COUNT DISTINCT
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
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
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->where('ptk_jawaban.level', '>=', 2)
            ->groupBy('kota.nama_kota', 'ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level');

        $modusKotaData = $modusKotaQuery->get();

        $modusPerKota = $this->getModusPerKota($modusKotaData, $semuaSubIndikator, $totalJawabanPerKota);

        // ========================================================
        // DATA LAINNYA
        // ========================================================

        // Progress pengisian per kota
        $progressKota = $this->getProgressKota($request);

        return [
            'statistik' => $statistik,
            'level_distribution' => $levelDistribution,
            'jenjang_distribution' => $jenjangDistribution,
            'bentuk_pendidikan_distribution' => $bentukPendidikanDistribution,
            'jenis_kelamin_distribution' => $jenisKelaminDistribution,
            'all_sub_indikators_chart' => $allSubIndikatorsChart,
            'progress_kota' => $progressKota,
            'modus_per_kota' => $modusPerKota
        ];
    }

    private function getStatistik(Request $request, $ptkData = null, $jawabanData = null)
    {
        // Query untuk total PTK yang terdaftar berdasarkan filter
        $ptkQuery = DB::table('ptk')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->when($request->filled('pangkat_jabatan_id'), function ($q) use ($request) {
                $q->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
            })
            ->when($request->filled('jenis_ptk_id'), function ($q) use ($request) {
                $q->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            })
            ->when($request->filled('kota_id'), function ($q) use ($request) {
                $q->where('ptk.kota_id', $request->kota_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            });

        $totalPtk = $ptkQuery->count();

        // PTK yang sudah menjawab berdasarkan kegiatan
        $ptkMenjawabQuery = DB::table('ptk_jawaban')
            ->select(DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) as jumlah'))
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
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
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            });

        $ptkMenjawab = $ptkMenjawabQuery->first()->jumlah ?? 0;

        // Rata-rata level berdasarkan jawaban
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

    private function getAllSubIndikatorsChartData($semuaSubIndikator, $subIndikatorData)
    {
        if ($semuaSubIndikator->isEmpty()) {
            return [
                'labels' => [],
                'datasets' => []
            ];
        }

        // Ambil semua sub indikator unik (maksimal 15 untuk chart yang readable)
        $subIndikators = $semuaSubIndikator
            ->map(function ($item) {
                return [
                    'sub_indikator_id' => $item->sub_indikator_id,
                    'sub_indikator_code' => $item->sub_indikator_code ?? 'SI-' . $item->sub_indikator_id,
                    'sub_indikator_name' => $item->sub_indikator_name ?? 'Sub Indikator ' . $item->sub_indikator_id
                ];
            })
            ->values()
            ->take(15); // Batasi 15 untuk chart yang lebih baik

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

        // Buat array untuk menyimpan data per level
        foreach ($levels as $level) {
            $dataPerLevel = [];

            foreach ($subIndikators as $subIndikator) {
                // Cari data untuk sub indikator dan level tertentu
                $data = $subIndikatorData
                    ->where('sub_indikator_id', $subIndikator['sub_indikator_id'])
                    ->where('level', $level)
                    ->first();

                // Jika tidak ada data, set ke 0
                $dataPerLevel[] = $data ? $data->ptk_count : 0;
            }

            // Selalu tambahkan dataset, meskipun semua datanya 0
            // Ini memastikan semua level muncul di legend
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
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
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
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->groupBy('kota.kota_id', 'kota.nama_kota')
            ->orderBy('persentase', 'desc')
            ->limit(10)
            ->get();
    }

    private function getModusPerKota($modusKotaData, $semuaSubIndikator, $totalJawabanPerKota)
    {
        if ($modusKotaData->isEmpty() || $semuaSubIndikator->isEmpty()) {
            return [];
        }

        // Buat mapping untuk nama sub indikator
        $subIndikatorMap = [];
        foreach ($semuaSubIndikator as $sub) {
            $subIndikatorMap[$sub->sub_indikator_id] = [
                'code' => $sub->sub_indikator_code,
                'name' => $sub->sub_indikator_name
            ];
        }

        // Kelompokkan per kota dan sub indikator
        $groupedByKota = $modusKotaData->groupBy(['nama_kota', 'sub_indikator_id']);

        $result = [];

        foreach ($groupedByKota as $namaKota => $subIndikators) {
            $kotaModus = [
                'nama_kota' => $namaKota ?: 'Tidak Diketahui',
                'sub_indikator_modus' => [],
                'total_jawaban' => $totalJawabanPerKota[$namaKota] ?? 0 // Gunakan total dari query khusus
            ];

            foreach ($subIndikators as $subIndikatorId => $levels) {
                // Cari level dengan jumlah jawaban terbanyak (modus)
                $modusLevel = $levels->sortByDesc('jumlah_jawaban')->first();

                if ($modusLevel) {
                    $subInfo = $subIndikatorMap[$subIndikatorId] ?? [
                        'code' => 'SI-' . $subIndikatorId,
                        'name' => 'Sub Indikator ' . $subIndikatorId
                    ];

                    $kotaModus['sub_indikator_modus'][] = [
                        'sub_indikator_code' => $subInfo['code'],
                        'sub_indikator_name' => $subInfo['name'],
                        'modus_level' => $modusLevel->level,
                        'jumlah_jawaban' => $modusLevel->jumlah_jawaban
                    ];
                }
            }

            // Jika tidak ada data modus untuk kota ini, set kosong
            if (empty($kotaModus['sub_indikator_modus'])) {
                continue;
            }

            // Urutkan berdasarkan jumlah jawaban terbanyak
            usort($kotaModus['sub_indikator_modus'], function ($a, $b) {
                return $b['jumlah_jawaban'] - $a['jumlah_jawaban'];
            });

            // Batasi hanya 5 sub indikator pertama
            $kotaModus['sub_indikator_modus'] = array_slice($kotaModus['sub_indikator_modus'], 0, 5);
            $result[] = $kotaModus;
        }

        // Urutkan kota berdasarkan total jawaban terbanyak
        usort($result, function ($a, $b) {
            return $b['total_jawaban'] - $a['total_jawaban'];
        });

        return $result;
    }
}
