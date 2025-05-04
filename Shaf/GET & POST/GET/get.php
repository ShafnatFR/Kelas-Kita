<?php
// $_GET["nama"] = "Shafnat";
// $_GET["NIM"] = "607012400075";
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
// var_dump($_GET);
?>

<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <h1>Daftar Mahasiswa</h1>
        <?php foreach($mahasiswa as $mhs) : ?>
            <ul>
                <li><img src="gambar/<?= $mhs["gambar"]; ?>"></li>
                <li><?= $mhs["NIM"]; ?></li>
                <li><?= $mhs["nama"]; ?></li>
            </ul>
        <?php endforeach;?>
    </body>
</html>