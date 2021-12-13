-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2021 at 08:45 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `amount` tinyint(4) DEFAULT '1',
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Ordering` int(11) NOT NULL,
  `Visibility` tinyint(5) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(5) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(1, 'games', 'view games', 3, 1, 0, 1),
(2, 'Mobiles', 'view mobiles phone', 1, 0, 0, 0),
(3, 'Computer', 'all about computers', 1, 1, 1, 1),
(4, 'Home made', 'no description', 3, 1, 1, 1),
(5, 'clothes', 'desc', 10, 1, 1, 1),
(6, 'sport', 'this is privat category', 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment`, `comment_date`, `item_id`, `user_id`, `comment_image`, `status`) VALUES
(4, 'good', '2020-10-26', 4, 2, '', 1),
(7, 'nice spoon\r\n', '2021-01-06', 4, 2, '', 1),
(11, 'this is nice', '2021-04-07', 28, 46, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemID` int(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` int(11) NOT NULL,
  `Status` tinyint(11) NOT NULL,
  `Contry_made` varchar(255) NOT NULL,
  `Cat_ID` int(255) NOT NULL,
  `Member_ID` int(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Time` varchar(20) NOT NULL DEFAULT '12:00',
  `Approve` int(11) NOT NULL DEFAULT '0',
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemID`, `Name`, `Description`, `Price`, `Status`, `Contry_made`, `Cat_ID`, `Member_ID`, `Add_Date`, `Image`, `Time`, `Approve`, `tags`) VALUES
(4, 'wooden spoon', 'wooden spoon', 21, 1, 'syria', 1, 2, '2020-10-26', '7236774_2.jpg', '12:00', 1, 'cheep,strong,good'),
(28, 'test1', 'this is privat category', 10, 2, 'japan', 1, 46, '2021-04-07', '7905664_46.jpg', '12:00', 1, 'cheep,strong,good,ho'),
(31, 'test55', 'this is privat category', 25, 2, 'CHINA', 3, 46, '2021-04-07', '1529697_46.jpg', '12:00', 1, 'cheep,strong,good,ho'),
(32, 'mouth cleaner', 'this is privat ', 15, 1, 'china', 5, 34, '2021-09-05', '', '12:00', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Fullname` varchar(255) NOT NULL,
  `RegStatus` tinyint(11) NOT NULL DEFAULT '0',
  `Date` date NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `GroupID` tinyint(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `Fullname`, `RegStatus`, `Date`, `avatar`, `GroupID`) VALUES
(2, 'mohamed', '8cb2237d0679ca88db6464eac60da96345513964', 'mohamed.hussein.a93@gmail.com', 'mohamed hussein', 1, '2020-10-21', '58082_IMG-20200609-WA0001-rmovebg-preview .jpg', 1),
(29, 'kamal', '8cb2237d0679ca88db6464eac60da96345513964', 'ka@m.com', '', 1, '2021-03-30', '', 0),
(34, 'adnan', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'adnan@a.com', 'adnan stef', 1, '2021-03-31', '64982_droidcam-20200924-104439.jpg', 0),
(37, 'user2', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user@f.com', '', 1, '2021-04-07', '', 0),
(46, 'salem', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'response@dd.com', '', 1, '2021-04-07', '', 0),
(47, 'test2', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'amer@web-tech.com', '', 1, '2021-04-07', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_notification`
--

CREATE TABLE `users_notification` (
  `n_id` int(11) NOT NULL,
  `notification` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_state` tinyint(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_notification`
--

INSERT INTO `users_notification` (`n_id`, `notification`, `date`, `user_id`, `read_state`) VALUES
(11, 'Your membership approved you can now add items and post comments', '2021-04-07 13:57:16', 46, 0),
(12, 'Your Item  \\\" test1 \\\" has been approved ', '2021-04-07 13:58:07', 46, 0),
(13, 'Your Comment on the Product   test1  has been approved ', '2021-04-07 14:33:21', 46, 0),
(16, 'Your membership approved you can now add items and post comments', '2021-04-07 14:40:20', 47, 0),
(19, 'Your Item  \\\" test55 \\\" has been approved ', '2021-04-07 23:13:00', 46, 0),
(20, 'Your Item  \\\" eeee \\\" has been approved ', '2021-04-07 23:29:04', 47, 0),
(21, 'Your Item  \\\" mouth cleaner \\\" has been approved ', '2021-09-05 15:32:23', 34, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items` (`item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_User` (`user_id`),
  ADD KEY `comment_item` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `item_category` (`Cat_ID`),
  ADD KEY `item_user` (`Member_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `users_notification`
--
ALTER TABLE `users_notification`
  ADD PRIMARY KEY (`n_id`),
  ADD KEY `user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users_notification`
--
ALTER TABLE `users_notification`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `items` FOREIGN KEY (`item_id`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_User` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `item_category` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_user` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_notification`
--
ALTER TABLE `users_notification`
  ADD CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
