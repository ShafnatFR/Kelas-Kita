<?php
include('db.php'); // koneksi database

// Validasi dan ambil ID user dari parameter GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID tidak valid.");
}

// Ambil data user berdasarkan ID
$query = "SELECT * FROM tbUser WHERE id = $id";
$result = $conn->query($query);
if (!$result || $result->num_rows == 0) {
    die("Pengguna tidak ditemukan.");
}
$user = $result->fetch_assoc();

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notifikasi_postingan_baru = isset($_POST['notifikasi_postingan_baru']) ? 1 : 0;
    $komentar_baru = isset($_POST['komentar_baru']) ? 1 : 0;

    $updateQuery = "
        UPDATE tbUser 
        SET 
            notifikasi_postingan_baru = $notifikasi_postingan_baru,
            komentar_baru = $komentar_baru 
        WHERE id = $id
    ";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: notifikasi.php");
        exit();
    } else {
        echo "Gagal memperbarui notifikasi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Notifikasi</title>
    <style>
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="checkbox"] {
            margin-right: 8px;
        }
    </style>
</head>
<body>

<h1>Edit Preferensi Notifikasi</h1>

<form method="POST" action="">
    <label>
        <input type="checkbox" name="notifikasi_postingan_baru" 
            <?= $user['notifikasi_postingan_baru'] == 1 ? 'checked' : '' ?>>
        Notifikasi Postingan Baru
    </label>

    <label>
        <input type="checkbox" name="komentar_baru" 
            <?= $user['komentar_baru'] == 1 ? 'checked' : '' ?>>
        Notifikasi Komentar Baru
    </label>

    <br><br>
    <button type="submit">Simpan</button>
</form>

<br>
<a href="notifikasi.php">Kembali ke Daftar Notifikasi</a>

</body>
</html>
