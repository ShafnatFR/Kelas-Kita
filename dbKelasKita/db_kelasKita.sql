-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 07:15 AM
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
-- Database: `kelaskita`
--

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

INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `deskripsi`, `fotoProfil`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`) VALUES
(5, '', '', 'Murid', '$2y$10$HKBEA1/qQyxcBoNa3xT4quUsUWHv4hBaNrXP6pAZnvtjzAhyu1BiG', 'murid', NULL, '', 0, 0, 0, '', '', '', '', ''),
(6, '', '', 'Mentor', '$2y$10$CX9KqmS8jtnx2ncX1A4D/.VBRfjbgAzou8VYdTb.ZnGoUQ3Xn9LBC', 'mentor', NULL, '', 0, 0, 0, '', '', '', '', ''),
(7, '', '', 'Admin', '$2y$10$Neozo9JGhh6JZb2wR42Rv.A5m6eMZrdos7TubtspxEtfw.MPpydzm', 'admin', NULL, '', 0, 0, 0, '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
