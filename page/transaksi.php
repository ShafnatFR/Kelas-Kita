<?php
session_start();

$total_payment = isset($_SESSION['cart_total_amount']) ? $_SESSION['cart_total_amount'] : '0.00';

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Kelaskita_baru'; // Ganti dengan nama database Anda

$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include "cart_db_integration.php";

// Proses form submission
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $tgl_transaksi = date('Y-m-d');
    
    // Handle file upload
    if (isset($_FILES['bukti_transaksi']) && $_FILES['bukti_transaksi']['error'] == 0) {
        $upload_dir = '../uploads/bukti_transaksi/';
        
        // Buat folder jika belum ada
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['bukti_transaksi']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            $new_filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['bukti_transaksi']['tmp_name'], $upload_path)) {
                // Insert ke database (sesuaikan dengan kebutuhan tabel Anda)
                // For now, using default values for foreign keys
              $id_kelas = 1; // Sesuaikan dengan kebutuhan
                $id_user = 1;  // Sesuaikan dengan kebutuhan
               $id_keranjang = 1; // Sesuaikan dengan kebutuhan
                // Use actual logged-in user id and cart info
                $id_user = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
                // You may need to get id_kelas and id_keranjang from session or cart data
                $id_kelas = 0;
               $id_keranjang = 0;
                if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                    $first_cart_item = $_SESSION['cart'][0];
                    $id_kelas = $first_cart_item['id'] ?? 0;
                    // id_keranjang may need to be retrieved from database or session if available
                }
                
                $stmt = $conn->prepare("INSERT INTO tb_transaksi (id_kelas, id_user, id_keranjang, bukti_transaksi, tgl_transaksi, status, list_transaksi) VALUES (?, ?, ?, ?, ?, 'Pending', 'pembelian')");
                $stmt->bind_param("iiiss", $id_kelas, $id_user, $id_keranjang, $new_filename, $tgl_transaksi);
                
                if ($stmt->execute()) {
                    $message = 'Transaksi berhasil dikirim! Tim kami akan memverifikasi pembayaran Anda dalam 1x24 jam.';
                    $message_type = 'success';
                    
                    // Reset form setelah berhasil
                    $_POST = array();
                } else {
                    $message = 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.';
                    $message_type = 'danger';
                }
                $stmt->close();
            } else {
                $message = 'Gagal mengunggah file bukti transfer.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.';
            $message_type = 'danger';
        }
    } else {
        $message = 'Silakan upload bukti transfer terlebih dahulu.';
        $message_type = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .step {
            display: flex;
            align-items: center;
            margin: 0 10px;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .step.active .step-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .step-line {
            width: 60px;
            height: 2px;
            background: #e9ecef;
            margin: 0 10px;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .bank-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            border-left: 5px solid #667eea;
        }
        
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 20px;
        }
        
        .upload-area:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
        
        .file-info {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 8px;
        }
        
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 40px;
        }
        
        .copy-btn {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            padding: 5px;
        }
        
        .copy-btn:hover {
            color: #764ba2;
        }
    </style>
</head>
<body class="bg-light">
    <div class="header-gradient">
        <div class="container">
            <div class="text-center">
                <h1 class="mb-0"><i class="fas fa-credit-card me-3"></i>Proses Transaksi</h1>
                <p class="mb-0 mt-2 opacity-75">Lengkapi data dan lakukan pembayaran</p>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active">
                <div class="step-number">1</div>
                <span>Data Diri</span>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-number">2</div>
                <span>Transfer</span>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-number">3</div>
                <span>Upload Bukti</span>
            </div>
        </div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show mt-4" role="alert">
        <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-body p-4">
                        <form method="POST" enctype="multipart/form-data" id="transactionForm">
                            <!-- Step 1: Data Diri -->
                            <div class="mb-4">
                                <h4 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Data Diri
                                </h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" name="nama" required 
                                               placeholder="Masukkan nama lengkap" 
                                               value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="no_hp" class="form-label fw-semibold">No. WhatsApp</label>
                                        <input type="tel" class="form-control" id="no_hp" name="no_hp" required 
                                               placeholder="08xxxxxxxxxx"
                                               value="<?php echo isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required 
                                           placeholder="nama@email.com"
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Step 2: Info Transfer -->
                            <div class="mb-4">
                                <h4 class="text-primary mb-3">
                                    <i class="fas fa-university me-2"></i>Informasi Transfer
                                </h4>
                                <div class="bank-info">
                                    <div class="row align-items-center">
                                        <div class="col-md-3 text-center">
                                            <img src="https://via.placeholder.com/100x60/4169E1/FFFFFF?text=BANK" alt="Bank" class="img-fluid rounded">
                                        </div>
                                        <div class="col-md-9">
                                            <h5 class="mb-1">Bank Mandiri</h5>
                                            <div class="d-flex align-items-center mb-2">
                                                <strong class="me-2">No. Rekening:</strong>
                                                <span id="norek" class="me-2">015501149884506</span>
                                                <button type="button" class="copy-btn" onclick="copyToClipboard('norek')" title="Copy">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <strong class="me-2">Atas Nama:</strong>
                                                <span>KelasKita</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-3 mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Total Pembayaran: Rp 500.000</strong><br>
                                        <small>Pastikan nominal transfer sesuai dengan jumlah di atas</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Step 3: Upload Bukti -->
                            <div class="mb-4">
                                <h4 class="text-primary mb-3">
                                    <i class="fas fa-receipt me-2"></i>Upload Bukti Transfer
                                </h4>
                                <p class="text-muted">Setelah melakukan transfer, silakan upload bukti transfer (screenshot/foto)</p>
                                
                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt fs-2 text-muted mb-3"></i>
                                    <h5 class="text-muted">Klik atau Drag & Drop</h5>
                                    <p class="text-muted mb-0">Upload bukti transfer disini</p>
                                    <p class="small text-muted mt-2">Format: JPG, JPEG, PNG, GIF (Max: 5MB)</p>
                                    <input type="file" class="d-none" id="bukti_transaksi" name="bukti_transaksi" 
                                           accept="image/*" required>
                                </div>
                                
                                <div class="file-info" id="fileInfo">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-image text-success me-2"></i>
                                        <span id="fileName"></span>
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="removeFile">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <img id="previewImage" class="preview-image" style="display: none;">
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary-custom btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Bukti Transfer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php if ($message_type === 'success'): ?>
<div class="container mt-2 text-center" style="max-width: 300px;">
    <a href="list-transaksi.php" class="btn btn-primary mb-2 w-100 btn-sm">Lihat Riwayat Transaksi</a>
    <a href="cart.php" class="btn btn-secondary mb-2 w-100 btn-sm">Kembali ke Keranjang</a>
    <a href="index.php" class="btn btn-secondary w-100 btn-sm">Kembali ke Beranda</a>
</div>
<?php endif; ?>

    <script src="../assets/images/Gambar WhatsApp 2025-06-12 pukul 01.23.30_429e9e73.jpg"></script>
    <script>
        // Copy to clipboard function
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            
            navigator.clipboard.writeText(text).then(() => {
                // Show copied notification
                const button = element.nextElementSibling;
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check text-success"></i>';
                button.style.color = '#28a745';
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.style.color = '#667eea';
                }, 2000);
            });
        }

        // Upload Area Functionality
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('bukti_transaksi');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const previewImage = document.getElementById('previewImage');
        const removeFileBtn = document.getElementById('removeFile');

        uploadArea.addEventListener('click', () => fileInput.click());

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.backgroundColor = '#f8f9ff';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#dee2e6';
            uploadArea.style.backgroundColor = 'transparent';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#dee2e6';
            uploadArea.style.backgroundColor = 'transparent';
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        removeFileBtn.addEventListener('click', () => {
            fileInput.value = '';
            fileInfo.style.display = 'none';
            previewImage.style.display = 'none';
        });

        function handleFileSelect(file) {
            fileName.textContent = file.name;
            fileInfo.style.display = 'block';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Form validation
        document.getElementById('transactionForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('bukti_transaksi');
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Silakan upload bukti transfer terlebih dahulu.');
                return false;
            }
            
            if (fileInput.files[0].size > 5 * 1024 * 1024) {
                e.preventDefault();
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                return false;
            }
        });

        // Phone number formatting
        document.getElementById('no_hp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                e.target.value = value;
            } else if (value.startsWith('62')) {
                e.target.value = '0' + value.substring(2);
            }
        });
    </script>
</body>
</html>