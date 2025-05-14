<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Staff;
use App\Models\Mahasiswa;

class KelolaPenggunaController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah login
        if (!Session::has('user_data')) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Cek apakah user adalah admin
        if (Session::get('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        // Ambil data semua user dari database dengan relationship
        $users = User::with(['staff', 'mahasiswa'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform data untuk view
        $usersData = [];
        foreach ($users as $user) {
            $userData = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'nama_lengkap' => ''
            ];

            // Ambil nama lengkap berdasarkan role
            if ($user->role === 'staff' && $user->staff) {
                $userData['nama_lengkap'] = $user->staff->nama_lengkap;
            } elseif ($user->role === 'mahasiswa' && $user->mahasiswa) {
                $userData['nama_lengkap'] = $user->mahasiswa->nama_lengkap;
            } elseif ($user->role === 'admin') {
                $userData['nama_lengkap'] = 'Administrator';
            }

            $usersData[] = $userData;
        }

        return view('admin.dashboard.kelola-pengguna.kelola-pengguna', [
            'users' => $users,
            'usersData' => $usersData
        ]);
    }

    public function edit($id)
    {
        // Check admin access
        if (Session::get('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        // Get specific user data dari database
        $user = User::with(['staff', 'mahasiswa'])->find($id);

        if (!$user) {
            return redirect()->route('admin.kelola-pengguna')->withErrors(['error' => 'Pengguna tidak ditemukan.']);
        }

        // Transform data untuk view
        $userData = [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'nama_lengkap' => ''
        ];

        // Ambil nama lengkap berdasarkan role
        if ($user->role === 'staff' && $user->staff) {
            $userData['nama_lengkap'] = $user->staff->nama_lengkap;
        } elseif ($user->role === 'mahasiswa' && $user->mahasiswa) {
            $userData['nama_lengkap'] = $user->mahasiswa->nama_lengkap;
        } elseif ($user->role === 'admin') {
            $userData['nama_lengkap'] = 'Administrator';
        }

        return view('admin.dashboard.kelola-pengguna.kelola-pengguna-edit', ['user' => $userData]);
    }

    public function update(Request $request, $id)
    {
        // Check admin access
        if (Session::get('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        // Validate request
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:admin,staff,mahasiswa',
            'password' => 'nullable|min:6',
            'nama_lengkap' => 'required|string|max:255'
        ]);

        try {
            // Find user
            $user = User::find($id);

            if (!$user) {
                return back()->withErrors(['error' => 'Pengguna tidak ditemukan.']);
            }

            // Update user data
            $user->username = $request->username;
            $user->email = $request->email;
            $user->role = $request->role;

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Update nama lengkap berdasarkan role
            if ($request->role === 'staff') {
                $staff = Staff::where('id_user', $user->id)->first();
                if ($staff) {
                    $staff->nama_lengkap = $request->nama_lengkap;
                    $staff->save();
                }
            } elseif ($request->role === 'mahasiswa') {
                $mahasiswa = Mahasiswa::where('nim', $user->username)->first();
                if ($mahasiswa) {
                    $mahasiswa->nama_lengkap = $request->nama_lengkap;
                    $mahasiswa->email = $request->email;
                    $mahasiswa->save();
                }
            }

            return redirect()->route('admin.kelola-pengguna')
                ->with('success', 'Data pengguna berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Hapus method getApiData dan getUserById karena tidak diperlukan lagi
}