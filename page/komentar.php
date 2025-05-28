<?php
session_start();
include "db.php";

$id_user = $_SESSION['id_user'] ?? 1;     // default testing
$id_kelas = $_SESSION['id_kelas'] ?? 101; // default testing

$msg = '';

// Proses kirim komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_komentar'])) {
    $isi = trim($_POST['isi']);
    if ($isi === '') {
        $msg = "Komentar tidak boleh kosong.";
    } else {
        $stmt = $conn->prepare("INSERT INTO tb_komentar (isi, id_user, id_kelas) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $isi, $id_user, $id_kelas);
        if ($stmt->execute()) {
            $msg = "Komentar berhasil dikirim.";
        } else {
            $msg = "Gagal mengirim komentar: " . $conn->error;
        }
        $stmt->close();
    }
}

// Ambil komentar
$result = $conn->query("SELECT * FROM tb_komentar WHERE id_kelas = $id_kelas ORDER BY id_komentar DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Komentar Kelas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

    <!-- Header dengan warna biru VSC (#007ACC) via inline style -->
    <header style="background-color:rgb(22, 137, 213);" class="text-white py-6">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">📝 Komentar KelasKita</h1>
            <p class="text-sm mt-1">Berbagi pendapat dan pertanyaan tentang materi kelas.</p>
        </div>
    </header>

    <div class="max-w-3xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold text-[#007ACC] mb-4">💬 Komentar untuk Kelas ID <?= htmlspecialchars($id_kelas) ?></h1>

        <?php if ($msg): ?>
            <div class="mb-4 px-4 py-3 rounded <?= str_contains($msg, 'berhasil') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="mb-8 bg-white p-6 rounded shadow">
            <label for="isi" class="block text-sm font-medium mb-2">Tulis Komentar</label>
            <textarea name="isi" id="isi" rows="4" class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-[#007ACC]" placeholder="Tulis komentar kamu di sini..." required></textarea>
            <button type="submit" name="submit_komentar" class="mt-4 bg-[#007ACC] text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Kirim Komentar
            </button>
        </form>

        <h2 class="text-xl font-semibold mb-4">Komentar Sebelumnya</h2>

        <div class="space-y-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bg-white p-4 rounded shadow">
                        <div class="text-sm text-gray-500 mb-1">User ID: <?= htmlspecialchars($row['id_user']) ?></div>
                        <p class="text-gray-800"><?= nl2br(htmlspecialchars($row['isi'])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-600">Belum ada komentar.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include "../Views/footer.php"; ?>
</body>

</html>