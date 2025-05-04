<?php
session_start();
include_once('db.php'); // Sesuaikan path ke file db.php

// Cek apakah user sudah login
$user_id = $_SESSION['username'] ?? null;
if (!$user_id) {
    header('Location: HalamanSignIn.php');
    exit();
}

// Tangani form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    $fotoBaru = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto'];
        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $namaFile = 'foto_' . $user_id . '_' . time() . '.' . $ext;
        $tujuan = '../../upload/' . $namaFile;

        if (move_uploaded_file($foto['tmp_name'], $tujuan)) {
            $fotoBaru = $namaFile;
        }
    }

    $query = "UPDATE tbuser SET 
                first_name = ?, 
                last_name = ?, 
                deskripsi = ?";

    $params = [$first_name, $last_name, $deskripsi];

    if ($fotoBaru !== '') {
        $query .= ", foto = ?";
        $params[] = $fotoBaru;
    }

    $query .= " WHERE idUser = ?";
    $params[] = $user_id;

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    header('Location: ../profil.php?update=success');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="custom.css" rel="stylesheet">
    <title>Profil Siswa</title>
</head>
<body class="d-flex flex-column min-vh-100">
<main class="flex-grow-1 container my-4">
    <div class="container my-5">
        <div class="card">
            <div class="row g-0">
                <div class="col-12 col-md-3 d-flex flex-column align-items-center pt-3">
                    <div class="text-center">
                        <img src="fotoaliq.jpg" class="img-fluid rounded-circle w-75 mb-2" alt="Profile Picture">
                        <p class="fw-bold mb-3">Michael</p>
                    </div>
                    <div class="d-grid gap-2 w-75 mb-4">
                        <a href="setting-profil.html" class="btn btn-danger active">Profil</a>
                        <a href="setting-preferensi.html" class="btn btn-outline-danger">Preferensi</a>
                        <a href="setting-notifikasi.html" class="btn btn-outline-danger">Notifikasi</a>
                        <a href="setting-hubungkanAkun.html" class="btn btn-outline-danger">Hubungkan Akun</a>
                        <a href="setting-keluar.html" class="btn btn-outline-danger">Keluar</a>
                        <a href="setting-tutupAkun.html" class="btn btn-outline-danger">Tutup Akun</a>
                    </div>
                </div>
                <div class="col-12 col-md-9 p-3">
                    <div class="card-body">
                        <div class="card-title text-center">
                            <h4 class="mb-4"><strong>Profil Siswa</strong></h4>
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="namaDepan" class="form-label">Nama Depan</label>
                                <input type="text" name="first_name" class="form-control" id="namaDepan" placeholder="Michael">
                            </div>
                            <div class="mb-3">
                                <label for="namaBelakang" class="form-label">Nama Belakang</label>
                                <input type="text" name="last_name" class="form-control" id="namaBelakang" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3" placeholder="Deskripsi diri"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Profil</label>
                                <input type="file" name="foto" class="form-control" id="foto">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-danger">Ubah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
