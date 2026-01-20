<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Role;
use App\Models\TimKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManagementUsersController extends Controller
{
    public function index()
    {
        $users = Users::with(['role', 'timKerja'])

            ->paginate(10);

        $roles = Role::all();
        $timKerja = TimKerja::all();

        return view('users.index', [
            'tittle' => 'Management Users',
            'data' => $users,
            'roles' => $roles,
            'timKerja' => $timKerja
        ]);
    }

    // ManagementUsersController.php - update method store()
    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            \Log::info('Store user request received', $request->all());

            $validated = $request->validate([
                'nama' => 'required|string|max:45',
                'user_name' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('users', 'user_name')->ignore($request->user_id, 'user_id')
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:45',
                    Rule::unique('users', 'email')->ignore($request->user_id, 'user_id')
                ],
                'nip' => [
                    'nullable',
                    'string',
                    'max:16',
                    Rule::unique('users', 'nip')->ignore($request->user_id, 'user_id')
                ],
                'nik' => [
                    'nullable',
                    'string',
                    'max:16',
                    Rule::unique('users', 'nik')->ignore($request->user_id, 'user_id')
                ],
                'password' => $request->user_id ? 'nullable|string|min:6' : 'required|string|min:6',
                'role_id' => 'nullable|exists:role,role_id',
                'tim_kerja_id' => 'nullable|exists:tim_kerja,tim_kerja_id',
                'npsn' => 'nullable|string|max:45',
                'nama_satuan_pendidikan' => 'nullable|string|max:100',
                'alamat_satuan_pendidikan' => 'nullable|string|max:100',
                'kab_kota' => 'nullable|string|max:45',
                'bos' => 'nullable|string|max:45'
            ]);

            \Log::info('Validation passed', $validated);

            $userData = [
                'nama' => $validated['nama'],
                'user_name' => $validated['user_name'],
                'email' => $validated['email'] ?? null,
                'nip' => $validated['nip'] ?? null,
                'nik' => $validated['nik'] ?? null,
                'role_id' => $validated['role_id'] ?? null,
                'tim_kerja_id' => $validated['tim_kerja_id'] ?? null,
                'npsn' => $validated['npsn'] ?? null,
                'nama_satuan_pendidikan' => $validated['nama_satuan_pendidikan'] ?? null,
                'alamat_satuan_pendidikan' => $validated['alamat_satuan_pendidikan'] ?? null,
                'kab_kota' => $validated['kab_kota'] ?? null,
                'bos' => $validated['bos'] ?? null
            ];

            // Jika password diisi, hash password
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            \Log::info('User data prepared', $userData);

            // Update or Create user
            $user = Users::updateOrCreate(
                ['user_id' => $request->user_id],
                $userData
            );

            \Log::info('User saved successfully', ['user_id' => $user->user_id]);

            $message = $request->user_id ? 'Data user berhasil diperbarui' : 'Data user berhasil ditambahkan';
            return redirect()->route('users.index')->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error', $e->errors());
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Throwable $e) {
            \Log::error('Error saving user: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ', $request->all());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function get($user_id)
    {
        $user = Users::with(['role', 'timKerja'])->where('user_id', $user_id)->first();

        if (!$user) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Jangan kirim password ke frontend
        unset($user->password);

        return response()->json($user);
    }

    public function delete($user_id)
    {
        try {
            $user = Users::find($user_id);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Data user tidak ditemukan'], 404);
            }

            $user->delete();
            return response()->json(['success' => true, 'message' => 'Data user berhasil dihapus'], 200);
        } catch (\Throwable $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data'], 500);
        }
    }

    public function resetPassword($user_id)
    {
        try {
            $user = Users::find($user_id);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Data user tidak ditemukan'], 404);
            }

            // Reset password default ke "password123"
            $user->password = Hash::make('password123');
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset ke default (password123)'
            ], 200);
        } catch (\Throwable $e) {
            \Log::error('Error resetting password: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mereset password'], 500);
        }
    }
}
