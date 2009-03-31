<?php


class frontOcms_imageParametrable {
	
	
	function __construct($site) {

		$this->site = $site;
		
	}
	
	
	
}


class imageP {
	
	/**
	 * Identifiant
	 *
	 * @var string
	 */
	private $id;
	
	/**
	 * Genfile
	 *
	 * @var genfile
	 */
	public $gf;
	
	/**
	 * Row 
	 *
	 * @var array
	 */
	public $row;
	
	public function __construct($id) {
		
		$this->id = $id;
		$sql = 'SELECT * FROM p_imagep WHERE imagep_label LIKE '.sql($this->id).' '.sqlVersionOnline();
		$this->row = GetSingle($sql);

		$this->gf = new genFile('p_imagep','imagep_img',$this->row);
		
		
	}
	
	/**
	 * Retourne le Genfile
	 *
	 * @return genfile
	 */
	public function getGf() {
		
		return $this->gf;
		
	}
	
	
	/**
	 * Retourne le tag complet de l'image
	 *
	 * @param string $tag Attribut supplémentaire pour la balise
	 */
	public function getImgTag($tag='') {
		return $this->gf->getImgtag($this->getAlt(),$tag);
	}
	
	
	/**
	 * Retourne juste le ALT
	 *
	 * @return string
	 */
	public function getAlt() {
		return getLgValue('imagep_alt',$this->row);		
	}
	
	
}

function imgP($str,$tag="") {
	$f = new imageP($str);
	return $f->getImgTag($tag);
}

?>