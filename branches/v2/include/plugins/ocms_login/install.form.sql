CREATE TABLE `h_form_field` (
`form_field_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`fk_univers_id` INT UNSIGNED NOT NULL ,
`form_field_order` SMALLINT UNSIGNED NOT NULL ,
`form_field_name_uk` VARCHAR( 255 ) NOT NULL ,
`form_field_type` ENUM( 'text', 'textarea', 'boolean', 'select', 'selectm' ) NOT NULL ,
`form_field_needed` TINYINT( 1 ) NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `h_form_field_value` (
`form_field_value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`fk_form_field_id` INT UNSIGNED NOT NULL ,
`form_field_value_order` SMALLINT NOT NULL ,
`form_field_value_uk` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `h_dev_user` (
`dev_user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`fk_utilisateur_id` INT UNSIGNED NOT NULL ,
`dev_user_nom` VARCHAR( 255 ) NOT NULL ,
`dev_user_prenom` VARCHAR( 255 ) NOT NULL ,
`dev_user_email` VARCHAR( 255 ) NOT NULL ,
`fk_pays_id` INT UNSIGNED NOT NULL ,
`fk_univers_id` INT UNSIGNED NOT NULL
) ENGINE = MYISAM ;