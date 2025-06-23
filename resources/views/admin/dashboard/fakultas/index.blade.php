@extends('layouts.admin-app')

@section('title', 'Kelola Fakultas')

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

    /* Fakultas info styling */
    .fakultas-info {
        display: flex;
        flex-direction: column;
    }

    .fakultas-name {
        font-weight: 600;
        color: #2d3748;
    }

    .fakultas-code {
        font-size: 12px;
        color: #718096;
        margin-top: 2px;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-edit {
        background-color: #f6c23e;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 12px;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-edit:hover {
        background-color: #dda20a;
        text-decoration: none;
        color: white;
    }

    .btn-delete {
        background-color: #e74a3b;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 12px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-delete:hover {
        background-color: #c0392b;
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

        .action-buttons {
            flex-direction: column;
            gap: 4px;
        }
    }
</style>
@endsection

@section('header', 'Dashboard Kelola Fakultas')

@section('header_button')
<a href="{{ route('admin.fakultas.create') }}" class="btn-add">
    <i class="fas fa-plus"></i>
    Tambah Fakultas
</a>
@endsection

@section('content')
<form method="GET" action="{{ route('admin.fakultas') }}" id="filterForm">
    <div class="filter-row">
        <div class="filter-left">
            @if(request('search'))
                <a href="{{ route('admin.fakultas') }}" class="btn btn-secondary" style="padding: 8px 15px; text-decoration: none; background-color: #6c757d; color: white; border-radius: 5px; font-size: 14px;">
                    Reset
                </a>
            @endif
        </div>

        <div class="search-wrapper">
            <input type="text" name="search" placeholder="Cari nama fakultas..." class="search-box" id="searchInput" value="{{ request('search') }}">
        </div>
    </div>
</form>

<div class="table-container">
    <div class="card-header">
        <h3 class="card-title">Semua Fakultas</h3>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Fakultas</th>
                    <th style="width: 120px;">Tanggal Dibuat</th>
                    <th style="width: 120px;">Terakhir Diubah</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fakultas as $index => $item)
                    <tr>
                        <td>{{ ($fakultas->firstItem() ?? 0) + $index }}</td>
                        <td>
                            <div class="fakultas-info">
                                <span class="fakultas-name">{{ $item['nama_fakultas'] }}</span>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.fakultas.edit', $item['id']) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn-delete" onclick="confirmDelete({{ $item['id'] }}, '{{ $item['nama_fakultas'] }}')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            @if(request('search'))
                                Tidak ada fakultas yang sesuai dengan pencarian "{{ request('search') }}".
                            @else
                                Tidak ada data fakultas.
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
                @if($fakultas->total() > 0)
                    Showing {{ $fakultas->firstItem() ?? 0 }}-{{ $fakultas->lastItem() ?? 0 }} of {{ $fakultas->total() }}
                    @if(request('search'))
                        (filtered by Search: "{{ request('search') }}")
                    @endif
                @else
                    No fakultas found
                @endif
            </div>
            <div class="page-controls">
                @if ($fakultas->onFirstPage())
                    <button class="page-btn" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <a href="{{ $fakultas->previousPageUrl() }}" class="page-btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach ($fakultas->getUrlRange(1, $fakultas->lastPage()) as $page => $url)
                    @if ($page == $fakultas->currentPage())
                        <button class="page-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($fakultas->hasMorePages())
                    <a href="{{ $fakultas->nextPageUrl() }}" class="page-btn">
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
                <p>Apakah Anda yakin ingin menghapus fakultas <strong id="fakultasName"></strong>?</p>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
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
        var fakultasName = row.find('.fakultas-name').text();
        console.log('Editing fakultas:', fakultasName);
    });

    // Add fakultas button functionality
    $('.btn-add').on('click', function() {
        console.log('Adding new fakultas');
    });

    // Clear search when clicking reset
    $('a[href*="admin.fakultas"]:contains("Reset")').on('click', function() {
        $('#searchInput').val('');
    });
});

// Delete confirmation function
function confirmDelete(id, name) {
    $('#fakultasName').text(name);
    $('#deleteForm').attr('action', '/admin/fakultas/' + id);
    $('#deleteModal').modal('show');
}
</script>
@endsection