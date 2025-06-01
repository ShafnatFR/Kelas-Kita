<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin-kelolaUser.php");
    exit();
}

// Pastikan parameter 'id' ada di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect kembali dengan pesan error jika ID tidak valid
    $_SESSION['error_message'] = "ID user tidak valid.";
    header("Location: admin-kelolaUser.php"); // Ganti dengan halaman dashboard admin Anda
    exit();
}

$userId = $_GET['id'];

// Pencegahan admin menonaktifkan akunnya sendiri
if ($userId == $_SESSION['user_id']) { // Anda perlu menyimpan id_user admin di sesi saat login
    $_SESSION['error_message'] = "Anda tidak bisa menonaktifkan akun Anda sendiri.";
    header("Location: admin-kelolaUser.php"); // Ganti dengan halaman dashboard admin Anda
    exit();
}

// Persiapkan dan jalankan query untuk menonaktifkan user
$stmt = null;
try {
    $stmt = $conn->prepare("UPDATE tb_user SET status = 'aktif' WHERE id_user = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Redirect kembali dengan pesan sukses
        $_SESSION['success_message'] = "User berhasil dinonaktifkan.";
    } else {
        // Redirect kembali dengan pesan error jika query gagal
        $_SESSION['error_message'] = "Gagal menonaktifkan user: " . $stmt->error;
    }
} catch (mysqli_sql_exception $e) {
    // Tangani pengecualian database
    $_SESSION['error_message'] = "Terjadi kesalahan database: " . $e->getMessage();
} finally {
    if ($stmt) {
        $stmt->close();
    }
    if ($conn) {
        $conn->close();
    }
    header("Location: admin-kelolaUser.php"); // Ganti dengan halaman dashboard admin Anda
    exit();
}
?>