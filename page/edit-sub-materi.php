<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";
$id_sub_materi = $_GET['id_sub_materi'] ?? 0;

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

// Ambil data sub-materi yang akan diedit
$sub_materi_data = null;
$current_video_url = "";
$current_dokumen_path = "";

if ($id_sub_materi > 0) {
    $stmt_sub_materi = $conn->prepare("
        SELECT tsm.*, tm.judul_materi, tk.nama_kelas, tk.id_kelas,
               td.file_path_dokumen, tv.file_path_video
        FROM tb_sub_materi tsm
        JOIN tb_materi tm ON tsm.id_materi = tm.id_materi
        JOIN tb_kelas tk ON tm.id_kelas = tk.id_kelas
        LEFT JOIN tb_dokumen td ON tsm.id_dokumen = td.id_dokumen
        LEFT JOIN tb_video tv ON tsm.id_video = tv.id_video
        WHERE tsm.id_sub_materi = ? AND tk.id_mentor = ?
    ");
    $stmt_sub_materi->bind_param("ii", $id_sub_materi, $id_mentor);
    $stmt_sub_materi->execute();
    $sub_materi_result = $stmt_sub_materi->get_result();

    if ($sub_materi_result->num_rows === 0) {
        $message = "Sub-materi tidak ditemukan atau bukan milik kelas Anda.";
        $id_sub_materi = 0;
    } else {
        $sub_materi_data = $sub_materi_result->fetch_assoc();
        $current_video_url = $sub_materi_data['file_path_video'] ?? "";
        $current_dokumen_path = $sub_materi_data['file_path_dokumen'] ?? "";
    }
    $stmt_sub_materi->close();
}

// Function to validate and convert video URL to embed format
function processVideoUrl($url) {
    if (empty($url)) {
        return null;
    }
    
    $url = trim($url);
    
    // YouTube URL patterns
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }
    
    // Vimeo URL patterns  
    if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
        return 'https://player.vimeo.com/video/' . $matches[1];
    }
    
    // Google Drive sharing link
    if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
    }
    
    // If already an embed URL or other video platform, return as is
    if (strpos($url, 'embed') !== false || strpos($url, 'player') !== false) {
        return $url;
    }
    
    return null;
}

// Function to convert embed URL back to original URL for display
function getOriginalVideoUrl($embedUrl) {
    if (empty($embedUrl)) return "";
    
    // YouTube embed to original
    if (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $embedUrl, $matches)) {
        return 'https://www.youtube.com/watch?v=' . $matches[1];
    }
    
    // Vimeo embed to original
    if (preg_match('/player\.vimeo\.com\/video\/(\d+)/', $embedUrl, $matches)) {
        return 'https://vimeo.com/' . $matches[1];
    }
    
    // Google Drive preview to original
    if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)\/preview/', $embedUrl, $matches)) {
        return 'https://drive.google.com/file/d/' . $matches[1] . '/view';
    }
    
    return $embedUrl;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $sub_materi_data) {
    $judul_sub_materi = trim($_POST['judul_sub_materi']);
    $urutan = trim($_POST['urutan']);
    $video_url = trim($_POST['video_url']);
    $hapus_dokumen = isset($_POST['hapus_dokumen']) ? 1 : 0;
    $hapus_video = isset($_POST['hapus_video']) ? 1 : 0;

    $new_id_dokumen = $sub_materi_data['id_dokumen']; // Default ke yang lama
    $new_id_video = $sub_materi_data['id_video']; // Default ke yang lama

    // --- Proses Hapus Dokumen ---
    if ($hapus_dokumen && $sub_materi_data['id_dokumen']) {
        // Hapus file fisik
        if (!empty($current_dokumen_path) && file_exists($current_dokumen_path)) {
            unlink($current_dokumen_path);
        }
        // Hapus record dari database
        $delete_doc_stmt = $conn->prepare("DELETE FROM tb_dokumen WHERE id_dokumen = ?");
        $delete_doc_stmt->bind_param("i", $sub_materi_data['id_dokumen']);
        $delete_doc_stmt->execute();
        $delete_doc_stmt->close();
        $new_id_dokumen = null;
    }

    // --- Proses Hapus Video ---
    if ($hapus_video && $sub_materi_data['id_video']) {
        // Hapus record dari database (tidak perlu hapus file karena ini URL)
        $delete_video_stmt = $conn->prepare("DELETE FROM tb_video WHERE id_video = ?");
        $delete_video_stmt->bind_param("i", $sub_materi_data['id_video']);
        $delete_video_stmt->execute();
        $delete_video_stmt->close();
        $new_id_video = null;
    }

    // --- Proses Upload Dokumen Baru ---
    if (empty($message) && isset($_FILES['dokumen_file']) && $_FILES['dokumen_file']['error'] === UPLOAD_ERR_OK) {
        // Hapus dokumen lama jika ada
        if ($sub_materi_data['id_dokumen'] && !$hapus_dokumen) {
            if (!empty($current_dokumen_path) && file_exists($current_dokumen_path)) {
                unlink($current_dokumen_path);
            }
            $delete_old_doc_stmt = $conn->prepare("DELETE FROM tb_dokumen WHERE id_dokumen = ?");
            $delete_old_doc_stmt->bind_param("i", $sub_materi_data['id_dokumen']);
            $delete_old_doc_stmt->execute();
            $delete_old_doc_stmt->close();
        }

        $file_tmp_name_d = $_FILES['dokumen_file']['tmp_name'];
        $file_name_d = basename($_FILES['dokumen_file']['name']);
        $file_size_d = $_FILES['dokumen_file']['size'];
        $file_type_d = $_FILES['dokumen_file']['type'];

        $upload_dir_d = '../uploads/dokumen/';
        $file_path_dokumen = $upload_dir_d . uniqid() . '_' . $file_name_d;

        $allowed_types_d = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
        if (!in_array($file_type_d, $allowed_types_d)) {
            $message = "Tipe file dokumen tidak diizinkan. Hanya PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.";
        } elseif ($file_size_d > 20 * 1024 * 1024) { // Batas 20MB
            $message = "Ukuran file dokumen terlalu besar. Maksimal 20MB.";
        } else {
            if (move_uploaded_file($file_tmp_name_d, $file_path_dokumen)) {
                $insert_doc_stmt = $conn->prepare("INSERT INTO tb_dokumen (file_path_dokumen) VALUES (?)");
                $insert_doc_stmt->bind_param("s", $file_path_dokumen);
                if ($insert_doc_stmt->execute()) {
                    $new_id_dokumen = $insert_doc_stmt->insert_id;
                } else {
                    $message = "Gagal menyimpan info dokumen ke database: " . $insert_doc_stmt->error;
                    unlink($file_path_dokumen);
                }
                $insert_doc_stmt->close();
            } else {
                $message = "Gagal memindahkan file dokumen yang diunggah.";
            }
        }
    }

    // --- Proses Video URL Baru ---
    if (empty($message) && !empty($video_url)) {
        $video_embed_url = processVideoUrl($video_url);
        if ($video_embed_url === null) {
            $message = "URL video tidak valid. Gunakan link dari YouTube, Vimeo, atau Google Drive.";
        } else {
            // Hapus video lama jika ada
            if ($sub_materi_data['id_video'] && !$hapus_video) {
                $delete_old_video_stmt = $conn->prepare("DELETE FROM tb_video WHERE id_video = ?");
                $delete_old_video_stmt->bind_param("i", $sub_materi_data['id_video']);
                $delete_old_video_stmt->execute();
                $delete_old_video_stmt->close();
            }

            // Simpan URL embed baru
            $insert_video_stmt = $conn->prepare("INSERT INTO tb_video (file_path_video) VALUES (?)");
            $insert_video_stmt->bind_param("s", $video_embed_url);
            if ($insert_video_stmt->execute()) {
                $new_id_video = $insert_video_stmt->insert_id;
            } else {
                $message = "Gagal menyimpan info video ke database: " . $insert_video_stmt->error;
            }
            $insert_video_stmt->close();
        }
    } elseif (empty($video_url) && !$hapus_video) {
        // Jika video URL dikosongkan tapi tidak dicentang hapus, maka hapus video
        if ($sub_materi_data['id_video']) {
            $delete_video_stmt = $conn->prepare("DELETE FROM tb_video WHERE id_video = ?");
            $delete_video_stmt->bind_param("i", $sub_materi_data['id_video']);
            $delete_video_stmt->execute();
            $delete_video_stmt->close();
            $new_id_video = null;
        }
    }

    // --- Update Sub-Materi ---
    if (empty($message)) {
        if (empty($judul_sub_materi)) {
            $message = "Judul Sub-Materi wajib diisi!";
        } elseif (!is_numeric($urutan) || $urutan < 1) {
            $message = "Urutan harus berupa angka positif.";
        } else {
            $update_stmt = $conn->prepare("UPDATE tb_sub_materi SET judul_sub_materi = ?, id_dokumen = ?, id_video = ?, urutan = ? WHERE id_sub_materi = ?");
            $update_stmt->bind_param("siiii", $judul_sub_materi, $new_id_dokumen, $new_id_video, $urutan, $id_sub_materi);

            if ($update_stmt->execute()) {
                header("Location: kelola-materi.php?id_kelas=" . $sub_materi_data['id_kelas'] . "&id_materi=" . $sub_materi_data['id_materi'] . "&msg=" . urlencode("Sub-materi '{$judul_sub_materi}' berhasil diupdate!"));
                exit();
            } else {
                $message = "Gagal mengupdate sub-materi: " . $update_stmt->error;
            }
            $update_stmt->close();
        }
    }
}

if (!$sub_materi_data) {
    header("Location: kelola-materi.php");
    exit();
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
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Sub-Materi</h4>
                        <small class="text-muted">Materi: <?= htmlspecialchars($sub_materi_data['judul_materi']) ?> | Kelas: <?= htmlspecialchars($sub_materi_data['nama_kelas']) ?></small>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="judul_sub_materi" class="form-label">Judul Sub-Materi</label>
                                <input type="text" class="form-control" id="judul_sub_materi" name="judul_sub_materi" 
                                       value="<?= htmlspecialchars($sub_materi_data['judul_sub_materi']) ?>" required>
                            </div>

                            <hr>
                            <h5>Dokumen</h5>
                            <?php if (!empty($current_dokumen_path)): ?>
                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="bi bi-file-earmark-text"></i> 
                                        Dokumen saat ini: <strong><?= basename($current_dokumen_path) ?></strong>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="hapus_dokumen" name="hapus_dokumen">
                                            <label class="form-check-label text-danger" for="hapus_dokumen">
                                                Hapus dokumen ini
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada dokumen yang tersimpan.</p>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="dokumen_file" class="form-label">Upload Dokumen Baru (Opsional)</label>
                                <input type="file" class="form-control" id="dokumen_file" name="dokumen_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                <div class="form-text">
                                    Maksimal 20MB. Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.<br>
                                    <strong>Catatan:</strong> Upload dokumen baru akan mengganti dokumen yang ada.
                                </div>
                            </div>

                            <hr>
                            <h5>Video</h5>
                            <?php if (!empty($current_video_url)): ?>
                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="bi bi-camera-video"></i> 
                                        Video saat ini: <a href="<?= htmlspecialchars(getOriginalVideoUrl($current_video_url)) ?>" target="_blank" class="fw-bold">Lihat Video</a>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="hapus_video" name="hapus_video">
                                            <label class="form-check-label text-danger" for="hapus_video">
                                                Hapus video ini
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Preview Video Saat Ini -->
                                    <div class="mb-3">
                                        <label class="form-label">Preview Video Saat Ini:</label>
                                        <div class="ratio ratio-16x9">
                                            <iframe src="<?= htmlspecialchars($current_video_url) ?>" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada video yang tersimpan.</p>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="video_url" class="form-label">Link Video Baru (Opsional)</label>
                                <input type="url" class="form-control" id="video_url" name="video_url" 
                                       value="<?= htmlspecialchars(getOriginalVideoUrl($current_video_url)) ?>" 
                                       placeholder="https://www.youtube.com/watch?v=...">
                                <div class="form-text">
                                    <strong>Platform yang didukung:</strong><br>
                                    • YouTube: https://www.youtube.com/watch?v=... atau https://youtu.be/...<br>
                                    • Vimeo: https://vimeo.com/...<br>
                                    • Google Drive: https://drive.google.com/file/d/.../view<br>
                                    <strong>Catatan:</strong> Kosongkan field ini jika ingin menghapus video.
                                </div>
                            </div>
                            
                            <!-- Preview Video Baru -->
                            <div class="mb-3" id="video-preview" style="display: none;">
                                <label class="form-label">Preview Video Baru:</label>
                                <div class="ratio ratio-16x9">
                                    <iframe id="preview-iframe" src="" frameborder="0" allowfullscreen></iframe>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label for="urutan" class="form-label">Urutan</label>
                                <input type="number" class="form-control" id="urutan" name="urutan" 
                                       value="<?= htmlspecialchars($sub_materi_data['urutan']) ?>" min="1" required>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="kelola-materi.php?id_kelas=<?= $sub_materi_data['id_kelas'] ?>&id_materi=<?= $sub_materi_data['id_materi'] ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Sub-Materi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Video URL preview functionality
        document.getElementById('video_url').addEventListener('input', function() {
            const url = this.value.trim();
            const previewDiv = document.getElementById('video-preview');
            const iframe = document.getElementById('preview-iframe');
            
            if (url) {
                let embedUrl = processVideoUrlJS(url);
                if (embedUrl) {
                    iframe.src = embedUrl;
                    previewDiv.style.display = 'block';
                } else {
                    previewDiv.style.display = 'none';
                }
            } else {
                previewDiv.style.display = 'none';
            }
        });

        function processVideoUrlJS(url) {
            // YouTube patterns
            let match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
            if (match) {
                return 'https://www.youtube.com/embed/' + match[1];
            }
            
            // Vimeo patterns
            match = url.match(/vimeo\.com\/(\d+)/);
            if (match) {
                return 'https://player.vimeo.com/video/' + match[1];
            }
            
            // Google Drive patterns
            match = url.match(/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/);
            if (match) {
                return 'https://drive.google.com/file/d/' + match[1] + '/preview';
            }
            
            // If already embed URL
            if (url.includes('embed') || url.includes('player')) {
                return url;
            }
            
            return null;
        }

        // Auto-hide checkboxes when new files are selected
        document.getElementById('dokumen_file').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('hapus_dokumen').checked = false;
            }
        });

        document.getElementById('video_url').addEventListener('input', function() {
            if (this.value.trim()) {
                document.getElementById('hapus_video').checked = false;
            }
        });
    </script>
</body>
</html>