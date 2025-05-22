<?php

// Mulai session jika diperlukan
include "db.php";

$site_name = "db_KelasKita";

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
        <div class="section-title">
            <h2>Kursus Unggulan & Terpopuler</h2>
            <p>Kursus yang direkomendasikan dan paling banyak diambil oleh pelajar kami</p>
        </div>
        
        <div class="row">
            <?php
            $base_url = ""; 
            
$sql_featured_courses = "
    SELECT 
        k.id_kelas AS id, 
        k.profil_kelas AS image, 
        k.badge AS badge, 
        '' AS tag, 
        k.nama_kelas AS title,  -- Use 'nama_kelas' for course name
        0 AS participant_count, 
        0 AS rating, 
        k.harga AS price,
        k.description AS description
    FROM tb_kelas k
    ORDER BY k.id_kelas DESC 
    LIMIT 3
";
            // Execute the query and store the result in $result_featured
            $result_featured = $conn->query($sql_featured_courses);

            // Check for query errors
            if (!$result_featured) {
                echo "Query error: " . $conn->error;
                // Fallback to show some default content
                echo '<div class="col-12 text-center">Maaf, tidak dapat memuat kursus unggulan saat ini.</div>';
            } else {
                // Check if query returned any results
                if ($result_featured->num_rows > 0) {
                    while ($course = $result_featured->fetch_assoc()) {
                        // Create the detail URL
$detail_url = "Coursedetail.php?id=" . intval($course['id']);
                        
                        // Badge class determination
                        $badge_class = "bg-primary";
                        if ($course['badge'] == "Hot") {
                            $badge_class = "bg-warning text-dark";
                        } elseif ($course['badge'] == "New") {
                            $badge_class = "bg-success";
                        }
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="<?php echo htmlspecialchars($course['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>">
                                <div class="p-3">
                                    <?php if (!empty($course['badge'])) : ?>
                                    <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($course['badge']); ?></span>
                                    <?php endif; ?>
                                    <h5 class="mt-2"><?php echo htmlspecialchars($course['title']); ?></h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-user-graduate"></i> <?php echo intval($course['participant_count']); ?>+ peserta</span>
                                        <span><i class="fas fa-star text-warning"></i> <?php echo floatval($course['rating']); ?></span>
                                    </div>
                                    <p class="small mb-4"><?php echo htmlspecialchars(substr($course['description'], 0, 120)); ?>...</p>
                                    
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold text-primary mb-0"><?php 
                                        // Periksa apakah harga sudah dalam format 'Rp X.XXX.XXX'
                                        if (strpos($course['price'], 'Rp') !== false) {
                                            echo $course['price']; // Tampilkan apa adanya jika sudah berformat
                                        } else {
                                            // Jika belum berformat, konversi ke format yang diinginkan
                                            echo 'Rp ' . number_format((float)$course['price'], 0, ',', '.');
                                        }
                                        ?></h5>
                                        <a href="<?php echo $detail_url; ?>" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                                    </div>
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
        
        <div class="text-center mt-4">
            <a href="courses.php" class="btn btn-primary">Lihat Semua Kursus</a>
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
<section class="section-padding">
    <div class="container">
        <div class="section-title">
            <h2>Dipercaya oleh Perusahaan Terkemuka</h2>
            <p>Bermitra dengan berbagai perusahaan ternama untuk pengembangan bakat</p>
        </div>
        
        <div class="row align-items-center justify-content-center">
            <?php
            // Query untuk mengambil partner dari database
$sql_partners = "SELECT gambar, nama FROM tb_partner ORDER BY id ASC LIMIT 6";
$result_partners = $conn->query($sql_partners);
            
            if ($result_partners && $result_partners->num_rows > 0) {
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

<?php include_once("../Views/footerbootsrap.php"); ?>

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