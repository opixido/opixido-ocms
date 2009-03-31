<?php


class ajaxUpload {
	
	
	
	function __construct($af,$champ) {
		
		$this->af = $af;
		$this->champ = $champ;
		$this->row = $af->row;
		$this->table = $af->table;
		$this->id = $af->id;		
		
		$this->champ_id = $this->table.'_'.$champ.'_'.$this->id;
	
	}
	
	
	function gen() {
		
		$html .= '
		         <form action="index.php" 
					method="post" 
					id="fo_'.$this->champ_id.'" 
					enctype="multipart/form-data" 
					target="if_'.$this->champ_id.'">'."\n";
		$html .= '<input type="hidden" name="xhr" value="ajaxForm" />'."\n";
		$html .= '<input type="hidden" name="table" value="'.$this->table.'" />'."\n";
		$html .= '<input type="hidden" name="champ" value="'.$this->champ.'" />'."\n";
		$html .= '<input type="hidden" name="id" value="'.$this->id.'" />'."\n";
		$html .= '<input type="hidden" name="upload" value="1" />'."\n";
		
		$gf = $this->getCurrent();
		
		if($gf->fileExists()) {
			echo '<a href="'.$gf->getWebUrl().'">'.($gf->getRealName().' '.$gf->getNiceSize()).'</a>'."\n";
		}
		
		$html .= '<input name="maf" type="file" class="ajax_upload" id="'.$this->champ_id.'" ';
		$html .= ' onchange="gid('.js('fo_'.$this->champ_id).').submit()" ';		//gid('.js('fo_'.$this->champ_id).').submit()
		$html .= ' />'."\n";
		$html .= '</form>'."\n";
		$html .= '<iframe src="http://faitou/" id="if_'.$this->champ_id.'" name="if_'.$this->champ_id.'" stylee="border:0;height:0;width:0;visibility:hidden"></iframe>'."\n"."\n";
		
		return $html;
		
	}
	
	/**
	 * Retourne un objet genfile
	 *
	 * @return genfile
	 */
	function getCurrent() {

		$gf = new genFile($this->table,$this->champ,$this->id,$this->row);
		return $gf;
		
	}
	
	
	
	
}


?>