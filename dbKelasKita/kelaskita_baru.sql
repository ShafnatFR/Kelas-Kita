-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
<<<<<<< HEAD
-- Waktu pembuatan: 10 Jun 2025 pada 06.18
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12
-- Generation Time: Jun 10, 2025 at 08:36 AM
=======
<<<<<<< HEAD
-- Generation Time: Jun 11, 2025 at 05:53 PM
=======
-- Generation Time: May 25, 2025 at 04:22 PM
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
>>>>>>> 0c80503c83755f2a7ea2c0af999c1a1b9821acba
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
-- Struktur dari tabel `tb_dokumen`

--
-- Table structure for table `tb_dokumen`
--

CREATE TABLE `tb_dokumen` (
  `id_dokumen` int(30) NOT NULL,
  `file_path_dokumen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

<<<<<<< HEAD
=======
--
-- Dumping data untuk tabel `tb_dokumen`
--

INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(0, '../uploads/dokumen/DOC000_PENGANTAR_PEMROGRAMAN_WEB.pdf', 'aktif'),
(1, '../uploads/dokumen/DOC001_MODUL_HTML_DASAR.pdf', 'aktif'),
(2, '../uploads/dokumen/DOC002_MODUL_CSS_STYLING.pdf', 'aktif'),
(3, '../uploads/dokumen/DOC003_JS_INTERAKTIF.pdf', 'aktif'),
(4, '../uploads/dokumen/DOC004_PANDUAN_SQL_BASIC.pdf', 'aktif'),
(5, '../uploads/dokumen/DOC005_DESAIN_GRAFIS_TOOLS.pdf', 'aktif'),
(6, '../uploads/dokumen/DUMMYDOC_6.pdf', 'pending'),
(7, '../uploads/dokumen/DUMMYDOC_7.pdf', 'non-aktif'),
(8, '../uploads/dokumen/DUMMYDOC_8.pdf', 'non-aktif'),
(9, '../uploads/dokumen/DUMMYDOC_9.pdf', 'aktif'),
(10, '../uploads/dokumen/DUMMYDOC_10.pdf', 'non-aktif'),
(11, '../uploads/dokumen/DUMMYDOC_11.pdf', 'aktif'),
(12, '../uploads/dokumen/DUMMYDOC_12.pdf', 'non-aktif'),
(13, '../uploads/dokumen/DUMMYDOC_13.pdf', 'aktif'),
(14, '../uploads/dokumen/DUMMYDOC_14.pdf', 'non-aktif'),
(15, '../uploads/dokumen/DUMMYDOC_15.pdf', 'non-aktif'),
(16, '../uploads/dokumen/DUMMYDOC_16.pdf', 'aktif'),
(17, '../uploads/dokumen/DUMMYDOC_17.pdf', 'pending'),
(18, '../uploads/dokumen/DUMMYDOC_18.pdf', 'non-aktif'),
(19, '../uploads/dokumen/DUMMYDOC_19.pdf', 'aktif'),
(20, '../uploads/dokumen/DUMMYDOC_20.pdf', 'pending'),
(21, '../uploads/dokumen/DUMMYDOC_21.pdf', 'aktif'),
(22, '../uploads/dokumen/DUMMYDOC_22.pdf', 'non-aktif'),
(23, '../uploads/dokumen/DUMMYDOC_23.pdf', 'aktif'),
(24, '../uploads/dokumen/DUMMYDOC_24.pdf', 'pending'),
(25, '../uploads/dokumen/DUMMYDOC_25.pdf', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kategori`
--

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(30) NOT NULL,
  `nama_kategori` varchar(30) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `jumlah_kursus` int(11) DEFAULT 0
  `id_kategori` int(30) NOT NULL,
  `nama_kategori` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `nama_kategori`, `icon`, `jumlah_kursus`) VALUES
(1, 'Pengembangan Web', 'fa-solid fa-code', 32),
(2, 'Data Science', 'fa-solid fa-book', 28),
(3, 'Mobile Development', 'fa-solid fa-mobile-screen-button', 24),
(4, 'UI/UX Design', 'fa-solid fa-pen-nib', 22);

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
-- Struktur dari tabel `tb_kategori_kelas`
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
(5, 5, 3),
(6, 17, 2),
(7, 10, 3),
(8, 17, 3),
(9, 13, 1),
(10, 16, 5),
(11, 10, 2),
(12, 11, 4),
(13, 16, 1),
(14, 15, 6),
(15, 12, 5);

>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kelas`
--

CREATE TABLE `tb_kelas` (
CREATE TABLE `tb_kelas` (
  `id_kelas` int(30) NOT NULL,
  `id_mentor` int(30) NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `kategori` enum('SQL','Design','Java','Web Development','Bisnis','Ekonomi','Psikologi','IT','Python') NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `profil_kelas` varchar(100) DEFAULT 'default.jpg',
  `description` text DEFAULT NULL,
<<<<<<< HEAD
  `status_publikasi` enum('draft','non-aktif','pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ada_sertifikat` int(1) DEFAULT NULL,
  `tanggal_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

=======
  `status_publikasi` enum('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft',
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ada_sertifikat` int(1) DEFAULT NULL,
  `tanggal_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  `status_publikasi` enum('pending','aktif','non-aktif','rejected') NOT NULL DEFAULT 'pending',
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kelas`
<<<<<<< HEAD
--

INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `jumlah_peserta`, `rating`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`, `ada_sertifikat`, `tanggal_update`) VALUES
(3, 4, 'Belajar PHP untuk Pemula', 'Web Development', 150, 4.5, 80000.00, '../assets/images/5de63102937d14a8350c852d3bf689be.jpg', NULL, 'Kelas belajar PHP dari dasar untuk pemula.', 'approved', '2024-09-30 17:00:00', 0, '2025-05-26 14:14:08'),
(4, 5, 'Kelas Bahasa Inggris (SD, SMP, SMA)', 'Bisnis', 200, 4.2, 100000.00, 'default.jpg', NULL, 'Kelas Bahasa Inggris untuk semua jenjang dengan metode interaktif.', 'approved', '2024-11-14 17:00:00', 0, '2025-05-26 12:41:44'),
(5, 5, 'Belajar PHP untuk Pemula 1', 'Web Development', 180, 4.9, 150000.00, 'default.jpg', NULL, 'Kelas lanjutan PHP untuk pemula yang sudah memiliki dasar.', 'approved', '2024-11-30 17:00:00', 0, '2025-05-26 13:14:54'),
(6, 5, 'Desain Grafis 1', 'Design', 120, 4.7, 80000.00, 'default.jpg', NULL, 'Belajar dasar desain grafis menggunakan tools populer.', 'approved', '2025-01-09 17:00:00', 0, '2025-05-26 13:14:47'),
(7, 6, 'Belajar PHP untuk Pemula', 'Web Development', 160, 4.8, 100000.00, 'default.jpg', NULL, 'Kelas belajar PHP untuk pemula yang lengkap dan praktis.', 'approved', '2025-01-19 17:00:00', 0, '2025-05-26 13:14:26'),
(8, 7, 'pemrograman web', 'Web Development', 0, 0.0, 1500000.00, '../uploads/kelas_profil/profil_68457e4b3c1db.jpg', NULL, 'pelajari web pemrograman nya sekarang', 'draft', '2025-06-07 17:00:00', NULL, '2025-06-07 17:00:00');

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(1, 1, 'Dasar Pemrograman Web (HTML, CSS, JS)', 'Web Development', 150000.00, NULL, NULL, 'Pelajari dasar-dasar pembuatan website interaktif dari nol.', 'rejected', '2025-06-01 15:29:17'),
(3, 1, 'Full-Stack Web Developer dengan PHP & Laravel', 'Web Development', 250000.00, NULL, NULL, 'Menjadi full-stack developer dengan framework PHP populer.', 'aktif', '2025-06-01 15:29:17'),
(4, 3, 'Analisis Data dengan Python untuk Pemula', 'Python', 180000.00, NULL, NULL, 'Pengenalan analisis data menggunakan bahasa Python dan library terkait.', 'non-aktif', '2025-06-01 15:29:17'),
(5, 2, 'Desain Grafis Fundamental dengan Adobe Illustrator', 'Design', 100000.00, NULL, NULL, 'Belajar dasar-dasar desain grafis dan penggunaan Adobe Illustrator.', 'non-aktif', '2025-06-01 15:29:17'),
(6, 3, 'Pengenalan Machine Learning dengan Python', 'Python', 200000.00, NULL, NULL, 'Kelas ini memperkenalkan dasar-dasar machine learning menggunakan Python.', 'pending', '2025-06-06 07:00:00'),
(7, 1, 'Fundamental UI/UX Design', 'Design', 175000.00, NULL, NULL, 'Pelajari prinsip-prinsip dasar UI/UX design dan tools prototyping.', 'rejected', '2025-06-06 07:15:00'),
(8, 3, 'Kelas Dummy 8: Intensif Pemrograman', 'Java', 175842.10, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 8, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-04-21 09:38:10'),
(9, 1, 'Kelas Dummy 9: Intensif Desain', 'Psikologi', 485576.90, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 9, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-03-06 09:38:10'),
(10, 2, 'Kelas Dummy 10: Intensif Data Science', 'IT', 90227.97, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 10, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2025-03-16 09:38:10'),
(11, 1, 'Kelas Dummy 11: Dasar Desain', 'Bisnis', 205087.46, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 11, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2025-04-15 09:38:10'),
(12, 9, 'Kelas Dummy 12: Dasar Data Science', 'Web Development', 110312.56, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 12, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-04-19 09:38:10'),
(13, 11, 'Kelas Dummy 13: Lanjutan Desain', 'Web Development', 186572.29, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 13, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2024-12-12 09:38:10'),
(14, 2, 'Kelas Dummy 14: Dasar Pemrograman', 'Java', 341189.04, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 14, mencakup topik-topik penting dan tujuan pembelajaran.', 'rejected', '2025-02-27 09:38:10'),
(15, 11, 'Kelas Dummy 15: Lanjutan Data Science', 'Psikologi', 343470.92, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 15, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-05-09 09:38:10'),
(16, 7, 'Kelas Dummy 16: Lanjutan Data Science', 'IT', 280536.41, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 16, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-02-27 09:38:10'),
(17, 3, 'Kelas Dummy 17: Lanjutan Desain', 'Web Development', 67671.79, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 17, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2025-01-10 09:38:10');

=======
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
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
>>>>>>> 0c80503c83755f2a7ea2c0af999c1a1b9821acba
-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_keranjang`
--

CREATE TABLE `tb_keranjang` (
  `id_keranjang` int(30) NOT NULL,
  `tgl_keranjang` date NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

<<<<<<< HEAD
=======
--
-- Dumping data for table `tb_keranjang`
--

INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(1, '2025-06-01', 1, 4),
(3, '2025-05-30', 4, 5),
(4, '2025-06-01', 5, 5),
(5, '2025-05-09', 15, 23),
(6, '2025-05-17', 16, 32),
(7, '2025-05-01', 15, 29),
(8, '2025-05-18', 11, 23),
(9, '2025-05-17', 14, 30),
(10, '2025-05-16', 15, 41),
(11, '2025-05-18', 12, 28),
(12, '2025-04-20', 14, 25),
(13, '2025-04-20', 13, 27),
(14, '2025-05-18', 15, 30);

--
-- Dumping data untuk tabel `tb_keranjang`
--

INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(0, '2025-06-10', 3, 18);
CREATE TABLE `tb_komentar` (
  `id_komentar` int(30) NOT NULL,
  `isi` varchar(255) NOT NULL,
  `id_user` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

<<<<<<< HEAD
--
-- Dumping data for table `tb_komentar`
--

INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(5, 'Ini adalah komentar dummy ke-5 tentang kelas ini. Sangat menarik.', 28, 11),
(6, 'Ini adalah komentar dummy ke-6 tentang kelas ini. Sangat informatif.', 23, 17),
(7, 'Ini adalah komentar dummy ke-7 tentang kelas ini. Sangat kurang jelas.', 30, 11),
(8, 'Ini adalah komentar dummy ke-8 tentang kelas ini. Sangat kurang jelas.', 36, 12),
(9, 'Ini adalah komentar dummy ke-9 tentang kelas ini. Sangat membantu.', 4, 15),
(10, 'Ini adalah komentar dummy ke-10 tentang kelas ini. Sangat bagus.', 29, 11),
(11, 'Ini adalah komentar dummy ke-11 tentang kelas ini. Sangat informatif.', 40, 15),
(12, 'Ini adalah komentar dummy ke-12 tentang kelas ini. Sangat bagus.', 35, 14),
(13, 'Ini adalah komentar dummy ke-13 tentang kelas ini. Sangat membantu.', 31, 15),
(14, 'Ini adalah komentar dummy ke-14 tentang kelas ini. Sangat menarik.', 37, 10),
(15, 'Ini adalah komentar dummy ke-15 tentang kelas ini. Sangat bagus.', 31, 17),
(16, 'Ini adalah komentar dummy ke-16 tentang kelas ini. Sangat membantu.', 27, 12),
(17, 'Ini adalah komentar dummy ke-17 tentang kelas ini. Sangat bagus.', 31, 13),
(18, 'Ini adalah komentar dummy ke-18 tentang kelas ini. Sangat bagus.', 37, 14),
(19, 'Ini adalah komentar dummy ke-19 tentang kelas ini. Sangat informatif.', 28, 16),
(20, 'Ini adalah komentar dummy ke-20 tentang kelas ini. Sangat kurang jelas.', 30, 10),
(21, 'Ini adalah komentar dummy ke-21 tentang kelas ini. Sangat menarik.', 26, 17),
(22, 'Ini adalah komentar dummy ke-22 tentang kelas ini. Sangat informatif.', 33, 9),
(23, 'Ini adalah komentar dummy ke-23 tentang kelas ini. Sangat membantu.', 34, 15),
(24, 'Ini adalah komentar dummy ke-24 tentang kelas ini. Sangat bagus.', 33, 10),
(25, 'Ini adalah komentar dummy ke-25 tentang kelas ini. Sangat bagus.', 42, 10),
(26, 'Ini adalah komentar dummy ke-26 tentang kelas ini. Sangat informatif.', 27, 14),
(27, 'Ini adalah komentar dummy ke-27 tentang kelas ini. Sangat bagus.', 34, 11),
(28, 'Ini adalah komentar dummy ke-28 tentang kelas ini. Sangat bagus.', 39, 13),
(29, 'Ini adalah komentar dummy ke-29 tentang kelas ini. Sangat membantu.', 29, 14),
(30, 'Ini adalah komentar dummy ke-30 tentang kelas ini. Sangat menarik.', 37, 12),
(31, 'Ini adalah komentar dummy ke-31 tentang kelas ini. Sangat bagus.', 41, 10),
(32, 'Ini adalah komentar dummy ke-32 tentang kelas ini. Sangat menarik.', 40, 11),
(33, 'Ini adalah komentar dummy ke-33 tentang kelas ini. Sangat membantu.', 42, 12);

=======
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
>>>>>>> 0c80503c83755f2a7ea2c0af999c1a1b9821acba
-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_laporan`
--

CREATE TABLE `tb_laporan` (
  `id_report` int(30) NOT NULL,
  `kategori_report` enum('Penggunaan kata kasar','Materi tidak relevan','Pornografi') NOT NULL,
  `keterangan_report` varchar(100) NOT NULL,
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `status_laporan` enum('Belum Diproses','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Belum Diproses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_materi`
--

CREATE TABLE `tb_materi` (
  `id_materi` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `urutan` int(11) DEFAULT 1,
  `judul_materi` varchar(255) NOT NULL,
  `deskripsi_m` text NOT NULL,
  `status` enum('pending','approved','non-aktif','rejected') NOT NULL DEFAULT 'pending',
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

<<<<<<< HEAD
=======
--
-- Dumping data untuk tabel `tb_materi`
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
(10, 7, 1, 'Pengenalan PHP'),
(11, 8, 6, 'belajar php dasar');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(1, 1, 1, 'Pengenalan Web & HTML Dasar', 'aktif', '2025-06-01 15:29:17'),
(2, 1, 2, 'Styling dengan CSS', 'non-aktif', '2025-06-01 15:29:17'),
(3, 1, 3, 'Interaktivitas dengan JavaScript', 'non-aktif', '2025-06-01 15:29:17'),
(6, 4, 1, 'Setup Lingkungan Python untuk Data', 'non-aktif', '2025-06-01 15:29:17'),
(7, 5, 1, 'Pengantar Tools Desain Grafis', 'non-aktif', '2025-06-01 15:29:17'),
(8, 14, 2, 'Materi Dummy 8: Bab 2 Studi Kasus', 'aktif', '2025-03-17 09:38:10'),
(9, 9, 10, 'Materi Dummy 9: Bab 10 Konsep Dasar', 'non-aktif', '2025-04-25 09:38:10'),
(10, 4, 4, 'Materi Dummy 10: Bab 4 Pendahuluan', 'aktif', '2025-03-27 09:38:10'),
(11, 10, 9, 'Materi Dummy 11: Bab 9 Konsep Dasar', 'non-aktif', '2025-05-29 09:38:10'),
(12, 13, 3, 'Materi Dummy 12: Bab 3 Studi Kasus', 'aktif', '2025-05-08 09:38:10'),
(13, 2, 6, 'Materi Dummy 13: Bab 6 Konsep Dasar', 'non-aktif', '2025-04-28 09:38:10'),
(14, 7, 9, 'Materi Dummy 14: Bab 9 Studi Kasus', 'pending', '2025-04-29 09:38:10'),
(15, 13, 7, 'Materi Dummy 15: Bab 7 Pendahuluan', 'pending', '2025-04-25 09:38:10'),
(16, 7, 2, 'Materi Dummy 16: Bab 2 Studi Kasus', 'pending', '2025-05-02 09:38:10'),
(17, 12, 6, 'Materi Dummy 17: Bab 6 Konsep Dasar', 'pending', '2025-03-21 09:38:10'),
(18, 7, 2, 'Materi Dummy 18: Bab 2 Pendahuluan', 'non-aktif', '2025-03-11 09:38:10'),
(19, 3, 9, 'Materi Dummy 19: Bab 9 Studi Kasus', 'pending', '2025-04-28 09:38:10'),
(20, 16, 6, 'Materi Dummy 20: Bab 6 Pendahuluan', 'pending', '2025-05-10 09:38:10'),
(21, 9, 4, 'Materi Dummy 21: Bab 4 Studi Kasus', 'aktif', '2025-04-29 09:38:10'),
(22, 12, 7, 'Materi Dummy 22: Bab 7 Pendahuluan', 'aktif', '2025-06-01 09:38:10'),
(23, 1, 7, 'Materi Dummy 23: Bab 7 Studi Kasus', 'aktif', '2025-03-31 09:38:10'),
(24, 16, 9, 'Materi Dummy 24: Bab 9 Studi Kasus', 'pending', '2025-04-21 09:38:10'),
(25, 9, 3, 'Materi Dummy 25: Bab 3 Pendahuluan', 'non-aktif', '2025-06-01 09:38:10'),
(26, 2, 3, 'Materi Dummy 26: Bab 3 Pendahuluan', 'non-aktif', '2025-03-31 09:38:10'),
(27, 13, 2, 'Materi Dummy 27: Bab 2 Pendahuluan', 'aktif', '2025-05-22 09:38:10');

>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_mentor`
--

CREATE TABLE `tb_mentor` (
  `id_mentor` int(30) NOT NULL,
  `status` enum('Aktif','Non-Aktif') NOT NULL,
  `id_user` int(30) NOT NULL,
  `keahlian` varchar(255) DEFAULT NULL,
  `pengalaman` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

<<<<<<< HEAD
-- --------------------------------------------------------

--
-- Table structure for table `tb_pembayaran`
=======
--
-- Dumping data untuk tabel `tb_mentor`
--

INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(1, 'Aktif', 1),
(2, 'Aktif', 14),
(3, 'Aktif', 15),
(4, 'Aktif', 11),
(5, 'Aktif', 17),
(6, 'Aktif', 16),
(7, 'Aktif', 18);
(1, 'Aktif', 2),
(2, 'Aktif', 3),
(3, 'Aktif', 6),
(7, 'Aktif', 22),
(8, 'Non-Aktif', 39),
(9, 'Non-Aktif', 25),
(10, 'Non-Aktif', 4),
(11, 'Aktif', 26),
(12, 'Non-Aktif', 38),
(13, 'Aktif', 23),
(14, 'Aktif', 24),
(15, 'Aktif', 25),
(16, 'Aktif', 26),
(17, 'Aktif', 27),
(18, 'Aktif', 23),
(19, 'Aktif', 24),
(20, 'Aktif', 25),
(21, 'Aktif', 26),
(22, 'Aktif', 27),
(23, 'Aktif', 23),
(24, 'Aktif', 24),
(25, 'Aktif', 25),
(26, 'Aktif', 26),
(27, 'Aktif', 27),
(28, 'Aktif', 23),
(29, 'Aktif', 24),
(30, 'Aktif', 25),
(31, 'Aktif', 26),
(32, 'Aktif', 27);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_notifikasi`
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
--

CREATE TABLE `tb_pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL,
  `metode_bayar` varchar(50) NOT NULL,
  `nomor_va` varchar(255) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `tanggal_pembayaran` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pembayaran`
--

CREATE TABLE `tb_pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL,
  `metode_bayar` varchar(50) NOT NULL,
  `nomor_va` varchar(255) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `tanggal_pembayaran` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_pembayaran`
--

INSERT INTO `tb_pembayaran` (`id_pembayaran`, `order_id`, `id_user`, `total_bayar`, `metode_bayar`, `nomor_va`, `bukti_transfer`, `tanggal_pembayaran`, `status`) VALUES
(7, 'ORD-80B7A5D3', 18, 100000.00, 'BCA Virtual Account', '123456789012', 'uploads/proofs/683eaecd3a245_download.png', '2025-06-03 15:14:05', 'Pending'),
(8, 'ORD-67D3B584', 18, 80000.00, 'BCA Virtual Account', '123456789012', 'uploads/proofs/683eaf67e6fa7_download.png', '2025-06-03 15:16:39', 'Pending'),
(9, 'ORD-BA79715B', 18, 100000.00, 'BCA Virtual Account', '123456789012', 'uploads/proofs/683eb4e71b103_download.png', '2025-06-03 15:40:07', 'Pending'),
(10, 'ORD-CDFC9AE5', 18, 80000.00, 'BCA Virtual Account', '123456789012', 'uploads/proofs/683eb5df03349_download.png', '2025-06-03 15:44:15', 'Pending'),
(11, 'ORD-C94939DC', 18, 730000.00, 'BCA Virtual Account', '123456789012', 'uploads/proofs/684300a63afd5_Cuplikan layar 2025-06-06 213540.png', '2025-06-06 21:52:22', 'Pending'),
(12, 'ORD-3167D32D', 18, 80000.00, 'BCA Virtual Account', '123456789012', 'uploads/proofs/68454cee52c5b_Cuplikan layar 2025-06-08 150305.png', '2025-06-08 15:42:22', 'Pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pengembangan_profesional`
--

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

-- --------------------------------------------------------


--
-- Struktur dari tabel `tb_progress_kelas`
--

CREATE TABLE `tb_progress_kelas` (
  `id_progress_kelas` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `id_materi` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_review`
--

CREATE TABLE `tb_review` (
  `id_review` int(30) NOT NULL,
  `bintang_review` enum('1','2','3','4','5') NOT NULL,
  `isi_review` varchar(100) NOT NULL,
  `tgl_review` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_user` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_sub_materi`
--

CREATE TABLE `tb_sub_materi` (
  `id_sub_materi` int(30) NOT NULL,
  `id_materi` int(30) NOT NULL,
  `id_dokumen` int(30) NOT NULL,
  `id_video` int(11) DEFAULT NULL,
  `urutan` int(11) DEFAULT 1,
  `judul_sub_materi` varchar(255) NOT NULL,
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

<<<<<<< HEAD
=======
--
-- Dumping data untuk tabel `tb_sub_materi`
--

INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(1, 1, 0, 0, 1, 'Struktur Dasar HTML', 'aktif', '2025-06-01 15:29:17'),
(2, 1, 1, 1, 2, 'Elemen dan Tag HTML Penting', 'aktif', '2025-06-01 15:29:17'),
(3, 2, 2, 2, 1, 'Selector dan Properti CSS', 'aktif', '2025-06-01 15:29:17'),
(6, 6, 0, 5, 1, 'Instalasi Anaconda dan Jupyter Notebook', 'aktif', '2025-06-01 15:29:17'),
(7, 7, 5, 0, 1, 'Mengenal Adobe Illustrator dan Figma', 'aktif', '2025-06-01 15:29:17'),
(8, 9, 6, 10, 3, 'Sub Materi Dummy 8: Praktik Topik A', 'aktif', '2025-05-12 09:38:10'),
(9, 14, 14, 12, 5, 'Sub Materi Dummy 9: Praktik Topik B', 'pending', '2025-04-12 09:38:10'),
(10, 24, 13, 13, 3, 'Sub Materi Dummy 10: Praktik Topik A', 'non-aktif', '2025-04-20 09:38:10'),
(11, 15, 2, 6, 2, 'Sub Materi Dummy 11: Praktik Topik B', 'pending', '2025-06-07 09:38:10'),
(12, 27, 12, 4, 4, 'Sub Materi Dummy 12: Pengantar Topik C', 'non-aktif', '2025-04-21 09:38:10'),
(13, 13, 1, 0, 4, 'Sub Materi Dummy 13: Praktik Topik C', 'non-aktif', '2025-04-27 09:38:10'),
(14, 7, 14, 5, 4, 'Sub Materi Dummy 14: Pengantar Topik A', 'aktif', '2025-05-01 09:38:10'),
(15, 11, 5, 4, 2, 'Sub Materi Dummy 15: Latihan Topik C', 'aktif', '2025-05-18 09:38:10'),
(16, 24, 7, 14, 2, 'Sub Materi Dummy 16: Pengantar Topik B', 'pending', '2025-05-08 09:38:10'),
(17, 11, 0, 11, 2, 'Sub Materi Dummy 17: Praktik Topik C', 'non-aktif', '2025-04-20 09:38:10'),
(18, 11, 4, 15, 1, 'Sub Materi Dummy 18: Pengantar Topik B', 'aktif', '2025-04-09 09:38:10'),
(19, 14, 15, 10, 3, 'Sub Materi Dummy 19: Latihan Topik C', 'non-aktif', '2025-04-23 09:38:10'),
(20, 19, 14, 2, 4, 'Sub Materi Dummy 20: Pengantar Topik A', 'aktif', '2025-05-24 09:38:10'),
(21, 23, 11, 14, 3, 'Sub Materi Dummy 21: Pengantar Topik B', 'aktif', '2025-05-09 09:38:10'),
(22, 19, 9, 8, 2, 'Sub Materi Dummy 22: Latihan Topik B', 'pending', '2025-05-18 09:38:10'),
(23, 16, 12, 12, 2, 'Sub Materi Dummy 23: Latihan Topik C', 'pending', '2025-05-28 09:38:10'),
(24, 16, 11, 10, 1, 'Sub Materi Dummy 24: Latihan Topik B', 'non-aktif', '2025-05-25 09:38:10'),
(25, 20, 10, 15, 3, 'Sub Materi Dummy 25: Pengantar Topik A', 'non-aktif', '2025-05-26 09:38:10'),
(26, 21, 6, 8, 3, 'Sub Materi Dummy 26: Latihan Topik A', 'non-aktif', '2025-05-12 09:38:10'),
(27, 24, 7, 5, 2, 'Sub Materi Dummy 27: Pengantar Topik A', 'aktif', '2025-05-23 09:38:10'),
(28, 12, 10, 9, 3, 'Sub Materi Dummy 28: Latihan Topik A', 'aktif', '2025-05-10 09:38:10'),
(29, 13, 11, 11, 5, 'Sub Materi Dummy 29: Praktik Topik B', 'non-aktif', '2025-05-16 09:38:10'),
(30, 26, 12, 1, 1, 'Sub Materi Dummy 30: Praktik Topik B', 'pending', '2025-05-06 09:38:10'),
(31, 23, 13, 11, 4, 'Sub Materi Dummy 31: Latihan Topik C', 'aktif', '2025-05-26 09:38:10'),
(32, 25, 14, 15, 3, 'Sub Materi Dummy 32: Praktik Topik A', 'aktif', '2025-05-11 09:38:10'),
(33, 17, 12, 12, 4, 'Sub Materi Dummy 33: Praktik Topik B', 'non-aktif', '2025-05-26 09:38:10'),
(34, 16, 1, 13, 4, 'Sub Materi Dummy 34: Pengantar Topik B', 'aktif', '2025-05-27 09:38:10'),
(35, 19, 0, 0, 5, 'Sub Materi Dummy 35: Latihan Topik B', 'aktif', '2025-05-20 09:38:10'),
(36, 13, 1, 10, 1, 'Sub Materi Dummy 36: Praktik Topik C', 'pending', '2025-05-02 09:38:10'),
(37, 24, 15, 6, 2, 'Sub Materi Dummy 37: Latihan Topik B', 'pending', '2025-05-07 09:38:10'),
(38, 23, 14, 15, 4, 'Sub Materi Dummy 38: Pengantar Topik C', 'non-aktif', '2025-05-24 09:38:10'),
(39, 13, 8, 1, 1, 'Sub Materi Dummy 39: Latihan Topik B', 'pending', '2025-05-07 09:38:10'),
(40, 25, 12, 13, 5, 'Sub Materi Dummy 40: Latihan Topik C', 'non-aktif', '2025-05-20 09:38:10'),
(41, 14, 0, 0, 5, 'Sub Materi Dummy 41: Praktik Topik A', 'aktif', '2025-05-07 09:38:10'),
(42, 10, 11, 4, 3, 'Sub Materi Dummy 42: Praktik Topik B', 'aktif', '2025-05-18 09:38:10'),
(43, 18, 12, 0, 3, 'Sub Materi Dummy 43: Pengantar Topik C', 'aktif', '2025-05-26 09:38:10'),
(44, 26, 12, 12, 4, 'Sub Materi Dummy 44: Pengantar Topik A', 'aktif', '2025-05-22 09:38:10'),
(45, 17, 11, 3, 2, 'Sub Materi Dummy 45: Latihan Topik C', 'non-aktif', '2025-05-21 09:38:10'),
(46, 27, 7, 7, 2, 'Sub Materi Dummy 46: Pengantar Topik C', 'aktif', '2025-05-20 09:38:10'),
(47, 10, 15, 14, 2, 'Sub Materi Dummy 47: Praktik Topik B', 'non-aktif', '2025-05-17 09:38:10'),
(48, 26, 8, 0, 5, 'Sub Materi Dummy 48: Latihan Topik A', 'pending', '2025-05-09 09:38:10'),
(49, 10, 9, 14, 5, 'Sub Materi Dummy 49: Praktik Topik A', 'aktif', '2025-05-22 09:38:10'),
(50, 20, 10, 2, 4, 'Sub Materi Dummy 50: Latihan Topik B', 'non-aktif', '2025-05-26 09:38:10'),
(51, 14, 15, 7, 4, 'Sub Materi Dummy 51: Pengantar Topik B', 'aktif', '2025-05-18 09:38:10'),
(52, 22, 13, 10, 5, 'Sub Materi Dummy 52: Pengantar Topik C', 'pending', '2025-05-06 09:38:10'),
(53, 21, 10, 15, 2, 'Sub Materi Dummy 53: Praktik Topik B', 'pending', '2025-05-29 09:38:10'),
(54, 10, 0, 15, 5, 'Sub Materi Dummy 54: Latihan Topik A', 'non-aktif', '2025-05-28 09:38:10'),
(55, 17, 7, 10, 2, 'Sub Materi Dummy 55: Pengantar Topik B', 'aktif', '2025-05-20 09:38:10'),
(56, 14, 7, 10, 3, 'Sub Materi Dummy 56: Latihan Topik A', 'non-aktif', '2025-05-23 09:38:10'),
(57, 13, 0, 4, 3, 'Sub Materi Dummy 57: Praktik Topik A', 'non-aktif', '2025-05-02 09:38:10'),
(58, 25, 3, 11, 4, 'Sub Materi Dummy 58: Latihan Topik C', 'aktif', '2025-05-25 09:38:10'),
(59, 21, 10, 13, 1, 'Sub Materi Dummy 59: Latihan Topik B', 'pending', '2025-05-06 09:38:10'),
(60, 22, 0, 11, 3, 'Sub Materi Dummy 60: Praktik Topik C', 'aktif', '2025-05-12 09:38:10'),
(61, 23, 11, 3, 3, 'Sub Materi Dummy 61: Pengantar Topik A', 'non-aktif', '2025-05-18 09:38:10'),
(62, 23, 0, 7, 4, 'Sub Materi Dummy 62: Latihan Topik B', 'aktif', '2025-05-05 09:38:10'),
(63, 16, 5, 10, 3, 'Sub Materi Dummy 63: Latihan Topik A', 'pending', '2025-05-24 09:38:10'),
(64, 27, 10, 1, 4, 'Sub Materi Dummy 64: Latihan Topik C', 'non-aktif', '2025-05-27 09:38:10'),
(65, 15, 1, 6, 2, 'Sub Materi Dummy 65: Praktik Topik A', 'non-aktif', '2025-05-16 09:38:10'),
(66, 17, 10, 11, 1, 'Sub Materi Dummy 66: Praktik Topik B', 'non-aktif', '2025-05-07 09:38:10'),
(67, 18, 9, 2, 4, 'Sub Materi Dummy 67: Pengantar Topik C', 'non-aktif', '2025-05-28 09:38:10'),
(68, 11, 15, 15, 3, 'Sub Materi Dummy 68: Latihan Topik C', 'non-aktif', '2025-05-28 09:38:10'),
(69, 9, 14, 11, 5, 'Sub Materi Dummy 69: Praktik Topik A', 'non-aktif', '2025-05-24 09:38:10'),
(70, 21, 6, 15, 1, 'Sub Materi Dummy 70: Praktik Topik B', 'aktif', '2025-05-24 09:38:10'),
(71, 18, 0, 12, 1, 'Sub Materi Dummy 71: Latihan Topik B', 'pending', '2025-05-09 09:38:10'),
(72, 14, 13, 10, 2, 'Sub Materi Dummy 72: Latihan Topik B', 'pending', '2025-05-07 09:38:10'),
(73, 19, 15, 2, 4, 'Sub Materi Dummy 73: Pengantar Topik C', 'non-aktif', '2025-05-18 09:38:10'),
(74, 15, 1, 10, 4, 'Sub Materi Dummy 74: Pengantar Topik A', 'pending', '2025-05-08 09:38:10'),
(75, 10, 4, 9, 3, 'Sub Materi Dummy 75: Latihan Topik B', 'non-aktif', '2025-05-14 09:38:10'),
(76, 26, 12, 5, 4, 'Sub Materi Dummy 76: Latihan Topik A', 'aktif', '2025-05-24 09:38:10'),
(77, 23, 2, 3, 1, 'Sub Materi Dummy 77: Praktik Topik A', 'aktif', '2025-05-24 09:38:10'),
(78, 20, 11, 4, 3, 'Sub Materi Dummy 78: Latihan Topik B', 'aktif', '2025-05-26 09:38:10'),
(79, 17, 10, 7, 4, 'Sub Materi Dummy 79: Pengantar Topik B', 'non-aktif', '2025-05-21 09:38:10'),
(80, 15, 7, 3, 1, 'Sub Materi Dummy 80: Praktik Topik A', 'pending', '2025-05-09 09:38:10'),
(81, 10, 13, 15, 3, 'Sub Materi Dummy 81: Pengantar Topik C', 'aktif', '2025-05-18 09:38:10'),
(82, 19, 14, 15, 5, 'Sub Materi Dummy 82: Latihan Topik B', 'non-aktif', '2025-05-03 09:38:10'),
(83, 18, 5, 0, 5, 'Sub Materi Dummy 83: Pengantar Topik B', 'aktif', '2025-05-22 09:38:10'),
(84, 15, 14, 1, 3, 'Sub Materi Dummy 84: Praktik Topik A', 'non-aktif', '2025-05-15 09:38:10'),
(85, 27, 8, 13, 1, 'Sub Materi Dummy 85: Pengantar Topik C', 'pending', '2025-05-22 09:38:10'),
(86, 20, 12, 14, 4, 'Sub Materi Dummy 86: Latihan Topik C', 'aktif', '2025-05-25 09:38:10'),
(87, 19, 11, 2, 4, 'Sub Materi Dummy 87: Latihan Topik B', 'non-aktif', '2025-05-25 09:38:10'),
(88, 22, 0, 9, 1, 'Sub Materi Dummy 88: Praktik Topik A', 'aktif', '2025-05-15 09:38:10');

>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_testimoni`
--

CREATE TABLE `tb_testimoni` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `posisi` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `quote` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_testimoni`
--

INSERT INTO `tb_testimoni` (`id`, `nama`, `posisi`, `avatar`, `quote`, `created_at`, `updated_at`) VALUES
(1, 'Ahmad Rizki', 'Full Stack Developer with Tokopedia', '../assets/images/c3913dc52d35241596ade71e69d29ab0.jpg', 'Berkat kursus di KelasKita, saya berhasil beralih karier dari seorang akuntan menjadi developer dalam waktu kurang dari 6 bulan. Materi yang diajarkan sangat relevan dengan kebutuhan industri.', '2025-05-13 15:26:17', '2025-05-21 14:00:42'),
(2, 'Chimika', 'UI/UX Designer with Gojek', '../assets/images/8c6ddb5fe6600fcc4b183cb2ee228eb7.jpg', 'Bootcamp UI/UX Design di KelasKita memberikan saya pengetahuan dan keterampilan yang dibutuhkan untuk masuk ke industri teknologi. Instrukturnya sangat supportif dan proyek-proyeknya menantang.', '2025-05-13 15:26:17', '2025-05-21 14:01:10'),
(3, 'Budi Pratama', 'Data Scientist with Bukalapak', '../assets/images/090ff51bf1b9e39ce8930063d7b252cf.jpg', 'Program Data Science sangat komprehensif dan up-to-date dengan teknologi terkini. Saya merekomendasikan KelasKita untuk siapa saja yang ingin menguasai Data Science dan Machine Learning.', '2025-05-13 15:26:17', '2025-05-21 14:01:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transaksi`

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

--
-- Dumping data untuk tabel `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(1, 7, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(2, 3, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(3, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(4, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(5, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(6, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(7, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(8, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(9, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(10, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(11, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(12, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(13, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(14, 5, 8, 0, 'QR_PAYMENT', '2025-05-31', 'Completed'),
(15, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(16, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(17, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(18, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(19, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(20, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(21, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(22, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(23, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(24, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(25, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(26, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(27, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(28, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(29, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(30, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(31, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(32, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(33, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(34, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(35, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(36, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(37, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(38, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(39, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(40, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(41, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(42, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(43, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(44, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(45, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(46, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(47, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(48, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(49, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(50, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(51, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(52, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(53, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(54, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(55, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(56, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(57, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(58, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(59, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(60, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(61, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(62, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(63, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(64, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(65, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(66, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(67, 5, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(68, 7, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(69, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(70, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(71, 5, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(72, 5, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(73, 5, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(74, 5, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(75, 5, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(76, 3, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(77, 5, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(78, 6, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(79, 7, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(80, 7, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(81, 7, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(82, 7, 8, 0, 'QR_PAYMENT', '2025-06-01', 'Completed'),
(83, 5, 8, 0, 'QR_PAYMENT', '2025-06-02', 'Completed'),
(84, 3, 8, 0, 'QR_PAYMENT', '2025-06-02', 'Completed');

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(1, 1, 4, 1, 'bukti_TRX001.jpg', '2025-06-01 15:29:17', 'acc'),
(3, 4, 5, 3, 'bukti_TRX003.jpg', '2025-06-01 15:29:17', 'acc'),
(4, 9, 39, 11, 'bukti_TRX_DUMMY_4.jpg', '2025-04-06 09:38:10', 'acc'),
(5, 12, 29, 6, 'bukti_TRX_DUMMY_5.jpg', '2025-05-01 09:38:10', 'acc'),
(6, 12, 33, 10, 'bukti_TRX_DUMMY_6.jpg', '2025-05-11 09:38:10', 'acc'),
(7, 10, 31, 10, 'bukti_TRX_DUMMY_7.jpg', '2025-05-17 09:38:10', 'acc'),
(8, 14, 25, 10, 'bukti_TRX_DUMMY_8.jpg', '2025-05-27 09:38:10', 'acc'),
(9, 13, 27, 9, 'bukti_TRX_DUMMY_9.jpg', '2025-05-14 09:38:10', 'acc'),
(10, 16, 23, 11, 'bukti_TRX_DUMMY_10.jpg', '2025-04-18 09:38:10', 'ditolak'),
(11, 13, 30, 8, 'bukti_TRX_DUMMY_11.jpg', '2025-04-20 09:38:10', 'acc'),
(12, 10, 26, 12, 'bukti_TRX_DUMMY_12.jpg', '2025-04-22 09:38:10', 'acc'),
(13, 12, 31, 6, 'bukti_TRX_DUMMY_13.jpg', '2025-04-12 09:38:10', 'ditolak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(30) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL,
  `no_telepon` int(13) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('murid','mentor','admin') NOT NULL DEFAULT 'murid',
  `status` enum('aktif','non-aktif') NOT NULL DEFAULT 'aktif',
  `deskripsi` text DEFAULT NULL,
  `fotoProfil` varchar(50) NOT NULL,
  `bahasa` enum('Bahasa Indonesia','Inggris','Jepang') NOT NULL,
  `zona_waktu` enum('Jakarta','London','Tokyo') NOT NULL,
  `notifikasi_postingan_baru` tinyint(1) NOT NULL,
  `email` varchar(50) NOT NULL,
  `instagram` varchar(30) NOT NULL,
  `twitter` varchar(30) NOT NULL,
  `linkdin` varchar(30) NOT NULL,
  `github` varchar(30) NOT NULL,
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

<<<<<<< HEAD
=======
--
-- Dumping data untuk tabel `tb_user`
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
(17, '', '', 'blabla', '$2y$10$4imiVUlOZFABSuRzavGqxeghZqhCIFY6JCyfVXRsj0fKWbxqBYQWi', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', ''),
(18, '', '', 'fahlivy Adithia sugara', '$2y$10$oIOC3hlQp1G1PGYXVj1Z2.N6pFFFEWn90shQ9ceQ/MtUFtfnbI5H.', 'murid', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', '');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(1, 'Admin', 'Kelaskita', 'admin01', '$2y$10$wGIB.sDNJO.rlHnln3.mI.UKwcOQs4bitXDOhnl6vGy3FSJPAg0hy', 'admin', 'non-aktif', 'Administrator Utama Website Kelaskita', 'admin_profile.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'admin@kelaskita.com', '', '', '', '', '2025-06-01 15:29:17'),
(2, 'Budi', 'Santoso', 'budi_mentor', '$2y$10$djBcFVMmkrJNDQOYE9D2Je/gDtva5uRmn4JonAuvuWa38mRzngi2m', 'mentor', 'non-aktif', 'Mentor Web Development dengan pengalaman 5 tahun.', 'budi_santoso.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'budi.mentor@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(3, 'Citra', 'Wirawan', 'citra_mentor', '$2y$10$uvkvgQ7H.cz.C76UrwKAu.S6hUXFTVwfivqdltQ1BdGPqTn1gkT9m', 'mentor', 'non-aktif', 'Ahli Database dan SQL.', 'citra_wirawan.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'citra.mentor@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(4, 'Dewi', 'Lestari', 'dewi_murid', '$2y$10$l2YdSJBJ8AR3SunZlvm7E.qimAhHVQ5mIcFdFR4IXgxMTcppVEOCC', 'murid', 'aktif', 'Pelajar antusias di bidang teknologi.', 'dewi_lestari.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'dewi.murid@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(5, 'Eko', 'Prasetyo', 'eko_murid', '$2y$10$xbonLUo2ymeprYB6OAgmQut/fdN18FP5FsFF3rV/FcbauZNjMNnGK', 'murid', 'aktif', 'Tertarik dengan desain grafis dan UI/UX.', 'eko_prasetyo.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'eko.murid@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(6, 'Fajar', 'Nugraha', 'fajar_mentor', '$2y$10$Uv1iau892wYDCp4i1PuJKeU0YX4nYe.uBbUSK3reYACNYY9EdTWMC', 'mentor', 'aktif', 'Spesialis Python dan Data Science.', 'fajar_nugraha.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'fajar.mentor@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(7, 'Gina', 'Hardiman', 'gina_murid', '$2y$10$/TPnN4zkLIvY0HMByXcfp.9oh8QdxCkGiSW1TMvbSDsoKaO6PsLIa', 'murid', 'non-aktif', 'Sedang non-aktif.', 'gina_hardiman.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'gina.murid@example.com', '', '', '', '', '2025-06-01 15:29:17'),
(21, '', '', 'Shafnat', '$2y$10$biEpw3vFhED9kg1NUcKZz.8lr7HGYAH7JWOSWIrDdQJkFfOQK9I.2', 'admin', 'non-aktif', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', '', '2025-06-04 15:58:31'),
(22, '', '', 'Shafnatt', '$2y$10$Y0/z0/ot8Vdg6jbFJmOrl.MSKqo7gYbv6DbzZ/8Lylpe1pIxZRvdq', 'murid', 'non-aktif', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', '', '2025-06-05 14:30:29'),
(23, 'DummyF23', 'DummyL23', 'dummy_user_23', '$2y$10$J2dkelDrLXK4l0Pt8IwE0Y', 'admin', 'aktif', 'Deskripsi dummy user 23.', 'profile_23.jpg', 'Inggris', 'London', 0, 1, 1, 'dummy_email_23@example.com', 'insta_user23', 'twitter_user23', 'linkedin_user23', 'github_user23', '2025-05-22 09:38:10'),
(24, 'DummyF24', 'DummyL24', 'dummy_user_24', '$2y$10$hfGT8d5SQF05loCWi1PFkE', 'murid', 'aktif', 'Deskripsi dummy user 24.', 'profile_24.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'dummy_email_24@example.com', 'insta_user24', 'twitter_user24', 'linkedin_user24', 'github_user24', '2025-03-17 09:38:10'),
(25, 'DummyF25', 'DummyL25', 'dummy_user_25', '$2y$10$XbV2l11UWBgKxpFHangk8W', 'murid', 'aktif', 'Deskripsi dummy user 25.', 'profile_25.jpg', 'Jepang', 'Jakarta', 1, 1, 1, 'dummy_email_25@example.com', 'insta_user25', 'twitter_user25', 'linkedin_user25', 'github_user25', '2024-12-31 09:38:10'),
(26, 'DummyF26', 'DummyL26', 'dummy_user_26', '$2y$10$8zLvSWTVGtE3OI9dkiUPIn', 'mentor', 'aktif', 'Deskripsi dummy user 26.', 'profile_26.jpg', 'Jepang', 'Jakarta', 0, 0, 0, 'dummy_email_26@example.com', 'insta_user26', 'twitter_user26', 'linkedin_user26', 'github_user26', '2025-02-17 09:38:10'),
(27, 'DummyF27', 'DummyL27', 'dummy_user_27', '$2y$10$jCxJEluRZS9u8ptY1M5Y5L', 'admin', 'non-aktif', 'Deskripsi dummy user 27.', 'profile_27.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 0, 'dummy_email_27@example.com', 'insta_user27', 'twitter_user27', 'linkedin_user27', 'github_user27', '2025-04-03 09:38:10'),
(28, 'DummyF28', 'DummyL28', 'dummy_user_28', '$2y$10$EOFRfHYQ2fQo8bH9XGNwkb', 'murid', 'non-aktif', 'Deskripsi dummy user 28.', 'profile_28.jpg', 'Inggris', 'London', 0, 1, 0, 'dummy_email_28@example.com', 'insta_user28', 'twitter_user28', 'linkedin_user28', 'github_user28', '2025-02-08 09:38:10'),
(29, 'DummyF29', 'DummyL29', 'dummy_user_29', '$2y$10$sC77erBAVnt0DDpM2hrTv0', 'admin', 'non-aktif', 'Deskripsi dummy user 29.', 'profile_29.jpg', 'Jepang', 'Tokyo', 1, 1, 0, 'dummy_email_29@example.com', 'insta_user29', 'twitter_user29', 'linkedin_user29', 'github_user29', '2024-10-06 09:38:10'),
(30, 'DummyF30', 'DummyL30', 'dummy_user_30', '$2y$10$KjjlDwN2pdpAnsICLOxhVv', 'murid', 'non-aktif', 'Deskripsi dummy user 30.', 'profile_30.jpg', 'Inggris', 'Tokyo', 1, 0, 1, 'dummy_email_30@example.com', 'insta_user30', 'twitter_user30', 'linkedin_user30', 'github_user30', '2024-07-12 09:38:10'),
(31, 'DummyF31', 'DummyL31', 'dummy_user_31', '$2y$10$eXOrthok8GrTyyqhYEjlnU', 'admin', 'aktif', 'Deskripsi dummy user 31.', 'profile_31.jpg', 'Inggris', 'Jakarta', 1, 0, 1, 'dummy_email_31@example.com', 'insta_user31', 'twitter_user31', 'linkedin_user31', 'github_user31', '2024-09-16 09:38:10'),
(32, 'DummyF32', 'DummyL32', 'dummy_user_32', '$2y$10$477RUjaXGkghmpVGC3iyvY', 'admin', 'aktif', 'Deskripsi dummy user 32.', 'profile_32.jpg', 'Bahasa Indonesia', 'Tokyo', 0, 1, 0, 'dummy_email_32@example.com', 'insta_user32', 'twitter_user32', 'linkedin_user32', 'github_user32', '2025-05-05 09:38:10'),
(33, 'DummyF33', 'DummyL33', 'dummy_user_33', '$2y$10$aRZRElV6XDjov1ifMfn3py', 'murid', 'non-aktif', 'Deskripsi dummy user 33.', 'profile_33.jpg', 'Inggris', 'Tokyo', 0, 0, 0, 'dummy_email_33@example.com', 'insta_user33', 'twitter_user33', 'linkedin_user33', 'github_user33', '2025-01-24 09:38:10'),
(34, 'DummyF34', 'DummyL34', 'dummy_user_34', '$2y$10$ODsOguIL0boyPosnuEagAh', 'admin', 'non-aktif', 'Deskripsi dummy user 34.', 'profile_34.jpg', 'Bahasa Indonesia', 'London', 1, 0, 1, 'dummy_email_34@example.com', 'insta_user34', 'twitter_user34', 'linkedin_user34', 'github_user34', '2025-05-25 09:38:10'),
(35, 'DummyF35', 'DummyL35', 'dummy_user_35', '$2y$10$xZQ5XkQvnD5GnAiJdlTIzi', 'mentor', 'aktif', 'Deskripsi dummy user 35.', 'profile_35.jpg', 'Jepang', 'London', 0, 1, 1, 'dummy_email_35@example.com', 'insta_user35', 'twitter_user35', 'linkedin_user35', 'github_user35', '2024-11-07 09:38:10'),
(36, 'DummyF36', 'DummyL36', 'dummy_user_36', '$2y$10$HmHYi9fVx0q2IrAd4yn7hS', 'admin', 'aktif', 'Deskripsi dummy user 36.', 'profile_36.jpg', 'Jepang', 'London', 1, 0, 0, 'dummy_email_36@example.com', 'insta_user36', 'twitter_user36', 'linkedin_user36', 'github_user36', '2025-03-16 09:38:10'),
(37, 'DummyF37', 'DummyL37', 'dummy_user_37', '$2y$10$EQmYOr3uG78PzNKUrfnlVC', 'admin', 'aktif', 'Deskripsi dummy user 37.', 'profile_37.jpg', 'Jepang', 'London', 0, 0, 0, 'dummy_email_37@example.com', 'insta_user37', 'twitter_user37', 'linkedin_user37', 'github_user37', '2024-06-17 09:38:10'),
(38, 'DummyF38', 'DummyL38', 'dummy_user_38', '$2y$10$MUvHRdicuxxDvQhNdAvCTY', 'mentor', 'aktif', 'Deskripsi dummy user 38.', 'profile_38.jpg', 'Jepang', 'Tokyo', 0, 0, 1, 'dummy_email_38@example.com', 'insta_user38', 'twitter_user38', 'linkedin_user38', 'github_user38', '2024-09-17 09:38:10'),
(39, 'DummyF39', 'DummyL39', 'dummy_user_39', '$2y$10$tLJXaxijEpImOVbl1yNqJz', 'admin', 'aktif', 'Deskripsi dummy user 39.', 'profile_39.jpg', 'Bahasa Indonesia', 'London', 1, 1, 1, 'dummy_email_39@example.com', 'insta_user39', 'twitter_user39', 'linkedin_user39', 'github_user39', '2024-12-29 09:38:10'),
(40, 'DummyF40', 'DummyL40', 'dummy_user_40', '$2y$10$fwnSCPxOQyVTU6MtBCtEIi', 'mentor', 'non-aktif', 'Deskripsi dummy user 40.', 'profile_40.jpg', 'Inggris', 'London', 1, 0, 1, 'dummy_email_40@example.com', 'insta_user40', 'twitter_user40', 'linkedin_user40', 'github_user40', '2024-12-13 09:38:10'),
(41, 'DummyF41', 'DummyL41', 'dummy_user_41', '$2y$10$RNV7lAO5Pzw9xDOTFORIvJ', 'murid', 'non-aktif', 'Deskripsi dummy user 41.', 'profile_41.jpg', 'Jepang', 'Tokyo', 1, 1, 0, 'dummy_email_41@example.com', 'insta_user41', 'twitter_user41', 'linkedin_user41', 'github_user41', '2024-08-25 09:38:10'),
(42, 'DummyF42', 'DummyL42', 'dummy_user_42', '$2y$10$xssm0tzzo8AkNY6z3PT5h0', 'admin', 'aktif', 'Deskripsi dummy user 42.', 'profile_42.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'dummy_email_42@example.com', 'insta_user42', 'twitter_user42', 'linkedin_user42', 'github_user42', '2024-10-19 09:38:10'),
(43, 'Rizki', 'Pratama', 'rizki_mentor', '$2y$10$example1hash', 'mentor', 'aktif', 'Expert Java Developer dengan 8 tahun pengalaman di industri fintech.', 'rizki_pratama.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'rizki.mentor@kelaskita.com', '@rizki_dev', '@rizkipratama', 'rizki-pratama', 'rizkipratama', '2025-06-09 16:51:19'),
(44, 'Sari', 'Indrawati', 'sari_mentor', '$2y$10$example2hash', 'mentor', 'aktif', 'UI/UX Designer profesional dengan portfolio internasional.', 'sari_indrawati.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'sari.mentor@kelaskita.com', '@sari_design', '@sariindrawati', 'sari-indrawati', 'sariindrawati', '2025-06-09 16:51:19'),
(45, 'Ahmad', 'Fauzi', 'ahmad_mentor', '$2y$10$example3hash', 'mentor', 'aktif', 'Business Analyst dan Digital Marketing Strategist.', 'ahmad_fauzi.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 1, 'ahmad.mentor@kelaskita.com', '@ahmad_biz', '@ahmadfauzi', 'ahmad-fauzi', 'ahmadfauzi', '2025-06-09 16:51:19'),
(46, 'Lisa', 'Wijaya', 'lisa_mentor', '$2y$10$example4hash', 'mentor', 'aktif', 'Psikolog klinis dan konselor pendidikan berpengalaman 10 tahun.', 'lisa_wijaya.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'lisa.mentor@kelaskita.com', '@lisa_psych', '@lisawijaya', 'lisa-wijaya', 'lisawijaya', '2025-06-09 16:51:19'),
(47, 'Doni', 'Setiawan', 'doni_mentor', '$2y$10$example5hash', 'mentor', 'aktif', 'Economics lecturer dan financial advisor.', 'doni_setiawan.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'doni.mentor@kelaskita.com', '@doni_econ', '@donisetiawan', 'doni-setiawan', 'donisetiawan', '2025-06-09 16:51:19'),
(48, 'Andi', 'Kurniawan', 'andi_murid', '$2y$10$example6hash', 'murid', 'aktif', 'Mahasiswa teknik informatika semester 6.', 'andi_kurniawan.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'andi.murid@example.com', '@andi_codes', '@andikurniawan', 'andi-kurniawan', 'andikurniawan', '2025-06-09 16:51:19'),
(49, 'Bella', 'Sartika', 'bella_murid', '$2y$10$example7hash', 'murid', 'aktif', 'Fresh graduate yang ingin berkarir di bidang design.', 'bella_sartika.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'bella.murid@example.com', '@bella_design', '@bellasartika', 'bella-sartika', 'bellasartika', '2025-06-09 16:51:19'),
(50, 'Chandra', 'Wijono', 'chandra_murid', '$2y$10$example8hash', 'murid', 'aktif', 'Entrepreneur muda yang ingin belajar digital marketing.', 'chandra_wijono.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 1, 'chandra.murid@example.com', '@chandra_biz', '@chandrawijono', 'chandra-wijono', 'chandrawijono', '2025-06-09 16:51:19'),
(51, 'Diana', 'Putri', 'diana_murid', '$2y$10$example9hash', 'murid', 'aktif', 'Karyawan bank yang ingin career switch ke IT.', 'diana_putri.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'diana.murid@example.com', '@diana_learns', '@dianaputri', 'diana-putri', 'dianaputri', '2025-06-09 16:51:19'),
(52, 'Erwin', 'Hakim', 'erwin_murid', '$2y$10$example10hash', 'murid', 'aktif', 'Freelancer yang ingin meningkatkan skill programming.', 'erwin_hakim.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'erwin.murid@example.com', '@erwin_dev', '@erwinhakim', 'erwin-hakim', 'erwinhakim', '2025-06-09 16:51:19'),
(53, 'Fitri', 'Ramadhani', 'fitri_murid', '$2y$10$example11hash', 'murid', 'aktif', 'Ibu rumah tangga yang ingin memulai bisnis online.', 'fitri_ramadhani.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 0, 'fitri.murid@example.com', '@fitri_mom', '@fitriramadhani', 'fitri-ramadhani', 'fitriramadhani', '2025-06-09 16:51:19'),
(54, 'Gilang', 'Pradana', 'gilang_murid', '$2y$10$example12hash', 'murid', 'aktif', 'Mahasiswa psikologi yang tertarik dengan teknologi.', 'gilang_pradana.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'gilang.murid@example.com', '@gilang_psych', '@gilangpradana', 'gilang-pradana', 'gilangpradana', '2025-06-09 16:51:19'),
(55, 'Hana', 'Safitri', 'hana_murid', '$2y$10$example13hash', 'murid', 'aktif', 'Designer grafis pemula yang ingin meningkatkan skill.', 'hana_safitri.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'hana.murid@example.com', '@hana_design', '@hanasafitri', 'hana-safitri', 'hanasafitri', '2025-06-09 16:51:19'),
(56, 'Ivan', 'Gunawan', 'ivan_murid', '$2y$10$example14hash', 'murid', 'aktif', 'Content creator yang ingin belajar data analysis.', 'ivan_gunawan.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'ivan.murid@example.com', '@ivan_content', '@ivangunawan', 'ivan-gunawan', 'ivangunawan', '2025-06-09 16:51:19'),
(57, 'Jihan', 'Aulia', 'jihan_murid', '$2y$10$example15hash', 'murid', 'non-aktif', 'Sedang cuti kuliah untuk fokus belajar programming.', 'jihan_aulia.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'jihan.murid@example.com', '@jihan_codes', '@jihanaulia', 'jihan-aulia', 'jihanaulia', '2025-06-09 16:51:19'),
(58, 'Rizki', 'Pratama', 'rizki_mentor', '$2y$10$example1hash', 'mentor', 'aktif', 'Expert Java Developer dengan 8 tahun pengalaman di industri fintech.', 'rizki_pratama.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'rizki.mentor@kelaskita.com', '@rizki_dev', '@rizkipratama', 'rizki-pratama', 'rizkipratama', '2025-06-09 16:51:29'),
(59, 'Sari', 'Indrawati', 'sari_mentor', '$2y$10$example2hash', 'mentor', 'aktif', 'UI/UX Designer profesional dengan portfolio internasional.', 'sari_indrawati.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'sari.mentor@kelaskita.com', '@sari_design', '@sariindrawati', 'sari-indrawati', 'sariindrawati', '2025-06-09 16:51:29'),
(60, 'Ahmad', 'Fauzi', 'ahmad_mentor', '$2y$10$example3hash', 'mentor', 'aktif', 'Business Analyst dan Digital Marketing Strategist.', 'ahmad_fauzi.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 1, 'ahmad.mentor@kelaskita.com', '@ahmad_biz', '@ahmadfauzi', 'ahmad-fauzi', 'ahmadfauzi', '2025-06-09 16:51:29'),
(61, 'Lisa', 'Wijaya', 'lisa_mentor', '$2y$10$example4hash', 'mentor', 'aktif', 'Psikolog klinis dan konselor pendidikan berpengalaman 10 tahun.', 'lisa_wijaya.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'lisa.mentor@kelaskita.com', '@lisa_psych', '@lisawijaya', 'lisa-wijaya', 'lisawijaya', '2025-06-09 16:51:29'),
(62, 'Doni', 'Setiawan', 'doni_mentor', '$2y$10$example5hash', 'mentor', 'aktif', 'Economics lecturer dan financial advisor.', 'doni_setiawan.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'doni.mentor@kelaskita.com', '@doni_econ', '@donisetiawan', 'doni-setiawan', 'donisetiawan', '2025-06-09 16:51:29'),
(63, 'Andi', 'Kurniawan', 'andi_murid', '$2y$10$example6hash', 'murid', 'aktif', 'Mahasiswa teknik informatika semester 6.', 'andi_kurniawan.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'andi.murid@example.com', '@andi_codes', '@andikurniawan', 'andi-kurniawan', 'andikurniawan', '2025-06-09 16:51:29'),
(64, 'Bella', 'Sartika', 'bella_murid', '$2y$10$example7hash', 'murid', 'aktif', 'Fresh graduate yang ingin berkarir di bidang design.', 'bella_sartika.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'bella.murid@example.com', '@bella_design', '@bellasartika', 'bella-sartika', 'bellasartika', '2025-06-09 16:51:29'),
(65, 'Chandra', 'Wijono', 'chandra_murid', '$2y$10$example8hash', 'murid', 'aktif', 'Entrepreneur muda yang ingin belajar digital marketing.', 'chandra_wijono.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 1, 'chandra.murid@example.com', '@chandra_biz', '@chandrawijono', 'chandra-wijono', 'chandrawijono', '2025-06-09 16:51:29'),
(66, 'Diana', 'Putri', 'diana_murid', '$2y$10$example9hash', 'murid', 'aktif', 'Karyawan bank yang ingin career switch ke IT.', 'diana_putri.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'diana.murid@example.com', '@diana_learns', '@dianaputri', 'diana-putri', 'dianaputri', '2025-06-09 16:51:29'),
(67, 'Erwin', 'Hakim', 'erwin_murid', '$2y$10$example10hash', 'murid', 'aktif', 'Freelancer yang ingin meningkatkan skill programming.', 'erwin_hakim.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'erwin.murid@example.com', '@erwin_dev', '@erwinhakim', 'erwin-hakim', 'erwinhakim', '2025-06-09 16:51:29'),
(68, 'Fitri', 'Ramadhani', 'fitri_murid', '$2y$10$example11hash', 'murid', 'aktif', 'Ibu rumah tangga yang ingin memulai bisnis online.', 'fitri_ramadhani.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 0, 'fitri.murid@example.com', '@fitri_mom', '@fitriramadhani', 'fitri-ramadhani', 'fitriramadhani', '2025-06-09 16:51:29'),
(69, 'Gilang', 'Pradana', 'gilang_murid', '$2y$10$example12hash', 'murid', 'aktif', 'Mahasiswa psikologi yang tertarik dengan teknologi.', 'gilang_pradana.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'gilang.murid@example.com', '@gilang_psych', '@gilangpradana', 'gilang-pradana', 'gilangpradana', '2025-06-09 16:51:29'),
(70, 'Hana', 'Safitri', 'hana_murid', '$2y$10$example13hash', 'murid', 'aktif', 'Designer grafis pemula yang ingin meningkatkan skill.', 'hana_safitri.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'hana.murid@example.com', '@hana_design', '@hanasafitri', 'hana-safitri', 'hanasafitri', '2025-06-09 16:51:29'),
(71, 'Ivan', 'Gunawan', 'ivan_murid', '$2y$10$example14hash', 'murid', 'aktif', 'Content creator yang ingin belajar data analysis.', 'ivan_gunawan.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'ivan.murid@example.com', '@ivan_content', '@ivangunawan', 'ivan-gunawan', 'ivangunawan', '2025-06-09 16:51:29'),
(72, 'Jihan', 'Aulia', 'jihan_murid', '$2y$10$example15hash', 'murid', 'non-aktif', 'Sedang cuti kuliah untuk fokus belajar programming.', 'jihan_aulia.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'jihan.murid@example.com', '@jihan_codes', '@jihanaulia', 'jihan-aulia', 'jihanaulia', '2025-06-09 16:51:29'),
(73, 'Rizki', 'Pratama', 'rizki_mentor', '$2y$10$example1hash', 'mentor', 'aktif', 'Expert Java Developer dengan 8 tahun pengalaman di industri fintech.', 'rizki_pratama.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'rizki.mentor@kelaskita.com', '@rizki_dev', '@rizkipratama', 'rizki-pratama', 'rizkipratama', '2025-06-09 16:52:32'),
(74, 'Sari', 'Indrawati', 'sari_mentor', '$2y$10$example2hash', 'mentor', 'aktif', 'UI/UX Designer profesional dengan portfolio internasional.', 'sari_indrawati.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'sari.mentor@kelaskita.com', '@sari_design', '@sariindrawati', 'sari-indrawati', 'sariindrawati', '2025-06-09 16:52:32'),
(75, 'Ahmad', 'Fauzi', 'ahmad_mentor', '$2y$10$example3hash', 'mentor', 'aktif', 'Business Analyst dan Digital Marketing Strategist.', 'ahmad_fauzi.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 1, 'ahmad.mentor@kelaskita.com', '@ahmad_biz', '@ahmadfauzi', 'ahmad-fauzi', 'ahmadfauzi', '2025-06-09 16:52:32'),
(76, 'Lisa', 'Wijaya', 'lisa_mentor', '$2y$10$example4hash', 'mentor', 'aktif', 'Psikolog klinis dan konselor pendidikan berpengalaman 10 tahun.', 'lisa_wijaya.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'lisa.mentor@kelaskita.com', '@lisa_psych', '@lisawijaya', 'lisa-wijaya', 'lisawijaya', '2025-06-09 16:52:32'),
(77, 'Doni', 'Setiawan', 'doni_mentor', '$2y$10$example5hash', 'mentor', 'aktif', 'Economics lecturer dan financial advisor.', 'doni_setiawan.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'doni.mentor@kelaskita.com', '@doni_econ', '@donisetiawan', 'doni-setiawan', 'donisetiawan', '2025-06-09 16:52:32'),
(78, 'Andi', 'Kurniawan', 'andi_murid', '$2y$10$example6hash', 'murid', 'aktif', 'Mahasiswa teknik informatika semester 6.', 'andi_kurniawan.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'andi.murid@example.com', '@andi_codes', '@andikurniawan', 'andi-kurniawan', 'andikurniawan', '2025-06-09 16:52:32'),
(79, 'Bella', 'Sartika', 'bella_murid', '$2y$10$example7hash', 'murid', 'aktif', 'Fresh graduate yang ingin berkarir di bidang design.', 'bella_sartika.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'bella.murid@example.com', '@bella_design', '@bellasartika', 'bella-sartika', 'bellasartika', '2025-06-09 16:52:32'),
(80, 'Chandra', 'Wijono', 'chandra_murid', '$2y$10$example8hash', 'murid', 'aktif', 'Entrepreneur muda yang ingin belajar digital marketing.', 'chandra_wijono.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 1, 'chandra.murid@example.com', '@chandra_biz', '@chandrawijono', 'chandra-wijono', 'chandrawijono', '2025-06-09 16:52:32'),
(81, 'Diana', 'Putri', 'diana_murid', '$2y$10$example9hash', 'murid', 'aktif', 'Karyawan bank yang ingin career switch ke IT.', 'diana_putri.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'diana.murid@example.com', '@diana_learns', '@dianaputri', 'diana-putri', 'dianaputri', '2025-06-09 16:52:32'),
(82, 'Erwin', 'Hakim', 'erwin_murid', '$2y$10$example10hash', 'murid', 'aktif', 'Freelancer yang ingin meningkatkan skill programming.', 'erwin_hakim.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'erwin.murid@example.com', '@erwin_dev', '@erwinhakim', 'erwin-hakim', 'erwinhakim', '2025-06-09 16:52:32'),
(83, 'Fitri', 'Ramadhani', 'fitri_murid', '$2y$10$example11hash', 'murid', 'aktif', 'Ibu rumah tangga yang ingin memulai bisnis online.', 'fitri_ramadhani.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 0, 'fitri.murid@example.com', '@fitri_mom', '@fitriramadhani', 'fitri-ramadhani', 'fitriramadhani', '2025-06-09 16:52:32'),
(84, 'Gilang', 'Pradana', 'gilang_murid', '$2y$10$example12hash', 'murid', 'aktif', 'Mahasiswa psikologi yang tertarik dengan teknologi.', 'gilang_pradana.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'gilang.murid@example.com', '@gilang_psych', '@gilangpradana', 'gilang-pradana', 'gilangpradana', '2025-06-09 16:52:32'),
(85, 'Hana', 'Safitri', 'hana_murid', '$2y$10$example13hash', 'murid', 'aktif', 'Designer grafis pemula yang ingin meningkatkan skill.', 'hana_safitri.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'hana.murid@example.com', '@hana_design', '@hanasafitri', 'hana-safitri', 'hanasafitri', '2025-06-09 16:52:32'),
(86, 'Ivan', 'Gunawan', 'ivan_murid', '$2y$10$example14hash', 'murid', 'aktif', 'Content creator yang ingin belajar data analysis.', 'ivan_gunawan.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'ivan.murid@example.com', '@ivan_content', '@ivangunawan', 'ivan-gunawan', 'ivangunawan', '2025-06-09 16:52:32'),
(87, 'Jihan', 'Aulia', 'jihan_murid', '$2y$10$example15hash', 'murid', 'non-aktif', 'Sedang cuti kuliah untuk fokus belajar programming.', 'jihan_aulia.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'jihan.murid@example.com', '@jihan_codes', '@jihanaulia', 'jihan-aulia', 'jihanaulia', '2025-06-09 16:52:32'),
(88, 'Rizki', 'Pratama', 'rizki_mentor', '$2y$10$example1hash', 'mentor', 'aktif', 'Expert Java Developer dengan 8 tahun pengalaman di industri fintech.', 'rizki_pratama.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'rizki.mentor@kelaskita.com', '@rizki_dev', '@rizkipratama', 'rizki-pratama', 'rizkipratama', '2025-06-09 16:53:49'),
(89, 'Sari', 'Indrawati', 'sari_mentor', '$2y$10$example2hash', 'mentor', 'aktif', 'UI/UX Designer profesional dengan portfolio internasional.', 'sari_indrawati.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'sari.mentor@kelaskita.com', '@sari_design', '@sariindrawati', 'sari-indrawati', 'sariindrawati', '2025-06-09 16:53:49'),
(90, 'Ahmad', 'Fauzi', 'ahmad_mentor', '$2y$10$example3hash', 'mentor', 'aktif', 'Business Analyst dan Digital Marketing Strategist.', 'ahmad_fauzi.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 1, 'ahmad.mentor@kelaskita.com', '@ahmad_biz', '@ahmadfauzi', 'ahmad-fauzi', 'ahmadfauzi', '2025-06-09 16:53:49'),
(91, 'Lisa', 'Wijaya', 'lisa_mentor', '$2y$10$example4hash', 'mentor', 'aktif', 'Psikolog klinis dan konselor pendidikan berpengalaman 10 tahun.', 'lisa_wijaya.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'lisa.mentor@kelaskita.com', '@lisa_psych', '@lisawijaya', 'lisa-wijaya', 'lisawijaya', '2025-06-09 16:53:49'),
(92, 'Doni', 'Setiawan', 'doni_mentor', '$2y$10$example5hash', 'mentor', 'aktif', 'Economics lecturer dan financial advisor.', 'doni_setiawan.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'doni.mentor@kelaskita.com', '@doni_econ', '@donisetiawan', 'doni-setiawan', 'donisetiawan', '2025-06-09 16:53:49'),
(93, 'Andi', 'Kurniawan', 'andi_murid', '$2y$10$example6hash', 'murid', 'aktif', 'Mahasiswa teknik informatika semester 6.', 'andi_kurniawan.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'andi.murid@example.com', '@andi_codes', '@andikurniawan', 'andi-kurniawan', 'andikurniawan', '2025-06-09 16:53:49'),
(94, 'Bella', 'Sartika', 'bella_murid', '$2y$10$example7hash', 'murid', 'aktif', 'Fresh graduate yang ingin berkarir di bidang design.', 'bella_sartika.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'bella.murid@example.com', '@bella_design', '@bellasartika', 'bella-sartika', 'bellasartika', '2025-06-09 16:53:49'),
(95, 'Chandra', 'Wijono', 'chandra_murid', '$2y$10$example8hash', 'murid', 'aktif', 'Entrepreneur muda yang ingin belajar digital marketing.', 'chandra_wijono.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 1, 'chandra.murid@example.com', '@chandra_biz', '@chandrawijono', 'chandra-wijono', 'chandrawijono', '2025-06-09 16:53:49'),
(96, 'Diana', 'Putri', 'diana_murid', '$2y$10$example9hash', 'murid', 'aktif', 'Karyawan bank yang ingin career switch ke IT.', 'diana_putri.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'diana.murid@example.com', '@diana_learns', '@dianaputri', 'diana-putri', 'dianaputri', '2025-06-09 16:53:49'),
(97, 'Erwin', 'Hakim', 'erwin_murid', '$2y$10$example10hash', 'murid', 'aktif', 'Freelancer yang ingin meningkatkan skill programming.', 'erwin_hakim.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'erwin.murid@example.com', '@erwin_dev', '@erwinhakim', 'erwin-hakim', 'erwinhakim', '2025-06-09 16:53:49'),
(98, 'Fitri', 'Ramadhani', 'fitri_murid', '$2y$10$example11hash', 'murid', 'aktif', 'Ibu rumah tangga yang ingin memulai bisnis online.', 'fitri_ramadhani.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 0, 'fitri.murid@example.com', '@fitri_mom', '@fitriramadhani', 'fitri-ramadhani', 'fitriramadhani', '2025-06-09 16:53:49'),
(99, 'Gilang', 'Pradana', 'gilang_murid', '$2y$10$example12hash', 'murid', 'aktif', 'Mahasiswa psikologi yang tertarik dengan teknologi.', 'gilang_pradana.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'gilang.murid@example.com', '@gilang_psych', '@gilangpradana', 'gilang-pradana', 'gilangpradana', '2025-06-09 16:53:49'),
(100, 'Hana', 'Safitri', 'hana_murid', '$2y$10$example13hash', 'murid', 'aktif', 'Designer grafis pemula yang ingin meningkatkan skill.', 'hana_safitri.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'hana.murid@example.com', '@hana_design', '@hanasafitri', 'hana-safitri', 'hanasafitri', '2025-06-09 16:53:49'),
(101, 'Ivan', 'Gunawan', 'ivan_murid', '$2y$10$example14hash', 'murid', 'aktif', 'Content creator yang ingin belajar data analysis.', 'ivan_gunawan.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 0, 'ivan.murid@example.com', '@ivan_content', '@ivangunawan', 'ivan-gunawan', 'ivangunawan', '2025-06-09 16:53:49'),
(102, 'Jihan', 'Aulia', 'jihan_murid', '$2y$10$example15hash', 'murid', 'non-aktif', 'Sedang cuti kuliah untuk fokus belajar programming.', 'jihan_aulia.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'jihan.murid@example.com', '@jihan_codes', '@jihanaulia', 'jihan-aulia', 'jihanaulia', '2025-06-09 16:53:49');

>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_video`
--

CREATE TABLE `tb_video` (
  `id_video` int(30) NOT NULL,
  `file_path_video` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
<<<<<<< HEAD
=======
-- Dumping data untuk tabel `tb_video`
--

INSERT INTO `tb_video` (`id_video`, `file_path_video`) VALUES
(1, 'Video Pengenalan PHP.mp4'),
(2, 'Video Variabel PHP.mp4'),
(3, 'Video Control Structure.mp4'),
(4, '../uploads/video/68330a508fd51_lv_7392906537750072583_20240905064053.mp4'),
(5, 'https://www.youtube.com/embed/71a2zeC71gk'),
(6, 'https://www.youtube.com/embed/71a2zeC71gk'),
(7, 'https://www.youtube.com/embed/71a2zeC71gk'),
(8, 'https://www.youtube.com/embed/71a2zeC71gk'),
(9, 'https://www.youtube.com/embed/71a2zeC71gk'),
(10, 'https://www.youtube.com/embed/71a2zeC71gk'),
(11, 'https://www.youtube.com/embed/71a2zeC71gk');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(0, '../uploads/video/VID000_INTRO_WEB.mp4', 'aktif'),
(1, '../uploads/video/VID001_HTML_TAGS.mp4', 'aktif'),
(2, '../uploads/video/VID002_CSS_SELECTORS.mp4', 'aktif'),
(3, '../uploads/video/VID003_JS_DOM_MANIPULATION.mp4', 'aktif'),
(4, '../uploads/video/VID004_SQL_JOINS.mp4', 'aktif'),
(5, '../uploads/video/VID005_PYTHON_PANDAS.mp4', 'aktif'),
(6, '../uploads/video/DUMMYVID_6.mp4', 'aktif'),
(7, '../uploads/video/DUMMYVID_7.mp4', 'non-aktif'),
(8, '../uploads/video/DUMMYVID_8.mp4', 'pending'),
(9, '../uploads/video/DUMMYVID_9.mp4', 'aktif'),
(10, '../uploads/video/DUMMYVID_10.mp4', 'non-aktif'),
(11, '../uploads/video/DUMMYVID_11.mp4', 'non-aktif'),
(12, '../uploads/video/DUMMYVID_12.mp4', 'aktif'),
(13, '../uploads/video/DUMMYVID_13.mp4', 'non-aktif'),
(14, '../uploads/video/DUMMYVID_14.mp4', 'aktif'),
(15, '../uploads/video/DUMMYVID_15.mp4', 'pending'),
(16, '../uploads/video/DUMMYVID_16.mp4', 'aktif'),
(17, '../uploads/video/DUMMYVID_17.mp4', 'pending'),
(18, '../uploads/video/DUMMYVID_18.mp4', 'non-aktif'),
(19, '../uploads/video/DUMMYVID_19.mp4', 'aktif'),
(20, '../uploads/video/DUMMYVID_20.mp4', 'pending'),
(21, '../uploads/video/DUMMYVID_21.mp4', 'aktif');

--
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_dokumen`
--
ALTER TABLE `tb_dokumen`
  ADD PRIMARY KEY (`id_dokumen`);

--
<<<<<<< HEAD
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `fk_kelas_mentor` (`id_mentor`);
=======
-- Indeks untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `tb_kategori_kelas`
--
ALTER TABLE `tb_kategori_kelas`
  ADD PRIMARY KEY (`id_kategori_kelas`),
  ADD KEY `fkid_kategori_kk` (`id_kategori`),
  ADD KEY `fkid_kelas_kk` (`id_kelas`);

--
-- Indeks untuk tabel `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `fkid_mentor_mentor` (`id_mentor`);
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123

--
-- Indeks untuk tabel `tb_keranjang`
--
ALTER TABLE `tb_keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `fkid_user_keranjang` (`id_user`),
  ADD KEY `fkid_kelas_keranjang` (`id_kelas`);

--
<<<<<<< HEAD
-- Indeks untuk tabel `tb_materi`
=======
<<<<<<< HEAD
-- Indexes for table `tb_laporan`
--
ALTER TABLE `tb_laporan`
  ADD PRIMARY KEY (`id_report`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_user` (`id_user`);
=======
>>>>>>> 0c80503c83755f2a7ea2c0af999c1a1b9821acba
-- Indexes for table `tb_komentar`
--
ALTER TABLE `tb_komentar`
  ADD PRIMARY KEY (`id_komentar`),
  ADD KEY `fkid_kelas` (`id_kelas`),
  ADD KEY `fkid_user` (`id_user`);
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123

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
-- Indeks untuk tabel `tb_mentor`
--
ALTER TABLE `tb_mentor`
  ADD PRIMARY KEY (`id_mentor`),
  ADD KEY `fkid_user_user` (`id_user`);

--
<<<<<<< HEAD
-- Indexes for table `tb_progress_kelas`
--
ALTER TABLE `tb_progress_kelas`
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_materi` (`id_materi`);

--
-- Indexes for table `tb_review`
--
ALTER TABLE `tb_review`
  ADD PRIMARY KEY (`id_review`);

--
-- Indexes for table `tb_sub_materi`
=======
-- Indeks untuk tabel `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tb_pengembangan_profesional`
--
ALTER TABLE `tb_pengembangan_profesional`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_sub_materi`
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
--
ALTER TABLE `tb_sub_materi`
  ADD PRIMARY KEY (`id_sub_materi`),
  ADD KEY `fkid_video_video` (`id_video`),
  ADD KEY `fkid_materi_materi` (`id_materi`),
  ADD KEY `fkid_dokumen_dokumen` (`id_dokumen`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_keranjang` (`id_keranjang`);

--
-- Indeks untuk tabel `tb_testimoni`
--
ALTER TABLE `tb_testimoni`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indeks untuk tabel `tb_video`
--
ALTER TABLE `tb_video`
  ADD PRIMARY KEY (`id_video`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
<<<<<<< HEAD
-- AUTO_INCREMENT for table `tb_dokumen`
--
ALTER TABLE `tb_dokumen`
  MODIFY `id_dokumen` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_laporan`
--
ALTER TABLE `tb_laporan`
  MODIFY `id_report` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_materi`
--
ALTER TABLE `tb_materi`
  MODIFY `id_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
=======
-- AUTO_INCREMENT untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
  MODIFY `id_kelas` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_komentar`
--
ALTER TABLE `tb_komentar`
  MODIFY `id_komentar` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tb_laporan`
--
ALTER TABLE `tb_laporan`
  MODIFY `id_report` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `tb_materi`
--
ALTER TABLE `tb_materi`
<<<<<<< HEAD
  MODIFY `id_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
  MODIFY `id_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
=======
  MODIFY `id_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
>>>>>>> 0c80503c83755f2a7ea2c0af999c1a1b9821acba

--
-- AUTO_INCREMENT untuk tabel `tb_mentor`
--
ALTER TABLE `tb_mentor`
<<<<<<< HEAD
  MODIFY `id_mentor` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
  MODIFY `id_mentor` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
=======
<<<<<<< HEAD
  MODIFY `id_mentor` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tb_review`
--
ALTER TABLE `tb_review`
  MODIFY `id_review` int(30) NOT NULL AUTO_INCREMENT;
=======
  MODIFY `id_mentor` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
>>>>>>> 0c80503c83755f2a7ea2c0af999c1a1b9821acba

--
-- AUTO_INCREMENT untuk tabel `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `tb_pengembangan_profesional`
--
ALTER TABLE `tb_pengembangan_profesional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_sub_materi`
--
ALTER TABLE `tb_sub_materi`
  MODIFY `id_sub_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_testimoni`
--
ALTER TABLE `tb_testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
<<<<<<< HEAD
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;
=======
<<<<<<< HEAD
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
=======
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
>>>>>>> 0c80503c83755f2a7ea2c0af999c1a1b9821acba

--
-- AUTO_INCREMENT untuk tabel `tb_video`
--
ALTER TABLE `tb_video`
<<<<<<< HEAD
  MODIFY `id_video` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
  MODIFY `id_video` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
=======
<<<<<<< HEAD
  MODIFY `id_video` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
=======
  MODIFY `id_video` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
>>>>>>> 0c80503c83755f2a7ea2c0af999c1a1b9821acba

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
<<<<<<< HEAD
-- Constraints for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD CONSTRAINT `fk_kelas_mentor` FOREIGN KEY (`id_mentor`) REFERENCES `tb_mentor` (`id_mentor`);

--
-- Constraints for table `tb_laporan`
--
ALTER TABLE `tb_laporan`
  ADD CONSTRAINT `tb_laporan_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`),
  ADD CONSTRAINT `tb_laporan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_materi`
=======
-- Ketidakleluasaan untuk tabel `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD CONSTRAINT `fkid_mentor_mentor` FOREIGN KEY (`id_mentor`) REFERENCES `tb_mentor` (`id_mentor`);

--
-- Ketidakleluasaan untuk tabel `tb_materi`
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
--
ALTER TABLE `tb_materi`
  ADD CONSTRAINT `fkid_kelas_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_mentor`
--
ALTER TABLE `tb_mentor`
  ADD CONSTRAINT `fkid_user_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
<<<<<<< HEAD
-- Constraints for table `tb_progress_kelas`
--
ALTER TABLE `tb_progress_kelas`
  ADD CONSTRAINT `tb_progress_kelas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `tb_progress_kelas_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`),
  ADD CONSTRAINT `tb_progress_kelas_ibfk_3` FOREIGN KEY (`id_materi`) REFERENCES `tb_materi` (`id_materi`);

--
-- Constraints for table `tb_sub_materi`
=======
-- Ketidakleluasaan untuk tabel `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD CONSTRAINT `tb_pembayaran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `tb_sub_materi`
>>>>>>> 108f9f33fda58bcc923cfe73f34db186b4d6b123
--
ALTER TABLE `tb_sub_materi`
  ADD CONSTRAINT `fkid_dokumen_dokumen` FOREIGN KEY (`id_dokumen`) REFERENCES `tb_dokumen` (`id_dokumen`),
  ADD CONSTRAINT `fkid_materi_materi` FOREIGN KEY (`id_materi`) REFERENCES `tb_materi` (`id_materi`),
  ADD CONSTRAINT `fkid_video_video` FOREIGN KEY (`id_video`) REFERENCES `tb_video` (`id_video`);

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `tb_transaksi_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`),
  ADD CONSTRAINT `tb_transaksi_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `tb_transaksi_ibfk_3` FOREIGN KEY (`id_keranjang`) REFERENCES `tb_keranjang` (`id_keranjang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;