-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 37.148.204.40
-- Generation Time: Dec 26, 2013 at 09:14 AM
-- Server version: 5.0.96
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bitcoinDirectory`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_name` varchar(255) NOT NULL,
  `parent_cat` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cat_id`),
  UNIQUE KEY `cat_id` (`cat_id`),
  UNIQUE KEY `cat_id_2` (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` VALUES(29, 'Arts', 0);
INSERT INTO `categories` VALUES(32, 'eCommerce', 0);
INSERT INTO `categories` VALUES(33, 'Web Design', 0);
INSERT INTO `categories` VALUES(34, 'Books', 0);
INSERT INTO `categories` VALUES(36, 'Gambling', 0);
INSERT INTO `categories` VALUES(37, 'Digital Goods', 32);
INSERT INTO `categories` VALUES(38, 'Books', 32);
INSERT INTO `categories` VALUES(39, 'Toys', 32);
INSERT INTO `categories` VALUES(40, 'Arts and Crafts', 32);
INSERT INTO `categories` VALUES(41, 'Adult', 32);
INSERT INTO `categories` VALUES(42, 'Entertainment', 32);
INSERT INTO `categories` VALUES(43, 'Electronics', 32);
INSERT INTO `categories` VALUES(44, 'Food', 32);
INSERT INTO `categories` VALUES(45, 'Physical Stores', 32);

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `ID` int(11) NOT NULL auto_increment,
  `question` varchar(125) NOT NULL,
  `message` varchar(500) NOT NULL,
  `name` varchar(55) NOT NULL,
  `email` varchar(125) NOT NULL,
  `website` varchar(155) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` VALUES(18, 'Etiam viverra felis nulla?', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas auctor, nisl ut pharetra interdum, nunc velit commodo dui, id pellentesque felis lectus in ligula. Quisque mauris sem, convallis vitae commodo nec, consequat a tellus. Nulla ultricies erat quis fermentum volutpat. Vestibulum semper lectus eget sapien laoreet vulputate. Phasellus quis eros vel tellus mollis hendrerit sit amet a diam. Proin gravida dui in lacinia vestibulum. Vestibulum pharetra nec urna posuere rhoncus. Mauris sed', 'Vestibulum dictum consectetur nunc', 'Etiam@auctor.com', 'http://www.commodo.com', '2013-08-08 08:10:39');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id_invoice` int(11) NOT NULL auto_increment,
  `tx_input_address` varchar(255) default NULL,
  `tx_destination_address` varchar(255) default NULL,
  `id_site` int(11) NOT NULL,
  `val_price_btc` float NOT NULL,
  `val_price_usd` float NOT NULL,
  PRIMARY KEY  (`id_invoice`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `invoice`
--


-- --------------------------------------------------------

--
-- Table structure for table `invoice_payments`
--

CREATE TABLE `invoice_payments` (
  `tx_transaction_hash` char(64) NOT NULL,
  `id_invoice` int(11) NOT NULL,
  `val_price_btc` double NOT NULL,
  `val_price_usd` double NOT NULL,
  `tx_input_address` varchar(255) NOT NULL,
  `id_site` int(11) NOT NULL,
  PRIMARY KEY  (`tx_transaction_hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `invoice_payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

CREATE TABLE `listing` (
  `id_site` int(11) NOT NULL auto_increment,
  `nm_site_name` varchar(255) NOT NULL,
  `url_site_address` varchar(255) NOT NULL,
  `tx_site_description` varchar(255) NOT NULL,
  `tx_site_image` varchar(255) NOT NULL default '0',
  `int_category` int(11) NOT NULL,
  `bool_premium` tinyint(4) NOT NULL default '0',
  `bool_featured` tinyint(4) NOT NULL default '0',
  `dt_date_listed` date NOT NULL,
  `bool_approved` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` VALUES(13, 'CDKey Hut', 'http://www.cdkey-hut.com', 'CDKey-Hut - Low prices on Steam, Origin and Xbox Live codes as well as CD Keys - Pay in Bitcoin - Codes are delivered instantly following confirmation of payment', 'uploads/1385506937.png', 37, 1, 1, '2013-12-04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `listing_type`
--

CREATE TABLE `listing_type` (
  `id_listing` int(11) NOT NULL auto_increment,
  `nm_listing` varchar(255) NOT NULL,
  `tx_listing_cost` float NOT NULL,
  PRIMARY KEY  (`id_listing`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `listing_type`
--

INSERT INTO `listing_type` VALUES(1, 'Standard', 0);
INSERT INTO `listing_type` VALUES(2, 'Premium', 0.05);
INSERT INTO `listing_type` VALUES(3, 'Featured', 0.01);

-- --------------------------------------------------------

--
-- Table structure for table `pending_invoice_payments`
--

CREATE TABLE `pending_invoice_payments` (
  `tx_transaction_hash` char(64) NOT NULL,
  `id_invoice` int(11) NOT NULL,
  `val_price_btc` double NOT NULL,
  `val_price_usd` double NOT NULL,
  `tx_input_address` varchar(255) NOT NULL,
  `id_site` int(11) NOT NULL,
  PRIMARY KEY  (`tx_transaction_hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pending_invoice_payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `pending_listing`
--

CREATE TABLE `pending_listing` (
  `id_site` int(11) NOT NULL auto_increment,
  `nm_site_name` varchar(255) NOT NULL,
  `url_site_address` varchar(255) NOT NULL,
  `tx_site_description` varchar(255) NOT NULL,
  `tx_site_image` varchar(255) NOT NULL default '0',
  `int_category` int(11) NOT NULL,
  `bool_premium` tinyint(4) NOT NULL default '0',
  `bool_featured` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pending_listing`
--

INSERT INTO `pending_listing` VALUES(8, 'CDKey Hut', 'http://www.cdkey-hut.com', 'CDKey-Hut - Low prices on Steam, Origin and Xbox Live codes as well as CD Keys - Pay in Bitcoin - Codes are delivered instantly following confirmation of payment', 'uploads/1385506937.png', 28, 1, 1);
