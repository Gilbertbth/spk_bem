<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
include 'config/db.php';

// Tentukan halaman berdasarkan parameter 'page'
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'kandidat', 'bobot', 'hasil', 'partisipasi'];
if (!in_array($page, $allowed_pages)) $page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | SPK BEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --blue-50: #eff6ff;
            --blue-100: #dbeafe;
            --blue-200: #bfdbfe;
            --blue-500: #3b82f6;
            --blue-600: #2563eb;
            --blue-700: #1d4ed8;
            --blue-800: #1e40af;
            --blue-900: #1e3a8a;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            --radius-lg: 20px;
            --radius-xl: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--blue-50);
            display: flex;
            min-height: 100vh;
        }

        html,
        body {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* SIDEBAR */
        .sidebar {
            width: 280px;
            background-image: url('./uploads/uhnp.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #fff;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            text-align: center;
        }

        .sidebar-header .logo {
            font-size: 26px;
            font-weight: 800;
            background: linear-gradient(135deg, #93c5fd, #f0f9ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar-header .badge {
            font-size: 11px;
            background: rgba(255, 255, 255, 0.15);
            padding: 4px 12px;
            border-radius: 40px;
            display: inline-block;
            margin-top: 10px;
            color: #bfdbfe;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 24px 12px;
            flex: 1;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            color: #bfdbfe;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 14px;
            margin-bottom: 4px;
            transition: all 0.2s;
            position: relative;
        }

        .menu-item i {
            width: 22px;
            font-size: 17px;
            text-align: center;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            transform: translateX(4px);
        }

        .menu-item.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 8px 16px -6px rgba(37, 99, 235, 0.5);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 12px;
            color: #93c5fd;
            text-align: center;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 28px 32px;
            transition: margin 0.3s;
            min-height: 100vh;

            background-image:
                linear-gradient(rgba(239, 246, 255, 0.44),
                    rgba(239, 246, 255, 0.81)),
                url('./uploads/uhknp.jpg');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 14px 28px;
            border-radius: 20px;
            margin-bottom: 32px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            flex-wrap: wrap;
            gap: 16px;
            border: 1px solid var(--gray-200);
        }

        .page-title {
            font-size: 26px;
            font-weight: 700;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info span {
            color: var(--gray-700);
            font-weight: 500;
        }

        .logout-icon {
            background: #fee2e2;
            color: #b91c1c;
            padding: 8px 18px;
            border-radius: 40px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            border: 1px solid #fecaca;
        }

        .logout-icon:hover {
            background: #fecaca;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(185, 28, 28, 0.1);
        }

        /* CARD */
        .card {
            background: white;
            border-radius: var(--radius-xl);
            padding: 28px;
            margin-bottom: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--gray-100);
            animation: fadeInUp 0.5s ease-out;
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: 0 12px 28px -8px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            font-size: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 5px solid #2563eb;
            padding-left: 18px;
            color: var(--gray-800);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 36px;
        }

        .stat-card {
            background: white;
            padding: 24px 20px;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
            transition: all 0.3s;
            border: 1px solid var(--gray-100);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, #3b82f6, #60a5fa);
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.1);
        }

        .stat-card .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--blue-100);
            color: var(--blue-600);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 14px;
        }

        .stat-card h3 {
            font-size: 34px;
            font-weight: 800;
            color: #1e3a8a;
            line-height: 1.1;
        }

        .stat-card p {
            color: #475569;
            margin-top: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        /* TABLE */
        .table-responsive {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid var(--gray-200);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: var(--blue-50);
            font-weight: 600;
            color: var(--blue-800);
            padding: 14px 16px;
            text-align: left;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-700);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f8fafc;
        }

        /* BUTTONS */
        .btn {
            padding: 8px 18px;
            border-radius: 40px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.35);
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
            box-shadow: 0 4px 8px rgba(245, 158, 11, 0.2);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
        }

        .btn-sm {
            padding: 5px 14px;
            font-size: 12px;
            border-radius: 30px;
        }

        /* MOBILE & TABLET RESPONSIVE */
        .menu-toggle {
            display: none;
            background: white;
            border: 1px solid var(--gray-200);
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 20px;
            color: var(--gray-700);
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 99;
        }

        /* =========================
   TABLET
========================= */
        @media (max-width: 1024px) {

            body {
                overflow-x: hidden;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 260px;
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            .overlay {
                display: none;
            }

            body.sidebar-open .overlay {
                display: block;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 18px;
                overflow-x: hidden;
            }

            .menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .top-bar {
                padding: 14px 18px;
                border-radius: 16px;
            }

            .page-title {
                font-size: 22px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .card {
                padding: 20px;
                border-radius: 18px;
            }

            canvas {
                max-width: 100% !important;
                height: auto !important;
            }

            .table-responsive {
                width: 100%;
                overflow-x: auto;
            }

            table {
                min-width: 700px;
            }
        }


        /* =========================
   MOBILE
========================= */
        @media (max-width: 768px) {

            html,
            body {
                overflow-x: hidden;
                width: 100%;
            }

            body {
                position: relative;
            }

            .sidebar {
                width: 240px;
            }

            .main-content {
                padding: 12px;
                width: 100%;
            }

            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                padding: 14px;
                margin-bottom: 18px;
            }

            .page-title {
                font-size: 20px;
                line-height: 1.3;
            }

            .user-info {
                width: 100%;
                justify-content: space-between;
                gap: 10px;
                flex-wrap: wrap;
            }

            .logout-icon {
                padding: 8px 14px;
                font-size: 12px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .stat-card {
                padding: 20px 16px;
                border-radius: 18px;
            }

            .stat-card h3 {
                font-size: 28px;
            }

            .card {
                padding: 16px;
                border-radius: 18px;
                margin-bottom: 18px;
            }

            .card h2 {
                font-size: 16px;
                padding-left: 12px;
                margin-bottom: 18px;
            }

            .btn {
                font-size: 12px;
                padding: 8px 14px;
            }

            .table-responsive {
                overflow-x: auto;
                border-radius: 12px;
            }

            table {
                min-width: 650px;
                font-size: 12px;
            }

            th,
            td {
                padding: 10px;
            }

            canvas {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
            }
        }


        /* =========================
   SMALL MOBILE
========================= */
        @media (max-width: 480px) {

            .main-content {
                padding: 10px;
            }

            .top-bar {
                padding: 12px;
            }

            .page-title {
                font-size: 18px;
            }

            .user-info span {
                font-size: 12px;
            }

            .card {
                padding: 14px;
            }

            .card h2 {
                font-size: 15px;
            }

            .stat-card h3 {
                font-size: 24px;
            }

            .menu-toggle {
                padding: 8px 12px;
                font-size: 18px;
            }

            .sidebar {
                width: 220px;
            }
        }
    </style>
</head>

<body>
    <div class="overlay" id="overlay"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-vote-yea"></i> SPK BEM
            </div>
            <div class="badge">Admin Panel • Universitas</div>
        </div>
        <div class="sidebar-menu">
            <a href="?page=dashboard" class="menu-item <?= $page == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="?page=kandidat" class="menu-item <?= $page == 'kandidat' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Kelola Kandidat
            </a>
            <a href="?page=bobot" class="menu-item <?= $page == 'bobot' ? 'active' : '' ?>">
                <i class="fas fa-balance-scale"></i> Bobot Kriteria
            </a>
            <a href="?page=hasil" class="menu-item <?= $page == 'hasil' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Hasil AHP
            </a>
            <a href="?page=partisipasi" class="menu-item <?= $page == 'partisipasi' ? 'active' : '' ?>">
                <i class="fas fa-user-check"></i> Partisipasi
            </a>
            <a href="export_excel.php" class="menu-item">
                <i class="fas fa-file-excel"></i> Export Data
            </a>
            <a href="logout.php" class="menu-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
        <div class="sidebar-footer">
            &copy; <?= date('Y') ?> SPK BEM • v1.0
        </div>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
            <div class="page-title">
                <?php
                $titles = [
                    'dashboard' => 'Dashboard',
                    'kandidat' => 'Kelola Kandidat',
                    'bobot' => 'Bobot Kriteria',
                    'hasil' => 'Hasil Perhitungan AHP',
                    'partisipasi' => 'Partisipasi Mahasiswa'
                ];
                echo $titles[$page];
                ?>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user-shield"></i> <?= $_SESSION['nama'] ?></span>
                <a href="logout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <?php
        switch ($page) {
            case 'dashboard':
                $total_mhs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='mahasiswa'"))['total'];
                $sudah_vote = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='mahasiswa' AND has_voted=1"))['total'];
                $persen = $total_mhs > 0 ? round(($sudah_vote / $total_mhs) * 100) : 0;
                $total_kandidat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kandidat"))['total'];

                $kandidat_vote = [];
                $query_kand = mysqli_query($conn, "SELECT id, nama, nomor_urut FROM kandidat ORDER BY nomor_urut");
                while ($k = mysqli_fetch_assoc($query_kand)) {
                    $query_jml = mysqli_query($conn, "SELECT COUNT(DISTINCT id_mahasiswa) as jml FROM penilaian WHERE id_kandidat = {$k['id']}");
                    $jml = mysqli_fetch_assoc($query_jml);
                    $jumlah_suara = (int)$jml['jml'];
                    $persentase = $sudah_vote > 0 ? round(($jumlah_suara / $sudah_vote) * 100, 1) : 0;
                    $kandidat_vote[] = [
                        'nama' => $k['nama'],
                        'nomor_urut' => $k['nomor_urut'],
                        'jumlah_suara' => $jumlah_suara,
                        'persentase' => $persentase
                    ];
                }

                $labels = array_map(fn($item) => $item['nomor_urut'] . '. ' . $item['nama'], $kandidat_vote);
                $data_jumlah = array_column($kandidat_vote, 'jumlah_suara');
                $data_persen = array_column($kandidat_vote, 'persentase');

                $ranking = [];
                $kand_all = mysqli_query($conn, "SELECT * FROM kandidat");
                while ($k = mysqli_fetch_assoc($kand_all)) {
                    $skor = 0;
                    $nilai_krit = mysqli_query($conn, "SELECT id_kriteria, AVG(nilai) as rata FROM penilaian WHERE id_kandidat = {$k['id']} GROUP BY id_kriteria");
                    $ratas = [];
                    while ($nk = mysqli_fetch_assoc($nilai_krit)) $ratas[$nk['id_kriteria']] = $nk['rata'];
                    $bobot_all = mysqli_query($conn, "SELECT id, bobot FROM kriteria");
                    while ($b = mysqli_fetch_assoc($bobot_all)) {
                        if (isset($ratas[$b['id']])) $skor += $ratas[$b['id']] * $b['bobot'];
                    }
                    $ranking[] = ['nama' => $k['nama'], 'skor' => round($skor, 2), 'urut' => $k['nomor_urut']];
                }
                usort($ranking, fn($a, $b) => $b['skor'] <=> $a['skor']);
        ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="icon-circle"><i class="fas fa-user-graduate"></i></div>
                        <h3><?= $total_mhs ?></h3>
                        <p>Total Mahasiswa</p>
                    </div>
                    <div class="stat-card">
                        <div class="icon-circle"><i class="fas fa-check-circle"></i></div>
                        <h3><?= $sudah_vote ?></h3>
                        <p>Sudah Memilih</p>
                    </div>
                    <div class="stat-card">
                        <div class="icon-circle"><i class="fas fa-percentage"></i></div>
                        <h3><?= $persen ?>%</h3>
                        <p>Partisipasi</p>
                    </div>
                    <div class="stat-card">
                        <div class="icon-circle"><i class="fas fa-user-tie"></i></div>
                        <h3><?= $total_kandidat ?></h3>
                        <p>Kandidat</p>
                    </div>
                </div>

                <div class="card">
                    <h2><i class="fas fa-chart-bar"></i> Perolehan Suara Kandidat</h2>
                    <?php if ($sudah_vote > 0): ?>
                        <div style="max-height: 380px;">
                            <canvas id="voteChart"></canvas>
                        </div>
                        <div class="table-responsive" style="margin-top: 28px;">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Kandidat</th>
                                        <th>Jumlah Suara</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kandidat_vote as $kv): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($kv['nama']) ?></strong></td>
                                            <td><?= $kv['jumlah_suara'] ?> suara</td>
                                            <td><span class="btn btn-sm" style="background:#dbeafe; color:#1e40af;"><?= $kv['persentase'] ?>%</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center; padding: 40px; color: #64748b;">
                            <i class="fas fa-chart-simple" style="font-size: 42px; opacity:0.5; margin-bottom:12px;"></i>
                            <p>Belum ada suara masuk. Ajak mahasiswa untuk memilih!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h2><i class="fas fa-trophy"></i> Peringkat Sementara (Skor AHP)</h2>
                    <ol style="margin-left: 28px; font-size: 15px;">
                        <?php foreach ($ranking as $r): ?>
                            <li style="margin-bottom: 10px;">
                                <strong><?= htmlspecialchars($r['nama']) ?></strong>
                                <span style="margin-left: 10px; background:#e0e7ff; padding:2px 12px; border-radius:20px; font-size:13px;">Skor: <?= $r['skor'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>

                <?php if ($sudah_vote > 0): ?>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        const ctx = document.getElementById('voteChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: <?= json_encode($labels) ?>,
                                datasets: [{
                                        label: 'Jumlah Suara',
                                        data: <?= json_encode($data_jumlah) ?>,
                                        backgroundColor: 'rgba(37, 99, 235, 0.75)',
                                        borderColor: '#2563eb',
                                        borderWidth: 1,
                                        borderRadius: 8,
                                        yAxisID: 'y',
                                    },
                                    {
                                        label: 'Persentase (%)',
                                        data: <?= json_encode($data_persen) ?>,
                                        type: 'line',
                                        borderColor: '#f59e0b',
                                        backgroundColor: 'transparent',
                                        borderWidth: 3,
                                        tension: 0.2,
                                        pointRadius: 5,
                                        pointBackgroundColor: '#f59e0b',
                                        yAxisID: 'y1',
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.dataset.label + ': ' + context.raw + (context.dataset.label.includes('%') ? '%' : '');
                                            }
                                        }
                                    },
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            usePointStyle: true,
                                            boxWidth: 8
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Jumlah Suara'
                                        },
                                        ticks: {
                                            stepSize: 1,
                                            precision: 0
                                        }
                                    },
                                    y1: {
                                        position: 'right',
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Persentase (%)'
                                        },
                                        ticks: {
                                            callback: (val) => val + '%'
                                        },
                                        grid: {
                                            drawOnChartArea: false
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                <?php endif; ?>
            <?php break;

            case 'kandidat':
                $kandidat = mysqli_query($conn, "SELECT * FROM kandidat ORDER BY nomor_urut");
            ?>
                <div class="card">
                    <h2><i class="fas fa-users"></i> Daftar Kandidat</h2>
                    <a href="tambah_kandidat.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kandidat</a>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>No Urut</th>
                                    <th>Nama</th>
                                    <th>Visi Misi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($k = mysqli_fetch_assoc($kandidat)): ?>
                                    <tr>
                                        <td>#<?= $k['id'] ?></td>
                                        <td><span class="btn btn-sm" style="background:#e0e7ff; color:#1e40af;"><?= $k['nomor_urut'] ?></span></td>
                                        <td><strong><?= htmlspecialchars($k['nama']) ?></strong></td>
                                        <td><?= substr(htmlspecialchars($k['visi_misi']), 0, 50) ?>...</td>
                                        <td>
                                            <a href="edit_kandidat.php?id=<?= $k['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="?page=kandidat&hapus=<?= $k['id'] ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if (isset($_GET['hapus'])) {
                    $id = (int)$_GET['hapus'];
                    mysqli_query($conn, "DELETE FROM kandidat WHERE id=$id");
                    echo "<script>location.href='?page=kandidat';</script>";
                } ?>
            <?php break;

            case 'bobot':
                $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id");
            ?>
                <div class="card">
                    <h2><i class="fas fa-sliders-h"></i> Bobot Kriteria (Metode AHP)</h2>
                    <p style="margin-bottom: 20px; color: #475569;">
                        Total bobot:
                        <strong style="background:#dbeafe; padding:4px 14px; border-radius:20px;">
                            <?php
                            $total = 0;
                            $krit_all = mysqli_query($conn, "SELECT bobot FROM kriteria");
                            while ($t = mysqli_fetch_assoc($krit_all)) $total += $t['bobot'];
                            echo round($total * 100) . '%';
                            ?>
                        </strong>
                    </p>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Bobot Saat Ini</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($kr = mysqli_fetch_assoc($kriteria)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($kr['nama']) ?></td>
                                        <td><span style="background:#e0e7ff; padding:4px 14px; border-radius:20px;"><?= $kr['bobot'] * 100 ?>%</span></td>
                                        <td><a href="edit_bobot.php?id=<?= $kr['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Ubah</a></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php break;

            case 'hasil':
                $kandidat_hasil = mysqli_query($conn, "SELECT * FROM kandidat ORDER BY nomor_urut");
                $kriteria_all = mysqli_query($conn, "SELECT id, nama, bobot FROM kriteria");
                $bobot_arr = [];
                while ($b = mysqli_fetch_assoc($kriteria_all)) $bobot_arr[$b['id']] = ['nama' => $b['nama'], 'bobot' => $b['bobot']];
            ?>
                <div class="card">
                    <h2><i class="fas fa-chart-simple"></i> Hasil Akhir Perhitungan AHP</h2>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No Urut</th>
                                    <th>Nama Kandidat</th>
                                    <th>Skor Akhir</th>
                                    <th>Detail Nilai per Kriteria</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($k = mysqli_fetch_assoc($kandidat_hasil)):
                                    $total_skor = 0;
                                    $detail = [];
                                    foreach ($bobot_arr as $id_k => $data) {
                                        $rata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(nilai) as avg FROM penilaian WHERE id_kandidat = {$k['id']} AND id_kriteria = $id_k"));
                                        $nilai_rata = $rata['avg'] ?: 0;
                                        $kontribusi = $nilai_rata * $data['bobot'];
                                        $total_skor += $kontribusi;
                                        $detail[] = $data['nama'] . ': ' . round($nilai_rata, 1) . ' (kontribusi ' . round($kontribusi, 2) . ')';
                                    }
                                ?>
                                    <tr>
                                        <td><span class="btn btn-sm" style="background:#e0e7ff; color:#1e40af;"><?= $k['nomor_urut'] ?></span></td>
                                        <td><strong><?= htmlspecialchars($k['nama']) ?></strong></td>
                                        <td><span style="background:#2563eb; color:white; padding:4px 14px; border-radius:40px; font-weight:600;"><?= round($total_skor, 2) ?></span></td>
                                        <td><small><?= implode(' | ', $detail) ?></small></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php break;

            case 'partisipasi':
                $mahasiswa = mysqli_query($conn, "SELECT * FROM users WHERE role='mahasiswa' ORDER BY created_at DESC");
            ?>
                <div class="card">
                    <h2><i class="fas fa-users"></i> Daftar Mahasiswa & Status Voting</h2>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Waktu Daftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($m = mysqli_fetch_assoc($mahasiswa)): ?>
                                    <tr>
                                        <td><?= $m['nim'] ?></td>
                                        <td><?= htmlspecialchars($m['nama']) ?></td>
                                        <td>
                                            <?= $m['has_voted']
                                                ? '<span style="color:#10b981;"><i class="fas fa-check-circle"></i> Sudah</span>'
                                                : '<span style="color:#ef4444;"><i class="fas fa-clock"></i> Belum</span>' ?>
                                        </td>
                                        <td><?= $m['created_at'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        <?php break;
        } ?>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const overlay = document.getElementById('overlay');

        function closeSidebar() {
            document.body.classList.remove('sidebar-open');
        }

        menuToggle.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-open');
        });

        overlay.addEventListener('click', closeSidebar);

        // Tutup sidebar saat klik menu di mobile
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 1024) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>

</html>