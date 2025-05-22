<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php"); // Jika bukan mentor, alihkan ke halaman login
    exit();
}

// Mengambil transaksi yang dilakukan oleh murid untuk kelas yang dikelola oleh mentor
$stmt_reviews = $conn->prepare("
    SELECT r.bintang_review, r.isi_review, r.tgl_review, u.username AS murid, k.nama_kelas
    FROM tb_review r
    JOIN tb_user u ON r.id_user = u.id_user
    JOIN tb_kelas k ON r.id_kelas = k.id_kelas
    JOIN tb_transaksi t ON t.id_kelas = k.id_kelas
    WHERE t.id_user = ?
");
$stmt_reviews->bind_param("i", $_SESSION['user_id']); // Menggunakan user_id yang disimpan dalam session
$stmt_reviews->execute();
$reviews_result = $stmt_reviews->get_result(); // Ambil hasil dari query
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar yang sudah didefinisikan sebelumnya -->
    <?php include 'sidebar.php'; ?>

   <!-- Review & Komentar -->
        <div id="reviews-comments" class="section mt-5">
            <h2>Review & Komentar</h2>
            <!-- Menampilkan review dan komentar -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Murid</th>
                        <th>Kelas</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP untuk menampilkan review dan komentar -->
                    <?php while ($row = $reviews_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['murid']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($row['rating']) ?></td>
                            <td><?= htmlspecialchars($row['komentar']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <!-- Tambahkan link ke Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
