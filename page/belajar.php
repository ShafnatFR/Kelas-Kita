<?php
include("db.php");

// Ambil data dari database
$sql_dokumen = "SELECT * FROM tb_dokumen";
$result_dokumen = mysqli_query($conn, $sql_dokumen);

$sql_video = "SELECT * FROM tb_video";
$result_video = mysqli_query($conn, $sql_video);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Belajar - KelasKita</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

    <header class="bg-indigo-700 text-white py-6">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">📚 Belajar di KelasKita</h1>
            <p class="text-sm mt-1">Tonton video pembelajaran dan download materi PDF dari mentor.</p>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10 space-y-16">

        <!-- VIDEO PEMBELAJARAN -->
        <section>
            <h2 class="text-2xl font-semibold text-indigo-700 mb-6">🎥 Video Pembelajaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($vid = mysqli_fetch_assoc($result_video)): ?>
                    <div class="bg-white rounded-lg shadow p-4 flex flex-col h-full">
                        <h3 class="text-base font-semibold mb-3 truncate w-full" title="<?= htmlspecialchars(basename($vid['file_path_video'])) ?>">
                            <?= htmlspecialchars(mb_strimwidth(basename($vid['file_path_video']), 0, 40, "...")) ?>
                        </h3>

                        <video class="rounded-md mb-4 w-full h-48 object-cover" controls>
                            <source src="<?= htmlspecialchars($vid['file_path_video']) ?>" type="video/mp4">
                            Browser Anda tidak mendukung pemutar video.
                        </video>

                        <a href="<?= htmlspecialchars($vid['file_path_video']) ?>" download
                            class="bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-2 px-4 rounded text-center transition">
                            📥 Download Video
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- MATERI PDF -->
        <section>
            <h2 class="text-2xl font-semibold text-indigo-700 mb-6">📄 Materi Pembelajaran (PDF)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($doc = mysqli_fetch_assoc($result_dokumen)): ?>
                    <div class="bg-white rounded-lg shadow p-4 flex flex-col h-full">
                        <h3 class="text-lg font-bold mb-2 truncate" title="<?= htmlspecialchars(basename($doc['file_path_dokumen'])) ?>">
                            <?= htmlspecialchars(mb_strimwidth(basename($doc['file_path_dokumen']), 0, 40, "...")) ?>
                        </h3>
                        <p class="text-sm text-gray-500 mb-3">File: <?= htmlspecialchars($doc['file_path_dokumen']) ?></p>
                        <a href="<?= htmlspecialchars($doc['file_path_dokumen']) ?>" download
                            class="bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-2 px-4 rounded text-center transition">
                            📥 Download PDF
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

    </main>

    <?php include "../Views/footer.php"; ?>
</body>

</html>
<?php mysqli_close($conn); ?>
