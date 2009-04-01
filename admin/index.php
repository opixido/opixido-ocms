<?php


ob_start();

define('IN_ADMIN',true);
error_reporting(E_ALL & ~E_NOTICE);

require_once('../include/include.php');


/* On aura toujours besoin de ca */

$gb_obj = new genBase();

$gb_obj->includeConfig();

$gb_obj->includeBase();

$gb_obj->includeGlobal();

$t = getmicrotime();

$genMessages = new genMessages();

$gb_obj->includeAdmin();

$lg = $_SESSION['lg'] = $_REQUEST['lg'] ? $_REQUEST['lg'] :(  $_SESSION['lg']  ? $_SESSION['lg'] : getBrowserLang() );
define('LG',$lg);
   		
   		
initPlugins();

$gs_obj = new genSecurity();

$gs_obj->needAuth();

loadParams();



/*
if(isset($_REQUEST['reindex']) ) {
	$t = getmicrotime();
	$is = new indexSearch($_REQUEST['table']);
	$txt = $is->getTextToIndex($_REQUEST['id']);
	$is->indexText($txt,$_REQUEST['id']);
	//print('Indexed in '.(getmicrotime()-$t).' s');
	die();
}
else */
if(isset($_REQUEST['reindex']) ) {
	
die();
}
if(isset($_REQUEST['popup'])){

	$gpopup = new genAdminPopup();
	$gpopup->gen();

}else if(isset($_REQUEST['gfa'])) {

	$gpopup = new genPopupAdmin($_REQUEST['curTable'],$_REQUEST['curId']);
	$gpopup->gen();
	
} else  if(isset($_REQUEST['xhr'])) {
	
	$gpopup = new genXhrAdmin($_REQUEST['curTable'],$_REQUEST['curId']);
	$gpopup->gen();
	
} else {

	$gadmin = new genAdmin($_REQUEST['curTable'],$_REQUEST['curId']);	
	
	$gadmin->gen();    


}


//debug($gs_obj->myroles);
$genMessages->gen();



/*
if(function_exists('replacePngTags')) {
	echo replacePngTags(ob_get_clean());
}
*/
if(strstr($_SERVER['REMOTE_ADDR'],'192.168.1.') || strstr($_SERVER['REMOTE_ADDR'],'82.67.200.175') || $_REQUEST['debug']) {

	print($profileSTR);

}
//debug(getmicrotime() - $startTime);
//print(getmicrotime() - $t);
?>