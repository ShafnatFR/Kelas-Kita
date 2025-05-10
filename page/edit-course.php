<?php
session_start();
include_once('db.php');

// Cek jika pengguna sudah login dan sudah berrole mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: signIn.php");
    exit();
}

// Ambil data kursus berdasarkan ID yang dipilih
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data kursus dari form
    $title = $_POST['title'];
    $kategori = $_POST['kategori'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Update kursus di database
    $update_query = "UPDATE tbkelas SET title=?, kategori=?, description=?, price=? WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssdi", $title, $kategori, $description, $price, $course_id);
    $stmt->execute();

    echo "Kursus berhasil diperbarui!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kursus</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="edit-course">
        <h1>Edit Kursus: <?= htmlspecialchars($course['title']) ?></h1>
        <form method="POST">
            <div>
                <label for="title">Judul Kelas:</label>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($course['title']) ?>" required>
            </div>
            <div>
                <label for="kategori">Kategori:</label>
                <select name="kategori" id="kategori" required>
                    <option value="Web Development" <?= $course['kategori'] == 'Web Development' ? 'selected' : '' ?>>Web Development</option>
                    <option value="Digital Marketing" <?= $course['kategori'] == 'Digital Marketing' ? 'selected' : '' ?>>Digital Marketing</option>
                    <option value="Data Science" <?= $course['kategori'] == 'Data Science' ? 'selected' : '' ?>>Data Science</option>
                    <!-- Add other categories here -->
                </select>
            </div>
            <div>
                <label for="description">Deskripsi:</label>
                <textarea name="description" id="description" required><?= htmlspecialchars($course['description']) ?></textarea>
            </div>
            <div>
                <label for="price">Harga:</label>
                <input type="number" name="price" id="price" value="<?= htmlspecialchars($course['price']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
