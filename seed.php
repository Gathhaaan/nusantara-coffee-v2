<?php
include 'includes/db.php';

// --- FUNGSI DOWNLOAD SAKTI (CURL & STREAM) ---
function downloadImageKuat($url) {
    // Opsi 1: Gunakan cURL (Lebih kuat menangani Redirect & SSL)
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Ikuti jika di-redirect
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Abaikan sertifikat SSL (Penting buat XAMPP)
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // Menyamar sebagai browser Chrome
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Tunggu maksimal 30 detik per gambar
        
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode == 200 && $data) return $data;
    }

    // Opsi 2: Fallback pakai file_get_contents dengan Context
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
        ],
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false
        ]
    ];
    $context = stream_context_create($opts);
    return @file_get_contents($url, false, $context);
}

// --- BAGIAN 1: PASTIKAN ADA USER ---
$validUserId = 0;
$cekUser = mysqli_query($conn, "SELECT id FROM users LIMIT 1");

if (mysqli_num_rows($cekUser) > 0) {
    $row = mysqli_fetch_assoc($cekUser);
    $validUserId = $row['id'];
    echo "<div style='font-family:sans-serif; padding:10px; background:#e0f2fe; border:1px solid #bae6fd; margin-bottom:10px;'>✅ Menggunakan User ID yang ada: <strong>" . $validUserId . "</strong></div>";
} else {
    $passDefault = password_hash('admin123', PASSWORD_DEFAULT);
    $buatUser = "INSERT INTO users (nama_lengkap, username, password) VALUES ('Administrator', 'admin', '$passDefault')";
    if (mysqli_query($conn, $buatUser)) {
        $validUserId = mysqli_insert_id($conn);
        echo "✅ Membuat user 'Administrator' baru dengan ID: " . $validUserId . "<br>";
    } else {
        die("❌ Gagal membuat user dummy: " . mysqli_error($conn));
    }
}

// --- BAGIAN 2: DATA PRODUK ---
// URL diganti ke source yang lebih ringan & stabil
$products = [
    [ 'nama' => 'Gayo Highland Arabica', 'daerah' => 'Aceh, Sumatra', 'harga' => 95000, 'stok' => 50, 'deskripsi' => 'Kopi Gayo klasik dengan body tebal.', 'url' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?w=600&q=80' ],
    [ 'nama' => 'Gayo Wine Process', 'daerah' => 'Takengon, Aceh', 'harga' => 135000, 'stok' => 15, 'deskripsi' => 'Fermentasi panjang rasa anggur.', 'url' => 'https://images.unsplash.com/photo-1611854779393-1b2ae563f603?w=600&q=80' ],
    [ 'nama' => 'Gayo Peaberry', 'daerah' => 'Bener Meriah, Aceh', 'harga' => 110000, 'stok' => 20, 'deskripsi' => 'Biji tunggal kafein tinggi.', 'url' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=600&q=80' ],
    [ 'nama' => 'Mandheling Grade 1', 'daerah' => 'Mandailing, Sumatra', 'harga' => 88000, 'stok' => 40, 'deskripsi' => 'Smooth, syrupy body rempah.', 'url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&q=80' ],
    [ 'nama' => 'Lintong Nihuta', 'daerah' => 'Humbahas, Sumatra', 'harga' => 92000, 'stok' => 25, 'deskripsi' => 'Aroma herbal rempah kuat.', 'url' => 'https://images.unsplash.com/photo-1527676594581-b5fdf0e10fef?w=600&q=80' ],
    [ 'nama' => 'Sidikalang Robusta', 'daerah' => 'Dairi, Sumatra', 'harga' => 75000, 'stok' => 60, 'deskripsi' => 'Cokelat pahit mantap.', 'url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&q=80' ],
    [ 'nama' => 'Kerinci Honey', 'daerah' => 'Jambi, Sumatra', 'harga' => 105000, 'stok' => 15, 'deskripsi' => 'Manis madu alami.', 'url' => 'https://images.unsplash.com/photo-1580933073521-dc49ac0d4e6a?w=600&q=80' ],
    [ 'nama' => 'Lahat Robusta', 'daerah' => 'Sumatra Selatan', 'harga' => 65000, 'stok' => 50, 'deskripsi' => 'Kopi rakyat body tebal.', 'url' => 'https://images.unsplash.com/photo-1621267860478-dbdd589372db?w=600&q=80' ],
    [ 'nama' => 'Java Preanger', 'daerah' => 'Bandung, Jabar', 'harga' => 90000, 'stok' => 30, 'deskripsi' => 'Manis karamel fruity.', 'url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=600&q=80' ],
    [ 'nama' => 'Java Puntang', 'daerah' => 'Gn. Puntang, Jabar', 'harga' => 150000, 'stok' => 10, 'deskripsi' => 'Juara dunia rasa floral.', 'url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80' ],
    [ 'nama' => 'Ciwidey Natural', 'daerah' => 'Bandung Selatan', 'harga' => 115000, 'stok' => 15, 'deskripsi' => 'Aroma nangka stroberi.', 'url' => 'https://images.unsplash.com/photo-1504630083234-141d77f9090f?w=600&q=80' ],
    [ 'nama' => 'Ijen Blue Fire', 'daerah' => 'Bondowoso, Jatim', 'harga' => 82000, 'stok' => 35, 'deskripsi' => 'Kacang pedas acidity lembut.', 'url' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?w=600&q=80' ],
    [ 'nama' => 'Temanggung Robusta', 'daerah' => 'Jawa Tengah', 'harga' => 68000, 'stok' => 50, 'deskripsi' => 'Aroma tembakau khas.', 'url' => 'https://images.unsplash.com/photo-1611854779393-1b2ae563f603?w=600&q=80' ],
    [ 'nama' => 'Malabar Mountain', 'daerah' => 'Pangalengan, Jabar', 'harga' => 95000, 'stok' => 20, 'deskripsi' => 'Bunga mawar cokelat.', 'url' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=600&q=80' ],
    [ 'nama' => 'Arjuno Arabica', 'daerah' => 'Malang, Jatim', 'harga' => 89000, 'stok' => 25, 'deskripsi' => 'Apel hijau segar.', 'url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&q=80' ],
    [ 'nama' => 'Toraja Sapan', 'daerah' => 'Toraja Utara', 'harga' => 110000, 'stok' => 20, 'deskripsi' => 'Herbal buah matang.', 'url' => 'https://images.unsplash.com/photo-1527676594581-b5fdf0e10fef?w=600&q=80' ],
    [ 'nama' => 'Toraja Yale', 'daerah' => 'Tana Toraja', 'harga' => 112000, 'stok' => 15, 'deskripsi' => 'White pepper note clean.', 'url' => 'https://images.unsplash.com/photo-1621267860478-dbdd589372db?w=600&q=80' ],
    [ 'nama' => 'Kalosi Enrekang', 'daerah' => 'Enrekang, Sulawesi', 'harga' => 105000, 'stok' => 22, 'deskripsi' => 'Aroma rempah kuat.', 'url' => 'https://images.unsplash.com/photo-1504630083234-141d77f9090f?w=600&q=80' ],
    [ 'nama' => 'Mamasa Arabica', 'daerah' => 'Sulawesi Barat', 'harga' => 98000, 'stok' => 18, 'deskripsi' => 'Floral melati almond.', 'url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80' ],
    [ 'nama' => 'Gowa Robusta', 'daerah' => 'Sulawesi Selatan', 'harga' => 60000, 'stok' => 40, 'deskripsi' => 'Pahit cokelat pekat.', 'url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=600&q=80' ],
    [ 'nama' => 'Bali Kintamani', 'daerah' => 'Bangli, Bali', 'harga' => 98000, 'stok' => 30, 'deskripsi' => 'Jeruk citrusy segar.', 'url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&q=80' ],
    [ 'nama' => 'Bali Karana', 'daerah' => 'Ubud, Bali', 'harga' => 105000, 'stok' => 15, 'deskripsi' => 'Manis buah nangka.', 'url' => 'https://images.unsplash.com/photo-1580933073521-dc49ac0d4e6a?w=600&q=80' ],
    [ 'nama' => 'Flores Bajawa', 'daerah' => 'Ngada, NTT', 'harga' => 96000, 'stok' => 30, 'deskripsi' => 'Kacang karamel manis.', 'url' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=600&q=80' ],
    [ 'nama' => 'Flores Manggarai', 'daerah' => 'Manggarai, NTT', 'harga' => 94000, 'stok' => 20, 'deskripsi' => 'Lemon floral.', 'url' => 'https://images.unsplash.com/photo-1611854779393-1b2ae563f603?w=600&q=80' ],
    [ 'nama' => 'Lombok Sembalun', 'daerah' => 'Lombok Timur', 'harga' => 90000, 'stok' => 20, 'deskripsi' => 'Brown sugar rempah.', 'url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&q=80' ],
    [ 'nama' => 'Liberika Kayong', 'daerah' => 'Kalimantan Barat', 'harga' => 75000, 'stok' => 45, 'deskripsi' => 'Aroma nangka kuat.', 'url' => 'https://images.unsplash.com/photo-1504630083234-141d77f9090f?w=600&q=80' ],
    [ 'nama' => 'Liberika Borneo', 'daerah' => 'Kalimantan', 'harga' => 72000, 'stok' => 40, 'deskripsi' => 'Vegetal nangka unik.', 'url' => 'https://images.unsplash.com/photo-1621267860478-dbdd589372db?w=600&q=80' ],
    [ 'nama' => 'Papua Wamena', 'daerah' => 'Jayawijaya, Papua', 'harga' => 125000, 'stok' => 10, 'deskripsi' => 'Cokelat floral seimbang.', 'url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80' ],
    [ 'nama' => 'Papua Moanemani', 'daerah' => 'Dogiyai, Papua', 'harga' => 120000, 'stok' => 8, 'deskripsi' => 'Manis alami aroma hutan.', 'url' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?w=600&q=80' ],
    [ 'nama' => 'Papua Pegunungan', 'daerah' => 'Oksibil, Papua', 'harga' => 130000, 'stok' => 5, 'deskripsi' => 'Fruity jeruk nipis berry.', 'url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=600&q=80' ]
];

// --- BAGIAN 3: EKSEKUSI ---
echo "<div style='font-family:sans-serif; max-width:800px; margin:20px auto;'>";
echo "<h2>🚀 Mendownload & Menyimpan 30 Data Kopi...</h2>";
echo "<p style='color:#666'>Proses ini mungkin memakan waktu. Mohon jangan tutup browser...</p>";
echo "<hr>";

$count = 0;
set_time_limit(300); // 5 Menit timeout

foreach ($products as $p) {
    $nama = mysqli_real_escape_string($conn, $p['nama']);
    
    // Cek apakah produk sudah ada (Agar tidak dobel)
    $cek = mysqli_query($conn, "SELECT id FROM products WHERE nama_produk = '$nama'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<div style='color:blue; margin-bottom:5px; font-size:0.9rem;'>ℹ️ <b>$nama</b> sudah ada di database. (Dilewati)</div>";
        continue;
    }

    // DOWNLOAD PAKAI FUNGSI KUAT
    $imageData = downloadImageKuat($p['url']);
    
    if ($imageData) {
        $binData = addslashes($imageData);
        $daerah = mysqli_real_escape_string($conn, $p['daerah']);
        $harga = $p['harga'];
        $stok = $p['stok'];
        $desc = mysqli_real_escape_string($conn, $p['deskripsi']);
        
        $query = "INSERT INTO products (user_id, nama_produk, daerah, harga, stok, deskripsi, gambar) 
                  VALUES ('$validUserId', '$nama', '$daerah', '$harga', '$stok', '$desc', '$binData')";
                  
        if (mysqli_query($conn, $query)) {
            echo "<div style='color:green; margin-bottom:5px;'>✅ <b>$nama</b> BERHASIL disimpan!</div>";
            $count++;
        } else {
            echo "<div style='color:red; margin-bottom:5px;'>❌ Gagal SQL untuk $nama: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div style='color:orange; margin-bottom:5px;'>⚠️ Gagal download gambar: <i>$nama</i> (Cek Koneksi)</div>";
    }
    
    // Jeda 1 detik agar tidak dianggap SPAM oleh server
    sleep(1);
    flush(); 
}

echo "<hr>";
echo "<h3>🎉 Selesai! Total $count produk baru ditambahkan.</h3>";
echo "<a href='index.php' style='display:inline-block; padding:10px 20px; background:#4A3B32; color:white; text-decoration:none; border-radius:5px;'>Lihat Marketplace &rarr;</a>";
echo "</div>";
?>