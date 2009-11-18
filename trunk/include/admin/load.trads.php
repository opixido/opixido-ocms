<?php




global $admin_trads,$_Gconfig;
/*
define('ADMIN_PICTOS_FOLDER',ADMIN_URL.'pictos_stock/tango/');
define('ADMIN_PICTOS_ARBO_SIZE','16x16');
define('ADMIN_PICTOS_FORM_SIZE','22x22');
define('ADMIN_PICTOS_FRONT_SIZE','22x22');

*/

$sql = 'SELECT * FROM s_trad';
$res = GetAll($sql);


if($res) {
	foreach($res as $row) {
		reset($_Gconfig['LANGUAGES']);
		foreach($_Gconfig['LANGUAGES'] as $v) {
	
			if($v) {				
				$admin_trads[$row['trad_id']][$v] = $row['trad_'.$v];
			}
		}
		reset($_Gconfig['LANGUAGES']);
	}
}

$sql = 'SELECT * FROM s_admin_trad';
$res = GetAll($sql);

if($res) {
	foreach($res as $row) {
		reset($_Gconfig['LANGUAGES']);
		foreach($_Gconfig['LANGUAGES'] as $v) {
	
			if($v) {
				$row['admin_trad_'.$v] = str_replace(array('[ADMIN_PICTOS_FOLDER]',
															'[ADMIN_PICTOS_ARBO_SIZE]',
															'[ADMIN_PICTOS_FORM_SIZE]')
											,		array(ADMIN_PICTOS_FOLDER,
															ADMIN_PICTOS_ARBO_SIZE,
															ADMIN_PICTOS_FORM_SIZE),
															$row['admin_trad_'.$v]);
				$admin_trads[$row['admin_trad_id']][$v] = $row['admin_trad_'.$v];
			}
		}
		reset($_Gconfig['LANGUAGES']);
	}
}




//debug($admin_trads);


?>