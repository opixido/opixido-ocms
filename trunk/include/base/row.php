<?php



class row {
	
	
	function __construct($table,$roworid) {
		
		$this->table = $table;
		
		if(is_array($roworid)) {
			$this->row = $roworid;
			$this->id = $this->row[getPrimaryKey($table)];
		} else {
			$this->id = $roworid;
			$this->row = getRowAndRelFromId($table,$this->id);			
		}
		
		$this->tabField = getTabField($this->table);		
		
	}
	
	/**
	 * Returns nice value for the specified $field
	 * 
	 *
	 * @param string $field
	 * @param bool $raw returns the raw value of the field, not parsed
	 * @return mixed
	 */
	function get($field,$raw=false) {
		
		/**
		 * Raw value ...
		 */
		if($raw) {
			return $this->row[$field];
		}
		
		/**
		 * Check field types
		 */
		global $uploadFields,$relations;
		
		/**
		 * Upload => genfile
		 */
		if(arrayInWord($uploadFields,$field)) {
			
			return new genFile($this->table,$field,$this->row);
			
		}
		/**
		 * LG Field
		 */
		else if(isBaseLgField($field,$this->table)) {
			
			return getLgValue($field,$this->row);
			
		}
		/**
		 * Foreign key
		 */
		else if($relations[$this->table][$field] ) {
			
			return new row($relations[$this->table][$field],$this->row[$field]);
			
		/**
		 * Raw value
		 */
		} else {
			
			$type = $this->tabField[$field]->type;
			
			if($type == 'date' || $type == 'datetime') {
				
				return new Date($this->row[$field]);
			}
			
			if(substr($type,0,4) == 'set(') {
				return explode(',',$this->row[$field]);
			}
			
			return $this->row[$field];
			
		}
	
		
	}
	
	
	function __get($name) {
		return $this->get($name);		
	}
	
	
}