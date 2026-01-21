<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HasilInstrumenExport;

class HasilInstrumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tittle = 'Hasil Instrumen PTK';

        // Query dengan INNER JOIN termasuk tabel pangkat_jabatan, kota, dan sub_indikator
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
                'ptk.ptk_id',
                'ptk.pangkat_jabatan_id',
                'ptk.instansi',
                'ptk.kota_id',
                // Ambil data dari tabel pangkat_jabatan
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                'pangkat_jabatan.level_kompetensi',
                // Ambil data dari tabel kota
                'kota.nama_kota',
                // Ambil data dari tabel sub_indikator
                'sub_indikator.sub_indikator_name',
                'kegiatan.kegiatan_name',
                'kegiatan.entity',
                'kegiatan.kegiatan_id', // ← TAMBAHKAN INI (penting!)
                'ptk_jawaban.kegiatan_id as jawaban_kegiatan_id'
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ptk.nip', 'like', "%{$search}%")
                    ->orWhere('ptk.nama', 'like', "%{$search}%")
                    ->orWhere('pangkat_jabatan.pangkat', 'like', "%{$search}%")
                    ->orWhere('pangkat_jabatan.jenjang_jabatan', 'like', "%{$search}%")
                    ->orWhere('kota.nama_kota', 'like', "%{$search}%")
                    ->orWhere('sub_indikator.sub_indikator_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kegiatan_id')) {
            $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        }

        if ($request->filled('tahap')) {
            $query->where('ptk_jawaban.tahap', $request->tahap);
        }

        $data = $query->orderBy('ptk_jawaban.ptk_jawaban_id', 'desc')->paginate(10);

        // Untuk setiap data, tambahkan rekomendasi dengan gap
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


            // TAMBAHKAN: Ambil data pelatihan untuk PTK ini
            // Gunakan jawaban_kegiatan_id jika tersedia, jika tidak gunakan kegiatan_id
            $kegiatanId = $item->jawaban_kegiatan_id ?? $item->kegiatan_id;
            if (isset($item->ptk_id) && isset($kegiatanId)) {
                $item->pelatihan = $this->getPelatihanByPtk($item->ptk_id, $kegiatanId);

                // DEBUG: Cek apakah data ditemukan
                \Log::info('Data pelatihan untuk PTK:', [
                    'ptk_id' => $item->ptk_id,
                    'kegiatan_id' => $kegiatanId,
                    'jumlah_pelatihan' => $item->pelatihan->count(),
                    'nama' => $item->nama
                ]);
            } else {
                $item->pelatihan = collect(); // return empty collection jika data tidak lengkap
                \Log::warning('Data ptk_id atau kegiatan_id tidak lengkap:', [
                    'ptk_id' => $item->ptk_id ?? 'null',
                    'kegiatan_id' => $kegiatanId ?? 'null',
                    'nama' => $item->nama ?? 'null'
                ]);
            }
        }


        // Ambil semua kegiatan untuk dropdown
        $kegiatans = DB::table('kegiatan')->get();

        return view('hasil.index', compact('tittle', 'data', 'kegiatans'));
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
                'ptk.ptk_id', // ← TAMBAHKAN ini untuk join dengan pelatihan
                'ptk.pangkat_jabatan_id',
                'ptk.instansi',
                'ptk.email',
                'ptk.no_hp',
                // Ambil data dari tabel pangkat_jabatan
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                'pangkat_jabatan.level_kompetensi',
                // Ambil data sub indikator
                'sub_indikator.sub_indikator_name',
                'kegiatan.kegiatan_name',
                'kegiatan.entity',
                'kegiatan.kegiatan_id' // ← TAMBAHKAN untuk join dengan pelatihan
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
            ->where('ptk_jawaban.ptk_id', $ptk_id)
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


            // TAMBAHKAN: Ambil data pelatihan untuk PTK ini
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
                    'kegiatan.kegiatan_id'
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
                ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
                ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id');

            // Filter pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('ptk.nip', 'like', "%{$search}%")
                        ->orWhere('ptk.nama', 'like', "%{$search}%")
                        ->orWhere('pangkat_jabatan.pangkat', 'like', "%{$search}%")
                        ->orWhere('pangkat_jabatan.jenjang_jabatan', 'like', "%{$search}%")
                        ->orWhere('kota.nama_kota', 'like', "%{$search}%")
                        ->orWhere('sub_indikator.sub_indikator_name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('kegiatan_id')) {
                $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            }

            if ($request->filled('tahap')) {
                $query->where('ptk_jawaban.tahap', $request->tahap);
            }

            $data = $query->orderBy('ptk.nama', 'asc')->get();

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
                'pelatihanPerPtk' => $pelatihanPerPtk, // Tambahkan ini
                'search' => $request->search,
                'kegiatan_id' => $request->kegiatan_id,
                'kegiatan_name' => $kegiatan_name,
                'tahap' => $request->tahap,
                'tanggal' => now()->format('d F Y H:i:s')
            ]);

            return $pdf->download('hasil-instrumen-filter-' . date('Ymd-His') . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function exportExcel(Request $request)
    {
        $filename = 'hasil-instrumen-' . date('Ymd-His') . '.xlsx';

        // Query dengan INNER JOIN untuk export Excel
        $query = DB::table('ptk_jawaban')
            ->select(
                'ptk_jawaban.ptk_jawaban_id',
                'ptk_jawaban.tahap',
                'ptk_jawaban.level',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.bobot',
                'ptk_jawaban.created_at',
                'ptk.nama',
                'ptk.nip',
                'ptk.pangkat_jabatan_id',
                'ptk.instansi',
                // Ambil data dari tabel pangkat_jabatan
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                'kegiatan.kegiatan_name',
                'kegiatan.entity'
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ptk.nip', 'like', "%{$search}%")
                    ->orWhere('ptk.nama', 'like', "%{$search}%")
                    ->orWhere('pangkat_jabatan.pangkat', 'like', "%{$search}%")
                    ->orWhere('pangkat_jabatan.jenjang_jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kegiatan_id')) {
            $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        }

        if ($request->filled('tahap')) {
            $query->where('ptk_jawaban.tahap', $request->tahap);
        }

        $data = $query->orderBy('ptk.nama', 'asc')->get();

        // Jika menggunakan Maatwebsite/Excel
        if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
            return Excel::download(new HasilInstrumenExport($data), $filename);
        } else {
            // Jika Excel tidak terinstall, kembalikan error
            return redirect()->back()->with('error', 'Fitur export Excel belum tersedia. Silakan install Maatwebsite/Excel.');
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
}
