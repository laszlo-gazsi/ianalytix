-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 21, 2010 at 11:13 AM
-- Server version: 5.1.47
-- PHP Version: 5.2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lacyg_ianalytix`
--

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(200) NOT NULL,
  `user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_sites_users` (`user_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=74 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(40) NOT NULL,
  `email` varchar(200) NOT NULL,
  `active` tinyint(4) DEFAULT '0',
  `code` varchar(20) DEFAULT NULL,
  `date_joined` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sites`
--
ALTER TABLE `sites`
  ADD CONSTRAINT `fk_sites_users` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
