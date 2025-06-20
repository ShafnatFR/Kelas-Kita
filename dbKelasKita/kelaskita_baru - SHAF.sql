-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 12:08 PM
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
-- Table structure for table `tb_catatan`
--

CREATE TABLE `tb_catatan` (
  `id_catatan` int(11) NOT NULL,
  `isi_catatan` text NOT NULL,
  `id_mentor` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_catatan`
--

INSERT INTO `tb_catatan` (`id_catatan`, `isi_catatan`, `id_mentor`, `id_kelas`) VALUES
(1, 'TES', 2, 2),
(2, 'Shafnat Ganteng', 3, 3),
(3, 'Sigma', 3, 3),
(4, '1. Konten pornografi', 3, 8),
(5, '1. Konten Pornografi', 3, 8),
(6, '1. Konten pornografi', 1, 9),
(7, 'Tes', 1, 9),
(8, 'Tessss', 1, 9),
(9, 'Tes', 1, 1),
(10, 'Tesiluhlkukhu', 3, 8),
(11, 'Materi tidak sesuai', 1, 9),
(12, 'Adanya konten pornografi', 2, 5),
(13, 'Penggunaan kata kasar', 1, 1),
(14, 'Penggunaan kata kasar', 3, 8);

-- --------------------------------------------------------

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
(1, '/documents/webdev_module1.pdf'),
(2, '/documents/java_basics.pdf'),
(3, '/documents/design_principles.pdf'),
(4, '/documents/sql_cheatsheet.pdf'),
(5, '/documents/python_intro.pdf'),
(6, '/documents/business_plan_template.docx'),
(7, '/documents/economic_theories.pdf'),
(8, '/documents/psychology_101.pdf'),
(9, '/documents/it_support_guide.pdf'),
(10, '/documents/advanced_sql.pdf'),
(15, '/uploads/documents/python_basics.pdf'),
(16, '/uploads/documents/django_tutorial.pdf'),
(17, '/uploads/documents/mysql_guide.pdf'),
(18, '/uploads/documents/laravel_handbook.pdf'),
(19, '/uploads/documents/ui_design_principles.pdf'),
(20, '/uploads/documents/figma_masterclass.pdf'),
(21, '/uploads/documents/business_plan_template.pdf'),
(22, '/uploads/documents/startup_strategy.pdf'),
(23, '/uploads/documents/java_fundamentals.pdf'),
(24, '/uploads/documents/web_development_guide.pdf'),
(25, '/uploads/documents/sql_advanced.pdf'),
(26, '/uploads/documents/psychology_basics.pdf'),
(27, '/uploads/documents/economics_101.pdf'),
(28, '/uploads/documents/it_infrastructure.pdf'),
(29, '/documents/marketing_strategies.pdf'),
(30, '/documents/ai_intro.pdf'),
(31, '/documents/blockchain_basics.pdf'),
(32, '/documents/cloud_computing.pdf'),
(33, '/documents/cybersecurity_fundamentals.pdf'),
(34, '/documents/data_visualization.pdf'),
(35, '/documents/machine_learning_concepts.pdf'),
(36, '/documents/mobile_dev_android.pdf'),
(37, '/documents/ios_dev_swift.pdf'),
(38, '/documents/network_security.pdf'),
(39, '/documents/project_management.pdf'),
(40, '/documents/statistical_analysis.pdf'),
(41, '/documents/web_security.pdf'),
(42, '/documents/frontend_frameworks.pdf'),
(43, '/documents/backend_frameworks.pdf'),
(44, '/documents/devops_guide.pdf'),
(45, '/documents/agile_methodology.pdf'),
(46, '/documents/digital_marketing.pdf'),
(47, '/documents/financial_accounting.pdf'),
(48, '/documents/human_resources.pdf');

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
  `profil_kelas` varchar(100) DEFAULT 'default.jpg',
  `description` text DEFAULT NULL,
  `status_publikasi` enum('draft','non-aktif','pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ada_sertifikat` int(1) DEFAULT NULL,
  `tanggal_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `list_transaksi` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `description`, `status_publikasi`, `tgl_dibuat`, `ada_sertifikat`, `tanggal_update`, `list_transaksi`) VALUES
(1, 1, 'Full-Stack Web Developer Bootcamp', 'Web Development', 1500000.00, 'tabs-hero.jpg', 'Learn to build web apps from scratch.', 'non-aktif', '2025-06-12 04:49:30', 1, '2025-06-12 04:49:30', NULL),
(2, 2, 'Java for Beginners', 'Java', 850000.00, 'tabs-hero.jpg', 'Master the fundamentals of Java programming.', 'approved', '2025-06-12 03:45:43', 1, '2025-06-12 03:45:43', NULL),
(3, 3, 'UI/UX Design Masterclass', 'Design', 1200000.00, 'tabs-hero.jpg', 'From wireframe to high-fidelity prototype.', 'approved', '2025-06-12 03:45:42', 1, '2025-06-12 03:45:42', NULL),
(4, 1, 'Advanced SQL for Data Analysts', 'SQL', 950000.00, 'tabs-hero.jpg', 'Deep dive into complex queries and optimization.', 'approved', '2025-06-12 00:20:50', 1, '2025-06-12 00:20:50', NULL),
(5, 2, 'Python for Data Science', 'Python', 1100000.00, 'tabs-hero.jpg', 'Learn Python with Pandas, NumPy, and Matplotlib.', 'non-aktif', '2025-06-12 04:49:05', 1, '2025-06-12 04:49:05', NULL),
(7, 4, 'Introduction to Economics', 'Ekonomi', 600000.00, 'tabs-hero.jpg', 'Understand the principles of market economy.', 'draft', '2025-06-12 00:20:50', 0, '2025-06-12 00:20:50', NULL),
(8, 3, 'Introduction to Psychology', 'Psikologi', 650000.00, 'tabs-hero.jpg', 'A journey into the human mind.', 'approved', '2025-06-12 04:51:18', 1, '2025-06-12 04:51:18', NULL),
(9, 1, 'IT Fundamentals for Everyone', 'IT', 500000.00, 'tabs-hero.jpg', 'Learn the basics of IT and computer systems.', 'non-aktif', '2025-06-12 04:48:40', 1, '2025-06-12 04:48:40', NULL),
(10, 2, 'Web Scraping with Python', 'Python', 900000.00, 'tabs-hero.jpg', 'Automate data extraction from websites.', 'approved', '2025-06-12 00:20:50', 1, '2025-06-12 00:20:50', NULL),
(11, 11, 'Machine Learning with Python', 'Python', 1800000.00, 'tabs-hero.jpg', 'Build and deploy machine learning models.', 'approved', '2025-06-12 00:20:50', 1, '2025-06-12 00:20:50', NULL),
(12, 12, 'AWS Cloud Practitioner Prep', 'IT', 1300000.00, 'tabs-hero.jpg', 'Prepare for your AWS Cloud Practitioner certification.', 'approved', '2025-06-12 00:20:50', 1, '2025-06-12 00:20:50', NULL),
(13, 13, 'Cybersecurity Fundamentals', 'IT', 1000000.00, 'tabs-hero.jpg', 'Understand the basics of protecting digital information.', 'pending', '2025-06-12 00:20:50', 0, '2025-06-12 00:20:50', NULL),
(14, 14, 'Android App Development with Kotlin', 'Java', 1600000.00, 'tabs-hero.jpg', 'Develop native Android applications.', 'approved', '2025-06-12 00:20:50', 1, '2025-06-12 00:20:50', NULL),
(15, 15, 'Agile Project Management', 'Bisnis', 900000.00, 'tabs-hero.jpg', 'Learn to manage projects efficiently using Agile.', 'approved', '2025-06-12 00:20:50', 1, '2025-06-12 00:20:50', NULL),
(16, 1, 'React JS for Beginners', 'Web Development', 1200000.00, 'tabs-hero.jpg', 'Build dynamic user interfaces with React.', 'approved', '2025-06-12 00:20:50', 1, '2025-06-12 00:20:50', NULL),
(17, 2, 'Spring Boot Microservices', 'Java', 1700000.00, 'tabs-hero.jpg', 'Develop scalable microservices with Spring Boot.', 'approved', '2025-06-12 00:20:50', 1, '2025-06-12 00:20:50', NULL),
(18, 3, 'Figma UI/UX Workshop', 'Design', 1100000.00, 'tabs-hero.jpg', 'Hands-on workshop for creating designs in Figma.', 'approved', '2025-06-12 00:20:50', 1, '2025-06-12 00:20:50', NULL);

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
(1, '2025-06-11', 1, 5),
(2, '2025-06-11', 2, 5),
(3, '2025-06-11', 4, 6),
(4, '2025-06-12', 5, 6),
(5, '2025-06-12', 8, 7),
(6, '2025-06-12', 9, 8),
(7, '2025-06-13', 10, 8),
(8, '2025-06-13', 3, 9),
(9, '2025-06-13', 1, 10),
(10, '2025-06-14', 5, 10),
(11, '2025-06-14', 11, 36),
(12, '2025-06-14', 12, 37),
(13, '2025-06-14', 13, 38),
(14, '2025-06-15', 14, 39),
(15, '2025-06-15', 15, 40),
(16, '2025-06-15', 16, 5),
(17, '2025-06-16', 17, 6),
(18, '2025-06-16', 18, 7),
(19, '2025-06-16', 11, 8),
(20, '2025-06-17', 12, 9),
(21, '2025-06-17', 14, 10),
(22, '2025-06-17', 15, 30),
(23, '2025-06-18', 16, 31),
(24, '2025-06-18', 17, 32),
(25, '2025-06-18', 18, 33);

-- --------------------------------------------------------

--
-- Table structure for table `tb_laporan`
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

--
-- Dumping data for table `tb_laporan`
--

INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `tgl_dibuat`, `id_kelas`, `id_user`, `status_laporan`) VALUES
(2, 'Penggunaan kata kasar', 'Mentor di kelas IT sesekali menggunakan kata yang tidak pantas.', '2025-06-11 16:20:08', 9, 10, 'Diproses'),
(3, 'Materi tidak relevan', 'Judulnya advanced sql tapi isinya masih dasar.', '2025-06-11 16:20:08', 4, 6, 'Belum Diproses'),
(4, 'Pornografi', 'Ada gambar tidak senonoh di materi psikologi.', '2025-06-11 16:20:08', 8, 7, 'Ditolak'),
(5, 'Materi tidak relevan', 'Deskripsi kelas tidak sesuai dengan isi materi.', '2025-06-11 16:20:08', 7, 5, 'Belum Diproses'),
(6, 'Penggunaan kata kasar', 'Beberapa komentar di forum diskusi mengandung kata-kata kasar.', '2025-06-11 16:20:08', 1, 5, 'Diproses'),
(7, 'Materi tidak relevan', 'Materi Java terlalu basic untuk kelas level menengah.', '2025-06-11 16:20:08', 2, 6, 'Selesai'),
(8, 'Materi tidak relevan', 'Tidak ada pembahasan tentang framework modern di kelas Web Dev.', '2025-06-11 16:20:08', 1, 8, 'Belum Diproses'),
(9, 'Penggunaan kata kasar', 'Mentor menggunakan analogi yang kurang sopan.', '2025-06-11 16:20:08', 3, 9, 'Ditolak'),
(10, 'Materi tidak relevan', 'Contoh kasus di kelas Ekonomi sudah usang.', '2025-06-11 16:20:08', 7, 10, 'Belum Diproses'),
(11, 'Materi tidak relevan', 'Materi cybersecurity terlalu singkat untuk kelas fundamentals.', '2025-06-14 04:00:00', 13, 38, 'Belum Diproses'),
(12, 'Penggunaan kata kasar', 'Ada penggunaan bahasa yang kurang profesional di forum diskusi.', '2025-06-15 03:30:00', 16, 31, 'Belum Diproses'),
(13, 'Materi tidak relevan', 'Studi kasus di kelas Project Management sudah usang.', '2025-06-16 02:00:00', 15, 33, 'Ditolak'),
(14, 'Pornografi', 'Ditemukan konten yang tidak pantas di salah satu sub materi Design.', '2025-06-17 07:00:00', 18, 34, 'Diproses');

-- --------------------------------------------------------

--
-- Table structure for table `tb_materi`
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

--
-- Dumping data for table `tb_materi`
--

INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `deskripsi_m`, `status`, `tgl_dibuat`) VALUES
(1, 1, 1, 'Introduction to HTML & CSS', 'Learn the building blocks of the web.', 'approved', '2025-06-11 16:18:34'),
(2, 2, 1, 'Setting Up Your Java Environment', 'Install JDK and your first IDE.', 'approved', '2025-06-11 16:18:34'),
(3, 3, 1, 'The Principles of Design', 'Understand the core concepts of good design.', 'approved', '2025-06-11 16:18:34'),
(4, 4, 1, 'Basic SELECT Statements', 'Learn how to query data from tables.', 'approved', '2025-06-11 16:18:34'),
(5, 5, 1, 'Introduction to Python and Data Types', 'Your first steps into Python programming.', 'approved', '2025-06-11 16:18:34'),
(7, 7, 1, 'Supply and Demand', 'The core of market economics.', 'approved', '2025-06-11 16:18:34'),
(8, 8, 1, 'History of Psychology', 'From Freud to modern cognitive science.', 'approved', '2025-06-11 16:18:34'),
(9, 9, 1, 'Computer Hardware and Software', 'Understand the components of a computer.', 'approved', '2025-06-11 16:18:34'),
(10, 10, 1, 'Introduction to Web Scraping', 'What is web scraping and why is it useful?', 'approved', '2025-06-11 16:18:34'),
(11, 11, 1, 'Introduction to Machine Learning', 'Basic concepts and types of machine learning.', 'approved', '2025-06-11 09:18:34'),
(12, 12, 1, 'Cloud Concepts', 'Understanding cloud computing models.', 'approved', '2025-06-11 09:18:34'),
(13, 13, 1, 'Threats and Vulnerabilities', 'Common cybersecurity threats.', 'approved', '2025-06-11 09:18:34'),
(14, 14, 1, 'Setting up Android Studio', 'Install and configure your Android development environment.', 'approved', '2025-06-11 09:18:34'),
(15, 15, 1, 'Introduction to Agile', 'What is Agile and its core values.', 'approved', '2025-06-11 09:18:34'),
(16, 16, 1, 'React JSX and Components', 'Building blocks of React applications.', 'approved', '2025-06-11 09:18:34'),
(17, 17, 1, 'Microservices Architecture', 'Understanding microservices and their benefits.', 'approved', '2025-06-11 09:18:34'),
(18, 18, 1, 'Figma Interface and Tools', 'Navigating the Figma design environment.', 'approved', '2025-06-11 09:18:34');

-- --------------------------------------------------------

--
-- Table structure for table `tb_mentor`
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

--
-- Dumping data for table `tb_mentor`
--

INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`, `keahlian`, `pengalaman`, `deskripsi`, `website_url`) VALUES
(1, 'Aktif', 1, 'Full-Stack Web Development', '10 Years at a Tech Unicorn', 'Specializes in React and Node.js.', 'https://budi.example.com'),
(2, 'Aktif', 2, 'Java, Python, Spring Boot', '8 Years as Software Engineer', 'Loves building scalable backend systems.', 'https://citra.example.com'),
(3, 'Aktif', 3, 'UI/UX Design, Figma, Adobe XD', 'Lead Designer at a Creative Agency', 'Focuses on user-centered design principles.', 'https://agus.example.com'),
(4, 'Non-Aktif', 4, 'Business Strategy, Market Analysis', '15 Years in Business Consulting', 'Helps startups grow and scale.', 'https://dewi.example.com'),
(5, 'Aktif', 1, 'Database Management', '5 years as DBA', 'Expert in SQL and NoSQL databases.', 'https://budi-db.example.com'),
(6, 'Aktif', 2, 'Data Science with Python', '6 years as Data Scientist', 'Focus on machine learning models.', 'https://citra-ds.example.com'),
(7, 'Non-Aktif', 3, 'Mobile App Design', '7 years in mobile design', 'Creates beautiful and intuitive mobile apps.', 'https://agus-mobile.example.com'),
(8, 'Aktif', 1, 'DevOps Engineering', '8 years in DevOps', 'Manages CI/CD pipelines and cloud infrastructure.', 'https://budi-devops.example.com'),
(9, 'Aktif', 2, 'API Development', '6 years as Backend Developer', 'Specializes in creating robust RESTful APIs.', 'https://citra-api.example.com'),
(10, 'Aktif', 3, 'Graphic Design', '9 years as Graphic Designer', 'Creates visually stunning marketing materials.', 'https://agus-gfx.example.com'),
(11, 'Aktif', 41, 'Data Science, Machine Learning, R', '7 Years in Data Analysis and ML Engineering', 'Passionate about making data-driven decisions.', 'https://wayan.example.com/ds'),
(12, 'Aktif', 1, 'Cloud Computing, DevOps', '9 Years as Cloud Architect', 'Specializes in AWS and Azure deployments.', 'https://budi.example.com/cloud'),
(13, 'Non-Aktif', 2, 'Cybersecurity, Network Security', '12 Years as Security Consultant', 'Helps organizations secure their digital assets.', 'https://citra.example.com/security'),
(14, 'Aktif', 3, 'Mobile App Development (Android/iOS)', '6 Years as Mobile Developer', 'Builds native and cross-platform mobile applications.', 'https://agus.example.com/mobile'),
(15, 'Aktif', 4, 'Project Management, Agile Methodologies', '10 Years as Project Manager', 'Expert in Scrum and Kanban frameworks.', 'https://dewi.example.com/pm');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembayaran`
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
-- Dumping data for table `tb_pembayaran`
--

INSERT INTO `tb_pembayaran` (`id_pembayaran`, `order_id`, `id_user`, `total_bayar`, `metode_bayar`, `nomor_va`, `bukti_transfer`, `tanggal_pembayaran`, `status`) VALUES
(0, 'ORDER001', 6, 199000.00, 'Bank Transfer', '1234567890123456', 'bukti_001.jpg', '2025-06-11 10:30:00', 'Approved'),
(0, 'ORDER002', 7, 499000.00, 'E-Wallet', NULL, 'bukti_002.jpg', '2025-06-11 11:15:00', 'Approved'),
(0, 'ORDER003', 8, 299000.00, 'Credit Card', NULL, 'bukti_003.jpg', '2025-06-11 14:20:00', 'Pending'),
(0, 'ORDER004', 9, 249000.00, 'Bank Transfer', '1234567890123457', 'bukti_004.jpg', '2025-06-10 16:45:00', 'Approved'),
(0, 'ORDER005', 10, 399000.00, 'E-Wallet', NULL, 'bukti_005.jpg', '2025-06-10 09:30:00', 'Approved'),
(1, 'ORD-20250611-001', 5, 1500000.00, 'bca_va', '12345678901', NULL, '2025-06-11 16:18:51', 'Approved'),
(2, 'ORD-20250611-002', 6, 950000.00, 'bni_va', '09876543210', NULL, '2025-06-11 16:18:51', 'Approved'),
(3, 'ORD-20250612-003', 7, 650000.00, 'gopay', NULL, 'bukti-gopay.jpg', '2025-06-11 16:18:51', 'Pending'),
(4, 'ORD-20250612-004', 8, 500000.00, 'bca_va', '12345678902', NULL, '2025-06-11 16:18:51', 'Approved'),
(5, 'ORD-20250613-005', 10, 1100000.00, 'manual_transfer', NULL, 'transfer-bca.jpg', '2025-06-11 16:18:51', 'Approved'),
(6, 'ORD-20250613-006', 5, 850000.00, 'gopay', NULL, 'gopay-eko.jpg', '2025-06-11 16:18:51', 'Rejected'),
(7, 'ORD-20250614-007', 8, 900000.00, 'bni_va', '09876543211', NULL, '2025-06-11 16:18:51', 'Pending'),
(8, 'ORD-20250614-008', 9, 1200000.00, 'manual_transfer', NULL, 'tf-indah.jpg', '2025-06-11 16:18:51', 'Pending'),
(9, 'ORD-20250615-009', 6, 1100000.00, 'bca_va', '12345678903', NULL, '2025-06-11 16:18:51', 'Approved'),
(10, 'ORD-20250615-010', 10, 1500000.00, 'gopay', NULL, 'joko-gopay.jpg', '2025-06-11 16:18:51', 'Approved'),
(11, 'ORD-20250614-011', 36, 1800000.00, 'bca_va', '12345678904', NULL, '2025-06-14 10:00:00', 'Approved'),
(12, 'ORD-20250614-012', 37, 1300000.00, 'gopay', NULL, 'gopay-susi.jpg', '2025-06-14 10:15:00', 'Approved'),
(13, 'ORD-20250614-013', 38, 1000000.00, 'manual_transfer', NULL, 'transfer-tono.jpg', '2025-06-14 10:30:00', 'Pending'),
(14, 'ORD-20250615-014', 39, 1600000.00, 'bni_va', '09876543212', NULL, '2025-06-15 09:00:00', 'Approved'),
(15, 'ORD-20250615-015', 40, 900000.00, 'bca_va', '12345678905', NULL, '2025-06-15 09:30:00', 'Approved'),
(16, 'ORD-20250615-016', 5, 1200000.00, 'gopay', NULL, 'gopay-eko2.jpg', '2025-06-15 11:00:00', 'Approved'),
(17, 'ORD-20250616-017', 6, 1700000.00, 'manual_transfer', NULL, 'tf-fitri.jpg', '2025-06-16 08:30:00', 'Pending'),
(18, 'ORD-20250616-018', 7, 1100000.00, 'bca_va', '12345678906', NULL, '2025-06-16 09:45:00', 'Approved'),
(19, 'ORD-20250616-019', 8, 1800000.00, 'gopay', NULL, 'gopay-hadi.jpg', '2025-06-16 10:30:00', 'Rejected'),
(20, 'ORD-20250617-020', 9, 1300000.00, 'manual_transfer', NULL, 'tf-indah2.jpg', '2025-06-17 11:00:00', 'Pending'),
(21, 'ORD-20250617-021', 10, 1600000.00, 'bni_va', '09876543213', NULL, '2025-06-17 12:00:00', 'Approved'),
(22, 'ORD-20250617-022', 30, 900000.00, 'gopay', NULL, 'gopay-lisa.jpg', '2025-06-17 13:00:00', 'Approved'),
(23, 'ORD-20250618-023', 31, 1200000.00, 'bca_va', '12345678907', NULL, '2025-06-18 09:00:00', 'Approved'),
(24, 'ORD-20250618-024', 32, 1700000.00, 'manual_transfer', NULL, 'tf-maya.jpg', '2025-06-18 10:00:00', 'Approved'),
(25, 'ORD-20250618-025', 33, 1100000.00, 'gopay', NULL, 'gopay-rudi.jpg', '2025-06-18 11:00:00', 'Pending');

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

--
-- Dumping data for table `tb_progress_kelas`
--

INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(1, 1, 5, 1),
(2, 4, 6, 4),
(3, 9, 8, 9),
(4, 1, 10, 1),
(5, 2, 5, 2),
(6, 8, 7, 8),
(7, 10, 8, 10),
(8, 5, 6, 5),
(9, 4, 10, 4),
(10, 3, 9, 3),
(11, 11, 36, 11),
(12, 12, 37, 12),
(13, 14, 39, 14),
(14, 15, 40, 15),
(15, 16, 5, 16),
(16, 18, 7, 18),
(17, 14, 10, 14),
(18, 15, 30, 15),
(19, 16, 31, 16),
(20, 17, 32, 17);

-- --------------------------------------------------------

--
-- Table structure for table `tb_review`
--

CREATE TABLE `tb_review` (
  `id_review` int(30) NOT NULL,
  `bintang_review` enum('1','2','3','4','5') NOT NULL,
  `isi_review` varchar(100) NOT NULL,
  `tgl_review` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_user` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_review`
--

INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(2, '4', 'Mentor sangat responsif dan helpful. Materi cukup challenging tapi worth it.', '2025-06-11 16:12:52', 7, 2),
(3, '5', 'Highly recommended! Setelah ikut kelas ini skill saya meningkat drastis.', '2025-06-11 16:12:52', 9, 4),
(4, '4', 'Kelas yang praktis dan applicable. Bisa langsung dipraktekkan di dunia kerja.', '2025-06-11 16:12:52', 10, 7),
(5, '5', 'Excellent course! Mentor berpengalaman dan materi up-to-date.', '2025-06-11 16:12:52', 6, 1),
(6, '5', 'Materi Machine Learning sangat lengkap dan mudah dipahami.', '2025-06-14 05:00:00', 36, 11),
(7, '4', 'Kelas AWS sangat membantu persiapan sertifikasi saya.', '2025-06-14 06:00:00', 37, 12),
(8, '5', 'Sangat senang dengan kelas Android, sekarang bisa buat aplikasi sendiri.', '2025-06-15 03:00:00', 39, 14),
(9, '4', 'Konsep Agile dijelaskan dengan sangat baik.', '2025-06-15 04:00:00', 40, 15),
(10, '5', 'React JS kelasnya mantap, mentornya asik!', '2025-06-15 07:00:00', 5, 16);

-- --------------------------------------------------------

--
-- Table structure for table `tb_sub_materi`
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

--
-- Dumping data for table `tb_sub_materi`
--

INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `tgl_dibuat`) VALUES
(1, 1, 1, 1, 1, 'Your First HTML Page', '2025-06-11 16:18:41'),
(2, 2, 2, 2, 1, 'Compiling and Running Your First Program', '2025-06-11 16:18:41'),
(3, 3, 3, 3, 1, 'Working with Colors and Typography', '2025-06-11 16:18:41'),
(4, 4, 4, 4, 1, 'Using the WHERE clause', '2025-06-11 16:18:41'),
(5, 5, 5, 5, 1, 'Variables and Basic Operators', '2025-06-11 16:18:41'),
(7, 7, 7, 7, 1, 'Understanding The Demand Curve', '2025-06-11 16:18:41'),
(8, 8, 8, 8, 1, 'What are Cognitive Biases?', '2025-06-11 16:18:41'),
(9, 9, 9, 9, 1, 'Inside a PC', '2025-06-11 16:18:41'),
(10, 10, 10, 10, 1, 'Using Beautiful Soup Library', '2025-06-11 16:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int(30) NOT NULL,
  `id_kelas` int(30) NOT NULL,
  `id_user` int(30) NOT NULL,
  `id_keranjang` int(30) NOT NULL,
  `bukti_transaksi` varchar(200) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `status` enum('Completed','Pending','Cancelled') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(1, 1, 5, 1, 'buktitf.jpg', '2025-06-11', 'Completed'),
(2, 4, 6, 3, 'buktitf.jpg', '2025-06-11', 'Completed'),
(3, 8, 7, 5, 'buktitf.jpg', '2025-06-12', 'Completed'),
(4, 9, 8, 6, 'buktitf.jpg', '2025-06-12', 'Completed'),
(5, 3, 9, 8, 'buktitf.jpg', '2025-06-13', 'Completed'),
(6, 1, 10, 9, 'buktitf.jpg', '2025-06-13', 'Completed'),
(7, 5, 10, 10, 'buktitf.jpg', '2025-06-14', 'Completed'),
(8, 2, 5, 2, 'buktitf.jpg', '2025-06-14', 'Completed'),
(9, 5, 6, 4, 'buktitf.jpg', '2025-06-14', 'Completed'),
(10, 10, 8, 7, 'buktitf.jpg', '2025-06-15', 'Completed'),
(11, 11, 36, 11, 'buktitf.jpg', '2025-06-14', 'Completed'),
(12, 12, 37, 12, 'buktitf.jpg', '2025-06-14', 'Completed'),
(13, 14, 39, 14, 'buktitf.jpg', '2025-06-15', 'Completed'),
(14, 15, 40, 15, 'buktitf.jpg', '2025-06-15', 'Completed'),
(15, 16, 5, 16, 'buktitf.jpg', '2025-06-15', 'Completed'),
(16, 18, 7, 18, 'buktitf.jpg', '2025-06-16', 'Completed'),
(17, 14, 10, 21, 'buktitf.jpg', '2025-06-17', 'Completed'),
(18, 15, 30, 22, 'buktitf.jpg', '2025-06-17', 'Completed'),
(19, 16, 31, 23, 'buktitf.jpg', '2025-06-18', 'Completed'),
(20, 17, 32, 24, 'buktitf.jpg', '2025-06-18', 'Completed');

-- --------------------------------------------------------

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

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `no_telepon`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(1, 'Budi', 'Santoso', 'budimentor', 1234567890, 'password123', 'mentor', 'aktif', 'Expert in Web Development.', 'profil1.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'budi.s@example.com', 'budis', 'budis', 'budis', 'budis', '2025-06-11 10:00:00'),
(2, 'Citra', 'Wijaya', 'citrajava', 1234567891, 'password123', 'mentor', 'aktif', 'Java and Python specialist.', 'profil2.jpg', 'Inggris', 'Jakarta', 1, 'citra.w@example.com', 'citraw', 'citraw', 'citraw', 'citraw', '2025-06-11 10:05:00'),
(3, 'Agus', 'Prasetyo', 'agusdesain', 1234567892, 'password123', 'mentor', 'aktif', 'UI/UX Design Lead with 10 years experience.', 'profil3.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 'agus.p@example.com', 'agusp', 'agusp', 'agusp', 'agusp', '2025-06-11 10:10:00'),
(4, 'Dewi', 'Lestari', 'dewibisnis', 1234567893, 'password123', 'mentor', 'non-aktif', 'Business and Economic Analyst.', 'profil4.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'dewi.l@example.com', 'dewil', 'dewil', 'dewil', 'dewil', '2025-06-11 10:15:00'),
(5, 'Eko', 'Nugroho', 'ekomurid', 1234567894, 'password123', 'murid', 'aktif', 'Student learning web development.', 'profil5.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'eko.n@example.com', 'ekon', 'ekon', 'ekon', 'ekon', '2025-06-11 10:20:00'),
(6, 'Fitri', 'Handayani', 'fitrih', 1234567895, 'password123', 'murid', 'aktif', 'Aspiring data scientist.', 'profil6.jpg', 'Inggris', 'London', 1, 'fitri.h@example.com', 'fitrih', 'fitrih', 'fitrih', 'fitrih', '2025-06-11 10:25:00'),
(7, 'Gita', 'Permata', 'gitap', 1234567896, 'password123', 'murid', 'aktif', 'Psychology student.', 'profil7.jpg', 'Jepang', 'Tokyo', 0, 'gita.p@example.com', 'gitap', 'gitap', 'gitap', 'gitap', '2025-06-11 10:30:00'),
(8, 'Hadi', 'Kusuma', 'hadik', 1234567897, 'password123', 'murid', 'aktif', 'Interested in digital marketing and business.', 'profil8.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'hadi.k@example.com', 'hadik', 'hadik', 'hadik', 'hadik', '2025-06-11 10:35:00'),
(9, 'Indah', 'Cahyani', 'indahc', 1234567898, 'password123', 'murid', 'non-aktif', 'Learning design from scratch.', 'profil9.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 'indah.c@example.com', 'indahc', 'indahc', 'indahc', 'indahc', '2025-06-11 10:40:00'),
(10, 'Joko', 'Susilo', 'jokos', 1234567899, 'password123', 'murid', 'aktif', 'IT support staff looking to upskill.', 'profil10.jpg', 'Inggris', 'London', 1, 'joko.s@example.com', 'jokos', 'jokos', 'jokos', 'jokos', '2025-06-11 10:45:00'),
(25, 'Ahmad', 'Rizki', 'ahmadrizki', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'aktif', 'Administrator sistem pembelajaran online', 'admin1.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'ahmad.rizki@kelaskita.com', 'ahmadrizki_', 'ahmad_rizki', 'ahmad-rizki', 'ahmadrizki', '2025-06-11 16:12:51'),
(26, 'Sari', 'Dewi', 'saridewi', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mentor', 'aktif', 'Mentor programming dengan pengalaman 5 tahun', 'mentor1.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'sari.dewi@email.com', 'saridewi_dev', 'sari_dewi', 'sari-dewi', 'saridewi', '2025-06-11 16:12:51'),
(27, 'Budi', 'Santoso', 'budisantoso', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mentor', 'aktif', 'Expert dalam web development dan database', 'mentor2.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'budi.santoso@email.com', 'budisantoso_web', 'budi_santoso', 'budi-santoso', 'budisantoso', '2025-06-11 16:12:51'),
(28, 'Rina', 'Sari', 'rinasari', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mentor', 'non-aktif', 'UI/UX Designer dengan pengalaman 4 tahun', 'mentor3.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'rina.sari@email.com', 'rinasari_design', 'rina_sari', 'rina-sari', 'rinasari', '2025-06-11 16:12:51'),
(29, 'Doni', 'Pratama', 'donipratama', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mentor', 'non-aktif', 'Business analyst dan startup consultant', 'mentor4.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'doni.pratama@email.com', 'donipratama_biz', 'doni_pratama', 'doni-pratama', 'donipratama', '2025-06-11 16:12:51'),
(30, 'Lisa', 'Wati', 'lisawati', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid', 'aktif', 'Mahasiswa teknik informatika', 'student1.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'lisa.wati@email.com', 'lisawati_', 'lisa_wati', 'lisa-wati', 'lisawati', '2025-06-11 16:12:51'),
(31, 'Agus', 'Setiawan', 'agussetiawan', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid', 'aktif', 'Fresh graduate yang ingin belajar programming', 'student2.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'agus.setiawan@email.com', 'agussetiawan_', 'agus_setiawan', 'agus-setiawan', 'agussetiawan', '2025-06-11 16:12:51'),
(32, 'Maya', 'Sinta', 'mayasinta', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid', 'aktif', 'Freelancer yang ingin upgrade skill', 'student3.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'maya.sinta@email.com', 'mayasinta_', 'maya_sinta', 'maya-sinta', 'mayasinta', '2025-06-11 16:12:51'),
(33, 'Rudi', 'Hermawan', 'rudihermawan', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid', 'aktif', 'Karyawan yang ingin career switch', 'student4.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'rudi.hermawan@email.com', 'rudihermawan_', 'rudi_hermawan', 'rudi-hermawan', 'rudihermawan', '2025-06-11 16:12:51'),
(34, 'Nina', 'Astuti', 'ninaastuti', 2147483647, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid', 'aktif', 'Entrepreneur yang ingin belajar teknologi', 'student5.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'nina.astuti@email.com', 'ninaastuti_', 'nina_astuti', 'nina-astuti', 'ninaastuti', '2025-06-11 16:12:51'),
(35, '', '', 'Shafnat', 0, '$2y$10$PZRlVGWZZaeICWIdrSQ38uBih3OekjOpvRTLXiT8CtQIHQtQvChaW', 'admin', 'aktif', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, '', '', '', '', '', '2025-06-11 18:01:57'),
(36, 'Rio', 'Saputra', 'riosaputra', 812345678, 'password123', 'murid', 'aktif', 'New student interested in IT.', 'profil11.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'rio.s@example.com', 'riosaputra', 'riosaputra', 'riosaputra', 'riosaputra', '2025-06-11 04:00:00'),
(37, 'Susi', 'Wijoyo', 'susiw', 812345679, 'password123', 'murid', 'aktif', 'Learning design for her startup.', 'profil12.jpg', 'Inggris', 'Jakarta', 0, 'susi.w@example.com', 'susiw', 'susiw', 'susiw', 'susiw', '2025-06-11 04:05:00'),
(38, 'Tono', 'Santoso', 'tonos', 812345680, 'password123', 'murid', 'non-aktif', 'Exploring Python for hobby projects.', 'profil13.jpg', 'Bahasa Indonesia', 'London', 1, 'tono.s@example.com', 'tonos', 'tonos', 'tonos', 'tonos', '2025-06-11 04:10:00'),
(39, 'Ulya', 'Rahmawati', 'ulyar', 812345681, 'password123', 'murid', 'aktif', 'Interested in economics and business.', 'profil14.jpg', 'Inggris', 'Tokyo', 1, 'ulya.r@example.com', 'ulyar', 'ulyar', 'ulyar', 'ulyar', '2025-06-11 04:15:00'),
(40, 'Vina', 'Nuraini', 'vinan', 812345682, 'password123', 'murid', 'aktif', 'New to programming, starting with Java.', 'profil15.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 'vina.n@example.com', 'vinan', 'vinan', 'vinan', 'vinan', '2025-06-11 04:20:00'),
(41, 'Wayan', 'Putra', 'wayanp', 812345683, 'password123', 'mentor', 'aktif', 'Experienced in Data Science and Machine Learning.', 'profil16.jpg', 'Inggris', 'Jakarta', 1, 'wayan.p@example.com', 'wayanp', 'wayanp', 'wayanp', 'wayanp', '2025-06-11 04:25:00'),
(42, 'Xenia', 'Dewi', 'xeniad', 812345684, 'password123', 'admin', 'aktif', 'Platform administrator.', 'profil17.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 'xenia.d@example.com', 'xeniad', 'xeniad', 'xeniad', 'xeniad', '2025-06-11 04:30:00'),
(43, '', '', 'Shafnatt', 0, '$2y$10$zUS9Ul5X.DbAI5gsE4T/keg42VjbIqDjQQOlOq2/0PW5pqAVc9TE2', 'murid', 'non-aktif', NULL, '', 'Bahasa Indonesia', 'Jakarta', 0, '', '', '', '', '', '2025-06-12 00:19:00');

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
(1, '/videos/webdev_intro.mp4'),
(2, '/videos/java_setup.mp4'),
(3, '/videos/figma_tutorial.mp4'),
(4, '/videos/select_statement.mp4'),
(5, '/videos/python_variables.mp4'),
(6, '/videos/market_research.mp4'),
(7, '/videos/microeconomics.mp4'),
(8, '/videos/cognitive_biases.mp4'),
(9, '/videos/troubleshooting.mp4'),
(10, '/videos/joins_explained.mp4'),
(25, '/uploads/videos/python_intro.mp4'),
(26, '/uploads/videos/django_setup.mp4'),
(27, '/uploads/videos/mysql_basics.mp4'),
(28, '/uploads/videos/laravel_installation.mp4'),
(29, '/uploads/videos/figma_interface.mp4'),
(30, '/uploads/videos/ui_principles.mp4'),
(31, '/uploads/videos/business_model_canvas.mp4'),
(32, '/uploads/videos/startup_pitching.mp4'),
(33, '/uploads/videos/java_variables.mp4'),
(34, '/uploads/videos/html_css_basics.mp4'),
(35, '/uploads/videos/sql_joins.mp4'),
(36, '/uploads/videos/psychology_intro.mp4'),
(37, '/uploads/videos/economic_principles.mp4'),
(38, '/uploads/videos/network_fundamentals.mp4'),
(39, '/uploads/videos/python_functions.mp4'),
(40, '/uploads/videos/django_models.mp4'),
(41, '/uploads/videos/mysql_optimization.mp4'),
(42, '/uploads/videos/laravel_routing.mp4'),
(43, '/uploads/videos/adobe_xd_tutorial.mp4'),
(44, '/uploads/videos/user_research.mp4'),
(45, '/uploads/videos/market_analysis.mp4'),
(46, '/uploads/videos/financial_planning.mp4'),
(47, '/uploads/videos/java_oop.mp4'),
(48, '/uploads/videos/javascript_dom.mp4'),
(49, '/videos/seo_basics.mp4'),
(50, '/videos/neural_networks.mp4'),
(51, '/videos/cryptocurrency_explained.mp4'),
(52, '/videos/aws_fundamentals.mp4'),
(53, '/videos/ethical_hacking.mp4'),
(54, '/videos/tableau_tutorial.mp4'),
(55, '/videos/supervised_learning.mp4'),
(56, '/videos/android_studio_intro.mp4'),
(57, '/videos/xcode_basics.mp4'),
(58, '/videos/firewall_configuration.mp4'),
(59, '/videos/scrum_master.mp4'),
(60, '/videos/r_programming.mp4'),
(61, '/videos/xss_attacks.mp4'),
(62, '/videos/react_components.mp4'),
(63, '/videos/nodejs_express.mp4'),
(64, '/videos/docker_containers.mp4'),
(65, '/videos/kanban_board.mp4'),
(66, '/videos/social_media_marketing.mp4'),
(67, '/videos/balance_sheet.mp4'),
(68, '/videos/recruitment_process.mp4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_catatan`
--
ALTER TABLE `tb_catatan`
  ADD PRIMARY KEY (`id_catatan`),
  ADD KEY `fkid_mentor_pk_catatan` (`id_mentor`),
  ADD KEY `fkid_kelas_pk_catatan` (`id_kelas`);

--
-- Indexes for table `tb_dokumen`
--
ALTER TABLE `tb_dokumen`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `fk_kelas_mentor` (`id_mentor`);

--
-- Indexes for table `tb_keranjang`
--
ALTER TABLE `tb_keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `fkid_user_keranjang` (`id_user`),
  ADD KEY `fkid_kelas_keranjang` (`id_kelas`);

--
-- Indexes for table `tb_laporan`
--
ALTER TABLE `tb_laporan`
  ADD PRIMARY KEY (`id_report`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_user` (`id_user`);

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
-- AUTO_INCREMENT for table `tb_catatan`
--
ALTER TABLE `tb_catatan`
  MODIFY `id_catatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_dokumen`
--
ALTER TABLE `tb_dokumen`
  MODIFY `id_dokumen` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tb_laporan`
--
ALTER TABLE `tb_laporan`
  MODIFY `id_report` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_materi`
--
ALTER TABLE `tb_materi`
  MODIFY `id_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tb_mentor`
--
ALTER TABLE `tb_mentor`
  MODIFY `id_mentor` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tb_review`
--
ALTER TABLE `tb_review`
  MODIFY `id_review` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_sub_materi`
--
ALTER TABLE `tb_sub_materi`
  MODIFY `id_sub_materi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tb_video`
--
ALTER TABLE `tb_video`
  MODIFY `id_video` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_catatan`
--
ALTER TABLE `tb_catatan`
  ADD CONSTRAINT `fkid_kelas_pk_catatan` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`),
  ADD CONSTRAINT `fkid_mentor_pk_catatan` FOREIGN KEY (`id_mentor`) REFERENCES `tb_mentor` (`id_mentor`);

--
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
--
ALTER TABLE `tb_materi`
  ADD CONSTRAINT `fkid_kelas_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE;

--
-- Constraints for table `tb_mentor`
--
ALTER TABLE `tb_mentor`
  ADD CONSTRAINT `fkid_user_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_progress_kelas`
--
ALTER TABLE `tb_progress_kelas`
  ADD CONSTRAINT `tb_progress_kelas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `tb_progress_kelas_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`),
  ADD CONSTRAINT `tb_progress_kelas_ibfk_3` FOREIGN KEY (`id_materi`) REFERENCES `tb_materi` (`id_materi`);

--
-- Constraints for table `tb_sub_materi`
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
