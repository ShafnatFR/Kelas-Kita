<?php
include "db.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? "";
    
    if (!isset($_POST["password"]) || $_POST["password"] === "") {
        $pesan = "Silakan isi password terlebih dahulu.";
    } else {
        $password = $_POST["password"];
        if (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/\d/', $password) ||
            !preg_match('/[@$!%*?&.]/', $password)
        ) {
            $pesan = "Password tidak memenuhi kriteria keamanan.";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE tbuser SET password=? WHERE username=?");
            $stmt->bind_param("ss", $passwordHash, $username);

            if ($stmt->execute()) {
                echo "Password berhasil diubah!";
                header("Location: HalamanSignIn.php?reset=success");
                exit;
            } else {
                $pesan = "Gagal mengubah password.";
            }
        }
    }
} 

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukkan Password Baru</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                    <button type="submit" class="btn btn-primary">Kirim</button>
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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
