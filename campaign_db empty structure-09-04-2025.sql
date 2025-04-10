-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 01:22 PM
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
(1, '84028eb3810f6ebd', 'Super', 'Admin', 'superadmin', 'superadmin@gmail.com', 'f64b66246dbe8e3b57dcb1538aaf511eb92cfc2400381af2f3136feb67ac38d2', 123456789, 1, 0, '2025-03-05 20:26:13', 0, '0000-00-00 00:00:00', 1);

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
(1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsIm5hbWUiOm51bGwsImV4cCI6MzYwMH0.6PCnhwv1GRWR9vu2zf8j8Bxs3XQCCAUkZB7OnAllu8', '2025-04-09 13:21:58', '2025-04-09 13:21:58', '2025-04-09 13:21:58', '0000-00-00 00:00:00.000000', 1, 1);

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
(1, 1, 1, 1, 1, '2025-04-09 13:21:06', 0, '2025-04-09 13:20:56', 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_campaign_contact`
--
ALTER TABLE `cmp_campaign_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_campaign_variable_mapping`
--
ALTER TABLE `cmp_campaign_variable_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_contact`
--
ALTER TABLE `cmp_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_group_contact`
--
ALTER TABLE `cmp_group_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_group_contact_mapping`
--
ALTER TABLE `cmp_group_contact_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_superadmin_vendor_login_log`
--
ALTER TABLE `cmp_superadmin_vendor_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_users`
--
ALTER TABLE `cmp_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cmp_user_login_log`
--
ALTER TABLE `cmp_user_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cmp_user_privilege_mapping`
--
ALTER TABLE `cmp_user_privilege_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_user_role_mapping`
--
ALTER TABLE `cmp_user_role_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cmp_vendor`
--
ALTER TABLE `cmp_vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_vendor_store_mapping`
--
ALTER TABLE `cmp_vendor_store_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_vendor_store_staff_mapping`
--
ALTER TABLE `cmp_vendor_store_staff_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_vendor_user_mapping`
--
ALTER TABLE `cmp_vendor_user_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_whatsapp_messages`
--
ALTER TABLE `cmp_whatsapp_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_whatsapp_templates`
--
ALTER TABLE `cmp_whatsapp_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cmp_whatsapp_template_languages`
--
ALTER TABLE `cmp_whatsapp_template_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
