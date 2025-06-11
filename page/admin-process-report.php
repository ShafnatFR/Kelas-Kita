<?php
session_start();
require 'db.php';

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_report = isset($_POST['id_report']) ? intval($_POST['id_report']) : 0;
    $id_kelas_reported = isset($_POST['id_kelas_reported']) ? intval($_POST['id_kelas_reported']) : 0;
    $catatan_admin = isset($_POST['catatan_admin']) ? $_POST['catatan_admin'] : '';

    $message_prefix = ""; // To store the primary message, will be combined with ban message if both occur

    // --- Action: Update Report Status ---
    if (isset($_POST['update_report_status'])) {
        $report_status = isset($_POST['report_status']) ? $_POST['report_status'] : '';

        if ($id_report > 0 && !empty($report_status)) {
            $allowed_statuses = ['Belum Diproses', 'Diproses', 'Selesai', 'Ditolak'];
            if (!in_array($report_status, $allowed_statuses)) {
                $message_prefix = "Status laporan yang dipilih tidak valid.";
                $_SESSION['message_type'] = "danger";
            } else {
                $stmt = $conn->prepare("UPDATE tb_laporan SET status_laporan = ?, catatan_admin = ? WHERE id_report = ?");
                if (!$stmt) {
                    $message_prefix = "Gagal mempersiapkan statement untuk update laporan: " . $conn->error;
                    $_SESSION['message_type'] = "danger";
                } else {
                    $stmt->bind_param("ssi", $report_status, $catatan_admin, $id_report);
                    if ($stmt->execute()) {
                        $message_prefix = "Status laporan berhasil diperbarui menjadi '" . htmlspecialchars($report_status) . "'.";
                        $_SESSION['message_type'] = "success";
                    } else {
                        $message_prefix = "Gagal memperbarui status laporan: " . $stmt->error;
                        $_SESSION['message_type'] = "danger";
                    }
                    $stmt->close();
                }
            }
        } else {
            $message_prefix = "Data tidak lengkap untuk memperbarui status laporan.";
            $_SESSION['message_type'] = "danger";
        }
    } 
    // --- Action: Ban Class ---
    elseif (isset($_POST['ban_class'])) {
        if ($id_kelas_reported > 0) {
            // Update status_publikasi kelas menjadi 'non-aktif'
            $stmt_ban_class = $conn->prepare("UPDATE tb_kelas SET status_publikasi = 'non-aktif' WHERE id_kelas = ?");
            if (!$stmt_ban_class) {
                $_SESSION['message'] = "Gagal mempersiapkan statement untuk membekukan kelas: " . $conn->error;
                $_SESSION['message_type'] = "danger";
            } else {
                $stmt_ban_class->bind_param("i", $id_kelas_reported);

                if ($stmt_ban_class->execute()) {
                    $message_prefix = "Kelas terkait (ID: " . htmlspecialchars($id_kelas_reported) . ") berhasil dibekukan.";
                    $_SESSION['message_type'] = "success";

                    // --- IMPORTANT ADDITION: Update report status after banning class ---
                    // You might want to set it to 'Selesai' or 'Diproses'
                    $new_report_status_after_ban = 'Selesai'; // Or 'Diproses'
                    $stmt_update_report = $conn->prepare("UPDATE tb_laporan SET status_laporan = ?, catatan_admin = CONCAT(catatan_admin, '\n[Otomatis: Kelas dibekukan pada ', NOW(), ']') WHERE id_report = ?");
                    if ($stmt_update_report) {
                        $stmt_update_report->bind_param("si", $new_report_status_after_ban, $id_report);
                        $stmt_update_report->execute();
                        $stmt_update_report->close();
                    } else {
                        error_log("Error updating report status after ban: " . $conn->error);
                    }
                    // --- END IMPORTANT ADDITION ---

                } else {
                    $message_prefix = "Gagal membekukan kelas: " . $stmt_ban_class->error;
                    $_SESSION['message_type'] = "danger";
                }
                $stmt_ban_class->close();
            }
        } else {
            $message_prefix = "ID Kelas untuk pembekuan tidak valid.";
            $_SESSION['message_type'] = "danger";
        }
    }
    else {
        // This 'else' block will catch cases where neither specific button was pressed
        // It's good to have a fallback or error message here if the flow isn't strict.
        $message_prefix = "Aksi tidak dikenal.";
        $_SESSION['message_type'] = "danger";
    }

    // Combine any messages if both actions were theoretically possible (e.g., if a user tried to manipulate POST)
    // For now, `message_prefix` will hold the most recent action's message.
    $_SESSION['message'] = $message_prefix;

} else {
    $_SESSION['message'] = "Akses tidak sah.";
    $_SESSION['message_type'] = "danger";
}

// Redirect kembali ke halaman detail laporan
if (isset($id_report) && $id_report > 0) {
    header("Location: admin-detail-report.php?id=" . $id_report);
} else {
    header("Location: admin-kelolaLaporan.php");
}
exit();
?>