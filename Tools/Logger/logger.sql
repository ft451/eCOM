CREATE SCHEMA `logger`;
USE `logger` ;

CREATE USER 'logger'@'localhost' IDENTIFIED BY 'logger';
GRANT ALL PRIVILEGES ON logger.* TO 'logger'@'localhost';
CREATE USER 'logger'@'127.0.0.1' IDENTIFIED BY 'logger';
GRANT ALL PRIVILEGES ON logger.* TO 'logger'@'127.0.0.1';

-- -----------------------------------------------------
-- Table `logger`.`log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `logger`.`log` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `operacja` VARCHAR(20) NOT NULL ,
  `uuid` VARCHAR(45) NOT NULL ,
  `ip` VARCHAR(50) NOT NULL ,
  `url` VARCHAR(120) NOT NULL ,
  `imei` VARCHAR(15) NOT NULL ,
  `mac` VARCHAR(20) NOT NULL ,
  PRIMARY KEY USING BTREE (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `logger`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `logger`.`user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `login` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL ,
  `password` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;

