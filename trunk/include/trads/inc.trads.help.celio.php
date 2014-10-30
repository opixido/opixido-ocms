<?

global $frontAdminTrads,$admin_trads,$_trads,$admin_trads;

if(!is_array($admin_trads))
$admin_trads = array();
$admin_trads['droits_insuffisants_pour_cette_action']['fr'] = 'Vous n\'avez pas les droits nécessaires pour effectuer cette action';

$admin_trads['rubrique_pas_en_ligne']['fr'] = 'Cette rubrique est déjà masquée';

$admin_trads['rubrique_refuser_ok']['fr'] = 'Votre refus de validation a bien été pris en compte';


$admin_trads['refuse']['fr'] = 'Refuser la validation';

$admin_trads['unvalidate']['fr'] = 'Masquer';

$admin_trads['PublicationValidate']['fr'] = $admin_trads['SiteValidate']['fr'] = 'Mettre en ligne';
$admin_trads['PublicationHide']['fr'] =$admin_trads['SiteHide']['fr'] = 'Masquer';

$admin_trads['save_and_close']['fr'] = 'Retour';

$admin_trads['ce_t_site']['fr'] = 'ce site';

$admin_trads['un_t_site']['fr'] = 'un site';


$admin_trads['ce_t_publication']['fr'] = 'cette publication';

$admin_trads['un_t_publication']['fr'] = 'une publication';

$admin_trads['ce_t_collection']['fr'] = 'cette collection';

$admin_trads['info_site_mis_en_ligne']['fr'] = 'Ce site a bien été mis en ligne et est désormais visible par les internautes';
$admin_trads['info_site_masque']['fr'] = 'Ce site a bien été masqué, les internautes n\'y ont plus accès';

$admin_trads['info_publi_mise_en_ligne']['fr'] = 'Cette publication a bien été mise en ligne et est désormais visible par les internautes';
$admin_trads['info_publi_masquee']['fr'] = 'Cette publication a bien été masquée, les internautes n\'y ont plus accès';


$admin_trads['src_refuse']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/edit-undo.png';
$admin_trads['src_light_edit']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/categories/applications-graphics.png';

$admin_trads['src_modification']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/accessories-text-editor.png';


$admin_trads['src_voir_version_en_ligne']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/document-template.png';

$admin_trads['src_voir_version_modifiable']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/document-properties.png';
$admin_trads['src_calendrier']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/office-calendar.png';


$admin_trads['src_random_password']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/apps/preferences-system-session.png';

$admin_trads['src_locked']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/status/locked.png';
$admin_trads['src_importftp']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/emblems/emblem-symbolic-link.png';
$admin_trads['src_copy']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/places/start-here.png';

$admin_trads['generate_random_password']['fr'] = 'Generate a password';
$admin_trads['erreur_lock_existe']['fr'] = '<img src="'.$admin_trads['src_locked']['fr'].'" alt="" /> Une autre personne est déjà <br/>en train de modifier cet élément';


$admin_trads['t_admin_p_0']['fr'] = 'Informations';
$admin_trads['t_admin_p_1']['fr'] = 'Allowed rubrics';


$admin_trads['table_log_action']['fr'] = 'Most recent actions';

$admin_trads['modification']['fr'] = 'Modify';

$admin_trads['empty_field']['fr'] = 'Empty field';

$admin_trads['ajout_sub_rub']['fr'] = 'New';

$admin_trads['invisible_rub']['fr'] = ' <span style="color:red">[Masquée]</span>';


$admin_trads['version_masquee']['fr'] = ' Cette rubrique est actuellement masquée';
$admin_trads['suppression_ok']['fr'] = 'Suppression effectuée pour : ';
$admin_trads['modification_ok']['fr'] = 'Changements enregistrés pour : ';
$admin_trads['ajout_ok']['fr'] = 'correctement ajouté';






?>