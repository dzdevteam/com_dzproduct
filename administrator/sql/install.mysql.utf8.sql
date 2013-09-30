CREATE TABLE IF NOT EXISTS `#__dzproduct_items` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`title` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`catid` INT(11)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created` DATETIME NOT NULL,
`created_by` INT(11)  NOT NULL ,
`images` TEXT NOT NULL ,
`other_images` TEXT NOT NULL ,
`short_desc` TEXT NOT NULL ,
`long_desc` TEXT NOT NULL ,
`video` VARCHAR(255)  NOT NULL ,
`openurl` VARCHAR(255)  NOT NULL ,
`price` INT  NOT NULL ,
`saleoff` VARCHAR(255)  NOT NULL ,
`new_arrival` TINYINT NOT NULL,
`featured` TINYINT NOT NULL,
`availability` TINYINT NOT NULL,
`metakey` TEXT NOT NULL ,
`metadesc` TEXT NOT NULL ,
`metadata` TEXT NOT NULL ,
`params` TEXT NOT NULL ,
`language` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__dzproduct_fields` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`name` VARCHAR(255)  NOT NULL ,
`dname` MEDIUMTEXT NOT NULL,
`type` VARCHAR(255)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`params` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__dzproduct_groups` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`name` VARCHAR(255)  NOT NULL ,
`fields` TEXT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`icon` VARCHAR(255)  NOT NULL ,
`params` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__dzproduct_groupcat_relations` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`catid` TEXT NOT NULL ,
`groupid` TEXT NOT NULL ,
`params` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__dzproduct_field_data` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`itemid` INT NOT NULL ,
`fieldid` INT NOT NULL ,
`value` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__dzproduct_orders` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`state` TINYINT(1) NOT NULL DEFAULT '0',
`checked_out` INT(11) NOT NULL,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created` DATETIME NOT NULL ,
`modified` DATETIME NOT NULL ,
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

INSERT INTO `#__content_types` (`type_id`, `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`) VALUES (NULL, 'Product Item', 'com_dzproduct.item', '{"special":{"dbtable":"#__dzproduct_items","key":"id","type":"Item","prefix":"DZProductTable","config":"array()"}}', '', '{"common":[{"core_content_item_id":"id","core_title":"title","core_state":"state","core_alias":"alias","core_body":"short_desc", "core_params":"params", "core_metadata":"metadata", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "asset_id":"asset_id"}]}', 'DZProductHelperRoute::getItemRoute');