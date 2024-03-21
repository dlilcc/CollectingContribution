-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2024 at 04:11 AM
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
  `closure_date` date DEFAULT NULL,
  `final_closure_date` date DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 0,
  `is_final` tinyint(1) DEFAULT 0,
  `faculty_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `title`, `content`, `image_url`, `submission_date`, `closure_date`, `final_closure_date`, `is_disabled`, `is_published`, `is_final`, `faculty_name`) VALUES
(24, 1, 'test 1', '<p><strong>aaaa<em>dsvsv<s>dsvsdvsdv</s></em></strong></p>\r\n', 'images/65f065f73f3c3_343752392_968890044281715_6654598999750888234_n.jpg', '2024-03-12 13:14:09', NULL, NULL, 1, 0, 0, NULL),
(25, 1, 'vdsvsdv', '<p>dsvdsvsvd</p>\r\n', 'images/image_65f05b4ee3715_a.jpg', '2024-03-12 13:40:30', NULL, NULL, 1, 0, 0, NULL),
(41, 5, 'hehehe', '<p>hehe</p>\r\n', 'images/65f40ef84060c_z4460767096294_ca8543d5ffd43d396ff60023e62b69b7.jpg', '2024-03-15 08:55:06', '2024-03-16', '2024-03-20', 0, 1, 0, 'Art'),
(42, 5, 'aaa', '<p>aa</p>\r\n', '', '2024-03-15 08:56:11', '2024-03-16', '2024-03-20', 1, 0, 0, 'Art'),
(43, 5, 'aaa', '<p>aa</p>\r\n', '', '2024-03-15 09:02:20', '2024-03-16', '2024-03-20', 0, 1, 0, 'Art'),
(44, 5, 'sadsa', '<p>asddas</p>\r\n', '', '2024-03-18 10:26:18', '2024-03-19', '2024-03-20', 1, 1, 0, 'Art'),
(45, 5, 'test 2', '<p>a</p>\r\n', '', '2024-03-18 10:32:56', '2024-03-19', '2024-03-20', 0, 1, 0, 'Art'),
(46, 5, 'test 3', '<p>a</p>\r\n', '', '2024-03-18 10:33:05', '2024-03-19', '2024-03-20', 1, 0, 0, 'Art'),
(47, 5, 'music', '<p>a</p>\r\n', '', '2024-03-18 10:47:04', '2024-03-19', '2024-03-20', 0, 0, 0, 'Muisc'),
(48, 6, 'art', '<p>aa</p>\r\n', '', '2024-03-18 10:49:31', '2024-03-19', '2024-03-20', 1, 0, 0, 'Art'),
(49, 6, 'test 4', '<h1><em>dsaasd<s>sadsadsad<strong>sadsadsdadsa</strong></s></em>asdsadda<small>sad<big>sadadasd</big></small></h1>\r\n', '', '2024-03-18 12:35:19', '2024-03-19', '2024-03-20', 0, 1, 0, 'Art'),
(50, 6, 'gaga', '<p>a</p>\r\n', '', '2024-03-19 10:49:08', '2024-03-19', '2024-03-20', 0, 1, 0, 'Art'),
(51, 6, 'fafa', '<p>a</p>\r\n', '', '2024-03-19 10:53:43', '2024-03-19', '2024-03-20', 1, 0, 0, 'Art'),
(52, 6, 'hyhy', '<p>a</p>\r\n', '', '2024-03-19 11:01:39', '2024-03-19', '2024-03-20', 1, 0, 0, 'Art'),
(53, 6, 'aaaa', '<p>a</p>\r\n', '', '2024-03-19 12:26:25', '2024-03-19', '2024-03-20', 0, 1, 0, 'Art'),
(54, 6, 'bbbb', '<p>b</p>\r\n', '', '2024-03-19 13:10:42', '2024-03-19', '2024-03-20', 1, 0, 0, 'Art'),
(55, 6, 'ccc', '<p>c</p>\r\n', 'images/image_65f98edbcddfc_CyberPunk2077.jpg', '2024-02-01 13:10:51', '2024-03-19', '2024-03-20', 1, 0, 0, 'Art'),
(56, 6, 'test', '<p>a</p>\r\n', '', '2024-03-21 02:24:20', '2024-03-22', '2024-03-31', 0, 0, 0, 'Art'),
(57, 6, 'article 1', '<p>aa</p>\r\n', '', '2024-03-21 02:25:39', '2024-03-22', '2024-03-31', 0, 1, 0, 'Art');

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
(9, '2023', '2024-03-20', '2024-03-27'),
(10, '2024', '2024-03-22', '2024-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `coordinator_id` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `coordinator_id`, `comment_text`, `created_at`) VALUES
(1, 41, 1, 'haha', '2024-03-18 12:22:44'),
(2, 49, 1, 'that funny lol', '2024-03-18 12:35:55'),
(3, 43, 1, 'haha', '2024-03-21 02:29:36'),
(4, 57, 1, 'haha', '2024-03-21 02:29:53');

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
  `faculty_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `faculty_name`, `email`) VALUES
(1, 'linhproht', '$2y$10$yX98XDEzpP/ZZEFrq224w.4U0Re1PBPehdI4HXsT0mufCNgAkejVy', 'coordinator', 'Art', 'dlilcc718@gmail.com'),
(3, 'guest', '$2y$10$7666ZLNhPYTp08lBvKDgA.bgRVtEbSuIxy4NL2DEVmLij9Injw9Bm', 'guest', NULL, ''),
(4, 'admin', '$2y$10$QaU9qF7pLg6IXvWGLRBc3uAk65Rg.JUJrDtRq3jtoeSPYstRyBkHO', 'admin', NULL, ''),
(5, 'linhproht2', '$2y$10$FJrNhDgNel2YyM145sXnLu7kYcaC1vmL9HHALsxzviqKYYE148bxe', 'student', 'Muisc', ''),
(6, 'linhproht3', '$2y$10$bDw7rxsbW0nDtOYpjr4LXuNj9uOwDCiGi5ziRqv96.Yop.5B.Z3D6', 'student', 'Art', 'dlilcc718@gmail.com'),
(7, 'linhproht4', '$2y$10$CmrudsI13UV.j.L6cJw16.ju6W3PpnUqlbQcngYEyxHNSU1DeTGAG', 'student', 'Science', '');

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
-- Indexes for table `closure_dates`
--
ALTER TABLE `closure_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `closure_dates`
--
ALTER TABLE `closure_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`faculty_name`) REFERENCES `faculty` (`faculty_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
