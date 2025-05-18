<?php
session_start();
include_once('db.php');

// Cek jika pengguna sudah login dan sudah berrole mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
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

    echo "<div class='alert alert-success'>Kursus berhasil diperbarui!</div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kursus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/mentor.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Edit Kursus: <?= htmlspecialchars($course['title']) ?></h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Kelas</label>
                                <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($course['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select name="kategori" id="kategori" class="form-select" required>
                                    <option value="Web Development" <?= $course['kategori'] == 'Web Development' ? 'selected' : '' ?>>Web Development</option>
                                    <option value="Digital Marketing" <?= $course['kategori'] == 'Digital Marketing' ? 'selected' : '' ?>>Digital Marketing</option>
                                    <option value="Data Science" <?= $course['kategori'] == 'Data Science' ? 'selected' : '' ?>>Data Science</option>
                                    <!-- Add other categories here -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea name="description" id="description" class="form-control" rows="5" required><?= htmlspecialchars($course['description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga (IDR)</label>
                                <input type="number" name="price" id="price" class="form-control" value="<?= htmlspecialchars($course['price']) ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
