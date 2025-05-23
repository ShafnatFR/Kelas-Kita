<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$id_kelas = $_GET['id_kelas'] ?? 0;
$message = "";

// Ambil data kelas untuk diedit
$stmt = $conn->prepare("
    SELECT k.* FROM tb_kelas k
    JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    WHERE k.id_kelas = ? AND m.id_user = ?
");
$stmt->bind_param("ii", $id_kelas, $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: kelola-kelas.php");
    exit();
}

$kelas = $result->fetch_assoc();

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kelas = trim($_POST['nama_kelas']);
    $kategori = trim($_POST['kategori']);
    $harga = trim($_POST['harga']);
    $description = trim($_POST['description']);
    
    if (empty($nama_kelas) || empty($kategori) || empty($harga)) {
        $message = "Nama kelas, kategori, dan harga wajib diisi!";
    } else {
        $update_stmt = $conn->prepare("UPDATE tb_kelas SET nama_kelas = ?, kategori = ?, harga = ?, description = ? WHERE id_kelas = ?");
        $update_stmt->bind_param("ssdsi", $nama_kelas, $kategori, $harga, $description, $id_kelas);
        
        if ($update_stmt->execute()) {
            header("Location: kelola-kelas.php");
            exit();
        } else {
            $message = "Gagal mengupdate kelas!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Kelas</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-warning"><?= htmlspecialchars($message) ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Kelas</label>
                                <input type="text" class="form-control" name="nama_kelas" 
                                       value="<?= htmlspecialchars($kelas['nama_kelas']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-control" name="kategori" required>
                                    <option value="Programming" <?= $kelas['kategori'] == 'Programming' ? 'selected' : '' ?>>Programming</option>
                                    <option value="Design" <?= $kelas['kategori'] == 'Design' ? 'selected' : '' ?>>Design</option>
                                    <option value="Marketing" <?= $kelas['kategori'] == 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                                    <option value="Business" <?= $kelas['kategori'] == 'Business' ? 'selected' : '' ?>>Business</option>
                                    <option value="Other" <?= $kelas['kategori'] == 'Other' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" class="form-control" name="harga" 
                                       value="<?= $kelas['harga'] ?>" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($kelas['description']) ?></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="kelola-kelas.php" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Update Kelas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>