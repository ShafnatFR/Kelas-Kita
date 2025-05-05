<?php
session_start();
include_once('db.php');

$username = $_SESSION['username'] ?? null;
if (!$username) {
    header("Location: HalamanSignIn.php");
    exit();
}

$bahasa = $_POST['bahasa'] ?? '';
$zona_waktu = $_POST['zona_waktu'] ?? '';
$balasan = isset($_POST['balasan_ke_komentar']) ? 1 : 0;

$query = "UPDATE tbuser SET bahasa = ?, zona_waktu = ?, balasan_ke_komentar = ? WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssis", $bahasa, $zona_waktu, $balasan, $username);

if ($stmt->execute()) {
    header("Location: setting-preferensi.php?update=success");
    exit();
} else {
    echo "Gagal memperbarui preferensi.";
}
?>