<?php


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

		$this->getBasePath();

	}

	

	private function getBasePath() {

		/* Pour recuperer le dossier des includes, on parcourt les fichiers inclus, et on inclus ensuite ce qu'il faut */
		$inc_files = get_included_files();
		foreach($inc_files as $file) {
			if(basename($file) == "include.php") {
				$this->include_path = dirname($file);
				break;
			}
		}

		define('gen_include_path',$this->include_path);

	}

	public function includeAllForAdmin() {
		
		$this->includeConfig();
		$this->includeBase();
		$this->includeGlobal();
		global $genMessages;
		$genMessages = new genMessages();
		$this->includeAdmin();
	}
	
	public function includeGlobal() {
		/* Inclu automatiquement, les fichiers du repertoire de base */
		
		$curfolder = $this->include_path.'/'.$this->include_global_path;
		
		$list = $this->getFileListing($curfolder);

		foreach($list as $file) {
			
			include_once($curfolder.'/'.$file);
			
		}
		
		$curfolder = $this->include_path.'/classes';
		$list = $this->getFileListing($curfolder);

		foreach($list as $file) {
			
			include_once($curfolder.'/'.$file);
			
		}

			
		return true;

	}
	
	public function includeBase() {
		/* Inclu automatiquement, les fichiers du repertoire de base */
		
		$curfolder = $this->include_path.'/'.$this->include_base_path;
		$list = $this->getFileListing($curfolder);

		foreach($list as $file) {
			
			include_once($curfolder.'/'.$file);
			
		}

			
		return true;

	}

	public function includeConfig() {
		/* Inclu automatiquement, les fichiers du repertoire de base */
		$curfolder = $this->include_path.'/'.$this->include_config_path;


		/*
		$list = $this->getFileListing($curfolder);
		*/
		$list = array('config.server.php','connect.php','config.base.php','config.app.php');

		foreach($list as $file) {			
			$res = @include_once($curfolder.'/'.$file);
			if(!$res) {
				echo ('File : '.$file.' not included yet : Install Mode');
			}
		}
		
		return true;
	}

	public function includeAdmin() {
		/* Inclu automatiquement, les fichiers du repertoire de base */
		$curfolder = $this->include_path.'/'.$this->include_admin_path;
		$list = $this->getFileListing($curfolder);

		foreach($list as $file) {
			include_once($curfolder.'/'.$file);
		}

		return true;
	}

	public function getFileListing($folder,$usecache=true) {

		/* retourne la liste d'un repertoire */
		$cachename = 'cache_'.md5($_SERVER['SCRIPT_FILENAME']);
		if(array_key_exists($cachename,$_SESSION) && array_key_exists('gb_folderList',$_SESSION[$cachename]) && array_key_exists($folder,$_SESSION[$cachename]['gb_folderList']) && $usecache)
		{
			return $_SESSION[$cachename]['gb_folderList'][$folder];
		}
		
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


	public function getIncludePath($file,$fold) {
		return $this->include_path.'/'.$fold.'/'.$file;
	}

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