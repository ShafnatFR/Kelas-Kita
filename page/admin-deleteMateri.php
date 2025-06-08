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

    // Hapus sub_materi yang terkait dengan materi ini terlebih dahulu
    // Berdasarkan skema SQL, tb_sub_materi memiliki foreign key ke tb_materi.
    // Dokumen dan video tidak dihapus karena bisa digunakan oleh sub_materi lain.
    $sql_delete_sub_materi = "DELETE FROM tb_sub_materi WHERE id_materi = ?";
    $success_delete_sub_materi = executeStatement($conn, $sql_delete_sub_materi, 'i', [$id_materi]);

    if ($success_delete_sub_materi) {
        // Kemudian hapus materi itu sendiri
        $sql_delete_materi = "DELETE FROM tb_materi WHERE id_materi = ?";
        if (executeStatement($conn, $sql_delete_materi, 'i', [$id_materi])) {
            $_SESSION['message'] = "Materi dan sub-materi terkait berhasil dihapus permanen.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Gagal menghapus materi.";
            $_SESSION['message_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Gagal menghapus sub-materi terkait materi.";
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
