<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database Anda

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

// Cek koneksi database
if (!$conn) {
    // Ideally, db.php would handle this, but as a fallback:
    $_SESSION['message'] = "Koneksi database gagal.";
    $_SESSION['message_type'] = "danger";
    header("Location: kelolaTransaksi.php");
    exit();
}

// Pastikan ID transaksi disediakan di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_transaksi = $_GET['id'];

    // Prepare an update statement to change the transaction status to 'ditolak'
    $stmt = $conn->prepare("UPDATE tb_transaksi SET status = 'ditolak' WHERE id_transaksi = ?");
    if (!$stmt) {
        $_SESSION['message'] = "Error preparing statement: " . $conn->error;
        $_SESSION['message_type'] = "danger";
        header("Location: kelolaTransaksi.php");
        exit();
    }
    $stmt->bind_param("s", $id_transaksi); // 's' karena id_transaksi kemungkinan string (UUID)

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Transaksi berhasil ditolak.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Transaksi dengan ID '$id_transaksi' tidak ditemukan atau status sudah 'ditolak'.";
            $_SESSION['message_type'] = "warning";
        }
    } else {
        $_SESSION['message'] = "Gagal menolak transaksi: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    $conn->close();

    header("Location: kelolaTransaksi.php"); // Redirect kembali ke halaman daftar transaksi
    exit();
} else {
    // Jika ID transaksi tidak disediakan
    $_SESSION['message'] = "ID Transaksi tidak ditemukan.";
    $_SESSION['message_type'] = "danger";
    header("Location: admin-kelolaTransaksi.php");
    exit();
}
?>