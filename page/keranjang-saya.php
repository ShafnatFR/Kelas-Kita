<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: HalamanSignIn.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil user_id
$queryUser = mysqli_query($conn, "SELECT id FROM tbuser WHERE username = '$username'");
$userData = mysqli_fetch_assoc($queryUser);
$user_id = $userData['id'];

// Ambil data keranjang berdasarkan user_id
$queryKeranjang = mysqli_query($conn, "
    SELECT k.id AS keranjang_id, c.id AS kelas_id, c.title, c.image, c.price
    FROM keranjang k
    JOIN tbkelas c ON k.kelas_id = c.id
    WHERE k.user_id = '$user_id'
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-10 px-6">
        <h1 class="text-2xl font-bold mb-6">Keranjang Saya</h1>

        <?php if (mysqli_num_rows($queryKeranjang) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($item = mysqli_fetch_assoc($queryKeranjang)): ?>
                    <div class="bg-white rounded-lg shadow p-4">
                        <img src="<?php echo $item['image']; ?>" class="w-full h-40 object-cover mb-3 rounded">
                        <h2 class="text-lg font-semibold mb-2"><?php echo $item['title']; ?></h2>
                        <p class="text-gray-700 font-bold mb-2"><?php echo $item['price']; ?></p>
                        <form action="hapus-keranjang.php" method="post">
                            <input type="hidden" name="keranjang_id" value="<?php echo $item['keranjang_id']; ?>">
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Keranjang kamu kosong.</p>
        <?php endif; ?>
    </div>
</body>
</html>
