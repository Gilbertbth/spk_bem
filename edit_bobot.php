<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
include 'config/db.php';
$id = (int)$_GET['id'];
$krit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kriteria WHERE id = $id"));
if (!$krit) { header("Location: dashboard_admin.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bobot = (float)$_POST['bobot'] / 100;
    mysqli_query($conn, "UPDATE kriteria SET bobot = '$bobot' WHERE id = $id");
    header("Location: dashboard_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Bobot Kriteria</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .card { background: white; border-radius: 24px; padding: 32px; width: 400px; }
        .form-group { margin-bottom: 20px; }
        label { font-weight: 500; }
        input { width: 100%; padding: 10px; border-radius: 10px; border: 1px solid #ccc; margin-top: 8px; }
        button { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px; border-radius: 12px; width: 100%; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Ubah Bobot: <?= $krit['nama'] ?></h2>
        <form method="POST">
            <div class="form-group">
                <label>Bobot (%)</label>
                <input type="number" step="0.01" name="bobot" value="<?= $krit['bobot'] * 100 ?>" required>
            </div>
            <button type="submit">Simpan</button>
        </form>
        <a href="dashboard_admin.php" style="display:block; margin-top:16px; text-align:center;">Kembali</a>
    </div>
</body>
</html>