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

// --- QUERY UNTUK STATS KELAS ---
// Query untuk total kelas dengan berbagai status (Disesuaikan dengan ENUM: 'aktif', 'pending', 'non-aktif')
$kelas_stats_query = $conn->prepare("
    SELECT
        COUNT(CASE WHEN status_publikasi = 'aktif' THEN 1 END) AS total_aktif,
        COUNT(CASE WHEN status_publikasi = 'pending' THEN 1 END) AS total_pending,
        COUNT(CASE WHEN status_publikasi = 'non-aktif' THEN 1 END) AS total_nonaktif
    FROM tb_kelas
");
if (!$kelas_stats_query) {
    die("Error preparing kelas_stats_query: " . $conn->error);
}
$kelas_stats_query->execute();
$kelas_stats_result = $kelas_stats_query->get_result();
$kelas_stats_data = $kelas_stats_result->fetch_assoc();


// Query untuk mengambil total user dengan error handling
$totalUser = $conn->prepare("SELECT COUNT(*) as total_users FROM tb_user");
if (!$totalUser) {
    die("Error preparing totalUser statement: " . $conn->error);
}
$totalUser->execute();
$userResult = $totalUser->get_result();
$userData = $userResult->fetch_assoc();

// Query untuk mengambil total laporan yang ada
$totalLaporan = $conn->prepare("SELECT COUNT(*) as total_laporan FROM tb_laporan");
if (!$totalLaporan) {
    die("Error preparing totalLaporan statement: " . $conn->error);
}
$totalLaporan->execute();
$laporanResult = $totalLaporan->get_result();
$laporanData = $laporanResult->fetch_assoc();

// Query untuk mengambil data user untuk tabel - sesuaikan dengan kolom yang ada
// Variabel diubah menjadi tb_user_query untuk konsistensi
$tb_user_query = $conn->prepare("
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
if (!$tb_user_query) {
    die("Error preparing tb_user_query: " . $conn->error);
}
$tb_user_query->execute();
$tb_user_result = $tb_user_query->get_result();

// Query untuk mengambil 10 user terbaru
$recent_users_query = $conn->prepare("
    SELECT username, role, status, tgl_dibuat
    FROM tb_user
    ORDER BY tgl_dibuat DESC
    LIMIT 10
");
if (!$recent_users_query) {
    die("Error preparing recent_users_query: " . $conn->error);
}
$recent_users_query->execute();
$recent_users_result = $recent_users_query->get_result();


//QUERY BUAT KELAS (Variabel diubah menjadi tb_kelas_query untuk konsistensi)
$tb_kelas_query = $conn->prepare("
    SELECT id_kelas, nama_kelas FROM tb_kelas
    ORDER BY id_kelas ASC
");
if (!$tb_kelas_query) {
    die("Error preparing tb_kelas_query: " . $conn->error);
}
$tb_kelas_query->execute();
$tb_kelas_result = $tb_kelas_query->get_result();

// Query untuk mengambil total kelas aktif (Variabel diubah menjadi totalKelasAktif untuk kejelasan)
$totalKelasAktif = $conn->prepare("SELECT COUNT(*) as total_kelas FROM tb_kelas WHERE status_publikasi = 'approved'");
if (!$totalKelasAktif) {
    die("Error preparing totalKelasAktif statement: " . $conn->error);
}
$totalKelasAktif->execute();
$kelasAktifResult = $totalKelasAktif->get_result();
$kelasAktifData = $kelasAktifResult->fetch_assoc();

// Query untuk mengambil total transaksi - disederhanakan jika tabel join bermasalah
$totalTransaksi = $conn->prepare("
    SELECT COALESCE(SUM(k.harga), 0) AS total_transaksi
    FROM tb_kelas k
    LEFT JOIN tb_keranjang kk ON kk.id_kelas = k.id_kelas
    LEFT JOIN tb_transaksi tk ON tk.id_keranjang = kk.id_keranjang
    WHERE tk.status = 'Completed'
");
if (!$totalTransaksi) {
    error_log("Error preparing totalTransaksi with JOIN: " . $conn->error . ". Falling back to 0.");
    $totalTransaksi = $conn->prepare("SELECT 0 AS total_transaksi");
    if (!$totalTransaksi) {
        die("Error preparing fallback transaksi statement: " . $conn->error);
    }
}
$totalTransaksi->execute();
$transaksiResult = $totalTransaksi->get_result();
$transaksiData = $transaksiResult->fetch_assoc();

// Query untuk mengambil total materi
$totalMateri = $conn->prepare("SELECT COUNT(*) as total_materi FROM tb_materi");
if (!$totalMateri) {
    die("Error preparing totalMateri statement: " . $conn->error);
}
$totalMateri->execute();
$materiResult = $totalMateri->get_result();
$materiData = $materiResult->fetch_assoc();

// Query untuk mengambil kelas terbaru (digunakan di card?)
$recent_classes_query = $conn->prepare("
    SELECT nama_kelas, kategori, harga 
    FROM tb_kelas 
    WHERE status_publikasi = 'approved' 
    ORDER BY id_kelas DESC 
    LIMIT 10
");
if (!$recent_classes_query) {
    die("Error preparing recent_classes_query: " . $conn->error);
}
$recent_classes_query->execute();
$recent_classes_result = $recent_classes_query->get_result();

// Query untuk mengambil 5 kelas terbaru untuk ditampilkan di tabel
$latest_classes_table_query = $conn->prepare("
    SELECT nama_kelas, harga, status_publikasi, tgl_dibuat
    FROM tb_kelas
    ORDER BY tgl_dibuat DESC
    LIMIT 5
");
if (!$latest_classes_table_query) {
    die("Error preparing latest_classes_table_query: " . $conn->error);
}
$latest_classes_table_query->execute();
$latest_classes_table_result = $latest_classes_table_query->get_result();

// --- START: Query untuk Review & Komentar ---
$review_query = $conn->prepare("
    SELECT 
        r.id_review,
        u.username, 
        k.nama_kelas, 
        r.bintang_review, 
        r.isi_review, 
        r.tgl_review
    FROM 
        tb_review r
    JOIN 
        tb_user u ON u.id_user = r.id_user
    JOIN 
        tb_kelas k ON k.id_kelas = r.id_kelas
    ORDER BY 
        r.tgl_review DESC
");
if (!$review_query) {
    die("Error preparing review_query: " . $conn->error);
}
$review_query->execute();
$review_result = $review_query->get_result();
// --- END: Query untuk Review & Komentar ---


// --- START: Penambahan untuk Diagram Pie Laporan ---
// Query untuk mendapatkan jumlah laporan per kategori
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
    error_log("Error fetching report category data: " . $conn->error);
}
$json_report_category_data = json_encode($report_category_data);
// --- END: Penambahan untuk Diagram Pie Laporan ---

// Data untuk statistik cards
$stats = array(
    'total_users' => $userData['total_users'] ?? 0,
    'total_kelas_aktif' => $kelasAktifData['total_kelas'] ?? 0, // Menggunakan data dari totalKelasAktif
    'total_kelas_pending' => $kelas_stats_data['total_pending'] ?? 0, // Menggunakan data dari kelas_stats_query
    'total_kelas_nonaktif' => $kelas_stats_data['total_nonaktif'] ?? 0, // Menggunakan data dari kelas_stats_query
    'total_laporan' => $laporanData['total_laporan'] ?? 0,
    'total_transaksi' => $transaksiData['total_transaksi'] ?? 0,
    'total_materi' => $materiData['total_materi'] ?? 0
);

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        /* Menggunakan warna Bootstrap 5 */
        .stat-card.primary { border-left-color: #0d6efd; }
        .stat-card.success { border-left-color: #198754; }
        .stat-card.info { border-left-color: #0dcaf0; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.danger { border-left-color: #dc3545; }
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
                    <div class="card stat-card primary shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Kelas Aktif</h6>
                                <h3 class="text-primary"><?= $stats['total_kelas_aktif'] ?? 0 ?></h3>
                            </div>
                            <i class="fas fa-book-open fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card success shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Transaksi</h6>
                                <h3 class="text-success">Rp <?= number_format($stats['total_transaksi'] ?? 0, 0, ',', '.') ?></h3>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card info shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Kelas Pending</h6>
                                <h3 class="text-info"><?= $stats['total_kelas_pending'] ?? 0 ?></h3>
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
                                <h3 class="text-danger"><?= $stats['total_kelas_nonaktif'] ?? 0 ?></h3>
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
                                <h3 class="text-warning"><?= $stats['total_laporan'] ?? 0 ?></h3>
                            </div>
                            <i class="fas fa-file-alt fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card primary shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total User</h6>
                                <h3 class="text-primary"><?= $stats['total_users'] ?? 0 ?></h3>
                            </div>
                            <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card success shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Materi</h6>
                                <h3 class="text-success"><?= $stats['total_materi'] ?? 0 ?></h3>
                            </div>
                            <i class="fas fa-file-powerpoint fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 gy-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-star me-2"></i>
                                Tabel Review & Komentar
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Nama Kelas</th>
                                            <th scope="col">Bintang</th>
                                            <th scope="col">Isi Review</th>
                                            <th scope="col">Tanggal Review</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($review_result->num_rows > 0): ?>
                                            <?php $review_counter = 1; ?>
                                            <?php while ($review = $review_result->fetch_assoc()): ?>
                                                <tr>
                                                    <th scope="row"><?= $review_counter++ ?></th>
                                                    <td><?= htmlspecialchars($review['username']) ?></td>
                                                    <td><?= htmlspecialchars($review['nama_kelas']) ?></td>
                                                    <td><?= htmlspecialchars($review['bintang_review']) ?> <i class="fas fa-star text-warning"></i></td>
                                                    <td><?= htmlspecialchars($review['isi_review']) ?></td>
                                                    <td>
                                                        <?php 
                                                        try {
                                                            $date = new DateTime($review['tgl_review']);
                                                            echo htmlspecialchars($date->format('d M Y, H:i')); 
                                                        } catch (Exception $e) {
                                                            echo htmlspecialchars($review['tgl_review']); // Fallback jika format salah
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="admin-deleteReview.php?id=<?= htmlspecialchars($review['id_review']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus review ini?');">Hapus</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted p-3">Tidak ada data review.</td>
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
</body>
</html>

<?php
// Close statements dan connection
if (isset($kelas_stats_query) && $kelas_stats_query) $kelas_stats_query->close();
if (isset($totalUser) && $totalUser) $totalUser->close();
if (isset($totalLaporan) && $totalLaporan) $totalLaporan->close();
if (isset($tb_user_query) && $tb_user_query) $tb_user_query->close();
if (isset($recent_users_query) && $recent_users_query) $recent_users_query->close();
if (isset($tb_kelas_query) && $tb_kelas_query) $tb_kelas_query->close();
if (isset($totalKelasAktif) && $totalKelasAktif) $totalKelasAktif->close();
if (isset($totalTransaksi) && $totalTransaksi) $totalTransaksi->close();
if (isset($totalMateri) && $totalMateri) $totalMateri->close();
if (isset($recent_classes_query) && $recent_classes_query) $recent_classes_query->close();
if (isset($latest_classes_table_query) && $latest_classes_table_query) $latest_classes_table_query->close();
if (isset($review_query) && $review_query) $review_query->close(); // Menambahkan penutupan untuk query review

if (isset($result_report_category) && is_object($result_report_category) && method_exists($result_report_category, 'close')) {
    $result_report_category->close();
}
if ($conn) $conn->close();
?>