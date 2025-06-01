<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek login
if (!isset($_SESSION['id_user'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: /Kelas-Kita/page/HalamanSignIn.php");
    exit();
}

// Koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kelasKita_baru";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id_user = $_SESSION['id_user'];

// Ambil id_kelas dari GET
if (!isset($_GET['id_kelas'])) {
    echo "Kelas tidak ditemukan.";
    exit();
}
$id_kelas = (int)$_GET['id_kelas'];

// Cek apakah user sudah membeli kelas dan transaksi selesai
$cekTransaksi = $conn->query("SELECT * FROM tb_transaksi WHERE id_user = $id_user AND id_kelas = $id_kelas AND status = 'Completed'");

$pesan = "";

// Proses kirim review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $cekTransaksi->num_rows > 0) {
    $bintang_review = $_POST['bintang_review'];
    $isi_review = substr(trim($_POST['isi_review']), 0, 100);

    // Cek apakah user sudah memberi review untuk kelas ini
    $cek = $conn->query("SELECT * FROM tb_review WHERE id_user = $id_user AND id_kelas = $id_kelas");
    if ($cek->num_rows > 0) {
        $pesan = "<p class='text-red-500 text-center mb-4'>Anda sudah memberikan review untuk kelas ini.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO tb_review (bintang_review, isi_review, id_user, id_kelas) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $bintang_review, $isi_review, $id_user, $id_kelas);
        if ($stmt->execute()) {
            $pesan = "<p class='text-green-500 text-center mb-4'>Review berhasil ditambahkan.</p>";
        } else {
            $pesan = "<p class='text-red-500 text-center mb-4'>Gagal menambahkan review.</p>";
        }
        $stmt->close();
    }
}

// Ambil semua review kelas ini
$getReview = $conn->query("SELECT r.*, u.first_name, u.last_name FROM tb_review r
    JOIN tb_user u ON r.id_user = u.id_user
    WHERE r.id_kelas = $id_kelas ORDER BY r.tgl_review DESC");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Review Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center p-4">
    <div class="max-w-3xl w-full bg-white rounded shadow p-6 mt-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Review Kelas</h1>

        <?php if (!empty($pesan)) echo $pesan; ?>

        <?php if ($cekTransaksi->num_rows > 0): ?>
            <form action="" method="POST" class="mb-8">
                <label for="bintang_review" class="block mb-2 font-semibold">Rating:</label>
                <select name="bintang_review" id="bintang_review" required class="w-full p-2 border rounded mb-4">
                    <option value="">Pilih rating</option>
                    <option value="1">1 - Buruk</option>
                    <option value="2">2 - Kurang</option>
                    <option value="3">3 - Cukup</option>
                    <option value="4">4 - Baik</option>
                    <option value="5">5 - Sangat Baik</option>
                </select>

                <label for="isi_review" class="block mb-2 font-semibold">Tulis review (maks. 100 karakter):</label>
                <textarea id="isi_review" name="isi_review" maxlength="100" required
                    class="w-full p-2 border rounded mb-4" rows="4" placeholder="Tulis review Anda..."></textarea>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded transition">
                    Kirim Review
                </button>
            </form>
        <?php else: ?>
            <p class="text-center text-red-500 font-semibold mb-6">Anda belum membeli kelas ini, tidak bisa memberikan review.</p>
        <?php endif; ?>

        <hr class="my-6" />

        <h2 class="text-2xl font-semibold mb-4 text-center">Semua Review</h2>

        <?php if ($getReview->num_rows > 0): ?>
            <?php while ($review = $getReview->fetch_assoc()): ?>
                <div class="mb-5 p-4 border rounded shadow-sm bg-gray-100">
                    <div class="font-semibold text-lg mb-1">
                        <?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?>
                    </div>
                    <div class="text-yellow-500 mb-2">Rating: <?= htmlspecialchars($review['bintang_review']) ?>/5</div>
                    <p class="text-gray-800"><?= htmlspecialchars($review['isi_review']) ?></p>
                    <small class="text-gray-500"><?= htmlspecialchars($review['tgl_review']) ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-gray-500">Belum ada review untuk kelas ini.</p>
        <?php endif; ?>
    </div>
</body>
</html>
