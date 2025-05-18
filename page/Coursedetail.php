<?php
session_start();
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "KelasKita";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

$site_name = "KelasKita"; // Define site name for footer usage

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID kursus dari parameter URL
$course_id = isset($_GET['id']) ? $_GET['id'] : '';

// Validasi ID kursus
if (!is_numeric($course_id)) {
    echo "ID Kursus tidak valid";
    exit;
}

// Add to cart logic
if (isset($_GET['add_to_cart']) && $_GET['add_to_cart'] == '1') {
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
        // Add new item
        $new_item = [
            'id' => $course['id'],
            'name' => $course['judul'],
            'price' => floatval(str_replace(['Rp', '.', ','], '', formatRupiah($course['harga']))),
            'quantity' => 1,
            'image' => $course['gambar'] ?? '',
            'category' => $course['kategori'] ?? ''
        ];
        $_SESSION['cart'][] = $new_item;
    }
    header("Location: cart.php");
    exit;
}

// Query untuk mengambil detail kursus
$sql = "SELECT * FROM kursus WHERE id = ?";
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

// Query untuk mengambil materi kursus
$sql_materi = "SELECT * FROM materi_kursus WHERE kursus_id = ? ORDER BY urutan ASC";
$stmt_materi = $conn->prepare($sql_materi);
$stmt_materi->bind_param("i", $course_id);
$stmt_materi->execute();
$result_materi = $stmt_materi->get_result();

// Query untuk mengambil ulasan kursus
$sql_ulasan = "SELECT u.*, p.nama, p.foto 
               FROM ulasan u 
               JOIN pelajar p ON u.pelajar_id = p.id 
               WHERE u.kursus_id = ? 
               ORDER BY u.tanggal DESC
               LIMIT 5";
$stmt_ulasan = $conn->prepare($sql_ulasan);
$stmt_ulasan->bind_param("i", $course_id);
$stmt_ulasan->execute();
$result_ulasan = $stmt_ulasan->get_result();

$instruktur = null;
if (isset($course['instruktur_id'])) {
    $sql_instruktur = "SELECT * FROM instruktur WHERE id = ?";
    $stmt_instruktur = $conn->prepare($sql_instruktur);
    $stmt_instruktur->bind_param("i", $course['instruktur_id']);
    $stmt_instruktur->execute();
    $result_instruktur = $stmt_instruktur->get_result();
    $instruktur = $result_instruktur->fetch_assoc();
    $stmt_instruktur->close();
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

$stmt->close();
$stmt_materi->close();
$stmt_ulasan->close();
if (isset($stmt_instruktur)) {
    $stmt_instruktur->close();
}
// Remove $conn->close() here to avoid closing connection before footer queries
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
                                case 'primary':
                                    $kategori_label = 'Web Development';
                                    break;
                                case 'warning':
                                    $kategori_label = 'Digital Marketing';
                                    break;
                                case 'success':
                                    $kategori_label = 'Data Science';
                                    break;
                                default:
                                    $kategori_label = $kategori;
                            }
                            echo htmlspecialchars($kategori_label); 
                            ?>
                        </span>
                    </div>
                    <h1 class="mb-3"><?php echo htmlspecialchars($course['judul'] ?? 'Judul Kursus'); ?></h1>
                    <p class="mb-2"><?php echo htmlspecialchars($course['deskripsi_singkat'] ?? 'Deskripsi singkat kursus tidak tersedia.'); ?></p>
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
                        <span class="me-3"><?php echo $course['rating'] ?? '0'; ?> (<?php echo $course['jumlah_ulasan'] ?? '0'; ?> ulasan)</span>
                        <span class="me-3"><i class="fas fa-users me-1"></i> <?php echo $course['jumlah_pelajar'] ?? '0'; ?> pelajar</span>
                        <span><i class="fas fa-clock me-1"></i> <?php echo $course['durasi'] ?? '0'; ?> jam</span>
                    </div>
                    <div class="mt-3">
                        <p class="mb-0">
                            <i class="fas fa-calendar-alt me-1"></i> Terakhir diperbarui: 
                            <?php 
                            $tanggal_update = $course['tanggal_update'] ?? '';
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
                    <div class="course-price mb-3"><?php echo formatRupiah($course['harga'] ?? 0); ?></div>
                    <a href="Coursedetail.php?id=<?php echo $course['id']; ?>&add_to_cart=1" class="course-button">Daftar Sekarang</a>
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
                        <?php echo $course['deskripsi_lengkap'] ?? 'Deskripsi lengkap tidak tersedia.'; ?>
                    </div>
                </div>

                <!-- Course Material -->
                <div class="course-content">
                    <h3 class="mb-4">Materi Pembelajaran</h3>
                    <div class="accordion" id="accordionMaterial">
                        <?php
                        $current_section = '';
                        $section_counter = 0;
                        
                        if ($result_materi->num_rows > 0) {
                            while ($materi = $result_materi->fetch_assoc()) {
                                if ($current_section != $materi['section']) {
                                    if ($current_section != '') {
                                        echo '</div></div></div>';
                                    }
                                    $current_section = $materi['section'];
                                    $section_counter++;
                                    ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo $section_counter; ?>">
                                            <button class="accordion-button <?php echo ($section_counter > 1) ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $section_counter; ?>" aria-expanded="<?php echo ($section_counter == 1) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $section_counter; ?>">
                                                <strong>Section <?php echo $section_counter; ?>: <?php echo htmlspecialchars($materi['section']); ?></strong>
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo $section_counter; ?>" class="accordion-collapse collapse <?php echo ($section_counter == 1) ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $section_counter; ?>" data-bs-parent="#accordionMaterial">
                                            <div class="accordion-body">
                                <?php
                                }
                                ?>
                                <div class="course-material">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php 
                                            $icon = '';
                                            $tipe = $materi['tipe'] ?? '';
                                            switch ($tipe) {
                                                case 'video':
                                                    $icon = 'fas fa-play-circle';
                                                    break;
                                                case 'dokumen':
                                                    $icon = 'fas fa-file-alt';
                                                    break;
                                                case 'quiz':
                                                    $icon = 'fas fa-question-circle';
                                                    break;
                                                default:
                                                    $icon = 'fas fa-file';
                                            }
                                            ?>
                                            <i class="<?php echo $icon; ?> me-2"></i>
                                            <?php echo htmlspecialchars($materi['judul'] ?? 'Judul materi tidak tersedia'); ?>
                                        </div>
                                        <div>
                                            <span class="badge bg-secondary"><?php echo $materi['durasi'] ?? '0 menit'; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            if ($current_section != '') {
                                echo '</div></div></div>'; // Close the last section
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
                    <a href="Coursedetail.php?id=<?php echo $course['id']; ?>&add_to_cart=1" class="btn btn-primary btn-lg w-100">Daftar Sekarang</a>
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
    <!-- Footer -->
<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row">
            <!-- Tentang & Kontak -->
            <div class="col-lg-3 col-md-6 mb-4">
                <?php
                // Query untuk mengambil info website
                $sql_site_info = "SELECT logo, tentang, email, telepon, alamat FROM site_info LIMIT 1";
                $result_site_info = $conn->query($sql_site_info);
                
                if ($result_site_info && $result_site_info->num_rows > 0) {
                    $site_info = $result_site_info->fetch_assoc();
                    echo '<img src="' . $site_info['logo'] . '" alt="KelasKita Logo" height="40" class="mb-4">
                    <p>' . $site_info['tentang'] . '</p>
                    <div class="mt-3">
                        <p><i class="fas fa-envelope me-2"></i> ' . $site_info['email'] . '</p>
                        <p><i class="fas fa-phone me-2"></i> ' . $site_info['telepon'] . '</p>
                        <p><i class="fas fa-map-marker-alt me-2"></i>' . $site_info['alamat'] . '</p>
                    </div>';
                } else {
                    echo '<img src="../assets/images/ChatGPT Image 13 Mei 2025, 12.52.09.png" alt="KelasKita Logo" height="40" width="40" class="mb-4">
                    <p>Platform pembelajaran online terkemuka yang menyediakan kursus berkualitas tinggi untuk membantu Anda mengembangkan keterampilan dan memajukan karier.</p>
                    <div class="mt-3">
                        <p><i class="fas fa-envelope me-2"></i> info@KelasKita.co.id</p>
                        <p><i class="fas fa-phone me-2"></i> +62 21 12345678</p>
                        <p><i class="fas fa-map-marker-alt me-2"></i>Jl. Telekomunikasi No. 1, Bandung Terusan Buahbatu - Bojongsoang, Sukapura, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40257</p>
                    </div>';
                }
                ?>
                <div class="mt-4">
                    <a href="#" class="me-3 text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="me-3 text-white"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="me-3 text-white"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="me-3 text-white"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    <a href="#" class="me-3 text-white"><i class="fab fa-youtube fa-lg"></i></a>
                </div>
            </div>

            <!-- Tautan Cepat -->
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h5 class="mb-4">Tautan Cepat</h5>
                <ul class="list-unstyled">
                    <?php
                    // Query untuk mengambil quick links dari database
                    $sql_quick_links = "SELECT url, text FROM quick_links ORDER BY urutan ASC LIMIT 6";
                    $result_quick_links = $conn->query($sql_quick_links);
                    
                    if ($result_quick_links && $result_quick_links->num_rows > 0) {
                        while($link = $result_quick_links->fetch_assoc()) {
                            echo '<li class="mb-2"><a href="' . $link['url'] . '" class="text-white text-decoration-none">' . $link['text'] . '</a></li>';
                        }
                    } else {
                        // Data default jika tidak ada data di database
                        $default_links = [
                            ["url" => "index.php", "text" => "Beranda"],
                            ["url" => "courses.php", "text" => "Kursus"],
                            ["url" => "bootcamp.php", "text" => "Bootcamp"],
                            ["url" => "about.php", "text" => "Tentang Kami"],
                            ["url" => "contact.php", "text" => "Kontak"],
                            ["url" => "faq.php", "text" => "FAQ"]
                        ];
                        
                        foreach ($default_links as $link) {
                            echo '<li class="mb-2"><a href="' . $link['url'] . '" class="text-white text-decoration-none">' . $link['text'] . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
            <!-- Kategori -->
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h5 class="mb-4">Kategori</h5>
                <ul class="list-unstyled">
                    <?php
                    // Query untuk mengambil kategori untuk footer
                    $sql_category_links = "SELECT nama_kategori 
                       FROM kategori_kursus 
                       ORDER BY jumlah_kursus DESC 
                       LIMIT 6";
                    $result_category_links = $conn->query($sql_category_links);
                    
                    if ($result_category_links && $result_category_links->num_rows > 0) {
                        while($category = $result_category_links->fetch_assoc()) {
                            echo '<li class="mb-2"><a href="category.php?cat=' . urlencode($category['nama_kategori']) . '" class="text-white text-decoration-none">' . $category['nama_kategori'] . '</a></li>';
                        }
                    } else {
                        // Data default jika tidak ada data di database
                        $default_categories = [
                            "Pengembangan Web",
                            "Pengembangan Mobile",
                            "Data Science",
                            "UI/UX Design",
                            "Digital Marketing",
                            "Business & Leadership"
                        ];
                        
                        foreach ($default_categories as $category) {
                            echo '<li class="mb-2"><a href="#" class="text-white text-decoration-none">' . $category . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>

            <!-- Dukungan -->
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h5 class="mb-4">Dukungan</h5>
                <ul class="list-unstyled">
                    <?php
                    // Query untuk mengambil support links
                    $sql_support_links = "SELECT judul FROM support_links ORDER BY id ASC LIMIT 6";
                    $result_support_links = $conn->query($sql_support_links);
                    
                    if ($result_support_links->num_rows > 0) {
                        while($support = $result_support_links->fetch_assoc()) {
                            echo '<li class="mb-2"><a href="#" class="text-white text-decoration-none">' . $support['judul'] . '</a></li>';
                        }
                    } else {
                        // Data default jika tidak ada data di database
                        $default_support = [
                            "Pusat Bantuan",
                            "Kebijakan Privasi",
                            "Syarat & Ketentuan",
                            "Kebijakan Refund",
                            "Laporan Bug",
                            "Affiliate Program"
                        ];
                        
                        foreach ($default_support as $support) {
                            echo '<li class="mb-2"><a href="#" class="text-white text-decoration-none">' . $support . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>

            <!-- Download Aplikasi -->
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h5 class="mb-4">Aplikasi Mobile</h5>
                <p>Belajar dari mana saja dengan aplikasi mobile kami</p>
                <?php
                // Query untuk mengambil app links
                $sql_app_links = "SELECT playstore
                , appstore FROM app_links LIMIT 1";
                $result_app_links = $conn->query($sql_app_links);
                
                if ($result_app_links->num_rows > 0) {
                    $app_links = $result_app_links->fetch_assoc();
                    echo '<div class="mb-3">
                        <a href="' . $app_links['playstore'] . '" class="d-block mb-2">
                            <img src="../assets/images/c63de450df4c84bc4f7d1b0a762d8d56.jpg" alt="Google Play Store" height="50">
                        </a>
                        <a href="' . $app_links['appstore'] . '" class="d-block">
                            <img src="../assets/images/7b51ea487052d8996a3c232fa23500c6.jpg" alt="Apple App Store" width="100" height="60">
                        </a>
                    </div>';
                } else {
                    echo '<div class="mb-3">
                        <a href="#" class="d-block mb-2">
                            <img src="../assets/images/c63de450df4c84bc4f7d1b0a762d8d56.jpg" alt="Google Play Store" height="50">
                        </a>
                        <a href="#" class="d-block">
                            <img src="../assets/images/7b51ea487052d8996a3c232fa23500c6.jpg" alt="Apple App Store" width="100" height="60">
                        </a>
                    </div>';
                }
                ?>
            </div>
        </div>
        
        <hr class="my-4 bg-secondary">
        
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo $site_name; ?>. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0">Designed & Developed by KelasKita Dev Team</p>
            </div>
        </div>
    </div>
</footer>


    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>