<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnrollmentMahasiswaController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = EnrollmentMahasiswa::with([
    //         'mahasiswa',
    //         'programStudi',
    //         'golonganUkt',
    //         'kelas',
    //         'tingkat',
    //         'tahunAkademik'
    //     ])->orderByDesc('id');

    //     // // Jika parameter 'id_mahasiswa' tersedia, filter berdasarkan ID mahasiswa
    //     // if ($request->has('id_mahasiswa')) {
    //     //     $query->where('id_mahasiswa', $request->id_mahasiswa);
    //     // }

    //     $data = $query->get();

    //     return response()->json([
    //         'status' => true,
    //         'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data Enrollment Mahasiswa ditemukan',
    //         'data' => $data
    //     ], 200);
    // }
    public function index(Request $request)
    {
        $query = EnrollmentMahasiswa::with([
            'mahasiswa',
            'programStudi',
            'golonganUkt',
            'kelas',
            'tingkat',
            'tahunAkademik'
        ]);

        // Jika ada query string nim, filter datanya
        if ($request->has('nim')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Enrollment Mahasiswa ditemukan',
            'data' => $data
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'id_program_studi' => 'required|exists:program_studi,id',
            'id_golongan_ukt' => 'required|exists:golongan_ukt,id',
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
            'data' => $enrollment->load([
                'mahasiswa', 'programStudi', 'golonganUkt', 'kelas', 'tingkat', 'tahunAkademik'
            ])
        ], 201);
    }

    public function show($key)
    {
        $data = EnrollmentMahasiswa::with([
            'mahasiswa',
            'programStudi',
            'golonganUkt',
            'kelas',
            'tingkat',
            'tahunAkademik'
        ])
        ->where('id', $key) // cari berdasarkan ID
        ->orWhereHas('mahasiswa', function ($query) use ($key) {
            $query->where('nim', $key); // atau cari berdasarkan NIM mahasiswa
        })
        ->first();

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

        $rules = [];

        if ($request->has('id_mahasiswa')) {
            $rules['id_mahasiswa'] = 'exists:mahasiswa,id';
        }

        if ($request->has('id_program_studi')) {
            $rules['id_program_studi'] = 'exists:program_studi,id';
        }

        if ($request->has('id_golongan_ukt')) {
            $rules['id_golongan_ukt'] = 'exists:golongan_ukt,id';
        }

        if ($request->has('id_kelas')) {
            $rules['id_kelas'] = 'exists:kelas,id';
        }

        if ($request->has('id_tingkat')) {
            $rules['id_tingkat'] = 'exists:tingkat,id';
        }

        if ($request->has('id_tahun_akademik')) {
            $rules['id_tahun_akademik'] = 'exists:tahun_akademik,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        // Update hanya field yang tervalidasi
        $data->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Update Enrollment Mahasiswa',
            'data' => $data->load([
                'mahasiswa', 'programStudi', 'golonganUkt', 'kelas', 'tingkat', 'tahunAkademik'
            ])
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
