<?php



class ajaxRelations {
	
	
	
	function __construct($af,$champ,$fk_table) {
		
		$this->af = $af;
		$this->fk_table = $fk_table;
		$this->champ = $champ;
		$this->row = $af->row;
		$this->id = $af->id;
		$this->table = $af->table;
			
		$this->champ_id = $this->table.'-'.$champ;
		
	}
	
	
	function gen() {
		
		$list = $this->getListing();		
		$cur = $this->getCurrent();
		$pk = getPrimaryKey($this->fk_table);		
		
		$html = '<select ';
		$html .= ' id="'.$this->champ_id.'" ';
		$html .= ' onchange="ajaxSaveValue(this,'.js($this->table).','.js($this->champ).','.js($this->id).')" >';
		$html .= "\n";
		
		$html .= '<option value=""></option>';
					
		foreach($list as $v) {
			
			$html .= '<option ';			
			if($v[$pk] == $cur) {				
				$html .= ' selected="selected" ';				
			}		
			$html .= ' value="'.$v[$pk].'" ';			
			$html .= ' > ';			
			$html.= GetTitleFromRow($this->fk_table,$v);						
			$html .= '</option>';		
			$html .= "\n";
		}
		
		$html .= '</select>';
		
		$html .= "\n";
		
		return $html;
		
	}
	
	
	
	/**
	 * Returns current selected ID
	 *
	 * @return unknown
	 */
	function getCurrent() {
		
		return $this->row[$this->champ];
		
	}
	
	
	function getCurrentValue() {
		
		$row = getRowFromId($this->fk_table,$this->getCurrent());
		
		return GetTitleFromRow($this->fk_table,$row);
		
	}
	
	function getListing() {
		
		$res = getTableListing($this->fk_table, $this->table);
		return $res;
		
	}
	
	
	
}

?>