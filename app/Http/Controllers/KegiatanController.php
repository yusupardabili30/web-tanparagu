<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::orderBy('created_at', 'desc')->paginate(10);

        return view('kegiatan.index', [
            'tittle' => 'Kegiatan',
            'data' => $kegiatan
        ]);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $token = Kegiatan::generateToken();

        try {
            $isUpdate = $request->has('kegiatan_id') && !empty($request->kegiatan_id);

            Kegiatan::updateOrCreate([
                'kegiatan_id' => $request->kegiatan_id
            ], [
                'kegiatan_name' => $request->kegiatan,
                'entity' => $request->entity,
                'tahap' => $request->tahap, // Tambahkan ini
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status_id,
                'instrumen_token' => $token,
                'created_at' => $isUpdate ? Kegiatan::where('kegiatan_id', $request->kegiatan_id)->first()->created_at : Carbon::now()->format('Y-m-d H:i:s'),
                'modified_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            $message = $isUpdate ? 'Data berhasil diperbarui' : 'Data berhasil ditambahkan';
            return redirect()->route('kegiatan.index')->with('success', $message);
        } catch (\Throwable $e) {
            $errorMessage = 'Terjadi kesalahan saat menyimpan data';
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function get($kegiatan_id)
    {
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)->first();
        if (!$kegiatan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($kegiatan);
    }

    public function delete($kegiatan_id)
    {
        try {
            $kegiatan = Kegiatan::find($kegiatan_id);

            if (!$kegiatan) {
                return response()->json(['success' => false, 'message' => 'Data Kegiatan tidak ditemukan'], 404);
            }

            $kegiatan->delete();
            return response()->json(['success' => true, 'message' => 'Data Kegiatan berhasil dihapus'], 200);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data'], 500);
        }
    }

    // Method untuk reset token
    public function resetToken($kegiatan_id)
    {
        try {
            $kegiatan = Kegiatan::find($kegiatan_id);

            if (!$kegiatan) {
                return response()->json(['success' => false, 'message' => 'Data Kegiatan tidak ditemukan'], 404);
            }

            $kegiatan->resetToken();

            return response()->json([
                'success' => true,
                'message' => 'Token berhasil direset',
                'new_token' => $kegiatan->instrumen_token
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mereset token'], 500);
        }
    }
}
