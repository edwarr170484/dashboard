-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 22 2019 г., 21:12
-- Версия сервера: 10.4.6-MariaDB
-- Версия PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
  `code` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_from` datetime DEFAULT current_timestamp(),
  `date_to` datetime DEFAULT current_timestamp(),
  `clicks` int(11) DEFAULT 0
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
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `sortorder` int(11) NOT NULL,
  `meta_tag_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_description` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_author` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_robots` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_keywords` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `is_active` tinyint(1) DEFAULT 1,
  `is_show_filters` tinyint(1) DEFAULT 1,
  `is_show_bu` tinyint(1) DEFAULT 0,
  `is_show_price_filter` tinyint(1) DEFAULT 1,
  `year_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `year_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `parent_id`, `name`, `title`, `description`, `image`, `sortorder`, `meta_tag_title`, `meta_tag_description`, `meta_tag_author`, `meta_tag_robots`, `meta_tag_keywords`, `is_active`, `is_show_filters`, `is_show_bu`, `is_show_price_filter`, `year_from`, `year_to`) VALUES
(27, NULL, 'legkovye-avtomobili', 'Легковые автомобили', 'Легковые автомобили', '78238.png', 1, 'Легковые автомобили', 'Легковые автомобили', NULL, NULL, 'Легковые автомобили', 1, 1, 1, 1, 'null', 'null'),
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
(248, NULL, 'Mototsikly', 'Мотоциклы', NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 'null', 'null'),
(249, NULL, 'Avtodoma', 'Автодома', NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null'),
(250, NULL, 'Kommercheskie', 'Коммерческие', NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'null', 'null');

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
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL
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
(17, 237);

-- --------------------------------------------------------

--
-- Структура таблицы `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `region_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortorder` int(11) DEFAULT 0
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
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `conversation`
--

CREATE TABLE `conversation` (
  `id` int(11) NOT NULL,
  `user_deleted` int(11) DEFAULT 0,
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
  `is_default` tinyint(1) DEFAULT 0,
  `sortorder` int(11) DEFAULT 0
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
  `is_show` tinyint(1) NOT NULL DEFAULT 1,
  `sortorder` int(11) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_search` tinyint(1) NOT NULL DEFAULT 0,
  `is_selltype` tinyint(1) NOT NULL DEFAULT 0,
  `is_show_card` tinyint(1) NOT NULL DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `filter`
--

INSERT INTO `filter` (`id`, `name`, `type`, `is_show`, `sortorder`, `is_required`, `is_search`, `is_selltype`, `is_show_card`, `parent_id`) VALUES
(16, 'Двигатель', 'radio', 1, 1, 0, 0, 0, 0, NULL),
(17, 'Привод', 'radio', 1, 2, 0, 0, 0, 0, NULL),
(18, 'Коробка передач', 'radio', 1, 3, 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `filter_linked_values`
--

CREATE TABLE `filter_linked_values` (
  `filter_value_source` int(11) NOT NULL,
  `filter_value_target` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `filter_linked_values`
--

INSERT INTO `filter_linked_values` (`filter_value_source`, `filter_value_target`) VALUES
(58, 60),
(58, 61),
(58, 62),
(59, 60),
(59, 61),
(59, 62);

-- --------------------------------------------------------

--
-- Структура таблицы `filter_value`
--

CREATE TABLE `filter_value` (
  `id` int(11) NOT NULL,
  `filter_id` int(11) DEFAULT NULL,
  `value` varchar(512) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `filter_value`
--

INSERT INTO `filter_value` (`id`, `filter_id`, `value`) VALUES
(58, 16, 'Бензин'),
(59, 16, 'Дизель'),
(60, 17, 'Передний'),
(61, 17, 'Задний'),
(62, 17, 'Полный'),
(63, 18, 'Механическая'),
(64, 18, 'Автоматическая'),
(65, 18, 'Роботизированная'),
(66, 18, 'Вариатор');

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
  `is_new` tinyint(1) NOT NULL DEFAULT 1,
  `answer` text COLLATE utf8_unicode_ci DEFAULT NULL
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
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
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
(2, 1, '<div class=\"slide-header\">\r\n<h1>Хотите быстро продать?...</h1>\r\n</div>\r\n<div class=\"slide-subtext\">Просто разместите объявление.</div>\r\n<div class=\"slide-link\"><a href=\"../../../account/addproduct\">Добавить объявление</a></div>', '25449.png', '25449.png', 'Слайд 1', 'Слайд 1', 1, 0, 1),
(3, 1, '<div class=\"slide-header\">\r\n<h1>Мы объединяем интересы...</h1>\r\n</div>\r\n<div class=\"slide-subtext\">Наш сайт поможет Вам максимально быстро добавить объвление и найти интересующихся в Вашем продукте.</div>\r\n<div class=\"slide-link\"><a href=\"../../../account/addproduct\">Добавить объявление</a></div>', '75613.png', '75613.png', 'Слайд 2', 'Слайд 2', 3, 0, 1),
(5, 1, '<div class=\"slide-header\">\r\n<h1>Мы объединяем интересы...</h1>\r\n</div>\r\n<div class=\"slide-subtext\">Просто разместите объявление!</div>\r\n<div class=\"slide-link\"><a href=\"../../../account/addproduct\">Добавить объявление</a></div>', '69385.png', '69385.png', 'Слайд 3', 'Слайд 3', 2, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `generation`
--

CREATE TABLE `generation` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `year_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `year_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `is_right_wheel` tinyint(1) DEFAULT 0,
  `is_gas` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `is_active` tinyint(1) DEFAULT 0,
  `is_default` tinyint(1) DEFAULT 0
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
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` double DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `pack_service`
--

CREATE TABLE `pack_service` (
  `id` int(11) NOT NULL,
  `pack_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT 0,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `sortorder` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `route` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `text` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_userpage` tinyint(1) NOT NULL,
  `meta_tag_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_description` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_author` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_robots` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_keywords` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `is_footer_menu` tinyint(1) NOT NULL,
  `sortorder` int(15) DEFAULT 0,
  `footer_menu_section` int(11) DEFAULT 0,
  `locale_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `page`
--

INSERT INTO `page` (`id`, `title`, `route`, `text`, `is_userpage`, `meta_tag_title`, `meta_tag_description`, `meta_tag_author`, `meta_tag_robots`, `meta_tag_keywords`, `is_footer_menu`, `sortorder`, `footer_menu_section`, `locale_id`) VALUES
(1, 'Kategorija', 'category', 'Страница категории товара', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(2, 'Produkts', 'product', 'Страница продукта', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(3, 'Mājas', 'main', '<h1>Доска объявлений в Латвии</h1>\r\n<p>Бесплатные объявления в Латвии&nbsp; - здесь вы найдете то, что искали! Нажав на кнопку \"Подать объявление\", вы перейдете на форму, заполнив которую сможете разместить объявление на любую необходимую тематику легко и абсолютно бесплатно. С помощью сайта объявлений ОЛХ Беларусь вы сможете купить или продать из рук в руки практически все, что угодно.</p>', 0, 'Доска объявлений', 'Доска объявлений', NULL, NULL, NULL, 0, 0, 1, 1),
(4, '404 kļūda', 'notfound', '<h1 class=\"error-message\">Ошибка 404...</h1>\r\n<div class=\"error-desc m-b-20\">Извините, но по вашему запросу ничего не найдено. Вернитесь на <a href=\"../../\">главную страницу</a> или воспользуйтесь поиском по сайту.</div>', 0, 'Ошибка 404.', 'null', NULL, NULL, NULL, 0, 0, 0, 1),
(5, 'Pielāgota lapa', 'pages', 'Пользовательская страница', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(7, 'Как покупать/продавать', 'Kak-pokupat-prodavat', '<strong>Как сделать заказ покупателю на gribupardot.sunweb.by</strong><br /><br />На странице, понравившегося объявления необходимо нажать кнопку \"Заказать\". <br /><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/ag5ygYBNbEGD5anHFrdjw.jpg\" alt=\"\" height=\"267\" width=\"518\" /><br />После нажатия появляется форма, которую необходимо заполнить. <br />Контактные данные необходимы продавцу для связи с вами и для отправки понравившегося товара. Всегда проверяйте все указанные цифры, ФИО, способ доставки во избежание конфликтных ситуаций (в комментариях к заказу).<br />А также обязательно указывайте желаемый цвет, размер и стоимость. <br /><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/lKqEj71qdkOo3koKO8Hcg.jpg\" alt=\"\" height=\"346\" width=\"668\" /><br /><br />После заполнения всех необходимых данных нажмите кнопку \"Отправить заказ\". Заказ сохранится, и продавец будет о нем оповещен.<br />После отправки заказа, внести изменения или отменить его уже будет невозможно.<br /><br />По завершению сделки и после получения товара вы можете оценить качество обслуживания, качество товара, выставив продавцу оценку.<br />Чтобы покупатель смог оставить отзыв - продавец должен поставить заказ в статус Одобрен. Отзывы видно в личном кабинете на вкладке Мои Отзывы.<br /><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/5qF5JCezl0y3LngIj3sT9w.jpg\" alt=\"\" height=\"276\" width=\"619\" />\r\n<div><strong><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/TcIZ9rsvLUC2DcVvytiJOg.jpg\" alt=\"\" height=\"337\" width=\"469\" /><br />Как обработать заказ продавцу на gribupardot.sunweb.by</strong><br /><br />После того как покупатель сделал заказ происходит активация значка \"Заявки\".<br /><br /></div>\r\n<div><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/1yYMLuvm0CyXjgD7c4RQ.jpg\" alt=\"\" height=\"204\" width=\"640\" /><br />При нажатии вы увидите заказанные позиции и вы можете перейти к обработке заказа. В полях заполненных покупателем продавец увидит всю необходимую информацию: контактные данные, цвет, размер и т.д. После этого отправляем заказ в работу, устанавливая статус заказа \"Одобрен\". По завершению сделки, устанавливаем статус \"Завершен\".<br /><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/sG4dxOKgA0OLpryJHoiLQ.jpg\" alt=\"\" height=\"286\" width=\"651\" /></div>\r\n<div>После завершения сделки покупатель может выставить оценку, формируя при этом рейтинг продавца. <br /><br />Рейтинг определяется так: кол-во положительных отзывов умножается на 100 и делится на общее кол-во отзывов.</div>', 1, 'Как покупать и продавать', 'Как покупать и продавать на gribupardot.sunweb.by', NULL, NULL, NULL, 1, 1, 0, 1),
(8, 'Условия использования', 'Usloviya-ispol-zovaniya', 'Условия использования', 1, 'Условия использования', 'Условия использования', NULL, NULL, NULL, 0, 2, 0, 1),
(9, 'Помощь/Контакты', 'Pomosch', 'Если у вас возникли вопросы - пишите пожалуйста на на почту:<span style=\"color: #222222; font-size: 12.8px; font-family: arial, sans-serif;\"> info@gribpardot.lv<br /><a href=\"https://vk.com/public128843952\"><br /></a></span>', 1, 'Помощь', 'Помощь', NULL, NULL, NULL, 1, 3, 0, 1),
(10, 'Sludinājums uz vietas', 'Reklama_na_sayte', 'Реклама на сайте. <br /><br /><br /><strong>Премиум объявления:</strong><br />Показываются в отдельном блоке на главной странице сайта и во всех категорях в которых находится товар, например, если это детская шапка то объявление будет выделено и в категории Детская одежда&nbsp;и Головные уборы.&nbsp;<br />Цена Премиум объявления 50 грн/сутки.<br /><br /><strong>Выделенное объявление:</strong><br />Ваше объявление будет показываться на странице результатов поиска, оно отмечатся особой иконкой и отображаются другой цветовой гаммой, в отличие от стандартного желтого фона. <br />BONUS: при этом оно поднимется на первое место в результатах поиска бесплатно. Стоимость услуги&nbsp; 5 евро/сутки<br /><br /><br />Не забывайте что для рекламы вы можете использовать деньги, накопленные по реферальной системе, мы дарим вам 10 грн на счет за каждого нового приведенного вами пользователя.<br /><br />Кроме того к размещению рекламы доступны два баннера на главной странице и сквозное размещение баннеров.<span style=\"color: #222222; font-size: 12.8px; font-family: arial, sans-serif;\"><a href=\"mailto:shumok.shu@gmail.com\"><br /><br /></a></span>', 1, 'Реклама на сайте', 'Реклама на сайте', NULL, NULL, NULL, 1, 4, 2, 1),
(13, 'Referral sistēma', 'Referal_naya_sistema', 'Реферальная система<br /><br />\r\n<p>Расскажите своим друзьям и клиентам о сайте&nbsp; и получите по 5 евро за каждого приведенного пользователя! Накопленные деньги можно потратить на любой вид рекламы на сайте.</p>\r\n<span><br /></span>Для приглашения используйте страничку Реферальная Система в своем профиле.', 1, 'Реферальная система', 'Реферальная система', NULL, NULL, NULL, 1, 5, 1, 1),
(14, 'Drošības noteikumi', 'Pravila-bezopasnosti', 'Правила безопасности совершения сделок на gribupardot.sunweb.by', 1, 'Правила безопасности', 'Правила безопасности', NULL, NULL, NULL, 1, 11, 1, 1),
(16, 'Pievienot reklāmu', 'addproduct', '<div class=\"block-rules-faster first\">\r\n<div class=\"block-rules-faster-header rules\">\r\n<h1>Правила размещения</h1>\r\n</div>\r\n<div class=\"block-rules-faster-list\">\r\n<ul class=\"list-unstyled\">\r\n<li>1. Не подавайте одно и то же объявление повторно.&nbsp;</li>\r\n<li>2. Не пишите телефон, email или адрес сайта в описании или на фото.</li>\r\n<li>3. Не пишите цену в названии, для этого есть отдельное поле.</li>\r\n<li>4. Не продавайте запрещенные товары.</li>\r\n</ul>\r\n</div>\r\n<div class=\"rules-link\"><a href=\"../../pages/Pravila-razmescheniya\">Подробнее о правилах</a></div>\r\n</div>\r\n<div class=\"block-rules-faster\">\r\n<div class=\"block-rules-faster-header faster\">\r\n<h1>Как продать быстрее?</h1>\r\n</div>\r\n<div class=\"block-rules-faster-list\">\r\n<ul class=\"list-unstyled\">\r\n<li>Устанавливайте разумную цену - недорогие товары продаются гораздо быстрее. Как это?</li>\r\n<li>Добавляете фотографии - хорошие фото привлекают больше внимания.</li>\r\n<li>Подробно описывайте товар - это поможет будущему покупателю.</li>\r\n<li>Выберите пакет \"премиум размещение\" или \"выделить объявление\".</li>\r\n</ul>\r\n</div>\r\n</div>', 0, 'Добавить объявление', 'Добавить объявление', NULL, NULL, NULL, 0, 0, 0, 1),
(18, 'Referral sistēma', 'account_friends', 'Расскажите своим друзьям и клиентам о сайте и получите по 5 евро за каждого приведенного пользователя! Накопленные деньги можно потратить на любой вид рекламы на сайте.<br /><br />Вы можете приглашать друзей стать пользователями сайта gribupardot.sunweb.by и получать за это награды!<br /><br />Для этого в форму ниже введите электронный адрес друга, которого хотите пригласить и система вышлет ему специально сформированную ссылку, по который надо будет зарегистрироваться на нашем сайте. Или воспользуйтесь готовой ссылкой, можете размещать ее в своем профиле, в социальных сетях, на форумах, нет никаких ограничений.', 0, 'Реферальная система', 'Реферальная система', NULL, NULL, NULL, 0, 0, 0, 1),
(19, 'Sazinieties ar mums', 'contact', '<span style=\"color: #333333; font-size: 14px; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;\">Если у вас возникли вопросы - пишите пожалуйста на на почту:&nbsp;</span><span style=\"color: #222222; font-size: 12.8px; font-family: arial, sans-serif;\">&lt;info@gribpardot.lv&gt;<br /></span>', 0, 'Связаться с нами', 'Связаться с нами', NULL, NULL, NULL, 0, 0, 0, 1),
(20, 'Izmitināšanas noteikumi', 'Pravila-razmescheniya', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил. <br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления. <br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества; <br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих \"лёгкий заработок\" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br /> Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by', 1, 'Правила размещения', 'Правила размещения', NULL, NULL, NULL, 1, 16, 1, 1),
(21, 'Платные услуги доски объявлений', 'Platnye-uslugi-doski-obyavleniy', '<span style=\"font-weight: 400;\"><strong>Премиум объявления:</strong><br />Показываются в отдельном блоке на главной странице сайта и во всех категорях в которых находится товар, например, если это детская шапка то объявление будет выделено и в категории Детсая одежда&nbsp;и Головные уборы.&nbsp;<br />Цена Премиум объявления 5 евро/сутки.<br /><br /><strong>Выделенное объявление:</strong><br />Ваше объявление будет показываться на странице результатов поиска, оно отмечатся особой иконкой и отображаются другой цветовой гаммой, в отличие от стандартного желтого фона.&nbsp;<br />BONUS: при этом оно поднимется на первое место в результатах поиска бесплатно. Стоимость услуги&nbsp; 5 увро/сутки<br /><br /><br />Не забывайте что для рекламы вы можете использовать деньги, накопленные по реферальной системе, мы дарим вам 2 евро на счет за каждого нового приведенного вами пользователя.<br /><br />Кроме того к размещению рекламы доступны два баннера на главной странице и сквозное размещение баннеров.<br />По вопросам размещения баннера - пишите пожалуйста на почту<span style=\"color: #222222; font-size: 12.8px; font-family: arial, sans-serif;\"><a href=\"mailto:shumok.shu@gmail.com\"><br /><br /></a></span></span>', 1, 'Платные услуги доски объявлений', 'Платные услуги доски объявлений', NULL, NULL, NULL, 0, 16, 2, 1),
(22, 'Категория', 'category', 'Страница категории товара', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 2),
(23, 'Продукт', 'product', 'Страница продукта', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 2),
(24, 'Главная', 'main', '<h1>Доска объявлений в Латвии</h1>\r\n<p>Бесплатные объявления в Латвии&nbsp; - здесь вы найдете то, что искали! Нажав на кнопку \"Подать объявление\", вы перейдете на форму, заполнив которую сможете разместить объявление на любую необходимую тематику легко и абсолютно бесплатно. С помощью сайта объявлений ОЛХ Беларусь вы сможете купить или продать из рук в руки практически все, что угодно.</p>', 0, 'Доска объявлений', 'Доска объявлений', NULL, NULL, NULL, 0, 0, 1, 2),
(25, 'Ошибка 404', 'notfound', '<h1 class=\"error-message\">Ошибка 404...</h1>\r\n<div class=\"error-desc m-b-20\">Извините, но по вашему запросу ничего не найдено. Вернитесь на <a href=\"../../\">главную страницу</a> или воспользуйтесь поиском по сайту.</div>', 0, 'Ошибка 404.', 'null', NULL, NULL, NULL, 0, 0, 0, 2),
(26, 'Пользовательская старница', 'pages', 'Пользовательская страница', 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 2),
(27, 'Как покупать/продавать', 'Kak-pokupat-prodavat', '<strong>Как сделать заказ покупателю на gribupardot.sunweb.by</strong><br /><br />На странице, понравившегося объявления необходимо нажать кнопку \"Заказать\". <br /><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/ag5ygYBNbEGD5anHFrdjw.jpg\" alt=\"\" height=\"267\" width=\"518\" /><br />После нажатия появляется форма, которую необходимо заполнить. <br />Контактные данные необходимы продавцу для связи с вами и для отправки понравившегося товара. Всегда проверяйте все указанные цифры, ФИО, способ доставки во избежание конфликтных ситуаций (в комментариях к заказу).<br />А также обязательно указывайте желаемый цвет, размер и стоимость. <br /><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/lKqEj71qdkOo3koKO8Hcg.jpg\" alt=\"\" height=\"346\" width=\"668\" /><br /><br />После заполнения всех необходимых данных нажмите кнопку \"Отправить заказ\". Заказ сохранится, и продавец будет о нем оповещен.<br />После отправки заказа, внести изменения или отменить его уже будет невозможно.<br /><br />По завершению сделки и после получения товара вы можете оценить качество обслуживания, качество товара, выставив продавцу оценку.<br />Чтобы покупатель смог оставить отзыв - продавец должен поставить заказ в статус Одобрен. Отзывы видно в личном кабинете на вкладке Мои Отзывы.<br /><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/5qF5JCezl0y3LngIj3sT9w.jpg\" alt=\"\" height=\"276\" width=\"619\" />\r\n<div><strong><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/TcIZ9rsvLUC2DcVvytiJOg.jpg\" alt=\"\" height=\"337\" width=\"469\" /><br />Как обработать заказ продавцу на gribupardot.sunweb.by</strong><br /><br />После того как покупатель сделал заказ происходит активация значка \"Заявки\".<br /><br /></div>\r\n<div><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/1yYMLuvm0CyXjgD7c4RQ.jpg\" alt=\"\" height=\"204\" width=\"640\" /><br />При нажатии вы увидите заказанные позиции и вы можете перейти к обработке заказа. В полях заполненных покупателем продавец увидит всю необходимую информацию: контактные данные, цвет, размер и т.д. После этого отправляем заказ в работу, устанавливая статус заказа \"Одобрен\". По завершению сделки, устанавливаем статус \"Завершен\".<br /><img src=\"http://data3.floomby.com/files/share/14_9_2016/21/sG4dxOKgA0OLpryJHoiLQ.jpg\" alt=\"\" height=\"286\" width=\"651\" /></div>\r\n<div>После завершения сделки покупатель может выставить оценку, формируя при этом рейтинг продавца. <br /><br />Рейтинг определяется так: кол-во положительных отзывов умножается на 100 и делится на общее кол-во отзывов.</div>', 1, 'Как покупать и продавать', 'Как покупать и продавать на gribupardot.sunweb.by', NULL, NULL, NULL, 1, 1, 0, 2),
(28, 'Условия использования', 'Usloviya-ispol-zovaniya', 'Условия использования', 1, 'Условия использования', 'Условия использования', NULL, NULL, NULL, 0, 2, 0, 2),
(29, 'Помощь/Контакты', 'Pomosch', 'Если у вас возникли вопросы - пишите пожалуйста на на почту:<span style=\"color: #222222; font-size: 12.8px; font-family: arial, sans-serif;\"> info@gribpardot.lv<br /><a href=\"https://vk.com/public128843952\"><br /></a></span>', 1, 'Помощь', 'Помощь', NULL, NULL, NULL, 1, 3, 0, 2),
(30, 'Реклама на сайте', 'Reklama_na_sayte', 'Реклама на сайте. <br /><br /><br /><strong>Премиум объявления:</strong><br />Показываются в отдельном блоке на главной странице сайта и во всех категорях в которых находится товар, например, если это детская шапка то объявление будет выделено и в категории Детская одежда&nbsp;и Головные уборы.&nbsp;<br />Цена Премиум объявления 50 грн/сутки.<br /><br /><strong>Выделенное объявление:</strong><br />Ваше объявление будет показываться на странице результатов поиска, оно отмечатся особой иконкой и отображаются другой цветовой гаммой, в отличие от стандартного желтого фона. <br />BONUS: при этом оно поднимется на первое место в результатах поиска бесплатно. Стоимость услуги&nbsp; 5 евро/сутки<br /><br /><br />Не забывайте что для рекламы вы можете использовать деньги, накопленные по реферальной системе, мы дарим вам 10 грн на счет за каждого нового приведенного вами пользователя.<br /><br />Кроме того к размещению рекламы доступны два баннера на главной странице и сквозное размещение баннеров.<span style=\"color: #222222; font-size: 12.8px; font-family: arial, sans-serif;\"><a href=\"mailto:shumok.shu@gmail.com\"><br /><br /></a></span>', 1, 'Реклама на сайте', 'Реклама на сайте', NULL, NULL, NULL, 1, 4, 2, 2),
(31, 'Реферальная система', 'Referal_naya_sistema', 'Реферальная система<br /><br />\r\n<p>Расскажите своим друзьям и клиентам о сайте&nbsp; и получите по 5 евро за каждого приведенного пользователя! Накопленные деньги можно потратить на любой вид рекламы на сайте.</p>\r\n<span><br /></span>Для приглашения используйте страничку Реферальная Система в своем профиле.', 1, 'Реферальная система', 'Реферальная система', NULL, NULL, NULL, 1, 5, 1, 2),
(32, 'Правила безопасности', 'Pravila-bezopasnosti', 'Правила безопасности совершения сделок на gribupardot.sunweb.by<br />', 1, 'Правила безопасности', 'Правила безопасности', NULL, NULL, NULL, 1, 11, 1, 2),
(33, 'Добавить объявление', 'addproduct', '<div class=\"block-rules-faster first\">\r\n<div class=\"block-rules-faster-header rules\">\r\n<h1>Правила размещения</h1>\r\n</div>\r\n<div class=\"block-rules-faster-list\">\r\n<ul class=\"list-unstyled\">\r\n<li>1. Не подавайте одно и то же объявление повторно.&nbsp;</li>\r\n<li>2. Не пишите телефон, email или адрес сайта в описании или на фото.</li>\r\n<li>3. Не пишите цену в названии, для этого есть отдельное поле.</li>\r\n<li>4. Не продавайте запрещенные товары.</li>\r\n</ul>\r\n</div>\r\n<div class=\"rules-link\"><a href=\"../../pages/Pravila-razmescheniya\">Подробнее о правилах</a></div>\r\n</div>\r\n<div class=\"block-rules-faster\">\r\n<div class=\"block-rules-faster-header faster\">\r\n<h1>Как продать быстрее?</h1>\r\n</div>\r\n<div class=\"block-rules-faster-list\">\r\n<ul class=\"list-unstyled\">\r\n<li>Устанавливайте разумную цену - недорогие товары продаются гораздо быстрее. Как это?</li>\r\n<li>Добавляете фотографии - хорошие фото привлекают больше внимания.</li>\r\n<li>Подробно описывайте товар - это поможет будущему покупателю.</li>\r\n<li>Выберите пакет \"премиум размещение\" или \"выделить объявление\".</li>\r\n</ul>\r\n</div>\r\n</div>', 0, 'Добавить объявление', 'Добавить объявление', NULL, NULL, NULL, 0, 0, 0, 2),
(34, 'Реферальная система', 'account_friends', 'Расскажите своим друзьям и клиентам о сайте и получите по 5 евро за каждого приведенного пользователя! Накопленные деньги можно потратить на любой вид рекламы на сайте.<br /><br />Вы можете приглашать друзей стать пользователями сайта gribupardot.sunweb.by и получать за это награды!<br /><br />Для этого в форму ниже введите электронный адрес друга, которого хотите пригласить и система вышлет ему специально сформированную ссылку, по который надо будет зарегистрироваться на нашем сайте. Или воспользуйтесь готовой ссылкой, можете размещать ее в своем профиле, в социальных сетях, на форумах, нет никаких ограничений.', 0, 'Реферальная система', 'Реферальная система', NULL, NULL, NULL, 0, 0, 0, 2),
(35, 'Связаться с нами', 'contact', '<span style=\"color: #333333; font-size: 14px; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;\">Если у вас возникли вопросы - пишите пожалуйста на на почту:&nbsp;</span><span style=\"color: #222222; font-size: 12.8px; font-family: arial, sans-serif;\">&lt;info@gribpardot.lv&gt;<br /></span>', 0, 'Связаться с нами', 'Связаться с нами', NULL, NULL, NULL, 0, 0, 0, 2),
(36, 'Правила размещения', 'Pravila-razmescheniya', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил. <br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления. <br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества; <br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих \"лёгкий заработок\" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br /> Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by', 1, 'Правила размещения', 'Правила размещения', NULL, NULL, NULL, 1, 16, 1, 2),
(37, 'Платные услуги доски объявлений', 'Platnye-uslugi-doski-obyavleniy', '<span style=\"font-weight: 400;\"><strong>Премиум объявления:</strong><br />Показываются в отдельном блоке на главной странице сайта и во всех категорях в которых находится товар, например, если это детская шапка то объявление будет выделено и в категории Детсая одежда&nbsp;и Головные уборы.&nbsp;<br />Цена Премиум объявления 5 евро/сутки.<br /><br /><strong>Выделенное объявление:</strong><br />Ваше объявление будет показываться на странице результатов поиска, оно отмечатся особой иконкой и отображаются другой цветовой гаммой, в отличие от стандартного желтого фона.&nbsp;<br />BONUS: при этом оно поднимется на первое место в результатах поиска бесплатно. Стоимость услуги&nbsp; 5 увро/сутки<br /><br /><br />Не забывайте что для рекламы вы можете использовать деньги, накопленные по реферальной системе, мы дарим вам 2 евро на счет за каждого нового приведенного вами пользователя.<br /><br />Кроме того к размещению рекламы доступны два баннера на главной странице и сквозное размещение баннеров.<br />По вопросам размещения баннера - пишите пожалуйста на почту<span style=\"color: #222222; font-size: 12.8px; font-family: arial, sans-serif;\"><a href=\"mailto:shumok.shu@gmail.com\"><br /><br /></a></span></span>', 1, 'Платные услуги доски объявлений', 'Платные услуги доски объявлений', NULL, NULL, NULL, 0, 16, 2, 2),
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
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `date_added` datetime NOT NULL,
  `date_edited` datetime NOT NULL,
  `rating_likes` int(11) DEFAULT NULL,
  `rating_dislikes` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `views_per_date` int(11) DEFAULT NULL,
  `is_blocked` tinyint(1) DEFAULT 0,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `meta_tag_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'null',
  `meta_tag_description` varchar(512) COLLATE utf8_unicode_ci DEFAULT 'null',
  `is_correct` tinyint(1) DEFAULT 0,
  `correct_reason` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `translit` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `term` int(11) DEFAULT NULL,
  `pack_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `category_id`, `user_id`, `region_id`, `city_id`, `selltype_id`, `author_name`, `author_email`, `author_phone`, `typeno`, `typebu`, `typenew`, `name`, `description`, `price`, `mainfoto`, `viewcommon`, `viewpremium`, `viewselected`, `sortorder`, `is_active`, `date_added`, `date_edited`, `rating_likes`, `rating_dislikes`, `views`, `views_per_date`, `is_blocked`, `is_confirm`, `meta_tag_title`, `meta_tag_description`, `is_correct`, `correct_reason`, `translit`, `term`, `pack_id`) VALUES
(1, NULL, 1, 1, 1, 4, 'Sunweb', 'sales@sunweb.by', '2003823', 0, 1, 0, 'Продам авто VW golf 1.9 tdi', '<span>Полная комплектация. Авто в отличном состоянии. 255000км. Рассмотрю все варианты обмена.</span><br /><ins class=\"copy_element\"><br /></ins>', 2500, '26759744.jpg', 1, 0, 0, NULL, 0, '2018-03-01 19:37:08', '2018-09-12 13:10:43', NULL, NULL, 10, 10, 0, 0, 'Продам авто VW golf 1.9 tdi', NULL, 1, NULL, 'Prodam_avto_VW_golf_1.9_tdi', NULL, NULL),
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
  `comment` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `is_new` tinyint(1) DEFAULT 1,
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
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
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
  `sortorder` int(11) DEFAULT 0
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
  `invite_code` longtext COLLATE utf8_unicode_ci DEFAULT NULL
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
  `status` int(11) DEFAULT 0,
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
  `description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` int(11) DEFAULT 0,
  `days` int(11) DEFAULT 0,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id`, `title`, `description`, `price`, `days`, `icon`) VALUES
(1, 'Премиум-размещение', 'Ваше объявление будет показываться на самом заметном месте сайта &mdash; на главной странице, в категориях над обычными объявлениями и на страницах результатов поиска.<br /> Стоимость услуги &mdash; 5 евро в сутки.', 50, 1, '<i class=\"fa fa-diamond\" aria-hidden=\"true\"></i>'),
(2, 'Выделить', 'Ваше объявление будет показываться на странице результатов поиска и в своей категории, оно отмечатся особой иконкой и отображаются другой цветовой гаммой. BONUS При этом оно поднимется на первое место в результатах поиска бесплатно. Цена - 3 евро/сутки.', 30, 1, '<i class=\"fa fa-pencil\" aria-hidden=\"true\"></i>'),
(3, 'Поднять', 'Ваше объявление поднимется на первое место в результатах поиска, как если бы оно было только что подано на сайт. BONUS На следующий день, в это же время, оно будет поднято ещё раз бесплатно. <br /> Стоимость услуги&nbsp;20 грн/сутки.', 20, 1, '<i class=\"fa fa-arrow-up\" aria-hidden=\"true\"></i>');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `user_default_group` int(11) DEFAULT 0,
  `user_advert_limit_text` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `site_name` varchar(512) COLLATE utf8_unicode_ci DEFAULT '0',
  `site_description` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `category_product_number` int(11) DEFAULT 0,
  `topseller_block_number` int(11) DEFAULT 0,
  `mainpage_adverts_number` int(11) DEFAULT 0,
  `site_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `catpage_premium_number` int(11) DEFAULT 0,
  `selected_adv_price` int(11) DEFAULT 0,
  `premium_adv_price` int(11) DEFAULT 0,
  `conversation_index` int(11) DEFAULT 0,
  `advert_days_show_number` int(11) DEFAULT 0,
  `up_adv_price` int(11) DEFAULT 0,
  `dafault_order_status` int(11) NOT NULL,
  `success_add_advert_text` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `watermark` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `aditional_advert_price` int(11) DEFAULT 0,
  `is_moderate` tinyint(1) NOT NULL DEFAULT 0,
  `locale_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `copyright` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `textblock_how_to_price` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `textblock_user_agreement` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_advert_work_right` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_show_captcha` tinyint(1) NOT NULL DEFAULT 0,
  `is_show_type` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `user_default_group`, `user_advert_limit_text`, `site_name`, `site_description`, `admin_email`, `category_product_number`, `topseller_block_number`, `mainpage_adverts_number`, `site_logo`, `catpage_premium_number`, `selected_adv_price`, `premium_adv_price`, `conversation_index`, `advert_days_show_number`, `up_adv_price`, `dafault_order_status`, `success_add_advert_text`, `watermark`, `aditional_advert_price`, `is_moderate`, `locale_id`, `currency_id`, `copyright`, `textblock_how_to_price`, `textblock_user_agreement`, `user_advert_work_right`, `is_show_captcha`, `is_show_type`) VALUES
(1, 2, 'Вы исчерпали доступный Вам лимит бесплатных объявлений. В данном случае Вы можете <a href=\"../account/buyslots\">купить дополнительные слоты</a> для объявлений, либо удалить старые объявления для освобождения места для новых.', 'Доска Объявлений', NULL, 'admin@gribupardot.sunweb.by', NULL, 3, 100, '30569458.png', 9, 30, 50, 1, 30, 20, 1, '<strong>Выполнено!</strong> Ваше объявление успешно добавлено и опубликовано.', '17220519.png', 10, 0, 1, 1, '&copy; Gribupardot.lv - bezmaksas sludinājumu klāja', 'Как правильно указать цену?', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил.&nbsp;<br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления.&nbsp;<br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества;&nbsp;<br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих \"лёгкий заработок\" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br />Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by', '<div class=\"block-rules-faster first\">\r\n<div class=\"block-rules-faster-header rules\">\r\n<h1>Правила размещения</h1>\r\n</div>\r\n<div class=\"block-rules-faster-list\">\r\n<ul class=\"list-unstyled\">\r\n<li>1. Не подавайте одно и то же объявление повторно. Почему?</li>\r\n<li>2. Не телефон, email или адрес сайта в описании или на фото.</li>\r\n<li>3. Не пишите цену в названии, для этого есть отдельное поле.</li>\r\n<li>4. Не продавайте запрещенные товары.</li>\r\n</ul>\r\n</div>\r\n<div class=\"rules-link\"><a href=\"../pages/Pravila-razmescheniya\">Подробнее о правилах</a></div>\r\n</div>\r\n<div class=\"block-rules-faster\">\r\n<div class=\"block-rules-faster-header faster\">\r\n<h1>Как продать быстрее?</h1>\r\n</div>\r\n<div class=\"block-rules-faster-list\">\r\n<ul class=\"list-unstyled\">\r\n<li>Устанавливайте разумную цену - недорогие товары продаются гораздо быстрее. Как это?</li>\r\n<li>Добавляете фотографии - хорошие фото привлекают больше внимания.</li>\r\n<li>Подробно описывайте товар - это поможет будущему покупателю.</li>\r\n<li>Выберите пакет \"премиум размещение\" или \"выделить объявление\".</li>\r\n</ul>\r\n</div>\r\n</div>', 1, 0),
(2, 2, 'Вы исчерпали доступный Вам лимит бесплатных объявлений. В данном случае Вы можете <a href=\"../account/buyslots\">купить дополнительные слоты</a> для объявлений, либо удалить старые объявления для освобождения места для новых.', 'Доска Объявлений', NULL, 'admin@gribupardot.sunweb.by', NULL, 3, 100, '6289673.png', 9, 30, 50, 1, 30, 20, 1, '<strong>Выполнено!</strong> Ваше объявление успешно добавлено и опубликовано.', '17220519.png', 10, 0, 2, 1, '&copy; Gribupardot.lv - доска бесплатных объявлений', 'Как правильно указать цену?', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил.&nbsp;<br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления.&nbsp;<br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества;&nbsp;<br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих \"лёгкий заработок\" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br />Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by', '<div class=\"block-rules-faster first\">\r\n<div class=\"block-rules-faster-header rules\">\r\n<h1>Правила размещения</h1>\r\n</div>\r\n<div class=\"block-rules-faster-list\">\r\n<ul class=\"list-unstyled\">\r\n<li>1. Не подавайте одно и то же объявление повторно. Почему?</li>\r\n<li>2. Не телефон, email или адрес сайта в описании или на фото.</li>\r\n<li>3. Не пишите цену в названии, для этого есть отдельное поле.</li>\r\n<li>4. Не продавайте запрещенные товары.</li>\r\n</ul>\r\n</div>\r\n<div class=\"rules-link\"><a href=\"../pages/Pravila-razmescheniya\">Подробнее о правилах</a></div>\r\n</div>\r\n<div class=\"block-rules-faster\">\r\n<div class=\"block-rules-faster-header faster\">\r\n<h1>Как продать быстрее?</h1>\r\n</div>\r\n<div class=\"block-rules-faster-list\">\r\n<ul class=\"list-unstyled\">\r\n<li>Устанавливайте разумную цену - недорогие товары продаются гораздо быстрее. Как это?</li>\r\n<li>Добавляете фотографии - хорошие фото привлекают больше внимания.</li>\r\n<li>Подробно описывайте товар - это поможет будущему покупателю.</li>\r\n<li>Выберите пакет \"премиум размещение\" или \"выделить объявление\".</li>\r\n</ul>\r\n</div>\r\n</div>', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `textblock`
--

CREATE TABLE `textblock` (
  `id` int(11) NOT NULL,
  `how_to_set_price` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agreement` longtext COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `textblock`
--

INSERT INTO `textblock` (`id`, `how_to_set_price`, `user_agreement`) VALUES
(1, 'Как правильно указать цену?', '<strong>Правила размещения объявлений на gribupardot.sunweb.by</strong><br /><br />Все перечисленные ниже правила распространяются на всех пользователей и рекламодателей без исключения. Администрация имеет право удалять объявление без предупреждения, если оно нарушает любое из Правил.&nbsp;<br />За правдивость всей предоставленной в объявлении информации и за возможные последствия, возникшие в результате размещения объявления на сайте gribupardot.sunweb.by, ответственность несет автор объявления.&nbsp;<br />Систематические нарушения любого из нижеперечисленных Правил приводят к блокированию учетной записи.<br />Администрация оставляет за собой право потребовать от пользователя дополнительную информацию (фото, описание и т.д.) о его товара или услугах.<br />Администрация gribupardot.sunweb.by оставляет за собой право на изменение и/или обновление данных Правил в любое время без предварительного предупреждения.<br /><br />После подачи объявления оно отправляется на модерацию. После модерации оно будет опубликовано или отправлено на правку. Модерация может занимать от 1 минуты до 6 часов (в зависимости от времени суток).<br /><br />1. Название объявления.<br />Данная строка объявления должна быть краткой, информативной и привлекательной, желательно с указанием наименования предлагаемого товара, услуги. Это первое, на что обращает внимание потенциальный покупатель. Постарайтесь сделать его точным и лаконичным.<br />Запрещается:<br />- использование заглавных букв;<br />- использование разнообразных символов для украшения;<br />- использование набора повторяющихся ключевых слов и фраз.<br /><br />2. Раздел и Рубрика.<br />Советуем выбирать раздел и рубрику, которые максимально приближены к содержанию вашего объявления. Это позволит, увеличит его шансы быть найденными клиентами или поисковыми системами.<br />Объявления, помещенные в неверный раздел или рубрику могут быть удалены или перемещены в верный раздел.<br /><br /><br />3. Цена.<br />В каждом объявление должна быть выставлена актуальная цена. Если цена изменяется, по каким, либо причинам, её необходимо изменять и в объявлении. Если в объявлении несколько товаров, то в описании должны быть перечисленны все цены на продаваемые позиции.<br /><br />4. Описание.<br />Описание должно соответствовать полностью названию объявления. Первые строчки должны быть наиболее привлекательными и раскрывать все достоинства предоставляемого товара или услуги. Описание не должно содержать ошибок и опечаток. Все детали, характеристики и особенности должны быть указаны в описании. Запрещается размещение ссылок на конкурирующие ресурсы.<br /><br />5. Фотографии.<br />Для повышения спроса на размещенное объявление следует добавлять фотографии. Фотография, демонстрирующая товар или услугу, должна соответствовать названию и тексту объявления. На размещенной фотографии должен быть изображен только предаваемый товар.<br />Запрещается:<br />- добавление фотографий плохого качества;&nbsp;<br />- размещение фотографии эротического или порнографического содержания;<br />- размещение фотографий со ссылками на конкурирующие ресурсы.<br /><br />6. Электронный адрес.<br />Одному пользователю на сайте gribupardot.sunweb.by доступна только одна учетная запись. Учетные записи, принадлежащие одному автору (определяется при помощи технического анализа) отмечаются системой как дублированные и блокируются автоматически.<br /><br />7. Телефон.<br />Не забывайте указывать вашу контактную информацию: телефон, данное действие увеличит шансы на совершение быстрой и успешной сделки.<br />Не забывайте, что указание чужого номера запрещено и приводит к блокировке учетной записи без права восстановления.<br /><br />Запрещается:<br />- размещение объявлений предлагающих \"лёгкий заработок\" в интернете;<br />- размещение объявлений с предложением перечисления куда-либо денег;<br />- давать ссылки в переписке под чужим объявлением с целью саморекламы в чужом объявлении;<br />- создание объявлений, рекламирующие конкурирующие ресурсы;<br />- создание однотипных объявлений с одним и тем же товаром;<br />- размещать объявления, а также рекламировать ресурсы, содержащие информацию о товарах и услугах, расцениваемых действующим законодательством Украины как незаконные либо требующие специального разрешения, либо содержащие контент для взрослых.<br /><br />Использование данного сайта означает согласие пользователя с лицензионным соглашением об условиях использования gribupardot.sunweb.by');

-- --------------------------------------------------------

--
-- Структура таблицы `translation`
--

CREATE TABLE `translation` (
  `id` int(11) NOT NULL,
  `locale_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `value` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
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
(32, 1, 27, 'Легковые автомобили', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 2, 27, 'Легковые автомобили', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
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
(141, 2, NULL, 'Вариатор', NULL, NULL, NULL, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `is_confirm` tinyint(1) DEFAULT 0,
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
  `enter_count` int(11) NOT NULL DEFAULT 0,
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
  `emailmessagesalerts` tinyint(1) DEFAULT 0,
  `emailmessagesreminders` tinyint(1) DEFAULT 0,
  `region_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT 0,
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
  `action` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `current_balanse` int(11) DEFAULT 0
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

-- --------------------------------------------------------

--
-- Структура таблицы `value_linked_filters`
--

CREATE TABLE `value_linked_filters` (
  `filter_value_id` int(11) NOT NULL,
  `filter_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `value_linked_filters`
--

INSERT INTO `value_linked_filters` (`filter_value_id`, `filter_id`) VALUES
(58, 17),
(59, 17);

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
-- Индексы таблицы `filter_linked_values`
--
ALTER TABLE `filter_linked_values`
  ADD PRIMARY KEY (`filter_value_source`,`filter_value_target`),
  ADD KEY `IDX_775484B1264C021` (`filter_value_source`),
  ADD KEY `IDX_775484B11B8190AE` (`filter_value_target`);

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
-- Индексы таблицы `value_linked_filters`
--
ALTER TABLE `value_linked_filters`
  ADD PRIMARY KEY (`filter_value_id`,`filter_id`),
  ADD KEY `IDX_C8FF8542C44FBE02` (`filter_value_id`),
  ADD KEY `IDX_C8FF8542D395B25E` (`filter_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `filter_value`
--
ALTER TABLE `filter_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

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
-- Ограничения внешнего ключа таблицы `filter_linked_values`
--
ALTER TABLE `filter_linked_values`
  ADD CONSTRAINT `FK_775484B11B8190AE` FOREIGN KEY (`filter_value_target`) REFERENCES `filter_value` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_775484B1264C021` FOREIGN KEY (`filter_value_source`) REFERENCES `filter_value` (`id`) ON DELETE CASCADE;

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

--
-- Ограничения внешнего ключа таблицы `value_linked_filters`
--
ALTER TABLE `value_linked_filters`
  ADD CONSTRAINT `FK_C8FF8542C44FBE02` FOREIGN KEY (`filter_value_id`) REFERENCES `filter_value` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_C8FF8542D395B25E` FOREIGN KEY (`filter_id`) REFERENCES `filter` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
