<?php
session_start();

include "db.php";

// Redirect ke login jika belum login
if (!isset($_SESSION['id_user'])) {
    header("Location: /Kelas-Kita/page/HalamanSignIn.php");

    exit();
}

$id_user = $_SESSION['id_user'];

// Pastikan id_kelas tersedia dari parameter GET
if (!isset($_GET['id_kelas'])) {
    echo "Kelas tidak ditemukan.";
    exit();
}

$id_kelas = $_GET['id_kelas'];

// Proses kirim review jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bintang_review = $_POST['bintang_review'];
    $isi_review = $_POST['isi_review'];

    // Cek apakah user sudah memberi review sebelumnya (opsional)
    $cek = mysqli_query($conn, "SELECT * FROM tb_review WHERE id_user = $id_user AND id_kelas = $id_kelas");
    if (mysqli_num_rows($cek) > 0) {
        echo "<p class='text-red-500 text-center'>Anda sudah memberikan review untuk kelas ini.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO tb_review (bintang_review, isi_review, id_user, id_kelas) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $bintang_review, $isi_review, $id_user, $id_kelas);
        if ($stmt->execute()) {
            echo "<p class='text-green-500 text-center'>Review berhasil ditambahkan.</p>";
        } else {
            echo "<p class='text-red-500 text-center'>Gagal menambahkan review.</p>";
        }
        $stmt->close();
    }
}

// Cek apakah user sudah membeli kelas
$cekTransaksi = mysqli_query($conn, "SELECT * FROM tb_transaksi WHERE id_user = $id_user AND id_kelas = $id_kelas AND status = 'Completed'");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Review Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <div class="max-w-3xl mx-auto mt-10 p-4 bg-white rounded shadow">
        <h2 class="text-2xl font-semibold mb-4">Review Kelas</h2>

        <?php if (mysqli_num_rows($cekTransaksi) > 0): ?>
        <!-- Form review hanya jika user telah membeli -->
        <form action="" method="POST" class="mb-6">
            <label class="block mb-2 font-medium">Rating:</label>
            <select name="bintang_review" class="w-full p-2 border rounded mb-4" required>
                <option value="">Pilih rating</option>
                <option value="1">1 - Buruk</option>
                <option value="2">2 - Kurang</option>
                <option value="3">3 - Cukup</option>
                <option value="4">4 - Baik</option>
                <option value="5">5 - Sangat Baik</option>
            </select>

            <label class="block mb-2 font-medium">Tulis review:</label>
            <textarea name="isi_review" class="w-full p-2 border rounded mb-4" required></textarea>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim Review</button>
        </form>
        <?php else: ?>
        <p class="text-red-500 font-semibold">Anda belum membeli kelas ini, tidak bisa memberikan review.</p>
        <?php endif; ?>

        <hr class="my-6">

        <h3 class="text-xl font-semibold mb-4">Semua Review</h3>

        <?php
        $getReview = mysqli_query($conn, "SELECT r.*, u.first_name, u.last_name FROM tb_review r
            JOIN tb_user u ON r.id_user = u.id_user
            WHERE r.id_kelas = $id_kelas ORDER BY r.tgl_review DESC");

        if (mysqli_num_rows($getReview) > 0):
            while ($review = mysqli_fetch_assoc($getReview)):
        ?>
            <div class="mb-4 p-4 border rounded shadow-sm bg-gray-100">
                <div class="font-semibold text-lg"><?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?></div>
                <div class="text-yellow-500">Rating: <?= $review['bintang_review'] ?>/5</div>
                <p class="text-gray-800 mt-1"><?= htmlspecialchars($review['isi_review']) ?></p>
                <small class="text-gray-500"><?= $review['tgl_review'] ?></small>
            </div>
        <?php
            endwhile;
        else:
            echo "<p class='text-gray-500'>Belum ada review untuk kelas ini.</p>";
        endif;
        ?>
    </div>

</body>
</html>
