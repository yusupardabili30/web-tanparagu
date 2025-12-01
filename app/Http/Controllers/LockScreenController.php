<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class LockScreenController extends Controller
{
    /**
     * Menampilkan halaman lockscreen
     */
    public function index($encode_kegiatan_id)
    {
        // Validasi encode_kegiatan_id
        if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
            abort(404, 'Kegiatan tidak ditemukan.');
        }

        // Decode kegiatan_id
        $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

        // Cek kegiatan aktif
        $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'Active')
            ->first();

        if (!$kegiatan) {
            abort(404, 'Kegiatan tidak aktif atau tidak ditemukan.');
        }

        return view('lockscreen.index', [
            'title' => 'Lock Screen - ' . $kegiatan->kegiatan_name,
            'encode_kegiatan_id' => $encode_kegiatan_id,
            'kegiatan' => $kegiatan
        ]);
    }

    /**
     * Proses unlock screen
     */
    public function unlock_screen(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_name' => 'required|string|max:50',
            'instrumen_token' => 'required|string|max:100',
            'encode_kegiatan_id' => 'required|string'
        ]);

        try {
            // Decode kegiatan_id dari input form
            $encode_kegiatan_id = $request->encode_kegiatan_id;

            if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
                return back()->withErrors([
                    'user_name' => 'Kode kegiatan tidak valid.',
                ])->withInput();
            }

            $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];

            // Cek kegiatan dan token
            $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
                ->where('status', 'Active')
                ->where('instrumen_token', $request->instrumen_token)
                ->first();

            if (!$kegiatan) {
                return back()->withErrors([
                    'instrumen_token' => 'Token kegiatan tidak valid atau kegiatan tidak aktif.',
                ])->withInput();
            }

            // Cek user berdasarkan user_name (username)
            $user = User::where('user_name', $request->user_name)->first();

            if (!$user) {
                return back()->withErrors([
                    'user_name' => 'Username tidak ditemukan.',
                ])->withInput();
            }

            // Cek apakah user memiliki NIK
            if (!$user->nik) {
                return back()->withErrors([
                    'user_name' => 'User tidak memiliki NIK yang valid.',
                ])->withInput();
            }

            // Log aktivitas berhasil
            \Log::info('User berhasil login melalui lockscreen', [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'nama' => $user->nama,
                'kegiatan_id' => $kegiatan_id,
                'kegiatan_name' => $kegiatan->kegiatan_name
            ]);

            // Redirect ke route ptk.search dengan parameter nik
            return redirect()->route('ptk.search', ['nik' => $user->nik]);
        } catch (\Exception $e) {
            \Log::error('Error dalam proses unlock screen: ' . $e->getMessage(), [
                'user_name' => $request->user_name,
                'encode_kegiatan_id' => $request->encode_kegiatan_id
            ]);

            return back()->withErrors([
                'user_name' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
            ])->withInput();
        }
    }

    /**
     * Method tambahan untuk testing/debugging
     */
    public function test_connection()
    {
        try {
            // Test database connection
            $user_count = User::count();
            $kegiatan_count = Kegiatan::where('status', 'Active')->count();

            return response()->json([
                'status' => 'success',
                'message' => 'Koneksi database berhasil',
                'data' => [
                    'total_users' => $user_count,
                    'active_kegiatan' => $kegiatan_count
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Koneksi database gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk mendapatkan informasi kegiatan
     */
    public function get_kegiatan_info($encode_kegiatan_id)
    {
        try {
            if (count(Hashids::decode($encode_kegiatan_id)) === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kegiatan tidak ditemukan'
                ], 404);
            }

            $kegiatan_id = Hashids::decode($encode_kegiatan_id)[0];
            $kegiatan = Kegiatan::where('kegiatan_id', $kegiatan_id)
                ->where('status', 'Active')
                ->first(['kegiatan_id', 'kegiatan_name', 'start_date', 'end_date', 'instrumen_token']);

            if (!$kegiatan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kegiatan tidak aktif'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $kegiatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
