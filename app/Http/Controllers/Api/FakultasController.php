<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FakultasController extends Controller
{
    public function index()
    {
        $data = Fakultas::orderBy('nama_fakultas', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Fakultas Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_fakultas' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data Fakultas',
                'data' => $validator->errors()
            ], 400);
        }

        $fakultas = Fakultas::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Data Fakultas',
            'data' => $fakultas
        ], 201);
    }

    public function show($id)
    {
        $fakultas = Fakultas::find($id);

        if ($fakultas) {
            return response()->json([
                'status' => true,
                'message' => 'Data Fakultas Ditemukan',
                'data' => $fakultas
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Fakultas Tidak Ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $fakultas = Fakultas::find($id);

        if (!$fakultas) {
            return response()->json([
                'status' => false,
                'message' => 'Data Fakultas Tidak Ditemukan'
            ], 404);
        }

        // Validasi hanya untuk field yang dikirim
        $rules = [];

        if ($request->has('nama_fakultas')) {
            $rules['nama_fakultas'] = 'string|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data Fakultas',
                'data' => $validator->errors()
            ], 400);
        }

        // Update hanya field yang dikirim
        $dataToUpdate = $validator->validated();
        $fakultas->update($dataToUpdate);

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data Fakultas',
            'data' => $fakultas
        ], 200);
    }


    public function destroy($id)
    {
        $fakultas = Fakultas::find($id);

        if (!$fakultas) {
            return response()->json([
                'status' => false,
                'message' => 'Data Fakultas Tidak Ditemukan'
            ], 404);
        }

        $fakultas->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Data Fakultas'
        ], 200);
    }
}
