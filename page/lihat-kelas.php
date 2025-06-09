<?php
session_start();
if (file_exists(__DIR__ . '/config/database.php')) {
    require_once __DIR__ . '/config/database.php';
} else {
    require_once __DIR__ . '/db.php';
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: HalamanSignIn.php');
    exit();
}

// Ambil ID kelas dari parameter URL
$kelas_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$selected_module = isset($_GET['module']) ? (int)$_GET['module'] : 0;

if ($kelas_id == 0) {
    header('Location: dashboard.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission untuk menandai modul selesai
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_module'])) {
    $modul_id = (int)$_POST['modul_id'];
    
    // Verifikasi bahwa modul belong to kelas yang benar
    $query_verify = "SELECT id FROM modul_kelas WHERE id = ? AND kelas_id = ?";
    $stmt_verify = $conn->prepare($query_verify);
    $stmt_verify->bind_param("ii", $modul_id, $kelas_id);
    $stmt_verify->execute();
    $result_verify = $stmt_verify->get_result();
    
    if ($result_verify->num_rows > 0) {
        // Cek apakah sudah ada progress untuk modul ini
        $query_check = "SELECT id FROM user_progress WHERE user_id = ? AND kelas_id = ? AND modul_id = ?";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param("iii", $user_id, $kelas_id, $modul_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows == 0) {
            // Insert progress baru
            $query_insert = "INSERT INTO user_progress (user_id, kelas_id, modul_id, tanggal_selesai) 
                             VALUES (?, ?, ?, NOW())";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param("iii", $user_id, $kelas_id, $modul_id);
            
            if ($stmt_insert->execute()) {
                $_SESSION['success'] = 'Modul berhasil ditandai selesai!';
            } else {
                $_SESSION['error'] = 'Gagal menyimpan progress.';
            }
        } else {
            $_SESSION['info'] = 'Modul sudah ditandai selesai sebelumnya.';
        }
    }
    
    // Redirect untuk mencegah double submission
    header("Location: lihat-kelas.php?id=$kelas_id&module=$modul_id");
    exit();
}

// Cek apakah user memiliki akses ke kelas ini (pembayaran sudah diverifikasi)
$query_akses = "SELECT p.*, k.nama_kelas, k.deskripsi, k.gambar_kelas, k.harga, k.instruktur_id, u.nama as nama_instruktur
                FROM pembayaran p 
                JOIN kelas k ON p.kelas_id = k.id 
                JOIN users u ON k.instruktur_id = u.id
                WHERE p.user_id = ? AND p.kelas_id = ? AND p.status_pembayaran = 'verified'";

$stmt_akses = $conn->prepare($query_akses);
$stmt_akses->bind_param("ii", $user_id, $kelas_id);
$stmt_akses->execute();
$result_akses = $stmt_akses->get_result();

if ($result_akses->num_rows == 0) {
    $_SESSION['error'] = 'Anda tidak memiliki akses ke kelas ini atau pembayaran belum diverifikasi.';
    header('Location: dashboard.php');
    exit();
}

$kelas_data = $result_akses->fetch_assoc();

// Ambil semua modul/video untuk kelas ini dengan status completion
$query_modul = "SELECT m.*, 
                CASE WHEN up.id IS NOT NULL THEN 1 ELSE 0 END as is_completed
                FROM modul_kelas m
                LEFT JOIN user_progress up ON m.id = up.modul_id AND up.user_id = ?
                WHERE m.kelas_id = ? 
                ORDER BY m.urutan ASC";
$stmt_modul = $conn->prepare($query_modul);
$stmt_modul->bind_param("ii", $user_id, $kelas_id);
$stmt_modul->execute();
$result_modul = $stmt_modul->get_result();

$modules = [];
$completed_modules = [];
while ($row = $result_modul->fetch_assoc()) {
    $modules[] = $row;
    if ($row['is_completed']) {
        $completed_modules[] = $row['id'];
    }
}

// Jika tidak ada modul yang dipilih, pilih modul pertama
if ($selected_module == 0 && !empty($modules)) {
    $selected_module = $modules[0]['id'];
}

// Ambil detail modul yang dipilih
$current_module = null;
foreach ($modules as $module) {
    if ($module['id'] == $selected_module) {
        $current_module = $module;
        break;
    }
}

// Hitung persentase progress
$total_modules = count($modules);
$completed_count = count($completed_modules);
$progress_percentage = $total_modules > 0 ? round(($completed_count / $total_modules) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($kelas_data['nama_kelas']); ?> - Platform Kursus Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .course-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        .module-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .module-item:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .module-completed {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .progress-ring {
            width: 60px;
            height: 60px;
        }
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background: #000;
            border-radius: 8px;
        }
        .video-container iframe,
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .sidebar {
            background-color: #f8f9fa;
            border-radius: 8px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .module-title {
            cursor: pointer;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        .module-title:hover {
            background-color: #e9ecef;
        }
        .module-content {
            padding: 1.5rem;
        }
        .completed-badge {
            background-color: #28a745;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-graduation-cap me-2"></i>
                Platform Kursus
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </nav>

    <!-- Course Header -->
    <div class="course-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2"><?php echo htmlspecialchars($kelas_data['nama_kelas']); ?></h1>
                    <p class="mb-3"><?php echo htmlspecialchars($kelas_data['deskripsi']); ?></p>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-tie me-2"></i>
                        <span>Instruktur: <?php echo htmlspecialchars($kelas_data['nama_instruktur']); ?></span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="card bg-white text-dark">
                        <div class="card-body text-center">
                            <h5>Progress Anda</h5>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progress_percentage; ?>%"></div>
                            </div>
                            <p class="mb-0"><?php echo $completed_count; ?>/<?php echo $total_modules; ?> Modul Selesai (<?php echo $progress_percentage; ?>%)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['info'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Sidebar Modul -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Daftar Modul
                        </h5>
                    </div>
                    <div class="module-list">
                        <?php foreach ($modules as $modul): ?>
                            <div class="module-item <?php echo $modul['is_completed'] ? 'module-completed' : ''; ?> 
                                        <?php echo $selected_module == $modul['id'] ? 'border-primary' : ''; ?>">
                                <a href="lihat-kelas.php?id=<?php echo $kelas_id; ?>&module=<?php echo $modul['id']; ?>" 
                                   class="text-decoration-none text-dark">
                                    <div class="module-title">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($modul['judul']); ?></h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?php echo $modul['durasi']; ?> menit
                                                </small>
                                            </div>
                                            <div>
                                                <?php if ($modul['is_completed']): ?>
                                                    <span class="completed-badge">
                                                        <i class="fas fa-check me-1"></i>Selesai
                                                    </span>
                                                <?php else: ?>
                                                    <i class="fas fa-play-circle text-primary"></i>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-8">
                <?php if ($current_module): ?>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><?php echo htmlspecialchars($current_module['judul']); ?></h4>
                            <?php if (!$current_module['is_completed']): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="modul_id" value="<?php echo $current_module['id']; ?>">
                                    <button type="submit" name="complete_module" class="btn btn-success" 
                                            onclick="return confirm('Apakah Anda yakin sudah menyelesaikan modul ini?')">
                                        <i class="fas fa-check me-1"></i>Tandai Selesai
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="completed-badge">
                                    <i class="fas fa-check me-1"></i>Selesai
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if ($current_module['video_url']): ?>
                                <div class="video-container mb-4">
                                    <?php if (strpos($current_module['video_url'], 'youtube.com') !== false || strpos($current_module['video_url'], 'youtu.be') !== false): ?>
                                        <?php
                                        // Extract YouTube video ID
                                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $current_module['video_url'], $matches);
                                        $video_id = isset($matches[1]) ? $matches[1] : '';
                                        ?>
                                        <iframe src="https://www.youtube.com/embed/<?php echo $video_id; ?>" 
                                                frameborder="0" allowfullscreen></iframe>
                                    <?php else: ?>
                                        <video controls>
                                            <source src="<?php echo htmlspecialchars($current_module['video_url']); ?>" type="video/mp4">
                                            Browser Anda tidak mendukung video HTML5.
                                        </video>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>Durasi: <?php echo $current_module['durasi']; ?> menit
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-sort-numeric-up me-1"></i>Modul <?php echo $current_module['urutan']; ?>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="content-text">
                                <h5>Deskripsi Modul</h5>
                                <p><?php echo $current_module['deskripsi'] ? htmlspecialchars($current_module['deskripsi']) : 'Tidak ada deskripsi untuk modul ini.'; ?></p>
                                
                                <?php if ($current_module['konten']): ?>
                                    <h5>Materi</h5>
                                    <div class="content-body">
                                        <?php echo nl2br(htmlspecialchars($current_module['konten'])); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-play-circle text-primary" style="font-size: 4rem;"></i>
                        <h3 class="mt-3">Pilih Modul untuk Memulai</h3>
                        <p class="text-muted">Klik pada salah satu modul di sidebar untuk mulai belajar</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Selesai -->
    <div class="modal fade" id="completeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selesaikan Modul</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin sudah menyelesaikan modul ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirmComplete">Ya, Selesai</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentModuleId = null;

        function loadModule(moduleId) {
            fetch(`ajax/get-module.php?id=${moduleId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentModuleId = moduleId;
                        displayModuleContent(data.module);
                    } else {
                        alert('Gagal memuat modul: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat modul');
                });
        }

        function displayModuleContent(module) {
            const contentArea = document.getElementById('module-content');
            const isCompleted = module.is_completed;
            
            contentArea.innerHTML = `
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">${module.judul}</h4>
                        ${!isCompleted ? `
                            <button class="btn btn-success" onclick="showCompleteModal()">
                                <i class="fas fa-check me-1"></i>Tandai Selesai
                            </button>
                        ` : `
                            <span class="completed-badge">
                                <i class="fas fa-check me-1"></i>Selesai
                            </span>
                        `}
                    </div>
                    <div class="card-body">
                        ${module.video_url ? `
                            <div class="video-container mb-4">
                                ${module.video_url.includes('youtube.com') || module.video_url.includes('youtu.be') 
                                    ? `<iframe src="${getYouTubeEmbedUrl(module.video_url)}" frameborder="0" allowfullscreen></iframe>`
                                    : `<video controls>
                                        <source src="${module.video_url}" type="video/mp4">
                                        Browser Anda tidak mendukung video HTML5.
                                       </video>`
                                }
                            </div>
                        ` : ''}
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>Durasi: ${module.durasi} menit
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <i class="fas fa-sort-numeric-up me-1"></i>Modul ${module.urutan}
                                </small>
                            </div>
                        </div>
                        
                        <div class="content-text">
                            <h5>Deskripsi Modul</h5>
                            <p>${module.deskripsi || 'Tidak ada deskripsi untuk modul ini.'}</p>
                            
                            ${module.konten ? `
                                <h5>Materi</h5>
                                <div class="content-body">
                                    ${module.konten}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        function getYouTubeEmbedUrl(url) {
            const videoId = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/);
            return videoId ? `https://www.youtube.com/embed/${videoId[1]}` : url;
        }

        function showCompleteModal() {
            const modal = new bootstrap.Modal(document.getElementById('completeModal'));
            modal.show();
        }

        document.getElementById('confirmComplete').addEventListener('click', function() {
            if (currentModuleId) {
                markModuleComplete(currentModuleId);
            }
        });

        function markModuleComplete(moduleId) {
            fetch('ajax/complete-module.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    kelas_id: <?php echo $kelas_id; ?>,
                    modul_id: moduleId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('completeModal'));
                    modal.hide();
                    
                    // Reload halaman untuk update progress
                    location.reload();
                } else {
                    alert('Gagal menandai modul selesai: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menandai modul selesai');
            });
        }

        // Auto-load first module if available
        document.addEventListener('DOMContentLoaded', function() {
            const firstModule = document.querySelector('.module-item');
            if (firstModule) {
                const moduleIdMatch = firstModule.getAttribute('onclick').match(/\d+/);
                if (moduleIdMatch) {
                    const moduleId = moduleIdMatch[0];
                    loadModule(parseInt(moduleId));
                }
            }
        });
    </script>
</body>
</html>