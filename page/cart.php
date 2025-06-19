<?php
session_start();

// Redirect if user not logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = "Silakan login terlebih dahulu untuk melanjutkan pembelian.";
    $_SESSION['message_type'] = "warning";
    $_SESSION['redirect_after_login'] = "cart.php";
    header("Location: HalamanSignIn.php");
    exit();
}

// Include cart database integration functions
include "cart_db_integration.php";

$site_name = "KelasKita"; // Define site name for footer usage

$db_host = "localhost";
$db_user = "root";
$db_pass = ""; 
$db_name = "KelasKita_baru";

// Membuat koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set karakter encoding
$conn->set_charset("utf8mb4");

// Pastikan keranjang belanja ada dalam session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    
    // If user is logged in, get cart data from database
    if (isset($_SESSION['id'])) {
        $_SESSION['cart'] = getCartItems($_SESSION['id'], $conn);
    }
}

// Proses aksi keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user ID if logged in
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    
    // Menghapus item dari keranjang
    if (isset($_POST['remove_item']) && isset($_POST['item_id'])) {
        $item_id = intval($_POST['item_id']);
        removeFromCart($item_id, $user_id, $conn);
        
        $_SESSION['message'] = "Item berhasil dihapus dari keranjang!";
        $_SESSION['message_type'] = "success";
    }
    
    // Mengupdate kuantitas item
    if (isset($_POST['update_quantity']) && isset($_POST['item_id']) && isset($_POST['quantity'])) {
        $item_id = intval($_POST['item_id']);
        // Force quantity to 1 regardless of input
        $quantity = 1;
        
        if ($quantity > 0) {
            foreach ($_SESSION['cart'] as $index => $item) {
                if ($item['id'] == $item_id) {
                    $_SESSION['cart'][$index]['quantity'] = $quantity;
                    break;
                }
            }
            
            // If user is logged in, update database
            if ($user_id) {
                updateDatabaseCart($user_id, $conn);
            }
            
            $_SESSION['message'] = "Kuantitas berhasil diperbarui!";
            $_SESSION['message_type'] = "success";
        }
    }
    
    // Mengosongkan keranjang
    if (isset($_POST['clear_cart'])) {
        clearCart($user_id, $conn);
        
        $_SESSION['message'] = "Keranjang berhasil dikosongkan!";
        $_SESSION['message_type'] = "success";
    }
    
    // Proses 
    if (isset($_POST['transaksi'])) {
        if (!empty($_SESSION['cart'])) {
            // Jika pengguna sudah login, proses 
            if (isset($_SESSION['id'])) {
                // Just redirect to page for payment processing
                header("Location: transaksi.php");
                exit();
            } else {
                // Jika belum login, arahkan ke halaman login
                $_SESSION['message'] = "Silakan login terlebih dahulu untuk melanjutkan pembelian.";
                $_SESSION['message_type'] = "warning";
                $_SESSION['redirect_after_login'] = "cart.php";
                header("Location: HalamanSignIn.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Keranjang belanja kosong!";
            $_SESSION['message_type'] = "warning";
        }
    }

    // Hindari pengiriman ulang form
    header("Location: cart.php");
    exit();
}

// Fetch complete course data for cart items from database
$cart_items_data = [];
if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $item_ids = array_map(function($item) { return $item['id']; }, $_SESSION['cart']);
    $ids_placeholder = implode(',', array_fill(0, count($item_ids), '?'));
    
    // Fetch complete course data including name, price, category, and image
$stmt = $conn->prepare("SELECT id_kelas, nama_kelas, harga, kategori, profil_kelas AS gambar, description AS deskripsi FROM tb_kelas WHERE id_kelas IN ($ids_placeholder)");
    $types = str_repeat('i', count($item_ids));
    $stmt->bind_param($types, ...$item_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        // Try different possible column names for course title
        $course_name = '';
        if (!empty($row['judul_kelas'])) {
            $course_name = $row['judul_kelas'];
        } elseif (!empty($row['nama_kelas'])) {
            $course_name = $row['nama_kelas'];
        } else {
            $course_name = 'Kursus Tanpa Judul';
        }
        
        $cart_items_data[$row['id_kelas']] = [
            'name' => $course_name,
            'price' => $row['harga'],
            'category' => $row['kategori'] ?? 'Umum',
            'image' => $row['gambar'] ?? '',
            'description' => $row['deskripsi'] ?? ''
        ];
    }
}

// Update cart items with fresh data from database
if (!empty($cart_items_data)) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if (isset($cart_items_data[$item['id']])) {
            $_SESSION['cart'][$index]['name'] = $cart_items_data[$item['id']]['name'];
            $_SESSION['cart'][$index]['price'] = $cart_items_data[$item['id']]['price'];
            $_SESSION['cart'][$index]['category'] = $cart_items_data[$item['id']]['category'];
            $_SESSION['cart'][$index]['image'] = $cart_items_data[$item['id']]['image'];
        }
    }
}

// Calculate total using current prices
$total = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $price = isset($cart_items_data[$item['id']]) ? $cart_items_data[$item['id']]['price'] : ($item['price'] ?? 0);
        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
        $total += $price * $quantity;
    }
    $_SESSION['cart_total_amount'] = $total; // Store total in session for use in transaksi.php
}

// Debug: Uncomment the lines below to see cart data
// echo "<pre>Cart Data: ";
// print_r($_SESSION['cart']);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - KelasKita</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            font-weight: bold;
            color: #0d6efd;
        }

        .cart-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .cart-item-img {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
        }

        .footer-section {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            margin-top: auto;
        }

        .social-icon {
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .main-content {
            flex: 1;
        }

        .quantity-input {
            width: 70px;
        }

        .empty-cart {
            text-align: center;
            padding: 50px 0;
        }

        .empty-cart i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include("../Views/navbarbootstrap.php"); ?>
    <!-- Display message if any -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php 
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        endif; 
    ?>

    <!-- Main Content -->
    <div class="main-content py-5">
        <div class="container">
            <h2 class="mb-4">Keranjang Belanja</h2>

            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-container p-4 mb-4">
                        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="2">Kursus</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Kuantitas</th>
                                        <th scope="col">Total</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($_SESSION['cart'] as $item):
                                        $price = isset($cart_items_data[$item['id']]) ? $cart_items_data[$item['id']]['price'] : ($item['price'] ?? 0);
                                        $course_name = isset($item['name']) && !empty($item['name']) ? $item['name'] : 'Kursus Tanpa Judul';
                                        $category = isset($item['category']) && !empty($item['category']) ? $item['category'] : 'Umum';
                                        $image = isset($item['image']) && !empty($item['image']) ? $item['image'] : '';
                                    ?>
                                    <tr>
                                        <td width="100">
                                            <?php if (!empty($image)): ?>
                                            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($course_name); ?>" class="cart-item-img">
                                            <?php else: ?>
                                            <div class="bg-light d-flex justify-content-center align-items-center cart-item-img">
                                                <i class="fas fa-book text-muted"></i>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($course_name); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($category); ?></small>
                                        </td>
                                        <td><?php echo 'Rp ' . number_format(intval($price), 0, ',', '.'); ?></td>
                                        <td>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm quantity-input" readonly>
                                                <input type="hidden" name="update_quantity" value="1">
                                            </form>
                                        </td>
                                        <td><?php echo 'Rp ' . number_format(intval($price * (isset($item['quantity']) ? $item['quantity'] : 1)), 0, ',', '.'); ?></td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" name="remove_item" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between mt-4">
                                <form method="post">
                                    <button type="submit" name="clear_cart" class="btn btn-outline-secondary">
                                        <i class="fas fa-trash me-2"></i> Hapus Keranjang
                                    </button>
                                </form>
                                <a href="index.php" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i> Lanjutkan Berbelanja
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="empty-cart">
                                <i class="fas fa-shopping-cart"></i>
                                <h4>Keranjang Anda kosong</h4>
                                <p class="text-muted">Sepertinya Anda belum menambahkan kursus apa pun ke keranjang belanja Anda.</p>
                                <a href="index.php" class="btn btn-primary mt-3">
                                Telusuri Kursus
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="cart-container p-4">
                        <h4 class="mb-4">Ringkasan Pesanan</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span><?php echo 'Rp ' . number_format(intval($total), 0, ',', '.'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span>Pajak</span>
                            <span>Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                        </div>
                        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                        <form method="post">
                            <button type="submit" name="transaksi" class="btn btn-primary w-100">
                            Lanjutkan ke Pembayaran
                            </button>
                        </form>
                        <?php else: ?>
                        <button type="button" class="btn btn-primary w-100" disabled>
                        Lanjutkan ke Pembayaran
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_once("../Views/footerbootsrap.php"); ?>

    <!-- Bootstrap JS bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>