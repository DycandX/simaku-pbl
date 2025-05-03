<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BeasiswaController extends Controller
{
    public function index()
    {
        $data = Beasiswa::orderBy('nama_beasiswa', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Beasiswa Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_beasiswa' => 'required|string',
            'jenis' => 'required|string',
            'deskripsi' => 'required|string',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'nominal_max' => 'required|numeric|min:0',
            'persyaratan' => 'required|string',
            'status' => 'required|in:aktif,non-aktif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data Beasiswa',
                'data' => $validator->errors()
            ], 400);
        }

        $beasiswa = Beasiswa::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Data Beasiswa',
            'data' => $beasiswa
        ], 201);
    }

    public function show($id)
    {
        $beasiswa = Beasiswa::find($id);

        if ($beasiswa) {
            return response()->json([
                'status' => true,
                'message' => 'Data Beasiswa Ditemukan',
                'data' => $beasiswa
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Beasiswa Tidak Ditemukan'
            ], 404);
        }
    }

    
    public function update(Request $request, $id)
    {
        $beasiswa = Beasiswa::find($id);

        if (empty($beasiswa)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Beasiswa Tidak Ditemukan'
            ], 404);
        }

        // Validasi dinamis hanya untuk field yang dikirim
        $rules = [];

        if ($request->has('nama_beasiswa')) {
            $rules['nama_beasiswa'] = 'string';
        }

        if ($request->has('jenis')) {
            $rules['jenis'] = 'string';
        }

        if ($request->has('deskripsi')) {
            $rules['deskripsi'] = 'string';
        }

        if ($request->has('periode_mulai')) {
            $rules['periode_mulai'] = 'date';
        }

        if ($request->has('periode_selesai')) {
            $rules['periode_selesai'] = 'date';
            if ($request->has('periode_mulai')) {
                $rules['periode_selesai'] .= '|after_or_equal:periode_mulai';
            }
        }

        if ($request->has('nominal_max')) {
            $rules['nominal_max'] = 'numeric|min:0';
        }

        if ($request->has('persyaratan')) {
            $rules['persyaratan'] = 'string';
        }

        if ($request->has('status')) {
            $rules['status'] = 'in:aktif,non-aktif';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data Beasiswa',
                'data' => $validator->errors()
            ], 400);
        }

        // Update hanya field yang dikirim
        $validatedData = $validator->validated();
        foreach ($validatedData as $field => $value) {
            $beasiswa->$field = $value;
        }

        $beasiswa->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data Beasiswa',
            'data' => $beasiswa
        ], 200);
    }


    public function destroy($id)
    {
        $beasiswa = Beasiswa::find($id);

        if (empty($beasiswa)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Beasiswa Tidak Ditemukan'
            ], 404);
        }

        $beasiswa->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Data Beasiswa'
        ], 200);
    }
}
