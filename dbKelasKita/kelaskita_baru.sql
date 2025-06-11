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
  `file_path_dokumen` varchar(255) NOT NULL,
  `status` enum('pending','aktif','non-aktif') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `tb_kategori` (
  `id_kategori` int(30) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(30) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `jumlah_kursus` int(11) DEFAULT 0,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kelas`
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
-- Struktur dari tabel `tb_keranjang`
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

CREATE TABLE `tb_komentar` (
  `id_komentar` int(30) NOT NULL,
  `isi` varchar(255) NOT NULL,
  `id_user` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_laporan`
--

CREATE TABLE `tb_laporan` (
  `id_report` int(11) NOT NULL,
  `kategori_report` enum('Penggunaan kata kasar','Materi tidak relevan','Pornografi') NOT NULL,
  `keterangan_report` varchar(100) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_laporan` enum('Belum Diproses','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Belum Diproses',
  `catatan_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_laporan`
--

INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`, `catatan_admin`) VALUES
(1, 'Penggunaan kata kasar', 'Ada beberapa komentar yang menggunakan bahasa tidak pantas di kelas ini.', 1, 4, '2025-06-05 03:00:00', 'Belum Diproses', NULL),
(3, 'Pornografi', 'Ditemukan konten yang tidak senonoh pada bagian diskusi kelas.', 3, 7, '2025-06-05 07:15:00', 'Selesai', NULL),
(4, 'Penggunaan kata kasar', 'Mentor sering menggunakan kata-kata kasar saat menjelaskan materi.', 4, 4, '2025-06-06 02:00:00', 'Belum Diproses', NULL),
(5, 'Materi tidak relevan', 'Video yang dilampirkan tidak berhubungan dengan topik materi.', 5, 5, '2025-06-06 06:45:00', 'Belum Diproses', NULL),
(6, 'Materi tidak relevan', 'Keterangan laporan dummy ke-6: Materi tidak relevan.', 9, 39, '2025-05-18 09:38:10', 'Diproses', NULL),
(7, 'Materi tidak relevan', 'Keterangan laporan dummy ke-7: Materi tidak relevan.', 15, 34, '2025-05-24 09:38:10', 'Diproses', NULL),
(8, 'Pornografi', 'Keterangan laporan dummy ke-8: Konten tidak senonoh.', 16, 27, '2025-05-29 09:38:10', 'Belum Diproses', NULL),
(9, 'Materi tidak relevan', 'Keterangan laporan dummy ke-9: Materi tidak relevan.', 14, 29, '2025-05-13 09:38:10', 'Selesai', NULL),
(10, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-10: Ada beberapa kata kasar.', 11, 25, '2025-05-26 09:38:10', 'Belum Diproses', NULL),
(11, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-11: Ada beberapa kata kasar.', 14, 30, '2025-05-27 09:38:10', 'Diproses', NULL),
(12, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-12: Ada beberapa kata kasar.', 17, 34, '2025-05-12 09:38:10', 'Diproses', NULL),
(13, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-13: Ada beberapa kata kasar.', 13, 30, '2025-05-27 09:38:10', 'Diproses', NULL),
(14, 'Materi tidak relevan', 'Keterangan laporan dummy ke-14: Materi tidak relevan.', 13, 32, '2025-05-23 09:38:10', 'Belum Diproses', NULL),
(15, 'Materi tidak relevan', 'Keterangan laporan dummy ke-15: Materi tidak relevan.', 14, 25, '2025-05-26 09:38:10', 'Belum Diproses', NULL),
(16, 'Pornografi', 'Keterangan laporan dummy ke-16: Konten tidak senonoh.', 16, 35, '2025-05-23 09:38:10', 'Belum Diproses', NULL),
(17, 'Pornografi', 'Keterangan laporan dummy ke-17: Konten tidak senonoh.', 11, 24, '2025-05-18 09:38:10', 'Belum Diproses', NULL),
(18, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-18: Ada beberapa kata kasar.', 16, 26, '2025-05-11 09:38:10', 'Belum Diproses', NULL),
(19, 'Pornografi', 'Keterangan laporan dummy ke-19: Konten tidak senonoh.', 17, 33, '2025-05-18 09:38:10', 'Diproses', NULL),
(20, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-20: Ada beberapa kata kasar.', 9, 39, '2025-05-17 09:38:10', 'Belum Diproses', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_materi`
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
(10, 7, 1, 'Pengenalan PHP');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_mentor`
--

CREATE TABLE `tb_mentor` (
  `id_mentor` int(30) NOT NULL,
  `status` enum('Aktif','Non-Aktif') NOT NULL,
  `id_user` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_mentor`
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
-- Struktur dari tabel `tb_notifikasi`
--

CREATE TABLE `tb_notifikasi` (
  `id_notifikasi` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `pesan_notif` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_notifikasi`
--

INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(1, 35, 'Pesan notifikasi dummy ke-1: Kelas baru tersedia atau ada balasan komentar di kelas Anda.'),
(2, 36, 'Pesan notifikasi dummy ke-2: Kelas baru tersedia atau ada balasan komentar di kelas Anda.'),
(3, 40, 'Pesan notifikasi dummy ke-3: Kelas baru tersedia atau ada balasan komentar di kelas Anda.'),
(4, 28, 'Pesan notifikasi dummy ke-4: Kelas baru tersedia atau ada balasan komentar di kelas Anda.'),
(5, 31, 'Pesan notifikasi dummy ke-5: Kelas baru tersedia atau ada balasan komentar di kelas Anda.'),
(6, 32, 'Pesan notifikasi dummy ke-6: Kelas baru tersedia atau ada balasan komentar di kelas Anda.'),
(7, 30, 'Pesan notifikasi dummy ke-7: Kelas baru tersedia atau ada balasan komentar di kelas Anda.'),
(8, 23, 'Pesan notifikasi dummy ke-8: Kelas baru tersedia atau ada balasan komentar di kelas Anda.'),
(9, 39, 'Pesan notifikasi dummy ke-9: Kelas baru tersedia atau ada balasan komentar di kelas Anda.'),
(10, 29, 'Pesan notifikasi dummy ke-10: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');

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
-- Struktur dari tabel `tb_progress_kelas`
--

CREATE TABLE `tb_progress_kelas` (
  `id_progress_kelas` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `id_materi` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_progress_kelas`
--

INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(1, 16, 27, 10),
(2, 17, 34, 15),
(3, 10, 24, 21),
(4, 13, 31, 20),
(5, 12, 38, 22),
(6, 17, 34, 24),
(7, 14, 24, 10),
(8, 17, 42, 27),
(9, 12, 36, 19),
(10, 16, 32, 22);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_review`
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
(2, '4', 'Review dummy ke-2: Kelas ini sangat informatif.', '2025-03-24 16:38:10', 32, 12),
(3, '4', 'Review dummy ke-3: Kelas ini sangat luar biasa.', '2025-05-09 16:38:10', 34, 13),
(4, '5', 'Review dummy ke-4: Kelas ini sangat bagus.', '2025-04-12 16:38:10', 41, 14),
(5, '4', 'Review dummy ke-5: Kelas ini sangat bagus.', '2025-03-09 16:38:10', 35, 10),
(6, '2', 'Review dummy ke-6: Kelas ini sangat bagus.', '2025-04-24 16:38:10', 37, 16),
(7, '1', 'Review dummy ke-7: Kelas ini sangat kurang memuaskan.', '2025-03-10 16:38:10', 30, 15),
(8, '4', 'Review dummy ke-8: Kelas ini sangat luar biasa.', '2025-05-18 16:38:10', 33, 16),
(9, '5', 'Review dummy ke-9: Kelas ini sangat informatif.', '2025-05-05 16:38:10', 25, 9),
(10, '5', 'Review dummy ke-10: Kelas ini sangat informatif.', '2025-03-23 16:38:10', 36, 15),
(11, '5', 'Review dummy ke-11: Kelas ini sangat bagus.', '2025-03-13 16:38:10', 29, 13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_sub_materi`
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
  `tgl_transaksi` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','acc','ditolak') NOT NULL DEFAULT 'pending'
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
(17, '', '', 'blabla', '$2y$10$4imiVUlOZFABSuRzavGqxeghZqhCIFY6JCyfVXRsj0fKWbxqBYQWi', 'mentor', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_video`
--

CREATE TABLE `tb_video` (
  `id_video` int(30) NOT NULL,
  `file_path_video` varchar(255) NOT NULL,
  `status` enum('pending','aktif','non-aktif') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_video`
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
-- Indeks untuk tabel `tb_dokumen`
--
ALTER TABLE `tb_dokumen`
  ADD PRIMARY KEY (`id_dokumen`);

--
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

--
-- Indeks untuk tabel `tb_keranjang`
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
-- Indeks untuk tabel `tb_mentor`
--
ALTER TABLE `tb_mentor`
  ADD PRIMARY KEY (`id_mentor`),
  ADD KEY `fkid_user_user` (`id_user`);

--
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
--
ALTER TABLE `tb_sub_materi`
  ADD PRIMARY KEY (`id_sub_materi`),
  ADD KEY `fkid_video_video` (`id_video`),
  ADD KEY `fkid_dokumen_dokumen` (`id_dokumen`),
  ADD KEY `fkid_materi_materi` (`id_materi`);

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
-- AUTO_INCREMENT untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tb_materi`
--
ALTER TABLE `tb_materi`
  MODIFY `id_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tb_mentor`
--
ALTER TABLE `tb_mentor`
  MODIFY `id_mentor` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id_sub_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

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
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `tb_video`
--
ALTER TABLE `tb_video`
  MODIFY `id_video` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD CONSTRAINT `fkid_mentor_mentor` FOREIGN KEY (`id_mentor`) REFERENCES `tb_mentor` (`id_mentor`);

--
-- Ketidakleluasaan untuk tabel `tb_materi`
--
ALTER TABLE `tb_materi`
  ADD CONSTRAINT `fkid_kelas_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`);

--
-- Ketidakleluasaan untuk tabel `tb_mentor`
--
ALTER TABLE `tb_mentor`
  ADD CONSTRAINT `fkid_user_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD CONSTRAINT `tb_pembayaran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `tb_sub_materi`
--
ALTER TABLE `tb_sub_materi`
  ADD CONSTRAINT `fkid_dokumen_dokumen` FOREIGN KEY (`id_dokumen`) REFERENCES `tb_dokumen` (`id_dokumen`),
  ADD CONSTRAINT `fkid_materi_materi` FOREIGN KEY (`id_materi`) REFERENCES `tb_materi` (`id_materi`),
  ADD CONSTRAINT `fkid_video_video` FOREIGN KEY (`id_video`) REFERENCES `tb_video` (`id_video`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
