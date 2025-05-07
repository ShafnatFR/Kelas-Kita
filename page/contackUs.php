<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Serif+Text&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="">
</head>

<body class="font-poppins">
  <!-- Navbar --> 
  <header>
    <?php include "../Views/navbar.php"; ?>
  </header>

  <!-- Header Section -->
  <section class="flex justify-center items-center bg-cover bg-center h-64" style="background-image: url('gambar/banner.png');">
    <div class="text-center text-white">
      <h3 class="text-4xl font-bold">Kontak Kami</h3>
      <p class="text-gray-200">
        <a href="index.php" class="underline">Beranda</a> / Kontak Kami
      </p>
    </div>
  </section>

  <!-- Info Kontak -->
  <div class="max-w-6xl mx-auto text-center py-12">
    <h3 class="text-3xl font-bold mb-8">Kontak</h3>
    <div class="flex flex-wrap justify-center gap-8">
      <div class="w-full md:w-1/3 p-4 bg-white shadow rounded">
        <i class="fas fa-map-marker-alt text-3xl mb-2 text-red-500"></i>
        <h4 class="text-xl font-semibold">Alamat</h4>
        <p>Jl. Telekomunikasi No. 1, Terusan Buahbatu, Bojongsoang, Jawa Barat, Bandung.</p>
      </div>
      <div class="w-full md:w-1/3 p-4 bg-white shadow rounded">
        <i class="fas fa-phone-alt text-3xl mb-2 text-blue-500"></i>
        <h4 class="text-xl font-semibold">Telepon</h4>
        <p>0812-3456-7890</p>
      </div>
      <div class="w-full md:w-1/3 p-4 bg-white shadow rounded">
        <i class="fas fa-envelope text-3xl mb-2 text-green-500"></i>
        <h4 class="text-xl font-semibold">Email</h4>
        <p>kelaskita@gmail.com</p>
      </div>
    </div>
  </div>

  <!-- Formulir Kontak -->
  <div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white p-6 shadow rounded">
      <h3 class="text-2xl font-bold mb-2">Hubungi Kami</h3>
      <p class="mb-6">Silahkan isi form di bawah ini untuk menghubungi kami.</p>
      <form id="contactForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="inputName4" class="block mb-1">Nama Lengkap</label>
          <input type="text" id="inputName4" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div>
          <label for="inputEmail4" class="block mb-1">Email</label>
          <input type="email" id="inputEmail4" placeholder="emailanda@gmail.com" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="md:col-span-2">
          <label for="inputPhone4" class="block mb-1">Nomor HP</label>
          <input type="text" id="inputPhone4" placeholder="081234567891" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="md:col-span-2">
          <label for="exampleFormControlTextarea1" class="block mb-1">Pesan</label>
          <textarea id="exampleFormControlTextarea1" rows="3" class="w-full p-2 border border-gray-300 rounded" required></textarea>
        </div>
        <div class="md:col-span-2">
          <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Kirim</button>
        </div>
      </form>

      <!-- Modal Konfirmasi -->
      <div id="myModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
          <h2 class="text-lg font-bold mb-4">Konfirmasi Pengiriman</h2>
          <p class="mb-4">Apakah Anda yakin ingin mengirim pesan ini?</p>
          <div class="flex justify-end gap-2">
            <button onclick="closeModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Kembali</button>
            <button onclick="sendMessage()" class="bg-blue-600 text-white px-4 py-2 rounded">Kirim</button>
          </div>
        </div>
      </div>

      <!-- Alert Sukses -->
      <div id="successAlert" class="hidden bg-green-100 text-green-800 p-4 mt-4 rounded">
        Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.
      </div>
    </div>
  </div>

  <!-- Google Maps -->
  <section class="mt-10">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63340.09002456192!2d107.6348359!3d-6.9716449!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9adf177bf8d%3A0x437398556f9fa03!2sUniversitas%20Telkom!5e0!3m2!1sen!2sid!4v1687575022237!5m2!1sen!2sid"
      width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>
  </section>

  <!-- Footer -->
  <?php include "../Views/footer.php"; ?>

  <!-- Script -->
  <script>
    const modal = document.getElementById('myModal');
    const successAlert = document.getElementById('successAlert');

    document.getElementById('contactForm').addEventListener('submit', function (e) {
      e.preventDefault();
      modal.classList.remove('hidden');
    });

    function closeModal() {
      modal.classList.add('hidden');
    }

    function sendMessage() {
      closeModal();
      successAlert.classList.remove('hidden');
      document.getElementById('contactForm').reset();
    }
  </script>
</body>
</html>
