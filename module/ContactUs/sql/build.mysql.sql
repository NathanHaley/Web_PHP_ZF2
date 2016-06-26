-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2016 at 02:27 PM
-- Server version: 10.0.23-MariaDB
-- PHP Version: 5.6.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tc`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_us_form`
--

DROP TABLE IF EXISTS `contact_us_form`;
CREATE TABLE `contact_us_form` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(254) NOT NULL,
  `comments` varchar(500) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_us_form`
--
ALTER TABLE `contact_us_form`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_us_form`
--
ALTER TABLE `contact_us_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
