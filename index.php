<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') header("Location: dashboard_admin.php");
    else header("Location: dashboard_mahasiswa.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SPK Pemilihan Ketua BEM</title>
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

        /* Overlay gelap */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 440px;
            padding: 40px;
            position: relative;
            z-index: 1;
            animation: floatUp 0.8s ease-out;
        }

        @keyframes floatUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
            color: white;
        }

        .logo h1 {
            font-size: 32px;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .logo p {
            color: rgba(255, 255, 255, 0.9);
            margin-top: 8px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: white;
        }

        input {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(5px);
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        input:focus {
            outline: none;
            border-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.3);
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }

        button:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
        }

        .error {
            background: rgba(220, 38, 38, 0.2);
            backdrop-filter: blur(5px);
            color: #fff;
            padding: 12px 16px;
            border-radius: 16px;
            margin-bottom: 24px;
            font-size: 14px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .register-link {
            text-align: center;
            margin-top: 28px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .register-link a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            padding-bottom: 2px;
            transition: 0.2s;
        }

        .register-link a:hover {
            border-bottom-color: #fff;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
                border-radius: 24px;
            }

            .logo h1 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="logo">
            <h1>🗳️ SPK BEM</h1>
            <p>SPK Pemilihan Ketua BEM UHKBPNP</p>
        </div>
        <?php if (isset($_GET['error'])): ?>
            <div class="error">❌ Username atau password salah</div>
        <?php endif; ?>
        <?php if (isset($_GET['msg'])): ?>
            <div class="error">⚠️ <?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>
        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit">Masuk</button>
        </form>
        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar sebagai Mahasiswa</a>
        </div>
    </div>
</body>

</html>