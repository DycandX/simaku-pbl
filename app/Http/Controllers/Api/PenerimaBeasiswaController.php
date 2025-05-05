<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenerimaBeasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenerimaBeasiswaController extends Controller
{
    public function index()
    {
        $data = PenerimaBeasiswa::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Penerima Beasiswa Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nim' => 'required|exists:mahasiswa,nim',
            'id_beasiswa' => 'required|exists:beasiswa,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'nominal' => 'required|numeric|min:0',
            'status' => 'in:aktif,non-aktif',
            'keterangan' => 'nullable|string',
            'created_by' => 'nullable|exists:staff,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Penerima Beasiswa',
                'data' => $validator->errors()
            ], 400);
        }

        $data = PenerimaBeasiswa::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Penerima Beasiswa',
            'data' => $data
        ], 201);
    }

    public function show($id)
    {
        $data = PenerimaBeasiswa::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Penerima Beasiswa Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Penerima Beasiswa Ditemukan',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = PenerimaBeasiswa::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Penerima Beasiswa Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('nim')) {
            $rules['nim'] = 'exists:mahasiswa,nim';
        }

        if ($request->has('id_beasiswa')) {
            $rules['id_beasiswa'] = 'exists:beasiswa,id';
        }

        if ($request->has('tanggal_mulai')) {
            $rules['tanggal_mulai'] = 'date';
        }

        if ($request->has('tanggal_selesai')) {
            $rules['tanggal_selesai'] = 'nullable|date|after_or_equal:tanggal_mulai';
        }

        if ($request->has('nominal')) {
            $rules['nominal'] = 'numeric|min:0';
        }

        if ($request->has('status')) {
            $rules['status'] = 'in:aktif,non-aktif';
        }

        if ($request->has('keterangan')) {
            $rules['keterangan'] = 'nullable|string';
        }

        if ($request->has('created_by')) {
            $rules['created_by'] = 'nullable|exists:staff,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Penerima Beasiswa',
                'data' => $validator->errors()
            ], 400);
        }

        $data->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Penerima Beasiswa',
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        $data = PenerimaBeasiswa::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Penerima Beasiswa Tidak Ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Penerima Beasiswa'
        ], 200);
    }
}
