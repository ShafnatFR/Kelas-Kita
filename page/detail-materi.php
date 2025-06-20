<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "KelasKita_baru";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

$site_name = "KelasKita_baru"; // Define site name for footer usage
$site_tagline = "Tingkatkan Keterampilan Anda, Raih Masa Depan Cemerlang"; // Contoh tagline

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Ambil ID kursus dari parameter URL (meskipun tidak digunakan di halaman index ini, tetap ada untuk konsistensi)
$sub_materi_id = isset($_GET['id']) ? $_GET['id'] : '';

// Mendapatkan seluruh kelas yang sedang diikuti oleh user
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0; // Pastikan user sudah login


$sub_materi_data = [];
$video_data = [];
$sub_materi_list = [];
$is_last_sub_materi = false;

if (!empty($sub_materi_id)) {
    // Ambil data sub_materi utama
    $stmt = $conn->prepare("SELECT * FROM tb_sub_materi WHERE id_sub_materi = ?");
    $stmt->bind_param("i", $sub_materi_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $sub_materi_data = $result->fetch_assoc();
    } else {
        die("Submateri tidak ditemukan.");
    }
    $stmt->close();

    // Ambil video berdasarkan id_video dari sub_materi
    if (!empty($sub_materi_data['id_video'])) {
        $stmt2 = $conn->prepare("SELECT * FROM tb_video WHERE id_video = ?");
        $stmt2->bind_param("i", $sub_materi_data['id_video']);
        $stmt2->execute();
        $result_video = $stmt2->get_result();

        if ($result_video && $result_video->num_rows > 0) {
            $video_data = $result_video->fetch_assoc();
        }

        $stmt2->close();
    }

    // Ambil dokumen berdasarkan id_dokumen dari sub_materi
    if (!empty($sub_materi_data['id_dokumen'])) {
        $stmt2 = $conn->prepare("SELECT * FROM tb_dokumen WHERE id_dokumen = ?");
        $stmt2->bind_param("i", $sub_materi_data['id_dokumen']);
        $stmt2->execute();
        $result_dokumen = $stmt2->get_result();

        if ($result_dokumen && $result_dokumen->num_rows > 0) {
            $dokumen_data = $result_dokumen->fetch_assoc();
        }

        // Get file path for download
        if (isset($dokumen_data['file_path_dokumen'])) {
            $sub_materi_data['file_path_dokumen'] = $dokumen_data['file_path_dokumen'];
        } else {
            $sub_materi_data['file_path_dokumen'] = '';
        }

        $stmt2->close();
    }

    // Ambil seluruh sub_materi dengan id_materi yang sama
    $id_materi = $sub_materi_data['id_materi'];
    $stmt3 = $conn->prepare("SELECT * FROM tb_sub_materi WHERE id_materi = ? ORDER BY id_sub_materi ASC");
    $stmt3->bind_param("i", $id_materi);
    $stmt3->execute();
    $result_list = $stmt3->get_result();

    $sub_materi_ids = [];
    while ($row = $result_list->fetch_assoc()) {
        $sub_materi_list[] = $row;
        $sub_materi_ids[] = $row['id_sub_materi'];
    }
    $stmt3->close();

    // Cek apakah ini sub_materi terakhir
    if (!empty($sub_materi_ids)) {
        $last_id = end($sub_materi_ids);
        $is_last_sub_materi = ($sub_materi_id == $last_id);
    }

    // Cek apakah materi ini sudah selesai
    $stmt4 = $conn->prepare("SELECT * FROM tb_progress_kelas WHERE id_materi = ? AND id_user = ?");
    $stmt4->bind_param("ii", $id_materi, $user_id);
    $stmt4->execute();
    $result_progress = $stmt4->get_result();
    if ($result_progress && $result_progress->num_rows > 0) {
        // Materi sudah selesai
        $is_materi_selesai = true;
    } else {
        // Materi belum selesai
        $is_materi_selesai = false;
    }
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

    <div class="container py-5">
        <!-- Judul Submateri -->
        <div class="mb-4">
            <h3 class="fw-bold">Submateri 1: Web Programming</h3>
        </div>

        <!-- Video YouTube -->
        <div class="ratio ratio-16x9 mb-4">
            <iframe src="<?php echo htmlspecialchars($video_data['file_path_video']); ?>"
                title="YouTube video"
                allowfullscreen></iframe>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex gap-3">
            <?php if (!$is_last_sub_materi): ?>
                <a href="detail-materi.php?id=<?php echo htmlspecialchars($sub_materi_data['id_sub_materi'] + 1); ?>"
                    class="btn btn-primary rounded-0">Lanjut ke Submateri Berikutnya</a>
            <?php elseif ($is_materi_selesai): ?>
                <a class="btn btn-secondary rounded-0" href="#">Materi Sudah Selesai</a>
            <?php else: ?>
                <a class="btn btn-success rounded-0" href="selesai.php?id=<?php echo htmlspecialchars($sub_materi_data['id_materi']); ?>">Tandai Selesai</a>
            <?php endif; ?>

            <a href="<?php echo htmlspecialchars($sub_materi_data['file_path_dokumen']); ?>" class="btn btn-outline-primary rounded-0" download>
                Download Materi
            </a>
        </div>

        <hr class="my-5">

        <h6 class="text-muted mb-3">Submateri dalam materi ini:</h6>
        <div class="list-group list-group-flush border rounded-1">
            <?php foreach ($sub_materi_list as $sub): ?>
                <a href="detail-materi.php?id=<?= $sub['id_sub_materi'] ?>"
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                    <?= $sub['id_sub_materi'] == $sub_materi_id ? 'active bg-light border-start border-3 border-primary text-dark' : '' ?>">

                    <span><?= htmlspecialchars($sub['judul_sub_materi']) ?></span>

                    <?php if ($sub['id_sub_materi'] == $sub_materi_id): ?>
                        <small class="text-muted">Sedang dibuka</small>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>


    </div>
    <?php include_once(__DIR__ . "/../Views/footerbootsrap.php"); ?>
</body>

</html>