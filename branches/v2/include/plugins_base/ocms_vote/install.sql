-- 
-- Structure de la table `plug_vote`
-- 

CREATE TABLE `plug_vote` (
  `ressource_table` varchar(64) NOT NULL,
  `fk_ressource_id` int(10) unsigned NOT NULL,
  `vote_moyenne` float(10,3) NOT NULL,
  `vote_nb` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`ressource_table`,`fk_ressource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `plug_vote_log`
-- 

CREATE TABLE `plug_vote_log` (
  `vote_log_ip` varchar(16) NOT NULL,
  `vote_log_table` varchar(64) NOT NULL,
  `vote_log_id` varchar(32) NOT NULL,
  `vote_log_time` int(11) NOT NULL,
  PRIMARY KEY  (`vote_log_ip`,`vote_log_table`,`vote_log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
