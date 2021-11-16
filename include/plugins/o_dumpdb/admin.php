<?php


/**
 * Export Database
 * @throws Exception
 */
function dumpDb()
{
    global $_Gconfig, $_bdd_host, $_bdd_type, $_bdd_pwd, $_bdd_bdd, $_bdd_user;

    $db = new mysqli($_bdd_host, $_bdd_user, $_bdd_pwd, $_bdd_bdd);
    $dump = new MySQLDump($db);

    /**
     * Récupération de la config
     */

    $dump->tables = array_merge($dump->tables, $_Gconfig['o_dumpdb']['tables']);


    /**
     * Suppression de tout le code HTML qui a pu être sorti avant
     */
    ob_clean();
    ob_end_clean();

    /**
     * Header de téléchargement
     */
    header("Expires: 0");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Content-type: application/file");
    header('Content-disposition: attachment; filename="' . $_bdd_bdd . '-' . date('Y-m-d-H-i-s') . '.sql"');

    /**
     * Go!
     */
    $dump->write();

    /**
     * Pour être certain qu'on aura rien après
     */
    die();
}