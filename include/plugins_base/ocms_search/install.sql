
CREATE TABLE `os_obj` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `obj` varchar(32) NOT NULL default '',
  `fkid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `obj` (`obj`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `is_rel`
-- 

CREATE TABLE `os_rel` (
  `fkobj` mediumint(5) NOT NULL default '0',
  `fkword` mediumint(5) NOT NULL default '0',
  `nb` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fkobj`,`fkword`),
  KEY `nb` (`nb`),
  KEY `fkword` (`fkword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `is_word`
-- 

CREATE TABLE `os_word` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `word` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `os_recherches` (
  `recherche_id` int(10) unsigned NOT NULL auto_increment,
  `recherche_q` varchar(255) NOT NULL,
  `recherche_nbres` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`recherche_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `s_gabarit` ( `gabarit_id` , `gabarit_titre` , `gabarit_para_crea` , `gabarit_para_include` , `gabarit_full_template` , `gabarit_bdd_deco` , `gabarit_classe` , `gabarit_classe_param` , `gabarit_plugin` , `gabarit_index_table` , `gabarit_index_url` , `fk_default_rubrique_id` )
VALUES (
NULL , 'Moteur de recherche', '', '', '', '0', 'genOcmsSearch', '', 'ocms_search', '', '', '0'
);

CREATE TABLE `os_tables` (
`table_name` VARCHAR( 128 ) NOT NULL ,
`table_url` VARCHAR( 255 ) NOT NULL
) ;

ALTER TABLE `os_tables` ADD PRIMARY KEY ( `table_name` ) ;