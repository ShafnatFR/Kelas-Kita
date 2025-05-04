<?php
$mahasiswa = [
    [
        "NIM" => "607012400075",
        "nama" => "Shafnat Fuaini Ramadhan",
        "gambar" => "20250429_0007.jpg"
    ],
    [
        "NIM" => "607012400076",
        "nama" => "Shafnat",
        "gambar" => "20250429_0010.jpg"
    ]
];
?>

<!DOCTYPE html>
<html>
<head><title>Daftar Mahasiswa</title></head>
<body>
    <h1>Daftar Mahasiswa</h1>
    <ul>
        <?php foreach($mahasiswa as $mhs) : ?>
            <li>
                <a href="get copy 2.php?nama=<?= urlencode($mhs["nama"]); ?>&NIM=<?= $mhs["NIM"]; ?>">
                    <?= $mhs["nama"]; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
