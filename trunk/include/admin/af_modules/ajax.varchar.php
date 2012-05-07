<?php


class ajaxVarchar {
	
	
	
	function __construct($af,$champ,$taille=64) {
		
		$this->af = $af;
		$this->champ = $champ;
		$this->row = $af->row;
		$this->table = $af->table;
		$this->id = $af->id;
                $this->taille = $taille;
		
		$this->champ_id = $this->table.'-'.$champ.'-'.$this->id;
		
	
	}
	
	
	function gen() {
		
		
		$html = '<input type="text" size="'.($this->taille > 64 ? 64 : $this->taille).'" class="ajax_varchar" id="'.$this->champ_id.'" ';
		$html .= ' onchange="ajaxSaveValue(this,'.js($this->table).','.js($this->champ).','.js($this->id).')" ';
			
		$html .= ' value='.alt($this->getCurrent());
		
		$html .= ' >'."\n";
		
		return $html;
		
	}
	
	function getCurrent() {

		return $this->row[$this->champ];
		
	}

}
