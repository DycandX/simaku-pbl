<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; // Untuk password hashing

class UsersController extends Controller
{   

    public function index()
    {
        $data = User::orderBy('username', 'asc')->get();
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
            'role' => 'required',
            'is_active' => 'required|boolean'
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
        $user->password = Hash::make($request->password); // Hash password
        $user->email = $request->email;
        $user->role = $request->role;
        $user->is_active = $request->is_active;
        $user->last_login = null; // default null
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Memasukkan Data User'
        ],201);
    }

    public function show(string $id)
    {
        $data = User::find($id);
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
        if (empty($user)) {
            return response()->json([
                'status' => false,
                'message' => 'Data User Tidak Ditemukan'
            ], 404);
        }

        // Validasi dinamis berdasarkan field yang dikirim
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
            $rules['role'] = 'string';
        }

        if ($request->has('is_active')) {
            $rules['is_active'] = 'boolean';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data',
                'data' => $validator->errors()
            ]);
        }

        // Update hanya field yang dikirim
        if ($request->has('username')) {
            $user->username = $request->username;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('role')) {
            $user->role = $request->role;
        }

        if ($request->has('is_active')) {
            $user->is_active = $request->is_active;
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data User'
        ]);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        if (empty($user)) {
            return response()->json([
                'status' => false,
                'message' => 'Data User Tidak Ditemukan'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Hapus Data User'
        ]);
    }
}

