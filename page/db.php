<?php
$servername = "localhost"; // Sesuaikan jika menggunakan host berbeda
$username = "root";        // Sesuaikan dengan username MySQL Anda
$password = "";            // Sesuaikan dengan password MySQL Anda
$dbname = "kelasKita_baru";     // Nama database

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
// hgf
// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>