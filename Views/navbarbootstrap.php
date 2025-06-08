<?php
// Create a new mysqli connection inside navbarbootstrap.php to avoid dependency on external connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "KelasKita_baru";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal di navbarbootstrap.php: " . $conn->connect_error);
}

// Pastikan ada session yang sudah login

// session_start();  // Removed session_start from navbarbootstrap.php as per user request

// Pastikan role diambil dari database dan diset dengan benar
$stmt = $conn->prepare("SELECT role FROM tb_user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Set role di session jika belum ada
if (isset($user['role'])) {
    $_SESSION['role'] = $user['role'];
}

?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand text-primary fw-bold" href="#">KelasKita</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active fw-medium" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="kursus.php">Kursus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="kategori.php">Kategori</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="blog.php">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="contackUs.php">Kontak</a>
                </li>
                <li class="nav-item">
                    <a href="cart.php" class="nav-link text-secondary position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if(!empty($_SESSION['cart'])): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo count($_SESSION['cart']); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>

            <!-- Dropdown Profil -->
            <div class="d-flex align-items-center gap-2">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="dropdown">
    <a href="#" role="button" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
        <img 
            src="<?= (!empty($user['fotoProfil']) && file_exists('../upload/' . $user['fotoProfil'])) 
                ? '../upload/' . htmlspecialchars($user['fotoProfil']) 
                : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=0D8ABC&color=fff&rounded=true&size=64' ?>" 
            alt="Profile" 
            class="rounded-circle" width="32" height="32">
    </a>
    <ul class="dropdown-menu dropdown-menu-end mt-2 shadow" aria-labelledby="dropdownProfile" style="min-width: 250px;">
        <li class="px-3 py-2 border-bottom d-flex align-items-center">
            <img 
                src="<?= (!empty($user['fotoProfil']) && file_exists('../upload/' . $user['fotoProfil'])) 
                    ? '../upload/' . htmlspecialchars($user['fotoProfil']) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=0D8ABC&color=fff&rounded=true&size=64' ?>" 
                alt="Profile" 
                class="rounded-circle me-2" width="32" height="32">
            <div>
                <div class="fw-semibold"><?= htmlspecialchars($_SESSION['username']) ?></div>
                <small class="text-muted text-truncate d-block" style="max-width: 160px;"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></small>
            </div>
        </li>
        <li><a class="dropdown-item" href="setting-profil.php">KelasKu</a></li>
        <?php if ($_SESSION['role'] !== 'mentor'): ?>
            <!-- Menampilkan opsi "Instruktur" hanya jika role pengguna adalah peserta -->
            <li><a class="dropdown-item" href="become-mentor.php">Instruktur</a></li>
        <?php endif; ?>
        <li><a class="dropdown-item" href="keranjang.php">Keranjang</a></li>
        <li><a class="dropdown-item" href="setting-profil.php">Pengaturan Profil</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
    </ul>
</div>
                <?php else: ?>
                    <a href="HalamanSignIn.php" class="btn btn-outline-secondary d-none d-md-inline-block">Masuk</a>
                    <a href="HalamanSignUp.php" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Tambahkan link ke Bootstrap JS jika belum ada -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>