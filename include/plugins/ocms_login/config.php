<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you cgan redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido 
# @copyright opixido 2012
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#

global $tabForms, $tablerel, $functionField, $_Gconfig, $uploadFields, $relations, $relinv, $orderFields;



/* $tabForms['t_rubrique']['pages']['proprietes'][] ='../plugins/ocms_login/form.rubrique.php'; */


$tabForms['e_utilisateur']['pages'][] = '../plugins/ocms_login/form.utilisateur.php';
//$tabForms['s_rubrique']['pages']['limitAccess'] = '../plugins/ocms_login/form.utilisateur.php';



$tabForms['e_groupe']['titre'] = array('groupe_nom');


$_Gconfig['adminMenus']['utilisateur'][] = 'e_utilisateur';
$_Gconfig['adminMenus']['utilisateur'][] = 'e_groupe';



/*
  $_Gconfig['adminMenus']['utilisateur'][]= 'h_part_user';
  $_Gconfig['adminMenus']['utilisateur'][]= 'h_dev_user';
  $_Gconfig['adminMenus']['utilisateur'][]= 'h_press_user';
 */


$tabForms['e_groupe']['titre'] = array('groupe_nom');
$tabForms['e_groupe']['pages'] = array('../plugins/ocms_login/form.groupe.php');
$tabForms['e_utilisateur']['titre'] = array('utilisateur_login'/* , 'utilisateur_email','utilisateur_valide' */);

$_Gconfig['rowActions']['e_storage_user']['movePath'] = 1;


$tablerel['r_rubrique_groupe'] = array('fk_rubrique_id' => 't_rubrique', 'fk_groupe_id' => 's_groupe');

$relations['e_groupe']['fk_groupe_id'] = 'e_groupe';
$relations['e_storage_user']['fk_path_id'] = 'e_path';


define('crypto_key_ocms_login', '10Fz418fd E9847sfzzF');



$functionField['utilisateur_pwd']['before'] = 'decodePasswordLA';
$functionField['utilisateur_pwd']['after'] = 'encodePasswordLA';

function decodePasswordLA($str) {
    $crypto = new crypto(crypto_cipher, crypto_mode, crypto_key_ocms_login);
    return $crypto->decrypt($str);
}

function encodePasswordLA($str) {
    $crypto = new crypto(crypto_cipher, crypto_mode, crypto_key_ocms_login);
    return $crypto->encrypt($str);
}

$_Gconfig['reloadOnChange'][] = 'privee';

$_Gconfig['passwordFields'] = array('utilisateur_pwd');

global $gr_on;

$gr_on['save']['s_rubrique'][] = 'laSaveForm';
$gr_on['save']['e_utilisateur'][] = 'laSaveForm';
$gr_on['save']['o_spectacle'][] = 'laSaveForm';
$gr_on['validate']['s_rubrique'][] = 'laSaveFormRub';


