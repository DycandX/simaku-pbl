<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * REGISTER - Membuat user baru dan mengembalikan token
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username'      => 'required|string|max:255|unique:users,username',
            'email'         => 'required|string|email|unique:users,email',
            'password'      => 'required|string|min:6|confirmed',
            'role'          => 'required|string|in:admin,staff,mahasiswa',
            'is_active'     => 'required|boolean',
            'mahasiswa_id'  => 'nullable|exists:mahasiswa,id|required_if:role,mahasiswa',
            'staff_id'      => 'nullable|exists:staff,id|required_if:role,staff',
        ]);

        $user = User::create([
            'username'      => $validated['username'],
            'email'         => $validated['email'],
            'role'          => $validated['role'],
            'is_active'     => $validated['is_active'],
            'mahasiswa_id'  => $validated['role'] === 'mahasiswa' ? $validated['mahasiswa_id'] : null,
            'staff_id'      => $validated['role'] === 'staff' ? $validated['staff_id'] : null,
            'password'      => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'User registered successfully',
            'access_token'=> $token,
            'token_type'  => 'Bearer',
            'user'        => [
                'id'           => $user->id,
                'username'     => $user->username,
                'email'        => $user->email,
                'role'         => $user->role,
                'display_name' => $user->display_name,
                'is_active'    => $user->is_active,
            ]
        ], 201);
    }

    /**
     * LOGIN - Autentikasi user dan generate token
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->is_active) {
            return response()->json([
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
            ], 403);
        }

        // Update last login
        $user->update(['last_login' => Carbon::now()]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login successful',
            'access_token'=> $token,
            'token_type'  => 'Bearer',
            'user'        => [
                'id'           => $user->id,
                'username'     => $user->username,
                'email'        => $user->email,
                'role'         => $user->role,
                'display_name' => $user->display_name,
                'is_active'    => $user->is_active,
            ]
        ]);
    }

    /**
     * LOGOUT - Menghapus token saat ini
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
