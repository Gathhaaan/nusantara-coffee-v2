<?php
session_start();
include 'includes/db.php';

// Cek Login 
if (!isset($_SESSION['login'])) {
    echo "<script>alert('Silakan LOGIN terlebih dahulu!'); window.location.href = 'login.php';</script>";
    exit;
}

// --- LOGIKA PENCARIAN & FILTER CANGGIH ---
$where_clauses = [];
$pesan_filter = "";

// 1. Cek Pencarian Keyword
if (isset($_GET['cari']) && !empty($_GET['cari'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['cari']);
    // Cari di nama produk ATAU daerah
    $where_clauses[] = "(products.nama_produk LIKE '%$keyword%' OR products.daerah LIKE '%$keyword%')";
    $pesan_filter .= "Kata kunci: <strong>" . htmlspecialchars($keyword) . "</strong>";
}

// 2. Cek Filter Wilayah
if (isset($_GET['filter']) && !empty($_GET['filter']) && $_GET['filter'] != 'all') {
    $filter = mysqli_real_escape_string($conn, $_GET['filter']);
    $region_query = "";
    
    // Mapping Filter ke Database (Kolom daerah)
    switch ($filter) {
        case 'sumatra': $region_query = "products.daerah LIKE '%Sumatra%' OR products.daerah LIKE '%Aceh%' OR products.daerah LIKE '%Lampung%'"; break;
        case 'jawa':    $region_query = "products.daerah LIKE '%Jawa%' OR products.daerah LIKE '%Bandung%' OR products.daerah LIKE '%Malang%'"; break;
        case 'kalimantan': $region_query = "products.daerah LIKE '%Kalimantan%'"; break;
        case 'sulawesi': $region_query = "products.daerah LIKE '%Sulawesi%' OR products.daerah LIKE '%Toraja%'"; break;
        case 'bali':    $region_query = "products.daerah LIKE '%Bali%' OR products.daerah LIKE '%Nusa%' OR products.daerah LIKE '%Flores%' OR products.daerah LIKE '%Lombok%'"; break;
        case 'papua':   $region_query = "products.daerah LIKE '%Papua%'"; break;
    }

    if ($region_query) {
        $where_clauses[] = "($region_query)";
        $separator = $pesan_filter ? " & " : "";
        $pesan_filter .= $separator . "Wilayah: <strong>" . ucfirst($filter) . "</strong>";
    }
}

// Gabungkan Semua Kondisi SQL
$sql_where = "";
if (count($where_clauses) > 0) {
    $sql_where = "WHERE " . implode(" AND ", $where_clauses);
}

// Eksekusi Query
$query = "SELECT products.*, users.nama_lengkap FROM products JOIN users ON products.user_id = users.id $sql_where ORDER BY products.id DESC";
$result = mysqli_query($conn, $query);

// Fungsi Bantu untuk Link Filter (Agar search tidak hilang saat klik filter)
function buildUrl($filterVal) {
    $params = $_GET;
    $params['filter'] = $filterVal;
    return "?" . http_build_query($params);
}
$activeFilter = $_GET['filter'] ?? 'all';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Marketplace - Nusantara Coffee</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Style Tambahan Khusus Halaman Ini */
        .search-container { max-width: 600px; margin: 0 auto 2rem; display: flex; gap: 10px; }
        .search-input { flex: 1; padding: 12px 20px; border-radius: 50px; border: 1px solid #ddd; outline: none; font-size: 1rem; transition: 0.3s; }
        .search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(47, 107, 59, 0.1); }
        .search-alert { background: #e0f2fe; color: #0369a1; padding: 1rem; text-align: center; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #bae6fd; max-width: 800px; margin-left:auto; margin-right:auto; }
        .reset-link { margin-left: 10px; color: #dc2626; font-weight: bold; text-decoration: underline; font-size: 0.9rem; }
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
            <a href="article.php" class="nav-link">Artikel</a>
            <a href="calculator.php" class="nav-link">Kalkulator</a>
            <a href="matcher.php" class="nav-link">Cek Cocoklogi</a>
            <a href="marketplace.php" class="nav-link is-active">Marketplace</a>
            <a href="contact.php" class="nav-link">Kontak</a>
            <a href="logout.php" class="nav-link nav-btn-logout">Logout</a>
          </nav>
      </div>
    </header>

    <div style="background:#f9f9f9; padding:3rem 1rem; text-align:center; margin-bottom:2rem;">
        <h1 class="section-title">Pasar Kopi</h1>
        <p class="muted">Halo, <strong><?= $_SESSION['nama'] ?? 'User'; ?></strong>! Temukan biji kopi terbaik di sini.</p>
        
       <?php 
            if (isset($_SESSION['role']) && ($_SESSION['role'] == 'seller' || $_SESSION['role'] == 'admin')) : 
            ?>
                <div style="margin-top: 1.5rem;">
                    <a href="tambah_produk.php" class="btn btn-primary" style="background:#d97706; border:none;">+ Jual Kopi Saya</a>
                </div>
        <?php endif; ?>
    </div>

    <div class="container">
        
        <form method="GET" action="marketplace.php" class="search-container">
            <input type="text" name="cari" class="search-input" placeholder="Cari nama kopi atau daerah..." value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>">
            <?php if(isset($_GET['filter'])): ?><input type="hidden" name="filter" value="<?= htmlspecialchars($_GET['filter']) ?>"><?php endif; ?>
            <button type="submit" class="btn btn-primary">Cari 🔍</button>
        </form>

        <div class="filter-tabs" style="margin-bottom: 2rem;">
            <a href="<?= buildUrl('all') ?>" class="filter-btn <?= $activeFilter == 'all' ? 'active' : '' ?>">Semua</a>
            <a href="<?= buildUrl('sumatra') ?>" class="filter-btn <?= $activeFilter == 'sumatra' ? 'active' : '' ?>">Sumatra</a>
            <a href="<?= buildUrl('jawa') ?>" class="filter-btn <?= $activeFilter == 'jawa' ? 'active' : '' ?>">Jawa</a>
            <a href="<?= buildUrl('kalimantan') ?>" class="filter-btn <?= $activeFilter == 'kalimantan' ? 'active' : '' ?>">Kalimantan</a>
            <a href="<?= buildUrl('sulawesi') ?>" class="filter-btn <?= $activeFilter == 'sulawesi' ? 'active' : '' ?>">Sulawesi</a>
            <a href="<?= buildUrl('bali') ?>" class="filter-btn <?= $activeFilter == 'bali' ? 'active' : '' ?>">Bali & Nusa</a>
            <a href="<?= buildUrl('papua') ?>" class="filter-btn <?= $activeFilter == 'papua' ? 'active' : '' ?>">Papua</a>
        </div>

        <?php if($pesan_filter) : ?>
            <div class="search-alert">
                Menampilkan hasil: <?= $pesan_filter ?> 
                <a href="marketplace.php" class="reset-link">(Reset Semua)</a>
            </div>
        <?php endif; ?>

        <div class="grid">
            <?php if (mysqli_num_rows($result) < 1) : ?>
                <div style="grid-column: 1/-1; text-align:center; padding: 4rem; background:white; border-radius:12px; border:1px solid #eee;">
                    <div style="font-size:3rem;">🍃</div>
                    <h3>Oops, Kopi tidak ditemukan.</h3>
                    <p class="muted">Coba gunakan kata kunci lain atau pilih wilayah yang berbeda.</p>
                    <a href="marketplace.php" class="btn btn-secondary" style="margin-top:1rem;">Lihat Semua Produk</a>
                </div>
            <?php endif; ?>

            <?php while($row = mysqli_fetch_assoc($result)) : 
                // CONVERT BLOB TO BASE64
                $gambarSrc = 'assets/images/logo.jpg';
                if ($row['gambar']) {
                    $gambarSrc = 'data:image/jpeg;base64,' . base64_encode($row['gambar']);
                }
            ?>
                <div class="product-card">
                    <img src="<?= $gambarSrc ?>" class="product-img" alt="<?= $row['nama_produk']; ?>">
                    <div class="product-body">
                        <span class="muted" style="font-size:0.8rem; text-transform:uppercase; color:var(--accent); font-weight:700;">📍 <?= $row['daerah']; ?></span>
                        <h3 style="margin: 0.5rem 0;"><?= $row['nama_produk']; ?></h3>
                        <p class="muted" style="font-size:0.9rem;">Penjual: <?= $row['nama_lengkap']; ?></p>
                        
                        <div style="margin-top:auto; padding-top:1rem;">
                            <p class="product-price" style="font-size:1.2rem; color:var(--primary); font-weight:bold;">Rp <?= number_format($row['harga']); ?></p>
                            <a href="https://wa.me/6281232678520/?text=Halo, saya tertarik membeli kopi <?= $row['nama_produk']; ?> dari Nusantara Coffee." target="_blank" class="btn btn-primary" style="width:100%;">Beli via WhatsApp</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <footer class="site-footer"><p>&copy; <?= date('Y'); ?> Nusantara Coffee.</p></footer>
</body>
</html>