# Ajout des champs à la rubrique
ALTER TABLE `s_rubrique`
ADD `ocms_version` int(10) unsigned NULL AFTER `fk_rubrique_id`,
ADD `ocms_version_name` varchar(128) NULL AFTER `ocms_version`,
ADD `ocms_etat` enum('brouillon','publiable','en_ligne','archive') NULL AFTER `ocms_version_name`,
COMMENT='';

# Gestion des versions
UPDATE s_rubrique SET ocms_version = rubrique_id WHERE fk_rubrique_version_id IS NULL;
UPDATE s_rubrique SET ocms_version = fk_rubrique_version_id,  ocms_version_name = "Brouillon importé" WHERE fk_rubrique_version_id IS NOT NULL;

# Gestion des états
UPDATE s_rubrique SET ocms_etat = "en_ligne" WHERE rubrique_etat = "en_ligne";
UPDATE s_rubrique SET ocms_etat = "brouillon" WHERE rubrique_etat = "redaction";

# Renomage des anciens champs état et version
ALTER TABLE `s_rubrique`
CHANGE `fk_rubrique_version_id` `__old_fk_rubrique_version_id` int(10) unsigned NULL AFTER `ocms_etat`,
CHANGE `rubrique_etat` `__old_rubrique_etat` enum('redaction','attente','en_ligne') COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'redaction' AFTER `rubrique_ordre`,
COMMENT=''; 
ALTER TABLE `s_rubrique`
CHANGE `__old_rubrique_etat` `__old_rubrique_etat` enum('redaction','attente','en_ligne') COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'redaction'  AFTER `rubrique_option`,
CHANGE `__old_fk_rubrique_version_id` `__old_fk_rubrique_version_id` int(10) unsigned NULL AFTER `__old_rubrique_etat`,
COMMENT='';

# Renommage du champ creator 
ALTER TABLE `s_rubrique`
CHANGE `fk_creator_id` `ocms_creator` smallint(5) unsigned NULL AFTER `fk_gabarit_id`,
COMMENT='';

ALTER TABLE `s_rubrique`
CHANGE `rubrique_date_crea` `ocms_date_crea` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `rubrique_desc_fr`,
CHANGE `rubrique_date_modif` `ocms_date_modif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `ocms_date_crea`,
CHANGE `rubrique_date_publi` `ocms_date_publi` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `ocms_date_modif`,
COMMENT=''; 

ALTER TABLE `s_rubrique`
CHANGE `ocms_etat` `ocms_etat` enum('brouillon','publiable','en_ligne','archive') COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'publiable' AFTER `ocms_version_name`,
COMMENT=''; 

REPLACE INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_fr`, `admin_trad_en`, `fk_plugin_id`) VALUES
('duplicateMV',	'Dupliquer cette version',	'',	''),
('publishMV',	'Publier',	'',	'');

REPLACE INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_fr`, `admin_trad_en`, `fk_plugin_id`)
VALUES ('unpublishMV', 'Mettre Hors Ligne', '', ''); -- 0.000 s

REPLACE INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_fr`, `admin_trad_en`, `fk_plugin_id`)
VALUES ('mv_duplicate_and_edit_draft', 'Modifier (nouveau brouillon)', '', ''); -- 0.000 s

INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_fr`, `admin_trad_en`, `fk_plugin_id`)
VALUES ('src_unpublishMV', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/emblems/emblem-unreadable.png', '', ''); -- 0.001 s

INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_fr`, `admin_trad_en`, `fk_plugin_id`)
VALUES ('src_publishMV', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/mimetypes/text-html.png', '', ''); -- 0.001 s



INSERT INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_fr`, `admin_trad_en`, `fk_plugin_id`)
VALUES ('src_duplicateMV', '[ADMIN_PICTOS_FOLDER][ADMIN_PICTOS_FORM_SIZE]/actions/edit-copy.png', '', ''); -- 0.001 s

REPLACE INTO `s_admin_trad` (`admin_trad_id`, `admin_trad_fr`, `admin_trad_en`, `fk_plugin_id`)
VALUES ('mv_edit_draft', 'Modifier (le brouillon)', '', ''); -- 0.000 s

ALTER TABLE  `s_rubrique` ADD  `ocms_date_online` DATETIME NULL AFTER  `rubrique_option` ,
ADD  `ocms_date_offline` DATETIME NULL AFTER  `ocms_date_online` ;

ALTER TABLE  `s_rubrique` ADD INDEX (  `ocms_date_online` ) ;
ALTER TABLE  `s_rubrique` ADD INDEX (  `ocms_date_offline` ) ;


ALTER TABLE  `s_plugin` CHANGE  `plugin_last_update`  `plugin_updates` BLOB NOT NULL ;