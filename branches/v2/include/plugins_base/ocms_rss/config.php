<?php 

global $tabForms,$_Gconfig,$uploadFields,$rteFields,$neededFields,$relations,$urlFields,$emailFields,$admin_trads,$relinv,$orderFields;

$tabForms["plug_rss"]["titre"] = array (
  0 => 'rss_titre_fr',
);

$tabForms["plug_rss"]["pages"] = array (
  'info' => '../plugins/ocms_rss/forms/form.info.php',
);


global $gr_on;
$gr_on['insert']['plug_rss'] = 'updateRssVersion';


function updateRssVersion($row) {
	
	DoSql('UPDATE plug_rss SET rss_version = '.sql($row).' WHERE rss_id = '.sql($row));
	
}

$tabForms["s_rubrique"]["pages"]['rss']= '../plugins/ocms_rss/forms/form.rubrique.php';


$tabForms["plug_rss"]["picto"] = "pictos_stock/tango/32x32/emblems/rss.png";

$relinv['s_rubrique']['FLUX_RSS'] = array('plug_rss','fk_rubrique_id');

$uploadFields[] = "rss_vignette";

$urlFields[] = "rss_url";

$orderFields['plug_rss'] = array('rss_ordre','fk_rubrique_id');

$_Gconfig["duplicateWithRubrique"][] = "plug_rss";

//$_Gconfig["adminMenus"]["ocms_rss"][] = "plug_rss";

$admin_trads["cp_txt_plug_rss"]["fr"] = "Rss";
$admin_trads["plug_rss"]["fr"] = "Rss";
$admin_trads["plug_rss.rss_titre_fr"]["fr"] = "Nom du flux";
$admin_trads["plug_rss.rss_desc_fr"]["fr"] = "Description";
$admin_trads["plug_rss.rss_vignette"]["fr"] = "Vignette";
$admin_trads["plug_rss.rss_url"]["fr"] = "URL";
$admin_trads["plug_rss.rss_html"]["fr"] = "Afficher le contenu en HTML ?";
$admin_trads["plug_rss.rss_truncate"]["fr"] = "Couper le contenu à  X caractères ?";
$admin_trads["plug_rss.rss_sql"]["fr"] = "Requête SQL ou nom de la table";
$admin_trads["plug_rss.rss_champ_titre"]["fr"] = "ITEM : Champ titre";
$admin_trads["plug_rss.rss_champ_desc"]["fr"] = "ITEM : Champ desc";
$admin_trads["plug_rss.rss_champ_date"]["fr"] = "ITEM : Champ date";
$admin_trads["plug_rss.rss_champ_image"]["fr"] = "ITEM : Champ image";
$admin_trads["plug_rss.rss_champ_enclosure"]["fr"] = "ITEM : Champ enclosure";


?>