{{-- File: resources/views/admin/dashboard/mahasiswa/form.blade.php --}}
@extends('layouts.admin-app')

@section('title', isset($mahasiswa) ? 'Edit Mahasiswa' : 'Tambah Mahasiswa')

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

    /* Textarea Styling */
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    /* File Upload Styling */
    .file-upload-container {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-upload-input {
        position: absolute;
        left: -9999px;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        border: 2px dashed #d1d5db;
        border-radius: 6px;
        background-color: #f9fafb;
        cursor: pointer;
        transition: all 0.2s;
        color: #6b7280;
        font-size: 14px;
    }

    .file-upload-label:hover {
        border-color: #4e73df;
        background-color: #f3f4f6;
        color: #4e73df;
    }

    .file-upload-preview {
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background-color: #f9fafb;
        display: none;
    }

    .file-preview-image {
        max-width: 150px;
        max-height: 150px;
        border-radius: 6px;
        object-fit: cover;
    }

    .file-remove-btn {
        background-color: #e74a3b;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 12px;
        cursor: pointer;
        margin-top: 5px;
    }

    .file-remove-btn:hover {
        background-color: #c0392b;
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

    /* Current Photo Display */
    .current-photo {
        margin-bottom: 15px;
        padding: 15px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background-color: #f9fafb;
    }

    .current-photo img {
        max-width: 150px;
        max-height: 150px;
        border-radius: 6px;
        object-fit: cover;
        border: 2px solid #e3e6f0;
    }

    .current-photo-label {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 8px;
        font-weight: 500;
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

        .file-preview-image {
            max-width: 100px;
            max-height: 100px;
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
    {{ isset($mahasiswa) ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' }}
@endsection

@section('header_button')
<a href="{{ route('admin.mahasiswa') }}" class="btn-back">
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
                    <i class="fas fa-user-graduate"></i>
                    {{ isset($mahasiswa) ? 'Edit Data Mahasiswa' : 'Tambah Mahasiswa Baru' }}
                </h3>
            </div>

            <div class="form-body">
                <div class="info-box">
                    <div class="info-title">
                        <i class="fas fa-info-circle"></i>
                        Informasi
                    </div>
                    <p class="info-text">
                        Pastikan untuk mengisi semua data mahasiswa dengan benar. NIM harus unik dan akan menjadi identitas utama mahasiswa.
                    </p>
                </div>

                <form method="POST" action="{{ isset($mahasiswa) ? route('admin.mahasiswa.update', $mahasiswa['id']) : route('admin.mahasiswa.store') }}" id="mahasiswaForm" enctype="multipart/form-data">
                    @csrf
                    @if(isset($mahasiswa))
                        @method('PUT')
                    @endif

                    <!-- NIM -->
                    <div class="form-group">
                        <label for="nim" class="form-label required">NIM (Nomor Induk Mahasiswa)</label>
                        <input
                            type="text"
                            class="form-control @error('nim') is-invalid @enderror"
                            id="nim"
                            name="nim"
                            value="{{ old('nim', isset($mahasiswa) ? $mahasiswa['nim'] : '') }}"
                            placeholder="Masukkan NIM"
                            required
                            autocomplete="off"
                            maxlength="20"
                        >
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            NIM harus unik dan sesuai dengan format yang ditetapkan institusi
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
                            value="{{ old('nama_lengkap', isset($mahasiswa) ? $mahasiswa['nama_lengkap'] : '') }}"
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

                    <!-- Alamat -->
                    <div class="form-group">
                        <label for="alamat" class="form-label required">Alamat</label>
                        <textarea
                            class="form-control form-textarea @error('alamat') is-invalid @enderror"
                            id="alamat"
                            name="alamat"
                            placeholder="Masukkan alamat lengkap"
                            required
                            maxlength="500"
                        >{{ old('alamat', isset($mahasiswa) ? $mahasiswa['alamat'] : '') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Masukkan alamat lengkap termasuk kota dan kode pos (maksimal 500 karakter)
                        </div>
                    </div>

                    <!-- No Telepon -->
                    <div class="form-group">
                        <label for="no_telepon" class="form-label required">No. Telepon</label>
                        <input
                            type="tel"
                            class="form-control @error('no_telepon') is-invalid @enderror"
                            id="no_telepon"
                            name="no_telepon"
                            value="{{ old('no_telepon', isset($mahasiswa) ? $mahasiswa['no_telepon'] : '') }}"
                            placeholder="Masukkan nomor telepon"
                            required
                            autocomplete="off"
                            maxlength="20"
                        >
                        @error('no_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Masukkan nomor telepon yang aktif (contoh: 081234567890)
                        </div>
                    </div>

                    <!-- Foto -->
                    <div class="form-group">
                        <label for="foto" class="form-label">Foto Mahasiswa</label>

                        @if(isset($mahasiswa) && $mahasiswa['foto_path'])
                            <div class="current-photo">
                                <div class="current-photo-label">Foto saat ini:</div>
                                <img src="{{ asset('storage/' . $mahasiswa['foto_path']) }}" alt="Foto {{ $mahasiswa['nama_lengkap'] }}" class="current-photo-image">
                                <div class="form-help" style="margin-top: 8px;">
                                    Upload foto baru jika ingin mengganti foto yang ada
                                </div>
                            </div>
                        @endif

                        <div class="file-upload-container">
                            <input
                                type="file"
                                class="file-upload-input @error('foto') is-invalid @enderror"
                                id="foto"
                                name="foto"
                                accept="image/jpeg,image/jpg,image/png"
                            >
                            <label for="foto" class="file-upload-label">
                                <i class="fas fa-upload"></i>
                                <span>Pilih foto atau drag & drop di sini</span>
                            </label>
                            <div class="file-upload-preview" id="fotoPreview">
                                <img id="previewImage" class="file-preview-image" alt="Preview">
                                <button type="button" class="file-remove-btn" id="removePreview">
                                    <i class="fas fa-times"></i> Hapus
                                </button>
                            </div>
                        </div>

                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-help">
                            Format yang didukung: JPEG, JPG, PNG. Maksimal ukuran file 2MB
                        </div>
                    </div>

                    <!-- Informasi Tambahan untuk Edit -->
                    @if(isset($mahasiswa))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Dibuat</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($mahasiswa['created_at'])->format('d/m/Y H:i') }}"
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
                                        value="{{ \Carbon\Carbon::parse($mahasiswa['updated_at'])->format('d/m/Y H:i') }}"
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
                            {{ isset($mahasiswa) ? 'Update Mahasiswa' : 'Simpan Mahasiswa' }}
                        </button>
                        <a href="{{ route('admin.mahasiswa') }}" class="btn btn-secondary">
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
    // File upload preview functionality
    $('#foto').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Harap pilih file JPEG, JPG, atau PNG.');
                $(this).val('');
                return;
            }

            // Validate file size (2MB = 2048KB)
            if (file.size > 2048 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                $(this).val('');
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
                $('#fotoPreview').show();
                $('.file-upload-label span').text(file.name);
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove preview
    $('#removePreview').on('click', function() {
        $('#foto').val('');
        $('#fotoPreview').hide();
        $('.file-upload-label span').text('Pilih foto atau drag & drop di sini');
    });

    // Form validation
    $('#mahasiswaForm').on('submit', function(e) {
        var nim = $('#nim').val().trim();
        var namaLengkap = $('#nama_lengkap').val().trim();
        var alamat = $('#alamat').val().trim();
        var noTelepon = $('#no_telepon').val().trim();
        var isValid = true;

        // Reset previous error states
        $('.form-control, .form-textarea').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Validate NIM
        if (!nim) {
            $('#nim').addClass('is-invalid');
            $('#nim').after('<div class="invalid-feedback">NIM harus diisi.</div>');
            isValid = false;
        } else if (nim.length < 8) {
            $('#nim').addClass('is-invalid');
            $('#nim').after('<div class="invalid-feedback">NIM minimal 8 karakter.</div>');
            isValid = false;
        }

        // Validate nama lengkap
        if (!namaLengkap) {
            $('#nama_lengkap').addClass('is-invalid');
            $('#nama_lengkap').after('<div class="invalid-feedback">Nama lengkap harus diisi.</div>');
            isValid = false;
        } else if (namaLengkap.length < 3) {
            $('#nama_lengkap').addClass('is-invalid');
            $('#nama_lengkap').after('<div class="invalid-feedback">Nama lengkap minimal 3 karakter.</div>');
            isValid = false;
        }

        // Validate alamat
        if (!alamat) {
            $('#alamat').addClass('is-invalid');
            $('#alamat').after('<div class="invalid-feedback">Alamat harus diisi.</div>');
            isValid = false;
        } else if (alamat.length < 10) {
            $('#alamat').addClass('is-invalid');
            $('#alamat').after('<div class="invalid-feedback">Alamat minimal 10 karakter.</div>');
            isValid = false;
        }

        // Validate no telepon
        if (!noTelepon) {
            $('#no_telepon').addClass('is-invalid');
            $('#no_telepon').after('<div class="invalid-feedback">Nomor telepon harus diisi.</div>');
            isValid = false;
        } else if (!isValidPhoneNumber(noTelepon)) {
            $('#no_telepon').addClass('is-invalid');
            $('#no_telepon').after('<div class="invalid-feedback">Format nomor telepon tidak valid.</div>');
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

    // Phone number validation function
    function isValidPhoneNumber(phone) {
        // Indonesian phone number pattern
        var phoneRegex = /^(\+62|62|0)[0-9]{9,13}$/;
        return phoneRegex.test(phone.replace(/\D/g, ''));
    }

    // Remove error state when user interacts with fields
    $('#nim, #nama_lengkap, #alamat, #no_telepon').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Auto format NIM (numbers only)
    $('#nim').on('input', function() {
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

    // Auto format phone number
    $('#no_telepon').on('input', function() {
        var value = $(this).val().replace(/[^0-9+]/g, '');
        $(this).val(value);
    });

    // Character counter for alamat
    $('#alamat').on('input', function() {
        var maxLength = 500;
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;

        var helpText = $(this).siblings('.form-help');
        if (remaining < 50) {
            helpText.html('Sisa karakter: ' + remaining + ' dari ' + maxLength);
            helpText.css('color', remaining < 20 ? '#e74a3b' : '#f6c23e');
        } else {
            helpText.html('Masukkan alamat lengkap termasuk kota dan kode pos (maksimal 500 karakter)');
            helpText.css('color', '#6b7280');
        }
    });

    // Focus on NIM field when page loads
    $('#nim').focus();

    // Handle Enter key navigation
    $('#nim').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#nama_lengkap').focus();
        }
    });

    $('#nama_lengkap').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#alamat').focus();
        }
    });

    $('#no_telepon').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#submitBtn').click();
        }
    });
});

// Show confirmation dialog when leaving page with unsaved changes
$(window).on('beforeunload', function(e) {
    var originalNim = '{{ old('nim', isset($mahasiswa) ? $mahasiswa['nim'] : '') }}';
    var originalNamaLengkap = '{{ old('nama_lengkap', isset($mahasiswa) ? $mahasiswa['nama_lengkap'] : '') }}';
    var originalAlamat = '{{ old('alamat', isset($mahasiswa) ? $mahasiswa['alamat'] : '') }}';
    var originalNoTelepon = '{{ old('no_telepon', isset($mahasiswa) ? $mahasiswa['no_telepon'] : '') }}';

    var currentNim = $('#nim').val();
    var currentNamaLengkap = $('#nama_lengkap').val();
    var currentAlamat = $('#alamat').val();
    var currentNoTelepon = $('#no_telepon').val();

    if (originalNim !== currentNim ||
        originalNamaLengkap !== currentNamaLengkap ||
        originalAlamat !== currentAlamat ||
        originalNoTelepon !== currentNoTelepon ||
        $('#foto').val()) {
        return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
    }
});

// Remove beforeunload when form is submitted
$('#mahasiswaForm').on('submit', function() {
    $(window).off('beforeunload');
});
</script>
@endsection