<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php"); // Jika bukan mentor, alihkan ke halaman login
    exit();
}

// Jika form disubmit, simpan data kelas ke database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kelas = $_POST['nama_kelas'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $description = $_POST['description'];
    
    // Pastikan data valid
    if (empty($nama_kelas) || empty($kategori) || empty($harga)) {
        $error_message = "Semua kolom wajib diisi!";
    } else {
        // Insert data kelas ke database
        $stmt = $conn->prepare("INSERT INTO tb_kelas (nama_kelas, kategori, harga, description, id_user) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdi", $nama_kelas, $kategori, $harga, $description, $_SESSION['user_id']); // Menggunakan user_id dari session
        if ($stmt->execute()) {
            $success_message = "Kelas berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan kelas. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h2>Tambah Kelas Baru</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php elseif (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <form action="create-class.php" method="POST">
            <div class="mb-3">
                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="kategori" required>
                    <option value="Web Development">Web Development</option>
                    <option value="Design">Design</option>
                    <option value="Python">Python</option>
                    <option value="Java">Java</option>
                    <option value="SQL">SQL</option>
                    <option value="Bisnis">Bisnis</option>
                    <option value="Ekonomi">Ekonomi</option>
                    <option value="Psikologi">Psikologi</option>
                    <option value="IT">IT</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Kelas</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
