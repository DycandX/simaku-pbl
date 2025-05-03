<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GolonganUkt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GolonganUktController extends Controller
{
    public function index()
    {
        $data = GolonganUkt::orderBy('level')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Golongan UKT Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'level' => 'required|integer|unique:golongan_ukt,level',
            'nominal' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'tahun_berlaku' => 'required|digits:4|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Golongan UKT',
                'data' => $validator->errors()
            ], 400);
        }

        $golongan = GolonganUkt::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Golongan UKT',
            'data' => $golongan
        ], 201);
    }

    public function show($id)
    {
        $golongan = GolonganUkt::find($id);

        if (!$golongan) {
            return response()->json([
                'status' => false,
                'message' => 'Golongan UKT Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Golongan UKT Ditemukan',
            'data' => $golongan
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $golongan = GolonganUkt::find($id);

        if (!$golongan) {
            return response()->json([
                'status' => false,
                'message' => 'Golongan UKT Tidak Ditemukan'
            ], 404);
        }

        // Validasi hanya field yang dikirim
        $rules = [];

        if ($request->has('level')) {
            $rules['level'] = 'integer|unique:golongan_ukt,level,' . $id;
        }

        if ($request->has('nominal')) {
            $rules['nominal'] = 'numeric';
        }

        if ($request->has('deskripsi')) {
            $rules['deskripsi'] = 'string|nullable';
        }

        if ($request->has('tahun_berlaku')) {
            $rules['tahun_berlaku'] = 'digits:4|integer';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Golongan UKT',
                'data' => $validator->errors()
            ], 400);
        }

        $golongan->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Golongan UKT',
            'data' => $golongan
        ], 200);
    }

    public function destroy($id)
    {
        $golongan = GolonganUkt::find($id);

        if (!$golongan) {
            return response()->json([
                'status' => false,
                'message' => 'Golongan UKT Tidak Ditemukan'
            ], 404);
        }

        $golongan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Golongan UKT'
        ], 200);
    }
}
