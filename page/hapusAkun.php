<?php
session_start();
include_once('db.php');

$username = $_SESSION['username'] ?? null;
$konfirmasi = $_POST['konfirmasi_username'] ?? '';

if (!$username) {
    header("Location: HalamanSignIn.php");
    exit();
}

// Cek apakah input konfirmasi cocok dengan username session
if ($username !== $konfirmasi) {
    echo "<script>alert('Username yang diketik tidak cocok. Akun tidak dihapus.'); history.back();</script>";
    exit();
}

// Hapus akun dari database
$stmt = $conn->prepare("DELETE FROM tbuser WHERE username = ?");
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
    session_unset();
    session_destroy();
    header("Location: HalamanSignIn.php?akun=terhapus");
    exit();
} else {
    echo "Terjadi kesalahan saat menghapus akun.";
}
?>