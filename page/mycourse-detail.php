<?php
session_start();
$site_name = "Kelas Kita";

// --- KONEKSI DATABASE ---
$host = "localhost";
$username = "root";
$password = "";
$database = "KelasKita_baru";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    // Tampilkan error koneksi yang lebih informatif dan hentikan skrip
    die("Koneksi database gagal: " . $conn->connect_error . ". Pastikan detail koneksi sudah benar dan server database berjalan.");
}

// --- INISIALISASI VARIABEL ---
$site_tagline = "Tingkatkan Keterampilan Anda, Raih Masa Depan Cemerlang";
$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Ambil ID sebagai integer
$course_data = [];
$materi_list = [];
$error_message = ''; // Variabel untuk menyimpan pesan error spesifik

// --- LOGIKA UTAMA ---
if ($course_id <= 0) {
    // KASUS 1: ID tidak valid atau tidak ada di URL
    $error_message = "Akses tidak valid. Silakan kembali ke daftar kursus dan pilih salah satu. (Parameter ID tidak ditemukan di URL).";
} else {
    // KASUS 2: ID ada di URL, lanjutkan pencarian di database
    
    // Ambil data kelas dan mentor
    $stmt = $conn->prepare("
        SELECT
            kelas.*,
            mentor.id_mentor,
            user.first_name AS mentor_first_name,
            user.last_name AS mentor_last_name,
            user.username AS username_mentor
        FROM tb_kelas AS kelas
        LEFT JOIN tb_mentor AS mentor ON kelas.id_mentor = mentor.id_mentor
        LEFT JOIN tb_user AS user ON mentor.id_user = user.id_user
        WHERE kelas.id_kelas = ?
    ");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $course_data = $result->fetch_assoc();
    } else {
        // Data tidak ditemukan di database dengan ID yang diberikan
        $error_message = "Kursus dengan ID '" . htmlspecialchars($course_id) . "' tidak dapat ditemukan. Pastikan ID yang Anda akses sudah benar.";
    }
    $stmt->close();

    // Jika data kursus berhasil ditemukan, ambil materinya
    if (!empty($course_data)) {
        $stmt_materi = $conn->prepare("SELECT * FROM tb_materi WHERE id_kelas = ?");
        $stmt_materi->bind_param("i", $course_id);
        $stmt_materi->execute();
        $result_materi = $stmt_materi->get_result();

        while ($materi = $result_materi->fetch_assoc()) {
            $stmt2 = $conn->prepare("SELECT * FROM tb_sub_materi WHERE id_materi = ?");
            $id_materi = $materi['id_materi'];
            $stmt2->bind_param("i", $id_materi);
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
        $stmt_materi->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_name); ?> - Detail Kursus</title>
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

        .course-header {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7)),
                url('https://i.imgur.com/7Yj7NYJ.png');
            background-size: cover;
            background-position: center;
            color: white;
            border-radius: 12px;
            padding: 2rem;
            min-height: 200px; /* Memberi tinggi minimum agar terlihat baik meski ada error */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .nav-tabs .nav-link.active {
            border-bottom: 2px solid var(--primary);
            color: var(--primary);
            font-weight: bold;
        }
        .accordion-button:not(.collapsed) {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }
    </style>
</head>

<body>
    <?php // include_once(__DIR__ . "/../Views/navbarbootstrap.php"); // Non-aktifkan jika file tidak ada atau sesuaikan path ?>
    
    <div class="container mt-4">

        <div class="course-header mb-4">
            <?php if (!empty($error_message)): ?>
                <h2 class="fw-bold mt-2">Terjadi Kesalahan</h2>
                <p class="text-white-50 lead"><?php echo $error_message; ?></p>

            <?php elseif (!empty($course_data)): ?>
                <div class="small fw-bold text-white-50"><?= htmlspecialchars($course_data['kategori']) ?></div>
                <h2 class="fw-bold mt-2"><?= htmlspecialchars($course_data['nama_kelas']) ?></h2>
                <p class="text-white-50">Mentor: 
                    <?php echo htmlspecialchars($course_data['mentor_first_name'] . ' ' . $course_data['mentor_last_name']); ?>
                </p>
            <?php endif; ?>
        </div>

        <?php if (empty($error_message) && !empty($course_data)): ?>
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Course</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="report.php">Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="review.php?id_kelas=<?= htmlspecialchars($course_data['id_kelas']) ?>">Rating</a>
                </li>
            </ul>

            <div class="mb-4">
                <h5 class="fw-semibold">Materi Pembelajaran</h5>

                <div class="accordion mb-3" id="accordionMateri">
                    <?php if (!empty($materi_list)): ?>
                        <?php foreach ($materi_list as $index => $materi): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $index ?>">
                                    <button class="accordion-button <?= $index !== 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
                                        <?= htmlspecialchars($materi['judul_materi']) ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#accordionMateri">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <?php if (!empty($materi['sub_materi'])): ?>
                                                <?php foreach ($materi['sub_materi'] as $sub): ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-play-circle me-2 text-primary"></i>
                                                            <?= htmlspecialchars($sub['judul_sub_materi']) ?>
                                                        </div>
                                                        <a href="detail-materi.php?id=<?= htmlspecialchars($sub['id_sub_materi']) ?>" class="btn btn-sm btn-outline-primary">
                                                            Tonton Video
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <li class="list-group-item">Belum ada sub-materi untuk bagian ini.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Tidak ada materi ditemukan untuk kursus ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php // include_once(__DIR__ . "/../Views/footerbootsrap.php"); // Non-aktifkan jika file tidak ada atau sesuaikan path ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>