-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 12:46 PM
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
(1, 1, 5, 'New Campaign', 1, 'UTC', '2025-04-04 16:34:39', 0, 1, 2, '2025-04-04 15:34:39', 0, '0000-00-00 00:00:00', 1),
(2, 22, 30, 'seasonal_promotion12', 0, '', '2025-04-08 12:25:26', 0, 1, 2, '2025-04-08 11:25:26', 0, '0000-00-00 00:00:00', 1),
(3, 24, 30, 'seasonal_promotion12', 0, '', '2025-04-08 12:36:56', 0, 1, 2, '2025-04-08 11:36:56', 0, '0000-00-00 00:00:00', 1);

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
(1, 1, 1, 1, 2, '2025-04-04 15:34:41', 0, '0000-00-00 00:00:00', 1),
(2, 1, 2, 1, 2, '2025-04-04 15:34:41', 0, '0000-00-00 00:00:00', 1),
(3, 3, 1, 1, 2, '2025-04-08 11:36:57', 0, '0000-00-00 00:00:00', 1),
(4, 3, 2, 1, 2, '2025-04-08 11:36:58', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_campaign_variable_mapping`
--

CREATE TABLE `cmp_campaign_variable_mapping` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
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

INSERT INTO `cmp_campaign_variable_mapping` (`id`, `campaign_id`, `template_id`, `type`, `variable_type_id`, `variable_value`, `group_id`, `active_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 1, 5, 'header', 1, 1, 1, 1, 2, '2025-04-04 15:34:42', 0, '0000-00-00 00:00:00', 1),
(2, 1, 5, 'body', 1, 1, 1, 1, 2, '2025-04-04 15:34:42', 0, '0000-00-00 00:00:00', 1),
(3, 1, 5, 'body', 2, 4, 1, 1, 2, '2025-04-04 15:34:42', 0, '0000-00-00 00:00:00', 1),
(4, 1, 5, 'body', 3, 3, 1, 1, 2, '2025-04-04 15:34:42', 0, '0000-00-00 00:00:00', 1);

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
  `gender` varchar(55) NOT NULL,
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

INSERT INTO `cmp_contact` (`id`, `vendor_id`, `store_id`, `first_name`, `last_name`, `gender`, `mobile`, `email`, `date_of_birth`, `anniversary`, `address`, `loyality`, `language_code`, `country`, `group_id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 1, 1, 'Bharathi', 'K', 'male', 916384626418, 'bharathi@gmail.com', '2025-03-27', '2025-03-31', 'Paramakudi', '', 'en_US', 'India', 0, 2, '2025-03-27 16:05:14', 2, '2025-04-09 11:49:28', 1),
(2, 1, 1, 'Mohamed', 'Thaslim', 'male', 919025714445, 'thaslim@gmail.com', '0000-00-00', '0000-00-00', 'Vandalur', '', 'en_US', 'Italy', 0, 2, '2025-03-27 17:42:24', 2, '2025-04-09 11:48:47', 1),
(8, 1, 1, 'Siva', 'Kumar', 'Male', 897878787, 'sivakkk@gmail.com', '2000-01-08', '2000-08-01', 'Chennai', '143', 'tamil', 'India', 0, 2, '2025-04-07 18:20:26', 2, '2025-04-07 18:27:50', 1),
(9, 1, 1, 'syed', 'ali', 'Male', 789776774, 'syed@gmail.com', '2000-01-08', '2000-08-01', 'Chennai', '143', 'tamil', 'India', 0, 2, '2025-04-07 18:29:59', 0, '0000-00-00 00:00:00', 1);

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
(1, '5c2188931ad27d8c', 1, 'Hermon_Solutions', 'Work and Enjoy', 0, 2, '2025-03-27 16:04:06', 0, '0000-00-00 00:00:00', 0),
(2, '03c04efb3e3d4da2', 1, 'Ans', 'Ans', 0, 2, '2025-04-07 15:22:37', 0, '0000-00-00 00:00:00', 0),
(3, 'c30dc6b42f63510b', 1, 'fdf', '', 0, 2, '2025-04-07 19:16:17', 0, '0000-00-00 00:00:00', 0),
(4, 'a2051902380a02b3', 1, 'getr', '', 0, 2, '2025-04-07 20:04:43', 0, '0000-00-00 00:00:00', 0),
(5, '5d769087933f0d72', 1, 'etrgetr', '', 0, 2, '2025-04-07 20:04:47', 0, '0000-00-00 00:00:00', 0),
(6, 'c0babbee25b2b631', 1, 'tehtrh', '', 0, 2, '2025-04-07 20:04:52', 0, '0000-00-00 00:00:00', 0),
(7, 'a88e12bb1acc3c2f', 1, 'htrht', '', 0, 2, '2025-04-07 20:04:55', 0, '0000-00-00 00:00:00', 0),
(8, '006d63d322c22ce1', 1, 'rthjryj', '', 0, 2, '2025-04-07 20:05:03', 0, '0000-00-00 00:00:00', 0),
(9, '56a9dca9715dcad8', 1, 'rjhtyj', '', 0, 2, '2025-04-07 20:05:06', 0, '0000-00-00 00:00:00', 0),
(10, '7fbcb78016a70280', 1, 'k6ut8kk', '', 1, 2, '2025-04-07 20:08:42', 0, '0000-00-00 00:00:00', 0),
(11, '8e840b61c10722ec', 1, 'k68uk', '', 1, 2, '2025-04-07 20:08:45', 0, '0000-00-00 00:00:00', 0),
(12, 'b730eef0697dbfa1', 1, 'e6tuk', '', 1, 2, '2025-04-07 20:08:48', 0, '0000-00-00 00:00:00', 0),
(13, '7415d114e23b8fe8', 1, 'k6u8tk', '', 0, 2, '2025-04-07 20:08:51', 0, '0000-00-00 00:00:00', 0),
(14, 'd6ed0ca6cce0f280', 1, '6uk8k', '', 0, 2, '2025-04-07 20:08:53', 0, '0000-00-00 00:00:00', 0),
(15, 'e28eefc067da3f97', 1, '6tu8k', '', 0, 2, '2025-04-07 20:08:56', 0, '0000-00-00 00:00:00', 1),
(16, '6ee3ae747f2279ab', 1, 'trdhrh', '', 0, 2, '2025-04-07 20:10:37', 0, '0000-00-00 00:00:00', 1),
(17, 'fe4928c2b8fe1e61', 1, 'threetr', '', 0, 2, '2025-04-07 20:10:40', 0, '0000-00-00 00:00:00', 0),
(18, '2af13042f7851155', 1, 'ethtreh', '', 0, 2, '2025-04-07 20:10:43', 0, '0000-00-00 00:00:00', 0),
(19, '55c684017a1516f6', 1, 'terhtreh', '', 0, 2, '2025-04-07 20:10:46', 0, '0000-00-00 00:00:00', 0),
(20, 'e4afeaeaa45b1c49', 1, 'trehtrh', '', 0, 2, '2025-04-07 20:10:48', 0, '0000-00-00 00:00:00', 1),
(21, '093695efe9ba4d8f', 1, 'rthtrhtrh', '', 0, 2, '2025-04-07 20:10:51', 0, '0000-00-00 00:00:00', 1),
(22, '55df4a053f900651', 1, 'trhrthtrh', '', 0, 2, '2025-04-07 20:10:54', 0, '0000-00-00 00:00:00', 1),
(23, '4f05ffa72b86196e', 1, 'thrh', '', 0, 2, '2025-04-08 10:21:57', 0, '0000-00-00 00:00:00', 1),
(24, 'cd3a4fbfbd92a734', 1, 'Hermons', 'HS', 1, 2, '2025-04-08 10:21:59', 2, '2025-04-08 11:35:53', 1),
(25, 'adb55a59861424e7', 1, '=-', '', 0, 2, '2025-04-08 10:48:15', 0, '0000-00-00 00:00:00', 1),
(26, '6f172c9797f24992', 1, 'fgtrjhy', '', 1, 2, '2025-04-08 10:48:20', 0, '0000-00-00 00:00:00', 1),
(27, '6f287adf9422597c', 1, 'yukjuy', '', 1, 2, '2025-04-08 11:18:02', 0, '0000-00-00 00:00:00', 1),
(28, '62b4e6c18eab88c9', 1, 'ykuili', '', 1, 2, '2025-04-08 11:18:07', 0, '0000-00-00 00:00:00', 1),
(29, '81ab685b2cd861fb', 1, 'ryilr', '', 1, 2, '2025-04-08 11:18:09', 0, '0000-00-00 00:00:00', 1),
(30, '1576577c7771fb69', 1, 'yukuy', '', 1, 2, '2025-04-08 11:18:16', 0, '0000-00-00 00:00:00', 1),
(31, '8a69069048fdb450', 1, 'yukliy', '', 1, 2, '2025-04-08 11:18:20', 0, '0000-00-00 00:00:00', 1),
(32, '5e5e18100c14eacb', 1, '0[p-[', '', 1, 2, '2025-04-08 14:58:29', 0, '0000-00-00 00:00:00', 1),
(33, 'ced77a70d20277a1', 1, 'tryhrjhy', '', 1, 2, '2025-04-08 14:58:33', 0, '0000-00-00 00:00:00', 1),
(34, 'bfb6aa87b11f291f', 1, 'ytj5yy5', '', 1, 2, '2025-04-08 14:58:36', 0, '0000-00-00 00:00:00', 1),
(35, '75ec4be734fd29a6', 1, '5yj5y', '', 1, 2, '2025-04-08 14:58:39', 0, '0000-00-00 00:00:00', 1);

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
(1, 1, 1, 1, 2, '2025-03-27 16:05:14', 0),
(2, 1, 2, 1, 2, '2025-03-27 17:42:24', 0),
(3, 1, 8, 1, 2, '2025-04-07 18:27:50', 1),
(4, 24, 1, 1, 2, '2025-04-08 11:36:07', 1),
(5, 24, 2, 1, 2, '2025-04-08 11:36:14', 1);

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
-- Table structure for table `cmp_mst_timezone`
--

CREATE TABLE `cmp_mst_timezone` (
  `id` int(11) NOT NULL,
  `timezone_name` varchar(100) NOT NULL,
  `utc_offset` varchar(10) NOT NULL,
  `location_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_mst_timezone`
--

INSERT INTO `cmp_mst_timezone` (`id`, `timezone_name`, `utc_offset`, `location_name`) VALUES
(1, 'Pacific/Midway', 'UTC-11:00', 'Midway Island, American Samoa'),
(2, 'Pacific/Honolulu', 'UTC-10:00', 'Hawaii'),
(3, 'America/Anchorage', 'UTC-08:00', 'Alaska'),
(4, 'America/Tijuana', 'UTC-07:00', 'Baja California'),
(5, 'America/Los_Angeles', 'UTC-07:00', 'Pacific Time (US and Canada)'),
(6, 'America/Phoenix', 'UTC-07:00', 'Arizona'),
(7, 'America/Chihuahua', 'UTC-06:00', 'Chihuahua, La Paz, Mazatlan'),
(8, 'America/Denver', 'UTC-06:00', 'Mountain Time (US and Canada)'),
(9, 'America/Belize', 'UTC-06:00', 'Central America'),
(10, 'America/Chicago', 'UTC-05:00', 'Central Time (US and Canada)'),
(11, 'America/Mexico_City', 'UTC-05:00', 'Guadalajara, Mexico City, Monterrey'),
(12, 'America/Regina', 'UTC-06:00', 'Saskatchewan'),
(13, 'America/Bogota', 'UTC-05:00', 'Bogota, Lima, Quito'),
(14, 'America/Jamaica', 'UTC-05:00', 'Kingston, George Town'),
(15, 'America/New_York', 'UTC-04:00', 'Eastern Time (US and Canada)'),
(16, 'America/Indiana/Indianapolis', 'UTC-04:00', 'Indiana (East)'),
(17, 'America/Caracas', 'UTC-04:30', 'Caracas'),
(18, 'America/Asuncion', 'UTC-03:00', 'Asuncion'),
(19, 'America/Halifax', 'UTC-03:00', 'Atlantic Time (Canada)'),
(20, 'America/Cuiaba', 'UTC-04:00', 'Cuiaba'),
(21, 'America/Manaus', 'UTC-04:00', 'Georgetown, La Paz, Manaus, San Juan'),
(22, 'America/St_Johns', 'UTC-02:30', 'Newfoundland and Labrador'),
(23, 'America/Sao_Paulo', 'UTC-03:00', 'Brasilia'),
(24, 'America/Buenos_Aires', 'UTC-03:00', 'Buenos Aires'),
(25, 'America/Cayenne', 'UTC-03:00', 'Cayenne, Fortaleza'),
(26, 'America/Godthab', 'UTC-02:00', 'Greenland'),
(27, 'America/Montevideo', 'UTC-03:00', 'Montevideo'),
(28, 'America/Bahia', 'UTC-03:00', 'Salvador'),
(29, 'America/Santiago', 'UTC-03:00', 'Santiago'),
(30, 'America/Noronha', 'UTC-02:00', 'Mid-Atlantic'),
(31, 'Atlantic/Azores', 'UTC+00:00', 'Azores'),
(32, 'Atlantic/Cape_Verde', 'UTC-01:00', 'Cape Verde Islands'),
(33, 'Europe/London', 'UTC+01:00', 'Dublin, Edinburgh, Lisbon, London'),
(34, 'Africa/Casablanca', 'UTC+01:00', 'Casablanca'),
(35, 'Africa/Monrovia', 'UTC+00:00', 'Monrovia, Reykjavik'),
(36, 'Europe/Amsterdam', 'UTC+02:00', 'Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),
(37, 'Europe/Belgrade', 'UTC+02:00', 'Belgrade, Bratislava, Budapest, Ljubljana, Prague'),
(38, 'Europe/Brussels', 'UTC+02:00', 'Brussels, Copenhagen, Madrid, Paris'),
(39, 'Europe/Warsaw', 'UTC+02:00', 'Sarajevo, Skopje, Warsaw, Zagreb'),
(40, 'Africa/Algiers', 'UTC+01:00', 'West Central Africa'),
(41, 'Africa/Windhoek', 'UTC+02:00', 'Windhoek'),
(42, 'Europe/Athens', 'UTC+03:00', 'Athens, Bucharest'),
(43, 'Asia/Beirut', 'UTC+03:00', 'Beirut'),
(44, 'Africa/Cairo', 'UTC+02:00', 'Cairo'),
(45, 'Asia/Damascus', 'UTC+03:00', 'Damascus'),
(46, 'EET', 'UTC+03:00', 'Eastern Europe'),
(47, 'Africa/Harare', 'UTC+02:00', 'Harare, Pretoria'),
(48, 'Europe/Helsinki', 'UTC+03:00', 'Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius'),
(49, 'Asia/Istanbul', 'UTC+03:00', 'Istanbul'),
(50, 'Asia/Jerusalem', 'UTC+03:00', 'Jerusalem'),
(51, 'Europe/Kaliningrad', 'UTC+02:00', 'Kaliningrad'),
(52, 'Africa/Tripoli', 'UTC+02:00', 'Tripoli'),
(53, 'Asia/Amman', 'UTC+03:00', 'Amman'),
(54, 'Asia/Baghdad', 'UTC+03:00', 'Baghdad'),
(55, 'Asia/Kuwait', 'UTC+03:00', 'Kuwait, Riyadh'),
(56, 'Europe/Minsk', 'UTC+03:00', 'Minsk'),
(57, 'Europe/Moscow', 'UTC+03:00', 'Moscow, St. Petersburg, Volgograd'),
(58, 'Africa/Nairobi', 'UTC+03:00', 'Nairobi'),
(59, 'Asia/Tehran', 'UTC+03:30', 'Tehran'),
(60, 'Asia/Muscat', 'UTC+04:00', 'Abu Dhabi, Muscat'),
(61, 'Asia/Baku', 'UTC+05:00', 'Baku'),
(62, 'Europe/Samara', 'UTC+04:00', 'Izhevsk, Samara'),
(63, 'Indian/Mauritius', 'UTC+04:00', 'Port Louis'),
(64, 'Asia/Tbilisi', 'UTC+04:00', 'Tbilisi'),
(65, 'Asia/Yerevan', 'UTC+04:00', 'Yerevan'),
(66, 'Asia/Kabul', 'UTC+04:30', 'Kabul'),
(67, 'Asia/Tashkent', 'UTC+05:00', 'Tashkent, Ashgabat'),
(68, 'Asia/Yekaterinburg', 'UTC+05:00', 'Ekaterinburg'),
(69, 'Asia/Karachi', 'UTC+05:00', 'Islamabad, Karachi'),
(70, 'Asia/Kolkata', 'UTC+05:30', 'Chennai, Kolkata, Mumbai, New Delhi'),
(71, 'Asia/Colombo', 'UTC+05:30', 'Sri Jayawardenepura'),
(72, 'Asia/Katmandu', 'UTC+05:45', 'Kathmandu'),
(73, 'Asia/Almaty', 'UTC+06:00', 'Astana'),
(74, 'Asia/Dhaka', 'UTC+06:00', 'Dhaka'),
(75, 'Asia/Novosibirsk', 'UTC+06:00', 'Novosibirsk'),
(76, 'Asia/Rangoon', 'UTC+06:30', 'Yangon (Rangoon)'),
(77, 'Asia/Bangkok', 'UTC+07:00', 'Bangkok, Hanoi, Jakarta'),
(78, 'Asia/Krasnoyarsk', 'UTC+07:00', 'Krasnoyarsk'),
(79, 'Asia/Chongqing', 'UTC+08:00', 'Beijing, Chongqing, Hong Kong SAR, Urumqi'),
(80, 'Asia/Taipei', 'UTC+08:00', 'Taipei'),
(81, 'Asia/Tokyo', 'UTC+09:00', 'Osaka, Sapporo, Tokyo'),
(82, 'Asia/Yakutsk', 'UTC+09:00', 'Yakutsk'),
(83, 'Australia/Sydney', 'UTC+11:00', 'Canberra, Melbourne, Sydney'),
(84, 'Pacific/Fiji', 'UTC+12:00', 'Fiji Islands, Kamchatka, Marshall Islands'),
(85, 'Pacific/Auckland', 'UTC+13:00', 'Auckland, Wellington'),
(86, 'Pacific/Apia', 'UTC+14:00', 'Samoa');

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
(1, 'Contact Full Name', 1, 1),
(2, 'Contact First Name', 1, 1),
(3, 'Contact Last Name', 1, 1),
(4, 'Contact Phone', 1, 1),
(5, 'Contact Language Code', 1, 1),
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
(1, 'fe0ad47290be6c08', 'BTS', '2/10,L.N.Pudhur, Bts Street', 'Paramakudi', 'Pudukkottai', 'Tamilnadu', 614203, 9488638996, 'bts@gmail.com', 1, 2, '2025-03-27 15:59:00', 2, '2025-03-27 17:02:24', 1),
(2, ' cbbb1c7c71f33b3e ', 'Super Store', 'T.Nagar', 'Chennai', 'Kanchipuram', 'Tamilnadu', 60028, 8998898989, 'ssstore@gmail.om', 1, 2, '2025-04-07 17:14:36', 0, '0000-00-00 00:00:00', 1);

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
(1, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-27 15:24:15', '2025-03-27 15:24:15', '2025-03-27 15:24:15', '0000-00-00 00:00:00', 1, 1),
(2, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-27 15:55:23', '2025-03-27 15:55:23', '2025-03-27 15:55:23', '0000-00-00 00:00:00', 1, 1),
(3, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-31 15:30:54', '2025-03-31 15:30:54', '2025-03-31 14:30:54', '0000-00-00 00:00:00', 1, 1),
(4, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-31 15:33:43', '2025-03-31 15:33:43', '2025-03-31 14:33:43', '0000-00-00 00:00:00', 1, 1),
(5, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-31 15:33:44', '2025-03-31 15:33:44', '2025-03-31 14:33:44', '0000-00-00 00:00:00', 1, 1),
(6, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-31 15:36:17', '2025-03-31 15:36:17', '2025-03-31 14:36:17', '0000-00-00 00:00:00', 1, 1),
(7, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-31 15:36:18', '2025-03-31 15:36:18', '2025-03-31 14:36:18', '0000-00-00 00:00:00', 1, 1),
(8, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MTc0MzQxNTYwMCwiaWF0IjoxNzQzNDEyMDAwfQ.rc4qQM0wJ47HJSxhH9UhBBJU6kRy83qXebXYweFLDI', '2025-03-31 15:36:46', '2025-03-31 15:36:46', '2025-03-31 14:36:46', '0000-00-00 00:00:00', 1, 1),
(9, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 14:22:13', '2025-04-08 14:22:13', '2025-04-08 13:22:13', '0000-00-00 00:00:00', 1, 1),
(10, 2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 14:55:48', '2025-04-08 14:55:48', '2025-04-08 13:55:48', '0000-00-00 00:00:00', 1, 1),
(11, 2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJEYW50aGVkZHIiLCJleHAiOjM2MDB9.I2wI4eKV72o71Cs5zKhwGnM7xXkgXOYahAddGfUklw', '2025-04-08 14:58:06', '2025-04-08 14:58:06', '2025-04-08 13:58:06', '0000-00-00 00:00:00', 1, 1),
(12, 2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJEYW50aGVkZHIiLCJleHAiOjM2MDB9.I2wI4eKV72o71Cs5zKhwGnM7xXkgXOYahAddGfUklw', '2025-04-08 15:32:12', '2025-04-08 15:32:12', '2025-04-08 14:32:12', '0000-00-00 00:00:00', 1, 1),
(13, 2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJEYW5pZWwiLCJleHAiOjM2MDB9.iY4kECcVQDPpxiHq0yuilIAhUYjaPNn1C3Vn3ys0dQ', '2025-04-08 15:36:20', '2025-04-08 15:36:20', '2025-04-08 14:36:20', '0000-00-00 00:00:00', 1, 1),
(14, 2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 15:57:07', '2025-04-08 15:57:07', '2025-04-08 14:57:07', '0000-00-00 00:00:00', 1, 1),
(15, 2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJEYW5pZWwiLCJleHAiOjM2MDB9.iY4kECcVQDPpxiHq0yuilIAhUYjaPNn1C3Vn3ys0dQ', '2025-04-08 15:58:11', '2025-04-08 15:58:11', '2025-04-08 14:58:11', '0000-00-00 00:00:00', 1, 1);

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
(2, 'b849f1847b1d96b5', 'Daniel', 'Raja', 'Daniel', 'dantheddr@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 9841652232, 1, 1, '2025-03-27 15:23:58', 1, '2025-04-08 14:36:13', 1),
(3, 'ec1ff52508fcff8b', 'trFirst', 'trlname', 'trendsuserName', 'trendsuser@gmail.com', '01b613da484bee91c3f3806b52a6f40fd61ade874b5ffc0f62a2091cce38158b', 12345, 1, 2, '2025-03-27 16:01:23', 3, '2025-03-28 15:20:51', 1),
(4, 'af37bbdf059d148e', 'Suresh', 'Raina', 'Suresh', 'suresh@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 8899776655, 1, 2, '2025-04-07 17:02:04', 0, '0000-00-00 00:00:00', 0),
(5, '1c6b8915bbd39493', 'Suresh', 'Kumar', 'Suresh', 'sureshkumar@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 8899776655, 1, 2, '2025-04-07 17:04:53', 0, '0000-00-00 00:00:00', 1);

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
(1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-27 10:46:18', '2025-03-27 10:46:18', '2025-03-27 15:16:18', '0000-00-00 00:00:00.000000', 0, 1),
(2, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-27 11:26:07', '2025-03-27 11:26:07', '2025-03-27 15:56:07', '0000-00-00 00:00:00.000000', 0, 1),
(3, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-27 11:26:27', '2025-03-27 11:26:27', '2025-03-27 15:56:27', '0000-00-00 00:00:00.000000', 0, 1),
(4, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-03-27 11:27:09', '2025-03-27 11:27:09', '2025-03-27 15:57:09', '0000-00-00 00:00:00.000000', 1, 1),
(5, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-03-27 12:31:57', '2025-03-27 12:31:57', '2025-03-27 17:01:57', '0000-00-00 00:00:00.000000', 0, 1),
(6, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-03-27 12:58:17', '2025-03-27 12:58:17', '2025-03-27 17:28:17', '0000-00-00 00:00:00.000000', 0, 1),
(7, 3, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMyIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.F3EkRbcG1yyho8tlB0pVw8WWAZyASRQBGG6mxXGTY', '2025-03-28 10:50:17', '2025-03-28 10:50:17', '2025-03-28 15:20:17', '0000-00-00 00:00:00.000000', 1, 1),
(8, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-03-31 10:58:52', '2025-03-31 10:58:52', '2025-03-31 14:28:52', '0000-00-00 00:00:00.000000', 0, 1),
(9, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MTc0MzQxNTYwMCwiaWF0IjoxNzQzNDEyMDAwfQ.rc4qQM0wJ47HJSxhH9UhBBJU6kRy83qXebXYweFLDI', '2025-03-31 11:06:40', '2025-03-31 11:06:40', '2025-03-31 14:36:40', '0000-00-00 00:00:00.000000', 0, 1),
(10, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 11:50:52', '2025-04-07 11:50:52', '2025-04-07 15:20:52', '0000-00-00 00:00:00.000000', 0, 1),
(11, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 11:58:25', '2025-04-07 11:58:25', '2025-04-07 15:28:25', '0000-00-00 00:00:00.000000', 0, 1),
(12, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 12:09:04', '2025-04-07 12:09:04', '2025-04-07 15:39:04', '0000-00-00 00:00:00.000000', 0, 1),
(13, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 12:25:33', '2025-04-07 12:25:33', '2025-04-07 15:55:33', '0000-00-00 00:00:00.000000', 0, 1),
(14, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 12:33:48', '2025-04-07 12:33:48', '2025-04-07 16:03:48', '0000-00-00 00:00:00.000000', 0, 1),
(15, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 12:38:59', '2025-04-07 12:38:59', '2025-04-07 16:08:59', '0000-00-00 00:00:00.000000', 0, 1),
(16, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 12:43:19', '2025-04-07 12:43:19', '2025-04-07 16:13:19', '0000-00-00 00:00:00.000000', 0, 1),
(17, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 12:49:14', '2025-04-07 12:49:14', '2025-04-07 16:19:14', '0000-00-00 00:00:00.000000', 0, 1),
(18, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 13:06:45', '2025-04-07 13:06:45', '2025-04-07 16:36:45', '0000-00-00 00:00:00.000000', 0, 1),
(19, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 13:13:46', '2025-04-07 13:13:46', '2025-04-07 16:43:46', '0000-00-00 00:00:00.000000', 0, 1),
(20, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 13:15:17', '2025-04-07 13:15:17', '2025-04-07 16:45:17', '0000-00-00 00:00:00.000000', 0, 1),
(21, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 13:16:25', '2025-04-07 13:16:25', '2025-04-07 16:46:25', '0000-00-00 00:00:00.000000', 0, 1),
(22, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 13:17:44', '2025-04-07 13:17:44', '2025-04-07 16:47:44', '0000-00-00 00:00:00.000000', 0, 1),
(23, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 13:22:45', '2025-04-07 13:22:45', '2025-04-07 16:52:45', '0000-00-00 00:00:00.000000', 0, 1),
(24, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 13:26:59', '2025-04-07 13:26:59', '2025-04-07 16:56:59', '0000-00-00 00:00:00.000000', 0, 1),
(25, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 13:30:06', '2025-04-07 13:30:06', '2025-04-07 17:00:06', '0000-00-00 00:00:00.000000', 0, 1),
(26, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 13:38:42', '2025-04-07 13:38:42', '2025-04-07 17:08:42', '0000-00-00 00:00:00.000000', 0, 1),
(27, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 14:21:22', '2025-04-07 14:21:22', '2025-04-07 17:51:22', '0000-00-00 00:00:00.000000', 0, 1),
(28, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 14:26:25', '2025-04-07 14:26:25', '2025-04-07 17:56:25', '0000-00-00 00:00:00.000000', 0, 1),
(29, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 14:45:50', '2025-04-07 14:45:50', '2025-04-07 18:15:50', '0000-00-00 00:00:00.000000', 0, 1),
(30, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 14:52:56', '2025-04-07 14:52:56', '2025-04-07 18:22:56', '0000-00-00 00:00:00.000000', 0, 1),
(31, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 15:11:06', '2025-04-07 15:11:06', '2025-04-07 18:41:06', '0000-00-00 00:00:00.000000', 0, 1),
(32, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 15:14:18', '2025-04-07 15:14:18', '2025-04-07 18:44:18', '0000-00-00 00:00:00.000000', 0, 1),
(33, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 15:28:01', '2025-04-07 15:28:01', '2025-04-07 18:58:01', '0000-00-00 00:00:00.000000', 0, 1),
(34, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 15:32:04', '2025-04-07 15:32:04', '2025-04-07 19:02:04', '0000-00-00 00:00:00.000000', 0, 1),
(35, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 15:44:40', '2025-04-07 15:44:40', '2025-04-07 19:14:40', '0000-00-00 00:00:00.000000', 0, 1),
(36, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 16:03:25', '2025-04-07 16:03:25', '2025-04-07 19:33:25', '0000-00-00 00:00:00.000000', 0, 1),
(37, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 16:11:50', '2025-04-07 16:11:50', '2025-04-07 19:41:50', '0000-00-00 00:00:00.000000', 0, 1),
(38, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 16:14:43', '2025-04-07 16:14:43', '2025-04-07 19:44:43', '0000-00-00 00:00:00.000000', 0, 1),
(39, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 16:17:10', '2025-04-07 16:17:10', '2025-04-07 19:47:10', '0000-00-00 00:00:00.000000', 0, 1),
(40, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 16:21:37', '2025-04-07 16:21:37', '2025-04-07 19:51:37', '0000-00-00 00:00:00.000000', 0, 1),
(41, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-07 16:52:52', '2025-04-07 16:52:52', '2025-04-07 20:22:52', '0000-00-00 00:00:00.000000', 0, 1),
(42, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 06:27:48', '2025-04-08 06:27:48', '2025-04-08 09:57:48', '0000-00-00 00:00:00.000000', 0, 1),
(43, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 06:39:28', '2025-04-08 06:39:28', '2025-04-08 10:09:28', '0000-00-00 00:00:00.000000', 0, 1),
(44, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 06:47:37', '2025-04-08 06:47:37', '2025-04-08 10:17:37', '0000-00-00 00:00:00.000000', 0, 1),
(45, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 06:57:14', '2025-04-08 06:57:14', '2025-04-08 10:27:14', '0000-00-00 00:00:00.000000', 0, 1),
(46, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 07:08:30', '2025-04-08 07:08:30', '2025-04-08 10:38:30', '0000-00-00 00:00:00.000000', 0, 1),
(47, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 07:11:31', '2025-04-08 07:11:31', '2025-04-08 10:41:31', '0000-00-00 00:00:00.000000', 0, 1),
(48, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 07:18:57', '2025-04-08 07:18:57', '2025-04-08 10:48:57', '0000-00-00 00:00:00.000000', 0, 1),
(49, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 07:23:32', '2025-04-08 07:23:32', '2025-04-08 10:53:32', '0000-00-00 00:00:00.000000', 0, 1),
(50, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 07:32:59', '2025-04-08 07:32:59', '2025-04-08 11:02:59', '0000-00-00 00:00:00.000000', 0, 1),
(51, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 07:39:24', '2025-04-08 07:39:24', '2025-04-08 11:09:24', '0000-00-00 00:00:00.000000', 0, 1),
(52, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 08:01:44', '2025-04-08 08:01:44', '2025-04-08 11:31:44', '0000-00-00 00:00:00.000000', 0, 1),
(53, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 08:06:26', '2025-04-08 08:06:26', '2025-04-08 11:36:26', '0000-00-00 00:00:00.000000', 0, 1),
(54, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 08:27:37', '2025-04-08 08:27:37', '2025-04-08 11:57:37', '0000-00-00 00:00:00.000000', 0, 1),
(55, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 08:51:16', '2025-04-08 08:51:16', '2025-04-08 12:21:16', '0000-00-00 00:00:00.000000', 0, 1),
(56, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 08:54:39', '2025-04-08 08:54:39', '2025-04-08 12:24:39', '0000-00-00 00:00:00.000000', 0, 1),
(57, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 09:04:53', '2025-04-08 09:04:53', '2025-04-08 12:34:53', '0000-00-00 00:00:00.000000', 0, 1),
(58, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 09:19:25', '2025-04-08 09:19:25', '2025-04-08 12:49:25', '0000-00-00 00:00:00.000000', 0, 1),
(59, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 09:25:06', '2025-04-08 09:25:06', '2025-04-08 12:55:06', '0000-00-00 00:00:00.000000', 0, 1),
(60, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 09:35:10', '2025-04-08 09:35:10', '2025-04-08 13:05:10', '0000-00-00 00:00:00.000000', 0, 1),
(61, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 09:48:18', '2025-04-08 09:48:18', '2025-04-08 13:18:18', '0000-00-00 00:00:00.000000', 0, 1),
(62, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 09:50:47', '2025-04-08 09:50:47', '2025-04-08 13:20:47', '0000-00-00 00:00:00.000000', 0, 1),
(63, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 09:51:23', '2025-04-08 09:51:23', '2025-04-08 13:21:23', '0000-00-00 00:00:00.000000', 0, 1),
(64, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 10:00:05', '2025-04-08 10:00:05', '2025-04-08 13:30:05', '0000-00-00 00:00:00.000000', 0, 1),
(65, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 10:00:11', '2025-04-08 10:00:11', '2025-04-08 13:30:11', '0000-00-00 00:00:00.000000', 0, 1),
(66, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 10:02:04', '2025-04-08 10:02:04', '2025-04-08 13:32:04', '0000-00-00 00:00:00.000000', 0, 1),
(67, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 10:10:21', '2025-04-08 10:10:21', '2025-04-08 13:40:21', '0000-00-00 00:00:00.000000', 0, 1),
(68, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 10:24:53', '2025-04-08 10:24:53', '2025-04-08 13:54:53', '0000-00-00 00:00:00.000000', 0, 1),
(69, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 10:27:53', '2025-04-08 10:27:53', '2025-04-08 13:57:53', '0000-00-00 00:00:00.000000', 0, 1),
(70, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJEYW50aGVkZHIiLCJleHAiOjM2MDB9.I2wI4eKV72o71Cs5zKhwGnM7xXkgXOYahAddGfUklw', '2025-04-08 10:28:06', '2025-04-08 10:28:06', '2025-04-08 13:58:06', '0000-00-00 00:00:00.000000', 0, 1),
(71, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 10:50:15', '2025-04-08 10:50:15', '2025-04-08 14:20:15', '0000-00-00 00:00:00.000000', 0, 1),
(72, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 10:55:31', '2025-04-08 10:55:31', '2025-04-08 14:25:31', '0000-00-00 00:00:00.000000', 0, 1),
(73, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJEYW50aGVkZHIiLCJleHAiOjM2MDB9.I2wI4eKV72o71Cs5zKhwGnM7xXkgXOYahAddGfUklw', '2025-04-08 11:02:12', '2025-04-08 11:02:12', '2025-04-08 14:32:12', '0000-00-00 00:00:00.000000', 0, 1),
(74, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 11:04:02', '2025-04-08 11:04:02', '2025-04-08 14:34:02', '0000-00-00 00:00:00.000000', 0, 1),
(75, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 11:05:58', '2025-04-08 11:05:58', '2025-04-08 14:35:58', '0000-00-00 00:00:00.000000', 1, 1),
(76, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJEYW5pZWwiLCJleHAiOjM2MDB9.iY4kECcVQDPpxiHq0yuilIAhUYjaPNn1C3Vn3ys0dQ', '2025-04-08 11:06:20', '2025-04-08 11:06:20', '2025-04-08 14:36:20', '0000-00-00 00:00:00.000000', 0, 1),
(77, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 11:06:57', '2025-04-08 11:06:57', '2025-04-08 14:36:57', '0000-00-00 00:00:00.000000', 0, 1),
(78, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 11:07:19', '2025-04-08 11:07:19', '2025-04-08 14:37:19', '0000-00-00 00:00:00.000000', 1, 1),
(79, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 11:18:42', '2025-04-08 11:18:42', '2025-04-08 14:48:42', '0000-00-00 00:00:00.000000', 0, 1),
(80, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-08 11:27:40', '2025-04-08 11:27:40', '2025-04-08 14:57:40', '0000-00-00 00:00:00.000000', 1, 1),
(81, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOiJEYW5pZWwiLCJleHAiOjM2MDB9.iY4kECcVQDPpxiHq0yuilIAhUYjaPNn1C3Vn3ys0dQ', '2025-04-08 11:28:11', '2025-04-08 11:28:11', '2025-04-08 14:58:11', '0000-00-00 00:00:00.000000', 1, 1),
(82, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 12:10:00', '2025-04-08 12:10:00', '2025-04-08 15:40:00', '0000-00-00 00:00:00.000000', 1, 1),
(83, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 12:26:51', '2025-04-08 12:26:51', '2025-04-08 15:56:51', '0000-00-00 00:00:00.000000', 1, 1),
(84, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 13:03:05', '2025-04-08 13:03:05', '2025-04-08 16:33:05', '0000-00-00 00:00:00.000000', 1, 1),
(85, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 13:06:10', '2025-04-08 13:06:10', '2025-04-08 16:36:10', '0000-00-00 00:00:00.000000', 1, 1),
(86, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-08 13:33:28', '2025-04-08 13:33:28', '2025-04-08 17:03:28', '0000-00-00 00:00:00.000000', 1, 1),
(87, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-09 07:20:08', '2025-04-09 07:20:08', '2025-04-09 10:50:08', '0000-00-00 00:00:00.000000', 1, 1),
(88, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-09 08:02:13', '2025-04-09 08:02:13', '2025-04-09 11:32:13', '0000-00-00 00:00:00.000000', 1, 1),
(89, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMiIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.D57pBJgU0w8Py1KXH2ckJIqeIzNKpU9bgrQ3JCF1h6I', '2025-04-09 12:45:22', '2025-04-09 12:45:22', '2025-04-09 12:45:22', '0000-00-00 00:00:00.000000', 1, 1);

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
(84, 102, 2, 1, 1, 7, '2025-03-14 18:37:13', 7, '2025-03-24 13:09:22', 0),
(85, 3, 1, 1, 1, 2, '2025-03-27 16:01:23', 2, '2025-03-27 16:02:00', 1),
(86, 4, 2, 1, 1, 2, '2025-04-07 17:02:32', 0, '0000-00-00 00:00:00', 0),
(87, 5, 2, 1, 1, 2, '2025-04-07 17:04:53', 0, '0000-00-00 00:00:00', 1);

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
(1, 1, 1, 1, 1, '2025-03-27 15:14:19', 0, '2025-03-27 10:44:10', 1),
(2, 2, 2, 1, 1, '2025-03-27 15:23:58', 0, '0000-00-00 00:00:00', 1),
(3, 3, 4, 1, 2, '2025-03-27 16:01:23', 0, '0000-00-00 00:00:00', 1),
(4, 4, 4, 1, 2, '2025-04-07 17:02:04', 0, '0000-00-00 00:00:00', 0),
(5, 4, 4, 1, 2, '2025-04-07 17:02:32', 0, '0000-00-00 00:00:00', 0),
(6, 5, 4, 1, 2, '2025-04-07 17:04:53', 0, '0000-00-00 00:00:00', 1);

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
(1, '059a9fa85a82b2b9', 'Hermon Solution', 'IT', 'Perungalathur,Chennai', 9841652232, 'hermonsolutions@gamil.com', 1, 1, '2025-03-27 10:53:58', 1, '2025-04-08 14:36:13', 1);

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
(1, 0, 1, 1, 1, '2025-03-27 15:53:50', 0, '0000-00-00 00:00:00', 1),
(2, 1, 1, 1, 2, '2025-03-27 15:59:00', 0, '0000-00-00 00:00:00', 1),
(3, 1, 2, 1, 0, '2025-04-07 17:14:36', 0, '0000-00-00 00:00:00', 1);

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
(1, 1, 1, 3, 1, 2, '2025-03-27 16:01:23', 2, '2025-03-27 16:02:00', 1),
(2, 1, 1, 4, 1, 2, '2025-04-07 17:02:32', 0, '0000-00-00 00:00:00', 0),
(3, 1, 1, 5, 1, 2, '2025-04-07 17:04:53', 0, '0000-00-00 00:00:00', 1);

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
(1, 2, 1, 1, 1, '2025-03-27 15:23:58', 0, '0000-00-00 00:00:00', 1),
(2, 3, 1, 1, 0, '2025-03-27 16:01:23', 0, '0000-00-00 00:00:00', 1),
(3, 4, 1, 1, 2, '2025-04-07 17:02:32', 0, '0000-00-00 00:00:00', 0),
(4, 5, 1, 1, 2, '2025-04-07 17:04:53', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_whatsapp_messages`
--

CREATE TABLE `cmp_whatsapp_messages` (
  `id` int(11) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `agent_contact` bigint(11) NOT NULL,
  `message_type` varchar(255) NOT NULL,
  `wam_id` text NOT NULL,
  `message_body` text NOT NULL,
  `media_link` text NOT NULL,
  `message_status` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cmp_whatsapp_messages`
--

INSERT INTO `cmp_whatsapp_messages` (`id`, `agent`, `agent_contact`, `message_type`, `wam_id`, `message_body`, `media_link`, `message_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 'bot', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAERgSMjEzRjVGMURGQTRGMDVGQzE4AA==', 'check the message @Sakthi 1.11', '', 'delivered', 0, '2025-04-08 21:01:53', 0, '2025-04-09 00:00:00', 1),
(2, 'user', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAEhggMTFERUM1MDgxOEM5NzdDRDU0QTFDRkQxODFGNDBGMUQA', 'Reply form', '', 'pending', 0, '2025-04-08 17:35:32', 0, '2025-04-09 00:00:00', 1),
(3, 'user', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAEhggMDJGNzVEM0MzN0NEN0ZDOUZFNEQ1QzdBNERGN0Y5OEYA', 'Hello', '', 'pending', 0, '2025-04-08 18:04:33', 0, '2025-04-09 00:00:00', 1),
(4, 'user', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAEhggRkQxNTE4NjhCMjU1RjYwNjA3M0RGODEzNTZBNjg3MzYA', 'Hello', '', 'pending', 0, '2025-04-08 18:29:11', 0, '2025-04-09 12:40:48', 1),
(5, 'user', 919342162357, 'text', 'wamid.HBgMOTE5MzQyMTYyMzU3FQIAEhggMkExMjg0NjlBMjRGODUyREI1OTIzMkY1NUZDQjMzOUMA', 'Hi', '', 'pending', 0, '2025-04-08 18:41:00', 0, '2025-04-09 00:00:00', 1),
(6, 'user', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAEhggNjkyMUVCOTI4QTkwMEJEQjQyMEQzNDJDN0UzMjBERkYA', 'Hi', '', 'pending', 0, '2025-04-09 00:31:05', 0, '2025-04-09 00:00:00', 1),
(7, 'user', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAEhggOTZBNkVEREE1QTZEQTZFM0ZEQzZDNENCRUNDMzMyOEUA', 'New', '', 'pending', 0, '2025-04-09 00:58:18', 0, '2025-04-09 00:00:00', 1),
(8, 'user', 919342162357, 'reaction', 'wamid.HBgMOTE5MzQyMTYyMzU3FQIAEhggRDVEMThCN0RCMjBBMTZDNTI2MEEwQTM0RDEyRjI2RUUA', '', '', 'pending', 0, '2025-04-09 04:06:18', 0, '2025-04-09 00:00:00', 1),
(9, 'user', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAEhggMjgzODMzMDEzOURFQ0M2MzlDRDRCOEVDOEQ0NUIzQkMA', 'Welcome home', '', 'pending', 0, '2025-04-09 04:14:24', 0, '2025-04-09 12:45:33', 1),
(10, 'user', 919342162357, 'text', 'wamid.HBgMOTE5MzQyMTYyMzU3FQIAEhggNjBDRjVFRDU5RkNGQTVEOThBODIxRkEzMDJEQzA3QzUA', 'Check', '', 'pending', 0, '2025-04-09 05:18:11', 0, '2025-04-09 12:31:46', 1),
(11, 'bot', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAERgSNEVGRDM5QzQ5NTE4QjM1MzYxAA==', 'check the message @Sakthi 1.11', '', 'delivered', 0, '2025-04-09 09:50:54', 0, '2025-04-09 00:00:00', 1),
(12, 'bot', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAERgSRDBCNjU4NDAzNzQxNEM4RjQwAA==', 'Hi', '', 'read', 2, '2025-04-09 11:14:23', 0, '2025-04-09 00:00:00', 1),
(13, 'bot', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAERgSRjUxODFENTE4OTVCREE5NDVCAA==', 'Love Is Lust', '12121', 'read', 2, '2025-04-09 11:16:46', 0, '2025-04-09 00:00:00', 1),
(14, 'user', 917092085411, 'text', 'wamid.HBgMOTE3MDkyMDg1NDExFQIAEhggMjExNUM0MTU4N0QzMjBDRDE5QTYzM0ZGOUQ1M0JDMEQA', 'No', '', 'pending', 0, '2025-04-09 07:47:15', 0, '2025-04-09 00:00:00', 1),
(15, 'bot', 916384626418, 'text', 'wamid.HBgMOTE2Mzg0NjI2NDE4FQIAERgSMDYxNjMwM0QxRDM3OUI3RDA0AA==', 'Hlo...', '', 'failed', 2, '2025-04-09 11:49:48', 0, '2025-04-09 08:27:20', 1),
(16, 'user', 916384626418, 'text', 'wamid.HBgMOTE2Mzg0NjI2NDE4FQIAEhggOUQ0QUNEQkUzRjA0MUIwMzNBRDZGMDg2Mzg1MUE0QzMA', 'H', '', 'pending', 0, '2025-04-09 08:20:36', 0, '2025-04-09 08:27:31', 1),
(17, 'bot', 916384626418, 'text', 'wamid.HBgMOTE2Mzg0NjI2NDE4FQIAERgSREE2RDg4NDkzNTQxODRFRkFBAA==', 'Hi', '', 'read', 2, '2025-04-09 11:50:43', 0, '2025-04-09 00:00:00', 1),
(18, 'bot', 916384626418, 'text', 'wamid.HBgMOTE2Mzg0NjI2NDE4FQIAERgSQzVCNEY0NDlFNEYwMkVCMThCAA==', 'Vanakam and welcome to hermon', '', 'read', 2, '2025-04-09 11:51:06', 0, '2025-04-09 00:00:00', 1),
(19, 'bot', 916384626418, 'text', 'wamid.HBgMOTE2Mzg0NjI2NDE4FQIAERgSQzE2QTZERThDREJDNDA3MTdGAA==', 'Hai', '213132', 'delivered', 2, '2025-04-09 11:53:57', 0, '2025-04-09 12:17:39', 1),
(20, 'user', 919342162357, 'text', 'wamid.HBgMOTE5MzQyMTYyMzU3FQIAEhggNkQ2NEIxOThGQTgyQkMxNThBOUI0NUE3MDhDMjc3Q0QA', 'Hi', '', 'pending', 0, '2025-04-09 08:36:59', 0, '2025-04-09 08:43:48', 1),
(21, '', 916384626418, 'text', 'wamid.HBgMOTE2Mzg0NjI2NDE4FQIAERgSQjJDNjdBNERFQjg5MzY4MERDAA==', 'Hi', '', 'sent', 2, '2025-04-09 12:19:07', 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cmp_whatsapp_templates`
--

CREATE TABLE `cmp_whatsapp_templates` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `vendor_id` bigint(20) NOT NULL,
  `template_id` varchar(45) NOT NULL,
  `media_id` varchar(255) NOT NULL,
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

INSERT INTO `cmp_whatsapp_templates` (`id`, `uid`, `vendor_id`, `template_id`, `media_id`, `template_name`, `category`, `language`, `body_data`, `template_status`, `created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES
(1, 'ee02aa36622bc739', 2, '1450442385941119', '', 'jio_fiber_offer', 'MARKETING', 'en_US', '{\"name\":\"jio_fiber_offer\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"image\",\"example\":{\"header_handle\":[\"4:Yml6Y29udm8tYmctbG9nby5wbmc=:aW1hZ2UvcG5n:ARZu_ZQjCvqT1zMC7_MlDbwJb8iIYKF04rJ9y1H5KYUxuCHe3sNQS-MNlFIQh2bKszdi_uSjMlw8gDVbtG2-kpPzaY4AiACSbWvOFkog2T8M_A:e:1743162773:685254847278606:769089702:ARbcpojLe1P1wUkudcU\"]}},{\"type\":\"BODY\",\"text\":\"\\ud83d\\ude14 \\u0b8f\\u0ba9\\u0bcd \\u0ba8\\u0bae\\u0bcd\\u0baa\\u0ba4\\u0bcd\\u0ba4\\u0b95\\u0bbe\\u0ba4 Wi-Fi \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd DTH \\u0b90\\u0baa\\u0bcd \\u0baa\\u0baf\\u0ba9\\u0bcd\\u0baa\\u0b9f\\u0bc1\\u0ba4\\u0bcd\\u0ba4 \\u0bb5\\u0bc7\\u0ba3\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd?\\u00a0\\n\\n\\ud83e\\udd29 JioAirFiber \\u0b87\\u0bb2\\u0bcd \\u0ba8\\u0bc0\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd \\u0baa\\u0bc6\\u0bb1\\u0bc1\\u0bb5\\u0bc0\\u0bb0\\u0bcd\\u0b95\\u0bb3\\u0bcd\\n\\u00a0800+ TV \\u0b9a\\u0bc7\\u0ba9\\u0bb2\\u0bcd\\u0b95\\u0bb3\\u0bcd | 15+ OTT \\u0b86\\u0baa\\u0bcd\\u0bb8\\u0bcd | 1 Gbps Wi-Fi\\u00a0\\n\\n\\ud83d\\udcb5 \\u0ba4\\u0bbf\\u0b9f\\u0bcd\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd \\u0bae\\u0bbe\\u0ba4\\u0ba4\\u0bcd\\u0ba4\\u0bbf\\u0bb1\\u0bcd\\u0b95\\u0bc1 \\u0bb5\\u0bc6\\u0bb1\\u0bc1\\u0bae\\u0bcd \\u20b9599 \\u0bae\\u0bc1\\u0ba4\\u0bb2\\u0bcd \\u0ba4\\u0bca\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bc1\\u0b95\\u0bbf\\u0ba9\\u0bcd\\u0bb1\\u0ba9\\n\\u2705\\ud83d\\uddd3\\ufe0f JioAirFiber \\u0b90 \\u0b87\\u0baa\\u0bcd\\u0baa\\u0bcb\\u0ba4\\u0bc7 \\u0baa\\u0ba4\\u0bbf\\u0bb5\\u0bc1 \\u0b9a\\u0bc6\\u0baf\\u0bcd\\u0ba4\\u0bc1 \\u20b91000 \\u0b95\\u0bc7\\u0bb7\\u0bcd\\u0baa\\u0bc7\\u0b95\\u0bcd\\u0b95\\u0bc8 \\u00a0\\u0baa\\u0bc6\\u0bb1\\u0bc1\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd\"},{\"type\":\"FOOTER\",\"text\":\"Have a nice day...!\"}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-24 17:23:39', 0, '0000-00-00 00:00:00', 1),
(2, '095c733a8cb42ff8', 2, '28857509373895493', '', 'jio_fiber_offer2', 'MARKETING', 'en_US', '{\"name\":\"jio_fiber_offer2\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"JioAirFiber Freedom Offer {{1}}\",\"example\":{\"header_text\":[\"Jio Customer\"]}},{\"type\":\"BODY\",\"text\":\"Installation \\u20b91000 FREE*\\ud83c\\udfe0 Join our family of 1Cr+ Happy Homes & enjoy:800+ TV Channels | 15+ OTT Plans | 1Gbps Wi-Fi\\u23f0 {{1}}\",\"example\":{\"body_text\":[[\"Limited Time Offer! Act now!*\"]]}},{\"type\":\"FOOTER\",\"text\":\"Have a nice day...!\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"QUICK_REPLY\",\"text\":\"click here\"},{\"type\":\"PHONE_NUMBER\",\"text\":\"call me\",\"phone_number\":\"919488793821\"}]}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-25 11:28:45', 0, '0000-00-00 00:00:00', 1),
(3, 'f9e27865ecc9b749', 2, '2408866722845845', '', 'jio_fiber_offer3', 'MARKETING', 'en_US', '{\"name\":\"jio_fiber_offer3\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"JioAirFiber Freedom Offer\"},{\"type\":\"BODY\",\"text\":\"\\ud83d\\udce2\\ud83d\\udcb5 Installation \\u20b91000 FREE*\\ud83c\\udfe0 Join our family of 1Cr+ Happy Homes &{{1}}\\u00a0Limited Time Offer! Act now!*\\ud83e\\udd29 JioAirFiber \\u0b87\\u0bb2\\u0bcd \\u0ba8\\u0bc0\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd \\u0baa\\u0bc6\\u0bb1\\u0bc1\\u0bb5\\u0bc0\\u0bb0\\u0bcd\\u0b95\\u0bb3\\u0bcd{{2}}\\u0ba4\\u0bbf\\u0b9f\\u0bcd\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd \\u0bae\\u0bbe\\u0ba4\\u0ba4\\u0bcd\\u0ba4\\u0bbf\\u0bb1\\u0bcd\\u0b95\\u0bc1 \\u0bb5\\u0bc6\\u0bb1\\u0bc1\\u0bae\\u0bcd{{3}}\\u0bae\\u0bc1\\u0ba4\\u0bb2\\u0bcd \\u0ba4\\u0bca\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bc1\\u0b95\\u0bbf\\u0ba9\\u0bcd\\u0bb1\\u0ba9.\\u2705\\ud83d\\uddd3\\ufe0f JioAirFiber \\u0b90 \\u0b87\\u0baa\\u0bcd\\u0baa\\u0bcb\\u0ba4\\u0bc7 \\u0baa\\u0ba4\\u0bbf\\u0bb5\\u0bc1 \\u0b9a\\u0bc6\\u0baf\\u0bcd\\u0ba4\\u0bc1 \\u20b91000 \\u0b95\\u0bc7\\u0bb7\\u0bcd\\u0baa\\u0bc7\\u0b95\\u0bcd\\u0b95\\u0bc8 \\u00a0\\u0baa\\u0bc6\\u0bb1\\u0bc1\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bcd\",\"example\":{\"body_text\":[[\"enjoy:800+ TV Channels | 15+ OTT Plans | 1Gbps Wi-Fi\\u23f0\",\"800+ TV \\u0b9a\\u0bc7\\u0ba9\\u0bb2\\u0bcd\\u0b95\\u0bb3\\u0bcd | 15+ OTT \\u0b86\\u0baa\\u0bcd\\u0bb8\\u0bcd | 1 Gbps Wi-Fi\",\"\\u20b9599\"]]}},{\"type\":\"FOOTER\",\"text\":\"Have a nice day...!\"}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-25 12:33:14', 0, '0000-00-00 00:00:00', 1),
(4, '8a01e397654e496a', 2, '', '', 'seasonal_promotion', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"Our {{1}} is on!\",\"example\":{\"header_text\":[\"Summer Sale\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25OFF\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"QUICK_REPLY\",\"text\":\"Unsubscribe from Promos\"}]}],\"allow_category_change\":false}', '', 7, '2025-03-25 14:49:19', 0, '0000-00-00 00:00:00', 1),
(5, 'f2948346dc1328a0', 2, '649494504336268', '', 'seasonal_promotion', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"Our {{1}} is on!\",\"example\":{\"header_text\":[\"Summer Sale\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25OFF\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"QUICK_REPLY\",\"text\":\"Unsubscribe from Promos\"}]}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-25 14:59:54', 0, '0000-00-00 00:00:00', 1),
(6, '52dab0a00248aede', 2, '', '', 'hi template', 'MARKETING', 'en', '{\"name\":\"hi template\",\"language\":\"en\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"hi\"},{\"type\":\"BODY\",\"text\":\"<b>hi<\\/b> everyone\"}],\"allow_category_change\":false}', '', 7, '2025-03-26 10:34:47', 0, '0000-00-00 00:00:00', 1),
(7, '8aadf6660d09ba5b', 2, '1812304893051020', '', 'hi_template', 'MARKETING', 'en', '{\"name\":\"hi_template\",\"language\":\"en\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"hi\"},{\"type\":\"BODY\",\"text\":\"<b>hi<\\/b> everyone\"}],\"allow_category_change\":false}', 'PENDING', 7, '2025-03-26 10:35:31', 0, '0000-00-00 00:00:00', 1),
(8, 'bb0fceb3b0120b1b', 0, '', '', '', '', '', '{\"name\":null,\"language\":null,\"category\":null,\"components\":null,\"allow_category_change\":false}', '', 1, '2025-03-26 20:30:04', 0, '0000-00-00 00:00:00', 1),
(9, '8a1889bdfb29b4a9', 0, '', '', 'seasonal_promotion_new7', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_new7\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"IMAGE\",\"example\":{\"header_handle\":[\"4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARaJ8Ato-yvkOUKh0GNxqGOhNwCWjH3uhfLhCTqynmmqdTQL-GFTMWV9S2zTU0C7H-ogwyPL3PbAjE1OXFRoXnM71D8hNpFp10NVHyX5GIvhDQ:e:1742285668:2499583603706991:61573534486887:ARZO04gcn7EYVzppPWM\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYAEKdUFFHqrLJLjYUcavYudu5-ff6HT1wSuV2gIVB-_29cn2nk9MfIUPd5uZiR3DvKNmF3W0vbuALyY8TJnSmfWsmRH_ESRkQJKnGJ4eHtjA:e:1742285669:2499583603706991:61573534486887:ARbqKAsrv3IKh2BB43E\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARb-kJTKaqtEVc1vKabum0r0CD8UitEgf0YyYTYHai4pUY2T4-D7azSEBLQm65L0XMUPr0OpHfNrvcQVBdj2w9_CjfLitGpv3D1purQkPJqXLA:e:1742285669:2499583603706991:61573534486887:ARbvl7jO-bkiWgpUygI\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARZ6kyNUPYJ88ruoIS1dUumpKs33FL0ZtymD3v50pAGPh2pUcjeq4iH0qH0amW1q2jKNreiF7b4OYedsFNdpTPqpPXk6a_-pergBGA2gFjNDPA:e:1742285669:2499583603706991:61573534486887:ARaMPYnZe07cFi6gOQg\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARZ9XKxPwns_gFMdFNJ3PVvNuO4jy5ZNW3_HvLz51hfBJnIHbt_DHI7pW_iZZ5sgs59zHicxjPNctHvSelDrYtMPj35o465pASKeb2B62eI0oQ:e:1742285669:2499583603706991:61573534486887:ARY4VmFiRngkXtt_RA8\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARa_uPpnNaG9h2JYDvd1LtImiXayi1ptjABUuU_8q-6X84RkrNK-DFiq6-gzcsaMz4JCSxJ-RiEObQp86k__tjM67Oa05_Mnpcz4KdVP_JFRmg:e:1742285669:2499583603706991:61573534486887:ARZRJsOyAdbkmEaML00\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARbbwrCWvDEPvC1B1DZFHFa3hLzk4Mf-ixU3JMYKOo1CfGt6RZ3plmE8T6ldjwu6Qah9_cF3n69mIuboqhic8DVewJJeQFnwDXBjN3sWHiHmEg:e:1742285669:2499583603706991:61573534486887:ARbyUbfUaEfRKYUmeNs\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYThaeNbMgxDCPwMl4SToN19Zj3miAiRZH0ZmKuKoXyXnTG8Ymgx4b6-RWIFNnojpCkx9cOtnRbOm3D6lOzPmiplUAY-v5ita44DP8VrixFTA:e:1742285669:2499583603706991:61573534486887:ARZd6DQP-a6EdL4uc0c\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARa56tP2Y0dUyq9vZjxgtyyf8IJSev9fqZTqCQh-7-PPlfhjPk-MDVp49SdR3rCZeSqShBCs-0HNAdKa5O3l8zpbUKE68VLgKiaG2Z7m8czswg:e:1742285669:2499583603706991:61573534486887:ARa9nirwrSGYu3ba9gI\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARbUTx6RQg3CF6vnxak7xKEoNPyy4ajMpD-Uaqz5F_T9X0QIa4QFZrmsuTLly2jc-4OztSEYK6H6QqoupZek88RjVA7CBERVPn4TcGjGI1SzCA:e:1742285669:2499583603706991:61573534486887:ARZMY2uFw1pXT6l-gKU\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through the end of August and use code 25OFF to get 25% off of all merchandise.\"},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', '', 1, '2025-03-26 20:30:12', 0, '0000-00-00 00:00:00', 1),
(10, '027273d1a6acd1bb', 0, '589178470823345', '', 'seasonal_promotion_new7', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_new7\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"IMAGE\",\"example\":{\"header_handle\":[\"4:YXZhdGVyLnBuZw==:aW1hZ2UvcG5n:ARYvOEvgfu4wfuAbqH76wlWuSEyOPFALx6wwWWlqnjR7yWHWTo7jD3GT8e4_4wSrafxYAFTcXsQS45ezwAV0cpDW8-NQPaCZ_fCuTo1KKi8sKQ:e:1743346831:4019595151630893:61574778745045:ARY3gJztDxwDoaYGao8\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through the end of August and use code 25OFF to get 25% off of all merchandise.\"},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', 'PENDING', 1, '2025-03-26 20:30:55', 0, '0000-00-00 00:00:00', 1),
(11, 'd6a5d7768fbfd538', 0, '', '', 'seasonal_promotion_new7', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_new7\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"IMAGE\",\"example\":{\"header_handle\":[\"4:YXZhdGVyLnBuZw==:aW1hZ2UvcG5n:ARYvOEvgfu4wfuAbqH76wlWuSEyOPFALx6wwWWlqnjR7yWHWTo7jD3GT8e4_4wSrafxYAFTcXsQS45ezwAV0cpDW8-NQPaCZ_fCuTo1KKi8sKQ:e:1743346831:4019595151630893:61574778745045:ARY3gJztDxwDoaYGao8\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"header_text\":[\"the end of August\",\"25OFF\",\"25%\"]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', '', 1, '2025-03-26 20:41:28', 0, '0000-00-00 00:00:00', 1),
(12, '100cc4aac9be552c', 0, '', '', 'seasonal_promotion_new7', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_new7\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"IMAGE\",\"example\":{\"header_handle\":[\"4:YXZhdGVyLnBuZw==:aW1hZ2UvcG5n:ARYvOEvgfu4wfuAbqH76wlWuSEyOPFALx6wwWWlqnjR7yWHWTo7jD3GT8e4_4wSrafxYAFTcXsQS45ezwAV0cpDW8-NQPaCZ_fCuTo1KKi8sKQ:e:1743346831:4019595151630893:61574778745045:ARY3gJztDxwDoaYGao8\"]}},{\"type\":\"BODY\",\"text\":\"Your {{order_id}}, is ready {{customer_name}}.\",\"example\":{\"header_text_named_params\":[{\"param_name\":\"order_id\",\"example\":\"335628\"},{\"param_name\":\"customer_name\",\"example\":\"Shiva\"}]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', '', 1, '2025-03-26 20:42:44', 0, '0000-00-00 00:00:00', 1),
(13, 'ed6b7fb157a2b6ac', 0, '', '', 'seasonal_promotion_variable', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"IMAGE\",\"example\":{\"header_handle\":[\"4:YXZhdGVyLnBuZw==:aW1hZ2UvcG5n:ARYvOEvgfu4wfuAbqH76wlWuSEyOPFALx6wwWWlqnjR7yWHWTo7jD3GT8e4_4wSrafxYAFTcXsQS45ezwAV0cpDW8-NQPaCZ_fCuTo1KKi8sKQ:e:1743346831:4019595151630893:61574778745045:ARY3gJztDxwDoaYGao8\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[\"the end of August\",\"25OFF\",\"25%\"]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', '', 1, '2025-03-26 20:44:01', 0, '0000-00-00 00:00:00', 1),
(14, '9132b8f192bf6117', 0, '9558685704170591', '', 'seasonal_promotion_variable', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"IMAGE\",\"example\":{\"header_handle\":[\"4:YXZhdGVyLnBuZw==:aW1hZ2UvcG5n:ARYvOEvgfu4wfuAbqH76wlWuSEyOPFALx6wwWWlqnjR7yWHWTo7jD3GT8e4_4wSrafxYAFTcXsQS45ezwAV0cpDW8-NQPaCZ_fCuTo1KKi8sKQ:e:1743346831:4019595151630893:61574778745045:ARY3gJztDxwDoaYGao8\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25OFF\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', 'PENDING', 1, '2025-03-26 20:46:15', 0, '0000-00-00 00:00:00', 1),
(15, 'a46545ac9d33e9fb', 1, '', '', '', '', '', '{\"name\":\"\",\"language\":\"\",\"category\":\"\",\"components\":[{\"type\":\"HEADER\",\"format\":\"image\",\"example\":{\"header_handle\":[\"4:bGlua2VkX2luX2JnLmpwZWc=:aW1hZ2UvanBlZw==:ARYa9YmjorGXya_jJ0K4gvyodLdCVaoY5yFTKzTy9kj6-YAyKm-UqgjkXESLqHZdvpN0pyPoTT15m5d4WgBzc1h1OyrMeb7YvGHzAqUKqGe-4A:e:1743423356:4019595151630893:61574778745045:ARb4M6lZSsAc7nhg4ZI\"]}},{\"type\":\"BODY\",\"text\":\"\"}],\"allow_category_change\":false}', '', 2, '2025-03-27 17:46:14', 0, '0000-00-00 00:00:00', 1),
(16, '9d0da4c55b30d2ae', 1, '1177390207349836', '2023050174772539', 'seasonal_promotion_variable_2', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable_2\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"image\",\"example\":{\"header_handle\":[\"4:MjE1MDcwOTgxOC5qcGc=:aW1hZ2UvanBlZw==:ARZIKsRi-H6fNUEmhy5RJ3TkUXgvmZdIroZPTdzpqOjfeiyZp74Y6MW7nVcS91vsOw77vF-s1wSR2QrRIJ7qTvKMkpM_djMIljP24oFVU_YB4Q:e:1743433611:4019595151630893:61574778745045:ARYFMCHMkefKqB1GCPg\\n4:MjE1MDcwOTgxOC5qcGc=:aW1hZ2UvanBlZw==:ARY6kYOTm_g8S9KBYEhqh1MHhTbiztZBfgDFsoIH-Z7P6NTbQIHYKCXGsB1sJsCGRT7pUZpmFROcLqz95cIqwIoXmLW3kl1cq3JvkoRBlOncEg:e:1743433611:4019595151630893:61574778745045:ARYBaWdfayRY5WAqvEc\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25 off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', 'APPROVED', 2, '2025-03-27 20:37:52', 0, '0000-00-00 00:00:00', 1),
(17, '717e54cf129b7f7c', 1, '', '1032683185576909', 'seasonal_promotion_variable_1', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable_1\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"video\",\"example\":{\"header_handle\":[\"4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARaYZQpImJn_nZZZDr3zr99hMx6uG_H6QH2GDYvzEG1rjxFU_ciG4OfWuIXKsyD3TmOPyQN3Yo202USpC2E7CX-1IunG24pnJA4R8koUY0u-zQ:e:1743436024:4019595151630893:61574778745045:ARZvOamUH_ik6jJM2RU\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARajFHG7rwhBCnqYqvvt4BdQlymFwu-gQ0Z7XvZygYQFyGUGJ24rjKssYIZtQUDmTh1y1SjhD8YbrewJmXVWPM1muUurunpePeOT18HOtYvS8A:e:1743436024:4019595151630893:61574778745045:ARZIOqjyTz0sWh1vnVY\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARbKXT921m0Mo2wSm1RcOt8T5Q6e7Twg6pbgM04oq2U8tYMQ3NXcBMKmgBl1UOG__A7H1ctlMJ-hEDTMlxmvD5PXVsyPG2_cjdn8KgO08IMj5g:e:1743436024:4019595151630893:61574778745045:ARaSU0ZXxqLtlsYWU_Q\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYFYc3tPLO3IPYiT5th8cAGcXayH4_eMZd3fZ50xn5iCqRBNye6A8n68bE3p8VIfKN9mv-LeXvHDjdSXTlK6NhwIR-16hBxuomDFFttbkyg3A:e:1743436024:4019595151630893:61574778745045:ARavuoud2I9J-AfEs70\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYIky4mMUZ_3m3miCz5Z75kTBLpGFuBJhy680KVb6yUFGLfHTSyQl-3RfhoN-BxRIVPu8LFTltRq6N0E1Q0rH9yQIZel1couBVIBEsp8v6AuQ:e:1743436024:4019595151630893:61574778745045:ARavGlqjPS-xiqYyn-M\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARZRXpzB1OV30hu1qKDKE5smm-_9UfnGG2xJAjOzQdtPUTzrHfXHJ5YGhBidYZCETy-yy5kCNETCO407DrTgKaYvkJRYqGFnuQs6vk0uMGZcEA:e:1743436024:4019595151630893:61574778745045:ARapTlBmQPQ4Qe0cJe8\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYN7vkK_pjDADsgYuaUaH0VOrNChWE-3LhLxA20ZapSBkpppjoRZ-9tcRiLlkY7m1iyEu6f8n-z-9IpubGaZ-ayWPjNgDPQR0ZPJN5XrLfyHQ:e:1743436024:4019595151630893:61574778745045:ARZmUZnXXkdbVqtFXRM\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYspcVZ08PNXsdbC2imbF6E7yFnx-VOlB4JMNFY6SocRxtJmY_ZjfQUQ3wHi2Pf2jzf9PtPEg8qsf-NbtUZzQHUk3QfZjLRo4b_UtyFBO1rwQ:e:1743436024:4019595151630893:61574778745045:ARYvr9onFWFLFMSaZgs\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYwfmvXhDwf9L5ICtiphWQ52hTuurJ9Bcb779qQL0Bd8T4ONziGhiREcPUT_sw9hvuQN3LAl--HVnsChkHOAbO40RVaqEOOLWfDh6754AOUOg:e:1743436024:4019595151630893:61574778745045:ARZMG2W1hDMyN3eLCyc\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARa7BLLsi2Jf8sQLa9-11i4jY-jrTPM0lqyKLWjZDIA9Rh92Zij6qst1kEKxPCUpBuwghJ90yGR4Yd5FS2ahYFuHT7L8IPMYze8qba4_XNckpg:e:1743436024:4019595151630893:61574778745045:ARZF8f62prRmLhUpxRc\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', '', 2, '2025-03-27 21:19:11', 0, '0000-00-00 00:00:00', 1),
(18, 'd167f7a76b7485df', 1, '685736130627965', '1032683185576909', 'seasonal_promotion_variable_4', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable_4\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"video\",\"example\":{\"header_handle\":[\"4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARaYZQpImJn_nZZZDr3zr99hMx6uG_H6QH2GDYvzEG1rjxFU_ciG4OfWuIXKsyD3TmOPyQN3Yo202USpC2E7CX-1IunG24pnJA4R8koUY0u-zQ:e:1743436024:4019595151630893:61574778745045:ARZvOamUH_ik6jJM2RU\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARajFHG7rwhBCnqYqvvt4BdQlymFwu-gQ0Z7XvZygYQFyGUGJ24rjKssYIZtQUDmTh1y1SjhD8YbrewJmXVWPM1muUurunpePeOT18HOtYvS8A:e:1743436024:4019595151630893:61574778745045:ARZIOqjyTz0sWh1vnVY\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARbKXT921m0Mo2wSm1RcOt8T5Q6e7Twg6pbgM04oq2U8tYMQ3NXcBMKmgBl1UOG__A7H1ctlMJ-hEDTMlxmvD5PXVsyPG2_cjdn8KgO08IMj5g:e:1743436024:4019595151630893:61574778745045:ARaSU0ZXxqLtlsYWU_Q\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYFYc3tPLO3IPYiT5th8cAGcXayH4_eMZd3fZ50xn5iCqRBNye6A8n68bE3p8VIfKN9mv-LeXvHDjdSXTlK6NhwIR-16hBxuomDFFttbkyg3A:e:1743436024:4019595151630893:61574778745045:ARavuoud2I9J-AfEs70\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYIky4mMUZ_3m3miCz5Z75kTBLpGFuBJhy680KVb6yUFGLfHTSyQl-3RfhoN-BxRIVPu8LFTltRq6N0E1Q0rH9yQIZel1couBVIBEsp8v6AuQ:e:1743436024:4019595151630893:61574778745045:ARavGlqjPS-xiqYyn-M\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARZRXpzB1OV30hu1qKDKE5smm-_9UfnGG2xJAjOzQdtPUTzrHfXHJ5YGhBidYZCETy-yy5kCNETCO407DrTgKaYvkJRYqGFnuQs6vk0uMGZcEA:e:1743436024:4019595151630893:61574778745045:ARapTlBmQPQ4Qe0cJe8\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYN7vkK_pjDADsgYuaUaH0VOrNChWE-3LhLxA20ZapSBkpppjoRZ-9tcRiLlkY7m1iyEu6f8n-z-9IpubGaZ-ayWPjNgDPQR0ZPJN5XrLfyHQ:e:1743436024:4019595151630893:61574778745045:ARZmUZnXXkdbVqtFXRM\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYspcVZ08PNXsdbC2imbF6E7yFnx-VOlB4JMNFY6SocRxtJmY_ZjfQUQ3wHi2Pf2jzf9PtPEg8qsf-NbtUZzQHUk3QfZjLRo4b_UtyFBO1rwQ:e:1743436024:4019595151630893:61574778745045:ARYvr9onFWFLFMSaZgs\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARYwfmvXhDwf9L5ICtiphWQ52hTuurJ9Bcb779qQL0Bd8T4ONziGhiREcPUT_sw9hvuQN3LAl--HVnsChkHOAbO40RVaqEOOLWfDh6754AOUOg:e:1743436024:4019595151630893:61574778745045:ARZMG2W1hDMyN3eLCyc\\n4:ZmlsZV9leGFtcGxlX01QNF8xMjgwXzEwTUcubXA0:dmlkZW8vbXA0:ARa7BLLsi2Jf8sQLa9-11i4jY-jrTPM0lqyKLWjZDIA9Rh92Zij6qst1kEKxPCUpBuwghJ90yGR4Yd5FS2ahYFuHT7L8IPMYze8qba4_XNckpg:e:1743436024:4019595151630893:61574778745045:ARZF8f62prRmLhUpxRc\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', 'PENDING', 2, '2025-03-27 21:19:34', 0, '0000-00-00 00:00:00', 1),
(19, 'de5e416b18ed95e1', 1, '663936106548436', '', 'seasonal_promotion_variable_5', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable_5\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"seasonal_promotion_variable_5\"},{\"type\":\"BODY\",\"text\":\"*Shop* now through {{1}} and _use code_ {{2}} to get {{3}} off of all ~merchandise~.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', 'PENDING', 2, '2025-03-27 21:24:08', 0, '0000-00-00 00:00:00', 1),
(20, '43d6f54be0fdee0c', 1, '1630179377607327', '', 'hai_template', 'MARKETING', 'en_US', '{\"name\":\"hai_template\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"Hai\"},{\"type\":\"BODY\",\"text\":\"*Hai* everyone _offer_ is going in our ~shop~...!\"},{\"type\":\"FOOTER\",\"text\":\"Have a nice day...!\"}],\"allow_category_change\":false}', 'PENDING', 2, '2025-03-27 21:33:59', 0, '0000-00-00 00:00:00', 1),
(21, 'fb05e37fac757796', 1, '', '', 'seasonal_promotion_variable_6', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable_6\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"Our {{1}} sale is on!\",\"example\":{\"header_text\":[\"Summer\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"PHONE_NUMBER\",\"text\":\"Call me\",\"phone_number\":\"6384626418\"}]}],\"allow_category_change\":false}', '', 2, '2025-03-28 11:19:59', 0, '0000-00-00 00:00:00', 1),
(22, '5dff1e76bb1e5e72', 1, '1850196415800230', '', 'seasonal_promotion_variable_6', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable_6\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"Our {{1}} sale is on!\",\"example\":{\"header_text\":[\"Summer\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"PHONE_NUMBER\",\"text\":\"Call me\",\"phone_number\":\"916384626418\"}]}],\"allow_category_change\":false}', 'PENDING', 2, '2025-03-28 11:20:15', 0, '0000-00-00 00:00:00', 1),
(23, '0d9bbb76b396605b', 1, '975616191217740', '', 'seasonal_promotion_variable_7', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable_7\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"Our {{1}} sale is on\",\"example\":{\"header_text\":[\"Summer\"]}},{\"type\":\"BODY\",\"text\":\"*Shop* now through {{1}} and _use_ code {{2}} to ~get~ {{3}} off of all *merchandise.*\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"QUICK_REPLY\",\"text\":\"Subscribe now\"},{\"type\":\"URL\",\"text\":\"Learn more\",\"url\":\"https:\\/\\/en.wikipedia.org\\/wiki\\/WhatsApp\"}]}],\"allow_category_change\":false}', 'PENDING', 2, '2025-03-28 11:50:23', 0, '0000-00-00 00:00:00', 1),
(24, 'cc3807c550d852b4', 1, '1012718800198671', '', 'seasonal_promotion_variable_8', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promotion_variable_8\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"Our {{1}} sale is on\",\"example\":{\"header_text\":[\"Summer\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"QUICK_REPLY\",\"text\":\"Subscribe now\"},{\"type\":\"PHONE_NUMBER\",\"text\":\"Call now\",\"phone_number\":\"916384626418\"},{\"type\":\"URL\",\"text\":\"Learn more\",\"url\":\"https:\\/\\/en.wikipedia.org\\/wiki\\/WhatsApp\",\"example\":[\"https:\\/\\/developers.facebook.com\\/\"]}]}],\"allow_category_change\":false}', 'PENDING', 2, '2025-03-28 11:58:05', 0, '0000-00-00 00:00:00', 1),
(25, '06ead0d0b614d9ad', 1, '506455749209049', '9359046190866229', 'seasonal_promos_9', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promos_9\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"},{\"type\":\"BUTTONS\",\"buttons\":[{\"type\":\"PHONE_NUMBER\",\"text\":\"Contact\",\"phone_number\":\"+916384626418\"}]}],\"allow_category_change\":false}', 'PENDING', 2, '2025-03-28 12:58:53', 0, '0000-00-00 00:00:00', 0),
(26, '9b3b3d6e20418811', 1, '9609188649199824', '669708175602202', 'seasonal_promos_10', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promos_10\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', 'PENDING', 2, '2025-03-28 13:05:11', 0, '0000-00-00 00:00:00', 0),
(27, 'ed3806f0dc8756d8', 1, '', '1314152026512284', 'seasonal_promos_9', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promos_9\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"document\",\"example\":{\"header_handle\":[\"4:ZmlsZS1zYW1wbGVfMTUwa0IucGRm:YXBwbGljYXRpb24vcGRm:ARY-47uRH12IdRL0z_T6f-muFOZHYB-E1LQKTRGLdkZVPWvBxsy-bKHX9ZlAptr-cQr2AzD2KKoss8j6lNVMC0sisJLgJCvA2FzhcUMmsbmFFQ:e:1743493121:4019595151630893:61574778745045:ARZ9Mbjv_3eIlw6FykA\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', '', 2, '2025-03-28 13:09:09', 0, '0000-00-00 00:00:00', 1),
(28, 'ed8fe285210c7568', 1, '1678790776052795', '1314152026512284', 'seasonal_promos_9', 'MARKETING', 'en_GB', '{\"name\":\"seasonal_promos_9\",\"language\":\"en_GB\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"document\",\"example\":{\"header_handle\":[\"4:ZmlsZS1zYW1wbGVfMTUwa0IucGRm:YXBwbGljYXRpb24vcGRm:ARY-47uRH12IdRL0z_T6f-muFOZHYB-E1LQKTRGLdkZVPWvBxsy-bKHX9ZlAptr-cQr2AzD2KKoss8j6lNVMC0sisJLgJCvA2FzhcUMmsbmFFQ:e:1743493121:4019595151630893:61574778745045:ARZ9Mbjv_3eIlw6FykA\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', 'PENDING', 2, '2025-03-28 13:09:35', 0, '0000-00-00 00:00:00', 1),
(29, '95744f149a4055a1', 1, '', '1312682326675873', 'seasonal_promos_word', 'MARKETING', 'en_US', '{\"name\":\"seasonal_promos_word\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"document\",\"example\":{\"header_handle\":[\"4:ZmlsZS1zYW1wbGVfMTAwa0IuZG9j:YXBwbGljYXRpb24vbXN3b3Jk:ARYPh-cr7Y9ftGo-XImEK9leScHTgFERaGQTPpblqfCWwARhneMxfV6MRxIT6KACgI7NYK85jq3L3nQ54pnaxLKYd7VpOIBJMrauwJ9vEOBFvg:e:1743493286:4019595151630893:61574778745045:ARZPxEzqgwS3UHoJ3LE\"]}},{\"type\":\"BODY\",\"text\":\"Shop now through {{1}} and use code {{2}} to get {{3}} off of all merchandise.\",\"example\":{\"body_text\":[[\"the end of August\",\"25off\",\"25%\"]]}},{\"type\":\"FOOTER\",\"text\":\"Use the buttons below to manage your marketing subscriptions\"}],\"allow_category_change\":false}', '', 2, '2025-03-28 13:11:54', 0, '0000-00-00 00:00:00', 1),
(30, 'b7cb0856bc9306f6', 1, '571608491966051', '', 'welcome', 'MARKETING', 'en_US', '{\"name\":\"welcome\",\"language\":\"en_US\",\"category\":\"MARKETING\",\"components\":[{\"type\":\"HEADER\",\"format\":\"TEXT\",\"text\":\"welcome\"},{\"type\":\"BODY\",\"text\":\"welcome\"},{\"type\":\"FOOTER\",\"text\":\"welcome\"}],\"allow_category_change\":false}', 'PENDING', 2, '2025-04-08 11:24:06', 0, '0000-00-00 00:00:00', 1);

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
-- Indexes for table `cmp_mst_timezone`
--
ALTER TABLE `cmp_mst_timezone`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `cmp_whatsapp_messages`
--
ALTER TABLE `cmp_whatsapp_messages`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cmp_campaign_contact`
--
ALTER TABLE `cmp_campaign_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cmp_campaign_variable_mapping`
--
ALTER TABLE `cmp_campaign_variable_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cmp_contact`
--
ALTER TABLE `cmp_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cmp_group_contact`
--
ALTER TABLE `cmp_group_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
-- AUTO_INCREMENT for table `cmp_mst_timezone`
--
ALTER TABLE `cmp_mst_timezone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cmp_superadmin_vendor_login_log`
--
ALTER TABLE `cmp_superadmin_vendor_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cmp_users`
--
ALTER TABLE `cmp_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cmp_user_login_log`
--
ALTER TABLE `cmp_user_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `cmp_user_privilege_mapping`
--
ALTER TABLE `cmp_user_privilege_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `cmp_user_role_mapping`
--
ALTER TABLE `cmp_user_role_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cmp_vendor`
--
ALTER TABLE `cmp_vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cmp_vendor_store_mapping`
--
ALTER TABLE `cmp_vendor_store_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cmp_vendor_store_staff_mapping`
--
ALTER TABLE `cmp_vendor_store_staff_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cmp_vendor_user_mapping`
--
ALTER TABLE `cmp_vendor_user_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cmp_whatsapp_messages`
--
ALTER TABLE `cmp_whatsapp_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `cmp_whatsapp_templates`
--
ALTER TABLE `cmp_whatsapp_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `cmp_whatsapp_template_languages`
--
ALTER TABLE `cmp_whatsapp_template_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
