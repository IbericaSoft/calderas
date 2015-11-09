/*
SQLyog Community v12.03 (64 bit)
MySQL - 5.6.27 : Database - alonsoel_seo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `_navigator` */

DROP TABLE IF EXISTS `_navigator`;

CREATE TABLE `_navigator` (
  `accion` varchar(25) NOT NULL,
  `metodo_pre_accion` varchar(255) DEFAULT NULL,
  `plantilla` varchar(255) DEFAULT NULL,
  `accion_salida_false` varchar(25) DEFAULT NULL,
  `accion_salida_true` varchar(25) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` char(3) DEFAULT 'ACT',
  UNIQUE KEY `accion` (`accion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `_navigator_detail` */

DROP TABLE IF EXISTS `_navigator_detail`;

CREATE TABLE `_navigator_detail` (
  `accion` varchar(25) DEFAULT NULL,
  `bloque` varchar(25) DEFAULT NULL,
  `orden` int(2) DEFAULT NULL,
  `exclusiones` varchar(255) NOT NULL,
  `paquete` varchar(255) DEFAULT NULL,
  `pagina` varchar(255) DEFAULT NULL,
  `estado` char(3) DEFAULT 'ACT'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `app_config` */

DROP TABLE IF EXISTS `app_config`;

CREATE TABLE `app_config` (
  `descripcion` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `clave` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `valor` longtext COLLATE latin1_spanish_ci NOT NULL,
  `editable` enum('S','N') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'N',
  `fmodificacion` datetime NOT NULL,
  `id_administrador` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `os_config` */

DROP TABLE IF EXISTS `os_config`;

CREATE TABLE `os_config` (
  `rol` int(1) NOT NULL DEFAULT '1',
  `clave` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `valor` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `descripcion` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `seo_articulos` */

DROP TABLE IF EXISTS `seo_articulos`;

CREATE TABLE `seo_articulos` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `profesion` varchar(255) COLLATE latin1_spanish_ci NOT NULL DEFAULT ' ',
  `titulo` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `articulo` text COLLATE latin1_spanish_ci NOT NULL,
  `foto` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `status` enum('ON','OFF','XXX') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'ON',
  `opiniones` int(3) NOT NULL,
  `media_votos` int(2) NOT NULL,
  `votos` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Table structure for table `seo_marcaymodelo` */

DROP TABLE IF EXISTS `seo_marcaymodelo`;

CREATE TABLE `seo_marcaymodelo` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `profesion` varchar(255) COLLATE latin1_spanish_ci NOT NULL DEFAULT ' ',
  `tipo` enum('MARCA','MODELO') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'MARCA',
  `marcamodelo` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `status` enum('ON','OFF','XXX') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'ON',
  `seo_slogan` text COLLATE latin1_spanish_ci NOT NULL,
  `fotoytexto` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `seo_comercial` text COLLATE latin1_spanish_ci NOT NULL,
  `seo_meta` text COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Table structure for table `seo_noticias` */

DROP TABLE IF EXISTS `seo_noticias`;

CREATE TABLE `seo_noticias` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `profesion` varchar(255) COLLATE latin1_spanish_ci NOT NULL DEFAULT ' ',
  `titulo` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `noticia` text COLLATE latin1_spanish_ci NOT NULL,
  `foto` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `status` enum('ON','OFF','XXX') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'ON',
  `votos` int(3) NOT NULL,
  `media_votos` int(2) NOT NULL,
  `opiniones` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Table structure for table `seo_opiniones` */

DROP TABLE IF EXISTS `seo_opiniones`;

CREATE TABLE `seo_opiniones` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `profesion` varchar(255) COLLATE latin1_spanish_ci NOT NULL DEFAULT ' ',
  `cliente` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `opinion` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  `status` enum('ON','OFF','XXX') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'ON',
  `valoracion` int(1) NOT NULL DEFAULT '5',
  `repetiria` enum('SI','NO') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'SI',
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Table structure for table `seo_servicios` */

DROP TABLE IF EXISTS `seo_servicios`;

CREATE TABLE `seo_servicios` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `profesion` varchar(255) COLLATE latin1_spanish_ci NOT NULL DEFAULT ' ',
  `status` enum('ON','OFF','XXX') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'ON',
  `seo_h1` varchar(255) COLLATE latin1_spanish_ci NOT NULL DEFAULT ' ',
  `seo_title` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `seo_url` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `seo_description` text COLLATE latin1_spanish_ci,
  `fotosytexto` text COLLATE latin1_spanish_ci,
  `seo_meta` text COLLATE latin1_spanish_ci,
  `combinable` enum('SI','NO') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'SI',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Table structure for table `seo_slogan` */

DROP TABLE IF EXISTS `seo_slogan`;

CREATE TABLE `seo_slogan` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `profesion` varchar(255) COLLATE latin1_spanish_ci NOT NULL DEFAULT ' ',
  `slogan` varchar(1000) COLLATE latin1_spanish_ci NOT NULL,
  `status` enum('ON','OFF','XXX') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'ON',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

/*Table structure for table `seo_ubicaciones` */

DROP TABLE IF EXISTS `seo_ubicaciones`;

CREATE TABLE `seo_ubicaciones` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `grupo` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `ubicacion` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `maps` text CHARACTER SET latin1 COLLATE latin1_spanish_ci,
  `status` enum('ON','OFF','XXX') CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT 'ON',
  `combinable` enum('SI','NO') NOT NULL DEFAULT 'SI',
  PRIMARY KEY (`id`,`tipo`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=308 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
