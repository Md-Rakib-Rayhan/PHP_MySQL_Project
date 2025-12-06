CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `email` varchar(255) UNIQUE,
  `password` varchar(255),
  `phone` varchar(255),
  `profile_image` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `admins` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `email` varchar(255) UNIQUE,
  `password` varchar(255),
  `role` enum(super_admin,moderator),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `professionals` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `profile_picture` varchar(255),
  `bio` text,
  `experience_years` int,
  `address` varchar(255),
  `city` varchar(255),
  `state` varchar(255),
  `country` varchar(255),
  `is_verified` boolean,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `professional_projects` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `professional_id` int,
  `project_title` varchar(255),
  `description` text,
  `image_url` varchar(255),
  `completed_date` date,
  `created_at` datetime
);

CREATE TABLE `services` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `professional_id` int,
  `service_name` varchar(255),
  `room_type` varchar(255),
  `base_price` decimal,
  `description` text,
  `created_at` datetime
);

CREATE TABLE `availability` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `professional_id` int,
  `available_date` date,
  `start_time` time,
  `end_time` time
);

CREATE TABLE `appointments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `professional_id` int,
  `appointment_date` date,
  `start_time` time,
  `end_time` time,
  `status` enum(pending,approved,rejected,completed,cancelled),
  `notes` text,
  `created_at` datetime
);

CREATE TABLE `reviews` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `professional_id` int,
  `rating` int,
  `review_text` text,
  `created_at` datetime
);

CREATE TABLE `review_reports` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `review_id` int,
  `reported_by` int,
  `reason` text,
  `status` enum(pending,reviewed,dismissed),
  `created_at` datetime
);

CREATE TABLE `quote_requests` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `professional_id` int,
  `room_type` varchar(255),
  `description` text,
  `estimated_budget` decimal,
  `status` enum(pending,responded,closed),
  `created_at` datetime
);

CREATE TABLE `messages` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `sender_id` int,
  `receiver_id` int,
  `message_text` text,
  `is_read` boolean,
  `created_at` datetime
);

CREATE TABLE `notifications` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `message` varchar(255),
  `type` varchar(255),
  `is_read` boolean,
  `created_at` datetime
);

CREATE TABLE `blog_posts` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `admin_id` int,
  `title` varchar(255),
  `content` text,
  `image_url` varchar(255),
  `created_at` datetime
);

CREATE TABLE `blog_comments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `post_id` int,
  `user_id` int,
  `comment_text` text,
  `created_at` datetime
);

CREATE TABLE `professional_reports` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `professional_id` int,
  `reported_by` int,
  `reason` text,
  `status` enum(pending,reviewed,dismissed),
  `created_at` datetime
);

CREATE TABLE `activity_logs` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `admin_id` int,
  `action` varchar(255),
  `description` text,
  `created_at` datetime
);

ALTER TABLE `professionals` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `professional_projects` ADD FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`);

ALTER TABLE `services` ADD FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`);

ALTER TABLE `availability` ADD FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`);

ALTER TABLE `appointments` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `appointments` ADD FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`);

ALTER TABLE `review_reports` ADD FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`);

ALTER TABLE `review_reports` ADD FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`);

ALTER TABLE `quote_requests` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `quote_requests` ADD FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`);

ALTER TABLE `messages` ADD FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

ALTER TABLE `messages` ADD FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

ALTER TABLE `notifications` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `blog_posts` ADD FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);

ALTER TABLE `blog_comments` ADD FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`);

ALTER TABLE `blog_comments` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `professional_reports` ADD FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`);

ALTER TABLE `professional_reports` ADD FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`);

ALTER TABLE `activity_logs` ADD FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);
