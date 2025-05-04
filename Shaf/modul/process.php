<?php
$nama = $_POST['nama'];
$password = $_POST['password'];

$errors = [];

if (empty($nama)){
    $errors[] = 'Nama tidak boleh kosong.';
}
?>