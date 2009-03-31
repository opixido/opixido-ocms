<?php

global $tabForms,$_Gconfig,$admin_trads;

$tabForms['s_bloc']['titre'] = array('bloc_nom');
$tabForms['s_bloc']['pages'] = array('../plugins/o_blocs/form.bloc.php');
$tabForms["s_bloc"]["picto"] = "pictos_stock/tango/32x32/apps/preferences-system-windows.png";

$_Gconfig['codeFields'][] = 'bloc_afterinit';

$_Gconfig['adminMenus']['menu_admin'][] = 's_bloc';

$admin_trads["cp_txt_s_bloc"]["fr"] = "Bloc";



?>
