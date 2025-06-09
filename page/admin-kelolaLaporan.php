<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Filter berdasarkan status
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// QUERY UNTUK COUNT
// Count total laporan
$countTL = $conn->prepare("
    SELECT COUNT(*) as total_laporan
    FROM tb_laporan r
    JOIN tb_user u ON u.id_user = r.id_user
    JOIN tb_kelas k ON k.id_kelas = r.id_kelas
");
if (!$countTL) {
    die("Error preparing countTL: " . $conn->error);
}
$countTL->execute();
$tlData = $countTL->get_result()->fetch_assoc();

// Count total laporan Pornografi
$countTLP = $conn->prepare("
    SELECT COUNT(*) as total_pornografi
    FROM tb_laporan r
    JOIN tb_user u ON u.id_user = r.id_user
    JOIN tb_kelas k ON k.id_kelas = r.id_kelas
    WHERE r.kategori_report LIKE 'Pornografi'
");
if (!$countTLP) {
    die("Error preparing countTLP: " . $conn->error);
}
$countTLP->execute();
$tlpData = $countTLP->get_result()->fetch_assoc();

// Count total laporan Materi tidak relevan
$countTLMTR = $conn->prepare("
    SELECT COUNT(*) as total_materi_tidak_relevan
    FROM tb_laporan r
    JOIN tb_user u ON u.id_user = r.id_user
    JOIN tb_kelas k ON k.id_kelas = r.id_kelas
    WHERE r.kategori_report LIKE 'Materi tidak relevan'
");
if (!$countTLMTR) {
    die("Error preparing countTLMTR: " . $conn->error);
}
$countTLMTR->execute();
$tlmrData = $countTLMTR->get_result()->fetch_assoc();

// Count total laporan Penggunaan kata kasar
$countTLPKK = $conn->prepare("
    SELECT COUNT(*) as total_kata_kasar
    FROM tb_laporan r
    JOIN tb_user u ON u.id_user = r.id_user
    JOIN tb_kelas k ON k.id_kelas = r.id_kelas
    WHERE r.kategori_report LIKE 'Penggunaan kata kasar'
");
if (!$countTLPKK) {
    die("Error preparing countTLPKK: " . $conn->error);
}
$countTLPKK->execute();
$tlpkkData = $countTLPKK->get_result()->fetch_assoc();

// QUERY UNTUK TABEL - Memasukkan filter status dan kolom status_laporan
$base_query = "
    SELECT r.id_report, u.username, k.nama_kelas, r.kategori_report, r.keterangan_report, r.tgl_dibuat, r.status_laporan
    FROM tb_laporan r
    JOIN tb_user u ON u.id_user = r.id_user
    JOIN tb_kelas k ON k.id_kelas = r.id_kelas
";

$where_clause_p = " WHERE r.kategori_report LIKE 'Pornografi'";
$where_clause_pkk = " WHERE r.kategori_report LIKE 'Penggunaan kata kasar'";
$where_clause_mtr = " WHERE r.kategori_report LIKE 'Materi tidak relevan'";

if (!empty($filter_status)) {
    $where_clause_p .= " AND r.status_laporan = ?";
    $where_clause_pkk .= " AND r.status_laporan = ?";
    $where_clause_mtr .= " AND r.status_laporan = ?";
}

$order_by = " ORDER BY r.tgl_dibuat DESC";

// Query laporan Pornografi untuk tabel
$totalLaporanP = $conn->prepare($base_query . $where_clause_p . $order_by);
if (!$totalLaporanP) { die("Error preparing totalLaporanP: " . $conn->error); }
if (!empty($filter_status)) { $totalLaporanP->bind_param("s", $filter_status); }
$totalLaporanP->execute();
$pDataResult = $totalLaporanP->get_result();

// Query laporan Penggunaan kata kasar untuk tabel
$totalLaporanPkk = $conn->prepare($base_query . $where_clause_pkk . $order_by);
if (!$totalLaporanPkk) { die("Error preparing totalLaporanPkk: " . $conn->error); }
if (!empty($filter_status)) { $totalLaporanPkk->bind_param("s", $filter_status); }
$totalLaporanPkk->execute();
$pkkDataResult = $totalLaporanPkk->get_result();

// Query laporan Materi tidak relevan untuk tabel
$totalLaporanMtr = $conn->prepare($base_query . $where_clause_mtr . $order_by);
if (!$totalLaporanMtr) { die("Error preparing totalLaporanMtr: " . $conn->error); }
if (!empty($filter_status)) { $totalLaporanMtr->bind_param("s", $filter_status); }
$totalLaporanMtr->execute();
$mtrDataResult = $totalLaporanMtr->get_result();

// Data untuk statistik cards - DIPERBAIKI sesuai dengan query count
$stats = array(
    'total_laporan' => $tlData['total_laporan'] ?? 0,
    'total_pornografi' => $tlpData['total_pornografi'] ?? 0,
    'total_materi_tidak_relevan' => $tlmrData['total_materi_tidak_relevan'] ?? 0,
    'total_kata_kasar' => $tlpkkData['total_kata_kasar'] ?? 0,
);

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Laporan</title>
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
                        Kelola Laporan
                    </h2>
                    <p class="text-muted">Selamat datang, <?= htmlspecialchars($namaAdmin) ?>!</p>
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                            <?= $_SESSION['message'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php 
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                        ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row mb-5 gy-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card primary shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Laporan</h6>
                                <h3 class="text-primary"><?= $stats['total_laporan'] ?></h3>
                            </div>
                            <i class="fas fa-file-alt fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card danger shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Laporan Pornografi</h6>
                                <h3 class="text-danger"><?= $stats['total_pornografi'] ?></h3>
                            </div>
                            <i class="fas fa-exclamation-triangle fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card warning shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Materi Tidak Relevan</h6>
                                <h3 class="text-warning"><?= $stats['total_materi_tidak_relevan'] ?></h3>
                            </div>
                            <i class="fas fa-ban fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card info shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Kata Kasar</h6>
                                <h3 class="text-info"><?= $stats['total_kata_kasar'] ?></h3>
                            </div>
                            <i class="fas fa-comment-slash fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="filterStatus" class="form-label">Filter Status Laporan:</label>
                    <select class="form-select" id="filterStatus" onchange="window.location.href='admin-kelolaLaporan.php?status=' + this.value;">
                        <option value="">Semua Status</option>
                        <option value="Belum Diproses" <?= ($filter_status == 'Belum Diproses') ? 'selected' : '' ?>>Belum Diproses</option>
                        <option value="Diproses" <?= ($filter_status == 'Diproses') ? 'selected' : '' ?>>Diproses</option>
                        <option value="Selesai" <?= ($filter_status == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                        <option value="Ditolak" <?= ($filter_status == 'Ditolak') ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Laporan Pornografi</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>ID Laporan</th>
                                            <th>Username</th>
                                            <th>Nama Kelas</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Status</th> <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($pDataResult->num_rows > 0): ?>
                                            <?php $counter = 1; ?>
                                            <?php while ($laporan = $pDataResult->fetch_assoc()): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($laporan['id_report']) ?></td>
                                                    <td><?= htmlspecialchars($laporan['username']) ?></td>
                                                    <td><?= htmlspecialchars($laporan['nama_kelas']) ?></td>
                                                    <td><span class="badge bg-danger"><?= htmlspecialchars($laporan['kategori_report']) ?></span></td>
                                                    <td><?= htmlspecialchars(substr($laporan['keterangan_report'], 0, 50)) . (strlen($laporan['keterangan_report']) > 50 ? '...' : '') ?></td>
                                                    <td><?= (new DateTime($laporan['tgl_dibuat']))->format('d M Y H:i') ?></td>
                                                    <td>
                                                        <?php
                                                        $status_badge_class = '';
                                                        switch ($laporan['status_laporan']) {
                                                            case 'Belum Diproses': $status_badge_class = 'bg-warning text-dark'; break;
                                                            case 'Diproses': $status_badge_class = 'bg-info'; break;
                                                            case 'Selesai': $status_badge_class = 'bg-success'; break;
                                                            case 'Ditolak': $status_badge_class = 'bg-danger'; break;
                                                            default: $status_badge_class = 'bg-secondary'; break;
                                                        }
                                                        ?>
                                                        <span class="badge <?= $status_badge_class ?>"><?= htmlspecialchars($laporan['status_laporan']) ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="admin-detail-report.php?id=<?= $laporan['id_report'] ?>" class="btn btn-sm btn-primary">Detail</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="9" class="text-center text-muted p-3">Tidak ada laporan pornografi.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-comment-slash me-2"></i>Laporan Penggunaan Kata Kasar</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>ID Laporan</th>
                                            <th>Username</th>
                                            <th>Nama Kelas</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Status</th> <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($pkkDataResult->num_rows > 0): ?>
                                            <?php $counter = 1; ?>
                                            <?php while ($laporan = $pkkDataResult->fetch_assoc()): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($laporan['id_report']) ?></td>
                                                    <td><?= htmlspecialchars($laporan['username']) ?></td>
                                                    <td><?= htmlspecialchars($laporan['nama_kelas']) ?></td>
                                                    <td><span class="badge bg-info"><?= htmlspecialchars($laporan['kategori_report']) ?></span></td>
                                                    <td><?= htmlspecialchars(substr($laporan['keterangan_report'], 0, 50)) . (strlen($laporan['keterangan_report']) > 50 ? '...' : '') ?></td>
                                                    <td><?= (new DateTime($laporan['tgl_dibuat']))->format('d M Y H:i') ?></td>
                                                    <td>
                                                        <?php
                                                        $status_badge_class = '';
                                                        switch ($laporan['status_laporan']) {
                                                            case 'Belum Diproses': $status_badge_class = 'bg-warning text-dark'; break;
                                                            case 'Diproses': $status_badge_class = 'bg-info'; break;
                                                            case 'Selesai': $status_badge_class = 'bg-success'; break;
                                                            case 'Ditolak': $status_badge_class = 'bg-danger'; break;
                                                            default: $status_badge_class = 'bg-secondary'; break;
                                                        }
                                                        ?>
                                                        <span class="badge <?= $status_badge_class ?>"><?= htmlspecialchars($laporan['status_laporan']) ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="admin-detail-report.php?id=<?= $laporan['id_report'] ?>" class="btn btn-sm btn-primary">Detail</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="9" class="text-center text-muted p-3">Tidak ada laporan kata kasar.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-ban me-2"></i>Laporan Materi Tidak Relevan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>ID Laporan</th>
                                            <th>Username</th>
                                            <th>Nama Kelas</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Status</th> <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($mtrDataResult->num_rows > 0): ?>
                                            <?php $counter = 1; ?>
                                            <?php while ($laporan = $mtrDataResult->fetch_assoc()): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($laporan['id_report']) ?></td>
                                                    <td><?= htmlspecialchars($laporan['username']) ?></td>
                                                    <td><?= htmlspecialchars($laporan['nama_kelas']) ?></td>
                                                    <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($laporan['kategori_report']) ?></span></td>
                                                    <td><?= htmlspecialchars(substr($laporan['keterangan_report'], 0, 50)) . (strlen($laporan['keterangan_report']) > 50 ? '...' : '') ?></td>
                                                    <td><?= (new DateTime($laporan['tgl_dibuat']))->format('d M Y H:i') ?></td>
                                                    <td>
                                                        <?php
                                                        $status_badge_class = '';
                                                        switch ($laporan['status_laporan']) {
                                                            case 'Belum Diproses': $status_badge_class = 'bg-warning text-dark'; break;
                                                            case 'Diproses': $status_badge_class = 'bg-info'; break;
                                                            case 'Selesai': $status_badge_class = 'bg-success'; break;
                                                            case 'Ditolak': $status_badge_class = 'bg-danger'; break;
                                                            default: $status_badge_class = 'bg-secondary'; break;
                                                        }
                                                        ?>
                                                        <span class="badge <?= $status_badge_class ?>"><?= htmlspecialchars($laporan['status_laporan']) ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="admin-detail-report.php?id=<?= $laporan['id_report'] ?>" class="btn btn-sm btn-primary">Detail</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="9" class="text-center text-muted p-3">Tidak ada laporan materi tidak relevan.</td></tr>
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
if (isset($countTL) && $countTL) $countTL->close();
if (isset($countTLP) && $countTLP) $countTLP->close();
if (isset($countTLMTR) && $countTLMTR) $countTLMTR->close();
if (isset($countTLPKK) && $countTLPKK) $countTLPKK->close();
if (isset($totalLaporanP) && $totalLaporanP) $totalLaporanP->close();
if (isset($totalLaporanPkk) && $totalLaporanPkk) $totalLaporanPkk->close();
if (isset($totalLaporanMtr) && $totalLaporanMtr) $totalLaporanMtr->close();

if ($conn) $conn->close();
?>