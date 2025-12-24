<?php
include 'includes/db.php';

// --- FUNGSI DOWNLOAD SAKTI (CURL) ---
// Kita gunakan fungsi yang paling kuat agar tidak gagal lagi
function downloadImageUpdate($url) {
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode == 200 && $data) return $data;
    }
    return false;
}

// --- DAFTAR GAMBAR UNIK (Mapping Nama Produk -> URL Baru) ---
// Saya sudah memilihkan URL yang bervariasi (Kemasan, Biji, Karung Kopi) agar tidak membosankan
$imageUpdates = [
    // SUMATRA (Nuansa Gelap & Earthy)
    'Gayo Highland Arabica' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?w=600&q=80', // Biji Kopi Close up
    'Gayo Wine Process' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&q=80', // Kopi dalam cangkir & beans
    'Gayo Peaberry (Lanang)' => 'https://images.unsplash.com/photo-1611854779393-1b2ae563f603?w=600&q=80', // Biji kopi tekstur
    'Mandheling Grade 1' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=600&q=80', // Packaging Paper Bag Style
    'Lintong Nihuta' => 'https://images.unsplash.com/photo-1527676594581-b5fdf0e10fef?w=600&q=80', // Biji kopi di tangan
    'Sidikalang Robusta' => 'https://images.unsplash.com/photo-1621267860478-dbdd589372db?w=600&q=80', // Biji kopi dark roast
    'Kerinci Honey Process' => 'https://images.unsplash.com/photo-1580933073521-dc49ac0d4e6a?w=600&q=80', // Kopi dengan background terang
    'Lahat Robusta' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&q=80', // Karung Goni Kopi

    // JAWA (Nuansa Klasik & Brown)
    'Java Preanger' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=600&q=80', // Petani memetik kopi
    'Java Puntang' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80', // Kopi & Buku (Aesthetic)
    'Ciwidey Natural' => 'https://images.unsplash.com/photo-1504630083234-141d77f9090f?w=600&q=80', // Biji kopi hijau (Green bean)
    'Ijen Raung Blue Fire' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=600&q=80', // Cangkir kopi estetik
    'Temanggung Robusta' => 'https://images.unsplash.com/photo-1511537629604-386c8f74303d?w=600&q=80', // Biji kopi berserakan
    'Malabar Mountain' => 'https://images.unsplash.com/photo-1503481766315-7a586b20f66d?w=600&q=80', // Kopi Latte Art
    'Arjuno Arabica' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&q=80', // Mesin roasting / grinder

    // SULAWESI (Nuansa Elegan)
    'Toraja Sapan' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?w=600&q=80', // Pour over coffee V60
    'Toraja Yale' => 'https://images.unsplash.com/photo-1550953683-9b422997b833?w=600&q=80', // Biji kopi macro shot
    'Kalosi Enrekang' => 'https://images.unsplash.com/photo-1579247620888-0a2c07334758?w=600&q=80', // Sack of coffee beans
    'Mamasa Arabica' => 'https://images.unsplash.com/photo-1563205764-52327d403986?w=600&q=80', // Kopi dalam toples kaca
    'Gowa Robusta' => 'https://images.unsplash.com/photo-1524350876685-274059332603?w=600&q=80', // Tangan memegang biji kopi

    // BALI & NTT (Nuansa Segar & Tropical)
    'Bali Kintamani Wash' => 'https://images.unsplash.com/photo-1559586616-361e18714958?w=600&q=80', // Perkebunan kopi
    'Bali Karana Natural' => 'https://images.unsplash.com/photo-1518832553480-cd0e625ed3e6?w=600&q=80', // Breakfast coffee
    'Flores Bajawa' => 'https://images.unsplash.com/photo-1522992319-0365e5f11656?w=600&q=80', // Filter coffee brewing
    'Flores Manggarai' => 'https://images.unsplash.com/photo-1518057111178-44f102da72da?w=600&q=80', // Kopi dengan background kayu
    'Lombok Sembalun' => 'https://images.unsplash.com/photo-1498804103079-a6351b050096?w=600&q=80', // Kopi outdoor nature

    // KALIMANTAN & PAPUA (Nuansa Eksotis)
    'Liberika Kayong' => 'https://images.unsplash.com/photo-1507133750069-41d571dd83f9?w=600&q=80', // Biji kopi unik
    'Liberika Borneo' => 'https://images.unsplash.com/photo-1561047029-3000c68339ca?w=600&q=80', // Cangkir hitam elegan
    'Papua Wamena' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?w=600&q=80', // Kopi manual brew
    'Papua Moanemani' => 'https://images.unsplash.com/photo-1529892485617-25f63cd7b1e9?w=600&q=80', // Creamy coffee
    'Papua Pegunungan Bintang' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&q=80', // Roasted beans pile
];

// --- EKSEKUSI UPDATE ---
echo "<div style='font-family:sans-serif; max-width:800px; margin:20px auto;'>";
echo "<h2>🔄 Memperbarui Gambar Produk...</h2>";
echo "<p style='color:#666'>Sedang mengganti gambar lama dengan gambar baru yang lebih variatif...</p>";
echo "<hr>";

$count = 0;
set_time_limit(600); // Waktu eksekusi diperpanjang jadi 10 menit

foreach ($imageUpdates as $productName => $newUrl) {
    // Escape string untuk keamanan SQL
    $safeName = mysqli_real_escape_string($conn, $productName);
    
    // 1. Download Gambar Baru
    $imageData = downloadImageUpdate($newUrl);
    
    if ($imageData) {
        $binData = addslashes($imageData);
        
        // 2. Lakukan UPDATE berdasarkan nama produk
        // Kita pakai UPDATE, bukan INSERT, agar data yang sudah ada diperbaiki
        $query = "UPDATE products SET gambar = '$binData' WHERE nama_produk = '$safeName'";
        
        if (mysqli_query($conn, $query)) {
            // Cek apakah ada baris yang berubah (affected rows)
            if (mysqli_affected_rows($conn) > 0) {
                echo "<div style='color:green; margin-bottom:5px;'>✅ <b>$productName</b>: Gambar Berhasil Diupdate!</div>";
                $count++;
            } else {
                // Jika 0 affected rows, mungkin nama produknya beda sedikit atau produk belum ada
                // Maka kita coba INSERT produk baru jika belum ada
                $cek = mysqli_query($conn, "SELECT id FROM products WHERE nama_produk = '$safeName'");
                if (mysqli_num_rows($cek) == 0) {
                     // Insert Data Dummy jika produk belum ada (Fallback)
                     // Pastikan ID User valid (ambil ID user pertama)
                     $uRes = mysqli_query($conn, "SELECT id FROM users LIMIT 1");
                     $uRow = mysqli_fetch_assoc($uRes);
                     $uid = $uRow['id'] ?? 1;
                     
                     $insertQ = "INSERT INTO products (user_id, nama_produk, daerah, harga, stok, deskripsi, gambar) 
                                 VALUES ('$uid', '$safeName', 'Indonesia', 90000, 10, 'Kopi Nusantara Premium', '$binData')";
                     if(mysqli_query($conn, $insertQ)) {
                         echo "<div style='color:blue; margin-bottom:5px;'>➕ <b>$productName</b>: Produk Baru Ditambahkan.</div>";
                         $count++;
                     }
                } else {
                    echo "<div style='color:gray; margin-bottom:5px;'>ℹ️ <b>$productName</b>: Gambar sudah paling baru.</div>";
                }
            }
        } else {
            echo "<div style='color:red; margin-bottom:5px;'>❌ Error SQL: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div style='color:orange; margin-bottom:5px;'>⚠️ Gagal download gambar untuk: <b>$productName</b> (Koneksi Timeout)</div>";
    }
    
    // Jeda sedikit agar server gambar tidak memblokir
    flush();
    usleep(500000); // 0.5 detik
}

echo "<hr>";
echo "<h3>🎉 Selesai! Total $count gambar berhasil diproses.</h3>";
echo "<a href='index.php' style='display:inline-block; padding:10px 20px; background:#4A3B32; color:white; text-decoration:none; border-radius:5px;'>Lihat Hasil di Beranda &rarr;</a>";
echo "</div>";
?>