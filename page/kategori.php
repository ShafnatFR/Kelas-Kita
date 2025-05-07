<?php
include "db.php";
$query = "SELECT * FROM tbkelas";
$result = $conn->query($query);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas Kita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-gradient {
            background: linear-gradient(135deg,rgb(2, 2, 253) 0%, #60efff 100%);
        }
        .rating-stars {
            color: #FFD700;
        }
    </style>
</head>
<body>
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-6">
        <h2 class="text-2xl font-bold mb-10">Kursus Unggulan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php while ($course = $result->fetch_assoc()): ?>
            <a href="course-detail.php?id=<?php echo $course['id']; ?>" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                <div class="relative">
                    <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>" class="w-full h-40 object-cover">
                    <?php if (!empty($course['badge'])): ?>
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
            <?php endwhile; ?>
        </div>
    </div>
</section>
</body>
