<?php
session_start();
require 'db.php'; // Pastikan Anda sudah menghubungkan ke database

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: HalamanSignIn.php");
    exit();
}

$username = $_SESSION['username']; // Mengambil username dari session

// Mengambil ID pengguna dan role dari session
$stmt = $conn->prepare("SELECT id_user, role FROM tb_user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Pastikan role pengguna adalah 'murid' sebelum diubah
if ($user['role'] === 'murid') {
    $user_id = $user['id_user'];
    
    // Update role menjadi mentor
    $stmt_update = $conn->prepare("UPDATE tb_user SET role = 'mentor' WHERE id_user = ?");
    $stmt_update->bind_param("i", $user_id);
    if ($stmt_update->execute()) {
        $_SESSION['role'] = 'mentor'; // Menyimpan role baru di session
        header("Location: mentor-dashboard.php"); // Arahkan ke dashboard mentor setelah role diperbarui
        exit();
    } else {
        echo "Error: " . $stmt_update->error;
    }
} else {
    // Jika role tidak 'murid', arahkan ke halaman lain (misalnya halaman utama)
    header("Location: index.php");
    exit();
}
?>
