<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";
$id_materi_preselected = $_GET['id_materi'] ?? 0;

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

$current_materi = null;
$id_kelas_of_materi = 0;
if ($id_materi_preselected > 0) {
    $stmt_materi = $conn->prepare("
        SELECT tm.id_materi, tm.judul_materi, tm.id_kelas, tk.id_mentor, tk.nama_kelas
        FROM tb_materi tm
        JOIN tb_kelas tk ON tm.id_kelas = tk.id_kelas
        WHERE tm.id_materi = ? AND tk.id_mentor = ?
    ");
    $stmt_materi->bind_param("ii", $id_materi_preselected, $id_mentor);
    $stmt_materi->execute();
    $materi_result = $stmt_materi->get_result();

    if ($materi_result->num_rows === 0) {
        $message = "Materi tidak ditemukan atau bukan milik kelas Anda.";
        $id_materi_preselected = 0;
    } else {
        $current_materi = $materi_result->fetch_assoc();
        $id_kelas_of_materi = $current_materi['id_kelas'];
    }
    $stmt_materi->close();
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_materi = trim($_POST['id_materi']);
    $judul_sub_materi = trim($_POST['judul_sub_materi']);
    $urutan = trim($_POST['urutan']);
    $video_url = trim($_POST['video_url']);

    $id_dokumen = null; // Default NULL
    $id_video = null; // Default NULL

    // --- Proses Video URL ---
    if (!empty($video_url)) {
        $video_embed_url = processVideoUrl($video_url);
        if ($video_embed_url === null) {
            $message = "URL video tidak valid. Gunakan link dari YouTube, Vimeo, atau Google Drive.";
        } else {
            // Simpan URL embed ke tb_video menggunakan kolom file_path_video
            $insert_video_stmt = $conn->prepare("INSERT INTO tb_video (file_path_video) VALUES (?)");
            $insert_video_stmt->bind_param("s", $video_embed_url);
            if ($insert_video_stmt->execute()) {
                $id_video = $insert_video_stmt->insert_id;
            } else {
                $message = "Gagal menyimpan info video ke database: " . $insert_video_stmt->error;
            }
            $insert_video_stmt->close();
        }
    }

    // --- Proses Upload Dokumen ---
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
        } elseif ($file_size_d > 20 * 1024 * 1024) { // Batas 20MB
            $message = "Ukuran file dokumen terlalu besar. Maksimal 20MB.";
        } else {
            if (move_uploaded_file($file_tmp_name_d, $file_path_dokumen)) {
                // Simpan info ke database
                $insert_doc_stmt = $conn->prepare("INSERT INTO tb_dokumen (file_path_dokumen) VALUES (?)");
                $insert_doc_stmt->bind_param("s", $file_path_dokumen);
                if ($insert_doc_stmt->execute()) {
                    $id_dokumen = $insert_doc_stmt->insert_id;
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

    // --- Validasi dan Insert Sub-Materi ---
    if (empty($message)) {
        // Validasi input dasar
        if (empty($id_materi) || empty($judul_sub_materi)) {
            $message = "Materi induk dan Judul Sub-Materi wajib diisi!";
        } elseif (!is_numeric($urutan) || $urutan < 1) {
            $message = "Urutan harus berupa angka positif.";
        } else {
            // Validasi tambahan: Pastikan id_materi benar-benar milik mentor yang login
            $check_materi_owner_stmt = $conn->prepare("
                SELECT tm.id_materi, tk.id_kelas
                FROM tb_materi tm
                JOIN tb_kelas tk ON tm.id_kelas = tk.id_kelas
                WHERE tm.id_materi = ? AND tk.id_mentor = ?
            ");
            $check_materi_owner_stmt->bind_param("ii", $id_materi, $id_mentor);
            $check_materi_owner_stmt->execute();
            $materi_owner_result = $check_materi_owner_stmt->get_result();

            if ($materi_owner_result->num_rows === 0) {
                $message = "Materi induk yang dipilih tidak valid atau bukan milik Anda.";
            } else {
                $materi_info_for_redirect = $materi_owner_result->fetch_assoc();
                $id_kelas_for_redirect = $materi_info_for_redirect['id_kelas'];

                if (empty($message)) {
                    // Masukkan data sub-materi ke database
                    $insert_stmt = $conn->prepare("INSERT INTO tb_sub_materi (id_materi, judul_sub_materi, id_dokumen, id_video, urutan) VALUES (?, ?, ?, ?, ?)");
                    
                    // Parameter: i (id_materi), s (judul_sub_materi), i (id_dokumen), i (id_video), i (urutan)
                    $insert_stmt->bind_param("isiii", $id_materi, $judul_sub_materi, $id_dokumen, $id_video, $urutan);

                    if ($insert_stmt->execute()) {
                        header("Location: kelola-materi.php?id_kelas=" . $id_kelas_for_redirect . "&id_materi=" . $id_materi . "&msg=" . urlencode("Sub-materi '{$judul_sub_materi}' berhasil ditambahkan!"));
                        exit();
                    } else {
                        $message = "Gagal menambahkan sub-materi: " . $insert_stmt->error;
                        // TODO: Jika gagal insert sub-materi, hapus file dan info dari tb_dokumen/tb_video jika sudah terupload
                    }
                    $insert_stmt->close();
                }

                if ($insert_stmt->execute()) {
                    header("Location: kelola-materi.php?id_kelas=" . $id_kelas_for_redirect . "&id_materi=" . $id_materi . "&msg=" . urlencode("Sub-materi '{$judul_sub_materi}' berhasil ditambahkan!"));
                    exit();
                } else {
                    $message = "Gagal menambahkan sub-materi: " . $insert_stmt->error;
                    // TODO: Jika gagal insert sub-materi, hapus file dan info dari tb_dokumen jika sudah terupload
                }
                $insert_stmt->close();
            }
            $check_materi_owner_stmt->close();
        }
    }
}

// Ambil daftar materi yang dimiliki mentor untuk dropdown (jika id_materi_preselected belum ada)
$materis = [];
if ($id_materi_preselected === 0 && $id_mentor > 0) {
    $stmt_materis = $conn->prepare("
        SELECT tm.id_materi, tm.judul_materi, tk.nama_kelas
        FROM tb_materi tm
        JOIN tb_kelas tk ON tm.id_kelas = tk.id_kelas
        WHERE tk.id_mentor = ?
        ORDER BY tk.nama_kelas ASC, tm.urutan ASC
    ");
    $stmt_materis->bind_param("i", $id_mentor);
    $stmt_materis->execute();
    $materis_result = $stmt_materis->get_result();
    while ($row = $materis_result->fetch_assoc()) {
        $materis[] = $row;
    }
    $stmt_materis->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Sub-Materi Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Sub-Materi Baru</h4>
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
                                <label for="id_materi" class="form-label">Pilih Materi Induk</label>
                                <select class="form-select" id="id_materi" name="id_materi" required
                                    <?= ($id_materi_preselected > 0) ? 'disabled' : '' ?>>
                                    <?php if ($id_materi_preselected > 0 && $current_materi): ?>
                                        <option value="<?= $current_materi['id_materi'] ?>" selected>
                                            <?= htmlspecialchars($current_materi['judul_materi']) ?> (Kelas: <?= htmlspecialchars($current_materi['nama_kelas']) ?>)
                                        </option>
                                    <?php else: ?>
                                        <option value="">-- Pilih Materi Induk --</option>
                                        <?php foreach ($materis as $materi): ?>
                                            <option value="<?= $materi['id_materi'] ?>">
                                                <?= htmlspecialchars($materi['judul_materi']) ?> (Kelas: <?= htmlspecialchars($materi['nama_kelas']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if ($id_materi_preselected > 0): ?>
                                    <input type="hidden" name="id_materi" value="<?= $id_materi_preselected ?>">
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="judul_sub_materi" class="form-label">Judul Sub-Materi</label>
                                <input type="text" class="form-control" id="judul_sub_materi" name="judul_sub_materi" required>
                            </div>

                            <hr>
                            <h5>Unggah Dokumen (Opsional)</h5>
                            <div class="mb-3">
                                <label for="dokumen_file" class="form-label">Pilih File Dokumen</label>
                                <input type="file" class="form-control" id="dokumen_file" name="dokumen_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                <div class="form-text">Maksimal 20MB. Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.</div>
                            </div>

                            <hr>
                            <h5>Tambahkan Video (Opsional)</h5>
                            <div class="mb-3">
                                <label for="video_url" class="form-label">Link Video</label>
                                <input type="url" class="form-control" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
                                <div class="form-text">
                                    <strong>Platform yang didukung:</strong><br>
                                    • YouTube: https://www.youtube.com/watch?v=... atau https://youtu.be/...<br>
                                    • Vimeo: https://vimeo.com/...<br>
                                    • Google Drive: https://drive.google.com/file/d/.../view
                                </div>
                            </div>
                            
                            <!-- Preview Video -->
                            <div class="mb-3" id="video-preview" style="display: none;">
                                <label class="form-label">Preview Video:</label>
                                <div class="ratio ratio-16x9">
                                    <iframe id="preview-iframe" src="" frameborder="0" allowfullscreen></iframe>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label for="urutan" class="form-label">Urutan</label>
                                <input type="number" class="form-control" id="urutan" name="urutan" value="1" min="1" required>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <?php 
                                $back_url = 'kelola-materi.php';
                                if ($id_materi_preselected > 0 && $current_materi) {
                                    $back_url .= '?id_kelas=' . $current_materi['id_kelas'] . '&id_materi=' . $id_materi_preselected;
                                }
                                ?>
                                <a href="<?= $back_url ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Tambah Sub-Materi</button>
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
    </script>
</body>
</html>