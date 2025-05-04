<?php
session_start();
include_once('../db.php'); // Sesuaikan path ke file db.php

// Contoh: user ID disimpan di session
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../HalamanSignIn.html');
    exit();
}

// Tangani form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaDepan = $_POST['namaDepan'] ?? '';
    $namaBelakang = $_POST['namaBelakang'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $prodi = $_POST['prodi'] ?? '';
    $fakultas = $_POST['fakultas'] ?? '';
    $universitas = $_POST['universitas'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    $fotoBaru = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto'];
        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $namaFile = 'foto_' . $user_id . '_' . time() . '.' . $ext;
        $tujuan = '../../upload/' . $namaFile;

        // Pindahkan file ke folder upload
        if (move_uploaded_file($foto['tmp_name'], $tujuan)) {
            $fotoBaru = $namaFile;
        }
    }

    // Siapkan query update
    $query = "UPDATE tbUser SET 
                namaDepan = ?, 
                namaBelakang = ?, 
                kelas = ?, 
                prodi = ?, 
                fakultas = ?, 
                universitas = ?, 
                deskripsi = ?";

    $params = [$namaDepan, $namaBelakang, $kelas, $prodi, $fakultas, $universitas, $deskripsi];

    // Jika ada foto baru, tambahkan ke query
    if ($fotoBaru !== '') {
        $query .= ", foto = ?";
        $params[] = $fotoBaru;
    }

    $query .= " WHERE idUser = ?";
    $params[] = $user_id;

    // Jalankan query
    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    header('Location: ../profil.php?update=success');
    exit();
}
?>
