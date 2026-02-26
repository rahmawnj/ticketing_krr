-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_membership.detail_transactions
CREATE TABLE IF NOT EXISTS `detail_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `ticket_id` bigint unsigned NOT NULL,
  `qty` int NOT NULL,
  `total` int NOT NULL,
  `ppn` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ticket_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('open','close') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `scanned` int NOT NULL DEFAULT '0',
  `scanned_at` timestamp NULL DEFAULT NULL,
  `gate` int DEFAULT NULL,
  `is_print` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.detail_transactions: ~10 rows (approximately)
DELETE FROM `detail_transactions`;
INSERT INTO `detail_transactions` (`id`, `transaction_id`, `ticket_id`, `qty`, `total`, `ppn`, `created_at`, `updated_at`, `ticket_code`, `status`, `scanned`, `scanned_at`, `gate`, `is_print`) VALUES
	(1, 1, 1, 1, 20000, 0.00, '2026-01-30 08:49:26', '2026-01-30 09:36:02', 'TKT20260130154926674', 'close', 1, '2026-01-30 09:36:02', 1, 0),
	(2, 1, 4, 1, 50000, 0.00, '2026-01-30 08:49:27', '2026-01-30 08:49:27', 'TKT20260130154927608', 'open', 0, NULL, NULL, 0),
	(3, 7, 1, 7, 140000, 0.00, '2026-02-10 02:11:40', '2026-02-10 02:11:46', 'TKT20260210091140603', 'open', 0, NULL, NULL, 0),
	(4, 7, 2, 6, 180000, 0.00, '2026-02-10 02:11:41', '2026-02-10 02:11:51', 'TKT20260210091141221', 'open', 0, NULL, NULL, 0),
	(5, 7, 5, 8, 120000, 0.00, '2026-02-10 02:11:42', '2026-02-10 02:11:56', 'TKT20260210091142683', 'open', 0, NULL, NULL, 0),
	(6, 25, 1, 5, 100000, 0.00, '2026-02-12 02:21:39', '2026-02-12 02:21:53', 'TKT20260212092139225', 'open', 0, NULL, NULL, 0),
	(7, 25, 4, 5, 250000, 0.00, '2026-02-12 02:21:40', '2026-02-12 02:21:49', 'TKT20260212092140218', 'open', 0, NULL, NULL, 0),
	(8, 34, 4, 1, 50000, 0.00, '2026-02-16 07:44:07', '2026-02-16 07:44:07', 'TKT20260216144407878', 'open', 0, NULL, NULL, 0),
	(9, 34, 1, 1, 20000, 0.00, '2026-02-16 07:44:07', '2026-02-16 07:44:07', 'TKT20260216144407162', 'open', 0, NULL, NULL, 0),
	(10, 47, 4, 1, 50000, 0.00, '2026-02-24 04:13:53', '2026-02-24 04:13:53', 'TKT20260224111353582', 'open', 0, NULL, NULL, 0),
	(11, 47, 1, 1, 20000, 0.00, '2026-02-24 04:13:53', '2026-02-24 04:13:53', 'TKT20260224111353523', 'open', 0, NULL, NULL, 0),
	(12, 51, 4, 1, 50000, 0.00, '2026-02-25 05:01:18', '2026-02-25 05:01:18', 'TKT20260225120118819', 'open', 0, NULL, NULL, 0),
	(13, 52, 1, 1, 20000, 0.00, '2026-02-25 05:13:00', '2026-02-25 05:13:00', 'TKT20260225121300804', 'open', 0, NULL, NULL, 0);

-- Dumping structure for table db_membership.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table db_membership.gate_accesses
CREATE TABLE IF NOT EXISTS `gate_accesses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gate_access_id` char(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gate_accesses_gate_access_id_unique` (`gate_access_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.gate_accesses: ~1 rows (approximately)
DELETE FROM `gate_accesses`;
INSERT INTO `gate_accesses` (`id`, `gate_access_id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, '1', 'Main GATE', 1, '2026-01-30 07:41:51', '2026-01-30 08:28:04');

-- Dumping structure for table db_membership.gate_access_membership
CREATE TABLE IF NOT EXISTS `gate_access_membership` (
  `gate_access_id` bigint unsigned NOT NULL,
  `membership_id` bigint unsigned NOT NULL,
  KEY `gate_access_membership_gate_access_id_foreign` (`gate_access_id`),
  KEY `gate_access_membership_membership_id_foreign` (`membership_id`),
  CONSTRAINT `gate_access_membership_gate_access_id_foreign` FOREIGN KEY (`gate_access_id`) REFERENCES `gate_accesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gate_access_membership_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.gate_access_membership: ~2 rows (approximately)
DELETE FROM `gate_access_membership`;
INSERT INTO `gate_access_membership` (`gate_access_id`, `membership_id`) VALUES
	(1, 1),
	(1, 2),
	(1, 3);

-- Dumping structure for table db_membership.histories
CREATE TABLE IF NOT EXISTS `histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int DEFAULT '0',
  `gate` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT NULL,
  `user_id` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.histories: ~8 rows (approximately)
DELETE FROM `histories`;
INSERT INTO `histories` (`id`, `member_id`, `gate`, `created_at`, `updated_at`, `waktu`, `user_id`) VALUES
	(1, 1, 1, '2026-01-30 09:34:00', '2026-01-30 09:34:00', '2026-01-30 09:34:00', 0),
	(2, 1, 1, '2026-01-30 09:34:22', '2026-01-30 09:34:22', '2026-01-30 09:34:22', 0),
	(3, 1, 1, '2026-01-30 09:34:37', '2026-01-30 09:34:37', '2026-01-30 09:34:37', 0),
	(4, 3, 1, '2026-01-30 09:35:22', '2026-01-30 09:35:22', '2026-01-30 09:35:22', 0),
	(5, 3, 1, '2026-01-30 09:35:23', '2026-01-30 09:35:23', '2026-01-30 09:35:23', 0),
	(6, 3, 1, '2026-01-30 09:35:24', '2026-01-30 09:35:24', '2026-01-30 09:35:24', 0),
	(7, 1, 1, '2026-01-30 09:35:57', '2026-01-30 09:35:57', '2026-01-30 09:35:57', 0);

-- Dumping structure for table db_membership.history_memberships
CREATE TABLE IF NOT EXISTS `history_memberships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `membership_id` bigint unsigned NOT NULL,
  `member_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `history_memberships_membership_id_foreign` (`membership_id`),
  KEY `history_memberships_member_id_foreign` (`member_id`),
  CONSTRAINT `history_memberships_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `history_memberships_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.history_memberships: ~12 rows (approximately)
DELETE FROM `history_memberships`;
INSERT INTO `history_memberships` (`id`, `membership_id`, `member_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
	(2, 2, 2, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
	(3, 2, 3, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
	(4, 2, 4, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
	(19, 2, 1, '2026-02-12', '2026-05-13', 'active', '2026-02-10 03:36:17', '2026-02-10 03:36:17'),
	(20, 2, 2, '2026-02-12', '2026-05-13', 'active', '2026-02-10 03:36:17', '2026-02-10 03:36:17'),
	(21, 2, 3, '2026-02-12', '2026-05-13', 'active', '2026-02-10 03:36:17', '2026-02-10 03:36:17'),
	(22, 2, 4, '2026-02-12', '2026-05-13', 'active', '2026-02-10 03:36:17', '2026-02-10 03:36:17'),
	(39, 2, 1, '2026-02-19', '2026-05-20', 'active', '2026-02-19 07:52:17', '2026-02-19 07:52:17'),
	(40, 2, 2, '2026-02-19', '2026-05-20', 'active', '2026-02-19 07:52:17', '2026-02-19 07:52:17'),
	(41, 2, 3, '2026-02-19', '2026-05-20', 'active', '2026-02-19 07:52:17', '2026-02-19 07:52:17'),
	(42, 2, 4, '2026-02-19', '2026-05-20', 'active', '2026-02-19 07:52:17', '2026-02-19 07:52:17'),
	(54, 3, 19, '2026-02-24', '2026-03-01', 'active', '2026-02-24 05:45:14', '2026-02-24 05:45:14'),
	(55, 3, 19, '2026-03-02', '2026-03-07', 'active', '2026-02-25 05:36:24', '2026-02-25 05:36:24'),
	(56, 1, 20, '2026-02-25', '2026-03-27', 'active', '2026-02-25 05:41:35', '2026-02-25 05:41:35'),
	(57, 2, 1, '2026-02-25', '2026-05-26', 'active', '2026-02-25 05:48:14', '2026-02-25 05:48:14'),
	(58, 2, 2, '2026-02-25', '2026-05-26', 'active', '2026-02-25 05:48:14', '2026-02-25 05:48:14'),
	(59, 2, 3, '2026-02-25', '2026-05-26', 'active', '2026-02-25 05:48:14', '2026-02-25 05:48:14'),
	(60, 2, 4, '2026-02-25', '2026-05-26', 'active', '2026-02-25 05:48:14', '2026-02-25 05:48:14'),
	(61, 1, 21, '2026-02-25', '2026-03-27', 'active', '2026-02-25 05:52:29', '2026-02-25 05:52:29'),
	(62, 1, 22, '2026-02-25', '2026-03-27', 'active', '2026-02-25 05:53:00', '2026-02-25 05:53:00'),
	(63, 3, 23, '2026-02-25', '2026-03-02', 'active', '2026-02-25 05:55:15', '2026-02-25 05:55:15'),
	(64, 3, 23, '2026-03-03', '2026-03-08', 'active', '2026-02-25 05:55:51', '2026-02-25 05:55:51'),
	(65, 3, 19, '2026-02-25', '2026-03-02', 'active', '2026-02-25 08:57:45', '2026-02-25 08:57:45'),
	(66, 1, 20, '2026-02-25', '2026-03-27', 'active', '2026-02-25 09:02:55', '2026-02-25 09:02:55');

-- Dumping structure for table db_membership.history_penyewaans
CREATE TABLE IF NOT EXISTS `history_penyewaans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned NOT NULL,
  `penyewaan_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `history_penyewaans_member_id_foreign` (`member_id`),
  KEY `history_penyewaans_penyewaan_id_foreign` (`penyewaan_id`),
  CONSTRAINT `history_penyewaans_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  CONSTRAINT `history_penyewaans_penyewaan_id_foreign` FOREIGN KEY (`penyewaan_id`) REFERENCES `penyewaans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.history_penyewaans: ~0 rows (approximately)
DELETE FROM `history_penyewaans`;

-- Dumping structure for table db_membership.jenis_tickets
CREATE TABLE IF NOT EXISTS `jenis_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.jenis_tickets: ~2 rows (approximately)
DELETE FROM `jenis_tickets`;
INSERT INTO `jenis_tickets` (`id`, `nama_jenis`, `created_at`, `updated_at`) VALUES
	(1, 'Reguler', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
	(2, 'Terusan', '2026-01-30 07:32:23', '2026-01-30 07:32:23');

-- Dumping structure for table db_membership.limit_members
CREATE TABLE IF NOT EXISTS `limit_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `limit` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.limit_members: ~0 rows (approximately)
DELETE FROM `limit_members`;

-- Dumping structure for table db_membership.members
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT '0',
  `membership_id` bigint DEFAULT '0',
  `member_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_ktp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_used` int NOT NULL DEFAULT '0',
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `tgl_register` date NOT NULL,
  `tgl_expired` date NOT NULL,
  `saldo` int NOT NULL DEFAULT '0',
  `is_active` int NOT NULL DEFAULT '0',
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `limit` int NOT NULL DEFAULT '0',
  `jenis_member` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_member_code_unique` (`member_code`),
  UNIQUE KEY `members_rfid_unique` (`rfid`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.members: ~4 rows (approximately)
DELETE FROM `members`;
INSERT INTO `members` (`id`, `parent_id`, `membership_id`, `member_code`, `rfid`, `no_ktp`, `no_hp`, `nama`, `access_used`, `alamat`, `tgl_lahir`, `tgl_register`, `tgl_expired`, `saldo`, `is_active`, `jenis_kelamin`, `image_profile`, `qr_code`, `created_at`, `updated_at`, `limit`, `jenis_member`) VALUES
	(1, 0, 2, 'MBR000001', NULL, '3277654567825678', '6289637761500', 'Budi', 0, 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-02-26', 0, 1, 'L', NULL, 'MBRBVCAWZRZ7WXN2', '2026-01-30 08:48:59', '2026-02-25 05:48:14', 0, NULL),
	(2, 1, 2, 'MBR000002', NULL, '3277654567825678', '6289637761500', 'Dina', 0, 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-02-26', 0, 1, 'L', NULL, 'MBRVK4DFYKK4BCKU', '2026-01-30 08:48:59', '2026-02-25 05:48:14', 0, NULL),
	(3, 1, 2, 'MBR000003', NULL, '3277654567825678', '6289637761500', 'Andi', 0, 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-02-26', 0, 1, 'L', NULL, 'MBRPWQMAWVI7SSCF', '2026-01-30 08:48:59', '2026-02-25 05:48:14', 0, NULL),
	(4, 1, 1, 'MBR000004', NULL, '3277654567825678', '6289637761500', 'Rara', 0, 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-02-26', 0, 1, 'L', NULL, 'MBRJPG3DKVV3W5TO', '2026-01-30 08:48:59', '2026-02-25 05:48:14', 0, NULL),
	(19, 0, 3, 'MSH00300001A', NULL, '7879', '08987654', 'Anggie', 0, 'Bandung', '2026-02-24', '2026-02-24', '2026-03-02', 0, 1, 'L', NULL, 'MBRTOBHEE4MS9MOJ', '2026-02-24 05:45:14', '2026-02-25 08:57:45', 0, NULL),
	(20, 0, 1, 'MBR/0020', '22222222222', '575', '08954754776', 'Rahma', 0, 'Bandung', '2026-02-18', '2026-02-25', '2026-03-27', 0, 1, 'L', NULL, 'MBR/0020', '2026-02-25 05:41:35', '2026-02-25 09:02:55', 0, NULL),
	(21, 0, 1, 'MBR/0021', NULL, NULL, '646564', 'Kino', 0, 'Cimahi', '2026-02-17', '2026-02-25', '2026-02-27', 0, 1, 'L', NULL, 'MBR/0021', '2026-02-25 05:52:29', '2026-02-25 05:52:29', 0, NULL),
	(22, 0, 1, 'MBR/0022', NULL, '908980', '9059043859403', 'Jiju', 0, 'Cimahi', '2026-02-18', '2026-02-25', '2026-02-27', 0, 1, 'L', NULL, 'MBR/0022', '2026-02-25 05:53:00', '2026-02-25 05:53:00', 0, NULL),
	(23, 0, 3, 'MV/0023', NULL, '8943', '8048204', 'DImaski', 0, 'Cimahi', '2026-02-18', '2026-02-25', '2026-02-08', 0, 1, 'L', NULL, 'MV/0023', '2026-02-25 05:55:15', '2026-02-25 05:55:51', 0, NULL);

-- Dumping structure for table db_membership.memberships
CREATE TABLE IF NOT EXISTS `memberships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_days` int NOT NULL,
  `price` double NOT NULL,
  `max_person` int NOT NULL DEFAULT '1',
  `is_active` int NOT NULL DEFAULT '1',
  `use_ppn` tinyint(1) NOT NULL DEFAULT '0',
  `ppn` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `max_access` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.memberships: ~3 rows (approximately)
DELETE FROM `memberships`;
INSERT INTO `memberships` (`id`, `name`, `code`, `duration_days`, `price`, `max_person`, `is_active`, `use_ppn`, `ppn`, `created_at`, `updated_at`, `max_access`) VALUES
	(1, 'Member Bulanan Reguler', 'MBR', 30, 150000, 1, 1, 0, 0.00, '2026-01-30 08:44:15', '2026-02-24 07:36:03', 10),
	(2, 'Family Pass 3 Bulan', 'FP', 90, 500000, 4, 1, 1, 0.00, '2026-01-30 08:45:02', '2026-02-24 07:36:11', 5),
	(3, 'Member VIP', 'MV', 5, 20000, 1, 1, 0, 0.00, '2026-02-12 02:11:15', '2026-02-24 07:35:55', 0);

-- Dumping structure for table db_membership.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.migrations: ~42 rows (approximately)
DELETE FROM `migrations`;
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

-- Dumping structure for table db_membership.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.model_has_permissions: ~0 rows (approximately)
DELETE FROM `model_has_permissions`;

-- Dumping structure for table db_membership.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.model_has_roles: ~1 rows (approximately)
DELETE FROM `model_has_roles`;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2);

-- Dumping structure for table db_membership.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.password_resets: ~0 rows (approximately)
DELETE FROM `password_resets`;

-- Dumping structure for table db_membership.penyewaans
CREATE TABLE IF NOT EXISTS `penyewaans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sewa_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `metode` enum('cash','debit','transfer','credit','qr','tap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bayar` double NOT NULL,
  `kembali` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `penyewaans_sewa_id_foreign` (`sewa_id`),
  KEY `penyewaans_user_id_foreign` (`user_id`),
  CONSTRAINT `penyewaans_sewa_id_foreign` FOREIGN KEY (`sewa_id`) REFERENCES `sewa` (`id`),
  CONSTRAINT `penyewaans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.penyewaans: ~11 rows (approximately)
DELETE FROM `penyewaans`;
INSERT INTO `penyewaans` (`id`, `sewa_id`, `user_id`, `qty`, `metode`, `jumlah`, `keterangan`, `start_time`, `end_time`, `created_at`, `updated_at`, `bayar`, `kembali`) VALUES
	(1, 1, 1, 1, 'cash', 10000, NULL, NULL, NULL, '2026-01-30 08:49:19', '2026-01-30 08:49:19', 10000, 0),
	(2, 2, 1, 4, 'cash', 40000, NULL, NULL, NULL, '2026-02-10 04:13:07', '2026-02-10 04:13:07', 40000, 0),
	(3, 1, 1, 4, 'cash', 40000, NULL, NULL, NULL, '2026-02-10 04:13:53', '2026-02-10 04:13:53', 40000, 0),
	(4, 4, 1, 1, 'cash', 10000, 'j\r\n\r\n\r\nhggj', '12:16:00', '14:16:00', '2026-02-10 05:16:57', '2026-02-10 05:16:57', 10000, 0),
	(5, 4, 1, 1, 'cash', 10000, '86876', '12:18:00', '14:18:00', '2026-02-10 05:18:10', '2026-02-10 05:18:10', 10000, 0),
	(6, 4, 1, 1, 'cash', 10000, '86876', '12:19:00', '14:18:00', '2026-02-10 05:19:22', '2026-02-10 05:19:22', 10000, 0),
	(7, 1, 1, 1, 'credit', 10000, 'Dinda\r\n\r\nLoker 31', '15:09:00', NULL, '2026-02-10 08:09:26', '2026-02-10 08:09:26', 10000, 0),
	(8, 2, 1, 5, 'debit', 50000, 'Dinar\r\n\r\nLOker 31', '15:13:00', NULL, '2026-02-10 08:13:27', '2026-02-10 08:13:27', 50000, 0),
	(9, 4, 1, 1, 'debit', 10000, 'Tyto\r\nkarpet merah', '09:33:00', '11:33:00', '2026-02-12 02:33:16', '2026-02-12 02:33:16', 10000, 0),
	(10, 2, 1, 1, 'transfer', 10000, 'hhghg', '17:15:00', NULL, '2026-02-12 10:15:10', '2026-02-12 10:15:10', 10000, 0),
	(11, 3, 1, 1, 'credit', 10000, 'hhghg', '17:17:00', NULL, '2026-02-12 10:17:20', '2026-02-12 10:17:20', 100000, 0),
	(12, 5, 1, 1, 'debit', 500000, 'bikin dengan data  member', '15:18:00', NULL, '2026-02-16 08:18:10', '2026-02-16 08:18:10', 500000, 0),
	(13, 3, 1, 4, 'cash', 44000, 'loker', '11:04:00', NULL, '2026-02-24 04:04:40', '2026-02-24 04:04:40', 44000, 0),
	(14, 1, 1, 1, 'debit', 10000, 'fsfds', '09:40:00', NULL, '2026-02-25 02:40:17', '2026-02-25 02:40:17', 10000, 0),
	(15, 1, 1, 1, 'transfer', 10000, NULL, '12:56:00', NULL, '2026-02-25 05:56:30', '2026-02-25 05:56:30', 10000, 0);

-- Dumping structure for table db_membership.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.permissions: ~15 rows (approximately)
DELETE FROM `permissions`;
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

-- Dumping structure for table db_membership.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table db_membership.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.roles: ~2 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'web', '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
	(2, 'Kasir', 'web', '2026-01-30 08:29:18', '2026-01-30 08:29:18');

-- Dumping structure for table db_membership.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.role_has_permissions: ~19 rows (approximately)
DELETE FROM `role_has_permissions`;
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
	(6, 2);

-- Dumping structure for table db_membership.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.settings: ~12 rows (approximately)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'name', 'ANWA PURI RESIDENCE SPORT CLUB', '2026-02-24 04:50:44', '2026-02-24 07:08:31'),
	(2, 'logo', 'logo/260224020831164.png', '2026-02-24 04:50:44', '2026-02-24 07:08:31'),
	(3, 'ucapan', 'Terima Kasih', '2026-02-24 04:50:44', '2026-02-24 04:50:44'),
	(4, 'deskripsi', 'qr code hanya berlaku satu kali', '2026-02-24 04:50:44', '2026-02-24 04:50:44'),
	(5, 'ppn', '0', '2026-02-24 04:50:44', '2026-02-24 04:50:44'),
	(8, 'print_mode', 'per_ticket', '2026-02-24 04:50:44', '2026-02-24 09:09:11'),
	(9, 'dashboard_metric_mode', 'count', '2026-02-24 04:50:44', '2026-02-24 07:22:49'),
	(10, 'whatsapp_enabled', '0', '2026-02-24 04:50:44', '2026-02-24 04:50:44'),
	(11, 'use_logo', '1', '2026-02-24 04:50:44', '2026-02-24 07:08:31'),
	(12, 'member_suspend_before_days', '7', '2026-02-24 04:53:19', '2026-02-24 04:53:19'),
	(13, 'member_suspend_after_days', '30', '2026-02-24 04:53:19', '2026-02-24 04:53:19'),
	(15, 'ticket_print_orientation', 'landscape', '2026-02-24 08:20:13', '2026-02-24 09:10:35'),
	(16, 'member_reactivation_admin_fee', '2500', '2026-02-25 08:54:45', '2026-02-25 08:55:23');

-- Dumping structure for table db_membership.settings_legacy
CREATE TABLE IF NOT EXISTS `settings_legacy` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.settings_legacy: ~0 rows (approximately)
DELETE FROM `settings_legacy`;

-- Dumping structure for table db_membership.sewa
CREATE TABLE IF NOT EXISTS `sewa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `is_nominal_flexible` tinyint(1) NOT NULL DEFAULT '0',
  `device` int NOT NULL,
  `use_time` tinyint(1) NOT NULL DEFAULT '1',
  `print_qr` tinyint(1) NOT NULL DEFAULT '0',
  `use_ppn` tinyint(1) NOT NULL DEFAULT '0',
  `ppn` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.sewa: ~5 rows (approximately)
DELETE FROM `sewa`;
INSERT INTO `sewa` (`id`, `name`, `harga`, `is_nominal_flexible`, `device`, `use_time`, `print_qr`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
	(1, 'Sewa Ban', 10000, 0, 1, 0, 0, 0, 0.00, '2026-01-30 07:32:23', '2026-02-10 04:48:06'),
	(2, 'Sewa Baju Renang', 10000, 0, 2, 0, 0, 0, 0.00, '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
	(3, 'Sewa Pelampung', 10000, 0, 3, 0, 0, 0, 0.00, '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
	(4, 'Sewa Tikar', 10000, 0, 4, 1, 0, 0, 0.00, '2026-01-30 07:32:23', '2026-02-10 05:03:23'),
	(5, 'Spicy Classic Fried Rice', 500000, 0, 3232, 0, 0, 0, 0.00, '2026-02-10 05:02:00', '2026-02-10 05:02:09');

-- Dumping structure for table db_membership.terusans
CREATE TABLE IF NOT EXISTS `terusans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tripod` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `terusans_tripod_unique` (`tripod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.terusans: ~0 rows (approximately)
DELETE FROM `terusans`;

-- Dumping structure for table db_membership.terusan_ticket
CREATE TABLE IF NOT EXISTS `terusan_ticket` (
  `terusan_id` bigint unsigned NOT NULL,
  `ticket_id` bigint unsigned NOT NULL,
  KEY `terusan_ticket_terusan_id_foreign` (`terusan_id`),
  KEY `terusan_ticket_ticket_id_foreign` (`ticket_id`),
  CONSTRAINT `terusan_ticket_terusan_id_foreign` FOREIGN KEY (`terusan_id`) REFERENCES `terusans` (`id`),
  CONSTRAINT `terusan_ticket_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.terusan_ticket: ~0 rows (approximately)
DELETE FROM `terusan_ticket`;

-- Dumping structure for table db_membership.tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis_ticket_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `tripod` int NOT NULL,
  `use_ppn` tinyint(1) NOT NULL DEFAULT '0',
  `ppn` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_jenis_ticket_id_foreign` (`jenis_ticket_id`),
  CONSTRAINT `tickets_jenis_ticket_id_foreign` FOREIGN KEY (`jenis_ticket_id`) REFERENCES `jenis_tickets` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.tickets: ~5 rows (approximately)
DELETE FROM `tickets`;
INSERT INTO `tickets` (`id`, `jenis_ticket_id`, `name`, `harga`, `tripod`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
	(1, 1, 'HTM Reguler Weekday', 20000, 1, 0, 0.00, '2026-01-30 07:32:23', '2026-01-30 07:46:46'),
	(2, 1, 'HTM Reguler Weekend', 30000, 2, 0, 0.00, '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
	(3, 1, 'HTM Terusan Weekday', 40000, 3, 0, 0.00, '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
	(4, 1, 'HTM Terusan Weekend', 50000, 4, 0, 0.00, '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
	(5, 1, 'HTM Promo Pelajar', 15000, 1, 0, 0.00, '2026-01-30 08:34:18', '2026-01-30 08:34:31');

-- Dumping structure for table db_membership.topups
CREATE TABLE IF NOT EXISTS `topups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `topups_member_id_foreign` (`member_id`),
  CONSTRAINT `topups_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.topups: ~0 rows (approximately)
DELETE FROM `topups`;

-- Dumping structure for table db_membership.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned DEFAULT '0',
  `member_id` bigint unsigned DEFAULT NULL,
  `member_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `no_trx` int NOT NULL,
  `ticket_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` enum('renewal','ticket','registration','rental') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ticket',
  `tipe` enum('group','individual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'group',
  `amount` int NOT NULL DEFAULT '0',
  `disc` int NOT NULL DEFAULT '0',
  `metode` enum('cash','debit','kredit','qris','transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_kartu` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_kartu` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` int NOT NULL DEFAULT '0',
  `amount_scanned` int NOT NULL DEFAULT '0',
  `status` enum('open','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `gate` int DEFAULT NULL,
  `is_active` int NOT NULL DEFAULT '0',
  `ppn` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_print` int DEFAULT '0',
  `bayar` double NOT NULL DEFAULT '0',
  `kembali` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.transactions: ~42 rows (approximately)
DELETE FROM `transactions`;
INSERT INTO `transactions` (`id`, `ticket_id`, `member_id`, `member_info`, `user_id`, `no_trx`, `ticket_code`, `transaction_type`, `tipe`, `amount`, `disc`, `metode`, `nama_kartu`, `no_kartu`, `bank`, `discount`, `amount_scanned`, `status`, `gate`, `is_active`, `ppn`, `created_at`, `updated_at`, `is_print`, `bayar`, `kembali`) VALUES
	(1, 0, NULL, NULL, 1, 1, 'INV/30012026/2373', 'ticket', 'group', 2, 7000, 'cash', NULL, NULL, NULL, 10, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:27:42', '2026-01-30 08:49:29', 0, 70000, 0),
	(2, 2, NULL, NULL, 1, 2, 'REG/300120261845', 'registration', 'group', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:48:59', '2026-01-30 08:48:59', 0, 500000, 0),
	(3, 1, NULL, NULL, 1, 3, 'TKT/1769762959', 'rental', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:49:19', '2026-01-30 08:49:19', 0, 10000, 0),
	(4, 0, NULL, NULL, 1, 4, 'INV/30012026/3937', 'ticket', 'group', 0, 0, NULL, NULL, NULL, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-01-30 08:49:33', '2026-01-30 08:49:33', 0, 0, 0),
	(5, 1, NULL, NULL, 1, 1, 'REG/100220265260', 'registration', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 01:35:52', '2026-02-10 01:35:52', 0, 150000, 0),
	(6, 2, NULL, NULL, 1, 2, 'REG/100220269110', 'registration', 'group', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 01:42:34', '2026-02-10 01:42:34', 0, 500000, 0),
	(7, 0, NULL, NULL, 1, 3, 'INV/10022026/3096', 'ticket', 'group', 21, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 01:59:28', '2026-02-10 02:12:43', 0, 440000, 0),
	(8, 2, NULL, NULL, 1, 4, 'RENEW/100220264765', 'renewal', 'group', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 02:03:25', '2026-02-10 02:03:25', 0, 500000, 0),
	(9, 0, NULL, NULL, 1, 5, 'INV/10022026/3212', 'ticket', 'group', 0, 0, NULL, NULL, NULL, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-10 02:13:08', '2026-02-10 02:13:08', 0, 0, 0),
	(10, 1, NULL, NULL, 1, 6, 'REG/100220269434', 'registration', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 03:24:18', '2026-02-10 03:24:18', 0, 150000, 0),
	(11, 2, 1, 'Budi - 089687695437', 1, 7, 'RENEW/100220265843', 'renewal', 'group', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 03:36:17', '2026-02-10 03:36:17', 0, 500000, 0),
	(12, 2, NULL, NULL, 1, 8, 'TKT/1770696787', 'rental', 'individual', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 04:13:07', '2026-02-10 04:13:07', 0, 40000, 0),
	(13, 3, NULL, NULL, 1, 9, 'TKT/1770696833', 'rental', 'individual', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 04:13:53', '2026-02-10 04:13:53', 0, 40000, 0),
	(14, 4, NULL, NULL, 1, 10, 'TKT/1770700617', 'rental', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 05:16:57', '2026-02-10 05:16:57', 0, 10000, 0),
	(15, 5, NULL, NULL, 1, 11, 'TKT/1770700690', 'rental', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 05:18:10', '2026-02-10 05:18:10', 0, 10000, 0),
	(16, 6, NULL, NULL, 1, 12, 'TKT/1770700762', 'rental', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 05:19:22', '2026-02-10 05:19:22', 0, 10000, 0),
	(17, 1, 10, 'Summer - 97897898797', 1, 13, 'RENEW/100220269642', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 06:35:03', '2026-02-10 06:35:03', 0, 150000, 0),
	(18, 1, 5, 'John - 0898998687687', 1, 14, 'RENEW/100220267327', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 06:41:23', '2026-02-10 06:41:23', 0, 150000, 0),
	(19, 1, 10, 'Summer - 97897898797', 1, 15, 'RENEW/100220264964', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 06:44:37', '2026-02-10 06:44:37', 0, 150000, 0),
	(20, 7, NULL, NULL, 1, 16, 'TKT/1770710966', 'rental', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 08:09:26', '2026-02-10 08:09:26', 0, 10000, 0),
	(21, 8, NULL, NULL, 1, 17, 'TKT/1770711207', 'rental', 'individual', 5, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 08:13:27', '2026-02-10 08:13:27', 0, 50000, 0),
	(22, 2, 6, 'Diki - 084374732', 1, 18, 'RENEW/100220264466', 'renewal', 'group', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 08:19:10', '2026-02-10 08:19:10', 0, 500000, 0),
	(23, 1, 11, 'Ina - 08989677667', 1, 1, 'REG/120220267951', 'registration', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:09:26', '2026-02-12 02:09:26', 0, 150000, 0),
	(24, 3, 12, 'Ika - 08954635643', 1, 2, 'REG/120220262949', 'registration', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:11:43', '2026-02-12 02:11:43', 0, 20000, 0),
	(25, 0, NULL, NULL, 1, 3, 'INV/12022026/9481', 'ticket', 'group', 10, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:13:13', '2026-02-12 02:22:03', 0, 350000, 0),
	(26, 3, 12, 'Ika - 08954635643', 1, 4, 'RENEW/120220265453', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:16:23', '2026-02-12 02:16:23', 0, 20000, 0),
	(27, 0, NULL, NULL, 1, 5, 'INV/12022026/7011', 'ticket', 'group', 0, 0, NULL, NULL, NULL, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-12 02:23:58', '2026-02-12 02:23:58', 0, 0, 0),
	(28, 9, NULL, NULL, 1, 19, 'TKT/1770863596', 'rental', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:33:16', '2026-02-12 02:33:16', 0, 10000, 0),
	(29, 1, 13, 'Ikag - 08954635643', 1, 3, 'REG/120220266564', 'registration', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 10:08:50', '2026-02-12 10:08:50', 0, 150000, 0),
	(30, 1, 14, 'Ikag - 08954635643', 1, 4, 'REG/12022026/004', 'registration', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 10:11:58', '2026-02-12 10:11:58', 0, 150000, 0),
	(31, 10, NULL, NULL, 1, 20, 'RENT/020', 'rental', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 10:15:10', '2026-02-12 10:15:10', 0, 10000, 0),
	(32, 11, NULL, NULL, 1, 21, 'RENT/021', 'rental', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 10:17:20', '2026-02-12 10:17:20', 0, 10000, 0),
	(33, 3, 12, 'Ika - 08954635643', 1, 1, 'RENEW/001', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-16 07:43:43', '2026-02-16 07:43:43', 0, 20000, 0),
	(34, 0, NULL, NULL, 1, 1, 'TKT/001', 'ticket', 'group', 2, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-16 07:44:05', '2026-02-16 07:44:10', 0, 70000, 0),
	(35, 0, NULL, NULL, 1, 2, 'TKT/002', 'ticket', 'group', 0, 0, NULL, NULL, NULL, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-16 07:44:17', '2026-02-16 07:44:17', 0, 0, 0),
	(36, 12, NULL, NULL, 1, 1, 'RENT/001', 'rental', 'individual', 1, 0, 'debit', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-16 08:18:10', '2026-02-16 08:18:10', 0, 500000, 0),
	(37, 3, 12, 'Ika - 08954635643', 1, 1, 'RENEW/001', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 07:48:21', '2026-02-19 07:48:21', 0, 20000, 0),
	(38, 1, 14, 'Ikag - 6289637761500', 1, 2, 'RENEW/002', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 07:52:03', '2026-02-19 07:52:03', 0, 150000, 0),
	(39, 3, 12, 'Ika - 6289637761500', 1, 3, 'RENEW/003', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 07:52:10', '2026-02-19 07:52:10', 0, 20000, 0),
	(40, 2, 1, 'Budi - 6289637761500', 1, 4, 'RENEW/004', 'renewal', 'group', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 07:52:17', '2026-02-19 07:52:17', 0, 500000, 0),
	(41, 2, 15, 'duma - 6289637761500', 1, 1, 'REG/001', 'registration', 'group', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 07:54:26', '2026-02-19 07:54:26', 0, 500000, 0),
	(42, 3, 12, 'Ika - 6289637761500', 1, 5, 'RENEW/005', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 08:17:28', '2026-02-19 08:17:28', 0, 20000, 0),
	(43, 2, 15, 'duma - 6289637761500', 1, 6, 'RENEW/006', 'renewal', 'group', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 08:17:38', '2026-02-19 08:17:38', 0, 500000, 0),
	(44, 1, 14, 'Ikag - 6289637761500', 1, 7, 'RENEW/007', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 08:17:51', '2026-02-19 08:17:51', 0, 150000, 0),
	(45, 1, 13, 'Ikag - 6289637761500', 1, 8, 'RENEW/008', 'renewal', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-19 08:37:25', '2026-02-19 08:37:25', 0, 150000, 0),
	(46, 13, NULL, NULL, 1, 1, 'RENT/001', 'rental', 'individual', 4, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-24 04:04:40', '2026-02-24 04:04:40', 0, 44000, 0),
	(47, 0, NULL, NULL, 2, 1, 'TKT/001', 'ticket', 'group', 2, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-24 04:09:23', '2026-02-24 04:13:56', 0, 70000, 0),
	(48, 3, 19, 'Anggie - 08987654', 1, 1, 'REG/001', 'registration', 'individual', 1, 0, 'cash', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-24 05:45:14', '2026-02-24 05:45:14', 0, 20000, 0),
	(49, 0, NULL, NULL, 1, 2, 'TKT/002', 'ticket', 'group', 0, 0, NULL, NULL, NULL, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-24 07:38:08', '2026-02-24 07:38:08', 0, 0, 0),
	(50, 14, NULL, NULL, 1, 1, 'RENT/001', 'rental', 'individual', 1, 0, 'debit', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 02:40:17', '2026-02-25 02:40:17', 0, 10000, 0),
	(51, 0, NULL, NULL, 1, 1, 'TKT/001', 'ticket', 'group', 1, 0, 'transfer', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 02:45:34', '2026-02-25 05:02:26', 0, 50000, 0),
	(52, 0, NULL, NULL, 1, 2, 'TKT/002', 'ticket', 'group', 1, 0, 'debit', 'oke', '08989', 'bandung', 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 05:12:58', '2026-02-25 05:16:46', 0, 20000, 0),
	(53, 0, NULL, NULL, 1, 3, 'TKT/003', 'ticket', 'group', 0, 0, NULL, NULL, NULL, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-25 05:18:06', '2026-02-25 05:18:06', 0, 0, 0),
	(54, 3, 19, 'Anggie - 08987654', 1, 1, 'RENEW/001', 'renewal', 'individual', 1, 0, NULL, NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 05:36:24', '2026-02-25 05:36:24', 0, 20000, 0),
	(55, 1, 20, 'Rahma - 08954754776', 1, 1, 'REG/001', 'registration', 'individual', 1, 0, NULL, NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 05:41:35', '2026-02-25 05:41:35', 0, 150000, 0),
	(56, 2, 1, 'Budi - 6289637761500', 1, 2, 'RENEW/002', 'renewal', 'group', 4, 0, 'transfer', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 05:48:14', '2026-02-25 05:48:14', 0, 500000, 0),
	(57, 1, 21, 'Kino - 646564', 1, 2, 'REG/002', 'registration', 'individual', 1, 0, 'debit', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 05:52:29', '2026-02-25 05:52:29', 0, 150000, 0),
	(58, 1, 22, 'Jiju - 9059043859403', 1, 3, 'REG/003', 'registration', 'individual', 1, 0, 'transfer', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 05:53:00', '2026-02-25 05:53:00', 0, 150000, 0),
	(59, 3, 23, 'DImaski - 8048204', 1, 4, 'REG/004', 'registration', 'individual', 1, 0, 'transfer', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 05:55:15', '2026-02-25 05:55:15', 0, 20000, 0),
	(60, 3, 23, 'DImaski - 8048204', 1, 3, 'RENEW/003', 'renewal', 'individual', 1, 0, 'qris', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 05:55:51', '2026-02-25 05:55:51', 0, 20000, 0),
	(61, 15, NULL, NULL, 1, 2, 'RENT/002', 'rental', 'individual', 1, 0, 'transfer', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 05:56:30', '2026-02-25 05:56:30', 0, 10000, 0),
	(62, 3, 19, 'Anggie - 08987654', 1, 4, 'RENEW/004', 'renewal', 'individual', 1, 0, 'qris', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 08:57:45', '2026-02-25 08:57:45', 0, 22500, 0),
	(63, 1, 20, 'Rahma - 08954754776', 1, 5, 'RENEW/005', 'renewal', 'individual', 1, 0, 'qris', NULL, NULL, NULL, 0, 0, 'open', NULL, 1, 0.00, '2026-02-25 09:02:55', '2026-02-25 09:02:55', 0, 150000, 0);

-- Dumping structure for table db_membership.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `uid` char(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_uid_unique` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.users: ~1 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `username`, `password`, `foto`, `created_at`, `updated_at`, `uid`, `is_active`) VALUES
	(1, 'Super Admin', 'superadmin', '$2y$10$sswDhoSF0XL0ePqzbGcZJOHuLzwe5TezY5l9DYQvXPzdR2iB79EaK', NULL, '2026-01-30 07:32:23', '2026-01-30 08:26:42', NULL, 1),
	(2, 'Dapur Nusantara', 'rahmawnj', '$2y$10$PiYxGR/REzX1dJHsG74QkOQTOiXQXAewCFhL9b/kMyNvlz81n4qhe', NULL, '2026-02-24 02:40:44', '2026-02-24 02:40:44', '08543212', 1);

-- Dumping structure for table db_membership.whatsapp_notification_logs
CREATE TABLE IF NOT EXISTS `whatsapp_notification_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'renewal_reminder, invoice',
  `member_id` bigint unsigned DEFAULT NULL,
  `transaction_id` bigint unsigned DEFAULT NULL,
  `recipient_phone` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','sent','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `retry_count` smallint unsigned NOT NULL DEFAULT '0',
  `provider_response` text COLLATE utf8mb4_unicode_ci,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_wa_logs_type_status` (`type`,`status`),
  KEY `idx_wa_logs_member_id` (`member_id`),
  KEY `idx_wa_logs_transaction_id` (`transaction_id`),
  KEY `idx_wa_logs_recipient_phone` (`recipient_phone`),
  KEY `idx_wa_logs_sent_at` (`sent_at`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.whatsapp_notification_logs: ~5 rows (approximately)
DELETE FROM `whatsapp_notification_logs`;
INSERT INTO `whatsapp_notification_logs` (`id`, `type`, `member_id`, `transaction_id`, `recipient_phone`, `message`, `status`, `retry_count`, `provider_response`, `sent_at`, `created_at`, `updated_at`) VALUES
	(16, 'renewal_reminder', 1, NULL, '6289637761500', 'Halo Budi, masa berlaku membership Anda akan berakhir pada 20/02/2026 (1 hari lagi). Silakan lakukan renewal sebelum jatuh tempo.', 'sent', 1, '{"ok":true,"http_code":200,"error_code":null,"error_message":null,"response_body":"{\\"success\\":true,\\"data\\":{\\"success\\":true,\\"message\\":\\"Message queued successfully (position: 1)\\",\\"timestamp\\":\\"2026-02-19T15:37:06+07:00\\",\\"message_length\\":129,\\"recipient_type\\":\\"individual\\",\\"send_method\\":\\"unofficial\\",\\"security_level\\":\\"enhanced\\",\\"quota_remaining\\":4978,\\"country_code\\":\\"62\\",\\"country_name\\":\\"Indonesia\\"},\\"message\\":\\"Berhasil mengirim pesan\\"}"}', '2026-02-19 08:37:05', '2026-02-19 08:37:01', '2026-02-19 08:37:05'),
	(17, 'renewal_reminder', 12, NULL, '6289637761500', 'Halo Ika, masa berlaku membership Anda akan berakhir pada 24/02/2026 (5 hari lagi). Silakan lakukan renewal sebelum jatuh tempo.', 'sent', 1, '{"ok":true,"http_code":200,"error_code":null,"error_message":null,"response_body":"{\\"success\\":true,\\"data\\":{\\"success\\":true,\\"message\\":\\"Message queued successfully (position: 1)\\",\\"timestamp\\":\\"2026-02-19T15:37:13+07:00\\",\\"message_length\\":128,\\"recipient_type\\":\\"individual\\",\\"send_method\\":\\"unofficial\\",\\"security_level\\":\\"enhanced\\",\\"quota_remaining\\":4977,\\"country_code\\":\\"62\\",\\"country_name\\":\\"Indonesia\\"},\\"message\\":\\"Berhasil mengirim pesan\\"}"}', '2026-02-19 08:37:12', '2026-02-19 08:37:01', '2026-02-19 08:37:12'),
	(18, 'renewal_reminder', 14, NULL, '6289637761500', 'Halo Ikag, masa berlaku membership Anda akan berakhir pada 24/02/2026 (5 hari lagi). Silakan lakukan renewal sebelum jatuh tempo.', 'sent', 1, '{"ok":true,"http_code":200,"error_code":null,"error_message":null,"response_body":"{\\"success\\":true,\\"data\\":{\\"success\\":true,\\"message\\":\\"Message queued successfully (position: 1)\\",\\"timestamp\\":\\"2026-02-19T15:37:25+07:00\\",\\"message_length\\":129,\\"recipient_type\\":\\"individual\\",\\"send_method\\":\\"unofficial\\",\\"security_level\\":\\"enhanced\\",\\"quota_remaining\\":4976,\\"country_code\\":\\"62\\",\\"country_name\\":\\"Indonesia\\"},\\"message\\":\\"Berhasil mengirim pesan\\"}"}', '2026-02-19 08:37:24', '2026-02-19 08:37:01', '2026-02-19 08:37:24'),
	(19, 'renewal_reminder', 15, NULL, '6289637761500', 'Halo duma, masa berlaku membership Anda akan berakhir pada 22/02/2026 (3 hari lagi). Silakan lakukan renewal sebelum jatuh tempo.', 'sent', 1, '{"ok":true,"http_code":200,"error_code":null,"error_message":null,"response_body":"{\\"success\\":true,\\"data\\":{\\"success\\":true,\\"message\\":\\"Message queued successfully (position: 1)\\",\\"timestamp\\":\\"2026-02-19T15:37:32+07:00\\",\\"message_length\\":129,\\"recipient_type\\":\\"individual\\",\\"send_method\\":\\"unofficial\\",\\"security_level\\":\\"enhanced\\",\\"quota_remaining\\":4975,\\"country_code\\":\\"62\\",\\"country_name\\":\\"Indonesia\\"},\\"message\\":\\"Berhasil mengirim pesan\\"}"}', '2026-02-19 08:37:32', '2026-02-19 08:37:01', '2026-02-19 08:37:32'),
	(20, 'renewal', 13, 45, '6289637761500', 'Halo Ikag, perpanjangan membership Anda berhasil diproses pada 19/02/2026 15:37. Kode invoice: RENEW/008. Terima kasih.', 'sent', 1, '{"ok":true,"http_code":200,"error_code":null,"error_message":null,"response_body":"{\\"success\\":true,\\"data\\":{\\"success\\":true,\\"message\\":\\"Message queued successfully (position: 1)\\",\\"timestamp\\":\\"2026-02-19T15:38:04+07:00\\",\\"message_length\\":119,\\"recipient_type\\":\\"individual\\",\\"send_method\\":\\"unofficial\\",\\"security_level\\":\\"enhanced\\",\\"quota_remaining\\":4974,\\"country_code\\":\\"62\\",\\"country_name\\":\\"Indonesia\\"},\\"message\\":\\"Berhasil mengirim pesan\\"}"}', '2026-02-19 08:38:03', '2026-02-19 08:37:25', '2026-02-19 08:38:03');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
