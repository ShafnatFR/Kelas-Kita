
<?php
session_start();
include_once('db.php');

// Cek jika pengguna sudah login dan sudah berrole mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: signIn.php");
    exit();
}

// Ambil data kursus berdasarkan ID yang dipilih
if (!isset($_GET['id'])) {
    echo "Kursus tidak ditemukan.";
    exit();
}

$course_id = $_GET['id'];

// Hapus kursus dari database
$query = "DELETE FROM tbkelas WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);

if ($stmt->execute()) {
    echo "Kursus berhasil dihapus!";
    header("Location: mentor-dashboard.php");  // Arahkan kembali ke dashboard mentor
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
