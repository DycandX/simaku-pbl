@extends('layouts.staff-app')

@section('title', 'Detail Mahasiswa - SIMAKU')

@section('header', 'Detail Mahasiswa')

@section('content')
<div class="row">
    <!-- Detail Data Mahasiswa -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Data Mahasiswa</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> Zirlda Syafira</p>
                        <p><strong>NIM:</strong> 4.33.23.4.22</p>
                        <p><strong>Jurusan:</strong> Administrasi Bisnis</p>
                        <p><strong>Program Studi:</strong> D4 - Manajemen Bisnis Internasional</p>
                        <p><strong>Kelas:</strong> MB-2D</p>
                        <p><strong>Golongan UKT:</strong> IV</p>
                        <p><strong>Status Mahasiswa:</strong> 
                            <span class="badge badge-pill badge-success">Aktif</span>
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        {{-- <a href="#" class="btn btn-primary">Banding UKT</a> --}}
                        <a href="{{ route('staff-keuangan.data-mahasiswa.data-banding-ukt') }}" class="btn btn-view">
                                        <i class="fas fa-eye"></i> Banding UKT
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Daftar Ulang Mahasiswa -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Daftar Ulang Mahasiswa</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Tagihan</th>
                                <th>Mahasiswa</th>
                                <th>Tanggal Terbit</th>
                                <th>Jatuh Tempo</th>
                                <th>Semester</th>
                                <th>Total</th>
                                <th>Bank Tujuan</th>
                                <th>Status</th>
                                <th>Status Payment</th>
                                <th>Keterangan Tagihan</th>
                                <th>Bukti Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>INV000013408</td>
                                <td>Zirlda Syafira</td>
                                <td>29 Jan 2024</td>
                                <td>2 Feb 2024</td>
                                <td>2023/2024 - Genap</td>
                                <td>Rp 3,600,000.00</td>
                                <td>BNI</td>
                                <td><span class="badge badge-success">Publish</span></td>
                                <td><span class="badge badge-info">Sudah Dibayar</span></td>
                                <td>-</td>
                                <td><a href="#" class="btn btn-view"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>INV000014590</td>
                                <td>Zirlda Syafira</td>
                                <td>7 Jul 2024</td>
                                <td>9 Jul 2024</td>
                                <td>2024/2025 - Gasal</td>
                                <td>Rp 2,600,000.00</td>
                                <td>BNI</td>
                                <td><span class="badge badge-success">Publish</span></td>
                                <td><span class="badge badge-info">Sudah Dibayar</span></td>
                                <td>Banding UKT Berhasil Dilakukan</td>
                                <td><a href="#" class="btn btn-view"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>INV000016030</td>
                                <td>Zirlda Syafira</td>
                                <td>20 Jan 2025</td>
                                <td>23 Jan 2025</td>
                                <td>2024/2025 - Genap</td>
                                <td>-</td>
                                <td>-</td>
                                <td><span class="badge badge-danger">Belum Dibayar</span></td>
                                <td><span class="badge badge-warning">Belum Dibayar</span></td>
                                <td>-</td>
                                <td><a href="#" class="btn btn-view"><i class="fas fa-eye"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="pagination-info">
                            Menampilkan 1-5 dari 50 data
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <ul class="pagination justify-content-end">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Sebelumnya</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Selanjutnya</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle filter, pagination and other logic if necessary
    });
</script>
@endsection
