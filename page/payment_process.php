<?php
// payment_process.php - Perbaikan alur pembayaran multi-step

session_start();

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

?>