<?php
session_start();

// Include cart database integration functions
include "cart_db_integration.php";

$site_name = "KelasKita"; // Define site name for footer usage

$db_host = "localhost";
$db_user = "root";
$db_pass = ""; 
$db_name = "KelasKita";


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
    if (isset($_SESSION['user_id'])) {
        $_SESSION['cart'] = getCartItems($_SESSION['user_id'], $conn);
    }
}

// Proses aksi keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user ID if logged in
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
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
        $quantity = intval($_POST['quantity']);
        
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
    
    // Proses checkout
    if (isset($_POST['checkout'])) {
        if (!empty($_SESSION['cart'])) {
            // Jika pengguna sudah login, proses checkout
            if (isset($_SESSION['user_id'])) {
                // Insert ke tabel orders
                $user_id = $_SESSION['user_id'];
                $total = 0;
                
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                
                $timestamp = date('Y-m-d H:i:s');
                $reference = 'ORD-'.time().'-'.$user_id;
                
                $sql = "INSERT INTO orders (user_id, total_amount, reference_id, status, created_at) 
                        VALUES (?, ?, ?, 'pending', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("idss", $user_id, $total, $reference, $timestamp);
                $stmt->execute();
                
                $order_id = $conn->insert_id;
                
                // Insert ke tabel order_items
                foreach ($_SESSION['cart'] as $item) {
                    $item_total = $item['price'] * $item['quantity'];
                    
                    $sql = "INSERT INTO order_items (order_id, course_id, quantity, price, total) 
                            VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iiidi", $order_id, $item['id'], $item['quantity'], $item['price'], $item_total);
                    $stmt->execute();
                }
                
                // Kosongkan keranjang
                clearCart($user_id, $conn);
                
                // Redirect ke halaman konfirmasi
                $_SESSION['message'] = "Pesanan berhasil dibuat!";
                $_SESSION['message_type'] = "success";
                header("Location: checkout.php?order_id=".$order_id);
                exit();
            } else {
                // Jika belum login, arahkan ke halaman login
                $_SESSION['message'] = "Silakan login terlebih dahulu untuk melanjutkan pembelian.";
                $_SESSION['message_type'] = "warning";
                $_SESSION['redirect_after_login'] = "cart.php";
                header("Location: login.php");
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

// Menghitung total keranjang
$total = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}

// Rest of the HTML code remains the same...
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">KelasKita</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kursus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a href="cart.php" class="nav-link active">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="badge bg-primary rounded-pill"><?php echo count($_SESSION['cart']); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>

                <!-- Login dan Sign Up -->
                <div class="d-flex align-items-center gap-2">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['username'] ?? 'Akun'); ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">Profil Saya</a></li>
                                <li><a class="dropdown-item" href="my-courses.php">Kursus Saya</a></li>
                                <li><a class="dropdown-item" href="my-orders.php">Pesanan Saya</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary btn-sm">Login</a>
                        <a href="signup.php" class="btn btn-primary btn-sm">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Display message if any -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
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
                                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                    <tr>
                                        <td width="100">
                                            <?php if (isset($item['image']) && !empty($item['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-img">
                                            <?php else: ?>
                                            <div class="bg-light d-flex justify-content-center align-items-center cart-item-img">
                                                <i class="fas fa-book text-muted"></i>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($item['category']); ?></small>
                                        </td>
                                        <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                        <td>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm quantity-input" onchange="this.form.submit()">
                                                <input type="hidden" name="update_quantity" value="1">
                                            </form>
                                        </td>
                                        <td>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
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
                            <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
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
                            <button type="submit" name="checkout" class="btn btn-primary w-100">
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