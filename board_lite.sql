-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2016 at 08:09 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `board_lite`
--

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE `boards` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Empty board',
  `description` varchar(255) NOT NULL DEFAULT 'Empty board',
  `prefix` varchar(255) NOT NULL,
  `tag` enum('SFW','NSFW') NOT NULL DEFAULT 'SFW'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`id`, `name`, `description`, `prefix`, `tag`) VALUES
(1, 'Random', 'NSFW board with random threads.', 'b', 'NSFW'),
(2, 'Music', 'All discussions about music.', 'mu', 'SFW'),
(3, 'Feedback', 'Give us your feedback & bug reports!', 'fb', 'SFW'),
(4, 'Video games', 'PC Master Race', 'v', 'SFW');

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `message_count` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`message_count`) VALUES
(0);

-- --------------------------------------------------------

--
-- Table structure for table `replys`
--

CREATE TABLE `replys` (
  `id` int(11) UNSIGNED NOT NULL,
  `thread_id` int(11) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `msg_id` int(11) UNSIGNED NOT NULL,
  `ip` varchar(255) NOT NULL,
  `img_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE `threads` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `content` text CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `prefix` varchar(255) NOT NULL,
  `msg_id` int(11) UNSIGNED NOT NULL,
  `img_url` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `pass` varchar(60) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `rank` enum('0','1','2') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boards`
--
ALTER TABLE `boards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `replys`
--
ALTER TABLE `replys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `msg_id` (`msg_id`);

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `msg_id` (`msg_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `replys`
--
ALTER TABLE `replys`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
