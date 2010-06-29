<?php

global $isTopNavRub, $noCopyTable, $tab_noCopyField,$tabForms,$uploadRep,$relations,$relinv,$tablerel,$searchField,$specialUpload,$previewField,$orderFields,$adminMenus,$rteFields,$neededFields,$neededSymbol,$uploadFields,$mailFields,$validateFields,$adminInfos,$gs_roles,$gs_actions,$formsRep,$frontAdminTrads,$gr_on, $rootId, $homeId, $headRootId, $footRootId,$basePath,$baseWebPath, $lexiqueId,$languages,$google_key,$_Gconfig,$adminTypesToMail,$functionField,$multiVersionField,$_Gconfig;



/**
 * On utilise le cache ?
 *
 */

if($_GET['nocache']) {
	define('CACHE_IS_ON',false);	
} else {
	define('CACHE_IS_ON',true);
}

/**
 * Compression et union des JS et CSS ?
 */
$_Gconfig['compressCssFiles'] = true;
$_Gconfig['compressJsFiles'] = true;


/**
 * On log toutes les requetes SQL
 */
$_Gconfig['debugSql'] = false;
$_Gconfig['debugSql'] = $_GET['debugSql'] ? true : $_Gconfig['debugSql'];


/**
 * Quel schéma d'URL ?
 * genUrlSimple ou genUrl
 */
$_Gconfig['URL_MANAGER'] = 'genUrl';


define('THUMBPATH',BU.'/thumb');
define('IMG_GENERATOR',BU.'/imgps.php');


/**
 * Titre du site (pour l'admin)
 */
$_Gconfig['titre'] = '';

/**
 * Liste des langues par defaut du site
 */
$_Gconfig['LANGUAGES'] = array('fr');
$_Gconfig['ADMIN_LANGUAGES'] = array('fr');

$_Gconfig['onlyOneLgForever'] = true;

define('ADMIN_LG_DEF','fr');

/**
 * Gabarit de page par défaut pour surclasser des informations
 *
 */
define('GABARIT_DEF',false);


/**
 * On utilise les thubmnails pour les images dans l'admin ?
 */
$_Gconfig['useThumbs'] = true;

/**
 * On utilise l'editeur d'images dans l'admin ?
 */
$_Gconfig['useImageEditor'] = true;

/**
 * Peut-on uploader un CSV pour creer un tableau dans le RTE ?
 */
$_Gconfig['uploadCsvInRte'] = false;

/**
 * Clef google pour la recherche
 */
$google_key = '8AKPcXZQFHKy28yKynR82wzjir7KF2JV';


/**
 * Est-ce qu'une rubrique avec des sous rubriques a un contenu ? Ou bien elle pointe vers la premiere sous rubrique ?
 */
$_Gconfig['rubLinkToSub'] = true;


/**
 * TABFORMS
 * $tabForms['TABLE']['pages'] = array(FORMULAIRES);
 * $tabForms['TABLE']['titre'][] = CHAMP;;
 */
$tabForms;



/**
 * Ratio des champs pour la recherche
 * Ratio par défaut : 1
 * $_Gconfig['searchRatio']['TABLE'] = array('CHAMP1'=>15,'CHAMP2'=>10);
 */
$_Gconfig['searchRatio'];



/**
 * Liste des tables avec Afficher/Masquer
 * $_Gconfig['hideableTable'][] = 'table';
 */

$_Gconfig['hideableTable'];


/**
 * Liste des tables avec plusieurs versions possibles
 * $_Gconfig['multiVersionTable'][] = 'table';
 */

$_Gconfig['multiVersionTable'];


/*  On definit les relations
 *  simples (clef externe)
 *
 *  $relations['TABLE']['FK_CHAMP'] = 'FK_TABLE';
 */
$relations;


/**
 *  Relations inverses
 *  Toutes les entrees de X table qui pointent vers moi
 *
 *  $relinv['TABLE PARENTE']['NOM_DU_FAUX_CHAMP'] = array('TABLE FILLE','CLEF EXTERNE');
 */
$relinv;



/**
 *  On definit les tables de relation
 *
 *  $tablerel['TABLE_RELATION'] = array('FK_CHAMP1'=>'FK_TABLE1','FK_CHAMP2'=>'FK_TABLE2');
 */
$tablerel;



/**
 * Quand on modifie le champ X on reload le formulaire
 *
 * $_Gconfig['reloadOnChange'] = array('TABLE.CHAMP');
 */
$_Gconfig['reloadOnChange'];


/**
 * Liste des champs "Cherchables" dans l'admin
 * $searchField['TABLE'][] = CHAMP;;
 */
$searchField;



/**
 * Liste des champs de type PASSWORD
 *
 * $_Gconfig['passwordFields'][] = CHAMP;
 */
$_Gconfig['passwordFields'];


/**
 * Upload de fichiers selon un chemin particulier
 *
 * $specialUpload["genfile_default"]["genfile_default"]["system"] = $basePath."fichier/ *TABLE* / *ID* /";
 * $specialUpload["genfile_default"]["genfile_default"]["name"] = "*FIELD*_*NAME*.*EXT*";
 * $specialUpload["genfile_default"]["genfile_default"]["web"] = $baseWebPath."fichier/ *TABLE* / *ID* /";
 */
$specialUpload;



/**
 * On definit la liste des champs
 * qui afficheront un bouton preview
 * et les champs a afficher
 *
 * $previewField['TABLE']['CLEF EXTERNE'] = 'CHAMP DE LA TABLE EXTERNE A AFFICHER';
 */
$previewField;

/**
 *      On definit la liste des champs
 *       qui gerent l'ordre
 *       On ajoute et regenere l'ordre directement
 *
 *  $orderFields['TABLE'] = array('CHAMP_ORDRE','EVENTUELLEMENT LE CHAMP DE SELECTION (seulement pour le fk = ....');
 */
$orderFields;


/**
 * Liste des champs IMAGES / UPLOAD ou l'on retaille l'image automatiquement
 * si elle est plus large ou pour haute que les valeurs ci-dessous
 * CONSERVE SES PROPORTIONS
 *
 * $_Gconfig['imageAutoResize'] = array('FIELD_NAME'=>array(MAXWIDTH,MAXHEIGHT));
 *
 */

$_Gconfig['imageAutoResize'];


/**
 *  Liste des champs qui doivent etre en RTE (Wysiwyg)
 *  $rteFields[] = CHAMP;;
 **/
$rteFields;



/**
 *  Liste des champs obligatoires
 *
 * $neededFields[] = CHAMP;;
 **/
$neededFields;



/**
 *  Liste des champs de type UPLOAD
 *
 *  $uploadFields[] = CHAMP;
 **/



/**
 *  Liste des champs de type MAIL
 *
 * $mailFields[] = CHAMP;
 **/
$mailFields;




/**
 * Images a retailler automatiquement
 * RETAILLAGE EXACT !
 * Ne conserve pas les proportions
 *
 * $_Gconfig['imageAutoResize']['CHAMP'] = array(LARGEUR,HAUTEUR);
 */
$_Gconfig;





/**
 * Definition des MENUS de navigation
 */

$_Gconfig['menus'] = array();

/**
	@example
		$_Gconfig['menus']['haut_gauche'] = array(
		'max_levels'=>2,
		'use_images'=>false,
		'open_selected'=>false,
		'profile'=>'menu_haut',
		'rollover'=>'menu_haut',
		'width'=>array(81,81,81,85),
		'imgW'=>array(81,81,81,85),
		'caps'=>true
	);


*/

$_Gconfig['menus']['__default__'] = array(
		'max_levels'=>1,
		'use_images'=>false,
		'open_selected'=>false,
		'profile'=>'menu_gauche',
		'rollover'=>'menu_gauche_hover',
		'rollovers'=>array(),
		'width'=>array(),
		'imgW'=>array(),
		'caps'=>false
);

/**
 * Pour la sécurité si malgré les relations certaines tables sont interdites
 * $_Gconfig['gsNoFollowRel'] = array('TABLE.SECONDETABLE');
 */

$_Gconfig['gsNoFollowRel'];






$tabForms["s_rubrique"]["picto"] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_BIG_SIZE."/apps/system-file-manager.png";
$tabForms["s_admin"]["picto"] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_BIG_SIZE."/apps/preferences-desktop-theme.png";
$tabForms["s_trad"]["picto"] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_BIG_SIZE."/mimetypes/font-x-generic.png";
$tabForms["s_plugin"]["picto"] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_BIG_SIZE."/mimetypes/package-x-generic.png";
$tabForms["s_param"]["picto"] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_BIG_SIZE."/categories/preferences-system.png";
$tabForms["s_admin_trad"]["picto"] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_BIG_SIZE."/apps/preferences-desktop-font.png";