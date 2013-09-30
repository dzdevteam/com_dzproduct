DROP TABLE IF EXISTS `#__dzproduct_items`;
DROP TABLE IF EXISTS `#__dzproduct_fields`;
DROP TABLE IF EXISTS `#__dzproduct_groups`;
DROP TABLE IF EXISTS `#__dzproduct_groupcat_relations`;
DROP TABLE IF EXISTS `#__dzproduct_field_data`;
DROP TABLE IF EXISTS `#__dzproduct_orders`;
DROP TABLE IF EXISTS `#__dzproduct_order_items`;

DELETE FROM `#__content_types` WHERE `type_alias` = 'com_dzproduct.item';