<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BiodataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tittle = 'Biodata PTK';

        // Query dengan JOIN
        $query = DB::table('peserta')
            ->select(
                'peserta.peserta_id',
                'peserta.nama',
                'peserta.nik',
                'peserta.nip',
                'peserta.email',
                'peserta.no_hp',
                'peserta.npwp',
                'peserta.no_rekening',
                'peserta.atas_nama_rekening',
                'peserta.tempat_lahir',
                'peserta.tgl_lahir',
                'peserta.pendidikan',
                'peserta.instansi',
                'peserta.alamat_kantor',
                'peserta.provinsi',
                'peserta.last_update',
                'peserta.agama',
                'peserta.jenis_kelamin',

                // Data relasi
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                'kota.nama_kota',
                'sekolah.nama_sekolah',
                'sekolah.npsn',
                'ms_bank.nama_bank',
                'kegiatan.kegiatan_name'
            )
            ->join('kegiatan', 'peserta.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('pangkat_jabatan', 'peserta.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'peserta.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'peserta.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('ms_bank', 'peserta.ms_bank_id', '=', 'ms_bank.ms_bank_id');

        // Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('peserta.nip', 'like', "%{$search}%")
                    ->orWhere('peserta.nama', 'like', "%{$search}%")
                    ->orWhere('peserta.nik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kegiatan_id')) {
            $query->where('peserta.kegiatan_id', $request->kegiatan_id);
        }

        $data = $query->orderBy('peserta.nama', 'asc')->paginate(10);

        // Data dropdown
        $kegiatans = DB::table('kegiatan')->get();

        return view('biodata.index', compact('tittle', 'data', 'kegiatans'));
    }


    /**
     * Export PDF per peserta
     */
    public function exportPdf($id)
    {
        $data = DB::table('peserta')
            ->select(
                'peserta.*',
                'pangkat_jabatan.golongan_ruang',
                'pangkat_jabatan.pangkat',
                'pangkat_jabatan.jenjang_jabatan',
                'kota.nama_kota',
                'sekolah.nama_sekolah',
                'sekolah.npsn',
                'sekolah.alamat as alamat_sekolah',
                'ms_bank.nama_bank',
                'ms_bank.kode_bank',
                'kegiatan.kegiatan_name',
                'kegiatan.start_date',
                'kegiatan.end_date',
                // Ambil TTD dari peserta jika ada, jika tidak dari ptk
                DB::raw('COALESCE(peserta.ttd_base64, ptk.ttd_base64) as ttd_base64'),
                'peserta.nama as peserta_nama' // Nama untuk bagian mengetahui
            )
            ->join('kegiatan', 'peserta.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->leftJoin('pangkat_jabatan', 'peserta.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
            ->leftJoin('kota', 'peserta.kota_id', '=', 'kota.kota_id')
            ->leftJoin('sekolah', 'peserta.sekolah_id', '=', 'sekolah.sekolah_id')
            ->leftJoin('ms_bank', 'peserta.ms_bank_id', '=', 'ms_bank.ms_bank_id')
            // Join dengan ptk untuk ambil TTD jika tidak ada di peserta
            ->leftJoin('ptk', function ($join) {
                $join->on('peserta.nip', '=', 'ptk.nip')
                    ->orWhere('peserta.nik', '=', 'ptk.nik');
            })
            ->where('peserta.peserta_id', $id)
            ->first();

        if (!$data) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // Format data untuk PDF
        $data->tgl_lahir_formatted = $data->tgl_lahir ? date('d-m-Y', strtotime($data->tgl_lahir)) : '-';
        $data->last_update_formatted = $data->last_update ? date('d-m-Y H:i:s', strtotime($data->last_update)) : '-';
        $data->jenis_kelamin_formatted = $data->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
        $data->start_date_formatted = $data->start_date ? date('d-m-Y', strtotime($data->start_date)) : '-';
        $data->end_date_formatted = $data->end_date ? date('d-m-Y', strtotime($data->end_date)) : '-';

        // Cek jika ada TTD
        $data->has_signature = !empty($data->ttd_base64);

        $pdf = Pdf::loadView('biodata.pdf-single', compact('data'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

        $filename = 'biodata-' . $data->nip . '-' . date('YmdHis') . '.pdf';
        return $pdf->download($filename);
    }
    /**
     * Export PDF semua data DENGAN FILTER
     */
    public function exportAllPdf(Request $request)
    {
        try {
            \Log::info('Export All PDF dipanggil dengan parameter:', $request->all());

            // Query dengan filter yang sama seperti index
            $query = DB::table('peserta')
                ->select(
                    'peserta.peserta_id',
                    'peserta.nama',
                    'peserta.nik',
                    'peserta.nip',
                    'peserta.email',
                    'peserta.no_hp',
                    'peserta.npwp',
                    'peserta.no_rekening',
                    'peserta.atas_nama_rekening',
                    'peserta.tempat_lahir',
                    'peserta.tgl_lahir',
                    'peserta.pendidikan',
                    'peserta.instansi',
                    'peserta.alamat_kantor',
                    'peserta.provinsi',
                    'peserta.last_update',
                    'peserta.agama',
                    'peserta.jenis_kelamin',

                    // Data relasi
                    'pangkat_jabatan.golongan_ruang',
                    'pangkat_jabatan.pangkat',
                    'pangkat_jabatan.jenjang_jabatan',
                    'kota.nama_kota',
                    'sekolah.nama_sekolah',
                    'sekolah.npsn',
                    'ms_bank.nama_bank',
                    'kegiatan.kegiatan_name'
                )
                ->join('kegiatan', 'peserta.kegiatan_id', '=', 'kegiatan.kegiatan_id')
                ->leftJoin('pangkat_jabatan', 'peserta.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('kota', 'peserta.kota_id', '=', 'kota.kota_id')
                ->leftJoin('sekolah', 'peserta.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('ms_bank', 'peserta.ms_bank_id', '=', 'ms_bank.ms_bank_id');

            // Filter pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('peserta.nip', 'like', "%{$search}%")
                        ->orWhere('peserta.nama', 'like', "%{$search}%")
                        ->orWhere('peserta.nik', 'like', "%{$search}%");
                });
            }

            if ($request->filled('kegiatan_id')) {
                $query->where('peserta.kegiatan_id', $request->kegiatan_id);
            }

            $data = $query->orderBy('peserta.nama', 'asc')->get();

            if ($data->isEmpty()) {
                return redirect()->route('biodata.index')
                    ->with('error', 'Tidak ada data untuk diexport')
                    ->withInput();
            }

            // Ambil info filter untuk ditampilkan di PDF
            $filterInfo = [];
            if ($request->filled('search')) {
                $filterInfo['search'] = $request->search;
            }
            if ($request->filled('kegiatan_id')) {
                $kegiatan = DB::table('kegiatan')->where('kegiatan_id', $request->kegiatan_id)->first();
                $filterInfo['kegiatan'] = $kegiatan ? $kegiatan->kegiatan_name : null;
            }

            // Hitung statistik
            $totalData = $data->count();
            $exportDate = date('d-m-Y H:i:s');

            $pdf = Pdf::loadView('biodata.pdf-all', [
                'data' => $data,
                'filterInfo' => $filterInfo,
                'totalData' => $totalData,
                'exportDate' => $exportDate
            ])->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);

            // Set nama file
            $filename = 'biodata-ptk-' . date('Ymd-His');
            if ($request->filled('search')) {
                $filename .= '-search-' . substr(str_replace(' ', '_', $request->search), 0, 20);
            }
            if ($request->filled('kegiatan_id')) {
                $filename .= '-kegiatan-' . $request->kegiatan_id;
            }
            $filename .= '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting PDF: ' . $e->getMessage());
            return redirect()->route('biodata.index')
                ->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage())
                ->withInput();
        }
    }
}
