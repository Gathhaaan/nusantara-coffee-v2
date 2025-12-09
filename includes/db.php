<?php
$conn = mysqli_connect("localhost", "root", "", "nusantara_coffee");
if (!$conn) {
    die("Gagal terhubung ke database: " . mysqli_connect_error());
}
?>