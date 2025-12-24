<?php
session_start();
include 'includes/db.php';
// 1. Cek Login
if (!isset($_SESSION['login'])) { 
    header("Location: login.php"); 
    exit; 
}

// 2. Cek Role (HANYA DISINI KITA MELARANG USER BIASA)
// Jika role BUKAN seller DAN BUKAN admin -> Tendang ke Marketplace
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'seller' && $_SESSION['role'] != 'admin')) {
    echo "<script>
            alert('Maaf, akun Anda adalah PEMBELI. Anda tidak memiliki akses untuk menjual produk.'); 
            window.location.href='marketplace.php';
          </script>";
    exit;
}

if (isset($_POST['submit'])) {
    $uid = $_SESSION['user_id'];
    $nama = $_POST['nama'];
    $daerah = $_POST['daerah'];
    $harga = $_POST['harga'];
    
    // PROSES UPLOAD KE DATABASE (BLOB)
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $fileContent = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
        
        $query = "INSERT INTO products (user_id, nama_produk, daerah, harga, stok, gambar) 
                  VALUES ('$uid', '$nama', '$daerah', '$harga', 1, '$fileContent')";
                  
        if(mysqli_query($conn, $query)){
            header("Location: marketplace.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Jual Kopi - Nusantara Coffee</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">
    <header class="site-header" style="position: fixed; top: 0; width: 100%;">
      <div class="container-flex">
          <a class="brand" href="index.php">
            <img src="assets/images/logo.jpg" alt="Logo" width="40" height="40" style="border-radius:50%;" />
            <span class="brand-text">Nusantara Coffee</span>
          </a>
          <nav class="nav">
            <a href="marketplace.php" class="nav-link">Kembali</a>
          </nav>
      </div>
    </header>

    <div class="auth-card" style="margin-top: 80px;">
        <h2>Jual Produk</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group"><input type="text" name="nama" class="form-control" placeholder="Nama Kopi" required></div>
            <div class="form-group"><input type="text" name="daerah" class="form-control" placeholder="Daerah Asal" required></div>
            <div class="form-group"><input type="number" name="harga" class="form-control" placeholder="Harga" required></div>
            <div class="form-group"><p style="text-align:left; color:white; margin:0;">Foto:</p><input type="file" name="gambar" class="form-control" required></div>
            <button type="submit" name="submit" class="btn-auth">UPLOAD</button>
        </form>
        <p><a href="marketplace.php" style="color:white;">Batal</a></p>
    </div>
</body>
</html>