@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran - SIMAKU')

@section('header', 'Upload Bukti Pembayaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Upload Bukti Pembayaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('upload-bukti-pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group row mb-3">
                        <label for="id_pembayaran" class="col-sm-2 col-form-label">ID Pembayaran</label>
                        <div class="col-sm-10">
                            @php
                                $pembayaran = $pembayaranList[0]; // karena hanya 1
                            @endphp
                            <input type="text" class="form-control" value="ID: {{ $pembayaran['id'] }} | Jatuh Tempo: {{ \Carbon\Carbon::parse($pembayaran['tanggal_jatuh_tempo'])->format('d M Y') }} | Nominal: Rp{{ number_format($pembayaran['nominal_tagihan'], 0, ',', '.') }}" disabled>
                            <input type="hidden" name="id_pembayaran" value="{{ $pembayaran['id'] }}">
                        </div>
                    </div>

                    <!-- Tanggal Transfer -->
                    <div class="form-group row mb-3">
                        <label for="tanggal_transfer" class="col-sm-2 col-form-label">Tanggal Transfer</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tanggal_transfer" name="tanggal_transfer" value="">
                        </div>
                    </div>

                    <!-- Bank Pengirim -->
                    <div class="form-group row mb-3">
                        <label for="bank_pengirim" class="col-sm-2 col-form-label">Bank Pengirim</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="bank_pengirim" name="bank_pengirim" value="">
                        </div>
                    </div>

                    <!-- Jumlah Dibayar -->
                    <div class="form-group row mb-3">
                        <label for="jumlah_dibayar" class="col-sm-2 col-form-label">Jumlah Dibayar</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="jumlah_dibayar" name="jumlah_dibayar" value="">
                            <small class="form-text text-muted">Untuk Jumlah Dibayar, Ditulis Tanpa Titik</small>
                        </div>
                    </div>

                    <!-- Upload File -->
                    <div class="form-group row mb-4">
                        <label for="file" class="col-sm-2 col-form-label">Upload File</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="bukti_pembayaran_path" name="bukti_pembayaran_path" accept="image/png, image/jpeg">
                                        <label class="custom-file-label" for="bukti_pembayaran_path">Choose File</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="upload-btn">Upload</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Upload Bukti Pembayaran format JPG/PNG (Max: 1MB)</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin mengirim Bukti Pembayaran?')">
                                <i class="fas fa-paper-plane"></i> Kirim
                            </button>
                            <a href="{{ route('mahasiswa-dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-control:disabled, .form-control[readonly] {
        background-color: #f8f9fa;
        opacity: 1;
    }

    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .btn-primary:hover {
        background-color: #375dd1;
        border-color: #375dd1;
    }

    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
    }

    .card-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .custom-file-label::after {
        content: "Browse";
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Script untuk custom file input
        $('input[type="file"]').change(function(e) {
            var fileName = e.target.files[0].name;
            $('.custom-file-label').html(fileName);
        });

        // Browse button click handler
        $('#upload-btn').click(function() {
            $('#file').click();
        });
    });
</script>
@endsection