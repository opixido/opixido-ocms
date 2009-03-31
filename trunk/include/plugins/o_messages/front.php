<?php

/**
 * This file is part of oCMS.
 *
 * oCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * oCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with oCMS. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright opixido 2008
 * @link http://opixido.com
 * @package ocms
 */


/**
 * Class to handle errors and messages to PRINT TO THE USER
 * Don't use this to show your debugs or stuffs, 
 * use debug() instead ...
 *
 */
class o_messagesFront extends ocmsPlugin {
	
	
	/**
	 * Messages arrays
	 *
	 * @var array
	 */
	private $error = array();
	
	/**
	 * Messages arrays
	 *
	 * @var array
	 */	
	private $info = array();
	
	/**
	 * Defines if messages have already been generated or not
	 *
	 * @var boolean
	 */
	private $generated = false;
	
	
	function afterInit() {

		$this->site->g_headers->addCssText('
			.o_messages {text-align:center;font-size:1.2em;font-weight:bold;padding:3px;margin:3px;-moz-border-radius:10px}
			.o_messages_info{}
			');
		
	}
	
	
	/**
	 * Adds a message to the ERRORS stack
	 * MUST BE CALLED BEFORE GEN !
	 * COZ ALL THIS WILL BE PRINTED THEN 
	 * 
     * @param string $str
	 */
	function addError($str) {
		
		/**
		 * Duplicate message ?
		 */
		if(false && @in_array($str,$this->error)) {
			return;
		}
		
		$this->error[] = $str;
		
		/**
		 * Already printed ?
		 * Dirty  Workaround ...
		 */
		if($this->generated) {
			$this->errorGen($str,'error');
		} 
		
	}
	
	/**
	 * Adds a message to the INFO stack
	 * MUST BE CALLED BEFORE GEN !
	 * COZ ALL THIS WILL BE PRINTED THEN 
	 * 
	 * @param string $str
	 */
	function addInfo($str) {
		
		/**
		 * Duplicate message ?
		 */
		if(@in_array($str,$this->info)) {
			return;
		}
		
		$this->info[] = $str;
		
		/**
		 * Already printed ?
		 * Dirty  Workaround ...
		 */		
		if($this->generated) {
			$this->errorGen($str,'info');
		}
	}
	
	/**
	 * Generates all the arrays
	 *
	 * @return string HTML
	 */
	function gen() {
		
		$this->generated = true;
		$html = '<div id="o_messages">';
		$html .= $this->genMessages($this->error,'error');
		$html .= $this->genMessages($this->info,'info');
		$html .= '</div>';
		
		
		return $html;
	}
	
	function genA() {
		if(!$this->generated) {
			return $this->genBeforePara();
		}
	}
	
	
	/**
	 * Show error for devs when a message is added AFTER the GEN method !
	 *
	 */
	function errorGen($str,$css='') {
		
		/**
		 * Already printed ?
		 * Dirty JS Workaround ...
		 */
		echo '<script type="text/javascript"> SafeAddOnload(function(){gid("o_messages").innerHTML += '.sql(str_replace(array("\n","\r"),"",($this->genMessages($str,$css)))).';});</script>';
		return;
		devbug('_o_messages_message_added_after_gen');
		devbug($str);		
		
	}
	
	/**
	 * generate HTML code for an array
	 * Creates a div with class $css and "o_messages"
	 *
	 * @param mixed $msgs string or array
	 * @param string $css className for the div
	 * @return string HTML
	 */
	function genMessages($msgs,$css='') {
		
		/**
		 * If $msgs is just a single message ...
		 */
		if(!is_array($msgs)) {
			$msgs = array($msgs);
		}

		$html = '';
		
		/**
		 * Looping all the messages
		 */
		foreach($msgs as $v) {
			/**
			 * If value isn't a string ... trying to show something ...
			 */
			if(is_array($v) || is_object($v)) {
				$v = var_export($v,true);
			}
			/**
			 * HTML message
			 */
			$html .= '<div class="o_messages o_messages_'.$css.'">'.nl2br($v).'</div>';
		}
		
		
		return $html;
	}
	
}

/**
 * Alias for the addInfo method of the o_messages plugin
 *
 * @param mixed $str string or array
 */
function addMessageInfo($str) {
	$GLOBALS['site']->plugins['o_messages']->addInfo($str);
}


/**
 * Alias for the addError method of the o_messages plugin
 *
 * @param mixed $str string or array
 */
function addMessageError($str) {
	
	$GLOBALS['site']->plugins['o_messages']->addError($str);
}
