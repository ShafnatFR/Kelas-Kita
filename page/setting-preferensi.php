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
$stmt = $conn->prepare("SELECT first_name, last_name, deskripsi, fotoProfil, bahasa, zona_waktu, balasan_ke_komentar FROM tbuser WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Ambil enum dari kolom bahasa
$resultBahasa = $conn->query("SHOW COLUMNS FROM tbuser LIKE 'bahasa'");
$rowBahasa = $resultBahasa->fetch_assoc();
preg_match("/^enum\((.*)\)$/", $rowBahasa['Type'], $matchesBahasa);
$bahasaOptions = array_map(fn($val) => trim($val, "'"), explode(',', $matchesBahasa[1]));

// Ambil enum dari kolom zona_waktu
$resultZona = $conn->query("SHOW COLUMNS FROM tbuser LIKE 'zona_waktu'");
$rowZona = $resultZona->fetch_assoc();
preg_match("/^enum\((.*)\)$/", $rowZona['Type'], $matchesZona);
$zonaOptions = array_map(fn($val) => trim($val, "'"), explode(',', $matchesZona[1]));

// Ambil data user dari database
$stmt = $conn->prepare("SELECT first_name, last_name, deskripsi, fotoProfil, bahasa, zona_waktu, balasan_ke_komentar FROM tbuser WHERE username = ?");
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
                        <a href="setting-prefeensi.php" class="btn btn-outline-primary active">Preferensi</a>
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
                            <h4 class="mb-4"><strong>Preferensi</strong></h4>
                        </div>
                        <form method="POST" action="update-preferensi.php">
                            <div class="mb-3">
                            <label for="bahasa" class="form-label">Bahasa</label>
                            <select name="bahasa" id="bahasa" class="form-select">
                                <?php foreach ($bahasaOptions as $bahasa): ?>
                                    <option value="<?= $bahasa ?>" <?= $user['bahasa'] === $bahasa ? 'selected' : '' ?>>
                                        <?= $bahasa ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="zona_waktu" class="form-label">Zona Waktu</label>
                            <select name="zona_waktu" id="zona_waktu" class="form-select">
                                <?php foreach ($zonaOptions as $zona): ?>
                                    <option value="<?= $zona ?>" <?= $user['zona_waktu'] === $zona ? 'selected' : '' ?>>
                                        <?= $zona ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- <div class="form-check form-switch form-check-reverse d-flex">
                                <div class="form-check-label mb-3">
                                    <input class="form-check-input" type="checkbox" name="balasan_ke_komentar" id="balasan_ke_komentar" value="1" <?= $user['balasan_ke_komentar'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="balasan_ke_komentar">
                                        Aktifkan notifikasi balasan ke komentar
                                    </label>
                                </div>
                            </div> -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-primary">Simpan Perubahan</button>
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
