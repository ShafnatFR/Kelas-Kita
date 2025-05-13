<?php
session_start();
include_once('db.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: HalamanSignIn.php");
    exit();
}

$username = $_SESSION['username'];

// Jika pengguna memilih "Ya", ubah role menjadi mentor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update role pengguna menjadi 'mentor'
    $stmt = $conn->prepare("UPDATE tbuser SET role = 'mentor' WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Set role baru di session
    $_SESSION['role'] = 'mentor';

    // Redirect ke dashboard mentor
    header("Location: mentor-dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menjadi Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h2>Apakah Anda ingin menjadi Mentor?</h2>
        <p>Anda akan mendapatkan akses untuk membuat kursus dan mengajar. Setelah menjadi mentor, Anda bisa mengakses dashboard mentor Anda.</p>
        
        <form method="POST">
            <button type="submit" class="btn btn-success">Ya, Saya Ingin Menjadi Mentor</button>
            <a href="index.php" class="btn btn-danger">Tidak, Kembali ke Halaman Utama</a>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
