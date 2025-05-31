<?php
session_start();
include 'db.php';

// Ambil data dari form
$kategori = $_POST['kategori_report'] ?? '';
$keterangan = $_POST['keterangan_report'] ?? '';
$id_kelas = $_POST['id_kelas'] ?? '';
$id_user = $_SESSION['id_user'] ?? null;

// Validasi data
if ($kategori && $keterangan && $id_kelas && $id_user) {
    // Escape string dan casting
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $keterangan = mysqli_real_escape_string($conn, $keterangan);
    $id_kelas = (int)$id_kelas;
    $id_user = (int)$id_user;

    // Simpan ke tabel tb_laporan
    $query = "INSERT INTO tb_laporan (kategori_report, keterangan_report, id_kelas, id_user)
            VALUES ('$kategori', '$keterangan', $id_kelas, $id_user)";

    if (mysqli_query($conn, $query)) {
        header("Location: belajar.php?lapor=berhasil");
        exit;
    } else {
        echo "Gagal menyimpan laporan: " . mysqli_error($conn);
    }
} else {
    echo "Data tidak lengkap.";
}
