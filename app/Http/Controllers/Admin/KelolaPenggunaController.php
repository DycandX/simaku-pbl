<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class KelolaPenggunaController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is logged in and is an admin
        $userData = Session::get('user_data');
        $token = Session::get('token');
        $role = Session::get('role');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }
        if ($role !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        // DEBUG: Log session data
        Log::info('Session Debug:', [
            'user_data' => $userData,
            'token' => $token ? 'Token exists' : 'No token',
            'role' => $role
        ]);

        // DEBUG: Log API URL
        $apiUrl = config('app.api_url') . '/api/user';
        Log::info('API URL:', ['url' => $apiUrl]);

        // Fetch user data from external API using token
        try {
            // DEBUG: Log before API call
            Log::info('Making API call to fetch users...');

            $response = Http::withToken($token)
                ->timeout(30) // Add timeout
                ->get($apiUrl);

            // DEBUG: Log response details
            Log::info('API Response Debug:', [
                'status_code' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);

            if ($response->successful() && $response->json('status') === true) {
                $usersData = $response->json('data');
                Log::info('Users data retrieved successfully:', ['count' => count($usersData)]);
            } else {
                $usersData = [];
                Log::error('API call failed or returned false status:', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            $usersData = [];
            Log::error('Exception during API call:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }

        // Get filter parameters
        $roleFilter = $request->input('role_filter');
        $statusFilter = $request->input('status_filter');
        $searchQuery = $request->input('search');

        // Apply filters
        $filteredUsers = $usersData;

        // Filter by role
        if (!empty($roleFilter)) {
            $filteredUsers = array_filter($filteredUsers, function($user) use ($roleFilter) {
                return strtolower($user['role']) === strtolower($roleFilter);
            });
        }

        // Filter by status
        if (!empty($statusFilter)) {
            $filteredUsers = array_filter($filteredUsers, function($user) use ($statusFilter) {
                $isActive = isset($user['is_active']) ? $user['is_active'] : false;

                if ($statusFilter === 'active') {
                    return $isActive === true || $isActive === 1 || $isActive === '1';
                } elseif ($statusFilter === 'inactive') {
                    return $isActive === false || $isActive === 0 || $isActive === '0' || $isActive === null;
                }

                return true;
            });
        }

        // Filter by search query
        if (!empty($searchQuery)) {
            $filteredUsers = array_filter($filteredUsers, function($user) use ($searchQuery) {
                // Search in username
                if (stripos($user['username'], $searchQuery) !== false) {
                    return true;
                }

                // Search in email
                if (stripos($user['email'], $searchQuery) !== false) {
                    return true;
                }

                // Search in nama_lengkap from mahasiswa relation
                if (isset($user['mahasiswa']['nama_lengkap']) &&
                    stripos($user['mahasiswa']['nama_lengkap'], $searchQuery) !== false) {
                    return true;
                }

                // Search in nama_lengkap from staff relation
                if (isset($user['staff']['nama_lengkap']) &&
                    stripos($user['staff']['nama_lengkap'], $searchQuery) !== false) {
                    return true;
                }

                return false;
            });
        }

        // Reset array keys after filtering
        $filteredUsers = array_values($filteredUsers);

        // DEBUG: Log filtered results
        Log::info('Filtered users count:', ['count' => count($filteredUsers)]);

        // Manual pagination
        $perPage = 10;
        $page = $request->input('page', 1);
        $total = count($filteredUsers);
        $offset = ($page - 1) * $perPage;
        $paginatedUsers = array_slice($filteredUsers, $offset, $perPage);

        // Preserve query parameters in pagination links
        $queryParams = $request->only(['role_filter', 'status_filter', 'search']);

        $users = new LengthAwarePaginator(
            $paginatedUsers,
            $total,
            $perPage,
            $page,
            [
                'path' => url()->current(),
                'pageName' => 'page',
            ]
        );

        // Append query parameters to pagination links
        $users->appends($queryParams);

        // DEBUG: Add debug info to view
        $debugInfo = [
            'api_url' => $apiUrl,
            'token_exists' => !empty($token),
            'total_users' => count($usersData),
            'filtered_users' => count($filteredUsers),
            'session_role' => $role
        ];

        return view('admin.dashboard.kelola-pengguna.kelola-pengguna', [
            'users' => $users,
            'roleFilter' => $roleFilter,
            'statusFilter' => $statusFilter,
            'searchQuery' => $searchQuery,
            'debugInfo' => $debugInfo // Add debug info
        ]);
    }

    // Test API connection method
    public function testApiConnection()
    {
        $token = Session::get('token');
        $apiUrl = config('app.api_url') . '/api/user';

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'No token found in session'
            ]);
        }

        try {
            $response = Http::withToken($token)
                ->timeout(10)
                ->get($apiUrl);

            return response()->json([
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'response_body' => $response->json(),
                'api_url' => $apiUrl,
                'token_exists' => !empty($token)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'api_url' => $apiUrl,
                'token_exists' => !empty($token)
            ]);
        }
    }
    public function create()
    {
        // Check if user is logged in and is an admin
        $userData = Session::get('user_data');
        $token = Session::get('token');
        $role = Session::get('role');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }
        if ($role !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        // Fetch mahasiswa and staff data from API
        $mahasiswaData = [];
        $staffData = [];

        try {
            // Get existing users to check which mahasiswa/staff already have accounts
            $usersResponse = Http::withToken($token)
                ->timeout(30)
                ->get(config('app.api_url') . '/api/user');

            $existingUsers = [
                'mahasiswa' => [],
                'staff' => []
            ];

            if ($usersResponse->successful()) {
                $users = $usersResponse->json()['data'] ?? [];
                foreach ($users as $user) {
                    if ($user['mahasiswa_id']) {
                        $existingUsers['mahasiswa'][] = $user['mahasiswa_id'];
                    }
                    if ($user['staff_id']) {
                        $existingUsers['staff'][] = $user['staff_id'];
                    }
                }
            }

            // Get mahasiswa data
            $mahasiswaResponse = Http::withToken($token)
                ->timeout(30)
                ->get(config('app.api_url') . '/api/mahasiswa');

            if ($mahasiswaResponse->successful()) {
                $allMahasiswa = $mahasiswaResponse->json()['data'] ?? [];
                // Filter out mahasiswa who already have user accounts
                $mahasiswaData = array_filter($allMahasiswa, function($mahasiswa) use ($existingUsers) {
                    return !in_array($mahasiswa['id'], $existingUsers['mahasiswa']);
                });
            }

            // Get staff data
            $staffResponse = Http::withToken($token)
                ->timeout(30)
                ->get(config('app.api_url') . '/api/staff');

            if ($staffResponse->successful()) {
                $allStaff = $staffResponse->json()['data'] ?? [];
                // Filter out staff who already have user accounts
                $staffData = array_filter($allStaff, function($staff) use ($existingUsers) {
                    return !in_array($staff['id'], $existingUsers['staff']);
                });
            }

        } catch (\Exception $e) {
            Log::error('Error fetching mahasiswa/staff data:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Gagal mengambil data mahasiswa dan staff.']);
        }

        // Re-index arrays to ensure proper JSON encoding
        $mahasiswaData = array_values($mahasiswaData);
        $staffData = array_values($staffData);


        return view('admin.dashboard.kelola-pengguna.create', compact('mahasiswaData', 'staffData'));
    }

    public function store(Request $request)
    {
        // Check if user is logged in and is an admin
        $userData = Session::get('user_data');
        $token = Session::get('token');
        $role = Session::get('role');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }
        if ($role !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        // Validate incoming request data
        $validatedData = $request->validate([
            'role' => 'required|string|in:admin,staff,mahasiswa',
            'person_id' => 'required_unless:role,admin|integer',
            'email' => 'required|email|max:255',
            'is_active' => 'required|boolean',
            'password' => 'required|string|min:8|confirmed',
        ], [
            // 'person_id.required_unless' => 'Pilih mahasiswa atau staff yang akan dibuatkan akun.',
            'password.confirmed' => 'Konfirmasi password tidak sama dengan password.',
            'password.min' => 'Password minimal 8 karakter.',
            'is_active.required' => 'Status akun harus dipilih.',
            'role.required' => 'Role pengguna harus dipilih.',
        ]);

        // Prepare data for API based on role
        $apiData = [
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
            'is_active' => (bool) $validatedData['is_active'],
            'password' => $validatedData['password'],
        ];
        $nama = $request->input('selected_name');
        $idValue = $request->input('selected_id_value');
        // Set username and IDs based on role
        if ($validatedData['role'] === 'admin') {
            // For admin, use email as username (or you can add separate username field)
            $apiData['username'] = $validatedData['email'];
            $apiData['mahasiswa_id'] = null;
            $apiData['staff_id'] = null;
        } elseif ($validatedData['role'] === 'mahasiswa') {
            // try {
            //     // Get mahasiswa data to set username as NIM

            //     // dd($idValue);
            //     $mahasiswaResponse = Http::withToken($token)
            //     ->timeout(30);
            //     // ->get(config('app.api_url') . '/api/mahasiswa/' . $validatedData['person_id']);
            //     if ($mahasiswaResponse->successful()) {
            //         // $mahasiswa = $mahasiswaResponse->json()['data'];

            //     } else {
            //         return redirect()->back()
            //             ->withErrors(['error' => 'Data mahasiswa tidak ditemukan.'])
            //             ->withInput();
            //     }
            // } catch (\Exception $e) {
            //     Log::error('Error fetching mahasiswa data:', ['message' => $e->getMessage()]);
            //     return redirect()->back()
            //         ->withErrors(['error' => 'Gagal mengambil data mahasiswa.'])
            //         ->withInput();
            // }
            $apiData['username'] = $idValue; //$mahasiswa['nim']
            $apiData['mahasiswa_id'] = $validatedData['person_id'];
            $apiData['staff_id'] = null;
        } elseif ($validatedData['role'] === 'staff') {
            try {
                // Get staff data to set username as NIP
                $staffResponse = Http::withToken($token)
                    ->timeout(30)
                    ->get(config('app.api_url') . '/api/staff/' . $validatedData['person_id']);

                if ($staffResponse->successful()) {
                    $staff = $staffResponse->json()['data'];
                    $apiData['username'] = $staff['nip'];
                    $apiData['mahasiswa_id'] = null;
                    $apiData['staff_id'] = $validatedData['person_id'];
                } else {
                    return redirect()->back()
                        ->withErrors(['error' => 'Data staff tidak ditemukan.'])
                        ->withInput();
                }
            } catch (\Exception $e) {
                Log::error('Error fetching staff data:', ['message' => $e->getMessage()]);
                return redirect()->back()
                    ->withErrors(['error' => 'Gagal mengambil data staff.'])
                    ->withInput();
            }
        }

        // Log data being sent to API
        Log::info('Creating user via API:', [
            'data' => array_merge($apiData, ['password' => '[HIDDEN]']),
            'api_url' => config('app.api_url') . '/api/user'
        ]);

        // Send data to the external API for creating a new user
        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->post(config('app.api_url') . '/api/user', $apiData);

            // Log API response
            Log::info('API Response for user creation:', [
                'status_code' => $response->status(),
                'successful' => $response->successful(),
                'response_body' => $response->json()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                // Check if API returned success status
                if (isset($responseData['status']) && $responseData['status'] === true) {
                    return redirect()->route('admin.kelola-pengguna')
                        ->with('success', 'Pengguna berhasil dibuat.');
                } else {
                    // API returned error in response
                    $errorMessage = $responseData['message'] ?? 'Gagal membuat pengguna.';
                    return redirect()->back()
                        ->withErrors(['error' => $errorMessage])
                        ->withInput();
                }
            } else {
                // HTTP error
                $errorData = $response->json();
                $errorMessage = 'Gagal membuat pengguna.';

                if ($response->status() === 422) {
                    // Handle validation errors from API
                    if (isset($errorData['errors'])) {
                        return redirect()->back()
                            ->withErrors($errorData['errors'])
                            ->withInput();
                    } elseif (isset($errorData['message'])) {
                        $errorMessage = $errorData['message'];
                    }
                } elseif (isset($errorData['message'])) {
                    $errorMessage = $errorData['message'];
                }

                Log::error('API Error when creating user:', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body()
                ]);

                return redirect()->back()
                    ->withErrors(['error' => $errorMessage])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Exception during user creation:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat membuat pengguna. Silakan coba lagi.'])
                ->withInput();
        }
    }

    // Optional: Add method to get person data via AJAX
    public function getPersonData(Request $request)
    {
        $token = Session::get('token');
        $role = $request->get('role');
        $personId = $request->get('person_id');

        if (!$token || !$role || !$personId) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        try {
            if ($role === 'mahasiswa') {
                $response = Http::withToken($token)
                    ->timeout(30)
                    ->get(config('app.api_url') . '/api/mahasiswa/' . $personId);

                if ($response->successful()) {
                    $data = $response->json()['data'];
                    return response()->json([
                        'username' => $data['nim'],
                        'nama_lengkap' => $data['nama_lengkap']
                    ]);
                }
            } elseif ($role === 'staff') {
                $response = Http::withToken($token)
                    ->timeout(30)
                    ->get(config('app.api_url') . '/api/staff/' . $personId);

                if ($response->successful()) {
                    $data = $response->json()['data'];
                    return response()->json([
                        'username' => $data['nip'],
                        'nama_lengkap' => $data['nama_lengkap']
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching person data:', ['message' => $e->getMessage()]);
        }

        return response()->json(['error' => 'Data not found'], 404);
    }

    public function edit($id)
    {
        // Check if user is logged in and is an admin
        $sessionUserData = Session::get('user_data');
        $token = Session::get('token');
        $role = Session::get('role');

        if (!$sessionUserData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }
        if ($role !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        // Retrieve user data for the specified ID
        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get(config('app.api_url') . '/api/user/' . $id);

            if ($response->successful() && $response->json('status') === true) {
                $userData = $response->json('data');

                // Validate that user data exists and has required fields
                if (!$userData || !isset($userData['id'])) {
                    return redirect()->route('admin.kelola-pengguna')
                        ->withErrors(['error' => 'Data pengguna tidak valid atau tidak ditemukan.']);
                }

                // Log for debugging
                Log::info('User data for edit:', [
                    'user_id' => $id,
                    'user_data' => $userData
                ]);

                return view('admin.dashboard.kelola-pengguna.kelola-pengguna-edit', [
                    'user' => $userData
                ]);

            } else {
                Log::error('Failed to fetch user data for edit:', [
                    'user_id' => $id,
                    'status_code' => $response->status(),
                    'response_body' => $response->body()
                ]);

                return redirect()->route('admin.kelola-pengguna')
                    ->withErrors(['error' => 'Pengguna tidak ditemukan atau terjadi kesalahan pada server.']);
            }
        } catch (\Exception $e) {
            Log::error('Exception during user edit fetch:', [
                'user_id' => $id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('admin.kelola-pengguna')
                ->withErrors(['error' => 'Terjadi kesalahan saat mengambil data pengguna.']);
        }
    }

    public function update(Request $request, $id)
    {
        $token = Session::get('token');
        $role = Session::get('role');

        if (!$token || $role !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Akses tidak sah.']);
        }

        // Validasi input
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            // 'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:admin,staff,mahasiswa',
            'is_active' => 'required|boolean',
            'password' => 'nullable|string|min:8',
        ]);

        $data = collect($validated)
            ->when(empty($validated['password']), fn($c) => $c->forget('password'))
            ->when(!empty($validated['password']), fn($c) => $c->put('password', bcrypt($validated['password'])))
            ->toArray();

        try {
            $response = Http::withToken($token)
                ->put(config('app.api_url') . "/api/user/{$id}", $data);

            // dd($response);

            if ($response->successful()) {
                return redirect()->route('admin.kelola-pengguna')->with('success', 'Data pengguna berhasil diperbarui.');
            }

            $errors = $response->json();
            return back()->withErrors(['error' => $errors['message'] ?? 'Gagal memperbarui data pengguna.'])
                         ->with('api_error', $errors)
                         ->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                         ->withInput();
        }
    }


    public function destroy(Request $request, $id)
    {
        // Check if user is logged in and is an admin
        $userData = Session::get('user_data');
        $token = Session::get('token');
        $role = Session::get('role');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }
        if ($role !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        try {
            $response = Http::withToken($token)->delete(config('app.api_url') . '/api/user/' . $id);
            if ($response->successful()) {
                return redirect()->route('admin.kelola-pengguna')->with('success', 'User deleted successfully.');
            } else {
                $errorMessage = 'Failed to delete user.';
                if ($response->json('message')) {
                    $errorMessage = $response->json('message');
                }
                return redirect()->back()->withErrors(['error' => $errorMessage]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while deleting user.']);
        }
    }
}