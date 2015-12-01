<?php

/**
 * Pour avoir BU
 */
require(dirname(__FILE__) . '/../../../include/config/config.server.php');

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
 * Sinon c'est qu'on est pas connecté
 */
if (empty($_SESSION['gs_admin_id'])) {
    die('nop');
}

/**
 * Si le dossier utilisateur n'existent pas on les créé
 */
if (!file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../' . $config['current_path'] . $_SESSION['gs_admin_id'])) {
    mkdir(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../' . $config['current_path'] . $_SESSION['gs_admin_id']);
    file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../' . $config['current_path'] . $_SESSION['gs_admin_id'] . '/config.php', '<?php if($_SESSION[\'gs_admin_id\'] == "' . ($_SESSION['gs_admin_id']) . '") { $delete_files = true;$create_folders = true;$delete_folders = true;$upload_files = true;$rename_files = true;$rename_folders = true;$duplicate_files = true;} else { $delete_files = false;$create_folders = false;$delete_folders = false;$upload_files = false;$rename_files = false;$rename_folders = false;$duplicate_files = false;}');
}

/**
 * Si le dossier général n'existe pas on le créé
 */
if (!file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../' . $config['current_path'] . $config['ocms_SharedFolder'])) {
    mkdir(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../' . $config['current_path'] . $config['ocms_SharedFolder']);
    file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../' . $config['current_path'] . $config['ocms_SharedFolder'] . '/config.php', '<?php if($_SESSION[\'superAdmin\']) { $delete_files = true;$create_folders = true;$delete_folders = true;$upload_files = true;$rename_files = true;$rename_folders = true;$duplicate_files = true;} else { $delete_files = false;$create_folders = false;$delete_folders = false;$upload_files = false;$rename_files = false;$rename_folders = false;$duplicate_files = false;}');
}