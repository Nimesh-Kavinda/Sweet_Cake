-- Sweet Cake Orders System Database Schema
-- Run this SQL to create the required tables for the orders functionality
-- Database name: 'cake' (as per existing config)

-- Create orders table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','completed') NOT NULL DEFAULT 'pending',
  `delivery_address` text,
  `customer_name` varchar(255),
  `customer_email` varchar(255),
  `customer_phone` varchar(20),
  `order_notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create order_items table
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11),
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create users table if it doesn't exist
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `phone` varchar(20),
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create cart table if it doesn't exist
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample users for testing
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`) VALUES
(1, 'John Doe', 'john@example.com', '123-456-7890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(2, 'Jane Smith', 'jane@example.com', '987-654-3210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(3, 'Mary Lee', 'mary@example.com', '555-123-4567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Insert sample orders for testing
INSERT IGNORE INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `delivery_address`, `customer_name`, `customer_email`, `customer_phone`, `order_notes`, `created_at`) VALUES
(1, 1, 1299.00, 'pending', '123 Main Street, City, State 12345', 'John Doe', 'john@example.com', '123-456-7890', 'Please ring the doorbell twice', '2025-06-10 10:30:00'),
(2, 1, 899.00, 'processing', '123 Main Street, City, State 12345', 'John Doe', 'john@example.com', '123-456-7890', NULL, '2025-06-09 14:15:00'),
(3, 1, 599.00, 'delivered', '123 Main Street, City, State 12345', 'John Doe', 'john@example.com', '123-456-7890', 'Birthday party cake', '2025-06-08 09:45:00'),
(4, 1, 1799.00, 'cancelled', '123 Main Street, City, State 12345', 'John Doe', 'john@example.com', '123-456-7890', 'Order cancelled by customer', '2025-06-07 16:20:00'),
(5, 2, 2499.00, 'shipped', '456 Oak Avenue, Town, State 67890', 'Jane Smith', 'jane@example.com', '987-654-3210', 'Wedding cake - handle with care', '2025-06-06 11:00:00');

-- Insert sample order items for testing
INSERT IGNORE INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `total_price`) VALUES
(1, 1, 1, 'Chocolate Dream Cake', 1, 1299.00, 1299.00),
(2, 2, 2, 'Vanilla Birthday Cake', 1, 899.00, 899.00),
(3, 3, 3, 'Red Velvet Cupcakes', 6, 99.00, 594.00),
(4, 4, 4, 'Wedding Cake Deluxe', 1, 1799.00, 1799.00),
(5, 5, 4, 'Wedding Cake Premium', 1, 2499.00, 2499.00),
(6, 1, 5, 'Chocolate Chip Cookies', 2, 149.00, 298.00);

-- Update order totals based on order items (recalculate for accuracy)
UPDATE `orders` o SET 
  `total_amount` = (
    SELECT COALESCE(SUM(oi.total_price), 0) 
    FROM `order_items` oi 
    WHERE oi.order_id = o.id
  );

COMMIT;
