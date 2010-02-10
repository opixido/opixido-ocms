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

class genBase {


	/* Sous dossiers de configuration */

	public $include_path;
	private $include_admin_path = 'admin';
	private $include_global_path = 'global';
	private $include_config_path = 'config';
	private $include_actions_path = 'actions';
	private $include_admin_html = 'admin_html';	
	private $include_base_path = 'base';
	private $cacheLoadedFiles = array();

	public function __construct() {
		ini_set('short_open_tags','on');
		$this->getBasePath();

	}

	
	/**
	 * Tries to get the full path of the "include" folder
	 *
	 */
	private function getBasePath() {

		$inc_files = get_included_files();
		foreach($inc_files as $file) {
			if(basename($file) == "include.php") {
				$this->include_path = dirname($file);
				break;
			}
		}

		define('gen_include_path',$this->include_path);

	}

	/**
	 * Includes all needed files for the Admin section
	 *
	 */
	public function includeAllForAdmin() {
		
		$this->includeConfig();
		$this->includeBase();
		$this->includeGlobal();
		global $genMessages;
		$genMessages = new genMessages();
		$this->includeAdmin();
	}
	
	/**
	 * Includes all the files in the global folder
	 *
	 * @return bool
	 */
	public function includeGlobal() {

		/**
		 * "Global" folder
		 */
		$curfolder = $this->include_path.'/'.$this->include_global_path;
		
		$list = $this->getFileListing($curfolder);

		/**
		 * Including
		 */
		foreach($list as $file) {
			
			include_once($curfolder.'/'.$file);
			
		}
		
		/**
		 * Global classes
		 */
		$curfolder = $this->include_path.'/classes';
		$list = $this->getFileListing($curfolder);
	
		foreach($list as $file) {
			
			include_once($curfolder.'/'.$file);
			
		}

			
		return true;

	}
	
	/**
	 * Includes the "base" folder
	 *
	 * @return unknown
	 */
	public function includeBase() {
		
		
		$curfolder = $this->include_path.'/'.$this->include_base_path;
		$list = $this->getFileListing($curfolder);

		foreach($list as $file) {
			
			include_once($curfolder.'/'.$file);
			
		}
		
		return true;

	}

	/**
	 * Includes all the config files
	 *
	 * THIS DOES NOT INCLUDE ALL FILES IN THE FOLDER
	 * ONLY SPECIFIED ONES
	 * 
	 * @return bool
	 */
	public function includeConfig() {
		
		$curfolder = $this->include_path.'/'.$this->include_config_path;

		$list = array('config.server.php','connect.php','config.base.php','config.app.php');

		foreach($list as $file) {			
			$res = @include_once($curfolder.'/'.$file);
			if(!$res) {
				echo ('File : '.$file.' not included yet : Install Mode');
			}
		}
		
		return true;
	}

	/**
	 * Includes the "admin" folder
	 *
	 * @return bool
	 */
	public function includeAdmin() {
		
		$curfolder = $this->include_path.'/'.$this->include_admin_path;
		$list = $this->getFileListing($curfolder);

		foreach($list as $file) {
			include_once($curfolder.'/'.$file);
		}

		return true;
	}

	/**
	 * Lists all files in a folder
	 * 
	 *
	 * @param unknown_type $folder
	 * @param unknown_type $usecache
	 * @return unknown
	 */
	public function getFileListing($folder,$usecache=true) {

		/**
		 * Cache in session
		 */
		$cachename = 'cache_'.md5($_SERVER['SCRIPT_FILENAME']);
		if(array_key_exists($cachename,$_SESSION) && array_key_exists('gb_folderList',$_SESSION[$cachename]) && array_key_exists($folder,$_SESSION[$cachename]['gb_folderList']) && $usecache)
		{
			return $_SESSION[$cachename]['gb_folderList'][$folder];
		}
		
		/**
		 * If folder exists
		 */
		if(is_dir($folder)) {
			$list = scandir($folder) or die('Wrong configuration can\'t include anything : '.$folder);
			$nlist = array();
			foreach($list as $file)
			{
				if(substr($file,0,1) != '.') {
					if(is_file($folder.'/'.$file)) {
						$nlist[] = $file;
					}
				}
			}
			$_SESSION[$cachename]['gb_folderList'][$folder] = $nlist;
			return $nlist;
		} else {
			die('Wrong configuration can\'t include anything : '.$folder);
		}


	}
	
	/**
	 * Lists all folders in a folder
	 *
	 * @param string $folder
	 * @return unknown
	 */
	public function getFolderListing($folder) {
		
		$folder = path_concat($this->include_path,$folder);
		
		if(is_dir($folder)) {
			$list = scandir($folder) or die('Wrong configuration can\'t include anything : '.$folder);
			$nlist = array();
			foreach($list as $file)
			{
				if(substr($file,0,1) != '.') {
					if(is_dir($folder.'/'.$file)) {
						$nlist[] = $file;
					}
				}
			}
			$_SESSION['cache_'.UNIQUE_SITE]['gb_folderList'][$folder] = $nList;
			return $nlist;
		} else {
			debug('no such directory : '.$folder);
			return false;
		}
		
	}


	/**
	 * Includes a specific file
	 *
	 * @param string $file Filename
	 * @param string $fold Foldername (after include/)
	 * @return bool
	 */
	public function includeFile($file,$fold) {

		$folder = '';
		if(property_exists($this,'include_'.$fold)) {
			$folder = $this->{'include_'.$fold};
		}
		if(!strlen($folder)) {
			$folder = $fold;
		}
		
		$path = $this->getIncludePath($file,$folder);

		if(is_file($path)) {
			$ret = include_once($path);
			return $ret;
		}
		return false;

	}


	/**
	 * Returns the full path for a file in a folder
	 *
	 * @param string $file
	 * @param string $fold
	 * @return string
	 */
	public function getIncludePath($file,$fold) {
		return $this->include_path.'/'.$fold.'/'.$file;
	}

	
	/**
	 * Load file content in array and cache
	 *
	 * @param unknown_type $file
	 * @param unknown_type $fold
	 * @return unknown
	 */
	public function loadFile($file,$fold) {

		$folder = '';
		
		if(property_exists($this,'include_'.$fold)) {
			$folder = $this->{'include_'.$fold};
		} 
		if(!strlen($folder)) {
			$folder = $fold;
		}
		$path = $this->getIncludePath($file,$folder);
		if(!ake($this->cacheLoadedFiles,$path)) {
			if(is_file($path)) {
				$this->cacheLoadedFiles[$path] = file_get_contents($path);
			}
		}

		return $this->cacheLoadedFiles[$path];

		return false;

	}



}




?>