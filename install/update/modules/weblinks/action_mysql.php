<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];

$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_cat;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_config;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_report;';

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_rows (
	`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`catid` MEDIUMINT(8) NOT NULL,
	`title` VARCHAR(255) NOT NULL,
	`alias` VARCHAR(255) NOT NULL,
	`url` VARCHAR(255) NOT NULL,
	`urlimg` VARCHAR(255) NOT NULL DEFAULT '',
	`description` TEXT NOT NULL,
	`add_time` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`edit_time` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`hits_total` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
	`status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `alias` (`alias`(191)),
	INDEX `catid` (`catid`),
	INDEX `status` (`status`)
)";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (
	`catid` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`parentid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
	`title` VARCHAR(100) NOT NULL,
	`catimage` VARCHAR(255) NOT NULL DEFAULT '',
	`alias` VARCHAR(100) NOT NULL,
	`description` TEXT NOT NULL,
	`weight` SMALLINT(4) NOT NULL DEFAULT '0',
	`inhome` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`keywords` TEXT NOT NULL,
	`add_time` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`edit_time` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`catid`),
	UNIQUE INDEX `alias` (`alias`)
)";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_config (
	`name` VARCHAR(20) NOT NULL,
	`value` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`name`)
)";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_report (
	`id` INT(11) NULL DEFAULT NULL,
	`type` INT(1) NULL DEFAULT NULL,
	`report_time` INT(11) NOT NULL,
	`report_ip` VARCHAR(16) NOT NULL,
	`report_browse_key` VARCHAR(100) NOT NULL DEFAULT '',
	`report_browse_name` VARCHAR(100) NOT NULL DEFAULT '',
	`report_os_key` VARCHAR(100) NOT NULL DEFAULT '',
	`report_os_name` VARCHAR(100) NOT NULL DEFAULT '',
	`report_note` VARCHAR(255) NOT NULL DEFAULT '',
	INDEX `id` (`id`)
)";

$sql_create_module[] = 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_config (name, value) VALUES
('imgwidth', '100'),
('imgheight', '74'),
('per_page', '20'),
('homepage', '1'),
('sort', 'des'),
('sortoption', 'bytime'),
('showlinkimage', '1'),
('timeout', '2'),
('report_timeout', '60'),
('new_icon', '3')";
