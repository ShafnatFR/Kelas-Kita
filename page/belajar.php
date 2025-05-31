<?php
include("db.php");
session_start();

// Dummy user login
if (!isset($_SESSION['id_user'])) {
    $_SESSION['id_user'] = 1;
}

// Ambil data dokumen dan video
$sql_dokumen = "SELECT * FROM tb_dokumen";
$result_dokumen = mysqli_query($conn, $sql_dokumen);

$sql_video = "SELECT * FROM tb_video";
$result_video = mysqli_query($conn, $sql_video);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Belajar - KelasKita</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-50 text-gray-900 font-sans">

    <header style="background-color: rgb(22, 137, 213);" class="text-white py-6 shadow-md">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-extrabold tracking-tight">📚 Belajar di KelasKita</h1>
            <p class="text-sm mt-1 text-blue-100">Tonton video dan download materi PDF dari mentor.</p>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10 space-y-16">

        <?php if (isset($_GET['lapor']) && $_GET['lapor'] === 'berhasil'): ?>
            <div class="bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-lg shadow-md mb-6 flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Laporan Anda berhasil dikirim. Terima kasih atas kontribusinya!</span>
            </div>
        <?php endif; ?>

        <!-- Video Pembelajaran -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 border-b-2 pb-2" style="border-color: rgb(22, 137, 213); color: rgb(22, 137, 213);">🎥 Video Pembelajaran</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php while ($vid = mysqli_fetch_assoc($result_video)): ?>
                    <?php
                    $videoFileName = basename($vid['file_path_video']);
                    $videoTitle = htmlspecialchars(mb_strimwidth($videoFileName, 0, 40, "..."));
                    $videoPath = htmlspecialchars($vid['file_path_video']);
                    ?>
                    <div class="bg-white rounded-lg shadow-lg p-5 flex flex-col hover:shadow-xl transition-shadow duration-300">
                        <h3 class="font-semibold mb-3 truncate" title="<?= $videoFileName ?>" style="color: rgb(22, 137, 213);"><?= $videoTitle ?></h3>
                        <video controls class="rounded-md mb-4 w-full h-48 object-cover bg-black">
                            <source src="<?= $videoPath ?>" type="video/mp4" />
                            Browser Anda tidak mendukung video.
                        </video>
                        <a href="<?= $videoPath ?>" download
                            class="mt-auto text-white py-2 rounded-lg text-center font-semibold transition-colors duration-300"
                            style="background-color: rgb(22, 137, 213);"
                            onmouseover="this.style.backgroundColor='rgb(15, 95, 148)'" onmouseout="this.style.backgroundColor='rgb(22, 137, 213)'">
                            📥 Download Video
                        </a>
                        <button
                            type="button"
                            onclick="openReportModal('video', <?= (int)$vid['id_video'] ?>)"
                            class="mt-3 text-sm font-semibold underline"
                            style="color: #dc2626;" /* merah Tailwind */
                            onmouseover="this.style.color='#991b1b'"
                            onmouseout="this.style.color='#dc2626'">🚨 Laporkan Video</button>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Materi PDF -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 border-b-2 pb-2" style="border-color: rgb(22, 137, 213); color: rgb(22, 137, 213);">📄 Materi Pembelajaran (PDF)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php while ($doc = mysqli_fetch_assoc($result_dokumen)): ?>
                    <?php
                    $docFileName = basename($doc['file_path_dokumen']);
                    $docTitle = htmlspecialchars(mb_strimwidth($docFileName, 0, 40, "..."));
                    $docPath = htmlspecialchars($doc['file_path_dokumen']);
                    ?>
                    <div class="bg-white rounded-lg shadow-lg p-5 flex flex-col hover:shadow-xl transition-shadow duration-300">
                        <h3 class="font-semibold mb-3 truncate" title="<?= $docFileName ?>" style="color: rgb(22, 137, 213);"><?= $docTitle ?></h3>
                        <a href="<?= $docPath ?>" download
                            class="mt-auto text-white py-2 rounded-lg text-center font-semibold transition-colors duration-300"
                            style="background-color: rgb(22, 137, 213);"
                            onmouseover="this.style.backgroundColor='rgb(15, 95, 148)'" onmouseout="this.style.backgroundColor='rgb(22, 137, 213)'">
                            📥 Download PDF
                        </a>
                        <button
                            type="button"
                            onclick="openReportModal('pdf', <?= (int)$doc['id_dokumen'] ?>)"
                            class="mt-3 text-sm font-semibold underline"
                            style="color: #dc2626;"
                            onmouseover="this.style.color='#991b1b'"
                            onmouseout="this.style.color='#dc2626'">🚨 Laporkan PDF</button>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

    </main>

    <!-- Modal Lapor -->
    <div id="reportModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-60 flex items-center justify-center">
        <div class="bg-white p-7 rounded-lg shadow-xl w-full max-w-md relative">
            <h2 class="text-xl font-bold mb-5" style="color: rgb(22, 137, 213);">Laporkan Konten</h2>
            <form action="lapor.php" method="POST" class="space-y-4">
                <input type="hidden" name="tipe_konten" id="tipeKonten" value="">
                <input type="hidden" name="id_kelas" id="idKelas" value="0">
                <label class="block text-sm font-medium text-gray-700">Kategori Laporan</label>
                <select name="kategori_report" required class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[rgb(22,137,213)] focus:border-transparent">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Penggunaan kata kasar">Penggunaan kata kasar</option>
                    <option value="Materi tidak relevan">Materi tidak relevan</option>
                    <option value="Pelanggaran hak cipta">Pelanggaran hak cipta</option>
                </select>
                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea name="keterangan_report" required rows="4" class="w-full border border-gray-300 rounded-md p-2 resize-y focus:outline-none focus:ring-2 focus:ring-[rgb(22,137,213)] focus:border-transparent"></textarea>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeReportModal()" class="px-5 py-2 rounded-md bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold transition-colors duration-300">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-md text-white font-semibold transition-colors duration-300" style="background-color: rgb(22, 137, 213);" onmouseover="this.style.backgroundColor='rgb(15, 95, 148)'" onmouseout="this.style.backgroundColor='rgb(22, 137, 213)'">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <?php include "../Views/footer.php"; ?>

    <!-- Script Modal Report -->
    <script>
        function openReportModal(tipe, id_kelas) {
            document.getElementById('reportModal').classList.remove('hidden');
            document.getElementById('tipeKonten').value = tipe;
            document.getElementById('idKelas').value = id_kelas;
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
            document.getElementById('tipeKonten').value = "";
            document.getElementById('idKelas').value = "0";
        }
    </script>
</body>

</html>