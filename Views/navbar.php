<?php
<<<<<<< HEAD

=======
>>>>>>> c3796a35fd4aa28ea4b013d4f79476c9a0ec68d6
include_once('db.php');
if (session_status() === PHP_SESSION_NONE) session_start();

$user = null;
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT fotoProfil FROM tbuser WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white py-4 px-6 shadow-sm">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="#" class="text-blue-600 font-bold text-2xl">KelasKita</a>
                <div class="hidden md:flex ml-10 space-x-6">
                    <a href="#" class="text-gray-900 font-medium">Beranda</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Kursus</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Kategori</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">Blog</a>
                    <a href="#" class="text-gray-500 hover:text-gray-900">kontak</a>
                </div>
                <div class="flex items-center space-x-4">
                <a href="cart.php" class="hidden md:inline-block text-gray-600 hover:text-gray-900 px-4 py-2">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if(!empty($_SESSION['cart'])): ?>
                        <span class="bg-red-500 text-white rounded-full px-2 py-1 text-xs"><?php echo count($_SESSION['cart']); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
    <div class="flex items-center space-x-4">
    <?php if (isset($_SESSION['username'])): ?>
        <div class="relative">
            <!-- Tombol Profil -->
            <button onclick="toggleDropdown()" class="focus:outline-none">
                <img 
                src="<?= (!empty($user['fotoProfil']) && file_exists('../upload/' . $user['fotoProfil'])) 
                    ? '../upload/' . htmlspecialchars($user['fotoProfil']) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=0D8ABC&color=fff&rounded=true&size=64' ?>" 
                alt="Profile" 
                class="rounded-full w-8 h-8 object-cover">

            </button>

            <!-- Dropdown -->
            <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg p-4 z-50">
                <div class="flex items-center space-x-3 border-b pb-3 mb-3">
                    <img 
                    src="<?= (!empty($user['fotoProfil']) && file_exists('../upload/' . $user['fotoProfil'])) 
                        ? '../upload/' . htmlspecialchars($user['fotoProfil']) 
                        : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=0D8ABC&color=fff&rounded=true&size=64' ?>" 
                    alt="Profile" 
                    class="rounded-full w-8 h-8 object-cover">
                    <div>
                        <p class="text-gray-800 font-semibold"><?= htmlspecialchars($_SESSION['username']) ?></p>
                        <p class="text-gray-500 text-sm block w-full max-w-[160px] overflow-hidden text-ellipsis whitespace-nowrap"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>

                    </div>
                </div>
                <ul class="space-y-2 text-sm">
                    <li><a href="setting-profil.php" class="block text-gray-700 hover:text-blue-600 transition">KelasKu</a></li>
                    <li><a href="keranjang.php" class="block text-gray-700 hover:text-blue-600 transition">Keranjang</a></li>
                    <li><a href="setting-profil.php" class="block text-gray-700 hover:text-blue-600 transition">Pengaturan Profil</a></li>
                    <li><a href="logout.php" class="block text-red-600 hover:text-red-800 transition">Logout</a></li>
                </ul>
            </div>
        </div>
    <?php else: ?>
        <a href="HalamanSignIn.php" class="hidden md:inline-block text-gray-600 hover:text-gray-900 px-4 py-2">Masuk</a>
        <a href="HalamanSignUp.php" class="bg-blue-600 text-white px-6 py-2 rounded-md font-medium hover:bg-blue-700 transition">Register</a>
    <?php endif; ?>
</div>
