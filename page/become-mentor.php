<?php
session_start();
include "db.php"; // Pastikan koneksi ke database sudah benar

// Pastikan pengguna sudah login dan memiliki role sebagai murid
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'murid') {
    header("Location: HalamanSignIn.php"); // Jika bukan murid, alihkan ke halaman login
    exit();
}

// Update role menjadi mentor jika pengguna mengonfirmasi
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['confirm'])) {
    $user_id = $_SESSION['id'];
    $stmt = $conn->prepare("UPDATE tb_user SET role = 'mentor' WHERE id_user = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Set role baru di session
        $_SESSION['role'] = 'mentor';
        header("Location: mentor-dashboard.php"); // Redirect ke halaman dashboard mentor
        exit();
    } else {
        // Jika ada kesalahan
        $error_message = "Gagal mengubah role menjadi mentor.";
    }
}
?>

<!-- Halaman untuk konfirmasi perubahan role -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Menjadi Mentor</title>
</head>
<body>
    <h3>Apakah Anda yakin ingin menjadi Mentor?</h3>
    <form action="become-mentor.php?confirm=true" method="GET">
        <button type="submit">Ya, Saya Yakin</button>
    </form>
    <a href="index.php">Batal</a>
</body>
</html>
