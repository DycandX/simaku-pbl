<?php

use App\Http\Controllers\Api\GolonganUktController as ApiGolonganUktController;
use App\Http\Controllers\Staff\dataBandingUktController;
use App\Http\Controllers\Staff\detailPembayaranUktStaffController;
use App\Http\Controllers\Staff\staffBuatTagihanUktController;
use App\Models\GolonganUkt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\CicilanController;
use App\Http\Controllers\Mahasiswa\GolonganUktController;
use App\Http\Controllers\Mahasiswa\DaftarUlangController;
use App\Http\Controllers\Mahasiswa\LihatTagihanUktController;
use App\Http\Controllers\Mahasiswa\BeasiswaController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use App\Http\Controllers\staff\staffBeasiswaController;
use App\Http\Controllers\staff\staffProfileController;
use App\Http\Controllers\staff\staffDataMahasiswaController;
use App\Http\Controllers\Staff\CekTagihanUktController;
use App\Http\Controllers\Staff\staffDetailDataMahasiswaController;
use App\Http\Controllers\staffBeasiswaController as ControllersStaffBeasiswaController;
use App\Http\Controllers\staffDataMahasiswaController as ControllersStaffDataMahasiswaController;
use App\Http\Controllers\Staff\StaffDetailBeasiswaController;
use App\Http\Controllers\Staff\StaffDetailBuatTagihanUktController;
use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\TingkatController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TahunAkademikController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\EnrollmentMahasiswaController;


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
    Route::get('/lihat-tagihan-ukt', [LihatTagihanUktController::class, 'index'])->name('mahasiswa-dashboard');
    Route::get('/lihat-tagihan-ukt/show', [LihatTagihanUktController::class, 'show'])->name('mahasiswa-dashboard.show');
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
    Route::get('/admin/kelola-pengguna/edit/{id}', [\App\Http\Controllers\Admin\KelolaPenggunaController::class, 'edit'])->name('admin.kelola-pengguna.edit');
    Route::put('/admin/kelola-pengguna/update/{id}', [\App\Http\Controllers\Admin\KelolaPenggunaController::class, 'update'])->name('admin.kelola-pengguna.update');
    Route::get('/admin/kelola-pengguna/create', [\App\Http\Controllers\Admin\KelolaPenggunaController::class, 'create'])->name('admin.kelola-pengguna.create');
    Route::post('/admin/kelola-pengguna/store', [\App\Http\Controllers\Admin\KelolaPenggunaController::class, 'store'])->name('admin.kelola-pengguna.store');
    // Ubah route menjadi:
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::get('/fakultas', [FakultasController::class, 'index'])->name('fakultas');
        Route::get('/fakultas/create', [FakultasController::class, 'create'])->name('fakultas.create');
        Route::post('/fakultas', [FakultasController::class, 'store'])->name('fakultas.store');
        Route::get('/fakultas/{id}', [FakultasController::class, 'show'])->name('fakultas.show');
        Route::get('/fakultas/{id}/edit', [FakultasController::class, 'edit'])->name('fakultas.edit');
        Route::put('/fakultas/{id}', [FakultasController::class, 'update'])->name('fakultas.update');
        Route::delete('/fakultas/{id}', [FakultasController::class, 'destroy'])->name('fakultas.destroy');
          // Program Studi Routes
        Route::get('/program-studi', [ProgramStudiController::class, 'index'])->name('program-studi');
        Route::get('/program-studi/create', [ProgramStudiController::class, 'create'])->name('program-studi.create');
        Route::post('/program-studi', [ProgramStudiController::class, 'store'])->name('program-studi.store');
        Route::get('/program-studi/{id}', [ProgramStudiController::class, 'show'])->name('program-studi.show');
        Route::get('/program-studi/{id}/edit', [ProgramStudiController::class, 'edit'])->name('program-studi.edit');
        Route::put('/program-studi/{id}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
        Route::delete('/program-studi/{id}', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');
        // Kelas Routes
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
        Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
        //Tingkat
        Route::get('/tingkat', [TingkatController::class, 'index'])->name('tingkat');
        Route::get('/tingkat/create', [TingkatController::class, 'create'])->name('tingkat.create');
        Route::post('/tingkat', [TingkatController::class, 'store'])->name('tingkat.store');
        Route::get('/tingkat/{id}', [TingkatController::class, 'show'])->name('tingkat.show');
        Route::get('/tingkat/{id}/edit', [TingkatController::class, 'edit'])->name('tingkat.edit');
        Route::put('/tingkat/{id}', [TingkatController::class, 'update'])->name('tingkat.update');
        Route::delete('/tingkat/{id}', [TingkatController::class, 'destroy'])->name('tingkat.destroy');
        //staff
        Route::get('/staff', [StaffController::class, 'index'])->name('staff');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
        Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
        //Tahun Akademik
        Route::get('/tahun-akademik', [TahunAkademikController::class, 'index'])->name('tahun-akademik');
        Route::get('/tahun-akademik/create', [TahunAkademikController::class, 'create'])->name('tahun-akademik.create');
        Route::post('/tahun-akademik', [TahunAkademikController::class, 'store'])->name('tahun-akademik.store');
        Route::get('/tahun-akademik/{id}', [TahunAkademikController::class, 'show'])->name('tahun-akademik.show');
        Route::get('/tahun-akademik/{id}/edit', [TahunAkademikController::class, 'edit'])->name('tahun-akademik.edit');
        Route::put('/tahun-akademik/{id}', [TahunAkademikController::class, 'update'])->name('tahun-akademik.update');
        Route::delete('/tahun-akademik/{id}', [TahunAkademikController::class, 'destroy'])->name('tahun-akademik.destroy');
        //Mahasiswa
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa');
        Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
        Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::get('/mahasiswa/{nim}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
        Route::put('/mahasiswa/{nim}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
        Route::delete('/mahasiswa/{nim}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    });

    Route::get('/admin/enrollment-mahasiswa', [EnrollmentMahasiswaController::class, 'index'])->name('admin.enrollment-mahasiswa');
    Route::get('/admin/enrollment-mahasiswa/create', [EnrollmentMahasiswaController::class, 'create'])->name('admin.enrollment-mahasiswa.create');
    Route::post('/admin/enrollment-mahasiswa', [EnrollmentMahasiswaController::class, 'store'])->name('admin.enrollment-mahasiswa.store');
    Route::get('/admin/enrollment-mahasiswa/{id}/edit', [EnrollmentMahasiswaController::class, 'edit'])->name('admin.enrollment-mahasiswa.edit');
    Route::put('/admin/enrollment-mahasiswa/{id}', [EnrollmentMahasiswaController::class, 'update'])->name('admin.enrollment-mahasiswa.update');
    Route::delete('/admin/enrollment-mahasiswa/{id}', [EnrollmentMahasiswaController::class, 'destroy'])->name('admin.enrollment-mahasiswa.destroy');

    //staff
    Route::get('/staff-app', function () {
        return view('layouts.staff-app');
    })->name('staff-app');


    // ADDED: Route pengajuan cicilan staff dengan detail, approve, dan reject
    Route::get('/staff/pengajuan-cicilan', [PengajuanCicilanStaffController::class, 'index'])->name('staff.pengajuan-cicilan');
    Route::get('/staff/pengajuan-cicilan/{id}', [PengajuanCicilanStaffController::class, 'show'])->name('staff.pengajuan-cicilan.detail');
    Route::get('/staff/pengajuan-cicilan/{id}/form-cicilan', [PengajuanCicilanStaffController::class, 'formUpdateCicilan'])->name('pengajuan-cicilan.form-update-cicilan');
    Route::put('/pengajuan-cicilan/{id}/update-status', [PengajuanCicilanStaffController::class, 'updateStatus'])->name('pengajuan-cicilan.update-status');
    Route::put('/staff/pengajuan-cicilan/{id}/hasil-cicilan', [PengajuanCicilanStaffController::class, 'updateHasilCicilan'])->name('staff.pengajuan-cicilan.update-hasil-cicilan');
    // Tambahkan route ini ke dalam grup route yang sesuai
    Route::post('/pengajuan-cicilan/{id}/buat-tagihan-baru', [PengajuanCicilanStaffController::class, 'buatTagihanBaru'])->name('pengajuan-cicilan.buat-tagihan-baru');

    Route::post('/staff/pengajuan-cicilan/{id}/approve', [PengajuanCicilanStaffController::class, 'approve'])->name('staff.pengajuan-cicilan.approve');
    Route::post('/staff/pengajuan-cicilan/{id}/reject', [PengajuanCicilanStaffController::class, 'reject'])->name('staff.pengajuan-cicilan.reject');

    Route::get('staff-keuangan/beasiswa/staff-beasiswa', [StaffBeasiswaController::class, 'index'])->name('staff-keuangan.beasiswa.staff-beasiswa');
    // Route for viewing beasiswa details
    Route::get('staff-keuangan/beasiswa/staff-detail-beasiswa/{nim}', [StaffDetailBeasiswaController::class, 'index'])->name('staff-keuangan.beasiswa.staff-detail-beasiswa');
    Route::get('/staff-profile', [staffprofileController::class, 'index'])->name('staff-profile');
    //Route::get('/staff-keuangan/data-mahasiswa', [staffDataMahasiswaController::class, 'showDataMahasiswa'])->name('staff.keuangan.data-mahasiswa');
    Route::get('/staff-keuangan/data-mahasiswa', [staffDataMahasiswaController::class, 'index'])->name('staff-keuangan.data-mahasiswa');

    Route::get('/staff-keuangan/data-mahasiswa/detail-data-mahasiswa/{nim}', [StaffDetailDataMahasiswaController::class, 'index'])->name('staff-keuangan.data-mahasiswa.detail-data-mahasiswa');


    Route::get('/staff-keuangan/data-banding-ukt', [dataBandingUktController::class, 'index'])->name('staff-keuangan.data-mahasiswa.data-banding-ukt');

    Route::get('/staff/pembayaran-ukt', [PembayaranUktStaffController::class, 'index'])->name('staff.pembayaran-ukt');

    Route::get('/pembayaran-ukt/{id}', [PembayaranUktStaffController::class, 'show'])->name('staff.pembayaran-ukt.show');

    Route::put('/staff/pembayaran-ukt/{id}/update-status', [PembayaranUktStaffController::class, 'updateStatus'])->name('staff.pembayaran-ukt.update-status');

    // buat tagihan (view list data log)
    Route::get('/staff/buat-tagihan-ukt', [staffBuatTagihanUktController::class, 'index'])->name('staff.buat-tagihan-ukt');
    Route::get('/staff/buat-tagihan', [staffBuatTagihanUktController::class, 'create'])->name('staff.buat-tagihan-ukt.create');
    Route::post('/staff/detail-buat-tagihan-ukt/preview', [staffDetailBuatTagihanUktController::class, 'preview'])->name('staff.detail-buatTagihanUkt.preview');

    // detail buat tagihan (generate)
    Route::get('/staff/tagihan-ukt', [StaffDetailBuatTagihanUktController::class, 'index'])->name('staff.detail-buatTagihanUkt.index');
    Route::post('/staff/tagihan-ukt/create', [StaffDetailBuatTagihanUktController::class, 'create'])->name('staff.detail-buatTagihanUkt.create');




    Route::get('/staff/cek-tagihan-ukt', [CekTagihanUktController::class, 'index'])->name('staff.cek-tagihan-ukt');
    Route::get('/staff/cek-tagihan-ukt/{noTagihan}', [CekTagihanUktController::class, 'detail'])->name('staff.cek-tagihan-ukt.detail');
});

// Lihat Tagihan route
Route::get('/lihat-tagihan/{id}', [TagihanController::class, 'show'])->name('lihat-tagihan');