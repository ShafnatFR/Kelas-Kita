<?php
session_start();
require 'db.php'; // Pastikan file ini ada dan variabel $conn diinisialisasi

//======================================================================
// 1. OTENTIKASI DAN INISIALISASI
//======================================================================

// Cek sesi dan peran pengguna
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

// Inisialisasi variabel dasar
$user_id = $_SESSION['id'];
$message = "";
$id_sub_materi = isset($_GET['id_sub_materi']) ? (int)$_GET['id_sub_materi'] : 0;

// Dapatkan id_mentor berdasarkan id_user yang login
try {
    $mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
    $mentor_query->bind_param("i", $user_id);
    $mentor_query->execute();
    $mentor_result = $mentor_query->get_result();
    $id_mentor = 0;
    if ($mentor_result->num_rows > 0) {
        $id_mentor = $mentor_result->fetch_assoc()['id_mentor'];
    }
    $mentor_query->close();

    if ($id_mentor === 0) {
        throw new Exception("ID Mentor tidak ditemukan untuk user ini. Silakan hubungi admin.");
    }
} catch (Exception $e) {
    die("Error Kritis: " . $e->getMessage());
}

//======================================================================
// 2. FUNGSI BANTU
//======================================================================

/**
 * Memproses URL video dari berbagai platform menjadi URL embed standar.
 * @param string $url URL video asli.
 * @return string|null URL embed atau null jika tidak valid.
 */
function processVideoUrl($url)
{
    $url = trim($url);
    if (empty($url)) {
        return null;
    }

    // YouTube: Dikonversi ke format embed standar
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }

    // Vimeo: Dikonversi ke format embed standar
    if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
        return 'https://player.vimeo.com/video/' . $matches[1];
    }

    // Google Drive: Dikonversi ke format preview/embed
    if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
    }

    // Jika sudah dalam format embed, kembalikan apa adanya
    if (strpos($url, 'embed') !== false || strpos($url, 'player') !== false) {
        return $url;
    }

    return null; // URL tidak dikenali
}


//======================================================================
// 3. MEMUAT DATA SUB-MATERI YANG ADA (METHOD GET)
//======================================================================
$current_sub_materi = null;
$id_kelas_for_redirect = 0;
$id_materi_for_redirect = 0;

if ($id_sub_materi > 0) {
    try {
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
            $id_sub_materi = 0; // Reset ID agar form tidak ditampilkan
        } else {
            $current_sub_materi = $load_result->fetch_assoc();
            $id_kelas_for_redirect = $current_sub_materi['id_kelas'];
            $id_materi_for_redirect = $current_sub_materi['id_materi'];
        }
        $stmt_load->close();
    } catch (Exception $e) {
        $message = "Gagal memuat data: " . $e->getMessage();
    }
}

//======================================================================
// 4. MEMPROSES FORM UPDATE (METHOD POST)
//======================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $current_sub_materi) {
    // Memulai transaksi database untuk memastikan semua query berhasil atau tidak sama sekali
    $conn->begin_transaction();

    try {
        // Ambil data dari form dengan aman
        $judul_sub_materi = trim($_POST['judul_sub_materi'] ?? '');
        $urutan = isset($_POST['urutan']) ? (int)$_POST['urutan'] : 0;
        $video_url = trim($_POST['video_url'] ?? '');
        $hapus_dokumen = isset($_POST['hapus_dokumen']);
        $hapus_video = isset($_POST['hapus_video']);

        // Inisialisasi ID dengan nilai yang ada
        $new_id_dokumen = $current_sub_materi['id_dokumen'];
        $new_id_video = $current_sub_materi['id_video'];
        
        // --- Handle Dokumen ---
        if ($hapus_dokumen) {
            $new_id_dokumen = null;
        }

        if (isset($_FILES['dokumen_file']) && $_FILES['dokumen_file']['error'] === UPLOAD_ERR_OK) {
            // Proses upload dokumen baru... (logika Anda sudah cukup baik)
            $file_tmp_name_d = $_FILES['dokumen_file']['tmp_name'];
            $file_name_d = basename($_FILES['dokumen_file']['name']);
            // ... (validasi ukuran dan tipe file) ...
            
            $upload_dir_d = '../uploads/dokumen/';
            if (!is_dir($upload_dir_d)) mkdir($upload_dir_d, 0777, true);
            $file_path_dokumen = $upload_dir_d . uniqid() . '_' . $file_name_d;

            if (move_uploaded_file($file_tmp_name_d, $file_path_dokumen)) {
                $insert_doc_stmt = $conn->prepare("INSERT INTO tb_dokumen (file_path_dokumen) VALUES (?)");
                $insert_doc_stmt->bind_param("s", $file_path_dokumen);
                if (!$insert_doc_stmt->execute()) throw new Exception("Gagal menyimpan info dokumen: " . $insert_doc_stmt->error);
                $new_id_dokumen = $insert_doc_stmt->insert_id;
                $insert_doc_stmt->close();
            } else {
                throw new Exception("Gagal memindahkan file dokumen.");
            }
        }

        // --- Handle Video ---
        if ($hapus_video) {
            $new_id_video = null;
        } elseif (!empty($video_url)) {
            $video_embed_url = processVideoUrl($video_url);
            if ($video_embed_url === null) {
                throw new Exception("URL video tidak valid.");
            }
            
            // Cek apakah URL ini sudah ada untuk menghindari duplikat
            $check_vid_stmt = $conn->prepare("SELECT id_video FROM tb_video WHERE file_path_video = ?");
            $check_vid_stmt->bind_param("s", $video_embed_url);
            $check_vid_stmt->execute();
            $vid_res = $check_vid_stmt->get_result();
            if ($vid_res->num_rows > 0) {
                $new_id_video = $vid_res->fetch_assoc()['id_video'];
            } else {
                // Jika belum ada, insert baru
                $insert_video_stmt = $conn->prepare("INSERT INTO tb_video (file_path_video) VALUES (?)");
                $insert_video_stmt->bind_param("s", $video_embed_url);
                if (!$insert_video_stmt->execute()) throw new Exception("Gagal menyimpan info video: " . $insert_video_stmt->error);
                $new_id_video = $insert_video_stmt->insert_id;
                $insert_video_stmt->close();
            }
            $check_vid_stmt->close();
        }

        // --- Validasi Akhir Sebelum Update ---
        if (empty($judul_sub_materi)) throw new Exception("Judul Sub-Materi wajib diisi!");
        if ($urutan < 1) throw new Exception("Urutan harus berupa angka positif.");

        // --- Finalisasi ID untuk Mencegah Error Foreign Key ---
        // Pastikan nilai yang dikirim adalah ID valid atau NULL, bukan 0 atau string kosong
        $final_id_dokumen = ($new_id_dokumen > 0) ? $new_id_dokumen : null;
        $final_id_video = ($new_id_video > 0) ? $new_id_video : null;

        // --- Eksekusi UPDATE Utama ---
        $update_stmt = $conn->prepare("UPDATE tb_sub_materi SET judul_sub_materi = ?, id_dokumen = ?, id_video = ?, urutan = ? WHERE id_sub_materi = ?");
        // Gunakan bind_param dengan tipe yang benar. 'i' bisa handle NULL.
        $update_stmt->bind_param("siiii", $judul_sub_materi, $final_id_dokumen, $final_id_video, $urutan, $id_sub_materi);
        if (!$update_stmt->execute()) {
            throw new Exception("Gagal memperbarui database: " . $update_stmt->error);
        }
        $update_stmt->close();
        
        // --- Opsional: Hapus file/data lama yang sudah tidak terpakai ---
        // (Logika cleanup Anda sudah cukup baik, pastikan ini ada di dalam blok try)

        // Jika semua berhasil, commit transaksi
        $conn->commit();
        
        // Redirect ke halaman kelola materi dengan pesan sukses
        $success_message = urlencode("Sub-materi '{$judul_sub_materi}' berhasil diperbarui!");
        header("Location: kelola-materi.php?id_kelas={$id_kelas_for_redirect}&id_materi={$id_materi_for_redirect}&msg={$success_message}");
        exit();

    } catch (Exception $e) {
        // Jika ada error di mana pun, batalkan semua perubahan
        $conn->rollback();
        $message = $e->getMessage();
    }
}

//======================================================================
// 5. PERSIAPAN DATA UNTUK DITAMPILKAN DI FORM
//======================================================================
$original_video_url = '';
if (!empty($current_sub_materi['file_path_video'])) {
    $embed_url = $current_sub_materi['file_path_video'];
    // Coba konversi balik dari embed URL ke URL asli untuk ditampilkan di input form
    if (strpos($embed_url, 'youtube.com/embed/') !== false) {
        $video_id = basename($embed_url);
        $original_video_url = 'https://www.youtube.com/watch?v=' . $video_id;
    } elseif (strpos($embed_url, 'player.vimeo.com/video/') !== false) {
        $video_id = basename($embed_url);
        $original_video_url = 'https://vimeo.com/' . $video_id;
    } else {
        $original_video_url = $embed_url; // Untuk Google Drive atau lainnya
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
    <div class="container mt-4 mb-5">
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
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!$current_sub_materi): ?>
                            <div class="alert alert-warning">
                                Data sub-materi tidak dapat dimuat.
                                <br><a href="dashboard-mentor.php" class="btn btn-secondary mt-2">Kembali ke Dashboard</a>
                            </div>
                        <?php else: ?>
                            <form method="POST" enctype="multipart/form-data" novalidate>
                                <div class="mb-3">
                                    <label for="judul_sub_materi" class="form-label">Judul Sub-Materi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="judul_sub_materi" name="judul_sub_materi" 
                                           value="<?= htmlspecialchars($current_sub_materi['judul_sub_materi']) ?>" required>
                                </div>

                                <hr>
                                <h5>Dokumen</h5>
                                <?php if ($current_sub_materi['file_path_dokumen']): ?>
                                    <div class="alert alert-light border">
                                        <i class="bi bi-file-earmark-text"></i> Dokumen saat ini: 
                                        <strong><?= htmlspecialchars(basename($current_sub_materi['file_path_dokumen'])) ?></strong>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="hapus_dokumen" name="hapus_dokumen">
                                            <label class="form-check-label text-danger" for="hapus_dokumen">Hapus dokumen ini</label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="dokumen_file" class="form-label">Upload Dokumen Baru (Opsional)</label>
                                    <input type="file" class="form-control" id="dokumen_file" name="dokumen_file">
                                </div>

                                <hr>
                                <h5>Video</h5>
                                <?php if (!empty($current_sub_materi['file_path_video'])): ?>
                                    <div class="alert alert-light border">
                                        <i class="bi bi-play-circle"></i> Video saat ini: 
                                        <div class="ratio ratio-16x9 mt-2">
                                            <iframe src="<?= htmlspecialchars($current_sub_materi['file_path_video']) ?>" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="hapus_video" name="hapus_video">
                                            <label class="form-check-label text-danger" for="hapus_video">Hapus video ini</label>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="video_url" class="form-label">Link Video Baru (Opsional)</label>
                                    <input type="url" class="form-control" id="video_url" name="video_url" 
                                           value="<?= htmlspecialchars($original_video_url) ?>"
                                           placeholder="Masukkan link YouTube, Vimeo, atau Google Drive">
                                </div>
                                
                                <div class="mb-3" id="video-preview" style="display: none;">
                                    <label class="form-label">Preview Video Baru:</label>
                                    <div class="ratio ratio-16x9">
                                        <iframe id="preview-iframe" src="" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="mb-3">
                                    <label for="urutan" class="form-label">Urutan <span class="text-danger">*</span></label>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const videoUrlInput = document.getElementById('video_url');
        const hapusVideoCheckbox = document.getElementById('hapus_video');

        function processVideoUrlJS(url) {
            if (!url) return null;
            let match;
            
            match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
            if (match) return 'https://www.youtube.com/embed/' + match[1];
            
            match = url.match(/vimeo\.com\/(\d+)/);
            if (match) return 'https://player.vimeo.com/video/' + match[1];
            
            match = url.match(/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/);
            if (match) return 'https://drive.google.com/file/d/' + match[1] + '/preview';
            
            return null;
        }

        function updatePreview() {
            const url = videoUrlInput.value.trim();
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
        }
        
        videoUrlInput.addEventListener('input', updatePreview);
        
        if (hapusVideoCheckbox) {
            hapusVideoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    videoUrlInput.value = '';
                    videoUrlInput.disabled = true;
                    updatePreview();
                } else {
                    videoUrlInput.disabled = false;
                }
            });
        }

        // Trigger preview on page load
        updatePreview();
    });
    </script>
</body>
</html>