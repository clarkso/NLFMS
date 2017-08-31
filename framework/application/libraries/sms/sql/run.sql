# ************************************************************
# Sequel Pro SQL dump
# Version 4529
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 125.88.168.50 (MySQL 5.5.39-cll-lve)
# Database: 9thleaf_yhw
# Generation Time: 2016-03-11 08:35:43 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table 9thleaf_sms_supplier
# ------------------------------------------------------------

DROP TABLE IF EXISTS `9thleaf_sms_supplier`;

CREATE TABLE `9thleaf_sms_supplier` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(50) NOT NULL DEFAULT '' COMMENT '供应商名称',
  `supplier_class` varchar(50) NOT NULL DEFAULT '' COMMENT '供应商的接口类',
  `supplier_account_name` varchar(50) DEFAULT NULL COMMENT '供应商提供的用户名',
  `supplier_account_passwd` varchar(50) DEFAULT NULL COMMENT '供应商提供的密码',
  `supplier_api_url` varchar(255) DEFAULT NULL COMMENT '供应商提供的接口地址',
  `in_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否正在使用，1为正在使用，0未不在使用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信供应商表';

LOCK TABLES `9thleaf_sms_supplier` WRITE;
/*!40000 ALTER TABLE `9thleaf_sms_supplier` DISABLE KEYS */;

INSERT INTO `9thleaf_sms_supplier` (`id`, `supplier_name`, `supplier_class`, `supplier_account_name`, `supplier_account_passwd`, `supplier_api_url`, `in_use`)
VALUES
	(1,'sms.cn','SMS_CN_short_message','emoxuan','emoxuan88118866','http://api.sms.cn/',0),
	(2,'saiman.tech','Sai_Man_short_message','wanzhang_51yihuo','N4r1zaRWE5ML0nwY','http://222.73.117.158/',1);

/*!40000 ALTER TABLE `9thleaf_sms_supplier` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
