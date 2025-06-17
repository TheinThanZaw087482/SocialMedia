-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 09:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `4633870_socialmedia`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `commentID` varchar(20) NOT NULL,
  `postID` varchar(20) NOT NULL,
  `contentID` varchar(20) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `reactID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hidden_posts`
--

CREATE TABLE `hidden_posts` (
  `hidden_post_id` int(11) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `postID` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hidden_posts`
--

INSERT INTO `hidden_posts` (`hidden_post_id`, `userID`, `postID`) VALUES
(6, '2321067', 10),
(7, '2321067', 10),
(8, '2321067', 10),
(9, '2321067', 9),
(10, '2321067', 8),
(11, '2321067', 14),
(12, '2321067', 14),
(13, '2321067', 11),
(14, '2321067', 14),
(16, '2321067', 7);

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `imgID` int(20) NOT NULL,
  `postID` int(20) NOT NULL,
  `img_url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`imgID`, `postID`, `img_url`) VALUES
(1, 1, 'WIN_20241030_14_39_09_Pro.jpg'),
(2, 1, 'WIN_20241030_14_40_42_Pro.jpg'),
(3, 1, 'WIN_20241030_14_39_09_Pro.jpg'),
(4, 1, 'cat1.jpg'),
(6, 1, 'WIN_20241030_14_40_54_Pro.jpg'),
(7, 1, 'WIN_20241030_14_41_19_Pro.jpg'),
(8, 1, 'WIN_20241107_14_55_46_Pro.jpg'),
(9, 1, 'WIN_20250108_12_57_15_Pro.jpg'),
(11, 1, 'WIN_20250108_12_57_29_Pro.jpg'),
(12, 2, 'cat1.jpg'),
(13, 2, 'cat2.jpg'),
(14, 2, 'cat3.jpg'),
(17, 3, '1748079016_683191a8e4f53.jpg'),
(18, 3, '1748074772_68318114ab3cd.jpg'),
(19, 4, 'download1.jpeg'),
(20, 4, 'download2.jpg'),
(21, 4, 'download1.jpeg'),
(22, 4, '2321067_cover.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `senderID` varchar(20) NOT NULL,
  `reciverID` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `message` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `senderID`, `reciverID`, `type`, `link`, `is_read`, `created_at`, `message`) VALUES
(1, '2321067', '2312073', 'login', '', 0, '2025-05-27 00:20:14', NULL),
(2, '2321067', '2321069', 'login', '', 0, '2025-05-27 00:20:14', NULL),
(3, '2321067', '2312073', 'login', '', 0, '2025-05-27 00:20:14', NULL),
(4, '2321067', '2321069', 'login', '', 0, '2025-05-27 00:20:14', NULL),
(5, '2321067', '2312073', 'login', '', 0, '2025-05-27 00:20:52', NULL),
(6, '2321067', '2321069', 'login', '', 0, '2025-05-27 00:20:52', NULL),
(7, '2321067', '2312073', 'login', '', 0, '2025-05-27 00:20:52', NULL),
(8, '2321067', '2321069', 'login', '', 0, '2025-05-27 00:20:52', NULL),
(9, '2321067', '2312073', 'login', '', 0, '2025-05-27 01:06:02', NULL),
(10, '2321067', '2321069', 'login', '', 0, '2025-05-27 01:06:02', NULL),
(11, '2321067', '2312073', 'login', '', 0, '2025-05-27 01:06:02', NULL),
(12, '2321067', '2321069', 'login', '', 0, '2025-05-27 01:06:02', NULL),
(13, '2312073', '2321067', 'login', '', 0, '2025-05-27 01:06:15', NULL),
(14, '2312073', '2321069', 'login', '', 0, '2025-05-27 01:06:15', NULL),
(15, '2312073', '2321067', 'login', '', 0, '2025-05-27 01:06:15', NULL),
(16, '2312073', '2321069', 'login', '', 0, '2025-05-27 01:06:15', NULL),
(17, '2312073', '2321067', 'login', '', 0, '2025-05-27 01:07:45', NULL),
(18, '2312073', '2321069', 'login', '', 0, '2025-05-27 01:07:45', NULL),
(19, '2312073', '2321067', 'login', '', 0, '2025-05-27 01:07:45', NULL),
(20, '2312073', '2321069', 'login', '', 0, '2025-05-27 01:07:45', NULL),
(21, '2312073', '2321067', 'login', '', 0, '2025-05-27 01:07:53', NULL),
(22, '2312073', '2321069', 'login', '', 0, '2025-05-27 01:07:53', NULL),
(23, '2312073', '2321067', 'login', '', 0, '2025-05-27 01:07:53', NULL),
(24, '2312073', '2321069', 'login', '', 0, '2025-05-27 01:07:53', NULL),
(25, '2321067', '2312073', 'login', '', 0, '2025-05-27 01:08:18', NULL),
(26, '2321067', '2321069', 'login', '', 0, '2025-05-27 01:08:18', NULL),
(27, '2321067', '2312073', 'login', '', 0, '2025-05-27 01:08:18', NULL),
(28, '2321067', '2321069', 'login', '', 0, '2025-05-27 01:08:18', NULL),
(29, '2321067', '2312073', 'login', '', 0, '2025-05-27 02:24:45', NULL),
(30, '2321067', '2321069', 'login', '', 0, '2025-05-27 02:24:45', NULL),
(31, '2321067', '2312073', 'login', '', 0, '2025-05-27 02:24:45', NULL),
(32, '2321067', '2321069', 'login', '', 0, '2025-05-27 02:24:45', NULL),
(33, '2321067', '2312073', 'post', 'new post', 0, '2025-06-04 12:28:53', NULL),
(34, '2321067', '2321069', 'post', 'new post', 0, '2025-06-04 12:28:53', NULL),
(35, '2321067', '2312073', 'post', 'new post', 0, '2025-06-04 14:32:08', NULL),
(36, '2321067', '2321069', 'post', 'new post', 0, '2025-06-04 14:32:08', NULL),
(37, '2321067', 'test1', 'post', 'new post', 0, '2025-06-04 14:32:08', NULL),
(41, 'test4', '2321069', 'Register', '', 0, '2025-06-06 02:43:09', NULL),
(42, 'test4', 'test2', 'Register', '', 0, '2025-06-06 02:43:09', NULL),
(43, 'test4', 'test3', 'Register', '', 0, '2025-06-06 02:43:09', NULL),
(44, '2321069', '2312073', 'post', 'new post', 0, '2025-06-09 13:37:02', NULL),
(45, '2321069', '2321067', 'post', 'new post', 0, '2025-06-09 13:37:02', NULL),
(46, '2321069', 'test1', 'post', 'new post', 0, '2025-06-09 13:37:02', NULL),
(47, '2321069', 'test2', 'post', 'new post', 0, '2025-06-09 13:37:02', NULL),
(48, '2321069', 'test3', 'post', 'new post', 0, '2025-06-09 13:37:02', NULL),
(49, '2321069', 'test4', 'post', 'new post', 0, '2025-06-09 13:37:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `postID` int(20) NOT NULL,
  `postdate` datetime NOT NULL,
  `userID` varchar(20) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `commentID` varchar(20) DEFAULT NULL,
  `reactID` varchar(20) DEFAULT NULL,
  `privacy` varchar(20) NOT NULL,
  `shareID` varchar(20) DEFAULT NULL,
  `reportID` varchar(20) DEFAULT NULL,
  `saveID` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`postID`, `postdate`, `userID`, `content`, `commentID`, `reactID`, `privacy`, `shareID`, `reportID`, `saveID`, `image`) VALUES
(1, '2025-05-16 10:41:39', '2312073', 'Hello', '', '', '', '', '', '', NULL),
(2, '2025-05-16 10:47:26', '2321067', 'Today is a good day', '', '', '', '', '', '', NULL),
(3, '2025-05-16 10:47:56', '2312073', 'I am here', '', '', '', '', '', '', NULL),
(4, '2025-05-16 16:25:58', '2321067', 'Now I writing for posting backend', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(5, '2025-05-16 22:32:24', '2312073', 'test', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(6, '2025-05-16 22:33:39', '2312073', 'complete post!', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(7, '2025-05-16 22:39:58', '2312073', 'it worked!', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(8, '2025-05-16 22:48:50', '2312073', 'test', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(9, '2025-05-16 22:48:56', '2312073', 'test', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(10, '2025-05-16 22:52:50', '2312073', 'testing', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(11, '2025-05-18 02:24:34', '2312073', 'hi there', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(12, '2025-05-19 01:38:58', '2321067', 'Hello', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(13, '2025-05-19 02:17:48', '2321067', 'Hi', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(14, '2025-05-19 02:22:42', '2312073', 'Done!', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(15, '2025-05-19 15:17:27', '2321067', 'Hello Testing', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(16, '2025-05-19 18:25:04', '2321067', 'privacy Testing', NULL, NULL, '', NULL, NULL, NULL, NULL),
(17, '2025-05-19 18:32:54', '2321067', 'privacy Testing2', NULL, NULL, 'batch', NULL, NULL, NULL, NULL),
(18, '2025-05-19 18:52:46', '2321067', 'privacy testing3', NULL, NULL, 'only_me', NULL, NULL, NULL, NULL),
(19, '2025-05-19 22:19:06', '2321067', 'Hi', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(22, '2025-05-19 23:37:17', '2321067', 'Hello', NULL, NULL, 'batch', NULL, NULL, NULL, NULL),
(24, '2025-05-19 23:47:45', '2321067', 'test', NULL, NULL, 'batch', NULL, NULL, NULL, '1747675065_682b67b989120.png'),
(27, '2025-05-22 14:13:27', '2321067', '', NULL, NULL, 'public', NULL, NULL, NULL, '1747899807_682ed59f6a4e5.png'),
(29, '2025-06-04 12:28:53', '2321067', 'This is the testing', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(30, '2025-06-04 14:32:08', '2321067', 'test', NULL, NULL, 'public', NULL, NULL, NULL, NULL),
(31, '2025-06-09 13:37:02', '2321069', 'hi', NULL, NULL, 'public', NULL, NULL, NULL, '1749452822_684688166901a.png');

-- --------------------------------------------------------

--
-- Table structure for table `reaction`
--

CREATE TABLE `reaction` (
  `reactionID` int(20) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `postID` int(20) NOT NULL,
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reaction`
--

INSERT INTO `reaction` (`reactionID`, `userID`, `postID`, `type`) VALUES
(93, '2312073', 5, 'Haha'),
(94, '2312073', 4, 'Haha'),
(95, '2321067', 11, 'Like'),
(96, '2321067', 10, 'Haha'),
(97, '2321067', 9, 'Haha'),
(98, '2321067', 5, 'Wow'),
(99, '2321067', 4, 'Wow'),
(100, '2321067', 3, 'Haha'),
(101, '2321067', 12, 'Sad'),
(102, '2321067', 8, 'Wow'),
(103, '2321067', 7, 'Sad'),
(104, '2321067', 6, 'Sad'),
(105, '2321067', 13, 'Wow'),
(106, '2321067', 1, 'Wow'),
(107, '2312073', 13, 'Wow'),
(108, '2312073', 12, 'Like'),
(109, '2312073', 11, 'Wow'),
(110, '2312073', 14, 'Haha'),
(111, '2312073', 3, 'Haha'),
(112, '2312073', 2, 'Wow'),
(114, '2321067', 19, 'Haha'),
(115, '2321067', 18, 'Like'),
(117, '2321067', 17, 'Haha'),
(118, '2321067', 24, 'Wow'),
(121, '2321067', 14, 'Love'),
(123, '2321067', 27, 'Haha'),
(124, '2321067', 2, 'Like'),
(126, '2321069', 14, 'Wow'),
(127, '2321069', 13, 'Like'),
(129, '2321067', 16, 'Love'),
(130, '2321069', 27, 'Wow'),
(131, '2321069', 17, 'Wow'),
(132, '2321069', 18, 'Wow'),
(133, '2321069', 12, 'Sad'),
(134, '2321069', 2, 'Haha'),
(135, '2321069', 8, 'Sad'),
(136, '2312073', 9, 'Love'),
(137, '2321067', 22, 'Haha'),
(138, '2321067', 15, 'Haha'),
(139, '2321067', 29, 'Love'),
(140, '2321067', 30, 'Love'),
(141, '2321069', 5, 'Love'),
(142, '2321069', 31, 'Love');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `reportID` varchar(20) NOT NULL,
  `reportuserID` varchar(20) NOT NULL,
  `is_reportedID` varchar(20) NOT NULL,
  `contenttype` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savepost`
--

CREATE TABLE `savepost` (
  `saveID` varchar(20) NOT NULL,
  `postID` varchar(20) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `savedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `share`
--

CREATE TABLE `share` (
  `shareID` varchar(20) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `postID` varchar(20) NOT NULL,
  `sharedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `ProfileimagePath` varchar(255) DEFAULT '2321067_profile.jpg',
  `coverPhoto` varchar(255) DEFAULT '2321067_cover.jpg',
  `Address` varchar(100) DEFAULT NULL,
  `Batch` varchar(20) NOT NULL,
  `online` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `userType` varchar(10) DEFAULT NULL,
  `approve` tinyint(1) NOT NULL DEFAULT 0,
  `nickname` varchar(255) NOT NULL,
  `bio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `name`, `email`, `password`, `gender`, `birthdate`, `ProfileimagePath`, `coverPhoto`, `Address`, `Batch`, `online`, `date`, `userType`, `approve`, `nickname`, `bio`) VALUES
('2312073', 'Myo Aun', 'myo202aung202@gmail.com', '$2y$10$FMZ9Gn/qdQ4UxwnuPH2K7.uiDpN5ILEgeqAPb5qFhFxrYggxQ.EQW', 'male', '2002-02-16', 'Pic 12.png	', 'porfileimage.pn', NULL, 'bt11', 0, '2025-05-16 10:41:39', 'student', 1, '', ''),
('2321067', 'Thein Than Zaw', 'sanminhtike9289@gmail.com', '$2y$10$oGOatOAszg7RuxAzzXnotepfIXZVZozGQN5GLZryrRv0A1gdlpW8G', 'male', '2025-05-01', '1748240764_6834097c8e11e.png', '1748110910_68320e3e11b1d.jpg', NULL, 'bt11', 0, '2025-05-16 10:41:39', 'student', 1, '', ''),
('2321069', 'San Min Htike', 'theinthanzaw2023@outlook.com', '$2y$10$rjATF4b8mquiMGG9nhUE..lffF46eQSxijDMaxp6OLJFrguWJYVRi', 'male', '2000-03-15', '2321067_profile.jpg', '2321067_cover.jpg', NULL, 'bt11', 0, '0000-00-00 00:00:00', 'admin', 1, '', ''),
('test1', 'test1', 'test1@gmail.com', '$2y$10$XxJPEMnLDVjCwbNqf0W6geJbesZCyY8mKnEJMGM4D1iuRoTWzM89C', 'male', '2025-06-05', '2321067_profile.jpg', '2321067_cover.jpg', NULL, 'bt11', 0, '0000-00-00 00:00:00', NULL, 1, '', ''),
('test2', 'test2', 'test2@gmail.com', '$2y$10$yaOMc5SiT/y/mHDuBQMN.OpaFf9UEUkQPDBHxof8t5h/ZgMEqEBv.', 'male', '2025-06-05', '2321067_profile.jpg', '2321067_cover.jpg', NULL, 'bt11', 0, '0000-00-00 00:00:00', 'admin', 1, '', ''),
('test3', 'test3', 'test3@gmail.com', '$2y$10$N.PAQWbaTRTJAeVxHetYiutGc1UMXV0789otCfD2/MLfqphdahYr6', 'male', '2025-06-07', '2321067_profile.jpg', '2321067_cover.jpg', NULL, 'bt11', 0, '0000-00-00 00:00:00', 'admin', 1, '', ''),
('test4', 'test4', 'test4@gmail.com', '$2y$10$qWrtkVy29ri0hXgK/W0BNeYyFydBYMznmB/mibLJHBVonQZJD2pni', 'male', '2025-06-14', '2321067_profile.jpg', '2321067_cover.jpg', NULL, 'bt11', 0, '0000-00-00 00:00:00', NULL, 0, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentID`);

--
-- Indexes for table `hidden_posts`
--
ALTER TABLE `hidden_posts`
  ADD PRIMARY KEY (`hidden_post_id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `postID` (`postID`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`imgID`),
  ADD KEY `postID` (`postID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_user_id` (`reciverID`),
  ADD KEY `notifications_ibfk_1` (`senderID`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`postID`),
  ADD KEY `post_user` (`userID`);

--
-- Indexes for table `reaction`
--
ALTER TABLE `reaction`
  ADD PRIMARY KEY (`reactionID`),
  ADD KEY `postID` (`postID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`reportID`);

--
-- Indexes for table `savepost`
--
ALTER TABLE `savepost`
  ADD PRIMARY KEY (`saveID`);

--
-- Indexes for table `share`
--
ALTER TABLE `share`
  ADD PRIMARY KEY (`shareID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`),
  ADD KEY `online` (`online`),
  ADD KEY `date` (`date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hidden_posts`
--
ALTER TABLE `hidden_posts`
  MODIFY `hidden_post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `imgID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `postID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `reaction`
--
ALTER TABLE `reaction`
  MODIFY `reactionID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hidden_posts`
--
ALTER TABLE `hidden_posts`
  ADD CONSTRAINT `hidden_posts_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `hidden_posts_ibfk_2` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`);

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`senderID`) REFERENCES `users` (`userid`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userid`);

--
-- Constraints for table `reaction`
--
ALTER TABLE `reaction`
  ADD CONSTRAINT `reaction_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`),
  ADD CONSTRAINT `reaction_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
