<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";
$id_sub_materi = $_GET['id_sub_materi'] ?? 0; // Ambil ID Sub-Materi dari URL

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
    $message = "Error: ID Mentor tidak ditemukan untuk user ini. Silakan hubungi admin.";
}

$sub_materi_data = null;
$id_kelas_for_redirect = 0;
$id_materi_for_redirect = 0;

if ($id_sub_materi > 0 && $id_mentor > 0) {
    // Ambil data sub-materi yang akan diedit dan pastikan itu milik mentor yang login
    $stmt = $conn->prepare("
        SELECT
            tsm.id_sub_materi, tsm.id_materi, tsm.judul_sub_materi, tsm.urutan,
            tsm.id_dokumen, tsm.id_video,
            tm.judul_materi, tm.id_kelas, tk.nama_kelas,
            td.file_path_dokumen,
            tv.file_path_video
        FROM tb_sub_materi tsm
        JOIN tb_materi tm ON tsm.id_materi = tm.id_materi
        JOIN tb_kelas tk ON tm.id_kelas = tk.id_kelas
        LEFT JOIN tb_dokumen td ON tsm.id_dokumen = td.id_dokumen
        LEFT JOIN tb_video tv ON tsm.id_video = tv.id_video
        WHERE tsm.id_sub_materi = ? AND tk.id_mentor = ?
    ");
    $stmt->bind_param("ii", $id_sub_materi, $id_mentor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sub_materi_data = $result->fetch_assoc();
        $id_kelas_for_redirect = $sub_materi_data['id_kelas'];
        $id_materi_for_redirect = $sub_materi_data['id_materi'];
    } else {
        $message = "Sub-Materi tidak ditemukan atau Anda tidak memiliki akses untuk mengeditnya.";
        $id_sub_materi = 0; // Invalid ID
    }
    $stmt->close();
} else {
    $message = "ID Sub-Materi tidak valid.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $sub_materi_data) {
    $new_judul_sub_materi = trim($_POST['judul_sub_materi']);
    $new_urutan = trim($_POST['urutan']);

    $id_dokumen_to_save = $sub_materi_data['id_dokumen']; // Pertahankan ID yang sudah ada secara default
    $id_video_to_save = $sub_materi_data['id_video'];     // Pertahankan ID yang sudah ada secara default
    
    $old_file_path_dokumen = $sub_materi_data['file_path_dokumen'];
    $old_file_path_video = $sub_materi_data['file_path_video'];

    $upload_dir_d = '../uploads/dokumen/'; // Pastikan path ini sesuai
    $upload_dir_v = '../uploads/video/';   // Pastikan path ini sesuai

    $conn->begin_transaction(); // Mulai transaksi

    try {
        // --- Proses Upload / Ganti Dokumen ---
        if (isset($_FILES['dokumen_file']) && $_FILES['dokumen_file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp_name_d = $_FILES['dokumen_file']['tmp_name'];
            $file_name_d = basename($_FILES['dokumen_file']['name']);
            $file_size_d = $_FILES['dokumen_file']['size'];
            $file_type_d = $_FILES['dokumen_file']['type'];

            $allowed_types_d = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
            
            if (!in_array($file_type_d, $allowed_types_d)) {
                throw new Exception("Tipe file dokumen tidak diizinkan. Hanya PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.");
            } elseif ($file_size_d > 20 * 1024 * 1024) { // Batas 20MB
                throw new Exception("Ukuran file dokumen terlalu besar. Maksimal 20MB.");
            }

            $new_file_path_dokumen = $upload_dir_d . uniqid() . '_' . $file_name_d;

            if (move_uploaded_file($file_tmp_name_d, $new_file_path_dokumen)) {
                // Hapus dokumen lama jika ada
                if ($id_dokumen_to_save) { // Ada dokumen lama yang akan diganti
                    // Hapus entri dokumen lama dari tb_dokumen
                    $delete_old_doc_stmt = $conn->prepare("DELETE FROM tb_dokumen WHERE id_dokumen = ?");
                    $delete_old_doc_stmt->bind_param("i", $id_dokumen_to_save);
                    if (!$delete_old_doc_stmt->execute()) {
                        throw new Exception("Gagal menghapus entri dokumen lama: " . $delete_old_doc_stmt->error);
                    }
                    $delete_old_doc_stmt->close();

                    // Hapus file fisik dokumen lama
                    if (!empty($old_file_path_dokumen) && file_exists($old_file_path_dokumen)) {
                        unlink($old_file_path_dokumen);
                    }
                }

                // Simpan info dokumen baru ke database
                $insert_doc_stmt = $conn->prepare("INSERT INTO tb_dokumen (file_path_dokumen) VALUES (?)");
                $insert_doc_stmt->bind_param("s", $new_file_path_dokumen);
                if (!$insert_doc_stmt->execute()) {
                    throw new Exception("Gagal menyimpan info dokumen baru ke database: " . $insert_doc_stmt->error);
                }
                $id_dokumen_to_save = $insert_doc_stmt->insert_id;
                $insert_doc_stmt->close();
            } else {
                throw new Exception("Gagal memindahkan file dokumen yang diunggah.");
            }
        } elseif (isset($_POST['remove_dokumen']) && $_POST['remove_dokumen'] === '1' && $id_dokumen_to_save) {
            // Logika untuk menghapus dokumen tanpa menggantinya
            $delete_old_doc_stmt = $conn->prepare("DELETE FROM tb_dokumen WHERE id_dokumen = ?");
            $delete_old_doc_stmt->bind_param("i", $id_dokumen_to_save);
            if (!$delete_old_doc_stmt->execute()) {
                throw new Exception("Gagal menghapus entri dokumen: " . $delete_old_doc_stmt->error);
            }
            $delete_old_doc_stmt->close();

            if (!empty($old_file_path_dokumen) && file_exists($old_file_path_dokumen)) {
                unlink($old_file_path_dokumen);
            }
            $id_dokumen_to_save = NULL; // Setel ke NULL karena dokumen telah dihapus
        }


        // --- Proses Upload / Ganti Video ---
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp_name_v = $_FILES['video_file']['tmp_name'];
            $file_name_v = basename($_FILES['video_file']['name']);
            $file_size_v = $_FILES['video_file']['size'];
            $file_type_v = $_FILES['video_file']['type'];

            $allowed_types_v = ['video/mp4', 'video/webm', 'video/ogg']; // Tambahkan tipe video lain jika perlu
            
            if (!in_array($file_type_v, $allowed_types_v)) {
                throw new Exception("Tipe file video tidak diizinkan. Hanya MP4, WebM, OGG.");
            } elseif ($file_size_v > 100 * 1024 * 1024) { // Batas 100MB
                throw new Exception("Ukuran file video terlalu besar. Maksimal 100MB.");
            }

            $new_file_path_video = $upload_dir_v . uniqid() . '_' . $file_name_v;

            if (move_uploaded_file($file_tmp_name_v, $new_file_path_video)) {
                // Hapus video lama jika ada
                if ($id_video_to_save) { // Ada video lama yang akan diganti
                    // Hapus entri video lama dari tb_video
                    $delete_old_video_stmt = $conn->prepare("DELETE FROM tb_video WHERE id_video = ?");
                    $delete_old_video_stmt->bind_param("i", $id_video_to_save);
                    if (!$delete_old_video_stmt->execute()) {
                        throw new Exception("Gagal menghapus entri video lama: " . $delete_old_video_stmt->error);
                    }
                    $delete_old_video_stmt->close();

                    // Hapus file fisik video lama
                    if (!empty($old_file_path_video) && file_exists($old_file_path_video)) {
                        unlink($old_file_path_video);
                    }
                }

                // Simpan info video baru ke database
                $insert_video_stmt = $conn->prepare("INSERT INTO tb_video (file_path_video) VALUES (?)");
                $insert_video_stmt->bind_param("s", $new_file_path_video);
                if (!$insert_video_stmt->execute()) {
                    throw new Exception("Gagal menyimpan info video baru ke database: " . $insert_video_stmt->error);
                }
                $id_video_to_save = $insert_video_stmt->insert_id;
                $insert_video_stmt->close();
            } else {
                throw new Exception("Gagal memindahkan file video yang diunggah.");
            }
        } elseif (isset($_POST['remove_video']) && $_POST['remove_video'] === '1' && $id_video_to_save) {
            // Logika untuk menghapus video tanpa menggantinya
            $delete_old_video_stmt = $conn->prepare("DELETE FROM tb_video WHERE id_video = ?");
            $delete_old_video_stmt->bind_param("i", $id_video_to_save);
            if (!$delete_old_video_stmt->execute()) {
                throw new Exception("Gagal menghapus entri video: " . $delete_old_video_stmt->error);
            }
            $delete_old_video_stmt->close();

            if (!empty($old_file_path_video) && file_exists($old_file_path_video)) {
                unlink($old_file_path_video);
            }
            $id_video_to_save = NULL; // Setel ke NULL karena video telah dihapus
        }


        // Validasi input
        if (empty($new_judul_sub_materi)) {
            throw new Exception("Judul Sub-Materi wajib diisi.");
        } elseif (!is_numeric($new_urutan) || $new_urutan < 1) {
            throw new Exception("Urutan harus berupa angka positif.");
        }

        // Update data sub-materi di database
        $update_stmt = $conn->prepare("
            UPDATE tb_sub_materi
            SET judul_sub_materi = ?, urutan = ?, id_dokumen = ?, id_video = ?
            WHERE id_sub_materi = ? AND id_materi = ?
        ");
        // Gunakan 'i' untuk NULL pada bind_param jika Anda menggunakan PHP 8.1+ dan MySQL Native Driver
        // Untuk kompatibilitas lebih luas atau versi PHP < 8.1, pastikan id_dokumen/id_video adalah integer
        // dan NULL akan otomatis dikonversi oleh PDO/MySQLi jika kolom diizinkan NULL
        $update_stmt->bind_param("siiiis", $new_judul_sub_materi, $new_urutan, $id_dokumen_to_save, $id_video_to_save, $id_sub_materi, $sub_materi_data['id_materi']);

        if (!$update_stmt->execute()) {
            throw new Exception("Gagal memperbarui sub-materi: " . $update_stmt->error);
        }
        $update_stmt->close();

        $conn->commit(); // Komit transaksi jika semua berhasil
        $message = "Sub-Materi berhasil diperbarui!";
        header("Location: kelola-materi.php?id_kelas=" . $id_kelas_for_redirect . "&id_materi=" . $id_materi_for_redirect . "&msg=" . urlencode($message));
        exit();

    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaksi jika ada error
        $message = "Error: " . $e->getMessage();
        // Hapus file baru jika ada error setelah upload tapi sebelum commit
        if (isset($new_file_path_dokumen) && file_exists($new_file_path_dokumen)) {
            unlink($new_file_path_dokumen);
        }
        if (isset($new_file_path_video) && file_exists($new_file_path_video)) {
            unlink($new_file_path_video);
        }
    }
    // Refresh data sub-materi setelah percobaan POST untuk menampilkan nilai terbaru (jika gagal update)
    // atau untuk menampilkan pesan error.
    // fetch ulang data untuk memastikan tampilan sesuai jika ada error dan tidak redirect
    $stmt = $conn->prepare("
        SELECT
            tsm.id_sub_materi, tsm.id_materi, tsm.judul_sub_materi, tsm.urutan,
            tsm.id_dokumen, tsm.id_video,
            tm.judul_materi, tm.id_kelas, tk.nama_kelas,
            td.file_path_dokumen,
            tv.file_path_video
        FROM tb_sub_materi tsm
        JOIN tb_materi tm ON tsm.id_materi = tm.id_materi
        JOIN tb_kelas tk ON tm.id_kelas = tk.id_kelas
        LEFT JOIN tb_dokumen td ON tsm.id_dokumen = td.id_dokumen
        LEFT JOIN tb_video tv ON tsm.id_video = tv.id_video
        WHERE tsm.id_sub_materi = ? AND tk.id_mentor = ?
    ");
    $stmt->bind_param("ii", $id_sub_materi, $id_mentor);
    $stmt->execute();
    $result = $stmt->get_result();
    $sub_materi_data = $result->fetch_assoc(); // Update $sub_materi_data
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sub-Materi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Sub-Materi</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($sub_materi_data): ?>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Kelas:</label>
                                    <p class="form-control-static"><strong><?= htmlspecialchars($sub_materi_data['nama_kelas']) ?></strong></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Materi Induk:</label>
                                    <p class="form-control-static"><strong><?= htmlspecialchars($sub_materi_data['judul_materi']) ?></strong></p>
                                </div>

                                <div class="mb-3">
                                    <label for="judul_sub_materi" class="form-label">Judul Sub-Materi</label>
                                    <input type="text" class="form-control" id="judul_sub_materi" name="judul_sub_materi" value="<?= htmlspecialchars($sub_materi_data['judul_sub_materi']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="urutan" class="form-label">Urutan</label>
                                    <input type="number" class="form-control" id="urutan" name="urutan" value="<?= htmlspecialchars($sub_materi_data['urutan']) ?>" min="1" required>
                                </div>

                                <hr>
                                <h5 class="mb-3">Kelola Dokumen</h5>
                                <div class="mb-3">
                                    <?php if (!empty($sub_materi_data['file_path_dokumen'])): ?>
                                        <p class="form-control-static">Dokumen saat ini: <a href="<?= htmlspecialchars($sub_materi_data['file_path_dokumen']) ?>" target="_blank"><?= htmlspecialchars(basename($sub_materi_data['file_path_dokumen'])) ?></a></p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remove_dokumen" name="remove_dokumen" value="1">
                                            <label class="form-check-label" for="remove_dokumen">
                                                Hapus dokumen ini
                                            </label>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">Belum ada dokumen untuk sub-materi ini.</p>
                                    <?php endif; ?>
                                    <label for="dokumen_file" class="form-label mt-2">Ganti / Unggah Dokumen Baru (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX - Max 20MB)</label>
                                    <input class="form-control" type="file" id="dokumen_file" name="dokumen_file" accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx">
                                </div>

                                <hr>
                                <h5 class="mb-3">Kelola Video</h5>
                                <div class="mb-3">
                                    <?php if (!empty($sub_materi_data['file_path_video'])): ?>
                                        <p class="form-control-static">Video saat ini: <a href="<?= htmlspecialchars($sub_materi_data['file_path_video']) ?>" target="_blank"><?= htmlspecialchars(basename($sub_materi_data['file_path_video'])) ?></a></p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remove_video" name="remove_video" value="1">
                                            <label class="form-check-label" for="remove_video">
                                                Hapus video ini
                                            </label>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">Belum ada video untuk sub-materi ini.</p>
                                    <?php endif; ?>
                                    <label for="video_file" class="form-label mt-2">Ganti / Unggah Video Baru (MP4, WebM, OGG - Max 100MB)</label>
                                    <input class="form-control" type="file" id="video_file" name="video_file" accept="video/mp4,video/webm,video/ogg">
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="kelola-materi.php?id_kelas=<?= $id_kelas_for_redirect ?>&id_materi=<?= $id_materi_for_redirect ?>" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Perbarui Sub-Materi</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-danger" role="alert">
                                Sub-Materi tidak ditemukan atau tidak dapat diakses.
                            </div>
                            <a href="kelola-materi.php" class="btn btn-secondary">Kembali ke Kelola Materi</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>