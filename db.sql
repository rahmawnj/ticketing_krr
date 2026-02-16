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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.detail_transactions: ~5 rows (approximately)
DELETE FROM `detail_transactions`;
INSERT INTO `detail_transactions` (`id`, `transaction_id`, `ticket_id`, `qty`, `total`, `ppn`, `created_at`, `updated_at`, `ticket_code`, `status`, `scanned`, `scanned_at`, `gate`, `is_print`) VALUES
	(1, 1, 1, 1, 20000, 0.00, '2026-01-30 08:49:26', '2026-01-30 09:36:02', 'TKT20260130154926674', 'close', 1, '2026-01-30 09:36:02', 1, 0),
	(2, 1, 4, 1, 50000, 0.00, '2026-01-30 08:49:27', '2026-01-30 08:49:27', 'TKT20260130154927608', 'open', 0, NULL, NULL, 0),
	(3, 7, 1, 7, 140000, 0.00, '2026-02-10 02:11:40', '2026-02-10 02:11:46', 'TKT20260210091140603', 'open', 0, NULL, NULL, 0),
	(4, 7, 2, 6, 180000, 0.00, '2026-02-10 02:11:41', '2026-02-10 02:11:51', 'TKT20260210091141221', 'open', 0, NULL, NULL, 0),
	(5, 7, 5, 8, 120000, 0.00, '2026-02-10 02:11:42', '2026-02-10 02:11:56', 'TKT20260210091142683', 'open', 0, NULL, NULL, 0),
	(6, 25, 1, 5, 100000, 0.00, '2026-02-12 02:21:39', '2026-02-12 02:21:53', 'TKT20260212092139225', 'open', 0, NULL, NULL, 0),
	(7, 25, 4, 5, 250000, 0.00, '2026-02-12 02:21:40', '2026-02-12 02:21:49', 'TKT20260212092140218', 'open', 0, NULL, NULL, 0);

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

-- Dumping data for table db_membership.histories: ~50 rows (approximately)
DELETE FROM `histories`;
INSERT INTO `histories` (`id`, `member_id`, `gate`, `created_at`, `updated_at`, `waktu`, `user_id`) VALUES
	(1, 1, 1, '2026-01-30 09:34:00', '2026-01-30 09:34:00', '2026-01-30 09:34:00', 0),
	(2, 1, 1, '2026-01-30 09:34:22', '2026-01-30 09:34:22', '2026-01-30 09:34:22', 0),
	(3, 1, 1, '2026-01-30 09:34:37', '2026-01-30 09:34:37', '2026-01-30 09:34:37', 0),
	(4, 3, 1, '2026-01-30 09:35:22', '2026-01-30 09:35:22', '2026-01-30 09:35:22', 0),
	(5, 3, 1, '2026-01-30 09:35:23', '2026-01-30 09:35:23', '2026-01-30 09:35:23', 0),
	(6, 3, 1, '2026-01-30 09:35:24', '2026-01-30 09:35:24', '2026-01-30 09:35:24', 0),
	(7, 1, 1, '2026-01-30 09:35:57', '2026-01-30 09:35:57', '2026-01-30 09:35:57', 0),
	(8, 6, 1, '2026-02-10 06:21:34', '2026-02-10 06:21:34', '2026-02-10 06:21:34', 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.history_memberships: ~24 rows (approximately)
DELETE FROM `history_memberships`;
INSERT INTO `history_memberships` (`id`, `membership_id`, `member_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
	(2, 2, 2, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
	(3, 2, 3, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
	(4, 2, 4, '2026-01-30', '2026-04-30', 'active', '2026-01-30 08:48:59', '2026-01-30 08:48:59'),
	(5, 1, 5, '2026-02-10', '2026-03-12', 'active', '2026-02-10 01:35:52', '2026-02-10 01:35:52'),
	(6, 2, 6, '2026-02-10', '2026-05-11', 'active', '2026-02-10 01:42:34', '2026-02-10 01:42:34'),
	(7, 2, 7, '2026-02-10', '2026-05-11', 'active', '2026-02-10 01:42:34', '2026-02-10 01:42:34'),
	(8, 2, 8, '2026-02-10', '2026-05-11', 'active', '2026-02-10 01:42:34', '2026-02-10 01:42:34'),
	(9, 2, 9, '2026-02-10', '2026-05-11', 'active', '2026-02-10 01:42:34', '2026-02-10 01:42:34'),
	(10, 2, 6, '2026-02-10', '2026-05-11', 'active', '2026-02-10 02:03:25', '2026-02-10 02:03:25'),
	(11, 2, 7, '2026-02-10', '2026-05-11', 'active', '2026-02-10 02:03:25', '2026-02-10 02:03:25'),
	(12, 2, 8, '2026-02-10', '2026-05-11', 'active', '2026-02-10 02:03:25', '2026-02-10 02:03:25'),
	(13, 2, 9, '2026-02-10', '2026-05-11', 'active', '2026-02-10 02:03:25', '2026-02-10 02:03:25'),
	(19, 2, 1, '2026-02-12', '2026-05-13', 'active', '2026-02-10 03:36:17', '2026-02-10 03:36:17'),
	(20, 2, 2, '2026-02-12', '2026-05-13', 'active', '2026-02-10 03:36:17', '2026-02-10 03:36:17'),
	(21, 2, 3, '2026-02-12', '2026-05-13', 'active', '2026-02-10 03:36:17', '2026-02-10 03:36:17'),
	(22, 2, 4, '2026-02-12', '2026-05-13', 'active', '2026-02-10 03:36:17', '2026-02-10 03:36:17'),
	(24, 1, 5, '2026-02-10', '2026-03-12', 'active', '2026-02-10 06:41:23', '2026-02-10 06:41:23'),
	(26, 2, 6, '2026-02-10', '2026-05-11', 'active', '2026-02-10 08:19:10', '2026-02-10 08:19:10'),
	(27, 2, 7, '2026-02-10', '2026-05-11', 'active', '2026-02-10 08:19:10', '2026-02-10 08:19:10'),
	(28, 2, 8, '2026-02-10', '2026-05-11', 'active', '2026-02-10 08:19:10', '2026-02-10 08:19:10'),
	(29, 2, 9, '2026-02-10', '2026-05-11', 'active', '2026-02-10 08:19:10', '2026-02-10 08:19:10'),
	(31, 3, 12, '2026-02-12', '2026-02-17', 'active', '2026-02-12 02:11:43', '2026-02-12 02:11:43'),
	(32, 3, 12, '2026-02-18', '2026-02-23', 'active', '2026-02-12 02:16:23', '2026-02-12 02:16:23');

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
  UNIQUE KEY `members_rfid_unique` (`rfid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.members: ~10 rows (approximately)
DELETE FROM `members`;
INSERT INTO `members` (`id`, `parent_id`, `membership_id`, `rfid`, `no_ktp`, `no_hp`, `nama`, `access_used`, `alamat`, `tgl_lahir`, `tgl_register`, `tgl_expired`, `saldo`, `is_active`, `jenis_kelamin`, `image_profile`, `qr_code`, `created_at`, `updated_at`, `limit`, `jenis_member`) VALUES
	(1, 0, 2, NULL, '3277654567825678', '089687695437', 'Budi', 0, 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-05-13', 0, 1, 'L', NULL, 'MBRBVCAWZRZ7WXN2', '2026-01-30 08:48:59', '2026-02-10 03:36:17', 0, NULL),
	(2, 1, 2, NULL, '3277654567825678', '089687695437', 'Dina', 0, 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-05-13', 0, 1, 'L', NULL, 'MBRVK4DFYKK4BCKU', '2026-01-30 08:48:59', '2026-02-10 03:36:17', 0, NULL),
	(3, 1, 2, NULL, '3277654567825678', '089687695437', 'Andi', 0, 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-05-13', 0, 1, 'L', NULL, 'MBRPWQMAWVI7SSCF', '2026-01-30 08:48:59', '2026-02-10 03:36:17', 0, NULL),
	(4, 1, 1, NULL, '3277654567825678', '089687695437', 'Rara', 0, 'Jl. Melati No. 123, Jakarta', '2026-01-30', '2026-01-30', '2026-05-13', 0, 1, 'L', NULL, 'MBRJPG3DKVV3W5TO', '2026-01-30 08:48:59', '2026-02-10 03:36:17', 0, NULL),
	(5, 0, 1, NULL, '27727272772727272', '0898998687687', 'John', 0, 'Jakarta', '2026-02-10', '2026-02-10', '2026-03-12', 0, 1, 'L', NULL, 'MBRDXV6VQEGUDCKB', '2026-02-10 01:35:52', '2026-02-10 06:41:23', 0, NULL),
	(6, 0, 2, NULL, '8909543988989', '084374732', 'Diki', 0, 'Jakarta', '2026-02-10', '2025-11-10', '2026-05-11', 0, 1, 'L', NULL, 'MBR74F2V2HAN7FEA', '2026-02-10 01:42:34', '2026-02-10 08:19:10', 0, NULL),
	(7, 6, 2, NULL, '8909543988989', '084374732', 'Diki 1', 0, 'Jakarta', '2026-02-10', '2026-02-10', '2026-05-11', 0, 1, 'L', NULL, 'MBR1BQ2SWKB7BDDM', '2026-02-10 01:42:34', '2026-02-10 08:19:10', 0, NULL),
	(8, 6, 2, NULL, '8909543988989', '084374732', 'Diki 2', 0, 'Jakarta', '2026-02-10', '2026-02-10', '2026-05-11', 0, 1, 'L', NULL, 'MBR9YWDSYKIHX6K0', '2026-02-10 01:42:34', '2026-02-10 08:19:10', 0, NULL),
	(9, 6, 2, NULL, '8909543988989', '084374732', 'Diki 3', 0, 'Jakarta', '2026-02-10', '2026-02-10', '2026-05-11', 0, 1, 'L', NULL, 'MBRSBOP2GR8VOV9V', '2026-02-10 01:42:34', '2026-02-10 08:19:10', 0, NULL),
	(12, 0, 3, NULL, NULL, '08954635643', 'Ika', 0, 'Bandung', '2026-02-12', '2026-02-12', '2026-02-23', 0, 1, 'L', NULL, 'MBRVKXZCY90NABLQ', '2026-02-12 02:11:43', '2026-02-12 02:16:23', 0, NULL);

-- Dumping structure for table db_membership.memberships
CREATE TABLE IF NOT EXISTS `memberships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

-- Dumping data for table db_membership.memberships: ~2 rows (approximately)
DELETE FROM `memberships`;
INSERT INTO `memberships` (`id`, `name`, `duration_days`, `price`, `max_person`, `is_active`, `use_ppn`, `ppn`, `created_at`, `updated_at`, `max_access`) VALUES
	(1, 'Member Bulanan Reguler', 30, 150000, 1, 1, 0, 0.00, '2026-01-30 08:44:15', '2026-02-10 06:32:54', 10),
	(2, 'Family Pass 3 Bulan', 90, 500000, 4, 1, 1, 0.00, '2026-01-30 08:45:02', '2026-02-10 05:39:33', 5),
	(3, 'Member VIP', 5, 20000, 1, 1, 0, 0.00, '2026-02-12 02:11:15', '2026-02-12 02:11:15', 0);

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
	(1, 'App\\Models\\User', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.penyewaans: ~8 rows (approximately)
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
	(9, 4, 1, 1, 'debit', 10000, 'Tyto\r\nkarpet merah', '09:33:00', '11:33:00', '2026-02-12 02:33:16', '2026-02-12 02:33:16', 10000, 0);

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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ucapan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ppn` int DEFAULT NULL,
  `member_reminder_days` int NOT NULL DEFAULT '7',
  `member_delete_grace_days` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `use_logo` int NOT NULL DEFAULT '1',
  `print_mode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'per_qty',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.settings: ~1 rows (approximately)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `name`, `logo`, `ucapan`, `deskripsi`, `ppn`, `member_reminder_days`, `member_delete_grace_days`, `created_at`, `updated_at`, `use_logo`, `print_mode`) VALUES
	(1, 'ANWA PURI RESIDENCE SPORT CLUB', 'logo/260210023643774.png', 'Terima kasih atas kunjungan anda', 'WA: 0812xxxx | IG: @sportclub_id', 0, 7, 5, NULL, '2026-02-12 09:06:56', 1, 'per_ticket');

-- Dumping structure for table db_membership.sewa
CREATE TABLE IF NOT EXISTS `sewa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `device` int NOT NULL,
  `use_time` tinyint(1) NOT NULL DEFAULT '1',
  `use_ppn` tinyint(1) NOT NULL DEFAULT '0',
  `ppn` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.sewa: ~5 rows (approximately)
DELETE FROM `sewa`;
INSERT INTO `sewa` (`id`, `name`, `harga`, `device`, `use_time`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
	(1, 'Sewa Ban', 10000, 1, 0, 0, 0.00, '2026-01-30 07:32:23', '2026-02-10 04:48:06'),
	(2, 'Sewa Baju Renang', 10000, 2, 0, 0, 0.00, '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
	(3, 'Sewa Pelampung', 10000, 3, 0, 0, 0.00, '2026-01-30 07:32:23', '2026-01-30 07:32:23'),
	(4, 'Sewa Tikar', 10000, 4, 1, 0, 0.00, '2026-01-30 07:32:23', '2026-02-10 05:03:23'),
	(5, 'Spicy Classic Fried Rice', 500000, 3232, 0, 0, 0.00, '2026-02-10 05:02:00', '2026-02-10 05:02:09');

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
  `metode` enum('cash','debit','kredit','qris') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.transactions: ~28 rows (approximately)
DELETE FROM `transactions`;
INSERT INTO `transactions` (`id`, `ticket_id`, `member_id`, `member_info`, `user_id`, `no_trx`, `ticket_code`, `transaction_type`, `tipe`, `amount`, `disc`, `metode`, `discount`, `amount_scanned`, `status`, `gate`, `is_active`, `ppn`, `created_at`, `updated_at`, `is_print`, `bayar`, `kembali`) VALUES
	(1, 0, NULL, NULL, 1, 1, 'INV/30012026/2373', 'ticket', 'group', 2, 7000, 'cash', 10, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:27:42', '2026-01-30 08:49:29', 0, 70000, 0),
	(2, 2, NULL, NULL, 1, 2, 'REG/300120261845', 'registration', 'group', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:48:59', '2026-01-30 08:48:59', 0, 500000, 0),
	(3, 1, NULL, NULL, 1, 3, 'TKT/1769762959', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-01-30 08:49:19', '2026-01-30 08:49:19', 0, 10000, 0),
	(4, 0, NULL, NULL, 1, 4, 'INV/30012026/3937', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-01-30 08:49:33', '2026-01-30 08:49:33', 0, 0, 0),
	(5, 1, NULL, NULL, 1, 1, 'REG/100220265260', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 01:35:52', '2026-02-10 01:35:52', 0, 150000, 0),
	(6, 2, NULL, NULL, 1, 2, 'REG/100220269110', 'registration', 'group', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 01:42:34', '2026-02-10 01:42:34', 0, 500000, 0),
	(7, 0, NULL, NULL, 1, 3, 'INV/10022026/3096', 'ticket', 'group', 21, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 01:59:28', '2026-02-10 02:12:43', 0, 440000, 0),
	(8, 2, NULL, NULL, 1, 4, 'RENEW/100220264765', 'renewal', 'group', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 02:03:25', '2026-02-10 02:03:25', 0, 500000, 0),
	(9, 0, NULL, NULL, 1, 5, 'INV/10022026/3212', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-10 02:13:08', '2026-02-10 02:13:08', 0, 0, 0),
	(10, 1, NULL, NULL, 1, 6, 'REG/100220269434', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 03:24:18', '2026-02-10 03:24:18', 0, 150000, 0),
	(11, 2, 1, 'Budi - 089687695437', 1, 7, 'RENEW/100220265843', 'renewal', 'group', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 03:36:17', '2026-02-10 03:36:17', 0, 500000, 0),
	(12, 2, NULL, NULL, 1, 8, 'TKT/1770696787', 'rental', 'individual', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 04:13:07', '2026-02-10 04:13:07', 0, 40000, 0),
	(13, 3, NULL, NULL, 1, 9, 'TKT/1770696833', 'rental', 'individual', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 04:13:53', '2026-02-10 04:13:53', 0, 40000, 0),
	(14, 4, NULL, NULL, 1, 10, 'TKT/1770700617', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 05:16:57', '2026-02-10 05:16:57', 0, 10000, 0),
	(15, 5, NULL, NULL, 1, 11, 'TKT/1770700690', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 05:18:10', '2026-02-10 05:18:10', 0, 10000, 0),
	(16, 6, NULL, NULL, 1, 12, 'TKT/1770700762', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 05:19:22', '2026-02-10 05:19:22', 0, 10000, 0),
	(17, 1, 10, 'Summer - 97897898797', 1, 13, 'RENEW/100220269642', 'renewal', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 06:35:03', '2026-02-10 06:35:03', 0, 150000, 0),
	(18, 1, 5, 'John - 0898998687687', 1, 14, 'RENEW/100220267327', 'renewal', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 06:41:23', '2026-02-10 06:41:23', 0, 150000, 0),
	(19, 1, 10, 'Summer - 97897898797', 1, 15, 'RENEW/100220264964', 'renewal', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 06:44:37', '2026-02-10 06:44:37', 0, 150000, 0),
	(20, 7, NULL, NULL, 1, 16, 'TKT/1770710966', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 08:09:26', '2026-02-10 08:09:26', 0, 10000, 0),
	(21, 8, NULL, NULL, 1, 17, 'TKT/1770711207', 'rental', 'individual', 5, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 08:13:27', '2026-02-10 08:13:27', 0, 50000, 0),
	(22, 2, 6, 'Diki - 084374732', 1, 18, 'RENEW/100220264466', 'renewal', 'group', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-10 08:19:10', '2026-02-10 08:19:10', 0, 500000, 0),
	(23, 1, 11, 'Ina - 08989677667', 1, 1, 'REG/120220267951', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:09:26', '2026-02-12 02:09:26', 0, 150000, 0),
	(24, 3, 12, 'Ika - 08954635643', 1, 2, 'REG/120220262949', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:11:43', '2026-02-12 02:11:43', 0, 20000, 0),
	(25, 0, NULL, NULL, 1, 3, 'INV/12022026/9481', 'ticket', 'group', 10, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:13:13', '2026-02-12 02:22:03', 0, 350000, 0),
	(26, 3, 12, 'Ika - 08954635643', 1, 4, 'RENEW/120220265453', 'renewal', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:16:23', '2026-02-12 02:16:23', 0, 20000, 0),
	(27, 0, NULL, NULL, 1, 5, 'INV/12022026/7011', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-02-12 02:23:58', '2026-02-12 02:23:58', 0, 0, 0),
	(28, 9, NULL, NULL, 1, 19, 'TKT/1770863596', 'rental', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-02-12 02:33:16', '2026-02-12 02:33:16', 0, 10000, 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.users: ~1 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `username`, `password`, `foto`, `created_at`, `updated_at`, `uid`, `is_active`) VALUES
	(1, 'Super Admin', 'superadmin', '$2y$10$sswDhoSF0XL0ePqzbGcZJOHuLzwe5TezY5l9DYQvXPzdR2iB79EaK', NULL, '2026-01-30 07:32:23', '2026-01-30 08:26:42', NULL, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
