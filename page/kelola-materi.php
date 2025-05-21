<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php"); // Jika bukan mentor, alihkan ke halaman login
    exit();
}

// Mengambil transaksi yang dilakukan oleh murid untuk kelas yang dikelola oleh mentor
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar yang sudah didefinisikan sebelumnya -->
    <?php include 'sidebar-mentor.php'; ?>

    <!-- Konten untuk Kelola Transaksi -->
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
