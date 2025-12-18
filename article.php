<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Artikel - Nusantara Coffee</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/article.css">
    <style>
      .article-page { margin-top: 2rem; }
      .history-card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; }
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

    <main class="container article-page">
      <article class="article" style="background: transparent; box-shadow: none; border: none; padding: 0;">
        <header class="text-center" style="margin-bottom: 3rem;">
          <h1 class="article-title" style="font-size: 2.5rem;">Sejarah Kopi: Dari Etiopia ke Nusantara</h1>
          <p class="muted">Sebuah perjalanan panjang melintasi benua, budaya, dan cita rasa.</p>
        </header>

        <div class="article-slider" style="margin-bottom: 3rem; border-radius: 12px; overflow: hidden;">
            <img src="assets/images/sejarah-kopi.jpg" alt="Peta Sejarah Kopi" style="width: 100%; object-fit: cover;" /> 
        </div>
           
        <section class="history-container">
          <div class="grid grid-3">
            <div class="history-card">
               <h3>1. Asal-usul di Etiopia</h3>
               <p class="muted">Legenda Kaldi sang gembala kambing di dataran tinggi Etiopia menjadi awal mula penemuan kopi.</p>
            </div>
            <div class="history-card">
               <h3>2. Masuk ke Jazirah Arab</h3>
               <p class="muted">Pada abad ke-15, kopi mulai dibudidayakan di Yaman. Kaum sufi menggunakannya untuk ibadah malam.</p>
            </div>
            <div class="history-card">
               <h3>3. Menyebar ke Eropa</h3>
               <p class="muted">Melalui pedagang Venesia, kopi masuk ke Eropa pada abad ke-17 dan kedai kopi bermunculan.</p>
            </div>
            <div class="history-card" style="grid-column: 1 / -1; background: #fdf8f0; border-color: var(--primary);">
               <h3>4. Kopi di Bumi Nusantara</h3>
               <p class="muted">VOC membawa bibit kopi ke Batavia pada tahun 1696. Tanah vulkanik Indonesia sangat cocok, menjadikan Jawa produsen besar.</p>
            </div>
          </div>
         </section>
      </article>
    </main>

    <footer class="site-footer"><p>&copy; <?= date('Y'); ?> Nusantara Coffee.</p></footer>
</body>
</html>