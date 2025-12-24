<?php
session_start();
include 'includes/db.php';
if (isset($_SESSION['login'])) { header("Location: index.php"); exit; }

$message = "";
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($pass, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama_lengkap'];
            $_SESSION['role'] = $row['role']; // <--- PENTING: SIMPAN ROLE

            // Redirect sesuai role
            if ($row['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }
    }
    $message = "<div class='alert alert-error'>Username atau Password salah!</div>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Login - Nusantara Coffee</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="auth-body">
    <div class="auth-card">
        <h2>Selamat Datang</h2>
        <p class="muted" style="color:#eee">Silakan login untuk belanja</p>
        <?= $message ?>
        <form method="POST">
            <div class="form-group"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
            <div class="form-group"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
            <button type="submit" name="login" class="btn-auth">MASUK</button>
        </form>
        <p style="font-size:0.9rem; margin-top:1rem;">Belum punya akun? <a href="register.php" style="color:#ffd700; font-weight:bold;">Daftar</a></p>
        <p><a href="index.php" style="color:white; font-size:0.8rem; text-decoration:underline;">Kembali ke Beranda</a></p>
    </div>
</body>
</html>