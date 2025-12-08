<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UiController extends Controller
{
    public function index()
    {
        return view('ui.quiz', [
            'tittle' => 'User Interface',
            'kegiatan' => null, // atau tidak kirim sekalian
        ]);
    }
}
