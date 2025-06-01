<?php
session_start();
require 'db.php';

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

// Load existing sub-materi data
$current_sub_materi = null;
$current_dokumen = null;
$current_video_url = null;
$id_kelas_for_redirect = 0;
$id_materi_for_redirect = 0;

if ($id_sub_materi > 0) {
    $stmt_load = $conn->prepare("
        SELECT tsm.*, tm.judul_materi, tm.id_kelas, tk.id_mentor, tk.nama_kelas,
               td.file_path_dokumen, tv.file_path_video
        FROM tb_sub_materi tsm
        JOIN tb_materi tm ON tsm.id_materi = tm.id_materi
        JOIN tb_kelas tk ON tm.id_kelas = tk.id_kelas
        LEFT JOIN tb_dokumen td ON tsm.id_dokumen = td.id_dokumen
        LEFT JOIN tb_video tv ON tsm.id_video = tv.id_video
        WHERE tsm.id_sub_materi = ? AND tk.id_mentor = ?
    ");
    $stmt_load->bind_param("ii", $id_sub_materi, $id_mentor);
    $stmt_load->execute();
    $load_result = $stmt_load->get_result();

    if ($load_result->num_rows === 0) {
        $message = "Sub-materi tidak ditemukan atau bukan milik kelas Anda.";
        $id_sub_materi = 0;
    } else {
        $current_sub_materi = $load_result->fetch_assoc();
        $id_kelas_for_redirect = $current_sub_materi['id_kelas'];
        $id_materi_for_redirect = $current_sub_materi['id_materi'];
        
        // Siapkan data untuk form
        if (!empty($current_sub_materi['file_path_video'])) {
            // Cek apakah ini URL atau file path
            if (strpos($current_sub_materi['file_path_video'], 'http') === 0) {
                $current_video_url = $current_sub_materi['file_path_video'];
            }
        }
    }
    $stmt_load->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $current_sub_materi) {
    $judul_sub_materi = trim($_POST['judul_sub_materi']);
    $urutan = trim($_POST['urutan']);
    $video_url = trim($_POST['video_url']);
    $hapus_dokumen = isset($_POST['hapus_dokumen']) ? true : false;
    $hapus_video = isset($_POST['hapus_video']) ? true : false;

    $new_id_dokumen = $current_sub_materi['id_dokumen']; // Keep existing by default
    $new_id_video = $current_sub_materi['id_video']; // Keep existing by default

    // --- Handle Dokumen ---
    if ($hapus_dokumen && $current_sub_materi['id_dokumen']) {
        // Set to NULL instead of deleting from tb_dokumen to avoid foreign key issues
        $new_id_dokumen = null;
    }

    // Upload dokumen baru jika ada
    if (empty($message) && isset($_FILES['dokumen_file']) && $_FILES['dokumen_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name_d = $_FILES['dokumen_file']['tmp_name'];
        $file_name_d = basename($_FILES['dokumen_file']['name']);
        $file_size_d = $_FILES['dokumen_file']['size'];
        $file_type_d = $_FILES['dokumen_file']['type'];

        $upload_dir_d = '../uploads/dokumen/';
        $file_path_dokumen = $upload_dir_d . uniqid() . '_' . $file_name_d;

        $allowed_types_d = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
        if (!in_array($file_type_d, $allowed_types_d)) {
            $message = "Tipe file dokumen tidak diizinkan. Hanya PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.";
        } elseif ($file_size_d > 20 * 1024 * 1024) {
            $message = "Ukuran file dokumen terlalu besar. Maksimal 20MB.";
        } else {
            if (move_uploaded_file($file_tmp_name_d, $file_path_dokumen)) {
                // Simpan dokumen baru ke database
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
    } elseif (isset($_FILES['dokumen_file']) && $_FILES['dokumen_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $message = "Kesalahan upload dokumen: " . $_FILES['dokumen_file']['error'];
    }

    // --- Handle Video URL ---
    if (empty($message)) {
        if ($hapus_video) {
            // Set to NULL instead of deleting from tb_video
            $new_id_video = null;
        } elseif (!empty($video_url)) {
            // Process video URL
            $video_embed_url = processVideoUrl($video_url);
            if ($video_embed_url === null) {
                $message = "URL video tidak valid. Gunakan link dari YouTube, Vimeo, atau Google Drive.";
            } else {
                // Simpan URL embed ke tb_video
                $insert_video_stmt = $conn->prepare("INSERT INTO tb_video (file_path_video) VALUES (?)");
                $insert_video_stmt->bind_param("s", $video_embed_url);
                if ($insert_video_stmt->execute()) {
                    $new_id_video = $insert_video_stmt->insert_id;
                } else {
                    $message = "Gagal menyimpan info video ke database: " . $insert_video_stmt->error;
                }
                $insert_video_stmt->close();
            }
        }
    }

    // --- Update Sub-Materi ---
    if (empty($message)) {
        // Validasi input dasar
        if (empty($judul_sub_materi)) {
            $message = "Judul Sub-Materi wajib diisi!";
        } elseif (!is_numeric($urutan) || $urutan < 1) {
            $message = "Urutan harus berupa angka positif.";
        } else {
            // Update sub-materi
            $update_stmt = $conn->prepare("UPDATE tb_sub_materi SET judul_sub_materi = ?, id_dokumen = ?, id_video = ?, urutan = ? WHERE id_sub_materi = ?");
            $update_stmt->bind_param("siiii", $judul_sub_materi, $new_id_dokumen, $new_id_video, $urutan, $id_sub_materi);

            if ($update_stmt->execute()) {
                // Optional: Clean up old unused records (be careful with this)
                // Only delete if no other sub_materi references them
                if ($new_id_dokumen != $current_sub_materi['id_dokumen'] && $current_sub_materi['id_dokumen']) {
                    // Check if old dokumen is used elsewhere before deleting
                    $check_doc = $conn->prepare("SELECT COUNT(*) as count FROM tb_sub_materi WHERE id_dokumen = ?");
                    $check_doc->bind_param("i", $current_sub_materi['id_dokumen']);
                    $check_doc->execute();
                    $doc_result = $check_doc->get_result()->fetch_assoc();
                    if ($doc_result['count'] == 0) {
                        // Safe to delete old document
                        if (!empty($current_sub_materi['file_path_dokumen']) && file_exists($current_sub_materi['file_path_dokumen'])) {
                            unlink($current_sub_materi['file_path_dokumen']);
                        }
                        $delete_doc = $conn->prepare("DELETE FROM tb_dokumen WHERE id_dokumen = ?");
                        $delete_doc->bind_param("i", $current_sub_materi['id_dokumen']);
                        $delete_doc->execute();
                        $delete_doc->close();
                    }
                    $check_doc->close();
                }

                if ($new_id_video != $current_sub_materi['id_video'] && $current_sub_materi['id_video']) {
                    // Check if old video is used elsewhere before deleting
                    $check_vid = $conn->prepare("SELECT COUNT(*) as count FROM tb_sub_materi WHERE id_video = ?");
                    $check_vid->bind_param("i", $current_sub_materi['id_video']);
                    $check_vid->execute();
                    $vid_result = $check_vid->get_result()->fetch_assoc();
                    if ($vid_result['count'] == 0) {
                        // Safe to delete old video record
                        $delete_vid = $conn->prepare("DELETE FROM tb_video WHERE id_video = ?");
                        $delete_vid->bind_param("i", $current_sub_materi['id_video']);
                        $delete_vid->execute();
                        $delete_vid->close();
                    }
                    $check_vid->close();
                }

                header("Location: kelola-materi.php?id_kelas=" . $id_kelas_for_redirect . "&id_materi=" . $id_materi_for_redirect . "&msg=" . urlencode("Sub-materi '{$judul_sub_materi}' berhasil diperbarui!"));
                exit();
            } else {
                $message = "Gagal memperbarui sub-materi: " . $update_stmt->error;
            }
            $update_stmt->close();
        }
    }
}

// Extract original video URL if it's a URL (not file path)
$original_video_url = '';
if ($current_video_url && strpos($current_video_url, 'http') === 0) {
    // Try to convert embed URL back to normal URL for editing
    if (strpos($current_video_url, 'youtube.com/embed/') !== false) {
        $video_id = str_replace('https://www.youtube.com/embed/', '', $current_video_url);
        $original_video_url = 'https://www.youtube.com/watch?v=' . $video_id;
    } elseif (strpos($current_video_url, 'player.vimeo.com/video/') !== false) {
        $video_id = str_replace('https://player.vimeo.com/video/', '', $current_video_url);
        $original_video_url = 'https://vimeo.com/' . $video_id;
    } else {
        $original_video_url = $current_video_url;
    }
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
                        <?php if ($current_sub_materi): ?>
                            <small class="text-muted">
                                Materi: <?= htmlspecialchars($current_sub_materi['judul_materi']) ?> 
                                (Kelas: <?= htmlspecialchars($current_sub_materi['nama_kelas']) ?>)
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!$current_sub_materi): ?>
                            <div class="alert alert-danger">
                                Sub-materi tidak ditemukan atau Anda tidak memiliki akses untuk mengeditnya.
                                <br><a href="kelola-materi.php" class="btn btn-secondary mt-2">Kembali ke Kelola Materi</a>
                            </div>
                        <?php else: ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="judul_sub_materi" class="form-label">Judul Sub-Materi</label>
                                <input type="text" class="form-control" id="judul_sub_materi" name="judul_sub_materi" 
                                       value="<?= htmlspecialchars($current_sub_materi['judul_sub_materi']) ?>" required>
                            </div>

                            <hr>
                            <h5>Dokumen</h5>
                            <?php if ($current_sub_materi['file_path_dokumen']): ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-file-earmark"></i> Dokumen saat ini: 
                                    <strong><?= htmlspecialchars(basename($current_sub_materi['file_path_dokumen'])) ?></strong>
                                    <br>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="hapus_dokumen" name="hapus_dokumen">
                                        <label class="form-check-label text-danger" for="hapus_dokumen">
                                            Hapus dokumen yang ada
                                        </label>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada dokumen</p>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="dokumen_file" class="form-label">Upload Dokumen Baru (Opsional)</label>
                                <input type="file" class="form-control" id="dokumen_file" name="dokumen_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                <div class="form-text">Maksimal 20MB. Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.</div>
                            </div>

                            <hr>
                            <h5>Video</h5>
                            <?php if ($current_video_url): ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-play-circle"></i> Video saat ini: 
                                    <div class="ratio ratio-16x9 mt-2">
                                        <iframe src="<?= htmlspecialchars($current_video_url) ?>" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="hapus_video" name="hapus_video">
                                        <label class="form-check-label text-danger" for="hapus_video">
                                            Hapus video yang ada
                                        </label>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada video</p>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="video_url" class="form-label">Link Video Baru (Opsional)</label>
                                <input type="url" class="form-control" id="video_url" name="video_url" 
                                       value="<?= htmlspecialchars($original_video_url) ?>"
                                       placeholder="https://www.youtube.com/watch?v=...">
                                <div class="form-text">
                                    <strong>Platform yang didukung:</strong><br>
                                    • YouTube: https://www.youtube.com/watch?v=... atau https://youtu.be/...<br>
                                    • Vimeo: https://vimeo.com/...<br>
                                    • Google Drive: https://drive.google.com/file/d/.../view
                                </div>
                            </div>
                            
                            <!-- Preview Video -->
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
                                       value="<?= htmlspecialchars($current_sub_materi['urutan']) ?>" min="1" required>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="kelola-materi.php?id_kelas=<?= $id_kelas_for_redirect ?>&id_materi=<?= $id_materi_for_redirect ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Sub-Materi</button>
                            </div>
                        </form>

                        <?php endif; ?>
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

        // Trigger preview on page load if there's a URL
        window.addEventListener('load', function() {
            const videoUrlInput = document.getElementById('video_url');
            if (videoUrlInput.value) {
                videoUrlInput.dispatchEvent(new Event('input'));
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

        // Handle checkbox interactions
        document.getElementById('hapus_video')?.addEventListener('change', function() {
            const videoUrlInput = document.getElementById('video_url');
            const previewDiv = document.getElementById('video-preview');
            
            if (this.checked) {
                videoUrlInput.value = '';
                previewDiv.style.display = 'none';
                videoUrlInput.disabled = true;
            } else {
                videoUrlInput.disabled = false;
            }
        });
    </script>
</body>
</html>