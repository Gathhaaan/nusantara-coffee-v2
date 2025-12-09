<?php
session_start();
include 'includes/db.php';

// Cek Login
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }

// LOGIKA PENCARIAN
$where_clause = "";
$pesan_pencarian = "";

if (isset($_GET['cari'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['cari']);
    $where_clause = "WHERE products.nama_produk LIKE '%$keyword%' 
                     OR products.daerah LIKE '%$keyword%' 
                     OR products.deskripsi LIKE '%$keyword%'";
    $pesan_pencarian = "Menampilkan hasil untuk: <strong>'$keyword'</strong>";
}

$query = "SELECT products.*, users.nama_lengkap 
          FROM products 
          JOIN users ON products.user_id = users.id 
          $where_clause 
          ORDER BY products.id DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Marketplace - Nusantara Coffee</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .search-alert { background: #e0f2fe; color: #0369a1; padding: 1rem; text-align: center; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #bae6fd; }
        .reset-link { margin-left: 10px; color: #dc2626; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <header class="site-header">
      <div class="container-flex">
          <a class="brand" href="index.php">
            <img src="assets/images/logo.jpg" alt="Logo" width="40" height="40" style="border-radius:50%;" />
            <span class="brand-text">Nusantara Coffee</span>
          </a>
          <nav class="nav">
            <a href="index.php" class="nav-link">Beranda</a>
            <a href="calculator.php" class="nav-link">Kalkulator</a>
            <a href="matcher.php" class="nav-link">Cek Cocoklogi</a>
            <a href="article.php" class="nav-link">Artikel</a>
            
            <?php if (isset($_SESSION['login'])) : ?>
                <a href="marketplace.php" class="nav-link is-active">Marketplace</a>
                <a href="logout.php" class="nav-link nav-btn-logout">Logout</a>
            <?php else : ?>
                <a href="contact.php" class="nav-link">Kontak</a>
                <a href="login.php" class="nav-link nav-btn-login">Login</a>
            <?php endif; ?>
          </nav>
      </div>
    </header>

    <div style="background:#f9f9f9; padding:3rem 1rem; text-align:center; margin-bottom:2rem;">
        <h1 class="section-title">Pasar Kopi</h1>
        <p class="muted">Temukan biji kopi terbaik dari seluruh nusantara.</p>
        <div style="margin-top: 1.5rem; display: flex; gap: 10px; justify-content: center;">
            <a href="tambah_produk.php" class="btn btn-primary" style="background:#d97706;">+ Jual Kopi Saya</a>
            <a href="matcher.php" class="btn btn-secondary">🔍 Bantu Saya Memilih</a>
        </div>
    </div>

    <div class="container">
        <?php if($pesan_pencarian) : ?>
            <div class="search-alert">
                <?= $pesan_pencarian ?> 
                <a href="marketplace.php" class="reset-link">(Reset Filter)</a>
            </div>
        <?php endif; ?>

        <div class="grid">
            <?php if (mysqli_num_rows($result) < 1) : ?>
                <div style="grid-column: 1/-1; text-align:center; padding: 3rem;">
                    <h3>Oops, Kopi tidak ditemukan ☕</h3>
                    <p class="muted">Coba kata kunci lain atau reset filter.</p>
                </div>
            <?php endif; ?>

            <?php while($row = mysqli_fetch_assoc($result)) : ?>
                <div class="product-card">
                    <img src="uploads/<?= $row['gambar']; ?>" class="product-img">
                    <div class="product-body">
                        <span class="muted" style="font-size:0.8rem; text-transform:uppercase; color:var(--accent);">📍 <?= $row['daerah']; ?></span>
                        <h3><?= $row['nama_produk']; ?></h3>
                        <p class="muted">Penjual: <?= $row['nama_lengkap']; ?></p>
                        <p class="product-price">Rp <?= number_format($row['harga']); ?></p>
                        <a href="https://wa.me/?text=Saya mau beli <?= $row['nama_produk']; ?>" target="_blank" class="btn btn-primary">Beli via WA</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>