<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'spk_bem';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8");
?>