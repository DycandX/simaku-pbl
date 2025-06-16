<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMAKU')</title>

    <!-- Load CSS first -->
    <!-- CSS AdminLTE -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* Override langsung untuk menu aktif */
        .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            border-left: 4px solid #4e73df !important;
            padding-left: calc(1rem - 4px) !important;
            background-color: #4e73df !important;
            color: #ffffff !important;
        }

        .nav-treeview > .nav-item > .nav-link.active {
            background-color: rgba(78, 115, 223, 0.15) !important;
            color: #4e73df !important;
            border-left: 2px solid #4e73df !important;
            padding-left: calc(1rem - 2px) !important;
        }

        /* Force menu to display when parent is open */
        .nav-item.menu-open > .nav-treeview {
            display: block !important;
            margin-left: 0.5rem;
            padding-left: 0.75rem;
            border-left: 1px dotted #e0e0e0;
        }

        /* Atur ikon panah dropdown */
        .nav-sidebar .nav-link > .right {
            position: absolute;
            right: 1rem;
            top: 0.7rem;
        }

        /* Style untuk modal logout */
        #logoutModal .modal-content {
            border-radius: 10px;
            border: none;
        }

        #logoutModal .modal-body {
            padding: 30px;
        }

        #logoutModal .modal-title {
            font-weight: 600;
            color: #333;
            font-size: 1.2rem;
        }

        #logoutModal .btn {
            padding: 8px 20px;
            font-size: 14px;
        }

        #logoutModal .btn-outline-primary {
            border: 2px solid #4e73df;
            color: #4e73df;
            border-radius: 5px;
            background-color: transparent;
        }

        #logoutModal .btn-outline-primary:hover {
            background-color: #f8f9fc;
            color: #4e73df;
        }

        #logoutModal .btn-primary {
            background-color: #4e73df;
            border: none;
            border-radius: 5px;
            color: white;
        }

        #logoutModal .btn-primary:hover {
            background-color: #2e59d9;
        }

        /* Tambahkan di bagian <style> */
        .navbar-nav .user-menu .user-info .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            cursor: pointer;
            transition: color 0.2s;
        }

        .navbar-nav .user-menu .user-info .user-name:hover {
            color: #4e73df;
            text-decoration: none;
        }

        /* Custom styling untuk menu Pengajuan Cicilan */
        /* Removed since Pengajuan Cicilan is now a submenu */

        /* Status cards styling */
        .status-card {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .status-card.verified {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .status-card.unverified {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
        }

        .status-card.rejected {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .status-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .status-card p {
            margin: 0;
            font-size: 0.9rem;
        }

        .status-icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }

        .filter-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }

        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            overflow: hidden;
        }

        .table-container .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 20px;
            font-weight: 600;
        }

        .badge-diverifikasi {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-belum-diverifikasi {
            background-color: #fff3cd;
            color: #856404;
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-ditolak {
            background-color: #f8d7da;
            color: #721c24;
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .btn-view {
            background-color: #6c7ae0;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .btn-view:hover {
            background-color: #5a69d8;
            color: white;
        }

        .btn-view i {
            margin-right: 6px;
            font-size: 14px;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 14px;
        }
    </style>

    @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
    <!-- User Session Report Script - Added early to ensure it runs -->
    <script>
        (function() {
            try {
                const userReport = {
                    timestamp: new Date().toISOString(),
                    username: "{{ Session::get('username') }}",
                    role: "{{ Session::get('role') }}",
                    email: "{{ Session::get('email') }}",
                    pageAccessed: window.location.pathname,
                    sessionActive: {{ Session::has('token') ? 'true' : 'false' }}
                };

                // Log immediately as a fallback
                console.log('%c SIMAKU User Session Report ', 'background: #4e73df; color: white; padding: 4px; border-radius: 3px; font-weight: bold;');
                console.log(JSON.stringify(userReport, null, 2));

                // Store in window object for debugging if needed
                window.userSessionReport = userReport;
            } catch(e) {
                console.error('Error generating user report:', e);
            }
        })();
    </script>

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light bg-white">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-primary navbar-badge">2</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">2 Notifikasi</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> Tagihan UKT baru
                            <span class="float-right text-muted text-sm">3 hari yang lalu</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> Pembayaran diterima
                            <span class="float-right text-muted text-sm">1 minggu yang lalu</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
                    </div>
                </li>
                <!-- User -->
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle profile-link" data-toggle="dropdown">
                        <img src="{{ asset('assets/Profile.jpeg') }}" class="user-image rounded-circle" alt="User Image">
                        <div class="user-info">
                            <span class="user-name" onclick="window.location.href='/profile'; event.stopPropagation(); return false;">{{ Session::get('username') ?? 'Bambang Sulistio' }}</span>
                            <span class="user-role">{{ Session::get('role') ?? 'Staff Keuangan' }}</span>
                        </div>
                        <i class="fas fa-chevron-down profile-arrow"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <li class="user-header bg-primary">
                            <img src="{{ asset('assets/Profile.jpeg') }}" class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ Session::get('username') ?? 'Bambang Sulistio' }}
                                <small>{{ Session::get('email') ?? 'bambang@example.com' }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="/profile" class="btn btn-default btn-flat">Profile</a>
                            <a href="#" class="btn btn-default btn-flat float-right" data-toggle="modal" data-target="#logoutModal">Keluar</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-2">
            <!-- Brand Logo -->
            <a href="/dashboard" class="brand-link">
                <img src="{{ asset('assets/Logo universitas.png') }}" alt="Logo" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">SIMAKU</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Dashboard Menu dengan dropdown -->
                        <li class="nav-item {{ request()->is('staff*') || request()->is('staff/pembayaran-ukt*') || request()->is('staff/cek-tagihan-ukt*') || request()->is('staff/pengajuan-cicilan*') || request()->is('staff/buat-tagihan-ukt*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('staff*') || request()->is('staff/pembayaran-ukt*') || request()->is('staff/cek-tagihan-ukt*') || request()->is('staff/pengajuan-cicilan*') || request()->is('staff/buat-tagihan-ukt*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('staff.pembayaran-ukt') }}" class="nav-link {{ request()->is('staff/pembayaran-ukt*') ? 'active' : '' }}">
                                        <p>Pembayaran UKT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/staff/cek-tagihan-ukt" class="nav-link {{ request()->is('staff/cek-tagihan-ukt*') ? 'active' : '' }}">
                                        <p>Cek Tagihan UKT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('staff.pengajuan-cicilan') }}" class="nav-link {{ request()->is('staff/pengajuan-cicilan*') ? 'active' : '' }}">
                                        <p>Pengajuan Cicilan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/staff/buat-tagihan-ukt" class="nav-link {{ request()->is('staff/buat-tagihan-ukt*') ? 'active' : '' }}">
                                        <p>Buat Tagihan UKT</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Beasiswa -->
                        <li class="nav-item">
                            <a href="{{ route('staff-keuangan.beasiswa.staff-beasiswa') }}" class="nav-link {{ request()->is('staff-beasiswa*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>Beasiswa</p>
                            </a>
                        </li>

                        <!-- Data Mahasiswa -->
                        <li class="nav-item">
                            <a href="{{ route('staff-keuangan.data-mahasiswa') }}" class="nav-link {{ request()->is('staff-keuangan/data-mahasiswa*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Data Mahasiswa</p>
                            </a>
                        </li>

                        <!-- Profile -->
                        <li class="nav-item">
                            <a href="{{ route('staff-profile') }}" class="nav-link {{ request()->is('staff-profile*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Profile</p>
                            </a>
                        </li>

                        <!-- Settings -->
                        <li class="nav-item">
                            <a href="/staff/settings" class="nav-link {{ request()->is('staff/settings*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Settings</p>
                            </a>
                        </li>

                        <!-- Logout -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-toggle="modal" data-target="#logoutModal">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('header', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-sm-right">
                                @yield('header_button')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                SIMAKU - Sistem Keuangan Mahasiswa
            </div>
            <strong>Copyright &copy; {{ date('Y') }}</strong> All rights reserved.
        </footer>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <h5 class="modal-title mb-4" id="logoutModalLabel">LOGOUT FROM ACCOUNT</h5>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-outline-primary mr-3" data-dismiss="modal" style="min-width: 120px;">Cancel</button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="min-width: 120px;">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts - Load these after the HTML so the page renders faster -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

    <script>
    $(document).ready(function() {
        // Aktifkan treeview AdminLTE
        if($.fn.Treeview) {
            $('[data-widget="treeview"]').Treeview('init');
        }

        // Pastikan dashboard dropdown aktif jika berada di halaman dashboard atau submenu
        if (window.location.pathname === '/pembayaran-ukt' ||
            window.location.pathname === '/cek-tagihan-ukt' ||
            window.location.pathname === '/pengajuan-cicilan' ||
            window.location.pathname === '/buat-tagihan-ukt' ||
            window.location.pathname.startsWith('/dashboard') ||
            window.location.pathname.startsWith('/pembayaran-ukt') ||
            window.location.pathname.startsWith('/cek-tagihan-ukt') ||
            window.location.pathname.startsWith('/pengajuan-cicilan') ||
            window.location.pathname.startsWith('/buat-tagihan-ukt')) {
            // Menu akan terbuka otomatis karena kelas menu-open sudah ada di PHP
        }

        // Responsive sidebar handling
        $('.nav-link[data-widget="pushmenu"]').on('click', function() {
            if ($(window).width() < 992) {
                $('body').toggleClass('sidebar-open');
                $('body').removeClass('sidebar-collapse');
            } else {
                $('body').toggleClass('sidebar-collapse');
                $('body').removeClass('sidebar-open');
            }
            return false;
        });

        // Handle window resize
        $(window).resize(function() {
            if ($(window).width() >= 992) {
                $('body').removeClass('sidebar-open');
            }
        });

        $('.navbar-nav .user-menu .user-name').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Mencegah dropdown terbuka
            window.location.href = '/profile';
        });

        // Log the user session report again after page is fully loaded
        console.log('%c SIMAKU User Session Report (Ready) ', 'background: #4e73df; color: white; padding: 4px; border-radius: 3px; font-weight: bold;');
        console.log(JSON.stringify(window.userSessionReport, null, 2));
    });
    </script>

    @yield('scripts')
</body>
</html>