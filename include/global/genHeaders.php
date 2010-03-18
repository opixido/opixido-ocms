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



class genHeaders {

	private $title;
	private $meta_keywords;
	private $meta_description;
	private $html_headers;
	public $titleSep = ' - ';
	public $firstBody = '';
	
	/**
	 * Contenu des CSS en ligne
	 * @var string
	 */
	public $cssTexts = '';

	/**
	 * Liste des fichiers CSS
	 * @var array
	 */
	public $cssFiles = array();
	
	/**
	 * Liste des fichiers JS
	 * @var array
	 */
	public $jsFiles = array();
		
	/**
	 * Durée du cache
	 * @var int
	 */
	public $fCacheTime = 86400;

	/**
	 * Dossier de cache
	 * @var string
	 */
	public $fCacheFolder = 'c';
	
	
	/**
	 * Handles HTML Headers
	 *
	 * @param unknown_type $site
	 * @return genHeaders
	 */
	public function __construct($site) {
		$this->site = $site;
		$this->cssFiles['global'][] = BU.'/css/base.css';
		$this->jsFiles['global'][] = BU.'/js/base.js';
	}
	

	/**
	 * Generates full headers
	 *
	 * @return unknown
	 */
	public function gen() {

		global $_Gconfig;

		$tpl = new genTemplate();
		$tpl->loadTemplate('headers.html');

		/**
		 * Compression et Cache des CSS
		 */
		if(count($this->cssFiles)) {
			
			$preHeaders = $this->html_headers;
			$this->html_headers = '';
			
			foreach($this->cssFiles as $k => $v) {			
				
				$pathCss = $this->getCssPath($v);
				/**
				 * Un seul CSS mis en cache
				 */
				$this->html_headers .= '<style type="text/css" media="screen"> /*'.$k.'-'.implode('-',$v).'*/ @import "'.$pathCss.'"; </style>'."\n";

			}
			$this->html_headers .= $preHeaders;
		}
		
		/**
		 * Compression et Cache des JS
		 */
		if(count($this->jsFiles)) {
			
			foreach($this->jsFiles as $k=>$v) {
				
				$jsF = $this->getJsPath($v);
				/**
				 * Un seul js mis en cache
				 */
				$this->addHtmlHeaders('<script type="text/javascript" src="'.$jsF.'"></script>');
			}
			
		}		

		/**
		 * Compression des CSS en ligne
		 */
		if(strlen($this->cssTexts)) {
			$this->addHtmlHeaders('<style type="text/css"> '.compressCSS($this->cssTexts).' </style>');
		}
		
		$tpl->set('lg',$this->site->getLg());
				
		$tpl->set('title',altify($this->getTitle()));
		$tpl->set('keywords',altify($this->getMetaKeywords()));
		$tpl->set('desc',altify($this->getMetaDescription()));
		$tpl->set('headers',$this->getHtmlHeaders());
		$tpl->set('first_body',$this->firstBody);
		

		return $tpl->gen();

	}
	
	
	public function getJsPath($fichiers) {
		
		/**
		 * Chemin du cache des js 
		 */
		$jsCacheName = ''.md5(implode('_',$fichiers)).'.js';
		$jsCache = new genCache($jsCacheName,(time() - ($this->fCacheTime) ),$_SERVER['DOCUMENT_ROOT'].BU.'/'.$this->fCacheFolder.'/');
					
		if(!$jsCache->cacheExists()) {				
			/**
			 * Si le cache est expiré ou inexistant
			 * On récupère le contenu de toutes les js
			 */		
			$j = new ECMAScriptPacker();					
			foreach($fichiers as $v) {
				$jj = @file_get_contents('.'.$v);
				if(!$jj) {
					devbug('Missing JS : '.$v);
				}
				/**
				 * Si déjà compressé on ne touche pas
				 */
				
				$js .= "\n//".$v."\n";
				
				if(substr($jj,0,10) == '/*packed*/') {
					$js .= $jj."\n";
				} else {
					$js .= $j->pack($jj)."\n";
				}			
			}
			/**
			 * On les compresse
			 */
			
			
			
			/**
			 * Ajout des déjà compressés
			 */
			//$js .= "\n".$jsEnd;			
			
			/**
			 * Et on sauvegarde
			 */
			$jsCache->saveCache($js);
		}
		
		return BU.'/'.$this->fCacheFolder.'/'.$jsCacheName;
			
	}
	
	
	
	public function getCssPath($fichiers) {
		
		if(!is_array($fichiers)) {
			$fichiers = array($fichiers);
		}

		/**
		 * Chemin du cache des CSS 
		 */
		if(count($fichiers) == 1) {
			$cssCacheName = nicename($fichiers[0]).'.css';
		} else {
			$cssCacheName = ''.md5(implode('_',$fichiers)).'.css';
		}
		$cssCache = new genCache($cssCacheName,(time() - ($this->fCacheTime) ),$_SERVER['DOCUMENT_ROOT'].BU.'/'.$this->fCacheFolder.'/');
					
		if(!$cssCache->cacheExists()) {
			/**
			 * Si le cache est expiré ou inexistant
			 * On récupère le contenu de toutes les CSS
			 */				
			foreach($fichiers as $v) {
				if($csT = file_get_contents('.'.$v)) {
					$css .= $csT."\n";
				} else {
					devbug('Cant load CSS : '.$v);
				}
			}
			/**
			 * On les compresse
			 */
			$css = compressCss($css);
			
			/**
			 * Et on sauvegarde
			 */
			$cssCache->saveCache($css);
		}
		
		return BU.'/'.$this->fCacheFolder.'/'.$cssCacheName;
		
	}

	/**
	 * Adds a link to a javascript FILE in the JS folder
	 *
	 * @param string $name
	 * @param bool $addBase if FALSE you have to set the FULL path to the file in $name 
	 * @param string $tag additional parameters to add in the script tag
	 */
	public function addScript($name,$addBase=true,$group='page') {

		if($addBase) {
			$name =  '/js/'.$name;
			//$this->addHtmlHeaders('<script '.$tag.' type="text/javascript" src="'.BU.'/js/'.$name.'" ></script>');
		} 
		if($group) {
			$this->jsFiles[$group][] = $name;
		}
		else {
			if($addBase) {
				$name = BU.$name;
			}
			$this->addHtmlHeaders('<script  type="text/javascript" src="'.$name.'" ></script>');
		}
	}

	/**
	 * Adds text in first place to the body
	 *
	 * @param string $str
	 */
	public function addFirstBody($str) {
		$this->firstBody .= $str;
	}

	/**
	 * Adds a link to a CSS FILE in the CSS folder
	 *
	 * @param string $name
	 */
	public function addCss($name,$group='page') {

		if(strpos($name,'/') === false) {
			$name = BU.'/css/'.$name;
			//$this->addHtmlHeaders('<style type="text/css" media="screen"> @import "'.$name.'"; </style>');			
		} 
		
		if($group) {
			$this->cssFiles[$group][] = $name;
		} 
		else {
			$this->addHtmlHeaders('<style type="text/css" media="screen"> @import "'.$this->getCssPath($name).'"; </style>');	
		}
	}

	/**
	 * Adds full CSS code in a tag
	 *
	 * @param string $value
	 */
	public function addCssText($value) {
		$this->cssTexts .= $value."\n\n";		
	}
	
	
	/**
	 * Fully replace the "title" of the page
	 *
	 * @param string $str
	 */
	public function setTitle($str) {
		
		if($str) {
			$str = ucfirst($str);
			$this->title = $str.$this->titleSep.t('base_title');
		} else {
			$this->title = t('base_title');
		}

	}
	
	/**
	 * Adds a new "level" to the current title
	 *
	 * @param string $str
	 */
	public function addTitle($str) {
		if(trim($str)) {
			$this->title = $str.$this->titleSep.$this->title;
		}
	}

	/**
	 * Returns title
	 *
	 * @return unknown
	 */
	public function getTitle() {
		
		return $this->title;
	}

	public function setMetaKeywords($str) {
		$this->meta_keywords = $str;
	}

	public function getMetaKeywords() {
		return $this->meta_keywords;
	}

	public function setMetaDescription($str) {
		$this->meta_description = $str;
	}

	public function getMetaDescription() {
		return $this->meta_description;
	}

	public function addHtmlHeaders($str) {
		$this->html_headers .= "\n".$str."\n";
	}

	public function getHtmlHeaders() {
		return $this->html_headers;
	}

}

