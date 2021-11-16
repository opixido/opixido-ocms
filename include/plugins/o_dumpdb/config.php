<?php

/**
 * Plugin d'export de BDD
 * Basé sur https://github.com/dg/MySQL-dump
 * Voir le dépot pour la doc
 */
global $_Gconfig, $admin_trads;

/**
 * Liste des tables pour lesquelles faire un traitement particulier
 */
$_Gconfig['o_dumpdb']['tables'] = [
    's_log_action' => MySQLDump::DROP | MySQLDump::CREATE,
    'os_obj' => MySQLDump::DROP | MySQLDump::CREATE,
    'os_recherches' => MySQLDump::DROP | MySQLDump::CREATE,
    'os_rel' => MySQLDump::DROP | MySQLDump::CREATE,
    'os_tables' => MySQLDump::DROP | MySQLDump::CREATE,
    'os_word' => MySQLDump::DROP | MySQLDump::CREATE
];

/**
 * On déclare la fonction
 */
$_Gconfig['globalActions'][] = 'dumpDb';


/**
 * Et des trads pour faire joli
 */
$admin_trads['dumpDb']['fr'] = 'Sauvegarder la BDD';
$admin_trads['picto_dumpDb']['fr'] = 'share';