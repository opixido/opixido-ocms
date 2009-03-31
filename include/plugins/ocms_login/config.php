<?php


global $tabForms,$tablerel,$functionField,$_Gconfig,$uploadFields,$relations,$relinv,$orderFields;



/*$tabForms['t_rubrique']['pages']['proprietes'][] ='../plugins/ocms_login/form.rubrique.php';*/


$tabForms['e_utilisateur']['pages'][] = '../plugins/ocms_login/form.utilisateur.php';
//$tabForms['s_rubrique']['pages']['limitAccess'] = '../plugins/ocms_login/form.utilisateur.php';



$tabForms['e_groupe']['titre'] = array('groupe_nom');


//$_Gconfig['adminMenus']['utilisateur'][] = 'e_utilisateur';
//$_Gconfig['adminMenus']['utilisateur'][] = 'e_groupe';



/*
$_Gconfig['adminMenus']['utilisateur'][]= 'h_part_user';
$_Gconfig['adminMenus']['utilisateur'][]= 'h_dev_user';
$_Gconfig['adminMenus']['utilisateur'][]= 'h_press_user';
*/


$tabForms['e_groupe']['titre'] = array('groupe_nom');
$tabForms['e_groupe']['pages'] = array('../plugins/ocms_login/form.groupe.php');
$tabForms['e_utilisateur']['titre'] = array('utilisateur_login'/*, 'utilisateur_email','utilisateur_valide'*/);

$_Gconfig['rowActions']['e_storage_user']['movePath'] = 1;


//$tablerel['r_rubrique_groupe'] = array('fk_rubrique_id'=>'t_rubrique','fk_groupe_id'=>'s_groupe');

$relations['e_groupe']['fk_groupe_id'] = 'e_groupe';
$relations['e_storage_user']['fk_path_id'] = 'e_path';

/**
 * Ajout Olivier 08 12 2008
 */
$relations['e_storage_user']['fk_utilisateur_id'] = 'e_utilisateur';
/**/

define('crypto_key_ocms_login','LauVeMiT3ndEr');



$functionField['utilisateur_pwd']['before'] = 'decodePasswordLA';
$functionField['utilisateur_pwd']['after'] = 'encodePasswordLA';


function decodePasswordLA($str) {
	$crypto = new crypto(crypto_cipher,crypto_mode,crypto_key_ocms_login);
	return $crypto->decrypt($str);
}

function encodePasswordLA($str) {
	$crypto = new crypto(crypto_cipher,crypto_mode,crypto_key_ocms_login);
	return $crypto->encrypt($str);
}


$_Gconfig['reloadOnChange'][] = 'privee';

$_Gconfig['passwordFields'] = array('utilisateur_pwd');
$uploadFields[] = 'utilisateur_photo';

global $gr_on;

$gr_on['save']['s_rubrique'][] = 'laSaveForm';
$gr_on['validate']['s_rubrique'][] = 'laSaveFormRub';


