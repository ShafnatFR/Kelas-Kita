<?php
session_start();
include_once('db.php');

// Cek jika pengguna sudah login dan berperan sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: signIn.php");
    exit();
}

// Ambil ID kursus dari URL
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validasi ID kursus
if ($course_id <= 0) {
    echo "Kursus tidak ditemukan.";
    exit;
}

// Cek apakah kursus yang dimaksud milik mentor yang sedang login
$query = "SELECT * FROM tbkelas WHERE id = ? AND instructor = ?";
$stmt = mysqli_prepare($conn, $query);
$username = $_SESSION['username'];  // Menyimpan nama pengguna yang sedang login
mysqli_stmt_bind_param($stmt, "is", $course_id, $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Jika kursus tidak ditemukan atau bukan milik mentor
if (mysqli_num_rows($result) == 0) {
    echo "Kursus tidak ditemukan atau Anda tidak memiliki hak untuk menghapus kursus ini.";
    exit();
}

// Hapus kursus dari database
$query_delete = "DELETE FROM tbkelas WHERE id = ?";
$stmt_delete = mysqli_prepare($conn, $query_delete);
mysqli_stmt_bind_param($stmt_delete, "i", $course_id);

if (mysqli_stmt_execute($stmt_delete)) {
    echo "Kursus berhasil dihapus!";
    header("Location: mentor-dashboard.php");  // Redirect ke dashboard setelah penghapusan
    exit();
} else {
    echo "Terjadi kesalahan saat menghapus kursus.";
}
?>
