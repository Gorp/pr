ALTER TABLE `pr_uz_ua`.`blog_entry` ADD COLUMN `author` VARCHAR(45) NULL DEFAULT 'admin'  AFTER `date` ;
ALTER TABLE `pr_uz_ua`.`blog_entry` ADD COLUMN `email` VARCHAR(245) NULL  AFTER `author` ;

ALTER TABLE `pr_uz_ua`.`gallery` CHANGE COLUMN `type` `type` ENUM('image','video','audio') NOT NULL DEFAULT 'image'  ;
ALTER TABLE `pr_uz_ua`.`gallery` ADD COLUMN `data` VARCHAR(45) NULL  AFTER `type` ;
ALTER TABLE `pr_uz_ua`.`gallery` CHANGE COLUMN `data` `data` TEXT NULL DEFAULT NULL  ;
ALTER TABLE `pr_uz_ua`.`page` ADD COLUMN `idaudio` INT NULL DEFAULT 0  AFTER `lang` ;


