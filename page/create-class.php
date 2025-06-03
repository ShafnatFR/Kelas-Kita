<?php
session_start();
require 'db.php'; // Pastikan path ke file db.php sudah benar

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

// Ambil id_mentor berdasarkan id_user yang login
$user_id = $_SESSION['id'];
$mentor_query = $conn->prepare("SELECT id_mentor FROM tb_mentor WHERE id_user = ?");
$mentor_query->bind_param("i", $user_id);
$mentor_query->execute();
$mentor_result = $mentor_query->get_result();

$id_mentor = 0;
if ($mentor_result->num_rows === 0) {
    // Jika belum ada record di tb_mentor, buat record baru
    $insert_mentor = $conn->prepare("INSERT INTO tb_mentor (id_user) VALUES (?)");
    $insert_mentor->bind_param("i", $user_id);
    
    if ($insert_mentor->execute()) {
        $id_mentor = $conn->insert_id; // Ambil ID yang baru dibuat
    } else {
        die("Error: Gagal membuat record mentor: " . $conn->error);
    }
    $insert_mentor->close();
} else {
    // Jika sudah ada, ambil id_mentor
    $mentor_row = $mentor_result->fetch_assoc();
    $id_mentor = $mentor_row['id_mentor'];
}
$mentor_query->close();

$error_message = "";
$success_message = "";

// Jika form disubmit, simpan data kelas ke database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kelas = trim($_POST['nama_kelas']);
    $kategori = trim($_POST['kategori']);
    $harga = trim($_POST['harga']);
    $description = trim($_POST['description']);
    
    $default_status = 'draft'; // Status publikasi default
    $profil_kelas_path = NULL; // Default NULL jika tidak ada gambar diupload atau error
    $badge_value = NULL;       // Kosongkan kolom badge sesuai permintaan
    $current_date = date('Y-m-d'); // Tanggal rilis dan update

    $upload_dir_kelas_profil = '../uploads/kelas_profil/'; // Direktori untuk menyimpan gambar profil kelas

    // Pastikan direktori upload ada
    if (!is_dir($upload_dir_kelas_profil)) {
        mkdir($upload_dir_kelas_profil, 0777, true); // Buat direktori jika belum ada (pastikan permission)
    }

    // --- Proses Upload Foto Profil Kelas ---
    if (isset($_FILES['profil_kelas_file']) && $_FILES['profil_kelas_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['profil_kelas_file']['tmp_name'];
        $file_name = basename($_FILES['profil_kelas_file']['name']);
        $file_size = $_FILES['profil_kelas_file']['size'];
        $file_type = $_FILES['profil_kelas_file']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        
        // Validasi tipe file
        if (!in_array($file_type, $allowed_types) || !in_array($file_ext, $allowed_exts)) {
            $error_message = "Tipe file foto profil kelas tidak diizinkan. Hanya JPG, JPEG, PNG, GIF.";
        } 
        // Validasi ukuran file (5MB)
        elseif ($file_size > 5 * 1024 * 1024) { 
            $error_message = "Ukuran file foto profil kelas terlalu besar. Maksimal 5MB.";
        } else {
            // Generate nama file unik untuk menghindari konflik
            $new_file_name = uniqid('profil_') . '.' . $file_ext;
            $destination_path = $upload_dir_kelas_profil . $new_file_name;

            // Pastikan path tidak melebihi varchar(100)
            if (strlen($destination_path) > 100) {
                 $error_message = "Path file terlalu panjang, coba nama file yang lebih pendek.";
            } elseif (move_uploaded_file($file_tmp_name, $destination_path)) {
                $profil_kelas_path = $destination_path; // Simpan path untuk database
            } else {
                $error_message = "Gagal memindahkan file foto profil kelas yang diunggah.";
            }
        }
    } elseif (isset($_FILES['profil_kelas_file']) && $_FILES['profil_kelas_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Tangani error upload selain tidak ada file
        $error_message = "Kesalahan upload foto profil kelas: " . $_FILES['profil_kelas_file']['error'];
    }

    // Lanjutkan proses insert hanya jika tidak ada error dari upload
    if (empty($error_message)) {
        // Validasi data input form lainnya
        if (empty($nama_kelas) || empty($kategori) || empty($harga)) {
            $error_message = "Nama kelas, kategori, dan harga wajib diisi!";
        } elseif (!is_numeric($harga) || $harga < 0) {
            $error_message = "Harga harus berupa angka yang valid!";
        } else {
            // Insert data ke tb_kelas, termasuk `profil_kelas`, `badge`, `status_publikasi`, `tanggal_rilis`, `tanggal_update`
            $stmt = $conn->prepare("INSERT INTO tb_kelas (id_mentor, nama_kelas, kategori, harga, description, profil_kelas, badge, status_publikasi, tgl_dibuat, tanggal_update) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            // Tipe parameter:
            // i (id_mentor), s (nama_kelas), s (kategori), d (harga), s (description), 
            // s (profil_kelas_path), s (badge_value), s (default_status), s (current_date), s (current_date)
            $stmt->bind_param("issdssssss", $id_mentor, $nama_kelas, $kategori, $harga, $description, $profil_kelas_path, $badge_value, $default_status, $current_date, $current_date);

            if ($stmt->execute()) {
                $success_message = "Kelas '$nama_kelas' berhasil ditambahkan!";
                // Reset form setelah berhasil
                $nama_kelas = $kategori = $harga = $description = "";
                $profil_kelas_path = NULL; // Reset path file
            } else {
                $error_message = "Gagal menambahkan kelas: " . $conn->error;
                // Jika gagal insert ke DB setelah upload, hapus file yang sudah terupload
                if ($profil_kelas_path && file_exists($profil_kelas_path)) {
                    unlink($profil_kelas_path);
                }
            }
            $stmt->close();
        }
    }
}

// Ambil daftar kelas yang sudah dibuat oleh mentor ini
$kelas_query = $conn->prepare("SELECT * FROM tb_kelas WHERE id_mentor = ? ORDER BY id_kelas DESC");
$kelas_query->bind_param("i", $id_mentor);
$kelas_query->execute();
$kelas_result = $kelas_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kelas Baru - Mentor Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Gaya untuk thumbnail gambar profil kelas di daftar */
        .kelas-card-img {
            width: 100px; /* Lebar thumbnail */
            height: 75px; /* Tinggi thumbnail */
            object-fit: cover; /* Memastikan gambar terisi penuh tanpa distorsi */
            margin-right: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-primary">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Buat Kelas Baru
                    </h2>
                    <div>
                        <a href="mentor-dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
                <small class="text-muted">Mentor: <?php echo htmlspecialchars($_SESSION['username']); ?> (ID Mentor: <?php echo $id_mentor; ?>)</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-edit mr-2"></i>Form Kelas Baru</h5>
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
                                    <i class="fas fa-book mr-1"></i>
                                    Nama Kelas <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="nama_kelas" 
                                        placeholder="Contoh: Belajar PHP untuk Pemula" 
                                        value="<?php echo isset($nama_kelas) ? htmlspecialchars($nama_kelas) : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="kategori">
                                    <i class="fas fa-tags mr-1"></i>
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="SQL" <?php echo (isset($kategori) && $kategori == 'SQL') ? 'selected' : ''; ?>>SQL</option>
                                    <option value="Design" <?php echo (isset($kategori) && $kategori == 'Design') ? 'selected' : ''; ?>>Design</option>
                                    <option value="Java" <?php echo (isset($kategori) && $kategori == 'Java') ? 'selected' : ''; ?>>Java</option>
                                    <option value="Web Development" <?php echo (isset($kategori) && $kategori == 'Web Development') ? 'selected' : ''; ?>>Web Development</option>
                                    <option value="Bahasa" <?php echo (isset($kategori) && $kategori == 'Bahasa') ? 'selected' : ''; ?>>Bahasa</option>
                                    <option value="Fisika" <?php echo (isset($kategori) && $kategori == 'Fisika') ? 'selected' : ''; ?>>Fisika</option>
                                    <option value="Leadership" <?php echo (isset($kategori) && $kategori == 'Leadership') ? 'selected' : ''; ?>>Leadership</option>
                                    </select>
                            </div>

                            <div class="form-group">
                                <label for="harga">
                                    <i class="fas fa-money-bill-wave mr-1"></i>
                                    Harga <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control" name="harga" 
                                            placeholder="100000" min="0" step="1000"
                                            value="<?php echo isset($harga) ? htmlspecialchars($harga) : ''; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="profil_kelas_file">
                                    <i class="fas fa-image mr-1"></i>
                                    Foto Profil Kelas (Max 5MB, JPG, JPEG, PNG, GIF)
                                </label>
                                <input type="file" class="form-control-file" id="profil_kelas_file" name="profil_kelas_file" accept="image/jpeg,image/png,image/gif">
                                <small class="form-text text-muted">Unggah gambar yang akan menjadi sampul kelas Anda.</small>
                            </div>

                            <div class="form-group">
                                <label for="description">
                                    <i class="fas fa-align-left mr-1"></i>
                                    Deskripsi
                                </label>
                                <textarea class="form-control" name="description" rows="4" 
                                            placeholder="Jelaskan tentang kelas ini..."><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-plus mr-2"></i>
                                Buat Kelas
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-list mr-2"></i>Kelas Yang Sudah Dibuat</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($kelas_result->num_rows > 0): ?>
                            <?php while ($kelas = $kelas_result->fetch_assoc()): ?>
                                <div class="card mb-3 border-left-primary">
                                    <div class="card-body p-3 d-flex align-items-center"> <?php 
                                        // Tampilkan gambar profil kelas jika ada dan file-nya ditemukan
                                        if (!empty($kelas['profil_kelas']) && file_exists($kelas['profil_kelas'])): ?>
                                            <img src="<?php echo htmlspecialchars($kelas['profil_kelas']); ?>" alt="Profil Kelas" class="kelas-card-img">
                                        <?php else: ?>
                                            <img src="../assets/img/default_class_profil.png" alt="No Image" class="kelas-card-img"> 
                                        <?php endif; ?>
                                        
                                        <div> <h6 class="card-title text-primary mb-1">
                                                <?php echo htmlspecialchars($kelas['nama_kelas']); ?>
                                            </h6>
                                            <div class="small text-muted mb-2">
                                                <span class="badge badge-secondary mr-2">
                                                    <?php echo htmlspecialchars($kelas['kategori']); ?>
                                                </span>
                                                <span class="text-success font-weight-bold">
                                                    Rp <?php echo number_format($kelas['harga'], 0, ',', '.'); ?>
                                                </span>
                                            </div>
                                            <?php if (!empty($kelas['description'])): ?>
                                                <p class="card-text small">
                                                    <?php echo htmlspecialchars(substr($kelas['description'], 0, 100)) . (strlen($kelas['description']) > 100 ? '...' : ''); ?>
                                                </p>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <a href="kelola-materi.php?id_kelas=<?= $kelas['id_kelas'] ?>" class="btn btn-sm btn-info"><i class="fas fa-folder-open mr-1"></i> Kelola Materi</a>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>Belum ada kelas yang dibuat.</p>
                            </div>
                        <?php endif; ?>
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
$kelas_query->close();
$conn->close();
?>