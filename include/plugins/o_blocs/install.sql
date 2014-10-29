CREATE TABLE `s_bloc` (
`bloc_nom` VARCHAR( 32 ) NOT NULL ,
`bloc_afterinit` TEXT NOT NULL ,
`bloc_classe` VARCHAR( 64 ) NOT NULL ,
`bloc_visible` TINYINT( 1 ) NOT NULL DEFAULT '1',
PRIMARY KEY ( `bloc_nom` )
) ;

