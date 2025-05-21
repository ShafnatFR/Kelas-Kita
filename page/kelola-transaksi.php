<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php"); // Jika bukan mentor, alihkan ke halaman login
    exit();
}

// Mengambil transaksi yang dilakukan oleh murid untuk kelas yang dikelola oleh mentor
$stmt_transactions = $conn->prepare("
    SELECT t.id_transaksi, u.username AS murid, k.nama_kelas, t.bukti_transaksi, t.tgl_transaksi
    FROM tb_transaksi t
    JOIN tb_user u ON t.id_user = u.id_user
    JOIN tb_kelas k ON t.id_kelas = k.id_kelas
    WHERE t.id_user = ?
");
$stmt_transactions->bind_param("i", $_SESSION['user_id']); // Menggunakan user_id yang disimpan dalam session
$stmt_transactions->execute();
$transactions_result = $stmt_transactions->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="../assets/css/keloladata-mentor.css">
</head>
<body>
    <!-- Sidebar yang sudah didefinisikan sebelumnya -->
    <?php include 'sidebar-mentor.php'; ?>

    <!-- Konten untuk Kelola Transaksi -->
    <div class="content-wrapper">
    <h2>Kelola Transaksi</h2>

    <!-- Membungkus tabel dengan class table-wrapper -->
    <div class="table-wrapper">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Nama Murid</th>
                    <th>Kelas</th>
                    <th>Bukti Transaksi</th>
                    <th>Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $transactions_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_transaksi']) ?></td>
                        <td><?= htmlspecialchars($row['murid']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                        <td><a href="../uploads/<?= htmlspecialchars($row['bukti_transaksi']) ?>" target="_blank">Lihat Bukti</a></td>
                        <td><?= htmlspecialchars($row['tgl_transaksi']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tambahkan link ke Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
