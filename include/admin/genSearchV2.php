<?php

class genSearchV2
{

    var $nbperpage = 20;
    var $lstart = 0;
    var $isExportResults = false;
    var $isExportAdvancedResults = false;
    var $full_custom_fields_request;
    public $relOne;
    public $relOneFk;
    public $searchField;
    public $tabField;
    public $fullRealFields;

    public function __construct($table)
    {

        global $gs_obj, $_Gconfig, $searchField;

        $this->gs = &$gs_obj;
        $this->table = $table;

        $this->tabField = getTabField($this->table);


        $this->lstart = empty($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * $this->nbperpage;
        $this->full_custom_fields_request = array();
        // -- constantes de validation de gestion des exports sous csv

        $this->searchField = akev($searchField, $this->table);

        if (!empty($_REQUEST['relOne'])) {
            $this->relOne = $_REQUEST['relOne'];
            $this->relOneFk = $_Gconfig['relOne'][$this->table][$this->relOne];
            if (!empty($searchField[$this->relOne])) {
                $this->searchField = $searchField[$this->relOne];
            }
            $this->tabField = array_merge($this->tabField, getTabField($this->relOne));
        }


        // pour la recherche basique
        $this->isExportResults = false;

        // pour la recherche avancée
        $this->isExportAdvancedResults = false;
    }

    /**
     * Affiche le SELECT et le moteur de recherche simple
     *
     */
    function printAll()
    {


        if ($_REQUEST['curTable'] == 's_rubrique' && empty($_REQUEST['relOne'])) {
            return;
        }

        global $searchField, $tabForms, $_Gconfig, $co;
        $this->showFullSearchForm = !empty($searchField[$this->table]);

        /**
         * On sélectionne tous les enregistrements
         */
        $sql = ' ' . getPrimaryKey($this->table) . ',
					' . GetTitleFromTable($this->table, " , ");


        if ($this->relOne) {
            $sql .= ' , ' . getPrimaryKey($this->relOne);
        }
        $sql .= '
				FROM ' . ($this->table) . ' AS T' . '
                                    ';
        if ($this->relOne) {
            $sql .= ' , ' . $this->relOne;
        }
        $sql .= '
				WHERE 1 ' . GetOnlyEditableVersion($this->table, 'T') . '
				' . $GLOBALS['gs_obj']->sqlCanRow($this->table);

        if (isset($_Gconfig['arboredTable'][$this->table])) {
            $sql .= ' AND ( ' . $_Gconfig['arboredTable'][$this->table] . ' = 0 OR ' . $_Gconfig['arboredTable'][$this->table] . ' IS NULL )';
        }

        if ($this->relOne) {
            $sql .= ' AND ' . $this->relOne . '.' . $this->relOneFk . ' = ' . getPrimaryKey($this->table);
        }

        $sqlCount = 'SELECT COUNT(' . getPrimaryKey($this->table) . ') AS NB , ' . $sql . ' ';


        $rCount = DoSql($sqlCount);


        if (!$rCount) {
            global $co;
            var_dump($sqlCount);
            var_dump($co->ErrorMsg());
        }
        $rCount = $rCount->FetchRow();
        $this->count = $rCount['NB'];

        $sql = 'SELECT ' . $sql;

        $sql .= ' ORDER BY ';

        if (isset($_REQUEST['order']) && array_key_exists($_REQUEST['order'], $this->tabField)) {
            $sql .= ' ' . $_REQUEST['order'] . ' ';
            if (akev($_GET, 'to') == 'asc') {
                $sql .= ' ASC , ';
            } else {
                $sql .= ' DESC , ';
            }
        }

        //Config permettant d'ajouter un ordre et son type sur un chmap spécifique.
        if (isset($_Gconfig['orderedTableType'][$this->table])) {
            $sql .= ' T.' . $_Gconfig['orderedTableType'][$this->table][0] . ' '.$_Gconfig['orderedTableType'][$this->table][1].' , ';
        }

        if (isset($_Gconfig['orderedTable'][$this->table])) {
            $sql .= ' T.' . $_Gconfig['orderedTable'][$this->table] . ' ASC , ';
        }

        //ORDER BY '.GetTitleFromTable($this->table," , ");

        $sql .= " " . GetTitleFromTable($this->table, " , ");

        $sql .= $this->limit();


        $this->res = DoSql($sql);

        p('<div class="row-fluid" id="search">');

        if (!empty($this->searchField)) {
            p('<div class="span2" style="">');
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
            } else {
                $_REQUEST['doFullSearch'] = 1;
                $_POST = $_REQUEST = array_merge($_SESSION['LastSearchQuery'][$this->table], $_REQUEST);
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
            if (!empty($this->searchField)) {
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

            // LANCEMENT DU FORMULAIRE AVANCEE
            if ($this->showFullSearchForm) {
                // Recuperation des arguments des champs personnalisés de la recherche avancée
                foreach ($_REQUEST as $k => $v) {
                    //print_r($k);
                    $key_id = (strpos($k, 'cf_') === 0) ? $k : '';
                    if (array_key_exists($key_id, $_REQUEST)) {
                        // le contenu du champs personnalisé est complété
                        if (!empty($_REQUEST[$k]) || $_REQUEST[$k] === "0") {
                            $this->fullRealFields[str_replace('cf_', '', $k)] = $v;
                            $this->full_custom_fields_request[$k] = array();
                            // si c'est un tableau
                            if (is_array($_REQUEST[$k])) {
                                // on parcourt le tableau
                                foreach ($_REQUEST[$k] as $w) {
                                    array_push($this->full_custom_fields_request[$k], $w);
                                }
                            } else {
                                // sinon c'est une valeur de clé
                                $this->full_custom_fields_request[$k] = $_REQUEST[$k];
                            }
                        }
                    }
                }
                $this->getFullSearchForm();
            }

            $res = $this->doFullSearch();
        }


        if (!empty($searchField[$this->table])) {
            p('</div>');
            p('<div class="span10">');
        }


        // validation bouton recherche rapide
        if (akev($_REQUEST, 'exportResults') == 'true') {
            $this->isExportResults = true;
            $this->exportRes($res, 'basicExport');
        } // validation bouton recherche avancée
        else if (akev($_REQUEST, 'exportAdvancedResults') == 'true') {
            $this->isExportAdvancedResults = true;
            $this->exportRes($res, 'advancedExport');
        } // sinon on affiche simplement le resultat
        else {
            $this->printRes($res);
        }

        p('</div></div>');
    }

    function getSimpleSearchForm()
    {


        p('<div class="well">');

        p('<form name="formChooseSearch" class="form-' . ($this->showFullSearchForm ? 'vertical' : 'inline') . '" id="formChooseSearch" method="post" action="index.php" style="margin-bottom: 0" >');
        p('<h5>Effectuer une recherche</h5>');
        p('<input type="hidden" name="doSimpleSearch" value="1" />');
        p('<input type="hidden" name="relOne" value="' . $this->relOne . '" />');

        p('<input type="hidden" name="curTable" value="' . $this->table . '" />');

        p('<div class="control-group"> <div class="controls"> ');

        echo('<input  type="text" placeholder=' . alt(ta('recherche_rapide')) . ' id="searchTxt" name="searchTxt" value="' . akev($_REQUEST, 'searchTxt') . '"/> ');

        p('<button onclick="document.forms[\'formChooseSearch\'].elements[\'exportResults\'].value = false;" class="btn "><img src="' . t('src_search') . '" alt=' . alt(t('rechercher')) . ' />' . t('search') . '</button></div></div>');


        /**
         * *************************
         */
        p('</form>');

        p('</div>');
    }

    function getSelect()
    {

        return;
        $res = $this->res;


        if (($this->count) > 1000)
            return;

        // p('<div style="clear:both;">&nbsp;</div>

        p('<div class="well">');


        $request_string = '';

        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            $request_string = $_SERVER['QUERY_STRING'];
        }

        p('<form name="formChooseSel" id="formChooseSel" method="post" action="?' . $request_string . '"  class="span12 form-vertical" style="float:left;">');

        p('<input type="hidden" name="curTable" value="' . $this->table . '" />');
        p('<input type="hidden" name="relOne" value="' . $this->relOne . '" />');
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

        p('<button class="btn"><img src="' . t('src_search') . '" /></label>');
        p('</form>');

        $this->getSimpleSearchForm();
    }

    function getFullSearchForm()
    {

        global $relations, $tablerel, $tabForms, $gs_obj;

        $table = $this->table;
        $fields = $this->tabField;
        p('<div class="well">');


        // Si il existe des arguments, nous le les perdons pas
        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            $request_string = $_SERVER['QUERY_STRING'];
        }

        p('<form id="search" name="advancedFormSearch" class="advancedFormSearch" method="post" action="?"  class="well form-vertical" >');
        p('<h5>Effectuer une recherche avancée</h5>');


        p('
        	<script type="text/javascript">
        		function submitFormRech(a,b) {
        			window.location = "?curTable=' . $_REQUEST['curTable'] . '&curId="+b;
        		}
        	</script>
        ');


        p('<input type="hidden" name="curTable" value="' . $this->table . '" />');
        p('<input type="hidden" name="relOne" value="' . $this->relOne . '" />');
        p('<input type="hidden" name="doFullSearch" value="1" />');


        $i = 0;

        if (!count($this->searchField)) {
            $this->searchField[$table] = $tabForms[$table]['titre'];
        }


        foreach ($this->searchField as $kk => $vv) {
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

                    foreach ($tablerel[$k] as $k2 => $v2) {
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

                    p('<select class="selectM" id="' . $k . '" name="cf_' . $k . '[]" multiple="multiple" >');

                    foreach ($res as $row) {
                        $sel = isset($_POST[$k]) && @in_array($row[$thiskey], $_POST[$k]) ? 'selected="selected"' : '';
                        p('<option ' . $sel . ' value="' . $row[$thiskey] . '">' . limit(GetTitleFromRow($fk_table, $row, " "), 30) . '</option>');
                    }

                    p('</select><div class="input-prepend "><span class="add-on add-on-mini"><i class="icon-search"></i></span><input type="text" class="selectMSearch input-mini" id="' . $k . '_search" onkeydown="searchInSelect(this)" /></div>');
                } else
                    if (!empty($relations[$table][$k])) {

                        p('<label class="select-label"> ' . t($k) . '</label>');
                        $tablenom = $relations[$table][$k];

                        $label = 'A.' . GetTitleFromTable($tablenom, " , A.");

                        $thiskey = GetPrimaryKey($tablenom);

                        $sql = "SELECT A." . $thiskey . " , " . $label . " ";

                        $sql .= " FROM " . $tablenom . " AS A  , " . $table . " AS B ";

                        if ($this->relOne) {
                            $sql .= ' , ' . $this->relOne . ' AS rOne ';
                        }


                        if ($this->tabField[$k]->table == $table) {
                            $sql .= " WHERE B." . $k . " = A." . $thiskey . " " . sqlOnlyReal($tablenom, 'A') . ' ';
                        } else {
                            $sql .= " WHERE rOne." . $k . " = A." . $thiskey . " " . sqlOnlyReal($tablenom, 'A') . ' ';
                        }

                        if ($this->relOne) {
                            $sql .= ' AND  rOne.' . $this->relOneFk . ' = B.' . getPrimaryKey($this->table);
                        }

                        $sql .= ' GROUP BY  A.' . $thiskey . " ORDER BY " . $label;
                        $res = GetAll($sql);

                        p('<select class="span12" id="' . $k . '"  name="cf_' . $k . '[]" multiple="multiple" >');
                        //p('<option value="">' . ta($k) . '</option>');
                        foreach ($res as $row) {
                            $sel = isset($this->fullRealFields[$k]) && in_array($row[$thiskey], $this->fullRealFields[$k]) ? 'selected="selected"' : '';
                            p('<option ' . $sel . ' value="' . $row[$thiskey] . '">' . limit(GetTitleFromRow($tablenom, $row, " "), 30) . '</option>');
                        }
                        p('</select><br/>');

                        // masquage temporaire pour comprendre utilité
                        //p('<div class="control-group"><input type="text" class="selectMSearch span2" id="' . $k . '_search" onkeydown="searchInSelect(this)" /></div>');
                    } else {

                        if (($type == "int" && $size < 2) || $type == "tinyint") {
                            $vv = akev($this->fullRealFields, $k);
                            p('<label class="hide-text"> ' . t($k) . '</label>');
                            $sel = $vv == "" ? 'selected="selected"' : '';
                            $sel0 = $vv === "0" ? 'selected="selected"' : '';
                            $sel1 = $vv == 1 ? 'selected="selected"' : '';
                            p('
                            <select class="span12" name="cf_' . $k . '">
                                <option ' . $sel . ' value="">' . ta($k) . '</option>
                                <option ' . $sel0 . '  value="0">' . t('non') . '</option>
                                <option ' . $sel1 . ' value="1">' . t('oui') . '</option>
                            </select>
                        ');
                        } else if ($type == "datetime" || $type == "date") {
                            p('<label class=""> ' . t($k) . '</label>');
                            $vv = akev($_REQUEST, $k . '_type');
                            $sel = $vv == "" ? 'selected="selected"' : '';
                            $sel0 = $vv == "inf" ? 'selected="selected"' : '';
                            $sel1 = $vv == "eg" ? 'selected="selected"' : '';
                            $sel2 = $vv == "sup" ? 'selected="selected"' : '';
                            p('<div class="row-fluid">
                            <select class="span2" name="' . $k . '_type">
                                <option ' . $sel . ' value="">-</option>
                                <option ' . $sel0 . '  value="inf"><</option>
                                <option ' . $sel1 . ' value="eg">=</option>
                                <option ' . $sel2 . ' value="sup">></option>
                            </select>
                            <input class="span9" type="text" placeholder="1980-10-24" name="cf_' . $k . '" value=' . alt(akev($this->fullRealFields, $k)) . ' /></div>
                        ');
                        } else if ($type == 'enum') {
                            p('<div><label class=""> ' . t($k) . '</label>');
                            $values = getEnumValues($this->table, $v->name);

                            p('<select  name="cf_' . $k . '[]" multiple="multiple" >');

                            foreach ($values as $rowe) {

                                $sel = @in_array($rowe, $this->fullRealFields[$k]) ? 'selected="selected"' : '';
                                p('<option ' . $sel . ' value="' . $rowe . '">' . t('enum_' . $rowe) . '</option>');
                            }
                            p('</select></div>');
                        } else if (substr($type, 0, 3) == 'set') {
                            p('<div><label class=""> ' . t($k) . '</label>');
                            $values = parseSetValues($type);

                            p('<select  name="cf_' . $k . '[]" multiple="multiple" >');

                            foreach ($values as $rowe) {
                                $sel = @in_array($rowe, $this->fullRealFields[$k]) ? 'selected="selected"' : '';
                                p('<option ' . $sel . ' value="' . $rowe . '">' . t('enum_' . $rowe) . '</option>');
                            }
                            p('</select></div>');
                        } else {
                            p('<label class="hide-text"> ' . t($k) . '</label>');

                            p('<input class="span12" placeholder=' . alt(ta($k)) . '  type="text"
                        			id="cf_' . $k . '" name="cf_' . $k . '"
                        			value="' . akev($this->fullRealFields, $k) . '" />');

                            p('

                        <script type="text/javascript">
                        jQuery(function(){
                                options = { serviceUrl:"?xhr=autocompletesearch&table=' . $_REQUEST['curTable'] . '&champ=' . $k . '", onSelect: submitFormRech   };
                                a = $("#' . $k . '").autocomplete(options);
							});
                         </script>
						');
                        }
                    }


                p('</div>');


                $i++;
            }
        }


        p('<button class="btn" ><img src="' . t('src_search') . '" />' . t('search') . '</button>');


        /**
         * *************************
         */
        p('</form>');


        p('</div>');
    }

    function printRes($res)
    {

        /*
         *
         * Formate les resultats sous la forme d'un tableau avec des liens pour modifier
         */


        global $searchField, $relations, $tabForms, $tables, $Gconfig, $tablerel;


        $r = "";

        $table = $this->table;
        $maxLength = 100;


        /* if (!empty($searchField[$table])) {
          $tablo = $searchField[$table];
          } else {
          $tablo = $tabForms[$table]['titre'];
          } */
        if (!empty($searchField[$table]))
            $tablo = $this->searchField;
        else if (!empty($tabForms[$table]['titre']))
            $tablo = $tabForms[$table]['titre'];
        else
            $tablo = array();


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
        <input type="hidden" name="relOne" value="' . $this->relOne . '" />

        ';


        $pagi = new pagination($pageTot);
        $r .= $pagi->gen();

        $r .= '<div>';


        $r .= '<table border="0" class="table table-striped table-bordered table-condensed" >';


        /**
         * Nombre de résultats
         */
        $r .= '<tr><td colspan="16">' . ('<h4  >' . t('il_y_a') . ' ' . ($this->count) . ' ' . t('resultats') . '</h4>') . '</td></tr>';


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

                $form = new GenForm($this->table, 'post', $id, $row);

                $form->thumbWidth = 200;
                $form->thumbHeight = 200;
                $form->editMode = true;
                $form->onlyData = true;

                reset($tablo);

                echo $this->getActions($row);


                echo('<table class="itemtab">');

                foreach ($tablo as $kk => $vv) {


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

            $r .= ('<tr>');


            $t = $this->tabField;
            $r .= ('<th style="width:20px;"  scope="col">Id</th>');

            /**
             * Entêtes du tableau des résultats
             */
            foreach ($tablo as $v => $k) {

                /**
                 * Champs de type boolean
                 */
                if (!empty($t[$k]) && $t[$k]->type == 'tinyint') {
                    $r .= ('<th class="colonne_booleen" scope="col">');
                } else {
                    $r .= ('<th scope="col">');
                }
                $r .= t($k) . "";

                /**
                 * Boutons de tri des résultats - Sur tous type de champs sauf les tables de relations
                 */
                if (empty($tablerel[$k])) {

                    $r .= ('<br/><a href="?fromList=1&amp;curTable=' . $_REQUEST['curTable'] . '&order=' . $k . '&to=asc&lstart=0&relOne=' . $this->relOne . '">
									<img src="img/sort_down.jpg" alt="Tri croissant" title="Tri croissant" />
								</a>&nbsp;
								<a href="?fromList=1&amp;curTable=' . $_REQUEST['curTable'] . '&order=' . $k . '&to=desc&lstart=0&relOne=' . $this->relOne . '">
									<img src="img/sort_up.jpg" alt="Tri décroissant" title="Tri décroissant" />
								</a>');

                    $r .= ('</th>');
                }
            }

            $r .= ('<th style="width:20px;">&nbsp;</th>');

            $r .= ('</tr>');
            $r .= "\n";


            /**
             * Liste des résultats
             */
            //for ($k = $lstart; $k < $lend; $k++) {
            foreach ($res as $k => $row) {
                /*
                  $res->Move($k);
                  $row = $res->FetchRow();
                 */
                $r .= ('<tr class="' . ($k % 2 ? 'odd' : 'even') . '">');

                $id = $row[$thisPk];
                $t1 = getmicrotime();

                $form = new GenForm($this->table, 'post', $id);

                $form->editMode = true;
                $form->onlyData = true;

                reset($tablo);

                $r .= '<th style="width:20px;"><input type="checkbox"  name="massiveActions[]" value="' . $id . '" /> ' . $row[$thisPk] . '' . "&nbsp;</th>";

                $t1 = getmicrotime();


                foreach ($tablo as $kk => $vv) {

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


                $r .= '</td>';
                $r .= ('</tr>');
                $r .= "\n";
            }

            if (($this->count) > 0) {

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

	   	<div style="clear:both;text-align:right" class="well form-inline" >');

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
    function getActions($row)
    {

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

                    $r .= '<a class="btn btn-mini" title="' . t($action) . '" href="?genform_action%5B' . $action . '%5D=1&amp;curTable=' . $table . '&amp;curId=' . $id . '&amp;action=' . $action . '&amp;fromList=1' . (empty($_REQUEST['page']) ? '' : ('&page=' . $_REQUEST['page'])) . '&amp;relOne=' . $this->relOne . '" onclick="return confirm(\'' . t('confirm_suppr') . '\')">
							<img src="' . $srcBtn . '" alt="' . t($action) . '" title="' . t($action) . '" />
						   </a>';
                } else {

                    if (method_exists($ga->obj, 'getSmallForm')) {
                        $v = $ga->obj->getSmallForm();
                        if ($v) {
                            $r .= '<div class="btn btn-mini small_form_action">' . $v . '</div>';
                        } else {
                            $nbActions--;
                        }
                    } else {

                        $r .= '<a class="btn btn-mini ' . ($action == 'edit' ? 'btn-primary' : '') . '" href="?genform_action%5B' . $action . '%5D=1&amp;curTable=' . $table . '&amp;curId=' . $id . '&amp;action=' . $action . '&amp;fromList=1' . ((empty($_REQUEST['page']) || !$ga->obj->canReturnToList) ? '' : ('&page=' . $_REQUEST['page'])) . '&amp;relOne=' . $this->relOne . '" title="' . t($action) . '">
								<img src="' . $srcBtn . '" alt="' . t($action) . '" title="' . t($action) . '" />
							   </a>';
                    }
                }

                $nbActions++;
            }
        }

        $r = '<div style="width:' . (36 * ($nbActions)) . 'px" class="btn-group">' . $r . '</div>';

        return $r;
    }

    function getTableActions()
    {

    }

    /**
     * Recherche spéciale par champs
     *
     * @param string $searchTxt
     * @param string $clauseSql
     * @return array liste de résultats MySQL
     */
    function doFullSearch($searchTxt = '', $clauseSql = '', $onlyEditable = true)
    {

        global $relations, $tablerel, $_Gconfig, $tabForms;

        /*
         * Create query for full relational search
         */
        $searchTxt = $searchTxt ? $searchTxt : akev($_REQUEST, 'searchTxt');


        $table = $this->table;
        $curkey = GetPrimaryKey($table);
        $tabfield = $this->tabField;
        $addToFROM = '';
        $addToWHERE = '';
        $addToORDER = '';
        /**
         * Paramètres supplémentaires si un champ de tri est demandé
         */
        if (!empty($_REQUEST['order']) && (array_key_exists($_REQUEST['order'], $this->tabField) || (array_key_exists($_REQUEST['order'] . '_' . ADMIN_LG_DEF, $this->tabField)))) {

            /**
             * Si le champ de tri est une clé étrangère
             */
            if (!empty($relations[$this->table][$_REQUEST['order']])) {

                $tableEtrangere = $relations[$this->table][$_REQUEST['order']];

                $addToFROM = ', ' . $tableEtrangere . ' AS Z ';
                if ($this->relOne) {
                    $tableInfo = MetaColumns($this->relOne);
                    if (ake($tableInfo, strtoupper($_REQUEST['order']))) {
                        $addToWHERE = ' AND Z.' . getPrimaryKey($tableEtrangere) . ' = ' . $this->relOne . '.' . $_REQUEST['order'] . ' ';
                    } else {
                        $addToWHERE = ' AND Z.' . getPrimaryKey($tableEtrangere) . ' = T.' . $_REQUEST['order'] . ' ';
                    }
                } else {
                    $addToWHERE = ' AND Z.' . getPrimaryKey($tableEtrangere) . ' = T.' . $_REQUEST['order'] . ' ';
                }
                $labelTableEtrangere = $tabForms[$tableEtrangere]['titre'][0];

                /**
                 * On vérifie si c'est un champ de langue mis en version de base (ex. table_titre pour table_titre_fr)
                 */
                $fkTabFields = getTabField($tableEtrangere);

                if (!isset($fkTabFields[$labelTableEtrangere]) && isset($fkTabFields[$labelTableEtrangere . '_' . ADMIN_LG_DEF])) {

                    $labelTableEtrangere .= '_' . ADMIN_LG_DEF;
                }

                $addToORDER = ' Z.' . $labelTableEtrangere;
            } /**
             * Si c'est un champ de la table
             */ else {

                $addToFROM = '';
                $addToWHERE = '';

                /**
                 * On vérifie si c'est un champ de langue mis en version de base (ex. table_titre pour table_titre_fr)
                 */
                if (empty($tabfield[$_REQUEST['order']]) && !empty($tabfield[$_REQUEST['order'] . '_' . ADMIN_LG_DEF])) {

                    $addToORDER = ' ' . $_REQUEST['order'] . '_' . ADMIN_LG_DEF;
                } else {

                    $addToORDER = ' ' . $_REQUEST['order'];
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

        //Config permettant d'ajouter un ordre et son type sur un chmap spécifique.
        if (isset($_Gconfig['orderedTableType'][$this->table])) {
            $addToORDER .= ' T.' . $_Gconfig['orderedTableType'][$this->table][0] . ' '.$_Gconfig['orderedTableType'][$this->table][1].' , ';
        }

        if (!empty($_Gconfig['orderedTable'][$this->table])) {
            $addToORDER .= ' T.' . $_Gconfig['orderedTable'][$this->table] . ' ASC , ';
        }


        /**
         * SQL start
         */
        $presql = 'SELECT  **COUNT**  T.* ';
        $ppresql = ' FROM ' . $table . ' AS T ';

        if ($this->relOne) {
            $presql .= ' , ' . $this->relOne . '.* ';
            $ppresql .= ' , ' . $this->relOne;
        }

        $presql .= $ppresql . '' . $addToFROM;

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


        if ($this->relOne) {
            $wheresql .= ' AND ' . $this->relOne . '.' . $this->relOneFk . ' = ' . getPrimaryKey($this->table);
        }


        if (empty($this->searchField)) {
            /**
             * No search field in configuration
             * Searching only on titles
             */
            $this->searchField = $tabForms[$table]['titre'];
        }

        $mySearchField = $this->searchField;


        /**
         * If there is a global search field,
         * looping on all text fields
         *
         * @deprecated THIS SHOULD NOT APPEND
         * @see $this->doSimpleSearch();
         *
         */
        if (strlen($searchTxt)) {
            $tabfield = $this->tabField;
            $wheresql .= ' AND ( 0 ';
            $mots = explode(" ", $searchTxt);


            foreach ($tabfield as $k => $v) {
                if ($v->type == "varchar" || $v->type == "text") {
                    reset($mots);
                    $wheresql .= " OR ( 1 ";

                    foreach ($mots as $mot) {
                        $wheresql .= " AND " . $k . ' LIKE "%' . $mot . '%" ';
                    }
                    $wheresql .= " ) ";
                }
            }
            $wheresql .= ' ) ';
        }


        $full_fields_request = array();
        $isCustomPageFullSearch = false;


        foreach ($this->full_custom_fields_request as $k => $v) {

            if (isset($_GET['page'])) {
                $isCustomPageFullSearch = true;
            }

            $full_fields_request[substr($k, 3)] = $v;
        }

        /**
         * Getting all table fields
         */
        $tabs = $this->tabField;

        /**
         * Looping on the search Fields
         */

        foreach ($mySearchField as $k => $v) {

            $k = $v;

            if (!empty($_POST['doFullSearch']) || $isCustomPageFullSearch) {
                // enlever "cf_" pour le reconnaitre dans la requete comme champs
                $v = akev($full_fields_request, $v);
            } else {
                //$v = akev($_POST, $v);
                $v = akev($_REQUEST, $v);
            }


            if (!empty($tablerel[$k]) && is_array($v)) {
                /**
                 * It's an n:n relation
                 */
                reset($tablerel[$k]);

                foreach ($tablerel[$k] as $k2 => $v2) {
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
            } else if (isset($tabs[$k]) && ($tabs[$k]->type == "datetime" || $tabs[$k]->type == "date")) {

                /**
                 * It's a date/time field
                 */
                $tabdate = array('inf' => '<', 'eg' => 'LIKE', 'sup' => '>');
                if (empty($_REQUEST[$k . '_type'])) {
                    $wheresql .= " AND " . $k . " LIKE '" . $v . "%' " . "\n";
                } else {
                    $wheresql .= " AND " . $k . " " . $tabdate[$_REQUEST[$k . '_type']] . " '" . $v . "' " . "\n";
                }
            } else if (isset($tabs[$k]) && ($tabs[$k]->type == 'enum' || substr($tabs[$k]->type, 0, 3) == 'set')) {

                /**
                 * It's an enum field
                 */
                if (is_array($v) && count($v)) {
                    /**
                     * Multiple choices
                     */
                    $wheresql .= " AND " . $k . " IN ('" . implode("','", $v) . "') " . "\n";
                } else if (strlen($v)) {
                    /**
                     * Single choice
                     */
                    $wheresql .= " AND " . $k . " IN ('" . $v . "') " . "\n";
                }
            } else if (strlen($v) > 0) {

                /**
                 * Anything else ...
                 */
                if (isBaseLgField($k, $table)) {

                    $mots = explode(" ", $v);

                    foreach ($mots as $mot) {
                        $wheresql .= ' AND ( 0 ';
                        foreach ($_Gconfig['LANGUAGES'] as $lg) {
                            $wheresql .= " OR " . $k . "_" . $lg . " LIKE '%" . $mot . "%' " . "\n";
                        }
                        $wheresql .= ' ) ';
                    }
                } else {
                    $mots = explode(" ", $v);
                    foreach ($mots as $mot) {
                        $wheresql .= " AND " . $k . " LIKE '%" . $mot . "%' " . "\n";
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
            $wheresql .= " AND T." . MULTIVERSION_FIELD . " = T." . getPrimaryKey($table) . "  ORDER BY   "; //T." . MULTIVERSION_FIELD . ",
        } else {
            $wheresql .= " ORDER BY ";
        }

        $wheresql .= $addToORDER;

        $wheresql .= "" . $label;

        $s = str_replace('**COUNT**', ' COUNT(' . getPrimaryKey($this->table) . ') AS NB, ', $presql) . $wheresql;
        $res = GetSingle($s);

        $this->count = $res['NB'];


        $wheresql .= $this->limit();

        $res = DoSql(str_replace('**COUNT**', '', $presql) . $wheresql);

        return $res;
    }

    public function limit()
    {

        return ' LIMIT ' . $this->lstart . ' , ' . $this->nbperpage;
    }

    /**
     * Recherche simple sur les champs textuels
     *
     */
    function doSimpleSearch()
    {

        global $searchField, $relations, $tablerel, $_Gconfig, $tabForms;

        $id = getPrimaryKey($this->table);
        $tabfield = $this->tabField;

        $addToFROM = '';
        $addToWHERE = '';
        $addToORDER = '';


        /**
         * Paramètres supplémentaires si un champ de tri est demandé
         */
        if (!empty($_REQUEST['order']) && (array_key_exists($_REQUEST['order'], $this->tabField)) || (!empty($_REQUEST['order']) && array_key_exists($_REQUEST['order'] . '_' . ADMIN_LG_DEF, $this->tabField))) {

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
            } /**
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

                    $addToORDER = ' ' . $_REQUEST['order'];
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

        //Config permettant d'ajouter un ordre et son type sur un chmap spécifique.
        if (isset($_Gconfig['orderedTableType'][$this->table])) {
            $addToORDER .= ' T.' . $_Gconfig['orderedTableType'][$this->table][0] . ' '.$_Gconfig['orderedTableType'][$this->table][1].' , ';
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

        if ($this->relOne) {
            $sql .= ' , ' . $this->relOne . ' ';
            $select .= ' , ' . $this->relOne . '.* ';
        }

        if (in_array($this->table, $_Gconfig['multiVersionTable'])) {
            $sql .= " WHERE  T." . MULTIVERSION_FIELD . " = T." . getPrimaryKey($this->table) . "  ";
        } else {
            $sql .= ' WHERE 1 ' . GetOnlyEditableVersion($this->table, "T") . ' ';
        }


        if ($this->relOne) {
            $sql .= ' AND ' . $this->relOne . '.' . $this->relOneFk . ' = ' . getPrimaryKey($this->table);
        }

        $sql .= $GLOBALS['gs_obj']->sqlCanRow($this->table) . ' AND ( ';

        $mots = explode(" ", $_REQUEST['searchTxt']);

        $sql .= " 0 ";

        /**
         * Recherche des mots du champ libre dans les champs de la table de type texte
         */

        foreach ($tabfield as $k => $v) {

            if ($v->type == "varchar" || $v->type == "text" || $v->type == "date") {

                reset($mots);

                $sql .= " OR ( 1 ";


                foreach ($mots as $mot) {

                    $sql .= " AND " . $k . ' LIKE ' . sql('%' . $mot . '%') . ' ';
                }

                $sql .= " ) ";
            }
        }

        if (!empty($_REQUEST['searchId'])) {
            $sql .= " ) ";
        }

        $sql .= " ) ";

        $sql .= $addToWHERE;

        $label = GetTitleFromTable($this->table, " , ");

        if (in_array($this->table, $_Gconfig['multiVersionTable'])) {

            $sql .= " ORDER BY   ";
            //$addToORDER .= ' , ' . MULTIVERSION_FIELD;
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

    /**
     * Export des résultats de la recherche au format CSV
     *
     * @param unknown_type $res
     *
     */
    function exportRes($res, $typeResult)
    {

        // si export basique
        if ($typeResult == 'basicExport') {
            $this->isExportResults = false;
            // si export avancé
        } else {
            $this->isExportAdvancedResults = false;
        }

        /**
         * On vide le tampon de sortie
         */
        global $tabForms;
        ob_end_clean();

        $filename = "data_export_" . date("Y-m-d") . ".csv";

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");

        $champs = isset($tabForms[$this->table]['exportFields']) ? $tabForms[$this->table]['exportFields'] : $tabForms[$this->table]['titre'];

        foreach ($res as $row) {

            $f = new GenForm($this->table, 'post', 0, $row);
            $f->editMode = true;
            $f->onlyData = true;
            $csv = '';
            foreach ($champs as $c) {

                $csv .= $this->csvenc(strip_tags(str_replace('&nbsp;', '', ($f->gen($c))))) . ';';
            }

            $csv .= "\n";
        }

        /**
         * On décode l'utf8 pour que le fichier de sortie soit lisible dans excel
         */
        echo(utf8_decode($csv));
        ob_end_flush();

        die();
    }

    function csvenc($str)
    {

        return str_replace(array(";", "\n", "\r"), array(":", " ", " "), $str);
    }

}
