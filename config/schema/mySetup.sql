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
-- Database: `mySetup_co`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `setup_id` INT(11) NOT NULL,
  `content` TEXT COLLATE utf8mb4_bin NOT NULL,
  `dateTime` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` INT(11) NOT NULL,
  `setup_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` INT(11) NOT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `setup_id` INT(11) DEFAULT NULL,
  `type` VARCHAR(255) COLLATE utf8_bin NOT NULL,
  `title` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `href` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `src` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `setups`
--

CREATE TABLE `setups` (
  `id` INT(11) NOT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `title` VARCHAR(255) COLLATE utf8_bin NOT NULL,
  `description` TEXT COLLATE utf8_bin,
  `author` VARCHAR(255) COLLATE utf8_bin NOT NULL,
  `featured` TINYINT(1) NOT NULL,
  `creationDate` DATETIME NOT NULL,
  `modifiedDate` DATETIME NOT NULL,
  `status` VARCHAR(255) COLLATE utf8_bin NOT NULL,
  `like_count` INT(11) NOT NULL DEFAULT 0,
  `main_colors` VARCHAR(128) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(255) COLLATE utf8_bin NOT NULL,
  `mail` VARCHAR(128) COLLATE utf8_bin NOT NULL,
  `password` VARCHAR(255) COLLATE utf8_bin NOT NULL,
  `preferredStore` VARCHAR(16) COLLATE utf8_bin NOT NULL,
  `timeZone` VARCHAR(16) COLLATE utf8_bin NOT NULL,
  `verified` INT(1) NOT NULL,
  `mailVerification` VARCHAR(32) COLLATE utf8_bin DEFAULT NULL,
  `creationDate` DATETIME NOT NULL,
  `modificationDate` timestamp NOT NULL,
  `lastLogginDate` DATETIME DEFAULT NULL,
  `mainSetup_id` INT(11) NOT NULL DEFAULT 0,
  `twitchToken` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `twitchUserId` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `uwebsite` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `ufacebook` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `utwitter` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
  `utwitch` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `content` TEXT COLLATE utf8mb4_bin NOT NULL,
  `new` TINYINT(1) NOT NULL,
  `dateTime` DATETIME NOT NULL
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
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `setups`
--
ALTER TABLE `setups`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
