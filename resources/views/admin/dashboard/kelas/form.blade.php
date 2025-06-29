{{-- File: resources/views/admin/dashboard/kelas/form.blade.php --}}
@extends('layouts.admin-app')

@section('title', isset($kelas) ? 'Edit Kelas' : 'Tambah Kelas')

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

    /* Alert Styling */
    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        border: 1px solid transparent;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
    }

    /* Year Range Input */
    .year-range-help {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
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
    {{ isset($kelas) ? 'Edit Kelas' : 'Tambah Kelas' }}
@endsection

@section('header_button')
<a href="{{ route('admin.kelas') }}" class="btn-back">
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
                    <i class="fas fa-users"></i>
                    {{ isset($kelas) ? 'Edit Data Kelas' : 'Tambah Kelas Baru' }}
                </h3>
            </div>

            <div class="form-body">
                @if(!empty($prodiList))
                    <div class="info-box">
                        <div class="info-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi
                        </div>
                        <p class="info-text">
                            Pastikan untuk memilih program studi yang sesuai dengan kelas yang akan ditambahkan.
                            Nama kelas sebaiknya menggunakan format yang konsisten seperti "FA-A", "TI-1", dll.
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ isset($kelas) ? route('admin.kelas.update', $kelas['id']) : route('admin.kelas.store') }}" id="kelasForm">
                    @csrf
                    @if(isset($kelas))
                        @method('PUT')
                    @endif

                    <!-- Nama Kelas -->
                    <div class="form-group">
                        <label for="nama_kelas" class="form-label required">Nama Kelas</label>
                        <input
                            type="text"
                            class="form-control @error('nama_kelas') is-invalid @enderror"
                            id="nama_kelas"
                            name="nama_kelas"
                            value="{{ old('nama_kelas', isset($kelas) ? $kelas['nama_kelas'] : '') }}"
                            placeholder="Masukkan nama kelas"
                            required
                            autocomplete="off"
                        >
                        @error('nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Contoh: FA-A, TI-1, SI-2, MJ-A, AK-1
                        </div>
                    </div>

                    <!-- Program Studi -->
                    <div class="form-group">
                        <label for="id_prodi" class="form-label required">Program Studi</label>
                        @if(!empty($prodiList))
                            <select
                                class="form-select @error('id_prodi') is-invalid @enderror"
                                id="id_prodi"
                                name="id_prodi"
                                required
                            >
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodiList as $prodi)
                                    <option value="{{ $prodi['id'] }}"
                                        {{ old('id_prodi', isset($kelas) ? $kelas['id_prodi'] : '') == $prodi['id'] ? 'selected' : '' }}>
                                        {{ $prodi['nama_prodi'] }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Tidak ada data program studi yang tersedia. Pastikan Anda sudah menambahkan program studi terlebih dahulu.
                                <br>
                                <a href="{{ route('admin.program-studi.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Tambah Program Studi
                                </a>
                            </div>
                            <input type="hidden" name="id_prodi" value="">
                        @endif

                        @error('id_prodi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Pilih program studi tempat kelas ini berada
                        </div>
                    </div>

                    <!-- Tahun Angkatan -->
                    <div class="form-group">
                        <label for="tahun_angkatan" class="form-label required">Tahun Angkatan</label>
                        <input
                            type="number"
                            class="form-control @error('tahun_angkatan') is-invalid @enderror"
                            id="tahun_angkatan"
                            name="tahun_angkatan"
                            value="{{ old('tahun_angkatan', isset($kelas) ? $kelas['tahun_angkatan'] : date('Y')) }}"
                            min="2000"
                            max="{{ date('Y') + 5 }}"
                            required
                        >
                        @error('tahun_angkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="year-range-help">
                            <i class="fas fa-calendar-alt"></i>
                            Rentang tahun yang diizinkan: 2000 - {{ date('Y') + 5 }}
                        </div>
                    </div>

                    <!-- Informasi Tambahan untuk Edit -->
                    @if(isset($kelas))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Dibuat</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($kelas['created_at'])->format('d/m/Y H:i') }}"
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
                                        value="{{ \Carbon\Carbon::parse($kelas['updated_at'])->format('d/m/Y H:i') }}"
                                        readonly
                                        style="background-color: #f8f9fa;"
                                    >
                                </div>
                            </div>
                        </div>

                        @if(isset($kelas['nama_prodi']))
                            <div class="form-group">
                                <label class="form-label">Program Studi Saat Ini</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $kelas['nama_prodi'] }}"
                                    readonly
                                    style="background-color: #f8f9fa;"
                                >
                                <div class="form-help">
                                    Ini adalah program studi yang saat ini terkait dengan kelas ini
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Button Group -->
                    <div class="button-group">
                        @if(!empty($prodiList))
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i>
                                {{ isset($kelas) ? 'Update Kelas' : 'Simpan Kelas' }}
                            </button>
                        @endif
                        <a href="{{ route('admin.kelas') }}" class="btn btn-secondary">
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
    $('#kelasForm').on('submit', function(e) {
        var namaKelas = $('#nama_kelas').val().trim();
        var idProdi = $('#id_prodi').val();
        var tahunAngkatan = $('#tahun_angkatan').val();
        var isValid = true;

        // Reset previous error states
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Validate nama kelas
        if (!namaKelas) {
            $('#nama_kelas').addClass('is-invalid');
            $('#nama_kelas').after('<div class="invalid-feedback">Nama kelas harus diisi.</div>');
            isValid = false;
        }

        // Validate program studi selection
        if (!idProdi) {
            $('#id_prodi').addClass('is-invalid');
            $('#id_prodi').after('<div class="invalid-feedback">Program studi harus dipilih.</div>');
            isValid = false;
        }

        // Validate tahun angkatan
        if (!tahunAngkatan) {
            $('#tahun_angkatan').addClass('is-invalid');
            $('#tahun_angkatan').after('<div class="invalid-feedback">Tahun angkatan harus diisi.</div>');
            isValid = false;
        } else {
            var currentYear = new Date().getFullYear();
            var tahun = parseInt(tahunAngkatan);
            if (tahun < 2000 || tahun > (currentYear + 5)) {
                $('#tahun_angkatan').addClass('is-invalid');
                $('#tahun_angkatan').after('<div class="invalid-feedback">Tahun angkatan harus antara 2000 - ' + (currentYear + 5) + '.</div>');
                isValid = false;
            }
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
    $('#nama_kelas, #id_prodi, #tahun_angkatan').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto format nama kelas (uppercase)
    $('#nama_kelas').on('input', function() {
        var value = $(this).val().toUpperCase();
        $(this).val(value);
    });

    // Set default tahun angkatan to current year
    if (!$('#tahun_angkatan').val()) {
        $('#tahun_angkatan').val(new Date().getFullYear());
    }

    // Focus on nama_kelas field when page loads
    $('#nama_kelas').focus();

    // Handle Enter key navigation
    $('#nama_kelas').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#id_prodi').focus();
        }
    });

    $('#id_prodi').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#tahun_angkatan').focus();
        }
    });

    $('#tahun_angkatan').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            if ($('#submitBtn').length) {
                $('#submitBtn').click();
            }
        }
    });

    // Highlight program studi change
    $('#id_prodi').on('change', function() {
        var selectedText = $(this).find('option:selected').text();
        if (selectedText !== 'Pilih Program Studi') {
            console.log('Selected program studi:', selectedText);
        }
    });

    // Auto-suggest kelas naming based on program studi
    $('#id_prodi').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var prodiName = selectedOption.text();
        var currentNamaKelas = $('#nama_kelas').val();

        if (!currentNamaKelas && prodiName && prodiName !== 'Pilih Program Studi') {
            // Extract acronym from program studi name
            var acronym = prodiName.split('-').pop() || prodiName;
            var words = acronym.split(' ');
            var suggestion = '';

            if (words.length > 1) {
                suggestion = words.map(word => word.charAt(0)).join('').toUpperCase() + '-A';
            } else {
                suggestion = acronym.substring(0, 2).toUpperCase() + '-A';
            }

            // Show suggestion in placeholder
            $('#nama_kelas').attr('placeholder', 'Contoh: ' + suggestion);
        }
    });
});

// Show confirmation dialog when leaving page with unsaved changes
$(window).on('beforeunload', function(e) {
    var originalNamaKelas = '{{ old('nama_kelas', isset($kelas) ? $kelas['nama_kelas'] : '') }}';
    var originalIdProdi = '{{ old('id_prodi', isset($kelas) ? $kelas['id_prodi'] : '') }}';
    var originalTahunAngkatan = '{{ old('tahun_angkatan', isset($kelas) ? $kelas['tahun_angkatan'] : '') }}';

    var currentNamaKelas = $('#nama_kelas').val();
    var currentIdProdi = $('#id_prodi').val();
    var currentTahunAngkatan = $('#tahun_angkatan').val();

    if (originalNamaKelas !== currentNamaKelas ||
        originalIdProdi !== currentIdProdi ||
        originalTahunAngkatan !== currentTahunAngkatan) {
        return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
    }
});

// Remove beforeunload when form is submitted
$('#kelasForm').on('submit', function() {
    $(window).off('beforeunload');
});
</script>
@endsection