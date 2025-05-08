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

// // Ambil enum dari kolom bahasa
// $resultBahasa = $conn->query("SHOW COLUMNS FROM tbuser LIKE 'bahasa'");
// $rowBahasa = $resultBahasa->fetch_assoc();
// preg_match("/^enum\((.*)\)$/", $rowBahasa['Type'], $matchesBahasa);
// $bahasaOptions = array_map(fn($val) => trim($val, "'"), explode(',', $matchesBahasa[1]));

// // Ambil enum dari kolom zona_waktu
// $resultZona = $conn->query("SHOW COLUMNS FROM tbuser LIKE 'zona_waktu'");
// $rowZona = $resultZona->fetch_assoc();
// preg_match("/^enum\((.*)\)$/", $rowZona['Type'], $matchesZona);
// $zonaOptions = array_map(fn($val) => trim($val, "'"), explode(',', $matchesZona[1]));

// // Ambil data user dari database
// $stmt = $conn->prepare("SELECT first_name, last_name, deskripsi, fotoProfil, bahasa, zona_waktu, balasan_ke_komentar FROM tbuser WHERE username = ?");
// $stmt->bind_param("s", $username);
// $stmt->execute();
// $user = $stmt->get_result()->fetch_assoc();
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

        <!-- header -->

        <!-- end header -->

        <!-- content -->
        <main class="flex-grow-1 container my-4">
            <!-- Profil Siswa -->
            <div class="container my-5">
                <div class="card">
                <div class="row g-0">
                    <!-- Bagian Foto Profil -->
                    <div class="col-12 col-md-3 d-flex flex-column align-items-center pt-3">
                        <!-- Gambar di tengah -->
                        <div class="text-center">
                            <img
                            src="<?= (!empty($user['fotoProfil']) && file_exists('../upload/' . $user['fotoProfil'])) 
                                ? '../upload/' . htmlspecialchars($user['fotoProfil']) 
                                : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=0D8ABC&color=fff&rounded=true&size=128' ?>" 
                            class="img-fluid rounded-circle w-75 mb-2"
                            style="aspect-ratio: 1/1; object-fit: cover;"
                            alt="Profile Picture">
                            <!-- problem
                            sudah di add username pada SQL, tetap tidak muncul. Sebagai alternatif menggunakan session -->
                            <h4 class="fw-bold mb-3"><?= htmlspecialchars($username) ?></4>
                        </div>
                        <!-- Tombol Rata Kanan Kiri -->
                        <div class="d-grid gap-2 w-75 mb-4">
                            <a href="setting-profil.php" class="btn btn-outline-primary">Profil</a>
                            <a href="setting-preferensi.php" class="btn btn-outline-primary">Preferensi</a>
                            <a href="setting-notifikasi.php" class="btn btn-outline-primary">Notifikasi</a>
                            <a href="setting-hubungkanAkun.php" class="btn btn-outline-primary active">Hubungkan Akun</a>
                            <a href="setting-keluar.php" class="btn btn-outline-primary">Keluar</a>
                            <a href="setting-tutupAkun.php" class="btn btn-outline-primary">Tutup Akun</a>
                        </div>
                    </div>
                    <!-- Bagian Informasi Profil -->
                    <div class="col-12 col-md-9 p-3">
                        <div class="card-body">
                            <div class="card-title text-center">
                                <h4 class="mb-4"><strong>Hubungkan Akun</strong></h4>
                            </div>
                            <form method="POST" action="update-hubungkanAkun.php" enctype="multipart/form-data">
                                <div class="mb-0">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email"
                                    name="email" value="<?= htmlspecialchars($user['email'] ?? '')?>" placeholder="Email">
                                </div>
                                <div class="mb-0">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" class="form-control" id="instagram"
                                    name="instagram" value="<?= htmlspecialchars($user['instagram'] ?? '')?>" placeholder="Instagram">
                                </div>
                                <div class="mb-0">
                                    <label for="twitter" class="form-label">Twitter</label>
                                    <input type="text" class="form-control" id="twitter"
                                    name="twitter" value="<?= htmlspecialchars($user['twitter'] ?? '')?>" placeholder="">
                                </div>
                                <div class="mb-0">
                                    <label for="linkdin" class="form-label">Linkedln</label>
                                    <input type="text" class="form-control" id="linkdin"
                                    name="linkdin" value="<?= htmlspecialchars($user['linkdin'] ?? '')?>" placeholder="">
                                </div>
                                <div class="mb-0">
                                    <label for="github" class="form-label">Github</label>
                                    <input type="text" class="form-control" id="github"
                                    name="github" value="<?= htmlspecialchars($user['github'])?>" placeholder="">
                                </div>
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-outline-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- end content -->\

    <!-- footer -->
    <!-- end footer -->
</body>
</html>