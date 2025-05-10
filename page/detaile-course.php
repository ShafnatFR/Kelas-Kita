<?php
session_start();
include_once('db.php');

// Cek jika kursus ID diberikan
if (!isset($_GET['id'])) {
    echo "Kursus tidak ditemukan.";
    exit();
}

$course_id = $_GET['id'];
$query = "SELECT * FROM tbkelas WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

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
    <title><?= htmlspecialchars($course['title']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="course-detail">
        <h1><?= htmlspecialchars($course['title']) ?></h1>
        <p><strong>Kategori:</strong> <?= htmlspecialchars($course['kategori']) ?></p>
        <p><strong>Deskripsi:</strong> <?= htmlspecialchars($course['description']) ?></p>
        <p><strong>Rating:</strong> <?= htmlspecialchars($course['rating']) ?> / 5</p>
        <p><strong>Harga:</strong> Rp. <?= number_format($course['price'], 2, ',', '.') ?></p>
        <a href="enroll.php?course_id=<?= $course['id'] ?>" class="btn btn-success">Daftar Kursus</a>
    </div>
</body>
</html>
