<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Laporan - KelasKita</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-50 text-gray-900 font-sans">

    <header style="background-color: rgb(22, 137, 213);" class="text-white py-6 shadow-md">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-extrabold tracking-tight">📋 Daftar Laporan</h1>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10">

        <?php if (isset($_GET['lapor']) && $_GET['lapor'] === 'berhasil'): ?>
            <div class="bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-lg shadow-md mb-6 flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Laporan Anda berhasil dikirim. Terima kasih atas kontribusinya!</span>
            </div>
        <?php endif; ?>

        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead style="background-color: rgb(22, 137, 213); color: white;">
                <tr>
                    <th class="py-3 px-6 text-left">ID Laporan</th>
                    <th class="py-3 px-6 text-left">Kategori</th>
                    <th class="py-3 px-6 text-left">Keterangan</th>
                    <th class="py-3 px-6 text-left">ID Kelas</th>
                    <th class="py-3 px-6 text-left">ID User</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tb_laporan ORDER BY id_report DESC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr class="border-b hover:bg-gray-100">';
                        echo '<td class="py-3 px-6">' . htmlspecialchars($row['id_report']) . '</td>';
                        echo '<td class="py-3 px-6">' . htmlspecialchars($row['kategori_report']) . '</td>';
                        echo '<td class="py-3 px-6">' . htmlspecialchars($row['keterangan_report']) . '</td>';
                        echo '<td class="py-3 px-6">' . htmlspecialchars($row['id_kelas']) . '</td>';
                        echo '<td class="py-3 px-6">' . htmlspecialchars($row['id_user']) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center py-4">Tidak ada laporan.</td></tr>';
                }
                ?>
            </tbody>
        </table>

    </main>
<?php include "../Views/footer.php"; ?>
</body>

</html>
