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

-- Create Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(11) UNIQUE NOT NULL,
    nid VARCHAR(12) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    balance DECIMAL(10,2) DEFAULT 0.00
);

-- Create Merchants Table
CREATE TABLE merchants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    merchant_number VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL
);

-- Create Transactions Table
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('send_money', 'receive_money', 'cash_in', 'cash_out', 'mobile_recharge', 'pay_bill') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    recipient_phone VARCHAR(11),
    merchant_number VARCHAR(50),
    phone VARCHAR(11),
    bill_type VARCHAR(50),
    provider VARCHAR(100),
    account_number VARCHAR(100),
    sender_phone VARCHAR(11),
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert Sample Users
INSERT INTO users (username, password, phone, nid, email, balance) VALUES
('kuresh', '$2y$10$8xZ6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Q', '01705395013', '123456789012', 'kureshreush@gmail.com', 10000.00),
('shifat', '$2y$10$8xZ6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Q', '01838073738', '111213141516', 'kuresh450@gmail.com', 5000.00),
('nazia', '$2y$10$8xZ6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Qz6Q', '01860491240', '313233343536', 'nazia@example.com', 7500.00);

-- Insert Sample Merchants
INSERT INTO merchants (merchant_number, name) VALUES
('M123456', 'Merchant One'),
('M789012', 'Merchant Two');

-- Insert Sample Transactions
INSERT INTO transactions (user_id, type, amount, recipient_phone, merchant_number, phone, bill_type, provider, account_number, sender_phone, created_at) VALUES
(1, 'cash_in', 5000.00, NULL, 'M123456', NULL, NULL, NULL, NULL, NULL, '2025-04-20 10:00:00'),
(1, 'send_money', 2000.00, '01838073738', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-20 12:00:00'),
(2, 'receive_money', 2000.00, NULL, NULL, NULL, NULL, NULL, NULL, '01705395013', '2025-04-20 12:00:00'),
(2, 'cash_out', 1000.00, NULL, 'M789012', NULL, NULL, NULL, NULL, NULL, '2025-04-21 09:00:00'),
(3, 'mobile_recharge', 500.00, NULL, NULL, '01860491240', NULL, NULL, NULL, NULL, '2025-04-21 11:00:00'),
(1, 'pay_bill', 1000.00, NULL, NULL, NULL, 'electricity', 'DPDC', 'E123456', NULL, '2025-04-22 08:00:00');