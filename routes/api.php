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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum', 'role:admin,staff,mahasiswa'])->group(function () {
    Route::apiResource('user', UsersController::class);
});

Route::apiResource('mahasiswa', MahasiswaController::class);
Route::apiResource('beasiswa', BeasiswaController::class);
Route::apiResource('staff', StaffController::class);
Route::apiResource('fakultas', FakultasController::class);
Route::apiResource('golongan-ukt', GolonganUktController::class);
Route::apiResource('tahun-akademik', TahunAkademikController::class);
Route::apiResource('periode-pembayaran', PeriodePembayaranController::class);
Route::apiResource('ukt-semester', UktSemesterController::class);
Route::apiResource('pembayaran-ukt-semester', PembayaranUktSemesterController::class);
Route::apiResource('detail-pembayaran', DetailPembayaranController::class);
Route::apiResource('kelas', KelasController::class);
Route::apiResource('program-studi', ProgramStudiController::class);
Route::apiResource('tingkat', TingkatController::class);
Route::apiResource('jenis-pembayaran', JenisPembayaranController::class);
Route::apiResource('log-aktivitas', LogAktivitasController::class);
Route::apiResource('enrollment-mahasiswa', EnrollmentMahasiswaController::class);