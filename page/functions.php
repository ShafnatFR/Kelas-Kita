<?php
// functions.php
// File ini berisi fungsi-fungsi umum untuk interaksi database

// Pastikan session sudah dimulai jika file ini disertakan di halaman utama
// Jika file ini dipanggil langsung (misalnya dari form action), pastikan session dimulai di sini
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sertakan file koneksi database
// Pastikan db.php menginisialisasi $conn sebagai objek mysqli
require_once 'db.php';

/**
 * Fungsi untuk mengambil data dari database menggunakan prepared statements.
 *
 * @param mysqli $conn Objek koneksi MySQLi.
 * @param string $sql Query SQL yang akan dieksekusi.
 * @param string $types Tipe parameter (e.g., 's' for string, 'i' for integer).
 * @param array $params Array parameter untuk bind.
 * @return array|false Hasil query sebagai array asosiatif, atau false jika terjadi kesalahan.
 */
function fetchData(mysqli $conn, string $sql, string $types = '', array $params = []) {
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

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result === false) {
        error_log("Error getting result: " . $stmt->error . " for query: " . $sql);
        $stmt->close();
        return false;
    }
    
    if (strpos(strtoupper($sql), 'COUNT(') !== false || strpos(strtoupper($sql), 'SUM(') !== false ||
        strpos(strtoupper($sql), 'MAX(') !== false || strpos(strtoupper($sql), 'MIN(') !== false) {
        $data = $result->fetch_assoc();
    } else {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    $stmt->close();
    return $data;
}

/**
 * Fungsi untuk memperbarui status materi di database.
 *
 * @param mysqli $conn Objek koneksi MySQLi.
 * @param int $id_materi ID materi yang akan diperbarui.
 * @param string $status_baru Status materi yang baru ('pending', 'aktif', 'non-aktif').
 * @return bool True jika pembaruan berhasil, false jika gagal.
 */
function updateMateriStatus(mysqli $conn, int $id_materi, string $status_baru): bool {
    $sql = "UPDATE tb_materi SET status = ? WHERE id_materi = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error preparing update statement: " . $conn->error);
        return false;
    }
    $stmt->bind_param("si", $status_baru, $id_materi);
    $success = $stmt->execute();
    if (!$success) {
        error_log("Error executing update statement: " . $stmt->error);
    }
    $stmt->close();
    return $success;
}
?>
