<?php
// checkout.php - Perbaikan untuk masalah User ID validation
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "KelasKita_baru";

// Buat koneksi database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk memeriksa dan menyambungkan kembali koneksi MySQL
function checkAndReconnect(&$conn, $servername, $username, $password, $dbname) {
    if (!$conn->ping()) {
        $conn->close();
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Koneksi gagal setelah reconnect: " . $conn->connect_error);
        }
    }
}

// Aktifkan pelaporan kesalahan untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Panggil fungsi reconnect saat startup
checkAndReconnect($conn, $servername, $username, $password, $dbname);

// Mulai sesi untuk mengakses data keranjang dan user
session_start();

// DEBUG: Tampilkan informasi sesi untuk debugging
if (isset($_GET['debug']) && $_GET['debug'] == '1') {
    echo "<pre>";
    echo "=== DEBUG SESSION INFO ===\n";
    echo "Session ID: " . session_id() . "\n";
    echo "User ID dari session: " . ($_SESSION['id'] ?? 'NOT SET') . "\n";
    echo "Username dari session: " . ($_SESSION['username'] ?? 'NOT SET') . "\n";
    echo "Email dari session: " . ($_SESSION['email'] ?? 'NOT SET') . "\n";
    echo "All session data:\n";
    print_r($_SESSION);
    echo "=== END DEBUG ===\n";
    echo "</pre>";
}

// PERBAIKAN 1: Cek berbagai kemungkinan key untuk user ID di session
$user_id = null;
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} elseif (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif (isset($_SESSION['id_user']) && !empty($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];
}

// PERBAIKAN 2: Validasi user ID dengan query ke database
$user_data = null;
if ($user_id) {
    checkAndReconnect($conn, $servername, $username, $password, $dbname);
    
    // Cek apakah user_id ada di database dan ambil data lengkap
    $stmt_check_user = $conn->prepare("SELECT id_user, username, email FROM tb_user WHERE id_user = ?");
    if ($stmt_check_user) {
        $stmt_check_user->bind_param("i", $user_id);
        $stmt_check_user->execute();
        $result_check_user = $stmt_check_user->get_result();
        
        if ($result_check_user->num_rows > 0) {
            $user_data = $result_check_user->fetch_assoc();
            // Update session dengan data terbaru dari database
            $_SESSION['id'] = $user_data['id_user'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['email'] = $user_data['email'];
        } else {
            // User ID tidak ditemukan di database
            $user_id = null;
            $user_data = null;
        }
        $stmt_check_user->close();
    } else {
        error_log("Failed to prepare statement for user validation: " . $conn->error);
        $user_id = null;
    }
}

// PERBAIKAN 3: Redirect dengan pesan error yang lebih informatif
if (!$user_id || !$user_data) {
    // Bersihkan session yang mungkin rusak
    session_unset();
    session_destroy();
    
    // Set pesan error di URL
    header('Location: HalamanSignIn.php?error=session_expired&message=' . urlencode('Sesi Anda telah berakhir. Silakan login kembali.'));
    exit;
}

// Redirect jika keranjang kosong
if (empty($_SESSION['cart'])) {
    header('Location: cart.php?message=' . urlencode('Keranjang Anda kosong.'));
    exit;
}

// PERBAIKAN 4: Validasi struktur data keranjang
$valid_cart = true;
foreach ($_SESSION['cart'] as $item) {
    if (!isset($item['id']) || !isset($item['name'])) {
        $valid_cart = false;
        break;
    }
}

if (!$valid_cart) {
    unset($_SESSION['cart']);
    header('Location: cart.php?error=invalid_cart&message=' . urlencode('Data keranjang tidak valid. Silakan tambahkan item kembali.'));
    exit;
}

// Ambil harga terbaru untuk item di keranjang dari database
$current_prices = [];
$item_ids_in_cart = [];
foreach ($_SESSION['cart'] as $item) {
    $item_ids_in_cart[] = $item['id'];
}

if (!empty($item_ids_in_cart)) {
    $ids_placeholder = implode(',', array_fill(0, count($item_ids_in_cart), '?'));
    $stmt = $conn->prepare("SELECT id_kelas, harga FROM tb_kelas WHERE id_kelas IN ($ids_placeholder)");
    if ($stmt) {
        $types = str_repeat('i', count($item_ids_in_cart));
        $stmt->bind_param($types, ...$item_ids_in_cart);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $current_prices[$row['id_kelas']] = $row['harga'];
        }
        $stmt->close();
    } else {
        error_log("Failed to prepare statement for fetching current prices: " . $conn->error);
    }
}

// Hitung total belanja menggunakan harga terbaru
$total = 0;
foreach ($_SESSION['cart'] as &$item) {
    $original_price = isset($current_prices[$item['id']]) ? $current_prices[$item['id']] : (floatval($item['price'] ?? 0));
    $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
    $total += $original_price * $quantity;
    $item['price'] = $original_price;
}

$discount_amount = 0;
$discounted_total = $total;

// Cek kupon yang diterapkan
$applied_coupon = null;
if (isset($_SESSION['applied_coupon'])) {
    $applied_coupon = $_SESSION['applied_coupon'];
    $discount_percentage = floatval($applied_coupon['discount'] ?? 0);
    $discount_amount = $total * ($discount_percentage / 100);
    $discounted_total = $total - $discount_amount;
}

// Format angka untuk tampilan
$total_formatted = number_format($total, 0, ',', '.');
$discounted_total_formatted = number_format($discounted_total, 0, ',', '.');
$discount_amount_formatted = number_format($discount_amount, 0, ',', '.');

// Variabel status halaman
$payment_processed = false;
$payment_confirmed_pending = false;
$payment_error = '';
$qr_virtual_account_number = "123456789012";
$current_page_status = 'customer_info';

// Cek apakah ada order_id di URL
if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $current_page_status = 'confirmation_pending';
    $order_id_from_url = $_GET['order_id'];
    checkAndReconnect($conn, $servername, $username, $password, $dbname);
    $stmt_fetch_payment = $conn->prepare("SELECT * FROM tb_pembayaran WHERE order_id = ? AND id_user = ?");
    if ($stmt_fetch_payment) {
        $stmt_fetch_payment->bind_param("si", $order_id_from_url, $user_id);
        $stmt_fetch_payment->execute();
        $result_fetch_payment = $stmt_fetch_payment->get_result();
        $last_order = $result_fetch_payment->fetch_assoc();
        $stmt_fetch_payment->close();
        
        if (!$last_order) {
            $payment_error = "Order ID tidak ditemukan atau tidak cocok dengan user.";
            $current_page_status = 'customer_info';
        }
    } else {
        error_log("Failed to prepare statement for fetching payment: " . $conn->error);
        $payment_error = "Terjadi kesalahan sistem saat memproses order Anda.";
        $current_page_status = 'customer_info';
    }
}

// Handle form submission: Proceed to Payment
if (isset($_POST['proceed_to_payment'])) {
    $errors = [];
    if (empty($_POST['full_name'])) $errors[] = 'Nama lengkap harus diisi';
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email yang valid harus diisi';
    if (empty($_POST['phone'])) $errors[] = 'Nomor telepon harus diisi';
    
    if (empty($errors)) {
        $current_page_status = 'qr_payment';
        $order_id = 'ORD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        $_SESSION['pending_order_data'] = [
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
        $current_page_status = 'customer_info';
    }
}

// PERBAIKAN 5: Handle form submission untuk upload bukti transfer dengan validasi yang lebih ketat
if (isset($_POST['upload_proof']) && isset($_SESSION['pending_order_data'])) {
    $pending_order = $_SESSION['pending_order_data'];
    $order_id = $pending_order['id'];
    $total_bayar = $pending_order['total'];
    $metode_bayar = "BCA Virtual Account";

    // PERBAIKAN 6: Validasi ulang user_id sebelum melanjutkan
    checkAndReconnect($conn, $servername, $username, $password, $dbname);
    $stmt_revalidate_user = $conn->prepare("SELECT id_user, username FROM tb_user WHERE id_user = ?");
    if ($stmt_revalidate_user) {
        $stmt_revalidate_user->bind_param("i", $user_id);
        $stmt_revalidate_user->execute();
        $result_revalidate = $stmt_revalidate_user->get_result();
        
        if ($result_revalidate->num_rows === 0) {
            $payment_error = "Sesi login Anda tidak valid. Silakan login kembali.";
            $stmt_revalidate_user->close();
            
            // Redirect ke halaman login dengan pesan
            session_unset();
            session_destroy();
            header('Location: HalamanSignIn.php?error=invalid_session&message=' . urlencode($payment_error));
            exit;
        }
        $stmt_revalidate_user->close();
    } else {
        error_log("Failed to prepare statement for user revalidation: " . $conn->error);
        $payment_error = "Terjadi kesalahan sistem. Silakan coba lagi.";
        $current_page_status = 'qr_payment';
    }

    // Proses upload file jika validasi user berhasil
    if (empty($payment_error)) {
        $bukti_transfer_path = null;
        if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] == UPLOAD_ERR_OK) {
            $file_tmp_name = $_FILES['proof_image']['tmp_name'];
            $file_name = uniqid() . '_' . basename($_FILES['proof_image']['name']);
            $upload_dir = 'uploads/proofs/';
            $bukti_transfer_path = $upload_dir . $file_name;

            // Buat direktori jika belum ada
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Validasi tipe file
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            $file_type = mime_content_type($file_tmp_name);
            if (!in_array($file_type, $allowed_types)) {
                $payment_error = "Format file tidak didukung. Gunakan JPG atau PNG.";
            }
            // Validasi ukuran file (max 2MB)
            elseif ($_FILES['proof_image']['size'] > 2 * 1024 * 1024) {
                $payment_error = "Ukuran file terlalu besar. Maksimal 2MB.";
            }
            // Upload file
            elseif (!move_uploaded_file($file_tmp_name, $bukti_transfer_path)) {
                $payment_error = "Gagal mengunggah bukti transfer.";
                $bukti_transfer_path = null;
            }
        } else {
            $payment_error = "Harap unggah bukti transfer.";
        }

        // Simpan ke database jika upload berhasil
        if ($bukti_transfer_path && empty($payment_error)) {
            checkAndReconnect($conn, $servername, $username, $password, $dbname);
            $stmt_insert_pembayaran = $conn->prepare("INSERT INTO tb_pembayaran (order_id, id_user, total_bayar, metode_bayar, nomor_va, bukti_transfer, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
            if ($stmt_insert_pembayaran) {
                $stmt_insert_pembayaran->bind_param("sidsss", $order_id, $user_id, $total_bayar, $metode_bayar, $qr_virtual_account_number, $bukti_transfer_path);
                if ($stmt_insert_pembayaran->execute()) {
                    // Hapus dari keranjang database
                    $stmt_delete_cart_entry = $conn->prepare("DELETE FROM tb_keranjang WHERE id_user = ? AND id_kelas IN (" . implode(',', array_fill(0, count($pending_order['items']), '?')) . ")");
                    if ($stmt_delete_cart_entry && !empty($pending_order['items'])) {
                        $types = 'i' . str_repeat('i', count($pending_order['items']));
                        $class_ids = array_map(function($item) { return $item['id']; }, $pending_order['items']);
                        $stmt_delete_cart_entry->bind_param($types, $user_id, ...$class_ids);
                        $stmt_delete_cart_entry->execute();
                        $stmt_delete_cart_entry->close();
                    }

                    // Bersihkan session
                    unset($_SESSION['cart']);
                    unset($_SESSION['applied_coupon']);
                    unset($_SESSION['pending_order_data']);

                // Redirect ke konfirmasi
                // After successful payment, redirect to lihat-kelas.php for the first purchased class
                $first_class_id = 0;
                if (!empty($pending_order['items'])) {
                    $first_class_id = $pending_order['items'][0]['id'] ?? 0;
                }
                if ($first_class_id > 0) {
                    header("Location: lihat-kelas.php?id=" . urlencode($first_class_id));
                } else {
                    header("Location: checkout.php?order_id=" . urlencode($order_id));
                }
                exit;

                } else {
                    $payment_error = "Gagal menyimpan data pembayaran: " . $stmt_insert_pembayaran->error;
                }
                $stmt_insert_pembayaran->close();
            } else {
                $payment_error = "Gagal menyiapkan statement untuk pembayaran: " . $conn->error;
            }
        }
    }
    
    if (!empty($payment_error)) {
        $current_page_status = 'qr_payment';
    }
}

// Variabel untuk QR code
$qr_data_string = "AMOUNT:" . $discounted_total . ";VA:" . $qr_virtual_account_number . ";ORDERID:" . ($_SESSION['pending_order_data']['id'] ?? 'N/A');
$qr_code_image_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qr_data_string);

// Pastikan $last_order didefinisikan
if ($current_page_status === 'confirmation_pending' && !isset($last_order)) {
    $payment_error = "Detail order tidak tersedia untuk konfirmasi.";
    $current_page_status = 'customer_info';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(($current_page_status === 'confirmation_pending') ? 'Payment Confirmation' : (($current_page_status === 'qr_payment') ? 'Complete Payment' : 'Checkout')); ?> | KelasKita</title>
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
        
        .debug-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            font-family: monospace;
            font-size: 12px;
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
            transition: background-color 0.3s ease;
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
            transition: background-color 0.3s ease;
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
            box-shadow: 0 4px 10px rgba(251, 191, 36, 0.3);
        }

        .payment-confirmation-box {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        .payment-confirmation-box .icon {
            font-size: 4rem;
            color: #10b981;
            margin-bottom: 20px;
        }
        .payment-confirmation-box h2 {
            color: #333;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .payment-confirmation-box p {
            color: #555;
            font-size: 1rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <?php include("../Views/navbarbootstrap.php"); ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- DEBUG INFO (hapus setelah masalah teratasi) -->
            <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
            <div class="debug-info">
                <h4>Debug Information:</h4>
                <p><strong>User ID:</strong> <?php echo htmlspecialchars($user_id ?? 'NULL'); ?></p>
                <p><strong>User Data:</strong> <?php echo $user_data ? 'Valid' : 'Invalid'; ?></p>
                <p><strong>Session Username:</strong> <?php echo htmlspecialchars($_SESSION['username'] ?? 'NOT SET'); ?></p>
                <p><strong>Session Email:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? 'NOT SET'); ?></p>
                <p><strong>Cart Items:</strong> <?php echo count($_SESSION['cart'] ?? []); ?></p>
                <p><strong>Current Page Status:</strong> <?php echo htmlspecialchars($current_page_status); ?></p>
                <?php if (!empty($payment_error)): ?>
                <p><strong>Payment Error:</strong> <?php echo htmlspecialchars($payment_error); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($current_page_status === 'confirmation_pending'): ?>
                <div class="payment-confirmation-box success-animation">
                    <div class="icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <h2>Pembayaran Anda Sedang Diverifikasi!</h2>
                    <p class="mb-4">Terima kasih telah melakukan pembayaran. Kami telah menerima bukti transfer Anda.</p>
                    <p class="mb-4">Nomor Order Anda: <strong class="text-blue-600"><?php echo htmlspecialchars($last_order['order_id'] ?? 'N/A'); ?></strong></p>
                    <p>Proses verifikasi biasanya membutuhkan waktu 1x24 jam kerja. Setelah pembayaran Anda berhasil diverifikasi, Anda akan menerima email konfirmasi dan akses ke kursus Anda.</p>
                    <p class="mt-4">Untuk pertanyaan lebih lanjut, silakan hubungi dukungan pelanggan kami.</p>
                    <a href="index.php" class="btn btn-primary mt-4">Kembali ke Beranda</a>
                    <a href="my_courses.php" class="btn btn-outline-secondary mt-4 ms-2">Lihat Kursus Saya</a>
                </div>

            <?php elseif ($current_page_status === 'qr_payment'): ?>
                <div>
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
                        <h1 class="text-3xl font-bold text-white mb-2">Selesaikan Pembayaran Anda</h1>
                        <p class="text-blue-100">Scan QR code di bawah untuk menyelesaikan pembayaran</p>
                    </div>
                    
                    <?php if (!empty($payment_error)): ?>
                        <div class="alert alert-danger text-center mb-4" role="alert">
                            <?php echo $payment_error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="card-modern p-6">
                            <div class="payment-timer">
                                <i class="fas fa-clock mr-2"></i>Selesaikan pembayaran dalam 15:00 menit
                            </div>
                            
                            <div class="qr-container">
                                <div class="invoice-header text-center mb-4">
                                    <h4 class="font-bold text-gray-800">Your Invoice</h4>
                                    <p class="text-sm text-gray-600">Invoice Code: <span class="font-bold"><?php echo htmlspecialchars($_SESSION['pending_order_data']['id'] ?? 'N/A'); ?></span></p>
                                    <p class="text-sm text-gray-600">Payment by BCA VIRTUAL ACCOUNT</p>
                                </div>
                                <div class="w-48 h-48 mx-auto mb-4 bg-white p-2 rounded-lg border border-gray-200">
                                    <img src="<?php echo htmlspecialchars($qr_code_image_url); ?>" alt="QR Code Pembayaran" class="w-full h-full object-contain">
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Nomor Virtual Account BCA</h3>
                                <p class="text-gray-600 mb-4 text-xl font-bold text-blue-700"><?php echo htmlspecialchars($qr_virtual_account_number); ?></p>
                                
                                <div class="bg-blue-50 p-3 rounded-lg mb-4">
                                    <p class="text-sm text-blue-800">
                                        <strong>Jumlah yang harus dibayar:</strong> Rp<?php echo $discounted_total_formatted; ?>
                                    </p>
                                </div>
                                
                                <div class="flex justify-center space-x-4 mb-4">
                                    <img src="https://via.placeholder.com/40x25/1f65ff/ffffff?text=OVO" alt="OVO" class="rounded">
                                    <img src="https://via.placeholder.com/40x25/00aa5b/ffffff?text=DANA" alt="DANA" class="rounded">
                                    <img src="https://via.placeholder.com/40x25/0066cc/ffffff?text=BCA" alt="BCA" class="rounded">
                                    <img src="https://via.placeholder.com/40x25/e31e24/ffffff?text=CIMB" alt="CIMB" class="rounded">
                                </div>
                            </div>
                            
                            <form method="POST" enctype="multipart/form-data" class="mt-4">
                                <div class="mb-3">
                                    <label for="proof_image" class="form-label text-gray-700">Unggah Bukti Transfer *</label>
                                    <input class="form-control" type="file" id="proof_image" name="proof_image" accept="image/*" required>
                                    <small class="text-muted">Format: JPG, PNG. Max size: 2MB.</small>
                                </div>
                                <button type="submit" name="upload_proof" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                                    <i class="fas fa-upload mr-2"></i>Unggah & Konfirmasi Pembayaran
                                </button>
                            </form>
                            
                            <p class="text-xs text-gray-500 text-center mt-3">
                                Klik tombol di atas setelah Anda berhasil melakukan pembayaran dan mengunggah bukti.
                            </p>
                        </div>
                        <div class="card-modern p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                <i class="fas fa-receipt mr-2 text-blue-500"></i>Ringkasan Pesanan
                            </h3>
                            
                            <div class="space-y-3 mb-4">
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <div>
                                        <h5 class="font-medium text-gray-800"><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <small class="text-gray-500">Qty: <?php echo (int)($item['quantity'] ?? 1); ?></small>
                                    </div>
                                    <span class="font-semibold text-gray-700">
                                        Rp<?php echo number_format($item['price'] * ($item['quantity'] ?? 1), 0, ',', '.'); ?>
                                    </span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-medium">Rp<?php echo $total_formatted; ?></span>
                                </div>
                                
                                <?php if ($applied_coupon): ?>
                                <div class="flex justify-between items-center mb-2 text-green-600">
                                    <span>Diskon (<?php echo htmlspecialchars($applied_coupon['code']); ?>):</span>
                                    <span>-Rp<?php echo $discount_amount_formatted; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center text-lg font-bold text-gray-800 border-t pt-2">
                                    <span>Total:</span>
                                    <span class="text-blue-600">Rp<?php echo $discounted_total_formatted; ?></span>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                <h4 class="font-semibold text-yellow-800 mb-2">
                                    <i class="fas fa-info-circle mr-2"></i>Instruksi Pembayaran
                                </h4>
                                <ol class="text-sm text-yellow-700 space-y-1">
                                    <li>1. Scan QR code dengan aplikasi mobile banking</li>
                                    <li>2. Transfer sesuai nominal yang tertera</li>
                                    <li>3. Screenshot bukti transfer</li>
                                    <li>4. Upload bukti transfer di form sebelah kiri</li>
                                    <li>5. Klik "Konfirmasi Pembayaran"</li>
                                </ol>
                            </div>
                            
                            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    Pembayaran Anda aman dan akan diverifikasi dalam 1x24 jam kerja.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: // customer_info ?>
                <div>
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
                        <p class="text-blue-100">Lengkapi informasi Anda untuk melanjutkan pembayaran</p>
                    </div>
                    
                    <?php if (!empty($payment_error)): ?>
                        <div class="alert alert-danger text-center mb-4" role="alert">
                            <?php echo $payment_error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="card-modern p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                <i class="fas fa-user-edit mr-2 text-blue-500"></i>Informasi Pelanggan
                            </h3>
                            
                            <form method="POST" class="space-y-4">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" 
                                           id="full_name" 
                                           name="full_name" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? $user_data['username'] ?? ''); ?>" 
                                           required>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? $user_data['email'] ?? ''); ?>" 
                                           required>
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon *</label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" 
                                           placeholder="08xxxxxxxxxx" 
                                           required>
                                </div>
                                
                                <div class="pt-4">
                                    <button type="submit" 
                                            name="proceed_to_payment" 
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105">
                                        <i class="fas fa-arrow-right mr-2"></i>Lanjut ke Pembayaran
                                    </button>
                                </div>
                            </form>
                            
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600">
                                    <i class="fas fa-lock mr-1"></i>
                                    Informasi Anda aman dan hanya digunakan untuk proses pembelian.
                                </p>
                            </div>
                        </div>
                        
                        <div class="card-modern p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                <i class="fas fa-shopping-cart mr-2 text-green-500"></i>Ringkasan Pesanan
                            </h3>
                            
                            <div class="space-y-3 mb-4">
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <div>
                                        <h5 class="font-medium text-gray-800"><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <small class="text-gray-500">Qty: <?php echo (int)($item['quantity'] ?? 1); ?></small>
                                    </div>
                                    <span class="font-semibold text-gray-700">
                                        Rp<?php echo number_format($item['price'] * ($item['quantity'] ?? 1), 0, ',', '.'); ?>
                                    </span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-medium">Rp<?php echo $total_formatted; ?></span>
                                </div>
                                
                                <?php if ($applied_coupon): ?>
                                <div class="flex justify-between items-center mb-2 text-green-600">
                                    <span>Diskon (<?php echo htmlspecialchars($applied_coupon['code']); ?>):</span>
                                    <span>-Rp<?php echo $discount_amount_formatted; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center text-lg font-bold text-gray-800 border-t pt-2">
                                    <span>Total:</span>
                                    <span class="text-green-600">Rp<?php echo $discounted_total_formatted; ?></span>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
                                <h4 class="font-semibold text-blue-800 mb-2">
                                    <i class="fas fa-credit-card mr-2"></i>Metode Pembayaran
                                </h4>
                                <p class="text-sm text-blue-700">BCA Virtual Account</p>
                                <p class="text-xs text-blue-600 mt-1">Transfer bank yang aman dan mudah</p>
                            </div>
                            
                            <div class="mt-4 p-3 bg-green-50 rounded-lg">
                                <p class="text-sm text-green-800">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Akses kursus langsung setelah pembayaran terverifikasi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Timer countdown untuk pembayaran (15 menit)
        <?php if ($current_page_status === 'qr_payment'): ?>
        let timeLeft = 15 * 60; // 15 menit dalam detik
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const timerElement = document.querySelector('.payment-timer');
            
            if (timerElement) {
                timerElement.innerHTML = `<i class="fas fa-clock mr-2"></i>Selesaikan pembayaran dalam ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} menit`;
            }
            
            if (timeLeft <= 0) {
                // Timer habis, redirect ke halaman cart
                alert('Waktu pembayaran telah habis. Silakan coba lagi.');
                window.location.href = 'cart.php';
                return;
            }
            
            // Ubah warna timer jika waktu tinggal sedikit
            if (timeLeft <= 300 && timerElement) { // 5 menit terakhir
                timerElement.style.background = 'linear-gradient(90deg, #ef4444, #dc2626)';
            }
            
            timeLeft--;
        }
        
        // Update timer setiap detik
        setInterval(updateTimer, 1000);
        updateTimer(); // Panggil sekali untuk inisialisasi
        <?php endif; ?>
        
        // Validasi form sebelum submit
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('input[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        alert('Mohon lengkapi semua field yang wajib diisi.');
                    }
                });
            });
            
            // Preview image sebelum upload
            const fileInput = document.getElementById('proof_image');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validasi ukuran file (2MB)
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
                    }
                });
            }
        });
        
        // Auto-format nomor telepon
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, ''); // Hapus semua non-digit
                
                // Format nomor telepon Indonesia
                if (value.startsWith('62')) {
                    value = '0' + value.substring(2);
                } else if (!value.startsWith('0') && value.length > 0) {
                    value = '0' + value;
                }
                
                e.target.value = value;
            });
        }
    </script>
</body>
</html>