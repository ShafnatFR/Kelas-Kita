<?php
session_start();
require 'db.php'; // Sesuaikan path

// Proteksi halaman, hanya untuk mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";
$message_type = "info"; // Untuk warna alert: "success", "danger"

// --- 1. Ambil ID Mentor ---
$mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
$mentor_query->bind_param("i", $user_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();
if ($mentor_result->num_rows === 0) {
    die("Error: Data mentor tidak ditemukan.");
}
$id_mentor = $mentor_result->fetch_assoc()['id_mentor'];
$mentor_query->close();

// --- 2. Hitung Total Pendapatan dari Transaksi yang "Completed" ---
// REVISI DI SINI: Menggunakan tb_transaksi
$stmt_total = $conn->prepare("
    SELECT SUM(k.harga) AS total_penjualan
    FROM tb_transaksi t
    JOIN tb_kelas k ON t.id_kelas = k.id_kelas
    WHERE k.id_mentor = ? AND t.status = 'Completed'
");
$stmt_total->bind_param("i", $id_mentor);
$stmt_total->execute();
$total_penjualan = $stmt_total->get_result()->fetch_assoc()['total_penjualan'] ?? 0;
$stmt_total->close();

// --- 3. Hitung Total Dana yang Sudah Ditarik atau Sedang Direquest ---
$stmt_ditarik = $conn->prepare("
    SELECT SUM(jumlah_diminta) AS total_ditarik
    FROM tb_request_penarikan
    WHERE id_mentor = ? AND (status = 'disetujui' OR status = 'pending')
");
$stmt_ditarik->bind_param("i", $id_mentor);
$stmt_ditarik->execute();
$total_ditarik = $stmt_ditarik->get_result()->fetch_assoc()['total_ditarik'] ?? 0;
$stmt_ditarik->close();

// --- 4. Hitung Saldo yang Tersedia untuk Ditarik ---
$saldo_tersedia = $total_penjualan - $total_ditarik;

// --- 5. Proses Form Jika di-Submit ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nominal_request = filter_input(INPUT_POST, 'nominal', FILTER_VALIDATE_FLOAT);

    // Validasi
    if ($nominal_request === false || $nominal_request <= 0) {
        $message = "Nominal yang dimasukkan tidak valid.";
        $message_type = "danger";
    } elseif ($nominal_request > $saldo_tersedia) {
        $message = "Gagal! Nominal yang Anda minta melebihi saldo yang tersedia.";
        $message_type = "danger";
    } else {
        // Masukkan ke database sebagai request baru
        $stmt_insert = $conn->prepare("INSERT INTO tb_request_penarikan (id_mentor, jumlah_diminta) VALUES (?, ?)");
        $stmt_insert->bind_param("id", $id_mentor, $nominal_request);
        if ($stmt_insert->execute()) {
            $message = "Permintaan penarikan dana sebesar Rp " . number_format($nominal_request, 0, ',', '.') . " berhasil diajukan. Mohon tunggu persetujuan admin.";
            $message_type = "success";
            // Refresh saldo tersedia setelah request berhasil
            $saldo_tersedia -= $nominal_request;
        } else {
            $message = "Terjadi kesalahan pada server. Coba lagi nanti.";
            $message_type = "danger";
        }
        $stmt_insert->close();
    }
}

// Ambil riwayat penarikan untuk ditampilkan
$history_stmt = $conn->prepare("SELECT jumlah_diminta, tanggal_request, status FROM tb_request_penarikan WHERE id_mentor = ? ORDER BY tanggal_request DESC");
$history_stmt->bind_param("i", $id_mentor);
$history_stmt->execute();
$history_result = $history_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendapatan & Penarikan Dana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><i class="bi bi-wallet2"></i> Informasi Pendapatan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h6 class="card-title">Total Penjualan</h6>
                                    <h3>Rp <?= number_format($total_penjualan, 0, ',', '.') ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="card text-white bg-danger">
                                <div class="card-body">
                                    <h6 class="card-title">Ditarik/Pending</h6>
                                    <h3>Rp <?= number_format($total_ditarik, 0, ',', '.') ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="card text-dark bg-warning">
                                <div class="card-body">
                                    <h6 class="card-title">Saldo Tersedia</h6>
                                    <h3>Rp <?= number_format($saldo_tersedia, 0, ',', '.') ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><i class="bi bi-cash-coin"></i> Ajukan Penarikan Dana</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal Penarikan (Rp)</label>
                            <input type="number" class="form-control" id="nominal" name="nominal" 
                                   min="10000" 
                                   max="<?= htmlspecialchars($saldo_tersedia) ?>" 
                                   placeholder="Contoh: 50000" 
                                   required 
                                   <?= ($saldo_tersedia < 10000) ? 'disabled' : '' ?>>
                            <div class="form-text">
                                Anda bisa menarik dana jika saldo tersedia minimal Rp 10.000.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" <?= ($saldo_tersedia < 10000) ? 'disabled' : '' ?>>
                            <i class="bi bi-send"></i> Kirim Permintaan
                        </button>
                        
                    </form>
                </div>
            </div>

             <div class="card">
                <div class="card-header">
                    <h4><i class="bi bi-clock-history"></i> Riwayat Penarikan</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal Request</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($history_result->num_rows > 0): ?>
                                <?php while($row = $history_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= date('d M Y, H:i', strtotime($row['tanggal_request'])) ?></td>
                                        <td>Rp <?= number_format($row['jumlah_diminta'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php
                                                $status = $row['status'];
                                                $badge_class = 'bg-secondary';
                                                if ($status == 'pending') $badge_class = 'bg-warning text-dark';
                                                if ($status == 'disetujui') $badge_class = 'bg-success';
                                                if ($status == 'ditolak') $badge_class = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada riwayat penarikan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>