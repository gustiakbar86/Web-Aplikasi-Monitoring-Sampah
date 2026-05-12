-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 16, 2025 at 08:48 AM
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
-- Database: `pwaste`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE `administrators` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_admin` varchar(255) NOT NULL,
  `email_admin` varchar(255) NOT NULL,
  `password_admin` varchar(255) NOT NULL,
  `role_admin` enum('super_admin','admin') NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`id`, `nama_admin`, `email_admin`, `password_admin`, `role_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@sipesa.com', '$2y$12$5e9ctAfMJgf5axxTNJs48.Loci.XXd1qOiNAKzpj06eTCmzzLfqsC', 'super_admin', NULL, '2025-12-11 16:26:45', '2025-12-11 16:26:45'),
(2, 'Admin', 'admin@sipesa.com', '$2y$12$O2eCZZyyjcr4kfJ968MXeOz1NPF3jkGE8.KbY5Pd.ipt58mys1aIC', 'admin', NULL, '2025-12-11 16:26:46', '2025-12-11 16:26:46');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumens`
--

CREATE TABLE `dokumens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `no_dokumen` varchar(255) NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `file_dokumen` varchar(255) NOT NULL,
  `instansi_kerjasama` varchar(255) DEFAULT NULL,
  `berlaku` tinyint(1) NOT NULL DEFAULT 1,
  `berakhir` date DEFAULT NULL,
  `keterangan_dokumen` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dokumens`
--

INSERT INTO `dokumens` (`id`, `id_user`, `no_dokumen`, `nama_dokumen`, `file_dokumen`, `instansi_kerjasama`, `berlaku`, `berakhir`, `keterangan_dokumen`, `created_at`, `updated_at`) VALUES
(1, 1, 'DOK-1-20251212', 'Rekap Pengelolaan Sampah September 2025', 'dokumen/rekap-sep-2025.pdf', 'Pelindo Subregional Banjarmasin', 1, '2026-12-12', 'Laporan rekap pengelolaan sampah bulan September 2025', '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(2, 1, 'DOK-2-20261212', 'SOP Pengelolaan Sampah', 'dokumen/sop-pengelolaan.pdf', 'Pelindo Subregional Banjarmasin', 1, '2027-12-12', 'Standar Operasional Prosedur', '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(3, 1, 'DOK-3-20271212', 'Kerjasama Pengelolaan Limbah', 'dokumen/kerjasama-limbah.pdf', 'Pelindo Subregional Banjarmasin', 1, '2028-12-12', 'Perjanjian kerjasama dengan pihak ketiga', '2025-12-11 16:26:48', '2025-12-11 16:26:48');

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
-- Table structure for table `instansis`
--

CREATE TABLE `instansis` (
  `id_instansi` bigint(20) UNSIGNED NOT NULL,
  `nama_instansi` varchar(255) NOT NULL,
  `kode_instansi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instansis`
--

INSERT INTO `instansis` (`id_instansi`, `nama_instansi`, `kode_instansi`, `created_at`, `updated_at`) VALUES
(1, 'Pelindo Terminal Petikemas', 'PTP001', '2025-12-11 16:26:46', '2025-12-11 16:26:46'),
(2, 'Pelindo Terminal Multipurpose', 'PTM0021', '2025-12-11 16:26:46', '2025-12-11 17:12:09'),
(3, 'Pelindo Terminal Penumpang', 'PTP003', '2025-12-11 16:26:46', '2025-12-11 16:26:46');

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` bigint(20) UNSIGNED NOT NULL,
  `kategori_jenis` enum('Organik','Anorganik','Residu') NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis`
--

INSERT INTO `jenis` (`id_jenis`, `kategori_jenis`, `nama_jenis`, `created_at`, `updated_at`) VALUES
(1, 'Organik', 'Organik', '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(2, 'Anorganik', 'Anorganik', '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(3, 'Residu', 'Residu', '2025-12-11 16:26:48', '2025-12-11 16:26:48');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_asals`
--

CREATE TABLE `lokasi_asals` (
  `id_lokasi` bigint(20) UNSIGNED NOT NULL,
  `nama_lokasi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lokasi_asals`
--

INSERT INTO `lokasi_asals` (`id_lokasi`, `nama_lokasi`, `created_at`, `updated_at`) VALUES
(1, 'Area Kantor', '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(2, 'Area Tempat Parkir/Taman/Jalan', '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(3, 'Area Ruang Tunggu', '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(4, 'Area Tempat Makan', '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(5, 'Sampah Kapal', '2025-12-11 16:26:48', '2025-12-11 23:30:53'),
(6, 'Area Lain', '2025-12-11 16:26:48', '2025-12-11 16:26:48');

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
(1, '2025_12_03_000001_create_administrators_table', 1),
(2, '2025_12_03_000002_create_instansis_table', 1),
(3, '2025_12_03_000003_create_users_table', 1),
(4, '2025_12_03_000004_create_jenis_table', 1),
(5, '2025_12_03_000005_create_lokasi_asals_table', 1),
(6, '2025_12_03_000006_create_tujuan_sampahs_table', 1),
(7, '2025_12_03_000007_create_sampah_terkelolas_table', 1),
(8, '2025_12_03_000008_create_sampah_diserahkans_table', 1),
(9, '2025_12_03_000009_create_dokumens_table', 1),
(10, '2025_12_03_000010_create_cache_table', 1),
(11, '2025_12_03_000011_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sampah_diserahkans`
--

CREATE TABLE `sampah_diserahkans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_lokasi` bigint(20) UNSIGNED NOT NULL,
  `id_jenis` bigint(20) UNSIGNED NOT NULL,
  `id_tujuan` bigint(20) UNSIGNED NOT NULL,
  `jumlah_berat` decimal(10,2) NOT NULL,
  `tgl_diserahkan` date NOT NULL,
  `foto_diserahkan` varchar(255) DEFAULT NULL,
  `alasan_edit` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sampah_diserahkans`
--

INSERT INTO `sampah_diserahkans` (`id`, `id_user`, `id_lokasi`, `id_jenis`, `id_tujuan`, `jumlah_berat`, `tgl_diserahkan`, `foto_diserahkan`, `alasan_edit`, `created_at`, `updated_at`) VALUES
(1, 3, 3, 2, 2, 152.00, '2025-10-24', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(2, 2, 5, 3, 2, 55.00, '2025-10-22', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(3, 3, 5, 3, 2, 158.00, '2025-08-06', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(4, 4, 5, 1, 3, 158.00, '2025-07-02', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(5, 2, 4, 2, 4, 206.00, '2025-07-07', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(6, 1, 2, 1, 3, 89.00, '2024-12-16', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(7, 2, 2, 1, 4, 165.00, '2025-04-05', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(8, 3, 4, 2, 1, 279.00, '2025-12-02', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(9, 2, 2, 2, 4, 168.00, '2025-11-07', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(10, 1, 3, 1, 3, 266.00, '2025-08-15', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(11, 1, 5, 1, 3, 231.00, '2025-10-19', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(12, 3, 5, 3, 3, 116.00, '2025-09-23', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(13, 3, 5, 2, 4, 93.00, '2025-06-27', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(14, 3, 6, 1, 1, 48.00, '2024-12-27', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(15, 3, 6, 2, 4, 152.00, '2025-10-08', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(16, 2, 6, 1, 1, 146.00, '2025-02-04', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(17, 4, 6, 1, 3, 35.00, '2025-06-12', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(18, 2, 3, 2, 2, 229.00, '2025-10-20', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(19, 4, 5, 2, 2, 182.00, '2025-08-10', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(20, 1, 2, 3, 2, 19.00, '2025-06-07', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(21, 2, 1, 2, 2, 29.00, '2025-06-27', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(22, 1, 5, 3, 1, 167.00, '2025-07-06', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(23, 4, 5, 1, 2, 44.00, '2025-03-22', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(24, 4, 2, 2, 4, 249.00, '2025-12-03', NULL, 'Kesalahan pada pengisian data kategori jenis dan jenis sampah yang mana seharusnya jenis sampahnya anorganik', '2025-12-11 16:26:49', '2025-12-14 17:32:54'),
(25, 2, 2, 3, 1, 21.00, '2025-06-16', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(26, 3, 6, 3, 3, 40.00, '2025-11-22', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(27, 2, 4, 2, 1, 155.00, '2025-06-13', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(28, 2, 6, 3, 1, 80.00, '2025-02-24', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(29, 2, 4, 2, 4, 206.00, '2025-10-12', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(30, 2, 2, 1, 4, 227.00, '2025-01-13', NULL, NULL, '2025-12-11 16:26:49', '2025-12-11 16:26:49'),
(31, 2, 1, 2, 3, 10.00, '2025-12-15', NULL, NULL, '2025-12-14 20:13:52', '2025-12-14 20:13:52');

-- --------------------------------------------------------

--
-- Table structure for table `sampah_terkelolas`
--

CREATE TABLE `sampah_terkelolas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_lokasi` bigint(20) UNSIGNED NOT NULL,
  `id_jenis` bigint(20) UNSIGNED NOT NULL,
  `jumlah_berat` decimal(10,2) NOT NULL,
  `tgl` date NOT NULL,
  `foto_kelola` varchar(255) DEFAULT NULL,
  `alasan_edit` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sampah_terkelolas`
--

INSERT INTO `sampah_terkelolas` (`id`, `id_user`, `id_lokasi`, `id_jenis`, `jumlah_berat`, `tgl`, `foto_kelola`, `alasan_edit`, `created_at`, `updated_at`) VALUES
(1, 3, 4, 2, 92.00, '2025-10-26', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(2, 1, 4, 1, 148.00, '2025-06-01', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(3, 2, 5, 2, 10.00, '2025-10-20', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(4, 2, 6, 1, 182.00, '2025-03-09', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(5, 3, 4, 3, 78.00, '2025-03-02', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(6, 1, 5, 1, 143.00, '2025-02-15', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(7, 3, 6, 2, 101.00, '2025-12-07', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(8, 3, 3, 2, 65.00, '2025-06-10', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(9, 1, 6, 3, 28.00, '2025-05-22', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(10, 3, 1, 1, 95.32, '2025-12-08', NULL, 'Kesalahan pada pengisian data Jenis Sampah', '2025-12-11 16:26:48', '2025-12-14 22:25:41'),
(11, 2, 6, 1, 142.00, '2025-02-11', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(12, 4, 6, 1, 179.00, '2025-05-03', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(13, 2, 2, 1, 76.00, '2025-11-23', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(14, 2, 4, 3, 159.00, '2025-03-29', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(15, 1, 1, 3, 103.00, '2024-12-25', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(16, 4, 4, 3, 82.00, '2025-10-11', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(17, 1, 6, 1, 49.00, '2025-05-19', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(18, 4, 5, 3, 142.00, '2025-04-10', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(20, 2, 4, 3, 119.00, '2025-01-12', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(21, 2, 6, 1, 149.00, '2025-05-31', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(22, 2, 3, 1, 175.00, '2025-08-09', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(23, 3, 3, 2, 28.00, '2025-03-02', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(24, 4, 1, 2, 180.00, '2025-10-08', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(25, 4, 1, 3, 195.00, '2025-10-04', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(26, 3, 5, 1, 65.00, '2025-03-30', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(27, 1, 5, 2, 142.00, '2025-10-31', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(28, 3, 6, 1, 165.00, '2025-05-12', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(29, 2, 2, 2, 186.00, '2025-08-13', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(30, 3, 2, 1, 25.00, '2025-07-12', NULL, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(31, 1, 3, 1, 100.98, '2025-12-15', NULL, NULL, '2025-12-14 22:26:16', '2025-12-14 22:26:16'),
(32, 2, 2, 1, 10.00, '2025-12-15', NULL, 'Kesalahan penginputan tanggal', '2025-12-14 22:26:37', '2025-12-14 22:33:59');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2l1iFv9cuzAGLDKZfydpWIFAd5bvI2O6wN4nsLTP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMjlmeVFXU210Sm12YzVGVm9UVFM1SXBwSEJHNEpXcjBoRlVTcTlXRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765869900),
('2xYjQa3Ylj5t1DYADilInCKgs7sbgIggwK89SyUk', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibDB5OU5sRjhDNVlocFU1aHFlTHFTUnZwRmhiNmFtNVh1TzJnSG1jSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765868462),
('62kJz5ugNGcZlkBF2NjOncapAvCURTS5cgYpYRt0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGlkWmJhN2dMSUx4S2U5TEJBUkFqRzdVRkwxbEY5SmN3YnpFY0VyUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765869073),
('A8XY4ZTUuXklb30dtABrRB5k9Em8ibaarcGZN2Af', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQnc1MWxVZ2JvOVhjNjVxZDlBeTZsbzl2bW5zVkVnMUw2NG96R0w2eCI7czo1OiJlcnJvciI7czo0MToiQW5kYSB0aWRhayBtZW1pbGlraSBha3NlcyBrZSBoYWxhbWFuIGluaS4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3Qvc3VwZXJhZG1pbi9kYXNoYm9hcmQ/aWQ9NmQwMTQzNDItZGRmOS00NjMzLWJhY2ItZDcxM2IwMTMwYjRiJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY1ODY4OTA1OTMxIjt9fQ==', 1765868906),
('DBBB04zy5E4wexfRorhsW8HkSgkeMiXxCSx1xZ48', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNGxUamxGWVphbENDd3k2a1AzOGFSQjZSZzE3eUh4cENyeE1iSFVnYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvYWRtaW4vZGFzaGJvYXJkP2ZpbHRlcl90eXBlPXllYXImaWRfaW5zdGFuc2k9JnllYXI9MjAyNSI7fXM6NjA6ImxvZ2luX2FkbWluaXN0cmF0b3JfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1765871095),
('FsyC5WJaDz7DNrkSY0gKfohh9fWCTkBXDFC9md7U', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0hMRGhFRGFFVkNqRDQ4TW1HZVpUdFNjdm04OFo4TmhwZnNWVGxXbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765868641),
('gD1K8Awg2TLnpapavnNXj1NcXFCbhXJhU1Ojoh5K', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkZTVWN0Z3YwOHVaeFJLMHhJdkw3UHlhbVNJeVhnOEs5UUdrdkJzUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765868327),
('hmBBJrYiFpWcfrOC09pXrIzUyGwG5foD0LbQv4kf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM1hnc0VhVnNQMkdXdnFWTmUxZHZWU04wSkxYamNKbTQ1bXIwMmJMSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765868850),
('hPueTH4q8hJ2UzQeGnPdGeoDSB12Jv9nqFHtc9vS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMEhsU2xXN0FheVQ4M1d6djk1aDY3R0lVS3B6SDV0WkcxWGk2TXZ0OCI7czo1OiJlcnJvciI7czo0MToiQW5kYSB0aWRhayBtZW1pbGlraSBha3NlcyBrZSBoYWxhbWFuIGluaS4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3Qvc3VwZXJhZG1pbi9kYXNoYm9hcmQ/aWQ9NmQwMTQzNDItZGRmOS00NjMzLWJhY2ItZDcxM2IwMTMwYjRiJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY1ODY5MzE1OTMyIjt9fQ==', 1765869316),
('IS1nUzZSyAzfYEHHhu2f4B7NZ97o5ZCYJB8RwS2z', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ3V2TVRuNjZYRzBiTDdYWUIyd01LdFdvdlVoNzVZbXc3cjkyV1dMViI7czo1OiJlcnJvciI7czo0MToiQW5kYSB0aWRhayBtZW1pbGlraSBha3NlcyBrZSBoYWxhbWFuIGluaS4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3Qvc3VwZXJhZG1pbi9kYXNoYm9hcmQ/aWQ9NmQwMTQzNDItZGRmOS00NjMzLWJhY2ItZDcxM2IwMTMwYjRiJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY1ODY4ODUwMzgxIjt9fQ==', 1765868850),
('NA8ycMlFqPB86ylYqPplOtxGVS3aDobwOZkD09JE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMzh1aHFRc3J5cjA3RnNPa3FOaFN1WngwNXR0QU9DZGJ4SGFHVEhUbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765869316),
('NMXrGWgaer6N8NHNKkjPrJ6lEto9hM74ZpgOUn6D', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMmtMRmxMU1ZUTE13dUdwNEZJWE1QekQ1T0VzbERxc2x0OUZNazIzNiI7czo1OiJlcnJvciI7czo0MToiQW5kYSB0aWRhayBtZW1pbGlraSBha3NlcyBrZSBoYWxhbWFuIGluaS4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3Qvc3VwZXJhZG1pbi9kYXNoYm9hcmQ/aWQ9ODI1ZmViMDUtNWI1MC00ZDljLThjY2QtNGU2ZmUzOWE4OGY4JnZzY29kZUJyb3dzZXJSZXFJZD0xNzY1ODY4MzI2NzYzIjt9fQ==', 1765868327),
('rsCb1MAeGpQXZ89YYoDVPNWYr0xwIwawnTlMtkkB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU0pGbTI1cmZZcFNtMzJaekViMnRiSnZKM1hYY0dZb29wQW1mMVZYTiI7czo1OiJlcnJvciI7czo0MToiQW5kYSB0aWRhayBtZW1pbGlraSBha3NlcyBrZSBoYWxhbWFuIGluaS4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3Qvc3VwZXJhZG1pbi9kYXNoYm9hcmQ/aWQ9NmQwMTQzNDItZGRmOS00NjMzLWJhY2ItZDcxM2IwMTMwYjRiJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY1ODY4NDYxNzgyIjt9fQ==', 1765868461),
('udDISTWQSDZJ9NZeVziCtLZlDDAyDgUFJEI2GCjT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicTNNbk9JWTJDMDBKZ3Z5b3dramJRN1VYb1BZbGFudVExWDVNZkZ0ZCI7czo1OiJlcnJvciI7czo0MToiQW5kYSB0aWRhayBtZW1pbGlraSBha3NlcyBrZSBoYWxhbWFuIGluaS4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3Qvc3VwZXJhZG1pbi9kYXNoYm9hcmQ/aWQ9NmQwMTQzNDItZGRmOS00NjMzLWJhY2ItZDcxM2IwMTMwYjRiJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY1ODY4NjQxNjY2Ijt9fQ==', 1765868641),
('uiCdSiHALl78y1amT9OQuKs1goSuTdGY1MevYly3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiazVFQTFIdm15d0t4VWRPdUNCT2xMQ2RJNm9mRDVRZTFiN0t4c3NPbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvYWRtaW4vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo2MDoibG9naW5fYWRtaW5pc3RyYXRvcl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1765871042),
('X3Y1BwrNE2hPG7RgbqOE0Vx21E0S6rx4q968Niy5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicHBOREZXdnQ4d2dhVWdJYWdrU3Voa3Jyb2dJSFNyd0xtcmxvdmliUyI7czo1OiJlcnJvciI7czo0MToiQW5kYSB0aWRhayBtZW1pbGlraSBha3NlcyBrZSBoYWxhbWFuIGluaS4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3Qvc3VwZXJhZG1pbi9kYXNoYm9hcmQ/aWQ9YzBjZGI5NTEtODA0MC00NTM0LTgwNWMtNzlhMmI3M2MzOWY1JnZzY29kZUJyb3dzZXJSZXFJZD0xNzY1ODY5OTAwMjkxIjt9fQ==', 1765869900),
('yEr5OwvQlcHqq1GGsbl1fdr8zzLvrv4iIWEzu6ar', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid1pEZEloWmp1TW1reUtKSTV4ZEc0ZHMyWUg4aHA5U1B1Q2h3dGdhMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765868906),
('ZulsnEg1XWyukFYlGW6kbiYpWjHn89iHeapelptL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.0 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSHJIUmhMRjJlSXoyeEk2UWJUeTc2WUZrdG5ra0FydzVtSGg1c2NlcyI7czo1OiJlcnJvciI7czo0MToiQW5kYSB0aWRhayBtZW1pbGlraSBha3NlcyBrZSBoYWxhbWFuIGluaS4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly9zaXBlc2FwZWxpbmRvLnRlc3Qvc3VwZXJhZG1pbi9kYXNoYm9hcmQ/aWQ9NmQwMTQzNDItZGRmOS00NjMzLWJhY2ItZDcxM2IwMTMwYjRiJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY1ODY5MDcyODM0Ijt9fQ==', 1765869073);

-- --------------------------------------------------------

--
-- Table structure for table `tujuan_sampahs`
--

CREATE TABLE `tujuan_sampahs` (
  `id_tujuan` bigint(20) UNSIGNED NOT NULL,
  `kategori_tujuan` varchar(255) NOT NULL,
  `nama_tujuan` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tujuan_sampahs`
--

INSERT INTO `tujuan_sampahs` (`id_tujuan`, `kategori_tujuan`, `nama_tujuan`, `alamat`, `status`, `created_at`, `updated_at`) VALUES
(1, 'sampah', 'TPA Pelabuhan', 'Jl. Pelabuhan No.1, Dekat Pelabuhan', 1, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(2, 'sampah', 'TPA Sekitar Pelabuhan', 'Jl. Dermaga Raya, Dekat Pelabuhan', 1, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(3, 'sampah', 'Bank Sampah Pelabuhan', 'Komplek Pelabuhan, Dekat Dermaga', 1, '2025-12-11 16:26:48', '2025-12-11 16:26:48'),
(4, 'lb3', 'Pengelola Limbah Khusus Pelabuhan', 'Jl. Industri Pelabuhan, Dekat Pelabuhan', 1, '2025-12-11 16:26:48', '2025-12-11 16:26:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `id_instansi` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `id_instansi`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'petugasaa', 'superadmin@example.com', NULL, '$2y$12$VZVkuJLovikw3GdV/ScaS.ENho4oy8gUWO8sJPqO5WJLIOQKC8wEO', 3, NULL, '2025-12-11 16:26:46', '2025-12-11 17:28:45'),
(2, 'Admin', 'admin@example.com', NULL, '$2y$12$p0XEYU5HDKmqWvhc00RuCul5DJFyVJ/esPQV4CCf3.Znf5jqQqona', 1, NULL, '2025-12-11 16:26:47', '2025-12-11 16:26:47'),
(3, 'Petugas1', 'petugas1@example.com', NULL, '$2y$12$aejOMlxHvIOSz4J008dG..Aq8hVXznnaUBHgQV0mw/7iGVn/LgxXW', 1, NULL, '2025-12-11 16:26:47', '2025-12-11 16:26:47'),
(4, 'Petugas4', 'petugas4@example.com', NULL, '$2y$12$ZvPjs8WB6EgkXLHV/.xcdu46a9k8aTFShorx4KOWvr/uUr9.tV8TO', 1, NULL, '2025-12-11 16:26:48', '2025-12-11 16:26:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `administrators_email_admin_unique` (`email_admin`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dokumens`
--
ALTER TABLE `dokumens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dokumens_no_dokumen_unique` (`no_dokumen`),
  ADD KEY `dokumens_id_user_foreign` (`id_user`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `instansis`
--
ALTER TABLE `instansis`
  ADD PRIMARY KEY (`id_instansi`),
  ADD UNIQUE KEY `instansis_kode_instansi_unique` (`kode_instansi`);

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_asals`
--
ALTER TABLE `lokasi_asals`
  ADD PRIMARY KEY (`id_lokasi`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sampah_diserahkans`
--
ALTER TABLE `sampah_diserahkans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sampah_diserahkans_id_user_foreign` (`id_user`),
  ADD KEY `sampah_diserahkans_id_lokasi_foreign` (`id_lokasi`),
  ADD KEY `sampah_diserahkans_id_jenis_foreign` (`id_jenis`),
  ADD KEY `sampah_diserahkans_id_tujuan_foreign` (`id_tujuan`);

--
-- Indexes for table `sampah_terkelolas`
--
ALTER TABLE `sampah_terkelolas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sampah_terkelolas_id_user_foreign` (`id_user`),
  ADD KEY `sampah_terkelolas_id_lokasi_foreign` (`id_lokasi`),
  ADD KEY `sampah_terkelolas_id_jenis_foreign` (`id_jenis`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tujuan_sampahs`
--
ALTER TABLE `tujuan_sampahs`
  ADD PRIMARY KEY (`id_tujuan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_id_instansi_foreign` (`id_instansi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dokumens`
--
ALTER TABLE `dokumens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instansis`
--
ALTER TABLE `instansis`
  MODIFY `id_instansi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jenis`
--
ALTER TABLE `jenis`
  MODIFY `id_jenis` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lokasi_asals`
--
ALTER TABLE `lokasi_asals`
  MODIFY `id_lokasi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sampah_diserahkans`
--
ALTER TABLE `sampah_diserahkans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sampah_terkelolas`
--
ALTER TABLE `sampah_terkelolas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tujuan_sampahs`
--
ALTER TABLE `tujuan_sampahs`
  MODIFY `id_tujuan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumens`
--
ALTER TABLE `dokumens`
  ADD CONSTRAINT `dokumens_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sampah_diserahkans`
--
ALTER TABLE `sampah_diserahkans`
  ADD CONSTRAINT `sampah_diserahkans_id_jenis_foreign` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`) ON DELETE CASCADE,
  ADD CONSTRAINT `sampah_diserahkans_id_lokasi_foreign` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi_asals` (`id_lokasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `sampah_diserahkans_id_tujuan_foreign` FOREIGN KEY (`id_tujuan`) REFERENCES `tujuan_sampahs` (`id_tujuan`) ON DELETE CASCADE,
  ADD CONSTRAINT `sampah_diserahkans_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sampah_terkelolas`
--
ALTER TABLE `sampah_terkelolas`
  ADD CONSTRAINT `sampah_terkelolas_id_jenis_foreign` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`) ON DELETE CASCADE,
  ADD CONSTRAINT `sampah_terkelolas_id_lokasi_foreign` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi_asals` (`id_lokasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `sampah_terkelolas_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_instansi_foreign` FOREIGN KEY (`id_instansi`) REFERENCES `instansis` (`id_instansi`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
