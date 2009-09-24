<?php 

global $tabForms,$_Gconfig,$uploadFields,$rteFields,$neededFields,$relinv,$relations,$urlFields,$emailFields,$admin_trads,$relinv,$tablerel;

$tabForms["forum_message"]["titre"] = array (
  0 => 'message_date',
  1 => 'message_titre',
  2 => 'fk_utilisateur_id',
 /* 2 => 'fk_rubrique_id',
  3 => 'fk_message_id',*/
);

$tabForms["forum_message"]["pages"] = array (
  'info' => '../plugins/ocms_forum/forms/form.info.php',
);

$tabForms["e_pays"]["titre"] = array (
'pays_name'
);

$GLOBALS['forum_user_table'] = 'forum_user';

$tabForms["forum_message"]["picto"] = "pictos_stock/tango/32x32/apps/internet-group-chat.png";
$tabForms["forum_user"]["picto"] = "pictos_stock/tango/32x32/emotes/face-smile.png";

$_Gconfig['hideableTable'][] = 'forum_message';

$uploadFields[] = "message_pj";
$uploadFields[] = "forum_user_avatar";

$relations["forum_message"]["fk_utilisateur_id"] = "e_utilisateur";
$relations["forum_message"]["fk_rubrique_id"] = "s_rubrique";
$relations["forum_message"]["fk_message_id"] = "forum_message";
$relations["forum_message"]["fk_root_id"] = "forum_message";
$rteFields[] = 'message_texte';


$relations['forum_user']['forum_user_pays'] = 'e_pays';
//$relinv['forum_message']['REPONSES'] = array('forum_message','fk_root_id');

$_Gconfig["adminMenus"]["ocms_forum"][] = "forum_message";
$_Gconfig["adminMenus"]["ocms_forum"][] = "e_pays";

$admin_trads["cp_txt_forum_message"]["fr"] = "Forum";
$admin_trads["forum_message"]["fr"] = "Forum";
$admin_trads["forum_message.message_date"]["fr"] = "Date";
$admin_trads["forum_message.fk_utilisateur_id"]["fr"] = "Writer";
$admin_trads["forum_message.fk_rubrique_id"]["fr"] = "Theme";
$admin_trads["forum_message.fk_message_id"]["fr"] = "Parent message";
$admin_trads["forum_message.fk_root_id"]["fr"] = "Root message";
$admin_trads["forum_message.message_titre"]["fr"] = "Title";
$admin_trads["forum_message.message_texte"]["fr"] = "Content";
$admin_trads["forum_message.message_pj"]["fr"] = "Attachment";

$_Gconfig['imageAutoResize']['forum_user_avatar'] = array(80,80);

$_Gconfig['specialListing']['s_rubrique']['forum_message'] = 'listOnlyForum';




$_Gconfig['rowActions']['forum_user']['banishForumUser'] = 1;
$_Gconfig['rowActions']['forum_user']['unBanishForumUser'] = 1;

$admin_trads['src_banishForumUser']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/emotes/face-sad.png';
$admin_trads['src_unBanishForumUser']['fr'] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/emotes/face-angel.png';


function listOnlyForum() {
	$gab = getGabaritByClass('genForumListe');
	$gabT = getGabaritByClass('genForumTheme');
	$sql = 'SELECT * FROM s_rubrique AS R WHERE 1 '.sqlRubriqueOnlyOnline('R').' AND fk_gabarit_id = '.$gab['gabarit_id'].' ORDER BY rubrique_ordre ASC ';
	$res =  getAll($sql);	
	
	$tab = array();
	foreach($res as $row) {
		
		$sql = 'SELECT * FROM s_rubrique AS R WHERE 
					fk_rubrique_id = '.$row['rubrique_id'].' '.sqlRubriqueOnlyOnline('R').' 
					AND fk_gabarit_id = '.sql($gabT['gabarit_id']).'
						ORDER BY rubrique_ordre ASC
					';
		$resS = GetAll($sql);
		
		
		
		foreach($resS as $rowW) {
			
				$tab = array_merge($tab,array($rowW));
			
				$sql = 'SELECT * FROM s_rubrique AS R WHERE 
				fk_rubrique_id = '.$rowW['rubrique_id'].' '.sqlRubriqueOnlyOnline('R').' 
				AND fk_gabarit_id = '.sql($gabT['gabarit_id']).'
					ORDER BY rubrique_ordre ASC
				';
				$resSS = GetAll($sql);
				
				foreach($resSS as $rowWW ) {
					$tab = array_merge($tab,array($rowWW));
				}
			
		}
		
	}
	
	return $tab ;
}


