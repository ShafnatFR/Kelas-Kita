<?php
session_start();
include "page/db.php"; // Pastikan sudah menghubungkan ke database

$message = "";

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); // Tambahkan trim untuk menghilangkan spasi
    $password = $_POST['password'];

    // Validasi input tidak kosong
    if (empty($username) || empty($password)) {
        $message = "Username dan password tidak boleh kosong!";
    } else {
        // Ambil data user dari database berdasarkan username
        $sql = "SELECT id_user, username, password, role FROM tb_user WHERE username = ?"; // Ambil kolom yang diperlukan saja
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Cek apakah user ditemukan
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();

                // Verifikasi password
                if (password_verify($password, $row['password'])) {
                    // Regenerate session ID untuk keamanan
                    session_regenerate_id(true);
                    
                    // Simpan ke session
                    $_SESSION['id'] = $row['id_user']; 
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role']; 

                    // Implementasi "Remember Me" (Opsional, perlu lebih lanjut)
                    // if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
                    //     // Set cookie dengan token yang aman
                    //     $token = bin2hex(random_bytes(32)); // Generate token unik
                    //     $hashed_token = hash('sha256', $token); // Hash token untuk penyimpanan
                    //     // Simpan $hashed_token dan $row['id_user'] ke tabel persistent_logins
                    //     // Set cookie: setcookie('remember_me', $token, time() + (86400 * 30), "/", "", false, true); // 30 hari
                    // }

                    // Redirect ke halaman sesuai role
                    if ($row['role'] == 'admin') {
                        header("Location: ../admin-dashboard.php"); // Pastikan path benar
                        exit();
                    } elseif ($row['role'] == 'murid' || $row['role'] == 'mentor') {
                        header("Location: page/index.php"); // Pastikan path benar
                        exit();
                    } else {
                        $message = "Role user tidak valid."; // Pesan yang lebih umum
                    }
                } else {
                    $message = "Username atau password salah."; // Pesan umum untuk keamanan
                }
            } else {
                $message = "Username atau password salah."; // Pesan umum untuk keamanan
            }
            $stmt->close();
        } else {
            $message = "Terjadi kesalahan pada sistem. Silakan coba lagi."; // Pesan umum, hindari detail teknis
            // Untuk debugging di lingkungan dev, bisa tambahkan: error_log("Prepared statement error: " . $conn->error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin 2 - Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <?php if (!empty($message)): ?>
                                        <div class="alert alert-danger text-center" role="alert">
                                            <?php echo $message; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form class="user" action="authenticate.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username" placeholder="Masukkan Username" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Masukkan Password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck" name="remember_me">
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                        <hr>
                                        <a href="#" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="#" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>