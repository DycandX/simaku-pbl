<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
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

        // Fetch user data from external API using token
        try {
            $response = Http::withToken($token)->get(config('app.api_url') . '/api/user');
            if ($response->successful() && $response->json('status') === true) {
                $usersData = $response->json('data');
            } else {
                $usersData = [];
            }
        } catch (\Exception $e) {
            $usersData = [];
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

        // Filter by search query (search in username and email)
        if (!empty($searchQuery)) {
            $filteredUsers = array_filter($filteredUsers, function($user) use ($searchQuery) {
                return stripos($user['username'], $searchQuery) !== false ||
                       stripos($user['email'], $searchQuery) !== false ||
                       stripos($user['nama_lengkap'] ?? '', $searchQuery) !== false;
            });
        }

        // Reset array keys after filtering
        $filteredUsers = array_values($filteredUsers);

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

        return view('admin.dashboard.kelola-pengguna.kelola-pengguna', [
            'users' => $users,
            'roleFilter' => $roleFilter,
            'statusFilter' => $statusFilter,
            'searchQuery' => $searchQuery
        ]);
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

        return view('admin.dashboard.kelola-pengguna.create');
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
            'username' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|in:admin,staff,mahasiswa',
            'password' => 'required|string|min:8', // password validation
        ]);

        // Send data to the external API for creating a new user
        try {
            $response = Http::withToken($token)->post(config('app.api_url') . '/api/user', [
                'username' => $validatedData['username'],
                'nama_lengkap' => $validatedData['nama_lengkap'],
                'email' => $validatedData['email'],
                'role' => $validatedData['role'],
                'password' => bcrypt($validatedData['password']),
            ]);

            // Check for a successful response
            if ($response->successful()) {
                return redirect()->route('admin.kelola-pengguna')->with('success', 'User created successfully.');
            } else {
                return redirect()->back()->withErrors(['error' => 'Failed to create user.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating user.']);
        }
    }

    public function edit($id)
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

        // Retrieve user data for the specified ID
        try {
            $response = Http::withToken($token)->get(config('app.api_url') . '/api/user/' . $id);
            if ($response->successful()) {
                $userData = $response->json('data');
                return view('admin.dashboard.kelola-pengguna.kelola-pengguna-edit', ['user' => $userData]);
            } else {
                return redirect()->route('admin.kelola-pengguna')->withErrors(['error' => 'User not found.']);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.kelola-pengguna')->withErrors(['error' => 'An error occurred while fetching user data.']);
        }
    }

    public function update(Request $request, $id)
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
            'username' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|in:admin,staff,mahasiswa',
            'is_active' => 'required|boolean',
            'password' => 'nullable|string|min:8', // Make password optional
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.max' => 'Username tidak boleh lebih dari 255 karakter.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',
            'is_active.required' => 'Status akun wajib dipilih.',
            'is_active.boolean' => 'Status akun tidak valid.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Prepare data for update
        $dataToUpdate = [
            'username' => $validatedData['username'],
            'nama_lengkap' => $validatedData['nama_lengkap'],
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
            'is_active' => (bool) $validatedData['is_active'], // Convert to boolean
        ];

        // Only add the password if it was provided
        if ($request->filled('password')) {
            $dataToUpdate['password'] = bcrypt($validatedData['password']);
        }

        // Send data to the external API for updating the existing user
        try {
            $response = Http::withToken($token)->put(config('app.api_url') . '/api/user/' . $id, $dataToUpdate);

            if ($response->successful()) {
                return redirect()->route('admin.kelola-pengguna')->with('success', 'Data pengguna berhasil diperbarui.');
            } else {
                // Get error message from API response if available
                $errorMessage = 'Gagal memperbarui data pengguna.';
                if ($response->json('message')) {
                    $errorMessage = $response->json('message');
                }
                return redirect()->back()->withErrors(['error' => $errorMessage])->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data pengguna.'])->withInput();
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
                return redirect()->back()->withErrors(['error' => 'Failed to delete user.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while deleting user.']);
        }
    }
}