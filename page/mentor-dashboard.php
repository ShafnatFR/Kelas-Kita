<?php
session_start();
include "db.php";

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM tbuser WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$mentor = $result->fetch_assoc();

// Verifikasi jika data mentor ditemukan
if (!$mentor) {
    echo "Data mentor tidak ditemukan.";
    exit();
}

// Ambil kursus yang diajarkan oleh mentor berdasarkan username
$courses = [];
$query = "SELECT k.id, k.title, k.description, COUNT(p.id) AS jumlah_peserta 
          FROM tbkelas k 
          LEFT JOIN tbuser p ON k.id = p.id
          WHERE k.instructor = ?
          GROUP BY k.id";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Proses Switch ke Peserta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ubah role kembali menjadi peserta
    $stmt = $conn->prepare("UPDATE tbuser SET role = 'peserta' WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();

    // Update session role
    $_SESSION['role'] = 'peserta';

    // Redirect kembali ke halaman utama
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/mentor.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-12 col-md-3 col-lg-2 sidebar bg-dark text-white p-4">
                <h4 class="text-center mb-4">KelasKita</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="mentor-dashboard.php" class="nav-link text-white">Dashboard</a></li>
                    <li class="nav-item"><a href="create-course.php" class="nav-link text-white">Buat Kursus</a></li>
                    <li class="nav-item"><a href="mentor-profil.php" class="nav-link text-white">Profil</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link text-white">Logout</a></li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-12 col-md-9 col-lg-10 main-content p-4">
                <h2 class="my-4">Dashboard Mentor</h2>

                <div class="row">
                    <!-- Daftar Kursus yang Diajarkan -->
                    <div class="col-12">
                        <h3 class="my-4">Kursus yang Diajarkan</h3>
                        <div class="row">
                            <?php if (count($courses) > 0): ?>
                                <?php foreach ($courses as $course): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                                                <p class="card-text"><?= substr(htmlspecialchars($course['description']), 0, 100) . '...' ?></p>
                                                <p><strong>Jumlah Peserta:</strong> <?= htmlspecialchars($course['jumlah_peserta']) ?></p>
                                                <a href="course-detail2.php?id=<?= $course['id'] ?>" class="btn btn-primary btn-sm">Lihat Kursus</a>
                                                <a href="edit-course.php?id=<?= $course['id'] ?>" class="btn btn-secondary btn-sm">Edit Kursus</a>
                                                <a href="delete-course.php?id=<?= $course['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">Hapus Kursus</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <p>Anda belum membuat kursus. <a href="create-course.php" class="btn btn-success btn-sm">Buat Kursus Baru</a></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <a href="create-course.php" class="btn btn-success btn-lg">Buat Kursus Baru</a>
                    </div>
                </div>

                <!-- Tombol Switch ke Peserta -->
                <form method="POST">
                    <button type="submit" class="btn btn-warning btn-lg mt-4">Switch to Peserta</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
