-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: sql
-- Generation Time: Apr 20, 2011 at 01:12 PM
-- Server version: 5.1.53
-- PHP Version: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `p_acc_wars`
--

-- --------------------------------------------------------

--
-- Table structure for table `acc_ban`
--

DROP TABLE IF EXISTS `acc_ban`;
CREATE TABLE `acc_ban` (
  `ban_id` int(11) NOT NULL AUTO_INCREMENT,
  `ban_type` varchar(255) NOT NULL,
  `ban_target` varchar(700) NOT NULL,
  `ban_user` int(11) NOT NULL,
  `ban_reason` varchar(4096) NOT NULL,
  `ban_date` varchar(1024) NOT NULL,
  `ban_duration` varchar(255) NOT NULL,
  `ban_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ban_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `acc_comment`
--

DROP TABLE IF EXISTS `acc_comment`;
CREATE TABLE `acc_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment_user` int(11) NOT NULL DEFAULT '0',
  `comment_comment` mediumtext NOT NULL,
  `comment_visibility` varchar(255) NOT NULL,
  `comment_request` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_log`
--

DROP TABLE IF EXISTS `acc_log`;
CREATE TABLE `acc_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_target_id` int(11) NOT NULL DEFAULT '0',
  `log_target_objecttype` varchar(45) DEFAULT NULL,
  `log_target_text` varchar(255) NOT NULL,
  `log_user_id` int(11) NOT NULL DEFAULT '0',
  `log_user_text` varchar(255) NOT NULL,
  `log_action` varchar(255) NOT NULL,
  `log_time` datetime NOT NULL,
  `log_cmt` blob NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `acc_message`
--

DROP TABLE IF EXISTS `acc_message`;
CREATE TABLE `acc_message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_text` blob NOT NULL,
  `message_count` int(11) NOT NULL,
  `message_desc` varchar(255) NOT NULL,
  `message_type` varchar(255) NOT NULL,
  `message_key` varchar(45) NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `acc_request`
--

DROP TABLE IF EXISTS `acc_request`;
CREATE TABLE `acc_request` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `request_email` varchar(512) NOT NULL,
  `request_ip` varchar(255) NOT NULL,
  `request_name` varchar(512) NOT NULL,
  `request_cmt` mediumtext NOT NULL,
  `request_status` varchar(255) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `request_checksum` varchar(256) NOT NULL,
  `request_emailsent` varchar(10) NOT NULL,
  `request_mailconfirm` varchar(255) NOT NULL,
  `request_reserved` int(11) NOT NULL DEFAULT '0' COMMENT 'User ID of user who has "reserved" this request',
  `request_useragent` blob NOT NULL COMMENT 'Useragent of the requesting web browser',
  `request_proxyip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `acc_titleblacklist`
--

DROP TABLE IF EXISTS `acc_titleblacklist`;
CREATE TABLE `acc_titleblacklist` (
  `titleblacklist_regex` varchar(128) NOT NULL,
  `titleblacklist_casesensitive` tinyint(1) NOT NULL,
  PRIMARY KEY (`titleblacklist_regex`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `acc_trustedips`
--

DROP TABLE IF EXISTS `acc_trustedips`;
CREATE TABLE `acc_trustedips` (
  `trustedips_ipaddr` varchar(15) NOT NULL,
  PRIMARY KEY (`trustedips_ipaddr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `acc_user`
--

DROP TABLE IF EXISTS `acc_user`;
CREATE TABLE `acc_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_level` varchar(255) NOT NULL DEFAULT 'New',
  `user_onwikiname` varchar(255) NOT NULL,
  `user_welcome_sig` varchar(4096) NOT NULL DEFAULT '',
  `user_lastactive` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_lastip` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '0.0.0.0',
  `user_forcelogout` int(3) NOT NULL DEFAULT '0',
  `user_secure` int(11) NOT NULL DEFAULT '0',
  `user_checkuser` int(1) NOT NULL DEFAULT '0',
  `user_identified` int(1) unsigned NOT NULL DEFAULT '0',
  `user_welcome_templateid` int(11) NOT NULL DEFAULT '0',
  `user_abortpref` tinyint(4) NOT NULL DEFAULT '0',
  `user_confirmationdiff` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `acc_welcomequeue`
--

DROP TABLE IF EXISTS `acc_welcomequeue`;
CREATE TABLE `acc_welcomequeue` (
  `welcomequeue_id` int(11) NOT NULL AUTO_INCREMENT,
  `welcomequeue_uid` int(11) NOT NULL DEFAULT '0' COMMENT 'Username of the welcoming user',
  `welcomequeue_user` varchar(1024) NOT NULL,
  `welcomequeue_status` varchar(96) NOT NULL,
  PRIMARY KEY (`welcomequeue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `acc_welcometemplate`
--

DROP TABLE IF EXISTS `acc_welcometemplate`;
CREATE TABLE `acc_welcometemplate` (
  `welcometemplate_id` int(11) NOT NULL AUTO_INCREMENT,
  `welcometemplate_usercode` tinytext NOT NULL,
  `welcometemplate_botcode` tinytext NOT NULL,
  PRIMARY KEY (`welcometemplate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
