<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kelola Pengguna</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f6fa;
            display: flex;
        }

        .sidebar {
            width: 240px;
            background-color: white;
            height: 100vh;
            padding: 20px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .logo img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .logo span {
            color: #4178f9;
            font-weight: bold;
            font-size: 18px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            color: #444;
            transition: all 0.3s;
        }

        .nav-item:hover {
            background-color: rgba(65, 120, 249, 0.1);
        }

        .nav-item.active {
            background-color: #4178f9;
            color: white;
            border-radius: 5px;
            margin: 0 10px;
            padding: 12px 10px;
        }

        .nav-item i {
            margin-right: 10px;
            font-size: 20px;
        }

        .content {
            margin-left: 240px;
            width: calc(100% - 240px);
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info .notif {
            width: 30px;
            height: 30px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            position: relative;
        }

        .notif .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ff4757;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-detail {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: #777;
        }

        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-left {
            display: flex;
            gap: 10px;
        }

        .select-wrapper {
            background-color: white;
            border-radius: 5px;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            width: 200px;
        }

        .select-wrapper select {
            border: none;
            background: transparent;
            width: 100%;
            outline: none;
        }

        .filter-btn {
            background-color: #4178f9;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 20px;
            cursor: pointer;
        }

        .add-btn {
            background-color: #4178f9;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .search-box {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px 15px;
            width: 250px;
        }

        .table-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table-header {
            margin-bottom: 20px;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #666;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .role-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            display: inline-block;
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

        .edit-btn {
            background-color: #4178f9;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .page-controls {
            display: flex;
            gap: 10px;
        }

        .page-btn {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Icons */
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
    </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="/api/placeholder/30/30" alt="SIMAKU Logo">
            <span>SIMAKU</span>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <i class="material-icons">dashboard</i>
                <span>Dashboard</span>
            </li>
            <li class="nav-item active">
                <i class="material-icons">people</i>
                <span>Kelola Pengguna</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">assignment</i>
                <span>Kelola Role</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">menu</i>
                <span>Kelola Menu</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">settings</i>
                <span>Settings</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">exit_to_app</i>
                <span>Logout</span>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="header">
            <h2>Dashboard Kelola Pengguna</h2>
            <div class="user-info">
                <div class="notif">
                    <i class="material-icons" style="font-size: 18px;">notifications</i>
                    <span class="badge">3</span>
                </div>
                <div class="user-avatar">
                    <img src="/api/placeholder/40/40" alt="User Avatar">
                </div>
                <div class="user-detail">
                    <div class="user-name">Arifyandi Samuel</div>
                    <div class="user-role">Admin Keuangan</div>
                </div>
            </div>
        </div>

        <div class="filter-row">
            <div class="filter-left">
                <div class="select-wrapper">
                    <select>
                        <option>All User</option>
                        <option>Admin</option>
                        <option>Staff</option>
                        <option>Mahasiswa</option>
                    </select>
                </div>
                <button class="filter-btn">Filter</button>
            </div>

            <div class="search-wrapper">
                <input type="text" placeholder="Search..." class="search-box">
            </div>
        </div>

        <button class="add-btn">
            <i class="material-icons" style="font-size: 18px;">add</i>
            Tambah Pengguna
        </button>

        <div class="table-container" style="margin-top: 20px;">
            <div class="table-header">
                <div class="table-title">Semua Pengguna</div>
            </div>

            <table>
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
                    <tr>
                        <td>01</td>
                        <td>Satria Ramadhan</td>
                        <td>satria@univ.ac.id</td>
                        <td><span class="role-badge role-admin">Admin Keuangan</span></td>
                        <td><button class="edit-btn">Edit</button></td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>Dina Kusumawati</td>
                        <td>dina@univ.ac.id</td>
                        <td><span class="role-badge role-admin">Admin Keuangan</span></td>
                        <td><button class="edit-btn">Edit</button></td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>Farhan Alamsyah</td>
                        <td>farhan.al@univ.ac.id</td>
                        <td><span class="role-badge role-staff">Staff Keuangan</span></td>
                        <td><button class="edit-btn">Edit</button></td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>Lestari Ayuningtyas</td>
                        <td>lestari.ay@univ.ac.id</td>
                        <td><span class="role-badge role-staff">Staff Keuangan</span></td>
                        <td><button class="edit-btn">Edit</button></td>
                    </tr>
                    <tr>
                        <td>05</td>
                        <td>Bima Aditya</td>
                        <td>bima.aditya@univ.ac.id</td>
                        <td><span class="role-badge role-staff">Staff Keuangan</span></td>
                        <td><button class="edit-btn">Edit</button></td>
                    </tr>
                    <tr>
                        <td>06</td>
                        <td>Aulia Rahmawati</td>
                        <td>aulia.rahma@univ.ac.id</td>
                        <td><span class="role-badge role-mahasiswa">Mahasiswa</span></td>
                        <td><button class="edit-btn">Edit</button></td>
                    </tr>
                    <tr>
                        <td>07</td>
                        <td>Galang Saputra</td>
                        <td>galang.saputra@univ.ac.id</td>
                        <td><span class="role-badge role-mahasiswa">Mahasiswa</span></td>
                        <td><button class="edit-btn">Edit</button></td>
                    </tr>
                    <tr>
                        <td>08</td>
                        <td>Indah Permatasari</td>
                        <td>indah.p@univ.ac.id</td>
                        <td><span class="role-badge role-mahasiswa">Mahasiswa</span></td>
                        <td><button class="edit-btn">Edit</button></td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination">
                <div>Showing 1-8 of 7950</div>
                <div class="page-controls">
                    <button class="page-btn"><i class="material-icons" style="font-size: 18px;">chevron_left</i></button>
                    <button class="page-btn"><i class="material-icons" style="font-size: 18px;">chevron_right</i></button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>