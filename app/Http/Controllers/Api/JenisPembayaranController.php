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
        $validator = Validator::make($request->all(), [
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_angsuran' => 'required|boolean',
            'max_angsuran' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Jenis Pembayaran',
                'errors' => $validator->errors()
            ], 422);
        }

        // Jika bukan angsuran, pastikan max_angsuran diset null
        if (!$request->is_angsuran) {
            $request->merge(['max_angsuran' => null]);
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
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Jika is_angsuran dikirim dan false, maka max_angsuran harus null
        if (array_key_exists('is_angsuran', $validated) && $validated['is_angsuran'] === false) {
            $validated['max_angsuran'] = null;
        }

        $data->update($validated);

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
