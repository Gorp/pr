# SQL Manager 2007 for MySQL 4.3.4.1
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : pr_uz_ua


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

SET FOREIGN_KEY_CHECKS=0;



#
# Structure for the `gallery` table : 
#

CREATE TABLE `gallery` (
  `idgallery` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) COLLATE utf8_general_ci DEFAULT NULL COMMENT 'назва галереї для адмінки',
  PRIMARY KEY (`idgallery`)
)ENGINE=MyISAM
AUTO_INCREMENT=5 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `image` table : 
#

CREATE TABLE `image` (
  `idimage` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(45) COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'шлях до картинки',
  `idgallery` INTEGER(11) DEFAULT NULL,
  `sort` INTEGER(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idimage`),
  KEY `fk-gallery` (`idgallery`)
)ENGINE=MyISAM
AUTO_INCREMENT=12 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `menu` table : 
#

CREATE TABLE `menu` (
  `idmenu` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `url-ua` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `url-ru` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `name` VARCHAR(45) COLLATE utf8_general_ci DEFAULT NULL COMMENT 'назва для адмінки',
  `parent` INTEGER(11) NOT NULL DEFAULT '0' COMMENT 'указати батьківський id, ящо нема то ставимо 0',
  `sort` TINYINT(4) NOT NULL DEFAULT '0',
  `idpage` INTEGER(11) DEFAULT NULL,
  PRIMARY KEY (`idmenu`)
)ENGINE=MyISAM
AUTO_INCREMENT=7 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `page` table : 
#

CREATE TABLE `page` (
  `idpage` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) COLLATE utf8_general_ci DEFAULT NULL,
  `url` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `keyword` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `description` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `richtext` TEXT COLLATE utf8_general_ci,
  `idgallery` INTEGER(11) DEFAULT '0',
  PRIMARY KEY (`idpage`)
)ENGINE=MyISAM
AUTO_INCREMENT=25 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

#
# Structure for the `users` table : 
#

CREATE TABLE `users` (
  `idusers` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `password` VARCHAR(45) COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idusers`)
)ENGINE=MyISAM
AUTO_INCREMENT=1 CHARACTER SET 'latin1' COLLATE 'latin1_swedish_ci';

#
# Data for the `gallery` table  (LIMIT 0,500)
#

INSERT INTO `gallery` (`idgallery`, `name`) VALUES 
  (2,'22223'),
  (4,'dewdd');
COMMIT;

#
# Data for the `menu` table  (LIMIT 0,500)
#

INSERT INTO `menu` (`idmenu`, `url-ua`, `url-ru`, `name`, `parent`, `sort`, `idpage`) VALUES 
  (5,NULL,NULL,'llll',6,0,NULL),
  (6,NULL,NULL,'jjj',0,0,NULL);
COMMIT;

#
# Data for the `page` table  (LIMIT 0,500)
#

INSERT INTO `page` (`idpage`, `title`, `url`, `keyword`, `description`, `richtext`, `idgallery`) VALUES 
  (16,'Кешаня',NULL,NULL,'Попугай','Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший',0),
  (17,'Гарна Назва 1',NULL,NULL,'Гарний Опис 1 ++','<p>\r\n\tТекст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n\t&nbsp;</div>',0),
  (21,'Samsung Monte SGH-5614616',NULL,NULL,'телефон з сенсорним екраном','<p>\r\n\tБагато інформації про телефон з сенсорним екраном Samsung Monte gjhkijjjjjjjjj</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n\t&nbsp;</div>',0),
  (23,'Нова сторінка2',NULL,NULL,'нова','<p style=\"text-align: center;\">\r\n\t<img alt=\"\" src=\"/public/img/eq_iceberg1.jpg\" /></p>\r\n<p style=\"text-align: center;\">\r\n\t&nbsp;</p>\r\n<p>\r\n\t<em><u><strong>фвфвфвфвфц</strong></u></em></p>',0);
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;