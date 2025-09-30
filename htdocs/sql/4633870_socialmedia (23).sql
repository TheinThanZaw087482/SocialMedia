-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 08:24 AM
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
  `commentID` varchar(255) NOT NULL,
  `postID` int(20) NOT NULL,
  `content` varchar(255) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentID`, `postID`, `content`, `userID`, `Date`) VALUES
('C1', 29, 'Hi', '2321069', '2025-07-06 22:55:39'),
('C2', 34, 'HI', '2321069', '2025-07-06 22:59:02'),
('C3', 41, 'Hi', '2321069', '2025-07-06 23:36:27');

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
(2, '2312073', 41),
(4, '2321069', 44),
(5, '2321069', 40),
(6, '2321067', 53);

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
(22, 4, '2321067_cover.jpg'),
(23, 35, '1749586148_684890e488331.jpg'),
(24, 35, '1749586148_684890e48866c.jpg'),
(25, 35, '1749586148_684890e488969.jpg'),
(26, 35, '1749586148_684890e488bed.jpg'),
(27, 36, '1749586211_68489123803ef.jpg'),
(28, 36, '1749586211_6848912380769.jpg'),
(29, 36, '1749586211_6848912380a25.jpg'),
(30, 36, '1749586211_6848912380ce1.jpg'),
(31, 36, '1749586211_68489123810d5.jpg'),
(32, 36, '1749586211_6848912381368.jpg'),
(33, 36, '1749586211_6848912381642.jpg'),
(40, 38, '1749587110_684894a6d109f.png'),
(41, 38, '1749587110_684894a6d1428.png'),
(42, 39, '1749587896_684897b8d306d.png'),
(43, 39, '1749587896_684897b8d34d8.png'),
(44, 39, '1749587896_684897b8d3881.png'),
(45, 40, '1749652947_684995d38a766.png'),
(46, 40, '1749652947_684995d38af21.png'),
(47, 40, '1749652947_684995d38b295.png'),
(48, 40, '1749652947_684995d38b690.png'),
(49, 40, '1749652947_684995d38b9fb.png'),
(50, 41, '1749705631_684a639f9e7e2.png'),
(51, 41, '1749705631_684a639f9f6ff.png'),
(52, 41, '1749705631_684a639f9fdc0.png'),
(53, 41, '1749705631_684a639fa0424.png'),
(54, 41, '1749705631_684a639fa0bd9.png'),
(56, 43, '1750401979_685503bbaec9a.jpg'),
(57, 44, '1750403397_685509452e11c.jpg'),
(63, 51, '1751687387_6868a0db248b6.png'),
(64, 52, '1751824476_686ab85c2cc53.jpg'),
(66, 62, '1751871188_686b6ed477e9f.jpg'),
(67, 62, '1751871188_686b6ed4784c2.jpg'),
(68, 62, '1751871188_686b6ed47871b.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` varchar(20) DEFAULT NULL,
  `receiver_id` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_url` varchar(255) DEFAULT NULL,
  `message_type` varchar(50) DEFAULT NULL
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
(1, '2321067', '2321069', 'Report', '46', 0, '2025-07-03 08:06:23', NULL),
(2, '2321067', 'test2', 'Report', '46', 0, '2025-07-03 08:06:23', NULL),
(3, '2321067', 'test3', 'Report', '46', 0, '2025-07-03 08:06:23', NULL),
(4, '2321067', '2321069', 'Report', '44', 0, '2025-07-03 08:12:42', NULL),
(5, '2321067', 'test2', 'Report', '44', 0, '2025-07-03 08:12:42', NULL),
(6, '2321067', 'test3', 'Report', '44', 0, '2025-07-03 08:12:42', NULL),
(7, '2321069', '2321069', 'Report', '44', 0, '2025-07-03 09:14:55', NULL),
(8, '2321069', 'test2', 'Report', '44', 0, '2025-07-03 09:14:55', NULL),
(9, '2321069', 'test3', 'Report', '44', 0, '2025-07-03 09:14:55', NULL),
(10, '2321069', '2312073', 'post', 'new post', 0, '2025-07-05 10:19:47', NULL),
(11, '2321069', '2321067', 'post', 'new post', 0, '2025-07-05 10:19:47', NULL),
(12, '2321069', 'test1', 'post', 'new post', 0, '2025-07-05 10:19:47', NULL),
(13, '2321069', 'test2', 'post', 'new post', 0, '2025-07-05 10:19:47', NULL),
(14, '2321069', 'test3', 'post', 'new post', 0, '2025-07-05 10:19:47', NULL),
(15, '2321069', 'test4', 'post', 'new post', 0, '2025-07-05 10:19:47', NULL),
(16, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:24:36', NULL),
(17, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:24:36', NULL),
(18, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:24:36', NULL),
(19, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:24:36', NULL),
(20, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:24:36', NULL),
(21, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:24:36', NULL),
(22, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:40:26', NULL),
(23, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:40:26', NULL),
(24, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:40:26', NULL),
(25, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:40:26', NULL),
(26, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:40:26', NULL),
(27, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:40:26', NULL),
(28, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:40:43', NULL),
(29, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:40:43', NULL),
(30, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:40:43', NULL),
(31, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:40:43', NULL),
(32, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:40:43', NULL),
(33, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:40:43', NULL),
(34, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:42:29', NULL),
(35, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:42:29', NULL),
(36, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:42:29', NULL),
(37, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:42:29', NULL),
(38, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:42:29', NULL),
(39, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:42:29', NULL),
(40, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:42:41', NULL),
(41, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:42:41', NULL),
(42, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:42:41', NULL),
(43, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:42:41', NULL),
(44, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:42:41', NULL),
(45, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:42:41', NULL),
(46, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:48:38', NULL),
(47, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:48:38', NULL),
(48, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:48:38', NULL),
(49, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:48:38', NULL),
(50, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:48:38', NULL),
(51, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:48:38', NULL),
(52, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:49:46', NULL),
(53, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:49:46', NULL),
(54, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:49:46', NULL),
(55, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:49:46', NULL),
(56, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:49:46', NULL),
(57, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:49:46', NULL),
(58, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:50:16', NULL),
(59, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:50:16', NULL),
(60, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:50:16', NULL),
(61, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:50:16', NULL),
(62, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:50:16', NULL),
(63, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:50:16', NULL),
(64, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:51:00', NULL),
(65, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:51:00', NULL),
(66, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:51:00', NULL),
(67, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:51:00', NULL),
(68, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:51:00', NULL),
(69, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:51:00', NULL),
(70, '2321069', '2312073', 'post', 'new post', 0, '2025-07-07 00:52:50', NULL),
(71, '2321069', '2321067', 'post', 'new post', 0, '2025-07-07 00:52:50', NULL),
(72, '2321069', 'test1', 'post', 'new post', 0, '2025-07-07 00:52:50', NULL),
(73, '2321069', 'test2', 'post', 'new post', 0, '2025-07-07 00:52:50', NULL),
(74, '2321069', 'test3', 'post', 'new post', 0, '2025-07-07 00:52:50', NULL),
(75, '2321069', 'test4', 'post', 'new post', 0, '2025-07-07 00:52:50', NULL),
(76, '2321067', '2312073', 'post', 'new post', 0, '2025-07-07 13:23:08', NULL),
(77, '2321067', '2321069', 'post', 'new post', 0, '2025-07-07 13:23:08', NULL),
(78, '2321067', 'test1', 'post', 'new post', 0, '2025-07-07 13:23:08', NULL),
(79, '2321067', 'test2', 'post', 'new post', 0, '2025-07-07 13:23:08', NULL),
(80, '2321067', 'test3', 'post', 'new post', 0, '2025-07-07 13:23:08', NULL),
(81, '2321067', 'test4', 'post', 'new post', 0, '2025-07-07 13:23:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `postID` int(20) NOT NULL,
  `postdate` datetime NOT NULL,
  `userID` varchar(20) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `is_ban` tinyint(1) NOT NULL DEFAULT 0,
  `privacy` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`postID`, `postdate`, `userID`, `content`, `is_ban`, `privacy`) VALUES
(1, '2025-05-16 10:41:39', '2312073', 'Hello', 1, ''),
(2, '2025-05-16 10:47:26', '2321067', 'Today is a good day', 0, ''),
(3, '2025-05-16 10:47:56', '2312073', 'I am here', 0, ''),
(4, '2025-05-16 16:25:58', '2321067', 'Now I writing for posting backend', 0, 'public'),
(5, '2025-05-16 22:32:24', '2312073', 'test', 0, 'public'),
(6, '2025-05-16 22:33:39', '2312073', 'complete post!', 0, 'public'),
(7, '2025-05-16 22:39:58', '2312073', 'it worked!', 0, 'public'),
(8, '2025-05-16 22:48:50', '2312073', 'test', 0, 'public'),
(9, '2025-05-16 22:48:56', '2312073', 'test', 0, 'public'),
(10, '2025-05-16 22:52:50', '2312073', 'testing', 0, 'public'),
(11, '2025-05-18 02:24:34', '2312073', 'hi there', 0, 'public'),
(12, '2025-05-19 01:38:58', '2321067', 'Hello', 0, 'public'),
(13, '2025-05-19 02:17:48', '2321067', 'Hi', 0, 'public'),
(14, '2025-05-19 02:22:42', '2312073', 'Done!', 0, 'public'),
(15, '2025-05-19 15:17:27', '2321067', 'Hello Testing', 0, 'public'),
(16, '2025-05-19 18:25:04', '2321067', 'privacy Testing', 0, ''),
(17, '2025-05-19 18:32:54', '2321067', 'privacy Testing2', 0, 'batch'),
(18, '2025-05-19 18:52:46', '2321067', 'privacy testing3', 0, 'only_me'),
(19, '2025-05-19 22:19:06', '2321067', 'Hi', 0, 'public'),
(22, '2025-05-19 23:37:17', '2321067', 'Hello', 0, 'batch'),
(27, '2025-05-22 14:13:27', '2321067', '', 0, 'public'),
(29, '2025-06-04 12:28:53', '2321067', 'This is the testing', 0, 'public'),
(30, '2025-06-04 14:32:08', '2321067', 'test', 0, 'public'),
(31, '2025-06-09 13:37:02', '2321069', 'hi', 0, 'public'),
(32, '2025-06-10 12:31:27', '2321069', 'testing hide post', 0, 'public'),
(33, '2025-06-10 23:17:02', '2321067', '', 0, 'public'),
(34, '2025-06-11 01:41:16', '2321067', 'testing', 0, 'public'),
(35, '2025-06-11 02:39:08', '2312073', '', 0, 'public'),
(36, '2025-06-11 02:40:11', '2312073', '', 0, 'public'),
(38, '2025-06-11 02:55:10', '2312073', '', 0, 'public'),
(39, '2025-06-11 03:08:16', '2321067', 'test', 0, 'public'),
(40, '2025-06-11 21:12:27', '2321067', '', 0, 'public'),
(41, '2025-06-12 11:50:31', '2321067', '', 0, 'public'),
(43, '2025-06-20 13:16:19', '2321067', '', 1, 'public'),
(44, '2025-06-20 13:39:57', '2321067', '', 1, 'public'),
(51, '2025-07-05 10:19:47', '2321069', '', 0, 'public'),
(52, '2025-07-07 00:24:36', '2321069', '', 0, 'public'),
(53, '2025-07-07 00:40:26', '2321069', 'HI', 0, 'public'),
(60, '2025-07-07 00:51:00', '2321069', 'Hello', 0, 'only_me'),
(62, '2025-07-07 13:23:08', '2321067', 'HI', 0, 'public');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `profile_ID` int(255) NOT NULL,
  `ProfileimagePath` varchar(255) DEFAULT NULL,
  `userid` varchar(20) NOT NULL,
  `coverPhoto` varchar(255) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `login_date` datetime DEFAULT NULL,
  `online` tinyint(1) NOT NULL DEFAULT 0,
  `nickname` varchar(255) DEFAULT NULL,
  `bio` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`profile_ID`, `ProfileimagePath`, `userid`, `coverPhoto`, `Address`, `login_date`, `online`, `nickname`, `bio`) VALUES
(2, '1751727446_68693d568d39d.jpg', '2321067', '1751815265_686a9461083cf.jpg', NULL, NULL, 0, '', 0),
(3, '1751725824_68693700642ee.jpg', '2321069', '1751725953_68693781c8e23.jpg', NULL, NULL, 0, '', 0);

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
(94, '2312073', 4, 'Wow'),
(95, '2321067', 11, 'Wow'),
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
(114, '2321067', 19, 'Wow'),
(115, '2321067', 18, 'Like'),
(117, '2321067', 17, 'Haha'),
(121, '2321067', 14, 'Love'),
(123, '2321067', 27, 'Sad'),
(124, '2321067', 2, 'Wow'),
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
(140, '2321067', 30, 'Love'),
(141, '2321069', 5, 'Love'),
(142, '2321069', 31, 'Sad'),
(143, '2321069', 30, 'Haha'),
(144, '2312073', 32, 'Haha'),
(145, '2321067', 32, 'Sad'),
(146, '2312073', 35, 'Wow'),
(147, '2321067', 39, 'Wow'),
(148, '2321067', 38, 'Sad'),
(149, '2321067', 36, 'Wow'),
(150, '2312073', 40, 'Love'),
(151, '2312073', 38, 'Love'),
(152, '2321067', 35, 'Haha'),
(153, '2312073', 33, 'Haha'),
(155, '2312073', 39, 'Wow'),
(156, '2321067', 31, 'Love'),
(157, '2321067', 41, 'Angry'),
(158, '2321067', 40, 'Wow'),
(159, '2312073', 41, 'Haha'),
(160, '2312073', 27, 'Haha'),
(162, '2312073', 22, 'Wow'),
(163, '2312073', 19, 'Haha'),
(166, '2321069', 11, 'Like'),
(167, '2321067', 34, 'Sad'),
(168, '2321067', 33, 'Wow'),
(169, '2321069', 41, 'Haha'),
(170, '2321067', 51, 'Wow'),
(171, '2321067', 62, 'Haha'),
(172, '2321067', 53, 'Wow'),
(173, '2321067', 44, 'Wow');

-- --------------------------------------------------------

--
-- Table structure for table `reply`
--

CREATE TABLE `reply` (
  `replyID` varchar(255) NOT NULL,
  `replyUserID` varchar(20) NOT NULL,
  `content` varchar(100) NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Parent_ReplyID` varchar(255) DEFAULT NULL,
  `Parent_commentID` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reply`
--

INSERT INTO `reply` (`replyID`, `replyUserID`, `content`, `Date`, `Parent_ReplyID`, `Parent_commentID`) VALUES
('R1', '2321069', '@San Min Htik HI', '2025-07-06 23:40:28', NULL, 'C3'),
('R2', '2321069', '@San Min Htik HI', '2025-07-06 23:47:49', 'R1', NULL),
('R3', '2321069', '@San Min Htik HI', '2025-07-06 23:47:59', 'R1', NULL),
('R4', '2321069', '@San Min Htik HI', '2025-07-06 23:48:08', 'R1', NULL),
('R5', '2321069', '@San Min Htik HI', '2025-07-06 23:57:18', NULL, 'C3'),
('R6', '2321069', '@San Min Htik HI', '2025-07-07 00:01:09', 'R5', NULL),
('R7', '2321069', '@San Min Htik HI test', '2025-07-07 00:16:33', 'R6', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `reportID` int(20) NOT NULL,
  `reportuserID` varchar(20) NOT NULL,
  `postID` int(20) NOT NULL,
  `Message` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_type`
--

CREATE TABLE `report_type` (
  `report_typeID` int(20) NOT NULL,
  `reportID` int(20) NOT NULL,
  `report_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savepost`
--

CREATE TABLE `savepost` (
  `saveID` int(20) NOT NULL,
  `postID` int(20) NOT NULL,
  `userID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savepost`
--

INSERT INTO `savepost` (`saveID`, `postID`, `userID`) VALUES
(1, 3, '2312073'),
(2, 19, '2321069'),
(7, 40, '2321069'),
(8, 35, '2321069'),
(9, 53, '2321067'),
(10, 38, '2321067'),
(11, 51, '2321067');

-- --------------------------------------------------------

--
-- Table structure for table `story`
--

CREATE TABLE `story` (
  `story_ID` int(11) NOT NULL,
  `userid` varchar(20) NOT NULL,
  `imagePath` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `story`
--

INSERT INTO `story` (`story_ID`, `userid`, `imagePath`, `content`, `time`, `status`) VALUES
(1, '2321067', '1750405589_685511d577d4b.jpg', 'test', '2025-06-27 09:46:29', 1),
(2, '2321067', '1750405759_6855127f0d925.png', 'test', '2025-06-27 09:49:19', 1),
(3, '2321067', '20250622_200341000_iOS.png', 'Hello', '2025-06-20 09:54:08', 1),
(4, '2321067', '20250618_094138000_iOS.png', 'Hellrdftgyhujkledrftgyhjkldrfgyhujiklrfgyhujikldrftgyhujikolhjkl;', '2025-06-25 08:39:38', 1),
(5, '2312073', 'Screenshot 2025-06-16 023048.png', NULL, '2025-06-26 23:48:46', 0);

-- --------------------------------------------------------

--
-- Table structure for table `story_views`
--

CREATE TABLE `story_views` (
  `id` int(11) NOT NULL,
  `story_owner_id` varchar(20) NOT NULL,
  `story_id` int(11) NOT NULL,
  `viewer_id` varchar(20) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reaction_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `story_views`
--

INSERT INTO `story_views` (`id`, `story_owner_id`, `story_id`, `viewer_id`, `viewed_at`, `reaction_type`) VALUES
(22, '2312073', 5, '2321067', '2025-06-27 08:01:05', NULL),
(24, '2321067', 2, '2321067', '2025-06-27 08:01:07', NULL),
(26, '2321067', 1, '2321067', '2025-06-27 08:01:06', NULL);

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
  `Batch` varchar(20) NOT NULL,
  `userType` varchar(10) DEFAULT NULL,
  `approve` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `name`, `email`, `password`, `gender`, `birthdate`, `Batch`, `userType`, `approve`) VALUES
('2312073', 'Myo Aung', 'myo202aung202@gmail.com', '$2y$10$FMZ9Gn/qdQ4UxwnuPH2K7.uiDpN5ILEgeqAPb5qFhFxrYggxQ.EQW', 'male', '2002-02-16', 'bt11', 'student', 1),
('2321067', 'Thein Than Zaw', 'sanminhtike9289@gmail.com', '$2y$10$oGOatOAszg7RuxAzzXnotepfIXZVZozGQN5GLZryrRv0A1gdlpW8G', 'male', '2025-05-01', 'bt11', 'student', 1),
('2321069', 'San Min Htik', 'theinthanzaw2023@outlook.com', '$2y$10$rjATF4b8mquiMGG9nhUE..lffF46eQSxijDMaxp6OLJFrguWJYVRi', 'male', '2000-03-15', 'bt11', 'admin', 1),
('99999', 'AI', 'gemini@gamil.com', '11111111', '', NULL, '', NULL, 0),
('test1', 'test1', 'test1@gmail.com', '$2y$10$XxJPEMnLDVjCwbNqf0W6geJbesZCyY8mKnEJMGM4D1iuRoTWzM89C', 'male', '2025-06-05', 'bt11', NULL, 1),
('test2', 'test2', 'test2@gmail.com', '$2y$10$yaOMc5SiT/y/mHDuBQMN.OpaFf9UEUkQPDBHxof8t5h/ZgMEqEBv.', 'male', '2025-06-05', 'bt11', 'admin', 1),
('test3', 'test3', 'test3@gmail.com', '$2y$10$N.PAQWbaTRTJAeVxHetYiutGc1UMXV0789otCfD2/MLfqphdahYr6', 'male', '2025-06-07', 'bt11', 'admin', 1),
('test4', 'test4', 'test4@gmail.com', '$2y$10$qWrtkVy29ri0hXgK/W0BNeYyFydBYMznmB/mibLJHBVonQZJD2pni', 'male', '2025-06-14', 'bt11', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `postID` (`postID`),
  ADD KEY `userID` (`userID`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

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
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profile_ID`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `reaction`
--
ALTER TABLE `reaction`
  ADD PRIMARY KEY (`reactionID`),
  ADD KEY `postID` (`postID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`replyID`),
  ADD KEY `replyUserID` (`replyUserID`),
  ADD KEY `Parent_commentID` (`Parent_commentID`),
  ADD KEY `Parent_ReplyID` (`Parent_ReplyID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`reportID`),
  ADD KEY `reportuserID` (`reportuserID`),
  ADD KEY `postID` (`postID`);

--
-- Indexes for table `report_type`
--
ALTER TABLE `report_type`
  ADD PRIMARY KEY (`report_typeID`),
  ADD KEY `reportID` (`reportID`);

--
-- Indexes for table `savepost`
--
ALTER TABLE `savepost`
  ADD PRIMARY KEY (`saveID`),
  ADD KEY `postID` (`postID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `story`
--
ALTER TABLE `story`
  ADD PRIMARY KEY (`story_ID`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `story_views`
--
ALTER TABLE `story_views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `story_owner_id` (`story_owner_id`,`story_id`,`viewer_id`),
  ADD KEY `story_id` (`story_id`),
  ADD KEY `viewer_id` (`viewer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hidden_posts`
--
ALTER TABLE `hidden_posts`
  MODIFY `hidden_post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `imgID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `postID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profile_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reaction`
--
ALTER TABLE `reaction`
  MODIFY `reactionID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `reportID` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_type`
--
ALTER TABLE `report_type`
  MODIFY `report_typeID` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `savepost`
--
ALTER TABLE `savepost`
  MODIFY `saveID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `story`
--
ALTER TABLE `story`
  MODIFY `story_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `story_views`
--
ALTER TABLE `story_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userid`);

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
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`userid`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`senderID`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`reciverID`) REFERENCES `users` (`userid`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userid`);

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `reaction`
--
ALTER TABLE `reaction`
  ADD CONSTRAINT `reaction_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`),
  ADD CONSTRAINT `reaction_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userid`);

--
-- Constraints for table `reply`
--
ALTER TABLE `reply`
  ADD CONSTRAINT `reply_ibfk_1` FOREIGN KEY (`replyUserID`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `reply_ibfk_2` FOREIGN KEY (`Parent_commentID`) REFERENCES `comment` (`commentID`),
  ADD CONSTRAINT `reply_ibfk_3` FOREIGN KEY (`Parent_ReplyID`) REFERENCES `reply` (`replyID`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`reportuserID`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`);

--
-- Constraints for table `report_type`
--
ALTER TABLE `report_type`
  ADD CONSTRAINT `report_type_ibfk_1` FOREIGN KEY (`reportID`) REFERENCES `report` (`reportID`);

--
-- Constraints for table `savepost`
--
ALTER TABLE `savepost`
  ADD CONSTRAINT `savepost_ibfk_1` FOREIGN KEY (`postID`) REFERENCES `post` (`postID`),
  ADD CONSTRAINT `savepost_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userid`);

--
-- Constraints for table `story`
--
ALTER TABLE `story`
  ADD CONSTRAINT `story_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `story_views`
--
ALTER TABLE `story_views`
  ADD CONSTRAINT `story_views_ibfk_1` FOREIGN KEY (`story_id`) REFERENCES `story` (`story_ID`),
  ADD CONSTRAINT `story_views_ibfk_2` FOREIGN KEY (`story_owner_id`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `story_views_ibfk_3` FOREIGN KEY (`viewer_id`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
