<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

// Mengambil kelas yang dikelola oleh mentor berdasarkan id_user
$stmt_classes = $conn->prepare("
    SELECT k.id_kelas, k.nama_kelas, k.kategori, k.harga, k.description
    FROM tb_kelas k
    JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    JOIN tb_user u ON m.id_user = u.id_user
    WHERE u.id_user = ?
");
$stmt_classes->bind_param("i", $_SESSION['id']); // Fix: gunakan 'id' bukan 'user_id'
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
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <?php include 'sidebar-mentor.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Kelola Kelas</h2>
            <a href="create-class.php" class="btn btn-success">+ Tambah Kelas</a>
        </div>

        <!-- Tabel Kelas -->
        <div class="table-wrapper">
            <?php if ($classes_result->num_rows > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
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
                                    <a href="edit-class.php?id_kelas=<?= $row['id_kelas'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete-class.php?id_kelas=<?= $row['id_kelas'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Yakin ingin menghapus kelas ini?')">Delete</a>
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