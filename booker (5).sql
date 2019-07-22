-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2019 at 12:25 PM
-- Server version: 5.7.26-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booker`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `description` text NOT NULL,
  `time_start` timestamp NULL DEFAULT NULL,
  `time_end` timestamp NULL DEFAULT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `id_user`, `id_room`, `description`, `time_start`, `time_end`, `id_parent`, `create_time`) VALUES
(54, 2, 3, 'Test', '2019-07-23 00:00:00', '2019-07-23 01:00:00', 54, '2019-07-20 12:11:00'),
(55, 2, 3, 'Test', '2019-07-30 00:00:00', '2019-07-30 01:00:00', 54, '2019-07-20 12:11:00'),
(56, 2, 3, 'Test', '2019-08-06 00:00:00', '2019-08-06 01:00:00', 54, '2019-07-20 12:11:00'),
(57, 2, 3, 'Test', '2019-08-13 00:00:00', '2019-08-13 01:00:00', 54, '2019-07-20 12:11:00'),
(58, 2, 1, 'UPDT', '2019-07-23 23:00:00', '2019-07-24 00:00:00', 58, '2019-07-20 12:11:57'),
(59, 2, 1, 'TEST FRONT', '2019-07-31 02:00:00', '2019-07-31 03:00:00', 58, '2019-07-20 12:11:57'),
(60, 2, 1, 'TEST FRONT', '2019-08-07 02:00:00', '2019-08-07 03:00:00', 58, '2019-07-20 12:11:57'),
(61, 2, 1, 'TestUPDATE21', '2019-07-24 22:00:00', '2019-07-25 00:00:00', NULL, '2019-07-20 12:12:30'),
(62, 6, 1, 'Test', '2019-07-15 00:30:00', '2019-07-15 02:00:00', NULL, '2019-07-20 12:16:56');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'user'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`) VALUES
(1, 'room 1'),
(2, 'room 2'),
(3, 'room 3');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `id_role`, `status`) VALUES
(1, 'admin', '$2y$10$tcwKUrVQ19nCeDLjlEoE6O68//G2s2n847JQ4rbTi2aX.hU.tXvuy', 'admin@gmail.com', 2, 1),
(2, 'lotus', '$2y$10$LvtcWaG3QS2sHRGxZDzHreXXMHkoiVSfX0DGnSJe1nezh8ocV3s7u', 'lotus@gmail.com', 1, 1),
(3, 'user2', '$2y$10$5oRmC/m5eI/6wjx3FR9C2OkPbyRX5qdP.PeH3/4sCl5z9iLh3I3de', 'user2@gmail.com', 1, 0),
(4, 'Admin223', '$2y$10$X3bdiARKnaeRyXsel3JDEOnF0ZkAPb6gG3mcvAckGa3oDak4xIBIu', 'admin22@gmail.com', 1, 0),
(5, 'Lizza', '$2y$10$STDbweZn/XoW/K1Z6/edR.Pdm36OYSTjlp8q1S.Nm0Y1Sor.BM1Um', 'liza@gmail.com', 1, 0),
(6, 'user', '$2y$10$Wp3dCitXY.6.S2QT6tZ00.VUNpV6yJDZi7qYhHT4gO7Fl.0LTpUY6', 'user@gmail.com', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_fk0` (`id_user`),
  ADD KEY `events_fk1` (`id_room`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `users_fk0` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_fk1` FOREIGN KEY (`id_room`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_fk0` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
