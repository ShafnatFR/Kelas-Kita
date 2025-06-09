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
    $status_aksi = isset($_POST['status_aksi']) ? $_POST['status_aksi'] : '';
    $catatan_admin = isset($_POST['catatan_admin']) ? $_POST['catatan_admin'] : ''; // Tambahan untuk catatan admin

    if ($id_report > 0 && !empty($status_aksi)) {
        // Validasi status yang diizinkan
        $allowed_statuses = ['Belum Diproses', 'Diproses', 'Selesai', 'Ditolak'];
        if (!in_array($status_aksi, $allowed_statuses)) {
            $_SESSION['message'] = "Status yang dipilih tidak valid.";
            $_SESSION['message_type'] = "danger";
            header("Location: admin-detail-report.php?id=" . $id_report); // Diubah ke nama file baru
            exit();
        }

        // Update status laporan di database
        $stmt = $conn->prepare("UPDATE tb_laporan SET status_laporan = ?, catatan_admin = ? WHERE id_report = ?");
        if (!$stmt) {
            $_SESSION['message'] = "Gagal mempersiapkan statement: " . $conn->error;
            $_SESSION['message_type'] = "danger";
            header("Location: admin-detail-report.php?id=" . $id_report); // Diubah ke nama file baru
            exit();
        }
        $stmt->bind_param("ssi", $status_aksi, $catatan_admin, $id_report);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Status laporan berhasil diperbarui menjadi '" . htmlspecialchars($status_aksi) . "'.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Gagal memperbarui status laporan: " . $stmt->error;
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Data tidak lengkap untuk memproses laporan.";
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Akses tidak sah.";
    $_SESSION['message_type'] = "danger";
}

// Redirect kembali ke halaman detail laporan atau daftar laporan
if (isset($id_report) && $id_report > 0) {
    header("Location: admin-detail-report.php?id=" . $id_report); // Diubah ke nama file baru
} else {
    header("Location: admin-kelolaLaporan.php");
}
exit();
?>