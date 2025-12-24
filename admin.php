<?php
session_start();
include 'includes/db.php';

// 1. KEAMANAN: Cek apakah user sudah login DAN role-nya ADMIN
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses Ditolak! Halaman ini khusus Admin.'); window.location.href = 'index.php';</script>";
    exit;
}

$message = "";

// 2. ADD PRODUCT (Logic BLOB)
if (isset($_POST['add_product'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $daerah = mysqli_real_escape_string($conn, $_POST['daerah']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Proses Gambar ke BLOB
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $fileContent = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
        
        // user_id 0 atau 1 (sebagai admin)
        $uid = $_SESSION['user_id']; 
        $query = "INSERT INTO products (user_id, nama_produk, daerah, harga, stok, deskripsi, gambar) 
                  VALUES ('$uid', '$nama', '$daerah', '$harga', '$stok', '$desc', '$fileContent')";
        
        if (mysqli_query($conn, $query)) {
            $message = "<div class='alert alert-success'>Produk berhasil ditambahkan!</div>";
        } else {
            $message = "<div class='alert alert-error'>Gagal DB: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-error'>Gambar wajib diupload!</div>";
    }
}

// 3. EDIT PRODUCT
if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $daerah = mysqli_real_escape_string($conn, $_POST['daerah']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $query = "UPDATE products SET nama_produk='$nama', daerah='$daerah', harga='$harga', stok='$stok', deskripsi='$desc'";
    
    // Update Gambar (Jika ada upload baru)
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $fileContent = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
        $query .= ", gambar='$fileContent'";
    }
    
    $query .= " WHERE id='$id'";
    
    if(mysqli_query($conn, $query)) {
        $message = "<div class='alert alert-success'>Produk berhasil diupdate!</div>";
    } else {
        $message = "<div class='alert alert-error'>Gagal Update: " . mysqli_error($conn) . "</div>";
    }
}

// 4. DELETE PRODUCT
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
    $message = "<div class='alert alert-success'>Produk berhasil dihapus!</div>";
}

// Ambil Data Produk
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin - Nusantara Coffee</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .admin-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .product-table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .product-table th, .product-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        .product-table th { background-color: var(--primary); color: white; font-weight: 600; }
        .btn-edit { background-color: #f59e0b; color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; }
        .btn-delete { background-color: #dc2626; color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; }
        .form-container { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 3rem; border: 1px solid var(--border); }
    </style>
</head>
<body style="background-color: #f3f4f6;">
    <header class="site-header">
        <div class="container-flex">
            <a class="brand" href="index.php">
                <img src="assets/images/logo.jpg" alt="Logo" width="40" height="40" style="border-radius:50%;" />
                <span class="brand-text">Admin Panel</span>
            </a>
            <nav class="nav">
                <a href="index.php" class="nav-link">Lihat Website</a>
                <a href="logout.php" class="nav-link nav-btn-logout">Logout</a>
            </nav>
        </div>
    </header>

    <div class="admin-container">
        <?= $message ?>

        <h1 class="section-title" style="font-size: 1.8rem; margin-bottom: 1rem;">Manajemen Produk</h1>
        <div style="overflow-x: auto;">
            <table class="product-table">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="10%">Foto</th>
                        <th width="20%">Nama Produk</th>
                        <th width="15%">Daerah</th>
                        <th width="15%">Harga</th>
                        <th width="10%">Stok</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($products)) { 
                        // Konversi BLOB ke Base64 untuk Preview
                        $imgSrc = 'assets/images/logo.jpg';
                        if ($row['gambar']) {
                            $imgSrc = 'data:image/jpeg;base64,' . base64_encode($row['gambar']);
                        }
                    ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><img src="<?= $imgSrc ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;"></td>
                        <td><strong><?= $row['nama_produk'] ?></strong></td>
                        <td><?= $row['daerah'] ?></td>
                        <td>Rp <?= number_format($row['harga']) ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td>
                            <a href="?edit=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')" class="btn-delete">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $edit_q = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
            $edit_row = mysqli_fetch_assoc($edit_q);
        ?>
            <h2 class="section-title" style="font-size: 1.5rem;">Edit Produk #<?= $edit_row['id'] ?></h2>
            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $edit_row['id'] ?>">
                    <div class="form-group"><label>Nama Produk</label><input type="text" name="nama" class="form-control" value="<?= $edit_row['nama_produk'] ?>" required></div>
                    <div class="form-group"><label>Daerah Asal</label><input type="text" name="daerah" class="form-control" value="<?= $edit_row['daerah'] ?>" required></div>
                    <div class="form-group"><label>Harga (Rp)</label><input type="number" name="harga" class="form-control" value="<?= $edit_row['harga'] ?>" required></div>
                    <div class="form-group"><label>Stok</label><input type="number" name="stok" class="form-control" value="<?= $edit_row['stok'] ?>" required></div>
                    <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3"><?= $edit_row['deskripsi'] ?></textarea></div>
                    <div class="form-group">
                        <label>Ganti Foto (Kosongkan jika tidak ingin mengganti)</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>
                    <button type="submit" name="edit_product" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="admin.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        <?php } else { ?>
            <h2 class="section-title" style="font-size: 1.5rem;">Tambah Produk Baru</h2>
            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group"><input type="text" name="nama" class="form-control" placeholder="Nama Produk" required></div>
                    <div class="form-group"><input type="text" name="daerah" class="form-control" placeholder="Daerah Asal" required></div>
                    <div class="form-group"><input type="number" name="harga" class="form-control" placeholder="Harga (Contoh: 85000)" required></div>
                    <div class="form-group"><input type="number" name="stok" class="form-control" placeholder="Stok Awal" required></div>
                    <div class="form-group"><textarea name="deskripsi" class="form-control" placeholder="Deskripsi Singkat" rows="3"></textarea></div>
                    <div class="form-group">
                        <label>Upload Foto Produk</label>
                        <input type="file" name="gambar" class="form-control" required>
                    </div>
                    <button type="submit" name="add_product" class="btn btn-primary">Tambah Produk</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>