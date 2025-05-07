<?php
include "db.php";
session_start();

// Ambil nilai ENUM untuk kategori
$sql = "SHOW COLUMNS FROM tbkelas LIKE 'kategori'";
$result = $conn->query($sql);

$enumValues = [];

if ($result) {
    $row = $result->fetch_assoc();
    $type = $row['Type'];
    preg_match("/^enum\('(.*)'\)$/", $type, $matches);
    if (isset($matches[1])) {
        $enumValues = explode("','", $matches[1]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $instructor = $_POST['instructor'];
    $price = $_POST['price'];
    $original_price = $_POST['original_price'];
    $rating = $_POST['rating'];
    $reviews = $_POST['reviews'];
    $tag = $_POST['tag'];
    $badge = $_POST['badge'];
    $kategori = isset($_POST['kategori']) ? $_POST['kategori'] : '';


    // Proses upload gambar
    $image = $_FILES['image'];
    $imagePath = '../upload/' . basename($image['name']);

    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        $query = "INSERT INTO tbkelas (title, instructor, price, original_price, rating, reviews, tag, badge, image, kategori) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssdisss", $title, $instructor, $price, $original_price, $rating, $reviews, $tag, $badge, $imagePath, $kategori);

        if ($stmt->execute()) {
            header("Location: kategori.php");
            exit;
        } else {
            echo "Gagal menambahkan kelas: " . $conn->error;
        }
    } else {
        echo "Gagal mengupload gambar.";
    }
}
?>

<!-- Form HTML -->
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Judul Kursus" required><br>
    <input type="text" name="instructor" placeholder="Instruktur" required><br>
    <input type="text" name="price" placeholder="Harga" required><br>
    <input type="text" name="original_price" placeholder="Harga Asli" required><br>
    <input type="text" name="rating" placeholder="Rating" required><br>
    <input type="number" name="reviews" placeholder="Jumlah Review" required><br>
    <input type="text" name="tag" placeholder="Tag (Contoh: BEST SELLER)" required><br>
    <input type="text" name="badge" placeholder="Badge (Contoh: HOT)" required><br>
    <label for="kategori">Kategori:</label>
    <select name="kategori" id="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($enumValues as $value): ?>
            <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></option>
        <?php endforeach; ?>
    </select><br>
    <input type="file" name="image" required><br>
    <button type="submit">Tambah Kelas</button>
</form>
