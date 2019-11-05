-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 05 2019 г., 16:00
-- Версия сервера: 10.1.9-MariaDB
-- Версия PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dashboard`
--

-- --------------------------------------------------------

--
-- Структура таблицы `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `link` varchar(512) COLLATE utf8_unicode_ci DEFAULT '0',
  `date_added` datetime NOT NULL,
  `position` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` longtext COLLATE utf8_unicode_ci,
  `date_from` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_to` datetime DEFAULT CURRENT_TIMESTAMP,
  `clicks` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `banner`
--

INSERT INTO `banner` (`id`, `title`, `image`, `link`, `date_added`, `position`, `code`, `date_from`, `date_to`, `clicks`) VALUES
(6, 'По умолчанию вверху страницы', '41314697.png', NULL, '2019-08-27 11:29:47', 'defaulttop', NULL, NULL, NULL, NULL),
(7, 'По умолчанию в центре страницы', '12850953.png', NULL, '2019-08-30 12:46:04', 'defaultcenter', NULL, NULL, NULL, NULL),
(8, 'По умолчанию сбоку страницы', '46240234.jpg', NULL, '2019-09-05 16:31:33', 'defaultright', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `blacklist`
--

CREATE TABLE `blacklist` (
  `id` int(11) NOT NULL,
  `user_author` int(11) NOT NULL,
  `user_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `image` longtext COLLATE utf8_unicode_ci,
  `sortorder` int(11) NOT NULL,
  `meta_tag_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_description` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_author` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_robots` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_keywords` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `is_active` tinyint(1) DEFAULT '1',
  `is_show_filters` tinyint(1) DEFAULT '1',
  `is_show_bu` tinyint(1) DEFAULT '0',
  `is_show_price_filter` tinyint(1) DEFAULT '1',
  `year_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `year_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `parent_id`, `name`, `title`, `description`, `image`, `sortorder`, `meta_tag_title`, `meta_tag_description`, `meta_tag_author`, `meta_tag_robots`, `meta_tag_keywords`, `is_active`, `is_show_filters`, `is_show_bu`, `is_show_price_filter`, `year_from`, `year_to`) VALUES
(27, NULL, 'legkovye', 'Легковые', 'Легковые автомобили', '<svg width="45" height="22" viewBox="0 0 45 22" fill="none" xmlns="http://www.w3.org/2000/svg">\r\n<path d="M31.5304 17.9975C31.5304 20.2151 33.1952 21.9999 35.2636 21.9999C37.332 21.9999 38.9968 20.2151 38.9968 17.9975C38.9968 15.78 37.332 13.9951 35.2636 13.9951C33.1952 13.9951 31.4799 15.8341 31.5304 17.9975ZM33.0438 18.0516C33.0438 16.7535 34.0528 15.6718 35.2636 15.6718C36.4743 15.6718 37.4833 16.7535 37.4833 18.0516C37.4833 19.3497 36.4743 20.4314 35.2636 20.4314C34.0024 20.4314 32.9934 19.3497 33.0438 18.0516Z" fill="#616161"/>\r\n<path d="M0 14.8607V18.0518C0 18.4845 0.353139 18.8631 0.756726 18.8631H3.73318C4.18722 18.8631 4.54036 18.5385 4.48991 18.0518C4.48991 14.9688 6.86099 12.4268 9.73655 12.4268C12.6121 12.4268 14.9832 14.9688 14.9832 18.0518C14.9832 18.4845 15.3363 18.8631 15.7399 18.8631H29.2096C29.6637 18.8631 30.0168 18.5385 29.9664 18.0518C29.9664 14.9688 32.3374 12.4268 35.213 12.4268C38.0886 12.4268 40.4596 14.9688 40.4596 18.0518C40.4596 18.4845 40.8128 18.8631 41.2164 18.8631H44.2433C44.6469 18.8631 45 18.4845 45 18.0518V14.8607C45 14.428 44.6469 14.0494 44.2433 14.0494L44.1424 12.0482C44.0919 10.7501 43.4865 9.61426 42.4776 8.91113C40.5605 7.55896 37.2814 6.85584 34.1031 6.80175C29.815 1.23083 23.1054 -0.932637 14.2769 0.365442H14.2265C9.38341 1.28491 7.21413 4.04333 5.59978 6.90992L1.36211 8.42435C1.05942 8.53252 0.857624 8.80296 0.857624 9.18156V13.9953C0.403588 13.9953 0.100897 14.3739 0 14.8607ZM21.037 6.42314L21.0874 2.09621C21.0874 1.87987 21.2892 1.66352 21.491 1.71761C25.5269 2.09621 28.8061 3.55655 31.4294 6.09863C31.6816 6.36906 31.5303 6.80175 31.1771 6.80175H21.3901C21.1883 6.80175 21.037 6.63949 21.037 6.42314ZM11.9563 2.9616C11.9563 2.79934 12.0572 2.63708 12.2085 2.58299C12.8644 2.36665 13.5706 2.1503 14.3778 1.98804C16.0426 1.71761 17.657 1.60943 19.12 1.60943C19.3217 1.60943 19.4731 1.82578 19.4731 2.04213V6.42314C19.4731 6.63949 19.3217 6.80175 19.12 6.80175H12.4103C12.2085 6.80175 12.0572 6.63949 12.0572 6.42314L11.9563 2.9616ZM7.76906 6.15271C8.37444 5.23324 9.03027 4.53011 9.83744 3.88107C10.0897 3.71881 10.4428 3.88107 10.4428 4.20559L10.4933 6.36906C10.4933 6.63949 10.3419 6.80175 10.1401 6.80175H8.1222C7.81951 6.80175 7.66816 6.42314 7.76906 6.15271Z" fill="#616161"/>\r\n<path d="M6.05371 17.9975C6.05371 20.2151 7.71851 21.9999 9.7869 21.9999C11.8553 21.9999 13.5201 20.2151 13.5201 17.9975C13.5201 15.78 11.8553 13.9951 9.7869 13.9951C7.71851 13.9951 6.05371 15.8341 6.05371 17.9975ZM7.56716 18.0516C7.56716 16.7535 8.57613 15.6718 9.7869 15.6718C10.9977 15.6718 12.0066 16.7535 12.0066 18.0516C12.0066 19.3497 10.9977 20.4314 9.7869 20.4314C8.57613 20.4314 7.56716 19.3497 7.56716 18.0516Z" fill="#616161"/>\r\n</svg>', 1, 'Легковые автомобили', 'Легковые автомобили', NULL, NULL, 'Легковые автомобили', 1, 1, 1, 1, 'null', 'null'),
(28, 27, 'audi', 'Audi', NULL, NULL, 1, 'Audi', 'Audi', NULL, NULL, 'Audi', 1, 1, 1, 1, 'null', 'null'),
(170, 27, 'Chrisler', 'Chrisler', NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(171, 27, 'Ford', 'Ford', NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(172, 27, 'Jeep', 'Jeep', NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(173, 27, 'Mercedes_Benz', 'Mercedes Benz', NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(174, 27, 'Peugeot', 'Peugeot', NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(175, 27, 'Seat', 'Seat', NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(176, 27, 'Suzuki', 'Suzuki', NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(177, 27, 'Alfa_Romeo', 'Alfa Romeo', NULL, NULL, 9, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(178, 27, 'BMW', 'BMW', NULL, NULL, 10, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(179, 27, 'Cadillac', 'Cadillac', NULL, NULL, 11, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(180, 27, 'Chevrolet', 'Chevrolet', NULL, NULL, 12, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(181, 27, 'Citroen', 'Citroen', NULL, NULL, 13, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(182, 27, 'Dacia', 'Dacia', NULL, NULL, 14, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(183, 27, 'Dodge', 'Dodge', NULL, NULL, 15, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(184, 27, 'Fiat', 'Fiat', NULL, NULL, 16, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(185, 27, 'Honda', 'Honda', NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(186, 27, 'Hyndai', 'Hyndai', NULL, NULL, 18, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(187, 27, 'Infinity', 'Infinity', NULL, NULL, 19, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(188, 27, 'Jaguar', 'Jaguar', NULL, NULL, 20, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(189, 27, 'Kia', 'Kia', NULL, NULL, 21, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(190, 27, 'Land_Rover', 'Land Rover', NULL, NULL, 22, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(191, 27, 'Lexus', 'Lexus', NULL, NULL, 23, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(192, 27, 'Mazda', 'Mazda', NULL, NULL, 24, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(193, 27, 'Mini', 'Mini', NULL, NULL, 25, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(194, 27, 'Mitsubishi', 'Mitsubishi', NULL, NULL, 26, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(195, 27, 'Nissan', 'Nissan', NULL, NULL, 27, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(196, 27, 'Opel', 'Opel', NULL, NULL, 28, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(197, 27, 'Porsche', 'Porsche', NULL, NULL, 29, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(198, 27, 'Renault', 'Renault', NULL, NULL, 30, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(199, 27, 'Rover', 'Rover', NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(200, 27, 'Saab', 'Saab', NULL, NULL, 32, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(201, 27, 'Skoda', 'Skoda', NULL, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(202, 27, 'Smart', 'Smart', NULL, NULL, 34, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(203, 27, 'Ssangyong', 'Ssangyong', NULL, NULL, 35, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(204, 27, 'Subaru', 'Subaru', NULL, NULL, 36, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(205, 27, 'Toyota', 'Toyota', NULL, NULL, 37, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(206, 27, 'Volkswagen', 'Volkswagen', NULL, NULL, 38, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(207, 27, 'Volvo', 'Volvo', NULL, NULL, 39, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(208, 27, 'Subaru', 'Subaru', NULL, NULL, 40, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(209, 195, 'AD', 'AD', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(210, 195, 'AD_Wagon', 'AD Wagon', NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(211, 195, 'Almera', 'Almera', NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(212, 195, 'Almera_Classic', 'Almera Classic', NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(213, 195, 'Almera_Tino', 'Almera Tino', NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(214, 195, 'Avenir', 'Avenir', NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(215, 195, 'Bluebird', 'Bluebird', NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(216, 195, 'Cedric', 'Cedric', NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(217, 195, 'Cefiro', 'Cefiro', NULL, NULL, 9, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(218, 195, 'Cube', 'Cube', NULL, NULL, 10, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(219, 195, 'Elgrand', 'Elgrand', NULL, NULL, 11, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(220, 195, 'Frontier', 'Frontier', NULL, NULL, 12, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(221, 195, 'GT-R', 'GT-R', NULL, NULL, 13, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(222, 195, 'Juke', 'Juke', NULL, NULL, 14, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(223, 195, 'Juke_Nismo', 'Juke Nismo', NULL, NULL, 15, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(224, 195, 'Kubistar', 'Kubistar', NULL, NULL, 16, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(225, 195, 'Laurel', 'Laurel', NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(226, 195, 'Leaf', 'Leaf', NULL, NULL, 18, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(227, 195, 'Liberty', 'Liberty', NULL, NULL, 19, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(228, 195, 'March', 'March', NULL, NULL, 20, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(229, 195, 'Maxima', 'Maxima', NULL, NULL, 21, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(230, 195, 'Micra', 'Micra', NULL, NULL, 22, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(231, 195, 'Murano', 'Murano', NULL, NULL, 23, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(232, 195, 'Navara', 'Navara', NULL, NULL, 24, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(233, 195, 'Note', 'Note', NULL, NULL, 25, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(234, 195, 'NP_300', 'NP 300', NULL, NULL, 26, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(235, 195, 'Pathfinder', 'Pathfinder', NULL, NULL, 27, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(236, 195, 'Patrol', 'Patrol', NULL, NULL, 28, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(237, 195, 'Qashqai', 'Qashqai', NULL, NULL, 29, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'null', 'null'),
(238, 195, 'Quest', 'Quest', NULL, NULL, 30, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(239, 195, 'Quashquai+2', 'Quashquai+2', NULL, NULL, 31, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(240, 195, 'Rnessa', 'Rnessa', NULL, NULL, 32, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(241, 195, 'Sentra', 'Sentra', NULL, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(242, 195, 'Serena', 'Serena', NULL, NULL, 34, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(243, 195, 'SkyLine', 'SkyLine', NULL, NULL, 35, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(244, 195, 'Sunny', 'Sunny', NULL, NULL, 36, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(245, 195, 'Teana', 'Teana', NULL, NULL, 37, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(246, 195, 'Terrano', 'Terrano', NULL, NULL, 38, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(247, 195, 'Tiida', 'Tiida', NULL, NULL, 39, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(248, NULL, 'Mototsikly', 'Мотоциклы', NULL, '<svg width="41" height="22" viewBox="0 0 41 26" fill="none" xmlns="http://www.w3.org/2000/svg">\r\n<path d="M5.98563 13.936C2.71617 13.936 0 16.484 0 19.864C0 23.244 2.66587 26 5.93533 26C9.20479 26 11.8707 23.244 11.8707 19.864C11.8707 19.604 11.8707 19.344 11.8204 19.084L13.3293 18.408L15.8946 21.06C16.0455 21.216 16.1964 21.268 16.3976 21.268C16.5485 21.268 16.6994 21.216 16.8 21.164L23.2383 16.692C23.2383 16.692 23.2383 16.692 23.2886 16.64C25.2 14.924 27.9162 14.56 29.8779 14.56C30.1797 14.56 30.4815 14.612 30.7329 14.612C28.3689 17.316 24.9485 17.004 24.9485 17.004L22.0814 19.24H29.3246C29.2743 19.5 29.2743 19.708 29.2743 19.968C29.2743 23.348 31.7892 26 35.0587 26C38.3282 26 40.994 23.244 40.994 19.864C40.994 18.928 40.7425 17.94 40.3401 17.108C40.994 17.42 41.1952 16.848 40.7928 16.328C40.7928 16.328 38.9318 12.844 33.8012 13.572L35.3605 12.116C35.4611 12.012 35.5114 11.856 35.5617 11.7C35.6623 11.284 35.6623 10.92 35.7126 10.556H34.4551C33.3988 8.684 35.3102 7.384 35.3102 7.384C35.4611 7.592 35.5617 7.748 35.6623 7.956C35.3102 5.512 34.1533 3.744 33.097 2.6C31.4371 0.78 29.5761 0.104 29.5258 0.0519997C29.4755 -2.98321e-07 29.3246 0 29.2743 0C29.0228 0 28.8719 0.104 28.721 0.26C28.5701 0.468 28.5198 0.832 28.6707 1.092C29.4252 2.392 29.7773 3.328 29.9282 4.004L28.8719 3.796C28.5198 3.744 28.1677 4.004 28.1174 4.368C28.0671 4.732 28.3186 5.096 28.6707 5.148L29.9785 5.356C29.9785 5.46 29.9785 5.46 29.9282 5.512C29.6264 6.084 28.8719 6.136 28.5701 6.136C28.5198 6.136 28.5198 6.136 28.4695 6.136C25.7533 5.304 23.7916 4.992 23.7413 4.992C23.3892 4.992 22.9868 4.94 22.685 4.94C21.0252 4.94 19.5162 5.616 18.4096 6.864C17.9569 7.384 17.6551 7.904 17.4539 8.268C14.5868 7.904 12.9773 6.188 12.9773 6.188C12.8264 6.032 12.6755 5.98 12.4743 5.98L4.72815 6.032C4.47665 6.032 4.22515 6.188 4.17485 6.448L3.72216 7.384C3.52096 7.696 3.67186 8.112 4.02395 8.32L4.72815 8.684H3.92336C3.62156 8.684 3.37006 8.944 3.37006 9.256C3.37006 9.568 3.62156 9.828 3.92336 9.828H4.27545L2.01198 12.636C1.81078 12.896 1.86108 13.26 2.06228 13.468C2.16288 13.572 2.31377 13.624 2.41437 13.624C2.61557 13.624 2.76647 13.572 2.86707 13.416L5.73413 9.88H6.33773L7.59521 12.844L12.424 14.924V15.912L10.8647 16.588C9.80839 14.976 7.99761 13.936 5.98563 13.936ZM12.424 13.312L8.6515 11.7L8.04791 10.296L12.424 12.428V13.312ZM35.2096 23.296C33.4994 23.296 32.091 21.84 32.091 20.02C32.091 19.396 32.2922 18.772 32.594 18.252L33.8012 20.8L36.3162 19.5L35.0084 16.744C35.0587 16.744 35.1593 16.744 35.2096 16.744C36.9701 16.744 38.3785 18.2 38.3785 20.02C38.3785 21.84 36.9701 23.296 35.2096 23.296ZM5.98563 23.296C4.22515 23.296 2.86707 21.84 2.86707 20.072C2.86707 18.252 4.27545 16.796 6.03593 16.796C6.89102 16.796 7.64551 17.16 8.24911 17.732L5.63353 18.876L6.74012 21.528L9.1042 20.488C8.903 22.048 7.59521 23.296 5.98563 23.296Z" fill="#616161"/>\r\n</svg>', 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'null', 'null'),
(249, NULL, 'Avtodoma', 'Автодома', NULL, '<svg width="42" height="22" viewBox="0 0 42 25" fill="none" xmlns="http://www.w3.org/2000/svg">\r\n<path d="M18.3468 17.7109C17.3445 17.7109 16.5427 18.0703 15.8411 18.7376C15.1897 19.405 14.8389 20.329 14.8389 21.3557C14.8389 22.3824 15.1395 23.2551 15.8411 23.9738C16.5427 24.6411 17.3445 25.0005 18.3468 25.0005C19.349 25.0005 20.1508 24.6925 20.8524 23.9738C21.5039 23.3064 21.8547 22.3824 21.8547 21.3557C21.8547 20.329 21.554 19.4563 20.8524 18.7376C20.1508 18.0703 19.349 17.7109 18.3468 17.7109ZM16.9937 22.7417C16.6429 22.3824 16.4425 21.9204 16.4425 21.407C16.4425 20.8937 16.6429 20.4317 16.9937 20.0723C17.3445 19.713 17.7955 19.5077 18.3468 19.5077C18.8479 19.5077 19.349 19.713 19.6998 20.0723C20.0506 20.4317 20.2511 20.8937 20.2511 21.407C20.2511 21.9204 20.0506 22.3824 19.6998 22.7417C19.349 23.1011 18.898 23.3064 18.3468 23.3064C17.8456 23.3064 17.3445 23.1011 16.9937 22.7417Z" fill="#616161"/>\r\n<path d="M0.907403 3.23409C0.255934 4.10678 -0.0447437 5.0308 0.00536923 6.05749V17.9158C0.00536923 18.8398 0.35616 19.6612 1.00763 20.3799C1.6591 21.0472 2.41079 21.4066 3.26271 21.4066H13.9368C13.9368 20.1232 14.3377 19.0452 15.1896 18.1725C16.0415 17.2998 17.0939 16.8891 18.3467 16.8891C19.5995 16.8891 20.6519 17.2998 21.5038 18.1725C22.3557 19.0452 22.7566 20.1232 22.7566 21.4066H42V19.6612H36.8384C37.3395 19.6612 37.5901 19.0965 37.5901 17.9158V6.62218C37.5901 5.80082 37.4898 5.0308 37.1892 4.36345C36.8885 3.6961 36.5878 3.18275 36.237 2.82341C35.8862 2.46407 35.4853 2.15606 35.0343 1.84805C34.5833 1.54004 34.2325 1.38604 34.0822 1.3347L33.581 1.1807C32.9296 1.02669 30.8248 0.770021 27.3169 0.462013C23.809 0.154005 20.8523 0 18.3968 0C15.8411 0 12.8844 0.154005 9.57694 0.462013C6.3196 0.770021 4.31508 1.07803 3.6135 1.28337C2.4609 1.69405 1.55887 2.3614 0.907403 3.23409ZM25.4126 10.1643V6.10883C25.4126 5.59548 25.6131 5.39014 26.014 5.39014H31.7269C31.8772 5.39014 32.0275 5.44148 32.1779 5.59548C32.2781 5.69815 32.3783 5.90349 32.3783 6.05749V10.2669C32.3783 10.6263 32.1278 10.7803 31.6767 10.7803H25.9138C25.5128 10.7803 25.3124 10.5749 25.3124 10.1643H25.4126ZM4.31508 6.05749C4.31508 5.54415 4.56565 5.33881 5.01666 5.33881H17.7453C18.1462 5.33881 18.3467 5.59548 18.3467 6.05749V10.2669C18.3467 10.6263 18.1462 10.7803 17.7453 10.7803H5.01666C4.51553 10.7803 4.31508 10.6263 4.31508 10.2669V6.05749Z" fill="#616161"/>\r\n</svg>', 3, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'null', 'null'),
(250, NULL, 'Kommercheskie', 'Коммерческие', NULL, '<svg width="42" height="22" viewBox="0 0 42 24" fill="none" xmlns="http://www.w3.org/2000/svg">\r\n<path d="M30.1408 16.8494C28.1435 16.8494 26.5249 18.4535 26.5249 20.4246C26.5249 22.4 28.1435 23.9999 30.1408 23.9999C32.1386 23.9999 33.7546 22.3997 33.7546 20.4246C33.7546 18.4536 32.1387 16.8494 30.1408 16.8494ZM30.1408 22.08C29.218 22.08 28.4717 21.341 28.4717 20.4247C28.4717 19.512 29.218 18.7735 30.1408 18.7735C31.0635 18.7735 31.8106 19.512 31.8106 20.4247C31.8108 21.341 31.0635 22.08 30.1408 22.08Z" fill="#616161"/>\r\n<path d="M6.032 16.8494C4.03455 16.8494 2.41748 18.4535 2.41748 20.4246C2.41748 22.4 4.03455 23.9999 6.032 23.9999C8.02543 23.9999 9.64652 22.3997 9.64652 20.4246C9.64652 18.4536 8.02543 16.8494 6.032 16.8494ZM6.032 22.08C5.10727 22.08 4.35841 21.341 4.35841 20.4247C4.35841 19.512 5.10727 18.7735 6.032 18.7735C6.95263 18.7735 7.7019 19.512 7.7019 20.4247C7.7019 21.341 6.95263 22.08 6.032 22.08Z" fill="#616161"/>\r\n<path d="M41.309 0H11.0808C10.7017 0 10.3935 0.304092 10.3935 0.679987V4.58685C10.3935 4.95001 10.6807 5.16729 10.8646 5.16729H17.1935C17.437 5.16729 17.6428 5.30181 17.765 5.49499L16.7646 6.46714C16.6401 6.28094 16.441 6.15072 16.1994 6.15072H10.3935V6.22634H9.79652C9.41459 6.22634 9.10919 6.53026 9.10919 6.90932L6.69871 9.42165L2.06603 9.98732C1.68212 9.98732 1.37615 10.2876 1.37615 10.6673L0.917268 13.1045V15.5847C0.917268 15.8855 0.715882 16.1425 0.435173 16.229L0 19.0893C0 19.4647 0.308272 19.7726 0.687172 19.7726H1.08535V19.832H1.54259C1.59788 19.3931 1.71067 18.9704 1.87202 18.58C2.58495 17.011 4.17463 15.9131 6.03172 15.9131C6.64277 15.9131 7.22642 16.0358 7.75789 16.2528L7.80129 16.2096C9.15767 16.8691 10.1433 18.216 10.3415 19.8319H25.4846C25.7819 17.5483 27.7529 15.7785 30.1411 15.7785C32.5296 15.7785 34.4987 17.5483 34.7939 19.8319H41.3093C41.6931 19.8319 42 19.5275 42 19.1477V0.679744C41.9998 0.304092 41.6929 0 41.309 0ZM15.3986 9.41037C15.3986 9.78927 15.1066 10.0982 14.749 10.0982H9.33871C8.95563 10.0982 8.64785 9.79357 8.64785 9.41833L10.0259 7.93665C10.0259 7.55743 10.3335 7.25747 10.7137 7.25747H14.749C15.1066 7.25747 15.3986 7.56189 15.3986 7.94062V9.41037Z" fill="#616161"/>\r\n</svg>', 4, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'null', 'null'),
(251, 171, 'Focus', 'Focus', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, '1998', '2019');

-- --------------------------------------------------------

--
-- Структура таблицы `category_banners`
--

CREATE TABLE `category_banners` (
  `banner_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `category_description`
--

CREATE TABLE `category_description` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `locale_id` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `category_filters`
--

CREATE TABLE `category_filters` (
  `filter_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `category_filters`
--

INSERT INTO `category_filters` (`filter_id`, `category_id`) VALUES
(16, 237),
(17, 237),
(19, 171),
(20, 251),
(22, 251),
(23, 251),
(24, 251),
(25, 251);

-- --------------------------------------------------------

--
-- Структура таблицы `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `region_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortorder` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `city`
--

INSERT INTO `city` (`id`, `region_id`, `name`, `sortorder`) VALUES
(1, 1, 'Балдоне', 0),
(2, 1, 'Баложи', 0),
(3, 1, 'Вангажи', 0),
(4, 1, 'Олайне', 0),
(5, 1, 'Саласпилс', 0),
(6, 1, 'Саулкрасты', 0),
(7, 1, 'Сигулда', 0),
(8, 1, 'Адажский округ', 0),
(9, 1, 'Аллажская вол.', 0),
(10, 1, 'Бабитская вол.', 0),
(11, 1, 'Балдонская с.т.', 0),
(12, 1, 'Гаркалненский округ', 0),
(13, 1, 'Даугмальская вол.', 0),
(14, 1, 'Инчукалнский округ', 0),
(15, 1, 'Кекавская вол.', 0),
(16, 1, 'Кримулдская вол.', 0),
(17, 1, 'Малпилская вол.', 0),
(18, 1, 'Марупская вол.', 0),
(19, 1, 'Моренская вол.', 0),
(20, 1, 'Олайненская вол.', 0),
(21, 1, 'Ропажский округ', 0),
(22, 1, 'Саласпилсская с.т.', 0),
(23, 1, 'Сейский округ', 0),
(24, 1, 'Сигулдская вол.', 0),
(25, 1, 'Стопиньский округ', 0),
(26, 1, 'Царникавский округ', 0),
(27, 1, 'Другое', 0),
(28, 29, 'Центр', 0),
(29, 29, 'Агенскалнс', 0),
(30, 29, 'Аплокциемс', 0),
(31, 29, 'Бебербеки', 0),
(32, 29, 'Берги', 0),
(33, 29, 'Биерини', 0),
(34, 29, 'Болдерая', 0),
(35, 29, 'Букулты', 0),
(36, 29, 'Булли', 0),
(37, 29, 'Вецаки', 0),
(38, 29, 'Вецдаугава', 0),
(39, 29, 'Вецмилгравис', 0),
(41, 29, 'Дарзини', 0),
(42, 29, 'Дарзциемс', 0),
(43, 29, 'Даугавгрива', 0),
(44, 29, 'Дзирциемс (Дзегужкалнс)', 0),
(45, 29, 'Старая Рига (Вецрига)', 0),
(46, 29, 'Дрейлини', 0),
(47, 29, 'Зиепниеккалнс', 0),
(48, 29, 'Золитуде', 0),
(49, 29, 'Ильгюциемс', 0),
(50, 29, 'Иманта', 0),
(51, 29, 'Катлакалнс', 0),
(52, 29, 'Кенгарагс', 0),
(53, 29, 'Кипсала', 0),
(54, 29, 'Клейсти', 0),
(55, 29, 'Кливерсала', 0),
(56, 29, 'Кундзиньсала', 0),
(57, 29, 'Мангали', 0),
(58, 29, 'Межапарк', 0),
(59, 29, 'Межциемс', 0),
(60, 29, 'Плявниеки', 0),
(61, 29, 'Пурвциемс', 0),
(62, 29, 'Московский форштадт', 0),
(63, 29, 'Скансте', 0),
(65, 29, 'Саркандаугава', 0),
(66, 29, 'Тейка', 0),
(67, 29, 'Торнякалнс', 0),
(68, 29, 'Трисциемс', 0),
(69, 29, 'Чиекуркалнс', 0),
(70, 29, 'Шампетерис-Плескодале', 0),
(71, 29, 'Югла', 0),
(72, 29, 'Яунциемс', 0),
(73, 29, 'Другой', 0),
(74, 29, 'Засулаукс', 0),
(75, 29, 'Яунмилгравис', 0),
(76, 29, 'Спилве', 0),
(77, 29, 'Петерсала-Андрейсала', 0),
(78, 3, 'Айзкраукле', 2),
(79, 3, 'Таурлканс', 1),
(80, 3, 'Город', 3),
(81, 3, 'Лиепая', 4),
(82, 3, 'Айзик', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `complaint`
--

CREATE TABLE `complaint` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `reason` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL,
  `status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `conversation`
--

CREATE TABLE `conversation` (
  `id` int(11) NOT NULL,
  `user_deleted` int(11) DEFAULT '0',
  `conversation_userone_id` int(11) DEFAULT NULL,
  `conversation_usertwo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `conversation`
--

INSERT INTO `conversation` (`id`, `user_deleted`, `conversation_userone_id`, `conversation_usertwo_id`) VALUES
(1, NULL, 1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `currency`
--

CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `kurs` double NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `sortorder` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `currency`
--

INSERT INTO `currency` (`id`, `name`, `code`, `kurs`, `is_default`, `sortorder`) VALUES
(1, 'Euro', 'EUR', 1, 1, 1),
(2, 'Российский рубль', 'RUB', 1, 0, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `favorite_products`
--

CREATE TABLE `favorite_products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `favorite_products`
--

INSERT INTO `favorite_products` (`id`, `user_id`, `product_id`) VALUES
(1, 1, 1),
(7, 1, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `filter`
--

CREATE TABLE `filter` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `sortorder` int(11) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_search` tinyint(1) NOT NULL DEFAULT '0',
  `is_selltype` tinyint(1) NOT NULL DEFAULT '0',
  `is_show_card` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `step` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `filter`
--

INSERT INTO `filter` (`id`, `name`, `type`, `is_show`, `sortorder`, `is_required`, `is_search`, `is_selltype`, `is_show_card`, `parent_id`, `step`) VALUES
(16, 'Двигатель', 'radio', 1, 1, 0, 0, 0, 0, NULL, 0),
(17, 'Привод', 'radio', 1, 2, 0, 0, 0, 0, NULL, 0),
(18, 'Коробка передач', 'radio', 1, 3, 0, 0, 0, 0, NULL, 0),
(19, 'Тип кузова', 'select', 1, 4, 0, 0, 0, 0, NULL, 0),
(20, 'Цвет', 'color', 1, 5, 1, 1, 0, 0, NULL, 2),
(22, 'Комплектация', 'select', 1, 6, 1, 1, 0, 0, NULL, 3),
(23, 'Салон', 'select', 1, 7, 1, 1, 0, 0, NULL, 3),
(24, 'Руль', 'select', 1, 8, 1, 1, 0, 0, NULL, 3),
(25, 'Сидения', 'select', 1, 9, 1, 1, 0, 0, NULL, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `filter_value`
--

CREATE TABLE `filter_value` (
  `id` int(11) NOT NULL,
  `filter_id` int(11) DEFAULT NULL,
  `value` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `additional_value` varchar(512) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `filter_value`
--

INSERT INTO `filter_value` (`id`, `filter_id`, `value`, `additional_value`) VALUES
(58, 16, 'Бензин', ''),
(59, 16, 'Дизель', ''),
(60, 17, 'Передний', ''),
(61, 17, 'Задний', ''),
(62, 17, 'Полный', ''),
(63, 18, 'Механическая', ''),
(64, 18, 'Автоматическая', ''),
(65, 18, 'Роботизированная', ''),
(66, 18, 'Вариатор', ''),
(67, 19, 'Седан', ''),
(68, 19, 'Хэчбек 3 дв.', ''),
(69, 19, 'Хэчбек 5 дв.', ''),
(70, 19, 'Универсал 5 дв.', ''),
(71, 19, 'Кабриолет', ''),
(72, 20, 'Белый', '#FFFFFF'),
(73, 20, 'Черный', '#000000'),
(74, 20, 'Желтый', '#F8E937'),
(75, 20, 'Фиолетовый', '#8D66C9'),
(76, 20, 'Зеленый', '#71BA3B'),
(77, 22, 'Гидроусилитель руля', NULL),
(78, 22, 'Датчик дождя', NULL),
(79, 22, 'Спорт-обвес', NULL),
(80, 22, 'Кондиционер', NULL),
(81, 23, 'Кожаный салон', NULL),
(82, 23, 'Полокотники', NULL),
(83, 23, 'Хлодильник', NULL),
(84, 23, 'Шторки на окнах', NULL),
(85, 24, 'Регулируемый', NULL),
(86, 24, 'Многофункциональный', NULL),
(87, 24, 'С обогревом', NULL),
(88, 24, 'Спортивный', NULL),
(89, 25, 'Эл. регулируемые', NULL),
(90, 25, 'Спортивные', NULL),
(91, 25, 'С массажем', NULL),
(92, 25, 'С обогревом', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `form_message`
--

CREATE TABLE `form_message` (
  `id` int(11) NOT NULL,
  `author_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `message_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT '1',
  `answer` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `friend`
--

CREATE TABLE `friend` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_friend_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `translit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `sort` int(11) NOT NULL,
  `is_show` tinyint(1) NOT NULL,
  `locale_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `gallery`
--

INSERT INTO `gallery` (`id`, `name`, `translit`, `description`, `sort`, `is_show`, `locale_id`) VALUES
(1, 'Слайдер на главной', 'mainslider', 'Слайдер на главной', 1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_items`
--

CREATE TABLE `gallery_items` (
  `id` int(11) NOT NULL,
  `gallery_id` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `thumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `sort` int(11) NOT NULL,
  `is_main` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `gallery_items`
--

INSERT INTO `gallery_items` (`id`, `gallery_id`, `description`, `thumb`, `image`, `alt`, `title`, `sort`, `is_main`, `status`) VALUES
(2, 1, '<div class="slide-header">\r\n<h1>Хотите быстро продать?...</h1>\r\n</div>\r\n<div class="slide-subtext">Просто разместите объявление.</div>\r\n<div class="slide-link"><a href="../../../account/addproduct">Добавить объявление</a></div>', '25449.png', '25449.png', 'Слайд 1', 'Слайд 1', 1, 0, 1),
(3, 1, '<div class="slide-header">\r\n<h1>Мы объединяем интересы...</h1>\r\n</div>\r\n<div class="slide-subtext">Наш сайт поможет Вам максимально быстро добавить объвление и найти интересующихся в Вашем продукте.</div>\r\n<div class="slide-link"><a href="../../../account/addproduct">Добавить объявление</a></div>', '75613.png', '75613.png', 'Слайд 2', 'Слайд 2', 3, 0, 1),
(5, 1, '<div class="slide-header">\r\n<h1>Мы объединяем интересы...</h1>\r\n</div>\r\n<div class="slide-subtext">Просто разместите объявление!</div>\r\n<div class="slide-link"><a href="../../../account/addproduct">Добавить объявление</a></div>', '69385.png', '69385.png', 'Слайд 3', 'Слайд 3', 2, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `generation`
--

CREATE TABLE `generation` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `year_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `is_right_wheel` tinyint(1) DEFAULT '0',
  `is_gas` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `generation`
--

INSERT INTO `generation` (`id`, `category_id`, `name`, `year_from`, `year_to`, `is_right_wheel`, `is_gas`) VALUES
(1, 251, 'I', '1998', '2001', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `generation_board`
--

CREATE TABLE `generation_board` (
  `id` int(11) NOT NULL,
  `board_id` int(11) DEFAULT NULL,
  `generation_id` int(11) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `generation_board`
--

INSERT INTO `generation_board` (`id`, `board_id`, `generation_id`, `image`) VALUES
(1, 67, 1, '77380338586261.jpg'),
(2, 68, 1, '994962810113138.jpg'),
(3, 69, 1, '5634423555970.jpg'),
(4, 70, 1, '820717559299081.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `generation_item`
--

CREATE TABLE `generation_item` (
  `id` int(11) NOT NULL,
  `generation_id` int(11) DEFAULT NULL,
  `board_id` int(11) DEFAULT NULL,
  `gas_type_id` int(11) DEFAULT NULL,
  `gas_transmission_id` int(11) DEFAULT NULL,
  `gear_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `generation_item`
--

INSERT INTO `generation_item` (`id`, `generation_id`, `board_id`, `gas_type_id`, `gas_transmission_id`, `gear_type_id`) VALUES
(2, 1, 1, 58, 60, 63),
(3, 1, 1, 58, 60, 64);

-- --------------------------------------------------------

--
-- Структура таблицы `generation_item_modification`
--

CREATE TABLE `generation_item_modification` (
  `generation_item_id` int(11) NOT NULL,
  `modification_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `generation_item_modification`
--

INSERT INTO `generation_item_modification` (`generation_item_id`, `modification_id`) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 1),
(3, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `invite`
--

CREATE TABLE `invite` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `invite_code` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `invite`
--

INSERT INTO `invite` (`id`, `user_id`, `invite_code`, `date_added`) VALUES
(1, 1, 'YToyOntzOjQ6InVzZXIiO2k6MTtzOjk6InJhbmRvbWl6ZSI7aTo2NTg2MzAzNzE7fQ==', '2018-03-26 16:39:37');

-- --------------------------------------------------------

--
-- Структура таблицы `liqpay`
--

CREATE TABLE `liqpay` (
  `id` int(11) NOT NULL,
  `public_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `private_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sandbox` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `liqpay`
--

INSERT INTO `liqpay` (`id`, `public_key`, `private_key`, `currency`, `sandbox`) VALUES
(1, 'i84902635400', '6zF3Ob1YfPeGjakkGCw5vnspEOsk9W1PRtPuQ8ux', 'EUR', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `locale`
--

CREATE TABLE `locale` (
  `id` int(11) NOT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `code` varchar(10) COLLATE utf8_unicode_ci DEFAULT 'null',
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `sortorder` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `is_default` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `locale`
--

INSERT INTO `locale` (`id`, `currency_id`, `name`, `code`, `country`, `sortorder`, `is_active`, `is_default`) VALUES
(1, 1, 'Latviešu', 'lv', 'Latvia.png', 1, 1, 1),
(2, 2, 'Русский', 'ru', 'Russia.png', 2, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `mark`
--

CREATE TABLE `mark` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `mark`
--

INSERT INTO `mark` (`id`, `title`) VALUES
(1, 'Отлично, рекомендую'),
(2, 'Плохое качество'),
(3, 'Среднее качество');

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_from_id` int(11) DEFAULT NULL,
  `user_to_id` int(11) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `is_new` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `sent_date` datetime NOT NULL,
  `readed_date` datetime NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_owner_id` int(11) DEFAULT NULL,
  `conversation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `message`
--

INSERT INTO `message` (`id`, `user_from_id`, `user_to_id`, `subject`, `message`, `image`, `is_new`, `is_deleted`, `sent_date`, `readed_date`, `product_id`, `user_owner_id`, `conversation_id`) VALUES
(1, 2, 1, 'Это тема сообщения', 'Это текст сообщения', NULL, 0, 0, '2018-09-20 09:38:56', '2018-09-20 11:12:58', NULL, 2, 1),
(2, 2, 1, 'Это тема сообщения', 'Это текст сообщения', NULL, 0, 0, '2018-09-20 09:38:56', '2018-09-20 11:12:58', NULL, 1, 1),
(3, 1, 2, NULL, 'Это ответ на сообщение', NULL, 0, 0, '2018-09-20 11:12:56', '2018-09-20 11:23:18', NULL, 1, 1),
(4, 1, 2, NULL, 'Это ответ на сообщение', NULL, 0, 0, '2018-09-20 11:12:56', '2018-09-20 11:23:18', NULL, 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `modification`
--

CREATE TABLE `modification` (
  `id` int(11) NOT NULL,
  `power` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `sortorder` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `generation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `modification`
--

INSERT INTO `modification` (`id`, `power`, `size`, `label`, `sortorder`, `generation_id`) VALUES
(1, '75', '1.4MT', '1998 - 2001', '1', 1),
(2, '100', '1.6MT', '1998 - 2001', '2', 1),
(3, '115', '1.8MT', '1998 - 2001', '3', 1),
(4, '131', '2.0MT', '1998 - 2001', '4', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `order_status`
--

CREATE TABLE `order_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `order_status`
--

INSERT INTO `order_status` (`id`, `name`) VALUES
(1, 'В обработке'),
(2, 'Отклонен'),
(3, 'Одобрен'),
(7, 'Завершен');

-- --------------------------------------------------------

--
-- Структура таблицы `pack`
--

CREATE TABLE `pack` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `description` longtext COLLATE utf8_unicode_ci,
  `price` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `pack_service`
--

CREATE TABLE `pack_service` (
  `id` int(11) NOT NULL,
  `pack_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT '0',
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `sortorder` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `route` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `text` longtext COLLATE utf8_unicode_ci,
  `is_userpage` tinyint(1) NOT NULL,
  `meta_tag_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_description` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_author` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_robots` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_keywords` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `is_footer_menu` tinyint(1) NOT NULL,
  `sortorder` int(15) DEFAULT '0',
  `footer_menu_section` int(11) DEFAULT '0',
  `locale_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `page`
--

INSERT INTO `page` (`id`, `title`, `route`, `text`, `is_userpage`, `meta_tag_title`, `meta_tag_description`, `meta_tag_author`, `meta_tag_robots`, `meta_tag_keywords`, `is_footer_menu`, `sortorder`, `footer_menu_section`, `locale_id`) VALUES
(1, 'Kategorija', 'category', 'Страница категории товара', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(2, 'Produkts', 'product', 'Страница продукта', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(3, 'Mājas', 'main', '<h1>Доска объявлений в Латвии</h1>\r\n<p>Бесплатные объявления в Латвии&nbsp; - здесь вы найдете то, что искали! Нажав на кнопку "Подать объявление", вы перейдете на форму, заполнив которую сможете разместить объявление на любую необходимую тематику легко и абсолютно бесплатно. С помощью сайта объявлений ОЛХ Беларусь вы сможете купить или продать из рук в руки практически все, что угодно.</p>', 0, 'Доска объявлений', 'Доска объявлений', NULL, NULL, NULL, 0, 0, 1, 1),
(4, '404 kļūda', 'notfound', '<h1 class="error-message">Ошибка 404...</h1>\r\n<div class="error-desc m-b-20">Извините, но по вашему запросу ничего не найдено. Вернитесь на <a href="../../">главную страницу</a> или воспользуйтесь поиском по сайту.</div>', 0, 'Ошибка 404.', 'null', NULL, NULL, NULL, 0, 0, 0, 1),
(5, 'Pielāgota lapa', 'pages', 'Пользовательская страница', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(7, 'Как покупать/продавать', 'Kak-pokupat-prodavat', '<strong>Как сделать заказ покупателю на gribupardot.sunweb.by</strong><br /><br />На странице, понравившегося объявления необходимо нажать кнопку "Заказать". <br /><img src="http://data3.floomby.com/files/share/14_9_2016/21/ag5ygYBNbEGD5anHFrdjw.jpg" alt="" height="267" width="518" /><br />После нажатия появляется форма, которую необходимо заполнить. <br />Контактные данные необходимы продавцу для связи с вами и для отправки понравившегося товара. Всегда проверяйте все указанные цифры, ФИО, способ доставки во избежание конфликтных ситуаций (в комментариях к заказу).<br />А также обязательно указывайте желаемый цвет, размер и стоимость. <br /><img src="http://data3.floomby.com/files/share/14_9_2016/21/lKqEj71qdkOo3koKO8Hcg.jpg" alt="" height="346" width="668" /><br /><br />После заполнения всех необходимых данных нажмите кнопку "Отправить заказ". Заказ сохранится, и продавец будет о нем оповещен.<br />После отправки заказа, внести изменения или отменить его уже будет невозможно.<br /><br />По завершению сделки и после получения товара вы можете оценить качество обслуживания, качество товара, выставив продавцу оценку.<br />Чтобы покупатель смог оставить отзыв - продавец должен поставить заказ в статус Одобрен. Отзывы видно в личном кабинете на вкладке Мои Отзывы.<br /><img src="http://data3.floomby.com/files/share/14_9_2016/21/5qF5JCezl0y3LngIj3sT9w.jpg" alt="" height="276" width="619" />\r\n<div><strong><img src="http://data3.floomby.com/files/share/14_9_2016/21/TcIZ9rsvLUC2DcVvytiJOg.jpg" alt="" height="337" width="469" /><br />Как обработать заказ продавцу на gribupardot.sunweb.by</strong><br /><br />После того как покупатель сделал заказ происходит активация значка "Заявки".<br /><br /></div>\r\n<div><img src="http://data3.floomby.com/files/share/14_9_2016/21/1yYMLuvm0CyXjgD7c4RQ.jpg" alt="" height="204" width="640" /><br />При нажатии вы увидите заказанные позиции и вы можете перейти к обработке заказа. В полях заполненных покупателем продавец увидит всю необходимую информацию: контактные данные, цвет, размер и т.д. После этого отправляем заказ в работу, устанавливая статус заказа "Одобрен". По завершению сделки, устанавливаем статус "Завершен".<br /><img src="http://data3.floomby.com/files/share/14_9_2016/21/sG4dxOKgA0OLpryJHoiLQ.jpg" alt="" height="286" width="651" /></div>\r\n<div>После завершения сделки покупатель может выставить оценку, формируя при этом рейтинг продавца. <br /><br />Рейтинг определяется так: кол-во положительных отзывов умножается на 100 и делится на общее кол-во отзывов.</div>', 1, 'Как покупать и продавать', 'Как покупать и продавать на gribupardot.sunweb.by', NULL, NULL, NULL, 1, 1, 0, 1),
(8, 'Условия использования', 'Usloviya-ispol-zovaniya', 'Условия использования', 1, 'Условия использования', 'Условия использования', NULL, NULL, NULL, 0, 2, 0, 1),
(9, 'Помощь/Контакты', 'Pomosch', 'Если у вас возникли вопросы - пишите пожалуйста на на почту:<span style="color: #222222; font-size: 12.8px; font-family: arial, sans-serif;"> info@gribpardot.lv<br /><a href="https://vk.com/public128843952"><br /></a></span>', 1, 'Помощь', 'Помощь', NULL, NULL, NULL, 1, 3, 0, 1),
(10, 'Sludinājums uz vietas', 'Reklama_na_sayte', 'Реклама на сайте. <br /><br /><br /><strong>Премиум объявления:</strong><br />Показываются в отдельном блоке на главной странице сайта и во всех категорях в которых находится товар, например, если это детская шапка то объявление будет выделено и в категории Детская одежда&nbsp;и Головные уборы.&nbsp;<br />Цена Премиум объявления 50 грн/сутки.<br /><br /><strong>Выделенное объявление:</strong><br />Ваше объявление будет показываться на странице результатов поиска, оно отмечатся особой иконкой и отображаются другой цветовой гаммой, в отличие от стандартного желтого фона. <br />BONUS: при этом оно поднимется на первое место в результатах поиска бесплатно. Стоимость услуги&nbsp; 5 евро/сутки<br /><br /><br />Не забывайте что для рекламы вы можете использовать деньги, накопленные по реферальной системе, мы дарим вам 10 грн на счет за каждого нового приведенного вами пользователя.<br /><br />Кроме того к размещению рекламы доступны два баннера на главной странице и сквозное размещение баннеров.<span style="color: #222222; font-size: 12.8px; font-family: arial, sans-serif;"><a href="mailto:shumok.shu@gmail.com"><br /><br /></a></span>', 1, 'Реклама на сайте', 'Реклама на сайте', NULL, NULL, NULL, 1, 4, 2, 1),
(13, 'Referral sistēma', 'Referal_naya_sistema', 'Реферальная система<br /><br />\r\n<p>Расскажите своим друзьям и клиентам о сайте&nbsp; и получите по 5 евро за каждого приведенного пользователя! Накопленные деньги можно потратить на любой вид рекламы на сайте.</p>\r\n<span><br /></span>Для приглашения используйте страничку Реферальная Система в своем профиле.', 1, 'Реферальная система', 'Реферальная система', NULL, NULL, NULL, 1, 5, 1, 1),
(14, 'Drošības noteikumi', 'Pravila-bezopasnosti', 'Правила безопасности совершения сделок на gribupardot.sunweb.by', 1, 'Правила безопасности', 'Правила безопасности', NULL, NULL, NULL, 1, 11, 1, 1),
(16, 'Pievienot reklāmu', 'addproduct', '<div class="block-rules-faster first">\r\n<div class="block-rules-faster-header rules">\r\n<h1>Правила размещения</h1>\r\n</div>\r\n<div class="block-rules-faster-list">\r\n<ul class="list-unstyled">\r\n<li>1. Не подавайте одно и то же объявление повторно.&nbsp;</li>\r\n<li>2. Не пишите телефон, email или адрес сайта в описании или на фото.</li>\r\n<li>3. Не пишите цену в названии, для этого есть отдельное поле.</li>\r\n<li>4. Не продавайте запрещенные товары.</li>\r\n</ul>\r\n</div>\r\n<div class="rules-link"><a href="../../pages/Pravila-razmescheniya">Подробнее о правилах</a></div>\r\n</div>\r\n<div class="block-rules-faster">\r\n<div class="block-rules-faster-header faster">\r\n<h1>Как продать быстрее?</h1>\r\n</div>\r\n<div class="block-rules-faster-list">\r\n<ul class="list-unstyled">\r\n<li>Устанавливайте разумную цену - недорогие товары продаются гораздо быстрее. Как это?</li>\r\n<li>Добавляете фотографии - хорошие фото привлекают больше внимания.</li>\r\n<li>Подробно описывайте товар - это поможет будущему покупателю.</li>\r\n<li>Выберите пакет "премиум размещение" или "выделить объявление".</li>\r\n</ul>\r\n</div>\r\n</div>', 0, 'Добавить объявление', 'Добавить объявление', NULL, NULL, NULL, 0, 0, 0, 1),
(18, 'Referral sistēma', 'account_friends', 'Расскажите своим друзьям и клиентам о сайте и получите по 5 евро за каждого приведенного пользователя! Накопленные деньги можно потратить на любой вид рекламы на сайте.<br /><br />Вы можете приглашать друзей стать пользователями сайта gribupardot.sunweb.by и получать за это награды!<br /><br />Для этого в форму ниже введите электронный адрес друга, которого хотите пригласить и система вышлет ему специально сформированную ссылку, по который надо будет зарегистрироваться на нашем сайте. Или воспользуйтесь готовой ссылкой, можете размещать ее в своем профиле, в социальных сетях, на форумах, нет никаких ограничений.', 0, 'Реферальная система', 'Реферальная система', NULL, NULL, NULL, 0, 0, 0, 1),
(19, 'Sazinieties ar mums', 'contact', '<span style="color: #333333; font-size: 14px; font-family: ''Helvetica Neue'', Helvetica, Arial, sans-serif;">Если у вас возникли вопросы - пишите пожалуйста на на почту:&nbsp;</span><span style="color: #222222; font-size: 12.8px; font-family: arial, sans-serif;">&lt;info@gribpardot.lv&gt;<br /></span>', 0, 'Связаться с нами', 'Связаться с нами', NULL, NULL, NULL, 0, 0, 0, 1),
(20, 'Izmitināšanas noteikumi', 'Pravila-razmescheniya', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил. <br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления. <br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества; <br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих "лёгкий заработок" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br /> Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by', 1, 'Правила размещения', 'Правила размещения', NULL, NULL, NULL, 1, 16, 1, 1),
(21, 'Платные услуги доски объявлений', 'Platnye-uslugi-doski-obyavleniy', '<span style="font-weight: 400;"><strong>Премиум объявления:</strong><br />Показываются в отдельном блоке на главной странице сайта и во всех категорях в которых находится товар, например, если это детская шапка то объявление будет выделено и в категории Детсая одежда&nbsp;и Головные уборы.&nbsp;<br />Цена Премиум объявления 5 евро/сутки.<br /><br /><strong>Выделенное объявление:</strong><br />Ваше объявление будет показываться на странице результатов поиска, оно отмечатся особой иконкой и отображаются другой цветовой гаммой, в отличие от стандартного желтого фона.&nbsp;<br />BONUS: при этом оно поднимется на первое место в результатах поиска бесплатно. Стоимость услуги&nbsp; 5 увро/сутки<br /><br /><br />Не забывайте что для рекламы вы можете использовать деньги, накопленные по реферальной системе, мы дарим вам 2 евро на счет за каждого нового приведенного вами пользователя.<br /><br />Кроме того к размещению рекламы доступны два баннера на главной странице и сквозное размещение баннеров.<br />По вопросам размещения баннера - пишите пожалуйста на почту<span style="color: #222222; font-size: 12.8px; font-family: arial, sans-serif;"><a href="mailto:shumok.shu@gmail.com"><br /><br /></a></span></span>', 1, 'Платные услуги доски объявлений', 'Платные услуги доски объявлений', NULL, NULL, NULL, 0, 16, 2, 1),
(22, 'Категория', 'category', 'Страница категории товара', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 2),
(23, 'Продукт', 'product', 'Страница продукта', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 2),
(24, 'Главная', 'main', '<h1>Доска объявлений в Латвии</h1>\r\n<p>Бесплатные объявления в Латвии&nbsp; - здесь вы найдете то, что искали! Нажав на кнопку "Подать объявление", вы перейдете на форму, заполнив которую сможете разместить объявление на любую необходимую тематику легко и абсолютно бесплатно. С помощью сайта объявлений ОЛХ Беларусь вы сможете купить или продать из рук в руки практически все, что угодно.</p>', 0, 'Доска объявлений', 'Доска объявлений', NULL, NULL, NULL, 0, 0, 1, 2),
(25, 'Ошибка 404', 'notfound', '<h1 class="error-message">Ошибка 404...</h1>\r\n<div class="error-desc m-b-20">Извините, но по вашему запросу ничего не найдено. Вернитесь на <a href="../../">главную страницу</a> или воспользуйтесь поиском по сайту.</div>', 0, 'Ошибка 404.', 'null', NULL, NULL, NULL, 0, 0, 0, 2),
(26, 'Пользовательская старница', 'pages', 'Пользовательская страница', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 2),
(27, 'Как покупать/продавать', 'Kak-pokupat-prodavat', '<strong>Как сделать заказ покупателю на gribupardot.sunweb.by</strong><br /><br />На странице, понравившегося объявления необходимо нажать кнопку "Заказать". <br /><img src="http://data3.floomby.com/files/share/14_9_2016/21/ag5ygYBNbEGD5anHFrdjw.jpg" alt="" height="267" width="518" /><br />После нажатия появляется форма, которую необходимо заполнить. <br />Контактные данные необходимы продавцу для связи с вами и для отправки понравившегося товара. Всегда проверяйте все указанные цифры, ФИО, способ доставки во избежание конфликтных ситуаций (в комментариях к заказу).<br />А также обязательно указывайте желаемый цвет, размер и стоимость. <br /><img src="http://data3.floomby.com/files/share/14_9_2016/21/lKqEj71qdkOo3koKO8Hcg.jpg" alt="" height="346" width="668" /><br /><br />После заполнения всех необходимых данных нажмите кнопку "Отправить заказ". Заказ сохранится, и продавец будет о нем оповещен.<br />После отправки заказа, внести изменения или отменить его уже будет невозможно.<br /><br />По завершению сделки и после получения товара вы можете оценить качество обслуживания, качество товара, выставив продавцу оценку.<br />Чтобы покупатель смог оставить отзыв - продавец должен поставить заказ в статус Одобрен. Отзывы видно в личном кабинете на вкладке Мои Отзывы.<br /><img src="http://data3.floomby.com/files/share/14_9_2016/21/5qF5JCezl0y3LngIj3sT9w.jpg" alt="" height="276" width="619" />\r\n<div><strong><img src="http://data3.floomby.com/files/share/14_9_2016/21/TcIZ9rsvLUC2DcVvytiJOg.jpg" alt="" height="337" width="469" /><br />Как обработать заказ продавцу на gribupardot.sunweb.by</strong><br /><br />После того как покупатель сделал заказ происходит активация значка "Заявки".<br /><br /></div>\r\n<div><img src="http://data3.floomby.com/files/share/14_9_2016/21/1yYMLuvm0CyXjgD7c4RQ.jpg" alt="" height="204" width="640" /><br />При нажатии вы увидите заказанные позиции и вы можете перейти к обработке заказа. В полях заполненных покупателем продавец увидит всю необходимую информацию: контактные данные, цвет, размер и т.д. После этого отправляем заказ в работу, устанавливая статус заказа "Одобрен". По завершению сделки, устанавливаем статус "Завершен".<br /><img src="http://data3.floomby.com/files/share/14_9_2016/21/sG4dxOKgA0OLpryJHoiLQ.jpg" alt="" height="286" width="651" /></div>\r\n<div>После завершения сделки покупатель может выставить оценку, формируя при этом рейтинг продавца. <br /><br />Рейтинг определяется так: кол-во положительных отзывов умножается на 100 и делится на общее кол-во отзывов.</div>', 1, 'Как покупать и продавать', 'Как покупать и продавать на gribupardot.sunweb.by', NULL, NULL, NULL, 1, 1, 0, 2),
(28, 'Условия использования', 'Usloviya-ispol-zovaniya', 'Условия использования', 1, 'Условия использования', 'Условия использования', NULL, NULL, NULL, 0, 2, 0, 2),
(29, 'Помощь/Контакты', 'Pomosch', 'Если у вас возникли вопросы - пишите пожалуйста на на почту:<span style="color: #222222; font-size: 12.8px; font-family: arial, sans-serif;"> info@gribpardot.lv<br /><a href="https://vk.com/public128843952"><br /></a></span>', 1, 'Помощь', 'Помощь', NULL, NULL, NULL, 1, 3, 0, 2),
(30, 'Реклама на сайте', 'Reklama_na_sayte', 'Реклама на сайте. <br /><br /><br /><strong>Премиум объявления:</strong><br />Показываются в отдельном блоке на главной странице сайта и во всех категорях в которых находится товар, например, если это детская шапка то объявление будет выделено и в категории Детская одежда&nbsp;и Головные уборы.&nbsp;<br />Цена Премиум объявления 50 грн/сутки.<br /><br /><strong>Выделенное объявление:</strong><br />Ваше объявление будет показываться на странице результатов поиска, оно отмечатся особой иконкой и отображаются другой цветовой гаммой, в отличие от стандартного желтого фона. <br />BONUS: при этом оно поднимется на первое место в результатах поиска бесплатно. Стоимость услуги&nbsp; 5 евро/сутки<br /><br /><br />Не забывайте что для рекламы вы можете использовать деньги, накопленные по реферальной системе, мы дарим вам 10 грн на счет за каждого нового приведенного вами пользователя.<br /><br />Кроме того к размещению рекламы доступны два баннера на главной странице и сквозное размещение баннеров.<span style="color: #222222; font-size: 12.8px; font-family: arial, sans-serif;"><a href="mailto:shumok.shu@gmail.com"><br /><br /></a></span>', 1, 'Реклама на сайте', 'Реклама на сайте', NULL, NULL, NULL, 1, 4, 2, 2),
(31, 'Реферальная система', 'Referal_naya_sistema', 'Реферальная система<br /><br />\r\n<p>Расскажите своим друзьям и клиентам о сайте&nbsp; и получите по 5 евро за каждого приведенного пользователя! Накопленные деньги можно потратить на любой вид рекламы на сайте.</p>\r\n<span><br /></span>Для приглашения используйте страничку Реферальная Система в своем профиле.', 1, 'Реферальная система', 'Реферальная система', NULL, NULL, NULL, 1, 5, 1, 2),
(32, 'Правила безопасности', 'Pravila-bezopasnosti', 'Правила безопасности совершения сделок на gribupardot.sunweb.by<br />', 1, 'Правила безопасности', 'Правила безопасности', NULL, NULL, NULL, 1, 11, 1, 2),
(33, 'Добавить объявление', 'addproduct', '<div class="block-rules-faster first">\r\n<div class="block-rules-faster-header rules">\r\n<h1>Правила размещения</h1>\r\n</div>\r\n<div class="block-rules-faster-list">\r\n<ul class="list-unstyled">\r\n<li>1. Не подавайте одно и то же объявление повторно.&nbsp;</li>\r\n<li>2. Не пишите телефон, email или адрес сайта в описании или на фото.</li>\r\n<li>3. Не пишите цену в названии, для этого есть отдельное поле.</li>\r\n<li>4. Не продавайте запрещенные товары.</li>\r\n</ul>\r\n</div>\r\n<div class="rules-link"><a href="../../pages/Pravila-razmescheniya">Подробнее о правилах</a></div>\r\n</div>\r\n<div class="block-rules-faster">\r\n<div class="block-rules-faster-header faster">\r\n<h1>Как продать быстрее?</h1>\r\n</div>\r\n<div class="block-rules-faster-list">\r\n<ul class="list-unstyled">\r\n<li>Устанавливайте разумную цену - недорогие товары продаются гораздо быстрее. Как это?</li>\r\n<li>Добавляете фотографии - хорошие фото привлекают больше внимания.</li>\r\n<li>Подробно описывайте товар - это поможет будущему покупателю.</li>\r\n<li>Выберите пакет "премиум размещение" или "выделить объявление".</li>\r\n</ul>\r\n</div>\r\n</div>', 0, 'Добавить объявление', 'Добавить объявление', NULL, NULL, NULL, 0, 0, 0, 2),
(34, 'Реферальная система', 'account_friends', 'Расскажите своим друзьям и клиентам о сайте и получите по 5 евро за каждого приведенного пользователя! Накопленные деньги можно потратить на любой вид рекламы на сайте.<br /><br />Вы можете приглашать друзей стать пользователями сайта gribupardot.sunweb.by и получать за это награды!<br /><br />Для этого в форму ниже введите электронный адрес друга, которого хотите пригласить и система вышлет ему специально сформированную ссылку, по который надо будет зарегистрироваться на нашем сайте. Или воспользуйтесь готовой ссылкой, можете размещать ее в своем профиле, в социальных сетях, на форумах, нет никаких ограничений.', 0, 'Реферальная система', 'Реферальная система', NULL, NULL, NULL, 0, 0, 0, 2),
(35, 'Связаться с нами', 'contact', '<span style="color: #333333; font-size: 14px; font-family: ''Helvetica Neue'', Helvetica, Arial, sans-serif;">Если у вас возникли вопросы - пишите пожалуйста на на почту:&nbsp;</span><span style="color: #222222; font-size: 12.8px; font-family: arial, sans-serif;">&lt;info@gribpardot.lv&gt;<br /></span>', 0, 'Связаться с нами', 'Связаться с нами', NULL, NULL, NULL, 0, 0, 0, 2),
(36, 'Правила размещения', 'Pravila-razmescheniya', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил. <br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления. <br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества; <br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих "лёгкий заработок" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br /> Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by', 1, 'Правила размещения', 'Правила размещения', NULL, NULL, NULL, 1, 16, 1, 2),
(37, 'Платные услуги доски объявлений', 'Platnye-uslugi-doski-obyavleniy', '<span style="font-weight: 400;"><strong>Премиум объявления:</strong><br />Показываются в отдельном блоке на главной странице сайта и во всех категорях в которых находится товар, например, если это детская шапка то объявление будет выделено и в категории Детсая одежда&nbsp;и Головные уборы.&nbsp;<br />Цена Премиум объявления 5 евро/сутки.<br /><br /><strong>Выделенное объявление:</strong><br />Ваше объявление будет показываться на странице результатов поиска, оно отмечатся особой иконкой и отображаются другой цветовой гаммой, в отличие от стандартного желтого фона.&nbsp;<br />BONUS: при этом оно поднимется на первое место в результатах поиска бесплатно. Стоимость услуги&nbsp; 5 увро/сутки<br /><br /><br />Не забывайте что для рекламы вы можете использовать деньги, накопленные по реферальной системе, мы дарим вам 2 евро на счет за каждого нового приведенного вами пользователя.<br /><br />Кроме того к размещению рекламы доступны два баннера на главной странице и сквозное размещение баннеров.<br />По вопросам размещения баннера - пишите пожалуйста на почту<span style="color: #222222; font-size: 12.8px; font-family: arial, sans-serif;"><a href="mailto:shumok.shu@gmail.com"><br /><br /></a></span></span>', 1, 'Платные услуги доски объявлений', 'Платные услуги доски объявлений', NULL, NULL, NULL, 0, 16, 2, 2),
(38, 'Результаты поиска', 'search', NULL, 0, 'Результаты поиска', 'null', 'null', 'null', 'null', 0, 0, 0, 2),
(39, 'Meklēšanas rezultāti', 'search', NULL, 0, 'Meklēšanas rezultāti', 'null', 'null', 'null', 'null', 0, 0, 0, 1),
(40, 'Reklāma ir pievienota', 'addproductSuccess', NULL, 0, 'null', 'null', 'null', 'null', 'null', 0, 10, 0, 1),
(41, 'Объявление добавлено', 'addproductSuccess', NULL, 0, 'null', 'null', 'null', 'null', 'null', 0, 10, 0, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `pages_banners`
--

CREATE TABLE `pages_banners` (
  `banner_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `selltype_id` int(11) DEFAULT NULL,
  `author_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `author_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `typeno` tinyint(1) NOT NULL,
  `typebu` tinyint(1) NOT NULL,
  `typenew` tinyint(1) NOT NULL,
  `name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) DEFAULT NULL,
  `mainfoto` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `viewcommon` tinyint(1) NOT NULL,
  `viewpremium` tinyint(1) NOT NULL,
  `viewselected` tinyint(1) NOT NULL,
  `sortorder` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL,
  `date_edited` datetime NOT NULL,
  `rating_likes` int(11) DEFAULT NULL,
  `rating_dislikes` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `views_per_date` int(11) DEFAULT NULL,
  `is_blocked` tinyint(1) DEFAULT '0',
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `meta_tag_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_description` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `is_correct` tinyint(1) DEFAULT '0',
  `correct_reason` longtext COLLATE utf8_unicode_ci,
  `translit` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `term` int(11) DEFAULT NULL,
  `pack_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `category_id`, `user_id`, `region_id`, `city_id`, `selltype_id`, `author_name`, `author_email`, `author_phone`, `typeno`, `typebu`, `typenew`, `name`, `description`, `price`, `mainfoto`, `viewcommon`, `viewpremium`, `viewselected`, `sortorder`, `is_active`, `date_added`, `date_edited`, `rating_likes`, `rating_dislikes`, `views`, `views_per_date`, `is_blocked`, `is_confirm`, `meta_tag_title`, `meta_tag_description`, `is_correct`, `correct_reason`, `translit`, `term`, `pack_id`) VALUES
(1, NULL, 1, 1, 1, 4, 'Sunweb', 'sales@sunweb.by', '2003823', 0, 1, 0, 'Продам авто VW golf 1.9 tdi', '<span>Полная комплектация. Авто в отличном состоянии. 255000км. Рассмотрю все варианты обмена.</span><br /><ins class="copy_element"><br /></ins>', 2500, '26759744.jpg', 1, 0, 0, NULL, 0, '2018-03-01 19:37:08', '2018-09-12 13:10:43', NULL, NULL, 10, 10, 0, 0, 'Продам авто VW golf 1.9 tdi', NULL, 1, NULL, 'Prodam_avto_VW_golf_1.9_tdi', NULL, NULL),
(3, 28, 1, 29, 63, 4, 'Sunweb', 'sales@sunweb.by', '12345678', 1, 0, 0, 'Дом на даче', 'Дом на даче', 12000, '45785523.jpg', 1, 1, 0, NULL, 1, '2018-08-16 16:14:21', '2018-09-18 14:51:01', NULL, NULL, 13, 13, 0, 1, 'Дом на даче', NULL, 0, NULL, 'Dom_na_dache', 7, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `product_filters`
--

CREATE TABLE `product_filters` (
  `product_id` int(11) NOT NULL,
  `filter_value_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_fotos`
--

CREATE TABLE `product_fotos` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `alt` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `sortorder` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `product_fotos`
--

INSERT INTO `product_fotos` (`id`, `product_id`, `foto`, `alt`, `title`, `sortorder`) VALUES
(2, 3, '83816528.jpg', NULL, 'Дом на даче', NULL),
(3, 3, '22113037.jpg', NULL, 'Дом на даче', NULL),
(4, 3, '1119996.jpg', NULL, 'Дом на даче', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `product_options`
--

CREATE TABLE `product_options` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(512) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_order`
--

CREATE TABLE `product_order` (
  `id` int(11) NOT NULL,
  `user_received_id` int(11) DEFAULT NULL,
  `user_sended_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` longtext COLLATE utf8_unicode_ci,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `is_new` tinyint(1) DEFAULT '1',
  `status_comment` varchar(512) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `product_order`
--

INSERT INTO `product_order` (`id`, `user_received_id`, `user_sended_id`, `product_id`, `name`, `email`, `phone`, `comment`, `date_added`, `status`, `is_new`, `status_comment`) VALUES
(1, 1, 2, 3, 'Эдгар', 'host@sunweb.by', '1234567', 'Это комментарий к заказу', '2018-09-20 09:39:25', 7, 0, '0');

-- --------------------------------------------------------

--
-- Структура таблицы `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `review` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_added` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `sortorder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_service`
--

CREATE TABLE `product_service` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `date_end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `product_service`
--

INSERT INTO `product_service` (`id`, `product_id`, `service_id`, `date_added`, `is_active`, `date_end`) VALUES
(1, 3, 1, '2018-09-11 10:10:44', 1, '2018-09-12 10:10:44');

-- --------------------------------------------------------

--
-- Структура таблицы `region`
--

CREATE TABLE `region` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortorder` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `region`
--

INSERT INTO `region` (`id`, `name`, `sortorder`) VALUES
(1, 'Рижский район', 2),
(3, 'Айзкраукле и р-он', 3),
(4, 'Алуксне и р-он', 4),
(5, 'Балви и р-он', 5),
(6, 'Бауска и р-он', 6),
(7, 'Валка и р-он', 7),
(8, 'Валмиера и р-он', 8),
(9, 'Вентспилс и р-он', 9),
(10, 'Гулбене и р-он', 10),
(11, 'Даугавпилс и р-он', 11),
(12, 'Добеле и р-он', 12),
(13, 'Екабпилс и р-он', 13),
(14, 'Елгава и р-он', 14),
(15, 'Краславa и р-он', 15),
(16, 'Кулдига и р-он', 16),
(17, 'Лиепая и р-он', 17),
(18, 'Лимбажи и р-он', 18),
(19, 'Лудза и р-он', 19),
(20, 'Мадона и р-он', 20),
(21, 'Огре и р-он', 21),
(22, 'Прейли и р-он', 22),
(23, 'Резекне и р-он', 23),
(24, 'Салдус и р-он', 24),
(25, 'Талси и р-он', 25),
(26, 'Тукумс и р-он', 26),
(27, 'Цесис и р-он', 27),
(28, 'Другой', 28),
(29, 'Рига', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `confirm_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `invite_code` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `register`
--

INSERT INTO `register` (`id`, `user_id`, `confirm_key`, `date`, `invite_code`) VALUES
(1, 2, '905b5bfdf81ee3a2394bac397d8250df', '2018-09-20 09:37:32', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_target_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `answer_id` int(11) DEFAULT NULL,
  `review_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) DEFAULT '0',
  `product_mark` varchar(512) COLLATE utf8_unicode_ci DEFAULT '0',
  `answer_to_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `advert_number` int(11) DEFAULT NULL,
  `advert_foto_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `name`, `role`, `advert_number`, `advert_foto_number`) VALUES
(1, 'admin', 'ROLE_ADMIN', 100, 30),
(2, 'seller', 'ROLE_SELLER', 50, 25);

-- --------------------------------------------------------

--
-- Структура таблицы `selltype`
--

CREATE TABLE `selltype` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `selltype`
--

INSERT INTO `selltype` (`id`, `title`) VALUES
(4, 'Продам'),
(5, 'Куплю'),
(6, 'Аренда'),
(7, 'Обмен'),
(10, 'Спрос/Ищу'),
(11, 'Приму в дар'),
(12, 'Отдам даром'),
(14, 'Услуги');

-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `description` longtext COLLATE utf8_unicode_ci,
  `price` int(11) DEFAULT '0',
  `days` int(11) DEFAULT '0',
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id`, `title`, `description`, `price`, `days`, `icon`) VALUES
(1, 'Премиум-размещение', 'Ваше объявление будет показываться на самом заметном месте сайта &mdash; на главной странице, в категориях над обычными объявлениями и на страницах результатов поиска.<br /> Стоимость услуги &mdash; 5 евро в сутки.', 50, 1, '<i class="fa fa-diamond" aria-hidden="true"></i>'),
(2, 'Выделить', 'Ваше объявление будет показываться на странице результатов поиска и в своей категории, оно отмечатся особой иконкой и отображаются другой цветовой гаммой. BONUS При этом оно поднимется на первое место в результатах поиска бесплатно. Цена - 3 евро/сутки.', 30, 1, '<i class="fa fa-pencil" aria-hidden="true"></i>'),
(3, 'Поднять', 'Ваше объявление поднимется на первое место в результатах поиска, как если бы оно было только что подано на сайт. BONUS На следующий день, в это же время, оно будет поднято ещё раз бесплатно. <br /> Стоимость услуги&nbsp;20 грн/сутки.', 20, 1, '<i class="fa fa-arrow-up" aria-hidden="true"></i>');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `user_default_group` int(11) DEFAULT '0',
  `user_advert_limit_text` longtext COLLATE utf8_unicode_ci,
  `site_name` varchar(512) COLLATE utf8_unicode_ci DEFAULT '0',
  `site_description` longtext COLLATE utf8_unicode_ci,
  `admin_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `category_product_number` int(11) DEFAULT '0',
  `topseller_block_number` int(11) DEFAULT '0',
  `mainpage_adverts_number` int(11) DEFAULT '0',
  `site_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `catpage_premium_number` int(11) DEFAULT '0',
  `selected_adv_price` int(11) DEFAULT '0',
  `premium_adv_price` int(11) DEFAULT '0',
  `conversation_index` int(11) DEFAULT '0',
  `advert_days_show_number` int(11) DEFAULT '0',
  `up_adv_price` int(11) DEFAULT '0',
  `dafault_order_status` int(11) NOT NULL,
  `success_add_advert_text` longtext COLLATE utf8_unicode_ci,
  `watermark` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `aditional_advert_price` int(11) DEFAULT '0',
  `is_moderate` tinyint(1) NOT NULL DEFAULT '0',
  `locale_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `copyright` longtext COLLATE utf8_unicode_ci,
  `textblock_how_to_price` longtext COLLATE utf8_unicode_ci,
  `textblock_user_agreement` longtext COLLATE utf8_unicode_ci,
  `user_advert_work_right` longtext COLLATE utf8_unicode_ci,
  `is_show_captcha` tinyint(1) NOT NULL DEFAULT '0',
  `is_show_type` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `user_default_group`, `user_advert_limit_text`, `site_name`, `site_description`, `admin_email`, `category_product_number`, `topseller_block_number`, `mainpage_adverts_number`, `site_logo`, `catpage_premium_number`, `selected_adv_price`, `premium_adv_price`, `conversation_index`, `advert_days_show_number`, `up_adv_price`, `dafault_order_status`, `success_add_advert_text`, `watermark`, `aditional_advert_price`, `is_moderate`, `locale_id`, `currency_id`, `copyright`, `textblock_how_to_price`, `textblock_user_agreement`, `user_advert_work_right`, `is_show_captcha`, `is_show_type`) VALUES
(1, 2, 'Вы исчерпали доступный Вам лимит бесплатных объявлений. В данном случае Вы можете <a href="../account/buyslots">купить дополнительные слоты</a> для объявлений, либо удалить старые объявления для освобождения места для новых.', 'Доска Объявлений', NULL, 'admin@gribupardot.sunweb.by', NULL, 3, 100, '30569458.png', 9, 30, 50, 1, 30, 20, 1, '<strong>Выполнено!</strong> Ваше объявление успешно добавлено и опубликовано.', '17220519.png', 10, 0, 1, 1, '&copy; Gribupardot.lv - bezmaksas sludinājumu klāja', 'Как правильно указать цену?', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил.&nbsp;<br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления.&nbsp;<br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества;&nbsp;<br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих "лёгкий заработок" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br />Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by', '<div class="block-rules-faster first">\r\n<div class="block-rules-faster-header rules">\r\n<h1>Правила размещения</h1>\r\n</div>\r\n<div class="block-rules-faster-list">\r\n<ul class="list-unstyled">\r\n<li>1. Не подавайте одно и то же объявление повторно. Почему?</li>\r\n<li>2. Не телефон, email или адрес сайта в описании или на фото.</li>\r\n<li>3. Не пишите цену в названии, для этого есть отдельное поле.</li>\r\n<li>4. Не продавайте запрещенные товары.</li>\r\n</ul>\r\n</div>\r\n<div class="rules-link"><a href="../pages/Pravila-razmescheniya">Подробнее о правилах</a></div>\r\n</div>\r\n<div class="block-rules-faster">\r\n<div class="block-rules-faster-header faster">\r\n<h1>Как продать быстрее?</h1>\r\n</div>\r\n<div class="block-rules-faster-list">\r\n<ul class="list-unstyled">\r\n<li>Устанавливайте разумную цену - недорогие товары продаются гораздо быстрее. Как это?</li>\r\n<li>Добавляете фотографии - хорошие фото привлекают больше внимания.</li>\r\n<li>Подробно описывайте товар - это поможет будущему покупателю.</li>\r\n<li>Выберите пакет "премиум размещение" или "выделить объявление".</li>\r\n</ul>\r\n</div>\r\n</div>', 1, 0),
(2, 2, 'Вы исчерпали доступный Вам лимит бесплатных объявлений. В данном случае Вы можете <a href="../account/buyslots">купить дополнительные слоты</a> для объявлений, либо удалить старые объявления для освобождения места для новых.', 'Доска Объявлений', NULL, 'admin@gribupardot.sunweb.by', NULL, 3, 100, '6289673.png', 9, 30, 50, 1, 30, 20, 1, '<strong>Выполнено!</strong> Ваше объявление успешно добавлено и опубликовано.', '17220519.png', 10, 0, 2, 1, '&copy; Gribupardot.lv - доска бесплатных объявлений', 'Как правильно указать цену?', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил.&nbsp;<br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления.&nbsp;<br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества;&nbsp;<br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих "лёгкий заработок" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br />Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by', '<div class="block-rules-faster first">\r\n<div class="block-rules-faster-header rules">\r\n<h1>Правила размещения</h1>\r\n</div>\r\n<div class="block-rules-faster-list">\r\n<ul class="list-unstyled">\r\n<li>1. Не подавайте одно и то же объявление повторно. Почему?</li>\r\n<li>2. Не телефон, email или адрес сайта в описании или на фото.</li>\r\n<li>3. Не пишите цену в названии, для этого есть отдельное поле.</li>\r\n<li>4. Не продавайте запрещенные товары.</li>\r\n</ul>\r\n</div>\r\n<div class="rules-link"><a href="../pages/Pravila-razmescheniya">Подробнее о правилах</a></div>\r\n</div>\r\n<div class="block-rules-faster">\r\n<div class="block-rules-faster-header faster">\r\n<h1>Как продать быстрее?</h1>\r\n</div>\r\n<div class="block-rules-faster-list">\r\n<ul class="list-unstyled">\r\n<li>Устанавливайте разумную цену - недорогие товары продаются гораздо быстрее. Как это?</li>\r\n<li>Добавляете фотографии - хорошие фото привлекают больше внимания.</li>\r\n<li>Подробно описывайте товар - это поможет будущему покупателю.</li>\r\n<li>Выберите пакет "премиум размещение" или "выделить объявление".</li>\r\n</ul>\r\n</div>\r\n</div>', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `textblock`
--

CREATE TABLE `textblock` (
  `id` int(11) NOT NULL,
  `how_to_set_price` longtext COLLATE utf8_unicode_ci,
  `user_agreement` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `textblock`
--

INSERT INTO `textblock` (`id`, `how_to_set_price`, `user_agreement`) VALUES
(1, 'Как правильно указать цену?', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил.&nbsp;<br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления.&nbsp;<br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества;&nbsp;<br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих "лёгкий заработок" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br />Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by');

-- --------------------------------------------------------

--
-- Структура таблицы `translation`
--

CREATE TABLE `translation` (
  `id` int(11) NOT NULL,
  `locale_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `selltype_id` int(11) DEFAULT NULL,
  `mark_id` int(11) DEFAULT NULL,
  `filter_id` int(11) DEFAULT NULL,
  `filter_value_id` int(11) DEFAULT NULL,
  `order_status_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `modification_id` int(11) DEFAULT NULL,
  `generation_id` int(11) DEFAULT NULL,
  `pack_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `translation`
--

INSERT INTO `translation` (`id`, `locale_id`, `category_id`, `value`, `selltype_id`, `mark_id`, `filter_id`, `filter_value_id`, `order_status_id`, `region_id`, `city_id`, `service_id`, `modification_id`, `generation_id`, `pack_id`) VALUES
(26, 1, NULL, 'Rīga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 2, NULL, 'Рига', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 1, NULL, 'Rīga', NULL, NULL, NULL, NULL, NULL, 29, NULL, NULL, NULL, NULL, NULL),
(29, 2, NULL, 'Рига', NULL, NULL, NULL, NULL, NULL, 29, NULL, NULL, NULL, NULL, NULL),
(30, 1, NULL, 'Centrs', NULL, NULL, NULL, NULL, NULL, NULL, 28, NULL, NULL, NULL, NULL),
(31, 2, NULL, 'Центр', NULL, NULL, NULL, NULL, NULL, NULL, 28, NULL, NULL, NULL, NULL),
(32, 1, 27, 'Легковые', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 2, 27, 'Легковые', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 1, NULL, 'Pārdošana', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 2, NULL, 'Продам', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 1, NULL, 'Pērciet', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 2, NULL, 'Куплю', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 1, NULL, 'Īre', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 2, NULL, 'Аренда', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 1, NULL, 'Apmaiņa', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 2, NULL, 'Обмен', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 1, NULL, 'Pieprasījums/meklēšana', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 2, NULL, 'Спрос/Ищу', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 1, NULL, 'Es pieņemšu dāvanu', 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 2, NULL, 'Приму в дар', 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 1, NULL, 'Es atdošu par neko', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 2, NULL, 'Отдам даром', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 1, NULL, 'Pakalpojumi', 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 2, NULL, 'Услуги', 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 1, NULL, 'Baldone', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(73, 2, NULL, 'Балдоне', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(78, 1, NULL, 'Augstākā izmitināšana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 2, NULL, 'Премиум-размещение', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 1, NULL, 'Augstākā izmitināšana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL),
(81, 2, NULL, 'Премиум-размещение', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL),
(82, 1, NULL, 'Izcelt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL),
(83, 2, NULL, 'Выделить', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL),
(84, 1, NULL, 'Pacelt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL),
(85, 2, NULL, 'Поднять', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL),
(94, 1, NULL, 'Айзкраукле и р-он', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL),
(95, 2, NULL, 'Айзкраукле и р-он', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL),
(96, 1, NULL, 'Айзкраукле', NULL, NULL, NULL, NULL, NULL, NULL, 78, NULL, NULL, NULL, NULL),
(97, 2, NULL, 'Айзкраукле', NULL, NULL, NULL, NULL, NULL, NULL, 78, NULL, NULL, NULL, NULL),
(98, 1, NULL, 'Таурлканс', NULL, NULL, NULL, NULL, NULL, NULL, 79, NULL, NULL, NULL, NULL),
(99, 2, NULL, 'Таурлканс', NULL, NULL, NULL, NULL, NULL, NULL, 79, NULL, NULL, NULL, NULL),
(100, 1, NULL, 'Город', NULL, NULL, NULL, NULL, NULL, NULL, 80, NULL, NULL, NULL, NULL),
(102, 2, NULL, 'Город', NULL, NULL, NULL, NULL, NULL, NULL, 80, NULL, NULL, NULL, NULL),
(105, 1, NULL, 'Лиепая', NULL, NULL, NULL, NULL, NULL, NULL, 81, NULL, NULL, NULL, NULL),
(107, 2, NULL, 'Лиепая', NULL, NULL, NULL, NULL, NULL, NULL, 81, NULL, NULL, NULL, NULL),
(108, 1, NULL, 'Aizik', NULL, NULL, NULL, NULL, NULL, NULL, 82, NULL, NULL, NULL, NULL),
(109, 2, NULL, 'Айзик', NULL, NULL, NULL, NULL, NULL, NULL, 82, NULL, NULL, NULL, NULL),
(110, 1, NULL, 'Apstrādē', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(111, 2, NULL, 'В обработке', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(112, 1, NULL, 'Fine, es iesaku', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(113, 2, NULL, 'Отлично, рекомендую', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(114, 1, 28, 'Audi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(115, 2, 28, 'Audi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(118, 1, NULL, 'Двигатель', NULL, NULL, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 2, NULL, 'Двигатель', NULL, NULL, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(120, 1, NULL, 'Бензин', NULL, NULL, NULL, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 2, NULL, 'Бензин', NULL, NULL, NULL, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(122, 1, NULL, 'Дизель', NULL, NULL, NULL, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(123, 2, NULL, 'Дизель', NULL, NULL, NULL, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(124, 1, NULL, 'Привод', NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(125, 2, NULL, 'Привод', NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(126, 1, NULL, 'Передний', NULL, NULL, NULL, 60, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(127, 2, NULL, 'Передний', NULL, NULL, NULL, 60, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(128, 1, NULL, 'Задний', NULL, NULL, NULL, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(129, 2, NULL, 'Задний', NULL, NULL, NULL, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(130, 1, NULL, 'Полный', NULL, NULL, NULL, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(131, 2, NULL, 'Полный', NULL, NULL, NULL, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(132, 1, NULL, 'Коробка передач', NULL, NULL, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(133, 2, NULL, 'Коробка передач', NULL, NULL, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(134, 1, NULL, 'Механическая', NULL, NULL, NULL, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(135, 2, NULL, 'Механическая', NULL, NULL, NULL, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(136, 1, NULL, 'Автоматическая', NULL, NULL, NULL, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(137, 2, NULL, 'Автоматическая', NULL, NULL, NULL, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(138, 1, NULL, 'Роботизированная', NULL, NULL, NULL, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(139, 2, NULL, 'Роботизированная', NULL, NULL, NULL, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(140, 1, NULL, 'Вариатор', NULL, NULL, NULL, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(141, 2, NULL, 'Вариатор', NULL, NULL, NULL, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(144, 1, NULL, 'Тип кузова', NULL, NULL, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(145, 2, NULL, 'Тип кузова', NULL, NULL, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 1, NULL, 'Седан', NULL, NULL, NULL, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 2, NULL, 'Седан', NULL, NULL, NULL, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(148, 1, NULL, 'Хэчбек 3 дв.', NULL, NULL, NULL, 68, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(149, 2, NULL, 'Хэчбек 3 дв.', NULL, NULL, NULL, 68, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(150, 1, NULL, 'Хэчбек 5 дв.', NULL, NULL, NULL, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(151, 2, NULL, 'Хэчбек 5 дв.', NULL, NULL, NULL, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(152, 1, NULL, 'Универсал 5 дв.', NULL, NULL, NULL, 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(153, 2, NULL, 'Универсал 5 дв.', NULL, NULL, NULL, 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(154, 1, NULL, 'Кабриолет', NULL, NULL, NULL, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(155, 2, NULL, 'Кабриолет', NULL, NULL, NULL, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(156, 1, 251, 'Focus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(157, 2, 251, 'Focus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(158, 1, NULL, 'Цвет', NULL, NULL, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(159, 2, NULL, 'Цвет', NULL, NULL, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(160, 1, NULL, 'Белый', NULL, NULL, NULL, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(161, 2, NULL, 'Белый', NULL, NULL, NULL, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(162, 1, NULL, 'Черный', NULL, NULL, NULL, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(163, 2, NULL, 'Черный', NULL, NULL, NULL, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(164, 1, NULL, 'Желтый', NULL, NULL, NULL, 74, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(165, 2, NULL, 'Желтый', NULL, NULL, NULL, 74, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(166, 1, NULL, 'Фиолетовый', NULL, NULL, NULL, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(167, 2, NULL, 'Фиолетовый', NULL, NULL, NULL, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(168, 1, NULL, 'Зеленый', NULL, NULL, NULL, 76, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(169, 2, NULL, 'Зеленый', NULL, NULL, NULL, 76, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(170, 1, NULL, 'Комплектация', NULL, NULL, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(171, 2, NULL, 'Комплектация', NULL, NULL, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(172, 1, NULL, 'Гидроусилитель руля', NULL, NULL, NULL, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(173, 2, NULL, 'Гидроусилитель руля', NULL, NULL, NULL, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(174, 1, NULL, 'Датчик дождя', NULL, NULL, NULL, 78, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(175, 2, NULL, 'Датчик дождя', NULL, NULL, NULL, 78, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(176, 1, NULL, 'Спорт-обвес', NULL, NULL, NULL, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(177, 2, NULL, 'Спорт-обвес', NULL, NULL, NULL, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(178, 1, NULL, 'Кондиционер', NULL, NULL, NULL, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(179, 2, NULL, 'Кондиционер', NULL, NULL, NULL, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(180, 1, NULL, 'Салон', NULL, NULL, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(181, 2, NULL, 'Салон', NULL, NULL, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(182, 1, NULL, 'Кожаный салон', NULL, NULL, NULL, 81, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(183, 2, NULL, 'Кожаный салон', NULL, NULL, NULL, 81, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(184, 1, NULL, 'Полокотники', NULL, NULL, NULL, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(185, 2, NULL, 'Полокотники', NULL, NULL, NULL, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(186, 1, NULL, 'Хлодильник', NULL, NULL, NULL, 83, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(187, 2, NULL, 'Хлодильник', NULL, NULL, NULL, 83, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(188, 1, NULL, 'Шторки на окнах', NULL, NULL, NULL, 84, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(189, 2, NULL, 'Шторки на окнах', NULL, NULL, NULL, 84, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(190, 1, NULL, 'Руль', NULL, NULL, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(191, 2, NULL, 'Руль', NULL, NULL, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(192, 1, NULL, 'Регулируемый', NULL, NULL, NULL, 85, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(193, 2, NULL, 'Регулируемый', NULL, NULL, NULL, 85, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(194, 1, NULL, 'Многофункциональный', NULL, NULL, NULL, 86, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(195, 2, NULL, 'Многофункциональный', NULL, NULL, NULL, 86, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(196, 1, NULL, 'С обогревом', NULL, NULL, NULL, 87, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(197, 2, NULL, 'С обогревом', NULL, NULL, NULL, 87, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(198, 1, NULL, 'Спортивный', NULL, NULL, NULL, 88, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(199, 2, NULL, 'Спортивный', NULL, NULL, NULL, 88, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(200, 1, NULL, 'Сидения', NULL, NULL, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(201, 2, NULL, 'Сидения', NULL, NULL, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(202, 1, NULL, 'Эл. регулируемые', NULL, NULL, NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(203, 2, NULL, 'Эл. регулируемые', NULL, NULL, NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(204, 1, NULL, 'Спортивные', NULL, NULL, NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(205, 2, NULL, 'Спортивные', NULL, NULL, NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(206, 1, NULL, 'С массажем', NULL, NULL, NULL, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(207, 2, NULL, 'С массажем', NULL, NULL, NULL, 91, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(208, 1, NULL, 'С обогревом', NULL, NULL, NULL, 92, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(209, 2, NULL, 'С обогревом', NULL, NULL, NULL, 92, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `is_active` tinyint(1) NOT NULL,
  `alerts` tinyint(1) NOT NULL,
  `is_confirm` tinyint(1) DEFAULT '0',
  `advert_number` int(11) NOT NULL,
  `vk_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `fb_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `is_active`, `alerts`, `is_confirm`, `advert_number`, `vk_id`, `fb_id`) VALUES
(1, 'sales@sunweb.by', '$2b$10$qkAF.rNcJL0hDU9ROmybsuk8NcCKSCLiSu8Mwu1fEqasF5mC7CcCi', 'sales@sunweb.by', 1, 1, 1, 3, NULL, NULL),
(2, 'host@sunweb.by', '$2y$13$vtSH86/tdpg2PTsPbqweNurffISvjjHnNJgvB.YtgoSPgt2BHNQXi', 'host@sunweb.by', 1, 1, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `enter_count` int(11) NOT NULL DEFAULT '0',
  `last_activity` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id`, `enter_count`, `last_activity`) VALUES
(1, 2, 1, '2018-09-20 09:37:54');

-- --------------------------------------------------------

--
-- Структура таблицы `user_info`
--

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `midname` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'null',
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `birthdayday` int(11) DEFAULT NULL,
  `birthdaymonth` varchar(30) COLLATE utf8_unicode_ci DEFAULT 'null',
  `birthdayyear` varchar(5) COLLATE utf8_unicode_ci DEFAULT 'null',
  `emailmessagesalerts` tinyint(1) DEFAULT '0',
  `emailmessagesreminders` tinyint(1) DEFAULT '0',
  `region_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT '0',
  `sex` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user_info`
--

INSERT INTO `user_info` (`id`, `user_id`, `firstname`, `midname`, `lastname`, `phone`, `avatar`, `birthdayday`, `birthdaymonth`, `birthdayyear`, `emailmessagesalerts`, `emailmessagesreminders`, `region_id`, `city_id`, `rating`, `sex`) VALUES
(2732, 1, 'Sunweb', 'null', 'null', 'null', '41042.png', 2, '1', '2022', 0, 0, NULL, NULL, 0, NULL),
(2733, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_purse`
--

CREATE TABLE `user_purse` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `balanse` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user_purse`
--

INSERT INTO `user_purse` (`id`, `user_id`, `balanse`) VALUES
(2732, 1, '0'),
(2734, NULL, '0'),
(2735, 2, '0');

-- --------------------------------------------------------

--
-- Структура таблицы `user_purse_history`
--

CREATE TABLE `user_purse_history` (
  `id` int(11) NOT NULL,
  `purse_id` int(11) DEFAULT NULL,
  `action_date` datetime NOT NULL,
  `action` longtext COLLATE utf8_unicode_ci,
  `current_balanse` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE `user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `blacklist`
--
ALTER TABLE `blacklist`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_64C19C1727ACA70` (`parent_id`);

--
-- Индексы таблицы `category_banners`
--
ALTER TABLE `category_banners`
  ADD PRIMARY KEY (`banner_id`,`category_id`),
  ADD KEY `IDX_E2D76516684EC833` (`banner_id`),
  ADD KEY `IDX_E2D7651612469DE2` (`category_id`);

--
-- Индексы таблицы `category_description`
--
ALTER TABLE `category_description`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DA7F024D12469DE2` (`category_id`),
  ADD KEY `IDX_DA7F024DE559DFD1` (`locale_id`);

--
-- Индексы таблицы `category_filters`
--
ALTER TABLE `category_filters`
  ADD PRIMARY KEY (`filter_id`,`category_id`),
  ADD KEY `IDX_BFAF27F3D395B25E` (`filter_id`),
  ADD KEY `IDX_BFAF27F312469DE2` (`category_id`);

--
-- Индексы таблицы `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2D5B023498260155` (`region_id`);

--
-- Индексы таблицы `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5F2732B5A76ED395` (`user_id`),
  ADD KEY `IDX_5F2732B54584665A` (`product_id`);

--
-- Индексы таблицы `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8A8E26E9827C85D5` (`conversation_userone_id`),
  ADD KEY `IDX_8A8E26E9E920621A` (`conversation_usertwo_id`);

--
-- Индексы таблицы `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `favorite_products`
--
ALTER TABLE `favorite_products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7FC45F1D727ACA70` (`parent_id`);

--
-- Индексы таблицы `filter_value`
--
ALTER TABLE `filter_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_34C6ABCBD395B25E` (`filter_id`);

--
-- Индексы таблицы `form_message`
--
ALTER TABLE `form_message`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `friend`
--
ALTER TABLE `friend`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_55EEAC616AB4D50C` (`user_friend_id`),
  ADD KEY `IDX_55EEAC61A76ED395` (`user_id`);

--
-- Индексы таблицы `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_472B783AE559DFD1` (`locale_id`);

--
-- Индексы таблицы `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_583396E4E7AF8F` (`gallery_id`);

--
-- Индексы таблицы `generation`
--
ALTER TABLE `generation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D3266C3B12469DE2` (`category_id`);

--
-- Индексы таблицы `generation_board`
--
ALTER TABLE `generation_board`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EA87D172E7EC5785` (`board_id`),
  ADD KEY `IDX_EA87D172553A6EC4` (`generation_id`);

--
-- Индексы таблицы `generation_item`
--
ALTER TABLE `generation_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_73EA38A7553A6EC4` (`generation_id`),
  ADD KEY `IDX_73EA38A7E7EC5785` (`board_id`),
  ADD KEY `IDX_73EA38A73145108E` (`gas_type_id`),
  ADD KEY `IDX_73EA38A72DBF1AFE` (`gas_transmission_id`),
  ADD KEY `IDX_73EA38A732CA4F08` (`gear_type_id`);

--
-- Индексы таблицы `generation_item_modification`
--
ALTER TABLE `generation_item_modification`
  ADD PRIMARY KEY (`generation_item_id`,`modification_id`),
  ADD KEY `IDX_1199AA83ED36393B` (`generation_item_id`),
  ADD KEY `IDX_1199AA834A605127` (`modification_id`);

--
-- Индексы таблицы `invite`
--
ALTER TABLE `invite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C7E210D7A76ED395` (`user_id`);

--
-- Индексы таблицы `liqpay`
--
ALTER TABLE `liqpay`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `locale`
--
ALTER TABLE `locale`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4180C69838248176` (`currency_id`);

--
-- Индексы таблицы `mark`
--
ALTER TABLE `mark`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6BD307F20C3C701` (`user_from_id`),
  ADD KEY `IDX_B6BD307FD2F7B13D` (`user_to_id`),
  ADD KEY `IDX_B6BD307F4584665A` (`product_id`),
  ADD KEY `IDX_B6BD307F9EB185F9` (`user_owner_id`),
  ADD KEY `IDX_B6BD307F9AC0396` (`conversation_id`);

--
-- Индексы таблицы `modification`
--
ALTER TABLE `modification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EF6425D2553A6EC4` (`generation_id`);

--
-- Индексы таблицы `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pack`
--
ALTER TABLE `pack`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pack_service`
--
ALTER TABLE `pack_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DAD40AAF1919B217` (`pack_id`),
  ADD KEY `IDX_DAD40AAFED5CA9E6` (`service_id`);

--
-- Индексы таблицы `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_140AB620E559DFD1` (`locale_id`);

--
-- Индексы таблицы `pages_banners`
--
ALTER TABLE `pages_banners`
  ADD PRIMARY KEY (`banner_id`,`page_id`),
  ADD KEY `IDX_B51DB282684EC833` (`banner_id`),
  ADD KEY `IDX_B51DB282C4663E4` (`page_id`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D34A04AD1919B217` (`pack_id`),
  ADD KEY `IDX_D34A04AD12469DE2` (`category_id`),
  ADD KEY `IDX_D34A04ADA76ED395` (`user_id`),
  ADD KEY `IDX_D34A04AD98260155` (`region_id`),
  ADD KEY `IDX_D34A04AD8BAC62AF` (`city_id`),
  ADD KEY `IDX_D34A04AD5EBADE83` (`selltype_id`);

--
-- Индексы таблицы `product_filters`
--
ALTER TABLE `product_filters`
  ADD PRIMARY KEY (`product_id`,`filter_value_id`),
  ADD KEY `IDX_A9AE7C3D4584665A` (`product_id`),
  ADD KEY `IDX_A9AE7C3DC44FBE02` (`filter_value_id`);

--
-- Индексы таблицы `product_fotos`
--
ALTER TABLE `product_fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6AD87E9B4584665A` (`product_id`);

--
-- Индексы таблицы `product_options`
--
ALTER TABLE `product_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1ECE1374584665A` (`product_id`);

--
-- Индексы таблицы `product_order`
--
ALTER TABLE `product_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5475E8C4113A2C60` (`user_received_id`),
  ADD KEY `IDX_5475E8C483B6363A` (`user_sended_id`),
  ADD KEY `IDX_5475E8C44584665A` (`product_id`);

--
-- Индексы таблицы `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B8A9F0BF4584665A` (`product_id`),
  ADD KEY `IDX_B8A9F0BFA76ED395` (`user_id`);

--
-- Индексы таблицы `product_service`
--
ALTER TABLE `product_service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_304481624584665A` (`product_id`),
  ADD KEY `IDX_30448162ED5CA9E6` (`service_id`);

--
-- Индексы таблицы `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F62F1765E237E06` (`name`);

--
-- Индексы таблицы `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_794381C6AA334807` (`answer_id`),
  ADD UNIQUE KEY `UNIQ_794381C6AB0FA336` (`answer_to_id`),
  ADD KEY `IDX_794381C6A76ED395` (`user_id`),
  ADD KEY `IDX_794381C6156E8682` (`user_target_id`),
  ADD KEY `IDX_794381C64584665A` (`product_id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_57698A6A57698A6A` (`role`);

--
-- Индексы таблицы `selltype`
--
ALTER TABLE `selltype`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E545A0C5E559DFD1` (`locale_id`),
  ADD KEY `IDX_E545A0C538248176` (`currency_id`);

--
-- Индексы таблицы `textblock`
--
ALTER TABLE `textblock`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `translation`
--
ALTER TABLE `translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B469456FE559DFD1` (`locale_id`),
  ADD KEY `IDX_B469456F12469DE2` (`category_id`),
  ADD KEY `IDX_B469456F5EBADE83` (`selltype_id`),
  ADD KEY `IDX_B469456F4290F12B` (`mark_id`),
  ADD KEY `IDX_B469456FD395B25E` (`filter_id`),
  ADD KEY `IDX_B469456FC44FBE02` (`filter_value_id`),
  ADD KEY `IDX_B469456FD7707B45` (`order_status_id`),
  ADD KEY `IDX_B469456F98260155` (`region_id`),
  ADD KEY `IDX_B469456F8BAC62AF` (`city_id`),
  ADD KEY `IDX_B469456FED5CA9E6` (`service_id`),
  ADD KEY `IDX_B469456F4A605127` (`modification_id`),
  ADD KEY `IDX_B469456F553A6EC4` (`generation_id`),
  ADD KEY `IDX_B469456F1919B217` (`pack_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4CF9ED5AA76ED395` (`user_id`);

--
-- Индексы таблицы `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B1087D9EA76ED395` (`user_id`),
  ADD KEY `IDX_B1087D9E98260155` (`region_id`),
  ADD KEY `IDX_B1087D9E8BAC62AF` (`city_id`);

--
-- Индексы таблицы `user_purse`
--
ALTER TABLE `user_purse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_EF6CBFF8A76ED395` (`user_id`);

--
-- Индексы таблицы `user_purse_history`
--
ALTER TABLE `user_purse_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CF33EAF1A429CB3` (`purse_id`);

--
-- Индексы таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `IDX_2DE8C6A3A76ED395` (`user_id`),
  ADD KEY `IDX_2DE8C6A3D60322AC` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `blacklist`
--
ALTER TABLE `blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;
--
-- AUTO_INCREMENT для таблицы `category_description`
--
ALTER TABLE `category_description`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT для таблицы `complaint`
--
ALTER TABLE `complaint`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `favorite_products`
--
ALTER TABLE `favorite_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `filter`
--
ALTER TABLE `filter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT для таблицы `filter_value`
--
ALTER TABLE `filter_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT для таблицы `form_message`
--
ALTER TABLE `form_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `friend`
--
ALTER TABLE `friend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `gallery_items`
--
ALTER TABLE `gallery_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `generation`
--
ALTER TABLE `generation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `generation_board`
--
ALTER TABLE `generation_board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `generation_item`
--
ALTER TABLE `generation_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `invite`
--
ALTER TABLE `invite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `liqpay`
--
ALTER TABLE `liqpay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `locale`
--
ALTER TABLE `locale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `mark`
--
ALTER TABLE `mark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `modification`
--
ALTER TABLE `modification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `pack`
--
ALTER TABLE `pack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `pack_service`
--
ALTER TABLE `pack_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `product_fotos`
--
ALTER TABLE `product_fotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `product_options`
--
ALTER TABLE `product_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `product_order`
--
ALTER TABLE `product_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `product_service`
--
ALTER TABLE `product_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `region`
--
ALTER TABLE `region`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT для таблицы `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `selltype`
--
ALTER TABLE `selltype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `textblock`
--
ALTER TABLE `textblock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `translation`
--
ALTER TABLE `translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2734;
--
-- AUTO_INCREMENT для таблицы `user_purse`
--
ALTER TABLE `user_purse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2736;
--
-- AUTO_INCREMENT для таблицы `user_purse_history`
--
ALTER TABLE `user_purse_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`);

--
-- Ограничения внешнего ключа таблицы `category_banners`
--
ALTER TABLE `category_banners`
  ADD CONSTRAINT `FK_E2D7651612469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_E2D76516684EC833` FOREIGN KEY (`banner_id`) REFERENCES `banner` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `category_description`
--
ALTER TABLE `category_description`
  ADD CONSTRAINT `FK_DA7F024D12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_DA7F024DE559DFD1` FOREIGN KEY (`locale_id`) REFERENCES `locale` (`id`);

--
-- Ограничения внешнего ключа таблицы `category_filters`
--
ALTER TABLE `category_filters`
  ADD CONSTRAINT `FK_BFAF27F312469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_BFAF27F3D395B25E` FOREIGN KEY (`filter_id`) REFERENCES `filter` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `FK_2D5B023498260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Ограничения внешнего ключа таблицы `complaint`
--
ALTER TABLE `complaint`
  ADD CONSTRAINT `FK_5F2732B54584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_5F2732B5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `FK_8A8E26E9827C85D5` FOREIGN KEY (`conversation_userone_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_8A8E26E9E920621A` FOREIGN KEY (`conversation_usertwo_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `filter`
--
ALTER TABLE `filter`
  ADD CONSTRAINT `FK_7FC45F1D727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `filter` (`id`);

--
-- Ограничения внешнего ключа таблицы `filter_value`
--
ALTER TABLE `filter_value`
  ADD CONSTRAINT `FK_34C6ABCBD395B25E` FOREIGN KEY (`filter_id`) REFERENCES `filter` (`id`);

--
-- Ограничения внешнего ключа таблицы `friend`
--
ALTER TABLE `friend`
  ADD CONSTRAINT `FK_55EEAC616AB4D50C` FOREIGN KEY (`user_friend_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_55EEAC61A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `FK_472B783AE559DFD1` FOREIGN KEY (`locale_id`) REFERENCES `locale` (`id`);

--
-- Ограничения внешнего ключа таблицы `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD CONSTRAINT `FK_583396E4E7AF8F` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`id`);

--
-- Ограничения внешнего ключа таблицы `generation`
--
ALTER TABLE `generation`
  ADD CONSTRAINT `FK_D3266C3B12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Ограничения внешнего ключа таблицы `generation_board`
--
ALTER TABLE `generation_board`
  ADD CONSTRAINT `FK_EA87D172553A6EC4` FOREIGN KEY (`generation_id`) REFERENCES `generation` (`id`),
  ADD CONSTRAINT `FK_EA87D172E7EC5785` FOREIGN KEY (`board_id`) REFERENCES `filter_value` (`id`);

--
-- Ограничения внешнего ключа таблицы `generation_item`
--
ALTER TABLE `generation_item`
  ADD CONSTRAINT `FK_73EA38A72DBF1AFE` FOREIGN KEY (`gas_transmission_id`) REFERENCES `filter_value` (`id`),
  ADD CONSTRAINT `FK_73EA38A73145108E` FOREIGN KEY (`gas_type_id`) REFERENCES `filter_value` (`id`),
  ADD CONSTRAINT `FK_73EA38A732CA4F08` FOREIGN KEY (`gear_type_id`) REFERENCES `filter_value` (`id`),
  ADD CONSTRAINT `FK_73EA38A7553A6EC4` FOREIGN KEY (`generation_id`) REFERENCES `generation` (`id`),
  ADD CONSTRAINT `FK_73EA38A7E7EC5785` FOREIGN KEY (`board_id`) REFERENCES `generation_board` (`id`);

--
-- Ограничения внешнего ключа таблицы `generation_item_modification`
--
ALTER TABLE `generation_item_modification`
  ADD CONSTRAINT `FK_1199AA834A605127` FOREIGN KEY (`modification_id`) REFERENCES `modification` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1199AA83ED36393B` FOREIGN KEY (`generation_item_id`) REFERENCES `generation_item` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `invite`
--
ALTER TABLE `invite`
  ADD CONSTRAINT `FK_C7E210D7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `locale`
--
ALTER TABLE `locale`
  ADD CONSTRAINT `FK_4180C69838248176` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`);

--
-- Ограничения внешнего ключа таблицы `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307F20C3C701` FOREIGN KEY (`user_from_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_B6BD307F4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_B6BD307F9AC0396` FOREIGN KEY (`conversation_id`) REFERENCES `conversation` (`id`),
  ADD CONSTRAINT `FK_B6BD307F9EB185F9` FOREIGN KEY (`user_owner_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_B6BD307FD2F7B13D` FOREIGN KEY (`user_to_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `modification`
--
ALTER TABLE `modification`
  ADD CONSTRAINT `FK_EF6425D2553A6EC4` FOREIGN KEY (`generation_id`) REFERENCES `generation` (`id`);

--
-- Ограничения внешнего ключа таблицы `pack_service`
--
ALTER TABLE `pack_service`
  ADD CONSTRAINT `FK_DAD40AAF1919B217` FOREIGN KEY (`pack_id`) REFERENCES `pack` (`id`),
  ADD CONSTRAINT `FK_DAD40AAFED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Ограничения внешнего ключа таблицы `page`
--
ALTER TABLE `page`
  ADD CONSTRAINT `FK_140AB620E559DFD1` FOREIGN KEY (`locale_id`) REFERENCES `locale` (`id`);

--
-- Ограничения внешнего ключа таблицы `pages_banners`
--
ALTER TABLE `pages_banners`
  ADD CONSTRAINT `FK_B51DB282684EC833` FOREIGN KEY (`banner_id`) REFERENCES `banner` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B51DB282C4663E4` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_D34A04AD1919B217` FOREIGN KEY (`pack_id`) REFERENCES `pack` (`id`),
  ADD CONSTRAINT `FK_D34A04AD5EBADE83` FOREIGN KEY (`selltype_id`) REFERENCES `selltype` (`id`),
  ADD CONSTRAINT `FK_D34A04AD8BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`),
  ADD CONSTRAINT `FK_D34A04AD98260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  ADD CONSTRAINT `FK_D34A04ADA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `product_filters`
--
ALTER TABLE `product_filters`
  ADD CONSTRAINT `FK_A9AE7C3D4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A9AE7C3DC44FBE02` FOREIGN KEY (`filter_value_id`) REFERENCES `filter_value` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_fotos`
--
ALTER TABLE `product_fotos`
  ADD CONSTRAINT `FK_6AD87E9B4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Ограничения внешнего ключа таблицы `product_options`
--
ALTER TABLE `product_options`
  ADD CONSTRAINT `FK_1ECE1374584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Ограничения внешнего ключа таблицы `product_order`
--
ALTER TABLE `product_order`
  ADD CONSTRAINT `FK_5475E8C4113A2C60` FOREIGN KEY (`user_received_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_5475E8C44584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_5475E8C483B6363A` FOREIGN KEY (`user_sended_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `FK_B8A9F0BF4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_B8A9F0BFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `product_service`
--
ALTER TABLE `product_service`
  ADD CONSTRAINT `FK_304481624584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_30448162ED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Ограничения внешнего ключа таблицы `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `FK_794381C6156E8682` FOREIGN KEY (`user_target_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_794381C64584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_794381C6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_794381C6AA334807` FOREIGN KEY (`answer_id`) REFERENCES `review` (`id`),
  ADD CONSTRAINT `FK_794381C6AB0FA336` FOREIGN KEY (`answer_to_id`) REFERENCES `review` (`id`);

--
-- Ограничения внешнего ключа таблицы `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `FK_E545A0C538248176` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `FK_E545A0C5E559DFD1` FOREIGN KEY (`locale_id`) REFERENCES `locale` (`id`);

--
-- Ограничения внешнего ключа таблицы `translation`
--
ALTER TABLE `translation`
  ADD CONSTRAINT `FK_B469456F12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_B469456F1919B217` FOREIGN KEY (`pack_id`) REFERENCES `pack` (`id`),
  ADD CONSTRAINT `FK_B469456F4290F12B` FOREIGN KEY (`mark_id`) REFERENCES `mark` (`id`),
  ADD CONSTRAINT `FK_B469456F4A605127` FOREIGN KEY (`modification_id`) REFERENCES `modification` (`id`),
  ADD CONSTRAINT `FK_B469456F553A6EC4` FOREIGN KEY (`generation_id`) REFERENCES `generation` (`id`),
  ADD CONSTRAINT `FK_B469456F5EBADE83` FOREIGN KEY (`selltype_id`) REFERENCES `selltype` (`id`),
  ADD CONSTRAINT `FK_B469456F8BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`),
  ADD CONSTRAINT `FK_B469456F98260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  ADD CONSTRAINT `FK_B469456FC44FBE02` FOREIGN KEY (`filter_value_id`) REFERENCES `filter_value` (`id`),
  ADD CONSTRAINT `FK_B469456FD395B25E` FOREIGN KEY (`filter_id`) REFERENCES `filter` (`id`),
  ADD CONSTRAINT `FK_B469456FD7707B45` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`id`),
  ADD CONSTRAINT `FK_B469456FE559DFD1` FOREIGN KEY (`locale_id`) REFERENCES `locale` (`id`),
  ADD CONSTRAINT `FK_B469456FED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `FK_4CF9ED5AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `FK_B1087D9E8BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`),
  ADD CONSTRAINT `FK_B1087D9E98260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  ADD CONSTRAINT `FK_B1087D9EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_purse`
--
ALTER TABLE `user_purse`
  ADD CONSTRAINT `FK_EF6CBFF8A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_purse_history`
--
ALTER TABLE `user_purse_history`
  ADD CONSTRAINT `FK_7CF33EAF1A429CB3` FOREIGN KEY (`purse_id`) REFERENCES `user_purse` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `FK_2DE8C6A3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2DE8C6A3D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
