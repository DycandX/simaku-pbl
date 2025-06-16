<?php
use App\Http\Controllers\Staff\dataBandingUktController;
use App\Http\Controllers\Staff\detailPembayaranUktStaffController;
use App\Http\Controllers\Staff\staffBuatTagihanUktController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Mahasiswa\DaftarUlangController;
use App\Http\Controllers\Mahasiswa\LihatTagihanUktController;
use App\Http\Controllers\Mahasiswa\BeasiswaController;
use App\Http\Controllers\Mahasiswa\GolonganUktController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use App\Http\Controllers\staff\staffBeasiswaController;
use App\Http\Controllers\staff\staffProfileController;
use App\Http\Controllers\staff\staffDataMahasiswaController;
use App\Http\Controllers\Staff\CekTagihanUktController;
use App\Http\Controllers\Staff\staffDetailDataMahasiswaController;
use App\Http\Controllers\staffBeasiswaController as ControllersStaffBeasiswaController;
use App\Http\Controllers\staffDataMahasiswaController as ControllersStaffDataMahasiswaController;
use App\Http\Controllers\Staff\staffBuatTagihanUktKlikController;



// Import controller yang missing
use App\Http\Controllers\Staff\PengajuanCicilanStaffController;
use App\Http\Controllers\Staff\PembayaranUktStaffController;
use App\Models\GolonganUktSemester;

// Login Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route - Dilindungi middleware
Route::middleware(['check.login'])->group(function () {
    Route::get('/lihat-tagihan-ukt', [LihatTagihanUktController::class, 'index'])->name('mahasiswa-dashboard');
    Route::get('/lihat-tagihan-ukt/{id}', [LihatTagihanUktController::class, 'show'])->name('mahasiswa-dashboard.show');
    Route::get('/upload-bukti-pembayaran/{id}', [LihatTagihanUktController::class, 'upload_bukti_pembayaran'])->name('upload-bukti-pembayaran');
    Route::post('/upload-bukti-pembayaran', [LihatTagihanUktController::class, 'bukti_pembayaran_store'])->name('upload-bukti-pembayaran.store');
    Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    

    Route::get('/daftar-ulang', [DaftarUlangController::class, 'index'])->name('daftar-ulang');
    Route::get('/golongan-ukt', [GolonganUktController::class, 'index'])->name('golongan-ukt');

    // Pengajuan Cicilan
   // Route::get('/pengajuan-cicilan', [CicilanController::class, 'create'])->name('pengajuan.cicilan'); // pas di submit pake ini
    Route::get('/pengajuan-cicilan/{id}', [LihatTagihanUktController::class, 'pengajuan_cicilan'])->name('pengajuan.cicilan'); // pas di submit pake ini
    Route::post('/pengajuan-cicilan', [LihatTagihanUktController::class, 'pengajuan_cicilan_store'])->name('pengajuan.cicilan.store'); //ini pas di awal tampilan

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


    // ADDED: Route pengajuan cicilan staff dengan detail, approve, dan reject
    Route::get('/staff/pengajuan-cicilan', [PengajuanCicilanStaffController::class, 'x'])->name('staff.pengajuan-cicilan');
    Route::get('/staff/pengajuan-cicilan/{id}', [PengajuanCicilanStaffController::class, 'show'])->name('staff.pengajuan-cicilan.detail');
    Route::post('/staff/pengajuan-cicilan/{id}/approve', [PengajuanCicilanStaffController::class, 'approve'])->name('staff.pengajuan-cicilan.approve');
    Route::post('/staff/pengajuan-cicilan/{id}/reject', [PengajuanCicilanStaffController::class, 'reject'])->name('staff.pengajuan-cicilan.reject');

    Route::get('/staff-beasiswa', [staffBeasiswaController::class, 'index'])->name('staff-beasiswa');
    Route::get('/staff-profile', [staffprofileController::class, 'index'])->name('staff-profile');
    Route::get('/staff-keuangan/data-mahasiswa', [staffDataMahasiswaController::class, 'showDataMahasiswa'])->name('staff.keuangan.data-mahasiswa');
    Route::get('/staff-keuangan/detail-data-mahasiswa', [staffDetailDataMahasiswaController::class, 'detail'])->name('staff.keuangan.data-mahasiswa.detail');
    Route::get('/staff-keuangan/data-banding-ukt', [dataBandingUktController::class, 'index'])->name('staff-keuangan.data-mahasiswa.data-banding-ukt');
    
    Route::get('/staff/pembayaran-ukt', [PembayaranUktStaffController::class, 'index'])->name('staff.pembayaran-ukt');
    Route::get('/staff/buat-tagihan-ukt', [staffBuatTagihanUktController::class, 'index'])->name('staff.buat-tagihan-ukt');
    Route::get('/staff/buat-tagihan', [staffBuatTagihanUktController::class, 'create'])->name('staff.buat-tagihan');

    Route::get('/staff/detail-pembayaran-ukt', [detailPembayaranUktStaffController::class, 'detail'])->name('staff.pembayaran-ukt.detail');

    Route::get('/staff/cek-tagihan-ukt', [CekTagihanUktController::class, 'index'])->name('staff.cek-tagihan-ukt');
    Route::get('/staff/cek-tagihan-ukt/{noTagihan}', [CekTagihanUktController::class, 'detail'])->name('staff.cek-tagihan-ukt.detail');
});
