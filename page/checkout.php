<?php
// checkout.php - Versi yang diperbaiki
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurasi Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "KelasKita_baru";

// Buat koneksi database
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Koneksi database gagal: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

session_start();

// Initialize pending_order from session if available
$pending_order = $_SESSION['pending_order'] ?? null;

// Fungsi untuk mendapatkan harga terbaru dari database
function getCurrentPrices($conn, $cart_items) {
    $prices = [];
    $item_ids = array_column($cart_items, 'id');
    
    if (!empty($item_ids)) {
        $placeholders = implode(',', array_fill(0, count($item_ids), '?'));
        $stmt = $conn->prepare("SELECT id_kelas, harga FROM tb_kelas WHERE id_kelas IN ($placeholders)");
        
        if ($stmt) {
            $types = str_repeat('i', count($item_ids));
            $stmt->bind_param($types, ...$item_ids);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $prices[$row['id_kelas']] = $row['harga'];
            }
            $stmt->close();
        }
    }
    
    return $prices;
}

// Hitung total belanja
$current_prices = getCurrentPrices($conn, $_SESSION['cart']);
$total = 0;

foreach ($_SESSION['cart'] as &$item) {
    $price = isset($current_prices[$item['id']]) ? $current_prices[$item['id']] : floatval($item['price'] ?? 0);
    $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
    $total += $price * $quantity;
    $item['price'] = $price; // Update dengan harga terbaru
}

// Hitung diskon jika ada
$discount_amount = 0;
$discounted_total = $total;

if (isset($_SESSION['applied_coupon'])) {
    $coupon = $_SESSION['applied_coupon'];
    $discount_percentage = floatval($coupon['discount'] ?? 0);
    $discount_amount = $total * ($discount_percentage / 100);
    $discounted_total = $total - $discount_amount;
}


// Fungsi untuk mengatur step pembayaran
function setPaymentStep($step) {
    $_SESSION['payment_step'] = $step;
}

function getPaymentStep() {
    return $_SESSION['payment_step'] ?? 1;
}

// Fungsi untuk memvalidasi step yang diizinkan
function validatePaymentStep($current_step) {
    $allowed_steps = [1, 2, 3];
    return in_array($current_step, $allowed_steps);
}

// Handler untuk proses pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'confirm_payment':
            // Validasi data pembayaran step 2
            if (validatePaymentData()) {
                // Simpan data pembayaran ke database/session
                savePaymentData();
                
                // Pindah ke step 3 (bukan kembali ke halaman utama)
                setPaymentStep(3);
                
                // Redirect ke step 3
                header("Location: payment.php?step=3");
                exit();
            } else {
                // Jika validasi gagal, tetap di step 2 dengan pesan error
                $error_message = "Data pembayaran tidak valid. Silakan periksa kembali.";
                setPaymentStep(2);
            }
            break;
            
        case 'complete_payment':
            // Proses finalisasi pembayaran di step 3
            if (finalizePayment()) {
                // Bersihkan session pembayaran
                unset($_SESSION['payment_step']);
                unset($_SESSION['payment_data']);
                
                // Bersihkan cart setelah pembayaran berhasil
                if (isset($_SESSION['user_id'])) {
                    clearCart($_SESSION['user_id'], $conn);
                }
                
                // Redirect ke halaman sukses atau dashboard
                header("Location: payment_success.php");
                exit();
            }
            break;
            
        case 'back_to_step':
            $target_step = intval($_POST['target_step'] ?? 1);
            if (validatePaymentStep($target_step)) {
                setPaymentStep($target_step);
                header("Location: payment.php?step=" . $target_step);
                exit();
            }
            break;
    }
}

function validatePaymentData() {
    // Validasi data yang diperlukan untuk konfirmasi pembayaran
    $required_fields = ['payment_method', 'amount'];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            return false;
        }
    }
    
    // Validasi tambahan sesuai kebutuhan
    $amount = floatval($_POST['amount']);
    if ($amount <= 0) {
        return false;
    }
    
    return true;
}

function savePaymentData() {
    // Simpan data pembayaran ke session untuk step berikutnya
    $_SESSION['payment_data'] = [
        'payment_method' => $_POST['payment_method'],
        'amount' => $_POST['amount'],
        'virtual_account' => $_POST['virtual_account'] ?? '',
        'confirmation_time' => date('Y-m-d H:i:s')
    ];
}

function finalizePayment() {
    global $conn;
    
    if (!isset($_SESSION['payment_data']) || !isset($_SESSION['user_id'])) {
        return false;
    }
    
    $payment_data = $_SESSION['payment_data'];
    $user_id = $_SESSION['user_id'];
    
    try {
        // Mulai transaksi database
        $conn->begin_transaction();
        
        // 1. Simpan data transaksi
        $stmt = $conn->prepare("
            INSERT INTO tb_transaksi (id_user, total_amount, payment_method, virtual_account, status, created_at) 
            VALUES (?, ?, ?, ?, 'pending', NOW())
        ");
        
        $stmt->bind_param("idss", 
            $user_id, 
            $payment_data['amount'], 
            $payment_data['payment_method'], 
            $payment_data['virtual_account']
        );
        
        $stmt->execute();
        $transaction_id = $conn->insert_id;
        $stmt->close();
        
        // 2. Simpan detail item yang dibeli
        $cart_items = getCartItems($user_id, $conn);
        
        if (!empty($cart_items)) {
            $detail_stmt = $conn->prepare("
                INSERT INTO tb_detail_transaksi (id_transaksi, id_kelas, harga) 
                VALUES (?, ?, ?)
            ");
            
            foreach ($cart_items as $item) {
                $detail_stmt->bind_param("iid", $transaction_id, $item['id'], $item['price']);
                $detail_stmt->execute();
            }
            $detail_stmt->close();
        }
        
        // 3. Commit transaksi
        $conn->commit();
        
        return true;
        
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $conn->rollback();
        error_log("Payment finalization error: " . $e->getMessage());
        return false;
    }
}

// Fungsi untuk mendapatkan data step saat ini
function getCurrentStepData() {
    $step = getPaymentStep();
    
    switch($step) {
        case 1:
            return [
                'title' => 'Informasi Pesanan',
                'description' => 'Periksa detail pesanan Anda'
            ];
        case 2:
            return [
                'title' => 'Pembayaran',
                'description' => 'Pilih metode pembayaran dan konfirmasi'
            ];
        case 3:
            return [
                'title' => 'Konfirmasi',
                'description' => 'Upload bukti pembayaran dan selesaikan transaksi'
            ];
        default:
            return [
                'title' => 'Informasi Pesanan',
                'description' => 'Periksa detail pesanan Anda'
            ];
    }
}

// Fungsi untuk mengecek apakah step dapat diakses
function canAccessStep($target_step) {
    $current_step = getPaymentStep();
    
    // Step 1 selalu dapat diakses
    if ($target_step == 1) return true;
    
    // Step 2 dapat diakses jika sudah melewati step 1
    if ($target_step == 2 && $current_step >= 1) return true;
    
    // Step 3 dapat diakses jika sudah melewati step 2
    if ($target_step == 3 && $current_step >= 2) return true;
    
    return false;
}

// Fungsi untuk generate breadcrumb
function generateBreadcrumb() {
    $current_step = getPaymentStep();
    $steps = [
        1 => 'Informasi Pesanan',
        2 => 'Pembayaran', 
        3 => 'Konfirmasi'
    ];
    
    $breadcrumb = '<div class="payment-steps">';
    
    foreach ($steps as $step_num => $step_name) {
        $active_class = ($step_num == $current_step) ? 'active' : '';
        $completed_class = ($step_num < $current_step) ? 'completed' : '';
        
        $breadcrumb .= '<div class="step-item ' . $active_class . ' ' . $completed_class . '">';
        $breadcrumb .= '<span class="step-number">' . $step_num . '</span>';
        $breadcrumb .= '<span class="step-name">' . $step_name . '</span>';
        $breadcrumb .= '</div>';
        
        if ($step_num < count($steps)) {
            $breadcrumb .= '<div class="step-separator">→</div>';
        }
    }
    
    $breadcrumb .= '</div>';
    
    return $breadcrumb;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | KelasKita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container-custom {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .card-modern {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .payment-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            gap: 15px;
        }
        
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-weight: bold;
            color: #6c757d;
        }
        
        .step-item.active {
            color: #007bff;
        }
        
        .step-item.completed {
            color: #28a745;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: currentColor;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }
        
        .step-separator {
            font-size: 1.5rem;
            color: #6c757d;
        }
        
        .qr-container {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border: 2px dashed #007bff;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-code {
            width: 200px;
            height: 200px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #6c757d;
            font-size: 14px;
        }
        
        .virtual-account {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .virtual-account .number {
            font-size: 18px;
            font-weight: bold;
            font-family: monospace;
            color: #1976d2;
            letter-spacing: 2px;
        }
        
        .confirmation-box {
            text-align: center;
            padding: 40px 20px;
        }
        
        .confirmation-box .icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
        }
        
        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        
        .item-list {
            max-height: 200px;
            overflow-y: auto;
        }
        
        .payment-methods {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .method-step {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }
        
        .method-step:before {
            content: counter(step-counter);
            counter-increment: step-counter;
            position: absolute;
            left: 0;
            top: 0;
            background: #007bff;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        .method-steps {
            counter-reset: step-counter;
        }

        /* New styles for checkout buttons */
        .checkout-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .checkout-buttons button,
        .checkout-buttons a.btn {
            flex: 1 1 48%;
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
            min-width: 110px;
            max-width: 180px;
            border-radius: 4px;
        }

        @media (max-width: 576px) {
            .checkout-buttons {
                flex-direction: column;
                gap: 10px;
            }
            .checkout-buttons button,
            .checkout-buttons a.btn {
                flex: 1 1 100%;
                min-width: unset;
                max-width: none;
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }
        }
        
        /* Center form inputs and container */
        .form-container {
            display: flex;
            justify-content: center;
        }
        
        .form-content {
            width: 100%;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <?php include("../Views/navbarbootstrap.php"); ?>
    
    <div class="container-custom">
        <!-- Breadcrumb -->
        <?php echo generateBreadcrumb(); ?>
        
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php $current_step = getPaymentStep(); ?>
        
        <?php if ($current_step === 3): ?>
            <!-- Halaman Konfirmasi -->
            <div class="card-modern">
                <div class="confirmation-box">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="text-success mb-3">Pembayaran Berhasil Diunggah!</h2>
                    <p class="lead mb-4">Terima kasih! Bukti pembayaran Anda telah berhasil diunggah.</p>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <strong>Order ID:</strong> <?php echo htmlspecialchars($payment_data['order_id'] ?? ''); ?><br>
                                <strong>Status:</strong> <span class="badge bg-warning">Menunggu Verifikasi</span><br>
                                <strong>Total:</strong> Rp <?php echo number_format($payment_data['total_bayar'] ?? 0, 0, ',', '.'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-muted mb-4">
                        Pembayaran Anda akan diverifikasi dalam 1x24 jam kerja. 
                        Kami akan mengirimkan konfirmasi melalui email.
                    </p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="back_to_step">
                            <input type="hidden" name="target_step" value="2">
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Pembayaran
                            </button>
                        </form>
                        <a href="index.php" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                        <a href="my-orders.php" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>Lihat Pesanan
                        </a>
                    </div>
                </div>
            </div>
            
        <?php elseif ($current_step === 2): ?>
            <!-- Halaman Pembayaran -->
            <div class="card-modern">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Pembayaran</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Info Order -->
                            <?php if ($pending_order): ?>
                            <div class="alert alert-info mb-4">
                                <h6><i class="fas fa-info-circle me-2"></i>Informasi Pesanan</h6>
                                <p class="mb-1"><strong>Order ID:</strong> <?php echo htmlspecialchars($pending_order['order_id']); ?></p>
                                <p class="mb-1"><strong>Nama:</strong> <?php echo htmlspecialchars($pending_order['full_name']); ?></p>
                                <p class="mb-0"><strong>Total:</strong> Rp <?php echo number_format($pending_order['total'], 0, ',', '.'); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="qr-container">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-qrcode me-2"></i>Scan QR Code untuk Pembayaran
                                </h5>
                                
                                <div class="qr-code">
                                    <div>
                                        <i class="fas fa-qrcode fa-3x mb-2"></i><br>
                                        QR Code Pembayaran
                                    </div>
                                </div>
                                
                                <div class="virtual-account">
                                    <div><strong>Virtual Account:</strong></div>
                                    <div class="number">1234567890</div>
                                    <div class="mt-2">
                                        <strong>Jumlah:</strong> 
                                        <span class="text-success">Rp <?php echo number_format($pending_order ? $pending_order['total'] : $discounted_total, 0, ',', '.'); ?></span>
                                    </div>
                                </div>
                                
                                <button class="btn btn-outline-primary btn-sm" onclick="copyVA()">
                                    <i class="fas fa-copy me-1"></i>Copy Virtual Account
                                </button>
                            </div>
                            
                            <div class="payment-methods">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Cara Pembayaran:
                                </h6>
                                <div class="method-steps">
                                    <div class="method-step">Buka aplikasi mobile banking atau e-wallet</div>
                                    <div class="method-step">Pilih menu "Scan QR" atau "QRIS"</div>
                                    <div class="method-step">Scan QR code atau masukkan Virtual Account</div>
                                    <div class="method-step">Masukkan nominal pembayaran</div>
                                    <div class="method-step">Konfirmasi pembayaran</div>
                                    <div class="method-step">Upload bukti transfer di bawah ini</div>
                                </div>
                            </div>
                            
                            <!-- Form Upload Bukti -->
                            <form method="POST" enctype="multipart/form-data" class="mt-4">
                                <input type="hidden" name="action" value="complete_payment">
                                <div class="card border-success">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-success">
                                            <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="proof_image" class="form-label">
                                                Pilih File Bukti Transfer <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control" id="proof_image" name="proof_image" 
                                                   accept="image/jpeg,image/jpg,image/png" required>
                                            <div class="form-text">
                                                Format: JPG, PNG. Maksimal: 2MB
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-2"></i>Konfirmasi Pembayaran
                                            </button>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="back_to_step">
                                                <input type="hidden" name="target_step" value="1">
                                                <button type="submit" class="btn btn-outline-secondary">
                                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Order Summary -->
                            <div class="order-summary">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-receipt me-2"></i>Ringkasan Pesanan
                                </h6>
                                
                                <div class="item-list mb-3">
                                    <?php foreach ($_SESSION['cart'] as $item): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <small class="text-muted">Qty: <?php echo isset($item['quantity']) ? $item['quantity'] : 1; ?></small>
                                        </div>
                                        <div class="text-end">
                                            <strong>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></strong>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                                    </div>
                                    
                                    <?php if ($discount_amount > 0): ?>
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Diskon:</span>
                                        <span>-Rp <?php echo number_format($discount_amount, 0, ',', '.'); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <hr>
                                    <div class="d-flex justify-content-between fs-5 fw-bold text-primary">
                                        <span>Total:</span>
                                        <span>Rp <?php echo number_format($pending_order ? $pending_order['total'] : $discounted_total, 0, ',', '.'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php elseif ($current_step === 1): ?>
            <!-- Halaman Input Data Customer -->
            <div class="card-modern">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pembeli</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="confirm_payment">
                        <div class="row">
                        <div class="col-md-8 form-container">
                            <div class="form-content">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? $user_data['username'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? $user_data['email'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        Nomor Telepon <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" 
                                           placeholder="Contoh: 08123456789" required>
                                </div>
                                
                                <!-- Removed optional address field as per user request -->
                                
                                <!-- Removed terms and conditions agreement as per user request -->
                                
                                <div class="checkout-buttons">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-arrow-right me-2"></i>Lanjut ke Pembayaran
                                    </button>
                                    <a href="cart.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
                                    </a>
                                </div>
                            </div>
                        </div>
                            
                            <div class="col-md-4">
                                <!-- Order Summary -->
                                <div class="order-summary">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-receipt me-2"></i>Ringkasan Pesanan
                                    </h6>
                                    
                                    <div class="item-list mb-3">
                                        <?php foreach ($_SESSION['cart'] as $item): ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></div>
                                                <small class="text-muted">Qty: <?php echo isset($item['quantity']) ? $item['quantity'] : 1; ?></small>
                                            </div>
                                            <div class="text-end">
                                                <strong>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></strong>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Coupon Section removed as per user request -->
                                    
                                    <div class="border-top pt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                                        </div>
                                        
                                        <?php if ($discount_amount > 0): ?>
                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Diskon (<?php echo $_SESSION['applied_coupon']['discount']; ?>%):</span>
                                            <span>-Rp <?php echo number_format($discount_amount, 0, ',', '.'); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <hr>
                                        <div class="d-flex justify-content-between fs-5 fw-bold text-primary">
                                            <span>Total:</span>
                                            <span>Rp <?php echo number_format($discounted_total, 0, ',', '.'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Security Info -->
                                <div class="alert alert-info mt-3">
                                    <h6><i class="fas fa-shield-alt me-2"></i>Keamanan Pembayaran</h6>
                                    <small>
                                        <i class="fas fa-check text-success me-1"></i>Pembayaran 100% aman<br>
                                        <i class="fas fa-check text-success me-1"></i>Data terenkripsi SSL<br>
                                        <i class="fas fa-check text-success me-1"></i>Verifikasi otomatis
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk copy Virtual Account
        function copyVA() {
            const vaNumber = '1234567890';
            navigator.clipboard.writeText(vaNumber).then(function() {
                alert('Virtual Account berhasil disalin: ' + vaNumber);
            }).catch(function(err) {
                console.error('Gagal menyalin: ', err);
                // Fallback untuk browser lama
                const textArea = document.createElement('textarea');
                textArea.value = vaNumber;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Virtual Account berhasil disalin: ' + vaNumber);
            });
        }
        
        // Fungsi untuk menerapkan kupon
        function applyCoupon() {
            const couponCode = document.getElementById('coupon_code').value.trim();
            if (!couponCode) {
                alert('Masukkan kode kupon terlebih dahulu.');
                return;
            }
            
            // Kirim AJAX request untuk validasi kupon
            fetch('apply_coupon.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'coupon_code=' + encodeURIComponent(couponCode)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Kode kupon tidak valid.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses kupon.');
            });
        }
        
        // Fungsi untuk menghapus kupon
        function removeCoupon() {
            if (confirm('Hapus kupon yang diterapkan?')) {
                fetch('remove_coupon.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus kupon.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan.');
                });
            }
        }
        
        // Validasi form sebelum submit
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const phone = document.getElementById('phone');
                    if (phone && phone.value) {
                        // Validasi format nomor telepon Indonesia
                        const phonePattern = /^(\+62|62|0)[0-9]{9,13}$/;
                        if (!phonePattern.test(phone.value.replace(/\s+/g, ''))) {
                            e.preventDefault();
                            alert('Format nomor telepon tidak valid. Gunakan format: 08123456789');
                            phone.focus();
                            return false;
                        }
                    }
                });
            }
            
            // Auto-format nomor telepon
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, '');
                    if (value.startsWith('0')) {
                        value = value.substring(1);
                    }
                    if (value.startsWith('62')) {
                        value = value.substring(2);
                    }
                    this.value = '08' + value;
                });
            }
        });
        
        // Countdown timer untuk session timeout (optional)
        let sessionTimeout = 30 * 60; // 30 menit
        function updateTimer() {
            const minutes = Math.floor(sessionTimeout / 60);
            const seconds = sessionTimeout % 60;
            
            if (sessionTimeout <= 0) {
                alert('Sesi telah berakhir. Silakan mulai kembali proses checkout.');
                window.location.href = 'cart.php';
                return;
            }
            
            sessionTimeout--;
            setTimeout(updateTimer, 1000);
        }
        
        // Mulai timer jika di halaman pembayaran
        <?php if ($page_status === 'payment'): ?>
        updateTimer();
        <?php endif; ?>
        
        // Preview gambar yang akan diupload
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('proof_image');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validasi ukuran file
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Ukuran file terlalu besar. Maksimal 2MB.');
                            this.value = '';
                            return;
                        }
                        
                        // Validasi tipe file
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!allowedTypes.includes(file.type)) {
                            alert('Format file tidak didukung. Gunakan JPG atau PNG.');
                            this.value = '';
                            return;
                        }
                        
                        // Preview gambar (optional)
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Bisa ditambahkan preview image di sini
                            console.log('File loaded successfully');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
        
        // Konfirmasi sebelum meninggalkan halaman pembayaran
        <?php if ($page_status === 'payment'): ?>
        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = 'Anda yakin ingin meninggalkan halaman ini? Proses pembayaran akan dibatalkan.';
        });
        <?php endif; ?>
    </script>
</body>
</html>