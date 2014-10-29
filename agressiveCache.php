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
    '_action/editer', // Front admin, version de prévisu
    'laLogout', // Déconnexion d'ocms_login
    'jsvote', // Déconnexion d'ocms_login
    'mon-espace', // Déconnexion d'ocms_login
    'paiement_securise'  // Déconnexion d'ocms_login
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
$parts = explode('?', $_SERVER['REQUEST_URI']);

function sanitize($string = '', $is_filename = FALSE)
{
    // Replace all weird characters with dashes
    //$string = preg_replace('/[^\w\-' . ($is_filename ? '~_\.' : '') . ']+/u', '-', $string);
    $string = preg_replace('/[^a-zA-Z0-9-_\.\/]/', '', $string);

    // Only allow one dash separator at a time (and make string lowercase)
    return mb_strtolower(preg_replace('/--+/u', '-', $string), 'UTF-8');
}

$agressiveFname = dirname(__FILE__) . '/include/cache_agr/' . sanitize($_SERVER['HTTP_HOST']) . '/';
if (empty($parts[0])) {
    $parts[0] = 'index';
} else if (substr($parts[0], -1) === '/') {
    $parts[0] = sanitize($parts[0]) . 'index';
} else {
    $parts[0] = sanitize($parts[0]) . '/index';
}
$agressiveFname .= $parts[0];
if (!empty($parts[1])) {
    $agressiveFname .= '--' . sanitize($parts[1]);
}

$agressiveCacheContent = false;


/**
 * On démarre hélas obligatoirement la session maintenant pour récupérer l'info de connexion ou non ...
 * On peut le supprimer pour les sites sans ocms_login
 */
ini_set('session.gc_maxlifetime', 7200);
session_start();

/**
 * Vérification de la présence chaque élément d'un array $arr dans un string $word
 *
 * @param array $arr
 * @param string $word
 * @return bool Le string a été trouvé ou non
 */
function newarrayInWord($arr, $word)
{
    while (list(, $v) = each($arr)) {
        if (strstr($word, $v) !== false)
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
if ($agressiveUseCache &&
    empty($_REQUEST['nocache']) &&
    empty($_REQUEST['_version']) &&
    empty($_SESSION['ocms_login']['utilisateur_id']) &&
    !count($_POST)
) {
    /**
     * Si le fichier de cache existe déjà on continue
     */
    if (is_file($agressiveFname)) {
        $mtime = filemtime($agressiveFname);
        /**
         * Si le fichier de cache date de moins de $agressiveCacheFor,
         * et est plus récent que la dernière modification de l'admin
         */
        $next = file_get_contents('./include/temoinnextcache');

        $t = !$next || time() <= $next;

        if ($mtime >= (time() - $agressiveCacheFor) && $mtime >= (filemtime('./include/temoinchange')) && $t) {
            /**
             * On définit les headers minimaux
             */
            header('Content-Type: text/html; charset=utf-8');
            header('Last-Modified: ' . date('r', filemtime($agressiveFname)));
            header('Expires: ' . date('r', filemtime($agressiveFname) + $agressiveCacheFor));
            /**
             * Une information de debug utile mais peut-être enlevée
             */
            header('X-cache: Agressive');

            /**
             * Et on retourne le fichier
             */
            //readfile($agressiveFname);
            $handle = fopen($agressiveFname, "r");
            $i = 0;
            $headers = '';
            while ($i <= 5000) {
                $c = fread($handle, 1);
                $headers .= $c;
                if ($c === '-') {
                    if (substr($headers, -15) === '---HEADERSEND--') {
                        $headers = explode("\n", substr($headers, 0, -15));
                        foreach ($headers as $v) {
                            header($v);
                        }
                        break;
                    }
                }
            }

            if ($i === 5000) {

                rewind($handle);
            }

            fpassthru($handle);

            die();
        } else if (!$t) {
            file_put_contents('./include/temoinchange', date('c'));
        }
    }

    /**
     * Enregistrement dans un fichier de cache de l'ensemble de la page à la fin de sa génération
     */
    function agressiveCacheShutdown()
    {

        global $agressiveUseCache, $agressiveFname, $site;
        if ($agressiveUseCache && $agressiveFname && !$site->isCurrent404) {

            $content = ob_get_contents();
            global $agressiveCacheContent;
            if ($agressiveCacheContent !== false) {
                $content = $agressiveCacheContent;
            }

            if ($content && strlen($content) > 1) {
                $path = dirname($agressiveFname);
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $hds = headers_list();
                $heads = '';
                foreach ($hds as $v) {
                    if (strstr(strtolower($v), 'set-cookie') === false) {
                        $heads .= $v . "\n";
                    }
                }
                $heads .= 'X-cache: Agressive';


                file_put_contents($agressiveFname, $heads . '---HEADERSEND--' . $content);
            }
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
