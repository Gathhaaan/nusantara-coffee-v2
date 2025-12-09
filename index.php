<?php session_start(); ?>
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
            <a href="calculator.php" class="nav-link">Kalkulator</a>
            <a href="matcher.php" class="nav-link">Cek Cocoklogi</a>
            <a href="article.php" class="nav-link">Artikel</a>
            <?php if (isset($_SESSION['login'])) : ?>
                <a href="marketplace.php" class="nav-link">Marketplace</a>
                <a href="logout.php" class="nav-link nav-btn-logout">Logout</a>
            <?php else : ?>
                <a href="contact.php" class="nav-link">Kontak</a>
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
          <?php if (isset($_SESSION['login'])) : ?>
             <a class="btn btn-primary" href="marketplace.php">Belanja Sekarang</a>
          <?php else : ?>
             <a class="btn btn-primary" href="login.php">Mulai Belanja</a>
          <?php endif; ?>
        </div>
      </section>

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
          </div>
          <div class="grid">
              <div class="showcase-card" data-category="sumatra">
                  <img src="assets/images/coffee-beans-sumatra-gayo.jpg" alt="Gayo" class="showcase-img">
                  <div class="showcase-body">
                      <div><h3 class="showcase-title">Sumatra Gayo</h3><p class="showcase-origin">Asal: Aceh, Sumatra</p></div>
                      <a href="marketplace.php?cari=Gayo" class="btn-showcase">Cari di Marketplace</a>
                  </div>
              </div>
              <div class="showcase-card" data-category="jawa">
                  <img src="assets/images/coffee-beans-java-preanger.jpg" alt="Preanger" class="showcase-img">
                  <div class="showcase-body">
                      <div><h3 class="showcase-title">Java Preanger</h3><p class="showcase-origin">Asal: Jawa Barat</p></div>
                      <a href="marketplace.php?cari=Preanger" class="btn-showcase">Cari di Marketplace</a>
                  </div>
              </div>
              <div class="showcase-card" data-category="bali">
                  <img src="assets/images/coffee-beans-bali-kintamani.jpg" alt="Kintamani" class="showcase-img">
                  <div class="showcase-body">
                      <div><h3 class="showcase-title">Bali Kintamani</h3><p class="showcase-origin">Asal: Bali</p></div>
                      <a href="marketplace.php?cari=Kintamani" class="btn-showcase">Cari di Marketplace</a>
                  </div>
              </div>
          </div>
      </section>

      <section class="map-section container text-center" style="padding-bottom: 4rem;">
          <h2 class="section-title">Peta Interaktif</h2>
          <p class="muted">Arahkan kursor untuk info singkat, atau <strong style="color:var(--accent)">KLIK wilayah</strong> untuk cari produk.</p>
          <div class="map-wrapper" style="margin-top: 2rem;">
            <img src="assets/images/peta-silhouette-indonesia-map.jpg" alt="Peta Indonesia" class="map-image">
            
            <button class="hotspot hs-sumatra" data-region="sumatra" aria-label="Sumatra"></button>
            <button class="hotspot hs-java" data-region="java" aria-label="Jawa"></button>
            <button class="hotspot hs-kalimantan" data-region="kalimantan" aria-label="Kalimantan"></button>
            <button class="hotspot hs-sulawesi" data-region="sulawesi" aria-label="Sulawesi"></button>
            <button class="hotspot hs-bali-ntb-ntt" data-region="baliNtt" aria-label="Bali"></button>
            <button class="hotspot hs-maluku" data-region="maluku" aria-label="Maluku"></button>
            <button class="hotspot hs-papua" data-region="papua" aria-label="Papua"></button>

            <div id="tooltip" hidden></div>
          </div>
      </section>
    </main>

    <footer class="site-footer"><p>&copy; <?= date('Y'); ?> Nusantara Coffee.</p></footer>
    <script src="assets/js/main.js"></script>
</body>
</html>