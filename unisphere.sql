-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 01:04 PM
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
-- Database: `unisphere`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_details`
--

CREATE TABLE `admin_details` (
  `AdminID` varchar(50) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `ProfilePic` varchar(100) DEFAULT 'profile.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_details`
--

INSERT INTO `admin_details` (`AdminID`, `Name`, `Email`, `Password`, `ProfilePic`) VALUES
('UMT94148', 'Tang Chee Kin', 'UMT94148@mail.umt.edu.my', '$2y$10$cZQn2FVc4YfEyqm.lRhM7edL/g7BfeceIzDi2Cxkxw5y/yLoXFhQO', '_6850cb6306be9.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_table`
--

CREATE TABLE `announcement_table` (
  `AnnouncementID` varchar(10) NOT NULL,
  `Date` date DEFAULT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Message` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement_table`
--

INSERT INTO `announcement_table` (`AnnouncementID`, `Date`, `Title`, `Message`) VALUES
('ANN001', '2025-06-01', 'Library Maintenance', 'The library will be closed for maintenance on June 3rd from 8am to 12pm.'),
('ANN002', '2025-06-02', 'New Cafeteria Menu', 'Enjoy our updated cafeteria menu with more vegetarian options starting this week!'),
('ANN003', '2025-05-03', 'Transport Delay', 'Morning bus service will be delayed by 15 minutes due to roadworks.'),
('ANN004', '2025-06-04', 'Exam Timetable Released', 'The final exam timetable is now available on the student portal.'),
('ANN005', '2025-06-05', 'Sports Day Registration', 'Register for Sports Day events by June 10th at the admin office or online.'),
('ANN006', '2025-06-06', 'Guest Lecture: AI in Education', 'Join us for a guest lecture on AI in Education, June 8th, 2pm, Main Hall.'),
('ANN007', '2025-06-07', 'Lost & Found', 'A set of keys was found near the library. Please visit the security desk to claim them.'),
('ANN008', '2025-03-08', 'Facility Reservation Update', 'The online facility reservation system will be offline for upgrades on June 9th.'),
('ANN009', '2025-06-09', 'Blood Donation Drive', 'Participate in the campus blood donation drive on June 12th, 10am–4pm, Student Center.'),
('ANN010', '2025-06-10', 'Graduation Photo Session', 'Graduation photo sessions will be held June 15–16. Book your slot online.'),
('ANN011', '2025-12-04', 'Flood Issues In Campus', 'Please get out from the dorm to prevent any issues!!'),
('ANN012', '2025-06-19', 'Diploma Orientation', 'The Auditorium 1 will occupied from 8:30 am to 5:30 pm on 23 June 2025 for orientation purpose.'),
('ANN013', '2025-06-27', 'Flood Height', 'The Flood is coming on next week Friday. Please be aware.');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_views`
--

CREATE TABLE `announcement_views` (
  `ViewID` int(11) NOT NULL,
  `AnnouncementID` varchar(50) NOT NULL,
  `UserID` varchar(50) NOT NULL,
  `UserType` enum('Admin','Student','Lecturer') NOT NULL,
  `ViewDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement_views`
--

INSERT INTO `announcement_views` (`ViewID`, `AnnouncementID`, `UserID`, `UserType`, `ViewDate`) VALUES
(11, 'ANN010', 'UMT87800', 'Student', '2025-06-19 19:10:54'),
(12, 'ANN012', 'UMT87800', 'Student', '2025-06-19 19:25:17'),
(14, 'ANN014', 'UMT87800', 'Student', '2025-06-19 19:36:43'),
(15, 'ANN013', 'UMT87800', 'Student', '2025-06-20 13:12:40'),
(16, 'ANN015', 'UMT87800', 'Student', '2025-06-20 14:17:01'),
(18, 'ANN016', 'UMT87800', 'Student', '2025-06-20 15:35:49'),
(23, 'ANN017', 'UMT87800', 'Student', '2025-06-20 15:38:29'),
(34, 'ANN002', 'UMT87800', 'Student', '2025-06-20 15:46:53'),
(36, 'ANN009', 'UMT87800', 'Student', '2025-06-24 10:13:51'),
(37, 'ANN004', 'UMT87800', 'Student', '2025-06-24 10:13:59'),
(38, 'ANN007', 'UMT87800', 'Student', '2025-06-24 18:54:50'),
(39, 'ANN006', 'UMT87800', 'Student', '2025-06-25 13:44:13'),
(40, 'ANN012', 'UMT93485', 'Lecturer', '2025-06-25 14:01:24'),
(41, 'ANN010', 'UMT93485', 'Lecturer', '2025-06-25 14:02:18');

-- --------------------------------------------------------

--
-- Table structure for table `bookingtable`
--

CREATE TABLE `bookingtable` (
  `BookingID` int(50) NOT NULL,
  `BookTableSlot1` varchar(50) DEFAULT NULL,
  `BookTableSlot2` varchar(50) DEFAULT NULL,
  `BookTableSlot3` varchar(50) DEFAULT NULL,
  `BookTableSlot4` varchar(50) DEFAULT NULL,
  `UserID` varchar(50) DEFAULT NULL,
  `booked_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_slot1`
--

CREATE TABLE `booking_slot1` (
  `Slot1_ID` varchar(10) NOT NULL,
  `FacilityID` varchar(10) DEFAULT NULL,
  `Day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_slot1`
--

INSERT INTO `booking_slot1` (`Slot1_ID`, `FacilityID`, `Day`) VALUES
('S101', 'BD-01', 'Monday'),
('S102', 'BD-02', 'Monday'),
('S103', 'BD-03', 'Monday'),
('S104', 'BD-04', 'Monday'),
('S105', 'BK-01', 'Monday'),
('S106', 'BK-02', 'Monday'),
('S107', 'BK-03', 'Monday'),
('S108', 'BK-04', 'Monday'),
('S109', 'VB-01', 'Monday'),
('S110', 'VB-02', 'Monday'),
('S111', 'VB-03', 'Monday'),
('S112', 'VB-04', 'Monday'),
('S113', 'MR-01', 'Monday'),
('S114', 'MR-02', 'Monday'),
('S115', 'MR-03', 'Monday'),
('S116', 'MR-04', 'Monday'),
('S117', 'BD-01', 'Tuesday'),
('S118', 'BD-02', 'Tuesday'),
('S119', 'BD-03', 'Tuesday'),
('S120', 'BD-04', 'Tuesday'),
('S121', 'BK-01', 'Tuesday'),
('S122', 'BK-02', 'Tuesday'),
('S123', 'BK-03', 'Tuesday'),
('S124', 'BK-04', 'Tuesday'),
('S125', 'VB-01', 'Tuesday'),
('S126', 'VB-02', 'Tuesday'),
('S127', 'VB-03', 'Tuesday'),
('S128', 'VB-04', 'Tuesday'),
('S129', 'MR-01', 'Tuesday'),
('S130', 'MR-02', 'Tuesday'),
('S131', 'MR-03', 'Tuesday'),
('S132', 'MR-04', 'Tuesday'),
('S133', 'BD-01', 'Wednesday'),
('S134', 'BD-02', 'Wednesday'),
('S135', 'BD-03', 'Wednesday'),
('S136', 'BD-04', 'Wednesday'),
('S137', 'BK-01', 'Wednesday'),
('S138', 'BK-02', 'Wednesday'),
('S139', 'BK-03', 'Wednesday'),
('S140', 'BK-04', 'Wednesday'),
('S141', 'VB-01', 'Wednesday'),
('S142', 'VB-02', 'Wednesday'),
('S143', 'VB-03', 'Wednesday'),
('S144', 'VB-04', 'Wednesday'),
('S145', 'MR-01', 'Wednesday'),
('S146', 'MR-02', 'Wednesday'),
('S147', 'MR-03', 'Wednesday'),
('S148', 'MR-04', 'Wednesday'),
('S149', 'BD-01', 'Thursday'),
('S150', 'BD-02', 'Thursday'),
('S151', 'BD-03', 'Thursday'),
('S152', 'BD-04', 'Thursday'),
('S153', 'BK-01', 'Thursday'),
('S154', 'BK-02', 'Thursday'),
('S155', 'BK-03', 'Thursday'),
('S156', 'BK-04', 'Thursday'),
('S157', 'VB-01', 'Thursday'),
('S158', 'VB-02', 'Thursday'),
('S159', 'VB-03', 'Thursday'),
('S160', 'VB-04', 'Thursday'),
('S161', 'MR-01', 'Thursday'),
('S162', 'MR-02', 'Thursday'),
('S163', 'MR-03', 'Thursday'),
('S164', 'MR-04', 'Thursday'),
('S165', 'BD-01', 'Friday'),
('S166', 'BD-02', 'Friday'),
('S167', 'BD-03', 'Friday'),
('S168', 'BD-04', 'Friday'),
('S169', 'BK-01', 'Friday'),
('S170', 'BK-02', 'Friday'),
('S171', 'BK-03', 'Friday'),
('S172', 'BK-04', 'Friday'),
('S173', 'VB-01', 'Friday'),
('S174', 'VB-02', 'Friday'),
('S175', 'VB-03', 'Friday'),
('S176', 'VB-04', 'Friday'),
('S177', 'MR-01', 'Friday'),
('S178', 'MR-02', 'Friday'),
('S179', 'MR-03', 'Friday'),
('S180', 'MR-04', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `booking_slot2`
--

CREATE TABLE `booking_slot2` (
  `Slot2_ID` varchar(10) NOT NULL,
  `FacilityID` varchar(10) DEFAULT NULL,
  `Day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_slot2`
--

INSERT INTO `booking_slot2` (`Slot2_ID`, `FacilityID`, `Day`) VALUES
('S201', 'BD-01', 'Monday'),
('S202', 'BD-02', 'Monday'),
('S203', 'BD-03', 'Monday'),
('S204', 'BD-04', 'Monday'),
('S205', 'BK-01', 'Monday'),
('S206', 'BK-02', 'Monday'),
('S207', 'BK-03', 'Monday'),
('S208', 'BK-04', 'Monday'),
('S209', 'VB-01', 'Monday'),
('S210', 'VB-02', 'Monday'),
('S211', 'VB-03', 'Monday'),
('S212', 'VB-04', 'Monday'),
('S213', 'MR-01', 'Monday'),
('S214', 'MR-02', 'Monday'),
('S215', 'MR-03', 'Monday'),
('S216', 'MR-04', 'Monday'),
('S217', 'BD-01', 'Tuesday'),
('S218', 'BD-02', 'Tuesday'),
('S219', 'BD-03', 'Tuesday'),
('S220', 'BD-04', 'Tuesday'),
('S221', 'BK-01', 'Tuesday'),
('S222', 'BK-02', 'Tuesday'),
('S223', 'BK-03', 'Tuesday'),
('S224', 'BK-04', 'Tuesday'),
('S225', 'VB-01', 'Tuesday'),
('S226', 'VB-02', 'Tuesday'),
('S227', 'VB-03', 'Tuesday'),
('S228', 'VB-04', 'Tuesday'),
('S229', 'MR-01', 'Tuesday'),
('S230', 'MR-02', 'Tuesday'),
('S231', 'MR-03', 'Tuesday'),
('S232', 'MR-04', 'Tuesday'),
('S233', 'BD-01', 'Wednesday'),
('S234', 'BD-02', 'Wednesday'),
('S235', 'BD-03', 'Wednesday'),
('S236', 'BD-04', 'Wednesday'),
('S237', 'BK-01', 'Wednesday'),
('S238', 'BK-02', 'Wednesday'),
('S239', 'BK-03', 'Wednesday'),
('S240', 'BK-04', 'Wednesday'),
('S241', 'VB-01', 'Wednesday'),
('S242', 'VB-02', 'Wednesday'),
('S243', 'VB-03', 'Wednesday'),
('S244', 'VB-04', 'Wednesday'),
('S245', 'MR-01', 'Wednesday'),
('S246', 'MR-02', 'Wednesday'),
('S247', 'MR-03', 'Wednesday'),
('S248', 'MR-04', 'Wednesday'),
('S249', 'BD-01', 'Thursday'),
('S250', 'BD-02', 'Thursday'),
('S251', 'BD-03', 'Thursday'),
('S252', 'BD-04', 'Thursday'),
('S253', 'BK-01', 'Thursday'),
('S254', 'BK-02', 'Thursday'),
('S255', 'BK-03', 'Thursday'),
('S256', 'BK-04', 'Thursday'),
('S257', 'VB-01', 'Thursday'),
('S258', 'VB-02', 'Thursday'),
('S259', 'VB-03', 'Thursday'),
('S260', 'VB-04', 'Thursday'),
('S261', 'MR-01', 'Thursday'),
('S262', 'MR-02', 'Thursday'),
('S263', 'MR-03', 'Thursday'),
('S264', 'MR-04', 'Thursday'),
('S265', 'BD-01', 'Friday'),
('S266', 'BD-02', 'Friday'),
('S267', 'BD-03', 'Friday'),
('S268', 'BD-04', 'Friday'),
('S269', 'BK-01', 'Friday'),
('S270', 'BK-02', 'Friday'),
('S271', 'BK-03', 'Friday'),
('S272', 'BK-04', 'Friday'),
('S273', 'VB-01', 'Friday'),
('S274', 'VB-02', 'Friday'),
('S275', 'VB-03', 'Friday'),
('S276', 'VB-04', 'Friday'),
('S277', 'MR-01', 'Friday'),
('S278', 'MR-02', 'Friday'),
('S279', 'MR-03', 'Friday'),
('S280', 'MR-04', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `booking_slot3`
--

CREATE TABLE `booking_slot3` (
  `Slot3_ID` varchar(10) NOT NULL,
  `FacilityID` varchar(10) DEFAULT NULL,
  `Day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_slot3`
--

INSERT INTO `booking_slot3` (`Slot3_ID`, `FacilityID`, `Day`) VALUES
('S301', 'BD-01', 'Monday'),
('S302', 'BD-02', 'Monday'),
('S303', 'BD-03', 'Monday'),
('S304', 'BD-04', 'Monday'),
('S305', 'BK-01', 'Monday'),
('S306', 'BK-02', 'Monday'),
('S307', 'BK-03', 'Monday'),
('S308', 'BK-04', 'Monday'),
('S309', 'VB-01', 'Monday'),
('S310', 'VB-02', 'Monday'),
('S311', 'VB-03', 'Monday'),
('S312', 'VB-04', 'Monday'),
('S313', 'MR-01', 'Monday'),
('S314', 'MR-02', 'Monday'),
('S315', 'MR-03', 'Monday'),
('S316', 'MR-04', 'Monday'),
('S317', 'BD-01', 'Tuesday'),
('S318', 'BD-02', 'Tuesday'),
('S319', 'BD-03', 'Tuesday'),
('S320', 'BD-04', 'Tuesday'),
('S321', 'BK-01', 'Tuesday'),
('S322', 'BK-02', 'Tuesday'),
('S323', 'BK-03', 'Tuesday'),
('S324', 'BK-04', 'Tuesday'),
('S325', 'VB-01', 'Tuesday'),
('S326', 'VB-02', 'Tuesday'),
('S327', 'VB-03', 'Tuesday'),
('S328', 'VB-04', 'Tuesday'),
('S329', 'MR-01', 'Tuesday'),
('S330', 'MR-02', 'Tuesday'),
('S331', 'MR-03', 'Tuesday'),
('S332', 'MR-04', 'Tuesday'),
('S333', 'BD-01', 'Wednesday'),
('S334', 'BD-02', 'Wednesday'),
('S335', 'BD-03', 'Wednesday'),
('S336', 'BD-04', 'Wednesday'),
('S337', 'BK-01', 'Wednesday'),
('S338', 'BK-02', 'Wednesday'),
('S339', 'BK-03', 'Wednesday'),
('S340', 'BK-04', 'Wednesday'),
('S341', 'VB-01', 'Wednesday'),
('S342', 'VB-02', 'Wednesday'),
('S343', 'VB-03', 'Wednesday'),
('S344', 'VB-04', 'Wednesday'),
('S345', 'MR-01', 'Wednesday'),
('S346', 'MR-02', 'Wednesday'),
('S347', 'MR-03', 'Wednesday'),
('S348', 'MR-04', 'Wednesday'),
('S349', 'BD-01', 'Thursday'),
('S350', 'BD-02', 'Thursday'),
('S351', 'BD-03', 'Thursday'),
('S352', 'BD-04', 'Thursday'),
('S353', 'BK-01', 'Thursday'),
('S354', 'BK-02', 'Thursday'),
('S355', 'BK-03', 'Thursday'),
('S356', 'BK-04', 'Thursday'),
('S357', 'VB-01', 'Thursday'),
('S358', 'VB-02', 'Thursday'),
('S359', 'VB-03', 'Thursday'),
('S360', 'VB-04', 'Thursday'),
('S361', 'MR-01', 'Thursday'),
('S362', 'MR-02', 'Thursday'),
('S363', 'MR-03', 'Thursday'),
('S364', 'MR-04', 'Thursday'),
('S365', 'BD-01', 'Friday'),
('S366', 'BD-02', 'Friday'),
('S367', 'BD-03', 'Friday'),
('S368', 'BD-04', 'Friday'),
('S369', 'BK-01', 'Friday'),
('S370', 'BK-02', 'Friday'),
('S371', 'BK-03', 'Friday'),
('S372', 'BK-04', 'Friday'),
('S373', 'VB-01', 'Friday'),
('S374', 'VB-02', 'Friday'),
('S375', 'VB-03', 'Friday'),
('S376', 'VB-04', 'Friday'),
('S377', 'MR-01', 'Friday'),
('S378', 'MR-02', 'Friday'),
('S379', 'MR-03', 'Friday'),
('S380', 'MR-04', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `booking_slot4`
--

CREATE TABLE `booking_slot4` (
  `Slot4_ID` varchar(10) NOT NULL,
  `FacilityID` varchar(10) DEFAULT NULL,
  `Day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_slot4`
--

INSERT INTO `booking_slot4` (`Slot4_ID`, `FacilityID`, `Day`) VALUES
('S401', 'BD-01', 'Monday'),
('S402', 'BD-02', 'Monday'),
('S403', 'BD-03', 'Monday'),
('S404', 'BD-04', 'Monday'),
('S405', 'BK-01', 'Monday'),
('S406', 'BK-02', 'Monday'),
('S407', 'BK-03', 'Monday'),
('S408', 'BK-04', 'Monday'),
('S409', 'VB-01', 'Monday'),
('S410', 'VB-02', 'Monday'),
('S411', 'VB-03', 'Monday'),
('S412', 'VB-04', 'Monday'),
('S413', 'MR-01', 'Monday'),
('S414', 'MR-02', 'Monday'),
('S415', 'MR-03', 'Monday'),
('S416', 'MR-04', 'Monday'),
('S417', 'BD-01', 'Tuesday'),
('S418', 'BD-02', 'Tuesday'),
('S419', 'BD-03', 'Tuesday'),
('S420', 'BD-04', 'Tuesday'),
('S421', 'BK-01', 'Tuesday'),
('S422', 'BK-02', 'Tuesday'),
('S423', 'BK-03', 'Tuesday'),
('S424', 'BK-04', 'Tuesday'),
('S425', 'VB-01', 'Tuesday'),
('S426', 'VB-02', 'Tuesday'),
('S427', 'VB-03', 'Tuesday'),
('S428', 'VB-04', 'Tuesday'),
('S429', 'MR-01', 'Tuesday'),
('S430', 'MR-02', 'Tuesday'),
('S431', 'MR-03', 'Tuesday'),
('S432', 'MR-04', 'Tuesday'),
('S433', 'BD-01', 'Wednesday'),
('S434', 'BD-02', 'Wednesday'),
('S435', 'BD-03', 'Wednesday'),
('S436', 'BD-04', 'Wednesday'),
('S437', 'BK-01', 'Wednesday'),
('S438', 'BK-02', 'Wednesday'),
('S439', 'BK-03', 'Wednesday'),
('S440', 'BK-04', 'Wednesday'),
('S441', 'VB-01', 'Wednesday'),
('S442', 'VB-02', 'Wednesday'),
('S443', 'VB-03', 'Wednesday'),
('S444', 'VB-04', 'Wednesday'),
('S445', 'MR-01', 'Wednesday'),
('S446', 'MR-02', 'Wednesday'),
('S447', 'MR-03', 'Wednesday'),
('S448', 'MR-04', 'Wednesday'),
('S449', 'BD-01', 'Thursday'),
('S450', 'BD-02', 'Thursday'),
('S451', 'BD-03', 'Thursday'),
('S452', 'BD-04', 'Thursday'),
('S453', 'BK-01', 'Thursday'),
('S454', 'BK-02', 'Thursday'),
('S455', 'BK-03', 'Thursday'),
('S456', 'BK-04', 'Thursday'),
('S457', 'VB-01', 'Thursday'),
('S458', 'VB-02', 'Thursday'),
('S459', 'VB-03', 'Thursday'),
('S460', 'VB-04', 'Thursday'),
('S461', 'MR-01', 'Thursday'),
('S462', 'MR-02', 'Thursday'),
('S463', 'MR-03', 'Thursday'),
('S464', 'MR-04', 'Thursday'),
('S465', 'BD-01', 'Friday'),
('S466', 'BD-02', 'Friday'),
('S467', 'BD-03', 'Friday'),
('S468', 'BD-04', 'Friday'),
('S469', 'BK-01', 'Friday'),
('S470', 'BK-02', 'Friday'),
('S471', 'BK-03', 'Friday'),
('S472', 'BK-04', 'Friday'),
('S473', 'VB-01', 'Friday'),
('S474', 'VB-02', 'Friday'),
('S475', 'VB-03', 'Friday'),
('S476', 'VB-04', 'Friday'),
('S477', 'MR-01', 'Friday'),
('S478', 'MR-02', 'Friday'),
('S479', 'MR-03', 'Friday'),
('S480', 'MR-04', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `bus_schedule`
--

CREATE TABLE `bus_schedule` (
  `ScheduleID` varchar(50) NOT NULL,
  `Time` varchar(50) DEFAULT NULL,
  `Origin` varchar(50) DEFAULT NULL,
  `Destination` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus_schedule`
--

INSERT INTO `bus_schedule` (`ScheduleID`, `Time`, `Origin`, `Destination`) VALUES
('SCH001', '07:30 AM', 'LRT', 'UMT'),
('SCH002', '07:45 AM', 'LRT', 'UMT'),
('SCH003', '08:00 AM', 'LRT', 'UMT'),
('SCH004', '08:15 AM', 'LRT', 'UMT'),
('SCH005', '08:30 AM', 'LRT', 'UMT'),
('SCH006', '08:45 AM', 'LRT', 'UMT'),
('SCH007', '09:00 AM', 'LRT', 'UMT'),
('SCH008', '09:15 AM', 'LRT', 'UMT'),
('SCH009', '09:30 AM', 'LRT', 'UMT'),
('SCH010', '09:45 AM', 'LRT', 'UMT'),
('SCH011', '10:00 AM', 'LRT', 'UMT'),
('SCH012', '10:15 AM', 'LRT', 'UMT'),
('SCH013', '10:30 AM', 'LRT', 'UMT'),
('SCH014', '10:45 AM', 'LRT', 'UMT'),
('SCH015', '11:00 AM', 'LRT', 'UMT'),
('SCH016', '11:15 AM', 'LRT', 'UMT'),
('SCH017', '11:30 AM', 'LRT', 'UMT'),
('SCH018', '11:45 AM', 'LRT', 'UMT'),
('SCH019', '12:00 PM', 'LRT', 'UMT'),
('SCH020', '12:15 PM', 'LRT', 'UMT'),
('SCH021', '12:30 PM', 'LRT', 'UMT'),
('SCH022', '12:45 PM', 'LRT', 'UMT'),
('SCH023', '01:00 PM', 'LRT', 'UMT'),
('SCH024', '01:15 PM', 'LRT', 'UMT'),
('SCH025', '01:30 PM', 'LRT', 'UMT'),
('SCH026', '01:45 PM', 'LRT', 'UMT'),
('SCH027', '02:00 PM', 'LRT', 'UMT'),
('SCH028', '02:15 PM', 'LRT', 'UMT'),
('SCH029', '02:30 PM', 'LRT', 'UMT'),
('SCH030', '02:45 PM', 'LRT', 'UMT'),
('SCH031', '03:00 PM', 'LRT', 'UMT'),
('SCH032', '03:15 PM', 'LRT', 'UMT'),
('SCH033', '03:30 PM', 'LRT', 'UMT'),
('SCH034', '03:45 PM', 'LRT', 'UMT'),
('SCH035', '04:00 PM', 'LRT', 'UMT'),
('SCH036', '04:15 PM', 'LRT', 'UMT'),
('SCH037', '04:30 PM', 'LRT', 'UMT'),
('SCH038', '04:45 PM', 'LRT', 'UMT'),
('SCH039', '05:00 PM', 'LRT', 'UMT'),
('SCH040', '05:15 PM', 'LRT', 'UMT'),
('SCH041', '05:30 PM', 'LRT', 'UMT'),
('SCH042', '05:45 PM', 'LRT', 'UMT'),
('SCH043', '06:00 PM', 'LRT', 'UMT'),
('SCH044', '10:30 AM', 'UMT', 'LRT'),
('SCH045', '10:45 AM', 'UMT', 'LRT'),
('SCH046', '11:00 AM', 'UMT', 'LRT'),
('SCH047', '11:15 AM', 'UMT', 'LRT'),
('SCH048', '11:30 AM', 'UMT', 'LRT'),
('SCH049', '11:45 AM', 'UMT', 'LRT'),
('SCH050', '12:00 PM', 'UMT', 'LRT'),
('SCH051', '12:15 PM', 'UMT', 'LRT'),
('SCH052', '12:30 PM', 'UMT', 'LRT'),
('SCH053', '12:45 PM', 'UMT', 'LRT'),
('SCH054', '01:00 PM', 'UMT', 'LRT'),
('SCH055', '01:15 PM', 'UMT', 'LRT'),
('SCH056', '01:30 PM', 'UMT', 'LRT'),
('SCH057', '01:45 PM', 'UMT', 'LRT'),
('SCH058', '02:00 PM', 'UMT', 'LRT'),
('SCH059', '02:15 PM', 'UMT', 'LRT'),
('SCH060', '02:30 PM', 'UMT', 'LRT'),
('SCH061', '02:45 PM', 'UMT', 'LRT'),
('SCH062', '03:00 PM', 'UMT', 'LRT'),
('SCH063', '03:15 PM', 'UMT', 'LRT'),
('SCH064', '03:30 PM', 'UMT', 'LRT'),
('SCH065', '03:45 PM', 'UMT', 'LRT'),
('SCH066', '04:00 PM', 'UMT', 'LRT'),
('SCH067', '04:15 PM', 'UMT', 'LRT'),
('SCH068', '04:30 PM', 'UMT', 'LRT'),
('SCH069', '04:45 PM', 'UMT', 'LRT'),
('SCH070', '05:00 PM', 'UMT', 'LRT'),
('SCH071', '05:15 PM', 'UMT', 'LRT'),
('SCH072', '05:30 PM', 'UMT', 'LRT'),
('SCH073', '05:45 PM', 'UMT', 'LRT'),
('SCH074', '06:00 PM', 'UMT', 'LRT'),
('SCH075', '06:15 PM', 'UMT', 'LRT'),
('SCH076', '06:30 PM', 'UMT', 'LRT'),
('SCH077', '06:45 PM', 'UMT', 'LRT'),
('SCH078', '07:00 PM', 'UMT', 'LRT');

-- --------------------------------------------------------

--
-- Table structure for table `changetimeslot`
--

CREATE TABLE `changetimeslot` (
  `ModuleCode` varchar(50) NOT NULL,
  `OriginDate` date DEFAULT NULL,
  `ChangeDate` date DEFAULT NULL,
  `TimeTableSlot1` varchar(50) DEFAULT NULL,
  `TimeTableSlot2` varchar(50) DEFAULT NULL,
  `TimeTableSlot3` varchar(50) DEFAULT NULL,
  `TimeTableSlot4` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `changetimeslot`
--

INSERT INTO `changetimeslot` (`ModuleCode`, `OriginDate`, `ChangeDate`, `TimeTableSlot1`, `TimeTableSlot2`, `TimeTableSlot3`, `TimeTableSlot4`) VALUES
('AAPP016-4-2-CAN-L-1', '2025-07-01', '2025-06-09', NULL, NULL, NULL, 'S403'),
('AAPP016-4-2-CAN-T-1', '2025-06-19', '2025-06-09', NULL, NULL, NULL, 'S402'),
('AAPP016-4-2-EAP-T-1', '2025-06-16', '2025-06-20', 'S113', NULL, NULL, NULL),
('AAPP016-4-2-VIP-L-1', '2025-06-04', '2025-06-19', NULL, NULL, NULL, 'S410'),
('AAPP016-5-2-CSF-T-1', '2025-06-05', '2025-06-09', NULL, NULL, NULL, 'S401'),
('AAPP016-5-2-DA-L-1', '2025-06-03', '2025-06-13', NULL, NULL, NULL, 'S415'),
('AAPP016-5-2-DBM-L-1', '2025-06-02', '2025-06-05', NULL, NULL, NULL, 'S410'),
('AAPP016-5-2-NTW-T-1', '2025-06-04', '2025-06-06', NULL, NULL, NULL, 'S415');

-- --------------------------------------------------------

--
-- Table structure for table `class_information`
--

CREATE TABLE `class_information` (
  `ClassID` varchar(10) NOT NULL,
  `Type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_information`
--

INSERT INTO `class_information` (`ClassID`, `Type`) VALUES
('Auditorium', 'Auditorium'),
('B-01-01', 'Classroom'),
('B-01-02', 'Classroom');

-- --------------------------------------------------------

--
-- Table structure for table `facility_information`
--

CREATE TABLE `facility_information` (
  `FacilityID` varchar(10) NOT NULL,
  `Type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facility_information`
--

INSERT INTO `facility_information` (`FacilityID`, `Type`) VALUES
('BD-01', 'Badminton Court'),
('BD-02', 'Badminton Court'),
('BD-03', 'Badminton Court'),
('BD-04', 'Badminton Court'),
('BK-01', 'Basketball Court'),
('BK-02', 'Basketball Court'),
('BK-03', 'Basketball Court'),
('BK-04', 'Basketball Court'),
('MR-01', 'Meeting Room'),
('MR-02', 'Meeting Room'),
('MR-03', 'Meeting Room'),
('MR-04', 'Meeting Room'),
('VB-01', 'Volleyball Court'),
('VB-02', 'Volleyball Court'),
('VB-03', 'Volleyball Court'),
('VB-04', 'Volleyball Court');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `TicketID` int(11) NOT NULL,
  `FeedbackType` varchar(50) DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `UserID` varchar(50) DEFAULT NULL,
  `Progress` varchar(50) DEFAULT 'Pending',
  `Date_Time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`TicketID`, `FeedbackType`, `Description`, `UserID`, `Progress`, `Date_Time`) VALUES
(1, 'Complaint', 'Is this working', 'TP076143', 'Finished', '2025-06-01 19:40:08'),
(2, 'Complaint', 'testttttttttttttttttttttttttttttttttttttttttttt sfsgdhjkjhgfdghnbv', 'UMT70507', 'Processing', '2025-06-14 14:07:13'),
(3, 'Suggestion', 'I think you need to disable the Advanced Booking features in the Library to ensure a fair booking system for both lecturer and student.', 'UMT87800', 'Processing', '2025-06-15 06:50:56'),
(4, 'Complaint', 'asdfghjkjtrewtyunbvcdfhjtrd dfytredfvbnhtresxcvbgfd', 'UMT87800', 'Processing', '2025-06-15 06:53:44'),
(5, 'Suggestion', 'sdjuytres589876543 4567876dvbnjdd', 'UMT87800', 'Finished', '2025-06-15 06:53:54'),
(6, 'Suggestion', 'dfghtrdcbnh 3456543fbgfd', 'UMT87800', 'Pending', '2025-06-15 06:54:02'),
(7, 'Complaint', 'sdfgtrd 1481452f8d5grdc ', 'UMT87800', 'Finished', '2025-06-15 06:54:39'),
(8, 'Complaint', 'fghytredcvb rtyhjuytrdv 345673 tfd', 'UMT87800', 'Finished', '2025-06-15 06:54:48'),
(9, 'Suggestion', 'asgtrfvfdj 34567323r7 fgytrd', 'UMT87800', 'Pending', '2025-06-15 06:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `holiday_schedule`
--

CREATE TABLE `holiday_schedule` (
  `HolidayID` int(11) NOT NULL,
  `Date` date DEFAULT NULL,
  `Event` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `holiday_schedule`
--

INSERT INTO `holiday_schedule` (`HolidayID`, `Date`, `Event`) VALUES
(1, '2025-01-01', 'New Year\'s Day'),
(2, '2025-01-14', 'YDPB Negeri Sembilan\'s Birthday'),
(3, '2025-01-27', 'Israk and Mikraj'),
(4, '2025-01-29', 'Chinese New Year'),
(5, '2025-01-30', 'Chinese New Year Holiday'),
(6, '2025-02-01', 'Federal Territory Day'),
(7, '2025-02-11', 'Thaipusam'),
(8, '2025-02-20', 'Independence Declaration Day'),
(9, '2025-03-02', 'Awal Ramadan'),
(10, '2025-03-03', 'Awal Ramadan Holiday'),
(11, '2025-03-04', 'Installation of Sultan Terengganu'),
(12, '2025-03-18', 'Nuzul Al-Quran'),
(13, '2025-03-23', 'Sultan of Johor\'s Birthday'),
(14, '2025-03-24', 'Sultan of Johor\'s Birthday Holiday'),
(15, '2025-03-30', 'Hari Raya Aidilfitri Holiday'),
(16, '2025-03-30', 'Sabah Governor\'s Birthday'),
(17, '2025-03-31', 'Hari Raya Aidilfitri'),
(18, '2025-04-01', 'Hari Raya Aidilfitri Holiday'),
(19, '2025-04-02', 'Hari Raya Aidilfitri Holiday (Melaka)'),
(20, '2025-04-18', 'Good Friday'),
(21, '2025-04-26', 'Sultan of Terengganu\'s Birthday'),
(22, '2025-04-27', 'Sultan of Terengganu\'s Birthday Holiday'),
(23, '2025-04-28', 'Special Public Holiday'),
(24, '2025-05-01', 'Labour Day'),
(25, '2025-05-12', 'Wesak Day'),
(26, '2025-05-17', 'Raja Perlis\' Birthday'),
(27, '2025-05-22', 'Hari Hol Pahang'),
(28, '2025-05-30', 'Harvest Festival'),
(29, '2025-05-31', 'Harvest Festival Holiday'),
(30, '2025-06-01', 'Hari Gawai'),
(31, '2025-06-02', 'Hari Gawai Holiday'),
(32, '2025-06-02', 'Agong\'s Birthday'),
(33, '2025-06-03', 'Agong\'s Birthday Holiday'),
(34, '2025-06-06', 'Arafat Day'),
(35, '2025-06-07', 'Hari Raya Haji'),
(36, '2025-06-08', 'Hari Raya Haji Holiday'),
(37, '2025-06-09', 'Hari Raya Haji Holiday'),
(38, '2025-06-22', 'Sultan of Kedah\'s Birthday'),
(39, '2025-06-27', 'Awal Muharram'),
(40, '2025-06-29', 'Awal Muharram Holiday'),
(41, '2025-07-07', 'Georgetown World Heritage City Day'),
(42, '2025-07-12', 'Penang Governor\'s Birthday'),
(43, '2025-07-22', 'Sarawak Day'),
(44, '2025-07-30', 'Sultan of Pahang\'s Birthday'),
(45, '2025-07-31', 'Hari Hol Almarhum Sultan Iskandar'),
(46, '2025-08-24', 'Melaka Governor\'s Birthday'),
(47, '2025-08-25', 'Melaka Governor\'s Birthday Holiday'),
(48, '2025-08-31', 'Merdeka Day'),
(49, '2025-09-01', 'Merdeka Day Holiday'),
(50, '2025-09-05', 'Prophet Muhammad\'s Birthday'),
(51, '2025-09-07', 'Prophet Muhammad\'s Birthday Holiday'),
(52, '2025-09-16', 'Malaysia Day'),
(53, '2025-09-29', 'Sultan of Kelantan\'s Birthday'),
(54, '2025-09-30', 'Sultan of Kelantan\'s Birthday Holiday'),
(55, '2025-10-11', 'Sarawak Governor\'s Birthday'),
(56, '2025-10-20', 'Deepavali'),
(57, '2025-11-07', 'Sultan of Perak\'s Birthday'),
(58, '2025-12-11', 'Sultan of Selangor\'s Birthday'),
(59, '2025-12-24', 'Christmas Eve'),
(60, '2025-12-25', 'Christmas Day');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_details`
--

CREATE TABLE `lecturer_details` (
  `LecturerID` varchar(50) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `ProfilePic` varchar(100) DEFAULT 'profile.png',
  `JobTitle` varchar(100) DEFAULT NULL,
  `Department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturer_details`
--

INSERT INTO `lecturer_details` (`LecturerID`, `Name`, `Email`, `Password`, `ProfilePic`, `JobTitle`, `Department`) VALUES
('UMT14133', 'Peter', 'UMT14133@mail.umt.edu.my', '$2y$10$ofEIlC9u50qC/', 'profile.png', 'Associate Professor', 'Faculty of Computing'),
('UMT39517', 'Alice ', 'UMT39517@mail.umt.edu.my', '$2y$10$2MmLJbhklFYh2', 'profile.png', 'Senior Lecturer', 'Faculty of Technology'),
('UMT60152', 'Alex', 'UMT60152@mail.umt.edu.my', '$2y$10$BnBXe7kEhG83m', 'profile.png', 'Lecturer', 'Faculty of Language'),
('UMT70507', 'Chen Yi Hung', 'UMT70507@mail.umt.edu.my', '$2y$10$l2Xpt7CBnwX16zfznFq3xOJ4R8oW5uwU8z3.Kr2eATavXLToddgYa', 'UMT70507_6850d2fbd6445.png', 'Professor', 'Faculty of Technology'),
('UMT87325', 'Benjamin', 'UMT87325@mail.umt.edu.my', '$2y$10$MvIUGVxuEJFrt', 'profile.png', 'Professor', 'Faculty of Computing'),
('UMT93485', 'Wan Hon Kit', 'UMT93485@mail.umt.edu.my', '$2y$10$Fzj7VvEPdtwubeESu4cBtui/9Dmzu7/y10fxmv4veEzVeYpIjdyJy', 'profile.png', 'Associate Professor', 'Faculty of Computing');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_timetable`
--

CREATE TABLE `lecturer_timetable` (
  `LecturerID` varchar(50) NOT NULL,
  `ModuleCode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturer_timetable`
--

INSERT INTO `lecturer_timetable` (`LecturerID`, `ModuleCode`) VALUES
('UMT14133', 'AAPP016-4-2-CAN-L-1'),
('UMT14133', 'AAPP016-4-2-CAN-T-1'),
('UMT14133', 'AAPP016-4-2-VIP-L-1'),
('UMT14133', 'AAPP016-4-2-VIP-T-1'),
('UMT14133', 'AAPP016-5-2-DA-L-1'),
('UMT14133', 'AAPP016-5-2-DA-T-1'),
('UMT14133', 'AAPP016-5-2-DBM-L-1'),
('UMT14133', 'AAPP016-5-2-DBM-T-1'),
('UMT39517', 'AAPP016-5-2-AIN-L-1'),
('UMT39517', 'AAPP016-5-2-AIN-T-1'),
('UMT39517', 'AAPP016-5-2-CSF-L-1'),
('UMT39517', 'AAPP016-5-2-CSF-T-1'),
('UMT39517', 'AAPP016-5-2-NTW-L-1'),
('UMT39517', 'AAPP016-5-2-NTW-T-1'),
('UMT39517', 'AAPP016-6-2-SWA-L-1'),
('UMT39517', 'AAPP016-6-2-SWA-T-1'),
('UMT60152', 'AAPP016-4-2-ARS-L-1'),
('UMT60152', 'AAPP016-4-2-ARS-T-1'),
('UMT60152', 'AAPP016-4-2-EAP-L-1'),
('UMT60152', 'AAPP016-4-2-EAP-T-1'),
('UMT60152', 'AAPP016-5-2-DMATH-L-1'),
('UMT60152', 'AAPP016-5-2-DMATH-T-1'),
('UMT87325', 'AAPP016-5-2-CAP-L-1'),
('UMT87325', 'AAPP016-5-2-DM-L-1'),
('UMT87325', 'AAPP016-5-2-DM-T-1'),
('UMT87325', 'AAPP016-6-2-CPP-L-1'),
('UMT87325', 'AAPP016-6-2-CPP-T-1'),
('UMT87325', 'AAPP016-6-2-PSM-L-1'),
('UMT87325', 'AAPP016-6-2-PSM-T-1'),
('UMT87325', 'AAPP016-6-2-SDM-L-1'),
('UMT87325', 'AAPP016-6-2-SDM-T-1'),
('UMT93485', 'AAPP016-6-2-DM-L-1'),
('UMT93485', 'AAPP016-6-2-DM-T-1'),
('UMT93485', 'AAPP016-6-2-DMG-L-1'),
('UMT93485', 'AAPP016-6-2-DMG-T-1'),
('UMT93485', 'AAPP016-6-2-DST-L-1'),
('UMT93485', 'AAPP016-6-2-DST-T-1'),
('UMT93485', 'AAPP016-6-2-REQ-L-1'),
('UMT93485', 'AAPP016-6-2-REQ-T-1'),
('UMT93485', 'AAPP016-6-2-SWT-L-1'),
('UMT93485', 'AAPP016-6-2-SWT-T-1');

-- --------------------------------------------------------

--
-- Table structure for table `library`
--

CREATE TABLE `library` (
  `BookID` varchar(50) NOT NULL,
  `Title` varchar(50) DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `Author` varchar(50) DEFAULT NULL,
  `Genre` varchar(50) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `library`
--

INSERT INTO `library` (`BookID`, `Title`, `Description`, `Author`, `Genre`, `Status`) VALUES
('1043350595', 'The Origins of the Second World War', 'A.J.P. Taylor analyzes the causes of World War II, challenging traditional narratives and exploring the complexities of international relations.', 'A.J.P. Taylor', 'History', 'Reserved'),
('1192374797', 'Waiting for Godot', 'Samuel Beckett’s absurdist play that explores themes of existence and the human condition through the interactions of two characters waiting for someone.', 'Samuel Beckett', 'Arts & Literature', 'Reserved'),
('1241255350', 'The Pragmatic Programmer', 'A guide for software developers that provides practical advice and methodologies for mastering programming and improving productivity.', 'David Thomas', 'Technology', 'Reserved'),
('224496812', 'The Age of Revolution: 1789-1848', 'Eric Hobsbawm explores the revolutionary changes in Europe during this period, focusing on political, economic, and social transformations.', 'Eric Hobsbawm', 'History', 'Available'),
('964633555', 'Ulysses', 'James Joyce’s landmark modernist novel, a reimagining of Homer’s Odyssey set in Dublin, focusing on the inner thoughts of its characters.', 'James Joyce', 'Arts & Literature', 'Borrowed'),
('9780028659657', 'International Encyclopedia of the Social Sciences', 'A detailed reference that encompasses the full spectrum of social science disciplines, offering insights and analyses from leading scholars.', 'William A. Darity', 'Social Sciences', 'Available'),
('9780029079409', 'The Rules of Sociological Method', 'Émile Durkheim outlines the principles and methods of sociology, advocating for a scientific approach to social phenomena.', 'Émile Durkheim', 'Social Sciences', 'Borrowed'),
('9780060787295', 'The Crusades: The Authoritative History of the War', 'Thomas Asbridge provides a thorough account of the Crusades, exploring their historical context and long-lasting impact on East-West relations.', 'Thomas Asbridge', 'History', 'Reserved'),
('9780063032378', 'Freakonomics: A Rogue Economist Explores the Hidde', 'Steven D. Levitt use economic theory to explore unconventional questions about human behavior.', 'Steven D. Levitt', 'Social Sciences', 'Available'),
('9780133387520', 'Cloud Computing: Concepts, Technology & Architectu', 'A comprehensive guide that covers the fundamental concepts, technologies, and architecture of cloud computing, providing insights into its implementation and impact on businesses and society.', 'Thomas Erl', 'Technology', 'Reserved'),
('9780133594140', 'Computer Networking: A Top-Down Approach', 'A comprehensive introduction to computer networking, presenting concepts from the application layer down to the physical layer in an engaging manner.', 'James F. Kurose', 'Technology', 'Available'),
('9780134661742', 'Clean Code', 'A practical guide to writing clean, maintainable code, emphasizing best practices and principles for software craftsmanship in agile environments.', 'Robert C. Martin', 'Technology', 'Available'),
('9780137909100', 'Code: The Hidden Language of Computer Hardware and', 'An insightful exploration of how computers work, explaining the fundamental principles of coding and hardware in an engaging and accessible manner.', 'Charles Petzold', 'Technology', 'Available'),
('9780140449082', 'The Histories', 'Herodotus’ work is a detailed account of historical events, exploring the rise and fall of empires, cultures, and significant figures of the ancient world.', 'Herodotus', 'History', 'Available'),
('9780141037622', 'The Fabric of the Cosmos', 'Brian Greene explores the nature of space and time, delving into the mysteries of the universe with clarity and insight.', 'Brian Greene', 'Science', 'Borrowed'),
('9780143120179', 'What Technology Wants', 'This book explores the intricate relationship between technology and humanity, arguing that technology has its own desires and influences our lives in profound ways.', 'Kevin Kelly', 'Technology', 'Available'),
('9780143131342', 'Things Fall Apart', 'Chinua Achebe’s novel that tells the story of Okonkwo, a leader in a Nigerian village, and the impact of colonialism on traditional societies.', 'Chinua Achebe', 'Arts & Literature', 'Reserved'),
('9780195133547', 'The Power Elite', 'C. Wright Mills examines the interconnections among the political, military, and economic elites in America.', 'C. Wright Mills', 'Social Sciences', 'Reserved'),
('9780195218213', 'Technology in World History', 'This book examines the significant role that technology has played throughout world history, highlighting key innovations and their effects on societies and cultures across different eras.', 'W. Bernard Carlson', 'Technology', 'Available'),
('9780201835953', 'The Mythical Man-Month', 'A collection of essays on software engineering and project management, discussing the challenges and complexities of managing software projects.', 'Frederick P. Brooks Jr.', 'Technology', 'Borrowed'),
('9780201896831', 'The Art of Computer Programming', 'A comprehensive and authoritative guide to algorithms and programming techniques, widely regarded as a fundamental text in computer science.', 'Donald E. Knuth', 'Technology', 'Available'),
('9780203024379', 'Media Technology and Society', 'Analyzes the evolution of media technologies from the telegraph to the internet, exploring how these technologies have shaped communication and societal change over time.', 'Brian Winston', 'Technology', 'Available'),
('9780226041414', 'The Waste Land', 'T.S. Eliot’s influential poem that reflects the disillusionment of the post-World War I era, utilizing a wide range of literary references.', 'T.S. Eliot', 'Arts & Literature', 'Available'),
('9780226458113', 'The Structure of Scientific Revolutions', 'Thomas S. Kuhn introduces the concept of paradigm shifts in science, arguing that scientific progress is not linear but rather episodic.', 'Thomas S. Kuhn', 'Science', 'Available'),
('9780241463352', 'Moby-Dick', 'Herman Melville’s epic tale of obsession and revenge, centered around Captain Ahab’s pursuit of the elusive white whale.', 'Herman Melville', 'Arts & Literature', 'Available'),
('9780241956816', 'The Periodic Table', 'Primo Levi reflects on his experiences as a chemist and a Holocaust survivor, using the periodic table as a framework for his memoir.', 'Primo Levi', 'Science', 'Available'),
('9780255365765', 'The Road to Serfdom', 'Friedrich Hayek argues against central planning and collectivism, advocating for individual freedom and economic liberty.', 'Friedrich Hayek', 'Social Sciences', 'Available'),
('9780316725095', 'The Liberation Trilogy', 'Rick Atkinson chronicles the American military campaign in World War II, offering a detailed account of key battles and strategic decisions.', 'Rick Atkinson', 'History', 'Available'),
('9780333432785', 'To the Lighthouse', 'Virginia Woolf’s novel that captures the complexities of human relationships and the passage of time through the lens of a family’s experiences.', 'Virginia Woolf', 'Arts & Literature', 'Available'),
('9780345539434', 'Cosmos', 'Carl Sagan takes readers on a journey through space and time, exploring the universe and our place within it in an engaging and poetic manner.', 'Carl Sagan', 'Science', 'Available'),
('9780374533557', 'Thinking, Fast and Slow', 'Daniel Kahneman explores the dual systems of thought that drive our decisions, offering insights into cognitive biases and human behavior.', 'Daniel Kahneman', 'Science', 'Available'),
('9780375727153', 'Genes, girls, and Gamow: after the Double helix', 'James D. Watson recounts the discovery of the structure of DNA, providing a personal account of the scientific process and collaboration.', 'James D. Watson', 'Science', 'Reserved'),
('9780385062763', 'The Cold War: A New History', 'John Lukacs provides a concise overview of the Cold War, analyzing its origins, key events, and implications for the modern world.', 'John Lukacs', 'History', 'Available'),
('9780393354324', 'Guns, Germs, and Steel: The Fates of Human Societi', 'Jared Diamond explores the factors that shaped civilizations, arguing that geography and environment played crucial roles in human history.', 'Jared Diamond', 'History', 'Available'),
('9780415476355', 'The Social Science Encyclopedia', 'This comprehensive reference work covers a wide range of topics in the social sciences, providing definitions, explanations, and key concepts.', 'Adam Kuper', 'Social Sciences', 'Available'),
('9780465034253', 'The Interpretation of Cultures', 'Clifford Geertz presents a collection of essays on cultural anthropology, emphasizing the importance of understanding cultures in context.', 'Clifford Geertz', 'Social Sciences', 'Available'),
('9780486817194', 'The History of the Peloponnesian War', 'Thucydides offers a critical account of the conflict between Athens and Sparta, providing insights into power, politics, and human nature.', 'Thucydides', 'History', 'Available'),
('9780486855288', 'Science and the Modern World', 'Alfred North Whitehead explores the relationship between science and modern philosophy, examining how scientific advances have shaped contemporary thought.', 'Alfred North Whitehead', 'Science', 'Reserved'),
('9780553077728', 'A Brief History of Time', 'Stephen Hawking provides an accessible overview of cosmology, discussing black holes, the Big Bang, and the nature of time itself.', 'Stephen Hawking', 'Science', 'Available'),
('9780593310878', 'The Wealth of Nations', 'Adam Smith’s foundational work in economics discusses the nature of wealth, free markets, and the division of labor.', 'Adam Smith', 'Social Sciences', 'Available'),
('9780674369542', 'Capital in the Twenty-First Century', 'Thomas Piketty analyzes wealth and income inequality, offering insights into economic trends and policy implications.', 'Thomas Piketty', 'Social Sciences', 'Available'),
('9780679423089', 'The Decline and Fall of the Roman Empire', 'Edward Gibbon’s classic work analyzes the reasons behind the fall of Rome, combining history with philosophical reflections on civilization.', 'Edward Gibbon', 'History', 'Borrowed'),
('9780715640159', 'The Singularity Is Near', 'A thought-provoking examination of the future of humanity as technology advances, discussing the potential for machines to surpass human intelligence.', 'Ray Kurzweil', 'Technology', 'Available'),
('9780743539135', 'Team of Rivals: The Political Genius of Abraham Li', 'Doris Kearns Goodwin examines Lincoln’s leadership style and his ability to unite a diverse cabinet during a tumultuous time in American history.', 'Doris Kearns Goodwin', 'History', 'Reserved'),
('9780783501147', 'The Civil War: A Narrative', 'Shelby Foote offers a detailed and engaging narrative of the American Civil War, capturing the complexities and human experiences of the conflict.', 'Shelby Foote', 'History', 'Borrowed'),
('9780785839965', 'The Communist manifesto and Das Kapital', 'Karl Marx critiques political economy, exploring the capitalist system and its implications for society and labor.', 'Karl Marx', 'Social Sciences', 'Available'),
('9780881462265', 'The Protestant Ethic and the Spirit of Capitalism', 'Steven Overman connects Protestant ethic, capitalism, and sport, showing how Protestant virtues shaped capitalism and influenced American institutions, especially organized sport and its core values.', 'Steven J. Overman', 'Social Sciences', 'Reserved'),
('9781118826461', 'Architecting the Cloud', 'Offers a detailed look at cloud computing service models, providing design decisions and best practices for successful cloud architecture.', 'Michael J. Kavis', 'Technology', 'Available'),
('9781133187813', 'Introduction to the Theory of Computation', 'A foundational text that covers the theoretical underpinnings of computer science, including automata theory, computability, and complexity.', 'Michael Sipser', 'Technology', 'Available'),
('9781250887313', 'The Sixth Extinction: An Unnatural History', 'Elizabeth Kolbert examines the ongoing extinction event caused by human activity, highlighting the urgency of addressing environmental issues.', 'Elizabeth Kolbert', 'Science', 'Available'),
('9781292153964', 'Artificial Intelligence: A Modern Approach', 'A foundational textbook on artificial intelligence, covering a wide range of topics from problem-solving to machine learning and robotics.', 'Stuart Russell', 'Technology', 'Available'),
('9781324066248', 'The Elegant Universe', 'Brian Greene explains string theory and its implications for understanding the universe, making complex concepts accessible to a general audience.', 'Brian Greene', 'Science', 'Available'),
('9781324095545', 'The Divine Comedy', 'Dante Alighieri’s epic poem that explores the realms of the afterlife, including Inferno, Purgatorio, and Paradiso.', 'Dante Alighieri', 'Arts & Literature', 'Available'),
('9781398831186', 'On the Origin of Species', 'Charles Darwin’s groundbreaking work that introduced the theory of evolution by natural selection, fundamentally changing our understanding of biology.', 'Charles Darwin', 'Science', 'Available'),
('9781400119523', 'The Disappearing Spoon', 'Sam Kean tells the stories behind the elements of the periodic table, blending science with history and biography.', 'Sam Kean', 'Science', 'Available'),
('9781405865319', 'Madame Bovary', 'Gustave Flaubert’s novel about a woman’s quest for passion and fulfillment, ultimately leading to her tragic downfall.', 'Gustave Flaubert', 'Arts & Literature', 'Borrowed'),
('9781420947731', 'The Elementary Forms of Religious Life', 'Émile Durkheim investigates the simplest forms of religious life, examining their social functions and meanings.', 'Émile Durkheim', 'Social Sciences', 'Available'),
('9781428815889', 'The Presentation of Self in Everyday Life', 'Erving Goffman explores how individuals manage their identities in social interactions, using theatrical metaphors.', 'Erving Goffman', 'Social Sciences', 'Available'),
('9781452206233', 'The emergence of sociological theory', 'A comprehensive look at classical social theory, tracing key thinkers and developments from Renaissance Europe through the Enlightenment to Mead and the Industrial Age.', 'Johnathan H. Turner', 'Social Sciences', 'Borrowed'),
('9781456610814', 'A People\'s History of the United States', 'Howard Zinn presents an alternative perspective on American history, focusing on the experiences of marginalized groups and social movements.', 'Howard Zinn', 'History', 'Available'),
('9781483078656', 'Postwar: A History of Europe Since 1945', 'Tony Judt offers a sweeping narrative of Europe’s history after World War II, examining the political, social, and cultural transformations.', 'Tony Judt', 'History', 'Available'),
('9781486256242', 'The History of the Ancient World: From the Earlies', 'Susan Wise Bauer provides a sweeping narrative of ancient history, covering civilizations from Mesopotamia to Rome in an engaging manner.', 'Susan Wise Bauer', 'History', 'Available'),
('9781536152975', 'Democracy in America', 'Alexis de Tocqueville analyzes American society and its political system, offering insights into democracy and social conditions.', 'Alexis de Tocqueville', 'Social Sciences', 'Available'),
('9781543616989', 'The Demon-Haunted World: Science as a Candle in th', 'Carl Sagan advocates for scientific literacy and skepticism, arguing against superstition and pseudoscience.', 'Carl Sagan', 'Science', 'Available'),
('9781598535600', 'Silent Spring', 'Rachel Carson’s seminal work that raised awareness about the dangers of pesticides and their impact on the environment, sparking the modern environmental movement.', 'Rachel Carson', 'Science', 'Available'),
('9781608194872', 'The Canterbury Tales', 'Geoffrey Chaucer’s collection of stories told by pilgrims traveling to Canterbury, offering a vivid portrayal of medieval society.', 'Geoffrey Chaucer', 'Arts & Literature', 'Reserved'),
('9781614646280', 'The Selfish Gene', 'Richard Dawkins presents a gene-centered view of evolution, arguing that genes are the primary unit of selection in evolution.', 'Richard Dawkins', 'Science', 'Borrowed'),
('9781619258297', 'Beloved', 'This book offers critical essays on Beloved, exploring themes like motherhood, slavery\'s psychological impact, memory repression, and links to the real-life slave behind Morrison’s story.', 'Maureen N. Eke', 'Arts & Literature', 'Available'),
('9781631060243', 'The Complete Works of William Shakespeare', 'A comprehensive collection of all plays and poems by William Shakespeare, showcasing his contributions to English literature.', 'William Shakespeare', 'Arts & Literature', 'Available'),
('9781638991373', 'The worst journey in the world', 'The Worst Journey in the World: The Graphic Novel, Volume 1 – Making Our Easting Down retells a harrowing Antarctic expedition through vivid illustrations and narrative adaptation of the original memoir.', 'Sarah Airriess', 'Arts & Literature', 'Available'),
('9781641701969', 'Les Misérables', 'Victor Hugo’s epic story of redemption and justice, set against the backdrop of post-revolutionary France.', 'Victor Hugo', 'Arts & Literature', 'Available'),
('9781662508165', 'The Shallows', 'Explores the effects of the internet on our brains and thinking processes, arguing that our reliance on technology can diminish our cognitive abilities.', 'Holly Craig', 'Technology', 'Available'),
('9781668473672', 'Encyclopedia of Information Science and Technology', 'An extensive reference work that covers a wide range of topics in information science and technology, offering valuable insights for researchers, practitioners, and students alike.', 'Mehdi Khosrow-Pour', 'Technology', 'Available'),
('9781775411246', 'The Theory of the Leisure Class', 'Thorstein Veblen analyzes the social and economic dynamics of leisure and consumption in capitalist societies.', 'Thorstein Veblen', 'Social Sciences', 'Reserved'),
('9781782267812', 'War and Peace', 'Leo Tolstoy’s monumental novel that intertwines the lives of several families during the Napoleonic Wars, exploring themes of fate and free will.', 'Leo Tolstoy', 'Arts & Literature', 'Available'),
('9781839406942', 'Crime and Punishment', 'Fyodor Dostoevsky’s psychological drama about a young man’s moral dilemmas following a crime, delving into themes of guilt and redemption.', 'Fyodor Dostoevsky', 'Arts & Literature', 'Reserved'),
('9781857990256', 'Modern Times: A History of the World from the 1920', 'This book provides a comprehensive overview of global history during a transformative period, examining political, social, and economic changes.', 'Paul Johnson', 'History', 'Reserved'),
('9781906838317', 'Don Quixote', 'Miguel de Cervantes’ novel follows the adventures of an aging nobleman who becomes a self-styled knight, exploring themes of reality and illusion.', 'Miguel de Cervantes', 'Arts & Literature', 'Borrowed'),
('9781927925171', 'Pride and Prejudice', 'Jane Austen’s beloved novel about love, class, and social expectations in early 19th-century England.', 'Jane Austen', 'Arts & Literature', 'Available'),
('9781951038748', 'The Great Gatsby', 'F. Scott Fitzgerald’s classic novel about the American Dream, exploring themes of wealth, love, and social change in the 1920s.', 'F. Scott Fitzgerald', 'Arts & Literature', 'Available'),
('9781982115852', 'The Code Breaker', 'A captivating narrative that tells the story of the innovators who created the digital revolution, showcasing their creativity, collaboration, and the impact of their work on modern technology.', 'Walter Isaacson', 'Technology', 'Borrowed'),
('9781982130848', 'Bowling Alone: The Collapse and Revival of America', 'Robert D. Putnam examines the decline of social capital in the U.S. and its implications for community and civic life.', 'Robert D. Putnam', 'Social Sciences', 'Available'),
('9781982762742', 'The Diary of a Young Girl', 'Anne Frank’s poignant diary provides a firsthand account of life hiding from the Nazis during World War II, capturing the resilience of the human spirit.', 'Anne Frank', 'History', 'Available'),
('9782806235527', 'The Tipping Point: How Little Things Can Make a Bi', 'Malcolm Gladwell explores how small changes can lead to significant societal shifts, introducing the concept of tipping points.', 'Malcolm Gladwell', 'Social Sciences', 'Borrowed'),
('9783864702112', 'The Second Machine Age', 'Looks at the impact of technology on work and prosperity, discussing how digital technology is transforming our economy and society in unprecedented ways.', 'Erik Brynjolfsson', 'Technology', 'Reserved'),
('9783985311767', 'The French Revolution: A History', 'Thomas Carlyle presents a dramatic account of the French Revolution, exploring its causes, key figures, and profound effects on society.', 'Thomas Carlyle', 'History', 'Available'),
('9786163012500', 'The Brothers Karamazov', 'Dostoevsky’s final novel that explores deep philosophical and theological questions through the lives of the Karamazov brothers.', 'Fyodor Dostoevsky', 'Arts & Literature', 'Available'),
('9787308161459', 'The Silk Roads: A New History of the World', 'Peter Frankopan reexamines global history through the lens of the Silk Roads, highlighting their significance in shaping cultures and economies.', 'Peter Frankopan', 'History', 'Borrowed'),
('9787508629865', 'The Master Switch', 'Investigates the rise and fall of information empires, revealing how innovation and regulation shape the future of communication technologies.', 'Tim Wu', 'Technology', 'Available'),
('9787508638119', 'The Emperor of All Maladies: A Biography of Cancer', 'Siddhartha Mukherjee presents a comprehensive history of cancer, exploring its impact on humanity and the ongoing battle against the disease.', 'Siddhartha Mukherjee', 'Science', 'Available'),
('9787508678375', 'The Wright Brothers', 'David McCullough tells the inspiring story of the Wright brothers and their pioneering achievements in aviation, highlighting their determination and innovation.', 'David McCullough', 'History', 'Available'),
('9787508682426', 'The Gene: An Intimate History', 'Siddhartha Mukherjee explores the history and science of genetics, weaving personal stories with scientific discoveries.', 'Siddhartha Mukherjee', 'Science', 'Available'),
('9787543332089', 'One Hundred Years of Solitude', 'Gabriel García Márquez’s magical realist novel that chronicles the Buendía family over several generations in the fictional town of Macondo.', 'Gabriel García Márquez', 'Arts & Literature', 'Available'),
('9787544779838', 'The Rise and Fall of the Third Reich', 'William L. Shirer’s comprehensive history examines the Nazi regime, its ideologies, and the events that led to its downfall.', 'William L. Shirer', 'History', 'Available'),
('9788178081359', 'Design Patterns', 'This book introduces design patterns in software engineering, providing solutions to common problems and promoting reusable object-oriented software design.', 'Erich Gamma', 'Technology', 'Available'),
('9789571362250', 'Outliers: The Story of Success', 'Malcolm Gladwell analyzes the factors that contribute to high levels of success, emphasizing the role of context and opportunity.', 'Malcolm Gladwell', 'Social Sciences', 'Available'),
('9789740205067', 'Surely You\'re Joking, Mr. Feynman!', 'Richard P. Feynman shares anecdotes from his life as a physicist, illustrating the joy of discovery and the importance of curiosity.', 'Richard P. Feynman', 'Science', 'Available'),
('9789740214854', 'The Man Who Knew Infinity: A Life of the Genius Ra', 'Robert Kanigel tells the inspiring story of mathematician Srinivasa Ramanujan and his contributions to mathematics.', 'Robert Kanigel', 'Science', 'Available'),
('9789742281731', 'The Immortal Life of Henrietta Lacks', 'Rebecca Skloot tells the story of Henrietta Lacks and her immortal cell line, raising ethical questions about medical research and race.', 'Rebecca Skloot', 'Science', 'Available'),
('9798350833881', 'The Sociological Imagination', 'C. Wright Mills argues for the importance of understanding the relationship between individual experiences and larger social forces.', 'C. Wright Mills', 'Social Sciences', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `module_information`
--

CREATE TABLE `module_information` (
  `ModuleCode` varchar(50) NOT NULL,
  `ModuleName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module_information`
--

INSERT INTO `module_information` (`ModuleCode`, `ModuleName`) VALUES
('AAPP016-4-2-ARS-L-1', 'Academic Research Skills'),
('AAPP016-4-2-ARS-T-1', 'Academic Research Skills'),
('AAPP016-4-2-CAN-L-1', 'Computer Architecture & Networking'),
('AAPP016-4-2-CAN-T-1', 'Computer Architecture & Networking'),
('AAPP016-4-2-EAP-L-1', 'English for Academic Purpose'),
('AAPP016-4-2-EAP-T-1', 'English for Academic Purpose'),
('AAPP016-4-2-VIP-L-1', 'Visual & Interactive Programming'),
('AAPP016-4-2-VIP-T-1', 'Visual & Interactive Programming'),
('AAPP016-5-2-AIN-L-1', 'Introduction to AI'),
('AAPP016-5-2-AIN-T-1', 'Introduction to AI'),
('AAPP016-5-2-CAP-L-1', 'Capstone Project'),
('AAPP016-5-2-CSF-L-1', 'Cyber Security & Forensics'),
('AAPP016-5-2-CSF-T-1', 'Cyber Security & Forensics'),
('AAPP016-5-2-DA-L-1', 'Introduction to Data Analytics'),
('AAPP016-5-2-DA-T-1', 'Introduction to Data Analytics'),
('AAPP016-5-2-DBM-L-1', 'Database Management'),
('AAPP016-5-2-DBM-T-1', 'Database Management'),
('AAPP016-5-2-DM-L-1', 'Data Analytics and Visualisation'),
('AAPP016-5-2-DM-T-1', 'Data Analytics and Visualisation'),
('AAPP016-5-2-DMATH-L-1', 'Discrete Mathematics'),
('AAPP016-5-2-DMATH-T-1', 'Discrete Mathematics'),
('AAPP016-5-2-NTW-L-1', 'Networking Technologies'),
('AAPP016-5-2-NTW-T-1', 'Networking Technologies'),
('AAPP016-6-2-CPP-L-1', 'Concurrent Programming'),
('AAPP016-6-2-CPP-T-1', 'Concurrent Programming'),
('AAPP016-6-2-DM-L-1', 'Data Mining'),
('AAPP016-6-2-DM-T-1', 'Data Mining'),
('AAPP016-6-2-DMG-L-1', 'Data Management'),
('AAPP016-6-2-DMG-T-1', 'Data Management'),
('AAPP016-6-2-DST-L-1', 'Data Structures'),
('AAPP016-6-2-DST-T-1', 'Data Structures'),
('AAPP016-6-2-PSM-L-1', 'Probability and Statistical Modelling'),
('AAPP016-6-2-PSM-T-1', 'Probability and Statistical Modelling'),
('AAPP016-6-2-REQ-L-1', 'Requirements Engineering'),
('AAPP016-6-2-REQ-T-1', 'Requirements Engineering'),
('AAPP016-6-2-SDM-L-1', 'System Development Methods'),
('AAPP016-6-2-SDM-T-1', 'System Development Methods'),
('AAPP016-6-2-SWA-L-1', 'Software Architecture'),
('AAPP016-6-2-SWA-T-1', 'Software Architecture'),
('AAPP016-6-2-SWT-L-1', 'Software Testing'),
('AAPP016-6-2-SWT-T-1', 'Software Testing');

-- --------------------------------------------------------

--
-- Table structure for table `program_information`
--

CREATE TABLE `program_information` (
  `IntakeCode` varchar(50) NOT NULL,
  `ProgramName` varchar(100) DEFAULT NULL,
  `EducationLevel` varchar(50) DEFAULT NULL,
  `Semester` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_information`
--

INSERT INTO `program_information` (`IntakeCode`, `ProgramName`, `EducationLevel`, `Semester`) VALUES
('AFCF2307ICT', 'Foundation in Information & Communication Technology', 'Foundation', '2'),
('APD2F2403(DA)', 'Bachelor Degree in Data Analytics', 'Degree', '3'),
('APD2F2403SE', 'Bachelor Degree in Software Engineering', 'Degree', '3'),
('UCDF2308ICT(DA)', 'Diploma in Data Analytics', 'Diploma', '5'),
('UCDF2308ICT(SE)', 'Diploma In Software Engineering', 'Diploma', '5');

-- --------------------------------------------------------

--
-- Table structure for table `student_details`
--

CREATE TABLE `student_details` (
  `StudentID` varchar(50) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `ProfilePic` varchar(100) DEFAULT 'profile.png',
  `Gender` varchar(10) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `IntakeCode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_details`
--

INSERT INTO `student_details` (`StudentID`, `Name`, `Email`, `Password`, `ProfilePic`, `Gender`, `DateOfBirth`, `Country`, `IntakeCode`) VALUES
('UMT66184', 'Jack Hung', 'UMT66184@mail.umt.edu.my', '$2y$10$nF9tZJS6ky2Bse81JHRNWOn6QythUGXN3s5NqajSLNZsDKHyauqcC', 'profile.png', 'Male', '2000-01-01', 'Australia', 'UCDF2308ICT(DA)'),
('UMT87800', 'Daniel Go Zi Yang', 'UMT87800@mail.umt.edu.my', '$2y$10$iq7xCk/.gDHWv2Lw0SGJBOipvFEdAkwQN1RX.roBqY/MP9Sod2JE2', 'UMT87800_685632a757552.png', 'Male', '2005-06-25', 'Malaysia', 'UCDF2308ICT(SE)');

-- --------------------------------------------------------

--
-- Table structure for table `student_timetable`
--

CREATE TABLE `student_timetable` (
  `IntakeCode` varchar(50) NOT NULL,
  `ModuleCode` varchar(50) NOT NULL,
  `TimeTableSlot1` varchar(50) DEFAULT NULL,
  `TimeTableSlot2` varchar(50) DEFAULT NULL,
  `TimeTableSlot3` varchar(50) DEFAULT NULL,
  `TimeTableSlot4` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_timetable`
--

INSERT INTO `student_timetable` (`IntakeCode`, `ModuleCode`, `TimeTableSlot1`, `TimeTableSlot2`, `TimeTableSlot3`, `TimeTableSlot4`) VALUES
('AFCF2307ICT', 'AAPP016-4-2-ARS-L-1', NULL, NULL, 'S302', NULL),
('AFCF2307ICT', 'AAPP016-4-2-ARS-T-1', NULL, NULL, 'S304', NULL),
('AFCF2307ICT', 'AAPP016-4-2-CAN-L-1', NULL, 'S204', NULL, NULL),
('AFCF2307ICT', 'AAPP016-4-2-CAN-T-1', 'S111', NULL, NULL, NULL),
('AFCF2307ICT', 'AAPP016-4-2-EAP-L-1', 'S102', NULL, NULL, NULL),
('AFCF2307ICT', 'AAPP016-4-2-EAP-T-1', NULL, 'S202', NULL, NULL),
('AFCF2307ICT', 'AAPP016-4-2-VIP-L-1', 'S107', NULL, NULL, NULL),
('AFCF2307ICT', 'AAPP016-4-2-VIP-T-1', NULL, NULL, 'S309', NULL),
('APD2F2403(DA)', 'AAPP016-6-2-CPP-L-1', 'S110', NULL, NULL, NULL),
('APD2F2403(DA)', 'AAPP016-6-2-CPP-T-1', NULL, NULL, 'S312', NULL),
('APD2F2403(DA)', 'AAPP016-6-2-DM-L-1', 'S103', NULL, NULL, NULL),
('APD2F2403(DA)', 'AAPP016-6-2-DM-T-1', NULL, NULL, 'S315', NULL),
('APD2F2403(DA)', 'AAPP016-6-2-DMG-L-1', NULL, 'S209', NULL, NULL),
('APD2F2403(DA)', 'AAPP016-6-2-DMG-T-1', NULL, NULL, NULL, 'S411'),
('APD2F2403(DA)', 'AAPP016-6-2-DST-L-1', NULL, NULL, 'S303', NULL),
('APD2F2403(DA)', 'AAPP016-6-2-DST-T-1', NULL, NULL, NULL, 'S414'),
('APD2F2403(DA)', 'AAPP016-6-2-PSM-L-1', 'S115', NULL, NULL, NULL),
('APD2F2403(DA)', 'AAPP016-6-2-PSM-T-1', NULL, 'S215', NULL, NULL),
('APD2F2403SE', 'AAPP016-6-2-DST-L-1', 'S114', NULL, NULL, NULL),
('APD2F2403SE', 'AAPP016-6-2-DST-T-1', NULL, 'S214', NULL, NULL),
('APD2F2403SE', 'AAPP016-6-2-REQ-L-1', NULL, 'S206', NULL, NULL),
('APD2F2403SE', 'AAPP016-6-2-REQ-T-1', NULL, NULL, NULL, 'S407'),
('APD2F2403SE', 'AAPP016-6-2-SDM-L-1', 'S101', NULL, NULL, NULL),
('APD2F2403SE', 'AAPP016-6-2-SDM-T-1', NULL, 'S212', NULL, NULL),
('APD2F2403SE', 'AAPP016-6-2-SWA-L-1', NULL, NULL, 'S308', NULL),
('APD2F2403SE', 'AAPP016-6-2-SWA-T-1', NULL, NULL, 'S310', NULL),
('APD2F2403SE', 'AAPP016-6-2-SWT-L-1', NULL, NULL, 'S306', NULL),
('APD2F2403SE', 'AAPP016-6-2-SWT-T-1', NULL, NULL, NULL, 'S405'),
('UCDF2308ICT(DA)', 'AAPP016-5-2-DA-L-1', NULL, NULL, 'S305', NULL),
('UCDF2308ICT(DA)', 'AAPP016-5-2-DA-T-1', NULL, NULL, 'S313', NULL),
('UCDF2308ICT(DA)', 'AAPP016-5-2-DBM-L-1', NULL, 'S201', NULL, NULL),
('UCDF2308ICT(DA)', 'AAPP016-5-2-DBM-T-1', NULL, NULL, NULL, 'S404'),
('UCDF2308ICT(DA)', 'AAPP016-5-2-DM-L-1', NULL, 'S210', NULL, NULL),
('UCDF2308ICT(DA)', 'AAPP016-5-2-DM-T-1', NULL, NULL, NULL, 'S413'),
('UCDF2308ICT(DA)', 'AAPP016-5-2-DMATH-L-1', NULL, NULL, 'S301', NULL),
('UCDF2308ICT(DA)', 'AAPP016-5-2-DMATH-T-1', NULL, NULL, 'S311', NULL),
('UCDF2308ICT(SE)', 'AAPP016-5-2-AIN-L-1', NULL, 'S203', NULL, NULL),
('UCDF2308ICT(SE)', 'AAPP016-5-2-AIN-T-1', NULL, 'S205', NULL, NULL),
('UCDF2308ICT(SE)', 'AAPP016-5-2-CAP-L-1', 'S109', NULL, NULL, NULL),
('UCDF2308ICT(SE)', 'AAPP016-5-2-CSF-L-1', 'S112', NULL, NULL, NULL),
('UCDF2308ICT(SE)', 'AAPP016-5-2-CSF-T-1', NULL, 'S211', NULL, NULL),
('UCDF2308ICT(SE)', 'AAPP016-5-2-NTW-L-1', NULL, 'S208', NULL, NULL),
('UCDF2308ICT(SE)', 'AAPP016-5-2-NTW-T-1', NULL, NULL, 'S307', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `timetable_slot1`
--

CREATE TABLE `timetable_slot1` (
  `Slot1_ID` varchar(10) NOT NULL,
  `ClassID` varchar(10) DEFAULT NULL,
  `Day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable_slot1`
--

INSERT INTO `timetable_slot1` (`Slot1_ID`, `ClassID`, `Day`) VALUES
('S101', 'B-01-01', 'Monday'),
('S102', 'B-01-02', 'Monday'),
('S103', 'Auditorium', 'Monday'),
('S104', 'B-01-01', 'Tuesday'),
('S105', 'B-01-02', 'Tuesday'),
('S106', 'Auditorium', 'Tuesday'),
('S107', 'B-01-01', 'Wednesday'),
('S108', 'B-01-02', 'Wednesday'),
('S109', 'Auditorium', 'Wednesday'),
('S110', 'B-01-01', 'Thursday'),
('S111', 'B-01-02', 'Thursday'),
('S112', 'Auditorium', 'Thursday'),
('S113', 'B-01-01', 'Friday'),
('S114', 'B-01-02', 'Friday'),
('S115', 'Auditorium', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `timetable_slot2`
--

CREATE TABLE `timetable_slot2` (
  `Slot2_ID` varchar(10) NOT NULL,
  `ClassID` varchar(10) DEFAULT NULL,
  `Day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable_slot2`
--

INSERT INTO `timetable_slot2` (`Slot2_ID`, `ClassID`, `Day`) VALUES
('S201', 'B-01-01', 'Monday'),
('S202', 'B-01-02', 'Monday'),
('S203', 'Auditorium', 'Monday'),
('S204', 'B-01-01', 'Tuesday'),
('S205', 'B-01-02', 'Tuesday'),
('S206', 'Auditorium', 'Tuesday'),
('S207', 'B-01-01', 'Wednesday'),
('S208', 'B-01-02', 'Wednesday'),
('S209', 'Auditorium', 'Wednesday'),
('S210', 'B-01-01', 'Thursday'),
('S211', 'B-01-02', 'Thursday'),
('S212', 'Auditorium', 'Thursday'),
('S213', 'B-01-01', 'Friday'),
('S214', 'B-01-02', 'Friday'),
('S215', 'Auditorium', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `timetable_slot3`
--

CREATE TABLE `timetable_slot3` (
  `Slot3_ID` varchar(10) NOT NULL,
  `ClassID` varchar(10) DEFAULT NULL,
  `Day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable_slot3`
--

INSERT INTO `timetable_slot3` (`Slot3_ID`, `ClassID`, `Day`) VALUES
('S301', 'B-01-01', 'Monday'),
('S302', 'B-01-02', 'Monday'),
('S303', 'Auditorium', 'Monday'),
('S304', 'B-01-01', 'Tuesday'),
('S305', 'B-01-02', 'Tuesday'),
('S306', 'Auditorium', 'Tuesday'),
('S307', 'B-01-01', 'Wednesday'),
('S308', 'B-01-02', 'Wednesday'),
('S309', 'Auditorium', 'Wednesday'),
('S310', 'B-01-01', 'Thursday'),
('S311', 'B-01-02', 'Thursday'),
('S312', 'Auditorium', 'Thursday'),
('S313', 'B-01-01', 'Friday'),
('S314', 'B-01-02', 'Friday'),
('S315', 'Auditorium', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `timetable_slot4`
--

CREATE TABLE `timetable_slot4` (
  `Slot4_ID` varchar(10) NOT NULL,
  `ClassID` varchar(10) DEFAULT NULL,
  `Day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable_slot4`
--

INSERT INTO `timetable_slot4` (`Slot4_ID`, `ClassID`, `Day`) VALUES
('S401', 'B-01-01', 'Monday'),
('S402', 'B-01-02', 'Monday'),
('S403', 'Auditorium', 'Monday'),
('S404', 'B-01-01', 'Tuesday'),
('S405', 'B-01-02', 'Tuesday'),
('S406', 'Auditorium', 'Tuesday'),
('S407', 'B-01-01', 'Wednesday'),
('S408', 'B-01-02', 'Wednesday'),
('S409', 'Auditorium', 'Wednesday'),
('S410', 'B-01-01', 'Thursday'),
('S411', 'B-01-02', 'Thursday'),
('S412', 'Auditorium', 'Thursday'),
('S413', 'B-01-01', 'Friday'),
('S414', 'B-01-02', 'Friday'),
('S415', 'Auditorium', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `viewed_feedback`
--

CREATE TABLE `viewed_feedback` (
  `TicketID` int(11) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `UserType` enum('Admin','Student','Lecturer') NOT NULL,
  `ViewDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `viewed_feedback`
--

INSERT INTO `viewed_feedback` (`TicketID`, `UserID`, `UserType`, `ViewDate`) VALUES
(3, 'UMT87800', 'Student', '2025-06-20 06:51:42'),
(4, 'UMT87800', 'Student', '2025-06-20 06:52:02'),
(7, 'UMT87800', 'Student', '2025-06-20 06:57:58'),
(8, 'UMT87800', 'Student', '2025-06-20 07:02:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_details`
--
ALTER TABLE `admin_details`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `announcement_table`
--
ALTER TABLE `announcement_table`
  ADD PRIMARY KEY (`AnnouncementID`);

--
-- Indexes for table `announcement_views`
--
ALTER TABLE `announcement_views`
  ADD PRIMARY KEY (`ViewID`),
  ADD UNIQUE KEY `AnnouncementID` (`AnnouncementID`,`UserID`,`UserType`);

--
-- Indexes for table `bookingtable`
--
ALTER TABLE `bookingtable`
  ADD PRIMARY KEY (`BookingID`),
  ADD KEY `BookTableSlot1` (`BookTableSlot1`),
  ADD KEY `BookTableSlot2` (`BookTableSlot2`),
  ADD KEY `BookTableSlot3` (`BookTableSlot3`),
  ADD KEY `BookTableSlot4` (`BookTableSlot4`);

--
-- Indexes for table `booking_slot1`
--
ALTER TABLE `booking_slot1`
  ADD PRIMARY KEY (`Slot1_ID`),
  ADD KEY `FacilityID` (`FacilityID`);

--
-- Indexes for table `booking_slot2`
--
ALTER TABLE `booking_slot2`
  ADD PRIMARY KEY (`Slot2_ID`),
  ADD KEY `FacilityID` (`FacilityID`);

--
-- Indexes for table `booking_slot3`
--
ALTER TABLE `booking_slot3`
  ADD PRIMARY KEY (`Slot3_ID`),
  ADD KEY `FacilityID` (`FacilityID`);

--
-- Indexes for table `booking_slot4`
--
ALTER TABLE `booking_slot4`
  ADD PRIMARY KEY (`Slot4_ID`),
  ADD KEY `FacilityID` (`FacilityID`);

--
-- Indexes for table `bus_schedule`
--
ALTER TABLE `bus_schedule`
  ADD PRIMARY KEY (`ScheduleID`);

--
-- Indexes for table `changetimeslot`
--
ALTER TABLE `changetimeslot`
  ADD PRIMARY KEY (`ModuleCode`),
  ADD KEY `fk_slot1` (`TimeTableSlot1`),
  ADD KEY `fk_slot2` (`TimeTableSlot2`),
  ADD KEY `fk_slot3` (`TimeTableSlot3`),
  ADD KEY `fk_slot4` (`TimeTableSlot4`);

--
-- Indexes for table `class_information`
--
ALTER TABLE `class_information`
  ADD PRIMARY KEY (`ClassID`);

--
-- Indexes for table `facility_information`
--
ALTER TABLE `facility_information`
  ADD PRIMARY KEY (`FacilityID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`TicketID`);

--
-- Indexes for table `holiday_schedule`
--
ALTER TABLE `holiday_schedule`
  ADD PRIMARY KEY (`HolidayID`);

--
-- Indexes for table `lecturer_details`
--
ALTER TABLE `lecturer_details`
  ADD PRIMARY KEY (`LecturerID`);

--
-- Indexes for table `lecturer_timetable`
--
ALTER TABLE `lecturer_timetable`
  ADD PRIMARY KEY (`LecturerID`,`ModuleCode`),
  ADD KEY `ModuleCode` (`ModuleCode`);

--
-- Indexes for table `library`
--
ALTER TABLE `library`
  ADD PRIMARY KEY (`BookID`);

--
-- Indexes for table `module_information`
--
ALTER TABLE `module_information`
  ADD PRIMARY KEY (`ModuleCode`);

--
-- Indexes for table `program_information`
--
ALTER TABLE `program_information`
  ADD PRIMARY KEY (`IntakeCode`);

--
-- Indexes for table `student_details`
--
ALTER TABLE `student_details`
  ADD PRIMARY KEY (`StudentID`),
  ADD KEY `IntakeCode` (`IntakeCode`);

--
-- Indexes for table `student_timetable`
--
ALTER TABLE `student_timetable`
  ADD PRIMARY KEY (`IntakeCode`,`ModuleCode`),
  ADD KEY `ModuleCode` (`ModuleCode`),
  ADD KEY `TimeTableSlot1` (`TimeTableSlot1`),
  ADD KEY `TimeTableSlot2` (`TimeTableSlot2`),
  ADD KEY `TimeTableSlot3` (`TimeTableSlot3`),
  ADD KEY `TimeTableSlot4` (`TimeTableSlot4`);

--
-- Indexes for table `timetable_slot1`
--
ALTER TABLE `timetable_slot1`
  ADD PRIMARY KEY (`Slot1_ID`),
  ADD KEY `ClassID` (`ClassID`);

--
-- Indexes for table `timetable_slot2`
--
ALTER TABLE `timetable_slot2`
  ADD PRIMARY KEY (`Slot2_ID`),
  ADD KEY `ClassID` (`ClassID`);

--
-- Indexes for table `timetable_slot3`
--
ALTER TABLE `timetable_slot3`
  ADD PRIMARY KEY (`Slot3_ID`),
  ADD KEY `ClassID` (`ClassID`);

--
-- Indexes for table `timetable_slot4`
--
ALTER TABLE `timetable_slot4`
  ADD PRIMARY KEY (`Slot4_ID`),
  ADD KEY `ClassID` (`ClassID`);

--
-- Indexes for table `viewed_feedback`
--
ALTER TABLE `viewed_feedback`
  ADD PRIMARY KEY (`TicketID`,`UserID`,`UserType`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement_views`
--
ALTER TABLE `announcement_views`
  MODIFY `ViewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `bookingtable`
--
ALTER TABLE `bookingtable`
  MODIFY `BookingID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `TicketID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `holiday_schedule`
--
ALTER TABLE `holiday_schedule`
  MODIFY `HolidayID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookingtable`
--
ALTER TABLE `bookingtable`
  ADD CONSTRAINT `bookingtable_ibfk_1` FOREIGN KEY (`BookTableSlot1`) REFERENCES `booking_slot1` (`Slot1_ID`),
  ADD CONSTRAINT `bookingtable_ibfk_2` FOREIGN KEY (`BookTableSlot2`) REFERENCES `booking_slot2` (`Slot2_ID`),
  ADD CONSTRAINT `bookingtable_ibfk_3` FOREIGN KEY (`BookTableSlot3`) REFERENCES `booking_slot3` (`Slot3_ID`),
  ADD CONSTRAINT `bookingtable_ibfk_4` FOREIGN KEY (`BookTableSlot4`) REFERENCES `booking_slot4` (`Slot4_ID`);

--
-- Constraints for table `booking_slot1`
--
ALTER TABLE `booking_slot1`
  ADD CONSTRAINT `booking_slot1_ibfk_1` FOREIGN KEY (`FacilityID`) REFERENCES `facility_information` (`FacilityID`);

--
-- Constraints for table `booking_slot2`
--
ALTER TABLE `booking_slot2`
  ADD CONSTRAINT `booking_slot2_ibfk_1` FOREIGN KEY (`FacilityID`) REFERENCES `facility_information` (`FacilityID`);

--
-- Constraints for table `booking_slot3`
--
ALTER TABLE `booking_slot3`
  ADD CONSTRAINT `booking_slot3_ibfk_1` FOREIGN KEY (`FacilityID`) REFERENCES `facility_information` (`FacilityID`);

--
-- Constraints for table `booking_slot4`
--
ALTER TABLE `booking_slot4`
  ADD CONSTRAINT `booking_slot4_ibfk_1` FOREIGN KEY (`FacilityID`) REFERENCES `facility_information` (`FacilityID`);

--
-- Constraints for table `changetimeslot`
--
ALTER TABLE `changetimeslot`
  ADD CONSTRAINT `changetimeslot_ibfk_1` FOREIGN KEY (`ModuleCode`) REFERENCES `module_information` (`ModuleCode`),
  ADD CONSTRAINT `fk_slot1` FOREIGN KEY (`TimeTableSlot1`) REFERENCES `timetable_slot1` (`Slot1_ID`),
  ADD CONSTRAINT `fk_slot2` FOREIGN KEY (`TimeTableSlot2`) REFERENCES `timetable_slot2` (`Slot2_ID`),
  ADD CONSTRAINT `fk_slot3` FOREIGN KEY (`TimeTableSlot3`) REFERENCES `timetable_slot3` (`Slot3_ID`),
  ADD CONSTRAINT `fk_slot4` FOREIGN KEY (`TimeTableSlot4`) REFERENCES `timetable_slot4` (`Slot4_ID`);

--
-- Constraints for table `lecturer_timetable`
--
ALTER TABLE `lecturer_timetable`
  ADD CONSTRAINT `lecturer_timetable_ibfk_1` FOREIGN KEY (`LecturerID`) REFERENCES `lecturer_details` (`LecturerID`),
  ADD CONSTRAINT `lecturer_timetable_ibfk_2` FOREIGN KEY (`ModuleCode`) REFERENCES `module_information` (`ModuleCode`);

--
-- Constraints for table `student_details`
--
ALTER TABLE `student_details`
  ADD CONSTRAINT `student_details_ibfk_1` FOREIGN KEY (`IntakeCode`) REFERENCES `program_information` (`IntakeCode`);

--
-- Constraints for table `student_timetable`
--
ALTER TABLE `student_timetable`
  ADD CONSTRAINT `student_timetable_ibfk_1` FOREIGN KEY (`IntakeCode`) REFERENCES `program_information` (`IntakeCode`),
  ADD CONSTRAINT `student_timetable_ibfk_2` FOREIGN KEY (`ModuleCode`) REFERENCES `module_information` (`ModuleCode`),
  ADD CONSTRAINT `student_timetable_ibfk_3` FOREIGN KEY (`TimeTableSlot1`) REFERENCES `timetable_slot1` (`Slot1_ID`),
  ADD CONSTRAINT `student_timetable_ibfk_4` FOREIGN KEY (`TimeTableSlot2`) REFERENCES `timetable_slot2` (`Slot2_ID`),
  ADD CONSTRAINT `student_timetable_ibfk_5` FOREIGN KEY (`TimeTableSlot3`) REFERENCES `timetable_slot3` (`Slot3_ID`),
  ADD CONSTRAINT `student_timetable_ibfk_6` FOREIGN KEY (`TimeTableSlot4`) REFERENCES `timetable_slot4` (`Slot4_ID`);

--
-- Constraints for table `timetable_slot1`
--
ALTER TABLE `timetable_slot1`
  ADD CONSTRAINT `timetable_slot1_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `class_information` (`ClassID`);

--
-- Constraints for table `timetable_slot2`
--
ALTER TABLE `timetable_slot2`
  ADD CONSTRAINT `timetable_slot2_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `class_information` (`ClassID`);

--
-- Constraints for table `timetable_slot3`
--
ALTER TABLE `timetable_slot3`
  ADD CONSTRAINT `timetable_slot3_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `class_information` (`ClassID`);

--
-- Constraints for table `timetable_slot4`
--
ALTER TABLE `timetable_slot4`
  ADD CONSTRAINT `timetable_slot4_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `class_information` (`ClassID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
