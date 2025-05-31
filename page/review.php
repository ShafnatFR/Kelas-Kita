<?php
session_start();
include "db.php";

// Pastikan user login
if (!isset($_SESSION['id_user'])) {
    die("Anda harus login terlebih dahulu.");
}

$id_user = $_SESSION['id_user'];

// Ambil id_kelas dari URL
if (!isset($_GET['id_kelas']) || !is_numeric($_GET['id_kelas'])) {
    die("ID kelas tidak ditemukan.");
}
$id_kelas = (int)$_GET['id_kelas'];

// Cek apakah kelas ada
$cekKelas = $con->prepare("SELECT nama_kelas FROM tb_kelas WHERE id_kelas = ?");
$cekKelas->bind_param("i", $id_kelas);
$cekKelas->execute();
$cekKelas->store_result();
if ($cekKelas->num_rows === 0) {
    die("Kelas tidak tersedia.");
}
$cekKelas->bind_result($nama_kelas);
$cekKelas->fetch();
$cekKelas->close();

// Cek apakah user sudah membeli kelas dengan transaksi 'Completed'
$cekTransaksi = $con->prepare("SELECT COUNT(*) FROM tb_transaksi WHERE id_user = ? AND id_kelas = ? AND status = 'Completed'");
$cekTransaksi->bind_param("ii", $id_user, $id_kelas);
$cekTransaksi->execute();
$cekTransaksi->bind_result($sudahBeli);
$cekTransaksi->fetch();
$cekTransaksi->close();

// Handle kirim review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $bintang = $_POST['bintang_review'] ?? '';
    $isi_review = trim($_POST['isi_review'] ?? '');

    if ($sudahBeli == 0) {
        $error = "Anda belum membeli kelas ini.";
    } elseif (!$bintang || !$isi_review) {
        $error = "Semua field wajib diisi.";
    } else {
        $stmt = $con->prepare("INSERT INTO tb_review (bintang_review, isi_review, id_user, id_kelas) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $bintang, $isi_review, $id_user, $id_kelas);
        if ($stmt->execute()) {
            header("Location: review_kelas.php?id_kelas=$id_kelas");
            exit;
        } else {
            $error = "Gagal menyimpan review.";
        }
        $stmt->close();
    }
}

// Ambil semua review kelas
$queryReviews = $con->prepare("
    SELECT r.bintang_review, r.isi_review, r.tgl_review, u.first_name, u.last_name 
    FROM tb_review r 
    JOIN tb_user u ON r.id_user = u.id_user 
    WHERE r.id_kelas = ? 
    ORDER BY r.tgl_review DESC
");
$queryReviews->bind_param("i", $id_kelas);
$queryReviews->execute();
$resultReviews = $queryReviews->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Review Kelas - <?= htmlspecialchars($nama_kelas) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Review untuk: <?= htmlspecialchars($nama_kelas) ?></h1>

    <!-- Form Review -->
    <?php if ($sudahBeli > 0): ?>
        <form method="POST" class="mb-6">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 text-red-700 p-2 mb-3 rounded"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <label class="block mb-2 font-semibold">Rating Bintang</label>
            <select name="bintang_review" class="w-full p-2 border rounded mb-4" required>
                <option value="">-- Pilih Rating --</option>
                <option value="1">1 ★</option>
                <option value="2">2 ★★</option>
                <option value="3">3 ★★★</option>
                <option value="4">4 ★★★★</option>
                <option value="5">5 ★★★★★</option>
            </select>

            <label class="block mb-2 font-semibold">Isi Review</label>
            <textarea name="isi_review" rows="4" class="w-full p-2 border rounded mb-4" required></textarea>

            <button type="submit" name="submit_review" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim Review</button>
        </form>
    <?php else: ?>
        <div class="mb-6 p-4 bg-yellow-100 border border-yellow-300 rounded">
            Anda belum membeli kelas ini. Hanya pembeli yang bisa memberikan review.
        </div>
    <?php endif; ?>

    <!-- List Review -->
    <h2 class="text-xl font-semibold mb-3">Ulasan dari Pelajar Lain</h2>
    <?php if ($resultReviews->num_rows == 0): ?>
        <p class="text-gray-500">Belum ada ulasan untuk kelas ini.</p>
    <?php else: ?>
        <ul class="space-y-4">
            <?php while($row = $resultReviews->fetch_assoc()): ?>
                <li class="border p-4 rounded bg-gray-50">
                    <div class="flex items-center justify-between mb-1">
                        <strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></strong>
                        <span class="text-yellow-500">
                            <?= str_repeat('★', (int)$row['bintang_review']) ?>
                            <?= str_repeat('☆', 5 - (int)$row['bintang_review']) ?>
                        </span>
                    </div>
                    <p class="mb-2"><?= htmlspecialchars($row['isi_review']) ?></p>
                    <small class="text-gray-500"><?= date('d M Y, H:i', strtotime($row['tgl_review'])) ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>
<?php include "../Views/footer.php"; ?>
</body>
</html>
