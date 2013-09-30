ALTER TABLE  `#__dzproduct_items` CHANGE  `price`  `price` INT NOT NULL;

CREATE TABLE IF NOT EXISTS `#__dzproduct_orders` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`state` TINYINT(1) NOT NULL DEFAULT '0',
`checked_out` INT(11) NOT NULL,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`phone` VARCHAR(255)  NOT NULL ,
`address` TEXT NOT NULL ,
`email` VARCHAR(255)  NOT NULL ,
`comment` TEXT NOT NULL ,
`params` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__dzproduct_order_items` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`order_id` int(11),
`title` VARCHAR(255)  NOT NULL ,
`image` TEXT NOT NULL ,
`description` TEXT NOT NULL ,
`price` INT  NOT NULL ,
`quantity` INT NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;