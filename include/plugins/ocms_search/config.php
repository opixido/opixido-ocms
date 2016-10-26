<?php


global $_Gconfig,$gr_on ,$tabForms;

$tabForms['os_tables']['titre'] = array('table_name','table_url');

//$_Gconfig['adminMenus']['menu_admin'][] = 'os_tables';


$_Gconfig['globalActions'][]= 'reIndexSearch';
$_Gconfig['globalActions'][]= 'testSearch';
$_Gconfig['globalActions'][]= 'mostUsedWords';
$_Gconfig['globalActions'][]= 'viderRecherche';

//$gr_on['saved']['ANY_TABLE'][] = 'indexForSearch';
//$gr_on['save']['ANY_TABLE'][] = 'indexForSearch';

$gr_on['validateVersion']['s_rubrique'][] = 'indexForSearch';



$_Gconfig['iSearches'] = array();