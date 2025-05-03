<?php
include('db.php'); // koneksi database

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data user berdasarkan ID
    $query = "SELECT * FROM tbUser WHERE id = $id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User tidak ditemukan.";
        exit();
    }
} else {
    echo "ID tidak ditemukan.";
    exit();
}

// Proses update data
if (isset($_POST['update'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $deskripsi = $_POST['deskripsi'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $update = "UPDATE tbUser SET 
                first_name='$first_name',
                last_name='$last_name',
                deskripsi='$deskripsi',
                username='$username',
                email='$email'
                WHERE id=$id";

    if ($conn->query($update) === TRUE) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!-- HTML Form Edit -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
</head>
<body>

<h1>Edit Data User</h1>

<form method="POST" action="">
    <label>Nama Depan</label><br>
    <input type="text" name="first_name" value="<?= $user['first_name']; ?>" required><br><br>

    <label>Nama Belakang</label><br>
    <input type="text" name="last_name" value="<?= $user['last_name']; ?>"><br><br>

    <label>Deskripsi</label><br>
    <textarea name="deskripsi"><?= $user['deskripsi']; ?></textarea><br><br>

    <label>Username</label><br>
    <input type="text" name="username" value="<?= $user['username']; ?>" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" value="<?= $user['email']; ?>" required><br><br>

    <button type="submit" name="update">Update</button>
</form>

<br>
<a href="profile.php">Kembali ke Profil</a>

</body>
</html>
