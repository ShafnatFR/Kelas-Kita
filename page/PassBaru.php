<?php
include "../koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $passwordBaru = $_POST["password"];

    // Validasi password di server
    if (
        strlen($passwordBaru) < 8 ||
        !preg_match('/[A-Z]/', $passwordBaru) ||
        !preg_match('/[a-z]/', $passwordBaru) ||
        !preg_match('/\d/', $passwordBaru) ||
        !preg_match('/[@$!%*?&.]/', $passwordBaru)
    ) {
        $pesan = "Password tidak memenuhi kriteria keamanan.";
    } else {
        // Enkripsi password
        $passwordHash = password_hash($passwordBaru, PASSWORD_DEFAULT);

        // Update ke database (pastikan username dikirim dari sesi atau form sebelumnya)
        $sql = "UPDATE users SET password='$passwordHash' WHERE username='$username'";
        if (mysqli_query($koneksi, $sql)) {
            header("Location: HalamanSignIn.php?reset=success");
            exit;
        } else {
            $pesan = "Gagal mengubah password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masukkan Password Baru</title>
    <link rel="stylesheet" href="../assets/css/PassBaru.css"> <!-- Optional -->
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-2">Masukkan Password Baru</h2>
                <p class="mb-3">Silakan masukkan password baru Anda</p>
                <?php if ($pesan): ?>
                    <div style="color: red;"><?= $pesan ?></div>
                <?php endif; ?>
                <form method="POST" onsubmit="return validasiForm();">
                    <div class="form-group">
                        <input type="hidden" name="username" value="<?= htmlspecialchars($_GET['username'] ?? '') ?>">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password baru" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Kirim</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function validasiForm() {
        var password = document.getElementById('password').value;
        var syarat = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.]).{8,}$/;

        if (!syarat.test(password)) {
            alert('Password harus minimal 8 karakter dan mengandung huruf besar, kecil, angka, serta karakter spesial.');
            return false;
        }
        return true;
    }
    </script>
</body>
</html>
