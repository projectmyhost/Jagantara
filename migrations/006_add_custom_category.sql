USE `jagantara_v2`;

ALTER TABLE `reports` ADD COLUMN `custom_category` VARCHAR(100) DEFAULT NULL AFTER `category_id`;
