<?php
// Mulai session jika diperlukan
session_start();

// Konfigurasi situs
$site_name = "KelasKita";
$site_tagline = "Platform Pembelajaran Online";

// Koneksi ke database
$host = "localhost";    // sesuaikan dengan host database Anda
$username = "root";     // sesuaikan dengan username database Anda
$password = "";         // sesuaikan dengan password database Anda
$database = "kelaskita"; // sesuaikan dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_name; ?> - <?php echo $site_tagline; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
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
        
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .testimonial-card {
            padding: 20px;
            text-align: center;
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
    </style>
</head>
<body class="bg-gray-50">
<?php include_once("../Views/navbarbootstrap.php"); ?>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
        // Klik di luar dropdown untuk nutup
        document.addEventListener('click', function handleOutsideClick(event) {
            if (!dropdown.contains(event.target) && !event.target.closest('button')) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', handleOutsideClick);
            }
        });
    }
</script>

<!-- Hero Section dengan Gambar Unggulan & CTA -->
<section class="hero-section" style="background: linear-gradient(rgba(3, 3, 176, 0.47), rgb(15, 167, 232)), url('path-to-hero-image.jpg'); background-size: cover; background-position: center; color: white; padding: 100px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Tingkatkan Keterampilan Anda, Raih Masa Depan Cemerlang</h1>
                <p class="lead mb-4">Platform pembelajaran online terkemuka yang menawarkan kursus berkualitas untuk membantu Anda mengembangkan keterampilan dan memajukan karier.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="courses.php" class="btn btn-primary btn-lg">Pelajari Sekarang</a>
                    <a href="register.php" class="btn btn-outline-light btn-lg">Berlangganan</a>
                </div>
            </div>
            <div class="col-lg-4">
                <img src="../assets/images/1683125533-img1.avif" alt="Gambar tambahan" class="img-fluid mb-10">
            </div>
        </div>
    </div>
</section>

<!-- Kursus Berdasarkan Kategori -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Jelajahi Kursus Berdasarkan Kategori</h2>
            <p>Temukan bidang minat Anda dan mulai perjalanan pembelajaran Anda hari ini</p>
        </div>
        
        <div class="row">
            <?php
            // Query untuk mengambil kategori kursus dari database
            $sql_categories = "SELECT icon, nama_kategori, jumlah_kursus FROM kategori_kursus ORDER BY jumlah_kursus DESC LIMIT 4";
            $result_categories = $conn->query($sql_categories);
            
            if ($result_categories->num_rows > 0) {
                while($row = $result_categories->fetch_assoc()) {
                    echo '<div class="col-md-3 col-6 mb-4">
                        <div class="card text-center p-4">
                            <div class="feature-icon">
                                <i class="' . $row['icon'] . '"></i>
                            </div>
                            <h5>' . $row['nama_kategori'] . '</h5>
                            <p class="small">' . $row['jumlah_kursus'] . '+ Kursus</p>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12 text-center">Tidak ada kategori kursus ditemukan</div>';
            }
            ?>
        </div>
        <div class="text-center mt-4">
            <a href="categories.php" class="btn btn-outline-primary">Lihat Semua Kategori</a>
        </div>
    </div>
</section>

<!-- Kelas Unggulan & Populer -->
<section class="section-padding">
    <div class="container">
        <div class="section-title">
            <h2>Kursus Unggulan & Terpopuler</h2>
            <p>Kursus yang direkomendasikan dan paling banyak diambil oleh pelajar kami</p>
        </div>
        
        <div class="row">
            <?php
            // Query untuk mengambil kursus unggulan dari database
            $sql_featured_courses = "
                SELECT 
                    k.id, 
                    k.gambar, 
                    k.badge_type, 
                    k.badge_text, 
                    k.judul, 
                    k.jumlah_peserta, 
                    k.rating, 
                    k.deskripsi, 
                    k.harga 
                FROM kursus k 
                WHERE k.featured = 1 
                ORDER BY k.jumlah_peserta DESC 
                LIMIT 3
            ";
            $result_featured = $conn->query($sql_featured_courses);

            if ($result_featured->num_rows > 0) {
                while ($course = $result_featured->fetch_assoc()) {
                    echo '
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <!-- Gambar Kursus -->
                            <img src="' . htmlspecialchars($course['gambar']) . '" class="card-img-top" alt="' . htmlspecialchars($course['judul']) . '">
                            <div class="card-body">
                                <span class="badge bg-' . htmlspecialchars($course['badge_type']) . ' mb-2">' . htmlspecialchars($course['badge_text']) . '</span>
                                <h5 class="card-title">' . htmlspecialchars($course['judul']) . '</h5>
                                <div class="d-flex justify-content-between mb-3">
                                    <span><i class="fas fa-user-graduate"></i> ' . intval($course['jumlah_peserta']) . '+ peserta</span>
                                    <span><i class="fas fa-star text-warning"></i> ' . floatval($course['rating']) . '</span>
                                </div>
                                <p class="card-text">' . htmlspecialchars($course['deskripsi']) . '</p>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">' . htmlspecialchars($course['harga']) . '</span>
                                    <a href="course-details.php?id=' . intval($course['id']) . '" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12 text-center">Tidak ada kursus unggulan ditemukan</div>';
            }
            ?>
        </div>
        <div class="text-center mt-4">
            <a href="../course-listing.php" class="btn btn-primary">Lihat Semua Kursus</a>
        </div>
    </div>
</section>

<!-- Mengapa Memilih Kami -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Mengapa Memilih Kami</h2>
            <p>Keunggulan platform kami yang membedakan dari yang lain</p>
        </div>
        
        <div class="row">
            <?php
            // Query untuk mengambil keunggulan dari database
            $sql_features = "SELECT icon, judul, deskripsi FROM keunggulan ORDER BY id ASC LIMIT 4";
            $result_features = $conn->query($sql_features);
            
            if ($result_features->num_rows > 0) {
                while($feature = $result_features->fetch_assoc()) {
                    echo '<div class="col-lg-3 col-md-6 mb-4">
                        <div class="card text-center p-4 h-100">
                            <div class="feature-icon">
                                <i class="' . $feature['icon'] . '"></i>
                            </div>
                            <h5>' . $feature['judul'] . '</h5>
                            <p>' . $feature['deskripsi'] . '</p>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12 text-center">Tidak ada data keunggulan ditemukan</div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Kamp Pelatihan & Program Pembelajaran -->
<section class="section-padding">
    <div class="container">
        <div class="section-title">
            <h2>Kamp Pelatihan & Program Khusus</h2>
            <p>Program intensif untuk memaksimalkan pembelajaran dalam waktu singkat</p>
        </div>
        
        <div class="row">
            <?php
            // Query untuk mengambil bootcamp dari database
            $sql_bootcamps = "SELECT b.id, b.gambar, b.judul, b.deskripsi FROM bootcamp b ORDER BY b.id ASC LIMIT 2";
            $result_bootcamps = $conn->query($sql_bootcamps);
            
            if ($result_bootcamps->num_rows > 0) {
                while($bootcamp = $result_bootcamps->fetch_assoc()) {
                    // Query untuk fitur bootcamp
                    $sql_features = "SELECT fitur FROM bootcamp_fitur WHERE bootcamp_id = " . $bootcamp['id'] . " LIMIT 3";
                    $result_features = $conn->query($sql_features);
                    echo '<div class="col-lg-8 mb-4">
                        <div class="card h-100">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <img src="' . $bootcamp['gambar'] . '" class="img-fluid rounded-start h-100" alt="' . $bootcamp['judul'] . '" style="object-fit: cover;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $bootcamp['judul'] . '</h5>
                                        <p class="card-text">' . $bootcamp['deskripsi'] . '</p>
                                        <ul class="list-unstyled">';
                    
                    // Menampilkan fitur bootcamp
                    if ($result_features->num_rows > 0) {
                        while($feature = $result_features->fetch_assoc()) {
                            echo '<li><i class="fas fa-check-circle text-success me-2"></i>' . $feature['fitur'] . '</li>';
                        }
                    }
                    
                    echo '</ul>
                                        <a href="bootcamp-details.php?id=' . $bootcamp['id'] . '" class="btn btn-primary mt-3">Detail Program</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12 text-center">Tidak ada program bootcamp ditemukan</div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Pengembangan Profesional -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Pengembangan Profesional</h2>
            <p>Tingkatkan keterampilan dan percepat kemajuan karier Anda</p>
        </div>
        
        <div class="row">
            <?php
            // Query untuk mengambil program pengembangan profesional dari database
            $sql_prof_dev = "SELECT icon, judul, deskripsi, link, button_text FROM pengembangan_profesional ORDER BY id ASC LIMIT 3";
            $result_prof_dev = $conn->query($sql_prof_dev);
            
            if ($result_prof_dev->num_rows > 0) {
                while($item = $result_prof_dev->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="feature-icon">
                                    <i class="' . $item['icon'] . '"></i>
                                </div>
                                <h5 class="card-title">' . $item['judul'] . '</h5>
                                <p class="card-text">' . $item['deskripsi'] . '</p>
                                <a href="' . $item['link'] . '" class="btn btn-outline-primary mt-3">' . $item['button_text'] . '</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12 text-center">Tidak ada program pengembangan profesional ditemukan</div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Mitra Merek Teratas -->
<section class="section-padding">
    <div class="container">
        <div class="section-title">
            <h2>Dipercaya oleh Perusahaan Terkemuka</h2>
            <p>Bermitra dengan berbagai perusahaan ternama untuk pengembangan bakat</p>
        </div>
        
        <div class="row align-items-center justify-content-center">
            <?php
            // Query untuk mengambil partner dari database
            $sql_partners = "SELECT gambar, nama FROM partner ORDER BY id ASC LIMIT 6";
            $result_partners = $conn->query($sql_partners);
            
            if ($result_partners->num_rows > 0) {
                while($partner = $result_partners->fetch_assoc()) {
                    echo '<div class="col-md-2 col-6 mb-4 text-center">
                        <img src="' . $partner['gambar'] . '" alt="' . $partner['nama'] . '" class="partner-logo">
                    </div>';
                }
            } else {
                echo '<div class="col-12 text-center">Tidak ada partner ditemukan</div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Testimoni -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Testimoni dari Alumni Kami</h2>
            <p>Apa kata mereka yang telah menyelesaikan program pembelajaran kami</p>
        </div>
        
        <div class="row">
            <?php
            // Query untuk mengambil testimoni dari database
            $sql_testimonials = "SELECT quote, avatar, nama, posisi FROM testimoni ORDER BY id ASC LIMIT 3";
            $result_testimonials = $conn->query($sql_testimonials);
            
            if ($result_testimonials->num_rows > 0) {
                while($testimonial = $result_testimonials->fetch_assoc()) {
                    echo '<div class="col-lg-4 col-md-6 mb-4">
                        <div class="card testimonial-card h-100">
                            <div class="mb-3">
                                <i class="fas fa-quote-left fa-2x text-primary opacity-50"></i>
                            </div>
                            <p class="mb-4">' . $testimonial['quote'] . '</p>
                            <div class="d-flex align-items-center">
                                <img src="' . $testimonial['avatar'] . '" alt="Avatar" class="rounded-circle" width="60">
                                <div class="ms-3">
                                    <h6 class="mb-0">' . $testimonial['nama'] . '</h6>
                                    <small class="text-muted">' . $testimonial['posisi'] . '</small>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12 text-center">Tidak ada testimoni ditemukan</div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Luncurkan Perjalanan Karier -->
<section class="section-padding" style="background: linear-gradient(rgba(3, 3, 176, 0.47), rgb(15, 167, 232)), url('path-to-cta-bg.jpg'); background-size: cover; background-position: center; color: white;">
    <div class="container text-center">
        <h2 class="display-5 mb-4">Siap Meluncurkan Perjalanan Karier Baru Anda?</h2>
        <p class="lead mb-5">Bergabunglah dengan ribuan profesional yang telah mengubah hidup mereka melalui pembelajaran yang transformatif</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="courses.php" class="btn btn-light btn-lg">Jelajahi Kursus</a>
            <a href="register.php" class="btn btn-outline-light btn-lg">Mulai Sekarang</a>
        </div>
    </div>
</section>

<!-- Langganan Buletin -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="newsletter-box text-center">
                    <h3 class="mb-4">Tetap Terinformasi</h3>
                    <p class="mb-4">Dapatkan pembaruan tentang kursus baru, acara eksklusif, dan tips pengembangan karier langsung ke kotak masuk Anda</p>
                    <form class="row g-3 justify-content-center" action="subscribe.php" method="POST">
                        <div class="col-md-8">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Alamat Email Anda" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100">Berlangganan</button>
                        </div>
                    </form>
                    <p class="mt-3 small text-muted">Dengan berlangganan, Anda menyetujui kebijakan privasi kami</p>
                </div>
            </div>
        </div>
    </div>
</section>

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
                
                if ($result_site_info->num_rows > 0) {
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
                    
                    if ($result_quick_links->num_rows > 0) {
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
                    $sql_category_links = "SELECT nama_kategori FROM kategori_kursus ORDER BY jumlah_kursus DESC LIMIT 6";
                    $result_category_links = $conn->query($sql_category_links);
                    
                    if ($result_category_links->num_rows > 0) {
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
<!-- Optional: jQuery jika diperlukan -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    // Script untuk animasi scroll
    document.addEventListener('DOMContentLoaded', function() {
        const scrollElements = document.querySelectorAll('.card, .section-title');
        
        const elementInView = (el, scrollOffset = 0) => {
            const elementTop = el.getBoundingClientRect().top;
            return (
                elementTop <= (window.innerHeight || document.documentElement.clientHeight) * 0.8
            );
        };
        
        const displayScrollElement = (element) => {
            element.classList.add('animate__animated', 'animate__fadeInUp');
        };
        
        const hideScrollElement = (element) => {
            element.classList.remove('animate__animated', 'animate__fadeInUp');
        };
        
        const handleScrollAnimation = () => {
            scrollElements.forEach((el) => {
                if (elementInView(el, 100)) {
                    displayScrollElement(el);
                } else {
                    hideScrollElement(el);
                }
            });
        };
        
        window.addEventListener('scroll', () => {
            handleScrollAnimation();
        });
        
        // Trigger once on load
        handleScrollAnimation();
    });
    
    // Script untuk testimonial carousel jika diperlukan
    $(document).ready(function(){
        // Inisialisasi carousel atau komponen lain jika diperlukan
    });
    
    // Form validation
    (() => {
        'use strict'
        
        // Fetch all forms we want to apply validation styles to
        const forms = document.querySelectorAll('.needs-validation')
        
        // Loop and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

</body>
</html>