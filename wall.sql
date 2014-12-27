-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 15, 2011 at 07:21 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wall`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments_activity`
--

CREATE TABLE IF NOT EXISTS `comments_activity` (
  `course_id` int(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `com_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(20000) DEFAULT NULL,
  `msg_id_fk` int(11) DEFAULT NULL,
  `uid_fk` int(11) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `created` int(11) DEFAULT '1269249260',
  PRIMARY KEY (`com_id`),
  KEY `msg_id_fk` (`msg_id_fk`),
  KEY `uid_fk` (`uid_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;


-- --------------------------------------------------------

--
-- Table structure for table `messages_activity`
--

CREATE TABLE IF NOT EXISTS `messages_activity` (
  `course_id` int(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(20000) DEFAULT NULL,
  `uid_fk` int(11) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `created` int(11) DEFAULT '1269249260',
  PRIMARY KEY (`msg_id`),
  KEY `uid_fk` (`uid_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Table structure for table `content` and `ratings`
--

CREATE TABLE IF NOT EXISTS  `content` (
`id` INT NOT NULL ,
`content` TEXT NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE IF NOT EXISTS  `ratings` (
`rating` VARCHAR ( 7 ) NOT NULL ,
`id` INT NOT NULL ,
`ip` VARCHAR ( 50 ) NOT NULL ,
`userid_rating` INT NOT NULL
) ENGINE = MYISAM ;

-- --------------------------------------------------------
