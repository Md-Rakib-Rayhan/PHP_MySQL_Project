-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2025 at 11:56 PM
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
-- Table structure for table `contact_requests`
--

CREATE TABLE `contact_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `professional_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `contact_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','resolved') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `professional_services`
--

CREATE TABLE `professional_services` (
  `id` int(11) NOT NULL,
  `professional_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progress_updates`
--

CREATE TABLE `progress_updates` (
  `id` int(11) NOT NULL,
  `service_request_id` int(11) DEFAULT NULL,
  `update_description` text DEFAULT NULL,
  `update_date` datetime DEFAULT current_timestamp(),
  `price_update` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `service_request_id` int(11) DEFAULT NULL,
  `report_text` text DEFAULT NULL,
  `reported_at` datetime DEFAULT current_timestamp(),
  `status` enum('pending','resolved','dismissed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(8, 19, 3, 'x', '2025-12-20 04:49:53');

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
(18, 3, 1, NULL, 'juk', 'pending', NULL, 0.00, 0.00, NULL, NULL, '2025-12-20 04:41:42', '2025-12-20 04:41:42', 0, '', NULL, NULL, 44.00),
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
(3, 'rakib', 'Rakib', 'Rayhan', 'rakib@gmail.com', '012', '', 'company', 'Developer', '2025-12-03', '21232f297a57a5a743894a0e4a801fc3', 'img/users/CFyJwi3K1awUTKMWg3K6RVRUDEYsPflb.jpg', 'Client', '2025-12-11 00:30:56', '2025-12-11 00:34:51', '2025-12-20 04:41:32'),
(4, 'rayhan', '', '', 'rayhan@gmail.com', '', '', 'individual', '', '0000-00-00', '21232f297a57a5a743894a0e4a801fc3', 'img/users/fdb8cf53200be561ab8582944b49f3b8.jpg', 'Admin', '2025-12-17 00:13:45', '2025-12-17 00:13:45', '2025-12-19 22:08:31'),
(5, 'OP Boys', NULL, NULL, 'opboys@gmail.com', NULL, NULL, 'individual', NULL, NULL, '21232f297a57a5a743894a0e4a801fc3', NULL, 'Client', '2025-12-17 20:39:49', '2025-12-17 20:39:49', NULL),
(6, 'imran', '', '', 'imran@gmail.com', '', '', 'individual', '', '0000-00-00', '21232f297a57a5a743894a0e4a801fc3', '', 'Client', '2025-12-17 22:44:23', '2025-12-17 22:44:23', '2025-12-20 03:14:15'),
(7, 'jubayer', '', '', 'jubayer@gmail.com', '01929483542', '', 'individual', 'Interior Designer', '2025-12-01', '21232f297a57a5a743894a0e4a801fc3', 'img/users/bbef2c55cb26ddc56bfde49dc25b33b8.jpg', 'Professionals', '2025-12-17 22:45:45', '2025-12-17 22:45:45', NULL),
(9, 'rony', '', '', 'rony@gmail.com', '', '', 'individual', 'Marketer', '2025-12-11', 'c3284d0f94606de1fd2af172aba15bf3', 'img/users/b1f4921c60103a40450f3675ee8414ab.jpg', 'Professionals', '2025-12-17 23:06:30', '2025-12-17 23:06:30', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_requests`
--
ALTER TABLE `contact_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `professional_id` (`professional_id`);

--
-- Indexes for table `professionals`
--
ALTER TABLE `professionals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `professional_services`
--
ALTER TABLE `professional_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `professional_id` (`professional_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `progress_updates`
--
ALTER TABLE `progress_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_request_id` (`service_request_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_request_id` (`service_request_id`);

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
-- AUTO_INCREMENT for table `contact_requests`
--
ALTER TABLE `contact_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `professionals`
--
ALTER TABLE `professionals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `professional_services`
--
ALTER TABLE `professional_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress_updates`
--
ALTER TABLE `progress_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact_requests`
--
ALTER TABLE `contact_requests`
  ADD CONSTRAINT `contact_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `contact_requests_ibfk_2` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`);

--
-- Constraints for table `professionals`
--
ALTER TABLE `professionals`
  ADD CONSTRAINT `professionals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `professional_services`
--
ALTER TABLE `professional_services`
  ADD CONSTRAINT `professional_services_ibfk_1` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`),
  ADD CONSTRAINT `professional_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `progress_updates`
--
ALTER TABLE `progress_updates`
  ADD CONSTRAINT `progress_updates_ibfk_1` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`);

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
