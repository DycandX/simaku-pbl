<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $data = ProgramStudi::orderBy('nama_prodi')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Program Studi Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_prodi'   => 'required|string|max:255',
            'id_fakultas'  => 'required|exists:fakultas,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal Menambahkan Program Studi',
                'data'    => $validator->errors()
            ], 400);
        }

        $prodi = ProgramStudi::create($validator->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Sukses Menambahkan Program Studi',
            'data'    => $prodi
        ], 201);
    }

    public function show($id)
    {
        $prodi = ProgramStudi::find($id);

        if (!$prodi) {
            return response()->json([
                'status'  => false,
                'message' => 'Program Studi Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Program Studi Ditemukan',
            'data'    => $prodi
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $prodi = ProgramStudi::find($id);

        if (!$prodi) {
            return response()->json([
                'status'  => false,
                'message' => 'Program Studi Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('nama_prodi')) {
            $rules['nama_prodi'] = 'string|max:255';
        }

        if ($request->has('id_fakultas')) {
            $rules['id_fakultas'] = 'exists:fakultas,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal Update Program Studi',
                'data'    => $validator->errors()
            ], 400);
        }

        $prodi->update($validator->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Sukses Update Program Studi',
            'data'    => $prodi
        ], 200);
    }

    public function destroy($id)
    {
        $prodi = ProgramStudi::find($id);

        if (!$prodi) {
            return response()->json([
                'status'  => false,
                'message' => 'Program Studi Tidak Ditemukan'
            ], 404);
        }

        $prodi->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Sukses Menghapus Program Studi'
        ], 200);
    }
}
