<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
include 'config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nomor_urut = (int)$_POST['nomor_urut'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $visi_misi = mysqli_real_escape_string($conn, $_POST['visi_misi']);
    $kepemimpinan = mysqli_real_escape_string($conn, $_POST['kepemimpinan']);
    $pengalaman = mysqli_real_escape_string($conn, $_POST['pengalaman']);
    $komunikasi = mysqli_real_escape_string($conn, $_POST['komunikasi']);
    $integritas = mysqli_real_escape_string($conn, $_POST['integritas']);
    $ipk = (float)$_POST['ipk'];
    $foto = '';
    
    // Upload foto
    if ($_FILES['foto']['name']) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $foto = $target_dir . time() . '_' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }
    
    $query = "INSERT INTO kandidat (nomor_urut, nama, visi_misi, kepemimpinan, pengalaman, komunikasi, integritas, ipk, foto) 
              VALUES ('$nomor_urut', '$nama', '$visi_misi', '$kepemimpinan', '$pengalaman', '$komunikasi', '$integritas', '$ipk', '$foto')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Kandidat berhasil ditambahkan!";
        // Reset form (optional, bisa redirect ke dashboard)
        echo "<script>setTimeout(function(){ window.location.href='dashboard_admin.php'; }, 1500);</script>";
    } else {
        $error = "Gagal menambahkan: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kandidat Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; padding: 30px; }
        .container { max-width: 800px; margin: auto; background: white; border-radius: 24px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 24px; color: #1f2937; }
        .form-group { margin-bottom: 20px; }
        label { font-weight: 600; display: block; margin-bottom: 6px; color: #374151; }
        input, textarea { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 16px; font-family: inherit; font-size: 14px; }
        textarea { resize: vertical; }
        button { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px 24px; border-radius: 40px; font-weight: 600; cursor: pointer; margin-right: 12px; }
        .btn-back { background: #9ca3af; text-decoration: none; display: inline-block; text-align: center; padding: 12px 24px; border-radius: 40px; color: white; font-weight: 600; }
        .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 16px; margin-bottom: 20px; }
        .success { background: #dcfce7; color: #16a34a; padding: 12px; border-radius: 16px; margin-bottom: 20px; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>
<div class="container">
    <h2>➕ Tambah Kandidat Baru</h2>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= $success ?> Redirecting...</div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nomor Urut *</label>
            <input type="number" name="nomor_urut" required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap *</label>
            <input type="text" name="nama" required>
        </div>
        <div class="form-group">
            <label>Visi & Misi *</label>
            <textarea name="visi_misi" rows="3" required placeholder="Tuliskan visi dan misi kandidat"></textarea>
        </div>
        <div class="form-group">
            <label>Kepemimpinan (deskripsi) *</label>
            <textarea name="kepemimpinan" rows="2" required placeholder="Contoh: Pengalaman sebagai ketua organisasi, gaya kepemimpinan, dll."></textarea>
        </div>
        <div class="form-group">
            <label>Pengalaman Organisasi *</label>
            <textarea name="pengalaman" rows="2" required placeholder="Riwayat organisasi, kepanitiaan, prestasi"></textarea>
        </div>
        <div class="form-group">
            <label>Komunikasi *</label>
            <textarea name="komunikasi" rows="2" required placeholder="Kemampuan public speaking, negosiasi, presentasi"></textarea>
        </div>
        <div class="form-group">
            <label>Integritas *</label>
            <textarea name="integritas" rows="2" required placeholder="Jujur, amanah, konsisten"></textarea>
        </div>
        <div class="form-group">
            <label>IPK (skala 4.00)</label>
            <input type="number" step="0.01" name="ipk" placeholder="0.00 - 4.00" required>
        </div>
        <div class="form-group">
            <label>Foto (bulat formal) - opsional</label>
            <input type="file" name="foto" accept="image/*">
        </div>
        <button type="submit" name="submit">Simpan Kandidat</button>
        <a href="dashboard_admin.php" class="btn-back">Batal</a>
    </form>
</div>
</body>
</html>