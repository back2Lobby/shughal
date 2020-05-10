-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2020 at 09:16 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shughal2`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_body` text NOT NULL,
  `posted_by` varchar(25) NOT NULL,
  `posted_to` varchar(25) NOT NULL,
  `date_added` datetime NOT NULL,
  `removed` varchar(3) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_body`, `posted_by`, `posted_to`, `date_added`, `removed`, `post_id`) VALUES
(1, 'testing', 'talha_mughal', 'talha_mughal', '2020-03-31 17:21:18', 'no', 48),
(2, 'testing', 'talha_mughal', 'talha_mughal', '2020-03-31 17:21:19', 'no', 48),
(3, 'testing', 'talha_mughal', 'talha_mughal', '2020-03-31 17:21:20', 'no', 48),
(4, 'testing', 'talha_mughal', 'talha_mughal', '2020-03-31 17:21:21', 'no', 48),
(5, 'Hi Tony, How are you?', 'steve_rogers85', 'tony_stark', '2020-03-31 18:18:23', 'no', 41),
(6, 'Why you bully me?', 'talha_mughal', 'tony_stark', '2020-04-01 12:22:22', 'no', 41),
(7, 'ok', 'talha_mughal', 'talha_mughal', '2020-04-02 11:10:34', 'no', 46),
(8, 'This is new comment!!!', 'talha_mughal', 'talha_mughal', '2020-04-02 11:19:57', 'no', 47),
(9, 'new comment', 'talha_mughal', 'talha_mughal', '2020-04-02 11:20:49', 'no', 45),
(10, 'Say it again', 'talha_mughal', 'talha_mughal', '2020-04-02 11:25:52', 'no', 46),
(11, 'what?', 'talha_mughal', 'talha_mughal', '2020-04-02 11:26:15', 'no', 40),
(12, 'really?', 'talha_mughal', 'tony_stark', '2020-04-02 11:26:38', 'no', 39),
(13, 'ok then', 'talha_mughal', 'tony_stark', '2020-04-02 11:34:36', 'no', 39),
(14, 'nope', 'talha_mughal', 'tony_stark', '2020-04-02 11:35:56', 'no', 39),
(15, 'efghijkl', 'talha_mughal', 'talha_mughal', '2020-04-02 11:36:09', 'no', 37),
(16, 'abc', 'talha_mughal', 'talha_mughal', '2020-04-02 11:36:51', 'no', 47),
(17, 'no its 7', 'talha_mughal', 'talha_mughal', '2020-04-02 11:40:17', 'no', 45),
(18, 'right', 'talha_mughal', 'talha_mughal', '2020-04-02 11:41:45', 'no', 40),
(19, 'its mine', 'talha_mughal', 'talha_mughal', '2020-04-02 11:55:16', 'no', 47),
(20, 'asdf', 'talha_mughal', 'talha_mughal', '2020-04-02 11:55:52', 'no', 47),
(21, 'again test', 'talha_mughal', 'talha_mughal', '2020-04-02 11:57:14', 'no', 47),
(22, 'test', 'talha_mughal', 'talha_mughal', '2020-04-02 11:58:18', 'no', 48),
(23, 'Hello World!', 'talha_mughal', 'tony_stark', '2020-04-02 11:58:51', 'no', 41),
(24, 'Now Comment system is done!', 'talha_mughal', 'talha_mughal', '2020-04-03 12:06:46', 'no', 48),
(25, '', 'talha_mughal', 'talha_mughal', '2020-04-03 12:06:50', 'no', 48),
(26, 'Testing comment system', 'talha_mughal', 'tony_stark', '2020-04-03 12:08:43', 'no', 39),
(27, 'excellent bro', 'talha_mughal', 'talha_mughal', '2020-04-03 12:11:32', 'no', 45),
(28, 'yup', 'talha_mughal', 'talha_mughal', '2020-04-03 12:13:10', 'no', 35),
(29, 'what is this', 'talha_mughal', 'talha_mughal', '2020-04-03 12:14:18', 'no', 34),
(30, 'yes', 'talha_mughal', 'tony_stark', '2020-04-03 12:14:49', 'no', 5),
(31, 'right', 'talha_mughal', 'tony_stark', '2020-04-03 12:15:38', 'no', 5),
(32, 'testing', 'talha_mughal', 'talha_mughal', '2020-04-03 12:17:48', 'no', 40),
(33, 'abc', 'talha_mughal', 'talha_mughal', '2020-04-03 12:20:30', 'no', 40),
(34, 'captain', 'talha_mughal', 'tony_stark', '2020-04-03 12:21:58', 'no', 41),
(35, 'next', 'talha_mughal', 'tony_stark', '2020-04-03 12:22:12', 'no', 41),
(36, 'abcd', 'talha_mughal', 'tony_stark', '2020-04-03 12:22:49', 'no', 41),
(37, 'new test', 'talha_mughal', 'talha_mughal', '2020-04-03 12:23:15', 'no', 40),
(38, 'I just commented', 'talha_mughal', 'talha_mughal', '2020-04-03 12:23:44', 'no', 43),
(39, 'new test', 'talha_mughal', 'tony_stark', '2020-04-03 12:24:42', 'no', 41),
(40, 'last test', 'talha_mughal', 'tony_stark', '2020-04-03 12:25:06', 'no', 39),
(41, 'after last', 'talha_mughal', 'tony_stark', '2020-04-03 12:25:27', 'no', 39),
(42, 'yeah right', 'talha_mughal', 'talha_mughal', '2020-04-03 12:25:54', 'no', 40),
(43, 'The emergence and growth of blogs in the late 1990s coincided with the advent of web publishing tools that facilitated the posting of content by non-technical users who did not have much experience with HTML or computer programming.', 'talha_mughal', 'talha_mughal', '2020-04-03 12:28:15', 'no', 36),
(44, 'test', 'talha_mughal', 'talha_mughal', '2020-04-03 12:28:22', 'no', 36),
(45, 'yooo', 'talha_mughal', 'tony_stark', '2020-04-03 19:01:31', 'no', 41),
(46, 'new comment', 'steve_rogers85', 'steve_rogers85', '2020-04-06 11:34:44', 'no', 50),
(47, 'yes?', 'talha_mughal', 'talha_mughal', '2020-04-08 11:49:38', 'no', 44),
(48, 'new comment', 'talha_mughal', 'talha_mughal', '2020-04-08 11:52:46', 'no', 44),
(49, 'comment testing', 'tony_stark', 'talha_mughal', '2020-04-12 10:49:22', 'no', 54),
(50, 'new test', 'tony_stark', 'talha_mughal', '2020-04-12 10:49:54', 'no', 53);

-- --------------------------------------------------------

--
-- Table structure for table `forgot_password`
--

CREATE TABLE `forgot_password` (
  `id` int(11) NOT NULL,
  `email_addr` varchar(60) NOT NULL,
  `vkey` text NOT NULL,
  `time_requested` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `requester` text NOT NULL,
  `receiver` text NOT NULL,
  `result` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `username`, `post_id`) VALUES
(25, 'steve_rogers85', 41),
(107, 'talha_mughal', 41),
(110, 'steve_rogers85', 50),
(111, 'talha_mughal', 54),
(115, 'talha_mughal', 53),
(118, 'talha_mughal', 77),
(119, 'talha_mughal', 78),
(121, 'talha_mughal', 80),
(124, 'tony_stark', 79),
(125, 'talha_mughal', 82),
(126, 'tony_stark', 82),
(127, 'talha_mughal', 83);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `msg_body` text NOT NULL,
  `msg_from` varchar(40) NOT NULL,
  `msg_to` varchar(40) NOT NULL,
  `msg_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `seen` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `msg_body`, `msg_from`, `msg_to`, `msg_time`, `seen`) VALUES
(1, 'mytestpost', 'talha_mughal', 'tony_stark', '2020-04-26 06:14:32', 'yes'),
(5, 'Its first ramadan!', 'talha_mughal', 'karen_turner', '2020-04-26 06:14:38', 'yes'),
(6, 'again', 'talha_mughal', 'tony_stark', '2020-04-26 06:14:43', 'yes'),
(7, 'event listener test', 'talha_mughal', 'tony_stark', '2020-04-26 06:14:40', 'yes'),
(8, 'new testin', 'talha_mughal', 'karen_turner', '2020-04-26 06:14:50', 'yes'),
(9, 'last test', 'talha_mughal', 'karen_turner', '2020-04-26 06:14:46', 'yes'),
(10, 'are you tony?', 'talha_mughal', 'tony_stark', '2020-04-26 06:14:52', 'yes'),
(11, 'hello talha', 'tony_stark', 'talha_mughal', '2020-04-26 06:14:55', 'yes'),
(12, 'yeah?', 'talha_mughal', 'tony_stark', '2020-04-26 06:14:57', 'yes'),
(13, 'The word-wrap property allows long words to be able to be broken and wrap onto the next line.', 'talha_mughal', 'tony_stark', '2020-04-26 06:14:59', 'yes'),
(29, 'y not workin', 'talha_mughal', 'tony_stark', '2020-04-26 06:54:34', 'yes'),
(30, 'now what?', 'talha_mughal', 'tony_stark', '2020-04-26 06:57:15', 'yes'),
(31, 'again new message', 'talha_mughal', 'tony_stark', '2020-04-26 06:59:16', 'yes'),
(32, 'what?', 'talha_mughal', 'tony_stark', '2020-04-26 07:00:45', 'yes'),
(33, 'again', 'talha_mughal', 'tony_stark', '2020-04-26 07:02:23', 'yes'),
(34, 'last one', 'talha_mughal', 'tony_stark', '2020-04-26 07:03:27', 'yes'),
(35, 'what?', 'talha_mughal', 'tony_stark', '2020-04-26 07:04:41', 'yes'),
(36, 'why scroll not working?', 'talha_mughal', 'tony_stark', '2020-04-26 07:05:48', 'yes'),
(37, 'what?', 'talha_mughal', 'tony_stark', '2020-04-26 07:07:57', 'yes'),
(38, 'done?', 'talha_mughal', 'tony_stark', '2020-04-26 07:11:38', 'yes'),
(39, 'all done?', 'talha_mughal', 'tony_stark', '2020-04-26 07:23:28', 'yes'),
(40, 'yoo', 'talha_mughal', 'tony_stark', '2020-04-26 07:24:22', 'yes'),
(41, 'soo now what?', 'talha_mughal', 'tony_stark', '2020-04-26 07:25:11', 'yes'),
(42, 'new message from mobile', 'talha_mughal', 'tony_stark', '2020-04-26 12:10:49', 'yes'),
(43, 'oops', 'talha_mughal', 'tony_stark', '2020-05-02 10:38:06', 'yes'),
(44, 'pic test', 'talha_mughal', 'tony_stark', '2020-05-02 10:39:52', 'yes'),
(45, 'testing', 'talha_mughal', 'tony_stark', '2020-05-02 10:43:23', 'yes'),
(46, 'again test', 'talha_mughal', 'tony_stark', '2020-05-02 10:56:03', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `action_notif` text NOT NULL,
  `notif_to` varchar(40) NOT NULL,
  `notif_from` varchar(40) NOT NULL,
  `sender_profile` text NOT NULL,
  `post_id` int(11) NOT NULL,
  `seen` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `action_notif`, `notif_to`, `notif_from`, `sender_profile`, `post_id`, `seen`) VALUES
(151, 'liked your post', 'talha_mughal', 'tony_stark', 'assets/images/profile_pics/1588404843.png', 80, 'yes'),
(152, 'liked your post', 'talha_mughal', 'tony_stark', 'assets/images/profile_pics/1588404843.png', 79, 'yes'),
(153, 'liked your post', 'talha_mughal', 'tony_stark', 'assets/images/profile_pics/1588404843.png', 82, 'yes'),
(154, 'liked your post', 'tony_stark', 'talha_mughal', 'assets/images/profile_pics/1588230532.png', 83, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `body` text NOT NULL,
  `added_by` varchar(25) NOT NULL,
  `user_to` varchar(25) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `deleted` varchar(3) NOT NULL,
  `likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `added_by`, `user_to`, `date_added`, `user_closed`, `deleted`, `likes`) VALUES
(1, 'This is a dummy post', 'tony_stark', 'none', '2020-03-25 11:48:17', 'no', 'no', 0),
(2, 'This is a dummy post', 'tony_stark', 'none', '2020-03-25 11:49:03', 'no', 'no', 0),
(3, 'Forntite is better than PUBG', 'tony_stark', 'none', '2020-03-25 11:49:39', 'no', 'no', 0),
(4, 'Forntite is better than PUBG', 'tony_stark', 'none', '2020-03-25 11:52:00', 'no', 'no', 0),
(5, 'Forntite is better than PUBG', 'tony_stark', 'none', '2020-03-25 11:56:34', 'no', 'no', 0),
(6, 'I am Talha Mughal', 'talha_mughal', 'none', '2020-03-25 12:53:12', 'no', 'no', 0),
(32, 'Stay Home Stay Safe', 'talha_mughal', 'none', '2020-03-27 15:27:40', 'no', 'no', 0),
(33, 'Anyone wanna play fortnite?\r\n', 'talha_mughal', 'none', '2020-03-27 15:28:05', 'no', 'no', 0),
(34, 'The emergence and growth of blogs in the late 1990s coincided with the advent of web publishing tools that facilitated the posting of content by non-technical users who did not have much experience with HTML or computer programming.', 'talha_mughal', 'none', '2020-03-27 15:28:48', 'no', 'no', 0),
(35, 'Post Number 10', 'talha_mughal', 'none', '2020-03-27 15:29:11', 'no', 'no', 0),
(36, 'Subscribe Tricks Flood', 'talha_mughal', 'none', '2020-03-27 15:29:29', 'no', 'no', 0),
(37, 'abcdef', 'talha_mughal', 'none', '2020-03-27 15:29:57', 'no', 'no', 0),
(38, 'Shughal is the best site', 'talha_mughal', 'none', '2020-03-27 15:30:34', 'no', 'no', 0),
(39, 'Subscribe If you want the Victory Royal', 'tony_stark', 'none', '2020-03-27 19:27:16', 'no', 'no', 0),
(40, 'Don\'t give attention to Tony Stark, he is a fake account', 'talha_mughal', 'none', '2020-03-27 19:27:50', 'no', 'no', 0),
(41, 'You are also fake Talha Mughal', 'tony_stark', 'none', '2020-03-27 19:28:26', 'no', 'no', 2),
(42, 'Stay Home Stay Safe', 'talha_mughal', 'none', '2020-03-29 11:08:04', 'no', 'no', 0),
(43, 'Subscribe to my youtube channel', 'talha_mughal', 'none', '2020-03-29 11:08:25', 'no', 'no', 0),
(44, 'Hello!!!!', 'talha_mughal', 'none', '2020-03-29 11:08:39', 'no', 'no', 0),
(45, '2 + 2 = 5', 'talha_mughal', 'none', '2020-03-29 11:08:53', 'no', 'no', 0),
(46, 'testing extra post', 'talha_mughal', 'none', '2020-03-29 11:38:56', 'no', 'no', 0),
(47, 'Who created this website, it is awesome!!!', 'talha_mughal', 'none', '2020-03-29 13:05:01', 'no', 'no', 0),
(48, 'I just created the posts loading system here!', 'talha_mughal', 'none', '2020-03-30 12:28:09', 'no', 'no', 0),
(49, 'Hey! How are you friends?', 'steve_rogers85', 'none', '2020-03-30 12:32:58', 'no', 'no', 0),
(50, 'I am new to this website', 'steve_rogers85', 'none', '2020-03-30 12:33:08', 'no', 'no', 1),
(51, 'Got three solo victory royals', 'talha_mughal', 'none', '2020-04-05 12:00:47', 'no', 'no', 0),
(52, 'it is almost 70% completed', 'talha_mughal', 'none', '2020-04-05 12:02:06', 'no', 'no', 0),
(53, 'it is almost 70% completed', 'talha_mughal', 'none', '2020-04-05 12:02:14', 'no', 'no', 1),
(54, 'my new post', 'talha_mughal', 'none', '2020-04-06 11:33:58', 'no', 'no', 1),
(55, 'fixing bugs', 'tony_stark', 'none', '2020-04-26 19:46:19', 'no', 'no', 0),
(56, 'testing image upload', 'talha_mughal', 'none', '2020-05-02 13:43:02', 'no', 'no', 0),
(57, 'test again', 'talha_mughal', 'none', '2020-05-02 14:19:31', 'no', 'no', 0),
(58, 'not working', 'talha_mughal', 'none', '2020-05-02 14:21:54', 'no', 'no', 0),
(59, '95% completed', 'talha_mughal', 'none', '2020-05-02 14:22:32', 'no', 'no', 0),
(60, 'abcdef', 'talha_mughal', 'none', '2020-05-02 14:23:34', 'no', 'no', 0),
(61, 'abcdef', 'talha_mughal', 'none', '2020-05-02 14:24:14', 'no', 'no', 0),
(62, 'abcdef', 'talha_mughal', 'none', '2020-05-02 14:24:51', 'no', 'no', 0),
(63, 'abcdef', 'talha_mughal', 'none', '2020-05-02 14:25:13', 'no', 'no', 0),
(64, 'abcdef', 'talha_mughal', 'none', '2020-05-02 14:26:51', 'no', 'no', 0),
(65, 'now working?', 'talha_mughal', 'none', '2020-05-02 14:27:04', 'no', 'no', 0),
(66, 'abc', 'talha_mughal', 'none', '2020-05-02 14:29:06', 'no', 'no', 0),
(67, 'a little bug fixed', 'talha_mughal', 'none', '2020-05-02 14:29:17', 'no', 'no', 0),
(68, 'a little bug fixed', 'talha_mughal', 'none', '2020-05-02 14:29:37', 'no', 'no', 0),
(69, 'image upload working now', 'talha_mughal', 'none', '2020-05-02 14:33:11', 'no', 'no', 0),
(70, 'a bit problem', 'talha_mughal', 'none', '2020-05-02 14:34:47', 'no', 'no', 0),
(71, 'now its fine', 'talha_mughal', 'none', '2020-05-02 14:35:42', 'no', 'no', 0),
(72, 'oops', 'talha_mughal', 'none', '2020-05-02 14:36:03', 'no', 'no', 0),
(73, 'naaa', 'talha_mughal', 'none', '2020-05-02 14:37:47', 'no', 'no', 0),
(74, 'naaa', 'talha_mughal', 'none', '2020-05-02 14:39:11', 'no', 'no', 0),
(75, 'whaaat?', 'talha_mughal', 'none', '2020-05-02 14:41:40', 'no', 'no', 0),
(76, 'whaaat?', 'talha_mughal', 'none', '2020-05-02 14:44:11', 'no', 'no', 0),
(77, 'good job, its working now', 'talha_mughal', 'none', '2020-05-02 14:44:37', 'no', 'no', 1),
(78, 'new pic', 'talha_mughal', 'none', '2020-05-02 14:50:29', 'no', 'no', 1),
(79, 'abcdefghijklmnopq #stayhomestaysafe', 'talha_mughal', 'none', '2020-05-03 12:38:49', 'no', 'no', 1),
(80, '#ertugrlghazi', 'talha_mughal', 'none', '2020-05-03 12:40:25', 'no', 'no', 1),
(81, 'Making Trending system #shughalisbestsite', 'talha_mughal', 'none', '2020-05-03 12:40:46', 'no', 'no', 0),
(82, '#trendA #trendB', 'talha_mughal', 'none', '2020-05-03 13:04:55', 'no', 'no', 2),
(83, '#trendB', 'tony_stark', 'none', '2020-05-03 13:25:15', 'no', 'no', 1);

-- --------------------------------------------------------

--
-- Table structure for table `post_photo_uploads`
--

CREATE TABLE `post_photo_uploads` (
  `id` int(11) NOT NULL,
  `pic_path` varchar(80) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post_photo_uploads`
--

INSERT INTO `post_photo_uploads` (`id`, `pic_path`, `post_id`) VALUES
(1, 'assets/images/uploads/78.jpg', 78);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `signup_date` date NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `num_posts` int(11) NOT NULL,
  `num_likes` int(11) NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `friend_array` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `signup_date`, `profile_pic`, `num_posts`, `num_likes`, `user_closed`, `friend_array`) VALUES
(3, 'Tony', 'Stark', 'tony_stark', 'ironman@mcu.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-04', 'assets/images/profile_pics/1588230532.png', 34, 0, 'no', ',talha_mughal,'),
(4, 'Tony', 'Stark', 'tony_stark22', 'tony@mcu.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-04', 'assets/images/profile_pics/1588231432.png', 0, 0, 'no', ','),
(5, 'Steve', 'Rogers', 'steve_rogers85', 'rogers@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-04', 'assets/images/profile_pics/1588231204.png', 2, 0, 'no', ',talha_mughal,'),
(6, 'Jack', 'Flores', 'jack_flores', 'jackflores@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-04', 'assets/images/profile_pics/1588231655.png', 0, 0, 'no', ',talha_mughal,'),
(7, 'Talha', 'Mughal', 'talha_mughal', 'talha@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-05', 'assets/images/profile_pics/1588498176.png', 45, 0, 'no', ',tony_stark,karen_turner,jack_flores,steve_rogers85,'),
(8, 'Tayyab', 'Javaid', 'tayyab_javaid', 'tayyab@shughal.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-05', 'assets/images/profile_pics/defaults/head_pomegranate.png', 0, 0, 'no', ','),
(9, 'Abdulhadi', 'Mughal', 'abdulhadi_mughal', 'abdul@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-05', 'assets/images/profile_pics/1588231325.png', 0, 0, 'no', ','),
(10, 'Jason', 'Roy', 'jason_roy', 'jasonroy@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-05', 'assets/images/profile_pics/1588231840.png', 0, 0, 'no', ','),
(11, 'Alex', 'Hales', 'alex_hales', 'alex@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-05', 'assets/images/profile_pics/1588231770.png', 0, 0, 'no', ','),
(12, 'Ahmad', 'Jahangir', 'ahmad_jahangir', 'ahmad@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-06', 'assets/images/profile_pics/1588231096.png', 0, 0, 'no', ','),
(13, 'Justin', 'Wright', 'justin_wright', 'justin@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-06', 'assets/images/profile_pics/1588230958.png', 0, 0, 'no', ','),
(14, 'Karen', 'Turner', 'karen_turner', 'karen@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-03-06', 'assets/images/profile_pics/1588230852.png', 0, 0, 'no', ',talha_mughal,'),
(15, 'Tayyab', 'Javaid', 'tayyab_javaid95', 'tayyabisking1101@gmail.com', 'e99a18c428cb38d5f260853678922e03', '2020-05-02', 'assets/images/profile_pics/defaults/head_wet_asphalt.png', 0, 0, 'no', ',');

-- --------------------------------------------------------

--
-- Table structure for table `users_status`
--

CREATE TABLE `users_status` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `last_seen` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_status`
--

INSERT INTO `users_status` (`id`, `username`, `last_seen`) VALUES
(192, 'talha_mughal', '2020-05-04 07:11:09'),
(193, 'tony_stark', '2020-05-03 08:25:15'),
(194, 'karen_turner', '2020-04-30 07:14:35'),
(195, 'justin_wright', '2020-04-30 07:16:02'),
(196, 'ahmad_jahangir', '2020-04-30 07:19:25'),
(197, 'steve_rogers85', '2020-04-30 07:21:11'),
(198, 'abdulhadi_mughal', '2020-04-30 07:22:15'),
(199, 'tony_stark22', '2020-04-30 07:25:11'),
(200, 'jack_flores', '2020-04-30 07:28:46'),
(201, 'alex_hales', '2020-04-30 07:30:04'),
(202, 'jason_roy', '2020-04-30 07:39:54'),
(203, 'tayyab_javaid95', '2020-05-02 03:55:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_photo_uploads`
--
ALTER TABLE `post_photo_uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_status`
--
ALTER TABLE `users_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `forgot_password`
--
ALTER TABLE `forgot_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `post_photo_uploads`
--
ALTER TABLE `post_photo_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users_status`
--
ALTER TABLE `users_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
