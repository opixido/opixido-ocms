-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 29 Octobre 2014 à 17:05
-- Version du serveur: 5.5.40-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `ocms1`
--

-- --------------------------------------------------------

--
-- Structure de la table `s_admin`
--

CREATE TABLE IF NOT EXISTS `s_admin` (
  `admin_id`      SMALLINT(5) UNSIGNED                            NOT NULL AUTO_INCREMENT,
  `admin_login`   VARCHAR(64)                                     NOT NULL DEFAULT '',
  `admin_nom`     VARCHAR(255)                                    NOT NULL DEFAULT '',
  `admin_pwd`     VARCHAR(255)                                    NOT NULL DEFAULT '',
  `admin_email`   VARCHAR(255)                                    NOT NULL DEFAULT '',
  `admin_type`    ENUM('redacteur', 'valideur', 'administrateur') NOT NULL DEFAULT 'redacteur',
  `admin_last_cx` DATETIME                                        NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_login` (`admin_login`),
  KEY `admin_login_2` (`admin_login`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =2;

--
-- Contenu de la table `s_admin`
--


-- --------------------------------------------------------

--
-- Structure de la table `s_admin_role`
--

CREATE TABLE IF NOT EXISTS `s_admin_role` (
  `fk_admin_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_role_id`  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`fk_admin_id`, `fk_role_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

--
-- Contenu de la table `s_admin_role`
--

INSERT INTO `s_admin_role` (`fk_admin_id`, `fk_role_id`) VALUES (1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `s_admin_rows`
--

CREATE TABLE IF NOT EXISTS `s_admin_rows` (
  `fk_admin_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_row_id`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_table`    VARCHAR(64)      NOT NULL DEFAULT '',
  PRIMARY KEY (`fk_admin_id`, `fk_row_id`, `fk_table`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Structure de la table `s_admin_trad`
--

CREATE TABLE IF NOT EXISTS `s_admin_trad` (
  `admin_trad_id`   VARCHAR(128) NOT NULL DEFAULT '',
  `admin_trad_[LG]` TEXT         NOT NULL,
  `fk_plugin_id`    VARCHAR(64)  NOT NULL,
  PRIMARY KEY (`admin_trad_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

--
-- Contenu de la table `s_admin_trad`
--

INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('droits_insuffisants_pour_cette_action', 'Vous n''avez pas les droits nécessaires pour effectuer cette action', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_pas_en_ligne', 'Cette rubrique est déjà masquée', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_refuser_ok', 'Votre refus de validation a bien été pris en compte', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('refuse', 'Refuser la validation', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('unvalidate', 'Masquer', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('save_and_close', 'Retour', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_refuse', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-undo.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_light_edit', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/applications-graphics.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_modification', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_voir_version_en_ligne', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-template.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('src_voir_version_modifiable', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-properties.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_calendrier', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/office-calendar.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_random_password', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/preferences-system-session.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_locked', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/locked.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_importftp', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-symbolic-link.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_copy', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/places/start-here.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('generate_random_password', 'Générer un mot de passe', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('erreur_lock_existe',
                                                                                        '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/locked.png" alt="" /> Une autre personne est déjà <br/>en train de modifier cet élément',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_admin_p_0', 'Informations', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_admin_p_1', 'Rubriques autorisées', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('table_log_action', 'Dernières opérations effectuées', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('modification', 'Modifier', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('empty_field', 'Champ vide', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('ajout_sub_rub', 'Nouveau', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('invisible_rub', ' <span style="color:red">[Masquée]</span>', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('version_masquee', ' Cette rubrique est actuellement masquée', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('suppression_ok', 'Suppression effectuée pour : ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('modification_ok', 'Changements enregistrés pour : ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ajout_ok', 'correctement ajouté', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('connexion', 'Connexion ... ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('username', 'Nom d''utilisateur', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('password', 'Mot de passe', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('save', 'Enregistrer', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('cancel', 'Annuler', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('delete', 'Supprimer', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('search_id', 'Rechercher sur un identifiant : ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('search_txt', 'Extrait de texte &agrave; rechercher ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('choose_item', 'Choisissez un &eacute;l&eacute;ment &agrave; modifier', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rechercher', 'Lancer la recherche', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('confirm_suppr', 'Voulez vous vraiment supprimer cet enregistrement ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ajouter', 'Cliquez sur cet icone pour ajouter un &eacute;l&eacute;ment &agrave; la liste : ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ajouter_elem', 'Ajouter un &eacute;l&eacute;ment', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('menu', 'Menu', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('logout', 'D&eacute;connexion', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('menu1', 'Gestion de la base', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('menu2', ' ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('retour', ' retour', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('selectable_items', 'El&eacute;ments s&eacute;lectionnables', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('selected_items', 'El&eacute;ments s&eacute;lectionn&eacute;s', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('modifier', 'Modifier', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('modify_item', 'Modifier un &eacute;l&eacute;ment', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('date_picker', 'Choisissez la date', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('mal_remplit', 'Vous avez mal rempli les champs suivants :', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('etes_vous_sur_action', 'Souhaitez vous vraiment effectuer cette action ?', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('help_tablerel',
                                                                                        '<b>Champ Multicritère partagés</b><br/>Vous avez la possibilité de choisir plusieurs éléments.<br/><br/>Vous devez sélectionner l''élément voulu dans la colonne de gauche, puis cliquer sur la flêche orientée vers la droite pour le faire passer dans la liste des éléments sélectionnés',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('help_rte',
                                                                                        '<b>Champ Wyziwyg</b><br/>Vous avez la possibilité de mettre ce texte en forme via un outil graphique. Pour accéder au texte, cliquez sur l''icône d''édition à gauche ',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('help_upload_table',
                                                                                        '<b>Champ de mise en ligne de tableau CSV</b><br/>Vous avez la possibilité de parcourir le disque de votre ordinateur, et d''envoyer un fichier Excel (format CSV) pour remplir ce champ par un tableau de données',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('upload_table', 'Mettre en ligne un tableau (CSV)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('champ_vide', 'Ce champ est actuellement vide', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('help_texte', '<b>Champ Texte</b><br/>Cliquez dans le champ puis saisissez ou bien copiez/collez votre texte.', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('help_relinv_table',
                                                                                        '<b>Eléments liés</b><br/>Vous avez ci-contre la liste des éléments liés.<br/><br/> Sélectionnez un élément puis cliquez sur l''icône d''édition pour le modifier ',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('help_relation', '<b>Sélection simple</b><br/>Vous avez la possibilité de sélectionner un élément dans cette liste ',
   '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('help_file',
                                                                                        '<b>Mise en ligne de fichier</b><br/>Pour mettre en ligne un fichier, cliquez sur le bouton <i>PARCOURIR</i>, puis sélectionnez le fichier voulu sur votre ordinateur.<br/>Ensuite cliquez sur le bouton <i>Mettre en ligne</i>, pour envoyer le fichier sur le serveur.<br/><br/>Si un fichier existe déjà, vous pouvez refaire la procédure ci-dessus pour le mettre à jour, mais l''ancienne versin sera supprimée<br/><br/>Vous pouvez également supprimer le fichier sans le remplacer en cliquant sur le bouton <i>Supprimer</i>',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('aucun_element', 'Il n''y a aucun élément dans cette liste', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('select_rub_below', 'Sélectionnez une page ci-dessous', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('rubrique_valider_ok',
                                                                                        'Cette rubrique a bien été validée. Vos modifications sont désormais visibles par les internautes ',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_demande_valider_ok', 'Votre demande de validation a bien été transmise à : ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_devalider_ok', 'Cette rubrique a bien été masquée.<br/>Les internautes n''y ont plus accès', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_editer', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_edit', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_voir', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_view', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_new', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/list-add.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_preview', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/image-x-generic.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_droite', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-last.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_gauche', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-first.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_help', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/apps/help-browser.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_logout', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-log-out.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_NewsletterPreview', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-news-reader.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_NewsletterFakeSend', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/evolution.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_NewsletterSend', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-mail.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_ask_for_validation', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/system-software-update.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_cancel', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-undo.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_delete', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/process-stop.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_del', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/process-stop.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_validate', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_validateVersion', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_showObject', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_unvalidate', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_hideVersion', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_hideObject', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_upload', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/x-directory-remote-workgroup.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_up', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-up.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_down', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-down.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_date', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-down.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_SiteValidate', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_PublicationValidate', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/internet-web-browser.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_SiteHide', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_PublicationHide', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/image-missing.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_back', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-previous.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_first', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-previous.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_save', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-save.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_saveas', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-saveas.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_search', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-search.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_desktop', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/places/user-home.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_close', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_message_info', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/dialog-information.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_message_error', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/status/dialog-warning.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_undo', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-undo.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_go', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-find.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_InstallPlugin', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/install.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_UninstallPlugin', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/uninstall.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('search', 'Rechercher', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('preview', 'Pré-visualiser', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_paragraphe_titre_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_paragraphe_titre_fr', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_titre_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_titre_fr', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-copy.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('field_img_r_rubrique_lexique', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/x-office-address-book.png',
   '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_fk_liens_id', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/status/mail-attachment.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('field_img_fk_paragraphe_id', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/format-justify-left.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_desc_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_desc_fr', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_keywords_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_keywords_fr', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/actions/edit-paste.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('field_img_rubrique_gabarit_param',
   '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('field_img_fk_gabarit_id', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('field_img_fk_para_type_id', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/categories/preferences-desktop.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('field_img_paragraphe_img_1_fr', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/image-x-generic.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('field_img_paragraphe_img_1_en', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_ARBO_SIZE]/mimetypes/image-x-generic.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('field_help_rubrique_titre_en',
                                                                                        '<b>Titre de la page</b><br/>Il apparaitra dans les menus de navigation,<br/> la barre de titre du navigateur,<br/> et les résultats du moteur de recherche',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('field_help_rubrique_titre_fr',
                                                                                        '<b>Titre de la page</b><br/>Il apparaitra dans les menus de navigation,<br/> la barre de titre du navigateur,<br/> et les résultats du moteur de recherche',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('field_help_fk_paragraphe_id',
                                                                                        '<b>Paragraphes de cete page</b><br/>Votre page est constituée de plusieurs paragraphes. <br/><br/>Vous pouvez voir ci-dessous les paragraphes déjà créés, et cliquer sur l''icône en début de ligne pour les modifier indépendement',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_titre_fr', 'Titre en Français', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_titre_en', 'Titre en Anglais', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_contenu_fr', 'Contenu en Français', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_contenu_en', 'Contenu en Anglais', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_img_1_fr', 'Image d''accompagnement Française', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_img_1_en', 'Image d''accompagnement Anglaise', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_img_1_alt_fr', 'Description Française de l''image ci-dessus', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_img_1_alt_en', 'Description Anglaise de l''image ci-dessus', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('max_size', 'Taille max. ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('tester_le_lien', 'Tester le lien dans une popup', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('choisir_rubrique', 'Choisir une page dans le site', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('choisir_rubrique_ci_dessous', 'Cliquez sur une rubrique ci-dessous', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('voir', '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-find.png"  alt="Voir" />', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('editer',
                                                                                        '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/apps/accessories-text-editor.png"  alt="Editer" />',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('access_direct', 'Accès direct ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('mettre_en_ligne', 'Mettre en ligne', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('non_renseigne', 'Non renseign&eacute;', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('exporter', 'Exporter la table au format CSV', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('vider', 'Vider le contenu de la table', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('etes_vous_sur', 'Etes vous sur de vouloir vider cette table ? ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('il_y_a', 'Il y a ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('resultats', 'R&eacute;sultat(s)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('recherche_textuelle', 'Recherche plein texte', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('recherche_avancee', 'Recherche avanc&eacute;e ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fichier_uploader', 'Mettre en ligne un fichier', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fichier_remplacer', 'Remplacer ce fichier par : ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('supprimer_fichier', 'Voulez vous vraiment supprimer ce fichier du serveur ?', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('impossible_supprimer_repertoire',
   'Le r&eacute;pertoire contentant les fichiers de cet &eacute;l&eacute;ment n''a pas pu etre supprim&eacute;', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('impossible_supprimer', 'Ce fichier n''a pas pu &agrave;tre supprim&eacute; : ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('editer_wysiwyg',
                                                                                        'Cliquez ici pour acc&eacute;der &agrave; l''&eacute;diteur WYSIWYG complet pour mettre en forme ce champ',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('voir_wysiwyg', 'Cliquez ici pour voir le contenu de ce champ dans une nouvelle fen&ecirc;tre', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('edit', 'Modifier', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('edis_image',
                                                                                        '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/document-properties.png" border="0" alt="Editer" /> Retailler, recadrer, optimiser ... cette image',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('deja_fichier', 'Fichier existant', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('menu_0', 'Page d''accueil', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('menu_1', 'Lexique', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('menu_2', 'Param&egrave;tres techniques', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('t_event_p_0', 'Evènement Agenda', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('t_action_p_0', 'Actions', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('t_link_p_0', 'Lien', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('t_bloc_home_p_0', 'Bloc Page d''accueil', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('arborescence', 'Rubriques', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('getup', 'Monter cette ligne', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('getdown', 'Descendre cette ligne', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_meta_keyw_fr', 'Mots clefs d&eacute;finissants la page (FR) (1000 char)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_meta_keyw_en', 'Mots clefs d&eacute;finissants la page (EN) (1000 char)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_meta_desc_fr', 'Description de la page (FR) (255 char max)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_meta_desc_en', 'Description de la page (EN) (255 char max)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_visible', 'Afficher cette rubrique', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fk_paragraphe_id', 'Liste des paragraphes constituants cette page', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fk_para_type_id', 'Type de paragraphe', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fk_gabarit_id', 'Gabarit Sp&eacute;cial', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_gabarit_param', 'Param&egrave;tre technique du gabarit', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_pos_box', 'BoÃ®te contextuelles align&eacute;es en haut de la page', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('meta_informations', 'M&eacute;ta informations', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_keywords_fr', 'Mots clefs FR (255 chars)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_keywords_en', 'Mots clefs EN(255 chars)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_desc_fr', 'Description courte de la page FR', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_desc_en', 'Description courte de la page EN', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('desc_meta',
                                                                                        'Ces informations ne seront pas visible dans la page, mais serviront au bon r&eacute;f&eacute;rencement de cette page.<br/>La description peut apparaitre dans la liste des r&eacute;sultats du moteur de recherche',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_param', 'Param&egrave;tres du site', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('url_name', 'Titre pour le lien', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique', 'Rubriques du site', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_param_p_0', 'Param&egrave;tre', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_0', 'Contenu', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_1', 'Référencement', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_2', 'Propriétés', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_4', 'Publications', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_3', 'Meta donn&eacute;es', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_5', 'Rendez-Vous', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_6', 'Liens document&eacute;s', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('s_rubrique_p_7', 'FAQs', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_questions', 'Questions', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fk_thematique_id', 'Thématique d''application de cette association', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_titre_fr', 'Titre de la rubrique en Français', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_titre_en', 'Titre de la rubrique en Anglais', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('s_rubrique.fk_site_id', 'Liste des liens document&eacute;s ratach&eacute;s &agrave; cette cat&eacute;gorie', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('url_fr_will_be', 'Le nom du dossier en Français de cette rubrique sera : ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('url_en_will_be', 'Le nom du dossier en Anglais de cette rubrique sera :', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('fk_rubrique_id', 'Pages', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_trad', 'Textes param&eacute;trables', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('t_fichier_p_0', 'Fichier', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_paragraphe_p_0', 'Format de paragraphe', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_paragraphe_p_1', 'Texte Français', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_paragraphe_p_2', 'Texte Anglais', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('voir_actualite', '', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('voir_editions_patrimoine', '', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('voir_fiche_grand_public', '', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('voir_reponses', '', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ce_s_paragraphe', 'ce Paragraphe', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ce_s_image', 'cette Image', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ce_t_lexique', 'cette D&eacute;finition', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('un_s_paragraphe', 'un Paragraphe', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ce_s_rubrique', 'cette Rubrique', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('un_s_rubrique', 'une Rubrique', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('info_crea_rub', 'Cr&eacute;ation d''une nouvelle rubrique', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('version_modifiable', 'Ceci est la version modifiable de la page', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('version_en_ligne', 'Ceci est la version en ligne de la page', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('voir_version_modifiable', 'Voir la version modifiable', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('voir_version_en_ligne', 'Voir la version en ligne', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('show_actions', 'Actions possibles', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('validate', 'Valider', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ask_for_validation', 'Demander la validation', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('contenu_en_bdd', 'Une partie de cette page provient d''une base externe<br/>', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('InstallPlugin', 'Installer', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('UninstallPlugin', 'Désinstaller', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_attente_rubs', 'Rubriques en-cours de validation', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_valide_rubs', 'Derni&egrave;res rubriques valid&eacute;es', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_create_rubs', 'Derni&egrave;res rubriques cr&eacute;&eacute;es', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_no_rubs_updated', 'Aucune rubrique en attente de validation', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_user_info', 'Informations utilisateur', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('emptyCache', 'Vider le cache', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('reIndexSearch', 'Ré-indexer le site', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('files_on', 'fichiers sur', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('have_been_removed', 'ont bien été supprimés', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('validateVersion', 'Valider les modifications', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('hideVersion', 'Masquer cet élément', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('hideObject', 'Masquer cet élément', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('showObject', 'Mettre en ligne', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_contenu', 'Contenu', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_referencement', 'Référencement / URI', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_proprietes', 'Paramètres', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_contexte', 'Téléchargements', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_forumValider', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/go-jump.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_forumInterdire', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/system-lock-screen.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES
  ('help_', 'Ce titre sea affiché dans les menus, en haut de la page, et dans les résultats des moteurs de recherche',
   '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_txt_s_rubrique', 'Gestion du contenu', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_txt_s_admin', 'Gestion utilisateurs', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_txt_s_plugin', 'Plugins', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_txt_s_param', 'Configuration', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_txt_s_trad', 'Textes param&eacute;trables', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cp_txt_s_admin_trad', 'Textes de l''admin', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('create_and_edit_rub', 'Continuer', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('genform_boolean_0',
                                                                                        '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record.png" alt="Non" />',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('genform_boolean_1',
                                                                                        '<img src="[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/media-record-green.png" alt="Oui" />',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('map_geocode', 'Trouver la position à partir de l''adresse', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('map_usecenter', 'Enregistrer le centre actuel de la carte', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('picto_emptyCache', 'trash', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('picto_showPhpInfo', 'tasks', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('picto_executeSql', 'download-alt', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('picto_changeTranslations', 'flag', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('picto_recheckTranslations', 'retweet', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('picto_setAllUrls', 'globe', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('picto_cleanFiles', 'fire', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('picto_backupDb', 'share', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('picto_o_createPlugin', 'inbox', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('showPhpInfo', 'phpinfo();', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('executeSql', 'Requête SQL', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('changeTranslations', 'Gérer les langues', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('recheckTranslations', 'Ajouter les champs de langue manquants', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('setAllUrls', 'Définir toutes les URLs manquantes', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('cleanFiles', 'Supprimer les fichiers inutilisés', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('backupDb', 'Sauvegarde de la BDD', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('o_createPlugin', 'Créer un Plugin', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('lastActions', 'Vos derniers éléments modifiés', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('derniere_connexion', 'Dernière connexion le ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('lastActions_none', 'Aucune action effectuée', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('add_another', 'Ajouter ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('liste_global_actions', 'Actions administrateur ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('recherche_rapide', 'Recherche rapide ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('enum_page', 'Page simple', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('enum_link', 'Lien', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('enum_folder', 'Dossier', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('enum_siteroot', 'Racine de site', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('enum_menuroot', 'Menu', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('base_title', 'TEST', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('imgonglet_s_rubrique_p_contenu', '[ADMIN_PICTOS_FOLDER]16x16/actions/edit-paste.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('imgonglet_s_rubrique_p_referencement', '[ADMIN_PICTOS_FOLDER]16x16/apps/system-file-manager.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('imgonglet_s_rubrique_p_proprietes', '[ADMIN_PICTOS_FOLDER]16x16/emblems/emblem-system.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_titre', '[ADMIN_PICTOS_FOLDER]16x16/apps/accessories-text-editor.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fieldset_img_referencement', '[ADMIN_PICTOS_FOLDER]16x16/mimetypes/text-html.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fieldset_referencement', 'Référencement ', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_keywords', '[ADMIN_PICTOS_FOLDER]16x16/apps/preferences-desktop-font.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_keywords', 'Mots-clefs', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_desc', 'Description courte', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_desc', '[ADMIN_PICTOS_FOLDER]16x16/apps/accessories-text-editor.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_url', '[ADMIN_PICTOS_FOLDER]16x16/categories/applications-internet.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_url', 'Url de la page', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fieldset_img_programmation', '[ADMIN_PICTOS_FOLDER]16x16/status/image-loading.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fieldset_programmation', 'Programmation / Archivage', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_ocms_date_online', '[ADMIN_PICTOS_FOLDER]16x16/actions/edit-redo.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ocms_date_online', 'Date de mise en ligne', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_ocms_date_offline', '[ADMIN_PICTOS_FOLDER]16x16/actions/edit-undo.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('ocms_date_offline', 'Date de mise hors ligne', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_template', 'Template du site', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_template', '[ADMIN_PICTOS_FOLDER]16x16/mimetypes/text-x-generic-template.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_type', '[ADMIN_PICTOS_FOLDER]16x16/mimetypes/package-x-generic.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_type', 'Type de fonctionnement', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('duplicateMV', 'Dupliquer cette version', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('mv_duplicate_and_edit_draft', 'Modifier (nouveau brouillon)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('mv_edit_draft', 'Modifier (le brouillon)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('publishMV', 'Publier', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_duplicateMV', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-copy.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_publishMV', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/text-html.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_unpublishMV', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-unreadable.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('unpublishMV', 'Mettre Hors Ligne', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_paragraphe_p_paragraphe', 'Paragraphe', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('imgonglet_s_paragraphe_p_paragraphe', '[ADMIN_PICTOS_FOLDER]16x16/actions/edit-paste.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_paragraphe_titre', '[ADMIN_PICTOS_FOLDER]16x16/actions/format-justify-left.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_titre', 'Titre', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_paragraphe_contenu', '[ADMIN_PICTOS_FOLDER]16x16/actions/edit-paste.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_contenu', 'Contenu', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_img_1', 'Image', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_paragraphe_img_1', '[ADMIN_PICTOS_FOLDER]16x16/mimetypes/image-x-generic.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('paragraphe_img_1_alt',
                                                                                        'Texte alternatif à l’image (uniquement si elle fourni une information autrement inaccessible)',
                                                                                        '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_paragraphe_img_1_alt', '[ADMIN_PICTOS_FOLDER]16x16/apps/preferences-desktop-accessibility.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('paragraphe_img_1_legend', 'Légende/copyright', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_paragraphe_img_1_legend', '[ADMIN_PICTOS_FOLDER]16x16/mimetypes/application-certificate.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('imgonglet_s_trad_p_0', '[ADMIN_PICTOS_FOLDER]16x16/actions/format-text-bold.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_trad_p_0', 'Texte paramétrable', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('trad_id', 'Identifiant (ne pas changer)', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_trad', '[ADMIN_PICTOS_FOLDER]16x16/actions/format-justify-left.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('trad', 'Traduction', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_plugin_nom', '[ADMIN_PICTOS_FOLDER]16x16/actions/format-justify-center.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('plugin_nom', 'Nom', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_plugin_actif', '[ADMIN_PICTOS_FOLDER]16x16/status/software-update-available.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`) VALUES ('plugin_actif', 'Actif', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('picto_updateDatabase', 'circle-arrow-up', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('imgonglet_s_rubrique_p_new', '[ADMIN_PICTOS_FOLDER]16x16/actions/document-new.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('s_rubrique_p_new', 'Nouvelle page', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('moveRubrique', 'Déplacer cette rubrique', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('src_moveRubrique', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-symbolic-link.png', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('fieldset-nbfields', 'champs masqués', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('confirm_delete', 'Pour supprimer cet élément saisissez "OUI" ci-dessous', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('rubrique_picto', 'Image principale / vignette', '');
INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_[LG]`, `fk_plugin_id`)
VALUES ('field_img_rubrique_picto', '[ADMIN_PICTOS_FOLDER]16x16/mimetypes/image-x-generic.png', '');

-- --------------------------------------------------------

--
-- Structure de la table `s_bloc`
--

CREATE TABLE IF NOT EXISTS `s_bloc` (
  `bloc_nom`       VARCHAR(32) NOT NULL,
  `bloc_afterinit` TEXT        NOT NULL,
  `bloc_classe`    VARCHAR(64) NOT NULL,
  `bloc_visible`   TINYINT(1)  NOT NULL DEFAULT '1',
  PRIMARY KEY (`bloc_nom`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1;

--
-- Contenu de la table `s_bloc`
--

INSERT INTO `s_bloc` (`bloc_nom`, `bloc_afterinit`, `bloc_classe`, `bloc_visible`) VALUES ('footer', '', '', 1);
INSERT INTO `s_bloc` (`bloc_nom`, `bloc_afterinit`, `bloc_classe`, `bloc_visible`) VALUES ('header', '', '', 1);
INSERT INTO `s_bloc` (`bloc_nom`, `bloc_afterinit`, `bloc_classe`, `bloc_visible`) VALUES ('main_after', '', '', 1);
INSERT INTO `s_bloc` (`bloc_nom`, `bloc_afterinit`, `bloc_classe`, `bloc_visible`) VALUES ('main_before', '', '', 1);

-- --------------------------------------------------------

--
-- Structure de la table `s_gabarit`
--

CREATE TABLE IF NOT EXISTS `s_gabarit` (
  `gabarit_id`             SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gabarit_titre`          VARCHAR(128)         NOT NULL DEFAULT '',
  `gabarit_para_crea`      VARCHAR(255)         NOT NULL DEFAULT '',
  `gabarit_para_include`   VARCHAR(64)          NOT NULL DEFAULT '',
  `gabarit_full_template`  LONGTEXT             NOT NULL,
  `gabarit_bdd_deco`       TINYINT(1)           NOT NULL DEFAULT '0',
  `gabarit_classe`         VARCHAR(64)          NOT NULL DEFAULT '',
  `gabarit_classe_param`   VARCHAR(255)         NOT NULL DEFAULT '',
  `gabarit_plugin`         VARCHAR(128)         NOT NULL,
  `gabarit_index_table`    VARCHAR(64)          NOT NULL DEFAULT '',
  `gabarit_index_url`      VARCHAR(255)         NOT NULL DEFAULT '',
  `fk_default_rubrique_id` INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  PRIMARY KEY (`gabarit_id`),
  KEY `fk_default_rubrique_id` (`fk_default_rubrique_id`),
  KEY `gabarit_classe` (`gabarit_classe`),
  KEY `gabarit_classe_param` (`gabarit_classe_param`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =1;

-- --------------------------------------------------------

--
-- Structure de la table `s_groupe`
--

CREATE TABLE IF NOT EXISTS `s_groupe` (
  `groupe_id`  INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `groupe_nom` VARCHAR(64)      NOT NULL DEFAULT '',
  PRIMARY KEY (`groupe_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =1;

-- --------------------------------------------------------

--
-- Structure de la table `s_image`
--

CREATE TABLE IF NOT EXISTS `s_image` (
  `image_id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `image_img`          VARCHAR(255) NOT NULL DEFAULT '',
  `image_titre_[LG]`   VARCHAR(255) NOT NULL DEFAULT '',
  `image_legende_[LG]` VARCHAR(255) NOT NULL DEFAULT '',
  `image_copyright`    VARCHAR(255) NOT NULL DEFAULT '',
  `image_lien_[LG]`    VARCHAR(255)          DEFAULT '',
  PRIMARY KEY (`image_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =1;

-- --------------------------------------------------------

--
-- Structure de la table `s_langue`
--

CREATE TABLE IF NOT EXISTS `s_langue` (
  `langue_nom` VARCHAR(64) NOT NULL DEFAULT '',
  `langue_id`  VARCHAR(3)  NOT NULL DEFAULT '',
  PRIMARY KEY (`langue_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Structure de la table `s_lock`
--

CREATE TABLE IF NOT EXISTS `s_lock` (
  `fk_admin_id` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  `lock_table`  VARCHAR(64)          NOT NULL DEFAULT '',
  `lock_id`     INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  `lock_time`   BIGINT(12) UNSIGNED  NOT NULL DEFAULT '0',
  PRIMARY KEY (`lock_table`, `lock_id`),
  KEY `fk_admin_id` (`fk_admin_id`),
  KEY `lock_time` (`lock_time`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Structure de la table `s_log_action`
--

CREATE TABLE IF NOT EXISTS `s_log_action` (
  `log_action_id`     INT(10) UNSIGNED     NOT NULL AUTO_INCREMENT,
  `fk_admin_id`       SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  `log_action_table`  VARCHAR(128)         NOT NULL DEFAULT '',
  `log_action_fk_id`  VARCHAR(255)         NOT NULL DEFAULT '0',
  `log_action_action` VARCHAR(128)         NOT NULL DEFAULT '',
  `log_action_time`   DATETIME             NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_action_id`),
  KEY `fk_admin_id` (`fk_admin_id`),
  KEY `log_action_table` (`log_action_table`),
  KEY `log_action_fk_id` (`log_action_fk_id`),
  KEY `log_action_time` (`log_action_time`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =1;

-- --------------------------------------------------------

--
-- Structure de la table `s_paragraphe`
--

CREATE TABLE IF NOT EXISTS `s_paragraphe` (
  `paragraphe_id`                 BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_rubrique_id`                INT(10) UNSIGNED             DEFAULT NULL,
  `fk_para_type_id`               SMALLINT(5) UNSIGNED         DEFAULT NULL,
  `paragraphe_ordre`              TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `paragraphe_titre_[LG]`         VARCHAR(255)        NOT NULL DEFAULT '',
  `paragraphe_contenu_[LG]`       TEXT                NOT NULL,
  `paragraphe_contenu_csv_[LG]`   TEXT                NOT NULL,
  `paragraphe_img_1_[LG]`         VARCHAR(255)        NOT NULL DEFAULT '',
  `paragraphe_img_2_[LG]`         VARCHAR(255)        NOT NULL DEFAULT '',
  `paragraphe_img_1_alt_[LG]`     VARCHAR(80)         NOT NULL DEFAULT '',
  `paragraphe_img_2_alt_[LG]`     VARCHAR(80)         NOT NULL DEFAULT '',
  `paragraphe_params_[LG]`        VARCHAR(255)                 DEFAULT NULL,
  `paragraphe_file_1_[LG]`        VARCHAR(255)        NOT NULL DEFAULT '',
  `paragraphe_file_1_legend_[LG]` VARCHAR(255)        NOT NULL DEFAULT '',
  `paragraphe_link_1_[LG]`        VARCHAR(255)        NOT NULL DEFAULT '',
  `paragraphe_img_1_copyright`    VARCHAR(80)         NOT NULL DEFAULT '',
  `paragraphe_img_2_copyright`    VARCHAR(80)         NOT NULL DEFAULT '',
  PRIMARY KEY (`paragraphe_id`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `paragraphe_ordre` (`paragraphe_ordre`),
  KEY `fk_para_type_id` (`fk_para_type_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =3;

--
-- Contenu de la table `s_paragraphe`
--

INSERT INTO `s_paragraphe` (`paragraphe_id`, `fk_rubrique_id`, `fk_para_type_id`, `paragraphe_ordre`, `paragraphe_titre_[LG]`, `paragraphe_contenu_[LG]`, `paragraphe_contenu_csv_[LG]`, `paragraphe_img_1_[LG]`, `paragraphe_img_2_[LG]`, `paragraphe_img_1_alt_[LG]`, `paragraphe_img_2_alt_[LG]`, `paragraphe_params_[LG]`, `paragraphe_file_1_[LG]`, `paragraphe_file_1_legend_[LG]`, `paragraphe_link_1_[LG]`, `paragraphe_img_1_copyright`, `paragraphe_img_2_copyright`)
VALUES
  (1, 2, 2, 1, 'Titre du paragraphe', '<p>Contenu du paragraphe</p>', '', '', '', '', '', NULL, '', '', '', '', '');
INSERT INTO `s_paragraphe` (`paragraphe_id`, `fk_rubrique_id`, `fk_para_type_id`, `paragraphe_ordre`, `paragraphe_titre_[LG]`, `paragraphe_contenu_[LG]`, `paragraphe_contenu_csv_[LG]`, `paragraphe_img_1_[LG]`, `paragraphe_img_2_[LG]`, `paragraphe_img_1_alt_[LG]`, `paragraphe_img_2_alt_[LG]`, `paragraphe_params_[LG]`, `paragraphe_file_1_[LG]`, `paragraphe_file_1_legend_[LG]`, `paragraphe_link_1_[LG]`, `paragraphe_img_1_copyright`, `paragraphe_img_2_copyright`)
VALUES
  (2, 1, 2, 1, 'Titre du paragraphe', '<p>Contenu du paragraphe</p>', '', '', '', '', '', NULL, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `s_param`
--

CREATE TABLE IF NOT EXISTS `s_param` (
  `param_id`          VARCHAR(64) NOT NULL DEFAULT '',
  `param_valeur`      TEXT        NOT NULL,
  `param_description` TEXT        NOT NULL,
  `fk_plugin_id`      VARCHAR(64) NOT NULL,
  PRIMARY KEY (`param_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

--
-- Contenu de la table `s_param`
--

INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('cache_path', 'cache', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('date_update_arbo', '1414595345', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('date_update_s_admin_trad', '1414593006', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('date_update_s_bloc', '1414591518', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('date_update_s_paragraphe', '1414595338', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('date_update_s_plugin', '1414592082', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('date_update_s_rubrique', '1414595345', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('fake_folder_param', 'p_', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('mail_host', 'localhost', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('mail_type', 'mail', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('nb_rows_before_search', '100', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('next_cache_update', '', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('param_key_sep', '/', '', '');
INSERT INTO `s_param` (`param_id`, `param_valeur`, `param_description`, `fk_plugin_id`)
VALUES ('param_val_sep', '-', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `s_para_type`
--

CREATE TABLE IF NOT EXISTS `s_para_type` (
  `para_type_id`             SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `para_type_titre`          VARCHAR(128)         NOT NULL DEFAULT '',
  `para_type_template`       TEXT                 NOT NULL,
  `para_type_tpl_file`       VARCHAR(255)         NOT NULL,
  `para_type_template_popup` TEXT                 NOT NULL,
  `para_type_vignette`       VARCHAR(255)         NOT NULL DEFAULT '',
  `para_type_use_img`        TINYINT(1)           NOT NULL DEFAULT '0',
  `para_type_use_file`       TINYINT(1)           NOT NULL DEFAULT '0',
  `para_type_use_table`      TINYINT(1)           NOT NULL DEFAULT '0',
  `para_type_use_txt`        TINYINT(1)           NOT NULL DEFAULT '1',
  `para_type_use_link`       TINYINT(1)           NOT NULL DEFAULT '0',
  `para_type_gabarit`        VARCHAR(64)          NOT NULL,
  `para_type_plugin`         VARCHAR(64)          NOT NULL,
  `para_type_champs`         TEXT                 NOT NULL,
  PRIMARY KEY (`para_type_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =3;

--
-- Contenu de la table `s_para_type`
--

INSERT INTO `s_para_type` (`para_type_id`, `para_type_titre`, `para_type_template`, `para_type_tpl_file`, `para_type_template_popup`, `para_type_vignette`, `para_type_use_img`, `para_type_use_file`, `para_type_use_table`, `para_type_use_txt`, `para_type_use_link`, `para_type_gabarit`, `para_type_plugin`, `para_type_champs`)
VALUES (2, 'Titre + image + texte',
        '<div class="paraimg">\r\n<h2>@@titre@@</h2>\r\n<div class="img">##1##</div>\r\n<div class="texte">@@texte@@</div>\r\n</div>\r\n\r\n',
        '', '', '', 1, 0, 0, 1, 0, '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `s_plugin`
--

CREATE TABLE IF NOT EXISTS `s_plugin` (
  `plugin_nom`         VARCHAR(64)          NOT NULL DEFAULT '',
  `plugin_actif`       TINYINT(1)           NOT NULL DEFAULT '0',
  `plugin_ordre`       SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  `plugin_installe`    TINYINT(1)           NOT NULL DEFAULT '0',
  `plugin_updates`     BLOB                 NOT NULL,
  `plugin_last_update` INT(11)              NOT NULL,
  PRIMARY KEY (`plugin_nom`),
  KEY `plugin_actif` (`plugin_actif`),
  KEY `plugin_ordre` (`plugin_ordre`),
  KEY `plugin_installe` (`plugin_installe`),
  KEY `plugin_last_update` (`plugin_last_update`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

--
-- Contenu de la table `s_plugin`
--

INSERT INTO `s_plugin` (`plugin_nom`, `plugin_actif`, `plugin_ordre`, `plugin_installe`, `plugin_updates`, `plugin_last_update`)
VALUES ('ocms_jstrad', 1, 0, 1, '', 0);
INSERT INTO `s_plugin` (`plugin_nom`, `plugin_actif`, `plugin_ordre`, `plugin_installe`, `plugin_updates`, `plugin_last_update`)
VALUES ('ocms_title', 1, 0, 1, '', 0);
INSERT INTO `s_plugin` (`plugin_nom`, `plugin_actif`, `plugin_ordre`, `plugin_installe`, `plugin_updates`, `plugin_last_update`)
VALUES ('o_blocs', 1, 0, 1, '', 0);
INSERT INTO `s_plugin` (`plugin_nom`, `plugin_actif`, `plugin_ordre`, `plugin_installe`, `plugin_updates`, `plugin_last_update`)
VALUES ('o_createplugin', 1, 0, 1, '', 0);
INSERT INTO `s_plugin` (`plugin_nom`, `plugin_actif`, `plugin_ordre`, `plugin_installe`, `plugin_updates`, `plugin_last_update`)
VALUES ('o_messages', 1, 0, 1, '', 0);
INSERT INTO `s_plugin` (`plugin_nom`, `plugin_actif`, `plugin_ordre`, `plugin_installe`, `plugin_updates`, `plugin_last_update`)
VALUES ('o_migration', 1, 0, 1, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `s_role`
--

CREATE TABLE IF NOT EXISTS `s_role` (
  `role_id`  INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_nom` VARCHAR(64)      NOT NULL DEFAULT '',
  PRIMARY KEY (`role_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =2;

--
-- Contenu de la table `s_role`
--

INSERT INTO `s_role` (`role_id`, `role_nom`) VALUES (1, 'ADMINISTRATEUR');

-- --------------------------------------------------------

--
-- Structure de la table `s_role_table`
--

CREATE TABLE IF NOT EXISTS `s_role_table` (
  `role_table_id`       INT(10) UNSIGNED                    NOT NULL AUTO_INCREMENT,
  `role_table_table`    VARCHAR(64)                         NOT NULL DEFAULT '',
  `fk_role_id`          INT(10) UNSIGNED                    NOT NULL DEFAULT '0',
  `role_table_type`     ENUM('all', 'per_user', 'specific') NOT NULL DEFAULT 'all',
  `role_table_specific` VARCHAR(128)                        NOT NULL DEFAULT '',
  `role_table_view`     TINYINT(1)                          NOT NULL DEFAULT '0',
  `role_table_add`      TINYINT(1)                          NOT NULL DEFAULT '0',
  `role_table_edit`     TINYINT(1)                          NOT NULL DEFAULT '0',
  `role_table_delete`   TINYINT(1)                          NOT NULL DEFAULT '0',
  `role_table_actions`  VARCHAR(255)                        NOT NULL DEFAULT '',
  `role_table_champs`   TEXT                                NOT NULL,
  PRIMARY KEY (`role_table_id`),
  KEY `fk_role_id` (`fk_role_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =2;

--
-- Contenu de la table `s_role_table`
--

INSERT INTO `s_role_table` (`role_table_id`, `role_table_table`, `fk_role_id`, `role_table_type`, `role_table_specific`, `role_table_view`, `role_table_add`, `role_table_edit`, `role_table_delete`, `role_table_actions`, `role_table_champs`)
VALUES (1, 'all', 1, 'all', '', 1, 1, 1, 1, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `s_rubrique`
--

CREATE TABLE IF NOT EXISTS `s_rubrique` (
  `rubrique_id`              INT(10) UNSIGNED                                       NOT NULL AUTO_INCREMENT,
  `ocms_version`             INT(10) UNSIGNED                                                DEFAULT NULL,
  `ocms_version_name`        VARCHAR(128)                                                    DEFAULT NULL,
  `ocms_etat`                ENUM('brouillon', 'publiable', 'en_ligne', 'archive')  NOT NULL DEFAULT 'publiable',
  `ocms_creator`             SMALLINT(5) UNSIGNED                                            DEFAULT NULL,
  `ocms_date_crea`           DATETIME                                               NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ocms_date_modif`          DATETIME                                               NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ocms_date_publi`          DATETIME                                               NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ocms_date_online`         DATETIME                                               NOT NULL,
  `ocms_date_offline`        DATETIME                                               NOT NULL,
  `fk_gabarit_id`            SMALLINT(5) UNSIGNED                                            DEFAULT NULL,
  `fk_rubrique_id`           INT(10) UNSIGNED                                                DEFAULT NULL,
  `rubrique_ordre`           TINYINT(3) UNSIGNED                                    NOT NULL DEFAULT '0',
  `rubrique_gabarit_param`   VARCHAR(255)                                           NOT NULL DEFAULT '',
  `rubrique_url_[LG]`        VARCHAR(255)                                           NOT NULL DEFAULT '',
  `rubrique_titre_[LG]`      VARCHAR(255)                                           NOT NULL DEFAULT '',
  `rubrique_titre_page_[LG]` VARCHAR(255)                                           NOT NULL,
  `rubrique_sous_titre_[LG]` VARCHAR(255)                                           NOT NULL DEFAULT '',
  `rubrique_texte_[LG]`      TEXT                                                   NOT NULL,
  `rubrique_keywords_[LG]`   VARCHAR(255)                                           NOT NULL DEFAULT '',
  `rubrique_desc_[LG]`       TEXT                                                   NOT NULL,
  `rubrique_date_crea`       DATETIME                                               NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rubrique_date_modif`      DATETIME                                               NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rubrique_date_publi`      DATETIME                                               NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rubrique_picto_[LG]`      VARCHAR(255)                                           NOT NULL,
  `rubrique_type`            ENUM('page', 'link', 'folder', 'siteroot', 'menuroot') NOT NULL DEFAULT 'page',
  `rubrique_link_[LG]`       VARCHAR(255)                                           NOT NULL DEFAULT '',
  `rubrique_dyntitle`        TINYINT(1)                                             NOT NULL DEFAULT '0',
  `rubrique_dynvisibility`   TINYINT(1)                                             NOT NULL DEFAULT '0',
  `rubrique_template`        VARCHAR(255)                                           NOT NULL DEFAULT '',
  `rubrique_option`          SET('dynVisibility', 'dynTitle', 'dynSubRubs')         NOT NULL,
  PRIMARY KEY (`rubrique_id`),
  KEY `fk_gabarit_id` (`fk_gabarit_id`),
  KEY `fk_creator_id` (`ocms_creator`),
  KEY `rubrique_ordre` (`rubrique_ordre`),
  KEY `rubrique_dyntitle` (`rubrique_dyntitle`),
  KEY `rubrique_url_[LG]` (`rubrique_url_[LG]`),
  KEY `ocms_date_online` (`ocms_date_online`),
  KEY `ocms_date_offline` (`ocms_date_offline`),
  KEY `rubrique_type` (`rubrique_type`),
  KEY `ocms_etat` (`ocms_etat`),
  KEY `fk_rubrique_id` (`fk_rubrique_id`),
  KEY `rubrique_type_2` (`rubrique_type`),
  KEY `ocms_etat_2` (`ocms_etat`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =3;

--
-- Contenu de la table `s_rubrique`
--

INSERT INTO `s_rubrique` (`rubrique_id`, `ocms_version`, `ocms_version_name`, `ocms_etat`, `ocms_creator`, `ocms_date_crea`, `ocms_date_modif`, `ocms_date_publi`, `ocms_date_online`, `ocms_date_offline`, `fk_gabarit_id`, `fk_rubrique_id`, `rubrique_ordre`, `rubrique_gabarit_param`, `rubrique_url_[LG]`, `rubrique_titre_[LG]`, `rubrique_titre_page_[LG]`, `rubrique_sous_titre_[LG]`, `rubrique_texte_[LG]`, `rubrique_keywords_[LG]`, `rubrique_desc_[LG]`, `rubrique_date_crea`, `rubrique_date_modif`, `rubrique_date_publi`, `rubrique_picto_[LG]`, `rubrique_type`, `rubrique_link_[LG]`, `rubrique_dyntitle`, `rubrique_dynvisibility`, `rubrique_template`, `rubrique_option`)
VALUES (1, 1, NULL, 'en_ligne', 1, '0000-00-00 00:00:00', '2014-10-29 16:09:05', '0000-00-00 00:00:00',
        '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, 1, '', ';[racine];', 'Titre du site', '', '', '', '',
        '', '2009-03-30 18:53:07', '2009-03-30 18:54:54', '2009-03-30 18:54:57', '', 'siteroot', '', 0, 0, '', '');
INSERT INTO `s_rubrique` (`rubrique_id`, `ocms_version`, `ocms_version_name`, `ocms_etat`, `ocms_creator`, `ocms_date_crea`, `ocms_date_modif`, `ocms_date_publi`, `ocms_date_online`, `ocms_date_offline`, `fk_gabarit_id`, `fk_rubrique_id`, `rubrique_ordre`, `rubrique_gabarit_param`, `rubrique_url_[LG]`, `rubrique_titre_[LG]`, `rubrique_titre_page_[LG]`, `rubrique_sous_titre_[LG]`, `rubrique_texte_[LG]`, `rubrique_keywords_[LG]`, `rubrique_desc_[LG]`, `rubrique_date_crea`, `rubrique_date_modif`, `rubrique_date_publi`, `rubrique_picto_[LG]`, `rubrique_type`, `rubrique_link_[LG]`, `rubrique_dyntitle`, `rubrique_dynvisibility`, `rubrique_template`, `rubrique_option`)
VALUES (2, 1, NULL, 'brouillon', 1, '2014-10-29 13:04:56', '2014-10-29 16:09:03', '0000-00-00 00:00:00',
        '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, 0, '', ';[racine];', 'Titre du site', '', '', '', '',
        '', '2009-03-30 18:53:07', '2009-03-30 18:54:54', '2009-03-30 18:54:57', '', 'siteroot', '', 0, 0, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `s_trad`
--

CREATE TABLE IF NOT EXISTS `s_trad` (
  `trad_id`      VARCHAR(64) NOT NULL DEFAULT '',
  `trad_[LG]`    TEXT        NOT NULL,
  `fk_plugin_id` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`trad_id`),
  KEY `fk_plugin_id` (`fk_plugin_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

--
-- Contenu de la table `s_trad`
--

INSERT INTO `s_trad` (`trad_id`, `trad_[LG]`, `fk_plugin_id`) VALUES ('cp_txt_home', 'Accueil', '');

-- --------------------------------------------------------

--
-- Structure de la table `s_traduction`
--

CREATE TABLE IF NOT EXISTS `s_traduction` (
  `fk_table`         VARCHAR(32)      NOT NULL DEFAULT '',
  `fk_id`            INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `fk_champ`         VARCHAR(64)      NOT NULL DEFAULT '',
  `fk_langue_id`     VARCHAR(3)       NOT NULL DEFAULT '0',
  `traduction_texte` TEXT             NOT NULL,
  PRIMARY KEY (`fk_table`, `fk_id`, `fk_champ`, `fk_langue_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;
  
  
ALTER TABLE  `s_para_type` ADD  `fk_gabarit_id` INT NOT NULL ;