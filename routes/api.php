<?php

use App\Http\Controllers\Api\BeasiswaController;
use App\Http\Controllers\Api\DetailPembayaranController;
use App\Http\Controllers\Api\EnrollmentMahasiswaController;
use App\Http\Controllers\Api\FakultasController;
use App\Http\Controllers\Api\GolonganUktController;
use App\Http\Controllers\Api\JenisPembayaranController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\LogAktivitasController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\PembayaranUktSemesterController;
use App\Http\Controllers\Api\PeriodePembayaranController;
use App\Http\Controllers\Api\ProgramStudiController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\TahunAkademikController;
use App\Http\Controllers\Api\TingkatController;
use App\Http\Controllers\Api\UktSemesterController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PenerimaBeasiswaController;
use App\Http\Controllers\API\PengajuanCicilanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// login dan logout untuk semua users
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('log-aktivitas', LogAktivitasController::class);
});

// route untuk role staff
Route::middleware(['auth:sanctum', 'role:staff'])->group(function () {
    Route::apiResource('beasiswa', BeasiswaController::class);
    Route::apiResource('penerima-beasiswa', PenerimaBeasiswaController::class); //belom buat
    Route::apiResource('staff', StaffController::class);
    Route::apiResource('golongan-ukt', GolonganUktController::class);
    Route::apiResource('tahun-akademik', TahunAkademikController::class);
    Route::apiResource('periode-pembayaran', PeriodePembayaranController::class);
    //Route::apiResource('ukt-semester', UktSemesterController::class);
    // Route::apiResource('pembayaran-ukt-semester', PembayaranUktSemesterController::class);
    // Route::apiResource('detail-pembayaran', DetailPembayaranController::class);
    Route::apiResource('kelas', KelasController::class);
    Route::apiResource('program-studi', ProgramStudiController::class);
    Route::apiResource('tingkat', TingkatController::class);
    Route::apiResource('fakultas', FakultasController::class);
    Route::apiResource('jenis-pembayaran', JenisPembayaranController::class);
    Route::apiResource('enrollment-mahasiswa', EnrollmentMahasiswaController::class);
    
});


// route untuk role mahasiswa
Route::middleware(['auth:sanctum', 'role:staff,mahasiswa'])->group(function () {
    // Mahasiswa hanya bisa akses data yang relevan ke dirinya
    // Route::get('mahasiswa/{id}', [MahasiswaController::class, 'show']);
    Route::apiResource('pengajuan-cicilan', PengajuanCicilanController::class);
    Route::apiResource('mahasiswa', MahasiswaController::class);
    Route::get('beasiswa', [BeasiswaController::class, 'index']);
    Route::get('penerima-beasiswa', [PenerimaBeasiswaController::class, 'index']);
    Route::get('ukt-semester', [UktSemesterController::class, 'index']);
    Route::apiResource('ukt-semester', UktSemesterController::class);
    Route::apiResource('pembayaran-ukt-semester', PembayaranUktSemesterController::class);
    Route::apiResource('detail-pembayaran', DetailPembayaranController::class);
    Route::get('jenis-pembayaran', [JenisPembayaranController::class, 'index']);
    Route::get('periode-pembayaran', [PeriodePembayaranController::class, 'index']);
    Route::get('enrollment-mahasiswa', [EnrollmentMahasiswaController::class, 'index']);
});

// route untuk login semua
Route::middleware(['auth:sanctum', 'role:staff,mahasiswa,admin'])->group(function () {
    Route::apiResource('user', UsersController::class);
});