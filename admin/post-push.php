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

ini_set('display_errors', 'on');
define('LG', $lg);


$gb_obj->includeBase();

$gb_obj->includeGlobal();

$t = getmicrotime();

$genMessages = new genMessages();

$gb_obj->includeAdmin();
initPlugins();
loadParams();

header('content-type:text/plain');

updateDatabase(true);
emptyCacheCli();