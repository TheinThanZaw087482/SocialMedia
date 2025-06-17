-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 10:11 PM
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
  `saveID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`postID`, `postdate`, `userID`, `content`, `commentID`, `reactID`, `privacy`, `shareID`, `reportID`, `saveID`) VALUES
(1, '2025-05-16 10:41:39', '2312073', 'Hello', '', '', '', '', '', ''),
(2, '2025-05-16 10:47:26', '2321067', 'Today is a good day', '', '', '', '', '', ''),
(3, '2025-05-16 10:47:56', '2312073', 'I am here', '', '', '', '', '', ''),
(4, '2025-05-16 16:25:58', '2321067', 'Now I writing for posting backend', NULL, NULL, 'public', NULL, NULL, NULL),
(5, '2025-05-16 22:32:24', '2312073', 'test', NULL, NULL, 'public', NULL, NULL, NULL),
(6, '2025-05-16 22:33:39', '2312073', 'complete post!', NULL, NULL, 'public', NULL, NULL, NULL),
(7, '2025-05-16 22:39:58', '2312073', 'it worked!', NULL, NULL, 'public', NULL, NULL, NULL),
(8, '2025-05-16 22:48:50', '2312073', 'test', NULL, NULL, 'public', NULL, NULL, NULL),
(9, '2025-05-16 22:48:56', '2312073', 'test', NULL, NULL, 'public', NULL, NULL, NULL),
(10, '2025-05-16 22:52:50', '2312073', 'testing', NULL, NULL, 'public', NULL, NULL, NULL),
(11, '2025-05-18 02:24:34', '2312073', 'hi there', NULL, NULL, 'public', NULL, NULL, NULL),
(12, '2025-05-19 01:38:58', '2321067', 'Hello', NULL, NULL, 'public', NULL, NULL, NULL),
(13, '2025-05-19 02:17:48', '2321067', 'Hi', NULL, NULL, 'public', NULL, NULL, NULL),
(14, '2025-05-19 02:22:42', '2312073', 'Done!', NULL, NULL, 'public', NULL, NULL, NULL);

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
(95, '2321067', 11, 'Haha'),
(96, '2321067', 10, 'Haha'),
(97, '2321067', 9, 'Haha'),
(98, '2321067', 5, 'Haha'),
(99, '2321067', 4, 'Haha'),
(100, '2321067', 3, 'Haha'),
(101, '2321067', 12, 'Haha'),
(102, '2321067', 8, 'Haha'),
(103, '2321067', 7, 'Wow'),
(104, '2321067', 6, 'Sad'),
(105, '2321067', 13, 'Haha'),
(106, '2321067', 1, 'Haha'),
(107, '2312073', 13, 'Haha'),
(108, '2312073', 12, 'Sad'),
(109, '2312073', 11, 'Wow'),
(110, '2312073', 14, 'Wow');

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
  `ProfileimagePath` varchar(255) NOT NULL DEFAULT 'porfileimage.png',
  `Address` varchar(100) DEFAULT NULL,
  `Batch` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `name`, `email`, `password`, `gender`, `birthdate`, `ProfileimagePath`, `Address`, `Batch`) VALUES
('2312073', 'Myo Aung', 'myo202aung202@gmail.com', '$2y$10$FMZ9Gn/qdQ4UxwnuPH2K7.uiDpN5ILEgeqAPb5qFhFxrYggxQ.EQW', 'male', '2002-02-16', '', NULL, 'bt11'),
('2321067', 'Thein Than Zaw', 'sanminhtike9289@gmail.com', '$2y$10$oGOatOAszg7RuxAzzXnotepfIXZVZozGQN5GLZryrRv0A1gdlpW8G', 'male', '2025-05-01', '', NULL, 'bt11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentID`);

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
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `postID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reaction`
--
ALTER TABLE `reaction`
  MODIFY `reactionID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- Constraints for dumped tables
--

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
