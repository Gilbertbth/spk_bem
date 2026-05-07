<?php
include 'config/db.php';

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_POST['reset'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $newPassword = $_POST['password'];

    // Hash password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Query update
    $query = "UPDATE users SET password='$hashedPassword' WHERE username='$username'";

    $result = mysqli_query($conn, $query);

    if ($result) {

        if (mysqli_affected_rows($conn) > 0) {
            echo "Password berhasil direset!";
        } else {
            echo "Username tidak ditemukan!";
        }

    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>

<h2>Reset Password</h2>

<form method="POST">

    <input type="text" name="username" placeholder="Masukkan Username" required>
    <br><br>

    <input type="password" name="password" placeholder="Password Baru" required>
    <br><br>

    <button type="submit" name="reset">Reset Password</button>

</form>

</body>
</html>