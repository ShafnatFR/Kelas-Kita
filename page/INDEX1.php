<?php
include "db.php";
session_start();

// Cek apakah keranjang sudah ada dalam session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
// Database connection simulation
$featuredCourses = [
    [
        'id' => 1,
        'title' => 'Digital Marketing Masterclass',
        'instructor' => 'John Smith',
        'price' => '$79.99',
        'original_price' => '$129.99',
        'rating' => '4.8',
        'reviews' => '1,275',
        'tag' => 'BEST SELLER',
        'image' => 'assets/images/course1.jpg',
        'badge' => 'HOT'
    ],
    [
        'id' => 2,
        'title' => 'Mobile Flutter Development',
        'instructor' => 'Sarah Johnson',
        'price' => '$89.99',
        'original_price' => '$149.99',
        'rating' => '4.9',
        'reviews' => '852',
        'tag' => 'NEW',
        'image' => 'assets/images/course2.jpg'
    ],
    [
        'id' => 3,
        'title' => 'Advanced Python for Data Science',
        'instructor' => 'Michael Wang',
        'price' => '$99.99',
        'original_price' => '$169.99',
        'rating' => '4.7',
        'reviews' => '2,342',
        'tag' => 'POPULAR',
        'image' => 'assets/images/course3.jpg'
    ],
    [
        'id' => 4,
        'title' => 'UX Complete Start-to-Finish',
        'instructor' => 'Emma Brooks',
        'price' => '$69.99',
        'original_price' => '$119.99',
        'rating' => '4.6',
        'reviews' => '1,064',
        'tag' => 'TRENDING',
        'image' => 'assets/images/course4.jpg'
    ],
    [
        'id' => 5,
        'title' => 'Graphic Design Fundamentals',
        'instructor' => 'Alex Martinez',
        'price' => '$59.99',
        'original_price' => '$99.99',
        'rating' => '4.5',
        'reviews' => '756',
        'tag' => 'BEGINNER',
        'image' => 'assets/images/course5.jpg'
    ],
    [
        'id' => 6,
        'title' => 'Business Leadership Mastery',
        'instructor' => 'Jessica Lee',
        'price' => '$119.99',
        'original_price' => '$199.99',
        'rating' => '4.9',
        'reviews' => '1,536',
        'tag' => 'ADVANCED',
        'image' => 'assets/images/course6.jpg'
    ],
    [
        'id' => 7,
        'title' => 'Web Development Bootcamp',
        'instructor' => 'David Chen',
        'price' => '$94.99',
        'original_price' => '$159.99',
        'rating' => '4.8',
        'reviews' => '3,128',
        'tag' => 'BEST SELLER',
        'image' => 'assets/images/course7.jpg'
    ],
    [
        'id' => 8,
        'title' => 'Social Media Marketing',
        'instructor' => 'Olivia Wilson',
        'price' => '$69.99',
        'original_price' => '$119.99',
        'rating' => '4.7',
        'reviews' => '942',
        'tag' => 'POPULAR',
        'image' => 'assets/images/course8.jpg'
    ]
];

$popularCourses = [
    [
        'id' => 9,
        'title' => 'Project Management Professional',
        'instructor' => 'Robert Johnson',
        'price' => '$129.99',
        'original_price' => '$199.99',
        'rating' => '4.9',
        'reviews' => '2,156',
        'tag' => 'CERTIFICATION',
        'image' => 'assets/images/course9.jpg',
        'color' => 'bg-blue-100'
    ],
    [
        'id' => 10,
        'title' => 'Financial Analysis Masterclass',
        'instructor' => 'Linda Thompson',
        'price' => '$109.99',
        'original_price' => '$189.99',
        'rating' => '4.8',
        'reviews' => '1,245',
        'tag' => 'PROFESSIONAL',
        'image' => 'assets/images/course10.jpg',
        'color' => 'bg-orange-100'
    ],
    [
        'id' => 11,
        'title' => 'Machine Learning A-Z',
        'instructor' => 'James Wilson',
        'price' => '$119.99',
        'original_price' => '$199.99',
        'rating' => '4.9',
        'reviews' => '3,542',
        'tag' => 'ADVANCED',
        'image' => 'assets/images/course11.jpg',
        'color' => 'bg-green-100'
    ],
    [
        'id' => 12,
        'title' => 'Content Creation Masterclass',
        'instructor' => 'Sophia Lee',
        'price' => '$89.99',
        'original_price' => '$149.99',
        'rating' => '4.7',
        'reviews' => '1,832',
        'tag' => 'CREATIVE',
        'image' => 'assets/images/course12.jpg',
        'color' => 'bg-purple-100'
    ]
];

$categories = [
    ['name' => 'All', 'icon' => 'grid'],
    ['name' => 'Development', 'icon' => 'code'],
    ['name' => 'Business', 'icon' => 'briefcase'],
    ['name' => 'Marketing', 'icon' => 'megaphone'],
    ['name' => 'Design', 'icon' => 'palette'],
    ['name' => 'Health', 'icon' => 'heart'],
    ['name' => 'Finance', 'icon' => 'chart-bar'],
    ['name' => 'IT & Software', 'icon' => 'computer']
];

$workshopCount = 253;
$studentsCount = "100K+";
$instructorsCount = "1.2K+";
$coursesCount = "2.5K+";
$toolsCount = "50+";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upskill - Online Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-gradient {
            background: linear-gradient(135deg,rgb(29, 188, 79) 0%, #60efff 100%);
        }
        .rating-stars {
            color: #FFD700;
        }
    </style>
</head>
<body class="bg-gray-50">
<?php  include "../Views/navbar.php"?>

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
    

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-16">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Faster Way For Your<br>Grow & Upskill</h1>
                <p class="text-lg mb-8 text-blue-100">Learn the skills you need to advance your career with our expert-led courses</p>
                <div class="flex space-x-4">
                    <a href="#" class="bg-white text-blue-600 px-6 py-3 rounded-md font-medium hover:bg-gray-100 transition">Get Started</a>
                    <a href="#" class="border border-white text-white px-6 py-3 rounded-md font-medium hover:bg-white hover:text-blue-600 transition">Learn More</a>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-end">
                <img src="assets/images/hero-person.png" alt="Person learning online" class="rounded-lg max-w-full">
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-center gap-6">
                <?php foreach ($categories as $category): ?>
                <div class="flex flex-col items-center bg-gray-50 p-6 rounded-lg w-24 h-24 justify-center hover:shadow-md transition cursor-pointer">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-full mb-2">
                        <i class="fas fa-<?php echo $category['icon']; ?>"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700"><?php echo $category['name']; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold mb-10">Featured courses</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($featuredCourses as $course): ?>
                <a href="course-detail.php?id=<?php echo $course['id']; ?>" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="relative">
                        <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>" class="w-full h-40 object-cover">
                        <?php if (isset($course['badge'])): ?>
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded"><?php echo $course['badge']; ?></span>
                        <?php endif; ?>
                        <span class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded"><?php echo $course['tag']; ?></span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2"><?php echo $course['title']; ?></h3>
                        <p class="text-gray-600 text-sm mb-3"><?php echo $course['instructor']; ?></p>
                        <div class="flex items-center mb-3">
                            <div class="rating-stars mr-1">★★★★★</div>
                            <span class="text-yellow-500 font-medium"><?php echo $course['rating']; ?></span>
                            <span class="text-gray-500 text-sm ml-1">(<?php echo $course['reviews']; ?>)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-bold text-gray-900"><?php echo $course['price']; ?></span>
                                <span class="text-gray-500 text-sm line-through ml-2"><?php echo $course['original_price']; ?></span>
                            </div>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Popular Courses Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold mb-10">Popular classes</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($popularCourses as $course): ?>
                <a href="course-detail.php?id=<?php echo $course['id']; ?>" class="block <?php echo $course['color']; ?> rounded-lg overflow-hidden p-4 hover:shadow-md transition">
                    <span class="inline-block bg-white text-blue-600 text-xs px-2 py-1 rounded mb-4"><?php echo $course['tag']; ?></span>
                    <h3 class="font-semibold text-lg mb-2"><?php echo $course['title']; ?></h3>
                    <p class="text-gray-700 text-sm mb-3"><?php echo $course['instructor']; ?></p>
                    <div class="flex items-center mb-3">
                        <div class="rating-stars mr-1">★★★★★</div>
                        <span class="text-yellow-500 font-medium"><?php echo $course['rating']; ?></span>
                        <span class="text-gray-700 text-sm ml-1">(<?php echo $course['reviews']; ?>)</span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="md:w-1/2">
                    <img src="../assets/images/stdnt using laptop.jpg" alt="Student using laptop" class="rounded-lg shadow-md w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-3xl font-bold mb-6">Why Upskill becomes the best training course & bootcamp</h2>
                    <p class="text-gray-600 mb-8">We've designed our platform to provide the best learning experience with expert instructors, hands-on projects, and a supportive community.</p>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-check text-blue-600"></i>
                            </div>
                            <span class="font-medium">Skilled instructors</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-check text-blue-600"></i>
                            </div>
                            <span class="font-medium">Hands-on projects</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-check text-blue-600"></i>
                            </div>
                            <span class="font-medium">Premium resources</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-check text-blue-600"></i>
                            </div>
                            <span class="font-medium">Lifetime access</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-center">
                <div class="p-4">
                    <div class="text-4xl font-bold text-blue-600 mb-2"><?php echo $workshopCount; ?>+</div>
                    <p class="text-gray-600">Online Workshops</p>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-blue-600 mb-2"><?php echo $studentsCount; ?></div>
                    <p class="text-gray-600">Students</p>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-blue-600 mb-2"><?php echo $instructorsCount; ?></div>
                    <p class="text-gray-600">Instructors</p>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-blue-600 mb-2"><?php echo $coursesCount; ?></div>
                    <p class="text-gray-600">Online Courses</p>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-blue-600 mb-2"><?php echo $toolsCount; ?></div>
                    <p class="text-gray-600">Practical Tools</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="bg-yellow-400 rounded-lg p-8 flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-2/3 mb-6 md:mb-0">
                    <h3 class="text-2xl font-bold mb-2">Launch Your Career Journey</h3>
                    <p class="text-gray-800">Through upskill</p>
                </div>
                <div>
                    <img src="assets/images/cta-people.png" alt="People learning" class="h-32">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
<?php include "../Views/footer.php"; ?>
</body>
</html>