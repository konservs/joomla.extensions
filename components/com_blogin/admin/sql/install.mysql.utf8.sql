CREATE TABLE `#__blogin_phones` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`userid` INT(11) NULL DEFAULT NULL,
	`phone` VARCHAR(15) NOT NULL COLLATE 'utf8_general_ci',
	`codesent` DATETIME NULL DEFAULT NULL,
	`codesms` CHAR(10) NOT NULL COLLATE 'utf8_general_ci',
	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `phone` (`phone`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
