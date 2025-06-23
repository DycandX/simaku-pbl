@extends('layouts.staff-app')

@section('title', 'Buat Tagihan UKT')

@section('header', 'Form Data Tagihan UKT')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Form Header -->
                    <h5 class="mb-4">Form Data Tagihan UKT</h5>

                    <form action="{{ route('staff.detail-buatTagihanUkt.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tagihan" class="form-label">Tagihan</label>
                            <select class="form-select" id="tagihan" name="tagihan">
                                @foreach ($periodePembayaran as $periode)
                                    <option value="{{ $periode['nama_periode'] }}">{{ $periode['nama_periode'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="mahasiswa" class="form-label">Pilih Mahasiswa</label>
                            <select class="form-select" id="mahasiswa" name="mahasiswa">
                                @foreach ($mahasiswaData as $mahasiswa)
                                    <option value="{{ $mahasiswa['id'] }}">{{ $mahasiswa['mahasiswa']['nama_lengkap'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_tagihan" class="form-label">Tanggal Tagihan</label>
                            <input type="date" class="form-control" id="tanggal_tagihan" name="tanggal_tagihan" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                            <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" required>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>Create TAGIHAN UKT 2023 - Genap</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Buat Tagihan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
