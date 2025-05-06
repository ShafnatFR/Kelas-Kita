<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="custom.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
        <title>Profil Siswa</title>
    </head>
    <body class="d-flex flex-column min-vh-100">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        
        <!-- Header -->
        <header>
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="Halamanutamalogin.html">
                        <img src="logo.png" alt="KelasKita Logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item"><a class="nav-link text-white" href="Halamanutamalogin.html">Beranda</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="kelasKita[kosong].html">Kelasku</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="aboutus1.html">Tentang Kami</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="contackUs.html">Hubungi Kami</a></li>
                        </ul>
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="color: white;" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                                    </svg>
                                    <span style="color: white;" class="ms-2">Michael</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                    <li><a class="dropdown-item" href="setting-profil.html">Pengaturan Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="HalamanSignIn.html">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
       
        <main class="flex-grow-1 container my-4">
            <!-- Profil Siswa -->
            <div class="container my-5">
                <div class="card">
                <div class="row g-0">
                    <!-- Bagian Foto Profil -->
                    <div class="col-12 col-md-3 d-flex flex-column align-items-center pt-3">
                        <!-- Gambar di tengah -->
                        <div class="text-center">
                            <img src="fotoaliq.jpg" class="img-fluid rounded-circle w-75 mb-2" alt="Profile Picture">
                            <p class="fw-bold mb-3">Michael</p>
                        </div>
                        <!-- Tombol Rata Kanan Kiri -->
                        <div class="d-grid gap-2 w-75 mb-4">
                            <a href="setting-profil.html" class="btn btn-outline-danger">Profil</a>
                            <a href="setting-preferensi.html" class="btn btn-outline-danger">Preferensi</a>
                            <a href="setting-notifikasi.html" class="btn btn-outline-danger">Notifikasi</a>
                            <a href="setting-hubungkanAkun.html" class="btn btn-danger active">Hubungkan Akun</a>
                            <a href="setting-keluar.html" class="btn btn-outline-danger">Keluar</a>
                            <a href="setting-tutupAkun.html" class="btn btn-outline-danger">Tutup Akun</a>
                        </div>
                    </div>
                    <!-- Bagian Informasi Profil -->
                    <div class="col-12 col-md-9 p-3">
                        <div class="card-body">
                            <div class="card-title text-center">
                                <h4 class="mb-4"><strong>Profil Siswa</strong></h4>
                            </div>
                            <form method="POST" action="update-profil.php" enctype="multipart/form-data">
                                <div class="mb-0">
                                    <label for="emil" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email"
                                    value="<?= htmlspecialchars($user['email'] ?? '')?>" placeholder="Email">
                                </div>
                                <div class="mb-0">
                                    <label for="namaDepan" class="form-label">Instagram</label>
                                    <input type="text" class="form-control" id="namaDepan" placeholder="">
                                </div>
                                <div class="mb-0">
                                    <label for="namaDepan" class="form-label">Twitter</label>
                                    <input type="text" class="form-control" id="namaDepan" placeholder="">
                                </div>
                                <div class="mb-0">
                                    <label for="namaDepan" class="form-label">Linkedln</label>
                                    <input type="text" class="form-control" id="namaDepan" placeholder="">
                                </div>
                                <div class="mb-0">
                                    <label for="namaDepan" class="form-label">Github</label>
                                    <input type="text" class="form-control" id="namaDepan" placeholder="">
                                </div>
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-outline-danger">Ubah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
        
        <!-- Footer -->
        <footer>
            <div class="logo-footer1">
                <a href ="mainlogin.html">
                <img src="kelass_kita-removebg-preview.png" alt="logo" class="img-logo1">
                </a>
                <span class="logo-footer2">KelasKita</span>
            </div>
    
            <div class="isi-footer1">
                <div class="kolom1">
                    <p>"Belajar kapan saja, di mana saja. KelasKita hadir untuk mendukung perjalanan belajar Anda menuju masa depan yang lebih cerah."</p>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="kolom2">
                    <a href="aboutus1.html">Tentang Kami</a>
                    <a href="contackUs.html">Kontak Kami</a>
                    <a href="SyaratnKetentuan.html">Syarat & Ketentuan</a>
                    <a href="faq.html">FAQ</a>
                </div>
                <div class="kolom3">
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Telekomunikasi No. 1, Terusan Buahbatu, Bojongsoang, Jawa Barat, Bandung.</p>
                    <p><i class="fas fa-phone-alt"></i> 0812-3456-7890</p>
                    <p><i class="fas fa-envelope"></i> kelaskita@gmail.com</p>
                </div>
            </div>
    
            <div class="hr-footer">
                <hr />
            </div>
            <div class="copyright">© 2025 KelasKita. All Rights Reserved.</div>
        </footer>
    
    </body>
</html>