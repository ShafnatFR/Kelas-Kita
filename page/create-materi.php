<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";
$id_kelas_preselected = $_GET['id_kelas'] ?? 0;

// Dapatkan id_mentor berdasarkan id_user yang login
$mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
$mentor_query->bind_param("i", $user_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

$id_mentor = 0;
if ($mentor_result->num_rows > 0) {
    $mentor_row = $mentor_result->fetch_assoc();
    $id_mentor = $mentor_row['id_mentor'];
}
$mentor_query->close();

if ($id_mentor === 0) {
    $message = "Error: ID Mentor tidak ditemukan untuk user ini. Silakan hubungi admin.";
}

// Ambil daftar kelas yang dimiliki mentor untuk dropdown
$classes = [];
if ($id_mentor > 0) {
    $stmt_classes = $conn->prepare("SELECT id_kelas, nama_kelas FROM tb_kelas WHERE id_mentor = ? ORDER BY nama_kelas ASC");
    $stmt_classes->bind_param("i", $id_mentor);
    $stmt_classes->execute();
    $classes_result = $stmt_classes->get_result();
    while ($row = $classes_result->fetch_assoc()) {
        $classes[] = $row;
    }
    $stmt_classes->close();
}

// Proses form jika di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kelas = trim($_POST['id_kelas']);
    $judul_materi = trim($_POST['judul_materi']);
    $urutan = trim($_POST['urutan']);

    // Validasi input dasar
    if (empty($id_kelas) || empty($judul_materi)) {
        $message = "Kelas dan Judul Materi wajib diisi!";
    } elseif (!is_numeric($urutan) || $urutan < 1) {
        $message = "Urutan harus berupa angka positif.";
    } else {
        // Validasi tambahan: Pastikan id_kelas benar-benar milik mentor yang login
        $check_class_owner_stmt = $conn->prepare("SELECT id_kelas FROM tb_kelas WHERE id_kelas = ? AND id_mentor = ?");
        $check_class_owner_stmt->bind_param("ii", $id_kelas, $id_mentor);
        $check_class_owner_stmt->execute();
        $class_owner_result = $check_class_owner_stmt->get_result();

        if ($class_owner_result->num_rows === 0) {
            $message = "Kelas yang dipilih tidak valid atau bukan milik Anda.";
        } else {
            // Masukkan data materi ke database (tanpa deskripsi_m)
            $insert_stmt = $conn->prepare("INSERT INTO tb_materi (id_kelas, judul_materi, urutan) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("isi", $id_kelas, $judul_materi, $urutan); // Sesuaikan parameter: i untuk id_kelas, s untuk judul_materi, i untuk urutan

            if ($insert_stmt->execute()) {
                header("Location: kelola-materi.php?id_kelas=" . $id_kelas . "&msg=" . urlencode("Materi '{$judul_materi}' berhasil ditambahkan!"));
                exit();
            } else {
                $message = "Gagal menambahkan materi: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        }
        $check_class_owner_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Materi Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Materi Baru</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="id_kelas" class="form-label">Pilih Kelas</label>
                                <select class="form-select" id="id_kelas" name="id_kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id_kelas'] ?>"
                                            <?= ($class['id_kelas'] == $id_kelas_preselected) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($class['nama_kelas']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="judul_materi" class="form-label">Judul Materi</label>
                                <input type="text" class="form-control" id="judul_materi" name="judul_materi" required>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_m" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi_m" name="deskripsi_m" rows="4" placeholder="Jelaskan secara singkat isi dari materi ini..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="urutan" class="form-label">Urutan</label>
                                <input type="number" class="form-control" id="urutan" name="urutan" value="1" min="1" required>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="kelola-materi.php<?= ($id_kelas_preselected > 0) ? '?id_kelas=' . $id_kelas_preselected : '' ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Tambah Materi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>