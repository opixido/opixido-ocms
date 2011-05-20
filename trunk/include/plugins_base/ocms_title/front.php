<?php

class ocms_titleFront {

    /**
     * gensite
     *
     * @var gensite
     */
    public $site;
    /**
     * Visible ou non
     *
     * @var bool
     */
    public $visible = true;
    /**
     * Titre de la page a� afficher
     *
     * @var string
     */
    public $titre;
    public $forceTitre = '';
    public $className = '';

    function __construct($site) {

	$this->site = $site;

	//$this->titre = getLgValue('rubrique_titre',$this->site->g_rubrique->rubrique);
    }

    function genBeforePara() {

	$html = '';

	/**
	 * Si on a un titre et qu'on veut etre vu 
	 */
	if ($this->visible) {

	    /**
	     * On supprime le premier élément : "accueil"
	     */
	    if ($this->forceTitre) {
		$t = $this->forceTitre;
	    } else {
		$r = new rubrique($this->site->g_rubrique->rubrique);
		$t = choose($r->rubrique_titre_page, $r->rubrique_titre);
		/*
		  $s = array_slice($this->site->g_url->buildRoad(),1);
		  $len = count($s);
		  if(!empty($s[$len-1])) {
		  $t = $s[$len-1]['titre'];
		  } else {
		  $t = false;
		  $this->visible = false;
		  }

		 */
	    }


	    if ($t)
		$html = '<h1 id="h1" class="misob ' . $this->className . '">' . $t . '</h1>';
	}
	return '<div id="titres" class="clearfix">' . $html . '</div><div class="clearer">&nbsp;</div>';
    }
    
    function hide() {
	$this->visible = false;
    }
    
    function show() {
	$this->visible = true;
    }

}

