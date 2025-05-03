<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index()
    {
        $data = Mahasiswa::orderBy('nim', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Mahasiswa Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nim' => 'required|string|unique:mahasiswa,nim',
            'nama_lengkap' => 'required|string',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string',
            'email' => 'required|email|unique:mahasiswa,email',
            'foto_path' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data Mahasiswa',
                'data' => $validator->errors()
            ], 400);
        }

        $mahasiswa = Mahasiswa::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Data Mahasiswa',
            'data' => $mahasiswa
        ], 201);
    }

    public function show($nim)
    {
        $mahasiswa = Mahasiswa::find($nim);

        if ($mahasiswa) {
            return response()->json([
                'status' => true,
                'message' => 'Data Mahasiswa Ditemukan',
                'data' => $mahasiswa
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Mahasiswa Tidak Ditemukan'
            ], 404);
        }
    }


    public function update(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::find($nim);

        if (empty($mahasiswa)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Mahasiswa Tidak Ditemukan'
            ], 404);
        }

        // Validasi dinamis hanya untuk field yang dikirim
        $rules = [];

        if ($request->has('nama_lengkap')) {
            $rules['nama_lengkap'] = 'string';
        }

        if ($request->has('alamat')) {
            $rules['alamat'] = 'string';
        }

        if ($request->has('no_telepon')) {
            $rules['no_telepon'] = 'string';
        }

        if ($request->has('email')) {
            // Gunakan pengecualian untuk nim agar tidak bentrok dengan dirinya sendiri
            $rules['email'] = 'email|unique:mahasiswa,email,' . $nim . ',nim';
        }

        if ($request->has('foto_path')) {
            $rules['foto_path'] = 'nullable|string';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data Mahasiswa',
                'data' => $validator->errors()
            ], 400);
        }

        // Update hanya field yang dikirim
        $dataToUpdate = $validator->validated();
        $mahasiswa->update($dataToUpdate);

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data Mahasiswa',
            'data' => $mahasiswa
        ], 200);
    }

    public function destroy($nim)
    {
        $mahasiswa = Mahasiswa::find($nim);

        if (empty($mahasiswa)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Mahasiswa Tidak Ditemukan'
            ], 404);
        }

        $mahasiswa->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Data Mahasiswa'
        ], 200);
    }
}
