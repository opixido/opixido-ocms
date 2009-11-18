<?php



class rubrique extends row {
	
	public $table = 's_rubrique';
	
	function __construct($row) {
		parent::__construct($this->table,$row);
	}
	
	/**
	 * Returns the $limit following rubriques
	 *
	 * @param int $limit
	 * @return array
	 */
	function getNextRubs($limit=1) {
		
		return $this->getAdjacentRubs($limit,'ASC');
		
	}
	
	/**
	 * Returns the $limit previous rubriques
	 *
	 * @param int $limit
	 * @return array
	 */
	function getPreviousRub($limit=1) {

		return $this->getAdjacentRubs($limit,'DESC');
		
	}
	
	
	/**
	 * Returns the $limit adjacent rubriques 
	 * if $order is ASC, returns the next rubs, 
	 * if $order is DESC, returns the previous rubs
	 *
	 * @param int $limit
	 * @param string $order ASC or DESC
	 * @return array
	 */
	function getAdjacentRubs($limit = 0,$order = '') {
		
		/**
		 * Sql Query
		 */
		$sql = 'SELECT * FROM s_rubrique 
		WHERE
		fk_rubrique_id = '.sql($this->row['fk_rubrique_id']).' 
		';
		
		if($order == 'ASC') {
			$sql .= ' AND rubrique_ordre > '.$this->row['rubrique_ordre'].' ';
		} else if($order == 'DESC') {
			$sql .= ' AND rubrique_ordre < '.$this->row['rubrique_ordre'].' ';
		}
		
		$sql .= ' '.sqlRubriqueOnlyOnline().' 
		
		ORDER BY rubrique_ordre '.$order.' 
		
		';

		/**
		 * How many next rubs
		 */
		if($limit) {
			$sql .= ' LIMIT 0,'.$limit.'';
		}
		
		/**
		 * If only one, the returns a getSingle instead of GetAll
		 */
		if($limit == 1) {
			return GetSingle($sql);
		}
		
		return GetAll($sql);		
		
	}
	
	
	/**
	 * Returns the $row of the parent rubrique
	 * or false if we are on siteroot
	 *
	 * @return mixed
	 */
	function getParentRub() {		
		if($this->row['fk_rubrique_id']) {
			return getRowFromId('s_rubrique',$this->row['fk_rubrique_id']);
		}
		return false;
	}
	
	
	/**
	 * Returns child rubriques
	 *
	 * @return array
	 */
	function getChildRubs() {
		$sql = 'SELECT * FROM s_rubrique WHERE fk_rubrique_id = '.sql($this->id).' '.sqlRubriqueOnlyOnline();
		return GetAll($sql);			
	}
	
	
	/**
	 * Returns rubrique URL
	 *
	 * @param array $params
	 * @return string
	 */
	function getUrl($params = array()) {		

		return getUrlFromId($this->id,LG,$params);
		
	}
	
	/**
	 * Returns Rubrique Title
	 *
	 * @return string Title
	 */
	function getTitle() {
		return $this->rubrique_titre;
	}
	
	
}