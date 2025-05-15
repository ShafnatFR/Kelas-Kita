<?php
include "db.php";  // Include the database connection file
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get course ID from URL
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validate the course ID
if ($course_id <= 0) {
    header("Location: index.php");
    exit;
}

// Fetch the course data from the database
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if the course data exists
if (mysqli_num_rows($result) > 0) {
    $course = mysqli_fetch_assoc($result);
} else {
    header("Location: index.php");
    exit;
}

// Initialize what_you_learn array with default values
$what_you_learn = [
    "Menguasai konsep dasar dari materi kursus ini",
    "Menerapkan praktik terbaik dalam pemecahan masalah",
    "Membangun proyek praktis dari konsep hingga implementasi",
    "Mengembangkan keterampilan yang dibutuhkan industri"
];

// Check if the course_content table exists
$tableExistsQuery = "SHOW TABLES LIKE 'course_content'";
$tableExistsResult = mysqli_query($conn, $tableExistsQuery);
$tableExists = mysqli_num_rows($tableExistsResult) > 0;

// Only fetch course content if the table exists
if ($tableExists) {
    // Fetch what you'll learn content
    $learnQuery = "SELECT * FROM course_content WHERE course_id = ? AND content_type = 'learn'";
    $learnStmt = mysqli_prepare($conn, $learnQuery);
    mysqli_stmt_bind_param($learnStmt, "i", $course_id);
    mysqli_stmt_execute($learnStmt);
    $learnResult = mysqli_stmt_get_result($learnStmt);

    // Store what you'll learn items if any exist
    if ($learnResult && mysqli_num_rows($learnResult) > 0) {
        // Clear the default values
        $what_you_learn = [];
        
        while ($item = mysqli_fetch_assoc($learnResult)) {
            $what_you_learn[] = $item['content'];
        }
    }
}

// Fetch reviews for the course
$reviewQuery = "SELECT * FROM reviews WHERE course_id = ? ORDER BY date DESC";
$reviewStmt = mysqli_prepare($conn, $reviewQuery);
mysqli_stmt_bind_param($reviewStmt, "i", $course_id);
mysqli_stmt_execute($reviewStmt);
$reviewsResult = mysqli_stmt_get_result($reviewStmt);

// Check if reviews exist
$reviews = [];
if ($reviewsResult && mysqli_num_rows($reviewsResult) > 0) {
    while ($review = mysqli_fetch_assoc($reviewsResult)) {
        $reviews[] = $review;
    }
}

// Initialize the average rating variable
$averageRating = 0;

// Calculate average rating
$totalRating = 0;
$reviewCount = count($reviews);

if ($reviewCount > 0) {
    foreach ($reviews as $review) {
        $totalRating += $review['rating'];
    }
    // Calculate the average rating
    $averageRating = round($totalRating / $reviewCount, 1);
}

// Rating distribution
$ratingDistribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
if ($reviewCount > 0) {
    foreach ($reviews as $review) {
        $ratingDistribution[$review['rating']]++;
    }
}

// Calculate percentages for each rating
$ratingPercentages = [];
foreach ($ratingDistribution as $rating => $count) {
    $ratingPercentages[$rating] = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
}

// Handle add to cart functionality
if (isset($_POST['add_to_cart'])) {
    // Add the course to the cart session
    $courseInCart = false;
    
    // Check if course is already in cart
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] == $course_id) {
            $courseInCart = true;
            break;
        }
    }
    
    if (!$courseInCart) {
        $_SESSION['cart'][] = [
            'id' => $course_id,
            'title' => $course['title'],
            'price' => $course['price'],
            'image' => $course['image']
        ];
        
        // Redirect to prevent form resubmission
        header("Location: course_detail.php?id=$course_id&added=1");
        exit;
    }
}

// Get category information
$categoryQuery = "SELECT c.* FROM categories c 
                  JOIN course_categories cc ON c.id = cc.category_id 
                  WHERE cc.course_id = ?";
$categoryStmt = mysqli_prepare($conn, $categoryQuery);
mysqli_stmt_bind_param($categoryStmt, "i", $course_id);
mysqli_stmt_execute($categoryStmt);
$categoryResult = mysqli_stmt_get_result($categoryStmt);

$courseCategory = "Uncategorized";
if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
    $category = mysqli_fetch_assoc($categoryResult);
    $courseCategory = $category['name'];
}

// Get instructor information
$instructorQuery = "SELECT * FROM instructors WHERE id = ?";
$instructorStmt = mysqli_prepare($conn, $instructorQuery);
mysqli_stmt_bind_param($instructorStmt, "i", $course['instructor_id']);
mysqli_stmt_execute($instructorStmt);
$instructorResult = mysqli_stmt_get_result($instructorStmt);

$instructor = [];
if ($instructorResult && mysqli_num_rows($instructorResult) > 0) {
    $instructor = mysqli_fetch_assoc($instructorResult);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> | Kelas Kita - Online Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
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
        .badge-custom {
            font-size: 0.75rem;
            padding: 0.25em 0.5em;
            border-radius: 0.25rem;
        }
        .rating-stars {
            color: #FFD700;
            font-size: 1.25rem;
        }
        .price {
            font-weight: 700;
            color: #4361ee;
            font-size: 1.5rem;
        }
        .btn-primary-custom {
            background-color: #4361ee;
            border-color: #4361ee;
        }
        .btn-primary-custom:hover {
            background-color: #3f37c9;
            border-color: #3f37c9;
        }
        .course-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
        .related-course-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include "../Views/navbarbootstrap.php"; ?>

    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="courses.php?category=<?php echo urlencode($courseCategory); ?>" class="text-decoration-none"><?php echo htmlspecialchars($courseCategory); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($course['title']); ?></li>
            </ol>
        </nav>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Course Details Card -->
                <div class="card p-4">
                    <div class="position-relative">
                        <?php if (!empty($course['badge'])): ?>
                        <div class="course-tag">
                            <span class="badge bg-danger badge-custom"><?php echo htmlspecialchars($course['badge']); ?></span>
                        </div>
                        <?php endif; ?>
                        <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="card-img-top rounded" style="object-fit: cover; height: 300px;">
                    </div>
                    <div class="card-body px-0 pb-0">
                        <h3 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <div class="mb-2">
                            <span class="badge bg-primary badge-custom"><?php echo htmlspecialchars($courseCategory); ?></span>
                        </div>
                        <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="rating-stars">
                                <?php 
                                for ($i = 1; $i <= 5; $i++) {
                                    echo ($i <= $averageRating) ? '★' : '☆';
                                }
                                ?>
                                <span class="text-dark ms-1"><?php echo $averageRating; ?>/5</span>
                            </div>
                            <div>
                                <span class="text-muted">(<?php echo $reviewCount; ?> ulasan)</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Instruktur:</strong> <?php echo !empty($instructor) ? htmlspecialchars($instructor['name']) : 'Instructor'; ?>
                        </div>
                        <div class="mb-3">
                            <strong>Jumlah Peserta:</strong> <span class="text-primary"><?php echo htmlspecialchars($course['students'] ?? '1,000'); ?> peserta</span>
                        </div>
                        <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
                        <div class="alert alert-success" role="alert">
                            Kursus berhasil ditambahkan ke keranjang! <a href="cart.php" class="alert-link">Lihat Keranjang</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- What You'll Learn Section -->
                <div class="card p-4 mt-4">
                    <h4 class="mb-3">Apa yang akan Anda pelajari</h4>
                    <div class="row">
                        <?php foreach ($what_you_learn as $item): ?>
                        <div class="col-md-6 mb-2">
                            <div class="d-flex">
                                <i class="fas fa-check text-success me-2 mt-1"></i>
                                <p class="mb-2"><?php echo htmlspecialchars($item); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Course Content Preview -->
                <div class="card p-4 mt-4">
                    <h4 class="mb-3">Materi Kursus</h4>
                    <p class="text-muted">Kursus ini terdiri dari <?php echo htmlspecialchars($course['lectures'] ?? '120'); ?> pelajaran dengan total durasi <?php echo htmlspecialchars($course['duration'] ?? '10 jam'); ?>.</p>
                    
                    <!-- Sample Course Content -->
                    <div class="border rounded">
                        <div class="bg-light p-3 fw-medium">
                            Modul 1: Pengenalan
                        </div>
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-play-circle text-primary me-3"></i>
                                    <span>Pendahuluan dan Persiapan</span>
                                </div>
                                <span class="text-muted small">10:15</span>
                            </div>
                        </div>
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-play-circle text-primary me-3"></i>
                                    <span>Instalasi dan Setup</span>
                                </div>
                                <span class="text-muted small">15:32</span>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-lock text-muted me-3"></i>
                                    <span class="text-muted">Konsep Dasar (Premium)</span>
                                </div>
                                <span class="text-muted small">20:45</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="#" class="text-decoration-none">Lihat Semua Materi</a>
                    </div>
                </div>

                <!-- Instructor Section -->
                <div class="card p-4 mt-4">
                    <h4 class="mb-3">Instruktur</h4>
                    <div class="d-flex">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; font-size: 24px;">
                                <?php echo !empty($instructor) ? substr($instructor['name'], 0, 1) : 'I'; ?>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-1"><?php echo !empty($instructor) ? htmlspecialchars($instructor['name']) : 'Instructor Name'; ?></h5>
                            <p class="text-muted mb-2"><?php echo !empty($instructor) ? htmlspecialchars($instructor['title'] ?? 'Professional Instructor') : 'Professional Instructor'; ?></p>
                            
                            <div class="d-flex mb-3">
                                <div class="me-3">
                                    <i class="fas fa-star text-warning"></i>
                                    <span><?php echo $averageRating; ?> Rating</span>
                                </div>
                                <div class="me-3">
                                    <i class="fas fa-comment text-primary"></i>
                                    <span><?php echo $reviewCount; ?> Ulasan</span>
                                </div>
                                <div>
                                    <i class="fas fa-user-graduate text-success"></i>
                                    <span><?php echo htmlspecialchars($course['students'] ?? '1,000'); ?> Pelajar</span>
                                </div>
                            </div>
                            
                            <p class="text-muted">
                                <?php echo !empty($instructor) ? htmlspecialchars($instructor['bio'] ?? 'Instructor biography not available.') : 'Instructor biography not available.'; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="card p-4 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Ulasan Pelajar</h4>
                        <div class="d-flex align-items-center">
                            <div class="rating-stars me-2">
                                <?php 
                                for ($i = 1; $i <= 5; $i++) {
                                    echo ($i <= $averageRating) ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <div>
                                <span class="fw-bold"><?php echo $averageRating; ?></span>/5 
                                <span class="text-muted">(<?php echo $reviewCount; ?> ulasan)</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rating Breakdown -->
                    <div class="mb-4">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-2"><?php echo $i; ?></span>
                            <span class="rating-stars me-2">★</span>
                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $ratingPercentages[$i]; ?>%" aria-valuenow="<?php echo $ratingPercentages[$i]; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="text-muted"><?php echo $ratingDistribution[$i]; ?></span>
                        </div>
                        <?php endfor; ?>
                    </div>
                    
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                        <div class="border-top pt-3 pb-3">
                            <div class="d-flex mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <?php echo substr($review['name'] ?? 'U', 0, 1); ?>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($review['name'] ?? 'Anonymous User'); ?></h6>
                                    <div class="d-flex align-items-center">
                                        <div class="rating-stars me-2">
                                            <?php 
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo ($i <= $review['rating']) ? '★' : '☆';
                                            }
                                            ?>
                                        </div>
                                        <small class="text-muted">
                                            <?php 
                                            echo isset($review['date']) 
                                            ? date('d M Y', strtotime($review['date'])) 
                                            : 'Recent';
                                            ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0"><?php echo htmlspecialchars($review['comment']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Belum ada ulasan untuk kursus ini.</p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Write a Review -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="border-top pt-4 mt-3">
                        <h5 class="mb-3">Tulis Ulasan</h5>
                        <form action="submit_review.php" method="post">
                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <div class="d-flex">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <div class="me-2">
                                        <input type="radio" name="rating" id="rating-<?php echo $i; ?>" value="<?php echo $i; ?>" class="d-none">
                                        <label for="rating-<?php echo $i; ?>" class="rating-stars" style="cursor: pointer;">★</label>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="comment" class="form-label">Komentar</label>
                                <textarea name="comment" id="comment" rows="4" class="form-control"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                        </form>
                    </div>
                    <?php else: ?>
                    <div class="border-top pt-4 mt-3 text-center">
                        <p class="mb-0">Silakan <a href="login.php" class="text-decoration-none">login</a> untuk menulis ulasan.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Enrollment Box -->
                <div class="card p-4 mb-4 sticky-top" style="top: 20px;">
                    <h4 class="mb-4">Daftar Sekarang</h4>
                    
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Harga Asli</span>
                            <?php if (isset($course['original_price']) && $course['original_price'] > $course['price']): ?>
                                <span class="text-decoration-line-through">Rp <?php echo number_format($course['original_price'], 0, ',', '.'); ?></span>
                            <?php else: ?>
                                <span class="text-decoration-line-through">Rp <?php echo number_format($course['price'] * 1.5, 0, ',', '.'); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Diskon</span>
                            <?php if (isset($course['original_price']) && $course['original_price'] > $course['price']): ?>
                                <span class="text-success">-<?php echo round((($course['original_price'] - $course['price']) / $course['original_price']) * 100); ?>%</span>
                            <?php else: ?>
                                <span class="text-success">-33%</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Harga Akhir</span>
                            <span class="price">Rp <?php echo number_format($course['price'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    
                    <form method="post" action="" class="mb-3">
                        <input type="hidden" name="add_to_cart" value="1">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-shopping-cart me-2"></i>Tambahkan ke Keranjang
                        </button>
                    </form>
                    
                    <a href="checkout.php?course_id=<?php echo $course_id; ?>" class="btn btn-success w-100 mb-3">
                        <i class="fas fa-credit-card me-2"></i>Beli Sekarang
                    </a>
                    
                    <div class="text-center text-muted mb-4">
                        <small>Garansi Uang Kembali 30 Hari</small>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Kursus ini mencakup:</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-video text-muted me-2"></i>
                            <span><?php echo htmlspecialchars($course['lectures'] ?? '120'); ?> video pelajaran</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-file-alt text-muted me-2"></i>
                            <span>20 sumber belajar yang dapat diunduh</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-mobile-alt text-muted me-2"></i>
                            <span>Akses di perangkat mobile dan TV</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-infinity text-muted me-2"></i>
                            <span>Akses seumur hidup</span>
                        </li>
                        <li>
                            <i class="fas fa-certificate text-muted me-2"></i>
                            <span>Sertifikat penyelesaian</span>
                        </li>
                    </ul>
                </div>

                <!-- Related Courses -->
                <div class="card p-4">
                    <h4 class="mb-3">Kursus Terkait</h4>
                    
                    <?php
                    // Fetch related courses
                    $relatedQuery = "SELECT c.* FROM courses c 
                                    JOIN course_categories cc ON c.id = cc.course_id 
                                    JOIN categories cat ON cc.category_id = cat.id 
                                    WHERE cat.name = ? AND c.id != ? 
                                    LIMIT 3";
                    $relatedStmt = mysqli_prepare($conn, $relatedQuery);
                    mysqli_stmt_bind_param($relatedStmt, "si", $courseCategory, $course_id);
                    mysqli_stmt_execute($relatedStmt);
                    $relatedResult = mysqli_stmt_get_result($relatedStmt);
                    ?>
                    
                    <?php if ($relatedResult && mysqli_num_rows($relatedResult) > 0): ?>
                        <?php while ($relatedCourse = mysqli_fetch_assoc($relatedResult)): ?>
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <img src="<?php echo htmlspecialchars($relatedCourse['image']); ?>" alt="<?php echo htmlspecialchars($relatedCourse['title']); ?>" class="related-course-img me-3">
                            <div>
                                <a href="course_detail.php?id=<?php echo $relatedCourse['id']; ?>" class="text-decoration-none fw-medium"><?php echo htmlspecialchars($relatedCourse['title']); ?></a>
                                <div class="rating-stars" style="font-size: 0.8rem;">
                                    <?php 
                                    $relatedRating = $relatedCourse['rating'] ?? rand(3, 5);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $relatedRating) ? '★' : '☆';
                                    }
                                    ?>
                                    <small class="text-muted ms-1">(<?php echo $relatedCourse['students'] ?? rand(10, 500); ?>)</small>
                                </div>
                                <div class="price" style="font-size: 0.9rem;">Rp <?php echo number_format($relatedCourse['price'], 0, ',', '.'); ?></div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted">Tidak ada kursus terkait yang ditemukan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include "../Views/footerbootsrap.php"; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Rating selection script
document.addEventListener('DOMContentLoaded', function() {
    const ratingLabels = document.querySelectorAll('label.rating-stars');
    
    ratingLabels.forEach(function(label) {
        label.addEventListener('click', function() {
            const radioId = this.getAttribute('for');
            if (radioId) {
                document.getElementById(radioId).checked = true;
                
                // Reset all labels to default style
                ratingLabels.forEach(lbl => {
                    lbl.style.color = '#ccc';
                });
                
                // Highlight selected rating and all ratings below it
                const selectedRating = parseInt(radioId.split('-')[1]);
                for (let i = 1; i <= selectedRating; i++) {
                    document.querySelector(label[for="rating-${i}"]).style.color = '#FFD700';
                }
            }
        });
    });
    
    // Initialize with default colors
    for (let i = 1; i <= 5; i++) {
        document.querySelector(label[for="rating-${i}"]).style.color = '#ccc';
    }
});

// Add to cart notification
const addedToCartAlert = document.querySelector('.alert-success');
if (addedToCartAlert) {
    // Auto hide the alert after 5 seconds
    setTimeout(() => {
        addedToCartAlert.style.opacity = '0';
        addedToCartAlert.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            addedToCartAlert.remove();
        }, 500);
    }, 5000);
}

// Sticky sidebar adjustment for better mobile experience
window.addEventListener('resize', function() {
    const sidebarCard = document.querySelector('.sticky-top');
    if (window.innerWidth < 992) {
        sidebarCard.classList.remove('sticky-top');
    } else {
        if (!sidebarCard.classList.contains('sticky-top')) {
            sidebarCard.classList.add('sticky-top');
        }
    }
});

// Initialize tooltip
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
</body>
</html>