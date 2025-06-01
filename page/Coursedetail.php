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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course_id = isset($_GET['id']) ? $_GET['id'] : '';

// Validasi ID kursus
if (!is_numeric($course_id)) {
    echo "ID Kursus tidak valid";
    exit;
}

// Fungsi untuk memformat harga
function formatRupiah($angka) {
    // Memastikan angka adalah numerik
    if (is_string($angka) && strpos($angka, 'Rp') !== false) {
        // Jika string sudah mengandung 'Rp', hilangkan dan bersihkan formatnya
        $angka = str_replace('Rp', '', $angka);
        $angka = str_replace('.', '', $angka);
        $angka = str_replace(',', '', $angka);
        $angka = trim($angka);
    }
    
    // Memastikan angka adalah numerik
    $angka = floatval($angka);
    
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function checkExistingTables($conn) {
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
    }
    return $tables;
}

$existing_tables = checkExistingTables($conn);

$course_table = 'tb_kelas';

$sql = "SELECT * FROM $course_table WHERE id_kelas = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
} else {
    echo "Kursus tidak ditemukan";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Debug: Add to cart POST request received
    // error_log("Add to cart POST request received for course ID: " . $course_id);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    // Check if course already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $course_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }
    if (!$found) {
        // Add new item - menggunakan kolom yang benar
        $new_item = [
            'id' => $course['id_kelas'],
            'name' => $course['judul'] ?? 'Kursus',
            'price' => floatval(str_replace(['Rp', '.', ','], '', formatRupiah($course['harga'] ?? 0))),
            'quantity' => 1,
            'image' => $course['gambar'] ?? '',
            'category' => $course['kategori'] ?? ''
        ];
        $_SESSION['cart'][] = $new_item;
    }
    header("Location: cart.php");
    exit;
}

// Query untuk mengambil materi kursus - menggunakan nama tabel yang benar
$sql_materi = "SELECT * FROM tb_materi WHERE id_kelas = ? ORDER BY urutan ASC";
$stmt_materi = $conn->prepare($sql_materi);
$stmt_materi->bind_param("i", $course['id_kelas']);
$stmt_materi->execute();
$result_materi = $stmt_materi->get_result();

// Query untuk mengambil ulasan kursus
$sql_ulasan = "SELECT u.*, CONCAT(p.first_name, ' ', p.last_name) AS nama, p.fotoProfil AS foto 
               FROM tb_ulasan u 
               JOIN tb_user p ON u.pelajar_id = p.id_user 
               WHERE u.kursus_id = ? 
               ORDER BY u.tanggal DESC
               LIMIT 5";
$stmt_ulasan = $conn->prepare($sql_ulasan);
$stmt_ulasan->bind_param("i", $course_id);
$stmt_ulasan->execute();
$result_ulasan = $stmt_ulasan->get_result();

$instruktur = null;
$mentor_id = $course['instruktur_id'] ?? $course['id_mentor'] ?? null;
if ($mentor_id) {
    $sql_instruktur = "SELECT *, CONCAT(first_name, ' ', last_name) AS nama FROM tb_user WHERE id_user = ? AND role = 'mentor'";
    $stmt_instruktur = $conn->prepare($sql_instruktur);
    $stmt_instruktur->bind_param("i", $mentor_id);
    $stmt_instruktur->execute();
    $result_instruktur = $stmt_instruktur->get_result();
    $instruktur = $result_instruktur->fetch_assoc();
    $stmt_instruktur->close();
}

$stmt->close();
$stmt_materi->close();
$stmt_ulasan->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['judul'] ?? 'Detail Kursus'); ?> - Detail Kursus | KelasKita</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }
        .course-header {
            background-color: #343a40;
            color: white;
            padding: 3rem 0;
        }
        .course-price {
            font-size: 1.8rem;
            font-weight: bold;
            color: #0d6efd;
        }
        .course-category {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .category-primary {
            background-color: #0d6efd;
            color: white;
        }
        .category-warning {
            background-color: #ffc107;
            color: #343a40;
        }
        .category-success {
            background-color: #198754;
            color: white;
        }
        .rating-stars {
            color: #ffc107;
        }
        .course-content {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .instructor-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .course-material {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .review-item {
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 0;
        }
        .review-item:last-child {
            border-bottom: none;
        }
        .review-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .course-button {
            display: inline-block;
            padding: 10px 30px;
            background-color: #0d6efd;
            color: white;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .course-button:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<?php include_once("../Views/navbarbootstrap.php"); ?>

    <!-- Course Header -->
    <div class="course-header">
        <div class="container">
            <div class="mb-3">
            </div>
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="mb-3">
                        <?php
                    $kategori = $course['kategori'] ?? 'primary';
                    $categoryClass = '';
                    switch ($kategori) {
                        case 'primary':
                            $categoryClass = 'category-primary';
                            break;
                        case 'warning':
                            $categoryClass = 'category-warning';
                            break;
                        case 'success':
                            $categoryClass = 'category-success';
                            break;
                        default:
                            $categoryClass = 'category-primary';
                    }
                    ?>
                    <span class="course-category <?php echo $categoryClass; ?>">
                        <?php 
                        $kategori_label = '';
                        switch ($kategori) {
                            case 'SQL':
                                $kategori_label = 'SQL';
                                break;
                            case 'Design':
                                $kategori_label = 'Design';
                                break;
                            case 'Java':
                                $kategori_label = 'Java';
                                break;
                            case 'Web Development':
                                $kategori_label = 'Web Development';
                                break;
                            case 'Bisnis':
                                $kategori_label = 'Bisnis';
                                break;
                            case 'Ekonomi':
                                $kategori_label = 'Ekonomi';
                                break;
                            case 'Psikologi':
                                $kategori_label = 'Psikologi';
                                break;
                            case 'IT':
                                $kategori_label = 'IT';
                                break;
                            case 'Python':
                                $kategori_label = 'Python';
                                break;
                            default:
                                $kategori_label = $kategori;
                        }
                        echo htmlspecialchars($kategori_label); 
                        ?>
                    </span>
                </div>
                <h1 class="mb-3"><?php echo htmlspecialchars($course['nama_kelas'] ?? 'Judul Kursus'); ?></h1>
                <p class="mb-2"><?php echo htmlspecialchars($course['description'] ?? 'Deskripsi singkat kursus tidak tersedia.'); ?></p>
                <div class="d-flex align-items-center mt-3">
                    <div class="rating-stars me-2">
                        <?php
                        $rating = $course['rating'] ?? 0;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ($i - 0.5 <= $rating) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <span class="me-3"><?php echo number_format(floatval($course['rating']), 1) ?? '0'; ?> (<?php echo $course['jumlah_ulasan'] ?? '0'; ?> ulasan)</span>
                    <span class="me-3"><i class="fas fa-user-graduate me-1"></i> <?php echo intval($course['jumlah_peserta']) ?? '0'; ?> peserta</span>
                    <span><i class="fas fa-clock me-1"></i> <?php echo $course['durasi'] ?? '0'; ?> jam</span>
                </div>
                <div class="mt-3">
                    <p class="mb-0">
                        <i class="fas fa-calendar-alt me-1"></i> Terakhir diperbarui: 
                        <?php 
                        $tanggal_update = $course['tanggal_rilis'] ?? '';
                        echo $tanggal_update ? date('d M Y', strtotime($tanggal_update)) : 'Tidak tersedia';
                        ?>
                    </p>
                    <?php if ($instruktur): ?>
                    <p class="mb-0">
                        <i class="fas fa-user-tie me-1"></i> Instruktur: <?php echo htmlspecialchars($instruktur['nama'] ?? 'Tidak tersedia'); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
<div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
    <?php
$price = intval($course['harga']);
?>
<div class="course-price fw-bold text-primary" style="font-size: 1.1rem;">
    Rp <?php echo number_format($price, 0, ',', '.'); ?>
</div>
    <form method="post" action="Coursedetail.php?id=<?php echo $course['id_kelas']; ?>">
        <input type="hidden" name="add_to_cart" value="1">
        <button type="submit" class="course-button">Daftar Sekarang</button>
    </form>
</div>
        </div>
    </div>
</div>

    <!-- Course Content -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Course Description -->
                <div class="course-content">
                    <h3 class="mb-4">Deskripsi Kursus</h3>
                    <div>
                        <?php echo $course['deskripsi'] ?? 'Deskripsi lengkap tidak tersedia.'; ?>
                    </div>
                </div>

                <!-- Course Material -->
                <div class="course-content">
                    <h3 class="mb-4">Materi Pembelajaran</h3>
                    <div class="accordion" id="accordionMaterial">
                        <?php
                        if ($result_materi->num_rows > 0) {
                            $counter = 0;
                            while ($materi = $result_materi->fetch_assoc()) {
                                $counter++;
                                ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?php echo $counter; ?>">
                                        <button class="accordion-button <?php echo ($counter > 1) ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $counter; ?>" aria-expanded="<?php echo ($counter == 1) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $counter; ?>">
                                            <strong>Materi <?php echo $counter; ?>: <?php echo htmlspecialchars($materi['judul_materi'] ?? 'Judul Materi'); ?></strong>
                                        </button>
                                    </h2>
                                    <div id="collapse<?php echo $counter; ?>" class="accordion-collapse collapse <?php echo ($counter == 1) ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $counter; ?>" data-bs-parent="#accordionMaterial">
                                        <div class="accordion-body">
                                            <div class="course-material">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-file me-2"></i>
                                                        <?php echo htmlspecialchars($materi['judul_materi'] ?? 'Judul materi tidak tersedia'); ?>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-secondary">Materi ke-<?php echo $materi['urutan'] ?? $counter; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>Belum ada materi yang tersedia untuk kursus ini.</p>";
                        }
                        ?>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="course-content">
                    <h3 class="mb-4">Ulasan Pelajar</h3>
                    <?php
                    if ($result_ulasan->num_rows > 0) {
                        while ($ulasan = $result_ulasan->fetch_assoc()) {
                            ?>
                            <div class="review-item">
                                <div class="d-flex align-items-start">
                                    <img src="<?php echo isset($ulasan['foto']) && !empty($ulasan['foto']) ? $ulasan['foto'] : 'img/pelajar/default.jpg'; ?>" alt="<?php echo htmlspecialchars($ulasan['nama'] ?? 'Pelajar'); ?>" class="review-img me-3">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <h5 class="mb-0"><?php echo htmlspecialchars($ulasan['nama'] ?? 'Pelajar'); ?></h5>
                                                <div class="rating-stars">
                                                    <?php
                                                    $ulasan_rating = $ulasan['rating'] ?? 0;
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= $ulasan_rating) {
                                                            echo '<i class="fas fa-star"></i>';
                                                        } else {
                                                            echo '<i class="far fa-star"></i>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                <?php 
                                                $tanggal_ulasan = $ulasan['tanggal'] ?? '';
                                                echo $tanggal_ulasan ? date('d M Y', strtotime($tanggal_ulasan)) : 'Tanggal tidak tersedia';
                                                ?>
                                            </small>
                                        </div>
                                        <p class="mb-0"><?php echo htmlspecialchars($ulasan['komentar'] ?? 'Tidak ada komentar.'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>Belum ada ulasan untuk kursus ini.</p>";
                    }
                    ?>
                    <a href="all-reviews.php?id=<?php echo $course_id; ?>" class="btn btn-outline-primary mt-3">Lihat Semua Ulasan</a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Course Image -->
                <div class="course-content mb-4">
                    <img src="<?php echo $course['gambar'] ?? 'img/courses/default.jpg'; ?>" alt="<?php echo htmlspecialchars($course['judul'] ?? 'Kursus'); ?>" class="img-fluid rounded mb-3">
                    <a href="Coursedetail.php?id=<?php echo $course['id_kelas']; ?>&add_to_cart=1" class="btn btn-primary btn-lg w-100">Daftar Sekarang</a>
                </div>
                
                <!-- Course Features -->
                <div class="course-content mb-4">
                    <h4 class="mb-3">Fitur Kursus</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-film me-2"></i> Jumlah Video</span>
                            <span><?php echo $course['jumlah_video'] ?? '0'; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-file-alt me-2"></i> Materi</span>
                            <span><?php echo $course['jumlah_materi'] ?? '0'; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-download me-2"></i> Resources</span>
                            <span><?php echo $course['jumlah_resource'] ?? '0'; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-infinity me-2"></i> Akses</span>
                            <span>Selamanya</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-certificate me-2"></i> Sertifikat</span>
                            <span><?php echo (isset($course['ada_sertifikat']) && $course['ada_sertifikat']) ? 'Ya' : 'Tidak'; ?></span>
                        </li>
                    </ul>
                </div>
                
                <!-- Instructor Info -->
                <?php if ($instruktur) : ?>
                <div class="course-content">
                    <h4 class="mb-3">Instruktur</h4>
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?php echo $instruktur['foto'] ?? 'img/instruktur/default.jpg'; ?>" alt="<?php echo htmlspecialchars($instruktur['nama'] ?? 'Instruktur'); ?>" class="instructor-img me-3">
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($instruktur['nama'] ?? 'Instruktur'); ?></h5>
                            <p class="mb-0 text-muted"><?php echo htmlspecialchars($instruktur['bidang'] ?? 'Belum tersedia'); ?></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-star text-warning me-2"></i>
                            <span><?php echo $instruktur['rating'] ?? '0'; ?> Rating</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-graduate me-2"></i>
                            <span><?php echo $instruktur['jumlah_pelajar'] ?? '0'; ?> Pelajar</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-play-circle me-2"></i>
                            <span><?php echo $instruktur['jumlah_kursus'] ?? '0'; ?> Kursus</span>
                        </div>
                    </div>
                    <p class="mb-0"><?php echo htmlspecialchars($instruktur['bio_singkat'] ?? 'Informasi instruktur belum tersedia.'); ?></p>
                    <a href="instructor-profile.php?id=<?php echo $instruktur['id']; ?>" class="btn btn-outline-secondary mt-3 w-100">Lihat Profil</a>
                </div>
                <?php endif; ?>
                
                <!-- Related Courses -->
                <div class="course-content">
                    <h4 class="mb-3">Butuh Bantuan?</h4>
                    <p>Jika Anda memiliki pertanyaan tentang kursus ini, jangan ragu untuk menghubungi kami:</p>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        <span>support@kelaskita.com</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-phone me-2"></i>
                        <span>+62 123 4567 890</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once("../Views/footerbootsrap.php"); ?>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>