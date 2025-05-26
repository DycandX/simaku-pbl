<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCicilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengajuanCicilanController extends Controller
{
    public function index()
    {
        $data = PengajuanCicilan::orderByDesc('id')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Pengajuan Cicilan ditemukan',
            'data' => $data
        ], 200);
    }

    public function show($id)
    {
        $data = PengajuanCicilan::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Pengajuan Cicilan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Pengajuan Cicilan ditemukan',
            'data' => $data
        ], 200);
    }

    // public function store(Request $request)
    // {
    //     $rules = [
    //         'nama_lengkap' => 'required|string',
    //         'nim' => 'required|string',
    //         'fakultas' => 'required|string',
    //         'program_studi' => 'required|string',
    //         'tagihan' => 'required|string',
    //         'angsuran' => 'required|integer',
    //         'file_path' => 'required|string',
    //         'status' => 'required|boolean'
    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Gagal menambahkan Pengajuan Cicilan',
    //             'data' => $validator->errors()
    //         ], 400);
    //     }

    //     $data = PengajuanCicilan::create($validator->validated());

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Sukses menambahkan Pengajuan Cicilan',
    //         'data' => $data
    //     ], 201);
    // }
    public function store(Request $request)
    {
        $rules = [
            'nama_lengkap' => 'required|string',
            'nim' => 'required|string',
            'fakultas' => 'required|string',
            'program_studi' => 'required|string',
            'tagihan' => 'required|string',
            'angsuran' => 'required|integer',
            // Ganti validasi file:
            'file_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan Pengajuan Cicilan',
                'data' => $validator->errors()
            ], 400);
        }

        // Simpan file
        $originalName = $request->file('file_path')->getClientOriginalName();
        $filename = '-' . time() . '-' . $originalName;
        $filePath = $request->file('file_path')->storeAs('bukti', $filename);


        // Simpan ke database
        $data = PengajuanCicilan::create([
            ...$validator->validated(),
            'file_path' => $filePath,
            'status' => false, // default status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Sukses menambahkan Pengajuan Cicilan',
            'data' => $data
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $data = PengajuanCicilan::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Pengajuan Cicilan tidak ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('nama_lengkap')) $rules['nama_lengkap'] = 'string';
        if ($request->has('nim')) $rules['nim'] = 'string';
        if ($request->has('fakultas')) $rules['fakultas'] = 'string';
        if ($request->has('program_studi')) $rules['program_studi'] = 'string';
        if ($request->has('tagihan')) $rules['tagihan'] = 'string';
        if ($request->has('angsuran')) $rules['angsuran'] = 'integer';
        if ($request->has('file_path')) $rules['file_path'] = 'string';
        if ($request->has('status')) $rules['status'] = 'boolean';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal update Pengajuan Cicilan',
                'data' => $validator->errors()
            ], 400);
        }

        $data->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses update Pengajuan Cicilan',
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        $data = PengajuanCicilan::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Pengajuan Cicilan tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses menghapus Pengajuan Cicilan'
        ], 200);
    }
}
