<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
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
            'id_user' => 'required|exists:users,id',  // Pastikan user dengan id_user ada di tabel 'users'
            'nip' => 'required|string',
            'nama_lengkap' => 'required|string',
            'jabatan' => 'required|string',
            'unit_kerja' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data Staff',
                'data' => $validator->errors()
            ], 400);
        }

        $staff = Staff::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Data Staff',
            'data' => $staff
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

    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);

        if (empty($staff)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Staff Tidak Ditemukan'
            ], 404);
        }

        // Validasi dinamis hanya untuk field yang dikirim
        $rules = [];

        if ($request->has('id_user')) {
            $rules['id_user'] = 'exists:users,id';
        }

        if ($request->has('nip')) {
            $rules['nip'] = 'string';
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

        // Update hanya field yang dikirim
        $dataToUpdate = $validator->validated();
        $staff->update($dataToUpdate);

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data Staff',
            'data' => $staff
        ], 200);
    }


    public function destroy($id)
    {
        $staff = Staff::find($id);

        if (empty($staff)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Staff Tidak Ditemukan'
            ], 404);
        }

        $staff->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Data Staff'
        ], 200);
    }
}
