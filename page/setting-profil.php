<?php
session_start();
include_once('db.php'); // Pastikan path ke db.php sesuai

// Ambil username dari session
$username = $_SESSION['username'] ?? null;
if (!$username) {
    header('Location: HalamanSignIn.php');
    exit();
}

// Ambil data user dari database
$stmt = $conn->prepare("SELECT first_name, last_name, deskripsi, fotoProfil FROM tbuser WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="custom.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <title>Profil</title>
</head>
<body class="d-flex flex-column min-vh-100">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<main class="flex-grow-1 container my-4">
    <div class="container my-5">
        <div class="card">
            <div class="row g-0">
                <!-- Foto Profil -->
                <div class="col-12 col-md-3 d-flex flex-column align-items-center pt-3">
                    <div class="text-center">
                        <img src="../upload/<?= htmlspecialchars($user['fotoProfil'] ?? 'default.jpg') ?>" 
                        class="img-fluid rounded-circle w-75 mb-2"
                        style="aspect-ratio: 1/1; object-fit: cover;"
                        alt="Profile Picture">
                        <p class="fw-bold mb-3"><?= htmlspecialchars($user['username'] ?? '') ?></p>
                    </div>
                    <div class="d-grid gap-2 w-75 mb-4">
                        <a href="setting-profil.php" class="btn btn-primary active">Profil</a>
                        <a href="setting-preferensi.php" class="btn btn-outline-primary">Preferensi</a>
                        <a href="setting-notifikasi.php" class="btn btn-outline-primary">Notifikasi</a>
                        <a href="setting-hubungkanAkun.php" class="btn btn-outline-primary">Hubungkan Akun</a>
                        <a href="setting-keluar.php" class="btn btn-outline-primary">Keluar</a>
                        <a href="setting-tutupAkun.php" class="btn btn-outline-primary">Tutup Akun</a>
                    </div>
                </div>
                <!-- Informasi Profil -->
                <div class="col-12 col-md-9 p-3">
                    <div class="card-body">
                        <div class="card-title text-center">
                            <h4 class="mb-4"><strong>Profil Siswa</strong></h4>
                        </div>
                        <form method="POST" action="update-profil.php" enctype="multipart/form-data">
                            <div class="mb-2">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" placeholder="First Name">
                            </div>
                            <div class="mb-2">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" placeholder="Last Name">
                            </div>
                            <div class="mb-2">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                placeholder="Deskripsi diri"><?= htmlspecialchars($user['deskripsi'] ?? '') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Ganti Foto Profil</label>
                                <input type="file" class="form-control" id="foto" name="foto">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-primary">Ubah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
