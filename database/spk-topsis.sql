-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2025 at 02:41 PM
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
-- Database: `spk-topsis`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `alternatif` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `alternatif`) VALUES
(21, 'Oatmeal'),
(22, 'Bubur Beras'),
(23, 'Ikan Salmon'),
(24, 'Bayam kukus'),
(25, 'Dada ayam rebus'),
(26, 'Telur rebus'),
(27, 'Telur dadar'),
(28, 'Bayam Tumis'),
(29, 'Wortel rebus'),
(30, 'Labu Siam tumis'),
(31, 'Bayam rebus'),
(32, 'Brokoli Kukus'),
(33, 'Kacang panjang rebus'),
(34, 'Bubur Kacang hijau'),
(35, 'Buncis rebus'),
(36, 'Kembang Tahu rebus'),
(37, 'Tempe orek'),
(38, 'Sup Sayur bening'),
(39, 'Sup Ayam'),
(40, 'Sup Kentang'),
(41, 'Bubur jagung'),
(42, 'Seblak kuah'),
(43, 'Pasta gandum'),
(44, 'Mie Gandum'),
(45, 'Pasta Gandum Utuh Kering'),
(46, 'Quinoa'),
(47, 'Salad Sayuran'),
(48, 'Tahu goreng'),
(49, 'Tempe kukus'),
(50, 'Kentang Rebus'),
(51, 'Tumis Sawi Putih'),
(52, 'Omelet Mie'),
(53, 'Ikan Kembung'),
(54, 'Ayam Panggang'),
(55, 'Ikan Tenggiri'),
(56, 'Bubur Ayam'),
(57, 'Kentang tumbuk (mashed potato)'),
(58, 'Mie Goreng'),
(59, 'Mie Instan'),
(60, 'Soto Ayam'),
(61, 'Ayam Goreng Kalasan, Paha'),
(62, 'Ayam Gulai'),
(63, 'Ayam Rendang'),
(64, 'Ampela Ayam'),
(65, 'Sate Ayam'),
(66, 'Ikan Mujahir Goreng'),
(67, 'Ayam Bakar'),
(68, 'Lele Goreng'),
(69, 'Mie Ayam'),
(70, 'Sop Buntut');

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

CREATE TABLE `hasil` (
  `id_hasil` int(11) NOT NULL,
  `kode_hasil` varchar(255) DEFAULT NULL,
  `id_alternatif` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `kriteria` varchar(50) NOT NULL,
  `type` enum('Benefit','Cost') NOT NULL,
  `bobot` float NOT NULL,
  `ada_pilihan` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `kriteria`, `type`, `bobot`, `ada_pilihan`) VALUES
(1, 'C1', 'Protein', 'Benefit', 4, 1),
(2, 'C2', 'Karbohidrat', 'Benefit', 3, 1),
(3, 'C3', 'Lemak', 'Cost', 5, 1),
(4, 'C4', 'Serat', 'Benefit', 4, 1),
(5, 'C5', 'Kalori', 'Benefit', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id_alternatif` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(216, 21, 1, 3),
(217, 21, 2, 6),
(218, 21, 3, 9),
(219, 21, 4, 11),
(220, 21, 5, 15),
(221, 23, 1, 1),
(222, 23, 2, 6),
(223, 23, 3, 7),
(224, 23, 4, 12),
(225, 23, 5, 14),
(226, 25, 1, 1),
(227, 25, 2, 6),
(228, 25, 3, 9),
(229, 25, 4, 12),
(230, 25, 5, 14),
(231, 28, 1, 3),
(232, 28, 2, 6),
(233, 28, 3, 9),
(234, 28, 4, 11),
(235, 28, 5, 15),
(236, 32, 1, 3),
(237, 32, 2, 6),
(238, 32, 3, 9),
(239, 32, 4, 11),
(240, 32, 5, 15),
(241, 37, 1, 2),
(242, 37, 2, 6),
(243, 37, 3, 8),
(244, 37, 4, 11),
(245, 37, 5, 14),
(246, 34, 1, 3),
(247, 34, 2, 5),
(248, 34, 3, 9),
(249, 34, 4, 11),
(250, 34, 5, 15),
(251, 46, 1, 2),
(252, 46, 2, 4),
(253, 46, 3, 8),
(254, 46, 4, 10),
(255, 46, 5, 13),
(256, 53, 1, 1),
(257, 53, 2, 6),
(258, 53, 3, 8),
(259, 53, 4, 12),
(260, 53, 5, 14),
(261, 61, 1, 1),
(262, 61, 2, 6),
(263, 61, 3, 7),
(264, 61, 4, 12),
(265, 61, 5, 13);

-- --------------------------------------------------------

--
-- Table structure for table `permintaan_menu`
--

CREATE TABLE `permintaan_menu` (
  `id` int(11) NOT NULL,
  `menu` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_sub_kriteria` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `sub_kriteria` varchar(50) NOT NULL,
  `min_value` float NOT NULL,
  `max_value` float NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub_kriteria`, `id_kriteria`, `sub_kriteria`, `min_value`, `max_value`, `nilai`) VALUES
(1, 1, 'Tinggi', 15.01, 99999, 5),
(2, 1, 'Sedang', 10, 15, 3),
(3, 1, 'Rendah', 0, 9.99, 1),
(4, 2, 'Tinggi', 30.01, 99999, 5),
(5, 2, 'Sedang', 20, 30, 3),
(6, 2, 'Rendah', 0, 19.99, 1),
(7, 3, 'Tinggi', 10.01, 99999, 1),
(8, 3, 'Sedang', 5, 10, 3),
(9, 3, 'Rendah', 0, 4.99, 5),
(10, 4, 'Tinggi', 3.01, 99999, 5),
(11, 4, 'Sedang', 1, 3, 3),
(12, 4, 'Rendah', 0, 0.99, 1),
(13, 5, 'Tinggi', 250.01, 99999, 5),
(14, 5, 'Sedang', 150, 250, 3),
(15, 5, 'Rendah', 0, 149.99, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(5) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(70) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `role` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `email`, `role`) VALUES
(16, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin', 'admin@gmail.com', '1'),
(18, 'jesica', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'jesica', 'jesica@gmail.com', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `fk_hasil` (`id_alternatif`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `fk_penilaian_alternatif` (`id_alternatif`),
  ADD KEY `fk_penilaian_kriteria` (`id_kriteria`);

--
-- Indexes for table `permintaan_menu`
--
ALTER TABLE `permintaan_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `fk_sub_kriteria_id_kriteria` (`id_kriteria`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

--
-- AUTO_INCREMENT for table `permintaan_menu`
--
ALTER TABLE `permintaan_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil`
--
ALTER TABLE `hasil`
  ADD CONSTRAINT `fk_hasil` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `fk_penilaian_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`),
  ADD CONSTRAINT `fk_penilaian_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD CONSTRAINT `fk_sub_kriteria_id_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
