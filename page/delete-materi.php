<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";
$id_materi_to_delete = $_GET['id_materi'] ?? 0;

$redirect_url = 'kelola-materi.php'; // Default redirect URL

// Dapatkan id_mentor berdasarkan id_user yang login
$mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
$mentor_query->bind_param("i", $user_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

$id_mentor = 0;
if ($mentor_result->num_rows > 0) {
    $mentor_row = $mentor_result->fetch_assoc();
    $id_mentor = $mentor_row['id_mentor'];
}
$mentor_query->close();

if ($id_mentor === 0) {
    header("Location: " . $redirect_url . "?msg=" . urlencode("Error: ID Mentor tidak ditemukan."));
    exit();
}

if ($id_materi_to_delete > 0) {
    // 1. Verifikasi kepemilikan materi dan ambil info ID kelasnya
    $stmt_get_materi_info = $conn->prepare("
        SELECT tm.id_materi, tm.id_kelas, tk.nama_kelas
        FROM tb_materi tm
        JOIN tb_kelas tk ON tm.id_kelas = tk.id_kelas
        WHERE tm.id_materi = ? AND tk.id_mentor = ?
    ");
    $stmt_get_materi_info->bind_param("ii", $id_materi_to_delete, $id_mentor);
    $stmt_get_materi_info->execute();
    $result_materi_info = $stmt_get_materi_info->get_result();

    if ($result_materi_info->num_rows > 0) {
        $materi_info = $result_materi_info->fetch_assoc();
        $id_kelas_for_redirect = $materi_info['id_kelas'];
        $redirect_url = 'kelola-materi.php?id_kelas=' . $id_kelas_for_redirect; // Redirect kembali ke daftar materi di kelas tsb.

        // Mulai transaksi untuk memastikan semua operasi berhasil atau tidak sama sekali
        $conn->begin_transaction();
        $success = true;

        try {
            // 2. Ambil semua ID sub-materi, dokumen, dan video yang terkait dengan materi ini
            //    Ini penting untuk menghapus file fisik dan entri di tabel dokumen/video
            $stmt_get_sub_materi_info = $conn->prepare("
                SELECT
                    tsm.id_sub_materi, tsm.id_dokumen, tsm.id_video,
                    td.file_path_dokumen,
                    tv.file_path_video
                FROM tb_sub_materi tsm
                LEFT JOIN tb_dokumen td ON tsm.id_dokumen = td.id_dokumen
                LEFT JOIN tb_video tv ON tsm.id_video = tv.id_video
                WHERE tsm.id_materi = ?
            ");
            $stmt_get_sub_materi_info->bind_param("i", $id_materi_to_delete);
            $stmt_get_sub_materi_info->execute();
            $result_sub_materi_info = $stmt_get_sub_materi_info->get_result();

            $sub_materis_to_delete = [];
            while ($row = $result_sub_materi_info->fetch_assoc()) {
                $sub_materis_to_delete[] = $row;
            }
            $stmt_get_sub_materi_info->close();

            // 3. Hapus setiap sub-materi beserta dokumen/video terkait
            foreach ($sub_materis_to_delete as $sub_materi) {
                // Hapus sub-materi itu sendiri
                $stmt_delete_sub = $conn->prepare("DELETE FROM tb_sub_materi WHERE id_sub_materi = ?");
                $stmt_delete_sub->bind_param("i", $sub_materi['id_sub_materi']);
                if (!$stmt_delete_sub->execute()) {
                    throw new Exception("Gagal menghapus sub-materi (ID: " . $sub_materi['id_sub_materi'] . "): " . $stmt_delete_sub->error);
                }
                $stmt_delete_sub->close();

                // Hapus dokumen terkait jika ada
                if ($sub_materi['id_dokumen']) {
                    $stmt_delete_doc = $conn->prepare("DELETE FROM tb_dokumen WHERE id_dokumen = ?");
                    $stmt_delete_doc->bind_param("i", $sub_materi['id_dokumen']);
                    if (!$stmt_delete_doc->execute()) {
                        throw new Exception("Gagal menghapus entri dokumen (ID: " . $sub_materi['id_dokumen'] . "): " . $stmt_delete_doc->error);
                    }
                    $stmt_delete_doc->close();

                    if (!empty($sub_materi['file_path_dokumen']) && file_exists($sub_materi['file_path_dokumen'])) {
                        unlink($sub_materi['file_path_dokumen']);
                    }
                }

                // Hapus video terkait jika ada
                if ($sub_materi['id_video']) {
                    $stmt_delete_video = $conn->prepare("DELETE FROM tb_video WHERE id_video = ?");
                    $stmt_delete_video->bind_param("i", $sub_materi['id_video']);
                    if (!$stmt_delete_video->execute()) {
                        throw new Exception("Gagal menghapus entri video (ID: " . $sub_materi['id_video'] . "): " . $stmt_delete_video->error);
                    }
                    $stmt_delete_video->close();

                    if (!empty($sub_materi['file_path_video']) && file_exists($sub_materi['file_path_video'])) {
                        unlink($sub_materi['file_path_video']);
                    }
                }
            }

            // 4. Setelah semua sub-materi dan file-nya dihapus, baru hapus materi induk
            $stmt_delete_materi = $conn->prepare("DELETE FROM tb_materi WHERE id_materi = ?");
            $stmt_delete_materi->bind_param("i", $id_materi_to_delete);
            if (!$stmt_delete_materi->execute()) {
                throw new Exception("Gagal menghapus materi: " . $stmt_delete_materi->error);
            }
            $stmt_delete_materi->close();

            $conn->commit(); // Komit transaksi jika semua berhasil
            $message = "Materi '" . htmlspecialchars($materi_info['nama_kelas']) . "' dan semua sub-materi terkait berhasil dihapus.";

        } catch (Exception $e) {
            $conn->rollback(); // Rollback transaksi jika ada error
            $message = "Error saat menghapus materi: " . $e->getMessage();
            error_log($message); // Catat error ke log server
        }

    } else {
        $message = "Materi tidak ditemukan atau Anda tidak memiliki akses untuk menghapusnya.";
    }
    $stmt_get_materi_info->close();
} else {
    $message = "ID Materi tidak valid.";
}

header("Location: " . $redirect_url . "?msg=" . urlencode($message));
exit();
?>