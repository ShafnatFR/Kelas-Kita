<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "kelaskita_baru"; // Ganti sesuai nama database kamu
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data transaksi dengan join ke tb_user dan tb_kelas
$sql = "SELECT t.id_transaksi, t.tgl_transaksi, CONCAT(u.first_name, ' ', u.last_name) AS nama_pelanggan, 
        COALESCE(k.harga, 0) AS total, t.status
        FROM tb_transaksi t
        LEFT JOIN tb_user u ON t.id_user = u.id_user
        LEFT JOIN tb_kelas k ON t.id_kelas = k.id_kelas
        ORDER BY t.tgl_transaksi DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Riwayat Transaksi</h1>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>Nama Pelanggan</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id_transaksi']); ?></td>
            <td><?= date("d-m-Y H:i", strtotime($row['tgl_transaksi'])); ?></td>
            <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
            <td>Rp<?= number_format($row['total'], 0, ',', '.'); ?></td>
            <td><?= htmlspecialchars($row['status']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>Tidak ada transaksi ditemukan.</p>
<?php endif; ?>

<?php $conn->close(); ?>

</body>
</html>
