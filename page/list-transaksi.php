<?php
session_start();

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Kelaskita_baru'; // Ganti dengan nama database Anda

$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$transactions = []; // Initialize an empty array to store transactions
$error_message = '';

try {
    // SQL query to fetch all transactions
    // If you want to filter by a specific user, uncomment the WHERE clause below
$sql = "SELECT tb_transaksi.id_transaksi, tb_transaksi.bukti_transaksi, tb_transaksi.tgl_transaksi, tb_transaksi.status, tb_transaksi.list_transaksi,
            tb_user.first_name, tb_user.last_name, tb_user.email, tb_user.no_telepon
            FROM tb_transaksi
            JOIN tb_user ON tb_transaksi.id_user = tb_user.id_user
            ORDER BY tb_transaksi.tgl_transaksi DESC, tb_transaksi.id_transaksi DESC"; // Order by latest transaction first

    // Example to filter by logged-in user (uncomment and adjust if needed)
    // if (isset($_SESSION['user_id'])) {
    //     $user_id = $_SESSION['user_id']; // Get user ID from session
    //     $sql = "SELECT id_transaksi, id_kelas, id_user, id_keranjang, bukti_transaksi, tgl_transaksi, status, list_transaksi
    //             FROM tb_transaksi
    //             WHERE id_user = ?
    //             ORDER BY tgl_transaksi DESC, id_transaksi DESC";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param("i", $user_id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    // } else {
        $result = $conn->query($sql);
    // }

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $transactions[] = $row;
            }
        }
        $result->free();
    } else {
        $error_message = "Error fetching transactions: " . $conn->error;
    }

    // Close the statement if it was prepared
    if (isset($stmt)) {
        $stmt->close();
    }

} catch (Exception $e) {
    $error_message = "Terjadi kesalahan: " . $e->getMessage();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            --bs-table-bg-type: var(--bs-table-striped-bg);
            background-color: rgba(0, 0, 0, 0.03); /* Lighter stripe */
        }
        .table thead {
            background-color: #667eea;
            color: white;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge-status {
            padding: 0.5em 0.75em;
            border-radius: 0.5rem;
            font-size: 0.85em;
            font-weight: 600;
        }
        .badge-pending { background-color: #ffc107; color: #856404; } /* yellow */
        .badge-completed { background-color: #28a745; color: #fff; } /* green */
        .badge-cancelled { background-color: #dc3545; color: #fff; } /* red */
        .btn-view-proof {
            background-color: #0d6efd;
            color: white;
            border-radius: 8px;
            padding: 8px 15px;
            font-size: 0.9em;
        }
        .btn-view-proof:hover {
            background-color: #0b5ed7;
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        .empty-state i {
            font-size: 3em;
            color: #ccc;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header-gradient text-center">
        <div class="container">
            <h1 class="mb-0"><i class="fas fa-history me-3"></i>Riwayat Transaksi</h1>
            <p class="mb-0 mt-2 opacity-75">Daftar semua transaksi Anda</p>
        </div>
    </div>

    <div class="container">
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($transactions)): ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>Belum ada transaksi</h3>
                <p>Anda belum melakukan transaksi apapun. Ayo mulai jelajahi kelas kami!</p>
                <a href="index.php" class="btn btn-primary-custom">Mulai Belanja</a>
            </div>
        <?php else: ?>
            <div class="card card-custom">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tgl. Transaksi</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">No. WhatsApp</th>
                                    <!-- Uncomment the following if you add these columns to your tb_transaksi table -->
                                    <!-- <th scope="col">Nama Pembeli</th> -->
                                    <!-- <th scope="col">No. HP</th> -->
                                    <!-- <th scope="col">Email</th> -->
                                    <th scope="col">Bukti Transfer</th>
                                    <th scope="col">Jenis Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $index => $transaction): ?>
                                <tr>
                                    <th scope="row"><?php echo $index + 1; ?></th>
                                    <td><?php echo htmlspecialchars($transaction['tgl_transaksi']); ?></td>
                                    <td>
                                        <?php
                                            $status_class = '';
                                            switch ($transaction['status']) {
                                                case 'Completed':
                                                    $status_class = 'badge-completed';
                                                    break;
                                                case 'Pending':
                                                    $status_class = 'badge-pending';
                                                    break;
                                                case 'Cancelled':
                                                    $status_class = 'badge-cancelled';
                                                    break;
                                                default:
                                                    $status_class = 'bg-secondary text-white'; // Default or unknown status
                                                    break;
                                            }
                                        ?>
                                        <span class="badge badge-status <?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($transaction['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($transaction['first_name'] . ' ' . $transaction['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['email']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['no_telepon']); ?></td>
                                    <!-- Uncomment the following if you add these columns to your tb_transaksi table -->
                                    <!-- <td><?php // echo htmlspecialchars($transaction['nama_pembeli'] ?? '-'); ?></td> -->
                                    <!-- <td><?php // echo htmlspecialchars($transaction['no_hp_pembeli'] ?? '-'); ?></td> -->
                                    <!-- <td><?php // echo htmlspecialchars($transaction['email_pembeli'] ?? '-'); ?></td> -->
                                    <td>
                                        <?php if (!empty($transaction['bukti_transaksi'])): ?>
                                            <a href="uploads/bukti_transaksi/<?php echo htmlspecialchars($transaction['bukti_transaksi']); ?>" 
                                               target="_blank" class="btn btn-sm btn-view-proof" title="Lihat Bukti Transfer">
                                                <i class="fas fa-eye me-1"></i> Lihat Bukti
                                            </a>
                                        <?php else: ?>
                                            Tidak ada bukti
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($transaction['list_transaksi']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>