-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2026 at 02:12 PM
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
-- Database: `construction_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `document_folders`
--

CREATE TABLE `document_folders` (
  `id` int(11) NOT NULL,
  `folder_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_folders`
--

INSERT INTO `document_folders` (`id`, `folder_name`, `created_at`) VALUES
(1, 'flood control', '2026-01-10 06:40:21');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'Unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `username`, `message`, `status`, `created_at`) VALUES
(1, 'rhy', 'lorem ipsum dolor sit amet', 'Read', '2026-01-12 06:52:04'),
(2, 'rhy', 'increase our salary', 'Read', '2026-01-12 07:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `logbook`
--

CREATE TABLE `logbook` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `role_designation` varchar(50) DEFAULT NULL,
  `consultant` varchar(50) DEFAULT NULL,
  `log_date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logbook`
--

INSERT INTO `logbook` (`id`, `firstname`, `lastname`, `role_designation`, `consultant`, `log_date`, `time_in`, `time_out`, `created_at`) VALUES
(1, 'jayson', 'gadiano', 'worker', 'i dunno', '2026-01-10', '10:10:00', '18:30:00', '2026-01-10 07:06:40'),
(2, 'jaeyyy', '', 'Worker', 'i dunno', '2026-01-10', '17:24:00', '00:00:00', '2026-01-10 09:24:41'),
(3, 'jaeyyy', '', 'Worker', 'i dunno', '2026-01-10', '17:24:00', '10:27:08', '2026-01-10 09:27:00'),
(4, 'jaeyyy', '', 'Worker', '', '2026-01-10', '17:27:00', '10:28:02', '2026-01-10 09:27:17'),
(5, 'jaeyyy', '', 'Worker', 'fgfgfg', '2026-01-11', '19:10:00', '12:11:09', '2026-01-11 11:10:51'),
(6, 'rhy', '', 'Worker', '', '2026-01-11', '19:32:00', '12:33:04', '2026-01-11 11:32:39'),
(7, 'rhy', '', 'Worker', 'i dunno', '2026-01-12', '14:30:00', '09:21:09', '2026-01-12 06:30:29'),
(8, 'jayson', '', 'Admin', '', '2026-01-12', '15:51:00', '08:53:38', '2026-01-12 07:51:12'),
(9, 'jayson', '', 'Admin', '', '2026-01-12', '15:54:00', '08:55:18', '2026-01-12 07:54:38'),
(10, 'jayson', '', 'Admin', '', '2026-01-12', '15:55:00', '08:55:19', '2026-01-12 07:55:16'),
(11, 'jayson', '', 'Admin', '', '2026-01-12', '15:56:00', '08:56:25', '2026-01-12 07:56:22'),
(12, 'jayson', '', 'Admin', '', '2026-01-12', '16:22:00', '09:22:43', '2026-01-12 08:22:41'),
(13, 'jayson', '', 'Admin', '', '2026-01-12', '16:38:00', '09:39:01', '2026-01-12 08:38:59');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_requests`
--

CREATE TABLE `payroll_requests` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `work_type` varchar(100) DEFAULT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `attendance_log` int(11) NOT NULL,
  `allowance` decimal(10,2) DEFAULT 0.00,
  `total_salary` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `approved_by` varchar(100) DEFAULT NULL,
  `date_approved` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_requests`
--

INSERT INTO `payroll_requests` (`id`, `username`, `firstname`, `lastname`, `role`, `work_type`, `daily_rate`, `attendance_log`, `allowance`, `total_salary`, `status`, `approved_by`, `date_approved`, `created_at`) VALUES
(1, 'rhy', 'rhy', '', 'Worker', 'construction', 120.00, 365, 123.00, 43923.00, 'Approved', 'admin', '2026-01-11', '2026-01-11 11:28:50'),
(2, 'jaeyyy', 'jaeyyy', '', 'Worker', 'construction', 510.00, 4, 0.00, 2040.00, 'Approved', 'admin', '2026-01-11', '2026-01-11 12:07:23'),
(3, 'jaeyyy', 'jaeyyy', '', 'Worker', 'construction', 510.00, 4, 0.00, 2040.00, 'Approved', 'admin', '2026-01-13', '2026-01-11 12:09:33');

-- --------------------------------------------------------

--
-- Table structure for table `procurement`
--

CREATE TABLE `procurement` (
  `id` int(11) NOT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `requested_by` varchar(50) DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `received_by` varchar(50) DEFAULT NULL,
  `delivered_by` varchar(50) DEFAULT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `site_manager` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `accomplishment_pct` int(11) DEFAULT 0,
  `google_sheet_id` varchar(255) DEFAULT NULL,
  `google_sheet_tab_id` varchar(50) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `location`, `site_manager`, `start_date`, `end_date`, `budget`, `status`, `created_at`, `accomplishment_pct`, `google_sheet_id`, `google_sheet_tab_id`) VALUES
(1, 'flood control', 'cabatuan, isabela', 'jayson gadiano', '2026-01-20', '2030-02-14', 5000000.00, 'Ongoing', '2026-01-10 06:37:04', 7, '1mBSmTkc-F_arw3H-PAK14YS7yd6co5x0__AsfVCCXp8', '0'),
(2, 'test project', 'somewhere', 'someone', '2026-01-12', '2032-01-12', 10000000.00, 'Ongoing', '2026-01-12 07:51:01', 100, '1mBSmTkc-F_arw3H-PAK14YS7yd6co5x0__AsfVCCXp8', '1374625025'),
(3, 'test project 2', 'somewhere', 'someone', '2026-01-12', '2033-01-20', 20000000.00, 'Ongoing', '2026-01-12 07:54:28', 0, '1mBSmTkc-F_arw3H-PAK14YS7yd6co5x0__AsfVCCXp8', '0'),
(4, 'test project 3', 'somewhere', 'someone', '2026-01-12', '2032-11-10', 30000000.00, 'Ongoing', '2026-01-12 07:56:13', 0, NULL, '0'),
(5, 'test project 4', 'somewhere', 'someone', '2026-01-12', '2031-01-30', 40000000.00, 'Ongoing', '2026-01-12 08:22:26', 0, NULL, '0'),
(6, 'test project 5', 'somewhere', 'someone', '2026-01-13', '2036-12-01', 50000000.00, 'Ongoing', '2026-01-12 08:38:47', 0, NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `project_items`
--

CREATE TABLE `project_items` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `item_description` varchar(255) DEFAULT NULL,
  `weight_factor` decimal(5,2) DEFAULT NULL,
  `status_pct` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_items`
--

INSERT INTO `project_items` (`id`, `project_id`, `item_description`, `weight_factor`, `status_pct`) VALUES
(1, 1, 'backhoe', 15.00, 10),
(2, 1, 'flooring', 25.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `timesheet`
--

CREATE TABLE `timesheet` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `hours_worked` decimal(10,2) DEFAULT NULL,
  `deductions` decimal(10,2) DEFAULT NULL,
  `total_salary` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_files`
--

CREATE TABLE `uploaded_files` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploaded_files`
--

INSERT INTO `uploaded_files` (`id`, `folder_id`, `file_name`, `file_path`, `uploaded_at`, `project_id`) VALUES
(1, 1, '6TN-sarah-discaya-photo-bullit-marquez-vera-files.jpeg', 'uploads/docs/6TN-sarah-discaya-photo-bullit-marquez-vera-files.jpeg', '2026-01-10 06:40:54', NULL),
(2, 1, '6TN-sarah-discaya-photo-bullit-marquez-vera-files (5).jpeg', 'uploads/docs/6TN-sarah-discaya-photo-bullit-marquez-vera-files (5).jpeg', '2026-01-12 08:39:19', NULL),
(3, NULL, '6TN-sarah-discaya-photo-bullit-marquez-vera-files (5) (1).jpeg', 'uploads/docs/1768281907_6TN-sarah-discaya-photo-bullit-marquez-vera-files (5) (1).jpeg', '2026-01-13 05:25:07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `work_directory`
--

CREATE TABLE `work_directory` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `role_designation` enum('Admin','Site Manager','Worker') NOT NULL,
  `work_type` varchar(100) DEFAULT NULL,
  `daily_rate` decimal(10,2) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `e_signature` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_directory`
--

INSERT INTO `work_directory` (`id`, `full_name`, `role_designation`, `work_type`, `daily_rate`, `email`, `phone_no`, `e_signature`, `created_at`, `username`, `password`) VALUES
(1, 'jayson', 'Admin', 'Engineer', 0.00, 'aranyx21@gmail.com', '09123456789', 'gadiano', '2026-01-10 06:51:37', 'admin', '$2y$10$dGcJWipSCCKyl3kLnhB0qeMvDLfIlNAeNEqJES7hAioxAVYBWUvdC'),
(2, 'jaeyyy', 'Worker', 'Construction', 510.00, 'jaey202004@gmail.com', '09123456789', 'sheeesh', '2026-01-10 07:00:17', 'jaeyyy', '$2y$10$Y.62gNoN4d1UUtCxWGEJruZ0CCyJmHAS66dax6aQrE7tYqS34p0di'),
(3, 'rhy', 'Worker', 'Construction', 520.00, 'jay.gadiano20@gmail.com', '09123456789', 'fsdfsd', '2026-01-11 11:13:23', 'rhy', '$2y$10$uENsRjI5wk69dIY0ECAEke5GTX4RkbElLlklre1JNpilGveTMwdjG'),
(4, 'Jayson gadiano', 'Worker', 'Construction', 620.00, 'jeanp_luiss18@hotmail.com', '09123456789', NULL, '2026-01-12 08:35:01', 'jay', '$2y$10$KuIQ47KFxUSu8BfxB9TIpeSRmg2a8M1eTr0I/kefLHVDxO.cOCtf6'),
(5, 'marjorie acob', 'Worker', 'Construction', 620.00, 'lorem@ipsum.com', '09123456789', NULL, '2026-01-12 08:36:56', 'marj', '$2y$10$qmi6PE2tck3VRgTebc/Pi.noyR2FYjfd/g/xxSE4BGxq3uojIH4Ei'),
(7, 'juan dela cruz', 'Worker', 'Construction', 620.00, 'juand.cruz@gmail.com', '09987654321', NULL, '2026-01-12 08:41:34', 'juand', '$2y$10$c9XiPmoA0xn8QkZCY/ikq.6zo0REPNzdr0o7fBkluDZzQgPN8JwsK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `document_folders`
--
ALTER TABLE `document_folders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logbook`
--
ALTER TABLE `logbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_requests`
--
ALTER TABLE `payroll_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement`
--
ALTER TABLE `procurement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_items`
--
ALTER TABLE `project_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `timesheet`
--
ALTER TABLE `timesheet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `folder_id` (`folder_id`),
  ADD KEY `fk_project_files` (`project_id`);

--
-- Indexes for table `work_directory`
--
ALTER TABLE `work_directory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `document_folders`
--
ALTER TABLE `document_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `logbook`
--
ALTER TABLE `logbook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payroll_requests`
--
ALTER TABLE `payroll_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `procurement`
--
ALTER TABLE `procurement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `project_items`
--
ALTER TABLE `project_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `timesheet`
--
ALTER TABLE `timesheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `work_directory`
--
ALTER TABLE `work_directory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project_items`
--
ALTER TABLE `project_items`
  ADD CONSTRAINT `project_items_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD CONSTRAINT `fk_project_files` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `uploaded_files_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `document_folders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
