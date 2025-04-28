<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\CicilanController;
use App\Http\Controllers\GolonganUktController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\DaftarUlangController;

// Login Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route - Dilindungi middleware
Route::middleware(['check.login'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/daftar-ulang', [DaftarUlangController::class, 'index'])->name('daftar-ulang');
    // Tambahkan route untuk golongan UKT
    Route::get('/golongan-ukt', [GolonganUktController::class, 'index'])->name('golongan-ukt');
});

Route::middleware(['check.login'])->group(function () {
    Route::get('/pengajuan-cicilan', [CicilanController::class, 'create'])->name('pengajuan.cicilan');
    Route::post('/pengajuan-cicilan', [CicilanController::class, 'store'])->name('pengajuan.cicilan.store');
});

// Lihat Tagihan route
Route::get('/lihat-tagihan/{id}', [TagihanController::class, 'show'])->name('lihat-tagihan');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');