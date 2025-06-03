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
                        <div class="text-left mb-4">
                            <!-- Profile Image -->
                            <img src="{{ $mahasiswa['foto'] }}"
                                 alt="Profile Picture"
                                 class="img-fluid"
                                 {{-- style="max-width: 200px; border-radius: 10px;"> --}}
                                 style="width: 200px; height: 200px; object-fit: cover; border-radius: 10%;">
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Nama :</strong></div>
                            <div class="col-sm-8">{{ $mahasiswa['nama'] }}</div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>NIM :</strong></div>
                            <div class="col-sm-8">{{ $mahasiswa['nim'] }}</div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Alamat :</strong></div>
                            <div class="col-sm-8">{{ $mahasiswa['alamat'] }}</div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>No. Telepon :</strong></div>
                            <div class="col-sm-8">{{ $mahasiswa['telepon'] }}</div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Email :</strong></div>
                            <div class="col-sm-8">{{ $mahasiswa['email'] }}</div>
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