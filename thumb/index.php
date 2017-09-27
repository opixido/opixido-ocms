<?php

/*
 * Kesako ?
 * Un cache buster je pense ...
 */
if (!empty($_REQUEST['c'])) {
    unset($_REQUEST['c']);
}

/**
 * On rempli le request avec tout ce qu'il faut en parsant l'URL propre
 */
if (empty($_REQUEST)) {

    $x = explode('?', ($_SERVER['REQUEST_URI']));
    $_SERVER['REQUEST_URI'] = $x[0];

    /**
     * le SRC est à la toute fin car il ajoute des / de partout
     */
    $x = explode('/src/', $x[0]);
    $src = '/' . urldecode($x[1]);

    /**
     * Donc avant c'est que les paramètres
     */
    $params = explode('/', $x[0]);

    /**
     * On vide tout et on prépare
     */
    $_GET = $_REQUEST = array();
    /**
     * On reconstruit GET REQUEST ET QUERY_STRING
     */
    $_GET['src'] = $_REQUEST['src'] = $src;
    $_SERVER['QUERY_STRING'] = 'src=' . $src;

    foreach ($params as $v) {
        /**
         * Séparateur de clef/valeur
         */
        $x = explode('__', $v);

        if (!empty($x[1])) {
            /**
             * Si c'est un array on remplit correctement le GET
             */
            if (strstr($x[0], '[]')) {
                $x[0] = str_replace('[]', '', $x[0]);
                $_GET[$x[0]][] = $_REQUEST[$x[0]][] = urldecode($x[1]);
                $_SERVER['QUERY_STRING'] .= '&' . $x[0] . '[]=' . $x[1];
            } else {
                $_GET[$x[0]] = $_REQUEST[$x[0]] = $x[1];
                $_SERVER['QUERY_STRING'] .= '&' . $x[0] . '=' . $x[1];
            }
        }
    }
}

/**
 * Et zou
 */
require('phpThumb.php');