-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 04:25 PM
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
-- Database: `smart_step_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `product_id`, `name`, `description`, `price`, `discount_price`, `quantity`, `image`, `user_id`) VALUES
(9, 4, 'airjorden', 'good shoes', 5000.00, 100.00, 1, 'uploads/sh.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `current_stock` int(11) NOT NULL,
  `required_stock` int(11) NOT NULL,
  `unit_type` varchar(50) DEFAULT NULL,
  `min_required_stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `material_name`, `current_stock`, `required_stock`, `unit_type`, `min_required_stock`) VALUES
(3, 'Laces', 100, 150, 'Meter(Sq)', 100),
(8, 'Leather', 400, 0, 'meters', 100),
(9, 'pPlastic', 200, 0, 'Kg', 150),
(10, 'Sole', 400, 0, 'pices', 300);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_usage`
--

CREATE TABLE `inventory_usage` (
  `id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `quantity_used` int(11) NOT NULL,
  `wastage_percentage` decimal(5,2) NOT NULL,
  `usage_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `size`, `quantity`, `total_price`, `status`, `created_at`) VALUES
(1, 2, 1, NULL, 1, 149.99, 'Completed', '2025-04-08 06:35:41'),
(2, 2, 2, NULL, 1, 179.99, 'Completed', '2025-04-08 06:36:29'),
(3, 2, 2, NULL, 1, 179.99, 'Completed', '2025-04-08 06:39:56'),
(4, 2, 4, NULL, 3, 300.00, 'Completed', '2025-04-10 02:14:51'),
(5, 5, 4, NULL, 1, 100.00, 'Completed', '2025-04-10 02:34:19'),
(6, 6, 6, NULL, 1, 10.00, 'Completed', '2025-04-10 02:40:16'),
(7, 2, 4, NULL, 1, 100.00, 'Completed', '2025-04-10 09:40:13'),
(8, 2, 4, NULL, 1, 100.00, 'Completed', '2025-04-11 00:35:16'),
(9, 5, 2, NULL, 1, 179.99, 'Completed', '2025-04-15 06:19:41'),
(10, 5, 2, NULL, 1, 179.99, 'Completed', '2025-04-15 06:32:39'),
(11, 5, 11, NULL, 2, 998.00, 'Completed', '2025-04-15 12:01:52'),
(12, 5, 1, NULL, 1, 149.99, 'Pending', '2025-04-16 02:35:59'),
(13, 5, 1, NULL, 5, 749.95, 'Pending', '2025-04-16 09:32:02'),
(14, 5, 1, NULL, 4, 599.96, 'Pending', '2025-04-16 09:41:27'),
(15, 5, 2, NULL, 1, 179.99, 'Pending', '2025-04-16 09:49:10'),
(16, 5, 2, NULL, 2, 359.98, 'Pending', '2025-04-16 09:52:13'),
(17, 5, 2, NULL, 2, 359.98, 'Pending', '2025-04-16 10:00:40'),
(18, 5, 5, NULL, 4, 800.00, 'Pending', '2025-04-16 10:02:39'),
(19, 5, 1, NULL, 5, 749.95, 'Pending', '2025-04-16 10:06:26'),
(20, 5, 12, NULL, 6, 17994.00, 'Pending', '2025-04-16 10:09:41'),
(21, 5, 1, NULL, 2, 299.98, 'Pending', '2025-04-16 10:18:22'),
(22, 5, 1, NULL, 2, 299.98, 'Pending', '2025-04-16 10:45:41');

-- --------------------------------------------------------

--
-- Table structure for table `production`
--

CREATE TABLE `production` (
  `id` int(11) NOT NULL,
  `shoe_model` varchar(255) NOT NULL,
  `quantity_produced` int(11) NOT NULL,
  `production_time` int(11) NOT NULL,
  `completion_status` enum('On Time','Delayed') NOT NULL,
  `production_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_schedule`
--

CREATE TABLE `production_schedule` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `shoe_type` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `priority` enum('High','Medium','Low') NOT NULL,
  `status` enum('Scheduled','In Progress','Quality Check','Ready for Dispatch') NOT NULL DEFAULT 'Scheduled',
  `estimated_completion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_schedule`
--

INSERT INTO `production_schedule` (`id`, `order_id`, `customer_name`, `shoe_type`, `quantity`, `order_date`, `priority`, `status`, `estimated_completion`) VALUES
(1, 'ORD1234', 'John Doe', 'Sneakers', 10, '2025-03-14', 'High', 'In Progress', '2025-03-16 14:00:00'),
(2, 'ORD1235', 'Jane Smith', 'Boots', 5, '2025-03-13', 'Medium', 'Scheduled', '2025-03-18 10:00:00'),
(3, 'ORD1236', 'Alice Brown', 'Sandals', 20, '2025-03-12', 'Low', 'Scheduled', '2025-03-20 12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `reviews` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 50,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `discount_price`, `rating`, `reviews`, `image`, `quantity`, `size`) VALUES
(1, 'Nike Air Zoom Pegasus 39', 'Men\'s Road Running Shoes', 129.99, 149.99, 5, 1280, 'uploads/s4.jpg', 68, 7),
(2, 'Adidas Ultraboost', 'High-performance running shoes', 159.99, 179.99, 5, 1050, 'uploads/s5.jpg', 62, 8),
(3, 'Puma RS-X', 'Casual sneakers with a retro style', 500.99, 109.99, 4, 750, 'uploads/s6.jpg', 69, 9),
(4, 'airjorden', 'good shoes', 5000.00, 100.00, 4, 12, 'uploads/sh.jpg', 70, 7),
(5, '\r\nRed Tape', 'Women Comfort Insole Sneakers', 300.00, 200.00, 4, 45, 'uploads/red.png', 43, 8),
(6, 'Us polo canvas  ', 'Best sneakers for summer.', 1452.00, 500.00, 4, 14558, 'uploads/s1.jpg', 99, 7),
(11, 'Nike covert', 'God comfortable all white sneaker for daily wear.', 1000.00, 499.00, 5, 123, 'uploads/s3.jpg', 98, 7),
(12, 'Gucci women Heel', 'Shoetopia Block Heeled Sandals with Buckle Fastening For Girls', 5999.00, 2999.00, 4, 5578, 'uploads/w2', 74, 6),
(13, 'Prose Women sandal', 'Embellished Heels for Girls for Festivities', 3599.00, 2499.00, 5, 723, 'uploads/w1', 156, 7),
(14, 'Redtape classic', 'Very comfortable shoe for daily wear', 600.00, 300.00, 5, 1000, 'uploads/1.jpg', 51, 9);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `shoe_model` varchar(255) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `revenue` decimal(10,2) NOT NULL,
  `sale_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Customer','Manager','Admin') NOT NULL DEFAULT 'Customer',
  `status` enum('Active','Suspended','Deleted') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'Prince kumar', 'prince1p100@gmail.com', '$2y$10$Kuwb1pWDkoXEJNTFC7PdN.5e46C0rDKkHPkGkDQv66DehFQElaljS', 'Manager', '', '2025-03-15 16:25:55'),
(2, 'prince', 'prince966160@gmail.com', '$2y$10$ICht9Ip9JObelSdgR7kere8t4LQVJJKLloNf/KXxkTkeKRk831sji', 'Customer', 'Active', '2025-03-27 05:16:06'),
(5, 'Harsh Kumar', 'harshkr.agrl@gmail.com', '$2y$10$pK6ndKvDOetZhPPz5xY6B.tjJ.JIavZbJXlTmvQuw43BCSHS.7EHy', 'Customer', '', '2025-03-27 05:24:35'),
(6, 'sunny kumar', 'sunnybhardwaj7549@gmail.com', '$2y$10$NN1/cyMDC7IkrooUKeVEJOJXO36l6SUqU4KBtnhyMdAzIiurwN38e', 'Customer', '', '2025-04-10 06:08:33'),
(8, 'Harsh Agrawal', 'hyper123nova@gmail.com', '$2y$10$mpCKaQ0IS1bY09vc9y5NNe2h.ZI4SnzgWMbhVw14iNFJHumEmKK9G', 'Manager', 'Active', '2025-04-16 09:56:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_usage`
--
ALTER TABLE `inventory_usage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `production`
--
ALTER TABLE `production`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_schedule`
--
ALTER TABLE `production_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_usage`
--
ALTER TABLE `inventory_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `production`
--
ALTER TABLE `production`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_schedule`
--
ALTER TABLE `production_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
