<?php

global $_Gconfig;
global $tabForms;
global $uploadFields;
global $specialUpload;
global $basePath;
global $admin_trads;


$tabForms['p_imagep']['titre'] = array('imagep_img','imagep_alt');
$tabForms['p_imagep']['pages'] = array('image'=>'../plugins/ocms_imageParametrable/form.imagep.php');

$uploadFields[] = 'imagep_img';

$_Gconfig['adminMenus']['menu_admin'][] = 'p_imagep';


foreach ($_Gconfig['LANGUAGES'] as $v) {
	$specialUpload["p_imagep"]["imagep_img_".$v]["system"] = $basePath."/fichier/imagep/";
	$specialUpload["p_imagep"]["imagep_img_".$v]["name"] = "*ID*_".$v.".*EXT*";
	$specialUpload["p_imagep"]["imagep_img_".$v]["web"] = "/fichier/imagep/";
}

$tabForms["p_imagep"]["picto"] = "pictos_stock/tango/32x32/mimetypes/image-x-generic.png";


$_Gconfig['versionedTable'][] = 'p_imagep';
$admin_trads["cp_txt_p_imagep"]["fr"] = "Images paramétrables";
$admin_trads["cp_txt_p_imagep"]["uk"] = "Images";

?>