-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2023 at 09:09 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `institute` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `title`, `slug`, `description`, `institute`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor of Science in Information Technology', 'BSIT', NULL, 'FCDSET', '2023-11-12 21:51:55', '2023-11-19 23:17:54'),
(3, 'Bachelor of Science in Civil Engineering', 'BSCE', '', 'FCDSET', '2023-11-14 20:03:23', '2023-11-19 23:18:11'),
(4, 'Bachelor of Physical Education', 'BPED', '', 'FTED', '2023-11-19 22:45:09', '2023-11-19 23:18:02'),
(5, 'Bachelor of Science in Criminology', 'BSCRIM', '', 'FGBM', '2023-11-19 22:48:05', '2023-11-19 23:18:06');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `institute` varchar(10) NOT NULL,
  `course` varchar(10) NOT NULL,
  `registered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `user_id`, `institute`, `course`, `registered_at`, `updated_at`) VALUES
(9, 76, 'FCDSET', 'BSIT', '2023-11-29 22:27:52', NULL),
(10, 77, 'FGBM', 'BSCRIM', '2023-11-29 23:48:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_subjects`
--

CREATE TABLE `faculty_subjects` (
  `faculty_id` int(11) NOT NULL,
  `subject_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_subjects`
--

INSERT INTO `faculty_subjects` (`faculty_id`, `subject_code`) VALUES
(9, '123'),
(9, 'ITPE130'),
(10, '1234');

-- --------------------------------------------------------

--
-- Table structure for table `institute`
--

CREATE TABLE `institute` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `institute`
--

INSERT INTO `institute` (`id`, `title`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Faculty of Data Science Computing Engineering and Technology', 'FCDSET', NULL, '2023-11-12 21:53:22', NULL),
(3, 'Faculty of Agriculture and Life Sciences', 'FALS', '', '2023-11-14 19:54:11', NULL),
(4, 'Faculty of Teachers Education', 'FTED', '', '2023-11-19 22:44:30', NULL),
(5, 'Faculty of Governance and Business Management', 'FGBM', '', '2023-11-19 22:47:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schoolyear`
--

CREATE TABLE `schoolyear` (
  `id` int(10) NOT NULL,
  `school_year` varchar(10) NOT NULL,
  `semester` varchar(5) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schoolyear`
--

INSERT INTO `schoolyear` (`id`, `school_year`, `semester`, `status`, `created_at`, `updated_at`) VALUES
(1, '2022-2023', '1st', '0', '2023-11-29 04:39:10', '2023-11-29 23:26:43'),
(3, '2023-2024', '1st', '1', '2023-11-29 21:54:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `street` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `zipcode` varchar(4) NOT NULL,
  `institute` varchar(10) NOT NULL,
  `course` varchar(10) NOT NULL,
  `guardian_name` varchar(50) NOT NULL,
  `guardian_contact` varchar(15) NOT NULL,
  `guardian_address` varchar(255) NOT NULL,
  `registered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `street`, `barangay`, `municipality`, `province`, `zipcode`, `institute`, `course`, `guardian_name`, `guardian_contact`, `guardian_address`, `registered_at`, `updated_at`) VALUES
('2023-0002', 71, 'Baybay', 'Poblacion', 'Lupon', 'Davao Oriental', '8207', 'FTED', 'BPED', 'Jane Doe', '09123456789', 'Mauswagon, Corporacion, Lupon, Davao Oriental', '2023-11-29 17:50:50', '2023-11-29 17:55:11'),
('2023-0003', 72, 'Baybay', 'Poblacion', 'Lupon', 'Davao Oriental', '8207', 'FTED', 'BPED', 'Jane Doe', '09123456789', 'Mauswagon, Corporacion, Lupon, Davao Oriental', '2023-11-29 17:51:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `subject_code` varchar(10) NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `grades` int(5) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`subject_code`, `student_id`, `faculty_id`, `grades`, `created_at`, `updated_at`) VALUES
('123', '2023-0002', 9, NULL, '2023-12-29 04:40:01', NULL),
('123', '2023-0003', 9, NULL, '2023-12-29 00:19:03', NULL),
('ITPE130', '2023-0003', 9, NULL, '2023-12-29 00:11:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `code` varchar(10) NOT NULL,
  `description` text NOT NULL,
  `unit` varchar(2) NOT NULL,
  `type` enum('lecture','laboratory','lecture & laboratory') NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `school_year` int(10) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`code`, `description`, `unit`, `type`, `status`, `school_year`, `created_at`, `updated_at`) VALUES
('123', 'Description', '4', 'lecture & laboratory', '1', 3, '2023-12-15 12:07:56', NULL),
('1234', 'Subject Description', '1', 'lecture & laboratory', '1', 3, '2023-12-15 13:31:24', NULL),
('ITPE130', 'Subject Description', '1', 'lecture & laboratory', '1', 3, '2023-12-28 22:20:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `middle_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('0','1','2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `middle_name`, `last_name`, `birthday`, `gender`, `email`, `contact_number`, `password`, `role`) VALUES
(27, 'admin', '', '', '2023-10-04', '', 'admin@gmail.com', NULL, '$2y$10$PumhXShcT5uQ3eviJ2eINuYL9z5R8kLszjQmL6jzrVVoTpfogv5Fa', '0'),
(71, 'Rayan', 'Maglicious', 'Celestino', '2000-12-27', 'male', 'rayan@gmail.com', '09123456789', '$2y$10$sKfzOvpyo5CyVb6N.4sVM.cvjmHuS.sj..dgz59mx9rz5jpAE4BVC', '2'),
(72, 'Rubylyn', 'Celistino', 'Lingaolingao', '2003-06-26', 'female', 'rubylyn@gmail.com', '09123456789', '$2y$10$u0pg4Gmh8zeb8hcI2/IoUOvS9g1zLDhETBRvTObiSPxQfb3d4UEqe', '2'),
(76, 'Jonathan', 'Dee', 'David', '2000-12-27', 'male', 'jonathan@gmail.com', '09123456789', '$2y$10$S6TrG1rqBuDfQfF2iy2LK.27HeScfdiTk2g9sbxfLuJlGKyax.GZu', '1'),
(77, 'Mica', 'Dee', 'David', '2000-12-27', 'male', 'mica@gmail.com', '09123456789', '$2y$10$VHHXfT58FdJK3kE1URhP5ehX5MFLIB0REktrDo5M/wiFsKZn6yBDG', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `institute` (`institute`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD KEY `faculty_institute` (`institute`),
  ADD KEY `faculty_course` (`course`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `faculty_subjects`
--
ALTER TABLE `faculty_subjects`
  ADD PRIMARY KEY (`faculty_id`,`subject_code`),
  ADD KEY `subject_id` (`subject_code`);

--
-- Indexes for table `institute`
--
ALTER TABLE `institute`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `schoolyear`
--
ALTER TABLE `schoolyear`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `student_institute` (`institute`),
  ADD KEY `student_course` (`course`),
  ADD KEY `students_ibfk_1` (`user_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`subject_code`,`student_id`),
  ADD KEY `student_subjects_ibfk_1` (`student_id`),
  ADD KEY `student_subjects_ibfk_3` (`faculty_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`code`),
  ADD KEY `school_year` (`school_year`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `institute`
--
ALTER TABLE `institute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `schoolyear`
--
ALTER TABLE `schoolyear`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`institute`) REFERENCES `institute` (`slug`);

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_course` FOREIGN KEY (`course`) REFERENCES `course` (`slug`),
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faculty_institute` FOREIGN KEY (`institute`) REFERENCES `institute` (`slug`);

--
-- Constraints for table `faculty_subjects`
--
ALTER TABLE `faculty_subjects`
  ADD CONSTRAINT `faculty_subjects_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `faculty_subjects_ibfk_2` FOREIGN KEY (`subject_code`) REFERENCES `subjects` (`code`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `student_course` FOREIGN KEY (`course`) REFERENCES `course` (`slug`),
  ADD CONSTRAINT `student_institute` FOREIGN KEY (`institute`) REFERENCES `institute` (`slug`),
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`subject_code`) REFERENCES `faculty_subjects` (`subject_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_subjects_ibfk_3` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`school_year`) REFERENCES `schoolyear` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
