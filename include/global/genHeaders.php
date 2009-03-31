<?php


class genHeaders {

	private $title;
	private $meta_keywords;
	private $meta_description;
	private $html_headers;
	public $titleSep = ' - ';
	public $firstBody = '';


	function genHeaders($site) {
		$this->site = $site;
		$this->showSubNavLink = false;
		if(stristr($_SERVER['HTTP_USER_AGENT'],'MSIE')) {			
			$this->addCss('ie.css');
		}
	}
	


	function gen() {


		$tpl = new genTemplate();
		$tpl->loadTemplate('headers.html');

		$tpl->set('lg',$this->site->getLg());
		$tpl->set('other_lg',$this->site->getOtherLg());
		$tpl->set('other_url',$this->site->g_url->getUrlForOtherLg());
		$tpl->set('other_version',altify(t('change_lg_'.$this->site->getLg())));
		$tpl->set('title',altify($this->getTitle()));
		$tpl->set('keywords',altify($this->getMetaKeywords()));
		$tpl->set('desc',altify($this->getMetaDescription()));
		$tpl->set('headers',$this->getHtmlHeaders());
		$tpl->set('first_body',$this->firstBody);
		
		if($this->showSubNavLink) {
			$tpl->set('sub_navigation','<a href="#sous_navigation" >'.t('acces_sub_nav').'</a> | ');
		}

		return $tpl->gen();

	}

	function addSubNavLink() {
		$this->showSubNavLink = true;
	}

	function addScript($name,$addBase=true,$tag='') {
		if($addBase) {
			$this->addHtmlHeaders('<script '.$tag.' type="text/javascript" src="'.BU.'/js/'.$name.'" ></script>');
		} else {
			$this->addHtmlHeaders('<script '.$tag.' type="text/javascript" src="'.$name.'" ></script>');
		}
	}

	function addFirstBody($str) {
		$this->firstBody .= $str;
	}

	function addCss($name) {
		
		if(strpos($name,'/') !== false) {
			$this->addHtmlHeaders('<style type="text/css"> @import "'.$name.'"; </style>');			
		} else {
			$this->addHtmlHeaders('<style type="text/css"> @import "'.BU.'/css/'.$name.'"; </style>');
		}
	}

	function addCssText($value) {

		$this->addHtmlHeaders('<style type="text/css"> '.$value.' </style>');
	}
	
	
	function setTitle($str) {
		
		if($str) {
			$str = ucfirst($str);
			$this->title = $str.$this->titleSep.t('base_title');
		} else {
			$this->title = t('base_title');
		}
	
		#$this->title = $str.$this->titleSep;
		#TITRE ICI
	}
	
	function addTitle($str) {
		if(trim($str)) {
			$this->title = $str.$this->titleSep.$this->title;
		}
	}

	function getTitle() {
		
		return $this->title;
	}

	function setMetaKeywords($str) {
		$this->meta_keywords = $str;
	}

	function getMetaKeywords() {
		return $this->meta_keywords;
	}

	function setMetaDescription($str) {
		$this->meta_description = $str;
	}

	function getMetaDescription() {
		return $this->meta_description;
	}

	function addHtmlHeaders($str) {
		$this->html_headers .= "\n".$str."\n";
	}

	function getHtmlHeaders() {
		return $this->html_headers;
	}

}



?>