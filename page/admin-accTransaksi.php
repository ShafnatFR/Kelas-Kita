<?php
session_start();
require 'db.php'; // Pastikan sudah menghubungkan ke database Anda

// Pastikan pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminLogin.php");
    exit();
}

// Cek koneksi database
if (!$conn) {
    // Ideally, db.php would handle this, but as a fallback:
    $_SESSION['message'] = "Koneksi database gagal.";
    $_SESSION['message_type'] = "danger";
    header("Location: kelolaTransaksi.php");
    exit();
}

// Pastikan ID transaksi disediakan di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_transaksi = $_GET['id'];

    // Mulai transaksi database untuk memastikan atomicity
    $conn->begin_transaction();

    try {
        // 1. Update status transaksi menjadi 'acc'
        $stmt_transaksi = $conn->prepare("UPDATE tb_transaksi SET status = 'Completed' WHERE id_transaksi = ?");
        if (!$stmt_transaksi) {
            throw new Exception("Error preparing transaction update: " . $conn->error);
        }
        $stmt_transaksi->bind_param("s", $id_transaksi); // 's' karena id_transaksi kemungkinan string (UUID)
        $stmt_transaksi->execute();
        if ($stmt_transaksi->affected_rows === 0) {
            throw new Exception("Transaksi dengan ID '$id_transaksi' tidak ditemukan atau status sudah 'acc'.");
        }
        $stmt_transaksi->close();

        // 2. Ambil id_kelas dari tb_keranjang berdasarkan id_transaksi
        $stmt_kelas_id = $conn->prepare("
            SELECT kk.id_kelas
            FROM tb_keranjang kk
            JOIN tb_transaksi t ON kk.id_keranjang = t.id_keranjang
            WHERE t.id_transaksi = ?
        ");
        if (!$stmt_kelas_id) {
            throw new Exception("Error preparing class ID query: " . $conn->error);
        }
        $stmt_kelas_id->bind_param("s", $id_transaksi);
        $stmt_kelas_id->execute();
        $result_kelas_id = $stmt_kelas_id->get_result();
        $kelas_data = $result_kelas_id->fetch_assoc();
        $stmt_kelas_id->close();

        if (!$kelas_data || empty($kelas_data['id_kelas'])) {
            throw new Exception("ID Kelas tidak ditemukan untuk transaksi ini.");
        }
        $id_kelas = $kelas_data['id_kelas'];

        // 3. Update status publikasi kelas menjadi 'aktif'
        $stmt_kelas_status = $conn->prepare("UPDATE tb_kelas SET status_publikasi = 'approved' WHERE id_kelas = ?");
        if (!$stmt_kelas_status) {
            throw new Exception("Error preparing class status update: " . $conn->error);
        }
        $stmt_kelas_status->bind_param("s", $id_kelas); // 's' sesuai dengan tipe id_kelas Anda
        $stmt_kelas_status->execute();
        // Optional: Check if the class was actually updated.
        // If it was already 'aktif', affected_rows might be 0.
        // if ($stmt_kelas_status->affected_rows === 0) {
        //     // Consider if this is an error or acceptable. For now, it's fine.
        // }
        $stmt_kelas_status->close();

        // Commit transaksi jika semua berhasil
        $conn->commit();
        $_SESSION['message'] = "Transaksi berhasil di-ACC dan kelas diaktifkan!";
        $_SESSION['message_type'] = "success";

    } catch (Exception $e) {
        // Rollback transaksi jika ada kesalahan
        $conn->rollback();
        $_SESSION['message'] = "Gagal meng-ACC transaksi: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    } finally {
        // Pastikan koneksi ditutup
        if ($conn) {
            $conn->close();
        }
    }

    header("Location: kelolaTransaksi.php");
    exit();

} else {
    // Jika ID transaksi tidak disediakan
    $_SESSION['message'] = "ID Transaksi tidak ditemukan.";
    $_SESSION['message_type'] = "danger";
    header("Location: admin-kelolaTransaksi.php");
    exit();
}
?>