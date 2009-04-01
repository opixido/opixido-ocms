<?php

class genControlPanel{

	function __construct($admin=false) {
		
		
		$strAction = $this->GlobalAction();	

		if(!($_REQUEST['globalAction'])) {
		
		
			$this->tpl_cp = new genTemplate();			
			$this->tpl_cp->loadTemplate('cp.main');	
			$this->selectUserInfo();		

		}
		
	}
	
	public function gen(){

		if(!($_REQUEST['globalAction'])) {
			
			$this->tpl_cp->set('pictoGrid', $this->genPictoGrid());
		
		
			$this->tpl_cp->set('infoTime', $this->getInfoTime());
			$this->tpl_cp->set('userInfos', $this->genUserInfo());
	
			$this->tpl_cp->set('updatedRubs', $this->getUpdatedRubs());
			$this->tpl_cp->set('validatedRubs', $this->getValidatedRubs());
			$this->tpl_cp->set('lastCreatedRubs', $this->getLastCreatedRubs());
			
			$this->tpl_cp->set('globalActions', $this->getGlobalActions());
			
			$this->tpl_cp = $this->tpl_cp;	
			
			
			return $this->tpl_cp->gen();
		}
		//$this->tpl_cp->set('lastLexique', $this->getLastLexique());
		//$this->tpl_cp->set('lastPubli', $this->getLastPublication());
		//$this->tpl_cp->set('lastSite', $this->getLastSite());

		
	}

	
	function globalAction() {
		
		global $_Gconfig,$gs_obj;	
		$action = $_REQUEST['globalAction'];
		//ob_start();
		if($action && in_array($action,$_Gconfig['globalActions']) && $gs_obj->can($action)) {
			
			p('<h3><a href="?">&laquo; '.t('retour').'</a></h3><div class="info" >');
			p('<h3>'.t($action).'</h3>');
			$action();			
			p('</div>');
			
		}
		//ob_get_contents();
		//return ob_get_clean();
		return true;
		
	}
	
	function getGlobalActions() {
		global $_Gconfig,$gs_obj;

		$html = "<div id='list_action' class='list_right' >";
		$html .= '<p class="titre_onglet">'.t('liste_global_actions').'</p>';
		foreach($_Gconfig['globalActions'] as $action) {
		
				if($gs_obj->can($action)) {
					
					$html .= '<a class="abutton" href="?globalAction='.$action.'">'.t($action).'</a>';
					
				}
			
		}
		$html .= '<p class="titre_onglet" style="clear:both;">&nbsp;</p>';
		$html .= '</div>';
		
		return $html;
		
	}
	
	private function genPictoGrid(){
		$grid = new genTemplate();
		$grid->loadTemplate('cp.picto.grid');

		return $grid->gen();
	}

	private function getInfoTime(){
		
		$date = ucfirst( strftime('%A %d %B %Y'));
		//utf8_encode(ucfirst(strftime("%A", strtotime(date('D'))))) .' ' .date('d') .' ' .utf8_encode(ucfirst(strftime("%B", strtotime(date('m'))))) .' ' .date('Y');

		return $date;
	}
	
	
	private function selectUserInfo() {
		global $_Gconfig;
		$this->addUserBlock('user',t('cp_user_info'));
		
		
		
		$sql = 'select * from s_admin where admin_id=' .$GLOBALS['gs_obj']->adminid;
		$admin = GetSingle( $sql );
		$lastcx = explode(' ', $_SESSION['last_cx']);
		
		$this->addUserLine('user',t('cp_derniere_connexion'),nicedate($lastcx[0]) .' à ' .$lastcx[1]);
		if(is_array($_Gconfig['ADMIN_LANGUAGES']) && count($_Gconfig['ADMIN_LANGUAGES']) > 1) {
			
			reset($_Gconfig['ADMIN_LANGUAGES']);
			foreach($_Gconfig['ADMIN_LANGUAGES'] as $v) {
				$lgs .= '<a href="?lg='.$v.'"><img src="./img/flags/'.$v.'.gif" alt="'.$v.'"/></a> &nbsp; ';
			}
			
			$this->addUserLine('user',t('cp_lg'),$lgs);
		}
		//$this->UserLines['user'][] = array(t('derniere_connexion')=>nicedate($lastcx[0]) .' à ' .$lastcx[1]);
		//$this->addUserLine('user',t('fonction'),$admin['admin_type']);
		//$this->UserLines['user'][] = array(t('fonction')=> $admin['admin_type']);

	}

	private function genUserInfo(){
		
		$tpl = new genTemplate();
		$tpl->loadTemplate('cp.userinfo');
		$tpl->set('user_name', $GLOBALS['gs_obj']->adminnom);

		
		foreach($this->UserBlocks as $k=>$v) {
			$html .= '<p class="titre_onglet">'.$v.'</p>';
			$html .= '<table cellspacing=0>';
			foreach($this->UserLines[$k] as $k2=>$v2) {
				$html .= '
				<tr>
				<td>'.$k2.'</td>
				<td>'.$v2.'</td>
				</tr>
				';
			}
			$html .= '</table>'	;
		}
		
		$tpl->set('content',$html);
		

		return $tpl->gen();
	}
	
	public function addUserBlock($type,$titre) {		
		
		$this->UserBlocks[$type] = $titre;
	}
	
	public function addUserLine($type,$nom,$valeur) {
		
		$this->UserLines[$type][$nom] = $valeur;
		
	}
	

	private function getUpdatedRubs(){
		$sql = 'select *
			   from s_rubrique
			   where rubrique_etat=\'attente\'
			   and fk_rubrique_id!=0
			   and fk_rubrique_version_id IS NOT NULL
			   order by rubrique_date_modif desc';

		$res = GetAll( $sql );

		$tpl = new genTemplate();
		$tpl->loadTemplate('cp.updated.rubs');

		if(count($res) == 0){
			$temp = '<p class="centre">' .t('cp_no_rubs_updated') .'</p>';
		}else{
			foreach( $res as $k => $v){
				if($v['rubrique_titre_fr'] == '')
					$v['rubrique_titre_fr'] = '*** Titre en-cours d\'ecriture';

				$temp .= '
				<tr class="ligne">
				  <td><a href="?curTable=s_rubrique&curId=' .$v['rubrique_id'] .'">' .$v['rubrique_titre_fr'] .'</a></td>
				  <td>' .nicedate(substr($v['rubrique_date_modif'], 0, -9)) .'</td>
				</tr>';
			}
		}

		$tpl->set('list_rubs', $temp);

		return $tpl->gen();
	}

	private function getValidatedRubs(){
		$sql = 'select r1.*, r2.rubrique_date_publi as date_publi
			   from s_rubrique r1, s_rubrique r2
			   where r2.rubrique_etat=\'en_ligne\'
			   and r1.fk_rubrique_version_id=r2.rubrique_id
			   and r1.fk_rubrique_id!=0
			   and r1.fk_rubrique_version_id IS NOT NULL
			   order by r2.rubrique_date_publi desc limit 10';

		$res = GetAll( $sql );

		$tpl = new genTemplate();
		$tpl->loadTemplate('cp.validated.rubs');

		foreach($res as $k => $v){
			if( $GLOBALS[gs_obj]->can('view', 's_rubrique', '', $v['rubrique_id']) ){
				if($v['rubrique_titre_fr'] == '')
					$v['rubrique_titre_fr'] = '*** Titre en-cours d\'ecriture';

				$temp .= '
				<tr class="ligne">
				  <td><a href="?curTable=s_rubrique&curId=' .$v['rubrique_id'] .'">' .$v['rubrique_titre_fr'] .'</td>
				  <td>' .nicedate(substr($v['date_publi'], 0, -9)) .'</td>
				</tr>';
			}
		}

		if($temp == ''){
			$temp = '<p class="centre">' .t('cp_no_rubs_validated') .'</p>';
		}

		$tpl->set('list_rubs', $temp);
		return $tpl->gen();
	}

	private function getLastCreatedRubs(){
		$sql = 'select *
			   from s_rubrique
			   where fk_rubrique_id!=0
			   and rubrique_etat ="redaction"
			   and fk_rubrique_version_id IS NOT NULL
			   order by rubrique_date_crea desc LIMIT 15';

		$res = GetAll( $sql );

		$tpl = new genTemplate();
		$tpl->loadTemplate('cp.created.rubs');

		foreach($res as $k => $v){
			if($v['rubrique_titre_fr'] == '')
				$v['rubrique_titre_fr'] = '*** Titre en-cours d\'ecriture';

			$temp .= '
			<tr class="ligne">
			  <td><a href="?curTable=s_rubrique&curId=' .$v['rubrique_id'] .'">' .$v['rubrique_titre_fr'] .'</a></td>
			  <td>' .nicedate(substr($v['rubrique_date_crea'], 0, -9)) .'</td>
			</tr>';
		}

		$tpl->set('list_rubs', $temp);

		return $tpl->gen();
	}

	private function getLastPublishedRub(){
		$sql = 'select * from s_rubrique order by rubrique_date_publi desc';
		$res = GetSingle( $sql );
	}
}

?>