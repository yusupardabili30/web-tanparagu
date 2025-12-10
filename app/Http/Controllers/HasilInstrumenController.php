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
                'ptk_jawaban.level',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.sub_indikator_id',
                'ptk_jawaban.bobot',
                'ptk_jawaban.created_at',
                'ptk.nama',
                'ptk.nip',
                'ptk.pangkat_jabatan_id',
                'ptk.instansi',
                'ptk.kota_id',
                // Ambil data dari tabel pangkat_jabatan
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                // Ambil data dari tabel kota
                'kota.nama_kota',
                // Ambil data dari tabel sub_indikator
                'sub_indikator.sub_indikator_name',
                'kegiatan.kegiatan_name',
                'kegiatan.entity'
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id'); // JOIN ke tabel sub_indikator

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ptk.nip', 'like', "%{$search}%")
                    ->orWhere('ptk.nama', 'like', "%{$search}%")
                    ->orWhere('pangkat_jabatan.pangkat', 'like', "%{$search}%")
                    ->orWhere('pangkat_jabatan.jenjang_jabatan', 'like', "%{$search}%")
                    ->orWhere('kota.nama_kota', 'like', "%{$search}%")
                    ->orWhere('sub_indikator.sub_indikator_name', 'like', "%{$search}%"); // Tambahkan pencarian sub_indikator_name
            });
        }

        if ($request->filled('kegiatan_id')) {
            $query->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
        }

        if ($request->filled('tahap')) {
            $query->where('ptk_jawaban.tahap', $request->tahap);
        }

        $data = $query->orderBy('ptk_jawaban.ptk_jawaban_id', 'desc')->paginate(10);

        // Ambil semua kegiatan untuk dropdown
        $kegiatans = DB::table('kegiatan')->get();

        return view('hasil.index', compact('tittle', 'data', 'kegiatans'));
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
                'ptk_jawaban.level',
                'ptk_jawaban.sub_indikator_code',
                'ptk_jawaban.bobot',
                'ptk_jawaban.created_at',
                'ptk.nama',
                'ptk.nip',
                'ptk.pangkat_jabatan_id',
                'ptk.instansi',
                'ptk.email',
                'ptk.no_hp',
                // Ambil data dari tabel pangkat_jabatan
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                'kegiatan.kegiatan_name',
                'kegiatan.entity'
            )
            ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
            ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->where('ptk_jawaban.ptk_id', $ptk_id)
            ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $totalSkor = $data->sum('bobot');
        $totalIndikator = $data->count();
        $ptk = $data->first(); // Ambil data PTK dari hasil pertama

        // Cek apakah DomPDF terinstall
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
            // Jika DomPDF tidak terinstall, kembalikan ke view HTML
            return view('hasil.export-pdf', [
                'data' => $data,
                'ptk' => $ptk,
                'totalSkor' => $totalSkor,
                'totalIndikator' => $totalIndikator,
                'tanggal' => now()->format('d F Y H:i:s')
            ]);
        }
    }

    /**
     * Export PDF semua data dengan filter
     */

    /**
     * Export PDF semua data dengan filter
     */
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
                    'ptk_jawaban.sub_indikator_id', // Tambahkan ini
                    'ptk_jawaban.bobot', // Opsional: bisa dihapus nanti
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
                    'kegiatan.entity'
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
                ->leftJoin('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
                ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id'); // JOIN ke tabel sub_indikator

            // Filter pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('ptk.nip', 'like', "%{$search}%")
                        ->orWhere('ptk.nama', 'like', "%{$search}%")
                        ->orWhere('pangkat_jabatan.pangkat', 'like', "%{$search}%")
                        ->orWhere('pangkat_jabatan.jenjang_jabatan', 'like', "%{$search}%")
                        ->orWhere('kota.nama_kota', 'like', "%{$search}%")
                        ->orWhere('sub_indikator.sub_indikator_name', 'like', "%{$search}%"); // Tambahkan pencarian sub_indikator
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

            if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                return redirect()->back()->with('error', 'Fitur PDF belum tersedia. Silakan install package DomPDF.');
            }

            $viewPath = 'hasil.export-all-pdf';
            if (!view()->exists($viewPath)) {
                return redirect()->back()->with('error', 'Template PDF tidak ditemukan.');
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewPath, [
                'groupedData' => $groupedData,
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
}
