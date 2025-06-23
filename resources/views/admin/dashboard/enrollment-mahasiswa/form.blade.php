@extends('layouts.admin-app')

@section('title', isset($enrollment) ? 'Edit Enrollment Mahasiswa' : 'Tambah Enrollment Mahasiswa')

@section('styles')
<style>
    .form-container {
        background-color: white;
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
        font-size: 1.5rem;
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
        min-width: 0;
    }

    .form-group.full-width {
        flex: 1 1 100%;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-weight: 500;
        font-size: 14px;
    }

    .form-label.required::after {
        content: ' *';
        color: #e74c3c;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        background-color: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        background-color: white;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
        appearance: none;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-select:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .error-message {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .form-actions {
        padding: 20px 30px;
        background-color: #f8f9fc;
        border-top: 1px solid #e3e6f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }

    .btn-primary {
        background-color: #4e73df;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        text-decoration: none;
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #545b62;
        text-decoration: none;
        color: white;
    }

    .btn-danger {
        background-color: #e74a3b;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c0392b;
        text-decoration: none;
        color: white;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        border: 1px solid transparent;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .alert-success {
        background-color: #d1eddf;
        border-color: #c3e6cb;
        color: #155724;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-spinner {
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #4e73df;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .form-body {
            padding: 20px;
        }

        .form-actions {
            padding: 15px 20px;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Select2 styling if needed */
    .select2-container .select2-selection--single {
        height: 48px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 46px;
        padding-left: 16px;
    }

    .select2-container .select2-selection--single .select2-selection__arrow {
        height: 46px;
        right: 16px;
    }
</style>
@endsection

@section('header')
{{ isset($enrollment) ? 'Edit Enrollment Mahasiswa' : 'Tambah Enrollment Mahasiswa' }}
@endsection

@section('header_button')
<a href="{{ route('admin.enrollment-mahasiswa') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Kembali
</a>
@endsection

@section('content')
<div class="form-container">
    <div class="form-header">
        <h2 class="form-title">
            <i class="fas fa-{{ isset($enrollment) ? 'edit' : 'plus' }}"></i>
            {{ isset($enrollment) ? 'Edit Enrollment Mahasiswa' : 'Tambah Enrollment Mahasiswa' }}
        </h2>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin: 20px 30px 0;">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin: 10px 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success" style="margin: 20px 30px 0;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ isset($enrollment) ? route('admin.enrollment-mahasiswa.update', $enrollment['id']) : route('admin.enrollment-mahasiswa.store') }}" id="enrollmentForm">
        @csrf
        @if(isset($enrollment))
            @method('PUT')
        @endif

        <div class="form-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="id_mahasiswa" class="form-label required">Mahasiswa</label>
                    <select name="id_mahasiswa" id="id_mahasiswa" class="form-select" required>
                        <option value="">Pilih Mahasiswa</option>
                        @foreach($mahasiswaList as $mahasiswa)
                            <option value="{{ $mahasiswa['id'] }}"
                                {{ (isset($enrollment) && $enrollment['id_mahasiswa'] == $mahasiswa['id']) || old('id_mahasiswa') == $mahasiswa['id'] ? 'selected' : '' }}>
                                {{ $mahasiswa['nama_lengkap'] }} - {{ $mahasiswa['nim'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_mahasiswa')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="id_program_studi" class="form-label required">Program Studi</label>
                    <select name="id_program_studi" id="id_program_studi" class="form-select" required>
                        <option value="">Pilih Program Studi</option>
                        @foreach($prodiList as $prodi)
                            <option value="{{ $prodi['id'] }}"
                                {{ (isset($enrollment) && $enrollment['id_program_studi'] == $prodi['id']) || old('id_program_studi') == $prodi['id'] ? 'selected' : '' }}>
                                {{ $prodi['nama_prodi'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_program_studi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="id_kelas" class="form-label required">Kelas</label>
                    <select name="id_kelas" id="id_kelas" class="form-select" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas['id'] }}"
                                {{ (isset($enrollment) && $enrollment['id_kelas'] == $kelas['id']) || old('id_kelas') == $kelas['id'] ? 'selected' : '' }}>
                                {{ $kelas['nama_kelas'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kelas')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="id_tingkat" class="form-label required">Tingkat</label>
                    <select name="id_tingkat" id="id_tingkat" class="form-select" required>
                        <option value="">Pilih Tingkat</option>
                        @foreach($tingkatList as $tingkat)
                            <option value="{{ $tingkat['id'] }}"
                                {{ (isset($enrollment) && $enrollment['id_tingkat'] == $tingkat['id']) || old('id_tingkat') == $tingkat['id'] ? 'selected' : '' }}>
                                {{ $tingkat['nama_tingkat'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_tingkat')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="id_golongan_ukt" class="form-label required">Golongan UKT</label>
                    <select name="id_golongan_ukt" id="id_golongan_ukt" class="form-select" required>
                        <option value="">Pilih Golongan UKT</option>
                        @foreach($golonganUktList as $ukt)
                            <option value="{{ $ukt['id'] }}"
                                {{ (isset($enrollment) && $enrollment['id_golongan_ukt'] == $ukt['id']) || old('id_golongan_ukt') == $ukt['id'] ? 'selected' : '' }}>
                                Level {{ $ukt['level'] }} - Rp {{ number_format($ukt['nominal'], 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_golongan_ukt')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="id_tahun_akademik" class="form-label required">Tahun Akademik</label>
                    <select name="id_tahun_akademik" id="id_tahun_akademik" class="form-select" required>
                        <option value="">Pilih Tahun Akademik</option>
                        @foreach($tahunAkademikList as $tahunAkademik)
                            <option value="{{ $tahunAkademik['id'] }}"
                                {{ (isset($enrollment) && $enrollment['id_tahun_akademik'] == $tahunAkademik['id']) || old('id_tahun_akademik') == $tahunAkademik['id'] ? 'selected' : '' }}>
                                {{ $tahunAkademik['tahun_akademik'] }} - {{ $tahunAkademik['semester'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_tahun_akademik')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <div>
                <a href="{{ route('admin.enrollment-mahasiswa') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
            <div style="display: flex; gap: 10px;">
                @if(isset($enrollment))
                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $enrollment['id'] }}, '{{ $enrollment['nama_mahasiswa'] }}')">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                @endif
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i>
                    {{ isset($enrollment) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <div>Menyimpan data...</div>
    </div>
</div>

@if(isset($enrollment))
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus enrollment mahasiswa <strong id="enrollmentName"></strong>?</p>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="{{ route('admin.enrollment-mahasiswa.destroy', $enrollment['id']) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form submission with loading state
    $('#enrollmentForm').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var submitBtn = $('#submitBtn');
        var originalText = submitBtn.html();

        // Show loading state
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $('#loadingOverlay').show();

        // Clear previous errors
        $('.error-message').remove();
        $('.form-control, .form-select').removeClass('is-invalid');

        // Submit form via AJAX
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                // Hide loading
                $('#loadingOverlay').hide();

                // Redirect to index page
                window.location.href = "{{ route('admin.enrollment-mahasiswa') }}";
            },
            error: function(xhr, status, error) {
                // Hide loading
                $('#loadingOverlay').hide();

                // Reset button state
                submitBtn.prop('disabled', false);
                submitBtn.html(originalText);

                // Handle validation errors
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        var input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        input.after('<span class="error-message">' + errors[field][0] + '</span>');
                    }
                } else {
                    // Show general error
                    var errorMessage = xhr.responseJSON.message || 'Terjadi kesalahan saat menyimpan data.';
                    alert(errorMessage);
                }
            }
        });
    });

    // Enhanced form validation
    $('.form-select, .form-control').on('change blur', function() {
        var input = $(this);
        var value = input.val();

        // Remove previous error styling
        input.removeClass('is-invalid');
        input.next('.error-message').remove();

        // Check if required field is empty
        if (input.prop('required') && !value) {
            input.addClass('is-invalid');
            input.after('<span class="error-message">Field ini wajib diisi.</span>');
        }
    });

    // Auto-populate program studi based on mahasiswa selection
    $('#id_mahasiswa').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var mahasiswaId = $(this).val();

        if (mahasiswaId) {
            // You can add logic here to auto-populate related fields
            // based on the selected mahasiswa if needed
            console.log('Mahasiswa selected:', mahasiswaId);
        }
    });

    // Validation for duplicate enrollment
    function validateEnrollment() {
        var mahasiswaId = $('#id_mahasiswa').val();
        var tahunAkademikId = $('#id_tahun_akademik').val();

        if (mahasiswaId && tahunAkademikId) {
            // Here you could add AJAX call to check for duplicates
            // if your API supports it
        }
    }

    $('#id_mahasiswa, #id_tahun_akademik').on('change', validateEnrollment);
});

@if(isset($enrollment))
// Delete confirmation function
function confirmDelete(id, name) {
    $('#enrollmentName').text(name);
    $('#deleteModal').modal('show');
}

// Handle delete form submission
$('#deleteForm').on('submit', function(e) {
    e.preventDefault();

    var form = $(this);
    var submitBtn = form.find('button[type="submit"]');
    var originalText = submitBtn.html();

    // Show loading state
    submitBtn.prop('disabled', true);
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menghapus...');

    // Submit form
    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        success: function(response) {
            $('#deleteModal').modal('hide');

            // Redirect to index page
            window.location.href = "{{ route('admin.enrollment-mahasiswa') }}";
        },
        error: function(xhr, status, error) {
            // Reset button state
            submitBtn.prop('disabled', false);
            submitBtn.html(originalText);

            // Show error message
            var errorMessage = xhr.responseJSON.message || 'Terjadi kesalahan saat menghapus data.';
            alert(errorMessage);
        }
    });
});
@endif

// Prevent form submission on Enter key in select fields
$('.form-select').on('keypress', function(e) {
    if (e.which === 13) {
        e.preventDefault();
        return false;
    }
});

// Enhanced UX: Show loading when navigating back
$('a[href*="enrollment-mahasiswa"]:not([href*="create"]):not([href*="edit"])').on('click', function() {
    $('#loadingOverlay').show();
});
</script>
@endsection