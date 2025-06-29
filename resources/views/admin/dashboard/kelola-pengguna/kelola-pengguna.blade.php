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

    /* Role Badge Styling - Updated */
    .role-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        text-align: center;
        display: inline-block;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background-color: #fff5f5;
        color: #c53030;
        border: 1px solid #fed7d7;
    }

    .role-staff {
        background-color: #f0fff4;
        color: #38a169;
        border: 1px solid #c6f6d5;
    }

    .role-mahasiswa {
        background-color: #ebf8ff;
        color: #3182ce;
        border: 1px solid #bee3f8;
    }

    /* Status Badge Styling - Updated (Less Bold) */
    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        text-align: center;
        display: inline-block;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background-color: #f0fff4;
        color: #38a169;
        border: 1px solid #c6f6d5;
    }

    .status-inactive {
        background-color: #fff5f5;
        color: #e53e3e;
        border: 1px solid #fed7d7;
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

    /* User info styling */
    .user-info {
        display: flex;
        flex-direction: column;
    }

    .username {
        font-weight: 600;
        color: #2d3748;
    }

    .nama-lengkap {
        font-size: 12px;
        color: #718096;
        margin-top: 2px;
    }

    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-left {
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .search-box {
            width: 100%;
        }
    }
</style>
@endsection

@section('header', 'Dashboard Kelola Pengguna')

@section('header_button')
<a href="{{ route('admin.kelola-pengguna.create') }}" class="btn-add">
    <i class="fas fa-plus"></i>
    Tambah Pengguna
</a>
@endsection

@section('content')
<form method="GET" action="{{ route('admin.kelola-pengguna') }}" id="filterForm">
    <div class="filter-row">
        <div class="filter-left">
            <div class="select-wrapper">
                <select name="role_filter" id="userRoleFilter">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role_filter') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="staff" {{ request('role_filter') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="mahasiswa" {{ request('role_filter') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
            </div>

            <div class="select-wrapper">
                <select name="status_filter" id="userStatusFilter">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <button type="submit" class="filter-btn">Filter</button>
            @if(request('role_filter') || request('status_filter') || request('search'))
                <a href="{{ route('admin.kelola-pengguna') }}" class="btn btn-secondary" style="padding: 8px 15px; margin-left: 10px; text-decoration: none; background-color: #6c757d; color: white; border-radius: 5px; font-size: 14px;">
                    Reset
                </a>
            @endif
        </div>

        <div class="search-wrapper">
            <input type="text" name="search" placeholder="Search username, email..." class="search-box" id="searchInput" value="{{ request('search') }}">
        </div>
    </div>
</form>

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
                    <th>Status Akun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td>{{ ($users->firstItem() ?? 0) + $index }}</td>
                        <td>
                            <div class="user-info">
                                <span class="username">{{ $user['username'] }}</span>
                                @php
                                    $namaLengkap = '';
                                    if ($user['role'] === 'mahasiswa' && isset($user['mahasiswa']['nama_lengkap'])) {
                                        $namaLengkap = $user['mahasiswa']['nama_lengkap'];
                                    } elseif ($user['role'] === 'staff' && isset($user['staff']['nama_lengkap'])) {
                                        $namaLengkap = $user['staff']['nama_lengkap'];
                                    }
                                @endphp
                                @if($namaLengkap)
                                    <span class="nama-lengkap">{{ $namaLengkap }}</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user['email'] }}</td>

                        <!-- Role badge -->
                        <td>
                            @switch($user['role'])
                                @case('admin')
                                    <span class="role-badge role-admin">Admin</span>
                                    @break
                                @case('staff')
                                    <span class="role-badge role-staff">Staff</span>
                                    @break
                                @case('mahasiswa')
                                    <span class="role-badge role-mahasiswa">Mahasiswa</span>
                                    @break
                                @default
                                    <span class="role-badge">{{ ucfirst($user['role']) }}</span>
                            @endswitch
                        </td>

                        <!-- Status badge -->
                        <td>
                            @if($user['is_active'])
                                <span class="status-badge status-active">Aktif</span>
                            @else
                                <span class="status-badge status-inactive">Tidak Aktif</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.kelola-pengguna.edit', $user['id']) }}" class="btn-edit">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            @if(request('role_filter') || request('status_filter') || request('search'))
                                Tidak ada pengguna yang sesuai dengan filter.
                            @else
                                Tidak ada data pengguna.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-wrapper">
            <div class="pagination-info">
                @if($users->total() > 0)
                    Showing {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                    @php
                        $filters = [];
                        if(request('role_filter')) $filters[] = 'Role: ' . ucfirst(request('role_filter'));
                        if(request('status_filter')) $filters[] = 'Status: ' . (request('status_filter') == 'active' ? 'Aktif' : 'Tidak Aktif');
                        if(request('search')) $filters[] = 'Search: "' . request('search') . '"';
                    @endphp
                    @if(count($filters) > 0)
                        (filtered by {{ implode(', ', $filters) }})
                    @endif
                @else
                    No users found
                @endif
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
    // Auto-submit form when filter changes
    $('#userRoleFilter, #userStatusFilter').on('change', function() {
        $('#filterForm').submit();
    });

    // Search functionality with debounce
    let searchTimeout;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500); // 500ms delay for better UX
    });

    // Handle Enter key for search
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            $('#filterForm').submit();
        }
    });

    // Edit button functionality
    $('.btn-edit').on('click', function(e) {
        var row = $(this).closest('tr');
        var username = row.find('td:eq(1)').text();
        console.log('Editing user:', username);
    });

    // Add user button functionality
    $('.btn-add').on('click', function() {
        console.log('Adding new user');
    });

    // Clear search when clicking reset
    $('a[href*="admin.kelola-pengguna"]:contains("Reset")').on('click', function() {
        $('#searchInput').val('');
        $('#userRoleFilter').val('');
        $('#userStatusFilter').val('');
    });
});
</script>
@endsection