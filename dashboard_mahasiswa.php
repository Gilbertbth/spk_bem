<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: index.php");
    exit();
}
include 'config/db.php';
$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id"));
$has_voted = $user['has_voted'];

$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';
$allowed = ['beranda', 'kandidat', 'panduan', 'profil'];
if (!in_array($page, $allowed)) $page = 'beranda';

// Data umum
$total_kandidat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kandidat"))['total'];
$total_suara = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT id_mahasiswa) as total FROM penilaian"))['total'];

// Untuk profil
$pilihan_kandidat = null;
if ($has_voted) {
    $vote = mysqli_query($conn, "SELECT id_kandidat FROM penilaian WHERE id_mahasiswa = $user_id LIMIT 1");
    if ($row = mysqli_fetch_assoc($vote)) {
        $kand = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM kandidat WHERE id = {$row['id_kandidat']}"));
        $pilihan_kandidat = $kand['nama'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa | SPK BEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.06), 0 4px 6px -2px rgba(0,0,0,0.03);
            --radius-lg: 20px;
            --radius-xl: 24px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--blue-50);
            display: flex;
            min-height: 100vh;
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
            border-bottom: 1px solid rgba(255,255,255,0.15);
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
            background: rgba(255,255,255,0.15);
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
            background: rgba(255,255,255,0.12);
            color: #fff;
            transform: translateX(4px);
        }
        .menu-item.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 8px 16px -6px rgba(37,99,235,0.5);
            font-weight: 600;
        }
        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
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

        /* TOP BAR */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 14px 28px;
            border-radius: 20px;
            margin-bottom: 32px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
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
        .btn-logout {
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
        .btn-logout:hover {
            background: #fecaca;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(185,28,28,0.1);
        }

        /* COMMON CARD */
        .card {
            background: white;
            border-radius: var(--radius-xl);
            padding: 28px;
            margin-bottom: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid var(--gray-100);
            animation: fadeInUp 0.5s ease-out;
            transition: box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 12px 28px -8px rgba(0,0,0,0.08);
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: white;
            padding: 24px 20px;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(0,0,0,0.03);
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
            box-shadow: 0 20px 25px -5px rgba(59,130,246,0.1);
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

        /* KANDIDAT GRID */
        .kandidat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            margin-top: 20px;
        }
        .kandidat-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--gray-100);
        }
        .kandidat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(59,130,246,0.1);
        }
        .card-header {
            text-align: center;
            padding: 24px 20px 16px;
            background: #fafcff;
            border-bottom: 1px solid var(--gray-200);
        }
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 12px;
        }
        .detail-kriteria {
            padding: 16px;
            flex: 1;
            overflow-y: auto;
            max-height: 300px;
        }
        .kriteria-item {
            margin-bottom: 14px;
        }
        .kriteria-label {
            font-weight: 700;
            color: var(--blue-700);
            font-size: 13px;
            display: flex;
            gap: 6px;
            margin-bottom: 4px;
        }
        .kriteria-desc {
            font-size: 13px;
            background: var(--gray-50);
            padding: 8px 12px;
            border-radius: 16px;
            line-height: 1.5;
        }
        .btn-vote {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 40px;
            width: calc(100% - 32px);
            margin: 0 16px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn-vote:hover {
            background: linear-gradient(135deg, #059669, #047857);
            box-shadow: 0 8px 16px rgba(16,185,129,0.3);
        }
        .vote-form-container {
            margin: 0 16px 16px;
            padding: 16px;
            background: #f1f5f9;
            border-radius: 20px;
        }
        .form-group {
            margin-bottom: 14px;
        }
        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            display: block;
            margin-bottom: 6px;
        }
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 20px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            transition: border 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .alert {
            padding: 14px 20px;
            border-radius: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }
        .alert-info {
            background: #e0e7ff;
            color: #4338ca;
        }
        .profile-item {
            display: flex;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--gray-200);
            padding-bottom: 12px;
        }
        .profile-label {
            width: 160px;
            font-weight: 600;
            color: #475569;
        }
        .profile-value {
            flex: 1;
            color: #1e293b;
        }
        .rekap-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 13px;
        }
        .rekap-table th, .rekap-table td {
            border: 1px solid var(--gray-200);
            padding: 10px 14px;
            text-align: center;
        }
        .rekap-table th {
            background: var(--gray-50);
            font-weight: 600;
        }
        .skor-akhir {
            font-weight: 700;
            color: #4f46e5;
        }
        .profil-singkat {
            background: var(--gray-50);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid var(--gray-200);
        }
        .profil-singkat:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.06);
        }
        .ipk-badge {
            background: #e2e8f0;
            padding: 4px 14px;
            border-radius: 30px;
            font-size: 12px;
            display: inline-block;
            margin-top: 8px;
            font-weight: 600;
        }

        /* TABLE RESPONSIVE */
        .table-responsive {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid var(--gray-200);
        }

        /* MOBILE & TABLET */
        .menu-toggle {
            display: none;
            background: white;
            border: 1px solid var(--gray-200);
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 20px;
            color: var(--gray-700);
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 99;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 100;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            body.sidebar-open .sidebar {
                transform: translateX(0);
            }
            body.sidebar-open .overlay {
                display: block;
            }
            .menu-toggle {
                display: flex;
            }
            .top-bar {
                padding: 12px 20px;
            }
            .page-title {
                font-size: 22px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .top-bar {
                padding: 12px 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .user-info {
                width: 100%;
                justify-content: space-between;
            }
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }
            .stat-card {
                padding: 18px 14px;
            }
            .stat-card h3 {
                font-size: 28px;
            }
            .stat-card .icon-circle {
                width: 40px;
                height: 40px;
                font-size: 18px;
                margin-bottom: 10px;
            }
            .card {
                padding: 20px;
                border-radius: 20px;
            }
            .card h2 {
                font-size: 18px;
                margin-bottom: 18px;
                padding-left: 14px;
                border-left-width: 4px;
            }
            .kandidat-grid {
                grid-template-columns: 1fr;
            }
            .profil-singkat {
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .stat-card h3 {
                font-size: 26px;
            }
            .top-bar {
                padding: 10px 14px;
                gap: 10px;
            }
            .menu-toggle {
                padding: 8px 12px;
                font-size: 18px;
            }
            .card {
                padding: 16px;
                border-radius: 16px;
            }
            .card h2 {
                font-size: 16px;
                padding-left: 12px;
            }
        }

        /* ===== FIX OVERFLOW MOBILE ===== */

html,
body {
    width: 100%;
    overflow-x: hidden;
}

/* Hindari elemen melebar */
* {
    max-width: 100%;
}

/* Main content */
.main-content {
    width: 100%;
    overflow-x: hidden;
}

/* Chart responsive */
canvas {
    max-width: 100% !important;
    height: auto !important;
}

/* Kandidat card */
.kandidat-card {
    width: 100%;
    min-width: 0;
}

/* Detail kandidat */
.detail-kriteria {
    overflow-x: hidden;
    word-wrap: break-word;
}

/* Text panjang */
.kriteria-desc,
.profile-value,
.alert,
.card p,
.card h2 {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Grid mobile */
@media (max-width: 768px) {

    body {
        overflow-x: hidden;
    }

    .sidebar {
        width: 240px;
    }

    .main-content {
        margin-left: 0;
        padding: 14px;
    }

    .top-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 14px;
    }

    .user-info {
        width: 100%;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .page-title {
        font-size: 20px;
        line-height: 1.3;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .kandidat-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .card {
        padding: 16px;
        border-radius: 18px;
    }

    .card h2 {
        font-size: 17px;
        margin-bottom: 16px;
    }

    .kandidat-card {
        border-radius: 18px;
    }

    .avatar {
        width: 80px;
        height: 80px;
    }

    .detail-kriteria {
        max-height: none;
        overflow: visible;
    }

    .btn-vote {
        font-size: 13px;
        padding: 11px 16px;
    }

    .vote-form-container {
        padding: 14px;
    }

    .form-group input {
        font-size: 13px;
    }

    .profile-item {
        flex-direction: column;
        gap: 4px;
    }

    .profile-label {
        width: 100%;
    }
}

/* HP kecil */
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

    .card {
        padding: 14px;
    }

    .card h2 {
        font-size: 15px;
    }

    .stat-card {
        padding: 16px;
    }

    .stat-card h3 {
        font-size: 24px;
    }

    .btn-vote {
        width: 100%;
    }

    .sidebar {
        width: 220px;
    }
}

/* MODAL */

.modal-detail{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 999;
    padding: 20px;
}

.modal-content{
    background: white;
    width: 100%;
    max-width: 700px;
    border-radius: 24px;
    padding: 24px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
}

.close-modal{
    position: absolute;
    top: 14px;
    right: 18px;
    border: none;
    background: transparent;
    font-size: 30px;
    cursor: pointer;
}

.modal-avatar{
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin: auto;
    display: block;
    margin-bottom: 16px;
}

.modal-title{
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 10px;
}

.modal-section{
    margin-top: 18px;
}

.modal-section h4{
    color: #2563eb;
    margin-bottom: 8px;
}

@media(max-width:768px){

    .modal-content{
        padding: 18px;
        border-radius: 18px;
    }

    .modal-title{
        font-size: 22px;
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
            <div class="badge">Mahasiswa Panel</div>
        </div>
        <div class="sidebar-menu">
            <a href="?page=beranda" class="menu-item <?= $page=='beranda'?'active':'' ?>">
                <i class="fas fa-home"></i> Beranda
            </a>
            <a href="?page=kandidat" class="menu-item <?= $page=='kandidat'?'active':'' ?>">
                <i class="fas fa-user-tie"></i> Daftar Kandidat
            </a>
            <a href="?page=panduan" class="menu-item <?= $page=='panduan'?'active':'' ?>">
                <i class="fas fa-info-circle"></i> Panduan
            </a>
            <a href="?page=profil" class="menu-item <?= $page=='profil'?'active':'' ?>">
                <i class="fas fa-user-circle"></i> Profil Saya
            </a>
            <a href="logout.php" class="menu-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
        <div class="sidebar-footer">
            &copy; <?= date('Y') ?> SPK BEM
        </div>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
            <div class="page-title">
                <?php 
                    $titles = ['beranda'=>'Beranda', 'kandidat'=>'Daftar Kandidat', 'panduan'=>'Panduan Memilih', 'profil'=>'Profil Saya'];
                    echo $titles[$page];
                ?>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user-graduate"></i> <?= htmlspecialchars($user['nama']) ?></span>
                <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </div>

        <?php if ($page == 'beranda'): 
            $kandidat_vote = [];
            $query_kand = mysqli_query($conn, "SELECT id, nama, nomor_urut, foto, visi_misi FROM kandidat ORDER BY nomor_urut");
            while ($k = mysqli_fetch_assoc($query_kand)) {
                $query_jml = mysqli_query($conn, "SELECT COUNT(DISTINCT id_mahasiswa) as jml FROM penilaian WHERE id_kandidat = {$k['id']}");
                $jml = mysqli_fetch_assoc($query_jml);
                $kandidat_vote[] = [
                    'id' => $k['id'],
                    'nama' => $k['nama'],
                    'nomor_urut' => $k['nomor_urut'],
                    'foto' => $k['foto'],
                    'visi_misi' => $k['visi_misi'],
                    'suara' => (int)$jml['jml']
                ];
            }
            $total_suara_beranda = array_sum(array_column($kandidat_vote, 'suara'));
            $labels_pie = array_map(fn($item) => $item['nomor_urut'] . '. ' . $item['nama'], $kandidat_vote);
            $data_pie = array_column($kandidat_vote, 'suara');
        ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-circle"><i class="fas fa-users"></i></div>
                <h3><?= $total_kandidat ?></h3>
                <p>Kandidat</p>
            </div>
            <div class="stat-card">
                <div class="icon-circle"><i class="fas fa-vote-yea"></i></div>
                <h3><?= $total_suara_beranda ?></h3>
                <p>Total Suara</p>
            </div>
            <div class="stat-card">
                <div class="icon-circle">
                    <?php if($has_voted): ?>
                        <i class="fas fa-check-circle" style="color:#10b981;"></i>
                    <?php else: ?>
                        <i class="fas fa-hourglass-half" style="color:#f59e0b;"></i>
                    <?php endif; ?>
                </div>
                <p><?= $has_voted ? 'Sudah Memilih' : 'Belum Memilih' ?></p>
            </div>
        </div>

        <div class="card">
            <h2><i class="fas fa-chart-pie"></i> Perolehan Suara Sementara</h2>
            <?php if ($total_suara_beranda > 0): ?>
            <canvas id="votePieChart" style="max-height: 250px;"></canvas>
            <div style="margin-top: 24px; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <?php foreach ($kandidat_vote as $kv): 
                    $persen = $total_suara_beranda > 0 ? round(($kv['suara'] / $total_suara_beranda) * 100, 1) : 0;
                ?>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: <?= $kv['suara'] > 0 ? '#2563eb' : '#cbd5e1' ?>; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;"><?= $persen ?>%</div>
                    <p style="margin-top: 6px; font-size: 12px;"><?= htmlspecialchars($kv['nama']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div style="text-align:center; padding: 40px; color: #64748b;">
                <i class="fas fa-chart-simple" style="font-size: 42px; opacity:0.5;"></i>
                <p style="margin-top: 12px;">Belum ada suara masuk.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2><i class="fas fa-user-tie"></i> Profil Singkat Kandidat</h2>
            <div class="kandidat-grid">
                <?php foreach ($kandidat_vote as $kv): 
                    $visi_singkat = substr(htmlspecialchars($kv['visi_misi']), 0, 80) . '...';
                ?>
                <div class="profil-singkat">
                    <img src="<?= $kv['foto'] ?: 'https://via.placeholder.com/80' ?>" style="width:80px; height:80px; border-radius:50%; object-fit:cover; margin-bottom:12px;">
                    <h4><?= $kv['nomor_urut'] ?>. <?= htmlspecialchars($kv['nama']) ?></h4>
                    <p style="font-size:13px; color:#475569; margin-top:8px;"><?= $visi_singkat ?></p>
                    <div style="margin-top:10px; background:#e2e8f0; border-radius:30px; padding:4px 14px; display:inline-block;">
                        <i class="fas fa-vote-yea"></i> <?= $kv['suara'] ?> suara
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if(!$has_voted): ?>
            <div style="text-align: center; margin-top: 24px;">
                <a href="?page=kandidat" class="btn-vote" style="display:inline-block; width:auto; padding:12px 28px;">
                    <i class="fas fa-arrow-right"></i> Lihat Detail & Beri Nilai
                </a>
            </div>
            <?php else: ?>
            <div style="text-align: center; margin-top: 24px; color: #10b981; font-weight:600;">
                <i class="fas fa-check-circle"></i> Terima kasih sudah memberikan suara.
            </div>
            <?php endif; ?>
        </div>

        <?php if ($total_suara_beranda > 0): ?>
        <script>
            new Chart(document.getElementById('votePieChart'), {
                type: 'pie',
                data: {
                    labels: <?= json_encode($labels_pie) ?>,
                    datasets: [{
                        data: <?= json_encode($data_pie) ?>,
                        backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec489a', '#14b8a6', '#f97316'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 12 }, usePointStyle: true } },
                        tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} suara (${((ctx.raw/<?= $total_suara_beranda ?>)*100).toFixed(1)}%)` } }
                    }
                }
            });
        </script>
        <?php endif; ?>

    <?php elseif ($page == 'kandidat'): 
        $rekap = [];
        $kandidat_all = mysqli_query($conn, "SELECT * FROM kandidat ORDER BY nomor_urut");
        $kriteria_all = mysqli_query($conn, "SELECT id, nama, bobot FROM kriteria ORDER BY id");
        $kriteria_list = [];
        while ($kr = mysqli_fetch_assoc($kriteria_all)) {
            $kriteria_list[$kr['id']] = ['nama' => $kr['nama'], 'bobot' => $kr['bobot']];
        }
        while ($k = mysqli_fetch_assoc($kandidat_all)) {
            $id_kandidat = $k['id'];
            $query_avg = mysqli_query($conn, "SELECT id_kriteria, AVG(nilai) as rata FROM penilaian WHERE id_kandidat = $id_kandidat GROUP BY id_kriteria");
            $rata_kriteria = [];
            while ($row = mysqli_fetch_assoc($query_avg)) {
                $rata_kriteria[$row['id_kriteria']] = round($row['rata'], 2);
            }
            $skor = 0;
            foreach ($kriteria_list as $idk => $data) {
                if (isset($rata_kriteria[$idk])) {
                    $skor += $rata_kriteria[$idk] * $data['bobot'];
                }
            }
            $skor = round($skor, 2);
            $rekap[] = [
                'id' => $k['id'],
                'nama' => $k['nama'],
                'nomor_urut' => $k['nomor_urut'],
                'skor' => $skor,
                'rata_kriteria' => $rata_kriteria,
                'foto' => $k['foto'],
                'visi_misi' => $k['visi_misi'],
                'kepemimpinan' => $k['kepemimpinan'],
                'pengalaman' => $k['pengalaman'],
                'komunikasi' => $k['komunikasi'],
                'integritas' => $k['integritas'],
                'ipk' => $k['ipk']
            ];
        }
    ?>
        <?php if ($has_voted): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> Anda sudah memberikan voting. Terima kasih!</div>
        <?php endif; ?>

        <h2 style="margin: 16px 0 8px; font-weight:700; color:var(--gray-800);"><i class="fas fa-user-tie"></i> Profil Lengkap Kandidat</h2>
        <div class="kandidat-grid">
            <?php foreach ($rekap as $k): ?>
            <div class="kandidat-card" data-id="<?= $k['id'] ?>" data-nama="<?= htmlspecialchars($k['nama']) ?>" data-ipk="<?= $k['ipk'] ?>">
                <div class="card-header">
                    <img class="avatar" src="<?= $k['foto'] ?: 'https://via.placeholder.com/100' ?>">
                    <h3 style="margin-bottom:4px;"><?= htmlspecialchars($k['nama']) ?></h3>
                    <span style="background:#e0e7ff; color:#1e40af; padding:2px 12px; border-radius:30px; font-size:12px;">No. <?= $k['nomor_urut'] ?></span>
                    <div><span class="ipk-badge"><i class="fas fa-graduation-cap"></i> IPK: <?= $k['ipk'] ?: '-' ?> / 4.00</span></div>
                </div>
                <div class="detail-kriteria">
    <div class="kriteria-item">
        <div class="kriteria-label">
            <i class="fas fa-eye"></i> Visi & Misi
        </div>

        <div class="kriteria-desc">
            <?= substr(strip_tags($k['visi_misi']), 0, 120) ?>...
        </div>
    </div>

    <button 
        class="btn-vote detail-btn"
        data-id="<?= $k['id'] ?>"
        data-nama="<?= htmlspecialchars($k['nama']) ?>"
        data-foto="<?= $k['foto'] ?>"
        data-ipk="<?= $k['ipk'] ?>"
        data-visi="<?= htmlspecialchars($k['visi_misi']) ?>"
        data-kepemimpinan="<?= htmlspecialchars($k['kepemimpinan']) ?>"
        data-pengalaman="<?= htmlspecialchars($k['pengalaman']) ?>"
        data-komunikasi="<?= htmlspecialchars($k['komunikasi']) ?>"
        data-integritas="<?= htmlspecialchars($k['integritas']) ?>"
    >
        <i class="fas fa-user"></i> Detail Kandidat
    </button>
</div>
                <?php if (!$has_voted): ?>
                    <button class="btn-vote vote-trigger" data-id="<?= $k['id'] ?>" data-nama="<?= htmlspecialchars($k['nama']) ?>" data-ipk="<?= $k['ipk'] ?>"><i class="fas fa-check-circle"></i> Pilih & Nilai</button>
                <?php else: ?>
                    <div style="text-align:center; padding:14px; color:#10b981; font-weight:600;"><i class="fas fa-check-circle"></i> Voting telah dilakukan</div>
                <?php endif; ?>
                <div class="vote-form-placeholder"></div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($page == 'panduan'): ?>
        <div class="card">
            <h2><i class="fas fa-lightbulb"></i> Panduan Memilih</h2>
            <ol style="margin-left:20px; line-height:1.9; font-size:15px; color:var(--gray-700);">
                <li>Klik menu <strong>Daftar Kandidat</strong> untuk melihat profil lengkap setiap calon ketua BEM.</li>
                <li>Setiap kandidat memiliki deskripsi sesuai kriteria: Kepemimpinan, Pengalaman, Komunikasi, Integritas, Visi Misi, dan IPK.</li>
                <li>Pilih satu kandidat yang menurut Anda paling layak, lalu klik tombol <strong>"Pilih & Nilai"</strong>.</li>
                <li>Anda akan diminta mengisi nilai 1-100 untuk setiap kriteria <strong>kecuali IPK</strong> (nilai IPK otomatis dari data kandidat).</li>
                <li>Nilai yang Anda berikan akan diproses dengan metode AHP untuk menentukan skor akhir.</li>
                <li><strong>Setelah mengirimkan suara, Anda tidak dapat mengubah pilihan.</strong></li>
            </ol>
        </div>

    <?php elseif ($page == 'profil'): ?>
        <div class="card">
            <h2><i class="fas fa-user-circle"></i> Profil Mahasiswa</h2>
            <div class="profile-item"><div class="profile-label">Nama Lengkap</div><div class="profile-value"><?= htmlspecialchars($user['nama']) ?></div></div>
            <div class="profile-item"><div class="profile-label">Username</div><div class="profile-value"><?= htmlspecialchars($user['username']) ?></div></div>
            <div class="profile-item"><div class="profile-label">NIM</div><div class="profile-value"><?= htmlspecialchars($user['nim']) ?></div></div>
            <div class="profile-item"><div class="profile-label">Tanggal Daftar</div><div class="profile-value"><?= date('d F Y H:i', strtotime($user['created_at'])) ?></div></div>
            <div class="profile-item"><div class="profile-label">Status Voting</div><div class="profile-value">
                <?php if ($has_voted): ?>
                    <span style="color:#10b981;"><i class="fas fa-check-circle"></i> Sudah memilih</span>
                    <?php if ($pilihan_kandidat): ?> → Kandidat: <strong><?= htmlspecialchars($pilihan_kandidat) ?></strong><?php endif; ?>
                <?php else: ?>
                    <span style="color:#ef4444;"><i class="fas fa-clock"></i> Belum memilih</span>
                <?php endif; ?>
            </div></div>
            <?php if (!$has_voted): ?>
                <a href="?page=kandidat" class="btn-vote" style="display:inline-block; width:auto; margin-top:16px; padding:12px 24px;"><i class="fas fa-vote-yea"></i> Gunakan Hak Pilih</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($page == 'kandidat' && !$has_voted): ?>
<template id="voteFormTemplate">
    <div class="vote-form-container">
        <h4 style="margin-bottom:14px; font-weight:600; color:var(--gray-800);"><i class="fas fa-star"></i> Beri Nilai untuk <span class="kandidat-name"></span></h4>
        <form action="vote_process.php" method="POST">
            <input type="hidden" name="id_kandidat" class="kandidat-id-input">
            <input type="hidden" name="nilai_ipk" class="kandidat-ipk-input">
            <?php 
            $krit = mysqli_query($conn, "SELECT * FROM kriteria WHERE nama != 'IPK' ORDER BY id");
            while ($kr = mysqli_fetch_assoc($krit)): 
            ?>
            <div class="form-group">
                <label><?= $kr['nama'] ?> (Bobot <?= $kr['bobot']*100 ?>%) - Nilai 1-100</label>
                <input type="number" name="nilai[<?= $kr['id'] ?>]" min="1" max="100" required placeholder="Masukkan nilai 1-100">
            </div>
            <?php endwhile; ?>
            <button type="submit" class="btn-vote" style="background:#2563eb; width:100%;"><i class="fas fa-paper-plane"></i> Kirim Suara</button>
            <button type="button" class="btn-vote close-form" style="background:#94a3b8; width:100%; margin-top:8px;">Batal</button>
        </form>
    </div>
</template>
<script>
    document.querySelectorAll('.vote-trigger').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.kandidat-card');
            const placeholder = card.querySelector('.vote-form-placeholder');
            if (placeholder.hasChildNodes()) { placeholder.innerHTML = ''; return; }
            const template = document.getElementById('voteFormTemplate');
            const clone = template.content.cloneNode(true);
            clone.querySelector('.kandidat-name').innerText = this.dataset.nama;
            clone.querySelector('.kandidat-id-input').value = this.dataset.id;
            clone.querySelector('.kandidat-ipk-input').value = this.dataset.ipk;
            clone.querySelector('.close-form').addEventListener('click', () => placeholder.innerHTML = '');
            placeholder.appendChild(clone);
            placeholder.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });
</script>
<?php endif; ?>

<script>
    const menuToggle = document.getElementById('menuToggle');
    const overlay = document.getElementById('overlay');
    function closeSidebar() { document.body.classList.remove('sidebar-open'); }
    menuToggle.addEventListener('click', () => { document.body.classList.toggle('sidebar-open'); });
    overlay.addEventListener('click', closeSidebar);
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 1024) closeSidebar();
        });
    });
</script>
<!-- MODAL DETAIL -->
<div class="modal-detail" id="modalDetail">
    <div class="modal-content">

        <button class="close-modal" id="closeModal">
            &times;
        </button>

        <div id="modalBody"></div>

    </div>
</div>

<script>

const modal = document.getElementById('modalDetail');
const modalBody = document.getElementById('modalBody');
const closeModal = document.getElementById('closeModal');

document.querySelectorAll('.detail-btn').forEach(btn => {

    btn.addEventListener('click', function(){

        modal.style.display = 'flex';

        modalBody.innerHTML = `
        
            <img src="${this.dataset.foto}" class="modal-avatar">

            <div class="modal-title">
                ${this.dataset.nama}
            </div>

            <div style="text-align:center; margin-bottom:20px;">
                <span class="ipk-badge">
                    IPK: ${this.dataset.ipk} / 4.00
                </span>
            </div>

            <div class="modal-section">
                <h4>Visi & Misi</h4>
                <p>${this.dataset.visi}</p>
            </div>

            <div class="modal-section">
                <h4>Kepemimpinan</h4>
                <p>${this.dataset.kepemimpinan}</p>
            </div>

            <div class="modal-section">
                <h4>Pengalaman</h4>
                <p>${this.dataset.pengalaman}</p>
            </div>

            <div class="modal-section">
                <h4>Komunikasi</h4>
                <p>${this.dataset.komunikasi}</p>
            </div>

            <div class="modal-section">
                <h4>Integritas</h4>
                <p>${this.dataset.integritas}</p>
            </div>

        `;
    });

});

closeModal.addEventListener('click', ()=>{

    modal.style.display = 'none';

});

window.addEventListener('click', (e)=>{

    if(e.target == modal){
        modal.style.display = 'none';
    }

});

</script>
</body>
</html>