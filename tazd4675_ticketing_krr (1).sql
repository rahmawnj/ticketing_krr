-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 26, 2026 at 11:08 AM
-- Server version: 11.4.9-MariaDB-cll-lve
-- PHP Version: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tazd4675_ticketing_krr`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transactions`
--

CREATE TABLE `detail_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `ppn` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ticket_code` varchar(255) DEFAULT NULL,
  `status` enum('open','close') NOT NULL DEFAULT 'open',
  `scanned` int(11) NOT NULL DEFAULT 0,
  `scanned_at` timestamp NULL DEFAULT NULL,
  `gate` int(11) DEFAULT NULL,
  `is_print` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_transactions`
--

INSERT INTO `detail_transactions` (`id`, `transaction_id`, `ticket_id`, `qty`, `total`, `ppn`, `created_at`, `updated_at`, `ticket_code`, `status`, `scanned`, `scanned_at`, `gate`, `is_print`) VALUES
(1, 1, 1, 1, 20000, 0.00, '2026-01-30 08:49:26', '2026-01-31 02:54:31', 'TKT20260130154926674', 'close', 1, '2026-01-30 09:36:02', 1, 0),
(2, 1, 4, 1, 50000, 0.00, '2026-01-30 08:49:27', '2026-01-30 08:49:27', 'TKT20260130154927608', 'open', 0, NULL, NULL, 0),
(4, 4, 1, 1, 20000, 0.00, '2026-01-30 09:47:17', '2026-01-30 09:47:47', 'TKT20260130164717186', 'close', 1, '2026-01-30 09:47:39', 1, 0),
(5, 4, 5, 1, 15000, 0.00, '2026-01-30 09:47:23', '2026-01-30 09:47:23', 'TKT20260130164723405', 'open', 0, NULL, NULL, 0),
(6, 5, 2, 1, 30000, 0.00, '2026-01-30 09:47:53', '2026-01-30 09:48:35', 'TKT20260130164753408', 'close', 1, '2026-01-30 09:48:29', 1, 0),
(7, 5, 3, 1, 40000, 0.00, '2026-01-30 09:47:54', '2026-01-30 09:47:54', 'TKT20260130164754467', 'open', 0, NULL, NULL, 0),
(8, 6, 1, 4, 80000, 0.00, '2026-01-30 09:48:30', '2026-01-30 09:49:12', 'TKT20260130164830774', 'close', 4, '2026-01-30 09:49:10', 1, 0),
(9, 9, 1, 1, 20000, 0.00, '2026-02-03 06:55:12', '2026-02-03 07:00:30', 'TKT20260203135512902', 'close', 1, '2026-02-03 06:57:09', 1, 0),
(10, 10, 1, 25, 500000, 0.00, '2026-02-03 06:59:36', '2026-02-03 06:59:42', 'TKT20260203135936787', 'open', 0, NULL, NULL, 0),
(11, 11, 1, 1, 20000, 0.00, '2026-02-03 07:01:02', '2026-02-03 07:01:02', 'TKT20260203140102797', 'open', 0, NULL, NULL, 0),
(12, 12, 1, 1, 20000, 0.00, '2026-02-03 07:01:13', '2026-02-03 07:05:34', 'TKT20260203140113504', 'close', 1, '2026-02-03 07:01:39', 1, 0),
(27, 14, 4, 6, 300000, 0.00, '2026-02-03 07:15:15', '2026-02-03 07:15:22', 'TKT20260203141515876', 'open', 0, NULL, NULL, 0),
(29, 16, 8, 1, 45000, 0.00, '2026-02-03 08:10:35', '2026-02-03 08:12:43', 'TKT20260203151035823', 'close', 1, '2026-02-03 08:11:23', 1, 0),
(30, 20, 8, 2, 90000, 0.00, '2026-02-04 03:28:21', '2026-02-04 03:29:21', 'TKT20260204102821199', 'close', 2, '2026-02-04 03:29:16', 1, 0),
(31, 25, 6, 3, 225000, 0.00, '2026-02-05 07:41:52', '2026-02-05 07:42:01', 'TKT20260205144152315', 'open', 0, NULL, NULL, 0),
(32, 25, 9, 4, 240000, 0.00, '2026-02-05 07:44:23', '2026-02-05 07:44:26', 'TKT20260205144423766', 'open', 0, NULL, NULL, 0),
(33, 27, 17, 2, 70000, 0.00, '2026-02-05 07:51:19', '2026-02-05 07:53:57', 'TKT20260205145119103', 'close', 2, '2026-02-05 07:52:50', 1, 0),
(36, 29, 17, 2, 70000, 0.00, '2026-02-05 07:52:52', '2026-02-05 07:52:54', 'TKT20260205145252236', 'open', 0, NULL, NULL, 0),
(37, 30, 17, 1, 35000, 0.00, '2026-02-05 07:56:02', '2026-02-05 07:56:02', 'TKT20260205145602897', 'open', 0, NULL, NULL, 0),
(39, 31, 8, 1, 45000, 0.00, '2026-02-05 07:58:15', '2026-02-05 07:59:41', 'TKT20260205145815731', 'open', 0, NULL, NULL, 0),
(40, 31, 17, 1, 35000, 0.00, '2026-02-05 07:58:24', '2026-02-05 08:00:28', 'TKT20260205145824655', 'open', 0, NULL, NULL, 0),
(41, 31, 17, 2, 70000, 0.00, '2026-02-05 08:00:13', '2026-02-05 08:00:29', 'TKT20260205150013900', 'open', 0, NULL, NULL, 0),
(43, 32, 22, 20, 700000, 0.00, '2026-02-05 08:05:47', '2026-02-05 08:05:58', 'TKT20260205150547244', 'open', 0, NULL, NULL, 0),
(44, 32, 13, 3, 60000, 0.00, '2026-02-05 08:10:04', '2026-02-05 08:10:07', 'TKT20260205151004678', 'open', 0, NULL, NULL, 0),
(45, 32, 8, 6, 270000, 0.00, '2026-02-05 08:10:05', '2026-02-05 08:10:11', 'TKT20260205151005415', 'open', 0, NULL, NULL, 0),
(54, 35, 17, 1, 35000, 0.00, '2026-02-10 01:37:27', '2026-02-10 01:39:40', 'TKT20260210083727474', 'close', 1, '2026-02-10 01:39:40', 1, 0),
(55, 38, 8, 1, 45000, 0.00, '2026-02-19 05:24:35', '2026-02-19 05:28:59', 'TKT20260219122435554', 'close', 1, '2026-02-19 05:28:42', 1, 0),
(56, 38, 13, 1, 20000, 0.00, '2026-02-19 05:24:40', '2026-02-19 05:24:40', 'TKT20260219122440173', 'open', 0, NULL, NULL, 0),
(57, 39, 8, 1, 45000, 0.00, '2026-02-19 05:25:27', '2026-02-19 05:25:27', 'TKT20260219122527225', 'open', 0, NULL, NULL, 0),
(58, 39, 13, 1, 20000, 0.00, '2026-02-19 05:25:27', '2026-02-19 05:25:27', 'TKT20260219122527418', 'open', 0, NULL, NULL, 0),
(62, 40, 8, 2, 90000, 0.00, '2026-02-19 05:29:47', '2026-02-19 05:31:05', 'TKT20260219122947401', 'close', 2, '2026-02-19 05:30:57', 1, 0),
(63, 41, 8, 1, 45000, 0.00, '2026-02-19 06:52:39', '2026-02-19 06:52:39', 'TKT20260219135239918', 'open', 0, NULL, NULL, 0),
(64, 43, 8, 2, 90000, 0.00, '2026-02-19 06:53:10', '2026-02-19 06:53:14', 'TKT20260219135310234', 'open', 0, NULL, NULL, 0),
(65, 44, 7, 1, 85000, 0.00, '2026-02-19 06:53:33', '2026-02-19 06:53:33', 'TKT20260219135333728', 'open', 0, NULL, NULL, 0),
(66, 44, 8, 1, 45000, 0.00, '2026-02-19 06:53:34', '2026-02-19 06:53:34', 'TKT20260219135334363', 'open', 0, NULL, NULL, 0),
(67, 45, 22, 125, 4375000, 0.00, '2026-02-19 06:53:54', '2026-02-19 06:54:01', 'TKT20260219135354973', 'open', 0, NULL, NULL, 0),
(68, 46, 14, 1, 100000, 0.00, '2026-02-20 02:13:35', '2026-02-20 02:13:35', 'TKT20260220091335750', 'open', 0, NULL, NULL, 0),
(69, 47, 16, 1, 350000, 0.00, '2026-02-20 02:16:41', '2026-02-20 02:16:41', 'TKT20260220091641990', 'open', 0, NULL, NULL, 0),
(72, 48, 8, 4, 180000, 0.00, '2026-02-20 02:25:42', '2026-02-20 02:25:48', 'TKT20260220092542726', 'open', 0, NULL, NULL, 0),
(73, 50, 14, 1, 100000, 0.00, '2026-02-20 03:09:44', '2026-02-20 03:09:44', 'TKT20260220100944900', 'open', 0, NULL, NULL, 0),
(74, 51, 14, 1, 100000, 0.00, '2026-02-20 03:10:24', '2026-02-20 03:10:24', 'TKT20260220101024628', 'open', 0, NULL, NULL, 0),
(75, 52, 14, 1, 100000, 0.00, '2026-02-20 03:10:53', '2026-02-20 03:10:53', 'TKT20260220101053140', 'open', 0, NULL, NULL, 0),
(77, 54, 16, 1, 350000, 0.00, '2026-02-20 05:21:18', '2026-02-20 05:23:41', 'TKT20260220122118599', 'close', 1, '2026-02-20 05:23:00', 1, 0),
(78, 53, 15, 4, 200000, 0.00, '2026-02-20 07:24:01', '2026-02-20 07:24:03', 'TKT20260220142401721', 'open', 0, NULL, NULL, 0),
(79, 55, 8, 2, 90000, 0.00, '2026-02-20 07:25:50', '2026-02-20 07:25:55', 'TKT20260220142550435', 'open', 0, NULL, NULL, 0),
(80, 56, 9, 2, 120000, 0.00, '2026-02-20 08:30:22', '2026-02-20 08:56:11', 'TKT20260220153022947', 'open', 2, NULL, NULL, 0),
(82, 56, 22, 2, 70000, 0.00, '2026-02-20 08:30:31', '2026-02-20 08:56:11', 'TKT20260220153031723', 'open', 2, NULL, NULL, 0),
(84, 61, 8, 1, 45000, 0.00, '2026-02-24 08:25:34', '2026-02-24 08:25:34', 'TKT20260224152534637', 'open', 0, NULL, NULL, 0),
(86, 63, 13, 1, 20000, 0.00, '2026-02-24 08:28:26', '2026-02-24 08:28:26', 'TKT20260224152826578', 'open', 0, NULL, NULL, 0),
(87, 64, 13, 1, 20000, 0.00, '2026-02-24 08:28:47', '2026-02-24 08:29:22', 'TKT20260224152847949', 'close', 1, '2026-02-24 08:29:11', 1, 0),
(88, 65, 14, 1, 100000, 0.00, '2026-02-24 08:29:20', '2026-02-24 08:29:20', 'TKT20260224152920899', 'open', 0, NULL, NULL, 0),
(89, 66, 8, 1, 45000, 0.00, '2026-02-24 08:29:45', '2026-02-24 08:29:45', 'TKT20260224152945895', 'open', 0, NULL, NULL, 0),
(90, 67, 14, 1, 100000, 0.00, '2026-02-24 08:34:54', '2026-02-24 08:34:54', 'TKT20260224153454654', 'open', 0, NULL, NULL, 0),
(91, 68, 14, 1, 100000, 0.00, '2026-02-24 08:47:25', '2026-02-24 08:47:25', 'TKT20260224154725288', 'open', 0, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gate_accesses`
--

CREATE TABLE `gate_accesses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gate_access_id` char(25) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gate_accesses`
--

INSERT INTO `gate_accesses` (`id`, `gate_access_id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '1', 'Main GATE', 1, '2026-01-30 07:41:51', '2026-01-30 08:28:04');

-- --------------------------------------------------------

--
-- Table structure for table `gate_access_membership`
--

CREATE TABLE `gate_access_membership` (
  `gate_access_id` bigint(20) UNSIGNED NOT NULL,
  `membership_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gate_access_membership`
--

INSERT INTO `gate_access_membership` (`gate_access_id`, `membership_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `histories`
--

CREATE TABLE `histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` int(11) DEFAULT 0,
  `gate` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `histories`
--

INSERT INTO `histories` (`id`, `member_id`, `gate`, `created_at`, `updated_at`, `waktu`, `user_id`) VALUES
(1, 1, 1, '2026-01-30 09:34:00', '2026-01-30 09:34:00', '2026-01-30 09:34:00', 0),
(2, 1, 1, '2026-01-30 09:34:22', '2026-01-30 09:34:22', '2026-01-30 09:34:22', 0),
(3, 1, 1, '2026-01-30 09:34:37', '2026-01-30 09:34:37', '2026-01-30 09:34:37', 0),
(4, 3, 1, '2026-01-30 09:35:22', '2026-01-30 09:35:22', '2026-01-30 09:35:22', 0),
(5, 3, 1, '2026-01-30 09:35:23', '2026-01-30 09:35:23', '2026-01-30 09:35:23', 0),
(6, 3, 1, '2026-01-30 09:35:24', '2026-01-30 09:35:24', '2026-01-30 09:35:24', 0),
(7, 1, 1, '2026-01-30 09:35:57', '2026-01-30 09:35:57', '2026-01-30 09:35:57', 0),
(8, 1, 1, '2026-01-30 09:42:25', '2026-01-30 09:42:25', '2026-01-30 09:42:25', 0),
(9, 1, 1, '2026-01-30 09:42:38', '2026-01-30 09:42:38', '2026-01-30 09:42:38', 0),
(10, 1, 1, '2026-01-30 09:46:49', '2026-01-30 09:46:49', '2026-01-30 09:46:49', 0),
(11, 1, 1, '2026-01-31 02:55:01', '2026-01-31 02:55:01', '2026-01-31 02:55:01', 0),
(12, 1, 1, '2026-01-31 02:55:06', '2026-01-31 02:55:06', '2026-01-31 02:55:06', 0),
(13, 1, 1, '2026-01-31 02:55:14', '2026-01-31 02:55:14', '2026-01-31 02:55:14', 0),
(14, 1, 1, '2026-01-31 03:11:50', '2026-01-31 03:11:50', '2026-01-31 03:11:50', 0),
(15, 5, 1, '2026-02-03 06:29:02', '2026-02-03 06:29:02', '2026-02-03 06:29:02', 0),
(16, 5, 1, '2026-02-03 06:29:12', '2026-02-03 06:29:12', '2026-02-03 06:29:12', 0),
(17, 5, 1, '2026-02-03 06:32:58', '2026-02-03 06:32:58', '2026-02-03 06:32:58', 0),
(18, 5, 1, '2026-02-03 06:33:04', '2026-02-03 06:33:04', '2026-02-03 06:33:04', 0),
(19, 13, 1, '2026-02-03 06:51:34', '2026-02-03 06:51:34', '2026-02-03 06:51:34', 0),
(20, 13, 1, '2026-02-03 06:51:54', '2026-02-03 06:51:54', '2026-02-03 06:51:54', 0),
(21, 5, 1, '2026-02-03 07:04:12', '2026-02-03 07:04:12', '2026-02-03 07:04:12', 0),
(22, 5, 1, '2026-02-03 07:04:21', '2026-02-03 07:04:21', '2026-02-03 07:04:21', 0),
(23, 5, 1, '2026-02-03 07:52:40', '2026-02-03 07:52:40', '2026-02-03 07:52:40', 0),
(24, 5, 1, '2026-02-03 07:52:50', '2026-02-03 07:52:50', '2026-02-03 07:52:50', 0),
(25, 5, 1, '2026-02-03 07:52:58', '2026-02-03 07:52:58', '2026-02-03 07:52:58', 0),
(26, 5, 1, '2026-02-03 07:53:05', '2026-02-03 07:53:05', '2026-02-03 07:53:05', 0),
(27, 5, 1, '2026-02-03 07:54:07', '2026-02-03 07:54:07', '2026-02-03 07:54:07', 0),
(28, 5, 1, '2026-02-03 07:54:12', '2026-02-03 07:54:12', '2026-02-03 07:54:12', 0),
(29, 5, 1, '2026-02-03 07:54:16', '2026-02-03 07:54:16', '2026-02-03 07:54:16', 0),
(30, 5, 1, '2026-02-03 07:54:20', '2026-02-03 07:54:20', '2026-02-03 07:54:20', 0),
(31, 5, 1, '2026-02-03 07:54:24', '2026-02-03 07:54:24', '2026-02-03 07:54:24', 0),
(32, 22, 1, '2026-02-20 03:17:39', '2026-02-20 03:17:39', '2026-02-20 03:17:39', 0),
(33, 22, 1, '2026-02-20 03:17:41', '2026-02-20 03:17:41', '2026-02-20 03:17:41', 0),
(34, 22, 1, '2026-02-20 03:17:45', '2026-02-20 03:17:45', '2026-02-20 03:17:45', 0),
(35, 22, 1, '2026-02-20 03:17:47', '2026-02-20 03:17:47', '2026-02-20 03:17:47', 0),
(36, 22, 1, '2026-02-20 03:17:51', '2026-02-20 03:17:51', '2026-02-20 03:17:51', 0),
(37, 22, 1, '2026-02-20 03:17:52', '2026-02-20 03:17:52', '2026-02-20 03:17:52', 0),
(38, 22, 1, '2026-02-20 03:32:40', '2026-02-20 03:32:40', '2026-02-20 03:32:40', 0),
(39, 22, 1, '2026-02-20 03:32:44', '2026-02-20 03:32:44', '2026-02-20 03:32:44', 0),
(40, 22, 1, '2026-02-20 03:32:48', '2026-02-20 03:32:48', '2026-02-20 03:32:48', 0),
(41, 22, 1, '2026-02-21 06:04:35', '2026-02-21 06:04:35', '2026-02-21 06:04:35', 0),
(42, 22, 1, '2026-02-21 06:04:42', '2026-02-21 06:04:42', '2026-02-21 06:04:42', 0),
(43, 22, 1, '2026-02-21 06:05:35', '2026-02-21 06:05:35', '2026-02-21 06:05:35', 0),
(44, 22, 1, '2026-02-21 06:05:36', '2026-02-21 06:05:36', '2026-02-21 06:05:36', 0),
(45, 22, 1, '2026-02-21 06:05:41', '2026-02-21 06:05:41', '2026-02-21 06:05:41', 0),
(46, 22, 1, '2026-02-21 06:06:25', '2026-02-21 06:06:25', '2026-02-21 06:06:25', 0),
(47, 22, 1, '2026-02-21 06:06:33', '2026-02-21 06:06:33', '2026-02-21 06:06:33', 0),
(48, 22, 1, '2026-02-21 06:07:10', '2026-02-21 06:07:10', '2026-02-21 06:07:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `history_memberships`
--

CREATE TABLE `history_memberships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `membership_id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `history_memberships`
--

INSERT INTO `history_memberships` (`id`, `membership_id`, `member_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
(2, 2, 2, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
(3, 2, 3, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
(4, 2, 4, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
(5, 1, 5, '2026-02-03', '2026-03-05', 'active', '2026-02-03 06:26:33', '2026-02-03 06:26:33'),
(12, 1, 13, '2026-02-03', '2026-03-05', 'active', '2026-02-03 06:50:14', '2026-02-03 06:50:14'),
(13, 1, 16, '2026-02-04', '2026-03-06', 'active', '2026-02-04 08:28:39', '2026-02-04 08:28:39'),
(14, 3, 17, '2026-02-19', '2026-03-22', 'active', '2026-02-19 05:34:29', '2026-02-19 05:34:29'),
(15, 3, 18, '2026-02-19', '2026-03-22', 'active', '2026-02-19 05:34:29', '2026-02-19 05:34:29'),
(16, 3, 19, '2026-02-19', '2026-03-22', 'active', '2026-02-19 05:34:29', '2026-02-19 05:34:29'),
(17, 3, 20, '2026-02-19', '2026-03-22', 'active', '2026-02-19 05:34:29', '2026-02-19 05:34:29'),
(18, 3, 21, '2026-02-19', '2026-03-22', 'active', '2026-02-19 05:34:29', '2026-02-19 05:34:29'),
(19, 1, 22, '2026-02-20', '2026-03-22', 'active', '2026-02-20 03:05:21', '2026-02-20 03:05:21');

-- --------------------------------------------------------

--
-- Table structure for table `history_penyewaans`
--

CREATE TABLE `history_penyewaans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `penyewaan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_tickets`
--

CREATE TABLE `jenis_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_tickets`
--

INSERT INTO `jenis_tickets` (`id`, `nama_jenis`, `created_at`, `updated_at`) VALUES
(1, 'Reguler', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(2, 'Terusan', '2026-01-30 07:32:23', '2026-01-30 07:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `limit_members`
--

CREATE TABLE `limit_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `limit` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) DEFAULT 0,
  `membership_id` bigint(20) DEFAULT 0,
  `rfid` varchar(255) DEFAULT NULL,
  `no_ktp` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `tgl_lahir` date NOT NULL,
  `tgl_register` date NOT NULL,
  `tgl_expired` date NOT NULL,
  `saldo` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 0,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `image_profile` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `limit` int(11) NOT NULL DEFAULT 0,
  `jenis_member` varchar(50) DEFAULT NULL,
  `access_used` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `parent_id`, `membership_id`, `rfid`, `no_ktp`, `no_hp`, `nama`, `alamat`, `tgl_lahir`, `tgl_register`, `tgl_expired`, `saldo`, `is_active`, `jenis_kelamin`, `image_profile`, `qr_code`, `created_at`, `updated_at`, `limit`, `jenis_member`, `access_used`) VALUES
(1, 0, 2, NULL, '3277654567825678', '089687695437', 'Budi', 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-04-30', 0, 1, 'L', NULL, 'MBRBVCAWZRZ7WXN2', '2026-01-30 08:48:59', '2026-01-30 08:48:59', 0, NULL, 0),
(2, 1, 2, NULL, '3277654567825678', '089687695437', 'Dina', 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-04-30', 0, 1, 'L', NULL, 'MBRVK4DFYKK4BCKU', '2026-01-30 08:48:59', '2026-01-30 08:48:59', 0, NULL, 0),
(3, 1, 2, NULL, '3277654567825678', '089687695437', 'Andi', 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-04-30', 0, 1, 'L', NULL, 'MBRPWQMAWVI7SSCF', '2026-01-30 08:48:59', '2026-01-30 08:48:59', 0, NULL, 0),
(4, 1, 2, NULL, '3277654567825678', '089687695437', 'Rara', 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-04-30', 0, 1, 'L', NULL, 'MBRJPG3DKVV3W5TO', '2026-01-30 08:48:59', '2026-01-30 08:48:59', 0, NULL, 0),
(5, 0, 1, NULL, '32068780301554', '081222466665', 'Gigink Nugraha', 'Bandung (testing)', '2026-02-03', '2026-02-03', '2026-03-05', 0, 1, 'L', NULL, 'MBRUFID13SWRDICQ', '2026-02-03 06:26:33', '2026-02-03 06:26:33', 0, NULL, 0),
(13, 0, 1, '723521', '1563165465', '09848465', 'kevin', 'jakarta', '2026-02-01', '2026-02-03', '2026-03-05', 0, 1, 'L', NULL, 'MBRJCBNWQFGP9HLD', '2026-02-03 06:50:14', '2026-02-03 06:50:14', 0, NULL, 0),
(16, 0, 1, NULL, '320104042', '08887776665', 'Tralala', 'tvm', '2000-01-05', '2026-02-04', '2026-03-06', 0, 1, 'P', 'members/XyMrG8QLR020260204152839.png', 'MBREUB03OYAQDCIT', '2026-02-04 08:28:39', '2026-02-04 08:28:39', 0, NULL, 0),
(17, 0, 3, '354736236', NULL, '2436464537698', 'Tenis Sport Club', 'sdfdxh mdfgdj', '2026-02-19', '2026-02-19', '2026-03-22', 0, 1, 'L', NULL, 'MBRAMQUYMRRNHRVR', '2026-02-19 05:34:29', '2026-02-19 05:34:29', 0, NULL, 0),
(18, 17, 3, '4673523424', NULL, '2436464537698', 'hjvghncvn', 'sdfdxh mdfgdj', '2026-02-19', '2026-02-19', '2026-03-22', 0, 1, 'L', NULL, 'MBRVXHEWRT2U3TLA', '2026-02-19 05:34:29', '2026-02-19 05:34:29', 0, NULL, 0),
(19, 17, 3, '542342424', NULL, '2436464537698', 'cvnvbmb', 'sdfdxh mdfgdj', '2026-02-19', '2026-02-19', '2026-03-22', 0, 1, 'L', NULL, 'MBRXRTPOMFNTNRRR', '2026-02-19 05:34:29', '2026-02-19 05:34:29', 0, NULL, 0),
(20, 17, 3, '242365477', NULL, '2436464537698', 'fhfchjg', 'sdfdxh mdfgdj', '2026-02-19', '2026-02-19', '2026-03-22', 0, 1, 'L', NULL, 'MBRBPOULKJMZPB5O', '2026-02-19 05:34:29', '2026-02-19 05:34:29', 0, NULL, 0),
(21, 17, 3, '343435465', NULL, '2436464537698', 'cjcgjkgkh', 'sdfdxh mdfgdj', '2026-02-19', '2026-02-19', '2026-03-22', 0, 1, 'L', NULL, 'MBRWAHTOSUBE7N8M', '2026-02-19 05:34:29', '2026-02-19 05:34:29', 0, NULL, 0),
(22, 0, 1, NULL, NULL, '08887776543', 'abdi', 'dki jakarta', '2000-02-19', '2026-02-20', '2026-03-22', 0, 1, 'L', NULL, 'MBROJFQXSBKKZZ2J', '2026-02-20 03:05:21', '2026-02-21 06:07:10', 0, NULL, 17);

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `price` double NOT NULL,
  `max_person` int(11) NOT NULL DEFAULT 1,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `use_ppn` tinyint(1) NOT NULL DEFAULT 0,
  `ppn` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `max_access` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `name`, `duration_days`, `price`, `max_person`, `is_active`, `use_ppn`, `ppn`, `created_at`, `updated_at`, `max_access`) VALUES
(1, 'Member Bulanan Reguler', 30, 150000, 1, 1, 0, 0.00, '2026-01-30 08:44:15', '2026-01-30 08:44:42', 0),
(2, 'Family Pass 3 Bulan', 90, 500000, 4, 1, 1, 0.00, '2026-01-30 08:45:02', '2026-01-30 08:45:02', 0),
(3, 'family 2 dewasa new', 31, 875000, 5, 1, 0, 0.00, '2026-02-03 06:43:24', '2026-02-03 06:43:24', 0),
(5, 'Admin Membership', 30, 100000, 1, 1, 0, 0.00, '2026-02-05 07:13:02', '2026-02-20 04:22:45', 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_02_16_091237_create_jenis_tickets_table', 1),
(6, '2023_02_16_091239_create_tickets_table', 1),
(7, '2023_02_16_091410_create_transactions_table', 1),
(8, '2023_04_06_022411_create_members_table', 1),
(9, '2023_04_06_030149_create_histories_table', 1),
(10, '2023_04_07_022739_create_sewas_table', 1),
(11, '2023_04_07_024121_create_penyewaans_table', 1),
(12, '2023_04_07_024401_create_history_penyewaans_table', 1),
(13, '2023_04_07_064151_create_permission_tables', 1),
(14, '2023_04_11_073712_create_terusans_table', 1),
(15, '2023_04_12_023911_create_detail_transactions_table', 1),
(16, '2023_04_13_065403_create_terusan_ticket_table', 1),
(17, '2023_04_15_030043_create_topups_table', 1),
(18, '2023_04_27_080534_add_user_id_to_penyewaans_table', 1),
(19, '2023_04_27_085330_add_no_hp_to_members_table', 1),
(20, '2023_04_27_101521_add_discount_to_transactions_table', 1),
(21, '2023_05_03_083958_add_metode_to_transactions_table', 1),
(22, '2023_12_03_210146_add_ticket_code_to_detail_transactions_table', 1),
(23, '2023_12_05_061401_add_is_print_to_transactions_table', 1),
(24, '2023_12_05_061824_add_is_print_to_detail_transactions_table', 1),
(25, '2023_12_05_115018_create_settings_table', 1),
(26, '2023_12_05_183504_add_use_logo_to_settings_table', 1),
(27, '2023_12_19_111103_add_bayar_to_transactions_table', 1),
(28, '2024_06_03_103029_create_limit_members_table', 1),
(29, '2024_06_04_181135_add_uid_to_users_table', 1),
(30, '2024_06_07_135533_add_waktu_to_histories_table', 1),
(31, '2024_06_10_145804_add_bayar_to_penyewaans_table', 1),
(32, '2024_06_11_144134_add_user_id_to_histories_table', 1),
(33, '2024_06_25_102231_add_limit_to_members_table', 1),
(34, '2024_06_25_103327_add_jenis_member_to_members_table', 1),
(35, '2025_10_31_203329_add_qr_code_to_members_table', 1),
(36, '2025_10_31_204653_create_memberships_table', 1),
(37, '2025_11_04_163914_create_gate_accesses_table', 1),
(38, '2025_11_05_104103_create_gate_access_membership_table', 1),
(39, '2025_11_10_105325_add_max_person_to_memberships_table', 1),
(40, '2025_11_10_194953_add_parent_id_to_members_table', 1),
(41, '2025_11_11_090340_create_history_memberships_table', 1),
(42, '2025_11_11_141124_add_scanned_at_to_detail_transactions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(1, 'App\\Models\\User', 5),
(1, 'App\\Models\\User', 6),
(1, 'App\\Models\\User', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyewaans`
--

CREATE TABLE `penyewaans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sewa_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `metode` enum('cash','debit','transfer','credit','qr','tap') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bayar` double NOT NULL,
  `kembali` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penyewaans`
--

INSERT INTO `penyewaans` (`id`, `sewa_id`, `user_id`, `qty`, `metode`, `jumlah`, `keterangan`, `start_time`, `end_time`, `created_at`, `updated_at`, `bayar`, `kembali`) VALUES
(1, 1, 1, 1, 'cash', 10000, NULL, NULL, NULL, '2026-01-30 08:49:19', '2026-01-30 08:49:19', 10000, 0),
(2, 1, 1, 1, 'cash', 10000, NULL, NULL, NULL, '2026-02-04 01:44:58', '2026-02-04 01:44:58', 10000, 0),
(3, 1, 1, 1, 'cash', 10000, NULL, NULL, NULL, '2026-02-04 06:31:20', '2026-02-04 06:31:20', 10000, 0),
(4, 1, 1, 1, 'cash', 10000, NULL, NULL, NULL, '2026-02-04 06:32:25', '2026-02-04 06:32:25', 10000, 0),
(5, 1, 1, 1, 'cash', 10000, NULL, NULL, NULL, '2026-02-05 07:40:39', '2026-02-05 07:40:39', 10000, 0),
(6, 1, 1, 1, 'debit', 10000, NULL, '16:21:00', '18:21:00', '2026-02-16 09:21:14', '2026-02-16 09:21:14', 10000, 0),
(7, 5, 1, 1, 'debit', 80000, 'Gazebo no 2', '15:41:00', '17:41:00', '2026-02-20 08:41:36', '2026-02-20 08:41:36', 80000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'master-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(2, 'user-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(3, 'ticket-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(4, 'sewa-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(5, 'member-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(6, 'transaction-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(7, 'penyewaan-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(8, 'topup-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(9, 'report-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(10, 'report-transaction-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(11, 'report-penyewaan-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(12, 'transaction-delete', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(13, 'penyewaan-delete', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(14, 'topup-delete', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(15, 'management-access', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
(2, 'Kasir', 'web', '2026-01-30 08:29:18', '2026-01-30 08:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(9, 2),
(10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `ucapan` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `ppn` int(11) DEFAULT NULL,
  `member_reminder_days` int(11) NOT NULL DEFAULT 7,
  `member_delete_grace_days` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `use_logo` int(11) NOT NULL DEFAULT 1,
  `print_mode` varchar(20) NOT NULL DEFAULT 'per_qty',
  `dashboard_metric_mode` varchar(20) NOT NULL DEFAULT 'amount',
  `whatsapp_enabled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `logo`, `ucapan`, `deskripsi`, `ppn`, `member_reminder_days`, `member_delete_grace_days`, `created_at`, `updated_at`, `use_logo`, `print_mode`, `dashboard_metric_mode`, `whatsapp_enabled`) VALUES
(1, 'ANWA PURI RESIDENCE SPORT CLUB', 'logo/260216042041133.png', 'Terima kasih atas kunjungan anda', 'WA: 0812xxxx | IG: @sportclub_id', 0, 7, 0, NULL, '2026-02-20 07:24:21', 1, 'per_ticket', 'count', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sewa`
--

CREATE TABLE `sewa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `device` int(11) NOT NULL,
  `use_time` tinyint(1) NOT NULL DEFAULT 1,
  `use_ppn` tinyint(1) NOT NULL DEFAULT 0,
  `ppn` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sewa`
--

INSERT INTO `sewa` (`id`, `name`, `harga`, `device`, `use_time`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
(1, 'Sewa Ban', 10000, 1, 1, 0, 0.00, '2026-01-30 07:32:23', '2026-02-20 04:22:42'),
(5, 'Sewa Gazebo', 80000, 1, 1, 0, 0.00, '2026-02-20 08:41:11', '2026-02-20 08:41:11');

-- --------------------------------------------------------

--
-- Table structure for table `terusans`
--

CREATE TABLE `terusans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `tripod` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `terusan_ticket`
--

CREATE TABLE `terusan_ticket` (
  `terusan_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jenis_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `tripod` int(11) NOT NULL,
  `use_ppn` tinyint(1) NOT NULL DEFAULT 0,
  `ppn` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `jenis_ticket_id`, `name`, `harga`, `tripod`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
(6, 1, 'Semua Fasilitas Weekday', 75000, 1, 0, 0.00, '2026-02-03 07:19:24', '2026-02-20 04:22:38'),
(7, 1, 'Semua Fasilitas Weekend', 85000, 1, 0, 0.00, '2026-02-03 07:20:11', '2026-02-03 07:20:11'),
(8, 1, 'Tiket Renang Weekday', 45000, 1, 0, 0.00, '2026-02-03 07:21:41', '2026-02-03 07:21:41'),
(9, 1, 'Tiket Renang Weekend', 60000, 1, 0, 0.00, '2026-02-03 07:21:57', '2026-02-03 07:21:57'),
(13, 1, 'Sewa Loker', 20000, 1, 0, 0.00, '2026-02-03 07:32:04', '2026-02-03 07:32:04'),
(14, 1, 'Sewa Gazebo (2 Jam)', 100000, 1, 0, 0.00, '2026-02-03 07:32:34', '2026-02-03 07:32:34'),
(15, 1, 'Sewa Gazebo (1 Jam)', 50000, 1, 0, 0.00, '2026-02-03 07:32:52', '2026-02-03 07:32:52'),
(16, 1, 'Paket Manula', 350000, 1, 0, 0.00, '2026-02-03 07:33:38', '2026-02-03 07:33:38'),
(17, 1, 'Voucher Renang', 35000, 1, 0, 0.00, '2026-02-03 07:35:40', '2026-02-03 07:35:40'),
(18, 1, 'Only Pingpong (Weekday)', 45000, 1, 0, 0.00, '2026-02-03 07:44:58', '2026-02-03 07:44:58'),
(19, 1, 'Only Pingpong (Weekend)', 60000, 1, 0, 0.00, '2026-02-03 07:45:28', '2026-02-03 07:45:28'),
(20, 1, 'IMPACT (4x Visit)', 700000, 1, 0, 0.00, '2026-02-03 07:46:05', '2026-02-03 07:46:05'),
(21, 1, 'IMPACT (8x Visit)', 1300000, 1, 0, 0.00, '2026-02-03 07:46:38', '2026-02-03 07:46:38'),
(22, 1, 'Tiket Rombongan (Weekday)', 35000, 1, 0, 0.00, '2026-02-03 07:47:37', '2026-02-03 07:47:37');

-- --------------------------------------------------------

--
-- Table structure for table `topups`
--

CREATE TABLE `topups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED DEFAULT 0,
  `member_id` bigint(20) UNSIGNED DEFAULT NULL,
  `member_info` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `no_trx` int(11) NOT NULL,
  `ticket_code` varchar(255) NOT NULL,
  `transaction_type` enum('renewal','ticket','registration','rental') NOT NULL DEFAULT 'ticket',
  `tipe` enum('group','individual') NOT NULL DEFAULT 'group',
  `amount` int(11) NOT NULL DEFAULT 0,
  `disc` int(11) NOT NULL DEFAULT 0,
  `metode` enum('cash','debit','kredit','qris') DEFAULT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `amount_scanned` int(11) NOT NULL DEFAULT 0,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `gate` int(11) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 0,
  `ppn` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_print` int(11) DEFAULT 0,
  `bayar` double NOT NULL DEFAULT 0,
  `kembali` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `ticket_id`, `member_id`, `member_info`, `user_id`, `no_trx`, `ticket_code`, `transaction_type`, `tipe`, `amount`, `disc`, `metode`, `discount`, `amount_scanned`, `status`, `gate`, `is_active`, `ppn`, `created_at`, `updated_at`, `is_print`, `bayar`, `kembali`) VALUES
(1, 0, NULL, NULL, 1, 1, 'INV/30012026/2373', 'ticket', 'group', 2, 7000, 'cash', 10, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:27:42', '2026-01-30 08:49:29', 0, 70000, 0),
(2, 2, NULL, NULL, 1, 2, 'REG/300120261845', 'registration', 'group', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:48:59', '2026-01-30 08:48:59', 0, 500000, 0),
(3, 1, NULL, NULL, 1, 3, 'TKT/1769762959', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:49:19', '2026-01-30 08:49:19', 0, 10000, 0),
(4, 0, NULL, NULL, 1, 4, 'INV/30012026/3937', 'ticket', 'group', 2, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:49:33', '2026-01-30 09:47:25', 0, 35000, 0),
(5, 0, NULL, NULL, 1, 5, 'INV/30012026/3659', 'ticket', 'group', 2, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-01-30 09:47:52', '2026-01-30 09:47:56', 0, 70000, 0),
(6, 0, NULL, NULL, 1, 6, 'INV/30012026/3828', 'ticket', 'group', 4, 0, 'cash', 0, 4, 'closed', NULL, 1, 0.00, '2026-01-30 09:48:29', '2026-01-30 09:49:10', 0, 80000, 0),
(7, 1, NULL, NULL, 1, 1, 'REG/030220267543', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-03 06:26:33', '2026-02-03 06:26:33', 0, 150000, 0),
(8, 1, NULL, NULL, 1, 2, 'REG/030220264587', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-03 06:50:14', '2026-02-03 06:50:14', 0, 150000, 0),
(9, 0, NULL, NULL, 1, 3, 'INV/03022026/5265', 'ticket', 'group', 1, 0, 'qris', 0, 1, 'closed', NULL, 1, 0.00, '2026-02-03 06:55:07', '2026-02-03 06:57:09', 0, 20000, 0),
(10, 0, NULL, NULL, 1, 4, 'INV/03022026/3815', 'ticket', 'group', 25, 0, 'qris', 0, 0, 'open', NULL, 1, 0.00, '2026-02-03 06:56:03', '2026-02-03 06:59:59', 0, 500000, 0),
(11, 0, NULL, NULL, 1, 5, 'INV/03022026/7444', 'ticket', 'group', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-03 07:00:08', '2026-02-03 07:01:04', 0, 20000, 0),
(12, 0, NULL, NULL, 1, 6, 'INV/03022026/8120', 'ticket', 'group', 1, 0, 'qris', 0, 1, 'closed', NULL, 1, 0.00, '2026-02-03 07:01:07', '2026-02-03 07:01:39', 0, 20000, 0),
(13, 0, NULL, NULL, 1, 7, 'INV/03022026/7680', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-03 07:01:21', '2026-02-03 07:01:21', 0, 0, 0),
(14, 0, NULL, NULL, 3, 8, 'INV/03022026/2658', 'ticket', 'group', 6, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-03 07:12:14', '2026-02-03 07:15:22', 0, 50000, 0),
(15, 0, NULL, NULL, 3, 9, 'INV/03022026/3178', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-03 07:15:25', '2026-02-03 07:15:25', 0, 0, 0),
(16, 0, NULL, NULL, 2, 10, 'INV/03022026/6686', 'ticket', 'group', 1, 0, 'qris', 0, 1, 'closed', NULL, 1, 0.00, '2026-02-03 08:10:32', '2026-02-03 08:11:23', 0, 45000, 0),
(17, 0, NULL, NULL, 2, 11, 'INV/03022026/8821', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-03 08:10:48', '2026-02-03 08:10:48', 0, 0, 0),
(18, 0, NULL, NULL, 1, 1, 'INV/04022026/1740', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-04 01:28:53', '2026-02-04 01:28:53', 0, 0, 0),
(19, 2, NULL, NULL, 1, 12, 'TKT/1770169498', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-04 01:44:58', '2026-02-04 01:44:58', 0, 10000, 0),
(20, 0, NULL, NULL, 3, 13, 'INV/04022026/3229', 'ticket', 'group', 2, 0, 'cash', 0, 2, 'closed', NULL, 1, 0.00, '2026-02-04 03:28:18', '2026-02-04 03:29:16', 0, 90000, 0),
(21, 0, NULL, NULL, 3, 14, 'INV/04022026/6720', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-04 03:28:44', '2026-02-04 03:28:44', 0, 0, 0),
(22, 3, NULL, NULL, 1, 15, 'TKT/1770186680', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-04 06:31:20', '2026-02-04 06:31:20', 0, 10000, 0),
(23, 4, NULL, NULL, 1, 16, 'TKT/1770186745', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-04 06:32:25', '2026-02-04 06:32:25', 0, 10000, 0),
(24, 1, NULL, NULL, 1, 17, 'REG/040220268423', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-04 08:28:39', '2026-02-04 08:28:39', 0, 150000, 0),
(25, 0, NULL, NULL, 1, 1, 'INV/05022026/3300', 'ticket', 'group', 7, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-05 03:36:30', '2026-02-05 07:44:27', 0, 465000, 0),
(26, 5, NULL, NULL, 1, 18, 'TKT/1770277239', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-05 07:40:39', '2026-02-05 07:40:39', 0, 10000, 0),
(27, 0, NULL, NULL, 2, 19, 'INV/05022026/5601', 'ticket', 'group', 2, 0, 'qris', 0, 2, 'closed', NULL, 1, 0.00, '2026-02-05 07:51:10', '2026-02-05 07:52:50', 0, 70000, 0),
(28, 0, NULL, NULL, 2, 20, 'INV/05022026/4661', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-05 07:52:23', '2026-02-05 07:52:23', 0, 0, 0),
(29, 0, NULL, NULL, 1, 21, 'INV/05022026/3386', 'ticket', 'group', 2, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-05 07:52:35', '2026-02-05 07:52:58', 0, 70000, 0),
(30, 0, NULL, NULL, 1, 22, 'INV/05022026/5403', 'ticket', 'group', 2, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-05 07:55:58', '2026-02-05 07:56:40', 0, 70000, 0),
(31, 0, NULL, NULL, 1, 23, 'INV/05022026/7278', 'ticket', 'group', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-05 07:58:12', '2026-02-05 08:00:45', 0, 150000, 0),
(32, 0, NULL, NULL, 1, 24, 'INV/05022026/3956', 'ticket', 'group', 29, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-05 08:05:23', '2026-02-05 08:10:14', 0, 1030000, 0),
(33, 0, NULL, NULL, 1, 25, 'INV/05022026/1327', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-05 08:15:59', '2026-02-05 08:15:59', 0, 0, 0),
(34, 0, NULL, NULL, 1, 1, 'INV/06022026/1415', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-05 23:41:30', '2026-02-05 23:41:30', 0, 0, 0),
(35, 0, NULL, NULL, 2, 1, 'INV/10022026/1272', 'ticket', 'group', 1, 0, 'qris', 0, 1, 'closed', NULL, 1, 0.00, '2026-02-10 01:37:21', '2026-02-10 01:39:40', 0, 35000, 0),
(36, 0, NULL, NULL, 2, 2, 'INV/10022026/5393', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-10 01:37:57', '2026-02-10 01:37:57', 0, 0, 0),
(37, 6, NULL, NULL, 1, 1, 'RENT/001', 'rental', 'individual', 1, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-16 09:21:14', '2026-02-16 09:21:14', 0, 10000, 0),
(38, 0, NULL, NULL, 2, 1, 'TKT/001', 'ticket', 'group', 2, 0, 'qris', 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 05:24:32', '2026-02-19 05:24:48', 0, 65000, 0),
(39, 0, NULL, NULL, 2, 2, 'TKT/002', 'ticket', 'group', 2, 0, 'qris', 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 05:25:16', '2026-02-19 05:25:32', 0, 65000, 0),
(40, 0, NULL, NULL, 2, 3, 'TKT/003', 'ticket', 'group', 2, 0, 'qris', 0, 2, 'closed', NULL, 1, 0.00, '2026-02-19 05:28:50', '2026-02-19 05:30:57', 0, 90000, 0),
(41, 0, NULL, NULL, 2, 4, 'TKT/004', 'ticket', 'group', 1, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 05:30:38', '2026-02-19 06:52:57', 0, 45000, 0),
(42, 3, 17, 'Tenis Sport Club - 2436464537698', 2, 1, 'REG/001', 'registration', 'group', 5, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 05:34:29', '2026-02-19 05:34:29', 0, 875000, 0),
(43, 0, NULL, NULL, 2, 5, 'TKT/005', 'ticket', 'group', 2, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 06:53:06', '2026-02-19 06:53:18', 0, 90000, 0),
(44, 0, NULL, NULL, 2, 6, 'TKT/006', 'ticket', 'group', 2, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 06:53:29', '2026-02-19 06:53:39', 0, 130000, 0),
(45, 0, NULL, NULL, 2, 7, 'TKT/007', 'ticket', 'group', 125, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 06:53:47', '2026-02-19 06:54:08', 0, 4375000, 0),
(46, 0, NULL, NULL, 3, 1, 'TKT/001', 'ticket', 'group', 1, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 02:13:32', '2026-02-20 02:13:40', 0, 100000, 0),
(47, 0, NULL, NULL, 3, 2, 'TKT/002', 'ticket', 'group', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 02:16:32', '2026-02-20 02:16:43', 0, 350000, 0),
(48, 0, NULL, NULL, 3, 3, 'TKT/003', 'ticket', 'group', 4, 0, 'qris', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 02:16:52', '2026-02-20 02:25:56', 0, 180000, 0),
(49, 1, 22, 'abdi - 08887776543', 1, 1, 'REG/001', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 03:05:21', '2026-02-20 03:05:21', 0, 150000, 0),
(50, 0, NULL, NULL, 1, 4, 'TKT/004', 'ticket', 'group', 1, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 03:08:39', '2026-02-20 03:10:00', 0, 100000, 0),
(51, 0, NULL, NULL, 1, 5, 'TKT/005', 'ticket', 'group', 1, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 03:10:17', '2026-02-20 03:10:30', 0, 100000, 0),
(52, 0, NULL, NULL, 1, 6, 'TKT/006', 'ticket', 'group', 1, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 03:10:47', '2026-02-20 03:11:02', 0, 100000, 0),
(53, 0, NULL, NULL, 1, 7, 'TKT/007', 'ticket', 'group', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 03:11:21', '2026-02-20 07:24:26', 0, 200000, 0),
(54, 0, NULL, NULL, 3, 8, 'TKT/008', 'ticket', 'group', 1, 0, 'cash', 0, 1, 'closed', NULL, 1, 0.00, '2026-02-20 03:25:04', '2026-02-20 05:23:00', 0, 350000, 0),
(55, 0, NULL, NULL, 3, 9, 'TKT/009', 'ticket', 'group', 2, 0, 'qris', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 05:22:12', '2026-02-20 07:26:01', 0, 90000, 0),
(56, 0, NULL, NULL, 1, 10, 'TKT/010', 'ticket', 'group', 4, 0, 'cash', 0, 0, 'closed', NULL, 1, 0.00, '2026-02-20 07:24:49', '2026-02-20 08:56:11', 0, 190000, 0),
(57, 0, NULL, NULL, 3, 11, 'TKT/011', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-20 07:27:34', '2026-02-20 07:27:34', 0, 0, 0),
(58, 7, NULL, NULL, 1, 1, 'RENT/001', 'rental', 'individual', 1, 0, 'debit', 0, 0, 'open', NULL, 1, 0.00, '2026-02-20 08:41:36', '2026-02-20 08:41:36', 0, 80000, 0),
(59, 0, NULL, NULL, 2, 12, 'TKT/012', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-20 09:18:18', '2026-02-20 09:18:18', 0, 0, 0),
(60, 0, NULL, NULL, 1, 13, 'TKT/013', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-20 09:48:19', '2026-02-20 09:48:19', 0, 0, 0),
(61, 0, NULL, NULL, 3, 1, 'TKT/001', 'ticket', 'group', 1, 0, 'qris', 0, 0, 'open', NULL, 1, 0.00, '2026-02-24 08:25:28', '2026-02-24 08:25:40', 0, 45000, 0),
(62, 0, NULL, NULL, 3, 2, 'TKT/002', 'ticket', 'group', 2, 0, 'qris', 0, 0, 'open', NULL, 1, 0.00, '2026-02-24 08:25:57', '2026-02-24 08:26:10', 0, 90000, 0),
(63, 0, NULL, NULL, 3, 3, 'TKT/003', 'ticket', 'group', 1, 0, 'qris', 0, 0, 'open', NULL, 1, 0.00, '2026-02-24 08:28:19', '2026-02-24 08:28:32', 0, 20000, 0),
(64, 0, NULL, NULL, 3, 4, 'TKT/004', 'ticket', 'group', 1, 0, 'qris', 0, 1, 'closed', NULL, 1, 0.00, '2026-02-24 08:28:37', '2026-02-24 08:29:11', 0, 20000, 0),
(65, 0, NULL, NULL, 3, 5, 'TKT/005', 'ticket', 'group', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-24 08:28:57', '2026-02-24 08:29:24', 0, 100000, 0),
(66, 0, NULL, NULL, 3, 6, 'TKT/006', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-24 08:29:31', '2026-02-24 08:29:31', 0, 0, 0),
(67, 0, NULL, NULL, 1, 7, 'TKT/007', 'ticket', 'group', 1, 0, 'qris', 0, 0, 'open', NULL, 1, 0.00, '2026-02-24 08:34:48', '2026-02-24 08:35:05', 0, 100000, 0),
(68, 0, NULL, NULL, 1, 8, 'TKT/008', 'ticket', 'group', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-24 08:35:33', '2026-02-24 08:47:34', 0, 100000, 0),
(69, 0, NULL, NULL, 1, 9, 'TKT/009', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-24 08:47:49', '2026-02-24 08:47:49', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `uid` char(30) DEFAULT NULL,
  `is_active` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `foto`, `created_at`, `updated_at`, `uid`, `is_active`) VALUES
(1, 'Super Admin', 'superadmin', '$2y$10$sswDhoSF0XL0ePqzbGcZJOHuLzwe5TezY5l9DYQvXPzdR2iB79EaK', NULL, '2026-01-30 07:32:23', '2026-01-30 08:26:42', NULL, 1),
(2, 'DIVA', 'DIVA', '$2y$10$mcPSv/7mCO010Go3kGJFOuarb53PATgaMeZi.pOzhe/FCdQkV9eVa', NULL, '2026-02-03 07:07:22', '2026-02-03 07:10:00', NULL, 1),
(3, 'KEVIN', 'KEVIN', '$2y$10$2C7obZUng.O47ULQdZpxBO3hQHiOS4CxQC2p0UFHe3En4c2UCnIr.', NULL, '2026-02-03 07:07:47', '2026-02-03 07:07:47', NULL, 1),
(4, 'CLAUDY', 'CLAUDY', '$2y$10$Im33dIUzyVvQFhM7mCblo.IgwpUO340vxIJ1q9lwjFjURfjoWq936', NULL, '2026-02-03 07:08:14', '2026-02-03 07:08:14', NULL, 1),
(5, 'MULI', 'MULI', '$2y$10$7achsqzC4tEZ9fI1n68S..SswRr8afcKBxehcg1ghOuaUMIPcVtrK', NULL, '2026-02-03 07:15:49', '2026-02-03 07:15:49', NULL, 1),
(6, 'HERNIE', 'HERNIE', '$2y$10$vM5u5YelgUUGJig/ktVaauElUq9/rIZkKf/A4dHasLabvp1Mlub..', NULL, '2026-02-03 07:16:11', '2026-02-03 07:16:11', NULL, 1),
(7, 'Vicky', 'vicky', '$2y$10$WbJO3FUzoNnFmtLK15s3.u6qyMXb/BqMh1uURGG1yxq1e8aNbttt2', NULL, '2026-02-20 07:13:46', '2026-02-20 07:13:46', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_notification_logs`
--

CREATE TABLE `whatsapp_notification_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'renewal_reminder, invoice',
  `member_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recipient_phone` varchar(25) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `retry_count` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `provider_response` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transactions`
--
ALTER TABLE `detail_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gate_accesses`
--
ALTER TABLE `gate_accesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gate_accesses_gate_access_id_unique` (`gate_access_id`);

--
-- Indexes for table `gate_access_membership`
--
ALTER TABLE `gate_access_membership`
  ADD KEY `gate_access_membership_gate_access_id_foreign` (`gate_access_id`),
  ADD KEY `gate_access_membership_membership_id_foreign` (`membership_id`);

--
-- Indexes for table `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_memberships`
--
ALTER TABLE `history_memberships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `history_memberships_membership_id_foreign` (`membership_id`),
  ADD KEY `history_memberships_member_id_foreign` (`member_id`);

--
-- Indexes for table `history_penyewaans`
--
ALTER TABLE `history_penyewaans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `history_penyewaans_member_id_foreign` (`member_id`),
  ADD KEY `history_penyewaans_penyewaan_id_foreign` (`penyewaan_id`);

--
-- Indexes for table `jenis_tickets`
--
ALTER TABLE `jenis_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `limit_members`
--
ALTER TABLE `limit_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `members_rfid_unique` (`rfid`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `penyewaans`
--
ALTER TABLE `penyewaans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penyewaans_sewa_id_foreign` (`sewa_id`),
  ADD KEY `penyewaans_user_id_foreign` (`user_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sewa`
--
ALTER TABLE `sewa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terusans`
--
ALTER TABLE `terusans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `terusans_tripod_unique` (`tripod`);

--
-- Indexes for table `terusan_ticket`
--
ALTER TABLE `terusan_ticket`
  ADD KEY `terusan_ticket_terusan_id_foreign` (`terusan_id`),
  ADD KEY `terusan_ticket_ticket_id_foreign` (`ticket_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_jenis_ticket_id_foreign` (`jenis_ticket_id`);

--
-- Indexes for table `topups`
--
ALTER TABLE `topups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topups_member_id_foreign` (`member_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_uid_unique` (`uid`);

--
-- Indexes for table `whatsapp_notification_logs`
--
ALTER TABLE `whatsapp_notification_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wa_logs_type_status` (`type`,`status`),
  ADD KEY `idx_wa_logs_member_id` (`member_id`),
  ADD KEY `idx_wa_logs_transaction_id` (`transaction_id`),
  ADD KEY `idx_wa_logs_recipient_phone` (`recipient_phone`),
  ADD KEY `idx_wa_logs_sent_at` (`sent_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transactions`
--
ALTER TABLE `detail_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gate_accesses`
--
ALTER TABLE `gate_accesses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `histories`
--
ALTER TABLE `histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `history_memberships`
--
ALTER TABLE `history_memberships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `history_penyewaans`
--
ALTER TABLE `history_penyewaans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_tickets`
--
ALTER TABLE `jenis_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `limit_members`
--
ALTER TABLE `limit_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `penyewaans`
--
ALTER TABLE `penyewaans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sewa`
--
ALTER TABLE `sewa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `terusans`
--
ALTER TABLE `terusans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `topups`
--
ALTER TABLE `topups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `whatsapp_notification_logs`
--
ALTER TABLE `whatsapp_notification_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gate_access_membership`
--
ALTER TABLE `gate_access_membership`
  ADD CONSTRAINT `gate_access_membership_gate_access_id_foreign` FOREIGN KEY (`gate_access_id`) REFERENCES `gate_accesses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gate_access_membership_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_memberships`
--
ALTER TABLE `history_memberships`
  ADD CONSTRAINT `history_memberships_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `history_memberships_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_penyewaans`
--
ALTER TABLE `history_penyewaans`
  ADD CONSTRAINT `history_penyewaans_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `history_penyewaans_penyewaan_id_foreign` FOREIGN KEY (`penyewaan_id`) REFERENCES `penyewaans` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyewaans`
--
ALTER TABLE `penyewaans`
  ADD CONSTRAINT `penyewaans_sewa_id_foreign` FOREIGN KEY (`sewa_id`) REFERENCES `sewa` (`id`),
  ADD CONSTRAINT `penyewaans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `terusan_ticket`
--
ALTER TABLE `terusan_ticket`
  ADD CONSTRAINT `terusan_ticket_terusan_id_foreign` FOREIGN KEY (`terusan_id`) REFERENCES `terusans` (`id`),
  ADD CONSTRAINT `terusan_ticket_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_jenis_ticket_id_foreign` FOREIGN KEY (`jenis_ticket_id`) REFERENCES `jenis_tickets` (`id`);

--
-- Constraints for table `topups`
--
ALTER TABLE `topups`
  ADD CONSTRAINT `topups_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
