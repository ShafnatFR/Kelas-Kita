<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

// 1. Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$id_user_mentor = $_SESSION['id'];
$id_mentor = 0;

// 2. Ambil id_mentor dari id_user yang sedang login
$mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ? LIMIT 1");
$mentor_query->bind_param("i", $id_user_mentor);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

if ($mentor_result->num_rows > 0) {
    $mentor_data = $mentor_result->fetch_assoc();
    $id_mentor = $mentor_data['id_mentor'];
} else {
    die("Error: Data mentor tidak ditemukan.");
}
$mentor_query->close();

// 3. Query utama untuk mengambil data penjualan kelas
$stmt_transactions = $conn->prepare("
    SELECT 
        t.id_transaksi,
        u.username AS nama_murid,
        u.first_name AS nama_depan_murid,
        u.last_name AS nama_belakang_murid,
        k.nama_kelas,
        k.harga AS harga_kelas,
        t.tgl_transaksi,
        t.status
    FROM tb_transaksi t
    JOIN tb_user u ON t.id_user = u.id_user
    JOIN tb_kelas k ON t.id_kelas = k.id_kelas
    WHERE k.id_mentor = ?
      AND t.status = 'Completed'
    ORDER BY t.tgl_transaksi DESC
");
$stmt_transactions->bind_param("i", $id_mentor);
$stmt_transactions->execute();
$transactions_result = $stmt_transactions->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Penjualan - Dashboard Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Memuat CSS dari sidebar Anda (path ini harus benar) -->
    <link rel="stylesheet" href="../assets/css/sidebar-mentor.css"> 

    <style>
        /* CSS tambahan untuk styling konten agar tetap modern */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.5rem;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fc;
        }
        .badge {
            font-size: 0.85em;
            padding: 0.5em 0.75em;
        }
        .empty-state {
            text-align: center;
            padding: 4rem;
            color: #6c757d;
        }
        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #e3e6f0;
        }
    </style>
</head>
<body>
    <!-- Memanggil sidebar Anda yang memiliki position:fixed -->
    <?php include 'sidebar-mentor.php'; ?>

    <!-- MEMBUNGKUS KONTEN DENGAN KELAS DARI CSS ANDA -->
    <div class="main-content">
        <!-- Header Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-receipt me-2"></i>Riwayat Penjualan Kelas
            </h1>
        </div>

        <!-- Card untuk Tabel Data -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Murid dengan Pembelian Sukses</h5>
                <span class="badge bg-primary-subtle text-primary-emphasis"><?= $transactions_result->num_rows ?> Transaksi</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">#</th>
                                <th scope="col">Nama Murid</th>
                                <th scope="col">Kelas yang Dibeli</th>
                                <th scope="col">Tanggal Transaksi</th>
                                <th scope="col" class="text-end">Harga</th>
                                <th scope="col" class="text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($transactions_result->num_rows > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($row = $transactions_result->fetch_assoc()): ?>
                                    <tr>
                                        <th scope="row" class="ps-4"><?= $no++ ?></th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle fa-2x me-3 text-secondary"></i>
                                                <div>
                                                    <?php 
                                                        $nama_lengkap = trim($row['nama_depan_murid'] . ' ' . $row['nama_belakang_murid']);
                                                        echo htmlspecialchars($nama_lengkap ?: $row['nama_murid']); 
                                                    ?>
                                                    <small class="d-block text-muted">@<?= htmlspecialchars($row['nama_murid']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                                        <td><?= date('d M Y', strtotime($row['tgl_transaksi'])) ?></td>
                                        <td class="text-end fw-bold">Rp <?= number_format($row['harga_kelas'], 0, ',', '.') ?></td>
                                        <td class="text-center pe-4">
                                            <span class="badge rounded-pill bg-success-subtle text-success-emphasis">
                                                <i class="fas fa-check-circle me-1"></i><?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="icon"><i class="fas fa-box-open"></i></div>
                                            <h4>Belum Ada Penjualan</h4>
                                            <p class="mb-0">Saat ini belum ada murid yang menyelesaikan pembelian untuk kelas Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- Akhir dari .main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
