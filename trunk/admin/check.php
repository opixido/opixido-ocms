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

/**
 * Better with multithread and heavy sites
 */
ini_set('memory_limit', '256M');



global $co;

function getListe() {
    $liste = array();

    $tables = getTables();
    foreach ($tables as $table) {
        if ($table == 's_404' || $table == 's_badlinks') {
            continue;
        }
        $champs = getTabField($table);
        $pk = getPrimaryKey($table);
        foreach ($champs as $champ => $vals) {
            if ($table == 's_rubrique' && strpos($champ, 'rubrique_url_') !== false) {
                continue;
            }
            if (isUrlField($champ)) {

                $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $champ . '  != ""';
                $res = DoSql($sql);
                foreach ($res as $row) {
                    $liste[] = array('table' => $table, 'id' => $row[$pk], 'champ' => $champ, 'url' => $row[$champ]);
                }
            } else if (isRteField($champ)) {
                $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $champ . '  != ""';
                $res = DoSql($sql);
                $matches = array();
                foreach ($res as $row) {
                    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";

                    preg_match_all('/<a[^>]*href="([^"]*)"[^>]*>.*<\/a>/', $row[$champ], $matches);
                    //var_dump($matches);
                    foreach ($matches[1] as $match) {

                        $info = parse_url($match);
                        if (empty($info['host'])) {
                            $match = 'http://barolo.ined.fr/' . $match;
                            $info = parse_url($match);
                        }

                        if (!empty($info['host'])) {
                            $url = $info['scheme'] . '://' . $info['host'] . $info['path'];
                            $liste[] = array('table' => $table, 'id' => $row[$pk], 'champ' => $champ, 'url' => $row[$champ]);
                        }
                    }
                }
            }
        }
    }

    return $liste;
}

$start = time();
$liste = getListe();
require('class.process_manager.php');

$co->disconnect();
$total = count($liste);
$j = 0;




$manager = new Processmanager();
$manager->executable = "/usr/bin/php";
$manager->path = dirname(__FILE__);
$manager->show_output = true;
$manager->processes = 20;
$manager->sleep_time = 1;


foreach ($liste as $url) {
    $manager->addScript("check_url.php " . '"' . $url['url'] . '" ' . $url['table'] . ' ' . $url['id'] . ' ' . $url['champ'], 10);
}

$manager->exec();

global $_bdd_host, $_bdd_type, $_bdd_pwd, $_bdd_bdd, $_bdd_user;
$co = ADONewConnection($_bdd_type);
$connec = $co->Connect($_bdd_host, $_bdd_user, $_bdd_pwd, $_bdd_bdd);
DoSql('DELETE FROM s_badlinks WHERE bad_last_date < ' . time());
