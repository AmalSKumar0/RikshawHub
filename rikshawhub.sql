-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2024 at 06:25 AM
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
-- Database: `rikshawhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintable`
--

CREATE TABLE `admintable` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admintable`
--

INSERT INTO `admintable` (`admin_id`, `name`, `email`, `password`) VALUES
(1, 'Amal S Kumar', 'amalskumar@gmail.com', 'amal@123'),
(2, 'Aleena Maria James', 'aleenamariajames@gmail.com', 'aleena@123'),
(3, 'Joshua M Philip', 'joushuamphilip@gmail.com', 'joshua@123'),
(4, 'Riyan Shamsudeen', 'riyanshamsudeen@gmail.com', 'riyan@123');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `book_id` int(11) NOT NULL,
  `pass_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `from` varchar(255) DEFAULT NULL,
  `to` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `OTP` int(6) DEFAULT NULL,
  `distance` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `completed_bookings`
--

CREATE TABLE `completed_bookings` (
  `booking_id` int(11) NOT NULL,
  `pass_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `completion_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `completed_bookings`
--

INSERT INTO `completed_bookings` (`booking_id`, `pass_id`, `driver_id`, `status`, `completion_time`) VALUES
(1, 9, 17, 'complete', '2024-10-06 10:40:03'),
(2, 9, 17, 'complete', '2024-10-13 16:22:32'),
(3, 9, 17, 'complete', '2024-10-13 16:38:48'),
(4, 9, 17, 'cancel', '2024-10-13 17:47:26'),
(5, 9, 17, 'complete', '2024-10-14 06:21:28'),
(6, 9, 17, 'complete', '2024-10-14 20:24:16'),
(7, 9, 17, 'complete', '2024-10-15 07:11:51'),
(8, 9, 17, 'complete', '2024-10-15 07:33:23'),
(9, 9, 17, 'complete', '2024-10-15 07:39:07'),
(10, 9, 17, 'complete', '2024-10-15 08:10:10'),
(11, 9, 17, 'complete', '2024-10-15 08:12:15'),
(12, 9, 17, 'complete', '2024-10-15 16:30:37'),
(13, 9, 17, 'complete', '2024-10-15 16:33:42'),
(14, 9, 17, 'complete', '2024-10-16 08:02:30'),
(15, 9, 17, 'complete', '2024-10-16 08:06:21'),
(16, 9, 17, 'complete', '2024-10-16 15:59:23'),
(17, 9, 17, 'complete', '2024-10-17 07:16:45'),
(18, 9, 17, 'complete', '2024-10-17 09:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `driver_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `vehicle_no` varchar(50) NOT NULL,
  `licence_no` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `Auto_img` longblob DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0 CHECK (`rating` >= 0.0 and `rating` <= 5.0),
  `is_active` tinyint(1) DEFAULT 0,
  `current_location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`driver_id`, `name`, `email`, `phone_no`, `address`, `vehicle_no`, `licence_no`, `password`, `gender`, `Auto_img`, `rating`, `is_active`, `current_location`, `created_at`) VALUES
(17, 'Amal S kumar', 'amalskumarofficialz@gmail.com', '8590774603', 'erumely', 'KL 32 C 0027', '987654321', '$2y$10$h9a0af6jnNQKf1BzHLJnzOO/F1Grm8UTY9/l8/rVZH8YsdxHpU9wy', 'Male', 0x63316433343064312d363839382d346236642d386664302d3834376636643531646135612e6a706567, 5.0, 1, 'Ranni, Pathanamthitta, Kerala, India', '2024-10-02 04:03:09'),
(18, 'Ayan anya', 'ayan@gmail.com', '8520733607', 'erumely', 'KL 32 D 3425', '8726345643', '$2y$10$Ah7PcC/1FPD8xu5yYy6FWuivMc0enoMPMA7sHjUWsnzOSRIwiaH7a', 'Male', 0x496e6469616e204175746f205269636b736861772e6a706567, 0.0, 0, NULL, '2024-10-16 12:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `passenger`
--

CREATE TABLE `passenger` (
  `pass_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passenger`
--

INSERT INTO `passenger` (`pass_id`, `name`, `email`, `phone_no`, `address`, `password`, `gender`) VALUES
(9, 'Abijit', 'abijit@gmail.com', '8590774603', 'erumely', '123', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `pass_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `stars` int(11) DEFAULT NULL CHECK (`stars` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `pass_id`, `driver_id`, `review_text`, `created_date`, `stars`) VALUES
(1, 9, 17, 'hello', '2024-10-14 01:01:45', 5),
(2, 9, 17, 'very good', '2024-10-14 01:37:03', 5),
(4, 9, 17, 'yea buddy', '2024-10-16 02:36:19', 5),
(5, 9, 17, 'was great', '2024-10-17 04:19:19', 5);

-- --------------------------------------------------------

--
-- Table structure for table `temporarydriver`
--

CREATE TABLE `temporarydriver` (
  `driver_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `vehicle_no` varchar(50) NOT NULL,
  `licence_no` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `Auto_img` longblob DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `temporarydriver`
--

INSERT INTO `temporarydriver` (`driver_id`, `name`, `email`, `phone_no`, `address`, `vehicle_no`, `licence_no`, `password`, `gender`, `Auto_img`, `created_at`) VALUES
(23, 'vijay p', 'vijay@gmail.com', '9884726355', 'erumely', 'KL 32 F 6354', '6245826528', '$2y$10$dpwfkSXz7xtlpRCAGMus4O7wN3b0g5KNzxVSqjorSiibp8ByGbmKq', 'Male', 0x4175746f2072696b73686120696e20206d756d62616920496e6469612e6a706567, '2024-08-20 06:26:54'),
(24, 'kiran binoy', 'kiran@gmail.com', '5916573242', 'erumely', 'KL 62 C 3462', '987654321', '$2y$10$3ROxC5iimdzLQ2/lyGIXX.V3Xzod.SYRbHwwoaTHlGiqJ.BHhkqeu', 'Male', 0x65396566303965362d633964612d346363332d623938642d3439626563343930646237622e6a706567, '2024-08-20 06:28:00'),
(25, 'fake profile', 'fake@gmail.com', '0000000000', 'Nill', 'KL 00 XX 0000', '0000000000', '$2y$10$weE2WQyFEl2.kWjr1IrqPeHqSkdFSG9zO85.0F6hy7erZmDxJaNmG', 'Other', 0x312c313734204175746f205269636b7368617720436172746f6f6e20496d616765732c2053746f636b2050686f746f732c203344206f626a656374732c202620566563746f7273205f205368757474657273746f636b2e6a706567, '2024-08-20 06:30:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admintable`
--
ALTER TABLE `admintable`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `pass_id` (`pass_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `completed_bookings`
--
ALTER TABLE `completed_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `pass_id` (`pass_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `passenger`
--
ALTER TABLE `passenger`
  ADD PRIMARY KEY (`pass_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `temporarydriver`
--
ALTER TABLE `temporarydriver`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admintable`
--
ALTER TABLE `admintable`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2562;

--
-- AUTO_INCREMENT for table `completed_bookings`
--
ALTER TABLE `completed_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `passenger`
--
ALTER TABLE `passenger`
  MODIFY `pass_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `temporarydriver`
--
ALTER TABLE `temporarydriver`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`pass_id`) REFERENCES `passenger` (`pass_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`);

--
-- Constraints for table `completed_bookings`
--
ALTER TABLE `completed_bookings`
  ADD CONSTRAINT `completed_bookings_ibfk_1` FOREIGN KEY (`pass_id`) REFERENCES `passenger` (`pass_id`),
  ADD CONSTRAINT `completed_bookings_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
