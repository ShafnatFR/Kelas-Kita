<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";

// Ambil data mentor lengkap
// FIX: Menghapus kolom 'deskripsi' dari query karena tidak lagi digunakan
$mentor_query = $conn->prepare("
    SELECT 
        u.id_user, u.first_name, u.last_name, u.username, u.email, u.fotoProfil, u.instagram, u.linkdin, u.twitter, u.github,
        m.id_mentor, m.keahlian, m.pengalaman, m.status
    FROM tb_user u 
    JOIN tb_mentor m ON u.id_user = m.id_user 
    WHERE u.id_user = ?
");
$mentor_query->bind_param("i", $user_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

if ($mentor_result->num_rows === 0) {
    header("Location: become-mentor.php?error=not_found");
    exit();
}
$mentor = $mentor_result->fetch_assoc();

// Ambil statistik mentor
$stats_query = $conn->prepare("
    SELECT 
        COUNT(DISTINCT k.id_kelas) as total_kelas,
        COUNT(CASE WHEN k.status_publikasi = 'approved' THEN k.id_kelas END) as kelas_approved,
        COUNT(CASE WHEN k.status_publikasi = 'pending' THEN k.id_kelas END) as kelas_pending,
        COUNT(CASE WHEN k.status_publikasi = 'draft' THEN k.id_kelas END) as kelas_draft
    FROM tb_mentor mt
    LEFT JOIN tb_kelas k ON mt.id_mentor = k.id_mentor
    WHERE mt.id_user = ?
");
$stats_query->bind_param("i", $user_id);
$stats_query->execute();
$stats = $stats_query->get_result()->fetch_assoc();

// Ambil kelas terbaru
$recent_classes_query = $conn->prepare("
    SELECT k.id_kelas, k.nama_kelas, k.kategori, k.tgl_dibuat, k.harga, k.status_publikasi
    FROM tb_kelas k
    INNER JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    WHERE m.id_user = ?
    ORDER BY k.tgl_dibuat DESC
    LIMIT 3
");
$recent_classes_query->bind_param("i", $user_id);
$recent_classes_query->execute();
$recent_classes = $recent_classes_query->get_result();

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    
    $conn->begin_transaction(); 

    try {
        // --- PROSES UPLOAD FOTO PROFIL BARU ---
        $new_photo_filename = null;
        if (isset($_FILES['foto_profil_file']) && $_FILES['foto_profil_file']['error'] === UPLOAD_ERR_OK) {
            
            $upload_dir = '../uploads/profile/'; 
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_tmp_name = $_FILES['foto_profil_file']['tmp_name'];
            $file_name = basename($_FILES['foto_profil_file']['name']);
            $file_size = $_FILES['foto_profil_file']['size'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

            if ($file_size > 2 * 1024 * 1024) { 
                throw new Exception("Ukuran file terlalu besar. Maksimal 2MB.");
            }
            if (!in_array($file_ext, $allowed_exts)) {
                throw new Exception("Tipe file tidak diizinkan. Hanya JPG, PNG, GIF.");
            }

            $new_photo_filename = uniqid('profil_') . '.' . $file_ext;
            $destination_path = $upload_dir . $new_photo_filename;

            if (!move_uploaded_file($file_tmp_name, $destination_path)) {
                throw new Exception("Gagal memindahkan file yang diunggah.");
            }

            $old_photo = $mentor['fotoProfil'];
            if (!empty($old_photo) && file_exists($upload_dir . $old_photo)) {
                unlink($upload_dir . $old_photo);
            }
        }

        // --- UPDATE DATABASE ---
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $keahlian = $_POST['keahlian'];
        $pengalaman = $_POST['pengalaman'];
        // FIX: Menghilangkan variabel $deskripsi karena sudah tidak digunakan
        $linkdin = $_POST['linkdin'];
        $instagram = $_POST['instagram'];
        $twitter = $_POST['twitter'];
        $github = $_POST['github'];

        // Siapkan query update untuk tb_user
        if ($new_photo_filename) {
            $sql_user = "UPDATE tb_user SET first_name = ?, last_name = ?, email = ?, linkdin = ?, instagram = ?, twitter = ?, github = ?, fotoProfil = ? WHERE id_user = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("ssssssssi", $first_name, $last_name, $email, $linkdin, $instagram, $twitter, $github, $new_photo_filename, $user_id);
        } else {
            $sql_user = "UPDATE tb_user SET first_name = ?, last_name = ?, email = ?, linkdin = ?, instagram = ?, twitter = ?, github = ? WHERE id_user = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("sssssssi", $first_name, $last_name, $email, $linkdin, $instagram, $twitter, $github, $user_id);
        }
        
        if (!$stmt_user->execute()) {
            throw new Exception("Gagal update data user: " . $stmt_user->error);
        }

        // Update data mentor (tb_mentor)
        // FIX: Menghapus kolom 'deskripsi = ?' dari query UPDATE
        $update_mentor = $conn->prepare("UPDATE tb_mentor SET keahlian = ?, pengalaman = ? WHERE id_user = ?");
        // FIX: Menyesuaikan tipe dan jumlah parameter menjadi "ssi"
        $update_mentor->bind_param("ssi", $keahlian, $pengalaman, $user_id);
        
        if (!$update_mentor->execute()) {
            throw new Exception("Gagal update data mentor: " . $update_mentor->error);
        }

        $conn->commit();
        $message = "Profil berhasil diperbarui!";
        header("Location: mentor-profil.php?msg=" . urlencode($message));
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $message = "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Ambil pesan dari URL
if (isset($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mentor - Kelas Kita</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Pastikan path CSS ini benar -->
    <link rel="stylesheet" href="../assets/css/sidebar-mentor.css"> 
    <link rel="stylesheet" href="../assets/css/mentor-profil.css"> 
</head>
<body class="bg-light">
    <?php include "sidebar-mentor.php"?>

    <!-- Bungkus semua konten dengan div yang sesuai dengan CSS sidebar Anda -->
    <div class="main-content">
        <!-- Header Profile -->
        <div class="profile-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <img src="<?php echo !empty($mentor['fotoProfil']) ? '../uploads/profile/' . $mentor['fotoProfil'] : '../assets/images/default-avatar.png'; ?>" 
                             alt="Profile" class="profile-img">
                    </div>
                    <div class="col-md-9">
                        <h2><?php echo htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']); ?></h2>
                        <p class="mb-2"><i class="fas fa-user"></i> @<?php echo htmlspecialchars($mentor['username']); ?></p>
                        <p class="mb-2"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($mentor['email']); ?></p>
                        <p class="mb-3"><i class="fas fa-tools"></i> <?php echo htmlspecialchars($mentor['keahlian'] ?? 'Belum diisi'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-5">
            <?php if (!empty($message)): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Statistik -->
                <div class="col-md-12 mb-4">
                    <div class="row">
                        <!-- Kolom statistik Anda ada di sini -->
                    </div>
                </div>

                <!-- Form Edit Profil -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Profil</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="fotoProfilInput" class="form-label">Ganti Foto Profil</label>
                                        <input type="file" class="form-control" id="fotoProfilInput" name="foto_profil_file" accept="image/png, image/jpeg, image/gif">
                                        <div class="form-text">Pilih file gambar (JPG, PNG, GIF) dengan ukuran maksimal 2MB.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="first_name" 
                                               value="<?php echo htmlspecialchars($mentor['first_name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" 
                                               value="<?php echo htmlspecialchars($mentor['last_name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="<?php echo htmlspecialchars($mentor['email']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keahlian</label>
                                        <input type="text" class="form-control" name="keahlian" 
                                               value="<?php echo htmlspecialchars($mentor['keahlian'] ?? ''); ?>" 
                                               placeholder="Contoh: Web Development, UI/UX Design">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pengalaman</label>
                                    <textarea class="form-control" name="pengalaman" rows="4" 
                                              placeholder="Ceritakan pengalaman profesional Anda..."><?php echo htmlspecialchars($mentor['pengalaman'] ?? ''); ?></textarea>
                                </div>
                                <!-- FIX: Menghapus textarea untuk Deskripsi Diri -->
                                
                                <h5 class="mt-4 mb-3"><i class="fas fa-share-alt me-2"></i>Social Media</h5>
                                <div class="row">
                                    <!-- Kolom Social Media Anda ada di sini -->
                                     <div class="col-md-6 mb-3">
                                    <label class="form-label">LinkedIn URL</label>
                                    <input type="url" class="form-control" name="linkdin" 
                                           value="<?php echo htmlspecialchars($mentor['linkdin'] ?? ''); ?>" 
                                           placeholder="https://linkedin.com/in/username">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Instagram Username</label>
                                    <input type="text" class="form-control" name="instagram" 
                                           value="<?php echo htmlspecialchars($mentor['instagram'] ?? ''); ?>" 
                                           placeholder="username">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Twitter Username</label>
                                    <input type="text" class="form-control" name="twitter" 
                                           value="<?php echo htmlspecialchars($mentor['twitter'] ?? ''); ?>" 
                                           placeholder="username">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GitHub Username</label>
                                    <input type="text" class="form-control" name="github" 
                                           value="<?php echo htmlspecialchars($mentor['github'] ?? ''); ?>" 
                                           placeholder="username">
                                </div>

                                <div class="text-end">
                                    <button type="submit" name="update_profile" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Kelas Terbaru -->
                <div class="col-md-4">
                    <!-- Konten Kelas Terbaru Anda ada di sini -->
                </div>
            </div>
        </div>
    </div> <!-- Akhir .main-content -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
