<?php
#
# This file is part of oCMS.
#
# oCMS is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido 
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#


class genAction {


	public $action;
	public $table;
	public $id;
	public $row;

	public function __construct ($action, $table, $id, $row = array()) {

		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		$this->row = $row;
		global $gs_obj;

		if(count($this->row) < 1) {
			$this->row = GetRowFromId($table,$id);
		}

		//debug(array($action,$table,$row,$id));

		if($gs_obj->can($this->action,$this->table,$this->row,$this->id)) {

			$cname = 'genAction'.ucfirst($action);
			if(class_exists($cname)) {
				$this->obj = new $cname($this->action,$this->table,$this->id,$this->row);

			} else {
				global $genMessages;
				$genMessages->add(t('action_inexistante').' : '.$cname);
			}
		} else {
			
			return false;
		}

	}
	
	


	public function doIt() {
		if( is_object ( $this->obj) ) {
			//debug($this->action.'s_rubrique'.$this->id);
			if($this->obj->checkCondition()) {
				$gr = new genRecord($this->table,$this->id);
				$gr->checkDoOn($this->action);
				logAction($this->action,$this->table,$this->id);
				return $this->obj->doIt();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}



	public function checkCondition() {
		if( is_object ( $this->obj) ) {
			return $this->obj->checkCondition();
		} else {
			return false;
		}
	}
	
	public function canReturnToList() {
		if( is_object ( $this->obj) ) {
			return $this->obj->canReturnToList;
		} else {
			return false;
		}	
	}

}



class baseAction {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = false;

	public function __construct ($action, $table, $id, $row = array()) {
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		$this->row = $row;
		if(!is_array($this->row) || !count($this->row) ) {
			$this->row = getRowFromId($this->table,$this->id);
		}
	}
	
	
	public function checkCondition() {
		debug('NO "checkCondition" SPECIFIED / PLEASE CREATE A "checkCondition" METHOD');
		return false;
	}	
	
	public function doIt() {
		debug('NO "doIt" SPECIFIED / PLEASE CREATE A "doIt" METHOD');
		return false;
	}	
}



class genActionEdit {

	public $action;
	public $table;
	public $id;
	public $row;

	public function __construct ($action, $table, $id, $row = array()) {
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
	}
	
	
	public function checkCondition() {
		return $GLOBALS['gs_obj']->can('edit',$this->table,$this->id);
	}	
	
	public function doIt() {
		
	}
}




class genActionView {

	public $action;
	public $table;
	public $id;
	public $row;

	public function __construct ($action, $table, $id, $row = array()) {
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
	}
	
	
	public function checkCondition() {
		
		return $GLOBALS['gs_obj']->can('view',$this->table,$this->id) && !$_REQUEST['resume'];
		
	}	
	
	public function doIt() {
		
	}
}





class genActionDel {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

	public function __construct ($action, $table, $id, $row = array()) {
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
	}
	
	
	public function checkCondition() {
		return $GLOBALS['gs_obj']->can('del',$this->table,$this->id);
	}	
	
	public function doIt() {
		
		$gr = new genRecord($this->table,$this->id);
		$gr->DeleteRow($this->id);
		
		//dinfo(t('element_supprime'));
		header('location:?curTable='.$this->table);
		die();
		//header('location:index.php?curTable='.$this->table);
		
	}
	
	public function getForm() 
	{
		
		p('<a href="#" 
				onclick="if(prompt(\''.t('confirm_delete').'\',\'NON\') == \'OUI\')
					{ window.location=
						\'index.php?curTable='.$this->table.'&curId='.$this->id.'&genform_action[del]=1\';
						return false;
					}
					 else {
					 	return false;
					}"
				class="abutton" >
				<img src="'.t('src_del').'" alt="" /> '.t('delete').'</a>');
		
	}
}





class genActionMoveRubrique {

	public $action;
	public $table;
	public $id;
	public $row;

	public function __construct ($action, $table, $id, $row = array()) {
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		
		if(!count($row) || !$row['fk_rubrique_version_id'])
		$this->row = getRowFromId($this->table,$this->id);
		else
		$this->row = $row;
		
	}
	
	
	public function checkCondition() {
		
		return true;
		
	}	
	
	public function doIt() {
		
		
		$row = GetSingle('SELECT MAX(rubrique_ordre) AS MX FROM s_rubrique WHERE fk_rubrique_id '.sqlParam($_REQUEST['move_rubrique']));
		
		
		$row['MX'] = $row['MX'] ? $row['MX'] : 1;
		
		$sql = 'UPDATE 
					s_rubrique 
					SET 
					fk_rubrique_id = '.sql($_REQUEST['move_rubrique']).' 
					, rubrique_ordre = '.$row['MX'].'
					WHERE rubrique_id = '.sql($this->id).'
					OR rubrique_id = '.sql($this->row['fk_rubrique_version_id']).'
					';
		DoSql($sql);
	
	}
	
	public function getForm() {
		p('<label for="move_rubrique">'.t('rubrique_deplacer_sous').'</label>');
		p('<select id="move_rubrique" name="move_rubrique" style="width:200px;">');
		if(is_array($GLOBALS['gs_obj']->myroles['s_rubrique']['rows'])) {
			$liste = $GLOBALS['gs_obj']->myroles['s_rubrique']['rows'];
			$res = array();
			foreach($liste as $rub) {
				
				$res = array_merge(getArboOrdered($rub,99999),$res);
			}
		} else {
			$res = getArboOrdered('NULL',99999);
		}
		
		p('<option value="">---------</option>');
		p('<option value="NULL">['.t('deplacer_a_la_racine').']</option>');
		foreach($res as $row) {
			if($row['rubrique_id']  && $row['rubrique_id'] != $this->row['fk_rubrique_version_id']) {
				//$row = addRowToTab($row,$row['level']);
				print('<option value="'.$row['rubrique_id'].'">'.str_repeat('&nbsp;&nbsp;&nbsp;',$row['level']).''.getLgValue('rubrique_titre',$row).'</option>');
			}
		}
		
		p('</select>');
		p('<input name="genform_action[moveRubrique]" value="Go" type="submit" />');
		
	}
	

}


class genActionHideVersion {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

	public function __construct ($action, $table, $id, $row = array()) {


		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		
		if(!count($row) || !ake($row,ONLINE_FIELD)) {
			$this->row = getRowFromId($table,$id);
		} else {
			$this->row = $row;
		}
		
		$this->onlineRow = getRowfromid($table,$this->row[VERSION_FIELD]);
		

	
		
	}
	
	
	public function checkCondition() {
		
		if($this->onlineRow[ONLINE_FIELD])
			return true;
		else 
			return false;
	}	
	
	public function doIt() {
		
		$res = DoSql('UPDATE '.$this->table.' SET '.ONLINE_FIELD.' = "0" 
				WHERE '.getPrimaryKey($this->table).' = "'.$this->onlineRow[getPrimaryKey($this->table)].'" ');
		
		if($res) {
			dinfo(t('element_plus_visible'));
		} else {
			derror(t('erreur_hideversion'));			
		}
	}
	
	

}




class genActionHideObject {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

	public function __construct ($action, $table, $id, $row = array()) {


		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		if(!count($row) || !ake($row,ONLINE_FIELD)) {
			$this->row = getRowFromId($table,$id);
		} else {
			$this->row = $row;
		}
		
	}
	
	
	public function checkCondition() {
		
		if($this->row[ONLINE_FIELD])
			return true;
		else 
			return false;
	}	
	
	public function doIt() {
		
		$res = DoSql('UPDATE '.$this->table.' SET '.ONLINE_FIELD.' = "0" 
				WHERE '.getPrimaryKey($this->table).' = "'.$this->id.'" ');
		
		if($res) {
			dinfo(t('element_plus_visible'));
		} else {
			derror(t('erreur_hideversion'));			
		}
	}
	
	

}


	
class genActionShowObject {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

	public function __construct ($action, $table, $id, $row = array()) {


		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		if(!count($row) || !ake($row,ONLINE_FIELD)) {
			$this->row = getRowFromId($table,$id);
		} else {
			$this->row = $row;
		}
		
	}
	
	
	public function checkCondition() {

		if(!$this->row[ONLINE_FIELD])
			return true;
		else 
			return false;
	}	
	
	public function doIt() {
		
		$res = DoSql('UPDATE '.$this->table.' SET '.ONLINE_FIELD.' = "1" 
				WHERE '.getPrimaryKey($this->table).' = "'.$this->id.'" ');
		
		if($res) {
			dinfo(t('element_visible'));
		} else {
			derror(t('erreur_showversion'));			
		}
	}
	
	

}



class objDuplication {
	
	
	
	public $action;
	public $table;
	public $id;
	public $row;
	public $noCopyField = array();
	

	public function __construct ($table, $id, $row = array()) {
	
		global $_Gconfig;
		
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		if(!is_array($row)) {
			$this->row = getRowFromId($table,$id);
		} else {
			$this->row = $row;
		}
		$this->noCopyFields = $_Gconfig['noCopyField'];
		$this->noCopyField[] = getPrimaryKey($table);
	}
	
	
	function duplicateTo($new_id ='new') {
		
		global $uploadFields,$_Gconfig,$relinv,$tablerel,$tablerel_reverse;
		
		genTableRelReverse();
		
		/**
		 * New ID or current ID ?
		 */
		if($new_id == 'new' && !$tablerel[$this->table]) {			
			$new_id = insertEmptyRecord($this->table);			
		}
		
		$newId = $new_id;
		
		if(!$newId) {
			derror('No ID');
			return false;
		}
		
		
		/**
		 * Préparation de la requête
		 * @old $sql = 'UPDATE '.$this->table.' SET ';
		 */
		
		$record = array();
		/**
		 * On copie d'abord les champs normaux
		 */
		while(list($k,$v) = each($this->row)) {
			/**
			 * Si il n'est pas dans la liste des fichiers à ne pas copier
			 * et que la clef n'est pas numérique (duplicat ADODB)
			 */
			if(
				!@in_array($k,$this->noCopyField) 
			 	 && !is_int($k) 
			  ) 
				{
				/**
				 * On met à jour le champ
				 * @old $sql .= ' '.$k.' = '.getNullValue(($v),$k,$this->table).' ,';
				 */						
				
				$record[$k] = $v;//getNullValue(($v),$k,$this->table);
				
				/**
				 * Si c'est un champ d'upload 
				 * on le met de côté
				 */
				if ( arrayInWord( $uploadFields, $k ) ) {
					$oldfile = new genFile($this->table,$k,$this->id,$v);
					$oldfiles[] = array('path'=>$oldfile->getSystemPath(),'valeur'=>$v,'champ'=>$k);
			    }
			}
		}
		
		
		/**
		 * On copie tous les fichiers de l'ancienne version vers la nouvelle
		 */
		if(count($oldfiles)) {
			foreach($oldfiles as $oldfile) {
				$newfile =  new genFile($this->table,$oldfile['champ'],$newId,$oldfile['valeur']);
				if(file_exists($oldfile['path'])) {
					$newfile->uploadFile($oldfile['path']);
				}

			}
		}		
		
		unset($oldfiles);
		
		
		
		/**
		 * Fin de la requête
		 * @old $sql = substr($sql,0,-1);
		 *      $sql .= ' WHERE '.getPrimaryKey($this->table).' = '.sql($newId,'int').' ';		
		 *      DoSql($sql);
		 */
		global $co;
		$co->autoExecute($this->table,$record,'UPDATE',getPrimaryKey($this->table).' = '.sql($newId,'int'));	
		
		
		/**
		 * On duplique les traduction supplémentaires
		 */
		$this->deleteAndDupli('s_traduction', 'fk_id',$this->id,$newId,'fk_table = "'.$this->table.'" AND ');
		
		
		
		
		/**
		 * On duplique les tables liées (relations inverses) 1<=n
		 */
		if(is_array($relinv[$this->table])) {
			foreach($relinv[$this->table] as $k=> $v) {
				//debug($v[0].' - '.$v[1]);
				$this->deleteAndDupli($v[0], $v[1],$this->id,$newId);
			}
		}
		
		/**
		 * On duplique les tables de relations n<=>n
		 */
		if(is_array($tablerel_reverse[$this->table])) {			
			foreach($tablerel_reverse[$this->table] as $k => $v) {		
				if(!@in_array($v['tablerel'],$_Gconfig['tablerelNotToDuplicate'][$this->table])) {		
					$this->deleteAndDupli($v['tablerel'], $v['myfk'],$this->id,$newId);			
				}
			}			
		}	
		
		
		return $newId;
		
	}
	
	
	/**
	 * Duplique toutes les liaisons vers un enregistrement
	 *
	 * @param string $table
	 * @param string $fkchamp Clef externe vers l'enregistrement principal
	 * @param mixed $idfrom identifiant de l'enregistrement principal source
	 * @param mixed $idto identifiant du nouvel enregistrement principal
	 * @param string $fk_cond condition SQL
	 */
	function deleteAndDupli($table,$fkchamp ,$idfrom,$idto,$fk_cond='') {		
		
		//debug('DELETE AND DUPLI : '.$table.' - '.$fk_champ.' - '.$idfrom.' - '.$idto);
		//return;
		global $tablerel,$uploadFields;	
		
		$pk = GetPrimaryKey($table);

		
		/**
		 * Suppression 
		 */
		if($table == 's_traduction') {
			
			$sql = 'DELETE  FROM '.$table.' WHERE '.$fk_cond.' '.$fkchamp.' = '.sql($idto,'int').' ';
			$res = DoSql($sql);
			
			
		} else {
			
			/**
			 * Si c'est une table de relation
			 * On ne supprime que les enregistrements de la table de relation
			 */
			if(ake($table,$tablerel)) {
				$sql = 'DELETE FROM '.$table.' WHERE '.$fk_cond.' '.$fkchamp.' = "'.mes($idto,'int').'" ';				
				DoSql($sql);

			/**
			 * Si c'est une table de relation inverse $relinv
			 * on supprime les enregistrements de cette table et tout ce qui le concerne
			 */
			} else {
				
			
				$sql = 'SELECT * FROM '.$table.' WHERE '.$fk_cond.' '.$fkchamp.' = "'.mes($idto,'int').'" ';
				$res = GetAll($sql);
				
				foreach($res as $row) {	
					$gr = new genRecord($table,$row[$pk]);
					$gr->deleteRow($row[$pk]);
				}
			}
		}
		

		/**
		 * On sélectionne tous les enregistrement 
		 * 
		 */
		$sql = 'SELECT * FROM '.$table.' WHERE  '.$fk_cond.' '.$fkchamp.' = "'.mes($idfrom,'int').'" ';
		$res = GetAll($sql);	

		//debug($res);

		global $co;
		foreach($res as $row) {			
		
			if($tablerel[$table]) {
				
				$row[$fkchamp] = $idto;
				$co->autoexecute($table,$row,'INSERT');
				
			} else {
				//$record = array();
				$row[$fkchamp] = $idto;
				
				$ob = new objDuplication($table,$row[getPrimaryKey($table)],$row);
				$id = $ob->duplicateTo('new');
			}
			
		}
		
		
		return;
//		/**
//		 * On parcourt tous les enregistrements 
//		 * 
//		 */		
//		foreach($res as $row) {			
//		
//			$record = array();
//			$record[$fkchamp] = $idto;
//			$oldfiles = array();
//			
//			/**
//			 * Clef primaire
//			 */
//			$id = $row[$pk];
//			
//			/**
//			 * On insert les nouvelles relations
//			 */
//			$sql = 'INSERT INTO '.$table.' ( ';
//			
//			$values =  ' ( ';
//			
//			/**
//			 * Si c'est une table de relation
//			 * et qu'elle a un autoincrement
//			 */
//			if(!is_array($tablerel[$table])) {
//				if($pk) {
//					$sql .= $pk.' , ';					
//					$values .= ' "" , ';
//				}
//			}
//			
//			/**
//			 * Nouveau champ et nouvelle valeur
//			 */
//			$sql .= '  '.$fkchamp.' ';			
//			$values .= '  '.sql($idfrom,'int').' ';
//			
//			/**
//			 * On parcourt tous les champs de l'enregistrement
//			 */
//			while(list($k,$v) = each($row)) {
//				
//				/**
//				 * Si c'est un champ à copier
//				 */
//				if(!@in_array($k,$this->noCopyField) && !is_int($k) && $k != $pk && $k != $fkchamp) {
//					
//					/**
//					 * On concatène à la requête
//					 * le champ et sa valeur
//					 */
//					$sql .= ' , '.$k.' ';					
//					$values .= ' , '.getNullValue(($v),$k,$table).' ';
//					
//					$record[$k] = $v;
//					
//					/**
//					 * si c'est un champ "fichier" 
//					 * On copie le fichier
//					 */
//					 if ( arrayInWord( $uploadFields, $k ) ) {
//
//						$oldfile = new genFile($table,$k,$id,$v);
//						$oldfiles[] = array('path'=>$oldfile->getSystemPath(),'valeur'=>$v,'champ'=>$k);
//						
//						/**
//						 * Si c'est un champ fichier traduisible
//						 * on rajoute tous les autres champs
//						 */
//						if(isDefaultLgField($k)) {
//						 	
//						 	$bch = fieldWithoutLg($k);
//						 	//debug($k.$bch);
//						 	$lgs = getLanguages($table,$id,$bch);
//						 	foreach($lgs as $lg) {
//						 		$va = getTradValue($table,$id,$bch,$lg);
//						 		$oldfile = new genFile($table,$bch.'_'.$lg,$id,$va);
//								$oldfiles[] = array('path'=>$oldfile->getSystemPath(),'valeur'=>$va,'champ'=>$bch.'_'.$lg);
//						 	}
//						 }
//
//					 }
//					 
//					 
//				}
//			}
//
//			/**
//			 * Fin de la requête
//			 */
//			$values .= ' ) ';
//
//			$sql .= ' ) VALUES '.$values;
//			
//			//$res = DoSql($sql);
//			global $co ;
//			//derror('A TESTER A TESTER');
//			$co->autoExecute($table,$record,'INSERT');
//			//debug($co->getInsertSql($table,$record));
//			
//
//			
//			/**
//			 * On récupère le nouvel identifiant 
//			 */
//			$curNewId = InsertId();
//			
//			
//			/**
//			 * On reprend toutes les traductions pour cet enregistrement et on les copie
//			 */
//			if($table != 's_traduction') {
//				
//				$this->deleteAndDupli('s_traduction', 'fk_id',$curNewId,$id,'fk_table = "'.$table.'" AND');
//				
//			}
//
//			
//			/**
//			 * Et on copie tous les fichiers
//			 */
//			if(count($oldfiles)) {
//				
//				foreach($oldfiles as $oldfile) {
//					
//					if(strlen($oldfile['path']) && file_exists($oldfile['path']) && $oldfile['valeur']) {
//							$newfile =  new genFile($table,$oldfile['champ'],$curNewId,$oldfile['valeur']);
//							$newfile->uploadFile($oldfile['path']);
//					}
//
//				}
//				
//			}		
//			
//		}	
	
	}
	
}


class genActionValidateVersion {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

	public function __construct ($action, $table, $id, $row = array()) {
		
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		
		$this->row = getRowFromId($table,$id);		
		
	}
	
	
	public function checkCondition() {

		return true;

	}	
	
	
	public function doIt() {
		
		global $uploadFields,$_Gconfig,$relinv,$tablerel,$tablerel_reverse;
		
		
		$newId = $this->row[VERSION_FIELD];
		
		$od = new objDuplication($this->table,$this->id,$this->row);
		$od->noCopyField[] = VERSION_FIELD;
		$od->noCopyField[] = ONLINE_FIELD;
		$newId = $od->duplicateTo($newId);

		$record = array();
		$record[ONLINE_FIELD] = 1;
		
		global $co;
		($co->AutoExecute($this->table,$record,'UPDATE',' '.getPrimaryKey($this->table).' = '.sql($newId)));
		dinfo(t('modifications_en_ligne'));			
		return;
//		/**
//		 * On Parcourt tous les champs de la table et on créé la requete
//		 */
//		$sql = 'UPDATE '.$this->table.' SET ';
//
//		while(list($k,$v) = each($this->row)) {
//			if(!@in_array($k,$_Gconfig['noCopyField']) && !is_int($k) && !in_array($k,array(getPrimaryKey($this->table),VERSION_FIELD,ONLINE_FIELD))) {
//				$sql .= ' '.$k.' = '.getNullValue(($v),$k,$this->table).' ,';
//				
//				if ( arrayInWord( $uploadFields, $k ) ) {
//
//					$oldfile = new genFile($this->table,$k,$this->id,$v);
//					$oldfiles[] = array('path'=>$oldfile->getSystemPath(),'valeur'=>$v,'champ'=>$k);
//			    }
//			}
//		}
//		/**
//		 * On copie tous les fichiers de l'ancienne version vers la nouvelle
//		 */
//		if(count($oldfiles)) {
//			foreach($oldfiles as $oldfile) {
//				$newfile =  new genFile($this->table,$oldfile['champ'],$newId,$oldfile['valeur']);
//				if(file_exists($oldfile['path'])) {
//					$newfile->uploadFile($oldfile['path']);
//				}
//
//			}
//		}		
//		
//		unset($oldfiles);
//
//
//		/**
//		 * On finit la requete
//		 */
//		$sql .= ' '.ONLINE_FIELD.' = 1 ';
//
//		$sql .= ' WHERE '.getPrimaryKey($this->table).' = "'.mes($newId,'int').'" ';
//		DoSql($sql);
//		
//		
//		/**
//		 * On duplique les traduction supplémentaires
//		 */
//		$this->deleteAndDupli('s_traduction', 'fk_id','fk_table = "'.$this->table.'" AND ',$newId,$this->id);
//		
//		
//		/**
//		 * On duplique les tables liées (relations inverses) 1<=n
//		 */
//		if(is_array($relinv[$this->table])) {
//			
//			foreach($relinv[$this->table] as $k=> $v) {
//				$this->deleteAndDupli($v[0], $v[1],'',$newId,$this->id);
//			}
//		}
//		
//		genTableRelReverse();
//		
//		/**
//		 * On duplique les tables de relations n<=>n
//		 */
//		if(is_array($tablerel_reverse[$this->table])) {			
//			foreach($tablerel_reverse[$this->table] as $k => $v) {	
//				if(!@in_array($v['tablerel'],$_Gconfig['tablerelNotToDuplicate'][$this->table])) {										$this->deleteAndDupli($v['tablerel'], $v['myfk'],'',$newId,$this->id);			
//					//debug($v['tablerel'].' - '.$v['myfk'].' - '.$newId.' * '.$this->id);
//				}
//			}
//		}	
		
		
		
	}
	
//	
//	function deleteAndDupli($table,$fkchamp = 'fk_rubrique_id',$fk_cond = '',$idfrom=0,$idto=0) {
//
//		
//		
//		
//		global $tablerel,$uploadFields;
//
//		$idfrom = $idfrom ? $idfrom : $this->row['fk_rubrique_version_id'];
//		$idto = $idto ? $idto : $this->row['rubrique_id'];
//			
//		
//		
//		$pk = GetPrimaryKey($table);
//
//		if($table == 's_traduction') {
//			
//			$sql = 'DELETE  FROM '.$table.' WHERE '.$fk_cond.' '.$fkchamp.' = "'.mes($idfrom,'int').'" ';
//			$res = DoSql($sql);
//			
//			
//		} else {
//			
//			if(ake($table,$tablerel)) {
//				$sql = 'DELETE FROM '.$table.' WHERE '.$fk_cond.' '.$fkchamp.' = "'.mes($idfrom,'int').'" ';
//				
//				DoSql($sql);
//				
//				
//			} else {
//				$sql = 'SELECT * FROM '.$table.' WHERE '.$fk_cond.' '.$fkchamp.' = "'.mes($idfrom,'int').'" ';
//				$res = GetAll($sql);
//				
//				foreach($res as $row) {	
//					$gr = new genRecord($table,$row[$pk]);
//					$gr->deleteRow($row[$pk]);
//				}
//			}
//		}
//		
//
//		$sql = 'SELECT * FROM '.$table.' WHERE  '.$fk_cond.' '.$fkchamp.' = "'.mes($idto,'int').'" ';
//		$res = GetAll($sql);
//		
//		foreach($res as $row) {
//			
//			$oldfiles = array();
//			$id = $row[$pk];
//			$sql = 'INSERT INTO '.$table.' ( ';
//			$values =  ' ( ';
//			//debug($tablerel[$table]);
//			
//			if(!is_array($tablerel[$table])) {
//				if($pk) {
//					$sql .= $pk.' , ';
//					
//					$values .= ' "" , ';
//				}
//			}
//			$sql .= '  '.$fkchamp.' ';
//			
//			$values .= '  "'.mes($idfrom,'int').'" ';
//			
//			while(list($k,$v) = each($row)) {
//				if(!@in_array($k,$this->noCopyField) && !is_int($k) && $k != $pk && $k != $fkchamp) {
//										
//					$sql .= ' , '.$k.' ';
//					
//					$values .= ' , '.getNullValue(($v),$k,$table).' ';
//					
//					 if ( arrayInWord( $uploadFields, $k ) ) {
//
//						$oldfile = new genFile($table,$k,$id,$v);
//						$oldfiles[] = array('path'=>$oldfile->getSystemPath(),'valeur'=>$v,'champ'=>$k);
//						
//						if(isDefaultLgField($k)) {
//						 	
//						 	$bch = fieldWithoutLg($k);
//						 	//debug($k.$bch);
//						 	$lgs = getLanguages($table,$id,$bch);
//						 	foreach($lgs as $lg) {
//						 		$va = getTradValue($table,$id,$bch,$lg);
//						 		$oldfile = new genFile($table,$bch.'_'.$lg,$id,$va);
//								$oldfiles[] = array('path'=>$oldfile->getSystemPath(),'valeur'=>$va,'champ'=>$bch.'_'.$lg);
//						 	}
//						 }
//
//					 }
//					 
//					 
//				}
//			}
//
//			$values .= ' ) ';
//
//			$sql .= ' ) VALUES '.$values;
//			$res = DoSql($sql);
//
//			$curNewId = InsertId();
//			
//			if($table != 's_traduction') {
//				
//				$this->deleteAndDupli('s_traduction', 'fk_id','fk_table = "'.$table.'" AND',$curNewId,$id);
//				
//			}
//
//			if(count($oldfiles)) {
//				//debug($oldfiles);
//				foreach($oldfiles as $oldfile) {
//					
//					if(strlen($oldfile['path']) && file_exists($oldfile['path']) && $oldfile['valeur']) {
//							$newfile =  new genFile($table,$oldfile['champ'],$curNewId,$oldfile['valeur']);
//							$newfile->uploadFile($oldfile['path']);
//					}
//
//				}
//			}
		//}
		
		
		



	//}	
	
}
class genActionValidate {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

	public function __construct ($action, $table, $id, $row = array()) {


		$this->noCopyField = array('rubrique_id','fk_rubrique_id','fk_rubrique_version_id','rubrique_etat','rubrique_ordre');
		$this->relTableToCopy = array('s_paragraphe');
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		$this->row = $row;

		if(!count($row)) {
			$this->row = getRowFromId($table,$id);
			 /*getRowFromId($gf = new GenForm($this->table,'',$this->id);
			$this->row = $gf->tab_default_field;*/
		}

		if(!$this->row['fk_rubrique_version_id']) {
			$sql = 'SELECT * FROM s_rubrique WHERE fk_rubrique_version_id = "'.$this->id.'"';
			$this->row = GetSingle($sql);
			$this->id = $this->row['rubrique_id'];
		}
		
		
		
	}

	public function checkCondition() {
		if(( $this->row['rubrique_etat'] == 'redaction' || $this->row['rubrique_etat'] == 'attente' ) && 		$this->row['fk_rubrique_version_id'] != 'NULL') {
			return true;
		} else {
			return false;
		}
	}

	public function doIt() {

		global $genMessages,$uploadFields,$_Gconfig;

		if(!$this->checkCondition()) {
			return 'error';
		}

		
		
	

		/* BORDEL DE DUPLICATION */
		$sql = 'UPDATE s_rubrique SET ';

		while(list($k,$v) = each($this->row)) {
			if(!in_array($k,$this->noCopyField) && !is_int($k)) {
				$sql .= ' '.$k.' = '.getNullValue(($v),$k,$this->table).' ,';
				
				if ( arrayInWord( $uploadFields, $k ) ) {

					$oldfile = new genFile($this->table,$k,$this->id,$v);
					$oldfiles[] = array('path'=>$oldfile->getSystemPath(),'valeur'=>$v,'champ'=>$k);
			    }
			}
		}
		
		if(count($oldfiles)) {
			foreach($oldfiles as $oldfile) {
				$newfile =  new genFile($this->table,$oldfile['champ'],$this->row['fk_rubrique_version_id'],$oldfile['valeur']);
				if(file_exists($oldfile['path'])) {
					//debug($oldfile);

					$newfile->uploadFile($oldfile['path']);
				}

			}
		}		
		
		unset($oldfiles);



		$sql .= ' rubrique_etat = "en_ligne" , rubrique_date_publi = NOW() ';

		$sql .= ' WHERE rubrique_id = "'.mes($this->row['fk_rubrique_version_id'],'int').'" ';
		DoSql($sql);


		$sql = 'UPDATE s_rubrique SET rubrique_etat = "redaction" WHERE rubrique_id = "'.$this->id.'"';
		DoSql($sql);

		$sql = 'UPDATE s_param SET param_valeur = UNIX_TIMESTAMP() WHERE param_id = "date_update_arbo" ';
		DoSql($sql);
		

		foreach($_Gconfig['duplicateWithRubrique'] as $v ) {
				$tabF = getTabField($v);
				if($tabF['fk_rubrique_id']) {
				//	debug($v);
					$this->deleteAndDupli($v);
				} else {
					global $relinv;
					foreach($relinv['s_rubrique'] as $fake=>$vvv) {
						if($vvv[0] == $v) {
							
							$this->deleteAndDupli($v,$vvv[1]);
						}
					}
				}
		}

		
		
		$this->deleteAndDupli('s_traduction', 'fk_id','fk_table = "s_rubrique" AND');

		//indexForSearch($this->row['fk_rubrique_version_id'],$this->row,array(),$this->table);
		
		$genMessages->add(t('rubrique_valider_ok'),'info');

		

	}

	function deleteAndDupli($table,$fkchamp = 'fk_rubrique_id',$fk_cond = '',$idfrom=0,$idto=0) {

	
		global $tablerel,$uploadFields,$relinv;

		$idfrom = $idfrom ? $idfrom : $this->row['fk_rubrique_version_id'];
		$idto = $idto ? $idto : $this->row['rubrique_id'];
			
		
		
		$pk = GetPrimaryKey($table);

		if($table == 's_traduction') {
			
			$sql = 'DELETE  FROM '.$table.' WHERE '.$fk_cond.' '.$fkchamp.' = "'.mes($idfrom,'int').'" ';
			$res = DoSql($sql);
			
			
		} else {
			
			if(ake($table,$tablerel)) {
				
				DoSql('DELETE FROM '.$table.' WHERE '.$fk_cond.' '.$fkchamp.' = "'.mes($idfrom,'int').'" ');
				
				
			} else {
				
				$sql = 'SELECT * FROM '.$table.' WHERE '.$fk_cond.' '.$fkchamp.' = "'.mes($idfrom,'int').'" ';
				$res = GetAll($sql);
				
				foreach($res as $row) {	
					$gr = new genRecord($table,$row[$pk]);
					$gr->deleteRow($row[$pk]);
				}
				
			}
		}
		
		
		$sql = 'SELECT * FROM '.$table.' WHERE  '.$fk_cond.' '.$fkchamp.' = "'.mes($idto,'int').'" ';
		$res = GetAll($sql);
		
		
		foreach($res as $row) {
			$oldfiles = array();
			$id = $row[$pk];
			$sql = 'INSERT INTO '.$table.' ( ';
			$values =  ' ( ';
			//debug($tablerel[$table]);

			if(!is_array($tablerel[$table])) {
				if($pk) {
					$sql .= $pk.' , ';
					
					$values .= ' "" , ';
				}
			}
			$sql .= '  '.$fkchamp.' ';
			
			$values .= '  "'.mes($idfrom,'int').'" ';
			while(list($k,$v) = each($row)) {
				if(!in_array($k,$this->noCopyField) && !is_int($k) && $k != $pk && $k != $fkchamp) {
					
					$sql .= ' , '.$k.' ';
					
					$values .= ' , '.getNullValue((($v)),$k,$table).' ';
					
					

					 if ( arrayInWord( $uploadFields, $k ) ) {

						$oldfile = new genFile($table,$k,$id,$v);
						$oldfiles[] = array('path'=>$oldfile->getSystemPath(),'valeur'=>$v,'champ'=>$k);
						
						if(isDefaultLgField($k)) {
						 	
						 	$bch = fieldWithoutLg($k);
						 	//debug($k.$bch);
						 	$lgs = getLanguages($table,$id,$bch);
						 	foreach($lgs as $lg) {
						 		$va = getTradValue($table,$id,$bch,$lg);
						 		$oldfile = new genFile($table,$bch.'_'.$lg,$id,$va);
								$oldfiles[] = array('path'=>$oldfile->getSystemPath(),'valeur'=>$va,'champ'=>$bch.'_'.$lg);
						 	}
						 }

					 }
					 
					 
				}
			}

			$values .= ' ) ';

			$sql .= ' ) VALUES '.$values;
			$res = DoSql($sql);

			$curNewId = InsertId();
			
			if(count($relinv[$table])) {
				
				foreach($relinv[$table] as $k => $v) {
					//debug($table.' '.$k);
					$this->deleteAndDupli($v[0],$v[1], '  ',$curNewId,$id);
					
				}
			}
			
			if($table != 's_traduction') {
				
				$this->deleteAndDupli('s_traduction', 'fk_id','fk_table = "'.$table.'" AND',$curNewId,$id);
				
			}

			if(count($oldfiles)) {
				//debug($oldfiles);
				foreach($oldfiles as $oldfile) {
					
					if(strlen($oldfile['path']) && file_exists($oldfile['path']) && $oldfile['valeur']) {
							$newfile =  new genFile($table,$oldfile['champ'],$curNewId,$oldfile['valeur']);
							$newfile->uploadFile($oldfile['path']);
					}

				}
			}
		}



	}

}


class genActionVoir_modifs {
	function doIt() {

	}

	function checkCondition() {
		return true;
	}
}

class genActionAsk_for_validation {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

	public function __construct ($action, $table, $id, $row = array()) {



		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		$this->row = $row;

		if(isRealRubrique($this->row)) {
			$this->version_row = getVersionForRubrique($this->id);
		} else {
			$this->version_row = $this->row;
			$this->row = getRealForRubrique($this->row);
		}
		if(!count($row)) {
			$gf = new GenForm($this->table,'',$this->id);
			$this->row = $gf->tab_default_field;
		}


	}

	public function checkCondition() {

		if( $this->version_row['rubrique_etat'] == 'redaction' ) {
			return true;
		} else {
			return false;
		}
	}

	public function doIt() {

		global $genMessages;

		if(!$this->checkCondition() && false) {
			return 'error';
		}
		/* BORDEL DE DUPLICATION */
		$sql = 'UPDATE s_rubrique SET ';


		$sql .= ' rubrique_etat = "attente" ';
		/*
		$genMessages->add(($this->row));
		$genMessages->add(getOnlineRubid($this->row));
		*/
		$sql .= ' WHERE fk_rubrique_version_id = "'.mes(getOnlineRubid($this->row),'int').'" ';
		DoSql($sql);


		//debug($this->row);
		$mails = $this->GetAdminMails(getOnlineRubid($this->row));

		//debug($mails);

		//$genMessages->add($mails);
		sendMails(
			$mails,
			t('mail_ask_validation'),
			array('id'=>$this->id,'titre'=>GetRubTitle($this->row),
			'url'=>GetRubUrl($this->row),
			'personne'=>GetCurrentLogin()));

		//debug($mails);
		foreach($mails as $curMail) {
			$genMessages->add(t('rubrique_demande_valider_ok').' '.$curMail['admin_nom'].' ('.$curMail['admin_email'].')','info');

		}
		//mail('celio@opixido.com','VALIDATION','VALIDATION '.$this->id);

	}

	private function GetAdminMails($rubid,$mails=array()) {

		global $adminTypesToMail,$onlyData;

		//debug("-->".$rubid);

		$sql = 'SELECT R.fk_rubrique_id , A.admin_nom, A.admin_email FROM s_rubrique AS R, s_admin AS A, r_admin_rubrique AS RA
				WHERE  RA.fk_admin_id = A.admin_id
				AND RA.fk_rubrique_id = R.rubrique_id
				AND A.admin_type IN ("'.implode('","',$adminTypesToMail).'")
				AND R.rubrique_id = '.mes($rubid).' ';

		$res = GetAll($sql);

		if(count($res) > 0) {
			foreach($res as $row) {
				$mails[] = $row;
			}
			$newrubid = $row['fk_rubrique_id'];

		} else {
			$sql = 'SELECT * FROM s_rubrique WHERE rubrique_id = "'.mes($rubid).'"';
			$row = GetSingle($sql);
			$newrubid = $row['fk_rubrique_id'];
		}

		if($newrubid != 'NULL' && $newrubid > 0) {
			$mails = $this->GetAdminMails($newrubid,$mails);
		}

		return $mails;

	}

}






class genActionUnvalidate {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;
	
	public function __construct ($action, $table, $id, $row = array()) {


		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		$this->row = $row;

		/* $row is real rubrique and $version_row is fake */

		if(isRealRubrique($this->row)) {
			$this->version_row = getVersionForRubrique($this->id);
		} else {
			$this->version_row = $this->row;
			$this->row = getRealForRubrique($this->row);
		}

		if(!count($row)) {
			$gf = new GenForm($this->table,'',$this->id);
			$this->row = $gf->tab_default_field;
		}


	}

	public function checkCondition() {

		$sql = 'SELECT rubrique_etat FROM s_rubrique WHERE rubrique_id = "'.GetOnlineRubId($this->row).'"';
		$row = GetSingle($sql);

		if( $row['rubrique_etat'] == 'en_ligne' ) {

			return true;
		} else {

			return false;
		}
	}

	public function doIt() {

		global $genMessages;

		if(!$this->checkCondition()) {
			$genMessages->add(t('rubrique_pas_en_ligne'),'error');
			return 'error';
		}
		/* BORDEL DE DUPLICATION */
		$sql = 'UPDATE s_rubrique SET ';

		$sql .= ' rubrique_etat = "redaction" ';

		$sql .= ' WHERE rubrique_id = "'.mes(GetOnlineRubId($this->row),'int').'" OR fk_rubrique_version_id = "'.mes(GetOnlineRubId($this->row),'int').'"';

		DoSql($sql);

		UpdateArboTime();


		$genMessages->add(t('rubrique_devalider_ok'),'info');

	}

}




class genActionRefuse {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

	public function __construct ($action, $table, $id, $row = array()) {


		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		$this->row = $row;

		/* $row is real rubrique and $version_row is fake */

		if(!count($row)) {
			$gf = new GenForm($this->table,'',$this->id);
			$this->row = $gf->tab_default_field;
		}

		if(isRealRubrique($this->row)) {
			$this->version_row = getVersionForRubrique($this->id);
		} else {
			$this->version_row = $this->row;
			$this->row = getRealForRubrique($this->row);
		}

		//debug($this->version_row);


	}

	public function checkCondition() {

		/*$sql = 'SELECT rubrique_etat FROM s_rubrique WHERE rubrique_id = "'.GetOnlineRubId($this->row).'"';
		$row = GetSingle($sql);
		*/

		if( $this->version_row['rubrique_etat'] == 'attente' ) {
			return true;
		} else {
			return false;
		}
	}

	public function doIt() {

		global $genMessages;

		if(!$this->checkCondition()) {
			$genMessages->add(t('rubrique_pas_en_attente'),'error');
			return 'error';
		}
		/* BORDEL DE DUPLICATION */
		$sql = 'UPDATE s_rubrique SET ';

		$sql .= ' rubrique_etat = "redaction" ';

		$sql .= ' WHERE fk_rubrique_version_id = "'.mes(GetOnlineRubId($this->row),'int').'"';

		DoSql($sql);

		$genMessages->add(t('rubrique_refuser_ok'),'info');

	}

}




class genActionTranslate {

	public $action;
	public $table;
	public $id;
	public $row;
	

	public function __construct ($action, $table, $id, $row = array()) {
		
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		$this->row = $row;
		
		
	}
	
	
	function checkCondition() {
		

		return true;
	}
	
	function doIt() {
		
		addTranslations($this->table,$this->id,$_REQUEST['translate_in']);
		
		dinfo(t('added_language').' '.$_REQUEST['translate_in']);
	}
	
	function getForm() {
		
		p('<label class="button" for="gen_actions_'.$action.'" >');
		//$this->genButton( 'genform_action['.$action.']', '1', " id='gen_actions_".$action."' class='inputimage'  type='image' src='".t('src_'.$action)."' border='0' title='".t($action)."' " );
		//p(t($action));
		$sql = 'SELECT * FROM s_langue ORDER BY langue_nom  ASC';
		$res = GetAll($sql);
		p(t('translate'));
		p('<select name="translate_in">');
			foreach($res as $row) {
				p('<option value="'.$row['langue_id'].'">'.$row['langue_nom'].'</option>')				;
			}
		p('</select>');
		p('<input type="submit" name="genform_action[translate]" value="'.t('go').'" />');
		p('</label>');
		
	}
	
}



/**********************************
 *
 *    GESTION DES PLUGINS
 *
 *********************************/   
 
 // INSTALLATION  
 class genActionInstallPlugin {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

  // CONSTRUCTEUR
	public function __construct ($action, $table, $id, $row = array()) {
		
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		$this->row = $row;
		
		if(!ake('plugin_installe',$this->row)) {
			$this->row = getRowFromId($table,$id);
		}
		
	}
	
	
	// CHECK CONDITION
	function checkCondition() {
		
		// on vérifie que le plugin n'est pas déj�  installé
		
		$ok = !$this->row['plugin_installe'];

		return $ok;
		

	}
	
	
	// ACTION DO IT
	function doIt() {
		  
	  // on appelle le fichier "install.php"
	  $filename = gen_include_path.'/plugins/'.$this->row['plugin_nom'].'/install.php';
	  if(file_exists($filename) && file_get_contents($filename) != '')
	      include($filename);
	  else 
	      dinfo('Fichier "install.php" inexistant ou vide.');

      // install BDD
      $filename = gen_include_path.'/plugins/'.$this->row['plugin_nom'].'/install.sql';
      
      if(file_exists($filename) && file_get_contents($filename) != '') {
      /*
          $sql = file_get_contents($filename);
         
          $sqls = explode(';'."\n",$sql);
          foreach($sqls as $sql) {
          	$sql = $sql .'';
          	if(strlen(trim($sql))) {
          		
          		$res = doSql($sql);
          		if($res)
          		dinfo('SQL executed : '."<br/>".$sql);
          		else
          		derror('SQL WRONG : '."<br/>".$sql);
          	}
          }
        */
      
      $quers = importSqlFile($filename);
      foreach($quers as $sql) {
      	if(!DoSql($sql)) {
      		  echo ("<p class=\"error\">Error at the line $linenumber: ". trim($dumpline)."</p>\n");
	          echo ("<p>Query: ".trim(nl2br(htmlentities($query)))."</p>\n");
	          echo ("<p>MySQL: ".mysql_error()."</p>\n");
	          debug($query);
      	}
      }
      
     
      recheckTranslations();

      dinfo('Fichier install.sql exécuté');
      }
      else  {
          dinfo('Fichier "install.sql" inexistant ou vide.');
      }
       $sql ='UPDATE s_plugin SET plugin_installe=1, plugin_actif = 1 WHERE plugin_nom='.sql($this->row['plugin_nom']);
	 $res = doSql($sql);

	 $_SESSION['cache'] = array();
	}
	
	
}


 // DESINTALLATION
 class genActionUninstallPlugin {

	public $action;
	public $table;
	public $id;
	public $row;
	public $canReturnToList = true;

  // CONSTRUCTEUR
	public function __construct ($action, $table, $id, $row = array()) {
		
		$this->action = $action;
		$this->table = $table;
		$this->id = $id;
		$this->row = $row;
		
		if(!ake('plugin_installe',$this->row)) {
			$this->row = getRowFromId($table,$id);
		}
		
	}
	
	
	// CHECK CONDITION
	function checkCondition() {
		
		// on vérifie que le plugin n'est pas déj�  installé
		$ok = $this->row['plugin_installe'];
	
		return $ok;
	}
	
	
	// ACTION DO IT
	function doIt() { 

		
      // on appelle le fichier "uninstall.php"
		  $filename = gen_include_path.'/plugins/'.$this->row['plugin_nom'].'/uninstall.php';
		  if(file_exists($filename) AND file_get_contents($filename) != '')
		      include($filename);
		  else 
		      dinfo('Fichier "uninstall.php" inexistant ou vide.');

      // install BDD
      $filename = gen_include_path.'/plugins/'.$this->row['plugin_nom'].'/uninstall.sql';
      if(file_exists($filename) AND file_get_contents($filename) != '') {
      /*
			$sql = file_get_contents($filename);
         
          $sqls = explode(';'."\n",$sql);
          foreach($sqls as $sql) {
          	$sql = $sql .'';
          	if(strlen(trim($sql))) {
          		dinfo('SQL executed : '."<br/>".$sql);
          		$res = doSql($sql);
          	}
          }
      	    */
	      $quers = importSqlFile($filename);
	      foreach($quers as $sql) {
	      	if(!DoSql($sql)) {
	      		  echo ("<p class=\"error\">Error at the line $linenumber: ". trim($dumpline)."</p>\n");
		          echo ("<p>Query: ".trim(nl2br(htmlentities($query)))."</p>\n");
		          echo ("<p>MySQL: ".mysql_error()."</p>\n");
		          debug($query);
	      	}
	      }
      	 dinfo('Fichier uninstall.sql exécuté');
        
      }
      else 
          dinfo('Fichier "uninstall.sql" inexistant ou vide.');
      
          $_SESSION['activePlugins'] = false;
          
      // si tout est ok, on passe le champ plugin_installe a TRUE
		  $sql ='UPDATE s_plugin SET plugin_installe=0 , plugin_actif = 0 WHERE plugin_nom='.sql($this->row['plugin_nom']);
		  $res = doSql($sql);
		  
	}
	
	
	
	
	
	
}


class genActionShowMV extends baseAction {

	function checkCondition () {
		return true;
	}
	
	function doIt() {
		
	}
	
	
	public function getForm() 
	{
		
		global $_Gconfig;
		
		$sql = 'SELECT * FROM '.$this->table.' WHERE 1 ';
		if($this->row[MULTIVERSION_FIELD]) {
			
			$mainId =$this->row[MULTIVERSION_FIELD];
		} else {
			$mainId = $this->id;
		}	
		
		$sql .= ' AND '.MULTIVERSION_FIELD.' = '.sql($mainId).' OR '.getPrimaryKey($this->table).' = '.sql($mainId);
		
		$res = GetAll($sql);
		
		
		p('<div class="bloc">');
		
		echo '<div class="bloc">'.t('mv_version_'.$this->row[MULTIVERSION_STATE]).'</div>';
		
		p('<table ><tr><th colspan="2">'.t('mv_versions').'</th></tr>');
		
		foreach ($res as $k=>$row) {
			
			if($row[getPrimaryKey($this->table)] != $this->id) {
				p('<tr class="row'.($k%2).'"><td>');				
			} else {
				p('<tr class="selected"><td>');
			}
		
			p( '<a href="?curTable='.$this->table.'&amp;curId='.$row[getPrimaryKey($this->table)].'&amp;resume=1">'.GetTitleFromRow($this->table,$row));
			if($row[MULTIVERSION_FIELDNAME]) {
				p('<br/><span class="light">'.$row[MULTIVERSION_FIELDNAME].'</span>');
			}else {
				p('<br/><span class="light">'.$row[$_Gconfig['field_date_crea']].'</span>');
			}
			p('</a>');
			//p('<td>'.t('enum_'.$row[MULTIVERSION_STATE]).'</td>');
			p('<td><img src="'.t('src_enum_'.$row[MULTIVERSION_STATE]).'" alt='.alt(t('enum_'.$row[MULTIVERSION_STATE])).' /></td>');
			p('</td></tr>');	
		
		}		
		
		p('	</table></div>');
		
	}
	
	
	function getSmallForm() {
		
	}
}



class genActionDuplicateMV extends baseAction {

	function checkCondition () {
		return true;
	}
	
	function doIt() {
		
		$od = new objDuplication($this->table,$this->id,$this->row);
		$newId = $od->duplicateTo('new');
		
		$record = array();
		$record[MULTIVERSION_FIELD] = $this->row[MULTIVERSION_FIELD] ? $this->row[MULTIVERSION_FIELD] : $this->id;
		$record[MULTIVERSION_STATE] = 'brouillon';
		$record[MULTIVERSION_FIELDNAME] = $_REQUEST[MULTIVERSION_FIELDNAME];
		
		global $co;
		($co->AutoExecute($this->table,$record,'UPDATE',' '.getPrimaryKey($this->table).' = '.sql($newId)));		
		
	}
	
	
	function getSmallForm() {
		
	}	
	
	function oldgetForm() {
		
		p('<a href="#" 
				onclick="AI = prompt(\''.t('mv_confirm_duplicate').'\',\''.str_replace("'",'`',GetTitleFromRow($this->table,$this->row)).'\'); if(AI)
					{ window.location=
						\'index.php?curTable='.$this->table.'&curId='.$this->id.'&genform_action[duplicateMV]=1&'.MULTIVERSION_FIELDNAME.'=\'+escape(AI);
						return false;
					}
					 else {
					 	return false;
					}"
				class="abutton" >
				<img src="'.t('src_duplicateMV').'" alt="" /> '.t('duplicateMV').'</a>');
		
		p('<div class="bloc2">
				<label>'.t('mv_confirm_duplicate').'</label>
				<textarea style="width:240px;border:1px solid;"></textarea><br/>
				<a href="" class="button">'.t($this->action).'</a>
				</div>');
		
	}
	
}


class genActionValidateMV extends baseAction {

	function checkCondition () {
		if($this->row[MULTIVERSION_STATE] == 'brouillon') {
			return true;
		} else {
			return false;
		}
	}
	
	function doIt() {
		
		$sql = 'UPDATE '.$this->table.' 
					SET '.MULTIVERSION_STATE.' = "publiable" 
					WHERE '.getPrimaryKey($this->table).' = '.sql($this->id);
		
		DoSql($sql);
	}
	
	
	function getSmallForm() {
		
	}
		
}


class genActionUnvalidateMV extends baseAction {

	function checkCondition () {
		if($this->row[MULTIVERSION_STATE] == 'publiable') {
			return true;
		} else {
			return false;
		}
	}
	
	function doIt() {
		
		$sql = 'UPDATE '.$this->table.' 
					SET '.MULTIVERSION_STATE.' = "brouillon" 
					WHERE '.getPrimaryKey($this->table).' = '.sql($this->id);
		
		DoSql($sql);
	}
	
	
	function getSmallForm() {
		
	}	
	
}


class genActionPublishMV extends baseAction {

	function checkCondition () {
		if($this->row[MULTIVERSION_STATE] == 'publiable') {
			return true;
		} else {
			return false;
		}
	}
	
	function doIt() {
		
		
		$sql = 'UPDATE '.$this->table.'
					 SET '.MULTIVERSION_STATE.' = "publiable"
					 WHERE ocms_version = '.sql($this->row['ocms_version']).' 
					 AND '.MULTIVERSION_STATE.' = "en_ligne"';
		
		DoSql($sql);
		
		$sql = 'UPDATE '.$this->table.' 
					SET '.MULTIVERSION_STATE.' = "en_ligne" 
					WHERE '.getPrimaryKey($this->table).' = '.sql($this->id);
		
		DoSql($sql);
	}
	
	
	function getSmallForm() {
		
	}	
	
}


class genActionUnpublishMV extends baseAction {

	function checkCondition () {
		if($this->row[MULTIVERSION_STATE] == 'en_ligne') {
			return true;
		} else {
			return false;
		}
	}
	
	function doIt() {
		
		
		$sql = 'UPDATE '.$this->table.' 
					SET '.MULTIVERSION_STATE.' = "publiable" 
					WHERE '.getPrimaryKey($this->table).' = '.sql($this->id);
		
		DoSql($sql);
	}
	
	
	function getSmallForm() {
		
	}	
	
}


class genActionDelete extends baseAction {

	function checkCondition () {
		return true;
	}
	
	function doIt() {
		
	}
	
	function getSmallForm() {
		
	}	
	
}

class genActionDeleteMv extends baseAction {

	function checkCondition () {
		return true;
	}
	
	function doIt() {
		
	}
	
}

?>