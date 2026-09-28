-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 06:30 AM
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
-- Database: `admin1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_tb`
--

CREATE TABLE `admin_tb` (
  `tbl_user_id` int(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `is_online` tinyint(255) NOT NULL,
  `score` int(11) DEFAULT 0,
  `coins` decimal(10,2) DEFAULT 0.00,
  `year_id` int(11) DEFAULT NULL,
  `sec_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_tb`
--

INSERT INTO `admin_tb` (`tbl_user_id`, `password`, `username`, `first_name`, `last_name`, `contact_number`, `email`, `profile_picture`, `is_online`, `score`, `coins`, `year_id`, `sec_id`) VALUES
(14, '$2y$10$av0uVMH5LC0iQI76RWafdulf46nMlv3BXODGadMo4PPSfwtoOq.M.', '112233445', '112233445', '112233445', '112233445', 'dsada@dassawda', '1743667167_Screenshot (1).png', 0, 323, 3132.00, 3, 19),
(16, '$2y$10$IAoLDYikwDQwo3eHlEIyJuKn7iPYUFzsBqCFgwb1bK0H7Vz4FpOnq', 'meeee', 'mine', 'is', '112233445', 'meeee@me', '1743696067_Screenshot (1).png', 0, 102, 0.00, 4, 19),
(17, '$2y$10$P5fnhxw30GaLnHIImFOCKeaOSCWag10XyhvxapjbEiuAc11B8EeF.', 'meeee2', 'joshua', 'garica', '121321321321', 'dadwdwadwa@dsads', '1743698236_black.jpg', 0, 321, 95264.00, 3, 17),
(19, '$2y$10$vWFYrGW9XHengmfu9nn3muA4m.azuU0uuur8L/7o2x8FqMAs/elom', 'jogar3n13213123', '1223', '1112222', '2312312321', '22222222@dfsf', '1744034571_me.jpg', 0, 342, 0.50, 1, 19),
(29, '$2y$10$/GlckSyzkh7KfwtXNfhmPed0tY/Z59cKklMFCTIkJ10OPIbArli1O', 'jjmode3dseson6', 'Wenzel Joshua', 'S. Garcia', '092343164111', '2021-0100-H', '1744041249_me.jpg', 0, 626, 99878982.85, 4, 18),
(30, '$2y$10$c509QUVPFMrZbVncSqWSDuK44osmtD69Lzn1W1Ld3NdJyzfu7khoe', 'Vargas', 'vince', 'Vargas', '0912345678', 'vargasvinc3211@gmail.com', '1744273596_Screenshot 2025-01-02 231744.png', 1, 139, 298375.00, 1, 19),
(31, '$2y$10$ST97AFhI7bSm1vu6PPtNtemZfIbOs3IZ6qqVMRtXgJKUXK6DoNXbq', '11111111', 'dsadsadsad', 'saddasdasdsadsa', '342423423423423', 'dsadsadsa@gfdg', '1745122238_Screenshot 2025-01-02 231744.png', 1, 10, 5.50, 3, 19),
(32, '$2y$10$zRNqImoWYQdJsAy/iU1e0.6cgnJoE2eLhnAx3mLVxE5GM6kIvNHzy', 'jjmode3dseson6dsadsadasds', 'dsadasd', 'sdasdsadsadsdsad', 'dsadasdsad', '2021-0095-H', '1745151028_Screenshot (2).png', 0, 0, 0.00, 2, 18);

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#FFFFFF',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `title`, `description`, `price`, `color`, `created_at`, `expiration_date`) VALUES
(38, 'Congratulations! Your Earning Journey Begins! ', 'Unlock the potential to earn real money! Purchase this and see your rewards grow. (Symbolic value: ?1)', 10, '#CD7F32', '2025-04-21 03:59:12', '2025-04-25'),
(40, 'Congratulations! Multiply Your Earnings! ', 'Unlock even greater potential to earn real money! Purchase this and watch your rewards grow. (Symbolic value: ?5)', 50, '#E5E4E2', '2025-04-21 04:02:12', NULL),
(41, 'Boost Your Earnings Now!', 'Ready to accelerate your real money earnings? Get this and witness your initial potential with ?5!', 50, '#E5E4E2', '2025-04-21 04:02:50', NULL),
(42, 'Great Start! Grow Your Earnings! ', 'Begin your journey to earn real money! Purchase this and see your rewards take shape. (Symbolic value: ?3)', 30, '#FFD700', '2025-04-21 04:03:43', NULL),
(43, ' Double Your Potential! Unlock ?20!', 'Get ready to see your real money earnings soar! Purchase this and witness a significant initial value of ?20!', 200, '#50C878', '2025-04-21 04:04:46', NULL),
(44, 'Unleash Your Earning Power! Claim Your ?50!', ' Get ready for a significant leap in your real money earnings! Purchase this today and witness a substantial initial value of ?50!', 550, '#B9F2FF', '2025-04-21 04:05:43', '2025-04-26'),
(45, 'dsadas', 'sdsada', 23, '#FFFFFF', '2025-04-23 02:10:59', '2025-04-23'),
(46, 'dsadsa', 'sadsadasd', 32, '#FFFFFF', '2025-04-23 02:13:29', '2025-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(255) NOT NULL,
  `cat_title` varchar(255) NOT NULL,
  `cat_dis` varchar(500) NOT NULL,
  `diff_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `cat_title`, `cat_dis`, `diff_id`) VALUES
(44, 'HTML', 'Easy Learning with HTML \"Try it Yourself\"', 1),
(45, 'HTML', 'Easy Learning with HTML \"Try it Yourself\"', 5),
(46, 'CSS', 'Learn CSS', 1),
(47, 'CSS', 'Learn CSS', 5),
(48, 'CSS', 'Learn CSS', 6),
(49, 'JS', 'JavaScript Tutorial', 6);

-- --------------------------------------------------------

--
-- Table structure for table `code_ex`
--

CREATE TABLE `code_ex` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `code_ex`
--

INSERT INTO `code_ex` (`id`, `title`, `description`, `category_id`, `part_id`, `created_at`) VALUES
(1, 'Exercises 1', 'Create a Button', 44, 67, '2025-06-07 08:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `diff`
--

CREATE TABLE `diff` (
  `id` int(255) NOT NULL,
  `select_diff` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `diff`
--

INSERT INTO `diff` (`id`, `select_diff`) VALUES
(1, 'Easy'),
(5, 'Medium'),
(6, 'Hard');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `year_level` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `year_level`) VALUES
(1, '1st'),
(2, '2nd'),
(3, '3rd'),
(4, '4th');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` varchar(10) DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `status`, `created_at`, `deleted`) VALUES
(1, 12, 'Your reported quiz (ID: 34) has been reset. You can retake it now.', 'read', '2025-03-31 09:03:58', 1),
(2, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-03-31 09:04:50', 1),
(3, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-03-31 09:04:52', 1),
(4, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-03-31 09:10:20', 1),
(5, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-03-31 09:10:29', 1),
(6, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-03-31 09:17:57', 1),
(7, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-03-31 09:17:59', 1),
(8, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-03-31 09:17:59', 1),
(9, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-03-31 09:18:01', 1),
(10, 12, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-03-31 09:19:03', 1),
(11, 12, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-03-31 09:19:15', 1),
(12, 12, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-03-31 09:29:40', 1),
(13, 12, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-03-31 09:29:41', 1),
(14, 12, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-03-31 09:29:42', 1),
(15, 12, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-03-31 09:29:43', 1),
(16, 12, 'Your reported quiz (ID: 28) has been reset. You can retake it now.', 'read', '2025-03-31 09:30:39', 0),
(17, 12, 'Your reported quiz (ID: 28) has been reset. You can retake it now.', 'read', '2025-03-31 09:42:24', 0),
(18, 12, 'Your reported quiz (ID: 30) has been reset. You can retake it now.', 'read', '2025-03-31 09:43:56', 1),
(19, 12, 'Your reported quiz (ID: 28) has been reset. You can retake it now.', 'read', '2025-03-31 12:50:58', 0),
(20, 12, 'Your reported quiz (ID: 28) has been reset. You can retake it now.', 'read', '2025-03-31 12:52:36', 0),
(21, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-04-02 03:29:43', 0),
(22, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-04-03 07:38:33', 0),
(23, 12, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-04-03 07:39:38', 0),
(24, 17, 'Your reported quiz (ID: 31) has been reset. You can retake it now.', 'read', '2025-04-05 07:01:31', 0),
(25, 17, 'Your reported quiz (ID: 37) has been reset. You can retake it now.', 'read', '2025-04-07 05:59:25', 0),
(26, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:21:46', 1),
(27, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:25:21', 1),
(28, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:30:41', 1),
(29, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:30:46', 1),
(30, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:38:22', 1),
(31, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:38:23', 1),
(32, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:38:23', 1),
(33, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:38:23', 1),
(34, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:38:24', 1),
(35, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:38:24', 1),
(36, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:38:33', 1),
(37, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:38:37', 1),
(38, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:45:42', 1),
(39, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:45:42', 1),
(40, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:45:43', 1),
(41, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:45:44', 1),
(42, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:45:44', 1),
(43, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:45:49', 1),
(44, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:47:30', 1),
(45, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:47:31', 1),
(46, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:47:32', 1),
(47, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:47:32', 1),
(48, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:47:33', 1),
(49, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:47:33', 1),
(50, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 14:47:33', 1),
(51, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:27:14', 1),
(52, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:48:18', 1),
(53, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:49:06', 1),
(54, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:49:19', 1),
(55, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:49:23', 1),
(56, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:49:27', 1),
(57, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:50:03', 1),
(58, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:52:35', 1),
(59, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:52:39', 1),
(60, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:55:27', 1),
(61, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:56:01', 1),
(62, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:58:04', 1),
(63, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:58:37', 1),
(64, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 15:59:57', 1),
(65, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 16:00:02', 1),
(66, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 16:00:03', 1),
(67, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 16:00:03', 1),
(68, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 16:00:24', 1),
(69, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 16:02:17', 1),
(70, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-09 16:02:23', 1),
(71, 29, 'Your reported quiz (ID: 38) has been reset. You can retake it now.', 'read', '2025-04-10 08:20:20', 1),
(72, 29, 'Your reported quiz (ID: 38) has been reset. You can retake it now.', 'read', '2025-04-10 14:07:14', 1),
(73, 29, 'Your reported quiz (ID: 38) has been reset. You can retake it now.', 'read', '2025-04-10 14:07:16', 1),
(74, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:12:22', 1),
(75, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:12:48', 1),
(76, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:13:24', 1),
(77, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:22:42', 1),
(78, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:22:43', 1),
(79, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:22:44', 1),
(80, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:22:44', 1),
(81, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:22:59', 1),
(82, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:23:02', 1),
(83, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:36:45', 1),
(84, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:41:00', 1),
(85, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 14:56:01', 1),
(86, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 15:01:29', 1),
(87, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 15:02:02', 1),
(88, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 15:02:47', 1),
(89, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 15:02:48', 1),
(90, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 15:02:49', 1),
(91, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 15:02:49', 1),
(92, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 15:03:23', 1),
(93, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-10 15:07:11', 1),
(94, 29, 'Your reported quiz (ID: 27) has been reset. You can retake it now.', 'read', '2025-04-12 06:47:27', 1),
(95, 30, 'Your reported quiz (ID: 43) has been reset. You can retake it now.', 'unread', '2025-04-13 16:03:49', 0),
(96, 29, 'Your reported quiz (ID: 45) has been reset. You can retake it now.', 'read', '2025-04-17 18:03:47', 1),
(97, 29, 'Your reported quiz (ID: 47) has been reset. You can retake it now.', 'read', '2025-04-18 14:16:28', 1),
(98, 29, 'Your reported quiz (ID: 48) has been reset. You can retake it now.', 'read', '2025-04-19 15:49:51', 1),
(99, 29, 'Your reported quiz (ID: 47) has been reset. You can retake it now.', 'read', '2025-04-19 15:50:34', 1),
(100, 31, 'Your reported quiz (ID: 48) has been reset. You can retake it now.', 'read', '2025-04-20 04:29:52', 1),
(101, 29, 'Your reported quiz (ID: 47) has been reset. You can retake it now.', 'read', '2025-04-20 06:49:34', 1),
(102, 29, 'Your reported quiz (ID: 50) has been reset. You can retake it now.', 'read', '2025-04-20 07:05:56', 1),
(103, 29, 'Your reported quiz (ID: 50) has been reset. You can retake it now.', 'read', '2025-04-20 07:13:10', 1),
(104, 29, 'Your reported quiz (ID: 50) has been reset. You can retake it now.', 'read', '2025-04-20 07:20:50', 1),
(105, 29, 'Your reported quiz (ID: 50) has been reset. You can retake it now.', 'read', '2025-04-20 07:27:26', 1),
(106, 29, 'Your reported quiz (ID: 47) has been reset. You can retake it now.', 'read', '2025-04-20 07:51:14', 1),
(107, 29, 'Your reported quiz (ID: 47) has been reset. You can retake it now.', 'read', '2025-04-20 07:51:33', 1),
(108, 31, 'Your reported quiz (ID: 49) has been reset. You can retake it now.', 'read', '2025-04-20 08:00:21', 1),
(109, 31, 'Your reported quiz (ID: 50) has been reset. You can retake it now.', 'read', '2025-04-20 08:01:07', 1),
(110, 31, 'Your reported quiz (ID: 50) has been reset. You can retake it now.', 'read', '2025-04-20 08:14:57', 1),
(111, 29, 'Your reported quiz (ID: 50) has been reset. You can retake it now.', 'read', '2025-04-20 08:15:26', 1),
(112, 29, 'Your reported quiz (ID: 49) has been reset. You can retake it now.', 'read', '2025-04-20 08:45:50', 1),
(113, 29, 'Your reported quiz (ID: 49) has been reset. You can retake it now.', 'read', '2025-04-20 08:46:34', 1),
(114, 29, 'Your reported quiz (ID: 48) has been reset. You can retake it now.', 'read', '2025-04-20 09:11:32', 1),
(115, 29, 'Your reported quiz (ID: 49) has been reset. You can retake it now.', 'read', '2025-04-20 09:14:05', 1),
(116, 29, 'Your reported quiz (ID: 55) has been reset. You can retake it now.', 'read', '2025-04-20 13:41:10', 1),
(117, 29, 'Your reported quiz (ID: 61) has been reset. You can retake it now.', 'read', '2025-04-21 06:27:13', 1),
(118, 29, 'Your reported quiz (ID: 60) has been reset. You can retake it now.', 'read', '2025-04-21 06:27:18', 1),
(119, 29, 'Your reported quiz (ID: 62) has been reset. You can retake it now.', 'read', '2025-04-23 08:32:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(255) NOT NULL,
  `question_id` int(255) NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_correct` tinyint(255) NOT NULL,
  `score` int(11) DEFAULT 0,
  `coins` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `question_id`, `option_text`, `is_correct`, `score`, `coins`) VALUES
(1, 1, 'Personal Home Page', 1, 0, 0.00),
(2, 1, 'Programming Hypertext Preprocessor', 0, 0, 0.00),
(3, 1, 'Pre Hypertext Processor', 0, 0, 0.00),
(4, 1, 'Private Home Page', 0, 0, 0.00),
(5, 0, 'Hypertext Markup Language', 1, 0, 0.00),
(6, 0, 'hyper text lang ', 0, 0, 0.00),
(7, 0, 'hypertext mine lungs', 0, 0, 0.00),
(8, 0, 'hyper lang', 0, 0, 0.00),
(9, 0, 'Hypertext Markup Language', 1, 0, 0.00),
(10, 0, 'hyper text lang ', 0, 0, 0.00),
(11, 0, 'hypertext mine lungs', 0, 0, 0.00),
(12, 0, 'hyper lang', 0, 0, 0.00),
(13, 0, 'dasdsa', 1, 0, 0.00),
(14, 0, 'sadas', 0, 0, 0.00),
(15, 0, 'saddsadsa', 0, 0, 0.00),
(16, 0, 'dsadsad', 0, 0, 0.00),
(17, 0, 'dadsads', 0, 0, 0.00),
(18, 0, 'dsadsa', 1, 0, 0.00),
(19, 0, 'dsadasda', 0, 0, 0.00),
(20, 0, 'dsadssa', 0, 0, 0.00),
(21, 0, 'dasdasds', 0, 0, 0.00),
(22, 0, 'dqwdqd', 1, 0, 0.00),
(23, 0, 'dqwdqw', 0, 0, 0.00),
(24, 0, 'dasdsadas', 0, 0, 0.00),
(26, 19, 'gfdgdf', 1, 0, 0.00),
(27, 19, 'gdgdf', 0, 0, 0.00),
(28, 19, 'gdfgdfgdf', 0, 0, 0.00),
(29, 19, 'gfdgdfgdf', 0, 0, 0.00),
(30, 20, 'dasdsa', 1, 0, 0.00),
(31, 20, 'dsdsad', 0, 0, 0.00),
(32, 20, 'dsadsa', 0, 0, 0.00),
(33, 20, 'dsadsadas', 0, 0, 0.00),
(34, 1, 'Hyper Text Markup Language', 1, 0, 0.00),
(35, 1, 'High Tech Machine Learning', 0, 0, 0.00),
(36, 1, 'Hyperlinks and Text Management Language', 0, 0, 0.00),
(41, 21, 'dqwdqwdqwd', 0, 0, 0.00),
(42, 21, 'dqwwwqw', 1, 0, 0.00),
(43, 21, 'dwqdqwd', 0, 0, 0.00),
(44, 21, 'qwdwqdqwdqw', 0, 0, 0.00),
(45, 22, 'Hypertext Markup Language', 1, 0, 0.00),
(46, 22, 'hyper text lang ', 0, 0, 0.00),
(47, 22, 'hypertext mine lungs', 0, 0, 0.00),
(48, 22, 'hyper lang', 0, 0, 0.00),
(49, 23, 'sdsadsa', 1, 0, 0.00),
(50, 23, 'hyper text lang ', 0, 0, 0.00),
(51, 23, 'hypertext mine lungs', 0, 0, 0.00),
(52, 23, 'hyper lang', 0, 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` int(255) NOT NULL,
  `part_title` varchar(255) NOT NULL,
  `part_dis` varchar(500) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `progress` int(11) DEFAULT 0,
  `diff_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `part_title`, `part_dis`, `price`, `progress`, `diff_id`) VALUES
(67, 'Part 1:', 'HTML', 0.00, 0, 1),
(68, 'Part 2:', 'HTML', 0.00, 0, 1),
(69, 'Part 3:', 'HTML', 0.00, 0, 1),
(70, 'Part 4:', 'HTML', 0.00, 0, 1),
(71, 'Part 5:', 'HTML', 0.00, 0, 1),
(72, 'Part 6:', 'HTML', 0.00, 0, 1),
(73, 'Part 7:', 'HTML', 0.05, 0, 1),
(74, 'Part 8:', 'HTMl', 0.05, 0, 1),
(75, 'Part 9:', 'HTML', 0.05, 0, 1),
(76, 'Part 10:', 'HTML', 0.05, 0, 1),
(77, 'Part 1:', 'HTML', 0.50, 0, 5),
(78, 'Part 2:', 'HTML', 0.50, 0, 5),
(79, 'Part 3:', 'HTML', 0.50, 0, 5),
(80, 'Part 4:', 'HTML', 0.50, 0, 5),
(81, 'Part 5:', 'HTML', 0.50, 0, 5),
(82, 'Part 6:', 'HTML', 0.50, 0, 5),
(83, 'Part 7:', 'HTML', 0.50, 0, 5),
(87, 'Part 1:', 'CSS', 0.00, 0, 1),
(88, 'Part 2:', 'CSS', 0.00, 0, 1),
(89, 'Part 3:', 'CSS', 0.00, 0, 1),
(90, 'Part 4:', 'CSS', 0.00, 0, 1),
(91, 'Part 5:', 'CSS', 0.00, 0, 1),
(92, 'Part 1:', 'CSS', 0.50, 0, 5),
(93, 'Part 2:', 'CSS', 0.50, 0, 5),
(94, 'Part 3:', 'CSS', 0.50, 0, 5),
(95, 'Part 4:', 'CSS', 0.50, 0, 5),
(96, 'Part 5:', 'CSS', 0.50, 0, 5),
(97, 'Part 1:', 'CSS', 3.00, 0, 6),
(98, 'Part 2:', 'CSS', 3.00, 0, 6),
(99, 'Part 3:', 'CSS', 3.00, 0, 6),
(100, 'Part 4:', 'CSS', 3.00, 0, 6),
(101, 'Part 5:', 'CSS', 3.00, 0, 6),
(102, 'Part 1:', 'JS', 3.00, 0, 6),
(103, 'Part 2:', 'JS', 3.00, 0, 6),
(104, 'Part 3:', 'JS', 3.00, 0, 6),
(105, 'Part 4:', 'JS', 3.00, 0, 6),
(106, 'Part 5:', 'JS', 3.00, 0, 6),
(107, 'Part 6:', 'HTML', 0.00, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `progress`
--

CREATE TABLE `progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `completed_tutorials` int(11) NOT NULL DEFAULT 0,
  `total_tutorials` int(11) NOT NULL,
  `progress_percentage` int(11) NOT NULL DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `progress`
--

INSERT INTO `progress` (`id`, `user_id`, `part_id`, `completed_tutorials`, `total_tutorials`, `progress_percentage`, `last_updated`) VALUES
(1, 12, 7, 35, 3, 100, '2025-04-21 15:15:17'),
(2, 12, 8, 2, 1, 100, '2025-04-21 15:15:17'),
(3, 12, 9, 1, 1, 100, '2025-04-21 15:15:17'),
(4, 12, 12, 2, 1, 100, '2025-04-21 15:15:17'),
(5, 17, 8, 7, 2, 100, '2025-04-21 15:15:17'),
(6, 17, 7, 5, 4, 100, '2025-04-21 15:15:17'),
(7, 17, 11, 3, 2, 100, '2025-04-21 15:15:17'),
(8, 17, 9, 3, 2, 100, '2025-04-21 15:15:17'),
(9, 17, 10, 2, 2, 100, '2025-04-21 15:15:17'),
(10, 17, 13, 3, 1, 100, '2025-04-21 15:15:17'),
(11, 17, 15, 1, 1, 100, '2025-04-21 15:15:17'),
(12, 17, 16, 2, 2, 100, '2025-04-21 15:15:17'),
(13, 17, 18, 1, 1, 100, '2025-04-21 15:15:17'),
(14, 17, 17, 2, 2, 100, '2025-04-21 15:15:17'),
(15, 17, 27, 3, 0, 100, '2025-04-21 15:15:17'),
(16, 17, 19, 2, 1, 100, '2025-04-21 15:15:17'),
(17, 17, 21, 1, 1, 100, '2025-04-21 15:15:17'),
(18, 17, 22, 2, 1, 100, '2025-04-21 15:15:17'),
(19, 17, 23, 1, 1, 100, '2025-04-21 15:15:17'),
(20, 17, 24, 1, 1, 100, '2025-04-21 15:15:17'),
(21, 17, 25, 3, 3, 100, '2025-04-21 15:15:17'),
(22, 17, 26, 3, 2, 100, '2025-04-21 15:15:17'),
(26, 29, 21, 7, 1, 100, '2025-04-21 15:15:17'),
(27, 29, 23, 1, 1, 100, '2025-04-21 15:15:17'),
(28, 29, 24, 2, 1, 100, '2025-04-21 15:15:17'),
(29, 29, 25, 3, 3, 100, '2025-04-21 15:15:17'),
(30, 19, 28, 1, 1, 100, '2025-04-21 15:15:17'),
(31, 19, 29, 1, 1, 100, '2025-04-21 15:15:17'),
(32, 29, 22, 2, 2, 100, '2025-04-21 15:15:17'),
(33, 29, 28, 1, 1, 100, '2025-04-21 15:15:17'),
(34, 29, 29, 1, 1, 100, '2025-04-21 15:15:17'),
(36, 19, 27, 0, 0, 100, '2025-04-21 15:15:17'),
(48, 30, 21, 2, 2, 100, '2025-04-21 15:15:17'),
(49, 30, 38, 0, 0, 100, '2025-04-21 15:15:17'),
(58, 29, 38, 5, 0, 100, '2025-04-21 15:15:17'),
(64, 30, 27, 0, 0, 100, '2025-04-21 15:15:17'),
(65, 30, 38, 0, 0, 100, '2025-04-21 15:15:17'),
(67, 29, 27, 0, 0, 100, '2025-04-21 15:15:17'),
(68, 29, 26, 2, 2, 100, '2025-04-21 15:15:17'),
(69, 30, 39, 0, 0, 100, '2025-04-21 15:15:17'),
(70, 30, 26, 1, 2, 50, '2025-04-21 15:15:17'),
(71, 30, 24, 1, 3, 33, '2025-04-21 15:15:17'),
(72, 29, 33, 1, 1, 100, '2025-04-21 15:15:17'),
(73, 29, 46, 3, 3, 100, '2025-04-21 15:15:17'),
(74, 29, 32, 4, 2, 100, '2025-04-21 15:15:17'),
(75, 29, 34, 2, 2, 100, '2025-04-21 15:15:17'),
(76, 29, 54, 2, 2, 100, '2025-04-21 15:15:17'),
(77, 29, 39, 1, 1, 100, '2025-04-21 15:15:17'),
(78, 29, 35, 2, 2, 100, '2025-04-21 15:15:17'),
(79, 29, 40, 2, 2, 100, '2025-04-21 15:15:17'),
(80, 29, 41, 3, 3, 100, '2025-04-21 15:15:17'),
(81, 29, 42, 1, 1, 100, '2025-04-21 15:15:17'),
(82, 29, 43, 2, 2, 100, '2025-04-21 15:15:17'),
(83, 29, 44, 2, 2, 100, '2025-04-21 15:15:17'),
(86, 29, 32, 2, 2, 100, '2025-04-21 15:15:17'),
(87, 29, 32, 2, 2, 100, '2025-04-21 15:15:17'),
(88, 29, 32, 2, 2, 100, '2025-04-21 15:15:17'),
(89, 29, 32, 2, 2, 100, '2025-04-21 15:15:17'),
(93, 29, 46, 0, 0, 100, '2025-04-21 15:15:17'),
(96, 29, 45, 0, 0, 100, '2025-04-21 15:15:17'),
(99, 29, 47, 0, 0, 100, '2025-04-21 15:15:17'),
(100, 29, 47, 0, 0, 100, '2025-04-21 15:15:17'),
(102, 29, 51, 2, 2, 100, '2025-04-21 15:15:17'),
(105, 31, 49, 2, 2, 100, '2025-04-21 15:15:17'),
(106, 31, 34, 2, 2, 100, '2025-04-21 15:15:17'),
(107, 31, 39, 1, 1, 100, '2025-04-21 15:15:17'),
(109, 29, 36, 1, 1, 100, '2025-04-21 15:15:17'),
(111, 29, 59, 1, 1, 100, '2025-04-21 15:15:17'),
(113, 29, 60, 6, 6, 100, '2025-04-21 15:15:17'),
(114, 29, 61, 4, 4, 100, '2025-04-21 15:15:17'),
(115, 29, 65, 4, 4, 100, '2025-04-21 15:15:17'),
(116, 29, 65, 0, 0, 100, '2025-04-22 15:52:51'),
(117, 29, 66, 0, 0, 100, '2025-04-22 15:56:19'),
(118, 29, 62, 0, 0, 100, '2025-04-23 08:32:26'),
(119, 29, 76, 0, 0, 100, '2025-04-24 04:09:25'),
(120, 29, 77, 0, 0, 100, '2025-04-25 05:00:00'),
(121, 29, 78, 0, 0, 100, '2025-05-10 14:07:14');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `purchased_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cleared` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `card_id`, `purchased_at`, `cleared`) VALUES
(1, 17, 16, '2025-04-05 08:28:36', 0),
(2, 17, 15, '2025-04-05 08:39:03', 0),
(3, 17, 14, '2025-04-05 09:31:10', 0),
(4, 17, 13, '2025-04-05 09:31:14', 0),
(5, 17, 12, '2025-04-05 09:33:27', 0),
(6, 17, 11, '2025-04-05 09:33:55', 0),
(7, 17, 17, '2025-04-05 09:38:49', 0),
(8, 12, 17, '2025-04-05 14:39:24', 0),
(9, 12, 16, '2025-04-05 14:39:27', 0),
(10, 17, 18, '2025-04-06 17:08:24', 0),
(11, 29, 18, '2025-04-07 16:25:48', 0),
(12, 29, 17, '2025-04-07 16:25:51', 0),
(13, 29, 16, '2025-04-07 16:25:54', 0),
(14, 29, 15, '2025-04-07 16:45:37', 0),
(15, 29, 14, '2025-04-09 13:52:49', 0),
(16, 29, 13, '2025-04-09 13:56:29', 0),
(17, 29, 12, '2025-04-09 13:59:17', 0),
(18, 29, 19, '2025-04-10 13:40:33', 0),
(19, 29, 11, '2025-04-10 13:49:09', 0),
(20, 30, 19, '2025-04-12 02:53:02', 0),
(21, 30, 18, '2025-04-12 02:53:15', 0),
(22, 30, 17, '2025-04-12 02:53:28', 0),
(23, 30, 16, '2025-04-13 16:05:18', 0),
(24, 29, 22, '2025-04-17 07:50:16', 0),
(25, 29, 21, '2025-04-17 07:50:26', 0),
(26, 29, 20, '2025-04-17 07:50:29', 0),
(27, 29, 26, '2025-04-17 09:14:38', 0),
(28, 29, 25, '2025-04-17 09:14:41', 0),
(29, 29, 27, '2025-04-17 09:26:48', 0),
(30, 29, 28, '2025-04-17 09:49:01', 0),
(31, 29, 29, '2025-04-17 15:19:10', 0),
(32, 29, 30, '2025-04-17 16:04:44', 0),
(33, 29, 36, '2025-04-19 15:59:25', 0),
(34, 29, 39, '2025-04-21 06:08:43', 0),
(35, 29, 38, '2025-04-21 14:23:16', 1),
(36, 29, 42, '2025-04-21 14:40:19', 1),
(37, 29, 41, '2025-04-21 14:48:35', 1),
(38, 31, 39, '2025-04-22 07:34:48', 0),
(39, 29, 40, '2025-04-23 04:46:44', 1),
(40, 29, 44, '2025-04-23 08:33:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(255) NOT NULL,
  `quiz_id` int(255) NOT NULL,
  `question_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`) VALUES
(1, 1, 'What does PHP stand for?'),
(2, 1, 'dsadsadsa'),
(3, 1, 'dsadsadsa'),
(4, 1, 'dsadsadsa'),
(5, 1, 'dsadad'),
(6, 2, 'dasdas'),
(7, 3, 'dasdsadas'),
(8, 3, 'dasdsadsa'),
(9, 3, 'sdadsdsa'),
(10, 3, 'dsadsa'),
(11, 3, 'dasdsa'),
(12, 0, 'eqweqweqw'),
(19, 17, 'dsadsadsadsa'),
(20, 18, 'dsadsad'),
(21, 19, 'dsadawqdqw'),
(22, 20, 'rtgrtrt'),
(23, 21, 'dsadsad');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(255) NOT NULL,
  `quiz_title` varchar(255) NOT NULL,
  `countdown` int(255) NOT NULL,
  `diff_id` int(255) NOT NULL,
  `category_id` int(255) NOT NULL,
  `part_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `quiz_title`, `countdown`, `diff_id`, `category_id`, `part_id`) VALUES
(27, 'begginerdsdas', 32, 5, 15, 24),
(28, 'dsadsa', 21, 1, 20, 25),
(38, 'HTML quiz', 15, 0, 16, 23),
(39, 'HTML quiz', 15, 0, 15, 28),
(40, 'dsadsa', 21, 0, 16, 28),
(41, 'dsadasd', 22, 0, 15, 24),
(42, 'dsdasdsa', 321, 0, 17, 21),
(43, 'dsadasd', 312321, 0, 19, 22),
(45, '2. Which CSS layout module is best for building a responsive grid of items?', 15, 0, 23, 32),
(46, 'dsadasdasd', 21, 0, 23, 33),
(47, 'dsadsadas', 212, 0, 26, 40),
(48, 'dsadasdsadsd', 21, 0, 26, 40),
(50, 'dsadasdasd', 12, 0, 35, 34),
(55, 'sadsad', 23, 0, 24, 35),
(56, 'dsadasd', 32, 0, 25, 36),
(57, 'dsadsadad', 32, 0, 25, 34),
(58, 'dsadsads', 2, 0, 26, 40),
(59, 'dsadsadsadsa', 323, 0, 29, 50),
(62, 'Question 1:', 15, 0, 37, 59),
(63, 'Question 2:', 15, 0, 37, 59),
(64, 'dsadsadad', 15, 0, 37, 60),
(65, 'dsadsadas', 333, 0, 37, 60),
(66, 'fdsfddsfdsf', 343, 0, 38, 61),
(76, 'Exercise 1', 15, 0, 44, 67),
(77, 'Exercise 2', 15, 0, 44, 67),
(78, 'Exercise 3', 15, 0, 44, 67),
(79, 'Exercise 4', 15, 0, 44, 67),
(80, 'Exercise 5', 15, 0, 44, 67),
(81, 'Exercise 1', 15, 0, 44, 68),
(82, 'Exercise 2', 15, 0, 44, 68),
(83, 'Exercise 3', 15, 0, 44, 68),
(84, 'Exercise 5', 15, 0, 44, 68),
(85, 'Exercise 1', 15, 0, 45, 77),
(86, 'Exercise 2', 15, 0, 45, 77),
(87, 'Exercise 3', 15, 0, 45, 77),
(88, 'Exercise 4', 15, 0, 45, 77),
(89, 'Exercise 5', 15, 0, 45, 78),
(90, 'Exercise 1', 15, 0, 46, 87),
(91, 'Exercise 2', 15, 0, 46, 87),
(92, 'Exercise 3', 15, 0, 46, 87),
(93, 'Exercise 4', 15, 0, 46, 87),
(94, 'Exercise 1', 15, 0, 46, 87),
(95, 'Exercise 1', 20, 0, 47, 92),
(96, 'Exercise 2', 20, 0, 47, 92),
(97, 'Exercise 3', 20, 0, 47, 92),
(98, 'Exercise 4', 20, 0, 47, 92),
(99, 'Exercise 5', 20, 0, 47, 92),
(100, 'What does JavaScript primarily run on?', 15, 0, 49, 102),
(101, 'How do you write a comment in JavaScript?', 15, 0, 49, 102);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `quiz_id` int(255) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `coins` decimal(10,2) NOT NULL DEFAULT 0.00,
  `correct_answers` int(11) NOT NULL DEFAULT 0,
  `attempt_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `user_id`, `quiz_id`, `score`, `coins`, `correct_answers`, `attempt_date`) VALUES
(8, 12, 29, 10, 0.50, 1, '2025-03-28 22:06:33'),
(12, 12, 32, 0, 0.00, 0, '2025-03-28 22:28:24'),
(13, 12, 33, 10, 0.50, 1, '2025-03-28 22:29:17'),
(19, 12, 27, 0, 0.00, 0, '2025-03-31 20:49:26'),
(21, 12, 30, 0, 0.00, 0, '2025-03-31 20:51:41'),
(23, 12, 35, 0, 0.00, 0, '2025-04-02 11:34:04'),
(24, 12, 31, 0, 0.00, 0, '2025-04-03 15:39:50'),
(26, 17, 27, 10, 0.50, 1, '2025-04-04 00:45:31'),
(27, 17, 29, 10, 0.50, 1, '2025-04-04 00:45:34'),
(28, 17, 31, 10, 0.50, 1, '2025-04-05 15:02:09'),
(29, 17, 36, 10, 0.50, 1, '2025-04-07 00:25:42'),
(30, 17, 35, 0, 0.00, 0, '2025-04-07 01:08:05'),
(32, 17, 37, 0, 0.00, 0, '2025-04-07 13:59:34'),
(34, 19, 27, 10, 0.50, 1, '2025-04-09 17:15:01'),
(44, 30, 38, 10, 0.50, 1, '2025-04-10 16:30:41'),
(51, 29, 38, 10, 0.50, 1, '2025-04-10 22:58:23'),
(57, 30, 27, 10, 0.50, 1, '2025-04-12 10:54:16'),
(58, 29, 27, 10, 0.50, 1, '2025-04-12 14:47:45'),
(59, 29, 39, 0, 0.00, 0, '2025-04-13 13:35:25'),
(60, 30, 39, 10, 0.50, 1, '2025-04-13 23:25:17'),
(62, 30, 43, 0, 0.00, 0, '2025-04-14 00:04:04'),
(63, 29, 44, 0, 0.00, 0, '2025-04-14 23:04:04'),
(67, 29, 46, 10, 0.50, 1, '2025-04-17 17:51:55'),
(68, 29, 45, 10, 0.50, 1, '2025-04-18 14:04:08'),
(72, 31, 47, 0, 0.00, 0, '2025-04-20 12:11:55'),
(82, 29, 47, 20, 1.00, 1, '2025-04-20 15:51:38'),
(83, 31, 49, 0, 0.00, 0, '2025-04-20 16:00:31'),
(87, 29, 48, 0, 0.00, 0, '2025-04-20 17:11:41'),
(92, 29, 63, 0, 0.00, 0, '2025-04-22 23:51:59'),
(93, 29, 65, 10, 0.50, 1, '2025-04-22 23:52:50'),
(94, 29, 64, 0, 0.00, 0, '2025-04-22 23:54:27'),
(95, 29, 66, 15, 1.25, 1, '2025-04-22 23:56:19'),
(96, 29, 62, 10, 0.50, 1, '2025-04-23 16:32:24'),
(97, 29, 76, 10, 0.50, 1, '2025-04-24 12:09:23'),
(98, 29, 77, 10, 0.50, 1, '2025-04-25 12:59:54'),
(99, 29, 78, 10, 0.50, 1, '2025-05-10 22:07:11'),
(100, 29, 79, 0, 0.00, 0, '2025-05-10 22:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_progress`
--

CREATE TABLE `quiz_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` int(11) DEFAULT 0,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quiz_progress`
--

INSERT INTO `quiz_progress` (`id`, `user_id`, `quiz_id`, `score`, `completed_at`) VALUES
(1, 12, 32, 0, '2025-03-30 14:08:22'),
(2, 12, 33, 0, '2025-03-30 14:08:26'),
(5, 12, 32, 10, '2025-03-30 14:56:58'),
(6, 12, 32, 0, '2025-03-30 14:57:06'),
(13, 12, 27, 0, '2025-03-31 12:49:26'),
(15, 12, 30, 0, '2025-03-31 12:51:41'),
(17, 12, 35, 0, '2025-04-02 03:34:05'),
(18, 12, 31, 0, '2025-04-03 07:39:50'),
(20, 17, 27, 10, '2025-04-03 16:45:31'),
(21, 17, 29, 10, '2025-04-03 16:45:34'),
(22, 17, 31, 10, '2025-04-05 07:02:09'),
(23, 17, 36, 10, '2025-04-06 16:25:42'),
(24, 17, 35, 0, '2025-04-06 17:08:05'),
(26, 17, 37, 0, '2025-04-07 05:59:34'),
(28, 19, 27, 10, '2025-04-09 09:15:01'),
(38, 30, 38, 10, '2025-04-10 08:30:41'),
(45, 29, 38, 10, '2025-04-10 14:58:23'),
(51, 30, 27, 10, '2025-04-12 02:54:16'),
(52, 29, 27, 10, '2025-04-12 06:47:45'),
(53, 29, 39, 0, '2025-04-13 05:35:25'),
(54, 30, 39, 10, '2025-04-13 15:25:17'),
(56, 30, 43, 0, '2025-04-13 16:04:04'),
(57, 29, 44, 0, '2025-04-14 15:04:04'),
(61, 29, 46, 10, '2025-04-17 09:51:55'),
(62, 29, 45, 10, '2025-04-18 06:04:08'),
(66, 31, 47, 0, '2025-04-20 04:11:55'),
(76, 29, 47, 20, '2025-04-20 07:51:38'),
(77, 31, 49, 0, '2025-04-20 08:00:31'),
(81, 29, 48, 0, '2025-04-20 09:11:41'),
(86, 29, 63, 0, '2025-04-22 15:51:59'),
(87, 29, 65, 10, '2025-04-22 15:52:50'),
(88, 29, 64, 0, '2025-04-22 15:54:28'),
(89, 29, 66, 15, '2025-04-22 15:56:19'),
(90, 29, 62, 10, '2025-04-23 08:32:24'),
(91, 29, 76, 10, '2025-04-24 04:09:23'),
(92, 29, 77, 10, '2025-04-25 04:59:54'),
(93, 29, 78, 10, '2025-05-10 14:07:11'),
(94, 29, 79, 0, '2025-05-10 14:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` enum('A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`) VALUES
(4, 27, 'gdfsgreresgsergre', 'dsadas', 'sadas', 'sdsadasd', 'sada', 'B'),
(5, 28, 'sghgbgfdgdfsfs', 'dsadsadsadsads', 'dsadsadsa', 'dsadsadsa', 'dasdasdsa', 'C'),
(15, 38, 'HTML is stands for?', 'sadadsadas', 'dasdasdsad', 'sadasdsad', 'dsadasda', 'D'),
(16, 39, 'saddsadsadasdsa', 'hypertext markup languege', 'hypermark laung', 'hyper mike ling', 'huper mark 1', 'B'),
(17, 40, 'dsadsdsadsa', 'dasdasdsad', 'sadasdasdsa', 'dsadasdasdsa', 'dsdasdas', 'B'),
(18, 41, 'dasdsad', 'adasdsa', 'sdasdasd', 'dsadsada', 'asdsa', 'A'),
(19, 42, 'dsadasd', 'dadasdsa', 'adaw', 'dasdaw', 'dawaaaaaaaa', 'B'),
(20, 43, 'asdsad', 'asdasds', 'dsdasdas', 'dsadas', 'dsadasd', 'C'),
(22, 45, '2. Which CSS layout module is best for building a responsive grid of items?', 'sadsadsadsa', 'dsdasdsadsad', 'sadadsad', 'sadsadsads', 'D'),
(23, 46, 'dsadsadasd', 'dasdas', 'dsadas', 'dsadsad', 'sadas', 'B'),
(24, 47, 'dsadadasdas', 'asdadsa', 'asdsadsa', 'dadasdad', 'dsadsad', 'B'),
(25, 48, 'dsadsadsadsd', 'sadasds', 'adasdsad', 'sadsad', 'sadasdas', 'A'),
(27, 50, 'dsadsdsdsda', 'dsadsads', 'dsadsad', 'sadsad', 'dsadsas', 'C'),
(32, 55, 'dsadsadsa', 'dsadasd', 'sadsad', 'asdsadsad', 'sadsad', 'D'),
(33, 56, 'dasdasdsd', 'adsadsa', 'dsadsad', 'sadsada', 'dsadasd', 'A'),
(34, 57, 'sdadsads', 'adasdasd', 'sadasd', 'asdsa', 'dasdsa', 'B'),
(35, 58, 'dsadsadas', 'dsadsa', 'dsadsa', 'dsadsa', 'dasdsa', 'A'),
(36, 59, 'dsadsadas', 'dasda', 'sadsadsadsa', 'dsadsad', 'sadasdas', 'D'),
(39, 62, 'Which of the following is the root element of every HTML page?', 'dasdasdasdsa', 'dsda', 'dsadadasd', 'asdasdsad', 'B'),
(40, 63, 'Which HTML tag is used to define the main content of an HTML document?', 'dasdasd', 'sdsadasa', 'dsadas', 'dsadasd', 'D'),
(41, 64, 'dsadsasadasdas', 'sadadasdsa', 'dsadasdsad', 'sadsads', 'adsadasdsa', 'D'),
(42, 65, 'dasdsadasd', 'asdsadas', 'dasd', 'asdsd', 'asdsadasdsa', 'A'),
(43, 66, 'fdsfsdffsdfds', 'fsdfscxvcvxcx', 'vsdfsdfds', 'fdsfsd', 'fsfsdf', 'B'),
(53, 76, 'What does HTML stand for?', 'HyperText Markdown Language', 'HyperText Markup Language', 'Hyper Transfer Markup Language', 'HighText Machine Language', 'B'),
(54, 77, 'Which tag is used to create a hyperlink in HTML?', 'link', 'a', 'href', 'hyperlink', 'B'),
(55, 78, 'What tag is used to insert an image in HTML?', 'image', 'pic', 'img', 'src', 'C'),
(56, 79, 'Which element defines the title of a document?', 'meta', 'head', 'title', 'header', 'C'),
(57, 80, 'How can you make text bold in HTML?', 'strong or b', 'bold', 'black', 'em', 'A'),
(58, 81, 'Which tag is used for an unordered list?', 'ul', 'ol', 'li', 'list', 'A'),
(59, 82, 'Which tag is used for a line break?', 'br', 'lb', 'break', 'line', 'A'),
(60, 83, 'What is the correct way to start an HTML document?', 'html', '!DOCTYPE html', 'body', 'start', 'B'),
(61, 84, 'Which of the following is a semantic HTML element?', 'div', 'article', 'span', 'b', 'B'),
(62, 85, 'Which tag is used to create a table?', '-- comment --', '// comment', '/* comment */', '# comment', 'C'),
(63, 86, 'Which tag creates a dropdown list?', 'input type=\"dropdown\"', 'select', 'list', 'dropdown', 'B'),
(64, 87, 'What attribute provides alternative text for an image?', 'alt', 'title', 'description', 'src', 'A'),
(65, 88, 'What input type is used for email addresses?', 'text', 'email', 'mail', 'e', 'B'),
(66, 89, 'Which tag is commonly used to group block elements?', 'section', 'div', 'group', 'container', 'B'),
(67, 90, 'What does CSS stand for?', 'Colorful Style Sheets', 'Computer Style Sheets', 'Cascading Style Sheets', 'Creative Style Syntax', 'C'),
(68, 91, 'Which property is used to change the text color in CSS?', 'background-color', 'text-color', 'font-color', 'color', 'D'),
(69, 92, 'How do you apply a CSS style to a specific HTML element by its ID?', '.idname', 'idname', '*idname', '#idname', 'D'),
(70, 93, 'Which CSS property controls the size of text?', 'font-style', 'font-size', 'text-size', 'size', 'B'),
(71, 94, 'How do you select all elements of a specific type, like all paragraphs?', '#p', '.p', '*p', 'p', 'D'),
(72, 95, 'Which value of the position property makes an element stick to a specific position on scroll?', 'relative', 'fixed', 'static', 'sticky', 'D'),
(73, 96, 'What is the correct syntax for a CSS comment?', '<!-- comment -->', '// comment', '/* comment */', '# comment', 'C'),
(74, 97, 'How do you apply a class in CSS?', '#classname', 'classname', '.classname', '*classname', 'C'),
(75, 98, 'Which CSS property is used to set the background image of an element?', 'background-img', 'image', 'background-image', 'img', 'C'),
(76, 99, 'Which unit is relative to the font size of the element?', 'px', '%', 'em', 'vh', 'C'),
(77, 100, 'What does JavaScript primarily run on?', 'Server', 'Browser', 'Database', 'Operating System', 'B'),
(78, 101, 'How do you write a comment in JavaScript?', '!-- This is a comment --', '# This is a comment', '// This is a comment', '* This is a comment **', 'C');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `image_paths` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `quiz_id`, `comment`, `image_paths`, `status`, `created_at`) VALUES
(864, 17, NULL, 'dsadsadsa', '[\"1743836457_1.png\"]', 'Seen', '2025-04-05 07:00:57'),
(867, 29, 27, 'dsadasd', NULL, 'Seen', '2025-04-09 14:17:05'),
(868, 29, 27, 'dasdwda', NULL, 'Seen', '2025-04-09 14:30:22'),
(869, 29, 27, 'dsadadwdadxadsadwad', NULL, 'Seen', '2025-04-09 14:38:16'),
(870, 29, 27, 'dsadwadsa', NULL, 'Seen', '2025-04-09 14:42:02'),
(871, 29, 27, 'dsadwdwadaw', NULL, 'Seen', '2025-04-09 14:52:35'),
(872, 29, 27, 'dasdd', NULL, 'Seen', '2025-04-09 14:56:09'),
(873, 29, 27, 'dsadas', NULL, 'Seen', '2025-04-09 15:18:37'),
(874, 29, 27, 'sdadsa', NULL, 'Seen', '2025-04-09 15:29:08'),
(875, 29, 27, 'dsadasd', NULL, 'Seen', '2025-04-09 15:58:33'),
(876, 29, 38, 'report', NULL, 'Seen', '2025-04-10 08:20:10'),
(877, 29, NULL, 'dsadsads', '[\"1744293000_Screenshot (2).png\"]', 'Seen', '2025-04-10 13:50:00'),
(878, 29, NULL, 'dsfdsfsd', '[\"1744293021_Screenshot 2024-12-31 161637.png\"]', 'Seen', '2025-04-10 13:50:21'),
(879, 29, 27, 'dsadsad', NULL, 'Seen', '2025-04-10 14:12:17'),
(880, 29, 27, 'dsasad', NULL, 'Seen', '2025-04-10 14:13:14'),
(881, 29, 27, 'dsadsads', NULL, 'Seen', '2025-04-10 14:22:55'),
(882, 29, NULL, 'dsadsadas', '[\"1744295008_Screenshot (1).png\"]', 'Seen', '2025-04-10 14:23:28'),
(883, 29, NULL, 'dsadasd', '[\"1744295384_Screenshot (1).png\"]', 'Seen', '2025-04-10 14:29:44'),
(884, 29, 27, 'dsadasdsa', NULL, 'Seen', '2025-04-10 14:39:46'),
(885, 29, 27, 'dsadsa', NULL, 'Seen', '2025-04-10 14:44:06'),
(886, 29, 27, 'dsadasda', NULL, 'Seen', '2025-04-10 14:46:41'),
(887, 29, 27, 'dsadsadsa', NULL, 'Seen', '2025-04-10 14:55:33'),
(888, 29, 27, 'dasdsads', NULL, 'Seen', '2025-04-10 15:01:26'),
(889, 29, 27, 'dsasa', NULL, 'Seen', '2025-04-10 15:01:55'),
(890, 29, 27, 'dsas', NULL, 'Seen', '2025-04-10 15:02:45'),
(891, 29, 27, 'dsadas', NULL, 'Seen', '2025-04-10 15:03:05'),
(892, 29, 27, 'dsadasd', NULL, 'Seen', '2025-04-10 15:07:04'),
(893, 29, 27, 'dsdasds', NULL, 'Seen', '2025-04-12 06:47:22'),
(894, 30, 43, 'dsadasd', NULL, 'Seen', '2025-04-13 16:03:44'),
(895, 29, NULL, 'fdfsfdsfdsfsd', '[\"1744912717_Screenshot (4).png\"]', 'Seen', '2025-04-17 17:58:37'),
(896, 29, 45, 'dsadsa', NULL, 'Seen', '2025-04-17 18:03:32'),
(897, 29, 47, 'dsadasds', NULL, 'Seen', '2025-04-18 14:15:45'),
(898, 29, 48, 'dsadsadsadsad', NULL, 'Seen', '2025-04-19 15:49:37'),
(899, 29, 47, 'dsadsa', NULL, 'Seen', '2025-04-19 15:50:25'),
(900, 31, 48, 'retake', NULL, 'Seen', '2025-04-20 04:12:21'),
(901, 29, 47, 'retake\r\n', NULL, 'Seen', '2025-04-20 06:49:25'),
(902, 29, 50, 'dsdasdsa', NULL, 'Seen', '2025-04-20 07:05:48'),
(903, 29, 50, 'dsadasdsdas', NULL, 'Seen', '2025-04-20 07:13:04'),
(904, 29, 50, 'dsadasdasd', NULL, 'Seen', '2025-04-20 07:20:46'),
(905, 29, 50, 'dasdasd', NULL, 'Seen', '2025-04-20 07:27:19'),
(906, 29, 47, 'dsadasdasdsa', NULL, 'Seen', '2025-04-20 07:51:06'),
(907, 29, 47, 'dsadasdas', NULL, 'Seen', '2025-04-20 07:51:28'),
(909, 31, 50, 'dsadsadas', NULL, 'Seen', '2025-04-20 08:01:03'),
(910, 29, 50, 'dsad', NULL, 'Seen', '2025-04-20 08:15:17'),
(912, 29, 48, 'dsadsad', NULL, 'Seen', '2025-04-20 09:11:26'),
(914, 29, 55, 'dsadasdasd', NULL, 'Seen', '2025-04-20 13:40:56'),
(917, 29, 62, 'retake', NULL, 'Seen', '2025-04-23 08:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `level_number` int(11) NOT NULL,
  `accuracy` float NOT NULL,
  `time_left` int(11) DEFAULT NULL,
  `coins_earned` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `grade_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`, `grade_id`) VALUES
(17, 'A', 0),
(18, 'B', 0),
(19, 'C', 0),
(20, 'D', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase`
--

CREATE TABLE `tbl_purchase` (
  `purchase_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp(),
  `cleared` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_purchase`
--

INSERT INTO `tbl_purchase` (`purchase_id`, `user_id`, `part_id`, `purchase_date`, `cleared`) VALUES
(12, 29, 67, '2025-04-24 11:01:27', 0),
(13, 29, 68, '2025-04-24 11:01:39', 0),
(14, 29, 69, '2025-04-24 12:08:53', 0),
(15, 29, 70, '2025-05-10 22:06:23', 0),
(16, 29, 71, '2025-05-10 22:11:25', 0),
(17, 29, 72, '2025-06-05 17:46:24', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_score`
--

CREATE TABLE `tbl_score` (
  `score_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `coin` int(11) NOT NULL,
  `completed` tinyint(1) UNSIGNED DEFAULT 0,
  `quiz_id` int(11) DEFAULT NULL,
  `attempt_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_score`
--

INSERT INTO `tbl_score` (`score_id`, `user_id`, `coin`, `completed`, `quiz_id`, `attempt_date`) VALUES
(1, 29, 2, 1, 3, '2025-04-23 12:25:12'),
(2, 29, 1, 1, 4, '2025-06-06 13:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `tbl_user_id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`tbl_user_id`, `first_name`, `last_name`, `contact_number`, `email`, `username`, `password`) VALUES
(11, 'dsadsads', 'saddasdsa', '312321323321', 'sadd@dsfdasdas', 'jogar3n1', '111111111'),
(15, 'Admin', 'Admin', '123456', 'admin@admin.com', 'admin12345', 'admin12345'),
(16, '111222333', '111222333', '111222333', '111@222333', '111222333', '$2y$10$cpox8O6uXx90f/TFgp9j3Oe92EMTt0t6N1WXuOneobnDEG9EW6Gja'),
(19, 'Admin', 'User', '09123456789', 'admin@example.com', 'admin', '$2y$10$3WxtfM99Em4HXPS/Ee0vy.5Qz6hzkkCINajd2rlPXrZiT6sW9cf6K');

-- --------------------------------------------------------

--
-- Table structure for table `tutorial`
--

CREATE TABLE `tutorial` (
  `id` int(255) NOT NULL,
  `tu_cat_id` int(255) NOT NULL,
  `tu_title` varchar(255) NOT NULL,
  `tut_par` text NOT NULL,
  `code_edit` text NOT NULL,
  `part_id` int(255) NOT NULL,
  `diff_part` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tutorial`
--

INSERT INTO `tutorial` (`id`, `tu_cat_id`, `tu_title`, `tut_par`, `code_edit`, `part_id`, `diff_part`) VALUES
(52, 11, 'dsdad', 'sdsadsadsadsad', 'sadasdsadasdsa', 7, 0),
(73, 23, 'Basic Structure of an HTML Document', 'Tags like <!DOCTYPE html>, <html>, <head>, and <body> are essential.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>My First Webpage</title>\r\n</head>\r\n<body>\r\n  <h1>Hello World!</h1>\r\n</body>\r\n</html>', 32, 0),
(74, 23, 'Common HTML Elements', 'Elements to display text and media:\r\n\r\nHeadings: <h1> to <h6>\r\n\r\nParagraphs: <p>\r\n\r\nLinks: <a>\r\n\r\nImages: <img>\r\n\r\nLists: <ul>, <ol>, <li>', 'dsdadsadsadsadasdsadasdasddsad', 34, 0),
(75, 23, 'Forms and Inputs', 'Useful for gathering user input.\r\n\r\nElements include: <form>, <input>, <textarea>, <button>, <select>', '<form>\r\n  <label for=\"email\">Email:</label>\r\n  <input type=\"email\" id=\"email\" name=\"email\">\r\n  <button type=\"submit\">Submit</button>\r\n</form>', 33, 0),
(76, 24, 'Basic CSS Syntax and Selectors', 'Targets elements using selectors.\r\n\r\nSyntax uses {} and properties like color, font-size, etc.', 'body {\r\n  background-color: lightblue;\r\n}', 34, 0),
(77, 24, 'Text and Box Styling', 'Style text with color, font-family, text-align\r\n\r\nManage spacing using margin, padding, border', '', 35, 0),
(78, 24, 'Layouts with Flexbox', 'Arrange elements in flexible rows or columns.\r\n\r\nGreat for creating responsive designs.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    .container {\r\n      display: flex;\r\n      justify-content: center;\r\n      align-items: center;\r\n      height: 100vh;\r\n      background-color: #f0f0f0;\r\n    }\r\n\r\n    .box {\r\n      width: 200px;\r\n      height: 100px;\r\n      background-color: #4CAF50;\r\n      color: white;\r\n      display: flex;\r\n      justify-content: center;\r\n      align-items: center;\r\n      font-size: 20px;\r\n      border-radius: 10px;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n\r\n<div class=\"container\">\r\n  <div class=\"box\">Centered Box</div', 35, 0),
(82, 26, 'Tutorial 1: Basic Semantic Layout', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Semantic Layout</title>\r\n</head>\r\n<body>\r\n  <header>\r\n    <h1>My Website</h1>\r\n    <nav>\r\n      <a href=\"#\">Home</a> |\r\n      <a href=\"#\">About</a>\r\n    </nav>\r\n  </header>\r\n\r\n  <main>\r\n    <p>Welcome to my awesome website!</p>\r\n  </main>\r\n\r\n  <footer>\r\n    <p>&copy; 2025 My Website</p>\r\n  </footer>\r\n</body>\r\n</html>', 40, 0),
(83, 26, 'Tutorial 2:', 'Using <section> and <article>', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Article Page</title>\r\n</head>\r\n<body>\r\n  <main>\r\n    <section>\r\n      <h2>Latest Blog Posts</h2>\r\n      <article>\r\n        <h3>5 Tips for Web Dev</h3>\r\n        <p>Learn how to improve your web development skills.</p>\r\n      </article>\r\n      <article>\r\n        <h3>Why Semantic HTML Matters</h3>\r\n        <p>Semantic tags help with accessibility and SEO.</p>\r\n      </article>\r\n    </section>\r\n  </main>\r\n</body>\r\n</html>', 40, 0),
(84, 26, 'Tutorial 1: Embed YouTube Video', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Video Example</title>\r\n</head>\r\n<body>\r\n  <h2>Watch This Video</h2>\r\n  <iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/dQw4w9WgXcQ\" \r\n    title=\"YouTube video\" frameborder=\"0\" allowfullscreen></iframe>\r\n</body>\r\n</html>', 41, 0),
(85, 26, 'Tutorial 2: Embed Audio', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Audio Example</title>\r\n</head>\r\n<body>\r\n  <h2>Listen to This Audio</h2>\r\n  <audio controls>\r\n    <source src=\"sample.mp3\" type=\"audio/mpeg\">\r\n    Your browser does not support the audio element.\r\n  </audio>\r\n</body>\r\n</html>', 41, 0),
(86, 26, 'Tutorial 1:', 'Table with thead, tbody, and tfoot', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>HTML Table</title>\r\n</head>\r\n<body>\r\n  <h2>User Data</h2>\r\n  <table border=\"1\">\r\n    <thead>\r\n      <tr><th>Name</th><th>Age</th></tr>\r\n    </thead>\r\n    <tbody>\r\n      <tr><td>Ana</td><td>21</td></tr>\r\n      <tr><td>Mark</td><td>19</td></tr>\r\n    </tbody>\r\n    <tfoot>\r\n      <tr><td colspan=\"2\">Total Users: 2</td></tr>\r\n    </tfoot>\r\n  </table>\r\n</body>\r\n</html>', 42, 0),
(87, 26, 'Tutorial 2: Fieldset Form Group', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Form Group</title>\r\n</head>\r\n<body>\r\n  <h2>Contact Info</h2>\r\n  <form>\r\n    <fieldset>\r\n      <legend>Personal Info</legend>\r\n      <label>Email: <input type=\"email\" name=\"email\"></label><br><br>\r\n      <label>Phone: <input type=\"tel\" name=\"phone\"></label>\r\n    </fieldset>\r\n  </form>\r\n</body>\r\n</html>', 41, 0),
(88, 27, 'Tutorial 1:', 'Link Hover Effect', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    a:hover {\r\n      color: red;\r\n      text-decoration: underline;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <a href=\"#\">Hover over me</a>\r\n</body>\r\n</html>', 43, 0),
(89, 27, 'Tutorial 2: Focus on Input Field', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    input:focus {\r\n      border: 2px solid green;\r\n      outline: none;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <input type=\"text\" placeholder=\"Focus on me\">\r\n</body>\r\n</html>', 43, 0),
(90, 27, 'Tutorial 1:', 'Absolute Inside Relative', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    .wrapper {\r\n      position: relative;\r\n      width: 300px;\r\n      height: 150px;\r\n      background-color: #ccc;\r\n    }\r\n\r\n    .absolute-box {\r\n      position: absolute;\r\n      top: 0;\r\n      right: 0;\r\n      background-color: orange;\r\n      padding: 10px;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <div class=\"wrapper\">\r\n    <div class=\"absolute-box\">Top Right</div>\r\n  </div>\r\n</body>\r\n</html>', 44, 0),
(91, 27, 'Tutorial 2:', 'Fixed Navigation Bar', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    nav {\r\n      position: fixed;\r\n      top: 0;\r\n      left: 0;\r\n      right: 0;\r\n      background: black;\r\n      color: white;\r\n      padding: 15px;\r\n      text-align: center;\r\n    }\r\n\r\n    .content {\r\n      margin-top: 60px;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <nav>Sticky Navigation</nav>\r\n  <div class=\"content\">\r\n    <p>Scroll to see the fixed nav bar stay in place!</p>\r\n    <p style=\"height:1000px;\">Lots of content here...</p>\r\n  </div>\r\n</body>\r\n</html>', 44, 0),
(92, 27, 'Tutorial 1: Button Hover Transition', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    button {\r\n      background-color: blue;\r\n      color: white;\r\n      padding: 10px 20px;\r\n      border: none;\r\n      transition: background-color 0.3s;\r\n    }\r\n\r\n    button:hover {\r\n      background-color: green;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <button>Hover me</button>\r\n</body>\r\n</html>', 45, 0),
(93, 27, 'Tutorial 2: Fade-in Effect on Load', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    .fade-in {\r\n      opacity: 0;\r\n      animation: fadeIn 2s forwards;\r\n    }\r\n\r\n    @keyframes fadeIn {\r\n      to {\r\n        opacity: 1;\r\n      }\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <div class=\"fade-in\">\r\n    <h2>Welcome!</h2>\r\n    <p>This text fades in on load.</p>\r\n  </div>\r\n</body>\r\n</html>', 45, 0),
(94, 28, 'Tutorial 1: Change Text on Button Click', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Change Text</title>\r\n</head>\r\n<body>\r\n  <p id=\"demo\">Original text.</p>\r\n  <button onclick=\"document.getElementById(\'demo\').innerText = \'Text has been changed!\'\">Click Me</button>\r\n</body>\r\n</html>', 46, 0),
(95, 28, 'Tutorial 2: Toggle Class', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    .box {\r\n      padding: 20px;\r\n      background-color: lightgray;\r\n    }\r\n    .active {\r\n      background-color: yellow;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <div id=\"box\" class=\"box\">I am a box</div>\r\n  <button onclick=\"document.getElementById(\'box\').classList.toggle(\'active\')\">Toggle Highlight</button>\r\n</body>\r\n</html>', 46, 0),
(96, 28, 'Tutorial 1: Display Array List html Copy Edit', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Array Display</title>\r\n</head>\r\n<body>\r\n  <ul id=\"fruitList\"></ul>\r\n\r\n  <script>\r\n    let fruits = [\"Apple\", \"Banana\", \"Mango\"];\r\n    let list = document.getElementById(\"fruitList\");\r\n\r\n    fruits.forEach(fruit => {\r\n      let item = document.createElement(\"li\");\r\n      item.textContent = fruit;\r\n      list.appendChild(item);\r\n    });\r\n  </script>\r\n</body>\r\n</html>', 46, 0),
(97, 28, 'Tutorial 1: Display Array List html Copy Edit', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Array Display</title>\r\n</head>\r\n<body>\r\n  <ul id=\"fruitList\"></ul>\r\n\r\n  <script>\r\n    let fruits = [\"Apple\", \"Banana\", \"Mango\"];\r\n    let list = document.getElementById(\"fruitList\");\r\n\r\n    fruits.forEach(fruit => {\r\n      let item = document.createElement(\"li\");\r\n      item.textContent = fruit;\r\n      list.appendChild(item);\r\n    });\r\n  </script>\r\n</body>\r\n</html>', 47, 0),
(98, 28, 'Tutorial 2: Number Loop Output', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Loop Output</title>\r\n</head>\r\n<body>\r\n  <ul id=\"numbers\"></ul>\r\n\r\n  <script>\r\n    for (let i = 1; i <= 5; i++) {\r\n      let li = document.createElement(\"li\");\r\n      li.textContent = \"Number: \" + i;\r\n      document.getElementById(\"numbers\").appendChild(li);\r\n    }\r\n  </script>\r\n</body>\r\n</html>', 47, 0),
(99, 29, 'Tutorial 1: Embedding Google Maps', '', '<!DOCTYPE html>\r\n<html>\r\n<head><title>Google Maps</title></head>\r\n<body>\r\n  <h2>My Location</h2>\r\n  <iframe \r\n    src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.187823389189!2d144.9631!3d-37.814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d43f5d5df7f%3A0xb771edb2b6b44c8!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1614746461326!5m2!1sen!2sau\" \r\n    width=\"600\" height=\"450\" allowfullscreen=\"\" loading=\"lazy\">\r\n  </iframe>\r\n</body>\r\n</html>', 50, 0),
(100, 29, 'Tutorial 2: HTML Page with Embedded PDF', '', '<!DOCTYPE html>\r\n<html>\r\n<head><title>PDF Embed</title></head>\r\n<body>\r\n  <h2>View PDF</h2>\r\n  <embed src=\"yourfile.pdf\" width=\"600\" height=\"500\" type=\"application/pdf\">\r\n</body>\r\n</html>', 48, 0),
(101, 29, 'Tutorial 1: HTML5 Form Validation html Copy Edit', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Form Validation</title>\r\n</head>\r\n<body>\r\n  <form>\r\n    <label>Email: <input type=\"email\" required></label><br><br>\r\n    <label>Password: <input type=\"password\" required minlength=\"6\"></label><br><br>\r\n    <button type=\"submit\">Submit</button>\r\n  </form>\r\n</body>\r\n</html>', 49, 0),
(102, 29, 'Tutorial 2: Pattern Attribute for Custom Validation', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Username Validation</title>\r\n</head>\r\n<body>\r\n  <form>\r\n    <label>Username (letters only): \r\n      <input type=\"text\" pattern=\"[A-Za-z]+\" title=\"Only letters allowed\" required>\r\n    </label>\r\n    <br><br>\r\n    <button type=\"submit\">Submit</button>\r\n  </form>\r\n</body>\r\n</html>', 49, 0),
(103, 29, 'Tutorial 1: Using data-* in HTML Elements', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Data Attributes</title>\r\n</head>\r\n<body>\r\n  <button data-user=\"admin\">Get User</button>\r\n\r\n  <script>\r\n    document.querySelector(\'button\').addEventListener(\'click\', function() {\r\n      alert(\"User: \" + this.dataset.user);\r\n    });\r\n  </script>\r\n</body>\r\n</html>', 50, 0),
(104, 29, 'Tutorial 2: Toggle Details Using data-*', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>Show/Hide Details</title>\r\n</head>\r\n<body>\r\n  <button onclick=\"toggle()\">Toggle Info</button>\r\n  <p id=\"info\" data-visible=\"false\" style=\"display:none;\">This is a hidden paragraph.</p>\r\n\r\n  <script>\r\n    function toggle() {\r\n      const info = document.getElementById(\"info\");\r\n      const isVisible = info.dataset.visible === \"true\";\r\n      info.style.display = isVisible ? \"none\" : \"block\";\r\n      info.dataset.visible = !isVisible;\r\n    }\r\n  </script>\r\n</body>\r\n</html>', 50, 0),
(105, 30, 'Tutorial 1: Responsive Flexbox Cards', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    .container {\r\n      display: flex;\r\n      gap: 20px;\r\n      flex-wrap: wrap;\r\n    }\r\n    .card {\r\n      flex: 1 1 200px;\r\n      padding: 20px;\r\n      background: #eee;\r\n      border-radius: 10px;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <div class=\"container\">\r\n    <div class=\"card\">Card 1</div>\r\n    <div class=\"card\">Card 2</div>\r\n    <div class=\"card\">Card 3</div>\r\n  </div>\r\n</body>\r\n</html>', 51, 0),
(106, 30, 'Tutorial 2: Centering with Flexbox', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    .center {\r\n      display: flex;\r\n      justify-content: center;\r\n      align-items: center;\r\n      height: 100vh;\r\n      background: lightblue;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <div class=\"center\">\r\n    <h1>Centered Content</h1>\r\n  </div>\r\n</body>\r\n</html>', 51, 0),
(107, 30, 'Tutorial 1: 2x2 Grid Layout', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    .grid {\r\n      display: grid;\r\n      grid-template-columns: 1fr 1fr;\r\n      gap: 20px;\r\n    }\r\n    .box {\r\n      background: #ccc;\r\n      padding: 20px;\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <div class=\"grid\">\r\n    <div class=\"box\">Box 1</div>\r\n    <div class=\"box\">Box 2</div>\r\n    <div class=\"box\">Box 3</div>\r\n    <div class=\"box\">Box 4</div>\r\n  </div>\r\n</body>\r\n</html>', 52, 0),
(108, 30, 'Tutorial 2: Grid Template Areas html Copy Edit', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    .layout {\r\n      display: grid;\r\n      grid-template-areas: \r\n        \"header header\"\r\n        \"nav content\"\r\n        \"footer footer\";\r\n      grid-template-columns: 1fr 3fr;\r\n      gap: 10px;\r\n    }\r\n    .header { grid-area: header; background: lightcoral; padding: 10px; }\r\n    .nav { grid-area: nav; background: lightblue; padding: 10px; }\r\n    .content { grid-area: content; background: lightgreen; padding: 10px; }\r\n    .footer { grid-area: footer; background: lightgray; padding: 10px; }\r\n  </style>\r\n</head>\r\n<body>\r\n  <div class=\"layout\">\r\n    <div class=\"header\">Header</div>\r\n    <div class=\"nav\">Navigation</div>\r\n    <div class=\"content\">Main Content</div>\r\n    <div class=\"footer\">Footer</div>\r\n  </div>\r\n</body>\r\n</html>', 52, 0),
(109, 30, 'Tutorial 1: Media Query for Mobile', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    body {\r\n      background-color: white;\r\n    }\r\n\r\n    @media (max-width: 600px) {\r\n      body {\r\n        background-color: lightgray;\r\n      }\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <h2>Resize the window</h2>\r\n</body>\r\n</html>', 53, 0),
(110, 30, 'Tutorial 2: CSS Animation (Bounce) html Copy Edit', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <style>\r\n    .bounce {\r\n      width: 100px;\r\n      height: 100px;\r\n      background: salmon;\r\n      position: relative;\r\n      animation: bounce 2s infinite;\r\n    }\r\n\r\n    @keyframes bounce {\r\n      0%, 100% { top: 0; }\r\n      50% { top: -50px; }\r\n    }\r\n  </style>\r\n</head>\r\n<body>\r\n  <div class=\"bounce\"></div>\r\n</body>\r\n</html>', 53, 0),
(111, 31, 'Form Validation with JS', '', '<!DOCTYPE html>\r\n<html>\r\n<head><title>Form Validation</title></head>\r\n<body>\r\n  <form onsubmit=\"return validateForm()\">\r\n    <input type=\"text\" id=\"name\" placeholder=\"Enter name\">\r\n    <input type=\"number\" id=\"age\" placeholder=\"Enter age\">\r\n    <button type=\"submit\">Submit</button>\r\n  </form>\r\n\r\n  <script>\r\n    function validateForm() {\r\n      const name = document.getElementById(\"name\").value;\r\n      const age = document.getElementById(\"age\").value;\r\n      if (name === \"\" || age === \"\") {\r\n        alert(\"All fields are required!\");\r\n        return false;\r\n      }\r\n      if (age < 18) {\r\n        alert(\"You must be 18 or older.\");\r\n        return false;\r\n      }\r\n      return true;\r\n    }\r\n  </script>\r\n</body>\r\n</html>', 54, 0),
(112, 31, 'Fetch Data from JSON Placeholder API', '', '<!DOCTYPE html>\r\n<html>\r\n<head><title>API Example</title></head>\r\n<body>\r\n  <h2>Fetch Users</h2>\r\n  <button onclick=\"loadUsers()\">Load Users</button>\r\n  <ul id=\"userList\"></ul>\r\n\r\n  <script>\r\n    function loadUsers() {\r\n      fetch(\'https://jsonplaceholder.typicode.com/users\')\r\n        .then(response => response.json())\r\n        .then(users => {\r\n          const list = document.getElementById(\"userList\");\r\n          list.innerHTML = \"\";\r\n          users.forEach(user => {\r\n            const li = document.createElement(\"li\");\r\n            li.textContent = user.name;\r\n            list.appendChild(li);\r\n          });\r\n        });\r\n    }\r\n  </script>\r\n</body>\r\n</html>', 54, 0),
(113, 25, 'dsadsadsa', 'dsadasdasd', 'sadsadsa', 39, 0),
(115, 24, 'dsdsa', '', '', 34, 0),
(116, 24, 'dsadsada', '', '', 34, 0),
(118, 24, 'dasdas', 'dsadasd', 'dsadsadsa', 36, 0),
(120, 24, 'dsadsad', 'dsadsa', 'dasdasdasdas', 37, 0),
(121, 37, 'Easy Learning with HTML \"Try it Yourself\"', 'With our \"Try it Yourself\" editor, you can edit the HTML code and view the result:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>Page Title</title>\r\n</head>\r\n<body>\r\n\r\n<h1>This is a Heading</h1>\r\n<p>This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 59, 0),
(122, 37, 'What is HTML?', 'HTML stands for Hyper Text Markup Language\r\nHTML is the standard markup language for creating Web pages\r\nHTML describes the structure of a Web page\r\nHTML consists of a series of elements\r\nHTML elements tell the browser how to display the content\r\nHTML elements label pieces of content such as \"this is a heading\", \"this is a paragraph\", \"this is a link\", etc.', '', 60, 0),
(123, 37, 'A Simple HTML Document', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>Page Title</title>\r\n</head>\r\n<body>\r\n\r\n<h1>My First Heading</h1>\r\n<p>My first paragraph.</p>\r\n\r\n</body>\r\n</html>', 60, 0),
(124, 37, 'A Simple HTML Document', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>Page Title</title>\r\n</head>\r\n<body>\r\n\r\n<h1>My First Heading</h1>\r\n<p>My first paragraph.</p>\r\n\r\n</body>\r\n</html>', 60, 0),
(125, 37, 'Example Explained', 'The <!DOCTYPE html> declaration defines that this document is an HTML5 document\r\nThe <html> element is the root element of an HTML page\r\nThe <head> element contains meta information about the HTML page\r\nThe <title> element specifies a title for the HTML page (which is shown in the browser\'s title bar or in the page\'s tab)\r\nThe <body> element defines the document\'s body, and is a container for all the visible contents, such as headings, paragraphs, images, hyperlinks, tables, lists, etc.\r\nThe <h1> element defines a large heading\r\nThe <p> element defines a paragraph', '', 60, 0),
(126, 37, 'What is an HTML Element?', 'An HTML element is defined by a start tag, some content, and an end tag:\r\n\r\n<tagname> Content goes here... </tagname>\r\nThe HTML element is everything from the start tag to the end tag:\r\n\r\n<h1>My First Heading</h1>\r\n<p>My first paragraph.</p>', 'Start tag	Element content 	End tag\r\n<h1>	My First Heading	                </h1>\r\n<p>	    My first paragraph.	                  </p>\r\n<br>	none	none', 60, 0),
(127, 37, 'HTML Page Structure', 'Below is a visualization of an HTML page structure:', '<html>\r\n<head>\r\n<title>Page title</title>\r\n</head>\r\n<body>\r\n<h1>This is a heading</h1>\r\n<p>This is a paragraph.</p>\r\n<p>This is another paragraph.</p>\r\n</body>\r\n</html>', 60, 0),
(128, 38, 'Color Names', 'In HTML, a color can be specified by using a color name:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1 style=\"background-color:Tomato;\">Tomato</h1>\r\n<h1 style=\"background-color:Orange;\">Orange</h1>\r\n<h1 style=\"background-color:DodgerBlue;\">DodgerBlue</h1>\r\n<h1 style=\"background-color:MediumSeaGreen;\">MediumSeaGreen</h1>\r\n<h1 style=\"background-color:Gray;\">Gray</h1>\r\n<h1 style=\"background-color:SlateBlue;\">SlateBlue</h1>\r\n<h1 style=\"background-color:Violet;\">Violet</h1>\r\n<h1 style=\"background-color:LightGray;\">LightGray</h1>\r\n\r\n</body>\r\n</html>', 61, 0),
(129, 38, 'Background Color', 'You can set the background color for HTML elements:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1 style=\"background-color:DodgerBlue;\">Hello World</h1>\r\n\r\n<p style=\"background-color:Tomato;\">\r\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.\r\nUt wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.\r\n</p>\r\n\r\n</body>\r\n</html>', 61, 0),
(130, 38, 'Text Color', 'Hello World\r\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.\r\n\r\nUt wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h3 style=\"color:Tomato;\">Hello World</h3>\r\n\r\n<p style=\"color:DodgerBlue;\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>\r\n\r\n<p style=\"color:MediumSeaGreen;\">Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>\r\n\r\n</body>\r\n</html>', 61, 0),
(131, 38, 'Border Color', 'You can set the color of borders:\r\n\r\nHello World\r\nHello World\r\nHello World', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1 style=\"border: 2px solid Tomato;\">Hello World</h1>\r\n\r\n<h1 style=\"border: 2px solid DodgerBlue;\">Hello World</h1>\r\n\r\n<h1 style=\"border: 2px solid Violet;\">Hello World</h1>\r\n\r\n</body>\r\n</html>', 61, 0),
(132, 39, 'Examples in Each Chapter', 'This CSS tutorial contains hundreds of CSS examples.\r\n\r\nWith our online editor, you can edit the CSS, and click on a button to view the result.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\nbody {\r\n  background-color: lightblue;\r\n}\r\n\r\nh1 {\r\n  color: white;\r\n  text-align: center;\r\n}\r\n\r\np {\r\n  font-family: verdana;\r\n  font-size: 20px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>My First CSS Example</h1>\r\n<p>This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 62, 0),
(133, 39, 'Use the Menu', 'We recommend reading this tutorial, in the sequence listed in the menu.\r\n\r\nIf you have a large screen, the menu will always be present on the left.\r\n\r\nIf you have a small screen, open the menu by clicking the top menu sign ?.', '', 62, 0),
(134, 40, 'Automatic Numbering With Counters', 'CSS counters are like \"variables\". The variable values can be incremented by CSS rules (which will track how many times they are used).\r\n\r\nTo work with CSS counters we will use the following properties:\r\n\r\ncounter-reset - Creates or resets a counter\r\ncounter-increment - Increments a counter value\r\ncontent - Inserts generated content\r\ncounter() or counters() function - Adds the value of a counter to an element\r\n\r\nTo use a CSS counter, it must first be created with counter-reset.\r\n\r\nThe following example creates a counter for the page (in the body selector), then increments the counter value for each <h2> element and adds \"Section <value of the counter>:\" to the beginning of each <h2> element:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\nbody {\r\n  counter-reset: section;\r\n}\r\n\r\nh2::before {\r\n  counter-increment: section;\r\n  content: \"Section \" counter(section) \": \";\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>Using CSS Counters</h1>\r\n\r\n<h2>HTML Tutorial</h2>\r\n<h2>CSS Tutorial</h2>\r\n<h2>JavaScript Tutorial</h2>\r\n<h2>Python Tutorial</h2>\r\n<h2>SQL Tutorial</h2>\r\n\r\n</body>\r\n</html>', 63, 0),
(135, 41, 'Examples in Each Chapter', 'With our \"Try it Yourself\" editor, you can edit the source code and view the result.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h2>My First JavaScript</h2>\r\n\r\n<button type=\"button\"\r\nonclick=\"document.getElementById(\'demo\').innerHTML = Date()\">\r\nClick me to display Date and Time.</button>\r\n\r\n<p id=\"demo\"></p>\r\n\r\n</body>\r\n</html>', 64, 0),
(136, 42, 'What is HTML Canvas?', 'The HTML <canvas> element is used to draw graphics, on the fly, via JavaScript.\r\n\r\nThe <canvas> element is only a container for graphics. You must use JavaScript to actually draw the graphics.\r\n\r\nCanvas has several methods for drawing paths, boxes, circles, text, and adding images.\r\n\r\nCanvas is supported by all major browsers.', '', 65, 0),
(137, 42, 'Canvas Examples', 'A canvas is a rectangular area on an HTML page. By default, a canvas has no border and no content.\r\nThe markup looks like this:\r\n\r\n<canvas id=\"myCanvas\" width=\"200\" height=\"100\"></canvas>\r\n\r\nNote: Always specify an id attribute (to be referred to in a script), and a width and height attribute to define the size of the canvas. To add a border, use the style attribute.\r\n\r\nHere is an example of a basic, empty canvas:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<canvas id=\"myCanvas\" width=\"200\" height=\"100\" style=\"border:1px solid #000000;\">\r\nYour browser does not support the HTML canvas tag.\r\n</canvas>\r\n\r\n</body>\r\n</html>', 65, 0),
(138, 42, 'Add a JavaScript', 'After creating the rectangular canvas area, you must add a JavaScript to do the drawing.\r\n\r\nHere are some examples:\r\n\r\nDraw a Line', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<canvas id=\"myCanvas\" width=\"200\" height=\"100\" style=\"border:1px solid #d3d3d3;\">\r\nYour browser does not support the HTML canvas tag.</canvas>\r\n\r\n<script>\r\nvar c = document.getElementById(\"myCanvas\");\r\nvar ctx = c.getContext(\"2d\");\r\nctx.moveTo(0,0);\r\nctx.lineTo(200,100);\r\nctx.stroke();\r\n</script>\r\n\r\n</body>\r\n</html>', 65, 0),
(139, 42, 'Draw a Circle', 'Draw a Text', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<canvas id=\"myCanvas\" width=\"200\" height=\"100\" style=\"border:1px solid #d3d3d3;\">\r\nYour browser does not support the HTML canvas tag.</canvas>\r\n\r\n<script>\r\nvar c = document.getElementById(\"myCanvas\");\r\nvar ctx = c.getContext(\"2d\");\r\nctx.beginPath();\r\nctx.arc(95,50,40,0,2*Math.PI);\r\nctx.stroke();\r\n</script> \r\n\r\n</body>\r\n</html>', 65, 0),
(140, 43, 'Property	Value', 'Property	Value\r\nfirstName	John\r\nlastName	Doe\r\nage	50\r\neyeColor	blue\r\nfullName	function() {return this.firstName + \" \" + this.lastName;}', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n<h1>JavaScript Objects</h1>\r\n<h2>Object Methods</h2>\r\n<p>A method is a function definition stored as a property value.</p>\r\n\r\n<p id=\"demo\"></p>\r\n\r\n<script>\r\nconst person = {\r\n  firstName: \"John\",\r\n  lastName: \"Doe\",\r\n  id: 5566,\r\n  fullName: function() {\r\n    return this.firstName + \" \" + this.lastName;\r\n  }\r\n};\r\n\r\ndocument.getElementById(\"demo\").innerHTML = person.fullName();\r\n</script>\r\n</body>\r\n</html>', 66, 0),
(141, 44, 'Easy Learning with HTML \"Try it Yourself\"', 'With our \"Try it Yourself\" editor, you can edit the HTML code and view the result:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>Page Title</title>\r\n</head>\r\n<body>\r\n\r\n<h1>This is a Heading</h1>\r\n<p>This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 67, 0),
(142, 44, 'HTML Examples', 'In this HTML tutorial, you will find more than 200 examples. With our online \"Try it Yourself\" editor, you can edit and test each example yourself!', '', 67, 0),
(143, 44, 'HTML Introduction', 'HTML stands for Hyper Text Markup Language\r\nHTML is the standard markup language for creating Web pages\r\nHTML describes the structure of a Web page\r\nHTML consists of a series of elements\r\nHTML elements tell the browser how to display the content\r\nHTML elements label pieces of content such as \"this is a heading\", \"this is a paragraph\", \"this is a link\", etc.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>Page Title</title>\r\n</head>\r\n<body>\r\n\r\n<h1>My First Heading</h1>\r\n<p>My first paragraph.</p>\r\n\r\n</body>\r\n</html>', 68, 0),
(144, 44, 'Example Explained', 'The <!DOCTYPE html> declaration defines that this document is an HTML5 document\r\nThe <html> element is the root element of an HTML page\r\nThe <head> element contains meta information about the HTML page\r\nThe <title> element specifies a title for the HTML page (which is shown in the browser\'s title bar or in the page\'s tab)\r\nThe <body> element defines the document\'s body, and is a container for all the visible contents, such as headings, paragraphs, images, hyperlinks, tables, lists, etc.\r\nThe <h1> element defines a large heading\r\nThe <p> element defines a paragraph', '', 68, 0),
(145, 44, 'What is an HTML Element?', 'An HTML element is defined by a start tag, some content, and an end tag:\r\n\r\n<tagname> Content goes here... </tagname>\r\nThe HTML element is everything from the start tag to the end tag:\r\n\r\n<h1>My First Heading</h1>\r\n<p>My first paragraph.</p>', '', 68, 0),
(146, 44, 'HTML Editors', 'Web pages can be created and modified by using professional HTML editors.\r\n\r\nHowever, for learning HTML we recommend a simple text editor like Notepad (PC) or TextEdit (Mac).\r\n\r\nWe believe that using a simple text editor is a good way to learn HTML.\r\n\r\nFollow the steps below to create your first web page with Notepad or TextEdit.', '', 69, 0),
(147, 44, 'Step 1: Open Notepad (PC)', 'Windows 8 or later:\r\n\r\nOpen the Start Screen (the window symbol at the bottom left on your screen). Type Notepad.\r\n\r\nWindows 7 or earlier:\r\n\r\nOpen Start > Programs > Accessories > Notepad', '', 69, 0),
(148, 44, 'Step 1: Open TextEdit (Mac)', 'Open Finder > Applications > TextEdit\r\n\r\nAlso change some preferences to get the application to save files correctly. In Preferences > Format > choose \"Plain Text\"\r\n\r\nThen under \"Open and Save\", check the box that says \"Display HTML files as HTML code instead of formatted text\".\r\n\r\nThen open a new document to place the code.', '', 69, 0),
(149, 44, 'Step 2: Write Some HTML', 'Write or copy the following HTML code into Notepad:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>My First Heading</h1>\r\n\r\n<p>My first paragraph.</p>\r\n\r\n</body>\r\n</html>', 69, 0),
(150, 44, 'HTML Documents', 'All HTML documents must start with a document type declaration: <!DOCTYPE html>.\r\n\r\nThe HTML document itself begins with <html> and ends with </html>.\r\n\r\nThe visible part of the HTML document is between <body> and </body>.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>My First Heading</h1>\r\n\r\n<p>My first paragraph.</p>\r\n\r\n</body>\r\n</html>', 70, 0),
(151, 44, 'The <!DOCTYPE> Declaration', 'The <!DOCTYPE> declaration represents the document type, and helps browsers to display web pages correctly.\r\n\r\nIt must only appear once, at the top of the page (before any HTML tags).\r\n\r\nThe <!DOCTYPE> declaration is not case sensitive.\r\n\r\nThe <!DOCTYPE> declaration for HTML5 is:', '<!DOCTYPE html>', 70, 0),
(152, 44, 'HTML Headings', 'HTML headings are defined with the <h1> to <h6> tags.\r\n\r\n<h1> defines the most important heading. <h6> defines the least important heading:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>This is heading 1</h1>\r\n<h2>This is heading 2</h2>\r\n<h3>This is heading 3</h3>\r\n<h4>This is heading 4</h4>\r\n<h5>This is heading 5</h5>\r\n<h6>This is heading 6</h6>\r\n\r\n</body>\r\n</html>', 70, 0),
(153, 44, 'HTML Paragraphs', 'HTML paragraphs are defined with the <p> tag:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>This is a paragraph.</p>\r\n<p>This is another paragraph.</p>\r\n\r\n</body>\r\n</html>', 70, 0),
(154, 44, 'HTML Links', 'HTML links are defined with the <a> tag:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h2>HTML Links</h2>\r\n<p>HTML links are defined with the a tag:</p>\r\n\r\n<a href=\"https://www.WebDev.com\">This is a link</a>\r\n\r\n</body>\r\n</html>', 70, 0),
(155, 44, '', 'The link\'s destination is specified in the href attribute. \r\n\r\nAttributes are used to provide additional information about HTML elements.\r\n\r\nYou will learn more about attributes in a later chapter.', '', 70, 0),
(156, 44, 'HTML Images', 'HTML images are defined with the <img> tag.\r\n\r\nThe source file (src), alternative text (alt), width, and height are provided as attributes:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h2>HTML Images</h2>\r\n<p>HTML images are defined with the img tag:</p>\r\n\r\n<img src=\"w3schools.jpg\" alt=\"W3Schools.com\" width=\"104\" height=\"142\">\r\n\r\n</body>\r\n</html>', 70, 0),
(157, 44, 'HTML Elements', 'The HTML element is everything from the start tag to the end tag:\r\n\r\n<tagname>Content goes here...</tagname>\r\nExamples of some HTML elements:\r\n\r\n<h1>My First Heading</h1>\r\n<p>My first paragraph.</p>', '', 71, 0),
(158, 44, 'Nested HTML Elements', 'HTML elements can be nested (this means that elements can contain other elements).\r\n\r\nAll HTML documents consist of nested HTML elements.\r\n\r\nThe following example contains four HTML elements (<html>, <body>, <h1> and <p>):', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>My First Heading</h1>\r\n<p>My first paragraph.</p>\r\n\r\n</body>\r\n</html>', 71, 0),
(159, 44, 'Example Explained', 'The <html> element is the root element and it defines the whole HTML document.\r\n\r\nIt has a start tag <html> and an end tag </html>.\r\n\r\nThen, inside the <html> element there is a <body> element:', '<body>\r\n\r\n<h1>My First Heading</h1>\r\n<p>My first paragraph.</p>\r\n\r\n</body>', 71, 0),
(160, 44, '', 'The <body> element defines the document\'s body.\r\n\r\nIt has a start tag <body> and an end tag </body>.\r\n\r\nThen, inside the <body> element there are two other elements: <h1> and <p>:', '<h1>My First Heading</h1>\r\n<p>My first paragraph.</p>', 71, 0),
(161, 44, '', 'The <h1> element defines a heading.\r\n\r\nIt has a start tag <h1> and an end tag </h1>:', '<h1>My First Heading</h1>', 71, 0),
(162, 44, '', 'The <p> element defines a paragraph.\r\n\r\nIt has a start tag <p> and an end tag </p>:', '<p>My first paragraph.</p>', 71, 0),
(163, 44, 'HTML Attributes', 'All HTML elements can have attributes\r\nAttributes provide additional information about elements\r\nAttributes are always specified in the start tag\r\nAttributes usually come in name/value pairs like: name=\"value\"', '', 72, 0),
(164, 44, 'The href Attribute', 'The <a> tag defines a hyperlink. The href attribute specifies the URL of the page the link goes to:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h2>The href Attribute</h2>\r\n\r\n<p>HTML links are defined with the a tag. The link address is specified in the href attribute:</p>\r\n\r\n<a href=\"https://www.WebDev.com\">Visit Web Dev</a>\r\n\r\n</body>\r\n</html>', 72, 0),
(165, 44, 'The src Attribute', 'The <img> tag is used to embed an image in an HTML page. The src attribute specifies the path to the image to be displayed:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h2>The src Attribute</h2>\r\n<p>HTML images are defined with the img tag, and the filename of the image source is specified in the src attribute:</p>\r\n\r\n<img src=\"img_girl.jpg\" width=\"500\" height=\"600\">\r\n\r\n</body>\r\n</html>', 72, 0),
(166, 44, 'HTML Headings', 'HTML headings are titles or subtitles that you want to display on a webpage.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>Heading 1</h1>\r\n<h2>Heading 2</h2>\r\n<h3>Heading 3</h3>\r\n<h4>Heading 4</h4>\r\n<h5>Heading 5</h5>\r\n<h6>Heading 6</h6>\r\n\r\n</body>\r\n</html>', 73, 0),
(167, 44, 'HTML Headings', 'HTML headings are defined with the <h1> to <h6> tags.\r\n\r\n<h1> defines the most important heading. <h6> defines the least important heading.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>Heading 1</h1>\r\n<h2>Heading 2</h2>\r\n<h3>Heading 3</h3>\r\n<h4>Heading 4</h4>\r\n<h5>Heading 5</h5>\r\n<h6>Heading 6</h6>\r\n\r\n</body>\r\n</html>', 73, 0),
(168, 44, 'Headings Are Important', 'Search engines use the headings to index the structure and content of your web pages.\r\n\r\nUsers often skim a page by its headings. It is important to use headings to show the document structure.\r\n\r\n<h1> headings should be used for main headings, followed by <h2> headings, then the less important <h3>, and so on.', '', 73, 0),
(169, 44, 'Bigger Headings', 'Each HTML heading has a default size. However, you can specify the size for any heading with the style attribute, using the CSS font-size property:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1 style=\"font-size:60px;\">Heading 1</h1>\r\n\r\n<p>You can change the size of a heading with the style attribute, using the font-size property.</p>\r\n\r\n</body>\r\n</html>', 73, 0),
(170, 45, 'HTML Styles', 'The HTML style attribute is used to add styles to an element, such as color, font, size, and more.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>I am normal</p>\r\n<p style=\"color:red;\">I am red</p>\r\n<p style=\"color:blue;\">I am blue</p>\r\n<p style=\"font-size:50px;\">I am big</p>\r\n\r\n</body>\r\n</html>', 77, 0),
(171, 45, 'The HTML Style Attribute', 'Setting the style of an HTML element, can be done with the style attribute.\r\n\r\nThe HTML style attribute has the following syntax:', '<tagname style=\"property:value;\">', 77, 0),
(172, 45, 'Background Color', 'The CSS background-color property defines the background color for an HTML element.', '<!DOCTYPE html>\r\n<html>\r\n<body style=\"background-color:powderblue;\">\r\n\r\n<h1>This is a heading</h1>\r\n<p>This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 77, 0),
(173, 45, '', '', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1 style=\"background-color:powderblue;\">This is a heading</h1>\r\n<p style=\"background-color:tomato;\">This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 77, 0),
(174, 45, 'HTML contains several elements for defining text with a special meaning.', '', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p><b>This text is bold</b></p>\r\n<p><i>This text is italic</i></p>\r\n<p>This is<sub> subscript</sub> and <sup>superscript</sup></p>\r\n\r\n</body>\r\n</html>', 78, 0),
(175, 45, 'HTML Formatting Elements', 'Formatting elements were designed to display special types of text:\r\n\r\n<b> - Bold text\r\n<strong> - Important text\r\n<i> - Italic text\r\n<em> - Emphasized text\r\n<mark> - Marked text\r\n<small> - Smaller text\r\n<del> - Deleted text\r\n<ins> - Inserted text\r\n<sub> - Subscript text\r\n<sup> - Superscript text', '', 78, 0),
(176, 45, 'HTML <b> and <strong> Elements', 'The HTML <b> element defines bold text, without any extra importance.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>This text is normal.</p>\r\n\r\n<p><b>This text is bold.</b></p>\r\n\r\n</body>\r\n</html>', 78, 0),
(177, 45, 'HTML <b> and <strong> Elements', 'The HTML <b> element defines bold text, without any extra importance.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>This text is normal.</p>\r\n\r\n<p><b>This text is bold.</b></p>\r\n\r\n</body>\r\n</html>', 78, 0),
(178, 45, 'HTML Quotation and Citation Elements', 'HTML <blockquote> for Quotations\r\nThe HTML <blockquote> element defines a section that is quoted from another source.\r\n\r\nBrowsers usually indent <blockquote> elements.', '', 79, 0),
(179, 45, '', 'HTML <q> for Short Quotations\r\nThe HTML <q> tag defines a short quotation.\r\n\r\nBrowsers normally insert quotation marks around the quotation.', '', 79, 0),
(180, 45, 'HTML <abbr> for Abbreviations', 'The HTML <abbr> tag defines an abbreviation or an acronym, like \"HTML\", \"CSS\", \"Mr.\", \"Dr.\", \"ASAP\", \"ATM\".\r\n\r\nMarking abbreviations can give useful information to browsers, translation systems and search-engines.\r\n\r\nTip: Use the global title attribute to show the description for the abbreviation/acronym when you mouse over the element.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>The <abbr title=\"World Health Organization\">WHO</abbr> was founded in 1948.</p>\r\n\r\n<p>Marking up abbreviations can give useful information to browsers, translation systems and search-engines.</p>\r\n\r\n</body>\r\n</html>', 79, 0),
(181, 45, 'HTML <address> for Contact Information', 'The HTML <address> tag defines the contact information for the author/owner of a document or an article.\r\n\r\nThe contact information can be an email address, URL, physical address, phone number, social media handle, etc.\r\n\r\nThe text in the <address> element usually renders in italic, and browsers will always add a line break before and after the <address> element.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>The HTML address element defines contact information (author/owner) of a document or article.</p>\r\n\r\n<address>\r\nWritten by John Doe.<br> \r\nVisit us at:<br>\r\nExample.com<br>\r\nBox 564, Disneyland<br>\r\nUSA\r\n</address>\r\n\r\n</body>\r\n</html>', 79, 0),
(182, 45, 'HTML <cite> for Work Title', 'The HTML <cite> tag defines the title of a creative work (e.g. a book, a poem, a song, a movie, a painting, a sculpture, etc.).\r\n\r\nNote: A person\'s name is not the title of a work.\r\n\r\nThe text in the <cite> element usually renders in italic.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>The HTML cite element defines the title of a work.</p>\r\n<p>Browsers usually display cite elements in italic.</p>\r\n\r\n<img src=\"img_the_scream.jpg\" width=\"220\" height=\"277\" alt=\"The Scream\">\r\n<p><cite>The Scream</cite> by Edvard Munch. Painted in 1893.</p>\r\n\r\n</body>\r\n</html>', 79, 0),
(183, 45, 'HTML <bdo> for Bi-Directional Override', 'BDO stands for Bi-Directional Override.\r\n\r\nThe HTML <bdo> tag is used to override the current text direction:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>If your browser supports bi-directional override (bdo), the next line will be written from right to left (rtl):</p>\r\n\r\n<bdo dir=\"rtl\">This line will be written from right to left</bdo>\r\n\r\n</body>\r\n</html>', 79, 0),
(184, 45, 'HTML <abbr> for Abbreviations', 'The HTML <abbr> tag defines an abbreviation or an acronym, like \"HTML\", \"CSS\", \"Mr.\", \"Dr.\", \"ASAP\", \"ATM\".\r\n\r\nMarking abbreviations can give useful information to browsers, translation systems and search-engines.\r\n\r\nTip: Use the global title attribute to show the description for the abbreviation/acronym when you mouse over the element.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>The <abbr title=\"World Health Organization\">WHO</abbr> was founded in 1948.</p>\r\n\r\n<p>Marking up abbreviations can give useful information to browsers, translation systems and search-engines.</p>\r\n\r\n</body>\r\n</html>', 80, 0),
(185, 45, 'HTML <address> for Contact Information', 'The HTML <address> tag defines the contact information for the author/owner of a document or an article.\r\n\r\nThe contact information can be an email address, URL, physical address, phone number, social media handle, etc.\r\n\r\nThe text in the <address> element usually renders in italic, and browsers will always add a line break before and after the <address> element.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>The HTML address element defines contact information (author/owner) of a document or article.</p>\r\n\r\n<address>\r\nWritten by John Doe.<br> \r\nVisit us at:<br>\r\nExample.com<br>\r\nBox 564, Disneyland<br>\r\nUSA\r\n</address>\r\n\r\n</body>\r\n</html>', 80, 0),
(186, 45, 'HTML <cite> for Work Title', 'The HTML <cite> tag defines the title of a creative work (e.g. a book, a poem, a song, a movie, a painting, a sculpture, etc.).\r\n\r\nNote: A person\'s name is not the title of a work.\r\n\r\nThe text in the <cite> element usually renders in italic.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>The HTML cite element defines the title of a work.</p>\r\n<p>Browsers usually display cite elements in italic.</p>\r\n\r\n<img src=\"img_the_scream.jpg\" width=\"220\" height=\"277\" alt=\"The Scream\">\r\n<p><cite>The Scream</cite> by Edvard Munch. Painted in 1893.</p>\r\n\r\n</body>\r\n</html>', 80, 0),
(187, 45, 'HTML <bdo> for Bi-Directional Override', 'BDO stands for Bi-Directional Override.\r\n\r\nThe HTML <bdo> tag is used to override the current text direction:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>If your browser supports bi-directional override (bdo), the next line will be written from right to left (rtl):</p>\r\n\r\n<bdo dir=\"rtl\">This line will be written from right to left</bdo>\r\n\r\n</body>\r\n</html>', 80, 0),
(188, 45, 'HTML Comment Tag', 'You can add comments to your HTML source by using the following syntax:', '<!-- Write your comments here -->', 81, 0),
(189, 45, 'Add Comments', 'With comments you can place notifications and reminders in your HTML code:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<!-- This is a comment -->\r\n<p>This is a paragraph.</p>\r\n<!-- Comments are not displayed in the browser -->\r\n\r\n</body>\r\n</html>', 81, 0),
(190, 45, 'Hide Content', 'Comments can be used to hide content.\r\n\r\nThis can be helpful if you hide content temporarily:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>This is a paragraph.</p>\r\n\r\n<!-- <p>This is another paragraph </p> -->\r\n\r\n<p>This is a paragraph too.</p>\r\n\r\n</body>\r\n</html>', 81, 0),
(191, 45, '', 'You can also hide more than one line. Everything between the <!-- and the --> will be hidden from the display.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<p>This is a paragraph.</p>\r\n<!--\r\n<p>Look at this cool image:</p>\r\n<img border=\"0\" src=\"pic_trulli.jpg\" alt=\"Trulli\">\r\n-->\r\n<p>This is a paragraph too.</p>\r\n\r\n</body>\r\n</html>', 81, 0),
(192, 45, 'Color Names', 'In HTML, a color can be specified by using a color name:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1 style=\"background-color:Tomato;\">Tomato</h1>\r\n<h1 style=\"background-color:Orange;\">Orange</h1>\r\n<h1 style=\"background-color:DodgerBlue;\">DodgerBlue</h1>\r\n<h1 style=\"background-color:MediumSeaGreen;\">MediumSeaGreen</h1>\r\n<h1 style=\"background-color:Gray;\">Gray</h1>\r\n<h1 style=\"background-color:SlateBlue;\">SlateBlue</h1>\r\n<h1 style=\"background-color:Violet;\">Violet</h1>\r\n<h1 style=\"background-color:LightGray;\">LightGray</h1>\r\n\r\n</body>\r\n</html>', 82, 0),
(193, 45, 'Background Color', 'You can set the background color for HTML elements:', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1 style=\"background-color:DodgerBlue;\">Hello World</h1>\r\n\r\n<p style=\"background-color:Tomato;\">\r\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.\r\nUt wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.\r\n</p>\r\n\r\n</body>\r\n</html>', 82, 0),
(194, 45, 'Text Color', 'You can set the color of text:\r\n\r\nHello World\r\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.\r\n\r\nUt wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h3 style=\"color:Tomato;\">Hello World</h3>\r\n\r\n<p style=\"color:DodgerBlue;\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>\r\n\r\n<p style=\"color:MediumSeaGreen;\">Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>\r\n\r\n</body>\r\n</html>', 82, 0),
(195, 45, 'How To Add a Favicon in HTML', 'To add a favicon to your website, either save your favicon image to the root directory of your webserver, or create a folder in the root directory called images, and save your favicon image in this folder. A common name for a favicon image is \"favicon.ico\".\r\n\r\nNext, add a <link> element to your \"index.html\" file, after the <title> element, like this:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n  <title>My Page Title</title>\r\n  <link rel=\"icon\" type=\"image/x-icon\" href=\"/images/favicon.ico\">\r\n</head>\r\n<body>\r\n\r\n<h1>This is a Heading</h1>\r\n<p>This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 83, 0),
(196, 45, '', 'Now, save the \"index.html\" file and reload it in your browser. Your browser tab should now display your favicon image to the left of the page title.', '', 83, 0),
(197, 45, 'Favicon File Format Support', 'The following table shows the file format support for a favicon image:', '', 83, 0);
INSERT INTO `tutorial` (`id`, `tu_cat_id`, `tu_title`, `tut_par`, `code_edit`, `part_id`, `diff_part`) VALUES
(198, 46, 'Examples in Each Chapter', 'This CSS tutorial contains hundreds of CSS examples.\r\n\r\nWith our online editor, you can edit the CSS, and click on a button to view the result.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\nbody {\r\n  background-color: lightblue;\r\n}\r\n\r\nh1 {\r\n  color: white;\r\n  text-align: center;\r\n}\r\n\r\np {\r\n  font-family: verdana;\r\n  font-size: 20px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>My First CSS Example</h1>\r\n<p>This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 87, 0),
(199, 46, 'What is CSS?', 'CSS stands for Cascading Style Sheets\r\nCSS describes how HTML elements are to be displayed on screen, paper, or in other media\r\nCSS saves a lot of work. It can control the layout of multiple web pages all at once\r\nExternal stylesheets are stored in CSS files', '', 68, 0),
(200, 46, 'CSS Demo - One HTML Page - Multiple Styles!', 'Here we will show one HTML page displayed with four different stylesheets. Click on the \"Stylesheet 1\", \"Stylesheet 2\", \"Stylesheet 3\", \"Stylesheet 4\" links below to see the different styles:', '', 88, 0),
(201, 46, 'Why Use CSS?', 'CSS is used to define styles for your web pages, including the design, layout and variations in display for different devices and screen sizes.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\nbody {\r\n  background-color: lightblue;\r\n}\r\n\r\nh1 {\r\n  color: white;\r\n  text-align: center;\r\n}\r\n\r\np {\r\n  font-family: verdana;\r\n  font-size: 20px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>My First CSS Example</h1>\r\n<p>This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 88, 0),
(202, 46, 'CSS Solved a Big Problem', 'HTML was NEVER intended to contain tags for formatting a web page!\r\n\r\nHTML was created to describe the content of a web page, like:\r\n\r\n<h1>This is a heading</h1>\r\n\r\n<p>This is a paragraph.</p>\r\n\r\nWhen tags like <font>, and color attributes were added to the HTML 3.2 specification, it started a nightmare for web developers. Development of large websites, where fonts and color information were added to every single page, became a long and expensive process.\r\n\r\nTo solve this problem, the World Wide Web Consortium (W3C) created CSS.\r\n\r\nCSS removed the style formatting from the HTML page!', '', 68, 0),
(203, 46, 'CSS Syntax', 'The selector points to the HTML element you want to style.\r\n\r\nThe declaration block contains one or more declarations separated by semicolons.\r\n\r\nEach declaration includes a CSS property name and a value, separated by a colon.\r\n\r\nMultiple CSS declarations are separated with semicolons, and declaration blocks are surrounded by curly braces.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\np {\r\n  color: red;\r\n  text-align: center;\r\n} \r\n</style>\r\n</head>\r\n<body>\r\n\r\n<p>Hello World!</p>\r\n<p>These paragraphs are styled with CSS.</p>\r\n\r\n</body>\r\n</html>', 89, 0),
(204, 46, 'Example Explained', 'p is a selector in CSS (it points to the HTML element you want to style: <p>).\r\ncolor is a property, and red is the property value\r\ntext-align is a property, and center is the property value', '', 89, 0),
(205, 46, 'CSS Selectors', 'CSS selectors are used to \"find\" (or select) the HTML elements you want to style.\r\n\r\nWe can divide CSS selectors into five categories:\r\n\r\nSimple selectors (select elements based on name, id, class)\r\nCombinator selectors (select elements based on a specific relationship between them)\r\nPseudo-class selectors (select elements based on a certain state)\r\nPseudo-elements selectors (select and style a part of an element)\r\nAttribute selectors (select elements based on an attribute or attribute value)', '', 90, 0),
(206, 46, 'The CSS element Selector', 'The element selector selects HTML elements based on the element name.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\np {\r\n  text-align: center;\r\n  color: red;\r\n} \r\n</style>\r\n</head>\r\n<body>\r\n\r\n<p>Every paragraph will be affected by the style.</p>\r\n<p id=\"para1\">Me too!</p>\r\n<p>And me!</p>\r\n\r\n</body>\r\n</html>', 90, 0),
(207, 46, 'The CSS id Selector', 'The id selector uses the id attribute of an HTML element to select a specific element.\r\n\r\nThe id of an element is unique within a page, so the id selector is used to select one unique element!\r\n\r\nTo select an element with a specific id, write a hash (#) character, followed by the id of the element.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n#para1 {\r\n  text-align: center;\r\n  color: red;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<p id=\"para1\">Hello World!</p>\r\n<p>This paragraph is not affected by the style.</p>\r\n\r\n</body>\r\n</html>', 90, 0),
(208, 46, 'Three Ways to Insert CSS', 'There are three ways of inserting a style sheet:\r\n\r\nExternal CSS\r\nInternal CSS\r\nInline CSS', '', 91, 0),
(209, 46, 'External CSS', 'With an external style sheet, you can change the look of an entire website by changing just one file!\r\n\r\nEach HTML page must include a reference to the external style sheet file inside the <link> element, inside the head section.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<link rel=\"stylesheet\" href=\"mystyle.css\">\r\n</head>\r\n<body>\r\n\r\n<h1>This is a heading</h1>\r\n<p>This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 91, 0),
(210, 46, '', 'An external style sheet can be written in any text editor, and must be saved with a .css extension.\r\n\r\nThe external .css file should not contain any HTML tags.\r\n\r\nHere is how the \"mystyle.css\" file looks:', '', 91, 0),
(211, 46, 'Internal CSS', 'An internal style sheet may be used if one single HTML page has a unique style.\r\n\r\nThe internal style is defined inside the <style> element, inside the head section.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\nbody {\r\n  background-color: linen;\r\n}\r\n\r\nh1 {\r\n  color: maroon;\r\n  margin-left: 40px;\r\n} \r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>This is a heading</h1>\r\n<p>This is a paragraph.</p>\r\n\r\n</body>\r\n</html>', 91, 0),
(212, 47, 'CSS border-radius Property', 'The CSS border-radius property defines the radius of an element\'s corners.\r\n\r\nTip: This property allows you to add rounded corners to elements!\r\n\r\nHere are three examples:\r\n\r\n\r\n1. Rounded corners for an element with a specified background color:\r\n\r\n2. Rounded corners for an element with a border:\r\n\r\n3. Rounded corners for an element with a background image:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style> \r\n#rcorners1 {\r\n  border-radius: 25px;\r\n  background: #73AD21;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px;  \r\n}\r\n\r\n#rcorners2 {\r\n  border-radius: 25px;\r\n  border: 2px solid #73AD21;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px;  \r\n}\r\n\r\n#rcorners3 {\r\n  border-radius: 25px;\r\n  background: url(paper.gif);\r\n  background-position: left top;\r\n  background-repeat: repeat;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px;  \r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>The border-radius Property</h1>\r\n\r\n<p>Rounded corners for an element with a specified background color:</p>\r\n<p id=\"rcorners1\">Rounded corners!</p>\r\n<p>Rounded corners for an element with a border:</p>\r\n<p id=\"rcorners2\">Rounded corners!</p>\r\n<p>Rounded corners for an element with a background image:</p>\r\n<p id=\"rcorners3\">Rounded corners!</p>\r\n\r\n</body>\r\n</html>', 92, 0),
(213, 47, 'CSS border-radius - Specify Each Corner', 'The border-radius property can have from one to four values. Here are the rules:\r\n\r\nFour values - border-radius: 15px 50px 30px 5px; (first value applies to top-left corner, second value applies to top-right corner, third value applies to bottom-right corner, and fourth value applies to bottom-left corner)\r\n\r\nThree values - border-radius: 15px 50px 30px; (first value applies to top-left corner, second value applies to top-right and bottom-left corners, and third value applies to bottom-right corner):\r\n\r\nTwo values - border-radius: 15px 50px; (first value applies to top-left and bottom-right corners, and the second value applies to top-right and bottom-left corners):\r\n\r\nOne value - border-radius: 15px; (the value applies to all four corners, which are rounded equally:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style> \r\n#rcorners1 {\r\n  border-radius: 15px 50px 30px 5px;\r\n  background: #73AD21;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px; \r\n}\r\n\r\n#rcorners2 {\r\n  border-radius: 15px 50px 30px;\r\n  background: #73AD21;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px; \r\n}\r\n\r\n#rcorners3 {\r\n  border-radius: 15px 50px;\r\n  background: #73AD21;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px; \r\n} \r\n\r\n#rcorners4 {\r\n  border-radius: 15px;\r\n  background: #73AD21;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px; \r\n} \r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>The border-radius Property</h1>\r\n\r\n<p>Four values - border-radius: 15px 50px 30px 5px:</p>\r\n<p id=\"rcorners1\"></p>\r\n\r\n<p>Three values - border-radius: 15px 50px 30px:</p>\r\n<p id=\"rcorners2\"></p>\r\n\r\n<p>Two values - border-radius: 15px 50px:</p>\r\n<p id=\"rcorners3\"></p>\r\n\r\n<p>One value - border-radius: 15px:</p>\r\n<p id=\"rcorners4\"></p>\r\n\r\n</body>\r\n</html>', 92, 0),
(214, 47, 'You could also create elliptical corners:', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style> \r\n#rcorners1 {\r\n  border-radius: 50px / 15px;\r\n  background: #73AD21;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px; \r\n}\r\n\r\n#rcorners2 {\r\n  border-radius: 15px / 50px;\r\n  background: #73AD21;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px; \r\n}\r\n\r\n#rcorners3 {\r\n  border-radius: 50%;\r\n  background: #73AD21;\r\n  padding: 20px; \r\n  width: 200px;\r\n  height: 150px;\r\n} \r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>The border-radius Property</h1>\r\n\r\n<p>Elliptical border - border-radius: 50px / 15px:</p>\r\n<p id=\"rcorners1\"></p>\r\n\r\n<p>Elliptical border - border-radius: 15px / 50px:</p>\r\n<p id=\"rcorners2\"></p>\r\n\r\n<p>Ellipse border - border-radius: 50%:</p>\r\n<p id=\"rcorners3\"></p>\r\n\r\n</body>\r\n</html>', 92, 0),
(215, 47, 'What is !important? The !important rule in CSS is used to add m', 'The !important rule in CSS is used to add more importance to a property/value than normal.\r\n\r\nIn fact, if you use the !important rule, it will override ALL previous styling rules for that specific property on that element!\r\n\r\nLet us look at an example:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n#myid {\r\n  background-color: blue;\r\n}\r\n\r\n.myclass {\r\n  background-color: gray;\r\n}\r\n\r\np {\r\n  background-color: red !important;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<p>This is some text in a paragraph.</p>\r\n\r\n<p class=\"myclass\">This is some text in a paragraph.</p>\r\n\r\n<p id=\"myid\">This is some text in a paragraph.</p>\r\n\r\n</body>\r\n</html>', 93, 0),
(216, 47, 'Important About !important', 'The only way to override an !important rule is to include another !important rule on a declaration with the same (or higher) specificity in the source code - and here the problem starts! This makes the CSS code confusing and the debugging will be hard, especially if you have a large style sheet!\r\n\r\nHere we have created a simple example. It is not very clear, when you look at the CSS source code, which color is considered most important:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n#myid {\r\n  background-color: blue !important;\r\n}\r\n\r\n.myclass {\r\n  background-color: gray !important;\r\n}\r\n\r\np {\r\n  background-color: red !important;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<p>This is some text in a paragraph.</p>\r\n\r\n<p class=\"myclass\">This is some text in a paragraph.</p>\r\n\r\n<p id=\"myid\">This is some text in a paragraph.</p>\r\n\r\n</body>\r\n</html>', 78, 0),
(217, 47, 'Maybe One or Two Fair Uses of !important', 'One way to use !important is if you have to override a style that cannot be overridden in any other way. This could be if you are working on a Content Management System (CMS) and cannot edit the CSS code. Then you can set some custom styles to override some of the CMS styles.\r\n\r\nAnother way to use !important is: Assume you want a special look for all buttons on a page. Here, buttons are styled with a gray background color, white text, and some padding and border:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n.button {\r\n  background-color: #8c8c8c; \r\n  color: white;\r\n  padding: 5px;\r\n  border: 1px solid black; \r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<p>Standard button: <a class=\"button\" href=\"default.asp\">CSS Tutorial</a></p>\r\n\r\n<p>Standard button: <a class=\"button\" href=\"/html/\">HTML Tutorial</a></p>\r\n\r\n</body>\r\n</html>', 93, 0),
(218, 47, '', 'The look of a button can sometimes change if we put it inside another element with higher specificity, and the properties get in conflict. Here is an example of this:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n.button {\r\n  background-color: #8c8c8c; \r\n  color: white;\r\n  padding: 5px;\r\n  border: 1px solid black; \r\n}\r\n\r\n#myDiv a {\r\n  color: red;\r\n  background-color: yellow;  \r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<p>Standard button: <a class=\"button\" href=\"default.asp\">CSS Tutorial</a></p>\r\n\r\n<div id=\"myDiv\">\r\n<p>A link text inside myDiv: <a href=\"/html/\">HTML Tutorial</a></p>\r\n<p>A link button inside myDiv: <a href=\"/html/\" class=\"button\">HTML Tutorial</a></p>\r\n</div>\r\n\r\n</body>\r\n</html>', 93, 0),
(219, 47, 'To \"force\" all buttons to have the same look, no matter what, we can add the !important rule to the properties of the button, like this:', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n.button {\r\n  background-color: #8c8c8c !important; \r\n  color: white !important;\r\n  padding: 5px !important;\r\n  border: 1px solid black !important; \r\n}\r\n\r\n#myDiv a {\r\n  color: red;\r\n  background-color: yellow;  \r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<p>Standard button: <a class=\"button\" href=\"default.asp\">CSS Tutorial</a></p>\r\n\r\n<div id=\"myDiv\">\r\n<p>A link text inside myDiv: <a href=\"/html/\">HTML Tutorial</a></p>\r\n<p>A link button inside myDiv: <a href=\"/html/\" class=\"button\">HTML Tutorial</a></p>\r\n</div>\r\n\r\n</body>\r\n</html>', 93, 0),
(220, 47, '', 'CSS has a lot of properties for formatting text.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\ndiv {\r\n  border: 1px solid gray;\r\n  padding: 8px;\r\n}\r\n\r\nh1 {\r\n  text-align: center;\r\n  text-transform: uppercase;\r\n  color: #4CAF50;\r\n}\r\n\r\np {\r\n  text-indent: 50px;\r\n  text-align: justify;\r\n  letter-spacing: 3px;\r\n}\r\n\r\na {\r\n  text-decoration: none;\r\n  color: #008CBA;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<div>\r\n  <h1>text formatting</h1>\r\n  <p>This text is styled with some of the text formatting properties. The heading uses the text-align, text-transform, and color properties.\r\n  The paragraph is indented, aligned, and the space between characters is specified. The underline is removed from this colored\r\n  <a target=\"_blank\" href=\"tryit.asp?filename=trycss_text\">\"Try it Yourself\"</a> link.</p>\r\n</div>\r\n\r\n</body>\r\n</html>', 94, 0),
(221, 47, 'Text Color', 'The color property is used to set the color of the text. The color is specified by:\r\n\r\na color name - like \"red\"\r\na HEX value - like \"#ff0000\"\r\nan RGB value - like \"rgb(255,0,0)\"\r\nLook at CSS Color Values for a complete list of possible color values.\r\n\r\nThe default text color for a page is defined in the body selector.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\nbody {\r\n  color: blue;\r\n}\r\n\r\nh1 {\r\n  color: green;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>This is heading 1</h1>\r\n<p>This is an ordinary paragraph. Notice that this text is blue. The default text color for a page is defined in the body selector.</p>\r\n<p>Another paragraph.</p>\r\n\r\n</body>\r\n</html>', 94, 0),
(222, 47, 'Text Color and Background Color', 'In this example, we define both the background-color property and the color property:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\nbody {\r\n  background-color: lightgrey;\r\n  color: blue;\r\n}\r\n\r\nh1 {\r\n  background-color: black;\r\n  color: white;\r\n}\r\n\r\ndiv {\r\n  background-color: blue;\r\n  color: white;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>This is a Heading</h1>\r\n<p>This page has a grey background color and a blue text.</p>\r\n<div>This is a div.</div>\r\n\r\n</body>\r\n</html>', 94, 0),
(223, 47, 'Text Alignment and Text Direction', 'In this chapter you will learn about the following properties:\r\n\r\ntext-align\r\ntext-align-last\r\ndirection\r\nunicode-bidi\r\nvertical-align', '', 95, 0),
(224, 47, 'Text Alignment', 'The text-align property is used to set the horizontal alignment of a text.\r\n\r\nA text can be left or right aligned, centered, or justified.\r\n\r\nThe following example shows center aligned, and left and right aligned text (left alignment is default if text direction is left-to-right, and right alignment is default if text direction is right-to-left):', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\nh1 {\r\n  text-align: center;\r\n}\r\n\r\nh2 {\r\n  text-align: left;\r\n}\r\n\r\nh3 {\r\n  text-align: right;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>Heading 1 (center)</h1>\r\n<h2>Heading 2 (left)</h2>\r\n<h3>Heading 3 (right)</h3>\r\n\r\n<p>The three headings above are aligned center, left and right.</p>\r\n\r\n</body>\r\n</html>', 95, 0),
(225, 47, '', 'When the text-align property is set to \"justify\", each line is stretched so that every line has equal width, and the left and right margins are straight (like in magazines and newspapers):', '', 95, 0),
(226, 47, '', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\ndiv {\r\n  border: 1px solid black;\r\n  padding: 10px;\r\n  width: 200px;\r\n  height: 200px;\r\n  text-align: justify;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>Example text-align: justify</h1>\r\n\r\n<p>The text-align: justify; value stretches the lines so that each line has equal width (like in newspapers and magazines).</p>\r\n\r\n<div>\r\nIn my younger and more vulnerable years my father gave me some advice that I\'ve been turning over in my mind ever since. \'Whenever you feel like criticizing anyone,\' he told me, \'just remember that all the people in this world haven\'t had the advantages that you\'ve had.\'\r\n</div>\r\n\r\n</body>\r\n</html>', 95, 0),
(227, 48, 'CSS Multiple Backgrounds', 'CSS allows you to add multiple background images for an element, through the background-image property.\r\n\r\nThe different background images are separated by commas, and the images are stacked on top of each other, where the first image is closest to the viewer.\r\n\r\nThe following example has two background images, the first image is a flower (aligned to the bottom and right) and the second image is a paper background (aligned to the top-left corner):', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style> \r\n#example1 {\r\n  background-image: url(img_flwr.gif), url(paper.gif);\r\n  background-position: right bottom, left top;\r\n  background-repeat: no-repeat, repeat;\r\n  padding: 15px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>Multiple Backgrounds</h1>\r\n<p>The following div element has two background images:</p>\r\n\r\n<div id=\"example1\">\r\n  <h1>Lorem Ipsum Dolor</h1>\r\n  <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>\r\n  <p>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>\r\n</div>\r\n\r\n</body>\r\n</html>', 97, 0),
(228, 48, '', 'Multiple background images can be specified using either the individual background properties (as above) or the background shorthand property.\r\n\r\nThe following example uses the background shorthand property (same result as example above):', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style> \r\n#example1 {\r\n  background: url(img_flwr.gif) right bottom no-repeat, url(paper.gif) left top repeat;\r\n  padding: 15px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<div id=\"example1\">\r\n  <h1>Lorem Ipsum Dolor</h1>\r\n  <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>\r\n  <p>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>\r\n</div>\r\n\r\n</body>\r\n</html>', 97, 0),
(229, 48, 'RGBA Colors', 'RGBA color values are an extension of RGB color values with an alpha channel - which specifies the opacity for a color.\r\n\r\nAn RGBA color value is specified with: rgba(red, green, blue, alpha). The alpha parameter is a number between 0.0 (fully transparent) and 1.0 (fully opaque).', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n#p1 {background-color:rgba(255,0,0,0.3);}\r\n#p2 {background-color:rgba(0,255,0,0.3);}\r\n#p3 {background-color:rgba(0,0,255,0.3);}\r\n#p4 {background-color:rgba(192,192,192,0.3);}\r\n#p5 {background-color:rgba(255,255,0,0.3);}\r\n#p6 {background-color:rgba(255,0,255,0.3);}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>Define Colors With RGBA Values</h1>\r\n\r\n<p id=\"p1\">Red</p>\r\n<p id=\"p2\">Green</p>\r\n<p id=\"p3\">Blue</p>\r\n<p id=\"p4\">Grey</p>\r\n<p id=\"p5\">Yellow</p>\r\n<p id=\"p6\">Cerise</p>\r\n\r\n</body>\r\n</html>', 98, 0),
(230, 48, 'HSL Colors', 'HSL stands for Hue, Saturation and Lightness.\r\n\r\nAn HSL color value is specified with: hsl(hue, saturation, lightness).\r\n\r\nHue is a degree on the color wheel (from 0 to 360):\r\n0 (or 360) is red\r\n120 is green\r\n240 is blue\r\nSaturation is a percentage value: 100% is the full color.\r\nLightness is also a percentage; 0% is dark (black) and 100% is white.', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n#p1 {background-color:hsl(120,100%,50%);}\r\n#p2 {background-color:hsl(120,100%,75%);}\r\n#p3 {background-color:hsl(120,100%,25%);}\r\n#p4 {background-color:hsl(120,60%,70%);}\r\n#p5 {background-color:hsl(290,100%,50%);}\r\n#p6 {background-color:hsl(290,60%,70%);}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>Define Colors With HSL Values</h1>\r\n\r\n<p id=\"p1\">Green</p>\r\n<p id=\"p2\">Light green</p>\r\n<p id=\"p3\">Dark green</p>\r\n<p id=\"p4\">Pastel green</p>\r\n<p id=\"p5\">Violet</p>\r\n<p id=\"p6\">Pastel violet</p>\r\n\r\n</body>\r\n</html>', 98, 0),
(231, 48, 'HSLA Colors', 'HSLA color values are an extension of HSL color values with an alpha channel - which specifies the opacity for a color.\r\n\r\nAn HSLA color value is specified with: hsla(hue, saturation, lightness, alpha), where the alpha parameter defines the opacity. The alpha parameter is a number between 0.0 (fully transparent) and 1.0 (fully opaque).', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n#p1 {background-color:hsla(120,100%,50%,0.3);}\r\n#p2 {background-color:hsla(120,100%,75%,0.3);}\r\n#p3 {background-color:hsla(120,100%,25%,0.3);}\r\n#p4 {background-color:hsla(120,60%,70%,0.3);}\r\n#p5 {background-color:hsla(290,100%,50%,0.3);}\r\n#p6 {background-color:hsla(290,60%,70%,0.3);}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>Define Colors With HSLA Values</h1>\r\n\r\n<p id=\"p1\">Green</p>\r\n<p id=\"p2\">Light green</p>\r\n<p id=\"p3\">Dark green</p>\r\n<p id=\"p4\">Pastel green</p>\r\n<p id=\"p5\">Violet</p>\r\n<p id=\"p6\">Pastel violet</p>\r\n\r\n</body>\r\n</html>', 98, 0),
(232, 48, 'Direction - Top to Bottom (this is default)', 'The following example shows a linear gradient that starts at the top. It starts red, transitioning to yellow:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n#grad1 {\r\n  height: 200px;\r\n  background-color: red; /* For browsers that do not support gradients */\r\n  background-image: linear-gradient(red, yellow);\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>Linear Gradient - Top to Bottom</h1>\r\n<p>This linear gradient starts red at the top, transitioning to yellow at the bottom:</p>\r\n\r\n<div id=\"grad1\"></div>\r\n\r\n</body>\r\n</html>', 99, 0),
(233, 48, 'Direction - Left to Right', 'The following example shows a linear gradient that starts from the left. It starts red, transitioning to yellow:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<style>\r\n#grad1 {\r\n  height: 200px;\r\n  background-color: red; /* For browsers that do not support gradients */\r\n  background-image: linear-gradient(to right, red , yellow);\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\r\n<h1>Linear Gradient - Left to Right</h1>\r\n<p>This linear gradient starts red at the left, transitioning to yellow (to the right):</p>\r\n\r\n<div id=\"grad1\"></div>\r\n\r\n</body>\r\n</html>', 99, 0),
(234, 49, 'JavaScript Assignment Operators', 'Assignment operators assign values to JavaScript variables.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>JavaScript Assignments</h1>\r\n<h2>Simple Assignment</h2>\r\n<h3>The = Operator</h3>\r\n\r\n<p id=\"demo\"></p>\r\n\r\n<script>\r\nlet x = 10;\r\ndocument.getElementById(\"demo\").innerHTML = \"Value of x is: \" + x;\r\n</script>\r\n\r\n</body>\r\n</html>', 102, 0),
(235, 49, '', '', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>JavaScript Assignments</h1>\r\n<h2>Simple Assignment</h2>\r\n<h3>The = Operator</h3>\r\n\r\n<p id=\"demo\"></p>\r\n\r\n<script>\r\nlet y = 50\r\nlet x = 10 + y;\r\ndocument.getElementById(\"demo\").innerHTML = \"Value of x is: \" + x;\r\n</script>\r\n\r\n</body>\r\n</html>', 102, 0),
(236, 49, 'The += Operator', 'The Addition Assignment Operator adds a value to a variable.', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>JavaScript Assignments</h1>\r\n<h2>Addition Assignment</h2>\r\n<h3>The += Operator</h3>\r\n\r\n<p id=\"demo\"></p>\r\n\r\n<script>\r\nlet x = 10;\r\nx += 5;\r\ndocument.getElementById(\"demo\").innerHTML = \"Value of x is: \" + x;\r\n</script>\r\n\r\n</body>\r\n</html>', 102, 0),
(237, 49, '', '', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h1>JavaScript Assignments</h1>\r\n<h2>Addition Assignment</h2>\r\n<h3>The += Operator</h3>\r\n\r\n<p id=\"demo\"></p>\r\n\r\n<script>\r\nlet text = \"Hello\";\r\ntext += \" World\";\r\ndocument.getElementById(\"demo\").innerHTML = text;\r\n</script>\r\n\r\n</body>\r\n</html>', 102, 0),
(238, 49, 'What is JavaScript?', 'JavaScript is the programming language of the web.\r\n\r\nIt can update and change both HTML and CSS.\r\n\r\nIt can calculate, manipulate and validate data.', '', 103, 0),
(239, 49, 'Why Study JavaScript?', 'JavaScript is one of the 3 languages all web developers must learn:\r\n\r\n   1. HTML to define the content of web pages\r\n\r\n   2. CSS to specify the layout of web pages\r\n\r\n   3. JavaScript to program the behavior of web pages', '', 103, 0),
(240, 49, 'JavaScript Can Change HTML Content', 'One of many JavaScript HTML methods is getElementById().\r\n\r\nThe example below \"finds\" an HTML element (with id=\"demo\"), and changes the element content (innerHTML) to \"Hello JavaScript\":', '<!DOCTYPE html>\r\n<html>\r\n<body>\r\n\r\n<h2>What Can JavaScript Do?</h2>\r\n\r\n<p id=\"demo\">JavaScript can change HTML content.</p>\r\n\r\n<button type=\"button\" onclick=\'document.getElementById(\"demo\").innerHTML = \"Hello JavaScript!\"\'>Click Me!</button>\r\n\r\n</body>\r\n</html>', 103, 0),
(241, 49, 'JavaScript Functions and Events', 'A JavaScript function is a block of JavaScript code, that can be executed when \"called\" for.\r\n\r\nFor example, a function can be called when an event occurs, like when the user clicks a button.', '', 104, 0),
(242, 49, 'JavaScript in <head> or <body>', 'You can place any number of scripts in an HTML document.\r\n\r\nScripts can be placed in the <body>, or in the <head> section of an HTML page, or in both.', '', 104, 0),
(243, 49, 'JavaScript in <head>', 'In this example, a JavaScript function is placed in the <head> section of an HTML page.\r\n\r\nThe function is invoked (called) when a button is clicked:', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<script>\r\nfunction myFunction() {\r\n  document.getElementById(\"demo\").innerHTML = \"Paragraph changed.\";\r\n}\r\n</script>\r\n</head>\r\n<body>\r\n\r\n<h2>Demo JavaScript in Head</h2>\r\n\r\n<p id=\"demo\">A Paragraph.</p>\r\n\r\n<button type=\"button\" onclick=\"myFunction()\">Try it</button>\r\n\r\n</body>\r\n</html>', 104, 0);

-- --------------------------------------------------------

--
-- Table structure for table `typing_levels`
--

CREATE TABLE `typing_levels` (
  `level_id` int(11) NOT NULL,
  `level_number` int(11) NOT NULL,
  `code_text` text NOT NULL,
  `time_limit` int(11) DEFAULT 60,
  `coin_reward` decimal(10,2) DEFAULT 0.50
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `typing_levels`
--

INSERT INTO `typing_levels` (`level_id`, `level_number`, `code_text`, `time_limit`, `coin_reward`) VALUES
(30, 1, '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>Page Title</title>\r\n</head>\r\n<body>\r\n\r\n</body>\r\n</html>', 90, 3.00),
(31, 2, '<h2>HTML Links</h2>\r\n<p>HTML links are defined with the a tag:</p>\r\n\r\n<a href=\"https://www.WevDev.com\">This is a link</a>', 60, 2.00),
(32, 3, '123456', 60, 2.00),
(33, 4, '123456789', 60, 0.50),
(34, 5, '111111', 60, 0.50),
(35, 5, '111111', 60, 0.50);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_date` datetime NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_level_progress`
--

CREATE TABLE `user_level_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `completed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_submissions`
--

CREATE TABLE `user_submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `submitted_code` longtext NOT NULL,
  `submission_time` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pending_review',
  `admin_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_submissions`
--

INSERT INTO `user_submissions` (`id`, `user_id`, `exercise_id`, `submitted_code`, `submission_time`, `status`, `admin_notes`) VALUES
(1, 29, 1, '11', '2025-06-08 23:12:52', 'pending_review', '123'),
(2, 29, 1, '11', '2025-06-08 23:12:57', 'pending_review', NULL),
(3, 29, 1, '11', '2025-06-08 23:13:06', 'pending_review', NULL),
(29, 29, 1, '123', '2025-06-08 23:41:30', 'pending_review', NULL),
(30, 29, 1, '123', '2025-06-08 23:42:53', 'pending_review', NULL),
(31, 29, 1, 'sajhddhsad', '2025-06-08 23:43:09', 'pending_review', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tb`
--
ALTER TABLE `admin_tb`
  ADD PRIMARY KEY (`tbl_user_id`),
  ADD KEY `year_id` (`year_id`),
  ADD KEY `sec_id` (`sec_id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `code_ex`
--
ALTER TABLE `code_ex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `part_id` (`part_id`);

--
-- Indexes for table `diff`
--
ALTER TABLE `diff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`quiz_id`);

--
-- Indexes for table `quiz_progress`
--
ALTER TABLE `quiz_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_quiz_id` (`quiz_id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_purchase`
--
ALTER TABLE `tbl_purchase`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `part_id` (`part_id`);

--
-- Indexes for table `tbl_score`
--
ALTER TABLE `tbl_score`
  ADD PRIMARY KEY (`score_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`tbl_user_id`);

--
-- Indexes for table `tutorial`
--
ALTER TABLE `tutorial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `typing_levels`
--
ALTER TABLE `typing_levels`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_level_progress`
--
ALTER TABLE `user_level_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `level_id` (`level_id`);

--
-- Indexes for table `user_submissions`
--
ALTER TABLE `user_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tb`
--
ALTER TABLE `admin_tb`
  MODIFY `tbl_user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `code_ex`
--
ALTER TABLE `code_ex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `diff`
--
ALTER TABLE `diff`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `quiz_progress`
--
ALTER TABLE `quiz_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=918;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_purchase`
--
ALTER TABLE `tbl_purchase`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_score`
--
ALTER TABLE `tbl_score`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `tbl_user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tutorial`
--
ALTER TABLE `tutorial`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT for table `typing_levels`
--
ALTER TABLE `typing_levels`
  MODIFY `level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_level_progress`
--
ALTER TABLE `user_level_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_submissions`
--
ALTER TABLE `user_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_tb`
--
ALTER TABLE `admin_tb`
  ADD CONSTRAINT `admin_tb_ibfk_1` FOREIGN KEY (`year_id`) REFERENCES `grades` (`id`);

--
-- Constraints for table `code_ex`
--
ALTER TABLE `code_ex`
  ADD CONSTRAINT `code_ex_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `code_ex_ibfk_2` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_quiz_id` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_tb` (`tbl_user_id`);

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_tb` (`tbl_user_id`);

--
-- Constraints for table `tbl_purchase`
--
ALTER TABLE `tbl_purchase`
  ADD CONSTRAINT `tbl_purchase_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_tb` (`tbl_user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_purchase_ibfk_2` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_submissions`
--
ALTER TABLE `user_submissions`
  ADD CONSTRAINT `user_submissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_tb` (`tbl_user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_submissions_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `code_ex` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
