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
$mentor_query = $conn->prepare("
    SELECT 
        u.id_user, u.nama_lengkap, u.username, u.email, u.no_telepon, u.fotoProfil, u.instagram, u.linkdin, u.twitter, u.github,
        m.id_mentor, m.keahlian, m.pengalaman, m.deskripsi, m.website_url, m.status
    FROM tb_user u 
    JOIN tb_mentor m ON u.id_user = m.id_user 
    WHERE u.id_user = ?
");
$mentor_query->bind_param("i", $user_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

if ($mentor_result->num_rows === 0) {
    $message = "Data mentor tidak ditemukan.";
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
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon']; // Disesuaikan dengan struktur tabel
    $keahlian = $_POST['keahlian'];
    $pengalaman = $_POST['pengalaman'];
    $deskripsi = $_POST['deskripsi'];
    $linkdin = $_POST['linkdin']; // Disesuaikan dengan struktur tabel (ada typo di database)
    $instagram = $_POST['instagram'];
    $twitter = $_POST['twitter'];
    $github = $_POST['github'];
    $website_url = $_POST['website_url'];
    
    // Update data user (sesuai struktur tabel tb_user)
    $update_user = $conn->prepare("UPDATE tb_user SET nama_lengkap = ?, email = ?, no_telepon = ?, linkdin = ?, instagram = ?, twitter = ?, github = ? WHERE id_user = ?");
    $update_user->bind_param("ssissssi", $nama_lengkap, $email, $no_telepon, $linkdin, $instagram, $twitter, $github, $user_id);
    
    // Update data mentor (sesuai struktur tabel tb_mentor)
    $update_mentor = $conn->prepare("UPDATE tb_mentor SET keahlian = ?, pengalaman = ?, deskripsi = ?, website_url = ? WHERE id_user = ?");
    $update_mentor->bind_param("ssssi", $keahlian, $pengalaman, $deskripsi, $website_url, $user_id);
    
    if ($update_user->execute() && $update_mentor->execute()) {
        $message = "Profil berhasil diperbarui!";
        
        // Tutup prepared statements
        $update_user->close();
        $update_mentor->close();
        
        // Refresh data
        header("Location: mentor-profil.php?msg=" . urlencode($message));
        exit();
    } else {
        $message = "Terjadi kesalahan saat memperbarui profil: " . $conn->error;
        
        // Tutup prepared statements
        $update_user->close();
        $update_mentor->close();
    }
}

// Ambil pesan dari URL
if (isset($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mentor - <?= htmlspecialchars($mentor['nama_lengkap']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/mentor-profil.css">
</head>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mentor - Kelas Kita</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/mentor-profil.css">    
</head>
<body class="bg-light">
    <?php include "sidebar-mentor.php"?>
    <!-- Header Profile -->
    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="<?php echo !empty($mentor['fotoProfil']) ? '../uploads/profile/' . $mentor['fotoProfil'] : '../assets/default-avatar.png'; ?>" 
                         alt="Profile" class="profile-img">
                </div>
                <div class="col-md-9">
                    <h2><?php echo htmlspecialchars($mentor['nama_lengkap']); ?></h2>
                    <p class="mb-2"><i class="fas fa-user"></i> @<?php echo htmlspecialchars($mentor['username']); ?></p>
                    <p class="mb-2"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($mentor['email']); ?></p>
                    <p class="mb-3"><i class="fas fa-tools"></i> <?php echo htmlspecialchars($mentor['keahlian'] ?? 'Belum diisi'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <!-- Alert Message -->
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
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <div class="stat-number"><?php echo $stats['total_kelas'] ?? 0; ?></div>
                            <div class="text-muted">Total Kelas</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <div class="stat-number"><?php echo $stats['kelas_approved'] ?? 0; ?></div>
                            <div class="text-muted">Kelas Disetujui</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <div class="stat-number"><?php echo $stats['kelas_pending'] ?? 0; ?></div>
                            <div class="text-muted">Kelas Pending</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <div class="stat-number"><?php echo $stats['kelas_draft'] ?? 0; ?></div>
                            <div class="text-muted">Draft Kelas</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Edit Profil -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Profil</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <!-- Data Pribadi -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap" 
                                           value="<?php echo htmlspecialchars($mentor['nama_lengkap']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="<?php echo htmlspecialchars($mentor['email']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="number" class="form-control" name="no_telepon" 
                                           value="<?php echo htmlspecialchars($mentor['no_telepon']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Keahlian</label>
                                    <input type="text" class="form-control" name="keahlian" 
                                           value="<?php echo htmlspecialchars($mentor['keahlian'] ?? ''); ?>" 
                                           placeholder="Contoh: Web Development, UI/UX Design">
                                </div>
                            </div>

                            <!-- Pengalaman & Deskripsi -->
                            <div class="mb-3">
                                <label class="form-label">Pengalaman</label>
                                <textarea class="form-control" name="pengalaman" rows="4" 
                                          placeholder="Ceritakan pengalaman profesional Anda..."><?php echo htmlspecialchars($mentor['pengalaman'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Diri</label>
                                <textarea class="form-control" name="deskripsi" rows="4" 
                                          placeholder="Perkenalkan diri Anda kepada calon siswa..."><?php echo htmlspecialchars($mentor['deskripsi'] ?? ''); ?></textarea>
                            </div>

                            <!-- Social Media -->
                            <h5 class="mt-4 mb-3"><i class="fas fa-share-alt me-2"></i>Social Media</h5>
                            <div class="row">
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
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Website URL</label>
                                    <input type="url" class="form-control" name="website_url" 
                                           value="<?php echo htmlspecialchars($mentor['website_url'] ?? ''); ?>" 
                                           placeholder="https://yourwebsite.com">
                                </div>
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
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Kelas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_classes->num_rows > 0): ?>
                            <?php while ($class = $recent_classes->fetch_assoc()): ?>
                                <div class="class-card card mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="card-title"><?php echo htmlspecialchars($class['nama_kelas']); ?></h6>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge-category"><?php echo htmlspecialchars($class['kategori']); ?></span>
                                            <small class="text-muted">
                                                <?php echo $class['tgl_dibuat'] ? date('d M Y', strtotime($class['tgl_dibuat'])) : 'Belum dirilis'; ?>
                                            </small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-primary">Rp <?php echo number_format($class['harga'], 0, ',', '.'); ?></strong>
                                            <span class="badge <?php 
                                                echo $class['status_publikasi'] == 'approved' ? 'bg-success' : 
                                                    ($class['status_publikasi'] == 'pending' ? 'bg-warning' : 'bg-secondary'); 
                                            ?>">
                                                <?php echo ucfirst($class['status_publikasi']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                                <p>Belum ada kelas yang dibuat</p>
                                <a href="create-class.php" class="btn btn-primary btn-sm">Buat Kelas Pertama</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="create-class.php" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-2"></i>Buat Kelas Baru
                            </a>
                            <a href="my-classes.php" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>Kelola Kelas
                            </a>
                            <a href="settings.php" class="btn btn-outline-info">
                                <i class="fas fa-cog me-2"></i>Pengaturan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>