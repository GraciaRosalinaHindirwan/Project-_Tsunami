-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 03:12 AM
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
-- Database: `tsunami`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', 'capt123', 'AY AY CAPTAIN',
 2, 'akbar', 'akbar123', 'Akbar Maulana Setiawan',
 3, 'damar', 'damar123', 'Damar Nugroho',
 4, 'yefa', 'yefa123', 'Yefa Qihan ',
 5, 'gracia', 'gracia123', 'Gracia Rosalina Hindirwan',
 5, 'okta', 'okta123', 'Oktafiani');

-- --------------------------------------------------------

--
-- Table structure for table `gempa_terkini`
--

CREATE TABLE `gempa_terkini` (
  `id` int(11) NOT NULL,
  `tanggal` varchar(50) NOT NULL,
  `jam` varchar(20) NOT NULL,
  `lintang` varchar(20) NOT NULL,
  `bujur` varchar(20) NOT NULL,
  `magnitude` varchar(10) NOT NULL,
  `kedalaman` varchar(20) NOT NULL,
  `wilayah` varchar(255) NOT NULL,
  `potensi` varchar(255) NOT NULL,
  `dirasakan` varchar(255) NOT NULL,
  `shakemap` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gempa_terkini`
--

INSERT INTO `gempa_terkini` (`id`, `tanggal`, `jam`, `lintang`, `bujur`, `magnitude`, `kedalaman`, `wilayah`, `potensi`, `dirasakan`, `shakemap`, `created_at`) VALUES
(0, '05 Nov 2025', '06:32:24 WIB', '0.11 LS', '123.13 BT', '6.2', '103 km', 'Pusat gempa berada di laut 71 km barat daya Bone Bolango', 'Gempa ini dirasakan untuk diteruskan pada masyarakat', 'III-IV Bone Bolango, III-IV Luwuk, III Kab. Gorontalo Utara, III Kab. Boalemo, III Ampana, III Taliabu, III Bolaang Mongodow Utara, III Bolaang Mongodow, III Banggai Kepulauan, III Banggai Laut, III Kota Gorontalo, II-III Kab. Pohuwato, II-III Tondano, II', '20251105063224.mmi.jpg', '2025-11-05 06:06:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gempa_terkini`
--
ALTER TABLE `gempa_terkini`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
