-- phpMyAdmin SQL Dump - FIXED VERSION
-- Database: `kelaskita`
-- Fixed by: Claude
-- Date: May 24, 2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kelaskita`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(30) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('murid','mentor','admin') NOT NULL DEFAULT 'murid',
  `deskripsi` text DEFAULT NULL,
  `fotoProfil` varchar(255) DEFAULT NULL,
  `balasan_ke_komentar` tinyint(1) NOT NULL DEFAULT 1,
  `komentar_baru` tinyint(1) NOT NULL DEFAULT 1,
  `notifikasi_postingan_baru` tinyint(1) NOT NULL DEFAULT 1,
  `email` varchar(255) NOT NULL UNIQUE,
  `instagram` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `linkedin` varchar(100) DEFAULT NULL,
  `github` varchar(100) DEFAULT NULL,
  `status` enum('aktif','nonaktif','suspended') DEFAULT 'aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  KEY `idx_user_email` (`email`),
  KEY `idx_user_username` (`username`),
  KEY `idx_user_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_mentor`
--

CREATE TABLE `tb_mentor` (
  `id_mentor` int(30) NOT NULL AUTO_INCREMENT,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `id_user` int(30) NOT NULL,
  `keahlian` text DEFAULT NULL,
  `pengalaman` text DEFAULT NULL,
  `rating_avg` decimal(3,2) DEFAULT 0.00,
  `total_siswa` int(30) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mentor`),
  UNIQUE KEY `unique_mentor_user` (`id_user`),
  KEY `idx_mentor_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(30) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi_kategori` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `unique_nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` int(30) NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(255) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `profil_kelas` varchar(255) DEFAULT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `level` enum('pemula','menengah','lanjutan') DEFAULT 'pemula',
  `durasi_estimasi` int(30) DEFAULT NULL, -- dalam menit
  `max_siswa` int(30) DEFAULT NULL,
  `jumlah_siswa` int(30) DEFAULT 0,
  `rating_avg` decimal(3,2) DEFAULT 0.00,
  `total_review` int(30) DEFAULT 0,
  `status` enum('draft','aktif','nonaktif','selesai') DEFAULT 'draft',
  `id_mentor` int(30) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kelas`),
  KEY `idx_kelas_mentor` (`id_mentor`),
  KEY `idx_kelas_status` (`status`),
  KEY `idx_kelas_rating` (`rating_avg`),
  KEY `idx_kelas_harga` (`harga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori_kelas`
--

CREATE TABLE `tb_kategori_kelas` (
  `id_kategori_kelas` int(30) NOT NULL AUTO_INCREMENT,
  `id_kelas` int(30) NOT NULL,
  `id_kategori` int(30) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kategori_kelas`),
  UNIQUE KEY `unique_kelas_kategori` (`id_kelas`, `id_kategori`),
  KEY `idx_kk_kategori` (`id_kategori`),
  KEY `idx_kk_kelas` (`id_kelas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_video`
--

CREATE TABLE `tb_video` (
  `id_video` int(30) NOT NULL AUTO_INCREMENT,
  `nama_video` varchar(255) NOT NULL,
  `deskripsi_v` text DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint DEFAULT NULL,
  `duration` int DEFAULT NULL, -- durasi dalam detik
  `format` varchar(20) DEFAULT 'mp4',
  `thumbnail` varchar(500) DEFAULT NULL,
  `quality` enum('240p','360p','480p','720p','1080p') DEFAULT '720p',
  `status` enum('aktif','nonaktif','processing') DEFAULT 'processing',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_video`),
  KEY `idx_video_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_dokumen`
--

CREATE TABLE `tb_dokumen` (
  `id_dokumen` int(30) NOT NULL AUTO_INCREMENT,
  `nama_dokumen` varchar(255) NOT NULL,
  `deskripsi_d` text DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint DEFAULT NULL,
  `file_type` varchar(20) NOT NULL, -- pdf, docx, pptx, xlsx, dll
  `download_count` int(30) DEFAULT 0,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`),
  KEY `idx_dokumen_type` (`file_type`),
  KEY `idx_dokumen_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_materi`
--

CREATE TABLE `tb_materi` (
  `id_materi` int(30) NOT NULL AUTO_INCREMENT,
  `nama_materi` varchar(255) NOT NULL,
  `deskripsi_m` text DEFAULT NULL,
  `id_kelas` int(30) NOT NULL,
  `urutan` int(10) DEFAULT 1,
  `durasi_estimasi` int(30) DEFAULT NULL, -- dalam menit
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_materi`),
  KEY `idx_materi_kelas` (`id_kelas`),
  KEY `idx_materi_urutan` (`urutan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sub_materi`
--

CREATE TABLE `tb_sub_materi` (
  `id_sub_materi` int(30) NOT NULL AUTO_INCREMENT,
  `nama_sub_materi` varchar(255) NOT NULL,
  `deskripsi_sm` text DEFAULT NULL,
  `id_materi` int(30) NOT NULL,
  `urutan` int(10) DEFAULT 1,
  `tipe_konten` enum('video','dokumen','campuran','teks') NOT NULL DEFAULT 'teks',
  `konten_teks` longtext DEFAULT NULL,
  `durasi_estimasi` int(30) DEFAULT NULL, -- dalam menit
  `is_preview` tinyint(1) DEFAULT 0, -- bisa diakses tanpa beli kelas
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_sub_materi`),
  KEY `idx_sub_materi_materi` (`id_materi`),
  KEY `idx_sub_materi_urutan` (`urutan`),
  KEY `idx_sub_materi_tipe` (`tipe_konten`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sub_materi_video`
--

CREATE TABLE `tb_sub_materi_video` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `id_sub_materi` int(30) NOT NULL,
  `id_video` int(30) NOT NULL,
  `urutan` int(10) DEFAULT 1,
  `is_primary` tinyint(1) DEFAULT 0, -- video utama
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_sub_video` (`id_sub_materi`, `id_video`),
  KEY `idx_smv_sub_materi` (`id_sub_materi`),
  KEY `idx_smv_video` (`id_video`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sub_materi_dokumen`
--

CREATE TABLE `tb_sub_materi_dokumen` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `id_sub_materi` int(30) NOT NULL,
  `id_dokumen` int(30) NOT NULL,
  `urutan` int(10) DEFAULT 1,
  `is_primary` tinyint(1) DEFAULT 0, -- dokumen utama
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_sub_dokumen` (`id_sub_materi`, `id_dokumen`),
  KEY `idx_smd_sub_materi` (`id_sub_materi`),
  KEY `idx_smd_dokumen` (`id_dokumen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_keranjang`
--

CREATE TABLE `tb_keranjang` (
  `id_keranjang` int(30) NOT NULL AUTO_INCREMENT,
  `tgl_keranjang` timestamp DEFAULT CURRENT_TIMESTAMP,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `harga_saat_ini` decimal(12,2) NOT NULL, -- simpan harga saat ditambah ke keranjang
  `status` enum('aktif','checkout','expired') DEFAULT 'aktif',
  `expired_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_keranjang`),
  UNIQUE KEY `unique_user_kelas_keranjang` (`id_user`, `id_kelas`),
  KEY `idx_keranjang_user` (`id_user`),
  KEY `idx_keranjang_kelas` (`id_kelas`),
  KEY `idx_keranjang_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int(30) NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(50) NOT NULL UNIQUE,
  `id_user` int(30) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `metode_pembayaran` enum('transfer_bank','e_wallet','kartu_kredit') NOT NULL,
  `bukti_transaksi` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('pending','success','failed','expired') DEFAULT 'pending',
  `tgl_transaksi` timestamp DEFAULT CURRENT_TIMESTAMP,
  `tgl_konfirmasi` timestamp NULL DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  UNIQUE KEY `unique_kode_transaksi` (`kode_transaksi`),
  KEY `idx_transaksi_user` (`id_user`),
  KEY `idx_transaksi_status` (`status_pembayaran`),
  KEY `idx_transaksi_tanggal` (`tgl_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi_detail`
--

CREATE TABLE `tb_transaksi_detail` (
  `id_detail` int(30) NOT NULL AUTO_INCREMENT,
  `id_transaksi` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detail`),
  UNIQUE KEY `unique_transaksi_kelas` (`id_transaksi`, `id_kelas`),
  KEY `idx_detail_transaksi` (`id_transaksi`),
  KEY `idx_detail_kelas` (`id_kelas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_enrollment`
--

CREATE TABLE `tb_enrollment` (
  `id_enrollment` int(30) NOT NULL AUTO_INCREMENT,
  `id_user` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_transaksi` int(30) DEFAULT NULL,
  `tgl_enrollment` timestamp DEFAULT CURRENT_TIMESTAMP,
  `progress_percent` decimal(5,2) DEFAULT 0.00,
  `status` enum('aktif','selesai','suspend') DEFAULT 'aktif',
  `tgl_selesai` timestamp NULL DEFAULT NULL,
  `sertifikat_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_enrollment`),
  UNIQUE KEY `unique_user_kelas_enrollment` (`id_user`, `id_kelas`),
  KEY `idx_enrollment_user` (`id_user`),
  KEY `idx_enrollment_kelas` (`id_kelas`),
  KEY `idx_enrollment_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_progress_materi`
--

CREATE TABLE `tb_progress_materi` (
  `id_progress` int(30) NOT NULL AUTO_INCREMENT,
  `id_user` int(30) NOT NULL,
  `id_sub_materi` int(30) NOT NULL,
  `status` enum('belum_mulai','sedang_belajar','selesai') DEFAULT 'belum_mulai',
  `progress_percent` int(3) DEFAULT 0,
  `waktu_belajar` int(30) DEFAULT 0, -- dalam detik
  `waktu_mulai` timestamp NULL DEFAULT NULL,
  `waktu_selesai` timestamp NULL DEFAULT NULL,
  `terakhir_akses` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_progress`),
  UNIQUE KEY `unique_user_sub_materi` (`id_user`, `id_sub_materi`),
  KEY `idx_progress_user` (`id_user`),
  KEY `idx_progress_sub_materi` (`id_sub_materi`),
  KEY `idx_progress_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_komentar`
--

CREATE TABLE `tb_komentar` (
  `id_komentar` int(30) NOT NULL AUTO_INCREMENT,
  `isi` text NOT NULL,
  `id_user` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `parent_id` int(30) DEFAULT NULL, -- untuk reply komentar
  `status` enum('aktif','hidden','deleted') DEFAULT 'aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_komentar`),
  KEY `idx_komentar_kelas` (`id_kelas`),
  KEY `idx_komentar_user` (`id_user`),
  KEY `idx_komentar_parent` (`parent_id`),
  KEY `idx_komentar_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_review`
--

CREATE TABLE `tb_review` (
  `id_review` int(30) NOT NULL AUTO_INCREMENT,
  `bintang_review` tinyint(1) NOT NULL CHECK (`bintang_review` >= 1 AND `bintang_review` <= 5),
  `isi_review` text NOT NULL,
  `tgl_review` timestamp DEFAULT CURRENT_TIMESTAMP,
  `id_user` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `status` enum('aktif','hidden','deleted') DEFAULT 'aktif',
  `helpful_count` int(30) DEFAULT 0,
  PRIMARY KEY (`id_review`),
  UNIQUE KEY `unique_user_kelas_review` (`id_user`, `id_kelas`),
  KEY `idx_review_user` (`id_user`),
  KEY `idx_review_kelas` (`id_kelas`),
  KEY `idx_review_rating` (`bintang_review`),
  KEY `idx_review_tanggal` (`tgl_review`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_laporan`
--

CREATE TABLE `tb_laporan` (
  `id_report` int(30) NOT NULL AUTO_INCREMENT,
  `kategori_report` enum('kata_kasar','materi_tidak_relevan','pornografi','spam','plagiarisme','lainnya') NOT NULL,
  `keterangan_report` text NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL, -- user yang melaporkan
  `status` enum('pending','dalam_review','ditolak','diterima') DEFAULT 'pending',
  `tgl_report` timestamp DEFAULT CURRENT_TIMESTAMP,
  `tgl_review` timestamp NULL DEFAULT NULL,
  `reviewed_by` int(30) DEFAULT NULL, -- admin yang me-review
  `catatan_admin` text DEFAULT NULL,
  PRIMARY KEY (`id_report`),
  KEY `idx_laporan_user` (`id_user`),
  KEY `idx_laporan_kelas` (`id_kelas`),
  KEY `idx_laporan_status` (`status`),
  KEY `idx_laporan_kategori` (`kategori_report`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- FOREIGN KEY CONSTRAINTS
--

-- Mentor constraints
ALTER TABLE `tb_mentor`
  ADD CONSTRAINT `fk_mentor_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE;

-- Kelas constraints
ALTER TABLE `tb_kelas`
  ADD CONSTRAINT `fk_kelas_mentor` FOREIGN KEY (`id_mentor`) REFERENCES `tb_mentor` (`id_mentor`) ON DELETE CASCADE;

-- Kategori kelas constraints
ALTER TABLE `tb_kategori_kelas`
  ADD CONSTRAINT `fk_kategori_kelas_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `tb_kategori` (`id_kategori`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_kategori_kelas_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE;

-- Materi constraints
ALTER TABLE `tb_materi`
  ADD CONSTRAINT `fk_materi_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE;

-- Sub materi constraints
ALTER TABLE `tb_sub_materi`
  ADD CONSTRAINT `fk_sub_materi_materi` FOREIGN KEY (`id_materi`) REFERENCES `tb_materi` (`id_materi`) ON DELETE CASCADE;

-- Sub materi video constraints
ALTER TABLE `tb_sub_materi_video`
  ADD CONSTRAINT `fk_smv_sub_materi` FOREIGN KEY (`id_sub_materi`) REFERENCES `tb_sub_materi` (`id_sub_materi`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_smv_video` FOREIGN KEY (`id_video`) REFERENCES `tb_video` (`id_video`) ON DELETE CASCADE;

-- Sub materi dokumen constraints
ALTER TABLE `tb_sub_materi_dokumen`
  ADD CONSTRAINT `fk_smd_sub_materi` FOREIGN KEY (`id_sub_materi`) REFERENCES `tb_sub_materi` (`id_sub_materi`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_smd_dokumen` FOREIGN KEY (`id_dokumen`) REFERENCES `tb_dokumen` (`id_dokumen`) ON DELETE CASCADE;

-- Keranjang constraints
ALTER TABLE `tb_keranjang`
  ADD CONSTRAINT `fk_keranjang_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_keranjang_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE;

-- Transaksi constraints
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE;

-- Transaksi detail constraints
ALTER TABLE `tb_transaksi_detail`
  ADD CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id_transaksi`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE;

-- Enrollment constraints
ALTER TABLE `tb_enrollment`
  ADD CONSTRAINT `fk_enrollment_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_enrollment_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_enrollment_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id_transaksi`) ON DELETE SET NULL;

-- Progress constraints
ALTER TABLE `tb_progress_materi`
  ADD CONSTRAINT `fk_progress_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_progress_sub_materi` FOREIGN KEY (`id_sub_materi`) REFERENCES `tb_sub_materi` (`id_sub_materi`) ON DELETE CASCADE;

-- Komentar constraints
ALTER TABLE `tb_komentar`
  ADD CONSTRAINT `fk_komentar_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_komentar_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_komentar_parent` FOREIGN KEY (`parent_id`) REFERENCES `tb_komentar` (`id_komentar`) ON DELETE CASCADE;

-- Review constraints
ALTER TABLE `tb_review`
  ADD CONSTRAINT `fk_review_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE;

-- Laporan constraints
ALTER TABLE `tb_laporan`
  ADD CONSTRAINT `fk_laporan_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_laporan_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_laporan_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `tb_user` (`id_user`) ON DELETE SET NULL;

-- --------------------------------------------------------

--
-- TRIGGERS untuk otomatis update statistik
--

DELIMITER $$

-- Trigger untuk update jumlah siswa di kelas
CREATE TRIGGER `tr_update_jumlah_siswa_insert` 
AFTER INSERT ON `tb_enrollment`
FOR EACH ROW 
BEGIN
    UPDATE tb_kelas 
    SET jumlah_siswa = (
        SELECT COUNT(*) FROM tb_enrollment 
        WHERE id_kelas = NEW.id_kelas AND status = 'aktif'
    )
    WHERE id_kelas = NEW.id_kelas;
END$$

CREATE TRIGGER `tr_update_jumlah_siswa_update` 
AFTER UPDATE ON `tb_enrollment`
FOR EACH ROW 
BEGIN
    UPDATE tb_kelas 
    SET jumlah_siswa = (
        SELECT COUNT(*) FROM tb_enrollment 
        WHERE id_kelas = NEW.id_kelas AND status = 'aktif'
    )
    WHERE id_kelas = NEW.id_kelas;
END$$

-- Trigger untuk update rating kelas
CREATE TRIGGER `tr_update_rating_kelas_insert` 
AFTER INSERT ON `tb_review`
FOR EACH ROW 
BEGIN
    UPDATE tb_kelas 
    SET rating_avg = (
        SELECT AVG(bintang_review) FROM tb_review 
        WHERE id_kelas = NEW.id_kelas AND status = 'aktif'
    ),
    total_review = (
        SELECT COUNT(*) FROM tb_review 
        WHERE id_kelas = NEW.id_kelas AND status = 'aktif'
    )
    WHERE id_kelas = NEW.id_kelas;
END$$

CREATE TRIGGER `tr_update_rating_kelas_update` 
AFTER UPDATE ON `tb_review`
FOR EACH ROW 
BEGIN
    UPDATE tb_kelas 
    SET rating_avg = (
        SELECT AVG(bintang_review) FROM tb_review 
        WHERE id_kelas = NEW.id_kelas AND status = 'aktif'
    ),
    total_review = (
        SELECT COUNT(*) FROM tb_review 
        WHERE id_kelas = NEW.id_kelas AND status = 'aktif'
    )
    WHERE id_kelas = NEW.id_kelas;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- VIEWS untuk query yang sering digunakan
--

-- View untuk kelas dengan informasi mentor
CREATE VIEW `vw_kelas_detail` AS
SELECT 
    k.id_kelas,
    k.nama_kelas,
    k.harga,
    k.description,
    k.level,
    k.rating_avg,
    k.total_review,
    k.jumlah_siswa,
    k.status as status_kelas,
    u.first_name as mentor_first_name,
    u.last_name as mentor_last_name,
    u.fotoProfil as mentor_foto,
    m.keahlian as mentor_keahlian,
    m.rating_avg as mentor_rating,
    GROUP_CONCAT(kat.nama_kategori SEPARATOR ', ') as kategori
FROM tb_kelas k
JOIN tb_mentor m ON k.id_mentor = m.id_mentor
JOIN tb_user u ON m.id_user = u.id_user
LEFT JOIN tb_kategori_kelas kk ON k.id_kelas = kk.id_kelas
LEFT JOIN tb_kategori kat ON kk.id_kategori = kat.id_kategori