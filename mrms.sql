-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 21, 2025 at 11:38 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_logged_in` timestamp NULL DEFAULT NULL,
  `status` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `last_logged_in`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(60, 'Super Admin', 'super@admin.com', '2025-02-17 17:35:00', '$2y$10$aNbVU19gMSuBZhyUmUP.XOXhrpJzzoANaAySwfGB.mOyHVtGb.tlm', '7XlCyJ87dB6nVg6bm2YtQUuPYsgofxWGNaDb5rljA5U0wAcXgj2KcYqJMm46', '2025-02-21 04:28:05', 1, '2025-02-17 16:34:34', '2025-02-21 04:28:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_email_verification`
--

CREATE TABLE `admin_email_verification` (
  `admin_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_email_verification`
--

INSERT INTO `admin_email_verification` (`admin_id`, `token`, `email`, `created_at`, `updated_at`) VALUES
(62, 'mDneedmHhf2CODSbKGfSGRnbmvfEU4GNBLgVM003vkeh2KUs7wh6Cu2QvwYs', NULL, '2025-02-17 13:59:14', '2025-02-17 13:59:14'),
(61, 'xCNesNMVZbfgOXMXh7ljCTHOcjamg4oRgEP3txQfLthUNHcj044pBxrSucz6', NULL, '2025-02-17 13:56:54', '2025-02-17 13:56:54');

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_password_resets`
--

INSERT INTO `admin_password_resets` (`email`, `token`, `created_at`) VALUES
('super@admin.com', '$2y$10$vW.O8di6Fmo7aZYmUC7fUOymABXuxW4YaWAPxjdYlvHxAAs062sPy', '2025-02-19 00:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `admin_profile`
--

CREATE TABLE `admin_profile` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `phone_number` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_profile`
--

INSERT INTO `admin_profile` (`id`, `admin_id`, `profile_image`, `phone_number`, `created_at`, `updated_at`) VALUES
(12, 60, 'cHJvZmlsZV9pbWFnZS0xNzM5OTg0NDY5.png', '0300', '2025-02-17 11:51:10', '2025-02-19 12:01:36');

-- --------------------------------------------------------

--
-- Table structure for table `contractors`
--

CREATE TABLE `contractors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `county` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_logged_in` timestamp NULL DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contractors`
--

INSERT INTO `contractors` (`id`, `name`, `email`, `title`, `country`, `line1`, `line2`, `line3`, `city`, `county`, `postcode`, `note`, `company_name`, `work_phone`, `fax`, `contact_type`, `email_verified_at`, `password`, `remember_token`, `last_logged_in`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(62, 'Test Contractor', 'contractor@gmail.com', 'Mr', 'United Kingdom', 'Test', NULL, NULL, 'London', 'Yorkshire', 'BD02', NULL, NULL, NULL, NULL, 'email', '2025-02-19 10:16:11', '$2y$10$cm825iR/706y7wVH76gJcOjakPQvsN75GyBrUI5/Mq3757iIYPP4m', NULL, '2025-02-19 10:16:20', 'Active', '2025-02-19 10:14:41', '2025-02-19 10:16:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contractors_password_resets`
--

CREATE TABLE `contractors_password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contractors_password_resets`
--

INSERT INTO `contractors_password_resets` (`email`, `token`, `created_at`) VALUES
('contractor@gmail.com', '$2y$10$C/f1wZaIsDnkW/PDw2GMYuRUqA1e9i1w2pu4/UTi0KNSmMAc4NmDi', '2025-02-19 00:29:33');

-- --------------------------------------------------------

--
-- Table structure for table `contractor_email_verification`
--

CREATE TABLE `contractor_email_verification` (
  `contractor_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contractor_email_verification`
--

INSERT INTO `contractor_email_verification` (`contractor_id`, `token`, `email`, `created_at`, `updated_at`) VALUES
(61, 'QOR7Pwoo6WtUqblAQ3y91XDwBd89UvRlsa3eeDIMJgzblr1KRu5OZqkoNCog', NULL, '2025-02-19 10:13:28', '2025-02-19 10:13:28'),
(63, 't3XivdQHuSx44ojSjBvitqKcAZXhCx1D2VzyPMteIhPla0jS5N01fUjQNY2j', NULL, '2025-02-21 05:00:21', '2025-02-21 05:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `contractor_profile`
--

CREATE TABLE `contractor_profile` (
  `id` int NOT NULL,
  `contractor_id` int NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `phone_number` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contractor_profile`
--

INSERT INTO `contractor_profile` (`id`, `contractor_id`, `profile_image`, `phone_number`, `created_at`, `updated_at`) VALUES
(16, 60, NULL, '0300', '2025-02-18 09:42:17', '2025-02-19 09:49:23'),
(17, 61, NULL, '0300', '2025-02-19 10:13:28', '2025-02-19 10:13:28'),
(18, 62, NULL, '0300', '2025-02-19 10:14:41', '2025-02-19 10:14:41'),
(19, 63, NULL, '03000000', '2025-02-21 05:00:20', '2025-02-21 05:29:17');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int NOT NULL,
  `type` varchar(125) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL,
  `is_html` enum('yes','no') NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `type`, `subject`, `status`, `is_html`, `content`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Reset Password', 'Reset Password Notifications', '1', 'yes', 'PGgyPjxzdHJvbmc+SGVsbG88L3N0cm9uZz48L2gyPg0KDQo8cD5Zb3UgYXJlIHJlY2VpdmluZyB0aGlzIGVtYWlsIGJlY2F1c2Ugd2UgcmVjZWl2ZWQgYSBwYXNzd29yZCByZXNldCByZXF1ZXN0IGZvciB5b3VyIGFjY291bnQuPC9wPg0KDQo8cD48YSBocmVmPSJSRVNFVF9UT0tFTiI+UmVzZXQgWW91ciBQYXNzd29yZDwvYT48L3A+DQoNCjxwPlRoaXMgcGFzc3dvcmQgcmVzZXQgbGluayB3aWxsIGV4cGlyZSBpbiA2MCBtaW51dGVzLjwvcD4NCg0KPHA+SWYgeW91IGRpZCBub3QgcmVxdWVzdCBhIHBhc3N3b3JkIHJlc2V0LCBubyBmdXJ0aGVyIGFjdGlvbiBpcyByZXF1aXJlZC48L3A+DQoNCjxwPlJlZ2FyZHMsPGJyIC8+DQpPbmJvYXJkaW5nPC9wPg0KDQo8aHIgLz4NCjxwPklmIHlvdSYjMzk7cmUgaGF2aW5nIHRyb3VibGUgY2xpY2tpbmcgdGhlICZxdW90O1Jlc2V0IFlvdXImbmJzcDtQYXNzd29yZCZxdW90OyBidXR0b24sIGNvcHkgYW5kIHBhc3RlIHRoZSBVUkwgYmVsb3cgaW50byB5b3VyIHdlYiBicm93c2VyOlJFU0VUX1RPS0VOPC9wPg==', '2025-02-17 18:47:10', '2025-02-19 04:53:34', NULL),
(16, 'User Account Verification', 'Verify your Account', '1', 'yes', 'PHAgc3R5bGU9InRleHQtYWxpZ246IGNlbnRlcjsiPkhpIDxzdHJvbmc+VVNFUl9OQU1FPC9zdHJvbmc+PC9wPg0KDQo8cCBzdHlsZT0idGV4dC1hbGlnbjogY2VudGVyOyI+V2VsY29tZSB0byBTWVNURU1fQVBQTElDQVRJT05fTkFNRTwvcD4NCg0KPHAgc3R5bGU9InRleHQtYWxpZ246IGNlbnRlcjsiPllvdXIgYWNjb3VudCBoYXMgYmVlbiBjcmVhdGVkIGZvciB5b3UuPC9wPg0KDQo8cCBzdHlsZT0idGV4dC1hbGlnbjogY2VudGVyOyI+UGxlYXNlIHZlcmlmeSB5b3VyIGFjY291bnQgYnkgY2xpY2tpbmcgdGhlIGxpbmsgYmVsb3c8L3A+DQoNCjxwIHN0eWxlPSJ0ZXh0LWFsaWduOiBjZW50ZXI7Ij48YSBocmVmPSJWRVJJRklDQVRJT05fTElOSyIgdHlwZT0iYnV0dG9uIj5SZXNldCBQYXNzd29yZDwvYT48L3A+', '2025-02-17 18:47:10', '2025-02-17 18:47:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landlords`
--

CREATE TABLE `landlords` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_rate` float NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `county` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_logged_in` timestamp NULL DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `landlords`
--

INSERT INTO `landlords` (`id`, `name`, `email`, `title`, `company_name`, `work_phone`, `home_phone`, `commission_rate`, `country`, `line1`, `line2`, `line3`, `city`, `county`, `postcode`, `note`, `email_verified_at`, `password`, `remember_token`, `last_logged_in`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(69, 'Test LandLord', 'landlord@gmail.com', 'Mr', NULL, NULL, NULL, 0, 'United Kindgdom', 'House', NULL, NULL, 'test', 'Yorkshire', 'BD20', NULL, '2025-02-19 09:30:38', '$2y$10$o7P3kBZlSPDyzwik5e7KFeMeoFlbgrSOEX0a4dwj9u7nu.azfx.p.', NULL, '2025-02-19 09:31:51', 'Active', '2025-02-19 09:23:19', '2025-02-21 06:09:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `landlords_password_resets`
--

CREATE TABLE `landlords_password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `landlords_password_resets`
--

INSERT INTO `landlords_password_resets` (`email`, `token`, `created_at`) VALUES
('landlord@gmail.com', '$2y$10$nGJEAEI28uv6Tw3.hGmecebNNChaqbM1UOWQLwnUsRypcjDvvGPqC', '2025-02-19 00:29:14');

-- --------------------------------------------------------

--
-- Table structure for table `landlord_email_verification`
--

CREATE TABLE `landlord_email_verification` (
  `landlord_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `landlord_email_verification`
--

INSERT INTO `landlord_email_verification` (`landlord_id`, `token`, `email`, `created_at`, `updated_at`) VALUES
(68, '9BRf7D5V10ufP9Aj5XfiuCnmLEKB4FJ4yQ36ZH3Mai5OkPjRuzEuSr5RY73g', NULL, '2025-02-19 09:04:05', '2025-02-19 09:04:05'),
(70, 'APrPF2xgHzzzXFP4ltINiw01AGV8rofW3j7f4WzZKb3uD7ESoBlfNB9xmwnX', NULL, '2025-02-20 00:41:25', '2025-02-20 00:41:25'),
(71, 'Hu0u6HT0xR9sQFyT2IepaGrLy0kH6NqFhwsf45O1BmZEo5gz9vbNq9zy7Ftc', NULL, '2025-02-21 01:25:19', '2025-02-21 01:25:19'),
(67, 'SVRVnJnyvqsvQitVjAZMvsUtYoJ5IFnn4cPH9DDtg9hcpxsP8Pw4oREErBcV', NULL, '2025-02-19 08:57:56', '2025-02-19 08:57:56');

-- --------------------------------------------------------

--
-- Table structure for table `landlord_profile`
--

CREATE TABLE `landlord_profile` (
  `id` int NOT NULL,
  `landlord_id` int NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `phone_number` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `landlord_profile`
--

INSERT INTO `landlord_profile` (`id`, `landlord_id`, `profile_image`, `phone_number`, `created_at`, `updated_at`) VALUES
(25, 69, 'cHJvZmlsZV9pbWFnZS0xNzM5OTc1NTIx.png', '0300', '2025-02-19 09:23:19', '2025-02-19 09:32:20'),
(26, 70, NULL, '03054388608', '2025-02-20 00:41:25', '2025-02-20 00:41:25'),
(27, 71, NULL, '0300', '2025-02-21 01:25:17', '2025-02-21 01:25:17');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('super@admin.com', '$2y$10$4JMpNBm8loSB2G.Eun0SXOb6duc9qToaPu5rVocxbkDwKO3ixAK8W', '2025-02-18 01:05:45');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` bigint UNSIGNED NOT NULL,
  `tenant_id` bigint DEFAULT NULL,
  `landlord_id` bigint DEFAULT NULL,
  `monthly_rent` float DEFAULT NULL,
  `number_of_floors` int DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `management_charge` tinyint(1) DEFAULT '0',
  `bedrooms` bigint DEFAULT NULL,
  `has_garden` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `is_furnished` int DEFAULT NULL,
  `has_garage` tinyint(1) DEFAULT '0',
  `has_parking` tinyint(1) DEFAULT '0',
  `rent_safe_month` enum('January','February','March','April','May','June','July','August','September','October','November','December') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `line1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `line2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `line3` varchar(255) DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `county` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `postcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gas_certificate_due` date DEFAULT NULL,
  `eicr_due` date DEFAULT NULL,
  `epc_due` date DEFAULT NULL,
  `epc_rate` enum('A','B','C','D','E','F','G') DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `tenant_id`, `landlord_id`, `monthly_rent`, `number_of_floors`, `type`, `management_charge`, `bedrooms`, `has_garden`, `is_active`, `is_furnished`, `has_garage`, `has_parking`, `rent_safe_month`, `line1`, `line2`, `line3`, `city`, `county`, `postcode`, `gas_certificate_due`, `eicr_due`, `epc_due`, `epc_rate`, `status`, `note`, `created_at`, `updated_at`) VALUES
(19, 75, 69, 22.22, 2, 'Bungalow', NULL, 2, NULL, NULL, NULL, NULL, NULL, 'January', 'test', NULL, NULL, 'test', 'dsfdas', '40100', NULL, NULL, NULL, NULL, 'Active', 'no', '2025-02-20 11:31:24', '2025-02-21 06:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `property_types`
--

CREATE TABLE `property_types` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `property_types`
--

INSERT INTO `property_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(3, 'Semi', '2025-02-20 12:57:29', '2025-02-20 12:57:29'),
(4, 'Detached', '2025-02-20 12:57:37', '2025-02-20 12:57:37'),
(5, 'Terraced', '2025-02-20 12:57:51', '2025-02-20 12:57:51'),
(6, 'Flat', '2025-02-20 12:58:27', '2025-02-20 12:58:27'),
(7, 'Bungalow', '2025-02-20 12:58:39', '2025-02-20 12:58:39'),
(8, 'Cottage', '2025-02-20 12:58:52', '2025-02-20 12:58:52'),
(9, 'Maisonette', '2025-02-20 12:59:02', '2025-02-20 12:59:02'),
(10, 'Other', '2025-02-20 12:59:12', '2025-02-20 12:59:12'),
(11, 'House', '2025-02-20 13:18:54', '2025-02-20 13:18:54');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `view` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `key`, `value`, `view`, `created_at`, `updated_at`) VALUES
(14, 'logo', 'logo', 'logo.png', NULL, '2024-08-26 06:24:51', '2025-02-18 00:57:30'),
(15, 'favicon', 'logo', 'favicon.jpg', NULL, '2024-08-26 06:24:51', '2025-02-17 13:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_logged_in` timestamp NULL DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `last_logged_in`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(75, 'Test Tenant', 'tenant@gmail.com', '2025-02-19 07:56:44', '$2y$10$vRtOSOXpWzTfWtuzLHnjge8HQSASWVCtry1xikbiEsIYCeyuhK.DW', NULL, '2025-02-19 07:56:56', 'Active', '2025-02-19 07:56:13', '2025-02-21 06:24:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tenants_password_resets`
--

CREATE TABLE `tenants_password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tenants_password_resets`
--

INSERT INTO `tenants_password_resets` (`email`, `token`, `created_at`) VALUES
('tenant@gmail.com', '$2y$10$c617e5vq/jVwWESTt7d0uOzPc5iBYGMAoUDL62eCbA/rF2hzrmn1O', '2025-02-19 00:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_email_verification`
--

CREATE TABLE `tenant_email_verification` (
  `tenant_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tenant_email_verification`
--

INSERT INTO `tenant_email_verification` (`tenant_id`, `token`, `email`, `created_at`, `updated_at`) VALUES
(72, '3xYvJz6nez2x9tauViA4KYkGZjy3hhdleIorYJoC0Mifb0gl4o7sJCnzTaEy', NULL, '2025-02-19 06:57:22', '2025-02-19 06:57:22'),
(70, 'DwXOKHLnomAzHipGrMzWsjtdXgYuu2keskNo2iypWHliyWpOvFt9FSaIewid', NULL, '2025-02-19 06:44:08', '2025-02-19 06:44:08'),
(74, 'FIKgbxdnwS28IfKJfU24b63R8fIP4WjdnsCV0Dyu4nC2CHJGWCG4VtIFgn55', NULL, '2025-02-19 07:54:31', '2025-02-19 07:54:31'),
(71, 'GAAR4BSBPYYILWU90Nb3s2jiyven1FgWDpvuILPKYEHmbHDRajybwdC1JRvm', NULL, '2025-02-19 06:54:20', '2025-02-19 06:54:20'),
(76, 'SHSMWubV4x9NWjZubRZAu3h2u4SmDt8oqYQn0gD4QTNutYX65a8NWEKRoC4X', NULL, '2025-02-20 00:38:14', '2025-02-20 00:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_profile`
--

CREATE TABLE `tenant_profile` (
  `id` int NOT NULL,
  `tenant_id` int NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `phone_number` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tenant_profile`
--

INSERT INTO `tenant_profile` (`id`, `tenant_id`, `profile_image`, `phone_number`, `created_at`, `updated_at`) VALUES
(31, 75, 'cHJvZmlsZV9pbWFnZS0xNzM5OTY5ODc5.png', '0300', '2025-02-19 07:56:13', '2025-02-19 07:57:59'),
(32, 76, NULL, '03054388608', '2025-02-20 00:38:12', '2025-02-20 00:38:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_email_verification`
--
ALTER TABLE `admin_email_verification`
  ADD PRIMARY KEY (`token`);

--
-- Indexes for table `admin_profile`
--
ALTER TABLE `admin_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contractors`
--
ALTER TABLE `contractors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contractors_email_unique` (`email`);

--
-- Indexes for table `contractor_email_verification`
--
ALTER TABLE `contractor_email_verification`
  ADD PRIMARY KEY (`token`);

--
-- Indexes for table `contractor_profile`
--
ALTER TABLE `contractor_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `landlords`
--
ALTER TABLE `landlords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `landlords_email_unique` (`email`);

--
-- Indexes for table `landlord_email_verification`
--
ALTER TABLE `landlord_email_verification`
  ADD PRIMARY KEY (`token`);

--
-- Indexes for table `landlord_profile`
--
ALTER TABLE `landlord_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_types`
--
ALTER TABLE `property_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenants_email_unique` (`email`);

--
-- Indexes for table `tenant_email_verification`
--
ALTER TABLE `tenant_email_verification`
  ADD PRIMARY KEY (`token`);

--
-- Indexes for table `tenant_profile`
--
ALTER TABLE `tenant_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `admin_profile`
--
ALTER TABLE `admin_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `contractors`
--
ALTER TABLE `contractors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `contractor_profile`
--
ALTER TABLE `contractor_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landlords`
--
ALTER TABLE `landlords`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `landlord_profile`
--
ALTER TABLE `landlord_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `property_types`
--
ALTER TABLE `property_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `tenant_profile`
--
ALTER TABLE `tenant_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
