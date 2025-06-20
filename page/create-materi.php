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
// Ambil NAMA KELAS SPESIFIK berdasarkan ID dari URL
$nama_kelas_terpilih = "";
if ($id_kelas_preselected > 0 && $id_mentor > 0) {
    // Query untuk mengambil nama kelas sekaligus memvalidasi kepemilikan
    $stmt_nama_kelas = $conn->prepare("SELECT nama_kelas FROM tb_kelas WHERE id_kelas = ? AND id_mentor = ?");
    $stmt_nama_kelas->bind_param("ii", $id_kelas_preselected, $id_mentor);
    $stmt_nama_kelas->execute();
    $result_nama_kelas = $stmt_nama_kelas->get_result();

    if ($result_nama_kelas->num_rows > 0) {
        $row = $result_nama_kelas->fetch_assoc();
        $nama_kelas_terpilih = $row['nama_kelas'];
    } else {
        // Jika kelas tidak ditemukan atau bukan milik mentor, tampilkan error
        die("Error: Kelas tidak valid atau Anda tidak memiliki akses ke kelas ini.");
    }
    $stmt_nama_kelas->close();
} elseif ($id_kelas_preselected === 0) {
    // Jika tidak ada ID kelas di URL, proses tidak bisa dilanjutkan
    die("Error: Tidak ada kelas yang dipilih. Silakan kembali dan pilih kelas terlebih dahulu.");
}


// Proses form jika di-submit
// Proses form jika di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil semua data dari form
    $id_kelas = trim($_POST['id_kelas']);
    $judul_materi = trim($_POST['judul_materi']);
    $deskripsi_m = trim($_POST['deskripsi_m']); // <-- TAMBAHKAN INI, sebelumnya hilang

    // Validasi input dasar
    if (empty($id_kelas) || empty($judul_materi) || empty($deskripsi_m)) { // <-- Tambahkan validasi deskripsi
        $message = "Kelas, Judul Materi, dan Deskripsi wajib diisi!";
    } else {
        // Validasi tambahan: Pastikan id_kelas benar-benar milik mentor yang login
        $check_class_owner_stmt = $conn->prepare("SELECT id_kelas FROM tb_kelas WHERE id_kelas = ? AND id_mentor = ?");
        $check_class_owner_stmt->bind_param("ii", $id_kelas, $id_mentor);
        $check_class_owner_stmt->execute();
        $class_owner_result = $check_class_owner_stmt->get_result();

        if ($class_owner_result->num_rows === 0) {
            $message = "Kelas yang dipilih tidak valid atau bukan milik Anda.";
        } else {
            // --- LOGIKA BARU UNTUK URUTAN OTOMATIS ---
            // 1. Cari urutan tertinggi (MAX) untuk kelas yang dipilih.
            $stmt_urutan = $conn->prepare("SELECT MAX(urutan) AS max_urutan FROM tb_materi WHERE id_kelas = ?");
            $stmt_urutan->bind_param("i", $id_kelas);
            $stmt_urutan->execute();
            $urutan_result = $stmt_urutan->get_result()->fetch_assoc();
            $stmt_urutan->close();

            // 2. Tentukan urutan baru. Jika belum ada materi (hasilnya NULL), mulai dari 1.
            $urutan_baru = ($urutan_result && $urutan_result['max_urutan'] !== null) ? $urutan_result['max_urutan'] + 1 : 1;

            // 3. Masukkan data materi ke database dengan urutan baru dan deskripsi
            $insert_stmt = $conn->prepare("INSERT INTO tb_materi (id_kelas, judul_materi, deskripsi_m, urutan) VALUES (?, ?, ?, ?)");
            // Perhatikan tipe parameter di bind_param berubah menjadi "issi"
            $insert_stmt->bind_param("issi", $id_kelas, $judul_materi, $deskripsi_m, $urutan_baru);

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

                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Kelas</label>
                            
                            <input type="text" class="form-control" id="nama_kelas" 
                                value="<?= htmlspecialchars($nama_kelas_terpilih) ?>" readonly>
                                
                            <input type="hidden" name="id_kelas" value="<?= $id_kelas_preselected ?>">
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
                                <label for="urutan_display" class="form-label">Urutan Materi Berikutnya</label>
                                <input type="number" class="form-control" id="urutan_display" name="urutan_display" value="1" readonly>
                                <div class="form-text">Nomor urutan ini akan terisi otomatis saat Anda memilih kelas.</div>
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