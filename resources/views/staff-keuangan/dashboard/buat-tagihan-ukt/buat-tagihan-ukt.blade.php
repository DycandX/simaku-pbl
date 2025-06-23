@extends('layouts.staff-app')

@section('title', 'Dashboard - SIMAKU')

@section('header', 'Dashboard Buat Tagihan UKT')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Dashboard Header -->
                    <h5 class="mb-4">Dashboard Buat Tagihan UKT</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="{{ route('staff.buat-tagihan') }}" class="btn btn-primary">Buat Tagihan</a>
                        </div>
                    </div>

                    <!-- Table for Jobs -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Job</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Waktu/ Tanggal Dibuat</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>TAGIHAN UKT 2023 - Gasal</td>
                                <td>1/1</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>08:00 29-Juli-2023</td>
                                <td>Buat TAGIHAN UKT 2023 - Gasal</td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>TAGIHAN UKT 2023 - Genap</td>
                                <td>1/1</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>08:00 29-Jan-2024</td>
                                <td>Buat TAGIHAN UKT 2023 - Genap</td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>TAGIHAN UKT 2024 - Gasal</td>
                                <td>1/1</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>08:00 29-Juli-2024</td>
                                <td>Buat TAGIHAN UKT 2024 - Gasal</td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td>TAGIHAN UKT 2024 - Genap</td>
                                <td>1/1</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>08:00 29-Jan-2025</td>
                                <td>Buat TAGIHAN UKT 2024 - Genap</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between">
                        <p>Menampilkan 1-4 dari 8</p>
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="#">‹</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">›</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection