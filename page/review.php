<?php
include "db.php";

// Simulasi login
$id_user = 1;
$id_kelas = $_GET['id_kelas'] ?? 1;

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
$nama_kelas = $info ? $info['nama_kelas'] : 'Kelas';

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
    SELECT r.*, u.first_name, u.last_name 
    FROM tb_review r 
    JOIN tb_user u ON r.id_user = u.id_user 
    WHERE r.id_kelas = $id_kelas
    ORDER BY r.tgl_review DESC
";
$review_result = $conn->query($review_query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Review Kelas - <?= htmlspecialchars($nama_kelas) ?> | Kelas Kita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

    <!-- Header Utama -->
    <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-8 flex justify-between items-center">
        <a href="detailKelas.php?id_kelas=<?= $id_kelas ?>" class="text-3xl font-bold text-blue-600 hover:underline">
            KelasKita
        </a>
    </div>
</header>


    <!-- Konten Utama -->
    <main class="max-w-3xl mx-auto p-6 bg-white mt-6 rounded shadow pb-10 mb-10">
        <h2 class="text-2xl font-bold mb-4">
            Review untuk Kelas: <span class="text-blue-600"><?= htmlspecialchars($nama_kelas) ?>WebProgramming</span>
        </h2>

        <p class="mb-6 text-gray-600">Reviewer: <strong><?= htmlspecialchars($nama_user) ?></strong></p>

        <?php if (isset($_GET['success'])): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">Review berhasil dikirim!</div>
        <?php elseif (isset($error)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= $error ?></div>
        <?php endif; ?>

        <!-- Form Review -->
        <form method="POST" class="mb-8 space-y-4">
            <div>
                <label class="block mb-1 font-semibold">Rating (Bintang):</label>
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

                <div class="star-rating flex flex-row-reverse justify-end">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" name="bintang" value="<?= $i ?>" required>
                        <label for="star<?= $i ?>">&#9733;</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Ulasan:</label>
                <textarea name="isi_review" rows="4" class="w-full border rounded p-2" placeholder="Tulis pendapatmu..." required></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Kirim Review
                </button>
            </div>
        </form>

        <!-- List Review -->
        <h2 class="text-xl font-bold mb-3">Ulasan dari Peserta Lain</h2>
        <div class="space-y-4">
            <?php if ($review_result && $review_result->num_rows > 0): ?>
                <?php while ($rev = $review_result->fetch_assoc()): ?>
                    <div class="border p-4 rounded bg-gray-50">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-semibold"><?= htmlspecialchars($rev['first_name'] . ' ' . $rev['last_name']) ?></span>
                            <span class="text-yellow-500"><?= str_repeat('⭐', (int)$rev['bintang_review']) ?></span>
                        </div>
                        <p class="text-gray-700"><?= htmlspecialchars($rev['isi_review']) ?></p>
                        <p class="text-sm text-gray-500"><?= date('d M Y H:i', strtotime($rev['tgl_review'])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-600">Belum ada ulasan untuk kelas ini.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include "../Views/footer.php"; ?>
</body>

</html>
