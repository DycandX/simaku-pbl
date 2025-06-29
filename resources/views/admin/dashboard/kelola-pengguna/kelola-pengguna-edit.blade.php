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

    /* Enhanced Alert Messages */
    .alert {
        padding: 15px 20px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 14px;
        position: relative;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeaa7;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    /* Error List Styling */
    .error-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .error-list li {
        padding: 8px 0;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .error-list li:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .error-list li::before {
        content: "⚠";
        color: #dc3545;
        font-weight: bold;
        font-size: 16px;
    }

    /* Debug Info Styling */
    .debug-info {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
    }

    .debug-title {
        font-weight: bold;
        color: #495057;
        margin-bottom: 10px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .debug-content {
        background-color: white;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #e9ecef;
        max-height: 200px;
        overflow-y: auto;
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
        position: relative;
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
        background-color: #fff5f5;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.5rem;
        font-size: 0.875em;
        color: #e74a3b;
        background-color: #fff5f5;
        padding: 8px 12px;
        border-radius: 4px;
        border-left: 3px solid #e74a3b;
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
        background-color: #fff5f5;
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

    /* Field Error Highlight */
    .field-error {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
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

        .debug-info {
            font-size: 11px;
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
        <strong>Berhasil!</strong> {{ session('success') }}
    </div>
@endif

<!-- Display Individual Error Messages -->
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan dalam memperbarui data:</strong>
        <ul class="error-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Display Debug Information (only in development) -->
@if(config('app.debug') && session('debug_info'))
    <div class="debug-info">
        <div class="debug-title">Debug Information:</div>
        <div class="debug-content">
            <pre>{{ json_encode(session('debug_info'), JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
@endif

<!-- Display API Error Response -->
@if(session('api_error'))
    <div class="alert alert-warning">
        <strong>Detail Error dari Server:</strong>
        <div style="margin-top: 10px;">
            @if(is_array(session('api_error')))
                <ul class="error-list">
                    @foreach(session('api_error') as $field => $messages)
                        @if(is_array($messages))
                            @foreach($messages as $message)
                                <li><strong>{{ ucfirst($field) }}:</strong> {{ $message }}</li>
                            @endforeach
                        @else
                            <li><strong>{{ ucfirst($field) }}:</strong> {{ $messages }}</li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p>{{ session('api_error') }}</p>
            @endif
        </div>
    </div>
@endif

@if(!isset($user) || empty($user))
    <div class="alert alert-danger">
        <strong>Error:</strong> Data pengguna tidak ditemukan.
        <a href="{{ route('admin.kelola-pengguna') }}" style="color: #721c24; text-decoration: underline;">
            Kembali ke daftar pengguna
        </a>
    </div>
@else
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
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </div>
                @enderror
                <div id="username-error" class="invalid-feedback" style="display: none;"></div>
            </div>

            <div class="form-group">
                <label for="nama_lengkap" class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text"
                       class="form-control @error('nama_lengkap') is-invalid @enderror"
                       id="nama_lengkap"
                       name="nama_lengkap"
                       value="{{ old('nama_lengkap', $user['nama_lengkap'] ?? ($user['mahasiswa']['nama_lengkap'] ?? ($user['staff']['nama_lengkap'] ?? ''))) }}"
                       required>
                @error('nama_lengkap')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </div>
                @enderror
                <div id="nama_lengkap-error" class="invalid-feedback" style="display: none;"></div>
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
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </div>
                @enderror
                <div id="email-error" class="invalid-feedback" style="display: none;"></div>
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
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </div>
                @enderror
                <div id="role-error" class="invalid-feedback" style="display: none;"></div>
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">Status Akun <span style="color: red;">*</span></label>
                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                    <option value="">Pilih Status</option>
                    @php
                        $currentStatus = old('is_active', $user['is_active'] ?? '');
                        $isActive = $currentStatus === true || $currentStatus === 1 || $currentStatus === '1';
                        $isInactive = $currentStatus === false || $currentStatus === 0 || $currentStatus === '0' || $currentStatus === null;
                    @endphp
                    <option value="1" {{ $isActive ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $isInactive ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                <div id="statusPreview" class="status-preview" style="display: none;"></div>
                @error('is_active')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </div>
                @enderror
                <div id="is_active-error" class="invalid-feedback" style="display: none;"></div>
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
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </div>
                @enderror
                <div id="password-error" class="invalid-feedback" style="display: none;"></div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endif
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

    // Enhanced form validation and submission
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();

        // Clear all previous errors
        clearAllErrors();

        // Get form data
        const formData = {
            username: $('#username').val().trim(),
            nama_lengkap: $('#nama_lengkap').val().trim(),
            email: $('#email').val().trim(),
            role: $('#role').val(),
            is_active: $('#is_active').val(),
            password: $('#password').val()
        };

        let errors = [];
        let isValid = true;

        // Detailed validation with specific error messages
        if (!formData.username) {
            addFieldError('username', 'Username wajib diisi');
            errors.push({field: 'username', message: 'Username tidak boleh kosong'});
            isValid = false;
        } else if (formData.username.length < 3) {
            addFieldError('username', 'Username minimal 3 karakter');
            errors.push({field: 'username', message: 'Username terlalu pendek (minimal 3 karakter)'});
            isValid = false;
        } else if (!/^[a-zA-Z0-9_]+$/.test(formData.username)) {
            addFieldError('username', 'Username hanya boleh mengandung huruf, angka, dan underscore');
            errors.push({field: 'username', message: 'Username mengandung karakter tidak valid'});
            isValid = false;
        }

        if (!formData.nama_lengkap) {
            addFieldError('nama_lengkap', 'Nama lengkap wajib diisi');
            errors.push({field: 'nama_lengkap', message: 'Nama lengkap tidak boleh kosong'});
            isValid = false;
        } else if (formData.nama_lengkap.length < 2) {
            addFieldError('nama_lengkap', 'Nama lengkap minimal 2 karakter');
            errors.push({field: 'nama_lengkap', message: 'Nama lengkap terlalu pendek'});
            isValid = false;
        }

        if (!formData.email) {
            addFieldError('email', 'Email wajib diisi');
            errors.push({field: 'email', message: 'Email tidak boleh kosong'});
            isValid = false;
        } else {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(formData.email)) {
                addFieldError('email', 'Format email tidak valid (contoh: user@domain.com)');
                errors.push({field: 'email', message: 'Format email tidak sesuai standar'});
                isValid = false;
            }
        }

        if (!formData.role) {
            addFieldError('role', 'Role pengguna wajib dipilih');
            errors.push({field: 'role', message: 'Silakan pilih role pengguna'});
            isValid = false;
        }

        if (!formData.is_active && formData.is_active !== '0') {
            addFieldError('is_active', 'Status akun wajib dipilih');
            errors.push({field: 'is_active', message: 'Silakan pilih status akun'});
            isValid = false;
        }

        // Password validation (only if filled)
        if (formData.password) {
            if (formData.password.length < 8) {
                addFieldError('password', 'Password minimal 8 karakter');
                errors.push({field: 'password', message: 'Password terlalu pendek (minimal 8 karakter)'});
                isValid = false;
            } else if (!/(?=.*[a-z])/.test(formData.password)) {
                addFieldError('password', 'Password harus mengandung minimal 1 huruf kecil');
                errors.push({field: 'password', message: 'Password perlu huruf kecil'});
                isValid = false;
            } else if (!/(?=.*[A-Z])/.test(formData.password)) {
                addFieldError('password', 'Password harus mengandung minimal 1 huruf besar');
                errors.push({field: 'password', message: 'Password perlu huruf besar'});
                isValid = false;
            } else if (!/(?=.*\d)/.test(formData.password)) {
                addFieldError('password', 'Password harus mengandung minimal 1 angka');
                errors.push({field: 'password', message: 'Password perlu angka'});
                isValid = false;
            }
        }

        if (!isValid) {
            // Show summary of all errors
            showErrorSummary(errors);

            // Scroll to first error
            const firstError = $('.is-invalid').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 300);
                firstError.focus();
            }
            return;
        }

        // Show loading state
        const submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $('.form-container').addClass('loading');

        // Log form data for debugging
        console.log('Form data being submitted:', formData);

        // Submit the actual form
        this.submit();
    });

    // Helper function to clear all errors
    function clearAllErrors() {
        $('.form-control, .form-select').removeClass('is-invalid field-error');
        $('.invalid-feedback').hide();
        $('#error-summary').remove();
    }

    // Helper function to add field error
    function addFieldError(fieldId, message) {
        const field = $('#' + fieldId);
        const errorDiv = $('#' + fieldId + '-error');

        field.addClass('is-invalid field-error');
        errorDiv.html('<i class="fas fa-exclamation-triangle"></i> ' + message).show();

        // Add shake animation
        setTimeout(() => {
            field.removeClass('field-error');
        }, 500);
    }

    // Helper function to show error summary
    function showErrorSummary(errors) {
        const errorHtml = `
            <div id="error-summary" class="alert alert-danger">
                <strong>Mohon periksa kembali data yang Anda masukkan:</strong>
                <ul class="error-list">
                    ${errors.map(error => `<li><strong>${getFieldLabel(error.field)}:</strong> ${error.message}</li>`).join('')}
                </ul>
            </div>
        `;

        // Remove existing error summary
        $('#error-summary').remove();

        // Add new error summary at the top
        $('.form-container').before(errorHtml);

        // Scroll to error summary
        $('html, body').animate({
            scrollTop: $('#error-summary').offset().top - 50
        }, 300);
    }

    // Helper function to get field label
    function getFieldLabel(fieldId) {
        const labels = {
            'username': 'Username',
            'nama_lengkap': 'Nama Lengkap',
            'email': 'Email',
            'role': 'Role',
            'is_active': 'Status Akun',
            'password': 'Password'
        };
        return labels[fieldId] || fieldId;
    }

    // Remove validation errors on input
    $('.form-control, .form-select').on('input change', function() {
        const fieldId = $(this).attr('id');
        $(this).removeClass('is-invalid field-error');
        $('#' + fieldId + '-error').hide();

        // Remove error summary if all fields are valid
        if ($('.is-invalid').length === 0) {
            $('#error-summary').fadeOut();
        }
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

    // Auto-dismiss alerts after 10 seconds
    setTimeout(function() {
        $('.alert').not('.alert-danger').fadeOut('slow');
    }, 10000);
});



</script>
@endsection