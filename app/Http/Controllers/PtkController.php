<?php

namespace App\Http\Controllers;

use App\Models\Ptk;
use Illuminate\Http\Request;

class PtkController extends Controller
{
    public function index(){
        $ptk = Ptk::all();
        return view('ptk.index', [
            'tittle'=>'Data Ptk',
            'data'=>$ptk
        ]);
    }

    public function search($nik){
        $ptk = Ptk::where('nik',$nik)->first();
        //return $ptk;
        return view('ptk.index', [
            'tittle'=>'Data Ptk',
            'data'=>$ptk
        ]);
    }
}
