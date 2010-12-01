-- MySQL dump 10.13  Distrib 5.1.49, for debian-linux-gnu (x86_64)
--
-- Host: server    Database: pr_uz_ua
-- ------------------------------------------------------
-- Server version	5.1.51-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blog_entry`
--

DROP TABLE IF EXISTS `blog_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_entry` (
  `identry` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `richtext` text,
  `idgallery` int(11) DEFAULT '0',
  PRIMARY KEY (`identry`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_entry`
--

LOCK TABLES `blog_entry` WRITE;
/*!40000 ALTER TABLE `blog_entry` DISABLE KEYS */;
INSERT INTO `blog_entry` VALUES (1,'Новий запис1',NULL,NULL,'Опис Новий запис1','<p>\r\n	<img alt=\"\" src=\"/public/img/4ce42fe4a6a4d.jpg\" style=\"width: 100px; height: 100px; float: left;\" />Контент Новий запис1</p>',0),(2,'Новий запис2',NULL,NULL,'Опис Новий запис2','<p>\r\n	КОнтент Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2Новий запис2</p>',0),(4,'Новий запис4',NULL,NULL,'Опис Новий запис3','<p>\r\n	Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4Новий запис4</p>',0);
/*!40000 ALTER TABLE `blog_entry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery` (
  `idgallery` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL COMMENT 'назва галереї для адмінки',
  PRIMARY KEY (`idgallery`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES (2,'22223'),(4,'dewdd');
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `idimage` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(45) NOT NULL DEFAULT '' COMMENT 'шлях до картинки',
  `idgallery` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idimage`),
  KEY `fk-gallery` (`idgallery`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `idmenu` int(11) NOT NULL AUTO_INCREMENT,
  `url-ua` varchar(255) DEFAULT NULL,
  `url-ru` varchar(255) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL COMMENT 'назва для адмінки',
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT 'указати батьківський id, ящо нема то ставимо 0',
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `idpage` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmenu`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (5,NULL,NULL,'llll',6,0,16),(6,NULL,NULL,'adada',0,0,21),(7,NULL,NULL,'dadwa',0,0,17),(8,NULL,NULL,'adad',0,0,16);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `idpage` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `richtext` text,
  `idgallery` int(11) DEFAULT '0',
  PRIMARY KEY (`idpage`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES (16,'Кешаня',NULL,NULL,'Попугай','<h3 style=\"color: red; text-align: center;\">\r\n	<span style=\"font-size: 26px;\"><span style=\"background-color: rgb(75, 0, 130);\"><img alt=\"\" src=\"/public/img/4ce3d82e141b8.jpg\" style=\"width: 100px; height: 80px; float: left;\" />Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший Кеша хороший <strong><em><u>Кеша </u></em></strong>хороший Кеша хороший </span></span></h3>\r\n<p>\r\n	<span style=\"font-size: 26px;\"><span style=\"background-color: rgb(75, 0, 130);\"><img alt=\"\" src=\"/public/img/4ce3d9463a432.jpg\" style=\"width: 100px; height: 75px; float: left;\" /></span></span></p>',0),(17,'Гарна Назва 1',NULL,NULL,'Гарний Опис','<p>\r\n	Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент Текст контент<img alt=\"\" src=\"/public/img/4ce42820cb5d7.jpg\" style=\"width: 100px; height: 75px;\" /></p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',0),(21,'Samsung Monte SGH-5614616',NULL,NULL,'телефон з сенсорним екраном','<p>\r\n	Багато інформації про телефон з сенсорним екраном Samsung Monte gjhkijjjjjjjjj</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',0),(23,'Нова сторінка',NULL,NULL,'нова','<p style=\"text-align: center;\">\r\n	&nbsp;</p>\r\n<p style=\"text-align: center;\">\r\n	<img alt=\"\" src=\"/public/img/4ce3dc3ef37a2.jpg\" style=\"width: 150px; height: 120px; float: left;\" /></p>\r\n<p>\r\n	<em><u><strong>фвфвфвфвфц</strong></u></em></p>',0),(30,'Нова сторінка2',NULL,NULL,'Опис нова сторінка2','<p>\r\n	Текст нова сторінка2</p>',0);
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `idusers` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '',
  `password` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`idusers`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-12-01 13:15:14
