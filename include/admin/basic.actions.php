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
# @copyright opixido 2012
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#

function s_admin_update($id, $row = array())
{


    if (!count($row)) {
        $row = getRowFromId('s_admin', $id);
    }

    $sql = 'DELETE FROM s_admin_rows WHERE fk_admin_id = "' . $id . '"';
    DoSql($sql);

    if (!ake($_POST, 's_admin_rows')) {
        return;
    }

    foreach ($_POST['s_admin_rows'] as $table => $rows) {
        foreach ($rows as $row) {
            $sql = 'INSERT INTO s_admin_rows (fk_admin_id,fk_row_id,fk_table) VALUES ("' . $id . '","' . $row . '","' . $table . '")';
            DoSql($sql);
        }
    }
}

function executeSql()
{


    $f = new simpleForm('', 'post', 'executeSql');

    $f->add('fieldset', 'Executer une requête SQL');
    $f->add('textarea', 'SELECT * FROM s_rubrique', 'Requête : ', 'sqlQ', '', true);
    $f->add('submit', 'QUERY');
    $f->add('endfieldset');

    if ($f->isSubmited() && $f->isValid()) {

        $res = GetAll($_POST['sqlQ']);

        echo '<h3>' . count($res) . ' Résultats</h3>';
        echo '<h3>' . Affected_Rows() . ' enregistrements affectés</h3>';
        echo '<h3>Identifiant : ' . InsertId() . ' inséré</h3>';

        if (count($res)) {
            p('<table >');
            foreach ($res as $j => $row) {
                p('<tr class="row' . ($j % 2) . '">');
                if ($j == 0) {
                    foreach ($row as $k => $v) {
                        p('<th style="background:#999;color:white">' . t($k) . '</th>');
                    }
                    p('</tr><tr class="row' . ($j % 2) . '">');
                    reset($row);
                }
                foreach ($row as $k => $v) {
                    p('<td >' . $v . '</td>');
                }
                p('</tr>');
            }
            p('</table>');
        }
    }

    echo $f->gen();
}

/**
 * Rajoute ou supprime une langue globalement pour le site
 *
 */
function changeTranslations()
{
    if (!$_POST['translationName']) {

        $sf = new simpleForm('', 'post');
        $sf->add('text', '', 'Langue : (code sur deux lettres)', 'translationName');
        $sf->add('select', array(array('label' => 'Ajouter', 'value' => 'add'), array('label' => 'Supprimer', 'value' => 'del')), 'Action : ', 'translationAction', '', true);
        $sf->add('hidden', 'changeTranslations', '', 'globalAction');
        $sf->add('submit', 'GO !');

        p($sf->gen());
    } else {
        doTranslations($_POST['translationName'], $_POST['translationAction']);
    }
    $_SESSION['cache_tabfield'] = array();
}

function doTranslations($lg, $action = 'add')
{
    $_SESSION['cache'] = array();

    $tables = GetTables();
    foreach ($tables as $table) {
        $chps = getTabField($table);
        foreach ($chps as $chp) {

            if (isDefaultLgField($chp->name)) {

                $chpnu = fieldWithoutLg($chp->name);
                $newName = $chpnu . '_' . $lg;

                if ($chps[$newName]) {
                    if ($action == 'del') {

                        print('<br/>DROPPING : ' . $table . '.' . $newName);
                        DoSql('ALTER TABLE ' . $table . ' DROP ' . $newName . '');
                    } else {
                        //p('<br/>CAN\'T ADD EXISTENT FIELD : '.$table.'.'.$newName);
                    }
                } else {
                    if ($action == 'del') {
                        p('<br/>CAN\'T DROP INEXISTENT FIELD : ' . $table . '.' . $newName);
                    } else {
                        print('<br/>ADDING => ' . $table . '.' . $newName);

                        $sql = 'ALTER TABLE
									' . $table . '
									ADD
									' . $newName . '
									' . $chp->type . '
									' . ($chp->max_length > 0 ? '(' . $chp->max_length . ') ' : '') . '
									' . ($chp->unsigned ? ' UNSIGNED ' : '') . '
									' . ($chp->not_null ? ' NOT ' : '') . ' NULL									
									' . ($chp->has_default ? ' DEFAULT "' . $chp->has_default . '" ' : '') . '

									AFTER ' . $chp->name . '
									';
                        DoSql($sql);
                    }
                }
            }
        }
    }

    $_SESSION['cache'] = array();
}

function recheckTranslations()
{
    global $_Gconfig;
    $ar = array_reverse($_Gconfig['LANGUAGES']);
    foreach ($ar as $v) {

        doTranslations($v, 'add');
    }
}

/**
 * Fonction pour reencoder les mots de passe des utilisateurs
 * Soit avec une clef diff�rents, un cipher different, ...
 */
function encodePasswords()
{

    $crypto = new crypto(crypto_cipher, crypto_mode, crypto_key);
    $crypto2 = new crypto('saferplus', crypto_mode, 'fdfsdfsd');
    $sql = 'SELECT * FROM s_admin';
    $res = GetAll($sql);
    foreach ($res as $row) {
        print('<h3>' . $row['admin_pwd'] . '</h3>');
        print('<h4>' . $crypto->decrypt($row['admin_pwd']) . '</h4>');
        print('<h5>' . $crypto2->encrypt($crypto->decrypt($row['admin_pwd'])) . '</h5>');
        print('<hr/>');
    }
}

function showPhpInfo()
{

    ob_start();
    phpinfo();

    $s = ob_get_contents();
    ob_end_clean();
    $s = substr($s, strpos($s, '<body>') + 6);
    $s = substr($s, 0, strrpos($s, '<h2>PHP License'));
    $s .= '</div>';
    p('<style type="text/css">');
    p('

 #phpinfo {
 /*overflow:auto;

 height:500px;*/
 }

  #phpinfo img {
  	display:none;
  }
 #phpinfo table {


 }

  #phpinfo table td.e {
  	background:#bbb;
  }


  #phpinfo table td.v {
  	background:#ccc;
  }

 ');
    p('</style>');
    p('<div id="phpinfo">');
    print($s);
    p('</div>');
    /*
      phpinfo(INFO_CREDITS);
      phpinfo(INFO_CONFIGURATION);
      phpinfo(INFO_MODULES);
      phpinfo(INFO_ENVIRONMENT);
      phpinfo(INFO_VARIABLES);

     */
}

function mostUsedWords()
{

    $sql = 'SELECT IW.word, COUNT(IR.fkword) AS SOMME FROM is_rel AS IR, is_word AS IW WHERE IW.id = IR.fkword GROUP BY IR.fkword ORDER BY SOMME DESC LIMIT 0,50';
    $res = GetAll($sql);
    p('<pre>');
    //implode('<br/>',$res);
    //print_r($res);
    foreach ($res as $row)
        p($row['SOMME'] . "\t" . $row['word'] . "\n");
    p('</pre>');
}


function du($dir)
{
    $res = `/usr/bin/du -sk $dir`;             // Unix command
    preg_match('/\d+/', $res, $KB); // Parse result
    $MB = round($KB[0] / 1024, 1);  // From kilobytes to megabytes
    return $MB;
}

function emptyCache()
{
    global $_Gconfig;
    echo '<ul class="nav nav-list">';
    foreach ($_Gconfig['cachePaths'] as $k => $v) {

        echo '<li><a href="?globalAction=emptyCache&empty=' . $k . '"><i class="icon-trash"></i>' . t($k) . ' (' . du($v) . ' Mo)</a>';

        if (akev($_REQUEST, 'empty') == $k || akev($_REQUEST, 'empty') == '_allCaches') {
            p('<span class="well">');
            emptyDir($v, true);
            p('</span>');
        }
        echo '</li>';
    }

    echo '<li class="divider"></li><li><a href="?globalAction=emptyCache&empty=_allCaches"><i class="icon-fire"></i>' . t('tout') . '</a></li>';
    echo '</ul>';
    /* emptyDir($cachepath,$_REQUEST['deleteThumbs']);
      emptyDir($gb_obj->include_path.'/../imgc/');
      emptyDir($gb_obj->include_path.'/cache_agr/');
     */
}

function emptyDir($cachepath, $dirsToo = false)
{
    if (!is_dir($cachepath)) {
        return;
    }
    if ($handle = opendir($cachepath)) {
        $nbok = 0;
        $nbfiles = 0;
        /* This is the correct way to loop over the directory. */
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..' && (!is_dir($cachepath . $file) || $dirsToo)) { //
                $nbfiles++;
                $nbok += rm($cachepath . $file);
            }
        }
        closedir($handle);
        p('' . $cachepath . ' : ' . $nbok . ' ' . t('files_on') . ' ' . $nbfiles . ' ' . t('have_been_removed'));
    }
}

function rm($fileglob)
{
    if (is_string($fileglob)) {
        if (is_file($fileglob)) {
            $name = basename($fileglob);
            if (substr($name, 0, 1) == '.') {
                return false;
            }
            return unlink($fileglob);
        } else if (is_dir($fileglob)) {
            $ok = rm("$fileglob/*");
            if (!$ok) {
                return false;
            }
            return rmdir($fileglob);
        } else {
            $matching = glob($fileglob);
            if ($matching === false) {
                trigger_error(sprintf('No files match supplied glob %s', $fileglob), E_USER_WARNING);
                return false;
            }
            $rcs = array_map('rm', $matching);
            if (in_array(false, $rcs)) {
                return false;
            }
        }
    } else if (is_array($fileglob)) {
        $rcs = array_map('rm', $fileglob);
        if (in_array(false, $rcs)) {
            return false;
        }
    } else {
        trigger_error('Param #1 must be filename or glob pattern, or array of filenames or glob patterns', E_USER_ERROR);
        return false;
    }

    return true;
}

function saveTemoinChange()
{
    updateParam('date_update_anything', time());
    @file_put_contents(INCLUDE_PATH . '/temoinchange', date('c'));
}