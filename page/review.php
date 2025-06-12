<?php
include "db.php";
session_start();

// Simulasi login
$id_user = $_SESSION['id'] ?? 1; 
$id_kelas = $_GET['id_kelas'] ?? 1;
$site_name = "Kelas Kita";

// Ambil info user & kelas
$query_info = "
    SELECT u.first_name, u.last_name, k.nama_kelas 
    FROM tb_user u 
    JOIN tb_review r ON u.id_user = r.id_user 
    JOIN tb_kelas k ON k.id_kelas = r.id_kelas 
    WHERE u.id_user = $id_user AND k.id_kelas = $id_kelas
    LIMIT 1
";
$result_info = $conn->query($query_info);
$info = $result_info->fetch_assoc();
$nama_user = $info ? $info['first_name'] . ' ' . $info['last_name'] : 'Peserta';

// Proses form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bintang = $_POST['bintang'];
    $isi_review = $conn->real_escape_string($_POST['isi_review']);

    $sql = "INSERT INTO tb_review (bintang_review, isi_review, id_user, id_kelas)
            VALUES ('$bintang', '$isi_review', $id_user, $id_kelas)";
    if ($conn->query($sql) === TRUE) {
        header("Location: review.php?id_kelas=$id_kelas&success=1");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}

$review_query = "
    SELECT r.*, u.username, u.first_name, u.last_name
    FROM tb_review r 
    JOIN tb_user u ON r.id_user = u.id_user 
    WHERE r.id_kelas = $id_kelas
    ORDER BY r.tgl_review DESC
";
$review_result = $conn->query($review_query);

// Ambil info kelas
$query_info_kelas = "
    Select * from tb_kelas 
    where id_kelas = $id_kelas
";
$result_info = $conn->query($query_info_kelas);
$info_kelas = $result_info->fetch_assoc();
$nama_kelas = $info_kelas ? $info_kelas['nama_kelas'] : 'Kelas Tidak Ditemukan';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_name); ?> - <?php echo htmlspecialchars($site_tagline); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
        }

        .section-padding {
            padding: 80px 0;
        }

        .section-title {
            margin-bottom: 50px;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .card-icon {
            font-size: 2rem;
            color: #4a6cf7;
            height: 60px;
            width: 60px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 8px;
            transition: transform 0.3s ease;
            height: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }


        .partner-logo {
            height: 60px;
            object-fit: contain;
            filter: grayscale(100%);
            transition: all 0.3s;
        }

        .partner-logo:hover {
            filter: grayscale(0);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .newsletter-box {
            background-color: var(--light);
            padding: 30px;
            border-radius: 10px;
        }

        /* Hero Section Specific Style */
        .hero-section {
            background: linear-gradient(rgba(3, 3, 176, 0.47), rgb(15, 167, 232)), url('../assets/images/hero-bg.jpg');
            /* Pastikan path gambar hero benar */
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }

        .course-header {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7)),
                url('https://i.imgur.com/7Yj7NYJ.png');
            /* Ganti dengan background kamu */
            background-size: cover;
            background-position: center;
            color: white;
            border-radius: 12px;
            padding: 2rem;
        }

        .nav-tabs .nav-link.active {
            border-bottom: 2px solid red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include_once(__DIR__ . "/../Views/navbarbootstrap.php"); ?>

    <div class="container py-5">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h3 class="card-title">Review untuk Kelas: <span class="text-primary"><?= htmlspecialchars($nama_kelas) ?></span></h3>
                <p class="text-muted mb-4">Reviewer: <strong><?= htmlspecialchars($nama_user) ?></strong></p>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Review berhasil dikirim!</div>
                <?php elseif (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <!-- Form Review -->
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Rating (Bintang):</label>
                        <div class="star-rating d-flex flex-row-reverse justify-content-end">
                            <style>
                                .star-rating input[type="radio"] {
                                    display: none;
                                }

                                .star-rating label {
                                    font-size: 2rem;
                                    color: #ddd;
                                    cursor: pointer;
                                    transition: color 0.2s;
                                }

                                .star-rating input[type="radio"]:checked~label,
                                .star-rating label:hover,
                                .star-rating label:hover~label {
                                    color: #facc15;
                                }
                            </style>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?= $i ?>" name="bintang" value="<?= $i ?>" required>
                                <label for="star<?= $i ?>">&#9733;</label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="isi_review" class="form-label">Ulasan:</label>
                        <textarea name="isi_review" id="isi_review" rows="4" class="form-control" placeholder="Tulis pendapatmu..." required></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Kirim Review</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- List Review -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Ulasan dari Peserta Lain</h5>
                <?php if ($review_result && $review_result->num_rows > 0): ?>
                    <?php while ($rev = $review_result->fetch_assoc()): ?>
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold"><?= htmlspecialchars($rev['username']) ?></span>
                                <span class="text-warning"><?= str_repeat('⭐', (int)$rev['bintang_review']) ?></span>
                            </div>
                            <p class="mb-1"><?= htmlspecialchars($rev['isi_review']) ?></p>
                            <small class="text-muted"><?= date('d M Y H:i', strtotime($rev['tgl_review'])) ?></small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted">Belum ada ulasan untuk kelas ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include_once(__DIR__ . "/../Views/footerbootsrap.php"); ?>
</body>


</html>