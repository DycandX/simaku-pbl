<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $data = TahunAkademik::orderByDesc('id')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Tahun Akademik Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'tahun_akademik' => 'required|string',
            'semester' => 'required|string|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,non-aktif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Tahun Akademik',
                'data' => $validator->errors()
            ], 400);
        }

        $tahunAkademik = TahunAkademik::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Tahun Akademik',
            'data' => $tahunAkademik
        ], 201);
    }

    public function show($id)
    {
        $tahunAkademik = TahunAkademik::find($id);

        if (!$tahunAkademik) {
            return response()->json([
                'status' => false,
                'message' => 'Tahun Akademik Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tahun Akademik Ditemukan',
            'data' => $tahunAkademik
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $tahunAkademik = TahunAkademik::find($id);

        if (!$tahunAkademik) {
            return response()->json([
                'status' => false,
                'message' => 'Tahun Akademik Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('tahun_akademik')) {
            $rules['tahun_akademik'] = 'string';
        }

        if ($request->has('semester')) {
            $rules['semester'] = 'string|in:Ganjil,Genap';
        }

        if ($request->has('tanggal_mulai')) {
            $rules['tanggal_mulai'] = 'date';
        }

        if ($request->has('tanggal_selesai')) {
            $rules['tanggal_selesai'] = 'date|after_or_equal:tanggal_mulai';
        }

        if ($request->has('status')) {
            $rules['status'] = 'in:aktif,non-aktif';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Tahun Akademik',
                'data' => $validator->errors()
            ], 400);
        }

        $tahunAkademik->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Tahun Akademik',
            'data' => $tahunAkademik
        ], 200);
    }

    public function destroy($id)
    {
        $tahunAkademik = TahunAkademik::find($id);

        if (!$tahunAkademik) {
            return response()->json([
                'status' => false,
                'message' => 'Tahun Akademik Tidak Ditemukan'
            ], 404);
        }

        $tahunAkademik->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Tahun Akademik'
        ], 200);
    }
}
