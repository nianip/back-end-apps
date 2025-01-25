-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2025 at 05:34 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uas`
--

-- --------------------------------------------------------

--
-- Table structure for table `tempat`
--

CREATE TABLE `tempat` (
  `id` int(11) NOT NULL,
  `nama_lapangan` varchar(100) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `ukuran_lapangan` varchar(50) DEFAULT NULL,
  `kapasitas_lapangan` int(11) DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tempat`
--

INSERT INTO `tempat` (`id`, `nama_lapangan`, `alamat`, `deskripsi`, `ukuran_lapangan`, `kapasitas_lapangan`, `harga`) VALUES
(1, 'Lapangan Futsal 1', 'Jl. Sudirman No. 1', 'Lapangan futsal indoor dengan lantai vinyl asli', '20x40 meter', 15, '150000.00'),
(2, 'Lapangan Futsal 2', 'Jl. Thamrin No. 99', 'Lapangan futsal indoor dengan lantai sintetis', '28x15 meter', 14, '60000.00');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_lapangan` int(11) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_booking` varchar(255) DEFAULT NULL,
  `nama_pembooking` varchar(255) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_user`, `id_lapangan`, `tanggal_booking`, `jam_booking`, `nama_pembooking`, `catatan`) VALUES
(2, 3, 1, '2025-01-17', '10 AM - 11 AM', 'Fulan', 'Hey'),
(3, 3, 1, '2025-01-17', '12 PM - 01 PM, 01 PM - 02 PM', 'Jamal', 'Jamaludin'),
(4, 3, 2, '2025-01-17', '10 AM - 11 AM, 11 AM - 12 PM', 'Gunter', 'Hayyuk Gunter'),
(5, 3, 1, '2025-01-17', '11 AM - 12 PM', 'Galih', 'Galih lubang'),
(6, 3, 1, '2025-01-19', '10 AM - 11 AM', 'Ghea', 'Ghea ghea'),
(7, 3, 1, '2025-01-18', '10 AM - 11 AM', 'Alif', 'Alif main'),
(8, 3, 1, '2025-01-18', '11 AM - 12 PM', 'Alif', 'Alif lagi main'),
(9, 3, 1, '2025-02-01', '11 AM - 12 PM, 10 AM - 11 AM', 'Ahmad', 'Ahmad gas'),
(10, 3, 1, '2025-01-18', '12 PM - 01 PM', 'Hanif', 'Hanif hayyuk'),
(11, 3, 2, '2025-01-18', '10 AM - 11 AM, 11 AM - 12 PM', 'Ahmad', 'Ahmad main'),
(12, 3, 1, '2025-01-18', '01 PM - 02 PM', 'Ahmad', 'Ahmad bermain'),
(13, 3, 1, '2025-01-18', '02 PM - 03 PM, 03 PM - 04 PM', 'Rendi', 'Rendi bermain'),
(14, 3, 1, '2025-01-18', '04 PM - 05 PM', 'Gerald', 'Gerald main'),
(15, 3, 2, '2025-01-19', '10 AM - 11 AM', 'Tim', 'Tim Bermain'),
(16, 3, 2, '2025-01-19', '11 AM - 12 PM', 'Tim', 'Tim bermain');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(256) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `role`) VALUES
(2, 'tes', 'ce0f6c28b5869ff166714da5fe08554c70c731a335ff9702e38b00f81ad348c6', '2025-01-10 19:58:28', 'tes'),
(3, 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '2025-01-10 20:09:41', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tempat`
--
ALTER TABLE `tempat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_lapangan` (`id_lapangan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tempat`
--
ALTER TABLE `tempat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_lapangan`) REFERENCES `tempat` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
