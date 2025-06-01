@extends('layouts.admin-app')

@section('title', 'Edit Pengguna')

@section('styles')
<style>
    /* Main Container Styling */
    .main-content {
        background-color: #f5f7fa;
        min-height: calc(100vh - 60px);
        padding: 30px;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }

    .btn-back {
        background-color: #4e73df;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 20px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s;
        text-decoration: none;
    }

    .btn-back:hover {
        background-color: #2e59d9;
        color: white;
        text-decoration: none;
    }

    /* Alert Messages */
    .alert {
        padding: 12px 16px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 5px;
        font-size: 14px;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    /* Form Container */
    .form-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        padding: 0;
        max-width: 600px;
        margin: 0 auto;
    }

    .form-header {
        background-color: #f8f9fc;
        padding: 20px 30px;
        border-bottom: 1px solid #e3e6f0;
        border-radius: 10px 10px 0 0;
    }

    .form-title {
        font-size: 18px;
        font-weight: 600;
        color: #5a5c69;
        margin: 0;
    }

    .form-body {
        padding: 30px;
    }

    /* Form Group Styling */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #5a5c69;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #d1d3e2;
        border-radius: 5px;
        font-size: 14px;
        transition: all 0.2s;
        background-color: #fff;
    }

    .form-control:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .form-control.is-invalid {
        border-color: #e74a3b;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #e74a3b;
    }

    /* Select Dropdown */
    .form-select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #d1d3e2;
        border-radius: 5px;
        font-size: 14px;
        background-color: white;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 18px;
        padding-right: 45px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .form-select.is-invalid {
        border-color: #e74a3b;
    }

    /* Password Input Group */
    .password-input-group {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #858796;
        font-size: 14px;
        background: transparent;
        border: none;
        padding: 5px;
    }

    .password-toggle:hover {
        color: #5a5c69;
    }

    /* Submit Button */
    .btn-submit {
        background-color: #4e73df;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #2e59d9;
    }

    .btn-submit:active {
        background-color: #224abe;
    }

    .btn-submit:disabled {
        background-color: #858796;
        cursor: not-allowed;
    }

    /* Helper Text */
    .helper-text {
        font-size: 12px;
        color: #858796;
        margin-top: 5px;
    }

    /* Status Badge Preview */
    .status-preview {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 5px;
    }

    .status-preview.active {
        background-color: #f0fff4;
        color: #38a169;
        border: 1px solid #c6f6d5;
    }

    .status-preview.inactive {
        background-color: #fff5f5;
        color: #e53e3e;
        border: 1px solid #fed7d7;
    }

    /* Loading State */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-content {
            padding: 20px 15px;
        }

        .form-container {
            margin: 0 10px;
        }

        .form-body {
            padding: 20px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>
@endsection

@section('header', 'Kelola Pengguna')

@section('header_button')
<a href="{{ route('admin.kelola-pengguna') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i>
    Kembali
</a>
@endsection

@section('content')
<!-- Display Success Messages -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Display Error Messages -->
@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-container">
    <div class="form-header">
        <h3 class="form-title">Edit Data Pengguna</h3>
    </div>

    <div class="form-body">
        <form id="editUserForm" method="POST" action="{{ route('admin.kelola-pengguna.update', $user['id']) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="username" class="form-label">Username <span style="color: red;">*</span></label>
                <input type="text"
                       class="form-control @error('username') is-invalid @enderror"
                       id="username"
                       name="username"
                       value="{{ old('username', $user['username'] ?? '') }}"
                       required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama_lengkap" class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text"
                       class="form-control @error('nama_lengkap') is-invalid @enderror"
                       id="nama_lengkap"
                       name="nama_lengkap"
                       value="{{ old('nama_lengkap', $user['nama_lengkap'] ?? '') }}"
                       required>
                @error('nama_lengkap')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Pengguna <span style="color: red;">*</span></label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email', $user['email'] ?? '') }}"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Role Pengguna <span style="color: red;">*</span></label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role', $user['role'] ?? '') == 'admin' ? 'selected' : '' }}>Admin Keuangan</option>
                    <option value="staff" {{ old('role', $user['role'] ?? '') == 'staff' ? 'selected' : '' }}>Staff Keuangan</option>
                    <option value="mahasiswa" {{ old('role', $user['role'] ?? '') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">Status Akun <span style="color: red;">*</span></label>
                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                    <option value="">Pilih Status</option>
                    <option value="1" {{ old('is_active', $user['is_active'] ?? '') == '1' || old('is_active', $user['is_active'] ?? '') === true ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $user['is_active'] ?? '') === '0' || old('is_active', $user['is_active'] ?? '') === false || old('is_active', $user['is_active'] ?? '') === 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                <div id="statusPreview" class="status-preview" style="display: none;"></div>
                @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-input-group">
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="helper-text">Kosongkan jika tidak ingin mengubah password (minimal 8 karakter jika diisi)</div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const passwordInput = $('#password');
        const icon = $(this).find('i');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Status preview update
    $('#is_active').on('change', function() {
        const statusValue = $(this).val();
        const statusPreview = $('#statusPreview');

        if (statusValue === '1') {
            statusPreview.removeClass('inactive').addClass('active').text('Aktif').show();
        } else if (statusValue === '0') {
            statusPreview.removeClass('active').addClass('inactive').text('Tidak Aktif').show();
        } else {
            statusPreview.hide();
        }
    });

    // Initialize status preview on page load
    $('#is_active').trigger('change');

    // Form validation and submission
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();

        // Remove previous error styles
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Get form data
        const formData = {
            username: $('#username').val().trim(),
            nama_lengkap: $('#nama_lengkap').val().trim(),
            email: $('#email').val().trim(),
            role: $('#role').val(),
            is_active: $('#is_active').val(),
            password: $('#password').val()
        };

        let isValid = true;
        let errorMessages = [];

        // Validation
        if (!formData.username) {
            markFieldInvalid('username', 'Username wajib diisi');
            isValid = false;
        }

        if (!formData.nama_lengkap) {
            markFieldInvalid('nama_lengkap', 'Nama lengkap wajib diisi');
            isValid = false;
        }

        if (!formData.email) {
            markFieldInvalid('email', 'Email wajib diisi');
            isValid = false;
        } else {
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(formData.email)) {
                markFieldInvalid('email', 'Format email tidak valid');
                isValid = false;
            }
        }

        if (!formData.role) {
            markFieldInvalid('role', 'Role pengguna wajib dipilih');
            isValid = false;
        }

        if (!formData.is_active && formData.is_active !== '0') {
            markFieldInvalid('is_active', 'Status akun wajib dipilih');
            isValid = false;
        }

        // Password validation (only if filled)
        if (formData.password && formData.password.length < 8) {
            markFieldInvalid('password', 'Password minimal 8 karakter');
            isValid = false;
        }

        if (!isValid) {
            // Scroll to first error
            const firstError = $('.is-invalid').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 300);
            }
            return;
        }

        // Show loading state
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $('.form-container').addClass('loading');

        // Submit the form
        console.log('Submitting form data:', formData);

        // Submit the actual form
        this.submit();
    });

    // Helper function to mark field as invalid
    function markFieldInvalid(fieldId, message) {
        const field = $('#' + fieldId);
        field.addClass('is-invalid');
        field.after('<div class="invalid-feedback">' + message + '</div>');
    }

    // Remove validation errors on input
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').remove();
    });

    // Input focus effects
    $('.form-control, .form-select').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });

    // Prevent form submission on Enter key (except for submit button)
    $(document).on('keydown', 'input', function(e) {
        if (e.which === 13 && this.type !== 'submit') {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection