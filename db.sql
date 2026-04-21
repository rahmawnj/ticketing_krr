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


-- Dumping database structure for db_membership
CREATE DATABASE IF NOT EXISTS `db_membership` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_membership`;

-- Dumping structure for table db_membership.detail_transactions
CREATE TABLE IF NOT EXISTS `detail_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `ticket_id` bigint unsigned NOT NULL,
  `qty` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `jenis_ticket_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.detail_transactions: ~0 rows (approximately)
INSERT IGNORE INTO `detail_transactions` (`id`, `transaction_id`, `ticket_id`, `qty`, `name`, `total`, `ppn`, `created_at`, `updated_at`, `ticket_code`, `status`, `scanned`, `scanned_at`, `gate`, `is_print`, `jenis_ticket_id`) VALUES
	(1, 8, 1, 1, NULL, 20000, 0.00, '2026-03-31 02:44:02', '2026-03-31 02:44:02', 'TKT20260331094402787', 'open', 0, NULL, NULL, 0, NULL),
	(2, 8, 6, 1, NULL, 25000, 0.00, '2026-03-31 03:11:32', '2026-03-31 03:11:32', 'TKT20260331101132893', 'open', 0, NULL, NULL, 0, NULL),
	(3, 8, 6, 1, NULL, 25000, 0.00, '2026-03-31 03:11:39', '2026-03-31 03:11:39', 'TKT20260331101139859', 'open', 0, NULL, NULL, 0, NULL),
	(4, 8, 7, 1, NULL, 35000, 0.00, '2026-03-31 03:11:52', '2026-03-31 03:11:52', 'TKT20260331101152236', 'open', 0, NULL, NULL, 0, NULL),
	(5, 8, 6, 1, NULL, 25000, 0.00, '2026-03-31 03:13:38', '2026-03-31 03:13:38', 'TKT20260331101338765', 'open', 0, NULL, NULL, 0, NULL),
	(6, 8, 6, 1, NULL, 25000, 0.00, '2026-03-31 03:36:41', '2026-03-31 03:36:41', 'TKT20260331103641623', 'open', 0, NULL, NULL, 0, NULL),
	(7, 8, 10, 1, NULL, 20000, 0.00, '2026-03-31 03:42:30', '2026-03-31 03:42:30', 'TKT20260331104230543', 'open', 0, NULL, NULL, 0, NULL);

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
INSERT IGNORE INTO `gate_accesses` (`id`, `gate_access_id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, '1', 'Gerbang Utama', 1, '2026-03-30 09:36:14', '2026-03-30 09:54:57');

-- Dumping structure for table db_membership.gate_access_membership
CREATE TABLE IF NOT EXISTS `gate_access_membership` (
  `gate_access_id` bigint unsigned NOT NULL,
  `membership_id` bigint unsigned NOT NULL,
  KEY `gate_access_membership_gate_access_id_foreign` (`gate_access_id`),
  KEY `gate_access_membership_membership_id_foreign` (`membership_id`),
  CONSTRAINT `gate_access_membership_gate_access_id_foreign` FOREIGN KEY (`gate_access_id`) REFERENCES `gate_accesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gate_access_membership_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.gate_access_membership: ~1 rows (approximately)
INSERT IGNORE INTO `gate_access_membership` (`gate_access_id`, `membership_id`) VALUES
	(1, 1),
	(1, 2),
	(1, 3),
	(1, 4),
	(1, 5),
	(1, 6),
	(1, 7),
	(1, 8),
	(1, 9),
	(1, 10),
	(1, 11),
	(1, 12),
	(1, 13);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.histories: ~0 rows (approximately)
INSERT IGNORE INTO `histories` (`id`, `member_id`, `gate`, `created_at`, `updated_at`, `waktu`, `user_id`) VALUES
	(1, 2, 1, '2026-03-30 11:01:56', '2026-03-30 11:01:56', '2026-03-30 11:01:56', 0),
	(2, 8, 1, '2026-03-30 11:56:14', '2026-03-30 11:56:14', '2026-03-30 11:56:14', 0),
	(3, 8, 1, '2026-03-30 11:56:16', '2026-03-30 11:56:16', '2026-03-30 11:56:16', 0),
	(4, 5, 1, '2026-03-30 11:57:11', '2026-03-30 11:57:11', '2026-03-30 11:57:11', 0),
	(5, 5, 1, '2026-03-30 11:57:12', '2026-03-30 11:57:12', '2026-03-30 11:57:12', 0),
	(6, 3, 1, '2026-03-30 11:58:35', '2026-03-30 11:58:35', '2026-03-30 11:58:35', 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.history_memberships: ~1 rows (approximately)
INSERT IGNORE INTO `history_memberships` (`id`, `membership_id`, `member_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, '2026-03-30', '2026-04-29', 'active', '2026-03-30 09:54:19', '2026-03-30 09:54:19'),
	(2, 1, 2, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:00:53', '2026-03-30 11:00:53'),
	(3, 7, 3, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:05:10', '2026-03-30 11:05:10'),
	(4, 7, 4, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:05:10', '2026-03-30 11:05:10'),
	(5, 7, 5, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:05:10', '2026-03-30 11:05:10'),
	(6, 7, 6, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:05:10', '2026-03-30 11:05:10'),
	(7, 7, 7, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:05:10', '2026-03-30 11:05:10'),
	(8, 7, 8, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:05:10', '2026-03-30 11:05:10'),
	(9, 2, 9, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:06:29', '2026-03-30 11:06:29'),
	(10, 2, 10, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:06:29', '2026-03-30 11:06:29'),
	(11, 4, 11, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:08:07', '2026-03-30 11:08:07'),
	(12, 4, 12, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:08:07', '2026-03-30 11:08:07'),
	(13, 4, 13, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:08:07', '2026-03-30 11:08:07'),
	(14, 4, 14, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:08:07', '2026-03-30 11:08:07'),
	(15, 3, 15, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:09:06', '2026-03-30 11:09:06'),
	(16, 3, 16, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:09:06', '2026-03-30 11:09:06'),
	(17, 3, 17, '2026-03-30', '2026-04-29', 'active', '2026-03-30 11:09:06', '2026-03-30 11:09:06'),
	(18, 2, 18, '2026-03-31', '2026-04-04', 'active', '2026-03-31 02:15:12', '2026-03-31 02:15:12'),
	(19, 2, 19, '2026-03-31', '2026-04-04', 'active', '2026-03-31 02:15:13', '2026-03-31 02:15:13'),
	(20, 2, 20, '2026-03-31', '2026-04-30', 'active', '2026-03-31 04:12:24', '2026-03-31 04:12:24'),
	(21, 2, 21, '2026-03-31', '2026-04-30', 'active', '2026-03-31 04:12:24', '2026-03-31 04:12:24');

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

-- Dumping structure for table db_membership.jenis_tickets
CREATE TABLE IF NOT EXISTS `jenis_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.jenis_tickets: ~2 rows (approximately)
INSERT IGNORE INTO `jenis_tickets` (`id`, `nama_jenis`, `created_at`, `updated_at`) VALUES
	(1, 'Reguler', '2026-03-30 09:29:43', '2026-03-30 09:29:43'),
	(2, 'Terusan', '2026-03-30 09:29:43', '2026-03-30 09:29:43');

-- Dumping structure for table db_membership.limit_members
CREATE TABLE IF NOT EXISTS `limit_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `limit` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.limit_members: ~0 rows (approximately)

-- Dumping structure for table db_membership.members
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT '0',
  `membership_id` bigint DEFAULT '0',
  `rfid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_ktp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.members: ~1 rows (approximately)
INSERT IGNORE INTO `members` (`id`, `parent_id`, `membership_id`, `rfid`, `no_ktp`, `no_hp`, `nama`, `alamat`, `tgl_lahir`, `tgl_register`, `tgl_expired`, `saldo`, `is_active`, `jenis_kelamin`, `image_profile`, `qr_code`, `created_at`, `updated_at`, `limit`, `jenis_member`) VALUES
	(1, 0, 1, NULL, '645', '75', 'rizal', 'h', '2026-03-30', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRVYGVZ7VL27UHX', '2026-03-30 09:54:19', '2026-03-30 09:54:19', 0, NULL),
	(2, 0, 1, NULL, '5588', '085819309386', 'ALI', 'DESA MEKAR JAYA', '1995-04-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBROT7ZTHI4ZAC4C', '2026-03-30 11:00:53', '2026-03-30 11:00:53', 0, NULL),
	(3, 0, 7, NULL, '9977', '081292927343', 'ROBBY', 'SELAKOPI BOGOR', '1994-01-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRNTLTXWMQUBQPT', '2026-03-30 11:05:10', '2026-03-30 11:05:10', 0, NULL),
	(4, 3, 7, NULL, '9977', '081292927343', 'ALDONA', 'SELAKOPI BOGOR', '1994-01-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBR7FIT3KOHJDVBS', '2026-03-30 11:05:10', '2026-03-30 11:05:10', 0, NULL),
	(5, 3, 7, NULL, '9977', '081292927343', 'LUTFI', 'SELAKOPI BOGOR', '1994-01-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRRWLEVUBF25KMO', '2026-03-30 11:05:10', '2026-03-30 11:05:10', 0, NULL),
	(6, 3, 7, NULL, '9977', '081292927343', 'SAMMY', 'SELAKOPI BOGOR', '1994-01-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRV6AJ4FIKNOEBD', '2026-03-30 11:05:10', '2026-03-30 11:05:10', 0, NULL),
	(7, 3, 7, NULL, '9977', '081292927343', 'RANDI', 'SELAKOPI BOGOR', '1994-01-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBROFJ84BS3OWJ9A', '2026-03-30 11:05:10', '2026-03-30 11:05:10', 0, NULL),
	(8, 3, 7, NULL, '9977', '081292927343', 'ARIF', 'SELAKOPI BOGOR', '1994-01-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRQAJ0HGMA7ESU3', '2026-03-30 11:05:10', '2026-03-30 11:05:10', 0, NULL),
	(9, 0, 2, NULL, '8877', '085819868262', 'DIAN', 'SINDANG BARANG', '1997-03-04', '2026-03-30', '2026-04-29', 0, 1, 'P', NULL, 'MBRGG8BNZJGINPK3', '2026-03-30 11:06:29', '2026-03-30 11:06:29', 0, NULL),
	(10, 9, 2, NULL, '8877', '085819868262', 'YOGO', 'SINDANG BARANG', '1997-03-04', '2026-03-30', '2026-04-29', 0, 1, 'P', NULL, 'MBRLML56XTPSLXL2', '2026-03-30 11:06:29', '2026-03-30 11:06:29', 0, NULL),
	(11, 0, 4, NULL, '1144', '089679127878', 'DAVIEN', 'MEKAR JAYA KP SAWAH HILIR', '2002-10-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRN25KYC1X41HZC', '2026-03-30 11:08:07', '2026-03-30 11:08:07', 0, NULL),
	(12, 11, 4, NULL, '1144', '089679127878', 'GALANG', 'MEKAR JAYA KP SAWAH HILIR', '2002-10-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRYNBIBYDSHQDLK', '2026-03-30 11:08:07', '2026-03-30 11:08:07', 0, NULL),
	(13, 11, 4, NULL, '1144', '089679127878', 'YAFI', 'MEKAR JAYA KP SAWAH HILIR', '2002-10-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBR0BOBK1QNS7FVB', '2026-03-30 11:08:07', '2026-03-30 11:08:07', 0, NULL),
	(14, 11, 4, NULL, '1144', '089679127878', 'ATALLA', 'MEKAR JAYA KP SAWAH HILIR', '2002-10-12', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRNRN0FEGSGXTZS', '2026-03-30 11:08:07', '2026-03-30 11:08:07', 0, NULL),
	(15, 0, 3, NULL, '8844', '087874591881', 'DIMAS', 'TANAH BARU', '1991-03-04', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRLOFT0MDDKWECT', '2026-03-30 11:09:06', '2026-03-30 11:09:06', 0, NULL),
	(16, 15, 3, NULL, '8844', '087874591881', 'ILHAM', 'TANAH BARU', '1991-03-04', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRQAH1LKAMMH6OO', '2026-03-30 11:09:06', '2026-03-30 11:09:06', 0, NULL),
	(17, 15, 3, NULL, '8844', '087874591881', 'ANIN', 'TANAH BARU', '1991-03-04', '2026-03-30', '2026-04-29', 0, 1, 'L', NULL, 'MBRWYF3WYLZHMGIA', '2026-03-30 11:09:06', '2026-03-30 11:09:06', 0, NULL),
	(18, 0, 2, NULL, '22222', '082125196335', 'ANGGI', 'PERUM CIWARU INDAH BLOK C NO. 22', '1985-01-22', '2026-03-31', '2026-04-04', 0, 1, 'L', NULL, 'MBR6TOS9HVYM2XOX', '2026-03-31 02:15:12', '2026-03-31 02:15:12', 0, NULL),
	(19, 18, 2, NULL, '22222', '082125196335', 'ANI', 'PERUM CIWARU INDAH BLOK C NO. 22', '1985-01-22', '2026-03-31', '2026-04-04', 0, 1, 'L', NULL, 'MBRA67KWQ8IDI28D', '2026-03-31 02:15:12', '2026-03-31 02:15:12', 0, NULL),
	(20, 0, 2, NULL, '1111', '085814065158', 'KIKO', 'PABUARAN BNR', '2007-04-17', '2026-03-31', '2026-04-30', 0, 1, 'L', NULL, 'MBRK875H1DWVIZZ6', '2026-03-31 04:12:24', '2026-03-31 04:12:24', 0, NULL),
	(21, 20, 2, NULL, '1111', '085814065158', 'ZAHRA', 'PABUARAN BNR', '2007-04-17', '2026-03-31', '2026-04-30', 0, 1, 'L', NULL, 'MBRARNLUATX4G8GS', '2026-03-31 04:12:24', '2026-03-31 04:12:24', 0, NULL);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.memberships: ~1 rows (approximately)
INSERT IGNORE INTO `memberships` (`id`, `name`, `duration_days`, `price`, `max_person`, `is_active`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
	(1, 'Single Monthly', 30, 250000, 1, 1, 0, 0.00, '2026-03-30 09:53:52', '2026-03-30 09:53:52'),
	(2, 'COUPLE MOUNTHLY', 30, 375000, 2, 1, 0, 0.00, '2026-03-30 10:55:31', '2026-03-31 04:11:30'),
	(3, 'Couple+1 Mounthly', 30, 500000, 3, 1, 0, 0.00, '2026-03-30 10:55:57', '2026-03-30 10:55:57'),
	(4, 'FAMILY MOUNTHLY', 30, 650000, 4, 1, 0, 0.00, '2026-03-30 10:56:25', '2026-03-30 10:56:25'),
	(5, 'FAMILY+1 MONTHLY', 30, 775000, 5, 1, 0, 0.00, '2026-03-30 10:57:27', '2026-03-30 10:57:27'),
	(6, 'FAMILY+2 MONTHLY', 30, 900000, 6, 1, 0, 0.00, '2026-03-30 10:57:55', '2026-03-30 10:57:55'),
	(7, 'RENEWAL FAMILY+2 MONTHLY', 30, 700000, 6, 1, 0, 0.00, '2026-03-30 10:59:21', '2026-03-30 10:59:21'),
	(8, 'SINGLE YEARLY', 360, 1192000, 1, 1, 0, 0.00, '2026-03-30 11:36:18', '2026-03-30 11:36:18'),
	(9, 'COUPLE YEARLY', 360, 2192000, 2, 1, 0, 0.00, '2026-03-30 11:36:46', '2026-03-30 11:36:46'),
	(10, 'COUPLE+1 YEARLY', 360, 2792000, 3, 1, 0, 0.00, '2026-03-30 11:37:21', '2026-03-30 11:37:21'),
	(11, 'FAMILY YEARLY', 360, 2992000, 4, 1, 0, 0.00, '2026-03-30 11:37:46', '2026-03-30 11:37:46'),
	(12, 'FAMILY+1 YEARLY', 360, 3592000, 5, 1, 0, 0.00, '2026-03-30 11:38:11', '2026-03-30 11:38:11'),
	(13, 'FAMILY+2 YEARLY', 360, 4192000, 6, 1, 0, 0.00, '2026-03-30 11:38:33', '2026-03-30 11:38:33');

-- Dumping structure for table db_membership.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.migrations: ~0 rows (approximately)
INSERT IGNORE INTO `migrations` (`id`, `migration`, `batch`) VALUES
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
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1);

-- Dumping structure for table db_membership.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.password_resets: ~0 rows (approximately)

-- Dumping structure for table db_membership.penyewaans
CREATE TABLE IF NOT EXISTS `penyewaans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sewa_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `metode` enum('Tap','Cash') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bayar` double NOT NULL,
  `kembali` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `penyewaans_sewa_id_foreign` (`sewa_id`),
  KEY `penyewaans_user_id_foreign` (`user_id`),
  CONSTRAINT `penyewaans_sewa_id_foreign` FOREIGN KEY (`sewa_id`) REFERENCES `sewa` (`id`),
  CONSTRAINT `penyewaans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.penyewaans: ~0 rows (approximately)

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
INSERT IGNORE INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'master-access', 'web', '2026-03-30 09:29:44', '2026-03-30 09:29:44'),
	(2, 'user-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(3, 'ticket-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(4, 'sewa-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(5, 'member-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(6, 'transaction-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(7, 'penyewaan-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(8, 'topup-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(9, 'report-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(10, 'report-transaction-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(11, 'report-penyewaan-access', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(12, 'transaction-delete', 'web', '2026-03-30 09:29:45', '2026-03-30 09:29:45'),
	(13, 'penyewaan-delete', 'web', '2026-03-30 09:29:46', '2026-03-30 09:29:46'),
	(14, 'topup-delete', 'web', '2026-03-30 09:29:46', '2026-03-30 09:29:46'),
	(15, 'management-access', 'web', '2026-03-30 09:29:46', '2026-03-30 09:29:46');

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

-- Dumping structure for table db_membership.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.roles: ~1 rows (approximately)
INSERT IGNORE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'web', '2026-03-30 09:29:46', '2026-03-30 09:29:46');

-- Dumping structure for table db_membership.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.role_has_permissions: ~15 rows (approximately)
INSERT IGNORE INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
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
	(15, 1);

-- Dumping structure for table db_membership.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ucapan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ppn` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `use_logo` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.settings: ~0 rows (approximately)
INSERT IGNORE INTO `settings` (`id`, `name`, `logo`, `ucapan`, `deskripsi`, `ppn`, `created_at`, `updated_at`, `use_logo`) VALUES
	(1, 'My Member ID', NULL, 'Terima Kasih', 'Terima kasih', 0, '2026-03-31 02:47:34', '2026-03-31 02:47:58', 0);

-- Dumping structure for table db_membership.sewa
CREATE TABLE IF NOT EXISTS `sewa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `device` int NOT NULL,
  `use_ppn` tinyint(1) NOT NULL DEFAULT '0',
  `ppn` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.sewa: ~4 rows (approximately)
INSERT IGNORE INTO `sewa` (`id`, `name`, `harga`, `device`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
	(1, 'Sewa Ban', 10000, 1, 0, 0.00, '2026-03-30 09:29:44', '2026-03-30 09:29:44'),
	(2, 'Sewa Baju Renang', 10000, 2, 0, 0.00, '2026-03-30 09:29:44', '2026-03-30 09:29:44'),
	(3, 'Sewa Pelampung', 10000, 3, 0, 0.00, '2026-03-30 09:29:44', '2026-03-30 09:29:44'),
	(4, 'Sewa Tikar', 10000, 4, 0, 0.00, '2026-03-30 09:29:44', '2026-03-30 09:29:44');

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

-- Dumping structure for table db_membership.tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis_ticket_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `tripod` int NOT NULL,
  `use_ppn` tinyint(1) NOT NULL DEFAULT '0',
  `ppn` decimal(12,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_jenis_ticket_id_foreign` (`jenis_ticket_id`),
  CONSTRAINT `tickets_jenis_ticket_id_foreign` FOREIGN KEY (`jenis_ticket_id`) REFERENCES `jenis_tickets` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.tickets: ~4 rows (approximately)
INSERT IGNORE INTO `tickets` (`id`, `jenis_ticket_id`, `name`, `harga`, `tripod`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
	(6, 1, 'Wp Weekday', 25000, 1, 0, 0.00, '2026-03-31 03:03:48', '2026-03-31 03:03:48'),
	(7, 1, 'WP WEEKEND', 35000, 1, 0, 0.00, '2026-03-31 03:05:42', '2026-03-31 03:05:42'),
	(8, 1, 'GYM WEEKDAY', 50000, 1, 0, 0.00, '2026-03-31 03:06:41', '2026-03-31 03:06:41'),
	(9, 1, 'GYM WEEKEND', 50000, 1, 0, 0.00, '2026-03-31 03:07:04', '2026-03-31 03:07:04'),
	(10, 1, 'ROMBONGAN SEKOLAH 25 ORANG', 20000, 1, 0, 0.00, '2026-03-31 03:08:12', '2026-03-31 03:08:12'),
	(11, 1, 'ROMBONGAN SEKOLAH 26-50 ORANG', 17500, 1, 0, 0.00, '2026-03-31 03:08:47', '2026-03-31 03:08:47'),
	(12, 1, 'ROMBONGAN SEKOLAH 51-75 ORANG', 15000, 1, 0, 0.00, '2026-03-31 03:09:38', '2026-03-31 03:09:38'),
	(13, 1, 'ROMBONGAN SEKOLAH 76-100 ORANG', 12500, 1, 0, 0.00, '2026-03-31 03:10:06', '2026-03-31 03:10:06'),
	(14, 1, 'ROMBONGAN SEKOLAH 100-UP', 10000, 1, 0, 0.00, '2026-03-31 03:10:37', '2026-03-31 03:10:37'),
	(15, 1, 'SEWA KANTIN', 1750000, 1, 0, 0.00, '2026-03-31 03:11:05', '2026-03-31 03:11:05');

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

-- Dumping structure for table db_membership.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned DEFAULT '0',
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.transactions: ~2 rows (approximately)
INSERT IGNORE INTO `transactions` (`id`, `ticket_id`, `user_id`, `no_trx`, `ticket_code`, `transaction_type`, `tipe`, `amount`, `disc`, `metode`, `discount`, `amount_scanned`, `status`, `gate`, `is_active`, `ppn`, `created_at`, `updated_at`, `is_print`, `bayar`, `kembali`) VALUES
	(1, 0, 1, 1, 'INV/30032026/3351', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-03-30 09:37:37', '2026-03-30 09:37:37', 0, 0, 0),
	(2, 1, 1, 2, 'REG/300320262140', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-03-30 09:54:19', '2026-03-30 09:54:19', 0, 250000, 0),
	(3, 1, 1, 3, 'REG/300320268853', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-03-30 11:00:53', '2026-03-30 11:00:53', 0, 250000, 0),
	(4, 7, 1, 4, 'REG/300320265419', 'registration', 'group', 6, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-03-30 11:05:10', '2026-03-30 11:05:10', 0, 700000, 0),
	(5, 2, 1, 5, 'REG/300320264301', 'registration', 'group', 2, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-03-30 11:06:29', '2026-03-30 11:06:29', 0, 375000, 0),
	(6, 4, 1, 6, 'REG/300320264304', 'registration', 'group', 4, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-03-30 11:08:07', '2026-03-30 11:08:07', 0, 650000, 0),
	(7, 3, 1, 7, 'REG/300320264962', 'registration', 'group', 3, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-03-30 11:09:06', '2026-03-30 11:09:06', 0, 500000, 0),
	(8, 0, 1, 1, 'INV/31032026/6973', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2026-03-31 00:48:54', '2026-03-31 00:48:54', 0, 0, 0),
	(10, 2, 1, 2, 'REG/310320263365', 'registration', 'group', 2, 0, 'cash', 0, 0, 'open', NULL, 1, 0.00, '2026-03-31 04:12:24', '2026-03-31 04:12:24', 0, 375000, 0);

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
INSERT IGNORE INTO `users` (`id`, `name`, `username`, `password`, `foto`, `created_at`, `updated_at`, `uid`, `is_active`) VALUES
	(1, 'Developer', 'developer', '$2y$10$GkfxVaUdGIbBnZ29fWJojeZFyfohYRAjlltnfmU2HZ5HYAAKffvSG', NULL, '2026-03-30 09:29:47', '2026-03-30 09:29:47', NULL, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
