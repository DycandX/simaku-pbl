<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\CicilanController;
use App\Http\Controllers\GolonganUktController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\DaftarUlangController;
use App\Http\Controllers\Mahasiswa\LihatTagihanUktController;
use App\Http\Controllers\staff\staffBeasiswaController;
use App\Http\Controllers\staff\staffProfileController;
use App\Http\Controllers\staff\staffDataMahasiswaController;
use App\Http\Controllers\staffBeasiswaController as ControllersStaffBeasiswaController;
use App\Http\Controllers\staffDataMahasiswaController as ControllersStaffDataMahasiswaController;

// Import controller yang missing
use App\Http\Controllers\Staff\PengajuanCicilanStaffController;
use App\Http\Controllers\Staff\PembayaranUktStaffController;

// Login Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route - Dilindungi middleware
Route::middleware(['check.login'])->group(function () {
    Route::get('/lihat-tagihan-ukt', [LihatTagihanUktController::class, 'index'])->name('dashboard');
    Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/daftar-ulang', [DaftarUlangController::class, 'index'])->name('daftar-ulang');
    Route::get('/golongan-ukt', [GolonganUktController::class, 'index'])->name('golongan-ukt');

    // Pengajuan Cicilan
    Route::get('/pengajuan-cicilan', [CicilanController::class, 'create'])->name('pengajuan.cicilan');
    Route::post('/pengajuan-cicilan', [CicilanController::class, 'store'])->name('pengajuan.cicilan.store');

    //Admin
    Route::get('/admin/kelola-pengguna', [\App\Http\Controllers\Admin\KelolaPenggunaController::class, 'index'])->name('admin.kelola-pengguna');
    Route::get('/admin/kelola-pengguna/edit/{id}', [\App\Http\Controllers\Admin\KelolaPenggunaController::class, 'edit'])->name('admin.kelola-pengguna.edit');
    Route::put('/admin/kelola-pengguna/update/{id}', [\App\Http\Controllers\Admin\KelolaPenggunaController::class, 'update'])->name('admin.kelola-pengguna.update');
    Route::get('/admin/kelola-role', [\App\Http\Controllers\Admin\KelolaRoleController::class, 'index'])->name('admin.kelola-role');
    Route::get('/admin/kelola-role/create', [\App\Http\Controllers\Admin\KelolaRoleController::class, 'create'])->name('admin.kelola-role.create');
    Route::post('/admin/kelola-role/store', [\App\Http\Controllers\Admin\KelolaRoleController::class, 'store'])->name('admin.kelola-role.store');
    Route::get('/admin/kelola-role/edit/{id}', [\App\Http\Controllers\Admin\KelolaRoleController::class, 'edit'])->name('admin.kelola-role.edit');
    Route::put('/admin/kelola-role/update/{id}', [\App\Http\Controllers\Admin\KelolaRoleController::class, 'update'])->name('admin.kelola-role.update');
    Route::delete('/admin/kelola-role/delete/{id}', [\App\Http\Controllers\Admin\KelolaRoleController::class, 'destroy'])->name('admin.kelola-role.destroy');

    //staff
    Route::get('/staff-app', function () {
        return view('layouts.staff-app');
    })->name('staff-app');

    // FIX: Menambahkan nama controller yang lengkap
    Route::get('/staff/pengajuan-cicilan', [PengajuanCicilanStaffController::class, 'x'])->name('staff.pengajuan-cicilan');
    Route::get('/staff-beasiswa', [staffBeasiswaController::class, 'index'])->name('staff-beasiswa');
    Route::get('/staff-profile', [staffprofileController::class, 'index'])->name('staff-profile');
    Route::get('/staff-keuangan/data-mahasiswa', [staffDataMahasiswaController::class, 'showDataMahasiswa'])->name('staff.keuangan.data-mahasiswa');
    Route::get('/staff/pembayaran-ukt', [PembayaranUktStaffController::class, 'index'])->name('staff.pembayaran-ukt');
});

// Lihat Tagihan route
Route::get('/lihat-tagihan/{id}', [TagihanController::class, 'show'])->name('lihat-tagihan');