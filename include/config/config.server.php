<?php

/**
 * VOIR EN BAS DU FICHIER POUR LA CONFIGURATION SERVEUR
 */
/**
 * Variables globales
 */
global $sqlTime, $baseWebPath, $_Gconfig,$_bdd_host,$_bdd_type,$_bdd_pwd,$_bdd_bdd,$_bdd_user;
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
define('crypto_key', 'b8528f0056280e424c4f7f7f1f92dde59017243');

/**
 * Clef unique pour ce site
 */
define('UNIQUE_SITE', '632b3084db5a4');


/**
 * Le site est à la racine
 * Ou bien dans un sous dossier
 */
$baseWebPath = '/';


/**
 * Timezone par défaut
 */
date_default_timezone_set('Europe/Paris');

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
if(!defined('IN_ADMIN')) {
    define('IN_ADMIN',true);
}
if (IN_ADMIN) {
    //define('BU',str_replace(array('/admin/index.php','/index.php','/admin/ImageManager/editorFrame.php'),array('','',''),$_SERVER["SCRIPT_NAME"]));
    define('BU', substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/admin/')));
} else {
    define('BU', str_replace('/index.php', '', $_SERVER["SCRIPT_NAME"]));
}

/**
 * Test pour les serveurs en VirtualDocRoot dans Apache qui retourne une mauvaise valeur dans
 * DOCUMENT_ROOT
 * On essaie alors de la calculer
 */
if ($_SERVER['SCRIPT_FILENAME'] != ($_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME'])) {
    $_SERVER['DOCUMENT_ROOT'] = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']);
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
 * Gestion des chemins d'accès aux includes
 */
$pathHere = str_replace(SEP . 'config', '', dirname(__FILE__));

/**
 * Chemin absolu vers le dossier ..../include/
 *
 */
define('INCLUDE_PATH', $pathHere);


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
if ($_SERVER['SERVER_ADDR'] == "127.0.0.1") {

    /**
     * Connexion à la base de donnée
     */
    $_bdd_user = 'root';
    $_bdd_pwd = '';
    $_bdd_bdd = 'ocms81';
    $_bdd_host = 'localhost';
    $_bdd_type = 'mysqli';

    $_Gconfig['debugIps'] =array('192.168.1.');

} else {

    /**
     * Rajouter la configuration propre au serveur de PROD ici
     */
    ini_set("display_errors", "Off");

    $_bdd_user = '';
    $_bdd_pwd = '';
    $_bdd_bdd = '';
    $_bdd_host = '';
    $_bdd_type = '';
}

