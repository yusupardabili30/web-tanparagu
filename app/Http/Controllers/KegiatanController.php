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

        try{
            Kegiatan::updateOrCreate([
                'kegiatan_id' => $request->kegiatan_id
            ],[
                    'kegiatan_name' => $request->kegiatan,
                    'entity' => $request->entity,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'status' => $request->status_id,
                    'instrumen_token' => $token,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'modified_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            return redirect()->route('kegiatan.index')->with('success', 'Data berhasil ditambahkan');

        }catch(Throwable $e){
            // dd($e);
            $errorMessage = 'Data berhasil ditambahkan';
            return redirect()->back()->with('error', $errorMessage);
        }

        // try {
        //     // Jika kegiatan_id kosong, berarti create baru
        //     if (empty($request->kegiatan_id)) {
        //         // Generate token dan URL sekaligus
        //         $token = Kegiatan::generateToken();

        //         $data = [
        //             'kegiatan_name' => $request->kegiatan,
        //             'entity' => $request->entity,
        //             'start_date' => $request->start_date,
        //             'end_date' => $request->end_date,
        //             'status' => $request->status_id,
        //             'instrumen_token' => $token,
        //             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //             'modified_at' => Carbon::now()->format('Y-m-d H:i:s')
        //         ];

        //         // Create data sekali saja
        //         $kegiatan = Kegiatan::create($data);

        //         // Update URL dengan kegiatan_id yang sebenarnya
        //         $kegiatan->instrumen_url = Kegiatan::generateUrl($kegiatan->kegiatan_id);
        //         $kegiatan->save();

        //         $message = 'Data berhasil ditambahkan';
        //     } else {
        //         // Untuk update existing data
        //         $data = [
        //             'kegiatan_name' => $request->kegiatan,
        //             'entity' => $request->entity,
        //             'start_date' => $request->start_date,
        //             'end_date' => $request->end_date,
        //             'status' => $request->status,
        //             'modified_at' => Carbon::now()->format('Y-m-d H:i:s')
        //         ];

        //         $kegiatan = Kegiatan::updateOrCreate(
        //             ['kegiatan_id' => $request->kegiatan_id],
        //             $data
        //         );

        //         $message = 'Data berhasil diubah';
        //     }

        //     // Jika request AJAX, return JSON
        //     if ($request->ajax()) {
        //         return response()->json([
        //             'success' => true,
        //             'message' => $message
        //         ]);
        //     }

        //     return redirect()->route('kegiatan.index')->with('success', $message);
        // } catch (\Throwable $e) {
        //     $errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();

        //     // Jika request AJAX, return JSON error
        //     if ($request->ajax()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => $errorMessage
        //         ], 500);
        //     }

        //     return redirect()->back()->with('error', $errorMessage);
        // }
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
        $kegiatan = Kegiatan::find($kegiatan_id);

        if (!$kegiatan) {
            return response()->json(['message' => 'Data Kegiatan tidak ditemukan'], 404);
        }
        $kegiatan->delete();
        return response()->json(['message' => 'Data Kegiatan berhasil dihapus'], 200);
    }

    // Method untuk reset token
    public function resetToken($kegiatan_id)
    {
        $kegiatan = Kegiatan::find($kegiatan_id);

        if (!$kegiatan) {
            return response()->json(['message' => 'Data Kegiatan tidak ditemukan'], 404);
        }

        $kegiatan->resetToken();

        return response()->json([
            'message' => 'Token berhasil direset',
            'new_token' => $kegiatan->instrumen_token
        ], 200);
    }
}
