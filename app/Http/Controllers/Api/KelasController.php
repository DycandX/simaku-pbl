<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index()
    {
        $data = Kelas::with('programStudi')->orderBy('tahun_angkatan', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Kelas Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_kelas' => 'required|string|max:255',
            'id_prodi' => 'required|exists:program_studi,id',
            'tahun_angkatan' => 'required|integer|min:1900|max:' . date('Y'),
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Kelas',
                'data' => $validator->errors()
            ], 400);
        }

        $kelas = Kelas::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Kelas',
            'data' => $kelas
        ], 201);
    }

    public function show($id)
    {
        $kelas = Kelas::with('programStudi')->find($id);

        if (!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'Kelas Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Kelas Ditemukan',
            'data' => $kelas
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'Kelas Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('nama_kelas')) {
            $rules['nama_kelas'] = 'string|max:255';
        }

        if ($request->has('id_prodi')) {
            $rules['id_prodi'] = 'exists:program_studi,id';
        }

        if ($request->has('tahun_angkatan')) {
            $rules['tahun_angkatan'] = 'integer|min:1900|max:' . date('Y');
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Kelas',
                'data' => $validator->errors()
            ], 400);
        }

        $kelas->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Kelas',
            'data' => $kelas
        ], 200);
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'Kelas Tidak Ditemukan'
            ], 404);
        }

        $kelas->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Kelas'
        ], 200);
    }
}
