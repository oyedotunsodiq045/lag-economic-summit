CREATE DATABASE vircon;

CREATE TABLE `vircon`.`user` 
(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(100) NOT NULL,
    `business` VARCHAR(100) NOT NULL,
    `address` VARCHAR(100) NOT NULL,
    `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`) 
);