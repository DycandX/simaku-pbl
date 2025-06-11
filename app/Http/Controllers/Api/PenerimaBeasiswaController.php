<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenerimaBeasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenerimaBeasiswaController extends Controller
{
    /**
     * Menampilkan daftar seluruh penerima beasiswa,
     * atau difilter berdasarkan NIM jika parameter tersedia.
     */
    public function index(Request $request)
    {
        $query = PenerimaBeasiswa::with(['beasiswa', 'mahasiswa'])->orderByDesc('id');

        // Filter berdasarkan NIM jika disediakan
        if ($request->has('nim')) {
            $query->where('nim', $request->nim);
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data Beasiswa Ditemukan',
            'data' => $data
        ], 200);
    }

    /**
     * Menampilkan data penerima beasiswa berdasarkan NIM.
     */
    public function show($nim)
    {
        $data = PenerimaBeasiswa::with(['beasiswa', 'mahasiswa'])
            ->where('nim', $nim)
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Penerima Beasiswa dengan NIM tersebut tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Penerima Beasiswa Ditemukan',
            'data' => $data
        ], 200);
    }

    /**
     * Menyimpan data baru penerima beasiswa.
     */
    public function store(Request $request)
    {
        $rules = [
            'nim' => 'required|exists:mahasiswa,nim',
            'id_beasiswa' => 'required|exists:beasiswa,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
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

    /**
     * Memperbarui data penerima beasiswa berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $data = PenerimaBeasiswa::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Penerima Beasiswa Tidak Ditemukan'
            ], 404);
        }

        $rules = [
            'nim' => 'sometimes|required|exists:mahasiswa,nim',
            'id_beasiswa' => 'sometimes|required|exists:beasiswa,id',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|nullable|date|after_or_equal:tanggal_mulai',
            'nominal' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|in:aktif,nonaktif',
            'keterangan' => 'sometimes|nullable|string',
            'created_by' => 'sometimes|nullable|exists:staff,id',
        ];

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

    /**
     * Menghapus data penerima beasiswa berdasarkan ID.
     */
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
