<?php

/* CLASSE DE GENERATION DE MENU */
class genMenu{

	/* Proprietes de la classe genMenu */
	private $site;			//Objet de type site
	private $tabHeader;		//Tableau contenant les url's du menu topright
	public $tabPrincipal;		//Celui contenant les url's du menu principal
	private $tabFooter;		//Celui contenant les url's du menu du footer
	public $separator;
	public $visible = true;
	public $tpl_name = 'menu.item';
	public $tpl_folder = '';

	/* Contructeur de la classe genMenu */
	function genMenu($site,$nom_menu='',$id_menu=0,$row_menu=array()){
		global $headRootId;
		global $rootId;
		global $footRootId;
		global $_Gconfig;

		//trigger_error('MENU ');
		
		

		$this->site = $site;

		if(ake($_Gconfig['menus'],$nom_menu)) {
			$this->conf = $_Gconfig['menus'][$nom_menu];
		} else {
			$this->conf = $_Gconfig['menus']['__default__'];
		}
		
		if(!count($row_menu)) {
			if($id_menu && !$nom_menu) {
					$row_menu = GetRowFromId('s_rubrique',(int)$id_menu);
			} else if($nom_menu && !$id_menu) {
					$row_menu = GetRowFromFieldLike('s_rubrique','rubrique_url_'.LG_DEF,mes($nom_menu));
			}
		}

		$this->row = $row_menu;
		$this->id_menu = $row_menu['rubrique_id'];
		$this->nom_menu = $row_menu['rubrique_url_'.LG_DEF];

		$this->cache2 = new GenCache('arbo'.$this->site->g_url->topRubId.'-'.$this->site->getCurId().'_'.$this->nom_menu,GetParam('date_update_arbo'));
		if(!$this->cache2->cacheExists()) {
			$this->genTab();
		}
	}


	/* Méthode qui renvoie la variable tabPrincipal */
	function getTab(){
		if(!$this->cache2->cacheExists())
			$this->cache2->saveCache($this->gen($this->tabPrincipal, 2));


		return $this->cache2->getCache();

	}


	/* Méthode qui génère le menu principal */
	function genTab(){
		global $rootId;

		if(!count($this->tabPrincipal))
			$this->tabPrincipal = $this->site->g_url->recursRub($this->id_menu,1,$this->conf['max_levels']);


	}



	/* Méthode qui génère un menu avec un un id racine et un tableau d'Url's pass� en param. */
	function gen($tab, $rootId=''){

		if(!$this->visible) {
			return;
		}

		$ulId = empty($rootId) ? '' : ' id="menu_' .$this->nom_menu .'"';
		$divid = empty($rootId) ? '' : ' id="div_menu_' .$this->nom_menu .'"';
		if($divid) {
			$html = '<div'.$divid.'><ul' .$ulId .'>';
		} else {
			$html = '<ul' .$ulId .'>';
		}
		if(!is_array($tab)  || !count($tab)) {
			return '';
		}

		$cpt = 1;
		$nbM = 0;
		$nbTot = count($tab);

		foreach ($tab as $key => $value){

			$nbM++;
			if(strlen($this->separator) && $cpt > 1) {
				$tpl = new genTemplate();

				$tpl->loadTemplate($this->tpl_name,$this->tpl_folder);
				$tpl->set('titre',$this->separator);
				$html .= $tpl->gen();
			}

			$tpl = new genTemplate();
			$tpl->loadTemplate('menu.item');


			if(!empty($rootId)) {
				if($this->conf['use_images']) {				
					$value['titre'] = $this->genImageMenu($value['titre'],$this->nom_menu,$cpt,$value['selected']).'';
				} else {
					$value['titre'] = ''.$value['titre'].'';					
				}
			}
			
		
			
			

			$style = ' class="';
			$style .= $value['selected'] ? 'selected ' : '';
			$style .= $nbM == $nbTot ? 'dernier ' : '';
			$style .= $nbM == 1 ? 'premier ' : '';
			$style .= $nbM != $nbTot && $nbM != 1 ? 'milieu ' : '';
			$style .= '" ';

			$style .= akev($value,'style');
			$tpl->set('lien',akev($value,'url'));
			$tpl->set('titre',akev($value,'titre'));
			$tpl->set('id',empty($rootId) ? ' id="ml'.akev($value,'id').'" ' : ' id="menu_' .$this->nom_menu .'_' .$cpt .'" ');
			$tpl->set('style',akev($value,'style'));
			$tpl->set('classa',akev($value,'selected') ? ' class="selected"  ' : '');
			$tpl->set('style',$style);
			//debug(akev($value,'selected']);

			if(is_array(akev($value,'sub'))) {
				$tpl->set('sub',$this->gen(akev($value,'sub')));
			}
			else if(akev($value,'selected') && $this->conf['open_selected']) {
				$sub = $this->site->g_url->recursRub(akev($value,'id'),1,1);
				$tpl->set('sub',$this->gen($sub));
			}

			$html .= $tpl->gen();

			$cpt++;



		}
		
		if($divid) {
			$html .='</ul></div>';
		} else {
			$html .='</ul>';
		}


		return $html;
	}

	/* Méthode qui permet d'ajouter un tableau d'Url's une nouvelle url et don titre */
	function addMenu($tab, $titre, $url,$style=''){
		/*
			Ajoute manuellement un element au menu $tab
			@tab Menu auquel ajouter (tabFooter,tabHeader,tabPrincipal)
		*/

		//debug_print_backtrace();
		$t = &$this->$tab;
		$t[$url]['titre'] = $titre;
		$t[$url]['url'] = $url;
		$t[$url]['sub'] = '';
		$t[$url]['style'] = $style;
	}

	
	
	function addConf($conf,$nb=0) {
	
		$val = $this->conf[$conf];
		if($nb) {
	
			$val = $this->conf[$conf][$nb-1];
	
		}
	
		if($this->conf[$conf]) {
			return '&amp;'.$conf.'='.$val;
		}
	
	}
	
	
	
	
	
	
	function genImageMenu($val,$nom_menu,$nb=0, $selected) {
	
	
		if($this->conf['caps']) {
			$val = majuscules($val);
		}
	
		$srcNormal = IMG_GENERATOR.'?text='.str_replace('-','%2d',urlencode($val));
	
	
	
		if(is_array($this->conf['profiles']) && $this->conf['profiles'][$nb-1]) {
			$srcNormal .= '&amp;profile='.$this->conf['profiles'][$nb-1];
			
		} else {
			$srcNormal .= $this->addConf('profile');
		}
		
		
		$srcNormal .= $this->addConf('width',$nb);
		$srcNormal .= $this->addConf('imgW',$nb);
	
		if($this->conf['rollover']) {
			$srcOver =  IMG_GENERATOR.'?text='.str_replace('-','%2d',urlencode($val));
			
			if(is_array($this->conf['rollovers']) && $this->conf['rollovers'][$nb-1]) {
				$srcOver .= '&amp;profile='.$this->conf['rollovers'][$nb-1];
			} else {
				$srcOver .= '&amp;profile='.$this->conf['rollover'];
			}
	
			$srcOver .= $this->addConf('width',$nb);
			$srcOver .= $this->addConf('imgW',$nb);
		}
	

		$return = '<img src="'.$srcNormal.'" ';
	
		if($srcOver) {
			$return .='	onmouseover="swapImage(\''.$srcOver.'\',this)" ';
		}
	
		$return .= ' alt="'.altify($val).'" />';
	
		//if($barre[$nom_menu][$nb] == 'oui') $return .= '<img src="/img/base/tirets_vert.gif" alt="" />';
	
	
		return $return;
	
		//}
	
	
	}



}



?>