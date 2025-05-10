<?php
session_start();
include_once('db.php');

if (!isset($_SESSION['username'])) {
    header("Location: signIn.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['confirm'] == 'yes') {
        // Ganti role menjadi mentor di database
        $username = $_SESSION['username'];
        
        // Update role pengguna menjadi mentor
        $sql = "UPDATE tbuser SET role='mentor' WHERE username='$username'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['role'] = 'mentor';  // Update session role
            header("Location: mentor-dashboard.php");  // Arahkan ke halaman dashboard mentor
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Perubahan role dibatalkan.";
    }
} else {
    ?>
    <div class="container py-5">
        <h2>Apakah Anda yakin ingin menjadi Mentor?</h2>
        <form method="POST">
            <button type="submit" name="confirm" value="yes" class="btn btn-success">Ya, Saya Yakin</button>
            <button type="submit" name="confirm" value="no" class="btn btn-danger">Tidak</button>
        </form>
    </div>
    <?php
}
?>
