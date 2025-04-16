-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 08:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pregnancy_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_comments`
--

CREATE TABLE `community_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `mother_id` int(11) DEFAULT NULL,
  `midwife_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_likes`
--

CREATE TABLE `community_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `mother_id` int(11) DEFAULT NULL,
  `midwife_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `mother_id` int(11) DEFAULT NULL,
  `midwife_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `pregnancy_week` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `complication` text DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `health_tracker`
--

CREATE TABLE `health_tracker` (
  `id` int(11) NOT NULL,
  `mother_id` int(11) NOT NULL,
  `water_cups` int(11) DEFAULT 0,
  `sleep_hours` int(11) DEFAULT 0,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_tracker`
--

INSERT INTO `health_tracker` (`id`, `mother_id`, `water_cups`, `sleep_hours`, `date`) VALUES
(1, 46, 0, 8, '2025-04-11'),
(2, 46, 3, 0, '2025-04-11'),
(3, 46, 5, 0, '2025-04-11'),
(4, 46, 5, 0, '2025-04-11'),
(5, 46, 0, 8, '2025-04-11'),
(6, 46, 3, 3, '2025-04-12'),
(7, 46, 0, 6, '2025-04-14'),
(8, 46, 0, 6, '2025-04-14'),
(9, 46, 0, 6, '2025-04-14'),
(10, 46, 4, 0, '2025-04-14');

-- --------------------------------------------------------

--
-- Table structure for table `invitation_tokens`
--

CREATE TABLE `invitation_tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invitation_tokens`
--

INSERT INTO `invitation_tokens` (`id`, `token`, `created_at`, `expires_at`, `used`) VALUES
(1, '5ef67b100002ab757d6ab01cac8ad7e51b47a849a3b2655c2795dd7faa748a14', '2025-04-11 20:12:54', '2025-04-12 19:12:54', 1),
(2, '75dba654b8c31468b02e0a0eecfd6e68b84ea219e565b914b613ecd2d9f1aea8', '2025-04-11 22:54:51', '2025-04-12 21:54:51', 0),
(3, '591bfe784ae6c6949d2a537fa56410526eeb3c51459ad5686b13528a5f0c423d', '2025-04-11 22:54:56', '2025-04-12 21:54:56', 0);

-- --------------------------------------------------------

--
-- Table structure for table `meals`
--

CREATE TABLE `meals` (
  `id` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `complication` text DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `recipe` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meals`
--

INSERT INTO `meals` (`id`, `week`, `complication`, `title`, `description`, `recipe`, `image`, `created_by`) VALUES
(2, 5, 'normal', 'efewfhhhh', 'fergregshami', 'gggeooo', NULL, 10),
(3, 5, 'normal', 'jgjgjguj', 'gjgjgghhhh', 'bbjkhuhgutgoo', NULL, 10);

-- --------------------------------------------------------

--
-- Table structure for table `medical_staff`
--

CREATE TABLE `medical_staff` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('doctor','midwife') NOT NULL,
  `description` text NOT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_staff`
--

INSERT INTO `medical_staff` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `description`, `verified`, `created_at`) VALUES
(1, 'sali', 'sam', 'hanadi123@gmail.com', '$2y$10$ZBeSN6f6nFgxGtuFjqqbM.fGh0ifGUBh9HGPNHTmCwFAFeIn70a.y', 'doctor', 'ddddd', 1, '2025-03-27 07:24:37'),
(10, 'ranim', 'olleike', 'ranimolleikbusiness@gmail.com', '$2y$10$GzfzXvi60bYxDl89OdRDFOsf9Q6bybI3mSELIZ8h/H5G0jT7uXr0C', 'midwife', 'fjekfjefkef', 1, '2025-04-11 20:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `sender_role` enum('mother','midwife','doctor') NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mothers`
--

CREATE TABLE `mothers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pregnancy_start` date NOT NULL,
  `complications` text DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pregnancy_week` int(11) DEFAULT NULL,
  `verification_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mothers`
--

INSERT INTO `mothers` (`id`, `first_name`, `last_name`, `email`, `password`, `pregnancy_start`, `complications`, `is_verified`, `created_at`, `pregnancy_week`, `verification_code`) VALUES
(24, 'ranim', 'eee', 'ranoll132@gmail.com', '$2y$10$agOe/sGjlzJAgaUsG.HlP.3IyQPgwM0r.fvrXClSIbLwOxgVMiOmC', '2025-02-11', '[\"high_blood_pressure\"]', 0, '2025-03-13 21:02:51', 4, '07eebe16879a4d36e9175029149bb9d2'),
(25, 'ranim', 'olleike', 'saras12@gmail.com', '$2y$10$pfoMz7idUp4RMeCSdmluKed.7uNOgGqkMkH4.3AB406Tv3WjGLjF2', '2025-03-03', '[\"other\",\"bad\"]', 0, '2025-03-13 23:08:59', 1, '9b0d94d80d1450945a27ed045b02400e'),
(27, 'eerr', 'same', 'saras312@gmail.com', '$2y$10$VGUQrB9R5vH4xNNtVXsvGOXtoevLBh1mad.ctJ4dTUNdD8egpD6H2', '2025-03-03', '[\"normal\"]', 0, '2025-03-14 22:31:41', 1, '616db112d02adc3cd39e71133643d33a'),
(46, 'ranim', 'shar', 'ranimolleik2004@gmail.com', '$2y$10$N/2rqlgvz3OSNgxhnRZR4O8c9uGJAJsAiqMmp53KEvsRJjpDX.NzC', '2025-03-03', '[\"normal\"]', 1, '2025-04-11 17:55:05', 5, '71e0dc27ff07a58e8d2dcdbfbe0ebe06');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `mother_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `mother_id`, `content`, `created_at`) VALUES
(3, 46, 'LOVE U', '2025-04-14 20:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_role` enum('mother','midwife','doctor','admin') NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photo_album`
--

CREATE TABLE `photo_album` (
  `id` int(11) NOT NULL,
  `mother_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photo_album`
--

INSERT INTO `photo_album` (`id`, `mother_id`, `image`, `uploaded_at`) VALUES
(15, 46, 'uploads/IMG_67f98b4d274621.14779354.jpeg', '2025-04-11 21:36:13');

-- --------------------------------------------------------

--
-- Table structure for table `pregnancy_progress`
--

CREATE TABLE `pregnancy_progress` (
  `id` int(11) NOT NULL,
  `mother_id` int(11) NOT NULL,
  `pregnancy_start` date NOT NULL,
  `due_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
  `midwife_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `mother_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verification_tokens`
--

CREATE TABLE `verification_tokens` (
  `id` int(11) NOT NULL,
  `mother_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `midwife_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weekly_advice`
--

CREATE TABLE `weekly_advice` (
  `id` int(11) NOT NULL,
  `pregnancy_week` int(11) DEFAULT NULL,
  `advice` text DEFAULT NULL,
  `midwife_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `mother_id` (`mother_id`),
  ADD KEY `midwife_id` (`midwife_id`);

--
-- Indexes for table `community_likes`
--
ALTER TABLE `community_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`mother_id`,`midwife_id`),
  ADD KEY `mother_id` (`mother_id`),
  ADD KEY `midwife_id` (`midwife_id`);

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mother_id` (`mother_id`),
  ADD KEY `midwife_id` (`midwife_id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `health_tracker`
--
ALTER TABLE `health_tracker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mother_id` (`mother_id`);

--
-- Indexes for table `invitation_tokens`
--
ALTER TABLE `invitation_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `meals`
--
ALTER TABLE `meals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `medical_staff`
--
ALTER TABLE `medical_staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mothers`
--
ALTER TABLE `mothers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mother_id` (`mother_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photo_album`
--
ALTER TABLE `photo_album`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mother_id` (`mother_id`);

--
-- Indexes for table `pregnancy_progress`
--
ALTER TABLE `pregnancy_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mother_id` (`mother_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `midwife_id` (`midwife_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `mother_id` (`mother_id`);

--
-- Indexes for table `verification_tokens`
--
ALTER TABLE `verification_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mother` (`mother_id`),
  ADD KEY `fk_midwife` (`midwife_id`);

--
-- Indexes for table `weekly_advice`
--
ALTER TABLE `weekly_advice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `midwife_id` (`midwife_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_comments`
--
ALTER TABLE `community_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_likes`
--
ALTER TABLE `community_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `health_tracker`
--
ALTER TABLE `health_tracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `invitation_tokens`
--
ALTER TABLE `invitation_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `meals`
--
ALTER TABLE `meals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `medical_staff`
--
ALTER TABLE `medical_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mothers`
--
ALTER TABLE `mothers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photo_album`
--
ALTER TABLE `photo_album`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pregnancy_progress`
--
ALTER TABLE `pregnancy_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_tokens`
--
ALTER TABLE `verification_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `weekly_advice`
--
ALTER TABLE `weekly_advice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD CONSTRAINT `community_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_comments_ibfk_2` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_comments_ibfk_3` FOREIGN KEY (`midwife_id`) REFERENCES `medical_staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_likes`
--
ALTER TABLE `community_likes`
  ADD CONSTRAINT `community_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_likes_ibfk_2` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_likes_ibfk_3` FOREIGN KEY (`midwife_id`) REFERENCES `medical_staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `community_posts_ibfk_1` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_posts_ibfk_2` FOREIGN KEY (`midwife_id`) REFERENCES `medical_staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exercises`
--
ALTER TABLE `exercises`
  ADD CONSTRAINT `exercises_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `medical_staff` (`id`);

--
-- Constraints for table `health_tracker`
--
ALTER TABLE `health_tracker`
  ADD CONSTRAINT `health_tracker_ibfk_1` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`);

--
-- Constraints for table `meals`
--
ALTER TABLE `meals`
  ADD CONSTRAINT `meals_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `medical_staff` (`id`);

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`);

--
-- Constraints for table `photo_album`
--
ALTER TABLE `photo_album`
  ADD CONSTRAINT `photo_album_ibfk_1` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`);

--
-- Constraints for table `pregnancy_progress`
--
ALTER TABLE `pregnancy_progress`
  ADD CONSTRAINT `pregnancy_progress_ibfk_1` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`);

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_ibfk_1` FOREIGN KEY (`midwife_id`) REFERENCES `medical_staff` (`id`),
  ADD CONSTRAINT `referrals_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `medical_staff` (`id`),
  ADD CONSTRAINT `referrals_ibfk_3` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`);

--
-- Constraints for table `verification_tokens`
--
ALTER TABLE `verification_tokens`
  ADD CONSTRAINT `fk_midwife` FOREIGN KEY (`midwife_id`) REFERENCES `medical_staff` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_mother` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verification_tokens_ibfk_1` FOREIGN KEY (`mother_id`) REFERENCES `mothers` (`id`);

--
-- Constraints for table `weekly_advice`
--
ALTER TABLE `weekly_advice`
  ADD CONSTRAINT `weekly_advice_ibfk_1` FOREIGN KEY (`midwife_id`) REFERENCES `medical_staff` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
