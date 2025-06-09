<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

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

    if (strpos(strtoupper($sql), 'COUNT(') !== false || strpos(strtoupper($sql), 'SUM(') !== false ||
        strpos(strtoupper($sql), 'MAX(') !== false || strpos(strtoupper($sql), 'MIN(') !== false) {
        $data = $result->fetch_assoc();
    } else {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    $stmt->close();
    return $data;
}

// Query untuk statistik kelas (tetap ada karena mungkin ini adalah dashboard yang lebih luas)
$kelas_stats_data = fetchData($conn, "
    SELECT
        COUNT(CASE WHEN status_publikasi = 'aktif' THEN 1 END) AS total_aktif,
        COUNT(CASE WHEN status_publikasi = 'pending' THEN 1 END) AS total_pending,
        COUNT(CASE WHEN status_publikasi IN ('non-aktif', 'rejected', 'draft') THEN 1 END) AS total_nonaktif
    FROM tb_kelas
");

// Query untuk materi pending
$totalMateriPendingResult = fetchData($conn, "
    SELECT
        tk.id_kelas,
        tk.nama_kelas,
        tk.status_publikasi AS status_kelas_publikasi,
        tm.id_materi,
        tm.judul_materi,
        tm.tgl_dibuat_materi,
        tm.status AS status_materi
    FROM tb_kelas tk
    JOIN tb_materi tm ON tk.id_kelas = tm.id_kelas
    WHERE tm.status = 'pending'
    ORDER BY tm.tgl_dibuat_materi DESC
    LIMIT 10
");

// --- START: Query Baru untuk Materi Ditolak/Non-Aktif ---
// Mengambil materi yang berstatus 'non-aktif'. Ini akan mencakup materi yang ditolak atau dinonaktifkan secara manual.
$totalMateriRejectedNonAktifResult = fetchData($conn, "
    SELECT
        tk.id_kelas,
        tk.nama_kelas,
        tk.status_publikasi AS status_kelas_publikasi,
        tm.id_materi,
        tm.judul_materi,
        tm.tgl_dibuat_materi,
        tm.status AS status_materi
    FROM tb_kelas tk
    JOIN tb_materi tm ON tk.id_kelas = tm.id_kelas
    WHERE tm.status = 'non-aktif'
    ORDER BY tm.tgl_dibuat_materi DESC
    LIMIT 10
");
// --- END: Query Baru untuk Materi Ditolak/Non-Aktif ---

// Query untuk materi aktif
$totalMateriAktifResult = fetchData($conn, "
    SELECT
        tk.id_kelas,
        tk.nama_kelas,
        tk.status_publikasi AS status_kelas_publikasi,
        tm.id_materi,
        tm.judul_materi,
        tm.tgl_dibuat_materi,
        tm.status AS status_materi
    FROM tb_kelas tk
    JOIN tb_materi tm ON tk.id_kelas = tm.id_kelas
    WHERE tm.status = 'aktif'
    ORDER BY tm.tgl_dibuat_materi DESC
    LIMIT 10
");

// Note: totalMateriNonaktifResult tidak lagi digunakan secara terpisah
// karena 'non-aktif' akan dicakup oleh totalMateriRejectedNonAktifResult.
// Jika Anda ingin membedakan antara 'rejected' dan 'dinonaktifkan secara manual',
// Anda perlu menambahkan kolom status baru di tb_materi (misalnya `rejection_reason`) atau mengubah enum status.

$userData = fetchData($conn, "SELECT COUNT(*) as total_users FROM tb_user");
$laporanData = fetchData($conn, "SELECT COUNT(*) as total_laporan FROM tb_laporan");


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
    <title>Kelola Materi - Admin</title>
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
            margin-left: 250px;
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-card.primary { border-left-color: var(--bs-primary); }
        .stat-card.success { border-left-color: var(--bs-success); }
        .stat-card.info { border-left-color: var(--bs-info); }
        .stat-card.warning { border-left-color: var(--bs-warning); }
        .stat-card.danger { border-left-color: var(--bs-danger); }

        /* Custom badge styles for materi statuses */
        .badge-status-pending { background-color: var(--bs-info); color: #fff; }
        .badge-status-aktif { background-color: var(--bs-success); color: #fff; }
        .badge-status-nonaktif { background-color: var(--bs-danger); color: #fff; }
        .badge-status-rejected { background-color: var(--bs-warning); color: #fff; } /* Added for rejected/non-aktif */
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
                        <i class="fas fa-book-reader me-2"></i>
                        Kelola Materi
                    </h2>
                    <p class="text-muted">Selamat datang, <?= htmlspecialchars($namaAdmin) ?>!</p>
                </div>
            </div>

            <?php
            // Tampilkan pesan notifikasi jika ada
            if (isset($_SESSION['message'])) {
                $message_type = $_SESSION['message_type'] ?? 'info'; // Default to info
                echo '<div class="alert alert-' . $message_type . ' alert-dismissible fade show" role="alert">';
                echo htmlspecialchars($_SESSION['message']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
                unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
                unset($_SESSION['message_type']); // Hapus tipe pesan juga
            }
            ?>

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
                <div class="col-lg-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Tabel Materi Pending</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Kelas</th>
                                            <th>Judul Materi</th>
                                            <th>Tgl Dibuat</th>
                                            <th>Status Materi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($totalMateriPendingResult)): ?>
                                            <?php $counter = 1; ?>
                                            <?php foreach ($totalMateriPendingResult as $materi): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($materi['nama_kelas']) ?></td>
                                                    <td><?= htmlspecialchars($materi['judul_materi']) ?></td>
                                                    <td><?= (new DateTime($materi['tgl_dibuat_materi']))->format('d M Y') ?></td>
                                                    <td>
                                                        <?php
                                                        $status_materi_badge_class = ($materi['status_materi'] === 'pending') ? 'badge-status-pending' : 'badge-status-default';
                                                        echo '<span class="badge ' . $status_materi_badge_class . '">' . htmlspecialchars(ucfirst($materi['status_materi'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="admin-approveMateri.php?id=<?= $materi['id_materi'] ?>" class="btn btn-sm btn-success" title="Approve Materi">
                                                            <i class="fas fa-check"></i> Approve
                                                        </a>
                                                        <a href="admin-rejectMateri.php?id=<?= $materi['id_materi'] ?>" class="btn btn-sm btn-danger" title="Reject Materi" onclick="return confirm('Apakah Anda yakin ingin menolak materi ini?');">
                                                            <i class="fas fa-times"></i> Reject
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6" class="text-center text-muted p-3">Tidak ada data materi pending.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- --- START: Tambahan Tabel Materi Ditolak/Non-Aktif Terbaru --- -->
                <div class="col-lg-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Tabel Materi Ditolak/Non-Aktif</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Kelas</th>
                                            <th>Judul Materi</th>
                                            <th>Tgl Dibuat</th>
                                            <th>Status Materi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($totalMateriRejectedNonAktifResult)): ?>
                                            <?php $counter = 1; ?>
                                            <?php foreach ($totalMateriRejectedNonAktifResult as $materi): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($materi['nama_kelas']) ?></td>
                                                    <td><?= htmlspecialchars($materi['judul_materi']) ?></td>
                                                    <td><?= (new DateTime($materi['tgl_dibuat_materi']))->format('d M Y') ?></td>
                                                    <td>
                                                        <?php
                                                        $status_materi_badge_class = ($materi['status_materi'] === 'non-aktif') ? 'badge-status-nonaktif' : 'badge-status-default';
                                                        echo '<span class="badge ' . $status_materi_badge_class . '">' . htmlspecialchars(ucfirst($materi['status_materi'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="admin-activateMateri.php?id=<?= $materi['id_materi'] ?>" class="btn btn-sm btn-success" title="Aktifkan Materi (Approve)">
                                                            <i class="fas fa-check-circle"></i> Aktifkan
                                                        </a>
                                                        <a href="admin-deleteMateri.php?id=<?= $materi['id_materi'] ?>" class="btn btn-sm btn-danger" title="Hapus Materi" onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">
                                                            <i class="fas fa-trash-alt"></i> Hapus
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6" class="text-center text-muted p-3">Tidak ada data materi ditolak atau non-aktif.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- --- END: Tambahan Tabel Materi Ditolak/Non-Aktif Terbaru --- -->

                <div class="col-lg-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Tabel Materi Aktif</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Kelas</th>
                                            <th>Judul Materi</th>
                                            <th>Tgl Dibuat</th>
                                            <th>Status Materi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($totalMateriAktifResult)): ?>
                                            <?php $counter = 1; ?>
                                            <?php foreach ($totalMateriAktifResult as $materi): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($materi['nama_kelas']) ?></td>
                                                    <td><?= htmlspecialchars($materi['judul_materi']) ?></td>
                                                    <td><?= (new DateTime($materi['tgl_dibuat_materi']))->format('d M Y') ?></td>
                                                    <td>
                                                        <?php
                                                        $status_materi_badge_class = ($materi['status_materi'] === 'aktif') ? 'badge-status-aktif' : 'badge-status-default';
                                                        echo '<span class="badge ' . $status_materi_badge_class . '">' . htmlspecialchars(ucfirst($materi['status_materi'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="admin-deactivateMateri.php?id=<?= $materi['id_materi'] ?>" class="btn btn-sm btn-danger" title="Non-Aktifkan Materi" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan materi ini?');">
                                                            <i class="fas fa-times-circle"></i> Non-Aktifkan
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6" class="text-center text-muted p-3">Tidak ada data materi aktif.</td></tr>
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
if ($conn) {
    $conn->close();
}
?>
