-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 04:43 AM
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
-- Database: `cake`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(100) NOT NULL,
  `user_id` int(100) DEFAULT NULL,
  `product_id` int(100) DEFAULT NULL,
  `quantity` int(100) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(1, 3, 3, 1, '2025-05-23 15:19:13'),
(19, 3, 2, 1, '2025-05-23 16:34:24');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`) VALUES
(1, 'Flowers'),
(2, 'Plants'),
(3, 'Gift Bouquets'),
(4, 'Bun');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `text` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `subject`, `text`, `created_at`) VALUES
(3, 6, 'Deshani', 'anuadmin@gmail.com', 'rew', 'ewrewrwerewrwer', '2025-06-12 02:24:51');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','processing','shipped','complete','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` enum('cod','pickup','','') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`, `payment_method`, `phone`, `shipping_address`) VALUES
(2, 2, '2025-05-23 23:42:59', 9000.00, 'complete', 'cod', '0778525115', 'Pallegama,Ganthuna\nKegalle\nSabaragamuwa Province\nPostal Code: 71222\nSri Lanka'),
(3, 2, '2025-05-23 23:46:27', 3000.00, 'shipped', 'pickup', '0778525115', 'Pallegama,Ganthuna\'Kegalle\'Sabaragamuwa\'71222\'Sri Lanka'),
(4, 2, '2025-05-24 00:25:22', 4500.00, 'complete', 'cod', '0778525522', 'Et dolore occaecat f\'Consequuntur volupta\'Uva\'71222\'Sri Lanka'),
(6, 2, '2025-05-24 00:32:24', 200.00, 'processing', 'cod', '0715637917', 'Veniam rerum pariat\'Voluptatibus dolorum\'Southern\'78555\'Sri Lanka'),
(7, 2, '2025-05-24 00:32:52', 400.00, 'complete', 'pickup', '0778525522', 'In ad dicta id solut\'Kegalle\'Eastern\'71222\'Sri Lanka'),
(9, 6, '2025-06-11 20:32:08', 233.00, 'pending', 'cod', '0761620691', 'sadasdsadas'),
(11, 6, '2025-06-11 20:45:17', 693.00, 'pending', 'cod', '0715637917', 'jgjhnmbnhfvhgh'),
(12, 6, '2025-06-12 00:25:07', 233.00, 'processing', 'cod', '0778525522', 'sadsadsadsadasdasd'),
(13, 6, '2025-06-12 07:55:12', 10233.00, 'pending', 'cod', '0741718855', 'qweqweqewqeqeqeqe'),
(14, 5, '2025-06-12 08:11:34', 233.00, 'pending', 'cod', '0778525522', 'hdfssggjhgfhg');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(2, 2, 3, 3, 2500.00),
(3, 2, 1, 1, 1500.00),
(4, 3, 1, 2, 1500.00),
(5, 4, 1, 3, 1500.00),
(6, 5, 2, 3, 200.00),
(7, 6, 2, 1, 200.00),
(8, 7, 2, 2, 200.00),
(11, 9, 4, 1, 230.00),
(13, 11, 4, 3, 230.00),
(14, 12, 4, 1, 230.00),
(15, 13, 4, 1, 230.00),
(16, 13, 3, 4, 2500.00),
(17, 14, 4, 1, 230.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(40) NOT NULL,
  `category_id` int(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(40) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `qty`, `image`, `description`, `created_at`) VALUES
(2, 1, 'Ocid', 200.00, 14, 'prod_6830717a5118d0.35130538.png', 'sdlkaskdlksaldksalkdlskadlksaldklsakdlsakdlksaldklsakldkslakdlskdlsakdlskaldklsakdlsakdlksdlsakl', '2025-05-23 13:00:42'),
(3, 3, 'Vibrant bouquet of yellow roses', 2500.00, 7, 'prod_6830804e4b5314.03651282.jpg', 'A hand holds a vibrant bouquet of yellow roses against a white background, showcasing the flowers\' lush green leaves. Ideal for gifting or celebrations.', '2025-05-23 14:03:58'),
(4, 1, 'Stephen Gilliam', 230.00, 775, '68498a190a648.png', 'Commodo voluptatem', '2025-06-11 13:52:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(100) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(2, 'UserTestHash', 'user@gmail.com', '$2y$10$0kGNf/OU4ar57uy9ID8rhet1J4VDQefmI3VdzPwsKCIy8z7EzX5B2', '2025-05-22 08:35:20', 'user'),
(3, 'admin', 'admin@gmail.com', '$2y$10$3CdfpfxWB5qtFiQ7.8b.meS/2RAQiCqvNstNnlBMb/UCiYlsU6yd2', '2025-05-22 09:10:39', 'admin'),
(4, 'TestUser', 'test@gmail.com', '$2y$10$h/rRqfsdIAm961fVC1gBs.gSVXYt5nBiEU2/FZgEQbW5CYhwEsTAi', '2025-05-22 15:15:05', 'user'),
(5, 'Anuradha', 'anuuser@gmail.com', '$2y$10$tpOc9SUGtnmJU07VbVBV5uiUKdEbbJSJ5deb8yOFOM/VifwLHMUGK', '2025-06-11 11:57:04', 'user'),
(6, 'Deshani', 'anuadmin@gmail.com', '$2y$10$hOEIGmPUMBdSL4oH5Rl7ie9pQDBqAGt0a4N/uIfsqT/8AUvtj1Yei', '2025-06-11 11:57:58', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
