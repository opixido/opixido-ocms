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

function adminer_object() {

    class AdminerSoftware extends Adminer {

        function credentials() { // server, username and password for connecting to database
            return array('localhost', 'root', 'lasergun');
        }

        function database() { // database name, will be escaped by Adminer
            return 'ocms';
        }

    }

    return new AdminerSoftware;
}

function o_adminer() {
    define('DB', 'ocms');
    define("ME", preg_replace('~^[^?]*/([^?]*).*~', '\\1', $_SERVER["REQUEST_URI"]) . '?globalAction=o_adminer&' . (SERVER !== null ? DRIVER . "=" . urlencode(SERVER) . '&' : '') . (isset($_GET["username"]) ? "username=" . urlencode($_GET["username"]) . '&' : '') . (DB != "" ? 'db=' . urlencode(DB) . '&' . (isset($_GET["ns"]) ? "ns=" . urlencode($_GET["ns"]) . "&" : "") : ''));

    $_GET['username'] = 'root';
    echo '<div style="position:relative">';
    require_once('adminer-3.3.4-en.php');
    echo '</div>';
}

function o_createPlugin() {
    global $_Gconfig;
    echo '<style type="text/css">
				#createtable {
					border:1px solid;
				}
				#createtable td{
					background:#eee;
					text-align:center;
					
				}
				#createtable th{
					background:#e0e0e0;
					padding:5px;
					text-align:left;
				}
				pre {
					font-family:courier new, monospace;border:1px solid;background:white;padding:5px;
				}
				
				.listimg img {
					display:block;
					float:left;
					margin:3px;
					padding:2px;
					border:1px solid #999;
				}
				</style>';

    if (!$_REQUEST['table']) {
        clearCache();
        $tables = getTables();
        echo '<ul>';
        foreach ($tables as $v) {
//if(substr($v,0,2) != 's_') {
            echo '<li><a href="?globalAction=o_createPlugin&table=' . $v . '">' . $v . '</a></li>';
//}
        }
        echo '</ul>';
    } else if (!$_POST['DoIt']) {
        $table = $_REQUEST['table'];
        $tab = getTabField($table);

        echo '<form method="post" action="index.php">
				<input type="hidden" name="globalAction" value="o_createPlugin" />
				<input type="hidden" name="table" value="' . $table . '" />
				<input type="hidden" name="DoIt" value="1" />
				
				';

        echo ('<h1>' . $table . '</h1>');
        echo '<table id="createtable" >';
        echo '<tr>
				<th>Nom</th>
				<th>Label</th>
				<th>Upload</th>
				<th>Wysiwyg</th>
				<th>Relation</th>
				<th>Obligatoire</th>
				<th>Url</th>
				<th>Mail</th>
				<th>onglet</th>
				<th>Nom affiché</th>
			</tr>';

        $tables = getTables();
        $tableList = '<option value=""></option>';
        foreach ($tables as $v) {
            $tableList .= '<option value="' . $v . '">' . $v . '</option>';
        }

        foreach ($tab as $k => $v) {
            echo '<tr>';
            echo '<th>' . $k . '</th>';

            echo '<td> <input type="checkbox" name="tabFormsTitle[]" value="' . $k . '" /></td>';

            if ($v->type == 'varchar') {
                echo '<td> <input type="checkbox" name="uploadFields[]" value="' . $k . '" /><br/>
						w : <input type="text" size="1" name="maxW[' . $k . ']" /> h : <input type="text" size="1" name="maxH[' . $k . ']" /></td>';
            } else {
                echo '<td>&nbsp;</td>';
            }


            if ($v->type == 'text' || $v->type == 'longtext' || $v->type == 'mediumtext') {
                echo '<td> <input type="checkbox" name="rteFields[]" value="' . $k . '" /></td>';
            } else {
                echo '<td>&nbsp;</td>';
            }

            if ($v->type == 'int' && $k != getPrimaryKey($table)) {
                echo '<td><select name="relation[' . $k . ']">';
                echo $tableList;
                echo '</select></td>';
            } else {
                echo '<td>&nbsp;</td>';
            }

            echo '<td> <input type="checkbox" name="neededFields[]" value="' . $k . '" /></td>';

            if ($v->type == 'varchar') {
                echo '<td> <input type="checkbox" name="urlFields[]" value="' . $k . '" /></td>';
            } else {
                echo '<td>&nbsp;</td>';
            }

            if ($v->type == 'varchar') {
                echo '<td> <input type="checkbox" name="mailFields[]" value="' . $k . '" /></td>';
            } else {
                echo '<td>&nbsp;</td>';
            }
            if ($k != getPrimaryKey($table)) {
                echo '<td><input type="text" name="onglets[' . $k . ']" value="info" /></td>';
                echo '<td><input type="text" name="tradChamp[' . $k . ']" value="' . ucfirst(str_replace('_', ' ', $k)) . '" /></td>';
            } else {
                echo '<td><input type="text" name="onglets[' . $k . ']" value="" /></td>';
                echo '<td><input type="text" name="tradChamp[' . $k . ']" value="" /></td>';
            }
        }
        echo '</table>';


        for ($k = 0; $k <= 2; $k++) {
            echo '<p>';
            echo 'Table de relation n<>n <select name="tablerel[' . $k . '][name]">' . $tableList . '</select> ';
            echo ' avec table distante : <select name="tablerel[' . $k . '][dist]">' . $tableList . '</select><br/>';
            echo ' Clef vers : ' . $table . ' : <input type="text" name="tablerel[' . $k . '][fk1]" />';
            echo ' Clef vers table distant : <input type="text" name="tablerel[' . $k . '][fk2]" />';
            echo '</p>';
        }


        echo '<p>';
        if ($tab[ONLINE_FIELD]) {
            echo '<strong>Eléments masquables (champ ' . ONLINE_FIELD . ' présent)</strong>';
        } else {
            echo '<strong>Eléments NON masquables (champ ' . ONLINE_FIELD . ' absent)</strong>';
        }
        echo '<br/>';
        if ($tab[VERSION_FIELD]) {
            echo '<strong>Eléments en deux versions visibles ou non (champ ' . VERSION_FIELD . ' présent)</strong>';
        } else {
            echo '<strong>Eléments simple immédiatement visibles (champ ' . VERSION_FIELD . ' absent)</strong>';
        }

        if ($tab['fk_rubrique_id']) {
            echo '<p>Dupliquer cette table avec la validation de la rubrique ? <input type="checkbox" name="duplicate" value="1" /> </p>';
        }

        echo '</p>';

        echo '<p>Nom du plugin (en code): <input type="text" name="nomplug" value="' . substr($table, strpos($table, '_') + 1) . '" /></p>';
        echo '<p>Nom du plugin réel : <input type="text" name="nomplugreel" value="' . ucfirst(substr($table, strpos($table, '_') + 1)) . '" /></p>';

        echo '<p>Afficher dans la rubrique : <select name="rubriqueShow"><option></option>';

//debug(getArboOrdered());
        $res = getListingRubrique();
//debug($res);
        foreach ($res as $row) {
            echo '<option value="' . $row['rubrique_id'] . '">' . getLgValue('rubrique_titre', $row) . '</option>';
        }

        echo '</select></p>';


        echo '<p>Picto : <input type="text" name="picto_tosave" id="picto_tosave" value="" style="width:400px"/></p>';

        echo '<div class="listimg" style="overflow:auto;height:200px;width:600px;border:1px solid;">';



        $imgs = getAllPictos(ADMIN_PICTOS_BIG_SIZE);
        foreach ($imgs as $v) {

            $tosave = str_replace(str_replace(ADMIN_URL, '', ADMIN_PICTOS_FOLDER), '', $v);
            echo '<img onclick="gid(\'picto_tosave\').value=this.title"  src="' . $v . '" title="' . $tosave . '" />';
        }

        echo '</div>';

        echo '<p>Effectuer réellement les modifs (ne pas juste afficher) : <input type="checkbox" name="DOITFORREAL" value="1" /></p>';

        echo '<br/><input type="submit" value="Créer" />';
        echo '</form>';



        /**
         * CREATION REELLE
         */
    } else if ($_POST['DoIt'] && $_POST['table']) {

        $nom = $_POST['nomplug'];
        $chm = INCLUDE_PATH . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . $nom . DIRECTORY_SEPARATOR;

        $DOITFORREAL = $_POST['DOITFORREAL'];

        $table = $_POST['table'];

        echo '<h3>Creation du dossier</h3>';
        if ($DOITFORREAL) {
            if (!file_exists($chm)) {
                mkdir($chm);
                mkdir($chm . 'forms');
                mkdir($chm . 'tpl');
            }
        }
        echo '<h3>Fichier config.php</h3>';
        $f = '<?php ' . "\n\n";
        $f .= 'global $tabForms,$_Gconfig,$uploadFields,$rteFields,$neededFields,$relations,$urlFields,$emailFields,$admin_trads,$relinv,$tablerel;' . "\n\n";

        $tabForms[$table]['titre'] = ($_POST['tabFormsTitle']);
        foreach ($_POST['onglets'] as $k => $v) {
            if (!$v) {
                continue;
            }
            if (!$tabForms[$table]['pages'][$v] && $v) {
                $tabForms[$table]['pages'][$v] = '../plugins/' . $nom . '/forms/form.' . $v . '.php';
            }
            if (isLgField($k)) {
                if (getLgFromField($k) == $_Gconfig['LANGUAGES'][0]) {
                    $formS[$v] .= '$form->genlg("' . getBaseLgField($k) . '");' . "\n";
                }
            } else {
                $formS[$v] .= '$form->gen("' . getBaseLgField($k) . '");' . "\n";
            }
        }
        $lastOnglet = $v;
        $f .= '$tabForms["' . $table . '"]["titre"] = ' . var_export($tabForms[$table]['titre'], true) . ';' . "\n\n";
        $f .= '$tabForms["' . $table . '"]["pages"] = ' . var_export($tabForms[$table]['pages'], true) . ';' . "\n\n";
        if ($_POST['picto_tosave']) {
            $f .= '$tabForms["' . $table . '"]["picto"] = ADMIN_PICTOS_FOLDER.' . sql($_POST['picto_tosave']) . ';' . "\n\n";
        }


        $f .= "\n";

        if ($_POST['uploadFields']) {
            foreach ($_POST['uploadFields'] as $v) {
                $f .= '$uploadFields[] = "' . getBaseLgField($v) . '";' . "\n";
                if ($_POST['maxW'][($v)] && $_POST['maxH'][($v)]) {
                    $f .= '$_Gconfig["imageAutoResize"]["' . getBaseLgField($v) . '"] = array("' . $_POST['maxW'][($v)] . '","' . $_POST['maxH'][($v)] . '");' . "\n";
                }
            }

            $f .= "\n";
        }


        if ($_POST['neededFields']) {
            foreach ($_POST['neededFields'] as $v) {
                $f .= '$neededFields[] = "' . getBaseLgField($v) . '";' . "\n";
            }
            $f .= "\n";
        }

        if ($_POST['urlFields']) {
            foreach ($_POST['urlFields'] as $v) {
                $f .= '$urlFields[] = "' . getBaseLgField($v) . '";' . "\n";
            }
            $f .= "\n";
        }

        if ($_POST['mailFields']) {
            foreach ($_POST['mailFields'] as $v) {
                $f .= '$mailFields[] = "' . getBaseLgField($v) . '";' . "\n";
            }
            $f .= "\n";
        }


        if ($_POST['relation']) {
            foreach ($_POST['relation'] as $k => $v) {
                if ($v) {
                    $f .= ' $relations["' . $table . '"]["' . $k . '"] = "' . $v . '";' . "\n";
                }
            }
            $f .= "\n";
        }

        if ($_POST['duplicate']) {
            $f .= '$_Gconfig["duplicateWithRubrique"][] = "' . $table . '";' . "\n\n";
        }

        if ($tab[VERSION_FIELD]) {
            $f .= '$_Gconfig["versionedTable"][] = "' . $table . '";' . "\n\n";
        } else
        if ($tab[ONLINE_FIELD]) {
            $f .= '$_Gconfig["hideableTable"][] = "' . $table . '";' . "\n\n";
        }


        if ($_POST['tablerel']) {
            foreach ($_POST['tablerel'] as $k => $v) {
                if ($v['name']) {
                    $f .= '$tablerel["' . $v['name'] . '"] = array("' . $v['fk1'] . '"=>"' . $table . '","' . $v['fk2'] . '"=>"' . $v['dist'] . '");' . "\n\n";
                    $formS[$lastOnglet] = '$form->gen("' . $v['name'] . '");\n';
                }
            }
        }


        $f .= '$_Gconfig["adminMenus"]["' . $nom . '"][] = "' . $table . '";' . "\n\n";


        $f .= '$admin_trads["cp_txt_' . $table . '"]["' . LG . '"] = "' . $_POST['nomplugreel'] . '";' . "\n";
        $f .= '$admin_trads["' . $table . '"]["' . LG . '"] = "' . $_POST['nomplugreel'] . '";' . "\n";

        foreach ($_POST['tradChamp'] as $k => $v) {
            if ($v) {
                $f .= '$admin_trads["' . $table . '.' . $k . '"]["' . LG . '"] = ' . sql($v) . ';' . "\n";
            }
        }

        $f .= "\n\n" . '';
        echo '<pre style="">' . htmlentities($f) . '</pre>';

        if ($DOITFORREAL) {
            fillFile($chm . 'config.php', $f);
        }

        echo '<h3>Création des formulaires</h3>';

        foreach ($formS as $k => $v) {
            $v = '<?php' . "\n\n" . $v . "\n";
            echo '<h4>Fichier : plugins/' . $nom . '/forms/form.' . $k . '.php</h4>';
            echo '<pre>' . htmlentities($v) . '</pre>';
            if ($DOITFORREAL) {
                fillFile($chm . 'forms' . DIRECTORY_SEPARATOR . 'form.' . $k . '.php', $v);
            }
        }

        echo '<h3>Création du plugin</h3>';
        $sql = 'REPLACE INTO s_plugin (plugin_nom,plugin_actif,plugin_installe) VALUES (' . sql($nom) . ',1,1)';
        if ($DOITFORREAL) {
            DoSql($sql);
        }

        echo '<h3>Création du gabarit</h3>';

        $sql = 'SELECT * FROM s_gabarit WHERE gabarit_classe LIKE ' . sql('gen' . $nom) . ' AND gabarit_plugin LIKE ' . sql($nom);
        $row = GetSingle($sql);

        if (!$row) {
            $sql = 'INSERT INTO s_gabarit (gabarit_id,gabarit_titre,gabarit_classe,gabarit_plugin) VALUES ("","' . $_POST['nomplugreel'] . '","gen' . (ucfirst($nom)) . '","' . $nom . '")';
            if ($DOITFORREAL) {
                DoSql($sql);
            }
        }


        $GABARIT = InsertId();
        echo '<pre>' . $sql . '</pre>';

        $fG = '<?php
		
class gen' . (ucfirst($nom)) . ' extends baseGen {

	public $table = "' . $table . '";
	
	

}


		';
        echo '<pre>' . htmlentities($fG) . '</pre>';
        if ($DOITFORREAL) {
            fillFile($chm . 'gen' . ucfirst($nom) . '.php', $fG);
        }

        echo '<h3>Liaison de la rubrique</h3>';

        $sql = 'UPDATE s_rubrique SET fk_gabarit_id = ' . $GABARIT . '
					WHERE rubrique_id = ' . $_POST['rubriqueShow'] . '
					OR fk_rubrique_version_id = ' . $_POST['rubriqueShow'];

        if ($_POST['rubriqueShow'] && $DOITFORREAL && $GABARIT) {
            echo '<pre>' . $sql . '</pre>';
            DoSql($sql);
        }

        $_SESSION['cache'] = array();
    }
}

function fillFile($fich, $cont) {

    if (file_exists($fich)) {
        rename($fich, $fich . '-' . date('Y-m-d_H-i-s'));
    }

    file_put_contents($fich, $cont);
}

?>
