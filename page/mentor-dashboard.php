<?php
session_start();
include_once('db.php');

// Cek jika pengguna sudah login dan sudah berrole mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: signIn.php");
    exit();
}

// Ambil data mentor berdasarkan username
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM tbuser WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$mentor = $result->fetch_assoc();

if (!$mentor) {
    echo "Data mentor tidak ditemukan.";
    exit();
}

$courses = [];
$query = "SELECT * FROM tbkelas WHERE instructor = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css//mentor.css">
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <div class="sidebar">
        <h4>KelasKita</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="mentor-dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="create-course.php">Buat Kursus</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="mentor-profil.php">Profil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Sidebar Toggle (for Mobile) -->
    <div class="sidebar-toggle">
        <span class="text-white">&#9776; Menu</span>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container py-5">
            <div class="row">
                <!-- Profil Mentor -->
                <div class="col-md-4">
                    <div class="card-profile">
                        <img src="<?= (!empty($mentor['fotoProfil']) && file_exists('../upload/' . $mentor['fotoProfil'])) 
                                        ? '../upload/' . htmlspecialchars($mentor['fotoProfil']) 
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($mentor['username']) . '&background=0D8ABC&color=fff&rounded=true&size=128' ?>" 
                            alt="Foto Mentor" class="rounded-circle mb-3" width="120" height="120">
                        <h3 class="card-title"><?= htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']) ?></h3>
                        <p><strong>Keahlian:</strong> <?= !empty($mentor['keahlian']) ? htmlspecialchars($mentor['keahlian']) : 'Belum ada keahlian yang diisi' ?></p>
                        <p><strong>Bio:</strong> <?= !empty($mentor['bio']) ? htmlspecialchars($mentor['bio']) : 'Belum ada bio yang diisi' ?></p>
                        <a href="edit-profile.php" class="btn btn-warning btn-sm">Edit Profil</a>
                    </div>
                </div>

                <!-- Daftar Kursus yang Diajarkan -->
                <div class="col-md-8">
                    <h2 class="mb-4">Kursus yang Diajarkan</h2>
                    <div class="row">
                        <?php if (count($courses) > 0): ?>
                            <?php foreach ($courses as $course): ?>
                                <div class="col-md-6 col-lg-4 card-course">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                                            <p class="card-text"><?= substr(htmlspecialchars($course['description']), 0, 100) . '...' ?></p>
                                            <a href="course-detail.php?id=<?= $course['id'] ?>" class="btn btn-primary btn-sm">Lihat Kursus</a>
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
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar untuk mobile
        const toggleButton = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    </script>
</body>
</html>
