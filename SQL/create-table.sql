-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema projet4
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema projet4
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `projet4` DEFAULT CHARACTER SET utf8 ;
USE `projet4` ;

-- -----------------------------------------------------
-- DROP Tables if exists
-- -----------------------------------------------------

DROP TABLE IF EXISTS projet4.comments ;
DROP TABLE IF EXISTS projet4.billets ;
DROP TABLE IF EXISTS projet4.users ;

-- -----------------------------------------------------
-- Table projet4.users
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS projet4.users (
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(128) NOT NULL,
  pwd VARCHAR(64) NOT NULL,
  pseudo VARCHAR(64) NOT NULL,
  userstatus VARCHAR(64) NOT NULL DEFAULT 'ACTIVE' CHECK (userstatus in ('ACTIVE', 'SUSPENDED', 'DELETED')),
  userrole VARCHAR(64) NOT NULL DEFAULT 'READER' CHECK (userrole in ('AUTHOR', 'READER')),
  PRIMARY KEY (id, email, pseudo))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table projet4.billets
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS projet4.billets (
  id INT NOT NULL AUTO_INCREMENT,
  content TEXT NOT NULL,
  pub_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  published BOOLEAN DEFAULT FALSE,
  user_id INT NOT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



-- -----------------------------------------------------
-- Table projet4.comments
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS projet4.comments (
  id INT NOT NULL AUTO_INCREMENT,
  content TEXT NOT NULL,
  pub_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  thumbs_up INT NOT NULL DEFAULT 0,
  thumbs_down INT NOT NULL DEFAULT 0,
  moderation VARCHAR(64) NOT NULL DEFAULT 'PUBLISHED' CHECK (moderation in ('PUBLISHED', 'SIGNALED', 'REVIEW_OK', 'REVIEW_KO')), 
  user_id INT NOT NULL,
  billet_id INT NOT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- CREATE INDEX fk_comments_users1_idx ON projet4.comments (users_id ASC);
-- ALTER TABLE projet4.comments ADD FOREIGN KEY fk_comments_users (users_id) 
--     REFERENCES projet4.users (id);
-- CREATE INDEX fk_comments_billets1_idx ON projet4.comments (billets_id ASC);
-- CREATE INDEX fk_billets_users_idx ON projet4.billets (users_id ASC);

ALTER TABLE projet4.billets ADD CONSTRAINT fk_billets_to_users FOREIGN KEY (user_id) 
  REFERENCES projet4.users(id);
ALTER TABLE projet4.comments ADD CONSTRAINT fk_comments_to_users FOREIGN KEY (user_id)
  REFERENCES projet4.users(id);
ALTER TABLE projet4.comments ADD CONSTRAINT fk_comments_to_billets FOREIGN KEY (billet_id)
  REFERENCES projet4.billets(id);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

--
