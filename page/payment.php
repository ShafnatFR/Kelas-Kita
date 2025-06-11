<?php
// payment.php - Halaman pembayaran dengan navigasi step yang benar

include 'cart_db_integration.php';
include 'payment_process.php';

// Ambil step dari URL atau session
$requested_step = intval($_GET['step'] ?? getPaymentStep());

// Validasi akses step
if (!canAccessStep($requested_step)) {
    // Redirect ke step yang diizinkan
    $current_step = getPaymentStep();
    header("Location: payment.php?step=" . $current_step);
    exit();
}

// Set step saat ini
setPaymentStep($requested_step);
$current_step = $requested_step;

// Ambil data yang diperlukan
$step_data = getCurrentStepData();
$cart_items = [];

if (isset($_SESSION['user_id'])) {
    $cart_items = getCartItems($_SESSION['user_id'], $conn);
} else {
    $cart_items = $_SESSION['cart'] ?? [];
}

// Hitung total
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Step <?php echo $current_step; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .payment-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            padding: 20px;
        }
        
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            opacity: 0.5;
        }
        
        .step-item.active {
            opacity: 1;
            color: #007bff;
            font-weight: bold;
        }
        
        .step-item.completed {
            opacity: 1;
            color: #28a745;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
        }
        
        .step-item.active .step-number {
            background: #007bff;
            color: white;
        }
        
        .step-item.completed .step-number {
            background: #28a745;
            color: white;
        }
        
        .step-separator {
            margin: 0 15px;
            font-size: 18px;
            opacity: 0.5;
        }
        
        .payment-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .alert-info {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
        <div class="payment-container">
            <!-- Progress Steps -->
            <?php echo generateBreadcrumb(); ?>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        <?php echo $step_data['title']; ?>
                    </h4>
                    <small><?php echo $step_data['description']; ?></small>
                </div>
                
                <div class="card-body">
                    <?php if ($current_step == 1): ?>
                        <!-- STEP 1: Informasi Pesanan -->
                        <div class="row">
                            <div class="col-md-8">
                                <h5>Detail Pesanan</h5>
                                <?php if (!empty($cart_items)): ?>
                                    <?php foreach ($cart_items as $item): ?>
                                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($item['category'] ?? ''); ?></small>
                                            </div>
                                            <strong>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></strong>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="alert alert-warning">Keranjang kosong</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Ringkasan Pesanan</h6>
                                        <div class="d-flex justify-content-between">
                                            <span>Subtotal:</span>
                                            <span>Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <strong>Total:</strong>
                                            <strong>Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-3">
                            <a href="payment.php?step=2" class="btn btn-primary">
                                Lanjut ke Pembayaran
                            </a>
                        </div>
                        
                    <?php elseif ($current_step == 2): ?>
                        <!-- STEP 2: Pembayaran -->
                        <form method="POST" action="payment_process.php">
                            <input type="hidden" name="action" value="confirm_payment">
                            <input type="hidden" name="amount" value="<?php echo $total_amount; ?>">
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>Metode Pembayaran</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Pilih Metode Pembayaran:</label>
                                        <select name="payment_method" class="form-select" required>
                                            <option value="">Pilih metode pembayaran</option>
                                            <option value="bank_transfer">Transfer Bank</option>
                                            <option value="virtual_account">Virtual Account</option>
                                            <option value="e_wallet">E-Wallet</option>
                                        </select>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <h6>Informasi Pembayaran</h6>
                                        <p><strong>Order ID:</strong> ORD-<?php echo date('YmdHis'); ?></p>
                                        <p><strong>Total:</strong> Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></p>
                                    </div>
                                    
                                    <!-- QR Code atau Virtual Account bisa ditampilkan di sini -->
                                    <div class="text-center py-4">
                                        <div class="border p-4 d-inline-block">
                                            <h6>QR Code Pembayaran</h6>
                                            <div style="width: 200px; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                                QR Code
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>Ringkasan Pembayaran</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <span>Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <strong>Total:</strong>
                                                <strong>Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3">
                                <a href="payment.php?step=1" class="btn btn-secondary">
                                    Kembali
                                </a>
                                <!-- PENTING: Tombol ini harus mengarah ke step 3, bukan kembali ke halaman utama -->
                                <button type="submit" class="btn btn-primary">
                                    Konfirmasi Pembayaran
                                </button>
                            </div>
                        </form>
                        
                    <?php elseif ($current_step == 3): ?>
                        <!-- STEP 3: Upload Bukti Pembayaran -->
                        <form method="POST" action="payment_process.php" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="complete_payment">
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>Upload Bukti Pembayaran</h5>
                                    
                                    <div class="alert alert-success">
                                        <h6>Pembayaran Dikonfirmasi</h6>
                                        <p>Silakan upload bukti pembayaran untuk menyelesaikan transaksi.</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="payment_proof" class="form-label">Pilih File Bukti Transfer:</label>
                                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" 
                                               accept="image/*,.pdf" required>
                                        <small class="text-muted">Format: JPG, PNG, PDF. Maksimal 5MB</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Catatan (Opsional):</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                  placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>Detail Transaksi</h6>
                                            <?php if (isset($_SESSION['payment_data'])): ?>
                                                <p><strong>Metode:</strong> <?php echo ucfirst(str_replace('_', ' ', $_SESSION['payment_data']['payment_method'])); ?></p>
                                                <p><strong>Jumlah:</strong> Rp <?php echo number_format($_SESSION['payment_data']['amount'], 0, ',', '.'); ?></p>
                                                <p><strong>Waktu:</strong> <?php echo $_SESSION['payment_data']['confirmation_time']; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3">
                                <a href="payment.php?step=2" class="btn btn-secondary">
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Selesaikan Pembayaran
                                </button>
                            </div>
                        </form>
                        
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>