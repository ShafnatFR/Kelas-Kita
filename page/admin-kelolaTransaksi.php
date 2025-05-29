<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginAdmin.php"); // Jika bukan admin, alihkan ke halaman login
    exit();
}

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Query untuk mengambil total user dengan error handling
$totalUser = $conn->prepare("SELECT COUNT(*) as total_users FROM tb_user");
if (!$totalUser) {
    die("Error preparing statement: " . $conn->error);
}
$totalUser->execute();
$userResult = $totalUser->get_result();
$userData = $userResult->fetch_assoc();

// Query untuk mengambil data user untuk tabel - sesuaikan dengan kolom yang ada
$tb_user = $conn->prepare("
    SELECT id_user, 
           CASE 
               WHEN first_name IS NOT NULL AND last_name IS NOT NULL 
               THEN CONCAT(first_name, ' ', last_name)
               ELSE username
           END AS fullname, 
           username
    FROM tb_user
    ORDER BY id_user ASC
");
if (!$tb_user) {
    die("Error preparing user statement: " . $conn->error);
}
$tb_user->execute();
$tb_userResult = $tb_user->get_result();
$tb_userData = $tb_userResult->fetch_all(MYSQLI_ASSOC);

//QUERY BUAT KELAS
$tb_kelas = $conn->prepare("
    SELECT id_kelas, nama_kelas FROM tb_kelas
    ORDER BY id_kelas ASC
");
if (!$tb_kelas) {
    die("Error preparing kelas statement: " . $conn->error);
}
$tb_kelas->execute();
$tb_kelasResult = $tb_kelas->get_result();
$tb_kelasData = $tb_kelasResult->fetch_all(MYSQLI_ASSOC);

// Query untuk mengambil total kelas
$totalKelas = $conn->prepare("SELECT COUNT(*) as total_kelas FROM tb_kelas");
if (!$totalKelas) {
    die("Error preparing kelas statement: " . $conn->error);
}
$totalKelas->execute();
$kelasResult = $totalKelas->get_result();
$kelasData = $kelasResult->fetch_assoc();

// Query untuk mengambil total transaksi - disederhanakan jika tabel join bermasalah
$totalTransaksi = $conn->prepare("
    SELECT COALESCE(SUM(k.harga), 0) AS total_transaksi
    FROM tb_kelas k
    LEFT JOIN tb_keranjang kk ON kk.id_kelas = k.id_kelas
    LEFT JOIN tb_transaksi tk ON tk.id_keranjang = kk.id_keranjang
    WHERE tk.status = 'Completed'
");
if (!$totalTransaksi) {
    // Jika query join gagal, gunakan query sederhana
    $totalTransaksi = $conn->prepare("SELECT 0 AS total_transaksi");
}
$totalTransaksi->execute();
$transaksiResult = $totalTransaksi->get_result();
$transaksiData = $transaksiResult->fetch_assoc();

// Query untuk mengambil total materi
$totalMateri = $conn->prepare("SELECT COUNT(*) as total_materi FROM tb_materi");
if (!$totalMateri) {
    die("Error preparing materi statement: " . $conn->error);
}
$totalMateri->execute();
$materiResult = $totalMateri->get_result();
$materiData = $materiResult->fetch_assoc();

// Menyimpan data untuk ditampilkan
$stats = array(
    'total_users' => $userData['total_users'] ?? 0,
    'total_kelas' => $kelasData['total_kelas'] ?? 0,
    'total_materi' => $materiData['total_materi'] ?? 0
);

$transaksi = array(
    'total_transaksi' => $transaksiData['total_transaksi'] ?? 0
);

$namaAdmin = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        
        <div class="row">
            <!-- Sidebar -->
            <h2 class="mb-4">
                <?php
                    echo "Halo " . htmlspecialchars($namaAdmin) . ", selamat datang di dashboard admin!";
                ?>
            </h2>
            <div class="col-12 col-md-3 d-flex flex-column align-items-center pt-3">
                <div class="text-center">
                    <img
                        src="<?= (!empty($user['fotoProfil']) && file_exists('../upload/' . $user['fotoProfil'])) 
                            ? '../upload/' . htmlspecialchars($user['fotoProfil']) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=0D8ABC&color=fff&rounded=true&size=128' ?>" 
                        class="img-fluid rounded-circle w-75 mb-2"
                        style="aspect-ratio: 1/1; object-fit: cover;"
                        alt="Profile Picture">
                    <h4 class="fw-bold mb-3"><?= htmlspecialchars($namaAdmin) ?></h4>
                </div>
                <div class="d-grid gap-2 w-75 mb-4">
                    <a href="admin-dashboard.php" class="btn btn-outline-primary">Dashboard</a>
                    <a href="admin-kelolaUser.php" class="btn btn-outline-primary">Kelola User</a>
                    <a href="admin-kelolaKelas.php" class="btn btn-outline-primary">Kelola Kelas</a>
                    <a href="admin-kelolaMateri.php" class="btn btn-outline-primary">Kelola Materi</a>
                    <a href="admin-kelolaTransaksi.php" class="btn btn-outline-primary active">Kelola Transaksi</a>
                    <a href="adminLogout.php" class="btn btn-outline-primary">Logout</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-12 col-md-9 d-flex flex-column align-items-center pt-3">
                <!-- Stats Overview -->
                <div class="row w-100 mt-4">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chalkboard-teacher fa-2x me-3"></i>
                                    <div>
                                        <h5 class="card-title mb-0">Total Seluruh Transaksi</h5>
                                        <h5 class="mb-0"><?= htmlspecialchars($stats['total_kelas']) ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users fa-2x me-3"></i>
                                    <div>
                                        <h5 class="card-title mb-0">Total Transaksi Pending</h5>
                                        <h5 class="mb-0"><?= htmlspecialchars($stats['total_users']) ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-money-bill-wave fa-2x me-3"></i>
                                    <div>
                                        <h5 class="card-title mb-0">Total Transaksi Diterima</h5>
                                        <h5 class="mb-0">Rp<?= number_format($transaksi['total_transaksi'], 0, ',', '.') ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-book fa-2x me-3"></i>
                                    <div>
                                        <h5 class="card-title mb-0">Total Transaksi Ditolak</h5>
                                        <h5 class="mb-0"><?= htmlspecialchars($stats['total_materi']) ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel User dan Kelas -->
                <div class="row w-100">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-users fa-2x me-3"></i>Data User Terdaftar
                                </h5>
                            </div>
                            <div class="card-body">
                            
                                <?php if (count($tb_userData) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">ID User</th>
                                                    <th scope="col">Nama Lengkap</th>
                                                    <th scope="col">Username</th>
                                                    <th scope="col" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $no = 1;
                                                foreach ($tb_userData as $user): 
                                                ?>
                                                <tr>
                                                    <th scope="row"><?= $no++ ?></th>
                                                    <td><?= htmlspecialchars($user['id_user']) ?></td>
                                                    <td><?= htmlspecialchars($user['fullname']) ?></td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            <?= htmlspecialchars($user['username']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            <a href="edit_user.php?id=<?= $user['id_user'] ?>" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               title="Edit User">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            <a href="delete_user.php?id=<?= $user['id_user'] ?>" 
                                                               class="btn btn-sm btn-outline-danger" 
                                                               title="Hapus User"
                                                               onclick="return confirm('Apakah Anda yakin ingin menghapus user <?= htmlspecialchars($user['username']) ?>?\n\nTindakan ini tidak dapat dibatalkan!')">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Total: <?= count($tb_userData) ?> user terdaftar
                                        </small>
                                        <div>
                                            <button class="btn btn-sm btn-outline-success" onclick="location.reload()">
                                                <i class="fas fa-sync-alt me-1"></i>Refresh
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">Belum Ada User Terdaftar</h5>
                                        <p class="text-muted">Data user akan muncul di sini ketika ada user yang mendaftar</p>
                                        <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                                            <i class="fas fa-sync-alt me-1"></i>Refresh Data
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chalkboard-teacher fa-2x me-3"></i>Data Kelas Terdaftar
                                </h5>
                            </div>
                            <div class="card-body">
                            
                                <?php if (count($tb_kelasData) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">ID Kelas</th>
                                                    <th scope="col">Nama Kelas</th>
                                                    <th scope="col" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $no = 1;
                                                foreach ($tb_kelasData as $kelas): 
                                                ?>
                                                <tr>
                                                    <th scope="row"><?= $no++ ?></th>
                                                    <td><?= htmlspecialchars($kelas['id_kelas']) ?></td>
                                                    <td>
                                                        <span class="badge bg-success">
                                                            <?= htmlspecialchars($kelas['nama_kelas']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            <a href="edit_kelas.php?id=<?= $kelas['id_kelas'] ?>" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               title="Edit Kelas">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            <a href="delete_kelas.php?id=<?= $kelas['id_kelas'] ?>" 
                                                               class="btn btn-sm btn-outline-danger" 
                                                               title="Hapus Kelas"
                                                               onclick="return confirm('Apakah Anda yakin ingin menghapus kelas <?= htmlspecialchars($kelas['nama_kelas']) ?>?\n\nTindakan ini tidak dapat dibatalkan!')">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Total: <?= count($tb_kelasData) ?> kelas terdaftar
                                        </small>
                                        <div>
                                            <button class="btn btn-sm btn-outline-success" onclick="location.reload()">
                                                <i class="fas fa-sync-alt me-1"></i>Refresh
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">Belum Ada Kelas Terdaftar</h5>
                                        <p class="text-muted">Data kelas akan muncul di sini ketika ada kelas yang ditambahkan</p>
                                        <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                                            <i class="fas fa-sync-alt me-1"></i>Refresh Data
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="row w-100 mt-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Info Admin:</strong> Dashboard ini menampilkan statistik keseluruhan sistem. 
                            Sebagai admin, Anda memiliki akses penuh untuk mengelola semua data.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>