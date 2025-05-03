<?php
include "db.php";

$messege = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi password: minimal 8 karakter dan ada karakter khusus
    if (strlen($password) < 8) {
        $messege = "Password minimal 8 karakter.";
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $messege = "Password harus mengandung setidaknya 1 karakter khusus (contoh: !, @, #, $, %, dll).";
    } else {
        // Jika validasi password lolos, lanjut hash password
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Cek apakah username sudah ada
        $sql = "SELECT * FROM tbuser WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $messege = "Username sudah terdaftar. Silahkan login.";
        } else {
            $sql = "INSERT INTO tbuser (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                header("Location: HalamanSignIn.php");
                exit();
            } else {
                $messege = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register KelasKita</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styless.css">
</head>
<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Kiri -->
            <div class="col-md-5 d-flex flex-column justify-content-center align-items-center text-white left-section">
                <h1 class="display-4 font-weight-bold text-center">Selamat Datang Kembali!</h1>
                <p class="text-center">Untuk tetap terhubung, silahkan login dengan akun Anda.</p>
                <a href="HalamanSignIn.php" class="btn btn-outline-light rounded-pill px-4 mt-3">Sign In</a>
            </div>
            
            <!-- Kanan -->
            <div class="col-md-7 d-flex flex-column justify-content-center align-items-center right-section">
                <h2 class="font-weight-bold text-danger">Buat Akun KelasKita</h2>
                <img src="../assets/images/Google1.png" alt="Google Logo" width="50" class="my-3">

                <?php if (!empty($messege)) : ?>
                    <div class="alert alert-warning w-75"><?php echo $messege; ?></div>
                <?php endif; ?>

                <form id="signupForm" class="w-75 mt-3" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Masukan Username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Masukan Password" required>
                    </div>
                    <button type="button" class="btn btn-danger btn-block rounded-pill mt-3" data-toggle="modal" data-target="#termsModal">Sign Up</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="termsModalLabel">Syarat dan Ketentuan KelasKita</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>1. Pendahuluan</h5>
                    <p>Selamat datang di KelasKita! Dengan mengakses dan menggunakan website kami, Anda dianggap telah menyetujui Syarat dan Ketentuan ini. Jika tidak setuju, mohon hentikan penggunaan.</p>

                    <h5>2. Definisi</h5>
                    <ul>
                        <li>"Website" = situs web resmi kami.</li>
                        <li>"Pengguna" = individu yang menggunakan website.</li>
                        <li>"Layanan" = semua fitur dan informasi dalam website.</li>
                    </ul>

                    <h5>3. Perubahan</h5>
                    <p>Kami bisa mengubah Syarat dan Ketentuan kapan saja. Harap cek halaman ini secara berkala.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Tolak</button>
                    <button type="button" id="acceptTermsBtn" class="btn btn-danger">Terima</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        document.getElementById('acceptTermsBtn').addEventListener('click', function() {
            document.getElementById('signupForm').submit();
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
