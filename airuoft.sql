SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `airuoft` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `airuoft` ;

-- -----------------------------------------------------
-- Table `airuoft`.`campus`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `airuoft`.`campus` ;

CREATE  TABLE IF NOT EXISTS `airuoft`.`campus` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(16) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `airuoft`.`timetable`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `airuoft`.`timetable` ;

CREATE  TABLE IF NOT EXISTS `airuoft`.`timetable` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `leavingfrom` INT NOT NULL COMMENT '	' ,
  `goingto` INT NOT NULL ,
  `time` TIME NULL ,
  INDEX `fk_timetable_campus` (`leavingfrom` ASC) ,
  INDEX `fk_timetable_campus1` (`goingto` ASC) ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_timetable_campus`
    FOREIGN KEY (`leavingfrom` )
    REFERENCES `airuoft`.`campus` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_timetable_campus1`
    FOREIGN KEY (`goingto` )
    REFERENCES `airuoft`.`campus` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `airuoft`.`flight`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `airuoft`.`flight` ;

CREATE  TABLE IF NOT EXISTS `airuoft`.`flight` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `timetable_id` INT NOT NULL ,
  `date` DATE NOT NULL ,
  `available` INT NOT NULL DEFAULT 3 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_flight_timetable1` (`timetable_id` ASC) ,
  CONSTRAINT `fk_flight_timetable1`
    FOREIGN KEY (`timetable_id` )
    REFERENCES `airuoft`.`timetable` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `airuoft`.`ticket`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `airuoft`.`ticket` ;

CREATE  TABLE IF NOT EXISTS `airuoft`.`ticket` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `first` VARCHAR(16) NOT NULL ,
  `last` VARCHAR(16) NOT NULL ,
  `creditcardnumber` VARCHAR(16) NOT NULL ,
  `creditcardexpiration` VARCHAR(4) NOT NULL ,
  `flight_id` INT NOT NULL ,
  `seat` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ticket_flight1` (`flight_id` ASC) ,
  CONSTRAINT `fk_ticket_flight1`
    FOREIGN KEY (`flight_id` )
    REFERENCES `airuoft`.`flight` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `airuoft`.`campus`
-- -----------------------------------------------------
START TRANSACTION;
USE `airuoft`;
INSERT INTO `airuoft`.`campus` (`id`, `name`) VALUES (NULL, 'St. George');
INSERT INTO `airuoft`.`campus` (`id`, `name`) VALUES (NULL, 'Mississauga');

COMMIT;

-- -----------------------------------------------------
-- Data for table `airuoft`.`timetable`
-- -----------------------------------------------------
START TRANSACTION;
USE `airuoft`;
INSERT INTO `airuoft`.`timetable` (`id`, `leavingfrom`, `goingto`, `time`) VALUES (NULL, 1, 2, '8:00');
INSERT INTO `airuoft`.`timetable` (`id`, `leavingfrom`, `goingto`, `time`) VALUES (NULL, 1, 2, '10:00');
INSERT INTO `airuoft`.`timetable` (`id`, `leavingfrom`, `goingto`, `time`) VALUES (NULL, 1, 2, '14:00');
INSERT INTO `airuoft`.`timetable` (`id`, `leavingfrom`, `goingto`, `time`) VALUES (NULL, 1, 2, '17:00');
INSERT INTO `airuoft`.`timetable` (`id`, `leavingfrom`, `goingto`, `time`) VALUES (NULL, 2, 1, '8:00');
INSERT INTO `airuoft`.`timetable` (`id`, `leavingfrom`, `goingto`, `time`) VALUES (NULL, 2, 1, '10:00');
INSERT INTO `airuoft`.`timetable` (`id`, `leavingfrom`, `goingto`, `time`) VALUES (NULL, 2, 1, '14:00');
INSERT INTO `airuoft`.`timetable` (`id`, `leavingfrom`, `goingto`, `time`) VALUES (NULL, 2, 1, '17:00');

COMMIT;
