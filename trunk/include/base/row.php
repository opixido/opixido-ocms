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
			
			$fk_table = $relations[$this->table][$field];
			$coup = mb_substr($fk_table,strpos($this->table,'_')+1);
			
			if(class_exists($fk_table)) {
				$classe = $this->table;
			}
			else if(class_exists($coup)) {
				$classe = $coup;
			}
			
			if($classe) {
				return new $classe($this->row[$field]);
			} 
			
			return new row($fk_table,$this->row[$field]);
			
		} else if ($tablerel[$field]) {
		
			$found = false;
			
			while ( list( $k, $v ) = each( $tablerel[$field] ) ) {
				
				if ( $v == $this->table && !$found) {
					$found = true;
					$pk1 = $k;
				} else {
					$pk2 = $k;
					$fk_table = $v;
				}
			
			}		
			
			if ($found) {
				
				$sql = 'SELECT *
						FROM '.$fk_table.'
						WHERE '.getPrimaryKey($fk_table).' IN (SELECT '.$pk2.'
															   FROM '.$field.'
															   WHERE '.$pk1.' = '.$this->id.')';
				
				return GetAll($sql);
				
			}
			
				
		/**
		 * Relation inverse
		 */
		} else if ($relinv[$this->table][$field]) {
		
			$foreignTable = $relinv[$this->table][$field][0];
			
			$sql = 'SELECT *
				    FROM '.$foreignTable.'
				    WHERE '.$relinv[$this->table][$field][1].' = '.$this->id;
			
			if ($orderFields[$foreignTable]) {
				
				$sql .= ' ORDER BY '.$orderFields[$foreignTable][0];
				
			}
			
			return GetAll($sql);
					
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