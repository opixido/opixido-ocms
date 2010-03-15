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


class genMenu{

	/* Proprietes de la classe genMenu */
	private $site;			//Objet de type site
	private $tabHeader;		//Tableau contenant les url's du menu topright
	public $tabPrincipal;	//Celui contenant les url's du menu principal
	private $tabFooter;		//Celui contenant les url's du menu du footer
	public $separator;
	public $visible = true;
	public $tpl_name = 'menu.item';
	public $tpl_folder = '';
	private $level = 1;

	/**
	 * Menu creation
	 *
	 * @param gensite $site
	 * @param string $nom_menu string ID/name of the menu
	 * @param int $id_menu numerical ID of the menu
	 * @param array $row_menu s_rubrique row of the menu
	 * @return genMenu
	 */
	function genMenu($site,$nom_menu='',$id_menu=0,$row_menu=array()){
		global $headRootId;
		global $rootId;
		global $footRootId;
		global $_Gconfig;

		$this->site = $site;

		/**
		 * Concats menus
		 */
		if(ake($_Gconfig['menus'],$nom_menu)) {
			$this->conf = $_Gconfig['menus'][$nom_menu];
		} else {
			$this->conf = $_Gconfig['menus']['__default__'];
		}
		
				
		/**
		 * If no $row, selecting it in database
		 */
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

		
		/**
		 * Cache for menu
		 */
		
		$this->cache2 = new GenCache('arbo'.md5($_ENV['REQUEST_URI'].'-'.$this->site->g_url->topRubId.'-'.$this->site->getCurId().'_'.$this->nom_menu),GetParam('date_update_arbo'));
		if(!$this->cache2->cacheExists()) {
			$this->genTab();
		}
	}


	/**
	 * Returns full HTML menu
	 *
	 * @return string
	 */
	function getTab(){
		if(!$this->cache2->cacheExists())
			$this->cache2->saveCache($this->gen($this->tabPrincipal, 2));
		
		return $this->cache2->getCache();

	}


	/**
	 * Generates HTML Menu
	 *
	 */
	function genTab(){
		global $rootId;

		if(!count($this->tabPrincipal))
			$this->tabPrincipal = $this->site->g_url->recursRub($this->id_menu,1,$this->conf['max_levels']);
			


	}



	/**
	 * Loops the items of the menu
	 * 
	 *
	 * @param array $tab
	 * @param int $rootId
	 * @return unknown
	 */
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
                                } else if($this->conf['use_premade_images']) {
                                        $value['titre'] = '<img src="'.BU.'/img/menu_'.$this->nom_menu.'/'.$cpt.($value['selected'] ? '-roll':'').'.png" alt='.alt($value['titre']).' />';
                                } else {
					$value['titre'] = $value['titre'].'';					
				}
			}
			
		
			
			

			$style = ' class="';
			$style .= $value['selected'] ? 'selected ' : '';
			$style .= $nbM == $nbTot ? 'dernier ' : '';
			$style .= $nbM == 1 ? 'premier ' : '';
			$style .= $nbM != $nbTot && $nbM != 1 ? 'milieu ' : '';
			$style .= '" ';

			if(!akev($value,'id')) {
				$value['idmenu'] = nicename($value['titre']);
			} else {
				$value['idmenu'] = $value['id'];
			}
			
			$style .= akev($value,'style');
			$tpl->set('lien',getLgUrl(akev($value,'url')));
			$tpl->set('titre',akev($value,'titre'));
			$tpl->set('id',empty($rootId) ? ' id="ml'.akev($value,'idmenu').'" ' : ' id="menu_' .$this->nom_menu .'_' .$cpt .'" ');
			$tpl->set('style',akev($value,'style'));
			$tpl->set('classa',akev($value,'selected') ? ' class="selected"  ' : '');
			$tpl->set('style',$style);
			$tpl->set('level',$this->level);
			//debug(akev($value,'selected']);
			$this->level++;
			
			if(is_array(akev($value,'sub'))) {
				$tpl->set('sub',$this->gen(akev($value,'sub')));
			}
			
			else if(akev($value,'selected') && $this->conf['open_selected'] && $this->conf['max_open_selected']  >= $this->level) {
				$sub = $this->site->g_url->recursRub(akev($value,'id'),1,1);
				
				$tpl->set('sub',$this->gen($sub));
				
			}
			$this->level--;
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

	/**
	 * Adds an element to the menu
	 *
	 * @param unknown_type $tab
	 * @param unknown_type $titre
	 * @param unknown_type $url
	 * @param unknown_type $style
	 */
	function addMenu($tab, $titre, $url,$style=''){
		/*
			Ajoute manuellement un element au menu $tab
			@tab Menu auquel ajouter (tabFooter,tabHeader,tabPrincipal)
		*/

		$t = &$this->$tab;
		$t[$url]['titre'] = $titre;
		$t[$url]['url'] = $url;
		$t[$url]['sub'] = '';
		$t[$url]['style'] = $style;
	}

	
	/**
	 * Changes configuration options of the menu
	 *
	 * @param string $conf name of option
	 * @param unknown_type $nb
	 * @return unknown
	 */
	function addConf($conf,$nb=0) {
	
		$val = $this->conf[$conf];
		if($nb) {	
			$val = $this->conf[$conf][$nb-1];	
		}
	
		if($this->conf[$conf]) {
			return '&amp;'.$conf.'='.$val;
		}
	
	}


	
	
	
	/**
	 * Generates images for the menu
	 *
	 * @param unknown_type $val
	 * @param unknown_type $nom_menu
	 * @param unknown_type $nb
	 * @param unknown_type $selected
	 * @return unknown
	 */
	function genImageMenu($val,$nom_menu,$nb=0, $selected) {
	
	
		if($this->conf['caps']) {
			$val = majuscules($val);
		}
	
		//$srcNormal = IMG_GENERATOR.'?nb='.$nb.'&text='.str_replace('-','%2d',urlencode($val));
		$srcNormal = 'nb='.$nb;
	
	
		if(is_array($this->conf['profiles']) && $this->conf['profiles'][$nb-1]) {
			$srcNormal .= '&amp;profile='.$this->conf['profiles'][$nb-1];
			
		} else {
			$srcNormal .= $this->addConf('profile');
		}
		
		
		$srcNormal .= $this->addConf('width',$nb);
		$srcNormal .= $this->addConf('imgW',$nb);
		
		if($selected) {
			$srcNormal .= '&textColor='.$GLOBALS['menu_'.$this->nom_menu.'_'.$nb];
			
		}
	
		if($this->conf['rollover']) {
			$srcOver =  IMG_GENERATOR.'?text='.str_replace('-','%2d',urlencode($val));
			
			if(is_array($this->conf['rollovers']) && $this->conf['rollovers'][$nb-1]) {
				$srcOver .= '&amp;profile='.$this->conf['rollovers'][$nb-1];
			} else {
				$srcOver .= '&amp;profile='.$this->conf['rollover'];
			}
			
	
			$srcOver .= $this->addConf('width',$nb);
			$srcOver .= $this->addConf('imgW',$nb);
			//$srcOver .= $this->addConf('b',$nb);
		}
	
		$srcNormal = getImgTextSrc($val,'',$srcNormal);
		
		$return = '<img src="'.$srcNormal.'" ';
	
		if($srcOver) {
			$return .='	onmouseover="swapImage(\''.$srcOver.'\',this)" ';
		}
	
		$return .= ' alt='.alt($val).' />';
	
		//if($barre[$nom_menu][$nb] == 'oui') $return .= '<img src="/img/base/tirets_vert.gif" alt="" />';
	
	
		return $return;
	
		//}
	
	
	}



}

