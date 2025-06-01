<?php
include "db.php";

$message = '';

// Ambil daftar kelas untuk dropdown
$kelasList = [];
$result = $conn->query("SELECT id_kelas, nama_kelas FROM tb_kelas ORDER BY nama_kelas ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $kelasList[] = $row;
    }
}

// Proses form jika submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori_report = $_POST['kategori_report'] ?? '';
    $keterangan_report = $_POST['keterangan_report'] ?? '';
    $id_kelas = intval($_POST['id_kelas'] ?? 0);
    $id_user = intval($_POST['id_user'] ?? 0);

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
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Form Laporan - Kelas Kita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-blue-700 text-white p-8 text-center text-4xl font-extrabold">
        Form Laporan Pengguna
    </header>

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

                <!-- Ganti id_user sesuai dengan user yang login -->
                <input type="hidden" name="id_user" value="456" />

                <button type="submit" class="w-full bg-blue-700 text-white py-4 rounded-lg text-xl font-semibold hover:bg-blue-800 transition">
                    Kirim Laporan
                </button>
            </form>
        </div>
    </main>

    <footer class="bg-gray-200 text-center p-4 text-gray-600">
        &copy; <?= date("Y") ?> Kelas Kita. All rights reserved.
    </footer>

</body>
</html>
