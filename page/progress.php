<?php
session_start();
include "db.php";  // koneksi database
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id'];

// Ambil kelas yang sudah dibeli user dengan status Completed
$sql = "SELECT k.id_kelas, k.nama_kelas, k.badge 
        FROM tb_kelas k
        JOIN tb_transaksi t ON k.id_kelas = t.id_kelas
        WHERE t.id_user = ? AND t.status = 'Completed'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelas Saya - KelasKita</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        header {
            background-color: rgb(22, 137, 213);
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Kelas Saya</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($kelas = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($kelas['nama_kelas']); ?></h2>
                            <?php if (!empty($kelas['badge'])): ?>
                                <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded"><?php echo htmlspecialchars($kelas['badge']); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php
                        // Ambil materi untuk kelas ini
                        $sql_materi = "SELECT id_materi, urutan, judul_materi FROM tb_materi WHERE id_kelas = ? ORDER BY urutan ASC";
                        $stmt_materi = $conn->prepare($sql_materi);
                        $stmt_materi->bind_param("i", $kelas['id_kelas']);
                        $stmt_materi->execute();
                        $result_materi = $stmt_materi->get_result();
                        ?>

                        <ul class="list-disc pl-5 space-y-1">
                            <?php while ($materi = $result_materi->fetch_assoc()): ?>
                                <?php
                                // Cek progress materi sudah selesai atau belum
                                $sql_progress = "SELECT 1 FROM tb_progress_kelas WHERE id_user = ? AND id_kelas = ? AND id_materi = ? LIMIT 1";
                                $stmt_progress = $conn->prepare($sql_progress);
                                $stmt_progress->bind_param("iii", $id_user, $kelas['id_kelas'], $materi['id_materi']);
                                $stmt_progress->execute();
                                $stmt_progress->store_result();
                                $selesai = $stmt_progress->num_rows > 0;
                                ?>
                                <li class="<?php echo $selesai ? 'text-green-600 font-semibold' : 'text-gray-700'; ?>">
                                    <?php echo htmlspecialchars($materi['urutan'] . '. ' . $materi['judul_materi']); ?>
                                    <?php if ($selesai): ?>
                                        <span class="ml-2 text-sm text-green-500 font-bold">&#10003; Selesai</span>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>

                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600">Belum ada kelas yang dibeli.</p>
        <?php endif; ?>

    </div>
    <?php include "../Views/footer.php"; ?>
</body>

</html>