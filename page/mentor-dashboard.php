<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php"); // Jika bukan mentor, alihkan ke halaman login
    exit();
}

// Mengambil kelas yang dikelola oleh mentor berdasarkan id_user
$stmt_classes = $conn->prepare("
    SELECT k.id_kelas, k.nama_kelas, k.kategori, k.harga, k.description
    FROM tb_kelas k
    JOIN tb_transaksi t ON k.id_kelas = t.id_kelas
    WHERE t.id_user = ?
");
$stmt_classes->bind_param("i", $_SESSION['user_id']); // Menggunakan user_id yang disimpan dalam session
$stmt_classes->execute();
$classes_result = $stmt_classes->get_result();


$stmt_materials = $conn->prepare("
    SELECT k.id_kelas, k.nama_kelas, k.kategori, k.harga, k.description
    FROM tb_kelas k
    JOIN tb_transaksi t ON k.id_kelas = t.id_kelas
    WHERE t.id_user = ?
");
$stmt_materials->bind_param("i", $_SESSION['user_id']); // Menggunakan user_id yang disimpan dalam session
$stmt_materials->execute();
$materials_result = $stmt_materials->get_result(); // Ambil hasil dari query

// Periksa apakah query berhasil
if ($materials_result === false) {
    echo "Error: " . $stmt_materials->error; // Menampilkan error jika query gagal
    exit();
}

$stmt_reviews = $conn->prepare("
    SELECT r.bintang_review, r.isi_review, r.tgl_review, u.username AS murid, k.nama_kelas
    FROM tb_review r
    JOIN tb_user u ON r.id_user = u.id_user
    JOIN tb_kelas k ON r.id_kelas = k.id_kelas
    JOIN tb_transaksi t ON t.id_kelas = k.id_kelas
    WHERE t.id_user = ?
");
$stmt_reviews->bind_param("i", $_SESSION['user_id']); // Menggunakan user_id yang disimpan dalam session
$stmt_reviews->execute();
$reviews_result = $stmt_reviews->get_result(); // Ambil hasil dari query

// Periksa apakah query berhasil
if ($reviews_result === false) {
    echo "Error: " . $stmt_reviews->error; // Menampilkan error jika query gagal
    exit();
}

$stmt_messages = $conn->prepare("
    SELECT n.id_notifikasi , n.pesan_notif, u.username 
    FROM tb_notifikasi n
    JOIN tb_user u ON n.id_user = u.id_user
    WHERE n.id_user =? 
");
$stmt_messages->bind_param("i", $_SESSION['user_id']); // Menggunakan user_id yang disimpan dalam session
$stmt_messages->execute();
$messages_result = $stmt_messages->get_result(); // Ambil hasil dari query

if ($messages_result === false) {
    echo "Error: " . $stmt_messages->error; // Menampilkan error jika query gagal
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/mentor.css">
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <div class="sidebar position-fixed">
        <div class="sidebar-header text-center p-4">
            <img src="mentor-logo.png" alt="Logo" class="rounded-circle" width="50">
            <h3>Mentor Dashboard</h3>
        </div>
        <ul class="nav flex-column p-3">
            <li class="nav-item">
                <a class="nav-link active" href="#manage-classes">Kelola Kelas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#manage-transactions">Kelola Transaksi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#manage-materials">Kelola Materi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#reviews-comments">Review & Komentar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#messages">Pesan</a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content-wrapper">
        <!-- Kelola Kelas -->
        <div id="manage-classes" class="section">
            <div class="header">
                <h2>Kelola Kelas</h2>
                <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#createClassModal">Tambah Kelas</button>
            </div>
            <!-- Menampilkan daftar kelas yang dikelola mentor -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP untuk menampilkan kelas yang dikelola mentor -->
                    <?php while ($row = $classes_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($row['kategori']) ?></td>
                            <td><?= htmlspecialchars($row['harga']) ?></td>
                            <td>
                                <a href="edit-class.php?id_kelas=<?= $row['id_kelas'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete-class.php?id_kelas=<?= $row['id_kelas'] ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Kelola Transaksi -->
        <div id="manage-transactions" class="section mt-5">
            <h2>Kelola Transaksi</h2>
            <!-- Menampilkan transaksi -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Murid</th>
                        <th>Kelas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP untuk menampilkan transaksi -->
                    <?php while ($row = $classes_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_transaksi']) ?></td>
                            <td><?= htmlspecialchars($row['murid']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Kelola Materi -->
        <div id="manage-materials" class="section mt-5">
            <h2>Kelola Materi</h2>
            <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#createMaterialModal">Tambah Materi</button>
            <!-- Menampilkan materi -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Judul Materi</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP untuk menampilkan materi -->
                    <?php while ($row = $materials_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['judul_materi']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td>
                                <a href="edit-material.php?id_materi=<?= $row['id_materi'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete-material.php?id_materi=<?= $row['id_materi'] ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Review & Komentar -->
        <div id="reviews-comments" class="section mt-5">
            <h2>Review & Komentar</h2>
            <!-- Menampilkan review dan komentar -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Murid</th>
                        <th>Kelas</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP untuk menampilkan review dan komentar -->
                    <?php while ($row = $reviews_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['murid']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($row['rating']) ?></td>
                            <td><?= htmlspecialchars($row['komentar']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pesan -->
        <div id="messages" class="section mt-5">
            <h2>Pesan</h2>
            <!-- Menampilkan pesan -->
            <ul>
                <?php while ($row = $messages_result->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($row['pesan_notif']) ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <!-- Modal untuk menambah kelas -->
     <!-- Modal untuk menambah kelas -->


    <!-- (Sudah Anda buat sebelumnya) -->
    
    <!-- Modal untuk menambah materi -->
    <!-- (Sudah Anda buat sebelumnya) -->

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
