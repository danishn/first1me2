-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 17, 2015 at 08:07 PM
-- Server version: 5.1.73-rel14.11-log
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sathi7ea_firstme`
--

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `displayName`, `shortDesc`, `longDesc`, `createdOn`, `status`, `pseudoSubscriptionCount`) VALUES
(1, 'Demo', 'Demo Short Desc', 'Demo Long Desc', '2015-06-13 11:13:15', '1', 0),
(2, 'hello', 'hello hello', 'aohidfopahdfoa', '2015-07-06 11:09:38', '1', 0),
(3, 'Apps', 'Category for Apps', 'Apps', '2015-07-13 07:34:37', '1', 0),
(4, 'Automotive', 'Category for Automotive', 'Automotive', '2015-07-13 07:35:02', '1', 0),
(5, 'Babies & Kids', 'Category for Babies & Kids', 'Babies & Kids', '2015-07-13 07:35:32', '1', 0),
(6, 'Beauty & Personal Care', 'Beauty & Personal Care', 'Beauty & Personal Care', '2015-07-13 07:35:57', '1', 0),
(7, 'Books and Magazines', 'Books and Magazines Category', 'Books and Magazines', '2015-07-13 07:36:30', '1', 0),
(8, 'Cell Phones & Tablets', 'Cell Phones & Tablets', 'Cell Phones & Tablets', '2015-07-13 07:37:22', '1', 0),
(9, 'Fashion & Accessories', 'Fashion & Accessories', 'Fashion & Accessories', '2015-07-13 07:37:41', '1', 0),
(10, 'Computers & Electronics', 'Computers & Electronics', 'Computers & Electronics', '2015-07-13 07:38:18', '1', 0),
(11, 'Food & Restaurants', 'Food & Restaurants', 'Food & Restaurants', '2015-07-13 07:38:34', '1', 0),
(12, 'Health & Fitness', 'Health & Fitness', 'Health & Fitness', '2015-07-13 07:48:27', '1', 0),
(13, 'Home & Garden', 'Home & Garden', 'Home & Garden', '2015-07-13 07:48:57', '1', 0),
(14, 'Jewelry & Watches', 'Jewelry & Watches', 'Jewelry & Watches', '2015-07-13 07:49:09', '1', 0),
(15, 'Movies & Entertainment', 'Movies & Entertainment', 'Movies & Entertainment', '2015-07-13 07:49:24', '1', 0);

--
-- Dumping data for table `deals`
--

INSERT INTO `deals` (`id`, `name`, `categoryId`, `vendorId`, `createdOn`, `thumbnailImg`, `bigImg`, `region`, `shortDesc`, `longDesc`, `likes`, `views`, `pseudoViews`, `expiresOn`, `status`) VALUES
(1, 'Demo Deal', 1, 1, '2015-07-05 10:12:14', '/public/images/deal/thumb/1.png', '/public/images/deal/big/1.png', 'Maharashtra', 'Any Thing', 'Any thing Any thing Any thing Any thing Any thing Any thing.', 0, 22, NULL, '2015-07-06 00:00:00', '1'),
(2, 'Test', 4, 1, '2015-07-13 20:23:52', '/public/images/deal/thumb/2.png', '/public/images/deal/big/2.png', 'Pune', 'demo', 'demo', 0, 3, 0, '2015-07-20 00:00:00', '1'),
(3, 'Test', 4, 1, '2015-07-13 20:23:59', '/public/images/deal/thumb/3.png', '/public/images/deal/big/3.png', 'Pune', 'demo', 'demo', 0, 1, 0, '2015-07-20 00:00:00', '1'),
(4, 'Testing Deal', 8, 1, '2015-07-13 21:30:59', '/public/images/deal/thumb/4.png', '/public/images/deal/big/4.png', 'Maharashtra', 'Demo', 'Demo demo', 0, 6, 0, '2015-08-15 00:00:00', '1'),
(5, 'Demo Computer Deal', 10, 1, '2015-07-13 21:43:48', '/public/images/deal/thumb/5.png', '/public/images/deal/big/5.png', 'Kolkata', 'demo desc', 'test description', 0, 2, 0, '2015-07-31 00:00:00', '1'),
(6, 'Test Computer Deal', 10, 1, '2015-07-14 00:40:40', '/public/images/deal/thumb/6.png', '/public/images/deal/big/6.png', 'Mumbai', 'demo desc', 'test description', 0, 1, 0, '2015-07-31 00:00:00', '1'),
(7, 'Jewellery Computer Deal', 14, 1, '2015-07-14 00:42:11', '/public/images/deal/thumb/7.png', '/public/images/deal/big/7.png', 'Mumbai', 'demo desc', 'test description', 0, 1, 0, '2015-07-31 00:00:00', '1'),
(8, 'Exciting Smartphone Prizes', 8, 1, '2015-07-14 00:58:40', '/public/images/deal/thumb/8.png', '/public/images/deal/big/8.png', 'India', 'test', 'test', 0, 3, 0, '2015-07-13 00:00:00', '1'),
(9, 'Hello', 13, 1, '2015-07-14 01:03:13', '/public/images/deal/thumb/9.png', '/public/images/deal/big/9.png', 'hkhk', 'test', 'gdhg lkllk', 0, 1, 0, '2015-07-13 00:00:00', '1'),
(10, 'aedsfd', 10, 1, '2015-07-16 12:06:41', '/public/images/deal/thumb/10.png', '/public/images/deal/big/10.png', 'asdfas', 'asdfasf', 'asdfasfd', 0, 1, 0, '2015-07-25 00:00:00', '1');

--
-- Dumping data for table `seen`
--

INSERT INTO `seen` (`relationId`, `userId`, `dealId`, `favourite`, `rating`) VALUES
(1, 4, 1, '0', 0),
(2, 2, 1, '0', 0),
(3, 6, 1, '0', 0),
(4, 7, 1, '0', 0),
(5, 9, 1, '0', 0),
(6, 9, 2, '0', 0),
(7, 9, 5, '0', 0),
(8, 9, 4, '0', 0),
(9, 9, 8, '0', 0),
(10, 9, 7, '0', 0),
(11, 10, 2, '0', 0),
(12, 10, 1, '0', 0),
(13, 10, 9, '0', 0),
(14, 10, 6, '0', 0),
(15, 10, 4, '0', 0),
(16, 10, 8, '0', 0),
(17, 10, 5, '0', 0),
(18, 10, 3, '0', 0),
(19, 10, 10, '0', 0);

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`subscriptionId`, `userId`, `categoryId`, `subscribedOn`) VALUES
(1, 2, 1, '2015-06-29 13:58:07'),
(2, 4, 1, '2015-07-09 22:34:25'),
(3, 4, 2, '2015-07-09 22:34:25'),
(4, 6, 1, '2015-07-12 13:57:46'),
(5, 6, 2, '2015-07-12 13:57:46'),
(9, 8, 1, '2015-07-12 14:41:17'),
(10, 8, 2, '2015-07-12 14:41:17'),
(11, 9, 1, '2015-07-12 14:43:32'),
(12, 9, 1, '2015-07-12 14:43:32'),
(15, 9, 2, '2015-07-12 20:40:00'),
(17, 7, 1, '2015-07-13 10:13:06'),
(18, 7, 2, '2015-07-13 10:13:07'),
(19, 7, 11, '2015-07-13 10:13:07'),
(20, 7, 12, '2015-07-13 10:13:07'),
(21, 7, 13, '2015-07-13 10:13:07'),
(22, 7, 14, '2015-07-13 10:13:07'),
(23, 7, 15, '2015-07-13 10:13:07'),
(63, 9, 3, '2015-07-14 21:28:33'),
(64, 9, 4, '2015-07-14 21:28:33'),
(65, 9, 5, '2015-07-14 21:28:33'),
(66, 9, 6, '2015-07-14 21:28:33'),
(67, 9, 7, '2015-07-14 21:28:33'),
(68, 9, 8, '2015-07-14 21:28:33'),
(69, 9, 9, '2015-07-14 21:28:33'),
(70, 9, 10, '2015-07-14 21:28:33'),
(71, 9, 11, '2015-07-14 21:28:33'),
(72, 9, 12, '2015-07-14 21:28:33'),
(73, 9, 13, '2015-07-14 21:28:33'),
(74, 9, 14, '2015-07-14 21:28:33'),
(75, 9, 15, '2015-07-14 21:28:33'),
(107, 10, 1, '2015-07-15 01:31:19'),
(108, 10, 2, '2015-07-15 01:32:21'),
(109, 10, 3, '2015-07-15 01:32:21'),
(110, 10, 4, '2015-07-15 01:32:21'),
(122, 10, 5, '2015-07-15 12:24:14'),
(123, 10, 6, '2015-07-15 12:24:14'),
(124, 10, 7, '2015-07-15 12:24:14'),
(125, 10, 8, '2015-07-15 12:24:14'),
(126, 10, 9, '2015-07-15 12:24:14'),
(127, 10, 10, '2015-07-15 12:24:14'),
(128, 10, 11, '2015-07-15 12:24:14'),
(129, 10, 12, '2015-07-15 12:24:14'),
(130, 10, 13, '2015-07-15 12:24:14'),
(131, 10, 14, '2015-07-15 12:24:14'),
(132, 10, 15, '2015-07-15 12:24:14'),
(133, 11, 1, '2015-07-17 21:03:25'),
(134, 11, 2, '2015-07-17 21:03:25'),
(135, 11, 3, '2015-07-17 21:03:25'),
(136, 11, 4, '2015-07-17 21:03:25'),
(137, 11, 5, '2015-07-17 21:03:25'),
(138, 11, 6, '2015-07-17 21:03:25'),
(139, 11, 7, '2015-07-17 21:03:25'),
(140, 11, 8, '2015-07-17 21:03:25'),
(141, 11, 9, '2015-07-17 21:03:25'),
(142, 11, 10, '2015-07-17 21:03:25'),
(143, 11, 11, '2015-07-17 21:03:25'),
(144, 11, 12, '2015-07-17 21:03:25'),
(145, 11, 13, '2015-07-17 21:03:25'),
(146, 11, 14, '2015-07-17 21:03:25'),
(147, 11, 15, '2015-07-17 21:03:25'),
(148, 12, 1, '2015-07-17 21:07:39'),
(149, 12, 2, '2015-07-17 21:07:39'),
(150, 12, 3, '2015-07-17 21:07:39'),
(151, 12, 4, '2015-07-17 21:07:39'),
(152, 12, 5, '2015-07-17 21:07:39'),
(153, 12, 6, '2015-07-17 21:07:39'),
(154, 12, 7, '2015-07-17 21:07:39'),
(155, 12, 8, '2015-07-17 21:07:39'),
(156, 12, 9, '2015-07-17 21:07:39'),
(157, 12, 10, '2015-07-17 21:07:39'),
(158, 12, 11, '2015-07-17 21:07:39'),
(159, 12, 12, '2015-07-17 21:07:39'),
(160, 12, 13, '2015-07-17 21:07:39'),
(161, 12, 14, '2015-07-17 21:07:39'),
(162, 12, 15, '2015-07-17 21:07:39');

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `os`, `token`, `firstName`, `lastName`, `email`, `mobile`, `country`, `city`, `password`, `fbStatus`, `registeredOn`) VALUES
(1, 'iOS 9', '-', 'Biswajit', 'Bardhan', 'bforbiswajit@outlook.com', '9762577346', 'India', 'Pune', '24/4Hb15npAe.', '0', '2015-07-11 07:20:42'),
(2, 'Android 4.2.2', '-', 'Danish', 'Nadaf', 'danishn@cybage.com', '9876543210', 'India', 'Pune', '18nDMhanAU0hs', '0', '2015-07-11 07:20:42'),
(3, 'iOS 8.3', '12345', 'Tushar', 'Katakdound', 'tushar02.katakdound@gmail.com', '8983472919', 'India', 'Pune', '29jAIVogFgTLg', '0', '2015-07-11 07:20:42'),
(4, 'Android', '12345', 'Tushar', 'Katakdound', 'abc.katakdound@gmail.com', '8983472919', 'India', 'Pune', '24alwVoc4uJA6', '1', '2015-07-11 07:20:42'),
(5, 'Android', '12345', 'Tushar', 'Katakdound', 'tinu02.katakdound@gmail.com', '8983472919', 'India', 'Pune', '27iuCbiEVVgV.', '0', '2015-07-12 12:34:26'),
(6, 'Android', '12345', 'Tushar', 'Katakdound', 'tushar.katakdound@gmail.com', '8983472919', 'India', 'Pune', '27iuCbiEVVgV.', '0', '2015-07-12 13:50:10'),
(7, 'Android', '12345', 'danish', 'nadaf', 'danishnadaf@gmail.com', '8793700938', 'India', 'Pune', '21Cnlz3R/lOSo', '1', '2015-07-12 14:32:49'),
(8, 'Android', '12345', 'Tushar', 'Katakdound', 't.katakdound@gmail.com', '8983472919', 'India', 'Pune', '22Qs4eXwOpzhM', '0', '2015-07-12 14:41:11'),
(9, 'Android', '12345', 'Tushar', 'Katakdound', 'tushar@gmail.com', '8983472919', 'India', 'Pune', '16MPw8fXNKuLw', '0', '2015-07-12 14:43:18'),
(10, 'Android', '12345', 'danish', 'nadaf', 'danishnadaf1@gmail.com', '8793700938', 'India', 'Pune', '22Qs4eXwOpzhM', '0', '2015-07-15 01:27:24'),
(11, 'Android', '12345', 'Tushar', 'Katakdound', 'king02.katakdound@gmail.com', '8983472919', 'India', 'Pune', '27BAKy3Efkrbk', '0', '2015-07-17 21:03:08'),
(12, 'Android', '12345', 'Tushar', 'Katakdound', 'tu02.katakdound@gmail.com', '8983472919', 'America', 'Pune', '25scwPWKPL3u2', '0', '2015-07-17 21:07:31');

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`id`, `email`, `username`, `password`, `lastLogin`, `name`) VALUES
(1, 'bforbiswajit@outlook.com', 'bforbiswajit', 12345, '2015-07-05 10:10:26', 'Biswajit');

--
-- Dumping data for table `vendorinfo`
--

INSERT INTO `vendorinfo` (`vendorId`, `firstName`, `lastName`, `businessTitle`, `desc`, `registeredOn`) VALUES
(1, 'Biswajit', 'Bardhan', 'Technokratz', 'Software Development', '2015-07-05 10:11:05');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
