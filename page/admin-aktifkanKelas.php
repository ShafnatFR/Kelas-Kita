<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Jika belum login atau bukan admin, redirect ke halaman login
    $_SESSION['error_message'] = "Anda harus login sebagai admin untuk mengakses halaman ini.";
    header("Location: adminLogin.php"); 
    exit();
}

// PERBAIKAN DI SINI: Hanya periksa parameter 'id'
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "ID kelas tidak valid atau tidak ditemukan.";
    header("Location: admin-kelolaKelas.php"); 
    exit();
}

$kelasId = (int)$_GET['id']; // Ambil ID kelas dan pastikan integer

// Persiapkan dan jalankan query untuk mengaktifkan/menyetujui kelas
$stmt = null;
try {
    // Mengubah status publikasi menjadi 'aktif' (atau 'approved' jika itu standar Anda)
    $stmt = $conn->prepare("UPDATE tb_kelas SET status_publikasi = 'approved' WHERE id_kelas = ?");
    if ($stmt === false) {
        // Gagal prepare statement
        $_SESSION['error_message'] = "Gagal mempersiapkan statement: " . $conn->error;
        header("Location: admin-kelolaKelas.php");
        exit();
    }
    
    $stmt->bind_param("i", $kelasId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Kelas berhasil diaktifkan/disetujui.";
        } else {
            $_SESSION['error_message'] = "Tidak ada kelas yang diubah. ID mungkin tidak ditemukan atau status sudah sesuai.";
        }
    } else {
        $_SESSION['error_message'] = "Gagal mengaktifkan kelas: " . $stmt->error;
    }
} catch (mysqli_sql_exception $e) {
    $_SESSION['error_message'] = "Terjadi kesalahan database: " . $e->getMessage();
} finally {
    if ($stmt) {
        $stmt->close();
    }
    if ($conn) {
        $conn->close();
    }
    // Selalu redirect kembali ke halaman kelola kelas
    header("Location: admin-kelolaKelas.php"); 
    exit();
}
?>