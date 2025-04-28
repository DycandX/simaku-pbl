<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->username;
        $password = $request->password;

        // Cek user di database
        $user = DB::table('users')
            ->where('username', $username)
            ->where('is_active', 1)
            ->first();

        if ($user && $password == $user->password) {
            // Login berhasil, buat session manual
            session([
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'logged_in' => true
            ]);

            // Update last_login
            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_login' => now()]);

            // Catat log aktivitas
            DB::table('log_aktivitas')->insert([
                'id_user' => $user->id,
                'aktivitas' => 'Login ke sistem',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            return redirect()->route('dashboard');
        }

        // Jika login gagal
        return back()->withErrors([
            'username' => 'Username atau password salah!',
        ])->withInput($request->only('username'));
    }

    // Proses logout
    public function logout(Request $request)
    {
        if (session('user_id')) {
            // Catat log aktivitas
            DB::table('log_aktivitas')->insert([
                'id_user' => session('user_id'),
                'aktivitas' => 'Logout dari sistem',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);
        }

        // Hapus semua session
        $request->session()->flush();

        return redirect()->route('login');
    }
}