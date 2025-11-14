-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-11-2025 a las 11:17:23
-- Versión del servidor: 10.3.39-MariaDB-log
-- Versión de PHP: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `wwwmrinsight_qr_project`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
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
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_09_174042_create_roles_table', 1),
(5, '2025_09_09_174049_create_modules_table', 1),
(6, '2025_09_09_174055_create_permissions_table', 1),
(7, '2025_09_09_174100_create_user_roles_table', 1),
(8, '2025_09_09_174109_create_role_permissions_table', 1),
(9, '2025_09_09_174118_add_is_active_to_users_table', 1),
(10, '2025_10_04_025509_create_qr_codes_table', 2),
(11, '2025_11_01_044337_create_user_permissions_table', 3),
(12, '2025_11_08_014535_create_qr_scans_table', 4),
(13, '2025_11_07_000001_add_soft_deletes_to_qr_codes_table', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modules`
--

INSERT INTO `modules` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'users', 'Gestión de usuarios', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(2, 'roles', 'Gestión de roles y permisos', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(3, 'permissions', 'Gestión de permisos', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(4, 'qr', 'Gestión de códigos QR', '2025-10-31 10:48:38', '2025-10-31 10:48:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(191) NOT NULL,
  `permission_key` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `module_id`, `action`, `permission_key`, `created_at`, `updated_at`) VALUES
(1, 1, 'view', 'users.view', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(2, 1, 'create', 'users.create', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(3, 1, 'edit', 'users.edit', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(4, 1, 'delete', 'users.delete', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(5, 2, 'view', 'roles.view', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(6, 2, 'create', 'roles.create', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(7, 2, 'edit', 'roles.edit', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(8, 2, 'delete', 'roles.delete', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(9, 3, 'view', 'permissions.view', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(10, 3, 'create', 'permissions.create', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(11, 3, 'edit', 'permissions.edit', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(12, 3, 'delete', 'permissions.delete', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(13, 4, 'view', 'qr.view', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(14, 4, 'create', 'qr.create', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(15, 4, 'edit', 'qr.edit', '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(16, 4, 'delete', 'qr.delete', '2025-10-31 10:48:38', '2025-10-31 10:48:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(191) NOT NULL,
  `uri` varchar(500) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `qr_codes`
--

INSERT INTO `qr_codes` (`id`, `uuid`, `name`, `uri`, `user_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'e8e76fd7-6a26-4a48-b2c2-98528444670f', '🎮 El Juego más Adictivo del Mundo', 'https://www.windows93.net/', 1, 0, '2025-10-04 09:39:17', '2025-11-08 10:30:07', NULL),
(3, 'f6eeb373-5abe-4308-bf0c-e87f687298ac', '🦆 Pato Infinito', 'https://ducksarethebest.com/', 1, 0, '2025-10-04 09:39:17', '2025-10-04 09:39:17', NULL),
(4, '38b0c04a-5793-4283-a549-74a39ba9cf32', '🎵 Música Chill para Trabajar', 'https://lofi.cafe/', 1, 0, '2025-10-04 09:39:17', '2025-10-04 09:39:17', NULL),
(5, '86619322-ca87-4156-afcb-954cff57b3e0', '🌌 Viaje al Espacio Profundo', 'https://stars.chromeexperiments.com/', 1, 0, '2025-10-04 09:39:17', '2025-11-10 05:22:53', '2025-11-10 05:22:53'),
(10, '8a10e0f8-cdf5-4a37-b966-8216c5ebb8c4', 'Solariega Cenit (TripAdvisor)', 'https://www.tripadvisor.es/UserReview-g150797-d28630490-Solariega_Cenit-Cuernavaca_Central_Mexico_and_Gulf_Coast.html?m=66827', 3, 1, '2025-11-08 08:29:00', '2025-11-08 08:29:00', NULL),
(11, 'af029fc5-620c-4e20-a928-3e11c280d782', 'Solariega Cenit (Google)', 'https://search.google.com/local/writereview?placeid=ChIJSzu_IDrfzYURX3xMeFT_K5s', 2, 1, '2025-11-10 05:40:23', '2025-11-10 05:40:23', NULL),
(13, '8a1fd04d-b54c-41b4-9b28-84d4a5f5c677', '🎮 El Juego más Adictivo del Mundo', 'https://www.windows93.net/', 1, 0, '2025-10-04 09:39:17', '2025-11-08 10:30:07', NULL),
(15, '3b7f9e48-2a3b-4d72-b6a4-0d5e3c9e2789', '🦆 Pato Infinito', 'https://ducksarethebest.com/', 1, 0, '2025-10-04 09:39:17', '2025-10-04 09:39:17', NULL),
(16, 'c4e8a2df-1f5b-4a12-9f3f-9c39b7d68d42', '🎵 Música Chill para Trabajar', 'https://lofi.cafe/', 1, 0, '2025-10-04 09:39:17', '2025-10-04 09:39:17', NULL),
(17, '91c2ef4a-0a8d-47da-8e9d-62141b9ac835', '🌌 Viaje al Espacio Profundo', 'https://stars.chromeexperiments.com/', 1, 0, '2025-10-04 09:39:17', '2025-11-10 05:22:53', '2025-11-10 05:22:53'),
(18, 'f2b6caa3-b1a9-4b9c-8e5a-6d0218cb6b56', 'Solariega Cenit (TripAdvisor)', 'https://www.tripadvisor.es/UserReview-g150797-d28630490-Solariega_Cenit-Cuernavaca_Central_Mexico_and_Gulf_Coast.html?m=66827', 3, 1, '2025-11-08 08:29:00', '2025-11-08 08:29:00', NULL),
(19, '8a1fd04d-b54c-41b4-9b28-84d4a5f5c672', 'Solariega Cenit (Google)', 'https://search.google.com/local/writereview?placeid=ChIJSzu_IDrfzYURX3xMeFT_K5s', 2, 1, '2025-11-10 05:40:23', '2025-11-10 05:40:23', NULL),
(20, 'eb9ebec7-983a-4bc3-9f84-ac08e0266304', 'Solariega 3', 'https://search.google.com/local/writereview?placeid=ChIJSzu_IDrfzYURX3xMeFT_K5s', 3, 0, '2025-11-12 06:35:00', '2025-11-12 06:41:26', '2025-11-12 06:41:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr_scans`
--

CREATE TABLE `qr_scans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qr_code_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `referer` varchar(191) DEFAULT NULL,
  `scanned_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `qr_scans`
--

INSERT INTO `qr_scans` (`id`, `qr_code_id`, `ip_address`, `user_agent`, `referer`, `scanned_at`, `created_at`, `updated_at`) VALUES
(1, 10, '2806:2f0:a4c0:e24f:10ae:b563:9344:8fa8', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', NULL, '2025-11-08 08:29:22', '2025-11-08 08:29:22', '2025-11-08 08:29:22'),
(2, 2, '2806:2f0:a4c0:e24f:10ae:b563:9344:8fa8', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', NULL, '2025-11-08 08:30:07', '2025-11-08 08:30:07', '2025-11-08 08:30:07'),
(3, 11, '2806:2f0:a4c0:e24f:164f:16be:426d:6dee', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', NULL, '2025-11-10 05:44:18', '2025-11-10 05:44:18', '2025-11-10 05:44:18'),
(4, 20, '104.28.50.21', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', NULL, '2025-11-12 06:35:25', '2025-11-12 06:35:25', '2025-11-12 06:35:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrador del sistema', 1, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(2, 'user', 'Usuario básico', 1, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(3, 'rol de pruebas', 'sin permisos', 0, '2025-11-01 10:51:26', '2025-11-11 10:19:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 2, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 3, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 4, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 5, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 6, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 7, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 8, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 9, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 10, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 11, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 12, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 13, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 14, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 15, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(1, 16, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(2, 1, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(2, 2, '2025-10-31 11:56:42', '2025-10-31 11:56:42'),
(2, 3, '2025-10-31 11:56:42', '2025-10-31 11:56:42'),
(2, 4, '2025-10-31 11:56:42', '2025-10-31 11:56:42'),
(2, 5, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(2, 6, '2025-10-31 12:09:46', '2025-10-31 12:09:46'),
(2, 7, '2025-10-31 12:09:46', '2025-10-31 12:09:46'),
(2, 8, '2025-10-31 12:09:46', '2025-10-31 12:09:46'),
(2, 9, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(2, 10, '2025-10-31 12:09:46', '2025-10-31 12:09:46'),
(2, 11, '2025-10-31 12:09:46', '2025-10-31 12:09:46'),
(2, 12, '2025-10-31 12:09:46', '2025-10-31 12:09:46'),
(2, 13, '2025-10-31 10:48:38', '2025-10-31 10:48:38'),
(2, 14, '2025-10-31 12:09:31', '2025-10-31 12:09:31'),
(2, 15, '2025-10-31 12:09:31', '2025-10-31 12:09:31'),
(2, 16, '2025-10-31 12:09:31', '2025-10-31 12:09:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Usuario 3', 'test@example.com', '2025-09-29 04:06:46', '$2y$12$XMfqvyvVS5a9v/krjb.xo.j.fXs27TRcsbf3.dG3a0S1lPdhMogGS', 0, 'qDZXPQeLTXyCti5jInBOpEC7OaG8lumThbwiTSIlReG44R9vBUnq8vT5VofD', '2025-09-29 04:06:46', '2025-11-11 10:59:13'),
(2, 'Cristian Bello', 'cristian.bello@ocracode.com', NULL, '$2y$12$wZs.l6lPwCd6gBTPwQ9R..t4iuHqxG6UkKaH4dn8VaPYMx7rG9k7y', 1, 'kVBlyFnkUTU58loJBFXkZiwbpRAXnB1pB5HZnrztfeZClT8TBSRvnHyIOIBl', '2025-09-29 04:06:47', '2025-09-29 04:06:47'),
(3, 'Admin User', 'admin@test.com', NULL, '$2y$12$5viSc1iQGGiwzPxe8QPK9OFH2FcAifDQfERrZkkPZURt67njxXO5O', 1, 'gQDLay3AfXi3zsIY7g87kSt03HUqJnZ7Ky9JR8EwVRNOcCTxS69g2BzZ1Dmp', '2025-09-29 04:06:48', '2025-10-31 10:48:39'),
(5, 'Usurio 4', 'eltevas@iq.com', NULL, '$2y$12$lN8lsDOFy83YVjILA89ZPeOgWLLN8S6bEl6EHYG.z1iOikLrezkxK', 1, NULL, '2025-11-11 09:27:06', '2025-11-11 09:27:06'),
(6, 'asd', 'asd@user.com', NULL, '$2y$12$XOyglhf1w2YLeVOyC0ZEYuwv2xWQ5K.UcAP9UFgs/svZkF8eUUMJG', 1, NULL, '2025-11-11 09:27:29', '2025-11-11 09:27:29'),
(7, 'usuario master', 'master@hard.com', NULL, '$2y$12$b0aBWfC.XW0ic9tQikJSheAhy2H8F4knb37//JAi6QnkxdFIwnOg.', 1, NULL, '2025-11-11 09:28:39', '2025-11-11 09:28:39'),
(8, 'user testing', 'test@test.com', NULL, '$2y$12$sevZZs91A2/4Fsek/nQTv.BMA6IeURsycIZ34Zp1dZr9mAlUre.5q', 0, NULL, '2025-11-11 09:29:20', '2025-11-11 09:29:20'),
(9, 'john', 'teste@test.com', NULL, '$2y$12$5GV0PSPtYdDJpYT.qWUjZ.IOacK7iv73woS99u07O5SX/hvmEkp0.', 1, NULL, '2025-11-11 09:29:55', '2025-11-11 09:29:55'),
(10, 'luffy', 'correo@correo.com', NULL, '$2y$12$1BXX.FfLVW9pIyFMIi/09ODOtc73Wm8ODYNkxvOtEBSm0OgBv8jVK', 1, NULL, '2025-11-11 09:30:45', '2025-11-11 09:30:45'),
(11, 'natura', 'user@natura.com', NULL, '$2y$12$EPgWMqLHM6GC/QayBuEofuFzDjFp8opspPdOQXjiIKZYaept0pmqC', 1, NULL, '2025-11-11 09:31:16', '2025-11-11 09:31:16'),
(12, 'dinouser', 'dino@test.com', NULL, '$2y$12$QHCfR3t7LoLIVAIJUbD7dO/aVhOyghkwhA9zEQ6r9PNll1YVlFrH.', 1, NULL, '2025-11-11 10:08:56', '2025-11-11 10:08:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(9, 1, 13, '2025-11-01 12:18:51', '2025-11-01 12:18:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-11-11 10:14:06', '2025-11-11 10:14:06'),
(1, 3, '2025-11-01 09:21:24', '2025-11-01 09:21:24'),
(2, 1, '2025-11-08 09:29:47', '2025-11-08 09:29:47'),
(2, 2, '2025-11-08 10:30:50', '2025-11-08 10:30:50'),
(2, 3, '2025-11-01 11:40:02', '2025-11-01 11:40:02'),
(3, 1, '2025-10-31 10:48:39', '2025-10-31 10:48:39'),
(3, 3, '2025-11-11 09:02:01', '2025-11-11 09:02:01'),
(5, 2, '2025-11-11 10:14:20', '2025-11-11 10:14:20'),
(5, 3, '2025-11-11 09:27:06', '2025-11-11 09:27:06'),
(6, 3, '2025-11-11 09:27:29', '2025-11-11 09:27:29'),
(7, 2, '2025-11-11 09:28:39', '2025-11-11 09:28:39'),
(8, 2, '2025-11-11 09:29:20', '2025-11-11 09:29:20'),
(9, 2, '2025-11-11 09:29:55', '2025-11-11 09:29:55'),
(10, 2, '2025-11-11 09:30:45', '2025-11-11 09:30:45'),
(11, 3, '2025-11-11 09:31:16', '2025-11-11 09:31:16'),
(12, 2, '2025-11-11 10:08:56', '2025-11-11 10:08:56');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modules_name_unique` (`name`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_permission_key_unique` (`permission_key`),
  ADD KEY `permissions_module_id_foreign` (`module_id`);

--
-- Indices de la tabla `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_codes_uuid_unique` (`uuid`),
  ADD KEY `qr_codes_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `qr_scans`
--
ALTER TABLE `qr_scans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qr_scans_scanned_at_index` (`scanned_at`),
  ADD KEY `qr_scans_qr_code_id_scanned_at_index` (`qr_code_id`,`scanned_at`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indices de la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_permissions_user_id_permission_id_unique` (`user_id`,`permission_id`),
  ADD KEY `user_permissions_permission_id_foreign` (`permission_id`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `qr_scans`
--
ALTER TABLE `qr_scans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `qr_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `qr_scans`
--
ALTER TABLE `qr_scans`
  ADD CONSTRAINT `qr_scans_qr_code_id_foreign` FOREIGN KEY (`qr_code_id`) REFERENCES `qr_codes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
