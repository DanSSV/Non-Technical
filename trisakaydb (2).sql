-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2023 at 02:44 PM
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
-- Database: `trisakaydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `pickup_lat` decimal(10,6) NOT NULL,
  `pickup_lng` decimal(10,6) NOT NULL,
  `dropoff_lat` decimal(10,6) NOT NULL,
  `dropoff_lng` decimal(10,6) NOT NULL,
  `expiration` datetime NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `Name` varchar(255) NOT NULL,
  `Tricycle_Number` varchar(255) NOT NULL,
  `Plate_Number` varchar(255) NOT NULL,
  `body_number` varchar(255) NOT NULL,
  `Toda` varchar(255) NOT NULL,
  `passenger` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`Name`, `Tricycle_Number`, `Plate_Number`, `body_number`, `Toda`, `passenger`) VALUES
('Juan dela Cruz', 'GHI-123', 'GHI-123', '299', 'Sabang ', '2'),
('Mike John Tyson', '500', 'GPO-451', '500', 'Piel', ''),
('Mariano Legaspi', 'TRC-234', 'XYZ 789', '', 'Piel', '');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `Route_ID` int(255) NOT NULL,
  `Starting_Point` varchar(255) NOT NULL,
  `Start_Latitude` varchar(255) NOT NULL,
  `Start_Longtitude` varchar(255) NOT NULL,
  `End_Latitude` varchar(255) NOT NULL,
  `End_Longtitude` varchar(255) NOT NULL,
  `Date_Time` datetime NOT NULL,
  `User_ID` int(255) NOT NULL,
  `Distance` varchar(255) NOT NULL,
  `Fare` varchar(255) NOT NULL,
  `Driver_ID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_1`
--

CREATE TABLE `history_1` (
  `route_id` int(11) NOT NULL,
  `starting_lat` decimal(20,17) NOT NULL,
  `starting_lng` decimal(20,17) NOT NULL,
  `new_lat` decimal(20,17) NOT NULL,
  `new_lng` decimal(20,17) NOT NULL,
  `currentMarker_lat` decimal(20,17) NOT NULL,
  `currentMarker_long` decimal(20,17) NOT NULL,
  `time_date` datetime DEFAULT NULL,
  `plate_number` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `distance` varchar(255) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `cancel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history_1`
--

INSERT INTO `history_1` (`route_id`, `starting_lat`, `starting_lng`, `new_lat`, `new_lng`, `currentMarker_lat`, `currentMarker_long`, `time_date`, `plate_number`, `user_id`, `status`, `distance`, `cost`, `cancel`) VALUES
(254, '15.15880000000000000', '120.62860000000000000', '15.15457119745812900', '120.66867828369142000', '0.00000000000000000', '0.00000000000000000', '2023-09-09 07:15:30', 'GHI-123', '2', 'Completed', '', '', 'confirmed'),
(255, '15.15457119745812900', '120.66867828369142000', '15.16857168215336700', '120.68635940551759000', '51.50735100000000000', '-0.12775800000000000', '2023-09-09 07:28:04', 'GHI-123', '2', 'Cancelled', '', '', 'confirmed'),
(256, '15.15880000000000000', '120.62860000000000000', '15.16484383300446000', '120.66610336303712000', '15.15880000000000000', '120.62860000000000000', '2023-09-09 07:54:25', 'GHI-123', '2', 'Cancelled', '', '', 'confirmed'),
(257, '15.15880000000000000', '120.62860000000000000', '15.16641782177568000', '120.65889358520509000', '15.15880000000000000', '120.62860000000000000', '2023-09-09 07:56:00', 'GHI-123', '2', 'Cancelled', '', '', 'confirmed'),
(258, '15.15880000000000000', '120.62860000000000000', '15.15349419870568700', '120.67116737365724000', '15.15880000000000000', '120.62860000000000000', '2023-09-09 07:57:15', 'GHI-123', '2', 'Cancelled', '', '', 'confirmed'),
(259, '15.15880000000000000', '120.62860000000000000', '15.15531680876712000', '120.66850662231447000', '15.15880000000000000', '120.62860000000000000', '2023-09-09 08:03:49', 'GHI-123', '2', 'Cancelled', '', '', 'confirmed'),
(260, '15.15880000000000000', '120.62860000000000000', '15.17437042790507100', '120.60739517211915000', '15.15880000000000000', '120.62860000000000000', '2023-09-09 08:06:17', 'GPO-451', '2', 'Cancelled', '', '', 'confirmed'),
(261, '15.15880000000000000', '120.62860000000000000', '15.17345920696895900', '120.60576438903810000', '0.00000000000000000', '0.00000000000000000', '2023-09-09 11:38:16', 'GHI-123', '2', 'Completed', '', '', ''),
(262, '15.17345920696895900', '120.60576438903810000', '15.16710126061901100', '120.58359861373903000', '15.17345920696895900', '120.60576438903810000', '2023-09-09 13:50:33', 'GHI-123', '2', 'Cancelled', '', '', 'confirmed'),
(263, '15.17345920696895900', '120.60576438903810000', '15.16701841966478900', '120.58357715606691000', '0.00000000000000000', '0.00000000000000000', '2023-09-09 13:51:34', 'GHI-123', '2', 'Completed', '', '', ''),
(264, '15.15210000000000000', '120.58630000000000000', '15.14916852082942000', '120.60825347900390000', '0.00000000000000000', '0.00000000000000000', '2023-09-21 12:07:54', 'GHI-123', '2', 'Completed', '', '', ''),
(265, '15.09720000000000000', '120.62280000000000000', '15.07402973240834300', '120.64395904541017000', '0.00000000000000000', '0.00000000000000000', '2023-09-29 20:26:48', 'GHI-123', '2', 'Completed', '', '', ''),
(266, '15.09720000000000000', '120.62280000000000000', '15.07477562686201500', '120.64335823059083000', '0.00000000000000000', '0.00000000000000000', '2023-09-29 20:26:49', 'GHI-123', '2', 'Completed', '', '', ''),
(267, '15.09720000000000000', '120.62280000000000000', '15.07477562686201500', '120.64335823059083000', '0.00000000000000000', '0.00000000000000000', '2023-09-29 20:26:49', 'GHI-123', '2', 'Completed', '', '', ''),
(268, '15.09720000000000000', '120.62280000000000000', '15.04692712775660200', '120.66833496093751000', '0.00000000000000000', '0.00000000000000000', '2023-09-29 20:30:04', 'GHI-123', '2', 'Completed', '', '', ''),
(269, '15.15880000000000000', '120.62860000000000000', '15.16517520003551800', '120.66593170166017000', '15.15880000000000000', '120.62860000000000000', '2023-09-29 20:32:12', 'GHI-123', '2', 'Cancelled', '', '', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(255) NOT NULL,
  `issue` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `driver_id` int(255) NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_recover`
--

CREATE TABLE `report_recover` (
  `user_id` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `concern` varchar(255) NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_recover`
--

INSERT INTO `report_recover` (`user_id`, `subject`, `plate_number`, `concern`, `date_time`) VALUES
('2', 'Lost item', 'GHI-123', 'Blue bag', '2023-09-09 06:52:26'),
('2', 'Report Driver', 'GHI-123', 'Overpricing', '2023-09-09 06:54:49'),
('2', 'Bag', 'GHI-123', 'i left my bag black bag on the tricycle', '2023-09-09 13:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `attempt` int(255) NOT NULL,
  `date_created` date NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `expiration` datetime DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL,
  `emergency` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `name`, `password`, `role`, `attempt`, `date_created`, `plate_number`, `otp`, `expiration`, `status`, `emergency`, `email`) VALUES
(1, 'Juan', '', '12345', 'driver', 0, '2023-08-08', 'GHI-123', '84d0b17053f7cc8cbc31fe8e2437ae05', '2023-09-02 16:17:55', 'not used', '', ''),
(2, 'Dan', 'Dunkurt Ken', '12345', 'commuter', 0, '2023-08-14', 'GHI-123', '19e7d1de1d2676b05f796d443c3df7f6', '2023-09-02 20:37:49', 'not used', 'dnkrtsalazar@gmail.com', 'dnkrtsalazar@gmail.com'),
(3, 'Mike', 'Mike Tyson', '12345', 'driver', 0, '0000-00-00', 'GPO-451', '', '2023-09-09 11:21:29', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD UNIQUE KEY `Plate_Number` (`Plate_Number`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`Route_ID`);

--
-- Indexes for table `history_1`
--
ALTER TABLE `history_1`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `Route_ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_1`
--
ALTER TABLE `history_1`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
