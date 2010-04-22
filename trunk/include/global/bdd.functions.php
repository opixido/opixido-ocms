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




class tablerel {
	
	
	public $table_relation;
	public $table_courante;
	public $id_courant;
	public $fk_courant;
	public $table_distante;
	public $fk_distant;
	
	
	/**
	 * Nouvelle table de relation
	 *
	 * @param string $table_relation Nom de la table de relation
	 * @param string $table_courante Nom de la table courante 
	 * @param string $id_courant identifiant de la table courante
	 */
	function __construct($table_relation,$table_courante,$id_courant=0) {

		global $tablerel,$tablerel_reverse,$tablerel_fks;
		
		$this->table_relation = $table_relation;;
		
		$this->table_courante = $table_courante;		 
		$this->id_courant = $id_courant;	
		
		genTableRelReverse();

		$this->fk_courant = $tablerel_fks[$this->table_courante][$this->table_relation];

		list($this->table_distante,$this->fk_distant) = $this->getOtherTable();
		
	}
	
	
	
	/**
	 * Enregistre les modifications pour l'ID courant
	 * avec les valeurs $values
	 * 
	 * @example $values = array(10,25,18084);
	 *
	 * @param array $values
	 */
	function record($values) {		
		
		if($this->id_courant && $this->fk_courant) {

			DoSql('DELETE FROM '.$this->table_relation.' WHERE '.$this->fk_courant.' = '.sql($this->id_courant));	
		
			foreach( $values as $v ) {
				$sql = 'INSERT INTO '.$this->table_relation.' 
							('.$this->fk_courant.','.$this->fk_distant.')
							VALUES
							('.sql($this->id_courant).','.sql($v).')';
				$res =  DoSql($sql);			
			}
		}		
	}
	
	/**
	 * Retourne pour une table de relation la table distante qui n'est pas celle depuis laquelle on cherche ...
	 *
	 * @param string $relname
	 * @param string $tablesource
	 * @return array ($table,$fk_champ)
	 */
	function getOtherTable() {
		
		global $tablerel;
		
		if(is_array($tablerel[$this->table_relation])) {
			foreach($tablerel[$this->table_relation] as $k=>$v) {
				if($v != $this->table_courante) {
					return array($v,$k);
				}
			}
		}
		return false;
	
	}
	
	/**
	 * Retourne la liste complète des enregistrements
	 *
	 * @return unknown
	 */
	function getFullListing() {
		
		global $tabForms;
		//sqlLgValue($tabForms[$this->table_distante]['titre'][0]
		$sql = 'SELECT '.sqlLgTitle($this->table_distante).' AS label, 					
						'.getPrimaryKey($this->table_distante).' AS value FROM '.$this->table_distante.' ORDER BY label';
					
		$res = GetAll($sql);
		
		return $res;
		
	}
	
	function getSelectedIds() {
		
		global $tabForms,$co;
		
		$sql = 'SELECT '.$this->fk_distant.','.$this->fk_distant.' AS VAL
				FROM '.$this->table_relation.' 
				WHERE '.$this->fk_courant.' = '.sql($this->id_courant);
					
		$res = ($co->getAssoc($sql));

		return $res;
	}
	
	
	function getSelectedListing() {
		
		
	}

	function getUnselectedListing() {
		
		
	}
	
}

/**
 * On reformate le table $tablerel en $tablerel_reverse qui est parfois pas pratique DU TOUT
 */
function genTableRelReverse() {
	global $tablerel,$tablerel_reverse,$tablerel_fks;
		if(is_array($tablerel)) {
		$tablerel_reverse = array();
		foreach($tablerel as $k=>$v) {
			
			foreach($v as $kk=>$vv) {
				$tablerel_reverse[$vv][] = array('tablerel'=>$k,'myfk'=>$kk);
				$tablerel_fks[$vv][$k] =$kk;
			}
		}
	}
}


/**
 * Retourne pour une table de relation la table distante qui n'est pas celle depuis laquelle on cherche ...
 *
 * @param string $relname
 * @param string $tablesource
 * @return array ($table,$fk_champ)
 */
function getOtherTablerel($relname,$tablesource) {
	
	$t = new tablerel($relname,$tablesource);
	return $t->getOtherTable();
	
}


function getRowAndRelFromId($table,$id) {
	
	global $getRowFromId_cacheRow,$relations,$_Gconfig;
	if(!is_array($getRowFromId_cacheRow)) {
		$getRowFromId_cacheRow = array();
	}
	
	
	
    if(!array_key_exists($table."_-REL_".$id,$getRowFromId_cacheRow) || !$getRowFromId_cacheRow[$table."_-REL_".$id]) {
    	
    	$pk = in_array($table,$_Gconfig['multiVersionTable']) ? ' ocms_etat = "en_ligne" AND ocms_version' : GetPrimaryKey($table);
    	
        $sql = 'SELECT * FROM '.$table.' AS MT ';
        $where = ' WHERE MT.'.$pk.' = "'.mes($id).'" ';
        
       // debug($where);
        if(is_array($relations[$table])) {
	        foreach($relations[$table] as $k=>$v) {
	        	if($v != $table) {
	        		$sql .= ' LEFT JOIN '.$v.' AS T'.$k.' ON T'.$k.'.'.getPrimaryKey($v).' = MT.'.$k.'  ';
	        	}
	        	//$where .= ' AND '.$table.'.'.$k.' = '.$v.'.'.getPrimaryKey($v);
	        }
        }


        $row = GetSingle($sql.$where);

        //debug($sql.$where);
	
        $getRowFromId_cacheRow[$table."_-REL_".$id] = $row;
    }
    
    return $getRowFromId_cacheRow[$table."_-REL_".$id];
    
}




function formatSqlCode($sql) {
        $words = array('SELECT', 'FROM', 'WHERE', 'AND', 'OR', 'ORDER BY', 'GROUP BY', 'UNION', 'DESC', 'ASC',",");
        $sql = str_replace("\n"," ",$sql);
        $sql = str_replace("\r"," ",$sql);
        $sql = str_replace("\t"," ",$sql);
        $sql = str_replace("  "," ",$sql);
        $sql ="<div style='padding:5px;margin:1px;border:1px solid #000;background-color:#eee;color:#0000cc;font-weight:bold;'><pre>".$sql;
        foreach($words as $word){
                $nsql = eregi_replace(" ".$word." ","\n<font color='#cc0000'><b> ".$word."</b></font>"."\n\t",$sql);
                if($nsql == $sql) {
                        $nsql = eregi_replace($word." ","\n<font color='#cc0000'><b> ".$word."</b></font>"."\n\t",$sql);
                }
                $sql = $nsql;
        }
        $sql .= "</pre></div>";

        print($sql);

}


function isLoggedIn() {
return false;
	if(is_object($GLOBALS['gs_obj']))
		if($GLOBALS['gs_obj']->isLogged())
			return true;
	return false;
}




function sqlRubriqueOnlyOnline($alias = '') {

	$sql = ' AND ';
	if( strlen($alias )) {
		$alias = $alias.'.';
		$sql .= $alias;		
	}

	/*if(isLoggedIn())
		$sql 	.= 'rubrique_etat != "AZ09" ';
	else
	*/
	$sql .= 'rubrique_etat = "en_ligne" AND '.$alias.'rubrique_type NOT IN ("'.RTYPE_MENUROOT.'")  ';
	return $sql;

}


function sqlMenuOnlyOnline($alias = '') {

	$sql = ' AND ';
	if( strlen($alias )) {
		$alias = $alias.'.';
		$sql .= $alias;		
	}

	if(isLoggedIn())
		$sql 	.= 'rubrique_etat != "AZ09" ';
	else
				$sql .= 'rubrique_etat = "en_ligne" AND '.$alias.'rubrique_type IN ("'.RTYPE_MENUROOT.'") ';
	return $sql;

}



function isRealRubrique($row) {
	if($row['fk_rubrique_version_id'] != 'NULL' && $row['fk_rubrique_version_id'] != '') {
		return false;
	}
	return true;
}



function isVersionRubrique($row) {
	if($row['fk_rubrique_version_id'] != 'NULL' && $row['fk_rubrique_version_id'] != '') {
		return true;
	}
	return false;
}

function isRubriqueOnline($roworid) {
	if(!is_array($roworid)) {
		$row = GetRowFromId('s_rubrique',$roworid);

	} else {
		$row = $roworid;
	}

	if(!isRealRubrique($row)) {
		$row = getRealForRubrique($row);
	}
	return $row['rubrique_etat'] == 'en_ligne' ? true : false;
}


function isRubriqueRealAndOnline($roworid) {
		if(!is_array($roworid)) {
		$row = GetRowFromId('s_rubrique',$roworid);

	} else {
		$row = $roworid;
	}

	if(!isRealRubrique($row)) {
		return false;
	}
	if(!in_array($row['rubrique_type'],array("folder","page","link","siteroot")))
		return false;
	return $row['rubrique_etat'] == 'en_ligne' ? true : false;	
}


function sqlRubriqueOnlyReal($alias = "") {
	return sqlRubriqueChoix('NULL',$alias);
}

function sqlRubriqueVersions($id,$alias='') {
	return sqlRubriqueChoix($id,$alias);
}

function getVersionForRubrique($roworid) {
	if(!is_array($roworid)) {
		$row = GetRowFromId('s_rubrique',$roworid);
	} else {
		$row = $roworid;
	}

	if(!isVersionRubrique($row)){
		$sql = 'SELECT * FROM s_rubrique WHERE fk_rubrique_version_id = "'.$row['rubrique_id'].'"';
		return GetSingle($sql);
	} else {
		return $row;
	}
}

function getRealForRubrique($roworid) {
	if(!is_array($roworid)) {
		$row = GetRowFromId('s_rubrique',$roworid);
	} else {
		$row = $roworid;
	}

	if(!isRealRubrique($row)){
		$sql = 'SELECT * FROM s_rubrique WHERE rubrique_id = "'.$row['fk_rubrique_version_id'].'"';
		return GetSingle($sql);
	}else {
		return $row;
	}
}


function UpdateArboTime() {
	$sql ='UPDATE s_param SET param_valeur = "'.time().'" WHERE param_id = "date_update_arbo"';
	return DoSql($sql,'Mise a jour de la date de modification de l\'arborescence');
}



function sqlRubriqueOnlyVersions($alias='') {
	return sqlRubriqueChoix('NOT NULL',$alias);
}

$GLOBALS['actionSaved'] = array();

function logAction($action, $table ,$id) {
	global $gs_obj;
	
	if(!in_array($action,$GLOBALS['actionSaved'])) {
		$sql = 'INSERT INTO s_log_action (fk_admin_id, log_action_table, log_action_fk_id, log_action_action, log_action_time)
				VALUES ("'.$gs_obj->adminid.'","'.$table.'","'.$id.'","'.$action.'",NOW()) ';
		$GLOBALS['actionSaved'][] = $action;
		return DoSql($sql,'Erreur Mise a jour du Log');
	}
}


function sqlRubriqueChoix($id,$alias='') {

	$sql = ' AND ';
	if( strlen($alias )) {
		$sql .= $alias.'.';
	}
	$sql .= 'fk_rubrique_version_id '.sqlParam($id);
	return $sql;

}



function getLgFieldsLike($field,$val) {
	global $languages;
	$sql = '';
	foreach($languages as $lg) {
		$sql .= ' OR '.$field.'_'.$lg.' LIKE "'.mes($val).'" ';
	}
	//lexique_mot_'.$this->site->getLg().' LIKE "'.mes($curMot).'" OR lexique_mot_'.$this->site->g_url->otherLg().'
	return $sql;
}
function updateRubriqueState($state,$rubid) {



}

function sqlError($sql,$msg='') {
        global $co;
        if(strlen($msg)) {
        	debug('Impossible d\'effectuer l\'action : '.$msg);
        }
        debug('Erreur SQL : '."\n".$co->ErrorMsg());
        //debug( debug_backtrace());
   		//trigger_error($co->ErrorMsg().' : '.$sql);
   		//debug($co->ErrorMsg());
   		debug($sql);
        //formatSqlCode($sql);
}



function getNullValue($val,$field,$table) {

	if(!strlen($val) ) {
		$t = getTabField($table);
		if(!$t[$field]->not_null ) {
			/*debug($field);
			debug($t[$field]);*/
			return 'NULL';
		}
	}
	return sql($val);

}


/**
 * On reformate le table $tablerel en $tablerel_reverse qui est parfois pas pratique DU TOUT
 */
global $tablerel,$tablerel_reverse;
if(is_array($tablerel)) {
foreach($tablerel as $k=>$v) {
	foreach($v as $kk=>$vv) {
		$tablerel_reverse[$vv][] = array('tablerel'=>$k,'myfk'=>$kk);
	}
}
}


/**
 * Returns the tablerel association
 * in an array :
 *  ['champ'] = fk to other table
 *  ['table'] = name of other table
 *  ['mychamp'] = fk to my table
 * 
 * @param unknown_type $tablerela
 * @param unknown_type $curtable
 */
function tablerelGetOtherTable($tablerela, $curtable) {
	
	global $tablerel;
	$ar = array();
	foreach($tablerel[$tablerela] as $k => $v) {
		if($v != $curtable) {
			$ar['champ'] = $k;
			$ar['table']=$v;
		}
		else {
			$ar['mychamp'] = $k;
		}
	}
	return $ar;
}

/**
 * Retourne la liste des champs de langue d'un champ donné
 * pour insérer dans une requete SQL
 * 
 * @example rubrique_titre => rubrique_titre_fr, rubrique_titre_en, ...
 *
 * @param unknown_type $champ
 * @param unknown_type $alias
 * @return unknown
 */
function sqlLgField($champ,$alias = '') {
	global $_Gconfig;
	if($alias != '') {
		$alias .= '.';
	}
	
	foreach($_Gconfig['LANGUAGES'] as $lg) {
		$str .= ' , '.$champ.'_'.$lg.' ';
	}
	
	return $str;
	
}



function getGabaritVisibility($gabid) {
	
		$rgab = getGabarit($gabid);
		$GLOBALS['gb_obj']->includeFile($rgab['gabarit_classe'].'.php','bdd');
		$gab = $rgab['gabarit_classe'];
		$gab = new $gab($GLOBALS['site']);
		return $gab->genvisibility();
}

function getGabaritSubRubs($rub,$gabid) {
	
		$rgab = getGabarit($gabid);
		
		$folder = $rgab['gabarit_plugin'] ? path_concat(PLUGINS_FOLDER,$rgab['gabarit_plugin']) : 'bdd';
		$GLOBALS['gb_obj']->includeFile($rgab['gabarit_classe'].'.php',$folder);
		$gabNom = $rgab['gabarit_classe'];
		if(method_exists($gabNom,'ocms_getSubRubs')) {
			$r = call_user_method('ocms_getSubRubs',$gabNom,$rub);
			
		}
		return $r;
}


function getEnumValues($table,$champ) {
	$sql = 'SHOW COLUMNS FROM '.$table.' LIKE "'.$champ.'"';
	$row = GetSingle($sql);
	
	$enum = str_replace(array('enum(','\'','"',')'),'',$row['Type']);
	$enums = explode(',',$enum);
	return $enums;							
}



function getSetValues($table,$champ) {
	
	$tab = getTabField($table);
	
	$set = explode(",",substr($tab[$champ]->type,4,-1));
	foreach($set as $k=>$v) {
		$set[$k] = substr($v,1,-1);
	}
	
	return $set;
	
}


