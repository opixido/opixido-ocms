<?php


class genBoxAdmin {

	private $site;

	private $html;

	function __construct($site) {

		global $nbFrontAdmin;

		$this->site = &$site;

	}


	function gen() {

	/* onclick="if(gid(\'admin_box\').className==\'admin_box_open\' ) {gid(\'admin_box\').className=\'admin_box_close\'} else {gid(\'admin_box\').className=\'admin_box_open\'}"*/

		return '<div id="admin_box" class="admin_box_open">
		<div id="admin_open_close" onclick="if(gid(\'admin_box\').className==\'admin_box_open\' ) {gid(\'admin_box\').className=\'admin_box_close\'} else {gid(\'admin_box\').className=\'admin_box_open\'}" >Menu</div>

		'.$this->html.'</div>';

	}


	function add($str) {
		$this->html .= '<div>'.$str."</div>\n";
	}

}


?>