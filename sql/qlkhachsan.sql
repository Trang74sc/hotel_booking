-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 08:01 AM
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
-- Database: `qlkhachsan`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `room_id`, `customer_name`, `email`, `check_in`, `check_out`, `status`, `created_at`, `user_id`) VALUES
(40, 3, 'user', 'trangbui@gmail.com', '2025-05-06', '2025-05-08', 'pending', '2025-05-04 10:47:48', 2),
(45, 4, 'Phạm Thu Minh', 'mnhan4568@gmail.com', '2025-05-05', '2025-05-06', 'pending', '2025-05-04 12:38:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `max_guests` int(11) DEFAULT NULL,
  `amenities` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `type`, `price`, `max_guests`, `amenities`) VALUES
(1, 'Phòng Đơn Tiêu Chuẩn', 'Đơn', 500000.00, 1, 'wifi,tv,air_con'),
(2, 'Phòng Đôi Gia Đình', 'Đôi', 900000.00, 2, 'wifi,tv,air_con,minibar'),
(3, 'Phòng Suite Hạng Sang', 'Suite', 1800000.00, 4, 'wifi,tv,air_con,minibar,spa,bathtub'),
(4, 'Phòng Deluxe View Biển', 'Deluxe', 1200000.00, 3, 'wifi,tv,air_con,minibar,sea_view'),
(5, 'Phòng Dorm Tập Thể', 'Dorm', 300000.00, 6, 'wifi,tv,air_con'),
(6, 'Phòng Superior Cao Cấp', 'Superior', 1500000.00, 2, 'wifi,tv,air_con,minibar,spa');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Phạm Thị Hoài', 'mnhan4568@gmail.com', '$2y$10$YqosdPgt8Hn9p9M5rcoTO.JD.QyViwaSkrhacdbU4y10iga7ruzR6', '2025-05-04 03:22:24'),
(2, 'Bùi Thu Trang', 'trangbui@gmail.com', '$2y$10$mTNU9laFOj5tZDDdaC277e5Depmt9PliF60XH1dVawu17p6/RwR52', '2025-05-04 03:47:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
