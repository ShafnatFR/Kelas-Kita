<?php
session_start();
include_once('db.php');

// Ambil ID kursus dari URL
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validasi ID kursus
if ($course_id <= 0) {
    echo "Kursus tidak ditemukan.";
    exit;
}

// Cek apakah pengunjung sudah dihitung selama sesi ini
if (!isset($_SESSION['visitor_added'])) {
    // Jika belum dihitung, tambahkan 1 ke jumlah pengunjung
    $query = "UPDATE tbkelas SET visitor_count = visitor_count + 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);

    // Tandai bahwa pengunjung telah dihitung dalam sesi ini
    $_SESSION['visitor_added'] = true;
}

// Ambil data kursus dari database
$query = "SELECT * FROM tbkelas WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$course = mysqli_fetch_assoc($result);

// Jika kursus tidak ditemukan
if (!$course) {
    echo "Kursus tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kursus - <?= htmlspecialchars($course['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <!-- Detail Kursus -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title"><?= htmlspecialchars($course['title']) ?></h1>
                <h4 class="card-subtitle mb-3 text-muted"><?= htmlspecialchars($course['kategori']) ?></h4>
                
                <!-- Deskripsi Kursus -->
                <p class="card-text"><strong>Deskripsi:</strong> <?= htmlspecialchars($course['description']) ?></p>

                <!-- Harga Kursus -->
                <p class="card-text"><strong>Harga:</strong> Rp. <?= number_format($course['price'], 2, ',', '.') ?></p>

                <!-- Instructor -->
                <p class="card-text"><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor']) ?></p>

                <!-- Tanggal Kursus -->
                <?php if ($course['start_date'] && $course['end_date']): ?>
                    <p><strong>Jadwal Kursus:</strong> <?= htmlspecialchars($course['start_date']) ?> - <?= htmlspecialchars($course['end_date']) ?></p>
                <?php else: ?>
                    <p><strong>Jenis Kursus:</strong> Kursus Fleksibel (Dapat Diakses Kapan Saja)</p>
                <?php endif; ?>

                <!-- Materi Kursus -->
                <h5>Materi Kursus:</h5>
                <ul>
                    <?php
                    $materials = explode(",", $course['materials']);  // Misal, materi disimpan sebagai string yang dipisahkan koma
                    foreach ($materials as $material) {
                        echo "<li><a href='" . htmlspecialchars($material) . "' target='_blank'>" . htmlspecialchars(basename($material)) . "</a></li>";
                    }
                    ?>
                </ul>

                <!-- Menampilkan Jumlah Pengunjung dan Peserta -->
                <p><strong>Jumlah Pengunjung:</strong> <?= $course['visitor_count'] ?></p>
                <p><strong>Jumlah Peserta:</strong> <?= $course['participant_count'] ?></p>

                <!-- Edit dan Hapus Kursus (Jika Mentor yang Mengajar) -->
                <?php if ($_SESSION['username'] == $course['instructor']): ?>
                    <a href="edit-course.php?id=<?= $course['id'] ?>" class="btn btn-warning btn-sm">Edit Kursus</a>
                    <a href="delete-course.php?id=<?= $course['id'] ?>" class="btn btn-danger btn-sm">Hapus Kursus</a>
                <?php else: ?>
                    <!-- Daftar Kursus (Jika Peserta) -->
                    <a href="enroll.php?id=<?= $course['id'] ?>" class="btn btn-success btn-sm">Daftar Kursus</a>
                <?php endif; ?>

                <a href="mentor-dashboard.php" class="btn btn-primary btn-sm mt-3">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
