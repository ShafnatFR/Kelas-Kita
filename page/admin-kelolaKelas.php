<?php
session_start();
require 'db.php'; // Ensure database connection is established

// Check if user is logged in and has admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

// Ensure database connection is successful
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

/**
 * Fungsi untuk mengeksekusi prepared statement dengan aman dan mengambil data.
 * Fungsi ini menangani persiapan, eksekusi, pengambilan hasil, dan penutupan statement.
 * @param mysqli $conn Objek koneksi database.
 * @param string $sql String kueri SQL.
 * @param string $types Tipe parameter untuk bind_param (misalnya, 's', 'i').
 * @param array $params Array parameter untuk di-bind.
 * @return array|false Mengembalikan array asosiatif (untuk COUNT/SUM) atau array dari array asosiatif (untuk SELECT), atau false jika gagal.
 */
function fetchData(mysqli $conn, string $sql, string $types = '', array $params = []) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error preparing statement: " . $conn->error . " for query: " . $sql);
        return false;
    }

    if (!empty($params) && !empty($types)) {
        $bind_params = [];
        $bind_params[] = &$types;
        foreach ($params as &$param) {
            $bind_params[] = &$param;
        }
        call_user_func_array([$stmt, 'bind_param'], $bind_params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result === false) {
        error_log("Error getting result: " . $stmt->error . " for query: " . $sql);
        $stmt->close();
        return false;
    }
    
    // Detect if the query is a COUNT/SUM/MAX/MIN to fetch a single row
    if (strpos(strtoupper($sql), 'COUNT(') !== false || strpos(strtoupper($sql), 'SUM(') !== false ||
        strpos(strtoupper($sql), 'MAX(') !== false || strpos(strtoupper($sql), 'MIN(') !== false) {
        $data = $result->fetch_assoc();
    } else { // For regular SELECT queries that return multiple rows
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    $stmt->close(); // Close the statement after use
    return $data;
}

// Query for total users (needed for $stats['total_users'])
$userData = fetchData($conn, "SELECT COUNT(*) as total_users FROM tb_user");

// Query for class statistics (active, pending, non-active/rejected/draft)
$kelas_stats_data = fetchData($conn, "
    SELECT
        COUNT(CASE WHEN status_publikasi = 'aktif' THEN 1 END) AS total_aktif,
        COUNT(CASE WHEN status_publikasi = 'pending' THEN 1 END) AS total_pending,
        COUNT(CASE WHEN status_publikasi IN ('non-aktif', 'rejected', 'draft') THEN 1 END) AS total_nonaktif
    FROM tb_kelas
");

// Query for total reports
$laporanData = fetchData($conn, "SELECT COUNT(*) as total_laporan FROM tb_laporan");

// Query for 10 latest pending classes
$totalKelasPendingResult = fetchData($conn, "
    SELECT id_kelas, nama_kelas, status_publikasi, harga, tgl_dibuat
    FROM tb_kelas
    WHERE status_publikasi = 'pending'
    ORDER BY tgl_dibuat DESC
    LIMIT 10
");

// Query for 10 latest non-active/rejected/draft classes
$tbKelasNonAktifResult = fetchData($conn, "
    SELECT id_kelas, nama_kelas, status_publikasi, harga, tgl_dibuat
    FROM tb_kelas
    WHERE status_publikasi IN ('non-aktif', 'rejected', 'draft')
    ORDER BY tgl_dibuat DESC
    LIMIT 10
");

// Query for 10 latest active classes
$tbKelasAktifResult = fetchData($conn, "
    SELECT k.id_kelas, k.nama_kelas, k.status_publikasi, u.username AS mentor_username, k.tgl_dibuat
    FROM tb_kelas k
<<<<<<< HEAD
    JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    JOIN tb_user u ON m.id_user = u.id_user
    WHERE k.status_publikasi = 'aktif'
    ORDER BY k.tgl_dibuat DESC
=======
    JOIN tb_mentor m ON k.id_mentor=m.id_mentor
    JOIN tb_user u ON m.id_user=u.id_user
    WHERE status_publikasi LIKE 'approved'
    ORDER BY tgl_dibuat DESC
>>>>>>> 20e67d865377f14837315d81814d3a1eb751b56a
    LIMIT 10
");

// Data for statistic cards
$stats = [
    'total_users' => $userData['total_users'] ?? 0,
    'total_kelas_aktif' => $kelas_stats_data['total_aktif'] ?? 0,
    'total_kelas_pending' => $kelas_stats_data['total_pending'] ?? 0,
    'total_kelas_nonaktif' => $kelas_stats_data['total_nonaktif'] ?? 0,
    'total_laporan' => $laporanData['total_laporan'] ?? 0
];

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kelas</title>
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
            margin-left: 250px; /* Adjust based on sidebar width */
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        /* Using Bootstrap CSS variables for color consistency */
        .stat-card.primary { border-left-color: var(--bs-primary); }
        .stat-card.success { border-left-color: var(--bs-success); }
        .stat-card.info { border-left-color: var(--bs-info); }
        .stat-card.warning { border-left-color: var(--bs-warning); }
        .stat-card.danger { border-left-color: var(--bs-danger); }

        /* Custom badge styles for more consistent visual cues */
        .badge-status-aktif { background-color: var(--bs-success); color: #fff; }
        .badge-status-pending { background-color: var(--bs-info); color: #fff; }
        .badge-status-nonaktif { background-color: var(--bs-danger); color: #fff; }
        .badge-status-default { background-color: var(--bs-secondary); color: #fff; } /* Fallback */
    </style>
</head>
<body class="bg-light">
    <?php include "adminSidebar.php"; ?>
    
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-primary">
                        <i class="fas fa-chalkboard-teacher me-2"></i> Dashboard Kelas Admin
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
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Kelas Pending Terbaru</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th><th>Nama Kelas</th><th>Status Publikasi</th><th>Tanggal Dibuat</th><th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($totalKelasPendingResult)): ?>
                                            <?php $counter = 1; ?>
                                            <?php foreach ($totalKelasPendingResult as $kelas): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($kelas['nama_kelas']) ?></td>
                                                    <td>
                                                        <?php 
                                                            $status_badge_class = ($kelas['status_publikasi'] === 'pending') ? 'badge-status-pending' : 'badge-status-default';
                                                            echo '<span class="badge ' . $status_badge_class . '">' . htmlspecialchars(ucfirst($kelas['status_publikasi'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td><?= (new DateTime($kelas['tgl_dibuat']))->format('d M Y') ?></td>
                                                    <td>
                                                        <a href="admin-approveKelas.php?id=<?= $kelas['id_kelas'] ?>" class="btn btn-sm btn-success" title="Approve Kelas">
                                                            <i class="fas fa-check"></i> Approve
                                                        </a>
                                                        <a href="admin-rejectKelas.php?id=<?= $kelas['id_kelas'] ?>" class="btn btn-sm btn-danger" title="Reject Kelas" onclick="return confirm('Apakah Anda yakin ingin menolak kelas ini?');">
                                                            <i class="fas fa-times"></i> Reject
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center text-muted p-3">Tidak ada data kelas pending.</td></tr>
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
                            <h5 class="mb-0"><i class="fas fa-ban me-2"></i>Kelas Dinonaktifkan Terbaru</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th><th>Nama Kelas</th><th>Status Publikasi</th><th>Tanggal Dibuat</th><th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($tbKelasNonAktifResult)): ?>
                                            <?php $counter = 1; ?>
                                            <?php foreach ($tbKelasNonAktifResult as $kelas): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($kelas['nama_kelas']) ?></td>
                                                    <td>
                                                        <?php 
                                                            $status_badge_class = ($kelas['status_publikasi'] === 'non-aktif' || $kelas['status_publikasi'] === 'rejected' || $kelas['status_publikasi'] === 'draft') ? 'badge-status-nonaktif' : 'badge-status-default';
                                                            echo '<span class="badge ' . $status_badge_class . '">' . htmlspecialchars(ucfirst($kelas['status_publikasi'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td><?= (new DateTime($kelas['tgl_dibuat']))->format('d M Y') ?></td>
                                                    <td>
                                                        <a href="admin-aktifkanKelas.php?id=<?= $kelas['id_kelas'] ?>" class="btn btn-sm btn-success" title="Aktifkan Kelas">
                                                            <i class="fas fa-check-circle"></i> Aktifkan
                                                        </a>
                                                        <a href="admin-deleteKelas.php?id=<?= $kelas['id_kelas'] ?>" class="btn btn-sm btn-warning text-dark" title="Hapus Kelas" onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                                            <i class="fas fa-trash-alt"></i> Hapus
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center text-muted p-3">Tidak ada data kelas non-aktif.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Kelas Aktif Terbaru</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Kelas</th>
                                            <th>Status Publikasi</th>
                                            <th>Nama Mentor</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($tbKelasAktifResult)): ?>
                                            <?php $counter = 1; ?>
                                            <?php foreach ($tbKelasAktifResult as $kelas): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($kelas['nama_kelas']) ?></td>
                                                    <td>
                                                        <?php 
                                                            $status_badge_class = ($kelas['status_publikasi'] === 'aktif') ? 'badge-status-aktif' : 'badge-status-default';
                                                            echo '<span class="badge ' . $status_badge_class . '">' . htmlspecialchars(ucfirst($kelas['status_publikasi'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($kelas['mentor_username']) ?></td>
                                                    <td><?= (new DateTime($kelas['tgl_dibuat']))->format('d M Y') ?></td>
                                                    <td>
                                                        <a href="admin-nonAktifkanKelas.php?id=<?= $kelas['id_kelas'] ?>" class="btn btn-sm btn-danger" title="Non-Aktifkan Kelas" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan kelas ini?');">
                                                            <i class="fas fa-times-circle"></i> Non-Aktifkan
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6" class="text-center text-muted p-3">Tidak ada data kelas aktif.</td></tr>
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
// Close the main database connection
if ($conn) {
    $conn->close();
}
?>