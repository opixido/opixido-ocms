<?php

global $isTopNavRub, $noCopyTable, $tab_noCopyField, $tabForms, $uploadRep, $relations, $relinv, $tablerel, $searchField, $specialUpload, $previewField, $orderFields, $adminMenus, $rteFields, $neededFields, $neededSymbol, $uploadFields, $mailFields, $validateFields, $adminInfos, $gs_roles, $gs_actions, $formsRep, $frontAdminTrads, $gr_on, $rootId, $homeId, $headRootId, $footRootId, $basePath, $baseWebPath, $lexiqueId, $languages, $google_key, $_Gconfig, $adminTypesToMail, $functionField, $multiVersionField, $_Gconfig;

$sid = session_id();
if (empty($sid)) {
    session_start();
}

/**
 * On utilise le cache ?
 *
 */
if (!empty($_GET['nocache'])) {
    define('CACHE_IS_ON', false);
} else if (IN_ADMIN) {
    define('CACHE_IS_ON', true);
} else {
    define('CACHE_IS_ON', false);
}

/**
 * Compression et union des JS et CSS ?
 */
$_Gconfig['groupCssFiles'] = false;
$_Gconfig['compressCssFiles'] = false;
$_Gconfig['compressJsFiles'] = false;


/**
 * On log toutes les requetes SQL
 */
$_Gconfig['debugSql'] = false;
$_Gconfig['debugSql'] = isset($_GET['debugSql']) ? true : $_Gconfig['debugSql'];


/**
 * Quel schéma d'URL ?
 * genUrlSimple ou genUrl
 */

$_Gconfig['URL_MANAGER'] = 'genUrlV2';

/**
 * Titre du site (pour l'admin)
 */
$_Gconfig['titre'] = '';

/**
 * Liste des langues par defaut du site
 */
$_Gconfig['LANGUAGES'] = array('fr','en','es');
$_Gconfig['ADMIN_LANGUAGES'] = array('fr');

$_Gconfig['onlyOneLgForever'] = false;

define('ADMIN_LG_DEF', 'fr');

/**
 * Gabarit de page par défaut pour surclasser des informations
 *
 */
define('GABARIT_DEF', false);


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
$_Gconfig['searchRatio'] = array();


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
 *    Définit si on ajoute au debut ou a la fin les nouveaux enregistrements
 *  $orderFields['TABLE'] = array('CHAMP_ORDRE','EVENTUELLEMENT LE CHAMP DE SELECTION (seulement pour le fk = ....','bottom|top');
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
$_Gconfig['imageAutoResize'] = array();


/**
 *  Liste des champs qui doivent etre en RTE (Wysiwyg)
 *  $rteFields[] = CHAMP;;
 * */
$rteFields;


/**
 *  Liste des champs obligatoires
 *
 * $neededFields[] = CHAMP;;
 * */
$neededFields;


/**
 *  Liste des champs de type UPLOAD
 *
 *  $uploadFields[] = CHAMP;
 * */
/**
 *  Liste des champs de type MAIL
 *
 * $mailFields[] = CHAMP;
 * */
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
 * Images a retailler automatiquement
 * RETAILLAGE EXACT !
 * Ne conserve pas les proportions
 *
 * $_Gconfig['imageAutoResize']['CHAMP'] = array(LARGEUR,HAUTEUR);
 */
$_Gconfig;

/**
 * Tables en arborescence sur elles même
 * Donc avec uen clef externe poitant vers elle même ou un relinv sur elle meme ...
 * $_Gconfig['arboredTable']['TABLE'] = 'CLEF_EXTERNE';
 * Si cette table doit gérer un ordre, penser à la mettre dans "orderedTable" ci-dessous
 */
$_Gconfig;


/**
 * Table avec gestion de l'ordre global sur cette table
 * $_Gconfig['orderedTable']['TABLE'] = 'CHAMP_ORDRE';
 */
$_Gconfig;

/**
 * Widget de selection de couleur sur un champ
 * $_Gconfig['colorFields'][] = 'CHAMP_COULEUR';
 */
$_Gconfig['colorFields'] = array();


/**
 * Definition des MENUS de navigation
 */
$_Gconfig['menus'] = array();


/**
 * Liaison simple depuis un champ upload vers les fichiers d'un dossier existant sans copie
 * $_Gconfig['fileListingFromFolder']['TABLE']['CHAMP'] = '/CHEMIN/VERS/LE/DOSSSIER/{*.ext1,*.ext2,...}';
 */
$_Gconfig['fileListingFromFolder'] = array();


/**
 * @example
 * $_Gconfig['menus']['haut_gauche'] = array(
 * 'max_levels'=>2,
 * 'use_images'=>false,
 * 'open_selected'=>false,
 * 'profile'=>'menu_haut',
 * 'rollover'=>'menu_haut',
 * 'width'=>array(81,81,81,85),
 * 'imgW'=>array(81,81,81,85),
 * 'caps'=>true
 * );
 */
$_Gconfig['menus']['__default__'] = array(
    'max_levels'         => 1,
    'use_images'         => false,
    'use_premade_images' => false,
    'open_selected'      => false,
    'max_open_selected'  => 3,
    'tpl_name'           => 'menu.item',
    'tpl_folder'         => 'template',
    'profile'            => 'menu_gauche',
    'rollover'           => 'menu_gauche_hover',
    'rollovers'          => array(),
    'width'              => array(),
    'imgW'               => array(),
    'caps'               => false
);

$_Gconfig['menus']['menu-bas'] = array(
    'max_levels'         => 1,
    'use_images'         => false,
    'use_premade_images' => false,
    'open_selected'      => false,
    'max_open_selected'  => 3,
    'tpl_name'           => 'menu.item',
    'tpl_folder'         => 'plugins/jukebox/tpl',
    'profile'            => 'menu_gauche',
    'rollover'           => 'menu_gauche_hover',
    'rollovers'          => array(),
    'width'              => array(),
    'imgW'               => array(),
    'caps'               => false
);

/**
 * Pour la sécurité si malgré les relations certaines tables sont interdites
 * $_Gconfig['gsNoFollowRel'] = array('TABLE.SECONDETABLE');
 */
$_Gconfig['gsNoFollowRel'] = array();


/**
 * Positionnement Latitude Longitude sur la carte
 * $_Gconfig['mapsFields']['TABLE']['IDENTIFIANT_CHAMP'] = array('CHAMP_LAT','CHAMP_LNG',array('LISTE','DES','CHAMPS','ADRESSE'));
 */
$_Gconfig['mapsFields'] = array();


$tabForms["s_rubrique"]["picto"] = ADMIN_PICTOS_FOLDER2 ."48/Editing/text_box-48.png";
$tabForms["s_admin"]["picto"] = ADMIN_PICTOS_FOLDER2 . "48/Users/user_menu_female-48.png";
$tabForms["s_trad"]["picto"] = ADMIN_PICTOS_FOLDER2 . "48/Editing/text_box-48.png";
$tabForms["s_plugin"]["picto"] = ADMIN_PICTOS_FOLDER2 . "24/Programming/plugin-24.png";
$tabForms["s_param"]["picto"] = ADMIN_PICTOS_FOLDER2 . "48/Very_Basic/settings-48.png";
$tabForms["s_admin_trad"]["picto"] = ADMIN_PICTOS_FOLDER2 . "48/Programming/edit_property-48.png";


foreach ($_Gconfig['LANGUAGES'] as $lg) {
    $specialUpload["s_paragraphe"][ "paragraphe_img_1_" . $lg ]["system"] = $basePath . "/fichier/s_rubrique/*fk_rubrique_id*/";
    $specialUpload["s_paragraphe"][ "paragraphe_img_1_" . $lg ]["name"] = "*NAME*.*EXT*";
    $specialUpload["s_paragraphe"][ "paragraphe_img_1_" . $lg ]["web"] = "/fichier/s_rubrique/*fk_rubrique_id*/";

    $specialUpload["s_paragraphe"][ "paragraphe_file_1_" . $lg ]["system"] = $basePath . "/fichier/s_rubrique/*fk_rubrique_id*/";
    $specialUpload["s_paragraphe"][ "paragraphe_file_1_" . $lg ]["name"] = "*NAME*.*EXT*";
    $specialUpload["s_paragraphe"][ "paragraphe_file_1_" . $lg ]["web"] = "/fichier/s_rubrique/*fk_rubrique_id*/";
}


/**
 * Liste des directives supplémentaires pour tinymce
 * @example $_Gconfig['tinyMce']['addConf']['theme_advanced_blockformats'] = 'p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,code,samp';
 */
$_Gconfig['tinyMce']['conf'] = array(
    'mode'                      => "exact",
    'theme'                     => "modern",
    'language'                  => "fr_FR",
    'browser_spellcheck'        => "true",
    'width'                     => '100%',
    'plugins'                   => "autoresize autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking save table contextmenu directionality emoticons template paste textcolor responsivefilemanager",
    'entity_encoding'           => "raw",
    'content_css'               => BU . "/css/baseadmin.css",
    'theme_advanced_styles'     => '',
    'toolbar1'                  => "pastetext insertfile undo redo | styleselect | bold italic | bullist numlist outdent indent | link image responsivefilemanager ",
    'toolbar2'                  => "",
    'toolbar3'                  => "",
    'browser_spellcheck'        => 'true',
    'visual'                    => 'true',
    'resize'                    => 'both',
    'paste_as_text'             => 'true',
    'external_filemanager_path' => ADMIN_URL . '/filemanager/',
    'filemanager_title'         => "Médiathèque",
    'external_plugins'          => array("filemanager" => ADMIN_URL . "/filemanager/plugin.min.js"),
    'relative_urls'             => 0,
    'insertdatetime_formats'    => array("%H:%M:%S", "%Y-%m-%d", "%I:%M:%S %p", "%D", '%d/%m/%Y'),
    'content_css'               => array('/css/global.css', '/css/specialadmin.css'
    ),
    'body_id'                   => 'paragraphes',
    'body_class'                => 'paragraphe',
    'style_formats'             => ""
        . "[{title: 'Chapeau',inline:'span', classes:'para-chapeau'},"
        . "{title: 'Légende', inline:'span', classes:'para-legende'}]"
);

