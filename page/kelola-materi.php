<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id']; // ID User dari sesi
$message = ""; // Untuk feedback pesan

// Dapatkan id_mentor berdasarkan id_user yang login
$mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
$mentor_query->bind_param("i", $user_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

$id_mentor = 0; // Default value
if ($mentor_result->num_rows > 0) {
    $mentor_row = $mentor_result->fetch_assoc();
    $id_mentor = $mentor_row['id_mentor'];
}
$mentor_query->close();

if ($id_mentor === 0) {
    // Jika ID mentor tidak ditemukan, kemungkinan ada masalah konfigurasi atau data
    $message = "Error: ID Mentor tidak ditemukan untuk user ini. Silakan hubungi admin.";
}

// Ambil pesan dari URL jika ada (misal setelah berhasil tambah/edit/hapus)
if (isset($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']));
}

// Logika tampilan berdasarkan level: Kelas -> Materi -> Sub-Materi
$view_level = 'kelas'; // Default view level
$selected_id_kelas = $_GET['id_kelas'] ?? 0;
$selected_id_materi = $_GET['id_materi'] ?? 0;

if ($selected_id_kelas > 0) {
    $view_level = 'materi';
    // Validasi kelas yang dipilih apakah milik mentor yang login
    $check_class_stmt = $conn->prepare("SELECT id_kelas, nama_kelas FROM tb_kelas WHERE id_kelas = ? AND id_mentor = ?");
    $check_class_stmt->bind_param("ii", $selected_id_kelas, $id_mentor);
    $check_class_stmt->execute();
    $class_result = $check_class_stmt->get_result();
    if ($class_result->num_rows === 0) {
        $message = "Kelas tidak ditemukan atau bukan milik Anda.";
        $view_level = 'kelas'; // Kembali ke tampilan daftar kelas
        $selected_id_kelas = 0;
    } else {
        $current_class = $class_result->fetch_assoc();
        if ($selected_id_materi > 0) {
            $view_level = 'sub_materi';
            // Validasi materi yang dipilih apakah milik kelas yang dipilih
            $check_materi_stmt = $conn->prepare("SELECT id_materi, judul_materi FROM tb_materi WHERE id_materi = ? AND id_kelas = ?");
            $check_materi_stmt->bind_param("ii", $selected_id_materi, $selected_id_kelas);
            $check_materi_stmt->execute();
            $materi_result = $check_materi_stmt->get_result();
            if ($materi_result->num_rows === 0) {
                $message = "Materi tidak ditemukan atau bukan bagian dari kelas ini.";
                $view_level = 'materi'; // Kembali ke tampilan daftar materi
                $selected_id_materi = 0;
            } else {
                $current_materi = $materi_result->fetch_assoc();
            }
            $check_materi_stmt->close();
        }
    }
    $check_class_stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .card-hover {
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #007bff;
        }
        .badge-count {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
        .card-body {
            position: relative;
            overflow: hidden;
        }
        .card-body::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, transparent 50%, rgba(0,123,255,0.1) 50%);
            transform: rotate(45deg) translate(50px, -50px);
        }
        .stats-row {
            background: rgba(0,123,255,0.05);
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'sidebar-mentor.php' ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-book-half text-primary me-2"></i>Kelola Materi
                </h3>
                <p class="text-muted mb-0">Kelola materi pembelajaran untuk kelas Anda</p>
            </div>
            <div>
                <?php if ($view_level === 'kelas'): ?>
                    <a href="kelola-kelas.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Kelola Kelas
                    </a>
                <?php elseif ($view_level === 'materi'): ?>
                    <a href="kelola-materi.php" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas
                    </a>
                    <a href="create-materi.php?id_kelas=<?= $selected_id_kelas ?>" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah Materi Baru
                    </a>
                <?php elseif ($view_level === 'sub_materi'): ?>
                    <a href="kelola-materi.php?id_kelas=<?= $selected_id_kelas ?>" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Kembali ke Materi
                    </a>
                    <a href="create-sub-materi.php?id_materi=<?= $selected_id_materi ?>" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah Sub-Materi Baru
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i><?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($view_level === 'kelas'): ?>
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-3">
                        <i class="bi bi-collection text-primary me-2"></i>Daftar Kelas Anda
                    </h4>
                    <p class="text-muted mb-4">Pilih kelas untuk mengelola materinya</p>
                </div>
            </div>
            
            <div class="row">
                <?php
                $stmt_classes = $conn->prepare("
                    SELECT k.id_kelas, k.nama_kelas, k.kategori,
                           COUNT(m.id_materi) as jumlah_materi
                    FROM tb_kelas k
                    JOIN tb_mentor mt ON k.id_mentor = mt.id_mentor
                    LEFT JOIN tb_materi m ON k.id_kelas = m.id_kelas
                    WHERE mt.id_user = ?
                    GROUP BY k.id_kelas, k.nama_kelas, k.kategori
                    ORDER BY k.id_kelas DESC
                ");
                $stmt_classes->bind_param("i", $user_id);
                $stmt_classes->execute();
                $classes_result = $stmt_classes->get_result();

                if ($classes_result->num_rows > 0) {
                    while ($class = $classes_result->fetch_assoc()) {
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-hover h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-primary badge-count"><?= htmlspecialchars($class['kategori']) ?></span>
                                        <i class="bi bi-book fs-4 text-primary opacity-25"></i>
                                    </div>
                                    
                                    <h5 class="card-title mb-3"><?= htmlspecialchars($class['nama_kelas']) ?></h5>
                                    
                                    <div class="stats-row">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">
                                                <i class="bi bi-journal-text me-2"></i>Total Materi
                                            </span>
                                            <span class="fw-bold text-primary"><?= $class['jumlah_materi'] ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <a href="kelola-materi.php?id_kelas=<?= $class['id_kelas'] ?>" 
                                           class="btn btn-primary w-100">
                                            <i class="bi bi-arrow-right-circle me-2"></i>Kelola Materi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Kelas</h5>
                            <p class="text-muted">Anda belum memiliki kelas yang dibuat</p>
                            <a href="kelola-kelas.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Buat Kelas Pertama
                            </a>
                        </div>
                    </div>
                    <?php
                }
                $stmt_classes->close();
                ?>
            </div>

        <?php elseif ($view_level === 'materi'): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="mb-1">
                                <i class="bi bi-journal-bookmark text-primary me-2"></i>
                                Materi untuk Kelas: "<?= htmlspecialchars($current_class['nama_kelas']) ?>"
                            </h4>
                            <p class="text-muted mb-0">ID Kelas: <?= $selected_id_kelas ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <?php
                $stmt_materi = $conn->prepare("
                    SELECT m.id_materi, m.judul_materi, m.urutan,
                           COUNT(sm.id_sub_materi) as jumlah_sub_materi
                    FROM tb_materi m
                    LEFT JOIN tb_sub_materi sm ON m.id_materi = sm.id_materi
                    WHERE m.id_kelas = ?
                    GROUP BY m.id_materi, m.judul_materi, m.urutan
                    ORDER BY m.urutan ASC, m.id_materi ASC
                ");
                $stmt_materi->bind_param("i", $selected_id_kelas);
                $stmt_materi->execute();
                $materi_result = $stmt_materi->get_result();

                if ($materi_result->num_rows > 0) {
                    while ($materi = $materi_result->fetch_assoc()) {
                        ?>
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card card-hover h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-secondary badge-count">Urutan <?= htmlspecialchars($materi['urutan']) ?></span>
                                        <i class="bi bi-file-earmark-text fs-4 text-primary opacity-25"></i>
                                    </div>
                                    
                                    <h5 class="card-title mb-3"><?= htmlspecialchars($materi['judul_materi']) ?></h5>
                                    
                                    <div class="stats-row">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">
                                                <i class="bi bi-list-ul me-2"></i>Sub-Materi
                                            </span>
                                            <span class="fw-bold text-primary"><?= $materi['jumlah_sub_materi'] ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 d-flex gap-2">
                                        <a href="kelola-materi.php?id_kelas=<?= $selected_id_kelas ?>&id_materi=<?= $materi['id_materi'] ?>" 
                                           class="btn btn-info flex-fill">
                                            <i class="bi bi-eye me-1"></i>Sub-Materi
                                        </a>
                                        <a href="edit-materi.php?id_materi=<?= $materi['id_materi'] ?>" 
                                           class="btn btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="delete-materi.php?id_materi=<?= $materi['id_materi'] ?>" 
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini dan semua sub-materinya?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x display-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Materi</h5>
                            <p class="text-muted">Belum ada materi untuk kelas ini</p>
                            <a href="create-materi.php?id_kelas=<?= $selected_id_kelas ?>" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Materi Pertama
                            </a>
                        </div>
                    </div>
                    <?php
                }
                $stmt_materi->close();
                ?>
            </div>

        <?php elseif ($view_level === 'sub_materi'): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="mb-1">
                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                Sub-Materi untuk: "<?= htmlspecialchars($current_materi['judul_materi']) ?>"
                            </h4>
                            <p class="text-muted mb-0">
                                Kelas: "<?= htmlspecialchars($current_class['nama_kelas']) ?>" 
                                <span class="text-primary">•</span> ID Materi: <?= $selected_id_materi ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <?php
                $stmt_sub_materi = $conn->prepare("
                    SELECT sm.id_sub_materi, sm.judul_sub_materi, sm.urutan,
                           v.file_path_video, d.file_path_dokumen
                    FROM tb_sub_materi sm
                    LEFT JOIN tb_video v ON sm.id_video = v.id_video
                    LEFT JOIN tb_dokumen d ON sm.id_dokumen = d.id_dokumen
                    WHERE sm.id_materi = ?
                    ORDER BY sm.urutan ASC, sm.id_sub_materi ASC
                ");
                $stmt_sub_materi->bind_param("i", $selected_id_materi);
                $stmt_sub_materi->execute();
                $sub_materi_result = $stmt_sub_materi->get_result();

                if ($sub_materi_result->num_rows > 0) {
                    while ($sub_materi = $sub_materi_result->fetch_assoc()) {
                        ?>
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card card-hover h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-info badge-count">Urutan <?= htmlspecialchars($sub_materi['urutan']) ?></span>
                                        <i class="bi bi-file-earmark-play fs-4 text-primary opacity-25"></i>
                                    </div>
                                    
                                    <h5 class="card-title mb-3"><?= htmlspecialchars($sub_materi['judul_sub_materi']) ?></h5>
                                    
                                    <div class="stats-row">
                                        <?php if (!empty($sub_materi['file_path_video'])): ?>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-camera-video text-success me-2"></i>
                                                <small class="text-muted">Video: <?= htmlspecialchars(basename($sub_materi['file_path_video'])) ?></small>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($sub_materi['file_path_dokumen'])): ?>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-file-earmark-text text-info me-2"></i>
                                                <small class="text-muted">Dokumen: <?= htmlspecialchars(basename($sub_materi['file_path_dokumen'])) ?></small>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (empty($sub_materi['file_path_video']) && empty($sub_materi['file_path_dokumen'])): ?>
                                            <div class="text-center py-2">
                                                <i class="bi bi-file-earmark text-muted"></i>
                                                <small class="text-muted d-block">Belum ada konten</small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mt-3 d-flex gap-2">
                                        <a href="edit-sub-materi.php?id_sub_materi=<?= $sub_materi['id_sub_materi'] ?>" 
                                           class="btn btn-outline-warning flex-fill">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </a>
                                        <a href="delete-sub-materi.php?id_sub_materi=<?= $sub_materi['id_sub_materi'] ?>" 
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus sub-materi ini?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-x display-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Sub-Materi</h5>
                            <p class="text-muted">Belum ada sub-materi untuk materi ini</p>
                            <a href="create-sub-materi.php?id_materi=<?= $selected_id_materi ?>" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Sub-Materi Pertama
                            </a>
                        </div>
                    </div>
                    <?php
                }
                $stmt_sub_materi->close();
                ?>
            </div>

        <?php endif; ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>