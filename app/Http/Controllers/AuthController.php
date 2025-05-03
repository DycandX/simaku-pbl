<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in, redirect to dashboard
        if (Session::has('token') && Session::has('logged_in')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            // Kirim POST request ke API login endpoint
            $response = Http::post(url('/api/login'), [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Simpan token dan user info ke session
                Session::put('token', $data['access_token']);
                Session::put('token_type', $data['token_type']);
                Session::put('username', $request->username);
                Session::put('logged_in', true);

                // Get user data
                $userResponse = Http::withToken($data['access_token'])->get(url('/api/user'));
                
                if ($userResponse->successful()) {
                    $userData = $userResponse->json()['data'];
                    
                    // Find the current user by username
                    $currentUser = null;
                    foreach ($userData as $user) {
                        if ($user['username'] === $request->username) {
                            $currentUser = $user;
                            break;
                        }
                    }
                    
                    if ($currentUser) {
                        Session::put('user_data', $currentUser);
                        Session::put('role', $currentUser['role']);
                        Session::put('email', $currentUser['email']);
                    }
                }

                return redirect()->route('dashboard');
            } else {
                return back()->withErrors(['username' => 'Login gagal! Cek kembali username dan password.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['username' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $token = Session::get('token');

        if ($token) {
            try {
                // Kirim request ke API logout
                Http::withToken($token)->post(url('/api/logout'));
            } catch (\Exception $e) {
                // Log error tapi tetap lanjutkan logout
            }
        }

        // Hapus session
        Session::flush();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}