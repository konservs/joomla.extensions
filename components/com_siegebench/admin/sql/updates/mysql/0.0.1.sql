SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE TABLE IF NOT EXISTS `#__siegebench_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(200) NOT NULL,
  `score` int(11) NOT NULL COMMENT 'Попугаи бенчмарка',
  `hardware` text NOT NULL COMMENT 'JSON с железом',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

