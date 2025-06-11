<?php
// payment_success.php - Halaman sukses pembayaran

session_start();

// Redirect jika akses langsung tanpa proses pembayaran
if (!isset($_SESSION['payment_completed'])) {
    header("Location: index.php");
    exit();
}

// Ambil data transaksi jika ada
$transaction_data = $_SESSION['transaction_data'] ?? null;

// Bersihkan session setelah ditampilkan
unset($_SESSION['payment_completed']);
unset($_SESSION['transaction_data']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - KelasKita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .success-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-card {
            max-width: 600px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .success-icon {
            font-size: 80px;
            color: #28a745;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 25px;
            padding: 12px 30px;
        }
        
        .btn-outline-primary:hover {
            background: #667eea;
            border-color: #667eea;
        }
        
        .support-links a {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .support-links a:hover {
            color: #667eea;
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card success-card fade-in">
                        <div class="card-body p-5 text-center">
                            <!-- Success Icon -->
                            <div class="mb-4">
                                <i class="fas fa-check-circle success-icon"></i>
                            </div>
                            
                            <!-- Success Message -->
                            <h2 class="text-success mb-3">Pembayaran Berhasil!</h2>
                            <p class="lead text-muted mb-4">
                                Terima kasih! Pembayaran Anda telah berhasil diproses. 
                                Tim kami akan segera memverifikasi pembayaran Anda.
                            </p>
                            
                            <!-- Transaction Details -->
                            <?php if ($transaction_data): ?>
                            <div class="alert alert-light border mb-4">
                                <h5 class="alert-heading">
                                    <i class="fas fa-receipt me-2"></i>
                                    Detail Transaksi
                                </h5>
                                <hr>
                                <div class="row text-start">
                                    <div class="col-sm-6">
                                        <strong>ID Transaksi:</strong><br>
                                        <span class="text-muted"><?php echo $transaction_data['id'] ?? 'TXN-' . date('YmdHis'); ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Total Pembayaran:</strong><br>
                                        <span class="text-success h5">Rp <?php echo number_format($transaction_data['amount'] ?? 0, 0, ',', '.'); ?></span>
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <strong>Metode Pembayaran:</strong><br>
                                        <span class="text-muted"><?php echo ucfirst(str_replace('_', ' ', $transaction_data['method'] ?? 'Transfer Bank')); ?></span>
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <strong>Tanggal:</strong><br>
                                        <span class="text-muted"><?php echo date('d/m/Y H:i', strtotime($transaction_data['created_at'] ?? 'now')); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Next Steps -->
                            <div class="alert alert-info text-start mb-4">
                                <h6 class="alert-heading">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Langkah Selanjutnya
                                </h6>
                                <ul class="mb-0">
                                    <li>Bukti pembayaran Anda sedang diverifikasi oleh tim kami</li>
                                    <li>Anda akan menerima email konfirmasi dalam 1x24 jam</li>
                                    <li>Setelah verifikasi, Anda dapat mengakses kursus yang dibeli</li>
                                    <li>Cek dashboard Anda untuk melihat status pembelian</li>
                                </ul>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <a href="dashboard.php" class="btn btn-primary">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Ke Dashboard
                                </a>
                                <a href="courses.php" class="btn btn-outline-primary">
                                    <i class="fas fa-book me-2"></i>
                                    Lihat Kursus Lain
                                </a>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-home me-2"></i>
                                    Kembali ke Beranda
                                </a>
                            </div>
                            
                            <!-- Support Info -->
                            <div class="mt-4 pt-4 border-top">
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-question-circle me-1"></i>
                                    Ada pertanyaan? Hubungi tim support kami
                                </p>
                                <div class="d-flex gap-3 justify-content-center support-links">
                                    <a href="mailto:support@kelaskita.com" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i>
                                        Email Support
                                    </a>
                                    <a href="https://wa.me/6281234567890" class="text-decoration-none" target="_blank">
                                        <i class="fab fa-whatsapp me-1"></i>
                                        WhatsApp
                                    </a>
                                    <a href="tel:+6281234567890" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>
                                        Call Center
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Print Receipt Button -->
                            <div class="mt-3">
                                <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-print me-1"></i>
                                    Cetak Bukti Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto redirect after 5 minutes -->
    <script>
        // Auto redirect ke dashboard setelah 5 menit
        setTimeout(function() {
            if (confirm('Halaman akan dialihkan ke dashboard. Klik OK untuk melanjutkan atau Cancel untuk tetap di halaman ini.')) {
                window.location.href = 'dashboard.php';
            }
        }, 300000); // 5 menit
        
        // Prevent back button after payment success
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
        
        // Disable right click and F12 for security
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
                e.preventDefault();
            }
        });
    </script>
    
    <!-- Print Styles -->
    <style media="print">
        .success-container {
            background: white !important;
            min-height: auto !important;
        }
        
        .success-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .btn, .support-links {
            display: none !important;
        }
        
        .success-icon {
            animation: none !important;
            color: #28a745 !important;
        }
    </style>
</body>
</html>