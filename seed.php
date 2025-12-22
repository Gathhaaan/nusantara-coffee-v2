<?php
include 'includes/db.php';

// --- BAGIAN 1: PASTIKAN ADA USER ---
$validUserId = 0;
$cekUser = mysqli_query($conn, "SELECT id FROM users LIMIT 1");

if (mysqli_num_rows($cekUser) > 0) {
    $row = mysqli_fetch_assoc($cekUser);
    $validUserId = $row['id'];
    echo "<div style='font-family:sans-serif; padding:10px; background:#e0f2fe; border:1px solid #bae6fd; margin-bottom:10px;'>✅ Menggunakan User ID yang ada: <strong>" . $validUserId . "</strong></div>";
} else {
    // Buat user dummy jika tabel kosong
    $passDefault = password_hash('admin123', PASSWORD_DEFAULT);
    $buatUser = "INSERT INTO users (nama_lengkap, username, password) VALUES ('Administrator', 'admin', '$passDefault')";
    if (mysqli_query($conn, $buatUser)) {
        $validUserId = mysqli_insert_id($conn);
        echo "✅ Membuat user 'Administrator' baru dengan ID: " . $validUserId . "<br>";
    } else {
        die("❌ Gagal membuat user dummy: " . mysqli_error($conn));
    }
}

// --- BAGIAN 2: 30 DATA KOPI NUSANTARA ---
$products = [
    // --- SUMATRA (8 Item) ---
    [
        'nama' => 'Gayo Highland Arabica',
        'daerah' => 'Aceh, Sumatra',
        'harga' => 95000,
        'stok' => 50,
        'deskripsi' => 'Kopi Gayo klasik dengan body tebal, aroma bumi (earthy), dan acidity rendah.',
        'url' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Gayo Wine Process',
        'daerah' => 'Takengon, Aceh',
        'harga' => 135000,
        'stok' => 15,
        'deskripsi' => 'Fermentasi panjang menghasilkan aroma anggur yang kuat dan rasa manis buah.',
        'url' => 'https://images.unsplash.com/photo-1611854779393-1b2ae563f603?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Gayo Peaberry (Lanang)',
        'daerah' => 'Bener Meriah, Aceh',
        'harga' => 110000,
        'stok' => 20,
        'deskripsi' => 'Biji tunggal (monokotil) dengan kafein lebih tinggi dan rasa lebih nendang.',
        'url' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Mandheling Grade 1',
        'daerah' => 'Mandailing, Sumatra Utara',
        'harga' => 88000,
        'stok' => 40,
        'deskripsi' => 'Smooth, syrupy body dengan aftertaste cokelat manis dan rempah.',
        'url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Lintong Nihuta',
        'daerah' => 'Humbahas, Sumatra Utara',
        'harga' => 92000,
        'stok' => 25,
        'deskripsi' => 'Aroma herbal rempah yang kuat, clean cup, dengan acidity medium.',
        'url' => 'https://images.unsplash.com/photo-1527676594581-b5fdf0e10fef?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Sidikalang Robusta',
        'daerah' => 'Dairi, Sumatra Utara',
        'harga' => 75000,
        'stok' => 60,
        'deskripsi' => 'Pesaing kopi Brazil. Rasa cokelat pahit yang mantap, cocok untuk kopi susu.',
        'url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Kerinci Honey Process',
        'daerah' => 'Jambi, Sumatra',
        'harga' => 105000,
        'stok' => 15,
        'deskripsi' => 'Manis madu alami dengan sentuhan rasa kayu manis dan buah tropis.',
        'url' => 'https://images.unsplash.com/photo-1580933073521-dc49ac0d4e6a?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Lahat Robusta',
        'daerah' => 'Sumatra Selatan',
        'harga' => 65000,
        'stok' => 50,
        'deskripsi' => 'Kopi rakyat dengan aroma kuat, body tebal, dan rasa karamel gosong.',
        'url' => 'https://images.unsplash.com/photo-1621267860478-dbdd589372db?auto=format&fit=crop&w=600&q=80'
    ],

    // --- JAWA (7 Item) ---
    [
        'nama' => 'Java Preanger',
        'daerah' => 'Bandung, Jawa Barat',
        'harga' => 90000,
        'stok' => 30,
        'deskripsi' => 'Legenda kopi Jawa. Rasa manis karamel, nutty, dan sedikit fruity.',
        'url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Java Puntang',
        'daerah' => 'Gn. Puntang, Jawa Barat',
        'harga' => 150000,
        'stok' => 10,
        'deskripsi' => 'Pernah juara dunia! Rasa sangat floral, manis seperti gulali.',
        'url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Ciwidey Natural',
        'daerah' => 'Bandung Selatan, Jabar',
        'harga' => 115000,
        'stok' => 15,
        'deskripsi' => 'Aroma nangka dan stroberi yang kuat hasil proses natural yang apik.',
        'url' => 'https://images.unsplash.com/photo-1504630083234-141d77f9090f?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Ijen Raung Blue Fire',
        'daerah' => 'Bondowoso, Jawa Timur',
        'harga' => 82000,
        'stok' => 35,
        'deskripsi' => 'Unik dengan aroma kacang, sedikit pedas (spicy), dan acidity lembut.',
        'url' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Temanggung Robusta',
        'daerah' => 'Jawa Tengah',
        'harga' => 68000,
        'stok' => 50,
        'deskripsi' => 'Aroma tembakau yang khas dan body yang sangat tebal.',
        'url' => 'https://images.unsplash.com/photo-1611854779393-1b2ae563f603?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Malabar Mountain',
        'daerah' => 'Pangalengan, Jawa Barat',
        'harga' => 95000,
        'stok' => 20,
        'deskripsi' => 'Rasa kompleks, perpaduan bunga mawar dan cokelat hitam.',
        'url' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Arjuno Arabica',
        'daerah' => 'Malang, Jawa Timur',
        'harga' => 89000,
        'stok' => 25,
        'deskripsi' => 'Acidity apel hijau yang segar dengan rasa akhir seperti teh.',
        'url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80'
    ],

    // --- SULAWESI (5 Item) ---
    [
        'nama' => 'Toraja Sapan',
        'daerah' => 'Toraja Utara, Sulawesi',
        'harga' => 110000,
        'stok' => 20,
        'deskripsi' => 'Kopi premium Sulawesi. Kompleks, herbal, notes buah matang.',
        'url' => 'https://images.unsplash.com/photo-1527676594581-b5fdf0e10fef?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Toraja Yale',
        'daerah' => 'Tana Toraja, Sulawesi',
        'harga' => 112000,
        'stok' => 15,
        'deskripsi' => 'White pepper note yang khas, clean, dan body medium.',
        'url' => 'https://images.unsplash.com/photo-1621267860478-dbdd589372db?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Kalosi Enrekang',
        'daerah' => 'Enrekang, Sulawesi',
        'harga' => 105000,
        'stok' => 22,
        'deskripsi' => 'Tetangga Toraja dengan aroma rempah yang lebih menonjol.',
        'url' => 'https://images.unsplash.com/photo-1504630083234-141d77f9090f?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Mamasa Arabica',
        'daerah' => 'Sulawesi Barat',
        'harga' => 98000,
        'stok' => 18,
        'deskripsi' => 'Jarang ditemukan. Rasa floral melati dan kacang almond.',
        'url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Gowa Robusta',
        'daerah' => 'Sulawesi Selatan',
        'harga' => 60000,
        'stok' => 40,
        'deskripsi' => 'Pahit cokelat pekat, cocok untuk campuran espresso.',
        'url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80'
    ],

    // --- BALI & NUSA TENGGARA (5 Item) ---
    [
        'nama' => 'Bali Kintamani Wash',
        'daerah' => 'Bangli, Bali',
        'harga' => 98000,
        'stok' => 30,
        'deskripsi' => 'Sangat khas dengan rasa jeruk (citrusy) segar dan ringan.',
        'url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Bali Karana Natural',
        'daerah' => 'Ubud, Bali',
        'harga' => 105000,
        'stok' => 15,
        'deskripsi' => 'Manis fermentasi buah tropis, nangka, dan salak.',
        'url' => 'https://images.unsplash.com/photo-1580933073521-dc49ac0d4e6a?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Flores Bajawa',
        'daerah' => 'Ngada, NTT',
        'harga' => 96000,
        'stok' => 30,
        'deskripsi' => 'Body tebal, aroma kacang-kacangan, dan karamel alami.',
        'url' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Flores Manggarai',
        'daerah' => 'Manggarai, NTT',
        'harga' => 94000,
        'stok' => 20,
        'deskripsi' => 'Sedikit lebih asam dari Bajawa, dengan nuansa lemon.',
        'url' => 'https://images.unsplash.com/photo-1611854779393-1b2ae563f603?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Lombok Sembalun',
        'daerah' => 'Lombok Timur, NTB',
        'harga' => 90000,
        'stok' => 20,
        'deskripsi' => 'Tumbuh di kaki Rinjani. Rasa brown sugar dan rempah.',
        'url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80'
    ],

    // --- KALIMANTAN & PAPUA (5 Item) ---
    [
        'nama' => 'Liberika Kayong',
        'daerah' => 'Kayong Utara, Kalimantan',
        'harga' => 75000,
        'stok' => 45,
        'deskripsi' => 'Spesies Liberika langka. Aroma nangka kuat, kafein rendah.',
        'url' => 'https://images.unsplash.com/photo-1504630083234-141d77f9090f?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Liberika Borneo',
        'daerah' => 'Kalimantan Barat',
        'harga' => 72000,
        'stok' => 40,
        'deskripsi' => 'Rasa unik seperti sayuran (vegetal) dan buah nangka.',
        'url' => 'https://images.unsplash.com/photo-1621267860478-dbdd589372db?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Papua Wamena',
        'daerah' => 'Jayawijaya, Papua',
        'harga' => 125000,
        'stok' => 10,
        'deskripsi' => 'Tumbuh liar di lembah Baliem. Aroma cokelat dan floral seimbang.',
        'url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Papua Moanemani',
        'daerah' => 'Dogiyai, Papua',
        'harga' => 120000,
        'stok' => 8,
        'deskripsi' => 'Kopi organik dengan rasa manis alami dan aroma hutan.',
        'url' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'nama' => 'Papua Pegunungan Bintang',
        'daerah' => 'Oksibil, Papua',
        'harga' => 130000,
        'stok' => 5,
        'deskripsi' => 'Sangat fruity, mirip jeruk nipis dan berry liar.',
        'url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80'
    ]
];

// --- BAGIAN 3: EKSEKUSI ---
echo "<div style='font-family:sans-serif; max-width:800px; margin:20px auto;'>";
echo "<h2>🚀 Mendownload & Menyimpan 30 Data Kopi...</h2>";
echo "<p style='color:#666'>Proses ini mungkin memakan waktu 10-30 detik. Mohon jangan tutup browser...</p>";
echo "<hr>";

$count = 0;
set_time_limit(300); // 5 Menit timeout

foreach ($products as $p) {
    $url = $p['url'];
    $imageContent = @file_get_contents($url);
    
    if ($imageContent !== false) {
        $imageData = addslashes($imageContent);
        
        $nama = mysqli_real_escape_string($conn, $p['nama']);
        $daerah = mysqli_real_escape_string($conn, $p['daerah']);
        $harga = $p['harga'];
        $stok = $p['stok'];
        $desc = mysqli_real_escape_string($conn, $p['deskripsi']);
        
        $query = "INSERT INTO products (user_id, nama_produk, daerah, harga, stok, deskripsi, gambar) 
                  VALUES ('$validUserId', '$nama', '$daerah', '$harga', '$stok', '$desc', '$imageData')";
                  
        if (mysqli_query($conn, $query)) {
            echo "<div style='color:green; margin-bottom:5px;'>✅ <b>$nama</b> berhasil disimpan!</div>";
            $count++;
        } else {
            echo "<div style='color:red; margin-bottom:5px;'>❌ Gagal SQL untuk $nama: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div style='color:orange; margin-bottom:5px;'>⚠️ Gagal download gambar: <i>$nama</i></div>";
    }
}

echo "<hr>";
echo "<h3>🎉 Selesai! Total $count produk baru ditambahkan.</h3>";
echo "<a href='index.php' style='display:inline-block; padding:10px 20px; background:#4A3B32; color:white; text-decoration:none; border-radius:5px;'>Lihat Marketplace &rarr;</a>";
echo "</div>";
?>