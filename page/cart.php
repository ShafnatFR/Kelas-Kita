<?php
// cart.php - Halaman keranjang belanja
include "db.php";
// Session start untuk menyimpan data keranjang
session_start();

// Inisialisasi cart jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah ke cart dari link ?action=add&id=...
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $course_id = $_GET['id'];
    
    // Load data kursus
    if (file_exists('tbkelas.php')) {
        include_once('tbkelas.php'); // file ini harus berisi $allCourses
        
        foreach ($allCourses as $course) {
            if ($course['id'] == $course_id) {
                $_SESSION['cart'][$course_id] = [
                    'id' => $course['id'],
                    'title' => $course['title'],
                    'instructor' => $course['instructor'],
                    'price' => $course['price'],
                    'image' => $course['image'] ?? '',
                ];
                break;
            }
        }
    } else {
        // Fallback jika file tidak ada
        $_SESSION['error_message'] = "Error: tbkelas.php tidak ditemukan";
    }

    header('Location: cart.php');
    exit;
}

// Hapus dari cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $course_id = $_GET['id'];
    if (isset($_SESSION['cart'][$course_id])) {
        unset($_SESSION['cart'][$course_id]);
    }
    
    // Redirect kembali ke halaman keranjang
    header('Location: cart.php');
    exit;
}

// Handler untuk menyimpan kursus untuk nanti
if (isset($_GET['action']) && $_GET['action'] == 'save_for_later' && isset($_GET['id'])) {
    $course_id = $_GET['id'];
    
    if (!isset($_SESSION['saved_for_later'])) {
        $_SESSION['saved_for_later'] = [];
    }
    
    if (isset($_SESSION['cart'][$course_id])) {
        $_SESSION['saved_for_later'][$course_id] = $_SESSION['cart'][$course_id];
        unset($_SESSION['cart'][$course_id]);
    }
    
    // Redirect kembali ke halaman keranjang
    header('Location: cart.php');
    exit;
}

// Handler untuk memindahkan ke wishlist
if (isset($_GET['action']) && $_GET['action'] == 'move_to_wishlist' && isset($_GET['id'])) {
    $course_id = $_GET['id'];
    
    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }
    
    if (isset($_SESSION['cart'][$course_id])) {
        $_SESSION['wishlist'][$course_id] = $_SESSION['cart'][$course_id];
        unset($_SESSION['cart'][$course_id]);
    }
    
    // Redirect kembali ke halaman keranjang
    header('Location: cart.php');
    exit;
}

// Handler untuk memindahkan dari saved_for_later ke cart
if (isset($_GET['action']) && $_GET['action'] == 'move_to_cart' && isset($_GET['id'])) {
    $course_id = $_GET['id'];

    if (!isset($_SESSION['saved_for_later'])) {
        $_SESSION['saved_for_later'] = [];
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['saved_for_later'][$course_id])) {
        $_SESSION['cart'][$course_id] = $_SESSION['saved_for_later'][$course_id];
        unset($_SESSION['saved_for_later'][$course_id]);
    }

    // Redirect kembali ke halaman keranjang
    header('Location: cart.php');
    exit;
}

// Hitung total belanja
$total = 0;
$discounted_total = 0;
$applied_coupon = null;
$discount_amount = 0;

// Handler untuk promo code
$promo_message = '';
$promo_code = '';

// List kode promo yang valid
$valid_promo_codes = [
    'KEEPLEARNING' => 15, // 15% discount
    'WELCOME10' => 10,    // 10% discount
    'FLASH25' => 25,      // 25% discount
    'NEWYEAR20' => 20     // 20% discount
];

// Handle apply promo code
if (isset($_POST['apply_promo']) && isset($_POST['promo_code'])) {
    $promo_code = strtoupper(trim($_POST['promo_code']));

    if (isset($valid_promo_codes[$promo_code])) {
        $applied_coupon = [
            'code' => $promo_code,
            'discount' => $valid_promo_codes[$promo_code]
        ];
        $_SESSION['applied_coupon'] = $applied_coupon;
        $promo_message = "Coupon applied! {$applied_coupon['discount']}% discount.";
    } else {
        $promo_message = "Invalid promo code. Please try again.";
    }
}

// Check for existing applied coupon
if (isset($_SESSION['applied_coupon'])) {
    $applied_coupon = $_SESSION['applied_coupon'];
}

// Calculate cart total
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['price']) && !empty($item['price'])) {
            // Remove currency symbol and convert to numeric
            $price = str_replace(['$', 'Rp', ','], '', $item['price']);
            $total += (float)$price;
        }
    }
}

// Calculate discount if coupon applied
if ($applied_coupon) {
    $discount_amount = $total * ($applied_coupon['discount'] / 100);
    $discounted_total = $total - $discount_amount;
} else {
    $discounted_total = $total;
}

// Format numbers for display
$total_formatted = number_format($total, 0, ',', '.');
$discounted_total_formatted = number_format($discounted_total, 0, ',', '.');
$discount_amount_formatted = number_format($discount_amount, 0, ',', '.');

// Remove existing coupon
if (isset($_GET['action']) && $_GET['action'] == 'remove_coupon') {
    unset($_SESSION['applied_coupon']);
    $applied_coupon = null;
    $discounted_total = $total;
    $discount_amount = 0;
    $discounted_total_formatted = $total_formatted;
    $discount_amount_formatted = '0';
    
    // Redirect to clean URL
    header('Location: cart.php');
    exit;
}

// Tampilkan halaman keranjang belanja
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja | Kelas Kita - </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<pre><?php print_r($_SESSION['cart']); ?></pre>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white py-4 px-6 shadow-sm">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="text-blue-600 font-bold text-2xl">Kelas Kita</a>
                <div class="hidden md:flex ml-10 space-x-6">
                    <a href="index.php" class="text-gray-500 hover:text-gray-900">Beranda</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Kursus</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Kategori</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Blog</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Kontak</a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#" class="hidden md:inline-block text-gray-600 hover:text-gray-900 px-4 py-2">Log in</a>
                <a href="#" class="bg-blue-600 text-white px-6 py-2 rounded-md font-medium hover:bg-blue-700 transition">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Cart Section -->
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-10">Keranjang Belanja</h1>
        
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <h2 class="text-xl font-semibold mb-4"><?php echo count($_SESSION['cart']); ?> Kursus di Keranjang</h2>
                    <?php if (empty($_SESSION['cart'])) { ?>
    <div class="py-8 text-center">
        <p class="text-gray-500 mb-4">Keranjang Anda Kosong</p>
        <a href="index.php" class="text-blue-600 font-medium hover:text-blue-800">Telusuri Kursus</a>
    </div>
<?php } else { ?>
    <?php foreach ($_SESSION['cart'] as $course_id => $item) { ?>
        <div class="flex flex-col md:flex-row border-b border-gray-200 py-6 last:border-b-0 last:pb-0">
            <div class="md:w-1/4 mb-4 md:mb-0">
                <img src="<?php echo isset($item['image']) ? $item['image'] : ''; ?>" alt="<?php echo isset($item['title']) ? $item['title'] : ''; ?>" class="w-full rounded-md">
            </div>
            <div class="md:w-3/4 md:pl-6">
                <h3 class="text-lg font-semibold mb-2"><?php echo isset($item['title']) ? $item['title'] : 'No Title'; ?></h3>
                <p class="text-gray-600 mb-2">By <?php echo isset($item['instructor']) ? $item['instructor'] : 'Unknown'; ?></p>
                <p class="text-gray-700 mb-2"><?php echo isset($item['description']) ? $item['description'] : ''; ?></p>

                <div class="flex items-center mb-4">
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">Bestseller</span>
                    <div class="flex items-center text-amber-500 ml-3">
                        <span class="text-sm">4.7</span>
                        <div class="mx-1">★★★★★</div>
                        <span class="text-xs text-gray-500">(366,616 ratings)</span>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4 border-b pb-4">
                    <div>
                        <div class="mt-2 space-x-3">
                            <a href="cart.php?action=remove&id=<?= $course_id ?>" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</a>
                            <a href="cart.php?action=save_for_later&id=<?= $course_id ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Save for Later</a>
                            <a href="cart.php?action=move_to_wishlist&id=<?= $course_id ?>" class="text-purple-600 hover:text-purple-800 text-sm font-medium">Move to Wishlist</a>
                        </div>
                    </div>
                    <div class="font-bold">
                        Rp<?php echo number_format(floatval(str_replace(['Rp', ',', '$'], '', $item['price'])), 0, ',', '.'); ?>
                        <?php if (!empty($item['original_price'])) { ?>
                            <span class="text-gray-500 text-sm line-through ml-1">
                                Rp<?php echo number_format(floatval(str_replace(['Rp', ',', '$'], '', $item['original_price'])), 0, ',', '.'); ?>
                            </span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
             
                <!-- Saved For Later (Optional Section) -->
                <?php if (!empty($_SESSION['saved_for_later'])): ?>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">Saved for later (<?php echo count($_SESSION['saved_for_later']); ?>)</h2>
        
        <?php foreach ($_SESSION['saved_for_later'] as $item): ?>
            <div class="flex flex-col md:flex-row border-b border-gray-200 py-6 last:border-b-0 last:pb-0">
                <div class="md:w-1/4 mb-4 md:mb-0">
                    <img src="<?php echo isset($item['image']) ? $item['image'] : ''; ?>" alt="<?php echo isset($item['title']) ? $item['title'] : ''; ?>" class="w-full rounded-md">
                </div>
                <div class="md:w-3/4 md:pl-6">
                    <h3 class="text-lg font-semibold mb-2"><?php echo isset($item['title']) ? $item['title'] : 'No Title'; ?></h3>
                    <p class="text-gray-600 mb-2">By <?php echo isset($item['instructor']) ? $item['instructor'] : 'Unknown'; ?></p>
                    <p class="text-gray-700 mb-2"><?php echo isset($item['description']) ? $item['description'] : ''; ?></p>
                    
                    <div class="flex items-center mb-4">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">Bestseller</span>
                        <div class="flex items-center text-amber-500 ml-3">
                            <span class="text-sm">4.7</span>
                            <div class="mx-1">★★★★★</div>
                            <span class="text-xs text-gray-500">(366,616 ratings)</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-4">
                        <div class="space-x-2">
                            <a href="cart.php?action=remove&id=<?php echo isset($item['id']) ? $item['id'] : ''; ?>" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</a>
                            <a href="cart.php?action=move_to_cart&id=<?php echo isset($item['id']) ? $item['id'] : ''; ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Move to Cart</a>
                        </div>
                        <div class="font-bold">
                            Rp<?php echo isset($item['price']) ? number_format(floatval(str_replace(['$', 'Rp', ','], '', $item['price'])), 0, ',', '.') : '0'; ?>
                            <?php if (isset($item['original_price']) && $item['original_price']): ?>
                                <span class="text-gray-500 text-sm line-through ml-1">
                                    Rp<?php echo number_format(floatval(str_replace(['$', 'Rp', ','], '', $item['original_price'])), 0, ',', '.'); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>
            
            <!-- Order Summary -->
            <?php if ($applied_coupon): ?>
    <div class="flex justify-between mb-2 text-green-600">
        <span>Diskon (<?= htmlspecialchars($applied_coupon['code']) ?>):</span>
        <span>- Rp<?= $discount_amount_formatted; ?></span>
    </div>
<?php endif; ?>

<div class="flex justify-between font-bold text-lg mb-4">
    <span>Total Akhir:</span>
    <span>Rp<?= $discounted_total_formatted; ?></span>
</div>

<?php if ($promo_message): ?>
    <div class="mb-4 text-sm <?= $applied_coupon ? 'text-green-600' : 'text-red-600' ?>">
        <?= $promo_message ?>
    </div>
<?php endif; ?>

<?php if (!$applied_coupon): ?>
<form method="POST" class="flex items-center space-x-2 mb-4">
    <input type="text" name="promo_code" placeholder="Kode Promo" class="border px-3 py-2 rounded w-full">
    <button type="submit" name="apply_promo" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Terapkan</button>
</form>
<?php else: ?>
    <a href="cart.php?action=remove_coupon" class="text-red-600 text-sm hover:underline">Hapus Kupon</a>
<?php endif; ?>

<a href="checkout.php" class="block w-full text-center mt-6 bg-green-600 text-white py-3 rounded hover:bg-green-700">Lanjutkan Pembayaran</a>

            <div class="w-full lg:w-1/3">
                <div class="bg-white p-6 rounded-lg shadow-sm sticky top-6">
                    <h2 class="text-xl font-semibold mb-4">Total:</h2>
                    
                    <div class="mb-6">
                        <div class="flex justify-between mb-2">
                            <span>Harga Asli:</span>
                            <span>Rp<?php echo $total_formatted; ?></span>
                        </div>
                        
                        <?php if ($applied_coupon): ?>
                        <div class="flex justify-between mb-2 text-green-600">
                            <span>Discount (<?php echo $applied_coupon['discount']; ?>% off):</span>
                            <span>-Rp<?php echo $discount_amount_formatted; ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex justify-between font-bold text-2xl mt-4">
                            <span>Total:</span>
                            <span>Rp<?php echo $discounted_total_formatted; ?></span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Anda belum akan dikenai biaya</p>
                    </div>
                    
                    <!-- Promotions Section -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-3">Promosi</h3>
                        
                        <?php if ($applied_coupon): ?>
                        <div class="flex justify-between items-center bg-gray-100 p-3 rounded-md mb-3">
                            <div>
                                <span class="font-medium"><?php echo $applied_coupon['code']; ?></span>
                                <span class="text-sm text-gray-600 block">is applied</span>
                            </div>
                            <a href="cart.php?action=remove_coupon" class="text-gray-600 hover:text-gray-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                        <?php endif; ?>

                        <form action="cart.php" method="post" class="flex gap-2">
                            <input type="text" name="promo_code" placeholder="Enter Coupon" class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="submit" name="apply_promo" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Apply</button>
                        </form>
                        
                        <?php if ($promo_message): ?>
                        <p class="text-sm mt-2 <?php echo strpos($promo_message, 'Invalid') !== false ? 'text-red-600' : 'text-green-600'; ?>">
                            <?php echo $promo_message; ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Checkout Button -->
                    <a href="checkout.php" class="block w-full bg-purple-600 text-white text-center py-3 px-4 rounded-md font-medium hover:bg-purple-700 transition">
                        Lanjutkan ke Pembayaran <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    
                    <!-- Payment Methods -->
                    <div class="mt-6">
                        <p class="text-sm text-gray-500 mb-2">metode pembayaran Aman</p>
                        <div class="flex gap-2">
                            <div class="border border-gray-200 rounded p-2">
                                <i class="fab fa-cc-visa text-blue-700"></i>
                            </div>
                            <div class="border border-gray-200 rounded p-2">
                                <i class="fab fa-cc-mastercard text-red-600"></i>
                            </div>
                            <div class="border border-gray-200 rounded p-2">
                                <i class="fab fa-cc-paypal text-blue-800"></i>
                            </div>
                            <div class="border border-gray-200 rounded p-2">
                                <i class="fab fa-cc-amex text-blue-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <img src="../assets/images/e3c90883212a5d017dbbb0fb0fe67ac0.jpg" alt="Payment Methods" class="h-12">
                </div>
            </div>
        </div>
    </footer>
    </body>
    </html>