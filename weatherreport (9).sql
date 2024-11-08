-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 05:27 PM
-- Server version: 8.0.39
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `weatherreport`
--

-- --------------------------------------------------------

--
-- Table structure for table `cuaca`
--

CREATE TABLE `cuaca` (
  `id` int NOT NULL,
  `jam` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kondisi` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `suhu` int NOT NULL,
  `kelembapan` int NOT NULL,
  `id_negara` int NOT NULL,
  `id_lokasi` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cuaca`
--

INSERT INTO `cuaca` (`id`, `jam`, `kondisi`, `suhu`, `kelembapan`, `id_negara`, `id_lokasi`) VALUES
(97, '00:00', 'Cerah', 20, 3, 4, 2),
(98, '01:00', 'Berawan', 2, 3, 4, 2),
(99, '02:00', 'Cerah', 3, 4, 4, 2),
(100, '03:00', 'Cerah', 3, 4, 4, 2),
(101, '04:00', 'Berawan', 4, 5, 4, 2),
(102, '05:00', 'Berawan', 3, 4, 4, 2),
(103, '06:00', 'Hujan', 4, 4, 4, 2),
(104, '07:00', 'Berawan', 6, 4, 4, 2),
(105, '08:00', 'Cerah', 5, 4, 4, 2),
(106, '09:00', 'Cerah', 5, 4, 4, 2),
(107, '10:00', 'Cerah', 7, 4, 4, 2),
(108, '11:00', 'Cerah', 4, 3, 4, 2),
(109, '12:00', 'Hujan', 4, 5, 4, 2),
(110, '13:00', 'Berawan', 6, 5, 4, 2),
(111, '14:00', 'Cerah', 4, 5, 4, 2),
(112, '15:00', 'Cerah', 5, 5, 4, 2),
(113, '16:00', 'Hujan', 5, 5, 4, 2),
(114, '17:00', 'Berawan', 6, 3, 4, 2),
(115, '18:00', 'Hujan', 5, 5, 4, 2),
(116, '19:00', 'Hujan', 5, 4, 4, 2),
(117, '20:00', 'Cerah', 6, 5, 4, 2),
(118, '21:00', 'Hujan', 4, 5, 4, 2),
(119, '22:00', 'Cerah', 4, 4, 4, 2),
(120, '23:00', 'Cerah', 2, 3, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `data_users`
--

CREATE TABLE `data_users` (
  `id` int NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `roles` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_users`
--

INSERT INTO `data_users` (`id`, `email`, `username`, `password`, `roles`) VALUES
(1, 'admin@gmail.com', 'admin', '$2y$10$vMPloOLWANesYtk8JVy3GuOv8OdK6P6QqAi3UU3fbvGWcLIdcCE.u', 'admin'),
(2, 'haji@gmail.com', 'haji', '$2y$10$YIiskOf9PgxZmGiA/qOEJe9wEw0LPSdM0dGbH7.p7OB4ccH7cCFsa', 'user'),
(3, 'chan@gmail.com', 'chan', '$2y$10$7PIIcJEYMlX55i14HEK84uJaw/Ap4ht.N9/W12t4C2nQhDEetj8p2', 'user'),
(4, 'cellia@gmail.com', 'cellia', '$2y$10$p5DDzPIq7eVBZFFPqMhY4uVJcu.wpT0UgQ4OVxqLqBsxxXUFC08Gm', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE `lokasi` (
  `id` int NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `koordinat` point DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id`, `alamat`, `koordinat`) VALUES
(2, 'busan', 0x0000000001010000004e25034015f15f4087dba16131f44140);

-- --------------------------------------------------------

--
-- Table structure for table `negara`
--

CREATE TABLE `negara` (
  `id` int NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `negara`
--

INSERT INTO `negara` (`id`, `foto`, `nama`) VALUES
(4, '2024-11-09 00.24.55.jpg', 'South Korea');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cuaca`
--
ALTER TABLE `cuaca`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKidnegara` (`id_negara`) USING BTREE,
  ADD KEY `FKidlokasi` (`id_lokasi`) USING BTREE;

--
-- Indexes for table `data_users`
--
ALTER TABLE `data_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `negara`
--
ALTER TABLE `negara`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cuaca`
--
ALTER TABLE `cuaca`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `data_users`
--
ALTER TABLE `data_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `negara`
--
ALTER TABLE `negara`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
