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
    $id_kelas = $_GET['id'];

    // Hapus kelas dari tb_kelas
    // HATI-HATI: Pastikan tidak ada foreign key constraint yang akan mencegah penghapusan ini
    // Atau, Anda perlu menghapus data terkait di tabel lain terlebih dahulu (misal tb_materi, tb_sub_materi, tb_komentar, dll.)
    // Berdasarkan skema SQL yang Anda berikan, tb_materi memiliki foreign key ke tb_kelas.
    // Jadi, Anda perlu menghapus materi terkait terlebih dahulu.

    // Contoh penghapusan bertahap (jika ada data terkait):
    // 1. Hapus entri di tb_sub_materi yang terkait dengan materi di kelas ini
    //    Ini bisa menjadi kompleks jika tb_sub_materi memiliki foreign key ke tb_dokumen dan tb_video
    //    Saya akan menghapus tb_sub_materi, kemudian tb_materi, baru tb_kelas.
    //    Perhatikan bahwa dokumen dan video tidak akan dihapus karena bisa digunakan oleh materi lain.

    // Mendapatkan id_materi yang terkait dengan id_kelas ini
    $materi_ids = [];
    $sql_get_materi_ids = "SELECT id_materi FROM tb_materi WHERE id_kelas = ?";
    $stmt_get_materi_ids = $conn->prepare($sql_get_materi_ids);
    if ($stmt_get_materi_ids) {
        $stmt_get_materi_ids->bind_param('i', $id_kelas);
        $stmt_get_materi_ids->execute();
        $result_materi_ids = $stmt_get_materi_ids->get_result();
        while ($row = $result_materi_ids->fetch_assoc()) {
            $materi_ids[] = $row['id_materi'];
        }
        $stmt_get_materi_ids->close();
    }

    $success_delete_sub_materi = true;
    if (!empty($materi_ids)) {
        // Hapus sub_materi yang terkait dengan materi-materi ini
        foreach ($materi_ids as $m_id) {
            $sql_delete_sub_materi = "DELETE FROM tb_sub_materi WHERE id_materi = ?";
            if (!executeStatement($conn, $sql_delete_sub_materi, 'i', [$m_id])) {
                $success_delete_sub_materi = false;
                break; // Hentikan jika ada kegagalan
            }
        }
    }

    if ($success_delete_sub_materi) {
        // Hapus materi yang terkait dengan kelas ini
        $sql_delete_materi = "DELETE FROM tb_materi WHERE id_kelas = ?";
        $success_delete_materi = executeStatement($conn, $sql_delete_materi, 'i', [$id_kelas]);

        if ($success_delete_materi) {
            // Hapus komentar yang terkait dengan kelas ini (jika ada)
            $sql_delete_komentar = "DELETE FROM tb_komentar WHERE id_kelas = ?";
            executeStatement($conn, $sql_delete_komentar, 'i', [$id_kelas]); // Tidak kritis jika gagal

            // Hapus keranjang yang terkait dengan kelas ini (jika ada)
            $sql_delete_keranjang = "DELETE FROM tb_keranjang WHERE id_kelas = ?";
            executeStatement($conn, $sql_delete_keranjang, 'i', [$id_kelas]); // Tidak kritis jika gagal

            // Hapus laporan yang terkait dengan kelas ini (jika ada)
            $sql_delete_laporan = "DELETE FROM tb_laporan WHERE id_kelas = ?";
            executeStatement($conn, $sql_delete_laporan, 'i', [$id_kelas]); // Tidak kritis jika gagal

            // Hapus progress kelas yang terkait dengan kelas ini (jika ada)
            $sql_delete_progress = "DELETE FROM tb_progress_kelas WHERE id_kelas = ?";
            executeStatement($conn, $sql_delete_progress, 'i', [$id_kelas]); // Tidak kritis jika gagal

            // Hapus review yang terkait dengan kelas ini (jika ada)
            $sql_delete_review = "DELETE FROM tb_review WHERE id_kelas = ?";
            executeStatement($conn, $sql_delete_review, 'i', [$id_kelas]); // Tidak kritis jika gagal

            // Hapus transaksi yang terkait dengan kelas ini (jika ada)
            $sql_delete_transaksi = "DELETE FROM tb_transaksi WHERE id_kelas = ?";
            executeStatement($conn, $sql_delete_transaksi, 'i', [$id_kelas]); // Tidak kritis jika gagal

            // Terakhir, hapus kelas itu sendiri
            $sql_delete_kelas = "DELETE FROM tb_kelas WHERE id_kelas = ?";
            if (executeStatement($conn, $sql_delete_kelas, 'i', [$id_kelas])) {
                $_SESSION['message'] = "Kelas dan data terkait berhasil dihapus permanen.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Gagal menghapus kelas.";
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "Gagal menghapus materi terkait kelas.";
            $_SESSION['message_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Gagal menghapus sub materi terkait kelas.";
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "ID Kelas tidak ditemukan.";
    $_SESSION['message_type'] = "danger";
}

// Tutup koneksi database
if ($conn) {
    $conn->close();
}

header("Location: admin-kelolaKelas.php"); // Kembali ke halaman kelola kelas
exit();
?>
