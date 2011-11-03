ALTER TABLE `blog_entry` ADD COLUMN `author` VARCHAR(45) NULL DEFAULT 'admin'  AFTER `date` ;
ALTER TABLE `blog_entry` ADD COLUMN `email` VARCHAR(245) NULL  AFTER `author` ;

ALTER TABLE `gallery` CHANGE COLUMN `type` `type` ENUM('image','video','audio') NOT NULL DEFAULT 'image'  ;
ALTER TABLE `gallery` ADD COLUMN `data` VARCHAR(45) NULL  AFTER `type` ;
ALTER TABLE `gallery` CHANGE COLUMN `data` `data` TEXT NULL DEFAULT NULL  ;
ALTER TABLE `page` ADD COLUMN `idaudio` INT NULL DEFAULT 0  AFTER `lang` ;

delimiter $$

CREATE TABLE `counter` (
  `idcounter` varchar(45) NOT NULL,
  `ip` varchar(45) NOT NULL,
  PRIMARY KEY (`idcounter`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1$$




