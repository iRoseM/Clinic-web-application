-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 28, 2025 at 01:58 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `theraflix`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `DoctorID` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `reason` text NOT NULL,
  `status` enum('Pending','Confirmed','Done') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `PatientID`, `DoctorID`, `date`, `time`, `reason`, `status`) VALUES
(25, 66, 77, '2025-03-12', '03:15:00', 'Having severe pain.', 'Pending'),
(29, 54, 22, '2025-03-06', '11:22:00', 'having pain', 'Done'),
(30, 54, 55, '2025-03-14', '11:23:00', 'sport injury', 'Done'),
(31, 54, 22, '2025-03-13', '04:19:00', 'pain', 'Done'),
(32, 55, 55, '2025-03-04', '00:35:00', 'pain in legs', 'Done'),
(33, 55, 22, '2025-02-27', '01:35:00', 'having severe pain', 'Done'),
(34, 55, 55, '2025-03-03', '02:36:00', 'pain', 'Done'),
(35, 55, 55, '2025-06-27', '01:36:00', 'checkup', 'Pending'),
(36, 54, 43, '2025-03-04', '16:23:00', 'having pain', 'Pending'),
(37, 55, 22, '2025-03-04', '21:45:00', 'having pain', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `uniqueFileName` varchar(255) NOT NULL,
  `SpecialityID` int(11) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `firstName`, `lastName`, `uniqueFileName`, `SpecialityID`, `emailAddress`, `password`) VALUES
(22, 'Selena', 'Gomez', 'doctor_67e50992637833.01607099.jpg', 2, 'selena@gmail.com', '$2y$10$wQaTlmrVp8EpW4R62Lkpzu6sSU.teK6PuAgTNI9Oek18xQaHp7nKa'),
(43, 'Noah', 'Saad', 'doctor_67e5093029ba70.42363022.jpg', 1, 'Noah@gmail.com', '$2y$10$XgIklpOT2x8bt.DGHfq4deFcvPXyTFtwP3GCKExZ7DfSkd4ES7CIG'),
(55, 'Lionel', 'Messi', 'doctor_67e5096a31b4a0.90373391.jpg', 3, 'messi@gmail.com', '$2y$10$5ewOkItFH.L/KmQs5aBzZeE31PpMyxwKLDNBaF4KFYX9T7XnGHH.S'),
(77, 'Ahmed', 'Ali', 'doctor_67e46c0f13ec73.33316311.jpg', 1, 'Ahmed@gmail.com', '$2y$10$tuHXkzAK7XFSbAyo0iXOpu7VhFSB1dK0L04P8rqE1oDf6aooBpFX2');

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `id` int(11) NOT NULL,
  `MedicationName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`id`, `MedicationName`) VALUES
(1, 'Muscle Relaxant Gel'),
(2, 'Pain Relief Patch'),
(3, 'Anti-inflammatory Tablets');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `Gender` enum('Male','Female') NOT NULL,
  `DoB` date NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `firstName`, `lastName`, `Gender`, `DoB`, `emailAddress`, `password`) VALUES
(22, ' Michael ', 'Jackson', 'Male', '1970-12-13', 'micheal@gmail.com', '$2y$10$qpW5/7ga31mVPYRTW2xa.OJmb1436AQRB0vQxg6zBUWQkr7CEXbDG'),
(54, 'Anwar', 'Mahmoud', 'Male', '2001-03-11', 'Anwar@gmail.com', '$2y$10$GztRNXUneVFo/AZl9AEkkuTA6ndQeOpA5Zy1suhF6RwtLeb79Hd3C'),
(55, 'Kylian', 'Mbappé', 'Male', '2000-12-06', 'mbappe@gmail.com', '$2y$10$zcP7mJ/hU7vU5mmEl7.2OeQjHxHiCpwejk7PAAxwQxRXaBw0qV3t2'),
(66, 'Ariana', 'Grande', 'Female', '1998-02-26', 'ariana@gmail.com', '$2y$10$hcj.ZzXXbq2UOF.FUpBiNuru.JLYdd7pAUjdri.nQ/Pve67i9CFiC');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `id` int(11) NOT NULL,
  `AppointmentID` int(11) NOT NULL,
  `MedicationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`id`, `AppointmentID`, `MedicationID`) VALUES
(17, 31, 2),
(18, 29, 1),
(19, 29, 3),
(20, 34, 1),
(21, 33, 2);

-- --------------------------------------------------------

--
-- Table structure for table `speciality`
--

CREATE TABLE `speciality` (
  `id` int(11) NOT NULL,
  `speciality` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `speciality`
--

INSERT INTO `speciality` (`id`, `speciality`) VALUES
(1, 'Physiotherapy'),
(2, 'Rehabilitation'),
(3, 'Sports Injury');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `DoctorID` (`DoctorID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`),
  ADD KEY `SpecialityID` (`SpecialityID`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `AppointmentID` (`AppointmentID`),
  ADD KEY `MedicationID` (`MedicationID`);

--
-- Indexes for table `speciality`
--
ALTER TABLE `speciality`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `speciality`
--
ALTER TABLE `speciality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`DoctorID`) REFERENCES `doctor` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`SpecialityID`) REFERENCES `speciality` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`AppointmentID`) REFERENCES `appointment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`MedicationID`) REFERENCES `medication` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
