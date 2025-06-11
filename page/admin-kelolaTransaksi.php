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

/**
 * Fungsi helper untuk menjalankan query dan mengambil satu baris hasil.
 * Ini menyelesaikan error "undefined function fetchData()".
 *
 * @param mysqli $connection Objek koneksi database.
 * @param string $query Query SQL yang akan dieksekusi.
 * @return array|null Mengembalikan hasil sebagai array asosiatif atau null jika tidak ada hasil.
 */
function fetchSingleRow($connection, $query) {
    $result = $connection->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    // Jika query gagal atau tidak ada hasil, kembalikan array default agar tidak error
    return null;
}

// Mengambil total pendapatan dari transaksi yang statusnya 'acc' (diterima)
$pendapatanData = fetchSingleRow($conn, "
    SELECT COALESCE(SUM(k.harga), 0) AS total_pendapatan
    FROM tb_kelas k
    JOIN tb_keranjang kk ON kk.id_kelas = k.id_kelas
    JOIN tb_transaksi tk ON tk.id_keranjang = kk.id_keranjang
    WHERE tk.status = 'Completed'
");

// Menghitung jumlah transaksi berdasarkan statusnya
$transaksiCount = fetchSingleRow($conn, "
    SELECT
        COUNT(CASE WHEN status = 'Completed' THEN 1 END) AS total_acc,
        COUNT(CASE WHEN status = 'Pending' THEN 1 END) AS total_pending,
        COUNT(CASE WHEN status = 'Cancelled' THEN 1 END) AS total_ditolak
    FROM tb_transaksi
");

// Mengambil semua data transaksi untuk ditampilkan di tabel
$transaksi_data_query = $conn->prepare("
    SELECT
        t.id_transaksi, t.bukti_transaksi, t.tgl_transaksi, t.status AS status_transaksi,
        k.nama_kelas, k.harga AS harga_kelas,
        u_user.username AS nama_user_pembeli,
        u_mentor.username AS nama_mentor_kelas
    FROM tb_transaksi t
    JOIN tb_keranjang kk ON t.id_keranjang = kk.id_keranjang
    JOIN tb_kelas k ON kk.id_kelas = k.id_kelas
    JOIN tb_user u_user ON kk.id_user = u_user.id_user
    JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    JOIN tb_user u_mentor ON m.id_user = u_mentor.id_user
    ORDER BY t.tgl_transaksi DESC
");

$transaksi_data_query->execute();
$transaksi_data_result = $transaksi_data_query->get_result();

// Data untuk statistik cards (logika diperbaiki)
$stats = [
    'total_pendapatan' => $pendapatanData['total_pendapatan'] ?? 0,
    'total_acc'        => $transaksiCount['total_acc'] ?? 0,
    'total_pending'    => $transaksiCount['total_pending'] ?? 0,
    'total_ditolak'    => $transaksiCount['total_ditolak'] ?? 0,
];

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
    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; }
        .content-wrapper { padding: 20px; flex: 1; margin-left: 250px; /* Sesuaikan dengan lebar sidebar */ }
        .stat-card { border-left: 4px solid; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card.primary { border-left-color: #0d6efd; }
        .stat-card.success { border-left-color: #198754; }
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
                unset($_SESSION['message'], $_SESSION['message_type']);
            }
            ?>

            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-primary"><i class="fas fa-tachometer-alt me-2"></i> Kelola Transaksi</h2>
                    <p class="text-muted">Selamat datang, <?= htmlspecialchars($namaAdmin) ?>!</p>
                </div>
            </div>
            
            <div class="row mb-5 gy-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card primary shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Transaksi</h6>
                                <h3 class="text-primary">Rp<?= number_format($stats['total_pendapatan'], 0, ',', '.') ?></h3>
                            </div>
                            <i class="fas fa-wallet fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card success shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Transaksi Diterima</h6>
                                <h3 class="text-success"><?= $stats['total_acc'] ?></h3>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card warning shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Transaksi Pending</h6>
                                <h3 class="text-warning"><?= $stats['total_pending'] ?></h3>
                            </div>
                            <i class="fas fa-hourglass-half fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card danger shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Transaksi Ditolak</h6>
                                <h3 class="text-danger"><?= $stats['total_ditolak'] ?></h3>
                            </div>
                            <i class="fas fa-times-circle fa-2x text-danger opacity-50"></i>
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
                                                    <td>Rp<?= number_format($transaksi['harga_kelas'], 0, ',', '.') ?></td>
                                                    <td><?= htmlspecialchars($transaksi['nama_mentor_kelas']) ?></td>
                                                    <td>
                                                        <?php if (!empty($transaksi['bukti_transaksi'])): ?>
                                                            <a href="uploads/bukti_transaksi/<?= htmlspecialchars($transaksi['bukti_transaksi']) ?>" target="_blank" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> Lihat
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">Tidak ada</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= (new DateTime($transaksi['tgl_transaksi']))->format('d M Y H:i') ?></td>
                                                    <td>
                                                        <?php
                                                            $status = $transaksi['status_transaksi'];
                                                            $badge_class = 'bg-secondary'; // Default
                                                            if ($status == 'Pending') {
                                                                $badge_class = 'bg-warning text-dark';
                                                            } elseif ($status == 'Completed') {
                                                                $badge_class = 'bg-success';
                                                            } elseif ($status == 'Cancelled') {
                                                                $badge_class = 'bg-danger';
                                                            }
                                                        ?>
                                                        <span class="badge <?= $badge_class ?>">
                                                            <?= htmlspecialchars(ucfirst($status)) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($transaksi['status_transaksi'] == 'Pending'): ?>
                                                            <div class="d-flex">
                                                                <?php if ($transaksi['status_transaksi'] == 'Pending' or 'Cancelled'): ?>
                                                                    <form action="admin-updateStatusTransaksi.php" method="POST" class="me-1">
                                                                        <input type="hidden" name="id_transaksi" value="<?= htmlspecialchars($transaksi['id_transaksi']) ?>">
                                                                        <input type="hidden" name="status" value="Completed">
                                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Anda yakin ingin MENERIMA transaksi ini?')">
                                                                            <i class="fas fa-check"></i> ACC
                                                                        </button>
                                                                    </form>

                                                                <form action="admin-updateStatusTransaksi.php" method="POST">
                                                                    <input type="hidden" name="id_transaksi" value="<?= htmlspecialchars($transaksi['id_transaksi']) ?>">
                                                                    <input type="hidden" name="status" value="Cancelled">
                                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin MENOLAK transaksi ini?')">
                                                                        <i class="fas fa-times"></i> Tolak
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
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
// --- Menutup statement dan koneksi yang benar-benar digunakan ---
if (isset($transaksi_data_query)) {
    $transaksi_data_query->close();
}
if ($conn) {
    $conn->close();
}
?>