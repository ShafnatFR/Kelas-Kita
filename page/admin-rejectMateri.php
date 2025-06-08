<?php
session_start();
require 'db.php'; // Pastikan koneksi database sudah terjalin

// Periksa apakah pengguna masuk dan memiliki peran admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

// Pastikan koneksi database berhasil
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Fungsi untuk mengeksekusi prepared statement dengan aman
function executeStatement(mysqli $conn, string $sql, string $types = '', array $params = []) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error preparing statement: " . $conn->error . " for query: " . $sql);
        return false;
    }

    if (!empty($params) && !empty($types)) {
        $bind_params = [];
        $bind_params[] = &$types;
        foreach ($params as &$param) {
            $bind_params[] = &$param;
        }
        call_user_func_array([$stmt, 'bind_param'], $bind_params);
    }

    $success = $stmt->execute();
    if (!$success) {
        error_log("Error executing statement: " . $stmt->error . " for query: " . $sql);
    }
    $stmt->close();
    return $success;
}

if (isset($_GET['id'])) {
    $id_materi = $_GET['id'];

    // Update status materi menjadi 'non-aktif' (sebagai 'rejected' karena enum terbatas)
    $sql = "UPDATE tb_materi SET status = 'non-aktif' WHERE id_materi = ?";
    if (executeStatement($conn, $sql, 'i', [$id_materi])) {
        $_SESSION['message'] = "Materi berhasil di-reject dan dinonaktifkan.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Gagal menolak materi.";
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "ID Materi tidak ditemukan.";
    $_SESSION['message_type'] = "danger";
}

// Tutup koneksi database
if ($conn) {
    $conn->close();
}

header("Location: admin-kelolaMateri.php"); // Kembali ke halaman kelola materi
exit();
?>
