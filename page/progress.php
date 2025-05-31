<?php
include "db.php";

// Koneksi Database
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Contoh user dan kelas (ganti sesuai aplikasi kamu)
$id_user  = 1;
$id_kelas = 1;

// Jika form disubmit untuk update progres
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_progres'])) {
    $checkedMateri = isset($_POST['materi']) ? $_POST['materi'] : [];

    // Hapus progres lama
    mysqli_query($conn, "DELETE FROM tb_progress_kelas WHERE id_user=$id_user AND id_kelas=$id_kelas");

    // Tambahkan progres baru
    foreach ($checkedMateri as $id_materi) {
        $id_materi = intval($id_materi);
        mysqli_query($conn, "INSERT INTO tb_progress_kelas (id_kelas, id_user, id_materi) VALUES ($id_kelas, $id_user, $id_materi)");
    }
}

// Ambil semua materi di kelas
$materi = [];
$result = mysqli_query($conn, "SELECT id_materi, judul_materi FROM tb_materi WHERE id_kelas=$id_kelas ORDER BY urutan ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $materi[$row['id_materi']] = $row['judul_materi'];
}

// Ambil progres user
$progres = [];
$result2 = mysqli_query($conn, "SELECT id_materi FROM tb_progress_kelas WHERE id_user=$id_user AND id_kelas=$id_kelas");
while ($row2 = mysqli_fetch_assoc($result2)) {
    $progres[] = $row2['id_materi'];
}

// Hitung progres
$total_materi = count($materi);
$materi_selesai = count($progres);
$persen = $total_materi > 0 ? round(($materi_selesai / $total_materi) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Progress Belajar | Kelas Kita</title>
    <style>
        body {
            background: #f6f8fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: rgb(22, 137, 213);
            color: white;
            padding: 16px 24px;
            text-align: center;
            font-size: 20px;
        }

        .container {
            max-width: 560px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            padding: 32px 24px;
        }

        h2 {
            color: #333;
        }

        .progress-container {
            width: 100%;
            background: #eee;
            border-radius: 8px;
            margin-bottom: 15px;
            height: 28px;
            overflow: hidden;
        }

        .summary {
            margin-bottom: 22px;
            color: #555;
        }

        ul {
            padding-left: 20px;
        }

        li {
            margin-bottom: 7px;
        }

        label {
            cursor: pointer;
            user-select: none;
        }

        .empty {
            color: #aaa;
            font-style: italic;
        }

        button {
            background-color: rgb(59, 94, 190);
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: rgb(59, 94, 190);
        }
    </style>
</head>

<body>
    <header>
        Kelas Kita - Progress Belajar
    </header>
    <div class="container">
        <h2>Progres Kelas Anda</h2>
        <div class="progress-container">
            <div class="progress-bar"><?php echo $persen; ?>%</div>
        </div>
        <div class="summary">
            <?php echo $materi_selesai; ?> dari <?php echo $total_materi; ?> materi selesai
        </div>

        <form method="POST" action="">
            <h3>Checklist Materi:</h3>
            <ul>
                <?php if ($total_materi > 0): ?>
                    <?php foreach ($materi as $id_materi => $judul_materi): ?>
                        <li>
                            <label>
                                <input type="checkbox" name="materi[]" value="<?php echo $id_materi; ?>" <?php echo in_array($id_materi, $progres) ? 'checked' : ''; ?>>
                                <?php echo htmlspecialchars($judul_materi); ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="empty">Belum ada materi di kelas ini.</li>
                <?php endif; ?>
            </ul>
            <button type="submit" name="update_progres">Simpan Progres</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>