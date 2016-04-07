# ************************************************************
# Sequel Pro SQL dump
# Version 4500
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.6.14)
# Database: barkarby_mise
# Generation Time: 2016-04-06 13:18:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table custom_content
# ------------------------------------------------------------

DROP TABLE IF EXISTS `custom_content`;

CREATE TABLE `custom_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `short_code` varchar(50) DEFAULT NULL,
  `visibility` tinyint(1) unsigned DEFAULT '0',
  `content` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `custom_content` WRITE;
/*!40000 ALTER TABLE `custom_content` DISABLE KEYS */;

INSERT INTO `custom_content` (`id`, `short_code`, `visibility`, `content`)
VALUES
	(1,'hem',1,'<p>Det Grekiska K&ouml;ket</p>\n<p>&nbsp;</p>\n<p>Det grekiska k&ouml;ket k&auml;nnetecknas av Medelhavets typiska dofter av oregano, rosmarin, timjan, vitl&ouml;k och salvia. Genom historien har grekerna haft m&aring;nga och l&aring;nga kontakter med andra folkslag runt om i Medelhavet. Detta har p&aring;verkat det grekiska k&ouml;ket, men det grekiska k&ouml;ket har i sin tur ocks&aring; p&aring;verkat matlagningskulturen hos m&aring;nga andra folkslag.</p>\n<p>&nbsp;</p>\n<p>Karakt&auml;ristiskt f&ouml;r det grekiska k&ouml;ket &auml;r den omfattande anv&auml;ndningen av gr&ouml;nsaker och olivolja. Matlagning &auml;r en del av livsstilen hos grekerna, som l&auml;gger stor vikt p&aring; f&auml;rska r&aring;varor som gr&ouml;nsaker, f&auml;rsk fisk direkt fr&aring;n hamnen samt f&auml;rska &auml;rter och citroner fr&aring;n den egna tr&auml;dg&aring;rden.</p>\n<p>&nbsp;</p>\n<p>En stor del av v&aring;ra huvudr&auml;tter tillagas p&aring; en &auml;kta kolgrill. Detta minskar intaget av m&auml;ttade fetter och f&ouml;rl&auml;nger livet! Vi erbjuder &auml;ven ett brett urval av sm&aring;r&auml;tter, sallader mm.</p>\n<p>&nbsp;</p>\n<p>V&auml;lkomna till en smakupplevelse fr&aring;n sommarlandet Grekland!</p>\n<p>&nbsp;</p>'),
	(2,'avhamtning',1,'<p>Nu g&ouml;r vi det &auml;nnu smidigare for v&aring;ra g&auml;ster att best&auml;lla v&aring;r uts&ouml;kta mat! &nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>Undvik missf&ouml;rst&aring;nd - Best&auml;ll online</p>'),
	(3,'kontakt',1,'<table>\n	<tbody><tr>\n    	<td style=\"text-align:left;\">Måndag-Torsdag</td>\n        <td style=\"text-align:right;\">10:00 till 21:00</td>\n    </tr>\n    <tr>\n    	<td style=\"text-align:left;\">Fredag</td>\n        <td style=\"text-align:right;\">09:00 till 22:00</td>\n    </tr>\n    <tr>\n    	<td style=\"text-align:left;\">Lördag</td>\n        <td style=\"text-align:right;\">12.00 till 22.00</td>\n    </tr> \n     <tr>\n    	<td style=\"text-align:left;\">Söndag</td>\n        <td style=\"text-align:right;\">12.00 till 21.00</td>\n    </tr>\n    \n     <tr>\n    	<td style=\"text-align:left;\">Telefon</td>\n        <td style=\"text-align:right;\">08-511 600 30\n</td>\n    </tr>\n    <tr>\n    	<td style=\"text-align:left;\">Hitta hit</td>\n        <td style=\"text-align:right; line-height: 1.1;\">Barkarbyvägen 45<br>\n177 38 Järfälla, Sweden\n</td>\n    </tr>\n</tbody></table>');

/*!40000 ALTER TABLE `custom_content` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
