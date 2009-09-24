<?php


class genForumPage extends ocmsGen  {
	
	
	
	
	function afterInit() {
		
		$this->site->g_headers->addCss('forum.css');
		
		
	}
	
	function genbeforepara() {
		
		return genForumHead();
		
	}
	
	
	
}