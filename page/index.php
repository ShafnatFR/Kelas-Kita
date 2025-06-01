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


// Remove manual connection creation since db.php already creates $conn
// Ambil ID kursus dari parameter URL
$course_id = isset($_GET['id']) ? $_GET['id'] : '';

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
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    
<?php include("../Views/navbarbootstrap.php"); ?>

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

<section class="section-padding bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Jelajahi Kursus Berdasarkan Kategori</h2>
            <p>Temukan bidang minat Anda dan mulai perjalanan pembelajaran Anda hari ini</p>
        </div>
        
        <div class="row">
            <?php
            // Query untuk mengambil kategori kursus dari database
$sql_Kategori_links = "SELECT id_kategori AS id, nama_kategori, icon, jumlah_kursus FROM tb_kategori ORDER BY nama_kategori ASC LIMIT 4";
$result_Kategori_links = $conn->query($sql_Kategori_links);
             
            if ($result_Kategori_links && $result_Kategori_links->num_rows > 0) {
                while ($category = $result_Kategori_links->fetch_assoc()) {
                    // Gunakan ikon dari database
                    $icon = '<i class="' . htmlspecialchars($category['icon']) . '"></i>';
                    
                    echo '<div class="col-md-3 col-6 mb-4">
                        <div class="card text-center p-4">
                            <div class="card-icon mb-3">' . $icon . '</div>
                            <h5>' . htmlspecialchars($category['nama_kategori']) . '</h5>
                            <p class="text-muted">' . $category['jumlah_kursus'] . '+ Kursus</p>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12 text-center">Tidak ada kategori ditemukan</div>';
            }
            ?>
        </div>
        <div class="text-center mt-4">
            <a href="categories.php" class="btn btn-outline-primary">Lihat Semua Kategori</a>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="section-title text-center">
            <h2>Berbagai <span class="text-success">Kelas</span> menarik ada di sini</h2>
        </div>
        <div class="row">
            <?php
            $base_url = "";

            // Updated SQL query to fetch additional details for the course cards
$sql_featured_courses = "
    SELECT 
        k.id_kelas AS id,
        k.profil_kelas AS image,
        c.nama_kategori AS category,
        k.nama_kelas AS title,
        CONCAT(u.first_name, ' ', u.last_name) AS instructor,
        k.jumlah_peserta AS views,
        (SELECT COUNT(*) FROM tb_ulasan WHERE kursus_id = k.id_kelas) AS comments,
        (SELECT AVG(rating) FROM tb_ulasan WHERE kursus_id = k.id_kelas) AS avg_rating,
        k.harga AS price
    FROM tb_kelas k
    LEFT JOIN tb_kategori c ON k.kategori = c.id_kategori
    LEFT JOIN tb_mentor m ON k.id_mentor = m.id_mentor
    LEFT JOIN tb_user u ON m.id_user = u.id_user
    WHERE k.status_publikasi = 'approved'
    ORDER BY k.tanggal_rilis DESC
    LIMIT 8
";
            $result_featured = $conn->query($sql_featured_courses);
            if (!$result_featured) {
                echo '<div class="col-12 text-center">Maaf, tidak dapat memuat kursus unggulan saat ini.</div>';
            } else {
                if ($result_featured->num_rows > 0) {
                    while ($course = $result_featured->fetch_assoc()) {
                        $detail_url = "Coursedetail.php?id=" . intval($course['id']);
                        $avg_rating = round(floatval($course['avg_rating']), 1);
                        $comments = intval($course['comments']);
                        $views = intval($course['views']);
                        $duration = htmlspecialchars($course['duration'] ?? '1 Jam');
                        $category = htmlspecialchars($course['category']);
                        $instructor = htmlspecialchars($course['instructor']);
                        $title = htmlspecialchars($course['title']);
                        $image = htmlspecialchars($course['image']);
$price = intval($course['price']);
$type = strtoupper($course['type'] ?? 'VIDEO');
?>
<div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="position-relative">
            <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo $title; ?>" style="height: 200px; object-fit: cover;">
            
            <!-- Duration Badge -->
            <div class="position-absolute top-0 end-0 m-2">
                <span class="badge bg-dark px-2 py-1" style="font-size: 0.75rem; border-radius: 6px;">
                    <?php echo $duration; ?>
                </span>
            </div>
            
            <!-- Category and Type Badges -->
            <div class="position-absolute top-0 start-0 m-2">
                <span class="badge bg-primary me-1 px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">
                    <?php echo strtoupper($category); ?>
                </span>
                <span class="badge bg-secondary px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">
                    <?php echo $type; ?>
                </span>
            </div>
        </div>
        
        <div class="card-body p-3 d-flex flex-column">
            <!-- Course Title -->
            <a href="<?php echo $detail_url; ?>" class="text-decoration-none text-dark">
                <h6 class="card-title fw-bold mb-2" style="font-size: 1rem; line-height: 1.4; min-height: 2.8rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                    <?php echo $title; ?>
                </h6>
            </a>
            
            <!-- Instructor -->
            <p class="mb-2 text-muted" style="font-size: 0.85rem;">
                <?php echo $instructor; ?>
            </p>
            
            <!-- Stats (Views, Comments, Rating) -->
            <div class="d-flex align-items-center mb-3" style="font-size: 0.8rem; color: #666;">
                <span class="me-3">
                    <i class="fas fa-eye me-1"></i><?php echo number_format($views); ?>
                </span>
                <span class="me-3">
                    <i class="fas fa-comment me-1"></i><?php echo $comments; ?>
                </span>
                <span class="d-flex align-items-center">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= floor($avg_rating)) {
                            echo '<i class="fas fa-star text-warning" style="font-size: 0.7rem;"></i>';
                        } elseif ($i - $avg_rating < 1) {
                            echo '<i class="fas fa-star-half-alt text-warning" style="font-size: 0.7rem;"></i>';
                        } else {
                            echo '<i class="far fa-star text-warning" style="font-size: 0.7rem;"></i>';
                        }
                    }
                    ?>
                </span>
            </div>
            
            <!-- Price Section -->
            <div class="mt-auto">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                    </div>
                    <div class="text-end">
<div class="fw-bold text-primary" style="font-size: 1.1rem;">
    Rp <?php echo number_format($price, 0, ',', '.'); ?>
</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo $detail_url; ?>" class="btn btn-outline-primary mt-3 w-100">Lihat Detail</a>
        </div>
    </div>
</div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12 text-center">Tidak ada kursus unggulan ditemukan</div>';
                }
            }
            ?>
        </div>
        
        <!-- View All Button -->
        <div class="text-center mt-5">
<a href="courses.php" class="btn btn-primary btn-lg px-4 py-2" style="border-radius: 8px; font-weight: 600;">
    Lihat Semua Kelas <i class="fas fa-arrow-right ms-2"></i>
</a>
        </div>
    </div>
</section>>

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
$sql_bootcamps = "SELECT b.id, b.gambar, b.judul, b.deskripsi FROM tb_bootcamp b ORDER BY b.id ASC LIMIT 2";
$result_bootcamps = $conn->query($sql_bootcamps);
            
            if ($result_bootcamps && $result_bootcamps->num_rows > 0) {
                while($bootcamp = $result_bootcamps->fetch_assoc()) {
                    // Query untuk fitur bootcamp
$sql_features = "SELECT fitur FROM tb_bootcamp_fitur WHERE bootcamp_id = " . $bootcamp['id'] . " LIMIT 3";
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
                    if ($result_features && $result_features->num_rows > 0) {
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
$sql_prof_dev = "SELECT icon, judul, deskripsi, link, button_text 
                 FROM tb_pengembangan_profesional 
                 ORDER BY id ASC 
                 LIMIT 3";
$result_prof_dev = $conn->query($sql_prof_dev);
            
            if ($result_prof_dev && $result_prof_dev->num_rows > 0) {
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
<!-- Section removed as per user request -->

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
$sql_testimonials = "SELECT quote, avatar, nama, posisi FROM tb_testimoni ORDER BY id ASC LIMIT 3";
$result_testimonials = $conn->query($sql_testimonials);
            
            if ($result_testimonials && $result_testimonials->num_rows > 0) {
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

<?php include ("../Views/footerbootsrap.php"); ?>

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
            element.classList.add('animate_animated', 'animate_fadeInUp');
        };
        
        const hideScrollElement = (element) => {
            element.classList.remove('animate_animated', 'animate_fadeInUp');
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