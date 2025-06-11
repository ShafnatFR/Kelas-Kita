<?php
session_start();
require 'db.php'; // Pastikan path ini benar

// 1. Keamanan: Pastikan hanya admin yang sudah login bisa mengakses
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Kirim respons 'Forbidden' jika diakses secara tidak sah
    http_response_code(403);
    die("AKSES DITOLAK: Anda harus login sebagai admin.");
}

// 2. Keamanan: Pastikan permintaan menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    die("METODE TIDAK DIIZINKAN: Aksi ini hanya bisa dilakukan melalui metode POST.");
}

// 3. Keamanan: Validasi Token CSRF untuk mencegah serangan Cross-Site Request Forgery
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    die("TOKEN TIDAK VALID: Permintaan tidak dapat diproses karena alasan keamanan.");
}

// 4. Validasi Input: Pastikan id_review ada dan merupakan angka
if (!isset($_POST['id_review']) || !filter_var($_POST['id_review'], FILTER_VALIDATE_INT)) {
    $_SESSION['message'] = "ID review tidak valid atau tidak ditemukan.";
    $_SESSION['message_type'] = "danger";
    header('Location: adminDashboard.php'); // Kembali ke dashboard
    exit();
}

$id_review = (int)$_POST['id_review'];

// 5. Proses Penghapusan Data menggunakan Prepared Statement
$query = "DELETE FROM tb_review WHERE id_review = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    // Jika prepare statement gagal
    $_SESSION['message'] = "Terjadi kesalahan pada server saat menyiapkan query.";
    $_SESSION['message_type'] = "danger";
    error_log("Prepare statement failed: " . $conn->error); // Catat error untuk admin
    header('Location: adminDashboard.php');
    exit();
}

$stmt->bind_param("i", $id_review);

// 6. Eksekusi dan Berikan Feedback
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Review berhasil dihapus.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Review tidak ditemukan atau sudah dihapus sebelumnya.";
        $_SESSION['message_type'] = "warning";
    }
} else {
    $_SESSION['message'] = "Gagal menghapus review: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}

// 7. Tutup statement dan koneksi
$stmt->close();
$conn->close();

// 8. Alihkan kembali ke halaman dashboard
header('Location: adminDashboard.php');
exit();
?>