<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginAdmin.php"); // Jika bukan admin, alihkan ke halaman login
    exit();
}

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Query untuk mengambil total user dengan error handling
$totalUser = $conn->prepare("SELECT COUNT(*) as total_users FROM tb_user");
if (!$totalUser) {
    die("Error preparing statement: " . $conn->error);
}
$totalUser->execute();
$userResult = $totalUser->get_result();
$userData = $userResult->fetch_assoc();

// Query untuk mengambil total laporan yang ada
$totalLaporan = $conn->prepare("SELECT COUNT(*) as total_laporan FROM tb_laporan");
if (!$totalLaporan) {
    die("Error preparing statement: " . $conn->error);
}
$totalLaporan->execute();
$laporanResult = $totalLaporan->get_result();
$laporanData = $laporanResult->fetch_assoc();

// Query untuk mengambil data user untuk tabel - sesuaikan dengan kolom yang ada
$tb_user = $conn->prepare("
    SELECT id_user, 
           CASE 
               WHEN first_name IS NOT NULL AND last_name IS NOT NULL 
               THEN CONCAT(first_name, ' ', last_name)
               ELSE username
           END AS fullname, 
           username
    FROM tb_user
    ORDER BY id_user ASC
");
if (!$tb_user) {
    die("Error preparing user statement: " . $conn->error);
}
$tb_user->execute();
$tb_userResult = $tb_user->get_result();
// $tb_userData = $tb_userResult->fetch_all(MYSQLI_ASSOC); // Komentari atau hapus jika tidak digunakan di bagian lain

// Query untuk mengambil 5 user terbaru
$recent_users_query = $conn->prepare("
    SELECT username, role, status, tgl_dibuat
    FROM tb_user
    ORDER BY tgl_dibuat DESC
    LIMIT 10
");
if (!$recent_users_query) {
    die("Error preparing recent users statement: " . $conn->error);
}
$recent_users_query->execute();
$recent_users_result = $recent_users_query->get_result();


//QUERY BUAT KELAS
$tb_kelas = $conn->prepare("
    SELECT id_kelas, nama_kelas FROM tb_kelas
    ORDER BY id_kelas ASC
");
if (!$tb_kelas) {
    die("Error preparing kelas statement: " . $conn->error);
}
$tb_kelas->execute();
$tb_kelasResult = $tb_kelas->get_result();
// $tb_kelasData = $tb_kelasResult->fetch_all(MYSQLI_ASSOC); // Komentari atau hapus jika tidak digunakan di bagian lain

// Query untuk mengambil total kelas
$totalKelas = $conn->prepare("SELECT COUNT(*) as total_kelas FROM tb_kelas WHERE status_publikasi = 'aktif'");
if (!$totalKelas) {
    die("Error preparing kelas statement: " . $conn->error);
}
$totalKelas->execute();
$kelasResult = $totalKelas->get_result();
$kelasData = $kelasResult->fetch_assoc();

// Query untuk mengambil total transaksi - disederhanakan jika tabel join bermasalah
$totalTransaksi = $conn->prepare("
    SELECT COALESCE(SUM(k.harga), 0) AS total_transaksi
    FROM tb_kelas k
    LEFT JOIN tb_keranjang kk ON kk.id_kelas = k.id_kelas
    LEFT JOIN tb_transaksi tk ON tk.id_keranjang = kk.id_keranjang
    WHERE tk.status = 'acc'
");
if (!$totalTransaksi) {
    // Jika query join gagal, gunakan query sederhana
    $totalTransaksi = $conn->prepare("SELECT 0 AS total_transaksi"); // Seharusnya ada fallback jika prepare gagal
    if (!$totalTransaksi) { // Tambahan error handling untuk fallback
        die("Error preparing fallback transaksi statement: " . $conn->error);
    }
}
$totalTransaksi->execute();
$transaksiResult = $totalTransaksi->get_result();
$transaksiData = $transaksiResult->fetch_assoc();

// Query untuk mengambil total materi
$totalMateri = $conn->prepare("SELECT COUNT(*) as total_materi FROM tb_materi");
if (!$totalMateri) {
    die("Error preparing materi statement: " . $conn->error);
}
$totalMateri->execute();
$materiResult = $totalMateri->get_result();
$materiData = $materiResult->fetch_assoc();

// Query untuk mengambil kelas terbaru
$recent_classes_query = $conn->prepare("
    SELECT nama_kelas, kategori, harga 
    FROM tb_kelas 
    WHERE status_publikasi = 'aktif' 
    ORDER BY id_kelas DESC 
    LIMIT 10
");
if (!$recent_classes_query) {
    die("Error preparing recent classes statement: " . $conn->error);
}
$recent_classes_query->execute();
$recent_classes_result = $recent_classes_query->get_result();

// ... (kode PHP Anda yang sudah ada di atas, setelah $recent_classes_result = $recent_classes_query->get_result();) ...

// Query untuk mengambil 5 kelas terbaru untuk ditampilkan di tabel
$latest_classes_table_query = $conn->prepare("
    SELECT nama_kelas, harga, status_publikasi, tgl_dibuat
    FROM tb_kelas
    ORDER BY tgl_dibuat DESC
    LIMIT 5
");
if (!$latest_classes_table_query) {
    die("Error preparing latest classes table statement: " . $conn->error);
}
$latest_classes_table_query->execute();
$latest_classes_table_result = $latest_classes_table_query->get_result();

// ... (sisa kode PHP Anda sebelum <!DOCTYPE html>) ...

// --- START: Penambahan untuk Diagram Pie Laporan ---
// Query untuk mendapatkan jumlah laporan per kategori
$sql_report_category = "SELECT kategori_report, COUNT(*) AS total FROM tb_laporan GROUP BY kategori_report";
$result_report_category = $conn->query($sql_report_category);

$report_category_data = [];
if ($result_report_category && $result_report_category->num_rows > 0) { // Tambahkan pengecekan $result_report_category
    while($row = $result_report_category->fetch_assoc()) {
        $report_category_data[] = [
            'kategori' => $row['kategori_report'],
            'total' => (int)$row['total']
        ];
    }
} else if (!$result_report_category) {
    // Handle error jika query gagal, misalnya log error atau tampilkan pesan
    // error_log("Error fetching report category data: " . $conn->error);
}
$json_report_category_data = json_encode($report_category_data);
// --- END: Penambahan untuk Diagram Pie Laporan ---

$stats = array(
    'total_users' => $userData['total_users'] ?? 0,
    'total_kelas' => $kelasData['total_kelas'] ?? 0,
    'total_materi' => $materiData['total_materi'] ?? 0,
    'total_transaksi' => $transaksiData['total_transaksi'] ?? 0,
    'total_laporan' => $laporanData['total_laporan'] ?? 0
);

// $transaksi array tampaknya tidak digunakan, bisa dikomentari atau dihapus jika memang tidak perlu.
// $transaksi = array(
//  'total_transaksi' => $transaksiData['total_transaksi'] ?? 0
// );

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Review & Komentar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column; /* Perlu untuk footer jika ada */
        }
        .content-wrapper {
            padding: 20px; /* Sesuaikan padding */
            flex: 1; /* Membuat content wrapper mengisi sisa ruang */
             margin-left: 250px; /* Sesuaikan dengan lebar sidebar */
        }
        /* Pastikan sidebar.php memiliki style position: fixed atau absolute dan lebar yang tetap */
        /* Contoh jika sidebar.php punya class .sidebar */
        /*
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #f8f9fa; // contoh warna
            padding-top: 20px;
        }
        */
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
        .chart-container {
            position: relative;
            height: 300px; 
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">
    <?php include "adminSidebar.php"; // Pastikan path ini benar dan sidebar.php ada ?>
    
    <div class="content-wrapper">
        <div class="container-fluid"> <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Kelola Review & Komentar
                    </h2>
                    <p class="text-muted">Selamat datang, <?= htmlspecialchars($namaAdmin) ?>!</p>
                </div>
            </div>
            
            <div class="row mb-5 gy-4"> <div class="col-xl-3 col-md-6"> <div class="card stat-card primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Kelas Aktif</h6>
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
                <!-- <div class="col-xl-3 col-md-6">
                    <div class="card stat-card success shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Total Transaksi</h6>
                                    <h3 class="text-success">Rp <?= number_format($stats['total_transaksi'] ?? 0, 0, ',', '.') ?></h3>
                                    <small class="text-muted">Jumlah pendapatan</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-money-bill-wave fa-2x text-success opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card info shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Total Kelas Pending</h6>
                                    <h3 class="text-info"><?= $stats['total_users'] ?? 0 ?></h3> <small class="text-muted">User keseluruhan</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x text-info opacity-50"></i>
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
                                    <h6 class="card-title text-muted">Total Kelas Dinonaktifkan</h6>
                                    <h3 class="text-info"><?= $stats['total_users'] ?? 0 ?></h3> <small class="text-muted">User keseluruhan</small>
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
                                    <h3 class="text-warning"><?= $stats['total_laporan'] ?? 0 ?></h3> <small class="text-muted">Laporan keseluruhan</small>
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
                

                <div class="col-lg-6"> <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2"></i>
                                User Terbaru
                            </h5>
                        </div>
                        <div class="card-body p-0"> <div class="table-responsive"> <table class="table table-hover table-striped mb-0"> <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Tgl Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($recent_users_result->num_rows > 0): ?>
                                            <?php $user_counter = 1; ?>
                                            <?php while ($user = $recent_users_result->fetch_assoc()): ?>
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
                                                            echo htmlspecialchars($user['tgl_dibuat']); // Fallback jika format salah
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
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

                <div class="row mb-5 gy-4">
                <div class="col-lg-12"> 
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-info text-white"> <h5 class="mb-0">
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
                                        <?php if ($latest_classes_table_result->num_rows > 0): ?>
                                            <?php $class_counter = 1; ?>
                                            <?php while ($kelas_item = $latest_classes_table_result->fetch_assoc()): ?>
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
                                                            $status_badge_class .= 'bg-warning text-dark'; // text-dark agar terbaca di bg kuning
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
                                            <?php endwhile; ?>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- START: JavaScript untuk Diagram Pie Laporan ---
        const reportCategoryData = <?php echo $json_report_category_data ?? '[]'; ?>; // Fallback ke array kosong

        if (reportCategoryData.length > 0) {
            const labels = reportCategoryData.map(item => item.kategori);
            const totals = reportCategoryData.map(item => item.total);

            const backgroundColors = [
                'rgba(255, 99, 132, 0.7)',  // Merah
                'rgba(54, 162, 235, 0.7)', // Biru
                'rgba(255, 206, 86, 0.7)', // Kuning
                'rgba(75, 192, 192, 0.7)', // Hijau Teal
                'rgba(153, 102, 255, 0.7)',// Ungu
                'rgba(255, 159, 64, 0.7)'  // Oranye
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
                new Chart(ctx, { // Dihilangkan variabel pieChart karena tidak digunakan lagi
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
        // --- END: JavaScript untuk Diagram Pie Laporan ---
    </script>
</body>
</html>

<?php
// Close statements dan connection
if (isset($totalUser) && $totalUser) $totalUser->close();
if (isset($totalLaporan) && $totalLaporan) $totalLaporan->close();
if (isset($tb_user) && $tb_user) $tb_user->close();
if (isset($recent_users_query) && $recent_users_query) $recent_users_query->close();
if (isset($tb_kelas) && $tb_kelas) $tb_kelas->close();
if (isset($totalKelas) && $totalKelas) $totalKelas->close();
if (isset($totalTransaksi) && $totalTransaksi) $totalTransaksi->close();
if (isset($totalMateri) && $totalMateri) $totalMateri->close();
if (isset($recent_classes_query) && $recent_classes_query) $recent_classes_query->close();
if (isset($latest_classes_table_query) && $latest_classes_table_query) $latest_classes_table_query->close();

if (isset($result_report_category) && is_object($result_report_category) && method_exists($result_report_category, 'close')) {
    $result_report_category->close();
}
if ($conn) $conn->close();
?>