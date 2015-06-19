-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `banned`;
CREATE TABLE `banned` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `banned` (`id`, `ip`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(2,	'100.10.25.40',	'2015-05-19 16:37:54',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	1,	0,	0);

DROP TABLE IF EXISTS `calendar`;
CREATE TABLE `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_dt` datetime DEFAULT NULL,
  `end_dt` datetime DEFAULT NULL,
  `content_type` varchar(100) NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_title` varchar(255) DEFAULT NULL,
  `teaser` mediumtext,
  `content` longtext,
  `page_title` varchar(100) DEFAULT NULL,
  `page_description` varchar(170) DEFAULT NULL,
  `page_keywords` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `calendar` (`id`, `start_dt`, `end_dt`, `content_type`, `content_id`, `title`, `short_title`, `teaser`, `content`, `page_title`, `page_description`, `page_keywords`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `published`) VALUES
(3,	'2015-06-09 00:00:00',	'2015-06-09 00:00:00',	'event',	15,	'teada vdaefa w',	'dafeawfaw adfeaw',	'',	'',	'teada vdaefa w',	'',	'',	'2015-06-09 12:02:10',	'2015-06-10 11:11:56',	'2015-06-10 11:30:29',	1,	1,	NULL,	1),
(4,	'2015-07-04 00:00:00',	'2015-07-01 00:00:00',	'event',	15,	'a new date',	'a new date',	'',	'<p>dfaeg garg awegagdfar e</p>',	'a new date',	'',	'',	'2015-06-15 12:42:00',	'2015-06-15 12:42:08',	NULL,	1,	1,	NULL,	1);

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`,`ip_address`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `contents`;
CREATE TABLE `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_type` varchar(100) NOT NULL,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `featured_image` varchar(255) DEFAULT NULL,
  `order` int(4) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `published_at` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_title` varchar(255) DEFAULT NULL,
  `teaser` mediumtext NOT NULL,
  `content` longtext,
  `page_title` varchar(100) DEFAULT NULL,
  `page_description` varchar(170) DEFAULT NULL,
  `page_keywords` varchar(255) DEFAULT NULL,
  `rake` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `contents` (`id`, `content_type`, `parent_id`, `featured_image`, `order`, `published`, `published_at`, `title`, `short_title`, `teaser`, `content`, `page_title`, `page_description`, `page_keywords`, `rake`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(2,	'page',	0,	'',	0,	1,	'2015-06-03 10:38:10',	'Test the page again',	'test it',	'',	'<p>In one single page</p>',	'Test the page',	'',	'',	0,	'2015-06-03 10:38:34',	'2015-06-09 11:52:19',	NULL,	1,	1,	0),
(15,	'event',	13,	NULL,	0,	1,	'2015-06-09 11:37:00',	'Test the event',	'Test the event',	'',	'<p>dfafe aefaev aeaw</p>',	'Test the event',	'',	'',	0,	'2015-06-09 11:37:14',	'2015-06-09 12:07:55',	NULL,	1,	1,	0),
(13,	'event_type',	0,	NULL,	0,	0,	'2015-06-08 12:03:57',	'Conference',	'Conference',	'',	'',	'Conference',	'',	'',	0,	'2015-06-08 12:28:39',	NULL,	NULL,	1,	0,	0);

DROP TABLE IF EXISTS `dictionary`;
CREATE TABLE `dictionary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `word` varchar(255) NOT NULL,
  `noise` tinyint(1) NOT NULL DEFAULT '0',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1,	'admin',	'Administrators'),
(2,	'members',	'Members');

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_type` varchar(100) NOT NULL,
  `content_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `width` int(5) NOT NULL DEFAULT '0',
  `height` int(5) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `keyphrases`;
CREATE TABLE `keyphrases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_type` varchar(255) NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `phrase_id` int(10) unsigned NOT NULL,
  `score` decimal(10,2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `keywords`;
CREATE TABLE `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_type` varchar(255) NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `word_id` int(10) unsigned NOT NULL,
  `appearances` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menus` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(2,	'top-menu',	'2015-05-04 12:25:23',	NULL,	NULL,	1,	0,	0);

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) unsigned NOT NULL,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `order` int(4) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `absolute_path` tinyint(1) DEFAULT '0',
  `styling` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `order`, `title`, `url`, `absolute_path`, `styling`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(2,	2,	0,	0,	'Primul itemic',	'#',	1,	'',	'2015-06-03 09:46:41',	'2015-06-03 10:00:37',	NULL,	1,	1,	0);

DROP TABLE IF EXISTS `phrases`;
CREATE TABLE `phrases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phrase` varchar(255) NOT NULL,
  `last_check` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rat`;
CREATE TABLE `rat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `code` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `rat` (`id`, `user_id`, `date_time`, `code`, `message`) VALUES
(5,	1,	'2015-06-19 17:03:50',	0,	'The user logged in');

DROP TABLE IF EXISTS `slugs`;
CREATE TABLE `slugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_type` varchar(150) NOT NULL,
  `content_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `redirect` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `slugs` (`id`, `content_type`, `content_id`, `url`, `redirect`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(15,	'calendar',	4,	'a-new-date',	0,	'2015-06-15 12:42:00',	NULL,	NULL,	1,	0,	0),
(12,	'calendar',	15,	'test-date',	15,	'2015-06-09 11:38:49',	'2015-06-15 12:42:00',	NULL,	1,	1,	0),
(11,	'event',	15,	'test-the-event',	0,	'2015-06-09 11:37:14',	NULL,	NULL,	1,	0,	0),
(10,	'calendar',	14,	'test-the-date',	15,	'2015-06-09 09:21:00',	'2015-06-15 12:42:00',	NULL,	1,	1,	0);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1,	'127.0.0.1',	'administrator',	'$2y$08$G0h47xFzvBDD3DjwWD13XeCfwGuZgqtSodh5ARhDJLLWPRv0jSgfG',	'',	'admin@admin.com',	'',	NULL,	NULL,	NULL,	1268889823,	1434722629,	1,	'Admin',	'istrator',	'ADMIN',	'0');

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `users_groups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(3,	1,	1);

DROP TABLE IF EXISTS `website`;
CREATE TABLE `website` (
  `title` varchar(255) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `admin_email` varchar(200) NOT NULL,
  `contact_email` varchar(200) NOT NULL,
  `modified_by` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `website` (`title`, `page_title`, `status`, `admin_email`, `contact_email`, `modified_by`) VALUES
('CI site',	'CI site',	1,	'avenir.ro@gmail.com',	'avenir.ro@gmail.com',	'1');

-- 2015-06-19 15:33:31
