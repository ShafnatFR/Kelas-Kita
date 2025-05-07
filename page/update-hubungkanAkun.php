<?php
session_start();
include_once('db.php');

$username = $_SESSION['username'] ?? null;
if (!$username) {
    header("Location: HalamanSignIn.php");
    exit();
}

$email = $_POST['email'] ?? '';
$instagram = $_POST['instagram'] ?? '';
$twitter = $_POST['twitter'] ?? '';
$linkdin = $_POST['linkdin'] ?? '';
$github = $_POST['github'] ?? '';

$query = "UPDATE tbuser SET email = ?, instagram = ?, twitter = ?, linkdin = ?, github = ? WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssis", $email, $instagram, $twitter, $linkdin, $github);

if ($stmt->execute()){
    header("Location: setting-hubungkanAkun.php?update=success");
    exit();
} else {
    echo "Gagal memperbaharui setting hubungkan akun.";
}
?>