-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 08:40 AM
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
(16, '2321067', 7),
(18, '2321067', 14);

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
(27, '2025-05-22 14:13:27', '2321067', '', NULL, NULL, 'public', NULL, NULL, NULL, '1747899807_682ed59f6a4e5.png');

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
(118, '2321067', 24, 'Haha'),
(121, '2321067', 14, 'Love'),
(123, '2321067', 27, 'Love'),
(124, '2321067', 2, 'Like'),
(126, '2321069', 14, 'Wow'),
(127, '2321069', 13, 'Like'),
(129, '2321067', 16, 'Love'),
(130, '2321069', 27, 'Wow'),
(131, '2321069', 17, 'Wow'),
(132, '2321069', 18, 'Wow'),
(133, '2321069', 12, 'Sad'),
(134, '2321069', 2, 'Haha'),
(135, '2321069', 8, 'Sad');

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
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `name`, `email`, `password`, `gender`, `birthdate`, `ProfileimagePath`, `coverPhoto`, `Address`, `Batch`, `online`, `date`) VALUES
('2312073', 'Myo Aun', 'myo202aung202@gmail.com', '$2y$10$FMZ9Gn/qdQ4UxwnuPH2K7.uiDpN5ILEgeqAPb5qFhFxrYggxQ.EQW', 'male', '2002-02-16', 'Pic 12.png	', 'porfileimage.pn', NULL, 'bt11', 0, '2025-05-16 10:41:39'),
('2321067', 'Thein Than Zaw', 'sanminhtike9289@gmail.com', '$2y$10$oGOatOAszg7RuxAzzXnotepfIXZVZozGQN5GLZryrRv0A1gdlpW8G', 'male', '2025-05-01', '1748240764_6834097c8e11e.png', '1748110910_68320e3e11b1d.jpg', NULL, 'bt11', 0, '2025-05-16 10:41:39'),
('2321069', 'San Min Htike', 'theinthanzaw2023@outlook.com', '$2y$10$rjATF4b8mquiMGG9nhUE..lffF46eQSxijDMaxp6OLJFrguWJYVRi', 'male', '2000-03-15', '2321067_profile.jpg', '2321067_cover.jpg', NULL, 'bt11', 0, '0000-00-00 00:00:00');

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `hidden_post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `postID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `reaction`
--
ALTER TABLE `reaction`
  MODIFY `reactionID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

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
