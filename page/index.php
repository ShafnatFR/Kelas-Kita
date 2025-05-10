<?php
include "db.php";  // Include the database connection file

// Start the session and initialize cart if needed
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch courses from the database with proper error handling
$featuredCourses = [];
$popularCourses = [];

try {
    $query = "SELECT * FROM courses WHERE status = 'active'";  // Only fetch active courses
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($conn));
    }
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Separate featured and popular courses by tag
        if ($row['tag'] == 'FEATURED') {
            $featuredCourses[] = $row;
        } elseif ($row['tag'] == 'POPULAR') {
            $popularCourses[] = $row;
        }
    }
} catch (Exception $e) {
    // Log error but don't show to users
    error_log("Error: " . $e->getMessage());
    // Could set an error message variable here to display to admins if needed
}

// Categories data with proper icon references
$categories = [
    ['name' => 'All', 'icon' => 'th-large', 'slug' => 'all'],
    ['name' => 'Development', 'icon' => 'code', 'slug' => 'development'],
    ['name' => 'Business', 'icon' => 'briefcase', 'slug' => 'business'],
    ['name' => 'Marketing', 'icon' => 'bullhorn', 'slug' => 'marketing'],
    ['name' => 'Design', 'icon' => 'paint-brush', 'slug' => 'design'],
    ['name' => 'Health', 'icon' => 'heartbeat', 'slug' => 'health'],
    ['name' => 'Finance', 'icon' => 'chart-line', 'slug' => 'finance'],
    ['name' => 'IT & Software', 'icon' => 'laptop-code', 'slug' => 'it-software']
];

// Function to sanitize output
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Add to cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    if (isset($_POST['kelas_id'])) {
        $kelasId = (int)$_POST['kelas_id'];
        
        // Check if course exists in database
        $checkQuery = "SELECT id, title, price FROM courses WHERE id = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "i", $kelasId);
        mysqli_stmt_execute($stmt);
        $checkResult = mysqli_stmt_get_result($stmt);
        
        if ($course = mysqli_fetch_assoc($checkResult)) {
            // Check if the course is already in the cart
            $courseExists = false;
            foreach ($_SESSION['cart'] as $item) {
                if ($item['id'] == $kelasId) {
                    $courseExists = true;
                    break;
                }
            }
            
            if (!$courseExists) {
                $_SESSION['cart'][] = [
                    'id' => $course['id'],
                    'title' => $course['title'],
                    'price' => $course['price']
                ];
                $_SESSION['flash_message'] = "Kursus berhasil ditambahkan ke keranjang!";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "Kursus sudah ada di keranjang!";
                $_SESSION['flash_type'] = "warning";
            }
        }
        
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kelas Kita - Platform belajar online untuk meningkatkan keterampilan Anda dengan kursus dari para ahli">
    <title>Kelas Kita - Belajar Online Untuk Meningkatkan Karier</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #3a5efc 0%, #5c87ff 100%);
            padding: 80px 0;
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border-radius: 50%;
            background-color: #f0f7ff;
            color: #3a5efc;
            transition: all 0.3s ease;
        }
        
        .card:hover .category-icon {
            background-color: #3a5efc;
            color: #ffffff;
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
        
        .rating {
            color: #FFC107;
        }
        
        .text-purple {
            color: #6f42c1;
        }
        
        .bg-purple {
            background-color: #6f42c1;
        }
        
        .section-heading {
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .section-heading:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: #3a5efc;
        }
        
        .flash-message {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navbar -->
    <?php include "../Views/navbarbootstrap.php"; ?>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="flash-message">
            <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['flash_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php 
        // Clear the flash message
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        ?>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-4">Cara Lebih Cepat Untuk Pertumbuhan Karier Anda</h1>
                    <p class="lead mb-4">Pelajari keterampilan yang Anda butuhkan untuk memajukan karier dengan kursus yang dipandu oleh para ahli kami.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="courses.php" class="btn btn-light btn-lg fw-bold text-primary">Memulai</a>
                        <a href="about.php" class="btn btn-outline-light btn-lg">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="../assets/images/hero-image.webp" alt="Siswa belajar online" class="img-fluid rounded-4 shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center section-heading">Kategori Kursus</h2>
            <div class="row justify-content-center g-4">
                <?php foreach ($categories as $category): ?>
                <div class="col-6 col-md-3 col-lg-3">
                    <a href="courses.php?category=<?= $category['slug'] ?>" class="text-decoration-none">
                        <div class="card h-100 text-center p-3 shadow-sm">
                            <div class="category-icon">
                                <i class="fas fa-<?= escape($category['icon']) ?> fa-lg"></i>
                            </div>
                            <h5 class="card-title"><?= escape($category['name']) ?></h5>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Featured Courses Section -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center section-heading">Featured Courses</h2>
        <div class="row g-4">
            <?php 
            // Check if there are featured courses to display
            if (!empty($featuredCourses)): 
                foreach ($featuredCourses as $course): 
            ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($course['image'])): ?>
                            <img src="<?= escape($course['image']) ?>" class="card-img-top" alt="<?= escape($course['title']) ?>">
                        <?php else: ?>
                            <img src="../assets/images/course-placeholder.jpg" class="card-img-top" alt="Course Image">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <?php if (!empty($course['tag'])): ?>
                                <span class="badge bg-primary mb-2"><?= escape($course['tag']) ?></span>
                            <?php endif; ?>
                            
                            <h5 class="card-title"><?= escape($course['title']) ?></h5>
                            <p class="card-text text-muted"><?= escape($course['instructor'] ?? 'Expert Instructor') ?></p>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-2 rating">
                                    <?php 
                                    $rating = !empty($course['rating']) ? (float)$course['rating'] : 0;
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars) >= 0.5;
                                    
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $fullStars) {
                                            echo '<i class="fas fa-star"></i>';
                                        } elseif ($i == $fullStars + 1 && $halfStar) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <?php if (!empty($course['rating']) && !empty($course['reviews_count'])): ?>
                                    <span class="fw-bold"><?= number_format($course['rating'], 1) ?></span>
                                    <span class="text-muted ms-1">(<?= number_format($course['reviews_count']) ?>)</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if (!empty($course['price'])): ?>
                                        <?php if (!empty($course['original_price']) && $course['original_price'] > $course['price']): ?>
                                            <span class="text-muted text-decoration-line-through me-2">Rp<?= number_format($course['original_price'], 0, ',', '.') ?></span>
                                        <?php endif; ?>
                                        <span class="fw-bold">Rp<?= number_format($course['price'], 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="fw-bold text-success">Free</span>
                                    <?php endif; ?>
                                </div>
                                
                                <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                                    <input type="hidden" name="action" value="add_to_cart">
                                    <input type="hidden" name="kelas_id" value="<?= $course['id'] ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <div class="col-12 text-center">
                    <p>No featured courses available at the moment. Please check back later!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
    <!-- Why Upskill Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="../assets/images/student_laptop.jpg" alt="Student using laptop" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bold mb-4">Why Upskill becomes the best training course & bootcamp</h2>
                    <p class="mb-4">We've designed our platform to provide the best learning experience with expert instructors, hands-on projects, and a supportive community.</p>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="fas fa-check text-primary"></i>
                        </div>
                        <span class="fw-medium">Skilled instructors</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="fas fa-check text-primary"></i>
                        </div>
                        <span class="fw-medium">Hands-on projects</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="fas fa-check text-primary"></i>
                        </div>
                        <span class="fw-medium">Premium resources</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="fas fa-check text-primary"></i>
                        </div>
                        <span class="fw-medium">Lifetime access</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Classes Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="mb-4">Popular classes</h2>
            <div class="row g-4">
                <!-- Project Management Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm rounded-3" style="background-color: #EBF5FF;">
                        <div class="card-body">
                            <span class="badge bg-primary bg-opacity-25 text-primary mb-2">CERTIFICATION</span>
                            <h5 class="card-title fw-bold">Project Management Professional</h5>
                            <p class="card-text text-muted mb-2">Robert Johnson</p>
                            <div class="d-flex align-items-center">
                                <div class="me-2 text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="fw-bold">4.9</span>
                                <span class="text-muted ms-1">(2,156)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Financial Analysis Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm rounded-3" style="background-color: #FFF8EB;">
                        <div class="card-body">
                            <span class="badge bg-warning bg-opacity-25 text-warning mb-2">PROFESSIONAL</span>
                            <h5 class="card-title fw-bold">Financial Analysis Masterclass</h5>
                            <p class="card-text text-muted mb-2">Linda Thompson</p>
                            <div class="d-flex align-items-center">
                                <div class="me-2 text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="fw-bold">4.8</span>
                                <span class="text-muted ms-1">(1,245)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Machine Learning Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm rounded-3" style="background-color: #EBFFF3;">
                        <div class="card-body">
                            <span class="badge bg-success bg-opacity-25 text-success mb-2">ADVANCED</span>
                            <h5 class="card-title fw-bold">Machine Learning A-Z</h5>
                            <p class="card-text text-muted mb-2">James Wilson</p>
                            <div class="d-flex align-items-center">
                                <div class="me-2 text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="fw-bold">4.9</span>
                                <span class="text-muted ms-1">(3,542)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Content Creation Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm rounded-3" style="background-color: #F8EBFF;">
                        <div class="card-body">
                            <span class="badge bg-purple bg-opacity-25 text-purple mb-2">CREATIVE</span>
                            <h5 class="card-title fw-bold">Content Creation Masterclass</h5>
                            <p class="card-text text-muted mb-2">Sophia Lee</p>
                            <div class="d-flex align-items-center">
                                <div class="me-2 text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="fw-bold">4.7</span>
                                <span class="text-muted ms-1">(1,832)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col">
                    <h2 class="fw-bold text-primary mb-1">253+</h2>
                    <p class="text-muted">Online Workshops</p>
                </div>
                <div class="col">
                    <h2 class="fw-bold text-primary mb-1">100K+</h2>
                    <p class="text-muted">Students</p>
                </div>
                <div class="col">
                    <h2 class="fw-bold text-primary mb-1">1.2K+</h2>
                    <p class="text-muted">Instructors</p>
                </div>
                <div class="col">
                    <h2 class="fw-bold text-primary mb-1">2.5K+</h2>
                    <p class="text-muted">Online Courses</p>
                </div>
                <div class="col">
                    <h2 class="fw-bold text-primary mb-1">50+</h2>
                    <p class="text-muted">Practical Tools</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Launch Career Section -->
    <section class="py-5 bg-warning">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">Launch Your Career Journey</h2>
                    <p class="mb-0">Through upskill</p>
                </div>
                <div class="col-md-4 text-end">
                    <img src="../assets/images/people_learning.jpg" alt="People learning" class="img-fluid rounded-3" style="max-height: 120px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include "../Views/footerbootsrap.php"; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- Custom JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto dismiss flash messages after 5 seconds
        setTimeout(function() {
            const flashMessage = document.querySelector('.flash-message .alert');
            if (flashMessage) {
                const bsAlert = new bootstrap.Alert(flashMessage);
                bsAlert.close();
            }
        }, 5000);
    });
    </script>
</body>

<?php
include_once('db.php');

$user = null;

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT fotoProfil FROM tbuser WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<body class="bg-light">
<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand text-primary fw-bold" href="#">KelasKita</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active fw-medium" href="#">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="#">Kursus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="#">Kategori</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="#">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="../page/contackUs.php">Kontak</a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="cart.php" class="nav-link text-secondary position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if(!empty($_SESSION['cart'])): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo count($_SESSION['cart']); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>

            <!-- Right section -->
            <div class="d-flex align-items-center gap-2">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="dropdown">
                        <a href="#" role="button" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
                            <img 
                                src="<?= (!empty($user['fotoProfil']) && file_exists('../upload/' . $user['fotoProfil'])) 
                                    ? '../upload/' . htmlspecialchars($user['fotoProfil']) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=0D8ABC&color=fff&rounded=true&size=64' ?>" 
                                alt="Profile" 
                                class="rounded-circle" width="32" height="32">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow" aria-labelledby="dropdownProfile" style="min-width: 250px;">
                            <li class="px-3 py-2 border-bottom d-flex align-items-center">
                                <img 
                                    src="<?= (!empty($user['fotoProfil']) && file_exists('../upload/' . $user['fotoProfil'])) 
                                        ? '../upload/' . htmlspecialchars($user['fotoProfil']) 
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=0D8ABC&color=fff&rounded=true&size=64' ?>" 
                                    alt="Profile" 
                                    class="rounded-circle me-2" width="32" height="32">
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($_SESSION['username']) ?></div>
                                    <small class="text-muted text-truncate d-block" style="max-width: 160px;"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></small>
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="setting-profil.php">KelasKu</a></li>
                            <li><a class="dropdown-item" href="changeRole.php">Instructor</a></li>
                            <li><a class="dropdown-item" href="keranjang.php">Keranjang</a></li>
                            <li><a class="dropdown-item" href="setting-profil.php">Pengaturan Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="HalamanSignIn.php" class="btn btn-outline-secondary d-none d-md-inline-block">Masuk</a>
                    <a href="HalamanSignUp.php" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Tambahkan link ke Bootstrap JS jika belum ada -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>