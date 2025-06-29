{{-- File: resources/views/admin/dashboard/tahun-akademik/form.blade.php --}}
@extends('layouts.admin-app')

@section('title', isset($tahunAkademik) ? 'Edit Tahun Akademik' : 'Tambah Tahun Akademik')

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

    /* Select styling */
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px 12px;
        padding-right: 40px;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        gap: 6px;
    }

    .status-badge.aktif {
        background-color: #d1f2eb;
        color: #0f5132;
        border: 1px solid #a3e3d0;
    }

    .status-badge.non-aktif {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f1a2a8;
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-indicator.aktif {
        background-color: #198754;
    }

    .status-indicator.non-aktif {
        background-color: #dc3545;
    }

    /* Date inputs */
    input[type="date"].form-control {
        position: relative;
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

    /* Date Range Preview */
    .date-range-preview {
        background-color: #f8f9fc;
        border: 1px solid #e3e6f0;
        border-radius: 6px;
        padding: 15px;
        margin-top: 15px;
        font-size: 13px;
        color: #5a5c69;
    }

    .date-range-preview .range-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .date-range-preview .range-item:last-child {
        margin-bottom: 0;
    }

    .date-range-preview strong {
        color: #374151;
    }
</style>
@endsection

@section('header')
    {{ isset($tahunAkademik) ? 'Edit Tahun Akademik' : 'Tambah Tahun Akademik' }}
@endsection

@section('header_button')
<a href="{{ route('admin.tahun-akademik') }}" class="btn-back">
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
                    <i class="fas fa-calendar-alt"></i>
                    {{ isset($tahunAkademik) ? 'Edit Data Tahun Akademik' : 'Tambah Tahun Akademik Baru' }}
                </h3>
            </div>

            <div class="form-body">
                <form method="POST" action="{{ isset($tahunAkademik) ? route('admin.tahun-akademik.update', $tahunAkademik['id']) : route('admin.tahun-akademik.store') }}" id="tahunAkademikForm">
                    @csrf
                    @if(isset($tahunAkademik))
                        @method('PUT')
                    @endif

                    <!-- Tahun Akademik -->
                    <div class="form-group">
                        <label for="tahun_akademik" class="form-label required">Tahun Akademik</label>
                        <input
                            type="text"
                            class="form-control @error('tahun_akademik') is-invalid @enderror"
                            id="tahun_akademik"
                            name="tahun_akademik"
                            value="{{ old('tahun_akademik', isset($tahunAkademik) ? $tahunAkademik['tahun_akademik'] : '') }}"
                            placeholder="Contoh: 2024/2025"
                            required
                            autocomplete="off"
                            maxlength="255"
                        >
                        @error('tahun_akademik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Format: YYYY/YYYY (contoh: 2024/2025)
                        </div>
                        <div class="char-counter" id="tahun-counter">0/255</div>
                    </div>

                    <!-- Semester -->
                    <div class="form-group">
                        <label for="semester" class="form-label required">Semester</label>
                        <select
                            class="form-control @error('semester') is-invalid @enderror"
                            id="semester"
                            name="semester"
                            required
                        >
                            <option value="">Pilih Semester</option>
                            <option value="Ganjil" {{ old('semester', isset($tahunAkademik) ? $tahunAkademik['semester'] : '') == 'Ganjil' ? 'selected' : '' }}>
                                Ganjil (Semester 1, 3, 5, 7)
                            </option>
                            <option value="Genap" {{ old('semester', isset($tahunAkademik) ? $tahunAkademik['semester'] : '') == 'Genap' ? 'selected' : '' }}>
                                Genap (Semester 2, 4, 6, 8)
                            </option>
                        </select>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Pilih jenis semester untuk tahun akademik ini
                        </div>
                    </div>

                    <!-- Tanggal Mulai dan Selesai -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_mulai" class="form-label required">Tanggal Mulai</label>
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                    id="tanggal_mulai"
                                    name="tanggal_mulai"
                                    value="{{ old('tanggal_mulai', isset($tahunAkademik) ? $tahunAkademik['tanggal_mulai'] : '') }}"
                                    required
                                >
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_selesai" class="form-label required">Tanggal Selesai</label>
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                    id="tanggal_selesai"
                                    name="tanggal_selesai"
                                    value="{{ old('tanggal_selesai', isset($tahunAkademik) ? $tahunAkademik['tanggal_selesai'] : '') }}"
                                    required
                                >
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Preview -->
                    <div class="date-range-preview" id="dateRangePreview" style="display: none;">
                        <div class="range-item">
                            <span>Durasi:</span>
                            <strong id="durationText">-</strong>
                        </div>
                        <div class="range-item">
                            <span>Periode:</span>
                            <strong id="periodText">-</strong>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status" class="form-label required">Status</label>
                        <select
                            class="form-control @error('status') is-invalid @enderror"
                            id="status"
                            name="status"
                            required
                        >
                            <option value="">Pilih Status</option>
                            <option value="aktif" {{ old('status', isset($tahunAkademik) ? $tahunAkademik['status'] : '') == 'aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="non-aktif" {{ old('status', isset($tahunAkademik) ? $tahunAkademik['status'] : '') == 'non-aktif' ? 'selected' : '' }}>
                                Non-Aktif
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            <div class="mt-2">
                                <span class="status-badge aktif">
                                    <span class="status-indicator aktif"></span>
                                    Aktif - Tahun akademik yang sedang berjalan
                                </span>
                                <br><br>
                                <span class="status-badge non-aktif">
                                    <span class="status-indicator non-aktif"></span>
                                    Non-Aktif - Tahun akademik yang tidak aktif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan untuk Edit -->
                    @if(isset($tahunAkademik))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Dibuat</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($tahunAkademik['created_at'])->format('d/m/Y H:i') }}"
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
                                        value="{{ \Carbon\Carbon::parse($tahunAkademik['updated_at'])->format('d/m/Y H:i') }}"
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
                            {{ isset($tahunAkademik) ? 'Update Tahun Akademik' : 'Simpan Tahun Akademik' }}
                        </button>
                        <a href="{{ route('admin.tahun-akademik') }}" class="btn btn-secondary">
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

    // Date range calculation
    function updateDateRangePreview() {
        var startDate = $('#tanggal_mulai').val();
        var endDate = $('#tanggal_selesai').val();

        if (startDate && endDate) {
            var start = new Date(startDate);
            var end = new Date(endDate);

            if (end > start) {
                var diffTime = Math.abs(end - start);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                var diffMonths = Math.floor(diffDays / 30);
                var remainingDays = diffDays % 30;

                var durationText = '';
                if (diffMonths > 0) {
                    durationText = diffMonths + ' bulan';
                    if (remainingDays > 0) {
                        durationText += ' ' + remainingDays + ' hari';
                    }
                } else {
                    durationText = diffDays + ' hari';
                }

                var periodText = start.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }) + ' - ' + end.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                $('#durationText').text(durationText);
                $('#periodText').text(periodText);
                $('#dateRangePreview').show();
            } else {
                $('#dateRangePreview').hide();
            }
        } else {
            $('#dateRangePreview').hide();
        }
    }

    // Initialize character counter
    updateCharCounter($('#tahun_akademik'), '#tahun-counter', 255);

    // Update counter on input
    $('#tahun_akademik').on('input', function() {
        updateCharCounter($(this), '#tahun-counter', 255);
    });

    // Update date range preview
    $('#tanggal_mulai, #tanggal_selesai').on('change', updateDateRangePreview);

    // Initialize date range preview
    updateDateRangePreview();

    // Form validation
    $('#tahunAkademikForm').on('submit', function(e) {
        var tahunAkademik = $('#tahun_akademik').val().trim();
        var semester = $('#semester').val();
        var tanggalMulai = $('#tanggal_mulai').val();
        var tanggalSelesai = $('#tanggal_selesai').val();
        var status = $('#status').val();
        var isValid = true;

        // Reset error states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Basic validation
        if (!tahunAkademik) {
            $('#tahun_akademik').addClass('is-invalid').after('<div class="invalid-feedback">Tahun akademik harus diisi.</div>');
            isValid = false;
        }

        if (!semester) {
            $('#semester').addClass('is-invalid').after('<div class="invalid-feedback">Semester harus dipilih.</div>');
            isValid = false;
        }

        if (!tanggalMulai) {
            $('#tanggal_mulai').addClass('is-invalid').after('<div class="invalid-feedback">Tanggal mulai harus diisi.</div>');
            isValid = false;
        }

        if (!tanggalSelesai) {
            $('#tanggal_selesai').addClass('is-invalid').after('<div class="invalid-feedback">Tanggal selesai harus diisi.</div>');
            isValid = false;
        } else if (tanggalMulai && new Date(tanggalSelesai) <= new Date(tanggalMulai)) {
            $('#tanggal_selesai').addClass('is-invalid').after('<div class="invalid-feedback">Tanggal selesai harus setelah tanggal mulai.</div>');
            isValid = false;
        }

        if (!status) {
            $('#status').addClass('is-invalid').after('<div class="invalid-feedback">Status harus dipilih.</div>');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
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

    // Remove error state when user interacts with form
    $('.form-control').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto format tahun akademik
    $('#tahun_akademik').on('input', function() {
        var value = $(this).val();
        // Remove any non-digit characters except slash
        value = value.replace(/[^\d\/]/g, '');

        // Auto-format to YYYY/YYYY pattern
        if (value.length === 4 && !value.includes('/')) {
            value = value + '/';
        }

        $(this).val(value);
    });

    // Focus on tahun_akademik field when page loads
    $('#tahun_akademik').focus();

    // Handle Tab navigation
    $('#tahun_akademik').on('keydown', function(e) {
        if (e.which === 9) { // Tab key
            e.preventDefault();
            $('#semester').focus();
        }
    });

    $('#semester').on('keydown', function(e) {
        if (e.which === 9 && !e.shiftKey) { // Tab key forward
            e.preventDefault();
            $('#tanggal_mulai').focus();
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
    var originalTahun = '{{ old('tahun_akademik', isset($tahunAkademik) ? $tahunAkademik['tahun_akademik'] : '') }}';
    var originalSemester = '{{ old('semester', isset($tahunAkademik) ? $tahunAkademik['semester'] : '') }}';
    var originalTanggalMulai = '{{ old('tanggal_mulai', isset($tahunAkademik) ? $tahunAkademik['tanggal_mulai'] : '') }}';
    var originalTanggalSelesai = '{{ old('tanggal_selesai', isset($tahunAkademik) ? $tahunAkademik['tanggal_selesai'] : '') }}';
    var originalStatus = '{{ old('status', isset($tahunAkademik) ? $tahunAkademik['status'] : '') }}';

    var currentTahun = $('#tahun_akademik').val();
    var currentSemester = $('#semester').val();
    var currentTanggalMulai = $('#tanggal_mulai').val();
    var currentTanggalSelesai = $('#tanggal_selesai').val();
    var currentStatus = $('#status').val();

    if (originalTahun !== currentTahun ||
        originalSemester !== currentSemester ||
        originalTanggalMulai !== currentTanggalMulai ||
        originalTanggalSelesai !== currentTanggalSelesai ||
        originalStatus !== currentStatus) {
        return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
    }
});

// Remove beforeunload when form is submitted
$('#tahunAkademikForm').on('submit', function() {
    $(window).off('beforeunload');
});
</script>
@endsection