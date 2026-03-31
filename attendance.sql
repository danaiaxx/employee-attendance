-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2026 at 04:52 PM
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
-- Database: `attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attRN` int(11) NOT NULL,
  `empId` int(11) NOT NULL,
  `attDate` date NOT NULL,
  `attTimeIn` time NOT NULL,
  `attTimeOut` time NOT NULL,
  `attStat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attRN`, `empId`, `attDate`, `attTimeIn`, `attTimeOut`, `attStat`) VALUES
(1, 234, '2026-03-31', '08:20:00', '17:20:00', 'Added'),
(2, 234, '2026-03-31', '08:20:00', '17:20:00', 'Added'),
(3, 234, '2026-03-31', '08:20:00', '17:20:00', 'Added'),
(4, 234, '2026-03-31', '08:20:00', '17:20:00', 'Added'),
(5, 234, '2026-03-31', '08:20:00', '17:20:00', 'Added'),
(6, 234, '2026-03-31', '08:20:00', '17:20:00', 'Added'),
(7, 237, '2026-03-03', '10:45:00', '20:30:00', 'Added');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attRN`),
  ADD KEY `empId` (`empId`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`empId`) REFERENCES `employees` (`empId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
