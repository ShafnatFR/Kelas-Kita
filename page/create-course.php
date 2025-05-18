<?php
session_start();
include_once('db.php');

// Pastikan pengguna sudah login dan memiliki role sebagai mentor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'mentor') {
    header("Location: HalamanSignIn.php");
    exit();
}

// Proses pembuatan kelas baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $kategori = $_POST['kategori'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $instructor = $_SESSION['username'];
    $course_type = $_POST['course_type'];  // Tipe kursus: Terjadwal atau Fleksibel
    $start_date = $_POST['start_date'];  // Jika kursus terjadwal
    $end_date = $_POST['end_date'];  // Jika kursus terjadwal

    // Menangkap file materi yang diupload
    $materials = [];
    $allowed_extensions = ['pdf', 'docx', 'pptx', 'mp4'];  // Format file yang diperbolehkan
    $upload_directory = '../upload/';

    foreach ($_FILES['materials']['name'] as $key => $filename) {
        $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Cek ekstensi file
        if (in_array($file_extension, $allowed_extensions)) {
            $target_file = $upload_directory . basename($filename);
            
            // Pindahkan file ke server
            if (move_uploaded_file($_FILES['materials']['tmp_name'][$key], $target_file)) {
                $materials[] = $target_file;  // Menyimpan path file yang berhasil diupload
            } else {
                echo "Terjadi kesalahan saat mengupload file $filename.";
                exit();
            }
        } else {
            echo "Format file $filename tidak diperbolehkan.";
            exit();
        }
    }

    // Menyimpan materi dalam bentuk string yang dipisahkan koma
    $materials_string = implode(",", $materials);

    // Menangani sub-bab yang ditambahkan oleh mentor
    $sub_babs = [];
    if (isset($_POST['sub_bab_title']) && is_array($_POST['sub_bab_title'])) {
        foreach ($_POST['sub_bab_title'] as $index => $sub_bab_title) {
            $sub_bab_content = $_POST['sub_bab_content'][$index] ?? '';  // Memastikan ada konten untuk sub-bab
            $sub_babs[] = ['title' => $sub_bab_title, 'content' => $sub_bab_content];
        }
    }
    
    // Menyimpan sub-bab dalam bentuk JSON
    $sub_babs_string = json_encode($sub_babs); 

    // Insert kelas baru ke database
    $query = "INSERT INTO tbkelas (title, kategori, description, price, instructor, materials, course_type, start_date, end_date, sub_babs) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssss", $title, $kategori, $description, $price, $instructor, $materials_string, $course_type, $start_date, $end_date, $sub_babs_string);

    if ($stmt->execute()) {
        echo "Kelas berhasil dibuat!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kursus Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container label {
            font-weight: 600;
        }
        .form-container .form-control {
            margin-bottom: 15px;
        }
        .form-container .btn-primary {
            background-color: #4caf50;
            border-color: #4caf50;
        }
        .material-list {
            margin-top: 15px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="form-container">
            <h2>Buat Kursus Baru</h2>
            <form method="POST" enctype="multipart/form-data">
                <!-- Judul Kursus -->
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Kursus:</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <!-- Kategori -->
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori:</label>
                    <select name="kategori" id="kategori" class="form-select" required>
                        <option value="Web Development">Web Development</option>
                        <option value="Digital Marketing">Digital Marketing</option>
                        <option value="Data Science">Data Science</option>
                        <option value="Business Management">Business Management</option>
                    </select>
                </div>

                <!-- Deskripsi Kursus -->
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi:</label>
                    <textarea name="description" id="description" class="form-control" required></textarea>
                </div>

                <!-- Harga Kursus -->
                <div class="mb-3">
                    <label for="price" class="form-label">Harga:</label>
                    <input type="number" name="price" id="price" class="form-control" required>
                </div>

                <!-- Sub-Bab Materi -->
                <div class="mb-3">
                    <label for="sub_bab" class="form-label">Sub-Bab Materi:</label>
                    <div id="sub_bab_container">
                        <div class="sub_bab_item">
                            <input type="text" name="sub_bab_title[]" class="form-control mb-2" placeholder="Judul Sub-Bab" required>
                            <label for="materials" class="form-label">Materi Kursus (Upload File):</label>
                            <input type="file" name="materials[]" id="materials" class="form-control" multiple required>
                            <small class="text-muted">Pilih beberapa file (PDF, DOCX, PPTX, MP4)</small>
                        </div>
                    </div>
                    <button type="button" id="add_sub_bab" class="btn btn-secondary btn-sm">Tambah Sub-Bab</button>
                </div>

                <!-- Jenis Kursus (Fleksibel atau Terjadwal) -->
                <div class="mb-3">
                    <label for="course_type" class="form-label">Jenis Kursus:</label>
                    <select name="course_type" id="course_type" class="form-select" required>
                        <option value="on_demand">Kursus Fleksibel (Dapat Diakses Kapan Saja)</option>
                        <option value="scheduled">Kursus Terjadwal</option>
                    </select>
                </div>

                <!-- Tanggal Mulai dan Tanggal Selesai (Hanya untuk Kursus Terjadwal) -->
                <div id="scheduled_dates" style="display: none;">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Simpan Kursus</button>
            </form>
        </div>
    </div>

    <script>
        // Menyembunyikan atau menampilkan kolom tanggal berdasarkan pilihan jenis kursus
        document.getElementById('course_type').addEventListener('change', function() {
            var courseType = this.value;
            if (courseType === 'scheduled') {
                document.getElementById('scheduled_dates').style.display = 'block';
            } else {
                document.getElementById('scheduled_dates').style.display = 'none';
            }
        });

        // Menambahkan Sub-Bab Materi Dinamis
        document.getElementById('add_sub_bab').addEventListener('click', function() {
            var container = document.getElementById('sub_bab_container');
            var subBabItem = document.createElement('div');
            subBabItem.classList.add('sub_bab_item');
            subBabItem.innerHTML = `
                <input type="text" name="sub_bab_title[]" class="form-control mb-2" placeholder="Judul Sub-Bab" required>
                <label for="materials" class="form-label">Materi Kursus (Upload File):</label>
                <input type="file" name="materials[]" id="materials" class="form-control" multiple required>
                <small class="text-muted">Pilih beberapa file (PDF, DOCX, PPTX, MP4)</small>
            `;
            container.appendChild(subBabItem);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
