<?php
// Get course ID from URL
$course_id = isset($_GET['id']) ? $_GET['id'] : 0;

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
        'badge' => 'HOT',
        'description' => 'Learn the most effective digital marketing strategies to grow your business online. This comprehensive course covers SEO, social media marketing, email marketing, content marketing, and paid advertising. You\'ll learn how to create effective marketing campaigns, analyze their performance, and optimize them for better results.',
        'what_you_learn' => [
            'Create effective digital marketing strategies',
            'Optimize websites for search engines (SEO)',
            'Run successful social media campaigns',
            'Create and optimize paid advertising campaigns',
            'Build and grow an email marketing list',
            'Analyze marketing data and create reports'
        ],
        'reviews_list' => [
            [
                'name' => 'Sarah Johnson',
                'rating' => 5,
                'date' => '15 April 2025',
                'comment' => 'This course completely transformed my marketing approach. The instructor explains complex concepts in a simple way, and the practical exercises helped me implement what I learned immediately. Highly recommended!'
            ],
            [
                'name' => 'Michael Brown',
                'rating' => 4,
                'date' => '2 April 2025',
                'comment' => 'Very comprehensive course with lots of real-world examples. The section on SEO was particularly helpful for my business. Only thing missing was more case studies.'
            ],
            [
                'name' => 'Emma Wilson',
                'rating' => 4.5,
                'date' => '27 March 2025',
                'comment' => 'John is an amazing instructor. His explanations are clear and the course content is up-to-date with the latest marketing trends. I\'ve already seen significant improvements in my campaigns.'
            ]
        ]
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
        'image' => 'assets/images/course2.jpg',
        'description' => 'Master Flutter and Dart to build beautiful, fast, and responsive cross-platform mobile applications for iOS and Android. Learn from scratch and build real-world apps with clean architecture. By the end of this course, you\'ll be able to develop your own mobile applications with confidence.',
        'what_you_learn' => [
            'Build beautiful, fast native-quality apps with Flutter',
            'Develop cross-platform apps for iOS and Android',
            'Understand Dart programming language',
            'Create responsive UIs with Flutter widgets',
            'Implement state management in Flutter apps',
            'Connect your app to backend services and APIs'
        ],
        'reviews_list' => [
            [
                'name' => 'David Chen',
                'rating' => 5,
                'date' => '10 April 2025',
                'comment' => 'Amazing course! Sarah explains complex concepts in an easy-to-understand way. I was able to build my first Flutter app in just a few days following her guidance.'
            ],
            [
                'name' => 'Lisa Martinez',
                'rating' => 5,
                'date' => '5 April 2025',
                'comment' => 'I\'ve tried many Flutter courses but this one is by far the best. The projects are practical and the instructor is very responsive to questions.'
            ],
            [
                'name' => 'James Wilson',
                'rating' => 4,
                'date' => '22 March 2025',
                'comment' => 'Great content and pacing. The only reason I\'m giving 4 stars instead of 5 is that I would have liked more advanced topics covered at the end.'
            ]
        ]
    ],
    [
        'id' => 3,
        'title' => 'Advanced python for data science',
        'instructor' => 'Michael Wang',
        'price' => '$99.99',
        'original_price' => '$169.99',
        'rating' => '4.7',
        'reviews' => '2,342',
        'tag' => 'NEW',
        'image' => 'assets/images/course2.jpg',
        'description' => 'Master Flutter and Dart to build beautiful, fast, and responsive cross-platform mobile applications for iOS and Android. Learn from scratch and build real-world apps with clean architecture. By the end of this course, you\'ll be able to develop your own mobile applications with confidence.',
        'what_you_learn' => [
            'Build beautiful, fast native-quality apps with Flutter',
            'Develop cross-platform apps for iOS and Android',
            'Understand Dart programming language',
            'Create responsive UIs with Flutter widgets',
            'Implement state management in Flutter apps',
            'Connect your app to backend services and APIs'
        ],
        'reviews_list' => [
            [
                'name' => 'David Chen',
                'rating' => 5,
                'date' => '10 April 2025',
                'comment' => 'Amazing course! Sarah explains complex concepts in an easy-to-understand way. I was able to build my first Flutter app in just a few days following her guidance.'
            ],
            [
                'name' => 'Lisa Martinez',
                'rating' => 5,
                'date' => '5 April 2025',
                'comment' => 'I\'ve tried many Flutter courses but this one is by far the best. The projects are practical and the instructor is very responsive to questions.'
            ],
            [
                'name' => 'James Wilson',
                'rating' => 4,
                'date' => '22 March 2025',
                'comment' => 'Great content and pacing. The only reason I\'m giving 4 stars instead of 5 is that I would have liked more advanced topics covered at the end.'
            ]
        ]
    ],
    [
        'id' => 4,
        'title' => 'UX Complete Start to Finish',
        'instructor' => 'Emma Brooks',
        'price' => '$69.99',
        'original_price' => '$119.99',
        'rating' => '4.6',
        'reviews' => '1,062',
        'tag' => 'NEW',
        'image' => 'assets/images/course2.jpg',
        'description' => 'Master Flutter and Dart to build beautiful, fast, and responsive cross-platform mobile applications for iOS and Android. Learn from scratch and build real-world apps with clean architecture. By the end of this course, you\'ll be able to develop your own mobile applications with confidence.',
        'what_you_learn' => [
            'Build beautiful, fast native-quality apps with Flutter',
            'Develop cross-platform apps for iOS and Android',
            'Understand Dart programming language',
            'Create responsive UIs with Flutter widgets',
            'Implement state management in Flutter apps',
            'Connect your app to backend services and APIs'
        ],
        'reviews_list' => [
            [
                'name' => 'David Chen',
                'rating' => 5,
                'date' => '10 April 2025',
                'comment' => 'Amazing course! Sarah explains complex concepts in an easy-to-understand way. I was able to build my first Flutter app in just a few days following her guidance.'
            ],
            [
                'name' => 'Lisa Martinez',
                'rating' => 5,
                'date' => '5 April 2025',
                'comment' => 'I\'ve tried many Flutter courses but this one is by far the best. The projects are practical and the instructor is very responsive to questions.'
            ],
            [
                'name' => 'James Wilson',
                'rating' => 4,
                'date' => '22 March 2025',
                'comment' => 'Great content and pacing. The only reason I\'m giving 4 stars instead of 5 is that I would have liked more advanced topics covered at the end.'
            ]
        ]
    ],
    [
        'id' => 5,
        'title' => 'Graghic design Fundamentals',
        'instructor' => 'Alex Martinez',
        'price' => '$59.99',
        'original_price' => '$99.99',
        'rating' => '4.5',
        'reviews' => '756',
        'tag' => 'NEW',
        'image' => 'assets/images/course2.jpg',
        'description' => 'Master Flutter and Dart to build beautiful, fast, and responsive cross-platform mobile applications for iOS and Android. Learn from scratch and build real-world apps with clean architecture. By the end of this course, you\'ll be able to develop your own mobile applications with confidence.',
        'what_you_learn' => [
            'Build beautiful, fast native-quality apps with Flutter',
            'Develop cross-platform apps for iOS and Android',
            'Understand Dart programming language',
            'Create responsive UIs with Flutter widgets',
            'Implement state management in Flutter apps',
            'Connect your app to backend services and APIs'
        ],
        'reviews_list' => [
            [
                'name' => 'David Chen',
                'rating' => 5,
                'date' => '10 April 2025',
                'comment' => 'Amazing course! Sarah explains complex concepts in an easy-to-understand way. I was able to build my first Flutter app in just a few days following her guidance.'
            ],
            [
                'name' => 'Lisa Martinez',
                'rating' => 5,
                'date' => '5 April 2025',
                'comment' => 'I\'ve tried many Flutter courses but this one is by far the best. The projects are practical and the instructor is very responsive to questions.'
            ],
            [
                'name' => 'James Wilson',
                'rating' => 4,
                'date' => '22 March 2025',
                'comment' => 'Great content and pacing. The only reason I\'m giving 4 stars instead of 5 is that I would have liked more advanced topics covered at the end.'
            ]
        ]
    ],
    [
        'id' => 6,
        'title' => 'Business leadership Mastery',
        'instructor' => 'Jessica Lee',
        'price' => '$119.99',
        'original_price' => '$199.99',
        'rating' => '4.9',
        'reviews' => '1,536',
        'tag' => 'NEW',
        'image' => 'assets/images/course2.jpg',
        'description' => 'Master Flutter and Dart to build beautiful, fast, and responsive cross-platform mobile applications for iOS and Android. Learn from scratch and build real-world apps with clean architecture. By the end of this course, you\'ll be able to develop your own mobile applications with confidence.',
        'what_you_learn' => [
            'Build beautiful, fast native-quality apps with Flutter',
            'Develop cross-platform apps for iOS and Android',
            'Understand Dart programming language',
            'Create responsive UIs with Flutter widgets',
            'Implement state management in Flutter apps',
            'Connect your app to backend services and APIs'
        ],
        'reviews_list' => [
            [
                'name' => 'David Chen',
                'rating' => 5,
                'date' => '10 April 2025',
                'comment' => 'Amazing course! Sarah explains complex concepts in an easy-to-understand way. I was able to build my first Flutter app in just a few days following her guidance.'
            ],
            [
                'name' => 'Lisa Martinez',
                'rating' => 5,
                'date' => '5 April 2025',
                'comment' => 'I\'ve tried many Flutter courses but this one is by far the best. The projects are practical and the instructor is very responsive to questions.'
            ],
            [
                'name' => 'James Wilson',
                'rating' => 4,
                'date' => '22 March 2025',
                'comment' => 'Great content and pacing. The only reason I\'m giving 4 stars instead of 5 is that I would have liked more advanced topics covered at the end.'
            ]
        ]
    ],
    [
        'id' => 7,
        'title' => 'Web Development Bootcamp',
        'instructor' => 'David Chen ',
        'price' => '$94.99',
        'original_price' => '$159.99',
        'rating' => '4.8',
        'reviews' => '3,128',
        'tag' => 'NEW',
        'image' => 'assets/images/course2.jpg',
        'description' => 'Master Flutter and Dart to build beautiful, fast, and responsive cross-platform mobile applications for iOS and Android. Learn from scratch and build real-world apps with clean architecture. By the end of this course, you\'ll be able to develop your own mobile applications with confidence.',
        'what_you_learn' => [
            'Build beautiful, fast native-quality apps with Flutter',
            'Develop cross-platform apps for iOS and Android',
            'Understand Dart programming language',
            'Create responsive UIs with Flutter widgets',
            'Implement state management in Flutter apps',
            'Connect your app to backend services and APIs'
        ],
        'reviews_list' => [
            [
                'name' => 'David Chen',
                'rating' => 5,
                'date' => '10 April 2025',
                'comment' => 'Amazing course! Sarah explains complex concepts in an easy-to-understand way. I was able to build my first Flutter app in just a few days following her guidance.'
            ],
            [
                'name' => 'Lisa Martinez',
                'rating' => 5,
                'date' => '5 April 2025',
                'comment' => 'I\'ve tried many Flutter courses but this one is by far the best. The projects are practical and the instructor is very responsive to questions.'
            ],
            [
                'name' => 'James Wilson',
                'rating' => 4,
                'date' => '22 March 2025',
                'comment' => 'Great content and pacing. The only reason I\'m giving 4 stars instead of 5 is that I would have liked more advanced topics covered at the end.'
            ]
        ]
    ],
    [
        'id' => 8,
        'title' => 'Social Media Marketing',
        'instructor' => 'Olivia Wilson',
        'price' => '$69.99',
        'original_price' => '$119.99',
        'rating' => '4.7',
        'reviews' => '942',
        'tag' => 'NEW',
        'image' => 'assets/images/course2.jpg',
        'description' => 'Master Flutter and Dart to build beautiful, fast, and responsive cross-platform mobile applications for iOS and Android. Learn from scratch and build real-world apps with clean architecture. By the end of this course, you\'ll be able to develop your own mobile applications with confidence.',
        'what_you_learn' => [
            'Build beautiful, fast native-quality apps with Flutter',
            'Develop cross-platform apps for iOS and Android',
            'Understand Dart programming language',
            'Create responsive UIs with Flutter widgets',
            'Implement state management in Flutter apps',
            'Connect your app to backend services and APIs'
        ],
        'reviews_list' => [
            [
                'name' => 'David Chen',
                'rating' => 5,
                'date' => '10 April 2025',
                'comment' => 'Amazing course! Sarah explains complex concepts in an easy-to-understand way. I was able to build my first Flutter app in just a few days following her guidance.'
            ],
            [
                'name' => 'Lisa Martinez',
                'rating' => 5,
                'date' => '5 April 2025',
                'comment' => 'I\'ve tried many Flutter courses but this one is by far the best. The projects are practical and the instructor is very responsive to questions.'
            ],
            [
                'name' => 'James Wilson',
                'rating' => 4,
                'date' => '22 March 2025',
                'comment' => 'Great content and pacing. The only reason I\'m giving 4 stars instead of 5 is that I would have liked more advanced topics covered at the end.'
            ]
        ]
    ],
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
        'color' => 'bg-blue-100',
        'description' => 'Prepare for the PMP certification exam with this comprehensive course. Learn all aspects of professional project management following the PMBOK guide. This course includes practice exams, case studies, and templates to help you succeed in your PMP certification journey.',
        'what_you_learn' => [
            'Master the PMBOK Guide concepts and processes',
            'Prepare effectively for the PMP certification exam',
            'Apply project management best practices',
            'Lead projects successfully from initiation to closure',
            'Manage project scope, schedule, cost, and quality',
            'Develop leadership and communication skills'
        ],
        'reviews_list' => [
            [
                'name' => 'Jennifer Lee',
                'rating' => 5,
                'date' => '20 April 2025',
                'comment' => 'I passed my PMP exam on the first try thanks to this course! Robert covers all the content thoroughly and the practice questions were very similar to the actual exam.'
            ],
            [
                'name' => 'Thomas Brown',
                'rating' => 5,
                'date' => '12 April 2025',
                'comment' => 'Excellent course with great explanations of complex project management concepts. The templates provided are invaluable for my day-to-day work.'
            ],
            [
                'name' => 'Maria Garcia',
                'rating' => 4,
                'date' => '5 April 2025',
                'comment' => 'Very comprehensive and well-structured. The only improvement would be to add more agile project management content.'
            ]
        ]
    ],
    [
        'id' => 10,
        'title' => 'Finalcial Analysis Masterclass',
        'instructor' => 'linda Thompson',
        'price' => '$119.99',
        'original_price' => '$189.99',
        'rating' => '4.8',
        'reviews' => '1,245',
        'tag' => 'CERTIFICATION',
        'image' => 'assets/images/course9.jpg',
        'color' => 'bg-blue-100',
        'description' => 'Prepare for the PMP certification exam with this comprehensive course. Learn all aspects of professional project management following the PMBOK guide. This course includes practice exams, case studies, and templates to help you succeed in your PMP certification journey.',
        'what_you_learn' => [
            'Master the PMBOK Guide concepts and processes',
            'Prepare effectively for the PMP certification exam',
            'Apply project management best practices',
            'Lead projects successfully from initiation to closure',
            'Manage project scope, schedule, cost, and quality',
            'Develop leadership and communication skills'
        ],
        'reviews_list' => [
            [
                'name' => 'Jennifer Lee',
                'rating' => 5,
                'date' => '20 April 2025',
                'comment' => 'I passed my PMP exam on the first try thanks to this course! Robert covers all the content thoroughly and the practice questions were very similar to the actual exam.'
            ],
            [
                'name' => 'Thomas Brown',
                'rating' => 5,
                'date' => '12 April 2025',
                'comment' => 'Excellent course with great explanations of complex project management concepts. The templates provided are invaluable for my day-to-day work.'
            ],
            [
                'name' => 'Maria Garcia',
                'rating' => 4,
                'date' => '5 April 2025',
                'comment' => 'Very comprehensive and well-structured. The only improvement would be to add more agile project management content.'
            ]
        ]
    ],
    [
        'id' => 11,
        'title' => 'Machice Learning A-Z',
        'instructor' => 'James Wilson',
        'price' => '$149.99',
        'original_price' => '$200.99',
        'rating' => '4.9',
        'reviews' => '3,542',
        'tag' => 'CERTIFICATION',
        'image' => 'assets/images/course9.jpg',
        'color' => 'bg-blue-100',
        'description' => 'Prepare for the PMP certification exam with this comprehensive course. Learn all aspects of professional project management following the PMBOK guide. This course includes practice exams, case studies, and templates to help you succeed in your PMP certification journey.',
        'what_you_learn' => [
            'Master the PMBOK Guide concepts and processes',
            'Prepare effectively for the PMP certification exam',
            'Apply project management best practices',
            'Lead projects successfully from initiation to closure',
            'Manage project scope, schedule, cost, and quality',
            'Develop leadership and communication skills'
        ],
        'reviews_list' => [
            [
                'name' => 'Jennifer Lee',
                'rating' => 5,
                'date' => '20 April 2025',
                'comment' => 'I passed my PMP exam on the first try thanks to this course! Robert covers all the content thoroughly and the practice questions were very similar to the actual exam.'
            ],
            [
                'name' => 'Thomas Brown',
                'rating' => 5,
                'date' => '12 April 2025',
                'comment' => 'Excellent course with great explanations of complex project management concepts. The templates provided are invaluable for my day-to-day work.'
            ],
            [
                'name' => 'Maria Garcia',
                'rating' => 4,
                'date' => '5 April 2025',
                'comment' => 'Very comprehensive and well-structured. The only improvement would be to add more agile project management content.'
            ]
        ]
    ],
    [
        'id' => 12,
        'title' => 'Content Creation MasterClass',
        'instructor' => 'Shopia Lee',
        'price' => '$110.99',
        'original_price' => '$140.99',
        'rating' => '4.7',
        'reviews' => '1,832',
        'tag' => 'CERTIFICATION',
        'image' => 'assets/images/course9.jpg',
        'color' => 'bg-blue-100',
        'description' => 'Prepare for the PMP certification exam with this comprehensive course. Learn all aspects of professional project management following the PMBOK guide. This course includes practice exams, case studies, and templates to help you succeed in your PMP certification journey.',
        'what_you_learn' => [
            'Master the PMBOK Guide concepts and processes',
            'Prepare effectively for the PMP certification exam',
            'Apply project management best practices',
            'Lead projects successfully from initiation to closure',
            'Manage project scope, schedule, cost, and quality',
            'Develop leadership and communication skills'
        ],
        'reviews_list' => [
            [
                'name' => 'Jennifer Lee',
                'rating' => 5,
                'date' => '20 April 2025',
                'comment' => 'I passed my PMP exam on the first try thanks to this course! Robert covers all the content thoroughly and the practice questions were very similar to the actual exam.'
            ],
            [
                'name' => 'Thomas Brown',
                'rating' => 5,
                'date' => '12 April 2025',
                'comment' => 'Excellent course with great explanations of complex project management concepts. The templates provided are invaluable for my day-to-day work.'
            ],
            [
                'name' => 'Maria Garcia',
                'rating' => 4,
                'date' => '5 April 2025',
                'comment' => 'Very comprehensive and well-structured. The only improvement would be to add more agile project management content.'
            ]
        ]
    ],

];

// Combine all courses
$allCourses = array_merge($featuredCourses, $popularCourses);

// Find the course by ID
$course = null;
foreach ($allCourses as $c) {
    if ($c['id'] == $course_id) {
        $course = $c;
        break;
    }
}

// Redirect if course not found
if (!$course) {
    header('Location: index.php');
    exit;
}

// Calculate average rating
$totalRating = 0;
$reviewCount = count($course['reviews_list']);
if ($reviewCount > 0) {
    foreach ($course['reviews_list'] as $review) {
        $totalRating += $review['rating'];
    }
    $averageRating = $totalRating / $reviewCount;
} else {
    $averageRating = 0;
}

// Rating distribution
$ratingDistribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
if ($reviewCount > 0) {
    foreach ($course['reviews_list'] as $review) {
        $ratingDistribution[$review['rating']]++;
    }
}

// Calculate percentages
$ratingPercentages = [];
foreach ($ratingDistribution as $rating => $count) {
    $ratingPercentages[$rating] = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $course['title']; ?> | Upskill - Online Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #0061ff 0%, #60efff 100%);
        }
        .rating-stars {
            color: #FFD700;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white py-4 px-6 shadow-sm">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="text-blue-600 font-bold text-2xl">upskill</a>
                <div class="hidden md:flex ml-10 space-x-6">
                    <a href="index.php" class="text-gray-900 font-medium">Home</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Courses</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Categories</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Blog</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Contact</a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#" class="hidden md:inline-block text-gray-600 hover:text-gray-900 px-4 py-2">Log in</a>
                <a href="#" class="bg-blue-600 text-white px-6 py-2 rounded-md font-medium hover:bg-blue-700 transition">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Course Header Section -->
    <div class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-2/3 mb-8 md:mb-0 md:pr-8">
                    <div class="flex items-center mb-4">
                        <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded mr-2"><?php echo $course['tag']; ?></span>
                        <?php if (isset($course['badge'])): ?>
                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded"><?php echo $course['badge']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold mb-4"><?php echo $course['title']; ?></h1>
                    
                    <p class="text-gray-300 mb-6"><?php echo $course['description']; ?></p>
                    
                    <div class="flex items-center mb-6">
                        <div class="rating-stars mr-2">★★★★★</div>
                        <span class="font-medium mr-1"><?php echo $course['rating']; ?></span>
                        <span class="text-gray-400">(<?php echo $course['reviews']; ?> ratings)</span>
                        <span class="mx-2">•</span>
                        <span><?php echo count($course['reviews_list']); ?> reviews</span>
                        <span class="mx-2">•</span>
                        <span>Created by <a href="#" class="text-blue-400 hover:text-blue-300"><?php echo $course['instructor']; ?></a></span>
                    </div>
                    
                    <div class="flex flex-wrap items-center">
                        <a href="#" class="bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 transition mr-4 mb-4 md:mb-0">Enroll Now</a>
                        <div class="flex items-center">
                            <span class="font-bold text-2xl"><?php echo $course['price']; ?></span>
                            <span class="text-gray-400 line-through ml-2"><?php echo $course['original_price']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="md:w-1/3">
                    <div class="relative aspect-video rounded-lg overflow-hidden shadow-lg">
                        <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>" class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40">
                            <button class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                                <i class="fas fa-play text-blue-600 text-2xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content Section -->
    <div class="container mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-8/12 md:pr-8">
                <!-- What You'll Learn Section -->
                <div class="bg-white p-6 rounded-lg shadow-sm mb-8">
                    <h2 class="text-2xl font-bold mb-6">Apa yang akan Anda pelajari</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($course['what_you_learn'] as $item): ?>
                        <div class="flex">
                            <div class="mr-3 mt-1">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <p><?php echo $item; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Course Includes Section -->
                <div class="bg-white p-6 rounded-lg shadow-sm mb-8">
                    <h2 class="text-2xl font-bold mb-6">Kursus ini mencakup:</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-video text-gray-600 mr-3"></i>
                            <p>Video siap pembelajaran 25 jam</p>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-gray-600 mr-3"></i>
                            <p>Akses di perangkat seluler dan TV</p>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-infinity text-gray-600 mr-3"></i>
                            <p>Akses penuh seumur hidup</p>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-certificate text-gray-600 mr-3"></i>
                            <p>Sertifikat penyelesaian</p>
                        </div>
                    </div>
                </div>
                
                <!-- Ratings & Reviews Section -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-2xl font-bold mb-6">Rating & Ulasan</h2>
                    
                    <div class="flex flex-col md:flex-row mb-10">
                        <div class="md:w-1/3 mb-6 md:mb-0 flex flex-col items-center justify-center">
                            <div class="text-5xl font-bold text-yellow-500 mb-2"><?php echo $course['rating']; ?></div>
                            <div class="rating-stars mb-2">★★★★★</div>
                            <div class="text-gray-600"><?php echo $course['reviews']; ?> ratings</div>
                        </div>
                        
                        <div class="md:w-2/3">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                            <div class="flex items-center mb-2">
                                <div class="w-12 text-right mr-3"><?php echo $i; ?> star</div>
                                <div class="flex-1 h-4 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="bg-yellow-500 h-full" style="width: <?php echo $ratingPercentages[$i]; ?>%"></div>
                                </div>
                                <div class="w-16 text-right ml-3"><?php echo $ratingDistribution[$i]; ?></div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <!-- Reviews List -->
                    <h3 class="text-xl font-semibold mb-6">Reviews</h3>
                    
                    <?php foreach ($course['reviews_list'] as $review): ?>
                    <div class="border-b border-gray-200 pb-6 mb-6 last:border-b-0 last:pb-0 last:mb-0">
                        <div class="flex items-center mb-3">
                            <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                <?php echo substr($review['name'], 0, 1); ?>
                            </div>
                            <div>
                                <h4 class="font-medium"><?php echo $review['name']; ?></h4>
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
                                    <span class="text-gray-500 text-sm"><?php echo $review['date']; ?></span>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-700"><?php echo $review['comment']; ?></p>
                    </div>
                    <?php endforeach; ?>
                    
                    <!-- Leave a Review Form -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4">Leave a Review</h3>
                        <form action="#" method="post" class="space-y-4">
                            <div>
                                <label for="rating" class="block text-gray-700 mb-2">Your Rating</label>
                                <div class="flex text-2xl text-gray-400">
                                    <button type="button" class="mr-1 hover:text-yellow-500">☆</button>
                                    <button type="button" class="mr-1 hover:text-yellow-500">☆</button>
                                    <button type="button" class="mr-1 hover:text-yellow-500">☆</button>
                                    <button type="button" class="mr-1 hover:text-yellow-500">☆</button>
                                    <button type="button" class="hover:text-yellow-500">☆</button>
                                </div>
                            </div>
                            <div>
                                <label for="comment" class="block text-gray-700 mb-2">Your Review</label>
                                <textarea id="comment" name="comment" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md font-medium hover:bg-blue-700 transition">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="md:w-4/12 mt-8 md:mt-0">
                <!-- Call to Action Card -->
                <div class="bg-white p-6 rounded-lg shadow-sm sticky top-6">
                    <h3 class="text-xl font-bold mb-4">Beli sekarang</h3>
                    <div class="flex items-center mb-6">
                        <span class="font-bold text-3xl"><?php echo $course['price']; ?></span>
                        <span class="text-gray-500 line-through ml-2"><?php echo $course['original_price']; ?></span>
                    </div>
                    <button class="w-full bg-blue-600 text-white py-3 px-4 rounded-md font-medium hover:bg-blue-700 transition mb-4">Tambah ke keranjang</button>
                    <button class="w-full border border-gray-300 text-gray-800 py-3 px-4 rounded-md font-medium hover:bg-gray-50 transition">Beli sekarang</button>
                    
                    <div class="mt-6 text-center">
                        <p class="text-gray-500 text-sm mb-1">30-day money-back guarantee</p>
                        <p class="text-gray-500 text-sm">Full lifetime access</p>
                    </div>
                    
                    <div class="border-t border-gray-200 my-6 pt-6">
                        <h4 class="font-semibold mb-4">Share this course</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-500 hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-gray-500 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-gray-500 hover:text-red-600"><i class="fab fa-pinterest"></i></a>
                            <a href="#" class="text-gray-500 hover:text-blue-700"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Courses Section -->
    <section class="py-12 bg-gray-50">
    <div class="container mx-auto px-6">
        <h2 class="text-2xl font-bold mb-10">You might also like</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php 
            $count = 0;
            foreach ($featuredCourses as $relatedCourse): 
                if ($relatedCourse['id'] != $course_id && $count < 4): 
                    $count++;
            ?>
            <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                <div class="relative">
                    <img src="<?php echo $relatedCourse['image']; ?>" alt="<?php echo $relatedCourse['title']; ?>" class="w-full h-48 object-cover">
                    <?php if (isset($relatedCourse['tag'])): ?>
                    <span class="absolute top-3 left-3 bg-blue-600 text-white text-xs px-2 py-1 rounded"><?php echo $relatedCourse['tag']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="p-5">
                    <h3 class="font-semibold text-lg mb-2">
                        <a href="course.php?id=<?php echo $relatedCourse['id']; ?>" class="text-gray-900 hover:text-blue-600"><?php echo $relatedCourse['title']; ?></a>
                    </h3>
                    
                    <p class="text-gray-500 text-sm mb-3">By <?php echo $relatedCourse['instructor']; ?></p>
                    
                    <div class="flex items-center mb-3">
                        <div class="rating-stars mr-2">★★★★★</div>
                        <span class="text-gray-700"><?php echo $relatedCourse['rating']; ?></span>
                        <span class="text-gray-500 text-xs ml-1">(<?php echo $relatedCourse['reviews']; ?>)</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-bold text-gray-900"><?php echo $relatedCourse['price']; ?></span>
                            <span class="text-gray-500 text-sm line-through ml-1"><?php echo $relatedCourse['original_price']; ?></span>
                        </div>
                        <a href="course.php?id=<?php echo $relatedCourse['id']; ?>" class="text-blue-600 hover:text-blue-700 font-medium text-sm">View Course</a>
                    </div>
                </div>
            </div>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>
    </div>
</section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="index.php" class="text-blue-400 font-bold text-2xl mb-4 block">upskill</a>
                    <p class="text-gray-400 mb-4">Transform your life through education with our online learning platform. Learn from industry experts and advance your career.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-4">Explore</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Our Courses</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Instructors</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Career</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Become an Instructor</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms & Conditions</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Support</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-4">Subscribe</h3>
                    <p class="text-gray-400 mb-4">Subscribe to our newsletter to receive the latest updates and offers.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your email" class="px-4 py-2 w-full rounded-l-md focus:outline-none">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700 transition">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">© 2025 Upskill. All rights reserved.</p>
                <div class="mt-4 md:mt-0">
                    <img src="assets/images/payment-methods.png" alt="Payment Methods" class="h-8">
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Rating stars functionality
        const ratingButtons = document.querySelectorAll('.flex.text-2xl.text-gray-400 button');
        ratingButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                ratingButtons.forEach((btn, i) => {
                    btn.innerHTML = i <= index ? '★' : '☆';
                    btn.classList.toggle('text-yellow-500', i <= index);
                    btn.classList.toggle('text-gray-400', i > index);
                });
                // Set hidden input value for form submission
                document.getElementById('rating-value').value = index + 1;
            });
        });
    </script>
</body>
</html>