-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 06:58 AM
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

--
-- Table structure for table `tb_dokumen`
--

CREATE TABLE `tb_dokumen` (
  `id_dokumen` int(30) NOT NULL,
  `file_path_dokumen` varchar(255) NOT NULL,
  `deskripsi_d` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dokumen`
--

INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `deskripsi_d`) VALUES
(1, 'Pengenalan PHP.pdf', 'Dokumen berisi pengenalan dasar PHP untuk pemula'),
(2, 'Variabel dan Tipe Data.pdf', 'Penjelasan tentang variabel dan tipe data dalam PHP'),
(3, 'Control Structure.pdf', 'Materi tentang if-else, loop, dan struktur kontrol PHP');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(30) NOT NULL,
  `nama_kategori` varchar(30) NOT NULL
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

-- Membuat tabel tb_kelas dengan struktur yang diperbaiki
CREATE TABLE `tb_kelas` (
  `id_kelas` int(30) NOT NULL AUTO_INCREMENT,
  `id_mentor` int(30) NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `kategori` enum('SQL','Design','Java','Web Development','Bisnis','Ekonomi','Psikologi','IT','Python') NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `profil_kelas` varchar(100) DEFAULT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status_publikasi` enum('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft',
  `tanggal_rilis` DATE DEFAULT NULL,
  PRIMARY KEY (`id_kelas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data dengan kategori yang sudah diperbaiki dan id_mentor yang ditambahkan
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tanggal_rilis`) VALUES
(1, 1, 'Full Stack Web Development', 'Web Development', 500000.00, 'fullstack_profile.jpg', 'advanced_badge.png', 'Belajar membuat website lengkap dari frontend hingga backend dengan teknologi modern', 'approved', '2024-01-15'),
(2, 2, 'UI/UX Design Fundamentals', 'Design', 300000.00, 'uiux_profile.jpg', 'beginner_badge.png', 'Dasar-dasar desain antarmuka dan pengalaman pengguna yang menarik', 'approved', '2024-01-20'),
(3, 3, 'Digital Marketing Strategy', 'Bisnis', 400000.00, 'marketing_profile.jpg', 'intermediate_badge.png', 'Strategi pemasaran digital untuk mengembangkan bisnis online', 'approved', '2024-02-01'),
(4, 4, 'Python Data Analysis', 'Python', 450000.00, 'python_profile.jpg', 'advanced_badge.png', 'Analisis data menggunakan Python dan library populer seperti Pandas dan NumPy', 'approved', '2024-02-10'),
(5, 5, 'Mobile App Development', 'Web Development', 600000.00, 'mobile_profile.jpg', 'expert_badge.png', 'Membuat aplikasi mobile dengan React Native dan Flutter', 'pending', NULL),
(6, 6, 'SQL Database Management', 'SQL', 350000.00, 'sql_profile.jpg', 'intermediate_badge.png', 'Mengelola database dengan SQL dari dasar hingga query kompleks', 'approved', '2024-02-15'),
(7, 7, 'JavaScript Modern', 'Web Development', 380000.00, 'js_profile.jpg', 'intermediate_badge.png', 'Belajar JavaScript ES6+ dan framework modern seperti React dan Vue', 'approved', '2024-02-20'),
(8, 8, 'Graphic Design Mastery', 'Design', 320000.00, 'graphic_profile.jpg', 'advanced_badge.png', 'Menguasai desain grafis dengan Adobe Creative Suite', 'approved', '2024-03-01'),
(9, 9, 'SEO & Content Marketing', 'Bisnis', 250000.00, 'seo_profile.jpg', 'beginner_badge.png', 'Optimasi mesin pencari dan strategi content marketing', 'draft', NULL),
(10, 10, 'Cloud Computing AWS', 'IT', 550000.00, 'aws_profile.jpg', 'expert_badge.png', 'Membangun infrastruktur cloud menggunakan Amazon Web Services', 'approved', '2024-03-10');

-- Set AUTO_INCREMENT untuk memulai dari 11 untuk data selanjutnya
ALTER TABLE `tb_kelas` AUTO_INCREMENT = 11;

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
  `deskripsi_m` text NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `urutan` int(11) DEFAULT 1,
  `judul_materi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_materi`
--

INSERT INTO `tb_materi` (`id_materi`, `deskripsi_m`, `id_kelas`, `urutan`, `judul_materi`) VALUES
(1, 'Bab pertama: Mengenal PHP dan dasar-dasarnya', 3, 1, 'Pengenalan PHP'),
(2, 'Bab pertama: Instalasi dan setup environment', 3, 1, 'Pengenalan PHP'),
(3, 'Bab pertama: Memahami sintaks dasar PHP', 3, 1, 'Pengenalan PHP'),
(4, 'Bab kedua: Bekerja dengan variabel', 3, 2, 'Variabel dan Tipe Data'),
(5, 'Bab kedua: Memahami tipe data', 3, 2, 'Variabel dan Tipe Data'),
(6, 'Bab ketiga: Pengambilan keputusan', 3, 3, 'Struktur Kontrol'),
(7, 'Bab ketiga: Perulangan dalam PHP', 3, 3, 'Struktur Kontrol');

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
(5, 'Aktif', 17);

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
  `deskripsi_sm` text NOT NULL,
  `id_materi` int(30) NOT NULL,
  `id_dokumen` int(30) NOT NULL,
  `id_video` int(30) NOT NULL,
  `urutan` int(11) DEFAULT 1,
  `judul_sub_materi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

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
(16, '', '', 'coba db baru', '$2y$10$TW9ZWuqYnaZpmxwk1X9JNeCCepSGDxwBk.4V7oi2JwzAAzBNxjF6O', 'murid', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(17, '', '', 'blabla', '$2y$10$4imiVUlOZFABSuRzavGqxeghZqhCIFY6JCyfVXRsj0fKWbxqBYQWi', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_video`
--

CREATE TABLE `tb_video` (
  `id_video` int(30) NOT NULL,
  `file_path_video` varchar(255) NOT NULL,
  `deskripsi_v` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_video`
--

INSERT INTO `tb_video` (`id_video`, `file_path_video`, `deskripsi_v`) VALUES
(1, 'Video Pengenalan PHP.mp4', 'Video tutorial pengenalan PHP untuk pemula'),
(2, 'Video Variabel PHP.mp4', 'Video tutorial tentang penggunaan variabel dalam PHP'),
(3, 'Video Control Structure.mp4', 'Video tutorial tentang if-else dan loop di PHP');

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
-- AUTO_INCREMENT for table `tb_materi`
--
ALTER TABLE `tb_materi`
  MODIFY `id_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_mentor`
--
ALTER TABLE `tb_mentor`
  MODIFY `id_mentor` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_sub_materi`
--
ALTER TABLE `tb_sub_materi`
  MODIFY `id_sub_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_video`
--
ALTER TABLE `tb_video`
  MODIFY `id_video` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
