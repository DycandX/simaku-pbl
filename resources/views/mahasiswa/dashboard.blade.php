@extends('layouts.app')

@section('title', 'Dashboard - SIMAKU')

@section('header', 'Dashboard Tagihan UKT')

@section('header_button')
<a href="{{ route('golongan-ukt') }}" class="btn btn-primary">
    <i class="fas fa-eye"></i> Lihat Golongan UKT
</a>
@endsection

@section('content')
<div class="row">
    <!-- Semua Tagihan -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Semua Tagihan</h6>
                <h3 class="mb-0">Rp 7.800.000,00</h3>
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Belum Terbayar -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Belum Terbayar</h6>
                <h3 class="mb-0">Rp 2.600.000,00</h3>
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Sudah Terbayar -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Sudah Terbayar</h6>
                <h3 class="mb-0">Rp 5.200.000,00</h3>
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Semua Tagihan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>No Tagihan</th>
                                <th>Semester</th>
                                <th>Total Tagihan</th>
                                <th>Total Terbayar</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Metode Pembayaran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">01</td>
                                <td>INV000013408</td>
                                <td>2023/2024 - Gasal</td>
                                <td>Rp 2.600.000,00</td>
                                <td>Rp 2.600.000,00</td>
                                <td><span class="badge badge-light text-dark">Kontan</span></td>
                                <td><span class="badge badge-success">Sudah Lunas</span></td>
                                <td>BNI</td>
                                <td class="text-center">
                                    <a href="{{ route('lihat-tagihan', ['id' => 1]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat Tagihan
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">02</td>
                                <td>INV000014590</td>
                                <td>2023/2024 - Genap</td>
                                <td>Rp 2.600.000,00</td>
                                <td>Rp 2.600.000,00</td>
                                <td><span class="badge badge-light text-dark">Kontan</span></td>
                                <td><span class="badge badge-success">Sudah Lunas</span></td>
                                <td>BNI</td>
                                <td class="text-center">
                                    <a href="{{ route('lihat-tagihan', ['id' => 2]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat Tagihan
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">03</td>
                                <td>INV000016030</td>
                                <td>2024/2025 - Gasal</td>
                                <td>Rp 2.600.000,00</td>
                                <td>Rp 0,00</td>
                                <td></td>
                                <td><span class="badge badge-danger">Ditagihkan</span></td>
                                <td>BNI</td>
                                <td class="text-center">
                                    <a href="{{ route('lihat-tagihan', ['id' => 3]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat Tagihan
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-muted mb-0">Showing 1-3 of 3</p>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection