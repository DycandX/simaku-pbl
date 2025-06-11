<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function index()
    {
        $data = Staff::with('user')->orderBy('nama_lengkap', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Staff Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nip' => 'required|string|unique:staff,nip',
            'nama_lengkap' => 'required|string',
            'jabatan' => 'required|string',
            'unit_kerja' => 'required|string',
            'user_email' => 'nullable|email|unique:users,email',
            'user_password' => 'nullable|string|min:6'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data Staff',
                'data' => $validator->errors()
            ], 400);
        }

        // Buat staff
        $staff = Staff::create($validator->validated());

        // Jika request mengandung data user, buat user terkait
        if ($request->filled('user_email') && $request->filled('user_password')) {
            $user = new User([
                'email' => $request->user_email,
                'password' => bcrypt($request->user_password),
                'role' => 'staff',
                'is_active' => true
            ]);

            $staff->user()->save($user);
        }

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Data Staff',
            'data' => $staff->load('user')
        ], 201);
    }

    public function show($id)
    {
        $staff = Staff::with('user')->find($id);

        if ($staff) {
            return response()->json([
                'status' => true,
                'message' => 'Data Staff Ditemukan',
                'data' => $staff
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Staff Tidak Ditemukan'
            ], 404);
        }
    }

    // public function update(Request $request, $id)
    // {
    //     $staff = Staff::find($id);

    //     if (!$staff) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Data Staff Tidak Ditemukan'
    //         ], 404);
    //     }

    //     $rules = [];

    //     if ($request->has('nip')) {
    //         $rules['nip'] = 'string|unique:staff,nip,' . $id;
    //     }
    //     if ($request->has('nama_lengkap')) {
    //         $rules['nama_lengkap'] = 'string';
    //     }
    //     if ($request->has('jabatan')) {
    //         $rules['jabatan'] = 'string';
    //     }
    //     if ($request->has('unit_kerja')) {
    //         $rules['unit_kerja'] = 'string';
    //     }

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Gagal Update Data Staff',
    //             'data' => $validator->errors()
    //         ], 400);
    //     }

    //     $staff->update($validator->validated());

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Sukses Update Data Staff',
    //         'data' => $staff
    //     ], 200);
    // }
    public function update(Request $request, $id)
    {
        $staff = Staff::with('user')->find($id);

        if (!$staff) {
            return response()->json([
                'status' => false,
                'message' => 'Data Staff Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('nip')) {
            $rules['nip'] = 'string|unique:staff,nip,' . $id;
        }
        if ($request->has('nama_lengkap')) {
            $rules['nama_lengkap'] = 'string';
        }
        if ($request->has('jabatan')) {
            $rules['jabatan'] = 'string';
        }
        if ($request->has('unit_kerja')) {
            $rules['unit_kerja'] = 'string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data Staff',
                'data' => $validator->errors()
            ], 400);
        }

        $staff->update($validator->validated());

        // Update user jika ada data
        if ($request->has('user_email') || $request->has('user_password')) {
            $userData = [];

            if ($request->has('user_email')) {
                $request->validate([
                    'user_email' => 'email|unique:users,email,' . optional($staff->user)->id
                ]);
                $userData['email'] = $request->user_email;
            }

            if ($request->has('user_password')) {
                $request->validate([
                    'user_password' => 'string|min:6'
                ]);
                $userData['password'] = bcrypt($request->user_password);
            }

            if ($staff->hasUser()) {
                $staff->user()->update($userData);
            } else {
                $staff->user()->create(array_merge($userData, [
                    'role' => 'staff',
                    'is_active' => true
                ]));
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data Staff',
            'data' => $staff->load('user')
        ], 200);
    }


    public function destroy($id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json([
                'status' => false,
                'message' => 'Data Staff Tidak Ditemukan'
            ], 404);
        }

        // Hapus juga user jika ada
        if ($staff->hasUser()) {
            $staff->user->delete();
        }

        $staff->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Data Staff'
        ], 200);
    }
}
