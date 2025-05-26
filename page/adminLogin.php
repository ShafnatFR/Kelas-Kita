<?php
session_start();
include "db.php";  // Pastikan sudah menghubungkan ke database

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
        $sql = "SELECT * FROM tb_user WHERE username = ?";
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
                    $_SESSION['id'] = $row['id_user'];  // Menyimpan ID user
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role']; // Menyimpan role di session

                    // Debug: Uncomment baris di bawah untuk debugging
                    // echo "Login berhasil! Role: " . $row['role']; exit();

                    // Redirect ke halaman sesuai role
                    if ($row['role'] == 'admin') {
                        // Jika role mentor, redirect ke dashboard mentor
                        header("Location: admin-dashboard.php");
                        exit();
                    } elseif ($row['role'] == 'murid' || $row['role'] == 'mentor') {
                        // Jika role murid atau peserta, redirect ke halaman utama
                        header("Location: index.php");
                        exit();
                    } else {
                        $message = "Role tidak dikenali: " . $row['role'];
                    }
                } else {
                    $message = "Password salah!";
                }
            } else {
                $message = "Username tidak ditemukan!";
            }
            $stmt->close();
        } else {
            $message = "Terjadi kesalahan pada sistem!";
        }
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
                <h1 class="display-4 font-weight-bold text-center">Halo Admin!</h1>
                <p class="text-center">Senang melihatmu kembali.</p>
                <!-- <a href="HalamanSignUp.php" class="btn btn-outline-light rounded-pill px-4 mt-3">Sign Up</a> -->
            </div>

            <!-- Kanan -->
            <div class="col-md-7 d-flex flex-column justify-content-center align-items-center right-section">
                <h2 class="font-weight-bold text-primary">Masuk ke Dashboard Admin</h2>

                <?php if (!empty($message)) : ?>
                    <div class="alert alert-warning w-75"><?php echo htmlspecialchars($message); ?></div>
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

                    <div class="d-flex justify-content-between mt-3">
                        <a href="ForgetPass.php" class="text-primary">Lupa Password?</a>
                        <!-- <a href="index.php" class="text-primary">Kembali</a> -->
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