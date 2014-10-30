<?php

global $tabForms,$_Gconfig,$admin_trads;

$tabForms['s_bloc']['titre'] = array('bloc_nom');
$tabForms['s_bloc']['pages'] = array('../plugins/o_blocs/form.bloc.php');
$tabForms["s_bloc"]["picto"] = ADMIN_PICTOS_FOLDER."32x32/apps/preferences-system-windows.png";

$_Gconfig['codeFields'][] = 'bloc_afterinit';


$admin_trads["cp_txt_s_bloc"]["fr"] = "Bloc";



