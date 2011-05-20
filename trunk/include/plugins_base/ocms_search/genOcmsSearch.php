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
	
	$s = 'SELECT '.sqlLgValue('saison_titre').' AS label , saison_id AS value FROM o_saison ORDER BY date_online ASC ';
	$saisons = getAll($s);

		
	$s = 'SELECT '.sqlLgValue('genre_titre').' AS label , genre_id AS value FROM o_genre ORDER BY genre_titre_'.LG.' ASC ';
	$genres = getAll($s);
	
	/**
	 * Formulaire 
	 */
	$f = new simpleForm();
	$f->add('text', akev($_REQUEST,'q'), t('recherche_q'), 'q', 'q')
	  ->add('select', $saisons, t('recherche_saison'), 'recherche_saison', 'recherche_saison')
	  ->add('select', $genres, t('recherche_genre'), 'recherche_genre', 'recherche_genre')
	  ->add('submit', t('recherche_submit'))
	  ->id = 'form_recherche';

	$this->site->plugins['o_blocs']->gauche->add('title',$this->site->plugins['ocms_title']->genBeforePara());
	$this->site->plugins['o_blocs']->gauche->add('form',$f->gen());
	$this->site->plugins['ocms_title']->visible = false;
	
	/**
	 * Recherche restreinte ?
	 */
	$this->type = '';

	/**
	 * Clauses spéciales pour les spectacles
	 */
	$select = $from = $where = '';
	if(!empty($_REQUEST['recherche_saison']) || !empty($_REQUEST['recherche_genre']) ) {
	    $this->type = "o_spectacle";
	    if(!empty($_REQUEST['recherche_saison']) ) {
		$where .= ' AND fk_saison_id = ' .sql($_REQUEST['recherche_saison']);
	    }
	    
	    if(!empty($_REQUEST['recherche_genre']) ) {
		$where .= ' AND fk_genre_id = ' .sql($_REQUEST['recherche_genre']);
	    }
	}
	
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
