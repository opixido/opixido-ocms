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

class genSearch {

    var $nbperpage = 20;

    function genSearch($table) {

        global $gs_obj;

        $this->gs = &$gs_obj;
        $this->table = $table;
    }

    /**
     * Affiche le SELECT et le moteur de recherche simple
     *
     */
    function printAll() {

        global $searchField, $tabForms;



        /**
         * On sélectionne tous les enregistrements
         */
        $sql = 'SELECT ' . getPrimaryKey($this->table) . ', 
					' . GetTitleFromTable($this->table, " , ") . ' 
						FROM ' . $this->table . ' 
						WHERE 1 ' . GetOnlyEditableVersion($this->table) . ' 
						' . $GLOBALS['gs_obj']->sqlCanRow($this->table) . ' 
						ORDER BY ' . GetTitleFromTable($this->table, " , ");

        $this->res = GetAll($sql);


        if ($this->table != 's_rubrique') {

            p('<div style="background:url(./img/fond.bloc2.gif) #ccc;clear:both;margin-bottom:10px;border-right:1px solid #555;border-bottom:1px solid #555;">
		<h1 style="text-align:center;padding:5px;background:url(./img/fond.bloc2.gif) #eee;border-bottom:1px solid #555">' . t('search') . '</h1>
		');

            /**
             * Menu déroulant
             */
            $this->getSelect();


            /**
             * Si on vient de la liste et qu'on a effectué une action on y revient au meme endroit
             */
            if ($_GET['fromList']) {
                if ($_SESSION['LastSearch'][$this->table] == 'simple') {
                    $_POST['doSimpleSearch'] = $_REQUEST['doSimpleSearch'] = 1;
                    $_REQUEST['searchTxt'] = $_POST['searchTxt'] = $_SESSION['LastSearchQuery'][$this->table];
                    //debug('LAST SIMPLE = '.$_POST['searchTxt']);
                } else {
                    $_REQUEST['doFullSearch'] = 1;
                    $_POST = $_SESSION['LastSearchQuery'][$this->table];
                }
            }

            /**
             * Si on fait une recherche full text sur l'ensemble de la table
             */
            if ($_REQUEST['doSimpleSearch']) {
                //$this->getSimpleSearchForm();

                $_SESSION['LastSearch'][$this->table] = 'simple';
                $_SESSION['LastSearchQuery'][$this->table] = $_POST['searchTxt'];

                $this->doSimpleSearch();
            } //else if($_REQUEST['doFullSearch'] || true) {
            else {

                /**
                 * On génère le formulaire complet
                 */
                $_SESSION['LastSearch'][$this->table] = 'full';
                $_SESSION['LastSearchQuery'][$this->table] = $_POST;

                $this->getFullSearchForm();

                $res = $this->doFullSearch();

                $this->printRes($res);
            }
        }
    }

    function getSimpleSearchForm() {


        p('<form name="formChooseSearch" method="post" action="index.php" class="fond1" style="float:left;">');

        p('<input type="hidden" name="doSimpleSearch" value="1" />');

        p('<input type="hidden" name="curTable" value="' . $this->table . '" />');

        p('<label for="searchTxt" style="margin-top:5px;float:left">' . t('search_txt') . '</label>');
        p('<input type="text" id="searchTxt" name="searchTxt" value="' . $_REQUEST['searchTxt'] . '" style="float:left;margin-top:5px;" />');

        p('<label class="abutton" style="float:left;margin:0;margin-left:10px;"><input type="image" src="' . t('src_search') . '" />' . t('rechercher') . '</label>');
        p('</form>');
    }

    function getSelect() {



        $res = $this->res;


        // p('<div style="clear:both;">&nbsp;</div>
        p('<form name="formChooseSel" id="formChooseSel" method="post" action="index.php"  class="fond1" style="float:left;">');


        p('<input type="hidden" name="curTable" value="' . $this->table . '" />');
        p('<input type="hidden" name="resume" value="1" />');
        p('<label style="margin-top:5px;float:left;" for="selectChooseSel">' . t('access_direct') . '</label>
                
                	<select id="selectChooseSel" name="curId" onChange="gid(\'formChooseSel\').submit();" style="margin-top:5px;float:left;">');

        p('<option value="" >--- ' . t("choose_item") . ' ---</option>');


        $this->pk = GetPrimaryKey($this->table);
        foreach ($res as $row) {

            $titre = truncate(GetTitleFromRow($this->table, $row), 70);

            p('<option value="' . $row[$this->pk] . '">' . $titre . '</option>');
        }
        p('</select>');

        p('<label class="abutton" style="float:left;margin:0;margin-left:10px;"><input type="image" src="' . t('src_search') . '" />' . t('go') . '</label>');
        p('</form>');

        $this->getSimpleSearchForm();
    }

    function getFullSearchForm() {

        global $searchField, $relations, $tablerel, $tabForms;

        $table = $this->table;
        $fields = getTabField($table);

        p('<div style="clear:both">&nbsp;</div>
        	<form id="search" method="post" action="index.php"  class="fond1" style="float:left;padding:5px !important;">');

        p('<fieldset style="border:0;padding:0;margin:0;">');


        p('<input type="hidden" name="curTable" value="' . $this->table . '" />');
        p('<input type="hidden" name="doFullSearch" value="1" />');



        $i = 0;
        //while(list($k,$v) = each($tables[$table])) {

        if (!count($searchField[$table])) {

            //   debug('CHAMPS DE RECHERCHE NON DEFINIS DANS LA CONFIGURATION');
            $searchField[$table] = $tabForms[$table]['titre'];
        }


        while (list($kk, $vv) = @each($searchField[$table])) {

            $k = $vv;
            $v = $fields[$vv];


            if ($k != 'dessin_fichier') {
                p('<div class="fond2" style="float:left">');
                if ($k != "pk") {
                    ;
                    p('<label  style="float:left;">' . t($k) . '</label>');
                    p('<div class="clearer">&nbsp;</div>');
                    p('<div style="float:left;">');


                    if (is_array($tablerel[$k])) {

                        reset($tablerel);
                        reset($tablerel[$k]);
                        while (list( $k2, $v2 ) = each($tablerel[$k])) {
                            if ($v2 == $table) {
                                $fk1 = $k2;
                            } else {
                                $fk2 = $k2;
                                $fk_table = $v2;
                            }
                        }
                        reset($tablerel[$k]);
                        $label = GetTitleFromTable($fk_table, " , ");
                        $thiskey = GetPrimaryKey($fk_table);

                        $sql = "SELECT * FROM " . $fk_table . " " . GetOnlyEditableVersion($fk_table) . " ORDER BY " . $label;
                        $res = GetAll($sql);

                        p('<select style="height:70px;float:left;"  name="' . $k . '[]" multiple="multiple" >');

                        foreach ($res as $row) {
                            $sel = @in_array($row[$thiskey], $_POST[$k]) ? 'selected="selected"' : '';
                            p('<option ' . $sel . ' value="' . $row[$thiskey] . '">' . limit(GetTitleFromRow($fk_table, $row, " "), 30) . '</option>');
                        }
                        p('</select>');
                    } else
                    if ($relations[$table][$k]) {


                        $tablenom = $relations[$table][$k];

                        $label = GetTitleFromTable($tablenom, " , ");

                        $thiskey = GetPrimaryKey($tablenom);

                        $sql = "SELECT " . $thiskey . " , " . $label . " FROM " . $tablenom . " , " . $table . " WHERE " . $k . " = " . $thiskey . " " . GetOnlyEditableVersion($fk_table) . ' GROUP BY  ' . $thiskey . " ORDER BY " . $label;
                        $res = GetAll($sql);

                        p('<select  style="height:70px;float:left;" name="' . $k . '[]" multiple="multiple" >');

                        foreach ($res as $row) {
                            $sel = @in_array($row[$thiskey], $_POST[$k]) ? 'selected="selected"' : '';
                            p('<option ' . $sel . ' value="' . $row[$thiskey] . '">' . limit(GetTitleFromRow($tablenom, $row, " "), 30) . '</option>');
                        }
                        p('</select>');
                    } else {
                        //print_r($v);
                        //debug($v);
                        if (($v->type == "int" && $v->max_length < 2 ) || $v->type == "tinyint") {
                            $sel = $_POST[$k] == "" ? 'selected="selected"' : '';
                            $sel0 = $_POST[$k] == "0" ? 'selected="selected"' : '';
                            $sel1 = $_POST[$k] == 1 ? 'selected="selected"' : '';
                            p('
                            <select style="float:left;" name="' . $k . '">
                                <option ' . $sel . ' value="">----------</option>
                                <option ' . $sel0 . '  value="0">' . t('non') . '</option>
                                <option ' . $sel1 . ' value="1">' . t('oui') . '</option>
                            </select>
                        ');
                        } else if ($v->type == "datetime" || $v->type == "date") {

                            $sel = $_POST[$k . '_type'] == "" ? 'selected="selected"' : '';
                            $sel0 = $_POST[$k . '_type'] == "inf" ? 'selected="selected"' : '';
                            $sel1 = $_POST[$k . '_type'] == "eg" ? 'selected="selected"' : '';
                            $sel2 = $_POST[$k . '_type'] == "sup" ? 'selected="selected"' : '';
                            p('
                            <select style="float:left;" name="' . $k . '_type">
                                <option ' . $sel . ' value="">-</option>
                                <option ' . $sel0 . '  value="inf"><</option>
                                <option ' . $sel1 . ' value="eg">=</option>
                                <option ' . $sel2 . ' value="sup">></option>
                            </select>
                            <input style="float:left;width:50px" type="text" name="' . $k . '" value="' . $_REQUEST[$k] . '" />
                        ');
                        } else if ($v->type == 'enum') {
                            $values = getEnumValues($this->table, $v->name);
                            p('<select  style="height:70px;float:left;" name="' . $k . '[]" multiple="multiple" >');

                            foreach ($values as $rowe) {
                                $sel = @in_array($rowe, $_POST[$k]) ? 'selected="selected"' : '';
                                p('<option ' . $sel . ' value="' . $rowe . '">' . t('enum_' . $rowe) . '</option>');
                            }
                            p('</select>');
                        } else {
                            p('<input style="float:left;" type="text" name="' . $k . '" value="' . $_REQUEST[$k] . '" />');
                        }
                    }
                    // p('<input class="rightsub" type="submit" value="'.t('rechercher').'"  />');
                    p('</div>');


                    $i++;
                }
                p('</div>');
            }
        }


        p('<label class="abutton" style="float:left;margin:0;margin-left:5px;"><input type="image" src="' . t('src_search') . '" />' . t('rechercher') . '</label>');

        p('</fieldset>');
        p('</form>');
    }

    function printRes($res) {

        /*
         *
         * Formate les resultats sous la forme d'un tableau avec des liens pour modifier
         */



        print('<div class="clearer">&nbsp;</div></div>');
        global $searchField, $relations, $tabForms, $tables, $Gconfig;


        $r = "";

        $table = $this->table;
        $maxLength = 100;


        if (is_array($searchField[$table])) {
            $tablo = $searchField[$table];
        } else {
            $tablo = $tabForms[$table]['titre'];
        }


        /**
         * Calcul des pages
         */
        $totRes = count($res);
        $_SESSION['LastStart'][$this->table] = $lstart = $_GET['lstart'] != '' ? $_GET['lstart'] : ($_GET['fromList'] ? $_SESSION['LastStart'][$this->table] : 0);

        $lend = $lstart + $this->nbperpage;
        if ($lend > $totRes) {
            $lend = $totRes;
        }


        $pageTot = ceil($totRes / $this->nbperpage);


        $pageNo = ceil($lstart / $this->nbperpage) + 1;

        if ($pageNo > $pageTot) {
            $pageNo = $pageTot;
            $lstart = ($pageNo - 1) * $this->nbperpage;
            $lend = $lstart + $this->nbperpage;
        }


        /*
          $pageTot = $pageTot == 0 ? 1 : $pageTot;
          $pageNo = $pageNo == 0 ? 1 : $pageNo;
         */



        /**
         * Suivant / Précédent
         */
        $r = '<form method="get" action="index.php" >
        <input type="hidden" name="curTable" value="' . $this->table . '" />
        <input type="hidden" name="fromList" value="1" />
        
        
        
        <table style="clear:both" border="0" class="genform_table" width="99%">';


        /**
         * Nombre de résultats
         */
        $r .= '<td colspan="10">' . ('<h4  >' . t('il_y_a') . ' ' . count($res) . ' ' . t('resultats') . '</h4>') . '</td>';


        if ($totRes == 0) {
            echo $r . '</tr></table></form>';
            return;
        }



        $r .= '<tr><td style="text-align:center;display:block;width:auto;" width="50" class="fond1" >';


        /**
         * Bouton précédent
         */
        if ($lstart > 0) {
            $r .= '<a style="display:block" href="?fromList=1&amp;curTable=' . $this->table . '&amp;lstart=' . ($lstart - $this->nbperpage) . '">' . t('page_precedente') . '</a>';
        }
        $r .= '</td>';
        $r .= '<td style="text-align:center"  colspan="' . count($tablo) . '">' . t('page') . ' ' . ($pageNo . ' / ' . $pageTot) . '<br/>';


        /**
         * Liste des pages
         */
        for ($p = 0; $p < $pageTot; $p++) {
            $curP = ($p * $this->nbperpage);

            $sel = ($curP == $lstart) ? 'style="font-weight:bold;text-decoration:underline;"' : '';

            $r .= '<a ' . $sel . ' href="?fromList=1&amp;curTable=' . $this->table . '&amp;lstart=' . $curP . '">' . ($p + 1) . '</a> ';
        }
        $r.= '</td>';

        /**
         * Bouton suivant
         */
        $r .= '<td style="text-align:center;display:block;width:auto;" width="180"  class="fond1" >';
        if ($totRes > $lend) {
            $r .= '<a style="display:block" href="?fromList=1&amp;curTable=' . $this->table . '&amp;lstart=' . ($lend) . '">' . t('page_suivante') . '</a>';
        }

        $r .= '</td></tr >';







        $thisPk = GetPrimaryKey($table);

        $_Gconfig['tableSearchAsItems']['d_dessin'] = true;

        /**
         * Si on liste les items PAS EN TABLEAU
         */
        if ($_Gconfig['tableSearchAsItems'][$this->table]) {

            echo('' . $r . '</table>        	
        		<div id="gensearch_items">
        	');

            for ($k = $lstart; $k < $lend; $k++) {

                p('<div class="item" >');
                $row = $res[$k];
                $id = $row[$thisPk];


                p('<input style="float:left" type="checkbox" name="massiveActions[' . $id . ']" value="1" class="selector" checked="checked"/>');

                $form = new GenForm($_REQUEST['curTable'], 'post', $id, $row);
                $tempsConstruct += (getmicrotime() - $t1);
                $form->thumbWidth = 200;
                $form->thumbHeight = 200;
                $form->editMode = true;
                $form->onlyData = true;

                reset($tablo);

                echo $this->getActions($row);


                echo('<table class="itemtab">');
                while (list($kk, $vv) = each($tablo)) {


                    $form->bufferPrint = "";
                    $form->genFields($vv);
                    $valeur = $form->getBuffer();

                    $valeur = truncate($valeur, $maxLength);

                    p('<tr><th>' . t($vv) . '</th><td>' . $valeur . '' . "</td>");
                }
                echo '</table>';



                p('</div>');
            }

            p('</div>');



            /**
             * On les liste en TABLEAU
             */
        } else {

            $r .=('<tr>');


            $t = getTabField($this->table);
            $r .= ('<th style="width:20px;"  scope="col">Id</th>');
            //while(list($k,$v) = each($tables[$table])) {

            while (list(, $k) = each($tablo)) {

                if ($t[$k]->type == 'tinyint') {
                    $r .=('<th style="width:20px" scope="col">');
                } else {
                    $r .=('<th scope="col">');
                }
                $r .= t($k) . "";
                $r .=('</th>');
            }

            $r .= ('<th style="width:20px;">&nbsp;</th>');

            $r .=('</tr>');
            $r .= "\n";
            //reset($tables);



            for ($k = $lstart; $k < $lend; $k++) {

                $row = $res[$k];

                //reset($tables[$table]);

                $r .=('<tr class="' . ($k % 2 ? 'odd' : 'even') . '">');

                $id = $row[$thisPk];
                $t1 = getmicrotime();
                $form = new GenForm($_REQUEST['curTable'], 'post', $id, $row);
                $tempsConstruct += (getmicrotime() - $t1);

                $form->editMode = true;
                $form->onlyData = true;

                reset($tablo);
                //$r .= '<input type="checkbox" name="massiveActions[]" value="1" checked="checked"/>';

                $r .= '<th style="width:20px;"><input type="checkbox"  name="massiveActions[' . $id . ']" value="1" checked="checked"/> ' . $row[$thisPk] . '' . "&nbsp;</th>";
                $t1 = getmicrotime();
                while (list($kk, $vv) = each($tablo)) {
                    $form->bufferPrint = "";
                    $form->genFields($vv);
                    $valeur = $form->getBuffer();

                    $valeur = truncate($valeur, $maxLength);

                    $r .= '<td>' . $valeur . '' . "&nbsp;</td>";
                }
                $tempsGen += (getmicrotime() - $t1);
                $r .= '<td style="width:60px;">';
                /*
                  if($this->gs->can('edit',$table,$row,$row[$thisPk]))
                  $r .= '<a href="?curTable='.$table.'&curId='.$row[$thisPk].'&resume=1"><img src="'.t('src_editer').'" border="0" alt="'.t('edit').'"/></a>';
                  else
                  $r .= '<img src="'.t('src_locked').'" border="0" alt="'.t('locked').'"/>';
                 */

                $t1 = getmicrotime();

                $tempsConstructAction += (getmicrotime() - $t1);

                $r .= '<div style="width:150px;">'; //style="width:'.(23*count($actions)).'px"
                $t1 = getmicrotime();


                $r .= $this->getActions($row);
                $tempsGenAction += (getmicrotime() - $t1);

                $r.= '</div>';

                $r .='</td>';
                $r .=('</tr>');
                $r .= "\n";
            }


            p('<div style="clear:both;">');


            if (count($res) > 0) {


                p($r);
            }
            p('</div>');

            p('</table>');
        }


        $actions = $GLOBALS['gs_obj']->getActions($this->table);


        global $_Gconfig;

        p('<div style="clear:both;text-align:right"  class="fond1">');
        $html = '<select name="mass_action">';
        $html .= '<option value="">-----------------</option>';
        foreach ($actions as $action) {

            if (!in_array($action, $_Gconfig['nonMassAction'])) {

                $html .= '<option value="' . $action . '">' . t($action) . '</option>';
            }
        }


        $html .= '
	   	</select>
	   	<input type="submit" value="' . t('ok') . '" />	   	
	   	</div>
	   	</form>';

        echo $html;
    }

    /**
     * Retourne la liste des boutons pour les actions possibles
     *
     * @param unknown_type $row
     * @return unknown
     */
    function getActions($row) {

        $table = $this->table;
        $thisPk = getPrimaryKey($table);
        $id = $row[$thisPk];
        $actions = $GLOBALS['gs_obj']->getActions($table, $id, $row);

        foreach ($actions as $action) {

            $ga = new genAction($action, $table, $id, $row);


            if ($ga->checkCondition()) {

                if (tradExists(('src_' . $action))) {
                    $srcBtn = t(('src_' . $action));
                } else {
                    $srcBtn = ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE . '/emblems/emblem-system.png';
                }
                //debug($action. ' - '.$srcBtn);

                if ($action == 'del') {
                    $r .= '<a href="?genform_action%5B' . $action . '%5D=1&amp;curTable=' . $table . '&amp;curId=' . $id . '&amp;action=' . $action . '&amp;fromList=1" onclick="return confirm(\'' . t('confirm_suppr') . '\')"><img src="' . $srcBtn . '" alt="' . t($action) . '"/></a>';
                } else {
                    if (method_exists($ga->obj, 'getSmallForm')) {
                        $r .= $ga->obj->getSmallForm();
                    } else {
                        $r .= '<a href="?genform_action%5B' . $action . '%5D=1&amp;curTable=' . $table . '&amp;curId=' . $id . '&amp;action=' . $action . '&amp;fromList=1"><img src="' . $srcBtn . '" alt="' . t($action) . '"/></a>';
                        //$r .= '<a href="?genform_action%5B'.$action.'%5D=1&amp;curTable='.$table.'&amp;curId='.$id.'&amp;action='.$action.'&amp;fromList=1"><img src="'.$srcBtn.'" alt="'.t($action).'"/></a>';
                    }
                }
            }
        }



        return $r;
    }

    function getTableActions() {
        
    }

    /**
     * Recherche spéciale par champs
     *
     * @param string $searchTxt
     * @param string $clauseSql
     * @return array liste de résultats MySQL
     */
    function doFullSearch($searchTxt = '', $clauseSql = '') {

        global $searchField, $relations, $tablerel, $_Gconfig;

        /*
         * Create query for full relational search
         */
        $searchTxt = $searchTxt ? $searchTxt : $_REQUEST['searchTxt'];

        $table = $this->table;
        $curkey = GetPrimaryKey($table);

        /**
         * SQL start
         */
        $presql = 'SELECT DISTINCT(T.' . $curkey . ') , T.* FROM ' . $table . ' AS T ';

        /**
         * Default where clause
         */
        if (in_array($table, $_Gconfig['multiVersionTable'])) {
            $wheresql = ' WHERE 1 ';
        } else {
            $wheresql = ' WHERE 1 ' . GetOnlyEditableVersion($table, "T") . ' ';
        }
        $wheresql .= $GLOBALS['gs_obj']->sqlCanRow($this->table) . ' ';
        $wheresql .= $clauseSql;



        if (!is_array($searchField[$table])) {

            /**
             * No search field in configuration
             * Searching only on titles
             */
            $searchField[$table] == $tabForms[$table]['titre'];
        }


        @reset($searchField[$table]);
        $mySearchField = $searchField;
        /* reset($mySearchField);
          foreach($mySearchField[$table] as $k=>$v) {
          if(isBaseLgField($v,$table)) {
          unset($mySearchField[$table][$k] );
          foreach($_Gconfig['LANGUAGES'] as $lg) {
          $mySearchField[$table][] = $v.'_'.$lg;
          }
          }
          } */
        @reset($mySearchField[$table]);

        /**
         * If there is a global search field, 
         * looping on all text fields
         * 
         * @deprecated THIS SHOULD NOT APPEND
         * @see $this->doSimpleSearch();
         * 
         */
        if (strlen($searchTxt)) {
            $tabfield = getTabField($table);
            $wheresql .= ' AND ( 0 ';
            $mots = explode(" ", $searchTxt);

            while (list($k, $v) = each($tabfield)) {
                if ($v->type == "varchar" || $v->type == "text") {
                    reset($mots);
                    $wheresql .= " OR ( 1 ";
                    while (list(, $mot) = each($mots)) {
                        $wheresql .= " AND " . $k . ' LIKE "%' . $mot . '%" ';
                    }
                    $wheresql .= " ) ";
                }
            }
            $wheresql .= ' ) ';
        }


        /**
         * Getting all table fields
         */
        $tabs = getTabField($this->table);



        /**
         * Looping on the search Fields
         */
        while (list($k, $v) = @each($mySearchField[$table])) {
            $k = $v;
            $v = $_POST[$v];



            if (is_array($tablerel[$k]) && is_array($v)) {

                /**
                 * It's an n:n relation
                 */
                reset($tablerel[$k]);
                while (list( $k2, $v2 ) = each($tablerel[$k])) {
                    if ($v2 == $table) {
                        $fk1 = $k2;
                    } else {
                        $fk2 = $k2;
                        $fk_table = $v2;
                    }
                }

                $in = implode(" , ", $v);
                $presql .= ' , ' . $k . ' ';
                $wheresql .= " AND " . $k . "." . $fk2 . " IN ( " . $in . " ) AND " . $k . "." . $fk1 . " =  T." . $curkey . " " . "\n";
            } else if ($relations[$table][$k] && is_array($v)) {

                /**
                 * It's an n:1 relation
                 */
                $in = implode(" , ", $v);

                $wheresql .= " AND " . $k . " IN ( " . $in . " ) " . "\n";
            } else if ($tabs[$k]->type == "datetime" || $tabs[$k]->type == "date") {

                /**
                 * It's a date/time field
                 */
                $tabdate = array('inf' => '<', 'eg' => 'LIKE', 'sup' => '>');
                if (!$_REQUEST[$k . '_type']) {
                    $wheresql .= " AND T." . $k . " LIKE '" . $v . "%' " . "\n";
                } else {
                    $wheresql .= " AND T." . $k . " " . $tabdate[$_REQUEST[$k . '_type']] . " '" . $v . "' " . "\n";
                }
            } else if ($tabs[$k]->type == 'enum') {

                /**
                 * It's an enum field
                 */
                if (is_array($v) && count($v)) {
                    /**
                     * Multiple choices
                     */
                    $wheresql .= " AND T." . $k . " IN ('" . implode("','", $v) . "') " . "\n";
                } else if (strlen($v)) {
                    /**
                     * Single choice
                     */
                    $wheresql .= " AND T." . $k . " IN ('" . $v . "') " . "\n";
                }
            } else if (strlen($v) > 0) {

                /**
                 * Anything else ...
                 */
                if (isBaseLgField($k, $table)) {

                    $mots = explode(" ", $v);
                    while (list(, $mot) = each($mots)) {
                        $wheresql .= ' AND ( 0 ';
                        foreach ($_Gconfig['LANGUAGES'] as $lg) {
                            $wheresql .= " OR T." . $k . "_" . $lg . " LIKE '%" . $mot . "%' " . "\n";
                        }
                        $wheresql .= ' ) ';
                    }
                } else {
                    $mots = explode(" ", $v);
                    while (list(, $mot) = each($mots)) {
                        $wheresql .= " AND T." . $k . " LIKE '%" . $mot . "%' " . "\n";
                    }
                }
            }
        }


        if ($_REQUEST['champ'] && $_REQUEST['curId'] && $_Gconfig['specialListingWhere'][$_REQUEST['champ']]) {
            $wheresql .= $_Gconfig['specialListingWhere'][$_REQUEST['champ']]($_REQUEST['curId']);
        }

        $label = GetTitleFromTable($table, " , ");

        if (in_array($table, $_Gconfig['multiVersionTable'])) {
            $wheresql .= " GROUP BY T." . MULTIVERSION_FIELD . " ORDER BY T." . MULTIVERSION_FIELD . ",  T." . $label;
        } else {
            $wheresql .= " ORDER BY T." . $label;
        }

        $res = GetAll($presql . $wheresql);


        return $res;
    }

    /**
     * Recherche simple sur les champs textuels 
     *
     */
    function doSimpleSearch() {

        global $tabForms;


        $id = getPrimaryKey($this->table);
        $tabfield = getTabField($this->table);

        //".getPrimaryKey($this->table).", ".GetTitleFromTable($this->table,' , ')." 


        $sql = "SELECT *
        			 FROM " . $this->table . "
        			 	 WHERE  1 " . GetOnlyEditableVersion($this->table) . ' 
        			 	 ' . $GLOBALS['gs_obj']->sqlCanRow($this->table) . ' AND ( ';

        $mots = split(" ", $_REQUEST['searchTxt']);

        $sql .= " 0 ";





        while (list($k, $v) = each($tabfield)) {
            if ($v->type == "varchar" || $v->type == "text") {
                reset($mots);
                $sql .= " OR ( 1 ";
                while (list(, $mot) = each($mots))
                    $sql .= " AND " . $k . ' LIKE ' . sql('%' . $mot . '%') . ' ';
                $sql .= " ) ";
            }
        }

        if ($_REQUEST['searchId'])
            $sql .= " ) ";

        $label = GetTitleFromTable($this->table, " , ");

        $sql .= " ) ORDER BY " . $label;


        $res = GetAll($sql);


        $this->printRes($res);
    }

}

?>
