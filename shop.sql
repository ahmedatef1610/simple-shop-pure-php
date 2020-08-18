-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3325
-- Generation Time: Aug 17, 2020 at 06:37 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--
CREATE DATABASE IF NOT EXISTS `shop` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `shop`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(1) NOT NULL DEFAULT 0,
  `AllowComment` tinyint(1) NOT NULL DEFAULT 0,
  `AllowAds` tinyint(1) NOT NULL DEFAULT 0,
  `Parent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`Id`, `Name`, `Description`, `Ordering`, `Visibility`, `AllowComment`, `AllowAds`, `Parent`) VALUES
(1, 'pc', 'a wow pc ', 2, 1, 1, 1, 0),
(6, 'clothes', '', 1, 1, 0, 0, 0),
(7, 'books', '', 0, 0, 1, 1, 0),
(8, 'phones', 'a wow mobile phone', 2, 0, 0, 0, 0),
(9, 'Nokia', 'a wow mobile phones', 1, 0, 0, 0, 8),
(10, 'T-shirt', 'a wow clothes', 1, 0, 0, 0, 6),
(11, 'games', 'a wow games', 1, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CommentId` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Status` int(11) NOT NULL,
  `CommentDate` date NOT NULL,
  `ItemId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`CommentId`, `Comment`, `Status`, `CommentDate`, `ItemId`, `UserId`) VALUES
(2, 'thank for this item ❤❤❤❤', 1, '2020-08-14', 8, 3),
(4, 'this is wow', 1, '2020-08-14', 5, 3),
(5, 'it is old', 1, '2020-08-16', 9, 3),
(6, 'lovely', 1, '2020-08-16', 9, 9);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemId` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` int(11) NOT NULL,
  `AddDate` date NOT NULL,
  `CountryMade` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` int(11) NOT NULL DEFAULT 0,
  `CatId` int(11) NOT NULL,
  `MemberId` int(11) NOT NULL,
  `Tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemId`, `Name`, `Description`, `Price`, `AddDate`, `CountryMade`, `Image`, `Status`, `Rating`, `Approve`, `CatId`, `MemberId`, `Tags`) VALUES
(5, 'samsung s6', 'a wow mobile phone', 125, '2020-08-14', 'Korea', '', '5', 3, 1, 8, 1, ''),
(6, 'iphone 6', 'a wow mobile phone', 235, '2020-08-14', 'USA', '', '1', 2, 1, 8, 3, ''),
(7, 'XBOX ONE', 'a wow game platform', 800, '2020-08-14', 'USA', '', '1', 3, 1, 1, 3, ''),
(8, 'PS4', 'a wow game platform', 750, '2020-08-14', 'USA', '', '1', 5, 0, 1, 9, ''),
(9, 'PS3', 'a wow game platform', 120, '2020-08-15', 'USA', '', '1', 2, 1, 1, 3, ''),
(10, 'wii', 'a wow game platform', 454, '2020-08-16', 'japan', '', '1', 2, 1, 1, 3, ''),
(11, 'shot game', 'a wow action game', 30, '2020-08-17', 'Egypt', '', '1', 4, 1, 11, 1, 'action, game,shot'),
(12, 'red alert 2', 'good pc strategy game', 10, '2020-08-17', 'USA', '', '3', 5, 1, 11, 3, 'strategy,online,RPG,game');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserId` int(11) NOT NULL COMMENT 'to identify user',
  `UserName` varchar(255) NOT NULL COMMENT 'username to login',
  `Password` varchar(255) NOT NULL COMMENT 'password to login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupId` int(11) NOT NULL DEFAULT 0 COMMENT 'to identify user permission',
  `TrustStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'seller rank',
  `RegStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'user approval',
  `Date` date NOT NULL,
  `avatar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserId`, `UserName`, `Password`, `Email`, `FullName`, `GroupId`, `TrustStatus`, `RegStatus`, `Date`, `avatar`) VALUES
(1, 'ahmedatef1610', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'test@test.com', 'Ahmed Atef', 1, 0, 1, '2020-08-11', ''),
(3, 'atef', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'test2@test.com', 'Atef Saad', 0, 0, 1, '2020-08-12', ''),
(9, 'salma', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'test3@test.com', 'salma atef', 0, 0, 1, '2020-08-12', ''),
(10, 'hala', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'test5@test.com', 'Hala Abd-Elsatar', 0, 0, 1, '2020-08-12', ''),
(11, 'amany', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'test4@test.com', 'amany atef', 0, 0, 1, '2020-08-12', ''),
(60, 'xxxx', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'xxx@xxx.com', 'xxxxx', 0, 0, 1, '2020-08-17', '61480_cleopatra_2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentId`),
  ADD KEY `ItemId` (`ItemId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemId`),
  ADD KEY `MemberId` (`MemberId`),
  ADD KEY `CatId` (`CatId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserId`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT COMMENT 'to identify user', AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`ItemId`) REFERENCES `items` (`ItemId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`MemberId`) REFERENCES `users` (`UserId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`CatId`) REFERENCES `categories` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
