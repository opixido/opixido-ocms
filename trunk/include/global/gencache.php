<?php

class genCache {

	private $cache_id;
	private $cache_time;
	private $cached;
	public $cache_path;
	private $strCache = '';

	function genCache($cache_id,$cache_time,$cache_path='') {

		global $gb_obj;
		$GLOBALS['nbCacheTotal']++;
		
		
		//debugOpix('CACHE : '.$cache_id);
		//debug($GLOBALS);
		
		$this->cache_id = LG.str_replace('/','.',BU).$_SERVER['SERVER_NAME'].'_'.$cache_id;
		$GLOBALS['cacheDeclared'][] = $this->cache_id;
		
		$cachepath = is_object($gb_obj) ? $gb_obj->include_path.'/'.GetParam('cache_path').'/'.$this->cache_id : $cache_path.'/'.$this->cache_id;
		
		$this->cache_time = $cache_time;
		
		$this->cache_path = $cachepath;
		$this->cached = false;
		$this->cacheChecked = false;

		/*trigger_error($cache_id);
		print('<pre>');debug_print_backtrace();
		print('</pre>');*/

	}

	function cacheExists() {
		// DESACTIVE LE CACHE
		if(!CACHE_IS_ON)
			return false;

		if(!$this->cacheChecked) {
			$this->cached = false;
			if( file_exists($this->cache_path) ) {

				if($this->cache_time <= filemtime($this->cache_path)) {
					//debug($this->cache_id.' '.filemtime($this->cache_path).' '.$this->cache_time.' '.time());
					$this->cacheChecked = true;
					$this->cached = true;
				}
			}
		}
		return $this->cached;
	}

	function saveCachePlain($str) {

		$this->cacheChecked = true;
		$this->strCache = $str;
		return file_put_contents($this->cache_path, $str);
	}

	function getCachePlain() {
		$GLOBALS['cacheUsed'][] = $this->cache_id;
		if(!$this->strCache) {
			$GLOBALS['nbCacheUsed']++;
			return file_get_contents($this->cache_path);
		}
		return $this->strCache;
	}
	
	function saveCache($str) {
		return $this->saveCachePlain($str);
		
	}
	
	function getCache() {
		return  $this->getCachePlain();
	}

	function saveCacheGz($str) {
		// DESACTIVE LE CACHE
		//return false;

		$this->cacheChecked = true;
		$this->strCache = $str;
		//return file_put_contents($this->cache_path, $str);
		$gz = gzopen($this->cache_path,'wb');
		gzputs($gz,$str);
		gzclose($gz);
		return true;
	}

	function getCacheGz() {
		//debug($this->cache_id.' USED');
		$GLOBALS['cacheUsed'][] = $this->cache_id;
		//debugOpix('CACHE USED  : '.$this->cache_id);
		if(!$this->strCache) {
			$GLOBALS['nbCacheUsed']++;
			//return file_get_contents($this->cache_path);
			//$gz = gzopen($this->cache_path,'rb');
			$gza = gzfile($this->cache_path);
			//gzclose($gz);
			return implode($gza);
			return '<span class="ocms_cache">'.implode($gza).'</span>';
		} else {
			return $this->strCache;
		}
	}

}


?>