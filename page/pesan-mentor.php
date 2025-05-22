<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php"); // Jika bukan mentor, alihkan ke halaman login
    exit();
}

// Mengambil transaksi yang dilakukan oleh murid untuk kelas yang dikelola oleh mentor
$stmt_messages = $conn->prepare("
    SELECT n.id_notifikasi , n.pesan_notif, u.username 
    FROM tb_notifikasi n
    JOIN tb_user u ON n.id_user = u.id_user
    WHERE n.id_user =? 
");
$stmt_messages->bind_param("i", $_SESSION['user_id']); // Menggunakan user_id yang disimpan dalam session
$stmt_messages->execute();
$messages_result = $stmt_messages->get_result(); // Ambil hasil dari query
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

  
    <!-- Pesan -->
        <div id="messages" class="section mt-5">
            <h2>Pesan</h2>
            <!-- Menampilkan pesan -->
            <ul>
                <?php while ($row = $messages_result->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($row['pesan_notif']) ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
    </div>

    <!-- Tambahkan link ke Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
