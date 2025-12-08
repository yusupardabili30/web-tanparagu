<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstrumenController extends Controller
{
    public function index(){
        return view('instrumen.index', [
            'tittle'=>'Instrumen'
        ]);
    }
}
