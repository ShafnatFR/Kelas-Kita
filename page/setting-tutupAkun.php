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
                        <img
                        src="<?= (!empty($user['fotoProfil']) && file_exists('../upload/' . $user['fotoProfil'])) 
                            ? '../upload/' . htmlspecialchars($user['fotoProfil']) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=0D8ABC&color=fff&rounded=true&size=128' ?>" 
                        class="img-fluid rounded-circle w-75 mb-2"
                        style="aspect-ratio: 1/1; object-fit: cover;"
                        alt="Profile Picture">
                        <h4 class="fw-bold mb-3"><?= htmlspecialchars($username) ?></4>
                    </div>
                    <div class="d-grid gap-2 w-75 mb-4">
                        <a href="setting-profil.php" class="btn btn-outline-primary">Profil</a>
                        <a href="setting-preferensi.php" class="btn btn-outline-primary">Preferensi</a>
                        <a href="setting-notifikasi.php" class="btn btn-outline-primary">Notifikasi</a>
                        <a href="setting-hubungkanAkun.php" class="btn btn-outline-primary">Hubungkan Akun</a>
                        <a href="setting-keluar.php" class="btn btn-outline-primary">Keluar</a>
                        <a href="setting-tutupAkun.php" class="btn btn-outline-primary active">Hapus Akun</a>
                    </div>
                </div>
                <!-- Bagian Informasi Profil -->
                <div class="col-12 col-md-9 p-3">
                    <div class="card-body">
                        <div class="text-center">
                            <h3 class="mb-4"><strong>Hapus Akun</strong></h3>
                        </div>
                        <div class="accordion-body text-center mx-5 px-5 mt-1">
                            <p>
                                Untuk memastikan anda benar-benar memutuskan untuk menutup atau menghapus akun ini, silakan isi ulang username anda di bawah ini.
                            </p>
                            <form method="POST" action="hapusAkun.php" enctype="multipart/form-data">
                                <div class="mb-0">
                                    <input type="text" name="konfirmasi_username" class="form-control" id="konfirmasi_username" placeholder="<?= htmlspecialchars($username) ?>" required>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-outline-primary">Hapus Akun</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
