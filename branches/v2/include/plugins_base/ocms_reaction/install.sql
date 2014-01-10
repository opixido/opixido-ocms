CREATE TABLE IF NOT EXISTS `plug_reaction` (
  `reaction_id` int(10) unsigned NOT NULL auto_increment,
  `reaction_date` datetime NOT NULL,
  `fk_obj` varchar(64) NOT NULL,
  `fk_id` int(11) NOT NULL,
  `reaction_nom` varchar(128) NOT NULL,
  `reaction_email` varchar(128) NOT NULL,
  `reaction_comment` text NOT NULL,
  `en_ligne` tinyint(1) NOT NULL,
  PRIMARY KEY  (`reaction_id`),
  KEY `reaction_date` (`reaction_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
