<?php
// Start a session to maintain state
include "db.php";
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "KelasKita";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Process cart actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Remove item from cart
    if (isset($_POST['remove_item']) && isset($_POST['item_index'])) {
        $index = intval($_POST['item_index']);
        if (isset($_SESSION['cart'][$index])) {
            array_splice($_SESSION['cart'], $index, 1);
            $_SESSION['message'] = "Item berhasil dihapus dari keranjang!";
            $_SESSION['message_type'] = "success";
        }
    }
    
    // Update item quantity
    if (isset($_POST['update_quantity']) && isset($_POST['item_index']) && isset($_POST['quantity'])) {
        $index = intval($_POST['item_index']);
        $quantity = intval($_POST['quantity']);
        
        if (isset($_SESSION['cart'][$index]) && $quantity > 0) {
            $_SESSION['cart'][$index]['quantity'] = $quantity;
            $_SESSION['message'] = "Quantity berhasil diupdate!";
            $_SESSION['message_type'] = "success";
        }
    }
    
    // Clear cart
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = array();
        $_SESSION['message'] = "Keranjang berhasil dikosongkan!";
        $_SESSION['message_type'] = "success";
    }
    
    // Checkout (this would typically redirect to a checkout page)
    if (isset($_POST['checkout'])) {
        if (!empty($_SESSION['cart'])) {
            // Redirect to checkout page
            header("Location: checkout.php");
            exit();
        } else {
            $_SESSION['message'] = "Keranjang belanja kosong!";
            $_SESSION['message_type'] = "warning";
        }
    }
    
    // Prevent form resubmission
    header("Location: shopping-cart.php");
    exit();
}

// Function to get course details by ID
function getCourseById($id) {
    global $conn;
    
    $stmt = mysqli_prepare($conn, "SELECT * FROM courses WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
    
    return null;
}

// Calculate cart total
$total = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
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
          <a href="shopping-cart.php" class="nav-link active">
            <i class="fas fa-shopping-cart"></i>
            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
              <span class="badge bg-primary rounded-pill"><?php echo count($_SESSION['cart']); ?></span>
            <?php endif; ?>
          </a>
        </li>
      </ul>

      <!-- Login dan Sign Up -->
<div class="d-flex align-items-center gap-2">
  <a href="login.php" class="btn btn-outline-primary btn-sm">Login</a>
  <a href="signup.php" class="btn btn-primary btn-sm">Sign Up</a>
</div>
    </div>
  </div>
</nav>

    <!-- Display message if any -->
    <?php if(isset($_SESSION['message'])): ?>
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
                        <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">Kursu</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Kuantitas</th>
                                    <th scope="col">Total</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($_SESSION['cart'] as $index => $item): ?>
                                <tr>
                                    <td width="100">
                                        <?php if(isset($item['image']) && !empty($item['image'])): ?>
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
                                            <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm quantity-input" onchange="this.form.submit()">
                                            <input type="hidden" name="update_quantity" value="1">
                                        </form>
                                    </td>
                                    <td>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="item_index" value="<?php echo $index; ?>">
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
                                <i class="fas fa-arrow-left me-2"></i> lanjutkan Berbelanja
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
                        <h4 class="mb-4">ringkasan pesanan</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span>pajak</span>
                            <span>Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                        </div>
                        <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
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

    <footer class="bg-white pt-5 pb-4 border-top">
  <div class="container px-4">
    <div class="row row-cols-1 row-cols-md-5 g-4 mb-5">
      <!-- Kelas Kita -->
      <div class="col-md">
        <h4 class="text-primary fw-bold mb-3">Kelas Kita</h4>
        <p class="text-muted mb-3">Platform terbaik untuk mempelajari keterampilan baru dan memajukan karier Anda.</p>
        <div class="d-flex gap-3 mb-3">
          <a href="#" class="text-muted"><i class="fab fa-facebook"></i></a>
          <a href="#" class="text-muted"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-muted"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
        </div>
      </div>

      <!-- Kursus -->
      <div class="col-md">
        <h5 class="fw-semibold mb-3">Kursus</h5>
        <ul class="list-unstyled text-muted">
          <li><a href="#" class="text-decoration-none text-muted">Pengembang Web</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Ilmu Data</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Pengembang Seluler</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Bisnis</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Pemasaran</a></li>
        </ul>
      </div>

      <!-- Perusahaan -->
      <div class="col-md">
        <h5 class="fw-semibold mb-3">Perusahaan</h5>
        <ul class="list-unstyled text-muted">
          <li><a href="#" class="text-decoration-none text-muted">Tentang Kami</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Karier</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Tekan</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Blog</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Kontak</a></li>
        </ul>
      </div>

      <!-- Support -->
      <div class="col-md">
        <h5 class="fw-semibold mb-3">Support</h5>
        <ul class="list-unstyled text-muted">
          <li><a href="#" class="text-decoration-none text-muted">Pusat Bantuan</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Ketentuan Layanan</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Legal</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Kebijakan Privasi</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Status</a></li>
        </ul>
      </div>

      <!-- Unduh Aplikasi -->
      <div class="col-md">
        <h5 class="fw-semibold mb-3">Unduh Aplikasi</h5>
        <div class="d-flex flex-column gap-2">
          <a href="#"><img src="../assets/images/6acf4c84f55a52f6ccbdaa71ad2701ee.jpg" alt="App Store" class="img-fluid" style="max-height: 40px;"></a>
        </div>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div class="border-top pt-3 text-center text-muted small">
      <p class="mb-0">© 2025 Upskill. All rights reserved. | www.DownloadRealProjectSource.com</p>
    </div>
  </div>
</footer>

    <!-- Bootstrap JS bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>