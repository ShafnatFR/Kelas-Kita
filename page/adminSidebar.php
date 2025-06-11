<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautiful Admin Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-header a {
            text-decoration: none;
            color: white;
        }
        
        .sidebar-header img {
            border: 3px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease;
        }
        
        .sidebar-header img:hover {
            transform: scale(1.1);
        }
        
        .sidebar-header h3 {
            font-weight: 600;
            margin-top: 15px;
            font-size: 1.4rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 15px 20px;
            margin: 8px 15px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }
        
        .nav-link:hover::before {
            left: 100%;
        }
        
        .logout-section {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 0 15px;
        }
        
        .logout-link {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white !important;
            border: none;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.4);
        }
        
        .logout-link:hover {
            background: linear-gradient(45deg, #ee5a24, #ff6b6b);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(238, 90, 36, 0.6);
        }
        
        .sidebar-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            margin: 20px 15px;
        }
        
        /* Custom scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .logout-section {
                position: relative;
                bottom: auto;
                margin-top: 20px;
            }
        }
        
        /* Demo content styling */
        .main-content {
            margin-left: 280px;
            padding: 20px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar position-fixed">
        <div class="sidebar-header text-center p-4">
            <a href="mentor-dashboard.php" class="text-decoration-none">
                <img src="https://via.placeholder.com/80x80/ffffff/667eea?text=LOGO" alt="Logo" class="rounded-circle" width="80">
                <h3 class="text-white mb-0">Dashboard Admin</h3>
                <small class="text-white-50 d-block mt-1">Sidebar</small>
            </a>
        </div>
        
        <div class="sidebar-divider"></div>
        
        <nav class="nav flex-column px-3 pb-5">
            <a class="nav-link" href="admin-dashboard.php">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            
            <a class="nav-link" href="admin-kelolaUser.php">
                <i class="fas fa-users"></i>
                Kelola User
            </a>

            <a class="nav-link" href="admin-kelolaKelas.php">
                <i class="fas fa-chalkboard-teacher"></i>
                Kelola Kelas
            </a>
            
            <a class="nav-link" href="Admin-kelolaTransaksi.php">
                <i class="fas fa-credit-card"></i>
                Kelola Transaksi
            </a>
            
            <a class="nav-link" href="admin-kelolaMateri.php">
                <i class="fas fa-book-open"></i>
                Kelola Materi
            </a>
            
            <a class="nav-link" href="admin-kelolaReviewKomentar.php">
                <i class="fas fa-comments"></i>
                Review & Komentar
            </a>
            
            <!-- <a class="nav-link" href="admin-kelolaPesan.php">
                <i class="fas fa-envelope"></i>
                Pesan
            </a> -->
            
            <a class="nav-link" href="admin-kelolaLaporan.php">
                <i class="fas fa-chart-bar"></i>
                Laporan
            </a>
            
            <div class="sidebar-divider"></div>
            
            <!-- <a class="nav-link" href="pengaturan.php">
                <i class="fas fa-cog"></i>
                Pengaturan
            </a> -->
        </nav>
        
        <div class="logout-section">
            <a class="nav-link logout-link text-center" href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>
    </body>