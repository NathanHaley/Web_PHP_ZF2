-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 11, 2016 at 12:41 PM
-- Server version: 10.0.23-MariaDB
-- PHP Version: 5.6.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tc`
--

-- --------------------------------------------------------

--
-- Table structure for table `x_users_tests_attempts_answers`
--

DROP TABLE IF EXISTS `x_users_tests_attempts_answers`;
CREATE TABLE `x_users_tests_attempts_answers` (
  `id` int(11) NOT NULL,
  `xuta_id` int(11) NOT NULL,
  `xeq_id` int(11) NOT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `valid` tinyint(1) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mdate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `muser_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `x_users_tests_attempts_answers`
--
ALTER TABLE `x_users_tests_attempts_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `xuta_id` (`xuta_id`),
  ADD KEY `xeq_id` (`xeq_id`),
  ADD KEY `muser_id` (`muser_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `x_users_tests_attempts_answers`
--
ALTER TABLE `x_users_tests_attempts_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `x_users_tests_attempts_answers`
--
ALTER TABLE `x_users_tests_attempts_answers`
  ADD CONSTRAINT `fk_xutaa_u_id_1` FOREIGN KEY (`muser_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_xutaa_xuta_id_1` FOREIGN KEY (`xuta_id`) REFERENCES `x_users_tests_attempts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
