@extends('layouts.staff-app')

@section('title', 'Beasiswa - SIMAKU')

@section('header', 'Sfaff - Beasiswa Pendidikan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Detail Beasiswa Mahasiswa -->
                <h5 class="mb-4">STAFF - BEASISWA</h5>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nama Beasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                KIP-Kuliah
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status Beasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                Aktif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Tipe Beasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                Pembebasan UKT + Uang Saku
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Periode :</strong>
                            </div>
                            <div class="col-sm-8">
                                2023/2024
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Semester Berlaku :</strong>
                            </div>
                            <div class="col-sm-8">
                                Semester 1 s.d. 8
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Skema Pembayaran:</strong>
                            </div>
                            <div class="col-sm-8">
                                Langsung ke rekening mahasiswa
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Penerimaan Dana -->
                <h5 class="mb-4">Detail Penerimaan Dana</h5>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Periode</th>
                                <th>Jumlah</th>
                                <th>Tanggal Cair</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Semester 1</td>
                                <td>Rp 2.400.000</td>
                                <td>20 Februari 2023</td>
                                <td><span class="badge badge-success">Sudah Cair</span></td>
                            </tr>
                            <tr>
                                <td>Semester 2</td>
                                <td>Rp 2.400.000</td>
                                <td>5 September 2023</td>
                                <td><span class="badge badge-success">Sudah Cair</span></td>
                            </tr>
                            <tr>
                                <td>Semester 3</td>
                                <td>Rp 2.400.000</td>
                                <td>10 Februari 2024</td>
                                <td><span class="badge badge-success">Sudah Cair</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        background-color: #fff;
    }

    .card-body {
        padding: 2rem;
    }

    h5 {
        color: #333;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .row.mb-3 {
        margin-bottom: 1rem;
    }

    .row.mb-3 .col-sm-4 {
        color: #6c757d;
    }

    .row.mb-3 .col-sm-8 {
        color: #333;
        font-weight: 500;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .badge-success {
        background-color: #4fd1c5;
        color: #fff;
        padding: 5px 10px;
        border-radius: 15px;
        font-weight: 500;
    }

    .table td {
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .row.mb-3 .col-sm-4,
        .row.mb-3 .col-sm-8 {
            padding: 0.5rem 0;
        }
    }
</style>
@endsection