<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['mahasiswa', 'staff']);

        // Filter by role if provided (untuk kebutuhan halaman admin)
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Filter by status if provided
        if ($request->has('is_active') && $request->is_active != '') {
            $query->where('is_active', $request->is_active);
        }

        // Search functionality (untuk kebutuhan halaman admin)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('username', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data User Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:mahasiswa,staff,admin',
            'is_active' => 'boolean',
            'mahasiswa_id' => 'nullable|exists:mahasiswa,id|required_if:role,mahasiswa',
            'staff_id' => 'nullable|exists:staff,id|required_if:role,staff',
            // Tambahan untuk admin yang tidak perlu mahasiswa_id atau staff_id
            'nama_lengkap' => 'required_if:role,admin|string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Memasukkan Data',
                'data' => $validator->errors()
            ]);
        }

        $user = new User;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->role = $request->role;
        $user->mahasiswa_id = $request->mahasiswa_id;
        $user->staff_id = $request->staff_id;
        $user->is_active = $request->is_active ?? true;
        $user->last_login = null;

        // Jika ada field nama_lengkap di tabel users
        if ($request->has('nama_lengkap')) {
            $user->nama_lengkap = $request->nama_lengkap;
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Memasukkan Data User',
            'data' => $user->load(['mahasiswa', 'staff'])
        ], 201);
    }

    public function show(string $id)
    {
        $data = User::with(['mahasiswa', 'staff'])->find($id);

        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data User Ditemukan',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data User Tidak Ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data User Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('username')) {
            $rules['username'] = 'unique:users,username,' . $user->id;
        }

        if ($request->has('password')) {
            $rules['password'] = 'min:6';
        }

        if ($request->has('email')) {
            $rules['email'] = 'email|unique:users,email,' . $user->id;
        }

        if ($request->has('role')) {
            $rules['role'] = 'in:mahasiswa,staff,admin';
        }

        if ($request->has('mahasiswa_id')) {
            $rules['mahasiswa_id'] = 'nullable|exists:mahasiswa,id';
        }

        if ($request->has('staff_id')) {
            $rules['staff_id'] = 'nullable|exists:staff,id';
        }

        if ($request->has('is_active')) {
            $rules['is_active'] = 'boolean';
        }

        if ($request->has('nama_lengkap')) {
            $rules['nama_lengkap'] = 'string|max:255';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data',
                'data' => $validator->errors()
            ]);
        }

        // Update fields sesuai request
        if ($request->has('username')) $user->username = $request->username;
        if ($request->filled('password')) $user->password = Hash::make($request->password);
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('role')) $user->role = $request->role;
        if ($request->has('mahasiswa_id')) $user->mahasiswa_id = $request->mahasiswa_id;
        if ($request->has('staff_id')) $user->staff_id = $request->staff_id;
        if ($request->has('is_active')) $user->is_active = $request->is_active;
        if ($request->has('nama_lengkap')) $user->nama_lengkap = $request->nama_lengkap;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data User',
            'data' => $user->load(['mahasiswa', 'staff'])
        ]);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data User Tidak Ditemukan'
            ], 404);
        }

        // Mencegah admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak dapat menghapus akun sendiri'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Hapus Data User'
        ]);
    }

    // Method tambahan untuk dashboard admin
    public function getDashboardStats()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'inactive_users' => User::where('is_active', false)->count(),
                'users_by_role' => [
                    'admin' => User::where('role', 'admin')->count(),
                    'staff' => User::where('role', 'staff')->count(),
                    'mahasiswa' => User::where('role', 'mahasiswa')->count(),
                ],
                'recent_users' => User::latest()->limit(5)->get(['id', 'username', 'email', 'role', 'created_at'])
            ];

            return response()->json([
                'status' => true,
                'message' => 'Dashboard stats berhasil diambil',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil dashboard stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}