CREATE TABLE `#__regoffices_countries` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`iso` CHAR(2) NULL DEFAULT NULL,
	`status` ENUM('P','N','D') NOT NULL DEFAULT 'P',
	`regions` INT(11) NOT NULL DEFAULT '0',
	`cities` INT(11) NOT NULL DEFAULT '0',
	`offices` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `iso` (`iso`)
)COMMENT='Таблица с городами'
ENGINE=InnoDB;




CREATE TABLE `#__regoffices_countries_lang` (
	`country` INT(11) UNSIGNED NOT NULL,
	`language` CHAR(5) NOT NULL,
	`name` VARCHAR(100) NOT NULL,
	`alias` VARCHAR(100) NOT NULL,
	`description` MEDIUMTEXT NOT NULL,
	`h1` TEXT NOT NULL,
	`title` TEXT NOT NULL,
	`metadesc` TEXT NOT NULL,
	`metakeyw` TEXT NOT NULL,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`country`, `language`),
	UNIQUE INDEX `language_alias` (`language`, `alias`),
	CONSTRAINT `FK_regoffices_countries` FOREIGN KEY (`country`) REFERENCES `#__regoffices_countries` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COMMENT='Переводы стран и.'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


CREATE TABLE `#__regoffices_regions` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`admin1code` CHAR(20) NULL DEFAULT NULL,
	`status` ENUM('P','N','D') NOT NULL DEFAULT 'P',
	`country` INT(10) UNSIGNED NOT NULL,
	`citiescount` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `admin1code` (`admin1code`),
	INDEX `FK_regoffices_regions_regoffices_countries` (`country`),
	CONSTRAINT `FK_regoffices_regions_regoffices_countries` FOREIGN KEY (`country`) REFERENCES `#__regoffices_countries` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COMMENT='Регионы стран'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `#__regoffices_regions_lang` (
	`region` INT(10) UNSIGNED NOT NULL,
	`language` CHAR(5) NOT NULL,
	`name` TEXT NOT NULL,
	`alias` TEXT NOT NULL,
	`description` TEXT NOT NULL,
	`h1` TEXT NOT NULL,
	`title` TEXT NOT NULL,
	`metadesc` TEXT NOT NULL,
	`metakeyw` TEXT NOT NULL,
	`metarobots` TINYINT(1) NOT NULL DEFAULT '0',
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`region`, `language`),
	CONSTRAINT `FK_regoffices_regions_lang_regoffices_regions` FOREIGN KEY (`region`) REFERENCES `#__regoffices_regions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COMMENT='Названия регионов стран'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


CREATE TABLE `#__regoffices_cities` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`status` ENUM('P','N','D') NOT NULL DEFAULT 'P',
	`country` INT(10) UNSIGNED NOT NULL,
	`region` INT(10) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_regoffices_cities_regoffices_countries` (`country`),
	INDEX `FK_regoffices_cities_regoffices_regions` (`region`),
	CONSTRAINT `FK_regoffices_cities_regoffices_regions` FOREIGN KEY (`region`) REFERENCES `#__regoffices_regions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_regoffices_cities_regoffices_countries` FOREIGN KEY (`country`) REFERENCES `#__regoffices_countries` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COMMENT='Города'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


CREATE TABLE `#__regoffices_cities_lang` (
	`city` INT(10) UNSIGNED NOT NULL,
	`language` CHAR(5) NOT NULL,
	`name` TEXT NOT NULL,
	`alias` TEXT NOT NULL,
	`description` TEXT NOT NULL,
	`h1` TEXT NOT NULL,
	`title` TEXT NOT NULL,
	`metadesc` TEXT NOT NULL,
	`metakeyw` TEXT NOT NULL,
	`metarobots` TINYINT(1) NOT NULL DEFAULT '0',
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`city`, `language`),
	CONSTRAINT `FK_regoffices_cities_lang_regoffices_cities` FOREIGN KEY (`city`) REFERENCES `#__regoffices_cities` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=InnoDB;



CREATE TABLE `#__regoffices_offices` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`status` ENUM('P','N','D') NOT NULL DEFAULT 'P',
	`country` INT(10) UNSIGNED NOT NULL,
	`region` INT(10) UNSIGNED NOT NULL,
	`city` INT(10) UNSIGNED NOT NULL,
	`lat` DOUBLE NULL DEFAULT NULL,
	`lng` DOUBLE NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_regoffices_offices_regoffices_countries` (`country`),
	INDEX `FK_regoffices_offices_regoffices_regions` (`region`),
	INDEX `FK_regoffices_offices_regoffices_cities` (`city`),
	CONSTRAINT `FK_regoffices_offices_regoffices_countries` FOREIGN KEY (`country`) REFERENCES `#__regoffices_countries` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_regoffices_offices_regoffices_regions` FOREIGN KEY (`region`) REFERENCES `#__regoffices_regions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_regoffices_offices_regoffices_cities` FOREIGN KEY (`city`) REFERENCES `#__regoffices_cities` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COMMENT='Офисы'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


CREATE TABLE `#__regoffices_offices_lang` (
	`office` INT(10) UNSIGNED NOT NULL,
	`language` CHAR(5) NOT NULL,
	`name` TEXT NOT NULL,
	`address` TEXT NOT NULL,
	`phone` TEXT NOT NULL,
	`site` TEXT NOT NULL,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL,
	PRIMARY KEY (`office`, `language`),
	CONSTRAINT `FK_regoffices_offices_lang_regoffices_offices` FOREIGN KEY (`office`) REFERENCES `#__regoffices_offices` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
