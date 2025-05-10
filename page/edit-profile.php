<?php
session_start();
include_once('db.php');

// Cek jika pengguna sudah login dan sudah berrole mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: signIn.php");
    exit();
}

// Ambil data profil mentor
$username = $_SESSION['username'];
$query = "SELECT * FROM tbuser WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$mentor = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bio = $_POST['bio'];
    $keahlian = $_POST['keahlian'];

    // Update profil mentor
    $update_query = "UPDATE tbuser SET bio=?, keahlian=? WHERE username=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sss", $bio, $keahlian, $username);
    $stmt->execute();

    echo "Profil berhasil diperbarui!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="edit-profile">
        <h1>Edit Profil Mentor</h1>
        <form method="POST">
            <div>
                <label for="bio">Bio:</label>
                <textarea name="bio" id="bio"><?= htmlspecialchars($mentor['bio']) ?></textarea>
            </div>
            <div>
                <label for="keahlian">Keahlian:</label>
                <textarea name="keahlian" id="keahlian"><?= htmlspecialchars($mentor['keahlian']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
