<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kontak Kami - Nusantara Coffee</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* CSS Tambahan Khusus Halaman Ini agar foto rapi */
        .team-img {
            width: 100%;
            height: 300px; /* Tinggi foto disamakan */
            object-fit: cover; /* Agar foto tidak gepeng/stretch */
            object-position: center top; /* Fokus ke wajah (atas) */
            border-bottom: 1px solid #eee;
        }
        .card {
            overflow: hidden; /* Agar sudut foto ikut melengkung sesuai card */
            border: 1px solid var(--border);
            border-radius: 12px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
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
        <h1 class="section-title text-center">Tim Kami</h1>
        <p class="text-center muted" style="max-width: 600px; margin: 0 auto 3rem;">
            Website ini dipersembahkan oleh Kelompok 3 Kelas 2024A dengan penuh cinta dan kopi.
        </p>

        <div class="grid grid-3">
          
          <article class="card text-center">
            <img src="assets/images/gathan.jpeg" alt="Gathan Yandino" class="team-img" 
                 onerror="this.src='https://ui-avatars.com/api/?name=Gathan+Yandino&size=300&background=random';">
            
            <div class="card-body">
              <h2 class="card-title">Gathan Yandino</h2>
              <p class="card-meta" style="color:var(--accent); font-weight:bold;">240913967026</p>
              <a href="https://instagram.com/Gathhaaan" target="_blank" class="btn btn-secondary">Follow Instagram</a>
            </div>
          </article>

          <article class="card text-center">
            <img src="assets/images/vivi.jpeg" alt="Lutviana Dwi" class="team-img"
                 onerror="this.src='https://ui-avatars.com/api/?name=Lutviana+Dwi&size=300&background=random';">

            <div class="card-body">
              <h2 class="card-title">Lutviana Dwi</h2>
              <p class="card-meta" style="color:var(--accent); font-weight:bold;">24091397013</p>
              <a href="https://instagram.com/ltvnadj_" target="_blank" class="btn btn-secondary">Follow Instagram</a>
            </div>
          </article>

          <article class="card text-center">
             <img src="assets/images/rizal.jpeg" alt="Rizal Bayhaqi" class="team-img"
                 onerror="this.src='https://ui-avatars.com/api/?name=Rizal+Bayhaqi&size=300&background=random';">

            <div class="card-body">
              <h2 class="card-title">Rizal Bayhaqi</h2>
              <p class="card-meta" style="color:var(--accent); font-weight:bold;">19051397023</p>
              <a href="https://instagram.com/rizal.bayhaqi" target="_blank" class="btn btn-secondary">Follow Instagram</a>
            </div>
          </article>

        </div>
      </div>
    </main>

    <footer class="site-footer"><p>&copy; <?= date('Y'); ?> Nusantara Coffee.</p></footer>
</body>
</html>