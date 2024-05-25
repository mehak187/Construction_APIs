-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 26, 2023 at 03:28 PM
-- Server version: 10.5.19-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u664450317_bayinshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `ch_favorites`
--

CREATE TABLE `ch_favorites` (
  `id` char(36) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `favorite_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ch_messages`
--

CREATE TABLE `ch_messages` (
  `id` char(36) NOT NULL,
  `from_id` bigint(20) NOT NULL,
  `to_id` bigint(20) NOT NULL,
  `body` varchar(5000) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ch_messages`
--

INSERT INTO `ch_messages` (`id`, `from_id`, `to_id`, `body`, `attachment`, `seen`, `created_at`, `updated_at`) VALUES
('46e7c851-e214-4828-8dfb-e398df7d6ced', 9, 2, 'bzhshhs', NULL, 0, '2023-07-26 09:09:30', '2023-07-26 09:09:30'),
('e04d85e1-3c68-4cf8-b7b6-fe5e538aa8b0', 9, 2, '🤡', NULL, 0, '2023-07-26 09:10:24', '2023-07-26 09:10:24');

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
-- Table structure for table `merchants`
--

CREATE TABLE `merchants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `latitude` text DEFAULT NULL,
  `longitude` text DEFAULT NULL,
  `type_of_activity` text DEFAULT NULL,
  `contact` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merchants`
--

INSERT INTO `merchants` (`id`, `user_id`, `name`, `email`, `address`, `city`, `latitude`, `longitude`, `type_of_activity`, `contact`, `created_at`, `updated_at`) VALUES
(1, 1, 'merchant', 'merchant@gmail.com', 'abc', 'xyz', '10', '20', 'type', '+923026490627', '2023-06-21 11:23:32', '2023-06-21 11:23:32');

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
(13, '2014_10_12_000000_create_users_table', 1),
(14, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(15, '2014_10_12_100000_create_password_resets_table', 1),
(16, '2019_08_19_000000_create_failed_jobs_table', 1),
(17, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(18, '2023_06_17_063834_create_products_table', 1),
(19, '2023_06_17_125627_create_vote_limits_table', 1),
(20, '2023_06_17_133326_create_vote_products_table', 1),
(22, '2023_06_19_060401_create_promotions_table', 2),
(23, '2023_06_19_999999_add_active_status_to_users', 3),
(24, '2023_06_19_999999_add_avatar_to_users', 3),
(25, '2023_06_19_999999_add_dark_mode_to_users', 3),
(26, '2023_06_19_999999_add_messenger_color_to_users', 3),
(27, '2023_06_19_999999_create_chatify_favorites_table', 3),
(28, '2023_06_19_999999_create_chatify_messages_table', 3),
(30, '2023_06_19_130738_create_merchants_table', 4),
(31, '2023_06_20_062608_create_payments_table', 5),
(32, '2023_06_21_070858_create_notifications_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('088ebf10-ec49-4a4c-a3a8-5aab0ccdd7f6', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":20}', NULL, '2023-08-05 07:05:00', '2023-08-05 07:05:00'),
('0fc7de6a-0be8-4769-8fe1-0df30a71e1ba', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":22}', NULL, '2023-08-05 07:06:40', '2023-08-05 07:06:40'),
('3b4a4ed3-259c-4813-b44c-ce7701d62e70', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":13}', NULL, '2023-08-04 16:55:32', '2023-08-04 16:55:32'),
('446ef918-7b6c-4d0d-a152-91931f596fe1', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":21}', NULL, '2023-08-05 07:06:10', '2023-08-05 07:06:10'),
('50ebfd4d-6698-4cfc-b9ee-4e49c77a5104', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":11}', NULL, '2023-08-04 09:38:22', '2023-08-04 09:38:22'),
('530e61f6-865b-4292-862e-f82c83f8c480', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":17}', NULL, '2023-08-05 06:39:14', '2023-08-05 06:39:14'),
('5db521c3-6adc-44bd-9bc2-1f1c605d24d1', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":8}', NULL, '2023-08-04 06:43:00', '2023-08-04 06:43:00'),
('815b3fef-c3b7-4b0b-a548-68a402f4e98b', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":18}', NULL, '2023-08-05 06:39:44', '2023-08-05 06:39:44'),
('8b905697-834d-472d-9d0a-d6c87bb1e026', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":1}', '2023-06-21 11:24:59', '2023-06-21 11:23:47', '2023-06-21 11:24:59'),
('8b923813-961a-4f43-8373-e1b1c1a88d89', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":10}', NULL, '2023-08-04 09:36:20', '2023-08-04 09:36:20'),
('92c0ce19-409c-428f-a5ef-76584f073c74', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product\",\"payment_id\":15}', NULL, '2023-08-05 06:32:20', '2023-08-05 06:32:20'),
('935cd2cd-405d-472a-a684-730ab4f697dd', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":12}', NULL, '2023-08-04 12:49:19', '2023-08-04 12:49:19'),
('997fb57c-7bae-451c-a787-77e9ce0091e2', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":3}', NULL, '2023-07-31 06:40:13', '2023-07-31 06:40:13'),
('9b18fe7f-58c1-4b24-9bee-d5c7a65b411e', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":14}', NULL, '2023-08-04 17:34:05', '2023-08-04 17:34:05'),
('9fadceb3-57e1-4ad3-97e8-ba8380fc2b26', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product\",\"payment_id\":16}', NULL, '2023-08-05 06:33:34', '2023-08-05 06:33:34'),
('ae96130a-3288-4cb6-bc6b-72fd6568d909', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":7}', NULL, '2023-08-04 06:31:01', '2023-08-04 06:31:01'),
('bf70ad4f-a6af-412c-9772-91614e2858dd', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":6}', NULL, '2023-08-04 06:22:09', '2023-08-04 06:22:09'),
('cd080bfc-bcd5-4c4d-a956-07432e89f100', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":9}', NULL, '2023-08-04 09:18:27', '2023-08-04 09:18:27'),
('db5ffd15-abd6-488b-ab8a-8d700f463ec0', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":19}', NULL, '2023-08-05 06:49:07', '2023-08-05 06:49:07'),
('dd396fa4-68b4-4a43-855a-cd1fd384e39d', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":23}', NULL, '2023-08-05 07:08:32', '2023-08-05 07:08:32'),
('df65a6e8-9dee-4500-8be1-25aa6245ab8c', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":5}', NULL, '2023-08-01 07:25:46', '2023-08-01 07:25:46'),
('fb9c6443-0db8-4cb7-bbf1-5a195e642f54', 'App\\Notifications\\PaymentNotification', 'App\\Models\\User', 1, '{\"message\":\"A payment has been made for your product: product update\",\"payment_id\":4}', NULL, '2023-08-01 05:51:08', '2023-08-01 05:51:08');

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
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023-06-21 11:23:47', '2023-06-21 11:23:47'),
(2, 1, 1, '2023-07-31 06:38:52', '2023-07-31 06:38:52'),
(3, 1, 1, '2023-07-31 06:40:13', '2023-07-31 06:40:13'),
(4, 1, 1, '2023-08-01 05:51:08', '2023-08-01 05:51:08'),
(5, 1, 1, '2023-08-01 07:25:46', '2023-08-01 07:25:46'),
(6, 1, 1, '2023-08-04 06:22:09', '2023-08-04 06:22:09'),
(7, 1, 1, '2023-08-04 06:31:01', '2023-08-04 06:31:01'),
(8, 1, 1, '2023-08-04 06:43:00', '2023-08-04 06:43:00'),
(9, 9, 1, '2023-08-04 09:18:27', '2023-08-04 09:18:27'),
(10, 1, 1, '2023-08-04 09:36:20', '2023-08-04 09:36:20'),
(11, 1, 1, '2023-08-04 09:38:22', '2023-08-04 09:38:22'),
(12, 9, 1, '2023-08-04 12:49:19', '2023-08-04 12:49:19'),
(13, 1, 1, '2023-08-04 16:55:31', '2023-08-04 16:55:31'),
(14, 19, 1, '2023-08-04 17:34:05', '2023-08-04 17:34:05'),
(15, 9, 3, '2023-08-05 06:32:20', '2023-08-05 06:32:20'),
(16, 9, 3, '2023-08-05 06:33:34', '2023-08-05 06:33:34'),
(17, 9, 1, '2023-08-05 06:39:14', '2023-08-05 06:39:14'),
(18, 9, 1, '2023-08-05 06:39:44', '2023-08-05 06:39:44'),
(19, 9, 1, '2023-08-05 06:49:07', '2023-08-05 06:49:07'),
(20, 9, 1, '2023-08-05 07:05:00', '2023-08-05 07:05:00'),
(21, 9, 1, '2023-08-05 07:06:10', '2023-08-05 07:06:10'),
(22, 9, 1, '2023-08-05 07:06:40', '2023-08-05 07:06:40'),
(23, 9, 1, '2023-08-05 07:08:32', '2023-08-05 07:08:32');

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
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'MyApp', '7577a4b78158afb7c489dd11355f6992a255719090fd4db427f751fbd0028c7f', '[\"*\"]', NULL, NULL, '2023-06-21 11:18:05', '2023-06-21 11:18:05'),
(2, 'App\\Models\\User', 2, 'MyApp', '41e22f4d4fa108758511af7334ca7ff763411a66e5f25976b69e16a3d0680c3e', '[\"*\"]', NULL, NULL, '2023-06-21 11:18:20', '2023-06-21 11:18:20'),
(3, 'App\\Models\\User', 1, 'MyApp', 'f55fe96687c9ec44e84a86f6417cd34a5242405bfa51ec2ad8600ad1a8547283', '[\"*\"]', '2023-06-21 11:25:04', NULL, '2023-06-21 11:18:33', '2023-06-21 11:25:04'),
(4, 'App\\Models\\User', 1, 'MyApp', '07f4dec0f2d7732aff990e6cb2f12c1427d5c60fe21b39a801f0aa74dc4a96cf', '[\"*\"]', '2023-07-26 08:47:06', NULL, '2023-06-21 11:41:12', '2023-07-26 08:47:06'),
(5, 'App\\Models\\User', 1, 'MyApp', 'd71037de76573e62dcf5813d6401a93fe7423b77b313f151dfb11eff53cba46f', '[\"*\"]', NULL, NULL, '2023-06-21 11:51:33', '2023-06-21 11:51:33'),
(6, 'App\\Models\\User', 1, 'MyApp', 'ff9f6be4a7b4f969f16fea129513c4831a86bd29f3d8d1ee593795260459eadb', '[\"*\"]', NULL, NULL, '2023-07-18 12:51:00', '2023-07-18 12:51:00'),
(7, 'App\\Models\\User', 1, 'MyApp', 'd831b8423eadc3939de80762c16efe1db66105a62d93356f6f5e7cbd67579b34', '[\"*\"]', NULL, NULL, '2023-07-22 08:12:40', '2023-07-22 08:12:40'),
(8, 'App\\Models\\User', 1, 'MyApp', '6381a2527d8e01d7b82f8283b8aec25c9d364e5d4f84a1e6673a1da9cc46f039', '[\"*\"]', NULL, NULL, '2023-07-22 09:41:21', '2023-07-22 09:41:21'),
(9, 'App\\Models\\User', 1, 'MyApp', '9c8f92ed364ee10db7249b891abacac0de54f405d67b2c0efee2cd3a86c5fd18', '[\"*\"]', NULL, NULL, '2023-07-24 08:04:15', '2023-07-24 08:04:15'),
(10, 'App\\Models\\User', 1, 'MyApp', '0c3ec999f3e35da7a165328a028d5871aaf1cafee87950c64d038bacd45b6422', '[\"*\"]', NULL, NULL, '2023-07-24 08:04:32', '2023-07-24 08:04:32'),
(11, 'App\\Models\\User', 1, 'MyApp', 'b097c4985eb456c85d0ad72093570bb1de764656fb07697bf85136a795aa7eec', '[\"*\"]', NULL, NULL, '2023-07-24 08:05:09', '2023-07-24 08:05:09'),
(12, 'App\\Models\\User', 3, 'MyApp', 'dd1e59bcd06b0665aaad97225c40b9c26e25aeb9b8821de60178c2c4b710f5c4', '[\"*\"]', NULL, NULL, '2023-07-24 08:11:04', '2023-07-24 08:11:04'),
(13, 'App\\Models\\User', 4, 'MyApp', '3c07457f1f24022acd88ff1daddadfe16262c6a0d6fa23c78f0530452af8be95', '[\"*\"]', NULL, NULL, '2023-07-24 08:18:44', '2023-07-24 08:18:44'),
(14, 'App\\Models\\User', 5, 'MyApp', 'd0abfe893cf28f8e423f5b3bbb1ebd5514ae75292945698983b0c7deae637af0', '[\"*\"]', NULL, NULL, '2023-07-24 08:36:47', '2023-07-24 08:36:47'),
(15, 'App\\Models\\User', 5, 'MyApp', '696f6096ec4fbce5140c92293cbeabdada1c80c997b91d576e9bec9800357928', '[\"*\"]', NULL, NULL, '2023-07-24 08:37:11', '2023-07-24 08:37:11'),
(16, 'App\\Models\\User', 6, 'MyApp', '0a574a17d5ab5e783416e23d00c0c84fb9f647e4d8d1a112f3363f14d7a9d681', '[\"*\"]', NULL, NULL, '2023-07-24 10:59:00', '2023-07-24 10:59:00'),
(17, 'App\\Models\\User', 7, 'MyApp', '31baf467e9380ea2a71f937cc503ceee346015200c0ff72f7faf1293d0fb372c', '[\"*\"]', NULL, NULL, '2023-07-24 11:56:41', '2023-07-24 11:56:41'),
(18, 'App\\Models\\User', 8, 'MyApp', '02facda59b38914f88d11981ea1d0a521877b57e8d9dc815b04a0d00d8308c18', '[\"*\"]', NULL, NULL, '2023-07-24 12:03:53', '2023-07-24 12:03:53'),
(19, 'App\\Models\\User', 9, 'MyApp', 'd59567c075b0e37938eeb065d12766c321041e1d447d9eba324efbf9a353bded', '[\"*\"]', NULL, NULL, '2023-07-24 12:08:32', '2023-07-24 12:08:32'),
(20, 'App\\Models\\User', 9, 'MyApp', 'a71beb69555c1228bfc0b07b59dca70a8971a7a989b499dfaab34ddd5f08c0ee', '[\"*\"]', NULL, NULL, '2023-07-24 12:08:44', '2023-07-24 12:08:44'),
(21, 'App\\Models\\User', 9, 'MyApp', '63f91457f61afa766801523eafc9a85ab50d0414bf122714dc96044005cdf515', '[\"*\"]', NULL, NULL, '2023-07-24 12:25:59', '2023-07-24 12:25:59'),
(22, 'App\\Models\\User', 1, 'MyApp', '58f4f862d8b33f25e32facc80f8a4c6f6ed87ddd6e77a5cb7a54439b83f72b0d', '[\"*\"]', '2023-07-26 13:11:34', NULL, '2023-07-24 12:54:42', '2023-07-26 13:11:34'),
(23, 'App\\Models\\User', 9, 'MyApp', '83547f58fcbc065a47a4c3f7496e4920a6f8d7e421520170a8843206f249da76', '[\"*\"]', NULL, NULL, '2023-07-25 06:37:13', '2023-07-25 06:37:13'),
(24, 'App\\Models\\User', 1, 'MyApp', 'e42be86b358ddbc11f3b98a8b416cde6c68e7e37c1c0f6d5224966886419c77a', '[\"*\"]', '2023-07-25 07:13:54', NULL, '2023-07-25 06:55:35', '2023-07-25 07:13:54'),
(25, 'App\\Models\\User', 1, 'MyApp', 'da97f5920c035885a0ffea4f4b384cd2a5998d62ebc48d682e3090e7b0ea8214', '[\"*\"]', NULL, NULL, '2023-07-25 07:23:52', '2023-07-25 07:23:52'),
(26, 'App\\Models\\User', 9, 'MyApp', '76a53477e77edff3d15a3c2cdb19e7ddc59652df7dd90d26ca8d9153925c9d5f', '[\"*\"]', '2023-07-25 13:13:53', NULL, '2023-07-25 07:25:37', '2023-07-25 13:13:53'),
(27, 'App\\Models\\User', 1, 'MyApp', '1e1460f4cc621e9703f6f0b9f2e3ca770d2d68cd284e200476cc72ffbcec03c9', '[\"*\"]', '2023-07-25 13:47:08', NULL, '2023-07-25 07:27:19', '2023-07-25 13:47:08'),
(28, 'App\\Models\\User', 1, 'MyApp', '0c070de584b4220502669f9e8034d8a331138cab69a21a67421c402bad99d8d9', '[\"*\"]', '2023-07-25 08:20:12', NULL, '2023-07-25 08:16:37', '2023-07-25 08:20:12'),
(29, 'App\\Models\\User', 9, 'MyApp', '59e09cd0609437b4d6bc0dccd636367e18eb43a6f900e09ff1dd0241a4155dd2', '[\"*\"]', '2023-07-27 05:37:45', NULL, '2023-07-25 08:20:29', '2023-07-27 05:37:45'),
(30, 'App\\Models\\User', 1, 'MyApp', 'fe0114d5cd928683bbd9f52b2e4b051dae894d8a56384f4e2554f7cbd379e4d3', '[\"*\"]', '2023-07-25 08:46:14', NULL, '2023-07-25 08:36:54', '2023-07-25 08:46:14'),
(31, 'App\\Models\\User', 1, 'MyApp', '1192b12d8531021b5039cd98a3c74b2d6804668f47689f4ea88067c1c773c890', '[\"*\"]', '2023-07-25 08:48:05', NULL, '2023-07-25 08:47:21', '2023-07-25 08:48:05'),
(32, 'App\\Models\\User', 1, 'MyApp', 'fa6e6eb27497d93443360e6fc08da6f4dfb21ed18683e36ccd667b1f3f8c4c25', '[\"*\"]', '2023-07-25 09:10:34', NULL, '2023-07-25 09:09:20', '2023-07-25 09:10:34'),
(33, 'App\\Models\\User', 1, 'MyApp', '942e901ecd9c043ad75e20e6d0934fa1bb0aef36ab7e010c0ff09114c0acc7a5', '[\"*\"]', NULL, NULL, '2023-07-25 11:07:26', '2023-07-25 11:07:26'),
(34, 'App\\Models\\User', 1, 'MyApp', '952d8451d8f40963b9711d8ae527d45c1b20a7c7ee8d4f22522acdbe6472cb0c', '[\"*\"]', '2023-07-25 12:31:38', NULL, '2023-07-25 12:31:16', '2023-07-25 12:31:38'),
(35, 'App\\Models\\User', 1, 'MyApp', '83e6a83921bab4c5417127fef1c6f322efd46b5219476cc2034a6f237c674909', '[\"*\"]', '2023-07-25 13:27:12', NULL, '2023-07-25 12:57:16', '2023-07-25 13:27:12'),
(36, 'App\\Models\\User', 1, 'MyApp', '6341c8f7332b2cf35cb32a9ef8fcc4d655448e33588fca9629088782abbacaae', '[\"*\"]', '2023-07-26 05:14:08', NULL, '2023-07-26 05:12:42', '2023-07-26 05:14:08'),
(37, 'App\\Models\\User', 1, 'MyApp', '7d6a298c1c3475d59e0005c546fd0e170cb60a7bfc2e8db833ddaf1b27cbeabb', '[\"*\"]', '2023-07-26 08:38:04', NULL, '2023-07-26 08:34:50', '2023-07-26 08:38:04'),
(38, 'App\\Models\\User', 1, 'MyApp', 'b1ce3a59a28a2cf4e90a2003b94791a21a6372da1e43056b85250ebbf37536b5', '[\"*\"]', '2023-07-26 09:00:33', NULL, '2023-07-26 09:00:11', '2023-07-26 09:00:33'),
(39, 'App\\Models\\User', 9, 'MyApp', 'abc2813fcfcc1dfa85fd2969e63342b22bb6f350d1b3aec9225d679c4b675d48', '[\"*\"]', '2023-07-26 09:10:44', NULL, '2023-07-26 09:08:26', '2023-07-26 09:10:44'),
(40, 'App\\Models\\User', 9, 'MyApp', 'f8ff62ad4c9014ed2486741542d944b20fabd62e89e686ae20f7389913e88a1d', '[\"*\"]', '2023-07-26 09:25:58', NULL, '2023-07-26 09:12:57', '2023-07-26 09:25:58'),
(41, 'App\\Models\\User', 1, 'MyApp', 'a941b26436c2d51c33f86f4e0c7deda4073190c7d7ebc4e4d65f1c93ed02c016', '[\"*\"]', '2023-07-26 09:20:59', NULL, '2023-07-26 09:20:41', '2023-07-26 09:20:59'),
(42, 'App\\Models\\User', 1, 'MyApp', '9cfea184132038344be06cbcfa3459ac89f780ccb731e27768ef89b7d9823068', '[\"*\"]', '2023-07-26 09:27:38', NULL, '2023-07-26 09:21:41', '2023-07-26 09:27:38'),
(43, 'App\\Models\\User', 9, 'MyApp', '434b064fa69d69259521c6b71cc05e7ff4522bd9ddc40a9190f5155b7f7fe252', '[\"*\"]', '2023-07-26 10:21:20', NULL, '2023-07-26 10:21:12', '2023-07-26 10:21:20'),
(44, 'App\\Models\\User', 9, 'MyApp', 'ff3161ebcf9f1187701851d36bd9b3c7c06c36d09e2ae493f87b0a39aa709bc6', '[\"*\"]', '2023-07-26 13:30:18', NULL, '2023-07-26 10:32:22', '2023-07-26 13:30:18'),
(45, 'App\\Models\\User', 1, 'MyApp', '0d97ba6dd6df02d90e80f83dedddc4666e97e6a7fe7e5dba55b7988184a6ada9', '[\"*\"]', '2023-07-26 11:07:48', NULL, '2023-07-26 11:07:21', '2023-07-26 11:07:48'),
(46, 'App\\Models\\User', 1, 'MyApp', '022dd342e6f6d6a01bf63ee023661fc5b2c3e6cd12f72a3805a941b461d838b4', '[\"*\"]', '2023-07-26 11:17:52', NULL, '2023-07-26 11:17:35', '2023-07-26 11:17:52'),
(47, 'App\\Models\\User', 1, 'MyApp', 'fc4d1fe713c35f2709c580b208b35654686cd2a51959927aa723f32cc52d0ced', '[\"*\"]', '2023-07-31 05:46:44', NULL, '2023-07-26 13:10:49', '2023-07-31 05:46:44'),
(48, 'App\\Models\\User', 9, 'MyApp', '35d46ce8ccfab02a52073e4565300df5a3f3950502cb6c9506fd91aef6c25643', '[\"*\"]', '2023-07-26 13:30:28', NULL, '2023-07-26 13:11:02', '2023-07-26 13:30:28'),
(49, 'App\\Models\\User', 1, 'MyApp', 'c2100ab7aeafea4ea42a3af53c456691adcd819476f0d634fa79d04b0521da26', '[\"*\"]', '2023-07-26 13:35:53', NULL, '2023-07-26 13:35:02', '2023-07-26 13:35:53'),
(50, 'App\\Models\\User', 1, 'MyApp', '2b451ef696b7b4a70b3820c4a501a9b64423f3e9ec02dccb074610ab94af1a34', '[\"*\"]', '2023-07-27 05:33:55', NULL, '2023-07-27 05:33:40', '2023-07-27 05:33:55'),
(51, 'App\\Models\\User', 9, 'MyApp', 'c17d316897ff116b5c5d27c772268ad97e4e9b1a22111fff39cbbdc353ee856f', '[\"*\"]', '2023-07-27 07:20:24', NULL, '2023-07-27 05:42:32', '2023-07-27 07:20:24'),
(52, 'App\\Models\\User', 9, 'MyApp', '07adc282feea89b0aac76b94cb5cd184b3f3b07aa453e860c30d1dc8257cc2a9', '[\"*\"]', '2023-07-27 07:36:47', NULL, '2023-07-27 06:03:58', '2023-07-27 07:36:47'),
(53, 'App\\Models\\User', 1, 'MyApp', 'c6a8ac797e8f582c6027389e488cbc91c1f2db99a03aec5b44d6b278bff1aff7', '[\"*\"]', '2023-07-27 06:24:16', NULL, '2023-07-27 06:24:04', '2023-07-27 06:24:16'),
(54, 'App\\Models\\User', 1, 'MyApp', 'aea0ddc0fcc7389d85a6998495870c7fe6e7e13491edd2d72aeac69f80f6fbaa', '[\"*\"]', '2023-07-31 05:49:15', NULL, '2023-07-27 07:37:50', '2023-07-31 05:49:15'),
(55, 'App\\Models\\User', 1, 'MyApp', 'ff8aaac0b199f527b2f1aae39e592390868349d824866818ed483f8fe7236fbc', '[\"*\"]', '2023-07-27 07:40:05', NULL, '2023-07-27 07:39:54', '2023-07-27 07:40:05'),
(56, 'App\\Models\\User', 1, 'MyApp', '96f9610c1af765d4a6f13e7e423a412ad14a2c6df5fb024a84b794bafe26f772', '[\"*\"]', NULL, NULL, '2023-07-27 07:45:18', '2023-07-27 07:45:18'),
(57, 'App\\Models\\User', 10, 'MyApp', '46ab6f327b2d8e75d32b73f18f0a8ee712200ed0a283249146dc9654e11f89ab', '[\"*\"]', NULL, NULL, '2023-07-27 09:56:00', '2023-07-27 09:56:00'),
(58, 'App\\Models\\User', 10, 'MyApp', '97884ba09f379142e4f55a2bf97f3305a646ee6a1646ecb37d7247a1f9b69572', '[\"*\"]', NULL, NULL, '2023-07-27 10:41:08', '2023-07-27 10:41:08'),
(59, 'App\\Models\\User', 10, 'MyApp', '9c983d74740ca9d320cf9f42edd34268f9bf625cb0119919cec8ba55d94af370', '[\"*\"]', NULL, NULL, '2023-07-27 10:46:52', '2023-07-27 10:46:52'),
(60, 'App\\Models\\User', 15, 'MyApp', 'e5f929cd2e0fe31ce746fa9209d44eefa37384ad15f02a58554239d7180f0b71', '[\"*\"]', NULL, NULL, '2023-07-27 10:55:05', '2023-07-27 10:55:05'),
(61, 'App\\Models\\User', 15, 'MyApp', '941efcb84922a105a4dc3b199d60fc43595298bfb0cc07cb1d702d1b052b4d69', '[\"*\"]', NULL, NULL, '2023-07-27 10:55:13', '2023-07-27 10:55:13'),
(62, 'App\\Models\\User', 15, 'MyApp', 'eb96af855d0527ecfcee50a2d9f32398c341c7a28a4f574dac5ccdbf3c5c235e', '[\"*\"]', NULL, NULL, '2023-07-27 10:59:05', '2023-07-27 10:59:05'),
(63, 'App\\Models\\User', 16, 'MyApp', '32c2c05bc51c47ac38bdad2935b29cbe1b0c021e48b44bf20431b6729109f21d', '[\"*\"]', NULL, NULL, '2023-07-27 10:59:59', '2023-07-27 10:59:59'),
(64, 'App\\Models\\User', 17, 'MyApp', 'f3b9da4a0be06a40e9828630ac5c7736e6858c1333d54884d4650c8db7b22e65', '[\"*\"]', NULL, NULL, '2023-07-27 11:00:21', '2023-07-27 11:00:21'),
(65, 'App\\Models\\User', 17, 'MyApp', '4bd63791cd97adde98e86715d033604ca3e2b4297b598b71da266bdc577aa2a9', '[\"*\"]', NULL, NULL, '2023-07-27 11:03:30', '2023-07-27 11:03:30'),
(66, 'App\\Models\\User', 17, 'MyApp', '9b66280d72b010bdc6799adee15effea70b3c3a9791e53acd13ac52a63600e09', '[\"*\"]', NULL, NULL, '2023-07-27 11:04:45', '2023-07-27 11:04:45'),
(67, 'App\\Models\\User', 17, 'MyApp', '26ff180c950d3b615ab58e9468abb0e23e40f0bc238efb3ce53e5d09e4425a4c', '[\"*\"]', NULL, NULL, '2023-07-27 11:11:28', '2023-07-27 11:11:28'),
(68, 'App\\Models\\User', 18, 'MyApp', '8ac2fd7a9a0a54451600874f5e55b3d89540bd23b54a298391c784e085dc4911', '[\"*\"]', NULL, NULL, '2023-07-27 11:11:49', '2023-07-27 11:11:49'),
(69, 'App\\Models\\User', 18, 'MyApp', '773234dc1bc9123016cb6c24f8f75c0311b9e156a17ca5b87d4e6144c2a09f64', '[\"*\"]', NULL, NULL, '2023-07-27 11:11:54', '2023-07-27 11:11:54'),
(70, 'App\\Models\\User', 1, 'MyApp', '76242b9ea13a470fc9208cc534a3003611b0e3d7ed3b47a571cdfd21cba9bbde', '[\"*\"]', '2023-07-27 13:07:05', NULL, '2023-07-27 13:06:52', '2023-07-27 13:07:05'),
(71, 'App\\Models\\User', 1, 'MyApp', 'd325500b1f80675b1fc014564d961d07ccaccf718caabe3a1378d14937a3ddf3', '[\"*\"]', '2023-07-31 05:45:49', NULL, '2023-07-31 05:40:31', '2023-07-31 05:45:49'),
(72, 'App\\Models\\User', 9, 'MyApp', '48f9a132dfb0fc338952e1fa93ba49de023965b100f52d8e8250f283f90328d8', '[\"*\"]', '2023-07-31 06:19:58', NULL, '2023-07-31 06:19:13', '2023-07-31 06:19:58'),
(73, 'App\\Models\\User', 1, 'MyApp', '856a03a829758e001ec5e3cc779b67fef83235f43072bd12cddb33fee0a22344', '[\"*\"]', '2023-08-04 09:34:54', NULL, '2023-07-31 06:24:23', '2023-08-04 09:34:54'),
(74, 'App\\Models\\User', 1, 'MyApp', 'a13f3e94f4927548c27e13a0b48c24afeb53f5dc0439bbf9d194abe12a38616c', '[\"*\"]', '2023-07-31 06:56:22', NULL, '2023-07-31 06:27:58', '2023-07-31 06:56:22'),
(75, 'App\\Models\\User', 1, 'MyApp', '4d464552731e52b60727761ab4a962df9c543c061ef649a6569883930192a9ad', '[\"*\"]', '2023-07-31 06:41:28', NULL, '2023-07-31 06:38:17', '2023-07-31 06:41:28'),
(76, 'App\\Models\\User', 1, 'MyApp', 'd379b81660b4656fd02e6bf84d24f836251ceb967f7afee85c84af9bec2521bf', '[\"*\"]', '2023-08-01 07:25:45', NULL, '2023-08-01 05:47:11', '2023-08-01 07:25:45'),
(77, 'App\\Models\\User', 9, 'MyApp', 'b23a7bc02506f9ed9d9233b63cdfa55ebe1b954843f38ffd020207f1c8b5e456', '[\"*\"]', '2023-08-01 09:01:16', NULL, '2023-08-01 09:00:48', '2023-08-01 09:01:16'),
(78, 'App\\Models\\User', 1, 'MyApp', 'ca62428b67f886be7e60d0924d57d1ae3e84946b1db282553248faf6e93dd013', '[\"*\"]', '2023-08-01 09:18:16', NULL, '2023-08-01 09:17:54', '2023-08-01 09:18:16'),
(79, 'App\\Models\\User', 1, 'MyApp', 'c84e25a72979cac0ace3a4713594c150c35071d6807c0703138d12d5ae4e7fd0', '[\"*\"]', '2023-08-04 06:22:07', NULL, '2023-08-04 06:19:40', '2023-08-04 06:22:07'),
(80, 'App\\Models\\User', 9, 'MyApp', '277d7a283469c0df22119e7dcdaaa6725f044eb979640ff454ba82737b41a53b', '[\"*\"]', '2023-08-05 05:57:16', NULL, '2023-08-04 06:27:48', '2023-08-05 05:57:16'),
(81, 'App\\Models\\User', 1, 'MyApp', '0783663234d61e8c99a308e13056cd29a4dbed4528b43af83006b7d45fe9eb9d', '[\"*\"]', '2023-08-04 09:39:08', NULL, '2023-08-04 06:30:30', '2023-08-04 09:39:08'),
(82, 'App\\Models\\User', 9, 'MyApp', '93c2159ea5a00bc6786a1b34b319588b641125a368f6902114df62cd105484d1', '[\"*\"]', '2023-08-04 11:26:11', NULL, '2023-08-04 08:18:30', '2023-08-04 11:26:11'),
(83, 'App\\Models\\User', 1, 'MyApp', 'c14a394073abd69e38c4b357fe01071b3ba12ce3ad608114d165e53a8da59f18', '[\"*\"]', '2023-08-04 08:26:17', NULL, '2023-08-04 08:24:59', '2023-08-04 08:26:17'),
(84, 'App\\Models\\User', 9, 'MyApp', 'a57091e53625f162560591b737268f7b4331405b3fdeb2479cb1e1052b97f6ad', '[\"*\"]', '2023-08-04 09:18:25', NULL, '2023-08-04 09:18:14', '2023-08-04 09:18:25'),
(85, 'App\\Models\\User', 1, 'MyApp', '30e1cdbd811c42d75d76d97e9bbe1724b8315d4576fe974ae6ee86a46097a214', '[\"*\"]', '2023-08-04 09:50:14', NULL, '2023-08-04 09:49:59', '2023-08-04 09:50:14'),
(86, 'App\\Models\\User', 9, 'MyApp', 'd8ae61a6718ca8847ca17713206be1975ad901ee4bf4a534e32f3c6d3fe9fb4a', '[\"*\"]', '2023-08-04 12:54:58', NULL, '2023-08-04 12:07:21', '2023-08-04 12:54:58'),
(87, 'App\\Models\\User', 1, 'MyApp', '6455d1bd9d7907006d9644d2f70069a41284cd030cd3d51120fda5bc4ac2b12e', '[\"*\"]', '2023-08-04 12:15:18', NULL, '2023-08-04 12:14:31', '2023-08-04 12:15:18'),
(88, 'App\\Models\\User', 1, 'MyApp', '06287f08ab09f08c14bfc62472356b0a0755d4ed9378c761d5c51d3710c21199', '[\"*\"]', '2023-08-04 17:12:39', NULL, '2023-08-04 16:48:04', '2023-08-04 17:12:39'),
(89, 'App\\Models\\User', 1, 'MyApp', 'f179f65ebdfc1717afd31c5640ee300d365674f5bc653680629ecd953c979b45', '[\"*\"]', '2023-08-04 17:37:28', NULL, '2023-08-04 17:11:36', '2023-08-04 17:37:28'),
(90, 'App\\Models\\User', 1, 'MyApp', '66c242a80438df3ad8631eedbddc80292ee4aa6c5f87724a66cf7b30f67c29b7', '[\"*\"]', '2023-08-04 17:32:19', NULL, '2023-08-04 17:27:16', '2023-08-04 17:32:19'),
(91, 'App\\Models\\User', 19, 'MyApp', '3a23915cf35682544a2ec684d8a1122f43d493664628db9547b43c4d420193fe', '[\"*\"]', '2023-08-04 17:36:37', NULL, '2023-08-04 17:33:29', '2023-08-04 17:36:37'),
(92, 'App\\Models\\User', 9, 'MyApp', '7a11f3e94279171b637362baa15560cf6508e2ec46a15870206a3cbe9dec0c88', '[\"*\"]', '2023-08-05 12:14:23', NULL, '2023-08-05 05:55:14', '2023-08-05 12:14:23'),
(93, 'App\\Models\\User', 9, 'MyApp', '0dc43c862109a89d62df0dfa86f91a648ef5382e49ba39b0e67ebb69f6b62243', '[\"*\"]', NULL, NULL, '2023-08-05 06:01:27', '2023-08-05 06:01:27'),
(94, 'App\\Models\\User', 1, 'MyApp', '9223a8ae4e9d0ce49215dba434d95a92d042beaf1242411fac1bc3166f34f56a', '[\"*\"]', '2023-08-05 06:07:52', NULL, '2023-08-05 06:03:49', '2023-08-05 06:07:52'),
(95, 'App\\Models\\User', 1, 'MyApp', 'f32e4bb51d36beb9f96fac61cd1c7984df5bb48af045b3d260912e3a405a0d74', '[\"*\"]', '2023-08-05 06:09:07', NULL, '2023-08-05 06:06:35', '2023-08-05 06:09:07');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` text DEFAULT NULL,
  `current_price` int(11) NOT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `current_price`, `weight`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'product update', 'product_images/xjQ6ZjadOWJCjDg8Roy3knpxvPQJMn8caT7NApX1.jpg', 20, '30', 1, '2023-06-21 11:19:15', '2023-06-21 11:19:42'),
(3, 'product', 'product_images/5RerLZzgd7wqnXyoNLMXflCiHmzX7Ob2zbfK7YUe.jpg', 10, '4', 1, '2023-07-24 12:55:20', '2023-07-24 12:55:20'),
(4, 'product', 'product_images/JqfMWHryiOhOBOZKB24iwk8GGShpPAyMKdI2xu3p.png', 10, '4', 1, '2023-07-25 06:55:48', '2023-07-25 06:55:48'),
(5, 'product', 'product_images/K363Ka3Dl6ltHfVvKs0uGlwjOke4BhoouxEf4mXQ.png', 10, '4', 1, '2023-07-25 07:13:38', '2023-07-25 07:13:38'),
(6, 'product', 'product_images/dkdtqtaY9TD8Xz6vVqUSB2Uy2X1VyLqVBa8xso09.png', 10, '4', 1, '2023-07-25 07:13:54', '2023-07-25 07:13:54'),
(7, 'keyboard', 'product_images/U7N3AwEYpMAuwqNgHFgoAO50ddPrd7VU2gYA13AU.jpg', 12, '2kg', 9, '2023-07-25 07:26:08', '2023-07-25 07:26:08'),
(8, 'product last', 'product_images/LLpjBg6XTmwYHelx7GzKbaghs0l4YciPRcBTZpDs.jpg', 10, '4', 1, '2023-07-25 08:37:31', '2023-07-25 08:37:31'),
(9, 'product last', 'product_images/8ZUy9zLMKJMK7MHnjd3DSkFvQc5ihbd1REiQYxWy.jpg', 10, '4', 1, '2023-07-25 08:46:11', '2023-07-25 08:46:11'),
(10, 'product 12', 'product_images/k1QyGghttYG8g2EGIOqxLoUcPAEyIpOJ0WMDrZb3.jpg', 10, '4', 1, '2023-07-25 08:47:52', '2023-07-25 08:47:52');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `valid_period` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending',
  `amount` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `user_id`, `product_id`, `valid_period`, `status`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023-10-10', 'approved', NULL, '2023-06-21 11:21:33', '2023-06-21 11:21:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` text DEFAULT NULL,
  `role` enum('admin','merchant','user') NOT NULL DEFAULT 'user',
  `bio` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `latitide` text DEFAULT NULL,
  `longitude` text DEFAULT NULL,
  `type_of_activity` text DEFAULT NULL,
  `contact` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT 0,
  `avatar` varchar(255) NOT NULL DEFAULT 'avatar.png',
  `dark_mode` tinyint(1) NOT NULL DEFAULT 0,
  `messenger_color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `image`, `role`, `bio`, `address`, `city`, `latitide`, `longitude`, `type_of_activity`, `contact`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `active_status`, `avatar`, `dark_mode`, `messenger_color`) VALUES
(1, 'Test', 'testuser@gmail.com', '1690378197_4.jfif', 'user', 'This is updated bio', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$WjgScZIuB4JuuE9ZZAB/u..adM4UU4kOhdsrqknx7NWLvg4OsDwUW', NULL, '2023-06-21 11:18:05', '2023-08-04 08:35:55', 0, 'avatar.png', 0, NULL),
(2, 'test shop', 'testshop@gmail.com', NULL, 'merchant', NULL, 'address', 'city', NULL, NULL, 'type of activity', '12345678', NULL, '$2y$10$p6/GriY27nYVSDSk/ikj5uPo1Kyx4EPl5hOd/YcDeSjbdY.MDDSmq', NULL, '2023-06-21 11:18:20', '2023-06-21 11:18:20', 0, 'avatar.png', 0, NULL),
(3, 't', 'testing@gmail.com', NULL, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$HosAoS0jQej8oUwD2v5W0.Hoyo5V4zWyGIeuwNsyr6tbuc3Umierm', NULL, '2023-07-24 08:11:04', '2023-07-24 08:11:04', 0, 'avatar.png', 0, NULL),
(4, 't', 'testing1@gmail.com', NULL, 'user', 'fgfhgj', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$ftPthdafG5oktm9zAVPs7upGlCk1fKpNYEUivQaQHJkG9xm3zGLzy', NULL, '2023-07-24 08:18:44', '2023-07-24 08:18:44', 0, 'avatar.png', 0, NULL),
(5, 'Nayab', 'n@gmail.com', NULL, 'user', 'hehe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$V3lYa3inaShwYtQELbQGJeID9Po5Pu487kp4KSn3yS89i.e3dZyle', NULL, '2023-07-24 08:36:47', '2023-07-24 08:36:47', 0, 'avatar.png', 0, NULL),
(6, 'n', 'n@gail.com', NULL, 'user', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$KUfh41da0wYKMrjt18Sm1OgkKxnwUYc09ud2MkNRbRHlZdIckU/lm', NULL, '2023-07-24 10:59:00', '2023-07-24 10:59:00', 0, 'avatar.png', 0, NULL),
(7, 'Rj Raaj', 'raj@gmail.com', NULL, 'user', '123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$3nj0LEminyLrXKJb0ywHG.MQBPmr7nAezG9kSKK4gHRbfaIP0fzai', NULL, '2023-07-24 11:56:41', '2023-07-24 11:56:41', 0, 'avatar.png', 0, NULL),
(8, 'Nayab Husayn', 'nayab@gmail.com', NULL, 'user', 'abc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$RW25eVUnha4TDJ0HLVT61uMqSU5H37LtJ0v/hgd518igA98Xv6VBy', NULL, '2023-07-24 12:03:53', '2023-07-24 12:03:53', 0, 'avatar.png', 0, NULL),
(9, 'Nayab x Space', 'm@gmail.com', '1690378553_1242x2688bb (2).png', 'user', 'abc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$9/ejG7G5.Sqkm994TN.8heoY/a8B8vFAbUb0KedtnNRoZMvE9Je4C', NULL, '2023-07-24 12:08:32', '2023-07-26 13:35:53', 0, 'avatar.png', 0, NULL),
(18, 'laraveldeveloperfabtechsol', 'laraveldeveloperfabtechsol@gmail.com', NULL, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$.aUF8isv3QZd1Q9QK49p2.s7ADqce.JBciUKdi4z.Kw0ffKH2CRE6', NULL, '2023-07-27 11:11:49', '2023-07-27 11:11:49', 0, 'avatar.png', 0, NULL),
(19, 'test user', 'testuser2@gmail.com', NULL, 'user', 'bio', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$XkPBt.AiFB6BNHvK8yGRzO5BqFT.zykcMknaByyjCC8FHVilrvl5C', NULL, '2023-08-04 17:33:29', '2023-08-04 17:33:29', 0, 'avatar.png', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vote_limits`
--

CREATE TABLE `vote_limits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `limit` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vote_limits`
--

INSERT INTO `vote_limits` (`id`, `limit`, `created_at`, `updated_at`) VALUES
(1, 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vote_products`
--

CREATE TABLE `vote_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vote_products`
--

INSERT INTO `vote_products` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023-06-21 11:20:55', '2023-06-21 11:20:55'),
(3, 1, 3, '2023-07-25 09:10:30', '2023-07-25 09:10:30'),
(4, 9, 4, '2023-07-26 12:04:01', '2023-07-26 12:04:01'),
(5, 9, 1, '2023-07-26 12:04:34', '2023-07-26 12:04:34'),
(6, 9, 3, '2023-07-26 12:13:26', '2023-07-26 12:13:26'),
(7, 9, 5, '2023-07-26 12:13:52', '2023-07-26 12:13:52'),
(8, 9, 7, '2023-07-26 12:20:14', '2023-07-26 12:20:14'),
(9, 9, 8, '2023-07-26 12:21:47', '2023-07-26 12:21:47'),
(10, 9, 6, '2023-07-26 12:23:16', '2023-07-26 12:23:16'),
(11, 9, 9, '2023-08-05 05:57:16', '2023-08-05 05:57:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ch_favorites`
--
ALTER TABLE `ch_favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_messages`
--
ALTER TABLE `ch_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `merchants`
--
ALTER TABLE `merchants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `merchants_email_unique` (`email`),
  ADD KEY `merchants_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_product_id_foreign` (`product_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_user_id_foreign` (`user_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promotions_user_id_foreign` (`user_id`),
  ADD KEY `promotions_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vote_limits`
--
ALTER TABLE `vote_limits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vote_products`
--
ALTER TABLE `vote_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vote_products_user_id_foreign` (`user_id`),
  ADD KEY `vote_products_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `merchants`
--
ALTER TABLE `merchants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `vote_limits`
--
ALTER TABLE `vote_limits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vote_products`
--
ALTER TABLE `vote_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `merchants`
--
ALTER TABLE `merchants`
  ADD CONSTRAINT `merchants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vote_products`
--
ALTER TABLE `vote_products`
  ADD CONSTRAINT `vote_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vote_products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
