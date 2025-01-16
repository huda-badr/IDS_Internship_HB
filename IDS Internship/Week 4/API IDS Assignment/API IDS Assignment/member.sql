-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 30, 2024 at 05:54 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_activity_club_ids`
--

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `FullName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `JoiningDate` date NOT NULL,
  `MobileNumber` varchar(15) NOT NULL,
  `EmergencyNumber` varchar(15) NOT NULL,
  `Photo` varchar(255) DEFAULT NULL,
  `Profession` int DEFAULT NULL,
  `Nationality` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Email` (`Email`),
  KEY `Profession` (`Profession`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`ID`, `FullName`, `Email`, `Password`, `DateOfBirth`, `Gender`, `JoiningDate`, `MobileNumber`, `EmergencyNumber`, `Photo`, `Profession`, `Nationality`) VALUES
(1, 'John Doe', 'johndoe@example.com', '$2y$10$vaECWKWULDoJrDHLzog5IeDfRdzqfzwMPvqYG50hJNvsWLWYZGrL.', '1980-01-01', 'Male', '2022-07-01', '1234567890', '0987654321', 'path/to/photo.jpg', 1, 'Country'),
(2, 'Jane Doe', 'janedoe@example.com', '$2y$10$.NjmyDPxMeSHHXV7YKIJ9uInIAugEgxqEFzLUq16FjLGS4evETpHW', '1985-01-01', 'Female', '2023-07-01', '70011555', '03969856', 'path/to/photo2.jpg', 3, 'Lebanese');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
