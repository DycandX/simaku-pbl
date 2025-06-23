{{-- File: resources/views/admin/dashboard/fakultas/form.blade.php --}}
@extends('layouts.admin-app')

@section('title', isset($fakultas) ? 'Edit Fakultas' : 'Tambah Fakultas')

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
    {{ isset($fakultas) ? 'Edit Fakultas' : 'Tambah Fakultas' }}
@endsection

@section('header_button')
<a href="{{ route('admin.fakultas') }}" class="btn-back">
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
                    <i class="fas fa-university"></i>
                    {{ isset($fakultas) ? 'Edit Data Fakultas' : 'Tambah Fakultas Baru' }}
                </h3>
            </div>

            <div class="form-body">
                <form method="POST" action="{{ isset($fakultas) ? route('admin.fakultas.update', $fakultas['id']) : route('admin.fakultas.store') }}" id="fakultasForm">
                    @csrf
                    @if(isset($fakultas))
                        @method('PUT')
                    @endif

                    <!-- Nama Fakultas -->
                    <div class="form-group">
                        <label for="nama_fakultas" class="form-label required">Nama Fakultas</label>
                        <input
                            type="text"
                            class="form-control @error('nama_fakultas') is-invalid @enderror"
                            id="nama_fakultas"
                            name="nama_fakultas"
                            value="{{ old('nama_fakultas', isset($fakultas) ? $fakultas['nama_fakultas'] : '') }}"
                            placeholder="Masukkan nama fakultas"
                            required
                            autocomplete="off"
                        >
                        @error('nama_fakultas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Contoh: Fakultas Teknik, Fakultas Ekonomi dan Bisnis, Fakultas Ilmu Komputer
                        </div>
                    </div>

                    <!-- Informasi Tambahan untuk Edit -->
                    @if(isset($fakultas))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Dibuat</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($fakultas['created_at'])->format('d/m/Y H:i') }}"
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
                                        value="{{ \Carbon\Carbon::parse($fakultas['updated_at'])->format('d/m/Y H:i') }}"
                                        readonly
                                        style="background-color: #f8f9fa;"
                                    >
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Button Group -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i>
                            {{ isset($fakultas) ? 'Update Fakultas' : 'Simpan Fakultas' }}
                        </button>
                        <a href="{{ route('admin.fakultas') }}" class="btn btn-secondary">
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
    $('#fakultasForm').on('submit', function(e) {
        var namaFakultas = $('#nama_fakultas').val().trim();

        // Basic validation
        if (!namaFakultas) {
            e.preventDefault();
            $('#nama_fakultas').addClass('is-invalid');

            // Add error message if not exists
            if (!$('#nama_fakultas').next('.invalid-feedback').length) {
                $('#nama_fakultas').after('<div class="invalid-feedback">Nama fakultas harus diisi.</div>');
            }
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

    // Remove error state when user starts typing
    $('#nama_fakultas').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto capitalize first letter of each word
    $('#nama_fakultas').on('input', function() {
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

    // Focus on nama_fakultas field when page loads
    $('#nama_fakultas').focus();

    // Prevent form submission with Enter key (optional)
    $('#nama_fakultas').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#submitBtn').click();
        }
    });
});

// Show confirmation dialog when leaving page with unsaved changes
$(window).on('beforeunload', function(e) {
    var originalValue = '{{ old('nama_fakultas', isset($fakultas) ? $fakultas['nama_fakultas'] : '') }}';
    var currentValue = $('#nama_fakultas').val();

    if (originalValue !== currentValue) {
        return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
    }
});

// Remove beforeunload when form is submitted
$('#fakultasForm').on('submit', function() {
    $(window).off('beforeunload');
});
</script>
@endsection