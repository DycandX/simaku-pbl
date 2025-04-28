@extends('layouts.app')

@section('title', 'Profile Mahasiswa - SIMAKU')

@section('header', 'Profile Mahasiswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="text-center mb-4">
                            <!-- Profile Image -->
                            <img src="{{ asset('assets/Profile.jpeg') }}"
                                 alt="Profile Picture"
                                 class="img-fluid rounded"
                                 style="max-width: 300px; border-radius: 10px;">
                        </div>

                        <!-- Profile Info -->
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nama :</strong>
                            </div>
                            <div class="col-sm-8">
                                Zirlda Syafira
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>NIM :</strong>
                            </div>
                            <div class="col-sm-8">
                                4.33.23.4.22
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Fakultas :</strong>
                            </div>
                            <div class="col-sm-8">
                                Administrasi Bisnis
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Program Studi :</strong>
                            </div>
                            <div class="col-sm-8">
                                D4 - Manajemen Bisnis Internasional
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Kelas :</strong>
                            </div>
                            <div class="col-sm-8">
                                MB-2D
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Golongan UKT :</strong>
                            </div>
                            <div class="col-sm-8">
                                IV
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status Mahasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                Aktif
                            </div>
                        </div>
                    </div>
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
        min-height: 600px;
    }

    .card-body {
        padding: 2rem;
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

    .img-fluid {
        border: 1px solid #ddd;
        padding: 5px;
        background: white;
    }

    @media (max-width: 768px) {
        .row.mb-3 .col-sm-4,
        .row.mb-3 .col-sm-8 {
            padding: 0.5rem 0;
        }
    }
</style>
@endsection