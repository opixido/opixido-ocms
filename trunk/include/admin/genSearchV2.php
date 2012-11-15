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

class genSearchV2 {

    var $nbperpage = 20;
    var $lstart = 0;

    function genSearchV2($table) {

        global $gs_obj;

        $this->gs = &$gs_obj;
        $this->table = $table;
        $this->lstart = empty($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * $this->nbperpage;
    }

    /**
     * Affiche le SELECT et le moteur de recherche simple
     *
     */
    function printAll() {

        global $searchField, $tabForms, $_Gconfig;



        /**
         * On sélectionne tous les enregistrements
         */
        $sql = ' ' . getPrimaryKey($this->table) . ',
					' . GetTitleFromTable($this->table, " , ") . ' 
				FROM ' . $this->table . ' AS T
				WHERE 1 ' . GetOnlyEditableVersion($this->table) . ' 
				' . $GLOBALS['gs_obj']->sqlCanRow($this->table);

        if (isset($_Gconfig['arboredTable'][$this->table])) {
            $sql .= ' AND ( ' . $_Gconfig['arboredTable'][$this->table] . ' = 0 OR ' . $_Gconfig['arboredTable'][$this->table] . ' IS NULL )';
        }

        $sqlCount = 'SELECT COUNT(' . getPrimaryKey($this->table) . ') AS NB , ' . $sql . ' GROUP BY ' . getPrimaryKey($this->table) . '';
        $rCount = DoSql($sqlCount);
        $rCount = $rCount->FetchRow();
        $this->count = $rCount['NB'];

        $sql = 'SELECT ' . $sql;

        $sql .= ' ORDER BY ';

        if (isset($_REQUEST['order']) && array_key_exists($_REQUEST['order'], getTabField($this->table))) {
            $sql .= 'T.' . $_REQUEST['order'] . ' ';
            if (akev($_GET, 'to') == 'asc') {
                $sql .= ' ASC , ';
            } else {
                $sql .= ' DESC , ';
            }
        }

        if (isset($_Gconfig['orderedTable'][$this->table])) {
            $sql .= ' T.' . $_Gconfig['orderedTable'][$this->table] . ' ASC , ';
        }

        //ORDER BY '.GetTitleFromTable($this->table," , ");

        $sql .= " T." . GetTitleFromTable($this->table, " , ");

        $sql .= $this->limit();

        $this->res = DoSql($sql);


        if ($this->table != 's_rubrique') {

            p('<div class="row-fluid">');

            if (!empty($searchField[$this->table])) {
                p('<div class="span3">');
            } else {
                p('<div class="span12">');
            }

            /**
             * Menu déroulant
             */
            $this->getSelect();


            /**
             * Si on vient de la liste et qu'on a effectué une action on y revient au meme endroit
             */
            if (!empty($_GET['fromList'])) {
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
            if (!empty($_REQUEST['doSimpleSearch'])) {
                //$this->getSimpleSearchForm();

                $_SESSION['LastSearch'][$this->table] = 'simple';
                $_SESSION['LastSearchQuery'][$this->table] = $_REQUEST['searchTxt'];

                $this->getSimpleSearchForm();
                if (!empty($searchField[$this->table])) {
                    $this->getFullSearchForm();
                }

                $res = $this->doSimpleSearch();
            } //else if($_REQUEST['doFullSearch'] || true) {
            else {

                /**
                 * On génère le formulaire complet
                 */
                $_SESSION['LastSearch'][$this->table] = 'full';
                $_SESSION['LastSearchQuery'][$this->table] = $_POST;
                global $searchField;

                $this->getSimpleSearchForm();
                if (!empty($searchField[$this->table])) {
                    $this->getFullSearchForm();
                }

                $res = $this->doFullSearch();
            }


            if (!empty($searchField[$this->table])) {
                p('</div>');
                p('<div class="span9">');
            }

            $this->printRes($res);
            p('</div></div>');
        }
    }

    function getSimpleSearchForm() {


        p('<form name="formChooseSearch" method="post" action="index.php" class="form-inline well" >');

        p('<input type="hidden" name="doSimpleSearch" value="1" />');

        p('<input type="hidden" name="curTable" value="' . $this->table . '" />');

        p('<div class="control-group"> <div class="controls"> <div class="input-append">');

        echo ('<input  type="text" placeholder=' . alt(ta('recherche_rapide')) . ' id="searchTxt" name="searchTxt" value="' . akev($_REQUEST, 'searchTxt') . '"/>');

        p('<button class="btn btn-mini"><img src="' . t('src_search') . '" alt=' . alt(t('rechercher')) . ' /></button></div></div></div>');

        p('</form>');
    }

    function getSelect() {

        return;
        $res = $this->res;


        if (($this->count) > 1000)
            return;

        // p('<div style="clear:both;">&nbsp;</div>
        p('<form name="formChooseSel" id="formChooseSel" method="post" action="index.php"  class="span12 form-vertical" style="float:left;">');


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

        p('<button class="btn" ><img src="' . t('src_search') . '" /></label>');
        p('</form>');

        $this->getSimpleSearchForm();
    }

    function getFullSearchForm() {

        global $searchField, $relations, $tablerel, $tabForms, $gs_obj;

        $table = $this->table;
        $fields = getTabField($table);

        p('
        	<form id="search" method="post" action="index.php"  class="well form-vertical" >');

        p('
        	<script type="text/javascript">
        		function submitFormRech(a,b) {
        			window.location = "?curTable=' . $_REQUEST['curTable'] . '&curId="+b;        			
        		}
        	</script>
        ');


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
            $v = akev($fields, $vv);

            $type = false;
            $size = false;
            if (is_object($v)) {
                $type = $v->type;
                $size = $v->max_length;
            }


            if ($k != "pk") {

                p('<div>');
                if (!empty($tablerel[$k])) {

                    p('<label> ' . t($k) . '</label>');
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

                    if (!empty($gs_obj->myroles[$fk_table]['rows'])) {
                        $sql = "SELECT * FROM " . $fk_table . " 
                            WHERE 1 " . GetOnlyEditableVersion($fk_table) . " 
                                AND " . getPrimaryKey($fk_table) . "
                                    IN (" . implode(",", $gs_obj->myroles[$fk_table]['rows']) . ")
                                ORDER BY " . $label;
                    } else {
                        $sql = "SELECT * FROM " . $fk_table . " WHERE 1 " . GetOnlyEditableVersion($fk_table) . "
                            ORDER BY " . $label;
                    }

                    $res = GetAll($sql);

                    p('<select class="selectM" id="' . $k . '" name="' . $k . '[]" multiple="multiple" >');

                    foreach ($res as $row) {
                        $sel = @in_array($row[$thiskey], $_POST[$k]) ? 'selected="selected"' : '';
                        p('<option ' . $sel . ' value="' . $row[$thiskey] . '">' . limit(GetTitleFromRow($fk_table, $row, " "), 30) . '</option>');
                    }

                    p('</select><div class="input-prepend "><span class="add-on add-on-mini"><i class="icon-search"></i></span><input type="text" class="selectMSearch input-mini" id="' . $k . '_search" onkeydown="searchInSelect(this)" /></div>');
                } else
                if (!empty($relations[$table][$k])) {

                    p('<label class="hide-text"> ' . t($k) . '</label>');
                    $tablenom = $relations[$table][$k];

                    $label = 'A.' . GetTitleFromTable($tablenom, " , A.");

                    $thiskey = GetPrimaryKey($tablenom);

                    $sql = "SELECT A." . $thiskey . " , " . $label . " FROM " . $tablenom . " AS A  , " . $table . " AS B WHERE B." . $k . " = A." . $thiskey . " " . GetOnlyEditableVersion($tablenom, 'A') . ' GROUP BY  A.' . $thiskey . " ORDER BY " . $label;
                    $res = GetAll($sql);

                    p('<select class="selectM" id="' . $k . '"  name="' . $k . '[]" multiple="multiple" >');
                    p('<option value="">' . ta($k) . '</option>');
                    foreach ($res as $row) {
                        $sel = @in_array($row[$thiskey], $_POST[$k]) ? 'selected="selected"' : '';
                        p('<option ' . $sel . ' value="' . $row[$thiskey] . '">' . limit(GetTitleFromRow($tablenom, $row, " "), 30) . '</option>');
                    }
                    p('</select><br/><input type="text" class="selectMSearch span2" id="' . $k . '_search" onkeydown="searchInSelect(this)" />');
                } else {

                    if (($type == "int" && $size < 2 ) || $type == "tinyint") {
                        $vv = akev($_POST, $k);
                        p('<label class="hide-text"> ' . t($k) . '</label>');
                        $sel = $vv == "" ? 'selected="selected"' : '';
                        $sel0 = $vv === "0" ? 'selected="selected"' : '';
                        $sel1 = $vv == 1 ? 'selected="selected"' : '';
                        p('
                            <select name="' . $k . '">
                                <option ' . $sel . ' value="">' . ta($k) . '</option>
                                <option ' . $sel0 . '  value="0">' . t('non') . '</option>
                                <option ' . $sel1 . ' value="1">' . t('oui') . '</option>
                            </select>
                        ');
                    } else if ($type == "datetime" || $type == "date") {
                        p('<label class=""> ' . t($k) . '</label>');
                        $vv = akev($_POST, $k . '_type');
                        $sel = $vv == "" ? 'selected="selected"' : '';
                        $sel0 = $vv == "inf" ? 'selected="selected"' : '';
                        $sel1 = $vv == "eg" ? 'selected="selected"' : '';
                        $sel2 = $vv == "sup" ? 'selected="selected"' : '';
                        p('
                            <select  name="' . $k . '_type">
                                <option ' . $sel . ' value="">-</option>
                                <option ' . $sel0 . '  value="inf"><</option>
                                <option ' . $sel1 . ' value="eg">=</option>
                                <option ' . $sel2 . ' value="sup">></option>
                            </select>
                            <input type="text" name="' . $k . '" value=' . alt(akev($_REQUEST, $k)) . ' />
                        ');
                    } else if ($type == 'enum') {
                        p('<div><label class=""> ' . t($k) . '</label>');
                        $values = getEnumValues($this->table, $v->name);
                        p('<select  name="' . $k . '[]" multiple="multiple" >');

                        foreach ($values as $rowe) {
                            $sel = @in_array($rowe, $_POST[$k]) ? 'selected="selected"' : '';
                            p('<option ' . $sel . ' value="' . $rowe . '">' . t('enum_' . $rowe) . '</option>');
                        }
                        p('</select>');
                    } else {
                        p('<label class="hide-text"> ' . t($k) . '</label>');

                        p('<input placeholder=' . alt(ta($k)) . '  type="text"
                        			id="rech_' . $k . '" name="' . $k . '" 
                        			value="' . akev($_REQUEST, $k) . '" />');

                        p('

                        <script type="text/javascript">                        
                        jQuery(function(){
                                options = { serviceUrl:"?xhr=autocompletesearch&table=' . $_REQUEST['curTable'] . '&champ=' . $k . '", onSelect: submitFormRech   };
                                a = $("#rech_' . $k . '").autocomplete(options);
							});	
                         </script>
						');
                    }
                }

                p('</div>');


                $i++;
            }
        }


        p('<button class="btn"><img src="' . t('src_search') . '" />' . t('rechercher') . '</button>');


        p('</form>');
    }

    function printRes($res) {

        /*
         *
         * Formate les resultats sous la forme d'un tableau avec des liens pour modifier
         */


        global $searchField, $relations, $tabForms, $tables, $Gconfig, $tablerel;


        $r = "";

        $table = $this->table;
        $maxLength = 100;


        if (!empty($searchField[$table])) {
            $tablo = $searchField[$table];
        } else {
            $tablo = $tabForms[$table]['titre'];
        }


        /**
         * Calcul des pages
         */
        $totRes = $this->count;
        $_SESSION['LastStart'][$this->table] = $lstart = akev($_GET, 'lstart') != '' ? $_GET['lstart'] : (!empty($_GET['fromList']) ? $_SESSION['LastStart'][$this->table] : 0);

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

        /**
         * Suivant / Précédent
         */
        $r = '<form method="get" action="index.php" id="formpages" >
        <input type="hidden" name="curTable" value="' . $this->table . '" />
        <input type="hidden" name="fromList" value="1" />
        
        ';



        $pagi = new pagination($pageTot);
        $r .= $pagi->gen();

        $r .= '<div>';


        $r .= '<table border="0" class="table table-striped table-bordered table-condensed" >';


        /**
         * Nombre de résultats
         */
        $r .= '<tr><td colspan="10">' . ('<h4  >' . t('il_y_a') . ' ' . ( $this->count ) . ' ' . t('resultats') . '</h4>') . '</td></tr>';


        if ($totRes == 0) {
            echo $r . '</table></div></form>';
            return;
        }



        $thisPk = GetPrimaryKey($table);

        $_Gconfig['tableSearchAsItems']['d_dessin'] = true;

        /**
         * Si on liste les items PAS EN TABLEAU MAIS EN BLOCS
         */
        if (!empty($_Gconfig['tableSearchAsItems'][$this->table])) {

            echo('' . $r . '</table>
        		<div id="gensearch_items">
        	');

            for ($k = $lstart; $k < $lend; $k++) {

                p('<div class="item" >');
                $row = $res[$k];
                $id = $row[$thisPk];


                p('<input type="checkbox" name="massiveActions[' . $id . ']" value="1" class="selector" />');

                $form = new GenForm($_REQUEST['curTable'], 'post', $id, $row);
                $tempsConstruct += ( getmicrotime() - $t1);
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

            $r .= ( '<tr>');


            $t = getTabField($this->table);
            $r .= ( '<th style="width:20px;"  scope="col">Id</th>');
            //while(list($k,$v) = each($tables[$table])) {

            /**
             * Entêtes du tableau des résultats
             */
            while (list(, $k) = each($tablo)) {

                /**
                 * Champs de type boolean
                 */
                if (!empty($t[$k]) && $t[$k]->type == 'tinyint') {
                    $r .= ( '<th class="colonne_booleen" scope="col">');
                } else {
                    $r .= ( '<th scope="col">');
                }
                $r .= t($k) . "";

                /**
                 * Boutons de tri des résultats - Sur tous type de champs sauf les tables de relations
                 */
                if (empty($tablerel[$k])) {

                    $r .= ( '<br/><a href="?fromList=1&amp;curTable=' . $_REQUEST['curTable'] . '&order=' . $k . '&to=asc&lstart=0">
									<img src="img/sort_down.jpg" alt="Tri croissant" title="Tri croissant" />
								</a>&nbsp;
								<a href="?fromList=1&amp;curTable=' . $_REQUEST['curTable'] . '&order=' . $k . '&to=desc&lstart=0">
									<img src="img/sort_up.jpg" alt="Tri décroissant" title="Tri décroissant" />
								</a>');

                    $r .= ( '</th>');
                }
            }

            $r .= ( '<th style="width:20px;">&nbsp;</th>');

            $r .= ( '</tr>');
            $r .= "\n";


            /**
             * Liste des résultats
             */
            //for ($k = $lstart; $k < $lend; $k++) {
            foreach ($res as $row) {
                /*
                  $res->Move($k);
                  $row = $res->FetchRow();
                 */
                $r .= ( '<tr class="' . ($k % 2 ? 'odd' : 'even') . '">');

                $id = $row[$thisPk];
                $t1 = getmicrotime();

                $form = new GenForm($_REQUEST['curTable'], 'post', $id, $row);

                $form->editMode = true;
                $form->onlyData = true;

                reset($tablo);

                $r .= '<th style="width:20px;"><input type="checkbox"  name="massiveActions[]" value="' . $id . '" /> ' . $row[$thisPk] . '' . "&nbsp;</th>";

                $t1 = getmicrotime();

                while (list($kk, $vv) = each($tablo)) {

                    $form->bufferPrint = "";
                    $form->genFields($vv);
                    $valeur = $form->getBuffer();

                    $valeur = truncate($valeur, $maxLength);

                    /**
                     * Class en fonction du type de champ
                     */
                    if (!empty($t[$vv]) && $t[$vv]->type == 'tinyint') {

                        /** Booléen * */
                        $class = 'class="colonne_booleen"';
                    } elseif (!empty($t[$vv]) && ($t[$vv]->type == 'date' || $t[$vv]->type == 'datetime')) {

                        /** Date * */
                        $class = 'class="colonne_date"';
                    } else
                        $class = '';

                    $r .= '<td ' . $class . '>' . $valeur . '' . "&nbsp;</td>";
                }

                $r .= '<td class="colonne_actions">';

                /**
                 * Boutons d'actions
                 */
                $r .= $this->getActions($row);




                $r .='</td>';
                $r .= ( '</tr>');
                $r .= "\n";
            }

            if (( $this->count ) > 0) {

                p($r);
            }

            p('</table>');

            p('</div>');
        }





        /**
         * Code javascript qui coche la checkbox en début de ligne lorsqu'on clique sur une ligne
         */
        p('
	   	<script type="text/javascript">
	   	$("table.table tr").click(function() {
	   		
	   		if($(this).find("input[type=checkbox]").is(":checked")) {
	   		
	   			$(this).find("input[type=checkbox]").attr("checked",false);
	   			$(this).removeClass("tr_selected");
	   			
	   		} else {
	   		
	   			$(this).find("input[type=checkbox]").attr("checked",true);
	   			$(this).addClass("tr_selected");
	   			
	   		}
	   	});
	   	</script>
	   	
	   	<div style="clear:both;text-align:right" class="well" >');

        $html = '';
        $html .= '<a href="#" onclick="searchSelectMass(true);return false;">' . t('select_all') . '</a> / ';
        $html .= '<a href="#" onclick="searchSelectMass(false);return false;">' . t('select_none') . '</a>';

        $html .= '<select name="mass_action">';
        $html .= '<option value="">-----------------</option>';

        $actions = $GLOBALS['gs_obj']->getActions($this->table);

        global $_Gconfig;

        foreach ($actions as $action) {

            if (!in_array($action, $_Gconfig['nonMassAction'])) {

                $html .= '<option value="' . $action . '">' . t($action) . '</option>';
            }
        }


        $html .= '
	   	</select>
	   	<input class="btn" type="submit" value="' . t('ok') . '" />
	   	</div>
	   	</form>';



        echo $html;


        p($pagi->gen());
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
        $nbActions = 0;
        $r = '';
        foreach ($actions as $action) {

            $ga = new genAction($action, $table, $id, $row);

            if ($ga->checkCondition()) {

                if (tradExists(('src_' . $action))) {
                    $srcBtn = t(('src_' . $action));
                } else {
                    $srcBtn = ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE . '/emblems/emblem-system.png';
                }

                if ($action == 'del') {

                    $r .= '<a class="btn btn-mini" title="' . t($action) . '" href="?genform_action%5B' . $action . '%5D=1&amp;curTable=' . $table . '&amp;curId=' . $id . '&amp;action=' . $action . '&amp;fromList=1" onclick="return confirm(\'' . t('confirm_suppr') . '\')">
							<img src="' . $srcBtn . '" alt="' . t($action) . '" title="' . t($action) . '" />
						   </a>';
                } else {

                    if (method_exists($ga->obj, 'getSmallForm')) {

                        $r .= '<div class="btn btn-mini small_form_action">' . $ga->obj->getSmallForm() . '</div>';
                    } else {

                        $r .= '<a class="btn btn-mini ' . ($action == 'edit' ? 'btn-primary' : '') . '" href="?genform_action%5B' . $action . '%5D=1&amp;curTable=' . $table . '&amp;curId=' . $id . '&amp;action=' . $action . '&amp;fromList=1" title="' . t($action) . '">
								<img src="' . $srcBtn . '" alt="' . t($action) . '" title="' . t($action) . '" />
							   </a>';
                    }
                }

                $nbActions++;
            }
        }

        $r = '<div style="width:' . (37 * $nbActions) . 'px" class="btn-group">' . $r . '</div>';

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
    function doFullSearch($searchTxt = '', $clauseSql = '', $onlyEditable = true) {

        global $searchField, $relations, $tablerel, $_Gconfig, $tabForms;

        /*
         * Create query for full relational search
         */
        $searchTxt = $searchTxt ? $searchTxt : akev($_REQUEST, 'searchTxt');

        $table = $this->table;
        $curkey = GetPrimaryKey($table);
        $tabfield = getTabField($table);
        $addToFROM = '';
        $addToWHERE = '';
        $addToORDER = '';
        /**
         * Paramètres supplémentaires si un champ de tri est demandé
         */
        if (!empty($_REQUEST['order']) && (array_key_exists($_REQUEST['order'], getTabField($this->table))
                || ( array_key_exists($_REQUEST['order'] . '_' . ADMIN_LG_DEF, getTabField($this->table))))) {

            /**
             * Si le champ de tri est une clé étrangère
             */
            if (!empty($relations[$this->table][$_REQUEST['order']])) {

                $tableEtrangere = $relations[$this->table][$_REQUEST['order']];

                $addToFROM = ', ' . $tableEtrangere . ' AS Z ';
                $addToWHERE = ' AND Z.' . getPrimaryKey($tableEtrangere) . ' = T.' . $_REQUEST['order'] . ' ';

                $labelTableEtrangere = $tabForms[$tableEtrangere]['titre'][0];

                /**
                 * On vérifie si c'est un champ de langue mis en version de base (ex. table_titre pour table_titre_fr)
                 */
                $fkTabFields = getTabField($tableEtrangere);

                if (!isset($fkTabFields[$labelTableEtrangere]) && isset($fkTabFields[$labelTableEtrangere . '_' . ADMIN_LG_DEF])) {

                    $labelTableEtrangere .= '_' . ADMIN_LG_DEF;
                }

                $addToORDER = ' Z.' . $labelTableEtrangere;
            }

            /**
             * Si c'est un champ de la table
             */ else {

                $addToFROM = '';
                $addToWHERE = '';

                /**
                 * On vérifie si c'est un champ de langue mis en version de base (ex. table_titre pour table_titre_fr)
                 */
                if (empty($tabfield[$_REQUEST['order']]) && !empty($tabfield[$_REQUEST['order'] . '_' . ADMIN_LG_DEF])) {

                    $addToORDER = ' T.' . $_REQUEST['order'] . '_' . ADMIN_LG_DEF;
                } else {

                    $addToORDER = ' T.' . $_REQUEST['order'];
                }
            }

            /**
             * Dans tous les cas on ajoute le sens de tri
             */
            if (akev($_GET, 'to') == 'asc') {

                $addToORDER .= ' ASC , ';
            } else {

                $addToORDER .= ' DESC , ';
            }
        }

        if (!empty($_Gconfig['arboredTable'][$this->table])) {
            $addToWHERE .= ' AND ( ' . $_Gconfig['arboredTable'][$this->table] . ' = 0 OR ' . $_Gconfig['arboredTable'][$this->table] . ' IS NULL )';
        }

        if (!empty($_Gconfig['orderedTable'][$this->table])) {
            $addToORDER .= ' T.' . $_Gconfig['orderedTable'][$this->table] . ' ASC , ';
        }


        /**
         * SQL start
         */
        $presql = 'SELECT DISTINCT(T.' . $curkey . ') , **COUNT**  T.* FROM ' . $table . ' AS T ' . $addToFROM;

        /**
         * Default where clause
         */
        if (in_array($table, $_Gconfig['multiVersionTable'])) {
            $wheresql = ' WHERE 1 ';
        } else if ($onlyEditable) {
            $wheresql = ' WHERE 1 ' . GetOnlyEditableVersion($table, "T") . ' ';
        } else {
            $wheresql = ' WHERE 1 ' . GetOnlyVisibleVersion($table, "T") . ' ';
        }
        $wheresql .= $GLOBALS['gs_obj']->sqlCanRow($this->table) . ' ';
        $wheresql .= $clauseSql;



        if (empty($searchField[$table])) {
            /**
             * No search field in configuration
             * Searching only on titles
             */
            $searchField[$table] = $tabForms[$table]['titre'];
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
            $v = akev($_POST, $v);



            if (!empty($tablerel[$k]) && is_array($v)) {

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
            } else if (!empty($relations[$table][$k]) && is_array($v)) {

                /**
                 * It's an n:1 relation
                 */
                $in = implode(" ',' ", $v);

                $wheresql .= " AND " . $k . " IN ('" . $in . "') " . "\n";
            } else if (isset($tabs[$k]) && ( $tabs[$k]->type == "datetime" || $tabs[$k]->type == "date")) {

                /**
                 * It's a date/time field
                 */
                $tabdate = array('inf' => '<', 'eg' => 'LIKE', 'sup' => '>');
                if (empty($_REQUEST[$k . '_type'])) {
                    $wheresql .= " AND T." . $k . " LIKE '" . $v . "%' " . "\n";
                } else {
                    $wheresql .= " AND T." . $k . " " . $tabdate[$_REQUEST[$k . '_type']] . " '" . $v . "' " . "\n";
                }
            } else if (isset($tabs[$k]) && $tabs[$k]->type == 'enum') {

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

        /**
         * Condition additionnelle
         */
        $wheresql .= $addToWHERE;


        if (!empty($_REQUEST['champ']) && !empty($_REQUEST['curId']) && isset($_Gconfig['specialListingWhere'][$_REQUEST['champ']])) {
            $wheresql .= $_Gconfig['specialListingWhere'][$_REQUEST['champ']]($_REQUEST['curId']);
        }

        $label = GetTitleFromTable($table, " , ");

        if (in_array($table, $_Gconfig['multiVersionTable'])) {
            $wheresql .= " GROUP BY T." . MULTIVERSION_FIELD . " ORDER BY T." . MULTIVERSION_FIELD . ",  ";
        } else {
            $wheresql .= " ORDER BY ";
        }

        $wheresql .= $addToORDER;

        $wheresql .= "T." . $label;


        $res = DoSql(str_replace('**COUNT**', ' COUNT(' . getPrimaryKey($this->table) . ') AS NB, ', $presql) . $wheresql);
        $res = $res->FetchRow();
        $this->count = $res['NB'];

        $wheresql .= $this->limit();


        $res = DoSql(str_replace('**COUNT**', '', $presql) . $wheresql);

        return $res;
    }

    public function limit() {

        return ' LIMIT ' . $this->lstart . ' , ' . $this->nbperpage;
    }

    /**
     * Recherche simple sur les champs textuels
     *
     */
    function doSimpleSearch() {

        global $searchField, $relations, $tablerel, $_Gconfig, $tabForms;

        $id = getPrimaryKey($this->table);
        $tabfield = getTabField($this->table);

        $addToFROM = '';
        $addToWHERE = '';
        $addToORDER = '';


        /**
         * Paramètres supplémentaires si un champ de tri est demandé
         */
        if (!empty($_REQUEST['order']) && (array_key_exists($_REQUEST['order'], getTabField($this->table)))
                || (!empty($_REQUEST['order']) && array_key_exists($_REQUEST['order'] . '_' . ADMIN_LG_DEF, getTabField($this->table)))) {



            /**
             * Si le champ de tri est une clé étrangère
             */
            if (!empty($relations[$this->table][$_REQUEST['order']])) {

                $tableEtrangere = $relations[$this->table][$_REQUEST['order']];

                $addToFROM = ', ' . $tableEtrangere . ' AS Z ';
                $addToWHERE = ' AND Z.' . getPrimaryKey($tableEtrangere) . ' = T.' . $_REQUEST['order'] . ' ';

                $labelTableEtrangere = $tabForms[$tableEtrangere]['titre'][0];

                /**
                 * On vérifie si c'est un champ de langue mis en version de base (ex. table_titre pour table_titre_fr)
                 */
                $fkTabFields = getTabField($tableEtrangere);

                if (!$fkTabFields[$labelTableEtrangere] && $fkTabFields[$labelTableEtrangere . '_' . ADMIN_LG_DEF]) {

                    $labelTableEtrangere .= '_' . ADMIN_LG_DEF;
                }

                $addToORDER = ' Z.' . $labelTableEtrangere;
            }

            /**
             * Si c'est un champ de la table
             */ else {

                $addToFROM = '';
                $addToWHERE = '';

                /**
                 * On vérifie si c'est un champ de langue mis en version de base (ex. table_titre pour table_titre_fr)
                 */
                if (empty($tabfield[$_REQUEST['order']]) && $tabfield[$_REQUEST['order'] . '_' . ADMIN_LG_DEF]) {

                    $addToORDER = ' T.' . $_REQUEST['order'] . '_' . ADMIN_LG_DEF;
                } else {

                    $addToORDER = ' T.' . $_REQUEST['order'];
                }
            }

            /**
             * Dans tous les cas on ajoute le sens de tri
             */
            if ($_GET['to'] == 'asc') {

                $addToORDER .= ' ASC , ';
            } else {

                $addToORDER .= ' DESC , ';
            }
        }

        if (!empty($_Gconfig['arboredTable'][$this->table])) {
            $addToWHERE .= ' AND ( ' . $_Gconfig['arboredTable'][$this->table] . ' = 0 OR ' . $_Gconfig['arboredTable'][$this->table] . ' IS NULL )';
        }

        if (!empty($_Gconfig['orderedTable'][$this->table])) {
            $addToORDER .= ' T.' . $_Gconfig['orderedTable'][$this->table] . ' ASC , ';
        }

        /**
         * Construction de la requête
         */
        $select = "SELECT DISTINCT(T." . GetPrimaryKey($this->table) . "), T.* ";
        $selectCount = "SELECT COUNT(" . GetPrimaryKey($this->table) . ") AS NB  ";


        $sql = " FROM " . $this->table . ' AS T ';

        $sql .= $addToFROM;

        if (in_array($this->table, $_Gconfig['multiVersionTable'])) {
            $sql .= ' WHERE 1 ';
        } else {
            $sql .= ' WHERE 1 ' . GetOnlyEditableVersion($this->table, "T") . ' ';
        }

        $sql .= $GLOBALS['gs_obj']->sqlCanRow($this->table) . ' AND ( ';

        $mots = explode(" ", $_REQUEST['searchTxt']);

        $sql .= " 0 ";

        /**
         * Recherche des mots du champ libre dans les champs de la table de type texte
         */
        while (list($k, $v) = each($tabfield)) {

            if ($v->type == "varchar" || $v->type == "text") {

                reset($mots);

                $sql .= " OR ( 1 ";

                while (list(, $mot) = each($mots)) {

                    $sql .= " AND T." . $k . ' LIKE ' . sql('%' . $mot . '%') . ' ';
                }

                $sql .= " ) ";
            }
        }

        if (!empty($_REQUEST['searchId']))
            $sql .= " ) ";

        $sql .= " ) ";

        $sql .= $addToWHERE;

        $label = GetTitleFromTable($this->table, " , ");

        if (in_array($this->table, $_Gconfig['multiVersionTable'])) {

            $sql .= " GROUP BY " . MULTIVERSION_FIELD . " ORDER BY " . MULTIVERSION_FIELD . ",  ";
        } else {

            $sql .= " ORDER BY ";
        }

        $sql .= $addToORDER;

        $sql .= $label;


        $resCount = DoSql($selectCount . $sql);
        $resCount = $resCount->FetchRow();
        $this->count = $resCount['NB'];


        $sql .= $this->limit();

        $res = DoSql($select . $sql);

        return $res;
    }

}

