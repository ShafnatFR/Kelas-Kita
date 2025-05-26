<?php
// checkout.php - Halaman checkout
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
    // Debugging: log cart content type
    // error_log('Cart content: ' . print_r($_SESSION['cart'], true));
    foreach ($_SESSION['cart'] as $item) {
        if (is_array($item) && isset($item['price']) && is_string($item['price']) && !empty($item['price'])) {
            $price_str = $item['price'];
                if ($price_str !== null) {
                    $price = floatval(preg_replace('/[^\d.]/', '', $price_str));
                    $total += $price * (isset($item['quantity']) ? (int)$item['quantity'] : 1);
                }
        } else {
            // Skip invalid item or log error
            // error_log('Invalid cart item: ' . print_r($item, true));
        }
    }
} else {
    // If cart is not array, clear it to prevent errors
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

if (isset($_POST['complete_payment'])) {
    // Validate form
    $errors = [];
    
    if (empty($_POST['full_name'])) {
        $errors[] = 'Full name is required';
    }
    
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if (empty($_POST['card_number']) || !preg_match('/^[0-9]{16}$/', str_replace(' ', '', $_POST['card_number']))) {
        $errors[] = 'Valid card number is required';
    }
    
    if (empty($_POST['expiry']) || !preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $_POST['expiry'])) {
        $errors[] = 'Valid expiry date is required (MM/YY)';
    }
    
    if (empty($_POST['cvv']) || !preg_match('/^[0-9]{3,4}$/', $_POST['cvv'])) {
        $errors[] = 'Valid CVV is required';
    }
    
    // If no errors, process payment (simulation)
        if (empty($errors)) {
            // In a real application, you would process the payment with a payment gateway here
            // For this simulation, we'll just set payment as successful
            $payment_success = true;
            
            // Clear cart and applied coupon after successful payment
            if ($payment_success) {
                // Save order to database (real implementation)
                $user_id = $_SESSION['id'];
                $order_id = 'ORD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
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
                    $stmt = $conn->prepare("INSERT INTO tb_transaksi (id_kelas, id_user, id_keranjang, bukti_transaksi, tgl_transaksi, status) VALUES (?, ?, ?, '', ?, 'Pending')");
                    $stmt->bind_param("iiis", $id_kelas, $user_id, $id_keranjang, $date);
                    $stmt->execute();
                }
            }

            // Clear tb_keranjang entries for the user
            $stmt = $conn->prepare("DELETE FROM tb_keranjang WHERE id_user = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
                
                // Clear cart and coupon in session
                $_SESSION['last_order'] = [
                    'id' => $order_id,
                    'items' => $_SESSION['cart'],
                    'total' => $discounted_total,
                    'discount' => $discount_amount,
                    'date' => date('Y-m-d H:i:s')
                ];
                
                $_SESSION['cart'] = [];
                unset($_SESSION['applied_coupon']);
                
                // Redirect to thank you page
                header('Location: checkout.php?success=true&order_id=' . $order_id);
                exit;
            }
        } else {
            $payment_error = implode('<br>', $errors);
        }
}

// Check for success parameter (after payment)
// Debugging: log the type and content of $_SESSION['last_order']
if (isset($_SESSION['last_order'])) {
    // error_log('$_SESSION[\'last_order\'] type: ' . gettype($_SESSION['last_order']));
    // error_log('$_SESSION[\'last_order\'] content: ' . print_r($_SESSION['last_order'], true));
}

if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['order_id']) && isset($_SESSION['last_order']) && is_array($_SESSION['last_order'])) {
    $payment_success = true;
    $order_id = $_GET['order_id'];
    $last_order = $_SESSION['last_order'];
} else {
    // If success parameters not set, prevent undefined variable errors
    $order_id = null;
    $last_order = null;
}

// Debugging: uncomment to check last_order and last_order_data content
// var_dump($last_order);
// var_dump($last_order_data);

// Prepare safe last_order data for display
$last_order_data = (is_array($last_order)) ? $last_order : [];
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
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <?php include("../Views/navbarbootstrap.php"); ?>
    <!-- Content Section -->
    <div class="container mx-auto px-4 py-10">
        <?php if ($payment_success && $order_id !== null && $last_order !== null): ?>
            <!-- Order Success Page -->
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-8 rounded-lg shadow-sm text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-check text-3xl text-green-600"></i>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
                    <p class="text-gray-600 mb-6">Thank you for your purchase. Your order has been confirmed.</p>
                    
                    <div class="bg-gray-50 p-4 rounded-md mb-6">
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Order ID:</span>
                            <span><?php echo htmlspecialchars($order_id); ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Date:</span>
                            <span><?php echo (is_array($last_order_data) && isset($last_order_data['date']) && $last_order_data['date'] !== null) ? htmlspecialchars(date('F j, Y', strtotime($last_order_data['date']))) : 'N/A'; ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Amount:</span>
                            <span>Rp<?php echo (is_array($last_order_data) && isset($last_order_data['total']) && $last_order_data['total'] !== null) ? htmlspecialchars(number_format($last_order_data['total'], 0, ',', '.')) : '0'; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Payment Method:</span>
                            <span>Credit Card</span>
                        </div>
                        <a href="index.php" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md font-medium hover:bg-blue-700 transition">Back to Home</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Checkout Form -->
            <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-sm">
                <h1 class="text-2xl font-bold mb-6">Checkout</h1>

                <!-- Display errors -->
                <?php if (!empty($payment_error)): ?>
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $payment_error; ?>
                    </div>
                <?php endif; ?>

                <!-- Order Summary -->
                <div class="mb-6">
                    <h2 class="font-semibold text-lg mb-2">Order Summary</h2>
                    <ul class="mb-2">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <li class="flex justify-between border-b py-2">
                                <span><?php echo isset($item['title']) ? htmlspecialchars($item['title']) : 'Unknown'; ?></span>
                                <span>Rp<?php echo number_format((float)str_replace(['Rp', ','], '', (string)($item['price'] ?? 0)), 0, ',', '.'); ?></span>

                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if ($applied_coupon): ?>
                        <div class="flex justify-between py-1">
                            <span>Discount (<?php echo $applied_coupon['code']; ?>):</span>
                            <span class="text-green-600">- Rp<?php echo $discount_amount_formatted; ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between font-semibold pt-2 border-t mt-2">
                        <span>Total:</span>
                        <span>Rp<?php echo $discounted_total_formatted; ?></span>
                    </div>
                </div>

                <!-- Payment Form -->
                <form method="POST">
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Full Name</label>
                        <input type="text" name="full_name" class="w-full border px-4 py-2 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Email Address</label>
                        <input type="email" name="email" class="w-full border px-4 py-2 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Card Number</label>
                        <input type="text" name="card_number" class="w-full border px-4 py-2 rounded" placeholder="1234 5678 9012 3456" required>
                    </div>
                    <div class="flex space-x-4 mb-4">
                        <div class="w-1/2">
                            <label class="block mb-1 font-medium">Expiry Date (MM/YY)</label>
                            <input type="text" name="expiry" class="w-full border px-4 py-2 rounded" placeholder="MM/YY" required>
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-1 font-medium">CVV</label>
                            <input type="text" name="cvv" class="w-full border px-4 py-2 rounded" required>
                        </div>
                    </div>
                    <button type="submit" name="complete_payment" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition w-full">
                        Complete Payment
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
