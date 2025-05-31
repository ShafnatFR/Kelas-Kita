<?php
// checkout.php - Halaman checkout dengan QR Code Payment
include "db.php";
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session start untuk mengakses data keranjang
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: HalamanSignIn.php');
    exit;
}

// Redirect jika keranjang kosong
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Hitung total belanja
$total = 0;
$discounted_total = 0;
$discount_amount = 0;

// Calculate cart total
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (is_array($item) && isset($item['price']) && is_string($item['price']) && !empty($item['price'])) {
            $price_str = $item['price'];
            if ($price_str !== null) {
                $price = floatval(preg_replace('/[^\d.]/', '', $price_str));
                $total += $price * (isset($item['quantity']) ? (int)$item['quantity'] : 1);
            }
        }
    }
} else {
    $_SESSION['cart'] = [];
}

// Check for applied coupon
$applied_coupon = null;
if (isset($_SESSION['applied_coupon'])) {
    $applied_coupon = $_SESSION['applied_coupon'];
    $discount_amount = $total * ($applied_coupon['discount'] / 100);
    $discounted_total = $total - $discount_amount;
} else {
    $discounted_total = $total;
}

// Format numbers for display
$total_formatted = number_format($total, 0, ',', '.');
$discounted_total_formatted = number_format($discounted_total, 0, ',', '.');
$discount_amount_formatted = number_format($discount_amount, 0, ',', '.');

// Handle form submission
$payment_success = false;
$payment_error = '';
$show_qr = false;

if (isset($_POST['proceed_to_payment'])) {
    // Validate form
    $errors = [];
    
    if (empty($_POST['full_name'])) {
        $errors[] = 'Full name is required';
    }
    
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if (empty($_POST['phone'])) {
        $errors[] = 'Phone number is required';
    }
    
    // If no errors, show QR code
    if (empty($errors)) {
        $show_qr = true;
        
        // Generate order ID
        $order_id = 'ORD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        $_SESSION['pending_order'] = [
            'id' => $order_id,
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'total' => $discounted_total,
            'discount' => $discount_amount,
            'items' => $_SESSION['cart'],
            'date' => date('Y-m-d H:i:s')
        ];
    } else {
        $payment_error = implode('<br>', $errors);
    }
}

// Handle payment confirmation
if (isset($_POST['confirm_payment'])) {
    $payment_success = true;
    
    // Save order to database
    $user_id = $_SESSION['id'];
    $pending_order = $_SESSION['pending_order'];
    $date = date('Y-m-d');
    
    // Insert each cart item as a transaction record in tb_transaksi
    foreach ($_SESSION['cart'] as $item) {
        $id_kelas = $item['id'];
        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;

        // Get id_keranjang for this user and kelas
        $stmt_keranjang = $conn->prepare("SELECT id_keranjang FROM tb_keranjang WHERE id_user = ? AND id_kelas = ? LIMIT 1");
        $stmt_keranjang->bind_param("ii", $user_id, $id_kelas);
        $stmt_keranjang->execute();
        $result_keranjang = $stmt_keranjang->get_result();
        $row_keranjang = $result_keranjang->fetch_assoc();
        $id_keranjang = $row_keranjang ? $row_keranjang['id_keranjang'] : 0;

        // For each quantity, insert a transaction record
        for ($i = 0; $i < $quantity; $i++) {
            $stmt = $conn->prepare("INSERT INTO tb_transaksi (id_kelas, id_user, id_keranjang, bukti_transaksi, tgl_transaksi, status) VALUES (?, ?, ?, 'QR_PAYMENT', ?, 'Completed')");
            $stmt->bind_param("iiis", $id_kelas, $user_id, $id_keranjang, $date);
            $stmt->execute();
        }
    }

    // Clear tb_keranjang entries for the user
    $stmt = $conn->prepare("DELETE FROM tb_keranjang WHERE id_user = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    // Store order details for confirmation
    $_SESSION['last_order'] = $_SESSION['pending_order'];
    
    // Clear cart and coupon
    $_SESSION['cart'] = [];
    unset($_SESSION['applied_coupon']);
    unset($_SESSION['pending_order']);
}

// Check for success parameter
if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_SESSION['last_order'])) {
    $payment_success = true;
    $last_order = $_SESSION['last_order'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $payment_success ? 'Order Confirmation' : 'Checkout'; ?> | Upskill - Online Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .step {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            font-weight: 600;
            margin: 0 10px;
            position: relative;
        }
        
        .step.completed {
            background-color: #10b981;
        }
        
        .step.active {
            background-color: #3b82f6;
        }
        
        .step.pending {
            background-color: #d1d5db;
            color: #6b7280;
        }
        
        .step-connector {
            width: 60px;
            height: 2px;
            background-color: #10b981;
        }
        
        .step-connector.pending {
            background-color: #d1d5db;
        }
        
        .card-modern {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .qr-container {
            background: linear-gradient(45deg, #f0f9ff, #e0f2fe);
            border: 2px dashed #0ea5e9;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            margin: 1rem 0;
        }
        
        .success-animation {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .payment-timer {
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <?php include("../Views/navbarbootstrap.php"); ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            
<?php if ($payment_success && isset($last_order) && $last_order): ?>
                <!-- Success Page -->
                <div class="success-animation">
                    <!-- Progress Steps -->
                    <div class="step-indicator mb-8">
                        <div class="step completed">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step completed">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step completed">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-bold text-white mb-2">Your booking is confirmed</h1>
                        <p class="text-blue-100">Thank you for your purchase. Your courses are now available.</p>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Booking Details -->
                        <div class="card-modern p-6">
                            <h2 class="text-xl font-bold mb-4 text-gray-800">Booking details</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></p>
                                    <div class="grid grid-cols-2 gap-4 mt-2 text-sm text-gray-600">
                                        <div>
                                            <p class="font-medium">ORDER ID</p>
                                            <p><?php echo htmlspecialchars($last_order['id'] ?? ''); ?></p>
                                        </div>
                                        <div>
                                            <p class="font-medium">DATE</p>
                                            <p><?php echo !empty($last_order['date']) ? date('D, d M Y', strtotime($last_order['date'])) : ''; ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-t pt-4">
                                    <p class="font-medium text-gray-600 mb-2">YOUR COURSES</p>
                                    <?php if (!empty($last_order['items'])): ?>
                                        <?php foreach ($last_order['items'] as $item): ?>
                                            <p class="text-blue-600 font-medium"><?php echo htmlspecialchars($item['title'] ?? $item['name'] ?? 'Unknown Course'); ?></p>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>No courses found.</p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="border-t pt-4">
                                    <p class="font-medium text-gray-600 mb-2">PHONE</p>
                                    <p><?php echo htmlspecialchars($last_order['phone'] ?? ''); ?></p>
                                    <p class="font-medium text-gray-600 mb-2 mt-3">EMAIL</p>
                                    <p><?php echo htmlspecialchars($last_order['email'] ?? ''); ?></p>
                                </div>
                                
                                <div class="border-t pt-4">
                                    <p class="font-medium text-gray-600 mb-2">BOOKING NUMBER</p>
                                    <p class="font-mono text-blue-600">#<?php echo isset($last_order['id']) ? substr($last_order['id'], -8) : ''; ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="card-modern p-6">
                            <h2 class="text-xl font-bold mb-4 text-gray-800">Order summary</h2>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span>Courses:</span>
                                    <span class="font-semibold">Rp<?php echo number_format($last_order['total'] + $last_order['discount'], 0, ',', '.'); ?></span>
                                </div>
                                
                                <?php if ($last_order['discount'] > 0): ?>
                                <div class="flex justify-between text-green-600">
                                    <span>Discount:</span>
                                    <span>-Rp<?php echo number_format($last_order['discount'], 0, ',', '.'); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="border-t pt-3 flex justify-between text-lg font-bold">
                                    <span>Total Price:</span>
                                    <span class="text-green-600">Rp<?php echo number_format($last_order['total'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                            
                            <button class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg mt-6 transition duration-200">
                                <i class="fas fa-download mr-2"></i>Download Invoice
                            </button>
                            
                            <div class="text-center mt-4">
                                <p class="text-sm text-gray-500">Powered by UpSkill Inc.</p>
                            </div>
                            
                            <div class="mt-6">
                                <a href="index.php" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg text-center transition duration-200">
                                    Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php elseif ($show_qr): ?>
                <!-- QR Code Payment -->
                <div>
                    <!-- Progress Steps -->
                    <div class="step-indicator mb-8">
                        <div class="step completed">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step active">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="step-connector pending"></div>
                        <div class="step pending">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-bold text-white mb-2">Complete Your Payment</h1>
                        <p class="text-blue-100">Scan QR code below to complete your payment</p>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- QR Code Section -->
                        <div class="card-modern p-6">
                            <div class="payment-timer">
                                <i class="fas fa-clock mr-2"></i>Complete payment within 15:00 minutes
                            </div>
                            
                            <div class="qr-container">
                                <div class="w-48 h-48 mx-auto mb-4 bg-white p-4 rounded-lg">
                                    <!-- QR Code placeholder - in real implementation, generate actual QR -->
                                    <svg viewBox="0 0 100 100" class="w-full h-full">
                                        <rect width="100" height="100" fill="white"/>
                                        <!-- QR Code pattern -->
                                        <rect x="0" y="0" width="20" height="20" fill="black"/>
                                        <rect x="25" y="0" width="5" height="5" fill="black"/>
                                        <rect x="35" y="0" width="5" height="5" fill="black"/>
                                        <rect x="45" y="0" width="10" height="10" fill="black"/>
                                        <rect x="80" y="0" width="20" height="20" fill="black"/>
                                        <rect x="0" y="25" width="5" height="5" fill="black"/>
                                        <rect x="10" y="25" width="5" height="5" fill="black"/>
                                        <rect x="25" y="25" width="15" height="5" fill="black"/>
                                        <rect x="45" y="25" width="5" height="15" fill="black"/>
                                        <rect x="55" y="25" width="5" height="5" fill="black"/>
                                        <rect x="65" y="25" width="10" height="10" fill="black"/>
                                        <rect x="80" y="25" width="5" height="5" fill="black"/>
                                        <rect x="90" y="25" width="5" height="5" fill="black"/>
                                        <!-- Add more QR pattern elements -->
                                        <rect x="0" y="80" width="20" height="20" fill="black"/>
                                        <rect x="25" y="80" width="15" height="5" fill="black"/>
                                        <rect x="45" y="80" width="5" height="10" fill="black"/>
                                        <rect x="55" y="80" width="10" height="15" fill="black"/>
                                        <rect x="70" y="80" width="5" height="5" fill="black"/>
                                        <rect x="80" y="80" width="15" height="15" fill="black"/>
                                    </svg>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Scan QR Code</h3>
                                <p class="text-gray-600 mb-4">Use your mobile banking app or e-wallet to scan this QR code</p>
                                
                                <div class="bg-blue-50 p-3 rounded-lg mb-4">
                                    <p class="text-sm text-blue-800">
                                        <strong>Amount:</strong> Rp<?php echo $discounted_total_formatted; ?>
                                    </p>
                                </div>
                                
                                <div class="flex justify-center space-x-4 mb-4">
                                    <img src="https://via.placeholder.com/40x25/1f65ff/ffffff?text=OVO" alt="OVO" class="rounded">
                                    <img src="https://via.placeholder.com/40x25/00aa5b/ffffff?text=DANA" alt="DANA" class="rounded">
                                    <img src="https://via.placeholder.com/40x25/0066cc/ffffff?text=BCA" alt="BCA" class="rounded">
                                    <img src="https://via.placeholder.com/40x25/e31e24/ffffff?text=CIMB" alt="CIMB" class="rounded">
                                </div>
                            </div>
                            
                            <form method="POST" class="mt-4">
                                <button type="submit" name="confirm_payment" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                                    <i class="fas fa-check mr-2"></i>I Have Completed the Payment
                                </button>
                            </form>
                            
                            <p class="text-xs text-gray-500 text-center mt-3">
                                Click the button above after you have successfully made the payment
                            </p>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="card-modern p-6">
                            <h2 class="text-xl font-bold mb-4 text-gray-800">Order Summary</h2>
                            
                            <div class="space-y-3">
<?php foreach ($_SESSION['cart'] as $item): ?>
    <div class="flex justify-between border-b pb-2">
        <span class="text-gray-700"><?php echo htmlspecialchars($item['title'] ?? $item['name'] ?? 'Unknown Course'); ?></span>
        <span class="font-semibold">Rp<?php echo number_format((float)str_replace(['Rp', ','], '', (string)($item['price'] ?? 0)), 0, ',', '.'); ?></span>
    </div>
<?php endforeach; ?>
                                
                                <?php if ($applied_coupon): ?>
                                <div class="flex justify-between text-green-600">
                                    <span>Discount (<?php echo $applied_coupon['code']; ?>):</span>
                                    <span>-Rp<?php echo $discount_amount_formatted; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="border-t pt-3 flex justify-between text-lg font-bold">
                                    <span>Total:</span>
                                    <span class="text-green-600">Rp<?php echo $discounted_total_formatted; ?></span>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <h3 class="font-semibold text-gray-800 mb-2">Customer Information</h3>
                                <p class="text-sm text-gray-600">Name: <?php echo htmlspecialchars($_SESSION['pending_order']['full_name']); ?></p>
                                <p class="text-sm text-gray-600">Email: <?php echo htmlspecialchars($_SESSION['pending_order']['email']); ?></p>
                                <p class="text-sm text-gray-600">Phone: <?php echo htmlspecialchars($_SESSION['pending_order']['phone']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php else: ?>
                <!-- Customer Information Form -->
                <div>
                    <!-- Progress Steps -->
                    <div class="step-indicator mb-8">
                        <div class="step active">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="step-connector pending"></div>
                        <div class="step pending">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="step-connector pending"></div>
                        <div class="step pending">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-bold text-white mb-2">Checkout</h1>
                        <p class="text-blue-100">Please fill in your information to proceed</p>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Customer Form -->
                        <div class="card-modern p-6">
                            <h2 class="text-xl font-bold mb-4 text-gray-800">Customer Information</h2>
                            
                            <?php if (!empty($payment_error)): ?>
                                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                                    <?php echo $payment_error; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           placeholder="Enter your full name" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           placeholder="Enter your email" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                    <input type="tel" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           placeholder="Enter your phone number" required>
                                </div>
                                
                                <button type="submit" name="proceed_to_payment" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                                    <i class="fas fa-arrow-right mr-2"></i>Proceed to Payment
                                </button>
                            </form>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="card-modern p-6">
                            <h2 class="text-xl font-bold mb-4 text-gray-800">Order Summary</h2>
                            
                            <div class="space-y-3">
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-700"><?php echo htmlspecialchars($item['title']); ?></span>
                                    <span class="font-semibold">Rp<?php echo number_format((float)str_replace(['Rp', ','], '', (string)($item['price'] ?? 0)), 0, ',', '.'); ?></span>
                                </div>
                                <?php endforeach; ?>
                                
                                <?php if ($applied_coupon): ?>
                                <div class="flex justify-between text-green-600">
                                    <span>Discount (<?php echo $applied_coupon['code']; ?>):</span>
                                    <span>-Rp<?php echo $discount_amount_formatted; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="border-t pt-3 flex justify-between text-lg font-bold">
                                    <span>Total:</span>
                                    <span class="text-green-600">Rp<?php echo $discounted_total_formatted; ?></span>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <h3 class="font-semibold text-blue-800 mb-2">
                                    <i class="fas fa-info-circle mr-2"></i>Payment Methods Available
                                </h3>
                                <div class="flex justify-center space-x-3">
                                    <img src="https://via.placeholder.com/50x30/1f65ff/ffffff?text=OVO" alt="OVO" class="rounded">
                                    <img src="https://via.placeholder.com/50x30/00aa5b/ffffff?text=DANA" alt="DANA" class="rounded">
                                    <img src="https://via.placeholder.com/50x30/0066cc/ffffff?text=BCA" alt="BCA" class="rounded">
                                    <img src="https://via.placeholder.com/50x30/e31e24/ffffff?text=Bank" alt="Bank" class="rounded">
                                </div>
                                <p class="text-xs text-blue-600 text-center mt-2">Secure payment via QR Code</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
<?php if ($show_qr): ?>
    <script>
        // Payment timer countdown
        let timeLeft = 15 * 60; // 15 minutes in seconds
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const timerElement = document.querySelector('.payment-timer');
            
            if (timerElement) {
                timerElement.innerHTML = `<i class="fas fa-clock mr-2"></i>Complete payment within ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} minutes`;
            }
            
            if (timeLeft > 0) {
                timeLeft--;
            } else {
                clearInterval(timerInterval);
                alert('Payment time expired. Please try again.');
                window.location.href = 'cart.php';
            }
        }
        
        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    </script>
<?php endif; ?>