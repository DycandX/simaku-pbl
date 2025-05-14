@extends('layouts.admin-app')

@section('title', 'Kelola Pengguna')

@section('styles')
<style>
    /* Filter Row Styling */
    .filter-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 15px;
    }

    .filter-left {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .select-wrapper {
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        min-width: 150px;
    }

    .select-wrapper select {
        border: none;
        background: transparent;
        width: 100%;
        outline: none;
        font-size: 14px;
    }

    .filter-btn {
        background-color: #4e73df;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 20px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s;
    }

    .filter-btn:hover {
        background-color: #2e59d9;
    }

    /* Search Box */
    .search-box {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 8px 15px;
        width: 250px;
        font-size: 14px;
        outline: none;
    }

    .search-box:focus {
        border-color: #4e73df;
    }

    /* Role Badge Styling */
    .role-badge {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
        text-align: center;
        display: inline-block;
        white-space: nowrap;
    }

    .role-admin {
        background-color: rgba(255, 71, 87, 0.2);
        color: #ff4757;
    }

    .role-staff {
        background-color: rgba(46, 213, 115, 0.2);
        color: #2ed573;
    }

    .role-mahasiswa {
        background-color: rgba(54, 162, 235, 0.2);
        color: #36a2eb;
    }

    /* Table Custom Styling */
    .table thead th {
        background-color: #f8f9fc;
        font-weight: 600;
        color: #666;
        font-size: 14px;
        padding: 12px 15px;
        border-bottom: 2px solid #e3e6f0;
    }

    .table tbody td {
        padding: 12px 15px;
        font-size: 14px;
        color: #5a5c69;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8f9fc;
    }

    /* Pagination Styling */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 10px 0;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 14px;
    }

    .page-controls {
        display: flex;
        gap: 5px;
    }

    .page-btn {
        width: 32px;
        height: 32px;
        border: 1px solid #dee2e6;
        background-color: white;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .page-btn:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    /* Pagination Link Styling */
    .page-btn {
        text-decoration: none;
        color: #6c757d;
    }

    .page-btn.active {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }

    a.page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    a.page-btn:hover {
        text-decoration: none;
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .page-btn[disabled] {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-left {
            margin-bottom: 10px;
        }

        .search-box {
            width: 100%;
        }
    }
</style>
@endsection

@section('header', 'Dashboard Kelola Pengguna')

@section('header_button')
<button class="btn-add">
    <i class="fas fa-plus"></i>
    Tambah Pengguna
</button>
@endsection

@section('content')
<div class="filter-row">
    <div class="filter-left">
        <div class="select-wrapper">
            <select id="userRoleFilter">
                <option value="">All User</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="mahasiswa">Mahasiswa</option>
            </select>
        </div>
        <button class="filter-btn">Filter</button>
    </div>

    <div class="search-wrapper">
        <input type="text" placeholder="Search..." class="search-box" id="searchInput">
    </div>
</div>

<div class="table-container">
    <div class="card-header">
        <h3 class="card-title">Semua Pengguna</h3>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Role Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usersData as $index => $user)
                <tr>
                    <td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $user['nama_lengkap'] ?: 'Belum diatur' }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>
                        @if($user['role'] == 'admin')
                            <span class="role-badge role-admin">Admin Keuangan</span>
                        @elseif($user['role'] == 'staff')
                            <span class="role-badge role-staff">Staff Keuangan</span>
                        @elseif($user['role'] == 'mahasiswa')
                            <span class="role-badge role-mahasiswa">Mahasiswa</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.kelola-pengguna.edit', $user['id']) }}" class="btn-edit">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
            </div>
            <div class="page-controls">
                @if ($users->onFirstPage())
                    <button class="page-btn" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="page-btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if ($page == $users->currentPage())
                        <button class="page-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="page-btn">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <button class="page-btn" disabled>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Filter functionality
    $('.filter-btn').on('click', function() {
        var selectedRole = $('#userRoleFilter').val();
        // Implement filter logic here
        console.log('Filtering by role:', selectedRole);
    });

    // Search functionality
    $('#searchInput').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        // Implement search logic here
        console.log('Searching for:', searchTerm);
    });

    // Page navigation
    $('.page-btn').on('click', function() {
        if (!$(this).hasClass('active') && $(this).text()) {
            $('.page-btn').removeClass('active');
            $(this).addClass('active');
        }
    });

    // Edit button
    $('.btn-edit').on('click', function() {
        var row = $(this).closest('tr');
        var username = row.find('td:eq(1)').text();
        console.log('Editing user:', username);
        // Implement edit logic here
    });

    // Add user button
    $('.btn-add').on('click', function() {
        console.log('Adding new user');
        // Implement add user logic here
    });
});
</script>
@endsection