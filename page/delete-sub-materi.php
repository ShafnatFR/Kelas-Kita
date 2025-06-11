<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

// --- START: Validasi dan Inisialisasi ---
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'mentor' || !isset($_SESSION['id'])) {
    header("Location: HalamanSignIn.php");
    exit();
}

// Validasi input dari URL
if (!isset($_GET['id_sub_materi']) || !filter_var($_GET['id_sub_materi'], FILTER_VALIDATE_INT)) {
    header("Location: kelola-kelas.php?msg=" . urlencode("Error: ID Sub-Materi tidak valid."));
    exit();
}

$id_sub_materi_to_delete = (int)$_GET['id_sub_materi'];
$user_id = $_SESSION['id'];
$message = "";
$id_mentor = 0;
$redirect_url = 'kelola-kelas.php'; // URL default jika terjadi error awal

// --- START: Dapatkan ID Mentor ---
try {
    $mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
    if (!$mentor_query) throw new Exception("Prepare failed: " . $conn->error);
    
    $mentor_query->bind_param("i", $user_id);
    $mentor_query->execute();
    $mentor_result = $mentor_query->get_result();

    if ($mentor_row = $mentor_result->fetch_assoc()) {
        $id_mentor = $mentor_row['id_mentor'];
    } else {
        throw new Exception("Data mentor tidak ditemukan.");
    }
    $mentor_query->close();
} catch (Exception $e) {
    die("Error Kritis: " . $e->getMessage());
}

// --- START: Proses Penghapusan ---
$conn->begin_transaction();
try {
    // 1. Validasi Kepemilikan dan Ambil Detail Sub-Materi
    //    Query ini mengambil semua ID yang dibutuhkan dan sekaligus memvalidasi bahwa sub-materi ini milik mentor yang login.
    $check_stmt = $conn->prepare("
        SELECT 
            sm.id_sub_materi, sm.id_dokumen, sm.id_video,
            d.file_path_dokumen,
            m.id_materi,
            k.id_kelas
        FROM tb_sub_materi sm
        JOIN tb_materi m ON sm.id_materi = m.id_materi
        JOIN tb_kelas k ON m.id_kelas = k.id_kelas
        LEFT JOIN tb_dokumen d ON sm.id_dokumen = d.id_dokumen
        WHERE sm.id_sub_materi = ? AND k.id_mentor = ?
    ");
    if (!$check_stmt) throw new Exception("Prepare check owner failed: " . $conn->error);

    $check_stmt->bind_param("ii", $id_sub_materi_to_delete, $id_mentor);
    $check_stmt->execute();
    $sub_materi_result = $check_stmt->get_result();

    if ($sub_materi_data = $sub_materi_result->fetch_assoc()) {
        $id_dokumen_to_delete = $sub_materi_data['id_dokumen'];
        $id_video_to_delete = $sub_materi_data['id_video'];
        $file_path_to_delete = $sub_materi_data['file_path_dokumen'];
        
        // Simpan info untuk redirect kembali ke halaman yang benar
        $id_materi_redirect = $sub_materi_data['id_materi'];
        $id_kelas_redirect = $sub_materi_data['id_kelas'];
        $redirect_url = "kelola-materi.php?id_kelas={$id_kelas_redirect}&id_materi={$id_materi_redirect}";

        // 2. Hapus record dari tb_sub_materi (langkah utama)
        $delete_sm_stmt = $conn->prepare("DELETE FROM tb_sub_materi WHERE id_sub_materi = ?");
        $delete_sm_stmt->bind_param("i", $id_sub_materi_to_delete);
        $delete_sm_stmt->execute();
        $delete_sm_stmt->close();

        // 3. Hapus record dokumen dan file fisiknya (jika ada)
        if ($id_dokumen_to_delete) {
            $delete_doc_stmt = $conn->prepare("DELETE FROM tb_dokumen WHERE id_dokumen = ?");
            $delete_doc_stmt->bind_param("i", $id_dokumen_to_delete);
            $delete_doc_stmt->execute();
            $delete_doc_stmt->close();

            // Hapus file fisik dari server
            if (!empty($file_path_to_delete) && file_exists($file_path_to_delete)) {
                unlink($file_path_to_delete);
            }
        }

        // 4. Hapus record video (jika ada)
        if ($id_video_to_delete) {
            $delete_vid_stmt = $conn->prepare("DELETE FROM tb_video WHERE id_video = ?");
            $delete_vid_stmt->bind_param("i", $id_video_to_delete);
            $delete_vid_stmt->execute();
            $delete_vid_stmt->close();
        }

        $conn->commit();
        $message = "Sub-materi berhasil dihapus.";

    } else {
        // Jika tidak ditemukan, berarti bukan milik mentor ini atau ID salah
        throw new Exception("Sub-materi tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.");
    }
    $check_stmt->close();

} catch (Exception $e) {
    $conn->rollback();
    $message = "Terjadi kesalahan saat menghapus: " . $e->getMessage();
}

// --- START: Redirect dengan Pesan ---
header("Location: {$redirect_url}&msg=" . urlencode($message));
exit();
?>
