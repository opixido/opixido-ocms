<?php


/**
 * AGRESSIVE CACHE
 */
$useCache = true;

$tabNoCache = array('_action/editer','laLogout');

$url = ('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

$fname = './include/cache_agr/page_'.md5($url);

session_start();

function newarrayInWord($arr,$word) {

    while(list(,$v) = each($arr)) {	
    	
            if(ereg($v,$word))
           //if (strpos($v, $word)) 
                    return true;                   
    }
    
    return false;
}

if(newarrayInWord($tabNoCache,$url)) {
	$useCache = false;
} 

if(	
	$useCache && 
	!$_REQUEST['nocache'] && 
	!$_SESSION['ocms_login']['utilisateur_id'] && 
	is_file($fname) && 
	!count($_POST) && 
	filemtime($fname) >= (@filemtime('./include/temoinchange'))) {

	header('Content-Type: text/html; charset=utf-8');
	header('Last-Modified: '.date('r',filemtime($fname)));
	header('X-cache: Agressive ');
	readfile($fname);	
		
	die();	

} else {
	$useCache = false;
}



/**
 * AGRESSIVE CACHE END
 */

ob_start();