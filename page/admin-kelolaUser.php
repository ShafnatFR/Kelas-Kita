<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

// Pastikan koneksi database berhasil
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
    
    // Deteksi apakah kueri adalah COUNT/SUM/MAX/MIN untuk mengambil satu baris saja
    if (strpos(strtoupper($sql), 'COUNT(') !== false || strpos(strtoupper($sql), 'SUM(') !== false ||
        strpos(strtoupper($sql), 'MAX(') !== false || strpos(strtoupper($sql), 'MIN(') !== false) {
        $data = $result->fetch_assoc();
    } else { // Untuk kueri SELECT biasa yang mengembalikan banyak baris
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    $stmt->close(); // Tutup statement setelah use
    return $data;
}

// Gabungkan kueri untuk jumlah pengguna (aktif, non-aktif, 10 terbaru secara keseluruhan)
$userCountsData = fetchData($conn, "
    SELECT
        COUNT(CASE WHEN status = 'aktif' THEN 1 END) AS total_users_aktif,
        COUNT(CASE WHEN status = 'non-aktif' THEN 1 END) AS total_users_nonaktif,
        (SELECT COUNT(*) FROM tb_user ORDER BY tgl_dibuat DESC LIMIT 10) AS total_user_terbaru_count
    FROM tb_user
");

// Kueri untuk total laporan
$laporanData = fetchData($conn, "SELECT COUNT(*) as total_laporan FROM tb_laporan");

// Kueri untuk mengambil 10 pengguna aktif terbaru
$tbUserAktifResult = fetchData($conn, "
    SELECT id_user, username, role, status, tgl_dibuat
    FROM tb_user
    WHERE status = 'aktif'
    ORDER BY tgl_dibuat DESC
    LIMIT 10
");

// Kueri untuk mengambil 10 pengguna non-aktif terbaru
$tbUserNonAktifResult = fetchData($conn, "
    SELECT id_user, username, role, status, tgl_dibuat
    FROM tb_user
    WHERE status = 'non-aktif'
    ORDER BY tgl_dibuat DESC
    LIMIT 10
");

// Kueri untuk pengguna yang dilaporkan
$tbUserDilaporkanResult = fetchData($conn, "
    SELECT u.id_user, u.username, u.role, u.status, l.keterangan_report, l.tgl_dibuat AS tgl_laporan
    FROM tb_user u
    JOIN tb_laporan l ON u.id_user = l.id_user
    ORDER BY l.tgl_dibuat DESC;
");

// Data untuk kartu statistik
$stats = [
    'total_user_aktif'          => $userCountsData['total_users_aktif'] ?? 0,
    'total_user_dinonaktifkan'  => $userCountsData['total_users_nonaktif'] ?? 0,
    'total_user_terbaru'        => $userCountsData['total_user_terbaru_count'] ?? 0,
    'total_laporan'             => $laporanData['total_laporan'] ?? 0
];

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin</title>
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
        /* Menggunakan variabel CSS Bootstrap untuk konsistensi warna */
        .stat-card.primary { border-left-color: var(--bs-primary); }
        .stat-card.success { border-left-color: var(--bs-success); }
        .stat-card.info { border-left-color: var(--bs-info); }
        .stat-card.warning { border-left-color: var(--bs-warning); }
        .stat-card.danger { border-left-color: var(--bs-danger); }
        
        /* CSS untuk badge status (jika ingin custom selain default Bootstrap) */
        .badge-status-aktif { background-color: var(--bs-success); color: #fff; }
        .badge-status-nonaktif { background-color: var(--bs-danger); color: #fff; }
        .badge-status-pending { background-color: var(--bs-info); color: #fff; }
        .badge-status-default { background-color: var(--bs-secondary); color: #fff; }
    </style>
</head>
<body class="bg-light">
    <?php include "adminSidebar.php"; ?>
    
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-primary">
                        <i class="fas fa-user-gear me-2"></i> Kelola Pengguna
                    </h2>
                    <p class="text-muted">Selamat datang, <?= htmlspecialchars($namaAdmin) ?>!</p>
                </div>
            </div>
            
            <div class="row mb-5 gy-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card success shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Pengguna Aktif</h6>
                                <h3 class="text-success"><?= $stats['total_user_aktif'] ?></h3>
                            </div>
                            <i class="fas fa-user-check fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card danger shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Pengguna Dinonaktifkan</h6>
                                <h3 class="text-danger"><?= $stats['total_user_dinonaktifkan'] ?></h3>
                            </div>
                            <i class="fas fa-user-slash fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card info shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Pengguna Terbaru</h6>
                                <h3 class="text-info"><?= $stats['total_user_terbaru'] ?></h3>
                            </div>
                            <i class="fas fa-user-plus fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card warning shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Laporan Pengguna</h6>
                                <h3 class="text-warning"><?= $stats['total_laporan'] ?></h3>
                            </div>
                            <i class="fas fa-exclamation-triangle fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 gy-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Pengguna Aktif Terbaru</h5>
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
                                            <th>Tgl Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($tbUserAktifResult)): ?>
                                            <?php $counter = 1; ?>
                                            <?php foreach ($tbUserAktifResult as $user): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                                                    <td>
                                                        <?php 
                                                            $status_class = ($user['status'] === 'aktif') ? 'badge-status-aktif' : 'badge-status-default';
                                                            echo '<span class="badge ' . $status_class . '">' . htmlspecialchars(ucfirst($user['status'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td><?= (new DateTime($user['tgl_dibuat']))->format('d M Y') ?></td>
                                                    <td>
                                                        <!-- <a href="send_message.php?id=<?= $user['id_user'] ?>" class="btn btn-sm btn-primary" title="Kirim Pesan">
                                                            <i class="fas fa-envelope"></i> Pesan
                                                        </a> -->
                                                        <a href="admin-deactivateUser.php?id=<?= $user['id_user'] ?>" class="btn btn-sm btn-danger" title="Non-Aktifkan Pengguna" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan pengguna ini?');">
                                                            <i class="fas fa-user-times"></i> Non-Aktifkan
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6" class="text-center text-muted p-3">Tidak ada data pengguna aktif terbaru.</td></tr>
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
                            <h5 class="mb-0"><i class="fas fa-user-slash me-2"></i>Pengguna Dinonaktifkan</h5>
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
                                        <?php if (!empty($tbUserNonAktifResult)): ?>
                                            <?php $counter = 1; ?>
                                            <?php foreach ($tbUserNonAktifResult as $user): ?>
                                                <tr>
                                                    <th><?= $counter++ ?></th>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                                                    <td>
                                                        <?php 
                                                            $status_class = ($user['status'] === 'non-aktif') ? 'badge-status-nonaktif' : 'badge-status-default';
                                                            echo '<span class="badge ' . $status_class . '">' . htmlspecialchars(ucfirst($user['status'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="admin-activateUser.php?id=<?= $user['id_user'] ?>" class="btn btn-sm btn-success" title="Aktifkan Pengguna">
                                                            <i class="fas fa-user-check"></i> Aktifkan
                                                        </a>
                                                        <!-- <a href="admin-deleteUser.php?id=<?= $user['id_user'] ?>" class="btn btn-sm btn-warning text-dark" title="Hapus Pengguna" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                                            <i class="fas fa-trash-alt"></i> Hapus
                                                        </a> -->
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center text-muted p-3">Tidak ada pengguna non-aktif.</td></tr>
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
// Tutup koneksi database utama
if ($conn) {
    $conn->close();
}
?>