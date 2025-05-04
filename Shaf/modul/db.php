<?php
$koneksi = mysqli_connect("localhost", "root", "", "kelaskita");
if (!$koneksi){
    die ("Koneksi gagal:" . mysqli_connect_error());
}
?>