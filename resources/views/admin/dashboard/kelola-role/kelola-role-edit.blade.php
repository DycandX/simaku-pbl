@extends('layouts.admin-app')

@section('title', 'Edit Role')

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
        background-color: #6c757d;
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
        background-color: #5a6268;
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

    /* Textarea */
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
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

    /* Alert messages */
    .alert {
        padding: 12px 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
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

@section('header', 'Edit Role')

@section('header_button')
<a href="{{ route('admin.kelola-role') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i>
    Kembali
</a>
@endsection

@section('content')
<div class="form-container">
    <div class="form-header">
        <h3 class="form-title">Data Role</h3>
    </div>

    <div class="form-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="editRoleForm" method="POST" action="{{ route('admin.kelola-role.update', $role['id']) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="key" class="form-label">Role Key</label>
                <input type="text"
                       class="form-control"
                       id="key"
                       name="key"
                       value="{{ $role['key'] }}"
                       readonly>
                <div class="helper-text">Role key tidak dapat diubah</div>
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Nama Role</label>
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       value="{{ old('name', $role['name']) }}"
                       required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control"
                          id="description"
                          name="description"
                          rows="3">{{ old('description', $role['description'] ?? '') }}</textarea>
                <div class="helper-text">Jelaskan fungsi dan hak akses role ini</div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form submission handler
    $('#editRoleForm').on('submit', function(e) {
        e.preventDefault();

        // Get form data
        const formData = {
            name: $('#name').val(),
            description: $('#description').val()
        };

        // Basic validation
        if (!formData.name) {
            alert('Nama role wajib diisi');
            return;
        }

        // Submit form
        this.submit();
    });

    // Input focus effects
    $('.form-control').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
});
</script>
@endsection