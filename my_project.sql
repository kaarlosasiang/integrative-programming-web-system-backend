-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2023 at 04:18 PM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`, `first_name`, `last_name`, `birthday`, `gender`, `email`, `phone_number`, `password`) VALUES
(27, 'Clarence', 'Japinan', '2023-10-04', 'female', 'test@test.com', '09510312859', '$2y$10$HBHh.CL1HNGHh5zJcOKSLeB5gqqO7p0EHOivZV1ppstGUmAMNd2Be'),
(28, 'Diane', 'Doe', '2023-09-10', 'male', 'dianne@gmail.com', '09128347865', '$2y$10$lRIKpkUvh8en48ihHm.EwuA0pwrnOGXy0SiQg9Y0BpjtCvG2SJMsG'),
(47, 'John', 'Doe', '2023-11-02', 'male', 'john@gmail.com', '09123456789', '$2y$10$PumhXShcT5uQ3eviJ2eINuYL9z5R8kLszjQmL6jzrVVoTpfogv5Fa'),
(48, 'Jade', 'Lore', '2023-11-02', 'male', 'jade@gmail.com', '09123456789', '$2y$10$AIT3xyYU5NQp.UW/tPzAyeoA/Z4ey/kw7DfWFHAJ6KPmFvcolcfrW'),
(50, 'Jonathan', 'Dale', '2023-11-03', 'female', 'jonathan@gmail.com', '09123456789', '$2y$10$ZfCw3CLWJr9QS8HayZxsR.lxzxzuyJZP9fOnBNqInKKG2WHWmCdrK'),
(63, 'Clarence', 'Japinan', '2023-11-03', 'female', 'japinanclarence@gmail.com', '09510312859', '$2y$10$b3EPe88wKHmCQyOvw/nIOOSFw0s.P3kp5/g/F0.lsX3Z5LbvyyNrS'),
(64, 'Roche', 'Santiago', '0000-00-00', '', 'rocheSantiago@gmail.com', NULL, '$2y$10$0mecrEjFBn/qbB0ntsI9tOsJ3oNksykdMSSy76M.TlnjLlG/MgTk2');

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
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `institute` varchar(10) NOT NULL,
  `course` varchar(10) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `registered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `first_name`, `last_name`, `middle_name`, `birthday`, `gender`, `institute`, `course`, `contact_number`, `registered_at`, `updated_at`) VALUES
(4, 'David', 'Ross', 'Mo', '2000-12-27', 'male', 'FTED', 'BPED', '09123456789', '2023-11-19 22:45:14', NULL),
(6, 'Michelle', 'Dee', 'Mo', '2000-12-27', 'male', 'FGBM', 'BSCRIM', '09123456789', '2023-11-19 22:48:13', NULL);

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
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `zipcode` varchar(4) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
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

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `middle_name`, `birthday`, `gender`, `purok`, `barangay`, `municipality`, `province`, `zipcode`, `contact_number`, `institute`, `course`, `guardian_name`, `guardian_contact`, `guardian_address`, `registered_at`, `updated_at`) VALUES
('2023-0001', 'Jonathan', 'David', 'Dee', '2000-12-27', 'male', 'Baybay', 'Poblacion', 'Lupon', 'Davao Oriental', '8207', '09123456789', 'FCDSET', 'BSIT', 'Jane Doe', '09123456789', 'Mauswagon, Corporacion, Lupon, Davao Oriental', '2023-11-14 18:55:48', NULL),
('2023-0003', 'Jonathan', 'David', 'Dee', '2000-12-27', 'male', 'Baybay', 'Poblacion', 'Lupon', 'Davao Oriental', '8207', '09123456789', 'FCDSET', 'BSIT', 'Jane Doe', '09123456789', 'Mauswagon, Corporacion, Lupon, Davao Oriental', '2023-11-15 05:10:40', NULL),
('2023-0004', 'Yasmin', 'Mendoza', 'Rosete', '2000-02-21', 'female', 'Mandalihan', 'Poblacion', 'Lupon', 'Davao Oriental', '8207', '09123456789', 'FTED', 'BPED', 'Jane Doe', '09123456789', 'Mauswagon, Corporacion, Lupon, Davao Oriental', '2023-11-19 22:46:22', NULL),
('2023-0005', 'Jaquiline', 'Dela Cuadra', 'Rosete', '2000-02-21', 'female', 'San Vicente', 'Corporacion', 'Lupon', 'Davao Oriental', '8207', '09123456789', 'FGBM', 'BSCRIM', 'Jane Doe', '09123456789', 'Mauswagon, Corporacion, Lupon, Davao Oriental', '2023-11-19 22:49:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `code` varchar(10) NOT NULL,
  `description` text NOT NULL,
  `unit` varchar(2) NOT NULL,
  `type` enum('lecture','laboratory','lecture & laboratory') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`code`, `description`, `unit`, `type`, `created_at`, `updated_at`) VALUES
('112223', 'Description', '4', '', '2023-11-18 09:05:51', NULL),
('1234', 'Sample description', '4', 'laboratory', '2023-11-18 07:07:45', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_institute` (`institute`),
  ADD KEY `faculty_course` (`course`);

--
-- Indexes for table `institute`
--
ALTER TABLE `institute`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `student_institute` (`institute`),
  ADD KEY `student_course` (`course`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `institute`
--
ALTER TABLE `institute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  ADD CONSTRAINT `faculty_institute` FOREIGN KEY (`institute`) REFERENCES `institute` (`slug`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `student_course` FOREIGN KEY (`course`) REFERENCES `course` (`slug`),
  ADD CONSTRAINT `student_institute` FOREIGN KEY (`institute`) REFERENCES `institute` (`slug`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
