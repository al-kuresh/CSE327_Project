-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2025 at 09:01 AM
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
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `provider` varchar(50) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `user_id`, `type`, `provider`, `account_number`, `amount`, `status`, `created_at`) VALUES
(1, 1, 'electricity', 'PowerCo', 'ELEC123', 200.00, 'paid', '2025-04-18 01:26:18'),
(2, 2, 'wifi', 'NetProvider', 'WIFI456', 300.00, 'pending', '2025-04-18 01:26:18');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `recipient` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `amount`, `recipient`, `description`, `created_at`) VALUES
(1, 1, 'cash_in', 5000.00, NULL, 'Initial cash in', '2025-04-18 01:26:18'),
(2, 1, 'send_money', 1000.00, '0987654321', 'Sent to Jane', '2025-04-18 01:26:18'),
(3, 2, 'mobile_recharge', 500.00, '0987654321', 'Recharge for self', '2025-04-18 01:26:18'),
(4, 2, 'pay_bill', 200.00, 'ELEC123', 'Electricity bill payment', '2025-04-18 01:26:18'),
(5, 1, 'send_money', 100.00, '01705395013', 'Sent to 01705395013', '2025-04-18 05:24:34'),
(6, 2, 'send_money', 200.00, '01838073738', 'Sent to 01838073738', '2025-04-18 05:27:30'),
(7, 1, 'send_money', 500.00, '01860491240', 'Sent to 01860491240', '2025-04-18 06:45:36'),
(8, 3, 'receive_money', 500.00, NULL, 'Received from user ID 1', '2025-04-18 06:45:36'),
(9, 1, 'send_money', 50.00, '01860491240', 'Sent to 01860491240', '2025-04-18 06:59:26'),
(10, 3, 'receive_money', 50.00, NULL, 'Received from 01838073738', '2025-04-18 06:59:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `nid` varchar(20) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `phone`, `nid`, `balance`, `created_at`) VALUES
(1, 'kuresh', '$2y$10$IkPOR5tV9G0bu202LmSlCe6/AqTCF9a2b/CXDkHba1wy0OyHeHw9u', '01838073738', '12345678910', 4350.00, '2025-04-18 01:26:18'),
(2, 'shifat', '$2y$10$SiaeA9bTeDRdGBuulAQ5husA5hF2ymBK2FIi/p5xgIzal7QHi2gOu', '01705395013', '111213141516', 2800.00, '2025-04-18 01:26:18'),
(3, 'Nazia', '$2y$10$3mXgePiDWkMM6b.TJUgNPu3y.Ns5hnpoix584VdHmm62UXEeuOlwS', '01860491240', '9876543210', 550.00, '2025-04-18 06:44:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD UNIQUE KEY `nid` (`nid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
