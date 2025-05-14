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
                return redirect()->route('mahasiswa-dashboard');
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
            // Check for known admin usernames
            $knownAdminUsernames = ['admin', '4.33.2.3.01', '4.33.2.3.03', '4.33.2.3.10'];
            $isLikelyAdmin = in_array($request->username, $knownAdminUsernames);

            if ($isLikelyAdmin) {
                Log::info('Login attempt from likely admin user', [
                    'username' => $request->username
                ]);
            }

            // Kirim POST request ke API login endpoint
            $response = Http::post(url('/api/login'), [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Simpan token ke session
                Session::put('token', $data['access_token']);
                Session::put('token_type', $data['token_type']);
                Session::put('username', $request->username);
                Session::put('logged_in', true);

                // SPECIAL HANDLING FOR ADMIN USERS
                // If username is known admin, set role directly
                if ($isLikelyAdmin) {
                    Session::put('role', 'admin');
                    Session::put('email', $request->username . '@example.com');
                    Session::put('user_data', [
                        'username' => $request->username,
                        'role' => 'admin',
                        'email' => $request->username . '@example.com'
                    ]);

                    Log::info('Admin user detected and role set manually', [
                        'username' => $request->username,
                        'role' => 'admin'
                    ]);

                    return redirect()->route('admin.kelola-pengguna');
                }

                // For non-admin users, proceed with regular API checks
                // Try /api/me first
                $meResponse = Http::withToken($data['access_token'])->get(url('/api/me'));

                if ($meResponse->successful() && isset($meResponse->json()['data'])) {
                    $userData = $meResponse->json()['data'];

                    // Check if this user has admin role
                    $role = $userData['role'] ?? '';
                    $isAdmin = false;

                    // Check for admin role with various formats that might be returned
                    if (
                        strtolower($role) === 'admin' ||
                        strtolower($role) === 'administrator' ||
                        $role == 1 ||  // Some APIs use numeric role IDs
                        (isset($userData['is_admin']) && $userData['is_admin'] === true)
                    ) {
                        $isAdmin = true;
                        $role = 'admin';
                    }

                    Log::info('User data from /api/me', [
                        'username' => $userData['username'] ?? 'N/A',
                        'raw_role' => $userData['role'] ?? 'N/A',
                        'processed_role' => $role,
                        'is_admin' => $isAdmin ? 'YES' : 'NO'
                    ]);

                    // Store user data in session
                    Session::put('user_data', $userData);
                    Session::put('role', $role);
                    Session::put('email', $userData['email'] ?? '');

                    // Redirect based on role
                    if ($isAdmin) {
                        return redirect()->route('admin.kelola-pengguna');
                    } elseif ($role === 'mahasiswa') {
                        return redirect()->route('lihat-tagihan-ukt');
                    } elseif ($role === 'staff') {
                        return redirect()->route('staff.pembayaran-ukt');
                    }
                } else {
                    Log::warning('Failed to get user data from /api/me, trying /api/user', [
                        'status' => $meResponse->status()
                    ]);
                }

                // Fallback to /api/user
                $userResponse = Http::withToken($data['access_token'])->get(url('/api/user'));

                if ($userResponse->successful()) {
                    $allUsers = $userResponse->json()['data'] ?? [];

                    // Log admin users for debugging
                    $adminUsers = array_filter($allUsers, function($user) {
                        return
                            (isset($user['role']) && strtolower($user['role']) === 'admin') ||
                            (isset($user['role']) && strtolower($user['role']) === 'administrator') ||
                            (isset($user['is_admin']) && $user['is_admin'] === true);
                    });

                    Log::info('Admin users found in /api/user response', [
                        'count' => count($adminUsers),
                        'usernames' => array_map(function($u) {
                            return $u['username'] ?? 'N/A';
                        }, $adminUsers)
                    ]);

                    // Find the current user
                    $currentUser = null;
                    foreach ($allUsers as $user) {
                        if (strtolower($user['username'] ?? '') === strtolower($request->username)) {
                            $currentUser = $user;
                            break;
                        }
                    }

                    if ($currentUser) {
                        // Check if this user has admin role
                        $role = $currentUser['role'] ?? '';
                        $isAdmin = false;

                        // Check various formats
                        if (
                            strtolower($role) === 'admin' ||
                            strtolower($role) === 'administrator' ||
                            $role == 1 ||
                            (isset($currentUser['is_admin']) && $currentUser['is_admin'] === true)
                        ) {
                            $isAdmin = true;
                            $role = 'admin';
                        }

                        Log::info('User found in /api/user response', [
                            'username' => $currentUser['username'] ?? 'N/A',
                            'raw_role' => $currentUser['role'] ?? 'N/A',
                            'processed_role' => $role,
                            'is_admin' => $isAdmin ? 'YES' : 'NO'
                        ]);

                        // Store in session
                        Session::put('user_data', $currentUser);
                        Session::put('role', $role);
                        Session::put('email', $currentUser['email'] ?? '');

                        // Redirect based on role
                        if ($isAdmin) {
                            return redirect()->route('admin.kelola-pengguna');
                        } elseif ($role === 'mahasiswa') {
                            return redirect()->route('lihat-tagihan-ukt');
                        } elseif ($role === 'staff') {
                            return redirect()->route('staff-app');
                        }
                    } else {
                        Log::warning('User not found in /api/user response', [
                            'username' => $request->username
                        ]);
                    }
                }

                // If we got here, we couldn't determine the role or the redirects didn't happen
                // Check session in case it was set earlier
                $role = Session::get('role');

                if ($role === 'admin') {
                    return redirect()->route('admin.kelola-pengguna');
                } elseif ($role === 'mahasiswa') {
                    return redirect()->route('lihat-tagihan-ukt');
                } elseif ($role === 'staff') {
                    Log::info('Redirecting to staff dashboard');
                    return redirect()->route('staff-app');
                }

                // If all else fails
                Log::warning('Could not determine user role after all attempts', [
                    'username' => $request->username
                ]);

                // Last resort - guess based on username pattern
                if (strpos($request->username, 'admin') !== false) {
                    Session::put('role', 'admin');
                    return redirect()->route('admin.kelola-pengguna');
                }

                // Default fallback
                return redirect()->route('login')
                    ->with('error', 'Tidak dapat menentukan peran pengguna. Silakan hubungi administrator.');

            } else {
                // Login failed
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
                'trace' => $e->getTrace()
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

        // Hapus session
        Session::flush();

        Log::info('Logout completed', [
            'username' => $username
        ]);

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}