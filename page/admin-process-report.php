<?php
session_start();
require 'db.php';

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['message'] = "Akses tidak sah.";
    $_SESSION['message_type'] = "danger";
    header("Location: admin-kelolaLaporan.php");
    exit();
}

// Inisialisasi variabel dari POST (tanpa catatan_admin)
$id_report = isset($_POST['id_report']) ? intval($_POST['id_report']) : 0;
$id_kelas_reported = isset($_POST['id_kelas_reported']) ? intval($_POST['id_kelas_reported']) : 0;
$report_status = isset($_POST['report_status']) ? $_POST['report_status'] : '';

$messages = [];
$message_type = "success";

$conn->begin_transaction();

try {
    // Aksi 1: Bekukan Kelas
    if (isset($_POST['ban_class'])) {
        if ($id_kelas_reported > 0) {
            $stmt_ban = $conn->prepare("UPDATE tb_kelas SET status_publikasi = 'non-aktif' WHERE id_kelas = ?");
            if (!$stmt_ban) throw new Exception("Gagal mempersiapkan statement pembekuan kelas: " . $conn->error);
            
            $stmt_ban->bind_param("i", $id_kelas_reported);
            if (!$stmt_ban->execute()) throw new Exception("Gagal mengeksekusi pembekuan kelas: " . $stmt_ban->error);

            $messages[] = "Kelas (ID: " . htmlspecialchars($id_kelas_reported) . ") berhasil dibekukan.";
            $stmt_ban->close();
            
            // Otomatis set status laporan ke 'Selesai' jika belum dipilih
            if (empty($report_status)) {
                $report_status = 'Selesai';
            }

        } else {
            throw new Exception("ID Kelas untuk pembekuan tidak valid.");
        }
    }

    // Aksi 2: Update Status Laporan
    if (!empty($report_status)) {
        $allowed_statuses = ['Belum Diproses', 'Diproses', 'Selesai', 'Ditolak'];
        if (!in_array($report_status, $allowed_statuses)) {
            throw new Exception("Status laporan yang dipilih tidak valid.");
        }

        if ($id_report > 0) {
            // Query UPDATE disederhanakan, tanpa catatan_admin
            $stmt_update = $conn->prepare("UPDATE tb_laporan SET status_laporan = ? WHERE id_report = ?");
            if (!$stmt_update) throw new Exception("Gagal mempersiapkan statement update laporan: " . $conn->error);

            // bind_param disesuaikan menjadi "si" (string, integer)
            $stmt_update->bind_param("si", $report_status, $id_report);
            if (!$stmt_update->execute()) throw new Exception("Gagal memperbarui status laporan: " . $stmt_update->error);

            $messages[] = "Status laporan berhasil diperbarui menjadi '" . htmlspecialchars($report_status) . "'.";
            $stmt_update->close();
        } else {
            throw new Exception("Data tidak lengkap untuk memperbarui status laporan.");
        }
    }

    if (empty($messages)) {
        $messages[] = "Tidak ada perubahan yang dilakukan.";
        $message_type = 'info';
    }

    $conn->commit();

} catch (Exception $e) {
    $conn->rollback();
    $messages[] = $e->getMessage();
    $message_type = "danger";
}

$_SESSION['message'] = implode("<br>", $messages);
$_SESSION['message_type'] = $message_type;

$conn->close();

if ($id_report > 0) {
    header("Location: admin-detail-report.php?id=" . $id_report);
} else {
    header("Location: admin-kelolaLaporan.php");
}
exit();
?>