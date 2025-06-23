@extends('layouts.app')

@section('title', 'Pengajuan Cicilan UKT - SIMAKU')

@section('header', 'Pengajuan Cicilan UKT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Upload Pengajuan Cicilan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pengajuan.cicilan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Hidden input for tagihan_id jika ada -->
                    @if(isset($tagihan))
                    <input type="hidden" name="tagihan_id" value="{{ $tagihan['id'] }}">
                    @endif

                    <!-- Nama -->
                    <div class="form-group row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ Auth::user()->name ?? 'ZIRLDA SYAFIRA' }}" readonly>
                        </div>
                    </div>

                    <!-- NIM -->
                    <div class="form-group row mb-3">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="{{ Auth::user()->nim ?? '4.33.23.4.22' }}" readonly>
                        </div>
                    </div>

                    <!-- Jurusan -->
                    <div class="form-group row mb-3">
                        <label for="jurusan" class="col-sm-2 col-form-label">Jurusan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="jurusan" name="jurusan" value="ADMINISTRASI BISNIS" readonly>
                        </div>
                    </div>

                    <!-- Prodi -->
                    <div class="form-group row mb-3">
                        <label for="prodi" class="col-sm-2 col-form-label">Prodi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="prodi" name="prodi" value="D4 - MANAJEMEN BISNIS INTERNASIONAL" readonly>
                        </div>
                    </div>

                    <!-- Tagihan -->
                    <div class="form-group row mb-3">
                        <label for="tagihan" class="col-sm-2 col-form-label">Tagihan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="tagihan" name="tagihan"
                                value="{{ $tagihan['nama_tagihan'] ?? 'TAGIHAN UKT 2023 - Genap' }}"
                                readonly>
                        </div>
                    </div>

                    <!-- Angsuran -->
                    <div class="form-group row mb-3">
                        <label for="angsuran" class="col-sm-2 col-form-label">Angsuran</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="angsuran" name="angsuran">
                                <option value="" disabled>-- Pilih Jumlah Angsuran --</option>
                                <option value="2x" selected>2x</option>
                                <option value="3x">3x</option>
                                <option value="4x">4x</option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload File -->
                    <div class="form-group row mb-4">
                        <label for="file" class="col-sm-2 col-form-label">Upload File</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file">
                                    <label class="custom-file-label" for="file">Choose File</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="upload-btn">Browse</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Upload surat permohonan cicilan dalam format PDF (Max: 2MB)</small>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
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