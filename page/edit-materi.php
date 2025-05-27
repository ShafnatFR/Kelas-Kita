<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";
$id_materi = $_GET['id_materi'] ?? 0; // Ambil ID Materi dari URL

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

$materi_data = null;
$id_kelas_for_redirect = 0;

if ($id_materi > 0 && $id_mentor > 0) {
    // Ambil data materi yang akan diedit dan pastikan itu milik mentor yang login
    $stmt = $conn->prepare("
        SELECT tm.id_materi, tm.id_kelas, tm.judul_materi, tm.urutan, tk.nama_kelas
        FROM tb_materi tm
        JOIN tb_kelas tk ON tm.id_kelas = tk.id_kelas
        WHERE tm.id_materi = ? AND tk.id_mentor = ?
    ");
    $stmt->bind_param("ii", $id_materi, $id_mentor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $materi_data = $result->fetch_assoc();
        $id_kelas_for_redirect = $materi_data['id_kelas'];
    } else {
        $message = "Materi tidak ditemukan atau Anda tidak memiliki akses untuk mengeditnya.";
        $id_materi = 0; // Invalid ID
    }
    $stmt->close();
} else {
    $message = "ID Materi tidak valid.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $materi_data) {
    $new_judul_materi = trim($_POST['judul_materi']);
    $new_urutan = trim($_POST['urutan']);

    // Validasi input
    if (empty($new_judul_materi)) {
        $message = "Judul Materi wajib diisi.";
    } elseif (!is_numeric($new_urutan) || $new_urutan < 1) {
        $message = "Urutan harus berupa angka positif.";
    } else {
        // Update data materi di database
        $update_stmt = $conn->prepare("UPDATE tb_materi SET judul_materi = ?, urutan = ? WHERE id_materi = ? AND id_kelas = ?");
        $update_stmt->bind_param("siii", $new_judul_materi, $new_urutan, $id_materi, $materi_data['id_kelas']);

        if ($update_stmt->execute()) {
            header("Location: kelola-materi.php?id_kelas=" . $materi_data['id_kelas'] . "&msg=" . urlencode("Materi '" . htmlspecialchars($new_judul_materi) . "' berhasil diperbarui!"));
            exit();
        } else {
            $message = "Gagal memperbarui materi: " . $update_stmt->error;
        }
        $update_stmt->close();
    }
    // Refresh data materi setelah percobaan POST untuk menampilkan nilai terbaru (jika gagal update)
    $materi_data['judul_materi'] = $new_judul_materi;
    $materi_data['urutan'] = $new_urutan;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Materi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Materi</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($materi_data): ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Kelas Induk:</label>
                                    <p class="form-control-static"><strong><?= htmlspecialchars($materi_data['nama_kelas']) ?></strong></p>
                                </div>

                                <div class="mb-3">
                                    <label for="judul_materi" class="form-label">Judul Materi</label>
                                    <input type="text" class="form-control" id="judul_materi" name="judul_materi" value="<?= htmlspecialchars($materi_data['judul_materi']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="urutan" class="form-label">Urutan</label>
                                    <input type="number" class="form-control" id="urutan" name="urutan" value="<?= htmlspecialchars($materi_data['urutan']) ?>" min="1" required>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="kelola-materi.php?id_kelas=<?= $materi_data['id_kelas'] ?>" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Perbarui Materi</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-danger" role="alert">
                                Materi tidak ditemukan atau tidak dapat diakses.
                            </div>
                            <a href="kelola-materi.php" class="btn btn-secondary">Kembali ke Kelola Materi</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>