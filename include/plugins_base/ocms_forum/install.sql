
CREATE TABLE IF NOT EXISTS `forum_message` (
  `message_id` int(10) unsigned NOT NULL auto_increment,
  `message_date` datetime NOT NULL,
  `fk_utilisateur_id` int(10) unsigned NOT NULL,
  `fk_rubrique_id` int(10) unsigned NOT NULL,
  `fk_message_id` int(10) unsigned NOT NULL,
  `fk_root_id` int(10) unsigned NOT NULL,
  `message_titre` varchar(255) NOT NULL,
  `message_texte` text NOT NULL,
  `message_pj` varchar(255) NOT NULL,
  `message_vues` int(10) unsigned NOT NULL,
  `en_ligne` tinyint(4) NOT NULL,
  `message_ip` varchar(16) NOT NULL,
  `message_type` enum('message','sticky','important') NOT NULL default 'message',
  `message_clos` tinyint(1) NOT NULL,
  PRIMARY KEY  (`message_id`),
  KEY `message_date` (`message_date`),
  KEY `fk_utilisateur_id` (`fk_utilisateur_id`),
  KEY `fk_theme_id` (`fk_rubrique_id`),
  KEY `fk_message_id` (`fk_message_id`),
  KEY `fk_root_id` (`fk_root_id`),
  KEY `message_titre` (`message_titre`),
  KEY `en_ligne` (`en_ligne`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_report`
--

CREATE TABLE IF NOT EXISTS `forum_report` (
  `fk_utilisateur_id` int(10) unsigned NOT NULL,
  `fk_message_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_utilisateur_id`,`fk_message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `forum_user`
--

CREATE TABLE IF NOT EXISTS `forum_user` (
  `forum_user_id` int(10) unsigned NOT NULL auto_increment,
  `fk_utilisateur_id` int(10) unsigned NOT NULL,
  `forum_user_nom` varchar(255) NOT NULL,
  `forum_user_prenom` varchar(255) NOT NULL,
  `forum_user_avatar` varchar(255) NOT NULL,
  `forum_user_signature` text NOT NULL,
  `forum_user_pays` int(11) NOT NULL,
  `forum_user_age` date NOT NULL,
  `forum_modo` tinyint(1) NOT NULL,
  PRIMARY KEY  (`forum_user_id`),
  KEY `fk_utilisateur_id` (`fk_utilisateur_id`),
  KEY `forum_user_pays` (`forum_user_pays`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_watch`
--

CREATE TABLE IF NOT EXISTS `forum_watch` (
  `fk_utilisateur_id` int(10) unsigned NOT NULL,
  `fk_forum_message_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_utilisateur_id`,`fk_forum_message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




INSERT INTO `s_gabarit` (`gabarit_id`, `gabarit_titre`, `gabarit_para_crea`, `gabarit_para_include`, `gabarit_full_template`, `gabarit_bdd_deco`, `gabarit_classe`, `gabarit_classe_param`, `gabarit_plugin`, `gabarit_index_table`, `gabarit_index_url`, `fk_default_rubrique_id`) VALUES
(15, 'Forum - Home', '', '', '', 0, 'genForumListe', '', 'ocms_forum', '', '', 0),
(16, 'Forum - Theme', '', '', '', 0, 'genForumTheme', '', 'ocms_forum', 'forum_message', 'php:return forumMessage::getUrl($row);', 0),
(18, 'Forum - Page', '', '', '', 0, 'genForumPage', '', 'ocms_forum', '', '', 0);
