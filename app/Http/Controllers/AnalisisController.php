<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;

class AnalisisController extends Controller
{
    public function index(Request $request)
    {
        $tittle = 'Analisis Hasil Instrumen';

        // =========================
        // DATA DROPDOWN
        // =========================
        $kegiatans = DB::table('kegiatan')->get();
        $pangkatJabatans = DB::table('pangkat_jabatan')->get();
        $jenisPtkList = DB::table('jenis_ptk')->get();
        $kotas = DB::table('kota')->orderBy('nama_kota')->get();

        // Ambil jenjang pendidikan dari tabel jenjang_pendidikan
        $jenjangPendidikanList = DB::table('jenjang_pendidikan')
            ->select('jenjang_pendidikan_id', 'jenjang_pendidikan')
            ->whereNotNull('jenjang_pendidikan')
            ->distinct()
            ->orderBy('jenjang_pendidikan')
            ->get();

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

        // =========================
        // DATA ANALISIS (DEFAULT NULL)
        // =========================
        $analisisData = null;

        // =========================
        // JIKA ADA FILTER
        // =========================
        if ($request->hasAny([
            'kegiatan_id',
            'pangkat_jabatan_id',
            'jenis_ptk_id',
            'kota_id',
            'jenjang_pendidikan_id',
            'bentuk_pendidikan',
            'jenis_kelamin'
        ])) {
            try {
                $analisisData = $this->getAnalisisData($request);

                // Jika AJAX → JSON
                if ($request->ajax()) {
                    return response()->json($analisisData);
                }
            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json([
                        'error' => 'Terjadi kesalahan: ' . $e->getMessage()
                    ], 500);
                }

                return redirect()
                    ->route('analisis.index')
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        return view('analisis.index', [
            'tittle' => $tittle,

            // dropdown
            'kegiatans' => $kegiatans,
            'pangkatJabatans' => $pangkatJabatans,
            'jenisPtkList' => $jenisPtkList,
            'kotas' => $kotas,
            'jenjangPendidikanList' => $jenjangPendidikanList,
            'bentukPendidikanList' => $bentukPendidikanList,
            'jenisKelaminList' => $jenisKelaminList,
            'data' => $analisisData
        ]);
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
                'sekolah.bentuk_pendidikan',
                'jenjang_pendidikan.jenjang_pendidikan'
            )
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
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
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->where('ptk_jawaban.level', '>=', 1); // INCLUDE LEVEL 1

        $jawabanData = $jawabanQuery->get();

        // ========================================================
        // HITUNG STATISTIK
        // ===============================s=================
        $statistik = $this->getStatistik($request, $ptkData, $jawabanData);



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

        // Distribusi jenjang pendidikan (berdasarkan PTK, bukan jawaban)
        $jenjangPendidikanDistribution = $ptkData->groupBy('jenjang_pendidikan')
            ->map(function ($items, $jenjang) {
                return [
                    'jenjang_pendidikan' => $jenjang ?: 'Tidak Diketahui',
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
        // DATA UNTUK CHART SUB INDIKATOR (DARI ptk_jawaban)
        // ========================================================

        // 1. Ambil semua sub indikator yang ada dalam kegiatan (VALIDASI DATA)
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
            ->whereNotNull('ptk_jawaban.sub_indikator_id')
            ->whereNotNull('ptk_jawaban.sub_indikator_code')
            ->groupBy('ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'sub_indikator.sub_indikator_name')
            ->orderBy('ptk_jawaban.sub_indikator_code');

        $semuaSubIndikator = $semuaSubIndikatorQuery->get();

        // 2. Query untuk data chart sub indikator (VALIDASI dengan DISTINCT pada ptk_id)
        $subIndikatorQuery = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.level',
                DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) as ptk_count')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->whereNotNull('ptk_jawaban.sub_indikator_id')
            ->whereNotNull('ptk_jawaban.sub_indikator_code')
            ->where('ptk_jawaban.level', '>=', 1) // INCLUDE LEVEL 1
            ->groupBy('ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level')
            ->orderBy('ptk_jawaban.sub_indikator_code')
            ->orderBy('ptk_jawaban.level');

        $subIndikatorData = $subIndikatorQuery->get();

        // Data untuk chart semua sub indikator
        $allSubIndikatorsChart = $this->getAllSubIndikatorsChartData($semuaSubIndikator, $subIndikatorData);

        // ========================================================
        // MODUS PER KOTA
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
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->where('ptk_jawaban.level', '>=', 1) // INCLUDE LEVEL 1
            ->groupBy('kota.nama_kota');

        $totalJawabanPerKota = $totalJawabanPerKotaQuery->get()
            ->pluck('total_jawaban', 'nama_kota')
            ->toArray();

        // Query untuk modus per kota dengan COUNT (bukan DISTINCT)
        $modusKotaQuery = DB::table('ptk_jawaban')
            ->select(
                'kota.nama_kota',
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.level',
                DB::raw('COUNT(*) as jumlah_jawaban')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->whereNotNull('ptk_jawaban.sub_indikator_id')
            ->whereNotNull('ptk_jawaban.sub_indikator_code')
            ->where('ptk_jawaban.level', '>=', 1) // INCLUDE LEVEL 1
            ->groupBy('kota.nama_kota', 'ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level')
            ->orderBy('kota.nama_kota')
            ->orderBy('ptk_jawaban.sub_indikator_code');

        $modusKotaData = $modusKotaQuery->get();

        $modusPerKota = $this->getModusPerKota($modusKotaData, $semuaSubIndikator, $totalJawabanPerKota, $request);
        $subIndikatorPerJenjang = $this->getSubIndikatorPerJenjang($request, $semuaSubIndikator);
        $subIndikatorPerJenjangPendidikan = $this->getSubIndikatorPerJenjangPendidikan($request, $semuaSubIndikator);

        // ========================================================
        // DATA LAINNYA
        // ========================================================

        // Progress pengisian per kota
        $progressKota = $this->getProgressKota($request);

        // Data pelatihan PTK
        $pelatihanData = $this->getPelatihanData($request);


        $rekomendasiGapPerJenjang = $this->getRekomendasiGapPerJenjang($request);


        $ptkBelumMenjawab = $this->getPtkBelumMenjawab($request);


        $levelTerendahPerPtk = $this->getLevelTerendahPerPtk($request);

        $levelkotaPerPtk = $this->getLevelkotaPerPtk($request);


        $distribusiLevelPerKota = $this->getDistribusiLevelPerKota($request);

        $persentaseLevelPerJenjang = $this->getPersentaseLevelPerJenjang($request);

        return [
            'statistik' => $statistik,
            'level_kota_per_ptk' => $levelkotaPerPtk,
            'level_terendah_per_ptk' => $levelTerendahPerPtk,
            'distribusi_level_per_kota' => $distribusiLevelPerKota,
            'jenjang_distribution' => $jenjangDistribution,
            'persentase_level_per_jenjang' => $persentaseLevelPerJenjang,
            'bentuk_pendidikan_distribution' => $bentukPendidikanDistribution,
            'jenjang_pendidikan_distribution' => $jenjangPendidikanDistribution,
            'jenis_kelamin_distribution' => $jenisKelaminDistribution,
            'all_sub_indikators_chart' => $allSubIndikatorsChart,
            'sub_indikator_per_jenjang' => $subIndikatorPerJenjang,
            'sub_indikator_per_jenjang_pendidikan' => $subIndikatorPerJenjangPendidikan,
            'progress_kota' => $progressKota,
            'modus_per_kota' => $modusPerKota,
            'pelatihan_data' => $pelatihanData,
            'rekomendasi_gap_per_jenjang' => $rekomendasiGapPerJenjang,
            'ptk_belum_menjawab' => $ptkBelumMenjawab,
        ];
    }





    private function getStatistik(Request $request, $ptkData = null, $jawabanData = null)
    {
        // Query untuk total PTK yang terdaftar berdasarkan filter
        $ptkQuery = DB::table('ptk')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->when($request->filled('pangkat_jabatan_id'), function ($q) use ($request) {
                $q->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
            })
            ->when($request->filled('jenis_ptk_id'), function ($q) use ($request) {
                $q->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            })
            ->when($request->filled('kota_id'), function ($q) use ($request) {
                $q->where('ptk.kota_id', $request->kota_id);
            })
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
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
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            });

        $ptkMenjawab = $ptkMenjawabQuery->first()->jumlah ?? 0;

        // Hitung PTK yang belum menjawab
        $ptkBelumMenjawabQuery = DB::table('ptk')
            ->select(DB::raw('COUNT(DISTINCT ptk.ptk_id) as jumlah'))
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->whereNotIn('ptk.ptk_id', function ($q) use ($request) {
                $q->select('ptk_id')
                    ->from('ptk_jawaban')
                    ->when($request->filled('kegiatan_id'), function ($q2) use ($request) {
                        $q2->where('kegiatan_id', $request->kegiatan_id);
                    });
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            });

        $ptkBelumMenjawab = $ptkBelumMenjawabQuery->first()->jumlah ?? 0;


        // Persentase pengisian
        $persentaseIsi = $totalPtk > 0
            ? round(($ptkMenjawab / $totalPtk) * 100, 1)
            : 0;

        return [
            'total_ptk' => $totalPtk,
            'ptk_menjawab' => $ptkMenjawab,
            'ptk_belum_menjawab' => $ptkBelumMenjawab,
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
            ->take(15);

        // Siapkan data untuk chart
        $chartData = [
            'labels' => $subIndikators->pluck('sub_indikator_code')->toArray(),
            'datasets' => []
        ];

        // Level yang akan ditampilkan (INCLUDE LEVEL 1)
        $levels = [1, 2, 3, 4, 5];
        $levelColors = [
            1 => '#17a212',  // Level 1
            2 => '#17a2b8',  // Level 2
            3 => '#007bff',  // Level 3
            4 => '#ffc107',  // Level 4
            5 => '#28a745'   // Level 5
        ];
        $levelNames = [
            1 => 'Level 1',
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

            // Selalu tambahkan dataset
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


    private function getPtkBelumMenjawab(Request $request)
    {
        try {
            // 1. Query untuk PTK yang sudah menjawab dalam kegiatan tertentu
            $ptkSudahMenjawabQuery = DB::table('ptk_jawaban')
                ->select('ptk_jawaban.ptk_id')
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
                ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                    $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
                })
                ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                    $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
                })
                ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                    $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
                })
                ->groupBy('ptk_jawaban.ptk_id');

            $ptkSudahMenjawab = $ptkSudahMenjawabQuery->pluck('ptk_id')->toArray();

            // 2. Query untuk semua PTK yang terdaftar dengan filter yang sama
            $query = DB::table('ptk')
                ->select(
                    'ptk.ptk_id',
                    'ptk.nip',
                    'ptk.nama',
                    'ptk.jenis_kelamin',
                    'ptk.no_hp',
                    'ptk.instansi',
                    'pangkat_jabatan.jenjang_jabatan',
                    'kota.nama_kota',
                    'sekolah.nama_sekolah',
                    'jenjang_pendidikan.jenjang_pendidikan',
                    'jenis_ptk.jenis_ptk'
                )
                ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
                ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id')
                ->whereNotIn('ptk.ptk_id', $ptkSudahMenjawab)
                ->where(function ($q) {
                    $q->whereNotNull('ptk.nip')
                        ->orWhere('ptk.nip', '!=', '');
                });

            // 3. TERAPKAN FILTER YANG SAMA PERSIS SEPERTI QUERY LAINNYA
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

            // 4. Tambahkan kegiatan name jika ada filter kegiatan
            if ($request->filled('kegiatan_id')) {
                $kegiatan = DB::table('kegiatan')->where('kegiatan_id', $request->kegiatan_id)->first();
                if ($kegiatan) {
                    $query->addSelect(DB::raw("'" . addslashes($kegiatan->kegiatan_name) . "' as kegiatan_name"));
                } else {
                    $query->addSelect(DB::raw("NULL as kegiatan_name"));
                }
            } else {
                $query->addSelect(DB::raw("NULL as kegiatan_name"));
            }

            // 5. Batasi jumlah data (maksimal 100 untuk performa)
            $result = $query->orderBy('ptk.nama')
                ->limit(100)
                ->get();

            return $result;
        } catch (\Exception $e) {
            \Log::error('Error getPtkBelumMenjawab: ' . $e->getMessage());
            return collect(); // Return empty collection jika error
        }
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
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
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

    private function getModusPerKota($modusKotaData, $semuaSubIndikator, $totalJawabanPerKota, $request)
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

        // PERUBAHAN: Jika filter kota_id kosong (semua kota), gabungkan semua data
        if (!$request->filled('kota_id')) {
            // Kelompokkan per sub indikator tanpa memperhatikan kota
            $groupedBySubIndikator = $modusKotaData->groupBy(['sub_indikator_id']);

            $combinedResult = [];
            $totalCombined = array_sum($totalJawabanPerKota);

            foreach ($groupedBySubIndikator as $subIndikatorId => $dataPerSub) {
                // Gabungkan semua data untuk sub indikator ini
                $combinedData = $dataPerSub->groupBy('level')
                    ->map(function ($items, $level) {
                        return [
                            'level' => (int)$level,
                            'jumlah_jawaban' => $items->sum('jumlah_jawaban')
                        ];
                    })
                    ->values()
                    ->sortByDesc('jumlah_jawaban');

                if (!$combinedData->isEmpty()) {
                    $modusData = $combinedData->first();
                    $subInfo = $subIndikatorMap[$subIndikatorId] ?? [
                        'code' => 'SI-' . $subIndikatorId,
                        'name' => 'Sub Indikator ' . $subIndikatorId
                    ];

                    $combinedResult[] = [
                        'sub_indikator_code' => $subInfo['code'],
                        'sub_indikator_name' => $subInfo['name'],
                        'modus_level' => $modusData['level'],
                        'jumlah_jawaban' => $modusData['jumlah_jawaban']
                    ];
                }
            }

            // Urutkan berdasarkan jumlah jawaban terbanyak
            usort($combinedResult, function ($a, $b) {
                return $b['jumlah_jawaban'] - $a['jumlah_jawaban'];
            });

            return [
                [
                    'nama_kota' => 'Banten',
                    'sub_indikator_modus' => $combinedResult,
                    'total_jawaban' => $totalCombined
                ]
            ];
        }

        // KODE ASAL (untuk filter kota tertentu)
        $groupedByKota = $modusKotaData->groupBy(['nama_kota', 'sub_indikator_id']);
        $result = [];

        foreach ($groupedByKota as $namaKota => $subIndikators) {
            $kotaModus = [
                'nama_kota' => $namaKota ?: 'Tidak Diketahui',
                'sub_indikator_modus' => [],
                'total_jawaban' => $totalJawabanPerKota[$namaKota] ?? 0
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

            $result[] = $kotaModus;
        }

        // Urutkan kota berdasarkan total jawaban terbanyak
        usort($result, function ($a, $b) {
            return $b['total_jawaban'] - $a['total_jawaban'];
        });

        return $result;
    }

    private function getSubIndikatorPerJenjang(Request $request, $semuaSubIndikator)
    {
        if ($semuaSubIndikator->isEmpty()) {
            return [];
        }

        // **PERBAIKAN: Gunakan SEMUA sub indikator yang sama dengan chart utama**
        // Bukan hanya 10, tapi semua yang ada di $semuaSubIndikator
        $allSubIndikators = $semuaSubIndikator;

        // Ambil semua jenjang jabatan yang ada
        $jenjangList = DB::table('ptk_jawaban')
            ->select('pangkat_jabatan.jenjang_jabatan')
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->whereNotNull('ptk_jawaban.sub_indikator_id')
            ->whereNotNull('ptk_jawaban.sub_indikator_code')
            ->where('ptk_jawaban.level', '>=', 1)
            ->whereNotNull('pangkat_jabatan.jenjang_jabatan')
            ->groupBy('pangkat_jabatan.jenjang_jabatan')
            ->orderBy('pangkat_jabatan.jenjang_jabatan')
            ->pluck('jenjang_jabatan')
            ->toArray();

        if (empty($jenjangList)) {
            return [];
        }

        // URUTKAN JENJANG SESUAI URUTAN YANG DIINGINKAN
        $sortedJenjangList = [];
        $order = ['Pertama', 'Muda', 'Madya', 'Utama'];

        foreach ($order as $jenjang) {
            if (in_array($jenjang, $jenjangList)) {
                $sortedJenjangList[] = $jenjang;
            }
        }


        $result = [];

        foreach ($sortedJenjangList  as $jenjang) {
            // Query untuk mendapatkan data per jenjang jabatan - INCLUDE SEMUA SUB INDIKATOR
            $perJenjangQuery = DB::table('ptk_jawaban')
                ->select(
                    'ptk_jawaban.sub_indikator_id',
                    'ptk_jawaban.sub_indikator_code',
                    'ptk_jawaban.level',
                    DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) as ptk_count')
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
                ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                    $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
                })
                ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                    $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
                })
                ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                    $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
                })
                ->whereNotNull('ptk_jawaban.sub_indikator_id')
                ->whereNotNull('ptk_jawaban.sub_indikator_code')
                ->where('ptk_jawaban.level', '>=', 1)
                ->where('pangkat_jabatan.jenjang_jabatan', $jenjang)
                ->groupBy('ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level')
                ->orderBy('ptk_jawaban.sub_indikator_code');

            $dataPerJenjang = $perJenjangQuery->get();

            // **PERBAIKAN: Gunakan semua sub indikator yang ada di $allSubIndikators**
            // Bukan hanya yang ada di $dataPerJenjang
            $allLabels = [];
            $mappingData = [];

            foreach ($allSubIndikators as $sub) {
                $label = $sub->sub_indikator_code;
                $allLabels[] = $label;

                // Inisialisasi mapping untuk semua level
                for ($level = 1; $level <= 5; $level++) {
                    $mappingData[$label][$level] = 0;
                }
            }

            // Isi mapping dengan data yang ada
            foreach ($dataPerJenjang as $data) {
                $label = $data->sub_indikator_code;
                $level = $data->level;
                if (isset($mappingData[$label][$level])) {
                    $mappingData[$label][$level] = $data->ptk_count;
                }
            }

            // Siapkan struktur data untuk chart
            $jenjangData = [
                'jenjang_jabatan' => $jenjang,
                'labels' => $allLabels,
                'datasets' => []
            ];

            // Level yang akan ditampilkan
            $levels = [1, 2, 3, 4, 5];
            $levelColors = [
                1 => '#17a212',
                2 => '#17a2b8',
                3 => '#007bff',
                4 => '#ffc107',
                5 => '#28a745'
            ];
            $levelNames = [
                1 => 'Level 1',
                2 => 'Level 2',
                3 => 'Level 3',
                4 => 'Level 4',
                5 => 'Level 5'
            ];

            // Buat dataset untuk setiap level
            foreach ($levels as $level) {
                $dataPerLevel = [];

                foreach ($allLabels as $label) {
                    $dataPerLevel[] = $mappingData[$label][$level] ?? 0;
                }

                $jenjangData['datasets'][] = [
                    'label' => $levelNames[$level],
                    'data' => $dataPerLevel,
                    'backgroundColor' => $levelColors[$level],
                    'borderColor' => $levelColors[$level],
                    'borderWidth' => 1
                ];
            }

            $result[] = $jenjangData;
        }

        return $result;
    }

    private function getSubIndikatorPerJenjangPendidikan(Request $request, $semuaSubIndikator)
    {
        // Query untuk data per jenjang pendidikan
        $perJenjangPendidikanQuery = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.level',
                'jenjang_pendidikan.jenjang_pendidikan',
                DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) as ptk_count')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->whereNotNull('ptk_jawaban.sub_indikator_id')
            ->whereNotNull('ptk_jawaban.sub_indikator_code')
            ->where('ptk_jawaban.level', '>=', 1) // INCLUDE LEVEL 1
            ->whereNotNull('jenjang_pendidikan.jenjang_pendidikan')
            ->groupBy('jenjang_pendidikan.jenjang_pendidikan', 'ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level')
            ->orderBy('jenjang_pendidikan.jenjang_pendidikan')
            ->orderBy('ptk_jawaban.sub_indikator_code');

        $dataPerJenjangPendidikan = $perJenjangPendidikanQuery->get();

        if ($dataPerJenjangPendidikan->isEmpty() || $semuaSubIndikator->isEmpty()) {
            return [];
        }

        // Ambil semua jenjang pendidikan yang ada
        $jenjangPendidikanList = $dataPerJenjangPendidikan->pluck('jenjang_pendidikan')->unique()->values();

        // Batasi sub indikator untuk readability
        $limitedSubIndikators = $semuaSubIndikator->take(10);

        $result = [];

        foreach ($jenjangPendidikanList as $jenjangPendidikan) {
            $jenjangPendidikanData = [
                'jenjang_pendidikan' => $jenjangPendidikan,
                'labels' => $limitedSubIndikators->pluck('sub_indikator_code')->toArray(),
                'datasets' => []
            ];

            // Level yang akan ditampilkan (INCLUDE LEVEL 1)
            $levels = [1, 2, 3, 4, 5];
            $levelColors = [
                1 => '#17a212',
                2 => '#17a2b8',
                3 => '#007bff',
                4 => '#ffc107',
                5 => '#28a745'
            ];
            $levelNames = [
                1 => 'Level 1',
                2 => 'Level 2',
                3 => 'Level 3',
                4 => 'Level 4',
                5 => 'Level 5'
            ];

            // Buat dataset untuk setiap level
            foreach ($levels as $level) {
                $dataPerLevel = [];

                foreach ($limitedSubIndikators as $subIndikator) {
                    // Cari data untuk jenjang pendidikan, sub indikator, dan level tertentu
                    $data = $dataPerJenjangPendidikan
                        ->where('jenjang_pendidikan', $jenjangPendidikan)
                        ->where('sub_indikator_id', $subIndikator->sub_indikator_id)
                        ->where('level', $level)
                        ->first();

                    $dataPerLevel[] = $data ? $data->ptk_count : 0;
                }

                $jenjangPendidikanData['datasets'][] = [
                    'label' => $levelNames[$level],
                    'data' => $dataPerLevel,
                    'backgroundColor' => $levelColors[$level],
                    'borderColor' => $levelColors[$level],
                    'borderWidth' => 1
                ];
            }

            // Hanya tambahkan jika ada data
            if (array_sum(array_merge(...array_column($jenjangPendidikanData['datasets'], 'data'))) > 0) {
                $result[] = $jenjangPendidikanData;
            }
        }

        return $result;
    }

    private function getPelatihanData(Request $request)
    {
        // Query untuk pelatihan dari ms_pelatihan
        $pelatihanFromMaster = DB::table('ptk_pelatihan')
            ->select(
                'ms_pelatihan.nama_pelatihan',
                DB::raw('COUNT(ptk_pelatihan.ptk_pelatihan_id) as jumlah_ptk'),
                DB::raw("'master' as tipe")
            )
            ->leftJoin('ms_pelatihan', 'ptk_pelatihan.ms_pelatihan_id', '=', 'ms_pelatihan.ms_pelatihan_id')
            ->leftJoin('ptk', 'ptk_pelatihan.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->when($request->filled('kegiatan_id'), function ($q) use ($request) {
                $q->where('ptk_pelatihan.kegiatan_id', $request->kegiatan_id);
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->whereNotNull('ms_pelatihan.nama_pelatihan')
            ->groupBy('ms_pelatihan.nama_pelatihan');

        // Query untuk pelatihan lainnya (manual input)
        $pelatihanLainnya = DB::table('ptk_pelatihan')
            ->select(
                DB::raw('TRIM(pelatihan_lainnya) as nama_pelatihan'),
                DB::raw('COUNT(ptk_pelatihan.ptk_pelatihan_id) as jumlah_ptk'),
                DB::raw("'manual' as tipe")
            )
            ->leftJoin('ptk', 'ptk_pelatihan.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->when($request->filled('kegiatan_id'), function ($q) use ($request) {
                $q->where('ptk_pelatihan.kegiatan_id', $request->kegiatan_id);
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
            ->when($request->filled('jenjang_pendidikan_id'), function ($q) use ($request) {
                $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            })
            ->when($request->filled('bentuk_pendidikan'), function ($q) use ($request) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            })
            ->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            })
            ->whereNotNull('pelatihan_lainnya')
            ->where('pelatihan_lainnya', '!=', '')
            ->groupBy(DB::raw('TRIM(pelatihan_lainnya)'))
            ->union($pelatihanFromMaster);

        // Eksekusi query gabungan
        $data = DB::query()->fromSub($pelatihanLainnya, 'combined')
            ->select('nama_pelatihan', 'jumlah_ptk', 'tipe')
            ->orderByDesc('jumlah_ptk')
            ->limit(15)
            ->get();

        // Gabungkan data yang sama
        $groupedData = collect();

        foreach ($data as $item) {
            $nama = trim($item->nama_pelatihan);
            $existing = $groupedData->firstWhere('nama_pelatihan', $nama);

            if ($existing) {
                $existing->jumlah_ptk += $item->jumlah_ptk;
            } else {
                $groupedData->push((object)[
                    'nama_pelatihan' => $nama,
                    'jumlah_ptk' => $item->jumlah_ptk,
                    'tipe' => $item->tipe
                ]);
            }
        }

        return $groupedData->sortByDesc('jumlah_ptk')->values();
    }
















    // Tambahkan method exportExcel di dalam class AnalisisController
    public function exportExcel(Request $request)
    {
        try {
            // Set memory limit
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 300);

            // Dapatkan data analisis
            $analisisData = $this->getAnalisisData($request);

            // Buat spreadsheet baru
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(10);

            // ======================
            // SHEET 1: ANALISIS GRAFIK
            // ======================
            $sheet1 = $spreadsheet->getActiveSheet();
            $sheet1->setTitle('ANALISIS GRAFIK');

            // Set page setup
            $sheet1->getPageSetup()
                ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(PageSetup::PAPERSIZE_A4)
                ->setFitToWidth(1)
                ->setFitToHeight(0);

            // Header Sheet 1
            $currentRow = 1;

            // Judul utama
            $sheet1->mergeCells("A{$currentRow}:L{$currentRow}");
            $sheet1->setCellValue("A{$currentRow}", 'LAPORAN ANALISIS HASIL INSTRUMEN PTK');
            $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1a5bb8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow++;
            $sheet1->mergeCells("A{$currentRow}:L{$currentRow}");
            $sheet1->setCellValue("A{$currentRow}", 'Analisis Komprehensif Berdasarkan Filter yang Diterapkan');
            $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['size' => 12, 'color' => ['rgb' => '2d3748']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow++;
            $sheet1->mergeCells("A{$currentRow}:L{$currentRow}");
            $sheet1->setCellValue("A{$currentRow}", 'Dicetak: ' . now()->format('d F Y H:i:s'));
            $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666'], 'italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow += 2;

            // ======================
            // FILTER INFO
            // ======================
            $filterStartRow = $currentRow;
            $sheet1->mergeCells("A{$currentRow}:L{$currentRow}");
            $sheet1->setCellValue("A{$currentRow}", 'FILTER YANG DIGUNAKAN');
            $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a5bb8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            // Ambil nama filter
            $kegiatanName = '';
            if ($request->filled('kegiatan_id')) {
                $kegiatan = DB::table('kegiatan')->where('kegiatan_id', $request->kegiatan_id)->first();
                $kegiatanName = $kegiatan->kegiatan_name ?? '';
            }

            $jenjangName = '';
            if ($request->filled('pangkat_jabatan_id')) {
                $jenjang = DB::table('pangkat_jabatan')->where('pangkat_jabatan_id', $request->pangkat_jabatan_id)->first();
                $jenjangName = $jenjang->jenjang_jabatan ?? '';
            }

            $jenisPtkName = '';
            if ($request->filled('jenis_ptk_id')) {
                $jenisPtk = DB::table('jenis_ptk')->where('jenis_ptk_id', $request->jenis_ptk_id)->first();
                $jenisPtkName = $jenisPtk->jenis_ptk ?? '';
            }

            $kotaName = '';
            if ($request->filled('kota_id')) {
                $kota = DB::table('kota')->where('kota_id', $request->kota_id)->first();
                $kotaName = $kota->nama_kota ?? '';
            }

            // Tampilkan filter
            $filters = [
                ['Kegiatan:', $kegiatanName ?: 'Semua Kegiatan'],
                ['Jenjang Jabatan:', $jenjangName ?: 'Semua Jenjang'],
                ['Jenis PTK:', $jenisPtkName ?: 'Semua Jenis'],
                ['Kota:', $kotaName ?: 'Semua Kota'],
                ['Jenis Kelamin:', $request->jenis_kelamin ? ($request->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan') : 'Semua'],
                ['Total PTK:', $analisisData['statistik']['total_ptk'] ?? 0],
                ['PTK Menjawab:', $analisisData['statistik']['ptk_menjawab'] ?? 0],
                ['Progress:', ($analisisData['statistik']['persentase_isi'] ?? 0) . '%']
            ];

            foreach ($filters as $index => $filter) {
                $currentRow++;
                $sheet1->setCellValue("A{$currentRow}", $filter[0]);
                $sheet1->setCellValue("B{$currentRow}", $filter[1]);
                $sheet1->mergeCells("B{$currentRow}:L{$currentRow}");

                $bgColor = $index % 2 == 0 ? 'F8FAFC' : 'F1F5F9';
                $sheet1->getStyle("A{$currentRow}:L{$currentRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                ]);
                $sheet1->getStyle("A{$currentRow}")->applyFromArray(['font' => ['bold' => true]]);
            }

            $currentRow += 2;

            // ======================
            // STATISTIK UTAMA
            // ======================
            $statRow = $currentRow;
            $sheet1->mergeCells("A{$currentRow}:L{$currentRow}");
            $sheet1->setCellValue("A{$currentRow}", 'STATISTIK UTAMA');
            $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            $currentRow++;
            $stats = [
                ['Total PTK', $analisisData['statistik']['total_ptk'] ?? 0, 'ri-user-3-line'],
                ['PTK Menjawab', $analisisData['statistik']['ptk_menjawab'] ?? 0, 'ri-checkbox-circle-line'],
                ['Progress Pengisian', ($analisisData['statistik']['persentase_isi'] ?? 0) . '%', 'ri-progress-4-line']
            ];

            $col = 0;
            foreach ($stats as $stat) {
                $cell = chr(65 + $col * 4) . $currentRow; // A, E, I
                $sheet1->mergeCells($cell . ':' . chr(65 + $col * 4 + 3) . $currentRow);
                $sheet1->setCellValue($cell, $stat[0]);
                $sheet1->getStyle($cell)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E8F0']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $currentRow++;
                $cellValue = chr(65 + $col * 4) . $currentRow;
                $sheet1->mergeCells($cellValue . ':' . chr(65 + $col * 4 + 3) . $currentRow);
                $sheet1->setCellValue($cellValue, $stat[1]);
                $sheet1->getStyle($cellValue)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1a5bb8']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $col++;
            }

            $currentRow += 2;



            // ======================
            // DISTRIBUSI JENJANG JABATAN
            // ======================
            if (!empty($analisisData['jenjang_distribution'])) {
                $sheet1->mergeCells("A{$currentRow}:F{$currentRow}");
                $sheet1->setCellValue("A{$currentRow}", '1. DISTRIBUSI JENJANG JABATAN');
                $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8'], 'size' => 12],
                    'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1a5bb8']]]
                ]);

                $currentRow++;

                $headers = ['Jenjang Jabatan', 'Jumlah PTK', 'Persentase', 'Diagram'];
                foreach ($headers as $col => $header) {
                    $cell = chr(65 + $col) . $currentRow;
                    $sheet1->setCellValue($cell, $header);
                    $sheet1->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $currentRow++;

                $jenjangData = collect($analisisData['jenjang_distribution']);
                $totalJenjang = $jenjangData->sum('count');

                $jenjangColors = [
                    'Pertama' => '#ff6b6b',
                    'Muda' => '#4ecdc4',
                    'Madya' => '#45b7d1',
                    'Utama' => '#96ceb4'
                ];

                foreach ($jenjangData as $index => $jenjang) {
                    $percentage = $totalJenjang > 0 ? round(($jenjang['count'] / $totalJenjang) * 100, 1) : 0;
                    $color = $jenjangColors[$jenjang['jenjang_jabatan']] ?? '#CBD5E1';

                    $sheet1->setCellValue("A{$currentRow}", $jenjang['jenjang_jabatan']);
                    $sheet1->setCellValue("B{$currentRow}", $jenjang['count']);
                    $sheet1->setCellValue("C{$currentRow}", $percentage . '%');

                    // Diagram visual
                    $barLength = (int)($percentage / 5);
                    $diagram = str_repeat('█', $barLength) . " ({$percentage}%)";
                    $sheet1->setCellValue("D{$currentRow}", $diagram);

                    // Warna untuk diagram
                    $fontColor = new Color();
                    $fontColor->setRGB(substr($color, 1));
                    $sheet1->getStyle("D{$currentRow}")->getFont()->setColor($fontColor);

                    $bgColor = $currentRow % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                    $sheet1->getStyle("A{$currentRow}:D{$currentRow}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $sheet1->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet1->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $currentRow++;
                }

                $currentRow += 2;
            }



            // Dalam method exportExcel() di controller, setelah bagian "STATISTIK UTAMA", tambahkan:

            // ======================
            // GRAFIK DISTRIBUSI JENJANG PENDIDIKAN
            // ======================
            if (!empty($analisisData['jenjang_pendidikan_distribution'])) {
                $currentRow += 2;
                $chartStartRow = $currentRow;

                $sheet1->mergeCells("A{$currentRow}:F{$currentRow}");
                $sheet1->setCellValue("A{$currentRow}", '2. DISTRIBUSI JENJANG PENDIDIKAN PTK');
                $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8'], 'size' => 12],
                    'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1a5bb8']]]
                ]);

                $currentRow++;

                // Header tabel
                $headers = ['Jenjang Pendidikan', 'Jumlah PTK', 'Persentase', 'Diagram'];
                foreach ($headers as $col => $header) {
                    $cell = chr(65 + $col) . $currentRow;
                    $sheet1->setCellValue($cell, $header);
                    $sheet1->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $currentRow++;

                $jenjangPendidikanData = collect($analisisData['jenjang_pendidikan_distribution']);
                $totalJenjangPendidikan = $jenjangPendidikanData->sum('count');

                $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'];

                foreach ($jenjangPendidikanData as $index => $jenjang) {
                    $percentage = $totalJenjangPendidikan > 0 ? round(($jenjang['count'] / $totalJenjangPendidikan) * 100, 1) : 0;
                    $color = $colors[$index % count($colors)];

                    $sheet1->setCellValue("A{$currentRow}", $jenjang['jenjang_pendidikan']);
                    $sheet1->setCellValue("B{$currentRow}", $jenjang['count']);
                    $sheet1->setCellValue("C{$currentRow}", $percentage . '%');

                    // Diagram visual pie chart
                    $barLength = min((int)($percentage / 5), 20);
                    $diagram = str_repeat('◉', ceil($percentage / 20)) . " ({$percentage}%)";
                    $sheet1->setCellValue("D{$currentRow}", $diagram);

                    // Warna untuk diagram
                    $fontColor = new Color();
                    $fontColor->setRGB(substr($color, 1));
                    $sheet1->getStyle("D{$currentRow}")->getFont()->setColor($fontColor);

                    $bgColor = $currentRow % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                    $sheet1->getStyle("A{$currentRow}:D{$currentRow}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $sheet1->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet1->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $currentRow++;
                }

                // Total row
                $sheet1->setCellValue("A{$currentRow}", 'TOTAL');
                $sheet1->setCellValue("B{$currentRow}", $totalJenjangPendidikan);
                $sheet1->setCellValue("C{$currentRow}", '100%');
                $sheet1->setCellValue("D{$currentRow}", str_repeat('◉', 5));

                $sheet1->getStyle("A{$currentRow}:D{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $currentRow += 2;
            }

            // ======================
            // GRAFIK DISTRIBUSI JENIS KELAMIN
            // ======================
            if (!empty($analisisData['jenis_kelamin_distribution'])) {
                $chartStartRow = $currentRow;

                $sheet1->mergeCells("A{$currentRow}:F{$currentRow}");
                $sheet1->setCellValue("A{$currentRow}", '3. DISTRIBUSI JENIS KELAMIN PTK');
                $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8'], 'size' => 12],
                    'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1a5bb8']]]
                ]);

                $currentRow++;

                // Header tabel
                $headers = ['Jenis Kelamin', 'Jumlah PTK', 'Persentase', 'Diagram'];
                foreach ($headers as $col => $header) {
                    $cell = chr(65 + $col) . $currentRow;
                    $sheet1->setCellValue($cell, $header);
                    $sheet1->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $currentRow++;

                $jenisKelaminData = collect($analisisData['jenis_kelamin_distribution']);
                $totalJenisKelamin = $jenisKelaminData->sum('count');

                $genderColors = [
                    'Laki-laki' => '#4dc9f6',
                    'Perempuan' => '#f67019',
                    'Tidak Diketahui' => '#f53794'
                ];

                foreach ($jenisKelaminData as $index => $jenis) {
                    $percentage = $totalJenisKelamin > 0 ? round(($jenis['count'] / $totalJenisKelamin) * 100, 1) : 0;
                    $color = $genderColors[$jenis['jenis_kelamin']] ?? '#CBD5E1';

                    $sheet1->setCellValue("A{$currentRow}", $jenis['jenis_kelamin']);
                    $sheet1->setCellValue("B{$currentRow}", $jenis['count']);
                    $sheet1->setCellValue("C{$currentRow}", $percentage . '%');

                    // Diagram visual pie chart
                    $barLength = min((int)($percentage / 5), 20);
                    $diagram = str_repeat('●', ceil($percentage / 20)) . " ({$percentage}%)";
                    $sheet1->setCellValue("D{$currentRow}", $diagram);

                    // Warna untuk diagram
                    $fontColor = new Color();
                    $fontColor->setRGB(substr($color, 1));
                    $sheet1->getStyle("D{$currentRow}")->getFont()->setColor($fontColor);

                    $bgColor = $currentRow % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                    $sheet1->getStyle("A{$currentRow}:D{$currentRow}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $sheet1->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet1->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $currentRow++;
                }

                // Total row
                $sheet1->setCellValue("A{$currentRow}", 'TOTAL');
                $sheet1->setCellValue("B{$currentRow}", $totalJenisKelamin);
                $sheet1->setCellValue("C{$currentRow}", '100%');
                $sheet1->setCellValue("D{$currentRow}", str_repeat('●', 5));

                $sheet1->getStyle("A{$currentRow}:D{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $currentRow += 2;
            }

            // ======================
            // DIAGRAM BATANG DISTRIBUSI PTK PER SUB INDIKATOR (DIPERBAIKI)
            // ======================
            if (!empty($analisisData['all_sub_indikators_chart']['labels'])) {
                $chartStartRow = $currentRow;

                $sheet1->mergeCells("A{$currentRow}:L{$currentRow}");
                $sheet1->setCellValue("A{$currentRow}", '4. DISTRIBUSI PTK PER SUB INDIKATOR');
                $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8'], 'size' => 12],
                    'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1a5bb8']]]
                ]);

                $currentRow++;

                // Ambil data chart
                $chartData = $analisisData['all_sub_indikators_chart'];
                $labels = $chartData['labels'];
                $datasets = $chartData['datasets'];

                // Hitung jumlah kolom dengan benar
                $numColumns = count($datasets) + 2; // A (Sub Indikator) + datasets + TOTAL
                $lastColumnLetter = chr(65 + $numColumns - 1);

                // Siapkan tabel data
                $headers = ['Sub Indikator'];
                foreach ($datasets as $dataset) {
                    $headers[] = $dataset['label'];
                }
                $headers[] = 'TOTAL';

                // Tulis header
                foreach ($headers as $col => $header) {
                    $cell = chr(65 + $col) . $currentRow;
                    $sheet1->setCellValue($cell, $header);
                    $sheet1->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $currentRow++;

                // Siapkan array untuk menyimpan total per level
                $totalPerLevel = array_fill(0, count($datasets), 0);
                $grandTotal = 0;

                // Tulis data
                for ($i = 0; $i < count($labels); $i++) {
                    $subIndikator = $labels[$i];
                    $sheet1->setCellValue("A{$currentRow}", $subIndikator);

                    $totalRow = 0;
                    $colIndex = 1;

                    foreach ($datasets as $index => $dataset) {
                        $value = $dataset['data'][$i] ?? 0;
                        $totalRow += $value;

                        // Akumulasi total per level
                        $totalPerLevel[$index] += $value;

                        $cell = chr(65 + $colIndex) . $currentRow;
                        $sheet1->setCellValue($cell, $value);

                        // Warna sesuai level
                        $levelColor = $dataset['backgroundColor'] ?? '#17a2b8';
                        $fontColor = new Color();
                        $fontColor->setRGB(substr($levelColor, 1));
                        $sheet1->getStyle($cell)->getFont()->setColor($fontColor);

                        $colIndex++;
                    }

                    // Total per sub indikator
                    $totalCell = chr(65 + $colIndex) . $currentRow;
                    $sheet1->setCellValue($totalCell, $totalRow);
                    $sheet1->getStyle($totalCell)->applyFromArray([
                        'font' => ['bold' => true]
                    ]);

                    // Akumulasi grand total
                    $grandTotal += $totalRow;

                    // Background color alternatif
                    $bgColor = $currentRow % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                    $sheet1->getStyle("A{$currentRow}:{$totalCell}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $sheet1->getStyle("B{$currentRow}:{$totalCell}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $currentRow++;
                }

                // Footer dengan total
                $sheet1->setCellValue("A{$currentRow}", 'TOTAL');
                $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true]
                ]);

                // Hitung total per level dengan benar
                $colIndex = 1;
                foreach ($totalPerLevel as $totalLevel) {
                    $cell = chr(65 + $colIndex) . $currentRow;
                    $sheet1->setCellValue($cell, $totalLevel);
                    $sheet1->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true]
                    ]);
                    $colIndex++;
                }

                // Grand total
                $grandTotalCell = $lastColumnLetter . $currentRow;
                $sheet1->setCellValue($grandTotalCell, $grandTotal);
                $sheet1->getStyle($grandTotalCell)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8']]
                ]);

                $sheet1->getStyle("A{$currentRow}:{$grandTotalCell}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $currentRow += 2;
            }

            // ======================
            // DIAGRAM BATANG DISTRIBUSI PTK PER SUB INDIKATOR PER JENJANG JABATAN (DIPERBAIKI)
            // ======================
            if (!empty($analisisData['sub_indikator_per_jenjang'])) {
                foreach ($analisisData['sub_indikator_per_jenjang'] as $jenjangIndex => $jenjangData) {
                    $chartStartRow = $currentRow;

                    $sheet1->mergeCells("A{$currentRow}:L{$currentRow}");
                    $sheet1->setCellValue("A{$currentRow}", ($jenjangIndex + 5) . '. DISTRIBUSI PTK PER SUB INDIKATOR - ' . strtoupper($jenjangData['jenjang_jabatan']));
                    $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8'], 'size' => 12],
                        'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1a5bb8']]]
                    ]);

                    $currentRow++;

                    // Siapkan tabel data
                    $labels = $jenjangData['labels'];
                    $datasets = $jenjangData['datasets'];

                    // Hitung jumlah kolom dengan benar
                    $numColumns = count($datasets) + 2; // A (Sub Indikator) + datasets + TOTAL
                    $lastColumnLetter = chr(65 + $numColumns - 1);

                    $headers = ['Sub Indikator'];
                    foreach ($datasets as $dataset) {
                        $headers[] = $dataset['label'];
                    }
                    $headers[] = 'TOTAL';

                    // Tulis header
                    foreach ($headers as $col => $header) {
                        $cell = chr(65 + $col) . $currentRow;
                        $sheet1->setCellValue($cell, $header);
                        $sheet1->getStyle($cell)->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                        ]);
                    }

                    $currentRow++;

                    // Siapkan array untuk menyimpan total per level
                    $totalPerLevel = array_fill(0, count($datasets), 0);
                    $grandTotal = 0;

                    // Tulis data
                    for ($i = 0; $i < count($labels); $i++) {
                        $subIndikator = $labels[$i];
                        $sheet1->setCellValue("A{$currentRow}", $subIndikator);

                        $totalRow = 0;
                        $colIndex = 1;

                        foreach ($datasets as $index => $dataset) {
                            $value = $dataset['data'][$i] ?? 0;
                            $totalRow += $value;

                            // Akumulasi total per level
                            $totalPerLevel[$index] += $value;

                            $cell = chr(65 + $colIndex) . $currentRow;
                            $sheet1->setCellValue($cell, $value);

                            // Warna sesuai level
                            $levelColor = $dataset['backgroundColor'] ?? '#17a2b8';
                            $fontColor = new Color();
                            $fontColor->setRGB(substr($levelColor, 1));
                            $sheet1->getStyle($cell)->getFont()->setColor($fontColor);

                            $colIndex++;
                        }

                        // Total per sub indikator
                        $totalCell = chr(65 + $colIndex) . $currentRow;
                        $sheet1->setCellValue($totalCell, $totalRow);
                        $sheet1->getStyle($totalCell)->applyFromArray([
                            'font' => ['bold' => true]
                        ]);

                        // Akumulasi grand total
                        $grandTotal += $totalRow;

                        // Background color alternatif
                        $bgColor = $currentRow % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                        $sheet1->getStyle("A{$currentRow}:{$totalCell}")->applyFromArray([
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                        ]);

                        $sheet1->getStyle("B{$currentRow}:{$totalCell}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $currentRow++;
                    }

                    // Footer dengan total
                    $sheet1->setCellValue("A{$currentRow}", 'TOTAL');
                    $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                        'font' => ['bold' => true]
                    ]);

                    // Hitung total per level dengan benar
                    $colIndex = 1;
                    foreach ($totalPerLevel as $totalLevel) {
                        $cell = chr(65 + $colIndex) . $currentRow;
                        $sheet1->setCellValue($cell, $totalLevel);
                        $sheet1->getStyle($cell)->applyFromArray([
                            'font' => ['bold' => true]
                        ]);
                        $colIndex++;
                    }

                    // Grand total
                    $grandTotalCell = $lastColumnLetter . $currentRow;
                    $sheet1->setCellValue($grandTotalCell, $grandTotal);
                    $sheet1->getStyle($grandTotalCell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8']]
                    ]);

                    $sheet1->getStyle("A{$currentRow}:{$grandTotalCell}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);

                    $currentRow += 2;
                }
            }

            // ======================
            // TABEL MODUS LEVEL PER KOTA
            // ======================
            if (!empty($analisisData['modus_per_kota'])) {
                $chartStartRow = $currentRow;

                $sheet1->mergeCells("A{$currentRow}:G{$currentRow}");
                $sheet1->setCellValue("A{$currentRow}", '9. MODUS LEVEL PER KOTA');
                $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8'], 'size' => 12],
                    'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1a5bb8']]]
                ]);

                $currentRow++;

                // Header tabel
                $headers = ['No', 'Kota', 'Sub Indikator', 'Modus Level', 'Level Name', 'Jumlah PTK', 'Persentase'];
                foreach ($headers as $col => $header) {
                    $cell = chr(65 + $col) . $currentRow;
                    $sheet1->setCellValue($cell, $header);
                    $sheet1->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $currentRow++;

                $no = 1;
                $levelNames = [
                    1 => 'Dasar',
                    2 => 'Penerapan',
                    3 => 'Analisis',
                    4 => 'Evaluasi',
                    5 => 'Pembimbingan'
                ];

                $levelColors = [
                    1 => '#17a212',
                    2 => '#17a2b8',
                    3 => '#007bff',
                    4 => '#ffc107',
                    5 => '#28a745'
                ];

                foreach ($analisisData['modus_per_kota'] as $kota) {
                    if (empty($kota['sub_indikator_modus'])) continue;

                    $kotaRowSpan = count($kota['sub_indikator_modus']);
                    $firstRow = $currentRow;

                    foreach ($kota['sub_indikator_modus'] as $subIndex => $sub) {
                        $sheet1->setCellValue("A{$currentRow}", $no);
                        $sheet1->setCellValue("B{$currentRow}", $kota['nama_kota']);
                        $sheet1->setCellValue("C{$currentRow}", $sub['sub_indikator_code'] . ' - ' . substr($sub['sub_indikator_name'], 0, 30) . (strlen($sub['sub_indikator_name']) > 30 ? '...' : ''));
                        $sheet1->setCellValue("D{$currentRow}", $sub['modus_level']);
                        $sheet1->setCellValue("E{$currentRow}", $levelNames[$sub['modus_level']] ?? '');
                        $sheet1->setCellValue("F{$currentRow}", $sub['jumlah_jawaban']);

                        // Hitung persentase
                        $percentage = $kota['total_jawaban'] > 0 ? round(($sub['jumlah_jawaban'] / $kota['total_jawaban']) * 100, 1) : 0;
                        $sheet1->setCellValue("G{$currentRow}", $percentage . '%');

                        // Warna untuk level
                        $levelColor = $levelColors[$sub['modus_level']] ?? '#17a2b8';
                        $fontColor = new Color();
                        $fontColor->setRGB(substr($levelColor, 1));
                        $sheet1->getStyle("D{$currentRow}")->getFont()->setColor($fontColor);
                        $sheet1->getStyle("E{$currentRow}")->getFont()->setColor($fontColor);

                        // Background color alternatif
                        $bgColor = $currentRow % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                        $sheet1->getStyle("A{$currentRow}:G{$currentRow}")->applyFromArray([
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                        ]);

                        // Merge kota name jika lebih dari 1 sub indikator
                        if ($subIndex === 0 && $kotaRowSpan > 1) {
                            $sheet1->mergeCells("B{$firstRow}:B" . ($firstRow + $kotaRowSpan - 1));
                            $sheet1->getStyle("B{$firstRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                        }

                        $currentRow++;
                    }

                    $no++;
                }

                $currentRow += 2;
            }

            // Set column widths untuk Sheet 1 (tambahkan di akhir)
            $sheet1->getColumnDimension('A')->setWidth(20);
            $sheet1->getColumnDimension('B')->setWidth(15);
            $sheet1->getColumnDimension('C')->setWidth(15);
            $sheet1->getColumnDimension('D')->setWidth(15);
            $sheet1->getColumnDimension('E')->setWidth(25);
            $sheet1->getColumnDimension('F')->setWidth(25);
            $sheet1->getColumnDimension('G')->setWidth(15);
            $sheet1->getColumnDimension('H')->setWidth(15);
            $sheet1->getColumnDimension('I')->setWidth(15);
            $sheet1->getColumnDimension('J')->setWidth(15);
            $sheet1->getColumnDimension('K')->setWidth(15);
            $sheet1->getColumnDimension('L')->setWidth(20);

            // ======================
            // PROGRESS PER KOTA
            // ======================
            if (!empty($analisisData['progress_kota'])) {
                $sheet1->mergeCells("A{$currentRow}:F{$currentRow}");
                $sheet1->setCellValue("A{$currentRow}", '10. PROGRESS PENGISIAN PER KOTA');
                $sheet1->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8'], 'size' => 12],
                    'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1a5bb8']]]
                ]);

                $currentRow++;

                $headers = ['Kota', 'Total PTK', 'Sudah Isi', 'Persentase', 'Status'];
                foreach ($headers as $col => $header) {
                    $cell = chr(65 + $col) . $currentRow;
                    $sheet1->setCellValue($cell, $header);
                    $sheet1->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $currentRow++;

                foreach ($analisisData['progress_kota'] as $kota) {
                    $status = '';
                    $statusColor = '';
                    if ($kota->persentase >= 80) {
                        $status = 'Baik';
                        $statusColor = '10B981';
                    } elseif ($kota->persentase >= 50) {
                        $status = 'Cukup';
                        $statusColor = 'F59E0B';
                    } else {
                        $status = 'Perlu Perhatian';
                        $statusColor = 'EF4444';
                    }

                    $sheet1->setCellValue("A{$currentRow}", $kota->nama_kota);
                    $sheet1->setCellValue("B{$currentRow}", $kota->total_ptk);
                    $sheet1->setCellValue("C{$currentRow}", $kota->sudah_isi);
                    $sheet1->setCellValue("D{$currentRow}", $kota->persentase . '%');
                    $sheet1->setCellValue("E{$currentRow}", $status);

                    // Warna status
                    $sheet1->getStyle("E{$currentRow}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => $statusColor]]
                    ]);

                    $bgColor = $currentRow % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                    $sheet1->getStyle("A{$currentRow}:E{$currentRow}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $sheet1->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet1->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet1->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet1->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $currentRow++;
                }

                $currentRow += 2;
            }

            // Set column widths untuk Sheet 1
            $sheet1->getColumnDimension('A')->setWidth(20);
            $sheet1->getColumnDimension('B')->setWidth(15);
            $sheet1->getColumnDimension('C')->setWidth(15);
            $sheet1->getColumnDimension('D')->setWidth(15);
            $sheet1->getColumnDimension('E')->setWidth(25);
            $sheet1->getColumnDimension('F')->setWidth(25);

            // ======================
            // SHEET 2: DETAIL HASIL INSTRUMEN (SAMA PERSIS DENGAN HASIL INSTRUMEN)
            // ======================
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('DETAIL HASIL');
            $sheet2->getPageSetup()
                ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(PageSetup::PAPERSIZE_A4)
                ->setFitToWidth(1)
                ->setFitToHeight(0);

            $row2 = 1;

            // JUDUL UTAMA SHEET 2
            $sheet2->mergeCells("A{$row2}:G{$row2}");
            $sheet2->setCellValue("A{$row2}", 'LAPORAN HASIL INSTRUMEN PTK DENGAN REKOMENDASI GAP ANALYSIS');
            $sheet2->getStyle("A{$row2}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row2++;
            $sheet2->mergeCells("A{$row2}:G{$row2}");
            $sheet2->setCellValue("A{$row2}", 'Penilaian Kompetensi Profesional Berbasis Level Kompetensi');
            $sheet2->getStyle("A{$row2}")->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row2++;
            $sheet2->mergeCells("A{$row2}:G{$row2}");
            $sheet2->setCellValue("A{$row2}", 'Dicetak: ' . now()->format('d F Y H:i:s'));
            $sheet2->getStyle("A{$row2}")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row2 += 2;

            // FILTER INFO (SAMA DENGAN ANALISIS UTAMA)
            $sheet2->mergeCells("A{$row2}:G{$row2}");
            $sheet2->setCellValue("A{$row2}", 'FILTER YANG DIGUNAKAN');
            $sheet2->getStyle("A{$row2}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            $filters = [
                ['Kegiatan:', $kegiatanName ?: 'Semua'],
                ['Jenjang Jabatan:', $jenjangName ?: 'Semua'],
                ['Jenis PTK:', $jenisPtkName ?: 'Semua'],
                ['Kota:', $kotaName ?: 'Semua'],
                ['Jenis Kelamin:', $request->jenis_kelamin ? ($request->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan') : 'Semua'],
                ['Total PTK:', $analisisData['statistik']['ptk_menjawab'] ?? 0 . ' PTK'],
            ];

            foreach ($filters as $filter) {
                $row2++;
                $sheet2->setCellValue("A{$row2}", $filter[0]);
                $sheet2->setCellValue("B{$row2}", $filter[1]);
                $sheet2->mergeCells("B{$row2}:G{$row2}");
                $sheet2->getStyle("A{$row2}:G{$row2}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9F9F9']]
                ]);
                $sheet2->getStyle("A{$row2}")->applyFromArray(['font' => ['bold' => true]]);
            }

            $row2 += 2;

            // AMBIL DATA DETAIL HASIL INSTRUMEN (SAMA DENGAN QUERY EXPORT)
            $detailData = $this->getDetailHasilInstrumenExcel($request);

            // KELOMPOKKAN DATA PER PTK (SAMA DENGAN PDF)
            $groupedData = $detailData->groupBy('nip');

            if (!empty($groupedData)) {
                foreach ($groupedData as $nip => $dataRows) {
                    if ($dataRows->isEmpty()) continue;

                    $firstRow = $dataRows->first();

                    // PROSES REKOMENDASI UNTUK SETIAP ROW
                    $processedRows = [];
                    foreach ($dataRows as $row) {
                        // Gunakan fungsi getRekomendasiWithGap yang SAMA
                        $rekomendasiInfo = $this->getRekomendasiWithGapExcel(
                            $row->jenjang_jabatan,
                            $row->level_dicapai,
                            $row->sub_indikator_id,
                            $row->tahap,
                            $row->entity,
                            $row->sub_indikator_code
                        );

                        $row->rekomendasi_info = $rekomendasiInfo;
                        $processedRows[] = $row;
                    }

                    // HEADER PTK (SAMA DENGAN PDF)
                    $sheet2->mergeCells("A{$row2}:G{$row2}");
                    $sheet2->setCellValue("A{$row2}", $firstRow->nama ?? 'Nama tidak tersedia');
                    $sheet2->getStyle("A{$row2}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2C3E50']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);

                    // KEGIATAN BADGE (SAMA DENGAN PDF)
                    $sheet2->setCellValue("F{$row2}", $firstRow->kegiatan_name ?? 'Kegiatan');
                    $sheet2->getStyle("F{$row2}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E74C3C']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);

                    $row2++;

                    // INFO PTK (SAMA DENGAN PDF)
                    $infoData = [
                        ['NIP:', $nip],
                        ['Jenjang:', $firstRow->jenjang_jabatan ?? '-'],
                        ['Pangkat:', ($firstRow->pangkat ?? '-') . ' ' . ($firstRow->golongan_ruang ? '(' . $firstRow->golongan_ruang . ')' : '')],
                        ['Instansi:', $firstRow->instansi ?? '-'],
                        ['Sekolah:', $firstRow->nama_sekolah ?? '-'],
                        ['NPSN:', $firstRow->npsn ?? '-'],
                        ['Kota:', $firstRow->nama_kota ?? '-'],
                        ['Kegiatan:', $firstRow->kegiatan_name ?? '-'],
                        ['Entity:', $firstRow->entity ?? '-'],
                        ['Tahap:', $firstRow->tahap ?? '-'],
                        ['Jenis PTK:', $firstRow->jenis_ptk ?? '-'],
                    ];

                    $infoStartRow = $row2;
                    foreach ($infoData as $index => $info) {
                        $sheet2->setCellValue("A{$row2}", $info[0]);
                        $sheet2->setCellValue("B{$row2}", $info[1]);
                        $sheet2->mergeCells("B{$row2}:G{$row2}");

                        $bgColor = $index % 2 == 0 ? 'FFFFFF' : 'F9F9F9';
                        $sheet2->getStyle("A{$row2}:G{$row2}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                        ]);
                        $sheet2->getStyle("A{$row2}")->applyFromArray(['font' => ['bold' => true]]);

                        $row2++;
                    }

                    // MERGE KOLOM LABEL INFO
                    for ($i = $infoStartRow; $i < $row2; $i++) {
                        $sheet2->mergeCells("A{$i}:A{$i}");
                    }

                    // PELATIHAN SECTION (SAMA DENGAN PDF)
                    $pelatihanData = $this->getPelatihanByPtkExcel($firstRow->ptk_id, $firstRow->kegiatan_id);

                    if ($pelatihanData->count() > 0) {
                        $row2++;
                        $sheet2->mergeCells("A{$row2}:G{$row2}");
                        $sheet2->setCellValue("A{$row2}", 'Pelatihan yang Anda Perlukan:');
                        $sheet2->getStyle("A{$row2}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => '2C3E50']]
                        ]);

                        $row2++;
                        $pelStartRow = $row2;
                        foreach ($pelatihanData as $index => $pelatihan) {
                            $sheet2->mergeCells("A{$row2}:G{$row2}");
                            $sheet2->setCellValue(
                                "A{$row2}",
                                ($index + 1) . '. ' . ($pelatihan->nama_pelatihan_lengkap ?? 'Belum Tersedia') .
                                    ' [' . ($pelatihan->kategori_pelatihan ?? 'Tidak Diketahui') . ']'
                            );
                            $sheet2->getStyle("A{$row2}")->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']],
                                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                                'font' => ['color' => ['rgb' => '1565C0']]
                            ]);
                            $row2++;
                        }
                    } else {
                        $row2++;
                        $sheet2->mergeCells("A{$row2}:G{$row2}");
                        $sheet2->setCellValue("A{$row2}", 'Pelatihan yang Anda Perlukan:');
                        $sheet2->getStyle("A{$row2}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => '2C3E50']]
                        ]);

                        $row2++;
                        $sheet2->mergeCells("A{$row2}:G{$row2}");
                        $sheet2->setCellValue("A{$row2}", 'Belum ada data pelatihan');
                        $sheet2->getStyle("A{$row2}")->applyFromArray([
                            'font' => ['color' => ['rgb' => '666666']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                        ]);
                        $row2++;
                    }

                    $row2++;

                    // HEADER TABEL (SAMA DENGAN PDF)
                    $headerRow = $row2;
                    $headers = ['NO', 'KODE SUB INDIKATOR', 'NAMA SUB INDIKATOR', 'LEVEL DICAPAI', 'LEVEL HARUS', 'STATUS', 'REKOMENDASI (GAP)'];

                    foreach ($headers as $col => $header) {
                        $columnLetter = chr(65 + $col);
                        $sheet2->setCellValue($columnLetter . $row2, $header);
                        $sheet2->getStyle($columnLetter . $row2)->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '34495E']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '2C3E50']]]
                        ]);
                    }

                    $row2++;

                    // DATA INDIKATOR (SAMA DENGAN PDF)
                    $indikatorNumber = 1;
                    foreach ($processedRows as $row) {
                        $info = $row->rekomendasi_info;

                        // Tentukan level harus
                        $levelMin = $info['level_min'] ?? 0;
                        $levelMax = $info['level_max'] ?? 0;
                        $levelHarus = '';
                        for ($i = $levelMin; $i <= $levelMax; $i++) {
                            $levelHarus .= ($levelHarus ? ', ' : '') . 'Lv ' . $i;
                        }

                        // STATUS (SAMA DENGAN PDF)
                        $status = $info['status'] ?? '-';
                        $statusClass = $info['status_class'] ?? 'secondary';

                        // REKOMENDASI GAP (SAMA DENGAN PDF)
                        $rekomendasiGap = $info['rekomendasi_gap'] ?? [];
                        $rekText = '';
                        if (!empty($rekomendasiGap)) {
                            foreach ($rekomendasiGap as $gap) {
                                $rekText .= 'Gap Level ' . ($gap['level'] ?? '') . ': ' .
                                    ($gap['rekomendasi'] ?? '') . "\n";
                            }
                        } else {
                            $rekText = 'Sudah mencapai semua level';
                        }

                        // ISI DATA
                        $sheet2->setCellValue("A{$row2}", $indikatorNumber);
                        $sheet2->setCellValue("B{$row2}", $row->sub_indikator_code);
                        $sheet2->setCellValue("C{$row2}", $row->sub_indikator_name);
                        $sheet2->setCellValue("D{$row2}", $row->level_dicapai ? 'Level ' . $row->level_dicapai : '-');
                        $sheet2->setCellValue("E{$row2}", $levelHarus);
                        $sheet2->setCellValue("F{$row2}", $status);
                        $sheet2->setCellValue("G{$row2}", $rekText);

                        // STYLING UNTUK BARIS DATA (SAMA DENGAN PDF)
                        $bgColor = $indikatorNumber % 2 == 0 ? 'FFFFFF' : 'F9F9F9';
                        $sheet2->getStyle("A{$row2}:G{$row2}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                            'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true]
                        ]);

                        // ALIGNMENT
                        $sheet2->getStyle("A{$row2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet2->getStyle("D{$row2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet2->getStyle("E{$row2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet2->getStyle("F{$row2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        // STYLING UNTUK STATUS (SAMA DENGAN PDF)
                        $statusStyles = [
                            'Mencapai Semua Level' => ['color' => '0F5132', 'bg' => 'D1E7DD'],
                            'Mendekati Target' => ['color' => '664D03', 'bg' => 'FFF3CD'],
                            'Perlu Peningkatan' => ['color' => '842029', 'bg' => 'F8D7DA']
                        ];

                        if (isset($statusStyles[$status])) {
                            $style = $statusStyles[$status];
                            $sheet2->getStyle("F{$row2}")->applyFromArray([
                                'font' => ['color' => ['rgb' => $style['color']]],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $style['bg']]],
                                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                            ]);
                        }

                        // STYLING UNTUK LEVEL DICAPAI (SAMA DENGAN PDF)
                        if ($row->level_dicapai) {
                            $levelColors = [
                                1 => ['color' => 'FFFFFF', 'bg' => '17A2B8'],
                                2 => ['color' => 'FFFFFF', 'bg' => '007BFF'],
                                3 => ['color' => 'FFFFFF', 'bg' => 'FFC107'],
                                4 => ['color' => 'FFFFFF', 'bg' => '28A745'],
                                5 => ['color' => 'FFFFFF', 'bg' => '6C757D']
                            ];

                            $levelColor = $levelColors[$row->level_dicapai] ?? ['color' => 'FFFFFF', 'bg' => '6C757D'];
                            $sheet2->getStyle("D{$row2}")->applyFromArray([
                                'font' => ['color' => ['rgb' => $levelColor['color']], 'bold' => true],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $levelColor['bg']]],
                                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                            ]);
                        }

                        // STYLING UNTUK LEVEL HARUS (SAMA DENGAN PDF)
                        $sheet2->getStyle("E{$row2}")->applyFromArray([
                            'font' => ['bold' => true],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                        ]);

                        $row2++;
                        $indikatorNumber++;
                    }

                    // SUMMARY PTK (SAMA DENGAN PDF)
                    $summaryRow = $row2;
                    $sheet2->mergeCells("A{$row2}:G{$row2}");
                    $sheet2->setCellValue("A{$row2}", "SUMMARY: " . count($processedRows) . " Sub indikator dinilai");
                    $sheet2->getStyle("A{$row2}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '2C3E50']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);

                    $row2 += 3; // Spasi antar PTK
                }
            }

            // FOOTER (SAMA DENGAN PDF)
            $sheet2->mergeCells("A{$row2}:G{$row2}");
            $sheet2->setCellValue("A{$row2}", 'Catatan: Dokumen untuk keperluan internal evaluasi');
            $sheet2->getStyle("A{$row2}")->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row2++;
            $sheet2->mergeCells("A{$row2}:G{$row2}");
            $sheet2->setCellValue("A{$row2}", 'Laporan ini menunjukkan gap antara level kompetensi yang dicapai dengan level yang harus dicapai berdasarkan jenjang jabatan');
            $sheet2->getStyle("A{$row2}")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row2++;
            $sheet2->mergeCells("A{$row2}:G{$row2}");
            $sheet2->setCellValue("A{$row2}", '© ' . date('Y') . ' - Sistem TanpaRagu | Dicetak: ' . now()->format('d F Y H:i:s'));
            $sheet2->getStyle("A{$row2}")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            // SET COLUMN WIDTHS (SAMA DENGAN PDF)
            $sheet2->getColumnDimension('A')->setWidth(6);   // NO
            $sheet2->getColumnDimension('B')->setWidth(15);  // KODE
            $sheet2->getColumnDimension('C')->setWidth(35);  // INDIKATOR
            $sheet2->getColumnDimension('D')->setWidth(12);  // LEVEL DICAPAI
            $sheet2->getColumnDimension('E')->setWidth(12);  // LEVEL HARUS
            $sheet2->getColumnDimension('F')->setWidth(18);  // STATUS
            $sheet2->getColumnDimension('G')->setWidth(50);  // REKOMENDASI

            // AUTO WRAP TEXT UNTUK KOLOM REKOMENDASI
            $sheet2->getStyle('G')->getAlignment()->setWrapText(true);

            // ======================
            // SHEET 3: PELATIHAN (VERSI SEDERHANA)
            // ======================
            $sheet3 = $spreadsheet->createSheet();
            $sheet3->setTitle('PELATIHAN');
            $sheet3->getPageSetup()
                ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(PageSetup::PAPERSIZE_A4);

            $row3 = 1;

            // JUDUL SHEET
            $sheet3->mergeCells("A{$row3}:F{$row3}");
            $sheet3->setCellValue("A{$row3}", 'DATA PELATIHAN YANG DIPILIH PTK');
            $sheet3->getStyle("A{$row3}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row3 += 2;

            // BAGIAN 1: JUMLAH PTK YANG MEMILIH SETIAP PELATIHAN
            $sheet3->mergeCells("A{$row3}:F{$row3}");
            $sheet3->setCellValue("A{$row3}", '1. JUMLAH PTK PER PELATIHAN');
            $sheet3->getStyle("A{$row3}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8']],
                'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM]]
            ]);

            $row3++;

            // Header tabel jumlah
            $headersJumlah = ['No', 'Nama Pelatihan', 'Jumlah PTK', 'Persentase'];
            foreach ($headersJumlah as $col => $header) {
                $cell = chr(65 + $col) . $row3;
                $sheet3->setCellValue($cell, $header);
                $sheet3->getStyle($cell)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);
            }

            $row3++;

            // Ambil data pelatihan yang sudah ada di $analisisData
            $pelatihanData = $analisisData['pelatihan_data'] ?? [];

            if (!empty($pelatihanData)) {
                $no = 1;
                $totalPtkPelatihan = 0;

                foreach ($pelatihanData as $pelatihan) {
                    $totalPtkPelatihan += $pelatihan->jumlah_ptk;
                }

                // Tampilkan data pelatihan
                foreach ($pelatihanData as $pelatihan) {
                    $percentage = $totalPtkPelatihan > 0 ? round(($pelatihan->jumlah_ptk / $totalPtkPelatihan) * 100, 1) : 0;

                    $sheet3->setCellValue("A{$row3}", $no);
                    $sheet3->setCellValue("B{$row3}", $pelatihan->nama_pelatihan);
                    $sheet3->setCellValue("C{$row3}", $pelatihan->jumlah_ptk);
                    $sheet3->setCellValue("D{$row3}", $percentage . '%');

                    // Diagram visual sederhana
                    $barLength = min((int)($percentage / 5), 20);
                    $diagram = str_repeat('█', $barLength);
                    $sheet3->setCellValue("E{$row3}", $diagram);
                    $sheet3->setCellValue("F{$row3}", $pelatihan->tipe == 'master' ? 'Master' : 'Manual');

                    // Styling
                    $bgColor = $row3 % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                    $sheet3->getStyle("A{$row3}:F{$row3}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $row3++;
                    $no++;
                }

                // Total row
                $sheet3->setCellValue("A{$row3}", 'TOTAL');
                $sheet3->setCellValue("C{$row3}", $totalPtkPelatihan);
                $sheet3->setCellValue("D{$row3}", '100%');

                $sheet3->getStyle("A{$row3}:F{$row3}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);
            }

            $row3 += 3;

            // BAGIAN 2: DAFTAR ORANG YANG MEMILIH PELATIHAN
            $sheet3->mergeCells("A{$row3}:F{$row3}");
            $sheet3->setCellValue("A{$row3}", '2. DAFTAR PTK YANG MEMILIH PELATIHAN');
            $sheet3->getStyle("A{$row3}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '1a5bb8']],
                'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM]]
            ]);

            $row3++;

            // Header tabel detail
            $headersDetail = ['No', 'Nama PTK', 'NIP', 'Jenjang Jabatan', 'Kota', 'Pelatihan Dipilih'];
            foreach ($headersDetail as $col => $header) {
                $cell = chr(65 + $col) . $row3;
                $sheet3->setCellValue($cell, $header);
                $sheet3->getStyle($cell)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);
            }

            $row3++;

            // Ambil data detail PTK yang memilih pelatihan
            $detailPelatihan = $this->getDetailPelatihanPTK($request);

            if (!empty($detailPelatihan)) {
                $noDetail = 1;

                foreach ($detailPelatihan as $detail) {
                    $sheet3->setCellValue("A{$row3}", $noDetail);
                    $sheet3->setCellValue("B{$row3}", $detail->nama ?? '');
                    $sheet3->setCellValue("C{$row3}", $detail->nip ?? '');
                    $sheet3->setCellValue("D{$row3}", $detail->jenjang_jabatan ?? '');
                    $sheet3->setCellValue("E{$row3}", $detail->nama_kota ?? '');
                    $sheet3->setCellValue("F{$row3}", $detail->nama_pelatihan ?? '');

                    // Styling
                    $bgColor = $row3 % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                    $sheet3->getStyle("A{$row3}:F{$row3}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $row3++;
                    $noDetail++;
                }
            }

            // Set lebar kolom
            $sheet3->getColumnDimension('A')->setWidth(6);
            $sheet3->getColumnDimension('B')->setWidth(25);
            $sheet3->getColumnDimension('C')->setWidth(20);
            $sheet3->getColumnDimension('D')->setWidth(15);
            $sheet3->getColumnDimension('E')->setWidth(15);
            $sheet3->getColumnDimension('F')->setWidth(30);

            // Set active sheet kembali ke sheet 1
            $spreadsheet->setActiveSheetIndex(0);




            // ======================
            // SHEET 4: PTK PROGRESS (DIPERBAIKI)
            // ======================
            $sheet4 = $spreadsheet->createSheet();
            $sheet4->setTitle('PTK PROGRESS');
            $sheet4->getPageSetup()
                ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(PageSetup::PAPERSIZE_A4);

            $row4 = 1;

            // JUDUL SHEET 4
            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", 'MONITORING PROGRESS PTK PER KEGIATAN');
            $sheet4->getStyle("A{$row4}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1a5bb8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row4++;
            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", 'Status Penyelesaian Instrumen Berdasarkan Entity');
            $sheet4->getStyle("A{$row4}")->applyFromArray([
                'font' => ['size' => 12, 'color' => ['rgb' => '2d3748']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row4++;
            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", 'Target: Guru = 13 Sub Indikator, Kepala Sekolah & Pengawas = 9 Sub Indikator');
            $sheet4->getStyle("A{$row4}")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666'], 'italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row4 += 2;

            // FILTER INFO
            $kegiatanName = '';
            if ($request->filled('kegiatan_id')) {
                $kegiatan = DB::table('kegiatan')->where('kegiatan_id', $request->kegiatan_id)->first();
                $kegiatanName = $kegiatan->kegiatan_name ?? '';
            }

            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", 'FILTER: ' . ($kegiatanName ?: 'Semua Kegiatan'));
            $sheet4->getStyle("A{$row4}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            $row4++;

            // AMBIL DATA PTK PROGRESS
            $progressData = $this->getPtkProgressData($request);

            // PERBAIKAN: Ambil data PTK yang BELUM MENJAWAB sama sekali (gunakan fungsi yang sudah ada)
            $ptkBelumMenjawabData = $this->getPtkBelumMenjawab($request);

            // GROUP BY STATUS untuk PTK yang sudah menjawab
            $groupedProgress = [
                'selesai' => [],
                'dalam_proses' => []
            ];

            foreach ($progressData as $ptk) {
                // Tentukan target berdasarkan entity
                $entity = strtolower($ptk->entity ?? '');
                $target = 13; // default untuk guru

                if (strpos($entity, 'kepala') !== false || strpos($entity, 'pengawas') !== false) {
                    $target = 9;
                }

                $progressPercent = $target > 0 ? round(($ptk->jumlah_sub_indikator / $target) * 100, 0) : 0;

                if ($progressPercent >= 100) {
                    $groupedProgress['selesai'][] = $ptk;
                } else {
                    $groupedProgress['dalam_proses'][] = $ptk;
                }
            }

            // PTK BELUM MULAI adalah yang belum menjawab sama sekali
            $groupedProgress['belum_mulai'] = $ptkBelumMenjawabData;

            // ======================
            // BAGIAN 1: PTK SELESAI (100%)
            // ======================
            if (!empty($groupedProgress['selesai'])) {
                $sheet4->mergeCells("A{$row4}:L{$row4}");
                $sheet4->setCellValue("A{$row4}", '1. PTK YANG SUDAH SELESAI (100%)');
                $sheet4->getStyle("A{$row4}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '28a745']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $row4++;

                // Header tabel selesai
                $headersSelesai = ['No', 'NIP', 'Nama', 'Jenjang Jabatan', 'Entity', 'Sekolah', 'Kota', 'Sub Indikator', 'Target', 'Progress', 'Status'];
                foreach ($headersSelesai as $col => $header) {
                    $cell = chr(65 + $col) . $row4;
                    $sheet4->setCellValue($cell, $header);
                    $sheet4->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $row4++;

                $noSelesai = 1;
                foreach ($groupedProgress['selesai'] as $ptk) {
                    $entity = strtolower($ptk->entity ?? '');
                    $target = 13;
                    if (strpos($entity, 'kepala') !== false || strpos($entity, 'pengawas') !== false) {
                        $target = 9;
                    }

                    $progressPercent = $target > 0 ? round(($ptk->jumlah_sub_indikator / $target) * 100, 0) : 0;

                    $sheet4->setCellValue("A{$row4}", $noSelesai);
                    $sheet4->setCellValue("B{$row4}", $ptk->nip ?? '-');
                    $sheet4->setCellValue("C{$row4}", $ptk->nama ?? '-');
                    $sheet4->setCellValue("D{$row4}", $ptk->jenjang_jabatan ?? '-');
                    $sheet4->setCellValue("E{$row4}", $ptk->entity ?? '-');
                    $sheet4->setCellValue("F{$row4}", $ptk->nama_sekolah ?? '-');
                    $sheet4->setCellValue("G{$row4}", $ptk->nama_kota ?? '-');
                    $sheet4->setCellValue("H{$row4}", $ptk->jumlah_sub_indikator);
                    $sheet4->setCellValue("I{$row4}", $target);
                    $sheet4->setCellValue("J{$row4}", $progressPercent . '%');
                    $sheet4->setCellValue("K{$row4}", 'SELESAI');

                    // Progress bar visual
                    $progressBar = str_repeat('█', 10);
                    $sheet4->setCellValue("J{$row4}", $progressBar);
                    $sheet4->getStyle("J{$row4}")->getFont()->setColor(new Color('28a745'));

                    // Styling
                    $bgColor = $row4 % 2 == 0 ? 'F0FFF4' : 'E6F7EC';
                    $sheet4->getStyle("A{$row4}:K{$row4}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $sheet4->getStyle("H{$row4}:I{$row4}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $row4++;
                    $noSelesai++;
                }

                $row4 += 2;
            }

            // ======================
            // BAGIAN 2: PTK DALAM PROSES (<100%)
            // ======================
            if (!empty($groupedProgress['dalam_proses'])) {
                $sheet4->mergeCells("A{$row4}:L{$row4}");
                $sheet4->setCellValue("A{$row4}", '2. PTK DALAM PROSES (<100%)');
                $sheet4->getStyle("A{$row4}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ffc107']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $row4++;

                // Header tabel proses
                $headersProses = ['No', 'NIP', 'Nama', 'Jenjang Jabatan', 'Entity', 'Sekolah', 'Kota', 'Sub Indikator', 'Target', 'Progress', 'Kurang'];
                foreach ($headersProses as $col => $header) {
                    $cell = chr(65 + $col) . $row4;
                    $sheet4->setCellValue($cell, $header);
                    $sheet4->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $row4++;

                $noProses = 1;
                foreach ($groupedProgress['dalam_proses'] as $ptk) {
                    $entity = strtolower($ptk->entity ?? '');
                    $target = 13;
                    if (strpos($entity, 'kepala') !== false || strpos($entity, 'pengawas') !== false) {
                        $target = 9;
                    }

                    $progressPercent = $target > 0 ? round(($ptk->jumlah_sub_indikator / $target) * 100, 0) : 0;
                    $kurang = max(0, $target - $ptk->jumlah_sub_indikator);

                    $sheet4->setCellValue("A{$row4}", $noProses);
                    $sheet4->setCellValue("B{$row4}", $ptk->nip ?? '-');
                    $sheet4->setCellValue("C{$row4}", $ptk->nama ?? '-');
                    $sheet4->setCellValue("D{$row4}", $ptk->jenjang_jabatan ?? '-');
                    $sheet4->setCellValue("E{$row4}", $ptk->entity ?? '-');
                    $sheet4->setCellValue("F{$row4}", $ptk->nama_sekolah ?? '-');
                    $sheet4->setCellValue("G{$row4}", $ptk->nama_kota ?? '-');
                    $sheet4->setCellValue("H{$row4}", $ptk->jumlah_sub_indikator);
                    $sheet4->setCellValue("I{$row4}", $target);
                    $sheet4->setCellValue("J{$row4}", $progressPercent . '%');
                    $sheet4->setCellValue("K{$row4}", $kurang);

                    // Progress bar visual berdasarkan persentase
                    $barLength = min((int)($progressPercent / 10), 10);
                    $progressBar = str_repeat('█', $barLength) . str_repeat('░', 10 - $barLength);
                    $sheet4->setCellValue("J{$row4}", $progressBar);

                    // Warna progress bar
                    if ($progressPercent >= 70) {
                        $sheet4->getStyle("J{$row4}")->getFont()->setColor(new Color('28a745'));
                    } elseif ($progressPercent >= 40) {
                        $sheet4->getStyle("J{$row4}")->getFont()->setColor(new Color('ffc107'));
                    } else {
                        $sheet4->getStyle("J{$row4}")->getFont()->setColor(new Color('dc3545'));
                    }

                    // Styling
                    $bgColor = $row4 % 2 == 0 ? 'FFFBF0' : 'FFF9E6';
                    $sheet4->getStyle("A{$row4}:K{$row4}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $sheet4->getStyle("H{$row4}:I{$row4}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $row4++;
                    $noProses++;
                }

                $row4 += 2;
            }

            // ======================
            // BAGIAN 3: PTK BELUM MULAI (0%) - DIPERBAIKI LANGSUNG
            // ======================
            if (!empty($groupedProgress['belum_mulai'])) {
                $sheet4->mergeCells("A{$row4}:L{$row4}");
                $sheet4->setCellValue("A{$row4}", '3. PTK BELUM MULAI (0%)');
                $sheet4->getStyle("A{$row4}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dc3545']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $row4++;

                // HEADER TABEL YANG SESUAI DENGAN DATA DARI getPtkBelumMenjawab()
                $headersBelum = ['No', 'NIP', 'Nama', 'Jenjang Jabatan', 'Jenis PTK', 'Sekolah', 'Kota', 'Instansi', 'Target', 'Status'];
                foreach ($headersBelum as $col => $header) {
                    $cell = chr(65 + $col) . $row4;
                    $sheet4->setCellValue($cell, $header);
                    $sheet4->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $row4++;

                $noBelum = 1;
                foreach ($groupedProgress['belum_mulai'] as $ptk) {
                    // TENTUKAN TARGET BERDASARKAN JENIS PTK
                    $jenis_ptk = strtolower($ptk->jenis_ptk ?? '');
                    $target = 13; // default untuk guru

                    if (strpos($jenis_ptk, 'kepala') !== false) {
                        $target = 9;
                    } elseif (strpos($jenis_ptk, 'pengawas') !== false) {
                        $target = 9;
                    }

                    $sheet4->setCellValue("A{$row4}", $noBelum);
                    $sheet4->setCellValue("B{$row4}", $ptk->nip ?? '-');
                    $sheet4->setCellValue("C{$row4}", $ptk->nama ?? '-');
                    $sheet4->setCellValue("D{$row4}", $ptk->jenjang_jabatan ?? '-');
                    $sheet4->setCellValue("E{$row4}", $ptk->jenis_ptk ?? '-');
                    $sheet4->setCellValue("F{$row4}", $ptk->nama_sekolah ?? '-');
                    $sheet4->setCellValue("G{$row4}", $ptk->nama_kota ?? '-');
                    $sheet4->setCellValue("H{$row4}", $ptk->instansi ?? '-');
                    $sheet4->setCellValue("I{$row4}", $target);
                    $sheet4->setCellValue("J{$row4}", 'BELUM MULAI');

                    // Progress 0%
                    $sheet4->setCellValue("K{$row4}", '0%');
                    $sheet4->setCellValue("L{$row4}", str_repeat('░', 10)); // Progress bar kosong
                    $sheet4->getStyle("L{$row4}")->getFont()->setColor(new Color('dc3545'));

                    // Styling
                    $bgColor = $row4 % 2 == 0 ? 'FFF0F0' : 'FFE6E6';
                    $sheet4->getStyle("A{$row4}:L{$row4}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);

                    $sheet4->getStyle("I{$row4}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet4->getStyle("I{$row4}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'dc3545']]
                    ]);

                    $row4++;
                    $noBelum++;
                }

                $row4 += 2;
            }

            // ======================
            // SUMMARY STATISTIK - DIPERBAIKI
            // ======================
            $totalPtk = count($progressData) + count($ptkBelumMenjawabData);
            $selesaiCount = count($groupedProgress['selesai']);
            $prosesCount = count($groupedProgress['dalam_proses']);
            $belumCount = count($groupedProgress['belum_mulai']);

            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", 'SUMMARY STATISTIK PROGRESS PTK');
            $sheet4->getStyle("A{$row4}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 13],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a5bb8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            $row4++;

            // Statistik dalam 3 kolom (karena hanya ada 12 kolom total)
            $sheet4->setCellValue("A{$row4}", 'TOTAL PTK');
            $sheet4->setCellValue("B{$row4}", $totalPtk);
            $sheet4->setCellValue("C{$row4}", 'SELESAI (100%)');
            $sheet4->setCellValue("D{$row4}", $selesaiCount);
            $sheet4->setCellValue("E{$row4}", 'DALAM PROSES');
            $sheet4->setCellValue("F{$row4}", $prosesCount);
            $sheet4->setCellValue("G{$row4}", 'BELUM MULAI (0%)');
            $sheet4->setCellValue("H{$row4}", $belumCount);

            // Styling untuk statistik
            for ($col = 0; $col < 8; $col++) {
                $cell = chr(65 + $col) . $row4;
                $sheet4->getStyle($cell)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8F9FA']]
                ]);
            }

            $row4 += 2;

            // FOOTER
            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", 'KETERANGAN:');
            $sheet4->getStyle("A{$row4}")->applyFromArray([
                'font' => ['bold' => true]
            ]);

            $row4++;
            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", '1. SELESAI: Telah menyelesaikan semua sub indikator sesuai target entity');
            $row4++;
            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", '2. DALAM PROSES: Telah mulai mengisi tetapi belum mencapai 100%');
            $row4++;
            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", '3. BELUM MULAI: Sama sekali belum mengisi instrumen');
            $row4++;
            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", '4. Target Guru = 13 Sub Indikator, Kepala Sekolah & Pengawas = 9 Sub Indikator');
            $row4++;
            $sheet4->mergeCells("A{$row4}:L{$row4}");
            $sheet4->setCellValue("A{$row4}", '© ' . date('Y') . ' - Sistem TanpaRagu | Dicetak: ' . now()->format('d F Y H:i:s'));
            $sheet4->getStyle("A{$row4}")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            // Set column widths
            $sheet4->getColumnDimension('A')->setWidth(6);   // No
            $sheet4->getColumnDimension('B')->setWidth(18);  // NIP
            $sheet4->getColumnDimension('C')->setWidth(25);  // Nama
            $sheet4->getColumnDimension('D')->setWidth(15);  // Jenjang Jabatan
            $sheet4->getColumnDimension('E')->setWidth(15);  // Jenis PTK/Entity
            $sheet4->getColumnDimension('F')->setWidth(25);  // Sekolah
            $sheet4->getColumnDimension('G')->setWidth(15);  // Kota
            $sheet4->getColumnDimension('H')->setWidth(20);  // Instansi
            $sheet4->getColumnDimension('I')->setWidth(10);  // Target
            $sheet4->getColumnDimension('J')->setWidth(12);  // Status
            $sheet4->getColumnDimension('K')->setWidth(12);  // Progress
            $sheet4->getColumnDimension('L')->setWidth(15);  // Progress Bar
            // Set active sheet kembali ke sheet 1
            $spreadsheet->setActiveSheetIndex(0);

            // Output file
            $filename = 'analisis-lengkap-' . date('Ymd-His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');

            exit;
        } catch (\Exception $e) {
            \Log::error('Export Excel Lengkap Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }








    // ======================
    // HELPER FUNCTIONS
    // ======================

    private function getDetailHasilInstrumen(Request $request)
    {
        $query = DB::table('ptk_jawaban')
            ->select(
                'ptk.nip',
                'ptk.nama',
                'pangkat_jabatan.jenjang_jabatan',
                'kota.nama_kota',
                'ptk_jawaban.sub_indikator_code',
                'sub_indikator.sub_indikator_name',
                'ptk_jawaban.level as level_dicapai',
                DB::raw('CASE
                WHEN pangkat_jabatan.jenjang_jabatan = "Pertama" THEN 2
                WHEN pangkat_jabatan.jenjang_jabatan = "Muda" THEN 3
                WHEN pangkat_jabatan.jenjang_jabatan = "Madya" THEN 4
                WHEN pangkat_jabatan.jenjang_jabatan = "Utama" THEN 5
                ELSE 2
            END as level_min_wajib'),
                DB::raw('CASE
                WHEN pangkat_jabatan.jenjang_jabatan = "Pertama" THEN 2
                WHEN pangkat_jabatan.jenjang_jabatan = "Muda" THEN 3
                WHEN pangkat_jabatan.jenjang_jabatan = "Madya" THEN 4
                WHEN pangkat_jabatan.jenjang_jabatan = "Utama" THEN 5
                ELSE 2
            END as level_max_wajib')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->where('ptk_jawaban.level', '>=', 1);

        // TERAPKAN FILTER YANG SAMA DENGAN ANALISIS UTAMA
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

        return $query->orderBy('ptk.nama')
            ->orderBy('ptk_jawaban.sub_indikator_code')
            ->get();
    }


    private function getDataPelatihan(Request $request)
    {
        $query = DB::table('ptk_pelatihan')
            ->select(
                'ptk.nip',
                'ptk.nama',
                'pangkat_jabatan.jenjang_jabatan',
                'kota.nama_kota',
                DB::raw('COALESCE(ms_pelatihan.nama_pelatihan, ptk_pelatihan.pelatihan_lainnya) as nama_pelatihan'),
                DB::raw('CASE
                WHEN ptk_pelatihan.ms_pelatihan_id IS NOT NULL THEN "master"
                ELSE "manual"
            END as tipe'),
                DB::raw('(SELECT COUNT(*) FROM ptk_jawaban pj
                 WHERE pj.ptk_id = ptk.ptk_id
                 AND pj.level <
                     CASE
                         WHEN pangkat_jabatan.jenjang_jabatan = "Pertama" THEN 2
                         WHEN pangkat_jabatan.jenjang_jabatan = "Muda" THEN 3
                         WHEN pangkat_jabatan.jenjang_jabatan = "Madya" THEN 4
                         WHEN pangkat_jabatan.jenjang_jabatan = "Utama" THEN 5
                         ELSE 2
                     END) as jumlah_gap')
            )
            ->join('ptk', 'ptk_pelatihan.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('ms_pelatihan', 'ptk_pelatihan.ms_pelatihan_id', '=', 'ms_pelatihan.ms_pelatihan_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id');

        // TERAPKAN FILTER YANG SAMA
        if ($request->filled('kegiatan_id')) {
            $query->where('ptk_pelatihan.kegiatan_id', $request->kegiatan_id);
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

        return $query->orderBy('ptk.nama')->get();
    }

    private function getKeteranganStatus($status)
    {
        $keterangan = [
            'Memenuhi' => 'Sudah mencapai atau melampaui level minimal yang diwajibkan untuk jenjang jabatan',
            'Mendekati' => 'Hanya 1 level di bawah level minimal wajib, perlu sedikit peningkatan',
            'Perlu Peningkatan' => 'Masih jauh dari level minimal yang diwajibkan, perlu pelatihan intensif'
        ];

        return $keterangan[$status] ?? '-';
    }

    private function getDetailPelatihanPTK(Request $request)
    {
        $query = DB::table('ptk_pelatihan')
            ->select(
                'ptk.nip',
                'ptk.nama',
                'pangkat_jabatan.jenjang_jabatan',
                'kota.nama_kota',
                DB::raw('COALESCE(ms_pelatihan.nama_pelatihan, ptk_pelatihan.pelatihan_lainnya) as nama_pelatihan')
            )
            ->join('ptk', 'ptk_pelatihan.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('ms_pelatihan', 'ptk_pelatihan.ms_pelatihan_id', '=', 'ms_pelatihan.ms_pelatihan_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id');

        // Terapkan filter yang sama
        if ($request->filled('kegiatan_id')) {
            $query->where('ptk_pelatihan.kegiatan_id', $request->kegiatan_id);
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

        return $query->orderBy('ptk.nama')->get();
    }

















    private function getDetailHasilInstrumenExcel(Request $request)
    {
        $query = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.ptk_jawaban_id',
                'ptk_jawaban.tahap',
                'ptk_jawaban.level as level_dicapai',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.bobot',
                'ptk_jawaban.created_at',
                'ptk.ptk_id',
                'ptk.nama',
                'ptk.nip',
                'ptk.pangkat_jabatan_id',
                'ptk.jenis_ptk_id',
                'ptk.instansi',
                'ptk.sekolah_id',
                'ptk.kota_id',
                // Ambil data dari tabel pangkat_jabatan
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                // Ambil data dari tabel sekolah
                'sekolah.nama_sekolah',
                'sekolah.npsn',
                // Ambil data dari tabel kota
                'kota.nama_kota',
                // Ambil data dari tabel sub_indikator
                'sub_indikator.sub_indikator_name',
                'kegiatan.kegiatan_name',
                'kegiatan.entity',
                'kegiatan.kegiatan_id',
                // Ambil data jenis_ptk
                'jenis_ptk.jenis_ptk'
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
            ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id')
            ->where('ptk_jawaban.level', '>=', 1);

        // TERAPKAN FILTER YANG SAMA DENGAN ANALISIS UTAMA
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

        return $query->orderBy('ptk.nip')
            ->orderBy('ptk_jawaban.sub_indikator_code')
            ->get();
    }

    private function getPelatihanByPtkExcel($ptkId, $kegiatanId)
    {
        return DB::table('ptk_pelatihan')
            ->select(
                'ptk_pelatihan.*',
                'ms_pelatihan.nama_pelatihan',
                // Tentukan nama pelatihan lengkap berdasarkan ms_pelatihan_id atau pelatihan_lainnya
                DB::raw("CASE
                WHEN ptk_pelatihan.ms_pelatihan_id IS NOT NULL AND ptk_pelatihan.ms_pelatihan_id != 0 THEN ms_pelatihan.nama_pelatihan
                WHEN ptk_pelatihan.pelatihan_lainnya IS NOT NULL AND ptk_pelatihan.pelatihan_lainnya != '' THEN ptk_pelatihan.pelatihan_lainnya
                ELSE 'Belum Tersedia'
            END as nama_pelatihan_lengkap"),
                // Tentukan kategori pelatihan
                DB::raw("CASE
                WHEN ptk_pelatihan.ms_pelatihan_id IS NOT NULL AND ptk_pelatihan.ms_pelatihan_id != 0 THEN 'Dari Daftar'
                WHEN ptk_pelatihan.pelatihan_lainnya IS NOT NULL AND ptk_pelatihan.pelatihan_lainnya != '' THEN 'Lainnya'
                ELSE 'Belum Tersedia'
            END as kategori_pelatihan")
            )
            ->leftJoin('ms_pelatihan', 'ptk_pelatihan.ms_pelatihan_id', '=', 'ms_pelatihan.ms_pelatihan_id')
            ->where('ptk_pelatihan.ptk_id', $ptkId)
            ->where('ptk_pelatihan.kegiatan_id', $kegiatanId)
            ->get();
    }

    private function getRekomendasiWithGapExcel($jenjangJabatan, $levelJawaban, $subIndikatorId, $tahap, $entity, $subIndikatorCode)
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
                    'gap' => $gap, // negatif atau 0
                    'status' => $gap < 0 ? 'melampaui' : 'tepat'
                ];
            } else {
                // Belum dicapai (GAP)
                $rekomendasiGap[] = [
                    'level' => $rek->level,
                    'rekomendasi' => $rek->rekomendasi,
                    'gap' => $gap, // positif
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
            'rekomendasi_gap' => $rekomendasiGap, // Yang belum dicapai
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
     * Helper function untuk mendapatkan target sub indikator berdasarkan entity
     */
    private function getEntityTarget($entity)
    {
        $entity = strtolower($entity ?? '');

        if (strpos($entity, 'guru') !== false) {
            return 13;
        } elseif (strpos($entity, 'kepala') !== false || strpos($entity, 'pengawas') !== false) {
            return 9;
        }

        return 0; // default jika tidak diketahui
    }





    private function getRekomendasiGapPerJenjang(Request $request)
    {
        // Tentukan target level per jenjang dan level minimal yang harus dicapai
        $targetLevels = [
            'Pertama' => ['min' => 2, 'max' => 2, 'target' => 2],  // Hanya level 2
            'Muda'    => ['min' => 2, 'max' => 3, 'target' => 3],  // Level 2-3
            'Madya'   => ['min' => 2, 'max' => 4, 'target' => 4],  // Level 2-4
            'Utama'   => ['min' => 2, 'max' => 5, 'target' => 5]   // Level 2-5
        ];

        // FILTER PERTAMA: Ambil jenjang-jenjang yang ada di data berdasarkan filter
        $jenjangQuery = DB::table('ptk_jawaban')
            ->select('pangkat_jabatan.jenjang_jabatan')
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->where('ptk_jawaban.level', '>=', 1)
            ->whereNotNull('pangkat_jabatan.jenjang_jabatan');

        // Terapkan filter yang sama dengan analisis utama
        if ($request->filled('kegiatan_id')) {
            $jenjangQuery->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        }
        if ($request->filled('pangkat_jabatan_id')) {
            $pangkat = DB::table('pangkat_jabatan')
                ->where('pangkat_jabatan_id', $request->pangkat_jabatan_id)
                ->first();
            if ($pangkat && $pangkat->jenjang_jabatan) {
                $jenjangQuery->where('pangkat_jabatan.jenjang_jabatan', $pangkat->jenjang_jabatan);
            }
        }
        if ($request->filled('kota_id')) {
            $jenjangQuery->where('ptk.kota_id', $request->kota_id);
        }
        if ($request->filled('jenis_ptk_id')) {
            $jenjangQuery->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        }
        if ($request->filled('jenjang_pendidikan_id')) {
            $jenjangQuery->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
        }
        if ($request->filled('bentuk_pendidikan')) {
            $jenjangQuery->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
        }
        if ($request->filled('jenis_kelamin')) {
            $jenjangQuery->where('ptk.jenis_kelamin', $request->jenis_kelamin);
        }

        $jenjangList = $jenjangQuery
            ->groupBy('pangkat_jabatan.jenjang_jabatan')
            ->pluck('jenjang_jabatan')
            ->toArray();

        if (empty($jenjangList)) {
            return [];
        }


        // URUTKAN JENJANG SESUAI URUTAN YANG DIINGINKAN
        $sortedJenjangList = [];
        $order = ['Pertama', 'Muda', 'Madya', 'Utama'];

        foreach ($order as $jenjang) {
            if (in_array($jenjang, $jenjangList)) {
                $sortedJenjangList[] = $jenjang;
            }
        }


        $result = [];

        foreach ($sortedJenjangList  as $jenjang) {
            // Pastikan jenjang ini ada dalam $targetLevels
            if (!isset($targetLevels[$jenjang])) {
                continue;
            }

            $levelMin = $targetLevels[$jenjang]['min'];
            $levelMax = $targetLevels[$jenjang]['max'];
            $levelTarget = $targetLevels[$jenjang]['target'];

            // QUERY UNTUK MENDAPATKAN DATA DENGAN JOIN TABEL REKOMENDASI
            $dataQuery = DB::table('ptk_jawaban')
                ->select(
                    'ptk.ptk_id',
                    'ptk.nama',
                    'jenis_ptk.jenis_ptk',
                    'pangkat_jabatan.jenjang_jabatan',
                    DB::raw('COALESCE(pangkat_jabatan.level_kompetensi, ' . $levelTarget . ') as level_kompetensi'),
                    'ptk_jawaban.sub_indikator_id',
                    'ptk_jawaban.sub_indikator_code',
                    'ptk_jawaban.level',
                    'ptk_jawaban.tahap',
                    'kegiatan.entity',
                    'ptk_jawaban_rekomendasi.level_gap',
                    'ptk_rekomendasi.rekomendasi',
                    'sub_indikator.sub_indikator_name'
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
                ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id')
                ->leftJoin('ptk_jawaban_rekomendasi', function ($join) {
                    $join->on('ptk_jawaban.ptk_id', '=', 'ptk_jawaban_rekomendasi.ptk_id')
                        ->on('ptk_jawaban.sub_indikator_id', '=', 'ptk_jawaban_rekomendasi.sub_indikator_id');
                })
                ->leftJoin('ptk_rekomendasi', function ($join) {
                    $join->on('ptk_jawaban_rekomendasi.sub_indikator_id', '=', 'ptk_rekomendasi.sub_indikator_id')
                        ->on('ptk_jawaban_rekomendasi.level_gap', '=', 'ptk_rekomendasi.level');
                })
                ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
                ->where('pangkat_jabatan.jenjang_jabatan', $jenjang)
                ->where('ptk_jawaban.level', '>=', 1);

            // Terapkan filter yang sama
            if ($request->filled('kegiatan_id')) {
                $dataQuery->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            }
            if ($request->filled('kota_id')) {
                $dataQuery->where('ptk.kota_id', $request->kota_id);
            }
            if ($request->filled('jenis_ptk_id')) {
                $dataQuery->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            }
            if ($request->filled('jenjang_pendidikan_id')) {
                $dataQuery->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            }
            if ($request->filled('bentuk_pendidikan')) {
                $dataQuery->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            }
            if ($request->filled('jenis_kelamin')) {
                $dataQuery->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            }

            $data = $dataQuery->get();

            if ($data->isEmpty()) {
                continue;
            }

            // HITUNG TOTAL PTK UNIK PER JENJANG
            $totalPtk = $data->groupBy('ptk_id')->count();

            // KELOMPOKKAN PER SUB INDIKATOR
            $groupedBySubIndikator = $data->groupBy('sub_indikator_id');
            $rekomendasiData = [];

            foreach ($groupedBySubIndikator as $subIndikatorId => $subData) {
                $firstData = $subData->first();

                // HITUNG PTK UNIK PER SUB INDIKATOR
                $ptkIdsPerSubIndikator = $subData->pluck('ptk_id')->unique();
                $totalPtkSubIndikator = $ptkIdsPerSubIndikator->count();

                // KELOMPOKKAN PTK PER LEVEL DICAPAI
                $ptkByLevelDicapai = [];

                foreach ($ptkIdsPerSubIndikator as $ptkId) {
                    $ptkRecords = $subData->where('ptk_id', $ptkId);
                    $levelDicapai = $ptkRecords->first()->level ?? 1;

                    if (!isset($ptkByLevelDicapai[$levelDicapai])) {
                        $ptkByLevelDicapai[$levelDicapai] = [];
                    }

                    $ptkByLevelDicapai[$levelDicapai][] = $ptkId;
                }

                // HITUNG GAP UNTUK SETIAP LEVEL DICAPAI
                $detailGap = [];

                foreach ($ptkByLevelDicapai as $levelDicapai => $ptkIds) {
                    $jumlahPtkLevel = count($ptkIds);

                    // TENTUKAN LEVEL YANG HARUS DICAPAI (GAP)
                    for ($levelHarus = $levelDicapai + 1; $levelHarus <= $levelTarget; $levelHarus++) {
                        if ($levelHarus >= $levelMin && $levelHarus <= $levelTarget) {
                            // CARI REKOMENDASI DARI DATABASE
                            $rekomendasiDariDB = null;
                            foreach ($subData as $record) {
                                if ($record->level_gap == $levelHarus && !empty($record->rekomendasi)) {
                                    $rekomendasiDariDB = $record->rekomendasi;
                                    break;
                                }
                            }

                            // JIKA TIDAK ADA DI DB, GUNAKAN FUNGSI getRekomendasiText
                            $rekomendasiText = $rekomendasiDariDB ?? $this->getRekomendasiText(
                                $subIndikatorId,
                                $firstData->sub_indikator_code,
                                $firstData->tahap ?? '',
                                $firstData->entity ?? '',
                                $levelDicapai,
                                $levelHarus
                            );

                            $detailGap[] = [
                                'level_dicapai' => $levelDicapai,
                                'level_harus' => $levelHarus,
                                'level_gap' => $levelHarus - $levelDicapai,
                                'rekomendasi' => $rekomendasiText,
                                'jumlah_ptk' => $jumlahPtkLevel
                            ];
                        }
                    }
                }

                // URUTKAN BERDASARKAN LEVEL DICAPAI DAN LEVEL HARUS
                usort($detailGap, function ($a, $b) {
                    if ($a['level_dicapai'] == $b['level_dicapai']) {
                        return $a['level_harus'] - $b['level_harus'];
                    }
                    return $a['level_dicapai'] - $b['level_dicapai'];
                });

                // HANYA TAMBAHKAN JIKA ADA GAP
                if (!empty($detailGap)) {
                    $rekomendasiData[] = [
                        'sub_indikator_id' => $subIndikatorId,
                        'sub_indikator_code' => $firstData->sub_indikator_code,
                        'sub_indikator_name' => $firstData->sub_indikator_name,
                        'detail_gap' => $detailGap,
                        'total_ptk_sub_indikator' => $totalPtkSubIndikator
                    ];
                }
            }

            // HANYA TAMBAHKAN JIKA ADA REKOMENDASI GAP
            if (!empty($rekomendasiData)) {
                $result[] = [
                    'jenjang_jabatan' => $jenjang,
                    'level_min' => $levelMin,
                    'level_max' => $levelMax,
                    'level_target' => $levelTarget,
                    'level_kompetensi' => $data->first()->level_kompetensi ?? $levelTarget,
                    'total_ptk' => $totalPtk,
                    'rekomendasi' => $rekomendasiData
                ];
            }
        }

        return $result;
    }

    private function getRekomendasiText($subIndikatorId, $subIndikatorCode, $tahap, $entity, $levelDicapai, $levelTarget)
    {
        // Coba ambil rekomendasi spesifik dari database terlebih dahulu
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

        // Jika tidak ada rekomendasi spesifik, cari yang umum
        $rekomendasi = DB::table('ptk_rekomendasi')
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('sub_indikator_code', $subIndikatorCode)
            ->where('level', $levelTarget)
            ->first();

        if ($rekomendasi) {
            return $rekomendasi->rekomendasi;
        }

        // Jika masih tidak ada, buat rekomendasi dinamis
        $gap = $levelTarget - $levelDicapai;

        $levelNames = [
            1 => 'Dasar',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan'
        ];

        $levelDicapaiName = $levelNames[$levelDicapai] ?? "Level $levelDicapai";
        $levelTargetName = $levelNames[$levelTarget] ?? "Level $levelTarget";

        if ($gap == 1) {
            return "Perlu meningkatkan dari $levelDicapaiName ke $levelTargetName (naik 1 level)";
        } else {
            return "Perlu meningkatkan dari $levelDicapaiName ke $levelTargetName (naik $gap level)";
        }
    }





    /**
     * Mendapatkan distribusi level terendah per PTK (SEMUA PTK yang menjawab)
     * Menghitung level TERENDAH dari semua jawaban PTK
     */
    private function getLevelTerendahPerPtk(Request $request)
    {
        // Query untuk mendapatkan level terendah setiap PTK
        $query = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.ptk_id',
                'ptk.nama',
                'pangkat_jabatan.jenjang_jabatan',
                DB::raw('MIN(ptk_jawaban.level) as level_terendah'),
                DB::raw('COUNT(DISTINCT ptk_jawaban.sub_indikator_id) as jumlah_sub_indikator')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->where('ptk_jawaban.level', '>=', 1) // INCLUDE LEVEL 1
            ->groupBy('ptk_jawaban.ptk_id', 'ptk.nama', 'pangkat_jabatan.jenjang_jabatan');

        // Terapkan filter yang sama
        if ($request->filled('kegiatan_id')) {
            $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        }
        if ($request->filled('pangkat_jabatan_id')) {
            $query->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
        }
        if ($request->filled('jenis_ptk_id')) {
            $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
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

        $data = $query->get();

        // Debug: Tampilkan sample data
        \Log::info('Data level terendah per PTK:', [
            'total_ptk' => $data->count(),
            'sample_data' => $data->take(3)->toArray()
        ]);

        // Hitung distribusi per level
        $distribusi = [];
        $detailPerLevel = []; // Untuk menyimpan detail PTK per level

        for ($level = 1; $level <= 5; $level++) {
            $distribusi[$level] = 0;
            $detailPerLevel[$level] = [];
        }

        foreach ($data as $item) {
            if ($item->level_terendah >= 1 && $item->level_terendah <= 5) {
                $distribusi[$item->level_terendah]++;

                // Simpan detail PTK untuk level ini
                $detailPerLevel[$item->level_terendah][] = [
                    'nama' => $item->nama,
                    'jenjang_jabatan' => $item->jenjang_jabatan,
                    'jumlah_sub_indikator' => $item->jumlah_sub_indikator
                ];
            }
        }

        return [
            'labels' => ['Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5'],
            'data' => array_values($distribusi),
            'total_ptk' => $data->count(),
            'detail_per_level' => $detailPerLevel,
            'interpretasi' => [
                1 => 'PTK memiliki minimal 1 jawaban di Level 1 (Dasar)',
                2 => 'PTK memiliki minimal 1 jawaban di Level 2 (Penerapan)',
                3 => 'PTK memiliki minimal 1 jawaban di Level 3 (Analisis)',
                4 => 'PTK memiliki minimal 1 jawaban di Level 4 (Evaluasi)',
                5 => 'PTK SEMUA jawaban di Level 5 (Pembimbingan) - Kompeten Tinggi'
            ]
        ];
    }


    private function getLevelkotaPerPtk(Request $request)
    {
        // Query untuk mendapatkan level terendah setiap PTK
        $query = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.ptk_id',
                'ptk.nama',
                'pangkat_jabatan.jenjang_jabatan',
                DB::raw('MIN(ptk_jawaban.level) as level_terendah'),
                DB::raw('COUNT(DISTINCT ptk_jawaban.sub_indikator_id) as jumlah_sub_indikator')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->where('ptk_jawaban.level', '>=', 1) // INCLUDE LEVEL 1
            ->groupBy('ptk_jawaban.ptk_id', 'ptk.nama', 'pangkat_jabatan.jenjang_jabatan');

        // Terapkan filter yang sama
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

        $data = $query->get();

        // Debug: Tampilkan sample data
        \Log::info('Data level terendah per PTK:', [
            'total_ptk' => $data->count(),
            'sample_data' => $data->take(3)->toArray()
        ]);

        // Hitung distribusi per level
        $distribusi = [];
        $detailPerLevel = []; // Untuk menyimpan detail PTK per level

        for ($level = 1; $level <= 5; $level++) {
            $distribusi[$level] = 0;
            $detailPerLevel[$level] = [];
        }

        foreach ($data as $item) {
            if ($item->level_terendah >= 1 && $item->level_terendah <= 5) {
                $distribusi[$item->level_terendah]++;

                // Simpan detail PTK untuk level ini
                $detailPerLevel[$item->level_terendah][] = [
                    'nama' => $item->nama,
                    'jenjang_jabatan' => $item->jenjang_jabatan,
                    'jumlah_sub_indikator' => $item->jumlah_sub_indikator
                ];
            }
        }

        return [
            'labels' => ['Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5'],
            'data' => array_values($distribusi),
            'total_ptk' => $data->count(),
            'detail_per_level' => $detailPerLevel,
            'interpretasi' => [
                1 => 'PTK memiliki minimal 1 jawaban di Level 1 (Dasar)',
                2 => 'PTK memiliki minimal 1 jawaban di Level 2 (Penerapan)',
                3 => 'PTK memiliki minimal 1 jawaban di Level 3 (Analisis)',
                4 => 'PTK memiliki minimal 1 jawaban di Level 4 (Evaluasi)',
                5 => 'PTK SEMUA jawaban di Level 5 (Pembimbingan) - Kompeten Tinggi'
            ]
        ];
    }



    /**
     * Mendapatkan distribusi level per kota (LAYERED BAR)
     * Menghitung berapa banyak PTK yang memiliki level tersebut sebagai LEVEL TERENDAH di kota tersebut
     */
    private function getDistribusiLevelPerKota(Request $request)
    {
        // Langkah 1: Dapatkan level terendah per PTK per kota
        $queryLevelTerendah = DB::table('ptk_jawaban')
            ->select(
                'ptk.ptk_id',
                'kota.nama_kota',
                DB::raw('MIN(ptk_jawaban.level) as level_terendah')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->where('ptk_jawaban.level', '>=', 1)
            ->whereNotNull('kota.nama_kota')
            ->groupBy('ptk.ptk_id', 'kota.nama_kota');

        // Terapkan filter yang sama
        if ($request->filled('kegiatan_id')) {
            $queryLevelTerendah->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        }
        if ($request->filled('pangkat_jabatan_id')) {
            $queryLevelTerendah->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
        }
        if ($request->filled('jenis_ptk_id')) {
            $queryLevelTerendah->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        }
        if ($request->filled('jenjang_pendidikan_id')) {
            $queryLevelTerendah->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
        }
        if ($request->filled('bentuk_pendidikan')) {
            $queryLevelTerendah->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
        }
        if ($request->filled('jenis_kelamin')) {
            $queryLevelTerendah->where('ptk.jenis_kelamin', $request->jenis_kelamin);
        }

        $dataLevelTerendah = $queryLevelTerendah->get();

        // Debug: Hitung total PTK
        $totalPtk = $dataLevelTerendah->count();
        \Log::info('Total PTK di distribusi kota: ' . $totalPtk);
        \Log::info('Sample data: ', $dataLevelTerendah->take(3)->toArray());

        // Langkah 2: Hitung distribusi per kota per level
        $kotaList = $dataLevelTerendah->pluck('nama_kota')->unique()->values()->toArray();

        // Batasi jumlah kota maksimal 10 agar chart tidak terlalu padat
        if (count($kotaList) > 10) {
            $kotaList = array_slice($kotaList, 0, 10);
        }

        // Inisialisasi array untuk menyimpan data
        $distribusiPerKota = [];

        foreach ($kotaList as $kota) {
            $distribusiPerKota[$kota] = [
                1 => 0, // Level 1
                2 => 0, // Level 2
                3 => 0, // Level 3
                4 => 0, // Level 4
                5 => 0  // Level 5
            ];
        }

        // Hitung jumlah PTK per kota per level
        foreach ($dataLevelTerendah as $item) {
            $kota = $item->nama_kota;
            $level = $item->level_terendah;

            if (in_array($kota, $kotaList) && $level >= 1 && $level <= 5) {
                $distribusiPerKota[$kota][$level]++;
            }
        }

        // Langkah 3: Format data untuk Chart.js (stacked bar)
        $datasets = [];
        $levelColors = [
            1 => 'rgba(220, 53, 69, 0.8)',    // Level 1: merah
            2 => 'rgba(255, 193, 7, 0.8)',    // Level 2: kuning
            3 => 'rgba(23, 162, 184, 0.8)',   // Level 3: biru muda
            4 => 'rgba(0, 123, 255, 0.8)',    // Level 4: biru
            5 => 'rgba(40, 167, 69, 0.8)'     // Level 5: hijau
        ];

        $levelNames = [
            1 => 'Dasar',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan'
        ];

        // Buat dataset untuk setiap level (1-5)
        for ($level = 1; $level <= 5; $level++) {
            $levelData = [];

            foreach ($kotaList as $kota) {
                $levelData[] = $distribusiPerKota[$kota][$level] ?? 0;
            }

            $datasets[] = [
                'label' => 'Level ' . $level . ' (' . $levelNames[$level] . ')',
                'data' => $levelData,
                'backgroundColor' => $levelColors[$level],
                'borderColor' => str_replace('0.8', '1', $levelColors[$level]),
                'borderWidth' => 1,
                'stack' => 'kota' // Ini membuat bar stacked
            ];
        }

        // Hitung total PTK per kota untuk informasi
        $totalPerKota = [];
        foreach ($kotaList as $kota) {
            $totalPerKota[$kota] = array_sum($distribusiPerKota[$kota]);
        }

        return [
            'labels' => $kotaList,
            'datasets' => $datasets,
            'total_kota' => count($kotaList),
            'total_ptk' => $totalPtk,
            'distribusi_detail' => $distribusiPerKota,
            'total_per_kota' => $totalPerKota,
            'interpretasi' => 'Menunjukkan level terendah yang dicapai PTK per kota'
        ];
    }

    // Helper function untuk nama level
    private function getLevelName($level)
    {
        $names = [
            1 => 'Dasar',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan'
        ];
        return $names[$level] ?? 'Unknown';
    }











    private function getPersentaseLevelPerJenjang(Request $request)
    {
        try {
            $targetJenjang = [
                'Pertama' => 2,
                'Muda'    => 3,
                'Madya'   => 4,
                'Utama'   => 5
            ];

            // Ambil daftar jenjang yang ada
            $jenjangQuery = DB::table('ptk_jawaban')
                ->select('pangkat_jabatan.jenjang_jabatan')
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
                ->whereNotNull('ptk_jawaban.level_kalkulasi')
                ->whereNotNull('pangkat_jabatan.jenjang_jabatan');

            if ($request->filled('kegiatan_id'))           $jenjangQuery->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            if ($request->filled('pangkat_jabatan_id'))    $jenjangQuery->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
            if ($request->filled('jenis_ptk_id'))          $jenjangQuery->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            if ($request->filled('kota_id'))               $jenjangQuery->where('ptk.kota_id', $request->kota_id);
            if ($request->filled('jenjang_pendidikan_id')) $jenjangQuery->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            if ($request->filled('bentuk_pendidikan'))     $jenjangQuery->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            if ($request->filled('jenis_kelamin'))         $jenjangQuery->where('ptk.jenis_kelamin', $request->jenis_kelamin);

            $jenjangList = $jenjangQuery
                ->groupBy('pangkat_jabatan.jenjang_jabatan')
                ->pluck('jenjang_jabatan')
                ->toArray();

            if (empty($jenjangList)) return [];

            // URUTKAN JENJANG SESUAI URUTAN YANG DIINGINKAN
            $sortedJenjangList = [];
            $order = ['Pertama', 'Muda', 'Madya', 'Utama'];

            foreach ($order as $jenjang) {
                if (in_array($jenjang, $jenjangList)) {
                    $sortedJenjangList[] = $jenjang;
                }
            }

            $result = [];

            foreach ($sortedJenjangList as $jenjang) {
                if (!isset($targetJenjang[$jenjang])) continue;

                $targetLevel = $targetJenjang[$jenjang];

                // Hitung SUM(level_kalkulasi) per PTK
                $query = DB::table('ptk_jawaban')
                    ->select(
                        'ptk.ptk_id',
                        'kegiatan.entity',
                        DB::raw('SUM(ptk_jawaban.level_kalkulasi) AS sum_level_kalkulasi')
                    )
                    ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                    ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
                    ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                    ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                    ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
                    ->where('pangkat_jabatan.jenjang_jabatan', $jenjang)
                    ->whereNotNull('ptk_jawaban.level_kalkulasi');

                if ($request->filled('kegiatan_id'))           $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
                if ($request->filled('kota_id'))               $query->where('ptk.kota_id', $request->kota_id);
                if ($request->filled('jenis_ptk_id'))          $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
                if ($request->filled('jenjang_pendidikan_id')) $query->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
                if ($request->filled('bentuk_pendidikan'))     $query->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
                if ($request->filled('jenis_kelamin'))         $query->where('ptk.jenis_kelamin', $request->jenis_kelamin);

                $data = $query
                    ->groupBy('ptk.ptk_id', 'kegiatan.entity')
                    ->get();

                if ($data->isEmpty()) continue;

                // Hitung persentase asli per PTK, lalu buat distribusi frekuensi
                // Key = persentase dibulatkan 2 desimal, Value = jumlah PTK
                $distribusi = [];

                foreach ($data as $ptk) {
                    $entity = strtolower($ptk->entity ?? '');

                    // Pembagi: 9 untuk KS/Pengawas, 13 untuk Guru
                    if (strpos($entity, 'kepala') !== false || strpos($entity, 'pengawas') !== false) {
                        $pembagi = 9;
                    } else {
                        $pembagi = 13;
                    }

                    $sumKalkulasi = (float) $ptk->sum_level_kalkulasi;

                    // RUMUS: SUM(level_kalkulasi) / pembagi
                    // level_kalkulasi sudah dalam bentuk persen (misal 97.436154)
                    // jadi hasilnya langsung persentase
                    $persentase = $pembagi > 0 ? $sumKalkulasi / $pembagi : 0;

                    // Cap max 100%
                    $persentase = min($persentase, 100.0);

                    // Bulatkan ke 2 desimal sebagai key distribusi
                    $key = number_format($persentase, 2, '.', '');

                    if (!isset($distribusi[$key])) {
                        $distribusi[$key] = 0;
                    }
                    $distribusi[$key]++;
                }

                // Urutkan berdasarkan nilai persentase (ascending)
                uksort($distribusi, function ($a, $b) {
                    return (float)$a <=> (float)$b;
                });

                $labels     = array_keys($distribusi);     // ["67.44", "83.21", "97.44", ...]
                $dataValues = array_values($distribusi);   // [2, 1, 3, ...]

                // Warna per bar berdasarkan nilai persentase
                $backgroundColors = array_map(function ($pct) {
                    $p = (float)$pct;
                    if ($p >= 90)      return 'rgba(21, 128, 61, 0.85)';
                    elseif ($p >= 70)  return 'rgba(34, 197, 94, 0.85)';
                    elseif ($p >= 50)  return 'rgba(163, 230, 53, 0.85)';
                    elseif ($p >= 30)  return 'rgba(251, 191, 36, 0.85)';
                    else               return 'rgba(220, 53, 69, 0.85)';
                }, $labels);

                $allPersentase = array_map('floatval', $labels);
                $rataPersentase = count($allPersentase) > 0
                    ? array_sum(array_map(fn($k, $v) => (float)$k * $v, $labels, $dataValues))
                    / array_sum($dataValues)
                    : 0;

                $result[] = [
                    'jenjang_jabatan'  => $jenjang,
                    'jumlah_ptk'       => count($data),
                    'rata_persentase'  => round($rataPersentase, 2),
                    'target_level'     => $targetLevel,
                    'chart_data'       => [
                        'labels'          => $labels,          // nilai % asli
                        'data'            => $dataValues,      // jumlah PTK
                        'backgroundColor' => $backgroundColors,
                    ],
                ];
            }

            return $result;
        } catch (\Exception $e) {
            \Log::error('Error getPersentaseLevelPerJenjang: ' . $e->getMessage());
            return [];
        }
    }












    /**
     * Ambil data progress PTK dari database (DENGAN INSTANSI & NO HP)
     */
    private function getPtkProgressData(Request $request)
    {
        // Query untuk menghitung jumlah sub indikator per PTK
        $query = DB::table('ptk_jawaban')
            ->select(
                'ptk.ptk_id',
                'ptk.nip',
                'ptk.nama',
                'ptk.instansi', // TAMBAHKAN INSTANSI
                'ptk.no_hp',    // TAMBAHKAN NO HP
                'pangkat_jabatan.jenjang_jabatan',
                'kegiatan.entity',
                'sekolah.nama_sekolah',
                'kota.nama_kota',
                DB::raw('COUNT(DISTINCT ptk_jawaban.sub_indikator_id) as jumlah_sub_indikator')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->where('ptk_jawaban.level', '>=', 1)
            ->groupBy(
                'ptk.ptk_id',
                'ptk.nip',
                'ptk.nama',
                'ptk.instansi',  // TAMBAHKAN KE GROUP BY
                'ptk.no_hp',     // TAMBAHKAN KE GROUP BY
                'pangkat_jabatan.jenjang_jabatan',
                'kegiatan.entity',
                'sekolah.nama_sekolah',
                'kota.nama_kota'
            );

        // TERAPKAN FILTER YANG SAMA DENGAN ANALISIS UTAMA
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

        return $query->orderBy('kegiatan.entity')
            ->orderBy('ptk.nama')
            ->get();
    }
}
