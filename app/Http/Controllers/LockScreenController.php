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

        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'Active')->first();

        if (!$kegiatan) {
            abort(404);
        }

        return view('lockscreen.index', [
            'title' => 'Lock Screen',
            'kegiatan_id' => $encode_kegiatan_id,
            'kegiatan' => $kegiatan
        ]);
    }

    public function unlock_screen(Request $request)
    {
        $request->validate([

            'password' => 'required',
            'kegiatan_id' => 'required'
        ]);

        // Decode kegiatan_id
        if (count(Hashids::decode($request->kegiatan_id)) === 0) {
            return back()->withErrors(['error' => 'ID kegiatan tidak valid'])->withInput();
        }

        $kegiatan_id = Hashids::decode($request->kegiatan_id)[0];

        // Cari kegiatan yang aktif
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'Active')
            ->first();

        if (!$kegiatan) {
            return back()->withErrors(['error' => 'Kegiatan tidak aktif atau tidak ditemukan'])->withInput();
        }

        // Verifikasi token dari kegiatan
        if ($request->password !== $kegiatan->instrumen_token) {
            return back()->withErrors(['password' => 'Token tidak valid'])->withInput();
        }

        // Simpan informasi di session
        session([
            'lockscreen_authenticated' => true,
            'lockscreen_kegiatan_id' => $kegiatan->kegiatan_id,
            'kegiatan_name' => $kegiatan->kegiatan_name,
            'kegiatan_token' => $kegiatan->instrumen_token
        ]);

        // Redirect langsung ke halaman PTK tanpa parameter
        return redirect()->route('ptk');
    }

    // Tambahkan method logout
    public function logout()
    {
        session()->forget([
            'lockscreen_authenticated',
            'lockscreen_kegiatan_id',
            'kegiatan_name',
            'kegiatan_token'
        ]);

        return redirect('/')->with('info', 'Anda telah logout dari lockscreen');
    }
}
