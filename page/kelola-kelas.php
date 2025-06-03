<?php
session_start();

// Pastikan path ke file db.php benar dan file ada
$db_path = 'db.php';
if (!file_exists($db_path)) {
    die("Error: File db.php tidak ditemukan di path: " . $db_path);
}

require $db_path;

// Validasi koneksi database
if (!isset($conn) || $conn === null) {
    die("Error: Koneksi database gagal. Pastikan file db.php mengembalikan variabel \$conn yang valid.");
}

// Cek koneksi database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validasi session
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

// Validasi session ID
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    die("Error: Session ID tidak valid. Silakan login ulang.");
}

$user_id = $_SESSION['id']; // ID User dari sesi
$message = ""; // Untuk pesan feedback

// --- START: Inisialisasi id_mentor ---
$id_mentor = 0; // Default value

try {
    // Dapatkan id_mentor berdasarkan id_user yang login
    $mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
    if (!$mentor_query) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $mentor_query->bind_param("i", $user_id);
    $mentor_query->execute();
    $mentor_result = $mentor_query->get_result();

    if ($mentor_result->num_rows > 0) {
        $mentor_row = $mentor_result->fetch_assoc();
        $id_mentor = $mentor_row['id_mentor'];
    } else {
        throw new Exception("Data mentor tidak ditemukan untuk user ID: $user_id");
    }
    $mentor_query->close();
} catch (Exception $e) {
    die("Error saat mengambil data mentor: " . $e->getMessage());
}

// Validasi id_mentor
if ($id_mentor <= 0) {
    die("Error: ID Mentor tidak valid ($id_mentor). Silakan hubungi admin.");
}

// --- START: Penanganan Aksi (Ajukan Publikasi) ---
if (isset($_GET['action']) && $_GET['action'] === 'submit_for_review' && isset($_GET['id_kelas'])) {
    $id_kelas_to_submit = (int)$_GET['id_kelas'];
    $debug_info = []; // Array untuk menyimpan info debug

    if ($id_kelas_to_submit <= 0) {
        $message = "ID Kelas tidak valid.";
    } else {
        try {
            // Ambil data kelas lengkap untuk validasi
            $check_stmt = $conn->prepare("
                SELECT id_kelas, nama_kelas, status_publikasi, tanggal_update 
                FROM tb_kelas 
                WHERE id_kelas = ? AND id_mentor = ?
            ");
            
            if (!$check_stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $check_stmt->bind_param("ii", $id_kelas_to_submit, $id_mentor);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $class_data = $check_result->fetch_assoc();
                $current_status = $class_data['status_publikasi'];
                $debug_info[] = "Status sebelum update: " . $current_status;
                
                // Validasi status yang diizinkan untuk diajukan
                $allowed_statuses = ['draft', 'rejected'];
                
                if (in_array($current_status, $allowed_statuses)) {
                    // Gunakan transaksi untuk memastikan konsistensi data
                    $conn->begin_transaction();
                    
                    try {
                        // Update dengan WHERE condition yang lebih spesifik
                        $update_status_stmt = $conn->prepare("
                            UPDATE tb_kelas 
                            SET status_publikasi = 'pending' 
                            WHERE id_kelas = ? 
                            AND id_mentor = ? 
                            AND status_publikasi = ?
                        ");
                        
                        if (!$update_status_stmt) {
                            throw new Exception("Prepare update failed: " . $conn->error);
                        }
                        
                        $update_status_stmt->bind_param("iis", $id_kelas_to_submit, $id_mentor, $current_status);
                        $update_result = $update_status_stmt->execute();
                        $affected_rows = $conn->affected_rows;
                        
                        $debug_info[] = "Update executed: " . ($update_result ? 'SUCCESS' : 'FAILED');
                        $debug_info[] = "Affected rows: " . $affected_rows;
                        
                        if ($update_result && $affected_rows > 0) {
                            // Verifikasi update berhasil dengan query SELECT
                            $verify_stmt = $conn->prepare("
                                SELECT status_publikasi, tanggal_update 
                                FROM tb_kelas 
                                WHERE id_kelas = ?
                            ");
                            
                            if (!$verify_stmt) {
                                throw new Exception("Prepare verify failed: " . $conn->error);
                            }
                            
                            $verify_stmt->bind_param("i", $id_kelas_to_submit);
                            $verify_stmt->execute();
                            $verify_result = $verify_stmt->get_result();
                            
                            if ($verify_result->num_rows > 0) {
                                $verify_data = $verify_result->fetch_assoc();
                                $new_status = $verify_data['status_publikasi'];
                                $debug_info[] = "Status setelah update: " . $new_status;
                                $debug_info[] = "Tanggal update: " . $verify_data['tanggal_update'];
                                
                                if ($new_status === 'pending') {
                                    $conn->commit();
                                    $message = "Kelas '{$class_data['nama_kelas']}' berhasil diajukan untuk publikasi.";
                                    
                                    // Log ke file untuk debugging
                                    error_log("Publication submitted - Class ID: $id_kelas_to_submit, Status: $new_status, Time: " . date('Y-m-d H:i:s'));
                                } else {
                                    throw new Exception("Status tidak berubah setelah update. Status saat ini: $new_status");
                                }
                            } else {
                                throw new Exception("Kelas tidak ditemukan setelah update");
                            }
                            $verify_stmt->close();
                            
                        } else {
                            throw new Exception("Update gagal atau tidak ada baris yang terpengaruh. MySQL Error: " . $conn->error);
                        }
                        $update_status_stmt->close();
                        
                    } catch (Exception $e) {
                        $conn->rollback();
                        $message = "Gagal mengajukan kelas: " . $e->getMessage();
                        $debug_info[] = "Exception: " . $e->getMessage();
                        
                        // Log error ke file
                        error_log("Publication failed - Class ID: $id_kelas_to_submit, Error: " . $e->getMessage());
                    }
                    
                } else {
                    $message = "Kelas dengan status '$current_status' tidak dapat diajukan untuk publikasi.";
                    $debug_info[] = "Status tidak diizinkan: $current_status";
                }
            } else {
                $message = "Kelas tidak ditemukan atau bukan milik Anda.";
                $debug_info[] = "Kelas tidak ditemukan - ID: $id_kelas_to_submit, Mentor: $id_mentor";
            }
            $check_stmt->close();
            
        } catch (Exception $e) {
            $message = "Error database: " . $e->getMessage();
            $debug_info[] = "Database Exception: " . $e->getMessage();
        }
    }

    // Simpan debug info ke session untuk ditampilkan jika diperlukan
    if (isset($_GET['debug']) && $_GET['debug'] == '1') {
        $_SESSION['debug_info'] = $debug_info;
    }

    // Redirect untuk membersihkan URL GET dan menampilkan pesan
    $redirect_url = "kelola-kelas.php?msg=" . urlencode($message);
    if (isset($_GET['debug']) && $_GET['debug'] == '1') {
        $redirect_url .= "&debug=1";
    }
    header("Location: $redirect_url");
    exit();
}

// Ambil pesan dari URL jika ada (setelah redirect)
if (isset($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']));
}

// --- START: Mengambil Data Kelas ---
try {
    $stmt_classes = $conn->prepare("
        SELECT 
            k.id_kelas, 
            k.nama_kelas, 
            k.kategori, 
            k.harga, 
            k.description, 
            k.status_publikasi,
            k.tanggal_update,
            k.tgl_dibuat
        FROM tb_kelas k
        JOIN tb_mentor m ON k.id_mentor = m.id_mentor
        WHERE m.id_user = ?
        ORDER BY k.tanggal_update DESC, k.id_kelas DESC
    ");
    
    if (!$stmt_classes) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt_classes->bind_param("i", $_SESSION['id']);
    $stmt_classes->execute();
    $classes_result = $stmt_classes->get_result();
    
} catch (Exception $e) {
    die("Error saat mengambil data kelas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/keloladata-mentor.css">
</head>
<body class="bg-light">
    <?php include 'sidebar-mentor.php'; ?>

    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Kelola Kelas</h2>
            <a href="create-class.php" class="btn btn-success">+ Tambah Kelas</a>
        </div>

        <!-- Tampilkan debug info jika ada -->
        <?php if (isset($_GET['debug']) && $_GET['debug'] == '1' && isset($_SESSION['debug_info'])): ?>
        <div class="alert alert-warning">
            <strong>Debug Information:</strong><br>
            <?php foreach ($_SESSION['debug_info'] as $info): ?>
                <?= htmlspecialchars($info) ?><br>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['debug_info']); endif; ?>

        <!-- Debug Mode Info -->
        <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
        <div class="alert alert-info">
            <strong>Debug Mode Active</strong><br>
            ID Mentor: <?= $id_mentor ?><br>
            Session ID: <?= $_SESSION['id'] ?? 'Not set' ?><br>
            Session Role: <?= $_SESSION['role'] ?? 'Not set' ?><br>
            Current Time: <?= date('Y-m-d H:i:s') ?><br>
            Database Connection: <?= ($conn && !$conn->connect_error) ? 'OK' : 'FAILED' ?>
        </div>
        <?php endif; ?>

        <!-- Pesan Feedback -->
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
                            <th>Status Publikasi</th>
                            <th>Aksi</th>
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
                                    $text_bg_class = '';
                                    switch ($status) {
                                        case 'draft':
                                            $text_bg_class = 'text-bg-secondary';
                                            break;
                                        case 'pending':
                                            $text_bg_class = 'text-bg-warning';
                                            break;
                                        case 'approved':
                                            $text_bg_class = 'text-bg-success';
                                            break;
                                        case 'rejected':
                                            $text_bg_class = 'text-bg-danger';
                                            break;
                                        default:
                                            $text_bg_class = 'text-bg-info';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $text_bg_class ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
                                    <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
                                        <small class="text-muted d-block">Update: <?= $row['tanggal_update'] ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit-class.php?id_kelas=<?= $row['id_kelas'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete-class.php?id_kelas=<?= $row['id_kelas'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus kelas ini?')">Delete</a>

                                    <?php if (in_array($row['status_publikasi'], ['draft', 'rejected'])): ?>
                                        <?php 
                                        $confirm_msg = "Yakin ingin mengajukan kelas '" . addslashes($row['nama_kelas']) . "' untuk publikasi?";
                                        $debug_param = (isset($_GET['debug']) && $_GET['debug'] == '1') ? '&debug=1' : '';
                                        ?>
                                        <a href="kelola-kelas.php?action=submit_for_review&id_kelas=<?= $row['id_kelas'] ?><?= $debug_param ?>" 
                                           class="btn btn-success btn-sm mt-1"
                                           onclick="return confirm('<?= $confirm_msg ?>')">
                                           Ajukan Publikasi
                                        </a>
                                    <?php elseif ($row['status_publikasi'] === 'pending'): ?>
                                        <span class="badge text-bg-warning">Menunggu Review</span>
                                    <?php elseif ($row['status_publikasi'] === 'approved'): ?>
                                        <span class="badge text-bg-success">Sudah Dipublikasi</span>
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
// Tutup statement dan koneksi
if (isset($stmt_classes)) {
    $stmt_classes->close();
}
if (isset($conn)) {
    $conn->close();
}
?>