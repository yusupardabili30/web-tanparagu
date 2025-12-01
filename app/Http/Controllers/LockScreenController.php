<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\User;
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
            'user_name' => 'required',
            'password' => 'required',
            'kegiatan_id' => 'required'
        ]);

        // Decode kegiatan_id
        if (count(Hashids::decode($request->kegiatan_id)) === 0) {
            return back()->withErrors(['error' => 'Invalid kegiatan ID']);
        }

        $kegiatan_id = Hashids::decode($request->kegiatan_id)[0];

        // Cari kegiatan yang aktif
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'Active')
            ->first();

        if (!$kegiatan) {
            return back()->withErrors(['error' => 'Kegiatan tidak aktif atau tidak ditemukan']);
        }

        // Verifikasi token dari kegiatan
        if ($request->password !== $kegiatan->instrumen_token) {
            return back()->withErrors(['password' => 'Token tidak valid']);
        }

        // Cari user berdasarkan user_name/NIP
        $user = User::where('user_name', $request->user_name)
            ->orWhere('nip', $request->user_name)
            ->first();

        if (!$user) {
            return back()->withErrors(['user_name' => 'NIP tidak ditemukan']);
        }

        // Redirect ke halaman PTK dengan NIK
        // Simpan user_id dan kegiatan_id di session untuk digunakan di PTK Controller jika diperlukan
        session([
            'lockscreen_user_id' => $user->user_id,
            'lockscreen_kegiatan_id' => $kegiatan->kegiatan_id
        ]);

        return redirect()->route('ptk.search', $user->nik);
    }
}
