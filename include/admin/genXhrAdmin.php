<?php


/***********************
  *
  *   Popup d'administration via le front office
  *
  **********************/



  
class genXhrAdmin {


    function __construct($table,$id) {

        $this->table = $table;
        $this->id = $id;

		$this->gs = $GLOBALS['gs_obj'];
		
		if(!$this->gs->isLogged()) {
			die();			
		}
		
        $this->field = strstr($_REQUEST['field'],"_-_") ? explode("_-_",$_REQUEST['field']) : $_REQUEST['field'];
        if(!$this->field) {
        	$this->field = $_SESSION['lastUsedField'];
        } else {
        	$_SESSION['lastUsedField'] = $this->field;
        }

        $this->LoadPlugins();
    }

	
	/**
	 * LoadPlugins
	 *
	 * @return unknown
	 */
	function LoadPlugins() {
		
		$plugs = GetPlugins();
		
		foreach($plugs as $v ) {
			
			$GLOBALS['gb_obj']->includeFile('admin.php',PLUGINS_FOLDER.''.$v.'/');
			$adminClassName = $v.'Admin';
			if(class_exists($adminClassName)) {
				
				$this->plugins[$v] = new $adminClassName($this);
			}
			
		}
		
	}

    /*
        Dispatcher des actions
    */
    function gen() {


    		switch($_REQUEST['xhr']) {
    			
    			case 'tablerel':
    				$this->searchTableRel();
    				
    				break;
    			case 'searchRelation':
    				$this->searchRelation();
    				
    				break;    				
    			case 'arbo':
    				
    				$this->getArboRubs();
    				
    				break;
    				
    			case 'links':
    				$this->getLinks();
    				
    				break;
    				
    			case 'reallink':
    				$this->getRealLink();
    				break;
    			
    			case 'editTrad':
    				$this->editTrad();
    				break;
    				
    			case 'gfa':
    				$this->gfa();
    				die();
    				break;

    			case 'ajaxRelinv':
    				$this->ajaxRelinv();
    				break;
    			case 'ajaxForm':
    				$this->ajaxForm();
    				break;  
    				
    			case 'ajaxAction':
    				$this->ajaxAction();
    				break;  
    				
    			case 'del404':
    				del404();
    				
    			case 'reorderRelinv';
    				$this->reorderRelinv();
    				
    			case 'autocompletesearch':
    				$this->autocompletesearch();
    				break;
    				
    				
    		}
    	
    }
    
    function autocompletesearch() {

		$x = array('query'=>$_REQUEST['query'],'suggestions'=>array(),'data'=>array());

		global $tabForms;
		if(!$tabForms[$_REQUEST['table']]) {
			die();
		}
		
		$sql = 'SELECT * FROM '.$_REQUEST['table'].' 
					WHERE '.$_REQUEST['champ'].' 
					LIKE '.sql('%'.$_REQUEST['query'].'%').'';
		$res = GetAll($sql);
		
		
    
		$add = true;
		if(strpos(getTitleFromtable($_REQUEST['table']),$_REQUEST['champ'])) {
			$add = false;
		}
			
		$pk = getPrimaryKey($_REQUEST['table']);
		
		
		/**
		 * Formatage pour JSON
		 */
		foreach($res as $row) {
			if(true) {
				$x['suggestions'][] =limitwords(strip_tags($row[$_REQUEST['champ']]));
			}else
			if($add) {
				$x['suggestions'][] = limitwords(strip_tags($row[$_REQUEST['champ']].' - '.GetTitleFromRow($_REQUEST['table'],$row,' - ')),50);
			} else {
				$x['suggestions'][] = limitwords(strip_tags(GetTitleFromRow($_REQUEST['table'],$row,' - ')),50);
			}
			$x['data'][] = $row[$pk];
		}
		
		/**
		 * Retour
		 */
		echo json_encode($x);
		die();			
    }
    
    function ajaxAction() {
    	
    	$action = $_REQUEST['action'];
    	$id = $_REQUEST['id'];
    	$table = $_REQUEST['table'];
    	$params = unserialize($_REQUEST['params']);
    	
    	
    	
    	if($GLOBALS['gs_obj']->can($action,$_REQUEST['table'],$_REQUEST['id'])) {
    		
    		if($action == 'goup') {
    			$row =getRowFromId($_REQUEST['table'],$_REQUEST['id']);
    			$fkC = $row[$params['vfk1']] ? $params['vfk1'] : $params['vfk2'];
    			$o = new GenOrder($_REQUEST['table'],$_REQUEST['id'],$row[$fkC],$fkC);
    			$o->GetUp();
    			
    		} else  if($action == 'godown') {    			
    			
    			$row =getRowFromId($_REQUEST['table'],$_REQUEST['id']);
    			$fkC = $row[$params['vfk1']] ? $params['vfk1'] : $params['vfk2'];
    			echo 'Descend '.$_REQUEST['table'].' - '.$_REQUEST['id'].' - '.$fkC;
    			
    			$o = new GenOrder($_REQUEST['table'],$_REQUEST['id'],$row[$fkC],$fkC);
    			
    			$o->GetDown();
    			
    		}
    		if($action == 'add') {
    			
    			/*print_r($_REQUEST);
    			print_r(unserialize($_REQUEST['params']));
    			*/
    			
    			$xfk = $id ? $params['vfk2'] : $params['vfk1'];
    			$id = $id ? $id : $params['id'];
    			
    			
    			$sql = 'SELECT MAX('.$params['order'].') AS MAXI FROM '.$table.' WHERE '.$xfk.' = '.sql($id);
    			$row = GetSingle($sql);
    			
    			$record[$xfk] = $id;
    			$record[$params['order']] = $row['MAXI']+1;
    			
    			global $co;    			
    			DoSqL($co->getInsertSql($table,$record));
    			
    			$ide = InsertId();
    			$GLOBALS['gb_obj']->includeFile('genform.fullarbo.php','admin/genform_modules/');
    			
    			$row = getRowFromId($table,$ide);
    			
    			global $_Gconfig;
    			
    			$fa = new fullArbo($params['table'],$params['id'],$_Gconfig['fullArbo'][$params['table']][$params['field']],$params['field']);
    			
    			$fa->html ='';
    			$fa->getLine($row,false);
    			
    			echo $fa->html;
    			
    		} else if($action == 'del') {
    			
    			$gr = new genRecord($table,$id);
    			$gr->DeleteRow($id);
    			
    		}
    		else if($action == 'reorderRelinv') {
				print_r($params);
				foreach($params['order'] as $k=>$v) {
					$sql = ('UPDATE '.$table.' SET '.$params['relinv'].' = '.sql($k+1).' WHERE '.getPrimaryKey($table).' = '.sql($v));
				
					Dosql($sql);
					
				}
    			die();
    		}
    		
    	}
    	
    }
    
    function ajaxForm() {
    	
    	if($_REQUEST['upload']) {
    		echo 'UPLOAD';
    		print_r($_REQUEST);
    		print_r($_FILES);
    	} else 
    	if( ake($_REQUEST,'save')  && $_REQUEST['champ'] && $_REQUEST['id'] && $_REQUEST['table']) {
    		
    		if($GLOBALS['gs_obj']->can('edit', $_REQUEST['table'],$_REQUEST['id'],$_REQUEST['champ'])) {
    		
	    		echo DoSql('UPDATE '.$_REQUEST['table'].' 
	    						SET '.$_REQUEST['champ']. ' = '.sql($_REQUEST['save']).' 
	    					WHERE '.getPrimaryKey($_REQUEST['table']).' = '.$_REQUEST['id']);	    	
	    		
    		}
    		
    	}
    }
    
    function ajaxRelinv() {
    	
    	if($_REQUEST['save'] && $_REQUEST['field'] && $_REQUEST['id'] && $_REQUEST['table']) {
    		
    		if($GLOBALS['gs_obj']->can('edit', $_REQUEST['table'],$_REQUEST['id'],$_REQUEST['field'])) {
    		
	    		echo DoSql('UPDATE '.$_REQUEST['table'].' SET '.$_REQUEST['field']. ' = '.sql($_REQUEST['save']).' 
	    					WHERE '.getPrimaryKey($_REQUEST['table']).' = '.$_REQUEST['id']);
	    	
	    		
    		}
    		
    	} else if($_REQUEST['fake']) {
    		
    		if($GLOBALS['gs_obj']->can('edit', $_REQUEST['table'],$_REQUEST['id'],$_REQUEST['field'])) {
    			  		
	    		global $_Gconfig,$orderFields;
	    		
	    		//$GLOBALS['gb_obj']->includeFile('genform.ajaxRelinv.php','admin/genform_modules');
	    		include($GLOBALS['gb_obj']->getIncludePath('genform.ajaxrelinv.php' , 'admin/genform_modules'));
	    		
	    		$vals = $_Gconfig['ajaxRelinv'][$_REQUEST['table']][$_REQUEST['fake']];
	    		/*print_r($_Gconfig['ajaxRelinv']);
	    		print_r($vals);*/
	    		//die();
				$a = new ajaxRelinv($_REQUEST['table'],$_REQUEST['id'],$vals[0],$vals[1],$_REQUEST['fake']);

				$sqlInsert = 'INSERT INTO '.$vals[0].' ('.getPrimaryKey($vals[0]).' , '.$vals[1].') VALUES ("",'.sql($_REQUEST['id']).')';
				//echo $sqlInsert;
				DoSql($sqlInsert);
				//echo mysql_error();
				$id = InsertId();
				
				
				if(!$_REQUEST['id'] || $_REQUEST['id'] == 'new') {
					$_SESSION['sqlWaitingForInsert'][] = 'UPDATE '.$vals[0].' SET '.$vals[1].' = [INSERTID] WHERE '.getPrimaryKey($vals[0]).' = '.sql($id);
				}
				if($orderFields[$vals[0]] && $orderFields[$vals[0]][1] == $vals[1] ) {
					$clefEx = $orderFields[$vals[0]][1];
					$champOrdre = $orderFields[$vals[0]][0];
					$r = getSingle('SELECT MAX('.$champOrdre.') AS MAXX FROM '.$vals[0].' WHERE '.$clefEx.' = '.sql($_REQUEST['id']));
					$maxx = $r['MAXX'] + 1;
					//echo $maxx;
					//echo ' : '.
					DoSql('UPDATE '.$vals[0].' SET '.$champOrdre.' = '.$maxx.' WHERE '.getPrimaryKey($vals[0]).' = '.sql($id));
					
				}
				
				$row = getRowFromId($vals[0],$id);
				
				echo $a->getLine($row,$vals[2]);
				
			}
			
    	} else if($_REQUEST['delete']) {
    		if($GLOBALS['gs_obj']->can('delete', $_REQUEST['table'],$_REQUEST['delete'])) {
    			
    			$gr = new genRecord($_REQUEST['table'],$_REQUEST['delete']);
    			echo $gr->DeleteRow($_REQUEST['delete']);
    			
    			//echo DoSql('DELETE FROM '.$_REQUEST['table'].' WHERE '.getPrimaryKey($_REQUEST['table']). ' = '.sql($_REQUEST['delete']));
    			
    		}
    	}
    	
    	die();
    	
    }
	
    function gfa() {
    	
    	$champ = $_REQUEST['field'];
    	echo '<input type="text" class="gfa_input" value="" />';
    	
    }
    
    
    function editTrad() {
    	
    	$_REQUEST['nom'] = str_replace('ET_','',$_REQUEST['nom']);
    	$s = str_replace(str_replace(ADMIN_URL,"",ADMIN_PICTOS_FOLDER),'[ADMIN_PICTOS_FOLDER]',$_REQUEST['valeur']);
		DoSql('REPLACE INTO s_admin_trad (admin_trad_id,admin_trad_'.LG_DEF.') VALUES ("'.$_REQUEST['nom'].'",'.sql($s).')');
    	  
		print_r($_REQUEST);
    }
    
    
    
    function getRealLink() {
    	$id = $_GET['id'];
    	
    	$site = new GenSite();
		$site->initLight();
		
		print path_concat(WEB_URL,$site->g_url->buildUrlFromId($id));
    	    	
    }
    
    function searchTableRel() {
    	
    	global $tablerel,$_Gconfig;

		$tables = array_values($tablerel[$_REQUEST['champ']]);
		$rev = array_flip($tablerel[$_REQUEST['champ']]);
		$fk_table = $tables[0] == $_REQUEST['curTable'] ? $tables[1] : $tables[0];
		$fk_pk = $rev[$fk_table];
		
		if($this->gd)
		
		$sql = 'SELECT '.$fk_pk.'  
					FROM '.mes($_REQUEST['champ']).' 
					WHERE '.mes($rev[$_REQUEST['curTable']]).' = "'.mes($_REQUEST['curId']).'"
					
					';
		if($_Gconfig['specialListingWhere'][$_REQUEST['champ']]) {
			$sql .= $_Gconfig['specialListingWhere'][$_REQUEST['champ']]($_REQUEST['curId']);			
		} 
		
		$res = GetAll($sql);
		
		$tab = array(0);   			
		foreach( $res as $row) {
			$tab[] = $row[$fk_pk];
		}
		
		$pk2 = getPrimaryKey($fk_table);
		
		$clause = "";
		if(count($tab))
			$clause = ' AND T.'.$pk2.' NOT IN ( '.implode(',',$tab).' )';
		

		
		$s = new genSearchV2($fk_table);
		$res = $s->doFullSearch($_REQUEST['q'],$clause);
		
		foreach($res as $row) {
			print('<option value="'.$row[$pk2].'">'.getTitleFromRow($fk_table,$row).'</option>')	;				
		}
		die();
    	
    }
    
    function searchRelation() {
    	
    	$t = $_REQUEST['table'];
    	$fk = str_replace('genform_','',$_REQUEST['fk']);
    	global $relations;
    	
    	$table = $relations[$t][$fk];
    	
		$pk2 = getPrimaryKey($table);
		$s = new genSearchV2($table);
		$res = $s->doFullSearch($_REQUEST['q'],$clause,false);
		foreach($res as $row) {
			print('<li><a class="sal" onclick="selectRelationValue(this)" rel="'.$row[$pk2].'">'.getTitleFromRow($table,$row,' > ',true).'</a></li>')	;				
		}
		die();
		
    }
    
    
    
    function getArboRubs() {
    	
    	genAdmin::handleOpenRubs();
    	
    	$this->id = $_SESSION['XHRlastCurId'];
    	$this->arboRubs = genAdmin::getRubs();
    	//$_REQUEST['curId'] = $_REQUEST['showRub'] ?  $_REQUEST['showRub'] :  $_REQUEST['hideRub'];
    	
    	
    	genAdmin::recurserub('NULL',0,"1");
    	
    	
    }
    
    function recurserub($a,$b,$c){
    	
    	genAdmin::recurserub($a,$b,$c);
    	
    }
    
    
    function getLinks() {
		$site = new GenSite();
		$site->initLight();
    	$menus = $site->getMenus();
    	
    	$this->html = '<h1>'.t('choisir_rubrique_ci_dessous').'</h1><ul>';
    	foreach($menus as $menu ){
    		
    		$arbo = $site->g_url->recursRub($menu['rubrique_id']);
    		$this->html .= '<li>'.$menu['rubrique_titre_'.LG];
    		$this->recursLinks($arbo);
    		
    		$this->html .= '</li>';
    	}
    	$this->html .= '</ul>';
    	
    	print $this->html;
    }
    

	private function recursLinks($array, $level='1', $rootRub='1') {
		if(!is_array($array)) {
			return;
		}
		foreach($array as $page) {
			
				$page['url'] = '';	
				$url = '@rubrique_id='.$page['id'];	
				if($level == 1){
					$this->html .= ('<li class="top_div_' .$rootRub .'">');
					$this->html .= ('<a onclick="update_links(\''.$_GET['champ'].'\','.$page['id'].')" > '.$page['titre'].'</a>');
					

					if(count($page['sub']) && $level != 3) {
						$this->html .= ('<ul class="ul_' .$rootRub .'">');
						$this->recursLinks($page['sub'], $level+1, $rootRub);
						$this->html .= ('</ul>');
					}
					$this->html .= ('</li>');

				}else{
					$this->html .= ('<li class="level' .$level .'_' .$rootRub .'">');

					$this->html .= ('<a onclick="update_links(\''.$_GET['champ'].'\','.$page['id'].')"  >'.$page['titre'].'</a>');
					if(count($page['sub']) && $level != 3) {
						$this->html .= ('<ul>');
						$this->recursLinks($page['sub'], $level+1, $rootRub);
						$this->html .= ('</ul>');
					}
					$this->html .= ('</li>');
				}

				if($level == 1)
					$rootRub++;
		}
	}    
}


class object {
	
}