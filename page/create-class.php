<?php
session_start();
require 'db.php';

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

if ($mentor_result->num_rows === 0) {
    // Jika belum ada record di tb_mentor, buat record baru
    $insert_mentor = $conn->prepare("INSERT INTO tb_mentor (id_user) VALUES (?)");
    $insert_mentor->bind_param("i", $user_id);
    
    if ($insert_mentor->execute()) {
        $id_mentor = $conn->insert_id; // Ambil ID yang baru dibuat
    } else {
        die("Error: Gagal membuat record mentor.");
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
    
    // Validasi data
    if (empty($nama_kelas) || empty($kategori) || empty($harga)) {
        $error_message = "Nama kelas, kategori, dan harga wajib diisi!";
    } elseif (!is_numeric($harga) || $harga < 0) {
        $error_message = "Harga harus berupa angka yang valid!";
    } else {
        // Insert data kelas ke tb_kelas dengan id_mentor yang sudah didapat
        $stmt = $conn->prepare("INSERT INTO tb_kelas (nama_kelas, kategori, harga, description, id_mentor) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $nama_kelas, $kategori, $harga, $description, $id_mentor);

        if ($stmt->execute()) {
            $success_message = "Kelas '$nama_kelas' berhasil ditambahkan!";
            // Reset form setelah berhasil
            $nama_kelas = $kategori = $harga = $description = "";
        } else {
            $error_message = "Gagal menambahkan kelas: " . $conn->error;
        }
        $stmt->close();
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
</head>
<body class="bg-light">
    <div class="container mt-4">
        <!-- Header -->
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
            <!-- Form Buat Kelas -->
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

                        <form method="POST">
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
                                    <option value="Programming" <?php echo (isset($kategori) && $kategori == 'Programming') ? 'selected' : ''; ?>>Programming</option>
                                    <option value="Design" <?php echo (isset($kategori) && $kategori == 'Design') ? 'selected' : ''; ?>>Design</option>
                                    <option value="Marketing" <?php echo (isset($kategori) && $kategori == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                                    <option value="Business" <?php echo (isset($kategori) && $kategori == 'Business') ? 'selected' : ''; ?>>Business</option>
                                    <option value="Language" <?php echo (isset($kategori) && $kategori == 'Language') ? 'selected' : ''; ?>>Language</option>
                                    <option value="Other" <?php echo (isset($kategori) && $kategori == 'Other') ? 'selected' : ''; ?>>Lainnya</option>
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

            <!-- Daftar Kelas yang Sudah Dibuat -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-list mr-2"></i>Kelas Yang Sudah Dibuat</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($kelas_result->num_rows > 0): ?>
                            <?php while ($kelas = $kelas_result->fetch_assoc()): ?>
                                <div class="card mb-3 border-left-primary">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-primary mb-1">
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