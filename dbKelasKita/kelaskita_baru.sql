-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 04:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kelaskita_baru`
--

-- --------------------------------------------------------
CREATE TABLE `tb_bootcamp` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_bootcamp`
--

INSERT INTO `tb_bootcamp` (`id`, `judul`, `deskripsi`, `gambar`, `kategori_id`, `created_at`, `updated_at`) VALUES
(1, 'Full Stack Web Developer Bootcamp', 'Program intensif 12 minggu untuk menjadi Full Stack Web Developer dengan jaminan kerja', '../assets/images/47dd78487f5e0994dc32fe6a7f48f609.jpg', NULL, '2025-05-13 15:26:17', '2025-05-14 16:17:14'),
(2, 'Data Science & Machine Learning Bootcamp', 'Program intensif 16 minggu untuk menguasai Data Science dan Machine Learning', '../assets/images/a4df645483f9877ac9e95d189b662d53.jpg', NULL, '2025-05-13 15:26:17', '2025-05-14 16:18:42');


-- --------------------------------------------------------

CREATE TABLE `tb_bootcamp_fitur` (
  `id` int(11) NOT NULL,
  `bootcamp_id` int(11) NOT NULL,
  `fitur` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_bootcamp_fitur`
--

INSERT INTO `tb_bootcamp_fitur` (`id`, `bootcamp_id`, `fitur`, `created_at`) VALUES
(1, 1, 'Lebih dari 500 jam pembelajaran intensif', '2025-05-13 15:26:17'),
(2, 1, 'Portfolio dan proyek nyata', '2025-05-13 15:26:17'),
(3, 1, 'Jaminan penempatan kerja', '2025-05-13 15:26:17'),
(4, 2, 'Bimbingan dari praktisi Data Science', '2025-05-13 15:26:17'),
(5, 2, 'Project-based learning dengan data real', '2025-05-13 15:26:17'),
(6, 2, 'Career coaching dan networking', '2025-05-13 15:26:17');

--
-- Table structure for table `tb_dokumen`
--

CREATE TABLE `tb_dokumen` (
  `id_dokumen` int(30) NOT NULL,
  `file_path_dokumen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dokumen`
--

INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`) VALUES
(0, '../uploads/dokumen/68330a508dcc9_BUKU PANDUAN PKL 2024.pdf'),
(1, 'Pengenalan PHP.pdf'),
(2, 'Variabel dan Tipe Data.pdf'),
(3, 'Control Structure.pdf');

-- --------------------------------------------------------

CREATE TABLE `tb_kategori` (
  `id_kategori` int(30) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(30) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `jumlah_kursus` int(11) DEFAULT 0,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori_kelas`
--

CREATE TABLE `tb_kategori_kelas` (
  `id_kategori_kelas` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_kategori` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CCREATE TABLE `tb_kelas` (
  `id_kelas` int(30) NOT NULL,
  `id_mentor` int(30) NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `kategori` enum('SQL','Design','Java','Web Development','Bisnis','Ekonomi','Psikologi','IT','Python') NOT NULL,
  `jumlah_peserta` int(11) DEFAULT 0,
  `rating` decimal(2,1) DEFAULT 0.0,
  `harga` decimal(10,2) NOT NULL,
  `profil_kelas` varchar(100) DEFAULT 'default.jpg',
  `badge` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status_publikasi` enum('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft',
  `tanggal_rilis` date DEFAULT NULL,
  `ada_sertifikat` tinyint(1) DEFAULT 0,
  `tanggal_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data untuk tabel `tb_kelas`
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `jumlah_peserta`, `rating`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tanggal_rilis`, `instruktur_id`, `jumlah_ulasan`, `jumlah_pelajar`, `jumlah_video`, `jumlah_resource`, `ada_sertifikat`, `tanggal_update`) VALUES
(3, 4, 'Belajar PHP untuk Pemula', 'Web Development', 150, 4.5, 80000.00, 'default.jpg', NULL, 'Kelas belajar PHP dari dasar untuk pemula.', 'approved', '2024-10-01', NULL, 0, 0, 0, 0, 0, '2025-05-26 12:41:44'),
(4, 5, 'Kelas Bahasa Inggris (SD, SMP, SMA)', 'Bisnis', 200, 4.2, 100000.00, 'default.jpg', NULL, 'Kelas Bahasa Inggris untuk semua jenjang dengan metode interaktif.', 'approved', '2024-11-15', NULL, 0, 0, 0, 0, 0, '2025-05-26 12:41:44'),
(5, 5, 'Belajar PHP untuk Pemula 1', 'Web Development', 180, 4.9, 150000.00, 'default.jpg', NULL, 'Kelas lanjutan PHP untuk pemula yang sudah memiliki dasar.', 'approved', '2024-12-01', NULL, 0, 0, 0, 0, 0, '2025-05-26 13:14:54'),
(6, 5, 'Desain Grafis 1', 'Design', 120, 4.7, 80000.00, 'default.jpg', NULL, 'Belajar dasar desain grafis menggunakan tools populer.', 'approved', '2025-01-10', NULL, 0, 0, 0, 0, 0, '2025-05-26 13:14:47'),
(7, 6, 'Belajar PHP untuk Pemula', 'Web Development', 160, 4.8, 100000.00, 'default.jpg', NULL, 'Kelas belajar PHP untuk pemula yang lengkap dan praktis.', 'approved', '2025-01-20', NULL, 0, 0, 0, 0, 0, '2025-05-26 13:14:26');
-- Indeks untuk tabel `tb_kelas`
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);
-- AUTO_INCREMENT untuk tabel `tb_kelas`
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;
-- --------------------------------------------------------

--
-- Table structure for table `tb_keranjang`
--

CREATE TABLE `tb_keranjang` (
  `id_keranjang` int(30) NOT NULL,
  `tgl_keranjang` date NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_komentar`
--

CREATE TABLE `tb_komentar` (
  `id_komentar` int(30) NOT NULL,
  `isi` varchar(255) NOT NULL,
  `id_user` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_laporan`
--

CREATE TABLE `tb_laporan` (
  `id_report` int(30) NOT NULL,
  `kategori_report` enum('Penggunaan kata kasar','Matei tidak relevan','Pornografi') NOT NULL,
  `keterangan_report` varchar(100) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_materi`
--

CREATE TABLE `tb_materi` (
  `id_materi` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `urutan` int(11) DEFAULT 1,
  `judul_materi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_materi`
--

INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`) VALUES
(1, 3, 1, 'Pengenalan PHP'),
(2, 3, 1, 'Pengenalan PHP'),
(3, 3, 1, 'Pengenalan PHP'),
(4, 3, 2, 'Variabel dan Tipe Data'),
(5, 3, 2, 'Variabel dan Tipe Data'),
(6, 3, 3, 'Struktur Kontrol'),
(7, 3, 3, 'Struktur Kontrol'),
(8, 6, 1, 'Belajar Design Grafis pada tools figma'),
(9, 6, 2, 'Variabel dan Tipe Data'),
(10, 7, 1, 'Pengenalan PHP');

-- --------------------------------------------------------

--
-- Table structure for table `tb_mentor`
--

CREATE TABLE `tb_mentor` (
  `id_mentor` int(30) NOT NULL,
  `status` enum('Aktif','Non-Aktif') NOT NULL,
  `id_user` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_mentor`
--

INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(1, 'Aktif', 1),
(2, 'Aktif', 14),
(3, 'Aktif', 15),
(4, 'Aktif', 11),
(5, 'Aktif', 17),
(6, 'Aktif', 16);

-- --------------------------------------------------------

--
-- Table structure for table `tb_notifikasi`
--

CREATE TABLE `tb_notifikasi` (
  `id_notifikasi` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `pesan_notif` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
CREATE TABLE `tb_pengembangan_profesional` (
  `id` int(11) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `button_text` varchar(50) DEFAULT 'Pelajari Lebih Lanjut',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_pengembangan_profesional`
--

INSERT INTO `tb_pengembangan_profesional` (`id`, `icon`, `judul`, `deskripsi`, `link`, `button_text`, `created_at`, `updated_at`) VALUES
(1, 'fas fa-id-badge', 'Program Sertifikasi', 'Dapatkan sertifikasi profesional yang diakui industri untuk meningkatkan nilai di dunia kerja', 'certification.php', 'Lihat Sertifikasi', '2025-05-13 15:26:17', '2025-05-13 15:26:17'),
(2, 'fas fa-handshake', 'Career Coaching', 'Konsultasikan karier Anda dengan coach profesional untuk percepat kemajuan karier', 'career-coaching.php', 'Jadwalkan Sesi', '2025-05-13 15:26:17', '2025-05-13 15:26:17'),
(3, 'fas fa-building', 'Corporate Training', 'Program pelatihan khusus untuk perusahaan untuk meningkatkan keterampilan tim', 'corporate.php', 'Untuk Perusahaan', '2025-05-13 15:26:17', '2025-05-13 15:26:17');

ALTER TABLE `tb_pengembangan_profesional`
  ADD PRIMARY KEY (`id`);
  ALTER TABLE `tb_pengembangan_profesional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;
--
-- Table structure for table `tb_progress_kelas`
--

CREATE TABLE `tb_progress_kelas` (
  `id_progress_kelas` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `id_materi` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_review`
--

CREATE TABLE `tb_review` (
  `id_review` int(30) NOT NULL,
  `bintang_review` enum('1','2','3','4','5') NOT NULL,
  `isi_review` varchar(100) NOT NULL,
  `tgl_review` datetime NOT NULL DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sub_materi`
--

CREATE TABLE `tb_sub_materi` (
  `id_sub_materi` int(30) NOT NULL,
  `id_materi` int(30) NOT NULL,
  `id_dokumen` int(30) NOT NULL,
  `id_video` int(30) NOT NULL,
  `urutan` int(11) DEFAULT 1,
  `judul_sub_materi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_sub_materi`
--

INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`) VALUES
(8, 10, 0, 4, 1, 'Penginstalan Vs Code dan Extensi PHP');

-- --------------------------------------------------------
CREATE TABLE `tb_testimoni` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `posisi` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `quote` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Dumping data untuk tabel `tb_testimoni`
INSERT INTO `tb_testimoni` (`id`, `nama`, `posisi`, `avatar`, `quote`, `created_at`, `updated_at`) VALUES
(1, 'Ahmad Rizki', 'Full Stack Developer with Tokopedia', '../assets/images/c3913dc52d35241596ade71e69d29ab0.jpg', 'Berkat kursus di KelasKita, saya berhasil beralih karier dari seorang akuntan menjadi developer dalam waktu kurang dari 6 bulan. Materi yang diajarkan sangat relevan dengan kebutuhan industri.', '2025-05-13 15:26:17', '2025-05-21 14:00:42'),
(2, 'Chimika', 'UI/UX Designer with Gojek', '../assets/images/8c6ddb5fe6600fcc4b183cb2ee228eb7.jpg', 'Bootcamp UI/UX Design di KelasKita memberikan saya pengetahuan dan keterampilan yang dibutuhkan untuk masuk ke industri teknologi. Instrukturnya sangat supportif dan proyek-proyeknya menantang.', '2025-05-13 15:26:17', '2025-05-21 14:01:10'),
(3, 'Budi Pratama', 'Data Scientist with Bukalapak', '../assets/images/090ff51bf1b9e39ce8930063d7b252cf.jpg', 'Program Data Science sangat komprehensif dan up-to-date dengan teknologi terkini. Saya merekomendasikan KelasKita untuk siapa saja yang ingin menguasai Data Science dan Machine Learning.', '2025-05-13 15:26:17', '2025-05-21 14:01:27');
-- Indeks untuk tabel `tb_testimoni`
ALTER TABLE `tb_testimoni`
  ADD PRIMARY KEY (`id`);
-- AUTO_INCREMENT untuk tabel `tb_testimoni`
ALTER TABLE `tb_testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;
--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `id_keranjang` int(30) NOT NULL,
  `bukti_transaksi` varchar(50) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `status` enum('Completed','Pending','Cancelled') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
CREATE TABLE `tb_ulasan` (
  `id_ulasan` int(30) NOT NULL,
  `kursus_id` int(30) NOT NULL,
  `pelajar_id` int(30) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `komentar` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tb_ulasan` (`id_ulasan`, `kursus_id`, `pelajar_id`, `rating`, `komentar`, `tanggal`) VALUES
(1, 3, 1, 5, 'Kursus PHP yang sangat bagus dan mudah dipahami!', '2025-05-26 12:52:03'),
(2, 3, 3, 4, 'Materi lengkap, instruktur juga menjelaskan dengan baik.', '2025-05-26 12:52:03'),
(3, 4, 1, 5, 'Kelas Bahasa Inggris yang sangat membantu', '2025-05-26 12:52:03'),
(4, 5, 3, 4, 'Penjelasan step-by-step yang mudah diikuti', '2025-05-26 12:52:03'),
(5, 6, 1, 5, 'Design grafis untuk pemula sangat cocok', '2025-05-26 12:52:03'),
(6, 7, 3, 4, 'Kursus PHP pemula yang recommended', '2025-05-26 12:52:03');
-- Indeks untuk tabel `tb_ulasan`
ALTER TABLE `tb_ulasan`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `kursus_id` (`kursus_id`),
  ADD KEY `pelajar_id` (`pelajar_id`);
-- AUTO_INCREMENT untuk tabel `tb_ulasan`
ALTER TABLE `tb_ulasan`
  MODIFY `id_ulasan` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
-- Ketidakleluasaan untuk tabel `tb_ulasan`
ALTER TABLE `tb_ulasan`
  ADD CONSTRAINT `tb_ulasan_ibfk_1` FOREIGN KEY (`kursus_id`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_ulasan_ibfk_2` FOREIGN KEY (`pelajar_id`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE;
COMMIT;
--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(30) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('murid','mentor','admin') NOT NULL DEFAULT 'murid',
  `deskripsi` text DEFAULT NULL,
  `fotoProfil` varchar(50) NOT NULL,
  `bahasa` enum('Bahasa Indonesia','Inggris','Jepang') NOT NULL,
  `zona_waktu` enum('Jakarta','London','Tokyo') NOT NULL,
  `balasan_ke_komentar` tinyint(1) NOT NULL,
  `komentar_baru` tinyint(1) NOT NULL,
  `notifikasi_postingan_baru` tinyint(1) NOT NULL,
  `email` varchar(50) NOT NULL,
  `instagram` varchar(30) NOT NULL,
  `twitter` varchar(30) NOT NULL,
  `linkdin` varchar(30) NOT NULL,
  `github` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`) VALUES
(1, '', '', 'Aliq', '$2y$10$wGIB.sDNJO.rlHnln3.mI.UKwcOQs4bitXDOhnl6vGy3FSJPAg0hy', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(3, '', '', 'Rafi', '$2y$10$djBcFVMmkrJNDQOYE9D2Je/gDtva5uRmn4JonAuvuWa38mRzngi2m', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(11, '', '', 'Saya123', '$2y$10$uvkvgQ7H.cz.C76UrwKAu.S6hUXFTVwfivqdltQ1BdGPqTn1gkT9m', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(12, '', '', 'Kamu123', '$2y$10$l2YdSJBJ8AR3SunZlvm7E.qimAhHVQ5mIcFdFR4IXgxMTcppVEOCC', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(13, '', '', 'Gua123', '$2y$10$xbonLUo2ymeprYB6OAgmQut/fdN18FP5FsFF3rV/FcbauZNjMNnGK', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(14, '', '', 'Aliqns', '$2y$10$Uv1iau892wYDCp4i1PuJKeU0YX4nYe.uBbUSK3reYACNYY9EdTWMC', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(15, '', '', 'Galih', '$2y$10$/TPnN4zkLIvY0HMByXcfp.9oh8QdxCkGiSW1TMvbSDsoKaO6PsLIa', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(16, '', '', 'coba db baru', '$2y$10$TW9ZWuqYnaZpmxwk1X9JNeCCepSGDxwBk.4V7oi2JwzAAzBNxjF6O', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(17, '', '', 'blabla', '$2y$10$4imiVUlOZFABSuRzavGqxeghZqhCIFY6JCyfVXRsj0fKWbxqBYQWi', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_video`
--

CREATE TABLE `tb_video` (
  `id_video` int(30) NOT NULL,
  `file_path_video` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_video`
--

INSERT INTO `tb_video` (`id_video`, `file_path_video`) VALUES
(1, 'Video Pengenalan PHP.mp4'),
(2, 'Video Variabel PHP.mp4'),
(3, 'Video Control Structure.mp4'),
(4, '../uploads/video/68330a508fd51_lv_7392906537750072583_20240905064053.mp4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_dokumen`
--
ALTER TABLE `tb_dokumen`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `tb_kategori_kelas`
--
ALTER TABLE `tb_kategori_kelas`
  ADD PRIMARY KEY (`id_kategori_kelas`),
  ADD KEY `fkid_kategori_kk` (`id_kategori`),
  ADD KEY `fkid_kelas_kk` (`id_kelas`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `fkid_mentor_kelas` (`id_mentor`);

--
-- Indexes for table `tb_keranjang`
--
ALTER TABLE `tb_keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `fkid_user_keranjang` (`id_user`),
  ADD KEY `fkid_kelas_keranjang` (`id_kelas`);

--
-- Indexes for table `tb_komentar`
--
ALTER TABLE `tb_komentar`
  ADD PRIMARY KEY (`id_komentar`),
  ADD KEY `fkid_kelas` (`id_kelas`),
  ADD KEY `fkid_user` (`id_user`);

--
-- Indexes for table `tb_materi`
--
ALTER TABLE `tb_materi`
  ADD PRIMARY KEY (`id_materi`),
  ADD KEY `fkid_kelas_kelas` (`id_kelas`);

--
-- Indexes for table `tb_mentor`
--
ALTER TABLE `tb_mentor`
  ADD PRIMARY KEY (`id_mentor`),
  ADD KEY `fkid_user_user` (`id_user`);

--
-- Indexes for table `tb_sub_materi`
--
ALTER TABLE `tb_sub_materi`
  ADD PRIMARY KEY (`id_sub_materi`),
  ADD KEY `fkid_video_video` (`id_video`),
  ADD KEY `fkid_dokumen_dokumen` (`id_dokumen`),
  ADD KEY `fkid_materi_materi` (`id_materi`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tb_video`
--
ALTER TABLE `tb_video`
  ADD PRIMARY KEY (`id_video`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_materi`
--
ALTER TABLE `tb_materi`
  MODIFY `id_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_mentor`
--
ALTER TABLE `tb_mentor`
  MODIFY `id_mentor` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_sub_materi`
--
ALTER TABLE `tb_sub_materi`
  MODIFY `id_sub_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_video`
--
ALTER TABLE `tb_video`
  MODIFY `id_video` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_materi`
--
ALTER TABLE `tb_materi`
  ADD CONSTRAINT `fkid_kelas_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`);

--
-- Constraints for table `tb_mentor`
--
ALTER TABLE `tb_mentor`
  ADD CONSTRAINT `fkid_user_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_sub_materi`
--
ALTER TABLE `tb_sub_materi`
  ADD CONSTRAINT `fkid_dokumen_dokumen` FOREIGN KEY (`id_dokumen`) REFERENCES `tb_dokumen` (`id_dokumen`),
  ADD CONSTRAINT `fkid_materi_materi` FOREIGN KEY (`id_materi`) REFERENCES `tb_materi` (`id_materi`),
  ADD CONSTRAINT `fkid_video_video` FOREIGN KEY (`id_video`) REFERENCES `tb_video` (`id_video`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
