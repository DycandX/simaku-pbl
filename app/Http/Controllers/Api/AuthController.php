<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * REGISTER - Membuat user baru dan mengembalikan token
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username'   => 'required|string|max:255',
            'email'      => 'required|string|email|unique:users',
            'password'   => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:admin,staff,mahasiswa',
            'is_active'  => 'required|integer',
        ]);

        $user = User::create([
            'username'   => $validated['username'],
            'email'      => $validated['email'],
            'role'       => $validated['role'],
            'is_active'  => $validated['is_active'],
            'password'   => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'User registered successfully',
            'access_token' => $token,
            'token_type'   => 'Bearer',
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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
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
