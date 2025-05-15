<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\CicilanController;
use App\Http\Controllers\GolonganUktController;
use App\Http\Controllers\Mahasiswa\DaftarUlangController;
use App\Http\Controllers\Mahasiswa\LihatTagihanUktController;
use App\Http\Controllers\Mahasiswa\BeasiswaController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use App\Http\Controllers\staff\staffBeasiswaController;
use App\Http\Controllers\staff\staffProfileController;
use App\Http\Controllers\staff\staffDataMahasiswaController;
use App\Http\Controllers\staffBeasiswaController as ControllersStaffBeasiswaController;
use App\Http\Controllers\staffDataMahasiswaController as ControllersStaffDataMahasiswaController;

// Login Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Dashboard Route - Dilindungi middleware
Route::middleware(['check.login'])->group(function () {
    Route::get('/lihat-tagihan-ukt', [LihatTagihanUktController::class, 'index'])->name('lihat-tagihan-ukt');
    Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
//     Route::get('/profile', function()
// {
//     return 'Hello WEorld';
// });
    Route::get('/daftar-ulang', [DaftarUlangController::class, 'index'])->name('daftar-ulang');
    Route::get('/golongan-ukt', [GolonganUktController::class, 'index'])->name('golongan-ukt');

    // Pengajuan Cicilan
    Route::get('/pengajuan-cicilan', [CicilanController::class, 'create'])->name('pengajuan.cicilan');
    Route::post('/pengajuan-cicilan', [CicilanController::class, 'store'])->name('pengajuan.cicilan.store');

    //Admin
    Route::get('/admin/kelola-pengguna', [\App\Http\Controllers\Admin\KelolaPenggunaController::class, 'index'])->name('admin.kelola-pengguna');

    //staff
    Route::get('/staff-app', function () {
        return view('layouts.staff-app');
    })->name('staff-app');

    Route::get('/staff-beasiswa', [staffBeasiswaController::class, 'index'])->name('staff-beasiswa');

    Route::get('/staff-profile', [staffprofileController::class, 'index'])->name('staff-profile');

    Route::get('/staff-keuangan/data-mahasiswa', [staffDataMahasiswaController::class, 'showDataMahasiswa'])->name('staff.keuangan.data-mahasiswa');
    //Route::get('/data-mahasiswa', [staffDataMahasiswaController::class, 'index'])->name('data-mahasiswa');
    
    Route::get('/staff/pembayaran-ukt', [\App\Http\Controllers\Staff\PembayaranUktStaffController::class, 'index'])->name('staff.pembayaran-ukt');

});


// Lihat Tagihan route
Route::get('/lihat-tagihan/{id}', [TagihanController::class, 'show'])->name('lihat-tagihan');