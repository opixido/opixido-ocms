<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you can redistribute it and/or modify
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
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#

if (PHP_SAPI != 'cli') {
    die();
}

ob_start();

define('IN_ADMIN', true);

error_reporting(E_ALL & ~E_NOTICE);

require_once('../include/include.php');


/* On aura toujours besoin de ca */

$gb_obj = new genBase();

$gb_obj->includeConfig();

if (!empty($_REQUEST['lg'])) {
    $lg = $_SESSION['lg'] = $_REQUEST['lg'];
} else if (!empty($_SESSION['lg'])) {
    $lg = $_SESSION['lg'];
} else {
    $lg = LG_DEF;
}


define('LG', $lg);


$gb_obj->includeBase();

$gb_obj->includeGlobal();

$t = getmicrotime();

$genMessages = new genMessages();

$gb_obj->includeAdmin();


$gs_obj = new genSecurity();

initPlugins();

loadParams();

$plugs = GetPlugins();

foreach ($plugs as $v) {
    $GLOBALS['gb_obj']->includeFile('admin.php', PLUGINS_FOLDER . '' . $v . '/');
}

ob_end_clean();


/**
 * Toutes les actions sont superAdmin
 */
$gs_obj->superAdmin = true;


global $_Gconfig;
/**
 * Récupération de l'argument 1 comme nom de fonction
 */
if (!empty($argv[1])) {
    /**
     * La fonction doit être définie dans les cmdActions
     */
    if (in_array($argv[1], $_Gconfig['cmdActions'])) {
        echo 'Execute ' . $argv[1] . "\n";
        /**
         * On récupère les arguments passés après pour les passer à la fonction
         */
        $params = array_slice($argv, 2);

        /**
         * On appel la fonction avec les arguments
         */
        call_user_func_array($argv[1], $params);
    }
}



