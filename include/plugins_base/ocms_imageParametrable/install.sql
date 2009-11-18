
CREATE TABLE `p_imagep` (
  `imagep_id` int(11) NOT NULL auto_increment,
  `imagep_label` varchar(32) NOT NULL,
  `imagep_img_[LG]` varchar(255) NOT NULL,
  `imagep_alt_[LG]` varchar(80) NOT NULL,
  `fk_version` varchar(32) NOT NULL,
  `en_ligne` tinyint(1) NOT NULL,
  PRIMARY KEY  (`imagep_id`),
  KEY `fk_version` (`fk_version`),
  KEY `en_ligne` (`en_ligne`),
  KEY `imagep_label` (`imagep_label`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `p_imagep` CHANGE `fk_version` `fk_version` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;