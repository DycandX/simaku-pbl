<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMAKU - Admin')</title>

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

        /* Hide menu when parent is closed */
        .nav-item:not(.menu-open) > .nav-treeview {
            display: none !important;
        }

        /* Atur ikon panah dropdown */
        .nav-sidebar .nav-link > .right {
            position: absolute;
            right: 1rem;
            top: 0.7rem;
            transition: transform 0.3s ease;
        }

        /* Rotate arrow when menu is open */
        .nav-item.menu-open > .nav-link > .right {
            transform: rotate(90deg);
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

        /* User info styling */
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

        /* Table styling */
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

        /* Button styling */
        .btn-edit {
            background-color: #4e73df;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
        }

        .btn-edit:hover {
            background-color: #2e59d9;
            color: white;
        }

        .btn-add {
            background-color: #4e73df;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .btn-add:hover {
            background-color: #2e59d9;
            color: white;
        }

        .btn-add i {
            margin-right: 6px;
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
                            <i class="fas fa-envelope mr-2"></i> Update sistem baru
                            <span class="float-right text-muted text-sm">3 hari yang lalu</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> Laporan bulanan siap
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
                            <span class="user-name" onclick="window.location.href='/admin/profile'; event.stopPropagation(); return false;">{{ Session::get('username') ?? 'Afriyandi Samuel' }}</span>
                            <span class="user-role">{{ Session::get('role') ?? 'Admin Keuangan' }}</span>
                        </div>
                        <i class="fas fa-chevron-down profile-arrow"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <li class="user-header bg-primary">
                            <img src="{{ asset('assets/Profile.jpeg') }}" class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ Session::get('username') ?? 'Afriyandi Samuel' }}
                                <small>{{ Session::get('email') ?? 'afriyandi@example.com' }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="/admin/profile" class="btn btn-default btn-flat">Profile</a>
                            <a href="#" class="btn btn-default btn-flat float-right" data-toggle="modal" data-target="#logoutModal">Keluar</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-2">
            <!-- Brand Logo -->
            <a href="/admin/dashboard" class="brand-link">
                <img src="{{ asset('assets/Logo universitas.png') }}" alt="Logo" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">SIMAKU</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Dashboard Menu dengan dropdown -->
                        <li class="nav-item {{ request()->is('admin/dashboard*') || request()->is('admin/kelola-pengguna*') || request()->is('admin/mahasiswa*') || request()->is('admin/enrollment-mahasiswa*') || request()->is('admin/fakultas*') || request()->is('admin/program-studi*') || request()->is('admin/kelas*') || request()->is('admin/tingkat*') || request()->is('admin/tahun-akademik*') || request()->is('admin/staff*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('admin/dashboard*') || request()->is('admin/kelola-pengguna*') || request()->is('admin/mahasiswa*') || request()->is('admin/enrollment-mahasiswa*') || request()->is('admin/fakultas*') || request()->is('admin/program-studi*') || request()->is('admin/kelas*') || request()->is('admin/tingkat*') || request()->is('admin/tahun-akademik*') || request()->is('admin/staff*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.kelola-pengguna') }}" class="nav-link {{ request()->is('admin/kelola-pengguna*') ? 'active' : '' }}">
                                        <p>Kelola Pengguna</p>
                                    </a>
                                </li>

                                <!-- Mahasiswa submenu dalam Dashboard -->
                                <li class="nav-item {{ request()->is('admin/mahasiswa*') || request()->is('admin/enrollment-mahasiswa*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('admin/mahasiswa*') || request()->is('admin/enrollment-mahasiswa*') ? 'active' : '' }}">
                                        <i class="fas fa-graduation-cap nav-icon"></i>
                                        <p>
                                            Mahasiswa
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="/admin/mahasiswa" class="nav-link {{ request()->is('admin/mahasiswa*') ? 'active' : '' }}">
                                                <p>Kelola Mahasiswa</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/admin/enrollment-mahasiswa" class="nav-link {{ request()->is('admin/enrollment-mahasiswa*') ? 'active' : '' }}">
                                                <p>Enrollment Mahasiswa</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Akademik submenu dalam Dashboard -->
                                <li class="nav-item {{ request()->is('admin/fakultas*') || request()->is('admin/program-studi*') || request()->is('admin/kelas*') || request()->is('admin/tingkat*') || request()->is('admin/tahun-akademik*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('admin/fakultas*') || request()->is('admin/program-studi*') || request()->is('admin/kelas*') || request()->is('admin/tingkat*') || request()->is('admin/tahun-akademik*') ? 'active' : '' }}">
                                        <i class="fas fa-university nav-icon"></i>
                                        <p>
                                            Akademik
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="/admin/fakultas" class="nav-link {{ request()->is('admin/fakultas*') ? 'active' : '' }}">
                                                <p>Fakultas</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/admin/program-studi" class="nav-link {{ request()->is('admin/program-studi*') ? 'active' : '' }}">
                                                <p>Program Studi</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/admin/kelas" class="nav-link {{ request()->is('admin/kelas*') ? 'active' : '' }}">
                                                <p>Kelas</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/admin/tingkat" class="nav-link {{ request()->is('admin/tingkat*') ? 'active' : '' }}">
                                                <p>Tingkat</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/admin/tahun-akademik" class="nav-link {{ request()->is('admin/tahun-akademik*') ? 'active' : '' }}">
                                                <p>Tahun Akademik</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Staff submenu dalam Dashboard -->
                                <li class="nav-item">
                                    <a href="/admin/staff" class="nav-link {{ request()->is('admin/staff*') ? 'active' : '' }}">
                                        <i class="fas fa-users nav-icon"></i>
                                        <p>Staff</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Settings -->
                        <li class="nav-item">
                            <a href="/admin/settings" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
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
                            <h1 class="m-0">@yield('header', 'Dashboard Kelola Pengguna')</h1>
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

        // Custom dropdown toggle handler
        $('.nav-sidebar .nav-link').on('click', function(e) {
            var $this = $(this);
            var $parent = $this.parent();

            // Jika link memiliki submenu (ada class nav-treeview)
            if ($parent.find('.nav-treeview').length > 0) {
                e.preventDefault();

                // Toggle menu-open class
                $parent.toggleClass('menu-open');

                // Toggle dropdown icon
                var $icon = $this.find('.right');
                if ($parent.hasClass('menu-open')) {
                    $icon.removeClass('fa-angle-left').addClass('fa-angle-down');
                } else {
                    $icon.removeClass('fa-angle-down').addClass('fa-angle-left');
                }

                // Slide toggle submenu
                $parent.find('.nav-treeview').slideToggle(300);

                return false;
            }
        });

        // Pastikan dropdown terbuka sesuai halaman aktif saat load
        $('.nav-item.menu-open').each(function() {
            var $this = $(this);
            var $icon = $this.find('> .nav-link .right');
            $icon.removeClass('fa-angle-left').addClass('fa-angle-down');
            $this.find('.nav-treeview').show();
        });

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
            window.location.href = '/admin/profile';
        });
    });
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

    @yield('scripts')
</body>
</html>