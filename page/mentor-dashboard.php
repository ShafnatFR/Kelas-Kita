<?php
session_start();
require 'db.php';

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

// Ambil id_mentor berdasarkan id_user yang login
$user_id = $_SESSION['id']; // Fix: gunakan 'id' bukan 'user_id'
$mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
$mentor_query->bind_param("i", $user_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

if ($mentor_result->num_rows === 0) {
    // Jika belum ada record mentor, redirect ke become-mentor
    header("Location: become-mentor.php");
    exit();
}

$mentor_row = $mentor_result->fetch_assoc();
$id_mentor = $mentor_row['id_mentor'];
$mentor_query->close();

// Query untuk mengambil statistik kelas dan materi
$stmt_stats = $conn->prepare("
    SELECT 
    COUNT(DISTINCT k.id_kelas) AS total_kelas,
    COALESCE(SUM(k.harga), 0) AS total_nilai_kelas
    FROM tb_kelas k
    WHERE k.id_mentor = ?
");
$stmt_stats->bind_param("i", $id_mentor);
$stmt_stats->execute();
$stats_result = $stmt_stats->get_result();
$stats = $stats_result->fetch_assoc();
$stmt_stats->close();

// Query untuk mengambil total materi (jika tabel tb_materi ada)
$total_materi = 0;
$materi_query = "SELECT COUNT(*) as total_materi FROM tb_materi m 
                 JOIN tb_kelas k ON m.id_kelas = k.id_kelas 
                 WHERE k.id_mentor = ?";
$stmt_materi = $conn->prepare($materi_query);
if ($stmt_materi) {
    $stmt_materi->bind_param("i", $id_mentor);
    $stmt_materi->execute();
    $materi_result = $stmt_materi->get_result();
    $materi_data = $materi_result->fetch_assoc();
    $total_materi = $materi_data['total_materi'];
    $stmt_materi->close();
}

// Query untuk mengambil total transaksi (jika tabel tb_transaksi ada)
$total_transaksi = 0;
$transaksi_query = "SELECT COUNT(*) as total_transaksi FROM tb_transaksi t
                    JOIN tb_kelas k ON t.id_kelas = k.id_kelas
                    WHERE k.id_mentor = ?";
$stmt_transaksi = $conn->prepare($transaksi_query);
if ($stmt_transaksi) {
    $stmt_transaksi->bind_param("i", $id_mentor);
    $stmt_transaksi->execute();
    $transaksi_result = $stmt_transaksi->get_result();
    $transaksi_data = $transaksi_result->fetch_assoc();
    $total_transaksi = $transaksi_data['total_transaksi'];
    $stmt_transaksi->close();
}

// Query untuk mendapatkan kelas terbaru
$recent_classes_query = $conn->prepare("
    SELECT nama_kelas, kategori, harga 
    FROM tb_kelas 
    WHERE id_mentor = ? 
    ORDER BY id_kelas DESC 
    LIMIT 5
");
$recent_classes_query->bind_param("i", $id_mentor);
$recent_classes_query->execute();
$recent_classes_result = $recent_classes_query->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ubah role kembali menjadi peserta
    $stmt = $conn->prepare("UPDATE tb_user SET role = 'murid' WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();

    // Update session role
    $_SESSION['role'] = 'murid';

    // Redirect kembali ke halaman utama
    header("Location: index.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-card.primary {
            border-left-color: #007bff;
        }
        .stat-card.success {
            border-left-color: #28a745;
        }
        .stat-card.info {
            border-left-color: #17a2b8;
        }
        .stat-card.warning {
            border-left-color: #ffc107;
        }
    </style>
</head>
<body class="bg-light">
    <?php include "sidebar-mentor.php"; ?>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper" style="margin-left: 250px; padding: 30px;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-primary">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard Mentor
                </h2>
                <p class="text-muted">Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
            </div>
        </div>
        
        <!-- Stats Overview -->
        <div class="row mb-5">
            <div class="col-md-3">
                <div class="card stat-card primary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-muted">Total Kelas</h6>
                                <h3 class="text-primary"><?= $stats['total_kelas'] ?? 0 ?></h3>
                                <small class="text-muted">Kelas aktif</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-book fa-2x text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stat-card success shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-muted">Total Nilai Kelas</h6>
                                <h3 class="text-success">Rp <?= number_format($stats['total_nilai_kelas'] ?? 0, 0, ',', '.') ?></h3>
                                <small class="text-muted">Nilai keseluruhan</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-money-bill-wave fa-2x text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stat-card info shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-muted">Total Materi</h6>
                                <h3 class="text-info"><?= $total_materi ?></h3>
                                <small class="text-muted">Materi pembelajaran</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-file-alt fa-2x text-info opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stat-card warning shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-muted">Total Transaksi</h6>
                                <h3 class="text-warning"><?= $total_transaksi ?></h3>
                                <small class="text-muted">Pembelian kelas</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Quick Actions -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="create-class.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Kelas Baru
                            </a>
                            <a href="kelola-kelas.php" class="btn btn-outline-primary">
                                <i class="fas fa-cog me-2"></i>
                                Kelola Kelas
                            </a>
                            <a href="create-material.php" class="btn btn-secondary">
                                <i class="fas fa-file-plus me-2"></i>
                                Tambah Materi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Classes -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Kelas Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_classes_result->num_rows > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php while ($class = $recent_classes_result->fetch_assoc()): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($class['nama_kelas']) ?></h6>
                                            <small class="text-muted"><?= htmlspecialchars($class['kategori']) ?></small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">
                                            Rp <?= number_format($class['harga'], 0, ',', '.') ?>
                                        </span>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center mb-0">Belum ada kelas yang dibuat</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST">
                    <button type="submit" class="btn btn-warning btn-lg mt-4">Switch to Peserta</button>
                </form>
            </div>
        </div>
    </div>

        <!-- Debug Info (hapus setelah selesai testing) -->
        <?php if (isset($_GET['debug'])): ?>
            <div class="alert alert-info mt-4">
                <strong>Debug Info:</strong><br>
                User ID: <?= $_SESSION['id'] ?? 'Not set' ?><br>
                Mentor ID: <?= $id_mentor ?? 'Not found' ?><br>
                Username: <?= $_SESSION['username'] ?? 'Not set' ?><br>
                Total Kelas: <?= $stats['total_kelas'] ?? 0 ?><br>
                Total Nilai: <?= $stats['total_nilai_kelas'] ?? 0 ?>
            </div>  
        <?php endif; ?>
    </div>

    <!-- Tombol Switch to Peserta -->
                
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$recent_classes_query->close();
$conn->close();
?>