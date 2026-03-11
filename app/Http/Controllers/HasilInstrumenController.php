<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HasilInstrumenExport;





use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;



class HasilInstrumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $tittle = 'Hasil Instrumen PTK';

    // =========================
    // 1) BASE QUERY (untuk paginate UI)
    // =========================
    $query = DB::table('ptk_jawaban')
        ->select(
            'ptk_jawaban.ptk_jawaban_id',
            'ptk_jawaban.tahap',
             'ptk_jawaban.level_kalkulasi',
            'ptk_jawaban.level as level_jawaban',
            'ptk_jawaban.sub_indikator_code',
            'ptk_jawaban.sub_indikator_id',
            'ptk_jawaban.bobot',
            'ptk_jawaban.created_at',
            'ptk.nama',
            'ptk.nip',
            'ptk.ptk_id',
            'ptk.pangkat_jabatan_id',
            'ptk.jenis_ptk_id',
            'ptk.instansi',
            'ptk.kota_id',
            'pangkat_jabatan.golongan_ruang',
            'pangkat_jabatan.pangkat',
            'pangkat_jabatan.jenjang_jabatan',
            'pangkat_jabatan.level_kompetensi',
            'kota.nama_kota',
            'sub_indikator.sub_indikator_name',
            'kegiatan.kegiatan_name',
            'kegiatan.entity',
            'kegiatan.kegiatan_id',
            'ptk_jawaban.kegiatan_id as jawaban_kegiatan_id',
            'jenis_ptk.jenis_ptk'
        )
        ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
        ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
        ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
        ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
        ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
        ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id');

    // =========================
    // 2) APPLY FILTERS (biar query paginate & query summary sama persis)
    // =========================
    $applyFilters = function ($q) use ($request) {

        if ($request->filled('search')) {
            $search = $request->search;
            $q->where(function ($qq) use ($search) {
                $qq->where('ptk.nip', 'like', "%{$search}%")
                    ->orWhere('ptk.nama', 'like', "%{$search}%")
                    ->orWhere('pangkat_jabatan.pangkat', 'like', "%{$search}%")
                    ->orWhere('pangkat_jabatan.jenjang_jabatan', 'like', "%{$search}%")
                    ->orWhere('kota.nama_kota', 'like', "%{$search}%")
                    ->orWhere('sub_indikator.sub_indikator_name', 'like', "%{$search}%")
                    ->orWhere('jenis_ptk.jenis_ptk', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kegiatan_id')) {
            $q->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        }

        if ($request->filled('tahap')) {
            $q->where('ptk_jawaban.tahap', $request->tahap);
        }

        if ($request->filled('pangkat_jabatan_id')) {
            // value ini udah jenjang_jabatan
            $q->where('pangkat_jabatan.jenjang_jabatan', $request->pangkat_jabatan_id);
        }

        if ($request->filled('jenis_ptk_id')) {
            $q->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
        }
    };

    // apply ke query paginate
    $applyFilters($query);

    // paginate (UI)
    $query->orderBy('ptk_jawaban.ptk_jawaban_id', 'desc');
    $data = $query->paginate(65);

    // =========================
    // 3) SUMMARY GLOBAL PER NIP (tanpa paginate)
    //    -> ini yang bikin 13/13 tetap 13/13 walau beda page
    // =========================
    $targetLevelSql = "
        CASE
            WHEN LOWER(COALESCE(pangkat_jabatan.jenjang_jabatan,'')) LIKE '%utama%'   THEN 5
            WHEN LOWER(COALESCE(pangkat_jabatan.jenjang_jabatan,'')) LIKE '%madya%'   THEN 4
            WHEN LOWER(COALESCE(pangkat_jabatan.jenjang_jabatan,'')) LIKE '%muda%'    THEN 3
            WHEN LOWER(COALESCE(pangkat_jabatan.jenjang_jabatan,'')) LIKE '%pertama%' THEN 2
            WHEN LOWER(COALESCE(pangkat_jabatan.jenjang_jabatan,'')) LIKE '%pratama%' THEN 2
            ELSE NULL
        END
    ";

    $summaryQuery = DB::table('ptk_jawaban')
        ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
        ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
        ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
        ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
        ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
        ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id');

    // apply filter yang sama persis
    $applyFilters($summaryQuery);

$summaryRows = $summaryQuery
    ->select(
        DB::raw("TRIM(ptk.nip) as nip"),
        DB::raw("COUNT(DISTINCT ptk_jawaban.sub_indikator_id) as total_indikator"),
        DB::raw("COUNT(DISTINCT CASE
                    WHEN ptk_jawaban.level >= ($targetLevelSql)
                    THEN ptk_jawaban.sub_indikator_id
                END) as memenuhi")
    )
    ->groupBy(DB::raw("TRIM(ptk.nip)"))
    ->get();

    // jadikan map: nip => ['total'=>..,'memenuhi'=>..]
    $summaryByNip = [];
    foreach ($summaryRows as $sr) {
        $nipKey = (string)($sr->nip ?? 'tanpa_nip');
        $summaryByNip[$nipKey] = [
            'total'    => (int) ($sr->total_indikator ?? 0),
            'memenuhi' => (int) ($sr->memenuhi ?? 0),
        ];
    }


    // ✅ Hitung presentase per NIP dari SEMUA data (bukan hanya halaman ini)
$presentaseQuery = DB::table('ptk_jawaban')
    ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
    ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
    ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
    ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
    ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
    ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id');

// apply filter SAMA PERSIS
$applyFilters($presentaseQuery);

$presentaseRows = $presentaseQuery
    ->select(
        DB::raw("TRIM(ptk.nip) as nip"),
        DB::raw("SUM(COALESCE(ptk_jawaban.level_kalkulasi, 0)) as total_kalkulasi"),
        DB::raw("COUNT(ptk_jawaban.sub_indikator_id) as jumlah_sub")
    )
    ->groupBy(DB::raw("TRIM(ptk.nip)"))
    ->get();

$presentaseByNip = [];
foreach ($presentaseRows as $pr) {
    $nipKey = (string)($pr->nip ?? 'tanpa_nip');
    $jumlah = (int)($pr->jumlah_sub ?? 0);
    $total  = (float)($pr->total_kalkulasi ?? 0);
    $presentaseByNip[$nipKey] = $jumlah > 0 ? round($total / $jumlah, 2) : 0;
}

    // =========================
    // 4) rekomendasi & pelatihan (tetap seperti punya kamu)
    // =========================
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

        $kegiatanId = $item->jawaban_kegiatan_id ?? $item->kegiatan_id;
        if (isset($item->ptk_id) && isset($kegiatanId)) {
            $item->pelatihan = $this->getPelatihanByPtk($item->ptk_id, $kegiatanId);
        } else {
            $item->pelatihan = collect();
        }
    }

    // dropdown
    $kegiatans = DB::table('kegiatan')->get();

    $pangkatJabatans = DB::table('pangkat_jabatan')
        ->select('jenjang_jabatan')
        ->distinct()
        ->orderByRaw("CASE jenjang_jabatan
            WHEN 'Utama' THEN 1
            WHEN 'Madya' THEN 2
            WHEN 'Muda' THEN 3
            WHEN 'Pertama' THEN 4
            WHEN 'Pratama' THEN 5
            ELSE 6 END")
        ->get();

    $jenisPtk = DB::table('jenis_ptk')
        ->orderBy('jenis_ptk', 'asc')
        ->get();



    return view('hasil.index', compact(
        'tittle',
        'data',
        'kegiatans',
        'pangkatJabatans',
        'jenisPtk',
        'summaryByNip',
         'presentaseByNip' // ✅ WAJIB dikirim ke blade
    ));
}   

    /**
     * Export PDF per PTK
     */
    public function export($ptk_id)
    {
        // Query dengan INNER JOIN untuk satu PTK
        $data = DB::table('ptk_jawaban')
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
                'ptk.ptk_id',
                'ptk.pangkat_jabatan_id',
                'ptk.instansi',
                'ptk.email',
                'ptk.no_hp',
                'ptk.jenis_ptk_id',
                // Ambil data dari tabel pangkat_jabatan
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                'pangkat_jabatan.level_kompetensi',
                // Ambil data sub indikator
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
            ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
            ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id')
            ->where('ptk_jawaban.ptk_id', $ptk_id)
            ->orderBy('ptk_jawaban.sub_indikator_code', 'asc') // TAMBAHKAN order by untuk PDF
            ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Tambahkan rekomendasi dengan gap untuk setiap data
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

            // Ambil data pelatihan untuk PTK ini
            $item->pelatihan = $this->getPelatihanByPtk($item->ptk_id, $item->kegiatan_id);
        }

        $totalSkor = $data->sum('bobot');
        $totalIndikator = $data->count();
        $ptk = $data->first();

        if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('hasil.export-pdf', [
                'data' => $data,
                'ptk' => $ptk,
                'totalSkor' => $totalSkor,
                'totalIndikator' => $totalIndikator,
                'tanggal' => now()->format('d F Y H:i:s')
            ]);

            return $pdf->download('hasil-instrumen-' . ($ptk->nip ?? 'unknown') . '-' . date('Ymd-His') . '.pdf');
        } else {
            return view('hasil.export-pdf', [
                'data' => $data,
                'ptk' => $ptk,
                'totalSkor' => $totalSkor,
                'totalIndikator' => $totalIndikator,
                'tanggal' => now()->format('d F Y H:i:s')
            ]);
        }
    }

    public function exportAllPdf(Request $request)
    {
        try {
            // Query dengan INNER JOIN termasuk tabel sekolah, kota, dan sub_indikator
            $query = DB::table('ptk_jawaban')
                ->select(
                    'ptk_jawaban.ptk_jawaban_id',
                    'ptk_jawaban.tahap',
                    'ptk_jawaban.level',
                    'ptk_jawaban.sub_indikator_code',
                    'ptk_jawaban.sub_indikator_id',
                    'ptk_jawaban.bobot',
                    'ptk_jawaban.created_at',
                    'ptk.ptk_id',
                    'ptk.nama',
                    'ptk.nip',
                    'ptk.pangkat_jabatan_id',
                    'ptk.jenis_ptk_id', // TAMBAHKAN
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
                ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id');

            // Filter pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('ptk.nip', 'like', "%{$search}%")
                        ->orWhere('ptk.nama', 'like', "%{$search}%")
                        ->orWhere('pangkat_jabatan.pangkat', 'like', "%{$search}%")
                        ->orWhere('pangkat_jabatan.jenjang_jabatan', 'like', "%{$search}%")
                        ->orWhere('kota.nama_kota', 'like', "%{$search}%")
                        ->orWhere('sub_indikator.sub_indikator_name', 'like', "%{$search}%")
                        ->orWhere('jenis_ptk.jenis_ptk', 'like', "%{$search}%");
                });
            }

            if ($request->filled('kegiatan_id')) {
                $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            }

            if ($request->filled('tahap')) {
                $query->where('ptk_jawaban.tahap', $request->tahap);
            }

            // FILTER PANGKAT JABATAN - pakai jenjang_jabatan
            if ($request->filled('pangkat_jabatan_id')) {
                $query->where('pangkat_jabatan.jenjang_jabatan', $request->pangkat_jabatan_id);
            }

            // FILTER JENIS PTK
            if ($request->filled('jenis_ptk_id')) {
                $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            }

            // ORDER BY: Pertama urutkan sub_indikator_code, lalu nama PTK (HANYA UNTUK PDF)
            $query->orderBy('ptk_jawaban.sub_indikator_code', 'asc')
                ->orderBy('ptk.nama', 'asc');

            $data = $query->get();

            if ($data->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data untuk diexport');
            }

            // Group data by PTK
            $groupedData = $data->groupBy('nip');

            // Ambil nama kegiatan untuk ditampilkan di PDF
            $kegiatan_name = '';
            if ($request->filled('kegiatan_id')) {
                $kegiatan = DB::table('kegiatan')->where('kegiatan_id', $request->kegiatan_id)->first();
                $kegiatan_name = $kegiatan->kegiatan_name ?? '';
            }

            // Ambil nama jenjang jabatan untuk filter (jika ada)
            $jenjang_name = '';
            if ($request->filled('pangkat_jabatan_id')) {
                $jenjang_name = $request->pangkat_jabatan_id; // Ini sudah jenjang_jabatan
            }

            // Ambil nama jenis ptk untuk filter (jika ada)
            $jenis_ptk_name = '';
            if ($request->filled('jenis_ptk_id')) {
                $jenisPtk = DB::table('jenis_ptk')->where('jenis_ptk_id', $request->jenis_ptk_id)->first();
                $jenis_ptk_name = $jenisPtk->jenis_ptk ?? '';
            }

            // Ambil data pelatihan untuk semua PTK yang ditampilkan
            $pelatihanPerPtk = [];
            foreach ($groupedData as $nip => $rows) {
                $firstRow = $rows->first();
                if (isset($firstRow->ptk_id) && isset($firstRow->kegiatan_id)) {
                    $pelatihanPerPtk[$nip] = $this->getPelatihanByPtk($firstRow->ptk_id, $firstRow->kegiatan_id);
                } else {
                    $pelatihanPerPtk[$nip] = collect();
                }
            }

            if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                return redirect()->back()->with('error', 'Fitur PDF belum tersedia. Silakan install package DomPDF.');
            }

            $viewPath = 'hasil.export-all-pdf';
            if (!view()->exists($viewPath)) {
                return redirect()->back()->with('error', 'Template PDF tidak ditemukan.');
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewPath, [
                'groupedData' => $groupedData,
                'pelatihanPerPtk' => $pelatihanPerPtk,
                'search' => $request->search,
                'kegiatan_id' => $request->kegiatan_id,
                'kegiatan_name' => $kegiatan_name,
                'tahap' => $request->tahap,
                'pangkat_jabatan_id' => $jenjang_name, // Kirim nama jenjang
                'jenis_ptk_id' => $jenis_ptk_name, // Kirim nama jenis ptk
                'tanggal' => now()->format('d F Y H:i:s')
            ]);

            $pdf->setOptions([
                'dpi' => 96,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => false,
                'chroot' => public_path(),
            ]);


            return $pdf->download('hasil-instrumen-filter-' . date('Ymd-His') . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }











    /**
     * Export Excel dengan tampilan SAMA PERSIS seperti PDF
     */
    public function exportExcelAll(Request $request)
    {
        try {
            // // Set memory limit untuk handle data besar
            // ini_set('memory_limit', '512M');
            // ini_set('max_execution_time', 300); // 5 menit

            // Query SAMA PERSIS dengan exportAllPdf
            $query = DB::table('ptk_jawaban')
                ->select(
                    'ptk_jawaban.ptk_jawaban_id',
                    'ptk_jawaban.tahap',
                    'ptk_jawaban.level',
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
                ->leftJoin('jenis_ptk', 'ptk.jenis_ptk_id', '=', 'jenis_ptk.jenis_ptk_id');

            // Filter pencarian SAMA PERSIS dengan PDF
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('ptk.nip', 'like', "%{$search}%")
                        ->orWhere('ptk.nama', 'like', "%{$search}%")
                        ->orWhere('pangkat_jabatan.pangkat', 'like', "%{$search}%")
                        ->orWhere('pangkat_jabatan.jenjang_jabatan', 'like', "%{$search}%")
                        ->orWhere('kota.nama_kota', 'like', "%{$search}%")
                        ->orWhere('sub_indikator.sub_indikator_name', 'like', "%{$search}%")
                        ->orWhere('jenis_ptk.jenis_ptk', 'like', "%{$search}%");
                });
            }

            if ($request->filled('kegiatan_id')) {
                $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            }

            if ($request->filled('tahap')) {
                $query->where('ptk_jawaban.tahap', $request->tahap);
            }

            // FILTER PANGKAT JABATAN - pakai jenjang_jabatan
            if ($request->filled('pangkat_jabatan_id')) {
                $query->where('pangkat_jabatan.jenjang_jabatan', $request->pangkat_jabatan_id);
            }

            // FILTER JENIS PTK
            if ($request->filled('jenis_ptk_id')) {
                $query->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            }

            // ORDER BY SAMA dengan PDF
            $query->orderBy('ptk_jawaban.sub_indikator_code', 'asc')
                ->orderBy('ptk.nama', 'asc');

            // // Untuk data besar, gunakan chunk tapi tetap simpan semua data
            // $allData = collect();
            // $query->chunk(1000, function ($chunk) use (&$allData) {
            //     foreach ($chunk as $item) {
            //         $allData->push($item);
            //     }
            // });


            // if ($allData->isEmpty()) {
            //     return redirect()->back()->with('error', 'Tidak ada data untuk diexport');
            // }

            // // Group data by PTK SAMA dengan PDF
            // $groupedData = $allData->groupBy('nip');



            $groupedData = collect();

            $query
                ->orderBy('ptk.ptk_id') // PALING AMAN UNTUK CHUNK
                ->orderBy('ptk_jawaban.sub_indikator_code')
                ->chunk(1000, function ($chunk) use (&$groupedData) {

                    foreach ($chunk as $item) {

                        if (!$groupedData->has($item->nip)) {
                            $groupedData->put($item->nip, collect());
                        }

                        $groupedData->get($item->nip)->push($item);
                    }
                });

            if ($groupedData->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data untuk diexport');
            }



            // Ambil filter info SAMA dengan PDF
            $kegiatan_name = '';
            if ($request->filled('kegiatan_id')) {
                $kegiatan = DB::table('kegiatan')->where('kegiatan_id', $request->kegiatan_id)->first();
                $kegiatan_name = $kegiatan->kegiatan_name ?? '';
            }

            $jenjang_name = '';
            if ($request->filled('pangkat_jabatan_id')) {
                $jenjang_name = $request->pangkat_jabatan_id;
            }

            $jenis_ptk_name = '';
            if ($request->filled('jenis_ptk_id')) {
                $jenisPtk = DB::table('jenis_ptk')->where('jenis_ptk_id', $request->jenis_ptk_id)->first();
                $jenis_ptk_name = $jenisPtk->jenis_ptk ?? '';
            }

            // Ambil data pelatihan SAMA dengan PDF
            $pelatihanPerPtk = [];
            foreach ($groupedData as $nip => $rows) {
                $firstRow = $rows->first();
                if (isset($firstRow->ptk_id) && isset($firstRow->kegiatan_id)) {
                    $pelatihanPerPtk[$nip] = $this->getPelatihanByPtk($firstRow->ptk_id, $firstRow->kegiatan_id);
                } else {
                    $pelatihanPerPtk[$nip] = collect();
                }
            }

            // ============ BUAT EXCEL SAMA DENGAN PDF ============
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getDefaultStyle()->getFont()->setName('Helvetica')->setSize(10);

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Hasil Instrumen');

            // Set page setup landscape SAMA dengan PDF
            $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // ============ HEADER EXCEL SAMA DENGAN PDF ============
            $currentRow = 1;

            // Header utama
            $sheet->mergeCells("A$currentRow:G$currentRow");
            $sheet->setCellValue("A$currentRow", 'LAPORAN HASIL INSTRUMEN PTK DENGAN REKOMENDASI GAP ANALYSIS');
            $sheet->getStyle("A$currentRow")->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow++;
            $sheet->mergeCells("A$currentRow:G$currentRow");
            $sheet->setCellValue("A$currentRow", 'Penilaian Kompetensi Profesional Berbasis Level Kompetensi');
            $sheet->getStyle("A$currentRow")->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow++;
            $sheet->mergeCells("A$currentRow:G$currentRow");
            $sheet->setCellValue("A$currentRow", 'Dicetak: ' . now()->format('d F Y H:i:s'));
            $sheet->getStyle("A$currentRow")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow += 2;

            // Filter box SAMA dengan PDF
            $sheet->mergeCells("A$currentRow:G$currentRow");
            $sheet->setCellValue("A$currentRow", 'FILTER YANG DIGUNAKAN');
            $sheet->getStyle("A$currentRow")->applyFromArray([
                'font' => ['bold' => true, 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            $filters = [
                ['Pencarian:', $request->search ?: 'Semua'],
                ['Kegiatan:', $kegiatan_name ?: 'Semua'],
                ['Tahap:', $request->tahap ? "Tahap " . $request->tahap : 'Semua'],
                ['Jenjang Jabatan:', $jenjang_name ?: 'Semua'],
                ['Jenis PTK:', $jenis_ptk_name ?: 'Semua'],
                ['Jumlah PTK:', count($groupedData) . ' PTK'],
            ];

            foreach ($filters as $filter) {
                $currentRow++;
                $sheet->setCellValue("A$currentRow", $filter[0]);
                $sheet->setCellValue("B$currentRow", $filter[1]);
                $sheet->mergeCells("B$currentRow:G$currentRow");
                $sheet->getStyle("A$currentRow:G$currentRow")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9F9F9']]
                ]);
                $sheet->getStyle("A$currentRow")->applyFromArray(['font' => ['bold' => true]]);
            }

            $currentRow += 2;

            // ============ DATA PER PTK SAMA DENGAN PDF ============
            foreach ($groupedData as $nip => $dataRows) {
                if ($dataRows->isEmpty()) continue;

                $firstRow = $dataRows->first();

                // Proses rekomendasi untuk setiap row SAMA dengan PDF
                $processedRows = [];
                foreach ($dataRows as $row) {
                    // Gunakan fungsi getRekomendasiWithGap yang SAMA
                    $rekomendasiInfo = $this->getRekomendasiWithGap(
                        $row->jenjang_jabatan,
                        $row->level,
                        $row->sub_indikator_id,
                        $row->tahap,
                        $row->entity,
                        $row->sub_indikator_code
                    );

                    $row->rekomendasi_info = $rekomendasiInfo;
                    $processedRows[] = $row;
                }

                // Header PTK SAMA dengan PDF
                $sheet->mergeCells("A$currentRow:G$currentRow");
                $sheet->setCellValue("A$currentRow", $firstRow->nama ?? 'Nama tidak tersedia');
                $sheet->getStyle("A$currentRow")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2C3E50']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                // Kegiatan badge SAMA dengan PDF
                $sheet->setCellValue("F$currentRow", $firstRow->kegiatan_name ?? 'Kegiatan');
                $sheet->getStyle("F$currentRow")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E74C3C']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $currentRow++;

                // Info PTK SAMA dengan PDF
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

                $infoStartRow = $currentRow;
                foreach ($infoData as $index => $info) {
                    $sheet->setCellValue("A$currentRow", $info[0]);
                    $sheet->setCellValue("B$currentRow", $info[1]);
                    $sheet->mergeCells("B$currentRow:G$currentRow");

                    $bgColor = $index % 2 == 0 ? 'FFFFFF' : 'F9F9F9';
                    $sheet->getStyle("A$currentRow:G$currentRow")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                    $sheet->getStyle("A$currentRow")->applyFromArray(['font' => ['bold' => true]]);

                    $currentRow++;
                }

                // Merge kolom label info
                for ($i = $infoStartRow; $i < $currentRow; $i++) {
                    $sheet->mergeCells("A$i:A$i");
                }

                // Pelatihan section SAMA dengan PDF
                $pelatihanData = $pelatihanPerPtk[$nip] ?? collect();

                if ($pelatihanData->count() > 0) {
                    $currentRow++;
                    $sheet->mergeCells("A$currentRow:G$currentRow");
                    $sheet->setCellValue("A$currentRow", 'Pelatihan yang Anda Perlukan:');
                    $sheet->getStyle("A$currentRow")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '2C3E50']]
                    ]);

                    $currentRow++;
                    $pelStartRow = $currentRow;
                    foreach ($pelatihanData as $index => $pelatihan) {
                        $sheet->mergeCells("A$currentRow:G$currentRow");
                        $sheet->setCellValue(
                            "A$currentRow",
                            ($index + 1) . '. ' . ($pelatihan->nama_pelatihan_lengkap ?? 'Belum Tersedia') .
                                ' [' . ($pelatihan->kategori_pelatihan ?? 'Tidak Diketahui') . ']'
                        );
                        $sheet->getStyle("A$currentRow")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                            'font' => ['color' => ['rgb' => '1565C0']]
                        ]);
                        $currentRow++;
                    }
                } else {
                    $currentRow++;
                    $sheet->mergeCells("A$currentRow:G$currentRow");
                    $sheet->setCellValue("A$currentRow", 'Pelatihan yang Anda Perlukan:');
                    $sheet->getStyle("A$currentRow")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '2C3E50']]
                    ]);

                    $currentRow++;
                    $sheet->mergeCells("A$currentRow:G$currentRow");
                    $sheet->setCellValue("A$currentRow", 'Belum ada data pelatihan');
                    $sheet->getStyle("A$currentRow")->applyFromArray([
                        'font' => ['color' => ['rgb' => '666666']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                    ]);
                    $currentRow++;
                }

                $currentRow++;

                // Header tabel SAMA dengan PDF
                $headerRow = $currentRow;
                $headers = ['NO', 'KODE SUB INDIKATOR', 'NAMA SUB INDIKATOR', 'LEVEL DICAPAI', 'LEVEL HARUS', 'STATUS', 'REKOMENDASI (GAP)'];

                foreach ($headers as $col => $header) {
                    $columnLetter = chr(65 + $col);
                    $sheet->setCellValue($columnLetter . $currentRow, $header);
                    $sheet->getStyle($columnLetter . $currentRow)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '34495E']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '2C3E50']]]
                    ]);
                }

                $currentRow++;

                // Data indikator SAMA dengan PDF
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

                    // Status SAMA dengan PDF
                    $status = $info['status'] ?? '-';
                    $statusClass = $info['status_class'] ?? 'secondary';

                    // Rekomendasi gap SAMA dengan PDF
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

                    // Isi data
                    $sheet->setCellValue("A$currentRow", $indikatorNumber);
                    $sheet->setCellValue("B$currentRow", $row->sub_indikator_code);
                    $sheet->setCellValue("C$currentRow", $row->sub_indikator_name);
                    $sheet->setCellValue("D$currentRow", $row->level ? 'Level ' . $row->level : '-');
                    $sheet->setCellValue("E$currentRow", $levelHarus);
                    $sheet->setCellValue("F$currentRow", $status);
                    $sheet->setCellValue("G$currentRow", $rekText);

                    // Styling untuk baris data SAMA dengan PDF
                    $bgColor = $indikatorNumber % 2 == 0 ? 'FFFFFF' : 'F9F9F9';
                    $sheet->getStyle("A$currentRow:G$currentRow")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true]
                    ]);

                    // Alignment
                    $sheet->getStyle("A$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("E$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("F$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Styling untuk status SAMA dengan PDF
                    $statusStyles = [
                        'Mencapai Semua Level' => ['color' => '0F5132', 'bg' => 'D1E7DD'],
                        'Mendekati Target' => ['color' => '664D03', 'bg' => 'FFF3CD'],
                        'Perlu Peningkatan' => ['color' => '842029', 'bg' => 'F8D7DA']
                    ];

                    if (isset($statusStyles[$status])) {
                        $style = $statusStyles[$status];
                        $sheet->getStyle("F$currentRow")->applyFromArray([
                            'font' => ['color' => ['rgb' => $style['color']]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $style['bg']]],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                        ]);
                    }

                    // Styling untuk level dicapai SAMA dengan PDF
                    if ($row->level) {
                        $levelColors = [
                            2 => ['color' => 'FFFFFF', 'bg' => '17A2B8'],
                            3 => ['color' => 'FFFFFF', 'bg' => '007BFF'],
                            4 => ['color' => 'FFFFFF', 'bg' => 'FFC107'],
                            5 => ['color' => 'FFFFFF', 'bg' => '28A745']
                        ];

                        $levelColor = $levelColors[$row->level] ?? ['color' => 'FFFFFF', 'bg' => '6C757D'];
                        $sheet->getStyle("D$currentRow")->applyFromArray([
                            'font' => ['color' => ['rgb' => $levelColor['color']], 'bold' => true],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $levelColor['bg']]],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                        ]);
                    }

                    // Styling untuk level harus SAMA dengan PDF
                    $sheet->getStyle("E$currentRow")->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                    ]);

                    $currentRow++;
                    $indikatorNumber++;
                }

                // Summary PTK SAMA dengan PDF
                $summaryRow = $currentRow;
                $sheet->mergeCells("A$currentRow:G$currentRow");
                $sheet->setCellValue("A$currentRow", "SUMMARY: " . count($processedRows) . " Sub indikator dinilai");
                $sheet->getStyle("A$currentRow")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '2C3E50']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $currentRow += 3; // Spasi antar PTK

                // Free memory untuk data besar
                unset($processedRows, $dataRows);
                if ($currentRow % 100 === 0) {
                    gc_collect_cycles();
                }
            }

            // Footer SAMA dengan PDF
            $sheet->mergeCells("A$currentRow:G$currentRow");
            $sheet->setCellValue("A$currentRow", 'Catatan: Dokumen untuk keperluan internal evaluasi');
            $sheet->getStyle("A$currentRow")->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow++;
            $sheet->mergeCells("A$currentRow:G$currentRow");
            $sheet->setCellValue("A$currentRow", 'Laporan ini menunjukkan gap antara level kompetensi yang dicapai dengan level yang harus dicapai berdasarkan jenjang jabatan');
            $sheet->getStyle("A$currentRow")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow++;
            $sheet->mergeCells("A$currentRow:G$currentRow");
            $sheet->setCellValue("A$currentRow", '© ' . date('Y') . ' - Sistem TanpaRagu | Dicetak: ' . now()->format('d F Y H:i:s'));
            $sheet->getStyle("A$currentRow")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            // Set column widths optimal
            $sheet->getColumnDimension('A')->setWidth(6);   // NO
            $sheet->getColumnDimension('B')->setWidth(15);  // KODE
            $sheet->getColumnDimension('C')->setWidth(35);  // INDIKATOR
            $sheet->getColumnDimension('D')->setWidth(12);  // LEVEL DICAPAI
            $sheet->getColumnDimension('E')->setWidth(12);  // LEVEL HARUS
            $sheet->getColumnDimension('F')->setWidth(18);  // STATUS
            $sheet->getColumnDimension('G')->setWidth(50);  // REKOMENDASI

            // Auto wrap text untuk kolom rekomendasi
            $sheet->getStyle('G')->getAlignment()->setWrapText(true);

            // Output file
            $filename = 'hasil-instrumen-filter-' . date('Ymd-His') . '.xlsx';

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

            // Clean up
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet, $writer);

            exit;
        } catch (\Exception $e) {
            \Log::error('Export Excel Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }












    /**
     * Fungsi untuk mendapatkan data pelatihan PTK
     */
    private function getPelatihanByPtk($ptkId, $kegiatanId)
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

    /**
     * Fungsi utama untuk mendapatkan rekomendasi dengan GAP level
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
}
