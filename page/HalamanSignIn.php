<?php
session_start();
include "db.php";

$messege = "";

if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data user dari database berdasarkan username
    $sql = "SELECT * FROM tbuser WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah user ditemukan
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verifikasi password dengan hash yang ada di database
        if (password_verify($password, $row['password'])) {
            // Jika password cocok, simpan username ke session dan arahkan ke halaman utama
            $_SESSION['username'] = $username;
            header("Location: index.php"); // Ganti dengan halaman utama kamu
            exit();
        } else {
            $messege = "Password salah!";
        }
    } else {
        $messege = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login KelasKita</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styless.css">
</head>
<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Kiri -->
            <div class="col-md-5 d-flex flex-column justify-content-center align-items-center text-white left-section">
                <h1 class="display-4 font-weight-bold text-center">Halo Teman Baru!</h1>
                <p class="text-center">Silakan daftar akun baru jika belum memiliki akun.</p>
                <a href="HalamanSignUp.php" class="btn btn-outline-light rounded-pill px-4 mt-3">Sign Up</a>
            </div><?php
            
            ?>
            
            <!-- Kanan -->
            <div class="col-md-7 d-flex flex-column justify-content-center align-items-center right-section">
                <h2 class="font-weight-bold text-primary">Masuk ke KelasKita</h2>
                <img src="Google1.png" alt="Google Logo" width="50" class="my-3">

                <?php if (!empty($messege)) : ?>
                    <div class="alert alert-warning w-75"><?php echo $messege; ?></div>
                <?php endif; ?>

                <form class="w-75 mt-3" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Masukan Username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password"placeholder="Masukan Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block rounded-pill">Sign In</button>
                    <div class="text-right mb-2">
                        <a href="ForgetPass.php" class="text-primary">Lupa Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
