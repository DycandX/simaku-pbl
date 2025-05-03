<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMAKU')</title>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

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
    </style>

    @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
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
                            <span class="user-name" onclick="window.location.href='/profile'; event.stopPropagation(); return false;">{{ Session::get('username') ?? 'Username' }}</span>
                            <span class="user-role">{{ Session::get('role') ?? 'role' }}</span>
                        </div>
                        <i class="fas fa-chevron-down profile-arrow"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <li class="user-header bg-primary">
                            <img src="{{ asset('assets/Profile.jpeg') }}" class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ Session::get('username') ?? 'Username' }}
                                <small>{{ Session::get('email') ?? 'user@example.com' }}</small>
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
                        <!-- Menu Dashboard dengan dropdown -->
                        <li class="nav-item {{ request()->is('dashboard*') || request()->is('tagihan-ukt*') || request()->is('daftar-ulang*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('dashboard*') || request()->is('tagihan-ukt*') || request()->is('daftar-ulang*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/dashboard" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                                        <i class="fas fa-money-bill nav-icon"></i>
                                        <p>Tagihan UKT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/daftar-ulang" class="nav-link {{ request()->is('daftar-ulang*') ? 'active' : '' }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>Daftar Ulang UKT</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Menu lainnya tetap sama -->
                        <li class="nav-item">
                            <a href="/beasiswa" class="nav-link {{ request()->is('beasiswa*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>Beasiswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/profile" class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/settings" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Settings</p>
                            </a>
                        </li>
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

    <!-- Scripts -->
    <script src="{{ asset('vendor/adminlte/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

    <script>
    $(document).ready(function() {
        // Aktifkan treeview AdminLTE
        $('[data-widget="treeview"]').Treeview('init');

        // Pastikan dashboard selalu aktif jika berada di halaman dashboard atau subhalaman
        if (window.location.pathname === '/dashboard' ||
            window.location.pathname.startsWith('/tagihan-ukt') ||
            window.location.pathname.startsWith('/daftar-ulang')) {
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
    });
    </script>

    @yield('scripts')
</body>
</html>