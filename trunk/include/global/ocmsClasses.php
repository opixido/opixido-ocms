<?php


class ocmsPlugin {
	
	/**
	 * Gensite
	 *
	 * @var gensite
	 */
	public $site;
	
	/**
	 * Constructeur
	 *
	 * @param gensite $site
	 */
	function __construct($site) {
		
		$this->site = $site;
		
	}
	
}



class baseObj {
	
	
	/**
	 * gensite
	 *
	 * @var  gensite
	 */
	public $site;
	
	/**
	 * params
	 *
	 * @var array
	 */
	public $params;
	
	/**
	 * Table utilisée
	 *
	 * @var string Table SQL
	 */
	public $table = false;
	
	/**
	 * Clef passée dans l'URL pour la fiche
	 *
	 * @var string
	 */
	public $clef = 'id';
	
	function __construct($roworid=false) {
		
		/**
		 * Recuperation automatique
		 * des informations
		 */
		if(is_array($roworid)) {
			$this->row = $roworid;
			$this->id = $roworid[getPrimaryKey($this->table)];
		} else if($roworid) {
			$this->id = $roworid;
			$this->row = getRowAndRelFromId($this->table,$this->id);	
		} else 
		if($_REQUEST[$this->clef]) {
			$this->id = $_REQUEST[$this->clef];
			$this->row = getRowAndRelFromId($this->table,$this->id);			
		}
		
	}
	
	
	/**
	 * Recupération d'un champ
	 * (mini genform pour front)
	 *
	 * @param string $champ
	 * @return mixed
	 */
	function get($champ) {
		
		if(isBaseLgField($champ,$this->table)) {
			return getLgValue($champ,$this->row);
		}
		
		global $uploadFields;
		if(arrayInWord($uploadFields,$champ)) {
			$gf= new genFile($this->table,$champ,$this->row);
			return $gf;
		}
		
		
		return $this->row[$champ];	
	}
	
	
	/**
	 * Génére le tout
	 * Liste ou fiche élément
	 *
	 * @return string HTML
	 */
	function gen () {
		
		if($this->table) {
			if(akev($_GET,$this->clef)) {
				return $this->genOne();		
			} else {
				return $this->genAll();
			}		
		}	
		
		return ;	
	}
	
	
	/**
	 * Génére une fiche Element
	 *
	 * @return string
	 */
	function genOne() {
		
		$row = getRowFromId($this->table,$_GET[$this->clef]);
		
		$html = '<h3>'.GetTitleFromRow($this->table,$row).'</h3>';
		$html .= '<dl>';
		foreach($row as $k=>$v) {
			$html .= '<dt>'.t($k).'</dt>';
			$html .= '<dd>'.$v.'</dd>';
		}
		$html .= '</dl>';
		
		return $html;
		
	}
	
	
	/**
	 * Génére une liste
	 *
	 * @return unknown
	 */
	public function genAll() {
		
		$res = GetAll("SELECT * FROM ".$this->table.' ORDER BY '.GetTitleFromTable($this->table,' , '));
		
		$html = '<ul>';
		foreach($res as $row) {
			$html .= '<li><a href="'.getUrlWithParams(array($this->clef=>$row[getPrimaryKey($this->table)])).'">'.GetTitleFromRow($this->table,$row).'</a></li>';
		}
		$html .= '</ul>';
		
		return $html;
		
	}
	
	
	public function getTitle() {
		
		return GetTitleFromRow($this->table,$this->row);
		
	}
	
	
	public function getUrl() {
		
		
		return getUrlWithParams(array(getPrimaryKey($this->table)=>$this->id));
		
	}
}




class baseGen extends baseObj {
	
	
	function __construct($site,$params="") {
		
		$this->site = $site;
		$this->params = SplitParams($params,';','=');
		$this->plugins = $site->plugins;
		if(method_exists($this,'ocms_defaultParams')) {
			$defParams = $this->ocms_defaultParams();
			foreach($defParams as $k=>$v) {
				if(!$this->params[$k]) {
					$this->params[$k] = $v;
				}
			}
		}
		
		parent::__construct();
		
		/**
		 * Rajouts automatique au titre ou chemin de fer
		 */
		if($this->row) {
			$this->site->g_headers->addTitle(GetTitleFromRow($this->table,$this->row));
			$this->site->g_url->addRoad(GetTitleFromRow($this->table,$this->row),getUrlWithParams(array($this->clef=>$this->id)));
		}
		
	}
	
	
}




class ocmsGen extends baseGen {
	
	
	
}
