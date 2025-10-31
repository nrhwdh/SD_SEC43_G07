-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 02:02 PM
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
-- Database: `the_pearl`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(30) DEFAULT NULL,
  `password_changed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password_hash`, `avatar`, `created_at`, `phone`, `password_changed_at`) VALUES
(1, 'Huwaidah', 'admin@thepearl.test', '$2y$10$PFi3w1dfQ7l2.LA/WNfXteB.WQZgYig6RtDjWLscTqRsz1Y64WYmC', NULL, '2025-09-21 19:57:38', '0123456789', '2025-10-21 20:27:56'),
(3, 'Admin', 'rosellydia16@gmail.com', '$2y$10$t34x3RuSUkzJFDa6gRxsFOjP9m5161AlIFuRc7FKIipeQ6TVwsrtq', NULL, '2025-09-21 20:50:23', '0111111167', '2025-09-23 14:48:59');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `guest_name` varchar(120) NOT NULL,
  `guest_email` varchar(120) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests` int(11) NOT NULL DEFAULT 1,
  `nights` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `room_id`, `guest_name`, `guest_email`, `check_in`, `check_out`, `guests`, `nights`, `total`, `created_at`, `quantity`) VALUES
(1, 1, 'Siti', 'siti@gmail.com', '2025-09-19', '2025-09-20', 1, 1, 480.00, '2025-09-19 07:18:19', 1),
(3, 1, '0', 'aidan@gamil.com', '2025-09-21', '2025-09-23', 1, 2, 960.00, '2025-09-21 14:47:26', 1),
(4, 6, '0', 'aidan@gamil.com', '2025-09-30', '2025-10-01', 1, 1, 520.00, '2025-09-28 16:45:34', 1),
(5, 2, '0', 'mimi@gmail.com', '2025-10-05', '2025-10-06', 4, 1, 620.00, '2025-10-04 03:00:40', 1),
(6, 6, '0', 'sya@gmail.com', '2025-10-05', '2025-10-06', 2, 1, 520.00, '2025-10-04 03:05:37', 1),
(7, 7, '0', 'kal12@gmail.com', '2025-10-04', '2025-10-06', 3, 2, 640.00, '2025-10-04 03:13:41', 1),
(8, 5, '0', 'mmalikh22@gmail.com', '2025-10-04', '2025-10-05', 6, 1, 430.00, '2025-10-04 03:31:25', 1),
(9, 5, '0', 'sitia@gmail.com', '2025-10-04', '2025-10-05', 1, 1, 430.00, '2025-10-04 03:34:50', 1),
(10, 2, '0', 'airinanur@gmail.com', '2025-10-06', '2025-10-09', 2, 3, 1860.00, '2025-10-04 04:04:58', 1),
(11, 2, '0', 'airinanur@gmail.com', '2025-10-06', '2025-10-09', 2, 3, 1860.00, '2025-10-04 04:13:33', 1),
(12, 8, '0', 'rosellydia16@gmail.com', '2025-10-12', '2025-10-14', 3, 2, 720.00, '2025-10-04 04:15:29', 1),
(13, 1, '0', 'rosellydia16@gmail.com', '2025-10-22', '2025-10-23', 3, 1, 480.00, '2025-10-21 05:51:47', 1),
(14, 1, '0', 'rosellydia16@gmail.com', '2025-10-22', '2025-10-23', 3, 1, 480.00, '2025-10-21 05:55:31', 1),
(15, 1, '0', 'rosellydia16@gmail.com', '2025-10-22', '2025-10-23', 3, 1, 480.00, '2025-10-21 05:55:35', 1),
(16, 1, '0', 'rosellydia16@gmail.com', '2025-10-22', '2025-10-23', 3, 1, 480.00, '2025-10-21 05:57:52', 1),
(17, 1, '0', 'rosellydia16@gmail.com', '2025-10-22', '2025-10-23', 3, 1, 480.00, '2025-10-21 05:58:02', 1),
(18, 4, '0', 'rosellydia16@gmail.com', '2025-10-24', '2025-10-27', 2, 3, 1140.00, '2025-10-21 12:52:53', 1),
(19, 4, '0', 'rosellydia16@gmail.com', '2025-10-24', '2025-10-27', 2, 3, 1140.00, '2025-10-21 12:54:20', 1),
(20, 8, '0', 'rosellydia16@gmail.com', '2025-10-22', '2025-10-23', 4, 1, 360.00, '2025-10-21 13:17:55', 1),
(21, 8, '0', 'rosellydia16@gmail.com', '2025-10-22', '2025-10-23', 4, 1, 360.00, '2025-10-21 13:18:56', 1),
(22, 5, '0', 'nrhwaidh@gmail.com', '2025-10-22', '2025-10-23', 4, 1, 430.00, '2025-10-21 13:26:42', 1),
(23, 3, '0', 'nrhwaidh@gmail.com', '2025-10-23', '2025-10-24', 3, 1, 360.00, '2025-10-21 14:31:25', 1),
(24, 3, '0', 'nrhwaidh@gmail.com', '2025-10-22', '2025-10-23', 3, 1, 360.00, '2025-10-21 14:46:04', 1),
(25, 6, '0', 'nrhwaidh@gmail.com', '2025-10-27', '2025-10-28', 3, 1, 520.00, '2025-10-25 18:48:39', 1),
(26, 7, '0', 'malikm22@gmail.com', '2025-11-07', '2025-11-08', 2, 1, 320.00, '2025-10-25 19:04:20', 1),
(27, 5, '0', 'malikm22@gmail.com', '2025-10-27', '2025-10-30', 4, 3, 1290.00, '2025-10-25 19:05:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` tinyint(3) UNSIGNED DEFAULT NULL CHECK (`rating` between 1 and 5),
  `stay_date` date DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `message`, `created_at`, `rating`, `stay_date`, `room_id`) VALUES
(1, 'Amira Z', '-', 'Loved the view and the connecting mall. Family room is spacious and spotless.', '2025-09-15 01:32:00', 5, '2025-09-12', 1),
(2, 'Daniel L', '-', 'Spacious suite and super comfy bed. Interconnecting room request was smooth.', '2025-08-28 10:11:00', 5, '2025-08-27', 2),
(3, 'Farah N', '-', 'Clean, quiet floor and nice city view. Breakfast decent, would stay again.', '2025-09-04 04:45:00', 4, '2025-09-02', 3),
(4, 'Kelvin T', '-', 'Check-in was smooth, great location linked to the mall. Gym is well kept.', '2025-07-15 12:06:00', 4, '2025-07-14', 4),
(5, 'Nurul H', '-', 'Studio room fits our short stay. Love the café downstairs and friendly staff.', '2025-06-09 00:10:00', 5, '2025-06-08', 8),
(6, 'Ariff R', '-', 'Kids enjoyed the pool. Room was comfy; breakfast could be improved.', '2025-05-04 02:00:00', 3, '2025-05-03', 6),
(7, 'Aidan', '-', 'i love the view and room!', '2025-09-22 14:26:35', 5, '2025-09-02', 8),
(8, 'Sasya', '-', 'the view niceyh', '2025-09-23 06:48:16', 5, '2025-09-05', 6),
(9, 'Anonymous', '-', 'I love the view.', '2025-10-04 07:46:33', 5, '2025-08-11', 2),
(10, 'Mimi', '-', 'Wish i could stay more longer.', '2025-10-11 13:36:48', 5, '2025-10-11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(191) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `success` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `email`, `ip`, `created_at`, `success`) VALUES
(1, 'rosellydia16@gmail.com', '::1', '2025-09-22 05:07:41', 1),
(2, 'rosellydia16@gmail.com', '::1', '2025-09-22 05:17:22', 1),
(3, 'rosellydia16@gmail.com', '::1', '2025-09-22 05:18:08', 1),
(4, 'rosellydia16@gmail.com', '::1', '2025-09-22 05:19:25', 1),
(5, 'rosellydia16@gmail.com', '::1', '2025-09-22 06:07:07', 1),
(6, 'rosellydia16@gmail.com', '::1', '2025-09-22 06:14:16', 1),
(7, 'rosellydia16@gmail.com', '::1', '2025-09-22 06:39:51', 1),
(8, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:14:17', 1),
(9, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:14:18', 1),
(10, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:14:20', 1),
(11, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:14:21', 1),
(12, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:41:07', 1),
(13, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:41:08', 1),
(14, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:41:09', 1),
(15, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:42:51', 1),
(16, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:42:52', 1),
(17, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:46:05', 1),
(18, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:46:07', 1),
(19, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:46:08', 1),
(20, 'rosellydia16@gmail.com', '::1', '2025-09-22 07:46:09', 1),
(23, 'rosellydia16@gmail.com', '::1', '2025-09-23 12:52:49', 0),
(24, 'rosellydia16@gmail.com', '::1', '2025-09-23 12:52:53', 1),
(25, 'rosellydia16@gmail.com', '::1', '2025-09-23 12:53:14', 1),
(26, 'rosellydia16@gmail.com', '::1', '2025-09-23 12:55:23', 1),
(27, 'rosellydia16@gmail.com', '::1', '2025-09-23 12:58:04', 1),
(28, 'rosellydia16@gmail.com', '::1', '2025-09-23 13:00:49', 0),
(29, 'rosellydia16@gmail.com', '::1', '2025-09-23 13:01:03', 1),
(30, 'rosellydia16@gmail.com', '::1', '2025-09-23 13:01:08', 1),
(31, 'rosellydia16@gmail.com', '::1', '2025-09-23 13:01:35', 1),
(32, 'admin@thepearl.test', '::1', '2025-09-23 13:03:33', 1),
(33, 'rosellydia16@gmail.com', '::1', '2025-09-23 14:48:31', 1),
(34, 'rosellydia16@gmail.com', '::1', '2025-09-23 14:49:15', 1),
(35, 'rosellydia16@gmail.com', '::1', '2025-09-25 23:11:01', 0),
(36, 'rosellydia16@gmail.com', '::1', '2025-09-25 23:11:03', 0),
(37, 'rosellydia16@gmail.com', '::1', '2025-09-25 23:11:05', 0),
(38, 'admin@thepearl.test', '::1', '2025-09-25 23:11:07', 1),
(39, 'admin@thepearl.test', '::1', '2025-10-10 20:34:44', 1),
(40, 'nrhwaidah@gmail.com', '::1', '2025-10-11 21:47:03', 0),
(41, 'admin@thepearl.test', '::1', '2025-10-11 21:47:39', 0),
(42, 'nrhwaidah@gmail.com', '::1', '2025-10-11 21:49:38', 0),
(43, 'admin@thepearl.test', '::1', '2025-10-11 21:51:30', 1),
(44, 'admin@thepearl.test', '::1', '2025-10-11 21:52:46', 1),
(45, 'admin@thepearl.test', '::1', '2025-10-12 21:22:07', 1),
(46, 'rosellydia16@gmail.com', '::1', '2025-10-13 05:33:18', 0),
(47, 'rosellydia16@gmail.com', '::1', '2025-10-13 05:33:22', 0),
(48, 'admin@thepearl.test', '::1', '2025-10-13 05:33:39', 1),
(49, 'admin@thepearl.test', '::1', '2025-10-13 15:24:23', 1),
(50, 'admin@thepearl.test', '::1', '2025-10-19 22:36:50', 1),
(51, 'admin@thepearl.test', '::1', '2025-10-19 22:36:58', 1),
(52, 'admin@thepearl.test', '::1', '2025-10-21 12:53:12', 1),
(53, 'admin@thepearl.test', '::1', '2025-10-21 20:37:08', 1),
(54, 'admin@thepearl.test', '::1', '2025-10-26 02:24:05', 1),
(55, 'rosellydia16@gmail.com', '::1', '2025-10-26 02:28:46', 1),
(56, 'rosellydia16@gmail.com', '::1', '2025-10-26 02:30:49', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token_hash`, `expires_at`, `created_at`) VALUES
(1, 'rosellydia16@gmail.com', '704d995404bcde2f126cb938a545718167baa22cff8b032bc008d258cdd60a15', '2025-10-21 16:55:14', '2025-10-21 21:55:14'),
(2, 'rosellydia16@gmail.com', '36e8c92a38f37e7e57d4c4039b134500f3acf18667296e7b31a857388963f0f5', '2025-10-21 16:55:40', '2025-10-21 21:55:40'),
(3, 'rosellydia16@gmail.com', 'cd6dd0dd641f58077ce974b7503f423e66057ee6aca59dd1db7a95a6f717b42e', '2025-10-21 17:01:27', '2025-10-21 22:01:27'),
(4, 'rosellydia16@gmail.com', '8553a363c795f92a2935ecfc4d570cc8836e1bbdf39d106074f251fc64e583e9', '2025-10-21 17:02:59', '2025-10-21 22:02:59'),
(5, 'rosellydia16@gmail.com', 'bba1b98b196b75ea427c15e756b3399bcd2e0a17e8663f90bbca1ed4a5cf3383', '2025-10-25 21:11:17', '2025-10-26 02:11:17'),
(6, 'rosellydia16@gmail.com', '2b6c1a30182ea5a6ce307f0dc42e59123ea4aaf14ba5b947599516fd19d87de4', '2025-10-25 21:19:04', '2025-10-26 02:19:04'),
(7, 'rosellydia16@gmail.com', 'f91e729aee45b207443912f957348b2b432e76145661685f712601e23d1d52d8', '2025-10-25 21:24:11', '2025-10-26 02:24:11');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `beds` varchar(50) NOT NULL,
  `size` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `total_rooms` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `beds`, `size`, `price`, `image`, `total_rooms`) VALUES
(1, 'Pearl Deluxe Family', '1 King + 2 Single', '68 m² / 732 sqf', 480.00, 'assets/img/rooms/pearl-deluxe-family.jpg', 42),
(2, 'Pearl Suite', '1 Super King', '68 m² / 732 sqf', 620.00, 'assets/img/rooms/pearl-suite.jpg', 39),
(3, 'Pearl Premier', 'Super King or Twin', '34 m² / 366 sqf', 360.00, 'assets/img/rooms/pearl-premier.jpg', 42),
(4, 'Pearl Executive King/Twin', 'Super King or Super Single (Twin)', '34 m² / 366 sqf', 380.00, 'assets/img/rooms/pearl-executive-king.jpg', 42),
(5, 'Pearl Executive Studio', '1 Super King + 1 Super Single / 3 SS', '47.5 m² / 496 sqf', 430.00, 'assets/img/rooms/pearl-executive-studio.jpg', 42),
(6, 'Pearl Executive Family', '1 Super King + 2 Super Single', '68 m² / 732 sqf', 520.00, 'assets/img/rooms/pearl-executive-family.jpg', 42),
(7, 'Pearl Deluxe King/Twin', 'King or Twin', '34 m² / 366 sqf', 320.00, 'assets/img/rooms/pearl-deluxe-king.jpg', 42),
(8, 'Pearl Deluxe Studio', '1 King + 1 Single / 3 Single', '47.5 m² / 496 sqf', 360.00, 'assets/img/rooms/pearl-deluxe-studio.jpg', 42);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_room` (`room_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rating` (`rating`),
  ADD KEY `stay_date` (`stay_date`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `ip` (`ip`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token_hash` (`token_hash`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
