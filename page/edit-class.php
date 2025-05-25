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
// Ambil data kelas untuk diedit
$stmt = $conn->prepare("
    SELECT k.*, k.status_publikasi FROM tb_kelas k JOIN tb_mentor m ON k.id_mentor = m.id_mentor
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
        // --- AWAL PERUBAHAN YANG SUDAH ANDA TERAPKAN ---
        // Ambil status kelas saat ini sebelum update
        $current_status_query = $conn->prepare("SELECT status_publikasi FROM tb_kelas WHERE id_kelas = ?");
        $current_status_query->bind_param("i", $id_kelas); // Gunakan $id_kelas yang didapat dari GET
        $current_status_query->execute();
        $current_status_result = $current_status_query->get_result();
        $current_status_row = $current_status_result->fetch_assoc();
        $current_status = $current_status_row['status_publikasi'];
        $current_status_query->close();

        $new_status = $current_status; // Default: status tidak berubah
        if ($current_status === 'approved' || $current_status === 'rejected') {
            // Jika kelas sudah disetujui atau ditolak, setiap edit akan mengembalikan status ke 'pending' untuk review ulang
            $new_status = 'pending';
        }
        // --- AKHIR PERUBAHAN YANG SUDAH ANDA TERAPKAN ---

        // Query UPDATE sudah disesuaikan untuk menyertakan status_publikasi
        $update_stmt = $conn->prepare("UPDATE tb_kelas SET nama_kelas = ?, kategori = ?, harga = ?, description = ?, status_publikasi = ? WHERE id_kelas = ?");
        $update_stmt->bind_param("ssdsis", $nama_kelas, $kategori, $harga, $description, $new_status, $id_kelas);

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
                         <?php if (isset($kelas['status_publikasi'])): ?>
                            <div class="mb-3">
                                <strong>Status Saat Ini: </strong>
                                <?php
                                $status = $kelas['status_publikasi'];
                                $text_bg_class = ''; // Untuk Bootstrap 5 text-bg-* classes
                                switch ($status) {
                                    case 'draft': $text_bg_class = 'text-bg-secondary'; break;
                                    case 'pending': $text_bg_class = 'text-bg-warning'; break;
                                    case 'approved': $text_bg_class = 'text-bg-success'; break;
                                    case 'rejected': $text_bg_class = 'text-bg-danger'; break;
                                    default: $text_bg_class = 'text-bg-info'; break;
                                }
                                ?>
                                <span class="badge <?= $text_bg_class ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
                                <?php if ($status === 'approved' || $status === 'rejected'): ?>
                                    <small class="text-muted ms-2">(Perubahan akan mengembalikan status ke 'Pending')</small>
                                <?php endif; ?>
                            </div>
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