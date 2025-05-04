<?php
if( !isset($_GET["nama"]) || 
    !isset($_GET["NIM"])
    // !isset($_GET["email"]) ||
    // !isset($_GET["gambar"]) 
    ){
    header("Location: get copy.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Data Mahasiswa</title></head>
<body>
    <h1>Data Mahasiswa dari URL</h1>
    <ul>
        <li>Nama: <?= $_GET["nama"] ?? "Tidak ada nama"; ?></li>
        <li>NIM: <?= $_GET["NIM"] ?? "Tidak ada NIM"; ?></li>
    </ul>
    <a href="get copy.php">Kembali</a>
</body>
</html>
