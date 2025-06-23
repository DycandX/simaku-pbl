<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tingkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TingkatController extends Controller
{
    public function index()
    {
        $data = Tingkat::orderBy('nama_tingkat')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Tingkat Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_tingkat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Tingkat',
                'data' => $validator->errors()
            ], 400);
        }

        $tingkat = Tingkat::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Tingkat',
            'data' => $tingkat
        ], 201);
    }

    public function show($id)
    {
        $tingkat = Tingkat::find($id);

        if (!$tingkat) {
            return response()->json([
                'status' => false,
                'message' => 'Tingkat Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tingkat Ditemukan',
            'data' => $tingkat
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $tingkat = Tingkat::find($id);

        if (!$tingkat) {
            return response()->json([
                'status' => false,
                'message' => 'Tingkat Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('nama_tingkat')) {
            $rules['nama_tingkat'] = 'string|max:255';
        }

        if ($request->has('deskripsi')) {
            $rules['deskripsi'] = 'nullable|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Tingkat',
                'data' => $validator->errors()
            ], 400);
        }

        $tingkat->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Tingkat',
            'data' => $tingkat
        ], 200);
    }

    public function destroy($id)
    {
        $tingkat = Tingkat::find($id);

        if (!$tingkat) {
            return response()->json([
                'status' => false,
                'message' => 'Tingkat Tidak Ditemukan'
            ], 404);
        }

        $tingkat->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Tingkat'
        ], 200);
    }
}
