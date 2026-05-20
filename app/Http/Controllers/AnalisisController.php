<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

        $jenisPtkQuery = DB::table('jenis_ptk');

        if ($request->filled('kegiatan_id')) {
            $kegiatan = DB::table('kegiatan')
                ->where('kegiatan_id', $request->kegiatan_id)
                ->first();

            if ($kegiatan && !empty($kegiatan->entity)) {
                $entity = strtolower($kegiatan->entity);

                $jenisPtkQuery->where(function ($q) use ($entity) {
                    if (strpos($entity, 'guru') !== false) {
                        $q->where('jenis_ptk', 'LIKE', '%Guru%');
                    }
                    if (strpos($entity, 'kepala sekolah') !== false) {
                        $q->orWhere('jenis_ptk', 'LIKE', '%Kepala Sekolah%');
                    }
                    if (strpos($entity, 'pengawas') !== false) {
                        $q->orWhere('jenis_ptk', 'LIKE', '%Pengawas%');
                    }
                });
            }
        }

        $jenisPtkList = $jenisPtkQuery->get();

        //modified
        switch (Auth::user()->role_id) {
            case '1':
            case '2':
            case '3':
                $jenjangPendidikanList = DB::table('jenjang_pendidikan')
                    ->select('jenjang_pendidikan_id', 'jenjang_pendidikan')
                    ->whereNotNull('jenjang_pendidikan')
                    ->distinct()
                    ->orderBy('jenjang_pendidikan')
                    ->get();
            case '7': //provinsi
                $kotas = DB::table('kota')->orderBy('nama_kota')->get();
                $jenjangPendidikanList = DB::table('jenjang_pendidikan')
                    ->select('jenjang_pendidikan_id', 'jenjang_pendidikan')
                    ->whereNotNull('jenjang_pendidikan')
                    ->whereIn('jenjang_pendidikan', ['SKH', 'SMA', 'SMK'])
                    ->distinct()
                    ->orderBy('jenjang_pendidikan')
                    ->get();
                break;
            case '6': //kota
                $kotas = DB::table('kota')->where('nama_kota', Auth::user()->kab_kota)->get();
                $jenjangPendidikanList = DB::table('jenjang_pendidikan')
                    ->select('jenjang_pendidikan_id', 'jenjang_pendidikan')
                    ->whereNotNull('jenjang_pendidikan')
                    ->whereIn('jenjang_pendidikan', ['TK/PAUD', 'SD', 'SMP'])
                    ->distinct()
                    ->orderBy('jenjang_pendidikan')
                    ->get();
                break;
            default:
                # code...
                break;
        }


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
            'jenis_kelamin',
            'npsn'
        ])) {
            try {
                // Validasi kompatibilitas kegiatan dan jenis_ptk (hanya jika keduanya diisi)
                if ($request->filled('kegiatan_id') && $request->filled('jenis_ptk_id')) {
                    $isCompatible = $this->validateJenisPtkWithKegiatan(
                        $request->kegiatan_id,
                        $request->jenis_ptk_id
                    );

                    if (!$isCompatible) {
                        if ($request->ajax()) {
                            return response()->json([
                                'error' => 'Filter tidak valid: Kegiatan dan Jenis PTK tidak kompatibel'
                            ], 422);
                        }

                        return redirect()
                            ->route('analisis.index')
                            ->with('error', 'Kegiatan dan Jenis PTK yang dipilih tidak kompatibel. Silakan pilih filter yang sesuai.');
                    }
                }

                $analisisData = $this->getAnalisisData($request);

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

    // =========================================================================
    // HELPER: Terapkan filter sekolah (bentuk_pendidikan / npsn) via whereExists
    // Digunakan oleh query yang belum join ke tabel sekolah
    // =========================================================================
    private function applySekolahFilter($query, Request $request, string $ptkAlias = 'ptk')
    {
        $needSekolah = $request->filled('bentuk_pendidikan') || $request->filled('npsn');
        if (!$needSekolah) return $query;

        $query->whereExists(function ($q) use ($request, $ptkAlias) {
            $q->select(DB::raw(1))
                ->from('sekolah')
                ->whereColumn("sekolah.sekolah_id", "{$ptkAlias}.sekolah_id");

            if ($request->filled('bentuk_pendidikan')) {
                $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            }
            if ($request->filled('npsn')) {
                $q->where('sekolah.npsn', $request->npsn);
            }
        });

        return $query;
    }

    private function getAnalisisData(Request $request)
    {
        // ========================================================
        // QUERY UNTUK DISTRIBUSI PTK (BUKAN BERDASARKAN JAWABAN)
        // ========================================================

        $ptkYangSudahMenjawabQuery = DB::table('ptk_jawaban')
            ->select('ptk_id')
            ->when($request->filled('kegiatan_id'), function ($q) use ($request) {
                $q->where('kegiatan_id', $request->kegiatan_id);
            })
            ->where('level', '>=', 1)
            ->where('tahap', 2)
            ->groupBy('ptk_id');

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
            })
            // FIX: filter NPSN — sekolah sudah di-join, tinggal tambahkan where
            ->when($request->filled('npsn'), function ($q) use ($request) {
                $q->where('sekolah.npsn', $request->npsn);
            });

        $ptkIds = $ptkQuery->pluck('ptk.ptk_id');

        // ========================================================
        // QUERY UNTUK LEVEL DISTRIBUTION (BERDASARKAN JAWABAN)
        // ========================================================

        $jawabanData = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.level',
                DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) as total')
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->when($request->filled('kegiatan_id'), fn($q) => $q->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id))
            ->when($request->filled('jenis_ptk_id'), fn($q) => $q->where('ptk.jenis_ptk_id', $request->jenis_ptk_id))
            ->groupBy('ptk_jawaban.level')
            ->get();

        // ========================================================
        // HITUNG STATISTIK
        // ========================================================
        $statistik = $this->getStatistik($request, $ptkIds, $jawabanData);

        // ========================================================
        // DISTRIBUSI BERDASARKAN PTK (BUKAN JAWABAN)
        // ========================================================

        $jenjangDistribution = DB::table('ptk')
            ->select(
                'pangkat_jabatan.jenjang_jabatan',
                DB::raw('COUNT(DISTINCT ptk.ptk_id) as count')
            )
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->whereIn('ptk.ptk_id', $ptkIds)
            ->groupBy('pangkat_jabatan.jenjang_jabatan')
            ->get();

        $bentukPendidikanDistribution = DB::table('ptk')
            ->select(
                'sekolah.bentuk_pendidikan',
                DB::raw('COUNT(DISTINCT ptk.ptk_id) as count')
            )
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->whereIn('ptk.ptk_id', $ptkIds)
            ->groupBy('sekolah.bentuk_pendidikan')
            ->get();

        $jenjangPendidikanDistribution = DB::table('ptk')
            ->select(
                'jenjang_pendidikan.jenjang_pendidikan',
                DB::raw('COUNT(DISTINCT ptk.ptk_id) as count')
            )
            ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
            ->whereIn('ptk.ptk_id', $ptkIds)
            ->whereNotNull('ptk.jenjang_pendidikan_id')
            ->whereNotNull('jenjang_pendidikan.jenjang_pendidikan')
            ->groupBy('jenjang_pendidikan.jenjang_pendidikan')
            ->get();

        $jenisKelaminDistribution = DB::table('ptk')
            ->select(
                'ptk.jenis_kelamin',
                DB::raw('COUNT(DISTINCT ptk.ptk_id) as count')
            )
            ->whereIn('ptk.ptk_id', $ptkIds)
            ->groupBy('ptk.jenis_kelamin')
            ->get();

        // ========================================================
        // DATA UNTUK CHART SUB INDIKATOR (DARI ptk_jawaban)
        // ========================================================

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

        // FIX: Semua filter sub indikator harus konsisten pakai leftJoin sekolah
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
            // FIX: NPSN pakai kolom dari join yang sudah ada
            ->when($request->filled('npsn'), function ($q) use ($request) {
                $q->where('sekolah.npsn', $request->npsn);
            })
            ->whereNotNull('ptk_jawaban.sub_indikator_id')
            ->whereNotNull('ptk_jawaban.sub_indikator_code')
            ->where('ptk_jawaban.level', '>=', 1)
            ->groupBy('ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level')
            ->orderBy('ptk_jawaban.sub_indikator_code')
            ->orderBy('ptk_jawaban.level');

        $subIndikatorData = $subIndikatorQuery->get();

        $allSubIndikatorsChart = $this->getAllSubIndikatorsChartData($semuaSubIndikator, $subIndikatorData);

        // ========================================================
        // MODUS PER KOTA
        // ========================================================

        $totalJawabanPerKotaQuery = DB::table('ptk_jawaban')
            ->select(
                'kota.nama_kota',
                DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) as total_jawaban')
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
            ->when($request->filled('npsn'), function ($q) use ($request) {
                $q->where('sekolah.npsn', $request->npsn);
            })
            ->where('ptk_jawaban.level', '>=', 1)
            ->groupBy('kota.nama_kota');

        $totalJawabanPerKota = $totalJawabanPerKotaQuery->get()
            ->pluck('total_jawaban', 'nama_kota')
            ->toArray();

        $modusKotaQuery = DB::table('ptk_jawaban')
            ->select(
                'kota.nama_kota',
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.level',
                DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) as jumlah_jawaban')
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
            ->when($request->filled('npsn'), function ($q) use ($request) {
                $q->where('sekolah.npsn', $request->npsn);
            })
            ->whereNotNull('ptk_jawaban.sub_indikator_id')
            ->whereNotNull('ptk_jawaban.sub_indikator_code')
            ->where('ptk_jawaban.level', '>=', 1)
            ->groupBy('kota.nama_kota', 'ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level')
            ->orderBy('kota.nama_kota')
            ->orderBy('ptk_jawaban.sub_indikator_code');

        $modusKotaData = $modusKotaQuery->get();

        $modusPerKota = $this->getModusPerKota($modusKotaData, $semuaSubIndikator, $totalJawabanPerKota, $request);
        $subIndikatorPerJenjang = $this->getSubIndikatorPerJenjang($request, $semuaSubIndikator);
        $subIndikatorPerJenjangPendidikan = $this->getSubIndikatorPerJenjangPendidikan($request, $semuaSubIndikator);

        $progressKota = $this->getProgressKota($request);
        $pelatihanData = $this->getPelatihanData($request);
        $rekomendasiGapPerJenjang = $this->getRekomendasiGapPerJenjang($request);
        $ptkBelumMenjawab = $this->getPtkBelumMenjawab($request);
        $rataRataLevelPerJenjangProvinsi = $this->getRataRataLevelPerJenjangProvinsi($request);
        $rataRataLevelPerJenjangKota = $this->getRataRataLevelPerJenjangKota($request);
        $persentaseLevelPerJenjang = $this->getPersentaseLevelPerJenjang($request);

        return [
            'statistik' => $statistik,
            'rata_rata_level_provinsi' => $rataRataLevelPerJenjangProvinsi,
            'rata_rata_level_kota' => $rataRataLevelPerJenjangKota,
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
        if (!$request->filled('kegiatan_id')) {
            return [
                'total_ptk' => 0,
                'ptk_menjawab' => 0,
                'ptk_belum_menjawab' => 0,
                'persentase_isi' => 0
            ];
        }

        // ============================================================
        // 1. HITUNG PTK YANG SUDAH MENJAWAB
        // ============================================================
        $ptkMenjawabQuery = DB::table('ptk_jawaban')
            ->where('kegiatan_id', $request->kegiatan_id)
            ->distinct('ptk_id');

        $hasFilter = $request->filled('pangkat_jabatan_id')
            || $request->filled('jenis_ptk_id')
            || $request->filled('kota_id')
            || $request->filled('jenjang_pendidikan_id')
            || $request->filled('bentuk_pendidikan')
            || $request->filled('jenis_kelamin')
            || $request->filled('npsn'); // FIX: tambahkan npsn

        if ($hasFilter) {
            $ptkMenjawabQuery->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                    ->from('ptk')
                    ->whereColumn('ptk.ptk_id', 'ptk_jawaban.ptk_id');

                if ($request->filled('pangkat_jabatan_id')) {
                    $q->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
                }
                if ($request->filled('jenis_ptk_id')) {
                    $q->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
                }
                if ($request->filled('kota_id')) {
                    $q->where('ptk.kota_id', $request->kota_id);
                }
                if ($request->filled('jenjang_pendidikan_id')) {
                    $q->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
                }
                if ($request->filled('jenis_kelamin')) {
                    $q->where('ptk.jenis_kelamin', $request->jenis_kelamin);
                }
                // FIX: bentuk_pendidikan dan npsn pakai satu join sekolah
                if ($request->filled('bentuk_pendidikan') || $request->filled('npsn')) {
                    $q->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id');
                    if ($request->filled('bentuk_pendidikan')) {
                        $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
                    }
                    if ($request->filled('npsn')) {
                        $q->where('sekolah.npsn', $request->npsn);
                    }
                }
            });
        }

        $ptkMenjawab = $ptkMenjawabQuery->count('ptk_id');

        // ============================================================
        // 2. HITUNG TOTAL PTK
        // ============================================================
        $totalPtkQuery = DB::table('ptk');

        // FIX: Join sekolah sekali saja jika ada filter bentuk_pendidikan atau npsn
        if ($request->filled('bentuk_pendidikan') || $request->filled('npsn')) {
            $totalPtkQuery->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id');
        }

        if ($request->filled('jenjang_pendidikan_id')) {
            $totalPtkQuery->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id');
        }

        if ($request->filled('pangkat_jabatan_id')) {
            $totalPtkQuery->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
        }
        if ($request->filled('jenis_ptk_id')) {
            $totalPtkQuery->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        }
        if ($request->filled('kota_id')) {
            $totalPtkQuery->where('ptk.kota_id', $request->kota_id);
        }
        if ($request->filled('jenjang_pendidikan_id')) {
            $totalPtkQuery->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
        }
        if ($request->filled('bentuk_pendidikan')) {
            $totalPtkQuery->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
        }
        if ($request->filled('jenis_kelamin')) {
            $totalPtkQuery->where('ptk.jenis_kelamin', $request->jenis_kelamin);
        }
        if ($request->filled('npsn')) {
            $totalPtkQuery->where('sekolah.npsn', $request->npsn);
        }

        $totalPtk = $totalPtkQuery->count();

        // ============================================================
        // 3. HITUNG DAN KEMBALIKAN HASIL
        // ============================================================
        $ptkBelumMenjawab = $totalPtk - $ptkMenjawab;
        $persentaseIsi = $totalPtk > 0 ? round(($ptkMenjawab / $totalPtk) * 100, 1) : 0;

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

        $chartData = [
            'labels' => $subIndikators->pluck('sub_indikator_code')->toArray(),
            'datasets' => []
        ];

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

        foreach ($levels as $level) {
            $dataPerLevel = [];

            foreach ($subIndikators as $subIndikator) {
                $data = $subIndikatorData
                    ->where('sub_indikator_id', $subIndikator['sub_indikator_id'])
                    ->where('level', $level)
                    ->first();

                $dataPerLevel[] = $data ? $data->ptk_count : 0;
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

    private function getPtkBelumMenjawab(Request $request)
    {
        try {
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
                ->when($request->filled('npsn'), function ($q) use ($request) {
                    $q->where('sekolah.npsn', $request->npsn);
                })
                ->groupBy('ptk_jawaban.ptk_id');

            $ptkSudahMenjawab = $ptkSudahMenjawabQuery->pluck('ptk_id')->toArray();

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
            // FIX: sekolah sudah di-join di atas, langsung where
            if ($request->filled('npsn')) {
                $query->where('sekolah.npsn', $request->npsn);
            }

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

            $result = $query->orderBy('ptk.nama')
                ->limit(100)
                ->get();

            return $result;
        } catch (\Exception $e) {
            \Log::error('Error getPtkBelumMenjawab: ' . $e->getMessage());
            return collect();
        }
    }

    private function getProgressKota(Request $request)
    {
        try {
            if (!$request->filled('kegiatan_id')) {
                return collect();
            }

            $kotas = DB::table('kota')->select('kota_id', 'nama_kota')->get();

            if ($kotas->isEmpty()) {
                return collect();
            }

            $ptkQuery = DB::table('ptk')
                ->select('ptk_id', 'kota_id')
                ->whereNotNull('nip');

            if ($request->filled('pangkat_jabatan_id')) {
                $ptkQuery->where('pangkat_jabatan_id', $request->pangkat_jabatan_id);
            }
            if ($request->filled('jenis_ptk_id')) {
                $ptkQuery->where('jenis_ptk_id', $request->jenis_ptk_id);
            }
            if ($request->filled('kota_id')) {
                $ptkQuery->where('kota_id', $request->kota_id);
            }
            if ($request->filled('jenjang_pendidikan_id')) {
                $ptkQuery->where('jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            }
            if ($request->filled('jenis_kelamin')) {
                $ptkQuery->where('jenis_kelamin', $request->jenis_kelamin);
            }
            // FIX: bentuk_pendidikan dan npsn pakai satu whereExists
            if ($request->filled('bentuk_pendidikan') || $request->filled('npsn')) {
                $ptkQuery->whereExists(function ($q) use ($request) {
                    $q->select(DB::raw(1))
                        ->from('sekolah')
                        ->whereColumn('sekolah.sekolah_id', 'ptk.sekolah_id');

                    if ($request->filled('bentuk_pendidikan')) {
                        $q->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
                    }
                    if ($request->filled('npsn')) {
                        $q->where('sekolah.npsn', $request->npsn);
                    }
                });
            }

            $ptkList = $ptkQuery->get();

            if ($ptkList->isEmpty()) {
                return collect();
            }

            $sudahMenjawabIds = DB::table('ptk_jawaban')
                ->where('kegiatan_id', $request->kegiatan_id)
                ->distinct()
                ->pluck('ptk_id')
                ->toArray();

            $sudahMenjawabSet = array_flip($sudahMenjawabIds);

            $kotaStats = [];
            foreach ($kotas as $kota) {
                $kotaStats[$kota->kota_id] = [
                    'nama_kota' => $kota->nama_kota,
                    'total_ptk' => 0,
                    'sudah_isi' => 0
                ];
            }

            foreach ($ptkList as $ptk) {
                $kotaId = $ptk->kota_id;
                if (!isset($kotaStats[$kotaId])) {
                    continue;
                }

                $kotaStats[$kotaId]['total_ptk']++;

                if (isset($sudahMenjawabSet[$ptk->ptk_id])) {
                    $kotaStats[$kotaId]['sudah_isi']++;
                }
            }

            $result = collect();
            foreach ($kotaStats as $kotaId => $stats) {
                if ($stats['total_ptk'] == 0) {
                    continue;
                }

                $persentase = round(($stats['sudah_isi'] / $stats['total_ptk']) * 100, 1);

                $result->push((object)[
                    'nama_kota' => $stats['nama_kota'],
                    'total_ptk' => $stats['total_ptk'],
                    'sudah_isi' => $stats['sudah_isi'],
                    'persentase' => $persentase
                ]);
            }

            return $result->sortByDesc('persentase')->take(10)->values();
        } catch (\Exception $e) {
            \Log::error('Error getProgressKota: ' . $e->getMessage());
            return collect();
        }
    }

    private function getModusPerKota($modusKotaData, $semuaSubIndikator, $totalJawabanPerKota, $request)
    {
        if ($modusKotaData->isEmpty() || $semuaSubIndikator->isEmpty()) {
            return [];
        }

        $subIndikatorMap = [];
        foreach ($semuaSubIndikator as $sub) {
            $subIndikatorMap[$sub->sub_indikator_id] = [
                'code' => $sub->sub_indikator_code,
                'name' => $sub->sub_indikator_name
            ];
        }

        if (!$request->filled('kota_id')) {
            $groupedBySubIndikator = $modusKotaData->groupBy(['sub_indikator_id']);

            $combinedResult = [];
            $totalCombined = array_sum($totalJawabanPerKota);

            foreach ($groupedBySubIndikator as $subIndikatorId => $dataPerSub) {
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

        $groupedByKota = $modusKotaData->groupBy(['nama_kota', 'sub_indikator_id']);
        $result = [];

        foreach ($groupedByKota as $namaKota => $subIndikators) {
            $kotaModus = [
                'nama_kota' => $namaKota ?: 'Tidak Diketahui',
                'sub_indikator_modus' => [],
                'total_jawaban' => $totalJawabanPerKota[$namaKota] ?? 0
            ];

            foreach ($subIndikators as $subIndikatorId => $levels) {
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

            if (empty($kotaModus['sub_indikator_modus'])) {
                continue;
            }

            usort($kotaModus['sub_indikator_modus'], function ($a, $b) {
                return $b['jumlah_jawaban'] - $a['jumlah_jawaban'];
            });

            $result[] = $kotaModus;
        }

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

        $allSubIndikators = $semuaSubIndikator;

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
            ->when($request->filled('npsn'), function ($q) use ($request) {
                $q->where('sekolah.npsn', $request->npsn);
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

        $sortedJenjangList = [];
        $order = ['Pertama', 'Muda', 'Madya', 'Utama'];

        foreach ($order as $jenjang) {
            if (in_array($jenjang, $jenjangList)) {
                $sortedJenjangList[] = $jenjang;
            }
        }

        $result = [];

        foreach ($sortedJenjangList as $jenjang) {
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
                ->when($request->filled('npsn'), function ($q) use ($request) {
                    $q->where('sekolah.npsn', $request->npsn);
                })
                ->whereNotNull('ptk_jawaban.sub_indikator_id')
                ->whereNotNull('ptk_jawaban.sub_indikator_code')
                ->where('ptk_jawaban.level', '>=', 1)
                ->where('pangkat_jabatan.jenjang_jabatan', $jenjang)
                ->groupBy('ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level')
                ->orderBy('ptk_jawaban.sub_indikator_code');

            $dataPerJenjang = $perJenjangQuery->get();

            $allLabels = [];
            $mappingData = [];

            foreach ($allSubIndikators as $sub) {
                $label = $sub->sub_indikator_code;
                $allLabels[] = $label;

                for ($level = 1; $level <= 5; $level++) {
                    $mappingData[$label][$level] = 0;
                }
            }

            foreach ($dataPerJenjang as $data) {
                $label = $data->sub_indikator_code;
                $level = $data->level;
                if (isset($mappingData[$label][$level])) {
                    $mappingData[$label][$level] = $data->ptk_count;
                }
            }

            $jenjangData = [
                'jenjang_jabatan' => $jenjang,
                'labels' => $allLabels,
                'datasets' => []
            ];

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
            ->when($request->filled('npsn'), function ($q) use ($request) {
                $q->where('sekolah.npsn', $request->npsn);
            })
            ->whereNotNull('ptk_jawaban.sub_indikator_id')
            ->whereNotNull('ptk_jawaban.sub_indikator_code')
            ->where('ptk_jawaban.level', '>=', 1)
            ->whereNotNull('jenjang_pendidikan.jenjang_pendidikan')
            ->groupBy('jenjang_pendidikan.jenjang_pendidikan', 'ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.level')
            ->orderBy('jenjang_pendidikan.jenjang_pendidikan')
            ->orderBy('ptk_jawaban.sub_indikator_code');

        $dataPerJenjangPendidikan = $perJenjangPendidikanQuery->get();

        if ($dataPerJenjangPendidikan->isEmpty() || $semuaSubIndikator->isEmpty()) {
            return [];
        }

        $jenjangPendidikanList = $dataPerJenjangPendidikan->pluck('jenjang_pendidikan')->unique()->values();
        $limitedSubIndikators = $semuaSubIndikator->take(10);

        $result = [];

        foreach ($jenjangPendidikanList as $jenjangPendidikan) {
            $jenjangPendidikanData = [
                'jenjang_pendidikan' => $jenjangPendidikan,
                'labels' => $limitedSubIndikators->pluck('sub_indikator_code')->toArray(),
                'datasets' => []
            ];

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

            foreach ($levels as $level) {
                $dataPerLevel = [];

                foreach ($limitedSubIndikators as $subIndikator) {
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

            if (array_sum(array_merge(...array_column($jenjangPendidikanData['datasets'], 'data'))) > 0) {
                $result[] = $jenjangPendidikanData;
            }
        }

        return $result;
    }

    private function getPelatihanData(Request $request)
    {
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
            ->when($request->filled('npsn'), function ($q) use ($request) {
                $q->where('sekolah.npsn', $request->npsn);
            })
            ->whereNotNull('ms_pelatihan.nama_pelatihan')
            ->groupBy('ms_pelatihan.nama_pelatihan');

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
            ->when($request->filled('npsn'), function ($q) use ($request) {
                $q->where('sekolah.npsn', $request->npsn);
            })
            ->whereNotNull('pelatihan_lainnya')
            ->where('pelatihan_lainnya', '!=', '')
            ->groupBy(DB::raw('TRIM(pelatihan_lainnya)'))
            ->union($pelatihanFromMaster);

        $data = DB::query()->fromSub($pelatihanLainnya, 'combined')
            ->select('nama_pelatihan', 'jumlah_ptk', 'tipe')
            ->orderByDesc('jumlah_ptk')
            ->limit(15)
            ->get();

        $groupedData = collect();

        foreach ($data as $item) {
            $nama = trim($item->nama_pelatihan);
            $existing = $groupedData->firstWhere('nama_pelatihan', $nama);

            if ($existing) {
                $existing->jumlah_ptk += (int) $item->jumlah_ptk;
            } else {
                $groupedData->push((object)[
                    'nama_pelatihan' => $nama,
                    'jumlah_ptk' => (int) $item->jumlah_ptk,
                    'tipe' => $item->tipe
                ]);
            }
        }

        return $groupedData->sortByDesc('jumlah_ptk')->values();
    }

    private function getRekomendasiGapPerJenjang(Request $request)
    {
        $targetLevels = [
            'Pertama' => ['min' => 2, 'max' => 2, 'target' => 2],
            'Muda'    => ['min' => 2, 'max' => 3, 'target' => 3],
            'Madya'   => ['min' => 2, 'max' => 4, 'target' => 4],
            'Utama'   => ['min' => 2, 'max' => 5, 'target' => 5],
        ];

        $jenjangQuery = DB::table('ptk_jawaban')
            ->select('pangkat_jabatan.jenjang_jabatan')
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
            ->where('ptk_jawaban.level', '>=', 1)
            ->whereNotNull('pangkat_jabatan.jenjang_jabatan');

        if ($request->filled('kegiatan_id')) $jenjangQuery->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        if ($request->filled('pangkat_jabatan_id')) {
            $pangkat = DB::table('pangkat_jabatan')->where('pangkat_jabatan_id', $request->pangkat_jabatan_id)->first();
            if ($pangkat && $pangkat->jenjang_jabatan)
                $jenjangQuery->where('pangkat_jabatan.jenjang_jabatan', $pangkat->jenjang_jabatan);
        }
        if ($request->filled('kota_id')) $jenjangQuery->where('ptk.kota_id', $request->kota_id);
        if ($request->filled('jenis_ptk_id')) $jenjangQuery->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        if ($request->filled('jenjang_pendidikan_id')) $jenjangQuery->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
        if ($request->filled('bentuk_pendidikan')) $jenjangQuery->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
        if ($request->filled('jenis_kelamin')) $jenjangQuery->where('ptk.jenis_kelamin', $request->jenis_kelamin);
        if ($request->filled('npsn')) $jenjangQuery->where('sekolah.npsn', $request->npsn);

        $jenjangList = $jenjangQuery->groupBy('pangkat_jabatan.jenjang_jabatan')->pluck('jenjang_jabatan')->toArray();
        if (empty($jenjangList)) return [];

        $sortedJenjangList = [];
        foreach (['Pertama', 'Muda', 'Madya', 'Utama'] as $j) {
            if (in_array($j, $jenjangList)) $sortedJenjangList[] = $j;
        }

        $result = [];

        foreach ($sortedJenjangList as $jenjang) {
            if (!isset($targetLevels[$jenjang])) continue;

            $levelMin    = $targetLevels[$jenjang]['min'];
            $levelMax    = $targetLevels[$jenjang]['max'];
            $levelTarget = $targetLevels[$jenjang]['target'];

            $maxLevelPerPtk = DB::table('ptk_jawaban')
                ->select(
                    'ptk_jawaban.ptk_id',
                    'ptk_jawaban.sub_indikator_id',
                    'ptk_jawaban.sub_indikator_code',
                    'ptk_jawaban.tahap',
                    'kegiatan.entity',
                    'sub_indikator.sub_indikator_name',
                    DB::raw('MAX(ptk_jawaban.level) as level_tertinggi')
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
                ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->where('pangkat_jabatan.jenjang_jabatan', $jenjang)
                ->where('ptk_jawaban.level', '>=', 1)
                ->groupBy('ptk_jawaban.ptk_id', 'ptk_jawaban.sub_indikator_id', 'ptk_jawaban.sub_indikator_code', 'ptk_jawaban.tahap', 'kegiatan.entity', 'sub_indikator.sub_indikator_name');

            if ($request->filled('kegiatan_id'))           $maxLevelPerPtk->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            if ($request->filled('kota_id'))               $maxLevelPerPtk->where('ptk.kota_id', $request->kota_id);
            if ($request->filled('jenis_ptk_id'))          $maxLevelPerPtk->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            if ($request->filled('jenjang_pendidikan_id')) $maxLevelPerPtk->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            if ($request->filled('bentuk_pendidikan'))     $maxLevelPerPtk->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            if ($request->filled('jenis_kelamin'))         $maxLevelPerPtk->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            if ($request->filled('npsn'))                  $maxLevelPerPtk->where('sekolah.npsn', $request->npsn);

            $ringkasanData = $maxLevelPerPtk->get();

            if ($ringkasanData->isEmpty()) continue;

            $rekomendasiRekomendasi = DB::table('ptk_jawaban_rekomendasi')
                ->select(
                    'ptk_jawaban_rekomendasi.ptk_id',
                    'ptk_jawaban_rekomendasi.sub_indikator_id',
                    'ptk_jawaban_rekomendasi.level_gap',
                    'ptk_rekomendasi.rekomendasi'
                )
                ->leftJoin('ptk_rekomendasi', function ($join) {
                    $join->on('ptk_jawaban_rekomendasi.sub_indikator_id', '=', 'ptk_rekomendasi.sub_indikator_id')
                        ->on('ptk_jawaban_rekomendasi.level_gap', '=', 'ptk_rekomendasi.level')
                        ->on('ptk_jawaban_rekomendasi.sub_indikator_code', '=', 'ptk_rekomendasi.sub_indikator_code');
                })
                ->when($request->filled('kegiatan_id'), function ($q) use ($request) {
                    $q->where('ptk_jawaban_rekomendasi.kegiatan_id', $request->kegiatan_id);
                });

            $rekomendasiMap = $rekomendasiRekomendasi->get()
                ->groupBy(function ($item) {
                    return $item->ptk_id . '_' . $item->sub_indikator_id;
                })
                ->map(function ($items) {
                    return $items->keyBy('level_gap')->toArray();
                });

            $totalPtk = $ringkasanData->groupBy('ptk_id')->count();
            $groupedBySubIndikator = $ringkasanData->groupBy('sub_indikator_id');
            $rekomendasiData = [];

            foreach ($groupedBySubIndikator as $subIndikatorId => $subData) {
                $firstData = $subData->first();
                $ptkIdsPerSubIndikator = $subData->pluck('ptk_id')->unique();
                $totalPtkSubIndikator = $ptkIdsPerSubIndikator->count();

                $ptkByLevelDicapai = [];
                foreach ($subData as $item) {
                    $levelDicapai = $item->level_tertinggi;
                    if (!isset($ptkByLevelDicapai[$levelDicapai])) {
                        $ptkByLevelDicapai[$levelDicapai] = [];
                    }
                    $ptkByLevelDicapai[$levelDicapai][] = $item->ptk_id;
                }

                $detailGap = [];
                $key = $firstData->ptk_id . '_' . $subIndikatorId;

                foreach ($ptkByLevelDicapai as $levelDicapai => $ptkIds) {
                    $jumlahPtkLevel = count($ptkIds);

                    for ($levelHarus = $levelDicapai + 1; $levelHarus <= $levelTarget; $levelHarus++) {
                        if ($levelHarus >= $levelMin && $levelHarus <= $levelTarget) {
                            $rekomendasiText = null;
                            if (isset($rekomendasiMap[$key][$levelHarus])) {
                                $rekomendasiText = $rekomendasiMap[$key][$levelHarus]->rekomendasi;
                            }

                            if (!$rekomendasiText) {
                                $rekomendasiText = $this->getRekomendasiText(
                                    $subIndikatorId,
                                    $firstData->sub_indikator_code ?? '',
                                    $firstData->tahap ?? '',
                                    $firstData->entity ?? '',
                                    $levelDicapai,
                                    $levelHarus
                                );
                            }

                            $detailGap[] = [
                                'level_dicapai' => $levelDicapai,
                                'level_harus'   => $levelHarus,
                                'level_gap'     => $levelHarus - $levelDicapai,
                                'rekomendasi'   => $rekomendasiText,
                                'jumlah_ptk'    => $jumlahPtkLevel,
                            ];
                        }
                    }
                }

                usort($detailGap, function ($a, $b) {
                    if ($a['level_dicapai'] != $b['level_dicapai'])
                        return $a['level_dicapai'] - $b['level_dicapai'];
                    return $a['level_harus'] - $b['level_harus'];
                });

                $uniqueGaps = [];
                foreach ($detailGap as $gap) {
                    $gapKey = $gap['level_dicapai'] . '_' . $gap['level_gap'];
                    if (!isset($uniqueGaps[$gapKey])) {
                        $uniqueGaps[$gapKey] = $gap;
                    }
                }
                $detailGap = array_values($uniqueGaps);

                if (!empty($detailGap)) {
                    $rekomendasiData[] = [
                        'sub_indikator_id'        => $subIndikatorId,
                        'sub_indikator_code'       => $firstData->sub_indikator_code ?? '',
                        'sub_indikator_name'       => $firstData->sub_indikator_name ?? 'Sub Indikator ' . $subIndikatorId,
                        'detail_gap'               => $detailGap,
                        'total_ptk_sub_indikator'  => $totalPtkSubIndikator,
                    ];
                }
            }

            if (!empty($rekomendasiData)) {
                $result[] = [
                    'jenjang_jabatan'  => $jenjang,
                    'level_min'        => $levelMin,
                    'level_max'        => $levelMax,
                    'level_target'     => $levelTarget,
                    'level_kompetensi' => $levelTarget,
                    'total_ptk'        => $totalPtk,
                    'rekomendasi'      => $rekomendasiData,
                ];
            }

            unset($ringkasanData, $groupedBySubIndikator, $rekomendasiData, $rekomendasiMap);
            gc_collect_cycles();
        }

        return $result;
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

        $gap = $levelTarget - $levelDicapai;

        $levelNames = [
            1 => 'Dasar',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan'
        ];

        $levelDicapaiName = $levelNames[$levelDicapai] ?? "Level $levelDicapai";
        $levelTargetName  = $levelNames[$levelTarget]  ?? "Level $levelTarget";

        if ($gap == 1) {
            return "Perlu meningkatkan dari $levelDicapaiName ke $levelTargetName (naik 1 level)";
        } else {
            return "Perlu meningkatkan dari $levelDicapaiName ke $levelTargetName (naik $gap level)";
        }
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
            if ($request->filled('npsn'))                  $jenjangQuery->where('sekolah.npsn', $request->npsn);

            $jenjangList = $jenjangQuery
                ->groupBy('pangkat_jabatan.jenjang_jabatan')
                ->pluck('jenjang_jabatan')
                ->toArray();

            if (empty($jenjangList)) return [];

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
                if ($request->filled('npsn'))                  $query->where('sekolah.npsn', $request->npsn);

                $data = $query
                    ->groupBy('ptk.ptk_id', 'kegiatan.entity')
                    ->get();

                if ($data->isEmpty()) continue;

                $kelompokLabels = ['0-50%', '51-80%', '81-99%', '100%'];
                $kelompokData   = [0, 0, 0, 0];

                $kelompokColors = [
                    'rgba(220, 53, 69, 0.85)',
                    'rgba(255, 193, 7, 0.85)',
                    'rgba(23, 162, 184, 0.85)',
                    'rgba(40, 167, 69, 0.85)'
                ];

                foreach ($data as $ptk) {
                    $entity = strtolower($ptk->entity ?? '');

                    if (strpos($entity, 'kepala sekolah') !== false || strpos($entity, 'pengawas') !== false) {
                        $pembagi = 9;
                    } else {
                        $pembagi = 13;
                    }

                    $sumKalkulasi = (float) $ptk->sum_level_kalkulasi;
                    $persentase   = $pembagi > 0 ? $sumKalkulasi / $pembagi : 0;
                    $persentase   = min($persentase, 100.0);

                    if ($persentase >= 100) {
                        $kelompokData[3]++;
                    } elseif ($persentase >= 81) {
                        $kelompokData[2]++;
                    } elseif ($persentase >= 51) {
                        $kelompokData[1]++;
                    } else {
                        $kelompokData[0]++;
                    }
                }

                $rataPersentase = $data->count() > 0
                    ? array_sum(array_map(function ($ptk) {
                        $entity  = strtolower($ptk->entity ?? '');
                        $pembagi = (strpos($entity, 'kepala sekolah') !== false || strpos($entity, 'pengawas') !== false) ? 9 : 13;
                        return $pembagi > 0 ? min((float)$ptk->sum_level_kalkulasi / $pembagi, 100) : 0;
                    }, iterator_to_array($data))) / $data->count()
                    : 0;

                $result[] = [
                    'jenjang_jabatan'  => $jenjang,
                    'jumlah_ptk'       => $data->count(),
                    'rata_persentase'  => round($rataPersentase, 2),
                    'target_level'     => $targetLevel,
                    'chart_data'       => [
                        'labels'          => $kelompokLabels,
                        'data'            => $kelompokData,
                        'backgroundColor' => $kelompokColors,
                        'borderColor'     => array_map(fn($c) => str_replace('0.85', '1', $c), $kelompokColors),
                    ],
                    'statistik' => [
                        '0_50'  => $kelompokData[0],
                        '51_80' => $kelompokData[1],
                        '81_99' => $kelompokData[2],
                        '100'   => $kelompokData[3]
                    ]
                ];
            }

            return $result;
        } catch (\Exception $e) {
            \Log::error('Error getPersentaseLevelPerJenjang: ' . $e->getMessage());
            return [];
        }
    }

    private function getRataRataLevelPerJenjangProvinsi(Request $request)
    {
        try {
            $query = DB::table('ptk_jawaban')
                ->select(
                    'pangkat_jabatan.jenjang_jabatan',
                    DB::raw('ROUND(AVG(ptk_jawaban.level_kalkulasi), 2) AS rata_rata_level'),
                    DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) AS jumlah_ptk'),
                    DB::raw('COUNT(ptk_jawaban.ptk_jawaban_id) AS total_jawaban')
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                // FIX: Selalu join sekolah (leftJoin aman), filter NPSN & bentuk_pendidikan
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
                ->whereNotNull('ptk_jawaban.level_kalkulasi')
                ->whereNotNull('pangkat_jabatan.jenjang_jabatan');

            if ($request->filled('kegiatan_id'))           $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            if ($request->filled('pangkat_jabatan_id'))    $query->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
            if ($request->filled('jenis_ptk_id'))          $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            if ($request->filled('jenis_kelamin'))         $query->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            if ($request->filled('bentuk_pendidikan'))     $query->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            if ($request->filled('npsn'))                  $query->where('sekolah.npsn', $request->npsn);
            if ($request->filled('jenjang_pendidikan_id')) $query->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);

            $data = $query->groupBy('pangkat_jabatan.jenjang_jabatan')
                ->orderByRaw("FIELD(pangkat_jabatan.jenjang_jabatan, 'Pertama', 'Muda', 'Madya', 'Utama')")
                ->get();

            $jenjangList    = ['Pertama', 'Muda', 'Madya', 'Utama'];
            $backgroundColors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4'];
            $labels         = [];
            $dataValues     = [];
            $detailPerJenjang = [];

            foreach ($jenjangList as $jenjang) {
                $labels[] = $jenjang;

                $row = $data->where('jenjang_jabatan', $jenjang)->first();

                if ($row) {
                    $dataValues[] = (float) $row->rata_rata_level;
                    $detailPerJenjang[$jenjang] = [
                        'jumlah_ptk'    => (int) $row->jumlah_ptk,
                        'total_jawaban' => (int) $row->total_jawaban,
                        'rata_rata'     => (float) $row->rata_rata_level
                    ];
                } else {
                    $dataValues[] = 0;
                    $detailPerJenjang[$jenjang] = [
                        'jumlah_ptk'    => 0,
                        'total_jawaban' => 0,
                        'rata_rata'     => 0
                    ];
                }
            }

            return [
                'labels'           => $labels,
                'data'             => $dataValues,
                'backgroundColors' => $backgroundColors,
                'detail_per_jenjang' => $detailPerJenjang,
            ];
        } catch (\Exception $e) {
            \Log::error('Error getRataRataLevelPerJenjangProvinsi: ' . $e->getMessage());

            return [
                'labels'           => ['Pertama', 'Muda', 'Madya', 'Utama'],
                'data'             => [0, 0, 0, 0],
                'backgroundColors' => ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4'],
                'detail_per_jenjang' => [],
                'error'            => $e->getMessage()
            ];
        }
    }

    private function getRataRataLevelPerJenjangKota(Request $request)
    {
        try {
            $query = DB::table('ptk_jawaban')
                ->select(
                    'pangkat_jabatan.jenjang_jabatan',
                    'kota.nama_kota',
                    DB::raw('ROUND(AVG(ptk_jawaban.level_kalkulasi), 2) AS rata_rata_level'),
                    DB::raw('COUNT(DISTINCT ptk_jawaban.ptk_id) AS jumlah_ptk'),
                    DB::raw('COUNT(ptk_jawaban.ptk_jawaban_id) AS total_jawaban')
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->join('kota', 'ptk.kota_id', '=', 'kota.kota_id')
                // FIX: Selalu leftJoin sekolah, jenjang_pendidikan
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
                ->whereNotNull('ptk_jawaban.level_kalkulasi')
                ->whereNotNull('pangkat_jabatan.jenjang_jabatan')
                ->whereNotNull('kota.nama_kota');

            if ($request->filled('kegiatan_id'))           $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            if ($request->filled('pangkat_jabatan_id'))    $query->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
            if ($request->filled('jenis_ptk_id'))          $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            if ($request->filled('kota_id'))               $query->where('ptk.kota_id', $request->kota_id);
            if ($request->filled('jenis_kelamin'))         $query->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            if ($request->filled('bentuk_pendidikan'))     $query->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            if ($request->filled('npsn'))                  $query->where('sekolah.npsn', $request->npsn);
            if ($request->filled('jenjang_pendidikan_id')) $query->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);

            $data = $query->groupBy('kota.nama_kota', 'pangkat_jabatan.jenjang_jabatan')
                ->orderBy('kota.nama_kota')
                ->orderByRaw("FIELD(pangkat_jabatan.jenjang_jabatan, 'Pertama', 'Muda', 'Madya', 'Utama')")
                ->get();

            if ($data->isEmpty()) {
                return ['labels' => [], 'datasets' => []];
            }

            $kotaList = $data->pluck('nama_kota')->unique()->values()->take(10)->toArray();

            $jenjangColors = [
                'Pertama' => '#ff6b6b',
                'Muda'    => '#4ecdc4',
                'Madya'   => '#45b7d1',
                'Utama'   => '#96ceb4'
            ];

            $jenjangOrder = ['Pertama', 'Muda', 'Madya', 'Utama'];
            $datasets     = [];

            foreach ($jenjangOrder as $jenjang) {
                $jenjangData = [];

                foreach ($kotaList as $kota) {
                    $row = $data->where('jenjang_jabatan', $jenjang)->where('nama_kota', $kota)->first();
                    $jenjangData[] = $row ? (float) $row->rata_rata_level : 0;
                }

                if (array_sum($jenjangData) > 0) {
                    $datasets[] = [
                        'label'           => $jenjang,
                        'data'            => $jenjangData,
                        'backgroundColor' => $jenjangColors[$jenjang] ?? '#cccccc',
                        'borderColor'     => $jenjangColors[$jenjang] ?? '#cccccc',
                        'borderWidth'     => 1,
                    ];
                }
            }

            if (empty($datasets)) {
                foreach ($jenjangOrder as $jenjang) {
                    $datasets[] = [
                        'label'           => $jenjang,
                        'data'            => array_fill(0, count($kotaList), 0),
                        'backgroundColor' => $jenjangColors[$jenjang] ?? '#cccccc',
                        'borderColor'     => $jenjangColors[$jenjang] ?? '#cccccc',
                        'borderWidth'     => 1,
                    ];
                }
            }

            return ['labels' => $kotaList, 'datasets' => $datasets];
        } catch (\Exception $e) {
            \Log::error('Error getRataRataLevelPerJenjangKota: ' . $e->getMessage());
            return ['labels' => [], 'datasets' => [], 'error' => $e->getMessage()];
        }
    }

    /**
     * Export data analisis ke CSV (tanpa vendor PhpSpreadsheet)
     * Tata letak dirapihkan agar mudah dibaca
     */
    public function exportExcel(Request $request)
    {
        try {
            // Set memory limit dan waktu eksekusi
            ini_set('memory_limit', '4G');
            ini_set('max_execution_time', '600');

            // Dapatkan data analisis
            $analisisData = $this->getAnalisisData($request);

            // Buat nama file
            $filename = 'analisis-data-' . date('Ymd-His') . '.csv';

            // Set headers untuk download CSV
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');

            // Buka output stream
            $output = fopen('php://output', 'w');

            // Tambahkan BOM untuk UTF-8
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ============================================================
            // HEADER UTAMA
            // ============================================================
            fputcsv($output, ['']);
            fputcsv($output, ['"LAPORAN ANALISIS HASIL INSTRUMEN PTK"']);
            fputcsv($output, ['"Dicetak: ' . now()->format('d F Y H:i:s') . '"']);
            fputcsv($output, ['']);

            // ============================================================
            // FILTER INFO
            // ============================================================
            fputcsv($output, ['"===== FILTER YANG DIGUNAKAN ====="']);

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

            $npsnName = '';
            if ($request->filled('npsn')) {
                $sekolah = DB::table('sekolah')->where('npsn', $request->npsn)->first();
                $npsnName = $sekolah->nama_sekolah ?? $request->npsn;
            }

            $bentukPendidikanName = $request->bentuk_pendidikan ?? 'Semua';
            $jenjangPendidikanName = '';
            if ($request->filled('jenjang_pendidikan_id')) {
                $jenjangPend = DB::table('jenjang_pendidikan')->where('jenjang_pendidikan_id', $request->jenjang_pendidikan_id)->first();
                $jenjangPendidikanName = $jenjangPend->jenjang_pendidikan ?? '';
            }

            fputcsv($output, ['Kegiatan:', $kegiatanName ?: 'Semua Kegiatan']);
            fputcsv($output, ['Jenjang Jabatan:', $jenjangName ?: 'Semua Jenjang']);
            fputcsv($output, ['Jenis PTK:', $jenisPtkName ?: 'Semua Jenis']);
            fputcsv($output, ['Kota:', $kotaName ?: 'Semua Kota']);
            fputcsv($output, ['NPSN Sekolah:', $npsnName ?: 'Semua Sekolah']);
            fputcsv($output, ['Bentuk Pendidikan:', $bentukPendidikanName]);
            fputcsv($output, ['Jenjang Pendidikan:', $jenjangPendidikanName ?: 'Semua']);
            fputcsv($output, ['Jenis Kelamin:', $request->jenis_kelamin ? ($request->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan') : 'Semua']);
            fputcsv($output, ['']);

            // ============================================================
            // STATISTIK UTAMA
            // ============================================================
            fputcsv($output, ['"===== STATISTIK UTAMA ====="']);
            fputcsv($output, ['Total PTK', $analisisData['statistik']['total_ptk'] ?? 0]);
            fputcsv($output, ['PTK Menjawab', $analisisData['statistik']['ptk_menjawab'] ?? 0]);
            fputcsv($output, ['PTK Belum Menjawab', $analisisData['statistik']['ptk_belum_menjawab'] ?? 0]);
            fputcsv($output, ['Persentase Pengisian', ($analisisData['statistik']['persentase_isi'] ?? 0) . '%']);
            fputcsv($output, ['']);

            // ============================================================
            // RATA-RATA CAPAIAN PER JENJANG (PROVINSI)
            // ============================================================
            if (!empty($analisisData['rata_rata_level_provinsi']['labels'])) {
                fputcsv($output, ['"===== RATA-RATA CAPAIAN KOMPETENSI PER JENJANG JABATAN (PROVINSI) ====="']);
                fputcsv($output, ['Catatan: Rata-rata dihitung dari level_kalkulasi per baris jawaban, skala 0-100']);
                fputcsv($output, ['']);
                fputcsv($output, ['Jenjang Jabatan', 'Rata-rata Capaian', 'Jumlah PTK', 'Total Jawaban']);
                fputcsv($output, ['-------------', '----------------', '----------', '------------']);

                $provinsiLabels = $analisisData['rata_rata_level_provinsi']['labels'] ?? [];
                $provinsiData = $analisisData['rata_rata_level_provinsi']['data'] ?? [];
                $provinsiDetail = $analisisData['rata_rata_level_provinsi']['detail_per_jenjang'] ?? [];

                foreach ($provinsiLabels as $idx => $jenjang) {
                    $rataRata = $provinsiData[$idx] ?? 0;
                    $detail = $provinsiDetail[$jenjang] ?? [];
                    $jumlahPtk = $detail['jumlah_ptk'] ?? 0;
                    $totalJawab = $detail['total_jawaban'] ?? 0;
                    fputcsv($output, [$jenjang, $rataRata, $jumlahPtk, $totalJawab]);
                }
                fputcsv($output, ['']);
            }

            // ============================================================
            // RATA-RATA CAPAIAN PER JENJANG (KOTA)
            // ============================================================
            if (!empty($analisisData['rata_rata_level_kota']['labels'])) {
                fputcsv($output, ['"===== RATA-RATA CAPAIAN KOMPETENSI PER JENJANG JABATAN (PER KOTA) ====="']);
                fputcsv($output, ['Catatan: Rata-rata dihitung dari level_kalkulasi per baris jawaban, skala 0-100']);
                fputcsv($output, ['']);

                $kotaLabels = $analisisData['rata_rata_level_kota']['labels'] ?? [];
                $kotaDatasets = $analisisData['rata_rata_level_kota']['datasets'] ?? [];

                if (!empty($kotaLabels) && !empty($kotaDatasets)) {
                    $headers = ['Kota / Kabupaten'];
                    foreach ($kotaDatasets as $dataset) {
                        $headers[] = $dataset['label'];
                    }
                    fputcsv($output, $headers);

                    $separator = [];
                    foreach ($headers as $h) {
                        $separator[] = str_repeat('-', strlen($h));
                    }
                    fputcsv($output, $separator);

                    foreach ($kotaLabels as $kotaIdx => $namaKota) {
                        $row = [$namaKota];
                        foreach ($kotaDatasets as $dataset) {
                            $row[] = $dataset['data'][$kotaIdx] ?? 0;
                        }
                        fputcsv($output, $row);
                    }
                }
                fputcsv($output, ['']);
            }

            // ============================================================
            // 1. DISTRIBUSI JENJANG JABATAN
            // ============================================================
            if (!empty($analisisData['jenjang_distribution'])) {
                fputcsv($output, ['"1. DISTRIBUSI JENJANG JABATAN"']);
                fputcsv($output, ['']);
                fputcsv($output, ['Jenjang Jabatan', 'Jumlah PTK', 'Persentase']);
                fputcsv($output, ['---------------', '----------', '----------']);

                $jenjangData = collect($analisisData['jenjang_distribution']);
                $totalJenjang = $jenjangData->sum('count');

                foreach ($jenjangData as $jenjang) {
                    $jenjangJabatan = is_array($jenjang) ? $jenjang['jenjang_jabatan'] : $jenjang->jenjang_jabatan;
                    $jenjangCount = is_array($jenjang) ? $jenjang['count'] : $jenjang->count;
                    $percentage = $totalJenjang > 0 ? round(($jenjangCount / $totalJenjang) * 100, 1) : 0;
                    fputcsv($output, [$jenjangJabatan, $jenjangCount, $percentage . '%']);
                }
                fputcsv($output, ['']);
            }

            // ============================================================
            // 2. DISTRIBUSI JENJANG PENDIDIKAN
            // ============================================================
            if (!empty($analisisData['jenjang_pendidikan_distribution'])) {
                fputcsv($output, ['"2. DISTRIBUSI JENJANG PENDIDIKAN PTK"']);
                fputcsv($output, ['']);
                fputcsv($output, ['Jenjang Pendidikan', 'Jumlah PTK', 'Persentase']);
                fputcsv($output, ['------------------', '----------', '----------']);

                $jenjangPendidikanData = collect($analisisData['jenjang_pendidikan_distribution']);
                $totalJenjangPendidikan = $jenjangPendidikanData->sum('count');

                foreach ($jenjangPendidikanData as $jenjang) {
                    $jenjangPendidikanVal = is_array($jenjang) ? $jenjang['jenjang_pendidikan'] : $jenjang->jenjang_pendidikan;
                    $jenjangPendidikanCount = is_array($jenjang) ? $jenjang['count'] : $jenjang->count;
                    $percentage = $totalJenjangPendidikan > 0 ? round(($jenjangPendidikanCount / $totalJenjangPendidikan) * 100, 1) : 0;
                    fputcsv($output, [$jenjangPendidikanVal, $jenjangPendidikanCount, $percentage . '%']);
                }
                fputcsv($output, ['TOTAL', $totalJenjangPendidikan, '100%']);
                fputcsv($output, ['']);
            }

            // ============================================================
            // 3. DISTRIBUSI JENIS KELAMIN
            // ============================================================
            if (!empty($analisisData['jenis_kelamin_distribution'])) {
                fputcsv($output, ['"3. DISTRIBUSI JENIS KELAMIN PTK"']);
                fputcsv($output, ['']);
                fputcsv($output, ['Jenis Kelamin', 'Jumlah PTK', 'Persentase']);
                fputcsv($output, ['-------------', '----------', '----------']);

                $jenisKelaminData = collect($analisisData['jenis_kelamin_distribution']);
                $totalJenisKelamin = $jenisKelaminData->sum('count');

                foreach ($jenisKelaminData as $jenis) {
                    $jenisKelaminVal = is_array($jenis) ? $jenis['jenis_kelamin'] : $jenis->jenis_kelamin;
                    $jenisKelaminCount = is_array($jenis) ? $jenis['count'] : $jenis->count;
                    $percentage = $totalJenisKelamin > 0 ? round(($jenisKelaminCount / $totalJenisKelamin) * 100, 1) : 0;
                    fputcsv($output, [$jenisKelaminVal, $jenisKelaminCount, $percentage . '%']);
                }
                fputcsv($output, ['TOTAL', $totalJenisKelamin, '100%']);
                fputcsv($output, ['']);
            }

            // ============================================================
            // 4. DISTRIBUSI PTK PER SUB INDIKATOR
            // ============================================================
            if (!empty($analisisData['all_sub_indikators_chart']['labels'])) {
                fputcsv($output, ['"4. DISTRIBUSI PTK PER SUB INDIKATOR"']);
                fputcsv($output, ['']);

                $chartData = $analisisData['all_sub_indikators_chart'];
                $labels = $chartData['labels'];
                $datasets = $chartData['datasets'];

                $headers = ['Sub Indikator'];
                foreach ($datasets as $dataset) {
                    $headers[] = $dataset['label'];
                }
                $headers[] = 'TOTAL';
                fputcsv($output, $headers);

                $totalPerLevel = array_fill(0, count($datasets), 0);
                $grandTotal = 0;

                for ($i = 0; $i < count($labels); $i++) {
                    $row = [$labels[$i]];
                    $totalRow = 0;

                    foreach ($datasets as $index => $dataset) {
                        $value = $dataset['data'][$i] ?? 0;
                        $row[] = $value;
                        $totalRow += $value;
                        $totalPerLevel[$index] += $value;
                    }
                    $row[] = $totalRow;
                    $grandTotal += $totalRow;
                    fputcsv($output, $row);
                }

                $footerRow = ['TOTAL'];
                foreach ($totalPerLevel as $totalLevel) {
                    $footerRow[] = $totalLevel;
                }
                $footerRow[] = $grandTotal;
                fputcsv($output, $footerRow);
                fputcsv($output, ['']);
            }

            // ============================================================
            // 5-8. DISTRIBUSI PER JENJANG JABATAN
            // ============================================================
            if (!empty($analisisData['sub_indikator_per_jenjang'])) {
                $sectionNumber = 5;
                foreach ($analisisData['sub_indikator_per_jenjang'] as $jenjangData) {
                    fputcsv($output, ['"' . $sectionNumber . '. DISTRIBUSI PTK PER SUB INDIKATOR - ' . strtoupper($jenjangData['jenjang_jabatan']) . '"']);
                    fputcsv($output, ['']);

                    $labels = $jenjangData['labels'];
                    $datasets = $jenjangData['datasets'];

                    $headers = ['Sub Indikator'];
                    foreach ($datasets as $dataset) {
                        $headers[] = $dataset['label'];
                    }
                    $headers[] = 'TOTAL';
                    fputcsv($output, $headers);

                    $totalPerLevel = array_fill(0, count($datasets), 0);
                    $grandTotal = 0;

                    for ($i = 0; $i < count($labels); $i++) {
                        $row = [$labels[$i]];
                        $totalRow = 0;

                        foreach ($datasets as $index => $dataset) {
                            $value = $dataset['data'][$i] ?? 0;
                            $row[] = $value;
                            $totalRow += $value;
                            $totalPerLevel[$index] += $value;
                        }
                        $row[] = $totalRow;
                        $grandTotal += $totalRow;
                        fputcsv($output, $row);
                    }

                    $footerRow = ['TOTAL'];
                    foreach ($totalPerLevel as $totalLevel) {
                        $footerRow[] = $totalLevel;
                    }
                    $footerRow[] = $grandTotal;
                    fputcsv($output, $footerRow);
                    fputcsv($output, ['']);

                    $sectionNumber++;
                }
            }

            // ============================================================
            // 9. MODUS LEVEL PER KOTA
            // ============================================================
            if (!empty($analisisData['modus_per_kota'])) {
                fputcsv($output, ['"9. MODUS LEVEL PER KOTA"']);
                fputcsv($output, ['']);
                fputcsv($output, ['No', 'Kota', 'Sub Indikator', 'Modus Level', 'Level Name', 'Jumlah PTK', 'Persentase']);
                fputcsv($output, ['--', '----', '-------------', '-----------', '----------', '-----------', '----------']);

                $levelNames = [
                    1 => 'Dasar',
                    2 => 'Penerapan',
                    3 => 'Analisis',
                    4 => 'Evaluasi',
                    5 => 'Pembimbingan'
                ];

                $no = 1;
                foreach ($analisisData['modus_per_kota'] as $kota) {
                    if (empty($kota['sub_indikator_modus'])) continue;

                    foreach ($kota['sub_indikator_modus'] as $sub) {
                        $percentage = $kota['total_jawaban'] > 0
                            ? round(($sub['jumlah_jawaban'] / $kota['total_jawaban']) * 100, 1)
                            : 0;

                        fputcsv($output, [
                            $no,
                            $kota['nama_kota'],
                            $sub['sub_indikator_code'] . ' - ' . substr($sub['sub_indikator_name'], 0, 45),
                            $sub['modus_level'],
                            $levelNames[$sub['modus_level']] ?? '',
                            $sub['jumlah_jawaban'],
                            $percentage . '%'
                        ]);
                    }
                    $no++;
                }
                fputcsv($output, ['']);
            }

            // ============================================================
            // 10. REKOMENDASI GAP PER JENJANG
            // ============================================================
            if (!empty($analisisData['rekomendasi_gap_per_jenjang'])) {
                fputcsv($output, ['"10. REKOMENDASI KEBUTUHAN BELAJAR PER JENJANG JABATAN"']);
                fputcsv($output, ['']);

                $jenjangOrderMap = ['Pertama' => 1, 'Muda' => 2, 'Madya' => 3, 'Utama' => 4];
                $sortedRekData = collect($analisisData['rekomendasi_gap_per_jenjang'])
                    ->sortBy(fn($item) => $jenjangOrderMap[$item['jenjang_jabatan']] ?? 999)
                    ->values()
                    ->toArray();

                $levelNames = [
                    1 => 'Dasar',
                    2 => 'Penerapan',
                    3 => 'Analisis',
                    4 => 'Evaluasi',
                    5 => 'Pembimbingan'
                ];

                foreach ($sortedRekData as $jenjangRek) {
                    $jenjangNama = $jenjangRek['jenjang_jabatan'] ?? '-';
                    $totalPtkJenj = $jenjangRek['total_ptk'] ?? 0;
                    $levelTarget = $jenjangRek['level_kompetensi'] ?? ($jenjangRek['level_target'] ?? 0);
                    $rekomendasis = $jenjangRek['rekomendasi'] ?? [];

                    fputcsv($output, []);
                    fputcsv($output, ['"=== ' . strtoupper($jenjangNama) . ' | Level Target: ' . $levelTarget . ' | Total PTK: ' . $totalPtkJenj . ' | Gap: ' . count($rekomendasis) . ' sub indikator ==="']);
                    fputcsv($output, ['']);

                    if (empty($rekomendasis)) {
                        fputcsv($output, ['"Semua PTK sudah mencapai level kompetensi!"']);
                        fputcsv($output, ['']);
                        continue;
                    }

                    fputcsv($output, ['#', 'Sub Indikator', 'No', 'Level Dicapai', 'Level Target', 'Gap', 'Rekomendasi', 'Jml PTK', '%']);
                    fputcsv($output, ['-', '-------------', '--', '-------------', '------------', '---', '-----------', '-------', '--']);

                    $counter = 1;
                    foreach ($rekomendasis as $rek) {
                        $detailGaps = $rek['detail_gap'] ?? [];
                        if (empty($detailGaps)) continue;

                        $subCode = $rek['sub_indikator_code'] ?? '-';
                        $subName = $rek['sub_indikator_name'] ?? '-';
                        $subDisplay = $subCode . ' - ' . $subName;

                        usort($detailGaps, function ($a, $b) {
                            if ($a['level_dicapai'] != $b['level_dicapai']) {
                                return $a['level_dicapai'] - $b['level_dicapai'];
                            }
                            return $a['level_harus'] - $b['level_harus'];
                        });

                        foreach ($detailGaps as $gapIdx => $gap) {
                            $lvlDicapai = $gap['level_dicapai'] ?? 0;
                            $lvlHarus = $gap['level_harus'] ?? 0;
                            $gapVal = $gap['level_gap'] ?? 0;
                            $rekText = $gap['rekomendasi'] ?? '-';
                            $noUrut = $gapIdx + 1;
                            $jumlahPtkLevel = $gap['jumlah_ptk'] ?? 0;

                            $percentage = $totalPtkJenj > 0 ? round(($jumlahPtkLevel / $totalPtkJenj) * 100, 1) : 0;

                            $lvlDicapaiName = $levelNames[$lvlDicapai] ?? "Level {$lvlDicapai}";
                            $lvlHarusName = $levelNames[$lvlHarus] ?? "Level {$lvlHarus}";

                            if ($gapIdx == 0) {
                                fputcsv($output, [
                                    $counter,
                                    $subDisplay,
                                    $noUrut,
                                    "Lv{$lvlDicapai} ({$lvlDicapaiName})",
                                    "Lv{$lvlHarus} ({$lvlHarusName})",
                                    $gapVal > 0 ? "+{$gapVal}" : '0',
                                    $rekText,
                                    $jumlahPtkLevel,
                                    $percentage . '%'
                                ]);
                            } else {
                                fputcsv($output, [
                                    '',
                                    '',
                                    $noUrut,
                                    "Lv{$lvlDicapai} ({$lvlDicapaiName})",
                                    "Lv{$lvlHarus} ({$lvlHarusName})",
                                    $gapVal > 0 ? "+{$gapVal}" : '0',
                                    $rekText,
                                    $jumlahPtkLevel,
                                    $percentage . '%'
                                ]);
                            }
                        }
                        $counter++;
                    }

                    fputcsv($output, ['']);
                    fputcsv($output, ['Total PTK pada jenjang ' . $jenjangNama . ': ' . $totalPtkJenj . ' PTK, dengan ' . count($rekomendasis) . ' sub indikator bermasalah']);
                    fputcsv($output, ['']);
                }
            }

            // ============================================================
            // 11. PROGRESS PENGISIAN PER KOTA
            // ============================================================
            if (!empty($analisisData['progress_kota'])) {
                fputcsv($output, ['"11. PROGRESS PENGISIAN PER KOTA"']);
                fputcsv($output, ['']);
                fputcsv($output, ['Kota', 'Total PTK', 'Sudah Isi', 'Persentase', 'Status']);
                fputcsv($output, ['----', '---------', '---------', '----------', '------']);

                foreach ($analisisData['progress_kota'] as $kota) {
                    $status = '';
                    if ($kota->persentase >= 80) {
                        $status = 'Baik';
                    } elseif ($kota->persentase >= 50) {
                        $status = 'Cukup';
                    } else {
                        $status = 'Perlu Perhatian';
                    }
                    fputcsv($output, [$kota->nama_kota, $kota->total_ptk, $kota->sudah_isi, $kota->persentase . '%', $status]);
                }
                fputcsv($output, ['']);
            }

            // ============================================================
            // 12. DATA PELATIHAN
            // ============================================================
            if (!empty($analisisData['pelatihan_data'])) {
                fputcsv($output, ['"12. DATA PELATIHAN YANG DIPILIH PTK"']);
                fputcsv($output, ['']);
                fputcsv($output, ['No', 'Nama Pelatihan', 'Jumlah PTK', 'Persentase', 'Tipe']);
                fputcsv($output, ['--', '---------------', '----------', '----------', '----']);

                $pelatihanData = $analisisData['pelatihan_data'];
                $totalPtkPelatihan = 0;

                foreach ($pelatihanData as $pelatihan) {
                    $totalPtkPelatihan += $pelatihan->jumlah_ptk;
                }

                $no = 1;
                foreach ($pelatihanData as $pelatihan) {
                    $percentage = $totalPtkPelatihan > 0 ? round(($pelatihan->jumlah_ptk / $totalPtkPelatihan) * 100, 1) : 0;
                    fputcsv($output, [$no, $pelatihan->nama_pelatihan, $pelatihan->jumlah_ptk, $percentage . '%', $pelatihan->tipe == 'master' ? 'Master' : 'Manual']);
                    $no++;
                }
                fputcsv($output, ['TOTAL', '', $totalPtkPelatihan, '100%', '']);
                fputcsv($output, ['']);
            }

            // Tutup stream
            fclose($output);
            exit;
        } catch (\Exception $e) {
            \Log::error('Export CSV Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }

    private function getPtkProgressData(Request $request)
    {
        $query = DB::table('ptk_jawaban')
            ->select(
                'ptk.ptk_id',
                'ptk.nip',
                'ptk.nama',
                'ptk.instansi',
                'ptk.no_hp',
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
                'ptk.instansi',
                'ptk.no_hp',
                'pangkat_jabatan.jenjang_jabatan',
                'kegiatan.entity',
                'sekolah.nama_sekolah',
                'kota.nama_kota'
            );

        if ($request->filled('kegiatan_id'))           $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        if ($request->filled('pangkat_jabatan_id'))    $query->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
        if ($request->filled('jenis_ptk_id'))          $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        if ($request->filled('kota_id'))               $query->where('ptk.kota_id', $request->kota_id);
        if ($request->filled('jenjang_pendidikan_id')) $query->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
        if ($request->filled('bentuk_pendidikan'))     $query->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
        if ($request->filled('jenis_kelamin'))         $query->where('ptk.jenis_kelamin', $request->jenis_kelamin);
        if ($request->filled('npsn'))                  $query->where('sekolah.npsn', $request->npsn);

        return $query->orderBy('kegiatan.entity')->orderBy('ptk.nama')->get();
    }

    private function validateJenisPtkWithKegiatan($kegiatanId, $jenisPtkId)
    {
        $kegiatan = DB::table('kegiatan')->where('kegiatan_id', $kegiatanId)->first();

        if (!$kegiatan || empty($kegiatan->entity)) {
            return true;
        }

        $jenisPtk = DB::table('jenis_ptk')->where('jenis_ptk_id', $jenisPtkId)->first();

        if (!$jenisPtk || empty($jenisPtk->jenis_ptk)) {
            return true;
        }

        $entity = strtolower(trim($kegiatan->entity));
        $jenis  = strtolower(trim($jenisPtk->jenis_ptk));

        $isCompatible = false;

        if (strpos($entity, 'guru') !== false && strpos($jenis, 'guru') !== false) {
            $isCompatible = true;
        }
        if (strpos($entity, 'kepala sekolah') !== false && strpos($jenis, 'kepala sekolah') !== false) {
            $isCompatible = true;
        }
        if (strpos($entity, 'pengawas') !== false && strpos($jenis, 'pengawas') !== false) {
            $isCompatible = true;
        }
        if (strpos($entity, 'semua') !== false || strpos($entity, 'all') !== false) {
            $isCompatible = true;
        }

        return $isCompatible;
    }

    public function getKegiatanEntity($id)
    {
        $kegiatan = DB::table('kegiatan')
            ->select('kegiatan_id', 'kegiatan_name', 'entity')
            ->where('kegiatan_id', $id)
            ->first();

        return response()->json($kegiatan);
    }

    // =========================================================================
    // Method-method untuk export Excel — tidak diubah logikanya, hanya
    // dipastikan filter NPSN sudah masuk lewat $request yang sama.
    // Semua helper di bawah ini konsisten dengan perbaikan di atas.
    // =========================================================================


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

        if ($request->filled('kegiatan_id'))           $query->where('ptk_pelatihan.kegiatan_id', $request->kegiatan_id);
        if ($request->filled('pangkat_jabatan_id'))    $query->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
        if ($request->filled('jenis_ptk_id'))          $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        if ($request->filled('kota_id'))               $query->where('ptk.kota_id', $request->kota_id);
        if ($request->filled('jenjang_pendidikan_id')) $query->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
        if ($request->filled('bentuk_pendidikan'))     $query->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
        if ($request->filled('jenis_kelamin'))         $query->where('ptk.jenis_kelamin', $request->jenis_kelamin);
        if ($request->filled('npsn'))                  $query->where('sekolah.npsn', $request->npsn);

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
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                'sekolah.nama_sekolah',
                'sekolah.npsn',
                'kota.nama_kota',
                'sub_indikator.sub_indikator_name',
                'kegiatan.kegiatan_name',
                'kegiatan.entity',
                'kegiatan.kegiatan_id',
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

        if ($request->filled('kegiatan_id'))           $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        if ($request->filled('pangkat_jabatan_id'))    $query->where('ptk.pangkat_jabatan_id', $request->pangkat_jabatan_id);
        if ($request->filled('jenis_ptk_id'))          $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        if ($request->filled('kota_id'))               $query->where('ptk.kota_id', $request->kota_id);
        if ($request->filled('jenjang_pendidikan_id')) $query->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
        if ($request->filled('bentuk_pendidikan'))     $query->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
        if ($request->filled('jenis_kelamin'))         $query->where('ptk.jenis_kelamin', $request->jenis_kelamin);
        if ($request->filled('npsn'))                  $query->where('sekolah.npsn', $request->npsn);

        return $query->orderBy('ptk.nip')->orderBy('ptk_jawaban.sub_indikator_code')->get();
    }

    private function getPelatihanByPtkExcel($ptkId, $kegiatanId)
    {
        return DB::table('ptk_pelatihan')
            ->select(
                'ptk_pelatihan.*',
                'ms_pelatihan.nama_pelatihan',
                DB::raw("CASE
                WHEN ptk_pelatihan.ms_pelatihan_id IS NOT NULL AND ptk_pelatihan.ms_pelatihan_id != 0 THEN ms_pelatihan.nama_pelatihan
                WHEN ptk_pelatihan.pelatihan_lainnya IS NOT NULL AND ptk_pelatihan.pelatihan_lainnya != '' THEN ptk_pelatihan.pelatihan_lainnya
                ELSE 'Belum Tersedia'
            END as nama_pelatihan_lengkap"),
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

    private function getRekomendasiWithGapExcel($jenjangJabatan, $levelDicapai, $subIndikatorId, $tahap, $entity, $subIndikatorCode)
    {
        $targetLevels = [
            'Pertama' => ['min' => 2, 'max' => 2],
            'Muda'    => ['min' => 2, 'max' => 3],
            'Madya'   => ['min' => 2, 'max' => 4],
            'Utama'   => ['min' => 2, 'max' => 5],
        ];

        $target = $targetLevels[$jenjangJabatan] ?? ['min' => 2, 'max' => 3];
        $levelMin = $target['min'];
        $levelMax = $target['max'];

        if ($levelDicapai >= $levelMax) {
            return [
                'level_min'       => $levelMin,
                'level_max'       => $levelMax,
                'status'          => 'Mencapai Semua Level',
                'status_class'    => 'success',
                'rekomendasi_gap' => []
            ];
        }

        $rekomendasiGap = [];
        for ($level = $levelDicapai + 1; $level <= $levelMax; $level++) {
            $rekText = $this->getRekomendasiText($subIndikatorId, $subIndikatorCode, $tahap, $entity, $levelDicapai, $level);
            $rekomendasiGap[] = [
                'level'       => $level,
                'rekomendasi' => $rekText
            ];
        }

        $gap = $levelMax - $levelDicapai;

        if ($gap <= 1) {
            $status      = 'Mendekati Target';
            $statusClass = 'warning';
        } else {
            $status      = 'Perlu Peningkatan';
            $statusClass = 'danger';
        }

        return [
            'level_min'       => $levelMin,
            'level_max'       => $levelMax,
            'status'          => $status,
            'status_class'    => $statusClass,
            'rekomendasi_gap' => $rekomendasiGap
        ];
    }
}
