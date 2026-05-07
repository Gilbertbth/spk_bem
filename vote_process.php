<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id_kandidat = (int)$_POST['id_kandidat'];
$nilai_array = $_POST['nilai']; // array [id_kriteria => nilai]
$nilai_ipk = (float)$_POST['nilai_ipk'];

// Cek apakah sudah pernah vote
$check = mysqli_query($conn, "SELECT has_voted FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($check);
if ($user['has_voted']) {
    header("Location: dashboard_mahasiswa.php?page=beranda&msg=already");
    exit();
}

// Dapatkan id_kriteria untuk IPK
$ipk_kriteria = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM kriteria WHERE nama = 'IPK'"));
if (!$ipk_kriteria) {
    // Jika tidak ada kriteria IPK, buat sendiri (fallback)
    $ipk_kriteria_id = null;
} else {
    $ipk_kriteria_id = $ipk_kriteria['id'];
}

// Konversi IPK (skala 0-4) ke nilai 1-100
$nilai_ipk_100 = round(($nilai_ipk / 4) * 100, 2);
if ($nilai_ipk_100 < 1) $nilai_ipk_100 = 1;
if ($nilai_ipk_100 > 100) $nilai_ipk_100 = 100;

mysqli_begin_transaction($conn);
try {
    // Simpan penilaian untuk kriteria yang diisi mahasiswa
    foreach ($nilai_array as $id_kriteria => $nilai) {
        $nilai = (int)$nilai;
        if ($nilai < 1 || $nilai > 100) throw new Exception("Nilai tidak valid");
        $query = "INSERT INTO penilaian (id_mahasiswa, id_kandidat, id_kriteria, nilai) 
                  VALUES ($user_id, $id_kandidat, $id_kriteria, $nilai)";
        mysqli_query($conn, $query);
    }
    // Simpan penilaian untuk IPK (otomatis)
    if ($ipk_kriteria_id) {
        $query_ipk = "INSERT INTO penilaian (id_mahasiswa, id_kandidat, id_kriteria, nilai) 
                      VALUES ($user_id, $id_kandidat, $ipk_kriteria_id, $nilai_ipk_100)";
        mysqli_query($conn, $query_ipk);
    }
    // Update status sudah memilih
    mysqli_query($conn, "UPDATE users SET has_voted = 1 WHERE id = $user_id");
    mysqli_commit($conn);
    header("Location: dashboard_mahasiswa.php?page=beranda&success=1");
} catch (Exception $e) {
    mysqli_rollback($conn);
    header("Location: dashboard_mahasiswa.php?page=kandidat&error=1");
}
?>