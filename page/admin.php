<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

    <!-- Header -->
    <header class="bg-primary text-white text-center py-3">
        <h1>Halaman Admin</h1>
    </header>

    <!-- Konten Utama -->
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    
                    <!-- Sidebar -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <img src="..." class="card-img-top" alt="Foto Profil">
                                    <hr class="mb-0">
                                    <div class="card-body">
                                        <h5 class="card-title mb-0">Nama</h5>
                                    </div>
                                    <div class="d-grid gap-2 p-3">
                                        <a href="admin.php" class="btn btn-outline-primary">Dashboard</a>
                                        <a href="admin.php" class="btn btn-outline-primary">Block</a>
                                        <a href="admin.php" class="btn btn-outline-primary">Exit</a>
                                    </div>
                                </div>
                            </div>
                </div>
            </div>
            <!-- Konten Kanan -->
            <div class="col-md-9">
                <div class="row">
                    
                    <!-- Card 1 -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <ul class="list-group list-group-flush text-center">
                                <li class="list-group-item">Jumlah Pengunjung</li>
                                <li class="list-group-item">Jumlah Transaksi</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <ul class="list-group list-group-flush text-center">
                                <li class="list-group-item">Jumlah Pengunjung</li>
                                <li class="list-group-item">Jumlah Transaksi</li>
                            </ul>
                        </div>
                    </div>
                
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <ul class="list-group list-group-flush text-center">
                                <li class="list-group-item">Jumlah Pengunjung</li>
                                <li class="list-group-item">Jumlah Transaksi</li>
                            </ul>
                        </div>
                    </div>
                </div>
<!--  -->
            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Kategori</th>
                                <th>Keluhan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

            </div> <!-- Tutup col-md-9 -->

        </div> <!-- Tutup row -->
    </div> <!-- Tutup container -->

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-5">
        <p class="mb-0">&copy; 2025 Admin Panel</p>
    </footer>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
