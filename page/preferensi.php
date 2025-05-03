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
    <title>Profil - Menu Setting</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

<h1>Profil Pengguna</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Bahasa</th>
            <th>Zona Waktu</th>
            <th>Balasan Ke Komentar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $user['id']; ?></td>
            <td><?= $user['bahasa']; ?></td>
            <td><?=$user['zona_waktu']; ?></td>
            <td><?php
            if ($user['balasan ke komentar'] = '1'){
                echo 'Ya';
            } else {
                echo 'Tidak';
            }
            ?></td>
            <td>
                <a href="editPreferensi.php?id=<?= $user['id']; ?>">Edit</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<br>
<a href="../index.php">Kembali ke Menu Setting</a>

</body>
</html>
