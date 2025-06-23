{{-- File: resources/views/admin/dashboard/staff/form.blade.php --}}
@extends('layouts.admin-app')

@section('title', isset($staff) ? 'Edit Staff' : 'Tambah Staff')

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
    {{ isset($staff) ? 'Edit Staff' : 'Tambah Staff' }}
@endsection

@section('header_button')
<a href="{{ route('admin.staff') }}" class="btn-back">
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
                    <i class="fas fa-user-tie"></i>
                    {{ isset($staff) ? 'Edit Data Staff' : 'Tambah Staff Baru' }}
                </h3>
            </div>

            <div class="form-body">
                <div class="info-box">
                    <div class="info-title">
                        <i class="fas fa-info-circle"></i>
                        Informasi
                    </div>
                    <p class="info-text">
                        Pastikan untuk mengisi semua data staff dengan benar. NIP akan digunakan sebagai username untuk login ke sistem.
                    </p>
                </div>

                <form method="POST" action="{{ isset($staff) ? route('admin.staff.update', $staff['id']) : route('admin.staff.store') }}" id="staffForm">
                    @csrf
                    @if(isset($staff))
                        @method('PUT')
                    @endif

                    <!-- NIP -->
                    <div class="form-group">
                        <label for="nip" class="form-label required">NIP (Nomor Induk Pegawai)</label>
                        <input
                            type="text"
                            class="form-control @error('nip') is-invalid @enderror"
                            id="nip"
                            name="nip"
                            value="{{ old('nip', isset($staff) ? $staff['nip'] : '') }}"
                            placeholder="Masukkan NIP"
                            required
                            autocomplete="off"
                            maxlength="20"
                        >
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            NIP harus unik dan akan digunakan sebagai username untuk login
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label for="nama_lengkap" class="form-label required">Nama Lengkap</label>
                        <input
                            type="text"
                            class="form-control @error('nama_lengkap') is-invalid @enderror"
                            id="nama_lengkap"
                            name="nama_lengkap"
                            value="{{ old('nama_lengkap', isset($staff) ? $staff['nama_lengkap'] : '') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                            autocomplete="off"
                        >
                        @error('nama_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Masukkan nama lengkap sesuai dengan dokumen resmi
                        </div>
                    </div>

                    <!-- Jabatan -->
                    <div class="form-group">
                        <label for="jabatan" class="form-label required">Jabatan</label>
                        <select
                            class="form-select @error('jabatan') is-invalid @enderror"
                            id="jabatan"
                            name="jabatan"
                            required
                        >
                            <option value="">Pilih Jabatan</option>
                            <option value="Staff" {{ old('jabatan', isset($staff) ? $staff['jabatan'] : '') == 'Staff' ? 'selected' : '' }}>Staff</option>
                            <option value="Supervisor" {{ old('jabatan', isset($staff) ? $staff['jabatan'] : '') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                            <option value="Manager" {{ old('jabatan', isset($staff) ? $staff['jabatan'] : '') == 'Manager' ? 'selected' : '' }}>Manager</option>
                            <option value="Kepala Bagian" {{ old('jabatan', isset($staff) ? $staff['jabatan'] : '') == 'Kepala Bagian' ? 'selected' : '' }}>Kepala Bagian</option>
                            <option value="Sekretaris" {{ old('jabatan', isset($staff) ? $staff['jabatan'] : '') == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                            <option value="Bendahara" {{ old('jabatan', isset($staff) ? $staff['jabatan'] : '') == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                            <option value="Koordinator" {{ old('jabatan', isset($staff) ? $staff['jabatan'] : '') == 'Koordinator' ? 'selected' : '' }}>Koordinator</option>
                        </select>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Pilih jabatan yang sesuai dengan posisi staff
                        </div>
                    </div>

                    <!-- Unit Kerja -->
                    <div class="form-group">
                        <label for="unit_kerja" class="form-label required">Unit Kerja</label>
                        <select
                            class="form-select @error('unit_kerja') is-invalid @enderror"
                            id="unit_kerja"
                            name="unit_kerja"
                            required
                        >
                            <option value="">Pilih Unit Kerja</option>
                            <option value="Bagian Keuangan" {{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') == 'Bagian Keuangan' ? 'selected' : '' }}>Bagian Keuangan</option>
                            <option value="Bagian Akademik" {{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') == 'Bagian Akademik' ? 'selected' : '' }}>Bagian Akademik</option>
                            <option value="Bagian Kemahasiswaan" {{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') == 'Bagian Kemahasiswaan' ? 'selected' : '' }}>Bagian Kemahasiswaan</option>
                            <option value="Bagian Umum" {{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') == 'Bagian Umum' ? 'selected' : '' }}>Bagian Umum</option>
                            <option value="Bagian SDM" {{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') == 'Bagian SDM' ? 'selected' : '' }}>Bagian SDM</option>
                            <option value="Bagian IT" {{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') == 'Bagian IT' ? 'selected' : '' }}>Bagian IT</option>
                            <option value="Bagian Marketing" {{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') == 'Bagian Marketing' ? 'selected' : '' }}>Bagian Marketing</option>
                            <option value="Bagian Penelitian" {{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') == 'Bagian Penelitian' ? 'selected' : '' }}>Bagian Penelitian</option>
                            <option value="Bagian Perpustakaan" {{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') == 'Bagian Perpustakaan' ? 'selected' : '' }}>Bagian Perpustakaan</option>
                        </select>
                        @error('unit_kerja')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Pilih unit kerja tempat staff bertugas
                        </div>
                    </div>

                    <!-- Email (untuk user account) -->
                    @if(!isset($staff))
                        <div class="form-group">
                            <label for="email" class="form-label required">Email</label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email"
                                required
                                autocomplete="off"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-help">
                                Email akan digunakan untuk akun login staff
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label required">Password</label>
                            <input
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                required
                                autocomplete="new-password"
                                minlength="6"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-help">
                                Password minimal 6 karakter
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label required">Konfirmasi Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Konfirmasi password"
                                required
                                autocomplete="new-password"
                                minlength="6"
                            >
                            <div class="form-help">
                                Masukkan ulang password untuk konfirmasi
                            </div>
                        </div>
                    @endif

                    <!-- Informasi Tambahan untuk Edit -->
                    @if(isset($staff))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Dibuat</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($staff['created_at'])->format('d/m/Y H:i') }}"
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
                                        value="{{ \Carbon\Carbon::parse($staff['updated_at'])->format('d/m/Y H:i') }}"
                                        readonly
                                        style="background-color: #f8f9fa;"
                                    >
                                </div>
                            </div>
                        </div>

                        @if(isset($staff['user']))
                            <div class="form-group">
                                <label class="form-label">Status Akun</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $staff['user']['is_active'] ? 'Aktif' : 'Tidak Aktif' }}"
                                    readonly
                                    style="background-color: #f8f9fa;"
                                >
                                <div class="form-help">
                                    Status akun user yang terkait dengan staff ini
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Button Group -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i>
                            {{ isset($staff) ? 'Update Staff' : 'Simpan Staff' }}
                        </button>
                        <a href="{{ route('admin.staff') }}" class="btn btn-secondary">
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
    $('#staffForm').on('submit', function(e) {
        var nip = $('#nip').val().trim();
        var namaLengkap = $('#nama_lengkap').val().trim();
        var jabatan = $('#jabatan').val();
        var unitKerja = $('#unit_kerja').val();
        var email = $('#email').val().trim();
        var password = $('#password').val();
        var passwordConfirmation = $('#password_confirmation').val();
        var isValid = true;

        // Reset previous error states
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Validate NIP
        if (!nip) {
            $('#nip').addClass('is-invalid');
            $('#nip').after('<div class="invalid-feedback">NIP harus diisi.</div>');
            isValid = false;
        } else if (nip.length < 10) {
            $('#nip').addClass('is-invalid');
            $('#nip').after('<div class="invalid-feedback">NIP minimal 10 karakter.</div>');
            isValid = false;
        }

        // Validate nama lengkap
        if (!namaLengkap) {
            $('#nama_lengkap').addClass('is-invalid');
            $('#nama_lengkap').after('<div class="invalid-feedback">Nama lengkap harus diisi.</div>');
            isValid = false;
        }

        // Validate jabatan
        if (!jabatan) {
            $('#jabatan').addClass('is-invalid');
            $('#jabatan').after('<div class="invalid-feedback">Jabatan harus dipilih.</div>');
            isValid = false;
        }

        // Validate unit kerja
        if (!unitKerja) {
            $('#unit_kerja').addClass('is-invalid');
            $('#unit_kerja').after('<div class="invalid-feedback">Unit kerja harus dipilih.</div>');
            isValid = false;
        }

        // Validate email (only for new staff)
        @if(!isset($staff))
        if (!email) {
            $('#email').addClass('is-invalid');
            $('#email').after('<div class="invalid-feedback">Email harus diisi.</div>');
            isValid = false;
        } else if (!isValidEmail(email)) {
            $('#email').addClass('is-invalid');
            $('#email').after('<div class="invalid-feedback">Format email tidak valid.</div>');
            isValid = false;
        }

        // Validate password
        if (!password) {
            $('#password').addClass('is-invalid');
            $('#password').after('<div class="invalid-feedback">Password harus diisi.</div>');
            isValid = false;
        } else if (password.length < 6) {
            $('#password').addClass('is-invalid');
            $('#password').after('<div class="invalid-feedback">Password minimal 6 karakter.</div>');
            isValid = false;
        }

        // Validate password confirmation
        if (!passwordConfirmation) {
            $('#password_confirmation').addClass('is-invalid');
            $('#password_confirmation').after('<div class="invalid-feedback">Konfirmasi password harus diisi.</div>');
            isValid = false;
        } else if (password !== passwordConfirmation) {
            $('#password_confirmation').addClass('is-invalid');
            $('#password_confirmation').after('<div class="invalid-feedback">Konfirmasi password tidak sesuai.</div>');
            isValid = false;
        }
        @endif

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

    // Email validation function
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Remove error state when user interacts with fields
    $('#nip, #nama_lengkap, #jabatan, #unit_kerja, #email, #password, #password_confirmation').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto format NIP (numbers only)
    $('#nip').on('input', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);
    });

    // Auto format nama lengkap (proper case)
    $('#nama_lengkap').on('blur', function() {
        var value = $(this).val();
        var properCase = value.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
        $(this).val(properCase);
    });

    // Focus on NIP field when page loads
    $('#nip').focus();

    // Handle Enter key navigation
    $('#nip').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#nama_lengkap').focus();
        }
    });

    $('#nama_lengkap').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#jabatan').focus();
        }
    });

    $('#jabatan').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#unit_kerja').focus();
        }
    });

    $('#unit_kerja').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            @if(!isset($staff))
            $('#email').focus();
            @else
            $('#submitBtn').click();
            @endif
        }
    });

    @if(!isset($staff))
    $('#email').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#password').focus();
        }
    });

    $('#password').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#password_confirmation').focus();
        }
    });

    $('#password_confirmation').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#submitBtn').click();
        }
    });
    @endif
});

// Show confirmation dialog when leaving page with unsaved changes
$(window).on('beforeunload', function(e) {
    var originalNip = '{{ old('nip', isset($staff) ? $staff['nip'] : '') }}';
    var originalNamaLengkap = '{{ old('nama_lengkap', isset($staff) ? $staff['nama_lengkap'] : '') }}';
    var originalJabatan = '{{ old('jabatan', isset($staff) ? $staff['jabatan'] : '') }}';
    var originalUnitKerja = '{{ old('unit_kerja', isset($staff) ? $staff['unit_kerja'] : '') }}';

    var currentNip = $('#nip').val();
    var currentNamaLengkap = $('#nama_lengkap').val();
    var currentJabatan = $('#jabatan').val();
    var currentUnitKerja = $('#unit_kerja').val();

    if (originalNip !== currentNip ||
        originalNamaLengkap !== currentNamaLengkap ||
        originalJabatan !== currentJabatan ||
        originalUnitKerja !== currentUnitKerja) {
        return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
    }
});

// Remove beforeunload when form is submitted
$('#staffForm').on('submit', function() {
    $(window).off('beforeunload');
});
</script>
@endsection