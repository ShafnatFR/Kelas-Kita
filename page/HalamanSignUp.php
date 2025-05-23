<?php
session_start();
include "db.php";

$messege = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi password
    if (strlen($password) < 8) {
        $messege = "Password minimal 8 karakter.";
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $messege = "Password harus mengandung setidaknya 1 karakter khusus.";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Set role menjadi 'murid' secara default (bukan 'peserta')
        $role = 'murid';

        // Cek apakah username sudah ada di database
        $sql = "SELECT * FROM tb_user WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $messege = "Username sudah terdaftar. Silahkan login.";
        } else {
            // Simpan data pengguna baru ke dalam database dengan role 'murid'
            $sql = "INSERT INTO tb_user (username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $hashedPassword, $role);

            if ($stmt->execute()) {
                // JANGAN set session setelah registrasi
                // Hanya set pesan sukses untuk ditampilkan di halaman login
                $_SESSION['success_message'] = "Berhasil register. Silahkan login.";
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
                <h2 class="font-weight-bold text-primary">Buat Akun KelasKita</h2>
                <!-- <img src="../assets/images/Google1.png" alt="Google Logo" width="50" class="my-3"> -->

                <?php if (!empty($messege)) : ?>
                    <div class="alert alert-warning w-75"><?php echo $messege; ?></div>
                <?php endif; ?>

                <!-- FORM -->
                <form id="signupForm" class="w-75 mt-3" method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Masukan Username" required>
                    </div>
                    <div class="form-group">
                         <label for="password">Password</label>
                         <input type="password" class="form-control" name="password" placeholder="Minimal memiliki 8 karakter dan karakter khusus" required>
                    </div>
                    <!-- Tombol akan memunculkan modal -->
                    <button type="button" class="btn btn-primary btn-block rounded-pill mt-4" data-toggle="modal" data-target="#termsModal">Sign Up</button>
                    <div class="text-left mb-2 mt-3">
                        <a href="index.php" class="text-primary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="termsModalLabel">Syarat dan Ketentuan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Dengan mendaftar, Anda menyetujui syarat dan ketentuan kami.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tolak</button>
                <button type="button" id="acceptTermsBtn" class="btn btn-primary">Terima</button>
            </div>
        </div>
    </div>
</div>

<!-- Script: Tutup modal lalu submit -->
<script>
    document.getElementById('acceptTermsBtn').addEventListener('click', function () {
    $('#termsModal').modal('hide');
    setTimeout(function () {
        document.getElementById('signupForm').submit();
    }, 300); // Jeda agar modal benar-benar tertutup
});
</script>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
