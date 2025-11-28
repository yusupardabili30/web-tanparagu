<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class LockScreenController extends Controller
{
    public function index($encode_kegiatan_id)
    {
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            abort(404); 
        }
        $kegiatan_id= Hashids::decode($encode_kegiatan_id)[0];
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
                            ->where('status','Active')->first();
        if (!$kegiatan) {
             abort(404);
        }
        return view('lockscreen.index',[
            'title' => 'Lock Screen'
        ]);
    }

    public function unlock_screen(Request $request)
    {

        if ($request->nik) {
            # code...
        } else {
            # code...
        }
        

        return redirect()->route('ptk.search', $request->nik);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
