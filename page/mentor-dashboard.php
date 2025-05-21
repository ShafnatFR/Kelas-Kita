<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php"); // Jika bukan mentor, alihkan ke halaman login
    exit();
}

// Query untuk mengambil informasi penting
// Query untuk mengambil informasi statistik kelas yang dikelola oleh mentor
$stmt_stats = $conn->prepare("
    SELECT COUNT(k.id_kelas) AS total_kelas, 
           COUNT(m.id_materi) AS total_materi
    FROM tb_kelas k
    LEFT JOIN tb_materi m ON k.id_kelas = m.id_kelas
    WHERE k.id_mentor = ?
");
$stmt_stats->bind_param("i", $_SESSION['user_id']);  // Menggunakan id_mentor yang sesuai dengan user_id
$stmt_stats->execute();
$stats_result = $stmt_stats->get_result();
$stats = $stats_result->fetch_assoc();


$stmt_stats_transaksi = $conn->prepare("
    SELECT COUNT(t.id_transaksi) AS total_transaksi
    FROM tb_transaksi t
    JOIN tb_kelas k ON t.id_kelas = k.id_kelas
    WHERE k.id_mentor = ?
");
$stmt_stats_transaksi->bind_param("i", $_SESSION['user_id']);
$stmt_stats_transaksi->execute();
$transaksi_result = $stmt_stats_transaksi->get_result();
$transaksi = $transaksi_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include "sidebar-mentor.php";?>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper" style="margin-left: 250px; padding: 30px;">
        <h2>Selamat datang di Dashboard Mentor!</h2>
        
        <!-- Stats Overview -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Kelas</h5>
                        <p class="card-text"><?= htmlspecialchars($stats['total_kelas']) ?> Kelas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi</h5>
                        <p class="card-text"><?= htmlspecialchars($transaksi['total_transaksi']) ?> Transaksi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Materi</h5>
                        <p class="card-text"><?= htmlspecialchars($stats['total_materi']) ?> Materi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-5">
            <div class="col-md-6">
                <a href="create-class.php" class="btn btn-primary btn-lg">Tambah Kelas</a>
            </div>
            <div class="col-md-6">
                <a href="create-material.php" class="btn btn-secondary btn-lg">Tambah Materi</a>
            </div>
        </div>
    </div>

    <!-- Tambahkan link ke Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
