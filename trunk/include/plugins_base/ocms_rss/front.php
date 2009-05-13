<?php


function includeRss() {

	/*define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
	$GLOBALS['gb_obj']->includeFile('rss_fetch.inc','plugins/ocms_rss/magpierss');
	*/
	
	
	/**
	 * Utile pour la lecture des flux externes
	 */
	
	$GLOBALS['gb_obj']->includeFile('simplepie.inc','plugins/ocms_rss/simplepie');
}


class rssFront extends ocmsPlugin {
	
	
	function afterInit() {
		
		
	}
	
	
	function getCurrentPageFlux() {
		
		
		$sql = 'SELECT * FROM plug_rss WHERE fk_rubrique_id = '.sql($GLOBALS['site']->getCurId());
		$res = GetAll($sql);
		
		if(!count($res)) {
			$id = getRubFromGabarit('genRss');
			
			$sql = 'SELECT * FROM plug_rss WHERE fk_rubrique_id = '.sql($id);
			$res = GetAll($sql);
		}
		
		return $res;
		
	}
	
	function genRssHeaders($res) {
		
		foreach($res as $row) {
			
			$rss = '<link href="'.rssFront::getUrl($row).'" rel="alternate" type="application/rss+xml" title='.alt(getLgValue('rss_titre',$row)).' />
';
			$GLOBALS['site']->g_headers->addHtmlHeaders($rss);
			
		}
		
	}
	
	
	function getUrl($row,$format='RRS2.0') {
		
		$rubId = getRubFromGabarit('genRss');
		
		return getUrlFromId($rubId,LG,array('flux'=>$row['rss_version'],'format'=>$format));
		
		
	}
	
}

?>