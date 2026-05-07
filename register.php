<?php
session_start();
if (isset($_SESSION['user_id'])) header("Location: index.php");
include 'config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);

    $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username' OR nim = '$nim'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username atau NIM sudah terdaftar!";
    } else {
        $query = "INSERT INTO users (username, password, nama, nim, role, has_voted) 
                  VALUES ('$username', '$password', '$nama', '$nim', 'mahasiswa', 0)";
        if (mysqli_query($conn, $query)) {
            $success = "Pendaftaran berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | SPK Pemilihan Ketua BEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;

            background-image: url('./uploads/uhknp.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Overlay */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 0;
        }

        /* Card */
        .register-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);

            border-radius: 28px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.2);

            width: 100%;
            max-width: 480px;
            padding: 40px;

            position: relative;
            z-index: 1;

            animation: fadeUp 0.7s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: white;
            font-size: 32px;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: white;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 14px 18px;

            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.3);

            background: rgba(255, 255, 255, 0.2);

            color: white;
            font-size: 15px;

            transition: 0.3s;
            backdrop-filter: blur(5px);
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        input:focus {
            outline: none;
            border-color: white;
            background: rgba(255, 255, 255, 0.28);

            box-shadow:
                0 0 0 3px rgba(255, 255, 255, 0.15);
        }

        button {
            width: 100%;
            padding: 14px;

            border: none;
            border-radius: 16px;

            background: linear-gradient(135deg, #4f46e5, #7c3aed);

            color: white;
            font-size: 16px;
            font-weight: 700;

            cursor: pointer;
            transition: 0.3s;

            margin-top: 8px;
        }

        button:hover {
            transform: translateY(-2px);

            background: linear-gradient(135deg, #4338ca, #6d28d9);

            box-shadow:
                0 8px 20px rgba(79, 70, 229, 0.4);
        }

        .error {
            background: rgba(220, 38, 38, 0.2);
            color: white;

            border: 1px solid rgba(255, 255, 255, 0.2);

            padding: 12px 16px;
            border-radius: 16px;

            margin-bottom: 20px;

            text-align: center;
            backdrop-filter: blur(5px);
        }

        .success {
            background: rgba(22, 163, 74, 0.2);
            color: white;

            border: 1px solid rgba(255, 255, 255, 0.2);

            padding: 12px 16px;
            border-radius: 16px;

            margin-bottom: 20px;

            text-align: center;
            backdrop-filter: blur(5px);
        }

        .success a {
            color: white;
            font-weight: 700;
        }

        .login-link {
            text-align: center;
            margin-top: 26px;

            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .login-link a {
            color: white;
            font-weight: 700;
            text-decoration: none;

            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            padding-bottom: 2px;
        }

        .login-link a:hover {
            border-bottom-color: white;
        }

        @media(max-width:480px) {

            .register-card {
                padding: 30px 22px;
                border-radius: 24px;
            }

            h2 {
                font-size: 28px;
            }

        }
    </style>
</head>

<body>
    <div class="register-card">
        <h2>📝 SPK BEM</h2>
        <p class="subtitle">Daftar Mahasiswa UHKBPNP</p>
        <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= $success ?> <a href="index.php" style="color:#16a34a;">Login sekarang</a></div><?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Nama Lengkap</label><input type="text" name="nama" required></div>
            <div class="form-group"><label>NIM</label><input type="text" name="nim" required></div>
            <button type="submit">Daftar</button>
        </form>
        <div class="login-link">Sudah punya akun? <a href="index.php">Login</a></div>
    </div>
</body>

</html>