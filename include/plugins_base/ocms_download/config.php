<?php 

global $relinv,$orderFields,$tabForms,$_Gconfig,$uploadFields,$rteFields,$neededFields,$relations,$urlFields,$emailFields,$admin_trads,$relinv,$tablerel;

$tabForms["p_download"]["titre"] = array (
  0 => 'download_titre_fr',
  1 => 'download_fichier_fr',
);

$tabForms["p_download"]["pages"] = array (
  'info' => '../plugins/ocms_download/forms/form.info.php',
);

$tabForms["s_rubrique"]["pages"]['contenu'][] = '../plugins/ocms_download/forms/rubrique.download.php';

$uploadFields[] = 'download_fichier';

$tabForms["p_download"]["picto"] = "[ADMIN_PICTOS_FOLDER]32x32/actions/document-save.png";


$relations["p_download"]["fk_rubrique_id"] = "s_rubrique";
$orderFields['p_download'] = array('download_ordre','fk_rubrique_id');

$relinv['s_rubrique']['DOWNLOADS'] = array('p_download','fk_rubrique_id');

