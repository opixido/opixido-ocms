<?php


class ocms_titleFront{
	
	
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
	
	function __construct($site) {
		
		$this->site = $site;
		
		//$this->titre = getLgValue('rubrique_titre',$this->site->g_rubrique->rubrique);
		
	}
	
	
	function genBeforePara () {
		
		$html = '';
		
		/**
		 * Si on a un titre et qu'on veut etre vu 
		 */
		if($this->visible ) {
			
			/**
			 * On supprime le premier élément : "accueil"
			 */
			
			
			if($this->forceTitre) {
				$t = $this->forceTitre;
			} else {
				$s = array_slice($this->site->g_url->buildRoad(),1);
				$len = count($s);
				$t = $s[$len-1]['titre'];
			}
			

			if($t) 
				$html = '<h2>'.$t.'</h2>';
			
			
		}
		return '<div id="titres">'.$html.'<div class="clearer">&nbsp;</div></div>';
	}
	
	
}

