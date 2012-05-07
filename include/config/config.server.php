<?php

/**
 * VOIR EN BAS DU FICHIER POUR LA CONFIGURATION SERVEUR
 */
/**
 * Variables globales
 */
global $sqlTime, $baseWebPath, $_Gconfig;

/**
 * Tableau général de configuration
 */
$_Gconfig = array();

/**
 * Variable globale de calcul de temps SQL
 */
$sqlTime = 0;


/**
 * On préfère tout ca pour éviter les problèmes
 */
ini_set('magic_quotes_runtime', 'Off');
ini_set('magic_quotes_sybase', 'Off');
ini_set('magic_quotes_gpc', 'Off');
ini_set("display_errors", "On");


/**
 * Redéfinit plus tard dans le genSite en fonction de la langue demandée
 */
setlocale(LC_ALL, 'fr_FR');

/**
 * Pour l'encodage des mots de passe dans l'administration
 *
 */
define('crypto_key', '88f8c4aa705ac896a6d735696a270c856330101');

/**
 * Clef unique pour ce site
 */
define('UNIQUE_SITE', '4e984408af7ac');


/**
 * Le site est à la racine
 * Ou bien dans un sous dossier
 */
$baseWebPath = '/';


/**
 * Protocole utilisé : (HTTP)
 */
$protocole = array_key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == 'on' ? 'https' : 'http';
$_Gconfig['protocole'] = $protocole;

/**
 * Langue par defaut parmis les langues complètes
 *
 */
define('LG_DEF', 'fr');
define('SEP', DIRECTORY_SEPARATOR);

/**
 * Base URL
 * On la construit dynamiquement 
 * mais on pourrait l'écrire en dur par serveur ...
 * Au choix
 */
if (IN_ADMIN) {
    //define('BU',str_replace(array('/admin/index.php','/index.php','/admin/ImageManager/editorFrame.php'),array('','',''),$_SERVER["SCRIPT_NAME"]));
    define('BU', substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/admin/')));

} else {
    define('BU', str_replace('/index.php', '', $_SERVER["SCRIPT_NAME"]));
}


/**
 * Adresse de l'administration
 * @example define('ADMIN_URL','http://www.site.com/admin/');
 */
define('ADMIN_URL', BU . '/admin/'); //

/**
 * Adresse principale du site
 * @example http://www.site.com/
 */
define('WEB_URL', BU); //


/**
 * Sur quel domaine poser les cookies ?
 * @example $_Gconfig['session_cookie_server'] = "site.com";
 *
 * @deprecated 
 * 
 * $_Gconfig['session_cookie_server'] = "";	
 */
/**
 * Pas utilisé
 *
 */
define('WEBADMIN_URL', '');
$_Gconfig['baseWebPath'] = WEB_URL;

/**
 * Générateur d'images textes à partir de typos
 *
 */
define('IMG_GENERATOR', BU . '/imgps.php');

/**
 * Thumbnail générator
 *
 */
define('THUMBPATH', BU . '/thumb/');

/**
 * Gestion des chemins d'accès aux includes et ADODB
 */
$pathHere = str_replace(SEP . 'config', '', dirname(__FILE__));

/**
 * Chemin absolu vers le dossier ..../include/
 *
 */
define('INCLUDE_PATH', $pathHere);

/**
 * Chemin vers ADODB pour inclusion des sous fichiers
 * d'ADODB
 * */
define('ADODB_DIR', INCLUDE_PATH . SEP . 'adodb5' . SEP);

/**
 * Chemin vers le dossier où l'on upload en FTP les fichiers trop gros
 */
$_Gconfig['ftpUpload_path'] = INCLUDE_PATH . SEP . 'upload' . SEP;

/**
 * Chemin d'inclusion par défaut
 */
$_Gconfig['basePath'] = realpath(INCLUDE_PATH . SEP . '..' . SEP);


/**
 * Réattribution des droits corrects des fichiers apres upload
 * */
$_Gconfig['chownFiles'] = false;
$_Gconfig['chgrpFiles'] = false;
$_Gconfig['chmodFiles'] = false;


/**
 * Si les fichiers / CSS / etc doivent êtres chargés d'un CDN
 */
$_Gconfig['CDN'] = '';


/**
 * On pourait définir des variables ci-dessus dans les blocs ci-dessous 
 * pour une configuration propre à chaque serveur.
 */

if ($_SERVER['SERVER_ADDR'] == "192.168.2.4" || empty($_SERVER['REMOTE_ADDR'])) {

    require('shared.config.opixido.php');


} else {

    require('shared.config.dev.php');
    /**
     * Rajouter la configuration propre au serveur de PROD ici
     */
   // ini_set("display_errors", "Off");


    
}


