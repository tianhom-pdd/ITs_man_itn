-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2025 at 10:33 AM
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
-- Database: `it_support`
--

-- --------------------------------------------------------

--
-- Table structure for table `cause_it`
--

CREATE TABLE `cause_it` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `type` enum('cause','question','solution','contact') NOT NULL,
  `title_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cause_it`
--

INSERT INTO `cause_it` (`id`, `title`, `text`, `image`, `type`, `title_id`, `parent_id`) VALUES
(1, 'สายหลุด/หลวม', 'ตรวจสอบการเชื่อมต่อสายเคเบิล', 'https://placehold.co/600x400/ef4444/FFFFFF?text=Check+LAN+Cable', 'cause', 1, NULL),
(2, 'STEP 1: ตรวจสอบสาย LAN', 'ตรวจสอบว่าสาย LAN เสียบแน่นทั้งสองฝั่ง (คอมพิวเตอร์และ Router)', 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+1:+Check+Cable', 'question', 1, 1),
(3, 'หาย', '', 'uploads/nodes/node_3_1753252065.jpg', 'solution', 1, 2),
(4, 'ไม่หาย', '', NULL, 'question', 1, 2),
(5, 'STEP 2: ลองเปลี่ยนช่องเสียบ LAN', 'STEP 2: ลองเปลี่ยนช่องเสียบ LAN', 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+2:+Change+Port', 'question', 1, 4),
(6, 'หาย', '', NULL, 'solution', 1, 5),
(7, 'ไม่หาย', '', NULL, 'question', 1, 5),
(8, 'STEP 3: ลองเปลี่ยนสาย LAN', 'STEP 3: ลองเปลี่ยนสาย LAN', 'https://placehold.co/600x400/ef4444/FFFFFF?text=Step+3:+New+Cable', 'question', 1, 7),
(9, 'หาย', '', NULL, 'solution', 1, 8),
(10, 'ไม่หาย', '', NULL, 'contact', 1, 8),
(18, 'WIFI', NULL, NULL, 'cause', 1, NULL),
(25, 'SAP1', '', '', 'cause', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conclusion`
--

CREATE TABLE `conclusion` (
  `id` int(11) NOT NULL,
  `cause_id` int(11) NOT NULL,
  `summary` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conclusion`
--

INSERT INTO `conclusion` (`id`, `cause_id`, `summary`) VALUES
(1, 3, 'ปัญหาเกิดจากสาย LAN หลวม'),
(2, 6, 'ปัญหาเกิดจาก Port เดิมของ Router/Switch เสีย'),
(3, 9, 'ปัญหาเกิดจากสาย LAN เส้นเดิมเสีย'),
(4, 10, 'จำเป็นต้องให้ IT Support ตรวจสอบ');

-- --------------------------------------------------------

--
-- Table structure for table `title_it`
--

CREATE TABLE `title_it` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `icon` text DEFAULT NULL,
  `color` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `title_it`
--

INSERT INTO `title_it` (`id`, `title`, `icon`, `color`) VALUES
(1, 'Network', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6.75 3v2.25M17.25 3v2.25M3 7.5h18M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V7.5a2.25 2.25 0 0 0-2.25-2.25h-15A2.25 2.25 0 0 0 2.25 7.5v9.75A2.25 2.25 0 0 0 4.5 19.5z\" /></svg>', '#ef4444'),
(2, 'SAP', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 7.5V6a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 6v1.5M3 7.5h18M3 7.5v9.75A2.25 2.25 0 0 0 5.25 19.5h13.5A2.25 2.25 0 0 0 21 17.25V7.5\" /></svg>', '#eab308'),
(5, 'windows', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M8 7V3m8 4V3m-9 8h10m-9 4h10M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V7.5a2.25 2.25 0 0 0-2.25-2.25h-15A2.25 2.25 0 0 0 2.25 7.5v9.75A2.25 2.25 0 0 0 4.5 19.5z\" /></svg>', '#4b89c3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cause_it`
--
ALTER TABLE `cause_it`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title_id` (`title_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `conclusion`
--
ALTER TABLE `conclusion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cause_id` (`cause_id`);

--
-- Indexes for table `title_it`
--
ALTER TABLE `title_it`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cause_it`
--
ALTER TABLE `cause_it`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `conclusion`
--
ALTER TABLE `conclusion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `title_it`
--
ALTER TABLE `title_it`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cause_it`
--
ALTER TABLE `cause_it`
  ADD CONSTRAINT `cause_it_ibfk_1` FOREIGN KEY (`title_id`) REFERENCES `title_it` (`id`),
  ADD CONSTRAINT `cause_it_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `cause_it` (`id`);

--
-- Constraints for table `conclusion`
--
ALTER TABLE `conclusion`
  ADD CONSTRAINT `conclusion_ibfk_1` FOREIGN KEY (`cause_id`) REFERENCES `cause_it` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
