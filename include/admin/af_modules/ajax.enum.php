<?php


class ajaxEnum {
	
	
	
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
	
		$enums = getEnumValues($this->table,$this->champ);


        foreach( $enums as $enum ) {
            $thisValue = t('enum_'.$enum);
            if ( strcmp( $this->getCurrent(), $enum ) == 0  )
               $html .= ( '<option selected="selected" value="' . $enum . '">' . ( $thisValue ) . '</option>' );

            else
               $html .= ( '<option  value="' . $enum . '"> ' . ( $thisValue ) . '</option>' );
        }

     
        $html .= ( '</select>' );

        return $html;
		
	}
	
	function getCurrent() {

		return $this->row[$this->champ];
		
	}
	
	
	
	
}


?>