<?php

class genSearchV2 {

    var $nbperpage = 20;

    function genSearchV2($table) {

	global $gs_obj;

	$this->gs = &$gs_obj;
	$this->table = $table;
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
	$sql = 'SELECT ' . getPrimaryKey($this->table) . ',
					' . GetTitleFromTable($this->table, " , ") . ' 
				FROM ' . $this->table . ' AS T
				WHERE 1 ' . GetOnlyEditableVersion($this->table) . ' 
				' . $GLOBALS['gs_obj']->sqlCanRow($this->table);

	if (isset($_Gconfig['arboredTable'][$this->table])) {
	    $sql .= ' AND ( ' . $_Gconfig['arboredTable'][$this->table] . ' = 0 OR ' . $_Gconfig['arboredTable'][$this->table] . ' IS NULL )';
	}

	$sql .= ' ORDER BY ';

	if (isset($_REQUEST['order']) && array_key_exists($_REQUEST['order'], getTabField($this->table))) {

	    $sql .= 'T.' . $_REQUEST['order'] . ' ';

	    if ($_GET['to'] == 'asc') {
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

		$this->doSimpleSearch();
	    } //else if($_REQUEST['doFullSearch'] || true) {
	    else {

		/**
		 * On génère le formulaire complet
		 */
		$_SESSION['LastSearch'][$this->table] = 'full';
		$_SESSION['LastSearchQuery'][$this->table] = $_POST;
		global $searchField;
		if (!empty($searchField[$this->table])) {
		    $this->getFullSearchForm();
		}

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
	p('<input type="text" id="searchTxt" name="searchTxt" value="' . akev($_REQUEST, 'searchTxt') . '" style="float:left;margin-top:5px;" />');

	p('<label class="abutton" style="float:left;margin:0;margin-left:10px;"><input type="image" src="' . t('src_search') . '" />' . t('rechercher') . '</label>');
	p('</form>');
    }

    function getSelect() {

	$res = $this->res;

	if (count($res) > 1000)
	    return;

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

	p('
        	<script type="text/javascript">
        		function submitFormRech(a,b) {
        			window.location = "?curTable=' . $_REQUEST['curTable'] . '&curId="+b;        			
        		}
        	</script>
            <fieldset style="border:0;padding:0;margin:0;"> 	
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
	    $v = akev($fields,$vv);


	    if ($k != 'dessin_fichier') {
		p('<div class="fond2" style="float:left">');
		if ($k != "pk") {
		    ;
		    p('<label  style="float:left;">' . t($k) . '</label>');
		    p('<div class="clearer">&nbsp;</div>');
		    p('<div style="float:left;">');


		    if (!empty($tablerel[$k])) {

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
		    if (!empty($relations[$table][$k])) {


			$tablenom = $relations[$table][$k];

			$label = 'A.' . GetTitleFromTable($tablenom, " , A.");

			$thiskey = GetPrimaryKey($tablenom);

			$sql = "SELECT A." . $thiskey . " , " . $label . " FROM " . $tablenom . " AS A  , " . $table . " AS B WHERE B." . $k . " = A." . $thiskey . " " . GetOnlyEditableVersion($tablenom, 'A') . ' GROUP BY  A.' . $thiskey . " ORDER BY " . $label;
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
			if (isset($v['type']) &&  ( ($v['type']== "int" && $v->max_length < 2 ) || $v['type']== "tinyint" )) {
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
			} else if ($v['type'] == "datetime" || $v['type']== "date") {

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
			} else if ($v['type']== 'enum') {
			    $values = getEnumValues($this->table, $v->name);
			    p('<select  style="height:70px;float:left;" name="' . $k . '[]" multiple="multiple" >');

			    foreach ($values as $rowe) {
				$sel = @in_array($rowe, $_POST[$k]) ? 'selected="selected"' : '';
				p('<option ' . $sel . ' value="' . $rowe . '">' . t('enum_' . $rowe) . '</option>');
			    }
			    p('</select>');
			} else {

			    p('<input style="float:left;" type="text"
                        			id="rech_' . $k . '" name="' . $k . '" 
                        			value="' . akev($_REQUEST,$k) . '" />');

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
	$totRes = count($res);
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


	/*
	  $pageTot = $pageTot == 0 ? 1 : $pageTot;
	  $pageNo = $pageNo == 0 ? 1 : $pageNo;
	 */



	/**
	 * Suivant / Précédent
	 */
	$r = '<form method="get" action="index.php" id="formpages" >
        <input type="hidden" name="curTable" value="' . $this->table . '" />
        <input type="hidden" name="fromList" value="1" />
        
        ';

	$r .= '<div >
        
        <table border="0"  width="100%">
        <tr><td style="text-align:center;display:block;width:100px;" width="100" class="fond1" >';


	/**
	 * Paramètres de tri
	 */
	$triParams = '';
	if (!empty($_REQUEST['order'])) {

	    $triParams = '&amp;order=' . $_REQUEST['order'];

	    if ($_REQUEST['to']) {

		$triParams .= '&amp;to=' . $_REQUEST['to'];
	    }
	}

	/**
	 * Bouton précédent
	 */
	if ($lstart > 0) {

	    $r .= '<a style="display:block" href="?fromList=1&amp;curTable=' . $this->table . '&amp;lstart=' . ($lstart - $this->nbperpage) . $triParams . '">
						<img src="' . ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE . '/actions/go-previous.png" alt="" /><br/> ' . t('page_precedente') . '
				   </a>';
	}

	$r .= '</td>';


	$r .= '<td style="text-align:center" width="80%" colspan="' . count($tablo) . '">';

	/**
	 * Liste de sélection de la page
	 */
	$r .= '<input type="hidden" name="curTable" value="' . $this->table . '" />';
	$r .= '<input type="hidden" name="fromList" value="1" />';

	/**
	 * Ajout des paramètres de tri
	 */
	if (!empty($_REQUEST['order']))
	    $r .= '<input type="hidden" name="order" value="' . $_REQUEST['order'] . '" />';

	if (!empty($_REQUEST['to']))
	    $r .= '<input type="hidden" name="to" value="' . $_REQUEST['to'] . '" />';

	$r .= t('page') . ' ';
	$r .= '<select name="lstart" onchange="gid(\'formpages\').submit();">';
	for ($p = 0; $p < $pageTot; $p++) {
	    $curP = ($p * $this->nbperpage);
	    $sel = ($curP == $lstart) ? 'selected="selected"' : '';
	    $r .= '<option value="' . $curP . '" ' . $sel . ' >' . ($p + 1) . '</option>';
	}
	$r .= '</select>';
	$r .= ' / ' . $pageTot;
	//$r.= '</form>';

	/*
	  $r .= t('page').' '.($pageNo.' / '.$pageTot).'<br/>';



	  for($p=0;$p<$pageTot;$p++) {
	  $curP = ($p*$this->nbperpage);

	  $sel =($curP ==$lstart) ? 'style="font-weight:bold;text-decoration:underline;"' : '';

	  $r .= '<a '.$sel.' href="?fromList=1&amp;curTable='.$this->table.'&amp;lstart='.$curP.'">'.($p+1).'</a> ';
	  }
	 */
	$r.= '</td>';

	/**
	 * Bouton suivant
	 */
	$r .= '<td style="text-align:center;display:block;width:100px;" width="100"  class="fond1" >';

	if ($totRes > $lend) {

	    $r .= '<a style="display:block" href="?fromList=1&amp;curTable=' . $this->table . '&amp;lstart=' . ($lend) . $triParams . '">
						<img src="' . ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE . '/actions/go-next.png" alt="" /><br/>' . t('page_suivante') . '
				   </a>';
	}

	$r .= '</td>
     	</tr >
     	</table>
     	</div>
     	<div>';


	$r .= '<table style="clear:both" border="0" class="genform_table" width="99%">';


	/**
	 * Nombre de résultats
	 */
	$r .= '<tr><td colspan="10">' . ('<h4  >' . t('il_y_a') . ' ' . count($res) . ' ' . t('resultats') . '</h4>') . '</td></tr>';


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
	    for ($k = $lstart; $k < $lend; $k++) {

		$row = $res[$k];

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

	    if (count($res) > 0) {

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
	   	$(".genform_table tr").click(function() {
	   		
	   		if($(this).find("input[type=checkbox]").is(":checked")) {
	   		
	   			$(this).find("input[type=checkbox]").attr("checked",false);
	   			$(this).removeClass("tr_selected");
	   			
	   		} else {
	   		
	   			$(this).find("input[type=checkbox]").attr("checked",true);
	   			$(this).addClass("tr_selected");
	   			
	   		}
	   	});
	   	</script>
	   	
	   	<div style="clear:both;text-align:right"  class="fond1">');

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

		    $r .= '<a class="btn_action" title="' . t($action) . '" href="?genform_action%5B' . $action . '%5D=1&amp;curTable=' . $table . '&amp;curId=' . $id . '&amp;action=' . $action . '&amp;fromList=1" onclick="return confirm(\'' . t('confirm_suppr') . '\')">
							<img src="' . $srcBtn . '" alt="' . t($action) . '" title="' . t($action) . '" />
						   </a>';
		} else {

		    if (method_exists($ga->obj, 'getSmallForm')) {

			$r .= '<div class="small_form_action">' . $ga->obj->getSmallForm() . '</div>';
		    } else {

			$r .= '<a class="btn_action" href="?genform_action%5B' . $action . '%5D=1&amp;curTable=' . $table . '&amp;curId=' . $id . '&amp;action=' . $action . '&amp;fromList=1" title="' . t($action) . '">
								<img src="' . $srcBtn . '" alt="' . t($action) . '" title="' . t($action) . '" />
							   </a>';
		    }
		}

		$nbActions++;
	    }
	}

	$r = '<div style="width:' . (28 * $nbActions) . 'px;">' . $r . '</div>';

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
    function doFullSearch($searchTxt = '', $clauseSql='', $onlyEditable=true) {

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
	 * SQL start
	 */
	$presql = 'SELECT DISTINCT(T.' . $curkey . ') , T.* FROM ' . $table . ' AS T ' . $addToFROM;

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
		if ($v['type']== "varchar" || $v['type']== "text") {
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

	$res = GetAll($presql . $wheresql);


	return $res;
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
	$sql = "SELECT DISTINCT(T." . GetPrimaryKey($this->table) . "), T.*
        		FROM " . $this->table . ' AS T ';

	$sql .= $addToFROM;

	if (in_array($this->table, $_Gconfig['multiVersionTable'])) {
	    $sql .= ' WHERE 1 ';
	} else {
	    $sql .= ' WHERE 1 ' . GetOnlyEditableVersion($this->table, "T") . ' ';
	}

	$sql .= $GLOBALS['gs_obj']->sqlCanRow($this->table) . ' AND ( ';

	$mots = split(" ", $_REQUEST['searchTxt']);

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

	$res = GetAll($sql);


	$this->printRes($res);
    }

}

?>
