<?php
session_start();
include_once('db.php');

$username = $_SESSION['username'] ?? null;
if (!$username) {
    header("Location: HalamanSignIn.php");
    exit();
}

$balasan_ke_komentar = isset($_POST['balasan_ke_komentar']) ? 1 : 0;
$komentar_baru = isset($_POST['komentar_baru']) ? 1 : 0;
$notifikasi_postingan_baru = isset($_POST['notifikasi_postingan_baru']) ? 1 : 0;

$query = "UPDATE tbuser SET balasan_ke_komentar = ?, komentar_baru = ?, notifikasi_postingan_baru = ? WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssis", $balasan_ke_komentar, $komentar_baru, $notifikasi_postingan_baru, $username);

if ($stmt->execute()) {
    header("Location: setting-notifikasi.php?update=success");
    exit();
} else {
    echo "Gagal memperbarui notifikasi.";
}
?>