-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 04, 2017 at 06:01 PM
-- Server version: 5.6.30-1
-- PHP Version: 7.0.16-3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `MySetup`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `setup_id` int(11) NOT NULL,
  `content` text COLLATE utf8mb4_bin NOT NULL,
  `dateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `setup_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `setup_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `href` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `src` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `setups`
--

CREATE TABLE `setups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin,
  `author` varchar(255) COLLATE utf8_bin NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `creationDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL,
  `status` VARCHAR(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `mail` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `preferredStore` varchar(16) COLLATE utf8_bin NOT NULL,
  `timeZone` varchar(16) COLLATE utf8_bin NOT NULL,
  `verified` int(1) NOT NULL,
  `mailVerification` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `creationDate` datetime NOT NULL,
  `lastLogginDate` datetime DEFAULT NULL,
  `twitchToken` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `uwebsite` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `ufacebook` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `utwitter` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `content` text COLLATE utf8mb4_bin NOT NULL,
  `new` TINYINT(1) NOT NULL,
  `dateTime` datetime NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` INT(11) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `picture` VARCHAR(255) NOT NULL,
  `dateTime` DATETIME NOT NULL,
  `user_id` INT(11) NOT NULL,
  `category` VARCHAR(255) NOT NULL,
  `tags` VARCHAR(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` INT(11) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `setup_id` INT(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `mail`, `password`, `preferredStore`, `verified`, `mailVerification`) VALUES
(1, 'Admin', 'admin@admin.admin', '$2y$10$YRpe68er1G1m4Adani5hbOaFbWKKsog6eiLI45OGSBI7es9QMyg2m', 'FR', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setups`
--
ALTER TABLE `setups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `setups`
--
ALTER TABLE `setups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
