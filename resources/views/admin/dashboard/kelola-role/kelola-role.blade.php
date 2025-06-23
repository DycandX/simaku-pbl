@extends('layouts.admin-app')

@section('title', 'Kelola Role')

@section('styles')
<style>
    /* Main Content Styling */
    .content {
        background-color: #f5f7fa;
        min-height: 100vh;
    }

    /* Page Header Styling */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #2d3436;
    }

    /* Filter Section */
    .filter-section {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .select-wrapper {
        position: relative;
        min-width: 200px;
    }

    .filter-select {
        width: 100%;
        padding: 10px 35px 10px 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-color: white;
        font-size: 14px;
        color: #6c757d;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-select:focus {
        outline: none;
        border-color: #5e72e4;
    }

    .select-wrapper::after {
        content: '\f107';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6c757d;
    }

    .filter-btn {
        background-color: #5e72e4;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 25px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .filter-btn:hover {
        background-color: #4c63d2;
    }

    /* Search Box */
    .search-wrapper {
        flex: 1;
        max-width: 300px;
        margin-left: auto;
    }

    .search-box {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        background-color: white;
        transition: all 0.2s;
    }

    .search-box:focus {
        outline: none;
        border-color: #5e72e4;
    }

    .search-box::placeholder {
        color: #adb5bd;
    }

    /* Add Role Button */
    .btn-add-role {
        background-color: #5e72e4;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s;
        margin-bottom: 25px;
    }

    .btn-add-role:hover {
        background-color: #4c63d2;
    }

    /* Table Container */
    .table-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table-header {
        padding: 20px 30px;
        border-bottom: 1px solid #e9ecef;
    }

    .table-title {
        font-size: 18px;
        font-weight: 600;
        color: #2d3436;
        margin: 0;
    }

    /* Table Styling */
    .roles-table {
        width: 100%;
        border-collapse: collapse;
    }

    .roles-table thead {
        background-color: #f8f9fa;
    }

    .roles-table th {
        text-align: left;
        padding: 15px 30px;
        font-size: 14px;
        font-weight: 600;
        color: #6c757d;
        border-bottom: 1px solid #e9ecef;
    }

    .roles-table td {
        padding: 15px 30px;
        font-size: 14px;
        color: #495057;
        border-bottom: 1px solid #f1f3f4;
    }

    .roles-table th:last-child,
    .roles-table td:last-child {
        text-align: right;
    }

    .roles-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .roles-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Edit Button */
    .btn-edit-role {
        background-color: #5e72e4;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-edit-role:hover {
        background-color: #4c63d2;
        color: white;
        text-decoration: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }

        .search-wrapper {
            max-width: 100%;
            margin-left: 0;
            margin-top: 10px;
        }

        .select-wrapper {
            width: 100%;
        }

        .roles-table th,
        .roles-table td {
            padding: 12px 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 30px;
        color: #6c757d;
    }

    .empty-state-icon {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .empty-state-text {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .empty-state-subtext {
        font-size: 14px;
        color: #adb5bd;
    }
</style>
@endsection

@section('header', 'Dashboard Kelola Role')

@section('header_button')
<!-- You can add a header button here if needed -->
@endsection

@section('content')
<!-- Filter Section -->
<div class="filter-section">
    <div class="select-wrapper">
        <select class="filter-select" id="roleFilter">
            <option value="">All Role</option>
            <option value="admin">Admin Keuangan</option>
            <option value="staff">Staff Keuangan</option>
            <option value="mahasiswa">Mahasiswa</option>
        </select>
    </div>
    <button class="filter-btn">Filter</button>

    <div class="search-wrapper">
        <input type="text" class="search-box" placeholder="Search" id="searchRole">
    </div>
</div>

<!-- Add Role Button -->
<button class="btn-add-role">
    <i class="fas fa-plus"></i>
    Tambah Role
</button>

<!-- Table Container -->
<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">Semua Pengguna</h3>
    </div>

    <table class="roles-table">
        <thead>
            <tr>
                <th style="width: 80px;">No</th>
                <th>Role Pengguna</th>
                <th style="width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>01</td>
                <td>Admin Keuangan</td>
                <td>
                    <a href="{{ route('admin.kelola-role.edit', ['id' => 1]) }}" class="btn-edit-role">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </td>
            </tr>
            <tr>
                <td>02</td>
                <td>Staff Keuangan</td>
                <td>
                    <a href="{{ route('admin.kelola-role.edit', ['id' => 2]) }}" class="btn-edit-role">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </td>
            </tr>
            <tr>
                <td>03</td>
                <td>Mahasiswa</td>
                <td>
                    <a href="{{ route('admin.kelola-role.edit', ['id' => 3]) }}" class="btn-edit-role">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Filter functionality
    $('.filter-btn').on('click', function() {
        var selectedRole = $('#roleFilter').val();
        filterRoles(selectedRole);
    });

    // Search functionality
    $('#searchRole').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        searchRoles(searchTerm);
    });

    // Add Role button
    $('.btn-add-role').on('click', function() {
        // Redirect to add role page or open modal
        window.location.href = '/admin/kelola-role/create';
    });

    // Filter roles function
    function filterRoles(roleType) {
        $('.roles-table tbody tr').show();

        if (roleType) {
            $('.roles-table tbody tr').each(function() {
                var roleName = $(this).find('td:eq(1)').text().toLowerCase();

                if ((roleType === 'admin' && !roleName.includes('admin')) ||
                    (roleType === 'staff' && !roleName.includes('staff')) ||
                    (roleType === 'mahasiswa' && !roleName.includes('mahasiswa'))) {
                    $(this).hide();
                }
            });
        }
    }

    // Search roles function
    function searchRoles(searchTerm) {
        $('.roles-table tbody tr').each(function() {
            var roleName = $(this).find('td:eq(1)').text().toLowerCase();

            if (roleName.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            $('#searchRole').focus();
        }
    });
});
</script>
@endsection