-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Okt 2026 pada 06.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spa_web`
--
CREATE DATABASE IF NOT EXISTS `spa_web` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `spa_web`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `active_sessions`
--

CREATE TABLE `active_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `therapist_name` varchar(255) DEFAULT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_duration_minutes` int(11) NOT NULL DEFAULT 60,
  `remaining_seconds` int(11) NOT NULL DEFAULT 3600,
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'running',
  `ended_at` timestamp NULL DEFAULT NULL,
  `is_paused` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `active_sessions`
--

INSERT INTO `active_sessions` (`id`, `booking_id`, `customer_name`, `service_name`, `therapist_name`, `started_at`, `total_duration_minutes`, `remaining_seconds`, `last_synced_at`, `status`, `ended_at`, `is_paused`, `created_at`, `updated_at`) VALUES
(1, 11, 'Haikal Ahmad Ridha', 'Balinese Massage', 'Putri Indah', '2026-10-08 01:40:41', 60, 0, '2026-10-08 01:40:41', 'ended', '2026-10-08 01:40:41', 0, '2026-10-07 06:03:45', '2026-10-08 01:40:41'),
(2, 12, 'putri', 'Swedish Massage', 'Sri Mulyani', '2026-10-07 18:48:30', 150, 0, NULL, 'ended', NULL, 0, '2026-10-07 18:48:30', '2026-10-07 18:59:32'),
(3, 13, 'Test Customer', 'Swedish Massage', 'Sri Mulyani', '2026-10-07 19:40:20', 75, 0, '2026-10-07 19:49:14', 'ended', '2026-10-07 19:49:14', 0, '2026-10-07 19:40:20', '2026-10-07 19:49:14'),
(6, 16, 'putri', 'Swedish Massage', 'Ratna Dewi', '2026-10-07 19:51:35', 120, 0, '2026-10-07 19:59:42', 'ended', '2026-10-07 19:59:42', 0, '2026-10-07 19:51:35', '2026-10-07 19:59:42'),
(7, 17, 'Haikal Ahmad Ridha', 'Facial Treatment', 'Ratna Dewi', '2026-10-08 01:17:32', 120, 0, '2026-10-08 02:00:43', 'ended', '2026-10-08 02:00:43', 0, '2026-10-08 01:17:32', '2026-10-08 02:00:43'),
(8, 18, 'putri', 'Swedish Massage', 'Ratna Dewi', '2026-10-08 07:12:48', 180, 0, '2026-10-08 07:37:16', 'ended', '2026-10-08 07:37:16', 0, '2026-10-08 07:12:48', '2026-10-08 07:37:16'),
(9, 19, 'Haikal Ahmad Ridha', 'Swedish Massage', 'Ayu Lestari', '2026-10-08 07:17:02', 60, 0, '2026-10-08 07:26:55', 'ended', '2026-10-08 07:26:55', 0, '2026-10-08 07:17:02', '2026-10-08 07:26:55'),
(10, 20, 'Haikal Ahmad Ridha', 'Swedish Massage', 'Ayu Lestari', '2026-10-08 07:34:34', 60, 0, '2026-10-08 07:36:50', 'ended', '2026-10-08 07:36:50', 0, '2026-10-08 07:34:34', '2026-10-08 07:36:50'),
(11, 21, 'Haikal Ahmad Ridha', 'Swedish Massage', 'Ratna Dewi', '2026-10-14 23:15:53', 30, 0, '2026-10-15 01:38:16', 'ended', '2026-10-15 01:38:16', 0, '2026-10-14 23:15:53', '2026-10-15 01:38:16'),
(12, 22, 'Haikal Ahmad Ridha', 'Couple Spa Package', 'Putri Indah', '2026-10-16 08:23:44', 30, 0, '2026-10-16 08:56:34', 'ended', '2026-10-16 08:56:34', 0, '2026-10-16 08:23:44', '2026-10-16 08:56:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_code` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `spa_service_id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `therapist_id` bigint(20) UNSIGNED DEFAULT NULL,
  `therapist_name` varchar(255) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `booking_time` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'QRIS / E-Wallet',
  `status` varchar(255) NOT NULL DEFAULT 'confirmed',
  `notes` text DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `user_id`, `customer_name`, `customer_phone`, `spa_service_id`, `service_name`, `therapist_id`, `therapist_name`, `booking_date`, `booking_time`, `duration`, `price`, `payment_method`, `status`, `notes`, `is_new`, `created_at`, `updated_at`) VALUES
(11, 'SPA-20261007-6XJJ', 7, 'Haikal Ahmad Ridha', '085624401245', 2, 'Balinese Massage', 5, 'Putri Indah', '2026-10-07', '19:30', 210, 700.00, 'QRIS / E-Wallet', 'completed', NULL, 0, '2026-10-07 06:03:24', '2026-10-08 01:40:41'),
(12, 'SPA-20261008-MYFN', 8, 'putri', '085862893154', 1, 'Swedish Massage', 2, 'Sri Mulyani', '2026-10-08', '09:00', 150, 560.00, 'QRIS / E-Wallet', 'completed', NULL, 0, '2026-10-07 18:47:29', '2026-10-07 18:58:41'),
(13, 'TEST-1791427220', 1, 'Test Customer', '081234567890', 1, 'Swedish Massage', 2, 'Sri Mulyani', '2026-10-08', '15:00', 75, 350.00, 'QRIS', 'completed', NULL, 0, '2026-10-07 19:40:20', '2026-10-07 19:49:14'),
(16, 'SPA-20261008-LZ8W', 8, 'putri', '085862893154', 1, 'Swedish Massage', 3, 'Ratna Dewi', '2026-10-08', '10:30', 120, 560.00, 'QRIS / E-Wallet', 'completed', NULL, 0, '2026-10-07 19:50:48', '2026-10-07 20:00:17'),
(17, 'SPA-20261008-08VZ', 7, 'Haikal Ahmad Ridha', '085624401245', 5, 'Facial Treatment', 3, 'Ratna Dewi', '2026-10-08', '09:00', 120, 560.00, 'QRIS / E-Wallet', 'completed', NULL, 0, '2026-10-08 01:16:38', '2026-10-08 02:00:43'),
(18, 'SPA-20261008-DZLF', 8, 'putri', '085862893154', 1, 'Swedish Massage', 3, 'Ratna Dewi', '2026-10-08', '09:00', 180, 840.00, 'QRIS / E-Wallet', 'completed', NULL, 0, '2026-10-08 07:11:17', '2026-10-08 07:37:16'),
(19, 'SPA-20261008-IKLU', 7, 'Haikal Ahmad Ridha', '085624401245', 1, 'Swedish Massage', 4, 'Ayu Lestari', '2026-10-08', '09:00', 60, 280.00, 'QRIS / E-Wallet', 'completed', NULL, 0, '2026-10-08 07:13:39', '2026-10-08 07:26:55'),
(20, 'SPA-20261008-ZBVU', 7, 'Haikal Ahmad Ridha', '085624401245', 1, 'Swedish Massage', 4, 'Ayu Lestari', '2026-10-08', '16:00', 60, 280.00, 'Tunai di Kasir', 'completed', NULL, 0, '2026-10-08 07:30:36', '2026-10-08 07:36:50'),
(21, 'SPA-20261015-075I', 7, 'Haikal Ahmad Ridha', '085624401245', 1, 'Swedish Massage', 3, 'Ratna Dewi', '2026-10-15', '14:30', 30, 140.00, 'QRIS / E-Wallet', 'completed', NULL, 0, '2026-10-14 23:15:19', '2026-10-15 01:38:16'),
(22, 'SPA-20261016-UT1Q', 7, 'Haikal Ahmad Ridha', '085624401245', 7, 'Couple Spa Package', 5, 'Putri Indah', '2026-10-16', '19:30', 30, 213.00, 'QRIS / E-Wallet', 'completed', NULL, 0, '2026-10-16 08:18:34', '2026-10-16 08:56:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'operasional',
  `amount` decimal(15,2) NOT NULL,
  `expense_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_07_000001_create_spa_services_table', 1),
(5, '2026_10_07_000002_create_therapists_table', 1),
(6, '2026_10_07_000003_create_bookings_table', 1),
(7, '2026_10_07_000004_create_active_sessions_table', 1),
(8, '2026_10_08_022458_update_sessions_and_therapists_tables', 2),
(9, '2026_10_14_000010_create_expenses_table', 3),
(10, '2026_10_17_000010_add_is_new_to_bookings_table', 4),
(11, '2026_10_17_000011_add_pricing_tiers_to_spa_services_table', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `spa_services`
--

CREATE TABLE `spa_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT 60,
  `price` decimal(12,2) NOT NULL,
  `pricing_tiers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pricing_tiers`)),
  `image_url` text DEFAULT NULL,
  `rating` decimal(3,1) NOT NULL DEFAULT 5.0,
  `benefits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`benefits`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `spa_services`
--

INSERT INTO `spa_services` (`id`, `name`, `category`, `description`, `duration`, `price`, `pricing_tiers`, `image_url`, `rating`, `benefits`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Balinese Massage', 'Massage', 'A traditional massage from Bali that combines gentle to firm pressure, long strokes, and aromatherapy oils to relieve muscle tension, improve blood circulation, and promote deep relaxation.', 60, 150.00, '{\"30\":75,\"60\":150,\"90\":225,\"120\":300,\"150\":375,\"180\":450,\"210\":525,\"240\":600}', 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=800', 4.9, '[\"Gentle to firm pressure\",\"Relieve muscle tension\",\"Improve blood circulation\",\"Deep relaxation\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00'),
(2, 'Thai Massage', 'Massage', 'An ancient Thai therapy that combines acupressure, stretching, and assisted yoga postures to improve flexibility, relieve muscle tension, and balance energy flow.', 60, 150.00, '{\"30\":75,\"60\":150,\"90\":225,\"120\":300,\"150\":375,\"180\":450,\"210\":525,\"240\":600}', 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&q=80&w=800', 4.8, '[\"Ancient Thai therapy\",\"Acupressure & stretching\",\"Improve flexibility\",\"Balance energy flow\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00'),
(3, 'Sports Massage', 'Massage', 'Designed for active individuals and athletes to reduce muscle soreness, prevent injuries, improve performance, and speed up recovery.', 60, 150.00, '{\"30\":75,\"60\":150,\"90\":225,\"120\":300,\"150\":375,\"180\":450,\"210\":525,\"240\":600}', 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&q=80&w=800', 4.9, '[\"For active individuals\",\"Reduce muscle soreness\",\"Prevent injuries\",\"Speed up recovery\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00'),
(4, 'Shiatsu Massage', 'Massage', 'A Japanese massage technique using finger pressure on specific points to restore energy flow, reduce stress, and promote overall well-being.', 60, 150.00, '{\"30\":75,\"60\":150,\"90\":225,\"120\":300,\"150\":375,\"180\":450,\"210\":525,\"240\":600}', 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&q=80&w=800', 4.8, '[\"Japanese technique\",\"Finger pressure points\",\"Restore energy flow\",\"Reduce stress\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00'),
(5, 'Massage with Ice Pool', 'Special Therapy', 'A refreshing therapy that combines therapeutic massage with an ice pool immersion to reduce inflammation, relieve muscle fatigue, and rejuvenate the body.', 60, 200.00, '{\"30\":100,\"60\":200,\"90\":300,\"120\":400,\"150\":500,\"180\":600,\"210\":700,\"240\":800}', 'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&q=80&w=800', 4.9, '[\"Therapeutic massage\",\"Ice pool immersion\",\"Reduce inflammation\",\"Rejuvenate the body\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00'),
(6, 'Massage with Cupping', 'Special Therapy', 'This therapy uses suction cups to stimulate blood flow, release deep muscle tension, detoxify the body, and promote healing.', 60, 200.00, '{\"30\":100,\"60\":200,\"90\":300,\"120\":400,\"150\":500,\"180\":600,\"210\":700,\"240\":800}', 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&q=80&w=800', 4.8, '[\"Suction cup therapy\",\"Stimulate blood flow\",\"Release deep tension\",\"Detoxify the body\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00'),
(7, 'Massage with Hot Stone', 'Special Therapy', 'Warm volcanic stones are placed on specific points and used in massage to relax muscles, improve circulation, and induce deep relaxation.', 60, 200.00, '{\"30\":100,\"60\":200,\"90\":300,\"120\":400,\"150\":500,\"180\":600,\"210\":700,\"240\":800}', 'https://images.unsplash.com/photo-1559599101-f09722fb4948?auto=format&fit=crop&q=80&w=800', 4.9, '[\"Warm volcanic stones\",\"Relax deep muscles\",\"Improve circulation\",\"Induce deep relaxation\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00'),
(8, 'Leg Massage', 'Targeted Massage', 'Focuses on the legs and feet to improve circulation, relieve swelling, reduce muscle tension, and promote overall relaxation.', 30, 70.00, '{\"30\":70,\"60\":100,\"90\":150,\"120\":200,\"150\":250,\"180\":300,\"210\":350,\"240\":400}', 'https://images.unsplash.com/photo-1519824145371-296894a0daa9?auto=format&fit=crop&q=80&w=800', 4.7, '[\"Focus on legs & feet\",\"Improve circulation\",\"Relieve swelling\",\"Reduce muscle tension\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00'),
(9, 'Back Massage', 'Targeted Massage', 'Targets the back, shoulders, and neck to relieve pain, reduce stiffness, and improve posture.', 30, 80.00, '{\"30\":80,\"60\":160,\"90\":240,\"120\":320,\"150\":400,\"180\":480,\"210\":560,\"240\":640}', 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&q=80&w=800', 4.8, '[\"Back, shoulders & neck\",\"Relieve back pain\",\"Reduce stiffness\",\"Improve posture\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00'),
(10, 'Only Ice Bath', 'Hydrotherapy', 'Ice bath immersion helps reduce muscle inflammation, speed up recovery, boost circulation, and refresh the body and mind.', 30, 50.00, '{\"30\":50,\"60\":100,\"90\":150,\"120\":200,\"150\":250,\"180\":300,\"210\":350,\"240\":400}', 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?auto=format&fit=crop&q=80&w=800', 4.9, '[\"Ice bath immersion\",\"Reduce muscle inflammation\",\"Speed up recovery\",\"Boost circulation\"]', 1, '2026-10-17 06:06:19', '2026-10-17 07:39:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `therapists`
--

CREATE TABLE `therapists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `speciality` varchar(255) NOT NULL,
  `experience` int(11) NOT NULL DEFAULT 1,
  `rating` decimal(3,1) NOT NULL DEFAULT 5.0,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ready',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `therapists`
--

INSERT INTO `therapists` (`id`, `name`, `speciality`, `experience`, `rating`, `phone`, `avatar`, `status`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Dewi Sartika', 'Swedish & Balinese Massage', 8, 4.9, '087700000001', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=250', 'off', 0, '2026-10-07 05:54:55', '2026-10-07 19:25:35'),
(2, 'Sri Mulyani', 'Aromatherapy & Facial', 5, 4.8, '087700000002', 'https://images.unsplash.com/photo-1584361853901-dd1904bb7987?auto=format&fit=crop&q=80&w=250', 'off', 0, '2026-10-07 05:54:55', '2026-10-08 01:13:33'),
(3, 'Ratna Dewi', 'Deep Tissue & Hot Stone', 10, 5.0, '087700000003', 'https://images.unsplash.com/photo-1573497019236-17f8177b81e8?auto=format&fit=crop&q=80&w=250', 'ready', 1, '2026-10-07 05:54:55', '2026-10-15 01:38:16'),
(4, 'Ayu Lestari', 'Body Treatment & Scrub', 6, 4.7, '087700000004', 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&q=80&w=250', 'ready', 1, '2026-10-07 05:54:55', '2026-10-08 07:36:50'),
(5, 'Putri Indah', 'Couple Spa & Romantic Package', 4, 4.9, '087700000005', 'https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&q=80&w=250', 'ready', 1, '2026-10-07 05:54:55', '2026-10-16 08:56:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `role`, `avatar`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Serenity', 'admin@serenityspace.id', '081200000001', 'admin', 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&q=80&w=250', NULL, '$2y$12$TwgIuSpZiiFKF9JP9UhEAePm1ZDgT5.wwl2NIT5qWNKQDRxw.JJba', NULL, '2026-10-07 05:54:53', '2026-10-07 05:54:53'),
(7, 'Haikal Ahmad Ridha', NULL, '085624401245', 'customer', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250', NULL, '$2y$12$nVktui0.hAiL0uCnre2k7epqaJT8MVGpNBxcqyNtAAnSl2082/4hG', NULL, '2026-10-07 06:02:44', '2026-10-08 00:42:35'),
(8, 'putri', NULL, '085862893154', 'customer', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250', NULL, '$2y$12$f0IuqSlb3PiCe3i/zdW1F.8kKSgRVGR8XRxni.XfCZ95PcAzNLLQi', NULL, '2026-10-07 18:46:54', '2026-10-07 18:46:54'),
(9, 'Owner F55 (Bos)', 'bos@f55center.id', '081200000002', 'bos', NULL, NULL, '$2y$12$Nszk3l7ta.UBxsN2iqmiYefWxV.3CO9862dq9CVxAAicPDlvVxvUC', NULL, '2026-10-14 02:49:59', '2026-10-14 02:49:59');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active_sessions_booking_id_foreign` (`booking_id`);

--
-- Indeks untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_spa_service_id_foreign` (`spa_service_id`),
  ADD KEY `bookings_therapist_id_foreign` (`therapist_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `spa_services`
--
ALTER TABLE `spa_services`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `therapists`
--
ALTER TABLE `therapists`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `active_sessions`
--
ALTER TABLE `active_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `spa_services`
--
ALTER TABLE `spa_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `therapists`
--
ALTER TABLE `therapists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD CONSTRAINT `active_sessions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_spa_service_id_foreign` FOREIGN KEY (`spa_service_id`) REFERENCES `spa_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_therapist_id_foreign` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
