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

// Query ini adalah kuncinya.
// Kita menggabungkan tabel review, user (peserta), dan kelas.
// Lalu kita filter HANYA untuk kelas yang dimiliki oleh mentor yang sedang login (WHERE k.id_mentor = ?)
$stmt = $conn->prepare("
    SELECT 
        r.bintang_review,
        r.isi_review,
        r.tgl_review,
        u.nama_lengkap AS nama_peserta,
        u.fotoProfil,
        k.nama_kelas
    FROM 
        tb_review AS r
    JOIN 
        tb_user AS u ON r.id_user = u.id_user
    JOIN 
        tb_kelas AS k ON r.id_kelas = k.id_kelas
    WHERE 
        k.id_mentor = ?
    ORDER BY 
        r.tgl_review DESC
");

$stmt->bind_param("i", $id_mentor_saat_ini);
$stmt->execute();
$reviews_result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review & Komentar Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
   <?php include 'sidebar-mentor.php' ?>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="mb-5">
                    <h1 class="display-5">Ulasan & Komentar Peserta</h1>
                    <p class="lead text-muted">Berikut adalah semua ulasan yang masuk untuk semua kelas Anda.</p>
                </div>

                <?php if ($reviews_result->num_rows > 0): ?>
                    <?php while ($review = $reviews_result->fetch_assoc()): ?>
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="bi bi-journal-text me-2"></i>
                                    Ulasan untuk Kelas: 
                                    <strong class="text-primary"><?= htmlspecialchars($review['nama_kelas']) ?></strong>
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <img src="<?= htmlspecialchars($review['foto_profil'] ?? 'assets/default-avatar.png') ?>" 
                                         class="rounded-circle me-3" width="50" height="50" alt="Avatar" style="object-fit: cover;">
                                    
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title mb-0 fw-bold"><?= htmlspecialchars($review['nama_peserta']) ?></h6>
                                                <small class="text-muted"><?= date('d F Y, H:i', strtotime($review['tgl_review'])) ?></small>
                                            </div>
                                            <div class="text-end">
                                                <?php
                                                $bintang = (int)$review['bintang_review'];
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo '<i class="bi bi-star'.($i <= $bintang ? '-fill' : '').' text-warning fs-5"></i>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <?php if (!empty(trim($review['isi_review']))): ?>
                                            <p class="card-text mt-2">
                                                <?= nl2br(htmlspecialchars($review['isi_review'])) ?>
                                            </p>
                                        <?php else: ?>
                                            <p class="card-text mt-2 text-muted fst-italic">
                                                (Peserta tidak memberikan komentar tulisan)
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class='text-center p-5 bg-light rounded'>
                        <i class='bi bi-chat-square-quote display-1 text-muted'></i>
                        <h4 class='mt-3'>Belum Ada Ulasan</h4>
                        <p class='text-muted'>Saat ini belum ada ulasan yang masuk dari peserta di kelas-kelas Anda.</p>
                    </div>
                <?php endif; ?>
                
                <?php
                    $stmt->close();
                    $conn->close();
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>