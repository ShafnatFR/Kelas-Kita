<?php
session_start();
include_once('db.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: signIn.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM tbuser WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$mentor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <img src="<?= (!empty($mentor['fotoProfil']) && file_exists('../upload/' . $mentor['fotoProfil'])) 
                    ? '../upload/' . htmlspecialchars($mentor['fotoProfil']) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($mentor['username']) . '&background=0D8ABC&color=fff&rounded=true&size=128' ?>" 
                    alt="Foto Mentor" class="rounded-circle mb-3" width="120" height="120">
                <h3 class="card-title"><?= htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']) ?></h3>
                <p><strong>Keahlian:</strong> <?= isset($mentor['keahlian']) ? htmlspecialchars($mentor['keahlian']) : 'Belum ada keahlian yang diisi' ?></p>
                <p><strong>Bio:</strong> <?= isset($mentor['bio']) ? htmlspecialchars($mentor['bio']) : 'Belum ada bio yang diisi' ?></p>
                <a href="edit-profile.php" class="btn btn-warning">Edit Profil</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
