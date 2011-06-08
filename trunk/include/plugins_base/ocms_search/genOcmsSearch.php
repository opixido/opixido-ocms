<?php

class genOcmsSearch extends ocmsGen {

    /**
     * GenSite
     *
     * @var GenSite
     */
    var $site;
    var $nbPerType = array();

    function __construct($site, $params) {

	parent::__construct($site, $params);

	/**
	 * Class de recherche
	 */
	$GLOBALS['gb_obj']->includeFile('class.ocms_search.php', 'plugins/ocms_search');

	/**
	 * Headers et autres ...
	 */
	$this->site->g_headers->addCss('recherche.css');
	

	/**
	 * Recherche restreinte ?
	 */
	$this->type = '';

	/**
	 * Clauses spéciales pour les objets
	 */
	$select = $from = $where = '';

	
	/**
	 * Template général
	 */
	$this->tpl = new genTemplate();
	$this->tpl->loadTemplate('recherche', 'plugins/ocms_search/tpl')
		  ->defineBlocks('ITEM');

	/**
	 * Terme recherché
	 */
	$_REQUEST['q'] = strip_tags(akev($_REQUEST, 'q'));

	/**
	 * Terme recherché dans le title de la page
	 */
	$this->site->g_headers->addTitle(t('resultats') . ' ' . $_REQUEST['q']);

	/**
	 * On lance la recherche
	 */
	$s = new indexSearch($this->type);
	$res = $s->search($_REQUEST['q'],$select,$from,$where);
	
	$nbResReal = t('pas_de');
	
	/**
	 * Log des recherches
	 */
	TrySql('INSERT INTO os_recherches VALUES("",' . sql($_REQUEST['q'] ) . ',' . count($res) . ')');

	if (count($res)) {
	    /**
	     * Nombre réel de résultats
	     */
	    $nbResReal = 0;
	    
	    foreach ($res as $row) {
		
		/**
		 * Infos sur l'objet en cours
		 */
		if(!empty($row['spectacle_id'])) {
		    $infos = $row;
		    $obj = 'o_spectacle';
		    $row['obj'] = 'o_spectacle';
		} else {
		    $infos = getRowFromId($row['obj'], $row['fkid'], true);		
		    $obj = $row['obj'];
		}
		
		if($infos['privee']) {
		    continue;
		}
		
		/**
		 * Possiblement il a été supprimé et le moteur n'est pas à jour
		 * ou autre ...
		 */
		if (count($infos)) {
		    $nbResReal++;
		    $t = $this->tpl->addBlock('ITEM');
		    $t->nb = $nbResReal;
		    $t->class = $obj;
		    $t->url = getUrlFromSearch($row, $infos);
		    $t->img = getImgFromRow($obj, $infos, 176, 86);
		    $t->titre = GetTitleFromRow($obj, $infos, " / ");
		    $t->desc = strip_tags(getDescFromRow($obj, $infos, 20));
		}
	    }

	    /**
	     * Si aucun ...
	     */
	    if ($nbResReal == 0) {
		
	    }

	    /**
	     * Texte avant la liste avec nombre de résultats
	     */
	   
	}
	
	 $this->tpl->texte = $nbResReal . ' ' . t('resultats');

	$this->tpl->form = '';
	/**
	 * Le tout ...
	 */
	$this->html = $this->tpl->gen();
    }
    
    function afterInit() {
	$this->site->g_headers->addCssText('.itemliste:hover {background:'.COULEUR_2.'}');
    }

    function gen() {
	return $this->html;
    }

    public static function ocms_getPicto() {
	return ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/actions/system-search.png';
    }

}
