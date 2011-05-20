
CREATE TABLE IF NOT EXISTS `e_groupe` (
  `groupe_id` int(10) unsigned NOT NULL auto_increment,
  `groupe_nom` varchar(128) NOT NULL,
  `groupe_type` varchar(32) NOT NULL,
  `fk_groupe_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`groupe_id`),
  KEY `fk_groupe_id` (`fk_groupe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Structure de la table `e_groupe_contenu`
--

CREATE TABLE IF NOT EXISTS `e_groupe_contenu` (
  `fk_groupe_id` int(10) unsigned NOT NULL,
  `fk_table` varchar(64) NOT NULL,
  `fk_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_groupe_id`,`fk_table`,`fk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `e_utilisateur`
--

DROP TABLE IF EXISTS `e_utilisateur`;
CREATE TABLE `e_utilisateur` (
  `utilisateur_id` int(10) unsigned NOT NULL auto_increment,
  `utilisateur_email` varchar(128) NOT NULL,
  `utilisateur_pwd` varchar(255) NOT NULL,
  `utilisateur_date_connexion` datetime NOT NULL,
  `utilisateur_valide` tinyint(1) NOT NULL,
  `utilisateur_lg` varchar(3) NOT NULL,
  `utilisateur_login` varchar(255) NOT NULL,
  PRIMARY KEY  (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------

--
-- Structure de la table `e_utilisateur_contenu`
--

CREATE TABLE IF NOT EXISTS `e_utilisateur_contenu` (
  `fk_utilisateur_id` int(10) unsigned NOT NULL,
  `fk_table` varchar(64) NOT NULL,
  `fk_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_utilisateur_id`,`fk_table`,`fk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `e_utilisateur_groupe`
--

CREATE TABLE IF NOT EXISTS `e_utilisateur_groupe` (
  `fk_groupe_id` int(10) unsigned NOT NULL,
  `fk_utilisateur_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_groupe_id`,`fk_utilisateur_id`),
  KEY `fk_utilisateur_id` (`fk_utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables exportées
--


ALTER TABLE `s_rubrique` ADD `privee` TINYINT(1)  NOT NULL DEFAULT '0';