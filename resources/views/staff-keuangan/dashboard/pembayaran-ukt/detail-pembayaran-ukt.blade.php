@extends('layouts.staff-app')

@section('title', 'Pembayaran UKT - SIMAKU')

@section('header', 'Pembayaran UKT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card p-4">
            <div class="card-title mb-4">
                <h5>Detail Pembayaran</h5>
            </div>

            <!-- Data Pembayaran Mahasiswa -->
            <div class="form-group mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" value="Zirlda Syafira" disabled>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">NIM</label>
                <input type="text" class="form-control" value="4.33.23.4.22" disabled>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Jurusan</label>
                <input type="text" class="form-control" value="Administrasi Bisnis" disabled>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Program Studi</label>
                <input type="text" class="form-control" value="D4 - Manajemen Bisnis Internasional" disabled>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Status</label>
                <input type="text" class="form-control" value="Aktif" disabled>
            </div>

            <!-- Data Pembayaran UKT -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6>Detail Pembayaran</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No Tagihan</th>
                                    <th>Semester</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Dibayar</th>
                                    <th>Bank Pengirim</th>
                                    <th>Status</th>
                                    <th>File</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>INV000013408</td>
                                    <td>2023/2024 - Genap</td>
                                    <td>29-Jan-2024</td>
                                    <td>Rp 2,600,000.00</td>
                                    <td>BNI</td>
                                    <td><span class="badge badge-success">Diversifikasi</span></td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Verifikasi
                                        </button>
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Batalkan
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button Kembali -->
        <a href="#" class="btn btn-outline-primary float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>
@endsection
