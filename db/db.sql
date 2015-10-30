-- MySQL Script generated by MySQL Workbench
-- Tue Sep  1 23:29:50 2015
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema penduduk
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema penduduk
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `penduduk` DEFAULT CHARACTER SET utf8 ;
USE `penduduk` ;

-- -----------------------------------------------------
-- Table `penduduk`.`residents`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `penduduk`.`residents` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `gender` CHAR(1) NULL COMMENT 'M or F',
  `dob` DATE NULL,
  `nric` VARCHAR(12) NULL COMMENT '790121010000',
  `phone` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `facebook` VARCHAR(45) NULL,
  `occupation` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `penduduk`.`houses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `penduduk`.`houses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` INT UNSIGNED NULL COMMENT 'House Owner',
  `residents_id` INT UNSIGNED NULL COMMENT 'Who stay at that house. can be rental or owner. only head of family/team',
  `ptb` CHAR(10) NULL COMMENT 'eg: PTB10468 / 24JDJ23/4',
  `house_no` TINYINT UNSIGNED NULL,
  `addr1` VARCHAR(45) NULL COMMENT 'Jalan Dato Jaafar 19',
  `addr2` VARCHAR(45) NULL COMMENT 'Taman Mutiara Desaru',
  `city` VARCHAR(45) NULL COMMENT 'Bandar Penawar',
  `postcode` VARCHAR(45) NULL COMMENT '81930',
  `state` VARCHAR(45) NULL COMMENT 'Johor',
  `country` VARCHAR(45) NULL COMMENT 'Malaysia',
  `country_code` CHAR(2) NULL COMMENT 'MY',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `ptb_idx` (`ptb` ASC),
  INDEX `fk_houses_owner_idx` (`owner_id` ASC),
  INDEX `fk_houses_residents_idx` (`residents_id` ASC),
  CONSTRAINT `fk_houses_owner`
    FOREIGN KEY (`owner_id`)
    REFERENCES `penduduk`.`residents` (`id`)
    ON DELETE SET NULL
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_houses_residents`
    FOREIGN KEY (`residents_id`)
    REFERENCES `penduduk`.`residents` (`id`)
    ON DELETE SET NULL
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `penduduk`.`relationship_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `penduduk`.`relationship_type` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL COMMENT 'spouse, parent/child, tenant',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `penduduk`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `penduduk`.`roles` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL COMMENT 'father, son, husband, wife',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `penduduk`.`relationships`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `penduduk`.`relationships` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `houses_id` INT UNSIGNED NOT NULL,
  `person1_id` INT UNSIGNED NOT NULL,
  `person2_id` INT UNSIGNED NOT NULL,
  `relationship_type_id` INT UNSIGNED NOT NULL,
  `person1_roles_id` INT UNSIGNED NOT NULL,
  `person2_roles_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`, `houses_id`, `person1_id`, `person2_id`, `relationship_type_id`, `person1_roles_id`, `person2_roles_id`),
  INDEX `fk_relationships_residents1_idx` (`person1_id` ASC),
  INDEX `fk_relationships_residents2_idx` (`person2_id` ASC),
  INDEX `fk_relationships_houses1_idx` (`houses_id` ASC),
  INDEX `fk_relationships_relationship_type1_idx` (`relationship_type_id` ASC),
  INDEX `fk_relationships_roles1_idx` (`person1_roles_id` ASC),
  INDEX `fk_relationships_roles2_idx` (`person2_roles_id` ASC),
  CONSTRAINT `fk_relationships_residents1`
    FOREIGN KEY (`person1_id`)
    REFERENCES `penduduk`.`residents` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_relationships_residents2`
    FOREIGN KEY (`person2_id`)
    REFERENCES `penduduk`.`residents` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_relationships_houses1`
    FOREIGN KEY (`houses_id`)
    REFERENCES `penduduk`.`houses` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_relationships_relationship_type1`
    FOREIGN KEY (`relationship_type_id`)
    REFERENCES `penduduk`.`relationship_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_relationships_roles1`
    FOREIGN KEY (`person1_roles_id`)
    REFERENCES `penduduk`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_relationships_roles2`
    FOREIGN KEY (`person2_roles_id`)
    REFERENCES `penduduk`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
