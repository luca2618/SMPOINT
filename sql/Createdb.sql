/* Droppper den tidligere database og skaber en ny */
DROP DATABASE IF EXISTS `smdatabase`;
CREATE DATABASE `smdatabase`;
USE `smdatabase`;
DROP TABLE IF EXISTS `s214636`;

/* Skaber tabeller til databasen. */

/* Tabel med brugere */
CREATE TABLE `medlemmer`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `studienr` VARCHAR(255) NOT NULL,
    `navn` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `telefonnr` VARCHAR(255) NOT NULL,
    `point` FLOAT NOT NULL,
    `card_id` VARCHAR(255)
);

CREATE TABLE `raadsmode`(
    `mode_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dato` DATE NOT NULL,
    `kode` VARCHAR(255) NOT NULL,
    `opretter` VARCHAR(255) NOT NULL
);

CREATE TABLE `admins` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(255) NOT NULL,
    `date` DATE NOT NULL);

CREATE TABLE `aktivitet_typer` (
    `type_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `Aktivitet` VARCHAR(255),
    `Point` FLOAT,
    `Forklaring` VARCHAR(255));

CREATE TABLE `aktiviteter` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `studienr` VARCHAR(255),
    `aktivitet` VARCHAR(255), -- (meeting, defined activity, free activity)
    `guest_name` VARCHAR(255), 
    `point` FLOAT,
    `kommentar` VARCHAR(255),
    `approved` BOOLEAN,
    `dato` DATE NOT NULL);

CREATE TABLE settings (
    SettingKey VARCHAR(255) PRIMARY KEY,
    SettingValue VARCHAR(255)
);

INSERT INTO `settings` (`SettingKey`, `SettingValue`) VALUES (
    'legacy_date',
    '2002-01-24'
);


INSERT INTO `admins`( `name`, `email`, `role`, `password`, `date`) VALUES (
    'Lucas Sylvester',
    'l@gmail.com',
    '2',
    '$2b$10$2gX7b6lV.nKxC2jWhfEbkeTmyBj1Lw4NRmVSfbBdh601zDSMBhmFC',
    '2021-12-30'
);


INSERT INTO `medlemmer`( `studienr`, `navn`, `email`, `telefonnr`, `point`) VALUES (
    's214636',
    'Lucas Sylvester',
    'l@gmail.com',
    '40143444',
    '30');



