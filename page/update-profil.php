<?php
session_start();
include_once('db.php'); // Pastikan path benar

// Ambil username dari session
$username = $_SESSION['username'] ?? null;
if (!$username) {
    header('Location: HalamanSignIn.php');
    exit();
}

// Tangani form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    $fotoBaru = null;

    // Tangani upload foto jika ada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto'];
        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $namaFile = 'foto_' . $username . '_' . time() . '.' . $ext;
        $tujuan = '../upload/' . $namaFile; // folder upload sejajar dengan file ini

        if (move_uploaded_file($foto['tmp_name'], $tujuan)) {
            $fotoBaru = $namaFile;
        }
    }

    // Siapkan query dan parameter
    if ($fotoBaru) {
        $query = "UPDATE tbUser SET first_name = ?, last_name = ?, deskripsi = ?, fotoProfil = ? WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $first_name, $last_name, $deskripsi, $fotoBaru, $username);
    } else {
        $query = "UPDATE tbUser SET first_name = ?, last_name = ?, deskripsi = ? WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $first_name, $last_name, $deskripsi, $username);
    }

    if ($stmt->execute()) {
        header("Location: setting-profil.php");
        exit();
    } else {
        echo "Gagal memperbarui data.";
    }
}
?>