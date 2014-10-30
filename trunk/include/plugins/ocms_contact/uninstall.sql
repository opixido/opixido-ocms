
DELETE FROM `s_trad` WHERE CONVERT(`trad_id` USING utf8) = 'c_comment' LIMIT 1;
DELETE FROM `s_trad` WHERE CONVERT(`trad_id` USING utf8) = 'c_email' LIMIT 1;
DELETE FROM `s_trad` WHERE CONVERT(`trad_id` USING utf8) = 'c_nom' LIMIT 1;
DELETE FROM `s_trad` WHERE CONVERT(`trad_id` USING utf8) = 'c_prenom' LIMIT 1;
DELETE FROM `s_trad` WHERE CONVERT(`trad_id` USING utf8) = 'c_qui' LIMIT 1;
DELETE FROM `s_trad` WHERE CONVERT(`trad_id` USING utf8) = 'c_submit' LIMIT 1;
DELETE FROM `s_trad` WHERE CONVERT(`trad_id` USING utf8) = 'c_from' LIMIT 1;
DELETE FROM `s_trad` WHERE CONVERT(`trad_id` USING utf8) = 'c_subject' LIMIT 1;
DELETE FROM `s_trad` WHERE CONVERT(`trad_id` USING utf8) = 'c_body' LIMIT 1;


DROP TABLE `plug_contact`;
DROP TABLE `plug_contact_field` ;

DELETE FROM s_gabarit WHERE gabarit_plugin = 'ocms_contact';