<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(){
        $kegiatan = Kegiatan::paginate(10);
        return view('kegiatan.index', [
            'tittle'=>'Kegiatan',
            'data'=>$kegiatan
        ]);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        //return $request;
        try{
            Kegiatan::updateOrCreate([
                'kegiatan_id' => $request->kegiatan_id
            ],[
                'kegiatan_name' => $request->kegiatan,
                'modified_at'=> date('Y-m-d H:i:s')

            ]);
            return redirect()->route('kegiatan.index')->with('success','berhasil disimpan');

        }catch(Throwable $e){
            dd($e);
        }
    }

    public function get($kegiatan_id){
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)->first();
        return response()->json($kegiatan);
    }

    public function delete($kegiatan_id) {
        $kegiatan = Kegiatan::find($kegiatan_id); 

        if (!$kegiatan) {
            return response()->json(['message' => 'Data Kegiatan tidak ditemukan'], 404);
        }
        $kegiatan->delete();
        return response()->json(['message' => 'Data Kegiatan berhasil dihapus'], 200);
    }
}
