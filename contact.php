<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kontak Kami - Nusantara Coffee</title>
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
            <a href="index.php" class="nav-link">Beranda</a>
            <a href="calculator.php" class="nav-link">Kalkulator</a>
            <a href="matcher.php" class="nav-link">Cek Cocoklogi</a>
            <a href="article.php" class="nav-link">Artikel</a>
            <a href="marketplace.php" class="nav-link">Marketplace</a>
            <a href="contact.php" class="nav-link is-active">Kontak</a>
            
            <?php if (isset($_SESSION['login'])) : ?>
                <a href="logout.php" class="nav-link nav-btn-logout">Logout</a>
            <?php else : ?>
                <a href="login.php" class="nav-link nav-btn-login">Login</a>
            <?php endif; ?>
          </nav>
      </div>
    </header>

    <main class="contact" style="padding: 4rem 0;">
      <div class="container">
        <h1 class="section-title">Tim Kami</h1>
        <p class="text-center muted" style="max-width: 600px; margin: 0 auto 3rem;">
            Website ini dipersembahkan oleh Kelompok 3 Kelas 2024A.
        </p>
        <div class="grid grid-3">
          <article class="card text-center">
            <div style="background: #f3f4f6; height: 180px; display:flex; align-items:center; justify-content:center; font-size:4rem;">👨‍💻</div>
            <div class="card-body">
              <h2 class="card-title">Gathan Yandino</h2>
              <p class="card-meta">240913967026</p>
              <p class="card-desc">Lead Developer & PM.</p>
              <a href="https://instagram.com/Gathhaaan" class="btn btn-secondary">Follow Instagram</a>
            </div>
          </article>
          <article class="card text-center">
            <div style="background: #f3f4f6; height: 180px; display:flex; align-items:center; justify-content:center; font-size:4rem;">👩‍💻</div>
            <div class="card-body">
              <h2 class="card-title">Lutviana Dwi</h2>
              <p class="card-meta">24091397013</p>
              <p class="card-desc">Researcher.</p>
              <a href="https://instagram.com/ltvnadj_" class="btn btn-secondary">Follow Instagram</a>
            </div>
          </article>
          <article class="card text-center">
             <div style="background: #f3f4f6; height: 180px; display:flex; align-items:center; justify-content:center; font-size:4rem;">🎨</div>
            <div class="card-body">
              <h2 class="card-title">Rizal Bayhaqi</h2>
              <p class="card-meta">19051397023</p>
              <p class="card-desc">Designer.</p>
              <a href="https://instagram.com/rizal.bayhaqi" class="btn btn-secondary">Follow Instagram</a>
            </div>
          </article>
        </div>
      </div>
    </main>

    <footer class="site-footer"><p>&copy; <?= date('Y'); ?> Nusantara Coffee.</p></footer>
</body>
</html>