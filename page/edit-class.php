<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

// 1. AMBIL ID KELAS DARI URL DAN VALIDASI
$id_kelas = $_GET['id_kelas'] ?? 0;
if ($id_kelas <= 0) {
    // Jika tidak ada ID, redirect ke halaman kelola kelas
    header("Location: kelola-kelas.php"); // Ganti jika nama file berbeda
    exit();
}

// 2. AMBIL DATA KELAS YANG AKAN DIEDIT
// Pastikan kelas ini milik mentor yang sedang login untuk keamanan
$user_id = $_SESSION['id'];
$stmt = $conn->prepare("
    SELECT k.*, m.id_mentor FROM tb_kelas k
    JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    WHERE k.id_kelas = ? AND m.id_user = ?
");
$stmt->bind_param("ii", $id_kelas, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Jika kelas tidak ditemukan atau bukan milik mentor ini, redirect
    echo "Error: Kelas tidak ditemukan atau Anda tidak memiliki akses.";
    exit();
}
$kelas = $result->fetch_assoc();
$id_mentor = $kelas['id_mentor']; // Dapatkan id_mentor dari data kelas
$stmt->close();


$error_message = "";
$success_message = "";

// 3. PROSES FORM UPDATE JIKA DISUBMIT (METHOD POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_kelas = trim($_POST['nama_kelas']);
    $kategori = trim($_POST['kategori']);
    $harga = trim($_POST['harga']);
    $description = trim($_POST['description']);
    $current_date = date('Y-m-d'); // Tanggal update

    // Ambil path gambar saat ini dari data yang sudah ada
    $profil_kelas_path = $kelas['profil_kelas'];

    // --- Proses Upload Foto Profil Kelas Baru ---
    if (isset($_FILES['profil_kelas_file']) && $_FILES['profil_kelas_file']['error'] === UPLOAD_ERR_OK) {
        
        $upload_dir_kelas_profil = '../uploads/kelas_profil/';
        if (!is_dir($upload_dir_kelas_profil)) {
            mkdir($upload_dir_kelas_profil, 0777, true);
        }

        $file_tmp_name = $_FILES['profil_kelas_file']['tmp_name'];
        $file_name = basename($_FILES['profil_kelas_file']['name']);
        $file_size = $_FILES['profil_kelas_file']['size'];
        $file_type = mime_content_type($file_tmp_name); // Lebih aman
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (!in_array($file_type, $allowed_types)) {
            $error_message = "Tipe file tidak diizinkan. Hanya JPG, PNG, GIF.";
        } elseif ($file_size > 5 * 1024 * 1024) { 
            $error_message = "Ukuran file terlalu besar. Maksimal 5MB.";
        } else {
            $new_file_name = uniqid('profil_') . '.' . $file_ext;
            $destination_path = $upload_dir_kelas_profil . $new_file_name;

            if (move_uploaded_file($file_tmp_name, $destination_path)) {
                // Hapus gambar lama jika berhasil upload yang baru, dan jika bukan gambar default
                $old_profil_path = $kelas['profil_kelas'];
                if (!empty($old_profil_path) && file_exists($old_profil_path) && strpos($old_profil_path, 'default') === false) {
                    unlink($old_profil_path);
                }
                $profil_kelas_path = $destination_path; // Update path dengan gambar baru
            } else {
                $error_message = "Gagal memindahkan file yang diunggah.";
            }
        }
    }

    // Lanjutkan proses update hanya jika tidak ada error
    if (empty($error_message)) {
        if (empty($nama_kelas) || empty($kategori) || !isset($harga)) {
            $error_message = "Nama kelas, kategori, dan harga wajib diisi!";
        } elseif (!is_numeric($harga) || $harga < 0) {
            $error_message = "Harga harus berupa angka yang valid!";
        } else {
            // 4. BUAT QUERY UPDATE
            $update_stmt = $conn->prepare("
                UPDATE tb_kelas 
                SET nama_kelas = ?, kategori = ?, harga = ?, description = ?, profil_kelas = ?, tanggal_update = ?
                WHERE id_kelas = ? AND id_mentor = ?
            ");
            $update_stmt->bind_param("ssdssiii", $nama_kelas, $kategori, $harga, $description, $profil_kelas_path, $current_date, $id_kelas, $id_mentor);

            if ($update_stmt->execute()) {
                $success_message = "Kelas '$nama_kelas' berhasil diperbarui!";
                // Refresh data kelas untuk ditampilkan di form
                $stmt = $conn->prepare("SELECT * FROM tb_kelas WHERE id_kelas = ?");
                $stmt->bind_param("i", $id_kelas);
                $stmt->execute();
                $kelas = $stmt->get_result()->fetch_assoc();
                $stmt->close();
            } else {
                $error_message = "Gagal memperbarui kelas: " . $conn->error;
            }
            $update_stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas - Mentor Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .current-profile-img {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-primary">
                        <i class="fas fa-pencil-alt mr-2"></i>
                        Edit Kelas
                    </h2>
                    <div>
                        <a href="kelola-kelas.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Kelola Kelas
                        </a>
                    </div>
                </div>
                <small class="text-muted">Anda sedang mengedit kelas: <?php echo htmlspecialchars($kelas['nama_kelas']); ?></small>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-edit mr-2"></i>Form Edit Kelas</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle mr-2"></i>
                                <?php echo htmlspecialchars($success_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nama_kelas">
                                    <i class="fas fa-book mr-1"></i> Nama Kelas <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="nama_kelas" 
                                       placeholder="Contoh: Belajar PHP untuk Pemula" 
                                       value="<?php echo htmlspecialchars($kelas['nama_kelas']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="kategori">
                                    <i class="fas fa-tags mr-1"></i> Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php 
                                        $kategori_options = ["SQL", "Design", "Java", "Web Development", "Bahasa", "Fisika", "Leadership"];
                                        foreach ($kategori_options as $option) {
                                            $selected = ($kelas['kategori'] == $option) ? 'selected' : '';
                                            echo "<option value=\"$option\" $selected>$option</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="harga">
                                    <i class="fas fa-money-bill-wave mr-1"></i> Harga <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control" name="harga" 
                                           placeholder="100000" min="0" step="1000"
                                           value="<?php echo htmlspecialchars($kelas['harga']); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="profil_kelas_file">
                                    <i class="fas fa-image mr-1"></i> Ganti Foto Profil Kelas (Max 5MB)
                                </label>
                                <div class="mb-2">
                                    <p class="mb-1 small text-muted">Foto saat ini:</p>
                                    <?php if (!empty($kelas['profil_kelas']) && file_exists($kelas['profil_kelas'])): ?>
                                        <img src="<?php echo htmlspecialchars($kelas['profil_kelas']); ?>?v=<?= time() ?>" alt="Profil Kelas Saat Ini" class="current-profile-img">
                                    <?php else: ?>
                                        <img src="../assets/img/default_class_profil.png" alt="No Image" class="current-profile-img">
                                    <?php endif; ?>
                                </div>
                                <input type="file" class="form-control-file" id="profil_kelas_file" name="profil_kelas_file" accept="image/jpeg,image/png,image/gif">
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                            </div>

                            <div class="form-group">
                                <label for="description">
                                    <i class="fas fa-align-left mr-1"></i> Deskripsi
                                </label>
                                <textarea class="form-control" name="description" rows="4" 
                                          placeholder="Jelaskan tentang kelas ini..."><?php echo htmlspecialchars($kelas['description']); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>