<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembayaran UKT</title>
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
            gap: 10px;
            margin-bottom: 20px;
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

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-card .title {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle.green {
            background-color: rgba(46, 213, 115, 0.2);
            color: #2ed573;
        }

        .icon-circle.blue {
            background-color: rgba(54, 162, 235, 0.2);
            color: #36a2eb;
        }

        .icon-circle.yellow {
            background-color: rgba(255, 206, 86, 0.2);
            color: #ffce56;
        }

        .icon-circle.red {
            background-color: rgba(255, 71, 87, 0.2);
            color: #ff4757;
        }

        .table-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
        }

        .filter-options {
            display: flex;
            gap: 10px;
        }

        .search-box {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px 15px;
            width: 250px;
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

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            display: inline-block;
            min-width: 80px;
        }

        .status-diverifikasi {
            background-color: rgba(46, 213, 115, 0.2);
            color: #2ed573;
        }

        .status-belum {
            background-color: rgba(255, 206, 86, 0.2);
            color: #ffce56;
        }

        .status-ditolak {
            background-color: rgba(255, 71, 87, 0.2);
            color: #ff4757;
        }

        .action-btn {
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
                <i class="material-icons">payment</i>
                <span>Pembayaran UKT</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">receipt</i>
                <span>Cek Tagihan UKT</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">account_balance_wallet</i>
                <span>Pengajuan Cicilan</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">description</i>
                <span>Buat Tagihan UKT</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">school</i>
                <span>Beasiswa</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">person</i>
                <span>Data Mahasiswa</span>
            </li>
            <li class="nav-item">
                <i class="material-icons">account_circle</i>
                <span>Profile</span>
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
            <h2>Dashboard Pembayaran UKT</h2>
            <div class="user-info">
                <div class="notif">
                    <i class="material-icons" style="font-size: 18px;">notifications</i>
                    <span class="badge">2</span>
                </div>
                <div class="user-avatar">
                    <img src="/api/placeholder/40/40" alt="User Avatar">
                </div>
                <div class="user-detail">
                    <div class="user-name">Bambang Sulistio</div>
                    <div class="user-role">Staff Keuangan</div>
                </div>
            </div>
        </div>

        <div class="filter-row">
            <div class="select-wrapper">
                <select>
                    <option>2023/2024 - Genap</option>
                    <option>2023/2024 - Ganjil</option>
                    <option>2022/2023 - Genap</option>
                </select>
            </div>
            <button class="filter-btn">Filter</button>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="title">Total Pembayaran</div>
                <div class="value">
                    <span>Rp 700.000.000,00</span>
                    <div class="icon-circle green">
                        <i class="material-icons" style="font-size: 18px;">paid</i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="title">Diverifikasi</div>
                <div class="value">
                    <span>1000</span>
                    <div class="icon-circle blue">
                        <i class="material-icons" style="font-size: 18px;">check_circle</i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="title">Belum Diverifikasi</div>
                <div class="value">
                    <span>200</span>
                    <div class="icon-circle yellow">
                        <i class="material-icons" style="font-size: 18px;">pending</i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="title">Ditolak</div>
                <div class="value">
                    <span>2</span>
                    <div class="icon-circle red">
                        <i class="material-icons" style="font-size: 18px;">cancel</i>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <div class="table-title">Semua Pembayaran</div>

                <div class="filter-options">
                    <div class="select-wrapper">
                        <select>
                            <option>All Status</option>
                            <option>Diverifikasi</option>
                            <option>Belum Diverifikasi</option>
                            <option>Ditolak</option>
                        </select>
                    </div>

                    <div class="select-wrapper">
                        <select>
                            <option>All Program Studi</option>
                            <option>Teknik Informatika</option>
                            <option>Manajemen</option>
                            <option>Akuntansi</option>
                        </select>
                    </div>

                    <button class="filter-btn">Filter</button>
                    <input type="text" placeholder="Search..." class="search-box">
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Tagihan</th>
                        <th>Mahasiswa</th>
                        <th>Semester</th>
                        <th>Tanggal</th>
                        <th>Dibayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>INV000013408</td>
                        <td>Zrivia Syafira</td>
                        <td>2023/2024 - Genap</td>
                        <td>26 Jan 2024</td>
                        <td>Rp 2.600.000,00</td>
                        <td><span class="status-badge status-diverifikasi">Diverifikasi</span></td>
                        <td><button class="action-btn">Lihat Pembayaran</button></td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>INV001234501</td>
                        <td>Fadhli Ramadhani</td>
                        <td>2023/2024 - Genap</td>
                        <td>26 Jan 2024</td>
                        <td>Rp 3.000.000,00</td>
                        <td><span class="status-badge status-diverifikasi">Diverifikasi</span></td>
                        <td><button class="action-btn">Lihat Pembayaran</button></td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>INV001234502</td>
                        <td>Aisyah Hanifah</td>
                        <td>2023/2024 - Genap</td>
                        <td>26 Jan 2024</td>
                        <td>Rp 2.850.000,00</td>
                        <td><span class="status-badge status-ditolak">Ditolak</span></td>
                        <td><button class="action-btn">Lihat Pembayaran</button></td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>INV001234503</td>
                        <td>Yudha Prasetyo</td>
                        <td>2023/2024 - Genap</td>
                        <td>25 Jan 2024</td>
                        <td>Rp 2.700.000,00</td>
                        <td><span class="status-badge status-diverifikasi">Diverifikasi</span></td>
                        <td><button class="action-btn">Lihat Pembayaran</button></td>
                    </tr>
                    <tr>
                        <td>05</td>
                        <td>INV001234506</td>
                        <td>Siti Nurhaliza</td>
                        <td>2023/2024 - Genap</td>
                        <td>29 Jan 2024</td>
                        <td>Rp 2.950.000,00</td>
                        <td><span class="status-badge status-belum">Belum Diverifikasi</span></td>
                        <td><button class="action-btn">Lihat Pembayaran</button></td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination">
                <div>Showing 1-5 of 1890</div>
                <div class="page-controls">
                    <button class="page-btn"><i class="material-icons" style="font-size: 18px;">chevron_left</i></button>
                    <button class="page-btn"><i class="material-icons" style="font-size: 18px;">chevron_right</i></button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>