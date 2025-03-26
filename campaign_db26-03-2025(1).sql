-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 06:06 PM
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
-- Database: `campaign_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cmp_campaign`
--

CREATE TABLE `cmp_campaign` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `restrictLangCode` tinyint(1) NOT NULL,
  `timezone` varchar(55) NOT NULL,
  `schedule_at` datetime NOT NULL,
  `send_num` bigint(11) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_campaign`
--

INSERT INTO `cmp_campaign` (`id`, `group_id`, `template_id`, `title`, `restrictLangCode`, `timezone`, `schedule_at`, `send_num`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 9, 7, 'New Campaign', 1, 'UTC', '2025-03-26 11:16:48', 919025741523, 1, 7, '2025-03-26 15:46:48', 0, '0000-00-00 00:00:00', 1),
(2, 9, 7, 'New Campaign', 1, 'UTC', '2025-03-26 14:49:19', 919025741523, 1, 7, '2025-03-26 15:49:19', 0, '0000-00-00 00:00:00', 1),
(3, 9, 5, 'text', 0, '', '2025-03-26 20:51:29', 0, 1, 7, '2025-03-26 20:51:29', 0, '0000-00-00 00:00:00', 1),
(4, 9, 5, 'seasonal_promos', 0, '', '2025-03-26 21:07:37', 0, 1, 7, '2025-03-26 21:07:37', 0, '0000-00-00 00:00:00', 1),
(5, 9, 7, 'New Campaign', 1, 'UTC', '2025-03-26 22:01:01', 919025741523, 1, 7, '2025-03-26 22:01:01', 0, '0000-00-00 00:00:00', 1),
(6, 9, 7, 'New Campaign', 1, 'UTC', '2025-03-26 22:04:11', 919025741523, 1, 7, '2025-03-26 22:04:11', 0, '0000-00-00 00:00:00', 1),
(7, 9, 7, 'New Campaign', 1, 'UTC', '2025-03-26 22:07:08', 919025741523, 1, 7, '2025-03-26 22:07:08', 0, '0000-00-00 00:00:00', 1),
(8, 9, 7, 'text', 0, 'UTC', '2025-03-28 22:22:00', 0, 1, 7, '2025-03-26 22:22:40', 0, '0000-00-00 00:00:00', 1),
(9, 9, 7, 'New Campaign', 1, 'UTC', '2025-03-26 22:23:05', 919025741523, 1, 7, '2025-03-26 22:23:05', 0, '0000-00-00 00:00:00', 1),
(10, 9, 7, 'hi_temp', 1, 'UTC', '2025-03-27 22:32:00', 0, 1, 7, '2025-03-26 22:32:13', 0, '0000-00-00 00:00:00', 1),
(11, 9, 7, 'hello_template', 1, 'UTC', '2025-03-27 22:32:00', 0, 1, 7, '2025-03-26 22:34:08', 0, '0000-00-00 00:00:00', 1),
(12, 9, 7, 'hello_hs', 1, 'UTC', '2025-03-27 22:32:00', 0, 1, 7, '2025-03-26 22:35:29', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_campaign_contact`
--

CREATE TABLE `cmp_campaign_contact` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_campaign_contact`
--

INSERT INTO `cmp_campaign_contact` (`id`, `campaign_id`, `contact_id`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 7, 9, 1, 7, '2025-03-26 22:07:09', 0, '0000-00-00 00:00:00', 1),
(2, 0, 9, 1, 7, '2025-03-26 22:08:57', 0, '0000-00-00 00:00:00', 1),
(3, 0, 10, 1, 7, '2025-03-26 22:08:58', 0, '0000-00-00 00:00:00', 1),
(4, 8, 9, 1, 7, '2025-03-26 22:22:41', 0, '0000-00-00 00:00:00', 1),
(5, 8, 10, 1, 7, '2025-03-26 22:22:42', 0, '0000-00-00 00:00:00', 1),
(6, 9, 9, 1, 7, '2025-03-26 22:23:06', 0, '0000-00-00 00:00:00', 1),
(7, 9, 10, 1, 7, '2025-03-26 22:23:07', 0, '0000-00-00 00:00:00', 1),
(8, 10, 9, 1, 7, '2025-03-26 22:32:13', 0, '0000-00-00 00:00:00', 1),
(9, 10, 10, 1, 7, '2025-03-26 22:32:14', 0, '0000-00-00 00:00:00', 1),
(10, 11, 9, 1, 7, '2025-03-26 22:34:09', 0, '0000-00-00 00:00:00', 1),
(11, 11, 10, 1, 7, '2025-03-26 22:34:10', 0, '0000-00-00 00:00:00', 1),
(12, 12, 9, 1, 7, '2025-03-26 22:35:29', 0, '0000-00-00 00:00:00', 1),
(13, 12, 10, 1, 7, '2025-03-26 22:35:30', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_campaign_variable_mapping`
--

CREATE TABLE `cmp_campaign_variable_mapping` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `variable_type_id` int(11) NOT NULL,
  `variable_value` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_campaign_variable_mapping`
--

INSERT INTO `cmp_campaign_variable_mapping` (`id`, `campaign_id`, `template_id`, `variable_type_id`, `variable_value`, `group_id`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 1, 7, 1, 1, 9, 1, 7, '2025-03-26 15:46:48', 0, '0000-00-00 00:00:00', 1),
(2, 1, 7, 2, 3, 9, 1, 7, '2025-03-26 15:46:48', 0, '0000-00-00 00:00:00', 1),
(3, 2, 7, 1, 1, 9, 1, 7, '2025-03-26 15:49:19', 0, '0000-00-00 00:00:00', 1),
(4, 2, 7, 2, 3, 9, 1, 7, '2025-03-26 15:49:19', 0, '0000-00-00 00:00:00', 1),
(5, 3, 5, 1, 1, 9, 1, 7, '2025-03-26 20:51:29', 0, '0000-00-00 00:00:00', 1),
(6, 3, 5, 2, 4, 9, 1, 7, '2025-03-26 20:51:29', 0, '0000-00-00 00:00:00', 1),
(7, 3, 5, 3, 6, 9, 1, 7, '2025-03-26 20:51:29', 0, '0000-00-00 00:00:00', 1),
(8, 4, 5, 1, 1, 9, 1, 7, '2025-03-26 21:07:37', 0, '0000-00-00 00:00:00', 1),
(9, 4, 5, 2, 2, 9, 1, 7, '2025-03-26 21:07:37', 0, '0000-00-00 00:00:00', 1),
(10, 4, 5, 3, 3, 9, 1, 7, '2025-03-26 21:07:37', 0, '0000-00-00 00:00:00', 1),
(11, 5, 7, 1, 1, 9, 1, 7, '2025-03-26 22:01:04', 0, '0000-00-00 00:00:00', 1),
(12, 5, 7, 2, 3, 9, 1, 7, '2025-03-26 22:01:04', 0, '0000-00-00 00:00:00', 1),
(13, 6, 7, 1, 1, 9, 1, 7, '2025-03-26 22:04:14', 0, '0000-00-00 00:00:00', 1),
(14, 6, 7, 2, 3, 9, 1, 7, '2025-03-26 22:04:14', 0, '0000-00-00 00:00:00', 1),
(15, 9, 7, 1, 1, 9, 1, 7, '2025-03-26 22:23:08', 0, '0000-00-00 00:00:00', 1),
(16, 9, 7, 2, 3, 9, 1, 7, '2025-03-26 22:23:08', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_contact`
--

CREATE TABLE `cmp_contact` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `mobile` bigint(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `anniversary` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `loyality` varchar(255) NOT NULL,
  `language_code` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_contact`
--

INSERT INTO `cmp_contact` (`id`, `vendor_id`, `store_id`, `first_name`, `last_name`, `mobile`, `email`, `date_of_birth`, `anniversary`, `address`, `loyality`, `language_code`, `country`, `group_id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 2, 2, 'Thass', 'tha', 9025714445, 'test@example.com', '2025-12-22', '0000-00-00', 'sdgsdysdbsd', 'shvdjhsvdh', 'tamil', 'india', 0, 7, '2025-03-24 17:36:31', 0, '0000-00-00 00:00:00', 1),
(2, 2, 3, 'Thass', 'tha', 9025714444, 'test@example.com', '2025-12-22', '2025-12-28', 'sdgsdysdbsd', 'shvdjhsvdh', 'tamil', 'india', 0, 7, '2025-03-24 17:38:54', 7, '2025-03-24 17:43:57', 1),
(3, 2, 2, 'text', '', 8765432167, 'text@gmail.com', '0000-00-00', '0000-00-00', 'hs', '', 'en', 'seasonal_promos', 0, 7, '2025-03-24 19:16:15', 0, '0000-00-00 00:00:00', 0),
(4, 2, 2, 'selva', '`', 9876543215, 'sg@gmail.com', '0000-00-00', '0000-00-00', 'vvv', '', 'en', 'India', 0, 7, '2025-03-24 19:22:26', 7, '2025-03-25 12:59:49', 1),
(5, 2, 5, 'selvam', '', 987654326, 'selvam@gmail.com', '0000-00-00', '0000-00-00', 'None', '', 'en_US', 'India', 0, 7, '2025-03-24 20:29:32', 7, '2025-03-25 14:51:22', 1),
(6, 2, 16, 'test name', '', 9444944494, 'test@mail.com', '0000-00-00', '0000-00-00', 'no.10,test address', '', 'En', 'india', 0, 7, '2025-03-25 14:53:38', 7, '2025-03-25 18:59:40', 0),
(7, 2, 6, 'Selva', '', 9876543218, 'sg@gmail.com', '0000-00-00', '0000-00-00', 'Chennai', '', 'en_US', 'India', 0, 7, '2025-03-25 19:01:54', 7, '2025-03-25 20:08:35', 1),
(8, 2, 2, 'seasonal_promos', '', 9876543217, 'ses@gmail.com', '0000-00-00', '0000-00-00', 'None', '', 'en', 'India', 0, 7, '2025-03-25 20:09:24', 7, '2025-03-25 20:09:44', 1),
(9, 2, 4, 'hs', '', 916384626418, 'hs@gmail.com', '0000-00-00', '0000-00-00', 'text', '', 'en_US', 'India', 0, 7, '2025-03-26 20:54:56', 0, '0000-00-00 00:00:00', 1),
(10, 2, 16, 'anssz', '', 917092085411, 'anssz@gmail.com', '0000-00-00', '0000-00-00', 'None', '', 'tamil', 'India', 0, 7, '2025-03-26 20:56:38', 0, '0000-00-00 00:00:00', 1),
(11, 2, 8, 'acc', '', 876543216, 'acc@gmail.com', '0000-00-00', '0000-00-00', 'None', '', 'en', 'India', 0, 7, '2025-03-26 20:57:21', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_group_contact`
--

CREATE TABLE `cmp_group_contact` (
  `id` int(11) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_group_contact`
--

INSERT INTO `cmp_group_contact` (`id`, `uid`, `vendor_id`, `group_name`, `description`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, '2147483647', 2, 'TestGroup ', 'This is a sample test group ', 1, 7, '2025-03-24 14:40:37', 7, '2025-03-24 16:02:45', 0),
(2, '017b10cbb4ab294e', 2, 'Test Group 1', 'This is a sample test group 1', 1, 7, '2025-03-24 14:42:23', 0, '0000-00-00 00:00:00', 0),
(3, 'de069adc6b351bd0', 2, 'Test Group 2', 'This is a sample test group 2', 1, 7, '2025-03-24 15:22:30', 0, '0000-00-00 00:00:00', 1),
(4, '8ef665953a973bc6', 2, 'Test Group 3', 'This is a sample test group 3', 1, 7, '2025-03-24 15:38:24', 0, '0000-00-00 00:00:00', 1),
(5, 'c2a054ea546c3531', 2, 'Test Group 4', 'This is a sample test group 4', 1, 7, '2025-03-24 15:40:22', 7, '2025-03-24 16:10:05', 1),
(6, '999409380ca5325d', 2, 'Test Group 5', 'This is a sample test group 5', 1, 7, '2025-03-24 15:41:37', 0, '0000-00-00 00:00:00', 1),
(7, '98594541802c1552', 2, 'Test Group 6', 'This is a sample test group 6', 1, 7, '2025-03-24 15:44:30', 0, '0000-00-00 00:00:00', 1),
(8, '464b1205210b68a5', 2, 'Test Group 7', 'This is a sample test group 7', 1, 7, '2025-03-24 15:46:00', 0, '0000-00-00 00:00:00', 1),
(9, '46e8ae720991d3f6', 2, 'Test Group 8', 'This is a sample test group 8', 1, 7, '2025-03-24 16:15:51', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_group_contact_mapping`
--

CREATE TABLE `cmp_group_contact_mapping` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_group_contact_mapping`
--

INSERT INTO `cmp_group_contact_mapping` (`id`, `group_id`, `contact_id`, `active_status`, `created_by`, `created_date`, `status`) VALUES
(1, 9, 9, 1, 7, '2025-03-26 20:54:56', 1),
(2, 8, 9, 1, 7, '2025-03-26 20:54:56', 1),
(3, 7, 9, 1, 7, '2025-03-26 20:54:56', 1),
(4, 9, 10, 1, 7, '2025-03-26 20:56:38', 1),
(5, 6, 11, 1, 7, '2025-03-26 20:57:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_mst_country`
--

CREATE TABLE `cmp_mst_country` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `iso_code` char(2) DEFAULT NULL,
  `name_capitalized` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `iso3_code` char(3) DEFAULT NULL,
  `iso_num_code` smallint(6) DEFAULT NULL,
  `phone_code` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cmp_mst_country`
--

INSERT INTO `cmp_mst_country` (`id`, `iso_code`, `name_capitalized`, `name`, `iso3_code`, `iso_num_code`, `phone_code`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8, 355),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20, 376),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24, 244),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL, 0),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533, 297),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 61),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112, 375),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204, 229),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL, 0),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL, 246),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124, 1),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236),
(42, 'TD', 'CHAD', 'Chad', 'TCD', 148, 235),
(43, 'CL', 'CHILE', 'Chile', 'CHL', 152, 56),
(44, 'CN', 'CHINA', 'China', 'CHN', 156, 86),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL, 61),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL, 672),
(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57),
(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174, 269),
(49, 'CG', 'CONGO', 'Congo', 'COG', 178, 242),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 243),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506),
(53, 'CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384, 225),
(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191, 385),
(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192, 53),
(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420),
(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208, 45),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253),
(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809),
(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593),
(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818, 20),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240),
(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291),
(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233, 372),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298),
(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242, 679),
(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246, 358),
(73, 'FR', 'FRANCE', 'France', 'FRA', 250, 33),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL, 0),
(77, 'GA', 'GABON', 'Gabon', 'GAB', 266, 241),
(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220),
(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995),
(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276, 49),
(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288, 233),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350),
(83, 'GR', 'GREECE', 'Greece', 'GRC', 300, 30),
(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299),
(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590),
(87, 'GU', 'GUAM', 'Guam', 'GUM', 316, 1671),
(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502),
(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324, 224),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245),
(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328, 592),
(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332, 509),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL, 0),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39),
(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340, 504),
(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852),
(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36),
(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352, 354),
(99, 'IN', 'INDIA', 'India', 'IND', 356, 91),
(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98),
(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964),
(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372, 353),
(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376, 972),
(105, 'IT', 'ITALY', 'Italy', 'ITA', 380, 39),
(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876),
(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392, 81),
(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400, 962),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7),
(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404, 254),
(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408, 850),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82),
(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996),
(116, 'LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418, 856),
(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428, 371),
(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961),
(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266),
(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423),
(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352),
(125, 'MO', 'MACAO', 'Macao', 'MAC', 446, 853),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261),
(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454, 265),
(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60),
(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960),
(131, 'ML', 'MALI', 'Mali', 'MLI', 466, 223),
(132, 'MT', 'MALTA', 'Malta', 'MLT', 470, 356),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596),
(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222),
(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230),
(137, 'YT', 'MAYOTTE', 'Mayotte', NULL, NULL, 269),
(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484, 52),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373),
(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492, 377),
(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664),
(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258),
(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95),
(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264),
(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520, 674),
(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524, 977),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505),
(155, 'NE', 'NIGER', 'Niger', 'NER', 562, 227),
(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234),
(157, 'NU', 'NIUE', 'Niue', 'NIU', 570, 683),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670),
(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578, 47),
(161, 'OM', 'OMAN', 'Oman', 'OMN', 512, 968),
(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92),
(163, 'PW', 'PALAU', 'Palau', 'PLW', 585, 680),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL, 970),
(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591, 507),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675),
(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595),
(168, 'PE', 'PERU', 'Peru', 'PER', 604, 51),
(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0),
(171, 'PL', 'POLAND', 'Poland', 'POL', 616, 48),
(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787),
(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634, 974),
(175, 'RE', 'REUNION', 'Reunion', 'REU', 638, 262),
(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642, 40),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643, 7),
(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784),
(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882, 684),
(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966),
(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232),
(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421),
(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677),
(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27),
(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL, 0),
(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724, 34),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94),
(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736, 249),
(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740, 597),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268),
(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255),
(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764, 66),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL, 670),
(213, 'TG', 'TOGO', 'Togo', 'TGO', 768, 228),
(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690),
(215, 'TO', 'TONGA', 'Tonga', 'TON', 776, 676),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868),
(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216),
(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792, 90),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649),
(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688),
(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800, 256),
(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44),
(226, 'US', 'UNITED STATES', 'United States', 'USA', 840, 1),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL, 1),
(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998),
(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678),
(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58),
(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212),
(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887, 967),
(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263),
(240, 'RS', 'SERBIA', 'Serbia', 'SRB', 688, 381),
(241, 'AP', 'ASIA PACIFIC REGION', 'Asia / Pacific Region', '0', 0, 0),
(242, 'ME', 'MONTENEGRO', 'Montenegro', 'MNE', 499, 382),
(243, 'AX', 'ALAND ISLANDS', 'Aland Islands', 'ALA', 248, 358),
(244, 'BQ', 'BONAIRE, SINT EUSTATIUS AND SABA', 'Bonaire, Sint Eustatius and Saba', 'BES', 535, 599),
(245, 'CW', 'CURACAO', 'Curacao', 'CUW', 531, 599),
(246, 'GG', 'GUERNSEY', 'Guernsey', 'GGY', 831, 44),
(247, 'IM', 'ISLE OF MAN', 'Isle of Man', 'IMN', 833, 44),
(248, 'JE', 'JERSEY', 'Jersey', 'JEY', 832, 44),
(249, 'XK', 'KOSOVO', 'Kosovo', '---', 0, 381),
(250, 'BL', 'SAINT BARTHELEMY', 'Saint Barthelemy', 'BLM', 652, 590),
(251, 'MF', 'SAINT MARTIN', 'Saint Martin', 'MAF', 663, 590),
(252, 'SX', 'SINT MAARTEN', 'Sint Maarten', 'SXM', 534, 1),
(253, 'SS', 'SOUTH SUDAN', 'South Sudan', 'SSD', 728, 211);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_mst_module`
--

CREATE TABLE `cmp_mst_module` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_mst_module`
--

INSERT INTO `cmp_mst_module` (`id`, `name`, `description`, `active_status`, `created_date`, `updated_date`, `status`) VALUES
(1, 'store', '', 1, '2025-02-20 12:21:28', '0000-00-00 00:00:00', 1),
(2, 'staff', '', 1, '2025-02-20 12:21:28', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_mst_permission`
--

CREATE TABLE `cmp_mst_permission` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_mst_permission`
--

INSERT INTO `cmp_mst_permission` (`id`, `name`, `description`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 'create', 'Allows creating new records', 1, 0, '2025-02-24 12:31:25', 0, '0000-00-00 00:00:00', 1),
(2, 'read', 'Allows reading/viewing records', 1, 0, '2025-02-24 12:31:25', 0, '0000-00-00 00:00:00', 1),
(3, 'update', 'Allows updating existing records', 1, 0, '2025-02-24 12:31:25', 0, '0000-00-00 00:00:00', 1),
(4, 'delete', 'Allows deleting records', 1, 0, '2025-02-24 12:31:25', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_mst_role`
--

CREATE TABLE `cmp_mst_role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(40) NOT NULL,
  `role_status` varchar(40) NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_mst_role`
--

INSERT INTO `cmp_mst_role` (`role_id`, `role_name`, `role_status`, `created_date`, `updated_date`, `status`) VALUES
(1, 'super_admin', '1', '2025-02-20 11:39:37', '0000-00-00 00:00:00', 1),
(2, 'vendor_super_admin', '1', '2025-02-20 11:39:37', '0000-00-00 00:00:00', 1),
(3, 'vendor_admin', '0', '2025-02-20 11:39:37', '0000-00-00 00:00:00', 0),
(4, 'vendor_user', '1', '2025-02-20 11:39:37', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_mst_variable`
--

CREATE TABLE `cmp_mst_variable` (
  `id` int(11) NOT NULL,
  `variable_name` varchar(50) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_mst_variable`
--

INSERT INTO `cmp_mst_variable` (`id`, `variable_name`, `active_status`, `status`) VALUES
(1, 'Contact Full name', 1, 1),
(2, 'Contact First name', 1, 1),
(3, 'Contact Last name', 1, 1),
(4, 'Contact Phone', 1, 1),
(5, 'Contact Language code', 1, 1),
(6, 'Contact Country', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_mst_wa_temp_payload_strc`
--

CREATE TABLE `cmp_mst_wa_temp_payload_strc` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_mst_wa_temp_payload_strc`
--

INSERT INTO `cmp_mst_wa_temp_payload_strc` (`id`, `type`, `payload`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 'base_template', '{\n    \"messaging_product\": \"whatsapp\",\n    \"to\": \"\",\n    \"type\": \"template\",\n    \"template\": {\n        \"name\": \"\",\n        \"language\": {\n            \"code\": \"\"\n        }\n    }\n}', 1, 0, '2025-03-25 20:57:01', 0, '2025-03-25 16:26:42', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_privilege`
--

CREATE TABLE `cmp_privilege` (
  `id` int(11) NOT NULL,
  `priv_name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_privilege`
--

INSERT INTO `cmp_privilege` (`id`, `priv_name`, `description`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 'admin', 'Full access to Admin modules', 1, 2, '2025-03-05 20:38:53', 2, '2025-03-05 20:44:03', 1),
(2, 'manager', 'manager testing', 1, 2, '2025-03-05 20:46:54', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_privilege_module_permission_mapping`
--

CREATE TABLE `cmp_privilege_module_permission_mapping` (
  `id` int(11) NOT NULL,
  `priv_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `pre_create` int(11) NOT NULL,
  `pre_read` int(11) NOT NULL,
  `pre_update` int(11) NOT NULL,
  `pre_delete` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_privilege_module_permission_mapping`
--

INSERT INTO `cmp_privilege_module_permission_mapping` (`id`, `priv_id`, `mod_id`, `pre_create`, `pre_read`, `pre_update`, `pre_delete`, `created_by`, `created_date`, `updated_by`, `updated_date`, `active_status`, `status`) VALUES
(1, 1, 1, 1, 1, 1, 0, 2, '2025-03-05 20:38:53', 2, '2025-03-05 20:44:03', 1, 1),
(2, 1, 2, 1, 1, 0, 0, 2, '2025-03-05 20:44:01', 2, '2025-03-05 20:44:03', 1, 1),
(3, 2, 1, 1, 1, 1, 1, 2, '2025-03-05 20:46:54', 0, '0000-00-00 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_store`
--

CREATE TABLE `cmp_store` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `store_name` varchar(100) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) NOT NULL,
  `dist` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` int(11) NOT NULL,
  `phone` bigint(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_store`
--

INSERT INTO `cmp_store` (`id`, `uid`, `store_name`, `address_line1`, `address_line2`, `dist`, `state`, `pincode`, `phone`, `email`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 'c31f9653d419e8c2', 'trends1', 'trichy', '', '', '', 0, 9638527410, 'trichytrends@example.com', 1, 2, '2025-03-05 20:34:36', 0, '0000-00-00 00:00:00', 1),
(2, '256adc499c1410f2', 'Trends', 'Chrompet123', '', '', '', 0, 9876543212, 'trends@gmail.com', 1, 7, '2025-03-05 20:50:39', 7, '2025-03-05 20:56:00', 1),
(3, '3d1eebb5e547ce0d', 'redho', 'trichy1', 'trichy2', 'trichy3', 'trichy4', 123456, 9638527410, 'thass@example.com', 1, 7, '2025-03-05 20:53:30', 7, '2025-03-24 12:50:32', 1),
(4, 'f69f05c0b44dadc3', 'Trendsz', 'trichy', '', '', '', 0, 9638527410, 'trichytrendsz@example.com', 1, 7, '2025-03-06 12:35:16', 7, '2025-03-06 16:22:01', 1),
(5, 'ce6ac122d9556846', 'Bilal', 'Chrompet', '', '', '', 0, 9876543212, 'bilal@gmail.com', 1, 7, '2025-03-06 16:21:27', 0, '0000-00-00 00:00:00', 1),
(6, '5e03758b9be70bfd', 'Fruit Stall', 'Chennai', '', '', '', 0, 9876543245, 'fs@gmail.com', 1, 7, '2025-03-06 16:54:58', 0, '0000-00-00 00:00:00', 1),
(7, 'bd32ca15ea67b0b2', 'Saravan Store', 'Chrompet', '', '', '', 0, 9876543212, 'ssstore@gmail.com', 1, 7, '2025-03-06 18:51:59', 0, '0000-00-00 00:00:00', 1),
(8, 'afe04c32756c6da2', 'Pothys', 'T.Nagar', '', '', '', 0, 876543212, 'pothys@gmail.com', 1, 7, '2025-03-06 19:15:31', 0, '0000-00-00 00:00:00', 1),
(9, '597444df79e63e2a', 'vvv', 'chennai', '', '', '', 0, 9876543212, 'hs@gmail.com', 0, 1, '2025-03-07 15:12:13', 0, '0000-00-00 00:00:00', 0),
(10, 'c2c15d6776995898', 'dfs', 'jghj', '', '', '', 0, 98765432123, 'hhjfd@gmail.com', 1, 25, '2025-03-07 16:12:49', 0, '0000-00-00 00:00:00', 1),
(11, '', 'trends2', 'chennai', '', '', '', 0, 9025714441, 'trichytrends2@example.com', 1, 2, '2025-03-10 17:51:48', 0, '0000-00-00 00:00:00', 1),
(13, ' df095f2937826a8e ', 'trends3', 'chennai', '', '', '', 0, 9025714442, 'trichytrends3@example.com', 1, 2, '2025-03-10 17:53:39', 0, '0000-00-00 00:00:00', 1),
(15, ' e6ace3c0ca9e8b1b ', 'trends4', 'arathangi', '', '', '', 0, 9025714443, 'trichytrends4@example.com', 1, 2, '2025-03-10 17:54:05', 0, '0000-00-00 00:00:00', 1),
(16, ' 740c4e07e8ea3832 ', 'Teams', 'Chrompet1', '', '', '', 0, 9876543212, 'teams@gmail.com', 1, 7, '2025-03-14 18:46:38', 7, '2025-03-21 14:16:17', 1),
(17, '84639e25f10f9dce', 'n', 'b', '', '', '', 0, 7, 'hs@gmail.com', 1, 7, '2025-03-21 13:14:09', 0, '0000-00-00 00:00:00', 0),
(18, '5e142b1bc9f7704d', 'f', 'm', '', '', '', 0, 7, 'hs@gmail.com', 1, 7, '2025-03-21 13:19:22', 0, '0000-00-00 00:00:00', 0),
(19, '0d61d11c4cec1c55', 'GRT', 'Tambaram Santorium', 'West', 'Chennai', 'Tamilnadu', 612203, 9876543215, 'grts@gmail.com', 1, 7, '2025-03-24 13:18:35', 7, '2025-03-24 13:19:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_superadmin_vendor_login_log`
--

CREATE TABLE `cmp_superadmin_vendor_login_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `login_time` datetime NOT NULL,
  `last_active_time` datetime NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL,
  `login_status` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_superadmin_vendor_login_log`
--

INSERT INTO `cmp_superadmin_vendor_login_log` (`id`, `user_id`, `vendor_id`, `token`, `login_time`, `last_active_time`, `created_date`, `updated_date`, `login_status`, `status`) VALUES
(1, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-06 16:22:52', '2025-03-06 16:22:52', '2025-03-06 20:52:52', '0000-00-00 00:00:00', 1, 1),
(2, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-06 16:23:05', '2025-03-06 16:23:05', '2025-03-06 20:53:05', '0000-00-00 00:00:00', 1, 1),
(3, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-06 20:55:25', '2025-03-06 20:55:25', '2025-03-06 20:55:25', '0000-00-00 00:00:00', 1, 1),
(4, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 12:58:04', '2025-03-07 12:58:04', '2025-03-07 12:58:04', '0000-00-00 00:00:00', 1, 1),
(5, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 14:46:37', '2025-03-07 14:46:37', '2025-03-07 14:46:37', '0000-00-00 00:00:00', 1, 1),
(6, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 14:47:15', '2025-03-07 14:47:15', '2025-03-07 14:47:15', '0000-00-00 00:00:00', 1, 1),
(7, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 14:48:14', '2025-03-07 14:48:14', '2025-03-07 14:48:14', '0000-00-00 00:00:00', 1, 1),
(8, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 14:48:54', '2025-03-07 14:48:54', '2025-03-07 14:48:54', '0000-00-00 00:00:00', 1, 1),
(9, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 14:49:58', '2025-03-07 14:49:58', '2025-03-07 14:49:58', '0000-00-00 00:00:00', 1, 1),
(10, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 15:05:58', '2025-03-07 15:05:58', '2025-03-07 15:05:58', '0000-00-00 00:00:00', 1, 1),
(11, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 15:06:53', '2025-03-07 15:06:53', '2025-03-07 15:06:53', '0000-00-00 00:00:00', 1, 1),
(12, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 15:11:44', '2025-03-07 15:11:44', '2025-03-07 15:11:44', '0000-00-00 00:00:00', 1, 1),
(13, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 15:19:17', '2025-03-07 15:19:17', '2025-03-07 15:19:17', '0000-00-00 00:00:00', 1, 1),
(14, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 15:58:40', '2025-03-07 15:58:40', '2025-03-07 15:58:40', '0000-00-00 00:00:00', 1, 1),
(15, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-09 22:39:15', '2025-03-09 22:39:15', '2025-03-09 22:39:15', '0000-00-00 00:00:00', 1, 1),
(16, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-09 23:04:37', '2025-03-09 23:04:37', '2025-03-09 23:04:37', '0000-00-00 00:00:00', 1, 1),
(17, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-09 23:08:09', '2025-03-09 23:08:09', '2025-03-09 23:08:09', '0000-00-00 00:00:00', 1, 1),
(18, 1, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-09 23:10:03', '2025-03-09 23:10:03', '2025-03-09 23:10:03', '0000-00-00 00:00:00', 1, 1),
(19, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.KyWGdZ6GqadtPa6I5ouJCpCMbblpVaw7FK6yB0U1fQ', '2025-03-09 23:11:56', '2025-03-09 23:11:56', '2025-03-09 23:11:56', '0000-00-00 00:00:00', 0, 1),
(20, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.KyWGdZ6GqadtPa6I5ouJCpCMbblpVaw7FK6yB0U1fQ', '2025-03-09 23:11:59', '2025-03-09 23:11:59', '2025-03-09 23:11:59', '0000-00-00 00:00:00', 0, 1),
(21, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.KyWGdZ6GqadtPa6I5ouJCpCMbblpVaw7FK6yB0U1fQ', '2025-03-09 23:15:03', '2025-03-09 23:15:03', '2025-03-09 23:15:03', '0000-00-00 00:00:00', 0, 1),
(22, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.KyWGdZ6GqadtPa6I5ouJCpCMbblpVaw7FK6yB0U1fQ', '2025-03-09 23:17:46', '2025-03-09 23:17:46', '2025-03-09 23:17:46', '0000-00-00 00:00:00', 0, 1),
(23, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 23:20:19', '2025-03-09 23:20:19', '2025-03-09 23:20:19', '0000-00-00 00:00:00', 0, 1),
(24, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 23:22:10', '2025-03-09 23:22:10', '2025-03-09 23:22:10', '0000-00-00 00:00:00', 0, 1),
(25, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 23:25:08', '2025-03-09 23:25:08', '2025-03-09 23:25:08', '0000-00-00 00:00:00', 0, 1),
(26, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 23:31:29', '2025-03-09 23:31:29', '2025-03-09 23:31:29', '0000-00-00 00:00:00', 0, 1),
(27, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 23:32:45', '2025-03-09 23:32:45', '2025-03-09 23:32:45', '0000-00-00 00:00:00', 0, 1),
(28, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 23:42:09', '2025-03-09 23:42:09', '2025-03-09 23:42:09', '0000-00-00 00:00:00', 0, 1),
(29, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-10 12:17:16', '2025-03-10 12:17:16', '2025-03-10 12:17:16', '0000-00-00 00:00:00', 1, 1),
(30, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-11 11:27:38', '2025-03-11 11:27:38', '2025-03-11 11:27:38', '0000-00-00 00:00:00', 1, 1),
(31, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-12 18:11:17', '2025-03-12 18:11:17', '2025-03-12 18:11:17', '0000-00-00 00:00:00', 1, 1),
(32, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-12 18:11:23', '2025-03-12 18:11:23', '2025-03-12 18:11:23', '0000-00-00 00:00:00', 1, 1),
(33, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 22:05:12', '2025-03-12 22:05:12', '2025-03-12 22:05:12', '0000-00-00 00:00:00', 1, 1),
(34, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-12 22:10:20', '2025-03-12 22:10:20', '2025-03-12 22:10:20', '0000-00-00 00:00:00', 1, 1),
(35, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-12 22:12:04', '2025-03-12 22:12:04', '2025-03-12 22:12:04', '0000-00-00 00:00:00', 1, 1),
(36, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-12 22:18:43', '2025-03-12 22:18:43', '2025-03-12 22:18:43', '0000-00-00 00:00:00', 1, 1),
(37, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-13 16:22:25', '2025-03-13 16:22:25', '2025-03-13 16:22:25', '0000-00-00 00:00:00', 1, 1),
(38, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-13 21:00:42', '2025-03-13 21:00:42', '2025-03-13 21:00:42', '0000-00-00 00:00:00', 1, 1),
(39, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-17 18:34:01', '2025-03-17 18:34:01', '2025-03-17 18:34:01', '0000-00-00 00:00:00', 1, 1),
(40, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-20 13:10:24', '2025-03-20 13:10:24', '2025-03-20 13:10:24', '0000-00-00 00:00:00', 1, 1),
(41, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-21 13:23:54', '2025-03-21 13:23:54', '2025-03-21 13:23:54', '0000-00-00 00:00:00', 1, 1),
(42, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-21 16:27:03', '2025-03-21 16:27:03', '2025-03-21 16:27:03', '0000-00-00 00:00:00', 1, 1),
(43, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-24 13:27:08', '2025-03-24 13:27:08', '2025-03-24 13:27:08', '0000-00-00 00:00:00', 1, 1),
(44, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 12:40:16', '2025-03-26 12:40:16', '2025-03-26 12:40:16', '0000-00-00 00:00:00', 1, 1),
(45, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 12:40:38', '2025-03-26 12:40:38', '2025-03-26 12:40:38', '0000-00-00 00:00:00', 1, 1),
(46, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 12:41:28', '2025-03-26 12:41:28', '2025-03-26 12:41:28', '0000-00-00 00:00:00', 1, 1),
(47, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-26 12:56:55', '2025-03-26 12:56:55', '2025-03-26 12:56:55', '0000-00-00 00:00:00', 1, 1),
(48, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 15:52:14', '2025-03-26 15:52:14', '2025-03-26 15:52:14', '0000-00-00 00:00:00', 1, 1),
(49, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 15:54:40', '2025-03-26 15:54:40', '2025-03-26 15:54:40', '0000-00-00 00:00:00', 1, 1),
(50, 7, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 16:00:16', '2025-03-26 16:00:16', '2025-03-26 16:00:16', '0000-00-00 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_users`
--

CREATE TABLE `cmp_users` (
  `id` int(11) NOT NULL,
  `uid` varchar(30) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_users`
--

INSERT INTO `cmp_users` (`id`, `uid`, `first_name`, `last_name`, `username`, `email`, `password`, `mobile`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, '84028eb3810f6ebd', 'Super', 'Admin', 'superadmin', 'superadmin@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 123456789, 1, 0, '2025-03-05 20:26:13', 0, '0000-00-00 00:00:00', 1),
(2, '14138c832af22a33', 'trFirst', 'trlname', 'trendsuserName', 'trendsuser@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 12345, 1, 1, '2025-03-05 20:33:37', 0, '0000-00-00 00:00:00', 1),
(6, '91ea326438682fff', 'John', 'Doe', 'ss3', 'ss3@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 1234567890, 1, 2, '2025-03-05 20:39:44', 2, '2025-03-05 20:48:25', 1),
(7, '38da3e1f4d799d68', 'selva', 'ganesh', 'selva', 'sg@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 654321234, 1, 0, '2025-03-05 20:48:14', 1, '2025-03-12 17:10:09', 1),
(8, '3bccb52aa0c3c1f7', 'trichy', 'kannan', 'trichykannan11', 'tk11@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 1234567890, 1, 2, '2025-03-06 12:23:30', 0, '0000-00-00 00:00:00', 1),
(50, '757c7cc9ccbfcebf', 'biology1', 'science', 'bsc1', 'bsrfdfc1@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 1472583691, 1, 7, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(51, '3594f7a34ddf7081', 'computer1', 'science', 'csc1', 'csfdffdfc1@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9638527412, 1, 7, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(52, '5f33d28ad03cae8e', 'biology1ds', 'sciencedsfds', 'bsc1', 'bsrfdtgrtggfc1@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 1472583691, 1, 7, '2025-03-12 11:51:54', 0, '0000-00-00 00:00:00', 0),
(53, 'fe6698698cdc32a2', 'bharathi', 'kannan', 'bharathii', 'bk@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9787671707, 1, 7, '2025-03-12 11:52:12', 0, '0000-00-00 00:00:00', 0),
(54, 'b79e9cb61db57a03', 'selva', 'ganesh', 'selvag', 'selva@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9080502010, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(55, '5b58f1db11d1af35', 'sakthi', 'vel', 'sakthi', 'sakthi@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 8248415806, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(56, '7a6ce6de2cee2b55', 'mohamed', 'Thaslim', 'thas', 'thas@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9025714445, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(57, 'e31f1b82e0c463e6', 'siva', 'r', 'siva', 'siva@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 7598568780, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(60, '06166519ff4a1925', 'computdsder1', 'sciencdsdse', 'csc1', 'csfdfftegtrrtydfc1@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9638527412, 1, 7, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(61, '98324d5858add157', 'bharathi', 'bharathi', 'bharathi', 'bg@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 7628368536, 1, 7, '2025-03-12 12:00:19', 0, '0000-00-00 00:00:00', 1),
(86, '13016d110b7356da', 'hermon', 'hs', 'hermon', 'hs@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9876543212, 1, 7, '2025-03-12 17:45:27', 0, '0000-00-00 00:00:00', 1),
(89, 'c617127abd09e858', 'selva', 'g', 'selva', 'sgz@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9876543212, 1, 7, '2025-03-12 21:20:54', 0, '0000-00-00 00:00:00', 1),
(92, '81e771331999dc1f', 'bharadfsthi', 'bharathi', 'bharathi', 'bgfdffd@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 7628368536, 1, 7, '2025-03-12 21:27:52', 0, '0000-00-00 00:00:00', 1),
(95, '1055ad74e86f787c', 'biology1ds', 'sciencedsfds', 'bsc1', 'bsc12@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 1472583691, 1, 7, '2025-03-13 17:32:08', 7, '2025-03-13 17:37:26', 1),
(96, '373b6d5b2953b37f', 'computdsder1', 'sciencdsdse', 'csc1', 'csc31@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9638527412, 1, 7, '2025-03-13 17:32:08', 7, '2025-03-13 17:37:43', 1),
(97, '782651a722f3911d', 'selvam', 'sk', 'selvam', 'selvam@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 1472583691, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(98, 'f4266c770b9c11b9', 'daniel', 'dr', 'daniel', 'daniel@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9638527412, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(99, 'beedc0d6edf4909f', 'selvaganesh', 'sk', 'selvam', 'selvaganesh@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 1472583691, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(100, '7d2a3732f1051b7a', 'danielraja', 'dr', 'daniel', 'danielraja@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9638527412, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(101, 'e84acfe092837363', 'selvaganesh', 'sk', 'selvaganesh', 'selvaganeshks@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 1472583691, 1, 7, '2025-03-14 18:37:13', 0, '0000-00-00 00:00:00', 1),
(102, '85f8606db780e8da', 'danielraja', 'dr', 'Daniel', 'danielrajadrj@example.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9638527412, 1, 7, '2025-03-14 18:37:13', 7, '2025-03-24 13:09:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_user_login_log`
--

CREATE TABLE `cmp_user_login_log` (
  `id` int(11) NOT NULL,
  `user_id` int(40) NOT NULL,
  `token` varchar(500) NOT NULL,
  `login_time` datetime NOT NULL,
  `last_active_time` datetime NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime(6) NOT NULL,
  `login_status` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_user_login_log`
--

INSERT INTO `cmp_user_login_log` (`id`, `user_id`, `token`, `login_time`, `last_active_time`, `created_date`, `updated_date`, `login_status`, `status`) VALUES
(1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-05 16:01:58', '2025-03-05 16:01:58', '2025-03-05 20:31:58', '0000-00-00 00:00:00.000000', 0, 1),
(2, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-03-05 16:03:54', '2025-03-05 16:03:54', '2025-03-05 20:33:54', '0000-00-00 00:00:00.000000', 1, 1),
(3, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-05 16:18:17', '2025-03-05 16:18:17', '2025-03-05 20:48:17', '0000-00-00 00:00:00.000000', 1, 1),
(4, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-05 16:22:33', '2025-03-05 16:22:33', '2025-03-05 20:52:33', '0000-00-00 00:00:00.000000', 0, 1),
(5, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-06 06:49:39', '2025-03-06 06:49:39', '2025-03-06 11:19:39', '0000-00-00 00:00:00.000000', 0, 1),
(6, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-06 07:18:46', '2025-03-06 07:18:46', '2025-03-06 11:48:46', '0000-00-00 00:00:00.000000', 0, 1),
(7, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-06 07:20:58', '2025-03-06 07:20:58', '2025-03-06 11:50:58', '0000-00-00 00:00:00.000000', 0, 1),
(8, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-06 07:21:16', '2025-03-06 07:21:16', '2025-03-06 11:51:16', '0000-00-00 00:00:00.000000', 0, 1),
(9, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-06 07:40:01', '2025-03-06 07:40:01', '2025-03-06 12:10:01', '0000-00-00 00:00:00.000000', 0, 1),
(10, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-03-06 08:03:06', '2025-03-06 08:03:06', '2025-03-06 12:33:06', '0000-00-00 00:00:00.000000', 1, 1),
(11, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-06 08:03:33', '2025-03-06 08:03:33', '2025-03-06 12:33:33', '0000-00-00 00:00:00.000000', 0, 1),
(12, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-06 08:27:27', '2025-03-06 08:27:27', '2025-03-06 12:57:27', '0000-00-00 00:00:00.000000', 0, 1),
(13, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-06 08:32:20', '2025-03-06 08:32:20', '2025-03-06 13:02:20', '0000-00-00 00:00:00.000000', 0, 1),
(14, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-06 08:36:22', '2025-03-06 08:36:22', '2025-03-06 13:06:22', '0000-00-00 00:00:00.000000', 0, 1),
(15, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-06 10:00:02', '2025-03-06 10:00:02', '2025-03-06 14:30:02', '0000-00-00 00:00:00.000000', 0, 1),
(16, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-06 12:22:16', '2025-03-06 12:22:16', '2025-03-06 16:52:16', '0000-00-00 00:00:00.000000', 0, 1),
(17, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-06 12:24:02', '2025-03-06 12:24:02', '2025-03-06 16:54:02', '0000-00-00 00:00:00.000000', 0, 1),
(18, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-06 14:02:39', '2025-03-06 14:02:39', '2025-03-06 18:32:39', '0000-00-00 00:00:00.000000', 0, 1),
(19, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 05:23:54', '2025-03-07 05:23:54', '2025-03-07 09:53:54', '0000-00-00 00:00:00.000000', 0, 1),
(20, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 05:47:13', '2025-03-07 05:47:13', '2025-03-07 10:17:13', '0000-00-00 00:00:00.000000', 0, 1),
(21, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 06:36:06', '2025-03-07 06:36:06', '2025-03-07 11:06:06', '0000-00-00 00:00:00.000000', 0, 1),
(22, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 06:37:47', '2025-03-07 06:37:47', '2025-03-07 11:07:47', '0000-00-00 00:00:00.000000', 0, 1),
(23, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 06:38:01', '2025-03-07 06:38:01', '2025-03-07 11:08:01', '0000-00-00 00:00:00.000000', 0, 1),
(24, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 06:43:29', '2025-03-07 06:43:29', '2025-03-07 11:13:29', '0000-00-00 00:00:00.000000', 0, 1),
(25, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 07:21:07', '2025-03-07 07:21:07', '2025-03-07 11:51:07', '0000-00-00 00:00:00.000000', 0, 1),
(26, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 07:23:28', '2025-03-07 07:23:28', '2025-03-07 11:53:28', '0000-00-00 00:00:00.000000', 0, 1),
(27, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 07:26:26', '2025-03-07 07:26:26', '2025-03-07 11:56:26', '0000-00-00 00:00:00.000000', 0, 1),
(28, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 07:27:54', '2025-03-07 07:27:54', '2025-03-07 11:57:54', '0000-00-00 00:00:00.000000', 0, 1),
(29, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 07:28:26', '2025-03-07 07:28:26', '2025-03-07 11:58:26', '0000-00-00 00:00:00.000000', 0, 1),
(30, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 07:28:36', '2025-03-07 07:28:36', '2025-03-07 11:58:36', '0000-00-00 00:00:00.000000', 0, 1),
(31, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 07:28:41', '2025-03-07 07:28:41', '2025-03-07 11:58:41', '0000-00-00 00:00:00.000000', 0, 1),
(32, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 08:16:00', '2025-03-07 08:16:00', '2025-03-07 12:46:00', '0000-00-00 00:00:00.000000', 0, 1),
(33, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 08:16:55', '2025-03-07 08:16:55', '2025-03-07 12:46:55', '0000-00-00 00:00:00.000000', 0, 1),
(34, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 08:39:04', '2025-03-07 08:39:04', '2025-03-07 13:09:04', '0000-00-00 00:00:00.000000', 0, 1),
(35, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 08:41:01', '2025-03-07 08:41:01', '2025-03-07 13:11:01', '0000-00-00 00:00:00.000000', 0, 1),
(36, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 08:41:49', '2025-03-07 08:41:49', '2025-03-07 13:11:49', '0000-00-00 00:00:00.000000', 0, 1),
(37, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 08:46:47', '2025-03-07 08:46:47', '2025-03-07 13:16:47', '0000-00-00 00:00:00.000000', 0, 1),
(38, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 08:50:24', '2025-03-07 08:50:24', '2025-03-07 13:20:24', '0000-00-00 00:00:00.000000', 0, 1),
(39, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 10:19:22', '2025-03-07 10:19:22', '2025-03-07 14:49:22', '0000-00-00 00:00:00.000000', 0, 1),
(40, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 10:35:24', '2025-03-07 10:35:24', '2025-03-07 15:05:24', '0000-00-00 00:00:00.000000', 0, 1),
(41, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 10:36:06', '2025-03-07 10:36:06', '2025-03-07 15:06:06', '0000-00-00 00:00:00.000000', 0, 1),
(42, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 10:36:47', '2025-03-07 10:36:47', '2025-03-07 15:06:47', '0000-00-00 00:00:00.000000', 0, 1),
(43, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 10:37:48', '2025-03-07 10:37:48', '2025-03-07 15:07:48', '0000-00-00 00:00:00.000000', 0, 1),
(44, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 10:38:41', '2025-03-07 10:38:41', '2025-03-07 15:08:41', '0000-00-00 00:00:00.000000', 0, 1),
(45, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 10:40:00', '2025-03-07 10:40:00', '2025-03-07 15:10:00', '0000-00-00 00:00:00.000000', 0, 1),
(46, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 10:49:11', '2025-03-07 10:49:11', '2025-03-07 15:19:11', '0000-00-00 00:00:00.000000', 0, 1),
(47, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 10:50:53', '2025-03-07 10:50:53', '2025-03-07 15:20:53', '0000-00-00 00:00:00.000000', 0, 1),
(48, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 11:03:40', '2025-03-07 11:03:40', '2025-03-07 15:33:40', '0000-00-00 00:00:00.000000', 0, 1),
(49, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 11:03:51', '2025-03-07 11:03:51', '2025-03-07 15:33:51', '0000-00-00 00:00:00.000000', 0, 1),
(50, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 11:12:03', '2025-03-07 11:12:03', '2025-03-07 15:42:03', '0000-00-00 00:00:00.000000', 0, 1),
(51, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 11:15:53', '2025-03-07 11:15:53', '2025-03-07 15:45:53', '0000-00-00 00:00:00.000000', 0, 1),
(52, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-07 11:22:13', '2025-03-07 11:22:13', '2025-03-07 15:52:13', '0000-00-00 00:00:00.000000', 0, 1),
(53, 0, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.IaqYLpoUmtzPxyr2qKEmVz6LYyjI0HVWQH560GKYhk', '2025-03-07 11:28:40', '2025-03-07 11:28:40', '2025-03-07 15:58:40', '0000-00-00 00:00:00.000000', 1, 1),
(54, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-07 11:37:58', '2025-03-07 11:37:58', '2025-03-07 16:07:58', '0000-00-00 00:00:00.000000', 0, 1),
(55, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMjUiLCJuYW1lIjpudWxsLCJleHAiOjM2MDB9.bHftmyjW8sCpj24MR8aiNNbr0NB0Sok1eiRSjY5e4', '2025-03-07 11:42:25', '2025-03-07 11:42:25', '2025-03-07 16:12:25', '0000-00-00 00:00:00.000000', 1, 1),
(56, 0, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.IaqYLpoUmtzPxyr2qKEmVz6LYyjI0HVWQH560GKYhk', '2025-03-09 18:09:15', '2025-03-09 18:09:15', '2025-03-09 22:39:15', '0000-00-00 00:00:00.000000', 1, 1),
(57, 0, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.KyWGdZ6GqadtPa6I5ouJCpCMbblpVaw7FK6yB0U1fQ', '2025-03-09 18:34:37', '2025-03-09 18:34:37', '2025-03-09 23:04:37', '0000-00-00 00:00:00.000000', 1, 1),
(58, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.KyWGdZ6GqadtPa6I5ouJCpCMbblpVaw7FK6yB0U1fQ', '2025-03-09 18:38:09', '2025-03-09 18:38:09', '2025-03-09 23:08:09', '0000-00-00 00:00:00.000000', 0, 1),
(59, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.KyWGdZ6GqadtPa6I5ouJCpCMbblpVaw7FK6yB0U1fQ', '2025-03-09 18:40:03', '2025-03-09 18:40:03', '2025-03-09 23:10:03', '0000-00-00 00:00:00.000000', 0, 1),
(60, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.KyWGdZ6GqadtPa6I5ouJCpCMbblpVaw7FK6yB0U1fQ', '2025-03-09 18:41:56', '2025-03-09 18:41:56', '2025-03-09 23:11:56', '0000-00-00 00:00:00.000000', 0, 1),
(61, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJLRkMiLCJleHAiOjM2MDB9.KyWGdZ6GqadtPa6I5ouJCpCMbblpVaw7FK6yB0U1fQ', '2025-03-09 18:41:59', '2025-03-09 18:41:59', '2025-03-09 23:11:59', '0000-00-00 00:00:00.000000', 0, 1),
(62, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 18:45:03', '2025-03-09 18:45:03', '2025-03-09 23:15:03', '0000-00-00 00:00:00.000000', 0, 1),
(63, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-09 18:46:14', '2025-03-09 18:46:14', '2025-03-09 23:16:14', '0000-00-00 00:00:00.000000', 0, 1),
(64, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-09 18:47:41', '2025-03-09 18:47:41', '2025-03-09 23:17:41', '0000-00-00 00:00:00.000000', 0, 1),
(65, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 18:47:46', '2025-03-09 18:47:46', '2025-03-09 23:17:46', '0000-00-00 00:00:00.000000', 0, 1),
(66, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-09 18:49:03', '2025-03-09 18:49:03', '2025-03-09 23:19:03', '0000-00-00 00:00:00.000000', 0, 1),
(67, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-09 18:50:14', '2025-03-09 18:50:14', '2025-03-09 23:20:14', '0000-00-00 00:00:00.000000', 0, 1),
(68, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 18:50:19', '2025-03-09 18:50:19', '2025-03-09 23:20:19', '0000-00-00 00:00:00.000000', 0, 1),
(69, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-09 19:01:29', '2025-03-09 19:01:29', '2025-03-09 23:31:29', '0000-00-00 00:00:00.000000', 0, 1),
(70, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-10 07:47:16', '2025-03-10 07:47:16', '2025-03-10 12:17:16', '0000-00-00 00:00:00.000000', 0, 1),
(71, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-11 06:57:30', '2025-03-11 06:57:30', '2025-03-11 11:27:30', '0000-00-00 00:00:00.000000', 0, 1),
(72, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-11 07:13:11', '2025-03-11 07:13:11', '2025-03-11 11:43:11', '0000-00-00 00:00:00.000000', 0, 1),
(73, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-11 08:59:55', '2025-03-11 08:59:55', '2025-03-11 13:29:55', '0000-00-00 00:00:00.000000', 0, 1),
(74, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-11 09:28:49', '2025-03-11 09:28:49', '2025-03-11 13:58:49', '0000-00-00 00:00:00.000000', 0, 1),
(75, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-11 10:02:40', '2025-03-11 10:02:40', '2025-03-11 14:32:40', '0000-00-00 00:00:00.000000', 0, 1),
(76, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-11 12:49:03', '2025-03-11 12:49:03', '2025-03-11 17:19:03', '0000-00-00 00:00:00.000000', 0, 1),
(77, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-11 12:50:37', '2025-03-11 12:50:37', '2025-03-11 17:20:37', '0000-00-00 00:00:00.000000', 0, 1),
(78, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-11 12:51:02', '2025-03-11 12:51:02', '2025-03-11 17:21:02', '0000-00-00 00:00:00.000000', 0, 1),
(79, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-11 12:51:26', '2025-03-11 12:51:26', '2025-03-11 17:21:26', '0000-00-00 00:00:00.000000', 0, 1),
(80, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-11 14:54:34', '2025-03-11 14:54:34', '2025-03-11 19:24:34', '0000-00-00 00:00:00.000000', 0, 1),
(81, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 05:53:01', '2025-03-12 05:53:01', '2025-03-12 10:23:01', '0000-00-00 00:00:00.000000', 0, 1),
(82, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 05:58:29', '2025-03-12 05:58:29', '2025-03-12 10:28:29', '0000-00-00 00:00:00.000000', 0, 1),
(83, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 06:16:12', '2025-03-12 06:16:12', '2025-03-12 10:46:12', '0000-00-00 00:00:00.000000', 0, 1),
(84, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 06:51:11', '2025-03-12 06:51:11', '2025-03-12 11:21:11', '0000-00-00 00:00:00.000000', 0, 1),
(85, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 07:32:55', '2025-03-12 07:32:55', '2025-03-12 12:02:55', '0000-00-00 00:00:00.000000', 0, 1),
(86, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 07:33:17', '2025-03-12 07:33:17', '2025-03-12 12:03:17', '0000-00-00 00:00:00.000000', 0, 1),
(87, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 13:00:28', '2025-03-12 13:00:28', '2025-03-12 17:30:28', '0000-00-00 00:00:00.000000', 0, 1),
(88, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 13:07:45', '2025-03-12 13:07:45', '2025-03-12 17:37:45', '0000-00-00 00:00:00.000000', 0, 1),
(89, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-12 13:37:49', '2025-03-12 13:37:49', '2025-03-12 18:07:49', '0000-00-00 00:00:00.000000', 0, 1),
(90, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-12 13:41:17', '2025-03-12 13:41:17', '2025-03-12 18:11:17', '0000-00-00 00:00:00.000000', 0, 1),
(91, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 14:36:35', '2025-03-12 14:36:35', '2025-03-12 19:06:35', '0000-00-00 00:00:00.000000', 0, 1),
(92, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-12 17:07:25', '2025-03-12 17:07:25', '2025-03-12 21:37:25', '0000-00-00 00:00:00.000000', 0, 1),
(93, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-12 17:08:51', '2025-03-12 17:08:51', '2025-03-12 21:38:51', '0000-00-00 00:00:00.000000', 0, 1),
(94, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-12 17:38:19', '2025-03-12 17:38:19', '2025-03-12 22:08:19', '0000-00-00 00:00:00.000000', 0, 1),
(95, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-12 17:40:20', '2025-03-12 17:40:20', '2025-03-12 22:10:20', '0000-00-00 00:00:00.000000', 0, 1),
(96, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-12 17:41:11', '2025-03-12 17:41:11', '2025-03-12 22:11:11', '0000-00-00 00:00:00.000000', 0, 1),
(97, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-12 17:42:51', '2025-03-12 17:42:51', '2025-03-12 22:12:51', '0000-00-00 00:00:00.000000', 0, 1),
(98, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-12 17:48:38', '2025-03-12 17:48:38', '2025-03-12 22:18:38', '0000-00-00 00:00:00.000000', 0, 1),
(99, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-12 17:48:43', '2025-03-12 17:48:43', '2025-03-12 22:18:43', '0000-00-00 00:00:00.000000', 0, 1),
(100, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-13 05:59:45', '2025-03-13 05:59:45', '2025-03-13 10:29:45', '0000-00-00 00:00:00.000000', 0, 1),
(101, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-13 06:22:01', '2025-03-13 06:22:01', '2025-03-13 10:52:01', '0000-00-00 00:00:00.000000', 0, 1),
(102, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-13 07:09:32', '2025-03-13 07:09:32', '2025-03-13 11:39:32', '0000-00-00 00:00:00.000000', 0, 1),
(103, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-13 07:38:13', '2025-03-13 07:38:13', '2025-03-13 12:08:13', '0000-00-00 00:00:00.000000', 0, 1),
(104, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-13 13:44:44', '2025-03-13 13:44:44', '2025-03-13 18:14:44', '0000-00-00 00:00:00.000000', 0, 1),
(105, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-13 16:28:33', '2025-03-13 16:28:33', '2025-03-13 20:58:33', '0000-00-00 00:00:00.000000', 0, 1),
(106, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-13 16:28:41', '2025-03-13 16:28:41', '2025-03-13 20:58:41', '0000-00-00 00:00:00.000000', 0, 1),
(107, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-14 06:54:29', '2025-03-14 06:54:29', '2025-03-14 11:24:29', '0000-00-00 00:00:00.000000', 0, 1),
(108, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-14 12:06:35', '2025-03-14 12:06:35', '2025-03-14 16:36:35', '0000-00-00 00:00:00.000000', 0, 1),
(109, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-14 13:46:38', '2025-03-14 13:46:38', '2025-03-14 18:16:38', '0000-00-00 00:00:00.000000', 0, 1),
(110, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-14 13:46:52', '2025-03-14 13:46:52', '2025-03-14 18:16:52', '0000-00-00 00:00:00.000000', 0, 1),
(111, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-14 13:47:00', '2025-03-14 13:47:00', '2025-03-14 18:17:00', '0000-00-00 00:00:00.000000', 0, 1),
(112, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-17 06:06:29', '2025-03-17 06:06:29', '2025-03-17 10:36:29', '0000-00-00 00:00:00.000000', 0, 1),
(113, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-17 06:08:03', '2025-03-17 06:08:03', '2025-03-17 10:38:03', '0000-00-00 00:00:00.000000', 0, 1),
(114, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-17 07:10:31', '2025-03-17 07:10:31', '2025-03-17 11:40:31', '0000-00-00 00:00:00.000000', 0, 1),
(115, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-17 07:30:15', '2025-03-17 07:30:15', '2025-03-17 12:00:15', '0000-00-00 00:00:00.000000', 0, 1),
(116, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-17 13:43:15', '2025-03-17 13:43:15', '2025-03-17 18:13:15', '0000-00-00 00:00:00.000000', 0, 1),
(117, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-18 06:09:50', '2025-03-18 06:09:50', '2025-03-18 10:39:50', '0000-00-00 00:00:00.000000', 0, 1),
(118, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-19 06:04:03', '2025-03-19 06:04:03', '2025-03-19 10:34:03', '0000-00-00 00:00:00.000000', 0, 1),
(119, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-19 06:04:11', '2025-03-19 06:04:11', '2025-03-19 10:34:11', '0000-00-00 00:00:00.000000', 0, 1),
(120, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-19 07:31:07', '2025-03-19 07:31:07', '2025-03-19 12:01:07', '0000-00-00 00:00:00.000000', 0, 1),
(121, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-19 12:44:47', '2025-03-19 12:44:47', '2025-03-19 17:14:47', '0000-00-00 00:00:00.000000', 0, 1),
(122, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-19 15:20:45', '2025-03-19 15:20:45', '2025-03-19 19:50:45', '0000-00-00 00:00:00.000000', 0, 1),
(123, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-20 05:48:53', '2025-03-20 05:48:53', '2025-03-20 10:18:53', '0000-00-00 00:00:00.000000', 0, 1),
(124, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-20 08:28:37', '2025-03-20 08:28:37', '2025-03-20 12:58:37', '0000-00-00 00:00:00.000000', 0, 1),
(125, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-20 08:39:08', '2025-03-20 08:39:08', '2025-03-20 13:09:08', '0000-00-00 00:00:00.000000', 0, 1),
(126, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-21 07:44:05', '2025-03-21 07:44:05', '2025-03-21 12:14:05', '0000-00-00 00:00:00.000000', 0, 1),
(127, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-21 11:43:10', '2025-03-21 11:43:10', '2025-03-21 16:13:10', '0000-00-00 00:00:00.000000', 0, 1),
(128, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-21 11:58:14', '2025-03-21 11:58:14', '2025-03-21 16:28:14', '0000-00-00 00:00:00.000000', 0, 1),
(129, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-24 08:25:28', '2025-03-24 08:25:28', '2025-03-24 12:55:28', '0000-00-00 00:00:00.000000', 0, 1),
(130, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-24 08:56:00', '2025-03-24 08:56:00', '2025-03-24 13:26:00', '0000-00-00 00:00:00.000000', 0, 1),
(131, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-24 10:29:50', '2025-03-24 10:29:50', '2025-03-24 14:59:50', '0000-00-00 00:00:00.000000', 0, 1),
(132, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-25 07:05:59', '2025-03-25 07:05:59', '2025-03-25 11:35:59', '0000-00-00 00:00:00.000000', 0, 1),
(133, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-25 16:23:32', '2025-03-25 16:23:32', '2025-03-25 20:53:32', '0000-00-00 00:00:00.000000', 0, 1),
(134, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-25 16:26:16', '2025-03-25 16:26:16', '2025-03-25 20:56:16', '0000-00-00 00:00:00.000000', 0, 1),
(135, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-25 17:07:47', '2025-03-25 17:07:47', '2025-03-25 21:37:47', '0000-00-00 00:00:00.000000', 0, 1),
(136, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 05:21:15', '2025-03-26 05:21:15', '2025-03-26 09:51:15', '0000-00-00 00:00:00.000000', 0, 1),
(137, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 06:01:40', '2025-03-26 06:01:40', '2025-03-26 10:31:40', '0000-00-00 00:00:00.000000', 0, 1),
(138, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 07:43:32', '2025-03-26 07:43:32', '2025-03-26 12:13:32', '0000-00-00 00:00:00.000000', 0, 1),
(139, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 08:06:34', '2025-03-26 08:06:34', '2025-03-26 12:36:34', '0000-00-00 00:00:00.000000', 0, 1),
(140, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-26 08:08:36', '2025-03-26 08:08:36', '2025-03-26 12:38:36', '0000-00-00 00:00:00.000000', 0, 1),
(141, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-26 08:10:06', '2025-03-26 08:10:06', '2025-03-26 12:40:06', '0000-00-00 00:00:00.000000', 1, 1),
(142, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 08:17:15', '2025-03-26 08:17:15', '2025-03-26 12:47:15', '0000-00-00 00:00:00.000000', 0, 1),
(143, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-26 08:19:11', '2025-03-26 08:19:11', '2025-03-26 12:49:11', '0000-00-00 00:00:00.000000', 1, 1),
(144, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOiJzZWx2YSIsImV4cCI6MzYwMH0.2BTsEnk9WMpyPxQo5WydeAL70YAFbddnebjxbEGNMZc', '2025-03-26 08:26:55', '2025-03-26 08:26:55', '2025-03-26 12:56:55', '0000-00-00 00:00:00.000000', 1, 1),
(145, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 08:44:18', '2025-03-26 08:44:18', '2025-03-26 13:14:18', '0000-00-00 00:00:00.000000', 1, 1),
(146, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 08:44:35', '2025-03-26 08:44:35', '2025-03-26 13:14:35', '0000-00-00 00:00:00.000000', 1, 1),
(147, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 08:46:14', '2025-03-26 08:46:14', '2025-03-26 13:16:14', '0000-00-00 00:00:00.000000', 1, 1),
(148, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 09:16:59', '2025-03-26 09:16:59', '2025-03-26 13:46:59', '0000-00-00 00:00:00.000000', 1, 1),
(149, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 12:49:00', '2025-03-26 12:49:00', '2025-03-26 17:19:00', '0000-00-00 00:00:00.000000', 1, 1),
(150, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 14:47:51', '2025-03-26 14:47:51', '2025-03-26 19:17:51', '0000-00-00 00:00:00.000000', 1, 1),
(151, 7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6n5Whsz2IWGkjAuqiIozzs7nZxLA1AVYROMIP4m3o', '2025-03-26 16:25:16', '2025-03-26 16:25:16', '2025-03-26 20:55:16', '0000-00-00 00:00:00.000000', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_user_privilege_mapping`
--

CREATE TABLE `cmp_user_privilege_mapping` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `privilege_id` int(11) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `mapping_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_user_privilege_mapping`
--

INSERT INTO `cmp_user_privilege_mapping` (`id`, `user_id`, `privilege_id`, `active_status`, `mapping_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 5, 1, 1, 1, 2, '2025-03-05 20:39:28', 0, '0000-00-00 00:00:00', 1),
(2, 6, 1, 1, 1, 2, '2025-03-05 20:39:44', 2, '2025-03-05 20:47:31', 0),
(3, 6, 2, 1, 1, 2, '2025-03-05 20:47:31', 2, '2025-03-05 20:48:25', 1),
(4, 6, 1, 1, 1, 2, '2025-03-05 20:48:25', 0, '0000-00-00 00:00:00', 1),
(5, 8, 1, 1, 1, 2, '2025-03-06 12:23:31', 0, '0000-00-00 00:00:00', 1),
(6, 9, 1, 1, 1, 7, '2025-03-06 13:35:57', 0, '0000-00-00 00:00:00', 0),
(7, 10, 1, 1, 1, 7, '2025-03-06 13:36:36', 7, '2025-03-06 13:37:18', 0),
(8, 11, 1, 1, 1, 7, '2025-03-06 13:38:24', 0, '0000-00-00 00:00:00', 1),
(9, 12, 1, 1, 1, 7, '2025-03-06 13:59:22', 0, '0000-00-00 00:00:00', 1),
(10, 13, 1, 1, 1, 7, '2025-03-06 14:19:29', 0, '0000-00-00 00:00:00', 1),
(11, 14, 1, 1, 1, 7, '2025-03-06 14:20:13', 0, '0000-00-00 00:00:00', 1),
(12, 15, 1, 1, 1, 7, '2025-03-06 14:23:04', 7, '2025-03-06 16:03:52', 1),
(13, 17, 1, 1, 1, 7, '2025-03-06 14:23:33', 7, '2025-03-06 15:36:13', 1),
(14, 18, 1, 1, 1, 7, '2025-03-06 15:34:28', 7, '2025-03-06 18:55:17', 0),
(15, 19, 1, 1, 1, 7, '2025-03-06 15:59:11', 7, '2025-03-06 18:54:53', 0),
(16, 20, 1, 1, 1, 7, '2025-03-06 16:22:58', 7, '2025-03-06 16:43:47', 1),
(17, 22, 1, 1, 1, 7, '2025-03-06 16:55:45', 7, '2025-03-06 16:56:18', 1),
(18, 23, 1, 1, 1, 7, '2025-03-06 18:52:38', 0, '0000-00-00 00:00:00', 1),
(19, 24, 1, 1, 1, 7, '2025-03-06 18:53:37', 7, '2025-03-07 11:06:39', 1),
(20, 6, 1, 1, 1, 2, '2025-03-10 17:19:04', 0, '0000-00-00 00:00:00', 1),
(21, 8, 1, 1, 1, 2, '2025-03-10 17:19:04', 0, '0000-00-00 00:00:00', 1),
(22, 8, 2, 1, 1, 2, '2025-03-10 17:19:04', 0, '0000-00-00 00:00:00', 1),
(23, 27, 1, 1, 1, 2, '2025-03-10 17:21:33', 0, '0000-00-00 00:00:00', 1),
(24, 28, 1, 1, 1, 2, '2025-03-10 17:21:33', 0, '0000-00-00 00:00:00', 1),
(48, 47, 1, 1, 1, 7, '2025-03-12 11:48:39', 0, '0000-00-00 00:00:00', 0),
(49, 47, 1, 1, 1, 7, '2025-03-12 11:48:42', 0, '0000-00-00 00:00:00', 0),
(50, 50, 1, 1, 1, 7, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(51, 51, 1, 1, 1, 7, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(52, 51, 2, 1, 1, 7, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(53, 50, 1, 1, 1, 7, '2025-03-12 11:53:08', 0, '0000-00-00 00:00:00', 0),
(54, 51, 1, 1, 1, 7, '2025-03-12 11:53:08', 0, '0000-00-00 00:00:00', 0),
(55, 51, 2, 1, 1, 7, '2025-03-12 11:53:08', 0, '0000-00-00 00:00:00', 0),
(56, 53, 1, 1, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(57, 54, 1, 1, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(58, 54, 2, 1, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(59, 55, 1, 1, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(60, 55, 2, 1, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(61, 56, 1, 1, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(62, 57, 1, 1, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(63, 57, 2, 1, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(64, 52, 1, 1, 1, 7, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(65, 60, 1, 1, 1, 7, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(66, 60, 2, 1, 1, 7, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(67, 52, 1, 1, 1, 7, '2025-03-12 11:59:14', 0, '0000-00-00 00:00:00', 0),
(68, 60, 1, 1, 1, 7, '2025-03-12 11:59:14', 0, '0000-00-00 00:00:00', 0),
(69, 60, 2, 1, 1, 7, '2025-03-12 11:59:14', 0, '0000-00-00 00:00:00', 0),
(70, 61, 1, 1, 1, 7, '2025-03-12 12:00:19', 0, '0000-00-00 00:00:00', 1),
(71, 86, 1, 1, 1, 7, '2025-03-12 17:45:27', 0, '0000-00-00 00:00:00', 1),
(72, 89, 1, 1, 1, 7, '2025-03-12 21:20:54', 0, '0000-00-00 00:00:00', 1),
(73, 95, 1, 1, 1, 7, '2025-03-13 17:32:08', 7, '2025-03-13 17:37:26', 1),
(74, 96, 1, 1, 1, 7, '2025-03-13 17:32:08', 7, '2025-03-13 17:37:43', 1),
(75, 96, 2, 1, 1, 7, '2025-03-13 17:32:08', 7, '2025-03-13 17:37:43', 0),
(76, 97, 1, 1, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(77, 98, 1, 1, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(78, 98, 2, 1, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(79, 99, 1, 1, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(80, 100, 1, 1, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(81, 100, 2, 1, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(82, 101, 1, 1, 1, 7, '2025-03-14 18:37:13', 0, '0000-00-00 00:00:00', 1),
(83, 102, 1, 1, 1, 7, '2025-03-14 18:37:13', 7, '2025-03-24 13:09:22', 1),
(84, 102, 2, 1, 1, 7, '2025-03-14 18:37:13', 7, '2025-03-24 13:09:22', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_user_role_mapping`
--

CREATE TABLE `cmp_user_role_mapping` (
  `id` int(11) NOT NULL,
  `user_id` int(40) NOT NULL,
  `role_id` int(40) NOT NULL,
  `mapping_status` int(40) NOT NULL DEFAULT 1,
  `created_by` int(20) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(20) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_user_role_mapping`
--

INSERT INTO `cmp_user_role_mapping` (`id`, `user_id`, `role_id`, `mapping_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 1, 1, 1, 0, '2025-03-05 20:31:22', 0, '0000-00-00 00:00:00', 1),
(2, 2, 2, 1, 1, '2025-03-05 20:33:37', 0, '0000-00-00 00:00:00', 1),
(3, 3, 4, 1, 2, '2025-03-05 20:35:22', 0, '0000-00-00 00:00:00', 1),
(4, 5, 4, 1, 2, '2025-03-05 20:39:28', 0, '0000-00-00 00:00:00', 1),
(5, 6, 4, 1, 2, '2025-03-05 20:39:44', 0, '0000-00-00 00:00:00', 1),
(6, 7, 2, 1, 7, '2025-03-05 20:48:14', 0, '0000-00-00 00:00:00', 1),
(7, 8, 4, 1, 2, '2025-03-06 12:23:30', 0, '0000-00-00 00:00:00', 1),
(8, 9, 4, 1, 7, '2025-03-06 12:24:44', 0, '0000-00-00 00:00:00', 0),
(9, 10, 4, 1, 7, '2025-03-06 13:07:27', 0, '0000-00-00 00:00:00', 0),
(10, 11, 4, 1, 7, '2025-03-06 13:38:24', 0, '0000-00-00 00:00:00', 1),
(11, 12, 4, 1, 7, '2025-03-06 13:59:22', 0, '0000-00-00 00:00:00', 1),
(12, 13, 4, 1, 7, '2025-03-06 14:19:29', 0, '0000-00-00 00:00:00', 1),
(13, 14, 4, 1, 7, '2025-03-06 14:20:13', 0, '0000-00-00 00:00:00', 1),
(14, 15, 4, 1, 7, '2025-03-06 14:23:04', 0, '0000-00-00 00:00:00', 1),
(15, 17, 4, 1, 7, '2025-03-06 14:23:33', 0, '0000-00-00 00:00:00', 1),
(16, 18, 4, 1, 7, '2025-03-06 15:34:28', 0, '0000-00-00 00:00:00', 0),
(17, 19, 4, 1, 7, '2025-03-06 15:59:11', 0, '0000-00-00 00:00:00', 0),
(18, 20, 4, 1, 7, '2025-03-06 16:22:58', 0, '0000-00-00 00:00:00', 1),
(19, 22, 4, 1, 7, '2025-03-06 16:55:45', 0, '0000-00-00 00:00:00', 1),
(20, 23, 4, 1, 7, '2025-03-06 18:52:38', 0, '0000-00-00 00:00:00', 1),
(21, 24, 4, 1, 7, '2025-03-06 18:53:37', 0, '0000-00-00 00:00:00', 1),
(22, 25, 2, 1, 25, '2025-03-07 16:12:17', 0, '0000-00-00 00:00:00', 1),
(23, 26, 2, 1, 26, '2025-03-09 23:01:22', 0, '0000-00-00 00:00:00', 1),
(24, 6, 4, 1, 2, '2025-03-10 17:17:14', 0, '0000-00-00 00:00:00', 1),
(25, 6, 4, 1, 2, '2025-03-10 17:18:43', 0, '0000-00-00 00:00:00', 1),
(26, 6, 4, 1, 2, '2025-03-10 17:19:04', 0, '0000-00-00 00:00:00', 1),
(27, 8, 4, 1, 2, '2025-03-10 17:19:04', 0, '0000-00-00 00:00:00', 1),
(28, 27, 4, 1, 2, '2025-03-10 17:21:33', 0, '0000-00-00 00:00:00', 1),
(29, 28, 4, 1, 2, '2025-03-10 17:21:33', 0, '0000-00-00 00:00:00', 1),
(30, 27, 4, 1, 2, '2025-03-10 17:27:17', 0, '0000-00-00 00:00:00', 1),
(31, 27, 4, 1, 2, '2025-03-10 17:27:49', 0, '0000-00-00 00:00:00', 1),
(32, 28, 4, 1, 2, '2025-03-10 17:27:49', 0, '0000-00-00 00:00:00', 1),
(33, 29, 4, 1, 2, '2025-03-10 17:28:40', 0, '0000-00-00 00:00:00', 1),
(34, 30, 4, 1, 2, '2025-03-10 17:28:40', 0, '0000-00-00 00:00:00', 1),
(35, 32, 4, 1, 7, '2025-03-11 17:22:15', 0, '0000-00-00 00:00:00', 1),
(36, 33, 4, 1, 7, '2025-03-11 17:24:03', 0, '0000-00-00 00:00:00', 1),
(37, 34, 4, 1, 7, '2025-03-11 17:27:43', 0, '0000-00-00 00:00:00', 1),
(38, 34, 4, 1, 7, '2025-03-11 17:28:44', 0, '0000-00-00 00:00:00', 1),
(39, 34, 4, 1, 7, '2025-03-11 17:30:13', 0, '0000-00-00 00:00:00', 1),
(40, 35, 4, 1, 7, '2025-03-11 17:30:13', 0, '0000-00-00 00:00:00', 0),
(41, 34, 4, 1, 7, '2025-03-12 10:29:34', 0, '0000-00-00 00:00:00', 1),
(42, 35, 4, 1, 7, '2025-03-12 10:29:34', 0, '0000-00-00 00:00:00', 0),
(43, 36, 4, 1, 7, '2025-03-12 11:00:57', 0, '0000-00-00 00:00:00', 1),
(44, 37, 4, 1, 7, '2025-03-12 11:22:09', 0, '0000-00-00 00:00:00', 0),
(45, 38, 4, 1, 7, '2025-03-12 11:22:09', 0, '0000-00-00 00:00:00', 0),
(46, 39, 4, 1, 7, '2025-03-12 11:23:40', 0, '0000-00-00 00:00:00', 0),
(47, 40, 4, 1, 7, '2025-03-12 11:23:40', 0, '0000-00-00 00:00:00', 0),
(48, 37, 4, 1, 7, '2025-03-12 11:25:01', 0, '0000-00-00 00:00:00', 0),
(49, 38, 4, 1, 7, '2025-03-12 11:25:01', 0, '0000-00-00 00:00:00', 0),
(50, 47, 4, 1, 7, '2025-03-12 11:48:39', 0, '0000-00-00 00:00:00', 0),
(51, 47, 4, 1, 7, '2025-03-12 11:48:42', 0, '0000-00-00 00:00:00', 0),
(52, 50, 4, 1, 7, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(53, 51, 4, 1, 7, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(54, 52, 4, 1, 7, '2025-03-12 11:51:54', 0, '0000-00-00 00:00:00', 0),
(55, 53, 4, 1, 7, '2025-03-12 11:52:12', 0, '0000-00-00 00:00:00', 0),
(56, 53, 4, 1, 7, '2025-03-12 11:52:15', 0, '0000-00-00 00:00:00', 0),
(57, 50, 4, 1, 7, '2025-03-12 11:53:08', 0, '0000-00-00 00:00:00', 0),
(58, 51, 4, 1, 7, '2025-03-12 11:53:08', 0, '0000-00-00 00:00:00', 0),
(59, 53, 4, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(60, 54, 4, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(61, 55, 4, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(62, 56, 4, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(63, 57, 4, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(64, 52, 4, 1, 7, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(65, 60, 4, 1, 7, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(66, 52, 4, 1, 7, '2025-03-12 11:59:14', 0, '0000-00-00 00:00:00', 0),
(67, 60, 4, 1, 7, '2025-03-12 11:59:14', 0, '0000-00-00 00:00:00', 0),
(68, 61, 4, 1, 7, '2025-03-12 12:00:19', 0, '0000-00-00 00:00:00', 1),
(69, 86, 4, 1, 7, '2025-03-12 17:45:27', 0, '0000-00-00 00:00:00', 1),
(70, 89, 4, 1, 7, '2025-03-12 21:20:54', 0, '0000-00-00 00:00:00', 1),
(71, 92, 4, 1, 7, '2025-03-12 21:27:52', 0, '0000-00-00 00:00:00', 1),
(72, 95, 4, 1, 7, '2025-03-13 17:32:08', 0, '0000-00-00 00:00:00', 1),
(73, 96, 4, 1, 7, '2025-03-13 17:32:08', 0, '0000-00-00 00:00:00', 1),
(74, 97, 4, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(75, 98, 4, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(76, 99, 4, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(77, 100, 4, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(78, 101, 4, 1, 7, '2025-03-14 18:37:13', 0, '0000-00-00 00:00:00', 1),
(79, 102, 4, 1, 7, '2025-03-14 18:37:13', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_vendor`
--

CREATE TABLE `cmp_vendor` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phone` bigint(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_vendor`
--

INSERT INTO `cmp_vendor` (`id`, `uid`, `name`, `type`, `address`, `phone`, `email`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 'd85d45882ec166f4', 'trends', 'purchaseservice', 'perungalathur,chennai', 123456789, 'trends@gmail.com', 1, 1, '2025-03-05 16:03:37', 0, '0000-00-00 00:00:00', 1),
(2, 'fc8993c542dcc97e', 'KFC', 'Resort', 'Chennai', 91876543212, 'sg@gmail.com', 1, 7, '2025-03-05 16:18:14', 1, '2025-03-12 17:10:09', 1),
(3, '13ffa0daab7de742', 'ANS', 'IT', 'Chennai', 987654321234, 'hssz@gmail.com', 1, 25, '2025-03-07 11:42:17', 0, '0000-00-00 00:00:00', 1),
(4, '79a91df0e080c176', 'Namnadu', 'Namnadu', 'Namnadu,chennai', 123456789, 'Namnadu@gmail.com', 1, 26, '2025-03-09 18:31:22', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_vendor_fb_credentials`
--

CREATE TABLE `cmp_vendor_fb_credentials` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `vendor_id` bigint(20) NOT NULL,
  `app_id` varchar(45) NOT NULL,
  `phone_no_id` varchar(45) NOT NULL,
  `whatsapp_business_acc_id` varchar(45) NOT NULL,
  `access_token` longtext NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_vendor_fb_credentials`
--

INSERT INTO `cmp_vendor_fb_credentials` (`id`, `uid`, `vendor_id`, `app_id`, `phone_no_id`, `whatsapp_business_acc_id`, `access_token`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, '', 1, '4019595151630893', '637421792778479', '667307839582149', 'EAA5HzO0nYi0BOZBaf6zlgEBEJCcT5lZAUOiAZCnobKiUkOeDdnLjxcyGNSlivoMsvSv9ZBY73gwSIyOx4rcOZAZBo0Hwtj0JbotvN7CxfyOsOZBZBnEZBg8KTtfGoBl1M0qnkH4e1sj83qzZCkVu3h3U1jqaysQvPXxF2OFZBP4mSWBYmg3PlJ9tzSZBVz07tFnr7yOrZCgZDZD', 1, '2025-03-12 10:36:14', 0, '2025-03-12 10:35:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_vendor_store_mapping`
--

CREATE TABLE `cmp_vendor_store_mapping` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `mapping_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_vendor_store_mapping`
--

INSERT INTO `cmp_vendor_store_mapping` (`id`, `vendor_id`, `store_id`, `mapping_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 1, 1, 1, 2, '2025-03-05 20:34:36', 0, '0000-00-00 00:00:00', 1),
(2, 2, 2, 1, 1, '2025-03-05 20:50:39', 0, '0000-00-00 00:00:00', 1),
(3, 2, 3, 1, 7, '2025-03-05 20:53:30', 0, '0000-00-00 00:00:00', 1),
(4, 2, 4, 1, 7, '2025-03-06 12:35:16', 0, '0000-00-00 00:00:00', 1),
(5, 2, 5, 1, 7, '2025-03-06 16:21:27', 0, '0000-00-00 00:00:00', 1),
(6, 2, 6, 1, 7, '2025-03-06 16:54:58', 0, '0000-00-00 00:00:00', 1),
(7, 2, 7, 1, 7, '2025-03-06 18:51:59', 0, '0000-00-00 00:00:00', 1),
(8, 2, 8, 1, 7, '2025-03-06 19:15:31', 0, '0000-00-00 00:00:00', 1),
(9, 2, 9, 1, 7, '2025-03-07 15:12:13', 0, '0000-00-00 00:00:00', 1),
(10, 3, 10, 1, 25, '2025-03-07 16:12:49', 0, '0000-00-00 00:00:00', 1),
(11, 1, 11, 1, 0, '2025-03-10 17:51:48', 0, '0000-00-00 00:00:00', 1),
(12, 1, 13, 1, 0, '2025-03-10 17:53:39', 0, '0000-00-00 00:00:00', 1),
(13, 1, 15, 1, 0, '2025-03-10 17:54:05', 0, '0000-00-00 00:00:00', 1),
(14, 2, 16, 1, 0, '2025-03-14 18:46:38', 0, '0000-00-00 00:00:00', 1),
(15, 2, 17, 1, 7, '2025-03-21 13:14:09', 0, '0000-00-00 00:00:00', 1),
(16, 2, 18, 1, 7, '2025-03-21 13:19:22', 0, '0000-00-00 00:00:00', 1),
(17, 2, 19, 1, 7, '2025-03-24 13:18:35', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_vendor_store_staff_mapping`
--

CREATE TABLE `cmp_vendor_store_staff_mapping` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `mapping_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_vendor_store_staff_mapping`
--

INSERT INTO `cmp_vendor_store_staff_mapping` (`id`, `vendor_id`, `store_id`, `staff_id`, `mapping_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 1, 1, 3, 1, 2, '2025-03-05 20:35:22', 0, '0000-00-00 00:00:00', 1),
(2, 1, 1, 5, 1, 2, '2025-03-05 20:39:28', 0, '0000-00-00 00:00:00', 1),
(3, 1, 1, 6, 1, 2, '2025-03-05 20:39:44', 2, '2025-03-05 20:48:25', 1),
(4, 1, 1, 8, 1, 2, '2025-03-06 12:23:30', 0, '0000-00-00 00:00:00', 1),
(5, 2, 1, 9, 1, 7, '2025-03-06 12:24:44', 7, '2025-03-06 13:35:57', 0),
(6, 2, 1, 10, 1, 7, '2025-03-06 13:07:27', 7, '2025-03-06 13:37:18', 0),
(7, 2, 1, 11, 1, 7, '2025-03-06 13:38:24', 0, '0000-00-00 00:00:00', 1),
(8, 2, 1, 12, 1, 7, '2025-03-06 13:59:22', 0, '0000-00-00 00:00:00', 1),
(9, 2, 1, 13, 1, 7, '2025-03-06 14:19:29', 0, '0000-00-00 00:00:00', 1),
(10, 2, 2, 14, 1, 7, '2025-03-06 14:20:13', 0, '0000-00-00 00:00:00', 1),
(11, 2, 2, 15, 1, 7, '2025-03-06 14:23:04', 7, '2025-03-06 16:03:52', 1),
(12, 2, 3, 17, 1, 7, '2025-03-06 14:23:33', 7, '2025-03-06 15:36:13', 1),
(13, 2, 5, 18, 1, 7, '2025-03-06 15:34:28', 7, '2025-03-06 18:55:17', 0),
(14, 2, 4, 19, 1, 7, '2025-03-06 15:59:11', 7, '2025-03-06 18:54:53', 0),
(15, 2, 4, 20, 1, 7, '2025-03-06 16:22:58', 7, '2025-03-06 16:43:47', 1),
(16, 2, 6, 22, 1, 7, '2025-03-06 16:55:45', 7, '2025-03-06 16:56:18', 1),
(17, 2, 7, 23, 1, 7, '2025-03-06 18:52:38', 0, '0000-00-00 00:00:00', 1),
(18, 2, 8, 24, 1, 7, '2025-03-06 18:53:37', 7, '2025-03-07 11:06:39', 1),
(19, 1, 1, 6, 1, 0, '2025-03-10 17:17:14', 0, '0000-00-00 00:00:00', 1),
(20, 1, 1, 6, 1, 0, '2025-03-10 17:18:43', 0, '0000-00-00 00:00:00', 1),
(21, 1, 1, 6, 1, 0, '2025-03-10 17:19:04', 0, '0000-00-00 00:00:00', 1),
(41, 2, 4, 47, 1, 0, '2025-03-12 11:48:39', 0, '0000-00-00 00:00:00', 0),
(42, 2, 4, 47, 1, 0, '2025-03-12 11:48:42', 0, '0000-00-00 00:00:00', 0),
(43, 2, 4, 50, 1, 0, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(44, 2, 5, 51, 1, 0, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(45, 2, 4, 50, 1, 7, '2025-03-12 11:53:08', 0, '0000-00-00 00:00:00', 0),
(46, 2, 5, 51, 1, 7, '2025-03-12 11:53:08', 0, '0000-00-00 00:00:00', 0),
(47, 0, 2, 53, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(48, 0, 2, 54, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(49, 0, 2, 55, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(50, 0, 2, 56, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(51, 0, 2, 57, 1, 7, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(52, 2, 4, 52, 1, 7, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(53, 2, 5, 60, 1, 7, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(54, 2, 4, 52, 1, 7, '2025-03-12 11:59:14', 0, '0000-00-00 00:00:00', 0),
(55, 2, 5, 60, 1, 7, '2025-03-12 11:59:14', 0, '0000-00-00 00:00:00', 0),
(56, 2, 5, 61, 1, 7, '2025-03-12 12:00:19', 0, '0000-00-00 00:00:00', 1),
(57, 2, 2, 86, 1, 7, '2025-03-12 17:45:27', 0, '0000-00-00 00:00:00', 1),
(58, 2, 4, 89, 1, 7, '2025-03-12 21:20:54', 0, '0000-00-00 00:00:00', 1),
(59, 2, 5, 92, 1, 7, '2025-03-12 21:27:52', 0, '0000-00-00 00:00:00', 1),
(60, 2, 4, 95, 1, 7, '2025-03-13 17:32:08', 7, '2025-03-13 17:37:26', 1),
(61, 2, 5, 96, 1, 7, '2025-03-13 17:32:08', 7, '2025-03-13 17:37:43', 1),
(62, 2, 4, 97, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(63, 2, 5, 98, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(64, 2, 4, 99, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(65, 2, 5, 100, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(66, 2, 4, 101, 1, 7, '2025-03-14 18:37:13', 0, '0000-00-00 00:00:00', 1),
(67, 2, 5, 102, 1, 7, '2025-03-14 18:37:13', 7, '2025-03-24 13:09:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_vendor_user_mapping`
--

CREATE TABLE `cmp_vendor_user_mapping` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `mapping_status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_vendor_user_mapping`
--

INSERT INTO `cmp_vendor_user_mapping` (`id`, `user_id`, `vendor_id`, `mapping_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 2, 1, 1, 1, '2025-03-05 20:33:37', 0, '0000-00-00 00:00:00', 1),
(2, 3, 1, 1, 0, '2025-03-05 20:35:22', 0, '0000-00-00 00:00:00', 1),
(3, 5, 1, 1, 0, '2025-03-05 20:39:28', 0, '0000-00-00 00:00:00', 1),
(4, 6, 1, 1, 0, '2025-03-05 20:39:44', 0, '0000-00-00 00:00:00', 1),
(5, 7, 2, 1, 7, '2025-03-05 20:48:14', 0, '0000-00-00 00:00:00', 1),
(6, 8, 1, 1, 0, '2025-03-06 12:23:31', 0, '0000-00-00 00:00:00', 1),
(7, 9, 2, 1, 0, '2025-03-06 12:24:44', 0, '0000-00-00 00:00:00', 0),
(8, 10, 2, 1, 0, '2025-03-06 13:07:27', 0, '0000-00-00 00:00:00', 0),
(9, 11, 2, 1, 0, '2025-03-06 13:38:24', 0, '0000-00-00 00:00:00', 1),
(10, 12, 2, 1, 0, '2025-03-06 13:59:22', 0, '0000-00-00 00:00:00', 1),
(11, 13, 2, 1, 0, '2025-03-06 14:19:29', 0, '0000-00-00 00:00:00', 1),
(12, 14, 2, 1, 0, '2025-03-06 14:20:13', 0, '0000-00-00 00:00:00', 1),
(13, 15, 2, 1, 0, '2025-03-06 14:23:04', 0, '0000-00-00 00:00:00', 1),
(14, 17, 2, 1, 0, '2025-03-06 14:23:33', 0, '0000-00-00 00:00:00', 1),
(15, 18, 2, 1, 0, '2025-03-06 15:34:28', 0, '0000-00-00 00:00:00', 0),
(16, 19, 2, 1, 0, '2025-03-06 15:59:11', 0, '0000-00-00 00:00:00', 0),
(17, 20, 2, 1, 0, '2025-03-06 16:22:58', 0, '0000-00-00 00:00:00', 1),
(18, 22, 2, 1, 0, '2025-03-06 16:55:45', 0, '0000-00-00 00:00:00', 1),
(19, 23, 2, 1, 0, '2025-03-06 18:52:38', 0, '0000-00-00 00:00:00', 1),
(20, 24, 2, 1, 0, '2025-03-06 18:53:37', 0, '0000-00-00 00:00:00', 1),
(21, 25, 3, 1, 25, '2025-03-07 16:12:17', 0, '0000-00-00 00:00:00', 1),
(22, 26, 4, 1, 26, '2025-03-09 23:01:22', 0, '0000-00-00 00:00:00', 1),
(23, 6, 1, 1, 0, '2025-03-10 17:17:14', 0, '0000-00-00 00:00:00', 1),
(24, 6, 1, 1, 0, '2025-03-10 17:18:43', 0, '0000-00-00 00:00:00', 1),
(25, 6, 1, 1, 0, '2025-03-10 17:19:04', 0, '0000-00-00 00:00:00', 1),
(26, 8, 1, 1, 0, '2025-03-10 17:19:04', 0, '0000-00-00 00:00:00', 1),
(27, 27, 1, 1, 0, '2025-03-10 17:21:33', 0, '0000-00-00 00:00:00', 1),
(28, 28, 1, 1, 0, '2025-03-10 17:21:33', 0, '0000-00-00 00:00:00', 1),
(29, 27, 1, 1, 0, '2025-03-10 17:27:17', 0, '0000-00-00 00:00:00', 1),
(30, 27, 1, 1, 0, '2025-03-10 17:27:49', 0, '0000-00-00 00:00:00', 1),
(31, 28, 1, 1, 0, '2025-03-10 17:27:49', 0, '0000-00-00 00:00:00', 1),
(32, 29, 1, 1, 0, '2025-03-10 17:28:40', 0, '0000-00-00 00:00:00', 1),
(33, 30, 1, 1, 0, '2025-03-10 17:28:40', 0, '0000-00-00 00:00:00', 1),
(34, 34, 2, 1, 0, '2025-03-11 17:30:13', 0, '0000-00-00 00:00:00', 1),
(35, 35, 2, 1, 0, '2025-03-11 17:30:13', 0, '0000-00-00 00:00:00', 0),
(36, 34, 2, 1, 0, '2025-03-12 10:29:34', 0, '0000-00-00 00:00:00', 1),
(37, 35, 2, 1, 0, '2025-03-12 10:29:34', 0, '0000-00-00 00:00:00', 0),
(38, 36, 2, 1, 0, '2025-03-12 11:00:57', 0, '0000-00-00 00:00:00', 1),
(39, 37, 2, 1, 0, '2025-03-12 11:22:09', 0, '0000-00-00 00:00:00', 0),
(40, 38, 2, 1, 0, '2025-03-12 11:22:09', 0, '0000-00-00 00:00:00', 0),
(41, 39, 2, 1, 0, '2025-03-12 11:23:40', 0, '0000-00-00 00:00:00', 0),
(42, 40, 2, 1, 0, '2025-03-12 11:23:40', 0, '0000-00-00 00:00:00', 0),
(43, 37, 2, 1, 0, '2025-03-12 11:25:01', 0, '0000-00-00 00:00:00', 0),
(44, 38, 2, 1, 0, '2025-03-12 11:25:01', 0, '0000-00-00 00:00:00', 0),
(45, 47, 2, 1, 0, '2025-03-12 11:48:39', 0, '0000-00-00 00:00:00', 0),
(46, 47, 2, 1, 0, '2025-03-12 11:48:42', 0, '0000-00-00 00:00:00', 0),
(47, 50, 2, 1, 0, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(48, 51, 2, 1, 0, '2025-03-12 11:49:38', 0, '0000-00-00 00:00:00', 0),
(49, 50, 2, 1, 0, '2025-03-12 11:53:08', 0, '0000-00-00 00:00:00', 0),
(50, 51, 2, 1, 0, '2025-03-12 11:53:08', 0, '0000-00-00 00:00:00', 0),
(51, 53, 0, 1, 0, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(52, 54, 0, 1, 0, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(53, 55, 0, 1, 0, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(54, 56, 0, 1, 0, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(55, 57, 0, 1, 0, '2025-03-12 11:54:25', 0, '0000-00-00 00:00:00', 0),
(56, 52, 2, 1, 0, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(57, 60, 2, 1, 0, '2025-03-12 11:55:32', 0, '0000-00-00 00:00:00', 0),
(58, 52, 2, 1, 7, '2025-03-12 11:59:14', 0, '0000-00-00 00:00:00', 0),
(59, 60, 2, 1, 7, '2025-03-12 11:59:14', 0, '0000-00-00 00:00:00', 0),
(60, 61, 2, 1, 0, '2025-03-12 12:00:19', 0, '0000-00-00 00:00:00', 1),
(61, 86, 2, 1, 0, '2025-03-12 17:45:27', 0, '0000-00-00 00:00:00', 1),
(62, 89, 2, 1, 0, '2025-03-12 21:20:54', 0, '0000-00-00 00:00:00', 1),
(63, 92, 2, 1, 7, '2025-03-12 21:27:52', 0, '0000-00-00 00:00:00', 1),
(64, 95, 2, 1, 7, '2025-03-13 17:32:08', 0, '0000-00-00 00:00:00', 1),
(65, 96, 2, 1, 7, '2025-03-13 17:32:08', 0, '0000-00-00 00:00:00', 1),
(66, 97, 2, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(67, 98, 2, 1, 7, '2025-03-13 17:35:02', 0, '0000-00-00 00:00:00', 1),
(68, 99, 2, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(69, 100, 2, 1, 7, '2025-03-14 18:33:53', 0, '0000-00-00 00:00:00', 1),
(70, 101, 2, 1, 7, '2025-03-14 18:37:13', 0, '0000-00-00 00:00:00', 1),
(71, 102, 2, 1, 7, '2025-03-14 18:37:13', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_whatsapp_templates`
--

CREATE TABLE `cmp_whatsapp_templates` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `vendor_id` bigint(20) NOT NULL,
  `template_id` varchar(45) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `language` varchar(100) NOT NULL,
  `body_data` longtext NOT NULL,
  `template_status` varchar(60) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_whatsapp_templates`
--

INSERT INTO `cmp_whatsapp_templates` (`id`, `uid`, `vendor_id`, `template_id`, `template_name`, `category`, `language`, `body_data`, `template_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 'ee02aa36622bc739', 2, '1450442385941119', 'jio_fiber_offer', 'MARKETING', 'en_US', '{\"name\":\"jio_fiber_offer\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"image\",\"example\":{\"header_handle\":[\"4:Yml6Y29udm8tYmctbG9nby5wbmc=:aW1hZ2UvcG5n:ARZu_ZQjCvqT1zMC7_MlDbwJb8iIYKF04rJ9y1H5KYUxuCHe3sNQS-MNlFIQh2bKszdi_uSjMlw8gDVbtG2-kpPzaY4AiACSbWvOFkog2T8M_A:e:1743162773:685254847278606:769089702:ARbcpojLe1P1wUkudcU\"]}},{\"type\":\"BODY\",\"text\":\"\\ud83d\\ude14 \\u0b8f\\u0ba9\\u0bcd \\u0ba8\\u0bae\\u0bcd\\u0baa\\u0ba4\\u0bcd\\u0ba4\\u0b95\\u0bbe\\u0ba4 Wi-Fi \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd DTH \\u0b90\\u0baa\\u0bcd \\u0baa\\u0baf\\u0ba9\\u0bcd\\u0baa\\u0b9f\\u0bc1\\u0ba4\\u0bcd\\u0ba4 \\u0bb5\\u0bc7\\u0ba3\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd?\\u00a0\\n\\n\\ud83e\\udd29 JioAirFiber \\u0b87\\u0bb2\\u0bcd \\u0ba8\\u0bc0\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd \\u0baa\\u0bc6\\u0bb1\\u0bc1\\u0bb5\\u0bc0\\u0bb0\\u0bcd\\u0b95\\u0bb3\\u0bcd\\n\\u00a0800+ TV \\u0b9a\\u0bc7\\u0ba9\\u0bb2\\u0bcd\\u0b95\\u0bb3\\u0bcd | 15+ OTT \\u0b86\\u0baa\\u0bcd\\u0bb8\\u0bcd | 1 Gbps Wi-Fi\\u00a0\\n\\n\\ud83d\\udcb5 \\u0ba4\\u0bbf\\u0b9f\\u0bcd\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd \\u0bae\\u0bbe\\u0ba4\\u0ba4\\u0bcd\\u0ba4\\u0bbf\\u0bb1\\u0bcd\\u0b95\\u0bc1 \\u0bb5\\u0bc6\\u0bb1\\u0bc1\\u0bae\\u0bcd \\u20b9599 \\u0bae\\u0bc1\\u0ba4\\u0bb2\\u0bcd \\u0ba4\\u0bca\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bc1\\u0b95\\u0bbf\\u0ba9\\u0bcd\\u0bb1\\u0ba9\\n\\u2705\\ud83d\\uddd3\\ufe0f JioAirFiber \\u0b90 \\u0b87\\u0baa\\u0bcd\\u0baa\\u0bcb\\u0ba4\\u0bc7 \\u0baa\\u0ba4\\u0bbf\\u0bb5\\u0bc1 \\u0b9a\\u0bc6\\u0baf\\u0bcd\\u0ba4\\u0bc1 \\u20b91000 \\u0b95\\u0bc7\\u0bb7\\u0bcd\\u0baa\\u0bc7\\u0b95\\u0bcd\\u0b95\\u0bc8 \\u00a0\\u0baa\\u0bc6\\u0bb1\\u0bc1\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd\"},{\"type\":\"FOOTER\",\"text\":\"Have a nice day...!\"}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-24 17:23:39', 0, '0000-00-00 00:00:00', 1),
(2, '095c733a8cb42ff8', 2, '28857509373895493', 'jio_fiber_offer2', 'MARKETING', 'en_US', '{\"name\":\"jio_fiber_offer2\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"JioAirFiber Freedom Offer {{1}}\",\"example\":{\"header_text\":[\"Jio Customer\"]}},{\"type\":\"BODY\",\"text\":\"Installation \\u20b91000 FREE*\\ud83c\\udfe0 Join our family of 1Cr+ Happy Homes & enjoy:800+ TV Channels | 15+ OTT Plans | 1Gbps Wi-Fi\\u23f0 {{1}}\",\"example\":{\"body_text\":[[\"Limited Time Offer! Act now!*\"]]}},{\"type\":\"FOOTER\",\"text\":\"Have a nice day...!\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"QUICK_REPLY\",\"text\":\"click here\"},{\"type\":\"PHONE_NUMBER\",\"text\":\"call me\",\"phone_number\":\"919488793821\"}]}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-25 11:28:45', 0, '0000-00-00 00:00:00', 1),
(3, 'f9e27865ecc9b749', 2, '2408866722845845', 'jio_fiber_offer3', 'MARKETING', 'en_US', '{\"name\":\"jio_fiber_offer3\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"JioAirFiber Freedom Offer\"},{\"type\":\"BODY\",\"text\":\"\\ud83d\\udce2\\ud83d\\udcb5 Installation \\u20b91000 FREE*\\ud83c\\udfe0 Join our family of 1Cr+ Happy Homes &{{1}}\\u00a0Limited Time Offer! Act now!*\\ud83e\\udd29 JioAirFiber \\u0b87\\u0bb2\\u0bcd \\u0ba8\\u0bc0\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd \\u0baa\\u0bc6\\u0bb1\\u0bc1\\u0bb5\\u0bc0\\u0bb0\\u0bcd\\u0b95\\u0bb3\\u0bcd{{2}}\\u0ba4\\u0bbf\\u0b9f\\u0bcd\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd \\u0bae\\u0bbe\\u0ba4\\u0ba4\\u0bcd\\u0ba4\\u0bbf\\u0bb1\\u0bcd\\u0b95\\u0bc1 \\u0bb5\\u0bc6\\u0bb1\\u0bc1\\u0bae\\u0bcd{{3}}\\u0bae\\u0bc1\\u0ba4\\u0bb2\\u0bcd \\u0ba4\\u0bca\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bc1\\u0b95\\u0bbf\\u0ba9\\u0bcd\\u0bb1\\u0ba9.\\u2705\\ud83d\\uddd3\\ufe0f JioAirFiber \\u0b90 \\u0b87\\u0baa\\u0bcd\\u0baa\\u0bcb\\u0ba4\\u0bc7 \\u0baa\\u0ba4\\u0bbf\\u0bb5\\u0bc1 \\u0b9a\\u0bc6\\u0baf\\u0bcd\\u0ba4\\u0bc1 \\u20b91000 \\u0b95\\u0bc7\\u0bb7\\u0bcd\\u0baa\\u0bc7\\u0b95\\u0bcd\\u0b95\\u0bc8 \\u00a0\\u0baa\\u0bc6\\u0bb1\\u0bc1\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd\",\"example\":{\"body_text\":[[\"enjoy:800+ TV Channels | 15+ OTT Plans | 1Gbps Wi-Fi\\u23f0\",\"800+ TV \\u0b9a\\u0bc7\\u0ba9\\u0bb2\\u0bcd\\u0b95\\u0bb3\\u0bcd | 15+ OTT \\u0b86\\u0baa\\u0bcd\\u0bb8\\u0bcd | 1 Gbps Wi-Fi\",\"\\u20b9599\"]]}},{\"type\":\"FOOTER\",\"text\":\"Have a nice day...!\"}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-25 12:33:14', 0, '0000-00-00 00:00:00', 1),
(4, '8a01e397654e496a', 2, '', 'seasonal_promotion', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"Our {{1}} is on!\",\"example\":{\"header_text\":[\"Summer Sale\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25OFF\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"QUICK_REPLY\",\"text\":\"Unsubscribe from Promos\"}]}],\"allow_category_change\":false}', '', 7, '2025-03-25 14:49:19', 0, '0000-00-00 00:00:00', 1),
(5, 'f2948346dc1328a0', 2, '649494504336268', 'seasonal_promotion', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"Our {{1}} is on!\",\"example\":{\"header_text\":[\"Summer Sale\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25OFF\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"QUICK_REPLY\",\"text\":\"Unsubscribe from Promos\"}]}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-25 14:59:54', 0, '0000-00-00 00:00:00', 1),
(6, '52dab0a00248aede', 2, '', 'hi template', 'MARKETING', 'en', '{\"name\":\"hi template\",\"language\":\"en\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"hi\"},{\"type\":\"BODY\",\"text\":\"<b>hi<\\/b> everyone\"}],\"allow_category_change\":false}', '', 7, '2025-03-26 10:34:47', 0, '0000-00-00 00:00:00', 1),
(7, '8aadf6660d09ba5b', 2, '1812304893051020', 'hi_template', 'MARKETING', 'en', '{\"name\":\"hi_template\",\"language\":\"en\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"hi\"},{\"type\":\"BODY\",\"text\":\"<b>hi<\\/b> everyone\"}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-26 10:35:31', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_whatsapp_template_languages`
--

CREATE TABLE `cmp_whatsapp_template_languages` (
  `id` int(11) NOT NULL,
  `language_code` varchar(10) NOT NULL,
  `language_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_whatsapp_template_languages`
--

INSERT INTO `cmp_whatsapp_template_languages` (`id`, `language_code`, `language_name`, `created_at`) VALUES
(1, 'af', 'Afrikaans', '2025-03-12 06:34:58'),
(2, 'sq', 'Albanian', '2025-03-12 06:34:58'),
(3, 'ar', 'Arabic', '2025-03-12 06:34:58'),
(4, 'az', 'Azerbaijani', '2025-03-12 06:34:58'),
(5, 'bn', 'Bengali', '2025-03-12 06:34:58'),
(6, 'bg', 'Bulgarian', '2025-03-12 06:34:58'),
(7, 'ca', 'Catalan', '2025-03-12 06:34:58'),
(8, 'zh_CN', 'Chinese (Simplified)', '2025-03-12 06:34:58'),
(9, 'zh_HK', 'Chinese (Hong Kong)', '2025-03-12 06:34:58'),
(10, 'zh_TW', 'Chinese (Traditional)', '2025-03-12 06:34:58'),
(11, 'hr', 'Croatian', '2025-03-12 06:34:58'),
(12, 'cs', 'Czech', '2025-03-12 06:34:58'),
(13, 'da', 'Danish', '2025-03-12 06:34:58'),
(14, 'nl', 'Dutch', '2025-03-12 06:34:58'),
(15, 'en', 'English', '2025-03-12 06:34:58'),
(16, 'en_GB', 'English (UK)', '2025-03-12 06:34:58'),
(17, 'en_US', 'English (US)', '2025-03-12 06:34:58'),
(18, 'et', 'Estonian', '2025-03-12 06:34:58'),
(19, 'fil', 'Filipino', '2025-03-12 06:34:58'),
(20, 'fi', 'Finnish', '2025-03-12 06:34:58'),
(21, 'fr', 'French', '2025-03-12 06:34:58'),
(22, 'de', 'German', '2025-03-12 06:34:58'),
(23, 'el', 'Greek', '2025-03-12 06:34:58'),
(24, 'gu', 'Gujarati', '2025-03-12 06:34:58'),
(25, 'he', 'Hebrew', '2025-03-12 06:34:58'),
(26, 'hi', 'Hindi', '2025-03-12 06:34:58'),
(27, 'hu', 'Hungarian', '2025-03-12 06:34:58'),
(28, 'id', 'Indonesian', '2025-03-12 06:34:58'),
(29, 'ga', 'Irish', '2025-03-12 06:34:58'),
(30, 'it', 'Italian', '2025-03-12 06:34:58'),
(31, 'ja', 'Japanese', '2025-03-12 06:34:58'),
(32, 'kn', 'Kannada', '2025-03-12 06:34:58'),
(33, 'kk', 'Kazakh', '2025-03-12 06:34:58'),
(34, 'ko', 'Korean', '2025-03-12 06:34:58'),
(35, 'lv', 'Latvian', '2025-03-12 06:34:58'),
(36, 'lt', 'Lithuanian', '2025-03-12 06:34:58'),
(37, 'ms', 'Malay', '2025-03-12 06:34:58'),
(38, 'ml', 'Malayalam', '2025-03-12 06:34:58'),
(39, 'mr', 'Marathi', '2025-03-12 06:34:58'),
(40, 'nb', 'Norwegian', '2025-03-12 06:34:58'),
(41, 'fa', 'Persian', '2025-03-12 06:34:58'),
(42, 'pl', 'Polish', '2025-03-12 06:34:58'),
(43, 'pt_BR', 'Portuguese (Brazil)', '2025-03-12 06:34:58'),
(44, 'pt_PT', 'Portuguese (Portugal)', '2025-03-12 06:34:58'),
(45, 'pa', 'Punjabi', '2025-03-12 06:34:58'),
(46, 'ro', 'Romanian', '2025-03-12 06:34:58'),
(47, 'ru', 'Russian', '2025-03-12 06:34:58'),
(48, 'sr', 'Serbian', '2025-03-12 06:34:58'),
(49, 'sk', 'Slovak', '2025-03-12 06:34:58'),
(50, 'sl', 'Slovenian', '2025-03-12 06:34:58'),
(51, 'es', 'Spanish', '2025-03-12 06:34:58'),
(52, 'es_AR', 'Spanish (Argentina)', '2025-03-12 06:34:58'),
(53, 'es_ES', 'Spanish (Spain)', '2025-03-12 06:34:58'),
(54, 'es_MX', 'Spanish (Mexico)', '2025-03-12 06:34:58'),
(55, 'sw', 'Swahili', '2025-03-12 06:34:58'),
(56, 'sv', 'Swedish', '2025-03-12 06:34:58'),
(57, 'ta', 'Tamil', '2025-03-12 06:34:58'),
(58, 'te', 'Telugu', '2025-03-12 06:34:58'),
(59, 'th', 'Thai', '2025-03-12 06:34:58'),
(60, 'tr', 'Turkish', '2025-03-12 06:34:58'),
(61, 'uk', 'Ukrainian', '2025-03-12 06:34:58'),
(62, 'ur', 'Urdu', '2025-03-12 06:34:58'),
(63, 'uz', 'Uzbek', '2025-03-12 06:34:58'),
(64, 'vi', 'Vietnamese', '2025-03-12 06:34:58'),
(65, 'zu', 'Zulu', '2025-03-12 06:34:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cmp_campaign`
--
ALTER TABLE `cmp_campaign`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_campaign_contact`
--
ALTER TABLE `cmp_campaign_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_campaign_variable_mapping`
--
ALTER TABLE `cmp_campaign_variable_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_contact`
--
ALTER TABLE `cmp_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_group_contact`
--
ALTER TABLE `cmp_group_contact`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `cmp_group_contact_mapping`
--
ALTER TABLE `cmp_group_contact_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_mst_country`
--
ALTER TABLE `cmp_mst_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_mst_module`
--
ALTER TABLE `cmp_mst_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_mst_permission`
--
ALTER TABLE `cmp_mst_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_mst_role`
--
ALTER TABLE `cmp_mst_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `cmp_mst_variable`
--
ALTER TABLE `cmp_mst_variable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_mst_wa_temp_payload_strc`
--
ALTER TABLE `cmp_mst_wa_temp_payload_strc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_privilege`
--
ALTER TABLE `cmp_privilege`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_privilege_module_permission_mapping`
--
ALTER TABLE `cmp_privilege_module_permission_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_store`
--
ALTER TABLE `cmp_store`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `cmp_superadmin_vendor_login_log`
--
ALTER TABLE `cmp_superadmin_vendor_login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_users`
--
ALTER TABLE `cmp_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cmp_user_login_log`
--
ALTER TABLE `cmp_user_login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_user_privilege_mapping`
--
ALTER TABLE `cmp_user_privilege_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_user_role_mapping`
--
ALTER TABLE `cmp_user_role_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_vendor`
--
ALTER TABLE `cmp_vendor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `cmp_vendor_fb_credentials`
--
ALTER TABLE `cmp_vendor_fb_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_vendor_store_mapping`
--
ALTER TABLE `cmp_vendor_store_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_vendor_store_staff_mapping`
--
ALTER TABLE `cmp_vendor_store_staff_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_vendor_user_mapping`
--
ALTER TABLE `cmp_vendor_user_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_whatsapp_templates`
--
ALTER TABLE `cmp_whatsapp_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cmp_whatsapp_template_languages`
--
ALTER TABLE `cmp_whatsapp_template_languages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cmp_campaign`
--
ALTER TABLE `cmp_campaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cmp_campaign_contact`
--
ALTER TABLE `cmp_campaign_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cmp_campaign_variable_mapping`
--
ALTER TABLE `cmp_campaign_variable_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cmp_contact`
--
ALTER TABLE `cmp_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cmp_group_contact`
--
ALTER TABLE `cmp_group_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cmp_group_contact_mapping`
--
ALTER TABLE `cmp_group_contact_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cmp_mst_module`
--
ALTER TABLE `cmp_mst_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cmp_mst_permission`
--
ALTER TABLE `cmp_mst_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cmp_mst_role`
--
ALTER TABLE `cmp_mst_role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cmp_mst_variable`
--
ALTER TABLE `cmp_mst_variable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cmp_mst_wa_temp_payload_strc`
--
ALTER TABLE `cmp_mst_wa_temp_payload_strc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cmp_privilege`
--
ALTER TABLE `cmp_privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cmp_privilege_module_permission_mapping`
--
ALTER TABLE `cmp_privilege_module_permission_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cmp_store`
--
ALTER TABLE `cmp_store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cmp_superadmin_vendor_login_log`
--
ALTER TABLE `cmp_superadmin_vendor_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `cmp_users`
--
ALTER TABLE `cmp_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `cmp_user_login_log`
--
ALTER TABLE `cmp_user_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `cmp_user_privilege_mapping`
--
ALTER TABLE `cmp_user_privilege_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `cmp_user_role_mapping`
--
ALTER TABLE `cmp_user_role_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `cmp_vendor`
--
ALTER TABLE `cmp_vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cmp_vendor_store_mapping`
--
ALTER TABLE `cmp_vendor_store_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cmp_vendor_store_staff_mapping`
--
ALTER TABLE `cmp_vendor_store_staff_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `cmp_vendor_user_mapping`
--
ALTER TABLE `cmp_vendor_user_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `cmp_whatsapp_templates`
--
ALTER TABLE `cmp_whatsapp_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cmp_whatsapp_template_languages`
--
ALTER TABLE `cmp_whatsapp_template_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
