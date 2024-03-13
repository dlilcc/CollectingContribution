-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2024 at 07:06 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `university_magazine`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_published` tinyint(1) DEFAULT 0,
  `is_final` tinyint(1) DEFAULT 0,
  `faculty_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `title`, `content`, `image_url`, `submission_date`, `is_published`, `is_final`, `faculty_name`) VALUES
(24, 1, 'test 1', '<p><strong>aaaa<em>dsvsv<s>dsvsdvsdv</s></em></strong></p>\r\n', 'images/65f065f73f3c3_343752392_968890044281715_6654598999750888234_n.jpg', '2024-03-12 13:14:09', 0, 0, NULL),
(25, 1, 'vdsvsdv', '<p>dsvdsvsvd</p>\r\n', 'images/image_65f05b4ee3715_a.jpg', '2024-03-12 13:40:30', 0, 0, NULL),
(26, 5, 'gfdgdf', '<p>dsfdsf</p>\r\n', 'images/image_65f1a0be75f86_CyberPunk2077.jpg', '2024-03-13 12:49:02', 0, 0, NULL),
(27, 5, 'sadsda', '<p>sdadsa</p>\r\n', '', '2024-03-13 13:10:16', 0, 0, NULL),
(30, 5, 'ddsfds', '<p>fsdfdfsdf</p>\r\n', '', '2024-03-13 13:25:25', 0, 0, 'Science'),
(31, 5, 'saddsa', '<p>dsffd</p>\r\n', 'images/image_65f1bdc1dbd63_coffee_in_rain_by_kirokaze_d98qb8z.gif', '2024-03-13 14:52:49', 0, 0, 'Art'),
(32, 5, 'vbcbvc', '<p>cvbvc</p>\r\n', '', '2024-03-13 14:53:41', 0, 0, 'Art');

-- --------------------------------------------------------

--
-- Table structure for table `article_comments`
--

CREATE TABLE `article_comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `closure_dates`
--

CREATE TABLE `closure_dates` (
  `id` int(11) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `closure_date` date NOT NULL,
  `final_closure_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `closure_dates`
--

INSERT INTO `closure_dates` (`id`, `academic_year`, `closure_date`, `final_closure_date`) VALUES
(7, '2024', '2024-03-13', '2024-03-27'),
(8, '2024', '2024-03-11', '2024-03-15');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_name`) VALUES
('Art'),
('Muisc'),
('Null'),
('Science');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','coordinator','student','manager','guest') NOT NULL,
  `faculty_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `faculty_name`) VALUES
(1, 'linhproht', '$2y$10$O5hrtfPZFDhtOncysalg1eavt5J4vtq.xAzhfRP3yHrOUDyKvokJK', 'student', NULL),
(3, 'guest', '$2y$10$7666ZLNhPYTp08lBvKDgA.bgRVtEbSuIxy4NL2DEVmLij9Injw9Bm', 'guest', NULL),
(4, 'admin', '$2y$10$QaU9qF7pLg6IXvWGLRBc3uAk65Rg.JUJrDtRq3jtoeSPYstRyBkHO', 'admin', NULL),
(5, 'linhproht2', '$2y$10$FJrNhDgNel2YyM145sXnLu7kYcaC1vmL9HHALsxzviqKYYE148bxe', 'student', 'Art');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `faculty_name` (`faculty_name`);

--
-- Indexes for table `article_comments`
--
ALTER TABLE `article_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `closure_dates`
--
ALTER TABLE `closure_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `faculty_name` (`faculty_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `article_comments`
--
ALTER TABLE `article_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `closure_dates`
--
ALTER TABLE `closure_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`faculty_name`) REFERENCES `faculty` (`faculty_name`);

--
-- Constraints for table `article_comments`
--
ALTER TABLE `article_comments`
  ADD CONSTRAINT `article_comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `article_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`faculty_name`) REFERENCES `faculty` (`faculty_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
