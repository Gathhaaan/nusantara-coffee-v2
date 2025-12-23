<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kalkulator Seduh - Nusantara Coffee</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .calc-box { max-width: 500px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 15px; border: 1px solid var(--border); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); text-align: center; }
        .result-display { background: var(--primary); color: white; padding: 1.5rem; border-radius: 12px; margin-top: 1.5rem; }
        .result-number { font-size: 2.5rem; font-weight: bold; display: block; }
        .ratio-btn-group { display: flex; gap: 10px; justify-content: center; margin-bottom: 1rem; }
        .ratio-btn { flex: 1; padding: 10px; border: 1px solid var(--border); background: #f9f9f9; cursor: pointer; border-radius: 8px; }
        .ratio-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
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
            <a href="calculator.php" class="nav-link is-active">Kalkulator</a>
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

    <main class="container">
        <div class="calc-box">
            <h1 style="font-size: 1.8rem; margin-bottom: 0.5rem;">☕ Kalkulator Seduh</h1>
            <p class="muted">Hitung rasio air dan kopi yang pas.</p>
            <div style="text-align: left; margin-top: 2rem;">
                <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Gram Kopi:</label>
                <input type="number" id="coffeeInput" style="width:100%; padding:0.8rem; border-radius:8px; border:1px solid #ddd; font-size:1.2rem; text-align:center;" value="15">
            </div>
            <div style="text-align: left; margin-top: 1.5rem;">
                <label class="form-label" style="display:block; margin-bottom:0.5rem; font-weight:600;">Kekuatan Rasa:</label>
                <div class="ratio-btn-group">
                    <button class="ratio-btn" onclick="setRatio(12, this)">Kuat (1:12)</button>
                    <button class="ratio-btn active" onclick="setRatio(15, this)">Sedang (1:15)</button>
                    <button class="ratio-btn" onclick="setRatio(18, this)">Ringan (1:18)</button>
                </div>
            </div>
            <div class="result-display">
                <span>Air Panas:</span>
                <span class="result-number" id="waterOutput">225 ml</span>
            </div>
        </div>
    </main>

    <script>
        let currentRatio = 15;
        function setRatio(ratio, btn) {
            currentRatio = ratio;
            document.querySelectorAll('.ratio-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            calculate();
        }
        function calculate() {
            const coffee = document.getElementById('coffeeInput').value;
            document.getElementById('waterOutput').textContent = (coffee * currentRatio) + " ml";
        }
        document.getElementById('coffeeInput').addEventListener('input', calculate);
    </script>
</body>
</html>