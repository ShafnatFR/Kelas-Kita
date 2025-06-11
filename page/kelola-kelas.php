<?php
session_start();
// Pastikan path ke file db.php benar
require 'db.php';

// Validasi session mentor
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'mentor' || !isset($_SESSION['id'])) {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$id_mentor = 0;
$message = "";

// Ambil id_mentor berdasarkan id_user yang login
try {
    $mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
    if (!$mentor_query) throw new Exception("Prepare failed: " . $conn->error);
    
    $mentor_query->bind_param("i", $user_id);
    $mentor_query->execute();
    $mentor_result = $mentor_query->get_result();

    if ($mentor_row = $mentor_result->fetch_assoc()) {
        $id_mentor = $mentor_row['id_mentor'];
    } else {
        // Jika mentor belum ada, bisa ditambahkan di sini atau dilempar error
        throw new Exception("Data mentor tidak ditemukan untuk user ID: $user_id");
    }
    $mentor_query->close();
} catch (Exception $e) {
    die("Error Kritis: " . $e->getMessage());
}

// --- START: Penanganan Aksi (Ajukan Publikasi) ---
if (isset($_GET['action']) && $_GET['action'] === 'submit_for_review' && isset($_GET['id_kelas'])) {
    $id_kelas_to_submit = (int)$_GET['id_kelas'];

    if ($id_kelas_to_submit > 0) {
        $conn->begin_transaction();
        try {
            // Cek status kelas saat ini milik mentor ini
            $check_stmt = $conn->prepare("SELECT nama_kelas, status_publikasi FROM tb_kelas WHERE id_kelas = ? AND id_mentor = ?");
            if (!$check_stmt) throw new Exception("Prepare check failed: " . $conn->error);
            $check_stmt->bind_param("ii", $id_kelas_to_submit, $id_mentor);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($class_data = $check_result->fetch_assoc()) {
                $current_status = $class_data['status_publikasi'];
                if (in_array($current_status, ['draft', 'rejected'])) {
                    $update_stmt = $conn->prepare("UPDATE tb_kelas SET status_publikasi = 'pending' WHERE id_kelas = ? AND id_mentor = ?");
                    if (!$update_stmt) throw new Exception("Prepare update failed: " . $conn->error);
                    $update_stmt->bind_param("ii", $id_kelas_to_submit, $id_mentor);
                    $update_stmt->execute();
                    
                    if ($update_stmt->affected_rows > 0) {
                        $conn->commit();
                        $message = "Kelas '{$class_data['nama_kelas']}' berhasil diajukan untuk ditinjau.";
                    } else {
                        throw new Exception("Gagal mengupdate status, tidak ada baris yang terpengaruh.");
                    }
                    $update_stmt->close();
                } else {
                    $message = "Hanya kelas dengan status 'Draft' atau 'Rejected' yang dapat diajukan.";
                }
            } else {
                $message = "Kelas tidak ditemukan atau Anda tidak memiliki akses.";
            }
            $check_stmt->close();
        } catch (Exception $e) {
            $conn->rollback();
            $message = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $message = "ID kelas tidak valid.";
    }
    header("Location: kelola-kelas.php?msg=" . urlencode($message));
    exit();
}

// Ambil pesan dari URL jika ada
if (isset($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']));
}

// --- START: Mengambil Data Kelas untuk Ditampilkan ---
$classes_result = null; // Inisialisasi
try {
    $stmt_classes = $conn->prepare("
        SELECT id_kelas, nama_kelas, kategori, harga, status_publikasi
        FROM tb_kelas
        WHERE id_mentor = ?
        ORDER BY tgl_dibuat DESC
    ");
    if (!$stmt_classes) throw new Exception("Prepare select classes failed: " . $conn->error);
    $stmt_classes->bind_param("i", $id_mentor);
    $stmt_classes->execute();
    $classes_result = $stmt_classes->get_result();
} catch (Exception $e) {
    die("Error saat mengambil data kelas: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas - Dashboard Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Memuat CSS dari sidebar Anda (pastikan path ini benar) -->
    <link rel="stylesheet" href="../assets/css/sidebar-mentor.css"> 
    
    <style>
        /* CSS Tambahan untuk styling konten */
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.5rem;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fc;
        }
        .badge {
            font-size: 0.85em;
            padding: 0.5em 0.75em;
            font-weight: 500;
        }
        .empty-state {
            text-align: center;
            padding: 4rem;
            color: #6c757d;
        }
        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #e3e6f0;
        }
        .action-buttons a, .action-buttons button {
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <!-- Memanggil sidebar Anda yang memiliki position:fixed -->
    <?php include 'sidebar-mentor.php'; ?>

    <!-- MEMBUNGKUS KONTEN DENGAN KELAS .main-content DARI CSS ANDA -->
    <div class="main-content">
        <!-- Header Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chalkboard-teacher me-2"></i>Kelola Kelas Anda
            </h1>
            <a href="create-class.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Buat Kelas Baru
            </a>
        </div>

        <!-- Tampilkan pesan feedback jika ada -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Card untuk Tabel Data -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Kelas</h5>
                <span class="badge bg-secondary-subtle text-secondary-emphasis"><?= $classes_result->num_rows ?> Kelas Ditemukan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">Nama Kelas</th>
                                <th scope="col">Kategori</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($classes_result->num_rows > 0): ?>
                                <?php while ($row = $classes_result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?= htmlspecialchars($row['nama_kelas']) ?></div>
                                            <small class="text-muted">Rp <?= number_format($row['harga'], 0, ',', '.') ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $status = $row['status_publikasi'];
                                            $badge_class = 'bg-secondary'; // Default
                                            if ($status == 'approved') $badge_class = 'bg-success';
                                            if ($status == 'pending') $badge_class = 'bg-warning text-dark';
                                            if ($status == 'rejected') $badge_class = 'bg-danger';
                                            if ($status == 'draft') $badge_class = 'bg-info text-dark';
                                            ?>
                                            <span class="badge rounded-pill <?= $badge_class ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
                                        </td>
                                        <td class="text-center pe-4 action-buttons">
                                            <a href="edit-class.php?id_kelas=<?= $row['id_kelas'] ?>" class="btn btn-outline-primary btn-sm" title="Edit Kelas">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete-class.php?id_kelas=<?= $row['id_kelas'] ?>" class="btn btn-outline-danger btn-sm" title="Hapus Kelas"
                                               onclick="return confirm('PENTING:\n\nAnda yakin ingin mengarsipkan kelas \'<?= addslashes($row['nama_kelas']) ?>\'?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            
                                            <?php if (in_array($row['status_publikasi'], ['draft', 'rejected'])): ?>
                                                <a href="kelola-kelas.php?action=submit_for_review&id_kelas=<?= $row['id_kelas'] ?>" 
                                                   class="btn btn-success btn-sm" title="Ajukan untuk ditinjau"
                                                   onclick="return confirm('Anda yakin ingin mengajukan kelas \'<?= addslashes($row['nama_kelas']) ?>\' untuk ditinjau oleh admin?')">
                                                   <i class="fas fa-paper-plane"></i> Ajukan
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div class="icon"><i class="fas fa-chalkboard"></i></div>
                                            <h4>Anda Belum Membuat Kelas</h4>
                                            <p class="mb-0">Ayo mulai buat kelas pertama Anda dan bagikan ilmu.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- Akhir dari .main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Tutup statement dan koneksi
if (isset($stmt_classes)) {
    $stmt_classes->close();
}
if (isset($conn)) {
    $conn->close();
}
?>
