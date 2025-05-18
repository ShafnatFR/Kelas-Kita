<?php
session_start();
include_once('db.php');

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM tbuser WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$mentor = $result->fetch_assoc();

// Jika form disubmit, update data profil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $instagram = $_POST['instagram'];
    $linkedin = $_POST['linkdin'];
    $github = $_POST['github'];
 
    
    // Update profil mentor di database
    $stmt = $conn->prepare("UPDATE tbuser SET username=?, email=?, bio=?, instagram=?, linkdin=?, github=? WHERE username=?");
    $stmt->bind_param("sssssss", $username, $email, $bio, $instagram, $linkedin, $github, $username);
    $stmt->execute();

    // Redirect ke halaman profil jika berhasil
    header("Location: mentor-profil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container label {
            font-weight: 600;
        }
        .form-container .form-control {
            margin-bottom: 15px;
        }
        .form-container .btn-primary {
            background-color: #4caf50;
            border-color: #4caf50;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="form-container">
            <h2>Edit Profil Mentor</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($mentor['username']) ?>" >
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?= htmlspecialchars($mentor['email']) ?>" >
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Biography</label>
                    <textarea class="form-control" id="bio" name="bio" rows="4"><?= htmlspecialchars($mentor['bio']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="instagram" class="form-label">Instagram</label>
                    <input type="text" class="form-control" id="instagram" name="instagram" value="<?= htmlspecialchars($mentor['instagram']) ?>" placeholder="instagram.com/username">
                </div>

                <div class="mb-3">
                    <label for="linkdin" class="form-label">LinkedIn</label>
                    <input type="text" class="form-control" id="linkdin" name="linkdin" value="<?= htmlspecialchars($mentor['linkdin']) ?>" placeholder="linkedin.com/in/username">
                </div>

                <div class="mb-3">
                    <label for="github" class="form-label">GitHub</label>
                    <input type="text" class="form-control" id="github" name="github" value="<?= htmlspecialchars($mentor['github']) ?>" placeholder="github.com/c/username">
                </div>


                <button type="submit" class="btn btn-primary btn-lg">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
