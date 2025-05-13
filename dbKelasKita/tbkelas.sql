-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 08:17 AM
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
-- Database: `kelaskita`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbkelas`
--

CREATE TABLE `tbkelas` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `kategori` enum('SQL','Design','Java','Web Development','Bisnis','Ekonomi','Psikologi','IT','Python') NOT NULL,
  `instructor` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `reviews` int(11) DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `materials` text DEFAULT NULL,
  `course_type` enum('on_demand','scheduled') DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `visitor_count` int(11) DEFAULT 0,
  `participant_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbkelas`
--

INSERT INTO `tbkelas` (`id`, `title`, `kategori`, `instructor`, `price`, `original_price`, `rating`, `reviews`, `tag`, `image`, `badge`, `description`, `materials`, `course_type`, `start_date`, `end_date`, `visitor_count`, `participant_count`) VALUES
(1, 'Digital Marketing Masterclass', 'SQL', 'John Smith', 79.99, 129.99, 4.8, 1275, 'BEST SELLER', 'assets/images/course1.jpg', 'HOT', NULL, NULL, NULL, NULL, NULL, 0, 0),
(2, 'Website Development', '', 'Aliq Nur Shiddiq', 50000.00, 60000.00, 0.0, 0, '0', '../upload/FNj6V7UacAAeV9Y.jpg', 'New', NULL, NULL, NULL, NULL, NULL, 0, 0),
(11, 'Website Programming', 'Web Development', 'Aliq', 10000.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Kelas Website Programming', '../upload/Bukti share informasi.docx,../upload/Bukti share informasi.pdf,../upload/Buku Panduan BM 8.pdf', 'on_demand', '0000-00-00', '0000-00-00', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbkelas`
--
ALTER TABLE `tbkelas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbkelas`
--
ALTER TABLE `tbkelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
