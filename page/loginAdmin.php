<?php
session_start();
include "db.php";  // Pastikan sudah menghubungkan ke database

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa username dan password
    $stmt = $conn->prepare("SELECT * FROM tb_user WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika login berhasil, simpan data pengguna ke session
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['id'] = $user['id_user'];

        // Redirect berdasarkan role
        if ($user['role'] == 'admin') {
            header("Location: admin-dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        // Jika login gagal
        $message = "Username atau Password salah!";
    }
}

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
                <h1 class="display-4 font-weight-bold text-center">Halo Min!</h1>
                <p class="text-center">Selamat datang!</p>
                <!-- <a href="HalamanSignUp.php" class="btn btn-outline-light rounded-pill px-4 mt-3">Sign Up</a> -->
            </div>

            <!-- Kanan -->
            <div class="col-md-7 d-flex flex-column justify-content-center align-items-center right-section">
                <h2 class="font-weight-bold text-primary">Masuk ke Kontrol Admin</h2>

                <?php if (!empty($message)) : ?>
                    <div class="alert alert-warning w-75"><?php echo $message; ?></div>
                <?php endif; ?>

                <form class="w-75 mt-3" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Masukkan Username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Masukkan Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block rounded-pill">Sign In</button>

                    <!-- <div class="d-flex justify-content-between mt-3">
                        <a href="ForgetPass.php" class="text-primary">Lupa Password?</a>
                        <a href="index.php" class="text-primary">Kembali</a>
                    </div> -->
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>