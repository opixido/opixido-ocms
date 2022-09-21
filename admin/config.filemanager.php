<?php

/**
 * Pour avoir BU
 */
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
require(dirname(__FILE__) . '/../include/config/config.server.php');

if (empty($_GET['field_id'])) {
    /**
     * On est initié depuis TinyMce
     * On a donc besoin du chemin complet
     */
    $config['upload_dir'] = BU . '/fichier/rte/';
} else {
    /**
     * On est appellé depuis un champ donc juste le chemin depuis la racine du site
     */
    $config['upload_dir'] = '/fichier/rte/';
}

/**
 * Chemin relatif vers le dossier
 */
$config['current_path'] = realpath(dirname(__FILE__) . '/../fichier/rte/') . '/';
$config['current_path'] = '../../fichier/rte/';

/**
 * Thumbs dans dossier de cache admin
 */
$config['thumbs_base_path'] = '../c/';

/**
 * Lague fr_FR ...
 */
$config['default_language'] = empty($_SESSION['lg']) ? 'fr_FR' : $_SESSION['lg'] . '_' . strtoupper($_SESSION['lg']);

/**
 * Dossier partagé général
 */
$config['ocms_SharedFolder'] = 'General';

/**
 * Pour ne pas avoir http://... dans le chemin retourné
 */
$config['base_url'] = false;

/**
 * Taille maxi par fichier
 * */
$config['MaxSizeUpload'] = '1024';


$config['transliteration'] = true;

$config['convert_spaces'] = true;
$config['replace_with'] = '-';

$config['tui_defaults_config']['usageStatistics'] = false;

/**
 * Sinon c'est qu'on est pas connecté
 */
if (empty($_SESSION['gs_admin_id'])) {
    die('nop');
}

/**
 * Si le dossier utilisateur n'existent pas on les créé
 */
if (!file_exists($config['current_path'] . $_SESSION['gs_adminuser'])) {
    mkdir($config['current_path'] . $_SESSION['gs_adminuser']);
    file_put_contents($config['current_path'] . $_SESSION['gs_adminuser'] . '/config.php', '<?php 
if($_SESSION[\'gs_admin_id\'] == "' . ($_SESSION['gs_admin_id']) . '" || $_SESSION["superAdmin"]) { 
       return [\'delete_files\' => true,
        \'create_folders\' => true,
        \'delete_folders\' => true,
        \'upload_files\' => true,
        \'rename_files\' => true,
        \'rename_folders\' => true,
        \'duplicate_files\' => true,
        \'create_text_files\' => true,
        \'edit_text_files\' => true,
        \'copy_cut_files\' => true,
        \'copy_cut_dirs\' => true];
} else {
    return [
        \'delete_files\' => false,
        \'create_folders\' => false,
        \'delete_folders\' => false,
        \'upload_files\' => false,
        \'rename_files\' => false,
        \'rename_folders\' => false,
        \'duplicate_files\' => false,
        \'create_text_files\' => false,
        \'edit_text_files\' => false,
        \'copy_cut_files\' => false,
        \'copy_cut_dirs\' => false,
    ];
}');
}

/**
 * Si le dossier général n'existe pas on le créé
 */
if (!file_exists($config['current_path'] . $config['ocms_SharedFolder'])) {
    mkdir($config['current_path'] . $config['ocms_SharedFolder']);
    file_put_contents($config['current_path'] . $config['ocms_SharedFolder'] . '/config.php', '<?php 
    
if($_SESSION[\'superAdmin\']) { 
       return [\'delete_files\' => true,
        \'create_folders\' => true,
        \'delete_folders\' => true,
        \'upload_files\' => true,
        \'rename_files\' => true,
        \'rename_folders\' => true,
        \'duplicate_files\' => true,
        \'create_text_files\' => true,
        \'edit_text_files\' => true,
        \'copy_cut_files\' => true,
        \'copy_cut_dirs\' => true];
} else {
    return [\'delete_files\' => false,
        \'create_folders\' => false,
        \'delete_folders\' => false,
        \'upload_files\' => false,
        \'rename_files\' => false,
        \'rename_folders\' => false,
        \'duplicate_files\' => false,
        \'create_text_files\' => false,
        \'edit_text_files\' => false,
        \'copy_cut_files\' => false,
        \'copy_cut_dirs\' => false];
}');
}