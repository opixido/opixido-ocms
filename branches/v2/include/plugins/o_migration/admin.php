<?php

global $_Gconfig;
$_Gconfig['globalActions'][] = 'dev_migratePhpSql';

function dev_migratePhpSql() {
    
    echo '<a href="?globalAction=dev_migratePhpSql&migrate_action=add" class="btn">Ajouter une migration</a> ';
    echo ' <a href="?globalAction=dev_migratePhpSql&migrate_action=migrate" class="btn btn_primary">Migrer à la dernière version</a> <pre>';
    
    $GLOBALS['gb_obj']->includeFile('migrate.php','plugins/o_migration');
    $migrations = get_migrations();   
    print_r($migrations);
    
    echo '</pre>';
    
}
