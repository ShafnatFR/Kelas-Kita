<?php
include('db.php'); // koneksi database

// Sanitasi ID dari GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mendapatkan data pengguna berdasarkan ID
$query = "SELECT * FROM tbUser WHERE id = $id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bahasa = $conn->real_escape_string($_POST['bahasa']);
    $zona_waktu = $conn->real_escape_string($_POST['zona_waktu']);
    $balasan_komentar = isset($_POST['balasan_komentar']) ? 1 : 0;

    // Perbarui menggunakan nama kolom yang benar: zona_waktu dan balasan_komentar
    $updateQuery = "
        UPDATE tbUser 
        SET bahasa = '$bahasa', zona_waktu = '$zona_waktu', balasan_komentar = $balasan_komentar 
        WHERE id = $id
    ";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: editPreferensi.php?id=$id"); // Setelah sukses, reload halaman
        exit();
    } else {
        echo "Error: " . $updateQuery . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Preferensi</title>
</head>
<body>

<h1>Edit Preferensi Pengguna</h1>

<form method="POST" action="">
    <label for="bahasa">Bahasa:</label><br>
    <select id="bahasa" name="bahasa" required>
        <option value="Indonesia" <?= $user['bahasa'] == 'Indonesia' ? 'selected' : ''; ?>>Indonesia</option>
        <option value="Inggris" <?= $user['bahasa'] == 'Inggris' ? 'selected' : ''; ?>>Inggris</option>
        <option value="Jepang" <?= $user['bahasa'] == 'Jepang' ? 'selected' : ''; ?>>Jepang</option>
    </select><br><br>
    
    <label for="zona_waktu">Zona Waktu:</label><br>
    <select id="zona_waktu" name="zona_waktu" required>
        <option value="Jakarta" <?= $user['zona_waktu'] == 'Jakarta' ? 'selected' : ''; ?>>Jakarta</option>
        <option value="London" <?= $user['zona_waktu'] == 'London' ? 'selected' : ''; ?>>London</option>
        <option value="Tokyo" <?= $user['zona_waktu'] == 'Tokyo' ? 'selected' : ''; ?>>Tokyo</option>
    </select><br><br>
    
    <label for="balasan_ke_komentar">Balasan Ke Komentar:</label><br>
    <input type="checkbox" id="balasan_ke_komentar" name="balasan_ke_komentar" <?= $user['balasan_ke_komentar'] == 1 ? 'checked' : ''; ?>><br><br>
    
    <button type="submit">Simpan Preferensi</button>
</form>

<br>
<a href="index.php">Kembali ke Menu Setting</a>

</body>
</html>
