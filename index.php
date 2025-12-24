<?php 
session_start(); 
include 'includes/db.php';

// Ambil 12 produk acak untuk showcase
$queryShowcase = "SELECT * FROM products ORDER BY RAND() LIMIT 12";
$resultShowcase = mysqli_query($conn, $queryShowcase);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda - Nusantara Coffee</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
      <div class="container-flex">
          <a class="brand" href="index.php">
            <img src="assets/images/logo.jpg" alt="Logo" width="40" height="40" style="border-radius:50%;" />
            <span class="brand-text">Nusantara Coffee</span>
          </a>
          <nav class="nav">
            <a href="index.php" class="nav-link is-active">Beranda</a>
            <a href="article.php" class="nav-link">Artikel</a>
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

    <main>
      <section class="hero">
        <div class="hero-background" id="hero-slideshow">
          <img src="assets/images/backgorund-kopi1.jpg" class="hero-background-image active" />
          <img src="assets/images/background-kopi2.jpg" class="hero-background-image" />
          <img src="assets/images/background-kopi3.jpg" class="hero-background-image" />
        </div>
        <div class="hero-inner">
          <h1 class="hero-title">Jelajah Kopi Nusantara</h1>
          <p class="hero-subtitle">Dari Sabang sampai Merauke, setiap pulau menghadirkan karakter rasa unik.</p>
          <a class="btn btn-primary" href="marketplace.php">Belanja Sekarang</a>
        </div>
      </section>

      <section class="container text-center" style="padding: 5rem 1rem 2rem;">
          <div style="max-width: 800px; margin: 0 auto;">
              <span style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; font-size: 0.9rem;">
                  Selamat Datang di Nusantara Coffee
              </span>
              <h2 style="font-size: 2.5rem; margin-top: 1rem; margin-bottom: 1.5rem;">
                  Menghubungkan Rasa, <br>Menyatukan Indonesia.
              </h2>
              <p class="muted" style="font-size: 1.15rem; line-height: 1.8;">
                  Nusantara Coffee hadir sebagai jembatan digital yang mempertemukan petani kopi lokal terbaik dari Aceh hingga Papua dengan para penikmat kopi sejati. Kami percaya bahwa setiap biji kopi menyimpan cerita tanah kelahirannya—sebuah warisan rasa yang layak dirayakan oleh dunia.
              </p>
              <div style="margin-top: 2rem; width: 60px; height: 4px; background: var(--accent); margin-left: auto; margin-right: auto; border-radius: 2px;"></div>
          </div>
      </section>

      <section class="features-container">

      <section class="features-container">
          <div class="features-grid">
              <div class="feature-card">
                  <div class="feature-icon-wrapper">🤖</div>
                  <h3>AI Coffee Matcher</h3>
                  <p>Bingung memilih kopi? Jawab kuis singkat dan biarkan AI kami merekomendasikan kopi untuk Anda.</p>
                  <a href="matcher.php" class="feature-link">Coba Sekarang &rarr;</a>
              </div>
              <div class="feature-card">
                  <div class="feature-icon-wrapper">🛍️</div>
                  <h3>Marketplace</h3>
                  <p>Jelajahi etalase biji kopi premium langsung dari petani lokal di seluruh kepulauan Nusantara.</p>
                  <a href="marketplace.php" class="feature-link">Mulai Belanja &rarr;</a>
              </div>
              <div class="feature-card">
                  <div class="feature-icon-wrapper">☕</div>
                  <h3>Kalkulator Seduh</h3>
                  <p>Hitung rasio air dan kopi yang presisi untuk metode Manual Brew favoritmu.</p>
                  <a href="calculator.php" class="feature-link">Hitung Rasio &rarr;</a>
              </div>
          </div>
      </section>

      <section class="container" style="margin-bottom: 5rem;">
          <h2 class="section-title text-center" style="margin-bottom: 2rem;">Showcase Biji Kopi</h2>
          <div class="filter-tabs">
              <button class="filter-btn active" data-filter="all">Semua</button>
              <button class="filter-btn" data-filter="sumatra">Sumatra</button>
              <button class="filter-btn" data-filter="jawa">Jawa</button>
              <button class="filter-btn" data-filter="kalimantan">Kalimantan</button>
              <button class="filter-btn" data-filter="sulawesi">Sulawesi</button>
              <button class="filter-btn" data-filter="bali">Bali & Nusa Tenggara</button>
              <button class="filter-btn" data-filter="papua">Papua</button>
          </div>

          <div class="grid">
              <?php 
              if (mysqli_num_rows($resultShowcase) > 0) {
                  while($row = mysqli_fetch_assoc($resultShowcase)) {
                      $daerah = strtolower($row['daerah']);
                      $kategori = 'all'; 
                      if (strpos($daerah, 'sumatra') !== false || strpos($daerah, 'aceh') !== false) $kategori = 'sumatra';
                      elseif (strpos($daerah, 'jawa') !== false || strpos($daerah, 'bandung') !== false) $kategori = 'jawa';
                      elseif (strpos($daerah, 'kalimantan') !== false) $kategori = 'kalimantan';
                      elseif (strpos($daerah, 'sulawesi') !== false || strpos($daerah, 'toraja') !== false) $kategori = 'sulawesi';
                      elseif (strpos($daerah, 'bali') !== false || strpos($daerah, 'ntt') !== false) $kategori = 'bali';
                      elseif (strpos($daerah, 'papua') !== false) $kategori = 'papua';
                      
                      // --- LOGIKA DISPLAY GAMBAR BLOB (BASE64) ---
                      $gambarDB = $row['gambar'];
                      // Cek apakah data gambar ada, jika tidak pakai placeholder
                      if ($gambarDB) {
                          $gambarSrc = 'data:image/jpeg;base64,' . base64_encode($gambarDB);
                      } else {
                          $gambarSrc = 'assets/images/logo.jpg'; // Fallback
                      }
              ?>
                  <div class="showcase-card" data-category="<?= $kategori ?>">
                      <img src="<?= $gambarSrc ?>" alt="<?= $row['nama_produk']; ?>" class="showcase-img">
                      <div class="showcase-body">
                          <div>
                              <h3 class="showcase-title"><?= $row['nama_produk']; ?></h3>
                              <p class="showcase-origin">Asal: <?= $row['daerah']; ?></p>
                          </div>
                          <a href="marketplace.php?cari=<?= urlencode($row['nama_produk']); ?>" class="btn-showcase">Cari di Marketplace</a>
                      </div>
                  </div>
              <?php 
                  }
              } else {
                  echo "<p class='text-center muted' style='grid-column: 1/-1;'>Belum ada data produk di database.</p>";
              }
              ?>
          </div>
      </section>

      <section class="map-section container text-center" style="padding-bottom: 4rem;">
          <h2 class="section-title">Peta Interaktif</h2>
          <p class="muted">Arahkan kursor untuk info singkat, atau <strong style="color:var(--accent)">KLIK wilayah</strong> untuk cari produk.</p>
          <div class="map-wrapper" style="margin-top: 2rem;">
            <img src="assets/images/peta-silhouette-indonesia-map.jpg" alt="Peta Indonesia" class="map-image">
            <button class="hotspot hs-sumatra" data-region="sumatra"></button>
            <button class="hotspot hs-java" data-region="java"></button>
            <button class="hotspot hs-kalimantan" data-region="kalimantan"></button>
            <button class="hotspot hs-sulawesi" data-region="sulawesi"></button>
            <button class="hotspot hs-bali-ntb-ntt" data-region="baliNtt"></button>
            <button class="hotspot hs-maluku" data-region="maluku"></button>
            <button class="hotspot hs-papua" data-region="papua"></button>
            <div id="tooltip" hidden></div>
          </div>
      </section>
    </main>

    <footer class="site-footer"><p>&copy; <?= date('Y'); ?> Nusantara Coffee.</p></footer>
    <script src="assets/js/main.js"></script>
</body>
</html>