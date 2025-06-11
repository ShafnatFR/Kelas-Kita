<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

// 1. Verifikasi sesi dan peran (role)
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id']; // ID User dari sesi

// 2. Dapatkan id_mentor berdasarkan id_user yang sedang login
$mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
$mentor_query->bind_param("i", $user_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

$id_mentor = 0; // Nilai default
if ($mentor_result->num_rows > 0) {
    $mentor_row = $mentor_result->fetch_assoc();
    $id_mentor = $mentor_row['id_mentor'];
}
$mentor_query->close();

// Menangani jika mentor tidak ditemukan
if ($id_mentor === 0) {
    // Anda bisa menghentikan skrip atau menampilkan pesan error di HTML
    // Contoh: die("Error: Tidak dapat menemukan data mentor untuk pengguna ini.");
}

// 3. Query utama untuk mengambil semua review dari semua kelas milik mentor ini
$stmt = $conn->prepare("
    SELECT 
        r.bintang_review,
        r.isi_review,
        r.tgl_review,
        u.first_name, u.last_name AS nama_peserta,
        u.fotoProfil, -- Pastikan nama kolom di database Anda adalah 'fotoProfil'
        k.nama_kelas
    FROM 
        tb_review AS r
    JOIN 
        tb_user AS u ON r.id_user = u.id_user
    JOIN 
        tb_kelas AS k ON r.id_kelas = k.id_kelas
    WHERE 
        k.id_mentor = ? -- Filter berdasarkan id_mentor yang didapat
    ORDER BY 
        r.tgl_review DESC
");

// PERBAIKAN 1: Menggunakan variabel $id_mentor yang benar (sebelumnya $id_mentor_saat_ini)
$stmt->bind_param("i", $id_mentor);
$stmt->execute();
$reviews_result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review & Komentar Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="../assets/css/sidebar-mentor.css"> 
</head>
<body>

    <?php include 'sidebar-mentor.php' ?>

    <main class="main-content">
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-12"> <div class="mb-5">
                        <h1 class="display-5">Ulasan & Komentar Peserta</h1>
                        <p class="lead text-muted">Berikut adalah semua ulasan yang masuk untuk semua kelas Anda.</p>
                    </div>

                    <?php if ($reviews_result && $reviews_result->num_rows > 0): ?>
                        <?php while ($review = $reviews_result->fetch_assoc()): ?>
                            <div class="card mb-4 shadow-sm">
                               <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                   </div>
                               <div class="card-body p-4">
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
                        if(isset($stmt)) $stmt->close();
                        if(isset($conn)) $conn->close();
                    ?>
                </div>
            </div>
        </div>

    </main> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>