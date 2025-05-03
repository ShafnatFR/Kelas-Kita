<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Pastikan user ada
    $stmt = $conn->prepare("SELECT * FROM tbuser WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Redirect ke form password baru
        header("Location: PassBaru.php?username=" . urlencode($username));
        exit;
    } else {
        echo "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password Anda</h2>
        <form id="resetForm" method="post" action="FogetPass.php">
            <p>Jangan khawatir, masukan username anda dibawah dan kami akan<br>mengirim anda reset kode.</p>
            <div class="form-group">
                <input type="text" id="username" name="username" required placeholder="Masukan username anda">
            </div>
            <button type="submit" class="btn btn-danger btn-block rounded-pill">Kirim</button>
        </form>
        <a href="HalamanSignIn.html" id="backToLogin">Kembali ke Halaman Utama</a>
    </div>

<script>
    document.getElementById('resetForm').addEventListener('submit', function(event) {
        const username = document.getElementById('username').value;
        if (!username) {
            event.preventDefault(); // Hentikan jika kosong
            alert('Silakan masukkan username.');
        } else {
            alert('Kode reset telah dikirim ke username: ' + username);
             // Form akan tetap dikirim ke Verification.php
        }
    });
</script>
</body>
</html>
