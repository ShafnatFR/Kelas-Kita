<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id']; // ID User dari sesi

// --- START: Inisialisasi id_mentor dan Penanganan Aksi (Ajukan Publikasi) ---
$message = ""; // Untuk pesan feedback

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

// Logika untuk menangani pengajuan publikasi kelas
if (isset($_GET['action']) && $_GET['action'] === 'submit_for_review' && isset($_GET['id_kelas'])) {
    $id_kelas_to_submit = $_GET['id_kelas'];

    // Validasi: Pastikan kelas ini milik mentor yang sedang login
    if ($id_mentor > 0) { // Pastikan id_mentor valid
        $check_stmt = $conn->prepare("SELECT id_kelas FROM tb_kelas WHERE id_kelas = ? AND id_mentor = ?");
        $check_stmt->bind_param("ii", $id_kelas_to_submit, $id_mentor);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Update status_publikasi menjadi 'pending'
            $update_status_stmt = $conn->prepare("UPDATE tb_kelas SET status_publikasi = 'pending' WHERE id_kelas = ?");
            $update_status_stmt->bind_param("i", $id_kelas_to_submit);
            if ($update_status_stmt->execute()) {
                $message = "Kelas berhasil diajukan untuk publikasi.";
            } else {
                $message = "Gagal mengajukan kelas: " . $conn->error;
            }
            $update_status_stmt->close();
        } else {
            $message = "Kelas tidak ditemukan atau bukan milik Anda.";
        }
        $check_stmt->close();
    } else {
        $message = "ID Mentor tidak valid. Silakan hubungi admin.";
    }

    // Redirect untuk membersihkan URL GET dan menampilkan pesan
    header("Location: kelola-kelas.php?msg=" . urlencode($message));
    exit();
}

// Ambil pesan dari URL jika ada (setelah redirect)
if (isset($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']));
}
// --- END: Inisialisasi id_mentor dan Penanganan Aksi ---


// Mengambil kelas yang dikelola oleh mentor berdasarkan id_user (SEKARANG JUGA MENGAMBIL status_publikasi)
$stmt_classes = $conn->prepare("
    SELECT k.id_kelas, k.nama_kelas, k.kategori, k.harga, k.description, k.status_publikasi
    FROM tb_kelas k
    JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    WHERE m.id_user = ?
    ORDER BY k.id_kelas DESC
");
$stmt_classes->bind_param("i", $_SESSION['id']);
$stmt_classes->execute();
$classes_result = $stmt_classes->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/keloladata-mentor.css">
    <style>
        /* CSS ini opsional jika text-bg-* sudah cukup. Bisa dihapus jika tidak diperlukan. */
        .badge-draft { background-color: #6c757d; color: #fff; } /* secondary */
        .badge-pending { background-color: #ffc107; color: #212529; } /* warning */
        .badge-approved { background-color: #28a745; color: #fff; } /* success */
        .badge-rejected { background-color: #dc3545; color: #fff; } /* danger */
    </style>
</head>
<body class="bg-light">
    <?php include 'sidebar-mentor.php'; // Pastikan path ke sidebar Anda benar ?>

    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Kelola Kelas</h2>
            <a href="create-class.php" class="btn btn-success">+ Tambah Kelas</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-wrapper">
            <?php if ($classes_result->num_rows > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                            <th>Status Publikasi</th> <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $classes_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                                <td><?= htmlspecialchars($row['kategori']) ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars(substr($row['description'], 0, 50)) ?><?= strlen($row['description']) > 50 ? '...' : '' ?></td>
                                <td>
                                    <?php
                                    $status = $row['status_publikasi'];
                                    $badge_class = '';
                                    $text_bg_class = ''; // For Bootstrap 5 text-bg-* classes
                                    switch ($status) {
                                        case 'draft':
                                            // $badge_class = 'badge-secondary'; // For older Bootstrap 4
                                            $text_bg_class = 'text-bg-secondary';
                                            break;
                                        case 'pending':
                                            // $badge_class = 'badge-warning';
                                            $text_bg_class = 'text-bg-warning';
                                            break;
                                        case 'approved':
                                            // $badge_class = 'badge-success';
                                            $text_bg_class = 'text-bg-success';
                                            break;
                                        case 'rejected':
                                            // $badge_class = 'badge-danger';
                                            $text_bg_class = 'text-bg-danger';
                                            break;
                                        default:
                                            // $badge_class = 'badge-info';
                                            $text_bg_class = 'text-bg-info';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $text_bg_class ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
                                </td>
                                <td>
                                    <a href="edit-class.php?id_kelas=<?= $row['id_kelas'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete-class.php?id_kelas=<?= $row['id_kelas'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus kelas ini?')">Delete</a>

                                    <?php if ($row['status_publikasi'] === 'draft' || $row['status_publikasi'] === 'rejected'): ?>
                                        <a href="kelola-kelas.php?action=submit_for_review&id_kelas=<?= $row['id_kelas'] ?>" class="btn btn-success btn-sm mt-1">Ajukan Publikasi</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada kelas yang dibuat.</p>
                    <a href="create-class.php" class="btn btn-primary">Buat Kelas Pertama</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt_classes->close();
$conn->close();
?>