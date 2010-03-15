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


class genCache {

	private $cache_id;
	private $cache_time;
	private $cached = false;
	public $cache_path;
	private $strCache = '';
	private $cacheChecked = false;

	function genCache($cache_id,$cache_time,$cache_path='') {

		global $gb_obj;
		
		/**
		 * How many times has genCache been instantiated ?
		 */
		$GLOBALS['nbCacheTotal']++;
		
		/**
		 * Cache unique filename
		 */
		$this->cache_id = LG.str_replace('/','.',BU).$_SERVER['SERVER_NAME'].'_'.$cache_id;
		
		/**
		 * Name of cache declared
		 */
		$GLOBALS['cacheDeclared'][] = $this->cache_id;
		
		/**
		 * Full path to cache file
		 */
		if(!$cache_path) {
			$cache_path = is_object($gb_obj) ? $gb_obj->include_path.'/'.GetParam('cache_path').'/'.$this->cache_id : $cache_path.'/'.$this->cache_id;
		} else {
			$cache_path .= '/'.$cache_id;		
		}
		/**
		 * When was the content modified
		 */
		$this->cache_time = $cache_time;
		
		/**
		 * Full path
		 */
		$this->cache_path = $cache_path;

	}

	/**
	 * Checks if cache file exists and is newer than content modification
	 *
	 * @return bool
	 */
	function cacheExists() {
		
		/**
		 * No cache ... no cache
		 */
		if(!CACHE_IS_ON)
			return false;

		/**
		 * If not already checked
		 */
		if(!$this->cacheChecked) {
			/**
			 * File must exist
			 */
			if( file_exists($this->cache_path) ) {
				/**
				 * And be newer than content modification
				 */
				if($this->cache_time <= filemtime($this->cache_path)) {					
					$this->cacheChecked = true;
					$this->cached = true;
				}
			}
		}
		return $this->cached;
	}

	/**
	 * Saves content in plain file (not gziped)
	 *
	 * @param string $str
	 * @return bool
	 */
	function saveCachePlain($str) {

		$this->cacheChecked = true;
		$this->strCache = $str;
	
		$a = file_put_contents($this->cache_path, $str);
		chgrp($this->cache_path,'www-data');
		return $a;
	}

	/**
	 * Returns the content of the current cache file 
	 * USE ONLY IF FILE WAS PLAIN TEXT SAVED
	 *
	 * @return string
	 */
	function getCachePlain() {
		/**
		 * Cache used
		 * If this method is not called, then cache is useless
		 */
		$GLOBALS['cacheUsed'][] = $this->cache_id;
		
		/**
		 * Cached version ?
		 */
		if(!$this->strCache) {
			$GLOBALS['nbCacheUsed']++;
			return file_get_contents($this->cache_path);
		}
		return $this->strCache;
	}
	
	/**
	 * Default alias for savePlain or saveGz
	 *
	 * @param string $str
	 * @return bool
	 */
	function saveCache($str) {
		return $this->saveCachePlain($str);
		
	}
	
	/**
	 * Default alias for savePlain of saveGz
	 *
	 * @return string
	 */
	function getCache() {
		return  $this->getCachePlain();
	}


	/**
	 * Saves cache content and compress it via GZ
	 *
	 * @param string $str
	 * @return bool
	 */
	function saveCacheGz($str) {

		$this->cacheChecked = true;
		$this->strCache = $str;

		$gz = gzopen($this->cache_path,'wb');
		gzputs($gz,$str);
		gzclose($gz);
		
		return true;
	}

	/**
	 * Returns Cache saved and compressed in GZ
	 *
	 * @return string
	 */
	function getCacheGz() {
		
		$GLOBALS['cacheUsed'][] = $this->cache_id;
		
		if(!$this->strCache) {
			$GLOBALS['nbCacheUsed']++;			
			$gza = gzfile($this->cache_path);
			return implode($gza);		
		} else {
			return $this->strCache;
		}
	}

}

