<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KelasKita - Platform Pembelajaran Online</title>
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
<?php include "../Views/navbarbootstrap.php" ?>
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
            </div>
        </nav>
    <!-- Hero Section dengan Gambar Unggulan & CTA -->
    <section class="hero-section" style="background: linear-gradient(rgba(3, 3, 176, 0.47), rgb(15, 167, 232)), url('path-to-hero-image.jpg'); background-size: cover; background-position: center; color: white; padding: 100px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Tingkatkan Keterampilan Anda, Raih Masa Depan Cemerlang</h1>
                    <p class="lead mb-4">Platform pembelajaran online terkemuka yang menawarkan kursus berkualitas untuk membantu Anda mengembangkan keterampilan dan memajukan karier.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="courses.html" class="btn btn-primary btn-lg">Pelajari Sekarang</a>
                        <a href="register.html" class="btn btn-outline-light btn-lg">Berlangganan</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Gambar tambahan atau konten hero di sisi kanan jika diperlukan -->
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
                <div class="col-md-3 col-6 mb-4">
                    <div class="card text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h5>Pengembangan Web</h5>
                        <p class="small">50+ Kursus</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5>Pengembangan Aplikasi</h5>
                        <p class="small">35+ Kursus</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5>Bisnis & Marketing</h5>
                        <p class="small">30+ Kursus</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h5>Desain & Kreativitas</h5>
                        <p class="small">25+ Kursus</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="categories.html" class="btn btn-outline-primary">Lihat Semua Kategori</a>
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
                <!-- Kursus 1 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="../assets/images/b96c5f8a5f486a923d18305001c6a80a.jpg" class="card-img-top" alt="Kursus JavaScript">
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">Terpopuler</span>
                            <h5 class="card-title">JavaScript Modern dari Dasar hingga Lanjutan</h5>
                            <div class="d-flex justify-content-between mb-3">
                                <span><i class="fas fa-user-graduate"></i> 15,200+ peserta</span>
                                <span><i class="fas fa-star text-warning"></i> 4.8</span>
                            </div>
                            <p class="card-text">Kuasai JavaScript modern dengan pengetahuan mendalam dan praktek langsung membuat proyek nyata.</p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary">Rp599.000</span>
                                <a href="../course-details.php" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kursus 2 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="../assets/images/a4df645483f9877ac9e95d189b662d53.jpg" class="card-img-top" alt="Kursus Data Science">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">Baru</span>
                            <h5 class="card-title">Data Science & Machine Learning dengan Python</h5>
                            <div class="d-flex justify-content-between mb-3">
                                <span><i class="fas fa-user-graduate"></i> 8,750+ peserta</span>
                                <span><i class="fas fa-star text-warning"></i> 4.9</span>
                            </div>
                            <p class="card-text">Pelajari konsep Data Science dan Machine Learning dengan implementasi praktis menggunakan Python.</p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary">Rp799.000</span>
                                <a href="../course-details.php" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kursus 3 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="../assets/images/460ef3c3fa05eae192e32d056fc5339d.jpg" class="card-img-top" alt="Kursus UI/UX">
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">Terpopuler</span>
                            <h5 class="card-title">UI/UX Design: Dari Pemula hingga Profesional</h5>
                            <div class="d-flex justify-content-between mb-3">
                                <span><i class="fas fa-user-graduate"></i> 12,300+ peserta</span>
                                <span><i class="fas fa-star text-warning"></i> 4.7</span>
                            </div>
                            <p class="card-text">Kuasai desain antarmuka dan pengalaman pengguna dengan prinsip-prinsip modern dan alat industri.</p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary">Rp699.000</span>
                                <a href="../course-details.php" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="../course-details.php" class="btn btn-primary">Lihat Semua Kursus</a>
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
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h5>Program Pembelajaran Intensif</h5>
                        <p>Kurikulum terstruktur yang dirancang oleh ahli industri untuk mempercepat proses belajar Anda.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h5>Pengembangan Pola Pikir</h5>
                        <p>Tidak hanya keterampilan teknis, tapi juga pola pikir yang tepat untuk sukses di era digital.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-industry"></i>
                        </div>
                        <h5>Relevansi Keterampilan</h5>
                        <p>Fokus pada keterampilan yang aktual dan dibutuhkan industri untuk meningkatkan peluang karier.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center p-4 h-100">
                        <div class="feature-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h5>Dukungan Sepanjang Perjalanan</h5>
                        <p>Mentor berpengalaman yang siap membantu Anda dengan masalah dan pertanyaan selama belajar.</p>
                    </div>
                </div>
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
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <img src="../assets/images/47dd78487f5e0994dc32fe6a7f48f609.jpg" class="img-fluid rounded-start h-100" alt="Web Development Bootcamp" style="object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Full-Stack Web Development Bootcamp</h5>
                                    <p class="card-text">Program intensif 12 minggu untuk menjadi developer web full-stack dan siap berkarir di industri teknologi.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Praktek intensif 40+ jam per minggu</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Bimbingan mentor senior</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Proyek portofolio nyata</li>
                                    </ul>
                                    <a href="bootcamp-details.html" class="btn btn-primary mt-3">Detail Program</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <img src="../assets/images/2a837c43bc66c31155de0bd6f1029076.jpg" class="img-fluid rounded-start h-100" alt="Data Science Bootcamp" style="object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Data Science & Analytics Bootcamp</h5>
                                    <p class="card-text">Program intensif 10 minggu untuk menguasai analisis data dan machine learning dengan proyek nyata.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Studi kasus dari perusahaan aktual</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Akses ke dataset premium</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Jaringan dengan profesional industri</li>
                                    </ul>
                                    <a href="bootcamp-details.html" class="btn btn-primary mt-3">Detail Program</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h5 class="card-title">Sertifikasi Profesional</h5>
                            <p class="card-text">Dapatkan sertifikasi yang diakui industri untuk memvalidasi keahlian dan meningkatkan daya saing CV Anda.</p>
                            <a href="professional-certifications.html" class="btn btn-outline-primary mt-3">Lihat Sertifikasi</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h5 class="card-title">Keterampilan Kepemimpinan</h5>
                            <p class="card-text">Kembangkan soft skill dan kemampuan kepemimpinan untuk mempersiapkan diri pada posisi manajerial.</p>
                            <a href="leadership-courses.html" class="btn btn-outline-primary mt-3">Jelajahi Kursus</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h5 class="card-title">Transisi Karier</h5>
                            <p class="card-text">Program khusus untuk mereka yang ingin beralih ke bidang teknologi dari latar belakang yang berbeda.</p>
                            <a href="career-transition.html" class="btn btn-outline-primary mt-3">Mulai Transisi</a>
                        </div>
                    </div>
                </div>
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
                <div class="col-md-2 col-6 mb-4 text-center">
                    <img src="path-to-partner1.png" alt="Partner 1" class="partner-logo">
                </div>
                <div class="col-md-2 col-6 mb-4 text-center">
                    <img src="path-to-partner2.png" alt="Partner 2" class="partner-logo">
                </div>
                <div class="col-md-2 col-6 mb-4 text-center">
                    <img src="path-to-partner3.png" alt="Partner 3" class="partner-logo">
                </div>
                <div class="col-md-2 col-6 mb-4 text-center">
                    <img src="path-to-partner4.png" alt="Partner 4" class="partner-logo">
                </div>
                <div class="col-md-2 col-6 mb-4 text-center">
                    <img src="path-to-partner5.png" alt="Partner 5" class="partner-logo">
                </div>
                <div class="col-md-2 col-6 mb-4 text-center">
                    <img src="path-to-partner6.png" alt="Partner 6" class="partner-logo">
                </div>
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
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card testimonial-card h-100">
                        <div class="mb-3">
                            <i class="fas fa-quote-left fa-2x text-primary opacity-50"></i>
                        </div>
                        <p class="mb-4">Bootcamp Full-Stack Web Development benar-benar mengubah hidup saya. Dalam 12 minggu, saya berhasil menguasai keterampilan yang dibutuhkan untuk mendapatkan pekerjaan pertama saya sebagai developer.</p>
                        <div class="d-flex align-items-center">
                            <img src="path-to-avatar1.jpg" alt="Avatar" class="rounded-circle" width="60">
                            <div class="ms-3">
                                <h6 class="mb-0">Dian Kusuma</h6>
                                <small class="text-muted">Full-Stack Developer di TechCorp</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card testimonial-card h-100">
                        <div class="mb-3">
                            <i class="fas fa-quote-left fa-2x text-primary opacity-50"></i>
                        </div>
                        <p class="mb-4">Sebagai profesional yang sibuk, kursus mandiri dari KelasKita sangat cocok untuk saya. Materi berkualitas tinggi dan dukungan mentor membantu saya menguasai Data Science dengan tempo saya sendiri.</p>
                        <div class="d-flex align-items-center">
                            <img src="path-to-avatar2.jpg" alt="Avatar" class="rounded-circle" width="60">
                            <div class="ms-3">
                                <h6 class="mb-0">Fajar Ramadhan</h6>
                                <small class="text-muted">Data Analyst di FinGroup</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card testimonial-card h-100">
                        <div class="mb-3">
                            <i class="fas fa-quote-left fa-2x text-primary opacity-50"></i>
                        </div>
                        <p class="mb-4">Sertifikasi UI/UX Design membuka banyak peluang bagi saya. Kurikulum yang relevan dengan industri dan proyek portofolio membuat saya menonjol dalam proses rekrutmen.</p>
                        <div class="d-flex align-items-center">
                            <img src="path-to-avatar3.jpg" alt="Avatar" class="rounded-circle" width="60">
                            <div class="ms-3">
                                <h6 class="mb-0">Anita Wijaya</h6>
                                <small class="text-muted">UI/UX Designer di Creative Studio</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Luncurkan Perjalanan Karier -->
    <section class="section-padding" style="background: linear-gradient(rgba(3, 3, 176, 0.47), rgb(15, 167, 232)), url('path-to-cta-bg.jpg'); background-size: cover; background-position: center; color: white;">
        <div class="container text-center">
            <h2 class="display-5 mb-4">Siap Meluncurkan Perjalanan Karier Baru Anda?</h2>
            <p class="lead mb-5">Bergabunglah dengan ribuan profesional yang telah mengubah hidup mereka melalui pembelajaran yang transformatif</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="courses.html" class="btn btn-light btn-lg">Jelajahi Kursus</a>
                <a href="register.html" class="btn btn-outline-light btn-lg">Mulai Sekarang</a>
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
                        <form class="row g-3 justify-content-center">
                            <div class="col-md-8">
                                <input type="email" class="form-control form-control-lg" placeholder="Alamat Email Anda" required>
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
                <img src="../assets/images/ChatGPT Image 13 Mei 2025, 12.52.09.png" alt="KelasKita Logo" height="40" class="mb-4">
                <p>Platform pembelajaran online terkemuka yang menyediakan kursus berkualitas tinggi untuk membantu Anda mengembangkan keterampilan dan memajukan karier.</p>
                <div class="mt-3">
                    <p><i class="fas fa-envelope me-2"></i> info@KelasKita.co.id</p>
                    <p><i class="fas fa-phone me-2"></i> +62 21 12345678</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Jl. Telekomunikasi No. 1, Bandung Terusan Buahbatu - Bojongsoang, Sukapura, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40257</p>
                </div>
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
                    <li class="mb-2"><a href="index.html" class="text-white text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="courses.html" class="text-white text-decoration-none">Kursus</a></li>
                    <li class="mb-2"><a href="bootcamp.html" class="text-white text-decoration-none">Bootcamp</a></li>
                    <li class="mb-2"><a href="about.html" class="text-white text-decoration-none">Tentang Kami</a></li>
                    <li class="mb-2"><a href="contact.html" class="text-white text-decoration-none">Kontak</a></li>
                    <li class="mb-2"><a href="faq.html" class="text-white text-decoration-none">FAQ</a></li>
                </ul>
            </div>

            <!-- Kategori -->
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h5 class="mb-4">Kategori</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Pengembangan Web</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Pengembangan Mobile</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Data Science</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">UI/UX Design</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Digital Marketing</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Business & Leadership</a></li>
                </ul>
            </div>

            <!-- Dukungan -->
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h5 class="mb-4">Dukungan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Pusat Bantuan</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Kebijakan Privasi</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Syarat & Ketentuan</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Kebijakan Refund</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Laporan Bug</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Affiliate Program</a></li>
                </ul>
            </div>

            <!-- Download Aplikasi -->
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h5 class="mb-4">Aplikasi Mobile</h5>
                <p>Belajar dari mana saja dengan aplikasi mobile kami</p>
                <div class="mb-3">
                    <a href="#" class="d-inline-block mb-2">
                        <img src="../assets/images/6acf4c84f55a52f6ccbdaa71ad2701ee.jpg" alt="App Store" height="40" class="img-fluid">
                    </a>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <!-- Copyright & Pembayaran -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0">&copy; 2025 KelasKita. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>