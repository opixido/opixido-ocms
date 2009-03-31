<?php


class genform_base {
	
	
	var $gf;
	var $table;
	var $id;
	var $champ;
	var $buffer;
	
	function __construct($table,$id,$champ,$gf=false) {
		
		global $editMode;
		$this->table = $table;
		$this->id = $id;
		$this->tab_name = $this->champ = $champ;
		$this->editMode = $gf ? $gf->editMode : $editMode;
		$this->buffer = '';
		if($gf)
			$this->gf = $gf;
		$this->init();
	}
	
	
	function gen() {


		/**
		 * Si on est en modification 
		 */
		if ( !$this->editMode ) {
		       return $this->genForm();
		} else {
			/**
			 * Sinon affichage seulement et pas de modification
			 */
		      return $this->genValue();
		}

		
		
	}
	
	
	function genForm() {
		
		$this->addBuffer('UNDEFINED FORM');
		
	}
	
	function genValue() {
		
		$this->addBuffer('UNDEFINED VALUE');
		
	}	
	
	
	function addBuffer($v)  {
		
		$this->buffer .= $v;
		
	}
	
	function getBuffer() {
		
		return $this->buffer;
		
	}
	
}

?>