<?php
session_start();
require 'db.php'; // Pastikan path ini benar, misal: '../db.php' jika di subfolder admin

// Periksa apakah pengguna login dan memiliki peran admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

// Pastikan koneksi database berhasil
if (!$conn) {
    $_SESSION['message'] = "Koneksi database gagal.";
    $_SESSION['message_type'] = "danger";
    header("Location: admin-kelolaKelas.php"); // Kembali ke dashboard
    exit();
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
        for ($i = 0; $i < count($params); $i++) {
            $bind_params[] = &$params[$i];
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

// Tangani permintaan POST dari modal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kelas = $_POST['id_kelas'] ?? null;
    $catatan = $_POST['catatan'] ?? null;

    if ($id_kelas && $catatan !== null) {
        // Ambil id_mentor dari tb_kelas berdasarkan id_kelas
        $sql_get_mentor = "SELECT id_mentor FROM tb_kelas WHERE id_kelas = ?";
        $stmt_get_mentor = $conn->prepare($sql_get_mentor);
        if ($stmt_get_mentor) {
            $stmt_get_mentor->bind_param("i", $id_kelas);
            $stmt_get_mentor->execute();
            $result_mentor = $stmt_get_mentor->get_result();
            $kelas_data = $result_mentor->fetch_assoc();
            $stmt_get_mentor->close();

            if ($kelas_data && isset($kelas_data['id_mentor'])) {
                $id_mentor = $kelas_data['id_mentor'];

                // 1. Update status kelas menjadi 'non-aktif'
                $sql_update_kelas = "UPDATE tb_kelas SET status_publikasi = 'non-aktif' WHERE id_kelas = ?";
                $update_success = executeStatement($conn, $sql_update_kelas, "i", [$id_kelas]);

                // 2. Masukkan catatan ke tb_catatan
                if ($update_success) {
                    $sql_insert_catatan = "INSERT INTO tb_catatan (isi_catatan, id_mentor, id_kelas) VALUES (?, ?, ?)";
                    $insert_success = executeStatement($conn, $sql_insert_catatan, "sii", [$catatan, $id_mentor, $id_kelas]);
                    header("Location: admin-kelolaKelas.php"); // Kembali ke dashboard kelola kelas
                    exit();
                }
            } else {
                // Jika diakses langsung tanpa POST, redirect
                header("Location: admin-kelolaKelas.php");
                exit();
            }
        }
    }
}
?>