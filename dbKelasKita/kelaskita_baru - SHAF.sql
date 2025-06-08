-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 05:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `status` enum('pending','aktif','non-aktif') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dokumen`
--

INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(0, '../uploads/dokumen/DOC000_PENGANTAR_PEMROGRAMAN_WEB.pdf', 'aktif'),
(1, '../uploads/dokumen/DOC001_MODUL_HTML_DASAR.pdf', 'aktif'),
(2, '../uploads/dokumen/DOC002_MODUL_CSS_STYLING.pdf', 'aktif'),
(3, '../uploads/dokumen/DOC003_JS_INTERAKTIF.pdf', 'aktif'),
(4, '../uploads/dokumen/DOC004_PANDUAN_SQL_BASIC.pdf', 'aktif'),
(5, '../uploads/dokumen/DOC005_DESAIN_GRAFIS_TOOLS.pdf', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(30) NOT NULL,
  `nama_kategori` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Web Development'),
(2, 'SQL'),
(3, 'Design Grafis'),
(4, 'Python'),
(5, 'Bisnis Digital'),
(6, 'Java');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori_kelas`
--

CREATE TABLE `tb_kategori_kelas` (
  `id_kategori_kelas` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_kategori` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kategori_kelas`
--

INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 1),
(4, 4, 4),
(5, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` int(30) NOT NULL,
  `id_mentor` int(30) NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `kategori` enum('SQL','Design','Java','Web Development','Bisnis','Ekonomi','Psikologi','IT','Python') NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `profil_kelas` varchar(100) DEFAULT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status_publikasi` enum('pending','aktif','non-aktif') NOT NULL DEFAULT 'pending',
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(1, 1, 'Dasar Pemrograman Web (HTML, CSS, JS)', 'Web Development', 150000.00, NULL, NULL, 'Pelajari dasar-dasar pembuatan website interaktif dari nol.', 'aktif', '2025-06-01 15:29:17'),
(2, 2, 'Mastering SQL: Dari Dasar hingga Lanjutan', 'SQL', 120000.00, NULL, NULL, 'Kuasai query SQL untuk manajemen dan analisis data.', 'aktif', '2025-06-01 15:29:17'),
(3, 1, 'Full-Stack Web Developer dengan PHP & Laravel', 'Web Development', 250000.00, NULL, NULL, 'Menjadi full-stack developer dengan framework PHP populer.', 'aktif', '2025-06-01 15:29:17'),
(4, 3, 'Analisis Data dengan Python untuk Pemula', 'Python', 180000.00, NULL, NULL, 'Pengenalan analisis data menggunakan bahasa Python dan library terkait.', 'aktif', '2025-06-01 15:29:17'),
(5, 2, 'Desain Grafis Fundamental dengan Adobe Illustrator', 'Design', 100000.00, NULL, NULL, 'Belajar dasar-dasar desain grafis dan penggunaan Adobe Illustrator.', 'aktif', '2025-06-01 15:29:17');

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

--
-- Dumping data for table `tb_keranjang`
--

INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(1, '2025-06-01', 1, 4),
(2, '2025-06-01', 2, 4),
(3, '2025-05-30', 4, 5),
(4, '2025-06-01', 5, 5);

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
  `id_report` int(11) NOT NULL,
  `kategori_report` enum('Penggunaan kata kasar','Matei tidak relevan','Pornografi') NOT NULL,
  `keterangan_report` varchar(100) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_materi`
--

CREATE TABLE `tb_materi` (
  `id_materi` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `urutan` int(11) DEFAULT 1,
  `judul_materi` varchar(255) NOT NULL,
  `status` enum('pending','aktif','non-aktif') NOT NULL DEFAULT 'pending',
  `tgl_dibuat_materi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_materi`
--

INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(1, 1, 1, 'Pengenalan Web & HTML Dasar', 'aktif', '2025-06-01 15:29:17'),
(2, 1, 2, 'Styling dengan CSS', 'aktif', '2025-06-01 15:29:17'),
(3, 1, 3, 'Interaktivitas dengan JavaScript', 'pending', '2025-06-01 15:29:17'),
(4, 2, 1, 'Pengenalan Database dan SQL', 'aktif', '2025-06-01 15:29:17'),
(5, 2, 2, 'Query Dasar: SELECT, FROM, WHERE', 'aktif', '2025-06-01 15:29:17'),
(6, 4, 1, 'Setup Lingkungan Python untuk Data', 'aktif', '2025-06-01 15:29:17'),
(7, 5, 1, 'Pengantar Tools Desain Grafis', 'aktif', '2025-06-01 15:29:17');

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
(1, 'Aktif', 2),
(2, 'Aktif', 3),
(3, 'Aktif', 6);

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

--
-- Dumping data for table `tb_review`
--

INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(1, '5', 'Kelasnya keren dan sangat membantu pemula seperti saya!', '2025-06-01 15:29:17', 4, 1),
(2, '4', 'Penjelasan SQLnya cukup detail, tapi mungkin perlu contoh kasus lebih banyak.', '2025-06-01 15:29:17', 4, 2);

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
  `judul_sub_materi` varchar(255) NOT NULL,
  `status` enum('pending','aktif','non-aktif') NOT NULL DEFAULT 'pending',
  `tgl_dibuat_subMateri` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_sub_materi`
--

INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(1, 1, 0, 0, 1, 'Struktur Dasar HTML', 'aktif', '2025-06-01 15:29:17'),
(2, 1, 1, 1, 2, 'Elemen dan Tag HTML Penting', 'aktif', '2025-06-01 15:29:17'),
(3, 2, 2, 2, 1, 'Selector dan Properti CSS', 'aktif', '2025-06-01 15:29:17'),
(4, 4, 4, 4, 1, 'Konsep Relational Database', 'aktif', '2025-06-01 15:29:17'),
(5, 5, 4, 4, 2, 'Praktik Query SELECT dan WHERE', 'aktif', '2025-06-01 15:29:17'),
(6, 6, 0, 5, 1, 'Instalasi Anaconda dan Jupyter Notebook', 'aktif', '2025-06-01 15:29:17'),
(7, 7, 5, 0, 1, 'Mengenal Adobe Illustrator dan Figma', 'aktif', '2025-06-01 15:29:17');

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
  `tgl_transaksi` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','acc') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(1, 1, 4, 1, 'bukti_TRX001.jpg', '2025-06-01 15:29:17', 'acc'),
(2, 2, 4, 2, 'bukti_TRX002.jpg', '2025-06-01 15:29:17', 'acc'),
(3, 4, 5, 3, 'bukti_TRX003.jpg', '2025-06-01 15:29:17', 'pending');

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
  `status` enum('aktif','non-aktif') NOT NULL DEFAULT 'aktif',
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
  `github` varchar(30) NOT NULL,
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(1, 'Admin', 'Kelaskita', 'admin01', '$2y$10$wGIB.sDNJO.rlHnln3.mI.UKwcOQs4bitXDOhnl6vGy3FSJPAg0hy', 'admin', 'aktif', 'Administrator Utama Website Kelaskita', 'admin_profile.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'admin@kelaskita.com', '', '', '', '', '2025-06-01 15:29:17'),
(2, 'Budi', 'Santoso', 'budi_mentor', '$2y$10$djBcFVMmkrJNDQOYE9D2Je/gDtva5uRmn4JonAuvuWa38mRzngi2m', 'mentor', 'aktif', 'Mentor Web Development dengan pengalaman 5 tahun.', 'budi_santoso.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'budi.mentor@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(3, 'Citra', 'Wirawan', 'citra_mentor', '$2y$10$uvkvgQ7H.cz.C76UrwKAu.S6hUXFTVwfivqdltQ1BdGPqTn1gkT9m', 'mentor', 'aktif', 'Ahli Database dan SQL.', 'citra_wirawan.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'citra.mentor@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(4, 'Dewi', 'Lestari', 'dewi_murid', '$2y$10$l2YdSJBJ8AR3SunZlvm7E.qimAhHVQ5mIcFdFR4IXgxMTcppVEOCC', 'murid', 'aktif', 'Pelajar antusias di bidang teknologi.', 'dewi_lestari.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'dewi.murid@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(5, 'Eko', 'Prasetyo', 'eko_murid', '$2y$10$xbonLUo2ymeprYB6OAgmQut/fdN18FP5FsFF3rV/FcbauZNjMNnGK', 'murid', 'aktif', 'Tertarik dengan desain grafis dan UI/UX.', 'eko_prasetyo.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'eko.murid@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(6, 'Fajar', 'Nugraha', 'fajar_mentor', '$2y$10$Uv1iau892wYDCp4i1PuJKeU0YX4nYe.uBbUSK3reYACNYY9EdTWMC', 'mentor', 'aktif', 'Spesialis Python dan Data Science.', 'fajar_nugraha.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'fajar.mentor@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(7, 'Gina', 'Hardiman', 'gina_murid', '$2y$10$/TPnN4zkLIvY0HMByXcfp.9oh8QdxCkGiSW1TMvbSDsoKaO6PsLIa', 'murid', 'non-aktif', 'Sedang non-aktif.', 'gina_hardiman.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'gina.murid@example.com', '', '', '', '', '2025-06-01 15:29:17');

-- --------------------------------------------------------

--
-- Table structure for table `tb_video`
--

CREATE TABLE `tb_video` (
  `id_video` int(30) NOT NULL,
  `file_path_video` varchar(255) NOT NULL,
  `status` enum('pending','aktif','non-aktif') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_video`
--

INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(0, '../uploads/video/VID000_INTRO_WEB.mp4', 'aktif'),
(1, '../uploads/video/VID001_HTML_TAGS.mp4', 'aktif'),
(2, '../uploads/video/VID002_CSS_SELECTORS.mp4', 'aktif'),
(3, '../uploads/video/VID003_JS_DOM_MANIPULATION.mp4', 'aktif'),
(4, '../uploads/video/VID004_SQL_JOINS.mp4', 'aktif'),
(5, '../uploads/video/VID005_PYTHON_PANDAS.mp4', 'aktif');

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
-- Indexes for table `tb_laporan`
--
ALTER TABLE `tb_laporan`
  ADD PRIMARY KEY (`id_report`);

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
-- AUTO_INCREMENT for table `tb_komentar`
--
ALTER TABLE `tb_komentar`
  MODIFY `id_komentar` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tb_laporan`
--
ALTER TABLE `tb_laporan`
  MODIFY `id_report` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_video`
--
ALTER TABLE `tb_video`
  MODIFY `id_video` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
