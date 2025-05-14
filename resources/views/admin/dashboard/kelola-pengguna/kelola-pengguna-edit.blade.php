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
    }

    .btn-back:hover {
        background-color: #2e59d9;
        color: white;
        text-decoration: none;
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

    .form-control[readonly] {
        background-color: #f8f9fc;
        cursor: not-allowed;
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

    /* Helper Text */
    .helper-text {
        font-size: 12px;
        color: #858796;
        margin-top: 5px;
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
<div class="form-container">
    <div class="form-header">
        <h3 class="form-title">Data pengguna</h3>
    </div>

    <div class="form-body">
        <form id="editUserForm" method="POST" action="{{ route('admin.kelola-pengguna.update', $user['id'] ?? 1) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text"
                       class="form-control"
                       id="username"
                       name="username"
                       value="{{ old('username', $user['username'] ?? '') }}"
                       required>
            </div>

            <div class="form-group">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text"
                       class="form-control"
                       id="nama_lengkap"
                       name="nama_lengkap"
                       value="{{ old('nama_lengkap', $user['nama_lengkap'] ?? '') }}"
                       required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Pengguna</label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       value="{{ old('email', $user['email'] ?? '') }}"
                       required>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Role Pengguna</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role', $user['role'] ?? '') == 'admin' ? 'selected' : '' }}>Admin Keuangan</option>
                    <option value="staff" {{ old('role', $user['role'] ?? '') == 'staff' ? 'selected' : '' }}>Staff Keuangan</option>
                    <option value="mahasiswa" {{ old('role', $user['role'] ?? '') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-input-group">
                    <input type="password"
                           class="form-control"
                           id="password"
                           name="password"
                           placeholder="••••••••">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="helper-text">Kosongkan jika tidak ingin mengubah password</div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn-submit">Simpan</button>
            </div>
        </form>
    </div>
                           name="password"
                           placeholder="••••••••">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="helper-text">Kosongkan jika tidak ingin mengubah password</div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn-submit">Simpan</button>
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

    // Form submission
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();

        // Get form data
        const formData = {
            nama: $('#nama').val(),
            email: $('#email').val(),
            role: $('#role').val(),
            password: $('#password').val()
        };

        // Validation
        if (!formData.nama || !formData.email || !formData.role) {
            alert('Mohon lengkapi semua field yang wajib diisi');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            alert('Format email tidak valid');
            return;
        }

        // Simulate form submission
        console.log('Submitting form data:', formData);
        alert('Data pengguna berhasil diperbarui!');

        // In real implementation, you would submit this via AJAX or regular form submission
        // this.submit();
    });

    // Input focus effects
    $('.form-control, .form-select').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
});
</script>
@endsection