<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenerimaBeasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenerimaBeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = PenerimaBeasiswa::with(['beasiswa'])->orderByDesc('id');

        // Jika parameter 'nim' ada, filter berdasarkan NIM mahasiswa
        if ($request->has('nim')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data Beasiswa Ditemukan',
            'data' => $data
        ], 200);
    }


    public function store(Request $request)
    {
        $rules = [
            'nim' => 'required|exists:mahasiswa,nim',
            'id_beasiswa' => 'required|exists:beasiswa,id',
            'periode_pencairan' => 'required|string',
            'tanggal_cair' => 'nullable|date',
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

        // Aturan validasi - sama dengan fungsi store
        $rules = [
            'nim' => 'sometimes|required|exists:mahasiswa,nim',
            'id_beasiswa' => 'sometimes|required|exists:beasiswa,id',
            'periode_pencairan' => 'sometimes|required|string',
            'tanggal_cair' => 'sometimes|nullable|date',
            'nominal' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|in:aktif,non-aktif',
            'keterangan' => 'sometimes|nullable|string',
            'created_by' => 'sometimes|nullable|exists:staff,id',
        ];

        // Validasi input
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Penerima Beasiswa',
                'data' => $validator->errors()
            ], 400);
        }

        // Update data dengan input yang telah divalidasi
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
