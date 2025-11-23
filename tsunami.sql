-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2025 at 09:03 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

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
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', 'capt123', 'AY AY CAPTAIN'),
(2, 'akbar', 'akbar123', 'Akbar Maulana Setiawan'),
(3, 'damar', 'damar123', 'Damar Nugroho'),
(4, 'yefa', 'yefa123', 'Yefa Qihan '),
(5, 'gracia', 'gracia123', 'Gracia Rosalina Hindirwan'),
(6, 'okta', 'okta123', 'Oktafiani Nur Widayati');

-- --------------------------------------------------------

--
-- Table structure for table `data_gempa`
--

CREATE TABLE `data_gempa` (
  `id` int NOT NULL,
  `tanggal_jam` datetime NOT NULL,
  `tanggal_text` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jam_text` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `magnitude` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kedalaman` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wilayah` text COLLATE utf8mb4_general_ci,
  `lintang` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bujur` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `potensi` text COLLATE utf8mb4_general_ci,
  `dirasakan` text COLLATE utf8mb4_general_ci,
  `shakemap` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_gempa`
--

INSERT INTO `data_gempa` (`id`, `tanggal_jam`, `tanggal_text`, `jam_text`, `magnitude`, `kedalaman`, `wilayah`, `lintang`, `bujur`, `potensi`, `dirasakan`, `shakemap`, `created_at`) VALUES
(1, '2025-11-17 19:12:37', '17 Nov 2025', '19:12:37 WIB', '6.2', '105 km', '49 km Tenggara BOLAANGUKI-BOLSEL-SULUT', '0.04 LS', '124.15 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(2, '2025-11-15 12:33:29', '15 Nov 2025', '12:33:29 WIB', '5.4', '10 km', '18 km TimurLaut MELONGUANE-SULUT', '4.12 LU', '126.79 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(3, '2025-11-13 10:31:03', '13 Nov 2025', '10:31:03 WIB', '5.2', '122 km', '106 km Tenggara PULAUKARATUNG-SULUT', '4.18 LU', '127.84 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(4, '2025-11-12 04:23:32', '12 Nov 2025', '04:23:32 WIB', '5.5', '10 km', '60 km BaratLaut KEP.ARU-MALUKU', '5.30 LS', '133.93 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(5, '2025-11-11 02:14:13', '11 Nov 2025', '02:14:13 WIB', '5.2', '191 km', '213 km BaratLaut TANIMBAR', '6.41 LS', '130.18 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(6, '2025-11-05 06:32:24', '05 Nov 2025', '06:32:24 WIB', '6.2', '103 km', '71 km BaratDaya BONEBOLANGO-GORONTALO', '0.11 LS', '123.13 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(7, '2025-11-04 22:58:26', '04 Nov 2025', '22:58:26 WIB', '5.1', '20 km', '83 km BaratLaut HALMAHERABARAT-MALUT', '1.88 LU', '127.06 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(8, '2025-11-03 11:21:24', '03 Nov 2025', '11:21:24 WIB', '5.3', '10 km', '167 km BaratLaut TAHUNA-KEP.SANGIHE-SULUT', '5.11 LU', '125.37 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(9, '2025-11-02 10:09:21', '02 Nov 2025', '10:09:21 WIB', '5.5', '139 km', '40 km TimurLaut MALUKUBRTDAYA', '7.79 LS', '127.81 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(10, '2025-11-01 11:32:33', '01 Nov 2025', '11:32:33 WIB', '5.3', '10 km', '80 km TimurLaut SARMI-PAPUA', '1.64 LS', '139.44 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(11, '2025-11-01 06:20:57', '01 Nov 2025', '06:20:57 WIB', '5.0', '10 km', '128 km Tenggara KAIMANA-PAPUABRT', '4.44 LS', '134.07 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(12, '2025-11-01 00:02:21', '01 Nov 2025', '00:02:21 WIB', '5.1', '55 km', '71 km Tenggara MALUKUTENGGARA', '6.11 LS', '133.20 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(13, '2025-10-30 15:04:02', '30 Okt 2025', '15:04:02 WIB', '5.3', '52 km', '107 km BaratDaya KAB-JAYAPURA-PAPUA', '2.68 LS', '139.44 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(14, '2025-10-28 21:40:18', '28 Okt 2025', '21:40:18 WIB', '6.8', '185 km', '183 km BaratLaut TANIMBAR', '6.81 LS', '130.13 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(15, '2025-10-28 08:31:20', '28 Okt 2025', '08:31:20 WIB', '5.5', '10 km', '68 km TimurLaut BUOL-SULTENG', '1.43 LU', '121.77 BT', 'Tidak berpotensi tsunami', NULL, NULL, '2025-11-19 02:55:54'),
(61, '2025-11-23 12:57:42', '23 Nov 2025', '12:57:42 WIB', '3.9', '29 km', 'Pusat gempa berada di laut 87 km Barat Daya Sumbawa Barat', '9.47 LS', '116.53 BT', 'Gempa ini dirasakan untuk diteruskan pada masyarakat', 'II Sumbawa, II Lombok Tengah, II Lombok Barat, II Lombok Timur', '20251123125742.mmi.jpg', '2025-11-23 06:53:48'),
(63, '2025-11-23 10:59:40', '23 Nov 2025', '10:59:40 WIB', '3.9', '23 km', 'Pusat gempa berada di Laut 71 Km Barat Daya Air Bangis', '0.33 LS', '99.02 BT', 'Tidak ada keterangan potensi', 'II Air Bangis', '', '2025-11-23 06:53:49'),
(64, '2025-11-23 10:19:57', '23 Nov 2025', '10:19:57 WIB', '5.2', '10 km', 'Pusat gempa berada di darat 11 km Barat Daya Halmahera Timur', '1.21 LU', '128.48 BT', 'Tidak ada keterangan potensi', 'III Tobelo', '', '2025-11-23 06:53:49'),
(65, '2025-11-23 04:36:52', '23 Nov 2025', '04:36:52 WIB', '4.2', '5 km', 'Pusat gempa berada di darat 42 km barat Parigi Moutong', '0.55 LU', '120.43 BT', 'Tidak ada keterangan potensi', 'III-IV Palasa', '', '2025-11-23 06:53:49'),
(66, '2025-11-21 12:15:59', '21 Nov 2025', '12:15:59 WIB', '4.4', '25 km', 'Pusat gempa berada di laut 37 km barat daya Pesisir Selatan', '2.09 LS', '100.60 BT', 'Tidak ada keterangan potensi', 'III-IV Pesisir Selatan, II-III Tua Pejat, II-III Solok Selatan', '', '2025-11-23 06:53:49'),
(67, '2025-11-21 09:26:00', '21 Nov 2025', '09:26:00 WIB', '2.2', '8 km', 'Pusat gempa berada di darat 12 km timur laut Kota Cimahi', '6.81 LS', '107.65 BT', 'Tidak ada keterangan potensi', 'II-III Lembang, II-III Cihideung, II-III Parongpong, Kab. Bandung Barat, II-III Dago, Kota Bandung', '', '2025-11-23 06:53:49'),
(68, '2025-11-20 13:59:44', '20 Nov 2025', '13:59:44 WIB', '6.0', '119 km', 'Pusat gempa berada di laut 15 km tenggara Ambon', '3.66 LS', '128.33 BT', 'Tidak ada keterangan potensi', 'III-IV Amahai, III Ambon, III Sorong, II Fak - Fak', '', '2025-11-23 06:53:49'),
(69, '2025-11-20 11:19:55', '20 Nov 2025', '11:19:55 WIB', '5.4', '118 km', 'Pusat gempa berada di laut 55 km BaratLaut Halmahera Barat', '1.59 LU', '127.15 BT', 'Tidak ada keterangan potensi', 'II Ternate, III Naha, III Halmahera Barat, III Bitung', '', '2025-11-23 06:53:49'),
(70, '2025-11-20 09:49:13', '20 Nov 2025', '09:49:13 WIB', '4.5', '5 km', 'Pusat gempa berada di laut 39 km barat daya Parigi Moutong', '0.45 LU', '120.47 BT', 'Tidak ada keterangan potensi', 'II-III Tolitoli', '', '2025-11-23 06:53:49'),
(71, '2025-11-20 07:15:35', '20 Nov 2025', '07:15:35 WIB', '4.8', '10 km', 'Pusat gempa berada di darat 40 km barat daya Parigimoutong', '0.56 LU', '120.44 BT', 'Tidak ada keterangan potensi', 'III Tolitoli', '', '2025-11-23 06:53:49'),
(72, '2025-11-20 00:26:56', '20 Nov 2025', '00:26:56 WIB', '3.2', '10 km', 'Pusat gempa berada di darat 23 km Tenggara Kab. Bandung', '7.22 LS', '107.61 BT', 'Tidak ada keterangan potensi', 'III-IV Pangalengan, II-III Banjaran, II-III Ibun, II-III Kertasari, II-III Pasirjambu, II-III Baleendah, II-III Margaasih', '', '2025-11-23 06:53:49'),
(73, '2025-11-19 23:50:36', '19 Nov 2025', '23:50:36 WIB', '2.2', '5 km', 'Pusat gempa berada di darat 24 km Tenggara Kab. Bandung', '7.23 LS', '107.59 BT', 'Tidak ada keterangan potensi', 'II Pangalengan', '', '2025-11-23 06:53:49'),
(74, '2025-11-19 22:54:21', '19 Nov 2025', '22:54:21 WIB', '3.1', '5 km', 'Pusat gempa berada di darat 22 km Tenggara Kabupaten Bandung', '7.22 LS', '107.58 BT', 'Tidak ada keterangan potensi', 'II-III Kertasari, II-III Cimaung, II-III Pangalengan, II-III Pameungpeuk', '', '2025-11-23 06:53:49'),
(75, '2025-11-19 10:10:41', '19 Nov 2025', '10:10:41 WIB', '2.9', '5 km', 'Pusat gempa berada di darat 21 km tenggara Kabupaten Bandung', '7.19 LS', '107.63 BT', 'Tidak ada keterangan potensi', 'II-III Kertasari, II-III Banjaran', '', '2025-11-23 06:53:49'),
(76, '2025-11-19 00:13:20', '19 Nov 2025', '00:13:20 WIB', '3.1', '4 km', 'Pusat gempa berada di darat 29 km timur Konawe', '3.82 LS', '122.31 BT', 'Tidak ada keterangan potensi', 'III Wawolesea', '', '2025-11-23 06:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `gempa_terkini`
--

CREATE TABLE `gempa_terkini` (
  `id` int NOT NULL,
  `tanggal` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jam` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `lintang` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `bujur` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `magnitude` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `kedalaman` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `wilayah` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `potensi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dirasakan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `shakemap` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gempa_terkini`
--

INSERT INTO `gempa_terkini` (`id`, `tanggal`, `jam`, `lintang`, `bujur`, `magnitude`, `kedalaman`, `wilayah`, `potensi`, `dirasakan`, `shakemap`, `created_at`) VALUES
(0, '05 Nov 2025', '06:32:24 WIB', '0.11 LS', '123.13 BT', '6.2', '103 km', 'Pusat gempa berada di laut 71 km barat daya Bone Bolango', 'Gempa ini dirasakan untuk diteruskan pada masyarakat', 'III-IV Bone Bolango, III-IV Luwuk, III Kab. Gorontalo Utara, III Kab. Boalemo, III Ampana, III Taliabu, III Bolaang Mongodow Utara, III Bolaang Mongodow, III Banggai Kepulauan, III Banggai Laut, III Kota Gorontalo, II-III Kab. Pohuwato, II-III Tondano, II', '20251105063224.mmi.jpg', '2025-11-05 06:06:15');

-- --------------------------------------------------------

--
-- Table structure for table `wilayah_resiko`
--

CREATE TABLE `wilayah_resiko` (
  `id` int NOT NULL,
  `nama_wilayah` varchar(255) NOT NULL,
  `kategori` enum('tinggi','sedang','rendah') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wilayah_resiko`
--

INSERT INTO `wilayah_resiko` (`id`, `nama_wilayah`, `kategori`, `created_at`) VALUES
(1, 'Pantai Barat Sumatra', 'tinggi', '2025-11-23 07:17:15'),
(2, 'Pantai Selatan Jawa', 'tinggi', '2025-11-23 07:17:15'),
(3, 'Bali & NTB', 'tinggi', '2025-11-23 07:17:15'),
(4, 'Sulawesi Tengah', 'tinggi', '2025-11-23 07:17:15'),
(5, 'Maluku & Papua', 'tinggi', '2025-11-23 07:17:15'),
(6, 'Pantai Utara Jawa', 'sedang', '2025-11-23 07:17:15'),
(7, 'Sebagian Sulawesi Selatan', 'sedang', '2025-11-23 07:17:15'),
(8, 'Sebagian Kalimantan Timur', 'sedang', '2025-11-23 07:17:15'),
(9, 'Kepulauan Nias', 'sedang', '2025-11-23 07:17:15'),
(10, 'Pantai Selatan Kalimantan', 'rendah', '2025-11-23 07:17:15'),
(11, 'Sebagian Maluku Utara', 'rendah', '2025-11-23 07:17:15'),
(12, 'Papua bagian utara', 'rendah', '2025-11-23 07:17:15'),
(19, 'test', 'rendah', '2025-11-23 08:46:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_gempa`
--
ALTER TABLE `data_gempa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_gempa` (`tanggal_jam`,`lintang`,`bujur`);

--
-- Indexes for table `gempa_terkini`
--
ALTER TABLE `gempa_terkini`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wilayah_resiko`
--
ALTER TABLE `wilayah_resiko`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `data_gempa`
--
ALTER TABLE `data_gempa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `wilayah_resiko`
--
ALTER TABLE `wilayah_resiko`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
