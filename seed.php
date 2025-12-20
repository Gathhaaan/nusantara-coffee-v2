<?php
include 'includes/db.php';

// --- BAGIAN PERBAIKAN: CARI ATAU BUAT USER DULU ---
$validUserId = 0;

// 1. Cek apakah sudah ada user di database?
$cekUser = mysqli_query($conn, "SELECT id FROM users LIMIT 1");

if (mysqli_num_rows($cekUser) > 0) {
    // Jika ada, ambil ID user pertama yang ditemukan
    $row = mysqli_fetch_assoc($cekUser);
    $validUserId = $row['id'];
    echo "✅ Menggunakan User ID yang ada: " . $validUserId . "<br>";
} else {
    // Jika TIDAK ADA, buat user dummy 'Admin'
    $passDefault = password_hash('admin123', PASSWORD_DEFAULT);
    $buatUser = "INSERT INTO users (nama_lengkap, username, password) VALUES ('Administrator', 'admin', '$passDefault')";
    
    if (mysqli_query($conn, $buatUser)) {
        $validUserId = mysqli_insert_id($conn);
        echo "✅ User kosong. Membuat user 'Administrator' baru dengan ID: " . $validUserId . "<br>";
    } else {
        die("❌ Gagal membuat user dummy: " . mysqli_error($conn));
    }
}
// ----------------------------------------------------

$products = [
    [
        'nama' => 'Gayo Highland',
        'daerah' => 'Aceh, Sumatra',
        'harga' => 95000,
        'stok' => 20,
        'deskripsi' => 'Kopi arabika dengan body tebal dan aroma earthy.',
        'file' => 'coffee-beans-sumatra-gayo.jpg' 
    ],
    [
        'nama' => 'Lintong Nihuta',
        'daerah' => 'Sumatra Utara',
        'harga' => 92000,
        'stok' => 15,
        'deskripsi' => 'Aroma herbal rempah yang kuat dengan acidity medium.',
        'file' => 'coffee-beans-lintong.jpg'
    ],
    [
        'nama' => 'Java Preanger',
        'daerah' => 'Bandung, Jawa Barat',
        'harga' => 85000,
        'stok' => 18,
        'deskripsi' => 'Legenda kopi Jawa dengan rasa manis karamel.',
        'file' => 'coffee-beans-java-preanger.jpg'
    ],
    [
        'nama' => 'Ijen Raung',
        'daerah' => 'Bondowoso, Jawa Timur',
        'harga' => 82000,
        'stok' => 20,
        'deskripsi' => 'Unik dengan aroma kacang dan sedikit pedas.',
        'file' => 'coffee-beans-ijen-raung.jpg'
    ],
    [
        'nama' => 'Bali Kintamani',
        'daerah' => 'Bangli, Bali',
        'harga' => 98000,
        'stok' => 15,
        'deskripsi' => 'Ciri khas rasa jeruk (citrusy) yang sangat segar.',
        'file' => 'coffee-beans-bali-kintamani.jpg'
    ],
    [
        'nama' => 'Flores Bajawa',
        'daerah' => 'Ngada, NTT',
        'harga' => 96000,
        'stok' => 15,
        'deskripsi' => 'Aroma kacang-kacangan dan karamel alami.',
        'file' => 'coffee-beans-flores-bajawa.jpg'
    ],
    [
        'nama' => 'Toraja Sapan',
        'daerah' => 'Sulawesi Selatan',
        'harga' => 110000,
        'stok' => 10,
        'deskripsi' => 'Kompleks, herbal, dengan notes buah-buahan matang.',
        'file' => 'coffee-beans-toraja.jpg'
    ],
    [
        'nama' => 'Liberika Kayong',
        'daerah' => 'Kalimantan Barat',
        'harga' => 75000,
        'stok' => 20,
        'deskripsi' => 'Aroma nangka yang khas dan rasa yang unik.',
        'file' => 'liberika-kayong.jpg'
    ],
    [
        'nama' => 'Papua Wamena',
        'daerah' => 'Jayawijaya, Papua',
        'harga' => 120000,
        'stok' => 8,
        'deskripsi' => 'Aroma cokelat dan floral dengan rasa yang seimbang.',
        'file' => 'coffee-beans-papua-wamena.jpg'
    ]
];

echo "<h2>Memproses Data Produk...</h2>";

foreach ($products as $p) {
    $path = "assets/images/" . $p['file'];
    
    if (file_exists($path)) {
        // BACA GAMBAR JADI DATA BINER
        $imageData = addslashes(file_get_contents($path));
        
        $nama = $p['nama'];
        $daerah = $p['daerah'];
        $harga = $p['harga'];
        $stok = $p['stok'];
        $desc = $p['deskripsi'];
        
        // Simpan ke DB menggunakan $validUserId
        $query = "INSERT INTO products (user_id, nama_produk, daerah, harga, stok, deskripsi, gambar) 
                  VALUES ('$validUserId', '$nama', '$daerah', '$harga', '$stok', '$desc', '$imageData')";
                  
        if (mysqli_query($conn, $query)) {
            echo "✅ Berhasil memasukkan: " . $nama . "<br>";
        } else {
            echo "❌ Gagal SQL: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "⚠️ Gambar tidak ditemukan: " . $path . " (Pastikan file ada di folder assets/images)<br>";
    }
}
echo "<br><h3>Selesai! Silakan cek Beranda.</h3>";
?>