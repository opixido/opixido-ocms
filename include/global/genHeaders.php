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
	 * Handles HTML Headers
	 *
	 * @param unknown_type $site
	 * @return genHeaders
	 */
	function genHeaders($site) {
		$this->site = $site;
	}
	

	/**
	 * Generates full headers
	 *
	 * @return unknown
	 */
	function gen() {

		global $_Gconfig;

		$tpl = new genTemplate();
		$tpl->loadTemplate('headers.html');

		$tpl->set('lg',$this->site->getLg());
				
		$tpl->set('title',altify($this->getTitle()));
		$tpl->set('keywords',altify($this->getMetaKeywords()));
		$tpl->set('desc',altify($this->getMetaDescription()));
		$tpl->set('headers',$this->getHtmlHeaders());
		$tpl->set('first_body',$this->firstBody);
		

		return $tpl->gen();

	}

	/**
	 * Adds a link to a javascript FILE in the JS folder
	 *
	 * @param string $name
	 * @param bool $addBase if FALSE you have to set the FULL path to the file in $name 
	 * @param string $tag additional parameters to add in the script tag
	 */
	function addScript($name,$addBase=true,$tag='') {
		if($addBase) {
			$this->addHtmlHeaders('<script '.$tag.' type="text/javascript" src="'.BU.'/js/'.$name.'" ></script>');
		} else {
			$this->addHtmlHeaders('<script '.$tag.' type="text/javascript" src="'.$name.'" ></script>');
		}
	}

	/**
	 * Adds text in first place to the body
	 *
	 * @param string $str
	 */
	function addFirstBody($str) {
		$this->firstBody .= $str;
	}

	/**
	 * Adds a link to a CSS FILE in the CSS folder
	 *
	 * @param string $name
	 */
	function addCss($name) {
		
		if(strpos($name,'/') !== false) {
			$this->addHtmlHeaders('<style type="text/css" media="screen"> @import "'.$name.'"; </style>');			
		} else {
			$this->addHtmlHeaders('<style type="text/css" media="screen"> @import "'.BU.'/css/'.$name.'"; </style>');
		}
	}

	/**
	 * Adds full CSS code in a tag
	 *
	 * @param string $value
	 */
	function addCssText($value) {

		$this->addHtmlHeaders('<style type="text/css"> '.$value.' </style>');
	}
	
	
	/**
	 * Fully replace the "title" of the page
	 *
	 * @param string $str
	 */
	function setTitle($str) {
		
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
	function addTitle($str) {
		if(trim($str)) {
			$this->title = $str.$this->titleSep.$this->title;
		}
	}

	/**
	 * Returns title
	 *
	 * @return unknown
	 */
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

