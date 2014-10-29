<?php
/*
 * Ajout Timothée Octobre 2013
 */

class ajaxText {
	
	
	
	function __construct($af,$champ,$taille=500) {
		
		$this->af = $af;
		$this->champ = $champ;
		$this->row = $af->row;
		$this->table = $af->table;
		$this->id = $af->id;
                $this->taille = $taille;
		
		$this->champ_id = $this->table.'-'.$champ.'-'.$this->id;
		
	
	}
	
	
	function gen() {
		
		
		$html = '<textarea size="'.($this->taille > 500 ? 500 : $this->taille).'" class="ajax_text" id="'.$this->champ_id.'" ';
		$html .= ' onchange="ajaxSaveValue(this,'.js($this->table).','.js($this->champ).','.js($this->id).')" >';
			
		$html .= $this->getCurrent();
		
		$html .= ' </textarea>'."\n";
		
		return $html;
		
	}
	
	function getCurrent() {

		return $this->row[$this->champ];
		
	}

}
