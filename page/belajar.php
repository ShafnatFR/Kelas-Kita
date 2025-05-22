<?php
// Koneksi database
$conn = new mysqli("localhost", "root", "", "kelaskita");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tampilkan data materi
echo "<h2>Daftar Materi</h2>";
$sql_materi = "SELECT id_materi, deskripsi_m FROM materi";
$result_materi = $conn->query($sql_materi);
if ($result_materi->num_rows > 0) {
    while ($m = $result_materi->fetch_assoc()) {
        echo "<div>";
        echo "<h3>Materi ID: " . $m['id_materi'] . "</h3>";
        echo "<p>" . $m['deskripsi_m'] . "</p>";
        echo "</div><hr>";
    }
} else {
    echo "Data materi tidak ditemukan.<br>";
}

// Tampilkan data video
echo "<h2>Video Pembelajaran</h2>";
$sql_video = "SELECT id_video, nama_video, deskripsi_v FROM video";
$result_video = $conn->query($sql_video);
if ($result_video->num_rows > 0) {
    while ($v = $result_video->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($v['nama_video']) . "</h3>";
        echo "<p>" . htmlspecialchars($v['deskripsi_v']) . "</p>";
        // Asumsi file video ada di folder files/video/ dengan nama file nama_video + .mp4
        $videoFile = "files/video/" . $v['nama_video'] . ".mp4";
        if (file_exists($videoFile)) {
            echo '<video width="320" height="240" controls>
                    <source src="' . $videoFile . '" type="video/mp4">
                    Browser Anda tidak mendukung video.
                  </video>';
        } else {
            echo "<p>Video belum tersedia.</p>";
        }
        echo "</div><hr>";
    }
} else {
    echo "Data video tidak ditemukan.<br>";
}

// Tampilkan data dokumen (PDF)
echo "<h2>Dokumen Pembelajaran (PDF)</h2>";
$sql_dokumen = "SELECT id_dokumen, nama_dokumen, deskripsi_d FROM dokumen";
$result_dokumen = $conn->query($sql_dokumen);
if ($result_dokumen->num_rows > 0) {
    while ($d = $result_dokumen->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($d['nama_dokumen']) . "</h3>";
        echo "<p>" . htmlspecialchars($d['deskripsi_d']) . "</p>";
        // Asumsi file pdf ada di folder files/pdf/ dengan nama file nama_dokumen + .pdf
        $pdfFile = "files/pdf/" . $d['nama_dokumen'] . ".pdf";
        if (file_exists($pdfFile)) {
            echo '<a href="' . $pdfFile . '" download>Download PDF</a>';
        } else {
            echo "<p>Dokumen belum tersedia.</p>";
        }
        echo "</div><hr>";
    }
} else {
    echo "Data dokumen tidak ditemukan.<br>";
}

$conn->close();
?>
