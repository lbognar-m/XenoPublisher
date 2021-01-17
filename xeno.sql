-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `xeno`;
CREATE DATABASE `xeno` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `xeno`;

DROP TABLE IF EXISTS `eventlog`;
CREATE TABLE `eventlog` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique event log ID.',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'The user.uid of the user who triggered the event.',
  `log_type` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Type of log message, for example "user" or "page not found."',
  `log_severity` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'The severity level of the event; ranges from 0 (Emergency) to 7 (Debug)',
  `log_referer` text COLLATE utf8_bin COMMENT 'URL of referring page.',
  `log_message` longtext COLLATE utf8_bin NOT NULL COMMENT 'Text of log message to be passed into the t() function.',
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `eventlog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `eventlog`;

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'File ID.',
  `user_id` int(11) NOT NULL COMMENT 'The id of the user who is associated with the file.',
  `file_name` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Name of the file with no path components. This may differ from the basename of the URI if the file is renamed to avoid overwriting an existing file.',
  `file_uri` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The URI to access the file (either local or remote).',
  `file_mime` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The file’s MIME type.',
  `file_size` int(11) NOT NULL COMMENT 'The size of the file in bytes.',
  `file_status` tinyint(4) NOT NULL COMMENT 'A field indicating the status of the file. Two status are defined in core: temporary (0) and permanent (1). Temporary files older than DRUPAL_MAXIMUM_TEMP_FILE_AGE will be removed during a cron run.',
  `file_created` int(11) NOT NULL COMMENT 'UNIX timestamp for when the file was added.',
  `file_type` varchar(11) COLLATE utf8_bin NOT NULL COMMENT 'The type of this file.',
  PRIMARY KEY (`file_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `files`;

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `lang_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Language ID.',
  `lang_code` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'Machine readable language code.',
  `lang_name` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'Human readable language name.',
  PRIMARY KEY (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `languages`;
INSERT INTO `languages` (`lang_id`, `lang_code`, `lang_name`) VALUES
(1,	'en',	'english');

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `user_name` varchar(32) COLLATE utf8_bin NOT NULL,
  `invalid_time` int(11) NOT NULL,
  KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `login_attempts`;
INSERT INTO `login_attempts` (`user_name`, `invalid_time`) VALUES
('admin',	1445871126);

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique menu id.',
  `menu_name` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'Unique machine-readable menu name.',
  `menu_title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Human-readable title of the men. Appears as the title of the menu widgets.',
  `menu_desc` text COLLATE utf8_bin NOT NULL COMMENT 'Description of the menu.',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `menu`;
INSERT INTO `menu` (`menu_id`, `menu_name`, `menu_title`, `menu_desc`) VALUES
(1,	'main-menu',	'Main Navigation',	'The main menu for the navigating the content.'),
(2,	'dashboard-menu',	'The dasboard menu.',	'Every administrative menu should live here.');

DROP TABLE IF EXISTS `menu_links`;
CREATE TABLE `menu_links` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique link id.',
  `link_parent` int(11) NOT NULL DEFAULT '0' COMMENT 'The link id of the parent link',
  `menu_id` int(11) NOT NULL COMMENT 'The id of the menu that contains this item.',
  `link_path` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The path of the page it links to. For placeholder this will not be rendered.',
  `link_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = hidden / 1 = visible',
  `link_type` int(11) NOT NULL DEFAULT '0' COMMENT '0 = page link / 1 = external link / 2 = placeholder',
  PRIMARY KEY (`link_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `menu_links_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `menu_links`;
INSERT INTO `menu_links` (`link_id`, `link_parent`, `menu_id`, `link_path`, `link_status`, `link_type`) VALUES
(1,	0,	1,	'node/1',	1,	0),
(2,	0,	2,	'dashboard',	1,	0);

DROP TABLE IF EXISTS `path`;
CREATE TABLE `path` (
  `path_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Custom Path ID.',
  `path_url` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Path this entry describes',
  `path_title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The title of the path (optional), can be set/overridden from code also.',
  `path_type` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The type of the callback. Ex.: function',
  `path_callback` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The function  to be executed for this pages'' content.',
  `access_level` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The permission needed to access this page.',
  PRIMARY KEY (`path_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `path`;
INSERT INTO `path` (`path_id`, `path_url`, `path_title`, `path_type`, `path_callback`, `access_level`) VALUES
(1,	'dashboard',	'Dashboard',	'function',	'display_dashboard',	'admin_access'),
(2,	'dashboard/(\\w+)',	'Admin the menus',	'function',	'display_admin_menus',	'admin_access'),
(3,	'home',	'Home Page',	'function',	'display_home',	'base_access'),
(4,	'pod/(\\w+)',	'Pod',	'pod',	'1',	'base_access');

DROP TABLE IF EXISTS `path_alias`;
CREATE TABLE `path_alias` (
  `alias_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'A unique path alias identifier.',
  `pod_id` int(10) NOT NULL COMMENT 'The Pod this alias is for.',
  `alias_path` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The alias for this path; e.g. title-of-the-story.',
  `lang_id` int(11) NOT NULL COMMENT 'Language id for the alias',
  PRIMARY KEY (`alias_id`),
  KEY `pod_id` (`pod_id`),
  KEY `lang_id` (`lang_id`),
  CONSTRAINT `path_alias_ibfk_1` FOREIGN KEY (`pod_id`) REFERENCES `pod` (`pod_id`),
  CONSTRAINT `path_alias_ibfk_2` FOREIGN KEY (`lang_id`) REFERENCES `languages` (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `path_alias`;

DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `perm_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Permission id.',
  `perm_name` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'Permission name.',
  PRIMARY KEY (`perm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `permission`;
INSERT INTO `permission` (`perm_id`, `perm_name`) VALUES
(1,	'view_pod'),
(2,	'edit_pod'),
(3,	'edit_own_pod'),
(4,	'add_pod'),
(5,	'admin_access');

DROP TABLE IF EXISTS `pod`;
CREATE TABLE `pod` (
  `pod_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for a pod.',
  `pod_type` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'The machine-readable name of the pod type.',
  `user_id` int(11) NOT NULL COMMENT 'The author''s user id.',
  `pod_status` int(11) NOT NULL COMMENT 'Boolean indicating whether the node is published (visible to non-administrators).',
  `pod_created` int(11) NOT NULL COMMENT 'The Unix timestamp when the pod was created.',
  `pod_modified` int(11) NOT NULL COMMENT 'The Unix timestamp when the pod was most recently saved.',
  PRIMARY KEY (`pod_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pod_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `pod`;
INSERT INTO `pod` (`pod_id`, `pod_type`, `user_id`, `pod_status`, `pod_created`, `pod_modified`) VALUES
(1,	'page',	1,	1,	1446217608,	1446217608);

DROP TABLE IF EXISTS `podfields`;
CREATE TABLE `podfields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Field data id.',
  `pod_id` int(10) NOT NULL COMMENT 'Parent Pod id.',
  `field_name` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'Field name.',
  `lang_name` int(11) NOT NULL COMMENT 'Pod content language.',
  `field_content` longtext COLLATE utf8_bin NOT NULL COMMENT 'The field data to be displayed in the front end.',
  `lang_id` int(11) NOT NULL DEFAULT '1' COMMENT 'Pod field language.',
  PRIMARY KEY (`field_id`),
  KEY `pod_id` (`pod_id`),
  KEY `lang_name` (`lang_name`),
  KEY `lang_id` (`lang_id`),
  CONSTRAINT `podfields_ibfk_1` FOREIGN KEY (`pod_id`) REFERENCES `pod` (`pod_id`),
  CONSTRAINT `podfields_ibfk_2` FOREIGN KEY (`lang_id`) REFERENCES `languages` (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `podfields`;
INSERT INTO `podfields` (`field_id`, `pod_id`, `field_name`, `lang_name`, `field_content`, `lang_id`) VALUES
(1,	1,	'subtitle',	1,	'method: The HTTP form method to use for finding the input for this form. May be \'post\' or \'get\'. Defaults to \'post\'. Note that \'get\' method forms do not use form ids so are always considered to be submitted, which can have unexpected effects. The \'get\' method should only be used on forms that do not change data, as that is exclusively the domain of \'post.\'',	1),
(2,	1,	'boat',	1,	'X22-L3',	1);

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(32) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `role`;
INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1,	'administrator'),
(2,	'authenticated_user'),
(3,	'moderator');

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL COMMENT 'Role id',
  `perm_id` int(11) NOT NULL COMMENT 'Permission id',
  KEY `role_id` (`role_id`),
  KEY `perm_id` (`perm_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`),
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`),
  CONSTRAINT `role_permissions_ibfk_3` FOREIGN KEY (`perm_id`) REFERENCES `permission` (`perm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `role_permissions`;
INSERT INTO `role_permissions` (`role_id`, `perm_id`) VALUES
(2,	1),
(2,	3),
(3,	2),
(3,	3),
(3,	4);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `setting_name` varchar(128) COLLATE utf8_bin NOT NULL COMMENT 'The name of the setting.',
  `setting_value` varchar(256) COLLATE utf8_bin NOT NULL COMMENT 'The value of the setting.',
  PRIMARY KEY (`setting_name`),
  UNIQUE KEY `unique` (`setting_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `settings`;
INSERT INTO `settings` (`setting_name`, `setting_value`) VALUES
('combine_css',	'1'),
('combine_js',	'1'),
('dev_mode',	'1'),
('language',	'1'),
('minify_css',	'1'),
('minify_js',	'1'),
('theme_admin',	'queen'),
('theme_front',	'prime');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique user ID.',
  `user_name` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'Unique user name.',
  `user_password` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'User’s password encrypted with password_hash()',
  `user_email` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'User’s e-mail address.',
  `user_created` int(11) NOT NULL COMMENT 'Timestamp for when user was created.',
  `user_login` int(11) NOT NULL COMMENT 'Timestamp for user’s last login.',
  `user_status` int(11) NOT NULL COMMENT 'Whether the user is active(1) or blocked(0).',
  `lang_id` int(11) NOT NULL DEFAULT '1' COMMENT 'User’s default language.',
  PRIMARY KEY (`user_id`),
  KEY `lang_id` (`lang_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`lang_id`) REFERENCES `languages` (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `user`;
INSERT INTO `user` (`user_id`, `user_name`, `user_password`, `user_email`, `user_created`, `user_login`, `user_status`, `lang_id`) VALUES
(1,	'admin',	'$2y$10$2WPC/.88MhMOnpY1Z1b5c.eh00VmSTfxDs.Lg6cAVWvLOWmPOMitO',	'admin@admin.com',	1445870898,	0,	1,	1);

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL COMMENT 'Primary Key: user.user_id for user.',
  `role_id` int(11) NOT NULL COMMENT 'Primary Key: role.role_id for role.',
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `user_roles`;
INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1,	1),
(1,	2),
(1,	3);

DROP TABLE IF EXISTS `widget`;
CREATE TABLE `widget` (
  `widget_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The id of the widget',
  `widget_name` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Machine readable name of the widget.',
  `widget_title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Human readable title of the widget.',
  `widget_type` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'Type of the widget. Usually it''s text or function.',
  `access_level` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The permission needed to view this widget.',
  `widget_body` longtext COLLATE utf8_bin NOT NULL COMMENT 'JSON content of the widget. This is not meant to be searchable so it''s ok.',
  PRIMARY KEY (`widget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `widget`;

-- 2015-11-01 17:40:56
