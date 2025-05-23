<?php
session_start();
include "db.php";

// Pastikan pengguna sudah login dan memiliki role sebagai murid
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'murid') {
    header("Location: HalamanSignIn.php");
    exit();
}

$error_message = "";
$success_message = "";

// Jika user mengkonfirmasi untuk menjadi mentor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_mentor'])) {
    $user_id = $_SESSION['id'];

    // Debug: Cek user_id
    if (empty($user_id)) {
        $error_message = "ID user tidak ditemukan di session.";
    } else {
        // Memeriksa apakah ID user ada di tb_user
        $check_stmt = $conn->prepare("SELECT id_user FROM tb_user WHERE id_user = ?");
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            // Cek apakah role sudah 'mentor'
            if ($_SESSION['role'] !== 'mentor') {
                // Update role menjadi 'mentor' di tabel tb_user
                $stmt = $conn->prepare("UPDATE tb_user SET role = 'mentor' WHERE id_user = ?");
                $stmt->bind_param("i", $user_id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        // Update session role
                        $_SESSION['role'] = 'mentor';
                        
                        // Menambahkan record ke tb_mentor
                        $mentor_stmt = $conn->prepare("INSERT INTO tb_mentor (id_user, status) VALUES (?, 'Aktif')");
                        $mentor_stmt->bind_param("i", $user_id);
                        $mentor_stmt->execute(); // Execute the query
                        $mentor_stmt->close();  // Close the statement

                        // Set pesan sukses
                        $success_message = "Anda berhasil menjadi mentor! Anda akan diarahkan ke dashboard.";
                        
                        // Langsung redirect tanpa delay
                        header("Location: mentor-dashboard.php");
                        exit();
                    } else {
                        $error_message = "Tidak ada perubahan data. Mungkin Anda sudah menjadi mentor.";
                    }
                } else {
                    $error_message = "Gagal mengubah status: " . $conn->error;
                }
                $stmt->close();
            } else {
                $error_message = "Anda sudah menjadi mentor.";
            }
        } else {
            $error_message = "ID pengguna tidak ditemukan di database.";
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menjadi Mentor - KelasKita</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styless.css">
</head>
<body>
    <div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-lg" style="max-width: 500px; width: 100%;">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-chalkboard-teacher text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="text-primary font-weight-bold">Menjadi Mentor</h3>
                </div>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success_message); ?>
                        <div class="spinner-border spinner-border-sm ml-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-4">
                        Apakah Anda yakin ingin menjadi mentor di KelasKita?<br>
                        Sebagai mentor, Anda dapat membuat kelas dan mengajar murid lainnya.
                    </p>

                    <div class="row">
                        <div class="col-6">
                            <form method="POST">
                                <input type="hidden" name="confirm_mentor" value="true">
                                <button type="submit" class="btn btn-success btn-block py-2">
                                    <i class="fas fa-check mr-2"></i>
                                    Ya, Saya Yakin
                                </button>
                            </form>
                        </div>
                        <div class="col-6">
                            <a href="index.php" class="btn btn-secondary btn-block py-2">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                        </div>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted">
                            Setelah menjadi mentor, Anda akan diarahkan ke dashboard mentor.
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
