<?php
session_start();
require 'db.php';

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$report_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$laporan = null;

if ($report_id > 0) {
    // Kita tidak perlu lagi mengambil `catatan_admin` dari database
    $stmt = $conn->prepare("
        SELECT r.id_report, u.username, k.nama_kelas, r.kategori_report, r.keterangan_report, r.tgl_dibuat, r.status_laporan, r.id_kelas
        FROM tb_laporan r
        JOIN tb_user u ON u.id_user = r.id_user
        JOIN tb_kelas k ON k.id_kelas = r.id_kelas
        WHERE r.id_report = ?
    ");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $laporan = $result->fetch_assoc();
    $stmt->close();

    if (!$laporan) {
        $_SESSION['message'] = "Laporan tidak ditemukan.";
        $_SESSION['message_type'] = "danger";
        header("Location: admin-kelolaLaporan.php");
        exit();
    }
} else {
    $_SESSION['message'] = "ID Laporan tidak valid.";
    $_SESSION['message_type'] = "danger";
    header("Location: admin-kelolaLaporan.php");
    exit();
}

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan #<?= htmlspecialchars($laporan['id_report']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; }
        .content-wrapper { padding: 20px; flex: 1; margin-left: 250px; /* Sesuaikan dengan lebar sidebar */ }
    </style>
</head>
<body class="bg-light">
    <?php include "adminSidebar.php"; ?>
    <div class="content-wrapper">     
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-primary"><i class="fas fa-file-alt me-2"></i> Detail Laporan #<?= htmlspecialchars($laporan['id_report']) ?></h2>
                    <p class="text-muted">Kelola detail dan tindakan untuk laporan ini.</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white"><h5 class="mb-0">Informasi Laporan</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID Laporan:</strong> <?= htmlspecialchars($laporan['id_report']) ?></p>
                            <p><strong>Username Pelapor:</strong> <?= htmlspecialchars($laporan['username']) ?></p>
                            <p><strong>Nama Kelas:</strong> <?= htmlspecialchars($laporan['nama_kelas']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Kategori Laporan:</strong> <span class="badge bg-danger"><?= htmlspecialchars($laporan['kategori_report']) ?></span></p>
                            <p><strong>Tanggal Dibuat:</strong> <?= (new DateTime($laporan['tgl_dibuat']))->format('d M Y H:i') ?></p>
                            <p><strong>Status Laporan:</strong> 
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
                            </p>
                        </div>
                    </div>
                    <p><strong>Keterangan Laporan:</strong><br><?= nl2br(htmlspecialchars($laporan['keterangan_report'])) ?></p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white"><h5 class="mb-0">Tindakan Admin</h5></div>
                <div class="card-body">
                    <form action="admin-process-report.php" method="POST">
                        <input type="hidden" name="id_report" value="<?= htmlspecialchars($laporan['id_report']) ?>">
                        <input type="hidden" name="id_kelas_reported" value="<?= htmlspecialchars($laporan['id_kelas']) ?>">
                        
                        <div class="mb-3">
                            <label for="report_status" class="form-label">Ubah Status Laporan:</label>
                            <select class="form-select" id="report_status" name="report_status" required>
                                <option value="">Pilih Status</option>
                                <option value="Belum Diproses" <?= ($laporan['status_laporan'] == 'Belum Diproses') ? 'selected' : '' ?>>Belum Diproses</option>
                                <option value="Diproses" <?= ($laporan['status_laporan'] == 'Diproses') ? 'selected' : '' ?>>Diproses</option>
                                <option value="Selesai" <?= ($laporan['status_laporan'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                                <option value="Ditolak" <?= ($laporan['status_laporan'] == 'Ditolak') ? 'selected' : '' ?>>Ditolak (Tidak ada pelanggaran)</option>
                            </select>
                        </div>

                        <p class="text-muted">
                            <strong>Tindakan Lainnya (Opsional):</strong><br>
                            <small>- Anda dapat membekukan kelas terkait jika diperlukan.</small>
                        </p>

                        <button type="submit" name="update_report_status" class="btn btn-success me-2"><i class="fas fa-save me-1"></i>Simpan Perubahan Status</button>
                        <button type="submit" name="ban_class" class="btn btn-danger"><i class="fas fa-ban me-1"></i>Bekukan Kelas</button>
                        <a href="admin-kelolaLaporan.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Laporan</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
if ($conn) $conn->close();
?>