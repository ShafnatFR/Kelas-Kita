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
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result === false) {
        error_log("Error getting result: " . $stmt->error . " for query: " . $sql);
        $stmt->close();
        return false;
    }
    
    if (strpos(strtoupper($sql), 'COUNT(*)') !== false || strpos(strtoupper($sql), 'SUM(') !== false) {
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

$userData = fetchData($conn, "SELECT COUNT(*) as total_users FROM tb_user");

$laporanData = fetchData($conn, "SELECT COUNT(*) as total_laporan FROM tb_laporan");

$kelasData = fetchData($conn, "SELECT COUNT(*) as total_kelas FROM tb_kelas WHERE status_publikasi = 'approved'");

$transaksiData = fetchData($conn, "
    SELECT COALESCE(SUM(k.harga), 0) AS total_transaksi
    FROM tb_kelas k
    INNER JOIN tb_keranjang kk ON kk.id_kelas = k.id_kelas
    INNER JOIN tb_transaksi tk ON tk.id_keranjang = kk.id_keranjang
    WHERE tk.status = 'Completed'
");

if ($transaksiData === false) {
    $transaksiData = ['total_transaksi' => 0];
}


$materiData = fetchData($conn, "SELECT COUNT(*) as total_materi FROM tb_materi");

$recent_users = fetchData($conn, "
    SELECT username, role, status, tgl_dibuat
    FROM tb_user
    ORDER BY tgl_dibuat DESC
    LIMIT 10
");

$latest_classes_table = fetchData($conn, "
    SELECT nama_kelas, harga, status_publikasi, tgl_dibuat
    FROM tb_kelas
    ORDER BY tgl_dibuat DESC
    LIMIT 5
");

$sql_report_category = "SELECT kategori_report, COUNT(*) AS total FROM tb_laporan GROUP BY kategori_report";
$result_report_category = $conn->query($sql_report_category);

$report_category_data = [];
if ($result_report_category && $result_report_category->num_rows > 0) {
    while($row = $result_report_category->fetch_assoc()) {
        $report_category_data[] = [
            'kategori' => $row['kategori_report'],
            'total' => (int)$row['total']
        ];
    }
} else if (!$result_report_category) {
}
$json_report_category_data = json_encode($report_category_data);

$stats = [
    'total_users' => $userData['total_users'] ?? 0,
    'total_kelas' => $kelasData['total_kelas'] ?? 0,
    'total_materi' => $materiData['total_materi'] ?? 0,
    'total_transaksi' => $transaksiData['total_transaksi'] ?? 0,
    'total_laporan' => $laporanData['total_laporan'] ?? 0
];

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
        .stat-card.primary { border-left-color: #007bff; }
        .stat-card.success { border-left-color: #28a745; }
        .stat-card.info { border-left-color: #17a2b8; }
        .stat-card.warning { border-left-color: #ffc107; }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">
    <?php include "adminSidebar.php"; // Ensure this path is correct ?>
    
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard Admin
                    </h2>
                    <p class="text-muted">Selamat datang, <?= htmlspecialchars($namaAdmin) ?>!</p>
                    <!-- Pengecekan penggunaan simbolik NPSP contoh, agar yang ditampilkan sesuai yang terdetect db itu, untuk menghindari SQL Injection, agar terbaca hanya karaker -->
                </div>
            </div>
            
            <div class="row mb-5 gy-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card primary shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Total Kelas</h6>
                                    <h3 class="text-primary"><?= $stats['total_kelas'] ?></h3>
                                    <small class="text-muted">Kelas aktif</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-book fa-2x text-primary opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card success shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Total Transaksi</h6>
                                    <h3 class="text-success">Rp <?= number_format($stats['total_transaksi'], 0, ',', '.') ?></h3>
                                    <small class="text-muted">Jumlah pendapatan</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-money-bill-wave fa-2x text-success opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card info shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Total User</h6>
                                    <h3 class="text-info"><?= $stats['total_users'] ?></h3>
                                    <small class="text-muted">User keseluruhan</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x text-info opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card warning shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Total Laporan</h6>
                                    <h3 class="text-warning"><?= $stats['total_laporan'] ?></h3>
                                    <small class="text-muted">Laporan keseluruhan</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-file-alt fa-2x text-warning opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 gy-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2"></i>
                                User Terbaru
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Tgl Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($recent_users)): ?>
                                            <?php $user_counter = 1; ?>
                                            <?php foreach ($recent_users as $user): ?>
                                                <tr>
                                                    <th scope="row"><?= $user_counter++ ?></th>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                                                    <td>
                                                        <?php 
                                                        $status_class = $user['status'] === 'aktif' ? 'badge bg-success' : 'badge bg-danger';
                                                        echo '<span class="' . $status_class . '">' . htmlspecialchars(ucfirst($user['status'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        try {
                                                            $date = new DateTime($user['tgl_dibuat']);
                                                            echo htmlspecialchars($date->format('d M Y, H:i')); 
                                                        } catch (Exception $e) {
                                                            echo htmlspecialchars($user['tgl_dibuat']); // Fallback if format is wrong
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted p-3">Tidak ada data user terbaru.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Laporan Berdasarkan Kategori
                            </h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <?php if (!empty($report_category_data)): ?>
                                <div class="chart-container">
                                    <canvas id="pieChart"></canvas>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center mb-0">Tidak ada data laporan untuk ditampilkan.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 gy-4">
                <div class="col-lg-12"> 
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Daftar Kelas Terbaru
                            </h5>
                        </div>
                        <div class="card-body p-0"> 
                            <div class="table-responsive"> 
                                <table class="table table-hover table-striped mb-0"> 
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Kelas</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Status Publikasi</th>
                                            <th scope="col">Tgl Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($latest_classes_table)): ?>
                                            <?php $class_counter = 1; ?>
                                            <?php foreach ($latest_classes_table as $kelas_item): ?>
                                                <tr>
                                                    <th scope="row"><?= $class_counter++ ?></th>
                                                    <td><?= htmlspecialchars($kelas_item['nama_kelas']) ?></td>
                                                    <td>Rp <?= htmlspecialchars(number_format($kelas_item['harga'], 0, ',', '.')) ?></td>
                                                    <td>
                                                        <?php 
                                                        $status_publikasi = $kelas_item['status_publikasi'];
                                                        $status_badge_class = 'badge ';
                                                        if ($status_publikasi === 'aktif') {
                                                            $status_badge_class .= 'bg-success';
                                                        } elseif ($status_publikasi === 'pending') {
                                                            $status_badge_class .= 'bg-warning text-dark';
                                                        } elseif ($status_publikasi === 'non-aktif') {
                                                            $status_badge_class .= 'bg-danger';
                                                        } else {
                                                            $status_badge_class .= 'bg-secondary';
                                                        }
                                                        echo '<span class="' . $status_badge_class . '">' . htmlspecialchars(ucfirst($status_publikasi)) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        try {
                                                            $date = new DateTime($kelas_item['tgl_dibuat']);
                                                            echo htmlspecialchars($date->format('d M Y, H:i')); 
                                                        } catch (Exception $e) {
                                                            echo htmlspecialchars($kelas_item['tgl_dibuat']); // Fallback
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted p-3">Tidak ada data kelas terbaru untuk ditampilkan.</td>
                                            </tr>
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
    <script>
        const reportCategoryData = <?php echo $json_report_category_data; ?>; // No need for ?? '[]' after PHP check

        if (reportCategoryData.length > 0) {
            const labels = reportCategoryData.map(item => item.kategori);
            const totals = reportCategoryData.map(item => item.total);

            const backgroundColors = [
                'rgba(255, 99, 132, 0.7)',   // Red
                'rgba(54, 162, 235, 0.7)',  // Blue
                'rgba(255, 206, 86, 0.7)',  // Yellow
                'rgba(75, 192, 192, 0.7)',  // Teal Green
                'rgba(153, 102, 255, 0.7)', // Purple
                'rgba(255, 159, 64, 0.7)'   // Orange
            ];
            const borderColors = [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ];

            const ctx = document.getElementById('pieChart');
            if (ctx) { 
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Laporan',
                            data: totals,
                            backgroundColor: backgroundColors.slice(0, labels.length), 
                            borderColor: borderColors.slice(0, labels.length),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, 
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += context.raw + ' laporan';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    </script>
</body>
</html>

<?php
if ($conn) {
    $conn->close();
}
?>