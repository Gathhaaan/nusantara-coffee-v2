<?php
session_start();
include 'includes/db.php';
$message = "";

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // Ambil input role

    // Validasi role agar tidak di-hack (Hanya boleh user/seller)
    if (!in_array($role, ['user', 'seller'])) {
        $role = 'user';
    }

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    if (mysqli_num_rows($cek) > 0) {
        $message = "<div class='alert alert-error'>Username sudah dipakai!</div>";
    } else {
        $query = "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('$nama', '$user', '$pass', '$role')";
        if(mysqli_query($conn, $query)) {
            $message = "<div class='alert alert-success'>Berhasil! Silakan <a href='login.php'>Login</a></div>";
        } else {
            $message = "<div class='alert alert-error'>Gagal: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Daftar - Nusantara Coffee</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="auth-body">
    <div class="auth-card">
        <h2>Buat Akun</h2>
        <?= $message ?>
        <form method="POST">
            <div class="form-group"><input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required></div>
            <div class="form-group"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
            <div class="form-group"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
            
            <div class="form-group" style="text-align:left; color:white;">
                <label>Daftar Sebagai:</label>
                <select name="role" class="form-control" style="margin-top:5px;">
                    <option value="user">Pembeli (User Biasa)</option>
                    <option value="seller">Penjual (Bisa Posting Produk)</option>
                </select>
            </div>

            <button type="submit" name="register" class="btn-auth">DAFTAR</button>
        </form>
        <p style="font-size:0.9rem; margin-top:1rem;">Sudah punya akun? <a href="login.php" style="color:#ffd700; font-weight:bold;">Login</a></p>
    </div>
</body>
</html>