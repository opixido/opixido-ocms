<?php


function getImgText($text,$profile='',$params='') {
	$ps = getImgTextSrc($text,$profile,$params);
	return '<img src="'.$ps.'" alt='.alt($text).' />';
}

function getImgTextSrc($text,$profile='',$params='') {
	
	$u =   BU.'/imgps.php?text='.urlencode(htmlentities($text,ENT_QUOTES,'utf-8'));
	if($profile) {
		$u .= '&profile='.$profile;
	}
	if($params) {
		$u .= '&'.str_replace('&amp;','&',$params);
	}
	$cPath = './imgc/';
	$m = md5($u).'.png';
	
	if(CACHE_IS_ON && file_exists($cPath.$m)) {			
		return BU.'/imgc/'.$m;
	} 
	return $u;
}