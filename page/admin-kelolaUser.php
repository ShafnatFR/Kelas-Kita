<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginAdmin.php");
    exit();
}

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// --- PERBAIKAN: QUERY UNTUK STATS KELAS ---
// Query untuk total kelas dengan berbagai status
$kelas_stats_query = $conn->prepare("
    SELECT
        COUNT(CASE WHEN status_publikasi = 'approved' THEN 1 END) AS total_aktif,
        COUNT(CASE WHEN status_publikasi = 'pending' THEN 1 END) AS total_pending,
        COUNT(CASE WHEN status_publikasi = 'rejected' OR status_publikasi = 'draft' THEN 1 END) AS total_nonaktif
    FROM tb_kelas
");
$kelas_stats_query->execute();
$kelas_stats_result = $kelas_stats_query->get_result();
$kelas_stats_data = $kelas_stats_result->fetch_assoc();


// Query untuk mengambil total user
$totalUser_stmt = $conn->prepare("SELECT COUNT(*) as total_users FROM tb_user");
$totalUser_stmt->execute();
$userData = $totalUser_stmt->get_result()->fetch_assoc();

// Query untuk mengambil total laporan
$totalLaporan_stmt = $conn->prepare("SELECT COUNT(*) as total_laporan FROM tb_laporan");
$totalLaporan_stmt->execute();
$laporanData = $totalLaporan_stmt->get_result()->fetch_assoc();

// Query untuk mengambil 10 user aktif terbaru
$recent_users_query = $conn->prepare("
    SELECT username, role, status, tgl_dibuat
    FROM tb_user
    WHERE status = 'aktif'
    ORDER BY tgl_dibuat DESC
    LIMIT 10
");
$recent_users_query->execute();
$recent_users_result = $recent_users_query->get_result();

// --- PERBAIKAN: QUERY USER NON-AKTIF ---
// Query untuk mengambil 10 user non-aktif terbaru
$tbUserNonAktif_stmt = $conn->prepare("
    SELECT id_user, username, role, status, tgl_dibuat
    FROM tb_user
    WHERE status = 'non-aktif'
    ORDER BY tgl_dibuat DESC
    LIMIT 10
");
$tbUserNonAktif_stmt->execute();
$tbUserNonAktifResult = $tbUserNonAktif_stmt->get_result();

// Data untuk statistik cards
$stats = array(
    'total_users' => $userData['total_users'] ?? 0,
    'total_kelas_aktif' => $kelas_stats_data['total_aktif'] ?? 0,
    'total_kelas_pending' => $kelas_stats_data['total_pending'] ?? 0,
    'total_kelas_nonaktif' => $kelas_stats_data['total_nonaktif'] ?? 0,
    'total_laporan' => $laporanData['total_laporan'] ?? 0
);

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>
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
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard Admin
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
                                <h3 class="text-success"><?= $stats['total_kelas_aktif'] ?></h3>
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
                                <h3 class="text-info"><?= $stats['total_kelas_pending'] ?></h3>
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
                                <h3 class="text-danger"><?= $stats['total_kelas_nonaktif'] ?></h3>
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
                                <h3 class="text-warning"><?= $stats['total_laporan'] ?></h3>
                            </div>
                            <i class="fas fa-file-alt fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 gy-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i>User Aktif Terbaru</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th><th>Username</th><th>Role</th><th>Status</th><th>Tgl Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($recent_users_result->num_rows > 0): ?>
                                            <?php $user_counter = 1; ?>
                                            <?php while ($user = $recent_users_result->fetch_assoc()): ?>
                                                <tr>
                                                    <th><?= $user_counter++ ?></th>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                                                    <td><span class="badge bg-success">Aktif</span></td>
                                                    <td><?= (new DateTime($user['tgl_dibuat']))->format('d M Y') ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center text-muted p-3">Tidak ada data.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="fas fa-user-slash me-2"></i>User Dinonaktifkan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th><th>Username</th><th>Role</th><th>Status</th><th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($tbUserNonAktifResult->num_rows > 0): ?>
                                            <?php $user_counter = 1; ?>
                                            <?php while ($user = $tbUserNonAktifResult->fetch_assoc()): ?>
                                                <tr>
                                                    <th><?= $user_counter++ ?></th>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                                                    <td>
                                                        <span class="badge bg-danger">Non-Aktif</span>
                                                    </td>
                                                    <td>
                                                        <a href="activate_user.php?id=<?= $user['id_user'] ?>" class="btn btn-sm btn-success">Aktifkan</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center text-muted p-3">Tidak ada user non-aktif.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="fas fa-user-slash me-2"></i>User yang Dilaporkan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Kategori Laporan</th>
                                            <th>Tgl Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($tbUserNonAktifResult->num_rows > 0): ?>
                                            <?php $user_counter = 1; ?>
                                            <?php while ($user = $tbUserNonAktifResult->fetch_assoc()): ?>
                                                <tr>
                                                    <th><?= $user_counter++ ?></th>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                                                    <td>
                                                        <span class="badge bg-danger">Non-Aktif</span>
                                                    </td>
                                                    <td>
                                                        <a href="activate_user.php?id=<?= $user['id_user'] ?>" class="btn btn-sm btn-success">Aktifkan</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center text-muted p-3">Tidak ada user non-aktif.</td></tr>
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
</body>
</html>

<?php
// --- PERBAIKAN: Close statements dan connection ---
if (isset($kelas_stats_query)) $kelas_stats_query->close();
if (isset($totalUser_stmt)) $totalUser_stmt->close();
if (isset($totalLaporan_stmt)) $totalLaporan_stmt->close();
if (isset($recent_users_query)) $recent_users_query->close();
if (isset($tbUserNonAktif_stmt)) $tbUserNonAktif_stmt->close();
if (isset($latest_classes_table_query)) $latest_classes_table_query->close();

if ($conn) $conn->close();
?>