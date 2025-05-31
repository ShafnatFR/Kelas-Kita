<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$id_kelas = $_GET['id_kelas'] ?? 0;
$message = "";

// Ambil data kelas untuk diedit
$stmt = $conn->prepare("
    SELECT k.* FROM tb_kelas k
    JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    WHERE k.id_kelas = ? AND m.id_user = ?
");
$stmt->bind_param("ii", $id_kelas, $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: kelola-kelas.php");
    exit();
}

$kelas = $result->fetch_assoc();

// Direktori upload untuk foto profil kelas
$upload_dir_kelas_profil = '../uploads/kelas_profil/';

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kelas = trim($_POST['nama_kelas']);
    $kategori = trim($_POST['kategori']);
    $harga = trim($_POST['harga']);
    $description = trim($_POST['description']);

    // Ambil path foto profil kelas yang ada saat ini dari database
    $current_profil_kelas_path = $kelas['profil_kelas'];
    $profil_kelas_path = $current_profil_kelas_path; // Default: pertahankan yang sudah ada

    // --- AWAL LOGIKA UPLOAD FOTO PROFIL KELAS BARU ---
    if (isset($_FILES['profil_kelas_file']) && $_FILES['profil_kelas_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['profil_kelas_file']['tmp_name'];
        $file_name = basename($_FILES['profil_kelas_file']['name']);
        $file_size = $_FILES['profil_kelas_file']['size'];
        $file_type = $_FILES['profil_kelas_file']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        
        // Validasi tipe file
        if (!in_array($file_type, $allowed_types) || !in_array($file_ext, $allowed_exts)) {
            $message = "Tipe file foto profil kelas tidak diizinkan. Hanya JPG, JPEG, PNG, GIF.";
        } 
        // Validasi ukuran file (5MB)
        elseif ($file_size > 5 * 1024 * 1024) { 
            $message = "Ukuran file foto profil kelas terlalu besar. Maksimal 5MB.";
        } else {
            // Pastikan direktori upload ada
            if (!is_dir($upload_dir_kelas_profil)) {
                mkdir($upload_dir_kelas_profil, 0777, true); 
            }

            // Generate nama file unik untuk menghindari konflik
            $new_file_name = uniqid('profil_') . '.' . $file_ext;
            $destination_path = $upload_dir_kelas_profil . $new_file_name;

            // Pastikan path tidak melebihi varchar(100)
            if (strlen($destination_path) > 100) {
                $message = "Path file terlalu panjang, coba nama file yang lebih pendek.";
            } elseif (move_uploaded_file($file_tmp_name, $destination_path)) {
                $profil_kelas_path = $destination_path; // Update path untuk database

                // Hapus foto lama jika ada dan bukan foto default
                if ($current_profil_kelas_path && file_exists($current_profil_kelas_path) && $current_profil_kelas_path !== '../assets/img/default_class_profil.png') {
                    unlink($current_profil_kelas_path);
                }
            } else {
                $message = "Gagal memindahkan file foto profil kelas yang diunggah.";
            }
        }
    } elseif (isset($_FILES['profil_kelas_file']) && $_FILES['profil_kelas_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Tangani error upload selain tidak ada file
        $message = "Kesalahan upload foto profil kelas: " . $_FILES['profil_kelas_file']['error'];
    }
    // --- AKHIR LOGIKA UPLOAD FOTO PROFIL KELAS BARU ---

    if (empty($nama_kelas) || empty($kategori) || empty($harga)) {
        $message = "Nama kelas, kategori, dan harga wajib diisi!";
    } elseif (empty($message)) { // Hanya lanjutkan jika tidak ada error upload
        // Ambil status kelas saat ini sebelum update
        $current_status_query = $conn->prepare("SELECT status_publikasi FROM tb_kelas WHERE id_kelas = ?");
        $current_status_query->bind_param("i", $id_kelas);
        $current_status_query->execute();
        $current_status_result = $current_status_query->get_result();
        $current_status_row = $current_status_result->fetch_assoc();
        $current_status = $current_status_row['status_publikasi'];
        $current_status_query->close();

        $new_status = $current_status; // Default: status tidak berubah

        // Jika kelas sudah disetujui atau ditolak, setiap edit akan mengembalikan status ke 'pending' untuk review ulang
        if ($current_status === 'approved' || $current_status === 'rejected') {
            $new_status = 'pending';
        }
        // Jika status sebelumnya adalah 'draft', tetap biarkan statusnya 'draft'
        elseif ($current_status === 'draft') {
            $new_status = 'draft';
        }

        // Query UPDATE sudah disesuaikan untuk menyertakan status_publikasi dan profil_kelas
        $update_stmt = $conn->prepare("UPDATE tb_kelas SET nama_kelas = ?, kategori = ?, harga = ?, description = ?, profil_kelas = ?, status_publikasi = ?, tanggal_update = CURRENT_TIMESTAMP WHERE id_kelas = ?");
        $update_stmt->bind_param("ssdsisi", $nama_kelas, $kategori, $harga, $description, $profil_kelas_path, $new_status, $id_kelas);

        if ($update_stmt->execute()) {
            header("Location: kelola-kelas.php");
            exit();
        } else {
            $message = "Gagal mengupdate kelas: " . $conn->error;
            // Jika update database gagal, dan ada file baru diupload, hapus file baru tersebut
            if ($profil_kelas_path !== $current_profil_kelas_path && file_exists($profil_kelas_path)) {
                unlink($profil_kelas_path);
            }
        }
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Kelas</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-warning"><?= htmlspecialchars($message) ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($kelas['status_publikasi'])): ?>
                            <div class="mb-3">
                                <strong>Status Saat Ini: </strong>
                                <?php
                                $status = $kelas['status_publikasi'];
                                $text_bg_class = '';
                                switch ($status) {
                                    case 'draft': $text_bg_class = 'text-bg-secondary'; break;
                                    case 'pending': $text_bg_class = 'text-bg-warning'; break;
                                    case 'approved': $text_bg_class = 'text-bg-success'; break;
                                    case 'rejected': $text_bg_class = 'text-bg-danger'; break;
                                    default: $text_bg_class = 'text-bg-info'; break;
                                }
                                ?>
                                <span class="badge <?= $text_bg_class ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
                                <?php if ($status === 'approved' || $status === 'rejected'): ?>
                                    <small class="text-muted ms-2">(Perubahan akan mengembalikan status ke 'Pending')</small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <!-- Foto Profil Kelas Section -->
                            <div class="mb-3">
                                <label class="form-label">Foto Profil Kelas</label>
                                
                                <!-- Tampilkan foto profil saat ini -->
                                <?php if (!empty($kelas['profil_kelas']) && file_exists($kelas['profil_kelas'])): ?>
                                    <div class="mb-2">
                                        <img src="<?= htmlspecialchars($kelas['profil_kelas']) ?>" 
                                             alt="Foto Profil Kelas" 
                                             class="img-thumbnail" 
                                             style="max-width: 200px; max-height: 200px;">
                                        <small class="text-muted d-block">Foto profil saat ini</small>
                                    </div>
                                <?php endif; ?>
                                
                                <input type="file" 
                                       class="form-control" 
                                       name="profil_kelas_file" 
                                       accept="image/jpeg,image/png,image/gif">
                                <small class="text-muted">Format yang diizinkan: JPG, JPEG, PNG, GIF. Maksimal 5MB. Kosongkan jika tidak ingin mengubah foto.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Kelas</label>
                                <input type="text" class="form-control" name="nama_kelas"
                                        value="<?= htmlspecialchars($kelas['nama_kelas']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-control" name="kategori" required>
                                    <option value="Programming" <?= $kelas['kategori'] == 'Programming' ? 'selected' : '' ?>>Programming</option>
                                    <option value="Design" <?= $kelas['kategori'] == 'Design' ? 'selected' : '' ?>>Design</option>
                                    <option value="Marketing" <?= $kelas['kategori'] == 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                                    <option value="Business" <?= $kelas['kategori'] == 'Business' ? 'selected' : '' ?>>Business</option>
                                    <option value="Other" <?= $kelas['kategori'] == 'Other' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" class="form-control" name="harga"
                                        value="<?= $kelas['harga'] ?>" min="0" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($kelas['description']) ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="kelola-kelas.php" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Update Kelas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>