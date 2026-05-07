<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
include 'config/db.php';
$id = (int)$_GET['id'];
$kand = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kandidat WHERE id = $id"));
if (!$kand) { header("Location: dashboard_admin.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_urut = (int)$_POST['nomor_urut'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $visi_misi = mysqli_real_escape_string($conn, $_POST['visi_misi']);
    $kepemimpinan = mysqli_real_escape_string($conn, $_POST['kepemimpinan']);
    $pengalaman = mysqli_real_escape_string($conn, $_POST['pengalaman']);
    $komunikasi = mysqli_real_escape_string($conn, $_POST['komunikasi']);
    $integritas = mysqli_real_escape_string($conn, $_POST['integritas']);
    $ipk = (float)$_POST['ipk'];
    $foto = $kand['foto'];
    if ($_FILES['foto']['name']) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $foto = $target_dir . time() . '_' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }
    $query = "UPDATE kandidat SET 
              nomor_urut='$nomor_urut', 
              nama='$nama', 
              visi_misi='$visi_misi',
              kepemimpinan='$kepemimpinan',
              pengalaman='$pengalaman',
              komunikasi='$komunikasi',
              integritas='$integritas',
              ipk='$ipk',
              foto='$foto' 
              WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: dashboard_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kandidat Lengkap</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; padding: 20px; }
        .card { max-width: 800px; margin: auto; background: white; border-radius: 24px; padding: 32px; }
        .form-group { margin-bottom: 20px; }
        label { font-weight: 600; display: block; margin-bottom: 6px; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 12px; }
        button { background: #667eea; color: white; border: none; padding: 12px 24px; border-radius: 40px; cursor: pointer; }
    </style>
</head>
<body>
<div class="card">
    <h2>Edit Profil Kandidat</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Nomor Urut</label><input type="number" name="nomor_urut" value="<?= $kand['nomor_urut'] ?>" required></div>
        <div class="form-group"><label>Nama Lengkap</label><input type="text" name="nama" value="<?= htmlspecialchars($kand['nama']) ?>" required></div>
        <div class="form-group"><label>Visi & Misi</label><textarea name="visi_misi" rows="3"><?= htmlspecialchars($kand['visi_misi']) ?></textarea></div>
        <div class="form-group"><label>Kepemimpinan (deskripsi)</label><textarea name="kepemimpinan" rows="2"><?= htmlspecialchars($kand['kepemimpinan']) ?></textarea></div>
        <div class="form-group"><label>Pengalaman Organisasi</label><textarea name="pengalaman" rows="2"><?= htmlspecialchars($kand['pengalaman']) ?></textarea></div>
        <div class="form-group"><label>Komunikasi</label><textarea name="komunikasi" rows="2"><?= htmlspecialchars($kand['komunikasi']) ?></textarea></div>
        <div class="form-group"><label>Integritas</label><textarea name="integritas" rows="2"><?= htmlspecialchars($kand['integritas']) ?></textarea></div>
        <div class="form-group"><label>IPK</label><input type="number" step="0.01" name="ipk" value="<?= $kand['ipk'] ?>" placeholder="0.00 - 4.00"></div>
        <div class="form-group"><label>Foto (bulat formal)</label><input type="file" name="foto" accept="image/*"></div>
        <?php if($kand['foto']): ?>
            <img src="<?= $kand['foto'] ?>" width="80" style="border-radius: 50%; margin-bottom: 16px;">
        <?php endif; ?>
        <button type="submit">Simpan Perubahan</button>
        <a href="dashboard_admin.php" style="margin-left: 16px;">Batal</a>
    </form>
</div>
</body>
</html>