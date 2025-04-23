-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2025 at 10:26 AM
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
-- Database: `mobile_wallet`
--

-- --------------------------------------------------------

--
-- Table structure for table `merchants`
--

CREATE TABLE `merchants` (
  `id` int(11) NOT NULL,
  `merchant_number` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merchants`
--

INSERT INTO `merchants` (`id`, `merchant_number`, `name`) VALUES
(1, 'M123456', 'Merchant One'),
(2, 'M789012', 'Merchant Two');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('send_money','receive_money','cash_in','cash_out','mobile_recharge','pay_bill') NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `recipient_phone` varchar(11) DEFAULT NULL,
  `merchant_number` varchar(50) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `bill_type` varchar(50) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `sender_phone` varchar(11) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `description`, `amount`, `recipient_phone`, `merchant_number`, `phone`, `bill_type`, `provider`, `account_number`, `sender_phone`, `created_at`) VALUES
(1, 1, 'cash_in', NULL, 5000.00, NULL, 'M123456', NULL, NULL, NULL, NULL, NULL, '2025-04-20 10:00:00'),
(2, 1, 'send_money', NULL, 2000.00, '01838073738', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-20 12:00:00'),
(3, 2, 'receive_money', NULL, 2000.00, NULL, NULL, NULL, NULL, NULL, NULL, '01705395013', '2025-04-20 12:00:00'),
(4, 2, 'cash_out', NULL, 1000.00, NULL, 'M789012', NULL, NULL, NULL, NULL, NULL, '2025-04-21 09:00:00'),
(5, 3, 'mobile_recharge', NULL, 500.00, NULL, NULL, '01860491240', NULL, NULL, NULL, NULL, '2025-04-21 11:00:00'),
(6, 1, 'pay_bill', NULL, 1000.00, NULL, NULL, NULL, 'electricity', 'DPDC', 'E123456', NULL, '2025-04-22 08:00:00'),
(7, 1, 'receive_money', NULL, 500.00, NULL, NULL, NULL, NULL, NULL, NULL, '01705395013', '2025-04-23 11:54:44'),
(8, 2, 'send_money', NULL, 500.00, '01705395013', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 11:54:47'),
(9, 1, 'receive_money', NULL, 200.00, NULL, NULL, NULL, NULL, NULL, NULL, '01705395013', '2025-04-23 11:56:47'),
(10, 2, 'send_money', NULL, 200.00, '01705395013', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 11:56:49'),
(11, 2, 'receive_money', NULL, 700.00, NULL, NULL, NULL, NULL, NULL, NULL, '01838073738', '2025-04-23 12:39:16'),
(12, 1, 'send_money', NULL, 700.00, '01838073738', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 12:39:19'),
(13, 1, 'cash_in', NULL, 500.00, NULL, '0', NULL, NULL, NULL, NULL, NULL, '2025-04-23 12:41:50'),
(14, 1, '', 'Bill Payment: electricity via wallet', 500.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(15, 2, 'receive_money', NULL, 200.00, NULL, NULL, NULL, NULL, NULL, NULL, '01838073738', '2025-04-23 13:28:36'),
(16, 1, 'send_money', NULL, 200.00, '01838073738', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 13:28:41'),
(17, 2, 'mobile_recharge', 'Mobile Recharge: 01838073738 (grameenphone)', 50.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(18, 3, 'receive_money', NULL, 1000.00, NULL, NULL, NULL, NULL, NULL, NULL, '01956312441', '2025-04-23 14:09:30'),
(19, 1, 'send_money', NULL, 1000.00, '01956312441', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 14:09:35'),
(20, 3, 'receive_money', NULL, 500.00, NULL, NULL, NULL, NULL, NULL, NULL, '01956312441', '2025-04-23 14:10:41'),
(21, 1, 'send_money', NULL, 500.00, '01956312441', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 14:10:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `nid` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `phone`, `nid`, `email`, `balance`) VALUES
(1, 'kuresh', '$2y$10$afpCrus1ljTpz9QoELb4cuDwdy9RPq1OfNH0nILVkdOcn7Kn4AkTK', '01705395013', '123456789012', 'kureshreush@gmail.com', 7300.00),
(2, 'shifat', '$2y$10$bah0uA9AT6B1g6g.r.awduJ/4idBmo7LtltT18fA/3oGD1SfHAOzS', '01838073738', '111213141516', 'kuresh450@gmail.com', 5150.00),
(3, 'nazia', '$2y$10$8xZ6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Q', '01956312441', '313233343536', 'diya.nazia2@gmail.com', 9000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `merchants`
--
ALTER TABLE `merchants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `merchant_number` (`merchant_number`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `nid` (`nid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `merchants`
--
ALTER TABLE `merchants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
