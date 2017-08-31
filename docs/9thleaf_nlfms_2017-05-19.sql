# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 9leaf.wicp.net (MySQL 5.5.54-MariaDB)
# Database: 9thleaf_nlfms
# Generation Time: 2017-05-19 15:41:00 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS `9thleaf_nlfms`;

CREATE DATABASE `9thleaf_nlfms`;

USE `9thleaf_nlfms`;

GRANT ALL PRIVILEGES ON `9thleaf_nlfms`.* TO 'nlfmsuser'@'127.0.0.1' IDENTIFIED BY 'nlfms123QWE';

FLUSH PRIVILEGES;

# Dump of table 9thleaf_front_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `9thleaf_front_sessions`;

CREATE TABLE `9thleaf_front_sessions` (
  `id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_activity_idx` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前台Session记录表';


# Dump of table 9thleaf_app_info
# ------------------------------------------------------------

DROP TABLE IF EXISTS `9thleaf_app_info`;

CREATE TABLE `9thleaf_app_info` (
  `id` int(11) DEFAULT NULL,
  `app_name` varchar(50) DEFAULT NULL,
  `description` text,
  `site_url` varchar(150) DEFAULT NULL,
  `admin_url` varchar(200) DEFAULT NULL,
  `site_logo` varchar(200) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `company_keyman` varchar(50) DEFAULT NULL,
  `keyman_phone` varchar(20) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `seo_keyword` varchar(255) DEFAULT NULL,
  `seo_description` text,
  `author` varchar(40) DEFAULT NULL,
  `icp_num` varchar(40) DEFAULT NULL,
  `copy_right` varchar(255) DEFAULT '© 2009-2015 Powered by NINTH·LEAF™. All Rights Reserved.',
  `domain` varchar(150) DEFAULT NULL,
  `theme` varchar(50) DEFAULT 'default',
  `wechat_appid` varchar(50) DEFAULT NULL,
  `wechat_appsecret` varchar(50) DEFAULT NULL,
  `wechat_access_token` varchar(100) DEFAULT NULL,
  `wechat_token_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `wechat_jsapi_ticket` varchar(100) DEFAULT NULL,
  `wechat_jsapi_timestamp` timestamp NULL DEFAULT NULL,
  `wechat_encrypt` tinyint(1) DEFAULT '0',
  `wechat_last_time` timestamp NULL DEFAULT NULL,
  `app_flag` tinyint(4) DEFAULT '0',
  `cs_phone` varchar(20) DEFAULT NULL,
  `welcome_words` varchar(255) DEFAULT NULL,
  `app_name_en` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='应用表';


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
