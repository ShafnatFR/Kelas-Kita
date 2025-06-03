<?php
include "db.php";

session_start();
$site_name = "Kelas Kita";

$message = '';

// Ambil daftar kelas
$kelasList = [];
$result = $conn->query("
    SELECT k.*
    FROM tb_transaksi t
    JOIN tb_kelas k ON t.id_kelas = k.id_kelas
    WHERE t.id_user = " . intval($_SESSION['id']) . " AND t.status = 'Completed'
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $kelasList[] = $row;
    }
}

// Proses submit laporan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori_report = $_POST['kategori_report'] ?? '';
    $keterangan_report = $_POST['keterangan_report'] ?? '';
    $id_kelas = intval($_POST['id_kelas'] ?? 0);
    $id_user = intval($_SESSION['id'] ?? 0);

    if ($kategori_report && $keterangan_report && $id_kelas > 0 && $id_user > 0) {
        $stmt = $conn->prepare("INSERT INTO tb_laporan (kategori_report, keterangan_report, id_kelas, id_user) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $kategori_report, $keterangan_report, $id_kelas, $id_user);

        if ($stmt->execute()) {
            $message = "Laporan berhasil dikirim.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Data tidak lengkap atau salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
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

<body class="bg-gray-100 min-h-screen flex flex-col">

    <?php include_once(__DIR__ . "/../Views/navbarbootstrap.php"); ?>

    <!-- Form utama -->
    <main class="flex-grow flex items-center justify-center p-8">
        <div class="w-full max-w-3xl bg-white p-10 rounded-lg shadow-lg">

            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded <?= strpos($message, 'berhasil') !== false ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">

                <label class="block">
                    <span class="font-semibold text-lg">Kategori Report</span>
                    <select name="kategori_report" required class="w-full mt-2 p-4 border border-gray-300 rounded-lg text-lg">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Penggunaan kata kasar">Penggunaan kata kasar</option>
                        <option value="Materi tidak relevan">Materi tidak relevan</option>
                        <option value="Kesalahan teknis">Kesalahan teknis</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </label>

                <label class="block">
                    <span class="font-semibold text-lg">Keterangan</span>
                    <textarea name="keterangan_report" rows="5" required placeholder="Jelaskan laporan kamu..." class="w-full mt-2 p-4 border border-gray-300 rounded-lg text-lg resize-y"></textarea>
                </label>

                <label class="block">
                    <span class="font-semibold text-lg">Pilih Kelas</span>
                    <select name="id_kelas" required class="w-full mt-2 p-4 border border-gray-300 rounded-lg text-lg">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelasList as $kelas): ?>
                            <option value="<?= $kelas['id_kelas'] ?>">
                                <?= htmlspecialchars($kelas['nama_kelas']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <button type="submit" class="w-full bg-blue-700 text-white py-4 rounded-lg text-xl font-semibold hover:bg-blue-800 transition">
                    Kirim Laporan
                </button>
            </form>
        </div>
    </main>

    <?php include "../Views/footerbootsrap.php"; ?>
</body>

</html>