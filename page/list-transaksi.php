<?php
session_start();
/*
Replaced the conditional require_once block with direct database connection configuration
*/

// Konfigurasi Database
$host = 'localhost';
$dbname = 'kelaskita_baru'; // Sesuaikan dengan nama database Anda
$username = 'root';     // Sesuaikan dengan username database Anda
$password = '';         // Sesuaikan dengan password database Anda

// Konfigurasi untuk koneksi PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Options untuk PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Membuat koneksi PDO
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Set timezone untuk database
    $pdo->exec("SET time_zone = '+07:00'");
    
} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan error
    die("Koneksi database gagal: " . $e->getMessage());
}

// Fungsi untuk koneksi menggunakan mysqli (optional)
function getConnection() {
    global $host, $username, $password, $dbname;
    
    $connection = new mysqli($host, $username, $password, $dbname);
    
    if ($connection->connect_error) {
        die("Koneksi gagal: " . $connection->connect_error);
    }
    
    // Set charset
    $connection->set_charset("utf8mb4");
    
    return $connection;
}

// Fungsi untuk menutup koneksi
function closeConnection($connection) {
    if ($connection) {
        $connection->close();
    }
}

// Fungsi helper untuk escape string
function escapeString($string) {
    global $pdo;
    return $pdo->quote($string);
}

// Fungsi untuk debug query (hanya untuk development)
function debugQuery($query, $params = []) {
    if (defined('DEBUG_MODE') && DEBUG_MODE === true) {
        echo "<pre>";
        echo "Query: " . $query . "\n";
        if (!empty($params)) {
            echo "Params: ";
            print_r($params);
        }
        echo "</pre>";
    }
}

// Konstanta untuk debug mode (set false untuk production)
define('DEBUG_MODE', false);

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: HalamanSignIn.php");
    exit();
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$tanggal_dari = isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : '';
$tanggal_sampai = isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : '';

// Query untuk menghitung total data
$count_query = "SELECT COUNT(*) as total FROM tb_transaksi t 
                LEFT JOIN tb_user u ON t.id_user = u.id_user 
                LEFT JOIN tb_kelas k ON t.id_kelas = k.id_kelas
                WHERE 1=1";
$count_params = [];

// Tambahkan kondisi pencarian
if (!empty($search)) {
    $count_query .= " AND (t.id_transaksi LIKE ? OR u.nama LIKE ? OR k.nama_kelas LIKE ? OR t.bukti_transaksi LIKE ?)";
    $count_params[] = "%$search%";
    $count_params[] = "%$search%";
    $count_params[] = "%$search%";
    $count_params[] = "%$search%";
}

if (!empty($status_filter)) {
    $count_query .= " AND t.status = ?";
    $count_params[] = $status_filter;
}

if (!empty($tanggal_dari)) {
    $count_query .= " AND DATE(t.tgl_transaksi) >= ?";
    $count_params[] = $tanggal_dari;
}

if (!empty($tanggal_sampai)) {
    $count_query .= " AND DATE(t.tgl_transaksi) <= ?";
    $count_params[] = $tanggal_sampai;
}

$count_stmt = $pdo->prepare($count_query);
$count_stmt->execute($count_params);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Query untuk mengambil data transaksi
$query = "SELECT t.*, u.nama as nama_user, k.nama_kelas, k.harga,
                 kr.total_harga as total_keranjang
          FROM tb_transaksi t 
          LEFT JOIN tb_user u ON t.id_user = u.id_user 
          LEFT JOIN tb_kelas k ON t.id_kelas = k.id_kelas
          LEFT JOIN tb_keranjang kr ON t.id_keranjang = kr.id_keranjang
          WHERE 1=1";

// Tambahkan kondisi pencarian yang sama
if (!empty($search)) {
    $query .= " AND (t.id_transaksi LIKE ? OR u.nama LIKE ? OR k.nama_kelas LIKE ? OR t.bukti_transaksi LIKE ?)";
}

if (!empty($status_filter)) {
    $query .= " AND t.status = ?";
}

if (!empty($tanggal_dari)) {
    $query .= " AND DATE(t.tgl_transaksi) >= ?";
}

if (!empty($tanggal_sampai)) {
    $query .= " AND DATE(t.tgl_transaksi) <= ?";
}

$query .= " ORDER BY t.tgl_transaksi DESC, t.id_transaksi DESC LIMIT ? OFFSET ?";

$params = $count_params;
$params[] = $limit;
$params[] = $offset;

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fungsi untuk format mata uang
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Fungsi untuk format tanggal
function formatTanggal($tanggal) {
    return date('d/m/Y', strtotime($tanggal));
}

// Fungsi untuk badge status
function getStatusBadge($status) {
    switch($status) {
        case 'Pending':
            return '<span class="badge bg-warning">Pending</span>';
        case 'Completed':
            return '<span class="badge bg-success">Completed</span>';
        case 'Cancelled':
            return '<span class="badge bg-danger">Cancelled</span>';
        default:
            return '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .badge {
            font-size: 0.8em;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .search-box {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .bukti-link {
            display: inline-block;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .status-completed { color: #198754; }
        .status-pending { color: #ffc107; }
        .status-cancelled { color: #dc3545; }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-list me-2"></i>Daftar Transaksi</h2>
                    <a href="tambah_transaksi.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Transaksi
                    </a>
                </div>

                <!-- Filter dan Pencarian -->
                <div class="search-box">
                    <form method="GET" action="">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Pencarian</label>
                                <input type="text" class="form-control" name="search" 
                                       value="<?= htmlspecialchars($search) ?>" 
                                       placeholder="Cari ID, nama user, kelas, atau bukti...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Completed" <?= $status_filter == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="Cancelled" <?= $status_filter == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Dari</label>
                                <input type="date" class="form-control" name="tanggal_dari" 
                                       value="<?= htmlspecialchars($tanggal_dari) ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Sampai</label>
                                <input type="date" class="form-control" name="tanggal_sampai" 
                                       value="<?= htmlspecialchars($tanggal_sampai) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Cari
                                    </button>
                                    <a href="list_transaksi.php" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabel Transaksi -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Data Transaksi</h5>
                        <small class="text-muted">Total: <?= $total_records ?> transaksi</small>
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($transaksi) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>ID Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>User</th>
                                        <th>Kelas</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Bukti Transaksi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = $offset + 1;
                                    foreach ($transaksi as $row): 
                                    ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td>
                                            <strong class="text-primary">#<?= $row['id_transaksi'] ?></strong>
                                        </td>
                                        <td><?= formatTanggal($row['tgl_transaksi']) ?></td>
                                        <td>
                                            <div>
                                                <strong><?= htmlspecialchars($row['nama_user']) ?></strong>
                                                <br><small class="text-muted">ID: <?= $row['id_user'] ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?= htmlspecialchars($row['nama_kelas']) ?></strong>
                                                <br><small class="text-muted">ID: <?= $row['id_kelas'] ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                <?= formatRupiah($row['harga'] ?? $row['total_keranjang'] ?? 0) ?>
                                            </strong>
                                        </td>
                                        <td><?= getStatusBadge($row['status']) ?></td>
                                        <td>
                                            <?php if (!empty($row['bukti_transaksi'])): ?>
                                                <a href="uploads/bukti/<?= htmlspecialchars($row['bukti_transaksi']) ?>" 
                                                   target="_blank" class="btn btn-sm btn-outline-info bukti-link" 
                                                   title="Lihat bukti transaksi">
                                                    <i class="fas fa-file-image me-1"></i>
                                                    <?= htmlspecialchars($row['bukti_transaksi']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="detail_transaksi.php?id=<?= $row['id_transaksi'] ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($row['status'] == 'Pending'): ?>
                                                <a href="edit_transaksi.php?id=<?= $row['id_transaksi'] ?>" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php endif; ?>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                            data-bs-toggle="dropdown" title="Status">
                                                        <i class="fas fa-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="update_status.php?id=<?= $row['id_transaksi'] ?>&status=Completed">
                                                            <i class="fas fa-check text-success me-2"></i>Set Completed
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="update_status.php?id=<?= $row['id_transaksi'] ?>&status=Pending">
                                                            <i class="fas fa-clock text-warning me-2"></i>Set Pending
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="update_status.php?id=<?= $row['id_transaksi'] ?>&status=Cancelled">
                                                            <i class="fas fa-times text-danger me-2"></i>Set Cancelled
                                                        </a></li>
                                                    </ul>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="hapusTransaksi(<?= $row['id_transaksi'] ?>)" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                    $no++;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data transaksi</h5>
                            <p class="text-muted">Belum ada transaksi yang tercatat</p>
                            <a href="tambah_transaksi.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Transaksi Pertama
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&tanggal_dari=<?= urlencode($tanggal_dari) ?>&tanggal_sampai=<?= urlencode($tanggal_sampai) ?>">
                                <i class="fas fa-chevron-left"></i> Sebelumnya
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&tanggal_dari=<?= urlencode($tanggal_dari) ?>&tanggal_sampai=<?= urlencode($tanggal_sampai) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&tanggal_dari=<?= urlencode($tanggal_dari) ?>&tanggal_sampai=<?= urlencode($tanggal_sampai) ?>">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function hapusTransaksi(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data transaksi akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus_transaksi.php?id=' + id;
                }
            });
        }

        // Auto-submit form saat tanggal berubah
        document.querySelectorAll('input[type="date"]').forEach(function(input) {
            input.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Auto-submit form saat status berubah
        document.querySelector('select[name="status"]').addEventListener('change', function() {
            this.form.submit();
        });

        // Show toast notification if there's a success message
        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $_SESSION['success'] ?>',
                showConfirmButton: false,
                timer: 3000
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        // Show toast notification if there's an error message
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= $_SESSION['error'] ?>',
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>