<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginAdmin.php"); // Jika bukan admin, alihkan ke halaman login
    exit();
}

// FIX: Buat token CSRF (Cross-Site Request Forgery) jika belum ada di sesi ini.
// Ini penting untuk keamanan form dan mengatasi error rendering.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

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


// Data statistik (jika diperlukan di halaman ini, jika tidak bisa dihapus)
$totalUser = $conn->query("SELECT COUNT(*) as total_users FROM tb_user")->fetch_assoc();
$totalLaporan = $conn->query("SELECT COUNT(*) as total_laporan FROM tb_laporan")->fetch_assoc();
$totalMateri = $conn->query("SELECT COUNT(*) as total_materi FROM tb_materi")->fetch_assoc();
$totalPendapatanResult = $conn->query("
    SELECT COALESCE(SUM(k.harga), 0) AS total_pendapatan
    FROM tb_kelas k
    JOIN tb_keranjang kk ON kk.id_kelas = k.id_kelas
    JOIN tb_transaksi tk ON tk.id_keranjang = kk.id_keranjang
    WHERE tk.status = 'Completed'
");
$totalPendapatan = $totalPendapatanResult ? $totalPendapatanResult->fetch_assoc()['total_pendapatan'] : 0;


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
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #f8f9fa;
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
    </style>
</head>
<body>
    <?php include "adminSidebar.php"; // Pastikan path ini benar dan sidebar.php ada ?>
    
    <div class="content-wrapper">
        <div class="container-fluid">

            <?php
            // --- FITUR BARU: Tampilkan Pesan Feedback dari Sesi ---
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-' . htmlspecialchars($_SESSION['message_type']) . ' alert-dismissible fade show" role="alert">';
                echo htmlspecialchars($_SESSION['message']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
                // Hapus pesan setelah ditampilkan agar tidak muncul lagi
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>

            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-primary">
                        <i class="fas fa-comments me-2"></i>
                        Kelola Review & Komentar
                    </h2>
                    <p class="text-muted">Halaman untuk mengelola semua ulasan dari pengguna.</p>
                </div>
            </div>
            
            <div class="row mb-5 gy-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card stat-card primary shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total User</h6>
                                <h3 class="text-primary"><?= $totalUser['total_users'] ?? 0 ?></h3>
                            </div>
                            <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stat-card success shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Pendapatan</h6>
                                <h3 class="text-success">Rp <?= number_format($totalPendapatan ?? 0, 0, ',', '.') ?></h3>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stat-card info shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Materi</h6>
                                <h3 class="text-info"><?= $totalMateri['total_materi'] ?? 0 ?></h3>
                            </div>
                            <i class="fas fa-book-open fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stat-card warning shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Laporan</h6>
                                <h3 class="text-warning"><?= $totalLaporan['total_laporan'] ?? 0 ?></h3>
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
                                                            echo htmlspecialchars($review['tgl_review']); // Fallback
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <form action="admin-deleteReview.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus review ini secara permanen?');">
                                                            <input type="hidden" name="id_review" value="<?= htmlspecialchars($review['id_review']) ?>">
                                                            
                                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                                            
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash-alt"></i> Hapus
                                                            </button>
                                                        </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close statements dan connection
if (isset($review_query) && $review_query) $review_query->close();

// Tutup koneksi utama
if ($conn) $conn->close();
?>