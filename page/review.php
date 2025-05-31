<?php
// db.php harus ada dan berisi koneksi mysqli ke database
include "db.php";

$alert = "";
date_default_timezone_set('Asia/Jakarta'); // Set timezone WIB

// Ambil kelas dari GET jika ada
$selected_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : null;

// Proses kirim review
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $bintang_review = $_POST['bintang_review'];
    $isi_review     = mysqli_real_escape_string($conn, $_POST['isi_review']);
    $id_user        = $_POST['id_user'];
    $id_kelas       = $_POST['id_kelas'];
    $tgl_review     = date("Y-m-d H:i:s");

    $query = "INSERT INTO tb_review (bintang_review, isi_review, tgl_review, id_user, id_kelas)
            VALUES ('$bintang_review', '$isi_review', '$tgl_review', '$id_user', '$id_kelas')";

    if (mysqli_query($conn, $query)) {
        // Redirect ke halaman dengan GET id_kelas agar review terbaru muncul
        header("Location: ".$_SERVER['PHP_SELF']."?id_kelas=".$id_kelas);
        exit();
    } else {
        $alert = "<div class='bg-red-100 text-red-800 p-3 rounded-lg mb-4'>Gagal mengirim review: " . mysqli_error($conn) . "</div>";
    }
}

// Data kelas statis
$kelas_list = [
    1 => 'Pemrograman Web',
    2 => 'UI/UX Desain',
    3 => 'Bahasa Jepang',
    4 => 'Bahasa Inggris',
    5 => 'Kalkulus',
    6 => 'Dasar Manajemen',
    7 => 'Ekonomi',
];

// Ambil review jika kelas dipilih
$review_result = null;
if ($selected_kelas) {
    $selected_kelas = intval($selected_kelas); // Amankan input
    $review_result = mysqli_query($conn, "SELECT * FROM tb_review WHERE id_kelas = $selected_kelas ORDER BY tgl_review DESC");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Review Kelas - KelasKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function setRating(value) {
            document.getElementById('bintang_review').value = value;
            let stars = document.querySelectorAll('.star');
            stars.forEach((star, index) => {
                if (index < value) {
                    star.classList.add('text-yellow-400');
                    star.classList.remove('text-gray-300');
                } else {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-yellow-400');
                }
            });
        }
    </script>
</head>

<body class="bg-gray-100">

<header style="background-color: rgb(22, 137, 213);" class="text-white py-6 shadow-md">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Review KelasKita</h1>
    </div>
</header>

<main class="p-6 max-w-3xl mx-auto">

    <!-- Pilih Kelas -->
    <div class="bg-white p-6 rounded-2xl shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4 text-center">Pilih Kelas yang Ingin Direview</h2>
        <form method="GET">
            <select name="id_kelas" class="w-full border rounded-lg p-2" onchange="this.form.submit()" required>
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($kelas_list as $id => $nama): ?>
                    <option value="<?= $id ?>" <?= $selected_kelas == $id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($nama) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($selected_kelas): ?>
        <!-- Form Review -->
        <div class="bg-white p-6 rounded-2xl shadow-md mb-8">
            <h2 class="text-2xl font-bold mb-4 text-center">Tulis Review</h2>
            <?= $alert ?>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block font-medium mb-1">Rating:</label>
                    <div class="flex space-x-1 text-3xl cursor-pointer">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star text-gray-300" onclick="setRating(<?= $i ?>)">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="bintang_review" id="bintang_review" required>
                </div>

                <div>
                    <label class="block font-medium mb-1">Isi Review:</label>
                    <textarea name="isi_review" rows="4" maxlength="100" required
                        class="w-full border rounded-lg p-2" placeholder="Tulis pendapatmu tentang kelas ini..."></textarea>
                </div>

                <!-- Hidden Inputs -->
                <input type="hidden" name="id_user" value="1"> <!-- Ganti dengan user session jika ada -->
                <input type="hidden" name="id_kelas" value="<?= $selected_kelas ?>">

                <div class="flex justify-end">
                    <button
                        type="submit"
                        name="submit_review"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition"
                    >
                        Kirim Review
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Review -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
            <h2 class="text-xl font-bold mb-4">Review untuk Kelas Ini</h2>
            <?php if ($review_result && mysqli_num_rows($review_result) > 0): ?>
                <div class="space-y-4">
                    <?php while ($review = mysqli_fetch_assoc($review_result)): ?>
                        <div class="border-b pb-3">
                            <div class="flex items-center mb-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="text-xl <?= $i <= $review['bintang_review'] ? 'text-yellow-400' : 'text-gray-300' ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-700"><?= htmlspecialchars($review['isi_review']) ?></p>
                            <p class="text-sm text-gray-500"><?= date('d M Y H:i', strtotime($review['tgl_review'])) ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">Belum ada review untuk kelas ini.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
