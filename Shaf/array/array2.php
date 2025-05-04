<?php
$mahasiswa = [
    ["Shafnat Fuaini Ramadhan", "607012400075", "D3 Sistem Informasi", "shafnatfuainiramadhan@gmail.com"],
    ["Shaf", "607012400070", "D3 Sistem Informasi Manajemen", "shaf@gmai.com"],
];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Daftar mahasiswa</title>
    </head>
    <body>
        <h1>Daftar mahasiswa</h1>
            <?php
            foreach ($mahasiswa as $mhs):?>
                <ul>
                    <li>Nama: <?= $mhs[0];?></li>
                    <li>NRP: <?= $mhs[1];?></li>
                    <li>Jurusan: <?= $mhs[2];?></li>
                    <li>Email: <?= $mhs[3];?></li>
                </ul>
            <?php endforeach?>
    </body>
</html>