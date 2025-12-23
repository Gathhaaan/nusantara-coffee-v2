<?php
session_start();
include 'includes/db.php';

$rekomendasi = null;
$pesan_ai = "";

// LOGIKA AI SEDERHANA
if (isset($_POST['analyze'])) {
    $rasa = $_POST['rasa'];
    $suasana = $_POST['suasana'];
    
    // Tentukan Keyword Pencarian
    $keyword = ($rasa == 'asam') ? "Gayo" : (($rasa == 'pahit') ? "Toraja" : "Lintong");
    
    // Tentukan Pesan
    if ($rasa == 'asam') {
        $pesan_ai = "Anda suka karakter <strong>Fruity & Bright</strong>. Saran: Sumatra (Gayo) atau Bali.";
    } elseif ($rasa == 'pahit') {
        $pesan_ai = "Anda suka kopi <strong>Bold & Strong</strong>. Saran: Sulawesi (Toraja) atau Jawa.";
    } else {
        $pesan_ai = "Anda suka keseimbangan. Saran: Lintong atau Flores Bajawa.";
    }

    // Cari Produk di Database
    $query = "SELECT * FROM products WHERE nama_produk LIKE '%$keyword%' OR daerah LIKE '%$keyword%' LIMIT 3";
    $rekomendasi = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>AI Coffee Matcher - Nusantara Coffee</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Sedikit style tambahan khusus halaman ini */
        .matcher-container { max-width: 800px; margin: 0 auto; padding: 3rem 1rem; }
        .result-box { background: #dcfce7; padding: 2rem; border-radius: 12px; border: 1px solid #86efac; text-align: center; margin-bottom: 3rem; }
    </style>
</head>
<body style="background: #fdfbf7;">
    <header class="site-header">
      <div class="container-flex">
          <a class="brand" href="index.php">
            <img src="assets/images/logo.jpg" alt="Logo" width="40" height="40" style="border-radius:50%;" />
            <span class="brand-text">Nusantara Coffee</span>
          </a>
          <nav class="nav">
            <a href="index.php" class="nav-link">Beranda</a>
            <a href="article.php" class="nav-link is-active">Artikel</a>
            <a href="calculator.php" class="nav-link">Kalkulator</a>
            <a href="matcher.php" class="nav-link">Cek Cocoklogi</a>
            <a href="marketplace.php" class="nav-link">Marketplace</a>
            <a href="contact.php" class="nav-link">Kontak</a>
            
            <?php if (isset($_SESSION['login'])) : ?>
                <a href="logout.php" class="nav-link nav-btn-logout">Logout</a>
            <?php else : ?>
                <a href="login.php" class="nav-link nav-btn-login">Login</a>
            <?php endif; ?>
          </nav>
      </div>
    </header>

    <main class="matcher-container">
        <div class="text-center" style="margin-bottom: 3rem;">
            <h1 class="section-title">✨ AI Coffee Matcher</h1>
            <p class="muted">Jawab 2 pertanyaan ini, biarkan sistem kami memilihkan untuk Anda.</p>
        </div>

        <?php if (!$rekomendasi) : ?>
        <div class="feature-card" style="padding: 2rem; max-width: 600px; margin: 0 auto; text-align: left;">
            <form method="POST">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display:block; font-weight:600; margin-bottom:0.5rem;">1. Rasa kopi seperti apa?</label>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <label style="background: #f3f4f6; padding: 1rem; border-radius: 8px; cursor: pointer; flex: 1;">
                            <input type="radio" name="rasa" value="asam" required> 🍋 Masam Segar
                        </label>
                        <label style="background: #f3f4f6; padding: 1rem; border-radius: 8px; cursor: pointer; flex: 1;">
                            <input type="radio" name="rasa" value="pahit"> 🍫 Pahit Mantap
                        </label>
                        <label style="background: #f3f4f6; padding: 1rem; border-radius: 8px; cursor: pointer; flex: 1;">
                            <input type="radio" name="rasa" value="seimbang"> ⚖️ Seimbang
                        </label>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label style="display:block; font-weight:600; margin-bottom:0.5rem;">2. Kapan Anda minum kopi?</label>
                    <select name="suasana" style="width:100%; padding:0.8rem; border-radius:8px; border:1px solid #ddd;">
                        <option value="pagi">Pagi Hari (Butuh Melek)</option>
                        <option value="kerja">Saat Kerja (Butuh Fokus)</option>
                        <option value="santai">Sore Santai</option>
                    </select>
                </div>

                <button type="submit" name="analyze" class="btn btn-primary" style="width: 100%;">ANALISA SELERA SAYA 🤖</button>
            </form>
        </div>

        <?php else : ?>
        
        <div class="result-box">
            <h2 style="color: #166534; margin-top: 0;">Hasil Analisa</h2>
            <p style="font-size: 1.1rem;"><?= $pesan_ai ?></p>
            <a href="matcher.php" style="color: #166534; font-weight: bold; text-decoration: underline;">Coba Ulang</a>
        </div>

        <div class="grid grid-3">
            <?php 
            if(mysqli_num_rows($rekomendasi) > 0) {
                while($row = mysqli_fetch_assoc($rekomendasi)) : 
                    
                    // --- PERBAIKAN UTAMA: KONVERSI BLOB KE BASE64 ---
                    $gambarSrc = 'assets/images/logo.jpg'; // Gambar Default
                    if (!empty($row['gambar'])) {
                        // Ubah data biner menjadi format gambar base64
                        $gambarSrc = 'data:image/jpeg;base64,' . base64_encode($row['gambar']);
                    }
                    // ------------------------------------------------
            ?>
                    <div class="product-card">
                        <img src="<?= $gambarSrc; ?>" class="product-img" alt="<?= $row['nama_produk']; ?>">
                        
                        <div class="product-body">
                            <h3><?= $row['nama_produk']; ?></h3>
                            <p class="muted" style="font-size: 0.9rem;"><?= $row['daerah']; ?></p>
                            <p class="product-price">Rp <?= number_format($row['harga']); ?></p>
                            <a href="marketplace.php?cari=<?= urlencode($row['nama_produk']); ?>" class="btn btn-primary btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                <?php endwhile; 
            } else { 
                echo "<p class='text-center muted' style='grid-column: 1 / -1;'>Maaf, stok kopi yang cocok sedang kosong di database.</p>"; 
            }
            ?>
        </div>
        <?php endif; ?>

    </main>

    <footer class="site-footer"><p>&copy; <?= date('Y'); ?> Nusantara Coffee.</p></footer>
</body>
</html>