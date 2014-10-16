-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Darbinė stotis: localhost
-- Atlikimo laikas: 2013 m. Bal 04 d. 05:55
-- Serverio versija: 5.5.24-log
-- PHP versija: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Duomenų bazė: `measurements`
--
CREATE DATABASE `measurements` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `measurements`;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `blood_pressures`
--

CREATE TABLE IF NOT EXISTS `blood_pressures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `systolic` int(11) DEFAULT NULL,
  `diastolic` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Sukurta duomenų kopija lentelei `blood_pressures`
--

INSERT INTO `blood_pressures` (`id`, `systolic`, `diastolic`) VALUES
(1, 120, 80);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `heartrate`
--

CREATE TABLE IF NOT EXISTS `heartrate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Sukurta duomenų kopija lentelei `heartrate`
--

INSERT INTO `heartrate` (`id`, `bpm`) VALUES
(1, 5),
(2, 120),
(3, 60),
(4, 120);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `heights`
--

CREATE TABLE IF NOT EXISTS `heights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` decimal(3,2) DEFAULT NULL,
  `unit` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `measurements`
--

CREATE TABLE IF NOT EXISTS `measurements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) DEFAULT NULL,
  `on_date_time` datetime DEFAULT NULL,
  `measurement_type` varchar(30) DEFAULT NULL,
  `measurement_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Sukurta duomenų kopija lentelei `measurements`
--

INSERT INTO `measurements` (`id`, `person_id`, `on_date_time`, `measurement_type`, `measurement_id`) VALUES
(3, 1, '2013-03-19 09:38:39', 'Temperature', 3),
(4, 1, '2013-03-19 09:39:04', 'Temperature', 4),
(5, 1, '2013-03-19 09:39:07', 'Temperature', 5),
(6, 1, '2013-03-19 09:39:13', 'Temperature', 6),
(7, 1, '2013-04-20 08:10:42', 'Temperature', 7),
(8, 1, '2013-03-20 10:47:23', 'Temperature', 8),
(9, 1, '2013-03-20 11:52:24', 'Temperature', 9),
(10, 1, '2013-03-20 11:55:41', 'Temperature', 10),
(11, 1, '2013-04-20 11:55:50', 'Temperature', 11),
(12, 1, '2013-04-20 11:56:12', 'Temperature', 12),
(13, 1, '2013-03-21 09:41:24', 'Temperature', 13),
(14, 1, '2013-03-20 09:42:14', 'Temperature', 14),
(15, 1, '2012-03-21 09:43:09', 'Temperature', 15),
(16, 1, '2013-03-21 13:25:58', 'Temperature', 16),
(17, 1, '2013-03-21 13:26:28', 'Temperature', 17),
(18, 1, '2013-03-21 13:43:49', 'Temperature', 18),
(19, 1, '2013-03-21 13:44:05', 'Temperature', 19),
(20, 1, '2013-03-22 05:36:06', 'BloodPressure', 1),
(21, 1, '2013-03-22 05:46:51', 'Heartrate', 1),
(22, 1, '2013-03-22 05:46:55', 'Heartrate', 2),
(23, 1, '2013-03-22 05:46:59', 'Heartrate', 3),
(24, 1, '2013-03-22 08:04:50', 'Weight', 1),
(25, 1, '2013-04-04 05:39:09', 'Heartrate', 4),
(26, 1, '2013-04-04 05:39:46', 'Temperature', 20);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `surname` varchar(30) DEFAULT NULL,
  `born` varchar(10) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Sukurta duomenų kopija lentelei `people`
--

INSERT INTO `people` (`id`, `name`, `surname`, `born`, `user_id`) VALUES
(1, 'John', 'Doe', '1990-07-16', NULL);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `temperatures`
--

CREATE TABLE IF NOT EXISTS `temperatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` decimal(5,3) DEFAULT NULL,
  `unit` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Sukurta duomenų kopija lentelei `temperatures`
--

INSERT INTO `temperatures` (`id`, `value`, `unit`) VALUES
(3, '36.600', 'c'),
(4, '36.600', 'c'),
(5, '36.600', 'c'),
(6, '36.600', 'c'),
(7, '36.600', 'c'),
(8, '38.000', 'c'),
(9, '35.000', 'c'),
(10, '36.000', 'c'),
(11, '36.000', 'c'),
(12, '39.000', 'c'),
(13, '36.000', 'c'),
(14, '36.600', 'c'),
(15, '36.600', 'c'),
(16, '36.600', 'c'),
(17, '37.500', 'c'),
(18, '40.000', 'c'),
(19, '36.700', 'c'),
(20, '36.600', 'c');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `phash` varchar(32) DEFAULT NULL,
  `sid` varchar(32) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `aid` varchar(32) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `user_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Sukurta duomenų kopija lentelei `users`
--

INSERT INTO `users` (`id`, `login`, `phash`, `sid`, `email`, `aid`, `active`, `user_type_id`) VALUES
(5, 'demo', '6c5ac7b4d3bd3311f033f971196cfa75', 'sfj5ursi16jtgm3b4nc86qaa33', 'demo@example.com', '', 1, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `weights`
--

CREATE TABLE IF NOT EXISTS `weights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` decimal(5,2) DEFAULT NULL,
  `unit` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Sukurta duomenų kopija lentelei `weights`
--

INSERT INTO `weights` (`id`, `value`, `unit`) VALUES
(1, '80.00', NULL);
--
-- Duomenų bazė: `measurements_test`
--
CREATE DATABASE `measurements_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `measurements_test`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
