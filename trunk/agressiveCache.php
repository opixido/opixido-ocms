<?php

/**
 * On utilise l'agressive cache ?
 */
$agressiveUseCache = false;

/**
 * Nombre de secondes avant de forcer une regénération de la page
 */
$agressiveCacheFor = 3600 * 2;

/**
 * Par défaut on ne met pas en cache les frontAdmin ni les actions de déconnexion de compte
 */
$tabNoCache = array(
    '_action/editer',	// Front admin, version de prévisu
    'laLogout',		// Déconnexion d'ocms_login
    'jsvote'		// Déconnexion d'ocms_login
    // ... Autres à ajouter en fonction des besoins 
    // ... Pages avec affichage aléatoire
    // ... Pages avec contenu non maitrisé
    // ... Pages avec un contenu d'internaute    
);

/**
 * Url complète de la page en cours
 */
$url = ('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

/**
 * Et son pendant md5 pour le fichier de cache
 */
$agressiveFname = './include/cache_agr/page_' . md5($url);

/**
 * On démarre hélas obligatoirement la session maintenant pour récupérer l'info de connexion ou non ...
 * On peut le supprimer pour les sites sans ocms_login 
 */
session_start();

/**
 * Vérification de la présence chaque élément d'un array $arr dans un string $word
 * 
 * @param array $arr
 * @param string $word
 * @return bool Le string a été trouvé ou non 
 */
function newarrayInWord($arr, $word) {
    while (list(, $v) = each($arr)) {
	if (strstr($word,$v) !== false)
	    return true;
    }
    return false;
}

/**
 * Si on est dans une page qu'on ne doit surtout pas cacher
 */
if (newarrayInWord($tabNoCache, $url)) {
    $agressiveUseCache = false;
}

/**
 * Si on cache, qu'on ne demande pas explicitement à ne pas cacher, qu'on est pas connecté, et qu'on a pas soumis de formulaire :
 */
if ($agressiveUseCache && empty($_REQUEST['nocache']) && empty($_SESSION['ocms_login']['utilisateur_id']) && !count($_POST)) {
    /**
     * Si le fichier de cache existe déjà on continue
     */
    if (is_file($agressiveFname)) {
	$mtime = filemtime($agressiveFname);
	/**
	 * Si le fichier de cache date de moins de $agressiveCacheFor, 
	 * et est plus récent que la dernière modification de l'admin
	 */
	if ($mtime >= (time() - $agressiveCacheFor) && $mtime >= (@filemtime('./include/temoinchange'))) {
	    /**
	     * On définit les headers minimaux
	     */
	    header('Content-Type: text/html; charset=utf-8');
	    header('Last-Modified: ' . date('r', filemtime($agressiveFname)));
	    header('Expires: ' . date('r', filemtime($agressiveFname)+$agressiveCacheFor));
	    /**
	     * Une information de debug utile mais peut-être enlevée
	     */
	    header('X-cache: Agressive');

	    /**
	     * Et on retourne le fichier
	     */
	    readfile($agressiveFname);
	    die();
	}
    }

    /**
     * Enregistrement dans un fichier de cache de l'ensemble de la page à la fin de sa génération
     */
    function agressiveCacheShutdown() {
	
	global $agressiveUseCache, $agressiveFname;
	if ($agressiveUseCache && $agressiveFname) {
	    @file_put_contents($agressiveFname, ob_get_contents());
	}
    }

    /**
     * Appel automatique en fin de script
     */
    register_shutdown_function('agressiveCacheShutdown');
    
    /**
     * On commence à loggé afin de mettre en cache à la fin du script ...
     */
    ob_start();
    
} else {
    /**
     * Sinon on utilise pas le cache et on ne met pas en cache le résultat
     * On pourrait tout aussi bien supprimer ce bloc 
     * Mais comme je suis bavard ça me fait plaisir ...
     */
    $agressiveUseCache = false;
}
