<?php


class ajaxBool {
	
	
	
	function __construct($af,$champ) {
		
		$this->af = $af;
		$this->champ = $champ;
		$this->row = $af->row;
		$this->table = $af->table;
		$this->id = $af->id;		
		
		$this->champ_id = $this->table.'-'.$champ;
		
	
	}
	
	
	function gen() {
		
		$html .= ( '<select id="'.$this->champ_id.'"
						 onchange="ajaxSaveValue(this,'.js($this->table).','.js($this->champ).','.js($this->id).')"  >' );
	

		$enums = array('0'=>'non','1'=>'oui');
		
		foreach( $enums as $k=>$v ) {
            $thisValue = t($v);
            if ( $this->getCurrent()==$k )
               $html .= ( '<option selected="selected" value="' . $k . '">' . ( $thisValue ) . '</option>' );
            else
               $html .= ( '<option  value="' . $k . '"> ' . ( $thisValue ) . '</option>' );
        }

     
        $html .= ( '</select>' );

        return $html;
		
	}
	
	function getCurrent() {

		return $this->row[$this->champ];
		
	}
	
	
	
	
}


?>