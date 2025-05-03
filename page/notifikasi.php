<?php
include('db.php'); // koneksi database

// Ambil semua data user
$query = "SELECT * FROM tbUser";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi - Menu Setting</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
        }
        th {
            background-color: #eee;
        }
        a {
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>

<h1>Pengaturan Notifikasi Pengguna</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Notifikasi Postingan Baru</th>
            <th>Notifikasi Komentar Baru</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']); ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= $user['notifikasi_postingan_baru'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                <td><?= $user['komentar_baru'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                <td><a href="editNotifikasi.php?id=<?= $user['id']; ?>">Edit</a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<br>
<a href="index.php">⬅ Kembali ke Menu Setting</a>

</body>
</html>
