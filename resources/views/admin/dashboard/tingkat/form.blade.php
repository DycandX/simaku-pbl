{{-- File: resources/views/admin/dashboard/tingkat/form.blade.php --}}
@extends('layouts.admin-app')

@section('title', isset($tingkat) ? 'Edit Tingkat' : 'Tambah Tingkat')

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

    /* Textarea specific styling */
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
        line-height: 1.5;
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

    /* Character Counter */
    .char-counter {
        font-size: 11px;
        color: #6b7280;
        text-align: right;
        margin-top: 5px;
    }

    .char-counter.warning {
        color: #f6c23e;
    }

    .char-counter.danger {
        color: #e74a3b;
    }
</style>
@endsection

@section('header')
    {{ isset($tingkat) ? 'Edit Tingkat' : 'Tambah Tingkat' }}
@endsection

@section('header_button')
<a href="{{ route('admin.tingkat') }}" class="btn-back">
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
                    <i class="fas fa-layer-group"></i>
                    {{ isset($tingkat) ? 'Edit Data Tingkat' : 'Tambah Tingkat Baru' }}
                </h3>
            </div>

            <div class="form-body">
                <form method="POST" action="{{ isset($tingkat) ? route('admin.tingkat.update', $tingkat['id']) : route('admin.tingkat.store') }}" id="tingkatForm">
                    @csrf
                    @if(isset($tingkat))
                        @method('PUT')
                    @endif

                    <!-- Nama Tingkat -->
                    <div class="form-group">
                        <label for="nama_tingkat" class="form-label required">Nama Tingkat</label>
                        <input
                            type="text"
                            class="form-control @error('nama_tingkat') is-invalid @enderror"
                            id="nama_tingkat"
                            name="nama_tingkat"
                            value="{{ old('nama_tingkat', isset($tingkat) ? $tingkat['nama_tingkat'] : '') }}"
                            placeholder="Masukkan nama tingkat"
                            required
                            autocomplete="off"
                            maxlength="100"
                        >
                        @error('nama_tingkat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Contoh: Tahun Pertama, Tahun Kedua, Semester 1, Semester 2
                        </div>
                        <div class="char-counter" id="nama-counter">0/100</div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            id="deskripsi"
                            name="deskripsi"
                            placeholder="Masukkan deskripsi tingkat (opsional)"
                            rows="4"
                            maxlength="500"
                        >{{ old('deskripsi', isset($tingkat) ? $tingkat['deskripsi'] : '') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Deskripsi singkat tentang tingkat ini. Maksimal 500 karakter.
                        </div>
                        <div class="char-counter" id="desc-counter">0/500</div>
                    </div>

                    <!-- Informasi Tambahan untuk Edit -->
                    @if(isset($tingkat))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Dibuat</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($tingkat['created_at'])->format('d/m/Y H:i') }}"
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
                                        value="{{ \Carbon\Carbon::parse($tingkat['updated_at'])->format('d/m/Y H:i') }}"
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
                            {{ isset($tingkat) ? 'Update Tingkat' : 'Simpan Tingkat' }}
                        </button>
                        <a href="{{ route('admin.tingkat') }}" class="btn btn-secondary">
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
    // Character counter function
    function updateCharCounter(input, counter, maxLength) {
        var currentLength = input.val().length;
        var counterElement = $(counter);

        counterElement.text(currentLength + '/' + maxLength);

        // Color coding
        if (currentLength > maxLength * 0.9) {
            counterElement.removeClass('warning').addClass('danger');
        } else if (currentLength > maxLength * 0.7) {
            counterElement.removeClass('danger').addClass('warning');
        } else {
            counterElement.removeClass('warning danger');
        }
    }

    // Initialize character counters
    updateCharCounter($('#nama_tingkat'), '#nama-counter', 100);
    updateCharCounter($('#deskripsi'), '#desc-counter', 500);

    // Update counters on input
    $('#nama_tingkat').on('input', function() {
        updateCharCounter($(this), '#nama-counter', 100);
    });

    $('#deskripsi').on('input', function() {
        updateCharCounter($(this), '#desc-counter', 500);
    });

    // Form validation
    $('#tingkatForm').on('submit', function(e) {
        var namaTingkat = $('#nama_tingkat').val().trim();
        var isValid = true;

        // Basic validation
        if (!namaTingkat) {
            e.preventDefault();
            $('#nama_tingkat').addClass('is-invalid');

            // Add error message if not exists
            if (!$('#nama_tingkat').next('.invalid-feedback').length) {
                $('#nama_tingkat').after('<div class="invalid-feedback">Nama tingkat harus diisi.</div>');
            }
            isValid = false;
        }

        if (!isValid) {
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
    $('#nama_tingkat').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    $('#deskripsi').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto capitalize first letter of each word for nama_tingkat
    $('#nama_tingkat').on('input', function() {
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

    // Focus on nama_tingkat field when page loads
    $('#nama_tingkat').focus();

    // Handle Enter key on nama_tingkat
    $('#nama_tingkat').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#deskripsi').focus();
        }
    });

    // Handle Ctrl+Enter to submit form
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.which === 13) {
            $('#submitBtn').click();
        }
    });
});

// Show confirmation dialog when leaving page with unsaved changes
$(window).on('beforeunload', function(e) {
    var originalNama = '{{ old('nama_tingkat', isset($tingkat) ? $tingkat['nama_tingkat'] : '') }}';
    var originalDesc = '{{ old('deskripsi', isset($tingkat) ? $tingkat['deskripsi'] : '') }}';
    var currentNama = $('#nama_tingkat').val();
    var currentDesc = $('#deskripsi').val();

    if (originalNama !== currentNama || originalDesc !== currentDesc) {
        return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
    }
});

// Remove beforeunload when form is submitted
$('#tingkatForm').on('submit', function() {
    $(window).off('beforeunload');
});
</script>
@endsection