<?
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

class genAdmin {

	/**
	 * GenSecurity
	 *
	 * @var GenSecurity
	 */
	var $gs;
	/**
	 * gencontrolpanel
	 *
	 * @var genControlPanel
	 */
	var $control_panel;
	
	/**
	 * Plugins
	 *
	 * @var array
	 */
	var $plugins = array();
	

    function genAdmin ($table="",$id = 0) {

        /* Always do on loading */

        
        $this->table =$table;
        $this->id = $id;

	    //debug('GenAdmin: '.$table.' : '.$id);

		$_SESSION['XHRlastCurId'] = $_REQUEST['curId'] ? $_REQUEST['curId'] : $_SESSION['XHRlastCurId'];


        global $gs_obj,$lg;
        
        /**
         * Gestion de la langue de l'admin
         * $lg = $_SESSION['lg'] = $this->lg = $_REQUEST['lg'] ? $_REQUEST['lg'] :(  $_SESSION['lg']  ? $_SESSION['lg'] : getBrowserLang() );
   		define('LG',$lg);
         */

        if(count($_REQUEST['genform_action']) && !$_REQUEST['genform_action']['edit']) {        	
        	$_REQUEST['resume'] = 1;        	
        }

        $this->gs = &$gs_obj;

     	$this->firstId = $id;

     	$this->loadPlugins();
     	 
     	$this->checkActions();
     	
        /* Si on clique sur le logo, on revient  vide */

        if((!count($_POST)  && $_GET['curTable'] && !$_GET['delId'] && !$_GET['goBack'] ) || $_GET['home'])  {
            $_SESSION['levels'] = array();
            $_SESSION['nbLevels'] = 0;

            $gl = new GenLocks();

            $gl->unsetAllLocks();
            

        }

        /* On quitte et detruit la session */
        if($_REQUEST['destroy'] || $_REQUEST['logout'] ) {
            $this->destroySession();
        }


        /* On export la table en CSV */
        if($_REQUEST['export']) {
            $this->exportCsv();

        }

		$this->doRecord();
		
		$this->FormToInclude = $this->whichForm();

        /* On vide la table */
        if($_REQUEST['vider'] && $_REQUEST['confirm']) {
            $this->emptyTable();
        }

        if($_REQUEST['hideRub'] || $_REQUEST['showRub']) {

            $this->handleOpenRubs();
        }


        if( ( (($_GET['bas_1'])) || (($_GET['haut_1'])) )  && (($_GET['rubId']))  )
        {
            $this->updateRubriqueOrder();
        }





        $this->arboRubs = $this->getRubs();

        $this->insideRealRubId = $this->getRealRubriqueId();

        /* Auto open rubs ! */
        if($this->isInRubrique()) {
        	$id =  $this->insideRealRubId;

        	while(true) {
        		if(!$id) {
        			break;
        		} else {
        			
        			$_SESSION['visibleRubs'][$id] = true;
        			$id = $this->reverserubs[$id];
        		}
        	}
        }
        



       
    }

    
    function getRubs() {
    	
    	$sql = 'Select rubrique_id,fk_rubrique_id,fk_rubrique_version_id FROM s_rubrique';
        $res = GetAll($sql);
        $rubs = array();
        $this->rubver  = array();
        $this->reverserubs = array();
        foreach($res as $row) {
            $rubs[$row['fk_rubrique_id']][] = $row['rubrique_id'];
            $this->reverserubs[$row['rubrique_id']] = $row['fk_rubrique_id'];
            $this->rubver[$row['rubrique_id']] = $row['fk_rubrique_version_id'];
            if($this->table == 's_rubrique' && $this->id == $row['rubrique_id']) {
            	$this->real_rub_id = $row['fk_rubrique_version_id'];
            	$this->real_fk_rub = $row['fk_rubrique_id'];
            	
            }
        }
    	
        return  $rubs;
    }

 
    
    /**
     * Est dans la rubrique X
     *
     * @return unknown
     */
	function isInRubrique() {
		if($this->table == 's_rubrique') {
			return true;
		}
		else if($_SESSION['levels'][1]['curTable'] == 's_rubrique') {
			return true;
		}

		return false;
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

	function getRealRubriqueId() {


		if($this->real_rub_id > 0)
			return $this->real_rub_id;
		if(is_array($_SESSION['levels'])) {
		@reset($_SESSION['levels']);
		foreach($_SESSION['levels'] as $lev) {
			
			if($lev['curTable'] == 's_rubrique') {
				@reset($_SESSION['levels']);
				
				if( $this->rubver[$lev['curId']])
					return $this->rubver[$lev['curId']];

				else
					return $lev['curId'];
			}
		}
		@reset($_SESSION['levels']);
		}
		return false;
	}


    function gen() {

    	global $gb_obj;

	//include(gen_include_path.'/admin_html/inc.header.php');

	
		$gb_obj->includeFile( 'inc.header.php','admin_html');

	
        $this->getHeader();
        
        

        p('<div id="contenu">');

            $this->GetHeaderTitle();
            
            
            if($_REQUEST['include_action']) {
        		p('<div style="border:1px dashed #cc0000;background:lightgray;padding:10px;"><h1>ACTIONS</h1>');
        		$GLOBALS['gb_obj']->includeFile($_REQUEST['include_action'],'include_actions');
        		//die();
        		p('</div>');
        	
      		  }
      		  
      		  

            p('<div id="contenupadd">');

                $this->includeForm();
                

            p('</div>');

        p('</div>');


        $gb_obj->includeFile( 'inc.footer.php','admin_html');
    }


    function updateRubriqueOrder() {


		 $go = new GenOrder('s_rubrique',$_GET['rubId'],$_GET['fkrubId']);

         if(isset($_GET['bas_1']))
         {
         	$go->getDown();

         }

         if(isset($_GET['haut_1']))
         {

         	$go->getUp();


         }
         
         DoSql('UPDATE s_param SET param_valeur = UNIX_TIMESTAMP() WHERE param_id = "date_update_arbo"');




    }


      function GetTools() {
		if($this->table != 's_rubrique') {
		global $_Gconfig;
		p('<div id="tools" >');
		if($this->gs->can('add',$this->table)) {
			p('<a class="abutton" href="?curTable='.$this->table.'&amp;curId=new"> <img src="'.ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/document-new.png" alt="-"  /> '.t('ajouter_elem').'</a>');
		}
		
		
		
		if(ake($_Gconfig['tableActions'],$this->table)) {
			
			
			
				foreach($_Gconfig['tableActions'][$this->table] as $action) {
				
					if($this->gs->can($action,$this->table) && $action != $_REQUEST['tableAction']) {

					p('<a class="abutton" href="?curTable='.$this->table.'&tableAction='.$action.'"> <img src="'.ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/'.$action.'.png" alt=""  /> '.t($action).'</a>');					
					
      			}
			}
			
		}
		
		p('</div>');
		
		
		if($_REQUEST['tableAction'] && in_array($_REQUEST['tableAction'],$_Gconfig['tableActions'][$this->table])) {
			
			if($this->gs->can($action,$this->table)) {
				p('<div class="tableActions">');
				
					p('<h3>'.t('tableAction').' '.t($_REQUEST['tableAction']).'</h3>');	
				
					$_REQUEST['tableAction']();
				
				p('</div>');
			} else {
				debug(t('action_non_autorisee'));	
			}
			
		}

	}
    }



    function GetHeader() {


        p('    <div id="menug">');


			if($this->isInRubrique()) {		
		
				$this->getArboRubs();
				
			} else {
				p('<style type="text/css">
					#contenu {
						margin-left:0;
					}
					</style>
				');
		
			}


        p('    </div>');

    }

    
    
    /**
     * Verifie si l'on doit executer une action ou non
     * Si oui declenche l'action
     */

    function checkActions()
    {
        if (ake('genform_action', $_REQUEST)) 		{
        	
        	
            while(list($action,) = each($_REQUEST['genform_action'])) {
            
            $this->action = new GenAction($action , $this->table , $this->id , $this->row);
            $this->action->DoIt();
            
            
            if($_REQUEST['fromList'] && $this->action->canReturnToList()) {
            	$_REQUEST['curId'] = '';
            	$this->id = '';
            	$_REQUEST['resume'] = '';
            } else 
            if($action != 'edit') {
            	$_REQUEST['resume'] = '1';
            }
            }
        }
       
         if (ake('mass_action', $_REQUEST) ) 		{
         	
         	if( $_REQUEST['mass_action'] != '') {
	         
	         	foreach($_REQUEST['massiveActions'] as $k=>$v) {
	         		
	         		$action = new GenAction($_REQUEST['mass_action'] , $this->table , $k );
	            	$action->DoIt();
	         	
	         	}
	         	
         	} else {
         		dinfo(t('no_action_specified'));
         	}
         	
         }
        
    }
    

    function doRecord() {

        if($_REQUEST['genform__add_sub_table'] && $_REQUEST['genform__add_sub_id']) {
            $_SESSION['genform__add_sub_table'] = $_REQUEST['genform__add_sub_table'];
            $_SESSION['genform__add_sub_id'] = $_REQUEST['genform__add_sub_id'];
        }

		
        $this->genRecord = new genRecord($this->table,$this->id,true);
       
        $this->id = $this->genRecord->doRecord();
        

    }

    function getArboRubs()  {

        p('<h1>'.t('arborescence').'</h1>');
        p('<div id="arbo_rubs">');
			$this->getArboActions();
			p('<div id="arbo">');
	        $this->recurserub('NULL',0,"1");
	        p('</div>');
        p('</div>');
        
    }

    function getLeftMenu() {

        global $adminMenus;
        if(!is_array($adminMenus)) {
            derror('Pas de menus definis');
            return;
        }
        while(list($k,$v) = each($adminMenus)) {
                p('<h1>'.t('menu_'.$k).'</h1>');
                p('<ul class="text1">');


                foreach($v as $m) {
                    if($this->gs->can('view',$m)) {
                        p('<li>');
                        $cl = ($this->table == $m) ? "class='mselected'" : "";
                        p('<a '.$cl.' href="index.php?curTable='.$m.'&"><img src="'.ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/document-open.png" alt="-" /> '.t($m).'</a>');
                        p('</li>');
                    }
                }
                p('</ul>');
                p('<br/>');
        }


    }

    
    function genPlugins() {
    	
    	if(!is_array($this->plugins)) {
    		return false;
    	}
    	foreach($this->plugins as $plugin ) {
    		if(method_exists($plugin,'gen')) {
    			$plugin->gen();
    		}
    	}
    	
    	
    }

    function includeForm() {

        global $form;
        $toInclude = $this->FormToInclude;
        
        
        $this->genPlugins();
        
		if(@method_exists($this->action->obj,'gen')) {		
			$this->action->obj->gen();			
		}
		
        switch ($toInclude) {

            case "home":
	
				$gl = new GenLocks();
				$gl->unsetAllLocks();

				$GLOBALS['inScreen'] = 'home';

                /*global $gb_obj;
                $gb_obj->includeFile('home.php','admin_html');
				*/
                $this->control_panel = new genControlPanel();
                
                
               	// $this->plugins['stats']->genAfter();
               	 foreach($this->plugins as $k=>$v) {
               	 	
               	 	if(is_object($v) && method_exists($v,'genAfter')) {
               	 		$v->genAfter();
               	 	}
               	 }
                
				p( $this->control_panel->gen() );
				

            break;
            case "search":


				$gl = new GenLocks();
				$gl->unsetAllLocks();
				
				
				
				$GLOBALS['inScreen'] = 'search';

                $this->GetTools();
                

                $search = new GenSearchv2($this->table);               

                $search->printAll();
                
                

            break;
            
            case 'searchv2':
            	
            	
				$gl = new GenLocks();
				$gl->unsetAllLocks();
				
				
				
				$GLOBALS['inScreen'] = 'searchv2';

                $this->GetTools();
                

                $search = new GenSearchV2($this->table);               

                $search->printAll();
                
                        	
            	
            	break;

            case "form":

            	$GLOBALS['inScreen'] = 'form';
            	
            	$gl = new GenLocks();
            	$tl = $gl->getLock($this->table,$this->id);

            	if(is_array($tl)) {
            		dinfo(t('erreur_lock_existe'));

            		/*if($_SESSION['levels'] > 1) {
            		$this->FormToInclude = $this->whichForm();
            		$this->includeForm();
            		} else {
            			$this->FormToInclude = 'resume';
            			$this->includeForm();
            		}*/

            		global $editMode;
            		$editMode = true;
            		
            		$form = new GenForm($this->table, "", $this->id, "");
            		$form->editMode = true;
            		//debug($tl);
            	} else {
            		/*$_SESSION['genform_curTable'] = $this->table;
            		$_SESSION['genform_curId'] = $this->id;
            		*/

				}
				
				
				$form = new GenForm($this->table, "", $this->id, "");
		
				$form->genHeader();
				
				$form->genPages();
		
				$form->genFooter();


            break;

            case "resume":
            	

		//if(!count($_SESSION['levels']) {
		
				$gl = new GenLocks();
		
				$gl->unsetAllLocks();
			
				$GLOBALS['inScreen'] = 'resume';
		
				p('<div >');
				p('<div id="genform_navi" style="width:100px;"><div class="genform_onglet"><div class="btnOngletOn"><a>'.t('recapitulatif').'</a></div></div></div>');
		
		
				$form = new GenForm($this->table, "", $this->id, "");
				
				$form->separator = '<br/>';
				global $editMode;
		
				p('<style type="text/css">
				#zegenform {padding:0 !important;margin:0;}
				#zegenform p ,
				#zegenform h1  ,
				#zegenform caption
				{text-align:center;margin-top:10px;margin-bottom:10px;}
		
				.genform_onglet { border-bottom:1px solid #999 !important; margin-bottom:0;}
		
				</style>');
		
				$editMode = 1;
		
				$form->editMode = 1;
		
				$form->genActions();
		
				$form->genHeader();
		
		
		
				$form->genPages();
		
		
				$this->showLog();
		
		
				$form->genFooter();
				
				p('</div>');

            break;
        
     case "arbo":
        
     	$GLOBALS['inScreen'] = 'arbo';
     	
        $arbo = new genArbo($_REQUEST['rubId']);
        $arbo->gen();
        
     break;  
        
        }
        /*if($toInclude && is_file($toInclude))
                include ( $toInclude );
        else
                debug('Page en construction');
          */

    }


    function showLog() {
    	//if($this->table == 's_rubrique') {
		$sql = 'SELECT * FROM s_log_action
				 LEFT JOIN s_admin AS A ON fk_admin_id = A.admin_id 
				 WHERE log_action_fk_id = "'.$this->id.'" 
					AND log_action_table = "'.$this->table.'"  
				    ORDER BY log_action_time DESC, log_action_id DESC LIMIT 0,30';

		$res = GetAll($sql);

		p('<table id="table_log_action" summary="">');
		p('<caption>'.t('table_log_action').'</caption>');
		foreach($res as $row) {
			
			//if($lastAction !=  $row['log_action_action'] || $lastAdmin != $row['admin_id']) {
				$k++;
				$lastAction = $row['log_action_action'];
				$lastAdmin = $row['admin_id'];
				p('<tr '.($k%2?'':'class="odd"').'><td>'.$row['admin_nom'].'</td><td>'.$row['admin_email'].'</td><td>'.t('action_'.$row['log_action_action']).'</td><td>'.niceDateTime($row['log_action_time']).'</td></tr>');
			//}
		}
		p('</table>');

    	//}
    }


    function handleOpenRubs() {

            /* AFFICHER MASQUER DES RUBRIQUES DU MENU */


            $visibleRubs = $_SESSION['visibleRubs'];


            if($_REQUEST['showRub'] > 0) {
                $visibleRubs[$_REQUEST['showRub']] = true;
            } else if($_REQUEST['hideRub'] > 0) {
                $visibleRubs[$_REQUEST['hideRub']] = false;
            }


            $_SESSION['visibleRubs'] = $visibleRubs ;

    }




    function destroySession() {

        /* On vire tout, c'est pratique pour les tests */
        $this->gs->clearAuth();
        session_destroy();
        $_SESSION = "";
        $_SESSION = array();

        header('location:/');

    }


    function exportCsv () {

        /* Export la table courante en CSV */

        $sql ='SELECT * FROM '.$this->table;
        $res = GetAll($sql);

        header('Content-Disposition: attachment; filename="'.$this->table.'.csv"');
        header("Content-Type: text/comma-separated-values");


        foreach($res as $row ) {
                $i=0;

                $f= new GenForm($this->table,'post',0,$row);
                $f->editMode=true;
                $f->onlyData=true;
                while(list($k,)=each($row)) {
                    if($i%2)
                        print($this->csvenc($f->gen($k)).';');
                    $i++;
                }
                
                print("\n");

        }

        die();


    }


    function  csvenc($str) {
        /* pour �iter les probl�es en CSV on r�ncode les retours �la ligne et les ";" */

        return str_replace(array(";","\n","\r"),array(":"," "," "),$str);
    }


    function addMessage($str) {
        /* Pour l'information */
        $this->messages[] = $str;
    }


    function emptyTable() {
        $sql ='TRUNCATE TABLE '.$this->table;
        DoSql($sql);

        $this->addMessage(t('table_videe')." ".t($this->table));
    }



     function getArboActions() {
	p('<div id="arbo_actions">');


		if($this->id) {
			$ht = '&nbsp;&nbsp;&nbsp;<a href="index.php?haut_1=1&amp;curTable='.$this->table.'&amp;curId='.$this->id.'&amp;rubId='.$this->real_rub_id.'&amp;fkrubId='.$this->real_fk_rub.'" title="Monter d\'un niveau"><img src="'.ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/go-up.png" alt="" /> '.t('monter').' </a>';

			$bs = '&nbsp;&nbsp;&nbsp;<a href="index.php?bas_1=1&amp;curTable='.$this->table.'&amp;curId='.$this->id.'&amp;rubId='.$this->real_rub_id.'&amp;fkrubId='.$this->real_fk_rub.'" title="Descendre d\'un niveau"><img src="'.ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/go-down.png" alt="" /> '.t('descendre').'</a>';

		$ajout ='';

		if($aff['rubrique_ordre'] == 1){
			//$ht ='&nbsp;&nbsp;&nbsp;<img src="'.ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/up_off.png" />';
		}

		if($aff['rubrique_ordre'] == $maxxi){
			//$bs = '&nbsp;&nbsp;&nbsp;<img src="'.ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/down_off.png" />';
		}

		// On autorise jusqu'a X niveau d'arborescence


		$sql = '
		SELECT * FROM s_rubrique AS R1, s_rubrique AS R2, s_rubrique AS R3 ,  s_rubrique AS R4 ,s_rubrique AS R5,s_rubrique AS R6
		WHERE R6.rubrique_id = "'.$this->real_rub_id.'"
		AND R6.fk_rubrique_id = R5.rubrique_id
		AND R5.fk_rubrique_id = R4.rubrique_id
		AND R4.fk_rubrique_id = R3.rubrique_id
		AND R3.fk_rubrique_id = R2.rubrique_id
		AND R2.fk_rubrique_id = R1.rubrique_id
		AND R1.fk_rubrique_id IS NULL';
		//$res = GetAll($sql);


		//if(!count($res) || true) {
		if(true) {
			$ajout =' &nbsp; <a href="index.php?curTable=s_rubrique&amp;curId=new&amp;genform__add_sub_table=s_rubrique&amp;genform__add_sub_id='.$this->real_rub_id.'&amp;genform_default__rubrique_ordre='.((count($this->arboRubs[$this->real_rub_id])/2)+1).'" title="Ajouter une sous rubrique "><img src="'.ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/document-new.png" alt="" /> '.t('ajout_sub_rub').'</a>';
		}
		}
		else {
			$ht = t('select_rub_below');
		}
		
		/* construction simplifiée de l'aroborescence  */
		if($_REQUEST['curId'] AND $_REQUEST['curId'] != 'new')
        $arbo = '&nbsp;&nbsp;&nbsp;<a href="index.php?arbo=1&amp;rubId='.$this->id.'&amp;fkrubId='.$this->real_fk_rub.'" title="'.t('arborescence').'"><img src="'.ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/arbo.png" alt="" /></a>';
		else $arbo ='';
		
		p($ht.$bs.$ajout.$arbo.'</div><br/>');


	}

	
	
	/**
	 * Toute l'arbrescence des rubriques sur la gauche
	 *
	 * @param unknown_type $id
	 * @param unknown_type $nivv
	 * @param unknown_type $dolinka
	 */
    function recurserub($id=0,$nivv=0,$dolinka=0){
    	
        
        $lighta = "<span style='color:#999'>";
        $lightb = "</span>";
//        $siteRootImg = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/apps/system-software-update.png';
//        $menuRootImg = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/apps/preferences-system-windows.png';
//        $pageImg = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/edit-copy.png';
//        $linkImg = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/edit-redo.png';
//        $folderImg = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/places/folder.png';
        $pictoAr['siteroot'] = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/apps/system-software-update.png';;
        $pictoAr['menuroot'] = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/apps/preferences-system-windows.png';;
        $pictoAr['page'] = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/document-template.png';;
        $pictoAr['link'] = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/actions/edit-redo.png';;
        $pictoAr['folder'] = ADMIN_PICTOS_FOLDER.''.ADMIN_PICTOS_ARBO_SIZE.'/places/folder.png';;
        
        $souldBeOrder = 1;        
        
		/**
		 * Selectionne toutes les sous rubriques
		 */		
		if($id == 0 && is_array($this->gs->myroles['s_rubrique']['rows'])) {
			/**
			 * Pour les utilisateurs simples
			 * avec des accès sous-rubriques
			 */			
			$q = "SELECT 
						G.*,
	        			R1.* , 
	        			R2.rubrique_ordre as r2_ordre, 
	        			R2.rubrique_etat AS r2_etat 
	        			FROM  s_rubrique as R2 , s_rubrique AS R1 LEFT JOIN s_gabarit AS G ON G.gabarit_id = R1.fk_gabarit_id
	        			WHERE R1.rubrique_id IN(".implode(',',$this->gs->myroles['s_rubrique']['rows']).") ".sqlRubriqueVersions('R2.rubrique_id','R1')." 
	        			ORDER BY r2_ordre ASC ";
			
		} else {
			/**
			 * Pour les super admins
			 */
	        $q = "SELECT 
	        			G.*,
	        			R1.* , 
	        			R2.rubrique_ordre as r2_ordre, 
	        			R2.rubrique_etat AS r2_etat 
	        			FROM  s_rubrique as R2 , s_rubrique AS R1 LEFT JOIN s_gabarit AS G ON G.gabarit_id = R1.fk_gabarit_id
	        			WHERE R1.fk_rubrique_id ".sqlParam($id)." ".sqlRubriqueVersions('R2.rubrique_id','R1')." 
	        			ORDER BY r2_ordre ASC ";
		}
		
        $query = GetAll($q); 
       

       foreach($query as $aff ) {

		    $real_rub = $aff['fk_rubrique_version_id'];
		    $version_rub = $aff['rubrique_id'];

		    /**
		     * Si l'utilisateur peut modifier cette rubrique
		     */
			if ( $this->gs->can('edit','s_rubrique',$aff)  ) {
	
				p('<ul>');
	
				/**
				 * Titre par défaut si vide
				 */
	            if(!strlen($aff['rubrique_titre_'.LG_DEF])) {
	                    $aff['rubrique_titre_'.LG_DEF] = "[TITRE VIDE]";
	             }
	
				
	            /**
	             * Si jamais l'ordre n'était pas bon pour une raison X 
	             */
	            if($souldBeOrder != $aff["r2_ordre"]) {
					/**
					 * Si jamais l'ordre est faux, on réordonne
					 */
					$sql = 'UPDATE s_rubrique SET rubrique_ordre = "'.$souldBeOrder.'" WHERE `rubrique_id` = "'.$real_rub.'"';
					$res = DoSql($sql);
				  		
					$aff['r2_ordre'] = $souldBeOrder;
	            }
	
	            /**
	             * On fait un lien ?
	             * 
	             */
	            $dolink =true;
	
	            /**
	             * permet de connaitre pour la rubriqque en cours son niveau maximal
	             */
				$cl = '';
				
	            /**
	             * Cette rubrique est-elle sélectionnée ?
	             */
	            if($this->insideRealRubId == $aff['fk_rubrique_version_id'] ||
	            		 ( $aff['fk_rubrique_version_id'] == $_SESSION['XHRlastCurId'] && !$_REQUEST['curId']) ) {
	            	$cl = 'class="mselected"';
	            	$_SESSION['XHRlastCurId'] = $aff['fk_rubrique_version_id'];
	            
	            }
	
	            p('<li '.$cl.'><span>');
	
	           	/**
	            *  Ancres pour liens directs quand le menu est plus long que la page
	            */
	            if($aff["fk_rubrique_id"] == 0)
	                  p('<a name="rub'.$aff["rubrique_id"].'" />');
	
	            /**
	             * Classe transparente si rubrique masquée
	             */
				$classColor = '';
			    $classColor = $aff['r2_etat'] == 'en_ligne' ? '' : ' pasenligne';
				
				
				
	            /**
	             * Dossier ouvert / fermé :
	             * plus ou moins
	             */
	            $paramShow = $_SESSION['visibleRubs'][$real_rub] ? 'hideRub='.$real_rub : 'showRub='.$real_rub;
	            $plusmoins = $_SESSION['visibleRubs'][$real_rub] ? '<img src="./img/moins.gif" alt="" />' : '<img src="./img/plus.gif" alt="" />';
	            
	            /**
	             * Code JS pour ouverture
	             */
	            $xhr = 'onclick="XHR_menuArbo(this.href,this);return false;"';
	            
	
	            $imageToShow = '';
	       
	            /**
	             * URL d'accès
	             */
	            $url = '?'.$paramShow.'&amp;curTable=s_rubrique';
	            

	            $picto = $pictoAr[$aff['rubrique_type'] ];
	            
            	if($aff['gabarit_classe']) {
	            	if($aff['gabarit_plugin']) {
	            		$f = path_concat(PLUGINS_FOLDER,$aff['gabarit_plugin']);
	            	} else {
	            		$f = 'bdd';
	            	}
	            	$GLOBALS['gb_obj']->includeFile($aff['gabarit_classe'].'.php',$f);
	            	//debug( $GLOBALS['gb_obj']->includeFile($aff['gabarit_classe'].'.php',$f));
	            	//$res = ${$aff['gabarit_classe'].'::getPicto'}();
	            	//debug($aff['gabarit_classe']);
	            	//$aaaa = eval(''.$aff['gabarit_classe'].'::getPicto();');
	            	if( method_exists($aff['gabarit_classe'],'ocms_getPicto')) {
//	            		debug('PICTO');
	            		$picto = call_user_method('ocms_getPicto',$aff['gabarit_classe'],$aff);	            		
	            	}
	            }
	  
				/**
				 * Si on a des sous-rubriques on affiche le plus/moins
				 * Sinon ... non
				 */
				if(count($this->arboRubs[$real_rub]) > 0) {
					p('<a class="plusmoins '.$classColor.'" 
							'.$xhr.' href="'.$url.'" 
							>
							'.$plusmoins.'<img src="'.$picto.'" alt="" /></a>');
				} else {
					p('<a class="plusmoins '.$classColor.'" 
							>
							<img src="./img/pixel.gif" width="16" height="16" alt="" /><img src="'.$picto.'" alt="" /></a>');
				}
		
		
				/**
				 * Sommes nous en fin de rubrique ?
				 */
				$m = "SELECT max(rubrique_ordre) as maxi FROM s_rubrique WHERE fk_rubrique_id ".sqlParam($id).' '.sqlRubriqueOnlyReal();
				$max = GetSingle($m);
				$maxxi = $max["maxi"];
		
				/**
				 * Lien texte
				 */
				$linka = '<a '.$cl.' 
								href="index.php?curTable=s_rubrique&amp;showRub='.$real_rub.'&amp;curId='.$version_rub.'&amp;resume=1" 
								title="'.$aff['rubrique_titre_'.LG_DEF].'" 
								onmouseover="swapactions(\'imm_'.$real_rub.'\',this)">';
		
		
				$link = (($aff['rubrique_titre_'.LG_DEF]));
				$link = getLgValue('rubrique_titre',$aff);
				$linkb = '</a>';
		

				if($dolink)
					echo $linka.$link.$linkb.'<span class="img_move" id="imm_'.$real_rub.'">'.$ht.$bs." ".$ajout."</span>";
				else
					echo $lighta.$link.$lightb;
		
				p('</span>');
				
				p('<br/>');
	
	
				if($_SESSION['visibleRubs'][$real_rub]) {		
					/**
					 * On parcourt en dessous
					 */
					$this->recurserub($real_rub,$nivv+1,$dolink);
		
				}
				$nextlink = 0;
		
		
		
		 		p('</ul>');
			
			}
			else {				
				/**
				 * On avait pas accès à cette rubrique, 
				 * On parcourt en dessous voir si on a accès
				 */
				$this->recurserub($real_rub,$nivv+1,$dolink);
			}

		$souldBeOrder++;


        }

        
    }


    function GetRecordTitle($table,$id,$sep=" ",$pk="") {
        /*
         * Formate proprement le titre d'une table
         *
         * */

        global $tabForms;
        if(strlen($pk) == 0) {
            $pk = GetPrimaryKey($table);
        }
        $sql = "SELECT ".GetTitleFromTable($table,' , ')." FROM ".$table." WHERE ".$pk." = '".$id."'";
        $row = GetSingle($sql);
       
        $r= "";
        /*
        if(is_array($row)) {
	        $i=0;
	        foreach($row as $v) {
	            $i++;
	            if($i%2)
	                $r .= $v.$sep;
	        }

            $r= substr($r,0,-1*(int)strlen($sep));
         }
         return $r;
         */
        
         return GetTitleFromRow($table,$row);
         



    }




    function GetHeaderTitle() {

         p('<div id="titre">');
         
         $table = strlen($_SESSION['levels'][0]['curTable']) ? $_SESSION['levels'][0]['curTable'] : $_REQUEST['curTable'];
         if($table) {
         	p('<a href="?curTable='.$table.'&amp" ><img class="inputimage" src="'.t('src_desktop').'" alt="Retour" /></a> ');
         }
         if($this->id  || $this->id == 'new') {

                        if($_SESSION['nbLevels'] > 0) {


                        	p('<a href="?curTable='.$_SESSION['levels'][1]['curTable'].'&amp;curId='.$_SESSION['levels'][1]['curId'].'&resume=1" ><img class="inputimage" src="'.t('src_first').'" alt="Retour" /></a> ');

                        	p('<a href="?'.time().'" ><img class="inputimage" src="'.t('src_back').'" alt="Retour" /></a> ');
							
							while(list($k,$v) = each($_SESSION['levels'])) {
							        if($v['curTable']) {
							        	p('<span class="titreListe">'.limitWords(strip_tags($this->GetRecordTitle($v["curTable"],$v["curId"]," ",$v["curTableKey"])),15)." [".$v["curId"]."] </span> &raquo;");
							        }
							 }
                         } else {

                         	if($this->FormToInclude == 'resume') {
								p('<a href="?curTable='.$_REQUEST['curTable'].'" style="margin:0;padding:0"><img style="vertical-align:middle;margin:0;" src="'.t('src_first').'" alt="Retour" /></a>');
                         	} else if($this->id == 'new') {
                         		p('<a href="?curTable='.$_REQUEST['curTable'].'" style="margin:0;padding:0"><img style="vertical-align:middle;margin:0;" src="'.t('src_first').'" alt="Retour" /></a>');

                         	} else {
								p('<a href="?curTable='.$_REQUEST['curTable'].'&amp;curId='.$_REQUEST['curId'].'&resume=1" style="margin:0;padding:0"><img style="vertical-align:middle;margin:0;" src="'.t('src_first').'" alt="Retour" /></a>');
                         	}
                         }
                        //p("  ".t($_REQUEST['curTable']));
                        $t = limitWords(strip_tags($this->GetRecordTitle($this->table,$this->id)),15);
                        if($this->id && $t) p(' <span class="titreListe">'.$t.' ['.$this->id.']</span> ');
                        else p('<span class="titreListe">Nouveau ['.$this->id.']</span>');

                       // p(' / <span class="titreTable">'.t($this->table).'</span>');

        } else  if($this->table) {

                p('<span class="titreListe">'.t($this->table).'</span>');
        }


        p('</div>
        ');
    }






    function whichForm() {

        /* Quel formulaire on inclu */

        global $tabForms, $formsRep,$fieldError,$genMessages;
        $comingBack = 0;


        /**
         * On reste sur le meme formulaire 
         * Car il y a un champ mal remplit ou bien on a demander à rester
         */
        if(is_array($fieldError) ||  strlen($_POST['genform_stay'])) {
            $gl = new GenLocks();

			$gl->setLock($this->table,$this->id);

            return ("form");

        } else {
            /**
             *  Si on vient de cliquer sur un bouton Ajouter depuis un autre formulaire 
             **/
            if($_REQUEST['newTable'] != "") {
                /*
                        On stock les infos actuelles dans la session
                 */

                $_SESSION['nbLevels']++;
                $_SESSION['levels'][$_SESSION['nbLevels']]['curTable'] = $_REQUEST['curTable'];
                $_SESSION['levels'][$_SESSION['nbLevels']]['curTableKey'] = $_REQUEST['curTableKey'];
                $_SESSION['levels'][$_SESSION['nbLevels']]['curId'] = $_REQUEST['curId'];
                $_SESSION['levels'][$_SESSION['nbLevels']]['fieldToUpdate'] = $_REQUEST['fieldToUpdate'];
                $_SESSION['levels'][$_SESSION['nbLevels']]['curPage'] = $_REQUEST['curPage'];
                $_SESSION['levels'][$_SESSION['nbLevels']]['tableToUpdate'] = $_REQUEST['tableToUpdate'];
                $_SESSION['levels'][$_SESSION['nbLevels']]['insertOtherField'] = $_REQUEST['insertOtherField'];



                /*
                        Et pour aller sur le nouveau formulaire on fait ca
                 */
                $_REQUEST['curTable'] = $_REQUEST['newTable'];
                $_REQUEST['curId'] = $_REQUEST['newId'] ? $_REQUEST['newId'] : "";
                $_REQUEST['curTableKey'] = "";
                $_REQUEST['curPage'] = "";
                $_REQUEST['newTable'] = "";
                $_REQUEST['tableToUpdate'] = "";
                $_REQUEST['insertOtherField'] = "";


            }
            /**
             *  Sinon, si on revient d'un formulaire vers un autre 
             **/
            else if ($_SESSION['nbLevels'] > 0   ) {
				/*
                        On recupere nos variables de sessions
                 */
                $beforeRequest = $_REQUEST;

                $_REQUEST['curTable'] = $_SESSION['levels'][$_SESSION['nbLevels']]['curTable'];
                $_REQUEST['curTableKey'] = $_SESSION['levels'][$_SESSION['nbLevels']]['curTableKey'];
                $_REQUEST['curPage'] = $_SESSION['levels'][$_SESSION['nbLevels']]['curPage'];
                $_REQUEST['tableToUpdate'] = $_SESSION['levels'][$_SESSION['nbLevels']]['tableToUpdate'];
                $_REQUEST['insertOtherField'] = $_SESSION['levels'][$_SESSION['nbLevels']]['insertOtherField'];

                $sql = "";
                /*
                        On modifie la Table de relation
                 */
                if($_REQUEST['tableToUpdate'] && $_REQUEST['curId'] && $_REQUEST['curId'] != 'new') {
                    $sql = "INSERT INTO ".$_REQUEST['tableToUpdate'].
                    		" ( fk_".$_SESSION['levels'][$_SESSION['nbLevels']]['fieldToUpdate']." , fk_".
                    		$_REQUEST['curTableKey']." )  VALUES  ( ".
                    		$_REQUEST['curId']." , ".
                    		$_SESSION['levels'][$_SESSION['nbLevels']]['curId']." ) ";

                 } else if($_SESSION['levels'][$_SESSION['nbLevels']]['fieldToUpdate'] &&
             		 $_REQUEST['curId'] && $_REQUEST['curId'] != "new") {
	                /*
	                        On modifie la Clef externe simple
	                 */
                    $sql = "UPDATE ".$_REQUEST['curTable']." SET ".
                    		$_SESSION['levels'][$_SESSION['nbLevels']]['fieldToUpdate']." = ".
                    		$_REQUEST['curId']." WHERE ".$_REQUEST['curTableKey']." = '".
                    		$_SESSION['levels'][$_SESSION['nbLevels']]['curId']."'";


                    /* On r�rganise / r�rdone */
                    $ord = new GenOrder($_REQUEST['curTable'],$_REQUEST['curId'],$_REQUEST['curId']);
                    $ord->OrderAfterInsert();

                }

                /*
                        Si on a pas annulé on fait vraiment cette requ�e
                 */

                if(!$_POST['genform_cancel'] && $sql) {
                        DoSql($sql);
			}

			$gl = new GenLocks();

			$gl->unsetLock($this->table,$this->id);


            if(!$_POST['nextPage'] && !$_POST['prevPage']) {
                    $_REQUEST['curId'] = $_SESSION['levels'][$_SESSION['nbLevels']]['curId'];

                    $_SESSION['levels'][$_SESSION['nbLevels']] = "";
                    $comingBack = 1;

                    $_SESSION['nbLevels']--;
                    $_REQUEST['curTable'] = $_REQUEST['curTable'];
            } else {
                    $_REQUEST = $beforeRequest;
            }
        }


        /**
         *  On change de page 
         * @deprecated  JE PENSE ... maintenant les pages sont en onglets JS ...
         **/
        if($_POST['nextPage']) {
                $_REQUEST['curPage']++;
        }
        if($_POST['prevPage']) {
                $_REQUEST['curPage']--;
        }
        
        
		global $_Gconfig;
        /**
         *  Si on finit apres avoir soumis le formulaire
         **/
        if(( ake('genform_cancel',$_POST)
        	|| ake('genform_ok',$_POST)
        	|| ake('genform_ok_x',$_POST)
        	|| ake('genform_cancel',$_POST)
        	|| $_REQUEST['resume'] ) && !$comingBack) {

        		
        	/**
        	 * Si c'est une table en "updateAfterInsert" et qu'on vient de la créer, on revient dessus
        	 */
         	if(in_array($this->table,$_Gconfig['updateAfterInsert']) && $this->firstId == 'new') {

     	         $gl = new GenLocks();

				 $gl->setLock($this->table,$this->id);

                  return('form');
                  
         	}
         	/**
         	 * Sinon on retourne au résumé si on a fait OK ou CANCEL ou qu'on a demandé le résumé
         	 */         	
	     	if((ake('genform_ok',$_POST)
	            || ake('genform_ok_x',$_POST)
	            || ake('genform_cancel',$_POST)
	            || ake('genform_cancel_x',$_POST)
	            || $_REQUEST['resume']
	            )) {
                if(!$_REQUEST['resume']) {
            		//$this->genRecord->checkDoOn('saved');
                }
                return('resume');
			}
	       	else {
	       		if(in_array($_REQUEST['curTable'] , $_Gconfig['multiVersionTable'])) {
	       			return ("searchv2");
	       		} else {
	            	return ("search");
	       		}
	       	}
        } else if($_REQUEST['curTable'] || $_POST['prevPage'] || $_POST['nextPage']) {
        	
	        /**
	         * Si on inclu vraiment le formulaire
	         * 
	         **/
	        $_REQUEST['curPage'] = $_REQUEST['curPage'] ? $_REQUEST['curPage'] : '0';
	
	        /* Enfin on retourne le formulaire */
	        if($_REQUEST['curId']) {
                $this->table = $_REQUEST['curTable'];
                $this->id = $_REQUEST['curId'];

                $gl = new GenLocks();

				$gl->setLock($this->table,$this->id);

                return "form";
       		}

                                //return ($formsRep.$tabForms[$_REQUEST['curTable']]['pages'][$_REQUEST['curPage']]);
            else if ($_REQUEST['curTable']) {
            	if(in_array($_REQUEST['curTable'] , $_Gconfig['multiVersionTable'])) {
	       			return ("searchv2");
	       		} else {
	            	return ("search");
	       		}
                    
            }

        } else if($_REQUEST['arbo']){
        
            return ("arbo");
                
        } else {
            /* Sinon  quoi ? */
            $_REQUEST['curTable'] = "";
            $_REQUEST['curId'] = "";

            return ("home");
        }
      }

    }
}


?>
