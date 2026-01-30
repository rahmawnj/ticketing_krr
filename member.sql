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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.detail_transactions: ~0 rows (approximately)
DELETE FROM `detail_transactions`;

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
	(1, '1', 'Depan', 1, '2025-12-08 08:29:23', '2025-12-08 08:29:23');

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
DELETE FROM `gate_access_membership`;
INSERT INTO `gate_access_membership` (`gate_access_id`, `membership_id`) VALUES
	(1, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.histories: ~0 rows (approximately)
DELETE FROM `histories`;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.history_memberships: ~1 rows (approximately)
DELETE FROM `history_memberships`;
INSERT INTO `history_memberships` (`id`, `membership_id`, `member_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, '2025-12-08', '2026-01-07', 'active', '2025-12-08 08:29:57', '2025-12-08 08:29:57');

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
	(1, 'Reguler', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(2, 'Terusan', '2025-12-08 08:25:06', '2025-12-08 08:25:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.members: ~1 rows (approximately)
DELETE FROM `members`;
INSERT INTO `members` (`id`, `parent_id`, `membership_id`, `rfid`, `no_ktp`, `no_hp`, `nama`, `alamat`, `tgl_lahir`, `tgl_register`, `tgl_expired`, `saldo`, `is_active`, `jenis_kelamin`, `image_profile`, `qr_code`, `created_at`, `updated_at`, `limit`, `jenis_member`) VALUES
	(1, 0, 1, '787979798798798', '644444', '8080', 'Moniq', 'Bandung', '2000-01-01', '2025-12-08', '2026-01-07', 0, 1, 'L', NULL, 'MBRY1WBO11WAX1ZD', '2025-12-08 08:29:57', '2025-12-08 08:29:57', 0, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.memberships: ~1 rows (approximately)
DELETE FROM `memberships`;
INSERT INTO `memberships` (`id`, `name`, `duration_days`, `price`, `max_person`, `is_active`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
	(1, 'Orang Satuan', 30, 20000, 1, 1, 1, 4000.00, '2025-12-08 08:29:32', '2025-12-08 08:29:32');

-- Dumping structure for table db_membership.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.migrations: ~0 rows (approximately)
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
DELETE FROM `penyewaans`;

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
	(1, 'master-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(2, 'user-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(3, 'ticket-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(4, 'sewa-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(5, 'member-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(6, 'transaction-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(7, 'penyewaan-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(8, 'topup-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(9, 'report-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(10, 'report-transaction-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(11, 'report-penyewaan-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(12, 'transaction-delete', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(13, 'penyewaan-delete', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(14, 'topup-delete', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(15, 'management-access', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.roles: ~1 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'web', '2025-12-08 08:25:06', '2025-12-08 08:25:06');

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

-- Dumping data for table db_membership.settings: ~1 rows (approximately)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `name`, `logo`, `ucapan`, `deskripsi`, `ppn`, `created_at`, `updated_at`, `use_logo`) VALUES
	(1, 'MyMember', NULL, 'Terimakasih!', 'datang lagi ya!', 20, '2025-12-08 08:28:17', '2025-12-08 08:28:17', 0);

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
DELETE FROM `sewa`;
INSERT INTO `sewa` (`id`, `name`, `harga`, `device`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
	(1, 'Sewa Ban', 10000, 1, 1, 2000.00, '2025-12-08 08:25:06', '2025-12-08 08:28:27'),
	(2, 'Sewa Baju Renang', 10000, 2, 1, 2000.00, '2025-12-08 08:25:06', '2025-12-08 08:28:31'),
	(3, 'Sewa Pelampung', 10000, 3, 1, 2000.00, '2025-12-08 08:25:06', '2025-12-08 08:28:38'),
	(4, 'Sewa Tikar', 10000, 4, 0, 0.00, '2025-12-08 08:25:06', '2025-12-08 08:25:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.tickets: ~4 rows (approximately)
DELETE FROM `tickets`;
INSERT INTO `tickets` (`id`, `jenis_ticket_id`, `name`, `harga`, `tripod`, `use_ppn`, `ppn`, `created_at`, `updated_at`) VALUES
	(1, 1, 'HTM Reguler Weekday', 20000, 1, 0, 0.00, '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(2, 1, 'HTM Reguler Weekend', 30000, 2, 0, 0.00, '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(3, 1, 'HTM Terusan Weekday', 40000, 3, 0, 0.00, '2025-12-08 08:25:06', '2025-12-08 08:25:06'),
	(4, 1, 'HTM Terusan Weekend', 50000, 4, 0, 0.00, '2025-12-08 08:25:06', '2025-12-08 08:25:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_membership.transactions: ~2 rows (approximately)
DELETE FROM `transactions`;
INSERT INTO `transactions` (`id`, `ticket_id`, `user_id`, `no_trx`, `ticket_code`, `transaction_type`, `tipe`, `amount`, `disc`, `metode`, `discount`, `amount_scanned`, `status`, `gate`, `is_active`, `ppn`, `created_at`, `updated_at`, `is_print`, `bayar`, `kembali`) VALUES
	(1, 0, 1, 1, 'INV/08122025/2981', 'ticket', 'group', 0, 0, NULL, 0, 0, 'open', NULL, 0, 0.00, '2025-12-08 08:26:00', '2025-12-08 08:26:00', 0, 0, 0),
	(2, 0, 1, 2, 'REG/081220254986', 'registration', 'individual', 1, 0, 'cash', 0, 0, 'open', NULL, 1, 4000.00, '2025-12-08 08:29:57', '2025-12-08 08:29:57', 0, 20000, 0);

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
	(1, 'Developer', 'developer', '$2y$10$F0YrfJyMQE3IjvyVdVvjgeksAFradIAKiAe.phvsUA4SDANlNP6t2', NULL, '2025-12-08 08:25:06', '2025-12-08 08:25:06', NULL, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
