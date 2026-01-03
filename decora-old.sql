-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2026 at 01:32 AM
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
-- Database: `decora`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(11, 6, 2, 1, '2025-12-31 23:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` enum('pending','accepted','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `invoice_token` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `phone`, `address`, `notes`, `total_amount`, `payment_method`, `status`, `created_at`, `invoice_token`) VALUES
(1, 6, 'imran', '142442', 'df', 'sdf', 120.00, 'paypal', 'accepted', '2025-12-31 22:37:11', NULL),
(2, 4, 'Rayhan', '01524', 'sdf', 'sdfdf', 366000.00, 'card', 'pending', '2025-12-31 22:45:52', NULL),
(3, 3, 'rakib', '015241', 'sdfa', 'asdfasdf', 190.00, 'card', 'pending', '2025-12-31 23:58:48', NULL),
(4, 3, 'rakib', '214313', 'sdfds', 'sdfsd', 78120.00, 'card', 'pending', '2026-01-01 00:09:13', NULL),
(5, 3, 'rakib', '01245', 'asdf', 'sdfsdf', 120.00, 'card', 'pending', '2026-01-01 00:13:29', NULL),
(6, 3, 'rakib', '01245', 'asdfasdf', 'asdfdf', 40.00, 'paypal', 'pending', '2026-01-01 00:14:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 1, 120.00),
(2, 2, 9, 1, 54000.00),
(3, 2, 10, 2, 125000.00),
(4, 2, 7, 1, 62000.00),
(5, 3, 1, 2, 75.00),
(6, 3, 5, 1, 40.00),
(7, 4, 2, 1, 120.00),
(8, 4, 8, 1, 78000.00),
(9, 5, 2, 1, 120.00),
(10, 6, 5, 1, 40.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` enum('pending','accepted','shipped','delivered','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `total_amount`, `payment_method`, `status`, `created_at`, `order_status`) VALUES
(2, 6, 120.00, 'paypal', 'completed', '2025-12-31 22:37:11', 'delivered'),
(3, 4, 366000.00, 'card', 'completed', '2025-12-31 22:45:52', 'pending'),
(4, 3, 190.00, 'card', 'completed', '2025-12-31 23:58:48', 'pending'),
(5, 3, 78120.00, 'card', 'completed', '2026-01-01 00:09:13', 'pending'),
(6, 3, 120.00, 'card', 'completed', '2026-01-01 00:13:29', 'pending'),
(7, 3, 40.00, 'paypal', 'completed', '2026-01-01 00:14:42', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `description`, `category`, `price`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'Modern Pendant Lamp', 'Stylish hanging lamp for living rooms or dining areas', 'Lighting', 75.00, 'img/products/pendant-lamp.jpg', '2025-12-31 21:31:14', '2025-12-31 21:31:14'),
(2, 'Minimalist Coffee Table', 'Oak wood coffee table with clean lines', 'Furniture', 120.00, 'img/products/coffee-table.jpg', '2025-12-31 21:31:14', '2025-12-31 21:31:14'),
(3, 'Decorative Wall Mirror', 'Round wall mirror with thin metal frame', 'Decor', 60.00, 'img/products/wall-mirror.jpg', '2025-12-31 21:31:14', '2025-12-31 21:31:14'),
(4, 'Ergonomic Office Chair', 'Comfortable chair for home office use', 'Furniture', 200.00, 'img/products/office-chair.jpg', '2025-12-31 21:31:14', '2025-12-31 21:31:14'),
(5, 'Luxury Bath Towels Set', 'Set of 4 premium cotton towels', 'Bathroom', 40.00, 'img/products/towel-set.jpg', '2025-12-31 21:31:14', '2025-12-31 21:31:14'),
(6, 'Luxury Sofa Set', 'Modern luxury sofa perfect for living room interiors', 'Living Room', 85000.00, 'img/products/sofa.jpg', '2025-12-31 21:37:38', '2025-12-31 21:37:38'),
(7, 'Wooden Dining Table', 'Premium wooden dining table with elegant finish', 'Dining', 62000.00, 'img/products/dining.jpg', '2025-12-31 21:37:38', '2025-12-31 21:37:38'),
(8, 'Modern Bed Design', 'Stylish king size bed with storage', 'Bedroom', 78000.00, 'img/products/bed.jpg', '2025-12-31 21:37:38', '2025-12-31 21:37:38'),
(9, 'Office Workstation', 'Minimal office workstation for productivity', 'Office', 54000.00, 'img/products/office.jpg', '2025-12-31 21:37:38', '2025-12-31 21:37:38'),
(10, 'Modular Kitchen Setup', 'Complete modular kitchen solution', 'Kitchen', 125000.00, 'img/products/kitchen.jpg', '2025-12-31 21:37:38', '2025-12-31 21:37:38'),
(11, 'Ceiling Light Decor', 'Decorative ceiling lighting for modern homes', 'Lighting', 12000.00, 'img/products/light.jpg', '2025-12-31 21:37:38', '2025-12-31 21:37:38'),
(12, 'Wardrobe Interior', 'Custom wardrobe interior design', 'Bedroom', 68000.00, 'img/products/wardrobe.jpg', '2025-12-31 21:37:38', '2025-12-31 21:37:38'),
(13, 'TV Unit Design', 'Modern wall-mounted TV unit', 'Living Room', 45000.00, 'img/products/tvunit.jpg', '2025-12-31 21:37:38', '2025-12-31 21:37:38'),
(14, 'Robot doll', 'Robot doll, suitable computer desk', 'Doll', 121.00, 'img/products/fdb8cf53200be561ab8582944b49f3b8.jpg', '2026-01-01 03:04:38', '2026-01-01 03:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `professionals`
--

CREATE TABLE `professionals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professionals`
--

INSERT INTO `professionals` (`id`, `user_id`, `name`, `bio`, `experience_years`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 9, 'rony', 'I Am Rony i am working in marketing sector for 5 year, i have provide service to many of my client', 5, 1, '2025-12-21 11:53:52', '2025-12-21 11:53:52'),
(2, 11, 'Alice Green', 'Expert in modern living room designs with 8 years of experience.', 8, 1, '2025-12-21 12:07:19', '2025-12-21 12:07:19'),
(3, 12, 'Bob White', 'Specializes in sustainable architecture and interior solutions.', 10, 1, '2025-12-21 12:07:19', '2025-12-21 12:07:19'),
(4, 13, 'Clara Black', 'Creative designer focusing on contemporary home interiors.', 6, 0, '2025-12-21 12:07:19', '2025-12-21 12:07:19'),
(5, 14, 'Daniel Blue', 'Professional decorator for offices and commercial spaces.', 12, 1, '2025-12-21 12:07:19', '2025-12-21 12:07:19'),
(6, 15, 'Eva Brown', 'Passionate about luxury interior designs and client satisfaction.', 7, 0, '2025-12-21 12:07:19', '2025-12-21 12:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `service_request_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `service_request_id`, `rating`, `review_text`, `created_at`) VALUES
(8, 19, 3, 'x', '2025-12-20 04:49:53'),
(9, 18, 4, 'nice service', '2025-12-21 10:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `avg_price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `description`, `avg_price`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'Living Room Design', 'Complete redesign of living room space including furniture arrangement, wall colors, and decoration.', 1500.00, 'img/services/living_room.jpg', '2025-12-18 00:39:36', '2025-12-18 02:54:58'),
(2, 'Kitchen Makeover', 'Renovation of kitchen with modern appliances, new cabinetry, and updated lighting.', 2500.00, 'img/services/light-blue-modern-kitchen.jpg', '2025-12-18 00:39:36', '2025-12-18 02:55:12'),
(3, 'Office Space Design', 'Design and layout of office spaces, including desk arrangement, lighting, and decor.', 1800.00, 'img/services/office.jpg', '2025-12-18 00:39:36', '2025-12-18 02:55:19'),
(4, 'Bathroom Remodeling', 'Full bathroom renovation including tiling, fixtures, and design customization.', 1200.00, 'img/services/Bathroom.jpg', '2025-12-18 00:39:36', '2025-12-18 02:55:27'),
(5, 'Bedroom Design', 'Transform your bedroom with modern furniture, stylish wall colors, and ambient lighting.', 1300.00, 'img/services/middle-class-indian-bedroom-design.jpg', '2025-12-18 02:58:14', '2025-12-18 02:58:14'),
(6, 'Lighting Design', 'Specialized lighting design to enhance the ambiance of your home or office, including installation of light fixtures.', 8000.00, 'img/services/lighting.png', '2025-12-18 03:01:18', '2025-12-18 03:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `professional_id` int(11) DEFAULT NULL,
  `describe` text DEFAULT NULL,
  `status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
  `price` decimal(10,2) DEFAULT NULL,
  `advance_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `custom_service` tinyint(1) DEFAULT 0,
  `custom_service_description` text DEFAULT NULL,
  `expected_duration` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `expected_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `user_id`, `service_id`, `professional_id`, `describe`, `status`, `price`, `advance_price`, `due_price`, `start_date`, `end_date`, `created_at`, `updated_at`, `custom_service`, `custom_service_description`, `expected_duration`, `duration`, `expected_price`) VALUES
(17, 3, 1, NULL, 'dddddddd', 'pending', NULL, 0.00, 0.00, NULL, NULL, '2025-12-20 04:39:35', '2025-12-20 04:39:35', 0, '', NULL, NULL, 11111.00),
(18, 3, 1, NULL, 'juk', 'completed', 44.00, 0.00, 44.00, NULL, '2025-12-21 10:40:09', '2025-12-20 04:41:42', '2025-12-21 10:40:09', 0, '', 0, NULL, NULL),
(19, 3, 2, NULL, 'hhhhhhhh', 'completed', 220020.00, 500.00, 219520.00, '2025-12-20 04:46:56', '2025-12-20 04:47:41', '2025-12-20 04:43:34', '2025-12-20 04:47:41', 0, '', 5, 0, NULL);

--
-- Triggers `service_requests`
--
DELIMITER $$
CREATE TRIGGER `before_service_request_delete` BEFORE DELETE ON `service_requests` FOR EACH ROW BEGIN
    DELETE FROM reviews 
    WHERE service_request_id = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `company_or_individual` enum('individual','company') DEFAULT 'individual',
  `profession` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Client','Professionals') DEFAULT 'Client',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `first_name`, `last_name`, `email`, `phone`, `address`, `company_or_individual`, `profession`, `birthday`, `password`, `profile_pic`, `role`, `created_at`, `updated_at`, `last_login`) VALUES
(3, 'rakib', 'Rakib', 'Rayhan', 'rakib@gmail.com', '012', '', 'company', 'Developer', '2025-12-03', '21232f297a57a5a743894a0e4a801fc3', 'img/users/CFyJwi3K1awUTKMWg3K6RVRUDEYsPflb.jpg', 'Client', '2025-12-11 00:30:56', '2025-12-11 00:34:51', '2026-01-01 05:58:31'),
(4, 'rayhan', '', '', 'rayhan@gmail.com', '', '', 'individual', '', '0000-00-00', '21232f297a57a5a743894a0e4a801fc3', 'img/users/fdb8cf53200be561ab8582944b49f3b8.jpg', 'Admin', '2025-12-17 00:13:45', '2025-12-17 00:13:45', '2026-01-01 04:41:23'),
(5, 'OP Boys', NULL, NULL, 'opboys@gmail.com', NULL, NULL, 'individual', NULL, NULL, '21232f297a57a5a743894a0e4a801fc3', NULL, 'Client', '2025-12-17 20:39:49', '2025-12-17 20:39:49', NULL),
(6, 'imran', '', '', 'imran@gmail.com', '', '', 'individual', '', '0000-00-00', '21232f297a57a5a743894a0e4a801fc3', '', 'Client', '2025-12-17 22:44:23', '2025-12-17 22:44:23', '2026-01-01 05:19:41'),
(9, 'Rony', '', '', 'rony@gmail.com', '', '', 'individual', 'Marketer', '2025-12-11', 'c3284d0f94606de1fd2af172aba15bf3', 'img/users/worker1.webp', 'Professionals', '2025-12-17 23:06:30', '2025-12-17 23:06:30', NULL),
(11, 'Alice Green', 'Alice', 'Green', 'alice@gmail.com', '01700000001', '', 'individual', 'Interior Designer', '1990-05-15', '21232f297a57a5a743894a0e4a801fc3', 'img/users/worker2.jpg', 'Professionals', '2025-12-21 12:07:19', '2025-12-21 12:07:19', NULL),
(12, 'Bob White', 'Bob', 'White', 'bob@gmail.com', '01700000002', '', 'individual', 'Architect', '1985-08-22', '21232f297a57a5a743894a0e4a801fc3', 'img/users/worker3.jpg', 'Professionals', '2025-12-21 12:07:19', '2025-12-21 12:07:19', NULL),
(13, 'Clara Black', 'Clara', 'Black', 'clara@gmail.com', '01700000003', '', 'individual', 'Designer', '1992-03-10', '21232f297a57a5a743894a0e4a801fc3', 'img/users/worker6.jpg', 'Professionals', '2025-12-21 12:07:19', '2025-12-21 12:07:19', NULL),
(14, 'Daniel Blue', 'Daniel', 'Blue', 'daniel@gmail.com', '01700000004', '', 'individual', 'Decorator', '1988-11-30', '21232f297a57a5a743894a0e4a801fc3', 'img/users/b1f4921c60103a40450f3675ee8414ab.jpg', 'Professionals', '2025-12-21 12:07:19', '2025-12-21 12:07:19', NULL),
(15, 'Eva Brown', 'Eva', 'Brown', 'eva@gmail.com', '01700000005', '', 'individual', 'Interior Designer', '1995-07-05', '21232f297a57a5a743894a0e4a801fc3', 'img/users/bbef2c55cb26ddc56bfde49dc25b33b8.jpg', 'Professionals', '2025-12-21 12:07:19', '2025-12-21 12:07:19', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professionals`
--
ALTER TABLE `professionals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_request_id` (`service_request_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `professional_id` (`professional_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `professionals`
--
ALTER TABLE `professionals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `professionals`
--
ALTER TABLE `professionals`
  ADD CONSTRAINT `professionals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`);

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `service_requests_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `service_requests_ibfk_3` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
