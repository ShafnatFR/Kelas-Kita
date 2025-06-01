<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "KelasKita_baru";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Cek apakah user sudah login
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

if ($user_id == 0) {
    die("Anda harus login untuk mengakses halaman ini.");
}

// Tandai materi sebagai selesai
if (isset($_GET['id'])) {
    $materi_id = $_GET['id'];

    // Update status materi di database
    $stmt = $conn->prepare("SELECT * FROM tb_materi where id_materi = ?");
    $stmt->bind_param("i", $materi_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Materi ditemukan, lakukan tindakan yang diperlukan
        $stmt = $conn->prepare("INSERT INTO tb_progress_kelas (id_materi, id_kelas, id_user) VALUES (?, ?, ?)");
        $kelas_id = $result->fetch_assoc()['id_kelas'];
        $stmt->bind_param("iii", $materi_id, $kelas_id, $user_id);
        $stmt->execute();
    }

    $stmt->close();
}

// Redirect ke halaman sebelumnya
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
