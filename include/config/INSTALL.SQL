-- MySQL dump 10.13  Distrib 5.5.50, for debian-linux-gnu (x86_64)
--
-- Host: 0.0.0.0    Database: ses50
-- ------------------------------------------------------
-- Server version	5.5.50-0ubuntu0.14.04.1

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
-- Table structure for table `os_obj`
--

DROP TABLE IF EXISTS `os_obj`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `os_obj` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `obj` varchar(32) NOT NULL DEFAULT '',
  `fkid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `obj` (`obj`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `os_obj`
--

LOCK TABLES `os_obj` WRITE;
/*!40000 ALTER TABLE `os_obj` DISABLE KEYS */;
INSERT INTO `os_obj` VALUES (1,'p_reponse',12),(2,'p_reponse',13),(3,'p_reponse',14),(4,'p_reponse',15),(5,'p_reponse',16),(6,'s_rubrique',1),(7,'s_rubrique',4),(8,'s_rubrique',5),(9,'s_rubrique',6),(10,'s_rubrique',7),(11,'s_rubrique',8),(12,'s_rubrique',10),(13,'s_rubrique',11),(14,'s_rubrique',12),(15,'s_rubrique',13),(16,'s_rubrique',17),(19,'p_reponse',18),(20,'p_reponse',19),(21,'p_reponse',20),(22,'p_reponse',21),(23,'p_reponse',22),(24,'p_reponse',23);
/*!40000 ALTER TABLE `os_obj` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `os_recherches`
--

DROP TABLE IF EXISTS `os_recherches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `os_recherches` (
  `recherche_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recherche_q` varchar(255) NOT NULL,
  `recherche_nbres` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`recherche_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `os_recherches`
--

LOCK TABLES `os_recherches` WRITE;
/*!40000 ALTER TABLE `os_recherches` DISABLE KEYS */;
/*!40000 ALTER TABLE `os_recherches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `os_rel`
--

DROP TABLE IF EXISTS `os_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `os_rel` (
  `fkobj` mediumint(5) NOT NULL DEFAULT '0',
  `fkword` mediumint(5) NOT NULL DEFAULT '0',
  `nb` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fkobj`,`fkword`),
  KEY `nb` (`nb`),
  KEY `fkword` (`fkword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `os_rel`
--

LOCK TABLES `os_rel` WRITE;
/*!40000 ALTER TABLE `os_rel` DISABLE KEYS */;
INSERT INTO `os_rel` VALUES (1,3,1),(1,5,1),(1,6,1),(1,7,1),(1,8,1),(1,9,1),(1,10,1),(1,11,1),(1,13,1),(2,3,1),(2,5,1),(2,6,1),(2,7,1),(2,8,1),(2,9,1),(2,10,1),(2,11,1),(2,13,1),(3,3,1),(3,5,1),(3,6,1),(3,7,1),(3,8,1),(3,9,1),(3,10,1),(3,11,1),(3,13,1),(4,3,1),(4,5,1),(4,6,1),(4,7,1),(4,8,1),(4,9,1),(4,10,1),(4,11,1),(4,13,1),(5,3,1),(5,5,1),(5,6,1),(5,7,1),(5,8,1),(5,9,1),(5,10,1),(5,11,1),(5,13,1),(6,15,1),(6,16,1),(6,19,1),(6,20,1),(6,22,1),(6,23,1),(6,24,1),(6,26,1),(7,23,1),(7,24,1),(7,25,1),(7,27,1),(7,29,1),(7,30,1),(7,31,1),(7,32,1),(7,33,1),(7,35,1),(8,23,1),(8,24,1),(8,25,1),(8,37,1),(8,38,1),(9,23,1),(9,24,1),(9,25,1),(9,39,1),(9,40,1),(10,23,1),(10,24,1),(10,25,1),(10,41,1),(10,42,1),(11,23,1),(11,24,1),(11,25,1),(11,43,1),(12,25,1),(12,44,1),(12,45,1),(13,25,1),(13,46,1),(13,47,1),(14,11,1),(14,25,1),(14,49,1),(14,50,1),(14,51,1),(14,52,1),(14,53,1),(14,55,1),(14,56,1),(14,57,1),(15,25,1),(16,25,1),(16,26,1),(16,42,1),(16,58,1),(16,59,1),(16,60,1),(17,3,1),(17,11,1),(17,61,1),(17,62,1),(17,63,1),(17,64,1),(17,65,1),(18,3,1),(18,11,1),(18,61,1),(18,62,1),(18,63,1),(18,64,1),(18,65,1),(19,3,1),(19,11,1),(19,62,1),(19,63,1),(19,64,1),(19,65,1),(19,66,1),(20,3,1),(20,11,1),(20,61,1),(20,62,1),(20,63,1),(20,64,1),(20,65,1),(21,3,1),(21,11,1),(21,61,1),(21,62,1),(21,67,1),(21,68,1),(21,69,1),(22,3,1),(22,11,1),(22,61,1),(22,62,1),(22,67,1),(22,68,1),(22,69,1),(23,3,1),(23,11,1),(23,61,1),(23,62,1),(23,67,1),(23,68,1),(23,69,1),(24,3,1),(24,11,1),(24,13,1),(24,62,1),(24,67,1),(24,68,1),(24,69,1),(6,21,2),(7,1,2),(7,14,2),(8,1,2),(8,14,2),(8,36,2),(9,1,2),(9,14,2),(10,1,2),(10,14,2),(11,1,2),(11,14,2),(12,1,2),(12,14,2),(13,1,2),(13,14,2),(14,1,2),(14,14,2),(14,28,2),(14,54,2),(15,1,2),(15,14,2),(16,1,2),(16,14,2),(17,12,2),(17,28,2),(18,12,2),(18,28,2),(19,12,2),(19,28,2),(19,61,2),(20,12,2),(20,28,2),(21,12,2),(21,28,2),(22,12,2),(22,28,2),(23,12,2),(23,28,2),(24,28,2),(1,4,3),(2,4,3),(3,4,3),(4,4,3),(5,4,3),(6,17,3),(6,18,3),(6,25,3),(7,28,3),(7,34,3),(24,61,3),(6,3,4),(6,14,4),(7,2,4),(8,2,4),(9,2,4),(10,2,4),(11,2,4),(12,2,4),(13,2,4),(14,2,4),(14,48,4),(15,2,4),(16,2,4),(1,12,5),(2,12,5),(3,12,5),(4,12,5),(5,12,5),(24,12,5),(6,2,6),(7,3,6),(7,12,6),(8,3,6),(8,12,6),(9,3,6),(9,12,6),(10,3,6),(10,12,6),(11,3,6),(11,12,6),(12,3,6),(12,12,6),(13,3,6),(13,12,6),(14,3,6),(14,12,6),(15,3,6),(15,12,6),(16,3,6),(16,12,6),(1,1,10),(1,2,10),(2,1,10),(2,2,10),(3,1,10),(3,2,10),(4,1,10),(4,2,10),(5,1,10),(5,2,10),(17,1,10),(17,2,10),(18,1,10),(18,2,10),(19,1,10),(19,2,10),(20,1,10),(20,2,10),(21,2,10),(21,15,10),(22,2,10),(22,15,10),(23,2,10),(23,15,10),(24,2,10),(24,15,10),(6,12,18);
/*!40000 ALTER TABLE `os_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `os_tables`
--

DROP TABLE IF EXISTS `os_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `os_tables` (
  `table_name` varchar(128) NOT NULL,
  `table_url` varchar(255) NOT NULL,
  PRIMARY KEY (`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `os_tables`
--

LOCK TABLES `os_tables` WRITE;
/*!40000 ALTER TABLE `os_tables` DISABLE KEYS */;
/*!40000 ALTER TABLE `os_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `os_word`
--

DROP TABLE IF EXISTS `os_word`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `os_word` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `word` (`word`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `os_word`
--

LOCK TABLES `os_word` WRITE;
/*!40000 ALTER TABLE `os_word` DISABLE KEYS */;
INSERT INTO `os_word` VALUES (35,''),(12,'0000'),(62,'1966'),(67,'1967'),(63,'1970'),(18,'2009'),(16,'2014'),(2,'2016'),(69,'archibald'),(26,'autr'),(39,'balad'),(43,'blog'),(6,'celio'),(11,'com'),(57,'comment'),(55,'commentair'),(41,'concour'),(5,'conort'),(48,'contact'),(22,'contenu'),(65,'david'),(52,'defaut'),(7,'dresseur'),(4,'elev'),(54,'email'),(59,'enregistr'),(64,'erlo'),(10,'fdg'),(9,'fdgfdgfd'),(49,'formulair'),(50,'gencontact'),(60,'genrecordrepons'),(37,'gentemoignag'),(68,'haddock'),(33,'jdflqg'),(32,'jsdqkl'),(47,'legal'),(14,'lign'),(53,'mail'),(17,'mar'),(46,'mention'),(23,'menu'),(25,'menuroot'),(3,'novembr'),(51,'ocm'),(15,'octobr'),(21,'paragraph'),(8,'pixel'),(44,'plan'),(13,'pourquoi'),(27,'present'),(24,'principal'),(61,'prof'),(66,'question'),(38,'repons'),(29,'sdf'),(34,'sdfg'),(30,'sdjkf'),(31,'sdjklf'),(1,'septembr'),(45,'sit'),(19,'siteroot'),(36,'temoignag'),(58,'temoignez'),(28,'test'),(56,'textarea'),(20,'titr'),(40,'urbain'),(42,'video');
/*!40000 ALTER TABLE `os_word` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_academie`
--

DROP TABLE IF EXISTS `p_academie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p_academie` (
  `academie_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `academie_titre_fr` varchar(255) NOT NULL,
  PRIMARY KEY (`academie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_academie`
--

LOCK TABLES `p_academie` WRITE;
/*!40000 ALTER TABLE `p_academie` DISABLE KEYS */;
INSERT INTO `p_academie` VALUES (1,'Besançon'),(2,'Bordeaux'),(3,'Clermont-Ferrand'),(4,'Dijon'),(5,'Grenoble'),(6,'la Guadeloupe'),(7,'la Guyane'),(8,'la Martinique'),(9,'La Réunion'),(10,'Limoges'),(11,'Lyon'),(12,'Poitiers'),(13,'Aix-Marseille'),(14,'Amiens'),(15,'Orléans-Tours'),(16,'Caen'),(17,'Corse'),(18,'Lille'),(19,'Nancy-Metz'),(20,'Nantes'),(21,'Nice'),(22,'Reims'),(23,'Rennes'),(24,'Rouen'),(25,'Strasbourg'),(26,'Créteil'),(27,'Montpellier'),(28,'Paris'),(29,'Toulouse'),(30,'Versailles');
/*!40000 ALTER TABLE `p_academie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_article`
--

DROP TABLE IF EXISTS `p_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p_article` (
  `article_id` int(11) NOT NULL,
  `article_date` datetime NOT NULL,
  `article_auteur` varchar(255) NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_article`
--

LOCK TABLES `p_article` WRITE;
/*!40000 ALTER TABLE `p_article` DISABLE KEYS */;
INSERT INTO `p_article` VALUES (20,'2016-10-19 00:00:00','Moi même'),(21,'2016-10-19 00:00:00','Moi même'),(22,'2016-10-02 00:00:00','Moi m\'aime'),(23,'2016-10-14 00:00:00','C*'),(24,'2016-10-14 00:00:00','C*');
/*!40000 ALTER TABLE `p_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_internaute`
--

DROP TABLE IF EXISTS `p_internaute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p_internaute` (
  `internaute_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `internaute_statut` enum('eleve','prof') NOT NULL,
  `fk_academie_id` int(11) NOT NULL,
  `internaute_annee_debut` smallint(6) NOT NULL,
  `internaute_annee_fin` smallint(6) NOT NULL,
  `internaute_nom` varchar(255) NOT NULL,
  `internaute_prenom` varchar(255) NOT NULL,
  `internaute_profession` varchar(255) NOT NULL,
  `internaute_email` varchar(255) NOT NULL,
  `ocms_date_crea` datetime NOT NULL,
  `ocms_date_modif` datetime NOT NULL,
  PRIMARY KEY (`internaute_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_internaute`
--

LOCK TABLES `p_internaute` WRITE;
/*!40000 ALTER TABLE `p_internaute` DISABLE KEYS */;
INSERT INTO `p_internaute` VALUES (1,'prof',5,16,16,'fsdfds','dsfdsfdsf','','dsfdsfds@fds.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'prof',5,16,16,'fsdfds','dsfdsfdsf','','dsfdsfds@fds.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'prof',5,16,16,'fsdfds','dsfdsfdsf','','dsfdsfds@fds.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'eleve',3,1995,0,'Conort','Célio','dresseur de pixels','fdgfdgfd@fdg.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,'prof',3,0,0,'Suce','Usure','','Ivry@juste.cim','0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,'eleve',3,1990,2000,'Conort','Célio','dresseur de pixel','conort@gmail.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(7,'prof',3,6,0,'TVHD','T\'en','','Ivry@juste.cim','0000-00-00 00:00:00','0000-00-00 00:00:00'),(8,'prof',6,2,0,'fdfds','fdsf','','dsfdsfds@fds.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(9,'prof',13,1966,1970,'Erlos','David','','test@test.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(10,'prof',13,1966,1967,'Durand','Bertrand','','test@test.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(11,'prof',3,1970,1973,'Le roi de la banane','Bernard','','bernard@test.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(12,'prof',13,1966,1967,'Haddock','Archibald','','test@test.com','0000-00-00 00:00:00','0000-00-00 00:00:00'),(13,'prof',2,1968,1975,'ythfg','hgfhgf','','hgfhgf@fds.com','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `p_internaute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_question`
--

DROP TABLE IF EXISTS `p_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p_question` (
  `question_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_texte` varchar(151) NOT NULL,
  `fk_internaute_id` int(11) NOT NULL,
  `ocms_date_crea` datetime NOT NULL,
  `ocms_date_modif` datetime NOT NULL,
  `en_ligne` tinyint(4) NOT NULL,
  `question_pour` enum('prof','eleve') NOT NULL,
  `question_annee` year(4) NOT NULL,
  PRIMARY KEY (`question_id`),
  UNIQUE KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_question`
--

LOCK TABLES `p_question` WRITE;
/*!40000 ALTER TABLE `p_question` DISABLE KEYS */;
INSERT INTO `p_question` VALUES (1,'pourquoi prof ?',1,'0000-00-00 00:00:00','0000-00-00 00:00:00',1,'prof',0000),(2,'pourquoi eleve ?',2,'0000-00-00 00:00:00','0000-00-00 00:00:00',1,'eleve',0000),(4,'Que doit savoir faire un chasseur sachant chasser ?',12,'2016-10-06 10:29:21','0000-00-00 00:00:00',1,'eleve',1987),(5,'fdsfds',12,'2016-10-06 10:32:01','0000-00-00 00:00:00',0,'prof',1972);
/*!40000 ALTER TABLE `p_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_reponse`
--

DROP TABLE IF EXISTS `p_reponse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p_reponse` (
  `reponse_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reponse_question` enum('','question_eleve_1','question_eleve_2','question_eleve_3','question_prof_1','question_prof_2','question_prof_3') NOT NULL,
  `fk_question_id` int(11) NOT NULL,
  `reponse_vignette` varchar(255) NOT NULL,
  `reponse_video` varchar(255) NOT NULL,
  `fk_internaute_id` int(11) NOT NULL,
  `ocms_date_crea` datetime NOT NULL,
  `ocms_date_modif` datetime NOT NULL,
  `en_ligne` tinyint(1) NOT NULL,
  PRIMARY KEY (`reponse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_reponse`
--

LOCK TABLES `p_reponse` WRITE;
/*!40000 ALTER TABLE `p_reponse` DISABLE KEYS */;
INSERT INTO `p_reponse` VALUES (12,'',2,'vignette.jpg','video.mp4',4,'2016-09-19 16:13:01','0000-00-00 00:00:00',1),(13,'',2,'vignette.jpg','video.mp4',4,'2016-09-19 16:20:09','0000-00-00 00:00:00',1),(14,'',2,'vignette.jpg','video.mp4',4,'2016-09-20 09:13:17','0000-00-00 00:00:00',1),(15,'',2,'vignette.jpg','video.mp4',4,'2016-09-20 16:12:58','0000-00-00 00:00:00',1),(16,'',2,'vignette.jpg','video.mp4',4,'2016-09-20 16:28:08','0000-00-00 00:00:00',1),(18,'question_prof_1',0,'vignette.jpg','video.mp4',9,'2016-09-23 13:05:31','0000-00-00 00:00:00',1),(19,'',0,'vignette.jpg','video.mp4',9,'2016-09-23 14:41:39','0000-00-00 00:00:00',1),(20,'',0,'vignette.jpg','video.mp4',12,'2016-10-06 08:15:12','0000-00-00 00:00:00',1),(21,'',0,'vignette.jpg','video.mp4',12,'2016-10-06 08:17:07','0000-00-00 00:00:00',1),(22,'',0,'vignette.jpg','video.mp4',12,'2016-10-06 08:17:30','0000-00-00 00:00:00',1),(23,'',1,'vignette.jpg','video.mp4',12,'2016-10-06 08:52:00','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `p_reponse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plug_contact`
--

DROP TABLE IF EXISTS `plug_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plug_contact` (
  `contact_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_titre_fr` varchar(255) NOT NULL DEFAULT '',
  `contact_email` varchar(255) NOT NULL DEFAULT '',
  `fk_rubrique_id` int(11) NOT NULL DEFAULT '0',
  `contact_ordre` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contact_id`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `contact_ordre` (`contact_ordre`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plug_contact`
--

LOCK TABLES `plug_contact` WRITE;
/*!40000 ALTER TABLE `plug_contact` DISABLE KEYS */;
INSERT INTO `plug_contact` VALUES (1,'Contact par défaut','test@test.com',12,1);
/*!40000 ALTER TABLE `plug_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plug_contact_field`
--

DROP TABLE IF EXISTS `plug_contact_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plug_contact_field` (
  `contact_field_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_rubrique_id` int(10) unsigned NOT NULL,
  `contact_field_ordre` smallint(5) unsigned NOT NULL,
  `contact_field_nom_fr` varchar(255) NOT NULL,
  `contact_field_type` enum('text','select','radio','textarea','email','submit','hidden','selectm','html','file','fieldset','endfieldset','checkbox','captcha_question') NOT NULL,
  `contact_field_needed` tinyint(1) NOT NULL,
  `contact_field_default_value` varchar(255) NOT NULL,
  `contact_field_values_fr` varchar(255) NOT NULL,
  `contact_field_name` varchar(64) NOT NULL,
  PRIMARY KEY (`contact_field_id`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `contact_field_ordre` (`contact_field_ordre`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plug_contact_field`
--

LOCK TABLES `plug_contact_field` WRITE;
/*!40000 ALTER TABLE `plug_contact_field` DISABLE KEYS */;
INSERT INTO `plug_contact_field` VALUES (1,12,1,'E-mail','email',1,'','','c_email'),(2,12,2,'Commentaire','textarea',1,'','','c_comment');
/*!40000 ALTER TABLE `plug_contact_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_admin`
--

DROP TABLE IF EXISTS `s_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_admin` (
  `admin_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `admin_login` varchar(64) NOT NULL DEFAULT '',
  `admin_nom` varchar(255) NOT NULL DEFAULT '',
  `admin_pwd` varchar(255) NOT NULL DEFAULT '',
  `admin_email` varchar(255) NOT NULL DEFAULT '',
  `admin_type` enum('redacteur','valideur','administrateur') NOT NULL DEFAULT 'redacteur',
  `admin_last_cx` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_login` (`admin_login`),
  KEY `admin_login_2` (`admin_login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_admin`
--

LOCK TABLES `s_admin` WRITE;
/*!40000 ALTER TABLE `s_admin` DISABLE KEYS */;
INSERT INTO `s_admin` VALUES (1,'opixido','opixido','$2y$10$N/R7lbioWTeZ7htAUKJAYO6B2piiYG1jczUZltIJWh2hDnumCpZIS','','redacteur','2016-10-03 09:01:11');
/*!40000 ALTER TABLE `s_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_admin_role`
--

DROP TABLE IF EXISTS `s_admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_admin_role` (
  `fk_admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_role_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fk_admin_id`,`fk_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_admin_role`
--

LOCK TABLES `s_admin_role` WRITE;
/*!40000 ALTER TABLE `s_admin_role` DISABLE KEYS */;
INSERT INTO `s_admin_role` VALUES (1,1);
/*!40000 ALTER TABLE `s_admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_admin_rows`
--

DROP TABLE IF EXISTS `s_admin_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_admin_rows` (
  `fk_admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_row_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_table` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`fk_admin_id`,`fk_row_id`,`fk_table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_admin_rows`
--

LOCK TABLES `s_admin_rows` WRITE;
/*!40000 ALTER TABLE `s_admin_rows` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_admin_rows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_admin_trad`
--

DROP TABLE IF EXISTS `s_admin_trad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_admin_trad` (
  `admin_trad_id` varchar(128) NOT NULL DEFAULT '',
  `admin_trad_fr` text NOT NULL,
  `fk_plugin_id` varchar(64) NOT NULL,
  PRIMARY KEY (`admin_trad_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_admin_trad`
--

LOCK TABLES `s_admin_trad` WRITE;
/*!40000 ALTER TABLE `s_admin_trad` DISABLE KEYS */;
INSERT INTO `s_admin_trad` VALUES ('droits_insuffisants_pour_cette_action','Vous n\'avez pas les droits nécessaires pour effectuer cette action',''),('rubrique_pas_en_ligne','Cette rubrique est déjà masquée',''),('rubrique_refuser_ok','Votre refus de validation a bien été pris en compte',''),('refuse','Refuser la validation',''),('unvalidate','Masquer',''),('save_and_close','Retour',''),('src_refuse','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-undo.png',''),('src_light_edit','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/applications-graphics.png',''),('src_modification','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png',''),('src_voir_version_en_ligne','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-template.png',''),('src_voir_version_modifiable','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-properties.png',''),('src_calendrier','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/office-calendar.png',''),('src_random_password','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/preferences-system-session.png',''),('src_locked','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/locked.png',''),('src_importftp','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-symbolic-link.png',''),('src_copy','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/places/start-here.png',''),('generate_random_password','Générer un mot de passe',''),('erreur_lock_existe','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/locked.png\" alt=\"\" /> Une autre personne est déjà <br/>en train de modifier cet élément',''),('s_admin_p_0','Informations',''),('s_admin_p_1','Rubriques autorisées',''),('table_log_action','Dernières opérations effectuées',''),('modification','Modifier',''),('empty_field','Champ vide',''),('ajout_sub_rub','Nouveau',''),('invisible_rub',' <span style=\"color:red\">[Masquée]</span>',''),('version_masquee',' Cette rubrique est actuellement masquée',''),('suppression_ok','Suppression effectuée pour : ',''),('modification_ok','Changements enregistrés pour : ',''),('ajout_ok','correctement ajouté',''),('connexion','Connexion ... ',''),('username','Nom d\'utilisateur',''),('password','Mot de passe',''),('save','Enregistrer',''),('cancel','Annuler',''),('delete','Supprimer',''),('search_id','Rechercher sur un identifiant : ',''),('search_txt','Extrait de texte &agrave; rechercher ',''),('choose_item','Choisissez un &eacute;l&eacute;ment &agrave; modifier',''),('rechercher','Lancer la recherche',''),('confirm_suppr','Voulez vous vraiment supprimer cet enregistrement ',''),('ajouter','Cliquez sur cet icone pour ajouter un &eacute;l&eacute;ment &agrave; la liste : ',''),('ajouter_elem','Ajouter un &eacute;l&eacute;ment',''),('menu','Menu',''),('logout','D&eacute;connexion',''),('menu1','Gestion de la base',''),('menu2',' ',''),('retour',' retour',''),('selectable_items','El&eacute;ments s&eacute;lectionnables',''),('selected_items','El&eacute;ments s&eacute;lectionn&eacute;s',''),('modifier','Modifier',''),('modify_item','Modifier un &eacute;l&eacute;ment',''),('date_picker','Choisissez la date',''),('mal_remplit','Vous avez mal rempli les champs suivants :',''),('etes_vous_sur_action','Souhaitez vous vraiment effectuer cette action ?',''),('help_tablerel','<b>Champ Multicritère partagés</b><br/>Vous avez la possibilité de choisir plusieurs éléments.<br/><br/>Vous devez sélectionner l\'élément voulu dans la colonne de gauche, puis cliquer sur la flêche orientée vers la droite pour le faire passer dans la liste des éléments sélectionnés',''),('help_rte','<b>Champ Wyziwyg</b><br/>Vous avez la possibilité de mettre ce texte en forme via un outil graphique. Pour accéder au texte, cliquez sur l\'icône d\'édition à gauche ',''),('help_upload_table','<b>Champ de mise en ligne de tableau CSV</b><br/>Vous avez la possibilité de parcourir le disque de votre ordinateur, et d\'envoyer un fichier Excel (format CSV) pour remplir ce champ par un tableau de données',''),('upload_table','Mettre en ligne un tableau (CSV)',''),('champ_vide','Ce champ est actuellement vide',''),('help_texte','<b>Champ Texte</b><br/>Cliquez dans le champ puis saisissez ou bien copiez/collez votre texte.',''),('help_relinv_table','<b>Eléments liés</b><br/>Vous avez ci-contre la liste des éléments liés.<br/><br/> Sélectionnez un élément puis cliquez sur l\'icône d\'édition pour le modifier ',''),('help_relation','<b>Sélection simple</b><br/>Vous avez la possibilité de sélectionner un élément dans cette liste ',''),('help_file','<b>Mise en ligne de fichier</b><br/>Pour mettre en ligne un fichier, cliquez sur le bouton <i>PARCOURIR</i>, puis sélectionnez le fichier voulu sur votre ordinateur.<br/>Ensuite cliquez sur le bouton <i>Mettre en ligne</i>, pour envoyer le fichier sur le serveur.<br/><br/>Si un fichier existe déjà, vous pouvez refaire la procédure ci-dessus pour le mettre à jour, mais l\'ancienne versin sera supprimée<br/><br/>Vous pouvez également supprimer le fichier sans le remplacer en cliquant sur le bouton <i>Supprimer</i>',''),('aucun_element','Il n\'y a aucun élément dans cette liste',''),('select_rub_below','Sélectionnez une page ci-dessous',''),('rubrique_valider_ok','Cette rubrique a bien été validée. Vos modifications sont désormais visibles par les internautes ',''),('rubrique_demande_valider_ok','Votre demande de validation a bien été transmise à : ',''),('rubrique_devalider_ok','Cette rubrique a bien été masquée.<br/>Les internautes n\'y ont plus accès',''),('src_editer','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png',''),('src_edit','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png',''),('src_voir','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png',''),('src_view','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png',''),('src_new','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/list-add.png',''),('src_preview','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/image-x-generic.png',''),('src_droite','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-last.png',''),('src_gauche','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-first.png',''),('src_help','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/apps/help-browser.png',''),('src_logout','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-log-out.png',''),('src_NewsletterPreview','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-news-reader.png',''),('src_NewsletterFakeSend','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/evolution.png',''),('src_NewsletterSend','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-mail.png',''),('src_ask_for_validation','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/system-software-update.png',''),('src_cancel','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-undo.png',''),('src_delete','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/process-stop.png',''),('src_del','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/process-stop.png',''),('src_validate','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png',''),('src_validateVersion','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png',''),('src_showObject','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png',''),('src_unvalidate','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png',''),('src_hideVersion','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png',''),('src_hideObject','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png',''),('src_upload','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/x-directory-remote-workgroup.png',''),('src_up','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-up.png',''),('src_down','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-down.png',''),('src_date','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-down.png',''),('src_SiteValidate','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png',''),('src_PublicationValidate','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png',''),('src_SiteHide','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png',''),('src_PublicationHide','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png',''),('src_back','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-previous.png',''),('src_first','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-previous.png',''),('src_save','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-save.png',''),('src_saveas','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-saveas.png',''),('src_search','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-search.png',''),('src_desktop','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/places/user-home.png',''),('src_close','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record.png',''),('src_message_info','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/dialog-information.png',''),('src_message_error','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/dialog-warning.png',''),('src_undo','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-undo.png',''),('src_go','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-find.png',''),('src_InstallPlugin','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/install.png',''),('src_UninstallPlugin','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/uninstall.png',''),('search','Rechercher',''),('preview','Pré-visualiser',''),('field_img_paragraphe_titre_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png',''),('field_img_paragraphe_titre_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png',''),('field_img_rubrique_titre_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png',''),('field_img_rubrique_titre_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png',''),('field_img_r_rubrique_lexique','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/x-office-address-book.png',''),('field_img_fk_liens_id','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/mail-attachment.png',''),('field_img_fk_paragraphe_id','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/format-justify-left.png',''),('field_img_rubrique_desc_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png',''),('field_img_rubrique_desc_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png',''),('field_img_rubrique_keywords_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png',''),('field_img_rubrique_keywords_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png',''),('field_img_rubrique_gabarit_param','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png',''),('field_img_fk_gabarit_id','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png',''),('field_img_fk_para_type_id','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png',''),('field_img_paragraphe_img_1_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/image-x-generic.png',''),('field_img_paragraphe_img_1_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/image-x-generic.png',''),('field_help_rubrique_titre_en','<b>Titre de la page</b><br/>Il apparaitra dans les menus de navigation,<br/> la barre de titre du navigateur,<br/> et les résultats du moteur de recherche',''),('field_help_rubrique_titre_fr','<b>Titre de la page</b><br/>Il apparaitra dans les menus de navigation,<br/> la barre de titre du navigateur,<br/> et les résultats du moteur de recherche',''),('field_help_fk_paragraphe_id','<b>Paragraphes de cete page</b><br/>Votre page est constituée de plusieurs paragraphes. <br/><br/>Vous pouvez voir ci-dessous les paragraphes déjà créés, et cliquer sur l\'icône en début de ligne pour les modifier indépendement',''),('paragraphe_titre_fr','Titre en Français',''),('paragraphe_titre_en','Titre en Anglais',''),('paragraphe_contenu_fr','Contenu en Français',''),('paragraphe_contenu_en','Contenu en Anglais',''),('paragraphe_img_1_fr','Image d\'accompagnement Française',''),('paragraphe_img_1_en','Image d\'accompagnement Anglaise',''),('paragraphe_img_1_alt_fr','Description Française de l\'image ci-dessus',''),('paragraphe_img_1_alt_en','Description Anglaise de l\'image ci-dessus',''),('max_size','Taille max. ',''),('tester_le_lien','Tester le lien dans une popup',''),('choisir_rubrique','Choisir une page dans le site',''),('choisir_rubrique_ci_dessous','Cliquez sur une rubrique ci-dessous',''),('voir','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png\"  alt=\"Voir\" />',''),('editer','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png\"  alt=\"Editer\" />',''),('access_direct','Accès direct ',''),('mettre_en_ligne','Mettre en ligne',''),('non_renseigne','Non renseign&eacute;',''),('exporter','Exporter la table au format CSV',''),('vider','Vider le contenu de la table',''),('etes_vous_sur','Etes vous sur de vouloir vider cette table ? ',''),('il_y_a','Il y a ',''),('resultats','R&eacute;sultat(s)',''),('recherche_textuelle','Recherche plein texte',''),('recherche_avancee','Recherche avanc&eacute;e ',''),('fichier_uploader','Mettre en ligne un fichier',''),('fichier_remplacer','Remplacer ce fichier par : ',''),('supprimer_fichier','Voulez vous vraiment supprimer ce fichier du serveur ?',''),('impossible_supprimer_repertoire','Le r&eacute;pertoire contentant les fichiers de cet &eacute;l&eacute;ment n\'a pas pu etre supprim&eacute;',''),('impossible_supprimer','Ce fichier n\'a pas pu &agrave;tre supprim&eacute; : ',''),('editer_wysiwyg','Cliquez ici pour acc&eacute;der &agrave; l\'&eacute;diteur WYSIWYG complet pour mettre en forme ce champ',''),('voir_wysiwyg','Cliquez ici pour voir le contenu de ce champ dans une nouvelle fen&ecirc;tre',''),('edit','Modifier',''),('edis_image','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-properties.png\" border=\"0\" alt=\"Editer\" /> Retailler, recadrer, optimiser ... cette image',''),('deja_fichier','Fichier existant',''),('menu_0','Page d\'accueil',''),('menu_1','Lexique',''),('menu_2','Param&egrave;tres techniques',''),('t_event_p_0','Evènement Agenda',''),('t_action_p_0','Actions',''),('t_link_p_0','Lien',''),('t_bloc_home_p_0','Bloc Page d\'accueil',''),('arborescence','Rubriques',''),('getup','Monter cette ligne',''),('getdown','Descendre cette ligne',''),('rubrique_meta_keyw_fr','Mots clefs d&eacute;finissants la page (FR) (1000 char)',''),('rubrique_meta_keyw_en','Mots clefs d&eacute;finissants la page (EN) (1000 char)',''),('rubrique_meta_desc_fr','Description de la page (FR) (255 char max)',''),('rubrique_meta_desc_en','Description de la page (EN) (255 char max)',''),('rubrique_visible','Afficher cette rubrique',''),('fk_paragraphe_id','Liste des paragraphes constituants cette page',''),('fk_para_type_id','Type de paragraphe',''),('fk_gabarit_id','Gabarit Sp&eacute;cial',''),('rubrique_gabarit_param','Param&egrave;tre technique du gabarit',''),('rubrique_pos_box','BoÃ®te contextuelles align&eacute;es en haut de la page',''),('meta_informations','M&eacute;ta informations',''),('rubrique_keywords_fr','Mots clefs FR (255 chars)',''),('rubrique_keywords_en','Mots clefs EN(255 chars)',''),('rubrique_desc_fr','Description courte de la page FR',''),('rubrique_desc_en','Description courte de la page EN',''),('desc_meta','Ces informations ne seront pas visible dans la page, mais serviront au bon r&eacute;f&eacute;rencement de cette page.<br/>La description peut apparaitre dans la liste des r&eacute;sultats du moteur de recherche',''),('s_param','Param&egrave;tres du site',''),('url_name','Titre pour le lien',''),('s_rubrique','Rubriques du site',''),('s_param_p_0','Param&egrave;tre',''),('s_rubrique_p_0','Contenu',''),('s_rubrique_p_1','Référencement',''),('s_rubrique_p_2','Propriétés',''),('s_rubrique_p_4','Publications',''),('s_rubrique_p_3','Meta donn&eacute;es',''),('s_rubrique_p_5','Rendez-Vous',''),('s_rubrique_p_6','Liens document&eacute;s',''),('s_rubrique_p_7','FAQs',''),('s_rubrique_p_questions','Questions',''),('fk_thematique_id','Thématique d\'application de cette association',''),('rubrique_titre_fr','Titre de la rubrique en Français',''),('rubrique_titre_en','Titre de la rubrique en Anglais',''),('s_rubrique.fk_site_id','Liste des liens document&eacute;s ratach&eacute;s &agrave; cette cat&eacute;gorie',''),('url_fr_will_be','Le nom du dossier en Français de cette rubrique sera : ',''),('url_en_will_be','Le nom du dossier en Anglais de cette rubrique sera :',''),('fk_rubrique_id','Pages',''),('s_trad','Textes param&eacute;trables',''),('t_fichier_p_0','Fichier',''),('s_paragraphe_p_0','Format de paragraphe',''),('s_paragraphe_p_1','Texte Français',''),('s_paragraphe_p_2','Texte Anglais',''),('voir_actualite','',''),('voir_editions_patrimoine','',''),('voir_fiche_grand_public','',''),('voir_reponses','',''),('ce_s_paragraphe','ce Paragraphe',''),('ce_s_image','cette Image',''),('ce_t_lexique','cette D&eacute;finition',''),('un_s_paragraphe','un Paragraphe',''),('ce_s_rubrique','cette Rubrique',''),('un_s_rubrique','une Rubrique',''),('info_crea_rub','Cr&eacute;ation d\'une nouvelle rubrique',''),('version_modifiable','Ceci est la version modifiable de la page',''),('version_en_ligne','Ceci est la version en ligne de la page',''),('voir_version_modifiable','Voir la version modifiable',''),('voir_version_en_ligne','Voir la version en ligne',''),('show_actions','Actions possibles',''),('validate','Valider',''),('ask_for_validation','Demander la validation',''),('contenu_en_bdd','Une partie de cette page provient d\'une base externe<br/>',''),('InstallPlugin','Installer',''),('UninstallPlugin','Désinstaller',''),('cp_attente_rubs','Rubriques en-cours de validation',''),('cp_valide_rubs','Derni&egrave;res rubriques valid&eacute;es',''),('cp_create_rubs','Derni&egrave;res rubriques cr&eacute;&eacute;es',''),('cp_no_rubs_updated','Aucune rubrique en attente de validation',''),('cp_user_info','Informations utilisateur',''),('emptyCache','Vider le cache',''),('reIndexSearch','Ré-indexer le site',''),('files_on','fichiers sur',''),('have_been_removed','ont bien été supprimés',''),('validateVersion','Valider les modifications',''),('hideVersion','Masquer cet élément',''),('hideObject','Masquer cet élément',''),('showObject','Mettre en ligne',''),('s_rubrique_p_contenu','Contenu',''),('s_rubrique_p_referencement','Référencement / URI',''),('s_rubrique_p_proprietes','Paramètres',''),('s_rubrique_p_contexte','Téléchargements',''),('src_forumValider','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-jump.png',''),('src_forumInterdire','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-lock-screen.png',''),('help_','Ce titre sea affiché dans les menus, en haut de la page, et dans les résultats des moteurs de recherche',''),('cp_txt_s_rubrique','Gestion du contenu',''),('cp_txt_s_admin','Gestion utilisateurs',''),('cp_txt_s_plugin','Plugins',''),('cp_txt_s_param','Configuration',''),('cp_txt_s_trad','Textes param&eacute;trables',''),('cp_txt_s_admin_trad','Textes de l\'admin',''),('create_and_edit_rub','Continuer',''),('genform_boolean_0','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record.png\" alt=\"Non\" />',''),('genform_boolean_1','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record-green.png\" alt=\"Oui\" />',''),('map_geocode','Trouver la position à partir de l\'adresse',''),('map_usecenter','Enregistrer le centre actuel de la carte',''),('picto_emptyCache','trash',''),('picto_showPhpInfo','tasks',''),('picto_executeSql','download-alt',''),('picto_changeTranslations','flag',''),('picto_recheckTranslations','retweet',''),('picto_setAllUrls','globe',''),('picto_cleanFiles','fire',''),('picto_backupDb','share',''),('picto_o_createPlugin','inbox',''),('showPhpInfo','phpinfo();',''),('executeSql','Requête SQL',''),('changeTranslations','Gérer les langues',''),('recheckTranslations','Ajouter les champs de langue manquants',''),('setAllUrls','Définir toutes les URLs manquantes',''),('cleanFiles','Supprimer les fichiers inutilisés',''),('backupDb','Sauvegarde de la BDD',''),('o_createPlugin','Créer un Plugin',''),('lastActions','Vos derniers éléments modifiés',''),('derniere_connexion','Dernière connexion le ',''),('lastActions_none','Aucune action effectuée',''),('add_another','Ajouter ',''),('liste_global_actions','Actions administrateur ',''),('recherche_rapide','Recherche rapide ',''),('enum_page','Page simple',''),('enum_link','Lien',''),('enum_folder','Dossier',''),('enum_siteroot','Racine de site',''),('enum_menuroot','Menu',''),('base_title','50 ans des SES',''),('imgonglet_s_rubrique_p_contenu','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-paste.png',''),('imgonglet_s_rubrique_p_referencement','[ADMIN_PICTOS_FOLDER]16x16/apps/system-file-manager.png',''),('imgonglet_s_rubrique_p_proprietes','[ADMIN_PICTOS_FOLDER]16x16/emblems/emblem-system.png',''),('field_img_rubrique_titre','[ADMIN_PICTOS_FOLDER]16x16/apps/accessories-text-editor.png',''),('fieldset_img_referencement','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/text-html.png',''),('fieldset_referencement','Référencement ',''),('field_img_rubrique_keywords','[ADMIN_PICTOS_FOLDER]16x16/apps/preferences-desktop-font.png',''),('rubrique_keywords','Mots-clefs',''),('rubrique_desc','Description courte',''),('field_img_rubrique_desc','[ADMIN_PICTOS_FOLDER]16x16/apps/accessories-text-editor.png',''),('field_img_rubrique_url','[ADMIN_PICTOS_FOLDER]16x16/categories/applications-internet.png',''),('rubrique_url','Url de la page',''),('fieldset_img_programmation','[ADMIN_PICTOS_FOLDER]16x16/status/image-loading.png',''),('fieldset_programmation','Programmation / Archivage',''),('field_img_ocms_date_online','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-redo.png',''),('ocms_date_online','Date de mise en ligne',''),('field_img_ocms_date_offline','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-undo.png',''),('ocms_date_offline','Date de mise hors ligne',''),('rubrique_template','Template du site',''),('field_img_rubrique_template','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/text-x-generic-template.png',''),('field_img_rubrique_type','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/package-x-generic.png',''),('rubrique_type','Type de fonctionnement',''),('duplicateMV','Dupliquer cette version',''),('mv_duplicate_and_edit_draft','Modifier (nouveau brouillon)',''),('mv_edit_draft','Modifier (le brouillon)',''),('publishMV','Publier',''),('src_duplicateMV','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-copy.png',''),('src_publishMV','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/text-html.png',''),('src_unpublishMV','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-unreadable.png',''),('unpublishMV','Mettre Hors Ligne',''),('s_paragraphe_p_paragraphe','Paragraphe',''),('imgonglet_s_paragraphe_p_paragraphe','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-paste.png',''),('field_img_paragraphe_titre','[ADMIN_PICTOS_FOLDER]16x16/actions/format-justify-left.png',''),('paragraphe_titre','Titre',''),('field_img_paragraphe_contenu','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-paste.png',''),('paragraphe_contenu','Contenu',''),('paragraphe_img_1','Image',''),('field_img_paragraphe_img_1','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/image-x-generic.png',''),('paragraphe_img_1_alt','Texte alternatif à l’image (uniquement si elle fourni une information autrement inaccessible)',''),('field_img_paragraphe_img_1_alt','[ADMIN_PICTOS_FOLDER]16x16/apps/preferences-desktop-accessibility.png',''),('paragraphe_img_1_legend','Légende/copyright',''),('field_img_paragraphe_img_1_legend','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/application-certificate.png',''),('imgonglet_s_trad_p_0','[ADMIN_PICTOS_FOLDER]16x16/actions/format-text-bold.png',''),('s_trad_p_0','Texte paramétrable',''),('trad_id','Identifiant (ne pas changer)',''),('field_img_trad','[ADMIN_PICTOS_FOLDER]16x16/actions/format-justify-left.png',''),('trad','Traduction',''),('field_img_plugin_nom','[ADMIN_PICTOS_FOLDER]16x16/actions/format-justify-center.png',''),('plugin_nom','Nom',''),('field_img_plugin_actif','[ADMIN_PICTOS_FOLDER]16x16/status/software-update-available.png',''),('plugin_actif','Actif',''),('picto_updateDatabase','circle-arrow-up',''),('imgonglet_s_rubrique_p_new','[ADMIN_PICTOS_FOLDER]16x16/actions/document-new.png',''),('s_rubrique_p_new','Nouvelle page',''),('moveRubrique','Déplacer cette rubrique',''),('src_moveRubrique','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-symbolic-link.png',''),('fieldset-nbfields','champs masqués',''),('confirm_delete','Pour supprimer cet élément saisissez \"OUI\" ci-dessous',''),('rubrique_picto','Image principale / vignette',''),('field_img_rubrique_picto','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/image-x-generic.png','');
/*!40000 ALTER TABLE `s_admin_trad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_bloc`
--

DROP TABLE IF EXISTS `s_bloc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_bloc` (
  `bloc_nom` varchar(32) NOT NULL,
  `bloc_afterinit` text NOT NULL,
  `bloc_classe` varchar(64) NOT NULL,
  `bloc_visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bloc_nom`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_bloc`
--

LOCK TABLES `s_bloc` WRITE;
/*!40000 ALTER TABLE `s_bloc` DISABLE KEYS */;
INSERT INTO `s_bloc` VALUES ('footer','','',1),('header','','',1),('main_after','','',1),('main_before','','',1),('outside_after','','',1);
/*!40000 ALTER TABLE `s_bloc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_gabarit`
--

DROP TABLE IF EXISTS `s_gabarit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_gabarit` (
  `gabarit_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `gabarit_titre` varchar(128) NOT NULL DEFAULT '',
  `gabarit_para_crea` varchar(255) NOT NULL DEFAULT '',
  `gabarit_para_include` varchar(64) NOT NULL DEFAULT '',
  `gabarit_full_template` longtext NOT NULL,
  `gabarit_bdd_deco` tinyint(1) NOT NULL DEFAULT '0',
  `gabarit_classe` varchar(64) NOT NULL DEFAULT '',
  `gabarit_classe_param` varchar(255) NOT NULL DEFAULT '',
  `gabarit_plugin` varchar(128) NOT NULL,
  `gabarit_index_table` varchar(64) NOT NULL DEFAULT '',
  `gabarit_index_url` varchar(255) NOT NULL DEFAULT '',
  `fk_default_rubrique_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`gabarit_id`),
  KEY `fk_default_rubrique_id` (`fk_default_rubrique_id`),
  KEY `gabarit_classe` (`gabarit_classe`),
  KEY `gabarit_classe_param` (`gabarit_classe_param`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_gabarit`
--

LOCK TABLES `s_gabarit` WRITE;
/*!40000 ALTER TABLE `s_gabarit` DISABLE KEYS */;
INSERT INTO `s_gabarit` VALUES (1,'Formulaire de contact','','','',0,'genContact','','ocms_contact','','',0),(2,'Enregistrement des vidéos','','','',0,'genRecordReponse','','ses','','',0),(3,'Témoignages','','','',0,'genTemoignages','','ses','p_reponse','',0),(4,'Moteur de recherche','','','',0,'genOcmsSearch','','ocms_search','','',0),(5,'Accueil','','','',0,'genAccueil','','ses','','',0),(6,'Articles','','','',0,'genArticles','','ses','','',0),(7,'Article','','','',0,'genArticle','','ses','','',0);
/*!40000 ALTER TABLE `s_gabarit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_groupe`
--

DROP TABLE IF EXISTS `s_groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_groupe` (
  `groupe_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupe_nom` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`groupe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_groupe`
--

LOCK TABLES `s_groupe` WRITE;
/*!40000 ALTER TABLE `s_groupe` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_groupe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_image`
--

DROP TABLE IF EXISTS `s_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `image_img` varchar(255) NOT NULL DEFAULT '',
  `image_titre_fr` varchar(255) NOT NULL DEFAULT '',
  `image_legende_fr` varchar(255) NOT NULL DEFAULT '',
  `image_copyright` varchar(255) NOT NULL DEFAULT '',
  `image_lien_fr` varchar(255) DEFAULT '',
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_image`
--

LOCK TABLES `s_image` WRITE;
/*!40000 ALTER TABLE `s_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_langue`
--

DROP TABLE IF EXISTS `s_langue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_langue` (
  `langue_nom` varchar(64) NOT NULL DEFAULT '',
  `langue_id` varchar(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`langue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_langue`
--

LOCK TABLES `s_langue` WRITE;
/*!40000 ALTER TABLE `s_langue` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_langue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_lock`
--

DROP TABLE IF EXISTS `s_lock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_lock` (
  `fk_admin_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `lock_table` varchar(64) NOT NULL DEFAULT '',
  `lock_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lock_time` bigint(12) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lock_table`,`lock_id`),
  KEY `fk_admin_id` (`fk_admin_id`),
  KEY `lock_time` (`lock_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_lock`
--

LOCK TABLES `s_lock` WRITE;
/*!40000 ALTER TABLE `s_lock` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_lock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_log_action`
--

DROP TABLE IF EXISTS `s_log_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_log_action` (
  `log_action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_admin_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `log_action_table` varchar(128) NOT NULL DEFAULT '',
  `log_action_fk_id` varchar(255) NOT NULL DEFAULT '0',
  `log_action_action` varchar(128) NOT NULL DEFAULT '',
  `log_action_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_action_id`),
  KEY `fk_admin_id` (`fk_admin_id`),
  KEY `log_action_table` (`log_action_table`),
  KEY `log_action_fk_id` (`log_action_fk_id`),
  KEY `log_action_time` (`log_action_time`)
) ENGINE=InnoDB AUTO_INCREMENT=649 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_log_action`
--

LOCK TABLES `s_log_action` WRITE;
/*!40000 ALTER TABLE `s_log_action` DISABLE KEYS */;
INSERT INTO `s_log_action` VALUES (1,1,'s_plugin','ses','insert','2016-09-12 14:47:37'),(2,1,'s_plugin','ses','update','2016-09-12 14:47:37'),(3,1,'s_plugin','ses','afterUpdate','2016-09-12 14:47:37'),(4,1,'s_plugin','ses','InstallPlugin','2016-09-12 14:47:39'),(5,1,'s_rubrique','3','insert','2016-09-12 14:50:10'),(6,1,'s_rubrique','3','update','2016-09-12 14:50:10'),(7,1,'s_rubrique','3','afterUpdate','2016-09-12 14:50:10'),(8,1,'s_rubrique','3','update','2016-09-12 14:50:14'),(9,1,'s_rubrique','3','afterUpdate','2016-09-12 14:50:14'),(10,1,'s_rubrique','3','update','2016-09-12 14:50:23'),(11,1,'s_rubrique','3','afterUpdate','2016-09-12 14:50:23'),(12,1,'s_rubrique','3','publishMV','2016-09-12 14:50:25'),(13,1,'s_rubrique','4','insert','2016-09-12 14:50:31'),(14,1,'s_rubrique','4','update','2016-09-12 14:50:31'),(15,1,'s_rubrique','4','afterUpdate','2016-09-12 14:50:31'),(16,1,'s_rubrique','4','update','2016-09-12 14:50:33'),(17,1,'s_rubrique','4','afterUpdate','2016-09-12 14:50:33'),(18,1,'s_rubrique','4','publishMV','2016-09-12 14:50:35'),(19,1,'s_rubrique','5','insert','2016-09-12 14:50:43'),(20,1,'s_rubrique','5','update','2016-09-12 14:50:43'),(21,1,'s_rubrique','5','afterUpdate','2016-09-12 14:50:43'),(22,1,'s_rubrique','5','update','2016-09-12 14:50:45'),(23,1,'s_rubrique','5','afterUpdate','2016-09-12 14:50:45'),(24,1,'s_rubrique','5','publishMV','2016-09-12 14:50:47'),(25,1,'s_rubrique','6','insert','2016-09-12 14:50:56'),(26,1,'s_rubrique','6','update','2016-09-12 14:50:56'),(27,1,'s_rubrique','6','afterUpdate','2016-09-12 14:50:56'),(28,1,'s_rubrique','6','update','2016-09-12 14:50:58'),(29,1,'s_rubrique','6','afterUpdate','2016-09-12 14:50:58'),(30,1,'s_rubrique','6','publishMV','2016-09-12 14:50:59'),(31,1,'s_rubrique','7','insert','2016-09-12 14:51:09'),(32,1,'s_rubrique','7','update','2016-09-12 14:51:09'),(33,1,'s_rubrique','7','afterUpdate','2016-09-12 14:51:09'),(34,1,'s_rubrique','7','update','2016-09-12 14:51:10'),(35,1,'s_rubrique','7','afterUpdate','2016-09-12 14:51:10'),(36,1,'s_rubrique','7','publishMV','2016-09-12 14:51:12'),(37,1,'s_rubrique','8','insert','2016-09-12 14:51:20'),(38,1,'s_rubrique','8','update','2016-09-12 14:51:20'),(39,1,'s_rubrique','8','afterUpdate','2016-09-12 14:51:20'),(40,1,'s_rubrique','8','update','2016-09-12 14:51:22'),(41,1,'s_rubrique','8','afterUpdate','2016-09-12 14:51:22'),(42,1,'s_rubrique','8','publishMV','2016-09-12 14:51:23'),(43,1,'s_rubrique','9','insert','2016-09-12 14:51:33'),(44,1,'s_rubrique','9','update','2016-09-12 14:51:33'),(45,1,'s_rubrique','9','afterUpdate','2016-09-12 14:51:33'),(46,1,'s_rubrique','9','update','2016-09-12 14:51:37'),(47,1,'s_rubrique','9','afterUpdate','2016-09-12 14:51:37'),(48,1,'s_rubrique','9','update','2016-09-12 14:51:38'),(49,1,'s_rubrique','9','afterUpdate','2016-09-12 14:51:38'),(50,1,'s_rubrique','9','publishMV','2016-09-12 14:51:39'),(51,1,'s_rubrique','10','insert','2016-09-12 14:51:48'),(52,1,'s_rubrique','10','update','2016-09-12 14:51:48'),(53,1,'s_rubrique','10','afterUpdate','2016-09-12 14:51:48'),(54,1,'s_rubrique','10','update','2016-09-12 14:51:50'),(55,1,'s_rubrique','10','afterUpdate','2016-09-12 14:51:50'),(56,1,'s_rubrique','10','publishMV','2016-09-12 14:51:51'),(57,1,'s_rubrique','11','insert','2016-09-12 14:51:58'),(58,1,'s_rubrique','11','update','2016-09-12 14:51:58'),(59,1,'s_rubrique','11','afterUpdate','2016-09-12 14:51:58'),(60,1,'s_rubrique','11','update','2016-09-12 14:51:59'),(61,1,'s_rubrique','11','afterUpdate','2016-09-12 14:51:59'),(62,1,'s_rubrique','11','publishMV','2016-09-12 14:52:01'),(63,1,'s_rubrique','12','insert','2016-09-12 14:52:08'),(64,1,'s_rubrique','12','update','2016-09-12 14:52:08'),(65,1,'s_rubrique','12','afterUpdate','2016-09-12 14:52:08'),(66,1,'s_plugin','ocms_contact','insert','2016-09-12 14:52:19'),(67,1,'s_plugin','ocms_contact','update','2016-09-12 14:52:19'),(68,1,'s_plugin','ocms_contact','afterUpdate','2016-09-12 14:52:19'),(69,1,'s_plugin','ocms_contact','InstallPlugin','2016-09-12 14:52:20'),(70,1,'s_rubrique','12','update','2016-09-12 14:52:29'),(71,1,'s_rubrique','12','afterUpdate','2016-09-12 14:52:29'),(72,1,'s_rubrique','12','update','2016-09-12 14:52:32'),(73,1,'s_rubrique','12','afterUpdate','2016-09-12 14:52:32'),(74,1,'plug_contact','1','insert','2016-09-12 14:52:43'),(75,1,'plug_contact','1','update','2016-09-12 14:52:43'),(76,1,'plug_contact','1','afterUpdate','2016-09-12 14:52:43'),(77,1,'s_rubrique','12','update','2016-09-12 14:52:45'),(78,1,'s_rubrique','12','afterUpdate','2016-09-12 14:52:45'),(79,1,'s_rubrique','12','update','2016-09-12 14:52:53'),(80,1,'s_rubrique','12','afterUpdate','2016-09-12 14:52:53'),(81,1,'s_rubrique','12','publishMV','2016-09-12 14:52:54'),(82,1,'s_rubrique','13','insert','2016-09-12 14:53:01'),(83,1,'s_rubrique','13','update','2016-09-12 14:53:01'),(84,1,'s_rubrique','13','afterUpdate','2016-09-12 14:53:01'),(85,1,'s_rubrique','13','update','2016-09-12 14:53:03'),(86,1,'s_rubrique','13','afterUpdate','2016-09-12 14:53:03'),(87,1,'s_rubrique','13','publishMV','2016-09-12 14:53:05'),(88,1,'s_para_type','3','insert','2016-09-12 16:09:15'),(89,1,'s_para_type','3','update','2016-09-12 16:09:15'),(90,1,'s_para_type','3','afterUpdate','2016-09-12 16:09:15'),(91,1,'s_para_type','3','update','2016-09-12 16:09:35'),(92,1,'s_para_type','3','afterUpdate','2016-09-12 16:09:35'),(93,1,'s_rubrique','4','duplicateMV','2016-09-12 16:10:19'),(94,1,'s_rubrique','14','insert','2016-09-12 16:10:19'),(95,1,'s_rubrique','14','update','2016-09-12 16:10:24'),(96,1,'s_rubrique','14','afterUpdate','2016-09-12 16:10:24'),(97,1,'s_paragraphe','3','insert','2016-09-12 16:13:09'),(98,1,'s_paragraphe','3','update','2016-09-12 16:13:09'),(99,1,'s_paragraphe','3','afterUpdate','2016-09-12 16:13:09'),(100,1,'s_rubrique','14','update','2016-09-12 16:13:12'),(101,1,'s_rubrique','14','afterUpdate','2016-09-12 16:13:12'),(102,1,'s_rubrique','14','update','2016-09-12 16:15:23'),(103,1,'s_rubrique','14','afterUpdate','2016-09-12 16:15:23'),(104,1,'s_rubrique','14','update','2016-09-12 16:17:03'),(105,1,'s_rubrique','14','afterUpdate','2016-09-12 16:17:03'),(106,1,'s_rubrique','14','update','2016-09-12 16:17:29'),(107,1,'s_rubrique','14','afterUpdate','2016-09-12 16:17:29'),(108,1,'s_rubrique','14','update','2016-09-12 16:19:50'),(109,1,'s_rubrique','14','afterUpdate','2016-09-12 16:19:50'),(110,1,'s_rubrique','14','update','2016-09-12 16:21:34'),(111,1,'s_rubrique','14','afterUpdate','2016-09-12 16:21:34'),(112,1,'s_para_type','3','update','2016-09-12 16:26:31'),(113,1,'s_para_type','3','afterUpdate','2016-09-12 16:26:31'),(114,1,'s_paragraphe','3','update','2016-09-12 16:26:44'),(115,1,'s_paragraphe','3','afterUpdate','2016-09-12 16:26:44'),(116,1,'s_rubrique','14','update','2016-09-12 16:26:46'),(117,1,'s_rubrique','14','afterUpdate','2016-09-12 16:26:46'),(118,1,'s_para_type','3','update','2016-09-12 16:27:12'),(119,1,'s_para_type','3','afterUpdate','2016-09-12 16:27:12'),(120,1,'s_para_type','3','update','2016-09-12 16:28:45'),(121,1,'s_para_type','3','afterUpdate','2016-09-12 16:28:45'),(122,1,'s_plugin','o_paragraphs','insert','2016-09-12 16:29:19'),(123,1,'s_plugin','o_paragraphs','update','2016-09-12 16:29:19'),(124,1,'s_plugin','o_paragraphs','afterUpdate','2016-09-12 16:29:19'),(125,1,'s_plugin','o_paragraphs','InstallPlugin','2016-09-12 16:29:21'),(126,1,'s_rubrique','14','update','2016-09-13 08:24:32'),(127,1,'s_rubrique','14','afterUpdate','2016-09-13 08:24:32'),(128,1,'s_paragraphe','4','insert','2016-09-13 08:24:57'),(129,1,'s_paragraphe','4','update','2016-09-13 08:24:57'),(130,1,'s_paragraphe','4','afterUpdate','2016-09-13 08:24:57'),(131,1,'s_paragraphe','4','update','2016-09-13 08:24:59'),(132,1,'s_paragraphe','4','afterUpdate','2016-09-13 08:24:59'),(133,1,'s_rubrique','14','update','2016-09-13 08:25:01'),(134,1,'s_rubrique','14','afterUpdate','2016-09-13 08:25:01'),(135,1,'s_rubrique','14','publishMV','2016-09-13 08:25:03'),(136,1,'s_paragraphe','5','insert','2016-09-13 08:25:03'),(137,1,'s_para_type','3','update','2016-09-13 09:03:48'),(138,1,'s_para_type','3','afterUpdate','2016-09-13 09:03:48'),(139,1,'s_paragraphe_image','1','insert','2016-09-13 10:06:31'),(140,1,'s_paragraphe_image','2','insert','2016-09-13 10:06:49'),(141,1,'s_paragraphe_image','3','insert','2016-09-13 10:06:57'),(142,1,'s_paragraphe_image','4','insert','2016-09-13 10:07:04'),(143,1,'s_paragraphe','5','update','2016-09-13 10:07:21'),(144,1,'s_paragraphe','5','afterUpdate','2016-09-13 10:07:21'),(145,1,'s_paragraphe','5','update','2016-09-13 10:14:01'),(146,1,'s_paragraphe','5','afterUpdate','2016-09-13 10:14:01'),(147,1,'s_rubrique','14','update','2016-09-13 10:18:03'),(148,1,'s_rubrique','14','afterUpdate','2016-09-13 10:18:03'),(149,1,'s_paragraphe','4','update','2016-09-13 10:18:05'),(150,1,'s_paragraphe','4','afterUpdate','2016-09-13 10:18:05'),(151,1,'s_rubrique','14','update','2016-09-13 10:18:06'),(152,1,'s_rubrique','14','afterUpdate','2016-09-13 10:18:06'),(153,1,'s_rubrique','14','update','2016-09-13 10:18:20'),(154,1,'s_rubrique','14','afterUpdate','2016-09-13 10:18:20'),(155,1,'s_rubrique','14','update','2016-09-13 10:18:31'),(156,1,'s_rubrique','14','afterUpdate','2016-09-13 10:18:31'),(157,1,'s_paragraphe_image','5','insert','2016-09-13 10:18:35'),(158,1,'s_rubrique','14','update','2016-09-13 10:18:40'),(159,1,'s_rubrique','14','afterUpdate','2016-09-13 10:18:40'),(160,1,'s_rubrique','14','update','2016-09-13 10:19:11'),(161,1,'s_rubrique','14','afterUpdate','2016-09-13 10:19:11'),(162,1,'s_rubrique','14','update','2016-09-13 10:20:24'),(163,1,'s_rubrique','14','afterUpdate','2016-09-13 10:20:24'),(164,1,'s_rubrique','14','update','2016-09-13 10:20:34'),(165,1,'s_rubrique','14','afterUpdate','2016-09-13 10:20:34'),(166,1,'s_rubrique','14','update','2016-09-13 10:22:17'),(167,1,'s_rubrique','14','afterUpdate','2016-09-13 10:22:17'),(168,1,'s_paragraphe','7','insert','2016-09-13 10:22:22'),(169,1,'s_paragraphe','7','update','2016-09-13 10:22:22'),(170,1,'s_paragraphe','7','afterUpdate','2016-09-13 10:22:22'),(171,1,'s_paragraphe_image','6','insert','2016-09-13 10:22:22'),(172,1,'s_paragraphe_image','7','insert','2016-09-13 10:22:28'),(173,1,'s_paragraphe_image','8','insert','2016-09-13 10:22:37'),(174,1,'s_paragraphe_image','9','insert','2016-09-13 10:22:45'),(175,1,'s_paragraphe','7','update','2016-09-13 10:23:05'),(176,1,'s_paragraphe','7','afterUpdate','2016-09-13 10:23:05'),(177,1,'s_rubrique','14','update','2016-09-13 10:23:09'),(178,1,'s_rubrique','14','afterUpdate','2016-09-13 10:23:09'),(179,1,'s_rubrique','14','update','2016-09-13 10:23:38'),(180,1,'s_rubrique','14','afterUpdate','2016-09-13 10:23:38'),(181,1,'s_paragraphe_image','10','insert','2016-09-13 10:23:42'),(182,1,'s_paragraphe','7','update','2016-09-13 10:23:44'),(183,1,'s_paragraphe','7','afterUpdate','2016-09-13 10:23:44'),(184,1,'s_rubrique','14','update','2016-09-13 10:23:47'),(185,1,'s_rubrique','14','afterUpdate','2016-09-13 10:23:47'),(186,1,'s_rubrique','14','update','2016-09-13 10:37:15'),(187,1,'s_rubrique','14','afterUpdate','2016-09-13 10:37:15'),(188,1,'s_rubrique','14','update','2016-09-13 10:38:07'),(189,1,'s_rubrique','14','afterUpdate','2016-09-13 10:38:07'),(190,1,'s_paragraphe','7','update','2016-09-13 10:38:12'),(191,1,'s_paragraphe','7','afterUpdate','2016-09-13 10:38:12'),(192,1,'s_rubrique','14','update','2016-09-13 10:38:14'),(193,1,'s_rubrique','14','afterUpdate','2016-09-13 10:38:14'),(194,1,'s_paragraphe_image','11','insert','2016-09-13 10:38:18'),(195,1,'s_paragraphe_image','12','insert','2016-09-13 10:38:32'),(196,1,'s_paragraphe','7','update','2016-09-13 10:38:38'),(197,1,'s_paragraphe','7','afterUpdate','2016-09-13 10:38:38'),(198,1,'s_rubrique','14','update','2016-09-13 10:38:41'),(199,1,'s_rubrique','14','afterUpdate','2016-09-13 10:38:41'),(200,1,'s_paragraphe','7','update','2016-09-13 10:38:54'),(201,1,'s_paragraphe','7','afterUpdate','2016-09-13 10:38:54'),(202,1,'s_rubrique','14','update','2016-09-13 10:39:03'),(203,1,'s_rubrique','14','afterUpdate','2016-09-13 10:39:03'),(204,1,'s_paragraphe_image','13','insert','2016-09-13 10:39:28'),(205,1,'s_paragraphe','7','update','2016-09-13 10:40:05'),(206,1,'s_paragraphe','7','afterUpdate','2016-09-13 10:40:05'),(207,1,'s_rubrique','14','update','2016-09-13 10:43:55'),(208,1,'s_rubrique','14','afterUpdate','2016-09-13 10:43:55'),(209,1,'s_rubrique','14','update','2016-09-13 10:47:47'),(210,1,'s_rubrique','14','afterUpdate','2016-09-13 10:47:47'),(211,1,'s_rubrique','14','update','2016-09-13 10:52:25'),(212,1,'s_rubrique','14','afterUpdate','2016-09-13 10:52:25'),(213,1,'s_paragraphe_image','14','insert','2016-09-13 10:52:53'),(214,1,'s_rubrique','14','update','2016-09-13 11:16:07'),(215,1,'s_rubrique','14','afterUpdate','2016-09-13 11:16:07'),(216,1,'s_paragraphe_image','15','insert','2016-09-13 11:16:29'),(217,1,'s_paragraphe_image','15','beforeDelete','2016-09-13 11:18:18'),(218,1,'s_paragraphe_image','','delete','2016-09-13 11:18:18'),(219,1,'s_rubrique','14','update','2016-09-13 12:19:08'),(220,1,'s_rubrique','14','afterUpdate','2016-09-13 12:19:08'),(221,1,'s_paragraphe_image','14','beforeDelete','2016-09-13 12:19:13'),(222,1,'s_paragraphe_image','','delete','2016-09-13 12:19:13'),(223,1,'s_paragraphe_image','16','insert','2016-09-13 12:19:36'),(224,1,'s_paragraphe_image','16','beforeDelete','2016-09-13 12:19:44'),(225,1,'s_paragraphe_image','','delete','2016-09-13 12:19:44'),(226,1,'s_paragraphe','7','update','2016-09-13 12:31:14'),(227,1,'s_paragraphe','7','afterUpdate','2016-09-13 12:31:14'),(228,1,'s_rubrique','14','update','2016-09-13 12:31:16'),(229,1,'s_rubrique','14','afterUpdate','2016-09-13 12:31:16'),(230,1,'s_rubrique','14','publishMV','2016-09-13 12:31:18'),(231,1,'s_paragraphe','5','beforeDelete','2016-09-13 12:31:19'),(232,1,'s_paragraphe_image','','delete','2016-09-13 12:31:19'),(233,1,'s_paragraphe','8','insert','2016-09-13 12:31:19'),(234,1,'s_rubrique','5','duplicateMV','2016-09-13 14:02:35'),(235,1,'s_rubrique','15','insert','2016-09-13 14:02:35'),(236,1,'s_rubrique','15','update','2016-09-13 14:02:36'),(237,1,'s_rubrique','15','afterUpdate','2016-09-13 14:02:36'),(238,1,'s_paragraphe','11','insert','2016-09-13 14:02:40'),(239,1,'s_paragraphe','11','update','2016-09-13 14:02:40'),(240,1,'s_paragraphe','11','afterUpdate','2016-09-13 14:02:40'),(241,1,'s_paragraphe_image','23','insert','2016-09-13 14:02:41'),(242,1,'s_paragraphe_image','24','insert','2016-09-13 14:02:47'),(243,1,'s_paragraphe','11','update','2016-09-13 14:02:54'),(244,1,'s_paragraphe','11','afterUpdate','2016-09-13 14:02:54'),(245,1,'s_rubrique','15','update','2016-09-13 14:02:55'),(246,1,'s_rubrique','15','afterUpdate','2016-09-13 14:02:55'),(247,1,'s_rubrique','15','publishMV','2016-09-13 14:02:56'),(248,1,'s_paragraphe','12','insert','2016-09-13 14:02:56'),(249,1,'s_rubrique','15','update','2016-09-13 14:09:19'),(250,1,'s_rubrique','15','afterUpdate','2016-09-13 14:09:19'),(251,1,'s_rubrique','15','update','2016-09-13 14:09:22'),(252,1,'s_rubrique','15','afterUpdate','2016-09-13 14:09:22'),(253,1,'s_paragraphe_image','27','insert','2016-09-13 14:09:25'),(254,1,'s_paragraphe','11','update','2016-09-13 14:09:35'),(255,1,'s_paragraphe','11','afterUpdate','2016-09-13 14:09:35'),(256,1,'s_rubrique','15','update','2016-09-13 14:09:38'),(257,1,'s_rubrique','15','afterUpdate','2016-09-13 14:09:38'),(258,1,'s_rubrique','15','publishMV','2016-09-13 14:09:39'),(259,1,'s_paragraphe','12','beforeDelete','2016-09-13 14:09:39'),(260,1,'s_paragraphe_image','','delete','2016-09-13 14:09:39'),(261,1,'s_paragraphe','13','insert','2016-09-13 14:09:39'),(262,1,'s_gabarit','2','insert','2016-09-14 14:47:10'),(263,1,'s_gabarit','2','update','2016-09-14 14:47:10'),(264,1,'s_gabarit','2','afterUpdate','2016-09-14 14:47:10'),(265,1,'s_rubrique','16','insert','2016-09-14 14:47:37'),(266,1,'s_rubrique','16','update','2016-09-14 14:47:37'),(267,1,'s_rubrique','16','afterUpdate','2016-09-14 14:47:37'),(268,1,'s_rubrique','16','update','2016-09-14 14:47:42'),(269,1,'s_rubrique','16','afterUpdate','2016-09-14 14:47:42'),(270,1,'s_rubrique','16','update','2016-09-14 14:47:44'),(271,1,'s_rubrique','16','afterUpdate','2016-09-14 14:47:44'),(272,1,'s_rubrique','16','publishMV','2016-09-14 14:47:46'),(273,1,'s_rubrique','17','insert','2016-09-14 14:47:56'),(274,1,'s_rubrique','17','update','2016-09-14 14:47:56'),(275,1,'s_rubrique','17','afterUpdate','2016-09-14 14:47:56'),(276,1,'s_rubrique','17','update','2016-09-14 14:48:00'),(277,1,'s_rubrique','17','afterUpdate','2016-09-14 14:48:00'),(278,1,'s_rubrique','17','update','2016-09-14 14:48:01'),(279,1,'s_rubrique','17','afterUpdate','2016-09-14 14:48:01'),(280,1,'s_rubrique','17','publishMV','2016-09-14 14:48:03'),(281,0,'p_reponse','2','insert','2016-09-16 16:17:07'),(282,0,'p_reponse','3','insert','2016-09-16 16:20:14'),(283,0,'p_reponse','4','insert','2016-09-16 16:20:54'),(284,0,'p_reponse','5','insert','2016-09-16 16:21:02'),(285,0,'p_reponse','6','insert','2016-09-19 09:15:52'),(286,0,'p_reponse','7','insert','2016-09-19 09:15:59'),(287,0,'p_reponse','8','insert','2016-09-19 09:16:01'),(288,0,'','','transcodedReceived','2016-09-19 09:41:38'),(289,0,'','','transcodedReceived','2016-09-19 14:15:56'),(290,0,'','','transcodedBadPayload','2016-09-19 14:15:56'),(291,0,'','','transcodedReceived','2016-09-19 14:22:41'),(292,0,'','','transcodedBadPayload','2016-09-19 14:22:41'),(293,0,'','','transcodedReceived','2016-09-19 14:24:47'),(294,0,'','','transcodedBadPayload','2016-09-19 14:24:47'),(295,0,'','','transcodedReceived','2016-09-19 14:25:43'),(296,0,'','','transcodedMissingData','2016-09-19 14:25:43'),(297,0,'','','transcodedReceived','2016-09-19 14:27:56'),(298,0,'','','transcodedMissingData','2016-09-19 14:27:56'),(299,0,'','','transcodedReceived','2016-09-19 14:28:22'),(300,0,'p_reponse','9','insert','2016-09-19 14:28:22'),(301,0,'','','transcodedReceived','2016-09-19 14:33:46'),(302,0,'','','transcodedBadPayload','2016-09-19 14:33:46'),(303,0,'','','transcodedReceived','2016-09-19 14:37:19'),(304,0,'','','transcodedMissingData','2016-09-19 14:37:19'),(305,0,'','','transcodedReceived','2016-09-19 14:37:33'),(306,0,'p_reponse','10','insert','2016-09-19 14:37:33'),(307,1,'p_reponse','10','del','2016-09-19 14:38:16'),(308,1,'p_reponse','10','beforeDelete','2016-09-19 14:38:16'),(309,1,'p_reponse','','delete','2016-09-19 14:38:16'),(310,1,'p_reponse','9','del','2016-09-19 14:38:18'),(311,1,'p_reponse','9','beforeDelete','2016-09-19 14:38:18'),(312,1,'p_reponse','','delete','2016-09-19 14:38:18'),(313,0,'','','transcodedReceived','2016-09-19 15:23:54'),(314,0,'','','transcodedBadPayload','2016-09-19 15:23:54'),(315,0,'','','transcodedReceived','2016-09-19 15:25:07'),(316,0,'p_reponse','11','insert','2016-09-19 15:25:07'),(317,0,'','','transcodedReceived','2016-09-19 15:33:59'),(318,0,'','','transcodedBadPayload','2016-09-19 15:33:59'),(319,0,'','','transcodedReceived','2016-09-19 15:37:32'),(320,0,'','','transcodedBadPayload','2016-09-19 15:37:32'),(321,0,'','','transcodedReceived','2016-09-19 15:41:49'),(322,0,'','','transcodedBadPayload','2016-09-19 15:41:49'),(323,0,'','','transcodedReceived','2016-09-19 15:46:43'),(324,0,'','','transcodedBadPayload','2016-09-19 15:46:43'),(325,0,'','','transcodedReceived','2016-09-19 16:00:24'),(326,0,'','','transcodedMissingData','2016-09-19 16:00:24'),(327,0,'','','transcodedReceived','2016-09-19 16:03:27'),(328,0,'','','transcodedMissingPayload','2016-09-19 16:03:27'),(329,0,'','','transcodedReceived','2016-09-19 16:04:39'),(330,0,'','','transcodedBadPayload','2016-09-19 16:04:39'),(331,0,'','','transcodedReceived','2016-09-19 16:06:48'),(332,0,'','','transcodedBadPayload','2016-09-19 16:06:48'),(333,0,'','','transcodedReceived','2016-09-19 16:08:44'),(334,0,'','','transcodedBadPayload','2016-09-19 16:08:44'),(335,0,'','','transcodedReceived','2016-09-19 16:09:43'),(336,0,'','','transcodedBadPayload','2016-09-19 16:09:43'),(337,0,'','','transcodedReceived','2016-09-19 16:13:01'),(338,0,'p_reponse','12','insert','2016-09-19 16:13:01'),(339,0,'','','transcodedReceived','2016-09-19 16:20:09'),(340,0,'p_reponse','13','insert','2016-09-19 16:20:09'),(341,0,'','','transcodedReceived','2016-09-20 09:13:17'),(342,0,'p_reponse','14','insert','2016-09-20 09:13:17'),(343,0,'','','transcodedReceived','2016-09-20 16:12:58'),(344,0,'p_reponse','15','insert','2016-09-20 16:12:58'),(345,1,'p_reponse','11','del','2016-09-20 16:17:17'),(346,1,'p_reponse','11','beforeDelete','2016-09-20 16:17:17'),(347,1,'p_reponse','','delete','2016-09-20 16:17:17'),(348,0,'','','transcodedReceived','2016-09-20 16:28:08'),(349,0,'p_reponse','16','insert','2016-09-20 16:28:08'),(350,0,'','','transcodedReceived','2016-09-22 09:32:44'),(351,0,'','','transcodedMissingData','2016-09-22 09:32:44'),(352,0,'','','transcodedReceived','2016-09-22 09:46:40'),(353,0,'','','transcodedMissingData','2016-09-22 09:46:40'),(354,0,'','','transcodedReceived','2016-09-22 09:50:14'),(355,0,'','','transcodedBadPayload','2016-09-22 09:50:14'),(356,0,'','','transcodedReceived','2016-09-22 10:01:52'),(357,0,'p_reponse','17','insert','2016-09-22 10:01:52'),(358,0,'p_question','3','insert','2016-09-22 10:39:00'),(359,1,'s_rubrique','15','update','2016-09-23 09:29:32'),(360,1,'s_rubrique','15','afterUpdate','2016-09-23 09:29:32'),(361,1,'s_rubrique','15','update','2016-09-23 09:29:36'),(362,1,'s_rubrique','15','afterUpdate','2016-09-23 09:29:36'),(363,1,'s_rubrique','15','update','2016-09-23 09:33:23'),(364,1,'s_rubrique','15','afterUpdate','2016-09-23 09:33:23'),(365,1,'s_rubrique','15','publishMV','2016-09-23 09:33:26'),(366,1,'s_paragraphe','13','beforeDelete','2016-09-23 09:33:27'),(367,1,'s_paragraphe_image','','delete','2016-09-23 09:33:27'),(368,1,'s_rubrique','15','publishMV','2016-09-23 09:34:00'),(369,1,'s_rubrique','15','publishMV','2016-09-23 09:38:49'),(370,1,'s_rubrique','15','publishMV','2016-09-23 09:40:12'),(371,1,'s_plugin','ocms_search','insert','2016-09-23 10:51:24'),(372,1,'s_plugin','ocms_search','update','2016-09-23 10:51:24'),(373,1,'s_plugin','ocms_search','afterUpdate','2016-09-23 10:51:24'),(374,1,'s_plugin','ocms_search','InstallPlugin','2016-09-23 10:51:27'),(375,1,'p_internaute','6','update','2016-09-23 13:00:31'),(376,1,'p_internaute','6','afterUpdate','2016-09-23 13:00:31'),(377,1,'p_internaute','6','update','2016-09-23 13:00:44'),(378,1,'p_internaute','6','afterUpdate','2016-09-23 13:00:44'),(379,1,'p_reponse','12','update','2016-09-23 13:00:55'),(380,1,'p_reponse','12','afterUpdate','2016-09-23 13:00:55'),(381,1,'p_reponse','12','update','2016-09-23 13:01:08'),(382,1,'p_reponse','12','afterUpdate','2016-09-23 13:01:08'),(383,1,'p_internaute','4','update','2016-09-23 13:01:21'),(384,1,'p_internaute','4','afterUpdate','2016-09-23 13:01:21'),(385,1,'p_reponse','12','update','2016-09-23 13:01:32'),(386,1,'p_reponse','12','afterUpdate','2016-09-23 13:01:32'),(387,1,'p_reponse','12','showObject','2016-09-23 13:01:38'),(388,1,'p_reponse','13','showObject','2016-09-23 13:02:48'),(389,1,'p_reponse','15','showObject','2016-09-23 13:02:49'),(390,1,'p_reponse','14','showObject','2016-09-23 13:02:53'),(391,1,'p_reponse','16','showObject','2016-09-23 13:02:54'),(392,1,'p_reponse','17','del','2016-09-23 13:02:58'),(393,1,'p_reponse','17','beforeDelete','2016-09-23 13:02:58'),(394,1,'p_reponse','','delete','2016-09-23 13:02:58'),(395,0,'','','transcodedReceived','2016-09-23 13:05:31'),(396,0,'p_reponse','18','insert','2016-09-23 13:05:31'),(397,1,'p_reponse','18','showObject','2016-09-23 13:05:54'),(398,1,'p_reponse','18','update','2016-09-23 13:06:47'),(399,1,'p_reponse','18','afterUpdate','2016-09-23 13:06:47'),(400,1,'p_reponse','18','hideObject','2016-09-23 13:07:13'),(401,1,'p_reponse','18','showObject','2016-09-23 13:07:15'),(402,1,'p_reponse','18','hideObject','2016-09-23 13:07:29'),(403,1,'p_reponse','18','showObject','2016-09-23 13:08:16'),(404,1,'p_reponse','18','hideObject','2016-09-23 13:08:18'),(405,1,'p_reponse','18','showObject','2016-09-23 13:08:32'),(406,1,'p_reponse','18','hideObject','2016-09-23 13:08:58'),(407,1,'p_reponse','18','showObject','2016-09-23 13:09:05'),(408,1,'p_reponse','18','hideObject','2016-09-23 13:09:08'),(409,1,'p_reponse','18','showObject','2016-09-23 13:28:40'),(410,1,'p_reponse','18','hideObject','2016-09-23 13:28:43'),(411,1,'p_reponse','18','showObject','2016-09-23 13:28:49'),(412,1,'p_reponse','18','update','2016-09-23 13:29:06'),(413,1,'p_reponse','18','afterUpdate','2016-09-23 13:29:06'),(414,1,'p_reponse','18','hideObject','2016-09-23 13:29:08'),(415,1,'p_reponse','18','showObject','2016-09-23 13:29:09'),(416,1,'p_internaute','4','update','2016-09-23 13:42:47'),(417,1,'p_internaute','4','afterUpdate','2016-09-23 13:42:47'),(418,0,'','','transcodedReceived','2016-09-23 14:41:39'),(419,0,'p_reponse','19','insert','2016-09-23 14:41:39'),(420,1,'p_reponse','19','showObject','2016-09-23 14:42:21'),(421,1,'s_rubrique','15','update','2016-09-23 16:04:26'),(422,1,'s_rubrique','15','afterUpdate','2016-09-23 16:04:26'),(423,1,'s_rubrique','15','publishMV','2016-09-23 16:04:28'),(424,1,'s_rubrique','2','update','2016-09-29 07:41:40'),(425,1,'s_rubrique','2','afterUpdate','2016-09-29 07:41:40'),(426,1,'s_paragraphe','1','update','2016-09-29 07:44:51'),(427,1,'s_paragraphe','1','afterUpdate','2016-09-29 07:44:51'),(428,1,'s_rubrique','2','update','2016-09-29 07:44:53'),(429,1,'s_rubrique','2','afterUpdate','2016-09-29 07:44:53'),(430,1,'s_rubrique','2','update','2016-09-29 07:55:30'),(431,1,'s_rubrique','2','afterUpdate','2016-09-29 07:55:30'),(432,1,'s_paragraphe_image','28','insert','2016-09-29 07:55:50'),(433,1,'s_paragraphe_image','28','beforeDelete','2016-09-29 07:56:50'),(434,1,'s_paragraphe_image','','delete','2016-09-29 07:56:50'),(435,1,'s_rubrique','2','update','2016-09-29 07:59:57'),(436,1,'s_rubrique','2','afterUpdate','2016-09-29 07:59:57'),(437,1,'s_paragraphe_image','29','insert','2016-09-29 07:59:59'),(438,1,'s_paragraphe_image','29','beforeDelete','2016-09-29 08:01:17'),(439,1,'s_paragraphe_image','','delete','2016-09-29 08:01:17'),(440,1,'s_paragraphe_image','30','insert','2016-09-29 08:01:17'),(441,1,'s_paragraphe_image','31','insert','2016-09-29 08:01:59'),(442,1,'s_paragraphe_image','32','insert','2016-09-29 08:02:17'),(443,1,'s_paragraphe','1','update','2016-09-29 08:02:51'),(444,1,'s_paragraphe','1','afterUpdate','2016-09-29 08:02:51'),(445,1,'s_rubrique','2','update','2016-09-29 08:02:53'),(446,1,'s_rubrique','2','afterUpdate','2016-09-29 08:02:53'),(447,1,'s_paragraphe','1','update','2016-09-29 08:03:31'),(448,1,'s_paragraphe','1','afterUpdate','2016-09-29 08:03:31'),(449,1,'s_rubrique','2','update','2016-09-29 08:03:33'),(450,1,'s_rubrique','2','afterUpdate','2016-09-29 08:03:33'),(451,1,'s_paragraphe','11','insert','2016-09-29 08:03:58'),(452,1,'s_paragraphe','11','update','2016-09-29 08:03:58'),(453,1,'s_paragraphe','11','afterUpdate','2016-09-29 08:03:58'),(454,1,'s_rubrique','2','update','2016-09-29 08:04:01'),(455,1,'s_rubrique','2','afterUpdate','2016-09-29 08:04:01'),(456,1,'s_paragraphe','12','insert','2016-09-29 08:04:44'),(457,1,'s_paragraphe','12','update','2016-09-29 08:04:44'),(458,1,'s_paragraphe','12','afterUpdate','2016-09-29 08:04:44'),(459,1,'s_rubrique','2','update','2016-09-29 08:04:53'),(460,1,'s_rubrique','2','afterUpdate','2016-09-29 08:04:53'),(461,1,'s_rubrique','2','publishMV','2016-09-29 08:04:54'),(462,1,'s_paragraphe','2','beforeDelete','2016-09-29 08:04:54'),(463,1,'s_paragraphe','','delete','2016-09-29 08:04:54'),(464,1,'s_paragraphe','13','insert','2016-09-29 08:04:54'),(465,1,'s_rubrique','2','update','2016-09-29 08:18:22'),(466,1,'s_rubrique','2','afterUpdate','2016-09-29 08:18:22'),(467,1,'s_rubrique','2','update','2016-09-29 08:18:22'),(468,1,'s_rubrique','2','afterUpdate','2016-09-29 08:18:22'),(469,1,'s_rubrique','2','publishMV','2016-09-29 08:18:24'),(470,1,'s_paragraphe','13','beforeDelete','2016-09-29 08:18:24'),(471,1,'s_paragraphe_image','','delete','2016-09-29 08:18:24'),(472,1,'s_paragraphe','16','insert','2016-09-29 08:18:24'),(473,1,'s_rubrique','2','update','2016-09-29 08:18:32'),(474,1,'s_rubrique','2','afterUpdate','2016-09-29 08:18:32'),(475,1,'s_rubrique','2','publishMV','2016-09-29 08:18:34'),(476,1,'s_paragraphe','16','beforeDelete','2016-09-29 08:18:34'),(477,1,'s_paragraphe_image','','delete','2016-09-29 08:18:34'),(478,1,'s_paragraphe','19','insert','2016-09-29 08:18:34'),(479,1,'s_rubrique','2','update','2016-09-29 09:51:15'),(480,1,'s_rubrique','2','afterUpdate','2016-09-29 09:51:15'),(481,1,'s_rubrique','2','update','2016-09-29 09:52:21'),(482,1,'s_rubrique','2','afterUpdate','2016-09-29 09:52:21'),(483,1,'s_paragraphe','12','update','2016-09-29 09:52:36'),(484,1,'s_paragraphe','12','afterUpdate','2016-09-29 09:52:36'),(485,1,'s_rubrique','2','update','2016-09-29 09:52:38'),(486,1,'s_rubrique','2','afterUpdate','2016-09-29 09:52:38'),(487,1,'s_rubrique','2','publishMV','2016-09-29 09:52:40'),(488,1,'s_paragraphe','19','beforeDelete','2016-09-29 09:52:40'),(489,1,'s_paragraphe_image','','delete','2016-09-29 09:52:40'),(490,1,'s_paragraphe','22','insert','2016-09-29 09:52:40'),(491,1,'s_rubrique','2','update','2016-09-29 09:52:54'),(492,1,'s_rubrique','2','afterUpdate','2016-09-29 09:52:54'),(493,1,'s_paragraphe','12','update','2016-09-29 09:52:59'),(494,1,'s_paragraphe','12','afterUpdate','2016-09-29 09:52:59'),(495,1,'s_rubrique','2','update','2016-09-29 09:53:22'),(496,1,'s_rubrique','2','afterUpdate','2016-09-29 09:53:22'),(497,1,'s_paragraphe','12','update','2016-09-29 09:53:37'),(498,1,'s_paragraphe','12','afterUpdate','2016-09-29 09:53:37'),(499,1,'s_rubrique','2','update','2016-09-29 09:54:11'),(500,1,'s_rubrique','2','afterUpdate','2016-09-29 09:54:11'),(501,1,'s_rubrique','2','update','2016-09-29 09:54:58'),(502,1,'s_rubrique','2','afterUpdate','2016-09-29 09:54:58'),(503,1,'s_paragraphe','12','update','2016-09-29 09:55:04'),(504,1,'s_paragraphe','12','afterUpdate','2016-09-29 09:55:04'),(505,1,'s_rubrique','2','update','2016-09-29 09:55:05'),(506,1,'s_rubrique','2','afterUpdate','2016-09-29 09:55:05'),(507,1,'s_rubrique','2','publishMV','2016-09-29 09:55:07'),(508,1,'s_paragraphe','22','beforeDelete','2016-09-29 09:55:07'),(509,1,'s_paragraphe_image','','delete','2016-09-29 09:55:07'),(510,1,'s_paragraphe','25','insert','2016-09-29 09:55:07'),(511,1,'s_rubrique','2','update','2016-09-29 10:17:32'),(512,1,'s_rubrique','2','afterUpdate','2016-09-29 10:17:32'),(513,1,'s_paragraphe','1','update','2016-09-29 10:18:12'),(514,1,'s_paragraphe','1','afterUpdate','2016-09-29 10:18:12'),(515,1,'s_rubrique','2','update','2016-09-29 10:18:14'),(516,1,'s_rubrique','2','afterUpdate','2016-09-29 10:18:14'),(517,1,'s_rubrique','2','publishMV','2016-09-29 10:18:15'),(518,1,'s_paragraphe','25','beforeDelete','2016-09-29 10:18:15'),(519,1,'s_paragraphe_image','','delete','2016-09-29 10:18:15'),(520,1,'s_paragraphe','28','insert','2016-09-29 10:18:15'),(521,1,'s_rubrique','2','update','2016-09-29 14:02:24'),(522,1,'s_rubrique','2','afterUpdate','2016-09-29 14:02:24'),(523,1,'s_paragraphe_image','69','insert','2016-09-29 14:02:27'),(524,1,'s_paragraphe','1','update','2016-09-29 14:03:14'),(525,1,'s_paragraphe','1','afterUpdate','2016-09-29 14:03:14'),(526,1,'s_rubrique','2','update','2016-09-29 14:03:16'),(527,1,'s_rubrique','2','afterUpdate','2016-09-29 14:03:16'),(528,1,'s_rubrique','2','publishMV','2016-09-29 14:03:17'),(529,1,'s_paragraphe','28','beforeDelete','2016-09-29 14:03:17'),(530,1,'s_paragraphe_image','','delete','2016-09-29 14:03:17'),(531,1,'s_paragraphe','31','insert','2016-09-29 14:03:18'),(532,1,'s_param','next_cache_update','update','2016-10-03 08:18:48'),(533,1,'s_param','next_cache_update','afterUpdate','2016-10-03 08:18:48'),(534,1,'s_param','cache_path','update','2016-10-03 08:20:57'),(535,1,'s_param','cache_path','afterUpdate','2016-10-03 08:20:57'),(536,1,'s_rubrique','6','duplicateMV','2016-10-03 09:31:51'),(537,1,'s_rubrique','18','insert','2016-10-03 09:31:51'),(538,1,'s_rubrique','18','update','2016-10-03 09:31:55'),(539,1,'s_rubrique','18','afterUpdate','2016-10-03 09:31:55'),(540,1,'s_rubrique','18','update','2016-10-03 09:31:56'),(541,1,'s_rubrique','18','afterUpdate','2016-10-03 09:31:56'),(542,1,'s_rubrique','18','publishMV','2016-10-03 09:31:58'),(543,1,'s_rubrique','8','duplicateMV','2016-10-03 09:32:02'),(544,1,'s_rubrique','19','insert','2016-10-03 09:32:02'),(545,1,'s_rubrique','19','update','2016-10-03 09:32:05'),(546,1,'s_rubrique','19','afterUpdate','2016-10-03 09:32:05'),(547,1,'s_rubrique','19','update','2016-10-03 09:32:06'),(548,1,'s_rubrique','19','afterUpdate','2016-10-03 09:32:06'),(549,1,'s_rubrique','19','publishMV','2016-10-03 09:32:07'),(550,1,'p_article','20','insert','2016-10-03 09:40:19'),(551,1,'s_rubrique','20','update','2016-10-03 09:40:19'),(552,1,'s_rubrique','20','afterUpdate','2016-10-03 09:40:19'),(553,1,'s_rubrique','20','update','2016-10-03 09:40:56'),(554,1,'s_rubrique','20','afterUpdate','2016-10-03 09:40:56'),(555,1,'s_paragraphe','34','insert','2016-10-03 09:41:24'),(556,1,'s_paragraphe','34','update','2016-10-03 09:41:24'),(557,1,'s_paragraphe','34','afterUpdate','2016-10-03 09:41:24'),(558,1,'s_paragraphe','34','update','2016-10-03 09:41:27'),(559,1,'s_paragraphe','34','afterUpdate','2016-10-03 09:41:27'),(560,1,'s_rubrique','20','update','2016-10-03 09:41:29'),(561,1,'s_rubrique','20','afterUpdate','2016-10-03 09:41:29'),(562,1,'s_rubrique','20','publishMV','2016-10-03 09:41:31'),(563,1,'s_rubrique','20','duplicateMV','2016-10-03 09:43:18'),(564,1,'s_rubrique','21','insert','2016-10-03 09:43:18'),(565,1,'s_rubrique','21','update','2016-10-03 09:43:24'),(566,1,'s_rubrique','21','afterUpdate','2016-10-03 09:43:24'),(567,1,'s_rubrique','21','publishMV','2016-10-03 09:43:26'),(568,1,'s_paragraphe','34','beforeDelete','2016-10-03 09:43:26'),(569,1,'s_paragraphe','','delete','2016-10-03 09:43:26'),(570,1,'s_paragraphe','36','insert','2016-10-03 09:43:26'),(571,1,'p_article','22','insert','2016-10-03 09:43:39'),(572,1,'s_rubrique','22','update','2016-10-03 09:43:39'),(573,1,'s_rubrique','22','afterUpdate','2016-10-03 09:43:39'),(574,1,'s_rubrique','22','update','2016-10-03 09:44:05'),(575,1,'s_rubrique','22','afterUpdate','2016-10-03 09:44:05'),(576,1,'s_rubrique','22','update','2016-10-03 09:44:27'),(577,1,'s_rubrique','22','afterUpdate','2016-10-03 09:44:27'),(578,1,'s_paragraphe','37','insert','2016-10-03 09:44:33'),(579,1,'s_paragraphe','37','update','2016-10-03 09:44:33'),(580,1,'s_paragraphe','37','afterUpdate','2016-10-03 09:44:33'),(581,1,'s_paragraphe_image','77','insert','2016-10-03 09:44:34'),(582,1,'s_paragraphe_image','78','insert','2016-10-03 09:44:38'),(583,1,'s_paragraphe_image','79','insert','2016-10-03 09:44:43'),(584,1,'s_paragraphe_image','80','insert','2016-10-03 09:44:51'),(585,1,'s_paragraphe','37','update','2016-10-03 09:44:58'),(586,1,'s_paragraphe','37','afterUpdate','2016-10-03 09:44:58'),(587,1,'s_rubrique','22','update','2016-10-03 09:45:00'),(588,1,'s_rubrique','22','afterUpdate','2016-10-03 09:45:00'),(589,1,'s_paragraphe_image','77','beforeDelete','2016-10-03 09:45:03'),(590,1,'s_paragraphe_image','','delete','2016-10-03 09:45:03'),(591,1,'s_paragraphe','37','update','2016-10-03 09:45:04'),(592,1,'s_paragraphe','37','afterUpdate','2016-10-03 09:45:04'),(593,1,'s_rubrique','22','update','2016-10-03 09:45:06'),(594,1,'s_rubrique','22','afterUpdate','2016-10-03 09:45:06'),(595,1,'s_rubrique','22','publishMV','2016-10-03 09:45:08'),(596,1,'s_rubrique','19','update','2016-10-03 12:36:41'),(597,1,'s_rubrique','19','afterUpdate','2016-10-03 12:36:41'),(598,1,'s_rubrique','19','publishMV','2016-10-03 12:36:43'),(599,1,'s_rubrique','18','update','2016-10-03 12:36:51'),(600,1,'s_rubrique','18','afterUpdate','2016-10-03 12:36:51'),(601,1,'s_rubrique','18','publishMV','2016-10-03 12:36:54'),(602,1,'s_rubrique','18','publishMV','2016-10-03 12:47:49'),(603,1,'p_article','23','insert','2016-10-05 15:58:19'),(604,1,'s_rubrique','23','update','2016-10-05 15:58:19'),(605,1,'s_rubrique','23','afterUpdate','2016-10-05 15:58:19'),(606,1,'s_rubrique','23','update','2016-10-05 15:58:22'),(607,1,'s_rubrique','23','afterUpdate','2016-10-05 15:58:22'),(608,1,'s_paragraphe','38','insert','2016-10-05 15:58:56'),(609,1,'s_paragraphe','38','update','2016-10-05 15:58:56'),(610,1,'s_paragraphe','38','afterUpdate','2016-10-05 15:58:56'),(611,1,'s_paragraphe','38','update','2016-10-05 15:59:15'),(612,1,'s_paragraphe','38','afterUpdate','2016-10-05 15:59:15'),(613,1,'s_rubrique','23','update','2016-10-05 15:59:44'),(614,1,'s_rubrique','23','afterUpdate','2016-10-05 15:59:44'),(615,1,'s_rubrique','23','publishMV','2016-10-05 15:59:46'),(616,1,'s_rubrique','23','duplicateMV','2016-10-05 16:00:35'),(617,1,'s_rubrique','24','insert','2016-10-05 16:00:35'),(618,1,'s_rubrique','24','update','2016-10-05 16:00:43'),(619,1,'s_rubrique','24','afterUpdate','2016-10-05 16:00:43'),(620,1,'s_rubrique','24','publishMV','2016-10-05 16:00:45'),(621,1,'s_paragraphe','38','beforeDelete','2016-10-05 16:00:45'),(622,1,'s_paragraphe','','delete','2016-10-05 16:00:45'),(623,1,'s_paragraphe','40','insert','2016-10-05 16:00:45'),(624,0,'','','transcodedReceived','2016-10-06 08:15:11'),(625,0,'p_reponse','20','insert','2016-10-06 08:15:12'),(626,1,'p_reponse','20','showObject','2016-10-06 08:16:29'),(627,0,'','','transcodedReceived','2016-10-06 08:17:07'),(628,0,'p_reponse','21','insert','2016-10-06 08:17:07'),(629,0,'','','transcodedReceived','2016-10-06 08:17:30'),(630,0,'p_reponse','22','insert','2016-10-06 08:17:30'),(631,0,'','','transcodedReceived','2016-10-06 08:52:00'),(632,0,'p_reponse','23','insert','2016-10-06 08:52:00'),(633,0,'p_reponse','','transcodedAndSaved','2016-10-06 08:52:04'),(634,1,'p_reponse','21','showObject','2016-10-06 08:53:16'),(635,1,'p_reponse','22','showObject','2016-10-06 08:53:19'),(636,1,'p_reponse','23','showObject','2016-10-06 08:53:22'),(637,0,'p_question','4','insert','2016-10-06 10:29:21'),(638,1,'p_question','4','showObject','2016-10-06 10:30:02'),(639,1,'p_question','3','del','2016-10-06 10:30:05'),(640,1,'p_question','3','beforeDelete','2016-10-06 10:30:05'),(641,1,'p_question','','delete','2016-10-06 10:30:05'),(642,0,'p_question','5','insert','2016-10-06 10:32:01'),(643,1,'s_trad','merci','insert','2016-10-06 10:32:47'),(644,1,'s_trad','merci','update','2016-10-06 10:32:48'),(645,1,'s_trad','merci','afterUpdate','2016-10-06 10:32:48'),(646,1,'s_trad','visionnez-temoignages','insert','2016-10-06 10:33:09'),(647,1,'s_trad','visionnez-temoignages','update','2016-10-06 10:33:09'),(648,1,'s_trad','visionnez-temoignages','afterUpdate','2016-10-06 10:33:09');
/*!40000 ALTER TABLE `s_log_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_para_type`
--

DROP TABLE IF EXISTS `s_para_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_para_type` (
  `para_type_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `para_type_titre` varchar(128) NOT NULL DEFAULT '',
  `para_type_template` text NOT NULL,
  `para_type_tpl_file` varchar(255) NOT NULL,
  `para_type_template_popup` text NOT NULL,
  `para_type_vignette` varchar(255) NOT NULL DEFAULT '',
  `para_type_use_img` tinyint(1) NOT NULL DEFAULT '0',
  `para_type_use_file` tinyint(1) NOT NULL DEFAULT '0',
  `para_type_use_table` tinyint(1) NOT NULL DEFAULT '0',
  `para_type_use_txt` tinyint(1) NOT NULL DEFAULT '1',
  `para_type_use_link` tinyint(1) NOT NULL DEFAULT '0',
  `para_type_gabarit` varchar(64) NOT NULL,
  `para_type_plugin` varchar(64) NOT NULL,
  `para_type_champs` text NOT NULL,
  PRIMARY KEY (`para_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_para_type`
--

LOCK TABLES `s_para_type` WRITE;
/*!40000 ALTER TABLE `s_para_type` DISABLE KEYS */;
INSERT INTO `s_para_type` VALUES (2,'Titre + image + texte','<div class=\"paraimg\">\r\n<h2>@@titre@@</h2>\r\n<div class=\"img\">##1##</div>\r\n<div class=\"texte\">@@texte@@</div>\r\n</div>\r\n\r\n','','','',1,0,0,1,0,'','',''),(3,'Code embed','','plugins/o_paragraphs/tpl/embed.php','','',0,0,0,1,0,'','','paragraphe_embed'),(4,'Diaporama','','plugins/o_paragraphs/tpl/diaporama.php','','',0,0,0,0,0,'','','IMAGES');
/*!40000 ALTER TABLE `s_para_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_paragraphe`
--

DROP TABLE IF EXISTS `s_paragraphe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_paragraphe` (
  `paragraphe_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fk_rubrique_id` int(10) unsigned DEFAULT NULL,
  `fk_para_type_id` smallint(5) unsigned DEFAULT NULL,
  `paragraphe_ordre` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `paragraphe_titre_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_contenu_fr` text NOT NULL,
  `paragraphe_contenu_csv_fr` text NOT NULL,
  `paragraphe_img_1_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_img_2_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_img_1_alt_fr` varchar(80) NOT NULL DEFAULT '',
  `paragraphe_img_2_alt_fr` varchar(80) NOT NULL DEFAULT '',
  `paragraphe_params_fr` varchar(255) DEFAULT NULL,
  `paragraphe_file_1_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_file_1_legend_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_link_1_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_img_1_copyright` varchar(80) NOT NULL DEFAULT '',
  `paragraphe_img_2_copyright` varchar(80) NOT NULL DEFAULT '',
  `fk_contact_id` int(11) NOT NULL,
  `paragraphe_embed_fr` text NOT NULL,
  PRIMARY KEY (`paragraphe_id`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `paragraphe_ordre` (`paragraphe_ordre`),
  KEY `fk_para_type_id` (`fk_para_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_paragraphe`
--

LOCK TABLES `s_paragraphe` WRITE;
/*!40000 ALTER TABLE `s_paragraphe` DISABLE KEYS */;
INSERT INTO `s_paragraphe` VALUES (1,2,4,1,'Diaporama','<p>Contenu du paragraphe</p>','','','','','',NULL,'','','','','',0,''),(3,14,3,1,'TEST','','','','','','',NULL,'','','','','',0,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/3wj4ncIEDxw\" frameborder=\"0\" allowfullscreen></iframe>'),(4,14,2,3,'Test','<p>sdf sdjkf sdjklf jsdqkl jdflqg df</p>\r\n<p>g </p>\r\n<p>sdfg </p>\r\n<p>sdfg</p>\r\n<p> sdf</p>\r\n<p>g </p>\r\n<p>sdfg </p>','','p7250004.jpg','','','',NULL,'','','','','',0,''),(7,14,4,2,'test','','','','','','',NULL,'','','','','',0,''),(8,4,3,1,'TEST','','','','','','',NULL,'','','','','',0,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/3wj4ncIEDxw\" frameborder=\"0\" allowfullscreen></iframe>'),(9,4,2,3,'Test','<p>sdf sdjkf sdjklf jsdqkl jdflqg df</p>\r\n<p>g </p>\r\n<p>sdfg </p>\r\n<p>sdfg</p>\r\n<p> sdf</p>\r\n<p>g </p>\r\n<p>sdfg </p>','','p7250004.jpg','','','',NULL,'','','','','',0,''),(10,4,4,2,'test','','','','','','',NULL,'','','','','',0,''),(11,2,2,2,'Les SES ont 50 ans','<p><strong>gdf </strong></p>\r\n<p><strong>gdfs</strong></p>\r\n<p><strong> gsdf</strong></p>\r\n<p><strong>g df</strong></p>\r\n<p><strong>g </strong></p>\r\n<p><strong>sdfg</strong></p>\r\n<p><strong> df</strong></p>\r\n<p> </p>','','','','','',NULL,'','','','','',0,''),(12,2,3,3,'Temoignez','<p>Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! </p>','','','','','',NULL,'','','','','',0,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/0daS_SDCT_U\" frameborder=\"0\" allowfullscreen></iframe>'),(31,1,4,1,'Diaporama','<p>Contenu du paragraphe</p>','','','','','',NULL,'','','','','',0,''),(32,1,2,2,'Les SES ont 50 ans','<p><strong>gdf </strong></p>\r\n<p><strong>gdfs</strong></p>\r\n<p><strong> gsdf</strong></p>\r\n<p><strong>g df</strong></p>\r\n<p><strong>g </strong></p>\r\n<p><strong>sdfg</strong></p>\r\n<p><strong> df</strong></p>\r\n<p> </p>','','','','','',NULL,'','','','','',0,''),(33,1,3,3,'Temoignez','<p>Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! Participez ! </p>','','','','','',NULL,'','','','','',0,'<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/0daS_SDCT_U\" frameborder=\"0\" allowfullscreen></iframe>'),(35,21,2,1,'Titre','<p>Contenu Contenu Contenu Contenu Contenu Contenu  Contenu  Contenu Contenu Contenu Contenu Contenu  Contenu Contenu Contenu Contenu Contenu Contenu Contenu   Contenu Contenu Contenu Contenu Contenu Contenu Contenu  Contenu Contenu Contenu  Contenu Contenu Contenu Contenu  Contenu Contenu Contenu Contenu Contenu Contenu Contenu Contenu Contenu  Contenu Contenu Contenu Contenu Contenu </p>','','img_20160709_121905.jpg','','','',NULL,'','','','','',0,''),(36,20,2,1,'Titre','<p>Contenu Contenu Contenu Contenu Contenu Contenu  Contenu  Contenu Contenu Contenu Contenu Contenu  Contenu Contenu Contenu Contenu Contenu Contenu Contenu   Contenu Contenu Contenu Contenu Contenu Contenu Contenu  Contenu Contenu Contenu  Contenu Contenu Contenu Contenu  Contenu Contenu Contenu Contenu Contenu Contenu Contenu Contenu Contenu  Contenu Contenu Contenu Contenu Contenu </p>','','img_20160709_121905.jpg','','','',NULL,'','','','','',0,''),(37,22,4,1,'Diapo !','','','','','','',NULL,'','','','','',0,''),(39,24,2,1,'Test','<p>Train indonésien sur Java</p>','','p7230108.jpg','','','',NULL,'','','','','',0,''),(40,23,2,1,'Test','<p>Train indonésien sur Java</p>','','p7230108.jpg','','','',NULL,'','','','','',0,'');
/*!40000 ALTER TABLE `s_paragraphe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_paragraphe_image`
--

DROP TABLE IF EXISTS `s_paragraphe_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_paragraphe_image` (
  `paragraphe_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paragraphe_image_img_fr` varchar(255) NOT NULL,
  `paragraphe_image_desc_fr` text NOT NULL,
  `paragraphe_image_ordre` int(11) NOT NULL,
  `fk_paragraphe_id` int(11) NOT NULL,
  `paragraphe_image_titre_fr` varchar(255) NOT NULL,
  `paragraphe_image_link_fr` varchar(255) NOT NULL,
  PRIMARY KEY (`paragraphe_image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_paragraphe_image`
--

LOCK TABLES `s_paragraphe_image` WRITE;
/*!40000 ALTER TABLE `s_paragraphe_image` DISABLE KEYS */;
INSERT INTO `s_paragraphe_image` VALUES (5,'','',0,4,'',''),(6,'dsc01375.jpg',' test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu ',1,7,'',''),(10,'dsc01242.jpg',' bcb',2,7,'',''),(11,'dsc01449.jpg',' dfgdfgdf',3,7,'',''),(12,'dsc01472.jpg',' larrrrge',4,7,'',''),(13,'dsc01276.jpg','ttttttttttttttttt',5,7,'',''),(17,'','',0,9,'',''),(18,'dsc01375.jpg',' test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu  test avec un long contenu ',1,10,'',''),(19,'dsc01242.jpg',' bcb',2,10,'',''),(20,'dsc01449.jpg',' dfgdfgdf',3,10,'',''),(21,'dsc01472.jpg',' larrrrge',4,10,'',''),(22,'dsc01276.jpg','ttttttttttttttttt',5,10,'',''),(23,'dsc01239.jpg',' XYZ',1,11,'',''),(24,'dsc01276.jpg',' TEZ',3,11,'',''),(27,'dsc01386.jpg',' fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f',2,11,'',''),(30,'dsc01239.jpg','Vu de l\'avion ...',2,1,'Fjord norvégiens',''),(31,'p7270369.jpg','Juste après le lever de soleil et une ascension de nuit sur lflanc d\'un auter volcan aux relents souffrés, le mont Bromo se dévoile.',1,1,'Volcan à Java',''),(32,'dsc02792.jpg',' Le long d\'une levada',3,1,'Madère',''),(69,'img_20160815_143619.jpg','Ruines d\'une église en Ardèche ',4,1,'Vestige éclésiastique',''),(70,'dsc01239.jpg','Vu de l\'avion ...',2,31,'Fjord norvégiens',''),(71,'p7270369.jpg','Juste après le lever de soleil et une ascension de nuit sur lflanc d\'un auter volcan aux relents souffrés, le mont Bromo se dévoile.',1,31,'Volcan à Java',''),(72,'dsc02792.jpg',' Le long d\'une levada',3,31,'Madère',''),(73,'img_20160815_143619.jpg','Ruines d\'une église en Ardèche ',4,31,'Vestige éclésiastique',''),(74,'dsc01239.jpg',' XYZ',1,32,'',''),(75,'dsc01276.jpg',' TEZ',3,32,'',''),(76,'dsc01386.jpg',' fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f fdsfds fsd fsd fd fsd fsd fsd fsd f',2,32,'',''),(78,'img_20160911_153116.jpg','',1,37,'',''),(79,'img_20160910_153616077.jpg','',2,37,'',''),(80,'img_20160808_132930733_hdr.jpg','',3,37,'','');
/*!40000 ALTER TABLE `s_paragraphe_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_param`
--

DROP TABLE IF EXISTS `s_param`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_param` (
  `param_id` varchar(64) NOT NULL DEFAULT '',
  `param_valeur` text NOT NULL,
  `param_description` text NOT NULL,
  `fk_plugin_id` varchar(64) NOT NULL,
  PRIMARY KEY (`param_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_param`
--

LOCK TABLES `s_param` WRITE;
/*!40000 ALTER TABLE `s_param` DISABLE KEYS */;
INSERT INTO `s_param` VALUES ('cache_path','cache','',''),('date_update_anything','1475749989','',''),('date_update_arbo','1475683245','',''),('date_update_plug_contact','1473691963','',''),('date_update_p_internaute','1474639066','',''),('date_update_p_reponse','1475744002','',''),('date_update_s_admin_trad','1414593006','',''),('date_update_s_bloc','1414591518','',''),('date_update_s_gabarit','1473864430','',''),('date_update_s_paragraphe','1475683155','',''),('date_update_s_param','1475482857','',''),('date_update_s_para_type','1473757428','',''),('date_update_s_plugin','1474627887','',''),('date_update_s_rubrique','1475683245','',''),('date_update_s_trad','1475749989','',''),('fake_folder_param','p_','',''),('mail_host','localhost','',''),('mail_type','mail','',''),('nb_rows_before_search','100','',''),('next_cache_update','','',''),('param_key_sep','/','',''),('param_val_sep','-','','');
/*!40000 ALTER TABLE `s_param` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_plugin`
--

DROP TABLE IF EXISTS `s_plugin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_plugin` (
  `plugin_nom` varchar(64) NOT NULL DEFAULT '',
  `plugin_actif` tinyint(1) NOT NULL DEFAULT '0',
  `plugin_ordre` smallint(5) unsigned NOT NULL DEFAULT '0',
  `plugin_installe` tinyint(1) NOT NULL DEFAULT '0',
  `plugin_updates` blob NOT NULL,
  `plugin_last_update` int(11) NOT NULL,
  PRIMARY KEY (`plugin_nom`),
  KEY `plugin_actif` (`plugin_actif`),
  KEY `plugin_ordre` (`plugin_ordre`),
  KEY `plugin_installe` (`plugin_installe`),
  KEY `plugin_last_update` (`plugin_last_update`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_plugin`
--

LOCK TABLES `s_plugin` WRITE;
/*!40000 ALTER TABLE `s_plugin` DISABLE KEYS */;
INSERT INTO `s_plugin` VALUES ('ocms_contact',1,0,1,'',0),('ocms_jstrad',1,0,1,'',0),('ocms_search',1,0,1,'',0),('ocms_title',1,0,1,'',0),('o_blocs',1,0,1,'',0),('o_createplugin',1,0,1,'',0),('o_messages',1,0,1,'',0),('o_migration',1,0,1,'',0),('o_paragraphs',1,0,1,'',0),('ses',1,0,1,'',0);
/*!40000 ALTER TABLE `s_plugin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_role`
--

DROP TABLE IF EXISTS `s_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_nom` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_role`
--

LOCK TABLES `s_role` WRITE;
/*!40000 ALTER TABLE `s_role` DISABLE KEYS */;
INSERT INTO `s_role` VALUES (1,'ADMINISTRATEUR');
/*!40000 ALTER TABLE `s_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_role_table`
--

DROP TABLE IF EXISTS `s_role_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_role_table` (
  `role_table_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_table_table` varchar(64) NOT NULL DEFAULT '',
  `fk_role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `role_table_type` enum('all','per_user','specific') NOT NULL DEFAULT 'all',
  `role_table_specific` varchar(128) NOT NULL DEFAULT '',
  `role_table_view` tinyint(1) NOT NULL DEFAULT '0',
  `role_table_add` tinyint(1) NOT NULL DEFAULT '0',
  `role_table_edit` tinyint(1) NOT NULL DEFAULT '0',
  `role_table_delete` tinyint(1) NOT NULL DEFAULT '0',
  `role_table_actions` varchar(255) NOT NULL DEFAULT '',
  `role_table_champs` text NOT NULL,
  PRIMARY KEY (`role_table_id`),
  KEY `fk_role_id` (`fk_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_role_table`
--

LOCK TABLES `s_role_table` WRITE;
/*!40000 ALTER TABLE `s_role_table` DISABLE KEYS */;
INSERT INTO `s_role_table` VALUES (1,'all',1,'all','',1,1,1,1,'','');
/*!40000 ALTER TABLE `s_role_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_rubrique`
--

DROP TABLE IF EXISTS `s_rubrique`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_rubrique` (
  `rubrique_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ocms_version` int(10) unsigned DEFAULT NULL,
  `ocms_version_name` varchar(128) DEFAULT NULL,
  `ocms_etat` enum('brouillon','publiable','en_ligne','archive') NOT NULL DEFAULT 'publiable',
  `ocms_creator` smallint(5) unsigned DEFAULT NULL,
  `ocms_date_crea` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ocms_date_modif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ocms_date_publi` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ocms_date_online` datetime NOT NULL,
  `ocms_date_offline` datetime NOT NULL,
  `fk_gabarit_id` smallint(5) unsigned DEFAULT NULL,
  `fk_rubrique_id` int(10) unsigned DEFAULT NULL,
  `rubrique_ordre` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rubrique_gabarit_param` varchar(255) NOT NULL DEFAULT '',
  `rubrique_url_fr` varchar(255) NOT NULL DEFAULT '',
  `rubrique_titre_fr` varchar(255) NOT NULL DEFAULT '',
  `rubrique_titre_page_fr` varchar(255) NOT NULL,
  `rubrique_sous_titre_fr` varchar(255) NOT NULL DEFAULT '',
  `rubrique_texte_fr` text NOT NULL,
  `rubrique_keywords_fr` varchar(255) NOT NULL DEFAULT '',
  `rubrique_desc_fr` text NOT NULL,
  `rubrique_date_crea` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rubrique_date_modif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rubrique_date_publi` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rubrique_picto_fr` varchar(255) NOT NULL,
  `rubrique_type` enum('page','link','folder','siteroot','menuroot') NOT NULL DEFAULT 'page',
  `rubrique_link_fr` varchar(255) NOT NULL DEFAULT '',
  `rubrique_dyntitle` tinyint(1) NOT NULL DEFAULT '0',
  `rubrique_dynvisibility` tinyint(1) NOT NULL DEFAULT '0',
  `rubrique_template` varchar(255) NOT NULL DEFAULT '',
  `rubrique_option` set('dynVisibility','dynTitle','dynSubRubs') NOT NULL,
  PRIMARY KEY (`rubrique_id`),
  KEY `fk_gabarit_id` (`fk_gabarit_id`),
  KEY `fk_creator_id` (`ocms_creator`),
  KEY `rubrique_ordre` (`rubrique_ordre`),
  KEY `rubrique_dyntitle` (`rubrique_dyntitle`),
  KEY `rubrique_url_fr` (`rubrique_url_fr`),
  KEY `ocms_date_online` (`ocms_date_online`),
  KEY `ocms_date_offline` (`ocms_date_offline`),
  KEY `rubrique_type` (`rubrique_type`),
  KEY `ocms_etat` (`ocms_etat`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `rubrique_type_2` (`rubrique_type`),
  KEY `ocms_etat_2` (`ocms_etat`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_rubrique`
--

LOCK TABLES `s_rubrique` WRITE;
/*!40000 ALTER TABLE `s_rubrique` DISABLE KEYS */;
INSERT INTO `s_rubrique` VALUES (1,1,NULL,'en_ligne',1,'0000-00-00 00:00:00','2016-09-29 16:03:18','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',5,NULL,1,'',';ses50-opixido-celio.c9users.io;','50 ans des SES','','','','','','2009-03-30 18:53:07','2009-03-30 18:54:54','2009-03-30 18:54:57','','siteroot','',0,0,'',''),(2,1,NULL,'brouillon',1,'2014-10-29 13:04:56','2016-09-29 14:03:16','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',5,NULL,0,'',';ses50-opixido-celio.c9users.io;','50 ans des SES','','','','','','2009-03-30 18:53:07','2009-03-30 18:54:54','2009-03-30 18:54:57','','siteroot','',0,0,'',''),(3,3,NULL,'en_ligne',1,'2016-09-12 14:50:10','2016-09-12 16:50:25','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,1,1,'','haut','Menu Principal','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','menuroot','',0,0,'',''),(4,4,NULL,'en_ligne',1,'2016-09-12 14:50:31','2016-09-13 14:31:19','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,3,1,'','presentation','Présentation','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(5,5,NULL,'en_ligne',1,'2016-09-12 14:50:43','2016-09-23 18:04:28','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',3,3,2,'','temoignages','Témoignages','Visionnez les témoignages','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(6,6,NULL,'en_ligne',1,'2016-09-12 14:50:56','2016-10-03 14:47:49','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',6,3,3,'article_type=balade;','balades-urbaines','Balades urbaines','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(7,7,NULL,'en_ligne',1,'2016-09-12 14:51:09','2016-09-12 16:51:12','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,3,4,'','concours-video','Concours vidéo','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(8,8,NULL,'en_ligne',1,'2016-09-12 14:51:20','2016-10-03 14:36:43','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',6,3,5,'article_type=blog;','le-blog','Le blog','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(9,9,NULL,'en_ligne',1,'2016-09-12 14:51:33','2016-09-12 16:51:39','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,1,2,'','bas','Bas','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','menuroot','',0,0,'',''),(10,10,NULL,'en_ligne',1,'2016-09-12 14:51:48','2016-09-12 16:51:51','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,9,1,'','plan-du-site','Plan du site','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(11,11,NULL,'en_ligne',1,'2016-09-12 14:51:58','2016-09-12 16:52:01','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,9,2,'','mentions-legales','Mentions légales','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(12,12,NULL,'en_ligne',1,'2016-09-12 14:52:08','2016-09-12 16:52:54','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,9,3,'','contact','Contact','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(13,13,NULL,'en_ligne',1,'2016-09-12 14:53:01','2016-09-12 16:53:05','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,9,4,'','presse','Presse','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(14,4,NULL,'brouillon',1,'2016-09-12 16:10:19','2016-09-13 12:31:16','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,3,0,'','presentation','Présentation','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(15,5,NULL,'brouillon',1,'2016-09-13 14:02:35','2016-09-23 16:04:26','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',3,3,0,'','temoignages','Témoignages','Visionnez les témoignages','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(16,16,NULL,'en_ligne',1,'2016-09-14 14:47:37','2016-09-14 16:47:46','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,1,3,'','autres','Autres','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','menuroot','',0,0,'',''),(17,17,NULL,'en_ligne',1,'2016-09-14 14:47:56','2016-09-14 16:48:03','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',2,16,1,'','temoignez','Témoignez','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(18,6,NULL,'brouillon',1,'2016-10-03 09:31:51','2016-10-03 12:36:51','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',6,3,0,'article_type=balade;','balades-urbaines','Balades urbaines','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(19,8,NULL,'brouillon',1,'2016-10-03 09:32:02','2016-10-03 12:36:41','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',6,3,0,'article_type=blog;','le-blog','Le blog','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','page','',0,0,'',''),(20,20,NULL,'en_ligne',1,'2016-10-03 09:40:19','2016-10-03 11:43:26','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',7,6,1,'','premier-article-du-blog','Premiere balade','','','','','Description courte Description courte Description courte Description courte Description courte Description courte Description courte','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','img_20160716_125440.jpg','page','',0,0,'',''),(21,20,NULL,'brouillon',1,'2016-10-03 09:43:18','2016-10-03 09:43:24','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',7,6,1,'','premier-article-du-blog','Premiere balade','','','','','Description courte Description courte Description courte Description courte Description courte Description courte Description courte','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','img_20160716_125440.jpg','page','',0,0,'',''),(22,22,NULL,'en_ligne',1,'2016-10-03 09:43:39','2016-10-03 11:45:08','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',7,8,1,'','premier-article-du-blog','Premier article du blog','','','','','fjksdl jfqsdkl jfklsd jfklsdqjfklqsjfklmsdfkljdsklqfmjsdklqf jkdlsjfklsdqjm fkdls mfjklsdjfk lsdjfklmsdjklf sdklf jsdk fsdkl fklsd f dsf','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','img_20160801_133801.jpg','page','',0,0,'',''),(23,23,NULL,'en_ligne',1,'2016-10-05 15:58:19','2016-10-05 18:00:45','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',7,6,2,'','autre-article-de-balade-avec-un-titre-tres-long-afin-de-valider-que-ca-fonctionne-quand-meme-et-que-ca-ne-depasse-pas-n-importe-comment','Autre article de balade avec un titre très long afin de valider que ça fonctionne quand même et que ça ne dépasse pas n\'importe comment','','','','','Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','img_20160725_131051860_hdr.jpg','page','',0,0,'',''),(24,23,NULL,'brouillon',1,'2016-10-05 16:00:35','2016-10-05 16:00:43','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',7,6,0,'','autre-article-de-balade-avec-un-titre-tres-long-afin-de-valider-que-ca-fonctionne-quand-meme-et-que-ca-ne-depasse-pas-n-importe-comment','Autre article de balade avec un titre très long afin de valider que ça fonctionne quand même et que ça ne dépasse pas n\'importe comment','','','','','Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article Description de cet article','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','img_20160725_131051860_hdr.jpg','page','',0,0,'','');
/*!40000 ALTER TABLE `s_rubrique` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_trad`
--

DROP TABLE IF EXISTS `s_trad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_trad` (
  `trad_id` varchar(64) NOT NULL DEFAULT '',
  `trad_fr` text NOT NULL,
  `fk_plugin_id` varchar(64) NOT NULL,
  PRIMARY KEY (`trad_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_trad`
--

LOCK TABLES `s_trad` WRITE;
/*!40000 ALTER TABLE `s_trad` DISABLE KEYS */;
INSERT INTO `s_trad` VALUES ('a_votre_tour','à votre tour de poser une question',''),('balade_titre','Balades urbaines,<br/>\r\nRegardez la ville autrement',''),('base_title','50 ans des SES',''),('blog_href_label','Voir tous les articles',''),('blog_titre','Le blog',''),('choisissez-une-question','Choisissez une question',''),('choisissez-une-question-internaute','Répondez à la question posée par un autre témoin',''),('confirm_send_contact','Votre message a bien été envoyé','ocms_contact'),('contact_auto_response','Votre message a bien été pris en compte.\r\nNous vous répondrons dans les meilleurs délais.\r\n\r\nMerci de votre intérêt.','ocms_contact'),('contact_auto_subject','Formulaire de contact','ocms_contact'),('contact_body','Une personne a envoyé ce message via le formulaire de contact du site :\r\n','ocms_contact'),('contact_from','ne_pas@repondre.com','ocms_contact'),('contact_from_nom','Envoi automatique','ocms_contact'),('contact_subject','Nouveau contact','ocms_contact'),('cp_txt_home','Accueil',''),('c_body','Nouveau message de contact :','ocms_contact'),('c_comment','Commentaire','ocms_contact'),('c_email','E-mail','ocms_contact'),('c_from','no@reply.com','ocms_contact'),('c_nom','Nom','ocms_contact'),('c_prenom','Pr&eacute;nom','ocms_contact'),('c_qui','Service','ocms_contact'),('c_subject','[SITE] Formulaire de contact','ocms_contact'),('c_submit','Envoyer','ocms_contact'),('eleve','élève',''),('enregistrez-votre-temoignage','Enregistrez votre temoignage',''),('facebook_label','APSES',''),('facebook_url','https://www.facebook.com/AssoProfSES/',''),('fin','FIN',''),('fk_academie_id','Dans l\'académie',''),('home_temoignages_titre','Témoignages',''),('internaute_annee','Autour de l\'année\r\n<span>(+/- 3 ans)</span>',''),('internaute_annee_debut','de',''),('internaute_annee_fin','à',''),('internaute_cgv','Conditions générales',''),('internaute_cgv_ok','J\'ai lu et j\'accepte les <a href=\"#\">conditions générales</a>',''),('internaute_email','Votre email',''),('internaute_nom','Votre nom',''),('internaute_prenom','Votre prénom',''),('internaute_profession','Votre profession actuelle',''),('internaute_q','Mots clés',''),('internaute_quand','Quand ?',''),('internaute_statut','Vous témoignez en tant que :',''),('internaute_valider','Suite',''),('label_url_presentation','Présentation',''),('label_url_temoignez','Laissez votre témoignage',''),('merci','Merci pour votre participation !',''),('merci-temoignage','Merci pour votre témoignage !',''),('ok','OK',''),('participez_titre_1','<b>Filmez les SES</b>\nconcours vidéo pour les lycéens',''),('participez_titre_2','Participez !',''),('prof','enseignant',''),('prof_1','1. Quelle est la couleur du cheval blanc d\'Henri 4 ?',''),('prof_2','2. Les chaussettes de l\'archiduchesse sont-elles sèches ou archisèches ?',''),('prof_3','3. Qu\'est-ce qu\'un petit pois qui se bat contre une carotte ?',''),('question','Les questions',''),('question_annee','Année',''),('question_pour','A qui souhaitez-vous la poser ?',''),('question_texte','Rédigez votre question (150 caractères maximum)',''),('rechercher','Rechercher',''),('recherchez','Recherchez',''),('repondez_question_internaute','Question posée par ',''),('suivez_titre','Suivez-nous',''),('temoi_href_label','Voir tous les témoignages',''),('twitter_hashtag','#a11y',''),('twitter_label','@50ansSES',''),('twitter_titre','#50ansSES',''),('twitter_url','https://twitter.com/50ansses',''),('url_presentation','/presentation/',''),('validez','Validez',''),('visionnez-temoignages','Visionner des témoignages',''),('vous-avez-60','Vous avez 60 secondes','');
/*!40000 ALTER TABLE `s_trad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_traduction`
--

DROP TABLE IF EXISTS `s_traduction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_traduction` (
  `fk_table` varchar(32) NOT NULL DEFAULT '',
  `fk_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_champ` varchar(64) NOT NULL DEFAULT '',
  `fk_langue_id` varchar(3) NOT NULL DEFAULT '0',
  `traduction_texte` text NOT NULL,
  PRIMARY KEY (`fk_table`,`fk_id`,`fk_champ`,`fk_langue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_traduction`
--

LOCK TABLES `s_traduction` WRITE;
/*!40000 ALTER TABLE `s_traduction` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_traduction` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-10-07  8:14:01
