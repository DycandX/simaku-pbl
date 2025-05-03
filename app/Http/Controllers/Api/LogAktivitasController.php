<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $data = LogAktivitas::with('user')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Log Aktivitas Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_user' => 'required|exists:users,id',
            'aktivitas' => 'required|string|max:255',
            'ip_address' => 'nullable|ip',
            'user_agent' => 'nullable|string',
            'created_at' => 'nullable|date',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Log Aktivitas',
                'data' => $validator->errors()
            ], 400);
        }

        $data = LogAktivitas::create([
            'id_user' => $request->id_user,
            'aktivitas' => $request->aktivitas,
            'ip_address' => $request->ip_address ?? $request->ip(),
            'user_agent' => $request->user_agent ?? $request->userAgent(),
            'created_at' => $request->created_at ?? now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Log Aktivitas',
            'data' => $data
        ], 201);
    }

    public function show($id)
    {
        $data = LogAktivitas::with('user')->find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Log Aktivitas Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Log Aktivitas Ditemukan',
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        $data = LogAktivitas::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Log Aktivitas Tidak Ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Log Aktivitas'
        ], 200);
    }
}
