<?php
session_start();
require 'db.php';

// --- START: Validasi dan Inisialisasi ---
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'mentor' || !isset($_SESSION['id'])) {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$id_mentor = 0;

// --- START: Penanganan Aksi (Switch Role) ---
// Logika ini ditempatkan di atas sebelum output HTML lainnya
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ubah role kembali menjadi peserta (murid)
    $stmt = $conn->prepare("UPDATE tb_user SET role = 'murid' WHERE id_user = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Update session role
    $_SESSION['role'] = 'murid';

    // Redirect kembali ke halaman utama dan hentikan eksekusi script
    header("Location: index.php");
    exit();
}


// Ambil id_mentor dari id_user yang login
try {
    $mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
    if (!$mentor_query) throw new Exception("Prepare failed: " . $conn->error);
    
    $mentor_query->bind_param("i", $user_id);
    $mentor_query->execute();
    $mentor_result = $mentor_query->get_result();

    if ($mentor_row = $mentor_result->fetch_assoc()) {
        $id_mentor = $mentor_row['id_mentor'];
    } else {
        // Jika belum menjadi mentor, arahkan ke halaman pendaftaran mentor
        header("Location: become-mentor.php");
        exit();
    }
    $mentor_query->close();
} catch (Exception $e) {
    die("Error Kritis: " . $e->getMessage());
}

// --- START: Mengambil Statistik ---
$stats = [
    'total_kelas' => 0,
    'total_penjualan' => 0,
    'total_materi' => 0,
    'total_murid' => 0,
];

try {
    // Total kelas dan total potensi pendapatan (berdasarkan harga kelas)
    $stmt_kelas = $conn->prepare("SELECT COUNT(*) AS total_kelas FROM tb_kelas WHERE id_mentor = ?");
    $stmt_kelas->bind_param("i", $id_mentor);
    $stmt_kelas->execute();
    $stats['total_kelas'] = $stmt_kelas->get_result()->fetch_assoc()['total_kelas'];
    $stmt_kelas->close();

    // Total penjualan (transaksi completed)
    $stmt_penjualan = $conn->prepare("SELECT COUNT(t.id_transaksi) AS total_penjualan FROM tb_transaksi t JOIN tb_kelas k ON t.id_kelas = k.id_kelas WHERE k.id_mentor = ? AND t.status = 'Completed'");
    $stmt_penjualan->bind_param("i", $id_mentor);
    $stmt_penjualan->execute();
    $stats['total_penjualan'] = $stmt_penjualan->get_result()->fetch_assoc()['total_penjualan'];
    $stmt_penjualan->close();

    // Total materi dari semua kelas mentor
    $stmt_materi = $conn->prepare("SELECT COUNT(m.id_materi) as total_materi FROM tb_materi m JOIN tb_kelas k ON m.id_kelas = k.id_kelas WHERE k.id_mentor = ?");
    $stmt_materi->bind_param("i", $id_mentor);
    $stmt_materi->execute();
    $stats['total_materi'] = $stmt_materi->get_result()->fetch_assoc()['total_materi'];
    $stmt_materi->close();
    
    // Total murid unik yang membeli kelas mentor
    $stmt_murid = $conn->prepare("SELECT COUNT(DISTINCT t.id_user) AS total_murid FROM tb_transaksi t JOIN tb_kelas k ON t.id_kelas = k.id_kelas WHERE k.id_mentor = ? AND t.status = 'Completed'");
    $stmt_murid->bind_param("i", $id_mentor);
    $stmt_murid->execute();
    $stats['total_murid'] = $stmt_murid->get_result()->fetch_assoc()['total_murid'];
    $stmt_murid->close();
    
} catch (Exception $e) {
    // Biarkan statistik 0 jika ada error
}

// --- START: Mengambil Kelas Terbaru ---
$recent_classes_result = null;
try {
    $recent_classes_query = $conn->prepare("SELECT nama_kelas, kategori, harga FROM tb_kelas WHERE id_mentor = ? ORDER BY id_kelas DESC LIMIT 5");
    $recent_classes_query->bind_param("i", $id_mentor);
    $recent_classes_query->execute();
    $recent_classes_result = $recent_classes_query->get_result();
} catch (Exception $e) {
    // Biarkan recent classes kosong jika error
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Memuat CSS dari sidebar Anda (pastikan path ini benar) -->
    <link rel="stylesheet" href="../assets/css/sidebar-mentor.css">
    
    <style>
        /* CSS tambahan untuk styling konten */
        body {
            background-color: #f8f9fa;
        }
        .stat-card {
            border: 1px solid #e3e6f0;
            border-left-width: 4px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border-radius: 0.5rem;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .border-primary { border-left-color: #4e73df !important; }
        .border-success { border-left-color: #1cc88a !important; }
        .border-info { border-left-color: #36b9cc !important; }
        .border-warning { border-left-color: #f6c23e !important; }
        .list-group-item {
            border-left: none;
            border-right: none;
        }
        .list-group-item:first-child { border-top: none; }
        .list-group-item:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <!-- Memanggil sidebar Anda yang memiliki position:fixed -->
    <?php include 'sidebar-mentor.php'; ?>

    <!-- MEMBUNGKUS KONTEN DENGAN KELAS .main-content DARI CSS ANDA -->
    <div class="main-content">
        <!-- Header Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Dashboard</h1>
                <p class="mb-0 text-muted">Selamat datang kembali, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
            </div>
            <div class="d-flex align-items-center">
                <form method="POST" class="me-2">
                    <button type="submit" class="btn btn-warning d-none d-sm-inline-block">
                        <i class="fas fa-sync-alt fa-sm"></i> Switch to Peserta
                    </button>
                </form>
                <a href="create-class.php" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus fa-sm"></i> Buat Kelas Baru
                </a>
            </div>
        </div>

        <!-- Kartu Statistik -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Kelas</div>
                                <div class="h5 mb-0 fw-bold text-gray-800"><?= $stats['total_kelas'] ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Penjualan</div>
                                <div class="h5 mb-0 fw-bold text-gray-800"><?= $stats['total_penjualan'] ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-receipt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Materi</div>
                                <div class="h5 mb-0 fw-bold text-gray-800"><?= $stats['total_materi'] ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Murid</div>
                                <div class="h5 mb-0 fw-bold text-gray-800"><?= $stats['total_murid'] ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Kelas Terbaru dan Aksi Cepat -->
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-history me-2"></i>Kelas Terbaru Anda</h6>
                        <a href="kelola-kelas.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if ($recent_classes_result && $recent_classes_result->num_rows > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php while ($class = $recent_classes_result->fetch_assoc()): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                        <div>
                                            <div class="fw-bold"><?= htmlspecialchars($class['nama_kelas']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($class['kategori']) ?></small>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">
                                            Rp <?= number_format($class['harga'], 0, ',', '.') ?>
                                        </span>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center p-5">
                                <p class="text-muted mb-0">Anda belum membuat kelas.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-4">
                <div class="card shadow">
                     <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="create-class.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Kelas Baru
                            </a>
                            <a href="kelola-kelas.php" class="btn btn-outline-secondary">
                                <i class="fas fa-cog me-2"></i>Kelola Semua Kelas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Akhir dari .main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
if ($recent_classes_result) $recent_classes_query->close();
$conn->close();
?>
