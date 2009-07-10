<?php 

global $tabForms,$_Gconfig,$uploadFields,$rteFields,$neededFields,$relations,$urlFields,$emailFields,$admin_trads,$relinv,$tablerel;

$tabForms["plug_reaction"]["titre"] = array (
  0 => 'reaction_date',
  1 => 'reaction_nom',
  2 => 'reaction_comment',
  3 => 'en_ligne',
);

$tabForms["plug_reaction"]["pages"] = array (
  'info' => '../plugins/ocms_reaction/forms/form.info.php',
);

$tabForms["plug_reaction"]["picto"] = "pictos_stock/tango/32x32/apps/internet-group-chat.png";



$_Gconfig["adminMenus"]["reaction"][] = "plug_reaction";



$admin_trads["cp_txt_plug_reaction"]["fr"] = "Reactions des spectateurs";
$admin_trads["plug_reaction"]["fr"] = "Reaction";
$admin_trads["viewWaitingReaction"]["fr"] = "Voir les réactions en attente";

$_Gconfig['hideableTable'][] = 'plug_reaction';

$_Gconfig['tableActions']['plug_reaction'][] = 'viewWaitingReaction';

function viewWaitingReaction() {
	
	$_POST['doFullSearch'] = 1;
	$_POST['en_ligne'] = 0;
	
}