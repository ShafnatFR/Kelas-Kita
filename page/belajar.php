<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Belajar</title>
    <link rel="stylesheet" href="belajar.css"> <!-- Link ke file CSS -->
</head>

<body>
    <main>
        <?php
        // Cek apakah video ada
        $videos = []; // Ganti dengan array video Anda, misalnya ['video1.mp4', 'video2.mp4']
        if (empty($videos)) {
            echo "<p>Tidak ada video.</p>";
        } else {
            foreach ($videos as $video) {
                echo "<div class='video-container'>";
                echo "<video width='600' controls>";
                echo "<source src='videos/$video' type='video/mp4'>"; // Ganti dengan path video Anda
                echo "Browser Anda tidak mendukung tag video.";
                echo "</video>";
                echo "</div>";
            }
        }


        // Mengatur nama file PDF yang akan di-download
        $pdf_file = 'file_pelajaran.pdf';

        // Mengecek apakah file PDF ada
        if (file_exists($pdf_file)) {
            echo '<p><a href="' . $pdf_file . '" download>Download PDF Pelajaran</a></p>';
        } else {
            echo '<p>Maaf, file PDF tidak tersedia.</p>';
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2025 Kelas Kita. Hak Cipta Dilindungi.</p>
    </footer>
</body>

</html>