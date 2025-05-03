<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisPembayaranController extends Controller
{
    public function index()
    {
        $data = JenisPembayaran::orderBy('nama_jenis')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Jenis Pembayaran Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_angsuran' => 'required|boolean',
            'max_angsuran' => 'nullable|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Jenis Pembayaran',
                'data' => $validator->errors()
            ], 400);
        }

        $data = JenisPembayaran::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Jenis Pembayaran',
            'data' => $data
        ], 201);
    }

    public function show($id)
    {
        $data = JenisPembayaran::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis Pembayaran Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Jenis Pembayaran Ditemukan',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = JenisPembayaran::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis Pembayaran Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('nama_jenis')) {
            $rules['nama_jenis'] = 'string|max:255';
        }

        if ($request->has('deskripsi')) {
            $rules['deskripsi'] = 'nullable|string';
        }

        if ($request->has('is_angsuran')) {
            $rules['is_angsuran'] = 'boolean';
        }

        if ($request->has('max_angsuran')) {
            $rules['max_angsuran'] = 'nullable|integer|min:1';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Jenis Pembayaran',
                'data' => $validator->errors()
            ], 400);
        }

        $data->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Jenis Pembayaran',
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        $data = JenisPembayaran::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis Pembayaran Tidak Ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Jenis Pembayaran'
        ], 200);
    }
}
