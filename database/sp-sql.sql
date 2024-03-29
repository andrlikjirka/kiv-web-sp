-- MySQL Script generated by MySQL Workbench
-- Wed Dec  8 14:55:32 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `jandrlik_prava`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jandrlik_prava` ;

CREATE TABLE IF NOT EXISTS `jandrlik_prava` (
  `id_pravo` INT NOT NULL AUTO_INCREMENT,
  `nazev` VARCHAR(20) NOT NULL,
  `vaha` INT NOT NULL,
  PRIMARY KEY (`id_pravo`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;


-- -----------------------------------------------------
-- Table `jandrlik_uzivatele`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jandrlik_uzivatele` ;

CREATE TABLE IF NOT EXISTS `jandrlik_uzivatele` (
  `id_uzivatel` INT NOT NULL AUTO_INCREMENT,
  `id_pravo` INT NOT NULL,
  `jmeno` VARCHAR(35) NOT NULL,
  `prijmeni` VARCHAR(35) NOT NULL,
  `login` VARCHAR(30) NOT NULL,
  `heslo` VARCHAR(200) NOT NULL,
  `email` VARCHAR(35) NOT NULL,
  `povolen` TINYINT NOT NULL,
  PRIMARY KEY (`id_uzivatel`),
  INDEX `fk_jandrlik_uzivatele_jandrlik_prava_idx` (`id_pravo` ASC) ,
  UNIQUE INDEX `login_UNIQUE` (`login` ASC) ,
  CONSTRAINT `fk_jandrlik_uzivatele_jandrlik_prava`
    FOREIGN KEY (`id_pravo`)
    REFERENCES `jandrlik_prava` (`id_pravo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;


-- -----------------------------------------------------
-- Table `jandrlik_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jandrlik_status` ;

CREATE TABLE IF NOT EXISTS `jandrlik_status` (
  `id_status` INT NOT NULL AUTO_INCREMENT,
  `nazev` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_status`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;


-- -----------------------------------------------------
-- Table `jandrlik_prispevky`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jandrlik_prispevky` ;

CREATE TABLE IF NOT EXISTS `jandrlik_prispevky` (
  `id_prispevek` INT NOT NULL AUTO_INCREMENT,
  `id_uzivatel` INT NOT NULL,
  `id_status` INT NOT NULL,
  `nadpis` VARCHAR(250) NOT NULL,
  `abstrakt` LONGTEXT NOT NULL,
  `dokument` VARCHAR(60) NOT NULL,
  `datum` DATETIME NOT NULL,
  INDEX `fk_jandrlik_prispevky_jandrlik_uzivatele1_idx` (`id_uzivatel` ASC) ,
  INDEX `fk_jandrlik_prispevky_jandrlik_status1_idx` (`id_status` ASC) ,
  PRIMARY KEY (`id_prispevek`),
  CONSTRAINT `fk_jandrlik_prispevky_jandrlik_uzivatele1`
    FOREIGN KEY (`id_uzivatel`)
    REFERENCES `jandrlik_uzivatele` (`id_uzivatel`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_jandrlik_prispevky_jandrlik_status1`
    FOREIGN KEY (`id_status`)
    REFERENCES `jandrlik_status` (`id_status`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;


-- -----------------------------------------------------
-- Table `jandrlik_hodnoceni`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jandrlik_hodnoceni` ;

CREATE TABLE IF NOT EXISTS `jandrlik_hodnoceni` (
  `id_hodnoceni` INT NOT NULL AUTO_INCREMENT,
  `id_uzivatel` INT NOT NULL,
  `id_prispevek` INT NOT NULL,
  `obsah` SMALLINT NULL,
  `jazyk` SMALLINT NULL,
  `odbornost` SMALLINT NULL,
  PRIMARY KEY (`id_hodnoceni`, `id_uzivatel`, `id_prispevek`),
  INDEX `fk_jandrlik_hodnoceni_jandrlik_uzivatele1_idx` (`id_uzivatel` ASC) ,
  INDEX `fk_jandrlik_hodnoceni_jandrlik_prispevky1_idx` (`id_prispevek` ASC) ,
  CONSTRAINT `fk_jandrlik_hodnoceni_jandrlik_uzivatele1`
    FOREIGN KEY (`id_uzivatel`)
    REFERENCES `jandrlik_uzivatele` (`id_uzivatel`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_jandrlik_hodnoceni_jandrlik_prispevky1`
    FOREIGN KEY (`id_prispevek`)
    REFERENCES `jandrlik_prispevky` (`id_prispevek`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_czech_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `jandrlik_prava`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `jandrlik_prava` (`id_pravo`, `nazev`, `vaha`) VALUES (1, 'SuperAdmin', 20);
INSERT INTO `jandrlik_prava` (`id_pravo`, `nazev`, `vaha`) VALUES (2, 'Admin', 10);
INSERT INTO `jandrlik_prava` (`id_pravo`, `nazev`, `vaha`) VALUES (3, 'Recenzent', 5);
INSERT INTO `jandrlik_prava` (`id_pravo`, `nazev`, `vaha`) VALUES (4, 'Autor', 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `jandrlik_uzivatele`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `jandrlik_uzivatele` (`id_uzivatel`, `id_pravo`, `jmeno`, `prijmeni`, `login`, `heslo`, `email`, `povolen`) VALUES (1, 1, 'Jiří', 'Andrlík', 'jandrlik', '$2y$10$egNcMjToWAj.puZucstFLuHTjuMJdg34vXWujqDzDabqMTYLXXih2', 'jandrlik@email.cz', 1);
INSERT INTO `jandrlik_uzivatele` (`id_uzivatel`, `id_pravo`, `jmeno`, `prijmeni`, `login`, `heslo`, `email`, `povolen`) VALUES (2, 2, 'Karel', 'Novák', 'knovak', '$2y$10$hJQQREUFyXRMNbCkG/5GmurZrik7XesCIa/rDfIiWj76XxpqDMF9e', 'knovak@email.cz', 1);
INSERT INTO `jandrlik_uzivatele` (`id_uzivatel`, `id_pravo`, `jmeno`, `prijmeni`, `login`, `heslo`, `email`, `povolen`) VALUES (3, 3, 'František', 'Nový', 'fnovy', '$2y$10$g7mWWJwZILujKDO.o1OU7O1OiFmuBNjtWVspNIBadnryA6RS6TGpW', 'fnovy@email.cz', 1);
INSERT INTO `jandrlik_uzivatele` (`id_uzivatel`, `id_pravo`, `jmeno`, `prijmeni`, `login`, `heslo`, `email`, `povolen`) VALUES (4, 4, 'Tomáš', 'Holý', 'tholy', '$2y$10$9y/o6LJSlH8wujcH9qzfHOeAQoL0XDRd96hQWXKDopGNiznjLEMnm', 'tholy@email.cz', 1);
INSERT INTO `jandrlik_uzivatele` (`id_uzivatel`, `id_pravo`, `jmeno`, `prijmeni`, `login`, `heslo`, `email`, `povolen`) VALUES (5, 4, 'Adam', 'Krátký', 'akratky', '$2y$10$yUV6yE3H.DAQZNLSTcYJUOFxC9NWG/Y1ToFqtmZ/EJwsKCga3ZwWu', 'akratky@email.cz', 1);
INSERT INTO `jandrlik_uzivatele` (`id_uzivatel`, `id_pravo`, `jmeno`, `prijmeni`, `login`, `heslo`, `email`, `povolen`) VALUES (6, 3, 'Kamila', 'Urbanová', 'kurbanova', '$2y$10$NkGJBHp/QYeoy.QxPf897OurUlQRDQY0QaYOCQZtAs8WmgMPx4TRq', 'kurbanova@email.cz', 1);
INSERT INTO `jandrlik_uzivatele` (`id_uzivatel`, `id_pravo`, `jmeno`, `prijmeni`, `login`, `heslo`, `email`, `povolen`) VALUES (7, 4, 'Tomáš', 'Dlouhý', 'tdlouhy', '$2y$10$nvQUm.dS.rGfv3p1KyLcVOJR4EMnuYqQEWgpiBEoUjBpIspfzijrK', 'tdlouhy@email.cz', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `jandrlik_status`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `jandrlik_status` (`id_status`, `nazev`) VALUES (1, 'Čeká na posouzení');
INSERT INTO `jandrlik_status` (`id_status`, `nazev`) VALUES (2, 'Schváleno');
INSERT INTO `jandrlik_status` (`id_status`, `nazev`) VALUES (3, 'Zamítnuto');

COMMIT;

