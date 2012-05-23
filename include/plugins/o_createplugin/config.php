<?php

global $_Gconfig;

$_Gconfig['globalActions'][] = 'o_adminer';
$_Gconfig['globalActions'][] = 'o_createPlugin';



$_Gconfig["adminMenus"]["administration"] = array('s_admin','s_trad');
$_Gconfig["adminMenus"]["menu_admin"] = array();

$_Gconfig["adminMenus"]["dev"] = array('s_plugin','s_param','s_bloc','s_admin_trad');
