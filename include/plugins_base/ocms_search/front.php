<?php

if (isset($_REQUEST['ajax_q']) && isset($_REQUEST['q'])) {

    $GLOBALS['gb_obj']->includeFile('class.ocms_search.php', 'plugins/ocms_search');
    $c = new indexSearch();
    $c->useWildCards();
    $res = $c->search($_REQUEST['q']);

    foreach ($res as $row) {
	$r = getRowFromId($row['obj'], $row['fkid']);
	echo '<a href="'.getUrlFromSearch($row, $r).'">'.GetTitleFromRow($row['obj'], $r).'</a>';
    }

    die();
    
}

class ocms_searchFront {

    /**
     * Gensite
     *
     * @var Gensite
     */
    public $site;

    function __construct($site) {

	$this->site = $site;
	
    }
    
    function afterInit( ) {
	$this->site->g_headers->addCssText('#autocomplete a:hover, #autocomplete a.selected {background:'.COULEUR_2.'}');
    }

    function genRechercheForm() {

	$t = new genTemplate();
	return $t->loadTemplate('recherche_form', 'plugins/ocms_search/tpl')->gen();
    }

    function gen() {
	
    }

}

?>