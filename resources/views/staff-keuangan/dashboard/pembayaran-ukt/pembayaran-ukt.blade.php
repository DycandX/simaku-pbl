@extends('layouts.staff-app')

@section('title', 'Pembayaran UKT')

@section('header', 'STAFF - PEMBAYARAN UKT')

@section('content')
<div class="container mt-5">
    <!-- Filter Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="filter-container">
            <label for="semester">Semester</label>
            <select id="semester" class="form-select">
                <option value="2023/2024 - Genap">2023/2024 - Genap</option>
                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="filter-container">
            <label for="status">Status</label>
            <select id="status" class="form-select">
                <option value="All Status">All Status</option>
                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="filter-container">
            <button class="btn btn-primary">Filter</button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-3">
            <div class="summary-card">
                <p>Total Pembayaran</p>
                <h4>Rp 700.000.000,00</h4>
            </div>
        </div>
        <div class="col-3">
            <div class="summary-card">
                <p>Divertifikasi</p>
                <h4>1000</h4>
            </div>
        </div>
        <div class="col-3">
            <div class="summary-card">
                <p>Belum Diverifikasi</p>
                <h4>200</h4>
            </div>
        </div>
        <div class="col-3">
            <div class="summary-card">
                <p>Ditolak</p>
                <h4>2</h4>
            </div>
        </div>
    </div>

    <!-- Payment Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Tagihan</th>
                    <th>Mahasiswa</th>
                    <th>Semester</th>
                    <th>Tanggal</th>
                    <th>Jumlah Dibayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>INV000013408</td>
                    <td>Zirilda Syafira</td>
                    <td>2023/2024 - Genap</td>
                    <td>29-Jan-2024</td>
                    <td>Rp 2,600,000.00</td>
                    <td class="text-success">Diverifikasi</td>
                    {{-- <td><button class="btn btn-info">Lihat Pembayaran</button></td> --}}
                    <td>
                        <a href="{{ route('staff.pembayaran-ukt.detail') }}" class="btn btn-view">
                            <i class="fas fa-eye"></i> Lihat Pembayaran
                        </a>
                    </td>
                    
                </tr>
                <tr>
                    <td>02</td>
                    <td>INV001234501</td>
                    <td>Fadhil Ramadhan</td>
                    <td>2023/2024 - Genap</td>
                    <td>29-Jan-2024</td>
                    <td>Rp 3,000,000.00</td>
                    <td class="text-success">Diverifikasi</td>
                    <td><button class="btn btn-info">Lihat Pembayaran</button></td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>INV001234502</td>
                    <td>Aisyah Hanifah</td>
                    <td>2023/2024 - Genap</td>
                    <td>29-Jan-2024</td>
                    <td>Rp 2,850,000.00</td>
                    <td class="text-danger">Ditolak</td>
                    <td><button class="btn btn-info">Lihat Pembayaran</button></td>
                </tr>
                <tr>
                    <td>04</td>
                    <td>INV001234503</td>
                    <td>Yudha Prasetyo</td>
                    <td>2023/2024 - Genap</td>
                    <td>29-Jan-2024</td>
                    <td>Rp 2,700,000.00</td>
                    <td class="text-warning">Belum Diverifikasi</td>
                    <td><button class="btn btn-info">Lihat Pembayaran</button></td>
                </tr>
                <tr>
                    <td>05</td>
                    <td>INV001234506</td>
                    <td>Siti Nurhaliza</td>
                    <td>2023/2024 - Genap</td>
                    <td>29-Jan-2024</td>
                    <td>Rp 2,950,000.00</td>
                    <td class="text-success">Diverifikasi</td>
                    <td><button class="btn btn-info">Lihat Pembayaran</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('styles')
<style>
    .filter-container {
        display: inline-block;
        margin-right: 10px;
    }
    .summary-card {
        background: #fff;
        padding: 15px;
        text-align: center;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .summary-card p {
        font-size: 14px;
        color: #6c757d;
    }
    .summary-card h4 {
        margin-top: 5px;
        font-weight: bold;
    }
    .table th, .table td {
        text-align: center;
    }
    .table .btn-info {
        color: white;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
</style>
@endsection
