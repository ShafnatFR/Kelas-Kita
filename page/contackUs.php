<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Us</title>

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .banner {
      background: url('gambar/banner.png') no-repeat center center/cover;
      height: 250px;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <header>
    <?php include "../Views/navbarbootstrap.php"; ?>
  </header>

  <!-- Banner -->
  <section class="d-flex justify-content-center align-items-center banner text-white text-center">
    <div>
      <h3 class="fw-bold display-5">Kontak Kami</h3>
      <p class="text-light">
        <a href="index.php" class="text-white text-decoration-underline">Beranda</a> / Kontak Kami
      </p>
    </div>
  </section>

  <!-- Info Kontak -->
  <div class="container text-center py-5">
    <h3 class="fw-bold mb-4">Kontak</h3>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="p-4 bg-white shadow rounded">
          <i class="fas fa-map-marker-alt fa-2x mb-2 text-danger"></i>
          <h4 class="fw-semibold">Alamat</h4>
          <p>Jl. Telekomunikasi No. 1, Terusan Buahbatu, Bojongsoang, Jawa Barat, Bandung.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white shadow rounded">
          <i class="fas fa-phone-alt fa-2x mb-2 text-primary"></i>
          <h4 class="fw-semibold">Telepon</h4>
          <p>0812-3456-7890</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white shadow rounded">
          <i class="fas fa-envelope fa-2x mb-2 text-success"></i>
          <h4 class="fw-semibold">Email</h4>
          <p>kelaskita@gmail.com</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Formulir Kontak -->
  <div class="container py-5">
    <div class="bg-white p-4 shadow rounded">
      <h3 class="fw-bold mb-2">Hubungi Kami</h3>
      <p class="mb-4">Silahkan isi form di bawah ini untuk menghubungi kami.</p>
      <form id="contactForm" class="row g-3">
        <div class="col-md-6">
          <label for="inputName4" class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" id="inputName4" required>
        </div>
        <div class="col-md-6">
          <label for="inputEmail4" class="form-label">Email</label>
          <input type="email" class="form-control" id="inputEmail4" placeholder="emailanda@gmail.com" required>
        </div>
        <div class="col-12">
          <label for="inputPhone4" class="form-label">Nomor HP</label>
          <input type="text" class="form-control" id="inputPhone4" placeholder="081234567891" required>
        </div>
        <div class="col-12">
          <label for="exampleFormControlTextarea1" class="form-label">Pesan</label>
          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-danger">Kirim</button>
        </div>
      </form>

      <!-- Modal -->
      <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Pengiriman</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
              Apakah Anda yakin ingin mengirim pesan ini?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
              <button type="button" class="btn btn-primary" onclick="sendMessage()">Kirim</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Alert -->
      <div id="successAlert" class="alert alert-success mt-4 d-none" role="alert">
        Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.
      </div>
    </div>
  </div>

  <!-- Google Maps -->
  <section class="mt-4">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63340.09002456192!2d107.6348359!3d-6.9716449!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9adf177bf8d%3A0x437398556f9fa03!2sUniversitas%20Telkom!5e0!3m2!1sen!2sid!4v1687575022237!5m2!1sen!2sid"
      width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>
  </section>

  <!-- Footer -->
  <?php include "../Views/footerbootsrap.php"; ?>

  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const contactForm = document.getElementById('contactForm');
    const successAlert = document.getElementById('successAlert');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      confirmModal.show();
    });

    function sendMessage() {
      confirmModal.hide();
      successAlert.classList.remove('d-none');
      contactForm.reset();
    }
  </script>
</body>
</html>
