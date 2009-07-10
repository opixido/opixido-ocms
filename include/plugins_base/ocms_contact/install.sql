
CREATE TABLE `plug_contact` (
  `contact_id` int(10) unsigned NOT NULL auto_increment,
  `contact_titre_[LG]` varchar(255) NOT NULL default '',
  `contact_email` varchar(255) NOT NULL default '',
  `fk_rubrique_id` int(11) NOT NULL default '0',
  `contact_ordre` int(11) NOT NULL default '0',
  PRIMARY KEY  (`contact_id`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `contact_ordre` (`contact_ordre`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


CREATE TABLE `plug_contact_field` (
  `contact_field_id` int(10) unsigned NOT NULL auto_increment,
  `fk_rubrique_id` int(10) unsigned NOT NULL,
  `contact_field_ordre` smallint(5) unsigned NOT NULL,
  `contact_field_nom_[LG]` varchar(255) NOT NULL,
  `contact_field_type` enum('text','select','radio','textarea','email','submit','hidden','selectm','html','file') NOT NULL,
  `contact_field_needed` tinyint(1) NOT NULL,
  `contact_field_default_value` varchar(255) NOT NULL,
  `contact_field_values_[LG]` varchar(255) NOT NULL,
  `contact_field_name` varchar(64) NOT NULL,
  PRIMARY KEY  (`contact_field_id`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `contact_field_ordre` (`contact_field_ordre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('c_body', 'Nouveau message de contact :','ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('c_comment', 'Commentaire','ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('c_email', 'E-mail','ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('c_from', 'no@reply.com','ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('c_nom', 'Nom','ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('c_prenom', 'Pr&eacute;nom','ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('c_qui', 'Service','ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('c_subject', '[SITE] Formulaire de contact','ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('c_submit', 'Envoyer','ocms_contact');


REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('contact_auto_response', 'Votre message a bien été pris en compte.\r\nNous vous répondrons dans les meilleurs délais.\r\n\r\nMerci de votre intérêt.', 'ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('contact_auto_subject', 'Formulaire de contact',  'ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('contact_body', 'Une personne a envoyé ce message via le formulaire de contact du site :\r\n','ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('contact_from', 'ne_pas@repondre.com', 'ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('contact_from_nom', 'Envoi automatique',  'ocms_contact');
REPLACE INTO `s_trad` (`trad_id`, `trad_[LG]` , `fk_plugin_id`) VALUES ('contact_subject', 'Nouveau contact','ocms_contact');
REPLACE INTO `s_trad` ( `trad_id` , `trad_[LG]` , `fk_plugin_id` )
VALUES (
'confirm_send_contact', 'Votre message a bien été envoyé', 'ocms_contact'
);



INSERT INTO `s_gabarit` ( `gabarit_id` , `gabarit_titre` , `gabarit_para_crea` , `gabarit_para_include` , `gabarit_full_template` , `gabarit_bdd_deco` , `gabarit_classe` , `gabarit_classe_param` , `gabarit_index_table` , `gabarit_index_url` , `fk_default_rubrique_id` ,gabarit_plugin)
VALUES (
NULL , 'Formulaire de contact', '', '', '', '0', 'genContact', '', '', '', '0','ocms_contact'
);

