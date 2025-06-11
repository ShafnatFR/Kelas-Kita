<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database Anda

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$transaksiData = fetchData($conn, "
    SELECT COALESCE(SUM(k.harga), 0) AS total_transaksi
    FROM tb_kelas k
    INNER JOIN tb_keranjang kk ON kk.id_kelas = k.id_kelas
    INNER JOIN tb_transaksi tk ON tk.id_keranjang = kk.id_keranjang
    WHERE tk.status = 'Completed'
");

// --- QUERY UNTUK DATA TRANSAKSI LANGSUNG DARI TABEL ---
$transaksi_data_query = $conn->prepare("
    SELECT
        t.id_transaksi,
        t.bukti_transaksi,
        t.tgl_transaksi,
        t.status AS status_transaksi,
        k.nama_kelas,
        k.harga AS harga_kelas,
        u_user.username AS nama_user_pembeli,
        u_mentor.username AS nama_mentor_kelas
    FROM
        tb_transaksi t
    JOIN
        tb_keranjang kk ON t.id_keranjang = kk.id_keranjang
    JOIN
        tb_kelas k ON kk.id_kelas = k.id_kelas
    JOIN
        tb_user u_user ON kk.id_user = u_user.id_user
    JOIN
        tb_mentor m ON k.id_mentor = m.id_mentor
    JOIN
        tb_user u_mentor ON m.id_user = u_mentor.id_user
    ORDER BY t.tgl_transaksi DESC
");
if (!$transaksi_data_query) {
    die("Error preparing transaksi_data_query: " . $conn->error);
}
$transaksi_data_query->execute();
$transaksi_data_result = $transaksi_data_query->get_result();

// Data untuk statistik cards
$stats = array(
    'total_transaksi' => $transaksiData['total_transaksi'] ?? 0,
);

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .content-wrapper {
            padding: 20px;
            flex: 1;
            margin-left: 250px; /* Sesuaikan dengan lebar sidebar */
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-card.primary { border-left-color: #0d6efd; }
        .stat-card.success { border-left-color: #198754; }
        .stat-card.info { border-left-color: #0dcaf0; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.danger { border-left-color: #dc3545; }
    </style>
</head>
<body class="bg-light">
    <?php include "adminSidebar.php"; ?>
    
    <div class="content-wrapper">
        <div class="container-fluid">
            <?php
            // --- Feedback Message Display ---
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-' . htmlspecialchars($_SESSION['message_type']) . ' alert-dismissible fade show mt-3" role="alert">';
                echo htmlspecialchars($_SESSION['message']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
                // Clear the message after displaying it
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>

            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Kelola Transaksi
                    </h2>
                    <p class="text-muted">Selamat datang, <?= htmlspecialchars($namaAdmin) ?>!</p>
                </div>
            </div>
            
            <div class="row mb-5 gy-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card success shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Kelas Aktif</h6>
                                <h3 class="text-success"><?= $stats['total_transaksi'] ?></h3>
                            </div>
                            <i class="fas fa-book-open fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card info shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Kelas Pending</h6>
                                <h3 class="text-info"><?= $stats[''] ?></h3>
                            </div>
                            <i class="fas fa-hourglass-half fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card danger shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Kelas Non-Aktif</h6>
                                <h3 class="text-danger"><?= $stats[''] ?></h3>
                            </div>
                            <i class="fas fa-book-dead fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card warning shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Laporan</h6>
                                <h3 class="text-warning"><?= $stats[''] ?></h3>
                            </div>
                            <i class="fas fa-file-alt fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 gy-4"> 
                
                <div class="col-lg-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Detail Transaksi</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>ID Transaksi</th>
                                            <th>Nama Pembeli</th>
                                            <th>Nama Kelas</th>
                                            <th>Harga Kelas</th>
                                            <th>Nama Mentor</th>
                                            <th>Bukti Transaksi</th>
                                            <th>Tgl Transaksi</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($transaksi_data_result->num_rows > 0): ?>
                                            <?php $counter = 1; ?>
                                            <?php while ($transaksi = $transaksi_data_result->fetch_assoc()): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($transaksi['id_transaksi']) ?></td>
                                                    <td><?= htmlspecialchars($transaksi['nama_user_pembeli']) ?></td>
                                                    <td><?= htmlspecialchars($transaksi['nama_kelas']) ?></td>
                                                    <td>Rp<?= number_format($transaksi['harga_kelas'], 2, ',', '.') ?></td>
                                                    <td><?= htmlspecialchars($transaksi['nama_mentor_kelas']) ?></td>
                                                    <td>
                                                        <?php if (!empty($transaksi['bukti_transaksi'])): ?>
                                                            <a href="uploads/bukti_transaksi/<?= htmlspecialchars($transaksi['bukti_transaksi']) ?>" target="_blank" class="btn btn-sm btn-info">Lihat Bukti</a>
                                                        <?php else: ?>
                                                            Tidak ada
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= (new DateTime($transaksi['tgl_transaksi']))->format('d M Y H:i') ?></td>
                                                    <td>
                                                        <span class="badge 
                                                            <?php
                                                                if ($transaksi['status_transaksi'] == 'pending') echo 'bg-warning text-dark';
                                                                else if ($transaksi['status_transaksi'] == 'acc') echo 'bg-success';
                                                                else if ($transaksi['status_transaksi'] == 'ditolak') echo 'bg-danger';
                                                                else echo 'bg-secondary';
                                                            ?>
                                                        ">
                                                            <?= htmlspecialchars(ucfirst($transaksi['status_transaksi'])) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($transaksi['status_transaksi'] == 'pending'): ?>
                                                            <a href="admin-accTransaksi.php?id=<?= urlencode($transaksi['id_transaksi']) ?>" class="btn btn-sm btn-success me-1">ACC</a>
                                                            <a href="admin-tolakTransaksi.php?id=<?= urlencode($transaksi['id_transaksi']) ?>" class="btn btn-sm btn-danger">Tolak</a>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="10" class="text-center text-muted p-3">Tidak ada data transaksi.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// --- Close statements dan connection ---
if (isset($kelas_stats_query) && $kelas_stats_query) $kelas_stats_query->close();
if (isset($totalUser_stmt) && $totalUser_stmt) $totalUser_stmt->close();
if (isset($totalLaporan_stmt) && $totalLaporan_stmt) $totalLaporan_stmt->close();
if (isset($transaksi_data_query) && $transaksi_data_query) $transaksi_data_query->close();
// Note: tbKelasNonAktif and tbKelasAktif were queried but not used in the HTML of this specific page.
// If they are not used, you can remove their queries and closing statements to optimize.
// For now, keeping them as they might be intended for future use or other dashboard elements.
if (isset($tbKelasNonAktif) && $tbKelasNonAktif) $tbKelasNonAktif->close();
if (isset($tbKelasAktif) && $tbKelasAktif) $tbKelasAktif->close();

if ($conn) $conn->close();
?>