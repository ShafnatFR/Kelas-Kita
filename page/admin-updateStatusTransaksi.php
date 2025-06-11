<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database Anda

// 1. Keamanan: Pastikan hanya admin yang sudah login yang bisa mengakses
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Jika bukan admin, kirim respons 'unauthorized'
    http_response_code(403); 
    echo "Akses ditolak.";
    exit();
}

// 2. Validasi: Pastikan ini adalah request POST dan data yang dibutuhkan ada
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_transaksi']) || !isset($_POST['status'])) {
    // Jika tidak valid, kembalikan ke halaman sebelumnya dengan pesan error
    $_SESSION['message'] = "Permintaan tidak valid.";
    $_SESSION['message_type'] = "danger";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'adminDashboard.php')); // Kembali ke halaman asal
    exit();
}

// 3. Sanitasi Input
$id_transaksi = $_POST['id_transaksi'];
$new_status = $_POST['status'];

// Validasi status yang diizinkan untuk keamanan tambahan
$allowed_statuses = ['Completed', 'Cancelled'];
if (!in_array($new_status, $allowed_statuses)) {
    $_SESSION['message'] = "Status yang dimasukkan tidak valid.";
    $_SESSION['message_type'] = "danger";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'adminDashboard.php'));
    exit();
}

// 4. Proses Update ke Database menggunakan Prepared Statement
$query = "UPDATE tb_transaksi SET status = ? WHERE id_transaksi = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    // Jika prepare gagal, catat error
    error_log("Prepare statement failed: " . $conn->error);
    $_SESSION['message'] = "Terjadi kesalahan pada server.";
    $_SESSION['message_type'] = "danger";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'adminDashboard.php'));
    exit();
}

$stmt->bind_param("ss", $new_status, $id_transaksi);

if ($stmt->execute()) {
    // Jika berhasil, siapkan pesan sukses
    $_SESSION['message'] = "Status transaksi #" . htmlspecialchars($id_transaksi) . " berhasil diperbarui menjadi " . htmlspecialchars($new_status) . ".";
    $_SESSION['message_type'] = "success";
} else {
    // Jika gagal, siapkan pesan error
    $_SESSION['message'] = "Gagal memperbarui status transaksi: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}

// 5. Tutup statement dan koneksi
$stmt->close();
$conn->close();

// 6. Redirect kembali ke halaman transaksi
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>