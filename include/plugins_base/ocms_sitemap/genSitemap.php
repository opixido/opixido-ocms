<?php

class genSiteMap extends row {

	private $html;

	/**
	 * gensite
	 *
	 * @var gensite
	 */
	private $site;
	
	public $showHome = true;
	public function __construct($site, $params) {

		$this->site = &$site;
		//debug($this->arbo);
		
		$this->params = SplitParams($params);

		$this->site->g_headers->addCss('sitemap.css');

		$this->cache = new genCache('sitemap'.$this->params['siteroot'],getParam('date_update_arbo'));

		//$this->site->g_rubrique->plugins['navigation']->setVisible(false);

	}

	public function afterInit() {

	}


	public function gen() {
		
	
		if($this->site->isCurrent404) {

			trySql("INSERT INTO s_404 VALUES ('','".mes('http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"])."','".mes($_SERVER['HTTP_REFERER'])."')");
			
			//$this->html .=  t('info_404');
			
		}

		$this->count = 1 ;

		if(/*$this->cache->cacheExists()*/0) {

			return $this->html.$this->cache->getCache();

		} else {
			
			
			
			$this->html .= ('
			<div id="sitemap">
			
			
			');
			
//			if($this->showHome) {
//				$this->html .= '
//				<br/>
//				<ul class="sitemap">
//				<li class="level1"><div class="level1"><a href="'.getUrlFromId(getRubFromGabarit('genHome')).'">'.t('accueil').'</a></div></li>
//				</ul>
//				';
//			}

			$menus = $this->params['siteroot'] ? $this->site->getMenus($this->params['siteroot']) : $this->site->getMenus();

			//$menus = array(0=>array('rubrique_id'=>472),1=>array('rubrique_id'=>474),2=>array('rubrique_id'=>626));
			
			
			foreach($menus as $row) {			
				
				if($row['rubrique_id'] == 17)
				{
					$this->html .= '<ul class="sitemap">';
					$this->arbo = $this->site->g_url->recursRub($row['rubrique_id']);
					
					//$this->arbo = $this->site->g_url->recursRub(17);
					$this->recursRub($this->arbo);
					$this->html .= '</ul>';
				}				
	
			}
			$this->html .= ('			
			</div>
			');
			
			$this->cache->saveCache($this->html);
			return $this->html;
		}
	}
	
	public function genRight() {
		
		$this->count = 1 ;

		if(/*$this->cache->cacheExists()*/0) {

			return $this->html.$this->cache->getCache();

		} else {

			$this->html = ('<div id="sitemap">');
			$menus_droite = $this->params['siteroot'] ? $this->site->getMenus($this->params['siteroot']) : $this->site->getMenus();
			
			foreach($menus_droite as $row) 
			{			
				
				if($row['rubrique_id'] != 17)
				{
					$this->html .= '<ul class="sitemap">';
					$this->arbo = $this->site->g_url->recursRub($row['rubrique_id']);
					//$this->arbo = $this->site->g_url->recursRub(17);
					$this->recursRub($this->arbo);
					$this->html .= '</ul>';
				}				

			}
			$this->html .= ('</div>');
			
			
			$this->cache->saveCache($this->html);
			return $this->html;
		}
	}

	
	
	private function recursRub($array, $level='1', $rootRub='1') {

		if(!is_array($array) || !count($array)) {
			return '';
		}
		$k = 0;
		$tot = count($array);
		foreach($array as $page) {
			$k++;
			
			//Récupération de la couleur de chaque rubrique
			$sql = "SELECT rubrique_color FROM s_rubrique WHERE rubrique_id = '".$page['id']."'";
			$res = GetSingle($sql);
			
			$couleur = $res['rubrique_color'];
							
			$this->html .= '<li '.$color.' class="level' .$level .' nb' .$rootRub .' '.($k==1?'premier':'').' '.($k == $tot ? 'dernier' : '').'">';			
			
			$color= '';					
			
			$this->html .= '<div class="level' .$level .'"><a style="color:#'.$couleur.'" href="'.$page['url'].'">'.$page['titre'].'</a></div>';

			if(count($page['sub']) && $level != 3) {
				$this->html .= '<ul>';
				$this->recursRub($page['sub'], $level+1, $rootRub);
				$this->html .= '</ul>';
			}

			$this->html .= '</li>'."\n" ;
			

			if($level == 1) {
				
				if ( $this->count % 3 === 0 ) {

					//$this->html .= '</ul><div class="clearer">&nbsp;</div><ul class="plan_site">' ;

				}
			
				$rootRub++;
				$this->count++ ;
			}

			
		}
	}
	
		
	function ocms_getPicto() {
		
		return ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/actions/media-eject.png';
	}
	
}

