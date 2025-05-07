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
$stmt = $conn->prepare("SELECT username first_name, last_name, deskripsi, fotoProfil, bahasa, zona_waktu, balasan_ke_komentar, email, instagram, twitter, linkdin, github FROM tbuser WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="custom.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
        <title>Profil Siswa</title>
    </head>
    <body class="d-flex flex-column min-vh-100">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <main class="flex-grow-1 container my-4">
            <!-- Profil Siswa -->
            <div class="container my-5">
                <div class="card">
                <div class="row g-0">
                    <!-- Bagian Foto Profil -->
                    <div class="col-12 col-md-3 d-flex flex-column align-items-center pt-3">
                        <!-- Gambar di tengah -->
                        <div class="text-center">
                            <img src="../upload/<?= htmlspecialchars($user['fotoProfil']?? 'default.jpg') ?>"
                            class="img-fluid rounded-circle w-75 mb-2" alt="Profile Picture"
                            style="aspect-ratio: 1/1; object-fit:cover;">
                            <!-- problem
                            sudah di add username pada SQL, tetap tidak muncul. Sebagai alternatif menggunakan session -->
                            <h4 class="fw-bold mb-3"><?= htmlspecialchars($username) ?></4>
                        </div>
                        <!-- Tombol Rata Kanan Kiri -->
                        <div class="d-grid gap-2 w-75 mb-4">
                            <a href="setting-profil.php" class="btn btn-outline-primary">Profil</a>
                            <a href="setting-preferensi.php" class="btn btn-outline-primary">Preferensi</a>
                            <a href="setting-notifikasi.php" class="btn btn-outline-primary">Notifikasi</a>
                            <a href="setting-hubungkanAkun.php" class="btn btn-outline-primary">Hubungkan Akun</a>
                            <a href="setting-keluar.php" class="btn btn-outline-primary active">Keluar</a>
                            <a href="setting-tutupAkun.php" class="btn btn-outline-primary">Tutup Akun</a>
                        </div>
                    </div>
                    <!-- Bagian Informasi Profil -->
                    <div class="col-12 col-md-9 p-3">
                        <div class="card-body">
                            <div class="text-center">
                                <h3 class="mb-4"><strong>Keluar</strong></h3>
                            </div>
                                <p><b>Peringatan! Keluar</b> berarti anda akan <b>keluar atau logout</b> dari 
                                    website ini, anda akan diminta untuk <b>login</b> kembali jika anda memasuki 
                                    website ini lagi. Apa anda ingin keluar?
                                <div class="text-center">
                                    <a type="submit" class="btn btn-outline-primary" href="HalamanSignIn.php" >Ya</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </main>

        <!-- Footer -->

        <!-- end footer -->

    </body>
</html>