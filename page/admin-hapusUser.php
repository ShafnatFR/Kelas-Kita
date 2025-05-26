<?php
// File: delete_user.php
session_start();
require 'db.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginAdmin.php");
    exit();
}

// Cek apakah ada parameter id_user
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID User tidak valid!";
    header("Location: admin-dashboard.php");
    exit();
}

$id_user = intval($_GET['id']);

// Cek apakah user exists
$checkUser = $conn->prepare("SELECT id_user, username, CONCAT(first_name, ' ', last_name) as fullname FROM tb_user WHERE id_user = ?");
$checkUser->bind_param("i", $id_user);
$checkUser->execute();
$result = $checkUser->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "User tidak ditemukan!";
    header("Location: admin-dashboard.php");
    exit();
}

$userData = $result->fetch_assoc();

// Proses hapus jika konfirmasi diterima
if (isset($_POST['confirm_delete'])) {
    $conn->begin_transaction();
    
    try {
        // Hapus data terkait user terlebih dahulu (sesuaikan dengan struktur database Anda)
        
        // 1. Hapus dari tb_keranjang jika ada
        $deleteKeranjang = $conn->prepare("DELETE FROM tb_keranjang WHERE id_user = ?");
        $deleteKeranjang->bind_param("i", $id_user);
        $deleteKeranjang->execute();
        
        // 2. Hapus dari tb_transaksi yang terkait dengan keranjang user (jika ada relasi)
        // Sesuaikan query ini dengan struktur database Anda
        
        // 3. Hapus user utama
        $deleteUser = $conn->prepare("DELETE FROM tb_user WHERE id_user = ?");
        $deleteUser->bind_param("i", $id_user);
        $deleteUser->execute();
        
        if ($deleteUser->affected_rows > 0) {
            $conn->commit();
            $_SESSION['success'] = "User '{$userData['username']}' berhasil dihapus!";
        } else {
            throw new Exception("Gagal menghapus user!");
        }
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    
    header("Location: admin-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Hapus User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white text-center">
                        <h4><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus User</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-user-times fa-5x text-danger mb-3"></i>
                            <h5>Apakah Anda yakin ingin menghapus user ini?</h5>
                        </div>
                        
                        <div class="alert alert-warning">
                            <strong>Data yang akan dihapus:</strong><br>
                            <strong>ID:</strong> <?= htmlspecialchars($userData['id_user']) ?><br>
                            <strong>Username:</strong> <?= htmlspecialchars($userData['username']) ?><br>
                            <strong>Nama:</strong> <?= htmlspecialchars($userData['fullname']) ?>
                        </div>
                        
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan! 
                            Semua data yang terkait dengan user ini akan ikut terhapus.
                        </div>
                        
                        <form method="POST" class="d-inline">
                            <button type="submit" name="confirm_delete" class="btn btn-danger me-2">
                                <i class="fas fa-trash me-2"></i>Ya, Hapus User
                            </button>
                        </form>
                        
                        <a href="admin-dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>