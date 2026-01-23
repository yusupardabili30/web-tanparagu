<?php

namespace App\Http\Controllers;

use App\Models\Ptk;
use App\Models\MsPelatihan;
use App\Models\PtkPelatihan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class PelatihanController extends Controller
{
    public function index($encoded_kegiatan_id, $nip)
    {
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;
        $ptk = Ptk::where('nip', $nip)->first();
        $kegiatan = Kegiatan::find($kegiatan_id);

        if (!$ptk || !$kegiatan) {
            return redirect()->route('quiz.finish', [
                'encoded_kegiatan_id' => $encoded_kegiatan_id,
                'nip' => $nip
            ])->with('error', 'Data tidak ditemukan');
        }

        // Ambil data dari tabel ms_pelatihan berdasarkan entity kegiatan
        $masterPelatihan = MsPelatihan::byEntity($kegiatan->entity)
            ->orderBy('nama_pelatihan')
            ->get();

        // Ambil data pelatihan yang sudah dipilih sebelumnya
        $pelatihanTerpilih = PtkPelatihan::where('ptk_id', $ptk->ptk_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->get();

        $selectedPelatihanIds = [];
        $pelatihanLainnya = '';

        foreach ($pelatihanTerpilih as $item) {
            if ($item->ms_pelatihan_id) {
                $selectedPelatihanIds[] = $item->ms_pelatihan_id;
            }
            if ($item->pelatihan_lainnya) {
                $pelatihanLainnya = $item->pelatihan_lainnya;
            }
        }

        return view('pelatihan.index', [
            'ptk' => $ptk,
            'kegiatan' => $kegiatan,
            'kegiatan_id' => $kegiatan_id,
            'encoded_kegiatan_id' => $encoded_kegiatan_id,
            'nip' => $nip,
            'masterPelatihan' => $masterPelatihan,
            'selectedPelatihanIds' => $selectedPelatihanIds,
            'pelatihanLainnya' => $pelatihanLainnya
        ]);
    }

    public function store(Request $request, $encoded_kegiatan_id, $nip)
    {
        $kegiatan_id = Hashids::decode($encoded_kegiatan_id)[0] ?? 0;
        $ptk = Ptk::where('nip', $nip)->first();
        $kegiatan = Kegiatan::find($kegiatan_id);

        if (!$ptk || !$kegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Data PTK atau Kegiatan tidak ditemukan'
            ], 404);
        }

        try {
            // Validasi
            $pelatihanIds = $request->input('pelatihan_pilihan', []);
            $pelatihanLainnya = $request->input('pelatihan_lainnya', '');

            if (empty($pelatihanIds) && empty($pelatihanLainnya)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan pilih minimal satu pelatihan'
                ], 400);
            }

            // Hapus data lama
            PtkPelatihan::where('ptk_id', $ptk->ptk_id)
                ->where('kegiatan_id', $kegiatan_id)
                ->delete();

            // Simpan pelatihan yang dipilih dari daftar
            if (!empty($pelatihanIds)) {
                foreach ($pelatihanIds as $pelatihanId) {
                    PtkPelatihan::create([
                        'ptk_id' => $ptk->ptk_id,
                        'kegiatan_id' => $kegiatan_id,
                        'ms_pelatihan_id' => $pelatihanId,
                        'created_at' => now()
                    ]);
                }
            }

            // Simpan pelatihan lainnya jika ada
            if (!empty(trim($pelatihanLainnya))) {
                PtkPelatihan::create([
                    'ptk_id' => $ptk->ptk_id,
                    'kegiatan_id' => $kegiatan_id,
                    'pelatihan_lainnya' => trim($pelatihanLainnya),
                    'created_at' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pelatihan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
