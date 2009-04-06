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


class genMessages {


	function genMessages() {

		global $specialUpload,$uploadRep;

		$this->messages = array();
	}



	function add($txt,$level='error') {
		$this->messages[$level][] = $txt;
	}
	
	function addInstant($txt,$level='error') {
		$this->genCss();
		p('
			<div id="genMessages">
			');
		$this->genMessage($txt,$level);
		p('</div>');
	}	

	function gen() {
		if(count($this->messages)) {
			
			$this->genCss();
			p('
			<div id="genMessages">
			');
			foreach($this->messages as $level=>$messages) {
				if(count($messages)) {

					foreach($messages as $msg) {
						$this->genMessage($msg,$level);
					}

				}
			}
			p('</div>');
		}
	}
	
	function genMessage($msg,$level) {
		
		p('<div onclick="this.style.display = \'none\';" class="genMessage_'.$level.'" >');
		p('<h2>&nbsp;</h2>');
		p('<h4>');
		if(is_array($msg) || is_object($msg)) {
			p('<pre>');
				print_r($msg);
			p('</pre>');
		} else {
			p($msg);
		}
		p('</h4>');
		p('</div>');
		
	}
	
	function genCss() {
		if(!$GLOBALS['genMessageCss']) {
			
		$GLOBALS['genMessageCss'] = true;
		
		p('
			<style type="text/css">

				#genMessages {
					position:absolute;
					left:50%;
					width:400px;
					margin-left:-200px;
					top:0px;
					z-index:500000000;
				}

				.genMessage_info {

					background:url('.ADMIN_URL.'/img/fond.bloc2.gif) #f5f6be;
					border-right:1px solid gray;
					border-bottom:1px solid gray;

					text-align:left;
					padding-left:50px;
					cursor:pointer;
				}

				.genMessage_info h4 {
					padding-top:10px;
				}

				.genMessage_info  h2{
					width:24px;
					height:24px;
					/*background:url('.t('src_message_info').');*/
					margin-left:-35px;
					float:left;
				}


				.genMessage_dev {

					/*background:url('.ADMIN_URL.'/img/fond.bloc2.gif) green;*/
					border-right:1px solid gray;
					border-bottom:1px solid gray;

					text-align:left;
					padding-left:50px;
					cursor:pointer;
				}

				.genMessage_dev h4 {
					padding-top:10px;
				}

				.genMessage_dev  h2{
					width:24px;
					height:24px;
				/*	background:url('.t('src_message_info').');*/
					margin-left:-35px;
					float:left;
				}

							
				/***** ERROR ****/

				.genMessage_error {

					background:url('.ADMIN_URL.'/img/fond.bloc2.gif) #cc0000;
					border-right:1px solid gray;
					border-bottom:1px solid gray;

					text-align:left;
					padding-left:50px;
					cursor:pointer;
				}

				.genMessage_error h4 {
					padding-top:10px;
					color:white;
				}
				.genMessage_error * {
					color:white;
				}
				.genMessage_error  h2{
					width:24px;
					height:24px;
					/*background:url('.t('src_message_error').');*/
					margin-left:-35px;
					float:left;

				}



			</style>');	
		}
	
	}


}


?>