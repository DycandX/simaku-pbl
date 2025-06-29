{{-- File: resources/views/admin/dashboard/program-studi/form.blade.php --}}
@extends('layouts.admin-app')

@section('title', isset($programStudi) ? 'Edit Program Studi' : 'Tambah Program Studi')

@section('styles')
<style>
    /* Form Container */
    .form-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .form-header {
        background-color: #f8f9fc;
        padding: 20px;
        border-bottom: 1px solid #e3e6f0;
    }

    .form-title {
        margin: 0;
        color: #5a5c69;
        font-size: 18px;
        font-weight: 600;
    }

    .form-body {
        padding: 30px;
    }

    /* Form Group Styling */
    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .form-label.required::after {
        content: " *";
        color: #e74a3b;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        background-color: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .form-control.is-invalid {
        border-color: #e74a3b;
        box-shadow: 0 0 0 3px rgba(231, 74, 59, 0.1);
    }

    /* Select Styling */
    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px 12px;
        padding-right: 40px;
        appearance: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-select:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .form-select.is-invalid {
        border-color: #e74a3b;
        box-shadow: 0 0 0 3px rgba(231, 74, 59, 0.1);
    }

    /* Error Messages */
    .invalid-feedback {
        display: block;
        color: #e74a3b;
        font-size: 12px;
        margin-top: 5px;
    }

    /* Help Text */
    .form-help {
        font-size: 12px;
        color: #6b7280;
        margin-top: 5px;
    }

    /* Button Group */
    .button-group {
        display: flex;
        gap: 10px;
        padding-top: 20px;
        border-top: 1px solid #e3e6f0;
        margin-top: 30px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: #4e73df;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #545b62;
        color: white;
        text-decoration: none;
    }

    /* Info Box */
    .info-box {
        background-color: #e7f3ff;
        border: 1px solid #b3d9ff;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .info-box .info-title {
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .info-box .info-text {
        color: #1e40af;
        font-size: 13px;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-body {
            padding: 20px;
        }

        .button-group {
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }

    /* Loading State */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('header')
    {{ isset($programStudi) ? 'Edit Program Studi' : 'Tambah Program Studi' }}
@endsection

@section('header_button')
<a href="{{ route('admin.program-studi') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i>
    Kembali
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-container">
            <div class="form-header">
                <h3 class="form-title">
                    <i class="fas fa-graduation-cap"></i>
                    {{ isset($programStudi) ? 'Edit Data Program Studi' : 'Tambah Program Studi Baru' }}
                </h3>
            </div>

            <div class="form-body">
                @if(!empty($fakultasList))
                    <div class="info-box">
                        <div class="info-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi
                        </div>
                        <p class="info-text">
                            Pastikan untuk memilih fakultas yang sesuai dengan program studi yang akan ditambahkan.
                            Program studi harus berada di bawah fakultas yang tepat.
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ isset($programStudi) ? route('admin.program-studi.update', $programStudi['id']) : route('admin.program-studi.store') }}" id="prodiForm">
                    @csrf
                    @if(isset($programStudi))
                        @method('PUT')
                    @endif

                    <!-- Nama Program Studi -->
                    <div class="form-group">
                        <label for="nama_prodi" class="form-label required">Nama Program Studi</label>
                        <input
                            type="text"
                            class="form-control @error('nama_prodi') is-invalid @enderror"
                            id="nama_prodi"
                            name="nama_prodi"
                            value="{{ old('nama_prodi', isset($programStudi) ? $programStudi['nama_prodi'] : '') }}"
                            placeholder="Masukkan nama program studi"
                            required
                            autocomplete="off"
                        >
                        @error('nama_prodi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Contoh: Teknik Informatika, Sistem Informasi, Manajemen, Akuntansi
                        </div>
                    </div>

                    <!-- Fakultas -->
                    <div class="form-group">
                        <label for="id_fakultas" class="form-label required">Fakultas</label>
                        @if(!empty($fakultasList))
                            <select
                                class="form-select @error('id_fakultas') is-invalid @enderror"
                                id="id_fakultas"
                                name="id_fakultas"
                                required
                            >
                                <option value="">Pilih Fakultas</option>
                                @foreach($fakultasList as $fakultas)
                                    <option value="{{ $fakultas['id'] }}"
                                        {{ old('id_fakultas', isset($programStudi) ? $programStudi['id_fakultas'] : '') == $fakultas['id'] ? 'selected' : '' }}>
                                        {{ $fakultas['nama_fakultas'] }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Tidak ada data fakultas yang tersedia. Pastikan Anda sudah menambahkan fakultas terlebih dahulu.
                                <br>
                                <a href="{{ route('admin.fakultas.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Tambah Fakultas
                                </a>
                            </div>
                            <input type="hidden" name="id_fakultas" value="">
                        @endif

                        @error('id_fakultas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Pilih fakultas tempat program studi ini berada
                        </div>
                    </div>

                    <!-- Informasi Tambahan untuk Edit -->
                    @if(isset($programStudi))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Dibuat</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($programStudi['created_at'])->format('d/m/Y H:i') }}"
                                        readonly
                                        style="background-color: #f8f9fa;"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Terakhir Diubah</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($programStudi['updated_at'])->format('d/m/Y H:i') }}"
                                        readonly
                                        style="background-color: #f8f9fa;"
                                    >
                                </div>
                            </div>
                        </div>

                        @if(isset($programStudi['nama_fakultas']))
                            <div class="form-group">
                                <label class="form-label">Fakultas Saat Ini</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $programStudi['nama_fakultas'] }}"
                                    readonly
                                    style="background-color: #f8f9fa;"
                                >
                                <div class="form-help">
                                    Ini adalah fakultas yang saat ini terkait dengan program studi ini
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Button Group -->
                    <div class="button-group">
                        @if(!empty($fakultasList))
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i>
                                {{ isset($programStudi) ? 'Update Program Studi' : 'Simpan Program Studi' }}
                            </button>
                        @endif
                        <a href="{{ route('admin.program-studi') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#prodiForm').on('submit', function(e) {
        var namaProdi = $('#nama_prodi').val().trim();
        var idFakultas = $('#id_fakultas').val();
        var isValid = true;

        // Reset previous error states
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Validate nama prodi
        if (!namaProdi) {
            $('#nama_prodi').addClass('is-invalid');
            $('#nama_prodi').after('<div class="invalid-feedback">Nama program studi harus diisi.</div>');
            isValid = false;
        }

        // Validate fakultas selection
        if (!idFakultas) {
            $('#id_fakultas').addClass('is-invalid');
            $('#id_fakultas').after('<div class="invalid-feedback">Fakultas harus dipilih.</div>');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            // Focus on first invalid field
            $('.is-invalid').first().focus();
            return false;
        }

        // Show loading state
        var submitBtn = $('#submitBtn');
        var originalText = submitBtn.html();

        submitBtn.prop('disabled', true);
        submitBtn.html('<div class="spinner"></div> Menyimpan...');

        // Restore button state after 5 seconds (fallback)
        setTimeout(function() {
            submitBtn.prop('disabled', false);
            submitBtn.html(originalText);
        }, 5000);
    });

    // Remove error state when user interacts with fields
    $('#nama_prodi, #id_fakultas').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto capitalize first letter of each word for nama prodi
    $('#nama_prodi').on('input', function() {
        var value = $(this).val();
        var capitalizedValue = value.replace(/\b\w/g, function(match) {
            return match.toUpperCase();
        });

        if (value !== capitalizedValue) {
            var cursorPosition = this.selectionStart;
            $(this).val(capitalizedValue);
            this.setSelectionRange(cursorPosition, cursorPosition);
        }
    });

    // Focus on nama_prodi field when page loads
    $('#nama_prodi').focus();

    // Handle Enter key submission
    $('#nama_prodi').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#id_fakultas').focus();
        }
    });

    $('#id_fakultas').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            if ($('#submitBtn').length) {
                $('#submitBtn').click();
            }
        }
    });

    // Highlight fakultas change
    $('#id_fakultas').on('change', function() {
        var selectedText = $(this).find('option:selected').text();
        if (selectedText !== 'Pilih Fakultas') {
            console.log('Selected fakultas:', selectedText);
        }
    });
});

// Show confirmation dialog when leaving page with unsaved changes
$(window).on('beforeunload', function(e) {
    var originalNamaProdi = '{{ old('nama_prodi', isset($programStudi) ? $programStudi['nama_prodi'] : '') }}';
    var originalIdFakultas = '{{ old('id_fakultas', isset($programStudi) ? $programStudi['id_fakultas'] : '') }}';

    var currentNamaProdi = $('#nama_prodi').val();
    var currentIdFakultas = $('#id_fakultas').val();

    if (originalNamaProdi !== currentNamaProdi || originalIdFakultas !== currentIdFakultas) {
        return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
    }
});

// Remove beforeunload when form is submitted
$('#prodiForm').on('submit', function() {
    $(window).off('beforeunload');
});
</script>
@endsection