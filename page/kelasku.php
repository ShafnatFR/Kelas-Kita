<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "KelasKita_baru";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Define site-wide values
$site_name = "KelasKita_baru";
$site_tagline = "Tingkatkan Keterampilan Anda, Raih Masa Depan Cemerlang";

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Ambil ID kursus dari parameter URL
$course_id = isset($_GET['id']) ? $_GET['id'] : '';

// Ambil user yang sedang login
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

$kelas_terdaftar = [];

if ($user_id > 0) {
    $stmt = $conn->prepare("
        SELECT 
            k.*, 
            m.id_mentor, 
            u.nama_lengkap AS nama_mentor, 
            u.username AS username_mentor
        FROM tb_transaksi t
        JOIN tb_kelas k ON t.id_kelas = k.id_kelas
        LEFT JOIN tb_mentor m ON k.id_mentor = m.id_mentor
        LEFT JOIN tb_user u ON m.id_user = u.id_user
        WHERE t.id_user = ? AND t.status = 'Completed'
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $id_kelas = $row['id_kelas'];

        // Hitung total materi
        $stmt_total = $conn->prepare("SELECT COUNT(*) AS total FROM tb_materi WHERE id_kelas = ?");
        $stmt_total->bind_param("i", $id_kelas);
        $stmt_total->execute();
        $result_total = $stmt_total->get_result();
        $total_materi = $result_total->fetch_assoc()['total'] ?? 0;
        $stmt_total->close();

        // Hitung materi yang sudah selesai
        $stmt_done = $conn->prepare("SELECT COUNT(*) AS selesai FROM tb_progress_kelas WHERE id_user = ? AND id_kelas = ?");
        $stmt_done->bind_param("ii", $user_id, $id_kelas);
        $stmt_done->execute();
        $result_done = $stmt_done->get_result();
        $materi_selesai = $result_done->fetch_assoc()['selesai'] ?? 0;
        $stmt_done->close();

        // Hitung progress %
        $progress_persen = ($total_materi > 0) ? round(($materi_selesai / $total_materi) * 100) : 0;

        // Tambahkan data ke row
        $row['total_materi'] = $total_materi;
        $row['materi_selesai'] = $materi_selesai;
        $row['progress'] = $progress_persen;

        // Simpan
        $kelas_terdaftar[] = $row;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_name); ?> - <?php echo htmlspecialchars($site_tagline); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
        }

        .section-padding {
            padding: 80px 0;
        }

        .section-title {
            margin-bottom: 50px;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .card-icon {
            font-size: 2rem;
            color: #4a6cf7;
            height: 60px;
            width: 60px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 8px;
            transition: transform 0.3s ease;
            height: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }


        .partner-logo {
            height: 60px;
            object-fit: contain;
            filter: grayscale(100%);
            transition: all 0.3s;
        }

        .partner-logo:hover {
            filter: grayscale(0);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .newsletter-box {
            background-color: var(--light);
            padding: 30px;
            border-radius: 10px;
        }

        /* Hero Section Specific Style */
        .hero-section {
            background: linear-gradient(rgba(3, 3, 176, 0.47), rgb(15, 167, 232)), url('../assets/images/hero-bg.jpg');
            /* Pastikan path gambar hero benar */
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }
    </style>
</head>

<body>
    <?php include_once(__DIR__ . "/../Views/navbarbootstrap.php"); ?>

    <div class="p-5">
        <!-- Buatkan judul ini kursus yang sedang saya jalani -->
        <h1 class="text-center mb-4">Kursus yang Sedang Saya Jalani</h1>
        <p class="text-center mb-5">Berikut adalah daftar kursus yang sedang Anda ikuti. Klik tombol "View Course" untuk melanjutkan belajar.</p>

        <?php if (empty($kelas_terdaftar)): ?>
            <div class="alert alert-info text-center" role="alert">
                Anda belum terdaftar di kursus manapun. Silakan daftar kursus terlebih dahulu.
            </div>
            <a href="index.php" class="d-block text-center">
                <button class="btn btn-primary">Daftar Kursus</button>
            </a>
        <?php endif; ?>
        <?php foreach ($kelas_terdaftar as $kelas): ?>
            <!-- Start Card -->
            <div class="card mb-4 shadow-sm px-4 py-2">
                <div class="row g-0 align-items-center">
                    <!-- Background Box -->
                    <div class="col-md-2 d-flex align-items-center justify-content-center" style="min-height: 130px; background-color: #e9ecef; border-radius: 0.5rem;">
                        <img src="../uploads/kelas_profil/profil_683bfc1bb40ba.png" alt="<?= htmlspecialchars($kelas['nama_kelas']) ?>" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                    </div>

                    <!-- Course Content -->
                    <div class="col-md-8 px-5 py-3">
                        <span class="badge bg-secondary mb-2"><?= htmlspecialchars($kelas['kategori'] ?? 'Kelas Online') ?></span>
                        <h5 class="mb-1"><?= htmlspecialchars($kelas['nama_kelas']) ?></h5>
                        <small class="text-muted"><?= htmlspecialchars($kelas['nama_mentor'] ?? $kelas['username_mentor']) ?></small>

                        <p class="mb-1 mt-2"><?= $kelas['materi_selesai'] ?> dari <?= $kelas['total_materi'] ?> materi selesai</p>
                        <div class="progress mb-1" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $kelas['progress'] ?>%"></div>
                        </div>
                        <small class="text-muted"><?= $kelas['progress'] ?>% Course Completed</small>

                    </div>

                    <!-- Button -->
                    <div class="col-md-2 text-end pe-4">
                        <a href="mycourse-detail.php?id=<?= $kelas['id_kelas'] ?>" class="btn btn-outline-primary mt-3">View Course</a>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        <?php endforeach; ?>

    </div>

</body>

</html>