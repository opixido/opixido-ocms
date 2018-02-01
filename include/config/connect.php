<?php

global $_bdd_host, $_bdd_type, $_bdd_pwd, $_bdd_bdd, $_bdd_user;

if (!defined('BU')) {
    define('BU', substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/admin/')));
}


/**
 * ADOConnection
 */
global $co;


if ($_bdd_type) {

    /**
     * @var $co ADODB_mysqli
     */
    $co = ADONewConnection($_bdd_type);


    /**
     * Connexion à la base de donnée
     */
    $connec = @$co->Connect($_bdd_host, $_bdd_user, $_bdd_pwd, $_bdd_bdd);

    /**
     * Si on a pas la connexion à la BDD
     */
    if (!$connec) {
        echo 'No MySQL Connection' . "\n\n<br/>";
        echo $co->ErrorMsg();
        $co = false;
        die();
        return;
    }


    /**
     * Pour que ADODB ne retourne que les enregistrements avec les clefs
     * et pas les index
     * Limite l'utilisation memoire
     */
    $co->SetFetchMode(ADODB_FETCH_ASSOC);


    $co->Execute('SET NAMES  utf8');

    $co->Execute('SET  SESSION sql_mode ="ALLOW_INVALID_DATES"');

} else if (!IN_ADMIN) {
    echo 'Please configure first <a href="./admin/">CONFIGURATION</a>';
    die();
}
