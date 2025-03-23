-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2025 at 04:26 AM
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
-- Database: `bygemsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('customer','admin','staff') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`user_id`, `name`, `username`, `password`, `email`, `phone`, `address`, `gender`, `profile_picture`, `role`) VALUES
(2, 'Admin User', 'admin', '$2y$10$CNcHI1xPcLReglCt/d4Use1imy7pFiqV8OL4VHnhW0gFxhxi6g9tS', 'admin@example.com', '09123456789', 'Admin Address', 'Male', NULL, 'admin'),
(5, 'John Doe', 'staff_user', '$2y$10$E0.YCqhloelvCm8PGXg9guWPFglW.V.pokUpnZr/RnLKiurASWori', 'staff@example.com', '09956237162', '123 Staff Street', 'Male', NULL, 'staff'),
(6, 'John Doe', 'johndoe', 'password', 'john@example.com', '09345678901', '123 Main St', 'Male', NULL, 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `approve_events`
--

CREATE TABLE `approve_events` (
  `approval_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `event_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_cart`
--

CREATE TABLE `event_cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_request`
--

CREATE TABLE `event_request` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `celebrant_name` varchar(255) NOT NULL,
  `event_location` varchar(255) DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `payment_status` enum('Pending','Paid','Cancelled') DEFAULT 'Pending',
  `request_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `discounted_price` decimal(10,2) DEFAULT NULL,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_request`
--

INSERT INTO `event_request` (`event_id`, `user_id`, `order_id`, `celebrant_name`, `event_location`, `event_date`, `payment_status`, `request_status`, `discounted_price`, `discount_percentage`) VALUES
(8, 6, '2', 'Me2', '870 Nangka Drive', '2025-03-04 15:00:00', 'Paid', 'Approved', NULL, 5.00),
(9, 6, '3', 'Me', '870 Nangka Drive', '2025-02-15 00:00:00', 'Pending', 'Approved', NULL, 0.00),
(10, 6, '4', 'John', 'asd', '2025-02-24 14:00:00', 'Paid', 'Approved', NULL, 0.00),
(11, 6, '5', 'James', '123', '2025-02-24 00:00:00', 'Pending', 'Approved', NULL, 0.00),
(12, 6, '6', 'Me2', '870 Nangka Drive', '2025-03-07 01:00:00', '', 'Pending', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `event_status_history`
--

CREATE TABLE `event_status_history` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` enum('Pending','Approved','Cancelled','Completed') NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `paid_date` datetime DEFAULT current_timestamp(),
  `payment_status` enum('Pending','Partial','Paid','Overdue') DEFAULT 'Pending',
  `payment_type` enum('Cash','Card','Online') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `category` enum('Prop-Up Packages','Party Packages','Entertainers','Food Cart Stations','Amenities','Venue Decorations','Party Accessories','Cakes','Tier Cakes','Dessert Packages','Cupcakes','Brownies') NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `category`, `price`, `description`, `image`, `status`, `enabled`) VALUES
(1, 'Prop-Up Package 1', 'Prop-Up Packages', 8700.00, 'A new style!', '1740034969_Untitled design.png', 'enabled', 1);

-- --------------------------------------------------------

--
-- Table structure for table `services_to_events`
--

CREATE TABLE `services_to_events` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_details`
--

CREATE TABLE `staff_details` (
  `staff_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `approve_events`
--
ALTER TABLE `approve_events`
  ADD PRIMARY KEY (`approval_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_cart`
--
ALTER TABLE `event_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_request`
--
ALTER TABLE `event_request`
  ADD PRIMARY KEY (`event_id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `event_status_history`
--
ALTER TABLE `event_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `services_to_events`
--
ALTER TABLE `services_to_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `staff_details`
--
ALTER TABLE `staff_details`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `approve_events`
--
ALTER TABLE `approve_events`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_cart`
--
ALTER TABLE `event_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_request`
--
ALTER TABLE `event_request`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `event_status_history`
--
ALTER TABLE `event_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services_to_events`
--
ALTER TABLE `services_to_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_details`
--
ALTER TABLE `staff_details`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approve_events`
--
ALTER TABLE `approve_events`
  ADD CONSTRAINT `approve_events_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event_request` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `event_cart`
--
ALTER TABLE `event_cart`
  ADD CONSTRAINT `event_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_cart_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `event_request` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `event_request`
--
ALTER TABLE `event_request`
  ADD CONSTRAINT `event_request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `event_status_history`
--
ALTER TABLE `event_status_history`
  ADD CONSTRAINT `event_status_history_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event_request` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event_request` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `services_to_events`
--
ALTER TABLE `services_to_events`
  ADD CONSTRAINT `services_to_events_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event_request` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_to_events_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_details`
--
ALTER TABLE `staff_details`
  ADD CONSTRAINT `staff_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
