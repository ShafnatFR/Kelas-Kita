<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php"); // Jika bukan mentor, alihkan ke halaman login
    exit();
}

// Mengambil kelas yang dikelola oleh mentor berdasarkan id_user
$stmt_classes = $conn->prepare("
    SELECT k.id_kelas, k.nama_kelas, k.kategori, k.harga, k.description
    FROM tb_kelas k
    JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    JOIN tb_user u ON m.id_user = u.id_user
    WHERE u.id_user = ?
");
$stmt_classes->bind_param("i", $_SESSION['user_id']); // Menggunakan user_id yang disimpan dalam session
$stmt_classes->execute();
$classes_result = $stmt_classes->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/keloladata-mentor.css">
</head>
<body class="bg-light">
    <!-- Sidebar yang sudah didefinisikan sebelumnya -->
    <?php include 'sidebar-mentor.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
    <h2>Kelola Kelas</h2>

    <!-- Membungkus tabel dengan class table-wrapper -->
    <div class="table-wrapper">
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
</div>

    <!-- Tambahkan link ke Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
