CREATE TABLE IF NOT EXISTS `p_download` (
  `download_id` int(10) unsigned NOT NULL auto_increment,
  `download_titre__[LG]` varchar(255) NOT NULL,
  `download_fichier_[LG]` varchar(255) NOT NULL,
  `download_ordre` smallint(6) NOT NULL,
  `fk_rubrique_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`download_id`),
  KEY `download_ordre` (`download_ordre`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;