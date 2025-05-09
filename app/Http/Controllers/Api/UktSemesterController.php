<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UktSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UktSemesterController extends Controller
{
    public function index(Request $request)
    {
        $query = UktSemester::with(['mahasiswa', 'golonganUkt', 'periodePembayaran'])->orderByDesc('id');

        // Jika parameter 'nim' ada, filter berdasarkan NIM mahasiswa
        if ($request->has('nim')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data UKT Semester Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nim' => 'required|exists:mahasiswa,nim',
            'id_golongan_ukt' => 'required|exists:golongan_ukt,id',
            'status' => 'required|string|in:aktif,non-aktif',
            'id_periode_pembayaran' => 'required|exists:periode_pembayaran,id',
            'jumlah_ukt' => 'required|numeric|min:0'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data UKT Semester',
                'data' => $validator->errors()
            ], 400);
        }

        $ukt = UktSemester::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Data UKT Semester',
            'data' => $ukt
        ], 201);
    }

    public function show($id)
    {
        $ukt = UktSemester::with(['mahasiswa', 'golonganUkt', 'periodePembayaran'])->find($id);

        if (!$ukt) {
            return response()->json([
                'status' => false,
                'message' => 'Data UKT Semester Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data UKT Semester Ditemukan',
            'data' => $ukt
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $ukt = UktSemester::find($id);

        if (!$ukt) {
            return response()->json([
                'status' => false,
                'message' => 'Data UKT Semester Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('nim')) {
            $rules['nim'] = 'exists:mahasiswa,nim';
        }

        if ($request->has('id_golongan_ukt')) {
            $rules['id_golongan_ukt'] = 'exists:golongan_ukt,id';
        }

        if ($request->has('status')) {
            $rules['status'] = 'string|in:aktif,non-aktif';
        }

        if ($request->has('id_periode_pembayaran')) {
            $rules['id_periode_pembayaran'] = 'exists:periode_pembayaran,id';
        }

        if ($request->has('jumlah_ukt')) {
            $rules['jumlah_ukt'] = 'numeric|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data UKT Semester',
                'data' => $validator->errors()
            ], 400);
        }

        $ukt->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data UKT Semester',
            'data' => $ukt
        ], 200);
    }

    public function destroy($id)
    {
        $ukt = UktSemester::find($id);

        if (!$ukt) {
            return response()->json([
                'status' => false,
                'message' => 'Data UKT Semester Tidak Ditemukan'
            ], 404);
        }

        $ukt->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Data UKT Semester'
        ], 200);
    }
}
