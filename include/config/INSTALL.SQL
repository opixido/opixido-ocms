-- MySQL dump 10.13  Distrib 5.5.53, for debian-linux-gnu (x86_64)
--
-- Host: 0.0.0.0    Database: mdp
-- ------------------------------------------------------
-- Server version	5.5.53-0ubuntu0.14.04.1

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
-- Table structure for table `m_borne`
--

DROP TABLE IF EXISTS `m_borne`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_borne` (
  `borne_id` int(11) NOT NULL AUTO_INCREMENT,
  `borne_titre_fr` varchar(255) CHARACTER SET latin1 NOT NULL,
  `borne_titre_en` varchar(255) NOT NULL,
  `borne_titre_es` varchar(255) NOT NULL,
  `borne_ip` varchar(15) CHARACTER SET latin1 NOT NULL,
  `borne_conferencier` tinyint(1) NOT NULL,
  `borne_short` varchar(64) NOT NULL,
  PRIMARY KEY (`borne_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_borne`
--

LOCK TABLES `m_borne` WRITE;
/*!40000 ALTER TABLE `m_borne` DISABLE KEYS */;
INSERT INTO `m_borne` VALUES (1,'IAM 8.1 - Actualité des trésors','','','',1,'tresors'),(2,'IAM 5.0.2.1 Monnaies traditionnelles','','','',1,'tradi'),(3,'IAM 4.2.3 Techniques de frappe','','','',1,'frappe'),(4,'IAM 2.0.2 Mendeleïev','','','',1,'mendeleiev');
/*!40000 ALTER TABLE `m_borne` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_lexique`
--

DROP TABLE IF EXISTS `m_lexique`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_lexique` (
  `lexique_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lexique_mot_fr` varchar(255) NOT NULL,
  `lexique_mot_en` varchar(255) NOT NULL,
  `lexique_mot_es` varchar(255) NOT NULL,
  `lexique_desc_fr` text NOT NULL,
  `lexique_desc_en` text NOT NULL,
  `lexique_desc_es` text NOT NULL,
  `ocms_version` int(11) NOT NULL,
  `ocms_etat` enum('brouillon','publiable','en_ligne','archive') NOT NULL,
  `ocms_date_modif` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ocms_date_crea` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lexique_id`),
  KEY `ocms_version` (`ocms_version`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_lexique`
--

LOCK TABLES `m_lexique` WRITE;
/*!40000 ALTER TABLE `m_lexique` DISABLE KEYS */;
INSERT INTO `m_lexique` VALUES (1,'Bonjour 2','','','<p>sdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld <a href=\"event:lexique,3\">sjklfjsdklj </a>flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj fl</p>','','',1,'en_ligne','2016-12-07 17:41:30','0000-00-00 00:00:00'),(2,'Bonjour 2','','','<p>sdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld <a href=\"event:lexique,3\">sjklfjsdklj </a>flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj flsdfj kldsjfkld sjklfjsdklj fl</p>','','',1,'brouillon','2016-12-07 16:41:28','0000-00-00 00:00:00'),(3,'àbricot','','','<p>blabla gdf <a href=\"event:lexique,1\">gdf </a></p>\r\n<p>gdf </p>\r\n<p>gdf g</p>\r\n<p>df</p>\r\n<p> gdf</p>\r\n<p> gdf</p>','','',3,'en_ligne','2016-12-07 17:40:57','2016-12-07 16:40:22');
/*!40000 ALTER TABLE `m_lexique` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_media`
--

DROP TABLE IF EXISTS `m_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `media_titre_fr` varchar(255) NOT NULL,
  `media_titre_en` varchar(255) NOT NULL,
  `media_titre_es` varchar(255) NOT NULL,
  `media_vignette_fr` varchar(255) NOT NULL,
  `media_vignette_en` varchar(255) NOT NULL,
  `media_vignette_es` varchar(255) NOT NULL,
  `media_fichier_fr` varchar(255) NOT NULL,
  `media_fichier_en` varchar(255) NOT NULL,
  `media_fichier_es` varchar(255) NOT NULL,
  `media_legende_fr` text NOT NULL,
  `media_ordre` int(11) NOT NULL,
  `fk_mendeleiev_id` int(11) NOT NULL,
  PRIMARY KEY (`media_id`),
  KEY `media_ordre` (`media_ordre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_media`
--

LOCK TABLES `m_media` WRITE;
/*!40000 ALTER TABLE `m_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_mendeleiev`
--

DROP TABLE IF EXISTS `m_mendeleiev`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_mendeleiev` (
  `mendeleiev_id` int(11) NOT NULL AUTO_INCREMENT,
  `ocms_version` int(11) NOT NULL,
  `ocms_etat` enum('brouillon','publiable','en_ligne','archive') NOT NULL,
  `ocms_date_crea` datetime NOT NULL,
  `ocms_date_modif` datetime NOT NULL,
  `mendeleiev_title_fr` varchar(255) NOT NULL,
  `mendeleiev_title_en` varchar(255) NOT NULL,
  `mendeleiev_title_es` varchar(255) NOT NULL,
  `mendeleiev_symbol` varchar(16) NOT NULL,
  `mendeleiev_atomic_number` int(11) NOT NULL,
  `fk_mendeleiev_serie_id` int(11) NOT NULL,
  `mendeleiev_state_fr` varchar(255) NOT NULL,
  `mendeleiev_state_en` varchar(255) NOT NULL,
  `mendeleiev_state_es` varchar(255) NOT NULL,
  `mendeleiev_durete` decimal(10,0) NOT NULL,
  `mendeleiev_preciosite` decimal(10,0) NOT NULL,
  `mendeleiev_preciosite_annee` year(4) NOT NULL,
  `mendeleiev_rarete` decimal(10,0) NOT NULL,
  `mendeleiev_masse` decimal(10,0) NOT NULL,
  `mendeleiev_outillage` tinyint(1) NOT NULL,
  `mendeleiev_process` tinyint(1) NOT NULL,
  `mendeleiev_production` tinyint(1) NOT NULL,
  `mendeleiev_text_fr` text NOT NULL,
  `mendeleiev_text_en` text NOT NULL,
  `mendeleiev_text_es` text NOT NULL,
  `mendeleiev_fusion` decimal(10,0) NOT NULL,
  `mendeleiev_ebulition` decimal(10,0) NOT NULL,
  `mendeleiev_bottom` tinyint(1) NOT NULL,
  `mendeleiev_selectable` tinyint(1) NOT NULL,
  `mendeleiev_row` int(11) NOT NULL,
  `mendeleiev_column` int(11) NOT NULL,
  `mendeleiev_used` tinyint(1) NOT NULL,
  PRIMARY KEY (`mendeleiev_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_mendeleiev`
--

LOCK TABLES `m_mendeleiev` WRITE;
/*!40000 ALTER TABLE `m_mendeleiev` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_mendeleiev` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_mendeleiev_serie`
--

DROP TABLE IF EXISTS `m_mendeleiev_serie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_mendeleiev_serie` (
  `mendeleiev_serie_id` int(11) NOT NULL AUTO_INCREMENT,
  `mendeleiev_serie_title_fr` varchar(255) NOT NULL,
  `mendeleiev_serie_title_en` varchar(255) NOT NULL,
  `mendeleiev_serie_title_es` varchar(255) NOT NULL,
  `mendeleiev_serie_value` char(6) NOT NULL,
  PRIMARY KEY (`mendeleiev_serie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_mendeleiev_serie`
--

LOCK TABLES `m_mendeleiev_serie` WRITE;
/*!40000 ALTER TABLE `m_mendeleiev_serie` DISABLE KEYS */;
INSERT INTO `m_mendeleiev_serie` VALUES (1,'Série de test','','','ffcc00');
/*!40000 ALTER TABLE `m_mendeleiev_serie` ENABLE KEYS */;
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
INSERT INTO `s_admin` VALUES (1,'opixido','opixido','$2y$10$G6Lb4e01iWufydaqgx.Zzud7c55G259f8pPFI25nHRpbgeL5GBHV.','','redacteur','2016-12-02 09:56:32');
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
  `admin_trad_en` text NOT NULL,
  `admin_trad_es` text NOT NULL,
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
INSERT INTO `s_admin_trad` VALUES ('droits_insuffisants_pour_cette_action','Vous n\'avez pas les droits nécessaires pour effectuer cette action','','',''),('rubrique_pas_en_ligne','Cette rubrique est déjà masquée','','',''),('rubrique_refuser_ok','Votre refus de validation a bien été pris en compte','','',''),('refuse','Refuser la validation','','',''),('unvalidate','Masquer','','',''),('save_and_close','Retour','','',''),('src_refuse','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-undo.png','','',''),('src_light_edit','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/applications-graphics.png','','',''),('src_modification','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png','','',''),('src_voir_version_en_ligne','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-template.png','','',''),('src_voir_version_modifiable','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-properties.png','','',''),('src_calendrier','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/office-calendar.png','','',''),('src_random_password','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/preferences-system-session.png','','',''),('src_locked','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/locked.png','','',''),('src_importftp','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-symbolic-link.png','','',''),('src_copy','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/places/start-here.png','','',''),('generate_random_password','Générer un mot de passe','','',''),('erreur_lock_existe','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/locked.png\" alt=\"\" /> Une autre personne est déjà <br/>en train de modifier cet élément','','',''),('s_admin_p_0','Informations','','',''),('s_admin_p_1','Rubriques autorisées','','',''),('table_log_action','Dernières opérations effectuées','','',''),('modification','Modifier','','',''),('empty_field','Champ vide','','',''),('ajout_sub_rub','Nouveau','','',''),('invisible_rub',' <span style=\"color:red\">[Masquée]</span>','','',''),('version_masquee',' Cette rubrique est actuellement masquée','','',''),('suppression_ok','Suppression effectuée pour : ','','',''),('modification_ok','Changements enregistrés pour : ','','',''),('ajout_ok','correctement ajouté','','',''),('connexion','Connexion ... ','','',''),('username','Nom d\'utilisateur','','',''),('password','Mot de passe','','',''),('save','Enregistrer','','',''),('cancel','Annuler','','',''),('delete','Supprimer','','',''),('search_id','Rechercher sur un identifiant : ','','',''),('search_txt','Extrait de texte &agrave; rechercher ','','',''),('choose_item','Choisissez un &eacute;l&eacute;ment &agrave; modifier','','',''),('rechercher','Lancer la recherche','','',''),('confirm_suppr','Voulez vous vraiment supprimer cet enregistrement ','','',''),('ajouter','Cliquez sur cet icone pour ajouter un &eacute;l&eacute;ment &agrave; la liste : ','','',''),('ajouter_elem','Ajouter un &eacute;l&eacute;ment','','',''),('menu','Menu','','',''),('logout','D&eacute;connexion','','',''),('menu1','Gestion de la base','','',''),('menu2',' ','','',''),('retour',' retour','','',''),('selectable_items','El&eacute;ments s&eacute;lectionnables','','',''),('selected_items','El&eacute;ments s&eacute;lectionn&eacute;s','','',''),('modifier','Modifier','','',''),('modify_item','Modifier un &eacute;l&eacute;ment','','',''),('date_picker','Choisissez la date','','',''),('mal_remplit','Vous avez mal rempli les champs suivants :','','',''),('etes_vous_sur_action','Souhaitez vous vraiment effectuer cette action ?','','',''),('help_tablerel','<b>Champ Multicritère partagés</b><br/>Vous avez la possibilité de choisir plusieurs éléments.<br/><br/>Vous devez sélectionner l\'élément voulu dans la colonne de gauche, puis cliquer sur la flêche orientée vers la droite pour le faire passer dans la liste des éléments sélectionnés','','',''),('help_rte','<b>Champ Wyziwyg</b><br/>Vous avez la possibilité de mettre ce texte en forme via un outil graphique. Pour accéder au texte, cliquez sur l\'icône d\'édition à gauche ','','',''),('help_upload_table','<b>Champ de mise en ligne de tableau CSV</b><br/>Vous avez la possibilité de parcourir le disque de votre ordinateur, et d\'envoyer un fichier Excel (format CSV) pour remplir ce champ par un tableau de données','','',''),('upload_table','Mettre en ligne un tableau (CSV)','','',''),('champ_vide','Ce champ est actuellement vide','','',''),('help_texte','<b>Champ Texte</b><br/>Cliquez dans le champ puis saisissez ou bien copiez/collez votre texte.','','',''),('help_relinv_table','<b>Eléments liés</b><br/>Vous avez ci-contre la liste des éléments liés.<br/><br/> Sélectionnez un élément puis cliquez sur l\'icône d\'édition pour le modifier ','','',''),('help_relation','<b>Sélection simple</b><br/>Vous avez la possibilité de sélectionner un élément dans cette liste ','','',''),('help_file','<b>Mise en ligne de fichier</b><br/>Pour mettre en ligne un fichier, cliquez sur le bouton <i>PARCOURIR</i>, puis sélectionnez le fichier voulu sur votre ordinateur.<br/>Ensuite cliquez sur le bouton <i>Mettre en ligne</i>, pour envoyer le fichier sur le serveur.<br/><br/>Si un fichier existe déjà, vous pouvez refaire la procédure ci-dessus pour le mettre à jour, mais l\'ancienne versin sera supprimée<br/><br/>Vous pouvez également supprimer le fichier sans le remplacer en cliquant sur le bouton <i>Supprimer</i>','','',''),('aucun_element','Il n\'y a aucun élément dans cette liste','','',''),('select_rub_below','Sélectionnez une page ci-dessous','','',''),('rubrique_valider_ok','Cette rubrique a bien été validée. Vos modifications sont désormais visibles par les internautes ','','',''),('rubrique_demande_valider_ok','Votre demande de validation a bien été transmise à : ','','',''),('rubrique_devalider_ok','Cette rubrique a bien été masquée.<br/>Les internautes n\'y ont plus accès','','',''),('src_editer','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png','','',''),('src_edit','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png','','',''),('src_voir','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png','','',''),('src_view','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png','','',''),('src_new','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/list-add.png','','',''),('src_preview','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/image-x-generic.png','','',''),('src_droite','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-last.png','','',''),('src_gauche','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-first.png','','',''),('src_help','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/apps/help-browser.png','','',''),('src_logout','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-log-out.png','','',''),('src_NewsletterPreview','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-news-reader.png','','',''),('src_NewsletterFakeSend','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/evolution.png','','',''),('src_NewsletterSend','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-mail.png','','',''),('src_ask_for_validation','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/system-software-update.png','','',''),('src_cancel','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-undo.png','','',''),('src_delete','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/process-stop.png','','',''),('src_del','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/process-stop.png','','',''),('src_validate','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png','','',''),('src_validateVersion','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png','','',''),('src_showObject','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png','','',''),('src_unvalidate','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png','','',''),('src_hideVersion','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png','','',''),('src_hideObject','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png','','',''),('src_upload','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/x-directory-remote-workgroup.png','','',''),('src_up','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-up.png','','',''),('src_down','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-down.png','','',''),('src_date','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-down.png','','',''),('src_SiteValidate','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png','','',''),('src_PublicationValidate','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png','','',''),('src_SiteHide','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png','','',''),('src_PublicationHide','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png','','',''),('src_back','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-previous.png','','',''),('src_first','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-previous.png','','',''),('src_save','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-save.png','','',''),('src_saveas','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-saveas.png','','',''),('src_search','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-search.png','','',''),('src_desktop','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/places/user-home.png','','',''),('src_close','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record.png','','',''),('src_message_info','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/dialog-information.png','','',''),('src_message_error','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/dialog-warning.png','','',''),('src_undo','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-undo.png','','',''),('src_go','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-find.png','','',''),('src_InstallPlugin','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/install.png','','',''),('src_UninstallPlugin','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/uninstall.png','','',''),('search','Rechercher','','',''),('preview','Pré-visualiser','','',''),('field_img_paragraphe_titre_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png','','',''),('field_img_paragraphe_titre_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png','','',''),('field_img_rubrique_titre_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png','','',''),('field_img_rubrique_titre_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png','','',''),('field_img_r_rubrique_lexique','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/x-office-address-book.png','','',''),('field_img_fk_liens_id','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/mail-attachment.png','','',''),('field_img_fk_paragraphe_id','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/format-justify-left.png','','',''),('field_img_rubrique_desc_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png','','',''),('field_img_rubrique_desc_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png','','',''),('field_img_rubrique_keywords_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png','','',''),('field_img_rubrique_keywords_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png','','',''),('field_img_rubrique_gabarit_param','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png','','',''),('field_img_fk_gabarit_id','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png','','',''),('field_img_fk_para_type_id','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png','','',''),('field_img_paragraphe_img_1_fr','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/image-x-generic.png','','',''),('field_img_paragraphe_img_1_en','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/image-x-generic.png','','',''),('field_help_rubrique_titre_en','<b>Titre de la page</b><br/>Il apparaitra dans les menus de navigation,<br/> la barre de titre du navigateur,<br/> et les résultats du moteur de recherche','','',''),('field_help_rubrique_titre_fr','<b>Titre de la page</b><br/>Il apparaitra dans les menus de navigation,<br/> la barre de titre du navigateur,<br/> et les résultats du moteur de recherche','','',''),('field_help_fk_paragraphe_id','<b>Paragraphes de cete page</b><br/>Votre page est constituée de plusieurs paragraphes. <br/><br/>Vous pouvez voir ci-dessous les paragraphes déjà créés, et cliquer sur l\'icône en début de ligne pour les modifier indépendement','','',''),('paragraphe_titre_fr','Titre en Français','','',''),('paragraphe_titre_en','Titre en Anglais','','',''),('paragraphe_contenu_fr','Contenu en Français','','',''),('paragraphe_contenu_en','Contenu en Anglais','','',''),('paragraphe_img_1_fr','Image d\'accompagnement Française','','',''),('paragraphe_img_1_en','Image d\'accompagnement Anglaise','','',''),('paragraphe_img_1_alt_fr','Description Française de l\'image ci-dessus','','',''),('paragraphe_img_1_alt_en','Description Anglaise de l\'image ci-dessus','','',''),('max_size','Taille max. ','','',''),('tester_le_lien','Tester le lien dans une popup','','',''),('choisir_rubrique','Choisir une page dans le site','','',''),('choisir_rubrique_ci_dessous','Cliquez sur une rubrique ci-dessous','','',''),('voir','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png\"  alt=\"Voir\" />','','',''),('editer','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png\"  alt=\"Editer\" />','','',''),('access_direct','Accès direct ','','',''),('mettre_en_ligne','Mettre en ligne','','',''),('non_renseigne','Non renseign&eacute;','','',''),('exporter','Exporter la table au format CSV','','',''),('vider','Vider le contenu de la table','','',''),('etes_vous_sur','Etes vous sur de vouloir vider cette table ? ','','',''),('il_y_a','Il y a ','','',''),('resultats','R&eacute;sultat(s)','','',''),('recherche_textuelle','Recherche plein texte','','',''),('recherche_avancee','Recherche avanc&eacute;e ','','',''),('fichier_uploader','Mettre en ligne un fichier','','',''),('fichier_remplacer','Remplacer ce fichier par : ','','',''),('supprimer_fichier','Voulez vous vraiment supprimer ce fichier du serveur ?','','',''),('impossible_supprimer_repertoire','Le r&eacute;pertoire contentant les fichiers de cet &eacute;l&eacute;ment n\'a pas pu etre supprim&eacute;','','',''),('impossible_supprimer','Ce fichier n\'a pas pu &agrave;tre supprim&eacute; : ','','',''),('editer_wysiwyg','Cliquez ici pour acc&eacute;der &agrave; l\'&eacute;diteur WYSIWYG complet pour mettre en forme ce champ','','',''),('voir_wysiwyg','Cliquez ici pour voir le contenu de ce champ dans une nouvelle fen&ecirc;tre','','',''),('edit','Modifier','','',''),('edis_image','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-properties.png\" border=\"0\" alt=\"Editer\" /> Retailler, recadrer, optimiser ... cette image','','',''),('deja_fichier','Fichier existant','','',''),('menu_0','Page d\'accueil','','',''),('menu_1','Lexique','','',''),('menu_2','Param&egrave;tres techniques','','',''),('t_event_p_0','Evènement Agenda','','',''),('t_action_p_0','Actions','','',''),('t_link_p_0','Lien','','',''),('t_bloc_home_p_0','Bloc Page d\'accueil','','',''),('arborescence','Rubriques','','',''),('getup','Monter cette ligne','','',''),('getdown','Descendre cette ligne','','',''),('rubrique_meta_keyw_fr','Mots clefs d&eacute;finissants la page (FR) (1000 char)','','',''),('rubrique_meta_keyw_en','Mots clefs d&eacute;finissants la page (EN) (1000 char)','','',''),('rubrique_meta_desc_fr','Description de la page (FR) (255 char max)','','',''),('rubrique_meta_desc_en','Description de la page (EN) (255 char max)','','',''),('rubrique_visible','Afficher cette rubrique','','',''),('fk_paragraphe_id','Liste des paragraphes constituants cette page','','',''),('fk_para_type_id','Type de paragraphe','','',''),('fk_gabarit_id','Gabarit Sp&eacute;cial','','',''),('rubrique_gabarit_param','Param&egrave;tre technique du gabarit','','',''),('rubrique_pos_box','BoÃ®te contextuelles align&eacute;es en haut de la page','','',''),('meta_informations','M&eacute;ta informations','','',''),('rubrique_keywords_fr','Mots clefs FR (255 chars)','','',''),('rubrique_keywords_en','Mots clefs EN(255 chars)','','',''),('rubrique_desc_fr','Description courte de la page FR','','',''),('rubrique_desc_en','Description courte de la page EN','','',''),('desc_meta','Ces informations ne seront pas visible dans la page, mais serviront au bon r&eacute;f&eacute;rencement de cette page.<br/>La description peut apparaitre dans la liste des r&eacute;sultats du moteur de recherche','','',''),('s_param','Param&egrave;tres du site','','',''),('url_name','Titre pour le lien','','',''),('s_rubrique','Rubriques du site','','',''),('s_param_p_0','Param&egrave;tre','','',''),('s_rubrique_p_0','Contenu','','',''),('s_rubrique_p_1','Référencement','','',''),('s_rubrique_p_2','Propriétés','','',''),('s_rubrique_p_4','Publications','','',''),('s_rubrique_p_3','Meta donn&eacute;es','','',''),('s_rubrique_p_5','Rendez-Vous','','',''),('s_rubrique_p_6','Liens document&eacute;s','','',''),('s_rubrique_p_7','FAQs','','',''),('s_rubrique_p_questions','Questions','','',''),('fk_thematique_id','Thématique d\'application de cette association','','',''),('rubrique_titre_fr','Titre de la rubrique en Français','','',''),('rubrique_titre_en','Titre de la rubrique en Anglais','','',''),('s_rubrique.fk_site_id','Liste des liens document&eacute;s ratach&eacute;s &agrave; cette cat&eacute;gorie','','',''),('url_fr_will_be','Le nom du dossier en Français de cette rubrique sera : ','','',''),('url_en_will_be','Le nom du dossier en Anglais de cette rubrique sera :','','',''),('fk_rubrique_id','Pages','','',''),('s_trad','Textes param&eacute;trables','','',''),('t_fichier_p_0','Fichier','','',''),('s_paragraphe_p_0','Format de paragraphe','','',''),('s_paragraphe_p_1','Texte Français','','',''),('s_paragraphe_p_2','Texte Anglais','','',''),('voir_actualite','','','',''),('voir_editions_patrimoine','','','',''),('voir_fiche_grand_public','','','',''),('voir_reponses','','','',''),('ce_s_paragraphe','ce Paragraphe','','',''),('ce_s_image','cette Image','','',''),('ce_t_lexique','cette D&eacute;finition','','',''),('un_s_paragraphe','un Paragraphe','','',''),('ce_s_rubrique','cette Rubrique','','',''),('un_s_rubrique','une Rubrique','','',''),('info_crea_rub','Cr&eacute;ation d\'une nouvelle rubrique','','',''),('version_modifiable','Ceci est la version modifiable de la page','','',''),('version_en_ligne','Ceci est la version en ligne de la page','','',''),('voir_version_modifiable','Voir la version modifiable','','',''),('voir_version_en_ligne','Voir la version en ligne','','',''),('show_actions','Actions possibles','','',''),('validate','Valider','','',''),('ask_for_validation','Demander la validation','','',''),('contenu_en_bdd','Une partie de cette page provient d\'une base externe<br/>','','',''),('InstallPlugin','Installer','','',''),('UninstallPlugin','Désinstaller','','',''),('cp_attente_rubs','Rubriques en-cours de validation','','',''),('cp_valide_rubs','Derni&egrave;res rubriques valid&eacute;es','','',''),('cp_create_rubs','Derni&egrave;res rubriques cr&eacute;&eacute;es','','',''),('cp_no_rubs_updated','Aucune rubrique en attente de validation','','',''),('cp_user_info','Informations utilisateur','','',''),('emptyCache','Vider le cache','','',''),('reIndexSearch','Ré-indexer le site','','',''),('files_on','fichiers sur','','',''),('have_been_removed','ont bien été supprimés','','',''),('validateVersion','Valider les modifications','','',''),('hideVersion','Masquer cet élément','','',''),('hideObject','Masquer cet élément','','',''),('showObject','Mettre en ligne','','',''),('s_rubrique_p_contenu','Contenu','','',''),('s_rubrique_p_referencement','Référencement / URI','','',''),('s_rubrique_p_proprietes','Paramètres','','',''),('s_rubrique_p_contexte','Téléchargements','','',''),('src_forumValider','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-jump.png','','',''),('src_forumInterdire','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-lock-screen.png','','',''),('help_','Ce titre sea affiché dans les menus, en haut de la page, et dans les résultats des moteurs de recherche','','',''),('cp_txt_s_rubrique','Gestion du contenu','','',''),('cp_txt_s_admin','Gestion utilisateurs','','',''),('cp_txt_s_plugin','Plugins','','',''),('cp_txt_s_param','Configuration','','',''),('cp_txt_s_trad','Textes param&eacute;trables','','',''),('cp_txt_s_admin_trad','Textes de l\'admin','','',''),('create_and_edit_rub','Continuer','','',''),('genform_boolean_0','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record.png\" alt=\"Non\" />','','',''),('genform_boolean_1','<img src=\"[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record-green.png\" alt=\"Oui\" />','','',''),('map_geocode','Trouver la position à partir de l\'adresse','','',''),('map_usecenter','Enregistrer le centre actuel de la carte','','',''),('picto_emptyCache','trash','','',''),('picto_showPhpInfo','tasks','','',''),('picto_executeSql','download-alt','','',''),('picto_changeTranslations','flag','','',''),('picto_recheckTranslations','retweet','','',''),('picto_setAllUrls','globe','','',''),('picto_cleanFiles','fire','','',''),('picto_backupDb','share','','',''),('picto_o_createPlugin','inbox','','',''),('showPhpInfo','phpinfo();','','',''),('executeSql','Requête SQL','','',''),('changeTranslations','Gérer les langues','','',''),('recheckTranslations','Ajouter les champs de langue manquants','','',''),('setAllUrls','Définir toutes les URLs manquantes','','',''),('cleanFiles','Supprimer les fichiers inutilisés','','',''),('backupDb','Sauvegarde de la BDD','','',''),('o_createPlugin','Créer un Plugin','','',''),('lastActions','Vos derniers éléments modifiés','','',''),('derniere_connexion','Dernière connexion le ','','',''),('lastActions_none','Aucune action effectuée','','',''),('add_another','Ajouter ','','',''),('liste_global_actions','Actions administrateur ','','',''),('recherche_rapide','Recherche rapide ','','',''),('enum_page','Page simple','','',''),('enum_link','Lien','','',''),('enum_folder','Dossier','','',''),('enum_siteroot','Racine de site','','',''),('enum_menuroot','Menu','','',''),('base_title','Monnaie de Paris','','',''),('imgonglet_s_rubrique_p_contenu','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-paste.png','','',''),('imgonglet_s_rubrique_p_referencement','[ADMIN_PICTOS_FOLDER]16x16/apps/system-file-manager.png','','',''),('imgonglet_s_rubrique_p_proprietes','[ADMIN_PICTOS_FOLDER]16x16/emblems/emblem-system.png','','',''),('field_img_rubrique_titre','[ADMIN_PICTOS_FOLDER]16x16/apps/accessories-text-editor.png','','',''),('fieldset_img_referencement','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/text-html.png','','',''),('fieldset_referencement','Référencement ','','',''),('field_img_rubrique_keywords','[ADMIN_PICTOS_FOLDER]16x16/apps/preferences-desktop-font.png','','',''),('rubrique_keywords','Mots-clefs','','',''),('rubrique_desc','Description courte','','',''),('field_img_rubrique_desc','[ADMIN_PICTOS_FOLDER]16x16/apps/accessories-text-editor.png','','',''),('field_img_rubrique_url','[ADMIN_PICTOS_FOLDER]16x16/categories/applications-internet.png','','',''),('rubrique_url','Url de la page','','',''),('fieldset_img_programmation','[ADMIN_PICTOS_FOLDER]16x16/status/image-loading.png','','',''),('fieldset_programmation','Programmation / Archivage','','',''),('field_img_ocms_date_online','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-redo.png','','',''),('ocms_date_online','Date de mise en ligne','','',''),('field_img_ocms_date_offline','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-undo.png','','',''),('ocms_date_offline','Date de mise hors ligne','','',''),('rubrique_template','Template du site','','',''),('field_img_rubrique_template','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/text-x-generic-template.png','','',''),('field_img_rubrique_type','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/package-x-generic.png','','',''),('rubrique_type','Type de fonctionnement','','',''),('duplicateMV','Dupliquer cette version','','',''),('mv_duplicate_and_edit_draft','Modifier (nouveau brouillon)','','',''),('mv_edit_draft','Modifier (le brouillon)','','',''),('publishMV','Publier','','',''),('src_duplicateMV','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-copy.png','','',''),('src_publishMV','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/text-html.png','','',''),('src_unpublishMV','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-unreadable.png','','',''),('unpublishMV','Mettre Hors Ligne','','',''),('s_paragraphe_p_paragraphe','Paragraphe','','',''),('imgonglet_s_paragraphe_p_paragraphe','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-paste.png','','',''),('field_img_paragraphe_titre','[ADMIN_PICTOS_FOLDER]16x16/actions/format-justify-left.png','','',''),('paragraphe_titre','Titre','','',''),('field_img_paragraphe_contenu','[ADMIN_PICTOS_FOLDER]16x16/actions/edit-paste.png','','',''),('paragraphe_contenu','Contenu','','',''),('paragraphe_img_1','Image','','',''),('field_img_paragraphe_img_1','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/image-x-generic.png','','',''),('paragraphe_img_1_alt','Texte alternatif à l’image (uniquement si elle fourni une information autrement inaccessible)','','',''),('field_img_paragraphe_img_1_alt','[ADMIN_PICTOS_FOLDER]16x16/apps/preferences-desktop-accessibility.png','','',''),('paragraphe_img_1_legend','Légende/copyright','','',''),('field_img_paragraphe_img_1_legend','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/application-certificate.png','','',''),('imgonglet_s_trad_p_0','[ADMIN_PICTOS_FOLDER]16x16/actions/format-text-bold.png','','',''),('s_trad_p_0','Texte paramétrable','','',''),('trad_id','Identifiant (ne pas changer)','','',''),('field_img_trad','[ADMIN_PICTOS_FOLDER]16x16/actions/format-justify-left.png','','',''),('trad','Traduction','','',''),('field_img_plugin_nom','[ADMIN_PICTOS_FOLDER]16x16/actions/format-justify-center.png','','',''),('plugin_nom','Nom','','',''),('field_img_plugin_actif','[ADMIN_PICTOS_FOLDER]16x16/status/software-update-available.png','','',''),('plugin_actif','Actif','','',''),('picto_updateDatabase','circle-arrow-up','','',''),('imgonglet_s_rubrique_p_new','[ADMIN_PICTOS_FOLDER]16x16/actions/document-new.png','','',''),('s_rubrique_p_new','Nouvelle page','','',''),('moveRubrique','Déplacer cette rubrique','','',''),('src_moveRubrique','[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-symbolic-link.png','','',''),('fieldset-nbfields','champs masqués','','',''),('confirm_delete','Pour supprimer cet élément saisissez \"OUI\" ci-dessous','','',''),('rubrique_picto','Image principale / vignette','','',''),('field_img_rubrique_picto','[ADMIN_PICTOS_FOLDER]16x16/mimetypes/image-x-generic.png','','','');
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
INSERT INTO `s_bloc` VALUES ('footer','','',1),('header','','',1),('main_after','','',1),('main_before','','',1);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_gabarit`
--

LOCK TABLES `s_gabarit` WRITE;
/*!40000 ALTER TABLE `s_gabarit` DISABLE KEYS */;
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
  `image_titre_en` varchar(255) NOT NULL,
  `image_titre_es` varchar(255) NOT NULL,
  `image_legende_fr` varchar(255) NOT NULL DEFAULT '',
  `image_legende_en` varchar(255) NOT NULL,
  `image_legende_es` varchar(255) NOT NULL,
  `image_copyright` varchar(255) NOT NULL DEFAULT '',
  `image_lien_fr` varchar(255) DEFAULT '',
  `image_lien_en` varchar(255) DEFAULT NULL,
  `image_lien_es` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_log_action`
--

LOCK TABLES `s_log_action` WRITE;
/*!40000 ALTER TABLE `s_log_action` DISABLE KEYS */;
INSERT INTO `s_log_action` VALUES (1,1,'s_rubrique','2','update','2016-12-01 14:36:36'),(2,1,'s_rubrique','2','afterUpdate','2016-12-01 14:36:36'),(3,1,'s_rubrique','2','update','2016-12-01 14:36:38'),(4,1,'s_rubrique','2','afterUpdate','2016-12-01 14:36:38'),(5,1,'s_rubrique','2','publishMV','2016-12-01 14:36:40'),(6,1,'s_paragraphe','2','beforeDelete','2016-12-01 14:36:40'),(7,1,'s_paragraphe','','delete','2016-12-01 14:36:40'),(8,1,'s_plugin','lexique','insert','2016-12-01 14:47:53'),(9,1,'s_plugin','lexique','update','2016-12-01 14:47:53'),(10,1,'s_plugin','lexique','afterUpdate','2016-12-01 14:47:53'),(11,1,'s_plugin','lexique','InstallPlugin','2016-12-01 14:47:55'),(12,1,'m_lexique','1','insert','2016-12-01 14:52:34'),(13,1,'m_lexique','1','update','2016-12-01 14:52:34'),(14,1,'m_lexique','1','afterUpdate','2016-12-01 14:52:34'),(15,1,'m_lexique','1','publishMV','2016-12-01 14:52:37'),(16,1,'m_lexique','1','duplicateMV','2016-12-01 14:53:16'),(17,1,'m_lexique','2','insert','2016-12-01 14:53:16'),(18,1,'m_lexique','2','update','2016-12-01 14:53:19'),(19,1,'m_lexique','2','afterUpdate','2016-12-01 14:53:19'),(20,1,'m_lexique','2','update','2016-12-01 14:57:41'),(21,1,'m_lexique','2','afterUpdate','2016-12-01 14:57:41'),(22,1,'m_lexique','2','update','2016-12-01 14:57:51'),(23,1,'m_lexique','2','afterUpdate','2016-12-01 14:57:51'),(24,1,'m_lexique','2','publishMV','2016-12-01 14:57:54'),(25,1,'s_rubrique','2','update','2016-12-01 15:02:22'),(26,1,'s_rubrique','2','afterUpdate','2016-12-01 15:02:23'),(27,1,'s_rubrique','2','publishMV','2016-12-01 15:02:25'),(28,1,'s_rubrique','1','duplicateMV','2016-12-01 15:02:42'),(29,1,'s_rubrique','3','insert','2016-12-01 15:02:42'),(30,1,'s_rubrique','3','update','2016-12-01 15:02:44'),(31,1,'s_rubrique','3','afterUpdate','2016-12-01 15:02:44'),(32,1,'s_rubrique','3','del','2016-12-01 15:02:59'),(33,1,'s_rubrique','3','beforeDelete','2016-12-01 15:02:59'),(34,1,'s_rubrique','','delete','2016-12-01 15:02:59'),(35,1,'s_rubrique','4','insert','2016-12-01 15:03:09'),(36,1,'s_rubrique','4','update','2016-12-01 15:03:09'),(37,1,'s_rubrique','4','afterUpdate','2016-12-01 15:03:09'),(38,1,'s_rubrique','4','update','2016-12-01 15:03:13'),(39,1,'s_rubrique','4','afterUpdate','2016-12-01 15:03:13'),(40,1,'s_rubrique','4','duplicateMV','2016-12-01 15:03:16'),(41,1,'s_rubrique','5','insert','2016-12-01 15:03:16'),(42,1,'s_rubrique','5','update','2016-12-01 15:03:20'),(43,1,'s_rubrique','5','afterUpdate','2016-12-01 15:03:20'),(44,1,'s_rubrique','4','del','2016-12-01 15:03:27'),(45,1,'s_rubrique','4','beforeDelete','2016-12-01 15:03:27'),(46,1,'s_rubrique','','delete','2016-12-01 15:03:27'),(47,1,'s_role','2','insert','2016-12-02 09:34:21'),(48,1,'s_role','2','update','2016-12-02 09:34:21'),(49,1,'s_role','2','afterUpdate','2016-12-02 09:34:21'),(50,1,'s_role_table','2','insert','2016-12-02 09:34:51'),(51,1,'s_role_table','2','update','2016-12-02 09:34:51'),(52,1,'s_role_table','2','afterUpdate','2016-12-02 09:34:51'),(53,1,'s_plugin','mdp','insert','2016-12-02 09:36:45'),(54,1,'s_plugin','mdp','update','2016-12-02 09:36:45'),(55,1,'s_plugin','mdp','afterUpdate','2016-12-02 09:36:45'),(56,1,'s_plugin','mdp','InstallPlugin','2016-12-02 09:36:46'),(57,1,'s_plugin','conferencier','insert','2016-12-02 14:32:44'),(58,1,'s_plugin','conferencier','update','2016-12-02 14:32:44'),(59,1,'s_plugin','conferencier','afterUpdate','2016-12-02 14:32:44'),(60,1,'s_plugin','conferencier','InstallPlugin','2016-12-02 14:32:46'),(61,1,'m_borne','1','insert','2016-12-02 15:11:44'),(62,1,'m_borne','1','update','2016-12-02 15:11:44'),(63,1,'m_borne','1','afterUpdate','2016-12-02 15:11:44'),(64,1,'m_borne','2','insert','2016-12-02 15:12:02'),(65,1,'m_borne','2','update','2016-12-02 15:12:02'),(66,1,'m_borne','2','afterUpdate','2016-12-02 15:12:02'),(67,1,'m_borne','3','insert','2016-12-02 15:12:16'),(68,1,'m_borne','3','update','2016-12-02 15:12:16'),(69,1,'m_borne','3','afterUpdate','2016-12-02 15:12:16'),(70,1,'m_borne','4','insert','2016-12-02 15:12:30'),(71,1,'m_borne','4','update','2016-12-02 15:12:30'),(72,1,'m_borne','4','afterUpdate','2016-12-02 15:12:30'),(73,1,'m_lexique','3','insert','2016-12-07 16:40:22'),(74,1,'m_lexique','3','update','2016-12-07 16:40:22'),(75,1,'m_lexique','3','afterUpdate','2016-12-07 16:40:22'),(76,1,'m_lexique','3','update','2016-12-07 16:40:55'),(77,1,'m_lexique','3','afterUpdate','2016-12-07 16:40:55'),(78,1,'m_lexique','3','publishMV','2016-12-07 16:40:57'),(79,1,'s_rubrique','3','duplicateMV','2016-12-07 16:41:06'),(80,1,'s_rubrique','3','insert','2016-12-07 16:41:06'),(81,1,'m_lexique','2','update','2016-12-07 16:41:28'),(82,1,'m_lexique','2','afterUpdate','2016-12-07 16:41:28'),(83,1,'m_lexique','2','publishMV','2016-12-07 16:41:30'),(84,1,'s_plugin','mendeleiev','insert','2016-12-08 09:59:18'),(85,1,'s_plugin','mendeleiev','update','2016-12-08 09:59:18'),(86,1,'s_plugin','mendeleiev','afterUpdate','2016-12-08 09:59:18'),(87,1,'s_plugin','mendeleiev','InstallPlugin','2016-12-08 09:59:20'),(88,1,'m_mendeleiev','1','insert','2016-12-08 10:18:26'),(89,1,'m_mendeleiev','1','update','2016-12-08 10:18:26'),(90,1,'m_mendeleiev','1','afterUpdate','2016-12-08 10:18:26'),(91,1,'m_mendeleiev_serie','1','insert','2016-12-08 10:18:34'),(92,1,'m_mendeleiev_serie','1','update','2016-12-08 10:18:34'),(93,1,'m_mendeleiev_serie','1','afterUpdate','2016-12-08 10:18:34'),(94,1,'m_mendeleiev','1','update','2016-12-08 10:18:52'),(95,1,'m_mendeleiev','1','afterUpdate','2016-12-08 10:18:52'),(96,1,'m_mendeleiev','1','update','2016-12-08 10:19:30'),(97,1,'m_mendeleiev','1','afterUpdate','2016-12-08 10:19:30'),(98,1,'m_mendeleiev','1','del','2016-12-08 12:18:21'),(99,1,'m_mendeleiev','1','beforeDelete','2016-12-08 12:18:21'),(100,1,'m_mendeleiev','','delete','2016-12-08 12:18:21');
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
  `fk_gabarit_id` int(11) NOT NULL,
  PRIMARY KEY (`para_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_para_type`
--

LOCK TABLES `s_para_type` WRITE;
/*!40000 ALTER TABLE `s_para_type` DISABLE KEYS */;
INSERT INTO `s_para_type` VALUES (2,'Titre + image + texte','<div class=\"paraimg\">\r\n<h2>@@titre@@</h2>\r\n<div class=\"img\">##1##</div>\r\n<div class=\"texte\">@@texte@@</div>\r\n</div>\r\n\r\n','','','',1,0,0,1,0,'','','',0);
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
  `paragraphe_titre_en` varchar(255) NOT NULL,
  `paragraphe_titre_es` varchar(255) NOT NULL,
  `paragraphe_contenu_fr` text NOT NULL,
  `paragraphe_contenu_en` text NOT NULL,
  `paragraphe_contenu_es` text NOT NULL,
  `paragraphe_contenu_csv_fr` text NOT NULL,
  `paragraphe_contenu_csv_en` text NOT NULL,
  `paragraphe_contenu_csv_es` text NOT NULL,
  `paragraphe_img_1_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_img_1_en` varchar(255) NOT NULL,
  `paragraphe_img_1_es` varchar(255) NOT NULL,
  `paragraphe_img_2_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_img_2_en` varchar(255) NOT NULL,
  `paragraphe_img_2_es` varchar(255) NOT NULL,
  `paragraphe_img_1_alt_fr` varchar(80) NOT NULL DEFAULT '',
  `paragraphe_img_1_alt_en` varchar(80) NOT NULL,
  `paragraphe_img_1_alt_es` varchar(80) NOT NULL,
  `paragraphe_img_2_alt_fr` varchar(80) NOT NULL DEFAULT '',
  `paragraphe_img_2_alt_en` varchar(80) NOT NULL,
  `paragraphe_img_2_alt_es` varchar(80) NOT NULL,
  `paragraphe_params_fr` varchar(255) DEFAULT NULL,
  `paragraphe_params_en` varchar(255) DEFAULT NULL,
  `paragraphe_params_es` varchar(255) DEFAULT NULL,
  `paragraphe_file_1_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_file_1_en` varchar(255) NOT NULL,
  `paragraphe_file_1_es` varchar(255) NOT NULL,
  `paragraphe_file_1_legend_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_file_1_legend_en` varchar(255) NOT NULL,
  `paragraphe_file_1_legend_es` varchar(255) NOT NULL,
  `paragraphe_link_1_fr` varchar(255) NOT NULL DEFAULT '',
  `paragraphe_link_1_en` varchar(255) NOT NULL,
  `paragraphe_link_1_es` varchar(255) NOT NULL,
  `paragraphe_img_1_copyright` varchar(80) NOT NULL DEFAULT '',
  `paragraphe_img_2_copyright` varchar(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`paragraphe_id`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `paragraphe_ordre` (`paragraphe_ordre`),
  KEY `fk_para_type_id` (`fk_para_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_paragraphe`
--

LOCK TABLES `s_paragraphe` WRITE;
/*!40000 ALTER TABLE `s_paragraphe` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_paragraphe` ENABLE KEYS */;
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
INSERT INTO `s_param` VALUES ('cache_path','cache','',''),('date_update_anything','1481192370','',''),('date_update_arbo','1481128890','',''),('date_update_m_borne','1480691550','',''),('date_update_m_lexique','1481128890','',''),('date_update_m_mendeleiev','1481199501','',''),('date_update_m_mendeleiev_serie','1481192314','',''),('date_update_s_admin_trad','1414593006','',''),('date_update_s_bloc','1414591518','',''),('date_update_s_paragraphe','1414595338','',''),('date_update_s_plugin','1481191160','',''),('date_update_s_role','1480671261','',''),('date_update_s_role_table','1480671291','',''),('date_update_s_rubrique','1481128866','',''),('fake_folder_param','p_','',''),('mail_host','localhost','',''),('mail_type','mail','',''),('nb_rows_before_search','100','',''),('next_cache_update','','',''),('param_key_sep','/','',''),('param_val_sep','-','','');
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
INSERT INTO `s_plugin` VALUES ('conferencier',1,0,1,'',0),('lexique',1,0,1,'',0),('mdp',1,0,1,'',0),('mendeleiev',1,0,1,'',0),('ocms_jstrad',1,0,1,'',0),('ocms_title',1,0,1,'',0),('o_blocs',1,0,1,'',0),('o_createplugin',1,0,1,'',0),('o_messages',1,0,1,'',0),('o_migration',1,0,1,'',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_role`
--

LOCK TABLES `s_role` WRITE;
/*!40000 ALTER TABLE `s_role` DISABLE KEYS */;
INSERT INTO `s_role` VALUES (1,'ADMINISTRATEUR'),(2,'Conférencier');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_role_table`
--

LOCK TABLES `s_role_table` WRITE;
/*!40000 ALTER TABLE `s_role_table` DISABLE KEYS */;
INSERT INTO `s_role_table` VALUES (1,'all',1,'all','',1,1,1,1,'',''),(2,'mediatheque',2,'all','',1,1,1,1,'','');
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
  `rubrique_url_en` varchar(255) NOT NULL,
  `rubrique_url_es` varchar(255) NOT NULL,
  `rubrique_titre_fr` varchar(255) NOT NULL DEFAULT '',
  `rubrique_titre_en` varchar(255) NOT NULL,
  `rubrique_titre_es` varchar(255) NOT NULL,
  `rubrique_titre_page_fr` varchar(255) NOT NULL,
  `rubrique_titre_page_en` varchar(255) NOT NULL,
  `rubrique_titre_page_es` varchar(255) NOT NULL,
  `rubrique_sous_titre_fr` varchar(255) NOT NULL DEFAULT '',
  `rubrique_sous_titre_en` varchar(255) NOT NULL,
  `rubrique_sous_titre_es` varchar(255) NOT NULL,
  `rubrique_texte_fr` text NOT NULL,
  `rubrique_texte_en` text NOT NULL,
  `rubrique_texte_es` text NOT NULL,
  `rubrique_keywords_fr` varchar(255) NOT NULL DEFAULT '',
  `rubrique_keywords_en` varchar(255) NOT NULL,
  `rubrique_keywords_es` varchar(255) NOT NULL,
  `rubrique_desc_fr` text NOT NULL,
  `rubrique_desc_en` text NOT NULL,
  `rubrique_desc_es` text NOT NULL,
  `rubrique_date_crea` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rubrique_date_modif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rubrique_date_publi` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rubrique_picto_fr` varchar(255) NOT NULL,
  `rubrique_picto_en` varchar(255) NOT NULL,
  `rubrique_picto_es` varchar(255) NOT NULL,
  `rubrique_type` enum('page','link','folder','siteroot','menuroot') NOT NULL DEFAULT 'page',
  `rubrique_link_fr` varchar(255) NOT NULL DEFAULT '',
  `rubrique_link_en` varchar(255) NOT NULL,
  `rubrique_link_es` varchar(255) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_rubrique`
--

LOCK TABLES `s_rubrique` WRITE;
/*!40000 ALTER TABLE `s_rubrique` DISABLE KEYS */;
INSERT INTO `s_rubrique` VALUES (1,1,NULL,'en_ligne',1,'0000-00-00 00:00:00','2016-12-01 16:02:25','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,NULL,2,'',';mdp-opixido-celio.c9users.io;','','','Monnaie de Paris','','','','','','','','','','','','','','','','','','2009-03-30 18:53:07','2009-03-30 18:54:54','2009-03-30 18:54:57','','','','siteroot','','','',0,0,'',''),(2,1,NULL,'brouillon',1,'2014-10-29 13:04:56','2016-12-01 15:02:22','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,NULL,2,'',';mdp-opixido-celio.c9users.io;','','','Monnaie de Paris','','','','','','','','','','','','','','','','','','2009-03-30 18:53:07','2009-03-30 18:54:54','2009-03-30 18:54:57','','','','siteroot','','','',0,0,'',''),(3,3,NULL,'brouillon',1,'2016-12-07 16:41:06','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,NULL,1,'','','','','','','','','','','','','','','','','','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','','','','page','','','',0,0,'','');
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
  `trad_en` text NOT NULL,
  `trad_es` text NOT NULL,
  `fk_plugin_id` varchar(64) NOT NULL,
  `fk_borne_id` int(11) NOT NULL,
  PRIMARY KEY (`trad_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_trad`
--

LOCK TABLES `s_trad` WRITE;
/*!40000 ALTER TABLE `s_trad` DISABLE KEYS */;
INSERT INTO `s_trad` VALUES ('base_title','Monnaie de Paris','','','',0),('cp_txt_home','Accueil','','','',0);
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

-- Dump completed on 2016-12-08 13:34:07
