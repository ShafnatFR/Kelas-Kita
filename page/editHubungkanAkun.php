<?php
include('db.php'); // koneksi database

// Ambil ID pengguna yang ingin diedit
$id = $_GET['id'];

// Query untuk mendapatkan data pengguna berdasarkan ID
$query = "SELECT * FROM tbUser WHERE id = $id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $instagram = $_POST['instagram'];
    $twitter = $_POST['twitter'];
    $linkdin = $_POST['linkdin'];
    $github = $_POST['github'];

    // Query untuk memperbarui informasi akun media sosial pengguna
    $updateQuery = "UPDATE tbUser SET email = '$email', instagram = '$instagram', twitter = '$twitter', linkdin = '$linkdin', github = '$github' WHERE id = $id";
    
    if ($conn->query($updateQuery) === TRUE) {
        echo "Akun berhasil diperbarui!";
        header("Location: editHubungkanAkun.php?id=$id"); // Setelah sukses, reload halaman
    } else {
        echo "Error: " . $updateQuery . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Hubungkan Akun</title>
</head>
<body>

<h1>Edit Hubungkan Akun Pengguna</h1>

<form method="POST" action="">
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" value="<?= $user['email']; ?>" required><br><br>
    
    <label for="instagram">Instagram:</label><br>
    <input type="text" id="instagram" name="instagram" value="<?= $user['instagram']; ?>"><br><br>
    
    <label for="twitter">Twitter:</label><br>
    <input type="text" id="twitter" name="twitter" value="<?= $user['twitter']; ?>"><br><br>
    
    <label for="linkdin">LinkedIn:</label><br>
    <input type="text" id="linkdin" name="linkdin" value="<?= $user['linkdin']; ?>"><br><br>
    
    <label for="github">GitHub:</label><br>
    <input type="text" id="github" name="github" value="<?= $user['github']; ?>"><br><br>
    
    <button type="submit">Simpan Perubahan</button>
</form>

<br>
<a href="hubungkan-akun.php">Kembali ke Hubungkan Akun</a>

</body>
</html>