<?php
include 'includes/db.php';

$username = 'admin'; // Username yang mau direset
$passwordBaru = 'admin123'; // Password baru

// Enkripsi password
$passwordHash = password_hash($passwordBaru, PASSWORD_DEFAULT);

// Update ke database
$query = "UPDATE users SET password = '$passwordHash' WHERE username = '$username'";

if (mysqli_query($conn, $query)) {
    if (mysqli_affected_rows($conn) > 0) {
        echo "✅ Berhasil! Password untuk user '<b>$username</b>' sudah diubah menjadi: <b>$passwordBaru</b>";
    } else {
        echo "⚠️ Query jalan, tapi tidak ada yang berubah. <br>Mungkin username '<b>$username</b>' tidak ditemukan di database?";
    }
} else {
    echo "❌ Gagal: " . mysqli_error($conn);
}
echo "<br><br><a href='login.php'>Coba Login Sekarang</a>";
?>