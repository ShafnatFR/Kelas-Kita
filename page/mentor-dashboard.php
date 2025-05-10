<?php
session_start();
include_once('db.php');

// Cek jika pengguna sudah login dan sudah berrole mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: signIn.php");
    exit();
}

// Ambil data mentor berdasarkan username
$username = $_SESSION['username'];  // Menggunakan username dari session
$stmt = $conn->prepare("SELECT * FROM tbuser WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$mentor = $result->fetch_assoc();

// Ambil kursus yang diajarkan oleh mentor berdasarkan username
$courses = [];
$query = "SELECT * FROM tbkelas WHERE instructor = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);  // Menggunakan username sebagai instructor
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
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <!-- Profil Mentor -->
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <img src="<?= (!empty($mentor['fotoProfil']) && file_exists('../upload/' . $mentor['fotoProfil'])) 
                            ? '../upload/' . htmlspecialchars($mentor['fotoProfil']) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($mentor['username']) . '&background=0D8ABC&color=fff&rounded=true&size=128' ?>" 
                            alt="Foto Mentor" class="rounded-circle mb-3" width="120" height="120">
                        <h3 class="card-title"><?= htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']) ?></h3>
                        <p><strong>Keahlian:</strong> <?= isset($mentor['keahlian']) ? htmlspecialchars($mentor['keahlian']) : 'Belum ada keahlian yang diisi' ?></p>
                        <p><strong>Bio:</strong> <?= isset($mentor['bio']) ? htmlspecialchars($mentor['bio']) : 'Belum ada bio yang diisi' ?></p>
                        <a href="edit-profile.php" class="btn btn-warning btn-sm">Edit Profil</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Daftar Kursus yang Diajarkan -->
                <h2 class="mb-4">Kursus yang Diajarkan</h2>
                <div class="row">
                    <?php if (count($courses) > 0): ?>
                        <?php foreach ($courses as $course): ?>
                            <div class="col-md-6">
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                                        <p class="card-text"><?= substr(htmlspecialchars($course['description']), 0, 100) . '...' ?></p>
                                        <a href="course-detail2.php?id=<?= $course['id'] ?>" class="btn btn-primary btn-sm">Lihat Kursus</a>
                                        <a href="edit-course.php?id=<?= $course['id'] ?>" class="btn btn-secondary btn-sm">Edit Kursus</a>
                                        <a href="delete-course.php?id=<?= $course['id'] ?>" class="btn btn-danger btn-sm">Hapus Kursus</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
