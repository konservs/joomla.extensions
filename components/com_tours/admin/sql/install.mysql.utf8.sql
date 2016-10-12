-- --------------------------------------------------------
-- Сервер:                       127.0.0.1
-- Версія сервера:               5.6.19 - MySQL Community Server (GPL)
-- ОС сервера:                   Win64
-- HeidiSQL Версія:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for таблиця test2.#__tours_categories
DROP TABLE IF EXISTS `#__tours_categories`;
CREATE TABLE IF NOT EXISTS `#__tours_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` mediumtext NOT NULL,
  `name` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Категории туров';

-- Dumping data for table test2.#__tours_categories: ~3 rows (приблизно)
DELETE FROM `#__tours_categories`;
/*!40000 ALTER TABLE `#__tours_categories` DISABLE KEYS */;
INSERT INTO `#__tours_categories` (`id`, `alias`, `name`) VALUES
	(1, 'children', 'Для детей'),
	(2, 'family', 'Семейный отдых'),
	(3, 'honey-month', 'Медовый месяц');
/*!40000 ALTER TABLE `#__tours_categories` ENABLE KEYS */;


-- Dumping structure for таблиця test2.#__tours_cities
DROP TABLE IF EXISTS `#__tours_cities`;
CREATE TABLE IF NOT EXISTS `#__tours_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` int(11) NOT NULL,
  `alias` text COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_#__tours_cities_#__tours_countries` (`country`),
  CONSTRAINT `FK_#__tours_cities_#__tours_countries` FOREIGN KEY (`country`) REFERENCES `#__tours_countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Таблица с городами';

-- Dumping data for table test2.#__tours_cities: ~2 rows (приблизно)
DELETE FROM `#__tours_cities`;
/*!40000 ALTER TABLE `#__tours_cities` DISABLE KEYS */;
INSERT INTO `#__tours_cities` (`id`, `country`, `alias`, `name`) VALUES
	(1, 1, 'kyiv', 'Киев'),
	(2, 1, 'chernivtsi', 'Черновцы');
/*!40000 ALTER TABLE `#__tours_cities` ENABLE KEYS */;


-- Dumping structure for таблиця test2.#__tours_countries
DROP TABLE IF EXISTS `#__tours_countries`;
CREATE TABLE IF NOT EXISTS `#__tours_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` text COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Таблица со странами';

-- Dumping data for table test2.#__tours_countries: ~4 rows (приблизно)
DELETE FROM `#__tours_countries`;
/*!40000 ALTER TABLE `#__tours_countries` DISABLE KEYS */;
INSERT INTO `#__tours_countries` (`id`, `alias`, `name`) VALUES
	(1, 'ukraine', 'Украина'),
	(2, 'russia', 'Россия'),
	(3, 'bulgaria', 'Болгария'),
	(4, 'greece', 'Греция');
/*!40000 ALTER TABLE `#__tours_countries` ENABLE KEYS */;


-- Dumping structure for таблиця test2.#__tours_orders
DROP TABLE IF EXISTS `#__tours_orders`;
CREATE TABLE IF NOT EXISTS `#__tours_orders` (
  `id` int(11) NOT NULL,
  `tour` int(11) DEFAULT NULL,
  `firstname` varchar(150) NOT NULL COMMENT 'Имя',
  `lastname` varchar(150) NOT NULL COMMENT 'Фамилия',
  `middlename` varchar(150) NOT NULL COMMENT 'Отчество',
  `phone` varchar(150) NOT NULL COMMENT 'Телефон',
  `email` varchar(150) NOT NULL,
  `adults` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `comment` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_#__tours_orders_#__tours` (`tour`),
  CONSTRAINT `FK_#__tours_orders_#__tours` FOREIGN KEY (`tour`) REFERENCES `#__tours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Заявки';

-- Dumping data for table test2.#__tours_orders: ~0 rows (приблизно)
DELETE FROM `#__tours_orders`;
/*!40000 ALTER TABLE `#__tours_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__tours_orders` ENABLE KEYS */;


-- Dumping structure for таблиця test2.#__tours
DROP TABLE IF EXISTS `#__tours`;
CREATE TABLE IF NOT EXISTS `#__tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `alias` text COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `country` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `dt_start` date NOT NULL,
  `dt_end` date NOT NULL,
  `hotel_name` text COLLATE utf8_bin NOT NULL COMMENT 'Название отеля',
  `hotel_url` text COLLATE utf8_bin NOT NULL COMMENT 'Ссылка на отель',
  `hotel_stars` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Кол-во звезд отеля',
  `description_short` mediumtext COLLATE utf8_bin NOT NULL COMMENT 'Краткое описание',
  `description_food` mediumtext COLLATE utf8_bin NOT NULL COMMENT 'Информация о питании',
  `price_adult` float NOT NULL COMMENT 'Цена тура для взрослого',
  `price_child` float NOT NULL COMMENT 'Цена тура для ребенка',
  PRIMARY KEY (`id`),
  KEY `FK_#__tours_#__tours_countries` (`country`),
  KEY `FK_#__tours_#__tours_cities` (`city`),
  CONSTRAINT `FK_#__tours_#__tours_cities` FOREIGN KEY (`city`) REFERENCES `#__tours_cities` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `FK_#__tours_#__tours_countries` FOREIGN KEY (`country`) REFERENCES `#__tours_countries` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Таблица с турами';

-- Dumping data for table test2.#__tours: ~1 rows (приблизно)
DELETE FROM `#__tours`;
/*!40000 ALTER TABLE `#__tours` DISABLE KEYS */;
INSERT INTO `#__tours` (`id`, `published`, `alias`, `name`, `country`, `city`, `dt_start`, `dt_end`, `hotel_name`, `hotel_url`, `hotel_stars`, `description_short`, `description_food`, `price_adult`, `price_child`) VALUES
	(1, 0, 'kyiv-tour-test', 'Мега Тур в Киев', 1, 1, '2014-10-10', '2014-10-20', 'Адрія', 'http://www.booking.com/hotel/ua/adria.uk.html?aid=332239', 3, '', '', 120, 100);
/*!40000 ALTER TABLE `#__tours` ENABLE KEYS */;


-- Dumping structure for таблиця test2.#__tours_categories
DROP TABLE IF EXISTS `#__tours_categories`;
CREATE TABLE IF NOT EXISTS `#__tours_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_#__tours_categories_#__tours` (`tour`),
  KEY `FK_#__tours_categories_#__tours_categories` (`category`),
  CONSTRAINT `FK_#__tours_categories_#__tours` FOREIGN KEY (`tour`) REFERENCES `#__tours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_#__tours_categories_#__tours_categories` FOREIGN KEY (`category`) REFERENCES `#__tours_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Связь тур - категория';

-- Dumping data for table test2.#__tours_categories: ~2 rows (приблизно)
DELETE FROM `#__tours_categories`;
/*!40000 ALTER TABLE `#__tours_categories` DISABLE KEYS */;
INSERT INTO `#__tours_categories` (`id`, `tour`, `category`) VALUES
	(1, 1, 1),
	(2, 1, 3);
/*!40000 ALTER TABLE `#__tours_categories` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
