<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in, redirect based on role
        if (Session::has('token') && Session::has('logged_in')) {
            $role = Session::get('role');

            Log::info('User already logged in', [
                'role' => $role,
                'username' => Session::get('username')
            ]);

            if ($role == 'mahasiswa') {
                return redirect()->route('lihat-tagihan-ukt');
            } elseif ($role == 'admin') {
                return redirect()->route('admin.kelola-pengguna');
            } elseif ($role == 'staff') {
                return redirect()->route('staff-app');
            }
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
            Log::info('Login attempt started', [
                'username' => $request->username,
                'timestamp' => now()
            ]);

            // Kirim POST request ke API login endpoint
            $response = Http::post(url('/api/login'), [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Login API response successful', [
                    'username' => $request->username,
                    'response_keys' => array_keys($data)
                ]);

                // Simpan token ke session
                Session::put('token', $data['access_token']);
                Session::put('token_type', $data['token_type']);
                Session::put('username', $request->username);
                Session::put('logged_in', true);

                // Ambil data pengguna yang sedang login menggunakan token
                $userResponse = Http::withToken($data['access_token'])
                    ->get(url('/api/user'));

                Log::info('API /user request sent', [
                    'status' => $userResponse->status(),
                    'username' => $request->username
                ]);

                if ($userResponse->successful()) {
                    $allUsers = $userResponse->json()['data'] ?? [];

                    Log::info('API /user response data', [
                        'username_requested' => $request->username,
                        'users_count' => count($allUsers),
                        'first_user_sample' => !empty($allUsers) ? [
                            'username' => $allUsers[0]['username'] ?? 'N/A',
                            'role' => $allUsers[0]['role'] ?? 'N/A'
                        ] : null
                    ]);

                    // Log semua admin users untuk debugging
                    $adminUsers = array_filter($allUsers, function($user) {
                        return isset($user['role']) && $user['role'] === 'admin';
                    });

                    Log::info('Admin users in API response', [
                        'admin_count' => count($adminUsers),
                        'admin_usernames' => array_map(function($user) {
                            return [
                                'username' => $user['username'],
                                'email' => $user['email'] ?? 'N/A'
                            ];
                        }, $adminUsers)
                    ]);

                    // Cari pengguna berdasarkan username yang diinput
                    $currentUser = null;
                    $requestedUsername = trim(strtolower($request->username));

                    foreach ($allUsers as $user) {
                        $userUsername = trim(strtolower($user['username'] ?? ''));

                        if ($userUsername === $requestedUsername) {
                            $currentUser = $user;
                            Log::info('User found in API response', [
                                'username' => $user['username'],
                                'role' => $user['role'],
                                'email' => $user['email'] ?? 'N/A'
                            ]);
                            break;
                        }
                    }

                    if ($currentUser) {
                        // Simpan data pengguna ke session
                        Session::put('user_data', $currentUser);
                        Session::put('role', $currentUser['role']);
                        Session::put('email', $currentUser['email']);

                        // Log data session untuk debugging
                        Log::info('Session data saved successfully', [
                            'username' => Session::get('username'),
                            'role' => Session::get('role'),
                            'email' => Session::get('email'),
                            'session_id' => Session::getId()
                        ]);

                        // Verifikasi ulang session dapat dibaca
                        $verifiedRole = Session::get('role');
                        Log::info('Session verification after save', [
                            'original_role' => $currentUser['role'],
                            'session_role' => $verifiedRole,
                            'match' => $currentUser['role'] === $verifiedRole ? 'YES' : 'NO'
                        ]);
                    } else {
                        // Jika pengguna tidak ditemukan dalam daftar
                        Log::warning('User not found in API /user response, trying /api/me', [
                            'username' => $request->username,
                            'total_users' => count($allUsers)
                        ]);

                        // Ambil endpoint /api/me sebagai fallback
                        try {
                            $meResponse = Http::withToken($data['access_token'])
                                ->get(url('/api/me'));

                            if ($meResponse->successful()) {
                                $userData = $meResponse->json()['data'];
                                Session::put('user_data', $userData);
                                Session::put('role', $userData['role']);
                                Session::put('email', $userData['email']);

                                Log::info('User data from /api/me', [
                                    'username' => $userData['username'],
                                    'role' => $userData['role'],
                                    'email' => $userData['email'] ?? 'N/A'
                                ]);
                            } else {
                                Log::error('Failed to fetch user data from /api/me', [
                                    'status' => $meResponse->status(),
                                    'response' => $meResponse->body()
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::error('Exception fetching /api/me', [
                                'error' => $e->getMessage(),
                                'username' => $request->username
                            ]);
                        }
                    }
                } else {
                    // Log respons error dari API user
                    Log::error('User API error', [
                        'status' => $userResponse->status(),
                        'response' => $userResponse->body(),
                        'username' => $request->username
                    ]);
                }

                // Redirect berdasarkan role
                $role = Session::get('role');

                Log::info('Redirecting user based on role', [
                    'username' => Session::get('username'),
                    'role' => $role,
                    'role_type' => gettype($role),
                    'is_admin' => $role === 'admin' ? 'YES' : 'NO',
                    'is_mahasiswa' => $role === 'mahasiswa' ? 'YES' : 'NO',
                    'is_staff' => $role === 'staff' ? 'YES' : 'NO'
                ]);

                if ($role === 'mahasiswa') {
                    Log::info('Redirecting to mahasiswa dashboard');
                    return redirect()->route('lihat-tagihan-ukt');
                } elseif ($role === 'admin') {
                    Log::info('Redirecting to admin dashboard');
                    return redirect()->route('admin.kelola-pengguna');
                } elseif ($role === 'staff') {
                    Log::info('Redirecting to staff dashboard');
                    return redirect()->route('staff-app');
                }

                // Default redirect jika role tidak terdeteksi
                Log::warning('No role matched, redirecting to login', [
                    'role' => $role,
                    'username' => Session::get('username')
                ]);
                return redirect()->route('login');
            } else {
                // Log respons error dari API login
                Log::error('Login API error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'username' => $request->username
                ]);

                return back()->withErrors(['username' => 'Login gagal! Cek kembali username dan password.']);
            }
        } catch (\Exception $e) {
            Log::error('Exception during login', [
                'error' => $e->getMessage(),
                'username' => $request->username,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['username' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $token = Session::get('token');
        $username = Session::get('username');

        Log::info('Logout attempt', [
            'username' => $username,
            'has_token' => !empty($token)
        ]);

        if ($token) {
            try {
                // Kirim request ke API logout
                $response = Http::withToken($token)->post(url('/api/logout'));

                Log::info('API logout response', [
                    'status' => $response->status(),
                    'username' => $username
                ]);
            } catch (\Exception $e) {
                // Log error tapi tetap lanjutkan logout
                Log::warning('Logout API error', [
                    'error' => $e->getMessage(),
                    'username' => $username
                ]);
            }
        }

        // Log session data sebelum flush
        Log::info('Session data before flush', [
            'username' => Session::get('username'),
            'role' => Session::get('role'),
            'logged_in' => Session::get('logged_in')
        ]);

        // Hapus session
        Session::flush();

        Log::info('Logout completed', [
            'username' => $username
        ]);

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}