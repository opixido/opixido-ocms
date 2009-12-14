
-- --------------------------------------------------------

-- 
-- Structure de la table `s_admin`
-- 

CREATE TABLE IF NOT EXISTS  `s_admin` (
  `admin_id` smallint(5) unsigned NOT NULL auto_increment,
  `admin_login` varchar(64) NOT NULL default '',
  `admin_nom` varchar(255) NOT NULL default '',
  `admin_pwd` varchar(255) NOT NULL default '',
  `admin_email` varchar(255) NOT NULL default '',
  `admin_type` enum('redacteur','valideur','administrateur') NOT NULL default 'redacteur',
  `admin_last_cx` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`admin_id`),
  UNIQUE KEY `admin_login` (`admin_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

 ALTER TABLE `s_admin` ADD INDEX ( `admin_login` )  ;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_admin_role`
-- 

CREATE TABLE IF NOT EXISTS    `s_admin_role` (
  `fk_admin_id` int(10) unsigned NOT NULL default '0',
  `fk_role_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fk_admin_id`,`fk_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_admin_rows`
-- 

CREATE TABLE IF NOT EXISTS  `s_admin_rows` (
  `fk_admin_id` int(10) unsigned NOT NULL default '0',
  `fk_row_id` int(10) unsigned NOT NULL default '0',
  `fk_table` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`fk_admin_id`,`fk_row_id`,`fk_table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


-- --------------------------------------------------------

-- 
-- Structure de la table `s_groupe`
-- 

CREATE TABLE IF NOT EXISTS  `s_groupe` (
  `groupe_id` int(10) unsigned NOT NULL auto_increment,
  `groupe_nom` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`groupe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_image`
-- 

CREATE TABLE IF NOT EXISTS  `s_image` (
  `image_id` int(11) NOT NULL auto_increment,
  `image_img` varchar(255) NOT NULL default '',
  `image_titre_[LG]` varchar(255) NOT NULL default '',
  
  `image_legende_[LG]` varchar(255) NOT NULL default '',
  
  `image_copyright` varchar(255) NOT NULL default '',
  `image_lien_[LG]` varchar(255) default '',
  
  PRIMARY KEY  (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_langue`
-- 

CREATE TABLE IF NOT EXISTS  `s_langue` (
  `langue_nom` varchar(64) NOT NULL default '',
  `langue_id` varchar(3) NOT NULL default '',
  PRIMARY KEY  (`langue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_lock`
-- 

CREATE TABLE IF NOT EXISTS  `s_lock` (
  `fk_admin_id` smallint(5) unsigned NOT NULL default '0',
  `lock_table` varchar(64) NOT NULL default '',
  `lock_id` int(10) unsigned NOT NULL default '0',
  `lock_time` bigint(12) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lock_table`,`lock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 ALTER TABLE `s_lock` ADD INDEX ( `fk_admin_id` )  ;
  ALTER TABLE `s_lock` ADD INDEX ( `lock_time` )  ;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_log_action`
-- 

CREATE TABLE IF NOT EXISTS  `s_log_action` (
  `log_action_id` int(10) unsigned NOT NULL auto_increment,
  `fk_admin_id` smallint(5) unsigned NOT NULL default '0',
  `log_action_table` varchar(128) NOT NULL default '',
  `log_action_fk_id` varchar(255) NOT NULL default '0',
  `log_action_action` varchar(128) NOT NULL default '',
  `log_action_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`log_action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;
 ALTER TABLE `s_log_action` ADD INDEX ( `fk_admin_id` )  ;
  ALTER TABLE `s_log_action` ADD INDEX ( `log_action_table` )  ;
   ALTER TABLE `s_log_action` ADD INDEX ( `log_action_fk_id` )  ;
    ALTER TABLE `s_log_action` ADD INDEX ( `log_action_time` )  ;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_para_type`
-- 

CREATE TABLE IF NOT EXISTS  `s_para_type` (
  `para_type_id` smallint(5) unsigned NOT NULL auto_increment,
  `para_type_titre` varchar(128) NOT NULL default '',
  `para_type_template` text NOT NULL,
  `para_type_template_popup` text NOT NULL,
  `para_type_vignette` varchar(255) NOT NULL default '',
  `para_type_use_img` tinyint(1) NOT NULL default '0',
  `para_type_use_file` tinyint(1) NOT NULL default '0',
  `para_type_use_table` tinyint(1) NOT NULL default '0',
  `para_type_use_txt` tinyint(1) NOT NULL default '1',
  `para_type_use_link` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`para_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_paragraphe`
-- 

CREATE TABLE IF NOT EXISTS  `s_paragraphe` (
  `paragraphe_id` bigint(20) unsigned NOT NULL auto_increment,
  `fk_rubrique_id` int(10) unsigned default NULL,
  `fk_para_type_id` smallint(5) unsigned default NULL,
  `paragraphe_ordre` tinyint(3) unsigned NOT NULL default '0',
  `paragraphe_titre_[LG]` varchar(255) NOT NULL default '',
  
  `paragraphe_contenu_[LG]` text NOT NULL,
  
  `paragraphe_contenu_csv_[LG]` text NOT NULL,
  
  `paragraphe_img_1_[LG]` varchar(255) NOT NULL default '',
  
  `paragraphe_img_2_[LG]` varchar(255) NOT NULL default '',
  
  `paragraphe_img_1_alt_[LG]` varchar(80) NOT NULL default '',
  
  `paragraphe_img_2_alt_[LG]` varchar(80) NOT NULL default '',
  
  `paragraphe_params_[LG]` varchar(255) default NULL,
  
  `paragraphe_file_1_[LG]` varchar(255) NOT NULL default '',
  
  `paragraphe_file_1_legend_[LG]` varchar(255) NOT NULL default '',
  
  `paragraphe_link_1_[LG]` varchar(255) NOT NULL default '',
  
  `paragraphe_img_1_copyright` varchar(80) NOT NULL default '',
  `paragraphe_img_2_copyright` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`paragraphe_id`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `paragraphe_ordre` (`paragraphe_ordre`),
  KEY `fk_para_type_id` (`fk_para_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_plugin`
-- 

CREATE TABLE IF NOT EXISTS  `s_plugin` (
  `plugin_nom` varchar(64) NOT NULL default '',
  `plugin_actif` tinyint(1) NOT NULL default '0',
  `plugin_ordre` smallint(5) unsigned NOT NULL default '0',
  `plugin_installe` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`plugin_nom`),
  KEY `plugin_actif` (`plugin_actif`),
  KEY `plugin_ordre` (`plugin_ordre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;
 ALTER TABLE `s_plugin` ADD INDEX ( `plugin_installe` )  ;
 
-- --------------------------------------------------------

-- 
-- Structure de la table `s_role`
-- 

CREATE TABLE IF NOT EXISTS  `s_role` (
  `role_id` int(10) unsigned NOT NULL auto_increment,
  `role_nom` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_role_table`
-- 

CREATE TABLE IF NOT EXISTS  `s_role_table` (
  `role_table_id` int(10) unsigned NOT NULL auto_increment,
  `role_table_table` varchar(64) NOT NULL default '',
  `fk_role_id` int(10) unsigned NOT NULL default '0',
  `role_table_type` enum('all','per_user','specific') NOT NULL default 'all',
  `role_table_specific` varchar(128) NOT NULL default '',
  `role_table_view` tinyint(1) NOT NULL default '0',
  `role_table_add` tinyint(1) NOT NULL default '0',
  `role_table_edit` tinyint(1) NOT NULL default '0',
  `role_table_delete` tinyint(1) NOT NULL default '0',
  `role_table_actions` varchar(255) NOT NULL default '',
  `role_table_champs` text NOT NULL,
  PRIMARY KEY  (`role_table_id`),
  KEY `fk_role_id` (`fk_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

-- --------------------------------------------------------


-- 
-- Structure de la table `s_traduction`
-- 

CREATE TABLE IF NOT EXISTS  `s_traduction` (
  `fk_table` varchar(32) NOT NULL default '',
  `fk_id` int(10) unsigned NOT NULL default '0',
  `fk_champ` varchar(64) NOT NULL default '',
  `fk_langue_id` varchar(3) NOT NULL default '0',
  `traduction_texte` text NOT NULL,
  PRIMARY KEY  (`fk_table`,`fk_id`,`fk_champ`,`fk_langue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_utilisateur`
-- 

CREATE TABLE IF NOT EXISTS  `s_utilisateur` (
  `utilisateur_id` int(10) unsigned NOT NULL auto_increment,
  `utilisateur_civilite` enum('Mme','Mlle','Mr') NOT NULL default 'Mme',
  `utilisateur_nom` varchar(128) NOT NULL default '',
  `utilisateur_prenom` varchar(128) NOT NULL default '',
  `utilisateur_email` varchar(255) NOT NULL default '',
  `utilisateur_pwd` varchar(255) NOT NULL default '',
  `utilisateur_organisme` varchar(255) NOT NULL default '',
  `utilisateur_adresse` varchar(255) NOT NULL default '',
  `utilisateur_ville` varchar(64) NOT NULL default '',
  `utilisateur_cp` varchar(10) NOT NULL default '',
  `utilisateur_pays` varchar(64) NOT NULL default '',
  `utilisateur_tel` varchar(64) NOT NULL default '',
  `utilisateur_valide` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`utilisateur_id`),
  KEY `utilisateur_valide` (`utilisateur_valide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;


-- 
-- Structure de la table `s_admin_trad`
-- 

DROP TABLE IF EXISTS `s_admin_trad`;
CREATE TABLE `s_admin_trad` (
  `admin_trad_id` varchar(128) NOT NULL default '',
  `admin_trad_[LG]` text NOT NULL,
  `admin_trad_en` text NOT NULL,
  `fk_plugin_id` varchar(64) NOT NULL,
  PRIMARY KEY  (`admin_trad_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_gabarit`
-- 

DROP TABLE IF EXISTS `s_gabarit`;
CREATE TABLE `s_gabarit` (
  `gabarit_id` smallint(5) unsigned NOT NULL auto_increment,
  `gabarit_titre` varchar(128) NOT NULL default '',
  `gabarit_para_crea` varchar(255) NOT NULL default '',
  `gabarit_para_include` varchar(64) NOT NULL default '',
  `gabarit_full_template` longtext NOT NULL,
  `gabarit_bdd_deco` tinyint(1) NOT NULL default '0',
  `gabarit_classe` varchar(64) NOT NULL default '',
  `gabarit_classe_param` varchar(255) NOT NULL default '',
  `gabarit_plugin` varchar(128) NOT NULL,
  `gabarit_index_table` varchar(64) NOT NULL default '',
  `gabarit_index_url` varchar(255) NOT NULL default '',
  `fk_default_rubrique_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`gabarit_id`),
  KEY `fk_default_rubrique_id` (`fk_default_rubrique_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 ALTER TABLE `s_gabarit` ADD INDEX ( `gabarit_classe` )  ;
  ALTER TABLE `s_gabarit` ADD INDEX ( `gabarit_classe_param` )  ;
  
  
-- --------------------------------------------------------

-- 
-- Structure de la table `s_param`
-- 

DROP TABLE IF EXISTS `s_param`;
CREATE TABLE `s_param` (
  `param_id` varchar(64) NOT NULL default '',
  `param_valeur` text NOT NULL,
  `param_description` text NOT NULL,
  `fk_plugin_id` varchar(64) NOT NULL,
  PRIMARY KEY  (`param_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_rubrique`
-- 

DROP TABLE IF EXISTS `s_rubrique`;
CREATE TABLE `s_rubrique` (
  `rubrique_id` int(10) unsigned NOT NULL auto_increment,
  `fk_rubrique_id` int(10) unsigned default NULL,
  `fk_rubrique_version_id` int(10) unsigned default NULL,
  `rubrique_ordre` tinyint(3) unsigned NOT NULL default '0',
  `rubrique_etat` enum('redaction','attente','en_ligne') NOT NULL default 'redaction',
  `rubrique_gabarit_param` varchar(255) NOT NULL default '',
  `fk_gabarit_id` smallint(5) unsigned default NULL,
  `fk_creator_id` smallint(5) unsigned default NULL,
  `rubrique_url_[LG]` varchar(255) NOT NULL default '',  
  `rubrique_titre_[LG]` varchar(255) NOT NULL default '',  
  `rubrique_sous_titre_[LG]` varchar(255) NOT NULL default '',  
  `rubrique_texte_[LG]` text NOT NULL,  
  `rubrique_keywords_[LG]` varchar(255) NOT NULL default '',  
  `rubrique_desc_[LG]` text NOT NULL,  
  `rubrique_date_crea` datetime NOT NULL default '0000-00-00 00:00:00',
  `rubrique_date_modif` datetime NOT NULL default '0000-00-00 00:00:00',
  `rubrique_date_publi` datetime NOT NULL default '0000-00-00 00:00:00',
  `rubrique_type` enum('page','link','folder','siteroot','menuroot') NOT NULL default 'page',
  `rubrique_link_[LG]` varchar(255) NOT NULL default '',  
  `rubrique_dyntitle` tinyint(1) NOT NULL default '0',
  `rubrique_dynvisibility` tinyint(1) NOT NULL default '0',
  `rubrique_template` varchar(255) NOT NULL default '',
  `rubrique_option` SET( 'dynVisibility', 'dynTitle', 'dynSubRubs' ) NOT NULL,
  PRIMARY KEY  (`rubrique_id`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`,`fk_rubrique_version_id`),
  KEY `fk_gabarit_id` (`fk_gabarit_id`),
  KEY `fk_creator_id` (`fk_creator_id`),
  KEY `rubrique_ordre` (`rubrique_ordre`),
  KEY `fk_rubrique_version_id` (`fk_rubrique_version_id`),
  KEY `rubrique_en_ligne` (`rubrique_etat`),
  KEY `rubrique_dyntitle` (`rubrique_dyntitle`),
  KEY `rubrique_url_[LG]` (`rubrique_url_[LG]`),
  
  KEY `rubrique_dynvisibility` (`rubrique_dynvisibility`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `s_trad`
-- 

DROP TABLE IF EXISTS `s_trad`;
CREATE TABLE `s_trad` (
  `trad_id` varchar(64) NOT NULL default '',
  `trad_[LG]` text NOT NULL,  
  `fk_plugin_id` varchar(64) NOT NULL,
  PRIMARY KEY  (`trad_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

REPLACE INTO `s_trad` VALUES ('cp_txt_home', 'Accueil', '');
REPLACE INTO `s_trad` (trad_id,trad_[LG]) VALUES ('mail_from', 'nepas@repondre.com');
REPLACE INTO `s_trad` (trad_id,trad_[LG])  VALUES ('mail_from_name', 'Ne pas repondre');


REPLACE INTO `s_param` VALUES ('cache_path', 'cache', '', '');
REPLACE INTO `s_param` VALUES ('date_update_arbo', '1178101994', '', '');
REPLACE INTO `s_param` VALUES ('fake_folder_param', 'bdd', '', '');
REPLACE INTO `s_param` VALUES ('nb_rows_before_search', '100', '', '');
REPLACE INTO `s_param` VALUES ('mail_type', 'mail', '', '');
REPLACE INTO `s_param` VALUES ('mail_host', 'localhost', '', '');


REPLACE INTO `s_admin_trad` VALUES ('droits_insuffisants_pour_cette_action', 'Vous n''avez pas les droits nécessaires pour effectuer cette action', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_pas_en_ligne', 'Cette rubrique est déjà masquée', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_refuser_ok', 'Votre refus de validation a bien été pris en compte', '','');
REPLACE INTO `s_admin_trad` VALUES ('refuse', 'Refuser la validation', '','');
REPLACE INTO `s_admin_trad` VALUES ('unvalidate', 'Masquer', '','');
REPLACE INTO `s_admin_trad` VALUES ('save_and_close', 'Retour', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_refuse', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-undo.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_light_edit', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/applications-graphics.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_modification', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_voir_version_en_ligne', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-template.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_voir_version_modifiable', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-properties.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_calendrier', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/office-calendar.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_random_password', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/preferences-system-session.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_locked', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/locked.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_importftp', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-symbolic-link.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_copy', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/places/start-here.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('generate_random_password', 'Générer un mot de passe', '','');
REPLACE INTO `s_admin_trad` VALUES ('erreur_lock_existe', '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/locked.png" alt="" /> Une autre personne est déjà <br/>en train de modifier cet élément', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_admin_p_0', 'Informations', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_admin_p_1', 'Rubriques autorisées', '','');
REPLACE INTO `s_admin_trad` VALUES ('table_log_action', 'Dernières opérations effectuées', '','');
REPLACE INTO `s_admin_trad` VALUES ('modification', 'Modifier', '','');
REPLACE INTO `s_admin_trad` VALUES ('empty_field', 'Champ vide', '','');
REPLACE INTO `s_admin_trad` VALUES ('ajout_sub_rub', 'Nouveau', '','');
REPLACE INTO `s_admin_trad` VALUES ('invisible_rub', ' <span style="color:red">[Masquée]</span>', '','');
REPLACE INTO `s_admin_trad` VALUES ('version_masquee', ' Cette rubrique est actuellement masquée', '','');
REPLACE INTO `s_admin_trad` VALUES ('suppression_ok', 'Suppression effectuée pour : ', '','');
REPLACE INTO `s_admin_trad` VALUES ('modification_ok', 'Changements enregistrés pour : ', '','');
REPLACE INTO `s_admin_trad` VALUES ('ajout_ok', 'correctement ajouté', '','');
REPLACE INTO `s_admin_trad` VALUES ('connexion', 'Connexion ... ', '','');
REPLACE INTO `s_admin_trad` VALUES ('username', 'Nom d''utilisateur', '','');
REPLACE INTO `s_admin_trad` VALUES ('password', 'Mot de passe', '','');
REPLACE INTO `s_admin_trad` VALUES ('save', 'Enregistrer', '','');
REPLACE INTO `s_admin_trad` VALUES ('cancel', 'Annuler', '','');
REPLACE INTO `s_admin_trad` VALUES ('delete', 'Supprimer', '','');
REPLACE INTO `s_admin_trad` VALUES ('search_id', 'Rechercher sur un identifiant : ', '','');
REPLACE INTO `s_admin_trad` VALUES ('search_txt', 'Extrait de texte &agrave; rechercher ', '','');
REPLACE INTO `s_admin_trad` VALUES ('choose_item', 'Choisissez un &eacute;l&eacute;ment &agrave; modifier', '','');
REPLACE INTO `s_admin_trad` VALUES ('rechercher', 'Lancer la recherche', '','');
REPLACE INTO `s_admin_trad` VALUES ('confirm_suppr', 'Voulez vous vraiment supprimer cet enregistrement ', '','');
REPLACE INTO `s_admin_trad` VALUES ('ajouter', 'Cliquez sur cet icone pour ajouter un &eacute;l&eacute;ment &agrave; la liste : ', '','');
REPLACE INTO `s_admin_trad` VALUES ('ajouter_elem', 'Ajouter un &eacute;l&eacute;ment', '','');
REPLACE INTO `s_admin_trad` VALUES ('menu', 'Menu', '','');
REPLACE INTO `s_admin_trad` VALUES ('logout', 'D&eacute;connexion', '','');
REPLACE INTO `s_admin_trad` VALUES ('menu1', 'Gestion de la base', '','');
REPLACE INTO `s_admin_trad` VALUES ('menu2', ' ', '','');
REPLACE INTO `s_admin_trad` VALUES ('retour', ' retour', '','');
REPLACE INTO `s_admin_trad` VALUES ('selectable_items', 'El&eacute;ments s&eacute;lectionnables', '','');
REPLACE INTO `s_admin_trad` VALUES ('selected_items', 'El&eacute;ments s&eacute;lectionn&eacute;s', '','');
REPLACE INTO `s_admin_trad` VALUES ('modifier', 'Modifier', '','');
REPLACE INTO `s_admin_trad` VALUES ('modify_item', 'Modifier un &eacute;l&eacute;ment', '','');
REPLACE INTO `s_admin_trad` VALUES ('date_picker', 'Choisissez la date', '','');
REPLACE INTO `s_admin_trad` VALUES ('mal_remplit', 'Vous avez mal rempli les champs suivants :', '','');
REPLACE INTO `s_admin_trad` VALUES ('etes_vous_sur_action', 'Souhaitez vous vraiment effectuer cette action ?', '','');
REPLACE INTO `s_admin_trad` VALUES ('help_tablerel', '<b>Champ Multicritère partagés</b><br/>Vous avez la possibilité de choisir plusieurs éléments.<br/><br/>Vous devez sélectionner l''élément voulu dans la colonne de gauche, puis cliquer sur la flêche orientée vers la droite pour le faire passer dans la liste des éléments sélectionnés', '','');
REPLACE INTO `s_admin_trad` VALUES ('help_rte', '<b>Champ Wyziwyg</b><br/>Vous avez la possibilité de mettre ce texte en forme via un outil graphique. Pour accéder au texte, cliquez sur l''icône d''édition à gauche ', '','');
REPLACE INTO `s_admin_trad` VALUES ('help_upload_table', '<b>Champ de mise en ligne de tableau CSV</b><br/>Vous avez la possibilité de parcourir le disque de votre ordinateur, et d''envoyer un fichier Excel (format CSV) pour remplir ce champ par un tableau de données', '','');
REPLACE INTO `s_admin_trad` VALUES ('upload_table', 'Mettre en ligne un tableau (CSV)', '','');
REPLACE INTO `s_admin_trad` VALUES ('champ_vide', 'Ce champ est actuellement vide', '','');
REPLACE INTO `s_admin_trad` VALUES ('help_texte', '<b>Champ Texte</b><br/>Cliquez dans le champ puis saisissez ou bien copiez/collez votre texte.', '','');
REPLACE INTO `s_admin_trad` VALUES ('help_relinv_table', '<b>Eléments liés</b><br/>Vous avez ci-contre la liste des éléments liés.<br/><br/> Sélectionnez un élément puis cliquez sur l''icône d''édition pour le modifier ', '','');
REPLACE INTO `s_admin_trad` VALUES ('help_relation', '<b>Sélection simple</b><br/>Vous avez la possibilité de sélectionner un élément dans cette liste ', '','');
REPLACE INTO `s_admin_trad` VALUES ('help_file', '<b>Mise en ligne de fichier</b><br/>Pour mettre en ligne un fichier, cliquez sur le bouton <i>PARCOURIR</i>, puis sélectionnez le fichier voulu sur votre ordinateur.<br/>Ensuite cliquez sur le bouton <i>Mettre en ligne</i>, pour envoyer le fichier sur le serveur.<br/><br/>Si un fichier existe déjà, vous pouvez refaire la procédure ci-dessus pour le mettre à jour, mais l''ancienne versin sera supprimée<br/><br/>Vous pouvez également supprimer le fichier sans le remplacer en cliquant sur le bouton <i>Supprimer</i>', '','');
REPLACE INTO `s_admin_trad` VALUES ('aucun_element', 'Il n''y a aucun élément dans cette liste', '','');
REPLACE INTO `s_admin_trad` VALUES ('select_rub_below', 'Sélectionnez une page ci-dessous', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_valider_ok', 'Cette rubrique a bien été validée. Vos modifications sont désormais visibles par les internautes ', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_demande_valider_ok', 'Votre demande de validation a bien été transmise à : ', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_devalider_ok', 'Cette rubrique a bien été masquée.<br/>Les internautes n''y ont plus accès', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_editer', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_edit', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_voir', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_view', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_new', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/list-add.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_preview', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/image-x-generic.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_droite', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-last.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_gauche', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-first.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_help', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/apps/help-browser.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_logout', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-log-out.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_NewsletterPreview', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-news-reader.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_NewsletterFakeSend', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/evolution.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_NewsletterSend', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-mail.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_ask_for_validation', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/system-software-update.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_cancel', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-undo.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_delete', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/process-stop.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_del', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/process-stop.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_validate', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_validateVersion', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_showObject', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_unvalidate', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_hideVersion', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_hideObject', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_upload', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/x-directory-remote-workgroup.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_up', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-up.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_down', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-down.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_date', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-down.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_SiteValidate', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_PublicationValidate', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_SiteHide', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_PublicationHide', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_back', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-previous.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_first', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-previous.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_save', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-save.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_saveas', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-saveas.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_search', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-search.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_desktop', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/places/user-home.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_close', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_message_info', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/dialog-information.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_message_error', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/dialog-warning.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_undo', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-undo.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_go', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-find.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_InstallPlugin', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/install.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_UninstallPlugin', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/uninstall.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('search', 'Rechercher', '','');
REPLACE INTO `s_admin_trad` VALUES ('preview', 'Pré-visualiser', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_paragraphe_titre_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_paragraphe_titre_[LG]', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_rubrique_titre_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_rubrique_titre_[LG]', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_r_rubrique_lexique', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/x-office-address-book.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_fk_liens_id', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/mail-attachment.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_fk_paragraphe_id', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/format-justify-left.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_rubrique_desc_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_rubrique_desc_[LG]', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_rubrique_keywords_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_rubrique_keywords_[LG]', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_rubrique_gabarit_param', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_fk_gabarit_id', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_fk_para_type_id', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_paragraphe_img_1_[LG]', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/image-x-generic.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_img_paragraphe_img_1_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/image-x-generic.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('field_help_rubrique_titre_en', '<b>Titre de la page</b><br/>Il apparaitra dans les menus de navigation,<br/> la barre de titre du navigateur,<br/> et les résultats du moteur de recherche', '','');

REPLACE INTO `s_admin_trad` VALUES ('field_help_rubrique_titre_[LG]', '<b>Titre de la page</b><br/>Il apparaitra dans les menus de navigation,<br/> la barre de titre du navigateur,<br/> et les résultats du moteur de recherche', '','');

REPLACE INTO `s_admin_trad` VALUES ('field_help_fk_paragraphe_id', '<b>Paragraphes de cete page</b><br/>Votre page est constituée de plusieurs paragraphes. <br/><br/>Vous pouvez voir ci-dessous les paragraphes déjà créés, et cliquer sur l''icône en début de ligne pour les modifier indépendement', '','');

REPLACE INTO `s_admin_trad` VALUES ('paragraphe_titre_[LG]', 'Titre en Français', '','');
REPLACE INTO `s_admin_trad` VALUES ('paragraphe_titre_en', 'Titre en Anglais', '','');
REPLACE INTO `s_admin_trad` VALUES ('paragraphe_contenu_[LG]', 'Contenu en Français', '','');
REPLACE INTO `s_admin_trad` VALUES ('paragraphe_contenu_en', 'Contenu en Anglais', '','');
REPLACE INTO `s_admin_trad` VALUES ('paragraphe_img_1_[LG]', 'Image d''accompagnement Française', '','');
REPLACE INTO `s_admin_trad` VALUES ('paragraphe_img_1_en', 'Image d''accompagnement Anglaise', '','');
REPLACE INTO `s_admin_trad` VALUES ('paragraphe_img_1_alt_[LG]', 'Description Française de l''image ci-dessus', '','');
REPLACE INTO `s_admin_trad` VALUES ('paragraphe_img_1_alt_en', 'Description Anglaise de l''image ci-dessus', '','');


REPLACE INTO `s_admin_trad` VALUES ('max_size', 'Taille max. ', '','');
REPLACE INTO `s_admin_trad` VALUES ('tester_le_lien', 'Tester le lien dans une popup', '','');
REPLACE INTO `s_admin_trad` VALUES ('choisir_rubrique', 'Choisir une page dans le site', '','');
REPLACE INTO `s_admin_trad` VALUES ('choisir_rubrique_ci_dessous', 'Cliquez sur une rubrique ci-dessous', '','');
REPLACE INTO `s_admin_trad` VALUES ('voir', '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png"  alt="Voir" />', '','');
REPLACE INTO `s_admin_trad` VALUES ('editer', '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png"  alt="Editer" />', '','');

REPLACE INTO `s_admin_trad` VALUES ('access_direct', 'Accès direct ', '','');
REPLACE INTO `s_admin_trad` VALUES ('mettre_en_ligne', 'Mettre en ligne', '','');
REPLACE INTO `s_admin_trad` VALUES ('non_renseigne', 'Non renseign&eacute;', '','');
REPLACE INTO `s_admin_trad` VALUES ('exporter', 'Exporter la table au format CSV', '','');
REPLACE INTO `s_admin_trad` VALUES ('vider', 'Vider le contenu de la table', '','');
REPLACE INTO `s_admin_trad` VALUES ('etes_vous_sur', 'Etes vous sur de vouloir vider cette table ? ', '','');
REPLACE INTO `s_admin_trad` VALUES ('il_y_a', 'Il y a ', '','');
REPLACE INTO `s_admin_trad` VALUES ('resultats', 'R&eacute;sultat(s)', '','');
REPLACE INTO `s_admin_trad` VALUES ('recherche_textuelle', 'Recherche plein texte', '','');
REPLACE INTO `s_admin_trad` VALUES ('recherche_avancee', 'Recherche avanc&eacute;e ', '','');
REPLACE INTO `s_admin_trad` VALUES ('fichier_uploader', 'Mettre en ligne un fichier', '','');
REPLACE INTO `s_admin_trad` VALUES ('fichier_remplacer', 'Remplacer ce fichier par : ', '','');
REPLACE INTO `s_admin_trad` VALUES ('supprimer_fichier', 'Voulez vous vraiment supprimer ce fichier du serveur ?', '','');
REPLACE INTO `s_admin_trad` VALUES ('impossible_supprimer_repertoire', 'Le r&eacute;pertoire contentant les fichiers de cet &eacute;l&eacute;ment n''a pas pu etre supprim&eacute;', '','');
REPLACE INTO `s_admin_trad` VALUES ('impossible_supprimer', 'Ce fichier n''a pas pu &agrave;tre supprim&eacute; : ', '','');
REPLACE INTO `s_admin_trad` VALUES ('editer_wysiwyg', 'Cliquez ici pour acc&eacute;der &agrave; l''&eacute;diteur WYSIWYG complet pour mettre en forme ce champ', '','');
REPLACE INTO `s_admin_trad` VALUES ('voir_wysiwyg', 'Cliquez ici pour voir le contenu de ce champ dans une nouvelle fen&ecirc;tre', '','');
REPLACE INTO `s_admin_trad` VALUES ('edit', 'Cliquez ici pour modifier cet &eacute;l&eacute;ment ', '','');
REPLACE INTO `s_admin_trad` VALUES ('edis_image', '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-properties.png" border="0" alt="Editer" /> Retailler, recadrer, optimiser ... cette image', '','');
REPLACE INTO `s_admin_trad` VALUES ('deja_fichier', 'Fichier existant', '','');
REPLACE INTO `s_admin_trad` VALUES ('menu_0', 'Page d''accueil', '','');
REPLACE INTO `s_admin_trad` VALUES ('menu_1', 'Lexique', '','');
REPLACE INTO `s_admin_trad` VALUES ('menu_2', 'Param&egrave;tres techniques', '','');
REPLACE INTO `s_admin_trad` VALUES ('t_event_p_0', 'Evènement Agenda', '','');
REPLACE INTO `s_admin_trad` VALUES ('t_action_p_0', 'Actions', '','');
REPLACE INTO `s_admin_trad` VALUES ('t_link_p_0', 'Lien', '','');
REPLACE INTO `s_admin_trad` VALUES ('t_bloc_home_p_0', 'Bloc Page d''accueil', '','');
REPLACE INTO `s_admin_trad` VALUES ('arborescence', 'Rubriques', '','');
REPLACE INTO `s_admin_trad` VALUES ('getup', 'Monter cette ligne', '','');
REPLACE INTO `s_admin_trad` VALUES ('getdown', 'Descendre cette ligne', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_meta_keyw_[LG]', 'Mots clefs d&eacute;finissants la page (FR) (1000 char)', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_meta_keyw_en', 'Mots clefs d&eacute;finissants la page (EN) (1000 char)', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_meta_desc_[LG]', 'Description de la page (FR) (255 char max)', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_meta_desc_en', 'Description de la page (EN) (255 char max)', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_visible', 'Afficher cette rubrique', '','');
REPLACE INTO `s_admin_trad` VALUES ('fk_paragraphe_id', 'Liste des paragraphes constituants cette page', '','');
REPLACE INTO `s_admin_trad` VALUES ('fk_para_type_id', 'Type de paragraphe', '','');
REPLACE INTO `s_admin_trad` VALUES ('fk_gabarit_id', 'Gabarit Sp&eacute;cial', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_gabarit_param', 'Param&egrave;tre technique du gabarit', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_pos_box', 'BoÃ®te contextuelles align&eacute;es en haut de la page', '','');
REPLACE INTO `s_admin_trad` VALUES ('meta_informations', 'M&eacute;ta informations', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_keywords_[LG]', 'Mots clefs FR (255 chars)', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_keywords_en', 'Mots clefs EN(255 chars)', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_desc_[LG]', 'Description courte de la page FR', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_desc_en', 'Description courte de la page EN', '','');

REPLACE INTO `s_admin_trad` VALUES ('desc_meta', 'Ces informations ne seront pas visible dans la page, mais serviront au bon r&eacute;f&eacute;rencement de cette page.<br/>La description peut apparaitre dans la liste des r&eacute;sultats du moteur de recherche', '','');

REPLACE INTO `s_admin_trad` VALUES ('s_param', 'Param&egrave;tres du site', '','');
REPLACE INTO `s_admin_trad` VALUES ('url_name', 'Titre pour le lien', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique', 'Rubriques du site', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_param_p_0', 'Param&egrave;tre', '','');

REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_0', 'Contenu', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_1', 'Référencement', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_2', 'Propriétés', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_4', 'Publications', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_3', 'Meta donn&eacute;es', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_5', 'Rendez-Vous', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_6', 'Liens document&eacute;s', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_7', 'FAQs', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_questions', 'Questions', '','');


REPLACE INTO `s_admin_trad` VALUES ('fk_thematique_id', 'Thématique d''application de cette association', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_titre_[LG]', 'Titre de la rubrique en Français', '','');
REPLACE INTO `s_admin_trad` VALUES ('rubrique_titre_en', 'Titre de la rubrique en Anglais', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique.fk_site_id', 'Liste des liens document&eacute;s ratach&eacute;s &agrave; cette cat&eacute;gorie', '','');
REPLACE INTO `s_admin_trad` VALUES ('url_fr_will_be', 'Le nom du dossier en Français de cette rubrique sera : ', '','');
REPLACE INTO `s_admin_trad` VALUES ('url_en_will_be', 'Le nom du dossier en Anglais de cette rubrique sera :', '','');



REPLACE INTO `s_admin_trad` VALUES ('fk_rubrique_id', 'Pages', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_trad', 'Textes param&eacute;trables', '','');

REPLACE INTO `s_admin_trad` VALUES ('t_fichier_p_0', 'Fichier', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_paragraphe_p_0', 'Format de paragraphe', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_paragraphe_p_1', 'Texte Français', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_paragraphe_p_2', 'Texte Anglais', '','');

REPLACE INTO `s_admin_trad` VALUES ('voir_actualite', '', '','');
REPLACE INTO `s_admin_trad` VALUES ('voir_editions_patrimoine', '', '','');
REPLACE INTO `s_admin_trad` VALUES ('voir_fiche_grand_public', '', '','');
REPLACE INTO `s_admin_trad` VALUES ('voir_reponses', '', '','');
REPLACE INTO `s_admin_trad` VALUES ('ce_s_paragraphe', 'ce Paragraphe', '','');
REPLACE INTO `s_admin_trad` VALUES ('ce_s_image', 'cette Image', '','');
REPLACE INTO `s_admin_trad` VALUES ('ce_t_lexique', 'cette D&eacute;finition', '','');
REPLACE INTO `s_admin_trad` VALUES ('un_s_paragraphe', 'un Paragraphe', '','');
REPLACE INTO `s_admin_trad` VALUES ('ce_s_rubrique', 'cette Rubrique', '','');
REPLACE INTO `s_admin_trad` VALUES ('un_s_rubrique', 'une Rubrique', '','');
REPLACE INTO `s_admin_trad` VALUES ('info_crea_rub', 'Cr&eacute;ation d''une nouvelle rubrique', '','');
REPLACE INTO `s_admin_trad` VALUES ('version_modifiable', 'Ceci est la version modifiable de la page', '','');
REPLACE INTO `s_admin_trad` VALUES ('version_en_ligne', 'Ceci est la version en ligne de la page', '','');
REPLACE INTO `s_admin_trad` VALUES ('voir_version_modifiable', 'Voir la version modifiable', '','');
REPLACE INTO `s_admin_trad` VALUES ('voir_version_en_ligne', 'Voir la version en ligne', '','');
REPLACE INTO `s_admin_trad` VALUES ('show_actions', 'Actions possibles', '','');
REPLACE INTO `s_admin_trad` VALUES ('validate', 'Valider', '','');
REPLACE INTO `s_admin_trad` VALUES ('ask_for_validation', 'Demander la validation', '','');
REPLACE INTO `s_admin_trad` VALUES ('contenu_en_bdd', 'Une partie de cette page provient d''une base externe<br/>', '','');

REPLACE INTO `s_admin_trad` VALUES ('InstallPlugin', 'Installer', '','');

REPLACE INTO `s_admin_trad` VALUES ('UninstallPlugin', 'Désinstaller', '','');

REPLACE INTO `s_admin_trad` VALUES ('cp_attente_rubs', 'Rubriques en-cours de validation', '','');
REPLACE INTO `s_admin_trad` VALUES ('cp_valide_rubs', 'Derni&egrave;res rubriques valid&eacute;es', '','');
REPLACE INTO `s_admin_trad` VALUES ('cp_create_rubs', 'Derni&egrave;res rubriques cr&eacute;&eacute;es', '','');
REPLACE INTO `s_admin_trad` VALUES ('cp_no_rubs_updated', 'Aucune rubrique en attente de validation', '','');

REPLACE INTO `s_admin_trad` VALUES ('cp_user_info', 'Informations utilisateur', '','');

REPLACE INTO `s_admin_trad` VALUES ('emptyCache', 'Vider le cache', '','');
REPLACE INTO `s_admin_trad` VALUES ('reIndexSearch', 'Ré-indexer le site', '','');
REPLACE INTO `s_admin_trad` VALUES ('files_on', 'fichiers sur', '','');
REPLACE INTO `s_admin_trad` VALUES ('have_been_removed', 'ont bien été supprimés', '','');
REPLACE INTO `s_admin_trad` VALUES ('validateVersion', 'Valider les modifications', '','');
REPLACE INTO `s_admin_trad` VALUES ('hideVersion', 'Masquer cet élément', '','');
REPLACE INTO `s_admin_trad` VALUES ('hideObject', 'Masquer cet élément', '','');
REPLACE INTO `s_admin_trad` VALUES ('showObject', 'Mettre en ligne', '','');

REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_contenu', 'Contenu', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_referencement', 'Référencement / URI', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_proprietes', 'Paramètres', '','');
REPLACE INTO `s_admin_trad` VALUES ('s_rubrique_p_contexte', 'Téléchargements', '','');

REPLACE INTO `s_admin_trad` VALUES ('src_forumValider', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-jump.png', '','');
REPLACE INTO `s_admin_trad` VALUES ('src_forumInterdire', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-lock-screen.png', '','');

REPLACE INTO `s_admin_trad` VALUES ('help_', 'Ce titre sea affiché dans les menus, en haut de la page, et dans les résultats des moteurs de recherche', '','');
REPLACE INTO `s_admin_trad` VALUES ('cp_txt_s_rubrique', 'Gestion du contenu', '','');
REPLACE INTO `s_admin_trad` VALUES ('cp_txt_s_admin', 'Gestion utilisateurs', '','');
REPLACE INTO `s_admin_trad` VALUES ('cp_txt_s_plugin', 'Plugins', '','');
REPLACE INTO `s_admin_trad` VALUES ('cp_txt_s_param', 'Configuration', '','');

REPLACE INTO `s_admin_trad` VALUES ('cp_txt_s_trad', 'Textes param&eacute;trables', '','');
REPLACE INTO `s_admin_trad` VALUES ('cp_txt_s_admin_trad', 'Textes de l''admin', '','');

REPLACE INTO `s_admin_trad` VALUES ('create_and_edit_rub', 'Continuer', '','');


REPLACE INTO `s_admin_trad` 
VALUES (
'genform_boolean_0', '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record.png" alt="Non" />', '', ''
), (
'genform_boolean_1', '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record-green.png" alt="Oui" />', '', ''
);


REPLACE INTO s_plugin SET plugin_nom = "o_createplugin" , plugin_actif = 1 , plugin_installe = 1;
REPLACE INTO s_plugin SET plugin_nom = "o_messages" , plugin_actif = 1 , plugin_installe = 1;




INSERT INTO `s_paragraphe` (`paragraphe_id`, `fk_rubrique_id`, `fk_para_type_id`, `paragraphe_ordre`, `paragraphe_titre_fr`, `paragraphe_contenu_fr`, `paragraphe_contenu_csv_fr`, `paragraphe_img_1_fr`, `paragraphe_img_2_fr`, `paragraphe_img_1_alt_fr`, `paragraphe_img_2_alt_fr`, `paragraphe_params_fr`, `paragraphe_file_1_fr`, `paragraphe_file_1_legend_fr`, `paragraphe_link_1_fr`, `paragraphe_img_1_copyright`, `paragraphe_img_2_copyright`) VALUES (3, 6, 1, 1, 'Contenu de la page 1', '<p>Contenu de la page 1</p>\r\n<p>Contenu de la page 1</p>\r\n<p>Contenu de la page 1</p>\r\n<p>Contenu de la page 1</p>', '', '', '', '', '', NULL, '', '', '', '', ''),
(5, 5, 1, 1, 'Contenu de la page 1', '<p>Contenu de la page 1</p>\r\n<p>Contenu de la page 1</p>\r\n<p>Contenu de la page 1</p>\r\n<p>Contenu de la page 1</p>', '', '', '', '', '', NULL, '', '', '', '', ''),
(6, 8, 2, 1, 'Sous-titre de la page2', ' <p>Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2</p>\r\n<p> </p> ', '', 'image.1.png', '', 'ocms', '', NULL, '', '', '', '', ''),
(9, 7, 2, 1, 'Sous-titre de la page2', ' <p>Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2 Contenu de la page 2</p>\r\n<p> </p> ', '', 'image.1.png', '', 'ocms', '', NULL, '', '', '', '', '');



INSERT INTO `s_para_type` (`para_type_id`, `para_type_titre`, `para_type_template`, `para_type_template_popup`, `para_type_vignette`, `para_type_use_img`, `para_type_use_file`, `para_type_use_table`, `para_type_use_txt`, `para_type_use_link`) VALUES (2, 'Titre + image + texte', '<div class="paraimg">\r\n<h2>@@titre@@</h2>\r\n<div class="img">##1##</div>\r\n<div class="texte">@@texte@@</div>\r\n</div>\r\n\r\n', '', '', 1, 0, 0, 1, 0);



INSERT INTO `s_rubrique` (`rubrique_id`, `fk_rubrique_id`, `fk_rubrique_version_id`, `rubrique_ordre`, `rubrique_etat`, `rubrique_gabarit_param`, `fk_gabarit_id`, `fk_creator_id`, `rubrique_url_fr`, `rubrique_titre_fr`, `rubrique_sous_titre_fr`, `rubrique_texte_fr`, `rubrique_keywords_fr`, `rubrique_desc_fr`, `rubrique_date_crea`, `rubrique_date_modif`, `rubrique_date_publi`, `rubrique_type`, `rubrique_link_fr`, `rubrique_dyntitle`, `rubrique_dynvisibility`, `rubrique_template`) VALUES (3, 1, NULL, 1, 'en_ligne', '', NULL, 1, 'menu', 'Menu', '', '', '', '', '2009-03-30 18:53:07', '2009-03-30 18:54:54', '2009-03-30 18:54:57', 'menuroot', '', 0, 0, ''),
(4, 1, 3, 1, 'redaction', '', NULL, 1, 'menu', 'Menu', '', '', '', '', '2009-03-30 18:53:07', '2009-03-30 18:54:54', '0000-00-00 00:00:00', 'menuroot', '', 0, 0, ''),
(5, 3, NULL, 1, 'en_ligne', '', NULL, 1, 'page-1', 'Page 1', '', '', '', '', '2009-03-30 18:53:14', '2009-03-30 18:53:32', '2009-03-30 18:53:39', 'page', '', 0, 0, ''),
(6, 3, 5, 1, 'redaction', '', NULL, 1, 'page-1', 'Page 1', '', '', '', '', '2009-03-30 18:53:14', '2009-03-30 18:53:32', '0000-00-00 00:00:00', 'page', '', 0, 0, ''),
(7, 3, NULL, 2, 'en_ligne', '', NULL, 1, 'page-2', 'Page 2', '', '', '', '', '2009-03-30 18:55:09', '2009-03-30 19:07:43', '2009-03-30 19:07:45', 'page', '', 0, 0, ''),
(8, 3, 7, 2, 'redaction', '', NULL, 1, 'page-2', 'Page 2', '', '', '', '', '2009-03-30 18:55:09', '2009-03-30 19:07:43', '0000-00-00 00:00:00', 'page', '', 0, 0, '');

