<?php
// Query untuk mengambil data user untuk tabel - sesuaikan dengan kolom yang ada
$tb_user = $conn->prepare("
    SELECT id_user, 
           CASE 
               WHEN first_name IS NOT NULL AND last_name IS NOT NULL 
               THEN CONCAT(first_name, ' ', last_name)
               ELSE username
           END AS fullname, 
           username
    FROM tb_user
    ORDER BY id_user ASC
");
if (!$tb_user) {
    die("Error preparing user statement: " . $conn->error);
}
$tb_user->execute();
$tb_userResult = $tb_user->get_result();
$tb_userData = $tb_userResult->fetch_all(MYSQLI_ASSOC);
?>