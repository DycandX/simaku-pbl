<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnrollmentMahasiswaController extends Controller
{
    // public function index()
    // {
    //     $data = EnrollmentMahasiswa::with(['mahasiswa', 'kelas', 'tingkat', 'tahunAkademik'])
    //         ->orderByDesc('id')
    //         ->get();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Data Enrollment Mahasiswa ditemukan',
    //         'data' => $data
    //     ], 200);
    // }
    public function index(Request $request)
    {
        $query = EnrollmentMahasiswa::with(['mahasiswa', 'kelas', 'tingkat', 'tahunAkademik'])
            ->orderByDesc('id');

        // Jika parameter 'nim' ada, filter berdasarkan NIM mahasiswa
        if ($request->has('nim')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data Enrollment Mahasiswa ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nim' => 'required|exists:mahasiswa,nim',
            'id_kelas' => 'required|exists:kelas,id',
            'id_tingkat' => 'required|exists:tingkat,id',
            'id_tahun_akademik' => 'required|exists:tahun_akademik,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $enrollment = EnrollmentMahasiswa::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menambahkan Enrollment Mahasiswa',
            'data' => $enrollment
        ], 201);
    }

    public function show($id)
    {
        $data = EnrollmentMahasiswa::with(['mahasiswa', 'kelas', 'tingkat', 'tahunAkademik'])->find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = EnrollmentMahasiswa::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $rules = [
            'nim' => 'exists:mahasiswa,nim',
            'id_kelas' => 'exists:kelas,id',
            'id_tingkat' => 'exists:tingkat,id',
            'id_tahun_akademik' => 'exists:tahun_akademik,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $data->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Update Enrollment Mahasiswa',
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        $data = EnrollmentMahasiswa::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menghapus Enrollment Mahasiswa'
        ], 200);
    }
}
