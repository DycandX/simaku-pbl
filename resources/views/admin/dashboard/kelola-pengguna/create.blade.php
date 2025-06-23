@extends('layouts.admin-app')

@section('title', 'Tambah Pengguna')

@section('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 20px 30px;
        margin: 0;
    }

    .form-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .form-body {
        padding: 30px;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background-color: #f9fafb;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #4e73df;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .form-control:disabled {
        background-color: #f3f4f6;
        color: #6b7280;
        cursor: not-allowed;
    }

    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        background-color: #f9fafb;
        cursor: pointer;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-select:focus {
        outline: none;
        border-color: #4e73df;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6b7280;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: #4e73df;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
    }

    .btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, #2e59d9 0%, #1e3a8a 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        text-decoration: none;
        color: white;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-danger {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
    }

    .alert-success {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #16a34a;
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .form-control.is-invalid {
        border-color: #dc2626;
        background-color: #fef2f2;
    }

    .form-select.is-invalid {
        border-color: #dc2626;
        background-color: #fef2f2;
    }

    .required {
        color: #dc2626;
    }

    .form-help {
        font-size: 12px;
        color: #6b7280;
        margin-top: 5px;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-spinner {
        background: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .person-selection {
        display: none;
    }

    .info-display {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
    }

    .info-display h6 {
        margin: 0 0 10px 0;
        color: #495057;
        font-weight: 600;
    }

    .info-display p {
        margin: 5px 0;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .form-container {
            margin: 20px;
        }

        .form-body {
            padding: 20px;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('header', 'Tambah Pengguna Baru')

@section('header_button')
<a href="{{ route('admin.kelola-pengguna') }}" class="btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Kembali ke Daftar
</a>
@endsection

@section('content')
<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-user-plus"></i> Tambah Pengguna Baru</h2>
    </div>

    <div class="form-body">
        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Display success message --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.kelola-pengguna.store') }}" method="POST" id="createUserForm">
            @csrf
            <input type="hidden" name="selected_name" id="hiddenSelectedName">
            <input type="hidden" name="selected_id_value" id="hiddenSelectedId">
            {{-- Role Selection --}}
            <div class="form-group">
                <label for="role">Role Pengguna <span class="required">*</span></label>
                <select class="form-select @error('role') is-invalid @enderror"
                        id="role"
                        name="role"
                        required>
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-help">Pilih role sesuai dengan akses yang diinginkan</div>
            </div>

            {{-- Person Selection for Staff/Mahasiswa --}}
            <div class="form-group person-selection" id="personSelection">
                <label for="person_id">Pilih <span id="personTypeLabel">Orang</span> <span class="required">*</span></label>
                <select class="form-select @error('person_id') is-invalid @enderror"
                        id="person_id"
                        name="person_id">
                    <option value="">Pilih <span id="personTypeOption">Orang</span></option>
                </select>
                @error('person_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-help">Pilih orang yang akan dibuatkan akun pengguna</div>
            </div>

            {{-- Selected Person Info Display --}}
            <div class="info-display" id="personInfo" style="display: none;">
                <h6>Informasi Terpilih:</h6>
                <p><strong>Nama:</strong> <span id="selectedName">-</span></p>
                <p><strong><span id="selectedIdType">ID</span>:</strong> <span id="selectedId">-</span></p>
            </div>

            {{-- Username (auto-filled or manual for admin) --}}
            <div class="form-group">
                <label for="username">Username <span class="required">*</span></label>
                <input type="text"
                       class="form-control @error('username') is-invalid @enderror"
                       id="username"
                       name="username"
                       value="{{ old('username') }}"
                       placeholder="Username akan otomatis terisi"
                       required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-help" id="usernameHelp">Username akan otomatis terisi dari NIM/NIP</div>
            </div>

            {{-- Email and Status --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Masukkan alamat email"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-help">Email harus valid dan akan digunakan untuk login</div>
                </div>

                <div class="form-group">
                    <label for="is_active">Status Akun <span class="required">*</span></label>
                    <select class="form-select @error('is_active') is-invalid @enderror"
                            id="is_active"
                            name="is_active"
                            required>
                        <option value="">Pilih Status</option>
                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <div class="password-wrapper">
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="Masukkan password"
                           required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-help">Password minimal 8 karakter</div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                <div class="password-wrapper">
                    <input type="password"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Ulangi password"
                           required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation')"></i>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-help">Ulangi password untuk konfirmasi</div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.kelola-pengguna') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
        <i class="fas fa-spinner fa-spin fa-2x"></i>
        <p>Menyimpan data pengguna...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Data from controller
    const mahasiswaData = @json($mahasiswaData);
    const staffData = @json($staffData);

    console.log('Mahasiswa Data:', mahasiswaData); // Debug log
    console.log('Staff Data:', staffData); // Debug log

    // Password toggle functionality
    window.togglePassword = function(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling;

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };

    // Handle role change
    $('#role').on('change', function() {
        const selectedRole = $(this).val();
        const personSelection = $('#personSelection');
        const personSelect = $('#person_id');
        const personInfo = $('#personInfo');
        const usernameField = $('#username');
        const usernameHelp = $('#usernameHelp');

        console.log('Role changed to:', selectedRole); // Debug log

        // Reset
        personSelect.empty().append('<option value="">Pilih Orang</option>');
        personInfo.hide();
        usernameField.val('').prop('disabled', false);

        if (selectedRole === 'admin') {
            // Admin - hide person selection, enable manual username
            personSelection.hide();
            personSelect.prop('required', false);
            usernameField.attr('placeholder', 'Masukkan username admin');
            usernameHelp.text('Username harus unik dan hanya boleh mengandung huruf, angka, dan underscore');
        } else if (selectedRole === 'mahasiswa') {
            // Mahasiswa - show mahasiswa selection
            personSelection.show();
            personSelect.prop('required', true);
            $('#personTypeLabel').text('Mahasiswa');

            console.log('Loading mahasiswa data, count:', mahasiswaData.length); // Debug log

            // Check if mahasiswaData is an array and has data
            if (Array.isArray(mahasiswaData) && mahasiswaData.length > 0) {
                mahasiswaData.forEach(function(mahasiswa) {
                    console.log('Adding mahasiswa:', mahasiswa); // Debug log
                    personSelect.append(`<option value="${mahasiswa.id}">${mahasiswa.nim} - ${mahasiswa.nama_lengkap}</option>`);
                });
            } else {
                personSelect.append('<option value="">Tidak ada data mahasiswa tersedia</option>');
                console.log('No mahasiswa data available'); // Debug log
            }

            usernameField.attr('placeholder', 'Username akan otomatis terisi dari NIM');
            usernameHelp.text('Username akan otomatis terisi dari NIM mahasiswa yang dipilih');
        } else if (selectedRole === 'staff') {
            // Staff - show staff selection
            personSelection.show();
            personSelect.prop('required', true);
            $('#personTypeLabel').text('Staff');

            console.log('Loading staff data, count:', staffData.length); // Debug log

            // Check if staffData is an array and has data
            if (Array.isArray(staffData) && staffData.length > 0) {
                staffData.forEach(function(staff) {
                    console.log('Adding staff:', staff); // Debug log
                    personSelect.append(`<option value="${staff.id}">${staff.nip} - ${staff.nama_lengkap}</option>`);
                });
            } else {
                personSelect.append('<option value="">Tidak ada data staff tersedia</option>');
                console.log('No staff data available'); // Debug log
            }

            usernameField.attr('placeholder', 'Username akan otomatis terisi dari NIP');
            usernameHelp.text('Username akan otomatis terisi dari NIP staff yang dipilih');
        } else {
            // No role selected
            personSelection.hide();
            personSelect.prop('required', false);
            usernameField.attr('placeholder', 'Pilih role terlebih dahulu');
            usernameHelp.text('Pilih role terlebih dahulu');
        }
    });

    // Handle person selection change
    $('#person_id').on('change', function() {
        const selectedRole = $('#role').val();
        const selectedPersonId = $(this).val();
        const personInfo = $('#personInfo');
        const usernameField = $('#username');

        console.log('Person selected:', selectedPersonId, 'for role:', selectedRole); // Debug log

        if (!selectedPersonId) {
            personInfo.hide();
            usernameField.val('').prop('disabled', false);
            return;
        }

        let selectedPerson = null;
        let username = '';
        let idType = '';

        if (selectedRole === 'mahasiswa') {
            selectedPerson = mahasiswaData.find(m => m.id == selectedPersonId);
            username = selectedPerson ? selectedPerson.nim : '';
            idType = 'NIM';
            console.log('Selected mahasiswa:', selectedPerson); // Debug log
        } else if (selectedRole === 'staff') {
            selectedPerson = staffData.find(s => s.id == selectedPersonId);
            username = selectedPerson ? selectedPerson.nip : '';
            idType = 'NIP';
            console.log('Selected staff:', selectedPerson); // Debug log
        }

        if (selectedPerson) {
            // Update info display
            $('#selectedName').text(selectedPerson.nama_lengkap);
            $('#selectedId').text(username);
            $('#selectedIdType').text(idType);
            $('#hiddenSelectedName').val(selectedPerson.nama_lengkap);
            $('#hiddenSelectedId').val(username);
            personInfo.show();
            // Set hidden inputs

            // Set username and disable field
            usernameField.val(username).prop('disabled', true);
            console.log('Username set to:', username); // Debug log
            console.log('Hidden name:', $('#hiddenSelectedName').val());
            console.log('Hidden id:', $('#hiddenSelectedId').val());
        } else {
            console.log('Selected person not found in data'); // Debug log
            personInfo.hide();
            usernameField.val('').prop('disabled', false);
        }
    });

    // Form validation and submission
    $('#createUserForm').on('submit', function(e) {
        // Clear previous validations
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        let isValid = true;
        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();

        // Password confirmation validation
        if (password !== confirmPassword) {
            e.preventDefault();
            $('#password_confirmation').addClass('is-invalid');
            $('#password_confirmation').after('<div class="invalid-feedback">Password dan konfirmasi password tidak sama</div>');
            isValid = false;
        }

        // Password length validation
        if (password.length < 8) {
            e.preventDefault();
            $('#password').addClass('is-invalid');
            if (!$('#password').next('.invalid-feedback').length) {
                $('#password').after('<div class="invalid-feedback">Password minimal 8 karakter</div>');
            }
            isValid = false;
        }

        // Username validation for admin (no spaces, special chars)
        const role = $('#role').val();
        if (role === 'admin') {
            const username = $('#username').val();
            const usernameRegex = /^[a-zA-Z0-9_]+$/;
            if (!usernameRegex.test(username)) {
                e.preventDefault();
                $('#username').addClass('is-invalid');
                $('#username').after('<div class="invalid-feedback">Username hanya boleh mengandung huruf, angka, dan underscore</div>');
                isValid = false;
            }
        }

        // Required field validation
        $('.form-control[required], .form-select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">Field ini wajib diisi</div>');
                isValid = false;
            }
        });

        if (!isValid) {
            // Scroll to first error
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 500);
            return false;
        }

        // Show loading state
        $('#loadingOverlay').show();
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    });

    // Real-time password confirmation validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();

        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').remove();

        if (confirmPassword && password !== confirmPassword) {
            $(this).addClass('is-invalid');
            $(this).after('<div class="invalid-feedback">Password tidak sama</div>');
        }
    });

    // Username validation for admin (no spaces)
    $('#username').on('input', function() {
        const role = $('#role').val();
        if (role === 'admin') {
            let value = $(this).val();
            // Remove invalid characters
            value = value.replace(/[^a-zA-Z0-9_]/g, '');
            $(this).val(value);
        }

        // Remove validation errors when typing
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').remove();
    });

    // Remove validation errors when user starts typing
    $('.form-control, .form-select').on('input change', function() {
        if ($(this).val()) {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });

    // Form field animations
    $('.form-control, .form-select').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });

    // Restore old values on page load (for validation errors)
    @if(old('role'))
        $('#role').trigger('change');
        @if(old('person_id'))
            setTimeout(function() {
                $('#person_id').val('{{ old('person_id') }}').trigger('change');
            }, 100);
        @endif
    @endif

    // Hide loading overlay if there are validation errors on page load
    @if ($errors->any())
        $('#loadingOverlay').hide();
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Pengguna');
    @endif
});
</script>
@endsection