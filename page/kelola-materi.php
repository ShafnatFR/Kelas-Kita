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
    // Kita bisa menghentikan eksekusi atau redirect di sini jika perlu
    // header("Location: dashboard.php"); // Atau ke halaman lain
    // exit();
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
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Kelola Materi</h3>
            <div>
                <?php if ($view_level === 'kelas'): ?>
                    <a href="kelola-kelas.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Kelola Kelas</a>
                <?php elseif ($view_level === 'materi'): ?>
                    <a href="kelola-materi.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas</a>
                    <a href="create-materi.php?id_kelas=<?= $selected_id_kelas ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah Materi Baru</a>
                <?php elseif ($view_level === 'sub_materi'): ?>
                    <a href="kelola-materi.php?id_kelas=<?= $selected_id_kelas ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Materi</a>
                    <a href="create-sub-materi.php?id_materi=<?= $selected_id_materi ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah Sub-Materi Baru</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($view_level === 'kelas'): ?>
            <h4>Daftar Kelas Anda</h4>
            <p class="text-muted">Pilih kelas untuk mengelola materinya.</p>
            <div class="list-group">
                <?php
                $stmt_classes = $conn->prepare("
                    SELECT k.id_kelas, k.nama_kelas, k.kategori
                    FROM tb_kelas k
                    JOIN tb_mentor m ON k.id_mentor = m.id_mentor
                    WHERE m.id_user = ?
                    ORDER BY k.id_kelas DESC
                ");
                $stmt_classes->bind_param("i", $user_id);
                $stmt_classes->execute();
                $classes_result = $stmt_classes->get_result();

                if ($classes_result->num_rows > 0) {
                    while ($class = $classes_result->fetch_assoc()) {
                        ?>
                        <a href="kelola-materi.php?id_kelas=<?= $class['id_kelas'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($class['nama_kelas']) ?> <span class="badge text-bg-primary"><?= htmlspecialchars($class['kategori']) ?></span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <?php
                    }
                } else {
                    echo "<p class='text-muted'>Anda belum memiliki kelas yang dibuat.</p>";
                }
                $stmt_classes->close();
                ?>
            </div>

        <?php elseif ($view_level === 'materi'): ?>
            <h4>Materi untuk Kelas: "<?= htmlspecialchars($current_class['nama_kelas']) ?>"</h4>
            <p class="text-muted">ID Kelas: <?= $selected_id_kelas ?></p>
            <div class="list-group">
                <?php
                $stmt_materi = $conn->prepare("
                    SELECT id_materi, judul_materi, urutan
                    FROM tb_materi
                    WHERE id_kelas = ?
                    ORDER BY urutan ASC, id_materi ASC
                ");
                $stmt_materi->bind_param("i", $selected_id_kelas);
                $stmt_materi->execute();
                $materi_result = $stmt_materi->get_result();

                if ($materi_result->num_rows > 0) {
                    while ($materi = $materi_result->fetch_assoc()) {
                        ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?= htmlspecialchars($materi['urutan']) ?>. <?= htmlspecialchars($materi['judul_materi']) ?></h5>
                                <a href="kelola-materi.php?id_kelas=<?= $selected_id_kelas ?>&id_materi=<?= $materi['id_materi'] ?>" class="btn btn-sm btn-info mt-2"><i class="bi bi-box-arrow-right"></i> Lihat Sub-Materi</a>
                            </div>
                            <div>
                                <a href="edit-materi.php?id_materi=<?= $materi['id_materi'] ?>" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil-square"></i> Edit</a>
                                <a href="delete-materi.php?id_materi=<?= $materi['id_materi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini dan semua sub-materinya?');"><i class="bi bi-trash"></i> Hapus</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-muted'>Belum ada materi untuk kelas ini.</p>";
                }
                $stmt_materi->close();
                ?>
            </div>

        <?php elseif ($view_level === 'sub_materi'): ?>
            <h4>Sub-Materi untuk Materi: "<?= htmlspecialchars($current_materi['judul_materi']) ?>"</h4>
            <p class="text-muted">Kelas: "<?= htmlspecialchars($current_class['nama_kelas']) ?>" (ID Materi: <?= $selected_id_materi ?>)</p>
            <div class="list-group">
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
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?= htmlspecialchars($sub_materi['urutan']) ?>. <?= htmlspecialchars($sub_materi['judul_sub_materi']) ?></h5>
                                <?php if (!empty($sub_materi['file_path_video'])): ?>
                                    <p class="mb-0 text-muted"><small><i class="bi bi-camera-video"></i> Video: <?= htmlspecialchars(basename($sub_materi['file_path_video'])) ?></small></p>
                                <?php endif; ?>
                                <?php if (!empty($sub_materi['file_path_dokumen'])): ?>
                                    <p class="mb-0 text-muted"><small><i class="bi bi-file-earmark-text"></i> Dokumen: <?= htmlspecialchars(basename($sub_materi['file_path_dokumen'])) ?></small></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="edit-sub-materi.php?id_sub_materi=<?= $sub_materi['id_sub_materi'] ?>" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil-square"></i> Edit</a>
                                <a href="delete-sub-materi.php?id_sub_materi=<?= $sub_materi['id_sub_materi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus sub-materi ini?');"><i class="bi bi-trash"></i> Hapus</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-muted'>Belum ada sub-materi untuk materi ini.</p>";
                }
                $stmt_sub_materi->close();
                ?>
            </div>

        <?php endif; ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>