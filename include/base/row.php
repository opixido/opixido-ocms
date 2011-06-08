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
		
		if(!$this->row) {
		    $this->id = 0;
		    return false;
		}
		$this->id = $this->row[getPrimaryKey($table)];

		$this->tabField = getTabField($this->table);		
		$this->site = akev($GLOBALS,'site');
		
		if(!$this->id) {
		    $this->id = 0;
		    return false;
		}
		
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
		global $uploadFields,$relations,$relinv,$tablerel,$orderFields;
		
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
		else if(!empty($relations[$this->table][$field] )) {
			
			$fk_table = $relations[$this->table][$field];
			$coup = mb_substr($fk_table,strpos($this->table,'_')+1);
			$classe = false;
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
			
		} else if (!empty($tablerel[$field])) {
			
			/**
			 * Table de relation
			 */
			
			$found = false;
			reset($tablerel[$field]);
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
				
				$sql = 'SELECT T.*
						FROM '.$fk_table.' AS T, '.$field.' AS R
						WHERE '.getPrimaryKey($fk_table).' = '.$pk2.'
						AND '.$pk1.' = '.sql($this->id).'';
				
				if ($orderFields[$field]) {					
					$sql .= ' ORDER BY '.$orderFields[$field][0];					
				}
				
				return GetAll($sql);
				
			} else {
				
			}
			
				
		/**
		 * Relation inverse
		 */
		} else if (!empty($relinv[$this->table][$field])) {
		
			
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