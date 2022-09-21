REPLACE INTO `s_gabarit` (`gabarit_titre`, `gabarit_para_crea`, `gabarit_para_include`, `gabarit_full_template`, `gabarit_bdd_deco`, `gabarit_classe`, `gabarit_classe_param`, `gabarit_plugin`, `gabarit_index_table`, `gabarit_index_url`, `fk_default_rubrique_id`) VALUES
('Abonement à la newsletter', '', '', '', 0, 'genAbonnementNewsletter', '', 'ocms_newsletter', '', '', 0);

REPLACE INTO `s_gabarit` (`gabarit_titre`, `gabarit_para_crea`, `gabarit_para_include`, `gabarit_full_template`, `gabarit_bdd_deco`, `gabarit_classe`, `gabarit_classe_param`, `gabarit_plugin`, `gabarit_index_table`, `gabarit_index_url`, `fk_default_rubrique_id`) VALUES
('Aperçu newsletter', '', '', '', 0, 'genApercuNewsletter', '', 'ocms_newsletter', '', '', 0);

CREATE TABLE IF NOT EXISTS `ocms_newsletter_newsletter` (
  `newsletter_id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletter_titre_fr` varchar(255) NOT NULL,
  `ocms_date_crea` datetime NOT NULL,
  `ocms_date_modif` datetime NOT NULL,
  `newsletter_mailchimp_id` varchar(127) NOT NULL,
  `newsletter_sent` tinyint(1) NOT NULL,
  `newsletter_sent_time` datetime NOT NULL,
  `newsletter_date_fr` varchar(255) NOT NULL,
  PRIMARY KEY (`newsletter_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `ocms_newsletter_r_newsletter_spectacle` (
  `fk_newsletter_id` int(11) NOT NULL,
  `fk_rubrique_id` int(11) NOT NULL,
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`fk_newsletter_id`,`fk_rubrique_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ocms_newsletter_user` ( 
`user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`user_email` varchar(255) NOT NULL,
`user_code` varchar(64) NOT NULL, 
`user_checked` tinyint(1) NOT NULL DEFAULT '0', 
`user_error` tinyint(1) NOT NULL, 
PRIMARY KEY (`user_id`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

REPLACE INTO `s_trad` (`trad_id`, `trad_fr`, `fk_plugin_id`) VALUES ('simpleform_confirmation', 'confirmation', '');

REPLACE INTO `s_trad` (`trad_id`, `trad_fr`, `fk_plugin_id`) VALUES
('ocms_newsletter_abo', 'S''abonner', ''),
('ocms_newsletter_abo_ok', 'Votre demande d''abonnement a bien été prise en compte. Veuillez la confirmer dans l''email qui vous a été envoyé.', ''),
('ocms_newsletter_action', 'Action', ''),
('ocms_newsletter_baseline', 'Les "coups de projecteur" Ticket-théâtre(s)', ''),
('ocms_newsletter_base_title', 'Les coups de projecteurs Ticket-théâtre(s)', ''),
('ocms_newsletter_codepostal', 'Code postal', ''),
('ocms_newsletter_default_title', 'Lettre d''information', ''),
('ocms_newsletter_desabo', 'Se désabonner', ''),
('ocms_newsletter_desabo_ok', 'Votre demande d''désabonnement a bien été prise en compte. Veuillez la confirmer dans l''email qui vous a été envoyé.', ''),
('ocms_newsletter_email', 'Adresse email', ''),
('ocms_newsletter_infos_pied_de_page', '<strong>COMMUNAUTÉ D’AGGLOMÉRATION MELUN VAL DE SEINE</strong>\r\n\r\n297, rue Tousseau Vaudran\r\nCS 30187\r\n77198 Dammarie-lès-Lys Cedex\r\n\r\nTél : 01 64 79 25 25\r\n\r\n<a style="color:#fff;" href="http://www.melunvaldeseine.fr">www.melunvaldeseine.fr</a>', ''),
('ocms_newsletter_lien_archive', 'Si le message ne s''affiche pas correctement, <strong><a style="color:#b2b2b2;" href="*|ARCHIVE|*">visualisez-li en ligne</a></strong>.', ''),
('ocms_newsletter_lien_desinscription', 'Pour vous désinscrire de la newsletter, <a style="color:white;" href="*|UNSUB|*">cliquez ici</a>', ''),
('ocms_newsletter_mail_from', 'no-reply@ticket-theatres.com', ''),
('ocms_newsletter_mail_pas_valide', 'Adresse email non enregistrée ou non vérifiée.', ''),
('ocms_newsletter_nom', 'Nom', ''),
('ocms_newsletter_prenom', 'Prénom', ''),
('ocms_newsletter_submit', '/// Valider', ''),
('ocms_newsletter_texte_btn_footer', '/// Abonnez-vous<br/>à la newsletter ', ''),
('ocms_newsletter_texte_twitter', 'Partagez les coups de projecteur Ticket-théâtre(s) !', ''),
('ocms_newsletter_titre_form_desabonnement', 'Désinscription à la newsletter', ''),
('ocms_newsletter_txt_infos_pratiques', 'Ticket-Théâtre(s) • association loi 1901 • Tél.: 01 49 58 17 12 • <a href="mailto:infos@ticket-theatre.com" style="text-decoration:none;">infos@ticket-theatre.com</a>', ''),
('ocms_newsletter_txt_lire_suite', 'Lire la suite', ''),
('ocms_newsletter_txt_partager', 'Partager', ''),
('ocms_newsletter_txt_reserver', 'Réserver en ligne', ''),
('ocms_newsletter_txt_savoir_plus', 'En savoir +', ''),
('ocms_newsletter_validation_ok', 'Votre adresse mail a bien été vérifiée, votre abonnement à la newsletter est effectif.', '');

INSERT INTO `ticktheatr`.`s_trad` (`trad_id` ,`trad_fr` ,`fk_plugin_id`) VALUES 
('ocms_newsletter_titre_form_abonnement', 'Inscription à la newsletter', ''), 
('ocms_newsletter_enregistrement_ok', 'Votre demande d''abonnement a bien été prise en compte. Veuillez la confirmer dans l''email qui vous a été envoyé.', ''),
('ocms_newsletter_mail_title_abo', 'Votre inscription à la newsletter de Ticket Théâtre(s)', ''),
('ocms_newsletter_mail_fromname', 'Ticket Théâtre(s)', '');
('ocms_newsletter_confirm_mail_abo', 'Bienvenue dans notre newsletter d''information.<br /><br />

Afin de confirmer votre inscription, cliquez ici : <br />
<a href="[URL]">Lien de confirmation </a><br /><br />

Merci de vous être inscrit<br /><br />

Ou copier/coller ce lien dans la barre d’adresse de votre navigateur :<br />
[URL]

<br /><br />Si vous ne vous êtes pas inscrit à cette newsletter, ignorez simplement ce message.<br /><br />

-----------------------------------------------------------------------------<br />
Cet email est généré automatiquement.<br />
Merci de ne pas y répondre. ', ''),
('ocms_newsletter_desenregistrement_ok', 'Vous allez recevoir un email de confirmation', ''), 
('ocms_newsletter_confirm_mail_desabo', 'Bonjour,<br /><br />
Pour valider votre désinscription à la newsletter de Ticket Théâtre(s), merci de cliquer sur le lien ci-dessous : <br />
<a href="[URL]">Lien de confirmation</a><br /><br />
Ou copier/coller ce lien dans la barre d''adresse de votre navigateur :<br />
[URL]<br /><br />
-----------------------------------------------------------------------------<br />
Cet email est généré automatiquement.<br />Merci de ne pas y répondre.', ''),
('ocms_newsletter_mail_title_desabo', 'Votre demande de désinscription à la newsletter de Ticket Théâtre(s)', ''),
('ocms_newsletter_delete_ok', 'Votre compte à bien été supprimé.', '');






