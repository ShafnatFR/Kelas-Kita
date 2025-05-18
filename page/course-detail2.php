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

// Menangani sub-bab materi yang disertakan oleh mentor
$sub_babs = json_decode($course['sub_babs'], true); // Memastikan materi disimpan sebagai JSON
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kursus - <?= htmlspecialchars($course['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sub-bab-container {
            margin-top: 20px;
        }
        .sub-bab-item {
            margin-bottom: 10px;
        }
        .sub-bab-item button {
            margin-top: 10px;
        }
        .sub-bab-content {
            margin-top: 15px;
        }
        .card-body p {
            font-size: 1.1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
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

                <!-- Sub-Bab Materi -->
                <div class="sub-bab-container">
                    <h5>Materi Kursus:</h5>

                    <?php if (!empty($sub_babs)): ?>
                        <?php foreach ($sub_babs as $index => $sub_bab): ?>
                            <div class="sub-bab-item">
                                <button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#sub_bab_<?= $index ?>" aria-expanded="false" aria-controls="sub_bab_<?= $index ?>">
                                    <?= htmlspecialchars($sub_bab['title']) ?>
                                </button>
                                <div id="sub_bab_<?= $index ?>" class="collapse sub-bab-content">
                                    <p><strong>Materi:</strong></p>
                                    <p><?= htmlspecialchars($sub_bab['content']) ?></p>
                                    <p><strong>File Materi:</strong></p>
                                    <?php if (isset($sub_bab['materials']) && !empty($sub_bab['materials'])): ?>
                                        <ul>
                                            <?php
                                            $materials = explode(",", $sub_bab['materials']);
                                            foreach ($materials as $material) {
                                                echo "<li><a href='" . htmlspecialchars($material) . "' target='_blank'>" . htmlspecialchars(basename($material)) . "</a></li>";
                                            }
                                            ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>Materi tidak tersedia.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Belum ada materi yang tersedia.</p>
                    <?php endif; ?>
                </div>

                <!-- Kembali ke Dashboard Mentor -->
                <a href="mentor-dashboard.php" class="btn btn-primary btn-sm mt-3">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
