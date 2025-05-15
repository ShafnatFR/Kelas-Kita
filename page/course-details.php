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

// Check if the course_content table exists before querying it
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
    </style>
</head>
<body>
    <?php include "../Views/navbarbootstrap.php"; ?>

    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="courses.php?category=<?php echo urlencode($courseCategory); ?>"><?php echo htmlspecialchars($courseCategory); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($course['title']); ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8">
                <div class="card p-4">
                    <img src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="card-img-top mb-3" style="object-fit: cover; height: 300px;">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <div class="mb-2">
                            <span class="badge bg-primary badge-custom"><?php echo htmlspecialchars($courseCategory); ?></span>
                            <?php if (!empty($course['badge'])): ?>
                            <span class="badge bg-danger badge-custom"><?php echo htmlspecialchars($course['badge']); ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="rating-stars">
                                <?php 
                                for ($i = 1; $i <= 5; $i++) {
                                    echo ($i <= $averageRating) ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <div>
                                <span class="fw-bold"><?php echo $averageRating; ?></span> / 5 (<?php echo $reviewCount; ?> ulasan)
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Instruktur:</strong> <?php echo !empty($instructor) ? htmlspecialchars($instructor['name']) : 'Instructor'; ?>
                        </div>
                        <div class="mb-3">
                            <strong>Harga:</strong> <span class="price">Rp <?php echo number_format($course['price'], 0, ',', '.'); ?></span>
                            <?php if (isset($course['original_price']) && $course['original_price'] > $course['price']): ?>
                            <span class="text-muted text-decoration-line-through ms-2">Rp <?php echo number_format($course['original_price'], 0, ',', '.'); ?></span>
                            <?php endif; ?>
                        </div>
                        <form method="post" action="">
                            <input type="hidden" name="add_to_cart" value="1">
                            <button type="submit" class="btn btn-primary btn-primary-custom w-100 mb-3">
                                <i class="fas fa-shopping-cart me-2"></i> Tambahkan ke Keranjang
                            </button>
                        </form>
                        <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
                        <div class="alert alert-success" role="alert">
                            Kursus berhasil ditambahkan ke keranjang! <a href="cart.php" class="alert-link">Lihat Keranjang</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- What You'll Learn Section -->
                <div class="card p-4 mt-4">
                    <h4>Apa yang akan Anda pelajari</h4>
                    <ul class="list-unstyled mt-3">
                        <?php foreach ($what_you_learn as $item): ?>
                        <li><i class="fas fa-check text-success me-2"></i><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Reviews Section -->
                <div class="card p-4 mt-4">
                    <h4>Ulasan Pelajar</h4>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rating-stars me-3">
                            <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                echo ($i <= $averageRating) ? '★' : '☆';
                            }
                            ?>
                        </div>
                        <div>
                            <strong><?php echo $averageRating; ?></strong> / 5 (<?php echo $reviewCount; ?> ulasan)
                        </div>
                    </div>
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                        <div class="mb-3 border-bottom pb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <?php echo strtoupper(substr($review['name'] ?? 'U', 0, 1)); ?>
                                </div>
                                <div>
                                    <strong><?php echo htmlspecialchars($review['name'] ?? 'Anonymous'); ?></strong>
                                    <div class="text-muted small">
                                        <?php echo isset($review['date']) ? date('d M Y', strtotime($review['date'])) : 'Recent'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="rating-stars mb-2">
                                <?php 
                                for ($i = 1; $i <= 5; $i++) {
                                    echo ($i <= $review['rating']) ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <p><?php echo htmlspecialchars($review['comment']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Belum ada ulasan untuk kursus ini.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Related Courses -->
                <div class="card p-4 mb-4">
                    <h4>Kursus Terkait</h4>
                    <?php
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
                        <div class="d-flex mb-3">
                            <img src="<?php echo htmlspecialchars($relatedCourse['image']); ?>" alt="<?php echo htmlspecialchars($relatedCourse['title']); ?>" class="me-3" style="width: 80px; height: 60px; object-fit: cover;">
                            <div>
                                <a href="course_detail.php?id=<?php echo $relatedCourse['id']; ?>" class="fw-bold text-decoration-none"><?php echo htmlspecialchars($relatedCourse['title']); ?></a>
                                <div class="rating-stars text-warning">
                                    <?php 
                                    $relatedRating = $relatedCourse['rating'] ?? rand(3, 5);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $relatedRating) ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                                <div class="text-muted">Rp <?php echo number_format($relatedCourse['price'], 0, ',', '.'); ?></div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Tidak ada kursus terkait yang ditemukan.</p>
                    <?php endif; ?>
                </div>

                <!-- Enrollment Box -->
                <div class="card p-4">
                    <h4>Daftar Sekarang</h4>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Harga Asli</span>
                            <?php if (isset($course['original_price']) && $course['original_price'] > $course['price']): ?>
                            <span class="text-decoration-line-through">Rp <?php echo number_format($course['original_price'], 0, ',', '.'); ?></span>
                            <?php else: ?>
                            <span class="text-decoration-line-through">Rp <?php echo number_format($course['price'] * 1.5, 0, ',', '.'); ?></span>
                            <?php endif; ?>
                        </div>

    <!-- Course Content Section -->
    <div class="container mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-8/12 md:pr-8">
                <!-- What You'll Learn Section -->
                <div class="bg-white p-6 rounded-lg shadow-sm mb-8">
                    <h2 class="text-2xl font-bold mb-6">Apa yang akan Anda pelajari</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if (!empty($what_you_learn)): ?>
                            <?php foreach ($what_you_learn as $item): ?>
                            <div class="flex">
                                <div class="mr-3 mt-1">
                                    <i class="fas fa-check text-green-500"></i>
                                </div>
                                <p><?php echo htmlspecialchars($item); ?></p>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Course Content Preview -->
                <div id="course-content" class="bg-white p-6 rounded-lg shadow-sm mb-8">
                    <h2 class="text-2xl font-bold mb-6">Materi Kursus</h2>
                    
                    <div class="mb-4">
                        <p class="text-gray-700">Kursus ini terdiri dari <?php echo htmlspecialchars($course['lectures'] ?? '120'); ?> pelajaran dengan total durasi <?php echo htmlspecialchars($course['duration'] ?? '10 jam'); ?>.</p>
                    </div>
                    
                    <!-- Sample Course Content -->
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-100 p-4 font-medium">
                            Modul 1: Pengenalan
                        </div>
                        <div class="p-4 border-b">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-play-circle text-blue-600 mr-3"></i>
                                    <span>Pendahuluan dan Persiapan</span>
                                </div>
                                <span class="text-gray-500 text-sm">10:15</span>
                            </div>
                        </div>
                        <div class="p-4 border-b">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-play-circle text-blue-600 mr-3"></i>
                                    <span>Instalasi dan Setup</span>
                                </div>
                                <span class="text-gray-500 text-sm">15:32</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-lock text-gray-400 mr-3"></i>
                                    <span class="text-gray-500">Konsep Dasar (Premium)</span>
                                </div>
                                <span class="text-gray-500 text-sm">20:45</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Lihat Semua Materi</a>
                    </div>
                </div>
                
                <!-- Instructor Section -->
                <div id="instructor" class="bg-white p-6 rounded-lg shadow-sm mb-8">
                    <h2 class="text-2xl font-bold mb-6">Instruktur</h2>
                    
                    <div class="flex flex-col md:flex-row items-start">
                        <div class="mb-4 md:mb-0 md:mr-6">
                            <div class="w-20 h-20 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                                <?php echo !empty($instructor) ? substr($instructor['name'], 0, 1) : 'I'; ?>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-bold mb-2">
                                <?php echo !empty($instructor) ? htmlspecialchars($instructor['name']) : 'Instructor Name'; ?>
                            </h3>
                            <p class="text-gray-500 mb-4">
                                <?php echo !empty($instructor) ? htmlspecialchars($instructor['title'] ?? 'Professional Instructor') : 'Professional Instructor'; ?>
                            </p>
                            
                            <div class="flex items-center mb-4">
                                <div class="flex items-center mr-4">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                                    <span><?php echo $averageRating; ?> Rating</span>
                                </div>
                                <div class="flex items-center mr-4">
                                    <i class="fas fa-comment text-blue-600 mr-1"></i>
                                    <span><?php echo $reviewCount; ?> Ulasan</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-user-graduate text-green-600 mr-1"></i>
                                    <span><?php echo htmlspecialchars($course['students'] ?? '1,000'); ?> Pelajar</span>
                                </div>
                            </div>
                            
                            <p class="text-gray-700 mb-4">
                                <?php echo !empty($instructor) ? htmlspecialchars($instructor['bio'] ?? 'Instructor biography not available.') : 'Instructor biography not available.'; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Reviews Section -->
                <div class="bg-white p-6 rounded-lg shadow-sm mb-8">
                    <div class="flex flex-col md:flex-row justify-between mb-6">
                        <h3 class="text-2xl font-semibold">Ulasan Pelajar</h3>
                        <div class="flex items-center">
                            <div class="rating-stars text-2xl mr-2">
                                <?php 
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $averageRating) {
                                        echo '★';
                                    } else {
                                        echo '☆';
                                    }
                                }
                                ?>
                            </div>
                            <div>
                                <span class="font-bold text-lg"><?php echo $averageRating; ?></span>
                                <span class="text-gray-500">/5</span>
                                <p class="text-gray-500"><?php echo $reviewCount; ?> ulasan</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rating Breakdown -->
                    <div class="mb-8">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <div class="flex items-center mb-2">
                            <span class="w-8 text-right mr-2"><?php echo $i; ?></span>
                            <div class="rating-stars mr-2">★</div>
                            <div class="flex-grow">
                                <div class="bg-gray-200 rounded-full h-2 w-full">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: <?php echo $ratingPercentages[$i]; ?>%"></div>
                                </div>
                            </div>
                            <span class="w-12 text-right ml-2"><?php echo $ratingDistribution[$i]; ?></span>
                        </div>
                        <?php endfor; ?>
                    </div>
                    
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                        <div class="border-t border-gray-200 pt-6 pb-6">
                            <div class="flex items-center mb-3">
                        <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center mr-3">
                            <?php echo substr($review['name'] ?? 'User', 0, 1); ?>
                        </div>
                        <div>
                            <h4 class="font-medium"><?php echo htmlspecialchars($review['name'] ?? 'Anonymous User'); ?></h4>
                            <div class="flex items-center">
                                <div class="rating-stars mr-2">
                                    <?php 
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $review['rating']) {
                                            echo '★';
                                        } else {
                                            echo '☆';
                                        }
                                    }
                                    ?>
                                </div>
                                <span class="text-gray-500 text-sm">
                                    <?php 
                                    echo isset($review['date']) 
                                    ? date('d M Y', strtotime($review['date'])) 
                                    : 'Recent';
                                    ?>
                                </span>
                            </div>
                        </div>
                            </div>
                            <p class="text-gray-700"><?php echo htmlspecialchars($review['comment']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada ulasan untuk kursus ini.</p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Write a Review -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h4 class="font-bold text-lg mb-4">Tulis Ulasan</h4>
                        <form action="submit_review.php" method="post">
                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Rating</label>
                                <div class="flex">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <div class="mr-2">
                                        <input type="radio" name="rating" id="rating-<?php echo $i; ?>" value="<?php echo $i; ?>" class="hidden peer">
                                        <label for="rating-<?php echo $i; ?>" class="rating-stars text-2xl peer-checked:text-yellow-500 cursor-pointer">★</label>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="comment" class="block text-gray-700 mb-2">Komentar</label>
                                <textarea name="comment" id="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"></textarea>
                            </div>
                            
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Kirim Ulasan</button>
                        </form>
                    </div>
                    <?php else: ?>
                    <div class="border-t border-gray-200 pt-6 mt-6 text-center">
                        <p class="mb-2">Silakan <a href="login.php" class="text-blue-600 hover:text-blue-800">login</a> untuk menulis ulasan.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="md:w-4/12">
                <!-- Related Courses -->
                <div class="bg-white p-6 rounded-lg shadow-sm mb-8">
                    <h3 class="text-xl font-bold mb-4">Kursus Terkait</h3>
                    
                    <?php
                    // Fetch related courses (same category, different course)
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
                        <div class="flex mb-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0 last:mb-0">
                            <div class="w-20 h-16 flex-shrink-0 mr-4">
                                <img src="<?php echo htmlspecialchars($relatedCourse['image']); ?>" alt="<?php echo htmlspecialchars($relatedCourse['title']); ?>" class="w-full h-full object-cover rounded">
                            </div>
                            <div>
<h4 class="font-medium mb-1">
    <a href="course_detail.php?id=<?php echo $relatedCourse['id']; ?>"><?php echo htmlspecialchars($relatedCourse['title']); ?></a>
</h4>
                                    <div class="flex items-center">
                                        <div class="rating-stars text-xs mr-1">
                                            <?php 
                                            // Calculate average rating for related course (simplified)
                                            $relatedRating = $relatedCourse['rating'] ?? rand(3, 5);
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $relatedRating) {
                                                    echo '★';
                                                } else {
                                                    echo '☆';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <span class="text-sm text-gray-500">(<?php echo $relatedCourse['students'] ?? rand(10, 500); ?>)</span>
                                    </div>
                                    <p class="text-blue-600 font-medium">Rp <?php echo number_format($relatedCourse['price'], 0, ',', '.'); ?></p>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-gray-500">Tidak ada kursus terkait yang ditemukan.</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Student Enrollment Box -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-8 sticky top-24">
                        <h3 class="text-xl font-bold mb-4">Daftar Sekarang</h3>
                        
                        <div class="bg-gray-100 p-4 rounded-lg mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span>Harga Asli</span>
                                <?php if (isset($course['original_price']) && $course['original_price'] > $course['price']): ?>
                                    <span class="line-through">Rp <?php echo number_format($course['original_price'], 0, ',', '.'); ?></span>
                                <?php else: ?>
                                    <span class="line-through">Rp <?php echo number_format($course['price'] * 1.5, 0, ',', '.'); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span>Diskon</span>
                                <?php if (isset($course['original_price']) && $course['original_price'] > $course['price']): ?>
                                    <span class="text-green-600">-<?php echo round((($course['original_price'] - $course['price']) / $course['original_price']) * 100); ?>%</span>
                                <?php else: ?>
                                    <span class="text-green-600">-33%</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center justify-between font-bold">
                                <span>Harga Akhir</span>
                                <span>Rp <?php echo number_format($course['price'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                        
                        <form method="post" action="">
                            <input type="hidden" name="add_to_cart" value="1">
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md font-medium hover:bg-blue-700 transition mb-4">
                                <i class="fas fa-shopping-cart mr-2"></i>Tambahkan ke Keranjang
                            </button>
                        </form>
                        
                        <a href="checkout.php?course_id=<?php echo $course_id; ?>" class="block w-full bg-green-600 text-white text-center py-3 rounded-md font-medium hover:bg-green-700 transition mb-4">
                            <i class="fas fa-credit-card mr-2"></i>Beli Sekarang
                        </a>
                        
                        <div class="text-center text-sm text-gray-500 mb-4">
                            <p>Garansi Uang Kembali 30 Hari</p>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-medium mb-2">Kursus ini mencakup:</h4>
                            <ul class="space-y-2">
                                <li class="flex items-center">
                                    <i class="fas fa-video text-gray-600 mr-2"></i>
                                    <span><?php echo htmlspecialchars($course['lectures'] ?? '120'); ?> video pelajaran</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-file-alt text-gray-600 mr-2"></i>
                                    <span>20 sumber belajar yang dapat diunduh</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-mobile-alt text-gray-600 mr-2"></i>
                                    <span>Akses di perangkat mobile dan TV</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-infinity text-gray-600 mr-2"></i>
                                    <span>Akses seumur hidup</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-certificate text-gray-600 mr-2"></i>
                                    <span>Sertifikat penyelesaian</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <?php include "../Views/footerbootsrap.php"; ?>
        
        <script>
        // Simple script to handle rating selection
        document.addEventListener('DOMContentLoaded', function() {
            const ratingLabels = document.querySelectorAll('.rating-stars');
            
            ratingLabels.forEach(function(label, index) {
                label.addEventListener('click', function() {
                    const radioId = this.getAttribute('for');
                    if (radioId) {
                        document.getElementById(radioId).checked = true;
                    }
                });
            });
        });
        </script>
    </body>
    </html>