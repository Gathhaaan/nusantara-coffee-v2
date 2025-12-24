<?php
include 'includes/db.php';

// --- 1. SOLUSI FOREIGN KEY: CARI ATAU BUAT USER ---
$validUserId = 0;

// Cek apakah ada user di database?
$cekUser = mysqli_query($conn, "SELECT id FROM users LIMIT 1");

if (mysqli_num_rows($cekUser) > 0) {
    // Jika ada, ambil ID user pertama yang ditemukan
    $row = mysqli_fetch_assoc($cekUser);
    $validUserId = $row['id'];
    echo "<div style='background:#dcfce7; padding:10px; margin-bottom:10px;'>✅ Menggunakan User ID yang tersedia: <strong>" . $validUserId . "</strong></div>";
} else {
    // Jika KOSONG, Buat User Admin Baru Otomatis
    $passHash = password_hash('admin123', PASSWORD_DEFAULT);
    // Role diset 'admin'
    $queryUser = "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('Super Admin', 'admin', '$passHash', 'admin')";
    
    if (mysqli_query($conn, $queryUser)) {
        $validUserId = mysqli_insert_id($conn);
        echo "<div style='background:#dcfce7; padding:10px; margin-bottom:10px;'>✅ Tabel User kosong. Membuat user 'admin' baru dengan ID: <strong>" . $validUserId . "</strong></div>";
    } else {
        die("❌ Gagal membuat user dummy: " . mysqli_error($conn));
    }
}

// --- 2. DATA PRODUK ---
$products = [
    [ 'nama' => 'Gayo Highland Arabica', 'daerah' => 'Aceh, Sumatra', 'harga' => 95000, 'stok' => 50, 'url' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?w=600&q=80' ],
    [ 'nama' => 'Mandheling Grade 1', 'daerah' => 'Mandailing, Sumatra', 'harga' => 88000, 'stok' => 40, 'url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&q=80' ],
    [ 'nama' => 'Java Preanger', 'daerah' => 'Bandung, Jawa Barat', 'harga' => 90000, 'stok' => 30, 'url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=600&q=80' ],
    [ 'nama' => 'Toraja Sapan', 'daerah' => 'Toraja, Sulawesi', 'harga' => 110000, 'stok' => 20, 'url' => 'https://images.unsplash.com/photo-1527676594581-b5fdf0e10fef?w=600&q=80' ],
    [ 'nama' => 'Bali Kintamani', 'daerah' => 'Bangli, Bali', 'harga' => 98000, 'stok' => 30, 'url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&q=80' ],
    [ 'nama' => 'Flores Bajawa', 'daerah' => 'Ngada, NTT', 'harga' => 96000, 'stok' => 30, 'url' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=600&q=80' ],
    [ 'nama' => 'Papua Wamena', 'daerah' => 'Wamena, Papua', 'harga' => 120000, 'stok' => 10, 'url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80' ],
    [ 'nama' => 'Liberika Kayong', 'daerah' => 'Kalimantan Barat', 'harga' => 75000, 'stok' => 45, 'url' => 'https://images.unsplash.com/photo-1504630083234-141d77f9090f?w=600&q=80' ]
];

// --- 3. EKSEKUSI ---
echo "<h3>🚀 Memproses Data...</h3>";

// Fungsi Download Gambar (Agar tidak error SSL)
function downloadImage($url) {
    $opts = ["http" => ["header" => "User-Agent: Mozilla/5.0"], "ssl" => ["verify_peer"=>false, "verify_peer_name"=>false]];
    return @file_get_contents($url, false, stream_context_create($opts));
}

set_time_limit(300); // Tambah waktu eksekusi

foreach ($products as $p) {
    $nama = mysqli_real_escape_string($conn, $p['nama']);
    
    // Cek duplikat
    $cek = mysqli_query($conn, "SELECT id FROM products WHERE nama_produk = '$nama'");
    if (mysqli_num_rows($cek) > 0) {
        echo "Example: ℹ️ Produk $nama sudah ada.<br>";
        continue;
    }

    $imageData = downloadImage($p['url']);
    if ($imageData) {
        $binData = addslashes($imageData);
        $daerah = $p['daerah'];
        $harga = $p['harga'];
        $stok = $p['stok'];
        
        // INSERT MENGGUNAKAN $validUserId (PENTING!)
        $query = "INSERT INTO products (user_id, nama_produk, daerah, harga, stok, deskripsi, gambar) 
                  VALUES ('$validUserId', '$nama', '$daerah', '$harga', '$stok', 'Kopi nikmat asli nusantara.', '$binData')";
        
        if (mysqli_query($conn, $query)) {
            echo "✅ Sukses: $nama<br>";
        } else {
            echo "❌ Gagal SQL: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "⚠️ Gagal download gambar: $nama<br>";
    }
}
echo "<hr><h3>Selesai! Silakan Cek Marketplace.</h3>";
?>