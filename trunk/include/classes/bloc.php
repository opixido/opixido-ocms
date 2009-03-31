<?php



class bloc {
	
	
	public $contenu = array();
	public $visible = true;
	public $nom = 'default';
	public $toAddBefore = array();
	public $toAddAfter = array();
	
	/**
	 * gensite
	 *
	 * @var gensite
	 */
	public $site;
	
	/**
	 * Constructeur
	 *
	 * @param genSite $site
	 */
	function __construct($site) {
		
		$this->site = $site;
		
		
		
	}
	
	/**
	 * Si il est bien visible, on décale le contenu
	 *
	 */	
	function afterInit() {
		
		if($this->visible) {
			
		} 
		
	}
	
	/**
	 * Sets this bloc not to render
	 *
	 */
	function hide() {
		
		$this->visible = false;
		
	}
	
	
	/**
	 * Makes this box visible
	 *
	 */
	function show() {
		
		$this->visible = true;
		
	}	
	
	/**
	 * returns content
	 *
	 */
	function genBloc () {
		
		$html = '<div id="bloc_'.$this->nom.'">';
		if($this->visible ) {
			foreach($this->contenu as $v ) {
				
				$html .= ($v);
				
				
			}
		}
		$html .= '</div>';
		return $html;
		
	}
	
	
	/**
	 * Ajoute une boite à la fin
	 *
	 * @param string $nom Nom de la boite
	 * @param string $html code HTML
	 */
	
	function add($nom,$html) {
		
		if(ake($this->toAddBefore,$nom)) {
			
			foreach($this->toAddBefore[$nom] as $k=>$v) {
				$this->contenu[$k] = $v;
			}
		}
				
		$this->contenu[$nom] = '<div id="bloc_'.$this->nom.'_'.$nom.'">'.$html.'</div>';
		
		if(ake($this->toAddAfter,$nom)) {
			
			foreach($this->toAddAfter[$nom] as $k=>$v) {
				$this->contenu[$k] = $v;
			}
		}
		
	}
	
	/**
	 * Ajoute une boite $nom apres la boite $other
	 *
	 * @param string $other
	 * @param string $nom
	 * @param string $html
	 */
	function addAfter($other,$nom,$html) {
		
		if(ake($other,$this->contenu)) {
			
		}
		else {
			$this->toAddAfter[$other][$nom] ='<div id="col_'.$nom.'">'.$html.'</div>';		
		}
	}
	
	/**
	 * Ajoute une boite $nom apres la boite $other
	 *
	 * @param string $other
	 * @param string $nom
	 * @param string $html
	 */
	function addBefore($other,$nom,$html) {
		
		if(ake($other,$this->contenu)) {
			
		}
		else {
			$this->toAddBefore[$other][$nom] ='<div id="col_'.$nom.'">'.$html.'</div>';		
		}
	}
		
	/**
	 * Ajoute en premier
	 *
	 * @param string $nom
	 * @param string $html
	 */
	
	function addAtTop($nom,$html) {
		
		$this->contenu = array_merge(array($nom=>'<div id="bloc_'.$this->nom.'_'.$nom.'">'.$html.'</div>'),$this->contenu);
		
	}
	
	
	/**
	 * Vide tout
	 *
	 */
	function clean() {
	
		$this->contenu = array();		
	}
	
	/**
	 * Ajoute un petit délimiteur
	 *
	 */
	function addSmallDelim($size=6) {
		
		global $nbDelim;
		$nbDelim++;
		$this->addAtEnd('delim_'.$nbDelim,'
			<div class="clearer">&nbsp;</div>
			<div class="col_delim">			
			</div>
			<div class="clearer">&nbsp;</div>');
		
	}
	
	/**
	 * Ajoute un grand délimiteur
	 *
	 */	
	function addBigDelim() {
		
		
	}
	
	
	function remove($nom) {
		
		unset($this->contenu[$nom]);
		
	}
	
	

	
}

?>