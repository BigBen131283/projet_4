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

DROP TABLE IF EXISTS projet4.likes ;
DROP TABLE IF EXISTS projet4.comments ;
DROP TABLE IF EXISTS projet4.billets ;
DROP TABLE IF EXISTS projet4.users ;

-- -----------------------------------------------------
-- Table projet4.users
-- status 10 inscrit en attente
-- status 20 inscrit
-- status 30 suspendu
-- status 40 supprimé
--
-- role 10 Author
-- role 20 Reader
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS projet4.users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  email VARCHAR(128) NOT NULL UNIQUE,
  password VARCHAR(256) NOT NULL,
  pseudo VARCHAR(64) NOT NULL UNIQUE,
  status INT(11) NOT NULL DEFAULT 10,
  role INT(11) NOT NULL DEFAULT 20,
  profile_picture VARCHAR(64) NOT NULL DEFAULT 'defaultuserpicture.png',
  PRIMARY KEY (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- CREATE UNIQUE INDEX `uniqueUser` ON projet4.users(`email`, `pseudo`) USING BTREE;

-- -----------------------------------------------------
-- Table projet4.billets
--
-- published 1 le billet est publié
-- published 0 le billet sera publié plus tard
--
-- Pas de valeur par défaut de publish_at, 
-- aller chercher la date ou la renseigner manuellement pour différer la publication
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS projet4.billets (
  id INT(11) NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL UNIQUE,
  abstract VARCHAR(255) NOT NULL,
  chapter TEXT NOT NULL,
  publish_at DATETIME NOT NULL,
  published TINYINT NOT NULL DEFAULT 1,
  users_id INT NOT NULL,
  thumbs_up INT(11) NOT NULL DEFAULT 0,
  thumbs_down INT(11) NOT NULL DEFAULT 0,
  chapter_picture VARCHAR(64) NOT NULL DEFAULT 'default.jpg',
  PRIMARY KEY (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



-- -----------------------------------------------------
-- Table projet4.comments
--
-- report 30 commentaire publié et visible
-- report 20 commentaire signalé et masqué
-- report 10 commentaire modéré et masqué/supprimé
-- report 40 commentaire modéré et accepté ne peut plus être signalé
--
-- Pas de date par défaut, aller chercher la date lors de la publication du commentaire
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS projet4.comments (
  id INT NOT NULL AUTO_INCREMENT,
  content VARCHAR(255) NOT NULL,
  publish_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  report INT(11) NOT NULL DEFAULT 30, 
  users_id INT(11) NOT NULL,
  billet_id INT(11) NOT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table projet4.likes
-- 0 si n'aime pas
-- 1 si aime
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS projet4.likes(
  users_id INT(11) NOT NULL,
  billets_id INT(11) NOT NULL,
  like_it TINYINT (1) NOT NULL
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Foreign Keys
-- -----------------------------------------------------

ALTER TABLE projet4.billets ADD CONSTRAINT fk_billets_to_users FOREIGN KEY (users_id) 
  REFERENCES projet4.users(id);
ALTER TABLE projet4.comments ADD CONSTRAINT fk_comments_to_users FOREIGN KEY (users_id)
  REFERENCES projet4.users(id);
ALTER TABLE projet4.comments ADD CONSTRAINT fk_comments_to_billets FOREIGN KEY (billet_id)
  REFERENCES projet4.billets(id);
ALTER TABLE projet4.likes ADD CONSTRAINT fk_likes_to_users FOREIGN KEY (users_id)
  REFERENCES projet4.users(id);
ALTER TABLE projet4.likes ADD CONSTRAINT fk_likes_to_billets FOREIGN KEY (billets_id)
  REFERENCES projet4.billets(id);

-- SET SQL_MODE=@OLD_SQL_MODE;
-- SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
-- SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Log table
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS projet4.logs(
  logid INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  logtime DATETIME DEFAULT CURRENT_TIMESTAMP,
  logmessage varchar(256) NOT NULL
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table Register / lost password tracking requests 
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS projet4.resets (
  resetid int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  resetactiontype varchar(64) NOT NULL,         -- Will be 'register', 'passreset', etc...
  pseudo VARCHAR(64) NOT NULL,
  selector TEXT NOT NULL,
  token LONGTEXT NOT NULL,
  expires int(32) NOT NULL,
  resetstatus TINYINT NOT NULL,
  requesttime DATETIME DEFAULT CURRENT_TIMESTAMP,
  processedtime DATETIME DEFAULT CURRENT_TIMESTAMP
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- -----------------------------------------------------
