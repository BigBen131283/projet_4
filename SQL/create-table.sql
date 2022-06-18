-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema projet4
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema projet4
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `projet4` DEFAULT CHARACTER SET utf8 ;
USE `projet4` ;

-- -----------------------------------------------------
-- Table `projet4`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projet4`.`users` ;

CREATE TABLE IF NOT EXISTS `projet4`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(128) NOT NULL,
  `pwd` VARCHAR(64) NOT NULL,
  `pseudo` VARCHAR(64) NOT NULL,
  `status` VARCHAR(3) NOT NULL,
  `role` VARCHAR(3) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `projet4`.`billets`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projet4`.`billets` ;

CREATE TABLE IF NOT EXISTS `projet4`.`billets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `text` TEXT NOT NULL,
  `pub_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `users_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



-- -----------------------------------------------------
-- Table `projet4`.`comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projet4`.`comments` ;

CREATE TABLE IF NOT EXISTS `projet4`.`comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `text` TEXT NOT NULL,
  `like` TINYINT(4) NOT NULL,
  `dislike` TINYINT(4) NOT NULL,
  `users_id` INT(11) NOT NULL,
  `billets_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- CREATE INDEX `fk_comments_users1_idx` ON `projet4`.`comments` (`users_id` ASC);
ALTER TABLE projet4.comments ADD FOREIGN KEY `fk_comments_users` (users_id) 
    REFERENCES projet4.users (id);

CREATE INDEX `fk_comments_billets1_idx` ON `projet4`.`comments` (`billets_id` ASC);

CREATE INDEX `fk_billets_users_idx` ON `projet4`.`billets` (`users_id` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

