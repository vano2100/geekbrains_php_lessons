CREATE DATABASE `galery`

CREATE TABLE `images` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`filename` VARCHAR(255) NOT NULL DEFAULT '0',
	`size` INT(11) NOT NULL DEFAULT '0',
	`click_counter` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)