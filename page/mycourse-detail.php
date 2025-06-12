<?php
session_start();
$site_name = "Kelas Kita";
include "db.php";
$site_name = "KelasKita_baru"; 
$site_tagline = "Tingkatkan Keterampilan Anda, Raih Masa Depan Cemerlang"; 

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Ambil ID kursus dari parameter URL (meskipun tidak digunakan di halaman index ini, tetap ada untuk konsistensi)
$course_id = isset($_GET['id']) ? $_GET['id'] : '';

$course_data = [];

if (!empty($course_id)) {
    // Join tb_kelas → tb_mentor → tb_user
    $stmt = $conn->prepare("
        SELECT 
            kelas.*, 
            mentor.id_mentor, 
            user.username AS username_mentor
        FROM tb_kelas AS kelas
        LEFT JOIN tb_mentor AS mentor ON kelas.id_mentor = mentor.id_mentor
        LEFT JOIN tb_user AS user ON mentor.id_user = user.id_user
        WHERE kelas.id_kelas = ?
    ");
    $stmt->bind_param("i", $course_id);//mengikat parameter ? di sql dgn variabel $course_id
    $stmt->execute();//menjalankan query sql 
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $course_data = $result->fetch_assoc();
    } else {
        die("Kursus tidak ditemukan.");
    }

    $stmt->close();
}

$materi_list = []; //untuk menyimpan daftar materi

if (!empty($course_id)) { 
    // Ambil semua materi untuk kelas ini
    $stmt = $conn->prepare("SELECT * FROM tb_materi WHERE id_kelas = ?"); //mengambil semua materi yang memiliki id_kelas sesuai dengan $course_id.
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result_materi = $stmt->get_result();//Mengambil hasil query dan menyimpannya dalam variabel $result_materi.

    while ($materi = $result_materi->fetch_assoc()) {
        // Ambil sub_materi untuk materi ini
        $stmt2 = $conn->prepare("SELECT * FROM tb_sub_materi WHERE id_materi = ?");
        $stmt2->bind_param("i", $materi['id_materi']);
        $stmt2->execute();
        $result_sub = $stmt2->get_result();

        $sub_materi_list = [];
        while ($sub = $result_sub->fetch_assoc()) {
            $sub_materi_list[] = $sub;
        }

        $materi['sub_materi'] = $sub_materi_list;
        $materi_list[] = $materi;

        $stmt2->close();
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

        .course-header {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7)),
                url('https://i.imgur.com/7Yj7NYJ.png');
            
            background-size: cover;
            background-position: center;
            color: white;
            border-radius: 12px;
            padding: 2rem;
        }

        .nav-tabs .nav-link.active {
            border-bottom: 2px solid red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include_once(__DIR__ . "/../Views/navbarbootstrap.php"); ?>

    <div class="container mt-4">

        <!-- Course Header -->
        <div class="course-header mb-4">
            <div class="small fw-bold text-white-50"><?= $course_data['kategori'] ?></div>
            <h2 class="fw-bold mt-2"><?= $course_data['nama_kelas'] ?? 'Kursus Tidak Ditemukan' ?></h2>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="#">Course</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="report.php">Report</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="review.php?id_kelas=<?= $course_data['id_kelas'] ?>">Rating</a>
            </li>
        </ul>

        <!-- Content Section -->
        <div class="mb-4">
            <h5 class="fw-semibold">General</h5>

            <!-- Topic 1 -->
            <div class="accordion mb-3" id="accordionMateri">
                <?php foreach ($materi_list as $index => $materi): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $index ?>">
                            <button class="accordion-button <?= $index !== 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
                                <?= htmlspecialchars($materi['judul_materi']) ?>
                            </button>

                        </h2>
                        <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <?php foreach ($materi['sub_materi'] as $sub): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <?= htmlspecialchars($sub['judul_sub_materi']) ?>
                                            </div>
                                            <a href="detail-materi.php?id=<?= $sub['id_sub_materi'] ?>" class="btn btn-sm btn-outline-primary">
                                                Tonton Video
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php include_once(__DIR__ . "/../Views/footerbootsrap.php"); ?>
</body>

</html>