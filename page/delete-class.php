<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$id_kelas = $_GET['id_kelas'] ?? 0;

if ($id_kelas > 0) {
    // Pastikan kelas milik mentor yang login
    $check_stmt = $conn->prepare("
        SELECT k.nama_kelas FROM tb_kelas k
        JOIN tb_mentor m ON k.id_mentor = m.id_mentor
        WHERE k.id_kelas = ? AND m.id_user = ?
    ");
    $check_stmt->bind_param("ii", $id_kelas, $_SESSION['id']);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Hapus kelas
        $delete_stmt = $conn->prepare("DELETE FROM tb_kelas WHERE id_kelas = ?");
        $delete_stmt->bind_param("i", $id_kelas);
        
        if ($delete_stmt->execute()) {
            // Redirect dengan pesan sukses
            header("Location: kelola-kelas.php?deleted=1");
        } else {
            // Redirect dengan pesan error
            header("Location: kelola-kelas.php?error=1");
        }
    } else {
        // Kelas tidak ditemukan atau bukan milik mentor ini
        header("Location: kelola-kelas.php?error=2");
    }
} else {
    header("Location: kelola-kelas.php");
}

exit();
?>