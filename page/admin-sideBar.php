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
        <h4 class="fw-bold mb-3"><?= htmlspecialchars($namaAdmin) ?></4>
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